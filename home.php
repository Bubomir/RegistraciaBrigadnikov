<?php
    session_start();
    include 'dbconnect.php';

    $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE User_ID=".$_SESSION['user']);
    $userRow=mysqli_fetch_array($result);

    if(!isset($_SESSION['user'])){
         header("Location: index.php");
    }



    mysqli_close($db);

    include('template/home_head.php');
    include('template/home_menu.php');
    include('template/home_main.php');
    include('template/registration_modal.php');
    include('template/notifications_modal.php');
    include('template/popup_info.php');
    include('template/home_footer.php');
?>


