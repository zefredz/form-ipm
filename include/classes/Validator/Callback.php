<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Validator that uses a given PHP callback to validate a value
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
 
class Validator_Callback implements Validator
{
    protected $callback;
    
    /**
     * @param   callback $callback;
     * @throws  Validator_Exception if $callback is not callable
     */
    public function __construct( $callback )
    {
        if ( ! is_callable( $this->callback ) )
        {
            throw new Validator_Exception("Callback ".var_export($this->callback, true)."is not callable");
        }
        else
        {
            $this->callback = $callback;
        }
    }
    
    /**
     * @see Validator
     */
    public function isValid( $value )
    {
        return call_user_func( $this->callback, $value );
    }
}

