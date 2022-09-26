<?php


namespace App\Traits;

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


trait SiteAuditTrait
{

	// private $crawled = [];
	private $start = 0;

	use SitemapGenerator {
        SitemapGenerator::GenerateSitemap insteadof ReportTrait;
        SitemapGenerator::GenerateSitemap AS Sitemap;
    }

    use ReportTrait {
        ReportTrait::reportStore AS ReportTraitModel;
    }

    public function siteAuditRun(Request $request){

		// file_put_contents(dirname(__FILE__).'/logs/runAudit.txt',print_r($request->all(),true));

		ini_set('max_execution_time', 7200);
		
		$user_id = @$request->user_id; //get user id from child
		$campaign_id = @$request->campaign_id;
		$url = $request->input('url');

		$limit = 50;
		if($campaign_id <> null){
			$auditLimit = SemrushUserAccount::where('id',$campaign_id)->first();
			$limit = $auditLimit->audit_crawl_pages <> null ? $auditLimit->audit_crawl_pages : 50;
		}
		
		$domain = parse_url(str_replace(['https://www.', 'http://www.'], ['https://', 'http://'], $request->input('url')), PHP_URL_HOST);
		if(!$domain){
			$domain = $url;
		}

		$httpStatus = SiteAudit::getIp($domain);

		if($httpStatus['http_code'] == 301 || $httpStatus['http_code'] == 302){
			$url = $httpStatus['redirect_url'];
		}
		
		

		if($campaign_id <> null){
			$summaryAudit = SiteAuditSummary::where('user_id',$user_id)->where('campaign_id',$campaign_id)->where('project',$domain)->first();
		}else{
			$summaryAudit = SiteAuditSummary::where('user_id',$user_id)->where('project',$domain)->first();
		}

		if($summaryAudit){
		
			// if(strtotime($summaryAudit->updated_at) < strtotime(date('Y-m-d',strtotime(' -1 month')))){
			
			// if(strtotime($summaryAudit->updated_at) < strtotime(date('Y-m-d'))){
				
				$this->updateAuditRefresh($request,$companyName,$summaryAudit->id);
			// }
			
			return [
				'status'=>true
			];
		}else{
			
			$ip = $httpStatus['primary_ip'];
			$sslStatus = SiteAudit::getSslStatus($url);
			$isSsl = $sslStatus == true ? 1:0;
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
				$request->request->add(['audit_id' => $audit_id]);
				// $list = $this->GenerateSitemap($request,$limit);
				$this->scanned = [];
				$this->matched = [];
				$this->crawled = [];
				$list = $this->curlPost($request,$limit);
				return [
					'status'=>true,
					'list'=>$list
				];
			}else{
				return [
					'status'=>false
				];
			}

		}
	}

	public function updateAuditRefresh(Request $request,$domain = null,$id = null){

		

		ini_set('max_execution_time', 7200);
		
		$summaryAuditSummary = SiteAuditSummary::where('id',$id)->first();
		
		$httpStatus = SiteAudit::getIp($summaryAuditSummary->url);
		
		if($httpStatus['http_code'] !== 301 && $httpStatus['http_code'] !== 302 && $httpStatus['http_code'] !== 200){

			return [
				'status'=>false,
				'failer_type'=>'expire',
				'message'=>'The url does not exists.'
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
			
			SiteAudit::where('audit_id',$summaryAuditSummary->id)->delete();
			file_put_contents(dirname(__FILE__).'/logs/refreshAudit.txt',print_r($request->all(),true),FILE_APPEND);
			$this->scanned = [];
			$this->matched = [];
			$this->crawled = [];
			$list = $this->curlPost($request,$limit);
			return [
					'status'=>true,
					'list'=>$list
				];

		}else{
			return [
				'status'=>false
			];
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

		$requestUrl = $request->input('url');
		$cURLConnection = curl_init($endpoint);
		curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, ['url' => $request->input('url'), 'start' => $start,'limit' => $end]);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
		$apiResponse = curl_exec($cURLConnection);
		curl_close($cURLConnection);
		
		$urlList = json_decode($apiResponse,true);
		
		if($urlList  !== null){
			if(count($urlList) > 0){
				foreach ($urlList as $key => $uri) {
					if(count($this->crawled) >= $limit){
						SiteAuditSummary::updateSummaryScore($request->audit_id,'completed');
						return $this->crawled;
					}

					if (!in_array(rtrim($uri,'/'), $this->crawled)){
						array_push($this->crawled, rtrim($uri,'/'));
						$request['url'] = $uri;
						// file_put_contents(dirname(__FILE__).'/logs/curlPost.txt',print_r($request->all(),true),FILE_APPEND);
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
		
		file_put_contents(dirname(__FILE__).'/logs/'.$request->campaign_id.'.txt',print_r($this->crawled,true));

		SiteAuditSummary::updateSummaryScore($request->audit_id,'completed');
		return $list;
	}

}