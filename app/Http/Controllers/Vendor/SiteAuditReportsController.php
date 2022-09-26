<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;

use App\Traits\ReportTrait;
use App\Traits\SitemapGenerator;

use App\Http\Requests\StoreReportRequest;

use Auth;
use App\User;
use App\SemrushUserAccount;
use App\SiteAudit;
use App\SiteAuditSummary;

use App\AuditErrorList;
use URL;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\TransferStats;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use AlesZatloukal\GoogleSearchApi\GoogleSearchApi;

class SiteAuditReportsController extends Controller {

	// use ReportTrait;

	/*private $crawled;*/
	private $start = 0;

	use SitemapGenerator {
        SitemapGenerator::GenerateSitemap insteadof ReportTrait;
        SitemapGenerator::GenerateSitemap AS Sitemap;
    }

    use ReportTrait {
        ReportTrait::reportStore AS ReportTraitModel;
    }

    // use GoogleUrlListTrait {
    //     GoogleUrlListTrait::GoogleUrlListTrait AS GoogleUrlListTraitModel;
    // }
	
	public function auditOverview(){
		
		return view('vendor.audits.d-audit-overview');
	}

	public function auditDetal(){
		
		return view('vendor.audits.d-audit-detail');
	}

	public function auditPdfOverview(){
		
		return view('viewkey.pdf.audits.d-audit-overview');
	}

	public function auditPdfDetail(){
		
		return view('viewkey.pdf.audits.d-audit-detail');
	}    

	public function saAudit(){
		
		return view('vendor.audits.sa-audit');
	}

	
	public function auditCrowler(Request $request){

		ini_set('max_execution_time', '-1');
        ini_set('memory_limit', '-1');
        // file_put_contents(dirname(__FILE__).'/logs/crowler_'.date('Y-m-d H i s').'.json',print_r(json_encode($request->all(),true),true));
		try {

			$summaryAudit = SiteAuditSummary::where('id',$request->audit_id)->orderBy('id','DESC')->first();
			$request->request->add(['audit_id' => $request->audit_id]);
			$request->request->add(['campaign_id' => $summaryAudit->campaign_id]);
			$request->request->add(['url' => $summaryAudit->url]);
			$request->request->add(['user_id' => $summaryAudit->user_id]);

			$limit = 50;
			if($summaryAudit <> null){
				$limit = $summaryAudit->crowl_pages <> null ? $summaryAudit->crowl_pages : 50;
			}

			SiteAudit::where('audit_id',$summaryAudit->id)->delete();
			sleep(1);
			$this->crawled = [];
			$list = $this->curlPost($request,$limit);
			
			return [
				'status'=>true,
				'list'=>$list,
			];

		} catch (Exception $e) {
			return [
				'status'=>false,
			];
		}		


	}


	public function siteAuditRun(Request $request){

		// file_put_contents(dirname(__FILE__).'/logs/runAudit.txt',print_r($request->all(),true));

		ini_set('max_execution_time', 7200);
		
		$user_id = @$request->user_id;
		$campaign_id = @$request->campaign_id;
		$url = $request->url;

		$limit = 50;
		$companyName = null;
		if($campaign_id <> null){
			$auditLimit = SemrushUserAccount::where('id',$campaign_id)->first();
			$limit = $auditLimit->audit_crawl_pages <> null ? $auditLimit->audit_crawl_pages : 50;
			$companyName = $auditLimit->company_name;
		}
		
		$domain = parse_url(str_replace(['https://www.', 'http://www.'], ['https://', 'http://'], $request->url), PHP_URL_HOST);
		if(!$domain){
			$domain = $url;
		}

		$httpStatus = SiteAudit::getIp($url);

		if($httpStatus['http_code'] == 301 || $httpStatus['http_code'] == 302){
			$url = $httpStatus['redirect_url'];
		}

		if(Auth::user() <> null){
			$user_id = User::get_parent_user_id(Auth::user()->id);
			$request->user_id = $user_id;
		}

		if($campaign_id <> null){
			$summaryAudit = SiteAuditSummary::where('user_id',$user_id)->where('campaign_id',$campaign_id)->where('project',$domain)->orderBy('id','DESC')->first();
		}else{
			$summaryAudit = SiteAuditSummary::where('user_id',$user_id)->where('project',$domain)->orderBy('id','DESC')->first();
		}
		
		$request->url = $url;
		// $limit = 500;
		//$list = $this->GenerateSitemap($request,$limit);
		// $list = $this->curlPost($request,$limit);
	
		if($summaryAudit){
		
			return [
				'status'=>true,
				'availability'=>true,
				'audit_id'=>$summaryAudit->id,
			];
		}else{
			
			$ip = $httpStatus['primary_ip'];

			$sslStatus = SiteAudit::getSslStatus($url);
			
			$isSsl = @$sslStatus == true ? 1:0;
			$isWww = strpos(parse_url($url,PHP_URL_HOST), "www.") === 1 ? 1:0;

			$shareKey = \Uuid::generate()->string;

			$create = SiteAuditSummary::create([
				'user_id'=>$user_id,
				'campaign_id'=>$campaign_id,
				'url'=>$url,
				'project'=>$domain,
				'ip'=>$ip,
				'is_ssl'=>$isSsl,
				'is_www'=>$isWww,
				'share_key'=>$shareKey,
			]);
			sleep(1);
			if($create){
				$audit_id = $create->id;
				
				return [
					'status'=>true,
					'availability'=>false,

					'audit_id'=>$audit_id,
				];
			}else{
				return [
					'status'=>false
				];
			}

		}
	}

	function curlPost(Request $request,$limit){
		
		ini_set('max_execution_time', '-1');
        ini_set('memory_limit', '-1');
        
		$endpoint = 'https://agencydashboard.io/public/crawler/index.php';
		$start = $this->start;
		$end = $limit;
		if($limit > 100){
			$end = $start + 99;
		}

		$requestUrl = $request->url;
		$cURLConnection = curl_init($endpoint);
		curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, ['url' => $request->url, 'start' => $start,'limit' => $end]);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
		$apiResponse = curl_exec($cURLConnection);
		curl_close($cURLConnection);
		
		$urlList = json_decode($apiResponse,true);
		
		if($urlList  !== null){
			if(count($urlList) > 0){
				foreach ($urlList as $key => $uri) {
					if(count($this->crawled) >= $limit){
						continue;
					}

					if (!in_array(rtrim($uri,'/'), $this->matched)){
						array_push($this->crawled, rtrim($uri,'/'));
						$request['url'] = $uri;
						$this->reportStore($request);
					}
					
				}
			}
			if(count($urlList) <> null && count($urlList) > 98){
				$this->start += 100;
				$request['url'] = $requestUrl;
				$this->curlPost($request,$limit);
			}
		}
		
		$list = $this->crawled;
		if(count($this->crawled) < $limit){
			$nextLimit = $limit - count($this->crawled);
			$request['url'] = rtrim($requestUrl,'/');
			$list = $this->GenerateSitemap($request,$nextLimit,$this->crawled);
		}
		
		SiteAuditSummary::updateSummaryScore($request->audit_id,'completed');
		return $list;
	}

	/*function curlPost(Request $request,$limit){
		
		$endpoint = 'https://agencydashboard.io/public/crawler/index.php';
		$cURLConnection = curl_init($endpoint);
		curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, ['url' => $request->url, 'limit' => $limit]);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
		$apiResponse = curl_exec($cURLConnection);
		curl_close($cURLConnection);
		
		$urlList = json_decode($apiResponse,true);
		if($urlList  !== null){
			if(count($urlList) > 0){
				foreach ($urlList as $key => $uri) {
					$request['url'] = $uri;
					$this->reportStore($request);
				}
			}
		}
		SiteAuditSummary::updateSummaryScore($request->audit_id,'completed');
		return $urlList;
		

	}*/

	public function auditLoaderView(){

		return view('vendor.audits.audit-loader-view');
	}

	public function auditLoaderDetails(Request $request,$domain,$id = null){
		if($id == null){
			$id = $domain;
		}
		
		$summaryAuditPages = SiteAudit::where('id',$id)->first();
		$user_id = $summaryAuditPages->summary->user_id;
		$key = base64_encode($summaryAuditPages->id.'-|-'.$user_id.'-|-'. strtotime(date('Y-m-d H:i:s')));
		$pageType = $request->pageType;
		return view('vendor.audits.pages.audit-loader-detail',compact('key','pageType','summaryAuditPages'));
	}

	public function siteAuditDetail(Request $request,$domain,$id = null){
		$limit = 50;
		//$user_id = Auth::user()->id;
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$campaignData = SemrushUserAccount::where('user_id',$user_id)->where('id',$id)->first();
		
		if($campaignData == null && !isset($data) && empty($data)) {
			return abort(404);
		}

		if($campaignData <> null && $campaignData->status == 1){
			return view('vendor.campaign_archived');
		}

		$summaryAudit = SiteAuditSummary::where('user_id',$user_id)->where('project',$campaignData->host_url)->orderBy('id','DESC')->first();
		
		$key = null;
		if($summaryAudit){
			$key = base64_encode($summaryAudit->id.'-|-'.$user_id.'-|-'. strtotime(date('Y-m-d H:i:s')));
		}
		

		return view('vendor.audits.audit-detail',compact('campaignData','summaryAudit','key'));
	}

	public function siteAuditOverView(Request $request){

		$domain = parse_url(str_replace(['https://www.', 'http://www.'], ['https://', 'http://'], $request->domain), PHP_URL_HOST);
		if(!$domain){
			$domain = $url;
		}
		if(Auth()->user() <> null){
			$user_id = Auth()->user()->id;
			$summaryAudit = SiteAuditSummary::where('user_id',$user_id)->where('project',$domain)->orderBy('id','DESC')->first();
		}else{
			$summaryAudit = SiteAuditSummary::where('project',$domain)->orderBy('id','DESC')->first();
			$user_id = $summaryAudit->user_id;
		}
		
		if($summaryAudit){
			$key = base64_encode($summaryAudit->id.'-|-'.$user_id.'-|-'. strtotime(date('Y-m-d H:i:s')));
			$summaryAudit['status'] = true;
			$summaryAudit['pdf_url'] = \config('app.base_url')."download/pdf/".$key.'/audit';
		}else{
			$summaryAudit['status'] = false;
			$summaryAudit['pdf_url'] = 'javascript:;';
		}
		return response()->json($summaryAudit);
	}

	public function siteAuditSummaryUpdate(Request $request,$domain,$id=null){
		
		if($id == null){
			$id = $domain;
		}
		// $user_id = Auth()->user()->id;
		$summaryAudit = SiteAuditSummary::where('id',$id)->orderBy('id','DESC')->first();

		if($summaryAudit <> null){
			$diff_in_minutes = 0;
			$checkUpdate = 0;
			if($summaryAudit->campaign_id == null){
				$summaryAudit->campaign_id = @$request->campaign;
				$checkUpdate++;
			}

			$diff_in_minutes = $summaryAudit->updated_at->diffInMinutes();
			if($diff_in_minutes > 1){
				$summaryAudit->audit_status = 'completed';
				$checkUpdate++;
				
			}

			if($checkUpdate <> 0){
				$summaryAudit->save();
			}
			$user_id = $summaryAudit->user_id;
			
			$summaryAudit->crawledPages = count($summaryAudit->pages);
			$summaryAudit->pdf_key = \config('app.base_url')."download/pdf/".base64_encode($summaryAudit->id.'-|-'.$user_id.'-|-'. strtotime(date('Y-m-d H:i:s'))).'/audit';
		}
		
		return $summaryAudit;
	}

	public function siteAuditSummary(Request $request,$domain,$id=null){
		if($id == null){
			$id = $domain;
		}
		$summaryAudit = SiteAuditSummary::where('id',$id)->orderBy('id','DESC')->first();
		$auditType = $request->auditType;
		$key = base64_encode($summaryAudit->id.'-|-'.@$user_id.'-|-'. strtotime(date('Y-m-d H:i:s')));
		$pdf_url = \config('app.base_url')."download/pdf/".$key.'/audit';
		return view('vendor.audits.audit-summary',compact('summaryAudit','auditType','pdf_url'));
	}

	public function siteAuditList(Request $request,$domain,$id=null){
		$limit = 50;
		if($id == null){
			$id = $domain;
		}
		$summaryAuditPages = SiteAudit::where('audit_id',$id)->paginate($limit);
		$auditType = $request->auditType;
		return view('vendor.audits.audit-pages-list',compact('summaryAuditPages','auditType'));
	}

	public function checkDomainValid(Request $request){
		return $httpStatus = SiteAudit::getIp($request->domain);
	}

	/*******  Page Detail *******/

	public function auditPageDetail(Request $request,$domain,$id){
		// $user_id = Auth()->user()->id;

		$user_id = User::get_parent_user_id(Auth::user()->id);
		$summaryAuditPages = SiteAudit::where('user_id',$user_id)->where('id',$id)->first();

		if(!isset($summaryAuditPages) && empty($summaryAuditPages) && $summaryAuditPages == null){
			return abort(404);
		}
		$key = base64_encode($summaryAuditPages->id.'-|-'.$user_id.'-|-'. strtotime(date('Y-m-d H:i:s')));

		return view('vendor.audits.pages.pages-detail',compact('summaryAuditPages','key'));
	}

	public function auditPageDetailOverview(Request $request,$domain,$id = null){
		if($id == null){
			$id = $domain;
		}
		$summaryAuditPages = SiteAudit::where('id',$id)->first();

		return view('vendor.audits.pages.page-overview',compact('summaryAuditPages'));
	}

	public function auditPageDetailSummary(Request $request,$domain,$id = null){
		if($id == null){
			$id = $domain;
		}
		$summaryAuditPages = SiteAudit::where('id',$id)->first();

		return view('vendor.audits.pages.page-summary',compact('summaryAuditPages'));
	}

	public function auditPageDetailData(Request $request,$domain,$id = null){
		if($id == null){
			$id = $domain;
		}
		$summaryAuditPages = SiteAudit::where('id',$id)->first();

		return view('vendor.audits.pages.page-detail',compact('summaryAuditPages'));
	}

	public function updateAuditPageDetail(Request $request,$domain,$id){

		$summaryAuditPages = SiteAudit::where('id',$id)->first();

		$updateStatus = $this->reportUpdate($request, $summaryAuditPages);
		
		if($updateStatus){
			$status = [
				'status'=>true,
				'message'=>'Page audit updated sucessfully.',
				'data'=>$summaryAuditPages
			];
		}else{
			$status = [
				'status'=>false,
				'message'=>'Unable to update Page audit.'
			];
		}

		return $status;
	}

	public function updateAuditRefresh(Request $request,$domain = null,$id = null){

		// file_put_contents(dirname(__FILE__).'/logs/refreshAudit.txt',print_r($request->all(),true));

		ini_set('max_execution_time', 7200);
		
		$summaryAuditSummary = SiteAuditSummary::where('id',$id)->orderBy('id','DESC')->first();
		
		$httpStatus = SiteAudit::getIp($summaryAuditSummary->url);
		
		if($httpStatus['http_code'] !== 301 && $httpStatus['http_code'] !== 302 && $httpStatus['http_code'] !== 200){

			return [
				'status'=>false,
				'failer_type'=>'expire',
				'message'=>'The url does not exists.'
			];
			
		}
		
		if($summaryAuditSummary->audit_status == 'process'){

			return [
				'status'=>false,
				'failer_type'=>'process',
				'message'=>'Request Already in progress!'
			];
			
		}

		if($summaryAuditSummary){
			$limit = $summaryAuditSummary->crowl_pages > 0 ? $summaryAuditSummary->crowl_pages : 50;
			$request->request->add(['url' => $summaryAuditSummary->url]);
			$request->request->add(['audit_id' => $summaryAuditSummary->id]);
			$request->request->add(['user_id' => $summaryAuditSummary->user_id]);

			$summaryAuditSummary->result = $summaryAuditSummary->total_tests = $summaryAuditSummary->criticals = $summaryAuditSummary->warnings = $summaryAuditSummary->notices = $summaryAuditSummary->title = $summaryAuditSummary->title = $summaryAuditSummary->meta_description = $summaryAuditSummary->headings = $summaryAuditSummary->content_keywords = $summaryAuditSummary->image_keywords = $summaryAuditSummary->seo_friendly_url = $summaryAuditSummary->seo_friendly_url = $summaryAuditSummary->noindex = $summaryAuditSummary->in_page_links = $summaryAuditSummary->favicon = $summaryAuditSummary->text_compression = $summaryAuditSummary->load_time = $summaryAuditSummary->page_size = $summaryAuditSummary->http_requests = $summaryAuditSummary->defer_javascript = $summaryAuditSummary->dom_size = $summaryAuditSummary->https_encryption = $summaryAuditSummary->plaintext_email = $summaryAuditSummary->structured_data = $summaryAuditSummary->meta_viewport = $summaryAuditSummary->meta_viewport = $summaryAuditSummary->sitemap = $summaryAuditSummary->content_length = $summaryAuditSummary->text_html_ratio = $summaryAuditSummary->inline_css = $summaryAuditSummary->inline_css = 0;
			$summaryAuditSummary->audit_status = 'process';

			if($request->limit <> null){
				$summaryAuditSummary->crowl_pages = $request->limit;
				$limit = $request->limit;
			}
			
			$summaryAuditSummary->save();

			// SiteAudit::where('audit_id',$summaryAuditSummary->id)->delete();
			// $list = $this->curlPost($request,$limit);
			return [
					'status'=>true,
					'audit_id'=>$summaryAuditSummary->id
				];

		}else{
			return [
				'status'=>false
			];
		}
		

	}

	public function siteSummaryLoader(){
		return view('vendor.audits.loaders.audit-summary');
	}
	
	public function siteListsLoader(){
		return view('vendor.audits.loaders.audit-lists');
	}

	public function pagesOverviewLoader(){
		return view('vendor.audits.loaders.pages-overview');
	}
	public function pagesSummaryLoader(){
		return view('vendor.audits.loaders.pages-summary');
	}
	public function pagesDetailLoader(){
		return view('vendor.audits.loaders.pages-details');
	}

	public function pdfAuditSummary(Request $request, $domain, $keys = null){
		if($keys == null){
			$keys = $domain;
		}
		$encription = base64_decode($keys);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];
		
		$summaryAudit = SiteAuditSummary::where('id',$campaign_id)->orderBy('id','DESC')->first();
		$data = SemrushUserAccount::with('ProfileInfo')->where('user_id',$user_id)->where('id',$summaryAudit->campaign_id)->first();
		
		$type = "Site-Audit Report";

		return view('vendor.audits.pdf.summary',compact('summaryAudit','data','type'));
		
	}

	public function pdfAuditDetails(Request $request, $domain, $keys = null){
		
		if($keys == null){
			$keys = $domain;
		}
		
		$encription = base64_decode($keys);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];
		
		$summaryAuditPages = SiteAudit::where('id',$campaign_id)->orderBy('id','DESC')->first();
		$data = SemrushUserAccount::with('ProfileInfo')->where('user_id',$user_id)->where('id',$summaryAuditPages->summary->campaign_id)->first();
		$type = "Site-Audit Report";
		return view('vendor.audits.pdf.details',compact('summaryAuditPages','data','type'));
		
	}

	public function auditShare($key){
		$pageType = 'shareKey';
		$auditType = 'individual';
		$summaryAudit = SiteAuditSummary::where('share_key',$key)->orderBy('id','DESC')->first();
		if($summaryAudit == null){
			return abort(404);
		}
		$key = base64_encode($summaryAudit->id.'-|-'.@$user_id.'-|-'. strtotime(date('Y-m-d H:i:s')));
		$pdf_url = \config('app.base_url')."download/pdf/".$key.'/audit';

		return view('viewkey.audits.audit-view',compact('pageType','summaryAudit','auditType','pdf_url'));
	}

	public function auditExpire(){
		return view('vendor.audits.audit-expire');
	}

	public function auditCampaignOverview($domain,$id = null)
	{	
		if($id == null){
			$id = $domain;
		}
		$summaryAudit = null;
		if($id !== null){
			$summaryAudit = SiteAuditSummary::where('campaign_id',$id)->orderBy('id','DESC')->first();
			if($summaryAudit == null){
				$summaryAudit['status'] = 'error'; 
			}
		}else{
			$summaryAudit['status'] = 'error'; 
		}
		return response()->json($summaryAudit);
	}

	public function auditPageDetailView(Request $request,$auditKey = null,$pageId = null){

		$limit = 50;
		if($auditKey == null || $pageId == null){
			return abort(404);
		}

		$summaryAudit = SiteAuditSummary::where('share_key',$auditKey)->orderBy('id','DESC')->first();

		$pageAudit = SiteAudit::where('id',$pageId)->first();

		if($summaryAudit == null || $pageAudit == null){
			return abort(404);
		}
		$profile_data = null;
		/*$profile_data = SemrushUserAccount::with('ProfileInfo')->where('id',$summaryAudit->campaign_id)->first();
		dd($profile_data);
		if($profile_data == null) {
			return abort(404);
		}*/

		if(isset($profile_data->ProfileInfo->agency_logo)){
			$logo = $this->agency_logo($summaryAudit->campaign_id,$profile_data->user_id,$profile_data->ProfileInfo->agency_logo);
			$profile_data->logo_data = $logo;
		}
		if(isset($profile_data->project_logo) && !empty($profile_data->project_logo)){
			$projectlogo = $this->project_logo($summaryAudit->campaign_id,$profile_data->project_logo);
			$profile_data->project_logo = $projectlogo;
		}

		$key = null;
		if($summaryAudit){
			$key = base64_encode($summaryAudit->id.'-|-'.$summaryAudit->user_id.'-|-'. strtotime(date('Y-m-d H:i:s')));
		}

		return view('vendor.audits.pages.pages-detail-view',compact('profile_data','summaryAudit','pageAudit','key','pageId'));
	}

	private function agency_logo($request_id,$user_id,$image_name){

		if (file_exists(\config('app.FILE_PATH').'public/storage/agency_logo/'.$user_id.'/'.$request_id)) {
			$path  = 'public/storage/agency_logo/'.$user_id.'/'.$request_id.'/';
			if(file_exists($path)){
				$image_url = URL::asset('public/storage/agency_logo/'.$user_id.'/'.$request_id.'/'.$image_name);

			}
		}else{
			$image_url   =   '';
		}

		return $image_url;   
	}


	private function project_logo($request_id,$image_name){

		if (file_exists(\config('app.FILE_PATH').'public/storage/project_logo/'.$request_id)) {
			$path  = 'public/storage/project_logo/'.$request_id.'/';
			if(file_exists($path)){
				$image_url = URL::asset('public/storage/project_logo/'.$request_id.'/'.$image_name);
			}
		}else{
			$image_url   =   '';
		}
		
		return $image_url;   
	}

	public function cronLogs(){

		$domainDetails = SemrushUserAccount::
			whereHas('UserInfo', function($q){
				$q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
				->where('subscription_status', 1);
			})
			->where('status','0')
			->where(function ($q) {
				$q
				->doesntHave('auditSummary');
				// ->orWhereHas('auditSummary', function($q){
				// ->WhereHas('auditSummary', function($q){
				// $q->whereDate('updated_at', '<' ,date('Y-m-d'));
				// });
			})
			->orderBy('id','ASC')
			->get();
			dd($domainDetails);
			$request = Request::capture();
			foreach ($domainDetails as $key => $value) {
				
				if($value->auditSummary <> null){
					$request->request->add(['url' => $value->auditSummary->url]);
					$urlList = $this->updateAuditRefresh($request,$value->UserInfo->company_name,$value->auditSummary->id);
				}else{
					$request->request->add(['url' => $value->host_url]);
					$request->request->add(['user_id' => $value->user_id]);
					$request->request->add(['campaign_id' => $value->id]);
					$this->siteAuditRun($request);
				}

				
			}
			

	}

}
