<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Xataface CMS helpers
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class IpmFormations_Website_Texts
{
    /**
     * Get a text to display from the database
     * @param string $label text identification label
     * @return string text
     * @table-select textes_du_site
     */
    public static function getText( $label )
    {
        $db = IpmFormations_Init::database();
        
        // var_dump( $db );
        
        // die();
        
        $res = IpmFormations_Init::database()->query("
            SELECT
                `contenu`
            FROM
                `textes_du_site`
            WHERE
                `etiquette` = ".IpmFormations_Init::database()->quote($label)."
        ");
        
        if ( $res->numRows() )
        {
            return utf8_encode( $res->fetch(Database_ResultSet::FETCH_VALUE) );
        }
        else
        {
            return '';
        }
    }
}

