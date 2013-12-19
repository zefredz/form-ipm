<?php // vim: expandtab sw=4 ts=4 sts=4:

// Application Specific Configuration parameters

// Application urls
define ( 'SECURE_URL',              'https://sites.uclouvain.be/ipm/formations' );
define ( 'NORMAL_URL',              'http://sites.uclouvain.be/ipm/formations');

define( 'LOGIN_URL',                SECURE_URL.'/login.php' );
define( 'LOGIN_OPT_URL',            NORMAL_URL.'/loginoptions.php' );
define( 'INDEX_URL',                NORMAL_URL.'/index.php' );
define( 'DETAILS_URL',              NORMAL_URL.'/index.php' );
define( 'REGISTER_URL',             NORMAL_URL.'/session.php' );
define( 'CREATE_URL',               SECURE_URL.'/creer.php' );
define( 'ACCOUNT_URL',              SECURE_URL.'/account.php' );

// Contact information
define( 'APPLICATION_MANAGER',      'nicole.marion@uclouvain.be' );

define( 'APPLICATION_CONTACT_MAIL', 'nicole.marion@uclouvain.be' );
define( 'APPLICATION_CONTACT_NAME', 'Nicole Marion' );

define( 'FORMATION_CONTACT_MAIL',   'nathalie.kruyts@uclouvain.be' );
define( 'FORMATION_CONTACT_NAME',   'Nathalie Kruyts' );

define( 'NOTIFICATION_CONTACT_MAIL','noreply-formations-ipm@uclouvain.be' );
define( 'NOTIFICATION_CONTACT_NAME','Formations IPM' );