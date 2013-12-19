<?php include TEMPLATE_PATH.'/header.tpl.php'; ?>
    
    <div id="container">
        
        <!-- the tabs -->
        <ul class="tabs">
            <li><a href="#" id="idTab">Identification</a></li>
        </ul>
    
        <!-- tab "panes" -->
        <div class="panes">
            <div id="idPane">
            <?php if ($this->userAuthenticated): ?>
                <h2>Vous êtes maintenant identifié !</h2>
                <p>
                    <a href="<?php echo INDEX_URL; ?>">Continuer</a>
                    |
                    <a href="<?php echo LOGIN_URL; ?>?action=logout">Quitter</a>
                </p>
            <?php else: ?>
                <?php if ( $this->sessionId ): ?>
                <p>Vous devez être identifié pour vous inscrire à une activité de formation.</p>
                <?php endif; ?>
                <?php if ( is_null($this->ucl) ) : ?>
                <p>
                    Les <span class="emphase">membres de l'UCL</span> doivent utiliser leur <a href="http://www.uclouvain.be/4040" rel="external">identifiant global UCL</a> pour s'identifier.<br />
                    Si vous n'êtes <span class="emphase">pas membre de l'UCL</span> vous devez au préalable vous <a href="<?php echo CREATE_URL; ?>">créer un compte</a>.
                </p>
                <?php elseif ( $this->ucl == 1): ?>
                <p>
                    Les <span class="emphase">membres de l'UCL</span> doivent utiliser leur <a href="http://www.uclouvain.be/4040" rel="external">identifiant global UCL</a> pour s'identifier.<br />
                </p>
                <?php else: ?>
                <p>
                    Si vous n'êtes <span class="emphase">pas membre de l'UCL</span> vous devez au préalable vous <a href="<?php echo CREATE_URL; ?>">créer un compte</a>.
                </p>
                <?php endif; ?>
                <form action="<?php echo LOGIN_URL; ?>" method="post">
                <input type="hidden" name="action" value="login" />
                <?php if (! is_null($this->formationId) ): ?>
                <input type="hidden" name="formation_id" value="<?php echo (int) $this->formationId; ?>" />
                <?php endif; ?>
                <?php if (! is_null($this->sessionId) ): ?>
                <input type="hidden" name="session_id" value="<?php echo (int) $this->sessionId; ?>" />
                <?php endif; ?>
                <label for="login_login">Identifiant</label> :<br />
                <input id="login_login" name="login" type="text" value="" /><br />
                <label for="login_passwd">Mot de passe</label> :<br />
                <input id="login_passwd" name="password" type="password" value="" /><br />
                <?php if ( is_null($this->ucl) ) : ?>
                    <label>Êtes-vous membre de l'UCL ?</label> :<br />
                    <input type="radio" id="institutionUcl" name="ucl" value="1" checked="checked" /><label for="institutionUcl">Oui</label>
                    <br />
                    <input type="radio" id="institutionNonUcl" name="ucl" value="0" /><label for="institutionNonUcl">Non</label>
                    <br />
                <?php else: ?>
                    <input type="hidden" id="ucl" name="ucl" value="<?php echo $this->ucl; ?>" />
                <?php endif; ?>
                <input id="login_submit" type="submit" value="s'identifier" />
                </form>
            <?php endif; ?>
            </div>
        </div>
    
    </div><!-- container -->
    
<?php include TEMPLATE_PATH.'/footer.tpl.php'; ?>