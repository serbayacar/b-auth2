<?php 

namespace oauth2;
use \interfaces\Oauth2Interface;

class Oauth2Client{

    private $interface;
    private $httpClient;


    public function __construct( Oauth2Interface $interface)
    {
        $this->interface = $interface;
    }

    public function auth(){
        $authURL = $this->interface->getAuthenticationURL();
        header('location: ' . $authURL);
    }

    public function getAccessToken($code){
        $tokens = $this->interface->getAccessToken($code);
        return $tokens;
    }

    public function getRefreshToken($request_type){
        $tokens = $this->interface->getRefreshToken();
        return $request;
    }


}