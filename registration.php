<?php
    include_once 'dbconnect.php';

    //Variabiles acquired from ajax throught registration form
    $first_name = $_POST['first_name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $mobile_number = $_POST['mobile_number'];
    $permission = $_POST['permissions'];
    $tempPass = md5(rand(1000, 100000));
    $change_number = $_POST['change_number'];

    //Variabiles for alerts
    $success = 1;
    $alertEmailExists = 2;
    $alertRegFail = 3;
    $alertChange_Num = 4;

    if($permission == 'non-supervizor' || $permission == 'non-admin' || $permission == 'non-brigadnik'){
        $result_change_num = mysqli_query($db,"SELECT User_ID FROM $table_employees WHERE Change_Number = '$change_number' ");
        if(mysqli_num_rows($result_change_num) == 0){

            $result_email = mysqli_query($db,"SELECT User_ID FROM $table_employees WHERE Email = '$email' ");

            if(mysqli_num_rows($result_email) == 0){

                if(mysqli_query($db,"INSERT INTO $table_employees(First_Name,Surname,Password,Email,Permissions,Mobile_Number,Change_Number) VALUES('$first_name','$surname','$tempPass','$email','$permission','$mobile_number','$change_number')")){
                    echo $success;

                    //Email content
                    $to = $email;
                    $subject = 'Vytvoření hesla pro váš účet';
                    $message = "Přihlašovací e-mail: $email <br><br>
                    Zde klikněte nebo vložte tento link do vašeho prohlížeč pro vytvoření vašeho hesla a dokončení vaší registrace: <br>
                    http://vtstudentplanner.cz/createPass.php?tempPass=$tempPass&email=$email";
                    $headers = 'From: noreply@vtstudentplanner.cz'."\r\n" . 'Content-type:text/html;charset=UTF-8' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

                    mail($to, $subject, $message, $headers);
                } else{
                    echo $alertRegFail;
                    }
            } else{
                echo $alertEmailExists;
                }
        }
        else{
            echo $alertChange_Num;
        }
    }
    else{
        if(mysqli_num_rows($result_change_num) == 0){

            $result_email = mysqli_query($db,"SELECT User_ID FROM $table_employees WHERE Email = '$email' ");

            if(mysqli_num_rows($result_email) == 0){

                if(mysqli_query($db,"INSERT INTO $table_employees(First_Name,Surname,Password,Email,Permissions,Mobile_Number,Change_Number) VALUES('$first_name','$surname','$tempPass','$email','$permission','$mobile_number','$change_number')")){
                    echo $success;

                    //Email content
                    $to = $email;
                    $subject = 'Vytvoření hesla pro váš účet';
                    $message = "Byl vám vytvořen účet na stránke www.vtstudentplanner.cz, pro vytvoření hesla a aktivaci účtu je potrebné kliknout na link<br><br>
                    Přihlašovací e-mail: $email <br><br>
                    Zde klikněte nebo vložte tento link do vašeho prohlížeč pro vytvoření vašeho hesla a dokončení vaší registrace: <br>
                    http://vtstudentplanner.cz/createPass.php?tempPass=$tempPass&email=$email";
                    $headers = 'From: noreply@vtstudentplanner.cz'."\r\n" . 'Content-type:text/html;charset=UTF-8' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

                    mail($to, $subject, $message, $headers);
                } else{
                    echo $alertRegFail;
                    }
            } else{
                echo $alertEmailExists;
                }
            }
    }

    mysqli_close($db);
?>
