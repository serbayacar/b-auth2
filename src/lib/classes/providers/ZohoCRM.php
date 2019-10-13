<?php

namespace interfaces;

class ZohoCRM implements Oauth2Interface {

    private $clients;

    private $apiURL;
    private $authURL;
    private $tokenURL;
    private $redirectURL;

    private  $scopes;

    public function setURLs( array $urls ){

        // TODO :: check required settings
        $this->apiURL = $urls['apiURL'];
        $this->authURL = $urls['authURL'];
        $this->tokenURL = $urls['tokenURL'];
        $this->redirectURL = $urls['redirectURL'];

        return $this;
    }

    public function setScopes( $scopes ){
        $this->scopes = $scopes;

        return $this;
    }

    public function setClient( array $config ){
        $this->clients = $config;

        return $this;
    }

    public function getAuthenticationURL(){
        $query = ['client_id' => $this->clients['client_id'],
                  'redirect_uri' => $this->redirectURL ,
                  'response_type' => 'code' ,
                  'scopes' => implode(',',$this->scopes),
                  'access_type' => 'offline'
                 ];

        return $this->authURL . '?' . http_build_query($query);
    }

    public function getAccessToken($code){
        $query = ['grant_type' => 'autherization_code' ,
                  'client_id' => $this->clients['client_id'],
                  'client_secret' => $this->clients['client_secret'],
                  'redirect_uri' => $this->redirectURL,
                  'code' => $code
                 ];

        $client = $this->getHTTPClient();
        $request = $client->post($this->tokenURL . '?' . http_build_query($query));
        return $request;
    }

    public function getRefreshToken(){
        $query = [ 'grant_type' => 'refresh_code' , 
                   'refresh_token' => $this->clients['refreshToken'] ,
                   'client_id' => $this->clients['client_id'],
                   'client_secret' => $this->clients['client_secret'],
                 ];

        $client = $this->getHTTPClient();
        $request = $client->post($this->tokenURL . '?' . http_build_query($query));
        return $request;
    }

    public function getHTTPClient(){
        $httpClient = new HTTPClient();
        return $httpClient;
    }

}