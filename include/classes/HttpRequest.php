<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Http Request (only GET is supported at this time)
 *
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class HttpRequest
{
    
    /**
     * Get XML data from URL
     * @return string data retrieved from the URL
     */
    public static function get( Url $url )
    {
        $contents = url_get_contents($url->toUrl());
        
        return $contents;
    }
}

