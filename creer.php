<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Create account page
 *
 * @todo        merge with account.php
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
    
    $displayForm = true;
    $displaySuccess = false;
    $serverMessage = '';
    $postedData = array();
    
    $typesInstitution = array(
        'AUT' => "Autre",
        'LOU' => "Acad&eacute;mie Louvain",
        'UNI' => "Autre universit&eacute;",
        'HEC' => "Haute &Eacute;cole",
        'ONG' => "ONG ou Associatif",
        'ENT' => "Entreprise"
    );
    
    if ( $action == 'creer' )
    {
        // var_dump( $_POST );
        
        $postedData = array();
        $validationErrors = array();
        
        try
        {
            $postedData['utilisateur'] = trim(IpmFormations_Init::input()->getMandatory('utilisateur'));
        }
        catch (Exception $e)
        {
            $validationErrors['utilisateur'] = "Vous devez spécifier un identifiant";
        }
        
        try
        {
            $postedData['mail_contact'] = trim(IpmFormations_Init::input()->getMandatory('mail_contact'));
            
            if ( !preg_match( '/[a-zA-Z0-9\-_\.]+@[a-zA-Z0-9\-_]+(.[a-zA-Z0-9\-_])+/', $postedData['mail_contact'] ) )
            {
                $validationErrors['mail_contact'] = "Le format de l'adresse email doit être du type utilisateur@exemple.be";
            }
        }
        catch (Exception $e)
        {
            $validationErrors['mail_contact'] = "Vous devez spécifier une adresse email de contact";
        }
        
        try
        {
            $postedData['motdepasse'] = trim(IpmFormations_Init::input()->getMandatory('motdepasse'));
        }
        catch (Exception $e)
        {
            $validationErrors['motdepasse'] = "Vous devez spécifier un mot de passe";
        }
        
        try
        {
            $postedData['motdepasse_confirme'] = trim(IpmFormations_Init::input()->getMandatory('motdepasse_confirme'));
            
            if ( $postedData['motdepasse'] != $postedData['motdepasse_confirme'] )
            {
                $validationErrors['motdepasse_confirme'] = "La confirmation de votre mot de passe ne correspond pas";
            }
        }
        catch (Exception $e)
        {
            $validationErrors['motdepasse_confirme'] = "Vous devez confirmer votre mot de passe";
        }
        
        try
        {
            $postedData['nom'] = trim(IpmFormations_Init::input()->getMandatory('nom'));
        }
        catch (Exception $e)
        {
            $validationErrors['nom'] = "Vous devez spécifier votre nom";
        }
        
        try
        {
            $postedData['prenom'] = trim(IpmFormations_Init::input()->getMandatory('prenom'));
        }
        catch (Exception $e)
        {
            $validationErrors['prenom'] = "Vous devez spécifier votre prénom";
        }
        
        try
        {
            $postedData['institution'] = trim(IpmFormations_Init::input()->getMandatory('institution'));
        }
        catch (Exception $e)
        {
            $validationErrors['institution'] = "Vous devez spécifier votre institution";
        }
        
        try
        {
            IpmFormations_Init::input()->setValidator( 'type_institution', new Validator_AllowedList( array_keys( $typesInstitution ) ) );
            $postedData['type_institution'] = trim(IpmFormations_Init::input()->getMandatory('type_institution'));
        }
        catch (Exception $e)
        {
            $validationErrors['type_institution'] = "Vous devez spécifier votre type d'institution";
        }
        
        try
        {
            $postedData['faculte'] = trim(IpmFormations_Init::input()->getMandatory('faculte'));
        }
        catch (Exception $e)
        {
            $validationErrors['faculte'] = "Vous devez spécifier votre faculté ou section";
        }

        $postedData['departement'] = trim(IpmFormations_Init::input()->get('departement'));
        $postedData['unite'] = trim(IpmFormations_Init::input()->get('unite'));
        $postedData['adresse'] = trim(IpmFormations_Init::input()->get('adresse'));
        $postedData['fax'] = trim(IpmFormations_Init::input()->get('fax'));
        $postedData['telephone'] = trim(IpmFormations_Init::input()->get('telephone'));
        $postedData['mail_travail'] = trim(IpmFormations_Init::input()->get('mail_travail'));
        $postedData['conf_humain'] = trim(IpmFormations_Init::input()->get('conf_humain'));
        
        if ( ! empty($postedData['conf_humain']) )
        {
            $validationErrors['conf_humain'] = "Vous devez décocher la case en bas du formulaire afin de prouver que vous êtes un humain";
        }
        
        if ( empty( $validationErrors ) )
        {
            // register user
            // motdepasse = md5(utilisateur.motdepasse)
            
            $localUser = $postedData;
            unset ( $localUser['motdepasse_confirme'], $localUser['conf_humain'] );
            
            if ( substr( $localUser['utilisateur'], 0, 4 ) != 'ipm-' )
            {
                $localUser['utilisateur'] = 'ipm_'.$localUser['utilisateur'];
            }
            
            if ( ! IpmFormations_UserList::userAlreadyInDatabase( $localUser['utilisateur'] ) )
            {
                IpmFormations_UserList::registerLocalUser( $localUser );
                
                $displayForm = false;
                $displaySuccess = true;
            }
            else
            {
                $displayForm = true;
                $serverMessage = '<span class="error">'."Un utilisateur existe déjà pour cet identifiant".'</span>';
            }
            
        }
        else
        {
            $displayForm = true;
            $serverMessage = '<span class="error">'.implode( '</span><br /><span class="error">', $validationErrors ).'</span>';
        }
    }
    
    $creerTpl = new PhpTemplate(TEMPLATE_PATH.'/creercompte.tpl.php');
    
    $creerTpl->assign('displayForm', $displayForm);
    $creerTpl->assign('displaySuccess',$displaySuccess);
    
    $creerTpl->assign('typesInstitution', $typesInstitution );
    
    $creerTpl->assign('serverMessage',$serverMessage);
    $creerTpl->assign('postedData', $postedData);
    
    header( 'Content-type: text/html; charset=utf-8' );
    echo $creerTpl->render();
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

