<?php  // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Class to manipulate Urls
 *
 * @version     2.1
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */

class Url
{
    protected $url = array(
        'scheme' => '',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '',
        'query' => array(),
        'fragment' => ''
    );
    

    /**
     * Constructor
     * @param   string url base url (use PHP_SELF if missing)
     */
    public function __construct( $url = '' )
    {
        $url = empty($url)
            ? $_SERVER['PHP_SELF']
            : $url
            ;
            
        $url = htmlspecialchars_decode( $url );
        
        $urlArr = @parse_url( $url );
        
        $queryArr = array();
        
        if ( !empty($urlArr['query']) )
        {
            @parse_str($urlArr['query'], $queryArr );
        }
        
        unset ($urlArr['query']);
        
        $this->url = array_merge( $this->url, $urlArr );
        $this->url['query'] = $queryArr;
    }
    
    public function __get( $name )
    {
        if ( isset( $this->url[$name] ) )
        {
            return $this->url[$name];
        }
        else
        {
            return null;
        }
    }
    
    public function __set( $name, $value )
    {
        if ( isset( $this->url[$name] ) )
        {
            $this->url[$name] = $value;
        }
    }
    
    public function getParam( $name )
    {
        if ( isset( $this->url['query'][$name] ) )
        {
            return $this->url['query'][$name];
        }
        
        return null;
    }

    /**
     * Add a list of parameters to the current url
     * @param   array $paramList associative array of parameters name=>value
     * @param   boolean $overwrite will overwrite the value of an existing
     *  parameter if set to true
     * @return $this
     */
    public function addParamList( $paramList, $overwrite = false )
    {
        if ( !empty( $paramList ) && is_array( $paramList ) )
        {
            foreach ( $paramList as $name => $value )
            {
                if ( !$overwrite && !empty( $value ) )
                {
                    $this->addParam( $name, $value );
                }
                elseif ( $overwrite )
                {
                    $this->replaceParam( $name, $value, true );
                }
            }
        }

        return $this;
    }

    /**
     * Add one parameter to the current url
     * @param   string $name parameter name
     * @param   string $value parameter value
     * @return $this
     */
    public function addParam( $name, $value )
    {
        if ( !array_key_exists($name, $this->url['query'] ) )
        {
            $this->url['query'][$name] = $value;
        }

        return $this;
    }

    /**
     * Replace the value of the given parameter with the given value
     * @param   string $name parameter name
     * @param   string $value parameter value
     * @param   boolean $addIfMissing add the parameter if missing (default false)
     * @return  $this
     * @throws  Exception if trying to modify a non existent parameter with
     *  $addIfMissing set to false (default)
     */
    public function replaceParam( $name, $value, $addIfMissing = false )
    {
        if ( $addIfMissing || array_key_exists( $name, $this->url['query'] ) )
        {
            $this->addParam( $name, $value );
            return $this;
        }
        else
        {
            throw new Exception("Cannot replace parameter {$name} : not found");
        }
    }

    /**
     * Remove the given parameter
     * @param   string $name parameter name
     * @param   boolean $ignoreMissing if set to true, the method invokation
     *  will ignore a missing parameter. If set to false (default) removing a
     *  non existent parameter will generate an exception
     * @return  $this
     * @throws  Exception if trying to remove a non existent parameter with
     *  $ignoreMissing set to false (default)
     */
    public function removeParam( $name, $ignoreMissing = false )
    {
        if ( array_key_exists( $name, $this->url['query'] ) )
        {
            unset( $this->url['query'] );

            return $this;
        }
        elseif ( $ignoreMissing)
        {
            return $this;
        }
        else
        {
            throw new Exception("Cannot remove parameter {$name} : not found");
        }
    }

    /**
     * Convert the current Url object to an URL string
     * @return string
     */
    public function toUrl()
    {
        return
            $this->getBaseUrl().
            $this->getPath().
            ($this->hasQueryString() ? '?'.$this->getQueryString() : '' ).
            $this->getFragment();
    }
    
    public function hasQueryString()
    {
        if ( count( $this->getRequestParameters() ) > 0 )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function getBaseUrl()
    {
        $url = '';
        
        if ( !empty($this->url['scheme']) )
        {
            if ( $this->url['scheme'] != 'mailto' )
            {
                $url .= $this->url['scheme'] . '://';
            }
            else
            {
                $url .= $this->url['scheme'] . ':';
            }
        }
        
        if ( !empty( $this->url['user'] ) )
        {
            $url .= $this->url['user'];
            
            if ( !empty( $this->url['pass'] ) )
            {
                $url .= ":{$this->url['pass']}";
            }
            
            $url .= '@';
        }
        
        if ( !empty ( $this->url['host']))
        {
            $url .= $this->url['host'];
        }
        
        if ( !empty ( $this->url['port']))
        {
            $url .= ':'.$this->url['port'];
        }
        
        return $url;
    }
    
    public function getPath()
    {
        if ( !empty ( $this->url['path']))
        {
            return $this->url['path'];
        }
        else
        {
            return '/';
        }
    }
    
    public function getRequestParameters()
    {
        if ( !empty($this->url['query']) )
        {
            return $this->url['query'];
        }
        else
        {
            return array();
        }
    }
    
    public function getQueryString()
    {
        if ( !empty($this->url['query']) )
        {
            return http_build_query( $this->url['query'] );
        }
        else
        {
            return '';
        }
    }
    
    public function getFragment()
    {
        if ( !empty ( $this->url['fragment']))
        {
            return '#' . $this->url['fragment'];
        }
        else
        {
            return '';
        }
    }

    /**
     * @since   Claroline 1.9
     * @return  string
     */
    public function  __toString()
    {
        return $this->toUrl();
    }

    /**
     * Build an url from sctract
     * @param string $url
     * @param array $params
     * @param array $context
     * @return Url
     */
    public static function buildUrl( $url, $params = null )
    {
        $urlObj = new self($url);
        $urlObj->addParamList($params);
        
        return $urlObj;
    }
}

