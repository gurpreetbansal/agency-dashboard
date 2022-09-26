<?php

namespace App\Http\Controllers\ViewKey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\SemrushUserAccount;
use App\BacklinksData;
use App\BacklinkSummary;
use App\SemrushOrganicMetric;

class DataForSeoController extends Controller {


	public function ajax_organicKeywordRanking(Request $request){	
		$request_id = $request['campaignId'];
		$result  = SemrushOrganicMetric::where('request_id',$request_id)->orderBy('id','desc')->first();
		
		if(isset($result) && !empty($result)){
			$total_count = $result->total_count;
		} else{
			$total_count =0;
		}
		
			$resultOld =  SemrushOrganicMetric::where('request_id',$request_id)->orderBy('id','desc')->offset(1)->limit(1)->first();
			
				if(!empty($result->total_count) && !empty($resultOld->total_count)){
					if($resultOld->total_count > 2){
						
						if($resultOld->total_count){
							$organic_keywords = round(($result->total_count-$resultOld->total_count)/$resultOld->total_count * 100, 2);
						} else {
							 $organic_keywords = 0;
						}
					} else{
						$organic_keywords	=  100;
					}
				}else if(empty($result->total_count) && !empty($resultOld->total_count) ) {
					$organic_keywords	=  -100;
				} else if(!empty($result->total_count) && empty($resultOld->total_count) ) {
					$organic_keywords	=  100;
				} else{
					$organic_keywords	=  0;
				}
		
			return array('totalCount' => $total_count, 'organic_keywords' => $organic_keywords);
		
	}
}