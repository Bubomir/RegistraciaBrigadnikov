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
        $userRow = mysqli_fetch_array($query);

        $ID_NON_ADMIN = 'non-admin';
        $ID_NON_BRIGADNIK = 'non-brigadnik';
        $ID_NON_SUPERVIZOR = 'non-supervizor';

        $db_email = $userRow['Email'];
        if  ($numrow!=0 && $userRow['Permissions'] != $ID_NON_ADMIN && $userRow['Permissions'] != $ID_NON_BRIGADNIK && $userRow['Permissions'] != $ID_NON_SUPERVIZOR)
        {
           
            if ($email == $db_email)
            {
                $code = md5(rand(1000, 1000000));

                $to = $db_email;
                $subject = "Resetování hesla";
                $body = "Zde klikněte, nebo vložte tento link do vašeho prohlížeče pro resetování hesla: <br>
                http://vtstudentplanner.cz/reset_pass.php?code=$code&email=$email";
                $headers = 'From: noreply@vtstudentplanner.cz'."\r\n" . 'Content-type:text/html;charset=UTF-8' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

                mysqli_query($db,"UPDATE $table_employees SET Reset_Password='$code' WHERE Email='$email' ");

                mail($to, $subject, $body, $headers);

                $alert_message = 'Link pro resetování hesla byl zaslán na Váš e-mail.';
                include ('template/alert_message_success.php');
            }
            else
            {
                $alert_message = 'E-mailová adresa není správná!';
                include ('template/alert_message.php');
            }
        }
        else
        {
            $alert_message = 'E-mailová adresa není správna alebo není aktivován účet!';
            include ('template/alert_message.php');
        }
    }

    $button_name = 'Poslat';
    include('template/login_box_button.php');

    include('template/footer.php');

?>

