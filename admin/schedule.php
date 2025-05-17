<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Schedule</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
</style>
</head>
<body>
    <?php

    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }
    
    

    //import database
    include("../connection.php");

    
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px" >
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title">Administrator</p>
                                    <p class="profile-subtitle">admin@ekeble.com</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                    </table>
                    </td>
                
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-dashbord" >
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-staff ">
                        <a href="staffs.php" class="non-style-link-menu "><div><p class="menu-text">Staffs</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-schedule menu-active menu-icon-schedule-active">
                        <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Schedule</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">Appointment</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-resident">
                        <a href="resident.php" class="non-style-link-menu"><div><p class="menu-text">Residents</p></a></div>
                    </td>
                </tr>

            </table>
        </div>
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%" >
                    <a href="schedule.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Shedule Manager</p>
                                           
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 

                        date_default_timezone_set('Asia/Kolkata');

                        $today = date('Y-m-d');
                        echo $today;

                        $list110 = $database->query("SELECT COUNT(*) as total FROM time_slots ts 
                            INNER JOIN staff_service ss ON ss.staff_id IN (SELECT stuid FROM staff)
                            INNER JOIN services sv ON ss.service_id = sv.service_id
                            WHERE ts.is_active = 1;");

                        ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>
               
                <tr>
                    <td colspan="4" >
                        <div style="display: flex;margin-top: 40px;">
                        <div class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49);margin-top: 5px;">Manage Time Slots</div>
                        <a href="?action=add-slot&id=none&error=0" class="non-style-link"><button  class="login-btn btn-primary btn button-icon"  style="margin-left:25px;background-image: url('../img/icons/add.svg');">Add Time Slot</font></button>
                        </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Time Slots (<?php 
                            $row = $list110->fetch_assoc();
                            echo $row['total']; 
                        ?>)</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:0px;width: 100%;" >
                        <center>
                        <table class="filter-container" border="0" >
                        <tr>
                           <td width="10%"></td> 
                        <td width="5%" style="text-align: center;">
                        Service:
                        </td>
                        <td width="30%">
                        <form action="" method="post">
                            <select name="service_id" id="service" class="box filter-container-items" style="width:95%;height: 37px;margin: 0;">
                                <option value="" disabled selected hidden>Choose Service</option>
                                <?php 
                                $list11 = $database->query("SELECT DISTINCT category, service_id, name FROM services WHERE is_active = 1 ORDER BY category, name;");
                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $service_name=$row00["name"];
                                    $category=$row00["category"];
                                    $id00=$row00["service_id"];
                                    echo "<option value='$id00'>$category - $service_name</option><br/>";
                                }
                                ?>
                            </select>
                        </td>
                        <td width="5%" style="text-align: center;">
                        Staff:
                        </td>
                        <td width="30%">
                        <select name="stuid" id="staff" class="box filter-container-items" style="width:90%;height: 37px;margin: 0;">
                            <option value="" disabled selected hidden>Choose Staff</option>
                            <?php 
                            $list11 = $database->query("SELECT s.stuid, s.stuname, d.depname 
                                FROM staff s 
                                LEFT JOIN departments d ON s.stucialties = d.id 
                                ORDER BY s.stuname;");
                            for ($y=0;$y<$list11->num_rows;$y++){
                                $row00=$list11->fetch_assoc();
                                $sn=$row00["stuname"];
                                $dept=$row00["depname"];
                                $id00=$row00["stuid"];
                                echo "<option value='$id00'>$sn ($dept)</option><br/>";
                            }
                            ?>
                        </select>
                    </td>
                    <td width="12%">
                        <input type="submit" name="filter" value="Filter" class="btn-primary-soft btn button-icon btn-filter" style="padding: 15px; margin:0;width:100%">
                        </form>
                    </td>
                    </tr>
                        </table>
                        </center>
                    </td>
                </tr>
                
                <?php
                    if($_POST){
                        $sqlpt1 = "";
                        if(!empty($_POST["service_id"])){
                            $service_id = $_POST["service_id"];
                            $sqlpt1 = " sv.service_id = '$service_id' ";
                        }

                        $sqlpt2 = "";
                        if(!empty($_POST["stuid"])){
                            $stuid = $_POST["stuid"];
                            $sqlpt2 = " s.stuid = $stuid ";
                        }

                        $sqlmain = "SELECT ts.slot_id, ts.start_time, ts.end_time, ts.is_active,
                            s.stuname, d.depname as department,
                            sv.name as service_name, sv.category,
                            COUNT(a.appoid) as booked_count
                            FROM time_slots ts
                            INNER JOIN staff_service ss ON ss.staff_id IN (SELECT stuid FROM staff)
                            INNER JOIN staff s ON ss.staff_id = s.stuid
                            INNER JOIN departments d ON s.stucialties = d.id
                            INNER JOIN services sv ON ss.service_id = sv.service_id
                            LEFT JOIN appointment a ON a.scheduleid = ts.slot_id AND a.status != 'cancelled'
                            WHERE ts.is_active = 1";

                        $sqllist = array($sqlpt1, $sqlpt2);
                        $sqlkeywords = array(" AND ", " AND ");
                        $key2 = 0;
                        foreach($sqllist as $key){
                            if(!empty($key)){
                                $sqlmain .= $sqlkeywords[$key2] . $key;
                                $key2++;
                            }
                        }
                        $sqlmain .= " GROUP BY ts.slot_id, s.stuid, sv.service_id ORDER BY ts.start_time, sv.category, s.stuname";
                    } else {
                        $sqlmain = "SELECT ts.slot_id, ts.start_time, ts.end_time, ts.is_active,
                            s.stuname, d.depname as department,
                            sv.name as service_name, sv.category,
                            COUNT(a.appoid) as booked_count
                            FROM time_slots ts
                            INNER JOIN staff_service ss ON ss.staff_id IN (SELECT stuid FROM staff)
                            INNER JOIN staff s ON ss.staff_id = s.stuid
                            INNER JOIN departments d ON s.stucialties = d.id
                            INNER JOIN services sv ON ss.service_id = sv.service_id
                            LEFT JOIN appointment a ON a.scheduleid = ts.slot_id AND a.status != 'cancelled'
                            WHERE ts.is_active = 1
                            GROUP BY ts.slot_id, s.stuid, sv.service_id
                            ORDER BY ts.start_time, sv.category, s.stuname";
                    }
                ?>
                  
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="93%" class="sub-table scrolldown" border="0">
                        <thead>
                        <tr>
                                <th class="table-headin">Service</th>
                                <th class="table-headin">Staff</th>
                                <th class="table-headin">Time Slot</th>
                                <th class="table-headin">Available Slots</th>
                                <th class="table-headin">Status</th>
                                <th class="table-headin">Events</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                            <?php
                                $result = $database->query($sqlmain);

                                if($result->num_rows==0){
                                    echo '<tr>
                                    <td colspan="6">
                                    <br><br><br><br>
                                    <center>
                                    <img src="../img/notfound.svg" width="25%">
                                    
                                    <br>
                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">No time slots found!</p>
                                    <a class="non-style-link" href="schedule.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Time Slots &nbsp;</font></button>
                                    </a>
                                    </center>
                                    <br><br><br><br>
                                    </td>
                                    </tr>';
                                } else {
                                    for ($x=0; $x<$result->num_rows; $x++){
                                        $row = $result->fetch_assoc();
                                        $slot_id = $row["slot_id"];
                                        $service_name = $row["service_name"];
                                        $category = $row["category"];
                                        $stuname = $row["stuname"];
                                        $department = $row["department"];
                                        $start_time = $row["start_time"];
                                        $end_time = $row["end_time"];
                                        $booked_count = $row["booked_count"];
                                        $is_active = $row["is_active"];
                                        $available_slots = 3 - $booked_count;
                                        
                                        echo '<tr>
                                            <td> &nbsp;'.
                                            $service_name.'<br><span style="font-size:12px;color:var(--primarycolor);">'.$category.'</span>'
                                            .'</td>
                                            <td>'.
                                            $stuname.'<br><span style="font-size:12px;color:var(--primarycolor);">'.$department.'</span>'
                                            .'</td>
                                            <td style="text-align:center;">'.
                                            date("h:i A", strtotime($start_time)).' - '.date("h:i A", strtotime($end_time))
                                            .'</td>
                                            <td style="text-align:center;">'.
                                            $available_slots.'/3'
                                            .'</td>
                                            <td style="text-align:center;">'.
                                            ($is_active ? '<span style="color:var(--success);">Active</span>' : '<span style="color:var(--danger);">Inactive</span>')
                                            .'</td>
                                            <td>
                                            <div style="display:flex;justify-content: center;">
                                            <a href="?action=edit-slot&id='.$slot_id.'" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-edit" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Edit</font></button></a>
                                            &nbsp;&nbsp;&nbsp;
                                            <a href="?action=delete-slot&id='.$slot_id.'" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-delete" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Delete</font></button></a>
                                            </div>
                                            </td>
                                        </tr>';
                                    }
                                }
                            ?>
 
                            </tbody>

                        </table>
                        </div>
                        </center>
                   </td> 
                </tr>
                       
                        
                        
            </table>
        </div>
    </div>
    <?php
    
    if($_GET){
        $id=$_GET["id"];
        $action=$_GET["action"];
        if($action=='add-session'){

            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    
                    
                        <a class="close" href="schedule.php">&times;</a> 
                        <div style="display: flex;justify-content: center;">
                        <div class="abc">
                        <table width="80%" class="sub-table scrolldown add-stu-form-container" border="0">
                        <tr>
                                <td class="label-td" colspan="2">'.
                                   ""
                                
                                .'</td>
                            </tr>

                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Add New Session.</p><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                <form action="add-session.php" method="POST" class="add-new-form">
                                    <label for="title" class="form-label">Session Title : </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="text" name="title" class="input-text" placeholder="Name of this Session" required><br>
                                </td>
                            </tr>
                            <tr>
                                
                                <td class="label-td" colspan="2">
                                    <label for="stuid" class="form-label">Select Staff: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <select name="stuid" id="" class="box" >
                                    <option value="" disabled selected hidden>Choose Staff Name from the list</option><br/>';
                                        
        
                                        $list11 = $database->query("select  * from  staff order by stuname asc;");
        
                                        for ($y=0;$y<$list11->num_rows;$y++){
                                            $row00=$list11->fetch_assoc();
                                            $sn=$row00["stuname"];
                                            $id00=$row00["stuid"];
                                            echo "<option value=".$id00.">$sn</option><br/>";
                                        };
        
        
        
                                        
                        echo     '       </select><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nop" class="form-label">Number of Residents/Appointment Numbers : </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="number" name="nop" class="input-text" min="0"  placeholder="The final appointment number for this session depends on this number" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="date" class="form-label">Session Date: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="date" name="date" class="input-text" min="'.date('Y-m-d').'" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="time" class="form-label">Schedule Time: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="time" name="time" class="input-text" placeholder="Time" required><br>
                                </td>
                            </tr>
                           
                            <tr>
                                <td colspan="2">
                                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                
                                    <input type="submit" value="Place this Session" class="login-btn btn-primary btn" name="shedulesubmit">
                                </td>
                
                            </tr>
                           
                            </form>
                            </tr>
                        </table>
                        </div>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';
        }elseif($action=='session-added'){
            $titleget=$_GET["title"];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br>
                        <h2>Session Placed.</h2>
                        <a class="close" href="schedule.php">&times;</a>
                        <div class="content">
                        '.substr($titleget,0,40).' was scheduled.<br><br>
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        
                        <a href="schedule.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>
                        <br><br><br><br>
                        </div>
                    </center>
            </div>
            </div>
            ';
        }elseif($action=='drop'){
            $nameget=$_GET["name"];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Are you sure?</h2>
                        <a class="close" href="schedule.php">&times;</a>
                        <div class="content">
                            You want to delete this record<br>('.substr($nameget,0,40).').
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <a href="delete-session.php?id='.$id.'" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"<font class="tn-in-text">&nbsp;Yes&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                        <a href="schedule.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>

                        </div>
                    </center>
            </div>
            </div>
            '; 
        }elseif($action=='view'){
            $sqlmain= "select schedule.scheduleid,schedule.title,staff.stuname,schedule.scheduledate,schedule.scheduletime,schedule.nop from schedule inner join staff on schedule.stuid=staff.stuid  where  schedule.scheduleid=$id";
            $result= $database->query($sqlmain);
            $row=$result->fetch_assoc();
            $stuname=$row["stuname"];
            $scheduleid=$row["scheduleid"];
            $title=$row["title"];
            $scheduledate=$row["scheduledate"];
            $scheduletime=$row["scheduletime"];
            
           
            $nop=$row['nop'];


            $sqlmain12= "select * from appointment inner join resident on resident.resid=appointment.resid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.scheduleid=$id;";
            $result12= $database->query($sqlmain12);
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup" style="width: 70%;">
                    <center>
                        <h2></h2>
                        <a class="close" href="schedule.php">&times;</a>
                        <div class="content">
                            
                            
                        </div>
                        <div class="abc scroll" style="display: flex;justify-content: center;">
                        <table width="80%" class="sub-table scrolldown add-stu-form-container" border="0">
                        
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">View Details.</p><br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Session Title: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$title.'<br><br>
                                </td>
                                
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Email" class="form-label">Staff of this session: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$resname.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nic" class="form-label">Scheduled Date: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$scheduledate.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Scheduled Time: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$scheduletime.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label"><b>Residents that Already registerd for this session:</b> ('.$result12->num_rows."/".$nop.')</label>
                                    <br><br>
                                </td>
                            </tr>

                            
                            <tr>
                            <td colspan="4">
                                <center>
                                 <div class="abc scroll">
                                 <table width="100%" class="sub-table scrolldown" border="0">
                                 <thead>
                                 <tr>   
                                        <th class="table-headin">
                                             Resident ID
                                         </th>
                                         <th class="table-headin">
                                             Resident name
                                         </th>
                                         <th class="table-headin">
                                             
                                             Appointment number
                                             
                                         </th>
                                        
                                         
                                         <th class="table-headin">
                                             Resident Telephone
                                         </th>
                                         
                                 </thead>
                                 <tbody>';
                                 
                
                
                                         
                                         $result= $database->query($sqlmain12);
                
                                         if($result->num_rows==0){
                                             echo '<tr>
                                             <td colspan="7">
                                             <br><br><br><br>
                                             <center>
                                             <img src="../img/notfound.svg" width="25%">
                                             
                                             <br>
                                             <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We  couldnt find anything related to your keywords !</p>
                                             <a class="non-style-link" href="appointment.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Appointments &nbsp;</font></button>
                                             </a>
                                             </center>
                                             <br><br><br><br>
                                             </td>
                                             </tr>';
                                             
                                         }
                                         else{
                                         for ( $x=0; $x<$result->num_rows;$x++){
                                             $row=$result->fetch_assoc();
                                             $apponum=$row["apponum"];
                                             $resid=$row["resid"];
                                             $resname=$row["resname"];
                                             $restel=$row["restel"];
                                             
                                             echo '<tr style="text-align:center;">
                                                <td>
                                                '.substr($resid,0,15).'
                                                </td>
                                                 <td style="font-weight:600;padding:25px">'.
                                                 
                                                 substr($resname,0,25)
                                                 .'</td >
                                                 <td style="text-align:center;font-size:23px;font-weight:500; color: var(--btnnicetext);">
                                                 '.$apponum.'
                                                 
                                                 </td>
                                                 <td>
                                                 '.substr($restel,0,25).'
                                                 </td>
                                                 
                                                 
                
                                                 
                                             </tr>';
                                             
                                         }
                                     }
                                          
                                     
                
                                    echo '</tbody>
                
                                 </table>
                                 </div>
                                 </center>
                            </td> 
                         </tr>

                        </table>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';  
    }
}
        
    ?>
    </div>

</body>
</html>