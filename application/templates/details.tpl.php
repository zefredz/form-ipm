                <h2>
                    <?php echo IpmFormations_Catalog::getTypeIcon($this->formation->type_de_formation['value']); ?>
                    <?php echo $this->formation->titre; ?>
                </h2>
                <h3>Description</h3>
                <div class="descriptionLongue">
                <?php if ( $this->formation->description ): ?>
                    <?php echo $this->formation->description; ?>
                <?php else: ?>
                    &nbsp;-&nbsp;
                <?php endif; ?>
                </div>
                <!-- h3>Intervenants</h3>
                <div>
                    <?php echo !empty($this->formation->intervenants) ? $this->formation->intervenants : 'Non communiqué'; ?>
                </div -->
                <h3>Agenda</h3>
                <dl id="sessionList">
                <?php $displayed = false; ?>
                <?php foreach( $this->formation->Sessions->related_record as $session ): ?>
                    <?php if ( $session->visible['value'] != 'non' 
                        && strtotime($session->date) > time() - 24 * 3600): ?>
                    <?php $displayed = true; ?>
                    <dt class="partOfThemeFormation">
                        <?php if( $session->annulee['value'] == 'oui' ): ?>
                        <strong>Annulée !</strong> <del>
                        <?php endif; ?>
                        <span class="dateShort"><?php echo $session->date; ?></span><?php echo trim($session->horaire) ? ', '.$session->horaire : ''; ?> -
                        <?php echo !empty( $session->locaux ) ? $session->locaux : 'Non communiqué'; ?> -
                        <a class="sinscrireLink" href="<?php echo REGISTER_URL; ?>?session_id=<?php echo (int) $session->id; ?>">
                        <?php if ($session->inscription_ouverte['value'] == 'non'): ?>
                        voir les détails
                        <?php else: ?>
                        voir les détails/s'inscrire
                        <?php endif; ?>
                        </a>
                        <?php if( $session->annulee['value'] == 'oui' ): ?>
                        </del>
                        <?php endif; ?>
                    </dt>
                    <?php if ( trim($session->sous_titre) ): ?>
                    <dd class="emphase">
                        <?php echo $session->sous_titre; ?>
                    </dd>
                    <?php endif; ?>
                    <?php if ( trim($session->intervenants) ): ?>
                    <dd class="descriptionCourte">
                        <strong>Intervenants</strong>
                        <blockquote>
                        <?php echo strip_tags($session->intervenants, '<ol><ul><li><strong><a><em><br><img>'); ?>
                        </blockquote>
                    </dd>
                    <?php elseif ( trim($this->formation->intervenants) ): ?>
                    <dd class="descriptionCourte">
                        <strong>Intervenants</strong>
                        <blockquote>
                        <?php echo strip_tags($this->formation->intervenants, '<ol><ul><li><strong><a><em><br><img>'); ?>
                        </blockquote>
                    </dd>
                    <?php else: ?>
                    <!-- non communique -->
                    <?php endif; ?>
                    <?php if ( !empty($session->infos_suppl) ): ?>
                    <dd class="descriptionCourte">
                        <?php echo $session->infos_suppl; ?>
                    </dd>
                    <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php foreach( $this->formation->Sessions->related_record as $session ): ?>
                    <?php if ( empty($session->date ) ): ?>
                    <?php $displayed = true; ?>
                    <dt class="partOfThemeFormation dateNonCommuniquee">
                        <?php if( $session->annulee['value'] == 'oui' ): ?>
                        <strong>Annulée !</strong> <del>
                        <?php endif; ?>
                        <?php if ( $this->formation->type_de_formation['value'] == 4 ): ?>
                        Sur demande - 
                        <?php else: ?>
                        Date non communiquée -
                        <?php endif; ?>
                        <?php echo $session->locaux; ?> - 
                        <?php if( $session->annulee['value'] == 'oui' ): ?>
                        </del>
                        <?php endif; ?>
                        <?php if ( $this->formation->type_de_formation['value'] == 4 ): ?>
                        <a class="sinscrireLink" href="<?php echo REGISTER_URL; ?>?session_id=<?php echo (int) $session->id; ?>">
                        <?php if ($session->inscription_ouverte['value'] == 'non'): ?>
                        voir les détails
                        <?php else: ?>
                        demander son organisation
                        <?php endif; ?>
                        </a>
                        <?php else: ?>
                        <strong class="error">inscription non disponible</strong>
                        <?php endif; ?>
                    </dt>
                    <?php if ( trim($session->sous_titre) ): ?>
                    <dd class="emphase">
                        <?php echo $session->sous_titre; ?>
                    </dd>
                    <?php endif; ?>
                    <?php if ( !empty($session->infos_suppl) ): ?>
                    <dd class="descriptionCourte">
                        <?php echo $session->infos_suppl; ?>
                    </dd>
                    <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if ( ! $displayed ): ?>
                    <dt>Aucune date pour le moment</dt>
                    
                    <?php if ( $this->formation->type_de_formation['value'] == 4 ): ?>
                    
                    <dd>Manifestez votre intérêt pour l'organisation de cette formation en envoyant un mail à
                        <?php if(empty($this->formation->email_personne_de_contact)): ?>
                            
                            <?php echo FORMATION_CONTACT_NAME; ?> 
                            &lt;<a href="mailto:<?php echo FORMATION_CONTACT_MAIL; ?>?subject=[Formations IPM] <?php echo htmlspecialchars( $this->formation->titre ); ?>">
                                <?php echo FORMATION_CONTACT_MAIL; ?>
                            </a>&gt;.
                        
                        <?php else: ?>
                            
                            <?php if (!empty($this->formation->nom_personne_de_contact)): ?>
                                <?php echo htmlspecialchars($this->formation->nom_personne_de_contact); ?>
                            <?php endif; ?>
                            &lt;<a href="mailto:<?php 
                                echo htmlspecialchars($this->formation->email_personne_de_contact); ?>?subject=[Formations IPM] <?php echo htmlspecialchars( $this->formation->titre ); ?>&cc=<?php echo FORMATION_CONTACT_MAIL; ?>">
                                <?php echo htmlspecialchars($this->formation->email_personne_de_contact); ?>
                            </a>&gt;.
                        
                        <?php endif; ?>
                    
                    </dd>
                    
                    <?php endif; ?>
                <?php endif; ?>
                </dl>
                <h3>Publics cibles</h3>
                <div>
                    <?php echo $this->formation->publics; ?>
                </div>
                <?php if ( !empty($this->formation->objectifs) ): ?>
                <h3>Objectifs</h3>
                <div>
                    <?php echo $this->formation->objectifs; ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($this->formation->motscles)): ?>
                <h3>Mots clés</h3>
                <div class="motscles">
                    <?php echo $this->formation->motscles; ?>
                </div>
                <?php endif; ?>
                <h3>Formats</h3>
                <div>
                    <?php echo $this->formation->formats; ?>
                </div>
                <?php if (!empty($this->formation->autres_infos)): ?>
                <h3>Autres informations</h3>
                <div>
                    <?php echo $this->formation->autres_infos; ?>
                </div>
                <?php endif; ?>
                
                <?php if ( count($this->formation->Formations_filles->related_record) > 0 ) :?>
                <h3>Autres activités proposées</h3>
                <ul>
                <?php 
                    $displayed = false;
                    $treated = array();
                    $treated[] = $this->formation->id;
                ?>
                <?php foreach ( $this->formation->Formations_filles->related_record as $fille ) :?>
                
                    <?php if ( IpmFormations_Catalog::isFormationActiveThisYear($fille->fille['value']) ): ?>
                    
                        <?php 
                            $displayed = true; 
                            $treated[] = (int) $fille->fille['value']; 
                        ?>
                        
                        <li><a href="<?php echo DETAILS_URL; ?>?formation_id=<?php echo (int) $fille->fille['value']; ?>"><?php echo $fille->fille;?></a></li>
                    
                    <?php endif; ?>
                    
                <?php endforeach; ?>
                <?php foreach ( $this->formation->Formations_meres->related_record as $mere ) :?>
                    
                    <?php if ( !in_array( (int) $mere->mere['value'], $treated ) ): ?>
                        
                        <?php if ( IpmFormations_Catalog::isFormationActiveThisYear($mere->mere['value']) ): ?>
                        
                            <?php 
                                $displayed = true;
                                $treated[] = (int) $mere->mere['value']; 
                            ?>
                            
                            <li><a href="<?php echo DETAILS_URL; ?>?formation_id=<?php echo (int) $mere->mere['value']; ?>"><?php echo $mere->mere;?></a></li>

                        <?php endif; ?>
                    
                    <?php endif; ?>
                    
                <?php endforeach; ?>
                <?php if ( !$displayed ): ?>
                    <li>Aucune autre activité proposée pour ce thème</li>
                <?php endif; ?>
                </ul>
                <?php endif; ?>
