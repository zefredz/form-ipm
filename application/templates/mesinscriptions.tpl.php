        <?php if ( IpmFormations_Init::user() ): ?>
            <div id="mesInscriptionsPane">
                <p>
                    Vous trouverez ci-après la liste des activités de formation auxquelles vous êtes inscrit(e).<br />
                    Pour modifier vos inscriptions, merci de prendre contact avec <a href="http://www.uclouvain.be/nicole.marion">Nicole Marion</a>.
                </p>
                <?php if ( count( $this->inscriptions ) ): ?>
                <table id="mesInscriptionList">
                    <thead>
                        <tr>
                            <th>
                                Date
                            </th>
                            <th>
                                Formation
                            </th>
                            <th>
                                Lieu
                            </th>
                            <th>
                                Statut
                            </th>
                            <th>
                                Détails
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( $this->inscriptions as $inscription ): ?>
                    <tr>
                        <td class="tableLeftColumn">
                            <a href="<?php echo REGISTER_URL; ?>?session_id=<?php echo (int) $inscription->session_id; ?>">
                                <?php if ( empty( $inscription->date ) ): ?>
                                date non déterminée
                                <?php else: ?>
                                <span class="dateShort"><?php echo $inscription->date; ?></span><br />
                                <?php echo trim($inscription->horaire) ? iconv( 'ISO-8859-1', 'UTF-8', htmlspecialchars( $inscription->horaire ) ) : ''; ?>
                                <?php endif; ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo DETAILS_URL; ?>?formation_id=<?php echo (int) $inscription->formation_id; ?>">
                            <?php
                                echo iconv( 'ISO-8859-1', 'UTF-8', $inscription->titre );
                            ?>
                            </a>
                        </td>
                        <td>
                            <?php echo iconv( 'ISO-8859-1', 'UTF-8', $inscription->lieu ); ?>
                        </td>
                        <td>
                            <?php if( $inscription->confirmation == 'confirme' ): ?>
                            inscription confirmée
                            <?php elseif ($inscription->preinscription == 'vrai'): ?>
                            <span class="emphase">préinscription</span>
                            <?php elseif ($inscription->confirmation == 'en attente'): ?>
                                <?php if ( $inscription->type_de_formation == 4 ): ?>
                                <span class="emphase">sur demande</span>
                                <?php else: ?>
                                <span class="emphase">en attente</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo REGISTER_URL; ?>?session_id=<?php echo (int) $inscription->session_id; ?>">
                                Afficher les détails...
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                Vous n'êtes inscrit(e) à aucune formation.
                <?php endif; ?>
            </div>
        <?php endif; ?>
