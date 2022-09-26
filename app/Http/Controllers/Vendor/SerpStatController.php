<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use DataTables;
use Session;
use App\SemrushUserAccount;
use App\BacklinksData;
use App\BacklinkSummary;

class SerpStatController extends Controller {


	public function ajax_serp_stat(Request $request){
		//$domainDetails = SemrushUserAccount::with('user_detail')->where('status','0')->select('id','user_id','domain_url')->orderBy('id','desc')->get();


		$domainDetails = SemrushUserAccount::whereHas('UserInfo', function($q){
		    $q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
		    ->where('subscription_status', 1);
		})	
		->where('status','0')
		->select('id','user_id','domain_url')
		->orderBy('id','desc')
		->get();

		$removeChar = ["https://", "http://", "/","www."];
		foreach($domainDetails as $details){
	        $campaign_id = $details->id;
	        $user_id = $details->user_id;

	        $domain_url = str_replace($removeChar, "", $details->domain_url);

	        $this->serpstatBacklinks($campaign_id,$user_id,$domain_url);
	        $this->serpstatBackLinksSummary($campaign_id,$user_id,$domain_url);
	    }   
		
		// $campaign_id = $request['campaign_id'];
		// $user_id = Auth::user()->id;
		// $domainDetails = SemrushUserAccount::where('id',$campaign_id)->first();

		 // $domainDetails = SemrushUserAccount::where('status','0')->select('id','user_id','domain_url')->where('id',49)->first();
		 
		// $removeChar = ["https://", "http://", "/","www."];
		// $domain_url = str_replace($removeChar, "", $domainDetails->domain_url);
		// $campaign_id = 49;
		// $this->serpstatBacklinks($campaign_id,$user_id,$domain_url);
		// $this->serpstatBackLinksSummary($campaign_id,$user_id,$domain_url);
				
	}

	private function serpstatBacklinks($campaign_id,$user_id,$domain_url){
		//dd($campaign_id);
		$ifExists = BackLinksData::
		where('request_id',$campaign_id)
		->whereMonth('created_at',date('m'))
		->whereYear('created_at',date('Y'))
		->orderBy('id','desc')
		->first();
		
	// dd($ifExists);
		if(empty($ifExists)){
			$data = [
				"id"=> 1,
				"method"=> "SerpstatBacklinksProcedure.getNewBacklinks",
				"params"=> [
							"query"=>$domain_url,
							"searchType"=> "domain_with_subdomains",
							"page"=> 1,
							"size"=> 1,
							"order"=> "desc"
						]
			];
			$dataResult = $this->http_curl_handler($data);
			$finalData = json_decode($dataResult);
		
			if(isset($finalData->result)){
			$result_data = $finalData->result->data;
			foreach ($result_data as $key => $value) {
			
				BacklinksData::create([
					'user_id'=>$user_id,
					'request_id'=>$campaign_id,
					'url_from'=>$value->url_from,
					'url_to'=>$value->url_to,
					'nofollow'=>$value->nofollow,
					'link_type'=>$value->link_type,
					'links_ext'=>$value->links_ext,
					'link_text'=>$value->link_text,
					'first_seen'=>$value->first_seen,
					'last_visited'=>$value->last_visited
				]);
			}
		}
		}
		
	}
	
	private function serpstatBackLinksSummary($campaign_id,$user_id,$domain_url){
		$ifExists = BacklinkSummary::
		where('request_id',$campaign_id)
		->whereDate('created_at',date('Y-m-d'))
		->orderBy('id','desc')
		->first();

		
		if(empty($ifExists)){
			$data = [
				"id"=> 1,
				"method"=> "SerpstatBacklinksProcedure.getSummary",
				"params"=> [
							"query"=>$domain_url
						]
			];
			
			$dataResult=	$this->http_curl_handler($data);
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

			BacklinkSummary::cron_GetBacklinksCount($campaign_id);
			}
		}
	}
	
	
	private function http_curl_handler($data){
		
		  $curl = curl_init();

		  curl_setopt_array($curl, array(
		  // CURLOPT_URL => 'http://api.serpstat.com/v4/?token=2ae59f058d5403f8c731a0d59e49ff11',
		  CURLOPT_URL => \env('SERPSTAT_URL').\env('SERPSTAT_TOKEN'),
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
	
	
	public function ajax_backlink_profile_data(Request $request){
		
			if($request['order']['0']['column'] == 0){
				$sortBy = 'url_from';
				$dir = $request['order']['0']['dir'];
			}elseif($request['order']['0']['column'] == 1){
					$sortBy = 'nofollow';
					$dir = $request['order']['0']['dir'];			
			}elseif($request['order']['0']['column'] == 2){
					$sortBy = 'link_text';
					$dir = $request['order']['0']['dir'];			
			}elseif($request['order']['0']['column'] == 3){
					$sortBy = 'link_type';
					$dir = $request['order']['0']['dir'];			   
			}elseif($request['order']['0']['column'] == 4){
					$sortBy = 'links_ext';
					$dir = $request['order']['0']['dir'];
			}elseif($request['order']['0']['column'] == 5){
					$sortBy = 'first_seen';
					$dir = $request['order']['0']['dir'];           
			}else{
					$sortBy = 'created_at';
					$dir = 'asc';	 
			}
			
			$string = trim($request['search']["value"]);
			$field = ['url_from','link_text'];
		
			$records = BackLinksData::
			where('request_id',$request['campaign_id'])
			->where(function ($query) use($string, $field) {
				 for ($i = 0; $i < count($field); $i++){
					$query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
				 }      
			})
			->skip($request->start)
			->take($request->length)
			->orderBy('created_at','asc')
			->orderBy($sortBy,$dir)
			->get();
		
		$data = array();
		foreach($records as $key=> $value){
			if(strlen($value->url_from) > 30){
				$url_from = substr($value->url_from,0,30)."...";
			}else{
				$url_from = $value->url_from;
			}
			
			if(strlen($value->url_to) > 30){
				$url_to = substr($value->url_to,0,30)."...";
			}else{
				$url_to = $value->url_to;
			}
			
			
			$data[$key][] = '<a href="'.$value->url_from.'" target="_blank" title="'.$value->url_from.'">'. $url_from.'</a>';
			$data[$key][] = $value->nofollow;
			$data[$key][] = '<a href="'.$value->url_to.'" target="_blank" title="'.$value->url_to.'">'. $url_to.'</a>';
			$data[$key][] = $value->link_type;
			$data[$key][] = $value->links_ext;
			$data[$key][] = date('F d, Y',strtotime($value->first_seen));
		}
		
		$record_count = BackLinksData::
			where('request_id',$request['campaign_id'])
			->where(function ($query) use($string, $field) {
				 for ($i = 0; $i < count($field); $i++){
					$query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
				 }      
			})
			->orderBy('created_at','asc')
			->orderBy($sortBy,$dir)
			->count();
			
			$output = array(
				"draw"              =>  intval($request->draw),
				"recordsTotal"      =>  $record_count,
				"recordsFiltered"   =>  $record_count,
				"data"              =>  $data
			);

			return response()->json($output);
	}
	
	
	public function ajax_referring_domains(Request $request){
		
		$request_id = $request['campaign_id'];
		$summaryData = 	BacklinkSummary::
		where('request_id',$request_id)
		->orderBy('id','desc')
		->limit(2)
		->get();
		
		if(isset($summaryData) && !empty($summaryData)){
			if(!empty($summaryData[0]->referringDomains) && !empty($summaryData[1]->referringDomains)){
				if($summaryData[1]->referringDomains > 2){
					$count = $summaryData[1]->referringDomains;
					
					if ($count) {
						$organic_keywords	=   round(($summaryData[0]->referringDomains-$count)/$count * 100, 2);
					} else {
						 $organic_keywords = 0;
					}
				} else{
					$organic_keywords	=  100;
				}
			} else if(empty($summaryData[0]->referringDomains) && !empty($summaryData[1]->referringDomains) ) {
				$organic_keywords	=  -100;
			} else if(!empty($summaryData[0]->referringDomains) && empty($summaryData[1]->referringDomains) ) {
				$organic_keywords	=  100;
			} else{
				$organic_keywords	=  0;
			}
			
			
			if(isset($summaryData[1]->referringDomains)){
				$total_old = $summaryData[1]->referringDomains;
			}else{
				$total_old = 0;
			}
			return array('avg'=>$organic_keywords,'total'=>@$summaryData[0]->referringDomains,'totalold'=>$total_old);
		} else{
			return array('avg'=>0,'total'=>0,'totalold'=>0);
		}		
	}
}