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

class TabbedLayout implements Template
{
    protected $template, $tabs, $panes;
    
    public function __construct( Template $template )
    {
        $this->template = $template;
        $this->tabs = array();
        $this->panes = array();
    }
    
    public function addTab ( $id, $label, View $pane )
    {
        $this->tabs[$id] = $label;
        $this->panes[$id] = $pane;
    }
    
    public function render()
    {
        $this->template->assign( 'tabs', $this->tabs );
        $this->template->assign( 'panes', $this->panes );
        
        return $this->template->render();
    }
    
    public function assign( $name, $value )
    {
        $this->template->assign( $name, $value );
    }
}
