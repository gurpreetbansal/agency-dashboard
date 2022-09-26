<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\GoogleAnalyticsUsers;
use App\SemrushUserAccount;
use App\User; 
use App\GmbLocation; 
use App\ModuleByDateRange; 
use App\GoogleAdsCustomer; 
use App\GoogleUpdate; 
use App\Traits\GMBAuth;

use Auth;
use \Illuminate\Pagination\LengthAwarePaginator;

use App\SearchConsoleUsers; 
use App\Error; 


// require_once \config('app.FILE_PATH') . 'vendor/google/auth/autoload.php';

class GMBController extends Controller {

	use GMBAuth;
	
	public function connectGMB(Request $request){
		try{
			$google_redirect_url = \config('app.base_url').'gmb/connect';
			
			$client = new \Google\Client();
			$client->setAuthConfig(\config('app.FILE_PATH').\env('ANALYTICS_CONFIG'));	
			$client->setRedirectUri($google_redirect_url);
			$client->setScopes('email');
			$client->addScope("https://www.googleapis.com/auth/business.manage");
			$client->addScope("https://www.googleapis.com/auth/plus.business.manage");
			$client->addScope("email");
			$client->addScope("profile");
			$client->setAccessType("offline");
			$client->setState($request->campaignId.'-'.$request->redirectPage);
			$client->setIncludeGrantedScopes(true);
			$client->setApprovalPrompt('force');


			if ($request->get('code') == NULL) {
				$auth_url = $client->createAuthUrl();
				return redirect()->to($auth_url);
			} else {

				$exploded_value = explode('-',$request->state);
				$campaignId = $exploded_value[0];
				if(isset($exploded_value[2])){
					$redirectPage = $exploded_value[1].'-'.$exploded_value[2];
				}else{
					$redirectPage = $exploded_value[1];
				}
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

				/*logged-in user data*/
				$getUserDetails = SemrushUserAccount::findorfail($campaignId);
				$getLoggedInUser = User::findorfail($getUserDetails->user_id);
				$domainName = $getLoggedInUser->company_name;
				
				/*logged-in user data*/

				$sessionData = Session::all();
				
				$userDetails = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $sessionData['token']['access_token']);
				$googleuser = json_decode($userDetails);

				$checkIfExists = GoogleAnalyticsUsers::where('user_id',$getUserDetails->user_id)->where('oauth_uid',$googleuser->id)->where('oauth_provider','gmb')->first();

				
				if(empty($checkIfExists)){
					SearchConsoleUsers::updateRefreshNAccessToken($googleuser->email,$getUserDetails->user_id,$sessionData['token']);
					$this->addLocation($client,$getUserDetails,$sessionData, $googleuser);

				}else if(!empty($sessionData['token']['access_token'])){

					$refresh_token 	= isset($sessionData['token']['refresh_token']) ? $sessionData['token']['refresh_token'] : $checkIfExists->google_refresh_token;

					if ($client->isAccessTokenExpired()){
						$client->refreshToken($sessionData['token']['refresh_token']);
					}
					SearchConsoleUsers::updateRefreshNAccessToken($googleuser->email,$getUserDetails->user_id,$sessionData['token']);
					$this->updateLocation($client,$getUserDetails,$sessionData, $googleuser,$refresh_token,$checkIfExists);
					
				}

				if($redirectPage == 'authorization'){
					$returnUrl = 'https://'.$domainName.'.'.config('app.APP_DOMAIN').$redirectPage;
				}

				if($redirectPage == 'add-new'){
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
			}
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}


	public function gmbStore(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$check = $this->checkGmbData($request->campaign_id,$user_id,$request->email,$request->account);

		if($check['status'] == 0){
			Error::updateOrCreate(
				['request_id' => $request->campaign_id,'module'=> 4],
				['response'=> json_encode($check),'request_id' => $request->campaign_id,'module'=> 4]
			);
			$response = SemrushUserAccount::display_google_errorMessages(4,$request->campaign_id);
			return response()->json($response);
		}
		
		$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)
		->update([
			'gmb_id'=>$request->account,
			'gmb_analaytics_id'=>$request->email
		]);

		$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();
		$acc_id = $if_Exist->gmb_analaytics_id;

		$accountName = GmbLocation::where('id',$if_Exist->gmb_id)->pluck('location_name')->first();
		$email = GoogleAnalyticsUsers::where('id',$if_Exist->gmb_analaytics_id)->pluck('email')->first();

		if($update) {			
			$response['status'] = 'success';
			$response['id'] = $if_Exist->id;
			$response['accountName'] = $accountName;
			$response['email'] = $email;
			$response['type'] = 'new';
			//delete if google error exists
			$ifErrorExists = Error::removeExisitingError(4,$request->campaign_id);
			if(!empty($ifErrorExists)){
				Error::where('id',$ifErrorExists->id)->delete();
			}
		} else if($acc_id == $request->account){
			$response['status'] = 'success';
			$response['id'] = $if_Exist->id;
			$response['accountName'] = $accountName;
			$response['email'] = $accountName;
			$response['type'] = 'existing';
		} else {
			$response['status'] = 'error'; 
		}				
		return  response()->json($response);
	}


	/* Old GMB functions */
	public function connect_gmb(Request $request){

		try{
			$google_redirect_url = \config('app.base_url').'connect_gmb';
			
			$client = new \Google\Client();
			$client->setAuthConfig(\config('app.FILE_PATH').\env('ANALYTICS_CONFIG'));	
			$client->setRedirectUri($google_redirect_url);
			$client->setScopes('email');
			$client->addScope("https://www.googleapis.com/auth/business.manage");
			$client->addScope("https://www.googleapis.com/auth/plus.business.manage");
			$client->addScope("email");
			$client->addScope("profile");
			$client->setAccessType("offline");
			$client->setState($request->campaignId.'-'.$request->redirectPage);
			$client->setIncludeGrantedScopes(true);
			$client->setApprovalPrompt('force');


			if ($request->get('code') == NULL) {
				$auth_url = $client->createAuthUrl();
				return redirect()->to($auth_url);
			} else {

				$exploded_value = explode('-',$request->state);
				$campaignId = $exploded_value[0];
				if(isset($exploded_value[2])){
					$redirectPage = $exploded_value[1].'-'.$exploded_value[2];
				}else{
					$redirectPage = $exploded_value[1];
				}
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

				/*logged-in user data*/
				$getUserDetails = SemrushUserAccount::findorfail($campaignId);
				$getLoggedInUser = User::findorfail($getUserDetails->user_id);
				$domainName = $getLoggedInUser->company_name;
				
				/*logged-in user data*/

				$sessionData = Session::all();
				
				$userDetails = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $sessionData['token']['access_token']);
				$googleuser = json_decode($userDetails);

				$checkIfExists = GoogleAnalyticsUsers::where('user_id',$getUserDetails->user_id)->where('oauth_uid',$googleuser->id)->where('oauth_provider','gmb')->first();

				
				if(empty($checkIfExists)){
					SearchConsoleUsers::updateRefreshNAccessToken($googleuser->email,$getUserDetails->user_id,$sessionData['token']);
					$this->addLocation($client,$getUserDetails,$sessionData, $googleuser);

				}else if(!empty($sessionData['token']['access_token'])){

					$refresh_token 	= isset($sessionData['token']['refresh_token']) ? $sessionData['token']['refresh_token'] : $checkIfExists->google_refresh_token;

					if ($client->isAccessTokenExpired()){
						$client->refreshToken($sessionData['token']['refresh_token']);
					}
					SearchConsoleUsers::updateRefreshNAccessToken($googleuser->email,$getUserDetails->user_id,$sessionData['token']);
					$this->updateLocation($client,$getUserDetails,$sessionData, $googleuser,$refresh_token,$checkIfExists);
					
				}

				if($redirectPage == 'authorization'){
					$returnUrl = 'https://'.$domainName.'.'.config('app.APP_DOMAIN').$redirectPage;
				}

				if($redirectPage == 'add-new'){
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
			}
		} catch (\Exception $e) {
			return $e->getMessage();
		}
		
		// try{
		// 	$google_redirect_url = \config('app.base_url').'connect_gmb';
			
		// 	$client = new \Google\Client();
		// 	$client->setAuthConfig(\config('app.FILE_PATH').\env('ANALYTICS_CONFIG'));	
		// 	$client->setRedirectUri($google_redirect_url);
		// 	$client->setScopes('email');
		// 	$client->addScope("https://www.googleapis.com/auth/business.manage");
		// 	$client->addScope("https://www.googleapis.com/auth/plus.business.manage");
		// 	$client->addScope("email");
		// 	$client->addScope("profile");
		// 	$client->setAccessType("offline");
		// 	$client->setState($request->campaignId.'-'.$request->redirectPage);
		// 	$client->setIncludeGrantedScopes(true);
		// 	$client->setApprovalPrompt('force');


		// 	if ($request->get('code') == NULL) {
		// 		$auth_url = $client->createAuthUrl();
		// 		return redirect()->to($auth_url);
		// 	} else {

		// 		$exploded_value = explode('-',$request->state);
		// 		$campaignId = $exploded_value[0];
		// 		if(isset($exploded_value[2])){
		// 			$redirectPage = $exploded_value[1].'-'.$exploded_value[2];
		// 		}else{
		// 			$redirectPage = $exploded_value[1];
		// 		}
		// 		if ($request->get('code')){
		// 			$client->authenticate($request->get('code'));
		// 			$client->refreshToken($request->get('code'));
		// 			Session::put('token', $client->getAccessToken());
					
		// 		}
				
		// 		if ($request->session()->get('token'))
		// 		{
		// 			$client->setAccessToken($request->session()->get('token'));
		// 		}
		// 		$session_result	= $client->getAccessToken();

		// 		/*logged-in user data*/
		// 		$getUserDetails = SemrushUserAccount::findorfail($campaignId);
		// 		$getLoggedInUser = User::findorfail($getUserDetails->user_id);
		// 		$domainName = $getLoggedInUser->company_name;
				
		// 		/*logged-in user data*/

		// 		$sessionData = Session::all();
				
		// 		$userDetails = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $sessionData['token']['access_token']);
		// 		$googleuser = json_decode($userDetails);

		// 		$this->getAccounts($client);

		// 		$mybusinessService = new \Google_Service_MyBusiness($client);

		// 		// $my_business_account = new \Google_Service_MyBusinessBusinessInformation($client);

		// 		// $list_accounts_response = $mybusinessService->accounts_locations;
		// 		$optParams = array(
		// 		           'name',
		// 		);


		// 		echo "<pre/>"; print_r($mybusinessService->accounts->listAccounts($optParams)); die;
		// 		echo "<pre/>"; print_r($mybusinessService->accounts->listAccounts()); die;
		// 		$checkIfExists = GoogleAnalyticsUsers::where('user_id',$getUserDetails->user_id)->where('oauth_uid',$googleuser->id)->where('oauth_provider','gmb')->first();

				
		// 		if(empty($checkIfExists)){
		// 			SearchConsoleUsers::updateRefreshNAccessToken($googleuser->email,$getUserDetails->user_id,$sessionData['token']);
		// 			$this->addLocation($client,$mybusinessService,$getUserDetails,$sessionData, $googleuser);

		// 		}else if(!empty($sessionData['token']['access_token'])){


		// 			$refresh_token 	= isset($sessionData['token']['refresh_token']) ? $sessionData['token']['refresh_token'] : $checkIfExists->google_refresh_token;

		// 			if ($client->isAccessTokenExpired()){
		// 				$client->refreshToken($sessionData['token']['refresh_token']);
		// 			}
		// 			SearchConsoleUsers::updateRefreshNAccessToken($googleuser->email,$getUserDetails->user_id,$sessionData['token']);
		// 			$this->updateLocation($client,$mybusinessService,$getUserDetails,$sessionData, $googleuser,$refresh_token,$checkIfExists);
		// 		}

		// 		if($redirectPage == 'authorization'){
		// 			$returnUrl = 'https://'.$domainName.'.'.config('app.APP_DOMAIN').$redirectPage;
		// 		}

		// 		if($redirectPage == 'add-new'){
		// 			echo  "<script>";
		// 			echo "window.close();";
		// 			echo "</script>";
		// 			return;
					
		// 		}

		// 		if($redirectPage == 'add-new-project'){
		// 			echo  "<script>";
		// 			echo "window.close();";
		// 			echo "</script>";
		// 			return;
					
		// 		}

		// 		if($redirectPage == 'campaign-detail'){
		// 			echo  "<script>";
		// 			echo "window.close();";
		// 			echo "</script>";
		// 			return;
					
		// 		}

		// 		if($redirectPage == 'project-settings'){
		// 			echo  "<script>";
		// 			echo "window.close();";
		// 			echo "</script>";
		// 			return;
					
		// 		}
		// 	}
		// } catch (\Exception $e) {
		// 	return $e->getMessage();
		// }
	}

	public function ajax_update_gmb_data(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$check = $this->checkGmbData($request->campaign_id,$user_id,$request->email,$request->account);

		if($check['status'] == 0){
			Error::updateOrCreate(
				['request_id' => $request->campaign_id,'module'=> 4],
				['response'=> json_encode($check),'request_id' => $request->campaign_id,'module'=> 4]
			);
			$response = SemrushUserAccount::display_google_errorMessages(4,$request->campaign_id);
			return response()->json($response);
		}
		
		$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)
		->update([
			'gmb_id'=>$request->account,
			'gmb_analaytics_id'=>$request->email
		]);

		$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();
		$acc_id = $if_Exist->gmb_analaytics_id;

		$accountName = GmbLocation::where('id',$if_Exist->gmb_id)->pluck('location_name')->first();
		$email = GoogleAnalyticsUsers::where('id',$if_Exist->gmb_analaytics_id)->pluck('email')->first();

		if($update) {			
			$response['status'] = 'success';
			$response['id'] = $if_Exist->id;
			$response['accountName'] = $accountName;
			$response['email'] = $email;
			$response['type'] = 'new';
			//delete if google error exists
			$ifErrorExists = Error::removeExisitingError(4,$request->campaign_id);
			if(!empty($ifErrorExists)){
				Error::where('id',$ifErrorExists->id)->delete();
			}
		} 
		else if($acc_id == $request->email){
			$response['status'] = 'success';
			$response['id'] = $if_Exist->id;
			$response['accountName'] = $accountName;
			$response['email'] = $accountName;
			$response['type'] = 'existing';
		} 
		else {
			$response['status'] = 'error'; 
		}				
		return  response()->json($response);
	}


	public function ajax_fetch_customer_view_graph(Request $request){

		$campaignId = $request['campaignId'];

		if (!file_exists(env('FILE_PATH')."public/gmb/".$campaignId)) {
			$res['status'] = 0;
		} else {

			$url = env('FILE_PATH')."public/gmb/".$campaignId.'/customer_view_graph.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);


			if($request->key <> null && $request->value <> null){
				if($request->value == 1){
					$start_date = date('Y-m-d',strtotime('-1 month'));
				}elseif($request->value == 3){
					$start_date = date('Y-m-d',strtotime('-3 month'));
				}elseif($request->value == 6){
					$start_date = date('Y-m-d',strtotime('-6 month'));
				}elseif($request->value == 9){
					$start_date = date('Y-m-d',strtotime('-9 month'));
				}elseif($request->value == 12){
					$start_date = date('Y-m-d',strtotime('-1 year'));
				}
			}else{
				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'gmb_customer_view');
				if(!empty($sessionHistoryRange)){
					$duration = $sessionHistoryRange->duration;


					if($duration == 1){
						$start_date = date('Y-m-d',strtotime('-1 month'));
					}elseif($duration == 3){
						$start_date = date('Y-m-d',strtotime('-3 month'));
					}elseif($duration == 6){
						$start_date = date('Y-m-d',strtotime('-6 month'));
					}elseif($duration == 9){
						$start_date = date('Y-m-d',strtotime('-9 month'));
					}elseif($duration == 12){
						$start_date = date('Y-m-d',strtotime('-1 year'));
					}
				}else{
					$start_date = date('Y-m-d',strtotime('-3 month'));
				}
			}

			$end = end($final->convrted_dates); 

			if(in_array($start_date,$final->convrted_dates)){
				$get_index = array_search($start_date,$final->convrted_dates);
				$get_index_today = array_search($end,$final->convrted_dates);
			}else{
				$get_index = 0;
				$get_index_today = array_search($end,$final->convrted_dates);
			}


			$current = $current_data = $current_prev = $prev_data = array();

			for($i=$get_index;$i<=$get_index_today;$i++){

				if(isset($final->convrted_dates[$i])){
					$dates[] = $final->convrted_dates[$i];
					$search[] = array('t'=>strtotime($final->convrted_dates[$i])*1000,'y'=>array('t'=>strtotime($final->convrted_dates[$i])*1000,'y'=>$final->search[$i]));
					$maps[] = array('t'=>strtotime($final->convrted_dates[$i])*1000,'y'=>array('t'=>strtotime($final->convrted_dates[$i])*1000,'y'=>$final->maps[$i]));
				}else{
					$dates[] = date('Y-m-d',$i);
					$search[] = array('t'=>strtotime(date('Y-m-d',$i))*1000,'y'=>array('t'=>strtotime(date('Y-m-d',$i))*1000,'y'=>0));
					$maps[] = array('t'=>strtotime(date('Y-m-d',$i))*1000,'y'=>array('t'=>strtotime(date('Y-m-d',$i))*1000,'y'=>0));
				}
				
			} 

			

			$res['from_datelabel'] = $dates;
			$res['search'] = $search;
			$res['maps'] = $maps;
			$res['status'] = 1;
		}

		return response()->json($res);


	}

	public function ajax_fetch_customer_action_graph(Request $request){

		$campaignId = $request['campaignId'];

		if (!file_exists(env('FILE_PATH')."public/gmb/".$campaignId)) {
			$res['status'] = 0;
		} else {

			$url = env('FILE_PATH')."public/gmb/".$campaignId.'/customer_action_graph.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);

			if($request->key <> null && $request->value <> null){
				if($request->value == 1){
					$start_date = date('Y-m-d',strtotime('-1 month'));
				}elseif($request->value == 3){
					$start_date = date('Y-m-d',strtotime('-3 month'));
				}elseif($request->value == 6){
					$start_date = date('Y-m-d',strtotime('-6 month'));
				}elseif($request->value == 9){
					$start_date = date('Y-m-d',strtotime('-9 month'));
				}elseif($request->value == 12){
					$start_date = date('Y-m-d',strtotime('-1 year'));
				}
			}else{
				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'gmb_customer_action');
				if(!empty($sessionHistoryRange)){
					$duration = $sessionHistoryRange->duration;
					if($duration == 1){
						$start_date = date('Y-m-d',strtotime('-1 month'));
					}elseif($duration == 3){
						$start_date = date('Y-m-d',strtotime('-3 month'));
					}elseif($duration == 6){
						$start_date = date('Y-m-d',strtotime('-6 month'));
					}elseif($duration == 9){
						$start_date = date('Y-m-d',strtotime('-9 month'));
					}elseif($duration == 12){
						$start_date = date('Y-m-d',strtotime('-1 year'));
					}
				}else{
					$start_date = date('Y-m-d',strtotime('-3 month'));
				}
			}

			$end = end($final->convrted_dates); 

			if(in_array($start_date,$final->convrted_dates)){
				$get_index = array_search($start_date,$final->convrted_dates);
				$get_index_today = array_search($end,$final->convrted_dates);
			}else{
				$get_index = 0;
				$get_index_today = array_search($end,$final->convrted_dates);
			}


			$current = $current_data = $current_prev = $prev_data = array();

			for($i=$get_index;$i<=$get_index_today;$i++){

				if(isset($final->convrted_dates[$i])){
					$dates[] = $final->convrted_dates[$i];
					$website_count[] = $final->website[$i];
					$direction_count[] = $final->directions[$i];
					$phone_count[] = $final->phone[$i];

					$website[] = array('t'=>strtotime($final->convrted_dates[$i])*1000,'y'=>array('t'=>strtotime($final->convrted_dates[$i])*1000,'y'=>$final->website[$i]));
					$directions[] = array('t'=>strtotime($final->convrted_dates[$i])*1000,'y'=>array('t'=>strtotime($final->convrted_dates[$i])*1000,'y'=>$final->directions[$i]));
					$phone[] = array('t'=>strtotime($final->convrted_dates[$i])*1000,'y'=>array('t'=>strtotime($final->convrted_dates[$i])*1000,'y'=>$final->phone[$i]));
				}else{
					$dates[] = date('Y-m-d',strtotime($i));
					$website_count[] = 0;
					$direction_count[] = 0;
					$phone_count[] = 0;

					$website[] = array('t'=>strtotime(date('Y-m-d',strtotime($i)))*1000,'y'=>array('t'=>strtotime(date('Y-m-d',strtotime($i)))*1000,'y'=>0));
					$directions[] = array('t'=>strtotime(date('Y-m-d',strtotime($i)))*1000,'y'=>array('t'=>strtotime(date('Y-m-d',strtotime($i)))*1000,'y'=>0));
					$phone[] = array('t'=>strtotime(date('Y-m-d',strtotime($i)))*1000,'y'=>array('t'=>strtotime(date('Y-m-d',strtotime($i)))*1000,'y'=>0));
				}

				
			} 

			
			$res['from_datelabel'] = $dates;
			$res['website'] = $website;
			$res['directions'] = $directions;
			$res['phone'] = $phone;
			/*total count*/
			$res['website_count'] = array_sum($website_count);
			$res['direction_count'] = array_sum($direction_count);
			$res['phone_count'] = array_sum($phone_count);
			$res['status'] = 1;
		}

		return response()->json($res);
		

	}


	public function ajax_fetch_photo_views_graph(Request $request){

		$campaignId = $request['campaignId'];

		if (!file_exists(env('FILE_PATH')."public/gmb/".$campaignId)) {
			$res['status'] = 0;
		} else {
			$total = array();
			$url = env('FILE_PATH')."public/gmb/".$campaignId.'/photo_views.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);
			if($request->key <> null && $request->value <> null){
				if($request->value == 7){
					$start_date = date('Y-m-d',strtotime('-1 week'));
				}elseif($request->value == 1){
					$start_date = date('Y-m-d',strtotime('-1 month'));
				}elseif($request->value == 3){
					$start_date = date('Y-m-d',strtotime('-3 month'));
				}elseif($request->value == 6){
					$start_date = date('Y-m-d',strtotime('-6 month'));
				}elseif($request->value == 9){
					$start_date = date('Y-m-d',strtotime('-9 month'));
				}elseif($request->value == 12){
					$start_date = date('Y-m-d',strtotime('-1 year'));
				}
			}else{

				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'gmb_photo_views');
				if(!empty($sessionHistoryRange)){
					$duration = $sessionHistoryRange->duration;
					if($duration == 7){
						$start_date = date('Y-m-d',strtotime('-1 week'));
					}elseif($duration == 1){
						$start_date = date('Y-m-d',strtotime('-1 month'));
					}elseif($duration == 3){
						$start_date = date('Y-m-d',strtotime('-3 month'));
					}elseif($duration == 6){
						$start_date = date('Y-m-d',strtotime('-6 month'));
					}elseif($duration == 9){
						$start_date = date('Y-m-d',strtotime('-9 month'));
					}elseif($duration == 12){
						$start_date = date('Y-m-d',strtotime('-1 year'));
					}
				}else{
					$start_date = date('Y-m-d',strtotime('-3 month'));
				}
			}

			$end = end($final->convrted_dates); 

			if(in_array($start_date,$final->convrted_dates)){
				$get_index = array_search($start_date,$final->convrted_dates);
				$get_index_today = array_search($end,$final->convrted_dates);
			}else{
				$get_index = 0;
				$get_index_today = array_search($end,$final->convrted_dates);
			}


			$current = $current_data = $current_prev = $prev_data = array();

			for($i=$get_index;$i<=$get_index_today;$i++){

				if(isset($final->value[$i])){
					$total[] = $final->value[$i];

					$dates[] = $final->convrted_dates[$i];
					$you[] = array('t'=>$final->convrted_dates[$i],'y'=>$final->value[$i]);

				}else{
					$total[] = 0;

					$dates[] = date('Y-m-d',strtotime($i));
					$you[] = array('t'=>date('Y-m-d',strtotime($i)),'y'=>0);

				}
			} 

			$res['from_datelabel'] = $dates;
			$res['you'] = $you;
			$res['you_count'] = array_sum($total);
			$res['status'] = 1;
		}

		return response()->json($res);
		

	}


	public function ajax_get_Customer_search(Request $request){
		// dd($request->all());
		$campaignId = $request['campaignId'];
		if (!file_exists(env('FILE_PATH')."public/gmb/".$campaignId)) {
			$res['status'] = 0;
		} else {

			$url = env('FILE_PATH')."public/gmb/".$campaignId.'/customer_search.json'; 
			$dataContent = file_get_contents($url);
			$final = json_decode($dataContent);

			if($request->key <> null && $request->value <> null){
				$duration = $request->value;
			}else{
				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'gmb_customer_search');
				if(!empty($sessionHistoryRange)){
					$duration = $sessionHistoryRange->duration;
				}else{
					$duration = 3;
				}
			}

			$total = 0;
			$data = [];
			if($duration == 1){

				if(isset($final->month_array->direct)){
					$data[] = (int)$final->month_array->direct;
					$total += $final->month_array->direct;
				}
				if(isset($final->month_array->discovery)){
					$data[] = (int)$final->month_array->discovery;
					$total += $final->month_array->discovery;
				}
				if(isset($final->month_array->branded)){
					$data[] = (int)$final->month_array->branded;
					$total += $final->month_array->branded;
				}

				// $data = [(int)$final->month_array->direct,(int)$final->month_array->discovery,(int)$final->month_array->branded];
				// $total = $final->month_array->direct+$final->month_array->discovery+$final->month_array->branded;
			}
			
			if($duration == 3){
				if(isset($final->three_array->direct)){
					$data[] = (int)$final->three_array->direct;
					$total += $final->three_array->direct;
				}
				if(isset($final->three_array->discovery)){
					$data[] = (int)$final->three_array->discovery;
					$total += $final->three_array->discovery;
				}
				if(isset($final->three_array->branded)){
					$data[] = (int)$final->three_array->branded;
					$total += $final->three_array->branded;
				}

				// $data = [(int)$final->three_array->direct,(int)$final->three_array->discovery,(int)$final->three_array->branded];
				// $total = $final->three_array->direct+$final->three_array->discovery+$final->three_array->branded;
			}

			if($duration == 6){
				if(isset($final->six_array->direct)){
					$data[] = (int)$final->six_array->direct;
					$total += $final->six_array->direct;
				}
				if(isset($final->six_array->discovery)){
					$data[] = (int)$final->six_array->discovery;
					$total += $final->six_array->discovery;
				}
				if(isset($final->six_array->branded)){
					$data[] = (int)$final->six_array->branded;
					$total += $final->six_array->branded;
				}

				// $data = [(int)$final->six_array->direct,(int)$final->six_array->discovery,(int)$final->six_array->branded];
				// $total = $final->six_array->direct+$final->six_array->discovery+$final->six_array->branded;
			}

			if($duration == 9){
				if(isset($final->nine_array->direct)){
					$data[] = (int)$final->nine_array->direct;
					$total += $final->nine_array->direct;
				}
				if(isset($final->nine_array->discovery)){
					$data[] = (int)$final->nine_array->discovery;
					$total += $final->nine_array->discovery;
				}
				if(isset($final->nine_array->branded)){
					$data[] = (int)$final->nine_array->branded;
					$total += $final->nine_array->branded;
				}
				// $data = [(int)$final->nine_array->direct,(int)$final->nine_array->discovery,(int)$final->nine_array->branded];
				// $total = $final->nine_array->direct+$final->nine_array->discovery+$final->nine_array->branded;
			}

			if($duration == 12){
				if(isset($final->year_array->direct)){
					$data[] = (int)$final->year_array->direct;
					$total += $final->year_array->direct;
				}
				if(isset($final->year_array->discovery)){
					$data[] = (int)$final->year_array->discovery;
					$total += $final->year_array->discovery;
				}
				if(isset($final->year_array->branded)){
					$data[] = (int)$final->year_array->branded;
					$total += $final->year_array->branded;
				}

				// $data = [(int)$final->year_array->direct,(int)$final->year_array->discovery,(int)$final->year_array->branded];
				// $total = $final->year_array->direct+$final->year_array->discovery+$final->year_array->branded;
			}

			$res['data'] = $data;
			$res['total'] = $total;
			$res['status'] = 1;
		}

		return response()->json($res);
		

	}

	public function ajax_get_direction_requests(Request $request){
		$campaignId = $request['campaignId'];
		if (!file_exists(env('FILE_PATH')."public/gmb/".$campaignId)) {
			$res['status'] = 0;
		} else {

			$url = env('FILE_PATH')."public/gmb/".$campaignId.'/direction_requests.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);
			
			if($request->key <> null && $request->value <> null){
				$duration = $request->value;
			}else{
				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'gmb_direction_requests');
				if(!empty($sessionHistoryRange)){
					$duration = $sessionHistoryRange->duration;
				}else{
					$duration = 30;
				}
			}
			$html =$map = ''; 

			if($duration == 7){
				if(!empty($final->seven_array->label)){
					foreach($final->seven_array->label as $key=>$value){
						$html .='<article><h5>'.$value.'</h5><p>'.$final->seven_array->count[$key].'</p></article>';
						$map ='<iframe src="https://maps.google.com/maps?q='.$final->seven_array->lat[$key].', '.$final->seven_array->long[$key].'&z=15&output=embed&key=AIzaSyAyfg5RfXsDreSKWq7-P-VjfW7d2-abe8c" width="360" height="270" frameborder="0" style="border:0"></iframe>';
					}
				}else{
					$html .='<article>Not enough data for selected time period</article>';
				}
			}

			if($duration == 30){
				if(!empty($final->thirty_array->label)){
					foreach($final->thirty_array->label as $key=>$value){
						$html .='<article><h5>'.$value.'</h5><p>'.$final->thirty_array->count[$key].'</p></article>';
						$map ='<iframe src="https://maps.google.com/maps?q='.$final->thirty_array->lat[$key].', '.$final->thirty_array->long[$key].'&z=15&output=embed&key=AIzaSyAyfg5RfXsDreSKWq7-P-VjfW7d2-abe8c" width="360" height="270" frameborder="0" style="border:0"></iframe>';
					}
				}else{
					$html .='<article>Not enough data for selected time period</article>';
				}
			}

			if($duration == 90){
				if(!empty($final->ninety_array->label)){
					foreach($final->ninety_array->label as $key=>$value){
						$html .='<article><h5>'.$value.'</h5><p>'.$final->ninety_array->count[$key].'</p></article>';
						$map ='<iframe src="https://maps.google.com/maps?q='.$final->ninety_array->lat[$key].', '.$final->ninety_array->long[$key].'&z=15&output=embed&key=AIzaSyAyfg5RfXsDreSKWq7-P-VjfW7d2-abe8c" width="360" height="270" frameborder="0" style="border:0"></iframe>';
					}
				}else{
					$html .='<article>Not enough data for selected time period</article>';
				}
			}
			

			$res['html'] = $html;
			$res['map'] = $map;
			$res['status'] = 1;
		}

		return response()->json($res);
	}

	public function ajax_get_phone_calls(Request $request){
		$campaignId = $request['campaignId'];
		if (!file_exists(env('FILE_PATH')."public/gmb/".$campaignId)) {
			$res['status'] = 0;
		} else {
			$url = env('FILE_PATH')."public/gmb/".$campaignId.'/phone_calls.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);
			
			if($request->key <> null && $request->value <> null){
				$duration = $request->value;
			}else{
				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'gmb_phone_calls');
				if(!empty($sessionHistoryRange)){
					$duration = $sessionHistoryRange->duration;
				}else{
					$duration = 3;
				}
			}

			$value = $labels = $sort = array();	
			$total_calls = 0;

			if($duration == 7){ //one week
				if(isset($final->week_array->labels)){
					$labels = array_values((array)$final->week_array->labels);
					$sort = $this->sortWeekDay($labels);
					foreach($sort as $label){
						$value[] = $final->week_array->value->$label;
					}
				}
			}	

			if($duration == 1){ //one month
				if(isset($final->month_array->labels)){
					$labels = array_values((array)$final->month_array->labels);
					$sort = $this->sortWeekDay($labels);
					foreach($sort as $label){
						$value[] = $final->month_array->value->$label;
					}
				}
			}
			
			if($duration == 3){ //three months
				if(isset($final->three_array->labels)){
					$labels = array_values((array)$final->three_array->labels);
					$sort = $this->sortWeekDay($labels);
					foreach($sort as $label){
						$value[] = $final->three_array->value->$label;
					}
				}
			}
			
			if($duration == 6){ //six months
				if(isset($final->six_array->labels)){
					$labels = array_values((array)$final->six_array->labels);
					$sort = $this->sortWeekDay($labels);
					foreach($sort as $label){
						$value[] = $final->six_array->value->$label;
					}
				}
			}
			
			if($duration == 9){ //nine months
				if(isset($final->nine_array->labels)){
					$labels = array_values((array)$final->nine_array->labels);
					$sort = $this->sortWeekDay($labels);
					foreach($sort as $label){
						$value[] = $final->nine_array->value->$label;
					}
				}
			}
			
			if($duration == 12){ //nine months
				if(isset($final->year_array->labels)){
					$labels = array_values((array)$final->year_array->labels);
					$sort = $this->sortWeekDay($labels);
					foreach($sort as $label){
						$value[] = $final->year_array->value->$label;
					}
				}
			}
			$total_calls = array_sum($value);
			
			$res['labels'] = $sort;
			$res['value'] = $value;
			$res['total_calls'] = $total_calls;
			$res['status'] = 1;
		}

		return response()->json($res);
	}
	
	private function sortWeekDay($days){
		$daysOfWeek = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
		$daysAux = array();
		foreach($days as $k=>$v) {
			$key = array_search($v, $daysOfWeek);
			if($key !== FALSE) {
				$daysAux[$key] = $v;
			}
		}
		
		ksort($daysAux);
		$days = $daysAux;
		return $days;
	}

	public function ajax_get_photo_quantity(Request $request){
		$campaignId = $request['campaignId'];
		if (!file_exists(env('FILE_PATH')."public/gmb/".$campaignId)) {
			$res['status'] = 0;
		} else {
			$url = env('FILE_PATH')."public/gmb/".$campaignId.'/photo_quantity.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);
			
			$owner_photos[] = 	$final->photo_count_merchant->value;
			$customer_photos[] = 	$final->photo_count_customers->value;
			
			$res['owner_photos'] = $owner_photos;
			$res['customer_photos'] = $customer_photos;
			$res['status'] = 1;
		}

		return response()->json($res);
	}

	public function ajax_get_gmb_reviews(Request $request){
		$campaignId = $request['campaignId'];
		$html = '';
		if (!file_exists(env('FILE_PATH')."public/gmb/".$campaignId)) {
			$res['status'] = 0;
			$res['message'] = 'No data.';
			return response()->json($res);
		} else {
			$url = env('FILE_PATH')."public/gmb/".$campaignId.'/reviews.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);
			$totalReviewCount = $final->totalReviewCount;
			if($totalReviewCount > 0){
				$newCollection = collect($final->review);
			}else{
				$result = array();
				$newCollection = collect($result);				
			}

			$page = request()->has('page') ? request('page') : 1;

   			 // Set default per page
			$perPage = request()->has('per_page') ? request('per_page') : 4;

   			 // Offset required to take the results
			$offset = ($page * $perPage) - $perPage;
			$results =  new LengthAwarePaginator(
				$newCollection->slice($offset, $perPage),
				$newCollection->count(),
				$perPage,
				$page
			);

			return view('vendor.gmb_sections.reviews.article', compact('results'))->render();
			
		}

	}


	public function ajax_get_gmb_pdf_reviews(Request $request){
		$campaignId = $request['campaignId'];
		$html = '';
		if (!file_exists(env('FILE_PATH')."public/gmb/".$campaignId)) {
			$res['status'] = 0;
			$res['message'] = 'No data.';
			return response()->json($res);
		} else {
			$url = env('FILE_PATH')."public/gmb/".$campaignId.'/reviews.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);
			$totalReviewCount = $final->totalReviewCount;
			if($totalReviewCount > 0){
				$newCollection = collect($final->review);
			}else{
				$result = array();
				$newCollection = collect($result);				
			}

			$page = request()->has('page') ? request('page') : 1;

   			 // Set default per page
			$perPage = request()->has('per_page') ? request('per_page') : 8;

   			 // Offset required to take the results
			$offset = ($page * $perPage) - $perPage;
			$results =  new LengthAwarePaginator(
				$newCollection->slice($offset, $perPage),
				$newCollection->count(),
				$perPage,
				$page
			);

			return view('viewkey.pdf.gmb_sections.reviews.article', compact('results'))->render();
			
		}

	}	

	public function ajax_get_gmb_media(Request $request){
		$campaignId = $request['campaignId'];
		$html = '';
		if (!file_exists(env('FILE_PATH')."public/gmb/".$campaignId)) {
			$res['status'] = 0;
			$res['message'] = 'No data.';
			return response()->json($res);
		} else {
			$url = env('FILE_PATH')."public/gmb/".$campaignId.'/media.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);
			
			return view('vendor.gmb_sections.media.images', compact('final'))->render();
		}

	}

	public function ajax_get_gmb_reviews_pagination(Request $request){
		$campaignId = $request['campaignId'];
		$html = '';
		if (!file_exists(env('FILE_PATH')."public/gmb/".$campaignId)) {
			$res['status'] = 0;
			$res['message'] = 'No data.';
			return response()->json($res);
		} else {
			$url = env('FILE_PATH')."public/gmb/".$campaignId.'/reviews.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);
			$totalReviewCount = $final->totalReviewCount;
			if($totalReviewCount > 0){
				$newCollection = collect($final->review);
			}else{
				$result = array();
				$newCollection = collect($result);				
			}

			$page = request()->has('page') ? request('page') : 1;

   			 // Set default per page
			$perPage = request()->has('per_page') ? request('per_page') : 4;

   			 // Offset required to take the results
			$offset = ($page * $perPage) - $perPage;
			$results =  new LengthAwarePaginator(
				$newCollection->slice($offset, $perPage),
				$newCollection->count(),
				$perPage,
				$page
			);

			return view('vendor.gmb_sections.reviews.pagination', compact('results'))->render();
			
		}

	}

	public function ajax_gmb_date_range(Request $request){
		$campaignId = $request->campaignId;
		$value = $request->value;
		$module = $request->module;
		if(Auth::user() <> null){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$role_id = User::get_user_role(Auth::user()->id);
		}else{
			$getUser = SemrushUserAccount::where('id',$campaignId)->first();
			$user_id = $getUser->user_id;
			$role_id = User::get_user_role($getUser->user_id);
		}	
		$state = ($request->has('key'))?'viewkey':'user';

		if($role_id != 4 && $state == 'user'){

			$ifCheck = ModuleByDateRange::where('request_id',$campaignId)->where('module',$module)->first();
			if(empty($ifCheck)){
				
				ModuleByDateRange::create([
					'user_id'=>$user_id,
					'request_id'=>$campaignId,
					'duration'=>$value,
					'module'=>$module
				]);
			}else{
				
				ModuleByDateRange::where('id',$ifCheck->id)->update([
					'user_id'=>$user_id,
					'request_id'=>$campaignId,
					'duration'=>$value,
					'module'=>$module
				]);
			}
			$res['status'] = 1;
		}else{
			$res['status'] = 0;
		}

		if($state == 'viewkey'){
			$res['status'] = 1;
		}
		return response()->json($res);
	}


	public function log_gmb_data(Request $request){
		$campaign_id = $request->campaign_id;
	//	try{
		$gtUser = SemrushUserAccount::whereNotNull('gmb_analaytics_id')->whereNotNull('gmb_id')->where('id', $campaign_id)->where('status',0)->first();

		if (!empty($gtUser))
		{
			$start_date = date('Y-m-d\TH:i:s\.000000000\Z',strtotime("-1 day"));
			$end_date =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-1 year", strtotime(date('Y-m-d'))));
			$gmbLocation =     GmbLocation::where('id',$gtUser->gmb_id)->first();
			$getAnalytics =     GoogleAnalyticsUsers::where('id',$gmbLocation->google_account_id)->first();

			$client = GoogleAnalyticsUsers::googleGmbClientAuth($getAnalytics);
			$refresh_token  = $getAnalytics->google_refresh_token;
			/*if refresh token expires*/
			if ($client->isAccessTokenExpired()) {
				GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
			}

			/* Where customers view your business on Google */
			if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
				mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
				$this->get_customer_view($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
			}else{
				$this->get_customer_view($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
			}

			/* Customer actions */
			if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
				mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
				$this->get_customer_action($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
			}else{
				$this->get_customer_action($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
			}


			/* Photo Views */
			if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
				mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
				$this->get_photo_views($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
			}else{
				$this->get_photo_views($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
			}

			/* Customer Search */
			if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
				mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
				$this->get_customer_search($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
			} else{
				$this->get_customer_search($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
			}   



			/* Phone calls */
			if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
				mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
				$this->get_phone_calls($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
			} else{
				$this->get_phone_calls($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
			} 

			/* Reviews */
			if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
				mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
				$this->get_reviews($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
			}else{
				$this->get_reviews($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
			}

			/*media*/
			if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
				mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
				$this->get_location_media($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
			}else{
				$this->get_location_media($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
			}

			$ifErrorExists = Error::removeExisitingError(4,$campaign_id);
			if(!empty($ifErrorExists)){
				Error::where('id',$ifErrorExists->id)->delete();
			}

			GoogleUpdate::updateTiming($campaign_id,'gmb','gmb_type','2');

			/* Direction Requests */
			if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
				mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
				$this->get_direction_requests($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
			} else{
				$this->get_direction_requests($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
			}  

		}    
		// }catch(\Exception $e){
		// 	$error = json_decode($e->getMessage() , true);
		// 	$result['status'] = 0;
		// 	$result['message'] = $error;
		// 	return $result;
		// }
	}


	private function get_customer_view($client,$account_id,$location_id,$start_date,$end_date,$campaign_id){

		$search = $maps = $dates= $convrted_dates = $customer_view_array =  array();
		$metrixGraphViewMap = $this->getLocationMetrixViewMap($client,$account_id,$location_id,$start_date,$end_date);

		

		if(count($metrixGraphViewMap['search']) > 0){
			$search = $metrixGraphViewMap['search']['value'];
			$dates = $metrixGraphViewMap['search']['labels'];
			$convrted_dates  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $dates);
		}
		if(count($metrixGraphViewMap['maps']) > 0){
			$maps = $metrixGraphViewMap['maps']['value'];
		}

		$customer_view_array = array(
			'search' =>$search,
			'maps'=>$maps,
			'dates'=>$dates,
			'convrted_dates'=>$convrted_dates
		);

		if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
			mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_view_graph.json', print_r(json_encode($customer_view_array,true),true));
		}else{
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_view_graph.json', print_r(json_encode($customer_view_array,true),true));
		}

		
	}

	private function get_customer_action($client,$account_id,$location_id,$start_date,$end_date,$campaign_id){
		
		$website = $directions = $phone = $dates= $convrted_dates = $customer_action_array =  array();
		$metrixGraphAction = $this->getLocationMetrixCustomerActions($client,$account_id,$location_id,$start_date,$end_date);
		if(count($metrixGraphAction['website']) > 0){
			$dates = $metrixGraphAction['website']['labels'];
			$website = $metrixGraphAction['website']['value'];
			$convrted_dates  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $dates);
		}
		
		if(count($metrixGraphAction['directions']) > 0){
			$directions = $metrixGraphAction['directions']['value'];
		}
		if(count($metrixGraphAction['phone']) > 0){
			$phone = $metrixGraphAction['phone']['value'];
		}
		  

		$customer_action_array = array(
			'website' =>$website,	
			'directions'=>$directions,
			'phone'=>$phone,
			'dates'=>$dates,
			'convrted_dates'=>$convrted_dates
		);

		if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
			mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_action_graph.json', print_r(json_encode($customer_action_array,true),true));
		}else{
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_action_graph.json', print_r(json_encode($customer_action_array,true),true));
		}

		
	}

	private function get_photo_views($client,$account_id,$location_id,$start_date,$end_date,$campaign_id){
		$you_total = $value = $dates= $convrted_dates = $photo_views_array =  array();
		$metrixGraphAction = $this->getLocationMetrixPhotoViews($client,$account_id,$location_id,$start_date,$end_date);

		if(count($metrixGraphAction['you']) > 0){
			$dates = $metrixGraphAction['you']['labels'];
			$value = $metrixGraphAction['you']['value'];
			$convrted_dates  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $dates);
			$you_total = $metrixGraphAction['you_total'];
		}	
	
		$photo_views_array = array(
			'you_total' =>$you_total,
			'value' =>$value,
			'dates'=>$dates,
			'convrted_dates'=>$convrted_dates
		);

		if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
			mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_views.json', print_r(json_encode($photo_views_array,true),true));
		}else{
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_views.json', print_r(json_encode($photo_views_array,true),true));
		}

	}

	private function get_customer_search($client,$account_id,$location_id,$campaign_id){

		$start_date = date('Y-m-d\TH:i:s\.000000000\Z');
		$one_month =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-1 month"));
		$three_month = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-3 month'));
		$six_month = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-6 month'));
		$nine_month = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-9 month'));
		$one_year = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-1 year'));

		$month_array = $three_array = $six_array= $nine_array = $year_array = $final_array =  array();

		/*one month*/
		$metrixGraphAction_one = $this->getLocationMetrix($client,$account_id,$location_id,$start_date,$one_month);
		if(count($metrixGraphAction_one) > 0){
			$month_array = array(
				'direct'=>$metrixGraphAction_one['QUERIES_DIRECT']['value'],
				'discovery'=>$metrixGraphAction_one['QUERIES_INDIRECT']['value'],
				'branded'=>$metrixGraphAction_one['QUERIES_CHAIN']['value']
			);
		}
		/*three month*/
		$metrixGraphAction_three = $this->getLocationMetrix($client,$account_id,$location_id,$start_date,$three_month);
		if(count($metrixGraphAction_three) > 0){
			$three_array = array(
				'direct'=>$metrixGraphAction_three['QUERIES_DIRECT']['value'],
				'discovery'=>$metrixGraphAction_three['QUERIES_INDIRECT']['value'],
				'branded'=>$metrixGraphAction_three['QUERIES_CHAIN']['value']
			);
		}
		/*six month*/
		$metrixGraphAction_six = $this->getLocationMetrix($client,$account_id,$location_id,$start_date,$six_month);
		if(count($metrixGraphAction_six) > 0){
			$six_array = array(
				'direct'=>$metrixGraphAction_six['QUERIES_DIRECT']['value'],
				'discovery'=>$metrixGraphAction_six['QUERIES_INDIRECT']['value'],
				'branded'=>$metrixGraphAction_six['QUERIES_CHAIN']['value']
			);
		}
		/*nine month*/
		$metrixGraphAction_nine = $this->getLocationMetrix($client,$account_id,$location_id,$start_date,$nine_month);
		if(count($metrixGraphAction_nine) > 0){
			$nine_array = array(
				'direct'=>$metrixGraphAction_nine['QUERIES_DIRECT']['value'],
				'discovery'=>$metrixGraphAction_nine['QUERIES_INDIRECT']['value'],
				'branded'=>$metrixGraphAction_nine['QUERIES_CHAIN']['value']
			);
		}
		/*one year*/
		$metrixGraphAction_year = $this->getLocationMetrix($client,$account_id,$location_id,$start_date,$one_year);
		if(count($metrixGraphAction_year) > 0){
			$year_array = array(
				'direct'=>$metrixGraphAction_year['QUERIES_DIRECT']['value'],
				'discovery'=>$metrixGraphAction_year['QUERIES_INDIRECT']['value'],
				'branded'=>$metrixGraphAction_year['QUERIES_CHAIN']['value']
			);
		}

		$final_array = array(
			'month_array'=>$month_array,
			'three_array'=>$three_array,
			'six_array'=>$six_array,
			'nine_array'=>$nine_array,
			'year_array'=>$year_array
		);


		if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
			mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_search.json', print_r(json_encode($final_array,true),true));
		}else{
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_search.json', print_r(json_encode($final_array,true),true));
		}

		
		
	}

	private function get_direction_requests($client,$account_id,$location_id,$campaign_id){
		/*weekly data*/
		$getDirectionRequests_seven = $this->getDirectionRequests($client,$account_id,$location_id,"SEVEN");
		if(isset($getDirectionRequests_seven) && !empty($getDirectionRequests_seven)){
			foreach($getDirectionRequests_seven['data'] as $key=>$value_seven){
				$label_seven[] = $value_seven['label'];
				$count_seven[] = $value_seven['count'];
				$lat_seven[] = $value_seven['latlng']['latitude'];
				$long_seven[] = $value_seven['latlng']['longitude'];
			}
			$seven_array = array(
				'dayCount'=>$getDirectionRequests_seven['dayCount'],
				'label'=>$label_seven,
				'count'=>$count_seven,
				'lat'=>$lat_seven,
				'long'=>$long_seven
			);
		}else{
			$seven_array = array(
				'dayCount'=>'',
				'label'=>array(),
				'count'=>array(),
				'lat'=>array(),
				'long'=>array()
			);
		}

		/*thirty data*/
		$getDirectionRequests_thirty = $this->getDirectionRequests($client,$account_id,$location_id,"THIRTY");
		if(isset($getDirectionRequests_thirty) && !empty($getDirectionRequests_thirty)){
			foreach($getDirectionRequests_thirty['data'] as $key=>$value){
				$label[] = $value['label'];
				$count[] = $value['count'];
				$lat[] = $value['latlng']['latitude'];
				$long[] = $value['latlng']['longitude'];
			}
			$thirty_array = array(
				'dayCount'=>$getDirectionRequests_thirty['dayCount'],
				'label'=>$label,
				'count'=>$count,
				'lat'=>$lat,
				'long'=>$long
			);
		}else{
			$thirty_array = array(
				'dayCount'=>'',
				'label'=>array(),
				'count'=>array(),
				'lat'=>array(),
				'long'=>array()
			);
		}

		/*ninety data*/
		$getDirectionRequests_ninety = $this->getDirectionRequests($client,$account_id,$location_id,"NINETY");

		if(isset($getDirectionRequests_ninety) && !empty($getDirectionRequests_ninety)){
			foreach($getDirectionRequests_ninety['data'] as $key=>$value_nine){
				
				$label_nine[] = $value_nine['label'];
				$count_nine[] = $value_nine['count'];
				$lat_nine[] = $value_nine['latlng']['latitude'];
				$long_nine[] = $value_nine['latlng']['longitude'];
			}
			$ninety_array = array(
				'dayCount'=>$getDirectionRequests_ninety['dayCount'],
				'label'=>$label_nine,
				'count'=>$count_nine,
				'lat'=>$lat_nine,
				'long'=>$long_nine
			);
		}else{
			$ninety_array = array(
				'dayCount'=>'',
				'label'=>array(),
				'count'=>array(),
				'lat'=>array(),
				'long'=>array()
			);
		}

		$final_array = array(
			'seven_array'=>$seven_array,
			'thirty_array'=>$thirty_array,
			'ninety_array'=>$ninety_array
		);

		$seven_label_array = $final_array['seven_array']['label'];
		$thirty_label_array = $final_array['thirty_array']['label'];
		$ninety_label_array = $final_array['ninety_array']['label'];
		$merged_zipCodes = array_values(array_unique(array_merge($seven_label_array,$thirty_label_array,$ninety_label_array)));

		if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
			mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/direction_requests.json', print_r(json_encode($final_array,true),true));
		}else{
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/direction_requests.json', print_r(json_encode($final_array,true),true));
		}
		$maps = $this->direction_request($merged_zipCodes,$campaign_id,$seven_label_array,$thirty_label_array,$ninety_label_array);

		$seven_array = $thirty_array = $ninety_array = $final_array =  array();
	}

	private function get_phone_calls($client,$account_id,$location_id,$campaign_id){
		/*weekly data*/
		$start_date = date('Y-m-d\TH:i:s\.000000000\Z');
		
		$week_array = $month_array = $three_array = $six_array = $nine_array = $year_array = $phone_array = array();

		$weekly =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-1 week"));
		$getDirectionRequests_seven = $this->getPhoneCalls($client,$account_id,$location_id,$start_date,$weekly);
		if(isset($getDirectionRequests_seven['labels'])){
			$week_array = array(
				'labels'=>$getDirectionRequests_seven['labels'],
				'value'=>$getDirectionRequests_seven['value']
			);
		}
		
		
		$one_month =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-1 month"));
		$getDirectionRequests_month = $this->getPhoneCalls($client,$account_id,$location_id,$start_date,$one_month);
		if(isset($getDirectionRequests_month['labels'])){
			$month_array = array(
				'labels'=>$getDirectionRequests_month['labels'],
				'value'=>$getDirectionRequests_month['value']
			);
		}

		
		
		$three_month = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-3 month'));
		$getDirectionRequests_three = $this->getPhoneCalls($client,$account_id,$location_id,$start_date,$three_month);
		if(isset($getDirectionRequests_three['labels'])){
			$three_array = array(
				'labels'=>$getDirectionRequests_three['labels'],
				'value'=>$getDirectionRequests_three['value']
			);
		}
		$six_month = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-6 month'));
		$getDirectionRequests_six = $this->getPhoneCalls($client,$account_id,$location_id,$start_date,$six_month);
		if(isset($getDirectionRequests_six['labels'])){
			$six_array = array(
				'labels'=>$getDirectionRequests_six['labels'],
				'value'=>$getDirectionRequests_six['value']
			);
		}
		$nine_month = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-9 month'));
		$getDirectionRequests_nine = $this->getPhoneCalls($client,$account_id,$location_id,$start_date,$nine_month);
		if(isset($getDirectionRequests_nine['labels'])){
			$nine_array = array(
				'labels'=>$getDirectionRequests_nine['labels'],
				'value'=>$getDirectionRequests_nine['value']
			);
		}

		$one_year = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-1 year'));
		$getDirectionRequests_year = $this->getPhoneCalls($client,$account_id,$location_id,$start_date,$one_year);
		if(isset($getDirectionRequests_year['labels'])){
			$year_array = array(
				'labels'=>$getDirectionRequests_year['labels'],
				'value'=>$getDirectionRequests_year['value']
			);
		}

		$phone_array  =  array(
			'week_array'=>$week_array,
			'month_array'=>$month_array,
			'three_array'=>$three_array,
			'six_array'=>$six_array,
			'nine_array'=>$nine_array,
			'year_array'=>$year_array,
		);

		if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
			mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/phone_calls.json', print_r(json_encode($phone_array,true),true));
		}else{
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/phone_calls.json', print_r(json_encode($phone_array,true),true));
		}

		
		
	}

	
	private function get_phone_quantity($client,$account_id,$location_id,$campaign_id){
		$start_date = date('Y-m-d\TH:i:s\.000000000\Z');
		$end_date =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-2 day", strtotime(date('Y-m-d'))));
		$data = array();
		
		$data = $this->getPhoneQuantity($client,$account_id,$location_id,$start_date,$end_date);

		if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
			mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_quantity.json', print_r(json_encode($data,true),true));
		}else{
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_quantity.json', print_r(json_encode($data,true),true));
		}

		$data = array();
	}
	
	private function get_reviews($client,$account_id,$location_id,$campaign_id){
		$getLocationReviews = array();
		
		$getLocationReviews = $this->getLocationReviews($client,$account_id,$location_id);

		if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
			mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/reviews.json', print_r(json_encode($getLocationReviews),true));
		}else{
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/reviews.json', print_r(json_encode($getLocationReviews,true),true));
		}

		$getLocationReviews = array();
	}

	private function get_location_media($client,$account_id,$location_id,$campaign_id){
		$getMedia = array();
		
		$getMedia = $this->getLocationMedia($client,$account_id,$location_id);

		if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
			mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/media.json', print_r(json_encode($getMedia),true));
		}else{
			file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/media.json', print_r(json_encode($getMedia,true),true));
		}

		$getMedia = array();
	}
	

	/*29 april*/
	public function ajax_refresh_gmb_acccount_list(Request $request){
		$response = array();
		$campaign_id = $request->campaign_id;
		$email_id = $request->email;
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		
		$checkIfExists = GoogleAnalyticsUsers::where('id',$email_id)->where('user_id',$user_id)->where('oauth_provider','gmb')->first();


		if($campaign_id <>null){
			if(!empty($checkIfExists)){
				$client = GmbLocation::client_auth($checkIfExists);
				$refresh_token = $checkIfExists->google_refresh_token;
				$access_token = $checkIfExists->google_access_token;

				if ($client->isAccessTokenExpired()){
					GmbLocation::google_refresh_token($client,$refresh_token,$checkIfExists->id);
				}

				$sessionData = Session::all();
				$getUserDetails = SemrushUserAccount::findorfail($campaign_id);


				$mybusinessService = new \Google_Service_MyBusiness($client);
				
				$data = $this->updateLocation_refresh($client,$getUserDetails,$refresh_token,$checkIfExists);


				if($data['status'] == 1){
					$response['status'] = 1;
					$response['message'] = 'Last fetched now';
				}
				if($data['status'] == 0){
					$response['status'] = 0;
					$response['message'] = $data['message'];
				}
			}else{
				$response['status'] = 2;
				$response['message'] = 'Error: Please try again.';
			}
		} else{
			$response['status'] = 2;
			$response['message'] = 'Error: Missing campaign id.';
		}

		return response()->json($response);
	}




	private function checkGmbData($campaignID,$user_id,$google_email,$analytics_account_id){
		$result = array();
		$getAnalytics =     GoogleAnalyticsUsers::where('id',$google_email)->first();
		if($getAnalytics){

			$gmbLocation =     GmbLocation::where('id',$analytics_account_id)->first();

			$start_date = date('Y-m-d\TH:i:s\.000000000\Z',strtotime("-1 day"));
			$end_date =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-1 week", strtotime(date('Y-m-d'))));

			$client = GoogleAnalyticsUsers::googleGmbClientAuth($getAnalytics);
			$refresh_token  = $getAnalytics->google_refresh_token;
			/*if refresh token expires*/
			if ($client->isAccessTokenExpired()) {
				GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
			}
			$error = array();
			try {
				$metrixGraphViewMap = $this->getLocationMetrixViewMap($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date);
				
				$search = $metrixGraphViewMap['search'];
				$result['status'] = 1;
				$result['message'] = $search;
			} catch(\Exception $j) {
				// echo "<pre/>"; print_r($j); die;
				$error = json_decode($j->getMessage(), true);
				$result['status'] = 0;
				$result['message'] = $error['error'];
			}
			return $result;
		}	
	}

	public function ajax_get_latest_gmb(Request $request){
		$response = array();
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		$get_gmb = SemrushUserAccount::select('gmb_id','gmb_analaytics_id')->where('id',$request->campaign_id)->first();
		

		if(isset($get_gmb) && !empty($get_gmb)){
			$check = $this->checkGmbData($request->campaign_id,$user_id,$get_gmb->gmb_analaytics_id,$get_gmb->gmb_id);

			if(isset($check['status'])  && ($check['status'] == 0)){

				// $response['status'] = 'google-error'; 
				// if(!empty($check['message']['error']['code'])){
				// 	if(isset($check['message']['error']['message'])){
				// 		$response['message'] =$check['message']['error']['message'];
				// 	}else{
				// 		$response['message'] = $check['message']['errors'][0]['message'];
				// 	}
				// }elseif(!empty($check['message']['message'])){
				// 	$response['message'] = $check['message']['message'];
				// }else{
				// 	$response['message'] = $check['message'];
				// }


				Error::updateOrCreate(
					['request_id' => $request->campaign_id,'module'=> 4],
					['response'=> json_encode($check),'request_id' => $request->campaign_id,'module'=> 4]
				);
				$response = SemrushUserAccount::display_google_errorMessages(4,$request->campaign_id);
				return response()->json($response);
			}else{
				$location_data = GmbLocation::where('id',$get_gmb->gmb_id)->where('google_account_id',$get_gmb->gmb_analaytics_id)->first();
				if($location_data->location_lat == '' || $location_data->location_lng == ''){
					// $location = $this->get_location_details($location_data->location_name);
					// GmbLocation::where('id',$location_data->id)->update([
					// 	'location_lat'=>$location['lat'],
					// 	'location_lng'=>$location['lng']
					// ]);
				}

				$log_data = GMBController::log_googleMyBusiness_data($request->campaign_id);
				if(isset($log_data['status']) && $log_data['status'] == 0){
					$response['status'] = 'error';
					$response['message'] = $log_data['message'];
				}else{
					GoogleUpdate::updateTiming($request->campaign_id,'gmb','gmb_type','2');
					$ifErrorExists = Error::removeExisitingError(4,$request->campaign_id);
					if(!empty($ifErrorExists)){
						Error::where('id',$ifErrorExists->id)->delete();
					}
					$response['status'] = 'success';
				}
			}
			return response()->json($response);
		}
	}


	private function log_googleMyBusiness_data($campaign_id){
		$error = array();
		try{
			$gtUser = SemrushUserAccount::whereNotNull('gmb_analaytics_id')->whereNotNull('gmb_id')->where('id', $campaign_id)->where('status',0)->first();

			if (!empty($gtUser))
			{
				$start_date = date('Y-m-d\TH:i:s\.000000000\Z',strtotime("-1 day"));
				$end_date =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-1 year", strtotime(date('Y-m-d'))));
				$gmbLocation =     GmbLocation::where('id',$gtUser->gmb_id)->first();
				$getAnalytics =     GoogleAnalyticsUsers::where('id',$gmbLocation->google_account_id)->first();

				$client = GoogleAnalyticsUsers::googleGmbClientAuth($getAnalytics);
				$refresh_token  = $getAnalytics->google_refresh_token;
				/*if refresh token expires*/
				if ($client->isAccessTokenExpired()) {
					GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
				}

				/* Where customers view your business on Google */
				if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
					mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
					$this->get_customer_view($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
				}else{
					$this->get_customer_view($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
				}

				/* Customer actions */
				if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
					mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
					$this->get_customer_action($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
				}else{
					$this->get_customer_action($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
				}


				/* Photo Views */
				if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
					mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
					$this->get_photo_views($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
				}else{
					$this->get_photo_views($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
				}

				/* Customer Search */
				if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
					mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
					$this->get_customer_search($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
				} else{
					$this->get_customer_search($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
				}   

				/* Phone calls */
				if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
					mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
					$this->get_phone_calls($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
				} else{
					$this->get_phone_calls($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
				} 

				/* Reviews */
				if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
					mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
					$this->get_reviews($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
				}else{
					$this->get_reviews($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
				}

				/*media*/
				if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
					mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
					$this->get_location_media($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
				}else{
					$this->get_location_media($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
				}

				/* Direction Requests */
				if (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
					mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
					$this->get_direction_requests($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
				} else{
					$this->get_direction_requests($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
				}   

			}    
		}catch(\Exception $e){
			$error = json_decode($e->getMessage() , true);
			$result['status'] = 0;
			$result['message'] = $error;
			return $result;
		}
	}

	public function direction_request ($zip_codes,$campaign_id,$seven_labels,$thirty_labels,$ninety_labels){
		$data = $cords = array();

		if(!empty($zip_codes)){		
			for($i=0;$i<count($zip_codes);$i++){
				$data = $this->getGeocodeData($zip_codes[$i]);
				if($data <> null){
					$newArray = array_values($data);	
					if($newArray <> null){
						if(is_array($newArray[0])){
							$coordinates = $newArray[0]['geojson']['coordinates'][0];
							if($coordinates <> null){
								for($j=0;$j<count($coordinates);$j++){
									$cords[$zip_codes[$i]][] = array('lng'=> $coordinates[$j][0],'lat'=> $coordinates[$j][1]);
									file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/map_data.json', print_r(json_encode($cords,true),true));
								}
							}
						}else{
							$coordinates = current(array_filter($newArray, function($value){
								if(isset($value['type']) && $value['type'] == 'Polygon'){
									return $value;
								}
							},ARRAY_FILTER_USE_BOTH));


							if($coordinates <> null){
								for($j=0;$j<count($coordinates['coordinates'][0]);$j++){
									$coords_data = $coordinates['coordinates'][0];
									$cords[$zip_codes[$i]][] = array('lng'=> $coords_data[$j][0],'lat'=> $coords_data[$j][1]);
									file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/map_data.json', print_r(json_encode($cords,true),true));
								}
							}
						}
					}
				}
				
			}
			$this->filter_map_data($campaign_id,$seven_labels,$thirty_labels,$ninety_labels);
		}
	}


	function getGeocodeData($zip_code) { 
		$data = array();
		$address = urlencode($zip_code);    
		$google_api_key = \config('app.GOOGLE_API_KEY');

		$googleMapUrl = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$google_api_key}";
		$geocodeResponseData = file_get_contents($googleMapUrl);
		$responseData = json_decode($geocodeResponseData, true);
		
		if($responseData['status']=='OK') {
			$formattedAddress = isset($responseData['results'][0]['formatted_address']) ? $responseData['results'][0]['formatted_address'] : "";  
			if($formattedAddress) {         
				$data = $this->get_coordinates($formattedAddress);  
				return $data;
			}      
		}
	}

	function get_coordinates($formattedAddress){
		$coordinates = array();
		$opts = array('http'=>array('header'=>"User-Agent: StevesCleverAddressScript 3.7.6\r\n"));
		$context = stream_context_create($opts);
		$address = urlencode($formattedAddress);
		$url = "https://nominatim.openstreetmap.org/search.php?q={$address}&polygon_geojson=1&format=json";
		$geocodeResponseData = file_get_contents($url, false, $context);
		$responseData = json_decode($geocodeResponseData, true);	
		$coordinates = current(array_filter($responseData, function($value){
			if(isset($value['geojson']['type']) && $value['geojson']['type'] == 'Polygon'){
				return $value;
			}
		},ARRAY_FILTER_USE_BOTH));
		if($coordinates == false){
			$coordinates = array();
		}
		return $coordinates;
	}

	function filter_map_data($campaign_id,$seven_labels,$thirty_labels,$ninety_labels){
		$final_filter = $seven_filtered = $thirty_filtered = $ninety_filtered = array();
		$final = file_get_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/map_data.json');

		$responseData = json_decode($final, true);
		if(!empty($responseData)){
			$seven_filtered = array_filter(
				$responseData,
				function ($key) use ($seven_labels) {
					return in_array($key, $seven_labels);
				},
				ARRAY_FILTER_USE_KEY
			);

			$thirty_filtered = array_filter(
				$responseData,
				function ($key) use ($thirty_labels) {
					return in_array($key, $thirty_labels);
				},
				ARRAY_FILTER_USE_KEY
			);

			$ninety_filtered = array_filter(
				$responseData,
				function ($key) use ($ninety_labels) {
					return in_array($key, $ninety_labels);
				},
				ARRAY_FILTER_USE_KEY
			);
		}

		$final_filter = array(
			'seven_array'=>$seven_filtered,
			'thirty_array'=>$thirty_filtered,
			'ninety_array'=>$ninety_filtered
		);
		file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/filtered_map_data.json', print_r(json_encode($final_filter,true),true));
	}


	public function ajax_get_Customer_search_pdf_data(Request $request){
		$campaignId = $request['campaignId'];
		if (!file_exists(env('FILE_PATH')."public/gmb/".$campaignId)) {
			$res['status'] = 0;
		} else {

			$url = env('FILE_PATH')."public/gmb/".$campaignId.'/customer_search.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);

			if($request->key <> null && $request->value <> null){
				$duration = $request->value;
			}else{
				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'gmb_customer_search');
				if(!empty($sessionHistoryRange)){
					$duration = $sessionHistoryRange->duration;
				}else{
					$duration = 3;
				}
			}

			if($duration == 1){
				$direct = $final->month_array->direct;
				$discovery = $final->month_array->discovery;
				$branded = $final->month_array->branded;
			}
			
			if($duration == 3){
				$direct = $final->three_array->direct;
				$discovery = $final->three_array->discovery;
				$branded = $final->three_array->branded;
			}

			if($duration == 6){
				$direct = $final->six_array->direct;
				$discovery = $final->six_array->discovery;
				$branded = $final->six_array->branded;
			}

			if($duration == 9){
				$direct = $final->nine_array->direct;
				$discovery = $final->nine_array->discovery;
				$branded = $final->nine_array->branded;
			}

			if($duration == 12){
				$direct = $final->year_array->direct;
				$discovery = $final->year_array->discovery;
				$branded = $final->year_array->branded;
			}

			$res['data'] = $data;
			$res['direct'] = $direct;
			$res['discovery'] = $discovery;
			$res['branded'] = $branded;
			$res['status'] = 1;
		}

		return response()->json($res);
		

	}

	public function update_location_lat_long(){
		$data = SemrushUserAccount::
		select('id','gmb_analaytics_id','gmb_id')
		->whereNotNull('gmb_analaytics_id')
		->whereNotNull('gmb_id')
		->whereHas('google_myBusiness_account',function($query){
			$query->whereNull('location_lat')->whereNull('location_lng');
		})
		// ->limit(2)
		->orderBy('id','desc')
		->get();
		// echo "<pre>";
		// print_r(count($data));
		// print_r($data);
		// die;
		
		if(!empty($data) && $data <> null){
			foreach($data as $key=>$value){
				$name = $value->google_myBusiness_account->location_name;
				$gmb_id = $value->google_myBusiness_account->id;
				$location = $this->get_location_details($name);
				GmbLocation::where('id',$gmb_id)->update([
					'location_lat'=>$location['lat'],
					'location_lng'=>$location['lng']
				]);
			}
		}			
	}
}