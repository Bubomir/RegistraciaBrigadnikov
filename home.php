<?php
    session_start();
    include_once 'dbconnect.php';

    $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE User_ID=".$_SESSION['user']);
    $userRow=mysqli_fetch_array($result);

    if(!isset($_SESSION['user'])){
         header("Location: index.php");
    }


    //Registration
    if(isset($_POST['btn-signup'])){
        $first_name = mysqli_real_escape_string($db,$_POST['first_name']);
        $surname = mysqli_real_escape_string($db,$_POST['surname']);
        $email =  mysqli_real_escape_string($db,$_POST['email']);
        $permission = mysqli_real_escape_string($db,$_POST['permissions']);
        $tempPass = md5(rand(1000, 1000000));
        $result_email = mysqli_query($db,"SELECT User_ID FROM $table_employees WHERE Email = '$email' ");
        if(mysqli_num_rows($result_email) == 0){

            if(mysqli_query($db,"INSERT INTO $table_employees(First_Name,Surname,Password,Email,Permissions) VALUES('$first_name','$surname','$tempPass','$email','$permission')")){

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
                mail($to, $subject, $message, $headers);
                echo 'succesfully registered';
                }
            else{
                    echo 'error while registering you';
                }
        }
        else{
            echo 'email already exists';
        }
    }


    mysqli_close($db);
?>
    <!doctype html>
    <html class="no-js" lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Foundation</title>
        <link rel="stylesheet" href="css/app.css" />
        <link rel="stylesheet" href="css/motion-ui.css" />
        <link href="css/fullcalendar.css" rel='stylesheet' />
        <link rel='stylesheet' href='css/fullcalendar.print.css' media='print' />
        <link href="css/style.css" rel='stylesheet' />
        <script src="js/modernizr.js"></script>
    </head>

    <body>
        <div class="main-box">
            <div class="row">
                <div class="large-12 medium-12 small-12 columns">
                    <div class="panel">
                        <div class="row">
                            <div class="large-1 medium-2 small-2 columns text-center">
                                <img class="logo main" src="img/vt_logo-baseline.png" alt="logo" />
                            </div>
                            <div class="large-7 medium-4 small-10 columns small-text-center medium-text-left large-text-left">
                                <div class="header-main">
                                    <?php echo $userRow['First_Name']. " " . $userRow['Surname']; ?>
                                </div>
                                <div class="header-main-smaller">
                                    <?php if ($userRow['Permissions'] == 'admin'){ ?>
                                        Správce
                                        <?php }elseif($userRow['Permissions'] == 'supervizor'){?>
                                            Supervízor
                                            <?php }elseif($userRow['Permissions'] == 'brigadnik'){?>
                                                Brigádník
                                                <?php }?>
                                </div>
                            </div>
                            <div class="large-2 medium-3 small-12 medium-push-3 large-push-2 columns text-right">


                                <form action="logout.php" method="post">
                                    <button type="submit" class="button custom-main expand" name="logout">Odhlásit</button>
                                </form>
                            </div>
                            <div class="large-2 medium-3 small-12 medium-pull-3 large-pull-2 columns">
                                <?php if($userRow['Permissions'] == 'admin' || $userRow['Permissions'] == 'supervizor') {?>
                                    <button data-toggle="animatedModal10" class="success button register expand">Registrace</button>
                                    <?php }?>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <div class="main-box">
            <div class="row">
                <?php if($userRow['Permissions'] == 'admin' || $userRow['Permissions'] == 'supervizor') {?>
                    <div class="large-2 medium-2 small-12 columns">
                        <div class="panel">
                            <div id="external-events">
                                <div class="row">

                                    <div class="large-12 medium-12 small-12 columns text-center">
                                        <div class="logo-morning">
                                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="612px" height="612px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
                                                <g>
                                                    <g id="_x34__10_">
                                                        <g>
                                                            <path d="M106.447,346.801c18.931-93.044,100.837-163.2,199.553-163.2s180.621,70.135,199.553,163.2h42.942
                                                                     c-15.688-115.077-118.137-204-242.495-204s-226.807,88.924-242.475,204H106.447z M61.2,192.046c7.976,7.977,20.89,7.977,28.845,0
                                                                     c7.977-7.976,7.977-20.889,0-28.845L61.2,134.355c-7.977-7.977-20.89-7.977-28.846,0s-7.977,20.89,0,28.846L61.2,192.046z
                                                                     M550.8,192.046l28.846-28.845c7.977-7.977,7.977-20.89,0-28.846s-20.89-7.977-28.846,0l-28.846,28.846
                                                                     c-7.977,7.976-7.977,20.89,0,28.845C529.91,200.022,542.823,200.022,550.8,192.046z M306,102c11.261,0,20.4-9.139,20.4-20.4V40.8
                                                                     c0-11.261-9.14-20.4-20.4-20.4s-20.4,9.139-20.4,20.4v40.8C285.6,92.861,294.739,102,306,102z M591.6,387.6H20.4
                                                                     C9.139,387.6,0,396.74,0,408c0,11.262,9.139,20.4,20.4,20.4h571.2c11.261,0,20.4-9.139,20.4-20.4
                                                                     C612,396.74,602.86,387.6,591.6,387.6z M510,550.801H102c-11.261,0-20.4,9.139-20.4,20.4c0,11.26,9.139,20.398,20.4,20.398h408
                                                                     c11.261,0,20.4-9.139,20.4-20.398C530.4,559.939,521.261,550.801,510,550.801z M571.2,469.201H40.8
                                                                     c-11.261,0-20.4,9.139-20.4,20.398c0,11.262,9.139,20.4,20.4,20.4h530.4c11.261,0,20.399-9.139,20.399-20.4
                                                                     C591.6,478.34,582.461,469.201,571.2,469.201z" />
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="large-12 medium-12 small-12 columns text-center">
                                        <div id="morning-change">
                                            Ranní změny
                                            <?php
                                             include 'dbconnect.php';
                                             $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE Permissions='supervizor' ORDER BY User_ID DESC");
                                             while ($row = mysqli_fetch_assoc($result)){
                                                 if($row['First_Name']!="Brigádnici"){
                                                      $meno = $row['First_Name'] . " ". $row['Surname'];
                                                     echo "<div class='fc-event' data-start='06:00:00' data-description='".md5($row['Email'])."' data-color = 'darkorange'>$meno</div>";
                                                 }
                                                 else{
                                                    echo "<div class='fc-event' data-start='06:00:00' data-description='".md5($row['Email'])."' data-color = 'green' style='background-color: green;'>".$row['First_Name']." R</div>";
                                                 }
                                             }

                                         ?>
                                        </div>
                                    </div>

                                    <div class="large-12 medium-12 small-12 columns">
                                        <div class="logo-change">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                                <g>
                                                    <g>
                                                        <path d="M50,22.44c15.198,0,27.56,12.367,27.56,27.562c0,15.197-12.362,27.559-27.56,27.559
                                                                        c-15.199,0-27.561-12.362-27.561-27.559C22.439,34.806,34.801,22.44,50,22.44 M50,12.5c-20.712,0-37.5,16.792-37.5,37.502
                                                                        C12.5,70.712,29.288,87.5,50,87.5c20.712,0,37.5-16.788,37.5-37.498C87.5,29.292,70.712,12.5,50,12.5L50,12.5z" />
                                                    </g>
                                                    <path d="M69.195,36.068l-3.897-3.902c-0.743-0.747-2.077-0.729-2.791,0L50.022,44.654l-6.863-6.863
                                                                    c-0.743-0.743-2.046-0.743-2.789,0l-3.892,3.893c-0.372,0.364-0.585,0.873-0.585,1.402c0,0.525,0.204,1.025,0.578,1.394
                                                                    l12.133,12.133c0.374,0.374,0.869,0.578,1.396,0.578c0.027,0,0.051,0,0.078,0c0.517-0.009,1-0.213,1.364-0.578l17.754-17.754
                                                                    C69.965,38.087,69.965,36.835,69.195,36.068z" />
                                                </g>
                                            </svg>
                                        </div>
                                    </div>


                                    <div class="large-12 medium-12 small-12 columns text-center">
                                        <div class="logo-night">
                                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="47.612px" height="47.612px" viewBox="0 0 47.612 47.612" style="enable-background:new 0 0 47.612 47.612;" xml:space="preserve">
                                                <g>
                                                    <path d="M14.626,23.917c-0.817-1.69-1.288-3.58-1.288-5.58c0-5.596,3.517-10.375,8.564-12.151
                                                    c-1.017,1.756-1.557,3.755-1.557,5.847c0,6.479,5.271,11.748,11.749,11.748c2.365,0,4.635-0.703,6.549-1.994
                                                    c-0.201,0.741-0.473,1.451-0.795,2.131h2.547c0.623-1.602,1-3.33,1.052-5.153c0.014-0.501-0.293-0.958-0.765-1.133
                                                    c-0.469-0.173-1-0.033-1.317,0.356c-1.796,2.188-4.445,3.444-7.271,3.444c-5.184,0-9.397-4.217-9.397-9.398
                                                    c0-2.608,1.053-5.036,2.963-6.836c0.364-0.344,0.471-0.883,0.265-1.341c-0.209-0.457-0.683-0.735-1.185-0.684
                                                    c-7.839,0.762-13.75,7.28-13.75,15.166c0,1.971,0.388,3.849,1.073,5.58h2.564V23.917z M1.121,30.055h45.37
                                                    c0.619,0,1.121-0.528,1.121-1.177s-0.5-1.174-1.121-1.174H1.121C0.502,27.705,0,28.23,0,28.878S0.502,30.055,1.121,30.055z
                                                    M46.491,34.753H1.121C0.501,34.753,0,35.279,0,35.929c0,0.646,0.501,1.175,1.121,1.175h45.37c0.618,0,1.121-0.527,1.121-1.175
                                                    C47.612,35.279,47.109,34.753,46.491,34.753z M46.491,42.096H1.121C0.501,42.096,0,42.621,0,43.271
                                                    c0,0.646,0.501,1.175,1.121,1.175h45.37c0.618,0,1.121-0.527,1.121-1.175C47.612,42.622,47.109,42.096,46.491,42.096z" />
                                                </g>
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="large-12 medium-12 small-12 columns text-center">
                                        <div id="night-change">
                                            Noční změny
                                            <?php
                                                include 'dbconnect.php';
                                                $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE Permissions='supervizor' ORDER BY User_ID DESC");
                                                while ($row = mysqli_fetch_assoc($result)){
                                                    if($row['First_Name']!="Brigádnici"){
                                                        $meno = $row['First_Name'] . " ". $row['Surname'];
                                                        echo "<div class='fc-event' data-start='18:00:00' data-description='".md5($row['Email'])."' data-color = 'black'>$meno</div>";
                                                    }
                                                    else{
                                                        echo "<div class='fc-event' data-start='18:00:00' data-description='".md5($row['Email'])."' data-color = 'green' style='background-color: green;'>".$row['First_Name']." N</div>";

                                                    }

                                                }

                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>

                    <?php }?>
                        <?php if($userRow['Permissions'] == 'supervizor' || $userRow['Permissions'] == 'admin'){?>
                            <div class="large-10 medium-10 small-12 columns">
                                <div class="panel">
                                    <div id='calendar'></div>
                                    <div style='clear:both'></div>
                                </div>
                            </div>
                            <?php }else { ?>
                                <div class="large-12 medium-12 small-12 columns">
                                    <div class="panel">
                                        <div id='calendar'></div>
                                        <div style='clear:both'></div>
                                    </div>
                                </div>
                                <?php }?>
            </div>
        </div>



        <div class="reveal medium" id="animatedModal10" data-reveal data-close-on-click="false" data-animation-in="slide-in-down" data-animation-out="slide-out-up">
            <div class="panel custom color">
                <div class="row">
                    <div class="large-10 medium-9 small-8 columns logo-header text-left">
                        <span>Registrace</span>
                    </div>
                    <div class="large-2 medium-3 small-4 columns text-right">
                        <img class="logo" src="img/logo.png" alt="Visko Teepak" />
                    </div>
                </div>
            </div>
            <div class="panel custom">
                <form method="post" id="registration_form">
                    <div class="row collapse">
                        <div class="small-10 medium-10 large-10 columns">
                            <input type="text" onblur="if (this.placeholder == '') {this.placeholder = 'Jméno';}" onfocus="this.placeholder = '';" placeholder="Jméno" name="first_name" required/>
                        </div>
                        <div class="small-2 medium-2 large-2 columns">
                            <span class="postfix custom">
                                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                                               width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                                    <path d="M81.195,31.517c1.128,0,2.042-0.897,2.042-1.996V23.61c0-1.102-0.914-2-2.042-2h-4.586v-1.603
                                                    c0-1.771-1.436-3.207-3.206-3.207H19.97c-1.771,0-3.206,1.435-3.206,3.207v59.986c0,1.766,1.436,3.206,3.206,3.206h53.432
                                                    c1.77,0,3.206-1.44,3.206-3.206v-1.67h4.586c1.128,0,2.042-0.894,2.042-1.996v-5.912c0-1.102-0.914-2-2.042-2h-4.586v-5.699h4.586
                                                    c1.128,0,2.042-0.894,2.042-1.991v-5.912c0-1.102-0.914-2-2.042-2h-4.586v-5.699h4.586c1.128,0,2.042-0.897,2.042-1.996v-5.912
                                                    c0-1.098-0.914-1.996-2.042-1.996h-4.586v-5.695H81.195z M62.391,63.681c0,1.152-0.804,2.088-1.795,2.088H32.75
                                                    c-0.992,0-1.795-0.935-1.795-2.088v-8.604c0-0.856,0.447-1.625,1.127-1.941l10.9-5.077c-2.583-1.557-4.351-4.689-4.351-8.304
                                                    c0-5.168,3.599-9.356,8.041-9.356c4.443,0,8.042,4.188,8.042,9.356c0,3.562-1.708,6.655-4.226,8.238l10.789,5.148
                                                    c0.674,0.325,1.115,1.085,1.115,1.937V63.681z"/>
                                                </svg>
                                                </span>
                        </div>
                        <div class="small-10 medium-10 large-10 columns">
                            <input type="text" onblur="if (this.placeholder == '') {this.placeholder = 'Příjmení';}" onfocus="this.placeholder = '';" placeholder="Příjmení" name="surname" required/>
                        </div>
                        <div class="small-2 medium-2 large-2 columns">
                            <span class="postfix custom">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                        width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                <path d="M81.195,31.517c1.128,0,2.042-0.897,2.042-1.996V23.61c0-1.102-0.914-2-2.042-2h-4.586v-1.603
                                 c0-1.771-1.436-3.207-3.206-3.207H19.97c-1.771,0-3.206,1.435-3.206,3.207v59.986c0,1.766,1.436,3.206,3.206,3.206h53.432
                                 c1.77,0,3.206-1.44,3.206-3.206v-1.67h4.586c1.128,0,2.042-0.894,2.042-1.996v-5.912c0-1.102-0.914-2-2.042-2h-4.586v-5.699h4.586
                                 c1.128,0,2.042-0.894,2.042-1.991v-5.912c0-1.102-0.914-2-2.042-2h-4.586v-5.699h4.586c1.128,0,2.042-0.897,2.042-1.996v-5.912
                                 c0-1.098-0.914-1.996-2.042-1.996h-4.586v-5.695H81.195z M62.391,63.681c0,1.152-0.804,2.088-1.795,2.088H32.75
                                 c-0.992,0-1.795-0.935-1.795-2.088v-8.604c0-0.856,0.447-1.625,1.127-1.941l10.9-5.077c-2.583-1.557-4.351-4.689-4.351-8.304
                                 c0-5.168,3.599-9.356,8.041-9.356c4.443,0,8.042,4.188,8.042,9.356c0,3.562-1.708,6.655-4.226,8.238l10.789,5.148
                                 c0.674,0.325,1.115,1.085,1.115,1.937V63.681z"/>
                            </svg>
                        </span>
                        </div>
                        <div class="small-10 medium-10 large-10 columns">
                            <input type="email" onblur="if (this.placeholder == '') {this.placeholder = 'E-mailová adresa';}" onfocus="this.placeholder = '';" placeholder="E-mailová adresa" name="email" required/>
                        </div>
                        <div class="small-2 medium-2 large-2 columns">
                            <span class="postfix custom">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                        width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                <g>
                                    <path d="M87.5,50.002C87.5,29.293,70.712,12.5,50,12.5c-20.712,0-37.5,16.793-37.5,37.502C12.5,70.712,29.288,87.5,50,87.5
                                    c6.668,0,12.918-1.756,18.342-4.809c0.61-0.22,1.049-0.799,1.049-1.486c0-0.622-0.361-1.153-0.882-1.413l0.003-0.004l-6.529-4.002
                                    L61.98,75.79c-0.274-0.227-0.621-0.369-1.005-0.369c-0.238,0-0.461,0.056-0.663,0.149l-0.014-0.012
                                    C57.115,76.847,53.64,77.561,50,77.561c-15.199,0-27.56-12.362-27.56-27.559c0-15.195,12.362-27.562,27.56-27.562
                                    c14.322,0,26.121,10.984,27.434,24.967C77.428,57.419,73.059,63,69.631,63c-1.847,0-3.254-1.23-3.254-3.957
                                    c0-0.527,0.176-1.672,0.264-2.111l4.163-19.918l-0.018,0c0.012-0.071,0.042-0.136,0.042-0.21c0-0.734-0.596-1.33-1.33-1.33h-7.23
                                    c-0.657,0-1.178,0.485-1.286,1.112l-0.025-0.001l-0.737,3.549c-1.847-3.342-5.629-5.893-10.994-5.893
                                    c-10.202,0-19.877,9.764-19.877,21.549c0,8.531,5.101,14.775,13.632,14.775c4.75,0,9.587-2.727,12.665-7.035l0.088,0.527
                                    c0.615,3.342,9.843,7.576,15.121,7.576c7.651,0,16.617-5.156,16.617-19.932l-0.022-0.009C87.477,51.13,87.5,50.569,87.5,50.002z
                                    M56.615,56.844c-1.935,2.727-5.101,5.805-9.763,5.805c-4.486,0-7.212-3.166-7.212-7.738c0-6.422,5.013-12.754,12.049-12.754
                                    c3.958,0,6.245,2.551,7.124,4.486L56.615,56.844z"/>
                                </g>
                        </span>
                        </div>
                    </div>
                    <div class="row large-up-3 medium-up-3 small-up-3 text-center">
                        <div class="column">
                            <p>Správca</p>
                            <div class="switch">
                                <input class="switch-input" id="exampleSwitch" type="radio" name="permissions" value="admin" required>
                                <label class="switch-paddle" for="exampleSwitch">
                                    <span class="show-for-sr">Správca</span>
                                </label>
                            </div>
                        </div>
                        <div class="column">
                            <p>Supervízor</p>
                            <div class="switch">
                                <input class="switch-input" id="exampleSwitch2" type="radio" name="permissions" value="supervizor" required>
                                <label class="switch-paddle" for="exampleSwitch2">
                                    <span class="show-for-sr">Supervízor</span>
                                </label>
                            </div>
                        </div>
                        <div class="columns">
                            <p>Brigádnik</p>
                            <div class="switch">
                                <input class="switch-input" id="exampleSwitch3" type="radio" name="permissions" value="brigadnik" required>
                                <label class="switch-paddle" for="exampleSwitch3">
                                    <span class="show-for-sr">Brigádnik</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="alert-free-space">
                        <div class="alert-success">
                            <div class="row">
                                <div class="large-12 columns large-centered text-center">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                        <g>
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                                <path d="M88.04,30.319L75.124,17.401c-0.454-0.453-1.067-0.709-1.71-0.709c-0.642,0-1.256,0.256-1.709,0.709L37.392,51.714
	               l-9.094-9.093c-0.945-0.944-2.474-0.944-3.419,0L11.96,55.539c-0.453,0.453-0.709,1.068-0.709,1.709c0,0.641,0.256,1.256,0.709,1.71
	               L35.607,82.6c0.453,0.453,1.067,0.708,1.709,0.708c0.029,0,0.055-0.016,0.083-0.016c0.024,0,0.05,0.014,0.075,0.014
	               c0.621,0,1.236-0.236,1.709-0.708L88.04,33.738C88.985,32.794,88.985,31.264,88.04,30.319z" />
                                            </svg>
                                    </svg>
                                    <div class="alert-message">
                                        Registrace byla úspěšná.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert">
                            <div class="row">
                                <div class="large-12 columns large-centered text-center">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                        <g>
                                            <path d="M91.17,81.374l0.006-0.004l-0.139-0.24c-0.068-0.128-0.134-0.257-0.216-0.375l-37.69-65.283
		   c-0.611-1.109-1.776-1.87-3.133-1.87c-1.47,0-2.731,0.887-3.285,2.153l-0.004-0.002L9.312,80.529l0.036,0.021
		   c-0.505,0.618-0.82,1.397-0.82,2.257c0,1.982,1.607,3.59,3.588,3.59h0h75.767v0c1.982,0,3.589-1.607,3.589-3.589
		   C91.472,82.297,91.362,81.814,91.17,81.374z M50.035,79.617c-2.874,0-5.201-2.257-5.201-5.13c0-2.874,2.326-5.2,5.201-5.2
		   c2.803,0,5.13,2.325,5.13,5.2C55.166,77.36,52.838,79.617,50.035,79.617z M55.165,34.25v28.299h-0.002
		   c0,0.005,0.002,0.01,0.002,0.016c0,1.173-0.95,2.094-2.094,2.094c-0.005,0-0.009-0.001-0.014-0.001v0.001h-6.093
		   c-1.174,0-2.123-0.921-2.123-2.094c0-0.005,0.002-0.01,0.002-0.016h-0.002V34.326c-0.001-0.026-0.008-0.051-0.008-0.077
		   c0-1.117,0.865-1.996,1.935-2.078v-0.016h6.288v0.001c1.149,0.007,2.074,0.897,2.103,2.039h0.005v0.053V34.25
		   C55.166,34.25,55.165,34.25,55.165,34.25z" />
                                        </g>
                                    </svg>
                                    <div class="alert-message">
                                        Registrace nebyla úspěšná!
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="space-top">
                        <div class="row">
                            <div class="large-6 large-push-6  medium-12 small-12 columns">
                                <button type="submit" name="btn-signup" class="success button register custom">Registrovat</button>
                            </div>
                            <div class="large-6 large-pull-6  medium-12 small-12 columns">
                                <button class="button custom" data-close onClick="clearForm()">Zavriet</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <footer>
            <div class="row">
                <div class="large-12 columns">
                    <p>@ 2016 <b>Muth v.o.s</b>
                        <span class="separator">|</span> Všetky práva vyhradené
                        <span class="separator">|</span> Tel: +421 948 634 433
                        <span class="separator">|</span> Email: info@muth.sk
                    </p>
                </div>
            </div>
        </footer>
        <script src="js/jquery.js"></script>
        <script src="js/foundation.js"></script>
        <script src="js/app.js"></script>
        <script src="js/clearForm.js"></script>
        <script>
            $(document).ready(function () {

                $('.alert-success').hide();
                $('.alert').hide();
                $('#registration_form').submit(function (event) {

                    var formData = {
                        'first_name': $('input[name=first_name]').val(),
                        'surname': $('input[name=surname]').val(),
                        'email': $('input[name=email]').val(),
                        'permissions': $('input[name=permissions]:checked').val()
                    }

                    //console.log('test',formData);

                    // process the form
                    $isRegistered = $.ajax({
                        type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                        url: 'registration.php', // the url where we want to POST
                        data: formData, // our data object
                        async: false,
                        done: function (response) {
                            return response;
                        }
                    }).responseText;

                    console.log($isRegistered);

                    if ($isRegistered == true) {
                        $('.alert-success').hide().slideDown(500);
                        $('#registration_form').trigger('reset');
                    } else {

                    }
                    // stop the form from submitting the normal way and refreshing the page
                    event.preventDefault();

                })



            });
        </script>

        <script src='js/calendar/moment.min.js'></script>
        <script src='js/jquery.min.js'></script>
        <script src='js/calendar/jquery-ui.custom.min.js'></script>
        <script src='js/calendar/fullcalendar.min.js'></script>
        <script src='../js/calendar/lang/cs.js'></script>
        <script src="js/attendanceCalendar.js"></script>
        <script src="js/stickyFooter.js"></script>
    </body>

    </html>
