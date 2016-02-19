<?php
    session_start();

    include_once 'dbconnect.php';

    if(!isset($_SESSION['user'])){
        header("Location: index.php");
    }

    $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE User_ID=".$_SESSION['user']);
    $userRow=mysqli_fetch_array($result);

    if(isset($_POST['btn-signup'])){

        $first_name = mysqli_real_escape_string($db,$_POST['first_name']);
        $surname = mysqli_real_escape_string($db,$_POST['surname']);
        //$password = md5(mysqli_real_escape_string($db,$_POST['password']));
        $email =  mysqli_real_escape_string($db,$_POST['email']);
        $permission = mysqli_real_escape_string($db,$_POST['permissions']);
        $tempPass = md5(rand(1000, 1000000));


        if(mysqli_query($db,"INSERT INTO $table_employees(First_Name,Surname,Password,Email,Permissions) VALUES('$first_name','$surname','$tempPass','$email','$permission')")){ ?>

    <script>
        alert('successfully registered ');
    </script>

    <?php
        //Parameters for sending mail
        //nastavit registracny email uzivatelov/brigadnikov
        $to = $email;
        $subject = 'Registracia brigadnika';
        $message = "Prihlasovacie udaje:
                        Prihlasovaci email: $email

                    Kliknite tu pre vytvorenia vasho hesla a dokoncenie registrácie
                    http://localhost/createPass.php?tempPass=$tempPass&email=$email";


        $headers = 'From: vtstudentplanner.cz'."\r\n" .
            'Reply-To: ' . $to . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

       if(mail($to, $subject, $message, $headers)){
           echo 'uspesne odoslany email';
       }
    else {
          echo 'chyba pri posielani emailu';
      }
        }
        else{
?>

        <script>
            alert('error while registering you...');
        </script>

        <?php
        }

         mysqli_close($db);
    }
?>
