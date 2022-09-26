<?php
// require __DIR__.  '/config.php';
require __DIR__ . '/lib/google-ads-php/vendor/autoload.php';
require __DIR__ . '/class/google_ads_class.php';
require __DIR__ . '/mysql/mysql.class.php';

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Utils\Helper;


$obj = new My_AdwordsClass();

/*$customerId = 8146354427;
$refreshToken = "1//0dio2kGRB9zIxCgYIARAAGA0SNwF-L9IrjxzaRTyGBnHcwZU1axrN7xPS8v2x338N6";
$manager_id = null;

$oAuth2Credential = (new OAuth2TokenBuilder())
                        ->withRefreshToken($refreshToken)
                        ->withClientId(CLIENT_ID)
                        ->withClientSecret(CLIENT_SECRET)
                        ->build();

$googleAdsClient = (new GoogleAdsClientBuilder())
    ->withOAuth2Credential($oAuth2Credential)
    ->withDeveloperToken(DEVELOPERTOKEN)
    ->withLoginCustomerId($manager_id)
    ->build();


$comList = $obj->getCampaigns($googleAdsClient,$customerId);
echo "<pre/>";
print_r($comList);
die;*/

$my_db = new MySQL();

$q = "select * from oauth where is_manager is NULL";
$res = $my_db->QueryArray($q);



 $i = 1;
foreach($res as $v){
    $customerId = $v['client_id'];
    $refreshToken = $v['refresh_token'];
    $manager_id = $v['manager_id'];

    $customerId = 6656849678;
    $refreshToken = '1//0d9QwqGBS2b4fCgYIARAAGA0SNwF-L9Ir8yk430K25W4seeTbFyegTvM34KPWPmgJP0B80d9UswQVQ1WMViY3t2wZkn08wV_aVQY';
    $manager_id = null;

    $oAuth2Credential = (new OAuth2TokenBuilder())
                            ->withRefreshToken($refreshToken)
                            ->withClientId(CLIENT_ID)
                            ->withClientSecret(CLIENT_SECRET)
                            ->build();

    $googleAdsClient = (new GoogleAdsClientBuilder())
        ->withOAuth2Credential($oAuth2Credential)
        ->withDeveloperToken(DEVELOPERTOKEN)
        ->withLoginCustomerId($manager_id)
        ->build();

    $obj->getCampaigns($googleAdsClient,$customerId);
    $i++;
    exit;
}
?>