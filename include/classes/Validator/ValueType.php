<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Validator that checks the data type of a value
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class Validator_ValueType implements Validator
{
    protected static $supportedType = array(
        'alnum'     => 'ctype_alnum',
        'alpha'     => 'ctype_alpha',
        'array'     => 'is_array',
        'bool'      => 'is_bool',
        'boolstr'   => array('Validator_ValueType', 'booleanString'),
        'digit'     => 'ctype_digit',
        'float'     => 'is_float',
        'floatstr'  => array('Validator_ValueType', 'floatString'),
        'int'       => 'is_int',
        'intstr'    => array('Validator_ValueType', 'integerString'),
        'lower'     => 'ctype_lower',
        'null'      => 'is_null',
        'numeric'   => 'is_numeric',
        'object'    => 'is_object',
        'space'     => 'ctype_space',
        'string'    => 'is_string',
        'upper'     => 'ctype_upper',
        'xdigit'    => 'ctype_xdigit',
        );
    
    /**
     * Allowed types are
     *  - alnum     : the value is an alpha-numeric string
     *  - alpha     : the value only containes alphabetical chars
     *  - array     : the value is an array
     *  - bool      : the value is a boolean
     *  - boolstr   : the value is the string 'true' or 'false'
     *  - digit     : the value is a string containing only digits
     *  - float     : the value is a float
     *  - floatstr  : the value is a float or the string representation of a float
     *  - int       : the value is an integer
     *  - intstr    : the value is a integer or the string representation of an integer
     *  - lower     : ths value is a lower case string
     *  - null      : the value is null
     *  - numeric   : the value is a number or a string representation of a number
     *  - object    : the value is an object
     *  - space     : the value is a string only containing white spaces
     *  - string    : the value is a string
     *  - upper     : the value is an upper case
     *  - xdigit    : the value is a string representation of an hexadecimal number
     * WARNING : do not use int, float, bool for strings representations, it will
     * fail. Use digit, numeric, intstr, floatstr and boolstr instead.
     * @param   string $type;
     * @throws  Validator_Exception if $type is not supported
     */    
    public function __construct( $type )
    {
        if ( array_key_exists( $type, self::$supportedType ) )
        {
            $this->type = $type;
        }
        else
        {
            throw new Validator_Exception("Unsupported type {$type}");
        }
    }
    
    /**
     * @see     Validator
     */
    public function isValid( $value )
    {
        if ( call_user_func( self::$supportedType[$this->type], $value ) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    private static function booleanString( $value )
    {
        return strtolower( $value ) == 'true' || strtolower( $value ) == 'false';
    }
    
    private static function floatString( $value )
    {
        return is_numeric( $value ) && (float) $value == $value;
    }
    
    private static function integerString( $value )
    {
        return is_numeric( $value ) && (int) $value == $value;
    }
}

