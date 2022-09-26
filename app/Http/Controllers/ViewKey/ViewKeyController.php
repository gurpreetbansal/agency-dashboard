<?php

namespace App\Http\Controllers\ViewKey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;

use App\DashboardType;
use App\CampaignDashboard;
use App\SeoAnalyticsEditSection;
use App\Moz;
use App\ProjectCompareGraph;
use App\ModuleByDateRange;
use App\SemrushUserAccount;
use App\GoogleAdsCustomer;
use App\GoogleAnalyticsUsers;
use App\RegionalDatabse;

class ViewKeyController extends Controller
{
	public function view_dashboard ($domain_name, $request_id=null){
		$requested_id = Crypt::decrypt($request_id);
		$exploded = explode('-|-',$requested_id);
		$campaign_id = $exploded[0];
		$user_id = $exploded[1];

		$all_dashboards = DashboardType::where('status',1)->where('parent_id',0)->where('type',0)->pluck('name','id')->all();


		$types = CampaignDashboard::
		where('user_id',$user_id)
		->where('status',1)
		->where('request_id',$campaign_id)
		->orderBy('order_status','asc')
		->orderBy('dashboard_id','asc')
		->pluck('dashboard_id')
		->all();

		$child = DashboardType::where('status',1)->where('parent_id','!=',0)->where('type',1)->pluck('name','id')->all();



		$moz_data = Moz::where('request_id', $campaign_id)->first();
		$summary = SeoAnalyticsEditSection::summary_section($campaign_id,$user_id);
		$dashboardtype = SemrushUserAccount::where('id',$campaign_id)->where('user_id',$user_id)->first();

		
		$moduleTrafficStatus = ModuleByDateRange::where('user_id',$user_id)->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

		if(!empty($moduleTrafficStatus)){
			$selected = $this->getSelectedDateForCharts($moduleTrafficStatus->start_date,$moduleTrafficStatus->end_date);
		}else{
			$selected = 0;
		}


		$moduleSearchStatus = ModuleByDateRange::where('user_id',$user_id)->where('request_id',$campaign_id)->where('module','search_console')->first();
		if(!empty($moduleSearchStatus)){
			$selectedSearch = $this->getSelectedDateForCharts($moduleSearchStatus->start_date,$moduleSearchStatus->end_date);	
		}else{
			$selectedSearch = 0;
		}

		
		/*ppc section*/

		$getGoogleAds = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
		$today = date('Y-m-d');
		$currency_code ='$';

		$AdsCustomer = GoogleAdsCustomer::where('id',$getGoogleAds->google_ads_campaign_id)->first();
		$ads_customer_id = isset($AdsCustomer->customer_id)?$AdsCustomer->customer_id:'';	
		
		$googleUserDetails = GoogleAnalyticsUsers::where('id',$getGoogleAds->google_ads_id)->first();
	
	
		$refreshToken = isset($googleUserDetails->google_refresh_token)?$googleUserDetails->google_refresh_token:'';	

		$moduleadsStatus = ModuleByDateRange::where('user_id',$user_id)->where('request_id',$campaign_id)->where('module','google_ads')->first();
		
	

		return view('viewkey.dashboard',['campaign_id'=>$campaign_id,'all_dashboards'=>$all_dashboards,'types'=>$types,'summary'=>$summary,'moz_data'=>$moz_data,'selected'=>$selected,'dashboardtype'=>$dashboardtype,'selectedSearch'=>$selectedSearch,'user_id'=>$user_id,'account_id'=>$ads_customer_id,'today'=>$today,'currency_code'=>$currency_code,'campaign_id'=>$campaign_id,'getGoogleAds'=>$getGoogleAds,'moduleadsStatus'=>$moduleadsStatus,'child'=>$child]);
	}


	public static function getSelectedDateForCharts($start_date,$end_date){
			$selected = 0;
			$analyticsStart  = date('Y-m-d',strtotime($start_date));
			$analyticsEnd  = date('Y-m-d',strtotime($end_date));
			
			$ts1Ana = strtotime($analyticsStart);
            $ts2Ana = strtotime($analyticsEnd);

            $year1Ana = date('Y', $ts1Ana);
            $year2Ana = date('Y', $ts2Ana);

            $month1Ana = date('m', $ts1Ana);
            $month2Ana = date('m', $ts2Ana);
			
			
			$day_diff  = strtotime($start_date) - strtotime($end_date);
			$count_days  = floor($day_diff/(60*60*24));
				if($count_days == '-7'){
					$selected  = 0.25;
				}else{
					$selected = (($year2Ana - $year1Ana) * 12) + ($month2Ana - $month1Ana);
				}

			return $selected;
	}


	public function ppc_content($domain_name,$campaign_id){
		$getGoogleAds = SemrushUserAccount::where('id',$campaign_id)->first();
		$user_id = $getGoogleAds->user_id;
		
		$today = date('Y-m-d');
		$currency_code ='$';

		$AdsCustomer = GoogleAdsCustomer::where('id',$getGoogleAds->google_ads_campaign_id)->first();
		$ads_customer_id = isset($AdsCustomer->customer_id)?$AdsCustomer->customer_id:'';	
			
		$googleUserDetails = GoogleAnalyticsUsers::where('id',$getGoogleAds->google_ads_id)->first();
		
		
		$refreshToken = isset($googleUserDetails->google_refresh_token)?$googleUserDetails->google_refresh_token:'';

		$getAdsAccounts = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','google_ads')->get();

		$dashboardtype = SemrushUserAccount::where('id',$campaign_id)->where('user_id',$user_id)->first();

		$moduleadsStatus = ModuleByDateRange::where('user_id',$user_id)->where('request_id',$campaign_id)->where('module','google_ads')->first();

		return \View::make('viewkey.dashboards.ppc',['account_id'=>$ads_customer_id,'today'=>$today,'currency_code'=>$currency_code,'campaign_id'=>$campaign_id,'getGoogleAds'=>$getGoogleAds,'dashboardtype'=>$dashboardtype,'getAdsAccounts'=>$getAdsAccounts,'moduleadsStatus'=>$moduleadsStatus]);
	}


	public function seo_content($domain_name,$campaign_id){
			$getGoogle = SemrushUserAccount::where('id',$campaign_id)->first();
			$user_id = $getGoogle->user_id;
			$summary = SeoAnalyticsEditSection::where('request_id', $campaign_id)->first();
			$moz_data = Moz::where('request_id', $campaign_id)->first();
			$dashboardtype = SemrushUserAccount::where('id',$campaign_id)->where('user_id',$user_id)->first();
			
			$moduleTrafficStatus = ModuleByDateRange::where('user_id',$user_id)->where('request_id',$campaign_id)->where('module','organic_traffic')->first();
			
			$moduleSearchStatus = ModuleByDateRange::where('user_id',$user_id)->where('request_id',$campaign_id)->where('module','search_console')->first();
			if(!empty($moduleTrafficStatus)){
				$selected = $this->getSelectedDateForCharts($moduleTrafficStatus->start_date,$moduleTrafficStatus->end_date);
			}else{
				$selected = 0;
			}
			if(!empty($moduleSearchStatus)){
				$selectedSearch = $this->getSelectedDateForCharts($moduleSearchStatus->start_date,$moduleSearchStatus->end_date);	
			}else{
				$selectedSearch = 0;
			}
			

			return \View::make('viewkey.dashboards.seo',['campaign_id'=>$campaign_id,'summary' => $summary, 'moz_data' => $moz_data,'campaign_id'=>$campaign_id,'dashboardtype'=>$dashboardtype,'selected'=>$selected,'selectedSearch'=>$selectedSearch]);
		}


	public function ranking_content ($domain_name,$campaign_id){
		$getGoogle = SemrushUserAccount::where('id',$campaign_id)->first();
		$user_id = $getGoogle->user_id;
		$moduleSearchStatus = ModuleByDateRange::where('user_id',$user_id)->where('request_id',$campaign_id)->where('module','search_console')->first();

		if(!empty($moduleSearchStatus)){
			$selectedSearch = $this->getSelectedDateForCharts($moduleSearchStatus->start_date,$moduleSearchStatus->end_date);	
		}else{
			$selectedSearch = 0;
		}

		$dashboardtype = SemrushUserAccount::where('id',$campaign_id)->where('user_id',$user_id)->first();
		return \View::make('viewkey.sidebarcontent.ranking',['campaign_id'=>$campaign_id,'selectedSearch'=>$selectedSearch,'dashboardtype'=>$dashboardtype]);
	}

	public function traffic_content($domain_name,$campaign_id){
		$getGoogle = SemrushUserAccount::where('id',$campaign_id)->first();
		$user_id = $getGoogle->user_id;
		$dashboardtype = SemrushUserAccount::where('id',$campaign_id)->where('user_id',$user_id)->first();

		if(!empty($moduleTrafficStatus)){
			$selected = $this->getSelectedDateForCharts($moduleTrafficStatus->start_date,$moduleTrafficStatus->end_date);
		}else{
			$selected = 0;
		}
		return \View::make('viewkey.sidebarcontent.traffic',['campaign_id'=>$campaign_id,'selected'=>$selected,'dashboardtype'=>$dashboardtype]);
	}

	public function backlinks_content($domain_name,$campaign_id){
		return \View::make('viewkey.sidebarcontent.backlinks',['campaign_id'=>$campaign_id]);
	}

	public function leads_content($domain_name,$campaign_id){
		return \View::make('viewkey.sidebarcontent.leads',['campaign_id'=>$campaign_id]);
	}
	
}