<?php  // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Application Init
 *
 * @version     2.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
 
class IpmFormations_Init
{
    protected static $_dbLink = false;
    protected static $_ldap = false;
    protected static $_log = false;
    
    public static function database()
    {
        if ( !self::$_dbLink )
        {
            self::$_dbLink = new Database_Connection_Mysql(
                DATABASE_HOST,
                DATABASE_USER,
                DATABASE_PASS,
                DATABASE_BASE
            );
            
            self::$_dbLink->connect();
        }
        
        return self::$_dbLink;
    }
    
    public static function ldap()
    {
        if ( !self::$_ldap )
        {
            self::$_ldap = new Ldap(
                LDAP_HOST,
                LDAP_PORT,
                LDAP_DN
            );
            
            self::$_ldap->connect();
        }
        
        return self::$_ldap;
    }
    
    public static function user()
    {
        return isset($_SESSION['user'])
            ? $_SESSION['user']
            : null
            ;
    }
    
    public static function input()
    {
        return UserInput::getInstance();
    }
    
    public static function log()
    {
        if ( !self::$_log )
        {
            self::$_log = new Log_ErrorLog();
        }
        
        return self::$_log;
    }
}

