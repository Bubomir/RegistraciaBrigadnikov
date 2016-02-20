<?php
    include_once 'dbconnect.php';

        $first_name = $_POST['first_name'];
        $surname = $_POST['surname'];
        $email =  $email = $_POST['email'];
        $permission = $_POST['permissions'];
        $tempPass = md5(rand(1000, 1000000));

        $result_email = mysqli_query($db,"SELECT User_ID FROM $table_employees WHERE Email = '$email' ");
        if(mysqli_num_rows($result_email) == 0){

            if(mysqli_query($db,"INSERT INTO $table_employees(First_Name,Surname,Password,Email,Permissions) VALUES('$first_name','$surname','$tempPass','$email','$permission')")){
                echo true;
            $to = $email;
            $subject = 'Registracia brigadnika';
            $message = "Prihlasovacie udaje:
                            Prihlasovaci email: $email

                        Kliknite tu pre vytvorenia vasho hesla a dokoncenie registrácie
                        http://localhost/createPass.php?tempPass=$tempPass&email=$email";


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
        }
        else{
            echo 'email already exists';
        }
         mysqli_close($db);

?>
