<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SemrushOrganicMetric;
use App\ActivityLog;
use RestClientException;
use App\Traits\ClientAuth;
use App\SemrushUserAccount;
use App\RegionalDatabse;

class SemrushOrganicSearchData extends Model {
	
	use ClientAuth;

	protected $table = 'semrush_organic_search_data';

	protected $primaryKey = 'id';

	protected $fillable = ['user_id', 'request_id', 'domain_name', 'keywords', 'position', 'previous_position', 'position_difference', 'search_volume', 'cpc', 'url', 'traffic', 'traffic_cost', 'competition', 'number_results', 'trends'];

   // public $timestamps = false;


	public static function store_extra_organic_keywords($user_id,$project_id,$domain_url){
		$client = null;
		$domainDetails = SemrushUserAccount::select('id','user_id','domain_url','host_url','url_type','extra_keywords_cron_date','regional_db')->where('id',$project_id)->first();
		$results = SemrushOrganicSearchData::where('request_id',$project_id)->whereDate('created_at',date('Y-m-d'))->first();
		$removeChar = ["https://", "http://", "/","www."];
		if($domainDetails->url_type == 2){
          $http_referer = str_replace($removeChar, "", $domainDetails->host_url);
        }else{
          $http_referer = str_replace($removeChar, "", $domainDetails->domain_url);
        }


        if($domainDetails->rank_location == null){
			$rd_data = RegionalDatabse::select('country','language')->where('short_name',$domainDetails->regional_db)->first();
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
			
			$client = self::DFSAuthConfig();
			
			
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
			if($ranked_keywords['tasks'][0]['result'] != null){

				if($ranked_keywords['tasks'][0]['result'][0]['items_count'] > 0){


					/*inserting metric data*/
					$metricInsertion = SemrushOrganicMetric::create([
						'request_id'=>$project_id,
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
						SemrushOrganicSearchData::where('request_id',$project_id)->delete();

						$diff = 0;
						foreach ($ranked_keywords['tasks'][0]['result'][0]['items'] as $key => $value) {
							$results = 	SemrushOrganicSearchData::where('user_id',$user_id)->where('request_id',$project_id)->where('keywords',$value['keyword_data']['keyword'])->orderBy('id','desc')->first();

							if($results <> null){
								$last_id =	$results->id;
							} else{
								$insertedData =  SemrushOrganicSearchData::create([
									'user_id'=>$user_id,
									'request_id' =>$project_id,
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
						//store activity data

						SemrushOrganicMetric::DFSKeywords_cron($project_id);
						$results = SemrushOrganicMetric::where('request_id',$project_id)->orderBy('id','desc')->skip(0)->take(2)->get();
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

							ActivityLog::keywordsLogTracked($user_id,$project_id,'keywords',$desc,'keywords');
						}
					}
				}
			}	

			SemrushUserAccount::where('id',$domainDetails->id)->update([
                'extra_keywords_cron_date'=>date('Y-m-d',strtotime('+1 week'))
            ]);
		}

	}
	
}
