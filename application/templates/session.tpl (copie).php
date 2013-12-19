<?php include TEMPLATE_PATH.'/header.tpl.php'; ?>
    
    <div id="container">
        
        <!-- the tabs -->
        <ul class="tabs">
            <li><a href="#" id="sinscrireTab">Détails/Inscription</a></li>
        </ul>
    
        <!-- tab "panes" -->
        <div class="panes">
            <div id="sinscrirePane">
            <?php if ($this->registrationStatus == 'success' ): ?>
                <p>
                    <?php if ($this->session->Formation->related_record->type_de_formation['value'] == 4): ?>
                    Votre demande pour l'organisation de la formation
                    <?php else: ?>
                    Votre demande d'inscription à la formation
                    <?php endif; ?>
                    "<?php echo  $this->session->Formation->related_record->titre; ?>"
                    <?php if ($this->session->Formation->related_record->type_de_formation['value'] != 4): ?>
                    du <span class="dateShort"><?php echo $this->session->date; ?></span>
                    <?php endif; ?>
                    <?php echo !empty($this->session->horaire) ? '('.$this->session->horaire.')' : ''; ?> a bien été enregistrée.<br />
                    <?php if ($this->session->Formation->related_record->type_de_formation['value'] != 4): ?>
                    <span class="emphase">Vous allez recevoir un email de confirmation lorsque votre inscription sera effective.</span>.
                    <?php else: ?>
                    <span class="emphase">Vous recevrez un email de confirmation si cette formation est organisée.</span>.
                    <?php endif; ?>
                </p>
                <p>
                    | <a href="<?php echo INDEX_URL; ?>">retour au catalogue</a> |
                </p>
            <?php elseif ($this->registrationStatus == 'already' ): ?>
                <p>
                    Vous êtes <strong>déjà inscrit(e)</strong> à la formation
                    "<?php echo  $this->session->Formation->related_record->titre; ?>"
                    du <span class="dateShort"><?php echo $this->session->date; ?></span>
                    <?php echo $this->session->horaire ? '('.$this->session->horaire.')' : ''; ?> !
                </p>
                <p>
                    | <a href="<?php echo INDEX_URL; ?>">retour au catalogue</a> |
                </p>
            <?php elseif ($this->registrationStatus == 'failure' ): ?>
                <?php if ( $this->failureMessage ): ?>
                <p>
                    <?php echo $this->failureMessage; ?>
                </p>
                <?php else: ?>
                <p>
                    Impossible de vous inscrire à cette formation. Veuillez réessayer plus tard.<br />
                    Si l'erreur persistait, vous pouvez contacter le gestionnaire de l'application
                    &lt;<a href="mailto:<?php echo APPLICATION_MANAGER; ?>"><?php echo APPLICATION_MANAGER; ?></a>&gt;
                </p>
                <?php endif; ?>
            <?php elseif ($this->registrationStatus == 'requested' ):?>
                <?php if ( ( ( empty($this->session->date) && $this->session->Formation->related_record->type_de_formation['value'] == 4 ) 
                        || strtotime($this->session->date ) > time() - 24 * 3600 )
                        
                    && !$this->userAlreadyRegistered ): ?>
                    <?php if ($this->displayForm ): ?>
                        <?php if ( empty( $this->session->date ) && $this->session->Formation->related_record->type_de_formation['value'] == 4 ): ?>
                        <p>
                            Cette formation sur demande sera organisée si suffisamment de personnes le demandent. <br />
                            Cliquez sur le bouton ci-dessous si vous désirez y participer.
                        </p>
                        <?php endif; ?>
                        <form method="get" action="<?php echo REGISTER_URL;?>">
                            <input type="hidden" name="action" value="sinscrire" />
                            <input type="hidden" name="session_id" value="<?php echo (int) $this->session->id; ?>" />
                            <?php if ( empty( $this->session->date ) && $this->session->Formation->related_record->type_de_formation['value'] == 4 ): ?>
                            <input type="submit" value="Demander son organisation" />
                            <?php else: ?>
                            <input type="submit" value="S'inscrire à cette formation" />
                            <?php endif; ?>
                        </form>
                    <?php else: ?>
                        <p class="registrationMessage">
                            <strong style="color:red;"><?php echo $this->registrationMessage; ?></strong>
                        </p>
                    <?php endif; ?>
                <?php elseif ( $this->userAlreadyRegistered ): ?>
                <p>
                    <?php if ( is_null($this->userRegistration) 
                        || $this->userRegistration->confirmation == 'confirme' ): ?>
                    Vous êtes déjà inscrit(e) à cette activité de formation.
                    <?php elseif ( $this->userRegistration->confirmation == 'en attente' || $this->userRegistration->confirmation == 'en traitement' ): ?>
                        <?php if ( $this->session->Formation->related_record->type_de_formation['value'] == 4 ): ?>
                        Votre intérêt pour cette formation <span class="emphase">a bien été enregistré</span>, vous serez averti(e) par email si elle est organisée.
                        <?php else: ?>
                        Votre inscription est <span class="emphase">en attente</span> et doit encore être confirmée.
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
                <?php else: ?>
                <!-- pas de date future -->
                <?php endif; ?>
                <div class="vevent">
                <h2 class="summary">
                    <?php echo IpmFormations_Catalog::getTypeIcon($this->session->Formation->related_record->type_de_formation['value']); ?>
                    <?php echo $this->session->Formation->related_record->titre; ?>
                </h2>
                <?php if ( trim($this->session->sous_titre) ): ?>
                <h3>
                    <?php echo $this->session->sous_titre; ?>
                </h3>
                <?php endif; ?>
                <div class="descriptionLongue description">
                    <?php echo  $this->session->Formation->related_record->description; ?>
                </div>
                <dl>
                    <dt>
                        Date
                    </dt>
                    <dd>
                        <?php if ( empty( $this->session->date ) ): ?>
                            Aucune date communiquée pour le moment
                            <?php if ( $this->session->Formation->related_record->type_de_formation['value'] == 4 ): ?>
                            (formation <span class="emphase">sur demande</span>)
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="dtstart" style="display:none"><?php echo $this->session->date; ?></span>
                            <span class="dateShort"><?php echo $this->session->date; ?></span><br />
                        <?php endif; ?>
                    </dd>
                    <dt>
                        Horaire
                    </dt>
                    <dd>
                        <?php echo trim($this->session->horaire ) ? $this->session->horaire : 'A déterminer'; ?>
                    </dd>
                    <dt>
                        Lieu
                    </dt>
                    <dd class="location">
                        <?php echo !empty( $this->session->locaux ) ? $this->session->locaux : 'A déterminer'; ?>
                    </dd>
                    <?php if(!empty($this->local->adresse)): ?>
                    <dd>
                        
                        <?php echo $this->local->adresse; ?>
                    </dd>
                    <?php endif; ?>
                    <?php if(!empty($this->local->url_du_plan_d_acces)): ?>
                    <dd>
                        <a rel="external" href="<?php echo htmlspecialchars( $this->local->url_du_plan_d_acces ); ?>">
                            plan d'accès
                        </a>
                    </dd>
                    <?php endif;?>
                    <?php if( !empty( $this->session->infos_suppl ) ) : ?>
                    <dt>
                        Informations supplémentaires
                    </dt>
                    <dd>
                        <?php echo !empty( $this->session->infos_suppl ) ? strip_tags($this->session->infos_suppl,'<ul><ol><li><strong><a><em><br><img>') : '&nbsp;-&nbsp;'; ?>
                    </dd>
                    <?php endif; ?>
                    <dt>
                        Intervenant(s)
                    </dt>
                    <dd>
                        <?php if ( trim($this->session->intervenants) ): ?>
                            <?php echo strip_tags($this->session->intervenants, '<strong><a><em><br><img><ul><ol><li><br>'); ?>
                        <?php elseif ( trim($this->session->Formation->related_record->intervenants) ): ?>
                            <?php echo strip_tags($this->session->Formation->related_record->intervenants, '<ul><ol><li><strong><a><em><br><img>'); ?>
                        <?php else: ?>
                            Non communiqué
                        <?php endif; ?>
                    </dd>
                    <dt>
                        Nombre d'inscrits
                    </dt>
                    <dd>
                        <?php 
                            $nbrInscrits = IpmFormations_SessionRegistration::countRegistrations( 
                                $this->session->id, 
                                true, 
                                true,
                                true );
                        ?>
                        <?php if ( $nbrInscrits > 0 ): ?>
                            <?php echo $nbrInscrits; ?> inscrits
                        <?php else: ?>
                            Pas d'inscrits pour le moment
                        <?php endif; ?>
                        <?php if ( !empty( $this->session->nombre_places ) ): ?>
                            (<?php echo $this->session->nombre_places; ?> places disponibles)
                        <?php endif; ?>
                    </dd>
                </dl>
                </div>
                <p>
                    | <a href="<?php echo INDEX_URL;?>?formation_id=<?php echo (int) $this->session->Formation->related_record->id; ?>">
                    Voir la fiche complète de la formation...
                    </a>
                    | <a href="<?php echo INDEX_URL; ?>">retour au programme</a> |
                </p>
            <?php endif; ?>
            </div>
        </div>
    </div>
    
<?php include TEMPLATE_PATH.'/footer.tpl.php'; ?>
