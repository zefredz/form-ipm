<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Time utility class
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class Time_Utils
{
    public static function isIso8601( $dateStr )
    {
        return preg_match( '/\d{4}-\d{2}-\d{2}T\d{2}\:\d{2}\:\d{2}\+\d{2}\:\d{2}/i', $dateStr );
    }
    
    public static function iso8601ToDatetime( $iso8601Str )
    {
        if ( ! self::isIso8601( $iso8601Str ) )
        {
            return false;
        }
        
        return preg_replace( '/(\d{4}-\d{2}-\d{2})T(\d{2}\:\d{2}\:\d{2})\+\d{2}\:\d{2}/i', "$1 $2", $iso8601Str ) ;
    }
    
    public static function timeToIso8601( $time = null )
    {
        if ( is_null( $time ) ) $time = time();

        return date('Y-m-d\TH:i:s+00:00',$time);
        //return (date('c') == 'c') ? date('Y-m-d\TH:i:s+1',$time) : date('c', $time );
    }

    public static function dateToIso8601( $date = null )
    {
        $time = is_null( $date )
            ? time()
            : strtotime( $date )
            ;
        
        return self::timeToIso8601( $time );
    }

    public static function timeToDatetime( $time = null )
    {
        if ( $time )
        {
            return date( "Y-m-d H:i:s", $time );
        }
        else
        {
            return date( "Y-m-d H:i:s" );
        }
    }

    public static function dateToDatetime( $date = null)
    {
        $time = is_null( $date )
            ? time()
            : strtotime( $date )
            ;

        return date('Y-m-d H:i:s',$time);
    }
}

