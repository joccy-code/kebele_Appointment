<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Seto Semero</title>
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
        background-color: white;
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
        color: #6b7280;
        text-decoration: none;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        transition: all 0.2s;
    }

    .nav-item:hover {
        background-color: #f3f4f6;
        color: #3b82f6;
    }

    .nav-item.active {
        background-color: #ebf5ff;
        color: #3b82f6;
    }

    .nav-item i {
        width: 20px;
        margin-right: 0.75rem;
    }

    .summary-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .summary-card:hover {
        transform: translateY(-2px);
    }

    .appointment-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }

    .appointment-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-badge.upcoming {
        background-color: #ebf5ff;
        color: #3b82f6;
    }

    .status-badge.completed {
        background-color: #dcfce7;
        color: #16a34a;
    }

    .status-badge.missed {
        background-color: #fee2e2;
        color: #dc2626;
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            z-index: 50;
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }
    }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="mb-8">
            <h1 class="text-xl font-bold text-gray-800">Seto Semero</h1>
            <p class="text-sm text-gray-600">Staff Portal</p>
        </div>

        <nav>
            <a href="#" class="nav-item active">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="newstaffappointment.php" class="nav-item">
                <i class="far fa-calendar-check"></i>
                My Appointments
            </a>
            <a href="newschedule.php" class="nav-item">
                <i class="far fa-clock"></i>
                My Sessions
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
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Welcome, John!</h1>
                <p class="text-gray-600">Here's your activity overview</p>
            </div>
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
                    JD
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="summary-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Today's Appointments</p>
                        <h3 class="text-2xl font-bold text-gray-800">8</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500">
                        <i class="far fa-calendar text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="summary-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Completed Today</p>
                        <h3 class="text-2xl font-bold text-gray-800">5</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-500">
                        <i class="fas fa-check text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="summary-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Pending Reviews</p>
                        <h3 class="text-2xl font-bold text-gray-800">3</h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-500">
                        <i class="far fa-clock text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Sessions -->
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Today's Sessions</h2>

            <div class="space-y-4">
                <!-- Appointment Card -->
                <div class="appointment-card">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Sarah Johnson</h4>
                                <p class="text-sm text-gray-600">ID Card Renewal</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-800">10:00 AM</p>
                                <span class="status-badge upcoming">Upcoming</span>
                            </div>
                            <button class="p-2 text-blue-500 hover:bg-blue-50 rounded-full">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Completed Appointment -->
                <div class="appointment-card">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Michael Smith</h4>
                                <p class="text-sm text-gray-600">Birth Certificate</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-800">9:00 AM</p>
                                <span class="status-badge completed">Completed</span>
                            </div>
                            <button class="p-2 text-blue-500 hover:bg-blue-50 rounded-full">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Missed Appointment -->
                <div class="appointment-card">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">David Wilson</h4>
                                <p class="text-sm text-gray-600">Marriage Certificate</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-800">8:30 AM</p>
                                <span class="status-badge missed">Missed</span>
                            </div>
                            <button class="p-2 text-blue-500 hover:bg-blue-50 rounded-full">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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