<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * RSS generation script
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

try
{
    require_once dirname(__FILE__) . '/init.php';

    $rss = new UniversalFeedCreator();
    $rss->title = "Les Formations de l'IPM";
    $rss->description = "Liste des formations programmées à l'IPM";
    $rss->link = INDEX_URL;

    $xmlCalendar = simplexml_load_file(XML_CACHE.'/calendar.xml');

    foreach ( $xmlCalendar as $session )
    {
        if ( !empty($session->date) 
            && $session->annulee['value'] != 'oui' 
            && $session->visible != 'non' 
            && IpmFormations_SessionDate::sessionTime( $session ) > time() - 12 * 3600 )
        {
            $item = new FeedItem();
            $item->title = $session->Formation->related_record->titre;
            $item->link = REGISTER_URL . "?session_id=".(int) $session->id;
            
            $item->date = Time_Utils::timeToIso8601( IpmFormations_SessionDate::sessionTime( $session ) );
            
            $descriptionTpl = new PhpTemplate(TEMPLATE_PATH.'/rssdescription.tpl.php');
            $descriptionTpl->assign( 'session', $session );
            
            $item->description = $descriptionTpl->render();
            
            $rss->addItem( $item );
        }
    }

    echo $rss->outputFeed("RSS2.0");

    $piwik = new PiwikApiClient(
        'http://sites.uclouvain.be/ipm/piwik',
        1,  
        '71a5a6182e8d6b26e5d5b1dbf4ead3c3'
    );

    $piwik->request( 'ActiveTrack.trackDownload', array(
        'linkUrl' => 'http://sites.uclouvain.be/ipm/formations/rss.php'
    ) );

}
catch ( Exception $e )
{
    if ( DEBUG_MODE )
    {
        var_dump($e);
    }
    
    $log = new Log_ErrorLog;
    $log->log( $e->__toString() );
}
