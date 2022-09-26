<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

use Google\Analytics\Admin\V1alpha\AnalyticsAdminServiceClient;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;

use App\SemrushUserAccount;
use App\GoogleAnalyticsUsers;
use App\SearchConsoleUsers;
use App\GoogleAnalyticAccount;
use App\User;
use App\Error;
use App\GoogleUpdate;
use App\ModuleByDateRange;
use App\DfsLanguage;
use Auth;

require 'vendor/autoload.php';

class GoogleAnalytics4Controller extends Controller {

	public function connect_google_analytics_4(Request $request){
		$client = new \Google_Client();
		$client->setApprovalPrompt('force');
		$client->setApplicationName('Agency dashboard');
		$client->addScope(\Google_Service_Analytics::ANALYTICS_READONLY);
		$client->setAuthConfig(\config('app.FILE_PATH').\config('app.ANALYTICS_CONFIG'));
		$client->setAccessType('offline');
		$client->setRedirectUri(\config('app.base_url').'connect_google_analytics_4');
		$client->setAccessType("offline");
		$client->setState($request->campaignId.'/'.$request->provider.'/'.$request->redirectPage);
		$client->setIncludeGrantedScopes(true);

		if ($request->get('code') == NULL){
			$auth_url = $client->createAuthUrl();
			return redirect()->to($auth_url);
		}else{
			$exploded_value = explode('/',$request->state);
			$campaignId = $exploded_value[0];
			$provider = $exploded_value[1];
			$redirectPage = $exploded_value[2];

			$accessToken = $client->fetchAccessTokenWithAuthCode($request->get('code'));
			$client->setAccessToken($accessToken);

			$tokenResponse = $client->getAccessToken();

			$google_oauthV2 = new \Google_Service_Oauth2($client);
			$googleuser = $google_oauthV2->userinfo->get();

			$get_agency_details = SemrushUserAccount::where('id',$campaignId)->first();

			$checkIfExists = GoogleAnalyticsUsers::where('user_id',$get_agency_details->user_id)->where('oauth_uid',$googleuser['id'])->where('oauth_provider',$provider)->first();

			if(empty($checkIfExists)){
				$insert = GoogleAnalyticsUsers::create([
					'user_id'=>$get_agency_details->user_id,
					'google_access_token'=> $tokenResponse['access_token'],
					'google_refresh_token'=>$tokenResponse['refresh_token'],
					'oauth_provider'=>$provider,
					'oauth_uid'=>$googleuser['id'],
					'first_name'=>$googleuser['givenName'],
					'last_name'=>$googleuser['familyName'],
					'email'=>$googleuser['email'],
					'gender'=>$googleuser['gender']??'',
					'locale'=>$googleuser['locale']??'',
					'picture'=>$googleuser['picture']??'',
					'link'=>$googleuser['link']??'',
					'token_type'=>$tokenResponse['token_type'],
					'expires_in'=>$tokenResponse['expires_in'],
					'id_token'=>$tokenResponse['id_token'],
					'service_created'=>$tokenResponse['created']
				]);
				SearchConsoleUsers::updateRefreshNAccessToken($googleuser['email'],$get_agency_details->user_id,$tokenResponse);
				$getLastInsertedId = $insert->id;

				$service_client = GoogleAnalyticAccount::get_admin_service_client($tokenResponse["refresh_token"]);

				GoogleAnalyticAccount::getGoogleAccountsList($service_client,$campaignId,$getLastInsertedId,$get_agency_details->user_id,$provider);						
			}else{
				$refresh_token 	= isset($tokenResponse['refresh_token']) ? $tokenResponse['refresh_token'] : $checkIfExists->google_refresh_token;
				$update = GoogleAnalyticsUsers::where('user_id',$get_agency_details->user_id)->where('oauth_uid',$googleuser['id'])->where('id',$checkIfExists->id)->update([
					'google_access_token'=> $tokenResponse['access_token'],
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
					'token_type'=>$tokenResponse['token_type'],
					'expires_in'=>$tokenResponse['expires_in'],
					'id_token'=>$tokenResponse['id_token'],
					'service_created'=>$tokenResponse['created']
				]);

				if ($client->isAccessTokenExpired()) {
					$client->refreshToken($tokenResponse['refresh_token']);
				}
				SearchConsoleUsers::updateRefreshNAccessToken($googleuser['email'],$get_agency_details->user_id,$tokenResponse);
				
				$service_client = GoogleAnalyticAccount::get_admin_service_client($tokenResponse["refresh_token"]);

				GoogleAnalyticAccount::getGoogleAccountsList($service_client,$campaignId,$checkIfExists->id,$get_agency_details->user_id,$provider);
			}

			echo  "<script>";
			echo "window.close();";
			echo "</script>";
			return;
		}
	}

	public function ajax_get_ga4_emails(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$emails = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','ga4')->get();
		$li	=	'<option value="">Select from existing account</option>'; 
		if(!empty($emails)) {
			foreach($emails as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->email.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return response()->json($li);
	}

	public function ajax_get_ga4_accounts(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$accounts = GoogleAnalyticAccount::where('user_id',$user_id)->where('google_email_id',$request->email)->where('parent_id',0)->get();
		$li	=	'<option value="">Select Account</option>'; 
		if(!empty($accounts)) {
			foreach($accounts as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->display_name.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return response()->json($li);
	}

	public function ajax_get_ga4_properties(Request $request){
		$accounts = GoogleAnalyticAccount::where('parent_id',$request->account_id)->get();
		$li	=	'<option value="">Select Property</option>'; 
		if(!empty($accounts)) {
			foreach($accounts as $result) {
				$li	.= '<option value="'.$result->id.'">'.$result->display_name.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return response()->json($li);
	}

	public function ajax_update_ga4_data(Request $request){
		$start_date = date('Y-m-d',strtotime('-4 year'));
		$week_start_date = date('Y-m-d',strtotime('-7 day'));
		$end_date = date('Y-m-d',strtotime('-1 day'));
		$response = array();

		$user_id = User::get_parent_user_id(Auth::user()->id);
		$get_google_user = GoogleAnalyticsUsers::where('user_id',$user_id)->where('id',$request->email)->first();
		$refresh_token = $get_google_user->google_refresh_token;
		$service = GoogleAnalyticAccount::get_beta_service_client($refresh_token);
		$get_property = GoogleAnalyticAccount::where('id',$request->property)->first();

		$check_week_data = GoogleAnalyticAccount::check_week_data($service,$get_property->property_id,$week_start_date,$end_date);
		
		if($check_week_data['status'] == 0){
			Error::updateOrCreate(
				['request_id' => $request->campaign_id,'module'=> 5],
				['response'=> json_encode($check_week_data['message']),'request_id' => $request->campaign_id,'module'=> 5]
			);
			$response = SemrushUserAccount::display_google_errorMessages(5,$request->campaign_id);

			$response['status'] = 'google-error'; 
			$response['message'] = $check_week_data['message'];
			return response()->json($response);

		}

		$update = SemrushUserAccount::
		where('user_id',$user_id)
		->where('id',$request->campaign_id)
		->update([
			'ga4_email_id'=>$request->email,
			'ga4_account_id'=>$request->account,
			'ga4_property_id'=>$request->property,
			'updated_at' =>now()
		]);

		$log_data = GoogleAnalyticAccount::log_ga4_data($service,$get_property->property_id,$start_date,$end_date,$request->campaign_id);
		if(isset($log_data['status']) && $log_data['status'] == 0){
			$response['status'] = 'error';
			$response['message'] = $log_data['message'];
		}else{
			$ifErrorExists = Error::removeExisitingError(5,$request->campaign_id);
			if(!empty($ifErrorExists)){
				Error::where('id',$ifErrorExists->id)->delete();
			}
			GoogleUpdate::updateTiming($request->campaign_id,'ga4','ga4_type','2');
			$response['status'] = 'success';
			$response['message'] = 'GA4 Account Connected successfully! Fetching Data it may take some time.';

			$getEmail = GoogleAnalyticsUsers::select('id','email')->where('id',$request->email)->first();
			$getAccount = GoogleAnalyticAccount::where('id',$request->account)->first();
			$getproperty = GoogleAnalyticAccount::where('id',$request->property)->first();
			$response['email'] = $getEmail->email;
			$response['account'] = $getAccount->display_name;
			$response['property'] = $getproperty->display_name;
			$response['project_id'] = $request->campaign_id;
			$response['step'] = 5;			
			$response['status'] = 'success';

		}
		return json_encode($response); 	
	}

	public function ajax_disconnect_ga4(Request $request){
		$result = SemrushUserAccount::findOrFail($request->request_id);
		if(!empty($result)){
			SemrushUserAccount::where('id',$request->request_id)->update([
				'ga4_email_id'=>NULL,
				'ga4_account_id'=>NULL,
				'ga4_property_id'=>NULL
			]);

			$ifErrorExists = Error::removeExisitingError(5,$request->request_id);
			if(!empty($ifErrorExists)){
				Error::where('id',$ifErrorExists->id)->delete();
			}

			if (file_exists(env('FILE_PATH').'public/google_analytics_4/'.$request->request_id)) {
				SemrushUserAccount::remove_directory(env('FILE_PATH').'public/google_analytics_4/'.$request->request_id);
			}
			
			$response['status'] = 'success';
		}else{
			$response['status'] = 'error';
		}
		return response()->json($response);
	}

	public function ajax_acquisition_overview(Request $request){

		$today = date('Y-m-d');
		$today_new = date('Y-m-d');
		$campaign_id = $request['campaign_id'];
		$history = ModuleByDateRange::getModuleDateRange($campaign_id,'ga4');

		if (!file_exists(env('FILE_PATH')."public/google_analytics_4/".$campaign_id)) {
			$res['status'] = 0;
		} else {
			$url = env('FILE_PATH')."public/google_analytics_4/".$campaign_id.'/graph.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);
			$last_file_date = end($final->dates);			
			$comparison = 0;  $comparison_period = 'previous_period';$duration = 3;$range_key = 'Last three month';


			if($request->selected_label == 0 && $request->selected_label !== null){
				$comparison = $request->comparison;  $comparison_period = $request->comparison_selected;
				$start_date = date('Y-m-d', strtotime($request->current_start));
				$end_date = date('Y-m-d', strtotime($request->current_end));
				$display_end = $end_date;
				$duration = GoogleAnalyticAccount::get_selected_range($request->selected_label);
				if($duration == 1){
					$range_key = 'Last month';
				}elseif($duration == 3){
					$range_key = 'Last three month';
				}elseif($duration == 6){
					$range_key = 'Last six month';
				}elseif($duration == 9){
					$range_key = 'Last nine month';
				}elseif($duration == 12){
					$range_key = 'Last one year';
				}elseif($duration == 24){
					$range_key = 'Last two year';
				}else{
					$range_key = 'Custom';
				}
			}else{
				$end_date = date('Y-m-d',strtotime('-1 day'));
				$display_end = $end_date;
				$start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
				$history = ModuleByDateRange::getModuleDateRange($campaign_id,'ga4');
				
				if(!empty($history)){
					$comparison = $history->status;
					$comparison_period = $history->comparison;
					$duration = $history->duration;
					if($duration == 1){
						$start_date = date('Y-m-d', strtotime("-1 month", strtotime($end_date)));
						$range_key = 'Last month';
					}elseif($duration == 3){
						$start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
						$range_key = 'Last three month';
					}elseif($duration == 6){
						$start_date = date('Y-m-d', strtotime("-6 month", strtotime($end_date)));
						$range_key = 'Last six month';
					}elseif($duration == 9){
						$start_date = date('Y-m-d', strtotime("-9 month", strtotime($end_date)));
						$range_key = 'Last nine month';
					}elseif($duration == 12){
						$start_date = date('Y-m-d', strtotime("-1 year", strtotime($end_date)));
						$range_key = 'Last one year';
					}elseif($duration == 24){
						$start_date = date('Y-m-d', strtotime("-2 year", strtotime($end_date)));
						$range_key = 'Last two year';
					}
				}

			}

			$project_data = SemrushUserAccount::select('id','regional_db','rank_language')->where('id',$campaign_id)->first();
			if($project_data->rank_language <> null){
				$language_data = DfsLanguage::where('language',$project_data->rank_language)->first();
				$language = ($language_data->language_code)?$language_data->language_code:'en';
				$db = ($language_data->regional_db)?$language_data->regional_db:'us';
			}else{
				$language = 'en';
				$db = 'us';
			}


			$res['start_date'] = SearchConsoleUsers::create_region_dateformat($language,$db,$start_date);
			$res['end_date'] = SearchConsoleUsers::create_region_dateformat($language,$db,$display_end);			

			$dates = $active_users =  $new_users = $previous_active_users = $previous_new_users = $previous_dates = $current_labels = $previous_labels = array();

			$calculated_duration = ModuleByDateRange::calculate_days($start_date,$end_date);
			

			if($comparison_period === 'previous_period'){
				$previous_period_dates = SearchConsoleUsers::calculate_previous_period($start_date,$calculated_duration);
			}else{
				$previous_period_dates = SearchConsoleUsers::calculate_previous_year($start_date,$end_date);	
			}

			$previous_start_date = $previous_period_dates['previous_start_date'];
			$previous_end_date = $previous_period_dates['previous_end_date'];

			$res['previous_start_date'] = SearchConsoleUsers::create_region_dateformat($language,$db,$previous_start_date);
			$res['previous_end_date'] = SearchConsoleUsers::create_region_dateformat($language,$db,$previous_end_date);

			if($comparison === 1 || $comparison === '1'){
				$res['compare_status'] = 1;
				$current_range = date("M d' Y",strtotime($start_date)) .' - '.date("M d' Y",strtotime($display_end));
				$previous_range = ' Compare: '.date("M d' Y",strtotime($previous_start_date)) .' - '.date("M d' Y",strtotime($previous_end_date));
				$display_range = '<p class="ga4_comparison_dates"><b><span class="range-key">'.$range_key.'</span>'.$current_range.'</b><span>'.$previous_range.'</span></p>';
			}else{
				$display_range = '<p class="ga4_comparison_dates"><b><span class="range-key">'.$range_key.'</span>'.date("M d' Y",strtotime($start_date)) .' - '.date("M d' Y",strtotime($display_end)).'</b></p>';
			}

			$current_activeUsers_count = $previous_activeUsers_count = 0; $previous_percentage = 0.00;
			$current_newUsers_count = $previous_newUsers_count = 0; $previous_newUser_percentage = 0.00;

			for($i = strtotime($start_date); $i <= strtotime($end_date); $i = $i+86400){
				$start_date = date('Y-m-d',$i);
				$current_index = array_search($start_date,$final->dates);			

				if($current_index == false){
					$dates[] = date('M d, Y',strtotime($start_date));
					$active_users[] = 0;
					$new_users[] = 0;

					$current_activeUsers_count += 0;
					$current_newUsers_count += 0;
				}else{
					$dates[] = date('M d, Y',strtotime($final->dates[$current_index]));
					$active_users[] = $final->active_users[$current_index];
					$new_users[] = $final->new_users[$current_index];

					$current_activeUsers_count += $final->active_users[$current_index];
					$current_newUsers_count += $final->new_users[$current_index];
				}				

				$current_labels[] = date('l, F d, Y',strtotime($i));
			}

			if($comparison === 1 || $comparison === '1'){
				for($j = strtotime($previous_start_date); $j <= strtotime($previous_end_date); $j = $j+86400){
					$previous_start_date = date('Y-m-d',$j);
					$previous_index = array_search($previous_start_date,$final->dates);

					if($previous_index == false){
						$previous_dates[] = date('M d, Y',strtotime($previous_start_date));
						$previous_active_users[] = 0;
						$previous_new_users[] = 0;

						$previous_activeUsers_count += 0;
						$previous_newUsers_count += 0;
					}else{
						$previous_dates[] = date('M d, Y',strtotime($final->dates[$previous_index]));
						$previous_active_users[] = $final->active_users[$previous_index];
						$previous_new_users[] = $final->new_users[$previous_index];

						$previous_activeUsers_count += $final->active_users[$previous_index];
						$previous_newUsers_count += $final->new_users[$previous_index];
					}					

					$previous_labels[] = date('l, F d, Y',strtotime($j));
				}
				$previous_percentage = ($previous_activeUsers_count > 0) ? round((($current_activeUsers_count - $previous_activeUsers_count)/$previous_activeUsers_count)*100,2) : 0;
				$previous_newUser_percentage = ($previous_newUsers_count > 0)? round((($current_newUsers_count - $previous_newUsers_count)/$previous_newUsers_count)*100,2) : 0;
			}

			$res['dates'] = $dates;
			$res['previous_dates'] = $previous_dates;
			$res['active_users'] = $active_users;
			$res['previous_active_users'] = $previous_active_users;
			$res['new_users'] = $new_users;
			$res['previous_new_users'] = $previous_new_users;
			
			$res['current_activeUsers_count'] = shortNumbers($current_activeUsers_count);

			$res['current_newUsers_count'] = shortNumbers($current_newUsers_count);
			
			$res['previous_percentage'] = $previous_percentage;
			$res['previous_newUser_percentage'] = $previous_newUser_percentage;
			$res['status'] = 1;
			$res['comparison'] = $comparison;
			$res['display_range'] = $display_range;

		} //end case
		return response()->json($res);
	}

	public function ajax_get_latest_googleAnalytics4(Request $request){
		$campaign_id = $request->campaign_id;
		$start_date = date('Y-m-d',strtotime('-4 years'));
		$week_start_date = date('Y-m-d',strtotime('-7 day'));
		$end_date = date('Y-m-d',strtotime('-1 day'));
		$response = array();

		$user_id = User::get_parent_user_id(Auth::user()->id);
		$get_analytics_data = SemrushUserAccount::select('ga4_email_id', 'ga4_account_id', 'ga4_property_id')->where('id',$campaign_id)->first();

		if(isset($get_analytics_data) && !empty($get_analytics_data)){
			$get_google_user = GoogleAnalyticsUsers::where('user_id',$user_id)->where('id',$get_analytics_data->ga4_email_id)->first();
			$refresh_token = $get_google_user->google_refresh_token;
			$service = GoogleAnalyticAccount::get_beta_service_client($refresh_token);
			$get_property = GoogleAnalyticAccount::where('id',$get_analytics_data->ga4_property_id)->first();
			$property_id = $get_property->property_id;

			$check_week_data = GoogleAnalyticAccount::check_week_data($service,$property_id,$week_start_date,$end_date);

			if(isset($check_week_data['status'])  && ($check_week_data['status'] == 0)){
				Error::updateOrCreate(
					['request_id' => $campaign_id,'module'=> 5],
					['response'=> json_encode($check_week_data),'request_id' => $campaign_id,'module'=> 5]
				);
				$response = SemrushUserAccount::display_google_errorMessages(5,$campaign_id);

				$response['status'] = 'google-error'; 
				$response['message'] = $check_week_data['message'];
				return response()->json($response);
			}else{
				$log_data = GoogleAnalyticAccount::log_ga4_data($service,$property_id,$start_date,$end_date,$campaign_id);
				if(isset($log_data['status']) && $log_data['status'] == 0){
					$response['status'] = 'error';
					$response['message'] = $log_data['message'];
				}else{
					GoogleUpdate::updateTiming($campaign_id,'ga4','ga4_type','2');
					$ifErrorExists = Error::removeExisitingError(5,$campaign_id);
					if(!empty($ifErrorExists)){
						Error::where('id',$ifErrorExists->id)->delete();
					}
					$response['status'] = 'success';
					$response['message'] = 'Data fetched successfully.';
				}
			}
		}else{
			$response['status'] = 'error';
			$response['message'] = 'Error, please try again later';
		}

		return response()->json($response);	
	}

	public function ajax_traffic_acquisition(Request $request){
		$res = array();
		$campaign_id = $request['campaign_id'];

		$history = ModuleByDateRange::getModuleDateRange($campaign_id,'ga4');

		if (!file_exists(env('FILE_PATH')."public/google_analytics_4/".$campaign_id)) {
			$res['status'] = 0;
		} else {
			$url = env('FILE_PATH')."public/google_analytics_4/".$campaign_id.'/traffic_acquisition.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);

			$end_date = date('Y-m-d',strtotime('-1 day'));

			$start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
			$comparison = 0;  $comparison_period = 'previous_period'; $duration = 3;$range_key  = 'Last three month';

			if($request->selected_label == 0 && $request->selected_label !== null){
				$comparison = $request->comparison;  $comparison_period = $request->comparison_selected;
				$start_date = date('Y-m-d', strtotime($request->current_start));
				$end_date = date('Y-m-d', strtotime($request->current_end));
				$calculated_duration = ModuleByDateRange::calculate_days($start_date,$end_date);
				$duration = GoogleAnalyticAccount::get_selected_range($request->selected_label);
				if($duration == 1){
					$range_key = 'Last month';
				}elseif($duration == 3){
					$range_key = 'Last three month';
				}elseif($duration == 6){
					$range_key = 'Last six month';
				}elseif($duration == 9){
					$range_key = 'Last nine month';
				}elseif($duration == 12){
					$range_key = 'Last one year';
				}elseif($duration == 24){
					$range_key = 'Last two year';
				}else{
					$range_key = 'Custom';
				}
			}else{
				$history = ModuleByDateRange::getModuleDateRange($campaign_id,'ga4');

				if(!empty($history)){
					$comparison = $history->status;
					$comparison_period = $history->comparison;
					$duration = $history->duration;
					if($duration == 1){
						$start_date = date('Y-m-d', strtotime("-1 month", strtotime($end_date)));
						$range_key  = 'Last month';
					}elseif($duration == 3){
						$start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
						$range_key  = 'Last three month';
					}elseif($duration == 6){
						$start_date = date('Y-m-d', strtotime("-6 month", strtotime($end_date)));
						$range_key  = 'Last six month';
					}elseif($duration == 9){
						$start_date = date('Y-m-d', strtotime("-9 month", strtotime($end_date)));
						$range_key  = 'Last nine month';
					}elseif($duration == 12){
						$start_date = date('Y-m-d', strtotime("-1 year", strtotime($end_date)));
						$range_key  = 'Last one year';
					}elseif($duration == 24){
						$start_date = date('Y-m-d', strtotime("-2 year", strtotime($end_date)));
						$range_key  = 'Last two year';
					}
				}
			}

			$project_data = SemrushUserAccount::select('id','regional_db','rank_language')->where('id',$campaign_id)->first();
			if($project_data->rank_language <> null){
				$language_data = DfsLanguage::where('language',$project_data->rank_language)->first();
				$language = ($language_data->language_code)?$language_data->language_code:'en';
				$db = ($language_data->regional_db)?$language_data->regional_db:'us';
			}else{
				$language = 'en';
				$db = 'us';
			}


			$res['start_date'] = SearchConsoleUsers::create_region_dateformat($language,$db,$start_date);
			$res['end_date'] = SearchConsoleUsers::create_region_dateformat($language,$db,$end_date);			

			$calculated_duration = ModuleByDateRange::calculate_days($start_date,$end_date);


			if($comparison_period === 'previous_period'){
				$previous_period_dates = SearchConsoleUsers::calculate_previous_period($start_date,$calculated_duration);
			}else{
				$previous_period_dates = SearchConsoleUsers::calculate_previous_year($start_date,$end_date);	
			}

			$previous_start_date = $previous_period_dates['previous_start_date'];
			$previous_end_date = $previous_period_dates['previous_end_date'];

			$res['previous_start_date'] = SearchConsoleUsers::create_region_dateformat($language,$db,$previous_start_date);
			$res['previous_end_date'] = SearchConsoleUsers::create_region_dateformat($language,$db,$previous_end_date);

			if($comparison === 1 || $comparison === '1'){
				$current_range = date("M d' Y",strtotime($start_date)) .' - '.date("M d' Y",strtotime($end_date));
				$previous_range = ' Compare: '.date("M d' Y",strtotime($previous_start_date)) .' - '.date("M d' Y",strtotime($previous_end_date));
				$display_range = '<p class="ga4_comparison_dates"><b><span class="range-key">'.$range_key.'</span>'.$current_range.'</b><span>'.$previous_range.'</span></p>';
				$res['compare_status'] = 1;
			}else{
				$display_range = '<p class="ga4_comparison_dates"><b><span class="range-key">'.$range_key.'</span>'.date("M d' Y",strtotime($start_date)) .' - '.date("M d' Y",strtotime($end_date)).'</b></p>';
			}

			$res['display_range'] = $display_range;
			
			$organic_social_count = $organic_search_count = $direct_count = $paid_search_count = $paid_social_count = 0;

			for($i = strtotime($start_date); $i <= strtotime($end_date); $i = $i+86400){
				$dateData = date('Y-m-d',$i);
				$label = date('M d,Y',$i);

				$res['label'][] = date("M d, Y",strtotime($label));
				$res['dates'][] = date("M d, Y",strtotime($dateData));
				$res['organic_social'][] = isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->active_users : 0;
				$res['organic_search'][] = isset($final->$dateData->organic_search)?$final->$dateData->organic_search->active_users:0;
				$res['direct'][] = isset($final->$dateData->direct)?$final->$dateData->direct->active_users:0;
				$res['paid_search'][] = isset($final->$dateData->paid_search)?$final->$dateData->paid_search->active_users:0;
				$res['paid_social'][] = isset($final->$dateData->paid_social)?$final->$dateData->paid_social->active_users:0;

				$organic_social_count += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->active_users : 0;
				$organic_search_count += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->active_users : 0;
				$direct_count += isset($final->$dateData->direct) ? $final->$dateData->direct->active_users : 0;
				$paid_search_count += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->active_users : 0;
				$paid_social_count += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->active_users : 0;
	         } //end-of-for

	         $previous_organic_social_count = $previous_organic_search_count = $previous_paid_search_count = $previous_paid_social_count = $previous_direct_count = 0;

	         for($j = strtotime($previous_start_date); $j <= strtotime($previous_end_date); $j = $j+86400){
	         	$prev_dateData = date('Y-m-d',$j);
	         	$prev_label = date('M d,Y',$j);

	         	$res['previous_dates'][] = date("M d, Y",strtotime($prev_dateData));
	         	$res['previous_label'][] = date("M d, Y",strtotime($prev_label));
	         	$res['previous_organic_social'][] = isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->active_users : 0;
	         	$res['previous_organic_search'][] = isset($final->$prev_dateData->organic_search)?$final->$prev_dateData->organic_search->active_users:0;
	         	$res['previous_direct'][] = isset($final->$prev_dateData->direct)?$final->$prev_dateData->direct->active_users:0;
	         	$res['previous_paid_search'][] = isset($final->$prev_dateData->paid_search)?$final->$prev_dateData->paid_search->active_users:0;
	         	$res['previous_paid_social'][] = isset($final->$prev_dateData->paid_social)?$final->$prev_dateData->paid_social->active_users:0;


	         	$previous_organic_social_count += isset($final->$prev_dateData->organic_social)?$final->$prev_dateData->organic_social->active_users:0;
	         	$previous_organic_search_count += isset($final->$prev_dateData->organic_search)?$final->$prev_dateData->organic_search->active_users:0;
	         	$previous_paid_search_count += isset($final->$prev_dateData->paid_search)?$final->$prev_dateData->paid_search->active_users:0;
	         	$previous_paid_social_count += isset($final->$prev_dateData->paid_social)?$final->$prev_dateData->paid_social->active_users:0;
	         	$previous_direct_count += isset($final->$prev_dateData->direct)?$final->$prev_dateData->direct->active_users:0;
	        } //end-of-for

	        $res['organic_social_count'] = $organic_social_count;
	        $res['organic_search_count'] = $organic_search_count;
	        $res['direct_count'] = $direct_count;
	        $res['paid_search_count'] = $paid_search_count;
	        $res['paid_social_count'] = $paid_social_count;
	        $res['current_label'] = date("M d",strtotime($start_date)) .' - '.date("M d, Y",strtotime($end_date));


	        $res['previous_organic_social_count'] = $previous_organic_social_count;
	        $res['previous_organic_search_count'] = $previous_organic_search_count;
	        $res['previous_direct_count'] = $previous_direct_count;
	        $res['previous_paid_search_count'] = $previous_paid_search_count;
	        $res['previous_paid_social_count'] = $previous_paid_social_count;
	        $res['previous_label'] = date("M d",strtotime($previous_start_date)) .' - '.date("M d, Y",strtotime($previous_end_date));

	        $res['compare_status'] = $comparison;

		} //end case
		return response()->json($res);
	}

	public function ajax_goals_listing_traffic_acquisition(Request $request){
		$response_data = array();
		$campaign_id = $request['campaign_id'];

		$history = ModuleByDateRange::getModuleDateRange($campaign_id,'ga4');

		if (!file_exists(env('FILE_PATH')."public/google_analytics_4/".$campaign_id)) {
			$status = 0;
		} else {
			$status = 1;
			$url = env('FILE_PATH')."public/google_analytics_4/".$campaign_id.'/traffic_acquisition.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);

			$comparison = 0;  $comparison_period = 'previous_period';$duration = 3;

			if($request->selected_label == 0 && $request->selected_label !== null){
				$comparison = $request->comparison;  $comparison_period = $request->comparison_selected;
				$start_date = date('Y-m-d', strtotime($request->current_start));
				$end_date = date('Y-m-d', strtotime($request->current_end));
				$calculated_duration = ModuleByDateRange::calculate_days($start_date,$end_date);
			}else{
				$end_date = date('Y-m-d',strtotime('-1 day'));
				$start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
				
				$history = ModuleByDateRange::getModuleDateRange($campaign_id,'ga4');

				if(!empty($history)){
					$comparison = $history->status;
					$comparison_period = $history->comparison;
					$duration = $history->duration;
					if($duration == 1){
						$start_date = date('Y-m-d', strtotime("-1 month", strtotime($end_date)));
					}elseif($duration == 3){
						$start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
					}elseif($duration == 6){
						$start_date = date('Y-m-d', strtotime("-6 month", strtotime($end_date)));
					}elseif($duration == 9){
						$start_date = date('Y-m-d', strtotime("-9 month", strtotime($end_date)));
					}elseif($duration == 12){
						$start_date = date('Y-m-d', strtotime("-1 year", strtotime($end_date)));
					}elseif($duration == 24){
						$start_date = date('Y-m-d', strtotime("-2 year", strtotime($end_date)));
					}
				}
				$calculated_duration = ModuleByDateRange::calculate_days($start_date,$end_date);
			}


			$os_activeUsers = $os_sessions = $os_engaged_sessions = $os_userEngagementDuration = $os_eventsPerSession = $os_engagementRate = $os_eventCount = $os_conversions = $os_totalRevenue = 0;
			$osearch_activeUsers = $osearch_sessions = $osearch_engaged_sessions = $osearch_userEngagementDuration = $osearch_eventsPerSession = $osearch_engagementRate = $osearch_eventCount = $osearch_conversions = $osearch_totalRevenue = 0;
			$paidSocial_activeUsers = $paidSocial_sessions = $paidSocial_engaged_sessions = $paidSocial_userEngagementDuration = $paidSocial_eventsPerSession = $paidSocial_engagementRate = $paidSocial_eventCount = $paidSocial_conversions = $paidSocial_totalRevenue = 0;
			$paidSearch_activeUsers = $paidSearch_sessions = $paidSearch_engaged_sessions = $paidSearch_userEngagementDuration = $paidSearch_eventsPerSession = $paidSearch_engagementRate = $paidSearch_eventCount = $paidSearch_conversions = $paidSearch_totalRevenue = 0;
			$direct_activeUsers = $direct_sessions = $direct_engaged_sessions = $direct_userEngagementDuration = $direct_eventsPerSession = $direct_engagementRate = $direct_eventCount = $direct_conversions = $direct_totalRevenue = 0;

			if($comparison_period === 'previous_period'){
				$previous_period_dates = SearchConsoleUsers::calculate_previous_period($start_date,$calculated_duration);
			}else{
				$previous_period_dates = SearchConsoleUsers::calculate_previous_year($start_date,$end_date);	
			}
			
			$previous_start_date = $previous_period_dates['previous_start_date'];
			$previous_end_date = $previous_period_dates['previous_end_date'];
			$previous_calculated_duration = ModuleByDateRange::calculate_days($previous_start_date,$previous_end_date);

			/*current duration data*/
			$display_range = date("M d, Y",strtotime($start_date)) .' - '.date("M d' Y",strtotime($end_date));

			for($i = strtotime($start_date); $i <= strtotime($end_date); $i = $i+86400){
				$dateData = date('Y-m-d',$i);
				/*organic social*/
				$os_activeUsers += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->active_users : 0;
				$os_sessions += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->sessions : 0;
				$os_engaged_sessions += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->engaged_sessions : 0;
				$os_userEngagementDuration += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->user_engagement_duration : 0;
				$os_eventsPerSession += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->events_per_session : 0;
				$os_engagementRate += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->engagement_rate : 0;
				$os_eventCount += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->event_count : 0;
				$os_conversions += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->conversions : 0;
				$os_totalRevenue += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->total_revenue : 0;
				/*organic search*/
				$osearch_activeUsers += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->active_users : 0;
				$osearch_sessions += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->sessions : 0;
				$osearch_engaged_sessions += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->engaged_sessions : 0;
				$osearch_userEngagementDuration += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->user_engagement_duration : 0;
				$osearch_eventsPerSession += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->events_per_session : 0;
				$osearch_engagementRate += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->engagement_rate : 0;
				$osearch_eventCount += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->event_count : 0;
				$osearch_conversions += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->conversions : 0;
				$osearch_totalRevenue += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->total_revenue : 0;
				/*paid social*/
				$paidSocial_activeUsers += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->active_users : 0;
				$paidSocial_sessions += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->sessions : 0;
				$paidSocial_engaged_sessions += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->engaged_sessions : 0;
				$paidSocial_userEngagementDuration += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->user_engagement_duration : 0;
				$paidSocial_eventsPerSession += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->events_per_session : 0;
				$paidSocial_engagementRate += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->engagement_rate : 0;
				$paidSocial_eventCount += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->event_count : 0;
				$paidSocial_conversions += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->conversions : 0;
				$paidSocial_totalRevenue += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->total_revenue : 0;
				/*paid social*/
				$paidSearch_activeUsers += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->active_users : 0;
				$paidSearch_sessions += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->sessions : 0;
				$paidSearch_engaged_sessions += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->engaged_sessions : 0;
				$paidSearch_userEngagementDuration += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->user_engagement_duration : 0;
				$paidSearch_eventsPerSession += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->events_per_session : 0;
				$paidSearch_engagementRate += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->engagement_rate : 0;
				$paidSearch_eventCount += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->event_count : 0;
				$paidSearch_conversions += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->conversions : 0;
				$paidSearch_totalRevenue += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->total_revenue : 0;
				/*direct*/
				$direct_activeUsers += isset($final->$dateData->direct) ? $final->$dateData->direct->active_users : 0;
				$direct_sessions += isset($final->$dateData->direct) ? $final->$dateData->direct->sessions : 0;
				$direct_engaged_sessions += isset($final->$dateData->direct) ? $final->$dateData->direct->engaged_sessions : 0;
				$direct_userEngagementDuration += isset($final->$dateData->direct) ? $final->$dateData->direct->user_engagement_duration : 0;
				$direct_eventsPerSession += isset($final->$dateData->direct) ? $final->$dateData->direct->events_per_session : 0;
				$direct_engagementRate += isset($final->$dateData->direct) ? $final->$dateData->direct->engagement_rate : 0;
				$direct_eventCount += isset($final->$dateData->direct) ? $final->$dateData->direct->event_count : 0;
				$direct_conversions += isset($final->$dateData->direct) ? $final->$dateData->direct->conversions : 0;
				$direct_totalRevenue += isset($final->$dateData->direct) ? $final->$dateData->direct->total_revenue : 0;	           
	         } //end-of-for

	         $os_average_engagement_time_per_session = ($os_sessions > 0)? $os_userEngagementDuration/$os_sessions :$os_userEngagementDuration;
	         $osearch_average_engagement_time_per_session = ($osearch_sessions > 0)? $osearch_userEngagementDuration/$osearch_sessions: $osearch_userEngagementDuration;
	         $paidSocial_average_engagement_time_per_session = ($paidSocial_sessions > 0)?($paidSocial_userEngagementDuration/$paidSocial_sessions): $paidSocial_userEngagementDuration;
	         $paidSearch_average_engagement_time_per_session = ($paidSearch_sessions > 0)?$paidSearch_userEngagementDuration/$paidSearch_sessions:$paidSearch_userEngagementDuration;
	         $direct_average_engagement_time_per_session =($direct_sessions > 0)? $direct_userEngagementDuration/$direct_sessions :$direct_userEngagementDuration;

	         $total_active_users = $os_activeUsers + $osearch_activeUsers + $paidSocial_activeUsers + $paidSearch_activeUsers + $direct_activeUsers;
	         $total_sessions = $os_sessions + $osearch_sessions + $paidSocial_sessions + $paidSearch_sessions + $direct_sessions;
	         $total_engaged_sessions = $os_engaged_sessions + $osearch_engaged_sessions + $paidSocial_engaged_sessions + $paidSearch_engaged_sessions + $direct_engaged_sessions;
	         $total_eventsPerSession = ($os_eventsPerSession + $osearch_eventsPerSession + $paidSocial_eventsPerSession + $paidSearch_eventsPerSession + $direct_eventsPerSession)/$calculated_duration;
	         $total_engagementRate = ($os_engagementRate + $osearch_engagementRate + $paidSocial_engagementRate + $paidSearch_engagementRate + $direct_engagementRate)/5;
	         $total_eventCount = $os_eventCount + $osearch_eventCount + $paidSocial_eventCount + $paidSearch_eventCount + $direct_eventCount;
	         $total_conversions = $os_conversions + $osearch_conversions + $paidSocial_conversions + $paidSearch_conversions + $direct_conversions;
	         $total_totalRevenue = $os_totalRevenue + $osearch_totalRevenue + $paidSocial_totalRevenue + $paidSearch_totalRevenue + $direct_totalRevenue;

	         $average_engagement_time_per_session = $os_average_engagement_time_per_session + $osearch_average_engagement_time_per_session + $paidSocial_average_engagement_time_per_session + $paidSearch_average_engagement_time_per_session + $direct_average_engagement_time_per_session;

	         $total_userEngagementDuration = GoogleAnalyticAccount::calculate_time($average_engagement_time_per_session);

	         $total_engaged_sessions_perSession = ($total_active_users > 0) ? $total_engaged_sessions/$total_active_users: $total_engaged_sessions;

	         $response_data['current']['organic_social'] = [
	         	'channel_group' => 'Organic Social',
	         	'active_users' => $os_activeUsers,
	         	'sessions' => $os_sessions,
	         	'engaged_sessions' => $os_engaged_sessions,
	         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($os_average_engagement_time_per_session),
	         	'engaged_sessions_per_user' => ($os_activeUsers > 0) ? number_format($os_engaged_sessions/$os_activeUsers,2) : number_format($os_engaged_sessions,2),
	         	'events_per_session' => number_format($os_eventsPerSession/$calculated_duration,2),
	         	'engagement_rate' => number_format($os_engagementRate,2),
	         	'event_count' => $os_eventCount,
	         	'conversions' => $os_conversions,
	         	'total_revenue' => $os_totalRevenue
	         ];

	         $response_data['current']['organic_search'] = [
	         	'channel_group' => 'Organic Search',
	         	'active_users' => $osearch_activeUsers,
	         	'sessions' => $osearch_sessions,
	         	'engaged_sessions' => $osearch_engaged_sessions,
	         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($osearch_average_engagement_time_per_session),
	         	'engaged_sessions_per_user' => ($osearch_activeUsers > 0) ? number_format($osearch_engaged_sessions/$osearch_activeUsers,2) : number_format($osearch_engaged_sessions,2),
	         	'events_per_session' => number_format($osearch_eventsPerSession/$calculated_duration,2),
	         	'engagement_rate' => number_format($osearch_engagementRate,2),
	         	'event_count' => $osearch_eventCount,
	         	'conversions' => $osearch_conversions,
	         	'total_revenue' => $osearch_totalRevenue
	         ];

	         $response_data['current']['paid_social'] = [
	         	'channel_group' => 'Paid Social',
	         	'active_users' => $paidSocial_activeUsers,
	         	'sessions' => $paidSocial_sessions,
	         	'engaged_sessions' => $paidSocial_engaged_sessions,
	         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($paidSocial_average_engagement_time_per_session),
	         	'engaged_sessions_per_user' => ($paidSocial_activeUsers > 0) ? number_format($paidSocial_engaged_sessions/$paidSocial_activeUsers,2) : number_format($paidSocial_engaged_sessions,2),
	         	'events_per_session' => number_format($paidSocial_eventsPerSession/$calculated_duration,2),
	         	'engagement_rate' => number_format($paidSocial_engagementRate,2),
	         	'event_count' => $paidSocial_eventCount,
	         	'conversions' => $paidSocial_conversions,
	         	'total_revenue' => $paidSocial_totalRevenue
	         ];

	         $response_data['current']['paid_search'] = [
	         	'channel_group' => 'Paid Search',
	         	'active_users' => $paidSearch_activeUsers,
	         	'sessions' => $paidSearch_sessions,
	         	'engaged_sessions' => $paidSearch_engaged_sessions,
	         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($paidSearch_average_engagement_time_per_session),
	         	'engaged_sessions_per_user' => ($paidSearch_activeUsers > 0) ? number_format($paidSearch_engaged_sessions/$paidSearch_activeUsers,2) : number_format($paidSearch_engaged_sessions,2),
	         	'events_per_session' => number_format($paidSearch_eventsPerSession/$calculated_duration,2),
	         	'engagement_rate' => number_format($paidSearch_engagementRate,2),
	         	'event_count' => $paidSearch_eventCount,
	         	'conversions' => $paidSearch_conversions,
	         	'total_revenue' => $paidSearch_totalRevenue
	         ];

	         $response_data['current']['direct'] = [
	         	'channel_group' => 'Direct',
	         	'active_users' => $direct_activeUsers,
	         	'sessions' => $direct_sessions,
	         	'engaged_sessions' => $direct_engaged_sessions,
	         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($direct_average_engagement_time_per_session),
	         	'engaged_sessions_per_user' => ($direct_activeUsers > 0) ? number_format($direct_engaged_sessions/$direct_activeUsers,2) : number_format($direct_engaged_sessions,2),
	         	'events_per_session' => number_format($direct_eventsPerSession/$calculated_duration,2),
	         	'engagement_rate' => number_format($direct_engagementRate,2),
	         	'event_count' => $direct_eventCount,
	         	'conversions' => $direct_conversions,
	         	'total_revenue' => $direct_totalRevenue
	         ];

	         $prev_os_activeUsers = $prev_os_sessions = $prev_os_engaged_sessions = $prev_os_userEngagementDuration = $prev_os_eventsPerSession = $prev_os_engagementRate = $prev_os_eventCount = $prev_os_conversions = $prev_os_totalRevenue = 0;

	         $prev_osearch_activeUsers = $prev_osearch_sessions = $prev_osearch_engaged_sessions = $prev_osearch_userEngagementDuration = $prev_osearch_eventsPerSession = $prev_osearch_engagementRate = $prev_osearch_eventCount = $prev_osearch_conversions = $prev_osearch_totalRevenue = 0;

	         $prev_paidSocial_activeUsers = $prev_paidSocial_sessions = $prev_paidSocial_engaged_sessions = $prev_paidSocial_userEngagementDuration = $prev_paidSocial_eventsPerSession = $prev_paidSocial_engagementRate = $prev_paidSocial_eventCount = $prev_paidSocial_conversions = $prev_paidSocial_totalRevenue = 0;

	         $prev_paidSearch_activeUsers = $prev_paidSearch_sessions = $prev_paidSearch_engaged_sessions = $prev_paidSearch_userEngagementDuration = $prev_paidSearch_eventsPerSession = $prev_paidSearch_engagementRate = $prev_paidSearch_eventCount = $prev_paidSearch_conversions = $prev_paidSearch_totalRevenue = 0;

	         $prev_direct_activeUsers = $prev_direct_sessions = $prev_direct_engaged_sessions = $prev_direct_userEngagementDuration = $prev_direct_eventsPerSession = $prev_direct_engagementRate = $prev_direct_eventCount = $prev_direct_conversions = $prev_direct_totalRevenue = 0;

	         if($comparison == 1){
	         	/*previous duration data*/
	         	$prev_display_range = date("M d, Y",strtotime($previous_start_date)) .' - '.date("M d' Y",strtotime($previous_end_date));


	         	for($j = strtotime($previous_start_date); $j <= strtotime($previous_end_date); $j = $j+86400){
	         		$prev_dateData = date('Y-m-d',$j);

	         		/*organic social*/
	         		$prev_os_activeUsers += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->active_users : 0;
	         		$prev_os_sessions += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->sessions : 0;
	         		$prev_os_engaged_sessions += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->engaged_sessions : 0;
	         		$prev_os_userEngagementDuration += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->user_engagement_duration : 0;
	         		$prev_os_eventsPerSession += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->events_per_session : 0;
	         		$prev_os_engagementRate += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->engagement_rate : 0;
	         		$prev_os_eventCount += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->event_count : 0;
	         		$prev_os_conversions += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->conversions : 0;
	         		$prev_os_totalRevenue += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->total_revenue : 0;
	         		/*organic search*/
	         		$prev_osearch_activeUsers += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->active_users : 0;
	         		$prev_osearch_sessions += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->sessions : 0;
	         		$prev_osearch_engaged_sessions += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->engaged_sessions : 0;
	         		$prev_osearch_userEngagementDuration += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->user_engagement_duration : 0;
	         		$prev_osearch_eventsPerSession += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->events_per_session : 0;
	         		$prev_osearch_engagementRate += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->engagement_rate : 0;
	         		$prev_osearch_eventCount += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->event_count : 0;
	         		$prev_osearch_conversions += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->conversions : 0;
	         		$prev_osearch_totalRevenue += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->total_revenue : 0;
	         		/*paid social*/
	         		$prev_paidSocial_activeUsers += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->active_users : 0;
	         		$prev_paidSocial_sessions += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->sessions : 0;
	         		$prev_paidSocial_engaged_sessions += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->engaged_sessions : 0;
	         		$prev_paidSocial_userEngagementDuration += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->user_engagement_duration : 0;
	         		$prev_paidSocial_eventsPerSession += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->events_per_session : 0;
	         		$prev_paidSocial_engagementRate += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->engagement_rate : 0;
	         		$prev_paidSocial_eventCount += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->event_count : 0;
	         		$prev_paidSocial_conversions += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->conversions : 0;
	         		$prev_paidSocial_totalRevenue += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->total_revenue : 0;

	         		/*paid social*/
	         		$prev_paidSearch_activeUsers += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->active_users : 0;
	         		$prev_paidSearch_sessions += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->sessions : 0;
	         		$prev_paidSearch_engaged_sessions += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->engaged_sessions : 0;
	         		$prev_paidSearch_userEngagementDuration += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->user_engagement_duration : 0;
	         		$prev_paidSearch_eventsPerSession += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->events_per_session : 0;
	         		$prev_paidSearch_engagementRate += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->engagement_rate : 0;
	         		$prev_paidSearch_eventCount += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->event_count : 0;
	         		$prev_paidSearch_conversions += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->conversions : 0;
	         		$prev_paidSearch_totalRevenue += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->total_revenue : 0;
	         		/*direct*/
	         		$prev_direct_activeUsers += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->active_users : 0;
	         		$prev_direct_sessions += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->sessions : 0;
	         		$prev_direct_engaged_sessions += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->engaged_sessions : 0;
	         		$prev_direct_userEngagementDuration += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->user_engagement_duration : 0;
	         		$prev_direct_eventsPerSession += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->events_per_session : 0;
	         		$prev_direct_engagementRate += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->engagement_rate : 0;
	         		$prev_direct_eventCount += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->event_count : 0;
	         		$prev_direct_conversions += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->conversions : 0;
	         		$prev_direct_totalRevenue += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->total_revenue : 0;	           
		         } //end-of-for

		         $prev_os_average_engagement_time_per_session = ($prev_os_sessions > 0) ? $prev_os_userEngagementDuration/$prev_os_sessions:$prev_os_userEngagementDuration;
		         $prev_osearch_average_engagement_time_per_session = ($prev_osearch_sessions > 0) ? $prev_osearch_userEngagementDuration/$prev_osearch_sessions: $prev_osearch_userEngagementDuration;
		         $prev_paidSocial_average_engagement_time_per_session = ($prev_paidSocial_sessions > 0) ? $prev_paidSocial_userEngagementDuration/$prev_paidSocial_sessions : $prev_paidSocial_userEngagementDuration;
		         $prev_paidSearch_average_engagement_time_per_session = ($prev_paidSearch_sessions > 0) ? $prev_paidSearch_userEngagementDuration/$prev_paidSearch_sessions : $prev_paidSearch_userEngagementDuration;
		         $prev_direct_average_engagement_time_per_session = ($prev_direct_sessions > 0) ? $prev_direct_userEngagementDuration/$prev_direct_sessions : $prev_direct_userEngagementDuration;

		         $response_data['previous']['organic_social'] = [
		         	'channel_group' => 'Organic Social',
		         	'active_users' => $prev_os_activeUsers,
		         	'sessions' => $prev_os_sessions,
		         	'engaged_sessions' => $prev_os_engaged_sessions,
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($prev_os_average_engagement_time_per_session),
		         	'engaged_sessions_per_user' => ($prev_os_activeUsers > 0) ? number_format($prev_os_engaged_sessions/$prev_os_activeUsers,2) : number_format($prev_os_engaged_sessions,2),
		         	'events_per_session' => number_format($prev_os_eventsPerSession/$previous_calculated_duration,2),
		         	'engagement_rate' => number_format($prev_os_engagementRate,2),
		         	'event_count' => $prev_os_eventCount,
		         	'conversions' => $prev_os_conversions,
		         	'total_revenue' => $prev_os_totalRevenue
		         ];

		         $response_data['previous']['organic_search'] = [
		         	'channel_group' => 'Previous Organic Search',
		         	'active_users' => $prev_osearch_activeUsers,
		         	'sessions' => $prev_osearch_sessions,
		         	'engaged_sessions' => $prev_osearch_engaged_sessions,
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($prev_osearch_average_engagement_time_per_session),
		         	'engaged_sessions_per_user' => ($prev_osearch_activeUsers > 0) ? number_format($prev_osearch_engaged_sessions/$prev_osearch_activeUsers,2) : number_format($prev_osearch_engaged_sessions,2),
		         	'events_per_session' => number_format($prev_osearch_eventsPerSession/$previous_calculated_duration,2),
		         	'engagement_rate' => number_format($prev_osearch_engagementRate,2),
		         	'event_count' => $prev_osearch_eventCount,
		         	'conversions' => $prev_osearch_conversions,
		         	'total_revenue' => $prev_osearch_totalRevenue
		         ];

		         $response_data['previous']['paid_social'] = [
		         	'channel_group' => 'Previous Paid Social',
		         	'active_users' => $prev_paidSocial_activeUsers,
		         	'sessions' => $prev_paidSocial_sessions,
		         	'engaged_sessions' => $prev_paidSocial_engaged_sessions,
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($prev_paidSocial_average_engagement_time_per_session),
		         	'engaged_sessions_per_user' => ($prev_paidSocial_activeUsers > 0) ? number_format($prev_paidSocial_engaged_sessions/$prev_paidSocial_activeUsers,2) : number_format($prev_paidSocial_engaged_sessions,2),
		         	'events_per_session' => number_format($prev_paidSocial_eventsPerSession/$previous_calculated_duration,2),
		         	'engagement_rate' => number_format($prev_paidSocial_engagementRate,2),
		         	'event_count' => $prev_paidSocial_eventCount,
		         	'conversions' => $prev_paidSocial_conversions,
		         	'total_revenue' => $prev_paidSocial_totalRevenue
		         ];

		         $response_data['previous']['paid_search'] = [
		         	'channel_group' => 'Previous Paid Search',
		         	'active_users' => $prev_paidSearch_activeUsers,
		         	'sessions' => $prev_paidSearch_sessions,
		         	'engaged_sessions' => $prev_paidSearch_engaged_sessions,
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($prev_paidSearch_average_engagement_time_per_session),
		         	'engaged_sessions_per_user' => ($prev_paidSearch_activeUsers > 0) ? number_format($prev_paidSearch_engaged_sessions/$prev_paidSearch_activeUsers,2) : number_format($prev_paidSearch_engaged_sessions,2),
		         	'events_per_session' => number_format($prev_paidSearch_eventsPerSession/$previous_calculated_duration,2),
		         	'engagement_rate' => number_format($prev_paidSearch_engagementRate,2),
		         	'event_count' => $prev_paidSearch_eventCount,
		         	'conversions' => $prev_paidSearch_conversions,
		         	'total_revenue' => $prev_paidSearch_totalRevenue
		         ];

		         $response_data['previous']['direct'] = [
		         	'channel_group' => 'Previous Direct',
		         	'active_users' => $prev_direct_activeUsers,
		         	'sessions' => $prev_direct_sessions,
		         	'engaged_sessions' => $prev_direct_engaged_sessions,
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($prev_direct_average_engagement_time_per_session),
		         	'engaged_sessions_per_user' => ($prev_direct_activeUsers > 0 ) ? number_format($prev_direct_engaged_sessions/$prev_direct_activeUsers,2) : number_format($prev_direct_engaged_sessions,2),
		         	'events_per_session' => number_format($prev_direct_eventsPerSession/$previous_calculated_duration,2),
		         	'engagement_rate' => number_format($prev_direct_engagementRate,2),
		         	'event_count' => $prev_direct_eventCount,
		         	'conversions' => $prev_direct_conversions,
		         	'total_revenue' => $prev_direct_totalRevenue
		         ];


		         $response_data['percentage']['organic_social'] = [
		         	'active_users' => GoogleAnalyticAccount::calculate_percentage($os_activeUsers,$prev_os_activeUsers),
		         	'sessions' => GoogleAnalyticAccount::calculate_percentage($os_sessions,$prev_os_sessions),
		         	'engaged_sessions' => GoogleAnalyticAccount::calculate_percentage($os_engaged_sessions,$prev_os_engaged_sessions),
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_percentage($os_average_engagement_time_per_session,$prev_os_average_engagement_time_per_session),
		         	'engagement_rate' => GoogleAnalyticAccount::calculate_percentage($os_engagementRate,$prev_os_engagementRate),
		         	'event_count' => GoogleAnalyticAccount::calculate_percentage($os_eventCount,$prev_os_eventCount),
		         	'conversions' => GoogleAnalyticAccount::calculate_percentage($os_conversions,$prev_os_conversions),
		         	'total_revenue' => GoogleAnalyticAccount::calculate_percentage($os_totalRevenue,$prev_os_totalRevenue)
		         ];

		         $response_data['percentage']['organic_search'] = [
		         	'active_users' => GoogleAnalyticAccount::calculate_percentage($osearch_activeUsers,$prev_osearch_activeUsers),
		         	'sessions' => GoogleAnalyticAccount::calculate_percentage($osearch_sessions,$prev_osearch_sessions),
		         	'engaged_sessions' => GoogleAnalyticAccount::calculate_percentage($osearch_engaged_sessions,$prev_osearch_engaged_sessions),
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_percentage($osearch_average_engagement_time_per_session,$prev_osearch_average_engagement_time_per_session),
		         	'engagement_rate' => GoogleAnalyticAccount::calculate_percentage($osearch_engagementRate,$prev_osearch_engagementRate),
		         	'event_count' => GoogleAnalyticAccount::calculate_percentage($osearch_eventCount,$prev_osearch_eventCount),
		         	'conversions' => GoogleAnalyticAccount::calculate_percentage($osearch_conversions,$prev_osearch_conversions),
		         	'total_revenue' => GoogleAnalyticAccount::calculate_percentage($osearch_totalRevenue,$prev_osearch_totalRevenue)
		         ];

		         $response_data['percentage']['paid_social'] = [
		         	'active_users' => GoogleAnalyticAccount::calculate_percentage($paidSocial_activeUsers,$prev_paidSocial_activeUsers),
		         	'sessions' => GoogleAnalyticAccount::calculate_percentage($paidSocial_sessions,$prev_paidSocial_sessions),
		         	'engaged_sessions' => GoogleAnalyticAccount::calculate_percentage($paidSocial_engaged_sessions,$prev_paidSocial_engaged_sessions),
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_percentage($paidSocial_average_engagement_time_per_session,$prev_paidSocial_average_engagement_time_per_session),
		         	'engagement_rate' => GoogleAnalyticAccount::calculate_percentage($paidSocial_engagementRate,$prev_paidSocial_engagementRate),
		         	'event_count' => GoogleAnalyticAccount::calculate_percentage($paidSocial_eventCount,$prev_paidSocial_eventCount),
		         	'conversions' => GoogleAnalyticAccount::calculate_percentage($paidSocial_conversions,$prev_paidSocial_conversions),
		         	'total_revenue' => GoogleAnalyticAccount::calculate_percentage($paidSocial_totalRevenue,$prev_paidSocial_totalRevenue)
		         ];

		         $response_data['percentage']['paid_search'] = [
		         	'active_users' => GoogleAnalyticAccount::calculate_percentage($paidSearch_activeUsers,$prev_paidSearch_activeUsers),
		         	'sessions' => GoogleAnalyticAccount::calculate_percentage($paidSearch_sessions,$prev_paidSearch_sessions),
		         	'engaged_sessions' => GoogleAnalyticAccount::calculate_percentage($paidSearch_engaged_sessions,$prev_paidSearch_engaged_sessions),
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_percentage($paidSearch_average_engagement_time_per_session,$prev_paidSearch_average_engagement_time_per_session),
		         	'engagement_rate' => GoogleAnalyticAccount::calculate_percentage($paidSearch_engagementRate,$prev_paidSearch_engagementRate),
		         	'event_count' => GoogleAnalyticAccount::calculate_percentage($paidSearch_eventCount,$prev_paidSearch_eventCount),
		         	'conversions' => GoogleAnalyticAccount::calculate_percentage($paidSearch_conversions,$prev_paidSearch_conversions),
		         	'total_revenue' => GoogleAnalyticAccount::calculate_percentage($paidSearch_totalRevenue,$prev_paidSearch_totalRevenue)
		         ];

		         $response_data['percentage']['direct'] = [
		         	'active_users' => GoogleAnalyticAccount::calculate_percentage($direct_activeUsers,$prev_direct_activeUsers),
		         	'sessions' => GoogleAnalyticAccount::calculate_percentage($direct_sessions,$prev_direct_sessions),
		         	'engaged_sessions' => GoogleAnalyticAccount::calculate_percentage($direct_engaged_sessions,$prev_direct_engaged_sessions),
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_percentage($direct_average_engagement_time_per_session,$prev_direct_average_engagement_time_per_session),
		         	'engagement_rate' => GoogleAnalyticAccount::calculate_percentage($direct_engagementRate,$prev_direct_engagementRate),
		         	'event_count' => GoogleAnalyticAccount::calculate_percentage($direct_eventCount,$prev_direct_eventCount),
		         	'conversions' => GoogleAnalyticAccount::calculate_percentage($direct_conversions,$prev_direct_conversions),
		         	'total_revenue' => GoogleAnalyticAccount::calculate_percentage($direct_totalRevenue,$prev_direct_totalRevenue)
		         ];


		         $prev_total_active_users = $prev_os_activeUsers + $prev_osearch_activeUsers + $prev_paidSocial_activeUsers + $prev_paidSearch_activeUsers + $prev_direct_activeUsers;
		         $prev_total_sessions = $prev_os_sessions + $prev_osearch_sessions + $prev_paidSocial_sessions + $prev_paidSearch_sessions + $prev_direct_sessions;
		         $prev_total_engaged_sessions = $prev_os_engaged_sessions + $prev_osearch_engaged_sessions + $prev_paidSocial_engaged_sessions + $prev_paidSearch_engaged_sessions + $prev_direct_engaged_sessions;
		         $prev_total_eventsPerSession = ($prev_os_eventsPerSession + $prev_osearch_eventsPerSession + $prev_paidSocial_eventsPerSession + $prev_paidSearch_eventsPerSession + $prev_direct_eventsPerSession)/$previous_calculated_duration;
		         $prev_total_engagementRate = ($prev_os_engagementRate + $prev_osearch_engagementRate + $prev_paidSocial_engagementRate + $prev_paidSearch_engagementRate + $prev_direct_engagementRate)/5;
		         $prev_total_eventCount = $prev_os_eventCount + $prev_osearch_eventCount + $prev_paidSocial_eventCount + $prev_paidSearch_eventCount + $prev_direct_eventCount;
		         $prev_total_conversions = $prev_os_conversions + $prev_osearch_conversions + $prev_paidSocial_conversions + $prev_paidSearch_conversions + $prev_direct_conversions;
		         $prev_total_totalRevenue = $prev_os_totalRevenue + $prev_osearch_totalRevenue + $prev_paidSocial_totalRevenue + $prev_paidSearch_totalRevenue + $prev_direct_totalRevenue;

		         $prev_average_engagement_time_per_session = $prev_os_average_engagement_time_per_session + $prev_osearch_average_engagement_time_per_session + $prev_paidSocial_average_engagement_time_per_session + $prev_paidSearch_average_engagement_time_per_session + $prev_direct_average_engagement_time_per_session;
		         $prev_total_userEngagementDuration = GoogleAnalyticAccount::calculate_time($prev_average_engagement_time_per_session);

		         $prev_total_engaged_sessions_perSession = ($prev_total_active_users > 0) ? $prev_total_engaged_sessions/$prev_total_active_users: $prev_total_engaged_sessions;

		         $response_data['total'] = [
		         	'active_users' => GoogleAnalyticAccount::calculate_percentage($total_active_users,$prev_total_active_users),
		         	'sessions' => GoogleAnalyticAccount::calculate_percentage($total_sessions,$prev_total_sessions),
		         	'engaged_sessions' => GoogleAnalyticAccount::calculate_percentage($total_engaged_sessions,$prev_total_engaged_sessions),
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_percentage($average_engagement_time_per_session,$prev_average_engagement_time_per_session),
		         	'engaged_sessions_perSession' => GoogleAnalyticAccount::calculate_percentage($total_engaged_sessions_perSession,$prev_total_engaged_sessions_perSession),
		         	'eventsPerSession' => GoogleAnalyticAccount::calculate_percentage($total_eventsPerSession,$prev_total_eventsPerSession),
		         	'engagement_rate' => GoogleAnalyticAccount::calculate_percentage($total_engagementRate,$prev_total_engagementRate),
		         	'event_count' => GoogleAnalyticAccount::calculate_percentage($total_eventCount,$prev_total_eventCount),
		         	'conversions' => GoogleAnalyticAccount::calculate_percentage($total_conversions,$prev_total_conversions),
		         	'total_revenue' => GoogleAnalyticAccount::calculate_percentage($total_totalRevenue,$prev_total_totalRevenue)
		         ];


		         return view('vendor.seo_sections.ga4_goals_listing',compact('response_data','comparison','status','display_range','prev_display_range','total_active_users','total_sessions','total_engaged_sessions','total_userEngagementDuration','total_eventsPerSession','total_engagementRate','total_eventCount','total_conversions', 'total_engaged_sessions_perSession','total_totalRevenue','prev_total_active_users','prev_total_sessions','prev_total_engaged_sessions','prev_total_eventsPerSession','prev_total_engagementRate','prev_total_eventCount','prev_total_conversions','prev_total_totalRevenue','prev_total_userEngagementDuration','prev_total_engaged_sessions_perSession'))->render();
		     }

		     return view('vendor.seo_sections.ga4_goals_listing',compact('response_data','comparison','status','display_range','total_active_users','total_sessions','total_engaged_sessions','total_userEngagementDuration','total_eventsPerSession','total_engagementRate','total_eventCount','total_conversions', 'total_engaged_sessions_perSession','total_totalRevenue'))->render();
		 }
		}

		public function ajax_google_analytics_overview(Request $request){
			$response = array();
			$state = ($request->has('key'))?'viewkey':'user';

			$module = $request->module;
			$campaignId = $request->campaignId;
			$start_date = $request->current_start;
			$end_date = $request->current_end;
			$previous_start = $request->previous_start;
			$previous_end = $request->previous_end;
			$comparison = $request->comparison;
			$comparison_selected = $request->comparison_selected;
			$selected_range = $request->selected_label;

			if(Auth::user() <> null){
				$user_id = User::get_parent_user_id(Auth::user()->id);
				$role_id = User::get_user_role(Auth::user()->id);
			}else{
				$getUser = SemrushUserAccount::where('id',$campaignId)->first();
				$user_id = $getUser->user_id;
				$role_id = User::get_user_role($getUser->user_id);
			}	

			$duration = GoogleAnalyticAccount::get_selected_range($selected_range);

			if($role_id != 4 && $state == 'user' && $duration !== 0){
				$ifCheck = ModuleByDateRange::where('request_id',$campaignId)->where('module',$module)->first();
				$array = [
					'user_id'=>$user_id,
					'request_id'=>$campaignId,
					'duration'=>($duration === 0)?$if_check->duration:$duration,
					'module'=>$module,
					'start_date'=>date('Y-m-d', strtotime($start_date)),
					'end_date'=>date('Y-m-d', strtotime($end_date)),
					'compare_start_date'=>date('Y-m-d', strtotime($previous_start)),
					'compare_end_date'=>date('Y-m-d', strtotime($previous_end)),
					'status'=>$comparison,
					'comparison'=>$comparison_selected
				];

				if(empty($ifCheck)){
					ModuleByDateRange::create($array);
				}else{
					ModuleByDateRange::where('id',$ifCheck->id)->update($array);
				}
			}
			$response['status'] = 'success';
			return response()->json($response);
		}


		public function ajax_store_ga4_data(Request $request){
			$week_start_date = date('Y-m-d',strtotime('-7 day'));
			$end_date = date('Y-m-d',strtotime('-1 day'));
			$response = array();

			$user_id = User::get_parent_user_id(Auth::user()->id);
			$get_google_user = GoogleAnalyticsUsers::where('user_id',$user_id)->where('id',$request->email)->first();
			$refresh_token = $get_google_user->google_refresh_token;
			$service = GoogleAnalyticAccount::get_beta_service_client($refresh_token);
			$get_property = GoogleAnalyticAccount::where('id',$request->property)->first();

			$check_week_data = GoogleAnalyticAccount::check_week_data($service,$get_property->property_id,$week_start_date,$end_date);

			if($check_week_data['status'] == 0){
				$response['status'] = 'google-error'; 

				if(!empty($check_week_data['message']['error']['code'])){
					if(isset($check_week_data['message']['error']['message'])){
						$response['message'] =$check_week_data['message']['error']['message'];
					}else{
						$response['message'] = $check_week_data['message']['errors'][0]['message'];
					}
				}else if(isset($check_week_data['message']['error_description'])){
					$response['message'] = $check_week_data['message']['error'].'-'.$check_week_data['message']['error_description'];
				}else{
					$response['message'] = $check_week_data['message'];					
				}
				return response()->json($response);
			}

			$update = SemrushUserAccount::
			where('user_id',$user_id)
			->where('id',$request->campaign_id)
			->update([
				'ga4_email_id'=>$request->email,
				'ga4_account_id'=>$request->account,
				'ga4_property_id'=>$request->property,
				'updated_at' =>now()
			]);

			if(!$update){
				$response['status'] = 'error';
				$response['message'] = 'Error: Try again';
			}else{
				$response['status'] = 'success';
				$response['message'] = 'GA4 Account Connected successfully! Fetching Data it may take some time.';

				$getEmail = GoogleAnalyticsUsers::select('id','email')->where('id',$request->email)->first();
				$getAccount = GoogleAnalyticAccount::where('id',$request->account)->first();
				$getproperty = GoogleAnalyticAccount::where('id',$request->property)->first();
				$response['email'] = $getEmail->email;
				$response['account'] = $getAccount->display_name;
				$response['property'] = $getproperty->display_name;
				$response['project_id'] = $request->campaign_id;
				$response['step'] = 5;			
				$response['status'] = 'success';

			}
			return json_encode($response); 	
		}

		public function ajax_refresh_ga4_list(Request $request){
			$response = array();
			$email_id = $request->email; $campaign_id = $request->campaign_id;
			$campaign_id = 1005;
			if($campaign_id <> null){
				$campaign_data = SemrushUserAccount::where('id',$campaign_id)->first();

				$connected_email_data = GoogleAnalyticsUsers::where('id',$email_id)->where('user_id',$campaign_data->user_id)->where('oauth_provider','ga4')->first();

			//SearchConsoleUsers::updateRefreshNAccessToken($googleuser['email'],$get_agency_details->user_id,$tokenResponse);

				$service_client = GoogleAnalyticAccount::get_admin_service_client($connected_email_data->google_refresh_token);

				$data = GoogleAnalyticAccount::getGoogleAccountsList($service_client,$campaign_id,$connected_email_data->id,$connected_email_data->user_id,'ga4');

				if($data['status'] == 1){
					GoogleAnalyticsUsers::where('id',$connected_email_data->id)->update([
						'updated_at' => now()
					]);

					$response['status'] = 1;
					$response['message'] = 'Last fetched now';
				}
				if($data['status'] == 0){
					$response['status'] = 0;
					$response['message'] = 'Error message: '.$data['message'];
				}
			}else{
				$response['status'] = 2;
				$response['message'] = 'Error: missing campaign id';
			}
			return response()->json($response);	
		}

		public function ajax_ga4_au_chart(Request $request){
			$active_users = array();
			if (!file_exists(env('FILE_PATH')."public/google_analytics_4/".$request->campaign_id)) {
				$active_users = [0];
				$end_new = [0];
			}else{
				$lapse ='-6 day';
				$end_date = date('Y-m-d');
				for($i=1;$i<=6;$i++){
					if($i==1){
						$start_date = date('Y-m-d',strtotime($end_date));
						$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
					}else{
						$start_date = date('Y-m-d',strtotime('-1 day',strtotime($end_date)));
						$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
					}

					$result[] = $this->overview_data_allUsers($end_date,$start_date,$request->campaign_id);
					$end_new[] = date('M d, Y',strtotime($end_date));
				}  
				$active_users = array_reverse($result);
				$end_new = array_reverse($end_new);

				array_unshift($active_users, 0);
				array_unshift($end_new, "");
			}


			$dates['active_users'] = $active_users;
			$dates['labels'] = $end_new;
			return $dates; 
		}

		private function overview_data_allUsers($start_date,$end_date,$campaign_id){
			$active_users = 0;
			if (!file_exists(env('FILE_PATH')."public/google_analytics_4/".$campaign_id)) {
				$active_users = 0;
			}else{
				$url = env('FILE_PATH')."public/google_analytics_4/".$campaign_id.'/graph.json'; 
				$data = file_get_contents($url);
				$final = json_decode($data);

				for($i = strtotime($start_date); $i <= strtotime($end_date); $i = $i+86400){
					$start_date = date('Y-m-d',$i);
					$current_index = array_search($start_date,$final->dates);			

					if($current_index == false){
						$active_users += 0;
					}else{
						$active_users += $final->active_users[$current_index];
					}				
				}
			}

			return $active_users;
		}

		public function ajax_ga4_conversions_chart(Request $request){
			$result = array();
			if (!file_exists(env('FILE_PATH')."public/google_analytics_4/".$request->campaign_id)) {
				$result = [0];
				$end_new = [0];
			}else{
				$url = env('FILE_PATH')."public/google_analytics_4/".$request->campaign_id.'/traffic_acquisition.json'; 
				$data = file_get_contents($url);
				$final = json_decode($data);

				$lapse ='-6 day';
				$end_date = date('Y-m-d');
				for($i=1;$i<=6;$i++){
					if($i==1){
						$start_date = date('Y-m-d',strtotime($end_date));
						$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
					}else{
						$start_date = date('Y-m-d',strtotime('-1 day',strtotime($end_date)));
						$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
					}

					$result[] = isset($final->$end_date->organic_social) ? $final->$end_date->organic_social->conversions : 0;
					$end_new[] = date('M d, Y',strtotime($end_date));
				}  


				$conversions = array_reverse($result);
				$end_new = array_reverse($end_new);

				array_unshift($conversions, 0);
				array_unshift($end_new, "");
			}


			$dates['conversions'] = $conversions;
			$dates['labels'] = $end_new;
			return $dates; 
		}

		public function ajax_alluser_statistics(Request $request){
			$res = array();
			if(!file_exists(env('FILE_PATH')."public/google_analytics_4/".$request->campaign_id)) {
				$res['current_active_users'] = '??';
				$res['previous_active_users'] = '??';
				$res['status'] = 0;			
			} else {
				$url = env('FILE_PATH')."public/google_analytics_4/".$request->campaign_id."/graph.json"; 
				$data = file_get_contents($url);
				$final = json_decode($data);

				$current_active_users = $previous_active_users = 0;

				$project_data = SemrushUserAccount::get_created_date($request->campaign_id);

				$end_date = date('Y-m-d');
				$start_date = date('Y-m-d',strtotime('-30 day'));

				for($i = strtotime($start_date); $i <= strtotime($end_date); $i = $i+86400){
					$current_index = array_search($start_date,$final->dates);		
					if($current_index == false){
						$current_active_users += 0;
					}else{
						$current_active_users += $final->active_users[$current_index];
					}				
				}

				$start_month_date = date('Y-m-d',strtotime('-30 day',strtotime($project_data->domain_register)));
				$end_month_date = date('Y-m-d',strtotime($project_data->domain_register));

				for($j = strtotime($start_month_date); $j <= strtotime($end_month_date); $j = $j+86400){
					$previous_index = array_search($start_month_date,$final->dates);		
					if($previous_index == false){
						$previous_active_users += 0;
					}else{
						$previous_active_users += $final->active_users[$previous_index];
					}				
				}


				if(($current_active_users > 0) && ($previous_active_users > 0)){
					if(($current_active_users + $previous_active_users) == 0){
						$res['total_users'] = number_format(($current_active_users - $previous_active_users),2);
					}else{
						if($previous_active_users > 0){
							$res['total_users'] = number_format((($current_active_users - $previous_active_users) / $previous_active_users) * 100,2);
						}else{
							$res['total_users'] = '100';
						}
					}

				}else if(($current_active_users == 0) && ($previous_active_users > 0)) {
					$res['total_users'] = '-100';
				} else if(($current_active_users > 0) && ($previous_active_users == 0)) {
					$res['total_users'] = '100';
				} else{
					$res['total_users'] = '??'; 
				}

				$res['current_active_users'] = shortNumbers($current_active_users);
				$res['previous_active_users'] = shortNumbers($previous_active_users);
				$res['status'] = 1;
			}
			return response()->json($res);
		}

		public function ajax_conversions_statstics(Request $request){
			$res = array();
			if(!file_exists(env('FILE_PATH')."public/google_analytics_4/".$request->campaign_id)) {
				$res['current_conversions'] = '??';
				$res['previous_conversions'] = '??';
				$res['status'] = 0;			
			} else {
				$url = env('FILE_PATH')."public/google_analytics_4/".$request->campaign_id."/traffic_acquisition.json"; 
				$data = file_get_contents($url);
				$final = json_decode($data);

				$current_conversions = $previous_conversions = 0;
				$organic_social = $organic_search = $paid_social = $paid_search = $direct = 0;
				$previous_organic_social = $previous_organic_search = $previous_paid_social = $previous_paid_search = $previous_direct = 0;

				$project_data = SemrushUserAccount::get_created_date($request->campaign_id);

				$end_date = date('Y-m-d');
				$start_date = date('Y-m-d',strtotime('-30 day'));

				for($i = strtotime($start_date); $i <= strtotime($end_date); $i = $i+86400){
					$current_dates = date('Y-m-d',$i);		
					$organic_social += isset($final->$current_dates->organic_social) ? $final->$current_dates->organic_social->conversions : 0;
					$organic_search += isset($final->$current_dates->organic_search) ? $final->$current_dates->organic_search->conversions : 0;
					$paid_social += isset($final->$current_dates->paid_social) ? $final->$current_dates->paid_social->conversions : 0;
					$paid_search += isset($final->$current_dates->paid_search) ? $final->$current_dates->paid_search->conversions : 0;
					$direct += isset($final->$current_dates->direct) ? $final->$current_dates->direct->conversions : 0;
				}
				$current_conversions = $organic_social + $organic_search + $paid_social + $paid_search + $direct;

				$start_month_date = date('Y-m-d',strtotime('-30 day',strtotime($project_data->domain_register)));
				$end_month_date = date('Y-m-d',strtotime($project_data->domain_register));


				for($j = strtotime($start_month_date); $j <= strtotime($end_month_date); $j = $j+86400){
					$previous_dates = date('Y-m-d',$j);
					$previous_organic_social += isset($final->$previous_dates->organic_social) ? $final->$previous_dates->organic_social->conversions : 0;
					$previous_organic_search += isset($final->$previous_dates->organic_search) ? $final->$previous_dates->organic_search->conversions : 0;
					$previous_paid_social += isset($final->$previous_dates->paid_social) ? $final->$previous_dates->paid_social->conversions : 0;
					$previous_paid_search += isset($final->$previous_dates->paid_search) ? $final->$previous_dates->paid_search->conversions : 0;
					$previous_direct += isset($final->$previous_dates->direct) ? $final->$previous_dates->direct->conversions : 0;	
				}

				$previous_conversions = $previous_organic_social + $previous_organic_search + $previous_paid_social + $previous_paid_search + $previous_direct;


				if(($current_conversions > 0) && ($previous_conversions > 0)){
					if(($current_conversions + $previous_conversions) == 0){
						$res['total_conversions'] = number_format(($current_conversions - $previous_conversions),2);
					}else{
						if($previous_conversions > 0){
							$res['total_conversions'] = number_format((($current_conversions - $previous_conversions) / $previous_conversions) * 100,2);
						}else{
							$res['total_conversions'] = '100';
						}
					}

				}else if(($current_conversions == 0) && ($previous_conversions > 0)) {
					$res['total_conversions'] = '-100';
				} else if(($current_conversions > 0) && ($previous_conversions == 0)) {
					$res['total_conversions'] = '100';
				} else{
					$res['total_conversions'] = '??'; 
				}

				$res['current_conversions'] = shortNumbers($current_conversions);
				$res['previous_conversions'] = shortNumbers($previous_conversions);
				$res['status'] = 1;
			}
			return response()->json($res);
		}

		public function ajax_goals_listing_traffic_acquisition_pdf (Request $request){
			$response_data = array();
			$campaign_id = $request['campaign_id'];

			$history = ModuleByDateRange::getModuleDateRange($campaign_id,'ga4');

			if (!file_exists(env('FILE_PATH')."public/google_analytics_4/".$campaign_id)) {
				$status = 0;
			} else {
				$status = 1;
				$url = env('FILE_PATH')."public/google_analytics_4/".$campaign_id.'/traffic_acquisition.json'; 
				$data = file_get_contents($url);
				$final = json_decode($data);

				$comparison = 0;  $comparison_period = 'previous_period';$duration = 3;

				if($request->selected_label == 0 && $request->selected_label !== null){
					$comparison = $request->comparison;  $comparison_period = $request->comparison_selected;
					$start_date = date('Y-m-d', strtotime($request->current_start));
					$end_date = date('Y-m-d', strtotime($request->current_end));
					$calculated_duration = ModuleByDateRange::calculate_days($start_date,$end_date);
				}else{
					$end_date = date('Y-m-d',strtotime('-1 day'));
					$start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));

					$history = ModuleByDateRange::getModuleDateRange($campaign_id,'ga4');

					if(!empty($history)){
						$comparison = $history->status;
						$comparison_period = $history->comparison;
						$duration = $history->duration;
						if($duration == 1){
							$start_date = date('Y-m-d', strtotime("-1 month", strtotime($end_date)));
						}elseif($duration == 3){
							$start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
						}elseif($duration == 6){
							$start_date = date('Y-m-d', strtotime("-6 month", strtotime($end_date)));
						}elseif($duration == 9){
							$start_date = date('Y-m-d', strtotime("-9 month", strtotime($end_date)));
						}elseif($duration == 12){
							$start_date = date('Y-m-d', strtotime("-1 year", strtotime($end_date)));
						}elseif($duration == 24){
							$start_date = date('Y-m-d', strtotime("-2 year", strtotime($end_date)));
						}
					}
					$calculated_duration = ModuleByDateRange::calculate_days($start_date,$end_date);
				}


				$os_activeUsers = $os_sessions = $os_engaged_sessions = $os_userEngagementDuration = $os_eventsPerSession = $os_engagementRate = $os_eventCount = $os_conversions = $os_totalRevenue = 0;
				$osearch_activeUsers = $osearch_sessions = $osearch_engaged_sessions = $osearch_userEngagementDuration = $osearch_eventsPerSession = $osearch_engagementRate = $osearch_eventCount = $osearch_conversions = $osearch_totalRevenue = 0;
				$paidSocial_activeUsers = $paidSocial_sessions = $paidSocial_engaged_sessions = $paidSocial_userEngagementDuration = $paidSocial_eventsPerSession = $paidSocial_engagementRate = $paidSocial_eventCount = $paidSocial_conversions = $paidSocial_totalRevenue = 0;
				$paidSearch_activeUsers = $paidSearch_sessions = $paidSearch_engaged_sessions = $paidSearch_userEngagementDuration = $paidSearch_eventsPerSession = $paidSearch_engagementRate = $paidSearch_eventCount = $paidSearch_conversions = $paidSearch_totalRevenue = 0;
				$direct_activeUsers = $direct_sessions = $direct_engaged_sessions = $direct_userEngagementDuration = $direct_eventsPerSession = $direct_engagementRate = $direct_eventCount = $direct_conversions = $direct_totalRevenue = 0;

				if($comparison_period === 'previous_period'){
					$previous_period_dates = SearchConsoleUsers::calculate_previous_period($start_date,$calculated_duration);
				}else{
					$previous_period_dates = SearchConsoleUsers::calculate_previous_year($start_date,$end_date);	
				}

				$previous_start_date = $previous_period_dates['previous_start_date'];
				$previous_end_date = $previous_period_dates['previous_end_date'];
				$previous_calculated_duration = ModuleByDateRange::calculate_days($previous_start_date,$previous_end_date);

				/*current duration data*/
				$display_range = date("M d, Y",strtotime($start_date)) .' - '.date("M d' Y",strtotime($end_date));

				for($i = strtotime($start_date); $i <= strtotime($end_date); $i = $i+86400){
					$dateData = date('Y-m-d',$i);
					/*organic social*/
					$os_activeUsers += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->active_users : 0;
					$os_sessions += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->sessions : 0;
					$os_engaged_sessions += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->engaged_sessions : 0;
					$os_userEngagementDuration += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->user_engagement_duration : 0;
					$os_eventsPerSession += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->events_per_session : 0;
					$os_engagementRate += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->engagement_rate : 0;
					$os_eventCount += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->event_count : 0;
					$os_conversions += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->conversions : 0;
					$os_totalRevenue += isset($final->$dateData->organic_social) ? $final->$dateData->organic_social->total_revenue : 0;
					/*organic search*/
					$osearch_activeUsers += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->active_users : 0;
					$osearch_sessions += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->sessions : 0;
					$osearch_engaged_sessions += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->engaged_sessions : 0;
					$osearch_userEngagementDuration += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->user_engagement_duration : 0;
					$osearch_eventsPerSession += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->events_per_session : 0;
					$osearch_engagementRate += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->engagement_rate : 0;
					$osearch_eventCount += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->event_count : 0;
					$osearch_conversions += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->conversions : 0;
					$osearch_totalRevenue += isset($final->$dateData->organic_search) ? $final->$dateData->organic_search->total_revenue : 0;
					/*paid social*/
					$paidSocial_activeUsers += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->active_users : 0;
					$paidSocial_sessions += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->sessions : 0;
					$paidSocial_engaged_sessions += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->engaged_sessions : 0;
					$paidSocial_userEngagementDuration += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->user_engagement_duration : 0;
					$paidSocial_eventsPerSession += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->events_per_session : 0;
					$paidSocial_engagementRate += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->engagement_rate : 0;
					$paidSocial_eventCount += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->event_count : 0;
					$paidSocial_conversions += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->conversions : 0;
					$paidSocial_totalRevenue += isset($final->$dateData->paid_social) ? $final->$dateData->paid_social->total_revenue : 0;
					/*paid social*/
					$paidSearch_activeUsers += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->active_users : 0;
					$paidSearch_sessions += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->sessions : 0;
					$paidSearch_engaged_sessions += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->engaged_sessions : 0;
					$paidSearch_userEngagementDuration += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->user_engagement_duration : 0;
					$paidSearch_eventsPerSession += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->events_per_session : 0;
					$paidSearch_engagementRate += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->engagement_rate : 0;
					$paidSearch_eventCount += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->event_count : 0;
					$paidSearch_conversions += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->conversions : 0;
					$paidSearch_totalRevenue += isset($final->$dateData->paid_search) ? $final->$dateData->paid_search->total_revenue : 0;
					/*direct*/
					$direct_activeUsers += isset($final->$dateData->direct) ? $final->$dateData->direct->active_users : 0;
					$direct_sessions += isset($final->$dateData->direct) ? $final->$dateData->direct->sessions : 0;
					$direct_engaged_sessions += isset($final->$dateData->direct) ? $final->$dateData->direct->engaged_sessions : 0;
					$direct_userEngagementDuration += isset($final->$dateData->direct) ? $final->$dateData->direct->user_engagement_duration : 0;
					$direct_eventsPerSession += isset($final->$dateData->direct) ? $final->$dateData->direct->events_per_session : 0;
					$direct_engagementRate += isset($final->$dateData->direct) ? $final->$dateData->direct->engagement_rate : 0;
					$direct_eventCount += isset($final->$dateData->direct) ? $final->$dateData->direct->event_count : 0;
					$direct_conversions += isset($final->$dateData->direct) ? $final->$dateData->direct->conversions : 0;
					$direct_totalRevenue += isset($final->$dateData->direct) ? $final->$dateData->direct->total_revenue : 0;	           
	         } //end-of-for

	         $os_average_engagement_time_per_session = ($os_sessions > 0)? $os_userEngagementDuration/$os_sessions :$os_userEngagementDuration;
	         $osearch_average_engagement_time_per_session = ($osearch_sessions > 0)? $osearch_userEngagementDuration/$osearch_sessions: $osearch_userEngagementDuration;
	         $paidSocial_average_engagement_time_per_session = ($paidSocial_sessions > 0)?($paidSocial_userEngagementDuration/$paidSocial_sessions): $paidSocial_userEngagementDuration;
	         $paidSearch_average_engagement_time_per_session = ($paidSearch_sessions > 0)?$paidSearch_userEngagementDuration/$paidSearch_sessions:$paidSearch_userEngagementDuration;
	         $direct_average_engagement_time_per_session =($direct_sessions > 0)? $direct_userEngagementDuration/$direct_sessions :$direct_userEngagementDuration;

	         $total_active_users = $os_activeUsers + $osearch_activeUsers + $paidSocial_activeUsers + $paidSearch_activeUsers + $direct_activeUsers;
	         $total_sessions = $os_sessions + $osearch_sessions + $paidSocial_sessions + $paidSearch_sessions + $direct_sessions;
	         $total_engaged_sessions = $os_engaged_sessions + $osearch_engaged_sessions + $paidSocial_engaged_sessions + $paidSearch_engaged_sessions + $direct_engaged_sessions;
	         $total_eventsPerSession = ($os_eventsPerSession + $osearch_eventsPerSession + $paidSocial_eventsPerSession + $paidSearch_eventsPerSession + $direct_eventsPerSession)/$calculated_duration;
	         $total_engagementRate = ($os_engagementRate + $osearch_engagementRate + $paidSocial_engagementRate + $paidSearch_engagementRate + $direct_engagementRate)/5;
	         $total_eventCount = $os_eventCount + $osearch_eventCount + $paidSocial_eventCount + $paidSearch_eventCount + $direct_eventCount;
	         $total_conversions = $os_conversions + $osearch_conversions + $paidSocial_conversions + $paidSearch_conversions + $direct_conversions;
	         $total_totalRevenue = $os_totalRevenue + $osearch_totalRevenue + $paidSocial_totalRevenue + $paidSearch_totalRevenue + $direct_totalRevenue;

	         $average_engagement_time_per_session = $os_average_engagement_time_per_session + $osearch_average_engagement_time_per_session + $paidSocial_average_engagement_time_per_session + $paidSearch_average_engagement_time_per_session + $direct_average_engagement_time_per_session;

	         $total_userEngagementDuration = GoogleAnalyticAccount::calculate_time($average_engagement_time_per_session);

	         $total_engaged_sessions_perSession = ($total_active_users > 0) ? $total_engaged_sessions/$total_active_users: $total_engaged_sessions;

	         $response_data['current']['organic_social'] = [
	         	'channel_group' => 'Organic Social',
	         	'active_users' => $os_activeUsers,
	         	'sessions' => $os_sessions,
	         	'engaged_sessions' => $os_engaged_sessions,
	         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($os_average_engagement_time_per_session),
	         	'engaged_sessions_per_user' => ($os_activeUsers > 0) ? number_format($os_engaged_sessions/$os_activeUsers,2) : number_format($os_engaged_sessions,2),
	         	'events_per_session' => number_format($os_eventsPerSession/$calculated_duration,2),
	         	'engagement_rate' => number_format($os_engagementRate,2),
	         	'event_count' => $os_eventCount,
	         	'conversions' => $os_conversions,
	         	'total_revenue' => $os_totalRevenue
	         ];

	         $response_data['current']['organic_search'] = [
	         	'channel_group' => 'Organic Search',
	         	'active_users' => $osearch_activeUsers,
	         	'sessions' => $osearch_sessions,
	         	'engaged_sessions' => $osearch_engaged_sessions,
	         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($osearch_average_engagement_time_per_session),
	         	'engaged_sessions_per_user' => ($osearch_activeUsers > 0) ? number_format($osearch_engaged_sessions/$osearch_activeUsers,2) : number_format($osearch_engaged_sessions,2),
	         	'events_per_session' => number_format($osearch_eventsPerSession/$calculated_duration,2),
	         	'engagement_rate' => number_format($osearch_engagementRate,2),
	         	'event_count' => $osearch_eventCount,
	         	'conversions' => $osearch_conversions,
	         	'total_revenue' => $osearch_totalRevenue
	         ];

	         $response_data['current']['paid_social'] = [
	         	'channel_group' => 'Paid Social',
	         	'active_users' => $paidSocial_activeUsers,
	         	'sessions' => $paidSocial_sessions,
	         	'engaged_sessions' => $paidSocial_engaged_sessions,
	         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($paidSocial_average_engagement_time_per_session),
	         	'engaged_sessions_per_user' => ($paidSocial_activeUsers > 0) ? number_format($paidSocial_engaged_sessions/$paidSocial_activeUsers,2) : number_format($paidSocial_engaged_sessions,2),
	         	'events_per_session' => number_format($paidSocial_eventsPerSession/$calculated_duration,2),
	         	'engagement_rate' => number_format($paidSocial_engagementRate,2),
	         	'event_count' => $paidSocial_eventCount,
	         	'conversions' => $paidSocial_conversions,
	         	'total_revenue' => $paidSocial_totalRevenue
	         ];

	         $response_data['current']['paid_search'] = [
	         	'channel_group' => 'Paid Search',
	         	'active_users' => $paidSearch_activeUsers,
	         	'sessions' => $paidSearch_sessions,
	         	'engaged_sessions' => $paidSearch_engaged_sessions,
	         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($paidSearch_average_engagement_time_per_session),
	         	'engaged_sessions_per_user' => ($paidSearch_activeUsers > 0) ? number_format($paidSearch_engaged_sessions/$paidSearch_activeUsers,2) : number_format($paidSearch_engaged_sessions,2),
	         	'events_per_session' => number_format($paidSearch_eventsPerSession/$calculated_duration,2),
	         	'engagement_rate' => number_format($paidSearch_engagementRate,2),
	         	'event_count' => $paidSearch_eventCount,
	         	'conversions' => $paidSearch_conversions,
	         	'total_revenue' => $paidSearch_totalRevenue
	         ];

	         $response_data['current']['direct'] = [
	         	'channel_group' => 'Direct',
	         	'active_users' => $direct_activeUsers,
	         	'sessions' => $direct_sessions,
	         	'engaged_sessions' => $direct_engaged_sessions,
	         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($direct_average_engagement_time_per_session),
	         	'engaged_sessions_per_user' => ($direct_activeUsers > 0) ? number_format($direct_engaged_sessions/$direct_activeUsers,2) : number_format($direct_engaged_sessions,2),
	         	'events_per_session' => number_format($direct_eventsPerSession/$calculated_duration,2),
	         	'engagement_rate' => number_format($direct_engagementRate,2),
	         	'event_count' => $direct_eventCount,
	         	'conversions' => $direct_conversions,
	         	'total_revenue' => $direct_totalRevenue
	         ];

	         $prev_os_activeUsers = $prev_os_sessions = $prev_os_engaged_sessions = $prev_os_userEngagementDuration = $prev_os_eventsPerSession = $prev_os_engagementRate = $prev_os_eventCount = $prev_os_conversions = $prev_os_totalRevenue = 0;

	         $prev_osearch_activeUsers = $prev_osearch_sessions = $prev_osearch_engaged_sessions = $prev_osearch_userEngagementDuration = $prev_osearch_eventsPerSession = $prev_osearch_engagementRate = $prev_osearch_eventCount = $prev_osearch_conversions = $prev_osearch_totalRevenue = 0;

	         $prev_paidSocial_activeUsers = $prev_paidSocial_sessions = $prev_paidSocial_engaged_sessions = $prev_paidSocial_userEngagementDuration = $prev_paidSocial_eventsPerSession = $prev_paidSocial_engagementRate = $prev_paidSocial_eventCount = $prev_paidSocial_conversions = $prev_paidSocial_totalRevenue = 0;

	         $prev_paidSearch_activeUsers = $prev_paidSearch_sessions = $prev_paidSearch_engaged_sessions = $prev_paidSearch_userEngagementDuration = $prev_paidSearch_eventsPerSession = $prev_paidSearch_engagementRate = $prev_paidSearch_eventCount = $prev_paidSearch_conversions = $prev_paidSearch_totalRevenue = 0;

	         $prev_direct_activeUsers = $prev_direct_sessions = $prev_direct_engaged_sessions = $prev_direct_userEngagementDuration = $prev_direct_eventsPerSession = $prev_direct_engagementRate = $prev_direct_eventCount = $prev_direct_conversions = $prev_direct_totalRevenue = 0;

	         if($comparison == 1){
	         	/*previous duration data*/
	         	$prev_display_range = date("M d, Y",strtotime($previous_start_date)) .' - '.date("M d' Y",strtotime($previous_end_date));


	         	for($j = strtotime($previous_start_date); $j <= strtotime($previous_end_date); $j = $j+86400){
	         		$prev_dateData = date('Y-m-d',$j);

	         		/*organic social*/
	         		$prev_os_activeUsers += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->active_users : 0;
	         		$prev_os_sessions += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->sessions : 0;
	         		$prev_os_engaged_sessions += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->engaged_sessions : 0;
	         		$prev_os_userEngagementDuration += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->user_engagement_duration : 0;
	         		$prev_os_eventsPerSession += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->events_per_session : 0;
	         		$prev_os_engagementRate += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->engagement_rate : 0;
	         		$prev_os_eventCount += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->event_count : 0;
	         		$prev_os_conversions += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->conversions : 0;
	         		$prev_os_totalRevenue += isset($final->$prev_dateData->organic_social) ? $final->$prev_dateData->organic_social->total_revenue : 0;
	         		/*organic search*/
	         		$prev_osearch_activeUsers += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->active_users : 0;
	         		$prev_osearch_sessions += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->sessions : 0;
	         		$prev_osearch_engaged_sessions += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->engaged_sessions : 0;
	         		$prev_osearch_userEngagementDuration += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->user_engagement_duration : 0;
	         		$prev_osearch_eventsPerSession += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->events_per_session : 0;
	         		$prev_osearch_engagementRate += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->engagement_rate : 0;
	         		$prev_osearch_eventCount += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->event_count : 0;
	         		$prev_osearch_conversions += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->conversions : 0;
	         		$prev_osearch_totalRevenue += isset($final->$prev_dateData->organic_search) ? $final->$prev_dateData->organic_search->total_revenue : 0;
	         		/*paid social*/
	         		$prev_paidSocial_activeUsers += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->active_users : 0;
	         		$prev_paidSocial_sessions += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->sessions : 0;
	         		$prev_paidSocial_engaged_sessions += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->engaged_sessions : 0;
	         		$prev_paidSocial_userEngagementDuration += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->user_engagement_duration : 0;
	         		$prev_paidSocial_eventsPerSession += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->events_per_session : 0;
	         		$prev_paidSocial_engagementRate += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->engagement_rate : 0;
	         		$prev_paidSocial_eventCount += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->event_count : 0;
	         		$prev_paidSocial_conversions += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->conversions : 0;
	         		$prev_paidSocial_totalRevenue += isset($final->$prev_dateData->paid_social) ? $final->$prev_dateData->paid_social->total_revenue : 0;

	         		/*paid social*/
	         		$prev_paidSearch_activeUsers += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->active_users : 0;
	         		$prev_paidSearch_sessions += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->sessions : 0;
	         		$prev_paidSearch_engaged_sessions += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->engaged_sessions : 0;
	         		$prev_paidSearch_userEngagementDuration += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->user_engagement_duration : 0;
	         		$prev_paidSearch_eventsPerSession += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->events_per_session : 0;
	         		$prev_paidSearch_engagementRate += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->engagement_rate : 0;
	         		$prev_paidSearch_eventCount += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->event_count : 0;
	         		$prev_paidSearch_conversions += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->conversions : 0;
	         		$prev_paidSearch_totalRevenue += isset($final->$prev_dateData->paid_search) ? $final->$prev_dateData->paid_search->total_revenue : 0;
	         		/*direct*/
	         		$prev_direct_activeUsers += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->active_users : 0;
	         		$prev_direct_sessions += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->sessions : 0;
	         		$prev_direct_engaged_sessions += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->engaged_sessions : 0;
	         		$prev_direct_userEngagementDuration += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->user_engagement_duration : 0;
	         		$prev_direct_eventsPerSession += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->events_per_session : 0;
	         		$prev_direct_engagementRate += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->engagement_rate : 0;
	         		$prev_direct_eventCount += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->event_count : 0;
	         		$prev_direct_conversions += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->conversions : 0;
	         		$prev_direct_totalRevenue += isset($final->$prev_dateData->direct) ? $final->$prev_dateData->direct->total_revenue : 0;	           
		         } //end-of-for

		         $prev_os_average_engagement_time_per_session = ($prev_os_sessions > 0) ? $prev_os_userEngagementDuration/$prev_os_sessions:$prev_os_userEngagementDuration;
		         $prev_osearch_average_engagement_time_per_session = ($prev_osearch_sessions > 0) ? $prev_osearch_userEngagementDuration/$prev_osearch_sessions: $prev_osearch_userEngagementDuration;
		         $prev_paidSocial_average_engagement_time_per_session = ($prev_paidSocial_sessions > 0) ? $prev_paidSocial_userEngagementDuration/$prev_paidSocial_sessions : $prev_paidSocial_userEngagementDuration;
		         $prev_paidSearch_average_engagement_time_per_session = ($prev_paidSearch_sessions > 0) ? $prev_paidSearch_userEngagementDuration/$prev_paidSearch_sessions : $prev_paidSearch_userEngagementDuration;
		         $prev_direct_average_engagement_time_per_session = ($prev_direct_sessions > 0) ? $prev_direct_userEngagementDuration/$prev_direct_sessions : $prev_direct_userEngagementDuration;

		         $response_data['previous']['organic_social'] = [
		         	'channel_group' => 'Organic Social',
		         	'active_users' => $prev_os_activeUsers,
		         	'sessions' => $prev_os_sessions,
		         	'engaged_sessions' => $prev_os_engaged_sessions,
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($prev_os_average_engagement_time_per_session),
		         	'engaged_sessions_per_user' => ($prev_os_activeUsers > 0) ? number_format($prev_os_engaged_sessions/$prev_os_activeUsers,2) : number_format($prev_os_engaged_sessions,2),
		         	'events_per_session' => number_format($prev_os_eventsPerSession/$previous_calculated_duration,2),
		         	'engagement_rate' => number_format($prev_os_engagementRate,2),
		         	'event_count' => $prev_os_eventCount,
		         	'conversions' => $prev_os_conversions,
		         	'total_revenue' => $prev_os_totalRevenue
		         ];

		         $response_data['previous']['organic_search'] = [
		         	'channel_group' => 'Previous Organic Search',
		         	'active_users' => $prev_osearch_activeUsers,
		         	'sessions' => $prev_osearch_sessions,
		         	'engaged_sessions' => $prev_osearch_engaged_sessions,
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($prev_osearch_average_engagement_time_per_session),
		         	'engaged_sessions_per_user' => ($prev_osearch_activeUsers > 0) ? number_format($prev_osearch_engaged_sessions/$prev_osearch_activeUsers,2) : number_format($prev_osearch_engaged_sessions,2),
		         	'events_per_session' => number_format($prev_osearch_eventsPerSession/$previous_calculated_duration,2),
		         	'engagement_rate' => number_format($prev_osearch_engagementRate,2),
		         	'event_count' => $prev_osearch_eventCount,
		         	'conversions' => $prev_osearch_conversions,
		         	'total_revenue' => $prev_osearch_totalRevenue
		         ];

		         $response_data['previous']['paid_social'] = [
		         	'channel_group' => 'Previous Paid Social',
		         	'active_users' => $prev_paidSocial_activeUsers,
		         	'sessions' => $prev_paidSocial_sessions,
		         	'engaged_sessions' => $prev_paidSocial_engaged_sessions,
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($prev_paidSocial_average_engagement_time_per_session),
		         	'engaged_sessions_per_user' => ($prev_paidSocial_activeUsers > 0) ? number_format($prev_paidSocial_engaged_sessions/$prev_paidSocial_activeUsers,2) : number_format($prev_paidSocial_engaged_sessions,2),
		         	'events_per_session' => number_format($prev_paidSocial_eventsPerSession/$previous_calculated_duration,2),
		         	'engagement_rate' => number_format($prev_paidSocial_engagementRate,2),
		         	'event_count' => $prev_paidSocial_eventCount,
		         	'conversions' => $prev_paidSocial_conversions,
		         	'total_revenue' => $prev_paidSocial_totalRevenue
		         ];

		         $response_data['previous']['paid_search'] = [
		         	'channel_group' => 'Previous Paid Search',
		         	'active_users' => $prev_paidSearch_activeUsers,
		         	'sessions' => $prev_paidSearch_sessions,
		         	'engaged_sessions' => $prev_paidSearch_engaged_sessions,
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($prev_paidSearch_average_engagement_time_per_session),
		         	'engaged_sessions_per_user' => ($prev_paidSearch_activeUsers > 0) ? number_format($prev_paidSearch_engaged_sessions/$prev_paidSearch_activeUsers,2) : number_format($prev_paidSearch_engaged_sessions,2),
		         	'events_per_session' => number_format($prev_paidSearch_eventsPerSession/$previous_calculated_duration,2),
		         	'engagement_rate' => number_format($prev_paidSearch_engagementRate,2),
		         	'event_count' => $prev_paidSearch_eventCount,
		         	'conversions' => $prev_paidSearch_conversions,
		         	'total_revenue' => $prev_paidSearch_totalRevenue
		         ];

		         $response_data['previous']['direct'] = [
		         	'channel_group' => 'Previous Direct',
		         	'active_users' => $prev_direct_activeUsers,
		         	'sessions' => $prev_direct_sessions,
		         	'engaged_sessions' => $prev_direct_engaged_sessions,
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_time($prev_direct_average_engagement_time_per_session),
		         	'engaged_sessions_per_user' => ($prev_direct_activeUsers > 0 ) ? number_format($prev_direct_engaged_sessions/$prev_direct_activeUsers,2) : number_format($prev_direct_engaged_sessions,2),
		         	'events_per_session' => number_format($prev_direct_eventsPerSession/$previous_calculated_duration,2),
		         	'engagement_rate' => number_format($prev_direct_engagementRate,2),
		         	'event_count' => $prev_direct_eventCount,
		         	'conversions' => $prev_direct_conversions,
		         	'total_revenue' => $prev_direct_totalRevenue
		         ];


		         $response_data['percentage']['organic_social'] = [
		         	'active_users' => GoogleAnalyticAccount::calculate_percentage($os_activeUsers,$prev_os_activeUsers),
		         	'sessions' => GoogleAnalyticAccount::calculate_percentage($os_sessions,$prev_os_sessions),
		         	'engaged_sessions' => GoogleAnalyticAccount::calculate_percentage($os_engaged_sessions,$prev_os_engaged_sessions),
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_percentage($os_average_engagement_time_per_session,$prev_os_average_engagement_time_per_session),
		         	'engagement_rate' => GoogleAnalyticAccount::calculate_percentage($os_engagementRate,$prev_os_engagementRate),
		         	'event_count' => GoogleAnalyticAccount::calculate_percentage($os_eventCount,$prev_os_eventCount),
		         	'conversions' => GoogleAnalyticAccount::calculate_percentage($os_conversions,$prev_os_conversions),
		         	'total_revenue' => GoogleAnalyticAccount::calculate_percentage($os_totalRevenue,$prev_os_totalRevenue)
		         ];

		         $response_data['percentage']['organic_search'] = [
		         	'active_users' => GoogleAnalyticAccount::calculate_percentage($osearch_activeUsers,$prev_osearch_activeUsers),
		         	'sessions' => GoogleAnalyticAccount::calculate_percentage($osearch_sessions,$prev_osearch_sessions),
		         	'engaged_sessions' => GoogleAnalyticAccount::calculate_percentage($osearch_engaged_sessions,$prev_osearch_engaged_sessions),
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_percentage($osearch_average_engagement_time_per_session,$prev_osearch_average_engagement_time_per_session),
		         	'engagement_rate' => GoogleAnalyticAccount::calculate_percentage($osearch_engagementRate,$prev_osearch_engagementRate),
		         	'event_count' => GoogleAnalyticAccount::calculate_percentage($osearch_eventCount,$prev_osearch_eventCount),
		         	'conversions' => GoogleAnalyticAccount::calculate_percentage($osearch_conversions,$prev_osearch_conversions),
		         	'total_revenue' => GoogleAnalyticAccount::calculate_percentage($osearch_totalRevenue,$prev_osearch_totalRevenue)
		         ];

		         $response_data['percentage']['paid_social'] = [
		         	'active_users' => GoogleAnalyticAccount::calculate_percentage($paidSocial_activeUsers,$prev_paidSocial_activeUsers),
		         	'sessions' => GoogleAnalyticAccount::calculate_percentage($paidSocial_sessions,$prev_paidSocial_sessions),
		         	'engaged_sessions' => GoogleAnalyticAccount::calculate_percentage($paidSocial_engaged_sessions,$prev_paidSocial_engaged_sessions),
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_percentage($paidSocial_average_engagement_time_per_session,$prev_paidSocial_average_engagement_time_per_session),
		         	'engagement_rate' => GoogleAnalyticAccount::calculate_percentage($paidSocial_engagementRate,$prev_paidSocial_engagementRate),
		         	'event_count' => GoogleAnalyticAccount::calculate_percentage($paidSocial_eventCount,$prev_paidSocial_eventCount),
		         	'conversions' => GoogleAnalyticAccount::calculate_percentage($paidSocial_conversions,$prev_paidSocial_conversions),
		         	'total_revenue' => GoogleAnalyticAccount::calculate_percentage($paidSocial_totalRevenue,$prev_paidSocial_totalRevenue)
		         ];

		         $response_data['percentage']['paid_search'] = [
		         	'active_users' => GoogleAnalyticAccount::calculate_percentage($paidSearch_activeUsers,$prev_paidSearch_activeUsers),
		         	'sessions' => GoogleAnalyticAccount::calculate_percentage($paidSearch_sessions,$prev_paidSearch_sessions),
		         	'engaged_sessions' => GoogleAnalyticAccount::calculate_percentage($paidSearch_engaged_sessions,$prev_paidSearch_engaged_sessions),
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_percentage($paidSearch_average_engagement_time_per_session,$prev_paidSearch_average_engagement_time_per_session),
		         	'engagement_rate' => GoogleAnalyticAccount::calculate_percentage($paidSearch_engagementRate,$prev_paidSearch_engagementRate),
		         	'event_count' => GoogleAnalyticAccount::calculate_percentage($paidSearch_eventCount,$prev_paidSearch_eventCount),
		         	'conversions' => GoogleAnalyticAccount::calculate_percentage($paidSearch_conversions,$prev_paidSearch_conversions),
		         	'total_revenue' => GoogleAnalyticAccount::calculate_percentage($paidSearch_totalRevenue,$prev_paidSearch_totalRevenue)
		         ];

		         $response_data['percentage']['direct'] = [
		         	'active_users' => GoogleAnalyticAccount::calculate_percentage($direct_activeUsers,$prev_direct_activeUsers),
		         	'sessions' => GoogleAnalyticAccount::calculate_percentage($direct_sessions,$prev_direct_sessions),
		         	'engaged_sessions' => GoogleAnalyticAccount::calculate_percentage($direct_engaged_sessions,$prev_direct_engaged_sessions),
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_percentage($direct_average_engagement_time_per_session,$prev_direct_average_engagement_time_per_session),
		         	'engagement_rate' => GoogleAnalyticAccount::calculate_percentage($direct_engagementRate,$prev_direct_engagementRate),
		         	'event_count' => GoogleAnalyticAccount::calculate_percentage($direct_eventCount,$prev_direct_eventCount),
		         	'conversions' => GoogleAnalyticAccount::calculate_percentage($direct_conversions,$prev_direct_conversions),
		         	'total_revenue' => GoogleAnalyticAccount::calculate_percentage($direct_totalRevenue,$prev_direct_totalRevenue)
		         ];


		         $prev_total_active_users = $prev_os_activeUsers + $prev_osearch_activeUsers + $prev_paidSocial_activeUsers + $prev_paidSearch_activeUsers + $prev_direct_activeUsers;
		         $prev_total_sessions = $prev_os_sessions + $prev_osearch_sessions + $prev_paidSocial_sessions + $prev_paidSearch_sessions + $prev_direct_sessions;
		         $prev_total_engaged_sessions = $prev_os_engaged_sessions + $prev_osearch_engaged_sessions + $prev_paidSocial_engaged_sessions + $prev_paidSearch_engaged_sessions + $prev_direct_engaged_sessions;
		         $prev_total_eventsPerSession = ($prev_os_eventsPerSession + $prev_osearch_eventsPerSession + $prev_paidSocial_eventsPerSession + $prev_paidSearch_eventsPerSession + $prev_direct_eventsPerSession)/$previous_calculated_duration;
		         $prev_total_engagementRate = ($prev_os_engagementRate + $prev_osearch_engagementRate + $prev_paidSocial_engagementRate + $prev_paidSearch_engagementRate + $prev_direct_engagementRate)/5;
		         $prev_total_eventCount = $prev_os_eventCount + $prev_osearch_eventCount + $prev_paidSocial_eventCount + $prev_paidSearch_eventCount + $prev_direct_eventCount;
		         $prev_total_conversions = $prev_os_conversions + $prev_osearch_conversions + $prev_paidSocial_conversions + $prev_paidSearch_conversions + $prev_direct_conversions;
		         $prev_total_totalRevenue = $prev_os_totalRevenue + $prev_osearch_totalRevenue + $prev_paidSocial_totalRevenue + $prev_paidSearch_totalRevenue + $prev_direct_totalRevenue;

		         $prev_average_engagement_time_per_session = $prev_os_average_engagement_time_per_session + $prev_osearch_average_engagement_time_per_session + $prev_paidSocial_average_engagement_time_per_session + $prev_paidSearch_average_engagement_time_per_session + $prev_direct_average_engagement_time_per_session;
		         $prev_total_userEngagementDuration = GoogleAnalyticAccount::calculate_time($prev_average_engagement_time_per_session);

		         $prev_total_engaged_sessions_perSession = ($prev_total_active_users > 0) ? $prev_total_engaged_sessions/$prev_total_active_users: $prev_total_engaged_sessions;

		         $response_data['total'] = [
		         	'active_users' => GoogleAnalyticAccount::calculate_percentage($total_active_users,$prev_total_active_users),
		         	'sessions' => GoogleAnalyticAccount::calculate_percentage($total_sessions,$prev_total_sessions),
		         	'engaged_sessions' => GoogleAnalyticAccount::calculate_percentage($total_engaged_sessions,$prev_total_engaged_sessions),
		         	'average_engagement_time_per_session' => GoogleAnalyticAccount::calculate_percentage($average_engagement_time_per_session,$prev_average_engagement_time_per_session),
		         	'engaged_sessions_perSession' => GoogleAnalyticAccount::calculate_percentage($total_engaged_sessions_perSession,$prev_total_engaged_sessions_perSession),
		         	'eventsPerSession' => GoogleAnalyticAccount::calculate_percentage($total_eventsPerSession,$prev_total_eventsPerSession),
		         	'engagement_rate' => GoogleAnalyticAccount::calculate_percentage($total_engagementRate,$prev_total_engagementRate),
		         	'event_count' => GoogleAnalyticAccount::calculate_percentage($total_eventCount,$prev_total_eventCount),
		         	'conversions' => GoogleAnalyticAccount::calculate_percentage($total_conversions,$prev_total_conversions),
		         	'total_revenue' => GoogleAnalyticAccount::calculate_percentage($total_totalRevenue,$prev_total_totalRevenue)
		         ];


		         return view('viewkey.pdf.seo_sections.ga4_goals_listing',compact('response_data','comparison','status','display_range','prev_display_range','total_active_users','total_sessions','total_engaged_sessions','total_userEngagementDuration','total_eventsPerSession','total_engagementRate','total_eventCount','total_conversions', 'total_engaged_sessions_perSession','total_totalRevenue','prev_total_active_users','prev_total_sessions','prev_total_engaged_sessions','prev_total_eventsPerSession','prev_total_engagementRate','prev_total_eventCount','prev_total_conversions','prev_total_totalRevenue','prev_total_userEngagementDuration','prev_total_engaged_sessions_perSession'))->render();
		     }

		     return view('viewkey.pdf.seo_sections.ga4_goals_listing',compact('response_data','comparison','status','display_range','total_active_users','total_sessions','total_engaged_sessions','total_userEngagementDuration','total_eventsPerSession','total_engagementRate','total_eventCount','total_conversions', 'total_engaged_sessions_perSession','total_totalRevenue'))->render();
		 }
		}

		public function ajax_get_ga4_connected_accounts(Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id);
			$campaign_id = $request->id;
			$campaign_data = SemrushUserAccount::select('id','ga4_email_id','ga4_account_id','ga4_property_id')->where('id',$campaign_id)->first();

			$emails = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','ga4')->get();
			$accounts = GoogleAnalyticAccount::where([['user_id',$user_id],['google_email_id',$campaign_data->ga4_email_id]])->get();
			$property = GoogleAnalyticAccount::where([['user_id',$user_id],['parent_id',$campaign_data->ga4_account_id]])->get();

			$emails_li =   '<option value="">No Result Found</option>';

			if(!empty($emails)) {
				$emails_li =   '<option value="">Select from existing account</option>';
				foreach($emails as $result) {
					$selected = $result->id == $campaign_data->ga4_email_id ? "selected" : "";
					$emails_li	.= '<option value="'.$result->id.'" '.$selected.'>'.$result->email.'</option>';
				} 
			}

			$accounts_li =   '<option value="">No Result Found</option>';
			if(!empty($emails)) {
				$accounts_li =   '<option value="">Select Account</option>';
				foreach($accounts as $accounts_result) {
					$selected = $accounts_result->id == $campaign_data->ga4_account_id ? "selected" : "";
					$accounts_li	.= '<option value="'.$accounts_result->id.'" '.$selected.'>'.$accounts_result->display_name.'</option>';
				} 
			}

			$property_li =   '<option value="">No Result Found</option>';
			if(!empty($emails)) {
				$property_li =   '<option value="">Select Property</option>';
				foreach($property as $property_result) {
					$selected = $property_result->id == $campaign_data->ga4_property_id ? "selected" : "";
					$property_li	.= '<option value="'.$property_result->id.'" '.$selected.'>'.$property_result->display_name.'</option>';
				} 
			}


			$data['emails'] = $emails_li;  
			$data['accounts'] = $accounts_li;  
			$data['property'] = $property_li;  
			return $data;
		}
	}