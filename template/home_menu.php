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
