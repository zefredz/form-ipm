<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Workaround methods for various issues with the LDAP data from UCL
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class UclUtils
{
    public static function uclMail( $mail )
    {
        if ( is_array( $mail ) ) 
        {   
            if ( isset($mail['count']) )
            {
                unset ($mail['count']);
            }

            foreach ( $mail as $address )
            {
                $retmail = $address;

                if ( preg_match('/@uclouvain.be/', $address ) ) 
                {
                    return $address;
                }

                if ( preg_match('/@student.uclouvain.be/', $address ) ) 
                {
                    return $address;
                }
            }

            return $retmail;
        }   
        else
        {   
            return $mail;
        }   
    }
}

