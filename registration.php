<?php
    include_once 'dbconnect.php';

    //Variabiles acquired from ajax throught registration form
    $first_name = $_POST['first_name'];
    $surname = $_POST['surname'];
    $email =  $email = $_POST['email'];
    $permission = $_POST['permissions'];
    $tempPass = md5(rand(1000, 100000));

        $result_email = mysqli_query($db,"SELECT User_ID FROM $table_employees WHERE Email = '$email' ");
        if(mysqli_num_rows($result_email) == 0){

            if(mysqli_query($db,"INSERT INTO $table_employees(First_Name,Surname,Password,Email,Permissions) VALUES('$first_name','$surname','$tempPass','$email','$permission')")){
                echo true;


                //Email content
                $to = $email;
                $subject = 'Vytvoření hesla pro váš účet';
                $message = "Přihlašovací e-mail: $email <br><br>
                Zde klikněte nebo vložte tento link do vašeho prohlížeč pro vytvoření vašeho hesla a dokončení vaší registrace: <br>
                http://vtstudentplanner.cz/createPass.php?tempPass=$tempPass&email=$email";
                $headers = 'From: noreply@vtstudentplanner.cz'."\r\n" . 'Content-type:text/html;charset=UTF-8' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers);
            } else{
              echo false;
            }
        } else{
            echo false;
        }

    mysqli_close($db);
?>