<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Output buffering wrapper to provide output
 * buffering with error and exception handling
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class Ob
{
    protected $buffer = '';
    
    public function __construct()
    {
        
    }
    
    public function getContents()
    {
        return ob_get_contents();
    }
    
    public function start()
    {
        set_error_handler('exception_error_handler', error_reporting() & ~E_STRICT);
        ob_start();
        
        return $this;
    }
    
    public function endClean()
    {
        ob_end_clean();
        
        return $this;
    }
    
    public function handleException()
    {
        $this->buffer = $this->getContents();
        $this->endClean();
        
        return $this;
    }
}

