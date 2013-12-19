<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Data input class with filters callback for validation
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class Input_Validator implements Input
{
    protected $validators;
    protected $validatorsForAll;
    protected $input;
    
    /**
     * @param   Input $input
     */
    public function __construct( Input $input )
    {
        $this->validators = array();
        $this->validatorsForAll = array();
        $this->input = $input;
    }
    
    /**
     * Set a validator for the given variable
     * @param   string $name variable name
     * @param   Validator $validator validator object
     * @throws  Input_Exception if the filter callback is not callable
     */
    public function setValidator( $name, Validator $validator )
    {
        if ( ! array_key_exists( $name, $this->validators ) )
        {
            $this->validators[$name] = array();
        }
        
        $validatorCallback = array( $validator, 'isValid' );
        
        if ( ! is_callable( $validatorCallback ) )
        {
            throw new Input_Exception ("Invalid validator callback : " 
                . $this->getFilterCallbackString($validatorCallback));
        }
        
        $this->validators[$name][] = $validatorCallback;
        
        return $this;
    }
    
    /**
     * Set a validator for all variables
     * @param   string $name variable name
     * @param   Validator $validator validator object
     * @throws  Input_Exception if the filter callback is not callable
     */
    public function setValidatorForAll( Validator $validator )
    {
        $validatorCallback = array( $validator, 'isValid' );
        
        if ( ! is_callable( $validatorCallback ) )
        {
            throw new Input_Exception ("Invalid validator callback : " 
                . $this->getFilterCallbackString($validatorCallback));
        }
        
        $this->validatorsForAll[] = $validatorCallback;
        
        return $this;
    }
    
    /**
     * @see     Input
     * @throws  Input_Exception if $value does not pass the validator
     */
    public function get( $name, $default = null )
    {
        $tainted = $this->input->get( $name, $default );
        
        if ( ( is_null( $default ) && is_null( $tainted ) )
            || $tainted == $default )
        {
            return $default;
        }
        else
        {
            return $this->validate( $name, $tainted );
        }
    }
    
    /**
     * @see     Input
     * @throws  Input_Exception if $value does not pass the validator
     */
    public function getMandatory( $name )
    {
        $tainted = $this->input->getMandatory( $name );
        
        return $this->validate( $name, $tainted );
    }
    
    /**
     * @param   string $name
     * @param   mixed $tainted value
     * @throws  Validator_Exception if $value does not pass the
     * filter for $name
     */
    public function validate( $name, $tainted )
    {
        // validators for all variables if any
        if ( !empty ($this->validatorsForAll ) )
        {
            foreach ( $this->validatorsForAll as $validatorForAllCallback )
            {
                if ( ! call_user_func( $validatorForAllCallback, $tainted ) )
                {
                    throw new Validator_Exception(
                        get_class( $validatorForAllCallback[0] )
                        . " : {$name} does not pass the validator !" );
                }
            }
        }
        
        // validators for the requested variable
        if ( array_key_exists( $name, $this->validators ) )
        {
            foreach ( $this->validators[$name] as $validatorCallback )
            {
                if ( ! call_user_func( $validatorCallback, $tainted ) )
                {
                    throw new Validator_Exception(
                        get_class( $validatorCallback[0] )
                        . " : {$name} does not pass the validator !" );
                }
            }
        }
        
        return $tainted;
    }
}

