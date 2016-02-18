<?php

    ob_start();
    
    $sessionID = md5(time()*rand()+$_SERVER['REMOTE_ADDR']);

    session_id($sessionID);
    session_start();

    include_once 'dbconnect.php';
    if(isset($_SESSION['user'])!=""){
        header("Location: home.php");
    }
    //HERE STARTING TEMPLATE, ORDER VERY IMPORTANT DONT MESS WITH IT!!!!!
    include ('template/head.php');
    $header_name = 'Přihlášení';
    include ('template/login_box_header.php');
    include('template/login_box_login.php');


        if(isset($_POST['submit'])){
            $email = mysqli_real_escape_string($db,$_POST['email']);
            $password = mysqli_real_escape_string($db,$_POST['password']);
            $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE Email='$email'");
            $row=mysqli_fetch_array($result);
            if($row['Password']==md5($password)){  
                              
                    
                $update = mysqli_query($db,"UPDATE $table_employees SET Session_ID='$sessionID' WHERE Email='$email'");
                if(!$update){
                    echo "error insert Session ID to DB";
                }
                $_SESSION['user'] = $row['User_ID'];
                header("Location: home.php");
            }
            else{
                $alert_message = 'E-mail nebo heslo není správné!';
                include ('template/alert_message.php');
            }
        }
    
    mysqli_close($db);

    $button_name = 'Přihlásit';
    include('template/login_box_button.php');

    include('template/footer.php');
    ob_end_flush();
?>

