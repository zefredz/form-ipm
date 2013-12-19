<?php

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * Session information and registration page
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

try
{
    require dirname(__FILE__).'/init.php';
    
    $sessionId = (int) IpmFormations_Init::input()->getMandatory('session_id');
    
    $action = IpmFormations_Init::input()->get('action', null);
    
    $xmlCalendar = simplexml_load_file(XML_CACHE.'/sessions.xml');
    
    $registrationStatus = 'requested';
    
    $sessions = $xmlCalendar->xpath("//sessions[id='".$sessionId."']");
    
    //var_dump($sessions);
    if ( ! count($sessions) )
    {
        $xmlCalendar = simplexml_load_string( IpmFormations_XatafaceQueries::getSessionXmlQuery( $sessionId )->getXmlResponse() );
        $sessions = $xmlCalendar->xpath("//sessions[id='".$sessionId."']");
        
        if ( ! count($sessions) )
        {
            throw new Exception('Impossible de trouver cette session');
        }
    }

    $session = $sessions[0];
    
    $failureMessage = '';

    if ( !empty( $session ) )
    {

        if ( $action == 'sinscrire' )
        {
            $registrationStatus = 'failure';
            $failureMessage = "Une erreur s'est produite, veuillez essayer de nouveau plus tard.";
            
            $user = IpmFormations_Init::user();
        
            if ( ! $user || ! $user['id'] )
            {
                header("Location:".LOGIN_URL.'?session_id='.$sessionId);
                exit();
            }

            if ( $session->inscription_ouverte['value'] == 'externe' )
            {
                $registrationStatus = 'failure';
                $failureMessage = 'Les inscriptions pour cette formation se font sur un autre site (voir ci-dessous)';
            }
            elseif ( ( empty($session->date) && $session->Formation->related_record->type_de_formation['value'] != 4 ) 
                || $session->inscription_ouverte['value'] != 'oui' )
            {
                $registrationStatus = 'failure';
                $failureMessage = 'Les inscriptions pour cette date ne sont pas ouvertes';
            }
            elseif ( !empty($session->date) && ( IpmFormations_SessionDate::sessionTime( $session ) - 3600 * 24) < time() 
                && ( empty( $session->inscription_fin ) || strtotime( $session->inscription_fin ) - 3600 * 24 < time() ) )
            {
                $registrationDateInLessThan24Hours = false;
                
                if ( ( IpmFormations_SessionDate::sessionTime( $session ) - 3600 * 24) < time() 
                        ||  ( !empty( $session->inscription_fin ) && strtotime( $session->inscription_fin ) - 3600 * 24 < time() ) )
                {
                    $registrationDateInLessThan24Hours = true;
                }
                
                $registrationStatus = 'failure';
                
                if ( !$registrationDateInLessThan24Hours )
                {
                    $failureMessage = 'Les inscriptions pour cette date sont cloturées';
                }
                else
                {
                    if ( !empty( $session->Formation->related_record->nom_personne_de_contact ) )
                    {
                        $nomPersonneContact = $session->Formation->related_record->nom_personne_de_contact;
                    }
                    else
                    {
                        $nomPersonneContact = "Responsable formation IPM";
                    }


                    if ( !empty( $session->Formation->related_record->email_personne_de_contact ) )
                    {
                        $mailTo = 'mailto:'.$session->Formation->related_record->email_personne_de_contact.'?subject='.htmlspecialchars($session->Formation->related_record->titre . ' - inscription tardive').'&cc=nicole.marion@uclouvain.be;nathalie.kruyts@uclouvain.be';
                    }
                    else
                    {
                        $mailTo = 'mailto:nicole.marion@uclouvain.be?subject='.htmlspecialchars($session->Formation->related_record->titre . ' - inscription tardive').'&cc=nathalie.kruyts@uclouvain.be';
                    }

                    $failureMessage = 'Cette formation commence dans moins de 24h, les inscriptions en ligne ne sont donc plus possibles afin de pouvoir organiser la logistique. 
                        Vous pouvez toutefois prendre contact directement avec l\'organisateur de la formation : <a href="'.$mailTo.'">'.$nomPersonneContact.'</a>.';
                }
            }
            elseif ( $session->annulee['value'] == 'oui'
                || $session->Formation->related_record->annulee['value'] == 'oui' )
            {
                $registrationStatus = 'failure';
                $failureMessage = 'Cette date est annulée';
            }
            elseif ( !empty( $session->nombre_places )
                && IpmFormations_SessionRegistration::countRegistrations( $sessionId ) >= $session->nombre_places )
            {

                $registrationStatus = 'failure';
                $failureMessage = 'Il n\'y a plus de place libre pour cette date';
            }
            elseif ( IpmFormations_SessionRegistration::isAlreadyRegistered( (int) $user['id'], $sessionId ) )
            {
                $registrationStatus = 'already';
            }
            elseif ( ( !empty( $session->inscription_debut ) && strtotime( $session->inscription_debut ) ) > time() 
                || ( !empty( $session->inscription_fin ) && strtotime( $session->inscription_fin ) < time() ) )
            {
                $registrationStatus = 'failure';

                if ( strtotime( $session->inscription_debut ) > time() )
                {
                    $failureMessage = 'Les inscriptions pour cette date ne sont pas encore ouvertes';
                }
                else
                {
                    $failureMessage = 'Les inscriptions pour cette date sont cloturées';
                }
            }
            else
            {
                if ( ! IpmFormations_SessionRegistration::register( (int) $user['id'], $sessionId ) )
                {
                    $registrationStatus = 'failure';
                    $failureMessage = "Une erreur s'est produite, veuillez essayer de nouveau plus tard.";
                }
                else
                {
                    $registrationStatus = 'success';
                    
                    if ( $session->Formation->related_record->type_de_formation['value'] == 4 )
                    {
                        send_ondemand_formation_registration_request( $session, $user );
                    }
                }
            }
        }
        
        $user = IpmFormations_Init::user();
        
        $xmlLocaux = simplexml_load_file(XML_CACHE.'/locaux.xml');
        
        $localArr = $xmlLocaux->xpath("//locaux[id='".$session->locaux['value']."']");
        // var_dump($local);
        if (empty( $localArr ) )
        {
            $local = null;
        }
        else
        {
            $local = $localArr[0];
        }

        $displayForm = true;
        $registrationMessage = null;
        
        if ( $session->inscription_ouverte['value'] == 'externe' )
        {
            $registrationMessage = 'Les inscriptions pour cette formation se font sur un autre site (voir ci-dessous)';
            $displayForm = false;
        }
        elseif ( !empty( $session->inscription_debut ) && strtotime( $session->inscription_debut ) > time() )
        {       
            $registrationMessage = 'Les inscriptions pour cette formation ne sont pas encore ouvertes';
            $displayForm = false;
        }
        elseif ( ( empty($session->date) && $session->Formation->related_record->type_de_formation['value'] != 4 ) 
            || $session->inscription_ouverte['value'] != 'oui' )
        {
            $registrationMessage = 'Les inscriptions pour cette formation ne sont pas ouvertes';
            $displayForm = false;
        }
        elseif ( !empty( $session->date) )
        {
            $registrationDateInLessThan24Hours = false;
            $registrationDateExceeded = false;
                
            if ( ( IpmFormations_SessionDate::sessionTime( $session ) - 3600 * 24) < time() 
                    ||  ( !empty( $session->inscription_fin ) && strtotime( $session->inscription_fin ) - 3600 * 24 < time() ) )
            {
                $registrationDateInLessThan24Hours = true;
            }
            
            if ( ( IpmFormations_SessionDate::sessionTime( $session ) ) < time() 
                    ||  ( !empty( $session->inscription_fin ) && strtotime( $session->inscription_fin ) < time() ) )
            {
                $registrationDateExceeded = true;
            }      

            if ( $registrationDateExceeded && !$registrationDateInLessThan24Hours )
            {
                 $registrationMessage = 'Les inscriptions pour cette formation sont cloturées';
                 $displayForm = false;
            }
            elseif ( $registrationDateInLessThan24Hours )
            {
                if ( !empty( $session->Formation->related_record->nom_personne_de_contact ) )
                {
                    $nomPersonneContact = $session->Formation->related_record->nom_personne_de_contact;
                }
                else
                {
                    $nomPersonneContact = "Responsable formation IPM";
                }
                
                
                if ( !empty( $session->Formation->related_record->email_personne_de_contact ) )
                {
                    $mailTo = 'mailto:'.$session->Formation->related_record->email_personne_de_contact.'?subject='.htmlspecialchars($session->Formation->related_record->titre . ' - inscription tardive').'&cc=nicole.marion@uclouvain.be;nathalie.kruyts@uclouvain.be';
                }
                else
                {
                    $mailTo = 'mailto:nicole.marion@uclouvain.be?subject='.htmlspecialchars($session->Formation->related_record->titre . ' - inscription tardive').'&cc=nathalie.kruyts@uclouvain.be';
                }
                
                $registrationMessage = 'Cette formation commence dans moins de 24h, les inscriptions en ligne ne sont donc plus possibles afin de pouvoir organiser la logistique. 
                    Vous pouvez toutefois prendre contact directement avec l\'organisateur de la formation : <a href="'.$mailTo.'">'.$nomPersonneContact.'</a>.';
                $displayForm = false;
            }
        }
        
        if ( $session->annulee['value'] == 'oui'
            || $session->Formation->related_record->annulee['value'] == 'oui' )
        {
            $registrationMessage = 'Cette formation est annulée';
            $displayForm = false;
        }
        
        if ( !empty( $session->nombre_places )
            && IpmFormations_SessionRegistration::countRegistrations( $sessionId, true, true, true ) >= $session->nombre_places )
        {
            $registrationMessage = 'Il n\'y a plus de place disponible pour cette formation. Toutefois, si la demande était suffisante, une nouvelle séance pourrait être organisée. Pour le savoir, consultez régulièrement notre agenda.';
            $displayForm = false;
        }
        
        $sinscrireTpl = new PhpTemplate(TEMPLATE_PATH.'/session.tpl.php');
        $sinscrireTpl->assign( 'session', $session );
        $sinscrireTpl->assign( 'sessionId', $sessionId );
        $sinscrireTpl->assign( 'local', $local );
        $sinscrireTpl->assign( 'registrationStatus', $registrationStatus );
        $sinscrireTpl->assign( 'displayForm', $displayForm );
        $sinscrireTpl->assign( 'registrationMessage', $registrationMessage );
        $sinscrireTpl->assign( 'failureMessage', $failureMessage );
        
        if ( $user && IpmFormations_SessionRegistration::isAlreadyRegistered( (int) $user['id'], $sessionId ) )
        {
            $userRegistration = IpmFormations_SessionRegistration::getUserRegistrationInfo((int) $user['id'], $sessionId);

            $sinscrireTpl->assign( 'userAlreadyRegistered', true );
            $sinscrireTpl->assign( 'userRegistration', $userRegistration );
        }
        else
        {
            $sinscrireTpl->assign( 'userAlreadyRegistered', false );
        }
        
        $pageTpl = new IpmFormations_Page();
        
        $pageTpl->assign( 'sessionId', $sessionId );
        $pageTpl->addTab( 'sinscrire', 'Détails/Inscription', $sinscrireTpl );
        
        header( 'Content-type: text/html; charset=utf-8' );
        echo $pageTpl->render();
        
        if ( !empty($_REQUEST['debug']) )
        {
            echo '<pre>';
            var_dump($session);
            echo '</pre>';
            
            echo '<pre>';
            var_dump(IpmFormations_SessionDate::sessionTime( $session ));
            echo '<br />';
            var_dump(IpmFormations_SessionDate::sessionTime( $session ) - 86400 );
            echo '<br />';
            var_dump( time() );
            echo '</pre>';
        }
    }
    else
    {
        throw new Exception("Session not found {$sessionId}");
    }
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
