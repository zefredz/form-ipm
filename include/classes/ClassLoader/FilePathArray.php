<?php  // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Class loader
 *
 * @version     2.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class ClassLoader_FilePathArray  implements ClassLoader
{
    private $_filePathArray = array();
    
    /**
     * Create an autoloader that loads class from an associative file path array
     *  consisting in class name => file path association
     * @param array $filePathArray
     */
    public function __construct( $filePathArray )
    {
        $this->_filePathArray = $filePathArray;
    }
    
    public function load( $className )
    {
        if ( is_array( $this->_filePathArray ) && array_key_exists( $className, $this->_filePathArray ) )
        {
            require_once $this->_filePathArray[$className];
        }
    }
}
