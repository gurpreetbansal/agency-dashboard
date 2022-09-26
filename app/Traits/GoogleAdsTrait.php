<?php

namespace App\Traits;

use App\SemrushUserAccount;
use App\GoogleAnalyticsUsers;
use App\GoogleAdsCustomer;
use App\ModuleByDateRange;

use App\GoogleUpdate;
use App\Error;
use Illuminate\Http\Request;
use \Illuminate\Pagination\LengthAwarePaginator;

use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V10\ResourceNames;
use Google\Ads\GoogleAds\V10\Services\CustomerServiceClient;
use Google\Ads\GoogleAds\V10\Resources\CustomerClient;
use Google\Ads\GoogleAds\V10\Services\GoogleAdsRow;
use Google\ApiCore\ApiException;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsServerStreamDecorator;
use Google\Ads\GoogleAds\V10\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\Utils\Helper;
use Google\Ads\GoogleAds\V10\Common\ManualCpc;
use Google\Ads\GoogleAds\V10\Common\ManualCpm;
use Google\Ads\GoogleAds\V10\Enums\AdvertisingChannelTypeEnum\AdvertisingChannelType;
use Google\Ads\GoogleAds\V10\Enums\AdServingOptimizationStatusEnum\AdServingOptimizationStatus;
use Google\Ads\GoogleAds\V10\Enums\BudgetDeliveryMethodEnum\BudgetDeliveryMethod;
use Google\Ads\GoogleAds\V10\Enums\CampaignStatusEnum\CampaignStatus;
use Google\Ads\GoogleAds\V10\Resources\Campaign;
use Google\Ads\GoogleAds\V10\Resources\Campaign\NetworkSettings;
use Google\Ads\GoogleAds\V10\Resources\CampaignBudget;
use Google\Ads\GoogleAds\V10\Services\CampaignBudgetOperation;
use Google\Ads\GoogleAds\V10\Services\CampaignOperation;
use Google\Ads\GoogleAds\V10\Enums\KeywordMatchTypeEnum\KeywordMatchType;

use Google\Ads\GoogleAds\V10\Enums\AdGroupAdStatusEnum\AdGroupAdStatus;
use Google\Ads\GoogleAds\V10\Enums\AdTypeEnum\AdType;
use Google\Ads\GoogleAds\V10\Common\AdTextAsset;
use Google\Protobuf\Internal\RepeatedField;
use Google\Ads\GoogleAds\V10\Enums\ServedAssetFieldTypeEnum\ServedAssetFieldType;

use Google\Ads\GoogleAds\V10\Enums\DeviceEnum\Device;
use Google\Ads\GoogleAds\V10\Enums\AdNetworkTypeEnum\AdNetworkType;
use Google\Ads\GoogleAds\V10\Enums\SlotEnum\Slot;
use Google\Ads\GoogleAds\V10\Enums\ClickTypeEnum\ClickType;

use Psr\Log\LogLevel;
use Google\Ads\GoogleAds\Lib\V10\LoggerFactory;


trait GoogleAdsTrait
{


	public function googleAdsAuth($refreshToken){

        $oAuth2Credential = (new OAuth2TokenBuilder())
			->withClientId(\config('app.ads_client_id'))
			->withClientSecret(\config('app.ads_client_secret'))
			->withRefreshToken($refreshToken)
			->build();

		$googleAdsClient = (new GoogleAdsClientBuilder())
			->withDeveloperToken(\config('app.ads_developerToken'))
	        ->withOAuth2Credential($oAuth2Credential)
	        ->build();

        return $googleAdsClient;
    }

    public function googleAdsAuthCustomerId($loginCustomerId,$refreshToken){

        $oAuth2Credential = (new OAuth2TokenBuilder())
			->withClientId(\config('app.ads_client_id'))
			->withClientSecret(\config('app.ads_client_secret'))
			->withRefreshToken($refreshToken)
			->build();

		$googleAdsClient = (new GoogleAdsClientBuilder())
			->withDeveloperToken(\config('app.ads_developerToken'))
            ->withOAuth2Credential($oAuth2Credential)
            ->withLoginCustomerId($loginCustomerId)
            ->build();

        return $googleAdsClient;
    }

    public function cronGoogleAdlogs($campaign_id = null){
		
		if($campaign_id == null){
			return false;
		}
		
        $value = SemrushUserAccount::
        with(array('google_adwords_account'=>function($query){
            $query->select('id','customer_id','login_customer_id');
        }))
        ->where('status','0')
        ->where('id',$campaign_id)
        ->first();
		
        if(!empty($value->google_adwords_account) || ($value->google_adwords_account != null)){

            $ads_customer_id = $value->google_adwords_account->customer_id;

            $googleUserDetails = GoogleAnalyticsUsers::findorfail($value->google_ads_id);
            $refreshToken = $googleUserDetails->google_refresh_token;
            $customerId = $value->google_adwords_account->customer_id;
            $loginCustomerId = $value->google_adwords_account->login_customer_id;
            
            if($loginCustomerId <> $customerId){
            	$adwordsSession  = $this->googleAdsAuthCustomerId($loginCustomerId,$refreshToken);
            }else{
            	$adwordsSession  = $this->googleAdsAuth($refreshToken);
            }

            try {
	            $checkLimit = $this->checkdailyLimit($adwordsSession,$customerId);
	        } catch (GoogleAdsException $googleAdsException) {
	            printf(
	                "Request with ID '%s' has failed.%sGoogle Ads failure details:%s",
	                $googleAdsException->getRequestId(),
	                PHP_EOL,
	                PHP_EOL
	            );
	            foreach ($googleAdsException->getGoogleAdsFailure()->getErrors() as $error) {
	                /** @var GoogleAdsError $error */
	                printf(
	                    "\t%s: %s%s",
	                    $error->getErrorCode()->getErrorCode(),
	                    $error->getMessage(),
	                    PHP_EOL
	                );
	            }
	            exit(1);
	        } catch (ApiException $apiException) {
	            printf(
	                "ApiException was thrown with message '%s'.%s",
	                $apiException->getMessage(),
	                PHP_EOL
	            );
	            exit(1);
	        }
           	
            if($checkLimit['status'] == 'success'){

                self::googleAdsSummary($adwordsSession,$ads_customer_id,$campaign_id);
                self::campaignReportsQuery($adwordsSession,$ads_customer_id,$campaign_id);
                self::keywordsReportsQuery($adwordsSession,$ads_customer_id,$campaign_id); 
                self::adGroupReportsQuery($adwordsSession,$ads_customer_id,$campaign_id);
                self::adReportsQuery($adwordsSession,$ads_customer_id,$campaign_id);
                self::deviceReportsQuery($adwordsSession,$ads_customer_id,$campaign_id);

                GoogleUpdate::updateTiming($campaign_id,'adwords','adwords_type','2');
            
            }
            
            return $checkLimit;
        }
    }

	public function googleAdlogs(Request $request){
		
		$campaign_id = $request->campaign_id;
        $value = SemrushUserAccount::
        with(array('google_adwords_account'=>function($query){
            $query->select('id','customer_id','login_customer_id');
        }))
        ->where('status','0')
        ->where('id',$campaign_id)
        ->first();
		
        if(!empty($value->google_adwords_account) || ($value->google_adwords_account != null)){

            $ads_customer_id = $value->google_adwords_account->customer_id;

            $googleUserDetails = GoogleAnalyticsUsers::findorfail($value->google_ads_id);
            $refreshToken = $googleUserDetails->google_refresh_token;
            $customerId = $value->google_adwords_account->customer_id;
            $loginCustomerId = $value->google_adwords_account->login_customer_id;
            
            if($loginCustomerId <> $customerId){
            	$adwordsSession  = $this->googleAdsAuthCustomerId($loginCustomerId,$refreshToken);
            }else{
            	$adwordsSession  = $this->googleAdsAuth($refreshToken);
            }
        
            $today = date('Y-m-d');
            $end_date = date('Ymd',strtotime('-1 day'));
            $start_date = date('Ymd',strtotime('-2 year'));
            


            $fileName = $ads_customer_id.'_campaigns.csv';
            $keywords_fileName = $ads_customer_id.'_keywords.csv';
            $ads_fileName = $ads_customer_id.'_ads.csv';
            $adgroup_fileName = $ads_customer_id.'_adgroup.csv';
            $place_file = $ads_customer_id.'_placeholder.csv';

            $duration = 3;

            try {
	            $checkLimit = $this->checkdailyLimit($adwordsSession,$customerId);
	        } catch (GoogleAdsException $googleAdsException) {
	            printf(
	                "Request with ID '%s' has failed.%sGoogle Ads failure details:%s",
	                $googleAdsException->getRequestId(),
	                PHP_EOL,
	                PHP_EOL
	            );
	            foreach ($googleAdsException->getGoogleAdsFailure()->getErrors() as $error) {
	                /** @var GoogleAdsError $error */
	                printf(
	                    "\t%s: %s%s",
	                    $error->getErrorCode()->getErrorCode(),
	                    $error->getMessage(),
	                    PHP_EOL
	                );
	            }
	            exit(1);
	        } catch (ApiException $apiException) {
	            printf(
	                "ApiException was thrown with message '%s'.%s",
	                $apiException->getMessage(),
	                PHP_EOL
	            );
	            exit(1);
	        }
           	
            if($checkLimit['status'] == 'success'){

                self::googleAdsSummary($adwordsSession,$ads_customer_id,$campaign_id);
                self::campaignReportsQuery($adwordsSession,$ads_customer_id,$campaign_id);
                self::keywordsReportsQuery($adwordsSession,$ads_customer_id,$campaign_id); 
                self::adGroupReportsQuery($adwordsSession,$ads_customer_id,$campaign_id);
                self::adReportsQuery($adwordsSession,$ads_customer_id,$campaign_id);
                self::deviceReportsQuery($adwordsSession,$ads_customer_id,$campaign_id);

                GoogleUpdate::updateTiming($campaign_id,'adwords','adwords_type','2');
            
            }
            
            return $checkLimit;
        }
    }

    public function checkdailyLimit($googleAdsClient,$account_id){
		
		$startDate = date('Ymd',strtotime('-1 day'));
        $endDate = date('Ymd',strtotime('-7 day'));

        try{
        	$googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
			$query = "SELECT campaign.id, campaign.name, campaign_budget.amount_micros, campaign.status, campaign.optimization_score, campaign.advertising_channel_type, metrics.clicks, metrics.impressions, metrics.ctr, metrics.conversions, metrics.all_conversions,  metrics.average_cpc,  metrics.cost_micros, campaign.bidding_strategy_type,segments.date FROM campaign WHERE segments.date BETWEEN '".$startDate."' AND '".$endDate."' ";

			$stream = $googleAdsServiceClient->search($account_id, $query);

			$results = $metrics = [];
			
		    foreach ($stream->iterateAllElements() as $googleAdsRow) {

		    	$date = date('Ymd',strtotime($googleAdsRow->getSegments()->getDate()));
		    	
		    	if(count($results) > 0 && isset($results[$date])){
		    		$results[$date]['clicks'] += $googleAdsRow->getMetrics()->getClicks();
		    		$results[$date]['impressions'] += $googleAdsRow->getMetrics()->getImpressions();
		    		$results[$date]['conversions'] += $googleAdsRow->getMetrics()->getAllConversions();
		    		$results[$date]['cost'] += $googleAdsRow->getMetrics()->getCostMicros();
		    		$results[$date]['avgCPC'] += $googleAdsRow->getMetrics()->getAverageCpc();
		    		$results[$date]['ctr'] += $googleAdsRow->getMetrics()->getCtr();
		    		
		    	}else{
		    		$results[$date]['impressions'] = $googleAdsRow->getMetrics()->getImpressions();
		    		$results[$date]['clicks'] = $googleAdsRow->getMetrics()->getClicks();
		    		$results[$date]['ctr'] = $googleAdsRow->getMetrics()->getCtr();
		    		$results[$date]['cost'] = $googleAdsRow->getMetrics()->getCostMicros();
		    		$results[$date]['avgCPC'] = $googleAdsRow->getMetrics()->getAverageCpc();
		    		$results[$date]['conversions'] = $googleAdsRow->getMetrics()->getAllConversions();
		    		
		    	}
		    }
		    $responce = [
                'status'=>'success',
            ];
		}catch (\Exception  $exception) {
			
			if($exception->getCode() == 400){
				$responce = [
                    'status'=>'error',
                    'message'=>'Token has been expired or revoked! Please reconnect your account',
                ];
			}else{
				$responce = [
                    'status'=>'error',
                    'message'=>'AuthenticationError: Please reconnect your account',
                ];
			}
		}catch (GoogleAdsException $googleAdsException) {
            printf(
                "Request with ID '%s' has failed.%sGoogle Ads failure details:%s",
                $googleAdsException->getRequestId(),
                PHP_EOL,
                PHP_EOL
            );
            foreach ($googleAdsException->getGoogleAdsFailure()->getErrors() as $error) {
                /** @var GoogleAdsError $error */
                printf(
                    "\t%s: %s%s",
                    $error->getErrorCode()->getErrorCode(),
                    $error->getMessage(),
                    PHP_EOL
                );
            }
            exit(1);
        } catch (ApiException $apiException) {
            printf(
                "ApiException was thrown with message '%s'.%s",
                $apiException->getMessage(),
                PHP_EOL
            );
            exit(1);
        }
       
	    return $responce;
	}

	public static function googleAdsSummary($googleAdsClient,$account_id,$campaign_id){
		ini_set('memory_limit', -1);
		$googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
		$startDate = date('Y-m-d',strtotime('-2 year'));
		$endDate = date('Y-m-d',strtotime('-1 day'));
        try{

			$query = "SELECT campaign.id, campaign.name, campaign_budget.amount_micros, campaign.status, campaign.optimization_score, campaign.advertising_channel_type, metrics.clicks, metrics.impressions, metrics.ctr, metrics.conversions, metrics.all_conversions, metrics.average_cpc, metrics.cost_micros, metrics.cost_per_conversion, campaign.bidding_strategy_type,segments.date FROM campaign WHERE segments.date BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY segments.date ASC";

			$stream = $googleAdsServiceClient->search($account_id, $query);

			$results = $metrics = [];
			
		    foreach ($stream->iterateAllElements() as $googleAdsRow) {

		    	$date = date('Ymd',strtotime($googleAdsRow->getSegments()->getDate()));
		    	
		    	if(count($results) > 0 && isset($results[$date])){
		    		$results[$date]['clicks'] += $googleAdsRow->getMetrics()->getClicks();
		    		$results[$date]['impressions'] += $googleAdsRow->getMetrics()->getImpressions();
		    		$results[$date]['conversions'] += $googleAdsRow->getMetrics()->getConversions();
		    		$results[$date]['all_conversions'] += $googleAdsRow->getMetrics()->getAllConversions();
		    		$results[$date]['cost'] += $googleAdsRow->getMetrics()->getCostMicros();
		    		$results[$date]['avgCPC'] += $googleAdsRow->getMetrics()->getAverageCpc();
		    		$results[$date]['ctr'] += $googleAdsRow->getMetrics()->getCtr();
		    		$results[$date]['cost_per_conversion'] += $googleAdsRow->getMetrics()->getCostPerConversion();
		    		
		    	}else{
		    		$results[$date]['impressions'] = $googleAdsRow->getMetrics()->getImpressions();
		    		$results[$date]['clicks'] = $googleAdsRow->getMetrics()->getClicks();
		    		$results[$date]['ctr'] = $googleAdsRow->getMetrics()->getCtr();
		    		$results[$date]['cost'] = $googleAdsRow->getMetrics()->getCostMicros();
		    		$results[$date]['avgCPC'] = $googleAdsRow->getMetrics()->getAverageCpc();
		    		$results[$date]['conversions'] = $googleAdsRow->getMetrics()->getConversions();
		    		$results[$date]['all_conversions'] = $googleAdsRow->getMetrics()->getAllConversions();
		    		$results[$date]['cost_per_conversion'] = $googleAdsRow->getMetrics()->getCostPerConversion();
		    		
		    	}
		    }

		    if (!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/graphs')) {
                mkdir(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/graphs', 0777, true);
                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/graphs/overview.json', print_r(json_encode($results,true),true));
            }else{
                if(!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/graphs/overview.json')){
                    file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/graphs/overview.json', print_r(json_encode($results,true),true));
                }else{
                    file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/graphs/overview.json', print_r(json_encode($results,true),true));
                }
            }

		}catch (Exception $e) {
	        $error = $e->getErrors();

	        if($error[0]->getErrorString() == 'AuthenticationError.OAUTH_TOKEN_INVALID'){
	            $responce = [
	                'status'=>'error',
	                'message'=>'AuthenticationError: Please reconnect your account',
	            ];
	        }else{
	            $responce = [
	                'status'=>'error',
	                'message'=>'RATE EXCEEDED: Basic Access Daily Reporting Quota',
	            ];
	        }
	    }
	}

	public static function campaignReportsQuery($googleAdsClient,$account_id,$campaign_id){
		ini_set('memory_limit', -1);
		$googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
		
		$startDate = date('Y-m-d',strtotime('-2 year'));
		$endDate = date('Y-m-d',strtotime('-1 day'));

        try{

			$query = "SELECT campaign.id, campaign.name, campaign_budget.amount_micros, campaign.status, campaign.optimization_score, campaign.advertising_channel_type, metrics.clicks, metrics.impressions, metrics.ctr, metrics.conversions, metrics.all_conversions,  metrics.average_cpc,  metrics.cost_micros, campaign.bidding_strategy_type,segments.date FROM campaign WHERE segments.date BETWEEN '".$startDate."' AND '".$endDate."' ";

			$stream = $googleAdsServiceClient->search($account_id, $query);

			$resultsList = $metrics = [];
			
		    foreach ($stream->iterateAllElements() as $googleAdsRow) {

		    	$dateMonth = date('Ym',strtotime($googleAdsRow->getSegments()->getDate()));
		    	$date = date('Ymd',strtotime($googleAdsRow->getSegments()->getDate()));
		    	
		    	$campaign = $googleAdsRow->getCampaign();
		    	
		    	$resultsList[$dateMonth][$campaign->getId()] = $campaign->getName();

		    	if(count($metrics) > 0 && isset($metrics[$date])){
		    		$metrics[$campaign->getId()][$date]['clicks'] += $googleAdsRow->getMetrics()->getClicks();
		    		$metrics[$campaign->getId()][$date]['impressions'] += $googleAdsRow->getMetrics()->getImpressions();
		    		$metrics[$campaign->getId()][$date]['conversions'] += $googleAdsRow->getMetrics()->getAllConversions();
		    		$metrics[$campaign->getId()][$date]['cost'] += $googleAdsRow->getMetrics()->getCostMicros();
		    		$metrics[$campaign->getId()][$date]['avgCPC'] += $googleAdsRow->getMetrics()->getAverageCpc();
		    		$metrics[$campaign->getId()][$date]['ctr'] += $googleAdsRow->getMetrics()->getCtr();
		    		
		    	}else{
		    		$metrics[$campaign->getId()][$date]['impressions'] = $googleAdsRow->getMetrics()->getImpressions();
		    		$metrics[$campaign->getId()][$date]['clicks'] = $googleAdsRow->getMetrics()->getClicks();
		    		$metrics[$campaign->getId()][$date]['ctr'] = $googleAdsRow->getMetrics()->getCtr();
		    		$metrics[$campaign->getId()][$date]['cost'] = $googleAdsRow->getMetrics()->getCostMicros();
		    		$metrics[$campaign->getId()][$date]['avgCPC'] = $googleAdsRow->getMetrics()->getAverageCpc();
		    		$metrics[$campaign->getId()][$date]['conversions'] = $googleAdsRow->getMetrics()->getAllConversions();
		    		
		    	}
		    }

		    if (!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/campaign')) {
	            mkdir(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/campaign', 0777, true);
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/campaign/metrics.json', print_r(json_encode($metrics,true),true));

	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/campaign/list.json', print_r(json_encode($resultsList,true),true));
	        }else{
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/campaign/metrics.json', print_r(json_encode($metrics,true),true));

	            if(!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/campaign/list.json')){
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/campaign/list.json', print_r(json_encode($resultsList,true),true));
	            }else{
	            
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/campaign/list.json', print_r(json_encode($resultsList,true),true));

	            }
	        }

		    $responce = [
                'status'=>'success',
            ];
		}catch (Exception $e) {
	        $error = $e->getErrors();

	        if($error[0]->getErrorString() == 'AuthenticationError.OAUTH_TOKEN_INVALID'){
	            $responce = [
	                'status'=>'error',
	                'message'=>'AuthenticationError: Please reconnect your account',
	            ];
	        }else{
	            $responce = [
	                'status'=>'error',
	                'message'=>'RATE EXCEEDED: Basic Access Daily Reporting Quota',
	            ];
	        }
	    }
	}

	public static function keywordsReportsQuery($googleAdsClient,$account_id,$campaign_id){
		ini_set('memory_limit', -1);
		$googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
		$startDate = date('Y-m-d',strtotime('-2 year'));
		$endDate = date('Y-m-d',strtotime('-1 day'));

        try{

		$query =    "SELECT campaign.id, campaign.name, ad_group.id, ad_group.name, ad_group_criterion.criterion_id, ad_group_criterion.keyword.text, ad_group_criterion.keyword.match_type, metrics.clicks, metrics.impressions, metrics.ctr, metrics.conversions, metrics.all_conversions,  metrics.average_cpc,  metrics.cost_micros,segments.date FROM keyword_view WHERE segments.date BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY segments.date ASC";

			$stream = $googleAdsServiceClient->search($account_id, $query);

			$adGroupArr = $metrics = [];
		
			foreach ($stream->iterateAllElements() as $googleAdsRow) {
	            
	            $dateMonth = date('Ym',strtotime($googleAdsRow->getSegments()->getDate()));
		    	$date = date('Ymd',strtotime($googleAdsRow->getSegments()->getDate()));

	            $campaign = $googleAdsRow->getCampaign();
	            $adGroup = $googleAdsRow->getAdGroup();
	            $adGroupCriterion = $googleAdsRow->getAdGroupCriterion();
	            $metricsData = $googleAdsRow->getMetrics();

	            $adGroupArr[$dateMonth][$adGroupCriterion->getCriterionId()] = $adGroupCriterion->getKeyword()->getText();

	            if(count($metrics) > 0 && isset($metrics[$date])){
		    		$metrics[$adGroupCriterion->getCriterionId()][$date]['clicks'] += $metricsData->getClicks();
		    		$metrics[$adGroupCriterion->getCriterionId()][$date]['impressions'] += $metricsData->getImpressions();
		    		$metrics[$adGroupCriterion->getCriterionId()][$date]['conversions'] += $metricsData->getAllConversions();
		    		$metrics[$adGroupCriterion->getCriterionId()][$date]['cost'] += $metricsData->getCostMicros();
		    		$metrics[$adGroupCriterion->getCriterionId()][$date]['avgCPC'] += $metricsData->getAverageCpc();
		    		$metrics[$adGroupCriterion->getCriterionId()][$date]['ctr'] += $metricsData->getCtr();
		    		
		    	}else{
		    		$metrics[$adGroupCriterion->getCriterionId()][$date]['impressions'] = $metricsData->getImpressions();
		    		$metrics[$adGroupCriterion->getCriterionId()][$date]['clicks'] = $metricsData->getClicks();
		    		$metrics[$adGroupCriterion->getCriterionId()][$date]['ctr'] = $metricsData->getCtr();
		    		$metrics[$adGroupCriterion->getCriterionId()][$date]['cost'] = $metricsData->getCostMicros();
		    		$metrics[$adGroupCriterion->getCriterionId()][$date]['avgCPC'] = $metricsData->getAverageCpc();
		    		$metrics[$adGroupCriterion->getCriterionId()][$date]['conversions'] = $metricsData->getAllConversions();
		    		
		    	}
		    }

		 	if (!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/keywords')) {
	            mkdir(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/keywords', 0777, true);
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/keywords/metrics.json', print_r(json_encode($metrics,true),true));

	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/keywords/list.json', print_r(json_encode($adGroupArr,true),true));
	        }else{
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/keywords/metrics.json', print_r(json_encode($metrics,true),true));

	            if(!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/keywords/list.json')){
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/keywords/list.json', print_r(json_encode($adGroupArr,true),true));
	            }else{
	            
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/keywords/list.json', print_r(json_encode($adGroupArr,true),true));

	            }
	        }

		    $responce = [
                'status'=>'success',
            ];
		}catch (Exception $e) {
	        $error = $e->getErrors();

	        if($error[0]->getErrorString() == 'AuthenticationError.OAUTH_TOKEN_INVALID'){
	            $responce = [
	                'status'=>'error',
	                'message'=>'AuthenticationError: Please reconnect your account',
	            ];
	        }else{
	            $responce = [
	                'status'=>'error',
	                'message'=>'RATE EXCEEDED: Basic Access Daily Reporting Quota',
	            ];
	        }
	    }
	}

	public static function adGroupReportsQuery($googleAdsClient,$account_id,$campaign_id){
		ini_set('memory_limit', -1);
		$googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
		$startDate = date('Y-m-d',strtotime('-2 year'));
		$endDate = date('Y-m-d',strtotime('-1 day'));

        try{

			$query =    "SELECT campaign.id, ad_group.id, ad_group.name, metrics.clicks, metrics.impressions, metrics.ctr, metrics.conversions, metrics.all_conversions,  metrics.average_cpc,  metrics.cost_micros, segments.date FROM ad_group WHERE segments.date BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY segments.date ASC";

			$stream = $googleAdsServiceClient->search($account_id, $query);

			$adGroupArr = $metrics = [];
		
			foreach ($stream->iterateAllElements() as $googleAdsRow) {
	            
	            $dateMonth = date('Ym',strtotime($googleAdsRow->getSegments()->getDate()));
		    	$date = date('Ymd',strtotime($googleAdsRow->getSegments()->getDate()));

	            $adGroup = $googleAdsRow->getAdGroup();
	            $metricsData = $googleAdsRow->getMetrics();
	            // $adGroupArr[$adGroup->getId()] = $adGroup->getName();

	            $adGroupArr[$dateMonth][$adGroup->getId()] = $adGroup->getName();

	            if(count($metrics) > 0 && isset($metrics[$date])){
		    		$metrics[$adGroup->getId()][$date]['clicks'] += $metricsData->getClicks();
		    		$metrics[$adGroup->getId()][$date]['impressions'] += $metricsData->getImpressions();
		    		$metrics[$adGroup->getId()][$date]['conversions'] += $metricsData->getAllConversions();
		    		$metrics[$adGroup->getId()][$date]['cost'] += $metricsData->getCostMicros();
		    		$metrics[$adGroup->getId()][$date]['avgCPC'] += $metricsData->getAverageCpc();
		    		$metrics[$adGroup->getId()][$date]['ctr'] += $metricsData->getCtr();
		    		
		    	}else{
		    		$metrics[$adGroup->getId()][$date]['impressions'] = $metricsData->getImpressions();
		    		$metrics[$adGroup->getId()][$date]['clicks'] = $metricsData->getClicks();
		    		$metrics[$adGroup->getId()][$date]['ctr'] = $metricsData->getCtr();
		    		$metrics[$adGroup->getId()][$date]['cost'] = $metricsData->getCostMicros();
		    		$metrics[$adGroup->getId()][$date]['avgCPC'] = $metricsData->getAverageCpc();
		    		$metrics[$adGroup->getId()][$date]['conversions'] = $metricsData->getAllConversions();
		    		
		    	}
	        }

		    if (!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/adGroup')) {
	            mkdir(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/adGroup/', 0777, true);
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/adGroup/metrics.json', print_r(json_encode($metrics,true),true));

	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/adGroup/list.json', print_r(json_encode($adGroupArr,true),true));
	        }else{
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/adGroup/metrics.json', print_r(json_encode($metrics,true),true));

	            if(!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/adGroup/list.json')){
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/adGroup/list.json', print_r(json_encode($adGroupArr,true),true));
	            }else{
	            
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/adGroup/list.json', print_r(json_encode($adGroupArr,true),true));

	            }
	        }

		    $responce = [
                'status'=>'success',
            ];
		}catch (Exception $e) {
	        $error = $e->getErrors();

	        if($error[0]->getErrorString() == 'AuthenticationError.OAUTH_TOKEN_INVALID'){
	            $responce = [
	                'status'=>'error',
	                'message'=>'AuthenticationError: Please reconnect your account',
	            ];
	        }else{
	            $responce = [
	                'status'=>'error',
	                'message'=>'RATE EXCEEDED: Basic Access Daily Reporting Quota',
	            ];
	        }
	    }
	}

	public static function adReportsQuery($googleAdsClient,$account_id,$campaign_id){
		ini_set('memory_limit', -1);
		$googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
		$startDate = date('Y-m-d',strtotime('-2 year'));
		$endDate = date('Y-m-d',strtotime('-1 day'));

        try{

			$query = "SELECT ad_group.id, ad_group_ad.ad.id, ad_group_ad.ad.display_url, ad_group_ad.ad.final_urls, ad_group_ad.ad.expanded_dynamic_search_ad.description, ad_group_ad.ad.expanded_dynamic_search_ad.description2, ad_group_ad.ad.responsive_display_ad.long_headline, ad_group_ad.ad.responsive_display_ad.headlines, ad_group_ad.ad.responsive_display_ad.descriptions, ad_group_ad.ad.responsive_search_ad.headlines, ad_group_ad.ad.responsive_search_ad.descriptions, ad_group_ad.ad.expanded_text_ad.headline_part1, ad_group_ad.ad.expanded_text_ad.headline_part2, ad_group_ad.ad.expanded_text_ad.headline_part3, ad_group_ad.ad.expanded_text_ad.description,ad_group_ad.ad.expanded_text_ad.description2, ad_group_ad.ad.text_ad.description1, ad_group_ad.ad.text_ad.description2, ad_group_ad.ad.text_ad.headline,  ad_group_ad.ad.type, metrics.clicks, metrics.impressions, metrics.ctr, metrics.conversions, metrics.all_conversions,  metrics.average_cpc,  metrics.cost_micros,segments.date FROM ad_group_ad WHERE segments.date BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY segments.date ASC";


			$response = $googleAdsServiceClient->search($account_id, $query);

			$adsArr = $metrics = [];

			$isEmptyResult = true;
	        foreach ($response->iterateAllElements() as $googleAdsRow) {
	            
	            $dateMonth = date('Ym',strtotime($googleAdsRow->getSegments()->getDate()));
		    	$date = date('Ymd',strtotime($googleAdsRow->getSegments()->getDate()));

	            $isEmptyResult = false;
	            $ad = $googleAdsRow->getAdGroupAd()->getAd();
	            $metricsData = $googleAdsRow->getMetrics();
	            $responsiveSearchAdInfo = $ad->getResponsiveSearchAd();	
	       		
	       		$adTypeName = AdType::name($ad->getType());
	       		$adTypeNames[$ad->getType()] = AdType::name($ad->getType());

	            if(AdType::name($ad->getType()) == 'RESPONSIVE_SEARCH_AD'){
	       
	           		$adsArr[$dateMonth][$ad->getId()] = [
	           			'type' => AdType::name($ad->getType()),
	           			'ad_type' => AdType::value($adTypeName),
	            		'heading' => self::convertAdTextAssetsToString($responsiveSearchAdInfo->getHeadlines(),$ad),
	            		'description' => self::convertAdTextAssetsToString($responsiveSearchAdInfo->getDescriptions()),
	            		'url' => self::convertAdUrl($ad->getFinalUrls()),
	            		'display_url' => $ad->getDisplayUrl(),
	            	];
	           	}
	           
	            if(AdType::name($ad->getType()) == 'EXPANDED_TEXT_AD'){
	            	
	            	$adsArr[$dateMonth][$ad->getId()] = [
	            		'type' => AdType::name($ad->getType()),
	            		'ad_type' => AdType::value($adTypeName),
	            		'heading' => $ad->getExpandedTextAd()->getHeadlinePart1() .' | '. $ad->getExpandedTextAd()->getHeadlinePart2() .' | '.$ad->getExpandedTextAd()->getHeadlinePart3(),
	            		'description' => $ad->getExpandedTextAd()->getDescription() .' | '. $ad->getExpandedTextAd()->getDescription2(),
	            		'url' => self::convertAdUrl($ad->getFinalUrls()),
	            		'display_url' => $ad->getDisplayUrl(),
	            	];
		        }

		        if(AdType::name($ad->getType()) == 'TEXT_AD'){
	            
	            	$adsArr[$dateMonth][$ad->getId()] = [
	            		'type' => AdType::name($ad->getType()),
	            		'heading' => $ad->getTextAd()->getHeadline(),
	            		'description' => $ad->getTextAd()->getDescription1() .' | '. $ad->getTextAd()->getDescription2(),
	            		'url' => self::convertAdUrl($ad->getFinalUrls()),
	            		'display_url' => $ad->getDisplayUrl(),
	            	];
		        }

		        if(AdType::name($ad->getType()) == 'RESPONSIVE_DISPLAY_AD'){
		        	$responsiveSearchAdInfo = $ad->getResponsiveDisplayAd();	
		        	$adsArr[$dateMonth][$ad->getId()] = [
	            		'type' => AdType::name($ad->getType()),
	            		'heading' => self::convertAdTextAssetsToString($responsiveSearchAdInfo->getHeadlines(),$ad),
	            		'description' => self::convertAdTextAssetsToString($responsiveSearchAdInfo->getDescriptions()),
	            		'url' => self::convertAdUrl($ad->getFinalUrls()),
	            		'display_url' => $ad->getDisplayUrl(),
	            	];
		        }

		        if(AdType::name($ad->getType()) == 'EXPANDED_DYNAMIC_SEARCH_AD'){

		         	$adsArr[$dateMonth][$ad->getId()] = [
	            		'type' => AdType::name($ad->getType()),
	            		'heading' => "[Dynamically generated headline]",
	            		// 'description' => '', 
	            		'description' => $ad->getExpandedDynamicSearchAd()->getDescription() .' | '. $ad->getExpandedDynamicSearchAd()->getDescription(),
	            		'url' => "[Dynamically generated display URL]",
	            		'display_url' => $ad->getDisplayUrl(),
	            	];
		        }


		        if(AdType::name($ad->getType()) == 'RESPONSIVE_SEARCH_AD' || AdType::name($ad->getType()) == 'EXPANDED_TEXT_AD' || AdType::name($ad->getType()) == 'TEXT_AD' || AdType::name($ad->getType()) == 'RESPONSIVE_DISPLAY_AD' || AdType::name($ad->getType()) == 'EXPANDED_DYNAMIC_SEARCH_AD'){

		        	if(count($metrics) > 0 && isset($metrics[$date])){
			    		$metrics[$ad->getId()][$date]['clicks'] += $metricsData->getClicks();
			    		$metrics[$ad->getId()][$date]['impressions'] += $metricsData->getImpressions();
			    		$metrics[$ad->getId()][$date]['conversions'] += $metricsData->getAllConversions();
			    		$metrics[$ad->getId()][$date]['cost'] += $metricsData->getCostMicros();
			    		$metrics[$ad->getId()][$date]['avgCPC'] += $metricsData->getAverageCpc();
			    		$metrics[$ad->getId()][$date]['ctr'] += $metricsData->getCtr();
		    		
			    	}else{
			    		$metrics[$ad->getId()][$date]['impressions'] = $metricsData->getImpressions();
			    		$metrics[$ad->getId()][$date]['clicks'] = $metricsData->getClicks();
			    		$metrics[$ad->getId()][$date]['ctr'] = $metricsData->getCtr();
			    		$metrics[$ad->getId()][$date]['cost'] = $metricsData->getCostMicros();
			    		$metrics[$ad->getId()][$date]['avgCPC'] = $metricsData->getAverageCpc();
			    		$metrics[$ad->getId()][$date]['conversions'] = $metricsData->getAllConversions();
			    		
			    	}
		        } 

		         
	        }
	       
	        if (!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/ads')) {
	            mkdir(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/ads/', 0777, true);
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/ads/metrics.json', print_r(json_encode($metrics,true),true));
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/ads/list.json', print_r(json_encode($adsArr,true),true));
	        }else{
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/ads/metrics.json', print_r(json_encode($metrics,true),true));
	            if(!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/ads/list.json')){
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/ads/list.json', print_r(json_encode($adsArr,true),true));
	            }else{
	            	file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/ads/list.json', print_r(json_encode($adsArr,true),true));
	            }
	        }

	        $responce = [
	            'status'=>'success',
	        ];
			
		}catch (Exception $e) {
	        $error = $e->getErrors();

	        if($error[0]->getErrorString() == 'AuthenticationError.OAUTH_TOKEN_INVALID'){
	            $responce = [
	                'status'=>'error',
	                'message'=>'AuthenticationError: Please reconnect your account',
	            ];
	        }else{
	            $responce = [
	                'status'=>'error',
	                'message'=>'RATE EXCEEDED: Basic Access Daily Reporting Quota',
	            ];
	        }
	    }
	}

	public static function deviceReportsQuery($googleAdsClient,$account_id,$campaign_id){
		ini_set('memory_limit', -1);
		$googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
		$startDate = date('Y-m-d',strtotime('-2 year'));
		$endDate = date('Y-m-d',strtotime('-1 day'));
		
		try{

			$query = "SELECT metrics.clicks, metrics.impressions, metrics.ctr, metrics.conversions, metrics.all_conversions,  metrics.average_cpc,  metrics.cost_micros,segments.device, segments.date FROM campaign WHERE segments.date BETWEEN '".$startDate."' AND '".$endDate."' ";

			$stream = $googleAdsServiceClient->search($account_id, $query);

			$networks = $devices = $slots = $clickType = $networkMetrics = $deviceMetrics = $slotsMetrics = $clickTypeMetrics = [];
			
		    foreach ($stream->iterateAllElements() as $googleAdsRow) {

		    	$dateMonth = date('Ym',strtotime($googleAdsRow->getSegments()->getDate()));
		    	$date = date('Ymd',strtotime($googleAdsRow->getSegments()->getDate()));

		    	$deviceId = $googleAdsRow->getSegments()->getDevice();
		    	$adNetworkId = $googleAdsRow->getSegments()->getAdNetworkType();
		    	$slotId = $googleAdsRow->getSegments()->getSlot();
		    	$clicksId = $googleAdsRow->getSegments()->getClickType();

		    	$networks[$dateMonth][$adNetworkId] = AdNetworkType::name($adNetworkId);
		    	$devices[$dateMonth][$deviceId] = Device::name($deviceId);
		    	$slots[$dateMonth][$slotId] = Slot::name($slotId);
		    	$clickType[$dateMonth][$clicksId] = ClickType::name($clicksId);

		    	if(count($networkMetrics) > 0 && isset($networkMetrics[$adNetworkId][$date])){
		    		$networkMetrics[$adNetworkId][$date]['clicks'] += $googleAdsRow->getMetrics()->getClicks();
		    		$networkMetrics[$adNetworkId][$date]['impressions'] += $googleAdsRow->getMetrics()->getImpressions();
		    		$networkMetrics[$adNetworkId][$date]['conversions'] += $googleAdsRow->getMetrics()->getAllConversions();
		    		$networkMetrics[$adNetworkId][$date]['cost'] += $googleAdsRow->getMetrics()->getCostMicros();
		    		$networkMetrics[$adNetworkId][$date]['avgCPC'] += $googleAdsRow->getMetrics()->getAverageCpc();
		    		$networkMetrics[$adNetworkId][$date]['ctr'] += $googleAdsRow->getMetrics()->getCtr();

		    	}else{
		    		$networkMetrics[$adNetworkId][$date]['impressions'] = $googleAdsRow->getMetrics()->getImpressions();
		    		$networkMetrics[$adNetworkId][$date]['clicks'] = $googleAdsRow->getMetrics()->getClicks();
		    		$networkMetrics[$adNetworkId][$date]['ctr'] = $googleAdsRow->getMetrics()->getCtr();
		    		$networkMetrics[$adNetworkId][$date]['cost'] = $googleAdsRow->getMetrics()->getCostMicros();
		    		$networkMetrics[$adNetworkId][$date]['avgCPC'] = $googleAdsRow->getMetrics()->getAverageCpc();
		    		$networkMetrics[$adNetworkId][$date]['conversions'] = $googleAdsRow->getMetrics()->getAllConversions();
		    	}

		    	if(count($deviceMetrics) > 0 && isset($deviceMetrics[$deviceId][$date])){
		    		$deviceMetrics[$deviceId][$date]['clicks'] += $googleAdsRow->getMetrics()->getClicks();
		    		$deviceMetrics[$deviceId][$date]['impressions'] += $googleAdsRow->getMetrics()->getImpressions();
		    		$deviceMetrics[$deviceId][$date]['conversions'] += $googleAdsRow->getMetrics()->getAllConversions();
		    		$deviceMetrics[$deviceId][$date]['cost'] += $googleAdsRow->getMetrics()->getCostMicros();
		    		$deviceMetrics[$deviceId][$date]['avgCPC'] += $googleAdsRow->getMetrics()->getAverageCpc();
		    		$deviceMetrics[$deviceId][$date]['ctr'] += $googleAdsRow->getMetrics()->getCtr();

		    	}else{

		    		$deviceMetrics[$deviceId][$date]['impressions'] = $googleAdsRow->getMetrics()->getImpressions();
		    		$deviceMetrics[$deviceId][$date]['clicks'] = $googleAdsRow->getMetrics()->getClicks();
		    		$deviceMetrics[$deviceId][$date]['ctr'] = $googleAdsRow->getMetrics()->getCtr();
		    		$deviceMetrics[$deviceId][$date]['cost'] = $googleAdsRow->getMetrics()->getCostMicros();
		    		$deviceMetrics[$deviceId][$date]['avgCPC'] = $googleAdsRow->getMetrics()->getAverageCpc();
		    		$deviceMetrics[$deviceId][$date]['conversions'] = $googleAdsRow->getMetrics()->getAllConversions();

		    	}
		    		
		    	if(count($slotsMetrics) > 0 && isset($slotsMetrics[$slotId][$date])){
		    		$slotsMetrics[$slotId][$date]['clicks'] += $googleAdsRow->getMetrics()->getClicks();
		    		$slotsMetrics[$slotId][$date]['impressions'] += $googleAdsRow->getMetrics()->getImpressions();
		    		$slotsMetrics[$slotId][$date]['conversions'] += $googleAdsRow->getMetrics()->getAllConversions();
		    		$slotsMetrics[$slotId][$date]['cost'] += $googleAdsRow->getMetrics()->getCostMicros();
		    		$slotsMetrics[$slotId][$date]['avgCPC'] += $googleAdsRow->getMetrics()->getAverageCpc();
		    		$slotsMetrics[$slotId][$date]['ctr'] += $googleAdsRow->getMetrics()->getCtr();

		    	}else{
		    		$slotsMetrics[$slotId][$date]['impressions'] = $googleAdsRow->getMetrics()->getImpressions();
		    		$slotsMetrics[$slotId][$date]['clicks'] = $googleAdsRow->getMetrics()->getClicks();
		    		$slotsMetrics[$slotId][$date]['ctr'] = $googleAdsRow->getMetrics()->getCtr();
		    		$slotsMetrics[$slotId][$date]['cost'] = $googleAdsRow->getMetrics()->getCostMicros();
		    		$slotsMetrics[$slotId][$date]['avgCPC'] = $googleAdsRow->getMetrics()->getAverageCpc();
		    		$slotsMetrics[$slotId][$date]['conversions'] = $googleAdsRow->getMetrics()->getAllConversions();
		    	}

		    	if(count($clickTypeMetrics) > 0 && isset($clickTypeMetrics[$clicksId][$date])){
		    		$clickTypeMetrics[$clicksId][$date]['clicks'] += $googleAdsRow->getMetrics()->getClicks();
		    		$clickTypeMetrics[$clicksId][$date]['impressions'] += $googleAdsRow->getMetrics()->getImpressions();
		    		$clickTypeMetrics[$clicksId][$date]['conversions'] += $googleAdsRow->getMetrics()->getAllConversions();
		    		$clickTypeMetrics[$clicksId][$date]['cost'] += $googleAdsRow->getMetrics()->getCostMicros();
		    		$clickTypeMetrics[$clicksId][$date]['avgCPC'] += $googleAdsRow->getMetrics()->getAverageCpc();
		    		$clickTypeMetrics[$clicksId][$date]['ctr'] += $googleAdsRow->getMetrics()->getCtr();

		    	}else{
		    		$clickTypeMetrics[$clicksId][$date]['impressions'] = $googleAdsRow->getMetrics()->getImpressions();
		    		$clickTypeMetrics[$clicksId][$date]['clicks'] = $googleAdsRow->getMetrics()->getClicks();
		    		$clickTypeMetrics[$clicksId][$date]['ctr'] = $googleAdsRow->getMetrics()->getCtr();
		    		$clickTypeMetrics[$clicksId][$date]['cost'] = $googleAdsRow->getMetrics()->getCostMicros();
		    		$clickTypeMetrics[$clicksId][$date]['avgCPC'] = $googleAdsRow->getMetrics()->getAverageCpc();
		    		$clickTypeMetrics[$clicksId][$date]['conversions'] = $googleAdsRow->getMetrics()->getAllConversions();

		    	}	
		    }

		    if (!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/networks')) {
	            mkdir(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/networks/', 0777, true);
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/networks/metrics.json', print_r(json_encode($networkMetrics,true),true));
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/networks/list.json', print_r(json_encode($networks,true),true));
	        }else{
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/networks/metrics.json', print_r(json_encode($networkMetrics,true),true));
	            if(!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/networks/list.json')){
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/networks/list.json', print_r(json_encode($networks,true),true));
	            }else{
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/networks/list.json', print_r(json_encode($networks,true),true));
	            }
	        }


	        if (!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/device')) {
	            mkdir(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/device/', 0777, true);
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/device/metrics.json', print_r(json_encode($deviceMetrics,true),true));
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/device/list.json', print_r(json_encode($devices,true),true));
	        }else{
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/device/metrics.json', print_r(json_encode($deviceMetrics,true),true));
	            if(!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/device/list.json')){
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/device/list.json', print_r(json_encode($devices,true),true));
	            }else{
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/device/list.json', print_r(json_encode($devices,true),true));
	            }
	        }

	        if (!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/slots')) {
	            mkdir(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/slots/', 0777, true);
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/slots/metrics.json', print_r(json_encode($slotsMetrics,true),true));
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/slots/list.json', print_r(json_encode($slots,true),true));
	        }else{
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/slots/metrics.json', print_r(json_encode($slotsMetrics,true),true));
	            if(!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/slots/list.json')){
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/slots/list.json', print_r(json_encode($slots,true),true));
	            }else{
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/slots/list.json', print_r(json_encode($slots,true),true));
	            }
	        }

	        if (!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/click_type')) {
	            mkdir(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/click_type/', 0777, true);
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/click_type/metrics.json', print_r(json_encode($clickTypeMetrics,true),true));
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/click_type/list.json', print_r(json_encode($clickType,true),true));
	        }else{
	            file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/click_type/metrics.json', print_r(json_encode($clickTypeMetrics,true),true));
	            if(!file_exists(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/click_type/list.json')){
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/click_type/list.json', print_r(json_encode($clickType,true),true));
	            }else{
	                file_put_contents(\config('app.FILE_PATH').'public/googleads/'.$campaign_id.'/click_type/list.json', print_r(json_encode($clickType,true),true));
	            }
	        }

        }catch (Exception $e) {
	        $error = $e->getErrors();

	        if($error[0]->getErrorString() == 'AuthenticationError.OAUTH_TOKEN_INVALID'){
	            $responce = [
	                'status'=>'error',
	                'message'=>'AuthenticationError: Please reconnect your account',
	            ];
	        }else{
	            $responce = [
	                'status'=>'error',
	                'message'=>'RATE EXCEEDED: Basic Access Daily Reporting Quota',
	            ];
	        }
	    }
	}

	public static function convertAdTextAssetsToString(RepeatedField $assets): string
    {
        $result = '';
        foreach ($assets as $key => $asset) {
            
            if($key == 0){
            	$result = $asset->getText();
            }else{
            	$result .= ' | '.$asset->getText();
            }
        }
        return $result;
    }

    public static function convertAdUrl(RepeatedField $assets): string
    {

        $result = '';
        foreach ($assets as $key => $asset) {
            
            if($key == 0){
            	$result = $asset;
            }else{
            	$result .= '/'.$asset;
            }
        }
        return $result;
    }

    private static function getList($campaign_id,$dirname,$durationRange=null){
		

		$getModule = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','google_ads')->first();
		$finalData = $finalDataNew = $finalDataDates = array();
        $url = env('FILE_PATH')."public/googleads/".$campaign_id.'/'.$dirname.'/list.json'; 

        if(file_exists($url)){
	     	$data = file_get_contents($url);
        	$final = json_decode($data,true);
	     }else{
	     	$final = array();
	     }

	    $firstCurrent = date('Y-m-d');
	    $firstDate = date('Y-m-d',strtotime('- 1 day',strtotime($firstCurrent)));
	    
	    if($durationRange <> null && isset($durationRange['duration']) && $durationRange['duration'] <> null){
	    	
	    	$durations = $durationRange['duration'];

	    	$lastEndDate  = date('Y-m-d',strtotime('-'.$durations.' month',strtotime($firstCurrent)));

			if($durations == 7 || $durations == 14){
				$lastEndDate  = date('Y-m-d',strtotime('-'.$durations.' day',strtotime($firstCurrent)));
				for ($i=0; $i <= $durations; $i++) { 
					if($i == 0){
						$start_date = date('Ym',strtotime('-1 day'));
						$lastDate = date('Y-m-d',strtotime('-1 day'));
						$lastcompareDate = date('Ym',strtotime('-1 day'));
					}else{
						$start_date = date('Ym',strtotime('-1 day',strtotime($lastDate)));
						$lastDate = date('Y-m-d',strtotime('-1 day',strtotime($lastDate)));
						
						$lastcompareDate = end($finalDataDates);
					}
				
					if(isset($final[$start_date]) && $final[$start_date] <> null){
						$finalData[$start_date] = $final[$start_date];

						// $finalDataDates[$i] = $start_date;
						if($start_date <> $lastcompareDate){
							$finalDataDates[] = $start_date;
						}

						$finalDataNew += $final[$start_date];
					}
				}
			
			}elseif($durations == 1){

				$startDates = date('Ym',strtotime('-1 day'));
				$lastDate = date('Y-m-01',strtotime('-1 day'));
				$startDate = date('Y-m-d',strtotime('-1 day'));
				$start_date = date('Ym',strtotime('-1 month',strtotime($startDate)));
				
				// echo $lastDate;
				
				if(isset($final[$start_date]) && $final[$start_date] <> null){
					$finalData[$start_date] = $final[$start_date];

					$finalDataDates[] = $startDates;
					$finalDataDates[] = $start_date;
										
					$finalDataNew += $final[$start_date];
				}
			}else{

				for ($i=0; $i <= $durations; $i++) { 
					if($i == 0){
						$start_date = date('Ym',strtotime('-1 day'));
						$lastDate = date('Y-m-01',strtotime('-1 day'));
					}else{
						$start_date = date('Ym',strtotime('-1 month',strtotime($lastDate)));
						$lastDate = date('Y-m-01',strtotime('-1 month',strtotime($lastDate)));
					}
				
					if(isset($final[$start_date]) && $final[$start_date] <> null){
						$finalData[$start_date] = $final[$start_date];

						$finalDataDates[$i] = $start_date;
						$finalDataNew += $final[$start_date];
					}
				}

			}

	    }elseif($getModule <> null){
	    	
			$lastEndDate  = date('Y-m-d',strtotime('-'.$getModule->duration.' month',strtotime($firstCurrent)));

			if($getModule->duration == 7 || $getModule->duration == 14){
				$lastEndDate  = date('Y-m-d',strtotime('-'.$getModule->duration.' day',strtotime($firstCurrent)));
				for ($i=0; $i <= $getModule->duration; $i++) { 
					if($i == 0){
						$start_date = date('Ym',strtotime('-1 day'));
						$lastDate = date('Y-m-d',strtotime('-1 day'));
						$lastcompareDate = date('Ym',strtotime('-1 day'));
					}else{
						$start_date = date('Ym',strtotime('-1 day',strtotime($lastDate)));
						$lastDate = date('Y-m-d',strtotime('-1 day',strtotime($lastDate)));
						
						$lastcompareDate = end($finalDataDates);
					}

					if(isset($final[$start_date]) && $final[$start_date] <> null){
						$finalData[$start_date] = $final[$start_date];

						// $finalDataDates[$i] = $start_date;
						if($start_date <> $lastcompareDate){
							$finalDataDates[] = $start_date;
						}

						$finalDataNew += $final[$start_date];
					}
				}
			
			}elseif($getModule->duration == 1){

				$startDates = date('Ym',strtotime('-1 day'));
				$lastDate = date('Y-m-01',strtotime('-1 day'));
				$startDate = date('Y-m-d',strtotime('-1 day'));
				$start_date = date('Ym',strtotime('-1 month',strtotime($startDate)));
				
				// echo $lastDate;
				
				if(isset($final[$start_date]) && $final[$start_date] <> null){
					$finalData[$start_date] = $final[$start_date];

					$finalDataDates[] = $startDates;
					$finalDataDates[] = $start_date;
										
					$finalDataNew += $final[$start_date];
				}
			}else{

				for ($i=0; $i <= $getModule->duration; $i++) { 
					if($i == 0){
						$start_date = date('Ym',strtotime('-1 day'));
						$lastDate = date('Y-m-01',strtotime('-1 day'));
					}else{
						$start_date = date('Ym',strtotime('-1 month',strtotime($lastDate)));
						$lastDate = date('Y-m-01',strtotime('-1 month',strtotime($lastDate)));
					}
				
					if(isset($final[$start_date]) && $final[$start_date] <> null){
						$finalData[$start_date] = $final[$start_date];

						$finalDataDates[$i] = $start_date;
						$finalDataNew += $final[$start_date];
					}
				}

			}

		}else{
			
			$lastEndDate  = date('Y-m-d',strtotime('-3 month',strtotime($firstCurrent)));
			for ($i=0; $i < 3; $i++) { 
				if($i == 0){
					$start_date = date('Ym',strtotime('-1 day'));
					$lastDate = date('Y-m-01',strtotime('-1 day'));
					
				}else{
					$start_date = date('Ym',strtotime('-1 month',strtotime($lastDate)));
					$lastDate = date('Y-m-01',strtotime('-1 month',strtotime($lastDate)));
					
				}

				if(isset($final[$start_date]) && $final[$start_date] <> null){
					$finalData[$start_date] = $final[$start_date];

					$finalDataDates[$i] = $start_date;
					$finalDataNew += $final[$start_date];
				}
			}

		}
		
		$dateRange = [
			'firstDate' => $firstDate,
			'lastEndDate' => $lastEndDate
		];
		return array('finalDataDates'=>$finalDataDates,'finalDataNew'=>$finalDataNew,'dateRange'=>$dateRange);
	}

	private static function adsCampaignData($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange = null){

			
		$ranges = self::getList($campaign_id,'campaign',$durationRange);
		$end_date = date('Ymd',strtotime('- 1 day'));

		$filterBy = ''; // or Finance etc.
       	
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){
				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
		}else{
			$filterData = $ranges['finalDataNew'];
		}
		
		$startRange = strtotime($ranges['dateRange']['lastEndDate']);
		$endRange = strtotime($ranges['dateRange']['firstDate']);

		$date = [];
		$newData = array();
		
		$urlValues = env('FILE_PATH')."public/googleads/".$campaign_id.'/campaign/metrics.json'; 
		if(file_exists($urlValues)){
		 	$dataValues = file_get_contents($urlValues);
		    $values = json_decode($dataValues,true);
		 }else{
		 	$values = array();
		 }

		 
		foreach ($filterData as $key => $valueData) {
		
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $allConversions = $arrCounter = 0;
			for($i = $startRange; $i <= $endRange; $i = $i+86400){
				$dateArr = date('Ymd',$i);

				if(isset($values[$key][$dateArr])){

						$impressions += $values[$key][$dateArr]['impressions'];
						$clicks += $values[$key][$dateArr]['clicks'];
						$ctr += $values[$key][$dateArr]['ctr'];
						$cost += $values[$key][$dateArr]['cost'];
						$conversions += $values[$key][$dateArr]['conversions'];
						$allConversions += 0;
				}
			}
			$ctrRate = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueData,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctrRate,
				'cost'=>$cost / 1000000,
				'conversions'=>$conversions,
				'all_conversions'=>$allConversions,
			];

 		}   
		
		if($sortType == 'campaign_name'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		

		$collection = collect($newData);
		/*->where('name','LIKE','%Allora%')*/
		/*->sortByDesc('impressions');*/
		/*$sorted->values()->all();*/

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );
		
		return $results;
		
	}


	private static function adsGroupData($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange){

		$fileName = 'adGroup';
		$ranges = self::getList($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){

				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
			
		}else{
			$filterData = $ranges['finalDataNew'];
		}

		$startRange = strtotime($ranges['dateRange']['lastEndDate']);
		$endRange = strtotime($ranges['dateRange']['firstDate']);

		$date = [];
		$newData = array();
		
		$urlValues = env('FILE_PATH')."public/googleads/".$campaign_id.'/'.$fileName.'/metrics.json'; 
		if(file_exists($urlValues)){
		 	$dataValues = file_get_contents($urlValues);
		    $values = json_decode($dataValues,true);
		 }else{
		 	$values = array();
		 }

		 
		foreach ($filterData as $key => $valueData) {
		
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $allConversions = $arrCounter = 0;
			for($i = $startRange; $i <= $endRange; $i = $i+86400){
				$dateArr = date('Ymd',$i);

				if(isset($values[$key][$dateArr])){

						$impressions += $values[$key][$dateArr]['impressions'];
						$clicks += $values[$key][$dateArr]['clicks'];
						$ctr += $values[$key][$dateArr]['ctr'];
						$cost += $values[$key][$dateArr]['cost'];
						$conversions += $values[$key][$dateArr]['conversions'];
						$allConversions += 0;
				}
			}
			$ctrRate = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueData,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctrRate,
				'cost'=>$cost / 1000000,
				'conversions'=>$conversions,
				'all_conversions'=>$allConversions,
			];

 		}
		
		if($sortType == 'ad_group'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		// $collection = collect($newData)
		// /*->where('name','LIKE','%Allora%')*/
		// ->sortByDesc('impressions');
		// /*$sorted->values()->all();*/

		// $page = request()->has('page') ? request('page') : 1;

		// // Set default per page
		// $perPage = request()->has('per_page') ? request('per_page') : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );

		return $results;
		
	}

	private static function adKeywordsData($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange){
		ini_set('memory_limit', -1);
		$fileName = 'keywords';
		$ranges = self::getList($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){
				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
		}else{
			$filterData = $ranges['finalDataNew'];
		}
		
		$startRange = strtotime($ranges['dateRange']['lastEndDate']);
		$endRange = strtotime($ranges['dateRange']['firstDate']);

		$date = [];
		$newData = array();
		
		$urlValues = env('FILE_PATH')."public/googleads/".$campaign_id.'/'.$fileName.'/metrics.json'; 
		if(file_exists($urlValues)){
			$dataValues = file_get_contents($urlValues);
		    $values = json_decode($dataValues,true);
		 }else{
		 	$values = array();
		 }

		 
		foreach ($filterData as $key => $valueData) {
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $allConversions = $arrCounter = 0;
			for($i = $startRange; $i <= $endRange; $i = $i+86400){
				$dateArr = date('Ymd',$i);

				if(isset($values[$key][$dateArr])){

						$impressions += $values[$key][$dateArr]['impressions'];
						$clicks += $values[$key][$dateArr]['clicks'];
						$ctr += $values[$key][$dateArr]['ctr'];
						$cost += $values[$key][$dateArr]['cost'];
						$conversions += $values[$key][$dateArr]['conversions'];
						$allConversions += 0;
				}
			}
			$ctrRate = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueData,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctrRate,
				'cost'=>$cost / 1000000,
				'conversions'=>$conversions,
				'all_conversions'=>$allConversions,
			];

 		}

		if($sortType == 'keywords'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );
		return $results;
		
	}

	private static function adsPerformanceClickType($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange){
		$fileName = 'click_type';
		$ranges = self::getList($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){

				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
			
		}else{
			$filterData = $ranges['finalDataNew'];
		}

		$startRange = strtotime($ranges['dateRange']['lastEndDate']);
		$endRange = strtotime($ranges['dateRange']['firstDate']);

		$date = [];
		$newData = array();
		
		$urlValues = env('FILE_PATH')."public/googleads/".$campaign_id.'/'.$fileName.'/metrics.json'; 
		if(file_exists($urlValues)){
		 	$dataValues = file_get_contents($urlValues);
		    $values = json_decode($dataValues,true);
		 }else{
		 	$values = array();
		 }

		 
		foreach ($filterData as $key => $valueData) {
		
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $allConversions = $arrCounter = 0;
			for($i = $startRange; $i <= $endRange; $i = $i+86400){
				$dateArr = date('Ymd',$i);

				if(isset($values[$key][$dateArr])){

						$impressions += $values[$key][$dateArr]['impressions'];
						$clicks += $values[$key][$dateArr]['clicks'];
						$ctr += $values[$key][$dateArr]['ctr'];
						$cost += $values[$key][$dateArr]['cost'];
						$conversions += $values[$key][$dateArr]['conversions'];
						$allConversions += 0;
				}
			}
			$ctrRate = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueData,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctrRate,
				'cost'=>$cost / 1000000,
				'conversions'=>$conversions,
				'all_conversions'=>$allConversions,
			];

 		}
		
		if($sortType == 'click_type'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		// $collection = collect($newData)
		// /*->where('name','LIKE','%Allora%')*/
		// ->sortByDesc('impressions');
		// /*$sorted->values()->all();*/

		// $page = request()->has('page') ? request('page') : 1;

		// // Set default per page
		// $perPage = request()->has('per_page') ? request('per_page') : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );

		return $results;
	}

	private static function adsPerformanceNetwork($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange){

		$fileName = 'networks';
		$ranges = self::getList($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){

				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
			
		}else{
			$filterData = $ranges['finalDataNew'];
		}

		$startRange = strtotime($ranges['dateRange']['lastEndDate']);
		$endRange = strtotime($ranges['dateRange']['firstDate']);

		$date = [];
		$newData = array();
		
		$urlValues = env('FILE_PATH')."public/googleads/".$campaign_id.'/'.$fileName.'/metrics.json'; 
		if(file_exists($urlValues)){
		 	$dataValues = file_get_contents($urlValues);
		    $values = json_decode($dataValues,true);
		 }else{
		 	$values = array();
		 }

		 
		foreach ($filterData as $key => $valueData) {
		
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $allConversions = $arrCounter = 0;
			for($i = $startRange; $i <= $endRange; $i = $i+86400){
				$dateArr = date('Ymd',$i);

				if(isset($values[$key][$dateArr])){

						$impressions += $values[$key][$dateArr]['impressions'];
						$clicks += $values[$key][$dateArr]['clicks'];
						$ctr += $values[$key][$dateArr]['ctr'];
						$cost += $values[$key][$dateArr]['cost'];
						$conversions += $values[$key][$dateArr]['conversions'];
						$allConversions += 0;
				}
			}
			$ctrRate = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueData,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctrRate,
				'cost'=>$cost / 1000000,
				'conversions'=>$conversions,
				'all_conversions'=>$allConversions,
			];

 		}

		if($sortType == 'publisher_by_network'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;
		
		// $collection = collect($newData)
		// /*->where('name','LIKE','%Allora%')*/
		// ->sortByDesc('impressions');
		// /*$sorted->values()->all();*/

		// $page = request()->has('page') ? request('page') : 1;

		// // Set default per page
		// $perPage = request()->has('per_page') ? request('per_page') : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );

		return $results;
		
	}

	private static  function adsPerformanceDevice($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange){
		$fileName = 'device';
		$ranges = self::getList($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){

				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
			
		}else{
			$filterData = $ranges['finalDataNew'];
		}

		$startRange = strtotime($ranges['dateRange']['lastEndDate']);
		$endRange = strtotime($ranges['dateRange']['firstDate']);

		$date = [];
		$newData = array();
		
		$urlValues = env('FILE_PATH')."public/googleads/".$campaign_id.'/'.$fileName.'/metrics.json'; 
		if(file_exists($urlValues)){
		 	$dataValues = file_get_contents($urlValues);
		    $values = json_decode($dataValues,true);
		 }else{
		 	$values = array();
		 }

		 
		foreach ($filterData as $key => $valueData) {
		
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $allConversions = $arrCounter = 0;
			for($i = $startRange; $i <= $endRange; $i = $i+86400){
				$dateArr = date('Ymd',$i);

				if(isset($values[$key][$dateArr])){

						$impressions += $values[$key][$dateArr]['impressions'];
						$clicks += $values[$key][$dateArr]['clicks'];
						$ctr += $values[$key][$dateArr]['ctr'];
						$cost += $values[$key][$dateArr]['cost'];
						$conversions += $values[$key][$dateArr]['conversions'];
						$allConversions += 0;
				}
			}
			$ctrRate = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueData,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctrRate,
				'cost'=>$cost / 1000000,
				'conversions'=>$conversions,
				'all_conversions'=>$allConversions,
			];

 		}
		
		if($sortType == 'device'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		// $collection = collect($newData)
		// /*->where('name','LIKE','%Allora%')*/
		// ->sortByDesc('impressions');
		// /*$sorted->values()->all();*/

		// $page = request()->has('page') ? request('page') : 1;

		// // Set default per page
		// $perPage = request()->has('per_page') ? request('per_page') : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );

		return $results;
	}

	private static function adPerformanceAdSlot($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange=null){
		$fileName = 'slots';
		$ranges = self::getList($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){

				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
			
		}else{
			$filterData = $ranges['finalDataNew'];
		}

		$startRange = strtotime($ranges['dateRange']['lastEndDate']);
		$endRange = strtotime($ranges['dateRange']['firstDate']);

		$date = [];
		$newData = array();
		
		$urlValues = env('FILE_PATH')."public/googleads/".$campaign_id.'/'.$fileName.'/metrics.json'; 
		if(file_exists($urlValues)){
		 	$dataValues = file_get_contents($urlValues);
		    $values = json_decode($dataValues,true);
		 }else{
		 	$values = array();
		 }

		 
		foreach ($filterData as $key => $valueData) {
		
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $allConversions = $arrCounter = 0;
			for($i = $startRange; $i <= $endRange; $i = $i+86400){
				$dateArr = date('Ymd',$i);

				if(isset($values[$key][$dateArr])){

						$impressions += $values[$key][$dateArr]['impressions'];
						$clicks += $values[$key][$dateArr]['clicks'];
						$ctr += $values[$key][$dateArr]['ctr'];
						$cost += $values[$key][$dateArr]['cost'];
						$conversions += $values[$key][$dateArr]['conversions'];
						$allConversions += 0;
				}
			}
			$ctrRate = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueData,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctrRate,
				'cost'=>$cost / 1000000,
				'conversions'=>$conversions,
				'all_conversions'=>$allConversions,
			];

 		}
		
		if($sortType == 'ad_slot'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;
	

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );

		return $results;
	}

	private static function addiionalData($campaign_id)
	{
		$fileName = 'ads';
		$urlValues = env('FILE_PATH')."public/adwords/".$campaign_id.'/'.$fileName.'/aditional.json'; 
		if(file_exists($urlValues)){
			$dataValues = file_get_contents($urlValues);
        	$values = json_decode($dataValues,true);
		}else{
			$values = array();
		}
        

        return $values;
   	}

	private static function adData($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange=null){

		$fileName = 'ads';
		$ranges = self::getList($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		$filterBy = ''; // or Finance etc.
       	
       	$additionalData = self::addiionalData($campaign_id);
       	
       if($query <> ''){

			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query,$additionalData){

				if (strpos($additionalData[$k]['ad_type'], $query) !== false) {
					
				    return $k;
				}
				if (strpos($additionalData[$k]['headlines'], $query) !== false) {
					return $k;
				}
				if (strpos($additionalData[$k]['displayurl'], $query) !== false) {
					return $k;
				}
				if (strpos($additionalData[$k]['discription'], $query) !== false) {
					return $k;
				}
				
			},ARRAY_FILTER_USE_BOTH);
			

		}else{
			$filterData = $ranges['finalDataNew'];
		}

		$startRange = strtotime($ranges['dateRange']['lastEndDate']);
		$endRange = strtotime($ranges['dateRange']['firstDate']);

		$date = [];
		$newData = array();
		
		$urlValues = env('FILE_PATH')."public/googleads/".$campaign_id.'/'.$fileName.'/metrics.json'; 
		if(file_exists($urlValues)){
		 	$dataValues = file_get_contents($urlValues);
		    $values = json_decode($dataValues,true);
		 }else{
		 	$values = array();
		 }

		foreach ($filterData as $key => $valueData) {
			
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $allConversions = $arrCounter = 0;
			for($i = $startRange; $i <= $endRange; $i = $i+86400){
				$dateArr = date('Ymd',$i);

				if(isset($values[$key][$dateArr])){

						$impressions += $values[$key][$dateArr]['impressions'];
						$clicks += $values[$key][$dateArr]['clicks'];
						$ctr += $values[$key][$dateArr]['ctr'];
						$cost += $values[$key][$dateArr]['cost'];
						$conversions += $values[$key][$dateArr]['conversions'];
						$allConversions += 0;
				}
			}
			$ctrRate = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueData['heading'],
				'displayurl'=>$valueData['url'],
				'description'=>$valueData['description'],
				'ad_type'=>$valueData['type'],
				'adId'=>$key,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctrRate,
				'cost'=>$cost / 1000000,
				'conversions'=>$conversions,
			];

 		}

		if($sortType == 'ad'){
			$keys = array_column($newData, 'displayurl');
		}else if($sortType == 'ad_type'){
			$keys = array_column($newData, 'ad_type');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );
		
		return $results;
	}


}
