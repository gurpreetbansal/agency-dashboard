<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CampaignData;

class SemrushOrganicMetric extends Model {

	protected $table = 'semrush_organic_metrics';

	protected $primaryKey = 'id';

	protected $fillable = ['request_id', 'semrush_organic_id', 'pos_1', 'pos_2_3', 'pos_4_10', 'pos_11_20', 'pos_21_30', 'pos_31_40', 'pos_41_50', 'pos_51_60', 'pos_61_70', 'pos_71_80', 'pos_81_90', 'pos_91_100', 'etv','impressions_etv','count','total_count','estimated_paid_traffic_cost','status'];

	public static function DFSKeywords($request_id){
		$results = SemrushOrganicMetric::where('request_id',$request_id)->orderBy('id','desc')->skip(0)->take(2)->get();
			// if(!empty($results)){
		if(count($results) >0){
				// echo "<pre>";
				// print_r($results);
				// die;
			if(!empty($results[0]->total_count) && !empty($results[1]->total_count) ) {
				if($results[1]->total_count > 2) {
					$count_history		=   $results[1]->total_count;

					if ($count_history) {
						$organic_keywords	=   round(($results[0]->total_count - $count_history)/$count_history*100, 2);
					} else {
						$organic_keywords = 0;
					}
				}else{
					$organic_keywords	=  100;
				}
			} else if(empty($results[0]->total_count) && !empty($results[1]->total_count) ) {
				$organic_keywords	=  -100;
			} else if(!empty($results[0]->total_count) && empty($results[1]->total_count) ) {
				$organic_keywords	=  100;
			} else{
				$organic_keywords	=  0;
			}
			$data =  array('avg'=>$organic_keywords,'total'=>$results[0]['total_count']);
		} else {
			$data =  array('avg'=>0,'total'=>0);
		}


		return $data;
	}


	public static function DFSKeywords_cron_bkp($request_id){
		$results = SemrushOrganicMetric::where('request_id',$request_id)->orderBy('id','desc')->skip(0)->take(2)->get();
		if(count($results) >0){
			if(!empty($results[0]->total_count) && !empty($results[1]->total_count) ) {
				if($results[1]->total_count > 2) {
					$count_history		=   $results[1]->total_count;

					if ($count_history) {
						$organic_keywords	=   round(($results[0]->total_count - $count_history)/$count_history*100, 2);
					} else {
						$organic_keywords = 0;
					}
				}else{
					$organic_keywords	=  100;
				}
			} else if(empty($results[0]->total_count) && !empty($results[1]->total_count) ) {
				$organic_keywords	=  -100;
			} else if(!empty($results[0]->total_count) && empty($results[1]->total_count) ) {
				$organic_keywords	=  100;
			} else{
				$organic_keywords	=  0;
			}
			$data =  array('avg'=>$organic_keywords,'total'=>$results[0]['total_count']);
		} else {
			$data =  array('avg'=>0,'total'=>0);
		}


		$if_exists = CampaignData::where('request_id',$request_id)->first();

		if(!empty($if_exists)){
			CampaignData::where('request_id', $request_id)->update([
				'keywords_count'=>$data['total'],
				'keyword_avg'=>$data['avg']
			]);
		}else{
			CampaignData::create([
				'request_id'=>$request_id,
				'keywords_count'=>$data['total'],
				'keyword_avg'=>$data['avg']
			]);
		}
	}


	public static function DFSKeywords_cron($request_id){
		$count = 0;
		$count = KeywordSearch::where('request_id',$request_id)->count();
		$if_exists = CampaignData::where('request_id',$request_id)->first();
		if(!empty($if_exists)){
			CampaignData::where('request_id',$request_id)->update([
				'keywords_count'=>$count,
				'keyword_avg'=>0

			]);
		}else{
			CampaignData::create([
				'request_id'=>$request_id,
				'keywords_count'=>$count,
				'keyword_avg'=>0
			]);
		}
		
	}


}
