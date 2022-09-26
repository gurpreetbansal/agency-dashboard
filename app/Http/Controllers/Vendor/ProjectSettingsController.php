<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\RegionalDatabse;
use App\SemrushUserAccount;
use App\ProfileInfo;
use App\DashboardType;
use App\User;
use App\CampaignDashboard;
use App\GoogleAnalyticsUsers;
use App\SearchConsoleUsers;
use App\SearchConsoleUrl;
use App\GoogleAdsCustomer;
use App\SeoAnalyticsEditSection;
use App\KeywordPosition;	
use App\GoogleAccountViewData;	
use App\GmbLocation;
use App\GoogleUpdate;
use App\GlobalSetting;
use App\LiveKeywordSetting;
use Auth;
use File;
use App\Error;

use App\CampaignSetting;
use App\Views\ViewKeywordSearch;
use App\KeywordSearch;
use App\KeywordAlert;
use Mail;
use App\Country;
use App\Social\{SocialAccount,FacebookUserPage};
use App\GoogleAnalyticAccount;

class ProjectSettingsController extends Controller {

	public function index($domain_name, $campaign_id){
		$data = GlobalSetting::uploading_changes();
		if($data == true || $data == 1){
			return \View::make('errors.uploading_changes');
		}
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child

		if(\Request::segment(1) !== 'profile-settings'){
			$check = User::check_subscription($user_id); 
			if($check == 'expired'){
				return redirect()->to('/dashboard');
			}
		} 
		$project_detail = SemrushUserAccount::where('id',$campaign_id)->where('user_id',$user_id)->first();
		
		if(isset($project_detail) && !empty($project_detail)){
			if($project_detail->status == 1){
				$campaign_errors = Error::where('request_id',$campaign_id)->orderBy('id','desc')->whereDate('updated_at',date('Y-m-d'))->get();
				return view('vendor.campaign_archived',compact('campaign_errors','project_detail'));
			}
			$regional_db = RegionalDatabse::get();
			$profile_info  = ProfileInfo::where('user_id',$user_id)->where('request_id',$campaign_id)->first();
			$dashboards = DashboardType::where('status',1)->get();
			$user_dashboards =  CampaignDashboard::where('user_id',$user_id)->where('request_id',$campaign_id)->get();

		// integration data -analytics
		/*$get_analytics_emails =GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','google')->get();
		$get_analytics_accounts = GoogleAccountViewData::where('user_id',$user_id)->where('google_account_id',$project_detail->google_account_id)->where('parent_id',0)->groupBy('category_id')->get();
		$get_analytics_property = GoogleAccountViewData::where('parent_id',$project_detail->google_analytics_id)->get();
		$get_analytics_view = GoogleAccountViewData::where('parent_id',$project_detail->google_property_id)->get();

		// integration data -console
		$getConsoleAccount = SearchConsoleUsers::select('id','email')->where('user_id',$user_id)->get();
		$get_console_url = SearchConsoleUrl::where('user_id',$user_id)->where('google_account_id',$project_detail->google_console_id)->groupBy('siteUrl')->get();
		
		// integration data - ppc
		$getAdsAccounts = GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','google_ads')->orderBy('id','desc')->get()->unique('email');
		
		$get_ads_campaign = GoogleAdsCustomer::where('user_id',$user_id)->where('google_ads_id',$project_detail->google_ads_id)->where('can_manage_clients',0)->orderBy('id','desc')->get()->unique('customer_id');
		

		// integration data -gmb
		$getGmbAccounts = GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','gmb')->get();
		$get_gmb_list = GmbLocation::where('user_id',$user_id)->where('google_account_id',$project_detail->gmb_analaytics_id)->groupBy('website_url')->get();*/

		$summary = SeoAnalyticsEditSection::summary_section($campaign_id,$user_id);
		$alert_setting = CampaignSetting::where('request_id',$campaign_id)->first();

		$country = Country::get();

		$connected = false; $connectivity = ['ua' => false, 'ga4' => false];
		if($project_detail->google_analytics_id !== null && $project_detail->google_analytics_id !== ''){
			$connected = true;
			$connectivity['ua'] = true;
		}
		if($project_detail->ga4_email_id !== null && $project_detail->ga4_email_id !== ''){
			$connected = true;
			$connectivity['ga4'] = true;
		}
		
		return view('vendor.project-settings',compact('campaign_id','regional_db','project_detail','profile_info','user_id','dashboards','user_dashboards','summary','alert_setting','country','connectivity'));

		// return view('vendor.project-settings',['campaign_id'=>$campaign_id,'regional_db'=>$regional_db,'project_detail'=>$project_detail,'profile_info'=>$profile_info,'user_id'=>$user_id,'dashboards'=>$dashboards,'user_dashboards'=>$user_dashboards,'get_analytics_emails'=>$get_analytics_emails,'getConsoleAccount'=>$getConsoleAccount,'getAdsAccounts'=>$getAdsAccounts,'getGmbAccounts'=>$getGmbAccounts,'summary'=>$summary,'get_analytics_accounts'=>$get_analytics_accounts,'get_analytics_property'=>$get_analytics_property,'get_analytics_view'=>$get_analytics_view,'get_console_url'=>$get_console_url,'get_ads_campaign'=>$get_ads_campaign,'get_gmb_list'=>$get_gmb_list,'alert_setting'=>$alert_setting,'country'=>$country,'connectivity'=>$connectivity,'connected'=>$connected]);

	}else{
		return view('errors.404');
	}
}

public function ajax_store_project_general_settings(Request $request){
	$validator = Validator::make($request->all(),[
		'project_logo'=>'mimes:jpg,jpeg,png|max:2048'
	]);

	$validator->after(function ($validator)use($request) {
		if(!$request->has('dashboard')){
			$validator->errors()->add('dashboard', 'Select atleast one dashboard');
		}
	});
	if($validator->fails()){
		$array = array();
		foreach($validator->messages()->getMessages() as $field_name => $messages) {
			foreach($messages as $message) {
				$array[$field_name] = $message;
			}
		}         
		$response['status'] = 2;
		$response['message'] = $array;
		return response()->json($response);
	}

	$ifExists  = SemrushUserAccount::where('id',$request['request_id'])->first();

	if ($request->has('project_logo')) {
		$name = pathinfo($request->file('project_logo')->getClientOriginalName(), PATHINFO_FILENAME);
		$folder = 'project_logo/'.$request['request_id'];
		$logo = SemrushUserAccount::resizeImage($request->file('project_logo'),$folder,$name);
	}elseif(isset($ifExists->project_logo)){
		$logo = $ifExists->project_logo;
	}else{
		$logo = '';
	}


	$user_id = User::get_parent_user_id(Auth::user()->id); 
	$update = SemrushUserAccount::where('id',$request['request_id'])->update([
		'domain_name'=>$request['domain_name'],
		'domain_register'=>date('Y-m-d',strtotime($request['domain_register'])),
		'regional_db'=>$request['regional_db'],
		'clientName'=>$request['clientName'],
		'project_logo'=>$logo,
		'dashboard_type'=>implode(',',array_keys($request->dashboard)),
		'updated_at'=>now()
	]);

	CampaignDashboard::where('user_id',$user_id)->where('request_id',$request['request_id'])->delete();
	sleep(1);
	foreach($request->dashboard as $key=>$value){
		if($value == 'on'){
			$status = 1;
		}else{
			$status = 0;
		}

		$create = CampaignDashboard::updateOrCreate(	
			['request_id' => $request['request_id'],'dashboard_id' => $key], 	
			[	
				'request_id'=>$request['request_id'],	
				'dashboard_id'=>$key,	
				'dashboard_status'=>$status,	
				'user_id'=>$user_id	
			]	
		);
	}

	if($request->has('summary_toggle')){
		if($request['summary_toggle'] == 'on'){
			$toggle_value = 1;
		}else{
			$toggle_value = 0;
		}
	}else{
		$toggle_value = 0;
	}

	SeoAnalyticsEditSection::where('request_id',$request['request_id'])->update([
		'edit_section'=>$request['summarydata'],
		'display'=>$toggle_value
	]);

	if($update){
		$response['status'] = 1;
	}else{
		$response['status'] = 0;
	}

	return response()->json($response);
}


public function ajax_store_project_white_label(Request $request){

	$validator = Validator::make($request->all(),[
		'white_label_logo'=>'mimes:jpg,jpeg,png|max:2048'
	]);

	if($validator->fails()){
		$array = array();
		foreach($validator->messages()->getMessages() as $field_name => $messages) {
			foreach($messages as $message) {
				$array[$field_name] = $message;
			}
		}         
		$response['status'] = 2;
		$response['message'] = $array;
		return response()->json($response);
	}


		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		$ifExists  = ProfileInfo::where('user_id',$user_id)->where('request_id',$request['request_id'])->first();

		if ($request->has('white_label_logo')) {
			$name = pathinfo($request->file('white_label_logo')->getClientOriginalName(), PATHINFO_FILENAME);
			$folder = 'agency_logo/'.$user_id.'/'.$request['request_id'];
			$image_name = SemrushUserAccount::resizeImage($request->file('white_label_logo'),$folder,$name);
		}elseif(!empty($ifExists)){
			$image_name = $ifExists->agency_logo;
		}else{
			$image_name = '';
		}

		if(($image_name === '' || $image_name === null) && $request->has('white_label_branding')){
			$response['status'] = 3;
			$response['message'] = 'Upload image to show logo on viewkey and pdf';
			return response()->json($response);
		}


		$update = ProfileInfo::updateOrCreate(
			['request_id' => $request['request_id']],
			[
				'user_id'=>$user_id,
				'email'=>$request['email'],
				'country_code'=>$request['country_code'],
				'contact_no'=>$request['mobile'],
				'client_name'=>$request['client_name'],
				'company_name'=>$request['company_name'],
				'agency_logo'=>$image_name,
				'white_label_branding'=>($request->white_label_branding)?1:0
			]
		);
		if($update){
			$response['status'] = 1;
		}else{
			$response['status'] = 0;
		}

		

		return response()->json($response);
	}

	public function ajax_update_dashboard_settings(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$dashboards = $request['dashboard'];
		$ids = implode(',',array_keys($dashboards));

		
		$delete = CampaignDashboard::where('user_id',$user_id)->where('request_id',$request['request_id'])->delete();
		SemrushUserAccount::where('id',$request['request_id'])->update([
			'dashboard_type'=>$ids
		]);
		foreach($dashboards as $key=>$value){
			if($value == 'on'){
				$status = 1;
			}else{
				$status = 0;
			}

			$create = CampaignDashboard::updateOrCreate(	
				['request_id' => $request['request_id'],'dashboard_id' => $key], 	
				[	
					'request_id'=>$request['request_id'],	
					'dashboard_id'=>$key,	
					'dashboard_status'=>$status,	
					'user_id'=>$user_id	
				]	
			);

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

	public function dashboardActivate(Request $request){

		
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		
		$all_dashboards = DashboardType::where('status',1)->where('name',$request['dashboard'])->first();
		$projectAccount = SemrushUserAccount::where('id',$request['request_id'])->first();

		SemrushUserAccount::where('id',$request['request_id'])->update([
			'dashboard_type'=>$projectAccount->dashboard_type.','.$all_dashboards->id
		]);

		$create = CampaignDashboard::updateOrCreate(
			['request_id' => $request['request_id'],'dashboard_id' => $all_dashboards->id], 
			[
				'request_id'=>$request['request_id'],
				'dashboard_id'=>$all_dashboards->id,
				'dashboard_status'=>1,
				'user_id'=>$user_id
			]
		);
		
		if($create){
			$response['status'] = 1;
			$response['message'] ='Dashboard Settings Updated successfully!';
		}else{
			$response['status'] = 0;
			$response['message'] ='Error updating Dashboard Settings!';
		}

		return response()->json($response);
		//}
	}

	public function ajax_update_analytics_data(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child	
		$check = GoogleAnalyticsUsers::checkAnalyticsData($request->campaign_id,$user_id,$request->email,$request->account,$request->property,$request->view);

		if($check['status'] == 0){
			Error::updateOrCreate(
				['request_id' => $request->campaign_id,'module'=> 1],
				['response'=> json_encode($check),'request_id' => $request->campaign_id,'module'=> 1]
			);
			$response = SemrushUserAccount::display_google_errorMessages(1,$request->campaign_id);
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
			'ecommerce_goals'=>$ecom,
			'updated_at' =>now()
			//'modified'=>now()
		]);

		$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();
		
		$acc_id = $if_Exist->google_profile_id;

		if($update) {			
			$response['status'] = 'success';
			$log_data = GoogleAnalyticsUsers::log_analytics_data($request->campaign_id);
			if(isset($log_data['status']) && $log_data['status'] == 0){
				
			}else{
				$ifErrorExists = Error::where('module',1)->where('request_id',$request->campaign_id)->whereDate('updated_at','=',date('Y-m-d'))->first();
				if($ifErrorExists){
					Error::where('id',$ifErrorExists->id)->delete();
				}
				GoogleUpdate::updateTiming($request->campaign_id,'analytics','analytics_type','2');
			}
		}
		else {
			$response['status'] = 'error'; 
		}				

		return  response()->json($response);
	}

	// public function ajax_update_console_data(Request $request){

	// 	$user_id = User::get_parent_user_id(Auth::user()->id); 
	// 	$check = SearchConsoleUrl::checkConsoleData($request->campaign_id,$user_id,$request->email,$request->account);

	// 	if($check['status'] == 0){
	// 		Error::updateOrCreate(
	// 			['request_id' => $request->campaign_id,'module'=> 2],
	// 			['response'=> json_encode($check),'request_id' => $request->campaign_id,'module'=> 2]
	// 		);
	// 		$response = SemrushUserAccount::display_google_errorMessages(2,$request->campaign_id);
	// 		return response()->json($response);
	// 	}

	// 	$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();
	// 	$acc_id = $if_Exist->console_account_id;
	
	// 	$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)
	// 	->update([
	// 		'google_console_id'=>$request->email,
	// 		'console_account_id'=>$request->account,
	// 		'modified'=>now()
	// 	]);


	// 	if($update) {		
	// 		$response['status'] = 'success';
	// 		$log_data = SearchConsoleUsers::log_console_data($request->campaign_id);	
	// 		if(isset($log_data['status']) && $log_data['status'] == 0){
	// 			$ifErrorExists = Error::where('module',2)->where('request_id',$request->campaign_id)->first();
	// 			if($ifErrorExists){
	// 				Error::where('id',$ifErrorExists->id)->delete();
	// 			}
	// 		}else{
	// 			GoogleUpdate::updateTiming($request->campaign_id,'search_console','sc_type','2');
	// 		}	
	// 	} else if(!$update){
	// 		$response['status'] = 'error'; 
	// 	}				
	// 	return  response()->json($response);
	// }

	public function ajax_update_adwords_data(Request $request){
		ini_set('max_execution_time', 10800);
		ini_set("memory_limit", "10056M");
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		
		$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)
		->update([
			'google_ads_campaign_id'=>$request->account,
			'google_ads_id'=>$request->email
		]);

		$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();
		
		if($if_Exist->status == 0  && $if_Exist->google_ads_id <> null && $if_Exist->google_ads_campaign_id <> null){
			$acc_id = $if_Exist->google_ads_campaign_id;
			GoogleAdsCustomer::log_adwords_data($request->campaign_id);
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

	public function ajax_update_adwords_json(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		
		$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)
		->update([
			'google_ads_campaign_id'=>$request->account,
			'google_ads_id'=>$request->email
		]);

		$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();
		
		if($if_Exist->status == 0  && $if_Exist->google_ads_id <> null && $if_Exist->google_ads_campaign_id <> null){
			$acc_id = $if_Exist->google_ads_campaign_id;
			GoogleAdsCustomer::log_adwords($request->campaign_id);
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

	// public function ajax_log_adwords_connected_data(Request $request){
	// 	GoogleAdsCustomer::log_adwords_data($request->campaign_id);
	// }


	public function ajax_get_adwords_account_id(Request $request){
		$ads_customer_id = '';
		$value = SemrushUserAccount::
		with(array('google_adwords_account'=>function($query){
			$query->select('id','customer_id');
		}))
		->where('status','0')
		->where('id',$request->campaign_id)
		->first();

		if(!empty($value->google_adwords_account) || ($value->google_adwords_account != null)){

			$ads_customer_id = $value->google_adwords_account->customer_id;
		}
		return response()->json($ads_customer_id);
	}

	public function ajax_update_summary_data(Request $request){	
		$update = SeoAnalyticsEditSection::where('request_id',$request['request_id'])->update([
			'edit_section'=>$request['summary'],
			'display'=>$request['summary_toggle']
		]);
		if($update){
			$response['status'] = 1;
			$response['message'] = 'Summary section updated successfully!';
		}else{
			$response['status'] = 0;
			$response['message'] = 'Error! Please try again.';
		}
		return response()->json($response);
	}

	public function ajax_disconnect_analaytics(Request $request){
		$result = SemrushUserAccount::findOrFail($request->request_id);
		if(!empty($result)){
			SemrushUserAccount::where('id',$request->request_id)->update([
				'google_account_id'=>NULL,
				'google_analytics_id'=>NULL,
				'google_property_id'=>NULL,
				'google_profile_id'=>NULL
			]);
			if (file_exists(env('FILE_PATH').'public/analytics/'.$request->request_id)) {
				SemrushUserAccount::remove_directory(env('FILE_PATH').'public/analytics/'.$request->request_id);
			}

			if (file_exists(env('FILE_PATH').'public/goalcompletion/'.$request->request_id)) {
				SemrushUserAccount::remove_directory(env('FILE_PATH').'public/goalcompletion/'.$request->request_id);
			}
			
			$response['status'] = 'success';
		}else{
			$response['status'] = 'error';
		}
		return response()->json($response);
	}

	public function ajax_disconnect_console(Request $request){
		$result = SemrushUserAccount::findOrFail($request->request_id);
		if(!empty($result)){
			SemrushUserAccount::where('id',$request->request_id)->update([
				'google_console_id'=>NULL,
				'console_account_id'=>NULL
			]);
			if (file_exists(env('FILE_PATH').'public/search_console/'.$request->request_id)) {
				SemrushUserAccount::remove_directory(env('FILE_PATH').'public/search_console/'.$request->request_id);
			}
			
			$response['status'] = 'success';
		}else{
			$response['status'] = 'error';
		}
		return response()->json($response);
	}

	public function ajax_disconnect_adwords(Request $request){
		$result = SemrushUserAccount::findOrFail($request->request_id);
		if(!empty($result)){
			SemrushUserAccount::where('id',$request->request_id)->update([
				'google_ads_id'=>NULL,
				'google_ads_campaign_id'=>NULL
			]);
			if (file_exists(env('FILE_PATH').'public/adwords/'.$request->request_id)) {
				SemrushUserAccount::remove_directory(env('FILE_PATH').'public/adwords/'.$request->request_id);
			}
			$response['status'] = 'success';
		}else{
			$response['status'] = 'error';
		}
		return response()->json($response);
	}

	public function ajax_disconnect_gmb(Request $request){	
		$result = SemrushUserAccount::findOrFail($request->request_id);	
		if(!empty($result)){	
			SemrushUserAccount::where('id',$request->request_id)->update([	
				'gmb_analaytics_id'=>NULL,	
				'gmb_id'=>NULL	
			]);	
			if (file_exists(env('FILE_PATH').'public/gmb/'.$request->request_id)) {	
				SemrushUserAccount::remove_directory(env('FILE_PATH').'public/gmb/'.$request->request_id);	
			}	
			
			$response['status'] = 'success';	
		}else{	
			$response['status'] = 'error';	
		}	
		return response()->json($response);	
	}

	/*06 May*/
	public function ajax_fetch_last_updated(Request $request){
		$response = array();
		$user_id = User::get_parent_user_id(Auth::user()->id);
		if($request->provider == 'gmb'){
			$data = GoogleAnalyticsUsers::select('id','updated_at')->where('user_id',$user_id)->where('id',$request->email_id)->first();
			if(!empty($data)){
				$time_span = KeywordPosition::calculate_time_span($data->updated_at);
				$response['status'] = 1;
				$response['time'] = $time_span;
			}else{
				$response['status'] = 0;
				$response['time'] = 'Error encountered.';
			}
		}


		if($request->provider == 'ppc'){
			$data = GoogleAnalyticsUsers::select('id','updated_at')->where('user_id',$user_id)->where('id',$request->email_id)->first();
			if(!empty($data)){
				$time_span = KeywordPosition::calculate_time_span($data->updated_at);
				$response['status'] = 1;
				$response['time'] = $time_span;
			}else{
				$response['status'] = 0;
				$response['time'] = 'Error encountered.';
			}
		}

		if($request->provider == 'google_analytics'){
			$data = GoogleAnalyticsUsers::select('id','updated_at')->where('user_id',$user_id)->where('id',$request->email_id)->first();
			if(!empty($data)){
				$time_span = KeywordPosition::calculate_time_span($data->updated_at);
				$response['status'] = 1;
				$response['time'] = $time_span;
			}else{
				$response['status'] = 0;
				$response['time'] = 'Error encountered.';
			}
		}

		if($request->provider == 'search_console'){
			$data = SearchConsoleUsers::select('id','updated_at')->where('user_id',$user_id)->where('id',$request->email_id)->first();
			if(!empty($data)){
				$time_span = KeywordPosition::calculate_time_span($data->updated_at);
				$response['status'] = 1;
				$response['time'] = $time_span;
			}else{
				$response['status'] = 0;
				$response['time'] = 'Error encountered.';
			}
		}

		if($request->provider == 'facebook'){
			$data = SocialAccount::select('id','updated_at')->where('id',$request->email_id)->first();
			if(!empty($data)){
				$time_span = KeywordPosition::calculate_time_span($data->updated_at);
				$response['status'] = 1;
				$response['time'] = $time_span;
			}else{
				$response['status'] = 0;
				$response['time'] = 'Error encountered.';
			}
		}

		return response()->json($response);
	}

	public function ajax_fetch_analytics_accounts(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$project_detail = SemrushUserAccount::where('id',$request->campaign_id)->first();

		$getAnaAccounts = GoogleAnalyticsUsers::where('user_id',$user_id)->where('oauth_provider','google')->get();

		$get_analytics_accounts = GoogleAccountViewData::where('user_id',$user_id)->where('google_account_id',$project_detail->google_account_id)->where('parent_id',0)->groupBy('category_id')->get();

		$get_analytics_property = GoogleAccountViewData::where('parent_id',$project_detail->google_analytics_id)->get();
		$analyticsProperty = GoogleAccountViewData::where('parent_id',$project_detail->google_property_id)->get();

		$emails	 = $accounts	= $property	= $view =	'<option value="">Select from Existing Accounts</option>';

		if(!empty($getAnaAccounts)) {
			foreach($getAnaAccounts as $result) {
				$selected = $result->id == $project_detail->google_account_id ? "selected" : "";
				$emails	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->email.'</option>';
			} 

		}

		if(!empty($get_analytics_accounts)) {
			foreach($get_analytics_accounts as $result) {
				
				$selected = $result->id == $project_detail->google_analytics_id ? "selected" : "";

				$accounts	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->category_name.'</option>';
			} 

		}

		if(!empty($get_analytics_property)) {
			foreach($get_analytics_property as $result) {
				$selected = $result->id == $project_detail->google_property_id ? "selected" : "";
				$property	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->category_name.'</option>';
			} 
		}

		if(!empty($analyticsProperty)) {
			foreach($analyticsProperty as $result) {
				$selected = $result->id == $project_detail->google_profile_id ? "selected" : "";
				$view	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->category_name.'</option>';
			} 
		}

		$data['emails'] = $emails;
		$data['accounts'] = $accounts;
		$data['properties'] = $property;
		$data['views'] = $view;

		return $data;
	}
	
	/*public function ajax_fetch_analytics_accounts(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$project_detail = SemrushUserAccount::where('id',$request->campaign_id)->first();
		$get_analytics_accounts = GoogleAccountViewData::where('user_id',$user_id)->where('google_account_id',$project_detail->google_account_id)->where('parent_id',0)->groupBy('category_id')->get();
		$li	=	'<option value="">Select from Existing Accounts</option>';
		if(!empty($get_analytics_accounts)) {
			foreach($get_analytics_accounts as $result) {
				
				$selected = $result->id == $project_detail->google_analytics_id ? "selected" : "";

				$li	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->category_name.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}*/


	public function ajax_fetch_analytics_property(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$project_detail = SemrushUserAccount::where('id',$request->campaign_id)->first();
		$get_analytics_property = GoogleAccountViewData::where('parent_id',$project_detail->google_analytics_id)->get();
		$li	=	'<option value="">Select from Existing Property</option>';
		if(!empty($get_analytics_property)) {
			foreach($get_analytics_property as $result) {
				$selected = $result->id == $project_detail->google_property_id ? "selected" : "";
				$li	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->category_name.'</option>';
			} 
		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}

	public function ajax_fetch_analytics_view(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$project_detail = SemrushUserAccount::where('id',$request->campaign_id)->first();
		$get_analytics_property = GoogleAccountViewData::where('parent_id',$project_detail->google_property_id)->get();
		$li	=	'<option value="">Select from Existing Property</option>';
		if(!empty($get_analytics_property)) {
			foreach($get_analytics_property as $result) {
				$selected = $result->id == $project_detail->google_profile_id ? "selected" : "";
				$li	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->category_name.'</option>';
			} 
		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		return $li;
	}

	/*public function ajax_fetch_console_urls(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$project_detail = SemrushUserAccount::where('id',$request->campaign_id)->first();
		$getData = SearchConsoleUrl::where('user_id',$user_id)->where('google_account_id',$project_detail->google_console_id)->groupBy('siteUrl')->get();
		$li	=	'<option value="">Select Account</option>';
		if(!empty($getData)) {
			foreach($getData as $result) {
				$selected = $result->id == $project_detail->console_account_id ? "selected" : "";
				$li	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->siteUrl.'</option>';
			} 
			
		}else{
			$li	.= '<option value="">No Result Found</option>';
		}
		
		return $li;
	}*/

	public function ajax_fetch_console_urls(Request $request){

		$urls	= $li	=	'<option value="">Select Account</option>';

		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$project_detail = SemrushUserAccount::where('id',$request->campaign_id)->first();
		
		$getConsoleAccount = SearchConsoleUsers::select('id','email')->where('user_id',$user_id)->get();

		if(!empty($getConsoleAccount)) {
			foreach($getConsoleAccount as $result) {
				$selected = $result->id == $project_detail->google_console_id ? "selected" : "";
				$urls	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->email.'</option>';
			} 
		}

		$getData = SearchConsoleUrl::where('user_id',$user_id)->where('google_account_id',$project_detail->google_console_id)->groupBy('siteUrl')->get();
		
		if(!empty($getData)) {
			foreach($getData as $result) {
				$selected = $result->id == $project_detail->console_account_id ? "selected" : "";
				$li	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->siteUrl.'</option>';
			} 
		}
		
		$data['emails'] = $li;  
		$data['url'] = $urls;  
		return $data;

	}

	public function ajax_fetch_adwords_campaigns(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$project_detail = SemrushUserAccount::where('id',$request->campaign_id)->first();
		$getData = GoogleAdsCustomer::where('user_id',$user_id)->where('google_ads_id',$project_detail->google_ads_id)->where('can_manage_clients',0)->where('name','!=','NULL')->get();
		$li	=	'<option value="">Select Account</option>';
		if(!empty($getData)) {
			foreach($getData as $result) {
				$selected = $result->id == $project_detail->google_ads_campaign_id ? "selected" : "";
				$li	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->name.'</option>';
			} 
			
		}else{
			$li	.= '<option value="">No Result Found</option>';
		}
		
		return $li;
	}

	public function ajax_fetch_gmb_accounts(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$project_detail = SemrushUserAccount::where('id',$request->campaign_id)->first();
		$getData = $get_gmb_list = GmbLocation::where('user_id',$user_id)->where('google_account_id',$project_detail->gmb_analaytics_id)->groupBy('website_url')->get();
		
		$li	=	'<option value="">Select from Existing Accounts</option>';
		if(!empty($getData)) {
			foreach($getData as $result) {
				$selected = $result->id == $project_detail->gmb_id ? "selected" : "";
				$li	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->location_name.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}
		
		return $li;
	}


	/*public function ajax_fetch_gmb_emails(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$project_detail = SemrushUserAccount::where('id',$request->campaign_id)->first();
		$getGmbAccounts = GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','gmb')->get();
		$li	=	'<option value="">Select from Existing Emails</option>';
		if(!empty($getGmbAccounts)) {
			foreach($getGmbAccounts as $result) {
				$selected = $result->id == $project_detail->gmb_analaytics_id ? "selected" : "";
				$li	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->email.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}
		
		return $li;
	}*/

	public function ajax_fetch_gmb_emails(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$project_detail = SemrushUserAccount::where('id',$request->campaign_id)->first();
		$getGmbAccounts = GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','gmb')->get();
		$li	=	'<option value="">Select from Existing Emails</option>';
		if(!empty($getGmbAccounts)) {
			foreach($getGmbAccounts as $result) {
				$selected = $result->id == $project_detail->gmb_analaytics_id ? "selected" : "";
				$li	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->email.'</option>';
			} 

		}

		$getData = $get_gmb_list = GmbLocation::where('user_id',$user_id)->where('google_account_id',$project_detail->gmb_analaytics_id)->groupBy('website_url')->get();
		
		$accounts =	'<option value="">Select from Existing Accounts</option>';
		if(!empty($getData)) {
			foreach($getData as $result) {
				$selected = $result->id == $project_detail->gmb_id ? "selected" : "";
				$accounts	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->location_name.'</option>';
			} 

		}

		$data['emails'] = $li;
		$data['accounts'] = $accounts;

		return $data;
	}


	/*public function ajax_fetch_adwords_emails(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$project_detail = SemrushUserAccount::where('id',$request->campaign_id)->first();
		$getAdsAccounts = GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','google_ads')->get();
		$li	=	'<option value="">Select from Existing Emails</option>';
		if(!empty($getAdsAccounts)) {
			foreach($getAdsAccounts as $result) {
				$selected = $result->id == $project_detail->google_ads_id ? "selected" : "";
				$li	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->email.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}
		
		return $li;
	}*/

	public function ajax_fetch_adwords_emails(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$project_detail = SemrushUserAccount::where('id',$request->campaign_id)->first();
		$getAdsAccounts = GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','google_ads')->get();
		$accounts	= $li	=	'<option value="">Select from Existing Emails</option>';
		if(!empty($getAdsAccounts)) {
			foreach($getAdsAccounts as $result) {
				$selected = $result->id == $project_detail->google_ads_id ? "selected" : "";
				$li	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->email.'</option>';
			} 

		}else{
			$li	.= '<option value="">No Result Found</option>';
		}

		$getData = GoogleAdsCustomer::where('user_id',$user_id)->where('google_ads_id',$project_detail->google_ads_id)->where('can_manage_clients',0)->where('name','!=','NULL')->get();
		
		if(!empty($getData)) {
			foreach($getData as $result) {
				$selected = $result->id == $project_detail->google_ads_campaign_id ? "selected" : "";
				$accounts	.= '<option value="'.$result->id.'" '. $selected .'>'.$result->name.'</option>';
			} 
			
		}
		
		$data['emails'] = $li;
		$data['accounts'] = $accounts; 
		return $data;
	}

	public function ajax_remove_project_logo(Request $request){

		$response = array();
		$image = explode('/',$request->project_logo);
		$project_logo = end($image);
		$products = SemrushUserAccount::where('id',$request->project_id)->update([
			'project_logo' => null
		]);
		if($products){
			$fullImgPath = storage_path('app/public/project_logo/'.$request->project_id.'/'.$project_logo);
			if(File::exists($fullImgPath)) {
				File::delete($fullImgPath);
			}
			$response['status'] = 1;
			$response['message'] = 'Project Logo removed successfully.';
		}else{
			$response['status'] = 0;
			$response['message'] = 'Error removing project logo.';
		}

		return response()->json($response);
	}

	public function ajax_remove_project_agency_logo(Request $request){
		
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$response = array();
		$image = explode('/',$request->agency_logo);
		$agency_logo = end($image);

		$products = ProfileInfo::where('request_id',$request->project_id)->update([
			'agency_logo' => null
		]);
		if($products){
			$fullImgPath = storage_path('app/public/agency_logo/'.$user_id.'/'.$request->project_id.'/'.$agency_logo);
			if(File::exists($fullImgPath)) {
				File::delete($fullImgPath);
			}
			$response['status'] = 1;
			$response['message'] = 'Agency Logo removed successfully.';
		}else{
			$response['status'] = 0;
			$response['message'] = 'Error removing project logo.';
		}

		return response()->json($response);
	}

	public function ajax_save_keyword_table_settings(Request $request){
		$response = array();
		$campaign_id = $request->campaign_id;
		$heading = $request->name;
		$column_type = $request->column;
		$status = $request->switchStatus;
		if($request->switchStatus == 'false'){
			$toggleValue = 0;
		}else{
			$toggleValue = 1;
		}
		
		$selected = array('detail','viewkey','pdf');
		if (($key = array_search($column_type, $selected)) !== false) {
			unset($selected[$key]);
			$selected = array_values($selected);
		}
		

		$ifExists = LiveKeywordSetting::where('request_id',$campaign_id)->where('heading',$heading)->first();
		if(empty($ifExists)){
			LiveKeywordSetting::create([
				'request_id'=>$campaign_id,
				'heading'=>$heading,
				$column_type=>$toggleValue
			]);
		}else{
			$first = $selected[0]; $second = $selected[1];
			if($toggleValue == 1 && ($ifExists->$first == 1 && $ifExists->$second == 1)){
				LiveKeywordSetting::where('request_id',$campaign_id)->where('heading',$heading)->delete();
			}else{
				LiveKeywordSetting::where('request_id',$campaign_id)->where('heading',$heading)->update([
					$column_type => $toggleValue
				]);
			}
		}

		$response['status'] = 1;
		$response['message'] = 'Changes saved successfully.';

		return response()->json($response);
	}

	public function ajax_get_table_settings(Request $request){
		$response = array();
		$campaign_id = $request->campaign_id;
		$get_table_settings = LiveKeywordSetting::select('id','heading','detail','viewkey','pdf')->where('request_id',$campaign_id)->get()->toArray();
		
		$start_rank_detail = $start_rank_viewkey = $start_rank_pdf = $page_detail = $page_viewkey = $page_pdf = $google_rank_detail = $google_rank_viewkey = $google_rank_pdf = $oneday_detail = $oneday_viewkey = $oneday_pdf = $weekly_detail = $weekly_viewkey = $weekly_pdf = $monthly_detail = $monthly_viewkey = $monthly_pdf = $lifetime_detail = $lifetime_viewkey = $lifetime_pdf = $competition_detail = $competition_viewkey = $competition_pdf = $sv_detail = $sv_viewkey = $sv_pdf =  $date_detail = $date_viewkey = $date_pdf =  $url_detail = $url_viewkey = $url_pdf = $graph_detail = $graph_viewkey = $graph_pdf = 'checked';

		if(isset($get_table_settings) && !empty($get_table_settings)){
			$start_rank = array_search('start_rank',array_column($get_table_settings, 'heading'));
			if($start_rank >= 0 && $start_rank !== false){
				if($get_table_settings[$start_rank]['detail'] == 0){
					$start_rank_detail = '';
				}
				if($get_table_settings[$start_rank]['viewkey'] == 0){
					$start_rank_viewkey = '';
				}
				if($get_table_settings[$start_rank]['pdf'] == 0){
					$start_rank_pdf = '';
				}
			}

			$page = array_search('page',array_column($get_table_settings, 'heading'));
			if($page >= 0  && $page !== false){
				if($get_table_settings[$page]['detail'] == 0){
					$page_detail = '';
				}
				if($get_table_settings[$page]['viewkey'] == 0){
					$page_viewkey = '';
				}
				if($get_table_settings[$page]['pdf'] == 0){
					$page_pdf = '';
				}
			}

			$google_rank = array_search('google_rank',array_column($get_table_settings, 'heading'));
			if($google_rank >= 0  && $google_rank !== false){
				if($get_table_settings[$google_rank]['detail'] == 0){
					$google_rank_detail = '';
				}
				if($get_table_settings[$google_rank]['viewkey'] == 0){
					$google_rank_viewkey = '';
				}
				if($get_table_settings[$google_rank]['pdf'] == 0){
					$google_rank_pdf = '';
				}
			}

			$oneday = array_search('oneday',array_column($get_table_settings, 'heading'));
			if($oneday >= 0 && $oneday !== false){
				if($get_table_settings[$oneday]['detail'] == 0){
					$oneday_detail = '';
				}
				if($get_table_settings[$oneday]['viewkey'] == 0){
					$oneday_viewkey = '';
				}
				if($get_table_settings[$oneday]['pdf'] == 0){
					$oneday_pdf = '';
				}
			}

			$weekly = array_search('weekly',array_column($get_table_settings, 'heading'));
			if($weekly >= 0 && $weekly !== false){
				if($get_table_settings[$weekly]['detail'] == 0){
					$weekly_detail = '';
				}
				if($get_table_settings[$weekly]['viewkey'] == 0){
					$weekly_viewkey = '';
				}
				if($get_table_settings[$weekly]['pdf'] == 0){
					$weekly_pdf = '';
				}
			}

			$monthly = array_search('monthly',array_column($get_table_settings, 'heading'));
			if($monthly >= 0 && $monthly !== false){
				if($get_table_settings[$monthly]['detail'] == 0){
					$monthly_detail = '';
				}
				if($get_table_settings[$monthly]['viewkey'] == 0){
					$monthly_viewkey = '';
				}
				if($get_table_settings[$monthly]['pdf'] == 0){
					$monthly_pdf = '';
				}
			}

			$lifetime = array_search('lifetime',array_column($get_table_settings, 'heading'));
			if($lifetime >= 0 && $lifetime !== false){
				if($get_table_settings[$lifetime]['detail'] == 0){
					$lifetime_detail = '';
				}
				if($get_table_settings[$lifetime]['viewkey'] == 0){
					$lifetime_viewkey = '';
				}
				if($get_table_settings[$lifetime]['pdf'] == 0){
					$lifetime_pdf = '';
				}
			}

			$competition = array_search('competition',array_column($get_table_settings, 'heading'));
			if($competition >= 0 && $competition !== false){
				if($get_table_settings[$competition]['detail'] == 0){
					$competition_detail = '';
				}
				if($get_table_settings[$competition]['viewkey'] == 0){
					$competition_viewkey = '';
				}
				if($get_table_settings[$competition]['pdf'] == 0){
					$competition_pdf = '';
				}
			}

			$sv = array_search('sv',array_column($get_table_settings, 'heading'));
			if($sv >= 0 && $sv !== false){
				if($get_table_settings[$sv]['detail'] == 0){
					$sv_detail = '';
				}
				if($get_table_settings[$sv]['viewkey'] == 0){
					$sv_viewkey = '';
				}
				if($get_table_settings[$sv]['pdf'] == 0){
					$sv_pdf = '';
				}
			}

			$date = array_search('date',array_column($get_table_settings, 'heading'));
			if($date >= 0 && $date !== false){
				if($get_table_settings[$date]['detail'] == 0){
					$date_detail = '';
				}
				if($get_table_settings[$date]['viewkey'] == 0){
					$date_viewkey = '';
				}
				if($get_table_settings[$date]['pdf'] == 0){
					$date_pdf = '';
				}
			}

			$url = array_search('url',array_column($get_table_settings, 'heading'));
			if($url >= 0 && $url !== false){
				if($get_table_settings[$url]['detail'] == 0){
					$url_detail = '';
				}
				if($get_table_settings[$url]['viewkey'] == 0){
					$url_viewkey = '';
				}
				if($get_table_settings[$url]['pdf'] == 0){
					$url_pdf = '';
				}
			}

			$graph = array_search('graph',array_column($get_table_settings, 'heading'));
			if($graph >= 0 && $graph !== false){
				if($get_table_settings[$graph]['detail'] == 0){
					$graph_detail = '';
				}
				if($get_table_settings[$graph]['viewkey'] == 0){
					$graph_viewkey = '';
				}
				if($get_table_settings[$graph]['pdf'] == 0){
					$graph_pdf = '';
				}
			}

			$response['status'] = 1;
		}else{
			$response['status'] = 0;
		}

		$response['start_rank_detail'] = $start_rank_detail;
		$response['start_rank_viewkey'] = $start_rank_viewkey;
		$response['start_rank_pdf'] = $start_rank_pdf;
		$response['page_detail'] = $page_detail;
		$response['page_viewkey'] = $page_viewkey;
		$response['page_pdf'] = $page_pdf;
		$response['google_rank_detail'] = $google_rank_detail;
		$response['google_rank_viewkey'] = $google_rank_viewkey;
		$response['google_rank_pdf'] = $google_rank_pdf;
		$response['oneday_detail'] = $oneday_detail;
		$response['oneday_viewkey'] = $oneday_viewkey;
		$response['oneday_pdf'] = $oneday_pdf;
		$response['weekly_detail'] = $weekly_detail;
		$response['weekly_viewkey'] = $weekly_viewkey;
		$response['weekly_pdf'] = $weekly_pdf;
		$response['monthly_detail'] = $monthly_detail;
		$response['monthly_viewkey'] = $monthly_viewkey;
		$response['monthly_pdf'] = $monthly_pdf;
		$response['lifetime_detail'] = $lifetime_detail;
		$response['lifetime_viewkey'] = $lifetime_viewkey;
		$response['lifetime_pdf'] = $lifetime_pdf;
		$response['competition_detail'] = $competition_detail;
		$response['competition_viewkey'] = $competition_viewkey;
		$response['competition_pdf'] = $competition_pdf;
		$response['sv_detail'] = $sv_detail;
		$response['sv_viewkey'] = $sv_viewkey;
		$response['sv_pdf'] = $sv_pdf;
		$response['date_detail'] = $date_detail;
		$response['date_viewkey'] = $date_viewkey;
		$response['date_pdf'] = $date_pdf;
		$response['url_detail'] = $url_detail;
		$response['url_viewkey'] = $url_viewkey;
		$response['url_pdf'] = $url_pdf;
		$response['graph_detail'] = $graph_detail;
		$response['graph_viewkey'] = $graph_viewkey;
		$response['graph_pdf'] = $graph_pdf;

		return response()->json($response);
	}

	public function ajax_update_project_alerts(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$rules = array(
			'keyword_alerts_client_email' => 'required_if:keyword_client_alerts,1',   
			'keyword_manager_alerts_email' => 'required_if:keyword_alerts,1'
		);
		if($request->has('keyword_client_alerts'))
			$rules["keyword_alerts_client_email.*"]="required|string|distinct|email";
		if ($request->has('keyword_manager_alerts') && $request->has('keyword_client_alerts'))
			$rules["keyword_manager_alerts_email"] ="required|string|email";

		$validator = Validator::make($request->all(),$rules);		

		if($validator->fails()){
			$array = $keys = array();
			foreach($validator->messages()->getMessages() as $field_name => $messages) {
				$explode = explode('.',$field_name);
				if(isset($explode[1])){
					$array[$explode[0]][$explode[1]] = $messages[0];
					$keys[] = $explode[1];
				}else{
					$array[$explode[0]] = $messages[0];
					$keys[] = $explode[0];
				}
			} 

			$response['status'] = 0;
			$response['message'] = $array;
			$response['keys'] = $keys;			
			return response()->json($response);
		}else{
			if($request->has('keyword_client_alerts')){
				$update = CampaignSetting::updateOrCreate(
					['request_id' => $request->request_id],
					[
						'user_id'=>$user_id,
						'request_id'=>$request->request_id,
						'client_alerts' => ($request->keyword_client_alerts)?1:0,
						'client_emails' => implode(', ',$request->keyword_alerts_client_email),
						'manager_alerts' => ($request->keyword_manager_alerts)?1:0,
						'manager_email' => ($request->keyword_manager_alerts)?$request->keyword_manager_alerts_email:null,
						'updated_at'=>now()
					]
				);
			}else{
				$update = CampaignSetting::where('request_id',$request->request_id)->delete();
			}

			if($update){
				$response['status'] = 1;
			}else{
				$response['status'] = 2;
			}
			return response()->json($response);
		}
	}

	/*new design changes*/

	public function ajax_update_console_data(Request $request){
		$response = array();
		$user_id = User::get_parent_user_id(Auth::user()->id); 

		$getAnalytics = SearchConsoleUsers::where('user_id',$user_id)->where('id',$request->email)->first();

		$client = SearchConsoleUsers::ClientAuth($getAnalytics);
		$refresh_token  = $getAnalytics->google_refresh_token;
		if ($client->isAccessTokenExpired()) {
			GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
		}

		$get_profile_data = SearchConsoleUrl::where('id',$request->account)->first();
		if(!empty($get_profile_data)){
			$profile_url = $get_profile_data->siteUrl;
			$check = SearchConsoleUrl::check_weekly_data($client,$profile_url,$request->account);

			if($check['status'] === 0 || $check['status'] === 2){
				$response['status'] = 'google-error'; 
				Error::updateOrCreate(
					['request_id' => $request->campaign_id,'module'=> 2],
					['response'=> json_encode($check),'request_id' => $request->campaign_id,'module'=> 2]
				);
				$response = SemrushUserAccount::display_google_errorMessages(2,$request->campaign_id);
				return response()->json($response);
			}else{
				$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();

				$update = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)
				->update([
					'google_console_id'=>$request->email,
					'console_account_id'=>$request->account,
					'updated_at'=>now()
				]);

				if($update) {	
					$response['status'] = 'success';
					$log_data = SearchConsoleUsers::log_search_console_data($client,$profile_url,$request->campaign_id);		
					if(isset($log_data['status']) && $log_data['status'] == 1){
						$ifErrorExists = Error::removeExisitingError(2,$request->campaign_id);
						if(!empty($ifErrorExists)){
							Error::where('id',$ifErrorExists->id)->delete();
						}
						GoogleUpdate::updateTiming($request->campaign_id,'search_console','sc_type','2');
					}
				} elseif(!$update){
					$response['status'] = 'error'; 
				}

				return  response()->json($response);
			}
		}
	}
	
}