<?php include TEMPLATE_PATH.'/header.tpl.php'; ?>

    <div id="container">
        <!-- the tabs -->
        <ul class="tabs">
            <li><a href="#" id="creerTab">Créer un compte</a></li>
        </ul>
    
        <!-- tab "panes" -->
        <div class="panes">
            <div id="creerPane">
                <?php if($this->serverMessage): ?>
                <p>
                    <?php echo $this->serverMessage; ?>
                </p>
                <?php endif;?>
                <?php if ( $this->displaySuccess ): ?>
                <p class="success">
                    Votre compte a été créé avec succès. Vous pouvez maintenant vous identifier pour vous inscrire à des formations.<br />
                    <a href="<?php echo LOGIN_URL; ?>?ucl=0">s'identifier</a>
                    |
                    <a href="<?php echo INDEX_URL; ?>">retour au programme</a>
                </p>
                <?php endif; ?>
                <?php if ( $this->displayForm) : ?>
                <p>
                    Pour vous créer un compte, merci de compléter les données suivantes.<br />(<strong>*</strong> indique un champ obligatoire)
                </p>
                <form id="creationCompte" action="<?php echo CREATE_URL;?>" method="post">
                <input type="hidden" name="action" value="creer" />
                <label for="utilisateur">Identifiant</label> <strong>*</strong> :<br />
                <input type="text" name="utilisateur" id="utilisateur"
                    value="<?php echo isset($this->postedData['utilisateur']) ? $this->postedData['utilisateur'] : '';?>" />
                <small>ne <strong>pas</strong> mettre de caractères accentués, cédille... dans l'identifiant</small><br />
                <label for="mail_contact">Adresse email de contact</label> <strong>*</strong> :<br />
                <input type="text" name="mail_contact" id="mail_contact"
                    value="<?php echo isset($this->postedData['mail_contact']) ? $this->postedData['mail_contact'] : '';?>" /><br />
                <label for="motdepasse">Mot de passe</label> <strong>*</strong> :<br />
                <input type="password" name="motdepasse" id="motdepasse"
                    value="<?php echo isset($this->postedData['motdepasse']) ? $this->postedData['motdepasse'] : '';?>" /><br />
                <label for="motdepasse_confirme">Confirmez votre mot de passe</label> <strong>*</strong> :<br />
                <input type="password" name="motdepasse_confirme" id="motdepasse_confirme"
                    value="<?php echo isset($this->postedData['motdepasse_confirme']) ? $this->postedData['motdepasse_confirme'] : '';?>" /><br />
                <label for="nom">Nom</label> <strong>*</strong> :<br />
                <input type="text" name="nom" id="nom"
                    value="<?php echo isset($this->postedData['nom']) ? $this->postedData['nom'] : '';?>" /><br />
                <label for="prenom">Prénom</label> <strong>*</strong> :<br />
                <input type="text" name="prenom" id="prenom"
                    value="<?php echo isset($this->postedData['prenom']) ? $this->postedData['prenom'] : '';?>" /><br />
                <label for="institution">Institution/école</label> <strong>*</strong> :<br />
                <input type="text" name="institution" id="institution"
                    value="<?php echo isset($this->postedData['institution']) ? $this->postedData['institution'] : '';?>" /><br />
                <label for="type_institution">Type d'institution</label> <strong>*</strong> :<br />
                <select id="type_institution" name="type_institution">
                    <option value="">Choisissez une valeur...</option>
                    <?php foreach ( $this->typesInstitution as $key => $value ): ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select><br />
                <label for="faculte">Faculté ou section</label> <strong>*</strong> :<br />
                <input type="text" name="faculte" id="faculte"
                    value="<?php echo isset($this->postedData['faculte']) ? $this->postedData['faculte'] : '';?>" /><br />
                <label for="departement">Département</label> :<br />
                <input type="text" name="departement" id="departement"
                    value="<?php echo isset($this->postedData['departement']) ? $this->postedData['departement'] : '';?>" /><br />
                <label for="unite">Unité</label> :<br />
                <input type="text" name="unite" id="unite"
                    value="<?php echo isset($this->postedData['unite']) ? $this->postedData['unite'] : '';?>" /><br />
                <label for="adresse">Adresse</label> :<br />
                <textarea name="adresse" id="adresse" rows="5" cols="30"><?php echo isset($this->postedData['adresse']) ? $this->postedData['adresse'] : '';?></textarea><br />
                <label for="telephone">Téléphone</label> :<br />
                <input type="text" name="telephone" id="telephone"
                    value="<?php echo isset($this->postedData['telephone']) ? $this->postedData['telephone'] : '';?>" /><br />
                <label for="fax">Fax</label> :<br />
                <input type="text" name="fax" id="fax" value="" /><br />
                <label for="mail_travail">Adresse email professionnelle</label> : (si différente de l'adresse de contact)<br />
                <input type="text" name="mail_travail" id="mail_travail"
                    value="<?php echo isset($this->postedData['mail_travail']) ? $this->postedData['mail_travail'] : '';?>" /><br />
                <input type="checkbox" name="conf_humain" id="conf_humain" checked="checked" value="yes" />
                <label for="conf_humain">Pour des raisons de sécurité, nous vous demandons de <strong>décocher</strong> cette case afin de prouver
                que vous êtes bien un être humain et non un programme automatisé.</label><br />
                <br />
                <input type="submit" value="Enregistrer" />
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php include TEMPLATE_PATH.'/footer.tpl.php'; ?>
