<?php
include_once 'dbconnect.php';
$type = $_POST['type'];
    
    
if($type == 'session_check'){
    session_start();
    
    $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE User_ID=".$_SESSION['user']);
    $userRow=mysqli_fetch_array($result);
            
    if(session_id() != $userRow['Session_ID']){    
        session_destroy();
        unset($_SESSION['user']);
        echo json_encode(array('status'=>'success'));
    }
    else{
        echo json_encode(array('status'=>'failed'));                   
    }
}
if($type == 'duplicity_ceck'){
    $start_date = $_POST['startDate'].'+'.$_POST['zone'];
    $email_hash = $_POST['emailHash'];
    $e_email;
    $result = mysqli_query($db, "SELECT Email FROM $table_employees WHERE Permissions='supervizor'");
    while($fetch = mysqli_fetch_array($result,MYSQLI_ASSOC)){
         if ($email_hash == md5($fetch['Email'])){
                $e_email = $fetch['Email'];
        }
     }
    $query = mysqli_query($db, "SELECT * FROM $table_calendar WHERE p_Email='$e_email' AND Start_Date='$start_date'");
    $count = mysqli_num_rows($query);

        if($count == 0){
            echo 'failed';
        }
        else{
            echo 'success';
        }
}

if($type == 'new'){
	
    $start_date = $_POST['start_date'].'+'.$_POST['zone'];
    $end_date = date('c', strtotime($start_date." + 12 Hours"));
	$email = $_POST['email'];
    $capacity = $_POST['capacity'];
    $logged_in = $_POST['logged_in'];
    $color = $_POST['color'];
    
    
    $result = mysqli_query($db, "SELECT Email FROM $table_employees WHERE Permissions='supervizor'");
    while($fetch = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        if ($email == md5($fetch['Email'])){
            $insert = mysqli_query($db,"INSERT INTO $table_calendar(p_Email, Start_Date, End_Date, Capacity, Logged_In, Color) VALUES('".$fetch['Email']."','$start_date','$end_date','$capacity','$logged_in','$color')");
            $lastid = mysqli_insert_id($db);
                
            if($insert){
                echo json_encode(array('status'=>'success','write to database'+'eventid'=>$lastid));
            }
            else{
                echo json_encode(array('status'=>'failed','write to database'));
            }
        }
        else{
            echo json_encode(array('status'=>'failed','email equal'));
        }
	}


}
if($type == 'changeCapacity')
{
	$event_id = $_POST['eventID'];
	$capacity = $_POST['capacity'];
    $query = mysqli_query($db, "SELECT * FROM $table_calendar WHERE ID='$event_id'");
    $fetch = mysqli_fetch_array($query);
    $e_logged_in = $fetch['Logged_In'];
    if($capacity<$e_logged_in){
        echo 'failed';
    }
    else{
        if($capacity!=0){
            $update = mysqli_query($db,"UPDATE $table_calendar SET Capacity='$capacity' WHERE ID='$event_id'");
            if($update){
                echo 'success';
            }
        }
        else{
            $delete = mysqli_query($db,"DELETE FROM $table_calendar where ID='$event_id'");
        }
    }
}
if($type == 'check_log_in_log_out'){
    $event_id = $_POST['event_id'];
    $email = $_POST['email'];
    $query = mysqli_query($db, "SELECT * FROM $table_calendar WHERE ID='$event_id'");
    $fetch = mysqli_fetch_array($query);
    $e_start_date = $fetch['Start_Date'];
    $result = mysqli_query($db,"SELECT ID FROM $table_calendar WHERE Start_Date='$e_start_date' AND p_Email='$email'");
    $count = mysqli_num_rows($result);
    echo $count;
}
if($type == 'change_number_of_logged_in'){
	$event_id = $_POST['event_id'];
	$logIn_logOut = $_POST['logIn_logOut'];
    $email = $_POST['email'];
   
    $query = mysqli_query($db, "SELECT * FROM $table_calendar WHERE ID='$event_id'");
    $fetch = mysqli_fetch_array($query);
	
    $e_logged_in=$fetch['Logged_In'];
    $e_capacity = $fetch['Capacity'];
    $e_start_date = $fetch['Start_Date'];
    $end_date = date('c', strtotime($e_start_date." + 12 Hours"));

    $result = mysqli_query($db,"SELECT ID FROM $table_calendar WHERE Start_Date='$e_start_date' AND p_Email='$email'");
    $fetch_1 = mysqli_fetch_array($result);
    
    $del_id = $fetch_1['ID'];
    
     //Zistenie rozdielu dni pre odhlasenie
    $click_time = new DateTime($e_start_date);
    $current_time = new DateTime(date('c'));
    $interval_time = $current_time->diff($click_time);
    switch($logIn_logOut){
        case 1:{
            
            //Osetrenie voci prektroceniu kapacity na smene
            if($e_logged_in < $e_capacity){
                //inkrementacia poctu pre prihlasenie
                $e_logged_in++;
                $update = mysqli_query($db,"UPDATE $table_calendar SET Logged_In='$e_logged_in' where ID='$event_id'");
                $insert = mysqli_query($db,"INSERT INTO $table_calendar(p_Email, Start_Date, End_Date, Capacity, Logged_In) VALUES('$email','$e_start_date','$end_date','null','null')");
            }
            else{
                $update = false;
            }
                      
            break;
        }
            
        case -1:{
            
            //dekrementacia poctu pre prihlasenie
           
            if((int)($interval_time->format('%a'))>5 && $e_logged_in>0){
                $e_logged_in--;
                $update = mysqli_query($db,"UPDATE $table_calendar SET Logged_In='$e_logged_in' WHERE ID='$event_id'");
                $delete = mysqli_query($db,"DELETE FROM $table_calendar WHERE ID='$del_id'");
            }
            else{
                $update = false;
            }
            
            break;
        }
    }
    
    if($update)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}
/*
if($type == 'resetdate')
{
	$title = $_POST['title'];
	$startdate = $_POST['start'];
	$enddate = $_POST['end'];
	$eventid = $_POST['eventid'];
	$update = mysqli_query($conn,"UPDATE $table_smeny SET title='$title', startdate = '$startdate', enddate = '$enddate' where id='$eventid'");
	if($update)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}
*/
if($type == 'remove')
{
	$event_id = $_POST['event_id'];
	
    $query = mysqli_query($db, "SELECT * FROM $table_calendar INNER JOIN $table_employees ON $table_employees.Email = $table_calendar.p_Email WHERE ID='$event_id'");
    $fetch = mysqli_fetch_array($query,MYSQLI_ASSOC);
    
    $e_start_time = $fetch['Start_Date'];
    $e_permissions = $fetch['Permissions'];
   
   
    
    if($e_permissions=='brigadnik'){
        $email = 'brigadnici@brigadnici.sk';
        $query_2 = mysqli_query($db, "SELECT * FROM $table_calendar WHERE Start_Date='$e_start_time' AND p_Email='$email'");
        $fetch_2 = mysqli_fetch_array($query_2,MYSQLI_ASSOC);
        $e_id = $fetch_2['ID'];
        $e_logged_in = $fetch_2['Logged_In'];
        $e_logged_in--; //decrementation capacity on brigadnici Event
        
        
         
        $update = mysqli_query($db,"UPDATE $table_calendar SET Logged_In='$e_logged_in' WHERE ID='$e_id'");
        $delete = mysqli_query($db,"DELETE FROM $table_calendar where ID='$event_id'");
        
    }
    else{
         $delete = mysqli_query($db,"DELETE FROM $table_calendar where ID='$event_id'");
    }
    
    
    
    
	if($delete)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}
if($type == 'fetch')
{
	$events = array();
	$query = mysqli_query($db, "SELECT * FROM $table_calendar INNER JOIN $table_employees ON $table_employees.Email = $table_calendar.p_Email");

        while($fetch = mysqli_fetch_array($query,MYSQLI_ASSOC)){
        $e = array();
        $e['id'] = $fetch['ID'];
        
        if($fetch['Permissions']=='supervizor'){
            if($fetch['p_Email'] == "brigadnici@brigadnici.sk"){
                $e['title'] = ' '.$fetch['First_Name'].': '.$fetch['Logged_In'].'/'.$fetch['Capacity'];
            }
            else{
                $e['title'] =  '  '.$fetch['First_Name'].' '.$fetch['Surname'];
            }
        }
        else{
            $e['title'] = $fetch['First_Name'].' '.$fetch['Surname'];
        }
     
        $e['start'] = $fetch['Start_Date'];
        $e['end'] = $fetch['End_Date'];
        $e['color'] = $fetch['Color'];
       array_push($events, $e);
	}   
    
	echo json_encode($events);
}
if($type == 'get_default_date'){
    echo date('Y-m-d');
}
if($type == 'get_now'){
    echo date('c');
}

if($type == 'get_loggedEmail'){
    session_start();

    $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE User_ID=".$_SESSION['user']);
    $userRow=mysqli_fetch_array($result);
    echo $userRow['Email'];
}

if($type == 'get_loggedPermissions'){
    session_start();

    $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE User_ID=".$_SESSION['user']);
    $userRow=mysqli_fetch_array($result);
    $nieco = $userRow['Permissions'];
    echo $userRow['Permissions'];
}
?>
