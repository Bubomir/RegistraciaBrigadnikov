<?php
    session_start();  

    include_once 'dbconnect.php';

    if(!isset($_SESSION['user'])){
        header("Location: index.php");
    }
    
    $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE User_ID=".$_SESSION['user']);
    $userRow=mysqli_fetch_array($result);
    
    if(isset($_POST['btn-signup'])){

        $first_name = mysqli_real_escape_string($db,$_POST['firstname']);
        $surname = mysqli_real_escape_string($db,$_POST['surname']);
        //$password = md5(mysqli_real_escape_string($db,$_POST['password']));
        $email =  mysqli_real_escape_string($db,$_POST['email']);
        $permission = mysqli_real_escape_string($db,$_POST['permission']);
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
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">

            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>Login & Registration System</title>
                <link rel="stylesheet" href="style.css" type="text/css" />

            </head>

            <body>
                <center>
                    <div id="login-form">
                        <form method="post">
                            <table align="center" width="30%" border="0">
                                <tr>
                                    <td>
                                        <input type="text" name="firstname" placeholder="Meno" required />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" name="surname" placeholder="Priezvisko" required />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="email" name="email" placeholder="Váš email" required />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Typ účtu:
                                        <?php
                                            switch($userRow['Permissions']){
                                                case "admin":
                                        ?>
                                            <input type="radio" name="permission" <?php if (isset($permission) && $permission=="admin" );?> value="admin">Admin
                                            <input type="radio" name="permission" <?php if (isset($permission) && $permission=="supervizor" );?> value="supervizor">Supervizor
                                            <input type="radio" name="permission" <?php if (isset($permission) && $permission=="brigadnik" );?> value="brigadnik" <?php echo "checked" ?> >Brigadnik
                                            <?php
                                                    break;
                                                case "supervizor"
                                            ?>
                                                <input type="radio" name="permission" <?php if (isset($permission) && $permission=="brigadnik" ) ;?> value="brigadnik" <?php echo "checked" ?> >User
                                                <?php
                                                    break;
                                                default:
                                                    break;
                                                    
                                            }
                                        ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <button type="submit" name="btn-signup">Registrovať</button>
                                    </td>
                                </tr>

                            </table>
                            <div id="right">
                                <div id="content">
                                    Ahoj
                                    <?php echo $userRow['First_Name']; ?>&nbsp;<a href="logout.php?logout">Odhlásiť</a>
                                </div>

                            </div>
                        </form>
                    </div>
                </center>
            </body>

            </html>
