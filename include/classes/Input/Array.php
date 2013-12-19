<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Array based data input class
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class Input_Array implements Input
{
    protected $input;
    protected $_notSet;
    
    /**
     * @param   array $input
     */
    public function __construct( $input )
    {
        $this->input = $input;
        // create a singleton object for the getMandatory method
        // this object will be used to check if a value is defined
        // in the input data in order to avoid pitfalls with the empty()
        // PHP function
        $this->_notSet = (object) null;
    }
    
    /**
     * @see     Input
     */
    public function get( $name, $default = null )
    {
        if ( array_key_exists( $name, $this->input ) )
        {
            return $this->input[$name];
        }
        else
        {
            return $default;
        }
    }
    
    /**
     * @see     Input
     */
    public function getMandatory( $name )
    {
        // get the value of the requested variable and give the _notSet
        // singleton object as the default value so we can check if the
        // varaible was set without having issues with the empty() function
        $ret = $this->get( $name, $this->_notSet );
        
        // check if $ret is the instance of the _notSet singleton object
        // if it is the case, the requested variable has not been set
        // in the input data so we have to throw an exception
        if ( $ret === $this->_notSet )
        {
            throw new Input_Exception(
                "{$name} not found in ".get_class($this)." !" );
        }
        else
        {
            return $ret;
        }
    }
}

