<?php echo IpmFormations_Website_Texts::getText('catalog_intro'); ?>
<?php if ( empty($this->xml->formations) ) :?>

    <p>Rien à afficher</p>
    
<?php else: ?>

    <h2 style="color:#BE1D2F;">Activités-phares<a name="formationsPhares"></a></h2>
    
    <?php if ( !empty($this->formationsPhares) ): ?>
    
    <dl id="formationsPharesList">
    <!-- ?php foreach ( $this->xml->formations as $formation ) :? -->
    <?php foreach ( $this->formationsPhares as $formation ) :?>
        <?php include TEMPLATE_PATH.'/catalog_fragment.tpl.php'; ?>
    <?php endforeach; ?>
    </dl>
    
     <?php else: ?>
     
    <p>Aucune formation de ce type pour l'instant</p>
    
    <?php endif; ?>
    
    <h2 style="color:#002E61;">Pour en savoir plus<a name="savoirPlus"></a></h2>
    
    <?php if ( !empty($this->savoirPlus) ): ?>
    
    <dl id="savoirPlusList">
    <!-- ?php foreach ( $this->xml->formations as $formation ) :? -->
    <?php foreach ( $this->savoirPlus as $formation ) :?>
        <?php include TEMPLATE_PATH.'/catalog_fragment.tpl.php'; ?>
    <?php endforeach; ?>
    </dl>
    
     <?php else: ?>
     
    <p>Aucune formation de ce type pour l'instant</p>
    
    <?php endif; ?>
    
    <h2 style="color:#009036;">Pour aller plus loin<a name="allerPlusLoin"></a></h2>
    
    <?php if ( !empty($this->allerPlusLoin) ): ?>
    
    <dl id="allerPlusLoinList">
    <!-- ?php foreach ( $this->xml->formations as $formation ) :? -->
    <?php foreach ( $this->allerPlusLoin as $formation ) :?>
        <?php include TEMPLATE_PATH.'/catalog_fragment.tpl.php'; ?>
    <?php endforeach; ?>
    </dl>
    
     <?php else: ?>
     
    <p>Aucune formation de ce type pour l'instant</p>
    
    <?php endif; ?>
    
    <h2 style="color: #F29400;">Pour public ciblé<a name="surDemande"></a></h2>
    
    <?php if ( !empty($this->specialAssistants) ): ?>
    
    <dl id="surDemandeList">
    <?php foreach ( $this->specialAssistants as $formation ) :?>
            <?php include TEMPLATE_PATH.'/catalog_fragment.tpl.php'; ?>
    <?php endforeach; ?>
    </dl>
    
     <?php else: ?>
     
    <p>Aucune formation de ce type pour l'instant</p>
    
    <?php endif; ?>
    
    <h2 style="color:#BE1D2F;">En Académie<a name="enAcademie"></a></h2>
    
    <?php if ( !empty($this->academieLouvain) ): ?>
    
    <dl id="savoirPlusList">
    <!-- ?php foreach ( $this->xml->formations as $formation ) :? -->
    <?php foreach ( $this->academieLouvain as $formation ) :?>
        <?php include TEMPLATE_PATH.'/catalog_fragment.tpl.php'; ?>
    <?php endforeach; ?>
    </dl>
    
    <?php else: ?>
    
    <p>Aucune formation de ce type pour l'instant</p>
    
    <?php endif; ?>
    
    <h2 style="color: #009ee1;">Sur demande<a name="aLaCarte"></a></h2>
    
    <?php if ( !empty($this->surDemande) ): ?>
    
    <dl id="aLaCarteList">
    <?php foreach ( $this->surDemande as $formation ) :?>
        <?php include TEMPLATE_PATH.'/catalog_fragment.tpl.php'; ?>
    <?php endforeach; ?>
    </dl>
    
     <?php else: ?>
     
    <p>Aucune formation de ce type pour l'instant</p>
    
    <?php endif; ?>
    
<?php endif; ?>
