<?php
    include_once 'dbconnect.php';
    
    //HERE STARTING TEMPLATE, ORDER VERY IMPORTANT DONT MESS WITH IT!!!!!
    include ('template/head.php');
    
    $header_name = 'Zapomněli jste heslo';
    include ('template/login_box_header.php');

    include ('template/login_box_forgoted_password.php');

    if (isset($_POST['submit']))
    {
        $email = $_POST['email'];
        $sql = "SELECT * FROM $table_employees WHERE Email='$email'";
        $query = mysqli_query($db,$sql);
        $numrow = mysqli_num_rows($query);
        
        if  ($numrow!=0)
        {
            while($row = mysqli_fetch_assoc($query))
            {
                $db_email = $row['Email'];
            }
            if ($email == $db_email)
            {
                $code = md5(rand(1000, 1000000));

                $to = $db_email;
                $subject = "Password Reset";
                $body = "
                This is an automate email. Please DO NOT REPLY to this email.
                
                Click the ling below or paste it into your brosers:
                http://localhost/resetPass.php?code=$code&email=$email
                ";  
                
                mysqli_query($db,"UPDATE $table_employees SET Reset_Password='$code' WHERE Email='$email' ");
                
                mail($to, $subject, $body);
                $alert_message = 'Link pro resetování hesla byl poslán k vám na e-mail.';
                include ('template/alert_message_success.php');
            }
            else
            {
                $alert_message = 'E-mailová adresa není správna!';
                include ('template/alert_message.php');
            }
        }
        else
        {
            $alert_message = 'E-mailová adresa není správna!';
            include ('template/alert_message.php');
        }
    }
   
    $button_name = 'Poslat';
    include('template/login_box_button.php');
    
    include('template/footer.php');

?>
                               
