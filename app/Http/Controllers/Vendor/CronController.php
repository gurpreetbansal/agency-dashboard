<?php 

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Crypt;
use App\KeywordPosition;
use App\KeywordSearch;
use App\Traits\ClientAuth;
use App\SemrushUserAccount;
use App\ActivityLog;
use App\CampaignData;
use App\SemrushOrganicMetric;
use App\BacklinkSummary;
use DB;
use App\KeywordAlert;

class CronController extends Controller {
	
	use ClientAuth;
	
	public function cron_live_keyword_tracking_bkp (){

		

		$keyword_position = KeywordPosition::
		select('id','request_id')
		->whereHas('campaign_data', function ($query) {
			$query->where('status', 0);
			$query->whereHas('UserInfo', function($q){
				$q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
				->where('subscription_status', 1);
			});
		}) 
		->where('status','!=','1')
		->whereDate('created_at','<',date('Y-m-d'))
		->groupBy('keyword_id')
		->orderBy('created_at','desc')
		->limit(100)
		->get();

		echo "<pre>";
		print_r($keyword_position);
		die;
		$client = null;
		try {
			$client = $this->DFSAuth();
		} catch (RestClientException $e) {
			return json_decode($e->getMessage(), true);
		}
		
		if(!empty($keyword_position) ){
			foreach($keyword_position as $result){
				
				$search_data = KeywordSearch::where('id',$result->keyword_id)->first();
				$semrush = SemrushUserAccount::where('id',$request->request_id)->first();
				$user_id = $semrush->user_id;

				if(empty($search_data)){
					continue;
				}	

				$post_array  =array();
				$post_array[] = array(
					"language_name" => $search_data->language,
                    // "locationType" => $search_data->canonical,
					'location_coordinate'=>$search_data->lat.','.$search_data->long,
					"se_domain" => $search_data->region,
					"domain" => $search_data->host_url,
					"keyword" => mb_convert_encoding($search_data->keyword, "UTF-8"),
					"priority" => 2,
					"postback_data" => "advanced",
					"postback_url" => url('/cron_postbackAddKeyResponse?request_id='.$result->request_id.'&keyword_id='.$result->keyword_id.'&data_id='.$result->id.'&user_id='.$user_id)
				);
				
				KeywordPosition::where('id',$result->id)->update([
					'status'=> 1
				]);		

				try {
					$resultOrg = $client->post('/v3/serp/google/organic/task_post', $post_array);
					$post_array = array();
					$response['status'] = '1'; 
					$response['error'] = '0';
					$response['message'] = 'Keyword Added Successfully';
					$response['html']   =   '';
				} catch (RestClientException $e) {
					$response['status'] = '2'; 
					$response['error'] = '2';
					$response['message'] = $e->getMessage();
				}
				
			}			
		}

	}

	public function cron_live_keyword_tracking()
	{


    	/*$keyword_position = KeywordPosition::
        whereHas('campaign_data', function ($query) {
            $query->where('status', 0);
            $query->whereHas('UserInfo', function($q){
                $q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
                ->where('subscription_status', 1);
            });
        }) 
        ->where('status','!=','1')
        ->whereDate('created_at','<',date('Y-m-d'))
        ->groupBy('keyword_id')
        ->orderBy('created_at','desc')
        // ->limit(100)
        ->get();*/


        $keyword_position = KeywordSearch::
        whereHas('SemrushUserData', function ($q) {
        	$q->where('status', 0);
        })
        ->whereHas('users', function($q){
        	$q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
        	->where('subscription_status', 1);
        })

        ->where(function($q){
        	$q->whereNull('cron_date')
        	->orwhereDate('cron_dates','<', date('Y-m-d'));
        })

        
        // ->whereDate('cron_date','<',date('Y-m-d'))
        ->orderBy('created_at','asc')
        ->limit(100)
        ->get();
        dd($keyword_position);
        $client = null;
        try {
        	$client = $this->DFSAuth();
        } catch (RestClientException $e) {
        	return json_decode($e->getMessage(), true);
        }
        

        if(!empty($keyword_position)){
        	foreach($keyword_position as $result){

        		$postiondata = KeywordPosition::where('keyword_id',$result->id)->orderBy('id','desc')->first();
        		/*dd($postiondata);*/
        		$semrush = SemrushUserAccount::where('id',$result->request_id)->first();
        		$user_id = $semrush->user_id;

        		$post_array  =array();
        		$post_array[] = array(
        			"language_name" => $result->language,
                        // "location_name" => $search_data->canonical,
        			'location_coordinate'=>$result->lat.','.$result->long,
        			"se_domain" => $result->region,
        			"domain" => $result->host_url,
        			"keyword" => mb_convert_encoding($result->keyword, "UTF-8"),
        			"priority" => 2,
        			"postback_data" => "advanced",
        			"postback_url" => url('/cron_postbackAddKeyResponse?request_id='.$result->request_id.'&keyword_id='.$result->id.'&data_id='.$postiondata->id.'&user_id='.$user_id)
        		);


        		KeywordSearch::where('id',$result->id)->update([
        			'cron_date'=> date('Y-m-d H:i:s')
        		]);     


        		try {
        			$resultOrg = $client->post('/v3/serp/google/organic/task_post', $post_array);

        			$post_array = array();
        			$response['status'] = '1'; 
        			$response['error'] = '0';
        			$response['message'] = 'Keyword Added Successfully';
        			$response['html']   =   '';
        		} catch (RestClientException $e) {
        			$response['status'] = '2'; 
        			$response['error'] = '2';
        			$response['message'] = $e->getMessage();
        		}
        		print_r($resultOrg);
        		dd($result);

        	}           
        }
    }


    public function cron_postbackAddKeyResponse(Request $request){
    	$results = KeywordSearch::where('id',$request['keyword_id'])->orderBy('created_at','desc')->first();
    	if($results <> null){
    		$post_arr = json_decode(gzdecode($request->getContent()),true);
    		$keyValue = $results->host_url;
    		$url_type = $results->url_type;
    		$ignore_local_listing = $results->ignore_local_listing;

    		$new = array_filter($post_arr['tasks'][0]['result'][0]['items'], function($value) use ($keyValue,$url_type,$ignore_local_listing) {
    			if($ignore_local_listing == 1){
    				if(strpos(strtolower($value['type']), strtolower('organic')) !== FALSE || strpos(strtolower($value['type']), strtolower('featured_snippet')) !== FALSE){

    		// 			if($url_type == 2){
						// 	$hostUrl = parse_url($value['url'],PHP_URL_HOST);
	     //    				$domain_name = preg_replace('/^www\./', '', $hostUrl);

						// 	if($domain_name == $keyValue){
						// 		return $value;
						// 	}
						// }else {
						// 	$domain_name = preg_replace('/^www\./', '', $value['domain']);
						// 	if($domain_name == $keyValue){
						// 		return $value;
						// 	}
						// }

    					/*if($url_type == 2){
    						if(strpos(strtolower($value['url']), strtolower($keyValue)) !== FALSE){
    							return $value;
    						}
    					}else {
    						if(strpos(strtolower($value['domain']), strtolower($keyValue)) !== FALSE){
    							return $value;
    						}
    					}*/

                        if($url_type == 2){
                            $hostUrl = parse_url($value['url']);
                            if(isset($hostUrl['host'])){
                                $domain_name = preg_replace('/^www\./', '', $hostUrl['host']);
                                $domainUrl = $domain_name.$hostUrl['path'];

                                if($domainUrl == $keyValue){
                                    return $value;
                                }
                            }
                        }else {
                            if(isset($value['domain'])){
                                $domain_name = preg_replace('/^www\./', '', $value['domain']);
                                if($domain_name == $keyValue){
                                    return $value;
                                }
                            }
                        }
    				}
    			}else{
    				if(strpos(strtolower($value['type']), strtolower('organic')) !== FALSE || strpos(strtolower($value['type']), strtolower('featured_snippet')) !== FALSE || strpos(strtolower($value['type']), strtolower('local_pack')) !== FALSE){

    		// 			if($url_type == 2){
						// 	$hostUrl = parse_url($value['url'],PHP_URL_HOST);
	     //    				$domain_name = preg_replace('/^www\./', '', $hostUrl);

						// 	if($domain_name == $keyValue){
						// 		return $value;
						// 	}
						// }else {
						// 	$domain_name = preg_replace('/^www\./', '', $value['domain']);
						// 	if($domain_name == $keyValue){
						// 		return $value;
						// 	}
						// }
						
    					/*if($url_type == 2){
    						if(strpos(strtolower($value['url']), strtolower($keyValue)) !== FALSE){
    							return $value;
    						}
    					}else {
    						if(strpos(strtolower($value['domain']), strtolower($keyValue)) !== FALSE){
    							return $value;
    						}
    					}*/

                        if($url_type == 2){
                            $hostUrl = parse_url($value['url']);
                            if(isset($hostUrl['host'])){
                                $domain_name = preg_replace('/^www\./', '', $hostUrl['host']);
                                $domainUrl = $domain_name.$hostUrl['path'];

                                if($domainUrl == $keyValue){
                                    return $value;
                                }
                            }
                        }else {
                            $domain_name = preg_replace('/^www\./', '', $value['domain']);
                            if($domain_name == $keyValue){
                                return $value;
                            }
                        }
    				}
    			}
    		}); 

    		$newKey = array_keys($new);
    		if($newKey){
    			$key = $newKey[0];
    		}else{
    			$key = null;
    		}

    		if($key >= 0 && $key !== null){
    			if($post_arr['tasks'][0]['result'][0]['items'][$key]['type'] == 'featured_snippet'){
    				$position_type = 2;
    			}elseif($post_arr['tasks'][0]['result'][0]['items'][$key]['type'] == 'local_pack'){
    				$position_type = 1;
    			}else{
    				$position_type = 0;
    			} 

    			KeywordSearch::where('id',$request['keyword_id'])->update([
    				'result_se_check_url' =>$post_arr['tasks'][0]['result'][0]['check_url'],
    				'result_url'=>$post_arr['tasks'][0]['result'][0]['items'][$key]['url'],
    				'url_site'=>$post_arr['tasks'][0]['result'][0]['items'][$key]['url'],
    				'result_title'=>$post_arr['tasks'][0]['result'][0]['items'][$key]['title'],
    				'position'=>$post_arr['tasks'][0]['result'][0]['items'][$key]['rank_group'],
    				'is_sync'=>'1'
    			]);

    			$params	= array(
    				'request_id'	=> $request['request_id'],
    				'keyword_id'	=>	$request['keyword_id'],
    				'position'		=>	$post_arr['tasks'][0]['result'][0]['items'][$key]['rank_group'],
    				'position_type'	=>	$position_type,
    				'updated_at'	=>	now()
    			);

    		}else{
    			$getFilteredUrl = KeywordSearch::getFilteredUrl('https://'.$keyValue);
    			KeywordSearch::where('id',$request['data_id'])->update([
    				'result_se_check_url'=>$post_arr['tasks'][0]['result'][0]['check_url'],
    				'is_sync'=>'1',
    				'result_url'=> $getFilteredUrl
    			]);

    			$params		=	array(
    				'request_id'	=> $request['request_id'],
    				'keyword_id'	=>	$request['keyword_id'],
    				'position_type'	=>	0,
    				'position'		=>	0,
    				'updated_at'	=>	now()
    			);
    		}

    		$this->addKeywordPosition($params);
    		$this->updateRanking($request['request_id'],$request['keyword_id'],$request['user_id']);

    		/*campaign data save*/
    		$this->keywordsData($request['request_id']);
    		SemrushOrganicMetric::DFSKeywords_cron($request['request_id']);
    		BacklinkSummary::cron_GetBacklinksCount($request['request_id']);
    		KeywordSearch::update_notification_status($request['request_id']);
    		KeywordAlert::update_keyword_alert_status($request['request_id'],$request['user_id']);
    	}
    }


    private function addKeywordPosition($data){

    	file_put_contents(dirname(__FILE__).'/logs/updatePostion.txt',print_r($data,true));
    	$if_exists =   KeywordPosition::where('request_id',$data['request_id'])->where('keyword_id', $data['keyword_id'])->whereDate('created_at','=',date('Y-m-d'))->count();
    	if($if_exists == 0){
    		$insert = KeywordPosition::create([
    			'request_id' => $data['request_id'],
    			'keyword_id' => $data['keyword_id'],
    			'position' =>  $data['position'],
    			'position_type' => $data['position_type']
    		]);
    	}else{
    		$insert = KeywordPosition::where('request_id',$data['request_id'])->where('keyword_id', $data['keyword_id'])->whereDate('created_at','=',date('Y-m-d'))->update([
    			'position' =>  $data['position'],
    			'position_type' => $data['position_type']
    		]);
    	}

        // if($insert){
        //     return true;
        // }else{
        //     return false;
        // }
    }



    private function updateRanking($requestId,$keywordId,$user_id){
    	$results = KeywordSearch::where('request_id',$requestId)->where('id',$keywordId)->orderBy('id','desc')->first();

    	$currentPostion = KeywordPosition::lastestKeywordPosition($requestId,$keywordId);
    	$oneData = KeywordPosition::oneDayKeyword($requestId,$keywordId);
    	$weekData = KeywordPosition::weeklyKeywords($requestId,$keywordId);
    	$monthData = KeywordPosition::monthlyKeywords($requestId,$keywordId);

    	/*calculating single day data*/
    	if((!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0) && (!empty($oneData->position) && $oneData->position <> null && $oneData->position > 0)){
    		$oneDay = (int) $oneData->position - (int) $currentPostion->position;	
    	}elseif((!empty($oneData->position) && $oneData->position <> null && $oneData->position > 0) && (!empty($currentPostion->position) && $currentPostion->position == null || $currentPostion->position == 0)){
    		$oneDay = (int) $oneData->position - 100;
    	}else{
    		$oneDay = 0;	
    	}


    	/*calculating weekly data*/
    	if((!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0) && (!empty($weekData->position) && $weekData->position <> null && $weekData->position > 0)){
    		$weekDay = (int) $weekData->position - (int) $currentPostion->position;	
    	}elseif((!empty($weekData->position) && $weekData->position <> null && $weekData->position > 0) && (!empty($currentPostion->position) && $currentPostion->position == null || $currentPostion->position == 0)){
    		$weekDay = (int) $weekData->position - 100;
    	}else{
    		$weekDay = 0;	
    	}

    	/*calculating monthly data*/
    	if((!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0) && (!empty($monthData->position) && $monthData->position <> null && $monthData->position > 0)){
    		$monthDay = (int) $monthData->position - (int) $currentPostion->position;	
    	}elseif((!empty($monthData->position) && $monthData->position <> null && $monthData->position > 0) && (!empty($currentPostion->position) && $currentPostion->position == null || $currentPostion->position == 0)){
    		$monthDay = (int) $monthData->position - 100;
    	}else{
    		$monthDay = 0;	
    	}



    	/*calculating lifetime data*/
		/*if((!empty($results->position) && $results->position <> null && $results->position > 0) && (!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0)){
			$lifeTime = (int) $results->start_ranking - (int) $currentPostion->position;
		}elseif((!empty($results->position) && $results->start_ranking == null || $results->start_ranking == 0) && (!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0)){
			$lifeTime = 100 - (int) $currentPostion->position;
		}elseif((!empty($results->position) && $results->start_ranking <> null && $results->start_ranking > 0) && (!empty($currentPostion->position) && $currentPostion->position == null || $currentPostion->position == 0)){
			$lifeTime = (int) $results->start_ranking - 100;
		}else{
			$lifeTime = 0;
		}*/

		/*calculating lifetime data*/
		file_put_contents(dirname(__FILE__).'/logs/kewords.txt',print_r($results,true));
		file_put_contents(dirname(__FILE__).'/logs/positions.txt',print_r($currentPostion,true));

		if(($results->start_ranking <> null && $results->start_ranking > 0) && ($currentPostion->position <> null && $currentPostion->position > 0)){
			$lifeTime = (int) $results->start_ranking - (int) $currentPostion->position;
		}elseif(($results->start_ranking == null || $results->start_ranking == 0) && ($currentPostion->position <> null && $currentPostion->position > 0)) {
			$lifeTime = 100 - (int) $currentPostion->position;
		}elseif(($results->start_ranking <> null && $results->start_ranking > 0) && ($currentPostion->position == null || $currentPostion->position == 0)){
			$lifeTime = (int) $results->start_ranking - 100;
		}else{
			$lifeTime = 0;
		}
		
		
		$latestPosition = $currentPostion->position <> null && $currentPostion->position > 0 ? $currentPostion->position : 0;
		
		$updateKeywordSearch = KeywordSearch::where('id',$keywordId)->where('request_id',$requestId)->update([
			'oneday_position'=>$oneDay,
			'one_week_ranking'=>$weekDay,
			'monthly_ranking'=>$monthDay,
			'life_ranking'=>$lifeTime,
			'position'=>$latestPosition
		]);

		if($oneDay <> 0){
			if($oneDay > 0){
				$desc = '<b>'. $results->keyword.'</b> moved <b class="activity-green">'.$oneDay.'</b> postion(s) up';
				ActivityLog::keywordsLogTracked($user_id,$requestId,$results->keyword,$desc,$results->keyword);		
			}elseif($oneDay < 0){
				$desc = '<b>'.$results->keyword.'</b> moved <b class="activity-red">'.abs($oneDay).'</b> postion(s) down';
				ActivityLog::keywordsLogTracked($user_id,$requestId,$results->keyword,$desc,$results->keyword);		
			}

		}
		
	}

	private function keywordsData($request_id){
        $count_of_keywords = KeywordSearch::where('request_id',$request_id)->count();
		$results = KeywordSearch::
		select(
			DB::raw('sum(CASE WHEN position > 0 THEN 1 ELSE 0 END) AS hundred'),
			DB::raw('sum(CASE WHEN position <= 20 AND position > 0 THEN 1 ELSE 0 END) AS twenty'),
			DB::raw('sum(CASE WHEN position <= 10 AND position > 0 THEN 1 ELSE 0 END) AS ten'),
			DB::raw('sum(CASE WHEN position <= 3 AND position > 0 THEN 1 ELSE 0 END) AS three')
		)
		->where('request_id',$request_id)
		->first();


		if(!empty($results->three)){
			$three = $results->three;
		}else{
			$three = 0;
		}

		if(!empty($results->ten)){
			$ten = $results->ten;
		}else{
			$ten = 0;
		}

		if(!empty($results->twenty)){
			$twenty = $results->twenty;
		}else{
			$twenty = 0;
		}

		if(!empty($results->hundred)){
			$hundred = $results->hundred;
		}else{
			$hundred = 0;
		}

		$if_exists = CampaignData::where('request_id',$request_id)->first();

		if(!empty($if_exists)){
			$campaign_data = CampaignData::where('request_id',$request_id)->update([
                'keywords_count'=>$count_of_keywords,
				'top_three'=>$three,
				'top_ten'=>$ten,
				'top_twenty'=>$twenty,
				'top_hundred'=>$hundred
			]);
		}else{		
			$campaign_data = CampaignData::create([
				'request_id',$request_id,
                'keywords_count'=>$count_of_keywords,
				'top_three'=>$three,
				'top_ten'=>$ten,
				'top_twenty'=>$twenty,
				'top_hundred'=>$hundred
			]);
		}
	}

	public function test(){
		$data = $this->updateRankingBkp('4','19','9');
		echo "<pre>";
		print_r($data);
		die;
	}

	private function updateRankingBkp($requestId,$keywordId,$user_id){
		$results = KeywordSearch::where('request_id',$requestId)->where('id',$keywordId)->orderBy('id','desc')->first();

		$currentPostion = KeywordPosition::lastestKeywordPosition($requestId,$keywordId);
		$oneData = KeywordPosition::oneDayKeyword($requestId,$keywordId);
		$weekData = KeywordPosition::weeklyKeywords($requestId,$keywordId);
		$monthData = KeywordPosition::monthlyKeywords($requestId,$keywordId);
		
		/*calculating single day data*/
		if((!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0) && (!empty($oneData->position) && $oneData->position <> null && $oneData->position > 0)){
			$oneDay = (int) $oneData->position - (int) $currentPostion->position;	
		}elseif((!empty($oneData->position) && $oneData->position <> null && $oneData->position > 0) && (!empty($currentPostion->position) && $currentPostion->position == null || $currentPostion->position == 0)){
			$oneDay = (int) $oneData->position - 100;
		}else{
			$oneDay = 0;	
		}
		
		
		/*calculating weekly data*/
		if((!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0) && (!empty($weekData->position) && $weekData->position <> null && $weekData->position > 0)){
			$weekDay = (int) $weekData->position - (int) $currentPostion->position;	
		}elseif((!empty($weekData->position) && $weekData->position <> null && $weekData->position > 0) && (!empty($currentPostion->position) && $currentPostion->position == null || $currentPostion->position == 0)){
			$weekDay = (int) $weekData->position - 100;
		}else{
			$weekDay = 0;	
		}
		
		/*calculating monthly data*/
		if((!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0) && (!empty($monthData->position) && $monthData->position <> null && $monthData->position > 0)){
			$monthDay = (int) $monthData->position - (int) $currentPostion->position;	
		}elseif((!empty($monthData->position) && $monthData->position <> null && $monthData->position > 0) && (!empty($currentPostion->position) && $currentPostion->position == null || $currentPostion->position == 0)){
			$monthDay = (int) $monthData->position - 100;
		}else{
			$monthDay = 0;	
		}
		
		/*calculating lifetime data*/
		if((!empty($results->position) && $results->position <> null && $results->position > 0) && (!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0)){
			$lifeTime = (int) $results->start_ranking - (int) $currentPostion->position;
		}elseif((!empty($results->position) && $results->start_ranking == null || $results->start_ranking == 0) && (!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0)){
			$lifeTime = 100 - (int) $currentPostion->position;
		}elseif((!empty($results->position) && $results->start_ranking <> null && $results->start_ranking > 0) && (!empty($currentPostion->position) && $currentPostion->position == null || $currentPostion->position == 0)){
			$lifeTime = (int) $results->start_ranking - 100;
		}else{
			$lifeTime = 0;
		}
		


		// echo "<pre>";
		// print_r($currentPostion);
		// print_r($oneData);
		// print_r($oneDay);
		// die;
		$latestPosition = $currentPostion->position <> null && $currentPostion->position > 0 ? $currentPostion->position : 0;
		
		$updateKeywordSearch = KeywordSearch::where('id',$keywordId)->where('request_id',$requestId)->update([
			'oneday_position'=>$oneDay,
			'one_week_ranking'=>$weekDay,
			'monthly_ranking'=>$monthDay,
			'life_ranking'=>$lifeTime,
			'position'=>$latestPosition
		]);

		if($oneDay <> 0){
			if($oneDay > 0){
				$desc = '<b>'. $results->keyword.'</b> moved <b class="activity-green">'.$oneDay.'</b> postion(s) up';
				ActivityLog::keywordsLogTracked($user_id,$requestId,$results->keyword,$desc,$results->keyword);		
			}elseif($oneDay < 0){
				$desc = '<b>'.$results->keyword.'</b> moved <b class="activity-red">'.abs($oneDay).'</b> postion(s) down';
				ActivityLog::keywordsLogTracked($user_id,$requestId,$results->keyword,$desc,$results->keyword);		
			}

		}
		
	}

}