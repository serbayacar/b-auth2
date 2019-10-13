<?php

// interface Oauth2Interface{

//     public function getAuthenticationURL();
//     public function auth();
//     public function getAccessToken();
//     public function refreshTokens();
//     public function checkTokens();
    
// }

interface Oauth2Interface{

    public function setURLs( array $urls);
    public function setScopes( array $scopes);
    public function setClient( array $config);
    
    public function getAuthenticationURL();
    public function getAccessToken($code);
    public function getRefreshToken();
    
}