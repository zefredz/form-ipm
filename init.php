<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Kernel
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
/*setlocale( LC_ALL, 'fr_BE.utf8' );
date_default_timezone_set('Europe/Brussels');*/

require_once dirname(__FILE__).'/config.php';

if ( file_exists(APP_PATH.'/config.php') )
{
    require_once APP_PATH.'/config.php';
}

require_once LIB_PATH.'/exception.lib.php';
require_once LIB_PATH.'/urlgetcontents.lib.php';
require_once LIB_PATH.'/sendmail.lib.php';

require_once INC_PATH.'/classes/Loader.php';

$loader = new Loader;
$loader->register();

// register auloaders for vendor libraries
Loader::registerVendorClassLoaders( INC_PATH.'/thirdparty' );

// register main application autoloader
Loader::registerApplicationLoader( APP_PATH );

set_error_handler('exception_error_handler', error_reporting() & ~E_STRICT);

// Protection against HTTP Response Splitting and XSS injections using PHP_SELF
$_SERVER['PHP_SELF'] = strip_tags(
    preg_replace(
        '~(\r\n|\r|\n|%0a|%0d|%0D|%0A)~', '',
        $_SERVER['PHP_SELF'] ) );

// Start the session
session_start();

// CSRF token
if ( empty( $_SESSION['csrf_token'] ) || !isset( $_SESSION['csrf_token'] ) )
{
    $_SESSION['csrf_token'] = strrev(md5(time())); //set a token with a reverse string and md5 encryption of the user's password
}

// Validate $_POST data
if ( $_POST )
{
    if ( defined('CSRF_PROTECTED') && CSRF_PROTECTED )
    {
        if ( !isset( $_POST['csrf_token'] ) || ( $_POST['csrf_token'] != $_SESSION['csrf_token'] ) )
        {
            throw Exception("CSRF Exploit !");
        }
    }
}

// Avoid multiple posts of the same WebForm
if ( isset( $_POST['formId'] ) )
{
    if ( ! isset($_SESSION['formIdList']) )
    {
        $_SESSION['formIdList'] = array( $_POST['formId'] );
    }
    elseif ( in_array( $_POST['formId'], $_SESSION['formIdList'] ) )
    {
        $_POST = array();
    }
    else
    {
        $formIdListCount = array_unshift(
            $_SESSION['formIdList'],
            $_POST['formId']
        );
        
        if ( $formIdListCount > 50 )
        {
           array_pop( $_SESSION['formIdList'] );
        }
    }
}

if ( defined('DEBUG_MODE') && DEBUG_MODE )
{
    $assertion = new Debug_Assertion( new Log_ErrorLog() );
    $assertion->register();
}

if ( file_exists(APP_PATH.'/init.php') )
{
    require_once APP_PATH.'/init.php';
}
