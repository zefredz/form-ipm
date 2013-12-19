<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Validator that checks if the value is in a given list
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class Validator_AllowedList implements Validator
{
    protected $allowedValues;
    
    /**
     * @param   array $allowedValues
     */
    public function __construct( $allowedValues )
    {
        $this->allowedValues = $allowedValues;
    }
    
    /**
     * @see     Validator
     */
    public function isValid( $value )
    {
        return in_array( $value, $this->allowedValues );
    }
}

