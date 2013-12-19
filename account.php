<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Account managment page (at this time it only allows to reset password)
 *
 * @todo        add profile edition + merge with creer.php
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

try
{
    require dirname(__FILE__).'/init.php';
    
    $action = IpmFormations_Init::input()->get('action', null);
    
    if ( $action == 'askreset' )
    {
        $email = IpmFormations_Init::input()->get('email', null);
        $username = IpmFormations_Init::input()->get('username', null);
        
        if ( $username )
        {
            $accounts = IpmFormations_AccountManager::getAccountsByUsername($username);
        }
        elseif ( $email )
        {
            $accounts = IpmFormations_AccountManager::getAccountsByEMail($email);
        }
        else
        {
            throw new Exception("Email ou identifiant manquant");
        }
        
        if ( count( $accounts ) )
        {
            $accounts->setFetchMode(Database_ResultSet::FETCH_OBJECT);
            
            // foreach account
            foreach ( $accounts as $account )
            {
                // add entry to reset account table and generate resetKey (store key + date)
                $date = mktime();
                $resetkey = UUID::generate( UUID::UUID_TIME, UUID::FMT_STRING );
                   
                IpmFormations_AccountManager::registerResetAccountRequest( $account, $resetkey, $date );
                
                // send link (with resetKey) to user email or error if missing
                send_account_reset_notification( $account->prenom, $account->nom, $account->email, $resetkey, date('d/m/Y H:i',$date+6*3600));
            }
            
            $tpl = new PhpTemplate( TEMPLATE_PATH . '/account.reset.mailsent.tpl.php');
        }
        else
        {
            throw new Exception("Aucun compte trouvé");
        }
    }
    elseif ( $action == 'reset' || $action == 'doreset' )
    {
        // get unique key
        $givenkey = IpmFormations_Init::input()->getMAndatory('resetkey');
        
        $resetinfo = IpmFormations_AccountManager::getAccountResetInformation($givenkey);
        
        if ( $resetinfo )
        {
            // check key validity
            if ( mktime() > (int) $resetinfo->unixtimestamp )
            {
                throw new Exception("Votre clé de réinitialisation de compte a expiré.");
            }
            
            $username = ltrim($resetinfo->utilisateur,'ipm_');
            
            if ( $action == 'reset' )
            {
                $message = "Réinitalisation du mot de passe de l'utilisateur <strong>{$username}</strong> &lt;{$resetinfo->email}&gt;";
                
                $tpl = new PhpTemplate(TEMPLATE_PATH.'/account.reset.form.tpl.php');
                $tpl->assign('message', $message);
                $tpl->assign('resetkey', $givenkey);
            }
            else
            {
                $newpassword = IpmFormations_Init::input()->getMandatory('newpassword');
                $confirmpassword = IpmFormations_Init::input()->getMandatory('confirmpassword');
                
                if ( $newpassword != $confirmpassword )
                {
                    // display error message
                    $message = "Réinitalisation du mot de passe de l'utilisateur {$username} &lt;{$resetinfo->email}&gt;";
                    $message .= "Vous n'avez pas entré deux fois le même mot de passe";
                    
                    $tpl = new PhpTemplate(TEMPLATE_PATH.'/account.reset.form.tpl.php');
                    $tpl->assign('message', $message);
                    $tpl->assign('resetkey', $givenkey);
                }
                else
                {
                    IpmFormations_AccountManager::changeUserPassword( $resetinfo->utilisateur,$newpassword );
                    
                    IpmFormations_AccountManager::deleteResetAccountRequests( $resetinfo->utilisateur );
                    
                    $message = 'Votre mot de passe a été modifié avec succès.';
                    
                    $tpl = new PhpTemplate(TEMPLATE_PATH.'/account.reset.ok.tpl.php');
                    $tpl->assign('message', $message);
                }
            }
        }
        else
        {
            throw new Exception("Aucun compte trouvé pour cette clé");
        }
    }
    else
    {
        $tpl = new PhpTemplate(TEMPLATE_PATH.'/account.reset.ask.tpl.php');
    }
    
    header( 'Content-type: text/html; charset=utf-8' );
    echo $tpl->render();
}
catch (Exception $e)
{
    $errorTpl = new PhpTemplate(TEMPLATE_PATH.'/error.tpl.php');
    $errorTpl->assign('errorMessage', $e->getMessage());
    $errorTpl->assign('errorDump', $e->__toString());
    
    echo $errorTpl->render();
    
    $log = new Log_ErrorLog;
    $log->log( $e->__toString() );
}

