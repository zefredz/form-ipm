<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Session Date formatting class
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class IpmFormations_SessionDate
{
    protected $date, $horaire, $time;
    
    public function __construct ( $date, $horaire = null )
    {
        $this->date = $date;
        $this->horaire = $horaire;
        $this->time = self::parseHoraire( $horaire );
    }
    
    public static function parseHoraire( $horaire )
    {
        if ( empty( $horaire) )
        {
            return '00:00';
        }
        else
        {
            $time = preg_split( '/\s*(à|-)\s*/', $horaire );
            
            if ( empty( $time ) )
            {
                return '00:00';
            }
            
            $time = trim( $time[0] );
            
            if ( preg_match( '/h$/', $time) )
            {
                $time = str_replace( 'h', ':00', $time );
            }
            else
            {
                $time = str_replace( 'h', ':', $time );
            }
            
            if ( ! preg_match( '/\d{1,2}:\d{2}/', $time ) )
            {
                $time = '00:00';
            }
            
            return $time;
        }
    }
    
    public function toTime()
    {
        if ( empty( $this->date ) )
        {
            return 0;
        }
        else
        {
            return strtotime( $this->date . ' ' . $this->time );
        }
    }
    
    public function __toString()
    {
        return $this->toTime();
    }
    
    public static function sessionTime( $session )
    {
        $sessionTime = new self( $session->date, !empty($session->horaire) ? $session->horaire : null );
        
        return $sessionTime->toTime();
    }
}
