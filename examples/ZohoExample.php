<?php

include __DIR__. '/../src/lib/classes/providers/ZohoCRM.php';
include __DIR__. '/../src/lib/classes/oauth2/Oauth2Client.php';


$zohoInterface = new ZohoCRM();
$zohoInterface->setClient(['client_id' => '1000.O1V3FWSJ0Y5TF2XLZ306ZT03B4VIWH', 
                           'client_secret' => '546b4c9a4f5e1cb863b0b1543b31d5fbf81d41077f']);
$zohoInterface->setURLs(['apiURL' => 'apiURL', 
                           'authURL' => 'https://accounts.zoho.com/oauth/v2/auth', 
                           'tokenURL' => 'https://accounts.zoho.com/oauth/v2/token', 
                           'redirectURL' => 'http://127.0.0.1:3000/examples/ZohoExample.php']);
$zohoInterface->setScopes(['ZohoCRM.modules.ALL', 
                           'ZohoCRM.settings.ALL']);
$oauth = new Oauth2Client( $zohoInterface );

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
