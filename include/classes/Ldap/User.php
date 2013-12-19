<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * LDAP User class
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class Ldap_User
{
    protected $dn;
    protected $data;
    
    public function __construct( $dn, $data )
    {
        $this->dn = $dn;
        $this->data = $data;
    }

    /**
     * Get the dn of the user
     * @return string
     */
    public function getDn()
    {
        return $this->dn;
    }

    /**
     * Get the dat of the user
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    
    public function getUid()
    {
        return $this->data['uid'][0];
    }
}

