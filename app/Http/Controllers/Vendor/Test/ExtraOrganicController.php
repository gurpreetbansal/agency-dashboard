<?php

namespace App\Http\Controllers\Vendor\Test;

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
	
	public function check_dfs_extra_keywords(){
		$client = null;	 $response = array()	;
		$details = SemrushUserAccount::whereHas('UserInfo', function($q){
			$q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
			->where('subscription_status', 1);
		})  
		->where('status','0')
		->select('id','user_id','domain_url','host_url','url_type','extra_keywords_cron_date','regional_db','rank_location')
		->where('id',2230)
		->first();


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

			echo "<pre>";
			print_r($ranked_keywords);
			die;

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

}