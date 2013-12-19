<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Validator that checks if a value has the given file extension
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class Validator_FileExtension implements Validator
{
    protected $extension;
    
    /**
     * @param   string $extension file extension
     */
    public function __construct( $extension )
    {
        $extension = $extension[0] == '.'
            ? substr( $extension, 1 )
            : $extension
            ;
            
        $this->extension = $extension;
    }
    
    /**
     * @see     Validator
     */
    public function isValid( $value )
    {
        return ( pathinfo( $value, PATHINFO_EXTENSION ) == $this->extension );
    }
}

