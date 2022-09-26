<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\BackLinksData;
use App\BacklinkSummary;
use App\SemrushUserAccount;
use App\KeywordPosition;
use DB;
use App\SemrushBacklinkSummary;

class BacklinkProfileController extends Controller {

	public function ajax_fetch_backlink_data(Request $request){
		if($request->ajax())
		{
			$sortBy = $request['column_name'];
			$sortType = $request['order_type'];
			$limit = $request['limit'];	
			$campaign_id = $request['campaign_id'];
			$query = $request['query'];

			$backlink_records = $this->get_backlink_data($limit,$campaign_id,$sortBy,$sortType,$query);
			return view('vendor.seo_sections.backlink_profile.list', compact('backlink_records','campaign_id'))->render();
		}
	}

	public function ajax_fetch_backlink_pagination(Request $request){
		if($request->ajax())
		{
			$sortBy = $request['column_name'];
			$sortType = $request['order_type'];
			$limit = $request['limit'];	
			$campaign_id = $request['campaign_id'];
			$query = $request['query'];

			$backlink_records = $this->get_backlink_data($limit,$campaign_id,$sortBy,$sortType,$query);
			return view('vendor.seo_sections.backlink_profile.pagination', compact('backlink_records','campaign_id'))->render();
		}
	}

	public static function get_backlink_data($limit=null,$campaign_id,$sortBy=null,$sortType=null,$query=null){
		$field = ['url_from','url_from','link_text','title','link_type'];

		$sortType = $sortType <> null ? $sortType : 'desc' ;
		$sortBy = $sortBy <> null ? $sortBy : 'id' ;
		$limit = $limit <> null ? $limit : '10' ;

		$records = BackLinksData::
		where('request_id',$campaign_id)
		->where(function ($dta) use($query, $field) {
			for ($i = 0; $i < count($field); $i++){
				$dta->orwhere($field[$i], 'LIKE',  '%' . $query .'%');
			}   
		})
		->orderBy($sortBy,$sortType)
		->paginate($limit);


		return $records;
	}

	public function ajax_backlink_chart_data_bkp(Request $request){
		if($request['value'] == 'last_30'){
			$range = date('Y-m-d',strtotime('-1 month'));
		}elseif($request['value'] == 'one_year'){
			$range = date('Y-m-d',strtotime('-1 year'));
		}else{
			$range_data = BacklinkSummary::
			where('request_id',$request['campaign_id'])
			->orderBy('id','asc')
			->select('created_at')
			->first();
			
			if($range_data <> null){
				$range = date('Y-m-d',strtotime($range_data->created_at));
			}else{
				$data = SemrushUserAccount::where('id',$request['campaign_id'])->select('id','domain_register')->first();
				if($data->domain_register <> null){
					$range = date('Y-m-d',strtotime($data->domain_register));
				}else{
					$range = date('Y-m-d',strtotime('-1 month'));
				}
			}
		}

		$tomorrow = date('Y-m-d',strtotime('+1 day'));
		$backlinks = BacklinkSummary::
		where('request_id',$request['campaign_id'])
		->whereBetween('created_at',[$range,$tomorrow])
		->get();
		
		$referringDomains = array();
		foreach ($backlinks as $key => $value) {
			$referringDomains[] = array('t'=>strtotime($value->created_at)*1000,'y'=>$value->referringDomains);
		}
		return array('referringDomains'=>$referringDomains);

	}

	public function ajax_backlink_chart_data(Request $request){
		$backlink = SemrushBacklinkSummary::
		where('request_id',$request['campaign_id']);
		if($request['value'] == 'last_30'){
			$range = date('Y-m-d',strtotime('-1 month'));
			$backlink->whereDate('created_at','>=',$range);
		}elseif($request['value'] == 'one_year'){
			$range = date('Y-m-d',strtotime('-1 year'));
			$backlink->whereDate('created_at','>=',$range); 
		}
		$backlinks = $backlink->get();

		$referringDomains = array();
		if(!empty($backlinks) && count($backlinks) > 0){
			foreach ($backlinks as $key => $value) {
				$referringDomains[] = array('t'=>strtotime($value->created_at)*1000,'y'=>$value->domains_num);
			}
		}else{
			$backlink = BacklinkSummary::
			where('request_id',$request['campaign_id']);
			if($request['value'] == 'last_30'){
				$range = date('Y-m-d',strtotime('-1 month'));
				$backlink->whereDate('created_at','>=',$range);
			}elseif($request['value'] == 'one_year'){
				$range = date('Y-m-d',strtotime('-1 year'));
				$backlink->whereDate('created_at','>=',$range); 
			}
			$backlinks = $backlink->get();

			foreach ($backlinks as $key => $value) {
				$referringDomains[] = array('t'=>strtotime($value->created_at)*1000,'y'=>$value->referringDomains);
			}
		}
		return array('referringDomains'=>$referringDomains);
	}


	public static  function getTitle($url) {
		$title = '';
    // Extract HTML using curl
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$data = curl_exec($ch);
		curl_close($ch);

    // Load HTML to DOM Object
		$dom = new \DOMDocument();
		@$dom->loadHTML($data);

    // Parse DOM to get Title
		$nodes = $dom->getElementsByTagName('title');
		$title = @$nodes->item(0)->nodeValue;

		return $title;
	}

	public function ajax_backlink_profile_list_bkp(Request $request){
		$backlink_profile_summary = BacklinkSummary::where('request_id',$request->campaign_id)->whereDate('created_at','<=',date('Y-m-d'))->orderBy('id','desc')->first();
		return view('vendor.seo_sections.backlink_profile.summary_table', compact('backlink_profile_summary'))->render();
	}

	public function ajax_backlink_profile_list(Request $request){
		$backlink_profile_summary = SemrushBacklinkSummary::where('request_id',$request->campaign_id)->whereDate('created_at','<=',date('Y-m-d'))->orderBy('id','desc')->first();
		$flag = 0;
		if(!isset($backlink_profile_summary) && $backlink_profile_summary ===  null){
			$backlink_profile_summary = BacklinkSummary::where('request_id',$request->campaign_id)->whereDate('created_at','<=',date('Y-m-d'))->orderBy('id','desc')->first();
			$flag = 1;
		}

		return view('vendor.seo_sections.backlink_profile.summary_table', compact('backlink_profile_summary','flag'))->render();
	}

	public function ajax_get_backlinkProfile_time(Request $request){
		$response = array();
		$data = BackLinksData::select('created_at')->where('request_id',$request->campaign_id)->orderBy('id','desc')->first();
		// if(isset($data) && !empty($data)){
		// 	$time_span = KeywordPosition::calculate_time_span($data->created_at);
		// 	$response['status'] = 1; 
		// 	$response['time']   = "Last Updated: ".$time_span." (".date('M d, Y',strtotime($data->created_at)).")" ;
		// }else {
		$detail = SemrushUserAccount::select('backlinks_cron_date')->where('id',$request->campaign_id)->first();
		if($detail->backlinks_cron_date <> null){
			$time_span = KeywordPosition::calculate_time_span($detail->backlinks_cron_date);
			$date = date('Y-m-d',strtotime('- 7 day',strtotime($detail->backlinks_cron_date)));
			$response['status'] = 1; 
			$response['time']   = "Last Updated: on ".date('M d, Y',strtotime($date)) ;
		}else{
			$response['status'] = 0;
			$response['message'] = '';
				// $response['message'] = 'Getting Error to update data';
		}

		// }
		return $response;
	}

	public function ajax_get_latest_backlinks(Request $request){
		$response = array();
		$domainDetails = SemrushUserAccount::
		whereHas('UserInfo', function($q){
			$q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
			->where('subscription_status', 1);
		})
		->where('status','0')
		->select('id','user_id','domain_url','backlinks_cron_date','url_type','host_url')
		->orderBy('id','desc')
		// ->where(function ($query) {
		// 	$query->where('backlinks_cron_date', '<=', date('Y-m-d'))
		// 	->orWhereNull('backlinks_cron_date');
		// })
		->where('id',$request->campaign_id)
		->first();


		if(isset($domainDetails) && !empty($domainDetails)){
			$backlink_cal_date = date('Y-m-d', strtotime("-1 week", strtotime($domainDetails->backlinks_cron_date)));
			if($domainDetails->backlinks_cron_date == null || $backlink_cal_date <= date('Y-m-d')){
				$removeChar = ["https://", "http://" ,'/', "www."];
				$campaign_id = $domainDetails->id;
				$user_id = $domainDetails->user_id;

				if($domainDetails->url_type == 2){
					$domain_url = str_replace($removeChar, "", $domainDetails->host_url);
				}else{
					$domain_url = str_replace($removeChar, "", $domainDetails->domain_url);
				}

				$this->get_backlinks_overview($campaign_id,$user_id,$domain_url);
				return 'success';
			}
		}
	}

	private function serpstatBackLinksSummary($campaign_id,$user_id,$domain_url){
		$data = [
			"id"=> 1,
			"method"=> "SerpstatBacklinksProcedure.getSummary",
			"params"=> [
				"query"=>$domain_url,
				"searchType"=>"domain_with_subdomains"
			]
		];

		$dataResult=  $this->http_curl_handler($data);
		$finalData = json_decode($dataResult);

		
		if(isset($finalData->result)){
			$result_data = $finalData->result->data;

			BacklinkSummary::create([
				'user_id'=>$user_id,
				'request_id'=>$campaign_id,
				'referringDomains'=>$result_data->referringDomains,
				'referringSubDomains'=>$result_data->referringSubDomains,
				'referringLinks'=>$result_data->referringLinks,
				'noFollowLinks'=>$result_data->noFollowLinks,
				'doFollowLinks'=>$result_data->doFollowLinks,
				'referringIps'=>$result_data->referringIps,
				'referringSubnets'=>$result_data->referringSubnets,
				'domainZoneEdu'=>$result_data->domainZoneEdu,
				'domainZoneGov'=>$result_data->domainZoneGov,
				'typeText'=>$result_data->typeText,
				'typeImg'=>$result_data->typeImg,
				'typeRedirect'=>$result_data->typeRedirect,
				'mainPageLinks'=>$result_data->mainPageLinks,
				'domainRank'=>$result_data->domainRank,
				'facebookLinks'=>$result_data->facebookLinks,
				'pinterestLinks'=>$result_data->pinterestLinks,
				'linkedinLinks'=>$result_data->linkedinLinks,
				'vkLinks'=>$result_data->vkLinks
			]);


			if($result_data->referringLinks > 0){
				$this->serpstatBacklinks($campaign_id,$user_id,$domain_url);
			}else{
				SemrushUserAccount::where('id',$campaign_id)->update([
					'backlinks_cron_date'=>date('Y-m-d',strtotime('+1 week'))
				]);
			}
			BacklinkSummary::cron_GetBacklinksCount($campaign_id);



		}
	}

	private function serpstatBacklinks($campaign_id,$user_id,$domain_url){
		$data = [
			"id"=> 1,
			"method"=> "SerpstatBacklinksProcedure.getNewBacklinks",
			"params"=> [
				"query"=>$domain_url,
				"searchType"=> 'domain_with_subdomains',
				"page"=> 1,
				"size"=> 20,
				"order"=> "desc"
			]
		];
		$dataResult = $this->http_curl_handler($data);
		$finalData = json_decode($dataResult);

		if(isset($finalData->result)){
			$result_data = $finalData->result->data;
			foreach ($result_data as $key => $value) {
				$scraped_page = self::curl_data($value->url_from);
				$title = self::scrape_between($scraped_page, "<title>", "</title>");


				BacklinksData::create([
					'user_id'=>$user_id,
					'request_id'=>$campaign_id,
					'title'=>$title,
					'url_from'=>$value->url_from,
					'url_to'=>$value->url_to,
					'nofollow'=>$value->nofollow,
					'link_type'=>$value->link_type,
					'links_ext'=>$value->links_ext,
					'link_text'=>$value->link_text,
					'first_seen'=>$value->first_seen,
					'last_visited'=>$value->last_visited
				]);

				$title = '';
			}

			SemrushUserAccount::where('id',$campaign_id)->update([
				'backlinks_cron_date'=>date('Y-m-d',strtotime('+1 week'))
			]);
		}
	}

	private function http_curl_handler($data){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => \config('app.SERPSTAT_URL').\config('app.SERPSTAT_TOKEN'),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS =>json_encode($data),
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	private function curl_data($url) {
		$options = Array(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_FOLLOWLOCATION => TRUE,
			CURLOPT_AUTOREFERER => TRUE,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT => 120,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",
			CURLOPT_URL => $url,
		);

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	private function scrape_between($data, $start, $end){
		$data = stristr($data, $start);
		$data = substr($data, strlen($start));
		$stop = stripos($data, $end);
		$data = substr($data, 0, $stop);
		return $data; 
	}

	private function get_backlinks_overview($campaign_id,$user_id,$domain_url){
		$semrush_api_key = \config('app.SEMRUSH_API_KEY');
		$url = 'https://api.semrush.com/analytics/v1/?key='.$semrush_api_key.'&type=backlinks_overview&target='.$domain_url.'&target_type=root_domain&export_columns=ascore,total,domains_num,urls_num,ips_num,follows_num,nofollows_num,sponsored_num,ugc_num,texts_num,images_num';
		$final_data =  SemrushBacklinkSummary::buildReport(SemrushBacklinkSummary::cURL($url));

		if(isset($final_data) && count($final_data) > 0){
			$final_data = $final_data[0];
			SemrushBacklinkSummary::create([
				'user_id'=>$user_id,
				'request_id'=>$campaign_id,
				'ascore'=>$final_data['ascore']?:0,
				'total'=>$final_data['total']?:0,
				'domains_num'=>$final_data['domains_num']?:0,
				'urls_num'=>$final_data['urls_num']?:0,
				'ips_num'=>$final_data['ips_num']?:0,
				'follows_num'=>$final_data['follows_num']?:0,
				'nofollows_num'=>$final_data['nofollows_num']?:0,
				'sponsored_num'=>$final_data['sponsored_num']?:0,
				'ugc_num'=>$final_data['ugc_num']?:0,
				'texts_num'=>$final_data['texts_num']?:0,
				'images_num'=>$final_data['images_num']?:0
			]);

		}

		$this->serpstatBacklinks($campaign_id,$user_id,$domain_url);
		SemrushUserAccount::where('id',$campaign_id)->update([
			'backlinks_cron_date'=>date('Y-m-d',strtotime('+1 week'))
		]);
		SemrushBacklinkSummary::cron_GetBacklinksCount($campaign_id);
	}  
}