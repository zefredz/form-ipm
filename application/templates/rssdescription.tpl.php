<div class="vevent">
        <?php if ( trim($this->session->sous_titre) ): ?>
        <p>
            <em><?php echo $this->session->sous_titre; ?></em>
        </p>
        <?php endif; ?>
        <div class="descriptionLongue description summary">
            <?php echo  $this->session->Formation->related_record->description; ?>
        </div>
        <dl>
            <dt>
                Date
            </dt>
            <dd class="dtstart">
                <?php echo Date_French::date( 'l j F Y', strtotime( $this->session->date ) ); ?>
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
        </dl>
</div>

