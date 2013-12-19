<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Login page
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

try
{
    require dirname(__FILE__).'/init.php';
    
    IpmFormations_Init::input()->setValidator('action', new Validator_AllowedList( array('register','login','logout','rqLogin') ) );
    
    $uclGiven = ! is_null( IpmFormations_Init::input()->get( 'ucl', null ) );
    
    try
    {
        $action = IpmFormations_Init::input()->getMandatory('action');
    }
    catch ( Exception $e )
    {
        $action = null;
    }
    
    if ( !is_null( $action ) )
    {
        if ( $action == 'logout' )
        {
            $id = IpmFormations_Init::input()->get( 'formation_id', null );
                
            if ( is_null($id) )
            {
                $nextUrl = INDEX_URL;
            }
            else
            {
                $nextUrl = DETAILS_URL.'?formation_id='.$id;
            }
            
            // reinit session
            session_destroy();
            session_start();
            
            header("Location:".$nextUrl);
            exit();
        }
        else
        {
            $authSuccess        = false;
            $registerSuccess    = false;
            
            $isUCL = ( (int) IpmFormations_Init::input()->get( 'ucl', 1 ) == 1 );
            
            if ( $isUCL )
            {

                $uid = trim( IpmFormations_Init::input()->get( 'login', null ) );
                
                $password = trim( IpmFormations_Init::input()->get( 'password', null ) );
                

                $ldapUser = IpmFormations_Init::ldap()->getUser( $uid );
                
                if ( $ldapUser )
                {
                    $authSuccess = IpmFormations_Init::ldap()->authenticate( $ldapUser->getDn(), $password );
                    
                    if ( ! $authSuccess )
                    {
                        throw new Exception('Aunthentication failed!');
                    }
                    
                    $ldapUser = IpmFormations_Init::ldap()->getUser( $uid );
                    
                    $uid = $ldapUser->getUid();

                    if ( !IpmFormations_UserList::uclUserAlreadyInDatabase( $ldapUser->getUid() ) )
                    {
                        
                        try
                        {
                            IpmFormations_UserList::registerLdapUser( $ldapUser );
                        }
                        catch (Exception $e)
                        {
                            // this happens when trying to add an account that already exists in the database...
                            // log the exception and continue !
                            error_log('['.date('c').']'.":\n".$e->__toString());
                        }
                    }
                    else
                    {
                        try
                        {
                            IpmFormations_UserList::syncLdapUser( $ldapUser );
                        }
                        catch (Exception $e)
                        {
                            // this happens when trying to add an account that already exists in the database...
                            // log the exception and continue !
                            error_log('['.date('c').']'.":\n".$e->__toString());
                        }
                    }
                }
                
            }
            else
            {
                
                // var_dump(__LINE__);
                $uid = trim( IpmFormations_Init::input()->get( 'login', null ) );
                
                if ( substr( $uid, 0, 4 ) != 'ipm_' )
                {
                    $uid = 'ipm_'.$uid;
                }

                // var_dump($uid);
                
                $password = trim( IpmFormations_Init::input()->get( 'password', null ) );
                
                $authSuccess = IpmFormations_UserList::authenticate( $uid, $password );
            }

            // var_dump( $authSuccess );
            
            if ( $authSuccess)
            {
                $_SESSION['user'] = IpmFormations_UserList::getUser( $uid );
            }
            else
            {
                $action = 'loginerror';
            }
        }
    }
    
    if ( is_null( $action ) && ! $uclGiven )
    {
        $loginTpl = new PhpTemplate(TEMPLATE_PATH.'/loginoptions.tpl.php');
        $loginTpl->assign('formationId', IpmFormations_Init::input()->get( 'formation_id', null ));
        $loginTpl->assign('sessionId', IpmFormations_Init::input()->get( 'session_id', null ));
    }
    elseif ( $action == 'loginerror' )
    {
        $loginTpl = new PhpTemplate(TEMPLATE_PATH.'/loginerror.tpl.php');
    }
    else
    {
        $loginTpl = new PhpTemplate( TEMPLATE_PATH.'/login.tpl.php' );
        
        if ( IpmFormations_Init::user() )
        {
            $loginTpl->assign('userAuthenticated', true);
            
            $formationId = IpmFormations_Init::input()->get( 'formation_id', null );
            $sessionId = IpmFormations_Init::input()->get( 'session_id', null );
                
            if ( ! is_null($sessionId) )
            {
                $nextUrl = REGISTER_URL.'?session_id='.$sessionId;
            }
            elseif ( !is_null($formationId) )
            {
                $nextUrl = DETAILS_URL.'?formation_id='.$formationId;
            }
            else
            {
                $nextUrl = INDEX_URL;
            }
            
            header("Location:".$nextUrl);
            exit();
        }
        else
        {
            $loginTpl->assign('userAuthenticated', false);
            $loginTpl->assign('formationId', IpmFormations_Init::input()->get( 'formation_id', null ));
            $loginTpl->assign('sessionId', IpmFormations_Init::input()->get( 'session_id', null ));
            $loginTpl->assign('ucl', IpmFormations_Init::input()->get( 'ucl', null ));
        }
    }
    
    header( 'Content-type: text/html; charset=utf-8' );
    
    echo $loginTpl->render();

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

