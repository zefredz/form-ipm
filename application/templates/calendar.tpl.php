<?php echo IpmFormations_Website_Texts::getText('calendar_intro'); ?>
<?php if ( empty($this->xml->sessions) ) :?>
    <p>Rien à afficher</p>
<?php else: ?>
    <table id="calendarList" class="vcalendar">
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
                    Détails / Inscription
                </th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ( $this->xml->sessions as $session ): ?>
            <?php if (
                !empty($session->date)
                && $session->visible != 'non'
                && ( IpmFormations_SessionDate::sessionTime( $session ) > time() - 12 * 3600 ) ): ?>
            <tr class="vevent">
                <td class="tableLeftColumn">
                    <?php if ($session->annulee['value'] == 'oui' ): ?>
                    <del>
                    <?php endif; ?>
                    <a href="<?php echo REGISTER_URL; ?>?session_id=<?php echo (int) $session->id; ?>">
                    <span class="dtstart" style="display:none"><?php echo $session->date; ?></span>
                    <span class="dateShort"><?php echo $session->date; ?></span><br />
                    <?php echo trim($session->horaire) ? $session->horaire : ''; ?> 
                    </a>
                    <span style="display: none"><?php echo strtotime( $session->date ); ?></span>
                    <span style="display: none"><?php echo IpmFormations_SessionDate::sessionTime( $session ); ?></span>
                    <?php if ($session->annulee['value'] == 'oui' ): ?>
                    </del>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($session->annulee['value'] == 'oui' ): ?>
                    <del>
                    <?php endif; ?>
                    <a href="<?php echo DETAILS_URL; ?>?formation_id=<?php echo (int) $session->Formation->related_record->id; ?>" class="summary">
                        <?php echo $session->Formation->related_record->titre; ?>
                    </a>
                    <?php if ($session->annulee['value'] == 'oui' ): ?>
                    </del>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($session->annulee['value'] == 'oui' ): ?>
                    <del>
                    <?php endif; ?>
                    <span class="location"><?php echo !empty($session->locaux) ? $session->locaux : 'Non communiqué'; ?></span>
                    <?php if ($session->annulee['value'] == 'oui' ): ?>
                    </del>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo REGISTER_URL; ?>?session_id=<?php echo (int) $session->id; ?>">
                    <?php if ( $session->annulee['value'] == 'oui' ) : ?>
                    <span style="color: red;">annulée, voir les détails</span>
                    <?php elseif( $session->inscription_ouverte['value'] != 'oui'
                        || IpmFormations_SessionDate::sessionTime( $session ) - 12 * 3600 < time() ): ?>
                    voir les détails
                    <?php else: ?>
                    voir les détails/s'inscrire
                    <?php endif; ?>
                    </a>
                </td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php foreach ( $this->xml->sessions as $session ): ?>
            <?php if ( empty($session->date) ): ?>
            <tr class="dateNonCommuniquee">
                <td class="tableLeftColumn">
                    Non communiquée
                </td>
                <td>
                    <a href="<?php echo DETAILS_URL; ?>?formation_id=<?php echo (int) $session->Formation->related_record->id; ?>">
                        <?php echo $session->Formation->related_record->titre; ?>
                    </a>
                </td>
                <td>
                    -
                </td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
