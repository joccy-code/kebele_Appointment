<?php
session_start();
require_once '../includes/utils.php';
require_once '../connection.php';

// Check if user is logged in as resident
if (!isset($_SESSION['user']) || $_SESSION['usertype'] != 'r') {
    header('Location: ../login.php');
    exit();
}

// Get resident ID from database using email
$useremail = $_SESSION['user'];
$sqlmain = "SELECT resid FROM resident WHERE resemail = ?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();
$userfetch = $result->fetch_assoc();

if (!$userfetch) {
    header('Location: ../login.php');
    exit();
}

$residentId = $userfetch['resid'];
$selectedService = null;
$selectedDate = null;
$availableSlots = [];
$error = null;
$success = null;

// Get all services
$services = getAllServices();
$categories = array_unique(array_column($services, 'category'));

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['select_service'])) {
        $selectedService = getServiceDetails($_POST['service_id']);
    } elseif (isset($_POST['select_date'])) {
        $selectedService = getServiceDetails($_POST['service_id']);
        $selectedDate = $_POST['date'];
        $availableSlots = getAvailableTimeSlots($selectedDate, $selectedService['service_id']);
    } elseif (isset($_POST['book_appointment'])) {
        $result = createAppointment(
            $residentId,
            $_POST['service_id'],
            $_POST['date'],
            $_POST['slot_id']
        );
        
        if ($result['success']) {
            $success = $result['message'];
            // Reset selections
            $selectedService = null;
            $selectedDate = null;
            $availableSlots = [];
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - eKeble</title>
    <link rel="stylesheet" href="../css/components.css">
    <link rel="stylesheet" href="../css/animations.css">
    <style>
        .booking-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--spacing-lg);
        }
        
        .booking-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--spacing-xl);
            position: relative;
        }
        
        .booking-step {
            flex: 1;
            text-align: center;
            padding: var(--spacing-md);
            background: var(--white);
            border-radius: var(--border-radius-md);
            margin: 0 var(--spacing-sm);
            position: relative;
            z-index: 1;
        }
        
        .booking-step.active {
            background: var(--primary-color);
            color: var(--white);
        }
        
        .booking-step.completed {
            background: var(--success-color);
            color: var(--white);
        }
        
        .service-categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
        }
        
        .service-card {
            background: var(--white);
            border-radius: var(--border-radius-md);
            padding: var(--spacing-lg);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }
        
        .service-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .service-card.selected {
            border: 2px solid var(--primary-color);
        }
        
        .calendar-container {
            background: var(--white);
            border-radius: var(--border-radius-md);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: var(--spacing-xs);
        }
        
        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .calendar-day.disabled {
            background: var(--light-gray);
            color: var(--dark-gray);
            cursor: not-allowed;
        }
        
        .calendar-day.selected {
            background: var(--primary-color);
            color: var(--white);
        }
        
        .time-slots-container {
            background: var(--white);
            border-radius: var(--border-radius-md);
            padding: var(--spacing-lg);
        }
        
        .time-slot {
            padding: var(--spacing-md);
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius-sm);
            margin-bottom: var(--spacing-sm);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .time-slot:hover:not(.disabled) {
            background: var(--light-gray);
        }
        
        .time-slot.disabled {
            background: var(--light-gray);
            color: var(--dark-gray);
            cursor: not-allowed;
        }
        
        .time-slot.selected {
            background: var(--primary-color);
            color: var(--white);
        }
        
        .booking-summary {
            background: var(--white);
            border-radius: var(--border-radius-md);
            padding: var(--spacing-lg);
            margin-top: var(--spacing-lg);
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <h1>Book an Appointment</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <div class="booking-steps">
            <div class="booking-step <?php echo !$selectedService ? 'active' : 'completed'; ?>">
                1. Select Service
            </div>
            <div class="booking-step <?php echo $selectedService && !$selectedDate ? 'active' : ($selectedDate ? 'completed' : ''); ?>">
                2. Choose Date
            </div>
            <div class="booking-step <?php echo $selectedDate ? 'active' : ''; ?>">
                3. Select Time
            </div>
        </div>
        
        <?php if (!$selectedService): ?>
            <!-- Step 1: Service Selection -->
            <h2>Select a Service</h2>
            <div class="service-categories">
                <?php foreach ($categories as $category): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($category); ?></h3>
                        <?php
                        $categoryServices = getServicesByCategory($category);
                        foreach ($categoryServices as $service):
                        ?>
                            <form method="POST" class="service-card">
                                <input type="hidden" name="service_id" value="<?php echo $service['service_id']; ?>">
                                <h4><?php echo htmlspecialchars($service['name']); ?></h4>
                                <p><?php echo htmlspecialchars($service['description']); ?></p>
                                <p><small>Duration: <?php echo $service['duration_minutes']; ?> minutes</small></p>
                                <button type="submit" name="select_service" class="btn btn-primary">Select</button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
        <?php elseif (!$selectedDate): ?>
            <!-- Step 2: Date Selection -->
            <h2>Select a Date for <?php echo htmlspecialchars($selectedService['name']); ?></h2>
            <div class="calendar-container">
                <div class="calendar-header">
                    <button class="btn btn-secondary" id="prevMonth">&lt; Previous</button>
                    <h3 id="currentMonth">Loading...</h3>
                    <button class="btn btn-secondary" id="nextMonth">Next &gt;</button>
                </div>
                <div class="calendar-grid" id="calendarGrid">
                    <!-- Calendar will be populated by JavaScript -->
                </div>
            </div>
            
        <?php else: ?>
            <!-- Step 3: Time Slot Selection -->
            <h2>Select a Time Slot</h2>
            <div class="booking-summary">
                <h3>Booking Summary</h3>
                <p><strong>Service:</strong> <?php echo htmlspecialchars($selectedService['name']); ?></p>
                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($selectedDate)); ?></p>
            </div>
            
            <div class="time-slots-container">
                <?php if (empty($availableSlots)): ?>
                    <div class="alert alert-warning">No available time slots for this date.</div>
                <?php else: ?>
                    <form method="POST">
                        <input type="hidden" name="service_id" value="<?php echo $selectedService['service_id']; ?>">
                        <input type="hidden" name="date" value="<?php echo $selectedDate; ?>">
                        
                        <?php foreach ($availableSlots as $slot): ?>
                            <div class="time-slot">
                                <input type="radio" name="slot_id" value="<?php echo $slot['slot_id']; ?>" 
                                       id="slot_<?php echo $slot['slot_id']; ?>" required>
                                <label for="slot_<?php echo $slot['slot_id']; ?>">
                                    <?php echo formatTime($slot['start_time']); ?> - 
                                    <?php echo formatTime($slot['end_time']); ?>
                                    <small>(<?php echo $slot['available']; ?> slots available)</small>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        
                        <button type="submit" name="book_appointment" class="btn btn-primary">Book Appointment</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Calendar functionality
        document.addEventListener('DOMContentLoaded', function() {
            const calendarGrid = document.getElementById('calendarGrid');
            const currentMonthElement = document.getElementById('currentMonth');
            const prevMonthButton = document.getElementById('prevMonth');
            const nextMonthButton = document.getElementById('nextMonth');
            
            let currentDate = new Date();
            let selectedDate = null;
            
            function renderCalendar() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                
                // Update month display
                currentMonthElement.textContent = new Date(year, month, 1).toLocaleString('default', { 
                    month: 'long', 
                    year: 'numeric' 
                });
                
                // Clear calendar
                calendarGrid.innerHTML = '';
                
                // Add day headers
                const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                days.forEach(day => {
                    const dayHeader = document.createElement('div');
                    dayHeader.className = 'calendar-day';
                    dayHeader.textContent = day;
                    calendarGrid.appendChild(dayHeader);
                });
                
                // Get first day of month and total days
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                
                // Add empty cells for days before first of month
                for (let i = 0; i < firstDay; i++) {
                    const emptyDay = document.createElement('div');
                    emptyDay.className = 'calendar-day';
                    calendarGrid.appendChild(emptyDay);
                }
                
                // Add days of month
                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(year, month, day);
                    const dayElement = document.createElement('div');
                    dayElement.className = 'calendar-day';
                    dayElement.textContent = day;
                    
                    // Disable weekends
                    if (date.getDay() === 0 || date.getDay() === 6) {
                        dayElement.classList.add('disabled');
                    } else {
                        dayElement.addEventListener('click', () => {
                            if (!dayElement.classList.contains('disabled')) {
                                // Remove selected class from all days
                                document.querySelectorAll('.calendar-day').forEach(el => {
                                    el.classList.remove('selected');
                                });
                                
                                // Add selected class to clicked day
                                dayElement.classList.add('selected');
                                
                                // Submit form with selected date
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.innerHTML = `
                                    <input type="hidden" name="service_id" value="<?php echo $selectedService['service_id']; ?>">
                                    <input type="hidden" name="date" value="${date.toISOString().split('T')[0]}">
                                    <input type="hidden" name="select_date" value="1">
                                `;
                                document.body.appendChild(form);
                                form.submit();
                            }
                        });
                    }
                    
                    calendarGrid.appendChild(dayElement);
                }
            }
            
            // Initialize calendar
            renderCalendar();
            
            // Add month navigation
            prevMonthButton.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
            });
            
            nextMonthButton.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            });
        });
    </script>
</body>
</html> 