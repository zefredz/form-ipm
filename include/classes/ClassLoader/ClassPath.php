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

class ClassLoader_ClassPath implements ClassLoader
{
    private $_classPath;
    
    /**
     * Create an autoloader that loads class located in the given folder.
     *  Interprets underscores in the class name as folder separator.
     * @param string $classPath
     */
    public function __construct( $classPath )
    {
        $this->_classPath = $classPath;
    }
    
    public function load( $className )
    {
        $path = $this->_classPath . '/' . str_replace( '_', '/', $className ) . '.php';
            
        if ( file_exists($path))
        {
            require $path;
        }
    }
}
