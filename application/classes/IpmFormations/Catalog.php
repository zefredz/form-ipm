<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Catalog helpers
 *
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
 
class IpmFormations_Catalog
{
    /**
     * Get the HTML icon for a formation of a given type
     * @param int type id
     * @return string HTML code for the icon
     */
    public static function getTypeIcon( $formationTypeId )
    {
        switch ( $formationTypeId )
        {
            case 1:
                return '<img src="./assets/img/info_2_small.png" alt="pour en savoir plus" />';
            case 2:
                return '<img src="./assets/img/plusloin_2_small.png" alt="pour aller plus loin" />';
            case 3:
                return '<img src="./assets/img/phare_1_small.png" alt="activité phare" />';
            case 4:
                return '<img src="./assets/img/a_la_demande_small.png" alt="activité à la demande" />';
            case 5:
                return '<img src="./assets/img/formation_ciblee_small.png" alt="formation ciblée" />';
            case 14:
                return '<img src="./assets/img/academie_louvain_small.png" alt="Académie Louvain" />';
            default:
                return '';
        }
    }
    
    /**
     * @param int $formationId
     * @return bool
     */
    public static function isFormationActiveThisYear( $formationId )
    {
        $activeThisYear = IpmFormations_Init::database()->query("
            SELECT
                `active_cette_annee`
            FROM
                `formations`
            WHERE
                `id` = ".IpmFormations_Init::database()->escape($formationId)."
        ")->setFetchMode(Database_ResultSet::FETCH_VALUE)->fetch();
        
        return $activeThisYear != 'non';
    }
}

