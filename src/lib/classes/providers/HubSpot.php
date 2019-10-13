<?php

include __DIR__ .'/../interfaces/Oauth2Interface.php';


class HubSpot implements Oauth2Interface {

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

    public function setScopes( array $scopes ){
        $this->scopes = $scopes;

        return $this;
    }

    public function setClient( array $config ){
        $this->clients = $config;

        return $this;
    }

    public function getAuthenticationURL(){
        $query = ['client_id' => $this->clients['client_id'],
                 'scope' => implode(',',$this->scopes),
                 'redirect_uri' => $this->redirectURL 
                 ];

        return $this->authURL . '?' . http_build_query($query);
    }

    public function getAccessToken($code){
        $query = ['grant_type' => 'authorization_code' ,
                  'client_id' => $this->clients['client_id'],
                  'client_secret' => $this->clients['client_secret'],
                  'redirect_uri' => $this->redirectURL,
                  'code' => $code
                 ];

        $client = $this->getHTTPClient();
        $request = $client->post($this->tokenURL . '?' . http_build_query($query));

        $tokens = $request->getResponseBodyAsJson();
        return $tokens;
    }

    public function getRefreshToken(){
        $query = [ 'grant_type' => 'refresh_code' , 
                   'refresh_token' => $this->clients['refreshToken'] ,
                   'client_id' => $this->clients['client_id'],
                   'client_secret' => $this->clients['client_secret'],
                   'redirect_uri' => $this->redirectURL
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