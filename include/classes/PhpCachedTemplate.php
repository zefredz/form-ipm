<?php // vim: expandtab sw=4 ts=4 sts=4:

/**
 * PHP Template with cache management
 *
 * @version     2.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
class PhpCachedTemplate extends PhpTemplate
{
    protected $cacheFolderPath, $cachedFilePath, $lastChanged, $lifeTime;
    
    public function __construct( $templatePath, $cachedFilePath, $lifeTime = null )
    {
        parent::__construct( $templatePath );
        
        $this->cachedFilePath = $cachedFilePath;
        
        $this->lifeTime = $lifeTime;
    }
    
    public function render()
    {
        if ( $this->mustRegenerateCache() )
        {
            clearstatcache();
            
            if ( file_exists($this->cachedFilePath) )
            {
                unlink( $this->cachedFilePath );
            }
            
            $content = parent::render();
            
            file_put_contents( $this->cachedFilePath, $content );
        }
        else
        {
            $content = file_get_contents( $this->cachedFilePath );
        }
        
        return $content;
    }
    
    public function mustRegenerateCache()
    {
        return (
            // the file does not exists
            !file_exists($this->cachedFilePath)
            // the file has expired
            || ( !is_null($this->lifeTime) && filectime( $this->cachedFilePath) + $this->lifeTime > time() )
            // the template has changed
            || ( filemtime($this->_templatePath) >= time() ) );
    }
}

