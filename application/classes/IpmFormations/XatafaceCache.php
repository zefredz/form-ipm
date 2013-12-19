<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * XML helper for querying the other xataface tables and cache the result
 * WARNING: we need to set a -limit and -offset on the Xataface query URLs or 
 * only the 30 first results are retreived !!!! 
 *
 * @version     2.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class IpmFormations_XatafaceCache
{
    /**
     * Retrieve and cache XML for the "locaux" data
     * @param bool $force set to true to force the refresh
     * @return void
     */
    public static function refreshLocauxXml( $force = false )
    {
        Xataface_CacheManager::refreshXmlCacheFromXataface(
            IpmFormations_XatafaceQueries::getLocauxXmlQuery(),
            XML_CACHE.'/locaux.xml',
            $force );
    }
    
    /**
     * Retrieve and cache XML for the "calendar" data
     * @param bool $force set to true to force the refresh
     * @return void
     */
    public static function refreshCalendarXml( $force = false )
    {
        Xataface_CacheManager::refreshXmlCacheFromXataface(
            IpmFormations_XatafaceQueries::getCalendarXmlQuery(),
            XML_CACHE.'/calendar.xml',
            $force );
    }
    
    /**
     * Retrieve and cache XML for the "sessions" data
     * @param bool $force set to true to force the refresh
     * @return void
     */
    public static function refreshSessionsXml( $force = false )
    {
        Xataface_CacheManager::refreshXmlCacheFromXataface(
            IpmFormations_XatafaceQueries::getSessionsXmlQuery(),
            XML_CACHE.'/sessions.xml',
            $force );
    }
    
    /**
     * Retrieve and cache XML for the "catalog" data
     * @param bool $force set to true to force the refresh
     * @return void
     */
    public static function refreshCatalogXml( $force = false )
    {
        Xataface_CacheManager::refreshXmlCacheFromXataface(
            IpmFormations_XatafaceQueries::getCatalogXmlQuery(),
            XML_CACHE.'/catalog.xml',
            $force );
    }
    
    /**
     * Regenerate cache for the catalog details from XML data
     * @param SimpleXmlObject $xml
     * @param bool $force set to true to force the refresh of the xml data
     * @return void
     */
    public static function regenerateDetails( $xml, $force = false )
    {
        self::refreshCatalogXml( $force );
        
        foreach ( $xml->formations as $formation )
        {
            self::generateDetails( $formation );
        }
    }
    
    /**
     * Regenerate cache for the given training activity from XML data
     * @param SimpleXmlElement $formation
     * @return void
     */
    public static function generateDetails( $formation )
    {
        $detailsTpl = new PhpCachedTemplate(
            TEMPLATE_PATH.'/details.tpl.php',
            HTML_CACHE.'/formation_'.$formation->id.'.tpl.html',
            time() - 3600
        );
        
        $detailsTpl->assign( 'formation', $formation );
        $detailsTpl->assign( 'lastUpdate', time() );
        
        $detailsTpl->render();
    }
}
