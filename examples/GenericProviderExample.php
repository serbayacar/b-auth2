<?php

include __DIR__. '/../src/lib/classes/providers/GenericProvider.php';
include __DIR__. '/../src/lib/classes/oauth2/Oauth2Client.php';


$interface = new GenericProvider();
$interface->setClient(['client_id' => '##Your Client ID is here##', 
                           'client_secret' => '##Your Client Secret is here##']);
$interface->setURLs(['apiURL' => '##Provider api url comming is here##', 
                           'authURL' => '##Provider auth url comming is here##', 
                           'tokenURL' => '##Provider auth token url comming is here##', 
                           'redirectURL' => '##Provider redirect uri comming is here##']);
$interface->setScopes(['## If you need set scope, you write here ##']);


$oauth = new Oauth2Client( $interface );

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
