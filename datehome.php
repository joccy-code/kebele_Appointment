<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seto Semero - Appointment Date and Time</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            background-color: #f5f7fb;
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            border-right: 1px solid #e5e7eb;
            padding: 1rem;
        }
        
        .sidebar-header {
            margin-bottom: 2rem;
        }
        
        .sidebar-header h1 {
            font-weight: bold;
            font-size: 1.25rem;
            color: #1f2937;
        }
        
        .sidebar-header p {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #374151;
            transition: all 0.2s;
            text-decoration: none;
            margin-bottom: 0.5rem;
        }
        
        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
        
        .nav-item.active {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-radius: 8px;
        }
        
        .nav-item i {
            margin-right: 0.75rem;
        }
        
        .main-content {
            flex-grow: 1;
            padding: 30px;
            margin-left: 250px;
            background-color: #f5f7fb;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .heading {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .notification-icon {
            background-color: #3b82f6;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            cursor: pointer;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 16px;
        }
        
        .user-role {
            color: #6b7280;
            font-size: 14px;
        }
        
        /* Progress Steps Styles */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }
        
        .progress-line {
            position: absolute;
            top: 25px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #dee2e6;
            z-index: 0;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            flex: 1;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #dee2e6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .step.active .step-number {
            background-color: #3B71CA;
        }
        
        .step.completed .step-number {
            background-color: #3B71CA;
        }
        
        .step-text {
            color: #6c757d;
            font-size: 14px;
        }

        .appointment-container {
            display: flex;
            gap: 30px;
            margin-top: 30px;
        }
        
        .calendar-section, .time-section {
            flex: 1;
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .calendar-container {
            border: none;
            background-color: transparent;
            padding: 0;
            box-shadow: none;
            max-width: 100%;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0;
        }
        
        .calendar-navigation {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .nav-btn {
            background-color: #f3f4f6;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #374151;
            transition: all 0.2s;
        }
        
        .nav-btn:hover {
            background-color: #3b82f6;
            color: white;
        }
        
        .month-indicator {
            background-color: transparent;
            color: #1f2937;
            font-weight: 600;
            font-size: 16px;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-top: 15px;
        }
        
        .day-header {
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            padding: 5px;
            border: none;
        }
        
        .day {
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 6px;
            font-size: 14px;
            color: #374151;
            transition: all 0.2s;
        }
        
        .day:hover {
            background-color: #f3f4f6;
        }
        
        .day.inactive {
            color: #d1d5db;
        }
        
        .day.weekend {
            color: #ef4444;
        }
        
        .day.selected {
            background-color: #3b82f6;
            color: white !important;
        }
        
        .time-slots-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        
        .time-column {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .time-column-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .time-slot {
            padding: 12px;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            color: #374151;
            font-size: 14px;
        }
        
        .time-slot:hover {
            border-color: #3b82f6;
            background-color: #ebf4ff;
        }
        
        .time-slot.selected {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .time-slot.disabled {
            background-color: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
            border-color: #e5e7eb;
        }
        
        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .btn-outline {
            border: 1px solid #e5e7eb;
            background-color: #f3f4f6;
            color: #374151;
        }
        
        .btn-outline:hover {
            background-color: #e5e7eb;
        }
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #3b82f6;
    opacity: 0.9;
        }
        
        .timezone-info, .location-info {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>Seto Semero</h1>
            <p>Kebele Services</p>
        </div>
        <div class="nav-links">
            <a href="home.php" class="nav-item">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="homeappointment.php" class="nav-item">
                <i class="far fa-calendar-alt"></i>
                Book Appointment
            </a>
            <div class="nav-item">
                <i class="far fa-bell"></i>
                Notifications
            </div>
            <div class="nav-item">
                <i class="far fa-comment"></i>
                Feedback
            </div>
            <a href="logout.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1 class="heading">Schedule Appointment</h1>
            <div class="user-profile">
                <i class="fas fa-bell notification-icon"></i>
                <div class="user-info">
                    <span class="user-name">John Doe</span>
                    <span class="user-role">Resident</span>
                </div>
            </div>
        </div>

        <div class="progress-steps">
            <div class="progress-line"></div>
            <div class="step completed">
                <div class="step-number">1</div>
                <div class="step-text">Select Service</div>
            </div>
            <div class="step active">
                <div class="step-number">2</div>
                <div class="step-text">Select Date</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-text">Select Time</div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-text">Confirm</div>
            </div>
        </div>

        <div class="appointment-container">
            <div class="calendar-section">
                <h2>Select Date</h2>
                <div class="calendar-container">
                    <div class="calendar-header">
                        <div class="calendar-navigation">
                            <button class="nav-btn" id="prevMonth">&lt;</button>
                            <div class="month-indicator" id="monthDisplay"></div>
                            <button class="nav-btn" id="nextMonth">&gt;</button>
                        </div>
                    </div>
                    <div class="calendar-grid" id="calendarDays">
                        <div class="day-header">Sun</div>
                        <div class="day-header">Mon</div>
                        <div class="day-header">Tue</div>
                        <div class="day-header">Wed</div>
                        <div class="day-header">Thu</div>
                        <div class="day-header">Fri</div>
                        <div class="day-header">Sat</div>
                    </div>
                </div>
            </div>

            <div class="time-section">
                <h2>Select Time</h2>
                <div class="timezone-info">
                    <i class="fas fa-globe"></i> Timezone: East Africa Time (EAT)
                </div>
                <div class="location-info">
                    <i class="fas fa-map-marker-alt"></i> Location: Seto Semero Kebele Office
                </div>
                <div class="time-slots-container" id="timeSlots">
                    <!-- Time slots will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <div class="footer">
            <button class="btn btn-outline" onclick="window.location.href='homeappointment.php'">
                <i class="fas fa-arrow-left"></i> Back to Services
            </button>
            <button class="btn btn-primary" id="confirmAppointment">
                Confirm Appointment <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentDate = new Date();
            let selectedDate = null;
            let selectedTimeSlot = null;

            // Progress tracking
            function updateProgress() {
                const steps = document.querySelectorAll('.step');
                
                // Reset all steps
                steps.forEach(step => {
                    step.classList.remove('active', 'completed');
                });

                // Mark first step as completed since we're on date selection page
                steps[0].classList.add('completed');
                
                // Mark second step (Select Date) as active by default
                steps[1].classList.add('active');

                // If date is selected
                if (selectedDate) {
                    steps[1].classList.remove('active');
                    steps[1].classList.add('completed');
                    steps[2].classList.add('active');
                }

                // If both date and time are selected
                if (selectedDate && selectedTimeSlot) {
                    steps[2].classList.remove('active');
                    steps[2].classList.add('completed');
                    steps[3].classList.add('active');
                }
            }

            // Initialize calendar
            function initCalendar() {
                const calendarDays = document.getElementById('calendarDays');
                const monthDisplay = document.getElementById('monthDisplay');
                const prevMonthBtn = document.getElementById('prevMonth');
                const nextMonthBtn = document.getElementById('nextMonth');

                function updateCalendar() {
                    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
                    const firstDayIndex = firstDay.getDay();
                    const lastDayIndex = lastDay.getDay();
                    const daysInMonth = lastDay.getDate();

                    monthDisplay.textContent = new Intl.DateTimeFormat('en-US', {
                        month: 'long',
                        year: 'numeric'
                    }).format(currentDate);

                    // Clear existing calendar days except headers
                    while (calendarDays.children.length > 7) {
                        calendarDays.removeChild(calendarDays.lastChild);
                    }

                    // Add padding days from previous month
                    for (let i = firstDayIndex; i > 0; i--) {
                        const prevMonthDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), -i + 1);
                        const dayElement = createDayElement(prevMonthDay.getDate(), true);
                        calendarDays.appendChild(dayElement);
                    }

                    // Add current month days
                    for (let day = 1; day <= daysInMonth; day++) {
                        const date = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
                        const dayElement = createDayElement(day, false, date);
                        calendarDays.appendChild(dayElement);
                    }

                    // Add padding days from next month
                    for (let i = 1; i <= (6 - lastDayIndex); i++) {
                        const dayElement = createDayElement(i, true);
                        calendarDays.appendChild(dayElement);
                    }
                }

                function createDayElement(day, inactive, date) {
                    const dayElement = document.createElement('div');
                    dayElement.textContent = day;
                    dayElement.className = 'day';
                    
                    if (inactive) {
                        dayElement.classList.add('inactive');
                    } else {
                        if (date) {
                            // Add weekend class
                            if (date.getDay() === 0 || date.getDay() === 6) {
                                dayElement.classList.add('weekend');
                            }

                            // Add selected class if this is the selected date
                            if (selectedDate && date.toDateString() === selectedDate.toDateString()) {
                                dayElement.classList.add('selected');
                            }

                            // Disable past dates
                            const today = new Date();
                            today.setHours(0, 0, 0, 0);
                            if (date < today) {
                                dayElement.classList.add('inactive');
                            } else {
                                dayElement.addEventListener('click', () => selectDate(date, dayElement));
                            }
                        }
                    }

                    return dayElement;
                }

                function selectDate(date, element) {
                    // Remove selected class from previously selected date
                    const previouslySelected = calendarDays.querySelector('.day.selected');
                    if (previouslySelected) {
                        previouslySelected.classList.remove('selected');
                    }

                    // Add selected class to new date
                    element.classList.add('selected');
                    selectedDate = date;

                    // Update time slots and progress
                    updateTimeSlots(date);
                    updateProgress();
                }

                prevMonthBtn.addEventListener('click', () => {
                    currentDate.setMonth(currentDate.getMonth() - 1);
                    updateCalendar();
                });

                nextMonthBtn.addEventListener('click', () => {
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    updateCalendar();
                });

                // Initial calendar render
                updateCalendar();
            }

            // Initialize time slots
            function updateTimeSlots(date) {
                const timeSlotsContainer = document.getElementById('timeSlots');
                timeSlotsContainer.innerHTML = ''; // Clear existing time slots

                // Create morning column
                const morningColumn = document.createElement('div');
                morningColumn.className = 'time-column';
                
                const morningTitle = document.createElement('div');
                morningTitle.className = 'time-column-title';
                morningTitle.textContent = 'Morning';
                morningColumn.appendChild(morningTitle);

                // Create afternoon column
                const afternoonColumn = document.createElement('div');
                afternoonColumn.className = 'time-column';
                
                const afternoonTitle = document.createElement('div');
                afternoonTitle.className = 'time-column-title';
                afternoonTitle.textContent = 'Afternoon';
                afternoonColumn.appendChild(afternoonTitle);

                // Define time slots
                const morningSlots = [
                    '9:00 AM - 9:30 AM',
                    '9:30 AM - 10:00 AM',
                    '10:00 AM - 10:30 AM',
                    '10:30 AM - 11:00 AM',
                    '11:00 AM - 11:30 AM',
                    '11:30 AM - 12:00 PM'
                ];

                const afternoonSlots = [
                    '2:00 PM - 2:30 PM',
                    '2:30 PM - 3:00 PM',
                    '3:00 PM - 3:30 PM',
                    '3:30 PM - 4:00 PM'
                ];

                // Add morning slots
                morningSlots.forEach(time => {
                    const timeSlot = createTimeSlot(time);
                    morningColumn.appendChild(timeSlot);
                });

                // Add afternoon slots
                afternoonSlots.forEach(time => {
                    const timeSlot = createTimeSlot(time);
                    afternoonColumn.appendChild(timeSlot);
                });

                // Add columns to container
                timeSlotsContainer.appendChild(morningColumn);
                timeSlotsContainer.appendChild(afternoonColumn);
            }

            function createTimeSlot(time) {
                const timeSlot = document.createElement('div');
                timeSlot.className = 'time-slot';
                timeSlot.textContent = time;

                // Add click event
                timeSlot.addEventListener('click', () => {
                    // Remove selected class from previously selected time slot
                    const previouslySelected = document.querySelector('.time-slot.selected');
                    if (previouslySelected) {
                        previouslySelected.classList.remove('selected');
                    }

                    // Add selected class to new time slot
                    timeSlot.classList.add('selected');
                    selectedTimeSlot = time;
                    
                    // Update progress
                    updateProgress();
                });

                return timeSlot;
            }

            // Initialize confirm button
            document.getElementById('confirmAppointment').addEventListener('click', () => {
                if (!selectedDate || !selectedTimeSlot) {
                    alert('Please select both a date and time for your appointment.');
                    return;
                }

                // Format the date for submission
                const formattedDate = selectedDate.toISOString().split('T')[0];
                
                // Here you would typically submit the appointment data to your server
                console.log('Appointment confirmed for:', formattedDate, selectedTimeSlot);
                
                // Update progress to show completion
                const steps = document.querySelectorAll('.step');
                steps[3].classList.remove('active');
                steps[3].classList.add('completed');
                
                // Redirect to confirmation page or handle as needed
                // window.location.href = 'confirmation.php';
            });

            // Initialize the calendar
            initCalendar();
            
            // Initialize progress
            updateProgress();
        });
    </script>
</body>
</html>
