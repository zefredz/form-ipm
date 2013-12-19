<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Dummy validator for debugging
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class Validator_AlwaysSucceed implements Validator
{
    /**
     * @see     Validator
     */
    public function isValid( $value )
    {
        return true;
    }
}

