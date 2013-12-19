    <div id="footer">
        | <a href="<?php echo dirname(INDEX_URL); ?>/vieprivee.php">vie privée</a>
        | Institut de Pédagogie Universitaire et des Multimédias - 54 Grand rue, 1348 Louvain-la-Neuve, Belgique<br />
        Tel. +32(0)10 47 22 77, Fax. +32(0)10 47 89 39 - Gestion des inscriptions aux formations : <a href="http://www.uclouvain.be/nicole.marion">Nicole Marion</a>
        |
    </div>
    
    <?php if ( property_exists( $this, 'lastUpdate' ) ): ?>
    <div id="lastUpdate">
        Dernière mise à jour : <?php echo gmdate('Y-m-d H:i:s', $this->lastUpdate ) . ' GMT' ?>
    </div>
    <?php endif; ?>
<!-- Piwik -->
<script type="text/javascript">
//<![CDATA[
var pkBaseURL = (("https:" == document.location.protocol) ? "https://sites.uclouvain.be/ipm/piwik/" : "http://sites.uclouvain.be/ipm/piwik/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
//]]>
</script><noscript><p><img src="http://sites.uclouvain.be/ipm/piwik/piwik.php?idsite=1" style="border:0" alt=""/></p></noscript>
<!-- End Piwik Tag -->
</body>
</html>
