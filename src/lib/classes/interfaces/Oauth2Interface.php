<?php

namespace interfaces;

interface Oauth2Interface{

    public function setURLs( array $urls);
    public function setScopes( array $scopes);
    public function setClient( array $config);
    
    public function getAuthenticationURL();
    public function getAccessToken($code);
    public function getRefreshToken();
    
}