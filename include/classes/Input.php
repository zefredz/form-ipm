<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Defines the required methods for a data input object
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
interface Input
{
    /**
     * Get a value given its name
     * @param   string $name variable name
     * @param   mixed $default default value (if $name is missingin the input)
     * @return  mixed value of $name in input data or $default value
     * @throws  Input_Exception on failure
     */
    public function get( $name, $default = null );
    /**
     * Get a value given its name, the value must be set in the data
     * but can be empty
     * @param   string $name variable name
     * @return  mixed value of $name
     * @throws  Input_Exception on failure or if $name is missing
     */
    public function getMandatory( $name );
}
 
