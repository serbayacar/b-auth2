<?php

include __DIR__. '/../src/lib/autoloader.php';
use \providers\HubSpot;
use \oauth2\Oauth2Client;

$hubSpotInterface = new HubSpot();
$hubSpotInterface->setClient(['client_id' => '58543c9b-7663-46e7-a586-327697996aed', 
                           'client_secret' => '9c6c26ba-6f3a-4970-b82e-c26398fa361a']);
$hubSpotInterface->setURLs(['apiURL' => 'apiURL', 
                           'authURL' => 'https://app.hubspot.com/oauth/authorize', 
                           'tokenURL' => 'https://api.hubapi.com/oauth/v1/token', 
                           'redirectURL' => 'http://localhost:3000/examples/HubSpotExample.php']);

$hubSpotInterface->setScopes(['oauth']);

$oauth = new Oauth2Client( $hubSpotInterface );

if(!isset($_GET['code'])){
    $oauth->auth('GET');
    exit();
}else{
    $code= $_GET['code'];
    $tokens = $oauth->getAccessToken($code);
    var_dump($tokens);
    exit();
}
exit();
