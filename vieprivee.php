<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Display disclaimer and terms of use
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

try
{
    require dirname(__FILE__).'/init.php';
    
    $viepriveeTpl = new PhpTemplate(TEMPLATE_PATH.'/vieprivee.tpl.php');
    
    header( 'Content-type: text/html; charset=utf-8' );
    echo $viepriveeTpl->render();
}
catch (Exception $e)
{
    $errorTpl = new PhpTemplate(TEMPLATE_PATH.'/error.tpl.php');
    $errorTpl->assign('errorMessage', $e->getMessage());
    $errorTpl->assign('errorDump', $e->__toString());

    echo $errorTpl->render();
}

