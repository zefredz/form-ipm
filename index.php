<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Main index
 *
 * @version     1.5
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

try
{
    require dirname(__FILE__).'/init.php';
    
    // retrieve formation id for request if any given
    $formationId = IpmFormations_Init::input()->get( 'formation_id', null );
    
    // formations catalog template
    $catalogTpl = new PhpCachedTemplate(
        TEMPLATE_PATH.'/catalog.tpl.php',
        HTML_CACHE.'/catalog.tpl.html',
        null // no automatic generation
    );
    
    // on demand formations template
    $aLaDemandeTpl = new PhpCachedTemplate(
        TEMPLATE_PATH.'/a_la_demande.pane.tpl.php',
        HTML_CACHE.'/a_la_demande.pane.tpl.html',
        null // no automatic generation
    );
    
    // calendar template (regenerate every 24 hours)
    $calendarTpl = new PhpCachedTemplate(
        TEMPLATE_PATH.'/calendar.tpl.php',
        HTML_CACHE.'/calendar.tpl.html',
        file_exists( HTML_CACHE.'/calendar.tpl.html' ) 
            ? 24 * 3600 
            : null
    );
    
    // regenerate catalog tabs if needed
    if ( $catalogTpl->mustRegenerateCache() || $aLaDemandeTpl->mustRegenerateCache() )
    {
        // load the formations catalog from the XML file
        $xmlCatalog = simplexml_load_file(XML_CACHE.'/catalog.xml');
        
        // regenerate the detailed view for every formation
        IpmFormations_XatafaceCache::regenerateDetails( $xmlCatalog );
        
        // load formations by type
        $formationsPhares = $xmlCatalog->xpath('//formations/type_de_formation[@value="3"]/..');
        $savoirPlus = $xmlCatalog->xpath('//formations/type_de_formation[@value="1"]/..');
        $allerPlusLoin = $xmlCatalog->xpath('//formations/type_de_formation[@value="2"]/..');
        $specialAssistants = $xmlCatalog->xpath('//formations/type_de_formation[@value="5"]/..');
        $surDemande = $xmlCatalog->xpath('//formations/type_de_formation[@value="4"]/..');
        $academieLouvain = $xmlCatalog->xpath('//formations/type_de_formation[@value="14"]/..');
        
        // assign template variables for the catalog tab
        $catalogTpl->assign('xml', $xmlCatalog );
        $catalogTpl->assign('formationsPhares', $formationsPhares);
        $catalogTpl->assign('allerPlusLoin', $allerPlusLoin);
        $catalogTpl->assign('savoirPlus', $savoirPlus);
        $catalogTpl->assign('specialAssistants', $specialAssistants);
        $catalogTpl->assign('surDemande', $surDemande);
        $catalogTpl->assign('academieLouvain', $academieLouvain);
        
        // assign template variables for the "on demand" tab
        $aLaDemandeTpl->assign('aLaDemande', $surDemande);
    }
    
    // regenerate the calendar tab if needed
    if ( $calendarTpl->mustRegenerateCache() )
    {
        // load xml calendar
        $xmlCalendar = simplexml_load_file(XML_CACHE.'/calendar.xml');
        
        // assign template variables
        $calendarTpl->assign('xml', $xmlCalendar );
    }
    
    // display formation details if a formation id has been given
    
    if ( ! is_null($formationId) && !file_exists(HTML_CACHE.'/formation_'.$formationId.'.tpl.html') )
    {
        $formationQuery = IpmFormations_XatafaceQueries::getFormationXmlQuery($formationId);
        $formationXml = $formationQuery->getXmlResponse();
        $formations = simplexml_load_string( $formationXml );
        
        if ( !empty( $formations ) )
        {
            IpmFormations_XatafaceCache::generateDetails( $formations->formations );
        }
        else
        {
            throw new Exception( "Formation not found {$formationId}" );
        }
    }
    
    $indexTpl = new IpmFormations_Page();
    
    if ( file_exists( XML_CACHE.'/catalog.xml' ) )
    {
        $lastUpdate = filectime(XML_CACHE.'/catalog.xml');
    }
    else
    {
        $lastUpdate = time();
    }
    
    $indexTpl->assign( 'lastUpdate', $lastUpdate );
    
    if ( ! is_null( $formationId ) )
    {
        $detailsTpl = new PhpTemplate(
            TEMPLATE_PATH.'/formation.tpl.php'
        );
        
        $detailsTpl->assign( 'formationId', $formationId );
        
        $indexTpl->assign( 'formationId', $formationId );
        $indexTpl->addTab( 'details', 'Formation', $detailsTpl );
    }
    else
    {
        $indexTpl->addTab( 'calendar', 'Agenda', $calendarTpl );
        $indexTpl->addTab( 'aLaDemande', 'Sur demande', $aLaDemandeTpl );
        $indexTpl->addTab( 'catalog', 'Catalogue', $catalogTpl );
        
        // load user data if user authenticated
        if ( IpmFormations_Init::user() )
        {
            $user = IpmFormations_Init::user();
            
            $inscriptionsTpl = new PhpTemplate(
                TEMPLATE_PATH.'/mesinscriptions.tpl.php'
            );
            
            $inscriptionsTpl->assign( 'inscriptions', IpmFormations_SessionRegistration::getUserRegistrations( (int) $user['id'] ) );
            
            $indexTpl->addTab( 'mesInscriptions', 'Mes inscriptions', $inscriptionsTpl );
        }
    }
    
    header( 'Content-type: text/html; charset=utf-8' );
    
    echo $indexTpl->render();
}
catch (Exception $e)
{
    $errorTpl = new PhpTemplate(TEMPLATE_PATH.'/error.tpl.php');
    $errorTpl->assign('errorMessage', $e->getMessage());
    $errorTpl->assign('errorDump', $e->__toString());
    
    echo $errorTpl->render();
    
    $log = new Log_ErrorLog;
    $log->log( $e->__toString() );
}
