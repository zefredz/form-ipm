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

class ClassLoader_ClassPathArray implements ClassLoader
{
    private $_classPathArray = array();
    
    /**
     * Create an autoloader that loads class located in one of the given folders.
     *  Interprets underscores in the class name as folder separator.
     * @param array $classPathArray array of folders where to look for the classes
     */
    public function __construct( $classPathArray )
    {
        $this->_classPathArray = $classPathArray;
    }
    
    public function load( $className )
    {
        $translatedClassName = str_replace( '_', '/', $className ) . '.php';
        
        foreach ( $this->_classPathArray as $classPath )
        {
            if ( file_exists( rtrim( $classPath, '/').'/'.$translatedClassName ) )
            {
                require rtrim( $classPath, '/').'/'.$translatedClassName;
            }
        }
    }
}
