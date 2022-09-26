<?php

namespace App\Http\Controllers\ViewKey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\SemrushUserAccount;
use App\GoogleAdsCustomer;
use Auth;
use Session;
use App\GoogleAnalyticsUsers;
use App\AdwordsCampaignDetail;
use App\AdwordsKeywordDetail;
use App\AdwordsAdTextDetail;
use App\AdwordsAdGroupDetail;
use App\AdwordsPlaceHolderDetail;
use App\ModuleByDateRange;
use DB;
use DataTables;


class PpcController extends Controller {


	public function ppc_date_range_data(Request $request){	
			$account_id = $request->account_id;
			$checkIfExists = ModuleByDateRange::where('request_id',$request->campaign_id)->where('module','google_ads')->first();
				 
			if(isset($request->start_date) && isset($request->end_date)){
				 $start_date = date('Ymd',strtotime($request->start_date));
				 $end_date =date('Ymd',strtotime($request->end_date));
				 $diff = abs(strtotime($end_date) - strtotime($start_date));
				 $days = floor(($diff)/ (60*60*24));
				// $compare_start_date = date('Ymd', strtotime('-'.$days.'days', strtotime($start_date)));
			 }  elseif(isset($checkIfExists->start_date) && isset($checkIfExists->end_date)){
				   $start_date = date('Ymd',strtotime($checkIfExists->start_date));
				   $end_date = date('Ymd',strtotime($checkIfExists->end_date));
			 } else{
				   $start_date = date('Ymd',strtotime('-30 days'));
				   $end_date = date('Ymd',strtotime('-1 day'));
			 }
				
			 $dates = $this->getDatesFromRange($start_date,$end_date);	 
			 $summary_chart = $this->chartData($dates,$account_id);


			if(isset($request->cmp_start_date) && isset($request->cmp_end_date)){
			
				$cmp_diff = abs(strtotime(date('Ymd',strtotime($request->cmp_start_date))) - strtotime(date('Ymd',strtotime($request->cmp_end_date))));
				$cmp_days = floor(($cmp_diff)/ (60*60*24));

				$compare_start_date = date('Ymd', strtotime($request->cmp_start_date));
				$compare_end_date = date('Ymd', strtotime($request->cmp_end_date));

				if($cmp_days != $days){
					return false;
				}

			
			}
			 
			 if($request->has('compare')){
				$compare_dates = $this->getDatesFromRange($compare_start_date,$compare_end_date);	
				$compare_summary_chart = $this->chartData($compare_dates,$account_id);
				$summary_chart['clicks_previous'] = $compare_summary_chart['clicks'];
				$summary_chart['conversions_previous'] = $compare_summary_chart['conversions'];
				$summary_chart['impressions_previous'] = $compare_summary_chart['impressions'];
				$summary_chart['cost_previous'] = $compare_summary_chart['cost'];
				$summary_chart['cpc_previous'] = $compare_summary_chart['cpc'];
				$summary_chart['revenue_per_click_previous'] = $compare_summary_chart['revenue_per_click'];
				$summary_chart['total_value_previous'] = $compare_summary_chart['total_value'];
				$summary_chart['averagecpm_previous'] = $compare_summary_chart['averagecpm'];
			 }
			 

			  if(isset($checkIfExists->compare_start_date) && isset($checkIfExists->compare_end_date)){
				   $compare_start_date = date('Ymd',strtotime($checkIfExists->compare_start_date));
				   $compare_end_date = date('Ymd',strtotime($checkIfExists->compare_end_date));

				$compare_dates = $this->getDatesFromRange($compare_start_date,$compare_end_date);	
				$compare_summary_chart = $this->chartData($compare_dates,$account_id);
				$summary_chart['clicks_previous'] = $compare_summary_chart['clicks'];
				$summary_chart['conversions_previous'] = $compare_summary_chart['conversions'];
				$summary_chart['impressions_previous'] = $compare_summary_chart['impressions'];
				$summary_chart['cost_previous'] = $compare_summary_chart['cost'];
				$summary_chart['cpc_previous'] = $compare_summary_chart['cpc'];
				$summary_chart['revenue_per_click_previous'] = $compare_summary_chart['revenue_per_click'];
				$summary_chart['total_value_previous'] = $compare_summary_chart['total_value'];
				$summary_chart['averagecpm_previous'] = $compare_summary_chart['averagecpm'];	
			 }
			
			 $summary_chart['date_range'] =  $dates;
			
			 return response()->json($summary_chart);
		 }


		 function getDatesFromRange($start, $end) { 
			// Declare an empty array 
			$array = array(); 
			  
			// Use strtotime function 
			$Variable1 = strtotime($start); 
			$Variable2 = strtotime($end); 
			  
			// Use for loop to store dates into array 
			// 86400 sec = 24 hrs = 60*60*24 = 1 day 
			for ($currentDate = $Variable1; $currentDate <= $Variable2;  
											$currentDate += (86400)) { 
												  
			$Store = date('Y-m-d', $currentDate); 
			$array[] = $Store; 
			} 
			  
			// Display the dates in array format 
			return $array;
    
		} 


		private function chartData($date_range,$account_id){
		if(!empty($date_range)){
			$clicks = $conversions = $cost_per_conversion = $cpc = $impressions = $clicks_previous = $conversions_previous =$impressions_previous = $cost = $cpc_previous = $averagecpm = $revenue_per_click =$revenue_per_click_previous = $total_value = $total_value_previous =$cost_previous =  array();
			foreach($date_range as $range){
				$data = AdwordsCampaignDetail::where('client_id',$account_id)
					 ->where('day',$range)
					 ->select( 
							'*' ,
							DB::raw('sum(cost) as cost'),
							DB::raw('sum(impressions) as impressions'),
							DB::raw('sum(clicks) as clicks'),
							DB::raw('sum(conversions) as conversions'),
							DB::raw('sum(cost)/sum(conversions) as cost_per_conversions'),
							DB::raw('sum(conversion_value)/sum(clicks) as revenue_per_clicks'),
							DB::raw('sum(cost)/sum(impressions)*1000 as averagecpm'),
							DB::raw('sum(cost)/sum(clicks) as average_cpc')
						)
						->first();
						 
			// echo '<pre>';
			// echo 'day: '.$range;
			// print_r($data);
			// die;
				$clicks[] = isset($data['clicks']) && !empty($data['clicks']) ? (float)$data['clicks']: 0;
				$conversions[] = isset($data['conversions']) && !empty($data['conversions']) ? (float)$data['conversions'] : 0;
				$cost_per_conversion[] = isset($data['cost_per_conversion']) && !empty($data['cost_per_conversion']) ? (float)number_format($data['cost_per_conversion'], 2, '.', '') : 0;
				$cpc[] = isset($data['average_cpc']) && !empty($data['average_cpc']) ? (float)number_format($data['average_cpc'], 2, '.', ''): 0;
				$impressions[] = isset($data['impressions']) && !empty($data['impressions']) ? (float)$data['impressions'] : 0;
				
				$cost[] = isset($data['cost']) && !empty($data['cost']) ? number_format((float)$data['cost'],2,'.','') : 0;
				$averagecpm[] = isset($data['averagecpm']) && !empty($data['averagecpm'])?(float)number_format($data['averagecpm'],2,'.',''):0;
				$revenue_per_click[] = isset($data['revenue_per_clicks']) && !empty($data['revenue_per_clicks'])?(float)number_format($data['revenue_per_clicks'],2,'.',''):0;
				
				$total_value[] = isset($data['conversion_value']) && !empty($data['conversion_value'])?(float)number_format($data['conversion_value'],2,'.',''):0;
				
				/*previous values*/
				$clicks_previous[] = 0;
				$conversions_previous[] = 0;
				$impressions_previous[] = 0;
				$cpc_previous[] = 0;		
				$revenue_per_click_previous[]=0;				
				$total_value_previous[]=0;
				$cost_previous[] = 0;
				$averagecpm_previous[] = 0;
				
			}
			
			
			$final = array('clicks'=>$clicks,'conversions'=>$conversions,'cost_per_conversion'=>$cost_per_conversion,'cpc'=>$cpc,'impressions'=>$impressions,'clicks_previous'=>$clicks_previous,'conversions_previous'=>$conversions_previous,'impressions_previous'=>$impressions_previous,'cost'=>$cost,'cpc_previous'=>$cpc_previous,'averagecpm'=>$averagecpm,'revenue_per_click'=>$revenue_per_click,'revenue_per_click_previous'=>$revenue_per_click_previous,'total_value'=>$total_value,'total_value_previous'=>$total_value_previous,'cost_previous'=>$cost_previous,'averagecpm_previous'=>$averagecpm_previous);
			return $final;
			
		}
	}


	public function summary_statistics(Request $request){
	   $campaign_id = $request->campaign_id;
	   $account_id = $request->account_id;
	   
	   $if_exists =  ModuleByDateRange::where('request_id',$campaign_id)->where('module','google_ads')->first();
		   if(isset($if_exists->start_date) && isset($if_exists->end_date)){
			   	$start_date = date('Ymd',strtotime($if_exists->start_date));
				$end_date = date('Ymd',strtotime($if_exists->end_date));
		   }else{	
			   $start_date = date('Ymd',strtotime('-30 days'));
			   $end_date = date('Ymd',strtotime('-1 day'));
		   }
		 
		 
		
	
		$summary_stats = AdwordsCampaignDetail::where('client_id',$account_id)
			// ->where('report_date',date('Y-m-d'))
		->whereBetween('day',[$start_date,$end_date])
			->select(
				DB::raw('sum(impressions) as impressions'),
				DB::raw('sum(clicks) as clicks'),
				DB::raw('sum(cost) as cost'),
				DB::raw('sum(conversions) as conversions'),
				DB::raw('sum(cost)/sum(conversions) as cost_per_conversion'),
				DB::raw('sum(clicks)/sum(impressions)*100 as ctr'),
				DB::raw('sum(conversions)/sum(clicks)*100 as conversion_rate'),
				DB::raw('sum(cost)/sum(clicks)*100 as average_cpc'),
				DB::raw('accountCurrencyCode as currency_code')
				
			)->first();
			
			
			
			if($summary_stats['accountCurrencyCode'] == 'CAD'){
				$currency_code = 'CA$';
			} elseif($summary_stats['accountCurrencyCode'] == 'US'){
				$currency_code = '$';
			} else{
				$currency_code ='$';
			}
			
		
				$summary_stats_data['impressions'] = isset($summary_stats['impressions'])?$summary_stats['impressions']:'0';
				$summary_stats_data['clicks'] = isset($summary_stats['clicks'])?$summary_stats['clicks']:'0';
				$summary_stats_data['cost'] = isset($summary_stats['cost'])?$currency_code.number_format($summary_stats['cost'],2,'.',''):'0';
				$summary_stats_data['conversions'] = isset($summary_stats['conversions'])?$summary_stats['conversions']:'0';
				$summary_stats_data['cost_per_conversion'] = isset($summary_stats['cost_per_conversion'])?$currency_code.number_format($summary_stats['cost_per_conversion'],2,'.',''):'0';
				$summary_stats_data['ctr'] = isset($summary_stats['ctr'])?number_format($summary_stats['ctr'],2,'.','').'%':'0%';
				$summary_stats_data['conversion_rate'] = isset($summary_stats['conversion_rate'])?number_format($summary_stats['conversion_rate'],2,'.','').'%':'0%';
				$summary_stats_data['average_cpc'] = isset($summary_stats['average_cpc'])?$currency_code.number_format($summary_stats['average_cpc']/100,2,'.',''):'0';


				if(isset($if_exists->compare_start_date) && isset($if_exists->compare_end_date)){
					$compare_start_date = date('Ymd', strtotime($if_exists->compare_start_date));
					$compare_end_date = date('Ymd', strtotime($if_exists->compare_end_date));

					$comparison = AdwordsCampaignDetail::where('client_id',$account_id)
					->whereBetween('day',[$compare_start_date,$compare_end_date])
					->select(
						DB::raw('sum(impressions) as impressions'),
						DB::raw('sum(clicks) as clicks'),
						DB::raw('sum(cost) as cost'),
						DB::raw('sum(conversions) as conversions'),
						DB::raw('sum(cost)/sum(conversions) as cost_per_conversion'),
						DB::raw('sum(clicks)/sum(impressions)*100 as ctr'),
						DB::raw('sum(conversions)/sum(clicks)*100 as conversion_rate'),
						DB::raw('sum(cost)/sum(clicks)*100 as average_cpc'),
						
						DB::raw('accountCurrencyCode as currency_code')
						
					)->first();					
				}


				/*comparison data*/
				$summary_stats_data['previous_impressions'] = isset($comparison['impressions'])?number_format($comparison['impressions'],0,' ',','):'0';
				$summary_stats_data['previous_clicks'] = isset($comparison['clicks'])?number_format($comparison['clicks'],0,' ',','):'0';
				$summary_stats_data['previous_cost'] = isset($comparison['cost'])?$currency_code.number_format($comparison['cost'],2,'.',''):'0';
				$summary_stats_data['previous_conversions'] = isset($comparison['conversions'])?$comparison['conversions']:'0';
				$summary_stats_data['previous_cost_per_conversion'] = isset($comparison['cost_per_conversion'])?$currency_code.number_format($comparison['cost_per_conversion'],2,'.',''):'0';
				$summary_stats_data['previous_ctr'] = isset($comparison['ctr'])?number_format($comparison['ctr'],2,'.','').'%':'0';
				$summary_stats_data['previous_conversion_rate'] = isset($comparison['conversion_rate'])?number_format($comparison['conversion_rate'],2,'.','').'%':'0';
				$summary_stats_data['previous_average_cpc'] = isset($comparison['average_cpc'])?$currency_code.number_format($comparison['average_cpc']/100,2,'.',''):'0';
				
				$summary_stats_data['compare'] = isset($request->compare)?:false;
				$summary_stats_data['date'] = date('m/d/Y',strtotime($start_date)).' - '.date('m/d/Y',strtotime($end_date));
				$summary_stats_data['compare_date'] = isset($compare_start_date) && !empty($compare_start_date)?date('m/d/Y',strtotime($compare_start_date)).' - '.date('m/d/Y',strtotime($compare_end_date)):'';

				
				
				$summary_stats_data['date'] = date('m/d/Y',strtotime($start_date)).' - '.date('m/d/Y',strtotime($end_date));
				
			return response()->json($summary_stats_data);
	}	


	public function ajaxAdsCampaign(Request $request){
		 	 $account_id = $request->account_id;
			 $today = $request->today;
			 $currency_code = $request->currency_code;
			 $start_date = date('Ymd',strtotime('-30 days'));
	 		 $end_date = date('Ymd',strtotime('-1 day'));
			 
			 /*fetching data conditionally*/
			 $data = AdwordsCampaignDetail::
			 where('client_id',$account_id)
			 // ->where('report_date',$today)
			 ->whereBetween('report_date',[$start_date,$end_date])
			 ->where('campaign_name','like', '%' . $request->search["value"] . '%')
			 ->select(
			 'accountCurrencyCode',
					DB::raw('sum(impressions) as impressions'),
					DB::raw('sum(clicks) as clicks'),
					DB::raw('sum(cost) as cost'),
					DB::raw('sum(conversions) as conversions'),
					DB::raw('sum(cost)/sum(conversions) as cost_per_conversion'),
					DB::raw('sum(clicks)/sum(impressions)*100 as ctr'),
					DB::raw('sum(conversions)/sum(clicks)*100 as conversion_rate'),
					DB::raw('sum(cost)/sum(clicks)*100 as average_cpc'),
					DB::raw('campaign_name')
				)
				->groupBy('campaign_id')
				->orderBy('impressions','desc')
				->get();
				
				
				
				$data_array = array();
				
				foreach($data as $val){
					
					$data_array[] = array(
					'campaign_name'=>$val['campaign_name'],
					'impressions'=>$val['impressions'],
					'clicks'=>$val['clicks'],
					'cost'=>$currency_code.number_format($val['cost'],2,'.',''),
					'conversions'=>$val['conversions'],
					'cost_per_conversion'=>$val['cost_per_conversion'],
					'ctr' =>number_format($val['ctr'],2,'.','')
					);
				}
				
				/*counting records*/
				$dataCount = AdwordsCampaignDetail::
					 where('client_id',$account_id)
					 ->where('report_date',$today)
					 ->where('campaign_name','like', '%' . $request->search["value"] . '%')
					 ->select('campaign_name')
					 ->groupBy('campaign_id')
					 ->count();

					
					
				$output = array(
					"draw"              =>  intval($request->draw),
					"recordsTotal"      =>  count($data),
					"recordsFiltered"   =>  count($data),
					"data"              =>  $data_array
				);
				
				
				return response()->json($output);
	}


	public function ajaxAdsKeywords(Request $request){		
			 $account_id = $request->account_id;
			 $today = $request->today;
			 $currency_code = $request->currency_code;

			 $start_date = date('Ymd',strtotime('-30 days'));
	 		 $end_date = date('Ymd',strtotime('-1 day'));
			 
			 /*fetching data conditionally*/
			 $data = AdwordsKeywordDetail::where('client_id',$account_id)
			 // ->where('report_date',$today)
			 ->whereBetween('report_date',[$start_date,$end_date])
			 ->where('keyword_text','like', '%' . $request->search["value"] . '%')
			 ->select( 
					'keyword_text as keywords',
					DB::raw('sum(impressions) as impressions'),
					DB::raw('sum(clicks) as clicks'),
					DB::raw('sum(cost) as cost'),
					DB::raw('sum(conversions) as conversions'),
					DB::raw('sum(cost)/sum(conversions) as cost_per_conversion'),
					DB::raw('sum(clicks)/sum(impressions)*100 as ctr'),
					DB::raw('sum(conversions)/sum(clicks)*100 as conversion_rate'),
					DB::raw('sum(cost)/sum(clicks)*100 as average_cpc')
				)
				 ->groupBy('adkeyword_id')
				->skip($request->start)->take($request->length)
				->orderBy('impressions','desc')
				->get();
				
			
				$data_array = array();
				
				foreach($data as $val){
					$data_array[] = array(
					'keywords'=>$val['keywords'],
					'impressions'=>$val['impressions'],
					'clicks'=>$val['clicks'],
					'cost'=>$currency_code.number_format($val['cost'],2,'.',''),
					'conversions'=>$val['conversions'],
					'cost_per_conversion'=>$val['cost_per_conversion'],
					'ctr' =>number_format($val['ctr'],2,'.','')
					);
				}
				
				/*counting table records*/
				$dataCount = AdwordsKeywordDetail::
					where('client_id',$account_id)
					->where('report_date',$today)
					->where('keyword_text','like', '%' . $request->search["value"] . '%')
					->select('keyword_text')	
					->groupBy('adkeyword_id')
					->count();


				
				 $output = array(
					"draw"              =>  intval($request->draw),
					"recordsTotal"      =>  count($data),
					"recordsFiltered"   =>  count($data),
					"data"              =>  $data_array
				);
				
				
				return response()->json($output);
	}

	public function ajaxAdsData(Request $request){	
			 $account_id = $request->account_id;
			 $today = $request->today;
			 $currency_code = $request->currency_code;
			 $start_date = date('Ymd',strtotime('-30 days'));
	 		 $end_date = date('Ymd',strtotime('-1 day'));
			 
			 /*fetching data conditionally*/
			 $data = AdwordsAdTextDetail::where('client_id',$account_id)
			 // ->where('report_date',$today)
			 ->whereBetween('report_date',[$start_date,$end_date])
			 ->select( 
					'*',
					DB::raw('sum(impressions) as impressions'),
					DB::raw('sum(clicks) as clicks'),
					DB::raw('sum(cost) as cost'),
					DB::raw('sum(conversions) as conversions'),
					DB::raw('sum(cost)/sum(conversions)as cost_per_conversion'),
					DB::raw('sum(clicks)/sum(impressions)*100 as ctr'),
					DB::raw('sum(conversions)/sum(clicks)*100 as conversion_rate'),
					DB::raw('sum(cost)/sum(clicks)*100 as average_cpc')
				)
				 ->groupBy('ad_text_id')
				->skip($request->start)->take($request->length)
				->orderBy('impressions','desc')
				->get();
				
				// echo '<pre>';
				// print_r($data);
				
				
				
			$dataArray = array();
			foreach($data as $val){
				
				$headline = $headline1 = $headline2 = $headline3 = $description = $description1 = '';
				
				
				if($val['ad_type']=='Expanded dynamic search ad'){
				  $headline = "[Dynamically generated headline]";
				  $headline1 = "[Dynamically generated display URL]";
				}
				
				
				
				if($val['ad_type'] == 'Responsive search ad'){
                    $headline =    implode(" | ",array_map(function($a){
                            return $a['assetText'];
                          },json_decode($val['responsive_search_ad_headlines'],true)));
						  
					$headline1 = json_decode($val['creative_final_urls'],true)[0].$val['responsive_search_ad_path1'].'/'.$val['responsive_search_ad_path2'].'/';
					
					$description = implode(",",array_map(function($a){
						return $a['assetText'];
					  },json_decode($val['responsive_search_ad_descriptions'],true))).'</span>';
				}
				
				if($val['ad_type'] == 'Responsive display ad'){
					if($val['multi_asset_responsive_display_adHeadlines'] != '--'){
					$multidesc = json_decode($val['multi_asset_responsive_display_adHeadlines'],true);
                    $headline =    implode(",",array_map(function($a){
                            return $a['assetText'];
                          },$multidesc));
					} 
					$headline1 = json_decode($val['creative_final_urls'],true)[0].$val['responsive_search_ad_path1'].'/'.$val['responsive_search_ad_path2'].'/';
					
					 if($val['multi_asset_responsive_displayAdLongHeadline']!=' --'){
					  $multiDesc1 = json_decode($val['multi_asset_responsive_displayAdLongHeadline'],true);
					  
					  if(count($multiDesc1)==1){
					   $description =  implode(",",array_map(function($a){
						  return $a['assetText'];
						},$multiDesc1));
					  }else{
					  $description =  implode(",",array_map(function($a){
						  return $a;
						},$multiDesc1));
					  }

					}
					
					 if($val['multi_asset_responsive_displayAdDescriptions']!=' --'){
					  $multiDescDisplayDes = json_decode($val['multi_asset_responsive_displayAdDescriptions'],true);

					  if(is_array($multiDescDisplayDes) && count($multiDescDisplayDes)==1){
						$description1 = implode(",",array_map(function($a){
						  return $a;
						},$multiDescDisplayDes));
					  }
					  if(is_array($multiDescDisplayDes) && count($multiDescDisplayDes)>1){
						$description1 =  implode(",",array_map(function($a){
						  return $a['assetText'];
						},$multiDescDisplayDes));
					  }
					 
					}
				}
				
				
				
				if($val['ad_type']=='Expanded text ad'){
					$path1 = $path2 = '';
					$headline = $val['display_url'];
					if($val['path1']!=' --'){
						$path1 = $val['path1'].'/';
					}
					
					if($val['path2']!=' --'){
						$path2 = $val['path2'].'/';
					}
					
					if($val['headline_part1']!=' --' && $val['headline_part2']!=' --'){
					$headline = $val['headline_part1'].' | '.$val['headline_part2'];
					}
					
					if($val['expanded_text_ad_headline_part3']){
						$headline .= '<br>'.$val['expanded_text_ad_headline_part3'];
					}
					$headline1 = json_decode($val['creative_final_urls'],true)[0].$path1.$path2;
					
					if($val['description']!=' --' && $val['description']!=null){
					  $description = $val['description'];
					}
					if($val['expanded_text_ad_description2']!=' --' && $val['expanded_text_ad_description2']!=null){
					  $description1 = $val['expanded_text_ad_description2'];
					}
					
				}
				
					if($val['headline']!=' --' && $val['headline']!=null){
						$headline = $val['headline'];
					}
				
			
				
				
				$dataArray[] = array(
					'ad'=> '<span style="color:blue;">'.$headline. '</span><br><span style="color:green;">'.$headline1. '</span><br>'. $headline2. '<br>'.$description. '<br>'.$description1. '<br>',
					'ad_type'=>$val['ad_type'],
					'impressions'=>$val['impressions'],
					'clicks'=>$val['clicks'],
					'cost'=>$currency_code.$val['cost'],
					'conversions'=>$val['conversions'],
					'ctr'=>number_format($val['ctr'],2,'.','')
				);
			}
			
		
				/*counting table records*/
				$dataCount = AdwordsAdTextDetail::
					where('client_id',$account_id)
					 ->where('report_date',$today)
					->select('*')	
					->groupBy('ad_text_id')
					->count();
				
				 $output = array(
					"draw"              =>  intval($request->draw),
					"recordsTotal"      =>  count($data),
					"recordsFiltered"   =>  count($data),
					"data"              =>  $dataArray
				);
				
				
				return response()->json($output);
	}
	
	
	public function ajaxAdGroupsData(Request $request){
			$account_id = $request->account_id;
			 $today = $request->today;
			 $currency_code = $request->currency_code;
			 
			 $start_date = date('Ymd',strtotime('-30 days'));
	 		 $end_date = date('Ymd',strtotime('-1 day'));

			 /*fetching data conditionally*/
			 $data = AdwordsAdGroupDetail::where('client_id',$account_id)
			 // ->where('report_date',$today)
			 ->whereBetween('report_date',[$start_date,$end_date])
			 ->where('ad_group_name','like', '%' . $request->search["value"] . '%')
			 ->select( 
					'ad_group_name as ad_group',
					'account_currency_code',
					DB::raw('sum(impressions) as impressions'),
					DB::raw('sum(clicks) as clicks'),
					DB::raw('sum(cost) as cost'),
					DB::raw('sum(conversions) as conversions'),
					DB::raw('sum(cost)/sum(conversions)as cost_per_conversion'),
					DB::raw('sum(clicks)/sum(impressions)*100 as ctr'),
					DB::raw('sum(conversions)/sum(clicks)*100 as conversion_rate'),
					DB::raw('sum(cost)/sum(clicks)*100 as average_cpc')
				)
				 ->groupBy('ad_group_id')
				->skip($request->start)->take($request->length)
				->orderBy('impressions','desc')
				->get();
				
				
				if(!empty($data)){
				$data_array = array();
				
				foreach($data as $val){
					$data_array[] = array(
					'ad_group'=>$val['ad_group'],
					'impressions'=>$val['impressions'],
					'clicks'=>$val['clicks'],
					'cost'=>$currency_code.number_format($val['cost'],2,'.',''),
					'conversions'=>$val['conversions'],
					'ctr' =>number_format($val['ctr'],2,'.','')
					);
				}
				}
			
				/*counting table records*/
				$dataCount = AdwordsAdGroupDetail::
					where('client_id',$account_id)
					->where('report_date',$today)
					->where('ad_group_name','like', '%' . $request->search["value"] . '%')
					->select('*')	
					->groupBy('ad_group_id')
					->count();
				
				
				 $output = array(
					"draw"              =>  intval($request->draw),
					"recordsTotal"      =>  count($data),
					"recordsFiltered"   =>  count($data),
					"data"              =>  $data_array
				);
				
				
				return response()->json($output);
	}
	
	
	public function ajaxAdPerformanceNetwork(Request $request){
			$account_id = $request->account_id;
			 $today = $request->today;
			 $currency_code = $request->currency_code;
			 $start_date = date('Ymd',strtotime('-30 days'));
	 		 $end_date = date('Ymd',strtotime('-1 day'));
			 
			 /*fetching data conditionally*/
			 $data = AdwordsCampaignDetail::where('client_id',$account_id)
			 // ->where('report_date',$today)
			 ->whereBetween('report_date',[$start_date,$end_date])
			 ->where('adNetworkType2','like', '%' . $request->search["value"] . '%')
			 ->select( 
			 // '*',
					'accountCurrencyCode',
					'adNetworkType2 as publisher_by_network',
					DB::raw('sum(impressions) as impressions'),
					DB::raw('sum(clicks) as clicks'),
					DB::raw('sum(cost) as cost'),
					DB::raw('sum(conversions) as conversions'),
					DB::raw('sum(cost)/sum(conversions)as cost_per_conversion'),
					DB::raw('sum(clicks)/sum(impressions)*100 as ctr')
				)
				 // ->groupBy('adNetworkType1')
				 ->groupBy('adNetworkType2')
				->skip($request->start)->take($request->length)
				->orderBy('impressions','desc')
				->get();
				
				
				
				$data_array = array();
				
				foreach($data as $val){
					$data_array[] = array(
					'publisher_by_network'=>$val['publisher_by_network'],
					'impressions'=>$val['impressions'],
					'clicks'=>$val['clicks'],
					'cost'=>$currency_code.number_format($val['cost'],2,'.',''),
					'conversions'=>$val['conversions'],
					'cost_per_conversion'=>$val['cost_per_conversion'],
					'ctr' =>number_format($val['ctr'],2,'.','')
					);
				}
				
				
				/*counting table records*/
				$dataCount = AdwordsCampaignDetail::
					where('client_id',$account_id)
					->where('report_date',$today)
					->where('adNetworkType2','like', '%' . $request->search["value"] . '%')
					->select('*')	
					->groupBy('adNetworkType1')
					->count();
				
				
				 $output = array(
					"draw"              =>  intval($request->draw),
					"recordsTotal"      =>  count($data),
					"recordsFiltered"   =>  count($data),
					"data"              =>  $data_array
				);
				
				
				return response()->json($output);
	}
	
	
	
	public function ajaxAdPerformanceDevice(Request $request){
			 $account_id = $request->account_id;
			 $today = $request->today;
			 $currency_code = $request->currency_code;
			 $start_date = date('Ymd',strtotime('-30 days'));
	 		 $end_date = date('Ymd',strtotime('-1 day'));
			 
			 /*fetching data conditionally*/
			 $data = AdwordsCampaignDetail::where('client_id',$account_id)
			 // ->where('report_date',$today)
			 ->whereBetween('report_date',[$start_date,$end_date])
			 ->where('device','like', '%' . $request->search["value"] . '%')
			 ->select( 
					'accountCurrencyCode',
					'device',
					DB::raw('sum(impressions) as impressions'),
					DB::raw('sum(clicks) as clicks'),
					DB::raw('sum(cost) as cost'),
					DB::raw('sum(conversions) as conversions'),
					DB::raw('sum(cost)/sum(conversions)as cost_per_conversion'),
					DB::raw('sum(clicks)/sum(impressions)*100 as ctr')
				)
				 ->groupBy('device')
				->skip($request->start)->take($request->length)
				->orderBy('impressions','desc')
				->get();
				
				
				
				$data_array = array();
				
				foreach($data as $val){
					$data_array[] = array(
					'device'=>$val['device'],
					'impressions'=>$val['impressions'],
					'clicks'=>$val['clicks'],
					'cost'=>$currency_code.number_format($val['cost'],2,'.',''),
					'conversions'=>$val['conversions'],
					'cost_per_conversion'=>$val['cost_per_conversion'],
					'ctr' =>number_format($val['ctr'],2,'.','')
					);
				}
				
				
				/*counting table records*/
				$dataCount = AdwordsCampaignDetail::
					where('client_id',$account_id)
					->where('report_date',$today)
					->where('device','like', '%' . $request->search["value"] . '%')
					->select('*')	
					->groupBy('device')
					->count();
				
				
				 $output = array(
					"draw"              =>  intval($request->draw),
					"recordsTotal"      =>  count($data),
					"recordsFiltered"   => 	count($data),
					"data"              =>  $data_array
				);
				
				
				return response()->json($output);
	}
	
	
	
	public function ajaxAdPerformanceClickTypes(Request $request){
			 $account_id = $request->account_id;
			 $today = $request->today;
			 $currency_code = $request->currency_code;
			 $start_date = date('Ymd',strtotime('-30 days'));
	 		 $end_date = date('Ymd',strtotime('-1 day'));
			 
			 /*fetching data conditionally*/
			 $data = AdwordsPlaceHolderDetail::where('client_id',$account_id)
			 // ->where('report_date',$today)
			 ->whereBetween('report_date',[$start_date,$end_date])
			 ->where('click_type','like', '%' . $request->search["value"] . '%')
			 ->select( 
					'click_type',
					DB::raw('sum(impressions) as impressions'),
					DB::raw('sum(clicks) as clicks'),
					DB::raw('sum(cost) as cost'),
					DB::raw('sum(conversions) as conversions'),
					DB::raw('sum(clicks)/sum(impressions)*100 as ctr')
					
				)
				 ->groupBy('click_type')
				->skip($request->start)->take($request->length)
				->orderBy('impressions','desc')
				->get();
				
				
				
				$data_array = array();
				
				foreach($data as $val){
					$data_array[] = array(
					'click_type'=>$val['click_type'],
					'impressions'=>$val['impressions'],
					'clicks'=>$val['clicks'],
					'cost'=>$currency_code.number_format($val['cost'],2,'.',''),
					'conversions'=>$val['conversions'],
					'ctr' =>number_format($val['ctr'],2,'.','')
					);
				}
				
				
				/*counting table records*/
				$dataCount = AdwordsPlaceHolderDetail::
					where('client_id',$account_id)
					->where('report_date',$today)
					->where('click_type','like', '%' . $request->search["value"] . '%')
					->select('*')	
					->groupBy('click_type')
					->count();
				
				
				 $output = array(
					"draw"              =>  intval($request->draw),
					"recordsTotal"      =>  count($data),
					"recordsFiltered"   =>  count($data),
					"data"              =>  $data_array
				);
				
				
				return response()->json($output);
	}
	
	public function ajaxAdPerformanceSlots(Request $request){
			 $account_id = $request->account_id;
			 $today = $request->today;
			 $currency_code = $request->currency_code;
			 $start_date = date('Ymd',strtotime('-30 days'));
	 		 $end_date = date('Ymd',strtotime('-1 day'));
			 
			 /*fetching data conditionally*/
			 $data = AdwordsCampaignDetail::where('client_id',$account_id)
			 // ->where('report_date',$today)
			 ->whereBetween('report_date',[$start_date,$end_date])
			 ->where('slot','like', '%' . $request->search["value"] . '%')
			 ->select( 
			 'accountCurrencyCode',
					'slot',
					DB::raw('sum(impressions) as impressions'),
					DB::raw('sum(clicks) as clicks'),
					DB::raw('sum(cost) as cost'),
					DB::raw('sum(conversions) as conversions'),
					DB::raw('sum(clicks)/sum(impressions)*100 as ctr')
					
				)
				->groupBy('slot')
				->skip($request->start)->take($request->length)
				->orderBy('impressions','desc')
				->get();
				
			
				
				
				$data_array = array();
				
				foreach($data as $val){
					$data_array[] = array(
					'ad_slot'=>$val['slot'],
					'impressions'=>$val['impressions'],
					'clicks'=>$val['clicks'],
					'cost'=>$currency_code.number_format($val['cost'],2,'.',''),
					'conversions'=>$val['conversions'],
					'ctr' =>number_format($val['ctr'],2,'.','')
					);
				}
				
				
				/*counting table records*/
				$dataCount = AdwordsCampaignDetail::
					where('client_id',$account_id)
					->where('report_date',$today)
					->where('slot','like', '%' . $request->search["value"] . '%')
					->select('*')	
					->groupBy('slot')
					->count();

				
				 $output = array(
					"draw"              =>  intval($request->draw),
					"recordsTotal"      =>  count($data),
					"recordsFiltered"   =>  count($data),
					"data"              =>  $data_array
				);
				
				
				return response()->json($output);
	}

	public function summary_stats(Request $request){
		$account_id = $request->account_id;
		 if(isset($request->start_date) && isset($request->end_date)){
			 $start_date = date('Ymd',strtotime($request->start_date));
			 $end_date =date('Ymd',strtotime($request->end_date));
			 $diff = abs(strtotime($end_date) - strtotime($start_date));
			 $days = floor(($diff)/ (60*60*24));
			 
		 } else{
			   $start_date = date('Ymd',strtotime('-30 days'));
			   $end_date = date('Ymd',strtotime('-1 day'));
		 }

		if(isset($request->cmp_start_date) && isset($request->cmp_end_date)){
			$cmp_diff = abs(strtotime(date('Ymd',strtotime($request->cmp_start_date))) - strtotime(date('Ymd',strtotime($request->cmp_end_date))));
			$cmp_days = floor(($cmp_diff)/ (60*60*24));

			$compare_start_date = date('Ymd', strtotime($request->cmp_start_date));
			$compare_end_date = date('Ymd', strtotime($request->cmp_end_date));

			if($cmp_days != $days){
				$summary_stats_data['status'] = false;
				$summary_stats_data['message'] = 'Please select '.$days.' days for comparison';
				return response()->json($summary_stats_data);
			}
		}

		

			

		$summary_stats = AdwordsCampaignDetail::where('client_id',$account_id)
			->whereBetween('day',[$start_date,$end_date])
			->select(
				'*',
				DB::raw('sum(impressions) as impressions'),
				DB::raw('sum(clicks) as clicks'),
				DB::raw('sum(cost) as cost'),
				DB::raw('sum(conversions) as conversions'),
				DB::raw('sum(cost)/sum(conversions) as cost_per_conversion'),
				DB::raw('sum(clicks)/sum(impressions)*100 as ctr'),
				DB::raw('sum(conversions)/sum(clicks)*100 as conversion_rate'),
				DB::raw('sum(cost)/sum(clicks)*100 as average_cpc'),
				DB::raw('accountCurrencyCode as currency_code')
				
			)->first();
			

			if($request->has('compare')){
				$comparison = AdwordsCampaignDetail::where('client_id',$account_id)
				->whereBetween('day',[$compare_start_date,$compare_end_date])
				->select(
					DB::raw('sum(impressions) as impressions'),
					DB::raw('sum(clicks) as clicks'),
					DB::raw('sum(cost) as cost'),
					DB::raw('sum(conversions) as conversions'),
					DB::raw('sum(cost)/sum(conversions) as cost_per_conversion'),
					DB::raw('sum(clicks)/sum(impressions)*100 as ctr'),
					DB::raw('sum(conversions)/sum(clicks)*100 as conversion_rate'),
					DB::raw('sum(cost)/sum(clicks)*100 as average_cpc'),
					
					DB::raw('accountCurrencyCode as currency_code')
					
				)->first();
				
				
			}
			
			if($summary_stats['accountCurrencyCode'] == 'CAD'){
				$currency_code = 'CA$';
			} elseif($summary_stats['accountCurrencyCode'] == 'US'){
				$currency_code = '$';
			} else{
				$currency_code ='$';
			}
			
		
				$summary_stats_data['impressions'] = isset($summary_stats['impressions'])?number_format($summary_stats['impressions'],0,' ',','):'0';
				$summary_stats_data['clicks'] = isset($summary_stats['clicks'])?number_format($summary_stats['clicks'],0,' ',','):'0';
				$summary_stats_data['cost'] = isset($summary_stats['cost'])?$currency_code.number_format($summary_stats['cost'],2,'.',''):'0';
				$summary_stats_data['conversions'] = isset($summary_stats['conversions'])?$summary_stats['conversions']:'0';
				$summary_stats_data['cost_per_conversion'] = isset($summary_stats['cost_per_conversion'])?$currency_code.number_format($summary_stats['cost_per_conversion'],2,'.',''):'0';
				$summary_stats_data['ctr'] = isset($summary_stats['ctr'])?number_format($summary_stats['ctr'],2,'.','').'%':'0%';
				$summary_stats_data['conversion_rate'] = isset($summary_stats['conversion_rate'])?number_format($summary_stats['conversion_rate'],2,'.','').'%':'0%';
				$summary_stats_data['average_cpc'] = isset($summary_stats['average_cpc'])?$currency_code.number_format($summary_stats['average_cpc']/100,2,'.',''):'0';
				
				/*comparison data*/
				$summary_stats_data['previous_impressions'] = isset($comparison['impressions'])?number_format($comparison['impressions'],0,' ',','):'0';
				$summary_stats_data['previous_clicks'] = isset($comparison['clicks'])?number_format($comparison['clicks'],0,' ',','):'0';
				$summary_stats_data['previous_cost'] = isset($comparison['cost'])?$currency_code.number_format($comparison['cost'],2,'.',''):'0';
				$summary_stats_data['previous_conversions'] = isset($comparison['conversions'])?$comparison['conversions']:'0';
				$summary_stats_data['previous_cost_per_conversion'] = isset($comparison['cost_per_conversion'])?$currency_code.number_format($comparison['cost_per_conversion'],2,'.',''):'0';
				$summary_stats_data['previous_ctr'] = isset($comparison['ctr'])?number_format($comparison['ctr'],2,'.','').'%':'0';
				$summary_stats_data['previous_conversion_rate'] = isset($comparison['conversion_rate'])?number_format($comparison['conversion_rate'],2,'.','').'%':'0';
				$summary_stats_data['previous_average_cpc'] = isset($comparison['average_cpc'])?$currency_code.number_format($comparison['average_cpc']/100,2,'.',''):'0';
				
				$summary_stats_data['compare'] = isset($request->compare)?:false;
				$summary_stats_data['date'] = date('m/d/Y',strtotime($start_date)).' - '.date('m/d/Y',strtotime($end_date));
				$summary_stats_data['compare_date'] = isset($compare_start_date) && !empty($compare_start_date)?date('m/d/Y',strtotime($compare_start_date)).' - '.date('m/d/Y',strtotime($compare_end_date)):'';
				
			return response()->json($summary_stats_data);
	}	

}