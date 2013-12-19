<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Xataface Queries Factory
 *
 *
 * @version     2.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class IpmFormations_XatafaceQueries
{
    /**
     * Get query to retreive XML data about a user
     * @param int $personneId id of the user in the table "personnes"
     * @return Xataface_Query
     */
    public static function getUserXmlQuery( $personneId )
    {
        $query = new Xataface_Query(XATAFACE_URL);
        
        $query->setQueryParameters( 'personnes', array(
            '--single-record-only' => 1,
            'id' => '='.(int)$personneId
        ) );
        
        $query->setLimit( 1 );
        
        return $query;
    }
    
    /**
     * Get query to retreive XML for the "locaux" data
     * @param bool $force set to true to force the refresh
     * @return Xataface_Query
     */
    public static function getLocauxXmlQuery()
    {
        $query = new Xataface_Query(XATAFACE_URL);
        
        $query->setQueryParameters( 'locaux', array(
            '-mode' => 'list'
        ) );
        
        return $query;
    }
    
    /**
     * Get query to retreive XML for the "calendar" data
     * @param bool $force set to true to force the refresh
     * @return Xataface_Query
     */
    public static function getCalendarXmlQuery()
    {
        $query = new Xataface_Query(XATAFACE_URL);
        
        $query->setQueryParameters( 'sessions', array(
            '-sort' => 'date asc',
            'date' => '>='.date('Y-m-d', time() - 24 * 3600 )
        ) );
        
        return $query;
    }
    
    /**
     * Get query to retreive XML for the "sessions" data
     * @param bool $force set to true to force the refresh
     * @return Xataface_Query
     */
    public static function getSessionsXmlQuery()
    {
        $query = new Xataface_Query(XATAFACE_URL);
        
        $query->setQueryParameters( 'sessions', array(
            '-sort' => 'date asc',
            'date' => '>='.date('Y-m-d', time() - 24 * 3600 )
        ) );
        
        return $query;
    }
    
    /**
     * Get query to retreive XML for the "catalog" data
     * @param bool $force set to true to force the refresh
     * @return Xataface_Query
     */
    public static function getCatalogXmlQuery()
    {
        $query = new Xataface_Query(XATAFACE_URL);
        
        $query->setQueryParameters( 'formations', array(
            '-mode' => 'list',
            'active_cette_annee' => '=oui'
        ) );
        
        return $query;
    }
    
    /**
     * Get query to retreive XML for the "sessions" data for the given session
     * @param int $sessionId id of the session
     * @return Xataface_Query
     */
    public static function getSessionXmlQuery( $sessionId )
    {
        $query = new Xataface_Query(XATAFACE_URL);
        
        $query->setQueryParameters( 'sessions', array(
            '-sort' => 'date asc',
            'id' => '='.(int) $sessionId
        ) );
        
        return $query;
    }
    
    /**
     * Get query to retreive XML for the "formations" data for the given formation
     * @param int $sessionId id of the session
     * @return Xataface_Query
     */
    public static function getFormationXmlQuery( $formationId )
    {
        $query = new Xataface_Query(XATAFACE_URL);
        
        $query->setQueryParameters( 'formations', array(
            '--single-record-only' => 1,
            'id' => '='.(int) $formationId
        ) );
        
        $query->setLimit( 1 );
        
        return $query;
    }
}

