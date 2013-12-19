<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Manage user registration to sessions
 *
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class IpmFormations_SessionRegistration
{
    /**
     * Register a user to a session
     * @param int $personneId
     * @param int $sessionId
     * @return bool
     * @table-insert sessions_participants
     */
    public static function register( $personneId, $sessionId )
    {
        if ( IpmFormations_Init::database()->exec("
            INSERT INTO `sessions_participants`
                (`personne_id`, `session_id`)
            VALUES
                (".IpmFormations_Init::database()->escape($personneId).",".IpmFormations_Init::database()->escape($sessionId).")
        ") )
        {
            return IpmFormations_Init::database()->exec("
                UPDATE
                    `personnes`
                SET
                    `date_derniere_inscription` = " . IpmFormations_Init::database()->quote( gmdate('Y-m-d H:i:s') ) . "
                WHERE
                    `id` = ".IpmFormations_Init::database()->escape($personneId)."
            ");
        }
    }
    
    /**
     * Check if a user is already registered to a session
     * @param int $personneId
     * @param int $sessionId
     * @return bool
     * @table-select sessions_participants
     */
    public static function isAlreadyRegistered( $personneId, $sessionId )
    {
        $res = IpmFormations_Init::database()->query("
            SELECT
                `personne_id`
            FROM
                `sessions_participants`
            WHERE
                `personne_id` = ".IpmFormations_Init::database()->escape($personneId)."
            AND
                `session_id` = ".IpmFormations_Init::database()->escape($sessionId)."
        ");
        
        return $res->numRows();
    }
    
    /**
     * Get information about a user registration in a session
     * @param int $personneId
     * @param int $sessionId
     * @return stdClass
     * @table-select sessions_participants
     */
    public static function getUserRegistrationInfo( $personneId, $sessionId )
    {
        $res = IpmFormations_Init::database()->query("
            SELECT
                `session_id`,
                `personne_id`,
                `preinscription`,
                `statut`,
                `confirmation`,
                `prix`,
                `intervenant`
            FROM
                `sessions_participants`
            WHERE
                `personne_id` = ".IpmFormations_Init::database()->escape($personneId)."
            AND
                `session_id` = ".IpmFormations_Init::database()->escape($sessionId)."
        ");
        
        if ( $res->numRows() )
        {
            return $res->fetch(Database_ResultSet::FETCH_OBJECT);
        }
        else
        {
            return null;
        }
    }
    
    /**
     * Get the list of sessions a user is registered in
     * @param int $personneId
     * @return Database_ResultSet
     * @table-select sessions_participants
     */
    public static function getUserRegistrations( $personneId )
    {
        $res = IpmFormations_Init::database()->query("
            SELECT
                f.`titre`,
                f.`type_de_formation`,
                s.`date`,
                s.`horaire`,
                l.`lieu`,
                l.`adresse`,
                sf.`formation_id`,
                sp.`session_id`,
                sp.`preinscription`,
                sp.`statut`,
                sp.`confirmation`,
                sp.`prix`,
                sp.`intervenant`
            FROM
                `sessions_participants` AS sp
                
            JOIN
                `sessions` AS s
            ON
                s.`id` = sp.`session_id`
            JOIN
                `sessions_formations` as sf
            ON
                sf.`session_id` = sp.`session_id`
            JOIN
                `formations` AS f
            ON
                f.`id` = sf.`formation_id`
                
            JOIN
                `locaux` AS l
            ON
                l.`id` = s.`locaux`
                
            WHERE
                sp.`personne_id` = ".IpmFormations_Init::database()->escape($personneId)."
                
            ORDER BY s.`date` ASC
        ");
        
        return $res->setFetchMode(Database_ResultSet::FETCH_OBJECT);
    }
    
    /**
     * Count the number of sessions a user is registered in
     * @param int $personneId
     * @return int
     * @table-select sessions_participants
     */
    public static function countRegistrations( $sessionId, $filterOrg = true, $confirmeOnly = false, $removeExcuses = true )
    {
        if ( $filterOrg == true )
        {
            $filterOrg = "
                AND
                    `intervenant` = 'participant'";
        }
        else
        {
            $filterOrg = "";
        }
        
        if ( $confirmeOnly == true )
        {
            $filterConfirme = "
                AND
                    `confirmation` = 'confirme'";
        }
        else
        {
            $filterConfirme = "
                AND
                    `confirmation` != 'refuse'
            ";
        }
        
        if ( $removeExcuses == true )
        {
            $filterExcuses = "
                AND
                    `statut` != 'excuse'";
        }

        $res = IpmFormations_Init::database()->query("
            SELECT
                `personne_id`
            FROM
                `sessions_participants`
            WHERE
                `session_id` = ".IpmFormations_Init::database()->escape($sessionId)."
            {$filterConfirme}
            {$filterOrg}
            {$filterExcuses}
        ");
    
        return $res->numRows();
 
    }
}
