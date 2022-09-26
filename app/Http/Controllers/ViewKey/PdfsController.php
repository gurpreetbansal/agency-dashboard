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

use URL;
use App\GmbLocation;
use App\SiteAudit;
use App\SiteAuditSummary;

class PdfsController extends Controller
{
	use ClientAuth;
	public function index($key = null){


		$encription = base64_decode($key);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];

		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
		$users = User::where('id',$user_id)->first();
		$all_dashboards = DashboardType::where('status',1)->pluck('name','id')->all();
		$baseUrl =  'https://' . $users->company_name . '.' . \config('app.DOMAIN_NAME');
		$domain_name = $users->company_name;
		$seo_content = CampaignDetailController::seo_content($domain_name,$campaign_id);
		$ppc_content = CampaignDetailController::ppc_content($domain_name,$campaign_id);


		$summary = $seo_content['summary'];
		$selectedSearch = $seo_content['selectedSearch'];
		$selected = $seo_content['selected'];
		$comparison = $seo_content['comparison'];
		$backlink_profile_summary = $seo_content['backlink_profile_summary'];
		$moz_data = $seo_content['moz_data'];
		$first_moz = $seo_content['first_moz'];
		$getGoogleAds = $ppc_content['getGoogleAds'];
		$account_id = $ppc_content['account_id'];
		$flag = $seo_content['flag'];
		$connected = $seo_content['connected'];
		$connectivity = $seo_content['connectivity'];

		$types = CampaignDashboard::
			where('user_id',$user_id)
			->where('status',1)
			->where('request_id',$campaign_id)
			->orderBy('order_status','asc')
			->orderBy('dashboard_id','asc')
			->pluck('dashboard_id')
			->all();

		$role_id = $users->role_id;

		$live_keywords = LiveKeywordController::ajax_live_keyword_listpdf('All',$campaign_id,'currentPosition','asc','','');
		$table_settings = LiveKeywordSetting::where('pdf',0)->where('request_id',$campaign_id)->pluck('heading')->all();
		$type = 'Google SEO Report';

		$share_key = $data->share_key;
		
		return view('viewkey.pdf.dashboard',compact('user_id','campaign_id','key','all_dashboards','types','data','users','baseUrl','summary','selectedSearch','selected','comparison','backlink_profile_summary','moz_data','first_moz','getGoogleAds','account_id','live_keywords','table_settings','type','role_id','flag', 'share_key','connected','connectivity'));

	}


	public function livekeyword($key = null){


		$encription = base64_decode($key);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];

		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
		$users = User::where('id',$user_id)->first();
		$all_dashboards = DashboardType::where('status',1)->pluck('name','id')->all();
		$baseUrl =  'https://' . $users->company_name . '.' . \config('app.DOMAIN_NAME');
		$domain_name = $users->company_name;
		$seo_content = CampaignDetailController::seo_content($domain_name,$campaign_id);
		$ppc_content = CampaignDetailController::ppc_content($domain_name,$campaign_id);


		$summary = $seo_content['summary'];
		$selectedSearch = $seo_content['selectedSearch'];
		$selected = $seo_content['selected'];
		$comparison = $seo_content['comparison'];
		$backlink_profile_summary = $seo_content['backlink_profile_summary'];
		$moz_data = $seo_content['moz_data'];
		$first_moz = $seo_content['first_moz'];
		$getGoogleAds = $ppc_content['getGoogleAds'];
		$account_id = $ppc_content['account_id'];

		$types = CampaignDashboard::
			where('user_id',$user_id)
			->where('status',1)
			->where('request_id',$campaign_id)
			->orderBy('order_status','asc')
			->orderBy('dashboard_id','asc')
			->pluck('dashboard_id')
			->all();


		$live_keywords = LiveKeywordController::ajax_live_keyword_listpdf('All',$campaign_id,'currentPosition','asc','','');

		$live_stats = LiveKeywordController::live_keyword_stats($campaign_id);

		// dd($live_stats);
		
		$table_settings = LiveKeywordSetting::where('pdf',0)->where('request_id',$campaign_id)->pluck('heading')->all();
		$type = 'Keyword Report';
		
		return view('viewkey.pdf.livekeyword',compact('user_id','campaign_id','key','all_dashboards','types','data','users','baseUrl','summary','selectedSearch','selected','comparison','backlink_profile_summary','moz_data','first_moz','getGoogleAds','account_id','live_keywords','live_stats','table_settings','type'));

	}
	
	public function ppcindex($key = null){


		$encription = base64_decode($key);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];

		//dd($key);
		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
		$users = User::where('id',$user_id)->first();
		$all_dashboards = DashboardType::where('status',1)->pluck('name','id')->all();
		$baseUrl =  'https://' . $users->company_name . '.' . \config('app.DOMAIN_NAME');
		$domain_name = $users->company_name;
		$seo_content = CampaignDetailController::seo_content($domain_name,$campaign_id);
		$ppc_content = CampaignDetailController::ppc_content($domain_name,$campaign_id);


		$summary = $seo_content['summary'];
		$selectedSearch = $seo_content['selectedSearch'];
		$selected = $seo_content['selected'];
		$comparison = $seo_content['comparison'];
		$backlink_profile_summary = $seo_content['backlink_profile_summary'];
		$moz_data = $seo_content['moz_data'];
		$getGoogleAds = $ppc_content['getGoogleAds'];
		$account_id = $ppc_content['account_id'];

		$types = CampaignDashboard::
			where('user_id',$user_id)
			->where('status',1)
			->where('request_id',$campaign_id)
			->orderBy('order_status','asc')
			->orderBy('dashboard_id','asc')
			->pluck('dashboard_id')
			->all();

		/*$endpoint = config('app.base_url').'ajax_fetch_ads_campaign_data?account_id='.$account_id.'&campaign_id='.$campaign_id.'&column_name=impressions&order_type=desc&limit=20&page=1';*/
		$endpoint = config('app.base_url').'ppc_summary_conversion_rate_graph?account_id='.$account_id.'&campaign_id='.$campaign_id.'&column_name=impressions&order_type=desc&limit=20&page=1';

			$curl = curl_init();

			curl_setopt_array($curl, array(
			    CURLOPT_URL => $endpoint,
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_ENCODING => "",
			    CURLOPT_TIMEOUT => 30000,
			    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			    CURLOPT_CUSTOMREQUEST => "GET",
			    CURLOPT_HTTPHEADER => array(

			    ),
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			$type = 'Google Ads Report';
		return view('viewkey.pdf.ppc',compact('user_id','campaign_id','key','all_dashboards','types','data','users','baseUrl','summary','selectedSearch','selected','comparison','backlink_profile_summary','moz_data','getGoogleAds','account_id','type'));

	}

	public function crowdpdf(Request $request, $key=null,$type=null)
	{
		try {
			$final ='2,-1';	$pages = array();
			for($i=2;$i<=50;$i++){
				$pages[] = $i;
			}

			$encription = base64_decode($key);
			$encrypted_id = explode('-|-',$encription);
			$campaign_id = $encrypted_id[0];
			$user_id = $encrypted_id[1];

			$data = SemrushUserAccount::with('ProfileInfo')->where('user_id',$user_id)->where('id',$campaign_id)->first();
			if(isset($data->ProfileInfo->white_label_branding) && $data->ProfileInfo->white_label_branding === 1){
				$footer_logo = $this->agency_logo($user_id,$campaign_id,$data->ProfileInfo->agency_logo);
				$footer_name = ($data->ProfileInfo->company_name)?$data->ProfileInfo->company_name:'AgencyDashboard.io';
			}else{
				$footer_logo = 'https://agencydashboard.io/public/front/img/logo-img.png';
				$footer_name = 'AgencyDashboard.io';
			}

			$base_url = \config('app.base_url');
			$final = implode(', ',$pages);

	        $client = new \Pdfcrowd\HtmlToPdfClient("agencydashboard", "5284d0142c46f66189276e801303c514");
	        $client->setPageSize("A4");
	        $client->setOrientation("portrait");
	        $client->setNoMargins(true);	      
	        $client->setHeaderHtml("<div id='first-header' class='extra-text' style='position: fixed; left: 0; top: 0; width: 100%; height: 48px;''><div style='background: url(".$base_url."public/viewkey/images/first-page.png); background-size: 100%; background-position: top left; background-repeat: no-repeat; width: 100%; height: 100%;'></div></div><div id='last-header' class='extra-text'></div>");
			$client->setHeaderHeight("0.5in");
			
			if($type == 'ppc'){
				$font_family = 'Ubuntu, sans-serif';
			}else{
				$font_family = 'Montserrat, sans-serif';
			}
			$client->setFooterHtml('<div style="position:absolute; left:0; right:0; top:0; bottom:0; font-family: '.$font_family.'; color: #5d5d5d; font-size: 10px; background:url('.$base_url.'public/viewkey/images/strip-curve.png), url('.$base_url.'public/viewkey/images/right-strip.png); background-repeat:no-repeat; background-position:-371px 0, 616px 0px; background-size:100%, 100%;  padding:0 30px;  box-sizing:border-box;"> <span style="width:33%;display:inline-block; vertical-align:middle; text-align:left;">The report data is provided by <span style="color:#327aee;">'.$footer_name.'</span></span><span style="width:33%;display:inline-block; vertical-align:middle; text-align:center;">Generated on:  '. date('M d,Y') .' </span> <span style="width:33%;display:inline-block; vertical-align:middle;height:0.3in; padding: 0.05in 0"><img align="right" src="'.$footer_logo.'" style="max-width: 100%; max-height: 100%;" /></span></div>');
			
			$client->setFooterHeight("0.4in");
			$client->setExcludeHeaderOnPages($final);
			$client->setHeaderFooterCssAnnotation(true);
	       	
	       	if($type == 'audit'){
				$filname = SiteAuditSummary::where('id',$campaign_id)->first();
				$projectName = $filname->project;
			}elseif($type == 'audit-detail'){
				$filname = SiteAudit::where('id',$campaign_id)->first();
				if($filname == null){
					$filname = SiteAuditSummary::where('id',$campaign_id)->first();
				}
				$projectName = $filname->project;
			}else{
				$projectName = $data->host_url;
			}

			$filname = $projectName.'-'.date('D-M-Y').'.pdf';
	       	
			if($type == 'livekeyword'){
				$url = \config('app.base_url')."download/livekeyword/".$key;
			}elseif($type == 'ppc'){
				$url = \config('app.base_url')."download/ppc/".$key;
			}elseif($type == 'gmb'){
				$url = \config('app.base_url')."download/gmb/".$key;
			}elseif($type == 'audit'){
				$url = \config('app.base_url')."pdf/audit/summary/".$key;
			}elseif($type == 'audit-detail'){
				$url = \config('app.base_url')."pdf/audit/details/".$key;
			}elseif($type == 'facebook'){
				$url = \config('app.base_url')."download/facebook/".$key;
			}else{
				$url = \config('app.base_url')."download/seo/".$key;
			}
			
	        $pdf = $client->convertUrl($url);

	        $remainingValue = $client->getRemainingCreditCount();
        	ApiBalance::where('name','pdfcrowd')->update(['balance'=>$remainingValue]);

	        // send the result and set HTTP response headers
	        return response($pdf)
	            ->header('Content-Type', 'application/pdf')
	            ->header('Cache-Control', 'no-cache')
	            ->header('Accept-Ranges', 'none')
	            ->header('Content-Disposition', 'attachment; filename="'.$filname.'"');
	    }
	    catch(\Pdfcrowd\Error $why) {
	        // send the error in the HTTP response
	        return response($why->getMessage(), $why->getCode())
	            ->header('Content-Type', 'text/plain');
	    }
	}

	private function agency_logo($user_id,$request_id,$image_name){
		$image_url   =   '';
		if (file_exists(\config('app.FILE_PATH').'public/storage/agency_logo/'.$user_id.'/'.$request_id)) {
			$path  = 'public/storage/agency_logo/'.$user_id.'/'.$request_id.'/';
			if(file_exists($path)){
				$image_url = URL::asset('public/storage/agency_logo/'.$user_id.'/'.$request_id.'/'.$image_name);

			}
		}
		return $image_url;   
	}


	public function gmbindex($key = null){
		$encription = base64_decode($key);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];

		
		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
		$users = User::where('id',$user_id)->first();
		$all_dashboards = DashboardType::where('status',1)->pluck('name','id')->all();

		$types = CampaignDashboard::
		where('user_id',$user_id)
		->where('status',1)
		->where('request_id',$campaign_id)
		->orderBy('order_status','asc')
		->orderBy('dashboard_id','asc')
		->pluck('dashboard_id')
		->all();
		$gmb_location_data = GmbLocation::where('id',$data->gmb_id)->first();
		$direction_request_selected = ModuleByDateRange::where('request_id',$campaign_id)->where('module','gmb_direction_requests')->first();
		if(!empty($direction_request_selected)){
			$selected_direction_request = $direction_request_selected->duration;
		}else{
			$selected_direction_request = 30;
		}
		$type = 'GMB Report';

		return view('viewkey.pdf.gmb_dashboard',compact('user_id','campaign_id','key','all_dashboards','types','data','users','gmb_location_data','selected_direction_request','type'));

	}

	public function site_audit_index($key = null){
		$encription = base64_decode($key);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];
		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
		$users = User::where('id',$user_id)->first();
		$baseUrl =  'https://' . $users->company_name . '.' . \config('app.DOMAIN_NAME');
		$domain_name = $users->company_name;
		$type = 'Site-Audit Report';

		$auditTask = AuditTask::where('campaign_id',$campaign_id)->orderBy('id','DESC')->first();
 
		$taskId = $auditTask->task_id;

		try {
			$client = $this->DFSAuth();
		} catch (RestClientException $e) {
			return json_decode($e->getMessage(), true);
		}

		try {
			$result = array();

			$id = $taskId;
			$result = $client->get('/v3/on_page/summary/' . $id);

		} catch (RestClientException $e) {
			echo "\n";
			print "HTTP code: {$e->getHttpCode()}\n";
			print "Error code: {$e->getCode()}\n";
			print "Message: {$e->getMessage()}\n";
			print  $e->getTraceAsString();
			echo "\n";
		}

		$summaryTask = $result['tasks'][0]['result'][0];
		$issueCount = 0;
		if($summaryTask['domain_info']['checks']['sitemap'] == 0){
			$issueCount++;
		}

		if($summaryTask['domain_info']['checks']['robots_txt'] == 0){
			$issueCount++;
		}
		if($summaryTask['page_metrics']['checks']['no_favicon'] > 0){
			$issueCount++;
		}
		if($summaryTask['page_metrics']['checks']['is_4xx_code'] <> 0){
			$issueCount++;
		}
		if($summaryTask['page_metrics']['checks']['is_http'] <> 0){
			$issueCount++;
		}

		$errorsListing = $this->errorBifurcation($summaryTask);
		$auditLevel = $this->auditLevel();

		$post_array[] = array(
			"id" => $taskId,
			"filters" => [
				["resource_type", "=", "html"],
			],
			"order_by" => ["meta.content.plain_text_word_count,desc"],
			"limit" => 500
		);

		try {
			$result = $client->post('/v3/on_page/pages', $post_array);
		} catch (RestClientException $e) {
			echo "\n";
			print "HTTP code: {$e->getHttpCode()}\n";
			print "Error code: {$e->getCode()}\n";
			print "Message: {$e->getMessage()}\n";
			print  $e->getTraceAsString();
			echo "\n";
		}

		$summaryTaskPages = $result['tasks'][0]['result'][0]['items'];


		return view('viewkey.pdf.audit.overview',compact('user_id','campaign_id','key','data','users','baseUrl','type','summaryTask','issueCount','errorsListing','auditLevel','summaryTaskPages'));
	}


	public function site_audit_detail($key = null,$page = 0){
		$encription = base64_decode($key);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];
		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
		$users = User::where('id',$user_id)->first();
		$baseUrl =  'https://' . $users->company_name . '.' . \config('app.DOMAIN_NAME');
		$domain_name = $users->company_name;
		$type = 'Site-Audit Report';

		$auditTask = AuditTask::where('campaign_id',$campaign_id)->orderBy('id','DESC')->orderBy('id','DESC')->first();
		
		$taskId = $auditTask->task_id;

		try {
			$client = $this->DFSAuth();
		} catch (RestClientException $e) {
			return json_decode($e->getMessage(), true);
		}

		$post_array[] = array(
			"id" => $taskId,
			"filters" => [
				["resource_type", "=", "html"],
			],
			"order_by" => ["meta.content.plain_text_word_count,desc"],
		);

		try {

			$result = $client->post('/v3/on_page/pages', $post_array);
		} catch (RestClientException $e) {
			echo "\n";
			print "HTTP code: {$e->getHttpCode()}\n";
			print "Error code: {$e->getCode()}\n";
			print "Message: {$e->getMessage()}\n";
			print  $e->getTraceAsString();
			echo "\n";
		}

		$summaryTask = $result['tasks'][0]['result'][0]['items'][$page];

		$errorsListing = $this->errorBifurcationPages($summaryTask);
		$auditLevel = $this->auditLevel();
		$externalLinks = $this->dfsLinks($taskId,$summaryTask['url'],'external');
		$internalLinks = $this->dfsLinks($taskId,$summaryTask['url'],'internal');
		$images = $this->dfsLinks($taskId,$summaryTask['url'],'image');

		$urlDesktop = env('FILE_PATH')."public/audits/".$campaign_id.'/desktop.json'; 
	    if(file_exists($urlDesktop)){
	     	$dataDesktop = file_get_contents($urlDesktop);
	        $valuesDesktop = json_decode($dataDesktop,true);
	    }else{
	    	$valuesDesktop = array();
	    }

	    $urlMobile = env('FILE_PATH')."public/audits/".$campaign_id.'/mobile.json'; 
	    if(file_exists($urlMobile)){
	     	$dataMobile = file_get_contents($urlMobile);
	        $valuesMobile = json_decode($dataMobile,true);
	    }else{
	    	$valuesMobile = array();
	    } 

		return view('viewkey.pdf.audit.detail',compact('user_id','campaign_id','key','data','users','baseUrl','type','summaryTask','errorsListing','auditLevel','externalLinks','internalLinks','images','valuesDesktop','valuesMobile'));
	}


	public function dfsLinks($taskId,$url,$type){

		$client = null;
		$relativeUrl = parse_url($url,PHP_URL_PATH);
		try {
			$client = $this->DFSAuth();
		} catch (RestClientException $e) {
			return json_decode($e->getMessage(), true);
		}

		if($type == 'internal'){
			$filter = [
				["dofollow", "=", true],
				"and",
				["direction", "=", "internal"],
				
				
			];
		}else if($type == 'external'){
			$filter = [
				["dofollow", "=", true],
				"and",
				["direction", "=", "external"]
			];
		}else if($type == 'image'){
			$filter = [
				["dofollow", "=", true],
				"and",
				["type", "=", "image"]
			];
		}else if($type == 'anchor'){
			$filter = [
				["dofollow", "=", true],
				"and",
				["direction", "=", "internal"],
				"and",
				["type", "=", "anchor"]
			];
		}else{
			$filter = [
				["dofollow", "=", true],
				"and",
				["direction", "=", "external"],
				"and",
				["type", "=", "anchor"]
			];
		}
		$post_array = array();
		// simple way to get a result
		$post_array[] = array(
			"id" => $taskId,
			"page_from" => $relativeUrl,
			"filters" => $filter,
			"limit" => 100
		);
		try {
			// POST /v3/on_page/links
			// the full list of possible parameters is available in documentation
			$result = $client->post('/v3/on_page/links', $post_array);
			// do something with post result
		} catch (RestClientException $e) {
			echo "\n";
			print "HTTP code: {$e->getHttpCode()}\n";
			print "Error code: {$e->getCode()}\n";
			print "Message: {$e->getMessage()}\n";
			print  $e->getTraceAsString();
			echo "\n";
		}
		
		return $result['tasks'][0]['result'][0];
	}
	
	public function errorBifurcationPages($errorData = null){

		if($errorData == null){
			return null;
		}

		$errorList = $this->auditErrorKey();
		
		$criticalMetricsResult = array_intersect_key($errorData,$errorList['critical']);
		$criticalMetricsCheckResult = array_intersect_key($errorData['checks'],$errorList['criticalCheck']);
		$criticalResult = array_merge($criticalMetricsResult,$criticalMetricsCheckResult);

		$wraningMetricsResult = array_intersect_key($errorData,$errorList['wraning']);
		$wraningMetricsCheckResult = array_intersect_key($errorData['checks'],$errorList['wraningCheck']);
		$wraningResult = array_merge($wraningMetricsResult,$wraningMetricsCheckResult);

		$notices = array_intersect_key($errorData,$errorList['notices']);
		$noticesCheck = array_intersect_key($errorData['checks'],$errorList['noticesCheck']);
		$noticesResult = array_merge($notices,$noticesCheck);

		$finalArr = [
			'critical' => $criticalResult,
			'warning' => $wraningMetricsResult,
			'notices' => $noticesResult,
		];

		$errorsListing = $errorData;

		$errorList = array();
		foreach($finalArr as $key => $value){
			$newArr = array_filter($value, function ($varOuter,$keyOuter) {
				if($varOuter == true){
					return $keyOuter = $varOuter;
				}
			},ARRAY_FILTER_USE_BOTH);

			$errorList[$key] = $newArr;
		}

		return $errorList;

	}

	public function auditErrorKey(){

		$errors['critical'] = [
			'duplicate_title' => 'duplicate_title',
			'duplicate_description' => 'duplicate_description',
			'duplicate_content' => 'duplicate_content',
			'broken_links' => 'broken_links',
			'broken_resources' => 'broken_resources',
		];

		$errors['criticalCheck'] = [
			'canonical' => 'canonical',
			'no_description' => 'no_description',
			'is_http' => 'is_http',
			'low_content_rate' => 'low_content_rate',
			'no_h1_tag' => 'no_h1_tag',
			'recursive_canonical' => 'recursive_canonical',
			'is_broken' => 'is_broken',
			'is_4xx_code' => 'is_4xx_code',
			'is_5xx_code' => 'is_5xx_code',
			'no_title' => 'no_title',
			'canonical_to_broken' => 'canonical_to_broken',
		];

		$errors['wraning'] = [
			//'links_relation_conflict' => 'links_relation_conflict',
			'redirect_loop' => 'redirect_loop',
		];

		$errors['wraningCheck'] = [
			'duplicate_meta_tags' => 'duplicate_meta_tags',
			'frame' => 'frame',
			'irrelevant_description' => 'irrelevant_description',
			'irrelevant_meta_keywords' => 'irrelevant_meta_keywords',
			'title_too_long' => 'title_too_long',
			'no_favicon' => 'no_favicon',
			'no_image_alt' => 'no_image_alt',
			'seo_friendly_url_characters_check' => 'seo_friendly_url_characters_check',
			'seo_friendly_url_keywords_check' => 'seo_friendly_url_keywords_check',
			'no_content_encoding' => 'no_content_encoding',
			'high_waiting_time' => 'high_waiting_time',
			'high_loading_time' => 'high_loading_time',
			'is_redirect' => 'is_redirect',
			'no_doctype' => 'no_doctype',
			'low_character_count' => 'low_character_count',
			'low_readability_rate' => 'low_readability_rate',
			'irrelevant_title' => 'irrelevant_title',
			'deprecated_html_tags' => 'deprecated_html_tags',
			'duplicate_title_tag' => 'duplicate_title_tag',
			'lorem_ipsum' => 'lorem_ipsum',
			'has_misspelling' => 'has_misspelling',
			'canonical_to_redirect' => 'canonical_to_redirect',
			'has_links_to_redirects' => 'has_links_to_redirects',
			'is_orphan_page' => 'is_orphan_page',
			'has_render_blocking_resources' => 'has_render_blocking_resources',
			'redirect_chain' => 'redirect_chain',
			'canonical_chain' => 'canonical_chain',
		];

		$errors['notices'] = [
			'links_external' => 'links_external',
			'links_internal' => 'links_internal'
		];

		$errors['noticesCheck'] = [
			'large_page_size' => 'large_page_size',
			'is_https' => 'is_https',
			'no_image_title' => 'no_image_title',
			'seo_friendly_url' => 'seo_friendly_url',
			'seo_friendly_url_dynamic_check' => 'seo_friendly_url_dynamic_check',
			'seo_friendly_url_relative_length_check' => 'seo_friendly_url_relative_length_check',
			'title_too_short' => 'title_too_short',
			'is_www' => 'is_www',
			'high_content_rate' => 'high_content_rate',
			'high_character_count' => 'high_character_count',
			'flash' => 'flash',
			'has_meta_refresh_redirect' => 'has_meta_refresh_redirect',
			'meta_charset_consistency' => 'meta_charset_consistency',
			'size_greater_than_3mb' => 'size_greater_than_3mb',
			'has_html_doctype' => 'has_html_doctype',
			'https_to_http_links' => 'https_to_http_links',

		];

		return $errors;
	}

	public function errorBifurcation($errorData = null){

		if($errorData == null){
			return null;
		}

		$errorList = $this->auditErrorKey();

		$criticalResult = array_intersect_key($errorData['page_metrics'],$errorList['critical']);
		$criticalCheckResult = array_intersect_key($errorData['page_metrics']['checks'],$errorList['criticalCheck']);

		$criticaldata = array_merge($criticalResult,$criticalCheckResult);

		$wraningResult = array_intersect_key($errorData['page_metrics'],$errorList['wraning']);
		$wraningCheckResult = array_intersect_key($errorData['page_metrics']['checks'],$errorList['wraningCheck']);

		$wraningdata = array_merge($wraningResult,$wraningCheckResult);

		$noticesResult = array_intersect_key($errorData['page_metrics'],$errorList['notices']);
		$noticesCheckResult = array_intersect_key($errorData['page_metrics']['checks'],$errorList['noticesCheck']);

		$noticedata = array_merge($noticesResult,$noticesCheckResult);

		$finalArr = [
			'critical' => $criticaldata,
			'warning' => $wraningdata,
			'notices' => $noticedata,
		];

		return $finalArr;
	}

	public function auditLevel(){

		$auditLevel = [
			'links_external' => 'High External Linking',
			'links_internal' => 'Internal Links',
			'duplicate_title' => 'Duplicate Title',
			'duplicate_description' => 'Duplicate Description',
			'duplicate_content' => 'Duplicate On-page Content',
			'broken_links' => 'Broken links',
			'broken_resources' => 'Broken Resources',
			'links_relation_conflict' => 'Link Relation Conflict ',
			'redirect_loop' => 'Redirect Loops',
			'canonical' => 'Canonical Pages',
			'duplicate_meta_tags' => 'Duplicate Meta Tags',
			'no_description' => 'Description is missing',
			'frame' => 'Frames',
			'large_page_size' => 'Page Size is over 4 MB',
			'irrelevant_description' => 'Irrelevant Descriptions',
			'irrelevant_meta_keywords' => 'Irrelevant Meta Keywords',
			'is_https' => 'Is HTTPS (Secure Pages)',
			'is_http' => 'Is HTTP (Unsecure Pages)',
			'title_too_long' => 'Title Too Long',
			'low_content_rate' => 'Low Content Rate',
			'small_page_size' => 'Small Page Size',
			'no_h1_tag' => 'H1 is empty',
			'recursive_canonical' => 'Recursive Canonical',
			'no_favicon' => 'No Favicon',
			'no_image_alt' => 'Missing Alt text',
			'no_image_title' => 'No Image Title',
			'seo_friendly_url' => 'SEO Friendly URL',
			'seo_friendly_url_characters_check' => 'SEO Friendly URL Character Check',
			'seo_friendly_url_dynamic_check' => 'SEO Friendly URL Dynamic Check',
			'seo_friendly_url_keywords_check' => 'SEO Friendly URL Keyword Check',
			'seo_friendly_url_relative_length_check' => 'SEO Friendly URL Relative Length Check',
			'title_too_short' => 'Title Too Short',
			'no_content_encoding' => 'No Content Encoding',
			'high_waiting_time' => 'High Waiting Time',
			'high_loading_time' => 'Timed Out',
			'is_redirect' => '301 redirects',
			'is_broken' => 'Broken Pages',
			'is_4xx_code' => '4xx client errors',
			'is_5xx_code' => '5xx server errors',
			'is_www' => 'WWW URLs',
			'no_doctype' => 'No Doctype',
			'no_encoding_meta_tag' => 'No Encoding Meta Tags',
			'high_content_rate' => 'High Content Rate',
			'low_character_count' => 'Low Character Count',
			'high_character_count' => 'High Character Count',
			'low_readability_rate' => 'Low Readability Rate',
			'irrelevant_title' => 'Irrelevant Title',
			'deprecated_html_tags' => 'Deprecated HTML Tags',
			'duplicate_title_tag' => 'Duplicate Title Tags',
			'no_title' => 'Title is empty',
			'flash' => 'Flash',
			'lorem_ipsum' => 'Dummy Content',
			'has_misspelling' => 'Has Misspelling',
			'canonical_to_broken' => 'Canonical URLs To Broken Pages',
			'canonical_to_redirect' => 'Canonical URLs to Redirecting Pgaes',
			'has_links_to_redirects' => 'Has Links to Redirect Pages',
			'is_orphan_page' => 'Orphan URLs',
			'has_meta_refresh_redirect' => 'Meta Refresh Redirect',
			'meta_charset_consistency' => 'Meta Charset Consistency',
			'size_greater_than_3mb' => 'Page size is over 2 MB',
			'has_html_doctype' => 'HTML Doctype',
			'https_to_http_links' => 'HTTPS to HTTP redirect',
			'has_render_blocking_resources' => 'Render Blocking Resources',
			'redirect_chain' => 'Redirect Chains',
			'canonical_chain' => 'Canonical Chain',
			'is_link_relation_conflict' => 'Link Relation Conflict',		
		];

		return $auditLevel;

	}

}