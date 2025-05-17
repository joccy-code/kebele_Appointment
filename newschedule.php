<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Sessions - Seto Semero</title>
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

    .session-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }

    .session-card:hover {
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

    .status-badge.cancelled {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .popup-content {
        background-color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        width: 90%;
        max-width: 600px;
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
    <?php
    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='s'){
            header("location: login.php");
        }else{
            $useremail=$_SESSION["user"];
        }
    }else{
        header("location: login.php");
    }

    //import database
    include("connection.php");
    $userrow = $database->query("select * from staff where stuemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["stuid"];
    $username=$userfetch["stuname"];

    date_default_timezone_set('Asia/Kolkata');
    $today = date('Y-m-d');
    ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="mb-8">
            <h1 class="text-xl font-bold text-gray-800">Seto Semero</h1>
            <p class="text-sm text-gray-600">Staff Portal</p>
        </div>

        <nav>
            <a href="newstaff.php" class="nav-item">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="newstaffappointment.php" class="nav-item">
                <i class="far fa-calendar-check"></i>
                My Appointments
            </a>
            <a href="newschedule.php" class="nav-item active">
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
                <h1 class="text-2xl font-bold text-gray-800">My Sessions</h1>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm text-gray-600">Today's Date</p>
                    <p class="text-sm font-medium"><?php echo $today; ?></p>
                </div>
                <button class="btn-label" style="display: flex;justify-content: center;align-items: center;">
                    <i class="far fa-calendar text-gray-600"></i>
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg p-6 shadow-sm mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 text-center">Filter Sessions</h2>
            <div class="flex justify-center">
                <div class="relative w-80">
                    <button id="statusDropdown"
                        class="w-full px-4 py-2 text-left bg-gray-100 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <?php
                        if(isset($_GET['status'])) {
                            switch($_GET['status']) {
                                case 'pending':
                                    echo 'Pending Sessions';
                                    break;
                                case 'completed':
                                    echo 'Completed Sessions';
                                    break;
                                case 'missed':
                                    echo 'Missed Sessions';
                                    break;
                                default:
                                    echo 'All Sessions';
                            }
                        } else {
                            echo 'Select Session Status';
                        }
                        ?>
                        <span class="float-right">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </button>
                    <div id="statusOptions" class="hidden absolute z-10 w-full mt-2 bg-white rounded-md shadow-lg">
                        <div class="py-1">
                            <a href="?status=all"
                                class="block px-4 py-2 text-sm text-gray-700 transition-colors duration-200 hover:bg-blue-50 hover:text-blue-600 <?php echo (!isset($_GET['status']) || $_GET['status'] == 'all') ? 'bg-blue-50 text-blue-600' : ''; ?>">
                                All Sessions
                            </a>
                            <a href="?status=pending"
                                class="block px-4 py-2 text-sm text-gray-700 transition-colors duration-200 hover:bg-blue-50 hover:text-blue-600 <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'bg-blue-50 text-blue-600' : ''; ?>">
                                Pending Sessions
                            </a>
                            <a href="?status=completed"
                                class="block px-4 py-2 text-sm text-gray-700 transition-colors duration-200 hover:bg-blue-50 hover:text-blue-600 <?php echo (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'bg-blue-50 text-blue-600' : ''; ?>">
                                Completed Sessions
                            </a>
                            <a href="?status=missed"
                                class="block px-4 py-2 text-sm text-gray-700 transition-colors duration-200 hover:bg-blue-50 hover:text-blue-600 <?php echo (isset($_GET['status']) && $_GET['status'] == 'missed') ? 'bg-blue-50 text-blue-600' : ''; ?>">
                                Missed Sessions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions List -->
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <?php
                if(isset($_GET['status'])) {
                    switch($_GET['status']) {
                        case 'pending':
                            echo 'Pending Sessions';
                            break;
                        case 'completed':
                            echo 'Completed Sessions';
                            break;
                        case 'missed':
                            echo 'Missed Sessions';
                            break;
                        default:
                            echo 'All Sessions';
                    }
                } else {
                    echo 'All Sessions';
                }
                ?>
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Session Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Max Bookings</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $sqlmain = "select schedule.scheduleid, schedule.title, staff.stuname, schedule.scheduledate, schedule.stuid 
                                  from schedule inner join staff on schedule.stuid=staff.stuid 
                                  where staff.stuid=$userid";

                        if(isset($_GET['status'])) {
                            $status = $_GET['status'];
                            $current_date = date('Y-m-d');
                            
                            if($status == 'pending') {
                                $sqlmain .= " AND schedule.scheduledate >= '$current_date'";
                            } elseif($status == 'completed') {
                                $sqlmain .= " AND schedule.scheduledate < '$current_date'";
                            } elseif($status == 'missed') {
                                $sqlmain .= " AND schedule.scheduledate < '$current_date'";
                            }
                        }

                        $result = $database->query($sqlmain);

                        if($result->num_rows == 0) {
                            echo '<tr>
                                    <td colspan="4" class="px-6 py-4 text-center">
                                        <div class="text-gray-500">
                                            <i class="fas fa-calendar-times text-4xl mb-2"></i>
                                            <p>No sessions found</p>
                                        </div>
                                    </td>
                                  </tr>';
                        } else {
                            while($row = $result->fetch_assoc()) {
                                $scheduleid = $row["scheduleid"];
                                $title = $row["title"];
                                $scheduledate = $row["scheduledate"];

                                // Calculate status based on current date
                                $current_date = date('Y-m-d');
                                
                                if(strtotime($scheduledate) >= strtotime($current_date)) {
                                    $status = 'pending';
                                } else {
                                    $status = 'completed';
                                }
                                ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo substr($title, 0, 30); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo substr($scheduledate, 0, 10); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="?action=view&scheduleid=<?php echo $scheduleid; ?>"
                                    class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                <?php if($status == 'pending') { ?>
                                <a href="?action=drop&scheduleid=<?php echo $scheduleid; ?>&name=<?php echo $title; ?>"
                                    class="text-red-600 hover:text-red-900">Cancel</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
    if($_GET){
        $id = $_GET["id"];
        $action = $_GET["action"];
        
        if($action == 'drop'){
            $nameget = $_GET["name"];
            ?>
    <div class="popup">
        <div class="popup-content">
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-4">Are you sure?</h2>
                <p class="mb-6">You want to delete this session (<?php echo substr($nameget, 0, 40); ?>)</p>
                <div class="flex justify-center space-x-4">
                    <a href="delete-session.php?id=<?php echo $id; ?>"
                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Yes</a>
                    <a href="newschedule.php"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">No</a>
                </div>
            </div>
        </div>
    </div>
    <?php
        } elseif($action == 'view'){
            $sqlmain = "select schedule.scheduleid, schedule.title, staff.stuname, schedule.scheduledate 
                       from schedule inner join staff on schedule.stuid=staff.stuid 
                       where schedule.scheduleid=$id";
            $result = $database->query($sqlmain);
            
            if($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $title = $row["title"];
                $scheduledate = $row["scheduledate"];
                $stuname = $row["stuname"];

                $sqlmain12 = "select * from appointment inner join resident on resident.resid=appointment.resid 
                             inner join schedule on schedule.scheduleid=appointment.scheduleid 
                             where schedule.scheduleid=$id";
                $result12 = $database->query($sqlmain12);
                ?>
    <div class="popup">
        <div class="popup-content">
            <div class="mb-6">
                <h2 class="text-2xl font-bold mb-4">Session Details</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Session Title</label>
                        <p class="mt-1"><?php echo $title; ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Staff Name</label>
                        <p class="mt-1"><?php echo $stuname; ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Scheduled Date</label>
                        <p class="mt-1"><?php echo $scheduledate; ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Registered Residents
                            (<?php echo $result12 ? $result12->num_rows : 0; ?>)</label>
                    </div>
                </div>
            </div>

            <?php if($result12 && $result12->num_rows > 0) { ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Resident ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Appointment #
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telephone</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        while($row = $result12->fetch_assoc()) {
                            ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo substr($row["resid"], 0, 15); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo substr($row["resname"], 0, 25); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $row["apponum"]; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo substr($row["restel"], 0, 25); ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php } else { ?>
            <div class="text-center text-gray-500 py-4">
                <p>No residents registered for this session</p>
            </div>
            <?php } ?>

            <div class="mt-6 text-center">
                <a href="newschedule.php"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Close</a>
            </div>
        </div>
    </div>
    <?php
            } else {
                echo '<div class="popup"><div class="popup-content"><div class="text-center text-red-500">Session not found</div></div></div>';
            }
        }
    }
    ?>

    <script>
    // Mobile sidebar toggle
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('open');
    }

    document.getElementById('statusDropdown').addEventListener('click', function() {
        document.getElementById('statusOptions').classList.toggle('hidden');
    });

    // Close the dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('statusDropdown');
        const options = document.getElementById('statusOptions');
        if (!dropdown.contains(event.target) && !options.contains(event.target)) {
            options.classList.add('hidden');
        }
    });
    </script>
</body>

</html>