<?php

class PiwikApiClient
{
    protected $serverUrl, $authToken, $siteId;
    
    public function __construct( $serverUrl, $siteId, $authToken )
    {
        $this->serverUrl = $serverUrl;
        $this->authToken = $authToken;
        $this->siteId = $siteId;
    }
    
    protected function buildBaseUrl()
    {
        return rtrim( $this->serverUrl, '/' ) . '/index.php?module=API&token_auth='.$this->authToken.'&idSite='.$this->siteId;
    }
    
    public function request( $method, $params = array() )
    {
        $url = $this->buildBaseUrl().'&method='.$method.'&'.http_build_query($params);
        
        $response = $this->getUrl( $url );

        // var_dump( $response );
        
        try
        {
        $xml = simplexml_load_string( $response );
        }
        catch (Exception $e )
        {
            error_log($e->getMessage().':'.$response."\n");
        }

        if ( !empty($xml->error) )
        {
            throw new Exception( $xml->error['message']);
        }
    }
    
    protected function getUrl( $url )
    {
        $ch = curl_init();
        
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        
        if ( isset( $_SERVER['HTTP_REFERER'] ) )
        {
            curl_setopt( $ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER'] );
        }
        else
        {
            curl_setopt( $ch, CURLOPT_REFERER, INDEX_URL );
        }
        
        if ( isset( $_SERVER['HTTP_USER_AGENT'] ) )
        {
            curl_setopt( $ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
        }
        else
        {
            curl_setopt( $ch, CURLOPT_USERAGENT, 'PHP/PiwikApiClient v0.1' );
        }
        
        
        $response = curl_exec( $ch );
        
        curl_close( $ch );
        
        return $response;
    }
}
