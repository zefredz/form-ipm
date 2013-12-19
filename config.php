<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Configuration parameters
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

// Paths
define( 'BASE_PATH',                dirname(__FILE__) );
define( 'INC_PATH',                 BASE_PATH.'/include');
define( 'LIB_PATH',                 INC_PATH.'/lib/' );

define( 'APP_PATH',                 BASE_PATH.'/application');
define( 'TEMPLATE_PATH',            APP_PATH.'/templates' );

define( 'XML_CACHE',                BASE_PATH.'/xml_cache' );
define( 'HTML_CACHE',               BASE_PATH.'/templates_c' );

// Xataface parameters
define( 'XATAFACE_URL',             'http://127.0.0.1/ipm/backend/index.php' );

// Database parameters
define( 'DATABASE_HOST',            'localhost' );
define( 'DATABASE_USER',            'user' );
define( 'DATABASE_PASS',            'password' );
define( 'DATABASE_BASE',            'database' );

// Mail parameters
define( 'SMTP_HOST',                'smtp.local.host' );
define( 'SMTP_PORT',                587 );

// LDAP parameters
define( 'LDAP_HOST',                'ldap.local.host' );
define( 'LDAP_PORT',                389 );
define( 'LDAP_DN',                  'o=mon institution,c=be' );

// Misc parameters
define( 'DEBUG_MODE',               false );

