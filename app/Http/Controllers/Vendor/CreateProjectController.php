<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DashboardType;
use App\RegionalDatabse;
use App\User;
use App\Moz;
use App\UserPackage;
use App\SemrushUserAccount;
use App\SeoAnalyticsEditSection;
use App\CampaignDashboard;
use App\GoogleAnalyticsUsers;
use App\GoogleAccountViewData;
use App\SearchConsoleUsers;
use App\SearchConsoleUrl;
use App\GoogleAdsCustomer;
use App\SemrushOrganicMetric;
use App\SemrushOrganicSearchData;
use App\BackLinksData;
use App\ActivityLog;
use App\GoogleUpdate;
use App\BacklinkSummary;
use App\AuditTask;
use Auth;
use Exception;

use App\GmbLocation;	
use App\Language;
use App\Traits\ClientAuth;
use DB;

use App\KeywordLocationList;
use App\GlobalSetting;
use App\SiteAudit;

use App\GoogleAnalyticAccount;

class CreateProjectController extends Controller {

	use ClientAuth;
	
	public function add_new_project(Request $request){

		$data = GlobalSetting::uploading_changes();
		if($data == true || $data == 1){
			return \View::make('errors.uploading_changes');
		}

		$language  = Language::where('status',1)->get();
		$campaign_id = $get_data = '';
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$dashboardTypes = DashboardType::where('status',1)->orderBy('order_status','asc')->get();
		$regional_db = RegionalDatabse::where('status', 1)->select('id', 'short_name', 'long_name')->get();
		$get_analytics_emails =GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','google')->get();

		$getConsoleAccount = SearchConsoleUsers::select('id','email')->where('user_id',$user_id)->get();
		$getAdsAccounts = GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','google_ads')->get();
		$getGmbAccounts = GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','gmb')->get();
		$get_ga4_emails = GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','ga4')->get();
		
		return view('vendor.add_new_project',['dashboardTypes'=>$dashboardTypes,'regional_db'=>$regional_db,'get_analytics_emails'=>$get_analytics_emails,'getConsoleAccount'=>$getConsoleAccount,'getAdsAccounts'=>$getAdsAccounts,'getGmbAccounts'=>$getGmbAccounts,'campaign_id'=>$campaign_id,'get_data'=>$get_data,'user_id'=>$user_id,'language'=>$language,'get_ga4_emails'=>$get_ga4_emails]);
	}


	public function checkdnsrr(Request $request){
		$url_info = parse_url($request->search);
		if (!empty($url_info) && isset($url_info['host'])) {
				$domain_url = str_replace("www.", "", $url_info['host']);
			} elseif (!empty($url_info) && isset($url_info['path'])) {
				$domain_url = str_replace("www.", "", $url_info['path']);
			}
			$domainn = rtrim($domain_url,'/');

			//$gethostbyname = SemrushUserAccount::isDomainAvailible($request->search);
			$dnsRecords = dns_get_record($domainn, DNS_A + DNS_AAAA + DNS_CNAME + DNS_MX + DNS_TXT + DNS_NS);
						
			if(empty($dnsRecords) && count($dnsRecords) == 0){
				$response['status'] = 'error';
				$response['field'] = 'domain_url';
				$response['message'] = 'No such domain is registered or domain expired.';
			}else{
				$response['status'] = 'success';
			}

			return response()->json($response);
	}


	public function ajax_google_analytics_accounts(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$get_analytics_emails =GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','google')->get();
		$li	=	'<option value="">Select from Existing Accounts</option>';
		if(!empty($get_analytics_emails)) {
			foreach($get_analytics_emails as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->email.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}

	public function ajax_google_cnsole_accounts(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$getConsoleAccount = SearchConsoleUsers::select('id','email')->where('user_id',$user_id)->get();
		$li	=	'<option value="">Select from Existing Accounts</option>';
		if(!empty($getConsoleAccount)) {
			foreach($getConsoleAccount as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->email.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}

	public function ajax_adwords_accounts(Request $request){
		$getAdsAccounts = GoogleAnalyticsUsers::select('id','email')->where('user_id',$request->user_id)->where('oauth_provider','google_ads')->get();
		$li	=	'<option value="">Select from Existing Accounts</option>';
		if(!empty($getAdsAccounts)) {
			foreach($getAdsAccounts as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->email.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}



	public function store_project_info(Request $request){
		if($request->addNew_url_type == '*.domain.com/*'){
			$url_type = 1;
		}
		if($request->addNew_url_type == 'URL'){
			$url_type = 2;
		}
		$favicon ='';
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$user_details = User::where('id',Auth::user()->id)->first();

		$user_package = UserPackage::with('package')->where('user_id', $user_id)->select('projects')->orderBy('created_at', 'desc')->first();
		$getCampaignsCount = SemrushUserAccount::where('user_id', $user_id)->where('status', 0)->count();
		if ($user_package->projects <= $getCampaignsCount) {
			$response['status'] = 'error';
			$response['field'] = 'general';
			$response['message'] = 'You have reached your project limit, Upgrade to add more projects.';
		} else {

			$user_data = User::where('id', $user_id)->first();
			$token = bin2hex(openssl_random_pseudo_bytes(16));
			$url_info = parse_url($request->domain_url);

			$project_exists = SemrushUserAccount::checkProjectName($request->project_name, $user_id);

			if (!empty($url_info) && isset($url_info['host'])) {
				$domain_url = str_replace("www.", "", $url_info['host']);
			} elseif (!empty($url_info) && isset($url_info['path'])) {
				$domain_url = str_replace("www.", "", $url_info['path']);
			}
			//$check_domain = SemrushUserAccount::checkdomainUrlNew($domain_url, $user_id);
			//$check_domain = SemrushUserAccount::checkdomainUrlNew($request->domain_url, $user_id);
			$check_domain = SemrushUserAccount::checkdomainUrlNew($domain_url, $user_id);

			$ss = rtrim($domain_url,'/');

			//$gethostbyname = SemrushUserAccount::isDomainAvailible($request->domain_url);
			$dnsRecords = dns_get_record($ss, DNS_A + DNS_AAAA + DNS_CNAME + DNS_MX + DNS_TXT + DNS_NS);
			
			if(empty($dnsRecords) && count($dnsRecords) == 0){
				$response['status'] = 'error';
				$response['field'] = 'domain_url';
				$response['message'] = 'No such domain is registered or domain expired.';
				return response()->json($response);
			}

			$dashboardType = implode(',',$request->dashboardType);

			if($project_exists == 1 && $request->existed_id == null){
				$response['status'] = 'error';
				$response['field'] = 'project_name';
				$response['message'] = 'Project Name already exists';
				return response()->json($response);
			}
			
			if($request->existed_id != null){
				$favicon = 	SemrushUserAccount::get_favicon($request->domain_url);
				$semrush_user_account = SemrushUserAccount::where('id',$request->existed_id)->update([
					'user_id' => $user_id,
					'domain_name' => $request->project_name,
					'domain_url' => $request->domain_url,
					'host_url' => $domain_url,
					'regional_db' => $request->regional_db,
					// 'clientName' => $user_data->name,
					'token' => $token,
					'dashboard_type'=>$dashboardType,
					'favicon'=>$favicon,
					'status'=>3,
					'domain_register'=>date('Y-m-d'),
					'url_type'=>$url_type
				]);

				CampaignDashboard::where('request_id',$request->existed_id)->delete();
				CampaignDashboard::addCampaignDashboards($user_id, $request->existed_id,$request->dashboardType);


				$response['status'] = 'success';
				$response['last_id'] = $request->existed_id;
				$response['dashboards'] = $dashboardType;
				$response['domain_url_value'] = $request->domain_url;
				return response()->json($response);
			}

			if ($check_domain == 0) {
				$favicon = 	SemrushUserAccount::get_favicon($request->domain_url);

				$semrush_user_account = SemrushUserAccount::create([
					'user_id' => $user_id,
					'domain_name' => $request->project_name,
					'domain_url' => $request->domain_url,
					'host_url' => $domain_url,
					'regional_db' => $request->regional_db,
					// 'clientName' => $user_data->name,
					'token' => $token,
					'dashboard_type'=>$dashboardType,
					'favicon'=>$favicon,
					'status'=>0,
					'domain_register'=>date('Y-m-d'),
					'url_type'=>$url_type
				]);
				if ($semrush_user_account) {
					$last_inserted_id = $semrush_user_account->id;
					if($user_details->parent_id != null){
						User::where('id',$user_details->id)->update([
							'restrictions'=>DB::raw("CONCAT(IFNULL(restrictions, ''), '," . $last_inserted_id . "')")
						]);
					}
					$domain_url = rtrim($domain_url, '/');
					CampaignDashboard::addCampaignDashboards($user_id, $last_inserted_id,$request->dashboardType);

					$response['status'] = 'success';
					$response['step'] = 1;
					$response['last_id'] = $last_inserted_id;
					$response['message'] = 'Project added successfully.';
					$response['dashboards'] = $dashboardType;
					$response['actual'] = 1;
					$response['domain_url_value'] = $request->domain_url;
				} else {
					$response['status'] = 'error';
					$response['field'] = 'general';
					$response['message'] = 'Getting error, Try again.';
				}
			} else {
				$response['status'] = 'error';
				$response['field'] = 'domain_url';
				$response['message'] = 'Domain Url already exists';
				$response['domain_url'] = $request->domain_url;
			}
			

		}
		return json_encode($response);
	}

	


	public function ajax_get_analytics_accounts(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 

		$getData = GoogleAccountViewData::
		where('user_id',$user_id)
		->where('google_account_id',$request->email)
		->where('parent_id',0)
		->get();


		// $getData = $getData->unique('category_id');

		$getData->values()->all();
		$li	=	'<option value="">Select Account</option>';
		if(!empty($getData)) {
			foreach($getData as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->category_name.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}

	public function ajax_get_analytics_property(Request $request){
		$getData = GoogleAccountViewData::where('parent_id',$request->account_id)->get();
		$li	=	'<option value="">Select Property</option>';
		if(!empty($getData)) {
			foreach($getData as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->category_name.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}

	public function ajax_get_analytics_view(Request $request){
		$getData = GoogleAccountViewData::where('parent_id',$request->property_id)->get();
		$li	=	'<option value="">Select View</option>';
		if(!empty($getData)) {
			foreach($getData as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->category_name.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}


	public function ajax_save_new_project_analytics_data(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child	
		$check = GoogleAnalyticsUsers::checkAnalyticsData($request->campaign_id,$user_id,$request->email,$request->account,$request->property,$request->view);
		
		if($check['status'] == 0){
			$response['status'] = 'google-error'; 
				if(!empty($check['message']['error']['code'])){
					if(isset($check['message']['error']['message'])){
						$response['message'] =$check['message']['error']['message'];
					}else{
						$response['message'] = $check['message']['errors'][0]['message'];
					}
				}else if($check['message']['error_description']){
					$response['message'] = $check['message']['error'].'-'.$check['message']['error_description'];
				}else{
					$response['message'] = $check['message'];					
				}
			return response()->json($response);
		}

		$ecom = 0;
		if($request->e_com == 'true'){
			$ecom = 1;
		}
		if($request->e_com == 'false'){
			$ecom = 0;
		}

		$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)
		->update([
			'google_account_id'=>$request->email,
			'google_analytics_id'=>$request->account,
			'google_property_id'=>$request->property,
			'google_profile_id'=>$request->view,
			'ecommerce_goals'=>$ecom
		]);

		if($update) {	
			$getEmail = GoogleAnalyticsUsers::select('id','email')->where('id',$request->email)->first();
			// GoogleAnalyticsUsers::log_analytics_data($request->campaign_id);
			$getAccount = GoogleAccountViewData::where('id',$request->account)->first();
			$getproperty = GoogleAccountViewData::where('id',$request->property)->first();
			$getview = GoogleAccountViewData::where('id',$request->view)->first();
			$response['email'] = $getEmail->email;
			$response['account'] = $getAccount->category_name;
			$response['property'] = $getproperty->category_name;
			$response['view'] = $getview->category_name;
			$response['project_id'] = $request->campaign_id;
			$response['step'] = 3;			
			$response['status'] = 'success';
		} else {
			$response['status'] = 'error'; 
		}				

		return  response()->json($response);
	}


	private function log_analytics_data($campaignId){
		try{
			$semrush_data = SemrushUserAccount::where('google_analytics_id','!=',NULL)->where('id',$campaignId)->first();
			if(!empty($semrush_data)){

				$start_date = date('Y-m-d');
				$end_date =  date('Y-m-d', strtotime("-2 years", strtotime(date('Y-m-d'))));

				$day_diff  = 	strtotime($end_date) - strtotime($start_date);
				$count_days    	=	floor($day_diff/(60*60*24));

				$start_data   =   date('Y-m-d', strtotime($end_date.' '.$count_days.' days'));


				$prev_start_date = date('Y-m-d', strtotime("-1 day", strtotime($end_date)));
				$prev_end_date = date('Y-m-d', strtotime("-2 years", strtotime($prev_start_date)));	

				$current_period     =   date('d-m-Y', strtotime($end_date)).' to '.date('d-m-Y', strtotime($start_date));
				$previous_period    =   date('d-m-Y', strtotime(date('Y-m-d',strtotime($prev_end_date)))).' to '.date('d-m-Y', strtotime($prev_start_date));

				$getAnalytics = 	GoogleAnalyticsUsers::where('id',$semrush_data->google_account_id)->first();

				$user_id = $getAnalytics->user_id;



				if(!empty($getAnalytics)){
					$status = 1;
					$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);


					$refresh_token  = $getAnalytics->google_refresh_token;

					/*if refresh token expires*/
					if ($client->isAccessTokenExpired()) {
						GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
					}

					$getAnalyticsId = SemrushUserAccount::where('id',$campaignId)->where('user_id',$user_id)->first();

					if(isset($getAnalyticsId->google_analytics_account)){
						$analyticsCategoryId = $getAnalyticsId->google_analytics_account->category_id;


						$analytics = new \Google_Service_Analytics($client);

						$profile = GoogleAnalyticsUsers::getProfileId($campaignId,$analyticsCategoryId);


						$current_data = GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$start_date,$end_date);	

						$outputRes = array_column ($current_data->rows , 0);

						$previous_data =  GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$prev_start_date,$prev_end_date);

						$outputRes_prev = array_column ($previous_data->rows , 0);


						$count_session = array_column ( $current_data->rows , 1);

						$from_dates  =  array_map(function($val) { return date("d M, Y", strtotime($val)); }, $outputRes);	
						$from_dates_format  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes);  

						/*prev data*/		
						$from_dates_prev  =  array_map(function($val) { return date("d M, Y", strtotime($val)); }, $outputRes_prev);
						$from_dates_prev_format  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes_prev);  			
						$combine_session = array_column($previous_data->rows , 1);

						$final_array = array_merge($combine_session,$count_session);
						$dates_final_array = array_merge($from_dates_prev,$from_dates);
						$dates_format = array_merge($from_dates_prev_format,$from_dates_format);


						$array = array(
							'final_array' =>$final_array,
							'from_dates'=>$dates_final_array,
							'dates_format'=>$dates_format
						);

						if (!file_exists(env('FILE_PATH').'public/analytics/'.$campaignId)) {
							mkdir(env('FILE_PATH').'public/analytics/'.$campaignId, 0777, true);

						}
						file_put_contents(env('FILE_PATH').'public/analytics/'.$campaignId.'/graph.json', print_r(json_encode($array,true),true));

						if(!empty($getAnalyticsId->google_profile_id)){
							/*current data*/
							$start_date_new = date('Y-m-d',strtotime('-1 day',strtotime($end_date)));
							$currentData = GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$start_date,$end_date);

							$outputRes_metrics = array_column ($currentData->rows , 0);
							$from_dates_metrics  =  array_map(function($val) { return date("d M, Y", strtotime($val)); }, $outputRes_metrics);
							$from_dates_metrics_format  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes_metrics);	
							$outputRes_sessions = array_column ($currentData->rows , 1);
							$current_sessions_data  =  array_map(function($val) { return $val; }, $outputRes_sessions);	

							$outputRes_users = array_column ($currentData->rows , 2);
							$current_users_data  =  array_map(function($val) { return $val; }, $outputRes_users);

							$outputRes_pageviews = array_column ($currentData->rows , 3);
							$current_pageviews_data  =  array_map(function($val) { return $val; }, $outputRes_pageviews);	


							/*Previous data*/
							$previousData = GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$start_date_new,$start_data);

							$outputRes_metrics_prev = array_column ($previousData->rows , 0);
							$from_dates_metrics_prev  =  array_map(function($val) { return date("d M, Y", strtotime($val)); }, $outputRes_metrics_prev);	
							$from_dates_metrics_prev_format  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes_metrics_prev);	
							$outputRes_sessions_prev = array_column ($previousData->rows , 1);
							$prev_sessions_data  =  array_map(function($val) { return $val; }, $outputRes_sessions_prev);

							$outputRes_users_prev = array_column ($previousData->rows , 2);
							$prev_users_data  =  array_map(function($val) { return $val; }, $outputRes_users_prev);

							$outputRes_pageviews_prev = array_column ($previousData->rows , 3);
							$prev_pageviews_data  =  array_map(function($val) { return $val; }, $outputRes_pageviews_prev);


							/*merged data for comparison*/
							$metrics_dates = array_merge($from_dates_metrics,$from_dates_metrics_prev);
							$metrics_sessions = array_merge($current_sessions_data,$prev_sessions_data);
							$metrics_users = array_merge($current_users_data,$prev_users_data);
							$metrics_pageviews = array_merge($current_pageviews_data,$prev_pageviews_data);
							$dates_format = array_merge($from_dates_metrics_format,$from_dates_metrics_prev_format);

							$final_array = array(
								'metrics_dates'=>$metrics_dates,
								'metrics_sessions'=>$metrics_sessions,
								'metrics_users'=>$metrics_users,
								'metrics_pageviews'=>$metrics_pageviews,
								'dates_format'=>$dates_format
							);



							if (!file_exists(env('FILE_PATH').'public/analytics/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/analytics/'.$campaignId, 0777, true);
							}
							file_put_contents(env('FILE_PATH').'public/analytics/'.$campaignId.'/metrics.json', print_r(json_encode($final_array,true),true));								
						}

					} else {
						$status = 0;
					}
				}

			}
		}catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	public function ajax_get_console_urls(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$getData = SearchConsoleUrl::where('user_id',$user_id)->where('google_account_id',$request->email)->get();
		//dd($getData);
		$li	=	'<option value="">Select Account</option>';
		if(!empty($getData)) {
			foreach($getData as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->siteUrl.'</option>';
			} 
			
		}else{
			$li	.= '<option value="">No Result Found</option>';
		}
		
		return $li;
	}

	public function ajax_save_new_project_console_data(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$check = SearchConsoleUrl::checkConsoleData($request->campaign_id,$user_id,$request->email,$request->account);


		if($check['status'] == 0){
			$response['status'] = 'google-error'; 
			$response['message'] = $check['message'];
			return response()->json($response);
		}

		$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)
		->update([
			'google_console_id'=>$request->email,
			'console_account_id'=>$request->account
		]);

		if($update) {	
			$getUrl = SearchConsoleUrl::where('id',$request->account)->select('siteUrl')->first();
			$getEmail = SearchConsoleUsers::where('id',$request->email)->select('email')->first();
			$response['value'] = $getUrl->siteUrl;
			$response['email'] = $getEmail->email;
			$response['project_id'] = $request->campaign_id;
			$response['step'] = 2;
			$response['status'] = 'success';
		} else if(!$update){
			$response['status'] = 'error'; 
		}				
		return  response()->json($response);
	}

	public function ajax_get_adwords_accounts(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$getData = GoogleAdsCustomer::where('user_id',$user_id)->where('google_ads_id',$request->email)
			->where('can_manage_clients',0)
			->orderBy('id','DESC')
			
			
			//->where('name','!=','NULL')
			->get()
			->unique('customer_id');
			
		$li	=	'<option value="">Select Account</option>';
		if(!empty($getData)) {
			foreach($getData as $result) {
				if($result->name <> null && !empty($result->name)){
					$name = $result->name;
				}else{
					$name = $result->customer_id;
				}

				$li	.= '<option value="'.$result->id.'">'.$name.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}
	
	/*public function ajax_get_adwords_accounts(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$getData = GoogleAdsCustomer::where('user_id',$user_id)->where('google_ads_id',$request->email)->where('can_manage_clients',0)->where('name','!=','NULL')->get();
		$li	=	'<option value="">Select Account</option>';
		if(!empty($getData)) {
			foreach($getData as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->name.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}*/


	public function ajax_save_new_project_adwords_data(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)
		->update([
			'google_ads_campaign_id'=>$request->account,
			'google_ads_id'=>$request->email
		]);

		if($update) {	
			$getEmail = GoogleAnalyticsUsers::select('id','email')->where('id',$request->email)->first();
			//GoogleAdsCustomer::log_adwords_data($request->campaign_id);		
			$getAds = GoogleAdsCustomer::where('id',$request->account)->first();
			$response['value'] = $getAds->name;
			$response['email'] = $getEmail->email;
			$response['project_id'] = $request->campaign_id;
			$response['step'] = 4;
			$response['status'] = 'success';
		} else {
			$response['status'] = 'error'; 
		}				
		return  response()->json($response);
	}

	public function ajax_store_ranking_details(Request $request){
		$long = $lat = '';
		if(!empty($request->add_project_locations)){
			if(empty($request->latitude) || empty($request->longitude)){
				$location = KeywordLocationList::getLatLong($request->add_project_locations);
				$latLong = explode(',', $location);
				$lat = $latLong[0];
				$long = $latLong[1];
			}else{
				$lat = $request->latitude;
				$long = $request->longitude;
			}
		}else{
			$lat = $request->latitude;
			$long = $request->longitude;
		}
		$update = SemrushUserAccount::where('id',$request->project_id)->update([
			'rank_search_engine'=>$request->add_project_search_engine,
			'rank_location'=>$request->add_project_locations,
			'rank_latitude'=>$lat,
			'rank_longitude'=>$long,
			'rank_device'=>$request->device,
			'rank_language'=>$request->add_project_language,
			'status'=>0
		]);
		if($update){
			$response['status'] = 1;
			$response['message'] = 'Success';
			$response['project_id'] = $request->project_id;
		}else{
			$response['status'] = 0;
			$response['message'] = 'Error!! Please try again.';
		}
		return response()->json($response);
	}

	public static function complete_steps(Request $request){
		$project_id = $request->project_id;
		$step = $request->steps;

		$data = SemrushUserAccount::where('id',$project_id)->select('id','user_id','dashboard_type','domain_url')->first();

		if($step == 1){
			SemrushUserAccount::make_project_json();
			SeoAnalyticsEditSection::CustomNote($data->user_id, $project_id);
			Moz::insertData($data->user_id,$project_id,$data->domain_url);
			BackLinksData::log_backlinks($data->user_id, $project_id);
			SemrushOrganicSearchData::store_extra_organic_keywords($data->user_id, $project_id,$data->domain_url);


			/*$client = null;
	        try {
	          $client = $this->DFSAuth();
	        } catch (RestClientException $e) {
	          return json_decode($e->getMessage(), true);
	        }
			AuditTask::createTask($client,$data->user_id, $project_id);*/

			$random = SemrushUserAccount::generateRandomString();
			$encrypted_id = base64_encode($project_id.'-|-'.$data->user_id.'-|-'.$random);
			sleep(1);
			SemrushUserAccount::where('id',$project_id)->update([
				'share_key'=>$encrypted_id
			]);
			
			$httpStatus = SiteAudit::getIp($data->domain_url);
			
			$auditStatus = null;
			if($httpStatus['http_code'] == 301 || $httpStatus['http_code'] == 302){
				$request->request->add(['campaign_id' => $project_id,'url' => $httpStatus['redirect_url']]);
				$siteAudit = new SiteAuditReportsController($request);
				$auditStatus = $siteAudit->siteAuditRun($request);
			}
			if($httpStatus['http_code'] == 200){
				$request->request->add(['campaign_id' => $project_id,'url' => $httpStatus['url']]);
				$siteAudit = new SiteAuditReportsController($request);
				$auditStatus = $siteAudit->siteAuditRun($request);
			}

			if($auditStatus <> null && $auditStatus['status'] == true && $auditStatus['availability'] == false){
				$request->request->add(['audit_id' => $auditStatus['audit_id']]);
				// file_put_contents(dirname(__FILE__).'/logs/audit.json',print_r(json_encode($request->all(),true),true));
				$siteAudit->auditCrowler($request);
			}

		}

		if($step == 2){
			$log_data = SearchConsoleUsers::log_console_data($project_id);
			if(isset($log_data['status']) && $log_data['status'] == 0){
			}else{
				GoogleUpdate::updateTiming($project_id,'search_console','sc_type','2');
			}
		}
		
		if($step == 3){
			$log_data = GoogleAnalyticsUsers::log_analytics_data($project_id);
			if(isset($log_data['status']) && $log_data['status'] == 0){
			}else{
				GoogleUpdate::updateTiming($project_id,'analytics','analytics_type','2');
			}
		}

		if($step == 4){
			GoogleAdsCustomer::log_adwords_data($project_id);
		}

		if($step == 5){
			GoogleAnalyticAccount::store_ga4_data($project_id);
		}

	}

	public function ajax_delete_added_project(Request $request){
		$last_id = $request->last_id;
		$this->remove_added_campaign_data($last_id);
		$user_details = User::where('id',Auth::user()->id)->first();
		if($user_details->parent_id != null){
			$user = User::where('id',$user_details->id)->select('id','restrictions')->first();
			$data_after_remove = $this->remove_value_from_string($user->restrictions,$last_id);
			User::where('id',$user_details->id)->update(['restrictions'=>$data_after_remove]);
		}
		$response['status'] = 1;
		return response()->json($response);
	}

	private function remove_added_campaign_data($last_id){
		SemrushUserAccount::where('id',$last_id)->delete();
		SemrushUserAccount::make_project_json();
		SeoAnalyticsEditSection::where('request_id',$last_id)->delete();
		Moz::where('request_id',$last_id)->delete();
		BackLinksData::where('request_id',$last_id)->delete();
		BacklinkSummary::where('request_id',$last_id)->delete();
		SemrushOrganicSearchData::where('request_id',$last_id)->delete();
		SemrushOrganicMetric::where('request_id',$last_id)->delete();


		if (file_exists(env('FILE_PATH').'public/analytics/'.$last_id)) {
			SemrushUserAccount::remove_directory(env('FILE_PATH').'public/analytics/'.$last_id);
		}
		if (file_exists(env('FILE_PATH').'public/goalcompletion/'.$last_id)) {
			SemrushUserAccount::remove_directory(env('FILE_PATH').'public/goalcompletion/'.$last_id);
		}
		if (file_exists(env('FILE_PATH').'public/ecommerce_goals/'.$last_id)) {
			SemrushUserAccount::remove_directory(env('FILE_PATH').'public/ecommerce_goals/'.$last_id);
		}
		if (file_exists(env('FILE_PATH').'public/search_console/'.$last_id)) {
			SemrushUserAccount::remove_directory(env('FILE_PATH').'public/search_console/'.$last_id);
		}
		if (file_exists(env('FILE_PATH').'public/gmb/'.$last_id)) {
			SemrushUserAccount::remove_directory(env('FILE_PATH').'public/gmb/'.$last_id);
		}
		if (file_exists(env('FILE_PATH').'public/adwords/'.$last_id)) {
			SemrushUserAccount::remove_directory(env('FILE_PATH').'public/adwords/'.$last_id);
		}
		GoogleUpdate::where('request_id',$last_id)->delete();
	}


	private function remove_value_from_string($string,$value){
		$hdnListCL=explode(',',$string);
		$index = array_search($value,$hdnListCL);
		if($index !== false){
			unset($hdnListCL[$index]);
		}
		$hdnListCL = implode(',',$hdnListCL);
		if(empty($hdnListCL)){
			$hdnListCL = NULL;
		}
		
		return $hdnListCL;
	}


	public function ajax_get_gmb_accounts(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$get_analytics_emails =GmbLocation::where('user_id',$user_id)->where('google_account_id',$request->email)->where('status',1)->groupBy('website_url')->get();
		$li	=	'<option value="">Select from Existing Accounts</option>';
		if(!empty($get_analytics_emails)) {
			foreach($get_analytics_emails as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->location_name.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}

	public function ajax_gmb_accounts(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$get_analytics_emails =GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','gmb')->get();
		$li	=	'<option value="">Select from Existing Accounts</option>';
		if(!empty($get_analytics_emails)) {
			foreach($get_analytics_emails as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->email.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}


	public function ajax_fetch_adwords_accounts(){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$getAdsAccounts = GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','google_ads')->get();
		$li	=	'<option value="">Select from Existing Accounts</option>';
		if(!empty($getAdsAccounts)) {
			foreach($getAdsAccounts as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->email.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}

	public function ajax_refresh_analytics_acccount_list(Request $request){
		$response = array();
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$analytic_id = $request['email'];
		$campaignId = $request['campaign_id'];

		if($campaignId <> null){
			$getAnalytics = GoogleAnalyticsUsers::accountInfoById($user_id,$analytic_id);

			if(!empty($getAnalytics)){
				$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);
				$refresh_token  = $getAnalytics->google_refresh_token;

				/*if refresh token expires*/
				if ($client->isAccessTokenExpired()) {
					GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
				}

				$getAnalyticsId = SemrushUserAccount::with('google_analytics_account')->where('id',$campaignId)->where('user_id',$user_id)->first();

				if($getAnalyticsId){
					$analytics = new \Google_Service_Analytics($client);

					$data = GoogleAnalyticsUsers::refresh_getGoogleAccountsList($analytics,$campaignId,$analytic_id,$user_id,'google');
					if($data['status'] == 1){
						$response['status'] = 1;
						$response['message'] = 'Last fetched now';
					}
					if($data['status'] == 0){
						$response['status'] = 0;
						$response['message'] = 'Error message: '.$data['message'].': Insufficient permission.';
					}
				}
			}else{
				$response['status'] = 2;
				$response['message'] = 'Error: Please try again.';

			}
		}else{
			$response['status'] = 2;
			$response['message'] = 'Error: missing campaign id';
			
		}
		return response()->json($response);	
	}

	public function update_share_key(){
		$projects = SemrushUserAccount::select('id','user_id')->get();
		foreach($projects as $key=>$value){
			$random = SemrushUserAccount::generateRandomString();
			$encrypted_id = base64_encode($value->id.'-|-'.$value->user_id.'-|-'.$random);
			SemrushUserAccount::where('id',$value->id)->update([
				'share_key'=>$encrypted_id
			]);
		}
	}

}