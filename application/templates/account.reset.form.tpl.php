<?php include TEMPLATE_PATH.'/header.tpl.php'; ?>
    
    <div id="container">
        
        <!-- the tabs -->
        <ul class="tabs">
            <li><a href="#" id="idTab">Mot de passe perdu</a></li>
        </ul>
    
        <!-- tab "panes" -->
        <div class="panes">
            <div id="idPane">
                <p class="message"><?php echo $this->message; ?></p>
                <form method="post" id="resetAccount" action="<?php echo ACCOUNT_URL; ?>?action=doreset">
                    <input type="hidden" name="resetkey" value="<?php echo $this->resetkey; ?>" />
                    <label for="resetAccount_newpassword">Nouveau mot de passe :</label> <strong>*</strong><br />
                    <input type="password" name="newpassword" id="resetAccount_newpassword" value="" /><br />
                    <label for="resetAccount_confirmpassword">Entrez une seconde fois votre nouveau mot de passe :</label> <strong>*</strong><br />
                    <input type="password" name="confirmpassword" id="resetAccount_confirmpassword" value="" /><br />
                    <br />
                    <input type="submit" name="submit" id="resetAccount_submit" value="Envoyer" />
                </form>
                <p class="message">(<strong>*</strong> indique un champ obligatoire)</p>
            </div>
        </div>
    
    </div><!-- container -->
    
    <script type="text/javascript">
        $( function() {
            $.validator.addMethod(
                'passwordMustMatch',
                function() {
                    return ($('#resetAccount_newpassword').val() == $('#resetAccount_confirmpassword').val());
                },
                "Vous n'avez pas entré le même mot de passe deux fois"
            );
            
            $('#resetAccount').validate({
                rules: {
                    newpassword: {
                        required: true,
                        minlength: 8
                    },
                    confirmpassword: {
                        required: true,
                        passwordMustMatch: true
                    }
                },
                messages: {
                    newpassword: {
                        required: "Vous devez choisir un mot de passe",
                        minlength: "Votre mot de passe doit faire au moins 8 caractères"
                    },
                    confirmpassword: {
                        required: "Vous devez entrer une seconde fois votre nouveau mot de passe",
                        passwordMustMatch: "Vous n'avez pas entré le même mot de passe deux fois"
                    }
                }
            });
        });
    </script>
    
<?php include TEMPLATE_PATH.'/footer.tpl.php'; ?>