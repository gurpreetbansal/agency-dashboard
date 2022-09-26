<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  Session;
use App\GoogleAnalyticsUsers;
use App\SemrushUserAccount;
use App\SearchConsoleUsers;
use App\SearchConsoleUrl;
use App\User;
use App\Error;

class TestController extends Controller {

	public function connect_search_console(Request $request){
		try{
			$google_redirect_url = \config('app.base_url').'connect_search_console';
			$client = new \Google_Client();
			$client->setAuthConfig(\config('app.FILE_PATH').\config('app.ANALYTICS_CONFIG'));
			$client->setRedirectUri($google_redirect_url);
			$client->addScope(['https://www.googleapis.com/auth/webmasters','https://www.googleapis.com/auth/webmasters.readonly','email','profile']);
			$client->setAccessType("offline");
			$client->setState($request->campaignId.'/'.$request->redirectPage);
			$client->setIncludeGrantedScopes(true);
			$client->setApprovalPrompt('force');


			if ($request->get('code') == NULL) {
				$auth_url = $client->createAuthUrl();
				return redirect()->to($auth_url);
			} else {

				$exploded_value = explode('/',$request->state);
				$campaignId = $exploded_value[0];
				$redirectPage = $exploded_value[1];


				if ($request->get('code')){
					$client->authenticate($request->get('code'));
					$client->refreshToken($request->get('code'));
					Session::put('token', $client->getAccessToken());
					
				}
				if ($request->session()->get('token'))
				{
					$client->setAccessToken($request->session()->get('token'));
				}
				$objOAuthService = new \Google_Service_Oauth2($client);

				if($client->getAccessToken()){
					$getUserDetails = SemrushUserAccount::findorfail($campaignId);
					
					$getLoggedInUser = User::findorfail($getUserDetails->user_id);
					$domainName = $getLoggedInUser->company_name;


					$googleuser = $objOAuthService->userinfo->get();
					$checkIfExists = SearchConsoleUsers::where('user_id',$getUserDetails->user_id)->where('oauth_uid',$googleuser['id'])->first();
					$sessionData = Session::all();



					if(empty($checkIfExists)){
						$insertion = SearchConsoleUsers::create([
							'user_id'=>$getUserDetails->user_id,
							'google_access_token'=> $sessionData['token']['access_token'],
							'google_refresh_token'=>$sessionData['token']['refresh_token'],
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

						if($insertion){
							$getLastInsertedId = $insertion->id;
							$updateSemrush = SemrushUserAccount::where('user_id',$getUserDetails->user_id)->where('id',$campaignId)->update([
								'google_console_id'=>$getLastInsertedId
							]);		
							SearchConsoleUsers::updateRefreshNAccessToken($googleuser['email'],$getUserDetails->user_id,$sessionData['token']);					
						}

						$service = new \Google_Service_Webmasters($client);
						SearchConsoleUrl::get_console_urls($service,$campaignId,$getLastInsertedId,$getUserDetails->user_id);

					}else if(!empty($sessionData['token']['access_token'])){

						$refresh_token 	= isset($sessionData['token']['refresh_token']) ? $sessionData['token']['refresh_token'] : $checkIfExists->google_refresh_token;
						$update = SearchConsoleUsers::where('user_id',$getUserDetails->user_id)->where('oauth_uid',$googleuser['id'])->where('id',$checkIfExists->id)->update([
							'google_access_token'=> $sessionData['token']['access_token'],
							'google_refresh_token'=> $refresh_token,
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
						$service = new \Google_Service_Webmasters($client);
						SearchConsoleUrl::get_console_urls($service,$campaignId,$checkIfExists->id,$getUserDetails->user_id);
					}
				}
			}
			echo  "<script>";
			echo "window.close();";
			echo "</script>";
			return;
			
			
		}catch(Exception $e){
			return $e->getMessage();
		}
	}

}