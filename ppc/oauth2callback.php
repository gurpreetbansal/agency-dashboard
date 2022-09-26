<?php

require __DIR__ . '/lib/google-ads-php/vendor/autoload.php';
//require __DIR__.'/config.php';
require __DIR__ . '/class/google_ads_class.php';
// require __DIR__ . '/mysql/mysql.class.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V8\ResourceNames;
use Google\Ads\GoogleAds\V8\Services\CustomerServiceClient;
use Google\Ads\GoogleAds\V8\Resources\CustomerClient;
use Google\Ads\GoogleAds\V8\Services\GoogleAdsRow;
use Google\ApiCore\ApiException;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsServerStreamDecorator;
use Google\Ads\GoogleAds\V8\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\Utils\Helper;
use Google\Ads\GoogleAds\V8\Common\ManualCpc;
use Google\Ads\GoogleAds\V8\Common\ManualCpm;
use Google\Ads\GoogleAds\V8\Enums\AdvertisingChannelTypeEnum\AdvertisingChannelType;
use Google\Ads\GoogleAds\V8\Enums\AdServingOptimizationStatusEnum\AdServingOptimizationStatus;
use Google\Ads\GoogleAds\V8\Enums\BudgetDeliveryMethodEnum\BudgetDeliveryMethod;
use Google\Ads\GoogleAds\V8\Enums\CampaignStatusEnum\CampaignStatus;
use Google\Ads\GoogleAds\V8\Resources\Campaign;
use Google\Ads\GoogleAds\V8\Resources\Campaign\NetworkSettings;
use Google\Ads\GoogleAds\V8\Resources\CampaignBudget;
use Google\Ads\GoogleAds\V8\Services\CampaignBudgetOperation;
use Google\Ads\GoogleAds\V8\Services\CampaignOperation;

use Psr\Log\LogLevel;
use Google\Ads\GoogleAds\Lib\V8\LoggerFactory;

$obj = new My_AdwordsClass();



if(isset($_GET['code']) && $_GET['code'] != ''){
    $code = trim($_GET['code']);
    $oauth_token = $obj->getGoogleAdsAuthToken($code);
    $refreshToken = $oauth_token['refresh_token'];
    $access_token = $oauth_token['access_token'];

    // echo "<pre/>";
    // echo 'refreshToken';
    // print_r($refreshToken);
    //  echo "<pre/>";
    // print_r($access_token);
    // die;


    // $accessible_clients = $obj->getAccessibleClients($refreshToken);
    // echo "<pre/>"; print_r($accessible_clients); die;

    $oAuth2Credential = (new OAuth2TokenBuilder())
        ->withRefreshToken($refreshToken)
        ->withClientId(CLIENT_ID)
        ->withClientSecret(CLIENT_SECRET)
        ->build();

    $googleAdsClient = (new GoogleAdsClientBuilder())
        ->withOAuth2Credential($oAuth2Credential)
        ->withLoginCustomerId(9696489139)
        ->withDeveloperToken(DEVELOPERTOKEN)
        ->build();

    // if(empty($accessible_clients)){
    //     echo json_encode(['success'=>true,'message'=>'no clients found']);
    //     exit;
    // }


    // $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();

    $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
 
    // $query = 'SELECT campaign.id, campaign.name FROM campaign ORDER BY campaign.id';

    $query = "SELECT campaign.id, campaign.name, campaign_budget.amount_micros, campaign.status, campaign.optimization_score, campaign.advertising_channel_type, metrics.clicks, metrics.impressions, metrics.ctr,  metrics.average_cpc,  metrics.cost_micros, campaign.bidding_strategy_type,segments.date FROM campaign WHERE segments.date BETWEEN '2021-01-27' AND '2022-04-29' ";

    // $query = "SELECT campaign.id, campaign.name, metrics.impressions, metrics.clicks, metrics.conversions, segments.date FROM keyword_view WHERE segments.date BETWEEN '2021-01-27' AND '2022-04-29'";

    $stream = $googleAdsServiceClient->search(9696489139, $query);
   
    $results = $metrics = [];
    foreach ($stream->iterateAllElements() as $googleAdsRow) {

        // $Campaign = $googleAdsRow->getCampaign();
        // $metrics[] = $googleAdsRow->getMetrics();

        $results[] = [
            // 'CampaignId' => $googleAdsRow->getCampaign()->getId(),
            // 'CampaignName' => $googleAdsRow->getCampaign()->getName(),
            // 'status' => $googleAdsRow->getCampaign()->getStatus(),
            'clicks' => $googleAdsRow->getMetrics()->getClicks(),
            'impressions' => $googleAdsRow->getMetrics()->getImpressions(),
            'cost' => $googleAdsRow->getMetrics()->getCostMicros(),
            'date' => $googleAdsRow->getSegments()->getDate(),

            
        ];
        // printf(
        //     "Campaign with ID %d and name '%s' was found.%s",
        //     $googleAdsRow->getCampaign()->getId(),
        //     $googleAdsRow->getCampaign()->getName(),
        //     PHP_EOL
        // );
    }

    echo "<pre/>"; print_r($results); die;

    // $query = "SELECT campaign.name, campaign_budget.amount_micros, campaign.status, campaign.optimization_score, campaign.advertising_channel_type, metrics.clicks, metrics.impressions, metrics.ctr,  metrics.average_cpc,  metrics.cost_micros, campaign.bidding_strategy_type FROM campaign WHERE segments.date DURING LAST_7_DAYS ";

    // $query = "SELECT campaign.id, campaign.name, ad_group.id, ad_group.name, ad_group_criterion.criterion_id, ad_group_criterion.keyword.text, ad_group_criterion.keyword.match_type, metrics.impressions, metrics.clicks, metrics.cost_micros FROM keyword_view WHERE segments.date DURING LAST_90_DAYS AND campaign.advertising_channel_type = 'SEARCH' AND ad_group.status = 'ENABLED' AND ad_group_criterion.status IN ('ENABLED', 'PAUSED') ORDER BY metrics.impressions DESC  LIMIT 500";

     // $query = 'SELECT campaign.id, campaign.name FROM campaign ORDER BY campaign.id';
        // Issues a search stream request.

    // echo "<pre/>"; print_r($accessible_clients); die;
    // foreach($accessible_clients as $v){

        // $client_id = $v['customerId'];

        // $res = $my_db->Query("SELECT * FROM oauth where client_id=$client_id limit 1");

        // $response = $googleAdsServiceClient->search(1972094370, $query, ['pageSize' => 10]);
        $stream = $googleAdsServiceClient->searchStream($customerId, $query);

        foreach ($response->iterateAllElements() as $googleAdsRow) {

            // $campaign = $googleAdsRow->getCampaign();
            echo "<pre>";
        echo 'refreshToken';
        print_r($googleAdsRow);
        die;
            /*$adGroup = $googleAdsRow->getAdGroup();
            $adGroupCriterion = $googleAdsRow->getAdGroupCriterion();
            $metrics = $googleAdsRow->getMetrics();
            printf(
                "Keyword text '%s' with "
                . "match type %s "
                . "and ID %d "
                . "in ad group '%s' "
                . "with ID %d "
                . "in campaign '%s' "
                . "with ID %d "
                . "had %d impression(s), "
                . "%d click(s), "
                . "and %d cost (in micros) "
                . "during the last 7 days.%s",
                $adGroupCriterion->getKeyword()->getText(),
                KeywordMatchType::name($adGroupCriterion->getKeyword()->getMatchType()),
                $adGroupCriterion->getCriterionId(),
                $adGroup->getName(),
                $adGroup->getId(),
                $campaign->getName(),
                $campaign->getId(),
                $metrics->getImpressions(),
                $metrics->getClicks(),
                $metrics->getCostMicros(),
                PHP_EOL
            );*/
        }
        die;
        echo "<pre>";
        echo 'refreshToken';
        print_r($response);
        die;
        /*if ($res->num_rows==0) {
            $values = 
                ['is_manager' => MySQL::SQLValue($v['is_manager']),
                'account_name' => MySQL::SQLValue($v['account_name']),
                'account_time_zone' => MySQL::SQLValue($v['account_time_zone']),
                'currencyCode' => MySQL::SQLValue($v['currencyCode']),
                'client_id' => MySQL::SQLValue($v['customerId']),
                'refresh_token' => MySQL::SQLValue($refreshToken),
                'manager_id'=> MySQL::SQLValue(isset($v['manager_id']) ? $v['manager_id'] : ''),
                ];
            $result = $my_db->InsertRow("oauth", $values);
        }else{
            $update["refresh_token"] = MySQL::SQLValue($refreshToken);
            $where["client_id"] = MySQL::SQLValue($v['customerId']);
            $result = $my_db->UpdateRows("oauth", $update, $where);
        }*/
    // }
    echo "Clients fetched successfully";exit;
}else{
    echo json_encode(['success'=>false,'error'=>'please re-authenticate again']);
}

