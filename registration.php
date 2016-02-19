<?php
    include_once 'dbconnect.php';

        $first_name = $_POST['first_name'];
        $surname = $_POST['surname'];
        $email =  $email = $_POST['email'];
        $permission = $_POST['permissions'];
        $tempPass = md5(rand(1000, 1000000));

        if(mysqli_query($db,"INSERT INTO $table_employees(First_Name,Surname,Password,Email,Permissions) VALUES('$first_name','$surname','$tempPass','$email','$permission')")){
            echo true;
        $to = $email;
        $subject = 'Registracia brigadnika';
        $message = "Prihlasovacie udaje:
                        Prihlasovaci email: $email

                    Kliknite tu pre vytvorenia vasho hesla a dokoncenie registrácie
                    http://vtstudentplanner.cz/createPass.php?tempPass=$tempPass&email=$email";


        $headers = 'From: vtstudentplanner.cz'."\r\n" .
            'Reply-To: ' . $to . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

            if(!mail($to, $subject, $message, $headers)){
           //echo 'uspesne odoslany email';
        }
        }
        else{
          echo false;
        }

         mysqli_close($db);

?>
