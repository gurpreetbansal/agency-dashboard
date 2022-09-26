<?php

namespace App\Http\Controllers\Vendor\goolgeAd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;
use App\SemrushUserAccount;
use App\GoogleAdsCustomer;
use App\GoogleAnalyticsUsers;
use App\ModuleByDateRange;
use App\Traits\GoogleAdsTrait;

/*Google ads*/
use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Message\Response;
use React\Http\Server;
use UnexpectedValueException;

use GetOpt\GetOpt;
use App\Http\Controllers\Vendor\ArgumentNames;
use App\Http\Controllers\Vendor\ArgumentParser;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsException;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsServerStreamDecorator;
use Google\Ads\GoogleAds\V10\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\V10\Resources\CustomerClient;
use Google\Ads\GoogleAds\V10\Services\CustomerServiceClient;
use Google\Ads\GoogleAds\V10\Services\GoogleAdsRow;
use Google\ApiCore\ApiException;

use Google\Ads\GoogleAds\Util\V10\ResourceNames;
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
use Psr\Log\LogLevel;
use Google\Ads\GoogleAds\Lib\V10\LoggerFactory;

class GoolgeAdController extends Controller {

	use GoogleAdsTrait; 

	public function campaignSave(Request $request){

		$user_id = User::get_parent_user_id(Auth::user()->id); 
		
		$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)
		->update([
			'google_ads_campaign_id'=>$request->account,
			'google_ads_id'=>$request->email
		]);

		$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();
		
		if($if_Exist->status == 0  && $if_Exist->google_ads_id <> null && $if_Exist->google_ads_campaign_id <> null){
			$acc_id = $if_Exist->google_ads_campaign_id;

			$this->googleAdlogs($request);
			ModuleByDateRange::set_default_month_range($request->campaign_id,$user_id);

			if($update) {			
				$response['status'] = 'success';
				// GoogleAdsCustomer::log_adwords_data($request->campaign_id);
			} else if($acc_id == $request->account){
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error'; 
			}
		}else{
			$response['status'] = 'error';
		}				
		return  response()->json($response);
	}

	public function campaignRefresh(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		
		$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();

		$acc_id = $if_Exist->google_ads_campaign_id;
		if($if_Exist->status == 0  && $if_Exist->google_ads_id <> null && $if_Exist->google_ads_campaign_id <> null){
			
			$data = $this->googleAdlogs($request);

			if($data['status'] == 'success'){
				$response['status'] = 'success';
				$response['account_id'] = $if_Exist->google_ads_id;
			}else{
				$data['account_id'] = $if_Exist->google_ads_id;
				$response = $data;
			}

		}else{
			$response['status'] = 'error'; 
		}
		return  response()->json($response);
	}	


	private static function currentDuration($campaign_id,$account_id,$duration = null){
		
		$durationMonthDay = " months";
		if($duration <> null){
			$duration =$default_duration =  $duration;
			
			if($duration == 7 || $duration == 14){
				$duration = $duration;
				$lapse ='+0 day';
				$durationMonthDay = " days";
			}elseif($duration <= 3){
				$lapse ='+6 days';
				
				$start_date = date('Y-m-d',strtotime('-'.$duration.' months'));
				$end_date = date('Y-m-d');
				$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
			}elseif($duration >= 6 && $duration <= 12){
				$duration = $duration;
				$lapse ='+1 month';
			}elseif($duration == 24){
				$duration = $duration/3;
				$lapse = '+3 month';
			}
			
		}else{
			$data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','google_ads')->first();
			
			if(!empty($data)){
				

				$default_duration = $data->duration;

				if($data->duration == 7 || $data->duration == 14){
					$lapse ='+0 day';
					$durationMonthDay = " days";
					$start_date = date('Y-m-d',strtotime('-'.$data->duration.' days'));
					$end_date = date('Y-m-d');
					$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				}elseif($data->duration <= 3){
					$lapse ='+6 days';

					$start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
					$end_date = date('Y-m-d');
					$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				}elseif($data->duration >= 6 && $data->duration <= 12){
					$duration = $data->duration;
					$lapse ='+1 month';
				}elseif($data->duration == 24){
					$duration = $data->duration/3;
					$lapse = '+3 month';
				}
			}else{
				$duration =$default_duration =  3;
				$lapse = '+6 days';
				// $lapse = '+6 days';
				$start_date = date('Y-m-d',strtotime('-3 months'));
				$end_date = date('Y-m-d');
				$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
			}
		}

		
		for($i=1;$i<=$duration;$i++){
			if($i==1){	
				$start_date = date('Y-m-d',strtotime('-'.$default_duration. $durationMonthDay));
				$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				
			}else{
				$start_date = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
				$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
			}
			if($end_date > date('Y-m-d')){
				
				$end_date = date('Y-m-d',strtotime('-1 day'));
			}
			$res[$i]['start_date'] = $start_date;
			$res[$i]['end_date'] = $end_date;
		}		
		return $res;
		
	}

	private static function compareDuration($duration = null){

		$durationMonthDay = " month";
		
		if($duration <> null){
			
			$default_duration = $duration*2;

			if($duration == 7 || $duration == 14){
				$lapse ='+0 day';
				$durationMonthDay = " days";

				$dates = date('Y-m-d',strtotime('-'.$default_duration.' days'));
				$start_date = date('Y-m-d',strtotime($dates));
				$end_date = date('Y-m-d',strtotime('+'.$duration.' days',strtotime($start_date)));
				
			}elseif($duration <= 3){
				$lapse ='+6 days';

				$dates = date('Y-m-d',strtotime('-'.$default_duration.' months'));
				$start_date = date('Y-m-d',strtotime('-1 day',strtotime($dates)));
				$end_date = date('Y-m-d',strtotime('+'.$duration.' months',strtotime($start_date)));
				$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
			
			}elseif($duration >= 6 && $duration <= 12){
				$duration = $duration;
				$lapse ='+1 month';

				$dates = date('Y-m-d',strtotime('-'.$default_duration.' months'));
				$start_date = date('Y-m-d',strtotime('-1 day',strtotime($dates)));
				$end_date = date('Y-m-d',strtotime('+'.$duration.' months',strtotime($start_date)));
			}elseif($duration == 24){
				$duration = $duration/3;
				$lapse = '+3 month';

				$dates = date('Y-m-d',strtotime('-'.$default_duration.' months'));
				$start_date = date('Y-m-d',strtotime('-1 day',strtotime($dates)));
				$end_date = date('Y-m-d',strtotime('+'.$duration.' months',strtotime($start_date)));
			}

		}else{
			$duration =  3;
			$default_duration =  6;
			$lapse = '+6 days';
			$start_date = date('Y-m-d',strtotime('-6 months'));
			$end_date = date('Y-m-d',strtotime('+'.$duration.' months',strtotime($start_date)));
			
		}
		
		$res = array();
		
		for($i=1;$i<=$duration;$i++){
			if($i==1){	
				$start_date = date('Y-m-d',strtotime($start_date));
				$end_dates = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				
			}else{
				$start_date = date('Y-m-d',strtotime('+1 day',strtotime($end_dates)));
				$end_dates = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
			}

			if($end_dates > $end_date){
				
				$end_dates = date('Y-m-d',strtotime($end_date));
			}

			$res[$i]['start_date'] = $start_date;
			$res[$i]['end_date'] = $end_dates;
		}

		return $res;
		
	}

	public function campaignSummary(Request $request){

		$label = $result = array();

		$data = ModuleByDateRange::select('duration','status')->where('request_id',$request['campaign_id'])->where('module','google_ads')->first();
       	
       	$ifExist = SemrushUserAccount::where('id',$request['campaign_id'])->first();

       	$adsCurrencies = GoogleAdsCustomer::where('id',$ifExist->google_ads_campaign_id)->first();
       	
       	if($request->duration <> null){

			$duration = $request->duration;
			$durationOld = $request->duration * 2;

		}else{

			if(!empty($data)){
				$duration = $data->duration;
				$durationOld = $data->duration * 2;
			}else{
				$duration = 3;
				$durationOld = 3 * 2;
			}
		}

		if($request->compare <> null  && $request->compare <> ''){
			$compareStatus =  $request->compare;
		}else{
			$compareStatus =  $data <> null ? $data->status : 0 ;
		}

		$dates = self::currentDuration($request['campaign_id'],$request['account_id'],$duration);
		$urlValues = env('FILE_PATH')."public/googleads/".$request['campaign_id'].'/graphs/overview.json'; 
	    if(file_exists($urlValues)){
	     	$dataValues = file_get_contents($urlValues);
	        $values = json_decode($dataValues,true);
	    }else{
	    	$values = array();
	    } 

	    $previousDuration = self::compareDuration($duration);
        $counterfor = $counter = $impressionCount = $clickCount  = $ctrCount = $costCount = $conversionsCount = $average_cpc = $conversion_rate = $cpc_rate = 0;
        
        foreach ($dates as $keyDate => $valueDate) {

	    	if($counter == 0){
	    		$firstDate = $valueDate['start_date'];
	    		$rangeStart = $valueDate['start_date'];
	    	}
	    	$lastDate = $valueDate['end_date'];

	        $begin = strtotime($valueDate['start_date']);
	        $end = strtotime($valueDate['end_date']);
	        $impressions = $clicks = $ctr = $cost = $conversions = $cpcRate = 0;
	        for($i = $begin; $i <= $end; $i = $i+86400){
	        	
	        	$impressions += isset($values[date('Ymd',$i)]['impressions']) ?$values[date('Ymd',$i)]['impressions']:0;
				$clicks += isset($values[date('Ymd',$i)]['clicks']) ? $values[date('Ymd',$i)]['clicks']:0;
				$cost += isset($values[date('Ymd',$i)]['cost']) ? $values[date('Ymd',$i)]['cost']:0;
				$conversions += isset($values[date('Ymd',$i)]['conversions']) ? $values[date('Ymd',$i)]['conversions']:0;
				
				// $cpcRate += isset($values[date('Ymd',$i)]['cost_per_conversion']) ? $values[date('Ymd',$i)]['cost_per_conversion']:0;


	        	$impressionCount += isset($values[date('Ymd',$i)]['impressions']) ?$values[date('Ymd',$i)]['impressions']:0;
				$clickCount += isset($values[date('Ymd',$i)]['clicks']) ? $values[date('Ymd',$i)]['clicks']:0;
				$costCount += isset($values[date('Ymd',$i)]['cost']) ? $values[date('Ymd',$i)]['cost']:0;
				$conversionsCount += isset($values[date('Ymd',$i)]['conversions']) ? $values[date('Ymd',$i)]['conversions']:0;

				$res['summaryGraph']['date_range'][$counterfor] = date('M d, Y',$i);
				$res['summaryGraph']['impressions'][$counterfor] = isset($values[date('Ymd',$i)]['impressions']) ?$values[date('Ymd',$i)]['impressions']:0;
				$res['summaryGraph']['clicks'][$counterfor] = isset($values[date('Ymd',$i)]['clicks']) ? $values[date('Ymd',$i)]['clicks']:0;
				$res['summaryGraph']['conversions'][$counterfor] = isset($values[date('Ymd',$i)]['conversions']) ? $values[date('Ymd',$i)]['conversions']:0;
				$res['summaryGraph']['compare'] = $data <> null ? $data->status : 0 ; 

				$res['performanceGraph']['date_range'][$counterfor] = date('M d, Y',$i);
				$res['performanceGraph']['cost'][$counterfor] = isset($values[date('Ymd',$i)]['cost']) ? (float)number_format($values[date('Ymd',$i)]['cost'], 2, '.', '') : 0;
				$res['performanceGraph']['cpc'][$counterfor] = isset($values[date('Ymd',$i)]['average_cpc']) ? (float)number_format($values[date('Ymd',$i)]['average_cpc'], 2, '.', '') : 0;
				$res['performanceGraph']['averagecpm'][$counterfor] = isset($values[date('Ymd',$i)]['average_cpm']) ? (float)number_format($values[date('Ymd',$i)]['average_cpm'], 2, '.', '') : 0;


				

				$res['performanceGraph']['revenue_per_click'][$counterfor] = isset($values[date('Ymd',$i)]['conversion_value']) && $values[date('Ymd',$i)]['conversion_value'] > 0  ? (float)number_format($values[date('Ymd',$i)]['conversion_value']/$values[date('Ymd',$i)]['clicks'], 2, '.', '') : 0;

				$res['performanceGraph']['total_value'][$counterfor] = isset($values[date('Ymd',$i)]['conversion_value']) ? (float)number_format($values[date('Ymd',$i)]['conversion_value'], 2, '.', '') : 0;
				$res['performanceGraph']['compare'] = $data <> null ? $data->status : 0 ;

				$counterfor++;
			}

		
			$ctrs = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;
			$avgCpc = $cost > 0 && $clicks > 0 ?$cost/$clicks:0 ;
			$conversionRate = $conversions > 0 && $clicks > 0 ?($conversions/$clicks)*100:0 ;
			$cpcRate = $cost <> 0 && $conversions <> 0 ? ($cost/$conversions)/1000000 : 0;

			$ctrCount += $ctrs;
			$average_cpc += $avgCpc;
			$conversion_rate += $conversionRate;
			/*$cpc_rate += $cpcRating;*/

			$costs = $cost / 1000000;

			$res['from_datelabel'][$counter] = date('M d, Y',$end);
			$res['impressions'][$counter] = (float)number_format($impressions, 2, '.', '');
			$res['clicks'][$counter] = (float)number_format($clicks, 2, '.', '');
			$res['ctr'][$counter] = (float)number_format($ctrs, 2, '.', ''); 
			$res['cost'][$counter] = (float)number_format($costs, 2, '.', '');
			$res['conversions'][$counter] = (float)number_format($conversions, 2, '.', '');
			$res['average_cpc'][$counter] = (float)number_format(($avgCpc/1000000), 2, '.', '');
			$res['conversion_rate'][$counter] = (float)number_format($conversionRate, 2, '.', '');
			$res['cpc_rate'][$counter] = (float)number_format($cpcRate, 2, '.', '');

	    	$counter++;
    	}

    	$ctrCounter = $impressionCount <> 0 ? ($clickCount/$impressionCount)*100 : 0 ;
        $conversionCounter = $conversionsCount > 0 && $clickCount > 0 ?($conversionsCount/$clickCount)*100:0 ;
        $cpcRateCounter = $costCount <> 0 && $conversionsCount <> 0 ? ($costCount/$conversionsCount)/1000000 : 0;
        $summaryCost = $costCount <> 0 ? ($costCount/1000000) : 0;

        $previousSummary = self::compareSummay($previousDuration,$values);
        
       
        $res['summary']['impressionCount'] = (float)number_format($impressionCount, 2, '.', '');
        $res['summary']['clickCount'] = (float)number_format($clickCount, 2, '.', '');
        $res['summary']['ctrCount'] = (float)number_format($ctrCounter, 2, '.', '');
        $res['summary']['costCount'] = (float)number_format($summaryCost, 2, '.', '');
        $res['summary']['conversionsCount'] = (float)number_format($conversionsCount, 2, '.', '');
        $res['summary']['average_cpc'] = (float)number_format(($average_cpc/$counter)/1000000, 2, '.', '');
        $res['summary']['conversion_rate'] = (float)number_format($conversionCounter, 2, '.', '');
        $res['summary']['cpc_rate'] = (float)number_format($cpcRateCounter, 2, '.', '');
        $res['summaryPrevious'] = $previousSummary;

        $res['summaryGraph']['impressions_previous'] = $previousSummary['impressions_previous'];
        $res['summaryGraph']['clicks_previous'] = $previousSummary['clicks_previous'];
        $res['summaryGraph']['conversions_previous'] = $previousSummary['conversions_previous'];
        
        $res['performanceGraph']['cost_previous'] = $previousSummary['cost_previous'];
        $res['performanceGraph']['cpc_previous'] = $previousSummary['cpc_previous'];
        $res['performanceGraph']['averagecpm_previous'] = $previousSummary['averagecpm_previous'];
        $res['performanceGraph']['revenue_per_click_previous'] = $previousSummary['revenue_per_click_previous'];
        $res['performanceGraph']['total_value_previous'] = $previousSummary['total_value_previous'];


        if(isset($impressionCount) && isset($previousSummary['impressionCount'])){
			$impressions_percentage = GoogleAdsCustomer::calculate_percentage($impressionCount,$previousSummary['impressionCount']);
		}elseif(!isset($impressionCount) && isset($previousSummary['impressionCount'])){
			$impressions_percentage = '-100';
		}elseif(isset($impressionCount) && !isset($previousSummary['impressionCount'])){
			$impressions_percentage = '0';
		}else{
			$impressions_percentage = '0';
		}

		if(isset($costCount) && isset($previousSummary['costCount'])){
			$costs_percentage = GoogleAdsCustomer::calculate_percentage($summaryCost,$previousSummary['costCount']);
		}elseif(!isset($costCount) && isset($previousSummary['costCount'])){
			$costs_percentage = '-100';
		}elseif(isset($costCount) && !isset($previousSummary['costCount'])){
			$costs_percentage = '0';
		}else{
			$costs_percentage = '0';
		}

		if(isset($clickCount) && isset($previousSummary['clickCount'])){
			$clicks_percentage = GoogleAdsCustomer::calculate_percentage($clickCount,$previousSummary['clickCount']);
		}elseif(!isset($clickCount) && isset($previousSummary['clickCount'])){
			$clicks_percentage = '-100';
		}elseif(isset($clickCount) && !isset($previousSummary['clickCount'])){
			$clicks_percentage = '0';
		}else{
			$clicks_percentage = '0';
		}

		if(isset($res['summary']['average_cpc']) && isset($previousSummary['average_cpc'])){
			$average_cpc_percentage = GoogleAdsCustomer::calculate_percentage($res['summary']['average_cpc'],$previousSummary['average_cpc']);
		}elseif(!isset($res['summary']['average_cpc']) && isset($previousSummary['average_cpc'])){
			$average_cpc_percentage = '-100';
		}elseif(isset($res['summary']['average_cpc']) && !isset($previousSummary['average_cpc'])){
			$average_cpc_percentage = '0';
		}else{
			$average_cpc_percentage = '0';
		}

		if(isset($ctrCounter) && isset($previousSummary['ctrCount'])){
			$ctr_percentage = GoogleAdsCustomer::calculate_percentage($ctrCounter,$previousSummary['ctrCount']);
		}elseif(!isset($ctrCounter) && isset($previousSummary['ctrCount'])){
			$ctr_percentage = '-100';
		}elseif(isset($ctrCounter) && !isset($previousSummary['ctrCount'])){
			$ctr_percentage = '0';
		}else{
			$ctr_percentage = '0';
		}
		

		if(isset($conversionsCount) && isset($previousSummary['conversionsCount'])){
			$conversions_percentage = GoogleAdsCustomer::calculate_percentage($conversionsCount,$previousSummary['conversionsCount']);
		}elseif(!isset($conversionsCount) && isset($previousSummary['conversionsCount'])){
			$conversions_percentage = '-100';
		}elseif(isset($conversionsCount) && !isset($previousSummary['conversionsCount'])){
			$conversions_percentage = '0';
		}else{
			$conversions_percentage = '0';
		}


		if(isset($conversionCounter) && isset($previousSummary['conversion_rate'])){
			$conversion_rates_percentage = GoogleAdsCustomer::calculate_percentage($conversionCounter,$previousSummary['conversion_rate']);
		}elseif(!isset($conversionCounter) && isset($previousSummary['conversion_rate'])){
			$conversion_rates_percentage = '-100';
		}elseif(isset($conversionCounter) && !isset($previousSummary['conversion_rate'])){
			$conversion_rates_percentage = '0';
		}else{
			$conversion_rates_percentage = '0';
		}


		if(isset($cpcRateCounter) && isset($previousSummary['cpc_rate'])){
			$cost_per_conversions_percentage = GoogleAdsCustomer::calculate_percentage($cpcRateCounter,$previousSummary['cpc_rate']);
		}elseif(!isset($cpcRateCounter) && isset($previousSummary['cpc_rate'])){
			$cost_per_conversions_percentage = '-100';
		}elseif(isset($cpcRateCounter) && !isset($previousSummary['cpc_rate'])){
			$cost_per_conversions_percentage = '0';
		}else{
			$cost_per_conversions_percentage = '0';
		}


		$res['impressions_percentage'] = $impressions_percentage;
		$res['costs_percentage'] = $costs_percentage;
		$res['clicks_percentage'] = $clicks_percentage;
		$res['average_cpc_percentage'] = $average_cpc_percentage;
		$res['ctr_percentage'] = $ctr_percentage;
		$res['conversions_percentage'] = $conversions_percentage;
		$res['conversion_rates_percentage'] = $conversion_rates_percentage;
		$res['cost_per_conversions_percentage'] = $cost_per_conversions_percentage;
		$res['compare'] = $compareStatus;
		$res['firstDate'] = $firstDate;
		$res['lastDate'] = $lastDate;
		if($compareStatus == 1){
			$res['range'] = date('M d Y',strtotime($rangeStart)). ' - ' . date('M d Y',strtotime($lastDate)) .' (compared to '. date('M d Y',strtotime($previousSummary['firstDate'])). ' - ' . date('M d Y',strtotime($previousSummary['lastDate'])).')';
		}else{
			$res['range'] = date('M d Y',strtotime($rangeStart)). ' - ' . date('M d Y',strtotime($lastDate));
		}
		$res['currencyCode'] = @$adsCurrencies->currencyCode <> null ? $adsCurrencies->currencyCode : 'USD';
		
		return response()->json($res);
		
	}

	private static function compareSummay($dates,$values){

		$counterfor = $counter = $impressionCount = $clickCount  = $ctrCount = $costCount = $conversionsCount = $average_cpc = $conversion_rate = $cpc_rate= $cpcRate = 0;

       
        foreach ($dates as $keyDate => $valueDate) {
        	if($counter == 0){
        		$rangeStart = $valueDate['start_date'];
        	}
        	$lastDate = $valueDate['end_date'];

	        $begin = strtotime($valueDate['start_date']);
	        $end = strtotime($valueDate['end_date']);
	        $impressions = $clicks = $ctr = $cost = $conversions = 0;
	        for($i = $begin; $i <= $end; $i = $i+86400){
	        	
	        	$impressions += isset($values[date('Ymd',$i)]['impressions']) ?$values[date('Ymd',$i)]['impressions']:0;
				$cost += isset($values[date('Ymd',$i)]['cost']) ? $values[date('Ymd',$i)]['cost']:0;
				$clicks += isset($values[date('Ymd',$i)]['clicks']) ? $values[date('Ymd',$i)]['clicks']:0;
				$conversions += isset($values[date('Ymd',$i)]['conversions']) ? $values[date('Ymd',$i)]['conversions']:0;
				$cpcRate += isset($values[date('Ymd',$i)]['cost_per_conversion']) ? $values[date('Ymd',$i)]['cost_per_conversion']:0;

	        	$impressionCount += isset($values[date('Ymd',$i)]['impressions']) ?$values[date('Ymd',$i)]['impressions']:0;
				$clickCount += isset($values[date('Ymd',$i)]['clicks']) ? $values[date('Ymd',$i)]['clicks']:0;
				$costCount += isset($values[date('Ymd',$i)]['cost']) ? $values[date('Ymd',$i)]['cost']:0;
				$conversionsCount += isset($values[date('Ymd',$i)]['conversions']) ? $values[date('Ymd',$i)]['conversions']:0;


				/*$res['summaryGraph']['date_range'][$counterfor] = date('M d, Y',$i);*/
				$res['date_rangePre'][$counterfor] = date('M d, Y',$i);
				$res['impressions_previous'][$counterfor] = isset($values[date('Ymd',$i)]['impressions']) ?$values[date('Ymd',$i)]['impressions']:0;
				$res['clicks_previous'][$counterfor] = isset($values[date('Ymd',$i)]['clicks']) ? $values[date('Ymd',$i)]['clicks']:0;
				$res['conversions_previous'][$counterfor] = isset($values[date('Ymd',$i)]['conversions']) ? $values[date('Ymd',$i)]['conversions']:0;
				
		
				$res['cost_previous'][$counterfor] = isset($values[date('Ymd',$i)]['cost']) ? (float)number_format($values[date('Ymd',$i)]['cost'], 2, '.', '') : 0;
				$res['cpc_previous'][$counterfor] = isset($values[date('Ymd',$i)]['average_cpc']) ? (float)number_format($values[date('Ymd',$i)]['average_cpc'], 2, '.', '') : 0;
				$res['averagecpm_previous'][$counterfor] = isset($values[date('Ymd',$i)]['average_cpm']) ? (float)number_format($values[date('Ymd',$i)]['average_cpm'], 2, '.', '') : 0;


				$res['revenue_per_click_previous'][$counterfor] = isset($values[date('Ymd',$i)]['clicks']) && isset($values[date('Ymd',$i)]['conversion_value']) && $values[date('Ymd',$i)]['clicks'] <> 0  ? (float)number_format($values[date('Ymd',$i)]['conversion_value']/$values[date('Ymd',$i)]['clicks'], 2, '.', '') : 0;

				$res['total_value_previous'][$counterfor] = isset($values[date('Ymd',$i)]['conversion_value']) && $values[date('Ymd',$i)]['conversion_value'] > 0 ? (float)number_format($values[date('Ymd',$i)]['conversion_value'], 2, '.', '') : 0;

				$counterfor++;

			}

			$avgCpc = $cost == 0 || $clicks == 0? 0: ($cost/1000000)/$clicks;
			$conversionRate = $conversions == 0 || $clicks == 0? 0: $conversions/$clicks;
			
			$average_cpc += $avgCpc;
			
			$cpc_rate += $cpcRate;


        	$counter++;
        }

		$ctrCount = $clickCount > 0 && $impressionCount > 0 ?($clickCount/$impressionCount)*100:0 ;
        $conversion_rate = $clickCount <> 0 ? ($conversionsCount/$clickCount)*100 :0;
        $costsCount = $costCount <> 0 ? ($costCount/1000000) : 0;
        $cpc_rate = $costsCount <> 0 && $conversionsCount <> 0 ? ($costsCount/$conversionsCount) : 0;
        


        $res['impressionCount'] = (float)number_format($impressionCount, 2, '.', '');
        $res['clickCount'] = (float)number_format($clickCount, 2, '.', '');
        $res['costCount'] = (float)number_format($costsCount, 2, '.', '');
        $res['conversionsCount'] = (float)number_format($conversionsCount, 2, '.', '');
        $res['ctrCount'] = (float)number_format($ctrCount, 2, '.', '');
        $res['average_cpc'] = (float)number_format($average_cpc/$counter, 2, '.', '');;
        $res['conversion_rate'] = (float)number_format($conversion_rate, 2, '.', '');
        $res['cpc_rate'] = (float)number_format($cpc_rate, 2, '.', '');
        $res['lastDate'] = $lastDate;
        $res['firstDate'] = $rangeStart;
        

        return $res;
	}

	public function campaignData(Request $request){
		
		if($request->ajax())
		{
			$durationRange = $request->all();
			
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date  = $request['start_date'];
			$end_date  = $request['end_date'];
			$campaign_id  = $request['campaign_id'];

			
			$results = self::adsCampaignData($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);
			
			return view('vendor.ppc_sections.campaigns-list.table', compact('results','account_id'))->render();
		}
	}

	public function campaignPagination(Request $request){
		
		if($request->ajax())
		{
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date  = $request['start_date'];
			$end_date  = $request['end_date'];
			$campaign_id  = $request['campaign_id'];
			
			$results = self::adsCampaignData($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.campaigns-list.pagination', compact('results','account_id'))->render();
		}
	}

	public function adGroups(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = self::adsGroupData($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.adsGroup-list.table', compact('results','account_id'))->render();
		}
	}

	public function adGroupsPagination(Request $request){
		if($request->ajax())
		{
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = self::adsGroupData($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.adsGroup-list.pagination', compact('results','account_id'))->render();
		}
	}

	public function keywordData(Request $request){
		// if($request->ajax())
		// {
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];
			
			$results = self::adKeywordsData($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);
			
			return view('vendor.ppc_sections.keywords-list.table', compact('results','account_id'))->render();			
		// }
	}

	public function keywordPagination(Request $request){
		if($request->ajax())
		{
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = self::adKeywordsData($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.keywords-list.pagination', compact('results','account_id'))->render();
		}
	}

	

	public function adsList(Request $request){
		if($request->ajax())
		{	

			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];
			
			$results = self::adData($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.ads-list.table', compact('results','account_id'))->render();
			
		}
	}

	public function adsPagination(Request $request){
		if($request->ajax())
		{
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];
			

			$results = self::adData($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.ads-list.pagination', compact('results','account_id'))->render();
		}
	}
	
	public function clickTypes(Request $request){
		if($request->ajax())
		{
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];
			
			$results = self::adsPerformanceClickType($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);
			return view('vendor.ppc_sections.clickType.table', compact('results','account_id'))->render();		
		}
	}

	public function clickTypesPagination(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = self::adsPerformanceClickType($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.clickType.pagination', compact('results','account_id'))->render();
		}
	}

	public function networkPerformance(Request $request){
		$durationRange = $request->all();
		$limit = $request['limit'];
		$account_id = $request['account_id'];
		$sortType = $request['column_name']?:'impressions';
		$sortBy = $request['order_type']?:'desc';
		$query  = $request['query'];
		$start_date = $request['start_date'];
		$end_date = $request['end_date'];
		$campaign_id = $request['campaign_id'];

		$results = self::adsPerformanceNetwork($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

		return view('vendor.ppc_sections.performance-network.table', compact('results','account_id'))->render();

	}

	public function networkPerformancePagination(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = self::adsPerformanceNetwork($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.performance-network.pagination', compact('results','account_id'))->render();
		}
		
	}

	public function devicesPerformance(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = self::adsPerformanceDevice($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.performance-device.table', compact('results','account_id'))->render();
		}
	}

	public function devicesPerformancePagination(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = self::adsPerformanceDevice($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.performance-device.pagination', compact('results','account_id'))->render();
		}
	}
	
	public function adSlots(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = self::adPerformanceAdSlot($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.performance-adSlot.table', compact('results','account_id'))->render();
		}
	}

	public function adSlotsPagination(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = self::adPerformanceAdSlot($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.performance-adSlot.pagination', compact('results','account_id'))->render();
		}
	}

}