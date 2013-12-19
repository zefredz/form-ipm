<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Exception library
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

// PHP Error to Exception converter

/**
 * Error handler to convert PHP errors to Exceptions and so have
 * only one error handling system to handle
 * 
 * taken from php.net online PHP manual
 */
function exception_error_handler( $code, $message, $file, $line )
{
    throw new ErrorException( $message, 0, $code, $file, $line );
}

