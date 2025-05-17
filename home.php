<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebele Services Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px 30px;
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
            margin-right: 12px;
            flex-shrink: 0;
        }
        .appointment-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            align-items: center;
        }
        .calendar-icon {
            background-color: #ebf4ff;
            border-radius: 8px;
            padding: 10px;
            margin-right: 15px;
            color: #3b82f6;
        }
        .star {
            color: #e0e0e0;
            cursor: pointer;
            font-size: 24px;
        }
        .star.filled {
            color: #3b82f6;
        }
        .action-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        .action-icon {
            background-color: #f3f4f6;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="flex">
        <?php 
        $currentPage = 'home';
        include 'includes/sidebar.php';
        ?>

        <!-- Main Content -->
        <div class="main-content flex-1">
          <!-- Header -->
          <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold">Welcome, Fitsum!</h1>
                <div class="flex items-center">
                    <div class="mr-4 relative">
                        <i class="far fa-bell text-gray-500"></i>
                    </div>
                    <div class="w-9 h-9 bg-gray-300 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-600"></i>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments Section -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Upcoming Appointments</h2>
                    <a href="homeappointment.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">Book New Appointment</a>
                </div>
                
                <div class="appointment-card">
                    <div class="calendar-icon">
                        <i class="far fa-calendar"></i>
                    </div>
                    <div>
                        <div class="text-gray-600">May 20, 2023</div>
                        <div class="font-medium">Kebele ID Card</div>
                        <div class="text-blue-600">3:00 PM</div>
                    </div>
                </div>
            </div>

            <!-- Recent Notifications Section -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h2 class="text-lg font-semibold mb-4">Recent Notifications</h2>
                
                <div class="flex mb-4">
                    <div class="notification-icon">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <div>
                        <div class="font-medium">Appointment Confirmed</div>
                        <div class="text-gray-600 text-sm">Your appointment for Kebele ID Card has been confirmed.</div>
                        <div class="text-gray-400 text-xs mt-1">2 hours ago</div>
                    </div>
                </div>
                
                <div class="flex">
                    <div class="notification-icon">
                        <i class="fas fa-file-alt text-sm"></i>
                    </div>
                    <div>
                        <div class="font-medium">Document Ready</div>
                        <div class="text-gray-600 text-sm">Your requested document is ready for pickup.</div>
                        <div class="text-gray-400 text-xs mt-1">1 day ago</div>
                    </div>
                </div>
            </div>

            <!-- Feedback Section -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-lg font-semibold mb-4">Leave Feedback</h2>
                
                <div class="flex mb-6">
                    <span class="star filled"><i class="fas fa-star"></i></span>
                    <span class="star filled"><i class="fas fa-star"></i></span>
                    <span class="star filled"><i class="fas fa-star"></i></span>
                    <span class="star"><i class="fas fa-star"></i></span>
                    <span class="star"><i class="fas fa-star"></i></span>
                </div>
                
                <div class="action-icons justify-center">
                    <div class="action-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="action-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="action-icon">
                    <i class="far fa-comment"></i>
                    </div>
                    <div class="action-icon">
                        <i class="fas fa-crosshairs"></i>
                    </div>
                </div>
            </div>
            
            <!-- Footer border -->
            <div class="mt-6 border-t border-gray-200"></div>
        </div>
    </div>
</body>
</html>