<?php echo IpmFormations_Website_Texts::getText('sur_demande_intro'); ?>
<?php if ( !empty( $this->aLaDemande ) ): ?>
<dl>
    <?php foreach ( $this->aLaDemande as $formation ): ?>

        <?php if ( $formation->visible != 'non' ): ?>
            <dt class="titreFormation">
                <a href="<?php echo DETAILS_URL; ?>?formation_id=<?php echo (int) $formation->id; ?>"><?php echo $formation->titre; ?></a>
            </dt>
            <?php if ( strip_tags( $formation->description,'<strong><a><em><br>') ): ?>
            <dd class="descriptionCourte">
                <?php echo IpmFormations_Catalog::getTypeIcon($formation->type_de_formation['value']); ?>
                <?php echo strip_tags( $formation->description,'<strong><a><em><br>') 
                    ? strip_tags( $formation->description,'<strong><a><em><br>') 
                    : 'Pas de description pour le moment...' ; ?>
            </dd>
            <?php endif; ?>
            <?php if ( !empty($formation->motscles) ): ?>
            <dd class="motsCles">
                <strong>Mots clés : </strong><?php echo $formation->motscles; ?>
            </dd>
            <?php endif; ?>
            <dd class="detailsLink">
                <a href="<?php echo DETAILS_URL; ?>?formation_id=<?php echo (int) $formation->id; ?>">Voir la fiche de la formation...</a>
            </dd>
        <?php endif; ?>
    <?php endforeach; ?>
</dl>
<?php else: ?>
<p>Aucune formation pour le moment</p>
<?php endif; ?>
