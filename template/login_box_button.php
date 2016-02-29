</div>
<div class="space-top">
    <div class="row">
        <?php if($button_name != null){?>
            <div class="large-5 large-push-7 medium-12 small-12 columns">
                <button class="button custom" name="submit">
                    <?php print $button_name ?>
                </button>
            </div>
            <?php } ?>
                <?php if($button_name == 'Přihlásit') {?>
                    <div class="large-7 large-pull-5 medium-12 small-12 columns text-center">
                        <div class="align-vertical-hyperlink">
                            <a href="forgoted_pass.php">Zapomněli jste heslo?</a>
                        </div>
                    </div>
                    <?php }elseif($button_name == 'Poslat' || $button_name == 'Potvrdit'|| $isRegistered == true) { ?>
                        <div class="large-7 large-pull-5 medium-12 small-12 columns text-center">
                            <div class="align-vertical-hyperlink">
                                <a href="index.php">Zpět na přihlášení</a>
                            </div>
                        </div>
                        <?php } ?>
                            <?php if($button_name == null){?>
                                <div class="large-12 medium-12 small-12 columns text-center">
                                    <div class="align-vertical-hyperlink">
                                        <a href="index.php">Zpět na přihlášení</a>
                                    </div>
                                </div>
                                <?php }?>
    </div>
</div>
