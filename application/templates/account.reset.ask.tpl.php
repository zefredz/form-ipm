<?php include TEMPLATE_PATH.'/header.tpl.php'; ?>
    
    <div id="container">
        
        <!-- the tabs -->
        <ul class="tabs">
            <li><a href="#" id="idTab">Mot de passe perdu</a></li>
        </ul>
    
        <!-- tab "panes" -->
        <div class="panes">
            <div id="idPane">
                <form method="post" action="<?php echo ACCOUNT_URL; ?>?action=askreset">
                    <fieldset>
                        <legend>Introduisez votre adresse email <strong>ou</strong> votre identifiant.</legend>
                        <label for="resetAccount_email">Email :</label><br /><input type="text" name="email" id="resetAccount_email" value="" /><br />
                        <strong>OU</strong><br />
                        <label for="resetAccount_username">Identifiant :</label><br /><input type="text" name="username" id="resetAccount_username" value="" />
                    </fieldset>
                    <br />
                    <input type="submit" name="submit" id="resetAccount_subbmit" value="Envoyer" />
                </form>
            </div>
        </div>
    
    </div><!-- container -->
    
<?php include TEMPLATE_PATH.'/footer.tpl.php'; ?>
