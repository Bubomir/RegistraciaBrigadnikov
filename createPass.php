<?php
    include_once 'dbconnect.php';
    
   //Check if hyperlink is provideng required data
    if($_GET['tempPass'] && $_GET['email'])
    {
        $get_tempPass = $_GET['tempPass'];
        $get_email = $_GET['email'];
        
        //Seleceting row from database with provided email from hyperlink 
        $sql = "SELECT * FROM $table_employees WHERE email = '$get_email'";
        $query = mysqli_query($db, $sql);
        
        //checking the match of code and email from on row
        while($row = mysqli_fetch_assoc($query))
        {
            $db_email = $row['Email'];
            $db_tempPass = $row['Password'];
        }
        
        $isRegistered = false;
        if($get_email == $db_email && $get_tempPass == $db_tempPass)
        {
            //Ahoj
            //HERE STARTING TEMPLATE, ORDER VERY IMPORTANT DONT MESS WITH IT!!!!!
            include ('template/head.php');
    
            $header_name = 'Dokončení registrace';
            include ('template/login_box_header.php');

            include('template/login_box_create_password.php');
            
            if(isset($_POST['submit']))
            {
                $newPass = $_POST['newPass'];
                $checkNewPass = $_POST['checkNewPass'];
                
                if($newPass == $checkNewPass)
                {
                    //Encrypting a new password
                    $enc_pass = md5($newPass);
                    
                    //Updating a database with a new password
                    mysqli_query($db, "UPDATE $table_employees SET Password = '$enc_pass' WHERE Email = '$get_email'");
                    $isRegistered = true;
                    $alert_message = 'Vaše heslo bylo vytvořeno.';
                    include ('template/alert_message_success.php');
                    mysqli_close($db);
                }
                else
                {
                    $alert_message = 'Heslo se musí shodovat!';
                    include ('template/alert_message.php');
                }
            }
            $button_name = 'Vytvořit';
            include('template/login_box_button.php');
            include('template/footer.php');
        }
        else
        {
            include ('template/head.php');
            
            $header_name = 'Prošlý';
            include ('template/login_box_header.php');
            include('template/login_box_expired.php');
            $alert_message = 'Tomuto linku vypršela platnost!';
            include('template/alert_message.php');
            $button_name = null;
            include('template/login_box_button.php');
            include('template/footer.php');
        }
    }

?>
