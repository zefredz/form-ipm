<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * User input class to replace $_REQUEST
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class UserInput
{        
    protected static $instance = false;
    
    /**
     * Get user input object
     * @return  Input_Validator
     */
    public static function getInstance()
    {
        if ( ! self::$instance )
        {
            // Create an input validator instance using the $_GET
            // and $_POST super arrays
            self::$instance = new Input_Validator( 
                new Input_Array( array_merge( $_GET, $_POST ) ) );
            
            self::$instance->setValidatorForAll( new Validator_CustomNotEmpty() );
        }
        
        return self::$instance;
    }
}

