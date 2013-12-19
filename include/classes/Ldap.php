<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * LDAP connection class
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 *
 * Usage :
 *
 *      $rootDomain = "o=universite catholique de louvain, c=be";
 *      $server = "ldaps://ldap.sgsi.ucl.ac.be";
 *      $port = "636";
 *
 *      $ldap = new Ldap( $server, $port, $rootDomain );
 *      $ldap->connect();
 *
 *      // bind en anonyme
 *      $ldap->bindAnonymous();
 *
 *      // recuperation des donnes de l'utilisateur
 *      $user = $ldap->getUser( $uid );
 *      // accès via $user->getDn(), $user->getData()
 *      
 *      // authentification de l'utilisateur
 *      $success = $ldap->authenticate( $user->getDn(), $pw );
 *
 *      // fin de la transaction LDAP
 *      $ldap->close(); 
 */

class Ldap
{
    protected $ds;
    protected $server, $port;
    protected $rootDomain;
    
    public function __construct( $server, $port, $rootDomain = '' )
    {
        $this->server = $server;
        $this->port = $port;
        $this->rootDomain = $rootDomain;
    }
    
    public function __destruct()
    {
        $this->close();
        unset( $this->ds, $this->server, $this->port, $this->rootDomain );
    }
    
    public function connect()
    {
        $this->ds = ldap_connect( $this->server, $this->port );
        
        if ( $this->ds )
        {
            ldap_set_option( $this->ds, LDAP_OPT_PROTOCOL_VERSION, 3 );
        }
        else
        {
            throw new Exception("Cannot connect to LDAP server");
        }
        
        return $this;
    }
    
    public function bind( $dn, $pw )
    {
        if ( false === @ldap_bind( $this->ds, $dn, $pw ) )
        {
            throw new Exception("Cannot bind to server");
        }
        
        return $this;
    }
    
    public function bindAnonymous()
    {
        if ( false === @ldap_bind( $this->ds ) )
        {
            throw new Exception("Cannot bind to server");
        }
        
        return $this;
    }
    
    public function close()
    {
        if ( $this->ds )
        {
            @ldap_unbind( $this->ds );
            @ldap_close( $this->ds );
        }
        
        return $this;
    }
    
    /**
     * Get one user from the LDAP given its uid
     * @return Ldap_User or false
     */
    public function getUser( $uid )
    {
        $sr = ldap_search( $this->ds, $this->rootDomain, "uid={$uid}" );
        
        if ( ldap_count_entries( $this->ds, $sr ) == 1 )
        {
            // get the resource of the user
            $re = ldap_first_entry( $this->ds, $sr );
            // get the data of the user as an array
            $entries = ldap_get_entries( $this->ds, $sr );
            // user object from the dn and the entries corresponding to the user
            $user = new Ldap_User( ldap_get_dn( $this->ds, $re ), $entries[0] );

            return $user;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Search the LDAP
     * @return array of matching entries or false
     */
    public function search( $searchString, $filterString = null )
    {
        if ( empty( $file ) )
        {
            $sr = ldap_search( $this->ds, $this->rootDomain, $searchString );
        }
        else
        {
            $sr = ldap_search( $this->ds, $this->rootDomain, $searchString, $filterString );
        }
        
        if ( ldap_count_entries( $this->ds, $sr ) > 0 )
        {
            $entries = ldap_get_entries( $this->ds, $sr );
            return $entries;
        }
        else
        {
            return false;
        }
    }
    
    public function authenticate( $dn, $pw )
    {
        try
        {
            $this->bind( $dn, $pw );
            return true;
        }
        catch ( Exception $e )
        {
            return false;
        }
    }
}

