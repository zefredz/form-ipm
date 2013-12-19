<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Data validator interface
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
 
interface Validator
{
    /**
     * Check if the given value is valid
     * @param   mixed $value
     * @return  boolean
     */
    public function isValid( $value );
}

