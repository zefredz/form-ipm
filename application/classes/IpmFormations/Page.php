<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Application main page layout
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class IpmFormations_Page extends TabbedLayout
{
    public function __construct()
    {
        $template = new PhpTemplate( TEMPLATE_PATH . '/page.tpl.php' );
        parent::__construct( $template );
    }
}
