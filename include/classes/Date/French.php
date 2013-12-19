<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * French Date L10N utility class
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class Date_French
{
    public static function date( $format, $timestamp = null ) 
    {
        $param_D = array('', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim');
        $param_l = array('', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        $param_F = array('', 'Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
        $param_M = array('', 'Jan', 'F&eacute;v', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Ao&ucirc;', 'Sep', 'Oct', 'Nov', 'D&eacute;c');

        $return = '';
        
        if(is_null($timestamp)) { $timestamp = mktime(); }
        
        for($i = 0, $len = strlen($format); $i < $len; $i++) 
        {
            switch($format[$i]) 
            {
                case '\\' : // fix.slashes
                    $i++;
                    $return .= isset($format[$i]) ? $format[$i] : '';
                    break;
                case 'D' :
                    $return .= $param_D[date('N', $timestamp)];
                    break;
                case 'l' :
                    $return .= $param_l[date('N', $timestamp)];
                    break;
                case 'F' :
                    $return .= $param_F[date('n', $timestamp)];
                    break;
                case 'M' :
                    $return .= $param_M[date('n', $timestamp)];
                    break;
                default :
                    $return .= date($format[$i], $timestamp);
                    break;
            }
        }
        
        return $return;
    }
}

