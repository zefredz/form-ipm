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
                <p>Vous pouvez maintenant <a href="<?php echo LOGIN_URL; ?>?ucl=0">vous identifier</a>.</p>
            </div>
        </div>
    
    </div><!-- container -->
    
<?php include TEMPLATE_PATH.'/footer.tpl.php'; ?>