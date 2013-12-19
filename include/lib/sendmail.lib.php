<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Sendmail helpers
 *
 * @version     1.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

function send_mail_to_user( $frommail, $fromname, $recipient, $email, $subject, $body )
{
    $mail = Swift_Message::newInstance()->setCharset('utf-8');
    $mail->setBody( $body, 'text/html' );
    $mail->setSubject( $subject );
    $mail->setFrom( array( $frommail => $fromname ) );
    $mail->setTo( array( $email => $recipient ) );
    
    $mailer = Swift_Mailer::newInstance( Swift_SmtpTransport::newInstance( SMTP_HOST, SMTP_PORT ) );
    
    if ( ! $mailer->send( $mail, $failures ) )
    {
        throw new Exception( var_export( $failures, true ) );
    }
}

function send_application_notification( $recipient, $email, $subject, $body )
{
    return send_mail_to_user( NOTIFICATION_CONTACT_MAIL, NOTIFICATION_CONTACT_NAME, $recipient, $email, $subject, $body );
}

function send_account_reset_notification( $firstname, $lastname, $email, $resetkey, $date )
{
    $subject = '[Formations IPM] Modification de votre mot de passe';
    
    $bodyTpl = new PhpTemplate( TEMPLATE_PATH . '/resetemail.tpl.php' );
    $bodyTpl->assign( 'resetkey', $resetkey );
    $bodyTpl->assign( 'date', $date );
    
    return send_application_notification( "{$firstname} {$lastname}", $email, $subject, $bodyTpl->render() );
}

function send_ondemand_formation_registration_request( $session, $user )
{
    $subject = "[Formations IPM] Inscription formation à la demande";
    $bodyTpl = new PhpTemplate( TEMPLATE_PATH . '/ondemandregemail.tpl.php' );
    $bodyTpl->assign( 'session', $session );
    $bodyTpl->assign( 'user', $user );
    
    // notify activity specific contact if any
    if ( ! empty($session->Formation->related_record->email_personne_de_contact) )
    {
        // var_dump( __FILE__.__LINE__ );
        
        $recipientMail = $session->Formation->related_record->email_personne_de_contact;
        
        if ( !empty( $session->Formation->related_record->nom_personne_de_contact ) )
        {
            $recipient = $session->Formation->related_record->nom_personne_de_contact;
        }
        else
        {
            $recipient = "Personne de contact";
        }

        send_application_notification( $recipient, $recipientMail, $subject, $bodyTpl->render() );
    }
    else
    {
        // no activity specific contact to notify
    }
    
    // notify training administrator
    send_application_notification( APPLICATION_CONTACT_NAME, APPLICATION_CONTACT_MAIL, $subject, $bodyTpl->render() );
    // notify training manager
    send_application_notification( FORMATION_CONTACT_NAME, FORMATION_CONTACT_MAIL, $subject, $bodyTpl->render() );
}

