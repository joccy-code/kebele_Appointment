<?php
session_start();

if(isset($_SESSION["user"])){
    if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
        header("location: login.php");
    }
} else {
    header("location: login.php");
}

include("connection.php");

// Fetch metrics
$staffrow = $database->query("SELECT * FROM staff");
$residentrow = $database->query("SELECT * FROM resident");
$appointmentrow = $database->query("SELECT * FROM appointment");
$schedulerow = $database->query("SELECT COUNT(*) as total FROM appointment WHERE appodate=CURRENT_DATE");

// Fetch today's sessions
$todaySessions = $database->query("
    SELECT 
        ts.slot_id,
        s.name as title,
        staff.stuname as staff_name,
        ts.start_time,
        ts.end_time,
        COUNT(a.appoid) as appointment_count,
        CASE 
            WHEN NOW() > CONCAT(CURRENT_DATE, ' ', ts.end_time) THEN 'Completed'
            WHEN NOW() BETWEEN CONCAT(CURRENT_DATE, ' ', ts.start_time) AND CONCAT(CURRENT_DATE, ' ', ts.end_time) THEN 'In Progress'
            ELSE 'Upcoming'
        END as status
    FROM time_slots ts
    CROSS JOIN staff
    INNER JOIN staff_service ss ON staff.stuid = ss.staff_id
    INNER JOIN services s ON ss.service_id = s.service_id
    LEFT JOIN appointment a ON a.scheduleid = ts.slot_id 
        AND a.appodate = CURRENT_DATE
        AND a.status != 'cancelled'
    WHERE ts.is_active = 1
    GROUP BY ts.slot_id, staff.stuid, s.service_id
    HAVING COUNT(a.appoid) < 3
    ORDER BY ts.start_time ASC, s.category, staff.stuname
    LIMIT 5
");

// Fetch recent appointments
$recentAppointments = $database->query("
    SELECT 
        r.resname,
        s.name as service_name,
        a.appodate,
        ts.start_time,
        CASE 
            WHEN a.appodate < CURRENT_DATE THEN 'Completed'
            WHEN a.appodate = CURRENT_DATE AND NOW() > CONCAT(a.appodate, ' ', ts.end_time) THEN 'Completed'
            WHEN a.appodate = CURRENT_DATE AND NOW() BETWEEN CONCAT(a.appodate, ' ', ts.start_time) AND CONCAT(a.appodate, ' ', ts.end_time) THEN 'In Progress'
            ELSE 'Upcoming'
        END as status
    FROM appointment a
    INNER JOIN resident r ON a.resid = r.resid
    INNER JOIN time_slots ts ON a.scheduleid = ts.slot_id
    INNER JOIN services s ON a.service_id = s.service_id
    ORDER BY a.appodate DESC, ts.start_time DESC
    LIMIT 5
");

// Fetch recent feedback
$recentFeedback = $database->query("
    SELECT 
        r.resname,
        f.rating,
        f.comment,
        f.created_at
    FROM feedback f
    INNER JOIN resident r ON f.resident_id = r.resid
    ORDER BY f.created_at DESC
    LIMIT 3
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Seto Semero</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #f5f7fb;
            border-right: 1px solid #e5e7eb;
            padding: 1.5rem;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #374151;
            text-decoration: none;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.05);
            color: #3b82f6;
            padding-left: 1.25rem;
        }

        .nav-item.active {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-radius: 8px;
            font-weight: 500;
        }

        .nav-item i {
            width: 20px;
            margin-right: 0.75rem;
            transition: transform 0.2s ease;
        }

        .nav-item:hover i {
            transform: scale(1.1);
        }

        .metric-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.1);
            background: linear-gradient(to bottom right, white, #f8fafc);
        }

        .metric-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent 30%, rgba(59, 130, 246, 0.03) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .metric-card:hover::after {
            transform: translateX(100%);
        }

        .table-container {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .table-container:hover {
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.1);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .status-badge:hover {
            transform: scale(1.05);
        }

        .status-badge.completed {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .status-badge.in-progress {
            background-color: #fef9c3;
            color: #ca8a04;
        }

        .status-badge.upcoming {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .star-rating {
            color: #fbbf24;
            transition: color 0.2s ease;
        }

        .star-rating:hover {
            color: #f59e0b;
        }

        /* Add smooth transitions for buttons */
        button {
            transition: all 0.2s ease;
        }

        button:hover {
            transform: translateY(-1px);
        }

        button:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="mb-8">
            <h1 class="text-xl font-bold text-gray-800">Seto Semero</h1>
            <p class="text-sm text-gray-500">Admin Portal</p>
        </div>

        <nav>
            <a href="newadmin.php" class="nav-item active">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="admin/appointment.php" class="nav-item">
                <i class="far fa-calendar-check"></i>
                Manage Appointments
            </a>
            <a href="admin/schedule.php" class="nav-item">
                <i class="far fa-clock"></i>
                Manage Sessions
            </a>
            <a href="admin/resident.php" class="nav-item">
                <i class="fas fa-users"></i>
                Manage Residents
            </a>
            <a href="admin/staffs.php" class="nav-item">
                <i class="fas fa-user-tie"></i>
                Manage Staff
            </a>
            <a href="admin/reports.php" class="nav-item">
                <i class="fas fa-chart-bar"></i>
                Reports & Analytics
            </a>
            <a href="admin/settings.php" class="nav-item">
                <i class="fas fa-cog"></i>
                Settings
            </a>
            <a href="logout.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
                <p class="text-gray-600">Welcome to your admin dashboard</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="text-gray-500 hover:text-gray-600">
                    <i class="fas fa-bell text-xl"></i>
                </button>
                <button class="text-gray-500 hover:text-gray-600">
                    <i class="fas fa-user-circle text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="metric-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-gray-500">
                        <i class="fas fa-calendar-check text-3xl"></i>
                    </div>
                    <span class="text-sm font-medium text-green-600 bg-green-100 px-2.5 py-0.5 rounded-full">Today</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">
                    <?php 
                    $row = $schedulerow->fetch_assoc();
                    echo $row['total'];
                    ?>
                </h3>
                <p class="text-gray-600 text-sm">Appointments Today</p>
            </div>

            <div class="metric-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-gray-500">
                        <i class="fas fa-users text-3xl"></i>
                    </div>
                    <span class="text-sm font-medium text-blue-600 bg-blue-100 px-2.5 py-0.5 rounded-full">Total</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">
                    <?php echo $residentrow->num_rows ?>
                </h3>
                <p class="text-gray-600 text-sm">Registered Residents</p>
            </div>

            <div class="metric-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-gray-500">
                        <i class="fas fa-user-tie text-3xl"></i>
                    </div>
                    <span class="text-sm font-medium text-purple-600 bg-purple-100 px-2.5 py-0.5 rounded-full">Active</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">
                    <?php echo $staffrow->num_rows ?>
                </h3>
                <p class="text-gray-600 text-sm">Staff Members</p>
            </div>

            <div class="metric-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-gray-500">
                        <i class="fas fa-clock text-3xl"></i>
                    </div>
                    <span class="text-sm font-medium text-orange-600 bg-orange-100 px-2.5 py-0.5 rounded-full">Total</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">
                    <?php echo $appointmentrow->num_rows ?>
                </h3>
                <p class="text-gray-600 text-sm">Total Appointments</p>
            </div>
        </div>

        <!-- Today's Sessions and Recent Appointments -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Today's Sessions -->
            <div class="table-container">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Today's Sessions</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            if($todaySessions->num_rows > 0) {
                                while($session = $todaySessions->fetch_assoc()) {
                                    $statusClass = strtolower(str_replace(' ', '-', $session['status']));
                                    echo '<tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">'.$session['title'].'</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">'.$session['staff_name'].'</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">'.
                                                date('h:i A', strtotime($session['start_time'])).' - '.
                                                date('h:i A', strtotime($session['end_time'])).
                                            '</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="status-badge '.$statusClass.'">'.$session['status'].'</span>
                                        </td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No sessions scheduled for today</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Appointments -->
            <div class="table-container">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Appointments</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resident</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            if($recentAppointments->num_rows > 0) {
                                while($appointment = $recentAppointments->fetch_assoc()) {
                                    $statusClass = strtolower(str_replace(' ', '-', $appointment['status']));
                                    echo '<tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">'.$appointment['resname'].'</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">'.$appointment['service_name'].'</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">'.
                                                date('M d, Y', strtotime($appointment['appodate'])).' '.
                                                date('h:i A', strtotime($appointment['start_time'])).
                                            '</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="status-badge '.$statusClass.'">'.$appointment['status'].'</span>
                                        </td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No recent appointments</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Feedback -->
        <div class="table-container">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Recent Feedback</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                if($recentFeedback->num_rows > 0) {
                    while($feedback = $recentFeedback->fetch_assoc()) {
                        echo '<div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-medium text-gray-900">'.$feedback['resname'].'</div>
                                <div class="star-rating">';
                        for($i = 1; $i <= 5; $i++) {
                            echo '<i class="'.($i <= $feedback['rating'] ? 'fas' : 'far').' fa-star"></i>';
                        }
                        echo '</div>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">'.$feedback['comment'].'</p>
                            <p class="text-gray-400 text-xs">'.date('M d, Y', strtotime($feedback['created_at'])).'</p>
                        </div>';
                    }
                } else {
                    echo '<div class="col-span-3 text-center text-gray-500">No feedback received yet</div>';
                }
                ?>
            </div>
        </div>

        <!-- View Reports Button -->
        <div class="mt-6 text-center">
            <a href="admin/reports.php" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-chart-bar mr-2"></i>
                View Reports & Analytics
            </a>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>
</html>
