<?php  // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Class loader interface
 *
 * @version     2.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
 
interface ClassLoader
{
    /**
     * Load a class from the framework class folder
     * @param    string $className
     */
    public function load( $className );
}
 