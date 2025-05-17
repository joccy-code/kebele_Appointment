<!DOCTYPE html>
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

    // Base query
    $sqlmain = "SELECT 
        a.appoid,
        ts.slot_id as scheduleid,
        s.name as title,
        staff.stuname,
        r.resname,
        a.appodate,
        ts.start_time as scheduletime,
        (SELECT COUNT(*) + 1 FROM appointment a2 
         WHERE a2.appodate = a.appodate 
         AND a2.scheduleid = a.scheduleid 
         AND a2.created_at <= a.created_at) as apponum,
        a.status
    FROM appointment a
    INNER JOIN time_slots ts ON a.scheduleid = ts.slot_id
    INNER JOIN services s ON a.service_id = s.service_id
    INNER JOIN staff ON a.staff_id = staff.stuid
    INNER JOIN resident r ON a.resid = r.resid
    WHERE staff.stuid = $userid";

    // Handle date filter
    if(isset($_POST["filter"]) && !empty($_POST["sheduledate"])){
        $sheduledate = $_POST["sheduledate"];
        $sqlmain .= " AND a.appodate = '$sheduledate'";
    }

    // Execute query
    $result = $database->query($sqlmain);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments - Seto Semero</title>
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
            transition: all 0.2s;
        }

        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.05);
            color: #3b82f6;
        }

        .nav-item.active {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-radius: 8px;
        }

        .nav-item i {
            width: 20px;
            margin-right: 0.75rem;
        }

        .table-container {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .appointments-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .appointments-table th {
            background-color: #f8fafc;
            font-weight: 600;
            text-align: left;
            padding: 1rem;
            color: #475569;
            font-size: 0.875rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .appointments-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .appointments-table td {
            padding: 1rem;
            color: #475569;
            border-bottom: 1px solid #e5e7eb;
        }

        .appointments-table tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .status-badge.upcoming {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .status-badge.completed {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .action-button {
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
            color: #3b82f6;
        }

        .action-button:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: #2563eb;
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

            .table-container {
                margin-top: 1rem;
            }

            .appointments-table thead {
                display: none;
            }

            .appointments-table, 
            .appointments-table tbody, 
            .appointments-table tr, 
            .appointments-table td {
                display: block;
                width: 100%;
            }

            .appointments-table tr {
                margin-bottom: 1rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 1rem;
            }

            .appointments-table td {
                text-align: right;
                padding: 0.5rem 0;
                border: none;
                position: relative;
            }

            .appointments-table td::before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                color: #475569;
            }
        }

        /* Popup styles */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup {
            background: white;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            width: 90%;
            max-width: 500px;
        }

        .popup .close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            color: #6b7280;
            text-decoration: none;
            transition: color 0.2s;
        }

        .popup .close:hover {
            color: #1f2937;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="mb-8">
            <h1 class="text-xl font-bold text-gray-800">Seto Semero</h1>
            <p class="text-sm text-gray-500">Staff Portal</p>
        </div>

        <nav>
            <a href="newstaff.php" class="nav-item">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="newstaffappointment.php" class="nav-item active">
                <i class="far fa-calendar-check"></i>
                My Appointments
            </a>
            <a href="#" class="nav-item">
                <i class="far fa-clock"></i>
                My Sessions
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-cog"></i>
                Settings
            </a>
            <a href="#" class="nav-item">
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
                <h1 class="text-2xl font-bold text-gray-800">My Appointments</h1>
                <p class="text-gray-600">View and manage your appointments (<?php echo $result->num_rows; ?>)</p>
            </div>
            <div class="flex items-center space-x-4">
                <form action="" method="post" class="flex items-center space-x-4">
                    <div class="relative">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Filter by Date</label>
                        <input type="date" name="sheduledate" id="date" 
                               class="pl-4 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="<?php echo isset($_POST['sheduledate']) ? $_POST['sheduledate'] : ''; ?>">
                    </div>
                    <div class="flex items-end">
                        <input type="submit" name="filter" value="Filter" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg cursor-pointer">
                    </div>
                </form>
            </div>
        </div>

        <!-- Appointments Table -->
        <div class="table-container">
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Resident Name</th>
                        <th>Appointment Number</th>
                        <th>Session Title</th>
                        <th>Session Date & Time</th>
                        <th>Appointment Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($result->num_rows == 0) {
                        echo '<tr>
                            <td colspan="7" class="text-center py-8">
                                <div class="flex flex-col items-center justify-center">
                                    <img src="img/notfound.svg" class="w-24 mb-4" alt="No appointments found">
                                    <p class="text-lg font-medium text-gray-600 mb-4">No appointments found</p>
                                    <p class="text-gray-500">We couldn\'t find any appointments matching your criteria</p>
                                </div>
                            </td>
                        </tr>';
                    } else {
                        while($row = $result->fetch_assoc()) {
                            $appoid = $row["appoid"];
                            $scheduleid = $row["scheduleid"];
                            $title = $row["title"];
                            $resname = $row["resname"];
                            $scheduledate = $row["appodate"];
                            $scheduletime = $row["scheduletime"];
                            $apponum = $row["apponum"];
                            $status = $row["status"];
                            
                            // Determine appointment status
                            $today = date('Y-m-d');
                            $status_text = '';
                            $status_icon = '';
                            if($scheduledate < $today) {
                                $status_text = 'Completed';
                                $status_icon = 'check-circle';
                                $status_badge_class = 'completed';
                            } else {
                                $status_text = 'Upcoming';
                                $status_icon = 'clock';
                                $status_badge_class = 'upcoming';
                            }
                            
                            echo '<tr>
                                <td data-label="Resident Name">'.$resname.'</td>
                                <td data-label="Appointment Number">'.$apponum.'</td>
                                <td data-label="Session Title">'.$title.'</td>
                                <td data-label="Session Date & Time">'.date('M d, Y', strtotime($scheduledate)).' '.date('h:i A', strtotime($scheduletime)).'</td>
                                <td data-label="Appointment Date">'.date('M d, Y', strtotime($scheduledate)).'</td>
                                <td data-label="Status">
                                    <span class="status-badge '.$status_badge_class.'">
                                        <i class="fas fa-'.$status_icon.' mr-1"></i> '.$status_text.'
                                    </span>
                                </td>
                                <td data-label="Actions">
                                    <div class="flex space-x-2">
                                        <button class="action-button" title="View Details" onclick="window.location.href=\'?action=view&id='.$appoid.'\'">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        '.($status === 'upcoming' ? '
                                        <button class="action-button" title="Mark Complete" onclick="window.location.href=\'?action=complete&id='.$appoid.'\'">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="action-button text-red-500 hover:bg-red-50" title="Cancel Appointment" onclick="window.location.href=\'?action=drop&id='.$appoid.'&name='.$resname.'&session='.$title.'&apponum='.$apponum.'\'">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        ' : '').'
                                    </div>
                                </td>
                            </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    if(isset($_GET['action']) && $_GET['action'] == 'drop') {
        $id = $_GET['id'];
        $nameget = $_GET['name'];
        $session = $_GET['session'];
        $apponum = $_GET['apponum'];
        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <h2 class="text-xl font-bold mb-4">Cancel Appointment</h2>
                    <a class="close" href="newstaffappointment.php">&times;</a>
                    <div class="content">
                        <p class="mb-4">Are you sure you want to cancel this appointment?</p>
                        <p class="mb-2"><span class="font-semibold">Resident Name:</span> '.$nameget.'</p>
                        <p class="mb-4"><span class="font-semibold">Appointment Number:</span> '.$apponum.'</p>
                        
                        <div class="flex justify-center space-x-4">
                            <a href="delete-appointment.php?id='.$id.'" class="btn-primary bg-red-500 hover:bg-red-600">Yes, Cancel</a>
                            <a href="newstaffappointment.php" class="btn-primary bg-gray-500 hover:bg-gray-600">No, Keep</a>
                        </div>
                    </div>
                </center>
            </div>
        </div>';
    }
    ?>

    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>
</html>
