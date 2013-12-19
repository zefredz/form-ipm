<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Simple PHP-based template class
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class PhpTemplate implements Template
{
    protected $_templatePath;
    protected $_contents;
    
    /**
     * Constructor
     * @param   string $templatePath path to the php template file
     */
    public function __construct( $templatePath )
    {
        $this->_templatePath = $templatePath;
        $this->_contents = '';
    }
    
    public function getContents()
    {
        return $this->_contents;
    }
    
    /**
     * Assign a value to a variable
     * @param   string $name
     * @param   mixed $value
     */
    public function assign( $name, $value )
    {
        $this->$name = $value;
        
        return $this;
    }
    
    /**
     * Render the template
     * @return  string
     * @throws  Exception if file not found or error/exception in the template
     */
    public function render()
    {
        if ( file_exists( $this->_templatePath ) )
        {
            $ob = new Ob();
            $ob->start();
            try
            {
                include $this->_templatePath;
                $this->_contents = $ob->getContents();
                $ob->endClean();
                
                return $this->_contents;
            }
            catch ( Exception $e )
            {
                $this->_contents = $ob->handleException();
                
                throw $e;
            }
        }
        else
        {
            throw new Exception("Template file not found {$this->_templatePath}");
        }
    }
}

