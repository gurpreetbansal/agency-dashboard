<?php

namespace App\Http\Controllers\ViewKey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\SemrushUserAccount;
use Crypt;
use App\User;
use App\CampaignDashboard;
use App\DashboardType;
use App\ModuleByDateRange;
use App\ProjectCompareGraph;
use App\BacklinkSummary;
use App\LiveKeywordSetting;
use App\Http\Controllers\Vendor\CampaignDetailController;
use App\SemrushBacklinkSummary;
use App\SiteAudit;
use App\SiteAuditSummary;

class DashboardController extends Controller
{
	public function index($keyenc = null){
		//dd($keyenc);

		$encription = base64_decode($keyenc);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];
		$current_time = $encrypted_id[2];

		$data = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();


		if($data->share_key !== $keyenc){
			return abort(404);
		}

		$check = User::check_subscription($user_id); 
        if($check == 'expired'){
    	   return view('viewkey.v1.expired_subscription',['user_id'=>$user_id,'campaign_id'=>$campaign_id]);
        }

		
		if($data->status != 0){
			return view('errors.archived_404');
		}
		$users = User::where('id',$user_id)->first();
		
		$all_dashboards = DashboardType::where('status',1)->pluck('name','id')->all();
		$baseUrl =  'https://' . $users->company_name . '.' . \config('app.DOMAIN_NAME');
		$domain_name = $users->company_name;

		$types = CampaignDashboard::
			where('user_id',$user_id)
			->where('dashboard_status',1)
			->where('request_id',$campaign_id)
			->orderBy('order_status','asc')
			->orderBy('dashboard_id','asc')
			->pluck('dashboard_id')
			->all();

		$audits = SiteAuditSummary::where('user_id',$user_id)->where('campaign_id',$campaign_id)->first();

		if($types[0] == 1){
			$table_settings = LiveKeywordSetting::where('viewkey',0)->where('request_id',$campaign_id)->pluck('heading')->all();
			$seo_content = CampaignDetailController::seo_content($domain_name,$campaign_id);
			$search_console = CampaignDetailController::detail_search_console($domain_name,$campaign_id);
			$ga4_data = CampaignDetailController::detail_google_analytics4($domain_name,$campaign_id);

			$compactData['ga4_selected'] = $ga4_data['duration'];
			$compactData['ga4_start_date'] = $ga4_data['start_date'];
			$compactData['ga4_end_date'] = $ga4_data['end_date'];
			$compactData['ga4_compare_start_date'] = $ga4_data['compare_start_date'];
			$compactData['ga4_compare_end_date'] = $ga4_data['compare_end_date'];
			$compactData['ga4_comparison'] = $ga4_data['comparison'];
			$compactData['ga4_compare_to'] = $ga4_data['compare_to'];

			$compactData['summary'] = $seo_content['summary'];
			$compactData['selectedSearch'] = $seo_content['selectedSearch'];
			$compactData['selected_ua'] = $seo_content['selected'];
			$compactData['comparison'] = $seo_content['comparison'];
			$compactData['backlink_profile_summary'] = $seo_content['backlink_profile_summary'];
			$compactData['moz_data'] = $seo_content['moz_data'];
			$compactData['first_moz'] = $seo_content['first_moz'];
			$compactData['display_type'] = $seo_content['display_type'];
			$compactData['table_settings'] = $table_settings;
			$compactData['flag'] = $seo_content['flag'];
			$compactData['selected'] = $search_console['duration'];
			$compactData['start_date'] = $search_console['start_date'];
			$compactData['end_date'] = $search_console['end_date'];
			$compactData['compare_start_date'] = $search_console['compare_start_date'];
			$compactData['compare_end_date'] = $search_console['compare_end_date'];
			$compactData['comparison'] = $search_console['comparison'];
			$compactData['compare_to'] = $search_console['compare_to'];
			$compactData['connected'] = $seo_content['connected'];
			$compactData['connectivity'] = $seo_content['connectivity'];
		}
		
		if($types[0] == 2){
			$ppc_content = CampaignDetailController::ppc_content($domain_name,$campaign_id);
			$compactData['getGoogleAds'] = $ppc_content['getGoogleAds'];	
			$compactData['account_id'] = $ppc_content['account_id'];	
			$compactData['selectedSearch'] = $ppc_content['selectedSearch'];	
		}

		if($types[0] == 3){
			$gmb_content = CampaignDetailController::gmb_content('',$campaign_id);
			$compactData['data'] = $gmb_content['gtUser'];		
			$compactData['selected_customer_search'] = $gmb_content['selected_customer_search'];	
			$compactData['selected_customer_view'] = $gmb_content['selected_customer_view'];	
			$compactData['selected_customer_action'] = $gmb_content['selected_customer_action'];	
			$compactData['selected_direction_request'] = $gmb_content['selected_direction_request'];	
			$compactData['selected_phone_calls'] = $gmb_content['selected_phone_calls'];	
			$compactData['selected_photo_views'] = $gmb_content['selected_photo_views'];
		}

		if($types[0] == 4){
			$compactData['gtUser'] = $data;
			// $compactData['profile_data'] = $compactData['gtUser'];
		}

		$compactData['dashboardStatus'] = true;
		$compactData['keyenc'] = $keyenc;
		$compactData['campaign_id'] = $campaign_id;
		$compactData['user_id'] = $user_id;
		$compactData['all_dashboards'] = $all_dashboards;
		$compactData['types'] = $types;
		$compactData['audits'] = $audits;
		$compactData['profile_data'] = $data;
		
		//dd($compactData);

		return view('viewkey.v1.project_detail',$compactData);
		// return view('viewkey.v1.project_detail',compact('user_id','campaign_id','keyenc','all_dashboards','types','data','users','baseUrl','summary','selectedSearch','selected','comparison','backlink_profile_summary','moz_data','first_moz','getGoogleAds','account_id'));

	}

	public function sidebar($key=null,$dashtype=null,$active=null)
	{
		
		$encription = base64_decode($key);
		$encrypted_id = explode('-|-',$encription);
		$user_id = $encrypted_id[0];
		$campaign_id = $encrypted_id[1];
		return view('includes.viewkey.sidebar_menu',compact('user_id','campaign_id','key','dashtype','active'));
	}

	public function tabs($key='')
	{
		$encription = base64_decode($key);
		$encrypted_id = explode('-|-',$encription);
		$user_id = $encrypted_id[0];
		$campaign_id = $encrypted_id[1];

		return view('includes.viewkey.tabs',compact('user_id','campaign_id','key'));
	}

	public function breadcrumb($key='')
	{
		$encription = base64_decode($key);
		$encrypted_id = explode('-|-',$encription);
		$user_id = $encrypted_id[0];
		$campaign_id = $encrypted_id[1];

		return view('includes.viewkey.sidebar_menu',compact('user_id','campaign_id','key'));
	}

	public function seoVisibility($key = null){
		$campaign_id = $key;
		$profile_data = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();
		$search_console = CampaignDetailController::detail_search_console('',$campaign_id);
		$selected = $search_console['duration'];
		$start_date = $search_console['start_date'];
		$end_date = $search_console['end_date'];
		$compare_start_date = $search_console['compare_start_date'];
		$compare_end_date = $search_console['compare_end_date'];
		$comparison = $search_console['comparison'];
		$compare_to = $search_console['compare_to'];

		
		
		return view('viewkey.dashboards.visibility',compact('key','campaign_id','profile_data','selected','start_date','end_date','compare_start_date','compare_end_date','comparison','compare_to'));
	}

	public function seoRankings($key = null){
		$campaign_id = $key;
		$table_settings = LiveKeywordSetting::where('viewkey',0)->where('request_id',$campaign_id)->pluck('heading')->all();
		return view('viewkey.dashboards.rankings',compact('key','campaign_id','table_settings'));
	}

	public function seoTraffic($key = null){
		$campaign_id = $key;
		$comparison = 0; $selected = 3;
		$display_type = 'day';
		$moduleTrafficStatus = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

		if(!empty($moduleTrafficStatus)){
			$selected = $moduleTrafficStatus->duration;
			$display_type = ($moduleTrafficStatus->display_type)?:'day';
		}

		$AnalyticsCompare = ProjectCompareGraph::where('request_id',$campaign_id)->first();
		if(!empty($AnalyticsCompare)){
			$comparison = $AnalyticsCompare->compare_status;
		}

		$profile_data = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();
		$connected = false; $connectivity = ['ua' => false, 'ga4' => false];
        if($profile_data->google_analytics_id !== null && $profile_data->google_analytics_id !== ''){
            $connected = true; $connectivity['ua'] = true;
        }
        if($profile_data->ga4_email_id !== null && $profile_data->ga4_email_id !== ''){
            $connected = true; $connectivity['ga4'] = true;
        }

        $users = User::where('id',$profile_data->user_id)->first();
		$domain_name = $users->company_name;

		$ga4_data = CampaignDetailController::detail_google_analytics4($domain_name,$campaign_id);

		$ga4_selected = $ga4_data['duration'];
		$ga4_start_date = $ga4_data['start_date'];
		$ga4_end_date = $ga4_data['end_date'];
		$ga4_compare_start_date = $ga4_data['compare_start_date'];
		$ga4_compare_end_date = $ga4_data['compare_end_date'];
		$ga4_comparison = $ga4_data['comparison'];
		$ga4_compare_to = $ga4_data['compare_to'];

		return view('viewkey.dashboards.traffic',compact('key','campaign_id','selected','comparison','display_type','profile_data','connected','connectivity','ga4_selected','ga4_start_date','ga4_end_date','ga4_compare_start_date','ga4_compare_end_date','ga4_comparison','ga4_compare_to'));
	}

	public function seoBacklinks($key = null){
		$campaign_id = $key;
		$backlink_profile_summary = SemrushBacklinkSummary::where('request_id',$campaign_id)->whereDate('created_at','<=',date('Y-m-d'))->orderBy('id','desc')->first();
	    $flag = 0;
	    if(!isset($backlink_profile_summary) && $backlink_profile_summary ===  null){
	      $backlink_profile_summary = BacklinkSummary::where('request_id',$campaign_id)->whereDate('created_at','<=',date('Y-m-d'))->orderBy('id','desc')->first();
	      $flag = 1;
	    }
		return view('viewkey.dashboards.backlinks',compact('key','campaign_id','backlink_profile_summary','flag'));
	}
	public function seoGoals($key = null){
		$campaign_id = $key;
		$comparison = 0; $selected = 3;
		$display_type = 'day';
		$moduleTrafficStatus = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');
		$data = SemrushUserAccount::select('ecommerce_goals')->where('id',$campaign_id)->first();

		if(!empty($moduleTrafficStatus)){
			$selected = $moduleTrafficStatus->duration;
			$display_type = ($moduleTrafficStatus->display_type)?:'day';
		}

		$AnalyticsCompare = ProjectCompareGraph::where('request_id',$campaign_id)->first();
		if(!empty($AnalyticsCompare)){
			$comparison = $AnalyticsCompare->compare_status;
		}

		$profile_data = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();
		$connected = false; $connectivity = ['ua' => false, 'ga4' => false];
        if($profile_data->google_analytics_id !== null && $profile_data->google_analytics_id !== ''){
            $connected = true; $connectivity['ua'] = true;
        }
        if($profile_data->ga4_email_id !== null && $profile_data->ga4_email_id !== ''){
            $connected = true; $connectivity['ga4'] = true;
        }

        $users = User::where('id',$profile_data->user_id)->first();
		$domain_name = $users->company_name;

		$ga4_data = CampaignDetailController::detail_google_analytics4($domain_name,$campaign_id);

		$ga4_selected = $ga4_data['duration'];
		$ga4_start_date = $ga4_data['start_date'];
		$ga4_end_date = $ga4_data['end_date'];
		$ga4_compare_start_date = $ga4_data['compare_start_date'];
		$ga4_compare_end_date = $ga4_data['compare_end_date'];
		$ga4_comparison = $ga4_data['comparison'];
		$ga4_compare_to = $ga4_data['compare_to'];

		return view('viewkey.dashboards.goals',compact('key','campaign_id','selected','comparison','display_type','data','profile_data','connected','connectivity','ga4_selected','ga4_start_date','ga4_end_date','ga4_compare_start_date','ga4_compare_end_date','ga4_comparison','ga4_compare_to'));
	}

	public function seoActivity($key = null){
		$campaign_id = $key;
		return view('viewkey.dashboards.activity',compact('campaign_id','key'));
	}

	public function seoAudit($key = null){
		$campaign_id = $key;
		$project_detail = SemrushUserAccount::where('id',$campaign_id)->first();
		return view('viewkey.site_audit.audit',compact('campaign_id','key','project_detail'));
	}

	public function seoAuditPages($key = null){

		$campaign_id = $key;
		$filter = '';
		$project_detail = SemrushUserAccount::where('id',$campaign_id)->first();
		return view('viewkey.site_audit.audit-pages',compact('campaign_id','key','project_detail','filter'));
	}

	public function seoAuditDetails($key = null,$page = null){
		$campaign_id = $key;
		$project_detail = SemrushUserAccount::where('id',$campaign_id)->first();
		return view('viewkey.site_audit.audit-details',compact('campaign_id','key','page','project_detail'));
	}

	public function campaign_gmb_content($key = null){	

		
		$campaign_id = $key;	
		$gmb_content = CampaignDetailController::gmb_content('',$campaign_id);	
		$gtUser = $gmb_content['gtUser'];			
		$data = $gmb_content['gtUser'];			
		$selected_customer_search = $gmb_content['selected_customer_search'];		
		$selected_customer_view = $gmb_content['selected_customer_view'];		
		$selected_customer_action = $gmb_content['selected_customer_action'];		
		$selected_direction_request = $gmb_content['selected_direction_request'];		
		$selected_phone_calls = $gmb_content['selected_phone_calls'];		
		$selected_photo_views = $gmb_content['selected_photo_views'];		

		$dashboardStatus = CampaignDetailController::dashboardStatus('GMB',$campaign_id);

		$profile_data = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();
			
		return view('viewkey.dashboards.gmb',compact('key','campaign_id','gtUser','selected_customer_search','selected_customer_view','selected_customer_action','selected_direction_request','selected_phone_calls','selected_photo_views','dashboardStatus','data','profile_data'));	
	}

	public function campaign_social_content($key = null){
		$campaign_id = $key;
		return view('viewkey.dashboards.social',compact('key','campaign_id'));
	}

	public function ppcCampaign($key = null){
		$campaign_id = $key;
		return view('viewkey.dashboards.campaign',compact('key','campaign_id'));
	}

	public function ppcKeywords($key = null){
		$campaign_id = $key;
		return view('viewkey.dashboards.keywords',compact('key','campaign_id'));
	}

	public function ppcAds($key = null){
		$campaign_id = $key;
		return view('viewkey.dashboards.ads',compact('key','campaign_id'));
	}

	public function ppcPerformance($key = null){
		$campaign_id = $key;
		return view('viewkey.dashboards.performance',compact('key','campaign_id'));
	}

	public function seoKeywordExplorer($key = null){
		$user_id = $key;
		return view('viewkey.dashboards.keyword_explorer',compact('user_id','key'));
	}
}