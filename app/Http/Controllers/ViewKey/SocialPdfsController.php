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
use App\ApiBalance;
use App\LiveKeywordSetting;
use App\Http\Controllers\Vendor\CampaignDetailController;
use App\Http\Controllers\Vendor\LiveKeywordController;
use Http;
use App\Traits\ClientAuth;

use App\GmbLocation;
use App\AuditTask;
use App\SiteAudit;
use App\SiteAuditSummary;


class SocialPdfsController extends Controller
{

	public function index($key = null){
		$selectedTab =  request()->segment(2);
		$encription = base64_decode($key);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];

		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
		$users = User::where('id',$user_id)->first();
		$all_dashboards = DashboardType::where('status',1)->pluck('name','id')->all();
		$baseUrl =  'https://' . $users->company_name . '.' . \config('app.DOMAIN_NAME');
		$domain_name = $users->company_name;
		$share_key = $data->share_key;
		
		$types = CampaignDashboard::where('user_id',$user_id)
		->where('status',1)
		->where('request_id',$campaign_id)
		->orderBy('order_status','asc')
		->orderBy('dashboard_id','asc')
		->pluck('dashboard_id')
		->all();
		$type='Facebook Report';
		
		return view('viewkey.pdf.social',compact('user_id','campaign_id','key','all_dashboards','types','selectedTab','data','type','share_key'));
	}


}