<?php

require __DIR__ . '/lib/google-ads-php/vendor/autoload.php';
require __DIR__ . '/class/google_ads_class.php';

use Google\Auth\OAuth2;
use Google\Auth\CredentialsLoader;

$refreshToken = $_POST['token'];
 // $refreshToken = '1//0d1jevgK_ddUHCgYIARAAGA0SNwF-L9Irg9gvsoiZ-QcdgwMWD9H9BRKFjzkiF410J2-1NrK842rWH38bKWAP9L5bazAdp-gsVNA';

$obj = new My_AdwordsClass();

$accessible_clients = $obj->getAccessibleClients($refreshToken);

echo json_encode($accessible_clients,true);
/*$campaignJson = json_encode($accessible_clients,true);
return $campaignJson;*/

