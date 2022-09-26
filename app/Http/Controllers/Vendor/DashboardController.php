<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DashboardType;
use App\CampaignDashboard;
use App\SeoAnalyticsEditSection;
use App\SemrushUserAccount;
use App\ModuleByDateRange;
use App\RegionalDatabse;
use App\ProjectCompareGraph;
use App\Moz;
use App\GoogleAdsCustomer;
use App\GoogleAnalyticsUsers;
use App\ActivityLog;
use App\User;
use App\KeywordSearch;
use App\SearchConsoleUsers;
use App\GmbLocation;
use Auth; 

use App\Traits\GMBAuth;

class DashboardController extends Controller
{
	 use GMBAuth;

	public function new_dashboard($domain_name,$campaign_id){
    	$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
    	$role_id = User::get_user_role(Auth::user()->id); 
    	$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
    	$keywords_left = KeywordSearch::keywordsLeft($user_id);

	

    	if(isset($data) && !empty($data)){
    	// $activity_log = ActivityLog::getActivity($user_id,$campaign_id);
    		$getAccounts = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','google')->get();
    		// $getConsoleAccount = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','search_console')->get();
    		$getConsoleAccount = SearchConsoleUsers::where('user_id',$user_id)->get();
    		$getAdsAccounts = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','google_ads')->get();
    		$all_dashboards = DashboardType::where('status',1)->pluck('name','id')->all();

    		$types = CampaignDashboard::
    		where('user_id',$user_id)
    		->where('status',1)
    		->where('request_id',$campaign_id)
    		->orderBy('order_status','asc')
    		->orderBy('dashboard_id','asc')
    		->pluck('dashboard_id')
    		->all();



    		$summary = SeoAnalyticsEditSection::where('request_id', $campaign_id)->first();
    		$moz_data = Moz::where('request_id', $campaign_id)->first();
    		$dashboardtype = SemrushUserAccount::where('id',$campaign_id)->first();


    	

    		$getRegions = RegionalDatabse::where('status',1)->get();

    		$googleAnalyticsCurrent = ProjectController::getCurrentDomainDetails($campaign_id);

    		$moduleTrafficStatus = ModuleByDateRange::where('user_id',$user_id)->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

    		$moduleSearchStatus = ModuleByDateRange::where('user_id',$user_id)->where('request_id',$campaign_id)->where('module','search_console')->first();
    		if(!empty($moduleTrafficStatus)){
    			$selected = $this->getSelectedDateForCharts($moduleTrafficStatus->start_date,$moduleTrafficStatus->end_date);
    		}else{
    			$selected = 3;
    		}
    		if(!empty($moduleSearchStatus)){
    			$selectedSearch = $this->getSelectedDateForCharts($moduleSearchStatus->start_date,$moduleSearchStatus->end_date);	
    		}else{
    			$selectedSearch = 3;
    		}



    		$AnalyticsCompare = ProjectCompareGraph::where('request_id',$campaign_id)->where('user_id',$user_id)->first();
    		if(!empty($AnalyticsCompare)){
    			$comparison = $AnalyticsCompare->compare_status;
    		}else{
    			$comparison = 0;
    		}



    		$ppc_data = $this->ppc_contents($domain_name,$campaign_id);

    		$account_id = $ppc_data['account_id'];
    		$today = $ppc_data['today'];
    		$currency_code = $ppc_data['currency_code'];
    		$campaign_id = $ppc_data['campaign_id'];
    		$getGoogleAds = $ppc_data['getGoogleAds'];

    		$moduleadsStatus = ModuleByDateRange::where('user_id',$user_id)->where('request_id',$campaign_id)->where('module','google_ads')->first();



    		return view('vendor.new_dashboard',['all_dashboards'=>$all_dashboards,'types'=>$types,'campaign_id'=>$campaign_id,'summary' => $summary, 'moz_data' => $moz_data,'campaign_id'=>$campaign_id,'googleAnalyticsCurrent'=>$googleAnalyticsCurrent,'dashboardtype'=>$dashboardtype,'getRegions'=>$getRegions,'selected'=>$selected,'comparison'=>$comparison,'selectedSearch'=>$selectedSearch,'account_id'=>$account_id,'today'=>$today,'currency_code'=>$currency_code,'campaign_id'=>$campaign_id,'getGoogleAds'=>$getGoogleAds,'getAccounts'=>$getAccounts,'getConsoleAccount'=>$getConsoleAccount,'getAdsAccounts'=>$getAdsAccounts,'moduleadsStatus'=>$moduleadsStatus,'role_id'=>$role_id,'keywords_left'=>$keywords_left]);
    	}else{
    		return view('errors.404');
    	}
    }


    public function ppc_content($domain_name,$campaign_id){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$ppc_data = $this->ppc_contents($domain_name,$campaign_id);
			$account_id = $ppc_data['account_id'];
			$today = $ppc_data['today'];
			$currency_code = $ppc_data['currency_code'];
			$campaign_id = $ppc_data['campaign_id'];
			$getGoogleAds = $ppc_data['getGoogleAds'];
			$getAdsAccounts = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','google_ads')->get();

			$moduleadsStatus = ModuleByDateRange::where('user_id',$user_id)->where('request_id',$campaign_id)->where('module','google_ads')->first();
			$dashboardtype = SemrushUserAccount::where('id',$campaign_id)->where('user_id',$user_id)->first();

			return \View::make('vendor.dashboards.ppc',['account_id'=>$account_id,'today'=>$today,'currency_code'=>$currency_code,'campaign_id'=>$campaign_id,'getGoogleAds'=>$getGoogleAds,'dashboardtype'=>$dashboardtype,'getAdsAccounts'=>$getAdsAccounts,'moduleadsStatus'=>$moduleadsStatus]);
		}

		public function seo_content($domain_name,$campaign_id){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$getAccounts = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','google')->get();
			$getConsoleAccount = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','search_console')->get();
			$summary = SeoAnalyticsEditSection::where('request_id', $campaign_id)->first();
			$moz_data = Moz::where('request_id', $campaign_id)->first();
			$dashboardtype = SemrushUserAccount::where('id',$campaign_id)->where('user_id',$user_id)->first();
			$keywords_left = KeywordSearch::keywordsLeft($user_id);
			

			$getRegions = RegionalDatabse::where('status',1)->get();
			
			$googleAnalyticsCurrent = ProjectController::getCurrentDomainDetails($campaign_id);

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
			
			
			
			$AnalyticsCompare = ProjectCompareGraph::where('request_id',$campaign_id)->where('user_id',$user_id)->first();
			if(!empty($AnalyticsCompare)){
				$comparison = $AnalyticsCompare->compare_status;
			}else{
				$comparison = 0;
			}

			return \View::make('vendor.dashboards.seo',['campaign_id'=>$campaign_id,'summary' => $summary, 'moz_data' => $moz_data,'campaign_id'=>$campaign_id,'googleAnalyticsCurrent'=>$googleAnalyticsCurrent,'dashboardtype'=>$dashboardtype,'getRegions'=>$getRegions,'selected'=>$selected,'comparison'=>$comparison,'selectedSearch'=>$selectedSearch,'getAccounts'=>$getAccounts,'getConsoleAccount'=>$getConsoleAccount,'keywords_left'=>$keywords_left]);
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


		public static function ppc_contents($domain_name,$campaign_id){
			$getGoogleAds = SemrushUserAccount::where('id',$campaign_id)->first();

			$today = date('Y-m-d');
			$currency_code ='$'; 

		// if(isset($getGoogleAds) && !empty($getGoogleAds)){
			$AdsCustomer = GoogleAdsCustomer::where('id',$getGoogleAds->google_ads_campaign_id)->first();
			$ads_customer_id = isset($AdsCustomer->customer_id)?$AdsCustomer->customer_id:'';	
			
			$googleUserDetails = GoogleAnalyticsUsers::where('id',$getGoogleAds->google_ads_id)->first();


			$refreshToken = isset($googleUserDetails->google_refresh_token)?$googleUserDetails->google_refresh_token:'';	

		// }	else{
		// 	$ads_customer_id = '';	
		// }

			
			$data = array('account_id'=>$ads_customer_id,'today'=>$today,'currency_code'=>$currency_code,'campaign_id'=>$campaign_id,'getGoogleAds'=>$getGoogleAds);
			return $data;

		}


		public function ajax_update_skip(Request $request){
			if($request['skipvalue'] == 'SEO'){
				$key = 'seo_skip';
			}

			if($request['skipvalue'] == 'PPC'){
				$key = 'ppc_skip';
			}

			$update = SemrushUserAccount::
			where('id',$request['campaign_id'])
			->update([
				$key => 1
			]);
			if($update){
				$response['status'] = 1;
			}else{
				$response['status'] = 0;
			}
			return response()->json($response);
		}


		public function get_account_activity (Request $request){
			$request_id = $request['request_id'];
			$lastDate = $request['lastDate'];
			$limit = $request['limit'];

			$roundArr = array('purple','green','pink','yellow','blue');
			$resultArr = '';

			$date = '';	

			$results = ActivityLog::campaign_activity($request_id,$limit);
			// echo "<pre>";
			// print_r($results);
			// die;


			if(!empty($results) && isset($results)){
				foreach($results['data'] as $key=>$value){
					$keytime = array_rand($roundArr); 


					$date = date('l, d F Y',strtotime($value->created_at));


					if($date <> $lastDate){
						$lastDate = date('l, d F Y',strtotime($value->created_at));
						$resultArr .= '<div class="account-timeline-date">'.$date.'</div>';
					}

					$resultArr .= '<article>';
					$resultArr .= '<div class="account-timeline-time">'.date('h:i A',strtotime($value->created_at)).'</div>';
					$resultArr .= '<div class="account-timeline-badge '.$roundArr[$keytime].'"><span></span></div>';
					$resultArr .= '<div class="account-timeline-info">'.$value->description.'</div>';
					$resultArr .= '</article>';

					if($key == 0 && $lastDate == ''){
						$lastDate = date('l, d F Y',strtotime($value->created_at));
					}

				}

				$finalArr = array('html' => $resultArr,'limit'=>$results['limit']);
				return response()->json($finalArr);
			}
		}

		public function gmb_content($domain_name,$campaign_id){

			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$getUser = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->where('status',0)->first();
			$gmbLocation =     GmbLocation::where('id',$getUser->gmb_id)->first();
			$getAnalytics =     GoogleAnalyticsUsers::where('id',$gmbLocation->google_account_id)->first();

			$client = GoogleAnalyticsUsers::googleGmbClientAuth($getAnalytics);
			$refresh_token  = $getAnalytics->google_refresh_token;
			/*if refresh token expires*/
			if ($client->isAccessTokenExpired()) {
				GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
			}
			//$this->getLocationReviews($client,$gmbLocation->location_id);
			// $this->getLocationMetrixData($client,$gmbLocation->account_id,$gmbLocation->location_id);
			$metrixOverview = $this->getLocationMetrix($client,$gmbLocation->account_id,$gmbLocation->location_id);
			$metrixGraphViewMap = $this->getLocationMetrixViewMap($client,$gmbLocation->account_id,$gmbLocation->location_id);
			$metrixGraphCustomerActions = $this->getLocationMetrixCustomerActions($client,$gmbLocation->account_id,$gmbLocation->location_id);
		
			
			return \View::make('vendor.dashboards.gmb',compact('metrixOverview','metrixGraphViewMap','metrixGraphCustomerActions'));
		}

	}
