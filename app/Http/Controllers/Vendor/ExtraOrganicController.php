<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SemrushOrganicMetric;
use App\SemrushOrganicSearchData;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrganicKeywordGrowthExport;


use App\Traits\ClientAuth;
use App\RegionalDatabse;
use App\ActivityLog;
use App\SemrushUserAccount;
use App\Language;
use App\KeywordPosition;
use App\User;
use Auth;

class ExtraOrganicController extends Controller {

	use ClientAuth;


	public function ajax_extra_organic_bar_chart(Request $request){
		$request_id = $request['campaignId'];

		$month = date('Y-m-d',strtotime(" -11 month"));

		$names = $total =$total_count =  $top_three = $four_ten = $eleven_twenty = $twentyone_fifty = array();
		for($i = 0; $i < 12; $i++){
			$data  = SemrushOrganicMetric::
			select(
				DB::raw('(pos_1+pos_2_3) AS top_three'),
				DB::raw('pos_4_10 AS four_ten'),
				DB::raw('pos_11_20 AS eleven_twenty'),
				DB::raw('(pos_21_30+pos_31_40+pos_41_50)  AS twentyone_fifty'),
				DB::raw('(total_count - (pos_1+pos_2_3+pos_4_10+pos_11_20+pos_21_30+pos_31_40+pos_41_50)) AS total'),
				DB::raw('total_count AS total_count'),
			)
			->where('request_id',$request_id)
			->whereMonth('updated_at',date('m', strtotime($i ."month", strtotime($month))))
			->whereYear('updated_at', date('Y', strtotime($i ."month", strtotime($month))))
			->orderBy('id','desc')	
			->first();


			if($data){
				$total_count[] =  $data->total_count; 
				$total[] =  $data->total; 
				$top_three[] =  $data->top_three; 
				$four_ten[] =  $data->four_ten; 
				$eleven_twenty[] =  $data->eleven_twenty; 
				$twentyone_fifty[] =  $data->twentyone_fifty; 
				$names[]  =  date('M, y', strtotime( $i ."month", strtotime($month)));
			}
		}
		

		$valuesCount =  12 - count($total);
		if($valuesCount < 12){
			for ($i=1; $i <= $valuesCount; $i++) { 		
				$total_count[] =  0; 
				$total[] =  0; 
				$top_three[] =  0; 
				$four_ten[] =  0; 
				$eleven_twenty[] =  0; 
				$twentyone_fifty[] =  0; 
				
				$names[]  =  date('M, y', strtotime($i ."month"));
			} 
		}

		return array('names' => json_encode($names), 
			'total_count' => json_encode($total_count),
			'total' => json_encode($total),
			'top_three' => json_encode($top_three),
			'four_ten' => json_encode($four_ten),
			'eleven_twenty' => json_encode($eleven_twenty),
			'twentyone_fifty' => json_encode($twentyone_fifty)
		);

	}

	public function ajax_extra_organic_keywords (Request $request){
		$keywords = SemrushOrganicSearchData::
		where('request_id', $request['campaignId'])
		->orderBy('position','asc')
		->limit(10)
		->get();
		//dd($keywords);
		return view('vendor.seo_sections.organic_keyword_growth.table', compact('keywords'))->render();
	}

	public function ajax_extra_organic_keywords_count (Request $request){
		$keywords = SemrushOrganicSearchData::
		where('request_id', $request['campaignId'])
		->orderBy('position','asc')
		->count();

		return array('count'=>$keywords);
	}

	public function extra_organic_keywords ($domain_name,$campaign_id){
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		if(\Request::segment(1) !== 'profile-settings'){
            $check = User::check_subscription($user_id); 
            if($check == 'expired'){
                return redirect('dashboard');
            }
        }
		$keywords = $this->keyword_data(100,$campaign_id,'position','asc','');

		return view('vendor.seo_sections.organic_keyword_growth.detail',['campaign_id'=>$campaign_id,'keywords'=>$keywords]);
	}

	private function keyword_data($limit,$campaign_id,$sortType,$sortBy,$query){
		$keywords = SemrushOrganicSearchData::
		where('request_id', $campaign_id)
		->where('keywords','LIKE','%'.$query.'%')
		->orderBy($sortType,$sortBy)
		->paginate($limit);
		
		return $keywords;
	}

	public function ajax_fetch_organic_keyword_data(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$campaign_id = $request['campaignID'];
			$sortType = $request['column_name'];
			$sortBy = $request['reverse_order'];
			$query  = $request['query'];

			$keywords = $this->keyword_data($limit,$campaign_id,$sortType,$sortBy,$query);
			return view('vendor.seo_sections.organic_keyword_growth.detail_data', compact('keywords','campaign_id'))->render();
		}
	}

	public function ajax_fetch_keyword_pagination(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$campaign_id = $request['campaignID'];
			$sortType = $request['column_name'];
			$sortBy = $request['reverse_order'];
			$query  = $request['query'];

			$keywords = $this->keyword_data($limit,$campaign_id,$sortType,$sortBy,$query);
			return view('vendor.seo_sections.organic_keyword_growth.detail_pagination', compact('keywords','campaign_id'))->render();
		}
	}

	public function generate_organic_keyword_excel($domain_name,$campaign_id){
		ob_end_clean(); 
		ob_start(); 
		return Excel::download(new OrganicKeywordGrowthExport($campaign_id),'Organic Keyword Growth.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
			'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		]);
	}

	public function check_dfs_extra_keywords(){
		$client = null;
		$domainDetails = SemrushUserAccount::whereHas('UserInfo', function($q){
			$q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
			->where('subscription_status', 1);
		})  
		->where('status','0')
		->select('id','user_id','domain_url','host_url','url_type','extra_keywords_cron_date','regional_db')
		->where(function ($query) {
			$query->where('extra_keywords_cron_date', '<=', date('Y-m-d'))
			->orWhereNull('extra_keywords_cron_date');
		})
		->limit(1)
		->get();
		
		file_put_contents(dirname(__FILE__).'/logs/dfs_keywords.txt',print_r($domainDetails,true));
        //die;
		if(!empty($domainDetails)){
			foreach($domainDetails  as $details){
				
				$results = SemrushOrganicSearchData::where('request_id',$details->id)->whereDate('created_at',date('Y-m-d'))->first();


				$removeChar = ["https://", "http://", "/","www."];
				if($details->url_type == 2){
					$http_referer = str_replace($removeChar, "", $details->host_url);
				}else{
					$http_referer = str_replace($removeChar, "", $details->domain_url);
				}


				if($details->rank_location == null){
					$rd_data = RegionalDatabse::select('country','language')->where('short_name',$details->regional_db)->first();
					if($rd_data->country <> null && $rd_data->language <> null){
						$location_name = $rd_data->country; 
						$language = $rd_data->language;
					}else{
						$location_name = 'United States'; 
						$language = 'English';
					}
				}else{
					$location_name = 'United States'; 
					$language = 'English';
				}

				if(empty($results)){

					$client = $this->DFSAuth();
					$post_arrays[] = array(
						"target" => $http_referer,
						"language_name" => $language,
						"location_name"=>$location_name,
						"filters" => [
							["keyword_data.keyword_info.search_volume", "<>", 0],
							"and",
							[
								["ranked_serp_element.serp_item.type", "<>", "paid"],
								"or",
								["ranked_serp_element.serp_item.is_malicious", "=", false]
							]
						],
                        // "order_by" => ["keyword_data.serp_item.rank_group,ASC","keyword_data.keyword_info.search_volume,desc"],
						"limit"=>700
					);

					
					try {
						$ranked_keywords = $client->post('/v3/dataforseo_labs/ranked_keywords/live', $post_arrays);
					} catch (RestClientException $e) {
						return $e->getMessage();
					}



                    //file_put_contents(dirname(__FILE__).'/logs/dfs_keywords_records.txt',print_r($ranked_keywords,true));
					if($ranked_keywords['tasks'][0]['result'] != null){

						if($ranked_keywords['tasks'][0]['result'][0]['items_count'] > 0){


							/*inserting metric data*/
							$metricInsertion = SemrushOrganicMetric::create([
								'request_id'=>$details->id,
								'pos_1'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_1'],
								'pos_2_3'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_2_3'],
								'pos_4_10'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_4_10'],
								'pos_11_20'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_11_20'],
								'pos_21_30'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_21_30'],
								'pos_31_40'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_31_40'],
								'pos_41_50'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_41_50'],
								'pos_51_60'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_51_60'],
								'pos_61_70'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_61_70'],
								'pos_71_80'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_71_80'],
								'pos_81_90'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_81_90'],
								'pos_91_100'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_91_100'],
								'etv'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['etv'],
								'impressions_etv'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['impressions_etv'],
								'count'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['count'],
								'total_count'=>$ranked_keywords['tasks'][0]['result'][0]['total_count'],
								'estimated_paid_traffic_cost'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['estimated_paid_traffic_cost'],
							]);


							if($metricInsertion){
								SemrushOrganicSearchData::where('request_id',$details->id)->delete();

								$diff = 0;
								foreach ($ranked_keywords['tasks'][0]['result'][0]['items'] as $key => $value) {
									$results =  SemrushOrganicSearchData::where('user_id',$details->user_id)->where('request_id',$details->id)->where('keywords',$value['keyword_data']['keyword'])->orderBy('id','desc')->first();

									if($results <> null){
										$last_id =  $results->id;
									} else{
										$insertedData =  SemrushOrganicSearchData::create([
											'user_id'=>$details->user_id,
											'request_id' =>$details->id,
											'domain_name'=>$value['ranked_serp_element']['serp_item']['domain'],
											'keywords'=>$value['keyword_data']['keyword'],
											'position'=>$value['ranked_serp_element']['serp_item']['rank_group'],
											'previous_position'=>$value['ranked_serp_element']['serp_item']['rank_group'],
											'position_difference'=>$diff,
											'search_volume'=>$value['keyword_data']['keyword_info']['search_volume'],
											'cpc'=>$value['keyword_data']['keyword_info']['cpc'],
											'url'=>$value['ranked_serp_element']['serp_item']['url'],   
											'traffic'=>$value['ranked_serp_element']['serp_item']['etv'],
											'traffic_cost'=>$value['ranked_serp_element']['serp_item']['estimated_paid_traffic_cost'],
											'competition'=>$value['keyword_data']['keyword_info']['competition'],
											'number_results'=>$value['ranked_serp_element']['se_results_count']
										]);

										if($insertedData){
											$last_id = $insertedData->id;
										}else{
											$last_id = 0;
										}
									}
								}


								$this->DFSKeywordsLog($details->user_id,$details->id);        

								SemrushUserAccount::where('id',$details->id)->update([
									'extra_keywords_cron_date'=>date('Y-m-d',strtotime('+1 week'))
								]);          
							}
						}
					}

					
				}   
				$post_arrays  = array();
			}
		}
	}

	private function DFSKeywordsLog($user_id,$campaign_id){
		SemrushOrganicMetric::DFSKeywords_cron($campaign_id);
		$results = SemrushOrganicMetric::where('request_id',$campaign_id)->orderBy('id','desc')->skip(0)->take(2)->get();
		if(count($results) >0){
			if(!empty($results[0]) && !empty($results[1])){
				$total = $results[0]->total_count - $results[1]->total_count;
			}else{
				$total = $results[0]->total_count - 0;
			}

			if($total > 0){
				$desc = '<b class="activity-green">'. $total. "</b> new keywords have started ranking today";
			}elseif($total < 0){
				$desc = '<b class="activity-red">'. abs($total). "</b> keywords have lost ranking today";
			}else{
				$desc = "New keywords have not started ranking today";
			}

			ActivityLog::keywordsLogTracked($user_id,$campaign_id,'keywords',$desc,'keywords');
		}
	}

	public function ajax_get_latest_organic_keyword_growth_bkp (Request $request){
		$client = null;	 $response = array()	;
		$details = SemrushUserAccount::whereHas('UserInfo', function($q){
			$q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
			->where('subscription_status', 1);
		})  
		->where('status','0')
		->select('id','user_id','domain_url','host_url','url_type','extra_keywords_cron_date','regional_db')
		->where('id',$request->campaign_id)
		->first();

		if(!empty($details)){

			
			$removeChar = ["https://", "http://" ,'/', "www."];
			if($details->url_type == 2){
				$http_referer = str_replace($removeChar, "", $details->host_url);
			}else{
				$http_referer = str_replace($removeChar, "", $details->domain_url);
			}

			if($details->rank_location == null){
				$rd_data = RegionalDatabse::select('country')->where('short_name',$details->regional_db)->first();
				$location_name = ($rd_data->country <> NULL)?$rd_data->country:'United States';
				$language = ($rd_data->language <> NULL)?$rd_data->language:'English';
			}else{
				$location_name = 'United States'; 
				$language = 'English';
			}

			$client = $this->DFSAuth();
			$post_arrays[] = array(
				"target" => $http_referer,
				"language_name" => $language,
				"location_name"=>$location_name,
				"filters" => [
					["keyword_data.keyword_info.search_volume", "<>", 0],
					"and",
					[
						["ranked_serp_element.serp_item.type", "<>", "paid"],
						"or",
						["ranked_serp_element.serp_item.is_malicious", "=", false]
					]
				],
				"limit"=>700
			);

			try {
				$ranked_keywords = $client->post('/v3/dataforseo_labs/ranked_keywords/live', $post_arrays);

			} catch (RestClientException $e) {
				return $e->getMessage();
			}
			// dd($ranked_keywords);
			if($ranked_keywords['tasks'][0]['result'] != null){

				if($ranked_keywords['tasks'][0]['result'][0]['items_count'] > 0){
					$last_entry = SemrushOrganicMetric::select('id','request_id','updated_at')->where('request_id',$details->id)->orderBy('id','desc')->first();
						$days = 0;
					if($last_entry <> null){
						if($last_entry->updated_at <> null){
							$date1=date_create(date('Y-m-d'));
							$date2=date_create(date('Y-m-d',strtotime($last_entry->updated_at)));
							$diff=date_diff($date1,$date2);
							$days = $diff->days;
						}
					}

					if((!empty($last_entry) || $last_entry <> null) && $days < 7){
						/*updating metric data*/
						$metricInsertion = SemrushOrganicMetric::where('id',$last_entry->id)->update([
							'request_id'=>$details->id,
							'pos_1'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_1'],
							'pos_2_3'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_2_3'],
							'pos_4_10'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_4_10'],
							'pos_11_20'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_11_20'],
							'pos_21_30'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_21_30'],
							'pos_31_40'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_31_40'],
							'pos_41_50'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_41_50'],
							'pos_51_60'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_51_60'],
							'pos_61_70'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_61_70'],
							'pos_71_80'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_71_80'],
							'pos_81_90'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_81_90'],
							'pos_91_100'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_91_100'],
							'etv'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['etv'],
							'impressions_etv'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['impressions_etv'],
							'count'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['count'],
							'total_count'=>$ranked_keywords['tasks'][0]['result'][0]['total_count'],
							'estimated_paid_traffic_cost'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['estimated_paid_traffic_cost'],
						]);
					}else{
						/*inserting metric data*/
						$metricInsertion = SemrushOrganicMetric::create([
							'request_id'=>$details->id,
							'pos_1'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_1'],
							'pos_2_3'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_2_3'],
							'pos_4_10'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_4_10'],
							'pos_11_20'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_11_20'],
							'pos_21_30'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_21_30'],
							'pos_31_40'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_31_40'],
							'pos_41_50'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_41_50'],
							'pos_51_60'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_51_60'],
							'pos_61_70'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_61_70'],
							'pos_71_80'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_71_80'],
							'pos_81_90'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_81_90'],
							'pos_91_100'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_91_100'],
							'etv'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['etv'],
							'impressions_etv'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['impressions_etv'],
							'count'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['count'],
							'total_count'=>$ranked_keywords['tasks'][0]['result'][0]['total_count'],
							'estimated_paid_traffic_cost'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['estimated_paid_traffic_cost'],
						]);
					}
					
					if($metricInsertion){
						SemrushOrganicSearchData::where('request_id',$details->id)->delete();

						$diff = 0;
						foreach ($ranked_keywords['tasks'][0]['result'][0]['items'] as $key => $value) {
							$results =  SemrushOrganicSearchData::where('user_id',$details->user_id)->where('request_id',$details->id)->where('keywords',$value['keyword_data']['keyword'])->orderBy('id','desc')->first();

							if($results <> null){
								$last_id =  $results->id;
							} else{
								$insertedData =  SemrushOrganicSearchData::create([
									'user_id'=>$details->user_id,
									'request_id' =>$details->id,
									'domain_name'=>$value['ranked_serp_element']['serp_item']['domain'],
									'keywords'=>$value['keyword_data']['keyword'],
									'position'=>$value['ranked_serp_element']['serp_item']['rank_group'],
									'previous_position'=>$value['ranked_serp_element']['serp_item']['rank_group'],
									'position_difference'=>$diff,
									'search_volume'=>$value['keyword_data']['keyword_info']['search_volume'],
									'cpc'=>$value['keyword_data']['keyword_info']['cpc'],
									'url'=>$value['ranked_serp_element']['serp_item']['url'],   
									'traffic'=>$value['ranked_serp_element']['serp_item']['etv'],
									'traffic_cost'=>$value['ranked_serp_element']['serp_item']['estimated_paid_traffic_cost'],
									'competition'=>$value['keyword_data']['keyword_info']['competition'],
									'number_results'=>$value['ranked_serp_element']['se_results_count']
								]);

								if($insertedData){
									$last_id = $insertedData->id;
								}else{
									$last_id = 0;
								}
							}
						}

						$this->DFSKeywordsLog($details->user_id,$details->id);  
					}
					SemrushUserAccount::where('id',$details->id)->update([
						'extra_keywords_cron_date'=>date('Y-m-d',strtotime('+1 week'))
					]);
					$response['status'] = 1;
				}else{
					$response['status'] = 0;
				}
			}

			
		}else{
			$response['status'] = 0;
		}
		return response()->json($response);
	}

	public function ajax_get_latest_organic_keyword_growth(Request $request){

		$client = null;	 $response = array()	;
		$details = SemrushUserAccount::whereHas('UserInfo', function($q){
			$q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
			->where('subscription_status', 1);
		})  
		->where('status','0')
		->select('id','user_id','domain_url','host_url','url_type','extra_keywords_cron_date','regional_db','rank_location')
		->where('id',$request->campaign_id)
		->first();
		file_put_contents(dirname(__FILE__)."/logs/details.txt", print_r($details,true));
	//	die;

		
		if(!empty($details)){
			$removeChar = ["https://", "http://" ,'/', "www."];
			if($details->url_type == 2){
				$http_referer = str_replace($removeChar, "", $details->host_url);
			}else{
				$http_referer = str_replace($removeChar, "", $details->domain_url);
			}

			if($details->rank_location !== null){
				$rd_data = RegionalDatabse::select('country')->where('short_name',$details->regional_db)->first();
				$location_name = ($rd_data->country <> NULL)?$rd_data->country:'United States';
				$language = ($rd_data->language <> NULL)?$rd_data->language:'English';
			}else{
				$location_name = 'United States'; 
				$language = 'English';
			}

			file_put_contents(dirname(__FILE__)."/logs/language.txt", print_r($language,true));

			$client = $this->DFSAuth();
			$post_arrays[] = array(
				"target" => $http_referer,
				"language_name" => $language,
				"location_name"=>$location_name,
				"filters" => [
					["keyword_data.keyword_info.search_volume", "<>", 0],
					"and",
					[
						["ranked_serp_element.serp_item.type", "<>", "paid"],
						"or",
						["ranked_serp_element.serp_item.is_malicious", "=", false]
					]
				],
				"limit"=>700
			);
				 file_put_contents(dirname(__FILE__)."/logs/post_arrays.txt", print_r($post_arrays,true));

			try {
				$ranked_keywords = $client->post('/v3/dataforseo_labs/ranked_keywords/live', $post_arrays);
				 file_put_contents(dirname(__FILE__)."/logs/ranked_keywords.txt", print_r($ranked_keywords,true));
			} catch (RestClientException $e) {
				return $e->getMessage();
			}

			if($ranked_keywords['tasks_error'] == 0){

				if($ranked_keywords['tasks'][0]['result'] != null && $ranked_keywords['tasks'][0]['result'][0]['items_count'] > 0){
					$last_entry = SemrushOrganicMetric::select('id','request_id','updated_at')->where('request_id',$details->id)->orderBy('id','desc')->first();
					
					$days = 0;
					if($last_entry->updated_at <> null){
						$date1=date_create(date('Y-m-d'));
						$date2=date_create(date('Y-m-d',strtotime($last_entry->updated_at)));
						$diff=date_diff($date1,$date2);
						$days = $diff->days;
					}

					if((!empty($last_entry) || $last_entry <> null) && $days < 7){
						/*updating metric data*/
						$metricInsertion = SemrushOrganicMetric::where('id',$last_entry->id)->update([
							'request_id'=>$details->id,
							'pos_1'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_1'],
							'pos_2_3'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_2_3'],
							'pos_4_10'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_4_10'],
							'pos_11_20'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_11_20'],
							'pos_21_30'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_21_30'],
							'pos_31_40'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_31_40'],
							'pos_41_50'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_41_50'],
							'pos_51_60'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_51_60'],
							'pos_61_70'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_61_70'],
							'pos_71_80'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_71_80'],
							'pos_81_90'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_81_90'],
							'pos_91_100'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_91_100'],
							'etv'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['etv'],
							'impressions_etv'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['impressions_etv'],
							'count'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['count'],
							'total_count'=>$ranked_keywords['tasks'][0]['result'][0]['total_count'],
							'estimated_paid_traffic_cost'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['estimated_paid_traffic_cost'],
						]);
					}else{
						/*inserting metric data*/
						$metricInsertion = SemrushOrganicMetric::create([
							'request_id'=>$details->id,
							'pos_1'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_1'],
							'pos_2_3'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_2_3'],
							'pos_4_10'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_4_10'],
							'pos_11_20'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_11_20'],
							'pos_21_30'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_21_30'],
							'pos_31_40'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_31_40'],
							'pos_41_50'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_41_50'],
							'pos_51_60'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_51_60'],
							'pos_61_70'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_61_70'],
							'pos_71_80'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_71_80'],
							'pos_81_90'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_81_90'],
							'pos_91_100'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_91_100'],
							'etv'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['etv'],
							'impressions_etv'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['impressions_etv'],
							'count'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['count'],
							'total_count'=>$ranked_keywords['tasks'][0]['result'][0]['total_count'],
							'estimated_paid_traffic_cost'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['estimated_paid_traffic_cost'],
						]);
					}
					
					if($metricInsertion){
						SemrushOrganicSearchData::where('request_id',$details->id)->delete();

						$diff = 0;
						foreach ($ranked_keywords['tasks'][0]['result'][0]['items'] as $key => $value) {
							$results =  SemrushOrganicSearchData::where('user_id',$details->user_id)->where('request_id',$details->id)->where('keywords',$value['keyword_data']['keyword'])->orderBy('id','desc')->first();

							if($results <> null){
								$last_id =  $results->id;
							} else{
								$insertedData =  SemrushOrganicSearchData::create([
									'user_id'=>$details->user_id,
									'request_id' =>$details->id,
									'domain_name'=>$value['ranked_serp_element']['serp_item']['domain'],
									'keywords'=>$value['keyword_data']['keyword'],
									'position'=>$value['ranked_serp_element']['serp_item']['rank_group'],
									'previous_position'=>$value['ranked_serp_element']['serp_item']['rank_group'],
									'position_difference'=>$diff,
									'search_volume'=>$value['keyword_data']['keyword_info']['search_volume'],
									'cpc'=>$value['keyword_data']['keyword_info']['cpc'],
									'url'=>$value['ranked_serp_element']['serp_item']['url'],   
									'traffic'=>$value['ranked_serp_element']['serp_item']['etv'],
									'traffic_cost'=>$value['ranked_serp_element']['serp_item']['estimated_paid_traffic_cost'],
									'competition'=>$value['keyword_data']['keyword_info']['competition'],
									'number_results'=>$value['ranked_serp_element']['se_results_count']
								]);

								if($insertedData){
									$last_id = $insertedData->id;
								}else{
									$last_id = 0;
								}
							}
						}

						$this->DFSKeywordsLog($details->user_id,$details->id);  
					}
				}
				SemrushUserAccount::where('id',$details->id)->update([
					'extra_keywords_cron_date'=>date('Y-m-d',strtotime('+1 week'))
				]);
				
				$response['status'] = 1;
			}

		}else{
			$response['status'] = 0;
		}
		return response()->json($response);
	}

	public function ajax_get_organic_keyword_growth_time(Request $request){
		$response = array();
		$data = SemrushUserAccount::select('id','extra_keywords_cron_date')->where('id',$request->campaign_id)->first();
		if(!empty($data) && isset($data)){
			$get_date = date('Y-m-d',strtotime('-7 days',strtotime($data->extra_keywords_cron_date)));
			$time_span = KeywordPosition::calculate_time_span($get_date);
			$response['status'] = 1; 
			$response['time'] 	= "Last Updated: ".$time_span." (".date('M d, Y',strtotime($get_date)).")" ;
		} else {
			$response['status'] = 0;
			$response['message'] = 'Getting Error to update data';
		}
		return $response;
	}

	public function ajax_get_organic_keyword_growth_time_bkp(Request $request){
		$response = array();
		$data = SemrushOrganicMetric::select('id','updated_at')->where('request_id',$request->campaign_id)->orderBy('id','desc')->first();
		if(!empty($data) && isset($data)){
			$time_span = KeywordPosition::calculate_time_span($data->updated_at);
			$response['status'] = 1; 
			$response['time'] 	= "Last Updated: ".$time_span." (".date('M d, Y',strtotime($data->updated_at)).")" ;
		} else {
			$response['status'] = 0;
			$response['message'] = 'Getting Error to update data';
		}
		return $response;
	}


	public function ajax_extra_organic_chart_stats(Request $request){
		$request_id = $request['campaignId'];

		$names = $total =$total_count =  $top_three = $four_ten = $eleven_twenty = $twentyone_fifty = '';
		$data  = SemrushOrganicMetric::
		select(
			DB::raw('(pos_1+pos_2_3) AS top_three'),
			DB::raw('pos_4_10 AS four_ten'),
			DB::raw('pos_11_20 AS eleven_twenty'),
			DB::raw('(pos_21_30+pos_31_40+pos_41_50)  AS twentyone_fifty'),
			DB::raw('(total_count - (pos_1+pos_2_3+pos_4_10+pos_11_20+pos_21_30+pos_31_40+pos_41_50)) AS total'),
			DB::raw('total_count AS total_count'),
		)
		->where('request_id',$request_id)
		->orderBy('id','desc')	
		->first();

		if($data){
			$total_count =  $data->total_count; 
			$total =  $data->total; 
			$top_three =  $data->top_three; 
			$four_ten =  $data->four_ten; 
			$eleven_twenty =  $data->eleven_twenty; 
			$twentyone_fifty =  $data->twentyone_fifty; 
		}
		return array(
			'total_count' => json_encode($total_count),
			'total' => json_encode($total),
			'top_three' => json_encode($top_three),
			'four_ten' => json_encode($four_ten),
			'eleven_twenty' => json_encode($eleven_twenty),
			'twentyone_fifty' => json_encode($twentyone_fifty)
		);
	}

}