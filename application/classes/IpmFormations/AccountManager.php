<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * User Account Manager 
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class IpmFormations_AccountManager
{
    public static function getAccountsByUsername( $username )
    {
        return IpmFormations_Init::database()->query("
                SELECT
                    id,
                    utilisateur,
                    mail_contact AS email,
                    nom,
                    prenom
                FROM
                    personnes
                WHERE
                    utilisateur = ".IpmFormations_Init::database()->quote('ipm_'.$username)."
            ");
    }
    
    public static function getAccountsByEMail( $email )
    {
        return IpmFormations_Init::database()->query("
                SELECT
                    id,
                    utilisateur,
                    mail_contact AS email,
                    nom,
                    prenom
                FROM
                    personnes
                WHERE
                    mail_contact = ".IpmFormations_Init::database()->quote($email)."
            ");
    }
    
    public static function registerResetAccountRequest( $account, $resetkey, $date )
    {
        return IpmFormations_Init::database()->exec("
            INSERT INTO
                `auth_reset_account`
            SET
                utilisateur = ".IpmFormations_Init::database()->quote($account->utilisateur).",
                resetkey = ".IpmFormations_Init::database()->quote($resetkey).",
                unixtimestamp = ".IpmFormations_Init::database()->escape($date+6*3600).",
                email = ".IpmFormations_Init::database()->quote($account->email)."
        ");
    }
    
    public static function getAccountResetInformation( $givenkey )
    {
        return IpmFormations_Init::database()->query("
            SELECT
                id,
                utilisateur,
                resetkey,
                unixtimestamp,
                email
            FROM
                `auth_reset_account`
            WHERE
                resetkey = ".IpmFormations_Init::database()->quote($givenkey)."
        ")->fetch(Database_ResultSet::FETCH_OBJECT);
    }
    
    public static function changeUserPassword( $username, $newpassword )
    {
        // update account
        return IpmFormations_Init::database()->exec("
            UPDATE
                `personnes`
            SET
                motdepasse = ".IpmFormations_Init::database()->quote( md5( $username.$newpassword ) )."
            WHERE
                utilisateur = ".IpmFormations_Init::database()->quote( $username )."
        ");
    }
    
    public static function deleteResetAccountRequests( $username )
    {
        // delete old reset keys
        return IpmFormations_Init::database()->exec("
            DELETE FROM
                `auth_reset_account`
            WHERE
                utilisateur = ".IpmFormations_Init::database()->quote( $username )."
        ");
    }
}