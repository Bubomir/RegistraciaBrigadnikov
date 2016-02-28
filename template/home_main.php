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
                                        <div class="morning-change">
                                            Ranní změny
                                            <?php
                                             include 'dbconnect.php';
                                             $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE Permissions='supervizor' ORDER BY Change_Number ASC");
                                             while ($row = mysqli_fetch_assoc($result)){
                                                 if($row['First_Name']!="Brigádnici"){
                                                     if($userRow['Permissions'] == 'admin'){
                                                      $meno = $row['Change_Number'].' '.$row['Surname'].' '.$row['First_Name'];
                                                     echo "<div id='supervizor-event-morning' class='fc-event' data-start='06:00:00' data-description='".md5($row['Email'])."' data-color = 'darkorange'>$meno</div>";
                                                     }
                                                 }
                                                 else{
                                                    echo "<div id='brig-button' class='fc-event' data-start='06:00:00' data-description='".md5($row['Email'])."' data-color = 'green'>".$row['First_Name']." R</div>";
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
                                        <div class="night-change">
                                            Noční změny
                                            <?php
                                                include 'dbconnect.php';
                                                $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE Permissions='supervizor' ORDER BY Change_Number ASC");
                                                while ($row = mysqli_fetch_assoc($result)){
                                                    if($row['First_Name']!="Brigádnici"){
                                                        if($userRow['Permissions'] == 'admin'){
                                                        $meno =  $row['Change_Number'].' '.$row['Surname'].' '.$row['First_Name'];
                                                        echo "<div id='supervizor-event-night' class='fc-event' data-start='18:00:00' data-description='".md5($row['Email'])."' data-color = 'black'>$meno</div>";
                                                        }
                                                    }
                                                    else{
                                                        echo "<div id='brig-button' class='fc-event' data-start='18:00:00' data-description='".md5($row['Email'])."' data-color = 'green'>".$row['First_Name']." N</div>";

                                                    }

                                                }

                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button id="notificationButton" data-toggle="modal-notifications" class="success button register custom" style="margin-top: 10px; margin-bottom: 25px;">Oznámení</button>
                            <p style="margin-bottom: 5px;font-size: 14px;"><strong>Filter pro oznámení:</strong></p>
                                <label>Login Status:
                                    <select id="activity" class="select-style">
                                        <option value="all">Vše</option>
                                        <option value="logIn">Přihlášení</option>
                                        <option value="logOut">Odhlášení</option>
                                    </select>
                                </label>
                                <label>Rok:
                                    <select name="year" id="year"  class="select-style">
                                        <option name="01" value="rwerwe">teest</option>
                                    </select>
                                </label>
                                <label>Měsíc:
                                    <select id="month"  class="select-style">
                                        <option name="01" value="01">Leden</option>
                                        <option name="02" value="02">Únor</option>
                                        <option name="03" value="03">Březen</option>
                                        <option name="04" value="04">Duben</option>
                                        <option name="05" value="05">Květen</option>
                                        <option name="06" value="06">Červen</option>
                                        <option name="07" value="07">Červenec</option>
                                        <option name="08" value="08">Srpen</option>
                                        <option name="09" value="09">Září</option>
                                        <option name="10" value="10">Říjen</option>
                                        <option name="11" value="11">Listopad</option>
                                        <option name="12" value="12">Prosinec</option>
                                    </select>
                                </label>

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
