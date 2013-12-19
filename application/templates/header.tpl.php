<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="fr">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8">

    <title>Les Formations de l'IPM</title>

    <link type="text/css" href="./assets/css/main.css" rel="stylesheet" media="screen" />
    <link type="text/css" href="./assets/css/devices.css" rel="stylesheet" media="screen" />
    <link type="text/css" href="./assets/css/tabs.css" rel="stylesheet" media="screen" />
    <link rel="shortcut icon" href="./favicon.ico" />
    <link rel="alternate" type="application/rss+xml" title="Agenda des formations de l'IPM" href="<?php echo dirname(INDEX_URL);?>/rss.php" />
    <link rel="profile" href="http://microformats.org/profile/hcalendar" />
    
    <script type="text/javascript" src="assets/js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.tools.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="assets/js/datetime.min.js"></script>
    <script type="text/javascript" src="assets/js/Date.min.js"></script>
    <script type="text/javascript" src="assets/js/Date.fr.min.js"></script>
    <script type="text/javascript" src="assets/js/ucfirst.min.js"></script>
    <script type="text/javascript" src="assets/js/main.min.js"></script>
</head>
<body>
    <div id="banner">
        
        <img class="imageLink" src="assets/img/bandeau_ipm.jpg" alt="UCL - IPM" />
        
        <div id="linkToPortailIPM">
            <a href="http://www.uclouvain.be/ipm" rel="external"><img src="assets/img/espace.gif" width="200" height="100" border="0" alt="" /></a>
        </div>
        <div id="linkToPortailUCL">
          <a href="http://www.uclouvain.be" rel="external"><img src="assets/img/espace.gif" width="295" height="70" border="0" alt="" /></a>
      
        </div>
        <div id="linkToPortail">
            <p>
                <a rel="external" href="http://www.uclouvain.be/ipm">Institut de pédagogie universitaire et des multimédias (IPM)</a>
            </p>
        </div>
    </div>
    
    <div id="connection">
        |
        <?php if ( ! IpmFormations_Init::user() ) :?>
            <?php if ( property_exists( $this,'formationId' ) && !empty($this->formationId) ) : ?>
            <a href="<?php echo LOGIN_URL.($this->formationId?'?formation_id='.$this->formationId:''); ?>">s'identifier</a>
            <?php elseif ( property_exists( $this,'sessionId' ) && !empty($this->sessionId) ): ?>
            <a href="<?php echo LOGIN_URL.($this->sessionId?'?session_id='.$this->sessionId:''); ?>">s'identifier</a>
            <?php else: ?>
            <a href="<?php echo LOGIN_URL; ?>">s'identifier</a>
            <?php endif; ?>
            |
        <?php else: ?>
            <a href="<?php echo LOGIN_URL; ?>?action=logout">déconnexion</a>
            |
        <?php endif; ?>
        <a href="<?php echo INDEX_URL; ?>">accueil</a>
        |
    </div>
    
    <div id="mainTitle">
        <h1>Programme des formations de l'IPM</h1>
        <?php echo IpmFormations_Website_Texts::getText('site_intro'); ?>
    </div>
