<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\SemrushUserAccount;
use App\DashboardType;
use App\CampaignDashboard;
use App\SeoAnalyticsEditSection;
use App\BacklinkSummary;
use App\BackLinksData;
use App\SemrushOrganicMetric;
use App\ModuleByDateRange;
use App\GoogleGoalCompletion;
use App\GoogleProfileData;
use App\ProjectCompareGraph;
use App\RegionalDatabse;
use App\GoogleAnalyticsUsers;
use App\SearchConsoleUsers;
use App\UserPackage;
use App\Package;
use App\KeywordSearch;
use App\Moz;
use App\Error;
use Auth;
use DB;
use Carbon\Carbon;

use App\GmbLocation;	
use App\Language;	
use App\KeywordTag;

use App\Traits\GMBAuth;

use App\GoogleAdsCustomer;
use App\SearchConsoleUrl;
use App\GoogleUpdate;
use App\KeywordPosition;
use App\GlobalSetting;
use App\LiveKeywordSetting;
use App\AuditTask;
use App\SemrushBacklinkSummary;
use App\Social\{SocialAccount};

class CampaignDetailController extends Controller {


	use GMBAuth;

	public function campaign_detail($domain_name, $campaign_id){

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
		$role_id = User::get_user_role(Auth::user()->id); 
		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();	
		$campaign_errors = Error::where('request_id',$campaign_id)->orderBy('id','desc')->whereDate('created_at',date('Y-m-d'))->get();
		$keyenc = base64_encode($campaign_id.'-|-'.$user_id.'-|-'.time());
		

		if(isset($data) && !empty($data)){

			if($data->status == 1){
				return view('vendor.campaign_archived',compact('campaign_errors'));
			}
			$all_dashboards = DashboardType::where('status',1)->pluck('name','id')->all();

			$types = CampaignDashboard::
			where('user_id',$user_id)
			->where('dashboard_status',1)
			->where('request_id',$campaign_id)
			->orderBy('order_status','asc')
			->orderBy('dashboard_id','asc')
			->pluck('dashboard_id')
			->all();
			
			if($types[0] == 1){
				$table_settings = LiveKeywordSetting::where('detail',0)->where('request_id',$campaign_id)->pluck('heading')->all();
				$seo_content = $this->seo_content($domain_name,$campaign_id);
				$search_console = $this->detail_search_console($domain_name,$campaign_id);
				$ga4_data = $this->detail_google_analytics4($domain_name,$campaign_id);

				$compactData['dashboardtype'] = $seo_content['dashboardtype'];
				$compactData['role_id'] = $seo_content['role_id'];
				$compactData['table_settings'] = $table_settings;
				$compactData['audit'] = $seo_content['audit'];

				$compactData['selected'] = $search_console['duration'];
				$compactData['start_date'] = $search_console['start_date'];
				$compactData['end_date'] = $search_console['end_date'];
				$compactData['compare_start_date'] = $search_console['compare_start_date'];
				$compactData['compare_end_date'] = $search_console['compare_end_date'];
				$compactData['comparison'] = $search_console['comparison'];
				$compactData['compare_to'] = $search_console['compare_to'];

				$compactData['ga4_selected'] = $ga4_data['duration'];
				$compactData['ga4_start_date'] = $ga4_data['start_date'];
				$compactData['ga4_end_date'] = $ga4_data['end_date'];
				$compactData['ga4_compare_start_date'] = $ga4_data['compare_start_date'];
				$compactData['ga4_compare_end_date'] = $ga4_data['compare_end_date'];
				$compactData['ga4_comparison'] = $ga4_data['comparison'];
				$compactData['ga4_compare_to'] = $ga4_data['compare_to'];
				$compactData['connected'] = $seo_content['connected'];
				$compactData['connectivity'] = $seo_content['connectivity'];
			}

			if($types[0] == 2){
				$ppc_content = $this->ppc_content($domain_name,$campaign_id);
				$compactData['account_id'] = $ppc_content['account_id'];		
				$compactData['moduleadsStatus'] = $ppc_content['moduleadsStatus'];		
				$compactData['getGoogleAds'] = $ppc_content['getGoogleAds'];		
				$compactData['getAdsAccounts'] = $ppc_content['getAdsAccounts'];	
				$compactData['selectedSearch'] = $ppc_content['selectedSearch'];			
				$compactData['selected_value'] = $ppc_content['selected_value'];
			}

			if($types[0] == 3){

				//gmb
				$compactData['gtUser'] = $data;

				$customer_search_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_customer_search')->first();
				if(!empty($customer_search_selected)){
					$compactData['selected_customer_search'] = $customer_search_selected->duration;
				}else{
					$compactData['selected_customer_search'] = 3;
				}
				$customer_view_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_customer_view')->first();
				if(!empty($customer_view_selected)){
					$compactData['selected_customer_view'] = $customer_view_selected->duration;
				}else{
					$compactData['selected_customer_view'] = 3;
				}
				$customer_action_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_customer_action')->first();
				if(!empty($customer_action_selected)){
					$compactData['selected_customer_action'] = $customer_action_selected->duration;
				}else{
					$compactData['selected_customer_action'] = 3;
				}
				$direction_request_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_direction_requests')->first();
				if(!empty($direction_request_selected)){
					$compactData['selected_direction_request'] = $direction_request_selected->duration;
				}else{
					$compactData['selected_direction_request'] = 30;
				}
				$phone_calls_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_phone_calls')->first();
				if(!empty($phone_calls_selected)){
					$compactData['selected_phone_calls'] = $phone_calls_selected->duration;
				}else{
					$compactData['selected_phone_calls'] = 3;
				}
				$photo_views_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_photo_views')->first();
				if(!empty($photo_views_selected)){
					$compactData['selected_photo_views'] = $photo_views_selected->duration;
				}else{
					$compactData['selected_photo_views'] = 3;
				}
			}

			if($types[0] == 4){
				$getFacebookAcounts = SocialAccount::select('id','oauth_uid','name')->where([['user_id',$user_id],['oauth_provider','facebook'],['id',$data->fbid]])->first();
				$compactData['fb_account'] = $getFacebookAcounts;
				$compactData['gtUser'] = $data;
			}
			
			$compactData['data'] = $data;
			$compactData['campaign_id'] = $campaign_id;
			$compactData['types'] = $types;
			$compactData['all_dashboards'] = $all_dashboards;
			$compactData['user_id'] = $user_id;
			$compactData['dashboardStatus'] = true;
			$compactData['keyenc'] = $keyenc;

			return view('vendor.campaign_detail',$compactData);
		}else{
			return view('errors.404');
		}
		
	}

	public function campaign_seo_second($domain_name,$campaign_id){
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		$keyenc = base64_encode($campaign_id.'-|-'.$user_id.'-|-'.time());
		
		$seo_content = $this->detail_seo_content($domain_name,$campaign_id);
		$dashboardtype = $seo_content['dashboardtype'];
		$role_id = $seo_content['role_id'];
		$table_settings = LiveKeywordSetting::where('detail',0)->where('request_id',$campaign_id)->pluck('heading')->all();

		return \View::make('vendor.campaign_detail.seo_second',[
			'campaign_id'=>$campaign_id,
			'dashboardtype'=>$dashboardtype,'role_id'=>$role_id,'keyenc'=>$keyenc,'table_settings'=>$table_settings
		]);
	}

	public function campaign_seo_third($domain_name,$campaign_id){
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		$keyenc = base64_encode($campaign_id.'-|-'.$user_id.'-|-'.time());
		
		$seo_content = $this->detail_seo_content($domain_name,$campaign_id);
		$dashboardtype = $seo_content['dashboardtype'];
		$role_id = $seo_content['role_id'];

		return \View::make('vendor.campaign_detail.seo_third',[
			'campaign_id'=>$campaign_id,'dashboardtype'=>$dashboardtype,'role_id'=>$role_id,'keyenc'=>$keyenc
		]);
	}

	public static function detail_seo_content($domain_name,$campaign_id){
		if(Auth::user() <> null){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$role_id = User::get_user_role(Auth::user()->id);
		}else{

			$getUser = SemrushUserAccount::where('id',$campaign_id)->first(); 
			$user_id = User::get_parent_user_id($getUser->user_id); //get user id from child
			$role_id = User::get_user_role($getUser->user_id);
		}

		$audit_task = AuditTask::where('campaign_id',$campaign_id)->latest()->first();

		if(!empty($audit_task) && $audit_task <> null){
			$audit = 'check';
		}else{
			$audit = 'run';
		}

		$dashboardtype = SemrushUserAccount::where('id',$campaign_id)->first();		
		$final = array(
			'dashboardtype'=>$dashboardtype,
			'role_id'=>$role_id,
			'audit'=>$audit
		);
		return $final;
	}

	public function ajax_analytics_range_data(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$selected = 'three'; $display_type = 'day'; $comparison = 0;
		$moduleTrafficStatus = ModuleByDateRange::getModuleDateRange($request->campaign_id,'organic_traffic');
		if(!empty($moduleTrafficStatus)){
			if($moduleTrafficStatus->duration == 1){ $selected = 'month';}elseif($moduleTrafficStatus->duration == 3){ $selected = 'three';}elseif($moduleTrafficStatus->duration == 6){ $selected = 'six';}elseif($moduleTrafficStatus->duration == 9){ $selected = 'nine';}elseif($moduleTrafficStatus->duration == 12){ $selected = 'year';}elseif($moduleTrafficStatus->duration == 24){ $selected = 'twoyear';}
			$display_type = ($moduleTrafficStatus->display_type)?:'day';
		}

		$AnalyticsCompare = ProjectCompareGraph::where('request_id',$request->campaign_id)->where('user_id',$user_id)->first();
		if(!empty($AnalyticsCompare)){
			$comparison = $AnalyticsCompare->compare_status;
		}

		$response['selected'] = $selected;
		$response['display_type'] = $display_type;
		$response['comparison'] = $comparison;

		return response()->json($response);
	}

	public function ajax_console_range_data(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$selected = 'month';

		$moduleSearchStatus = ModuleByDateRange::getModuleDateRange($request->campaign_id,'search_console');
		if(!empty($moduleSearchStatus)){
			if($moduleSearchStatus->duration == 1){ $selected = 'month';}elseif($moduleSearchStatus->duration == 3){ $selected = 'three';}elseif($moduleSearchStatus->duration == 6){ $selected = 'six';}elseif($moduleSearchStatus->duration == 9){ $selected = 'nine';}elseif($moduleSearchStatus->duration == 12){ $selected = 'year';}elseif($moduleSearchStatus->duration == 24){ $selected = 'twoyear';}
		}

		$response['selected'] = $selected;

		return response()->json($response);
	}


	public function campaign_seo_content($domain_name,$campaign_id){
		$dashboardStatus = self::dashboardStatus('SEO',$campaign_id);
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child		
		$seo_content = $this->seo_content($domain_name,$campaign_id);
		$table_settings = LiveKeywordSetting::where('detail',0)->where('request_id',$campaign_id)->pluck('heading')->all();
		$dashboardtype = $seo_content['dashboardtype'];
		$role_id = $seo_content['role_id'];

		$ga4_data = $this->detail_google_analytics4($domain_name,$campaign_id);

		$profile_data = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();	

		return \View::make('vendor.campaign_detail.seo',[
			'campaign_id'=>$campaign_id,
			'dashboardtype'=>$dashboardtype,
			'role_id'=>$role_id,
			'dashboardStatus'=>$dashboardStatus,
			'table_settings'=>$table_settings,
			'connected'=>$seo_content['connected'],
			'connectivity'=>$seo_content['connectivity'],
			'ga4_selected' => $ga4_data['duration'],
			'ga4_start_date' => $ga4_data['start_date'],
			'ga4_end_date' => $ga4_data['end_date'],
			'ga4_compare_start_date' => $ga4_data['compare_start_date'],
			'ga4_compare_end_date' => $ga4_data['compare_end_date'],
			'ga4_comparison' => $ga4_data['comparison'],
			'ga4_compare_to' =>$ga4_data['compare_to'],
			'profile_data' =>$profile_data
		]);
	}


	public function campaign_ppc_content($domain_name=null,$campaign_id=null){

		$dashboardStatus = self::dashboardStatus('PPC',$campaign_id);

		$ppc_content = $this->ppc_content($domain_name,$campaign_id);
		$account_id = $ppc_content['account_id'];		
		$moduleadsStatus = $ppc_content['moduleadsStatus'];		
		$getGoogleAds = $ppc_content['getGoogleAds'];		
		$getAdsAccounts = $ppc_content['getAdsAccounts'];		
		$selectedSearch = $ppc_content['selectedSearch'];		
		$selected_value = $ppc_content['selected_value'];	

		$profile_data = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();	

		return \View::make('vendor.campaign_detail.ppc',['campaign_id'=>$campaign_id,'account_id'=>$account_id,'moduleadsStatus'=>$moduleadsStatus,'getGoogleAds'=>$getGoogleAds,'getAdsAccounts'=>$getAdsAccounts,'selectedSearch'=>$selectedSearch,'selected_value'=>$selected_value,'dashboardStatus'=>$dashboardStatus,'profile_data'=>$profile_data]);
	}

	public function campaign_gmb_content($domain_name,$campaign_id){	

		$dashboardStatus = self::dashboardStatus('GMB',$campaign_id);

		if(Auth::user() <> null){	
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child	
		}else{	
			$getUser = SemrushUserAccount::where('id',$campaign_id)->first(); 	
			$user_id = User::get_parent_user_id($getUser->user_id); //get user id from child	
		}	
		$gtUser = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->where('status',0)->first();	
		$customer_search_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_customer_search')->first();	
		if(!empty($customer_search_selected)){	
			$selected_customer_search = $customer_search_selected->duration;	
		}else{	
			$selected_customer_search = 3;	
		}	
		$customer_view_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_customer_view')->first();	
		if(!empty($customer_view_selected)){	
			$selected_customer_view = $customer_view_selected->duration;	
		}else{	
			$selected_customer_view = 3;	
		}	
		$customer_action_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_customer_action')->first();	
		if(!empty($customer_action_selected)){	
			$selected_customer_action = $customer_action_selected->duration;	
		}else{	
			$selected_customer_action = 3;	
		}	
		$direction_request_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_direction_requests')->first();	
		if(!empty($direction_request_selected)){	
			$selected_direction_request = $direction_request_selected->duration;	
		}else{	
			$selected_direction_request = 30;	
		}	
		$phone_calls_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_phone_calls')->first();	
		if(!empty($phone_calls_selected)){	
			$selected_phone_calls = $phone_calls_selected->duration;	
		}else{	
			$selected_phone_calls = 3;	
		}	
		$photo_views_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_photo_views')->first();	
		if(!empty($photo_views_selected)){	
			$selected_photo_views = $photo_views_selected->duration;	
		}else{	
			$selected_photo_views = 3;	
		}	
		return \View::make('vendor.dashboards.gmb',compact('campaign_id','gtUser','selected_customer_search','selected_customer_view','selected_customer_action','selected_direction_request','selected_phone_calls','selected_photo_views','dashboardStatus'));	
	}

	public static function gmb_content($domain_name,$campaign_id){

		if(Auth::user() <> null){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		}else{
			$getUser = SemrushUserAccount::where('id',$campaign_id)->first(); 
			$user_id = User::get_parent_user_id($getUser->user_id); //get user id from child
		}
		
		
		$gtUser = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->where('status',0)->first();

		$customer_search_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_customer_search')->first();
		if(!empty($customer_search_selected)){
			$selected_customer_search = $customer_search_selected->duration;
		}else{
			$selected_customer_search = 3;
		}
		$customer_view_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_customer_view')->first();
		if(!empty($customer_view_selected)){
			$selected_customer_view = $customer_view_selected->duration;
		}else{
			$selected_customer_view = 3;
		}
		$customer_action_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_customer_action')->first();
		if(!empty($customer_action_selected)){
			$selected_customer_action = $customer_action_selected->duration;
		}else{
			$selected_customer_action = 3;
		}
		$direction_request_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_direction_requests')->first();
		if(!empty($direction_request_selected)){
			$selected_direction_request = $direction_request_selected->duration;
		}else{
			$selected_direction_request = 30;
		}
		$phone_calls_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_phone_calls')->first();
		if(!empty($phone_calls_selected)){
			$selected_phone_calls = $phone_calls_selected->duration;
		}else{
			$selected_phone_calls = 3;
		}
		$photo_views_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_photo_views')->first();
		if(!empty($photo_views_selected)){
			$selected_photo_views = $photo_views_selected->duration;
		}else{
			$selected_photo_views = 3;
		}
		return array('gtUser'=>$gtUser,'selected_customer_search'=>$selected_customer_search,'selected_customer_view'=>$selected_customer_view,'selected_customer_action'=>$selected_customer_action,'selected_direction_request'=>$selected_direction_request,'selected_phone_calls'=>$selected_phone_calls,'selected_photo_views'=>$selected_photo_views);
	}

	public function campaign_social_content($domain_name,$campaign_id){
		return \View::make('vendor.campaign_detail.social',['campaign_id'=>$campaign_id]);
	}

	public static function seo_content($domain_name,$campaign_id){
		
		if(Auth::user() <> null){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$role_id = User::get_user_role(Auth::user()->id);
		}else{

			$getUser = SemrushUserAccount::where('id',$campaign_id)->first(); 
			$user_id = User::get_parent_user_id($getUser->user_id); //get user id from child
			$role_id = User::get_user_role($getUser->user_id);
		}
		$summary = SeoAnalyticsEditSection::where('request_id', $campaign_id)->first();
		$moz_data = Moz::where('request_id',$campaign_id)->orderBy('id','desc')->first();
		$first_moz = Moz::where('request_id',$campaign_id)->orderBy('id','asc')->first();


		$dashboardtype = SemrushUserAccount::where('id',$campaign_id)->first();

		$moduleSearchStatus = ModuleByDateRange::getModuleDateRange($campaign_id,'search_console');
		if(!empty($moduleSearchStatus)){
			$selectedSearch = $moduleSearchStatus->duration;
		}else{
			$selectedSearch = 3;
		}


		$moduleTrafficStatus = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');
		if(!empty($moduleTrafficStatus)){
			$selected = $moduleTrafficStatus->duration;
			$display_type = ($moduleTrafficStatus->display_type)?:'day';
		}else{
			$selected = 3;
			$display_type = 'day';
		}


		$AnalyticsCompare = ProjectCompareGraph::where('request_id',$campaign_id)->where('user_id',$user_id)->first();
		if(!empty($AnalyticsCompare)){
			$comparison = $AnalyticsCompare->compare_status;
		}else{
			$comparison = 0;
		}


		$backlink_profile_summary = SemrushBacklinkSummary::where('request_id',$campaign_id)->whereDate('created_at','<=',date('Y-m-d'))->orderBy('id','desc')->first();
		$flag = 0;
		if(!isset($backlink_profile_summary) && $backlink_profile_summary ===  null){
			$backlink_profile_summary = BacklinkSummary::where('request_id',$campaign_id)->whereDate('created_at','<=',date('Y-m-d'))->orderBy('id','desc')->first();
			$flag = 1;
		}



		$backlink_records = BacklinkProfileController::get_backlink_data(20,$campaign_id,'created_at','asc','');
		//$get_goal_data = GoalCompletionController::get_goal_data($campaign_id,1,'id','asc');

		$live_keywords = LiveKeywordController::get_live_keyword_tracking($campaign_id,'currentPosition','asc','20');
		$getRegions = RegionalDatabse::where('status',1)->get();

		$audit_task = AuditTask::where('campaign_id',$campaign_id)->latest()->first();
		if(!empty($audit_task) && $audit_task <> null){
			$audit = 'check';
		}else{
			$audit = 'run';
		}

		$connected = false; $connectivity = ['ua' => false, 'ga4' => false];
        if($dashboardtype->google_analytics_id !== null && $dashboardtype->google_analytics_id !== ''){
            $connected = true; $connectivity['ua'] = true;
        }
        if($dashboardtype->ga4_email_id !== null && $dashboardtype->ga4_email_id !== ''){
            $connected = true; $connectivity['ga4'] = true;
        }

		
		$final = array('summary'=>$summary,'moz_data'=>$moz_data,'first_moz'=>$first_moz,'dashboardtype'=>$dashboardtype,'selectedSearch'=>$selectedSearch,'selected'=>$selected,'comparison'=>$comparison,'backlink_profile_summary'=>$backlink_profile_summary,'backlink_records'=>$backlink_records,'live_keywords'=>$live_keywords,'role_id'=>$role_id,'getRegions'=>$getRegions,'display_type'=>$display_type,
			'audit'=>$audit,'flag'=>$flag,'connected'=>$connected,'connectivity'=>$connectivity
			//,'get_goal_data'=>$get_goal_data
		);
		return $final;
	}

	public static function ppc_content($domain_name,$campaign_id){

		$account_id = $moduleadsStatus = '';
		$selectedSearch = 3;$selected_value = 'three';
		if(Auth::user() <> null){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		}else{
			$getUser = SemrushUserAccount::where('id',$campaign_id)->first(); 
			$user_id = User::get_parent_user_id($getUser->user_id); //get user id from child
		}
		$getGoogleAds = SemrushUserAccount::where('id',$campaign_id)->first();
		// dd($getGoogleAds);
		if(!empty($getGoogleAds)){

			$AdsCustomer = GoogleAdsCustomer::where('id',$getGoogleAds->google_ads_campaign_id)->first();
			$account_id = isset($AdsCustomer->customer_id)?$AdsCustomer->customer_id:'';

			$moduleadsStatus = ModuleByDateRange::where('user_id',$user_id)->where('request_id',$campaign_id)->where('module','google_ads')->first();

			if(!empty($moduleadsStatus)){
				$selectedSearch = $moduleadsStatus->duration;	
				if($moduleadsStatus->duration == 1){
					$selected_value = 'month';
				}elseif($moduleadsStatus->duration == 3){
					$selected_value = 'three';
				}elseif($moduleadsStatus->duration == 6){
					$selected_value = 'six';
				}elseif($moduleadsStatus->duration == 9){
					$selected_value = 'nine';
				}elseif($moduleadsStatus->duration == 12){
					$selected_value = 'year';
				}elseif($moduleadsStatus->duration == 24){
					$selected_value = 'twoyear';
				}else{
					$selected_value = 'three';
				}
			}else{
				$selectedSearch = 3;
				$selected_value = 'three';
			}

		}

		$getAdsAccounts = GoogleAnalyticsUsers::select('id','email')->where('user_id',$user_id)->where('oauth_provider','google_ads')->get();

		
		return array('account_id'=>$account_id,'moduleadsStatus'=>$moduleadsStatus,'getGoogleAds'=>$getGoogleAds,'getAdsAccounts'=>$getAdsAccounts,'selectedSearch'=>$selectedSearch,'selected_value'=>$selected_value);
	}


	public function ajax_page_authority_chart(Request $request){
		$moz_chart = Moz::where('request_id',$request['campaignId'])->select('created_at','page_authority')->latest()->limit(6)->get();
		$label = $result = $labels = $results = array();

		foreach($moz_chart as $key=>$value){
			$label[] = date('M d, Y',strtotime($value->created_at));
			$result[] = $value->page_authority;
		}
		$labels = array_reverse($label);
		$results = array_reverse($result);

		array_unshift($labels, '');
		array_unshift($results, 0);
		$res['from_datelabel'] = $labels;
		$res['page'] = $results;
		return response()->json($res);
	}

	public function ajax_domain_authority_chart(Request $request){
		$moz_chart = Moz::where('request_id',$request['campaignId'])->select('created_at','domain_authority')->latest()->limit(6)->get();
		$label = $result = $labels = $results = array();

		foreach($moz_chart as $key=>$value){
			$label[] = date('M d, Y',strtotime($value->created_at));
			$result[] = $value->domain_authority;
		}
		$labels = array_reverse($label);
		$results = array_reverse($result);

		array_unshift($labels, '');
		array_unshift($results, 0);
		$res['from_datelabel'] = $labels;
		$res['domain'] = $results;
		return response()->json($res);
	}

	

	public function store_moz_data_monthly(Request $request){
		$request_data = SemrushUserAccount::select('id','user_id','domain_url')->where('status','!=','2')->get();
		
		if(!empty($request_data) && isset($request_data)){
			foreach($request_data  as $semrush_data){
				$data = Moz::
				whereMonth('created_at','=',date('m'))
				->whereYear('created_at','=',date('Y'))
				->where('request_id',$semrush_data->id)
				->orderBy('id','desc')
				->first();


				if(empty($data) && $data == null){
					$domain_url = rtrim($semrush_data->domain_url, '/');
					$insertMozData = Moz::getMozData($semrush_data->domain_url);
					if ($insertMozData) {
						Moz::create([
							'user_id' => $semrush_data->user_id,
							'request_id' => $semrush_data->id,
							'domain_authority' => $insertMozData->DomainAuthority,
							'page_authority' => $insertMozData->PageAuthority,
							'status' => 0
						]);
					}

				}	
			}		
		}		
	}


	public function ajax_referring_domains_bkppp(Request $request){
		$request_id = $request['campaign_id'];

		$current_month = 	BacklinkSummary::
		where('request_id',$request_id)
		->latest()
		->first();

		$since_start = 	BacklinkSummary::
		where('request_id',$request_id)
		->orderBy('id','asc')
		->first();

		if(!empty($current_month) && !empty($since_start)){
			$avg = $current_month->referringDomains - $since_start->referringDomains;
			$total = $current_month->referringDomains;
		}elseif(empty($current_month) && !empty($since_start)){
			$avg = 0 - $since_start->referringDomains;
			$total = $since_start->referringDomains;
		}elseif(!empty($current_month) && empty($since_start)){
			$avg = $total = $current_month->referringDomains;
		}else{
			$avg  = '??';
			$total = '??';
		}
		return array('avg'=>$avg,'total'=>$total);
	}

	public function ajax_referring_domains(Request $request){
		$request_id = $request['campaign_id'];
		$current_month = SemrushBacklinkSummary::where('request_id',$request_id)->latest()->first();
		if(!empty($current_month) && isset($current_month) && $current_month <> null){
			$since_start = 	SemrushBacklinkSummary::where('request_id',$request_id)->orderBy('id','asc')->first();
			$rf = $current_month->domains_num;
			$ss = $current_month->domains_num;
		}else{
			$since_start = 	BacklinkSummary::where('request_id',$request_id)->orderBy('id','asc')->first();
			$current_month = BacklinkSummary::where('request_id',$request_id)->latest()->first();
			$rf = $current_month->referringDomains;
			$ss = $since_start->referringDomains;
		}

		if(!empty($current_month) && !empty($since_start)){
			$avg =  $rf - $ss;
			$total = $rf;
		}elseif(empty($current_month) && !empty($since_start)){
			$avg = 0 - $ss;
			$total = $ss;
		}elseif(!empty($current_month) && empty($since_start)){
			$avg = $total = $rf;
		}else{
			$avg  = '??';
			$total = '??';
		}
		return array('avg'=>$avg,'total'=>$total);
	}


	public function ajaxorganicKeywordRanking(Request $request){	
		$request_id = $request['campaignId'];
		$current  = SemrushOrganicMetric::where('request_id',$request_id)->latest()->first();
		
		
		if(isset($current) && !empty($current)){
			$total_count = $current->total_count;
		} else{
			$total_count = '??';
		}
		
		$since_start =  SemrushOrganicMetric::where('request_id',$request_id)->orderBy('id','asc')->limit(1)->first();

		if(!empty($current->total_count) && !empty($since_start->total_count)){
			if($since_start->total_count){
				$organic_keywords = $current->total_count - $since_start->total_count;
			} else {
				$organic_keywords = 0;
			}
			
		}else if(empty($current->total_count) && !empty($since_start->total_count) ) {
			$organic_keywords	=  -100;
		} else if(!empty($current->total_count) && empty($since_start->total_count) ) {
			$organic_keywords	=  $current->total_count;
		} else{
			$organic_keywords	=  '??';
		}
		
		return array('totalCount' => $total_count, 'organic_keywords' => $organic_keywords);
		
	}
	

	public function ajax_organic_visitors(Request $request){
		$today = date('Y-m-d');
		$today_new = date('Y-m-d');
		$campaign_id = $request['campaignId'];

		if (!file_exists(env('FILE_PATH')."public/analytics/".$campaign_id)) {
			$res['traffic_growth'] = '??';
			$res['current_users'] = '??';
			$res['status'] = 0;			
		} else {
			$url = env('FILE_PATH')."public/analytics/".$campaign_id.'/metrics.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);

			$visitors = $visitors_current = 0;
			$project_data = SemrushUserAccount::get_created_date($campaign_id);

			$current_start_date = date('d M, Y',strtotime(now()));
			$current_end_date = date('d M, Y',strtotime('-30 day'));				

			$current_start = array_search($current_start_date,$final->metrics_dates);
			$current_end = array_search($current_end_date,$final->metrics_dates);

			// $day_diff = SemrushUserAccount::day_diff($project_data->domain_register);
			// if($day_diff <= 30){
			// 	$total_users = 0;
			// 	if($current_end !== false && $current_start !== false){
			// 		for($j=$current_end;$j<=$current_start;$j++){
			// 			$visitors_current += $final->metrics_users[$j];
			// 		}	
			// 	}else{
			// 		$current_start_date = end($final->metrics_dates);
			// 		$current_start = array_search($current_start_date,$final->metrics_dates);
			// 		$current_end_date = date('d M, Y',strtotime('-30 day',strtotime($current_start_date)));
			// 		$current_end = array_search($current_end_date,$final->metrics_dates);
			// 		for($j=$current_end;$j<=$current_start;$j++){
			// 			$visitors_current += $final->metrics_users[$j];
			// 		}
			// 	}
			// }else{
			$end_month_date = date('d M, Y',strtotime('-30 day',strtotime($project_data->domain_register)));
			$start_month_date = date('d M, Y',strtotime($project_data->domain_register));

			$prev_start = array_search($start_month_date,$final->metrics_dates);
			$prev_end = array_search($end_month_date,$final->metrics_dates);

			if($prev_start !== false && $prev_end !== false){
				for($i=$prev_end;$i<=$prev_start;$i++){
					$visitors += $final->metrics_users[$i];
				}
			}else{
				for($i=0;$i<=29;$i++){
					$visitors += $final->metrics_users[$i];
				}
			}

			if($current_end !== false && $current_start !== false){
				for($j=$current_end;$j<=$current_start;$j++){
					$visitors_current += $final->metrics_users[$j];
				}	
			}else{
				$current_start_date = end($final->metrics_dates);
				$current_start = array_search($current_start_date,$final->metrics_dates);
				$current_end_date = date('d M, Y',strtotime('-30 day',strtotime($current_start_date)));
				$current_end = array_search($current_end_date,$final->metrics_dates);
				for($j=$current_end;$j<=$current_start;$j++){
					$visitors_current += $final->metrics_users[$j];
				}
			}

			if(($visitors_current > 0) && ($visitors > 0)){
				
				if(($visitors_current + $visitors) == 0){
					$total_users = number_format(($visitors_current - $visitors),2);
				}else{
					if($visitors != 0){
						$total_users = number_format((($visitors_current - $visitors) / $visitors) * 100,2);
					}else{
						$total_users = '100';
					}
				}

			}else if(($visitors_current == 0) && ($visitors > 0)) {
				$total_users = '-100';
			} else if(($visitors_current > 0) && ($visitors == 0)) {
				$total_users = '100';
			} else{
				$total_users = '??'; 
			}
			// }

			$res['traffic_growth'] = $total_users;
			$res['current_users'] = shortNumbers($visitors_current);
			$res['status'] = 1;	
		}
		return response()->json($res);
	}


	public function ajaxgoogleAnalyticsGoal(Request $request){


		$campaignId = $request['campaignId'];
		if(Auth::user() <> null){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		}else{
			$getUser = SemrushUserAccount::where('id',$campaignId)->first();
			$user_id = $getUser->user_id;
		}

		$goalCompletion = GoogleGoalCompletion::
		where('request_id',$request['campaignId'])
		->where('user_id',$user_id)
		->first();
		
		
		$goal = GoogleProfileData::
		select(DB::raw('SUM(goal_completions) AS total'))
		->where('request_id',$request['campaignId'])
		->where('user_id',$user_id)
		->orderBy('id','asc')
		->first();
		
		
		if(!empty($goalCompletion->goal_count) && !empty($goal->total) ) {
			$goal_result	=	(($goal->total - $goalCompletion->goal_count) / $goalCompletion->goal_count * 100);
			$goal_result = number_format($goal_result,2,'.','');
		} else if(empty($goal->total) && !empty($goalCompletion->goal_count) ) {
			$goal_result	= ' -100';
		} else if(!empty($goal->total) && empty($goalCompletion->goal_count) ) {
			$goal_result	= ' 100';
		} else{
			$goal_result	= 'N/A';
		}

		if(isset($goal->total) && !empty($goal)){
			$goal_total= $goal->total;
		}	else{
			$goal_total = 'N/A';
		}	

		return array('total'=>$goal_total,'goal_result'=>$goal_result);
	}


	public function ajax_organic_keyword_chart(Request $request){
		$labels = $data = array();
		$result  = SemrushOrganicMetric::where('request_id',$request['campaignId'])->latest()->limit(6)->select('total_count','created_at')->get()->toArray();
		$results = array_reverse((array)$result);
		

		if(!empty($results)){
			foreach($results as $key=> $value){
				$labels[] = date('M d, Y',strtotime($value['created_at']));
				$data[] = $value['total_count'];
			} 

			array_unshift($labels, '');
			array_unshift($data, 0);

			$res['from_datelabel'] = $labels;
			$res['total_count'] = $data;
			$res['status'] = 1;
		}else{
			$res['status'] = 0;
			$res['from_datelabel'] = [0];
			$res['total_count'] = [0];
		}

		return response()->json($res);
	}
	

	public function ajax_organic_visitors_chart(Request $request){
		$result = array();
		$campaign_id = $request['campaignId'];
		if (!file_exists(env('FILE_PATH')."public/analytics/".$campaign_id)) {
			$result = [0];
			$end_new = [0];
		}else{
			$lapse ='-7 day';
			$end_date = date('Y-m-d');
			for($i=1;$i<=6;$i++){
				if($i==1){
					$start_date = date('Y-m-d',strtotime($end_date));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				}else{
					$start_date = date('Y-m-d',strtotime('-1 day',strtotime($end_date)));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				}

				$result[] = $this->organic_visitors_chart_data($end_date,$start_date,$campaign_id);
				$end_new[] = date('M d, Y',strtotime($end_date));
				$start_date_new[] = date('M d, Y',strtotime($start_date));
			}  
			$result = array_reverse($result);
			$end_new = array_reverse($end_new);

			array_unshift($result, 0);
			array_unshift($end_new, "");   
		}

		$dates['visitors'] = $result;
		$dates['labels'] = $end_new;
		

		return $dates; 
	}

	private function organic_visitors_chart_data($start_date,$end_date,$campaign_id){
		$start_date = date('d M, Y',strtotime($start_date));
		$end_date = date('d M, Y',strtotime($end_date));


		$visitors = 0;
		if (!file_exists(env('FILE_PATH')."public/analytics/".$campaign_id)) {
			$visitors = 0;
		}else{
			$url = env('FILE_PATH')."public/analytics/".$campaign_id.'/metrics.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);

			$get_index = array_search($start_date,$final->metrics_dates);
			$get_index_today = array_search($end_date,$final->metrics_dates);
			
			if($get_index == false && $get_index_today == false){
				return 0;
			}elseif($get_index <> false && $get_index_today == false){
				$get_index_today = array_search(end($final->metrics_dates),$final->metrics_dates);
			}

			for($i=$get_index;$i<=$get_index_today;$i++){
				$visitors += $final->metrics_users[$i];
			}
		}

		return $visitors;
	}

	public function ajax_referring_domain_chart_bkp(Request $request){
		$summaryData = 	BacklinkSummary::select('referringDomains','created_at')->where('request_id',$request['campaignId'])->latest()->limit(6)->get()->toArray();
		$results = array_reverse($summaryData);
		$labels = $data = array();
		foreach($results as $key=> $value){
			$labels[] = date('M d, Y',strtotime($value['created_at']));
			$data[] = $value['referringDomains'];
		} 
		array_unshift($labels, '');
		array_unshift($data, 0);
		$res['labels'] = $labels;
		$res['referringDomains'] = $data;
		return response()->json($res);
	}

	public function ajax_referring_domain_chart(Request $request){
		$summaryData = 	SemrushBacklinkSummary::select('domains_num','created_at')->where('request_id',$request['campaignId'])->latest()->limit(6)->get()->toArray();
		if(!empty($summaryData) && isset($summaryData) && $summaryData <> null){
			$results = array_reverse($summaryData);
			$labels = $data = array();
			foreach($results as $key=> $value){
				$labels[] = date('M d, Y',strtotime($value['created_at']));
				$data[] = $value['domains_num'];
			} 
		}else{
			$summaryData = 	BacklinkSummary::select('referringDomains','created_at')->where('request_id',$request['campaignId'])->latest()->limit(6)->get()->toArray();
			$results = array_reverse($summaryData);
			$labels = $data = array();
			foreach($results as $key=> $value){
				$labels[] = date('M d, Y',strtotime($value['created_at']));
				$data[] = $value['referringDomains'];
			} 
		}
		
		array_unshift($labels, '');
		array_unshift($data, 0);

		$res['labels'] = $labels;
		$res['referringDomains'] = $data;
		return response()->json($res);
	}


	/* View functions */

	public function campaign_seo_content_view($campaign_id){

		$types = CampaignDashboard::
		where('status',1)
		->where('request_id',$campaign_id)
		->where('dashboard_id',1)
		->first();

		$dashboardStatus = self::dashboardStatus('SEO',$campaign_id);
		$profile_data = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();
		if($types == null){
			return \View::make('viewkey.dashboards.seo',compact('types','campaign_id','dashboardStatus','profile_data'));
		}
		$domain_name = '';
		$seo_content = $this->seo_content($domain_name,$campaign_id);
		$summary = $seo_content['summary'];
		$moz_data = $seo_content['moz_data'];
		$first_moz = $seo_content['first_moz'];
		$dashboardtype = $seo_content['dashboardtype'];
		$selectedSearch = $seo_content['selectedSearch'];
		$selected = $seo_content['selected'];
		$comparison = $seo_content['comparison'];
		$backlink_profile_summary = $seo_content['backlink_profile_summary'];
		$backlink_records = $seo_content['backlink_records'];
		$live_keywords = $seo_content['live_keywords'];
		$role_id = $seo_content['role_id'];
		$getRegions = $seo_content['getRegions'];

		
		
		return \View::make('viewkey.dashboards.seo',['campaign_id'=>$campaign_id,'summary'=>$summary,'moz_data'=>$moz_data,'first_moz'=>$first_moz,'dashboardtype'=>$dashboardtype,'selectedSearch'=>$selectedSearch,'selected'=>$selected,'comparison'=>$comparison,'backlink_profile_summary'=>$backlink_profile_summary,'backlink_records'=>$backlink_records,
			'live_keywords'=>$live_keywords,'role_id'=>$role_id,'getRegions'=>$getRegions,'types'=>$types,'dashboardStatus'=>$dashboardStatus,'profile_data'=>$profile_data
		]);
	}

	public function campaign_seo_content_viewmore($campaign_id,$encrypted_id){

		$types = CampaignDashboard::
		where('status',1)
		->where('request_id',$campaign_id)
		->where('dashboard_id',1)
		->first();

		
		if($types == null){
			return \View::make('viewkey.dashboards.seo-view-more',compact('types','campaign_id','encrypted_id'));
		}
		$domain_name = '';
		$table_settings = LiveKeywordSetting::where('viewkey',0)->where('request_id',$campaign_id)->pluck('heading')->all();

		$seo_content = $this->seo_content($domain_name,$campaign_id);
		$ga4_data = $this->detail_google_analytics4($domain_name,$campaign_id);

		$summary = $seo_content['summary'];
		$moz_data = $seo_content['moz_data'];
		$first_moz = $seo_content['first_moz'];
		$dashboardtype = $seo_content['dashboardtype'];
		$selectedSearch = $seo_content['selectedSearch'];
		$selected = $seo_content['selected'];
		$comparison = $seo_content['comparison'];
		$backlink_profile_summary = $seo_content['backlink_profile_summary'];
		$backlink_records = $seo_content['backlink_records'];
		$live_keywords = $seo_content['live_keywords'];
		$role_id = $seo_content['role_id'];
		$getRegions = $seo_content['getRegions'];
		$display_type = $seo_content['display_type'];
		$flag = $seo_content['flag'];

		$connected = $seo_content['connected'];
		$connectivity = $seo_content['connectivity'];
		$ga4_start_date = $ga4_data['start_date'];
		$ga4_end_date = $ga4_data['end_date'];
		$ga4_compare_start_date = $ga4_data['compare_start_date'];
		$ga4_compare_end_date = $ga4_data['compare_end_date'];
		$ga4_comparison = $ga4_data['comparison'];
		$ga4_compare_to = $ga4_data['compare_to'];


		return \View::make('vendor.campaign_detail.seo-view-more',['campaign_id'=>$campaign_id,'summary'=>$summary,'moz_data'=>$moz_data,'first_moz'=>$first_moz,'dashboardtype'=>$dashboardtype,'selectedSearch'=>$selectedSearch,'selected'=>$selected,'comparison'=>$comparison,'backlink_profile_summary'=>$backlink_profile_summary,'backlink_records'=>$backlink_records,'live_keywords'=>$live_keywords,'role_id'=>$role_id,'getRegions'=>$getRegions,'encrypted_id'=>$encrypted_id,'table_settings'=>$table_settings,'display_type'=>$display_type,'flag'=>$flag,'connectivity'=>$connectivity,'connected'=>$connected,'ga4_start_date'=>$ga4_start_date,'ga4_end_date'=>$ga4_end_date,'ga4_compare_start_date'=>$ga4_compare_start_date,'ga4_compare_end_date'=>$ga4_compare_end_date,'ga4_comparison'=>$ga4_comparison,'ga4_compare_to'=>$ga4_compare_to
		]);
	}

	public function campaign_ppc_content_view($campaign_id=null){

		$types = CampaignDashboard::
		where('status',1)
		->where('request_id',$campaign_id)
		->where('dashboard_id',2)
		->first();

		$dashboardStatus = self::dashboardStatus('PPC',$campaign_id);

		if($types == null){
			$getGoogleAds = null;
			return \View::make('viewkey.dashboards.ppc',compact('types','getGoogleAds','campaign_id','dashboardStatus'));
		}

		$domain_name = '';
		$ppc_content = $this->ppc_content($domain_name,$campaign_id);

		$account_id = $ppc_content['account_id'];		
		$moduleadsStatus = $ppc_content['moduleadsStatus'];		
		$getGoogleAds = $ppc_content['getGoogleAds'];		
		$getAdsAccounts = $ppc_content['getAdsAccounts'];		
		$selectedSearch = $ppc_content['selectedSearch'];		
		$selected_value = $ppc_content['selected_value'];	
		$profile_data = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();		


		return \View::make('viewkey.dashboards.ppc',['campaign_id'=>$campaign_id,'account_id'=>$account_id,'moduleadsStatus'=>$moduleadsStatus,'getGoogleAds'=>$getGoogleAds,'getAdsAccounts'=>$getAdsAccounts,'selectedSearch'=>$selectedSearch,'selected_value'=>$selected_value,'types'=>$types,'dashboardStatus'=>$dashboardStatus,'profile_data'=>$profile_data]);
	}

	public function campaign_ppc_content_viewmore($campaign_id=null){

		$types = CampaignDashboard::
		where('status',1)
		->where('request_id',$campaign_id)
		->where('dashboard_id',2)
		->first();

		if($types == null){
			$getGoogleAds = null;
			return \View::make('viewkey.dashboards.ppc',compact('types','getGoogleAds','campaign_id'));
		}

		$domain_name = '';
		$ppc_content = $this->ppc_content($domain_name,$campaign_id);
		$profile_data = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();	

		$account_id = $ppc_content['account_id'];		
		$moduleadsStatus = $ppc_content['moduleadsStatus'];		
		$getGoogleAds = $ppc_content['getGoogleAds'];		
		$getAdsAccounts = $ppc_content['getAdsAccounts'];		
		$selectedSearch = $ppc_content['selectedSearch'];		
		$selected_value = $ppc_content['selected_value'];		


		return \View::make('viewkey.dashboards.ppcviewmore',['campaign_id'=>$campaign_id,'account_id'=>$account_id,'moduleadsStatus'=>$moduleadsStatus,'getGoogleAds'=>$getGoogleAds,'getAdsAccounts'=>$getAdsAccounts,'selectedSearch'=>$selectedSearch,'selected_value'=>$selected_value,'types'=>$types,'profile_data'=>$profile_data]);
	}

	public function campaign_gmb_content_view($campaign_id){
		die("HERE");
		$dashboardStatus = self::dashboardStatus('GMB',$campaign_id);
		return \View::make('viewkey.dashboards.gmb',['campaign_id'=>$campaign_id,'dashboardStatus'=>$dashboardStatus]);
	}

	
	public function campaign_social_content_view($campaign_id){
		return \View::make('viewkey.dashboards.social',['campaign_id'=>$campaign_id]);
	}

	public static function dashboardStatus($type,$campaign_id)
	{
		$all_dashboards = DashboardType::where('status',1)->where('name',$type)->first();
		
		if($all_dashboards <> null){
			$types = CampaignDashboard::where('dashboard_status',1)
			->where('request_id',$campaign_id)
			->where('dashboard_id',$all_dashboards->id)
			->first();
			if($types <> null){
				return true;
			}else{
				return false;	
			}
		}else{
			return false;
		}
	}

	public function ajax_get_latest_console_data(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		$get_console_details = SemrushUserAccount::select('google_console_id','console_account_id')->where('id',$request->campaign_id)->first();

		$check = SearchConsoleUrl::checkConsoleData($request->campaign_id,$user_id,$get_console_details->google_console_id,$get_console_details->console_account_id);


		if(isset($check['status'])  && ($check['status'] == 0)){

			Error::updateOrCreate(
				['request_id' => $request->campaign_id,'module'=> 2],
				['response'=> json_encode($check),'request_id' => $request->campaign_id,'module'=> 2]
			);

			$response['status'] = 'google-error'; 
			$response['message'] = $check['message'];
			return response()->json($response);
		}
		else{
			$log_data = SearchConsoleUsers::log_console_data($request->campaign_id);
			if(isset($log_data['status']) && $log_data['status'] == 0){
				$response['status'] = 'error';
				$response['message'] = $log_data['message'];
			}else{
				$ifErrorExists = Error::where('module',2)->where('request_id',$request->campaign_id)->first();
				if($ifErrorExists){
					Error::where('id',$ifErrorExists->id)->delete();
				}
				GoogleUpdate::updateTiming($request->campaign_id,'search_console','sc_type','2');
				$response['status'] = 'success';
			}
			return response()->json($response);
		}
	}

	public function ajax_get_latest_organic_traffic_trend(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		$get_analytics_details = SemrushUserAccount::select('google_account_id','google_analytics_id','google_property_id','google_profile_id')->where('id',$request->campaign_id)->first();

		
		if(isset($get_analytics_details) && !empty($get_analytics_details)){
			$check = GoogleAnalyticsUsers::checkAnalyticsData($request->campaign_id,$user_id,$get_analytics_details->google_account_id,$get_analytics_details->google_analytics_id,$get_analytics_details->google_property_id,$get_analytics_details->google_profile_id);

			if(isset($check['status'])  && ($check['status'] == 0)){
				Error::updateOrCreate(
					['request_id' => $request->campaign_id,'module'=> 1],
					['response'=> json_encode($check),'request_id' => $request->campaign_id,'module'=> 1]
				);
				$response['status'] = 'google-error'; 
				if(!empty($check['message']['error']['code'])){
					if(isset($check['message']['error']['message'])){
						$response['message'] =$check['message']['error']['message'];
					}else{
						$response['message'] = $check['message']['errors'][0]['message'];
					}
				}else{
					$response['message'] = $check['message'];
				}
				return response()->json($response);

				
			// 	$response = SemrushUserAccount::display_google_errorMessages(1,$request->campaign_id);
			// return response()->json($response);
			}else{
				$log_data = GoogleAnalyticsUsers::log_analytics_data($request->campaign_id);

				if(isset($log_data['status']) && $log_data['status'] == 0){
					$response['status'] = 'error';
					$response['message'] = $log_data['message'];
				}else{
					$ifErrorExists = Error::where('module',1)->where('request_id',$request->campaign_id)->first();
					if($ifErrorExists){
						Error::where('id',$ifErrorExists->id)->delete();
					}
					GoogleUpdate::updateTiming($request->campaign_id,'analytics','analytics_type','2');
					$response['status'] = 'success';
				}
			}
			return response()->json($response);			
		}
	}

	public function ajax_get_google_updated_time (Request $request){
		if(($request->moduleType != '') && ($request->moduleType == 'search_console')){
			$result  = GoogleUpdate::select('search_console')->where('request_id',$request->request_id)->first();
		}else if(($request->moduleType != '') && ($request->moduleType == 'analytics')){
			$result  = GoogleUpdate::select('analytics')->where('request_id',$request->request_id)->first();
		}elseif(($request->moduleType != '') && ($request->moduleType == 'gmb')){
			$result  = GoogleUpdate::select('gmb')->where('request_id',$request->request_id)->first();
		}elseif(($request->moduleType != '') && ($request->moduleType == 'adwords')){
			$result  = GoogleUpdate::select('adwords')->where('request_id',$request->request_id)->first();
		}elseif(($request->moduleType != '') && ($request->moduleType == 'ga4')){
			$result  = GoogleUpdate::select('ga4')->where('request_id',$request->request_id)->first();
		}elseif(($request->moduleType != '') && ($request->moduleType == 'facebook')){
			$result  = GoogleUpdate::select('facebook')->where('request_id',$request->request_id)->first();
		}else{
			$result  = GoogleUpdate::where('request_id',$request->request_id)->first();
		}
		if(isset($result) && !empty($result)){
			$response['status'] = '1'; 
			
			if($result->search_console != null || $result->search_console != ''){
				$search_console = KeywordPosition::calculate_time_span($result->search_console);
				$response['search_console_time'] 	= "Last Updated: ".$search_console." (".date('M d, Y',strtotime($result->search_console)).")" ;
			}else{
				$response['search_console_time'] 	= '' ;
			}

			if($result->analytics != null || $result->analytics != ''){
				$analytics = KeywordPosition::calculate_time_span($result->analytics);
				$response['analytics_time'] 	= "Last Updated: ".$analytics." (".date('M d, Y',strtotime($result->analytics)).")" ;
			}else{
				$response['analytics_time'] 	= '' ;
			}

			if($result->gmb != null || $result->gmb != ''){
				$gmb = KeywordPosition::calculate_time_span($result->gmb);
				$response['gmb_time'] 	= "Last Updated: ".$gmb." (".date('M d, Y',strtotime($result->gmb)).")" ;
			}else{
				$response['gmb_time'] 	= '' ;
			}
			
			if($result->adwords != null || $result->adwords != ''){
				$adwords = KeywordPosition::calculate_time_span($result->adwords);
				$response['adwords_time'] 	= "Last Updated: ".$adwords." (".date('M d, Y',strtotime($result->adwords)).")" ;
			}else{
				$response['adwords_time'] 	= '' ;
			}

			if($result->ga4 != null || $result->ga4 != ''){
				$ga4 = KeywordPosition::calculate_time_span($result->ga4);
				$response['ga4_time'] 	= "Last Updated: ".$ga4." (".date('M d, Y',strtotime($result->ga4)).")" ;
			}else{
				$response['ga4_time'] 	= '' ;
			}

			if($result->facebook != null || $result->facebook != ''){
				$facebook = KeywordPosition::calculate_time_span($result->facebook);
				$response['facebook_time'] 	= $facebook.'_ ('.date('M d, Y',strtotime($result->facebook)).")";
			}else{
				$response['facebook_time'] 	= '' ;
			}
			
			
		}else{
			$response['status'] = '0'; 
		}
		return response()->json($response);
	}

	public function ajax_get_summary_data(Request $request){
		$response = array();
		$summary = SeoAnalyticsEditSection::select('display','edit_section')->where('request_id', $request->campaign_id)->first();
		
		if(!empty($summary)){
			if($summary->display == 1){
				$response['status'] = 1;
				$response['message'] = $summary->edit_section;
			}else{
				$response['status'] = 2; // if display off 
			}
		}else{
			$response['status'] = 0;
			$response['message'] = '';
		}
		return response()->json($response);
	}

	public function ajax_get_page_authority_stats(Request $request){
		$response = array();
		$current_data = Moz::where('request_id',$request->campaign_id)->latest()->first();
		$since_start = Moz::where('request_id',$request->campaign_id)->orderBy('id','asc')->first();

		if(!empty($current_data) && !empty($since_start)){
			$page_authority = $current_data->page_authority;
			$page_authority_point = $current_data->page_authority - $since_start->page_authority;
		}elseif(!empty($current_data) && empty($since_start)){
			$page_authority = $current_data->page_authority;
			$page_authority_point = $current_data->page_authority;
		}elseif(empty($current_data) && !empty($since_start)){
			$page_authority = $current_data->page_authority;
			$page_authority_point = 0 - $since_start->page_authority;
		}else{
			$page_authority = '??';
			$page_authority_point =  0;
		}

		$replace_page_string = $page_authority_point; 
		$page_color = ''; $page_arrow = '';

		if($page_authority_point > 0 ){
			$replace_page_string = $page_authority_point; 
			$page_color = 'green'; $page_arrow = 'arrow-up';
		}elseif($page_authority_point < 0 ){
			$page_string = (string)$page_authority_point;
			$replace_page_string = str_replace('-', '', $page_string);
			$page_color = 'red'; $page_arrow = 'arrow-down';
		}

		$response['page_authority'] = $page_authority;
		$response['page_string'] = $replace_page_string;
		$response['page_color'] = $page_color;
		$response['page_arrow'] = $page_arrow;

		return response()->json($response);	
	}

	public function ajax_get_domain_authority_stats(Request $request){
		$response = array();
		$current_data = Moz::where('request_id',$request->campaign_id)->latest()->first();
		$since_start = Moz::where('request_id',$request->campaign_id)->orderBy('id','asc')->first();

		if(!empty($current_data) && !empty($since_start)){
			$domain_authority = $current_data->domain_authority;
			$domain_authority_point = $current_data->domain_authority - $since_start->domain_authority;
		}elseif(!empty($current_data) && empty($since_start)){
			$domain_authority = $current_data->domain_authority;
			$domain_authority_point = $current_data->domain_authority;
		}elseif(empty($current_data) && !empty($since_start)){
			$domain_authority = $current_data->domain_authority;
			$domain_authority_point = 0 - $since_start->domain_authority;
		}else{
			$domain_authority = '??';
			$domain_authority_point =  0;
		}

		$replace_domain_string = $domain_authority_point; 
		$domain_color = ''; $domain_arrow = '';

		if($domain_authority_point > 0 ){
			$replace_domain_string = $domain_authority_point; 
			$domain_color = 'green'; $domain_arrow = 'arrow-up';
		}elseif($domain_authority_point < 0 ){
			$domain_string = (string)$domain_authority_point;
			$replace_domain_string = str_replace('-', '', $domain_string);
			$domain_color = 'red'; $domain_arrow = 'arrow-down';
		}
		
		$response['domain_authority'] = $domain_authority;
		$response['domain_string'] = $replace_domain_string;
		$response['domain_color'] = $domain_color;
		$response['domain_arrow'] = $domain_arrow;

		return response()->json($response);
	}

	public function ajax_get_error_messages_bkp(Request $request){
		$response = '';
		$getErrors = SemrushUserAccount::getErrorMessage($request->moduleType,$request->campaign_id);
		if(!empty($getErrors)){
			$error = json_decode($getErrors->response, true);
			if($error <>null){
				$currentPlayer = $error['message'];
				if($error['status'] == 2){
					$response =  $currentPlayer;
				}else{
					if (array_key_exists('error',$currentPlayer)){
						if (array_key_exists('message',$currentPlayer['error'])){
							$response =  $currentPlayer['error']['message'];
						}elseif(array_key_exists('error_description',$currentPlayer)){
							$response =  $currentPlayer['error_description'];
						}
					}else if (array_key_exists('message',$currentPlayer)){
						$response =  $currentPlayer['message'];
					}
				}
			}
		}
		return response()->json($response);
	}


	public function ajax_get_error_messages(Request $request){
		$response = '';
		$getErrors = SemrushUserAccount::getErrorMessage($request->moduleType,$request->campaign_id);
		if(!empty($getErrors)){
			$error = json_decode($getErrors->response, true);
			$currentPlayer = $error['message'];
			if(is_array($currentPlayer)){		
				if (array_key_exists('error',$currentPlayer)){
					if (array_key_exists('message',$currentPlayer['error'])){
						$response =  $currentPlayer['error']['message'];
					}elseif(array_key_exists('error_description',$currentPlayer)){
						$response =  $currentPlayer['error_description'];
					}
				}else{
					$response =  $currentPlayer['message'];
				}
			}else{
				$response =  $currentPlayer;
			}
		}
		return response()->json($response);
	}

	public static function detail_search_console($domain_name,$campaign_id){
		$selected = 'Three Month';
		$end_date = date('Y-m-d',strtotime('-1 day'));
		$start_date = date('Y-m-d',strtotime('-3 month',strtotime($end_date)));
		$comparison_period = 'previous_period'; $comparison = 0;
		
		$moduleSearchStatus = ModuleByDateRange::getModuleDateRange($campaign_id,'search_console');

		if(!empty($moduleSearchStatus)){
			$comparison = $moduleSearchStatus->status;
			$comparison_period = $moduleSearchStatus->comparison;
			$duration = $moduleSearchStatus->duration;
			if($duration == 1){
				$selected = 'One Month';
				$start_date = date('Y-m-d', strtotime("-1 month", strtotime($end_date)));
			}elseif($duration == 3){
				$selected = 'Three Month';
				$start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
			}elseif($duration == 6){
				$selected = 'Six Month';
				$start_date = date('Y-m-d', strtotime("-6 month", strtotime($end_date)));
			}elseif($duration == 9){
				$selected = 'Nine Month';
				$start_date = date('Y-m-d', strtotime("-9 month", strtotime($end_date)));
			}elseif($duration == 12){
				$selected = 'One Year';
				$start_date = date('Y-m-d', strtotime("-1 year", strtotime($end_date)));
			}elseif($duration == 24){
				$selected = 'Two Year';
				$start_date = date('Y-m-d', strtotime("-2 year", strtotime($end_date)));
			}
		}

		if($comparison_period === 'previous_period'){
			$calculated_duration = ModuleByDateRange::calculate_days($start_date,$end_date);
			$previous_period_dates = SearchConsoleUsers::calculate_previous_period($start_date,$calculated_duration);
		}else{
			$previous_period_dates = SearchConsoleUsers::calculate_previous_year($start_date,$end_date);	
		}
		$prev_start_date = $previous_period_dates['previous_start_date'];
		$prev_end_date = $previous_period_dates['previous_end_date'];

		
		$final = array(
			'duration' => $selected,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'compare_start_date' => $prev_start_date,
			'compare_end_date' => $prev_end_date,
			'comparison' => $comparison,
			'compare_to' => $comparison_period
		);

		return $final;
	}

	public static function detail_google_analytics4($domain_name,$campaign_id){
		$selected = 'Three Month';
		$end_date = date('Y-m-d',strtotime('-1 day'));
		$start_date = date('Y-m-d',strtotime('-3 month',strtotime($end_date)));
		$comparison_period = 'previous_period'; $comparison = 0;
		
		$moduleSearchStatus = ModuleByDateRange::getModuleDateRange($campaign_id,'ga4');

		if(!empty($moduleSearchStatus)){
			$comparison = $moduleSearchStatus->status;
			$comparison_period = $moduleSearchStatus->comparison;
			$duration = $moduleSearchStatus->duration;
			if($duration == 1){
				$selected = 'One Month';
				$start_date = date('Y-m-d', strtotime("-1 month", strtotime($end_date)));
			}elseif($duration == 3){
				$selected = 'Three Month';
				$start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
			}elseif($duration == 6){
				$selected = 'Six Month';
				$start_date = date('Y-m-d', strtotime("-6 month", strtotime($end_date)));
			}elseif($duration == 9){
				$selected = 'Nine Month';
				$start_date = date('Y-m-d', strtotime("-9 month", strtotime($end_date)));
			}elseif($duration == 12){
				$selected = 'One Year';
				$start_date = date('Y-m-d', strtotime("-1 year", strtotime($end_date)));
			}elseif($duration == 24){
				$selected = 'Two Year';
				$start_date = date('Y-m-d', strtotime("-2 year", strtotime($end_date)));
			}
		}

		if($comparison_period === 'previous_period'){
			$calculated_duration = ModuleByDateRange::calculate_days($start_date,$end_date);
			$previous_period_dates = SearchConsoleUsers::calculate_previous_period($start_date,$calculated_duration);
		}else{
			$previous_period_dates = SearchConsoleUsers::calculate_previous_year($start_date,$end_date);	
		}
		$prev_start_date = $previous_period_dates['previous_start_date'];
		$prev_end_date = $previous_period_dates['previous_end_date'];

		
		$final = array(
			'duration' => $selected,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'compare_start_date' => $prev_start_date,
			'compare_end_date' => $prev_end_date,
			'comparison' => $comparison,
			'compare_to' => $comparison_period
		);	

		return $final;
	}

}