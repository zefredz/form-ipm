form-ipm
========

Outil de gestion des formations de l' Institut de pédagogie universitaire et des multimédias de l'Université catholique de Louvain. 

Requiert Xataface pour fonctionner.

@copyright   2001-2012 Universite catholique de Louvain (UCL)
@author      Frederic Minne <frederic.minne@uclouvain.be>
@license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
              GNU AFFERO GENERAL PUBLIC LICENSE version 3

This application is build using a framework specific for creating application using a Xataface backend.

Folders and files:

- application : contains the application specific files
    - templates : php templates definition
    - classes : application specific classes
    - init.php, config.php : application specific initialization and configuration (not required by the framework)
    - \__autoload__.php : application classes autoloader
- include : contains the framework libraries
    - classes : framework class
    - lib : libraries and helpers
    - thirdparty : vendor classes and libraries
        - \*/ \_\_autoload\_\_.php : framework autoloader declaration files
- assets : web assets
    - img : images and icons
    - js : javascript libraries
    - css : stylesheets
- templates_c : compiled templates cache
- xml_cache : cache of the xml responses from xataface
- config.php : the framework configuration
- init.php : the framework initialization script
- index.php : main application controller
- account.php : account management controller (only allows to reset the user password
    at this time)
- creer.php : controller to create a local user account (todo: merge with account.php)
- login.php : sign in controller using both LDAP and local user accounts
- session.php : controller to register oneself to a training activity
- rss.php : publish the training activity catalog as a RSS feed
    (used by uclouvain.be portal)
- vieprivee.php : controller to display privacy policy

Command line scripts :

    - refreshcache.sh : force the application to reload and regenerate xml and compiled templates caches
