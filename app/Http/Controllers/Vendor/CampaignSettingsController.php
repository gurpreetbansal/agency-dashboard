<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use Session;
use App\GoogleAnalyticsUsers;
use App\SemrushUserAccount;
use App\GoogleAccountViewData;
use App\GoogleAdsCustomer;
use Exception;
use App\RegionalDatabse;
use App\ProfileInfo;
use URL;
use App\DashboardType;
use App\CampaignDashboard;
use App\CampaignTag;
use App\SearchConsoleUsers;
use App\SearchConsoleUrl;

// error_reporting(1);
// ini_set('display_errors', 1);

class CampaignSettingsController extends Controller {


	public function index ($domain_name, $campaign_id,Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$getAccounts = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','google')->get();
			$getAdsAccounts = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','google_ads')->get();
			
			$dashboardtype = SemrushUserAccount::where('id',$campaign_id)->first();
			// $getConsoleAccount = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','search_console')->get();
			$getConsoleAccount = SearchConsoleUsers::where('user_id',$user_id)->get();

			$profile_info  = ProfileInfo::where('user_id',$user_id)->where('request_id',$campaign_id)->first();

			$regional_db = RegionalDatabse::get();

			$logo = $this->getLogo($campaign_id);
			
			$managerImage = $this->getmanagerImage($campaign_id);

			$dashboards = DashboardType::where('status',1)->get();
			$user_dashboards =  CampaignDashboard::where('user_id',$user_id)->where('request_id',$campaign_id)->get();

			
			return view('vendor.campaign_settings',['getAccounts'=>$getAccounts,'getAdsAccounts'=>$getAdsAccounts,'dashboardtype'=>$dashboardtype,'getConsoleAccount'=>$getConsoleAccount,'regional_db'=>$regional_db,'profile_info'=>$profile_info,'logo'=>$logo,'dashboards'=>$dashboards,'user_dashboards'=>$user_dashboards,'managerImage'=>$managerImage]);
		}
		

		private function getLogo($request_id){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child

			$path  = 'public/storage/agency_logo/'.$user_id.'/'.$request_id.'/';
			
			if(file_exists($path)){
				$files1 = array_values(array_diff(scandir($path), array('..', '.')));

				if(!empty($files1)) {
					$image_url = URL::asset('public/storage/agency_logo/'.$user_id.'/'.$request_id.'/'.$files1[0]);
					$response['return_path']	=	$image_url; 
					$response['file_name']		=	$files1[0];
				}else{
					$response	=	'';
				}
				return $response;  			
			}
		}

		private function getmanagerImage($request_id){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child

			$path  = 'public/storage/agency_managers/'.$user_id.'/'.$request_id.'/';
			
			if(file_exists($path)){
				$files1 = array_values(array_diff(scandir($path), array('..', '.')));

				if(!empty($files1)) {
					$image_url = URL::asset('public/storage/agency_managers/'.$user_id.'/'.$request_id.'/'.$files1[0]);
					$response['return_path']	=	$image_url; 
					$response['file_name']		=	$files1[0];
				}else{
					$response	=	'';
				}
				return $response;  			
			}
		}
		
		public function connectGoogleAnalytics (Request $request){
			ini_set('max_execution_time', 0);
			try{
				$google_redirect_url = \config('app.base_url').'connect_google_analytics';
				$client = new \Google_Client();
				$client->setAuthConfig(\config('app.FILE_PATH').\config('app.ANALYTICS_CONFIG'));
				$client->setRedirectUri($google_redirect_url);
				$client->addScope(['https://www.googleapis.com/auth/webmasters','https://www.googleapis.com/auth/webmasters.readonly','email','profile','https://www.googleapis.com/auth/analytics.readonly']);
				$client->setAccessType("offline");
				$client->setApplicationName("AgencyDashboard.io");
				$client->setState($request->campaignId.'/'.$request->provider.'/'.$request->redirectPage);
				$client->setIncludeGrantedScopes(true);
				$client->setApprovalPrompt('force');


			// echo '<pre>';
			// print_r($request->all());
			// die;

				if ($request->get('code') == NULL) {
					$auth_url = $client->createAuthUrl();
					return redirect()->to($auth_url);
				} else {
					$exploded_value = explode('/',$request->state);
					$campaignId = $exploded_value[0];
					$provider = $exploded_value[1];
					// if(isset($exploded_value[3])){
					// 	$redirectPage = $exploded_value[2].'-'.@$exploded_value[3];
					// }else{
					$redirectPage = $exploded_value[2];
					// }
					
					if ($request->get('code')){
						$client->authenticate($request->get('code'));
						$client->refreshToken($request->get('code'));
						Session::put('token', $client->getAccessToken());
						
					}
					if ($request->session()->get('token'))
					{
						$client->setAccessToken($request->session()->get('token'));
					}
					$session_result	= $client->getAccessToken();
					
					/*fetching details of logged-in user*/
					$getUserDetails = SemrushUserAccount::findorfail($campaignId);
					
					
					$getLoggedInUser = User::findorfail($getUserDetails->user_id);
					$domainName = $getLoggedInUser->company_name;
					
					
					
					$google_oauthV2 = new \Google_Service_Oauth2($client);
					$googleuser = $google_oauthV2->userinfo->get(); 
					
					$checkIfExists = GoogleAnalyticsUsers::where('user_id',$getUserDetails->user_id)->where('oauth_uid',$googleuser['id'])->where('oauth_provider',$provider)->first();

					// dd($checkIfExists);
					$sessionData = Session::all();
					
					if(empty($checkIfExists)){
						
						$insert = GoogleAnalyticsUsers::create([
							'user_id'=>$getUserDetails->user_id,
							'google_access_token'=> $sessionData['token']['access_token'],
							'google_refresh_token'=>$sessionData['token']['refresh_token'],
							'oauth_provider'=>$provider,
							'oauth_uid'=>$googleuser['id'],
							'first_name'=>$googleuser['givenName'],
							'last_name'=>$googleuser['familyName'],
							'email'=>$googleuser['email'],
							'gender'=>$googleuser['gender']??'',
							'locale'=>$googleuser['locale']??'',
							'picture'=>$googleuser['picture']??'',
							'link'=>$googleuser['link']??'',
							'token_type'=>$sessionData['token']['token_type'],
							'expires_in'=>$sessionData['token']['expires_in'],
							'id_token'=>$sessionData['token']['id_token'],
							'service_created'=>$sessionData['token']['created']
						]);
						SearchConsoleUsers::updateRefreshNAccessToken($googleuser['email'],$getUserDetails->user_id,$sessionData['token']);
						if($insert){
							$getLastInsertedId = $insert->id;
							if($provider == 'google'){
								// $updateSemrush = SemrushUserAccount::where('user_id',$getUserDetails->user_id)->where('id',$campaignId)->update([
								// 	'google_account_id'=>$getLastInsertedId
								// ]);
							}else{
								$updateSemrush = SemrushUserAccount::where('user_id',$getUserDetails->user_id)->where('id',$campaignId)->update([
									'google_console_id'=>$getLastInsertedId
								]);
							}
							
						}
						$analytics = new \Google_Service_Analytics($client);
						GoogleAnalyticsUsers::getGoogleAccountsList($analytics,$campaignId,$getLastInsertedId,$getUserDetails->user_id,$provider);
					} else if(!empty($sessionData['token']['access_token'])){

						$refresh_token 	= isset($sessionData['token']['refresh_token']) ? $sessionData['token']['refresh_token'] : $checkIfExists->google_refresh_token;
						$update = GoogleAnalyticsUsers::where('user_id',$getUserDetails->user_id)->where('oauth_uid',$googleuser['id'])->where('id',$checkIfExists->id)->update([
							'google_access_token'=> $sessionData['token']['access_token'],
							'google_refresh_token'=> $refresh_token,
							'oauth_provider'=>$provider,
							'oauth_uid'=>$googleuser['id'],
							'first_name'=>$googleuser['givenName'],
							'last_name'=>$googleuser['familyName'],
							'email'=>$googleuser['email'],
							'gender'=>$googleuser['gender']??'',
							'locale'=>$googleuser['locale']??'',
							'picture'=>$googleuser['picture']??'',
							'link'=>$googleuser['link']??'',
							'token_type'=>$sessionData['token']['token_type'],
							'expires_in'=>$sessionData['token']['expires_in'],
							'id_token'=>$sessionData['token']['id_token'],
							'service_created'=>$sessionData['token']['created']
						]);
						
						
						if ($client->isAccessTokenExpired()) {
							$client->refreshToken($sessionData['token']['refresh_token']);
						}

						SearchConsoleUsers::updateRefreshNAccessToken($googleuser['email'],$getUserDetails->user_id,$sessionData['token']);
						
						$analytics =  new \Google_Service_Analytics($client);
						
						GoogleAnalyticsUsers::getGoogleAccountsList($analytics,$campaignId,$checkIfExists->id,$getUserDetails->user_id,$provider);
					}
					if($redirectPage == 'authorization'){
						echo  "<script>";	
						echo "window.close();";	
						echo "</script>";	
						return;
					}
					if($redirectPage == 'add-new-project'){
						echo  "<script>";
						echo "window.close();";
						echo "</script>";
						return;
						
					}

					if($redirectPage == 'campaign-detail'){
						echo  "<script>";
						echo "window.close();";
						echo "</script>";
						return;
						
					}

					if($redirectPage == 'project-settings'){
						echo  "<script>";
						echo "window.close();";
						echo "</script>";
						return;
						
					}
					// else{	
					// 	$returnUrl = 'https://'.$domainName.'.'.config('app.APP_DOMAIN').$redirectPage.'/'.$campaignId;
					// }
					//return redirect($returnUrl);
				}
			} catch (\Exception $e) {
				return $e->getMessage();
				if($redirectPage == 'authorization'){
					$returnUrl = 'https://'.$domainName.'.'.config('app.APP_DOMAIN').$redirectPage;
				}
				elseif($redirectPage == 'add-new-project'){
					// $returnUrl = 'https://'.$domainName.'.'.config('app.APP_DOMAIN').$redirectPage;
				}
				else{	
					$returnUrl = 'https://'.$domainName.'.'.config('app.APP_DOMAIN').$redirectPage.'/'.$campaignId;
				}
				return redirect($returnUrl);
			}

		}


		
		
		
		// public function ajax_google_view_account_bkp($domain=null,$account_id =null, $campaignID = null){
		public function ajax_google_view_account_analytics($domain=null,$account_id =null, $campaignID = null){
		//	$getData = GoogleAccountViewData::where('user_id',Auth::user()->id)->where('request_id',$campaignID)->where('google_account_id',$account_id)->where('parent_id',0)->get();
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child

			$getData = GoogleAccountViewData::
			where('user_id',$user_id)
			->where('google_account_id',$account_id)
			//->where('request_id',$campaignID)
			->where('parent_id',0)
			->get();


			$getData = $getData->unique('category_id');

			$getData->values()->all();
			// echo "<pre>";
			// print_r($getData);
			// die;

			// echo '<pre>';
			// print_r($messagesUnique);
			// die;
			$li	=	'<option value=""><--Select Account--></option>';
			if(!empty($getData)) {
				foreach($getData as $result) {
					$li	.= '<option value="'.$result->id.'">'.$result->category_name.'</option>';
				} 
				
			}else{
				$li	.= '<option value="">No Result Found</option>';
			}
			
			return $li;
			
		}

		public function ajax_google_view_account($domain=null,$account_id =null, $campaignID = null){

			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child

			$getData = SearchConsoleUrl::where('user_id',$user_id)->where('google_account_id',$account_id)->get();

			$li	=	'<option value=""><--Select Account--></option>';
			if(!empty($getData)) {
				foreach($getData as $result) {
					$li	.= '<option value="'.$result->id.'">'.$result->siteUrl.'</option>';
				} 
				
			}else{
				$li	.= '<option value="">No Result Found</option>';
			}
			
			return $li;
			
		}
		
		
		public function ajax_google_property_data($domain=null,$property_id =null){
			$getData = GoogleAccountViewData::where('parent_id',$property_id)->get();
			$li	=	'<option value=""><--Select Analytic Account--></option>';
			if(!empty($getData)) {
				foreach($getData as $result) {
					$li	.= '<option value="'.$result->id.'">'.$result->category_name.'</option>';
				} 
				
			}else{
				$li	.= '<option value="">No Result Found</option>';
			}
			
			return $li;
		}
		
		
		public function ajax_google_viewId_data($domain=null,$property_id =null){
			$getData = GoogleAccountViewData::where('parent_id',$property_id)->get();
			$li	=	'<option value=""><--Select Console Account--></option>';
			if(!empty($getData)) {
				foreach($getData as $result) {
					$li	.= '<option value="'.$result->id.'">'.$result->category_name.'</option>';
				} 
				
			}else{
				$li	.= '<option value="">No Result Found</option>';
			}
			
			return $li;
		}
		

		private function check_analytics($analytic_view_id,$request_id){
			$start_date = date('Y-m-d');
			$end_date =  date('Y-m-d', strtotime("-3 months", strtotime(date('Y-m-d'))));

			$profile_account_data = GoogleAccountViewData::where('id',$analytic_view_id)->first();
			$category_id = $profile_account_data->category_id;

			$semrush_data = SemrushUserAccount::where('id',$request_id)->first();

			$getAnalytics =  GoogleAnalyticsUsers::where('id',$semrush_data->google_account_id)->first();
			$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);
			if ($client->isAccessTokenExpired()) {
				GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
			}
			$analytics = new \Google_Service_Analytics($client);
			try{			
				$current_data = GoogleAnalyticsUsers::getResultForDateRange($analytics, $category_id,$start_date,$end_date); 
				
				return $current_data;
			}catch(Exception $e){
				return $e->getMessage();
			}
			
		}
		
		public function ajax_save_analytics_data(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child	

		$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->request_id)->first();
		$acc_id = $if_Exist->google_analytics_id;

		$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->request_id)
		->update([
			'google_account_id'=>$request->google_account_id,
			'google_profile_id'=>$request->analytic_view_id,
			'google_analytics_id'=>$request->analytic_account_id,
			'google_property_id'=>$request->analytic_property_id,
		]);

		if($update) {			
			$response['status'] = 'success';
			$this->log_analytics_data($request->request_id);
		} elseif($acc_id == $request->analytic_account_id){
			$response['status'] = 'success';
		}else {
			$response['status'] = 'error'; 
		}				

		// }else{
		// 	// dd('else');
		// 	$response['status'] = 'analytics-error';
		// 	$response['message'] = $result['error']['message'];
		// }
		return json_encode($response); 

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

						// $profile = GoogleAnalyticsUsers::getFirstProfileId($analytics,$analyticsCategoryId);
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
							$outputRes_sessions_prev = array_column ($previousData->rows , 1);
							$prev_sessions_data  =  array_map(function($val) { return $val; }, $outputRes_sessions_prev);

							$outputRes_users_prev = array_column ($previousData->rows , 2);
							$prev_users_data  =  array_map(function($val) { return $val; }, $outputRes_users_prev);

							$outputRes_pageviews_prev = array_column ($previousData->rows , 3);
							$prev_pageviews_data  =  array_map(function($val) { return $val; }, $outputRes_pageviews_prev);


							/*merged data for comparison*/
							// $metrics_dates = array_merge($from_dates_metrics,$from_dates_metrics_prev);
							// $metrics_sessions = array_merge($current_sessions_data,$prev_sessions_data);
							// $metrics_users = array_merge($current_users_data,$prev_users_data);
							// $metrics_pageviews = array_merge($current_pageviews_data,$prev_pageviews_data);

							$metrics_dates = array_merge($from_dates_metrics_prev,$from_dates_metrics);
                            $metrics_sessions = array_merge($prev_sessions_data,$current_sessions_data);
                            $metrics_users = array_merge($prev_users_data,$current_users_data);
                            $metrics_pageviews = array_merge($prev_pageviews_data,$current_pageviews_data);

							$final_array = array(
								'metrics_dates'=>$metrics_dates,
								'metrics_sessions'=>$metrics_sessions,
								'metrics_users'=>$metrics_users,
								'metrics_pageviews'=>$metrics_pageviews,
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

	public function ajax_save_console_data (Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$check = $this->checkConsoleData($request->campaignID,$user_id,$request->existing_console_accounts,$request->console_account);
			
			
			if($check['status'] == 0){
				$response['status'] = 'google-error'; 
				$response['message'] = $check['message'];
				return response()->json($response);
			}

			$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaignID)->first();
			$acc_id = $if_Exist->console_account_id;
			$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaignID)
			->update([
				'google_console_id'=>$request->existing_console_accounts,
				'console_account_id'=>$request->console_account
			]);

			if($update) {			
				$response['status'] = 'success';
				$this->log_console_data($request->campaignID);
			}elseif($acc_id == $request->console_account){
				//$this->log_console_data($request->campaignID);
				$response['status'] = 'success';
			} else if(!$update){
				$response['status'] = 'error'; 
			}				
		//	die('566');
			return json_encode($response); 
		}

		private function log_console_data_bkp($campaignId){
			
			try{
				$data = SemrushUserAccount::where('console_account_id','!=',NULL)->where('id',$campaignId)->first();
				if(!empty($data)){
					$dates  = $converted_dates = $clicks = $impressions = array();
					$page_dates = $page_converted_dates = $page_key = $page_clicks = $page_impressions = array();
					$device_dates = $device_converted_dates = $device_key = $device_clicks = $device_impressions = $device_ctr = $device_positions = array();
					$country_dates = $country_converted_dates = $country_query = $country_clicks = $country_impressions = $country_ctr = $country_position = array();

					/*query variables*/
					$final_query = array();
					$month_query_keys = $month_query_clicks = $month_query_impressions = '';
					$three_query_keys = $three_query_clicks = $three_query_impressions = '';
					$six_query_keys = $six_query_clicks = $six_query_impressions = '';
					$nine_query_keys = $nine_query_clicks = $nine_query_impressions = '';
					$one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = '';
					$query_dates=$query_converted_dates=	$query_keys = $query_clicks = $query_impressions  = '';

					$nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = array();

					/*device variables*/
					$month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

					$three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
					$six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
					$nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
					$year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
					$two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
					$month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();


					$getAnalytics  = GoogleAnalyticsUsers::where('id',$data->google_console_id)->first();
					$user_id = $data->user_id;
					$campaignId = $data->id;

					$role_id =User::get_user_role($user_id);

					if(!empty($getAnalytics)){
						$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);

						$refresh_token  = $getAnalytics->google_refresh_token;

						if ($client->isAccessTokenExpired()) {
							GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
						}

						$getAnalyticsId = SemrushUserAccount::with('google_search_account')->where('user_id',$user_id)->where('id',$campaignId)->first();


						if(isset($getAnalyticsId->google_search_account)){
							$analyticsCategoryId = $getAnalyticsId->google_search_account->category_id;
							$analytics = new \Google_Service_Analytics($client);
							$profileUrl = GoogleAnalyticsUsers::getDomainProfileUrl($campaignId);


							$end_date = date('Y-m-d');
							$start_date = date('Y-m-d', strtotime("-2 years", strtotime(date('Y-m-d'))));


							$one_month = date('Y-m-d',strtotime('-1 month'));
							$three_month = date('Y-m-d',strtotime('-3 month'));
							$six_month = date('Y-m-d',strtotime('-6 month'));
							$nine_month = date('Y-m-d',strtotime('-9 month'));
							$one_year = date('Y-m-d',strtotime('-1 year'));

							/*query data*/

							$search_console_query = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$start_date,$end_date);	
							$search_console_query_one = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$one_month,$end_date);	
							$search_console_query_three = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$three_month,$end_date);	
							$search_console_query_six = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$six_month,$end_date);	
							$search_console_query_nine = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$nine_month,$end_date);	
							$search_console_query_year = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$one_year,$end_date);	



							if(!empty($search_console_query_one)){
								foreach($search_console_query_one->getRows() as $month_query){

									$month_query_keys = $month_query->keys[0];
									$month_query_clicks = $month_query->clicks;
									$month_query_impressions = $month_query->impressions;

									$month_query_array[] = array(
										'month_query_keys'=>$month_query_keys,
										'month_query_clicks' =>$month_query_clicks,
										'month_query_impressions'=>$month_query_impressions
									);
								}

							}

							if(!empty($search_console_query_three)){
								foreach($search_console_query_three->getRows() as $three_query){
									$three_query_keys = $three_query->keys[0]	;
									$three_query_clicks = $three_query->clicks;
									$three_query_impressions = $three_query->impressions;

									$three_query_array[] = array(
										'three_query_keys'=>$three_query_keys,
										'three_query_clicks' =>$three_query_clicks,
										'three_query_impressions'=>$three_query_impressions
									);
								}
							}

							if(!empty($search_console_query_six)){
								foreach($search_console_query_six->getRows() as $six_query){
									$six_query_keys = $six_query->keys[0]	;
									$six_query_clicks = $six_query->clicks;
									$six_query_impressions = $six_query->impressions;


									$six_query_array[] = array(
										'six_query_keys'=>$six_query_keys,
										'six_query_clicks' =>$six_query_clicks,
										'six_query_impressions'=>$six_query_impressions
									);
								}
							}							

							if(!empty($search_console_query_nine)){
								foreach($search_console_query_nine->getRows() as $nine_query){
									$nine_query_keys = $nine_query->keys[0]	;
									$nine_query_clicks = $nine_query->clicks;
									$nine_query_impressions = $nine_query->impressions;

									$nine_query_array[] = array(
										'nine_query_keys'=>$nine_query_keys,
										'nine_query_clicks' =>$nine_query_clicks,
										'nine_query_impressions'=>$nine_query_impressions
									);
								}
							}

							if(!empty($search_console_query_year)){
								foreach($search_console_query_year->getRows() as $one_year_query){
									$one_year_query_keys = $one_year_query->keys[0]	;
									$one_year_query_clicks = $one_year_query->clicks;
									$one_year_query_impressions = $one_year_query->impressions;

									$one_year_query_array[] = array(
										'one_year_query_keys'=>$one_year_query_keys,
										'one_year_query_clicks' =>$one_year_query_clicks,
										'one_year_query_impressions'=>$one_year_query_impressions
									);
								}
							}

							if(!empty($search_console_query)){
								foreach($search_console_query->getRows() as $query_key=> $query){
									$query_keys = $query->keys[0]	;
									$query_clicks = $query->clicks;
									$query_impressions = $query->impressions;


									$query_array[] = array(
										'query_keys'=>$query_keys,
										'query_clicks' =>$query_clicks,
										'query_impressions'=>$query_impressions
									);
								}
							} 							

							$final_query = array(
								'month_query_array'=>$month_query_array,
								'three_query_array'=>$three_query_array,
								'six_query_array'=>$six_query_array,
								'nine_query_array'=>$nine_query_array,
								'one_year_query_array'=>$one_year_query_array,
								'two_year_query_array'=>$query_array
							);


							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$queryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json';
								if(date("Y-m-d", filemtime($queryfilename)) != date('Y-m-d')){
									file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json', print_r(json_encode($final_query,true),true));
								}
							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json', print_r(json_encode($final_query,true),true));
							}

							$month_query_keys = $month_query_clicks = $month_query_impressions = '';
							$three_query_keys = $three_query_clicks = $three_query_impressions = '';
							$six_query_keys = $six_query_clicks = $six_query_impressions = '';
							$nine_query_keys = $nine_query_clicks = $nine_query_impressions = '';
							$one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = '';
							$query_dates=$query_converted_dates=	$query_keys = $query_clicks = $query_impressions  = '';

							$nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = $final_query = array();

							/*query data*/


							/*device data*/
							// if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
							// 	$devicefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json';
							// 	if(date("Y-m-d", filemtime($devicefilename)) != date('Y-m-d')){

							$one_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$one_month,$end_date);
							$three_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$three_month,$end_date);
							$six_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$six_month,$end_date);
							$nine_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$nine_month,$end_date);
							$year_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$one_year,$end_date);
							$two_year_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$start_date,$end_date);


							if(!empty($one_search_console_device)){
								foreach($one_search_console_device->getRows() as $month_device){

									$month_device_keys = $month_device->keys[0];
									$month_device_clicks = $month_device->clicks;
									$month_device_impressions = $month_device->impressions;
									$month_device_ctr = $month_device->ctr;
									$month_device_position = $month_device->position;

									$month_device_array[] = array(
										'month_device_keys'=>$month_device_keys,
										'month_device_clicks' =>$month_device_clicks,
										'month_device_impressions'=>$month_device_impressions,
										'month_device_ctr'=>$month_device_ctr,
										'month_device_position'=>$month_device_position
									);
								}
							}


							if(!empty($three_search_console_device)){
								foreach($three_search_console_device->getRows() as $three_device){

									$three_device_keys = $three_device->keys[0];
									$three_device_clicks = $three_device->clicks;
									$three_device_impressions = $three_device->impressions;
									$three_device_ctr = $three_device->ctr;
									$three_device_position = $three_device->position;

									$three_device_array[] = array(
										'three_device_keys'=>$three_device_keys,
										'three_device_clicks' =>$three_device_clicks,
										'three_device_impressions'=>$three_device_impressions,
										'three_device_ctr'=>$three_device_ctr,
										'three_device_position'=>$three_device_position
									);
								}
							}


							if(!empty($six_search_console_device)){
								foreach($six_search_console_device->getRows() as $six_device){

									$six_device_keys = $six_device->keys[0];
									$six_device_clicks = $six_device->clicks;
									$six_device_impressions = $six_device->impressions;
									$six_device_ctr = $six_device->ctr;
									$six_device_position = $six_device->position;

									$six_device_array[] = array(
										'six_device_keys'=>$six_device_keys,
										'six_device_clicks' =>$six_device_clicks,
										'six_device_impressions'=>$six_device_impressions,
										'six_device_ctr'=>$six_device_ctr,
										'six_device_position'=>$six_device_position
									);
								}
							}


							if(!empty($nine_search_console_device)){
								foreach($nine_search_console_device->getRows() as $nine_device){

									$nine_device_keys = $nine_device->keys[0];
									$nine_device_clicks = $nine_device->clicks;
									$nine_device_impressions = $nine_device->impressions;
									$nine_device_ctr = $nine_device->ctr;
									$nine_device_position = $nine_device->position;

									$nine_device_array[] = array(
										'nine_device_keys'=>$nine_device_keys,
										'nine_device_clicks' =>$nine_device_clicks,
										'nine_device_impressions'=>$nine_device_impressions,
										'nine_device_ctr'=>$nine_device_ctr,
										'nine_device_position'=>$nine_device_position
									);
								}
							}


							if(!empty($year_console_device)){
								foreach($year_console_device->getRows() as $year_device){

									$year_device_keys = $year_device->keys[0];
									$year_device_clicks = $year_device->clicks;
									$year_device_impressions = $year_device->impressions;
									$year_device_ctr = $year_device->ctr;
									$year_device_position = $year_device->position;

									$year_device_array[] = array(
										'year_device_keys'=>$year_device_keys,
										'year_device_clicks' =>$year_device_clicks,
										'year_device_impressions'=>$year_device_impressions,
										'year_device_ctr'=>$year_device_ctr,
										'year_device_position'=>$year_device_position
									);
								}
							}


							if(!empty($two_year_search_console_device)){
								foreach($two_year_search_console_device->getRows() as $two_year_device){

									$two_year_device_keys = $two_year_device->keys[0];
									$two_year_device_clicks = $two_year_device->clicks;
									$two_year_device_impressions = $two_year_device->impressions;
									$two_year_device_ctr = $two_year_device->ctr;
									$two_year_device_position = $two_year_device->position;

									$two_year_device_array[] = array(
										'two_year_device_keys'=>$two_year_device_keys,
										'two_year_device_clicks' =>$two_year_device_clicks,
										'two_year_device_impressions'=>$two_year_device_impressions,
										'two_year_device_ctr'=>$two_year_device_ctr,
										'two_year_device_position'=>$two_year_device_position
									);
								}
							}

							$final_device = array(
								'month_device_array'=>$month_device_array,
								'three_device_array'=>$three_device_array,
								'six_device_array'=>$six_device_array,
								'nine_device_array'=>$nine_device_array,
								'year_device_array'=>$year_device_array,
								'two_year_device_array'=>$two_year_device_array
							);

							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$devicefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json';
								if(file_exists($devicefilename)){
									if(date("Y-m-d", filemtime($devicefilename)) != date('Y-m-d')){
										file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json', print_r(json_encode($final_device,true),true));
									}
								}else{
									file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json', print_r(json_encode($final_device,true),true));
								}

							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json', print_r(json_encode($final_device,true),true));
							}




							$month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

							$three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
							$six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
							$nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
							$year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
							$two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
							$month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();
							// 	}
							// }
							/*device data*/

							/*pages data*/
							// if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
							// 	$pagefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json';
							// 	if(date("Y-m-d", filemtime($pagefilename)) != date('Y-m-d')){

							$one_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$one_month,$end_date);
							$three_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$three_month,$end_date);
							$six_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$six_month,$end_date);
							$nine_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$nine_month,$end_date);
							$one_year_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$one_year,$end_date);
							$two_year_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$start_date,$end_date);




							if(!empty($one_month_page)){
								foreach($one_month_page->getRows() as $month_page){
									$month_page_keys = $month_page->keys[0];
									$month_page_clicks = $month_page->clicks;
									$month_page_impressions = $month_page->impressions;

									$month_page_array[] = array(
										'month_page_keys'=>$month_page_keys,
										'month_page_clicks' =>$month_page_clicks,
										'month_page_impressions'=>$month_page_impressions
									);
								}
							}

							if(!empty($three_month_page)){
								foreach($three_month_page->getRows() as $three_page){
									$three_page_keys = $three_page->keys[0];
									$three_page_clicks = $three_page->clicks;
									$three_page_impressions = $three_page->impressions;

									$three_page_array[] = array(
										'three_page_keys'=>$three_page_keys,
										'three_page_clicks' =>$three_page_clicks,
										'three_page_impressions'=>$three_page_impressions
									);
								}
							}

							if(!empty($six_month_page)){
								foreach($six_month_page->getRows() as $six_page){
									$six_page_keys = $six_page->keys[0];
									$six_page_clicks = $six_page->clicks;
									$six_page_impressions = $six_page->impressions;

									$six_page_array[] = array(
										'six_page_keys'=>$six_page_keys,
										'six_page_clicks' =>$six_page_clicks,
										'six_page_impressions'=>$six_page_impressions
									);
								}
							}

							if(!empty($nine_month_page)){
								foreach($nine_month_page->getRows() as $nine_page){
									$nine_page_keys = $nine_page->keys[0];
									$nine_page_clicks = $nine_page->clicks;
									$nine_page_impressions = $nine_page->impressions;

									$nine_page_array[] = array(
										'nine_page_keys'=>$nine_page_keys,
										'nine_page_clicks' =>$nine_page_clicks,
										'nine_page_impressions'=>$nine_page_impressions
									);
								}
							}

							if(!empty($one_year_page)){
								foreach($one_year_page->getRows() as $year_page){
									$year_page_keys = $year_page->keys[0];
									$year_page_clicks = $year_page->clicks;
									$year_page_impressions = $year_page->impressions;

									$year_page_array[] = array(
										'year_page_keys'=>$year_page_keys,
										'year_page_clicks' =>$year_page_clicks,
										'year_page_impressions'=>$year_page_impressions
									);
								}
							}

							if(!empty($two_year_page)){
								foreach($two_year_page->getRows() as $two_yearpage){
									$two_year_page_keys = $two_yearpage->keys[0];
									$two_year_page_clicks = $two_yearpage->clicks;
									$two_year_page_impressions = $two_yearpage->impressions;

									$two_year_page_array[] = array(
										'two_year_page_keys'=>$two_year_page_keys,
										'two_year_page_clicks' =>$two_year_page_clicks,
										'two_year_page_impressions'=>$two_year_page_impressions
									);
								}
							}

							$final_page = array(
								'month_page_array'=>$month_page_array,
								'three_page_array'=>$three_page_array,
								'six_page_array'=>$six_page_array,
								'nine_page_array'=>$nine_page_array,
								'year_page_array'=>$year_page_array,
								'two_year_page_array'=>$two_year_page_array
							);

							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$pagefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json';
								if(file_exists($pagefilename)){
									if(date("Y-m-d", filemtime($pagefilename)) != date('Y-m-d')){
										file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json', print_r(json_encode($final_page,true),true));
									}
								}else{
									file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json', print_r(json_encode($final_page,true),true));
								}
							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json', print_r(json_encode($final_page,true),true));
							}


							$month_page_keys = $month_page_clicks = $month_page_impressions = '';
							$three_page_keys = $three_page_clicks = $three_page_impressions = '';
							$six_page_keys = $six_page_clicks = $six_page_impressions = '';
							$nine_page_keys = $nine_page_clicks = $nine_page_impressions = '';
							$year_page_keys = $year_page_clicks = $year_page_impressions = '';
							$two_year_page_keys = $two_year_page_clicks = $two_year_page_impressions = '';
							$month_page_array = $three_page_array = $six_page_array = $nine_page_array =  $year_page_array = $two_year_page_array = $final_page = array();
							// 	}
							// }
							/*pages data*/

							/*country data*/
							// if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
							// 	$countryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json';
							// 	if(date("Y-m-d", filemtime($countryfilename)) != date('Y-m-d')){
							$month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$one_month,$end_date);
							$three_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$three_month,$end_date);
							$six_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$six_month,$end_date);
							$nine_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$nine_month,$end_date);
							$one_year_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$one_year,$end_date);
							$two_year_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$start_date,$end_date);


							if(!empty($month_country)){
								foreach($month_country->getRows() as $monthCountry){

									$month_country_keys = $monthCountry->keys[0];
									$month_country_clicks = $monthCountry->clicks;
									$month_country_impressions = $monthCountry->impressions;
									$month_country_ctr = $monthCountry->ctr;
									$month_country_position = $monthCountry->position;

									$month_country_array[] = array(
										'month_country_keys'=>$month_country_keys,
										'month_country_clicks' =>$month_country_clicks,
										'month_country_impressions'=>$month_country_impressions,
										'month_country_ctr'=>$month_country_ctr,
										'month_country_position'=>$month_country_position
									);
								}
							}

							if(!empty($three_month_country)){
								foreach($three_month_country->getRows() as $threeCountry){

									$threeCountry_keys = $threeCountry->keys[0];
									$threeCountry_clicks = $threeCountry->clicks;
									$threeCountry_impressions = $threeCountry->impressions;
									$threeCountry_ctr = $threeCountry->ctr;
									$threeCountry_position = $threeCountry->position;

									$three_country_array[] = array(
										'threeCountry_keys'=>$threeCountry_keys,
										'threeCountry_clicks' =>$threeCountry_clicks,
										'threeCountry_impressions'=>$threeCountry_impressions,
										'threeCountry_ctr'=>$threeCountry_ctr,
										'threeCountry_position'=>$threeCountry_position
									);
								}

							}

							if(!empty($six_month_country)){
								foreach($six_month_country->getRows() as $six_month_Country){

									$six_month_Country_keys = $six_month_Country->keys[0];
									$six_month_Country_clicks = $six_month_Country->clicks;
									$six_month_Country_impressions = $six_month_Country->impressions;
									$six_month_Country_ctr = $six_month_Country->ctr;
									$six_month_Country_position = $six_month_Country->position;

									$six_country_array[] = array(
										'six_month_Country_keys'=>$six_month_Country_keys,
										'six_month_Country_clicks' =>$six_month_Country_clicks,
										'six_month_Country_impressions'=>$six_month_Country_impressions,
										'six_month_Country_ctr'=>$six_month_Country_ctr,
										'six_month_Country_position'=>$six_month_Country_position
									);
								}

							}

							if(!empty($nine_month_country)){
								foreach($nine_month_country->getRows() as $nine_month_Country){

									$nine_month_Country_keys = $nine_month_Country->keys[0];
									$nine_month_Country_clicks = $nine_month_Country->clicks;
									$nine_month_Country_impressions = $nine_month_Country->impressions;
									$nine_month_Country_ctr = $nine_month_Country->ctr;
									$nine_month_Country_position = $nine_month_Country->position;

									$nine_country_array[] = array(
										'nine_month_Country_keys'=>$nine_month_Country_keys,
										'nine_month_Country_clicks' =>$nine_month_Country_clicks,
										'nine_month_Country_impressions'=>$nine_month_Country_impressions,
										'nine_month_Country_ctr'=>$nine_month_Country_ctr,
										'nine_month_Country_position'=>$nine_month_Country_position
									);
								}

							}

							if(!empty($one_year_country)){
								foreach($one_year_country->getRows() as $year_Country){

									$year_Country_keys = $year_Country->keys[0];
									$year_Country_clicks = $year_Country->clicks;
									$year_Country_impressions = $year_Country->impressions;
									$year_Country_ctr = $year_Country->ctr;
									$year_Country_position = $year_Country->position;

									$year_country_array[] = array(
										'year_Country_keys'=>$year_Country_keys,
										'year_Country_clicks' =>$year_Country_clicks,
										'year_Country_impressions'=>$year_Country_impressions,
										'year_Country_ctr'=>$year_Country_ctr,
										'year_Country_position'=>$year_Country_position
									);
								}

							}


							if(!empty($two_year_country)){
								foreach($two_year_country->getRows() as $two_year_Country){

									$two_year_Country_keys = $two_year_Country->keys[0];
									$two_year_Country_clicks = $two_year_Country->clicks;
									$two_year_Country_impressions = $two_year_Country->impressions;
									$two_year_Country_ctr = $two_year_Country->ctr;
									$two_year_Country_position = $two_year_Country->position;

									$two_year_country_array[] = array(
										'two_year_Country_keys'=>$two_year_Country_keys,
										'two_year_Country_clicks' =>$two_year_Country_clicks,
										'two_year_Country_impressions'=>$two_year_Country_impressions,
										'two_year_Country_ctr'=>$two_year_Country_ctr,
										'two_year_Country_position'=>$two_year_Country_position
									);
								}

							}

							$final_country = array(
								'month_country_array'=>$month_country_array,
								'three_country_array'=>$three_country_array,
								'six_country_array'=>$six_country_array,
								'nine_country_array'=>$nine_country_array,
								'year_country_array'=>$year_country_array,
								'two_year_country_array'=>$two_year_country_array
							);


							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$countryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json';
								if(file_exists($countryfilename)){
									if(date("Y-m-d", filemtime($countryfilename)) != date('Y-m-d')){
										file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json', print_r(json_encode($final_country,true),true));
									}
								}else{
									file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json', print_r(json_encode($final_country,true),true));
								}
							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json', print_r(json_encode($final_country,true),true));

							}

							$month_country_keys = $month_country_clicks = $month_country_impressions = $month_country_ctr = $month_country_position =  '';
							$threeCountry_keys = $threeCountry_clicks = $threeCountry_impressions = $threeCountry_ctr = $threeCountry_position =  '';
							$six_month_Country_keys = $six_month_Country_clicks = $six_month_Country_impressions = $six_month_Country_ctr = $six_month_Country_position =  '';
							$nine_month_Country_keys = $nine_month_Country_clicks = $nine_month_Country_impressions = $nine_month_Country_ctr = $nine_month_Country_position =  '';
							$year_Country_keys = $year_Country_clicks = $year_Country_impressions = $year_Country_ctr = $year_Country_position =  '';
							$two_year_Country_keys = $two_year_Country_clicks = $two_year_Country_impressions = $two_year_Country_ctr = $two_year_Country_position =  '';


							$month_country_array = $three_country_array = $six_country_array = $nine_country_array = $year_country_array = $final_country = $two_year_country_array =  array();
							// 	}
							// }
							/*country data*/

							/*graph data*/
							// if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
							// 	$graphfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json';
							// 	if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){

							$searchConsoleData = GoogleAnalyticsUsers::getSearchConsoleData($client,$profileUrl,$start_date,$end_date);
							if(!empty($searchConsoleData)){
								foreach($searchConsoleData->getRows() as $data_key=>$data){
									$dates[] = $data->keys[0];
									$converted_dates[] = strtotime($data->keys[0])*1000;
									$clicks[]    = array('t'=>strtotime($data->keys[0])*1000,'y'=>$data->clicks);
									$impressions[] = array('t'=>strtotime($data->keys[0])*1000,'y'=>$data->impressions);
								}

							}


							$data_array = array(
								'dates'=>$dates,
								'converted_dates'=>$converted_dates,
								'clicks' =>$clicks,
								'impressions'=>$impressions
							);

							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$graphfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json';
								if(file_exists($graphfilename)){
									if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){
										file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json', print_r(json_encode($data_array,true),true));
									}
								}else{
									file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json', print_r(json_encode($data_array,true),true));
								}
							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json', print_r(json_encode($data_array,true),true));
							}
							$dates = $converted_dates = $clicks = $impressions = array();
							// 	}
							// }
							/*graph data*/

						}					
					}

				}
			}catch(\Exception $e){
				return $e->getMessage();
			}
		}
		

		private function log_console_data($campaignId){
			
			try{
				$data = SemrushUserAccount::where('console_account_id','!=',NULL)->where('id',$campaignId)->first();
				if(!empty($data)){
					$dates  = $converted_dates = $clicks = $impressions = array();
					$page_dates = $page_converted_dates = $page_key = $page_clicks = $page_impressions = array();
					$device_dates = $device_converted_dates = $device_key = $device_clicks = $device_impressions = $device_ctr = $device_positions = array();
					$country_dates = $country_converted_dates = $country_query = $country_clicks = $country_impressions = $country_ctr = $country_position = array();

					/*query variables*/
					$final_query = array();
					$month_query_keys = $month_query_clicks = $month_query_impressions = '';
					$three_query_keys = $three_query_clicks = $three_query_impressions = '';
					$six_query_keys = $six_query_clicks = $six_query_impressions = '';
					$nine_query_keys = $nine_query_clicks = $nine_query_impressions = '';
					$one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = '';
					$query_dates=$query_converted_dates=	$query_keys = $query_clicks = $query_impressions  = '';

					$nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = array();

					/*device variables*/
					$month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

					$three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
					$six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
					$nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
					$year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
					$two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
					$month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();


					$getAnalytics  = SearchConsoleUsers::where('id',$data->google_console_id)->first();

					$user_id = $data->user_id;
					$campaignId = $data->id;

					$role_id =User::get_user_role($user_id);

					

					if(!empty($getAnalytics)){

						$client = SearchConsoleUsers::ConsoleClientAuth($getAnalytics);

						$refresh_token  = $getAnalytics->google_refresh_token;

						if ($client->isAccessTokenExpired()) {
							SearchConsoleUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
						}

						
						$getAnalyticsId = SearchConsoleUrl::where('id',$data->console_account_id)->first();


						if(isset($getAnalyticsId)){
							$profileUrl = $getAnalyticsId->siteUrl;



							$end_date = date('Y-m-d');
							$start_date = date('Y-m-d', strtotime("-2 years", strtotime(date('Y-m-d'))));


							$one_month = date('Y-m-d',strtotime('-1 month'));
							$three_month = date('Y-m-d',strtotime('-3 month'));
							$six_month = date('Y-m-d',strtotime('-6 month'));
							$nine_month = date('Y-m-d',strtotime('-9 month'));
							$one_year = date('Y-m-d',strtotime('-1 year'));

							/*query data*/
							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$queryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json';
								if(file_exists($queryfilename)){

									if(date("Y-m-d", filemtime($queryfilename)) != date('Y-m-d')){
										$this->search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
									}else{
										$this->search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
									}
								}else{

									$this->search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
								}

							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								$this->search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
							}


							/*query data*/

							
							/*device data*/
							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$devicefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json';
								if(file_exists($devicefilename)){
									if(date("Y-m-d", filemtime($devicefilename)) != date('Y-m-d')){
										$this->search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
									}else{
										$this->search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
									}
								}else{
									$this->search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
								}

							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								$this->search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
							}						
							/*device data*/

							/*pages data*/
							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$pagefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json';
								if(file_exists($pagefilename)){
									if(date("Y-m-d", filemtime($pagefilename)) != date('Y-m-d')){
										$this->search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
									}else{
										$this->search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
									}
								}else{
									$this->search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
								}

							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								$this->search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
							}

							/*pages data*/

							/*country data*/
							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$countryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json';
								if(file_exists($countryfilename)){
									if(date("Y-m-d", filemtime($countryfilename)) != date('Y-m-d')){
										$this->search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
									}else{
										$this->search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
									}
								}else{
									$this->search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
								}

							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								$this->search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
							}

							/*country data*/

							/*graph data*/

							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$graphfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json';
								if(file_exists($graphfilename)){
									if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){
										$this->search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId);
									}else{
										$this->search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId);
									}
								}else{
									$this->search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId);
								}

							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								$this->search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId);
							}


							/*graph data*/

						}					
					}

				}
			}catch(\Exception $e){
				return $e->getMessage();
			}
		}

		public function checkConsoleData($campaignID,$user_id,$google_console_id,$console_account_id){
			$get_profile_data = SearchConsoleUrl::where('id',$console_account_id)->first();
			$profile_url = $get_profile_data->siteUrl;

			$error	 = array();

			$getAnalytics  = SearchConsoleUsers::where('user_id',$user_id)->where('id',$google_console_id)->first();
			if($getAnalytics){
				$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);

				$refresh_token  = $getAnalytics->google_refresh_token;

				$start_date = date('Y-m-d', strtotime("-1 week", strtotime(date('Y-m-d'))));
				$end_date = date('Y-m-d');

				try{
					$page	= new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
					$page->setStartDate($start_date);
					$page->setEndDate($end_date);
					$page->setDimensions(['date']);
					$page->setSearchType('web');

					$service = new \Google_Service_Webmasters($client);
					$pages = $service->searchanalytics->query($profile_url, $page);

					

					$result['status'] = 1;
					$result['message'] = $pages;	
				}catch(\Exception $e){
					$error = json_decode($e->getMessage(),true);
					$result['status'] = 0;
					$result['message'] = $error['error']['message'];
				}

				// echo "<pre>";
				// 	print_r($result);
				// 	die;
				return $result;
			}
			

		}

		public function ajax_google_ads_campaigns ($domain=null,$account_id =null, $campaignID = null){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$getData = GoogleAdsCustomer::where('user_id',$user_id)->where('google_ads_id',$account_id)->where('can_manage_clients',0)->where('name','!=','NULL')->get();
			
			
			$li	=	'<option value=""><--Select Account--></option>';
			if(!empty($getData)) {
				foreach($getData as $result) {
					$li	.= '<option value="'.$result->id.'">'.$result->name.'</option>';
				} 
				
			}else{
				$li	.= '<option value="">No Result Found</option>';
			}
			
			return $li;
			
		}
		
		public function ajax_save_google_ads_data(Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->request_id)
			->update([
				'google_ads_campaign_id'=>$request->ads_accounts,
				'google_ads_id'=>$request->existing_ads_accounts
			]);

			if($update) {			
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error'; // could not register
			}				
			return json_encode($response); 
		}
		

		public function ajax_save_campaign_general_settings(Request $request){
			//dd($request->all());
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$update = SemrushUserAccount::where('id',$request['request_id'])->update([
				'domain_name'=>$request['domain_name'],
				'domain_url'=>$request['domain_url'],
				'domain_register'=>date('Y-m-d',strtotime($request['domain_register'])),
				'regional_db'=>$request['regional_db'],
				'clientName'=>$request['clientName'],
				//'tags'=>$request['tags'],
				'modified'=>now()
			]);


			// if(!empty($request['tags'])){
			// 	CampaignTag::where('request_id',$request['request_id'])->delete();
			// 	$tags = explode(',',$request['tags']);
			// 	foreach ($tags as $key => $value) {
			// 		CampaignTag::create([
			// 			'user_id'=>$user_id,
			// 			'request_id'=>$request['request_id'],
			// 			'tag'=>trim($value)
			// 		]);
			// 	}

			// }
			if($update){
				$response['status'] = 'success';
			}else{
				$response['status'] = 'error';
			}

			return response()->json($response);

		}


		public function ajax_save_campaign_white_label(Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$ifExists  = ProfileInfo::where('user_id',$user_id)->where('request_id',$request['request_id'])->first();

			if(!empty($ifExists)){
				$update = ProfileInfo::where('id',$ifExists->id)->update([
					'email'=>$request['email'],
					'contact_no'=>$request['mobile'],
					'client_name'=>$request['client_name'],
					'company_name'=>$request['company_name']
					//,
					// 'manager_name'=>$request['manager_name'],
					// 'manager_email'=>$request['manager_email']
				]);

				if($update){
					$response['status'] = 'success';
					$response['message'] = 'White Label details updated successfully!';
				}else{
					$response['status'] = 'error';
				}
			} else {
				$create = ProfileInfo::create([
					'request_id'=>$request['request_id'],
					'user_id'=>$user_id,
					'email'=>$request['email'],
					'contact_no'=>$request['mobile'],
					'client_name'=>$request['client_name'],
					'company_name'=>$request['company_name']
					// ,
					// 'manager_name'=>$request['manager_name'],
					// 'manager_email'=>$request['manager_email']
				]);

				if($create){
					$response['status'] = 'success';
					$response['message'] = 'White Label details updated successfully!';
				}else{
					$response['status'] = 'error';
				}
			}

			return response()->json($response);
		}


		public function ajax_upload_agency_logo(Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$request_id = $request['request_id'];

			if($request->has('logo')){
				$image = $request->file('logo');
				$name = pathinfo($request->file('logo')->getClientOriginalName(), PATHINFO_FILENAME);
				$folder = 'agency_logo/'.$user_id.'/'.$request_id.'/';

				$disk = 'public';
				$filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
				$file = $image->storeAs($folder, $name . '.' . $image->getClientOriginalExtension(), $disk);




				$image_url = URL::asset('public/storage/'.$filePath);
				$response = array('status' => 'success' );
				$key = 'logo_image_delete';
				$url = '/ajax_upload_agency_logo';
				$p1[0] = $image_url; // sends the data
				$p2[0] = ['caption' => $name, 'size' => $_FILES['logo']['size'], 'width' => '120px', 'url' => $url, 'key' => $key, 'extra' => array('action' => 'logo_image_delete', 'request_id' => $request_id) ];
				return response()->json([
					'initialPreview' => $p1, 
					'initialPreviewConfig' => $p2,   
					'append' => true 
				]); 


                // return response()->json(['uploaded' => '/'.$folder.$name]);
			}
		}

		public function ajax_upload_manager_image(Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$request_id = $request['request_id'];

			if($request->has('manager_image')){
				$image = $request->file('manager_image');
				$name = pathinfo($request->file('manager_image')->getClientOriginalName(), PATHINFO_FILENAME);
				$folder = 'agency_managers/'.$user_id.'/'.$request_id.'/';

				$disk = 'public';
				$filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
				$file = $image->storeAs($folder, $name . '.' . $image->getClientOriginalExtension(), $disk);


				$image_url = URL::asset('public/storage/'.$filePath);
				$response = array('status' => 'success' );
				$key = 'logo_image_delete';
				$url = '/ajax_upload_agency_logo';
				$p1[0] = $image_url; // sends the data
				$p2[0] = ['caption' => $name, 'size' => $_FILES['manager_image']['size'], 'width' => '120px', 'url' => $url, 'key' => $key, 'extra' => array('action' => 'logo_image_delete', 'request_id' => $request_id) ];
				return response()->json([
					'initialPreview' => $p1, 
					'initialPreviewConfig' => $p2,   
					'append' => true 
				]); 

			}
		}


		public function ajax_remove_agency_logo(Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$request_id = $request['request_id'];

			$path  = 'public/storage/agency_logo/'.$user_id.'/'.$request_id.'/';
			
			if (file_exists($path)) {
			$files 		= 	glob($path."*"); // get all file names
			foreach($files as $file){ // iterate files
				if(is_file($file))
				unlink($file); // delete file
		}	
		$response = array('status' => 'success' );
	} else {
		$response = array('status' => 'error' );
	}
	return $response;

}

public function ajax_remove_manager_image(Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$request_id = $request['request_id'];

			$path  = 'public/storage/agency_managers/'.$user_id.'/'.$request_id.'/';
			
			if (file_exists($path)) {
			$files 		= 	glob($path."*"); // get all file names
			foreach($files as $file){ // iterate files
				if(is_file($file))
				unlink($file); // delete file
		}	
		$response = array('status' => 'success' );
	} else {
		$response = array('status' => 'error' );
	}
	return $response;

}


public function ajax_save_dashboard_settings(Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$dashboards = $request['dashboard'];
			$ids = implode(',',array_keys($dashboards));

			
			$delete = CampaignDashboard::where('user_id',$user_id)->where('request_id',$request['request_id'])->delete();
			if($delete){
				SemrushUserAccount::where('id',$request['request_id'])->update([
					'dashboard_type'=>$ids
				]);
				foreach($dashboards as $key=>$value){
					if($value == 'on'){
						$status = 1;
					}else{
						$status =0;
					}

					$create = CampaignDashboard::create([
						'request_id'=>$request['request_id'],
						'dashboard_id'=>$key,
						'dashboard_status'=>$status,
						'user_id'=>$user_id
					]);

					if($create){
						$response['status'] = 1;
						$response['message'] ='Dashboard Settings Updated successfully!';
					}else{
						$response['status'] = 0;
						$response['message'] ='Error updating Dashboard Settings!';
					}

				}
				return response()->json($response);
			}
			
		}


		private function search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year){

			$final_query = array();
			$month_query_keys = $month_query_clicks = $month_query_impressions = '';
			$three_query_keys = $three_query_clicks = $three_query_impressions = '';
			$six_query_keys = $six_query_clicks = $six_query_impressions = '';
			$nine_query_keys = $nine_query_clicks = $nine_query_impressions = '';
			$one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = '';
			$query_dates=$query_converted_dates=	$query_keys = $query_clicks = $query_impressions  = '';

			$nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = array();

			$search_console_query = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$start_date,$end_date);	
			$search_console_query_one = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$one_month,$end_date);	

			$search_console_query_three = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$three_month,$end_date);	
			$search_console_query_six = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$six_month,$end_date);	
			$search_console_query_nine = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$nine_month,$end_date);	
			$search_console_query_year = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$one_year,$end_date);	



			if(!empty($search_console_query_one)){
				foreach($search_console_query_one->getRows() as $month_query){

					$month_query_keys = $month_query->keys[0];
					$month_query_clicks = $month_query->clicks;
					$month_query_impressions = $month_query->impressions;

					$month_query_array[] = array(
						'month_query_keys'=>$month_query_keys,
						'month_query_clicks' =>$month_query_clicks,
						'month_query_impressions'=>$month_query_impressions
					);
				}

			}

			if(!empty($search_console_query_three)){
				foreach($search_console_query_three->getRows() as $three_query){
					$three_query_keys = $three_query->keys[0]	;
					$three_query_clicks = $three_query->clicks;
					$three_query_impressions = $three_query->impressions;

					$three_query_array[] = array(
						'three_query_keys'=>$three_query_keys,
						'three_query_clicks' =>$three_query_clicks,
						'three_query_impressions'=>$three_query_impressions
					);
				}
			}

			if(!empty($search_console_query_six)){
				foreach($search_console_query_six->getRows() as $six_query){
					$six_query_keys = $six_query->keys[0]	;
					$six_query_clicks = $six_query->clicks;
					$six_query_impressions = $six_query->impressions;


					$six_query_array[] = array(
						'six_query_keys'=>$six_query_keys,
						'six_query_clicks' =>$six_query_clicks,
						'six_query_impressions'=>$six_query_impressions
					);
				}
			}							

			if(!empty($search_console_query_nine)){
				foreach($search_console_query_nine->getRows() as $nine_query){
					$nine_query_keys = $nine_query->keys[0]	;
					$nine_query_clicks = $nine_query->clicks;
					$nine_query_impressions = $nine_query->impressions;

					$nine_query_array[] = array(
						'nine_query_keys'=>$nine_query_keys,
						'nine_query_clicks' =>$nine_query_clicks,
						'nine_query_impressions'=>$nine_query_impressions
					);
				}
			}

			if(!empty($search_console_query_year)){
				foreach($search_console_query_year->getRows() as $one_year_query){
					$one_year_query_keys = $one_year_query->keys[0]	;
					$one_year_query_clicks = $one_year_query->clicks;
					$one_year_query_impressions = $one_year_query->impressions;

					$one_year_query_array[] = array(
						'one_year_query_keys'=>$one_year_query_keys,
						'one_year_query_clicks' =>$one_year_query_clicks,
						'one_year_query_impressions'=>$one_year_query_impressions
					);
				}
			}

			if(!empty($search_console_query)){
				foreach($search_console_query->getRows() as $query_key=> $query){
					$query_keys = $query->keys[0]	;
					$query_clicks = $query->clicks;
					$query_impressions = $query->impressions;


					$query_array[] = array(
						'query_keys'=>$query_keys,
						'query_clicks' =>$query_clicks,
						'query_impressions'=>$query_impressions
					);
				}
			} 							

			$final_query = array(
				'month_query_array'=>$month_query_array,
				'three_query_array'=>$three_query_array,
				'six_query_array'=>$six_query_array,
				'nine_query_array'=>$nine_query_array,
				'one_year_query_array'=>$one_year_query_array,
				'two_year_query_array'=>$query_array
			);


	// if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 	$queryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json';
	// 	if(date("Y-m-d", filemtime($queryfilename)) != date('Y-m-d')){
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json', print_r(json_encode($final_query,true),true));
		//}
	// }
	// elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 	mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
	// 	file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json', print_r(json_encode($final_query,true),true));
	// }

			$month_query_keys = $month_query_clicks = $month_query_impressions = '';
			$three_query_keys = $three_query_clicks = $three_query_impressions = '';
			$six_query_keys = $six_query_clicks = $six_query_impressions = '';
			$nine_query_keys = $nine_query_clicks = $nine_query_impressions = '';
			$one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = '';
			$query_dates=$query_converted_dates=	$query_keys = $query_clicks = $query_impressions  = '';

			$nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = $final_query = array();
		}

		private function search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year){
			// dd('1936');
			$month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

			$three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
			$six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
			$nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
			$year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
			$two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
			$month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();


			$one_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$one_month,$end_date);
			$three_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$three_month,$end_date);
			$six_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$six_month,$end_date);
			$nine_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$nine_month,$end_date);
			$year_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$one_year,$end_date);
			$two_year_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$start_date,$end_date);


			if(!empty($one_search_console_device)){
				foreach($one_search_console_device->getRows() as $month_device){

					$month_device_keys = $month_device->keys[0];
					$month_device_clicks = $month_device->clicks;
					$month_device_impressions = $month_device->impressions;
					$month_device_ctr = $month_device->ctr;
					$month_device_position = $month_device->position;

					$month_device_array[] = array(
						'month_device_keys'=>$month_device_keys,
						'month_device_clicks' =>$month_device_clicks,
						'month_device_impressions'=>$month_device_impressions,
						'month_device_ctr'=>$month_device_ctr,
						'month_device_position'=>$month_device_position
					);
				}
			}


			if(!empty($three_search_console_device)){
				foreach($three_search_console_device->getRows() as $three_device){

					$three_device_keys = $three_device->keys[0];
					$three_device_clicks = $three_device->clicks;
					$three_device_impressions = $three_device->impressions;
					$three_device_ctr = $three_device->ctr;
					$three_device_position = $three_device->position;

					$three_device_array[] = array(
						'three_device_keys'=>$three_device_keys,
						'three_device_clicks' =>$three_device_clicks,
						'three_device_impressions'=>$three_device_impressions,
						'three_device_ctr'=>$three_device_ctr,
						'three_device_position'=>$three_device_position
					);
				}
			}


			if(!empty($six_search_console_device)){
				foreach($six_search_console_device->getRows() as $six_device){

					$six_device_keys = $six_device->keys[0];
					$six_device_clicks = $six_device->clicks;
					$six_device_impressions = $six_device->impressions;
					$six_device_ctr = $six_device->ctr;
					$six_device_position = $six_device->position;

					$six_device_array[] = array(
						'six_device_keys'=>$six_device_keys,
						'six_device_clicks' =>$six_device_clicks,
						'six_device_impressions'=>$six_device_impressions,
						'six_device_ctr'=>$six_device_ctr,
						'six_device_position'=>$six_device_position
					);
				}
			}


			if(!empty($nine_search_console_device)){
				foreach($nine_search_console_device->getRows() as $nine_device){

					$nine_device_keys = $nine_device->keys[0];
					$nine_device_clicks = $nine_device->clicks;
					$nine_device_impressions = $nine_device->impressions;
					$nine_device_ctr = $nine_device->ctr;
					$nine_device_position = $nine_device->position;

					$nine_device_array[] = array(
						'nine_device_keys'=>$nine_device_keys,
						'nine_device_clicks' =>$nine_device_clicks,
						'nine_device_impressions'=>$nine_device_impressions,
						'nine_device_ctr'=>$nine_device_ctr,
						'nine_device_position'=>$nine_device_position
					);
				}
			}


			if(!empty($year_console_device)){
				foreach($year_console_device->getRows() as $year_device){

					$year_device_keys = $year_device->keys[0];
					$year_device_clicks = $year_device->clicks;
					$year_device_impressions = $year_device->impressions;
					$year_device_ctr = $year_device->ctr;
					$year_device_position = $year_device->position;

					$year_device_array[] = array(
						'year_device_keys'=>$year_device_keys,
						'year_device_clicks' =>$year_device_clicks,
						'year_device_impressions'=>$year_device_impressions,
						'year_device_ctr'=>$year_device_ctr,
						'year_device_position'=>$year_device_position
					);
				}
			}


			if(!empty($two_year_search_console_device)){
				foreach($two_year_search_console_device->getRows() as $two_year_device){

					$two_year_device_keys = $two_year_device->keys[0];
					$two_year_device_clicks = $two_year_device->clicks;
					$two_year_device_impressions = $two_year_device->impressions;
					$two_year_device_ctr = $two_year_device->ctr;
					$two_year_device_position = $two_year_device->position;

					$two_year_device_array[] = array(
						'two_year_device_keys'=>$two_year_device_keys,
						'two_year_device_clicks' =>$two_year_device_clicks,
						'two_year_device_impressions'=>$two_year_device_impressions,
						'two_year_device_ctr'=>$two_year_device_ctr,
						'two_year_device_position'=>$two_year_device_position
					);
				}
			}

			$final_device = array(
				'month_device_array'=>$month_device_array,
				'three_device_array'=>$three_device_array,
				'six_device_array'=>$six_device_array,
				'nine_device_array'=>$nine_device_array,
				'year_device_array'=>$year_device_array,
				'two_year_device_array'=>$two_year_device_array
			);

			if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
				file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json', print_r(json_encode($final_device,true),true));

			}
			elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
				mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
				file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json', print_r(json_encode($final_device,true),true));
			}




			$month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

			$three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
			$six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
			$nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
			$year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
			$two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
			$month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();
		}

		private function search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year){



			$month_page_keys = $month_page_clicks = $month_page_impressions = '';
			$three_page_keys = $three_page_clicks = $three_page_impressions = '';
			$six_page_keys = $six_page_clicks = $six_page_impressions = '';
			$nine_page_keys = $nine_page_clicks = $nine_page_impressions = '';
			$year_page_keys = $year_page_clicks = $year_page_impressions = '';
			$two_year_page_keys = $two_year_page_clicks = $two_year_page_impressions = '';
			$month_page_array = $three_page_array = $six_page_array = $nine_page_array =  $year_page_array = $two_year_page_array = $final_page = array();



			$one_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$one_month,$end_date);
			$three_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$three_month,$end_date);
			$six_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$six_month,$end_date);
			$nine_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$nine_month,$end_date);
			$one_year_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$one_year,$end_date);
			$two_year_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$start_date,$end_date);




			if(!empty($one_month_page)){
				foreach($one_month_page->getRows() as $month_page){
					$month_page_keys = $month_page->keys[0];
					$month_page_clicks = $month_page->clicks;
					$month_page_impressions = $month_page->impressions;

					$month_page_array[] = array(
						'month_page_keys'=>$month_page_keys,
						'month_page_clicks' =>$month_page_clicks,
						'month_page_impressions'=>$month_page_impressions
					);
				}
			}

			if(!empty($three_month_page)){
				foreach($three_month_page->getRows() as $three_page){
					$three_page_keys = $three_page->keys[0];
					$three_page_clicks = $three_page->clicks;
					$three_page_impressions = $three_page->impressions;

					$three_page_array[] = array(
						'three_page_keys'=>$three_page_keys,
						'three_page_clicks' =>$three_page_clicks,
						'three_page_impressions'=>$three_page_impressions
					);
				}
			}

			if(!empty($six_month_page)){
				foreach($six_month_page->getRows() as $six_page){
					$six_page_keys = $six_page->keys[0];
					$six_page_clicks = $six_page->clicks;
					$six_page_impressions = $six_page->impressions;

					$six_page_array[] = array(
						'six_page_keys'=>$six_page_keys,
						'six_page_clicks' =>$six_page_clicks,
						'six_page_impressions'=>$six_page_impressions
					);
				}
			}

			if(!empty($nine_month_page)){
				foreach($nine_month_page->getRows() as $nine_page){
					$nine_page_keys = $nine_page->keys[0];
					$nine_page_clicks = $nine_page->clicks;
					$nine_page_impressions = $nine_page->impressions;

					$nine_page_array[] = array(
						'nine_page_keys'=>$nine_page_keys,
						'nine_page_clicks' =>$nine_page_clicks,
						'nine_page_impressions'=>$nine_page_impressions
					);
				}
			}

			if(!empty($one_year_page)){
				foreach($one_year_page->getRows() as $year_page){
					$year_page_keys = $year_page->keys[0];
					$year_page_clicks = $year_page->clicks;
					$year_page_impressions = $year_page->impressions;

					$year_page_array[] = array(
						'year_page_keys'=>$year_page_keys,
						'year_page_clicks' =>$year_page_clicks,
						'year_page_impressions'=>$year_page_impressions
					);
				}
			}

			if(!empty($two_year_page)){
				foreach($two_year_page->getRows() as $two_yearpage){
					$two_year_page_keys = $two_yearpage->keys[0];
					$two_year_page_clicks = $two_yearpage->clicks;
					$two_year_page_impressions = $two_yearpage->impressions;

					$two_year_page_array[] = array(
						'two_year_page_keys'=>$two_year_page_keys,
						'two_year_page_clicks' =>$two_year_page_clicks,
						'two_year_page_impressions'=>$two_year_page_impressions
					);
				}
			}

			$final_page = array(
				'month_page_array'=>$month_page_array,
				'three_page_array'=>$three_page_array,
				'six_page_array'=>$six_page_array,
				'nine_page_array'=>$nine_page_array,
				'year_page_array'=>$year_page_array,
				'two_year_page_array'=>$two_year_page_array
			);

			if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
				file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json', print_r(json_encode($final_page,true),true));
			}
			elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
				mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
				file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json', print_r(json_encode($final_page,true),true));
			}


			$month_page_keys = $month_page_clicks = $month_page_impressions = '';
			$three_page_keys = $three_page_clicks = $three_page_impressions = '';
			$six_page_keys = $six_page_clicks = $six_page_impressions = '';
			$nine_page_keys = $nine_page_clicks = $nine_page_impressions = '';
			$year_page_keys = $year_page_clicks = $year_page_impressions = '';
			$two_year_page_keys = $two_year_page_clicks = $two_year_page_impressions = '';
			$month_page_array = $three_page_array = $six_page_array = $nine_page_array =  $year_page_array = $two_year_page_array = $final_page = array();

		}

		private function search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year){


			$month_country_keys = $month_country_clicks = $month_country_impressions = $month_country_ctr = $month_country_position =  '';
			$threeCountry_keys = $threeCountry_clicks = $threeCountry_impressions = $threeCountry_ctr = $threeCountry_position =  '';
			$six_month_Country_keys = $six_month_Country_clicks = $six_month_Country_impressions = $six_month_Country_ctr = $six_month_Country_position =  '';
			$nine_month_Country_keys = $nine_month_Country_clicks = $nine_month_Country_impressions = $nine_month_Country_ctr = $nine_month_Country_position =  '';
			$year_Country_keys = $year_Country_clicks = $year_Country_impressions = $year_Country_ctr = $year_Country_position =  '';
			$two_year_Country_keys = $two_year_Country_clicks = $two_year_Country_impressions = $two_year_Country_ctr = $two_year_Country_position =  '';


			$month_country_array = $three_country_array = $six_country_array = $nine_country_array = $year_country_array = $final_country = $two_year_country_array =  array();


			$month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$one_month,$end_date);
			$three_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$three_month,$end_date);
			$six_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$six_month,$end_date);
			$nine_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$nine_month,$end_date);
			$one_year_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$one_year,$end_date);
			$two_year_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$start_date,$end_date);


			if(!empty($month_country)){
				foreach($month_country->getRows() as $monthCountry){

					$month_country_keys = $monthCountry->keys[0];
					$month_country_clicks = $monthCountry->clicks;
					$month_country_impressions = $monthCountry->impressions;
					$month_country_ctr = $monthCountry->ctr;
					$month_country_position = $monthCountry->position;

					$month_country_array[] = array(
						'month_country_keys'=>$month_country_keys,
						'month_country_clicks' =>$month_country_clicks,
						'month_country_impressions'=>$month_country_impressions,
						'month_country_ctr'=>$month_country_ctr,
						'month_country_position'=>$month_country_position
					);
				}
			}

			if(!empty($three_month_country)){
				foreach($three_month_country->getRows() as $threeCountry){

					$threeCountry_keys = $threeCountry->keys[0];
					$threeCountry_clicks = $threeCountry->clicks;
					$threeCountry_impressions = $threeCountry->impressions;
					$threeCountry_ctr = $threeCountry->ctr;
					$threeCountry_position = $threeCountry->position;

					$three_country_array[] = array(
						'threeCountry_keys'=>$threeCountry_keys,
						'threeCountry_clicks' =>$threeCountry_clicks,
						'threeCountry_impressions'=>$threeCountry_impressions,
						'threeCountry_ctr'=>$threeCountry_ctr,
						'threeCountry_position'=>$threeCountry_position
					);
				}

			}

			if(!empty($six_month_country)){
				foreach($six_month_country->getRows() as $six_month_Country){

					$six_month_Country_keys = $six_month_Country->keys[0];
					$six_month_Country_clicks = $six_month_Country->clicks;
					$six_month_Country_impressions = $six_month_Country->impressions;
					$six_month_Country_ctr = $six_month_Country->ctr;
					$six_month_Country_position = $six_month_Country->position;

					$six_country_array[] = array(
						'six_month_Country_keys'=>$six_month_Country_keys,
						'six_month_Country_clicks' =>$six_month_Country_clicks,
						'six_month_Country_impressions'=>$six_month_Country_impressions,
						'six_month_Country_ctr'=>$six_month_Country_ctr,
						'six_month_Country_position'=>$six_month_Country_position
					);
				}

			}

			if(!empty($nine_month_country)){
				foreach($nine_month_country->getRows() as $nine_month_Country){

					$nine_month_Country_keys = $nine_month_Country->keys[0];
					$nine_month_Country_clicks = $nine_month_Country->clicks;
					$nine_month_Country_impressions = $nine_month_Country->impressions;
					$nine_month_Country_ctr = $nine_month_Country->ctr;
					$nine_month_Country_position = $nine_month_Country->position;

					$nine_country_array[] = array(
						'nine_month_Country_keys'=>$nine_month_Country_keys,
						'nine_month_Country_clicks' =>$nine_month_Country_clicks,
						'nine_month_Country_impressions'=>$nine_month_Country_impressions,
						'nine_month_Country_ctr'=>$nine_month_Country_ctr,
						'nine_month_Country_position'=>$nine_month_Country_position
					);
				}

			}

			if(!empty($one_year_country)){
				foreach($one_year_country->getRows() as $year_Country){

					$year_Country_keys = $year_Country->keys[0];
					$year_Country_clicks = $year_Country->clicks;
					$year_Country_impressions = $year_Country->impressions;
					$year_Country_ctr = $year_Country->ctr;
					$year_Country_position = $year_Country->position;

					$year_country_array[] = array(
						'year_Country_keys'=>$year_Country_keys,
						'year_Country_clicks' =>$year_Country_clicks,
						'year_Country_impressions'=>$year_Country_impressions,
						'year_Country_ctr'=>$year_Country_ctr,
						'year_Country_position'=>$year_Country_position
					);
				}

			}


			if(!empty($two_year_country)){
				foreach($two_year_country->getRows() as $two_year_Country){

					$two_year_Country_keys = $two_year_Country->keys[0];
					$two_year_Country_clicks = $two_year_Country->clicks;
					$two_year_Country_impressions = $two_year_Country->impressions;
					$two_year_Country_ctr = $two_year_Country->ctr;
					$two_year_Country_position = $two_year_Country->position;

					$two_year_country_array[] = array(
						'two_year_Country_keys'=>$two_year_Country_keys,
						'two_year_Country_clicks' =>$two_year_Country_clicks,
						'two_year_Country_impressions'=>$two_year_Country_impressions,
						'two_year_Country_ctr'=>$two_year_Country_ctr,
						'two_year_Country_position'=>$two_year_Country_position
					);
				}

			}

			$final_country = array(
				'month_country_array'=>$month_country_array,
				'three_country_array'=>$three_country_array,
				'six_country_array'=>$six_country_array,
				'nine_country_array'=>$nine_country_array,
				'year_country_array'=>$year_country_array,
				'two_year_country_array'=>$two_year_country_array
			);


			if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
				file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json', print_r(json_encode($final_country,true),true));
			}
			elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
				mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
				file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json', print_r(json_encode($final_country,true),true));

			}

			$month_country_keys = $month_country_clicks = $month_country_impressions = $month_country_ctr = $month_country_position =  '';
			$threeCountry_keys = $threeCountry_clicks = $threeCountry_impressions = $threeCountry_ctr = $threeCountry_position =  '';
			$six_month_Country_keys = $six_month_Country_clicks = $six_month_Country_impressions = $six_month_Country_ctr = $six_month_Country_position =  '';
			$nine_month_Country_keys = $nine_month_Country_clicks = $nine_month_Country_impressions = $nine_month_Country_ctr = $nine_month_Country_position =  '';
			$year_Country_keys = $year_Country_clicks = $year_Country_impressions = $year_Country_ctr = $year_Country_position =  '';
			$two_year_Country_keys = $two_year_Country_clicks = $two_year_Country_impressions = $two_year_Country_ctr = $two_year_Country_position =  '';


			$month_country_array = $three_country_array = $six_country_array = $nine_country_array = $year_country_array = $final_country = $two_year_country_array =  array();

		}


		private function search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId){

			$dates = $converted_dates = $clicks = $impressions = $data_array = array();


			$searchConsoleData = GoogleAnalyticsUsers::getSearchConsoleData($client,$profileUrl,$start_date,$end_date);
			if(!empty($searchConsoleData)){
				foreach($searchConsoleData->getRows() as $data_key=>$data){
					$dates[] = $data->keys[0];
					$converted_dates[] = strtotime($data->keys[0])*1000;
					$clicks[]    = array('t'=>strtotime($data->keys[0])*1000,'y'=>$data->clicks);
					$impressions[] = array('t'=>strtotime($data->keys[0])*1000,'y'=>$data->impressions);
				}

			}


			$data_array = array(
				'dates'=>$dates,
				'converted_dates'=>$converted_dates,
				'clicks' =>$clicks,
				'impressions'=>$impressions
			);

			if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
				file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json', print_r(json_encode($data_array,true),true));
			}
			elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
				mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
				file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json', print_r(json_encode($data_array,true),true));
			}
			$dates = $converted_dates = $clicks = $impressions = array();
		}


		public function campaignsettings ($domain_name, $campaign_id){
			return view('vendor.campaign-settings',['campaign_id'=>$campaign_id]);
		}

	}
