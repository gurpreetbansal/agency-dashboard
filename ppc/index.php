<?php
require __DIR__ . '/lib/google-ads-php/vendor/autoload.php';
require __DIR__ . '/class/google_ads_class.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



use GetOpt\GetOpt;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentNames;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentParser;

use Google\Auth\OAuth2;
use Google\Auth\CredentialsLoader;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V8\ResourceNames;
use Google\Ads\GoogleAds\V8\Enums\KeywordPlanNetworkEnum\KeywordPlanNetwork;
use Google\Ads\GoogleAds\V8\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\V8\Services\GenerateKeywordIdeaResult;
use Google\Ads\GoogleAds\V8\Services\KeywordAndUrlSeed;
use Google\Ads\GoogleAds\V8\Services\KeywordSeed;
use Google\Ads\GoogleAds\V8\Services\UrlSeed;
use Google\ApiCore\ApiException;

$obj = new My_AdwordsClass();

// $refreshToken = '1//0d5Vpwfzi-rRcCgYIARAAGA0SNwF-L9IrT8mIb6HFS5WxHOjyypaGiGmPBrbuq-Cxnmk_tqggaadzYkrpcLsV6e7KRgCyPwNghR8';

//  $oAuth2Credential = (new OAuth2TokenBuilder())
//     ->withRefreshToken($refreshToken)
//     ->withClientId(CLIENT_ID)
//     ->withClientSecret(CLIENT_SECRET)
//     ->build();

// $googleAdsClient = (new GoogleAdsClientBuilder())
//   ->withOAuth2Credential($oAuth2Credential)
//   ->withDeveloperToken(DEVELOPERTOKEN)
//   ->build();


// $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();

// $query = "SELECT campaign.name, campaign_budget.amount_micros, campaign.status, campaign.optimization_score, campaign.advertising_channel_type, metrics.clicks, metrics.impressions, metrics.ctr,  metrics.average_cpc,  metrics.cost_micros, campaign.bidding_strategy_type FROM campaign WHERE segments.date DURING LAST_7_DAYS ";

// $response = $googleAdsServiceClient->search('7300053848', $query, ['pageSize' => 1000]);

// echo "<pre/>"; print_r($response); 
// die;
// foreach ($response->iterateAllElements() as $googleAdsRow) {
//   dd($googleAdsRow);
// }


?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Google Ads API</title>
  </head>
  <body>
  <div class="container py-5">
        <h1 class="display-5 fw-bold">Google Ads API</h1>
        <p class="col-md-8 fs-6">
            After clicking, you will reach Adwords login page. Please make sure you enter login details correctly.
            <p class="col-md-8 fs-6">
            Once you are logged in, then you will have to click on the "Grant access" button to give us access.</p>
        <a href="<?php echo $obj->getAuthorizationUri();?>"><button class="btn btn-primary btn-lg" type="button">Login with Google</button></a>
      </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->

  </body>
</html>
