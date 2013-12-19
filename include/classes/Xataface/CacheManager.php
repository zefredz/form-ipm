<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Cache management helpers
 *
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class Xataface_CacheManager
{
    /**
     * Retrieve and cache XML from Xataface
     * @param string $sourceUrl query URL
     * @param string $destinationPath path of the cached data
     * @param bool $force set to true to force the refresh
     * @return void
     */
    public static function refreshXmlCacheFromXataface( Xataface_Query $query, $destinationPath, $force = false )
    {
        if ( $force || ! file_exists( $destinationPath ) /* || ( filemtime($destinationPath) + 3600 * 2 ) < time() */  )
        {
            file_put_contents( $destinationPath, $query->getXmlResponse() );
        }
    }
}
