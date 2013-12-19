<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Manage and query Xataface user list by connecting directly to the database
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class IpmFormations_UserList
{
    /**
     * Get user data
     * @param string $uid user login
     * @return array user
     * @table-select personnes
     */
    public static function getUser( $uid )
    {
        $user = IpmFormations_Init::database()->query("
            SELECT
                `id`,
                `utilisateur`,
                `fgs_ucl`,
                `nom`,
                `prenom`,
                `mail_contact`
            FROM
                `personnes`
            WHERE
                `utilisateur` = ".IpmFormations_Init::database()->quote($uid)."
        ")->fetch();
        
        if ( $user )
        {
            $user['nom'] = iconv('ISO-8859-1','UTF-8',$user['nom']);
            $user['prenom'] = iconv('ISO-8859-1','UTF-8',$user['prenom']);
        }
        
        return $user;
    }
    
    /**
     * Authenticate local users against the database
     * @param string $uid user login
     * @param string $password user password
     * @return bool
     * @table-select personnes
     */
    public static function authenticate( $uid, $password )
    {
        $password = md5($uid.$password);
        
        return IpmFormations_Init::database()->query("
            SELECT
                `id`
            FROM
                `personnes`
            WHERE
                `utilisateur` = ".IpmFormations_Init::database()->quote($uid)."
            AND
                `motdepasse` = ".IpmFormations_Init::database()->quote($password)."
            AND
                `fgs_ucl` IS NULL
        ")->numRows();
    }
    
    protected static function removeLdapCount( $data )
    {
        if ( isset($data['count']) )
        {
            unset($data['count']);
        }
        
        return $data;
    }
    
    /**
     * Register a user from the LDAP
     * @param Ldap_User $ldapUser
     * @return void
     * @table-insert personnes
     * @table-insert reporting
     */
    public static function registerLdapUser( $ldapUser )
    {
        // var_dump($ldapUser);

        $rawData = $ldapUser->getData();

        // echo '<pre>'; var_dump($rawData); echo '</pre>';
        
        $entites = isset( $rawData['uclentite'] ) ? $rawData['uclentite'] : null;

        if ( $entites )
        {
            unset( $entites['count'] );
        
            $struct = new UclEntiteStructure( $entites );
            $struct->parse();
            $structArr = $struct->getParsedStructure();
        }
        else
        {
            $structArr = array();
        }
        
        $personneData = array(
            'nom' => iconv( 'UTF-8', 'ISO-8859-1', $rawData['sn'][0] ),
            'prenom' => iconv( 'UTF-8', 'ISO-8859-1', $rawData['givenname'][0] ),
            'utilisateur' => $rawData['uid'][0],
            'fgs_ucl' => $rawData['employeenumber'][0],
            'mail_travail' => UclUtils::uclMail($rawData['mail']),
            'mail_contact' => UclUtils::uclMail($rawData['mail']), // $rawData['mail'][0],
            'telephone' => isset($rawData['telephonenumber']) ? implode(',', self::removeLdapCount($rawData['telephonenumber'])) : null,
            'type_institution' => 'LOU',
            'institution' => 'UCL',
            'secteur' => isset( $structArr['firstlvl'] ) ? implode( ',', $structArr['firstlvl'] ) : null, 
            'faculte' => isset( $structArr['secondlvl'] ) ? implode( ',', $structArr['secondlvl'] ) : null,
            'departement' => isset( $structArr['thirdlvl'] ) ? implode( ',', $structArr['thirdlvl'] ) : null,
            'unite' => isset( $structArr['fourthlvl'] ) ? implode( ',', $structArr['fourthlvl'] ) : null,
            'adresse' => isset($rawData['postaladdress']) ? implode(',', self::removeLdapCount($rawData['postaladdress'])) : 'NULL',
            'mail_travail_actif' => 'oui',
            'mail_contact_actif' => 'oui',
            'formateur' => 'non',
            'date_creation' => gmdate('Y-m-d H:i:s')
        );
        
        if ( isset( $rawData['title'] ) )
        {
            $ldapTitle = self::removeLdapCount($rawData['title']);
            
            $personneData['titre'] = $ldapTitle[0] == 'Madame' ? 'Mme' : 'Mr';
        }
        else
        {
            $personneData['titre'] = null;
        }
        
        $sqlFields = array();
        
        foreach ( $personneData as $field => $value )
        {
            if ( !is_null( $value ) )
            {
                $sqlFields[] = IpmFormations_Init::database()->quote( $value );
            }
            else
            {
                $sqlFields[] = 'NULL';
            }
        }
        
        IpmFormations_Init::database()->exec( "
            INSERT INTO `personnes`
            (`".(implode('`,`',array_keys($personneData)))."`)
            VALUES
            (" . implode(',',$sqlFields) .");
        ");
        
        $pid = IpmFormations_Init::database()->insertId();
        
        $reportingData = array(
            'personne_id' => $pid,
            'nom' => iconv( 'UTF-8', 'ISO-8859-1', $rawData['sn'][0] ),
            'prenom' => iconv( 'UTF-8', 'ISO-8859-1', $rawData['givenname'][0] ),
            'ucl_fgs' => $rawData['employeenumber'][0],
            'ucl_noma' => isset($rawData['uclnoma']) ? $rawData['uclnoma'][0] : null,
            'ucl_fournisseur' => isset($rawData['uclfournisseur']) ? implode(',', self::removeLdapCount($rawData['uclfournisseur'])) : null
        );
        
        $sqlFields = array();
        
        foreach ( $reportingData as $field => $value )
        {
            if ( !is_null( $value ) )
            {
                $sqlFields[] = IpmFormations_Init::database()->quote( $value );
            }
            else
            {
                $sqlFields[] = 'NULL';
            }
        }
        
        IpmFormations_Init::database()->exec( "
            INSERT INTO `reporting`
            (`".(implode('`,`',array_keys($reportingData)))."`)
            VALUES
            (" . implode(',',$sqlFields) .");
        ");
    }
    
    /**
     * Sync user data with data from the LDAP
     * @param Ldap_User $ldapUser
     * @return void
     * @table-update personnes
     * @table-update reporting
     */
    public function syncLdapUser ( $ldapUser )
    {
        $rawData = $ldapUser->getData();
        $user = self::getUser( $ldapUser->getUid() );
        $id = $user['id'];
    
        $entites = isset( $rawData['uclentite'] ) ? $rawData['uclentite'] : null;

        if ( $entites )
        {
            unset( $entites['count'] );
        
            $struct = new UclEntiteStructure( $entites );
            $struct->parse();
            $structArr = $struct->getParsedStructure();
        }
        else
        {
            $structArr = array();
        }

        $personneData = array(
            'mail_travail' => isset( $rawData['mail'] ) ? UclUtils::uclMail($rawData['mail']) : '',
            'mail_contact' => isset( $rawData['mail'] ) ? UclUtils::uclMail($rawData['mail']) : '',
            'telephone' => isset($rawData['telephonenumber']) ? implode(',', self::removeLdapCount($rawData['telephonenumber'])) : 'NULL',
            'adresse' => isset($rawData['postaladdress']) ? implode(',', self::removeLdapCount($rawData['postaladdress'])) : 'NULL',
            'institution' => 'UCL',
            'secteur' => isset( $structArr['firstlvl'] ) ? implode( ',', $structArr['firstlvl'] ) : null, 
            'faculte' => isset( $structArr['secondlvl'] ) ? implode( ',', $structArr['secondlvl'] ) : null,
            'departement' => isset( $structArr['thirdlvl'] ) ? implode( ',', $structArr['thirdlvl'] ) : null,
            'unite' => isset( $structArr['fourthlvl'] ) ? implode( ',', $structArr['fourthlvl'] ) : null
        );
        
        if ( isset( $rawData['title'] ) )
        {
            $ldapTitle = self::removeLdapCount($rawData['title']);
            
            $personneData['titre'] = $ldapTitle[0] == 'Madame' ? 'Mme' : 'Mr';
        }
        else
        {
            $personneData['titre'] = null;
        }
        
        $sqlFields = array();
        
        foreach ( $personneData as $field => $value )
        {
            $sqlFields[] = "`{$field}` = " . IpmFormations_Init::database()->quote( $value );
        }
        
        IpmFormations_Init::database()->exec("
            UPDATE `personnes`
            SET
                ".implode(",\n", $sqlFields ) . "
            WHERE
                `id` = ".(int)IpmFormations_Init::database()->escape($id)."
        ");
        
        
        $reportingData = array(
            'ucl_noma' => isset($rawData['uclnoma']) ? $rawData['uclnoma'][0] : 'NULL',
            'ucl_fournisseur' => isset($rawData['uclfournisseur']) ? implode(',', self::removeLdapCount($rawData['uclfournisseur'])) : 'NULL'
        );
        
        $sqlFields = array();
        
        foreach ( $reportingData as $field => $value )
        {
            $sqlFields[] = "`{$field}` = " . IpmFormations_Init::database()->quote( $value );
        }
        
        IpmFormations_Init::database()->exec("
            UPDATE `reporting`
            SET
                ".implode(",\n", $sqlFields ) . "
            WHERE
                `personne_id` = ".(int)IpmFormations_Init::database()->escape($id)."
        ");
        
        return true;
    }
    
    /**
     * Check if a user from the LDAP is already registered in the database
     * @param string $uid user login
     * @return bool
     * @table-select personnes
     */
    public static function uclUserAlreadyInDatabase( $uid )
    {
        $res = IpmFormations_Init::database()->query("
            SELECT
                `utilisateur`
            FROM
                `personnes`
            WHERE
                `utilisateur` = ".IpmFormations_Init::database()->quote($uid)."
        ");
        
        return $res->numRows();
    }
    
    /**
     * Check if a local user account is already registered in the database
     * @param string $uid user login
     * @return bool
     * @table-select personnes
     */
    public static function userAlreadyInDatabase( $uid )
    {
        $res = IpmFormations_Init::database()->query("
            SELECT
                `utilisateur`
            FROM
                `personnes`
            WHERE
                `utilisateur` = ".IpmFormations_Init::database()->quote($uid)."
        ");
        
        return $res->numRows();
    }
    
    /**
     * Register a user local account in the database
     * @param array $localUser
     * @return void
     * @table-insert personnes
     */
    public static function registerLocalUser( $localUser )
    {
        $localUser['nom'] = iconv( 'UTF-8', 'ISO-8859-1', $localUser['nom'] );
        $localUser['prenom'] = iconv( 'UTF-8', 'ISO-8859-1', $localUser['prenom'] );
        $localUser['faculte'] = iconv( 'UTF-8', 'ISO-8859-1', $localUser['faculte'] );
        $localUser['departement'] = iconv( 'UTF-8', 'ISO-8859-1', $localUser['departement'] );
        $localUser['institution'] = iconv( 'UTF-8', 'ISO-8859-1', $localUser['institution'] );
        $localUser['unite'] = iconv( 'UTF-8', 'ISO-8859-1', $localUser['unite'] );
        $localUser['adresse'] = iconv( 'UTF-8', 'ISO-8859-1', $localUser['adresse'] );
        
        $localUser['utilisateur'] = iconv( 'UTF-8', 'ISO-8859-1', $localUser['utilisateur'] );
        $localUser['motdepasse'] = iconv( 'UTF-8', 'ISO-8859-1', $localUser['motdepasse'] );
        
        $localUser['motdepasse'] = md5($localUser['utilisateur'].$localUser['motdepasse']);
        
        $localUser['date_creation'] = gmdate('Y-m-d H:i:s');
        
        foreach ( $localUser as $field => $value )
        {
            $localUser[$field] = IpmFormations_Init::database()->quote( $value );
        }
        
        IpmFormations_Init::database()->exec( "
            INSERT INTO `personnes`
            (`".(implode('`,`',array_keys($localUser)))."`)
            VALUES
            (" . implode(',',$localUser) .");
        ");
    }
}

