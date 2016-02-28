<?php
include_once 'dbconnect.php';

     $start_date = $_POST['interval'];
     $activity = $_POST['activity'];
     $end_date = date('Y-m-d', strtotime($start_date." + 1 Month"));



              if($activity != 'all'){
                  $result_notification_KTO = mysqli_query($db, "SELECT * FROM $table_notification INNER JOIN $table_employees ON $table_employees.Email = $table_notification.p_Email_KTO WHERE Activity = '$activity' AND TimeStamp BETWEEN '$start_date' AND '$end_date'");
                   $result_notification_KOMU = mysqli_query($db, "SELECT * FROM $table_notification INNER JOIN $table_employees ON $table_employees.Email = $table_notification.p_Email_KOMU  WHERE Activity = '$activity' AND TimeStamp BETWEEN '$start_date' AND '$end_date'");
                   $result_notification_KOHO = mysqli_query($db, "SELECT * FROM $table_notification INNER JOIN $table_employees ON $table_employees.Email = $table_notification.p_Email_KOHO  WHERE Activity = '$activity' AND TimeStamp BETWEEN '$start_date' AND '$end_date'");

                  //$result_notification = mysqli_query($db, "SELECT * FROM $table_notification WHERE Activity='$activity' AND TimeStamp = '$interval'");
              }
              if($activity == 'all'){
                   $result_notification_KTO = mysqli_query($db, "SELECT * FROM $table_notification INNER JOIN $table_employees ON $table_employees.Email = $table_notification.p_Email_KTO  WHERE TimeStamp BETWEEN '$start_date' AND '$end_date'");
                   $result_notification_KOMU = mysqli_query($db, "SELECT * FROM $table_notification INNER JOIN $table_employees ON $table_employees.Email = $table_notification.p_Email_KOMU  WHERE TimeStamp BETWEEN '$start_date' AND '$end_date'");
                   $result_notification_KOHO = mysqli_query($db, "SELECT * FROM $table_notification INNER JOIN $table_employees ON $table_employees.Email = $table_notification.p_Email_KOHO  WHERE TimeStamp BETWEEN '$start_date' AND '$end_date'");
              }

            while ($row_notification_KTO = mysqli_fetch_assoc($result_notification_KTO)){
                $row_notification_KOHO = mysqli_fetch_assoc($result_notification_KOHO);
                $row_notification_KOMU = mysqli_fetch_assoc($result_notification_KOMU);
                 if ($row_notification_KTO['Activity'] == 'prihlasenie'){
                    include('template/notifications_success.php');

                 }
                 else{

                     include('template/notifications_alert.php');
                 }
             }


?>
