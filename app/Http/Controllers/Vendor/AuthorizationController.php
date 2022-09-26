<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\SemrushUserAccount;
use App\RegionalDatabse;
use App\GoogleAnalyticsUsers;
use App\CampaignTag;
use URL;
use App\SearchConsoleUsers;
use App\SearchConsoleUrl;
use App\GmbLocation;

class AuthorizationController extends Controller {

	public function index(){
		$user_id = Auth::user()->id;
		$getAccounts = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','google')->get();
		$getAdsAccounts = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','google_ads')->get();
		$getgmbAccounts = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','gmb')->get();
		// $getConsoleAccount = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','search_console')->get();
		$getConsoleAccount = SearchConsoleUsers::where('user_id',$user_id)->get();

		// return view('vendor.authorization.index',['getAccounts'=>$getAccounts,'getAdsAccounts'=>$getAdsAccounts,'getConsoleAccount'=>$getConsoleAccount]);
		return view('vendor.authorization.index', ['getAccounts'=>$getAccounts,'getAdsAccounts'=>$getAdsAccounts,'getConsoleAccount'=>$getConsoleAccount,'getgmbAccounts'=>$getgmbAccounts]);
	}

	public function ajax_auth_campaigns(Request $request){
		$user_id = Auth::user()->id;
		$string = $request['search']["value"];
		$field = ['domain_name','domain_url'];
		$results = SemrushUserAccount::
		where('user_id', $user_id)
		->where('status', 0)
		->where(function ($query) use($string, $field) {
			for ($i = 0; $i < count($field); $i++){
				$query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
			}      
		})
		->skip($request['start'])->take($request['length'])
		->get();


		$searcherArr = RegionalDatabse::get_search_arr();

		$data = array();
		foreach ($results as $key => $result) {
			$manager_name  = $mimage = $tags = $action = '';

			$key = array_search($result->regional_db, array_column($searcherArr, 'value'));

			$searchlocation = explode('.', $searcherArr[$key]['key']);

			if(count($searchlocation) >2){
				$location = '.'.$searchlocation[1].'.'.$searchlocation[2];
			}else{
				$location = '.'.$searchlocation[1];
			}

			$link = URL::asset('/public/flags/'.$result->regional_db.'.png');
			


			

			$data[] = [
				'<a href="/new-dashboard/'.$result->id.'" class="ml-0 mr-1">'. $result->domain_name .'</a>',
				'<a class="consoleAuth" href="javascript:;" data-toggle="modal" data-target="#AuthConnectGoogleSearchConsoleModal" data-id="'.$result->id.'"><img  src="public/vendor/images/gsc-logo.png" /></a>&nbsp;
				<a class="analyticAuth" href="javascript:;" data-toggle="modal" data-target="#AuthConnectGoogleAnalyticsModal" data-id="'.$result->id.'"><img  src="public/vendor/images/google-analytics-logo.png" /></a>&nbsp;
				<a class="adwordsAuth" href="javascript:;" data-toggle="modal" data-target="#AuthConnectGoogleAdsModal" data-id="'.$result->id.'"><img src="public/vendor/images/adwords-logo.png" /></a>&nbsp;
				<a class="Authtags" href="javascript:;" data-toggle="modal" data-target="#Auth_tags" data-id="'.$result->id.'"><i class="fa fa-tags"></i></a>&nbsp;
				 <a class="AuthGmb" href="javascript:;" data-toggle="modal" data-target="#Auth_GMB" data-id="'.$result->id.'"><img src="public/vendor/images/google-my-business-logo.png" /></a>&nbsp;
				'
							];	

		}


		$json_data = array(
			"draw"            => intval( $request['draw'] ),   
			"recordsTotal"    => count($results),  
			"recordsFiltered" => count($results),
			"data"            => $data   
		);

		return response()->json($json_data);
	}

	public function ajax_save_tags(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		if(!empty($request['tags'])){
			SemrushUserAccount::where('id',$request['campId'])->update(['tags'=>$request['tags']]);
			CampaignTag::where('request_id',$request['campId'])->delete();
			$tags = explode(',',$request['tags']);
			foreach ($tags as $key => $value) {
				$update = CampaignTag::create([
					'user_id'=>$user_id,
					'request_id'=>$request['campId'],
					'tag'=>trim($value)
				]);
			}

		}

		if($update){
			$response['status'] = 'success';
		}else{
			$response['status'] = 'error';
		}

		return response()->json($response);
	}

	public function ajax_get_tags(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$tags = CampaignTag::where('request_id',$request['campId'])->where('user_id',$user_id)->pluck('tag')->toArray();
		$final = isset($tags)?implode(',',$tags):'';
		return response()->json($final);
	}

	public function ajax_update_analytics(Request $request){
		
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$analytic_id = $request['analytic_id'];
		$campaignId = $request['request_id'];
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
				//dd($analytics);
				$data = GoogleAnalyticsUsers::getGoogleAccountsList_update($analytics,$campaignId,$analytic_id,$user_id,'google');
				if($data==1){
					$response['status'] = 1;
					$response['message'] = 'Account refreshed successfully!';
				}else{
					$response['status'] = 0;
					$response['message'] = 'Error refreshing account';
				}
				return response()->json($response);
			}


		}else{
			$response['status'] = 0;
			$response['message'] = 'Id not found!';
			return response()->json($response);
		}
	}

	public function ajax_update_search_console(Request $request){
		
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$sc_id = $request['sc_id'];
		$campaignId = $request['request_id'];
		
		$getConsoleAccount = SearchConsoleUsers::where('id',$sc_id)->first();
		if($getConsoleAccount){
			$client = SearchConsoleUsers::ConsoleClientAuth($getConsoleAccount);
			$refresh_token  = $getConsoleAccount->google_refresh_token;


			/*if refresh token expires*/
			if ($client->isAccessTokenExpired()) {
				SearchConsoleUsers::google_refresh_token($client,$refresh_token,$getConsoleAccount->id);
			}


			$service = new \Google_Service_Webmasters($client);
			$data = SearchConsoleUrl::get_console_urls_update($service,$campaignId,$sc_id,$user_id);

			if($data==1){
				$response['status'] = 1;
				$response['message'] = 'Account refreshed successfully!';
			}else{
				$response['status'] = 0;
				$response['message'] = 'Error refreshing account';
			}
			return response()->json($response);
		
		}else{
			$response['status'] = 0;
			$response['message'] = 'Id not found!';
			return response()->json($response);
		}
	}

	public function ajax_google_view_account($domain=null,$account_id =null, $campaignID = null){

		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child

		$getData = GmbLocation::where('user_id',$user_id)->where('google_account_id',$account_id)->get();

		$li	=	'<option value=""><--Select Account--></option>';
		if(!empty($getData)) {
			foreach($getData as $result) {

				$li	.= '<option value="'.$result->id.'">'.$result->location_name.'</option>';
			} 
			
		}else{
			$li	.= '<option value="">No Result Found</option>';
		}
		
		return $li;
	}

	public function ajax_save_console_data (Request $request){

		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		
		$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaignID)->first();
		$acc_id = $if_Exist->console_account_id;
		$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaignID)
		->update([
			'gmb_id'=>$request->console_account,
		]);
		
		if($update) {			
			$response['status'] = 'success';
		}elseif($acc_id == $request->console_account){
			//$this->log_console_data($request->campaignID);
			$response['status'] = 'success';
		} else if(!$update){
			$response['status'] = 'error'; 
		}				
	
		return json_encode($response); 
	}
}