<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Validator that checks if a value is not empty but considers
 * '0', 0 and false as not empty !
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class Validator_CustomNotEmpty implements Validator
{
    /**
     * @see     Validator
     */
    public function isValid( $value )
    {
        return ( is_numeric($value) || is_bool($value) || !empty( $value ) );
    }
}

