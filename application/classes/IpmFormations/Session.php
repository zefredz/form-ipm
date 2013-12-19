<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Session class
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class IpmFormations_Session
{
    const TIME_24_HOURS = 86400;
     
    private $session;
    
    public function __construct( $sessionXml )
    {
        $this->session = $sessionXml;
    }
    
    public function hasSessionDate()
    {
        return !empty( $this->session->date );
    }
    
    public function getSessionDate()
    {
        if ( $this->hasSessionDate() )
        {
            return IpmFormations_SessionDate::sessionTime( $this->session );
        }
        else
        {
            return null;
        }
    }
    
    public function hasRegistrationEndDate()
    {
        return !empty( $this->session->inscription_fin );
    }
    
    public function getRegistrationEndDate()
    {
        if ( $this->hasRegistrationEndDate() )
        {
            return strtotime( $this->session->inscription_fin );
        }
        else
        {
            return null;
        }
    }
    
    public function isRegistrationDateExceeded()
    {
        return $this->isRegistrationDateExpiringInTheNext( 0 );
    }
    
    public function isRegistrationDateExpiringInTheNext( $seconds = self::TIME_24_HOURS )
    {
        if ( $this->hasRegistrationEndDate() )
        {
            $dateLimit = $this->getRegistrationEndDate();
        }
        elseif ( $this->hasSessionDate() )
        {
            $dateLimit = $this->getSessionDate();
        }
        else
        {
            return false;
        }
        
        return ( $dateLimit + $seconds ) < time();
    }
    
    public function isOndemandSession()
    {
        return ! $this->hasSessionDate() && $this->session->Formation->related_record->type_de_formation['value'] == 4;
    }
    
    public function noMoreSeatsAvailable()
    {
        return $this->getAvailableSeats() <= 0;
    }
    
    public function getMaximumNumberOfSeats()
    {
        if ( !empty( $this->session->nombre_places ) )
        {
            return $this->session->nombre_place;
        }
        else
        {
            return PHP_INT_MAX;
        }
    }
    
    public function getAvailableSeats( $confirmedOnly = true )
    {
        if ( !empty( $this->session->nombre_places ) )
        {
            return $this->session->nombre_place 
                - IpmFormations_SessionRegistration::countRegistrations( $this->session->id, true, $confirmedOnly, true );
        }
        else
        {
            return PHP_INT_MAX;
        }
    }
    
    public function isCancelled()
    {
        return $this->session->annulee['value'] == 'oui'
            || $this->session->Formation->related_record->annulee['value'] == 'oui';
    }
    
    public function externalRegistration()
    {
        return $this->session->inscription_ouverte['value'] == 'externe';
    }
    
    public function registrationNotOpenYet()
    {
        return !empty( $this->session->inscription_debut ) && strtotime( $this->session->inscription_debut ) > time();
    }
    
    public function registrationNotAvailable()
    {
        return ( empty($this->session->date) && $this->session->Formation->related_record->type_de_formation['value'] != 4 ) 
            || $this->session->inscription_ouverte['value'] != 'oui';
    }
}
