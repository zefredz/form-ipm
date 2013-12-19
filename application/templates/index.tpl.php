<?php include TEMPLATE_PATH.'/header.tpl.php'; ?>
    
    <div id="container">
        <!-- the tabs -->
        <ul class="tabs">
            <li><a href="#calendar_tab" id="calendarTab">Agenda</a></li>
            <li><a href="#aLaDemande_tab" id="aLaDemandeTab">Sur demande</a></li>
            <li><a href="#catalog_tab" id="catalogTab">Catalogue</a></li>
            <?php if ( IpmFormations_Init::user() ): ?>
            <li><a href="#mesinscriptions_tab" id="mesInscriptionsTab">Mes inscriptions</a></li>
            <?php endif; ?>
        </ul>
        <!-- tab "panes" -->
        <div class="panes">
            <?php echo $this->panes->render(); ?>
            
            <?php include TEMPLATE_PATH.'/mesinscriptions.tpl.php'; ?>
        </div>
    
    </div><!-- container -->
    
<?php include TEMPLATE_PATH.'/footer.tpl.php'; ?>
