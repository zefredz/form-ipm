<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Validator that checks if a value is not empty based on PHP's empty()
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
 
class Validator_NotEmpty implements Validator
{
    /**
     * @see     Validator
     */
    public function isValid( $value )
    {
        return ( !empty( $value ) );
    }
}

