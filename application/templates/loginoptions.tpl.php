<?php include TEMPLATE_PATH.'/header.tpl.php'; ?>
    
    <div id="container">
        
        <!-- the tabs -->
        <ul class="tabs">
            <li><a href="#" id="idTab">Identification</a></li>
        </ul>
    
        <!-- tab "panes" -->
        <div class="panes">
            <div id="idPane">
                <p>Êtes-vous membre de l'UCL, de l'UCL-Mons ou de LOCI (personnel ou étudiant) ?</p>
                <ul id="idOptions">
                    <li>
                        <a href="<?php echo LOGIN_URL; ?>?ucl=1<?php
                        echo ($this->formationId?'&amp;formation_id='.$this->formationId:'');
                        echo ($this->sessionId?'&amp;session_id='.$this->sessionId:'');?>">
                            Oui, je suis membre de l'UCL, de l'UCL-Mons ou de LOCI
                        </a>
                    </li>
                    <li><!-- a href="#" id="linkNonUCL" -->Non, je ne suis pas membre de l'UCL<!-- /a -->
                        <ul id="nonUCLOptions">
                            <li>
                                <a href="<?php echo LOGIN_URL; ?>?ucl=0<?php
                                echo ($this->formationId?'&amp;formation_id='.$this->formationId:'');
                                echo ($this->sessionId?'&amp;session_id='.$this->sessionId:'');?>">
                                    mais j'ai déjà un identifiant et un mot de passe pour ce site
                                </a>
                            </li>
                            <li><a href="<?php echo CREATE_URL; ?>">et je n'ai pas encore d'identifiant et de mot de passe pour ce site</a></li>
                            <li><a href="<?php echo ACCOUNT_URL; ?>">et j'ai oublié mon identifiant ou mon mot de passe pour ce site</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- script type="text/javascript">
            $(
                function () {
                    $('#nonUCLOptions').hide();
                    $('#linkNonUCL').click( function() {
                        $('#nonUCLOptions').toggle();
                        return false;
                    });
                }
            );
        </script -->
    
    </div><!-- container -->
    
<?php include TEMPLATE_PATH.'/footer.tpl.php'; ?>
