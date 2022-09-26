<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SemrushUserAccount;
use App\User;
use App\GoogleAnalyticsUsers;
use App\ActivityLog;
use App\ModuleByDateRange;
use App\ProjectCompareGraph;
use App\Error;
use Auth;
use Exception;
use \Illuminate\Pagination\LengthAwarePaginator;

class EcommerceGoalsController extends Controller {

	public function ajax_ecom_goal_completion_chart(Request $request){
		$campaign_id = $request->campaign_id;
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$res['status'] = 0;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/graph.json'; 
			if (!file_exists($url)) {
				$res['status'] = 0;
			}else{
				$data = file_get_contents($url);
				$final = json_decode($data);

				$dates = $this->make_dates($campaign_id);

				$compare_status = $dates['compare_status'];
				$type = $dates['type'];
				$startDate = $dates['start_date'];
				$endDate = $dates['end_date'];
				$prev_start_dates = $dates['prev_date_1'];
				$prev_end_dates = $dates['prev_end_dates'];
				$default_duration = $dates['default_duration'];
				$duration = $dates['duration'];

				if($type == 'day'){
					$duration = ModuleByDateRange::calculate_days($startDate,$endDate);
					for($i=1;$i<=$duration;$i++){
						if($i==1){  
							$start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
							$prev_start_date = date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_start_dates))); 
						}else{
							$start_date = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
							$prev_start_date = date('Y-m-d',strtotime('+1 day',strtotime($prev_end_date))); 
						}

						$end_date = date('Y-m-d',strtotime('+0 day',strtotime($start_date)));    
						$prev_end_date = date('Y-m-d',strtotime('+0 day',strtotime($prev_start_date))); 

						$result[] = $this->ecom_goal_completion_chart($start_date,$end_date,$prev_start_date,$prev_end_date,$campaign_id,$type,0,0);
						if($default_duration == 1 || $default_duration == 3){
							$current[] = date('M d, Y',strtotime($start_date));
						}else{
							$current[] = date('M Y',strtotime($start_date));
						}
						$current_dates[] = date('l, F d, Y',strtotime($start_date));
						$current_prev_dates[] = date('l, F d, Y',strtotime($prev_start_date));
					}

				}


				if($type == 'week'){
					$i =1;
					$csd = $prev_end_dates;
					$sd = $startDate;
					/*infinite for loop*/
					for( ; ;){
						$start = $sd;
						$previous_start = $csd;

						if($i == 1){
							if(date('D', strtotime($start)) == 'Sun'){
								$end_date = date('Y-m-d',strtotime('saturday next week',strtotime($start)));
							}else{  
								$end_date = date('Y-m-d',strtotime('saturday this week',strtotime($start)));
							}

							if(date('D', strtotime($previous_start)) == 'Sun'){
								$previous_end = date('Y-m-d',strtotime('saturday next week',strtotime($previous_start)));
							}else{  
								$previous_end = date('Y-m-d',strtotime('saturday this week',strtotime($previous_start)));
							}
						}else{
							$end_date = date('Y-m-d',strtotime('saturday next week',strtotime($start)));
							$previous_end = date('Y-m-d',strtotime('saturday next week',strtotime($previous_start)));
						}


						$sd = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
						$csd = date('Y-m-d',strtotime('+1 day',strtotime($previous_end)));


						if($previous_end > $startDate){
							$previousEnd = date('Y-m-d',strtotime('-1 day',strtotime($startDate))); 
						}else{
							$previousEnd = $previous_end;
						}
						$previous_days = ModuleByDateRange::calculate_ecom_days($previous_start,$previousEnd);

						/*to break the infinite loop at the last entry*/
						if($end_date  > date('Y-m-d')){
							$enddate = date('Y-m-d');
							$current_days = ModuleByDateRange::calculate_ecom_days($start,$enddate);

							$result[] = $this->ecom_goal_completion_chart($start,$enddate,$previous_start,$previousEnd,$campaign_id,$type,$current_days,$previous_days);
							if($default_duration == 1){
								$current[] = date('M d, Y',strtotime($start));
							}else{
								$current[] = date('M Y',strtotime($start));
							}
							$current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($enddate));
							$current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
							break; 
						}else{
							$current_days = ModuleByDateRange::calculate_ecom_days($start,$end_date);

							$result[] = $this->ecom_goal_completion_chart($start,$end_date,$previous_start,$previousEnd,$campaign_id,$type,$current_days,$previous_days);
							if($default_duration == 1 || $default_duration == 3){
								$current[] = date('M d, Y',strtotime($start));
							}else{
								$current[] = date('M Y',strtotime($start));
							}
							$current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($end_date));
							$current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
						}

						$i++;
					}

				}

				if($type == 'month'){
					$i = 1;
					$csd = $prev_end_dates;
					$sd = $startDate;
					for( ; ;){
						$start = $sd;
						$previous_start = $csd;

						$end_date = date('Y-m-d',strtotime(date("Y-m-t", strtotime($start))));
						$previous_end = date('Y-m-d',strtotime(date("Y-m-t", strtotime($previous_start))));


						$sd = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
						$csd = date('Y-m-d',strtotime('+1 day',strtotime($previous_end)));

						if($previous_end > $startDate){
							$previousEnd = date('Y-m-d',strtotime('-1 day',strtotime($startDate))); 
						}else{
							$previousEnd = $previous_end;
						}

						$previous_days = ModuleByDateRange::calculate_ecom_days($previous_start,$previousEnd);

						if($end_date  > date('Y-m-d')){
							$enddate = date('Y-m-d');
							$current_days = ModuleByDateRange::calculate_ecom_days($start,$enddate);

							$result[] = $this->ecom_goal_completion_chart($start,$enddate,$previous_start,$previousEnd,$campaign_id,$type,$current_days,$previous_days);
							if($default_duration == 1 || $default_duration == 3){
								$current[] = date('M d, Y',strtotime($start));
							}else{
								$current[] = date('M Y',strtotime($start));
							}
							$current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($enddate));
							$current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
							break;
						}else{
							$current_days = ModuleByDateRange::calculate_ecom_days($start,$end_date);
							$result[] = $this->ecom_goal_completion_chart($start,$end_date,$previous_start,$previousEnd,$campaign_id,$type,$current_days,$previous_days);
							if($default_duration == 1 || $default_duration == 3){
								$current[] = date('M d, Y',strtotime($start));
							}else{
								$current[] = date('M Y',strtotime($start));
							}
							$current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($end_date));
							$current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
						}
						$i++;
					}
				}  


				$current_users = array_column($result, 'current_users');
				$prev_users = array_column($result, 'prev_users');
				$current_organic = array_column($result, 'current_organic');
				$prev_organic = array_column($result, 'prev_organic');

				$current_period  = date('d-m-Y', strtotime($startDate)).' to '.date('d-m-Y', strtotime($endDate));
				$prev_period   = date('d-m-Y', strtotime($prev_end_dates)).' to '.date('d-m-Y', strtotime($prev_start_dates));


				$res['from_datelabel'] = $current;
				$res['from_datelabels'] = $current_dates;
				$res['prev_from_datelabels'] = $current_prev_dates;
				$res['users'] = $current_users;
				$res['previous_users'] = $prev_users;
				$res['organic'] = $current_organic;
				$res['previous_organic'] = $prev_organic;
				$res['current_period'] = $current_period;
				$res['previous_period'] = $prev_period;
				$res['compare_status'] = $compare_status;
				$res['status'] = 1;
			}
		}
		
		return response()->json($res);
	}

	private function ecom_goal_completion_chart($start_date,$end_date,$prev_start_date,$prev_end_date,$campaign_id,$type,$current_days,$previous_days){
		$current_users = $current_organic = $prev_users = $prev_organic = 0;
		$current_prev = $current_prev_dates = '';
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$current_users = $current_organic = $prev_users = $prev_organic = 0;
		}else{
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/graph.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);

			$get_index = array_search($start_date,$final->dates_format);
			$get_index_today = array_search($end_date,$final->dates_format);

			if($get_index <> false && $get_index_today == false){
				$get_index_today = array_search(end($final->dates_format),$final->dates_format);
			}

			$get_indexprev = array_search($prev_start_date,$final->dates_format);
			$get_index_prev = array_search($prev_end_date,$final->dates_format);

			if($type == 'week'){
				if($get_index == false && $get_index_today == false){
					$current = date('M d',strtotime($start_date));
					$current_dates = date('l, F d, Y',strtotime($start_date));
					$current_users = $current_organic = 0;
				}else{
					for($i=$get_index;$i<=$get_index_today;$i++){
						$current = date('M d',strtotime($final->dates_format[$i]));
						$current_dates = date('l, F d,Y',strtotime($final->dates_format[$i]));
						$current_users += number_format(($final->final_user_data[$i]/$current_days),2);
						$current_organic += number_format(($final->final_organic_data[$i]/$current_days),2);
					}
				}
				if($get_index_prev == false && $get_indexprev == false){
					$current_prev = date('M d',strtotime($prev_start_date));
					$current_prev_dates = date('l, F d, Y',strtotime($prev_start_date));
					$prev_users = $prev_organic = 0;
				}else{
					for($j=$get_index_prev;$j>=$get_indexprev;$j--){
						$current_prev = $final->dates_format[$j];
						$current_prev_dates = date('l, F d,Y',strtotime($final->dates_format[$j]));
						$prev_users += number_format(($final->final_user_data[$j]/$previous_days),2);
						$prev_organic += number_format(($final->final_organic_data[$j]/$previous_days),2);
					}
				}
			}elseif($type == 'month'){
				if($get_index == false && $get_index_today == false){
					$current = date('M d',strtotime($start_date));
					$current_dates = date('l, F d, Y',strtotime($start_date));
					$current_users = $current_organic = 0;
				}else{
					for($i=$get_index;$i<=$get_index_today;$i++){
						$current = date('M d',strtotime($final->dates_format[$i]));
						$current_dates = date('l, F d,Y',strtotime($final->dates_format[$i]));
						$current_users += number_format(($final->final_user_data[$i]/$current_days),2);
						$current_organic += number_format(($final->final_organic_data[$i]/$current_days),2);
					}
				}
				if($get_index_prev == false && $get_indexprev == false){
					$current_prev = date('M d',strtotime($prev_start_date));
					$current_prev_dates = date('l, F d, Y',strtotime($prev_start_date));
					$prev_users = $prev_organic = 0;
				}else{
					for($j=$get_index_prev;$j<=$get_indexprev;$j++){
						$current_prev = $final->dates_format[$j];
						$current_prev_dates = date('l, F d,Y',strtotime($final->dates_format[$j]));
						$prev_users += number_format(($final->final_user_data[$j]/$previous_days),2);
						$prev_organic += number_format(($final->final_organic_data[$j]/$previous_days),2);
					}
				}
			}else{
				if($get_index == false && $get_index_today == false){
					$current = date('M d',strtotime($start_date));
					$current_dates = date('l, F d, Y',strtotime($start_date));
					$current_users = $current_organic = 0;
				}else{
					for($i=$get_index;$i<=$get_index_today;$i++){
						$current = date('M d',strtotime($final->dates_format[$i]));
						$current_dates = date('l, F d,Y',strtotime($final->dates_format[$i]));
						$current_users = number_format($final->final_user_data[$i],2);
						$current_organic = number_format($final->final_organic_data[$i],2);
					}
				}

				if($get_index_prev == false && $get_indexprev == false){
					$current_prev = date('M d',strtotime($prev_start_date));
					$current_prev_dates = date('l, F d, Y',strtotime($prev_start_date));
					$prev_users = $prev_organic = 0;
				}else{
					for($j=$get_indexprev;$j<=$get_index_prev;$j++){
						$current_prev = $final->dates_format[$j];
						$current_prev_dates = date('l, F d,Y',strtotime($final->dates_format[$j]));
						$prev_users = number_format($final->final_user_data[$j],2);
						$prev_organic = number_format($final->final_organic_data[$j],2);
					}
				}
			}
			
			return array('current'=>$current,'current_dates'=>$current_dates,'current_users'=>(string)$current_users,'current_organic'=>(string)$current_organic,'current_prev'=>$current_prev,'current_prev_dates'=>$current_prev_dates,'prev_users'=>(string)$prev_users,'prev_organic'=>(string)$prev_organic);
		}
	}

	private function make_dates($campaign_id){
		$result =  array();
		$getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
		if(!empty($getCompareChart)){
			$compare_status = $getCompareChart->compare_status;
		}else{
			$compare_status = 0;
		}

		$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');
		if(empty($sessionHistoryRange) && $sessionHistoryRange == null){
			$start_date_1 = date('Y-m-d',strtotime('-3 month'));
			$duration = 3;
			$type = 'day';
			$range = 'three';
		}else{
			if($sessionHistoryRange->duration == 1){
				$start_date_1   = date('Y-m-d', strtotime('-1 month'));
				$duration =1;
				$range = 'month';
			}elseif($sessionHistoryRange->duration == 3){
				$start_date_1   = date('Y-m-d', strtotime('-3 month'));
				$duration = 3;
				$range = 'three';
			}elseif($sessionHistoryRange->duration == 6){
				$start_date_1   = date('Y-m-d', strtotime('-6 month'));
				$duration = 6;
				$range = 'six';
			}elseif($sessionHistoryRange->duration == 9){
				$start_date_1   = date('Y-m-d', strtotime('-9 month'));
				$duration = 9;
				$range = 'nine';
			}elseif($sessionHistoryRange->duration == 12){
				$start_date_1   = date('Y-m-d', strtotime('-1 year'));
				$duration = 12;
				$range = 'year';
			}elseif($sessionHistoryRange->duration == 24){
				$start_date_1   = date('Y-m-d', strtotime('-2 year'));
				$duration = 24;
				$range = 'twoyear';
			}
			$type = ($sessionHistoryRange->display_type)?:'day';
		}

		$updatedValue = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

		if($updatedValue){
			$default_duration = $updatedValue->duration;
		}else{
			$default_duration =  3;
		}

		if($range == 'year'){
			$prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
			$prev_end_dates =  date('Y-m-d',strtotime(' -1 year',strtotime($prev_date_1)));
		}
		elseif($range == 'twoyear'){
			$prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
			$prev_end_dates =  date('Y-m-d',strtotime(' -2 year',strtotime($prev_date_1)));           
		}
		else{
			$prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
			$prev_end_dates =  date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_date_1)));
		}

		$start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
		$end_date = date('Y-m-d');

		$result['start_date'] = $start_date;
		$result['end_date'] = $end_date;
		$result['prev_date_1'] = $prev_date_1;
		$result['prev_end_dates'] = $prev_end_dates;
		$result['default_duration'] = $default_duration;
		$result['compare_status'] = $compare_status;
		$result['start_date_1'] = $start_date_1;
		$result['type'] = $type;
		$result['duration'] = $duration;

		return $result;
	}


	public function ajax_ecom_goal_completion_overview_bkp(Request $request){
		$end_date = date('Y-m-d');
		$campaign_id = $request['campaign_id'];

		$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

		$getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
		if(!empty($getCompareChart)){
			$compare_status = $getCompareChart->compare_status;
		}else{
			$compare_status = 0;
		}


		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$res['status'] = 0;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json'; 
			$data = file_get_contents($url);

			$final = json_decode($data);

			if(empty($sessionHistoryRange) && $sessionHistoryRange == null){
				$start_date_new = date('Y-m-d',strtotime('-3 month'));
				$start_date = date('Y-m-d',strtotime('-3 month'));


				$day_diff = strtotime($start_date_new) - strtotime($end_date);
				$count_days = floor($day_diff/(60*60*24));
				$number_of_days     =   (int)floor((strtotime($end_date) - strtotime($start_date_new))/(60*60*24));


				$prev_date1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_new)));
				$prev_date =  date('d M, Y',strtotime('-1 day',strtotime($start_date_new)));
				$prev_date_new =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
				$prev_end_date =  date('d M, Y',strtotime($count_days.' days',strtotime($prev_date_new)));
				$prev_end_date1 =  date('Y-m-d',strtotime($count_days.' days',strtotime($prev_date_new)));


				$get_index = array_search($start_date,$final->dates);
				$get_index_today = array_search($end_date,$final->dates);

				$get_indexprev = array_search($prev_end_date1,$final->dates);
				$get_index_prev = array_search($prev_date1,$final->dates);
			}else{
				if($sessionHistoryRange->duration == 1){
					$start_date   = date('Y-m-d', strtotime('-1 month'));
				}elseif($sessionHistoryRange->duration == 3){
					$start_date   = date('Y-m-d', strtotime('-3 month'));
				}elseif($sessionHistoryRange->duration == 6){
					$start_date   = date('Y-m-d', strtotime('-6 month'));
				}elseif($sessionHistoryRange->duration == 9){
					$start_date   = date('Y-m-d', strtotime('-9 month'));
				}elseif($sessionHistoryRange->duration == 12){
					$start_date   = date('Y-m-d', strtotime('-1 year'));
				}elseif($sessionHistoryRange->duration == 24){
					$start_date   = date('Y-m-d', strtotime('-2 year'));
				}


				$number_of_days     =   (int)floor((strtotime($end_date) - strtotime($start_date))/(60*60*24));

				$day_diff  =    strtotime($start_date) - strtotime($end_date);
				$count_days     =   floor($day_diff/(60*60*24));


				$prev_date =  date('d M, Y',strtotime('-1 day',strtotime($start_date)));
				$prev_date1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
				$prev_date_new =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
				$prev_end_date =  date('d M, Y',strtotime($count_days.' days',strtotime($prev_date_new)));      
				$prev_end_date1 =  date('Y-m-d',strtotime($count_days.' days',strtotime($prev_date_new)));      



				$get_index = array_search($start_date,$final->dates);
				$get_index_today = array_search($end_date,$final->dates);

				if($get_index_today == false){
					$end_date = end($final->dates); 
					$get_index_today = array_search($end_date,$final->dates);
				}

				$get_indexprev = array_search($prev_end_date1,$final->dates);
				$get_index_prev = array_search($prev_date1,$final->dates);

				if($get_indexprev == false){
					$prev_end_date =  date('d M, Y',strtotime(($count_days+1).' days',strtotime($prev_date_new)));      
					$prev_end_date1 =  date('Y-m-d',strtotime(($count_days+1).' days',strtotime($prev_date_new)));    
					$get_indexprev = array_search($prev_end_date1,$final->dates);
				}
			}

			$current_conversion_rate = $current_conversion_rate_organic = $current_transactions = $current_transactions_organic = $current_revenue = $current_revenue_organic = $current_avg_orderValue = $current_avg_orderValue_organic = array();

			for($i=$get_index;$i<=$get_index_today;$i++){
				//conversion rate 
				$current_conversion_rate[] = number_format($final->conversionRate[$i],2);
				$current_conversion_rate_organic[] = number_format($final->conversionRate_organic[$i],2);
				//transactions
				$current_transactions[] = number_format($final->transactions[$i],2);
				$current_transactions_organic[] = number_format($final->transactions_organic[$i],2, '.', '');
				//revenue
				$current_revenue[] = number_format($final->revenue[$i],2,'.','');
				$current_revenue_organic[] = number_format($final->revenue_organic[$i],2, '.', '');
				//avg order value
				$current_avg_orderValue[] = number_format($final->order_value[$i],2,'.','');
				$current_avg_orderValue_organic[] = number_format($final->order_value_organic[$i],2, '.', '');
			}	




			for($j=$get_indexprev;$j<=$get_index_prev;$j++){
				//conversion rate 
				$previous_conversion_rate[] = number_format($final->conversionRate[$j],2);
				$previous_conversion_rate_organic[] = number_format($final->conversionRate_organic[$j],2);
				//transactions
				$previous_transactions[] = number_format($final->transactions[$j],2, '.', '');
				$previous_transactions_organic[] = number_format($final->transactions_organic[$j],2, '.', '');
				//revenue
				$previous_revenue[] = number_format($final->revenue[$j],2, '.', '');
				$previous_revenue_organic[] = number_format($final->revenue_organic[$j],2, '.', '');
				//avg order value
				$previous_avg_orderValue[] = number_format($final->order_value[$j],2,'.','');
				$previous_avg_orderValue_organic[] = number_format($final->order_value_organic[$j],2, '.', '');
			}


			//conversion rate
			$final_current_conversionRate = number_format((array_sum($current_conversion_rate)/$number_of_days),2);
			$final_previous_conversionRate = number_format((array_sum($previous_conversion_rate)/$number_of_days),2);
			$conversionRate_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_conversionRate,$final_previous_conversionRate);

			$final_current_conversionRate_organic = number_format((array_sum($current_conversion_rate_organic)/$number_of_days),2);
			$final_previous_conversionRate_organic = number_format((array_sum($previous_conversion_rate_organic)/$number_of_days),2);
			$conversionRate_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_conversionRate_organic,$final_previous_conversionRate_organic);


			//transactions
			$final_current_transactions = number_format(array_sum($current_transactions),2, '.', '');
			$final_previous_transactions = number_format(array_sum($previous_transactions),2, '.', '');
			$transactions_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_transactions,$final_previous_transactions);

			$final_current_transactions_organic = number_format(array_sum($current_transactions_organic),2, '.', '');
			$final_previous_transactions_organic = number_format(array_sum($previous_transactions_organic),2, '.', '');
			$transactions_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_transactions_organic,$final_previous_transactions_organic);


			//revenue
			$final_current_revenue = number_format(array_sum($current_revenue),2, '.', '');
			$final_previous_revenue = number_format(array_sum($previous_revenue),2, '.', '');
			$revenue_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_revenue,$final_previous_revenue);

			$final_current_revenue_organic = number_format(array_sum($current_revenue_organic),2, '.', '');
			$final_previous_revenue_organic = number_format(array_sum($previous_revenue_organic),2, '.', '');
			$revenue_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_revenue_organic,$final_previous_revenue_organic);
			
			//avg order value
			$final_current_avg_orderVal = number_format((array_sum($current_avg_orderValue)/$number_of_days),2);
			$final_previous_avg_orderVal = number_format((array_sum($previous_avg_orderValue)/$number_of_days),2);
			$avg_orderVal_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_avg_orderVal,$final_previous_avg_orderVal);

			$final_current_avg_orderVal_organic = number_format((array_sum($current_avg_orderValue_organic)/$number_of_days),2);
			$final_previous_avg_orderVal_organic = number_format((array_sum($previous_avg_orderValue_organic)/$number_of_days),2);
			$avg_orderVal_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_avg_orderVal_organic,$final_previous_avg_orderVal_organic);


			//current values
			$res['current_conversionRate'] = $final_current_conversionRate;
			$res['current_transactions'] = $final_current_transactions;
			$res['current_revenue'] = $final_current_revenue;
			$res['current_avg_orderVal'] = $final_current_avg_orderVal;

			$res['current_conversionRate_organic'] = $final_current_conversionRate_organic;
			$res['current_transactions_organic'] = $final_current_transactions_organic;
			$res['current_revenue_organic'] = $final_current_revenue_organic;
			$res['current_avg_orderVal_organic'] = $final_current_avg_orderVal_organic;

			//previous values
			$res['previous_conversionRate'] = $final_previous_conversionRate;
			$res['previous_transactions'] = $final_previous_transactions;
			$res['previous_revenue'] = $final_previous_revenue;
			$res['previous_avg_orderVal'] = $final_previous_avg_orderVal;

			$res['previous_conversionRate_organic'] = $final_previous_conversionRate_organic;
			$res['previous_transactions_organic'] = $final_previous_transactions_organic;
			$res['previous_revenue_organic'] = $final_previous_revenue_organic;
			$res['previous_avg_orderVal_organic'] = $final_previous_avg_orderVal_organic;

			//percentage values
			$res['conversionRate_percentage'] = $conversionRate_percentage;
			$res['transactions_percentage'] = $transactions_percentage;
			$res['revenue_percentage'] = $revenue_percentage;
			$res['avg_orderVal_percentage'] = $avg_orderVal_percentage;

			$res['conversionRate_percentage_organic'] = $conversionRate_percentage_organic;
			$res['transactions_percentage_organic'] = $transactions_percentage_organic;
			$res['revenue_percentage_organic'] = $revenue_percentage_organic;
			$res['avg_orderVal_percentage_organic'] = $avg_orderVal_percentage_organic;

			$res['compare_status'] = $compare_status;
			$res['status'] = 1;			
		}
		return response()->json($res);
	}



	public function ajax_ecom_goal_completion_overview(Request $request){
		$end_date = date('Y-m-d');
		$campaign_id = $request['campaign_id'];

		$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

		$getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
		if(!empty($getCompareChart)){
			$compare_status = $getCompareChart->compare_status;
		}else{
			$compare_status = 0;
		}


		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$res['status'] = 0;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json'; 
			if (!file_exists($url)) {
				$res['status'] = 0;
			}else{


				$data = file_get_contents($url);

				$final = json_decode($data);
				$dates = $this->make_dates($campaign_id);

				$compare_status = $dates['compare_status'];
				$type = $dates['type'];
				$startDate = $dates['start_date'];
				$endDate = $dates['end_date'];
				$prev_start_dates = $dates['prev_date_1'];
				$prev_end_dates = $dates['prev_end_dates'];
				$default_duration = $dates['default_duration'];
				$duration = $dates['duration'];

				$number_of_days = ModuleByDateRange::calculate_days($startDate,$endDate);

				$get_index = array_search($startDate,$final->dates);
				$get_index_today = array_search($endDate,$final->dates);
				$get_indexprev = array_search($prev_end_dates,$final->dates);
				$get_index_prev = array_search($prev_start_dates,$final->dates);


				$current_conversion_rate = $current_conversion_rate_organic = $current_transactions = $current_transactions_organic = $current_revenue = $current_revenue_organic = $current_avg_orderValue = $current_avg_orderValue_organic = array();

				$previous_conversion_rate = $previous_conversion_rate_organic = $previous_transactions = $previous_transactions_organic = $previous_revenue = $previous_revenue_organic = $previous_avg_orderValue = $previous_avg_orderValue_organic = array();


				if($get_index == false && $get_index_today == false){
					$current_conversion_rate[] = $current_conversion_rate_organic[] = $current_transactions[] = $current_transactions_organic[] = $current_revenue[] = $current_revenue_organic[] = $current_avg_orderValue[] = $current_avg_orderValue_organic[] = 0;
				}elseif($get_index && $get_index_today == false){
					$today = end($final->dates); 
					$get_index_today = array_search($today,$final->dates);
					for($i=$get_index;$i<=$get_index_today;$i++){
				//conversion rate 
						$current_conversion_rate[] = number_format($final->conversionRate[$i],2);
						$current_conversion_rate_organic[] = number_format($final->conversionRate_organic[$i],2);
				//transactions
						$current_transactions[] = number_format($final->transactions[$i],2);
						$current_transactions_organic[] = number_format($final->transactions_organic[$i],2, '.', '');
				//revenue
						$current_revenue[] = number_format($final->revenue[$i],2,'.','');
						$current_revenue_organic[] = number_format($final->revenue_organic[$i],2, '.', '');
				//avg order value
						$current_avg_orderValue[] = number_format($final->order_value[$i],2,'.','');
						$current_avg_orderValue_organic[] = number_format($final->order_value_organic[$i],2, '.', '');
					}	
				}else{
					for($i=$get_index;$i<=$get_index_today;$i++){
				//conversion rate 
						$current_conversion_rate[] = number_format($final->conversionRate[$i],2);
						$current_conversion_rate_organic[] = number_format($final->conversionRate_organic[$i],2);
				//transactions
						$current_transactions[] = number_format($final->transactions[$i],2);
						$current_transactions_organic[] = number_format($final->transactions_organic[$i],2, '.', '');
				//revenue
						$current_revenue[] = number_format($final->revenue[$i],2,'.','');
						$current_revenue_organic[] = number_format($final->revenue_organic[$i],2, '.', '');
				//avg order value
						$current_avg_orderValue[] = number_format($final->order_value[$i],2,'.','');
						$current_avg_orderValue_organic[] = number_format($final->order_value_organic[$i],2, '.', '');
					}	
				}


				if($get_indexprev == false && $get_index_prev == false){
					$previous_conversion_rate[] = $previous_conversion_rate_organic[] = $previous_transactions[] = $previous_transactions_organic[] = $previous_revenue[] = $previous_revenue_organic[] = $previous_avg_orderValue[] = $previous_avg_orderValue_organic[] = 0;
				}elseif($get_indexprev && $get_index_prev == false){

					$end_prev = date('Y-m-d',strtotime('-1 day',strtotime($startDate))); 
					$get_index_prev = array_search($end_prev,$final->dates);

					if($get_index_prev == false){ 
						$get_index_prev = array_search(end($final->dates),$final->dates);
					}

					for($j=$get_indexprev;$j<=$get_index_prev;$j++){
					//conversion rate 
						$previous_conversion_rate[] = number_format($final->conversionRate[$j],2);
						$previous_conversion_rate_organic[] = number_format($final->conversionRate_organic[$j],2);
					//transactions
						$previous_transactions[] = number_format($final->transactions[$j],2, '.', '');
						$previous_transactions_organic[] = number_format($final->transactions_organic[$j],2, '.', '');
					//revenue
						$previous_revenue[] = number_format($final->revenue[$j],2, '.', '');
						$previous_revenue_organic[] = number_format($final->revenue_organic[$j],2, '.', '');
					//avg order value
						$previous_avg_orderValue[] = number_format($final->order_value[$j],2,'.','');
						$previous_avg_orderValue_organic[] = number_format($final->order_value_organic[$j],2, '.', '');
					}
				}else{
					for($j=$get_indexprev;$j<=$get_index_prev;$j++){
					//conversion rate 
						$previous_conversion_rate[] = number_format($final->conversionRate[$j],2);
						$previous_conversion_rate_organic[] = number_format($final->conversionRate_organic[$j],2);
					//transactions
						$previous_transactions[] = number_format($final->transactions[$j],2, '.', '');
						$previous_transactions_organic[] = number_format($final->transactions_organic[$j],2, '.', '');
					//revenue
						$previous_revenue[] = number_format($final->revenue[$j],2, '.', '');
						$previous_revenue_organic[] = number_format($final->revenue_organic[$j],2, '.', '');
					//avg order value
						$previous_avg_orderValue[] = number_format($final->order_value[$j],2,'.','');
						$previous_avg_orderValue_organic[] = number_format($final->order_value_organic[$j],2, '.', '');
					}
				}


			//conversion rate
				$final_current_conversionRate = number_format((array_sum($current_conversion_rate)/$number_of_days),2);
				$final_previous_conversionRate = number_format((array_sum($previous_conversion_rate)/$number_of_days),2);
				$conversionRate_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_conversionRate,$final_previous_conversionRate);

				$final_current_conversionRate_organic = number_format((array_sum($current_conversion_rate_organic)/$number_of_days),2);
				$final_previous_conversionRate_organic = number_format((array_sum($previous_conversion_rate_organic)/$number_of_days),2);

				$conversionRate_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_conversionRate_organic,$final_previous_conversionRate_organic);


			//transactions
				$final_current_transactions = number_format(array_sum($current_transactions),2, '.', '');
				$final_previous_transactions = number_format(array_sum($previous_transactions),2, '.', '');
				$transactions_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_transactions,$final_previous_transactions);

				$final_current_transactions_organic = number_format(array_sum($current_transactions_organic),2, '.', '');
				$final_previous_transactions_organic = number_format(array_sum($previous_transactions_organic),2, '.', '');
				$transactions_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_transactions_organic,$final_previous_transactions_organic);


			//revenue
				$final_current_revenue = number_format(array_sum($current_revenue),2, '.', '');
				$final_previous_revenue = number_format(array_sum($previous_revenue),2, '.', '');
				$revenue_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_revenue,$final_previous_revenue);

				$final_current_revenue_organic = number_format(array_sum($current_revenue_organic),2, '.', '');
				$final_previous_revenue_organic = number_format(array_sum($previous_revenue_organic),2, '.', '');
				$revenue_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_revenue_organic,$final_previous_revenue_organic);

			//avg order value
				$final_current_avg_orderVal = number_format((array_sum($current_avg_orderValue)/$number_of_days),2);
				$final_previous_avg_orderVal = number_format((array_sum($previous_avg_orderValue)/$number_of_days),2);
				$avg_orderVal_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_avg_orderVal,$final_previous_avg_orderVal);

				$final_current_avg_orderVal_organic = number_format((array_sum($current_avg_orderValue_organic)/$number_of_days),2);
				$final_previous_avg_orderVal_organic = number_format((array_sum($previous_avg_orderValue_organic)/$number_of_days),2);
				$avg_orderVal_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_avg_orderVal_organic,$final_previous_avg_orderVal_organic);


			//current values
				$res['current_conversionRate'] = GoogleAnalyticsUsers::getFormattedValue($final_current_conversionRate);
				$res['current_transactions'] = GoogleAnalyticsUsers::getFormattedValue($final_current_transactions);
				$res['current_revenue'] = GoogleAnalyticsUsers::getFormattedValue($final_current_revenue);
				$res['current_avg_orderVal'] = GoogleAnalyticsUsers::getFormattedValue($final_current_avg_orderVal);

				$res['current_conversionRate_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_current_conversionRate_organic);
				$res['current_transactions_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_current_transactions_organic);
				$res['current_revenue_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_current_revenue_organic);
				$res['current_avg_orderVal_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_current_avg_orderVal_organic);

			//previous values
				$res['previous_conversionRate'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_conversionRate);
				$res['previous_transactions'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_transactions);
				$res['previous_revenue'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_revenue);
				$res['previous_avg_orderVal'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_avg_orderVal);

				$res['previous_conversionRate_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_conversionRate_organic);
				$res['previous_transactions_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_transactions_organic);
				$res['previous_revenue_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_revenue_organic);
				$res['previous_avg_orderVal_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_avg_orderVal_organic);

			//percentage values
				$res['conversionRate_percentage'] = $conversionRate_percentage;
				$res['transactions_percentage'] = $transactions_percentage;
				$res['revenue_percentage'] = $revenue_percentage;
				$res['avg_orderVal_percentage'] = $avg_orderVal_percentage;

				$res['conversionRate_percentage_organic'] = $conversionRate_percentage_organic;
				$res['transactions_percentage_organic'] = $transactions_percentage_organic;
				$res['revenue_percentage_organic'] = $revenue_percentage_organic;
				$res['avg_orderVal_percentage_organic'] = $avg_orderVal_percentage_organic;

				$res['compare_status'] = $compare_status;
				$res['status'] = 1;			
			}
		}
		return response()->json($res);
	}


	public function ajax_ecom_conversion_rate_users(Request $request){
		$result = $dates = array();
		$campaign_id = $request['campaign_id'];

		$data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$dates['status'] = 0;
		}else{
			if(!empty($data)){

				$default_duration = $data->duration;
				if($data->duration <= 3){
					$lapse ='+1 week';
					$start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
					$end_date = date('Y-m-d');
					$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				}


				if($data->duration >= 6 && $data->duration <= 12){
					$duration = $data->duration;
					$lapse ='+1 month';
				}

				if($data->duration == 24){
					$duration = $data->duration/3;
					$lapse = '+3 month';
				}
			}else{
				$duration =$default_duration =  3;
				$lapse = '+1 week';
				$start_date = date('Y-m-d',strtotime('-3 months'));
				$end_date = date('Y-m-d');
			}


			for($i=1;$i<=$duration;$i++){
				if($i==1){  
					$start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

				}else{
					$start_date = date('Y-m-d',strtotime($end_date));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				}
				$end_new[] = $end_date;


				$result[] = $this->conversionRate_users($start_date,$end_date,$campaign_id);
			}     

			$dates['status'] = 1;
			$dates['data'] = $result;
			$dates['from_datelabel'] = $end_new;


		}
		return $dates; 
	}


	private function conversionRate_users($start_date,$end_date,$campaign_id){
		$current_data = 0;
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			return $current_data;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json'; 
			if (!file_exists($url)) {
				return $current_data;
			}else{
				$data = file_get_contents($url);
				$final = json_decode($data);
				$get_index = array_search($start_date,$final->dates);
				$get_index_today = array_search($end_date,$final->dates);

				if($get_index == false && $get_index_today == false){
					return 0;
				}elseif($get_index <> false && $get_index_today == false){
					$get_index_today = array_search(end($final->dates),$final->dates);
				}


				for($i=$get_index;$i<=$get_index_today;$i++){
					$current_data += $final->conversionRate[$i];
				}

				return number_format($current_data, 2, '.', '');
			}
		}
	}

	public function ajax_ecom_conversion_rate_organic(Request $request){
		$result = $dates = array();
		$campaign_id = $request['campaign_id'];

		$data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$dates['status'] = 0;
		}else{
			if(!empty($data)){

				$default_duration = $data->duration;
				if($data->duration <= 3){
					$lapse ='+1 week';
					$start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
					$end_date = date('Y-m-d');
					$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				}


				if($data->duration >= 6 && $data->duration <= 12){
					$duration = $data->duration;
					$lapse ='+1 month';
				}

				if($data->duration == 24){
					$duration = $data->duration/3;
					$lapse = '+3 month';
				}
			}else{
				$duration =$default_duration =  3;
				$lapse = '+1 week';
				$start_date = date('Y-m-d',strtotime('-3 months'));
				$end_date = date('Y-m-d');
			}


			for($i=1;$i<=$duration;$i++){
				if($i==1){  
					$start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

				}else{
					$start_date = date('Y-m-d',strtotime($end_date));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				}
				$end_new[] = $end_date;


				$result[] = $this->conversionRate_organic($start_date,$end_date,$campaign_id);


			}     

			$dates['status'] = 1;
			$dates['data'] = $result;
			$dates['from_datelabel'] = $end_new;
		}
		return $dates; 
	}


	private function conversionRate_organic($start_date,$end_date,$campaign_id){
		$current_data = 0;
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			return $current_data;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json'; 
			if (!file_exists($url)) {
				return $current_data;
			} else{
				$data = file_get_contents($url);
				$final = json_decode($data);
				$get_index = array_search($start_date,$final->dates);
				$get_index_today = array_search($end_date,$final->dates);

				if($get_index == false && $get_index_today == false){
					return 0;
				}elseif($get_index <> false && $get_index_today == false){
					$get_index_today = array_search(end($final->dates),$final->dates);
				}


				for($i=$get_index;$i<=$get_index_today;$i++){
					$current_data += $final->conversionRate_organic[$i];
				}

				return number_format($current_data, 2, '.', '');
			}
		}
	}

	public function ajax_ecom_transaction_users(Request $request){
		$result = $dates = array();
		$campaign_id = $request['campaign_id'];

		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$dates['status'] = 0;
		} else {
			$data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

			if(!empty($data)){

				$default_duration = $data->duration;
				if($data->duration <= 3){
					$lapse ='+1 week';
					$start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
					$end_date = date('Y-m-d');
					$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				}


				if($data->duration >= 6 && $data->duration <= 12){
					$duration = $data->duration;
					$lapse ='+1 month';
				}

				if($data->duration == 24){
					$duration = $data->duration/3;
					$lapse = '+3 month';
				}
			}else{
				$duration =$default_duration =  3;
				$lapse = '+1 week';
				$start_date = date('Y-m-d',strtotime('-3 months'));
				$end_date = date('Y-m-d');
			}


			for($i=1;$i<=$duration;$i++){
				if($i==1){  
					$start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

				}else{
					$start_date = date('Y-m-d',strtotime($end_date));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				}
				$end_new[] = $end_date;


				$result[] = $this->transaction_users($start_date,$end_date,$campaign_id);


			}     

			$dates['status'] = 1;
			$dates['data'] = $result;
			$dates['from_datelabel'] = $end_new;
		}

		return $dates; 
	}


	private function transaction_users($start_date,$end_date,$campaign_id){
		$current_data = 0;
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			return $current_data;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json'; 
			if (!file_exists($url)) {
				return $current_data;
			} else {
				$data = file_get_contents($url);
				$final = json_decode($data);
				$get_index = array_search($start_date,$final->dates);
				$get_index_today = array_search($end_date,$final->dates);

				if($get_index == false && $get_index_today == false){
					return 0;
				}elseif($get_index <> false && $get_index_today == false){
					$get_index_today = array_search(end($final->dates),$final->dates);
				}


				for($i=$get_index;$i<=$get_index_today;$i++){
					$current_data += $final->transactions[$i];
				}

				return number_format($current_data, 2, '.', '');
			}
		}
	}

	public function ajax_ecom_transaction_organic(Request $request){
		$result = $dates = array();
		$campaign_id = $request['campaign_id'];
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$dates['status'] = 0;
		} else {
			$data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

			if(!empty($data)){

				$default_duration = $data->duration;
				if($data->duration <= 3){
					$lapse ='+1 week';
					$start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
					$end_date = date('Y-m-d');
					$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				}


				if($data->duration >= 6 && $data->duration <= 12){
					$duration = $data->duration;
					$lapse ='+1 month';
				}

				if($data->duration == 24){
					$duration = $data->duration/3;
					$lapse = '+3 month';
				}
			}else{
				$duration =$default_duration =  3;
				$lapse = '+1 week';
				$start_date = date('Y-m-d',strtotime('-3 months'));
				$end_date = date('Y-m-d');
			}


			for($i=1;$i<=$duration;$i++){
				if($i==1){  
					$start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

				}else{
					$start_date = date('Y-m-d',strtotime($end_date));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				}
				$end_new[] = $end_date;


				$result[] = $this->transaction_organic($start_date,$end_date,$campaign_id);


			}     

			$dates['status'] = 1;
			$dates['data'] = $result;
			$dates['from_datelabel'] = $end_new;
		}

		return $dates; 
	}

	private function transaction_organic($start_date,$end_date,$campaign_id){
		$current_data = 0;
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			return $current_data;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json'; 
			if (!file_exists($url)) {
				return $current_data;
			} else {
				$data = file_get_contents($url);
				$final = json_decode($data);
				$get_index = array_search($start_date,$final->dates);
				$get_index_today = array_search($end_date,$final->dates);

				if($get_index == false && $get_index_today == false){
					return 0;
				}elseif($get_index <> false && $get_index_today == false){
					$get_index_today = array_search(end($final->dates),$final->dates);
				}


				for($i=$get_index;$i<=$get_index_today;$i++){
					$current_data += $final->transactions_organic[$i];
				}

				return number_format($current_data, 2, '.', '');
			}
		}
	}

	public function ajax_ecom_revenue_users(Request $request){
		$result = $dates = array();
		$campaign_id = $request['campaign_id'];
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$dates['status'] = 0;
		} else {

			$data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

			if(!empty($data)){

				$default_duration = $data->duration;
				if($data->duration <= 3){
					$lapse ='+1 week';
					$start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
					$end_date = date('Y-m-d');
					$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				}


				if($data->duration >= 6 && $data->duration <= 12){
					$duration = $data->duration;
					$lapse ='+1 month';
				}

				if($data->duration == 24){
					$duration = $data->duration/3;
					$lapse = '+3 month';
				}
			}else{
				$duration =$default_duration =  3;
				$lapse = '+1 week';
				$start_date = date('Y-m-d',strtotime('-3 months'));
				$end_date = date('Y-m-d');
			}


			for($i=1;$i<=$duration;$i++){
				if($i==1){  
					$start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

				}else{
					$start_date = date('Y-m-d',strtotime($end_date));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				}
				$end_new[] = $end_date;


				$result[] = $this->revenue_users($start_date,$end_date,$campaign_id);


			}     

			$dates['status'] = 1;
			$dates['data'] = $result;
			$dates['from_datelabel'] = $end_new;
		}

		return $dates; 
	}

	private function revenue_users($start_date,$end_date,$campaign_id){
		$current_data = 0;
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			return $current_data;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json'; 
			if (!file_exists($url)) {
				return $current_data;
			} else {
				$data = file_get_contents($url);
				$final = json_decode($data);
				$get_index = array_search($start_date,$final->dates);
				$get_index_today = array_search($end_date,$final->dates);

				if($get_index == false && $get_index_today == false){
					return 0;
				}elseif($get_index <> false && $get_index_today == false){
					$get_index_today = array_search(end($final->dates),$final->dates);
				}


				for($i=$get_index;$i<=$get_index_today;$i++){
					$current_data += $final->revenue[$i];
				}

				return number_format($current_data, 2, '.', '');
			}
		}
	}

	public function ajax_ecom_revenue_organic(Request $request){
		$result = $dates = array();
		$campaign_id = $request['campaign_id'];
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$dates['status'] = 0;
		} else {
			$data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

			if(!empty($data)){

				$default_duration = $data->duration;
				if($data->duration <= 3){
					$lapse ='+1 week';
					$start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
					$end_date = date('Y-m-d');
					$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				}


				if($data->duration >= 6 && $data->duration <= 12){
					$duration = $data->duration;
					$lapse ='+1 month';
				}

				if($data->duration == 24){
					$duration = $data->duration/3;
					$lapse = '+3 month';
				}
			}else{
				$duration =$default_duration =  3;
				$lapse = '+1 week';
				$start_date = date('Y-m-d',strtotime('-3 months'));
				$end_date = date('Y-m-d');
			}


			for($i=1;$i<=$duration;$i++){
				if($i==1){  
					$start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

				}else{
					$start_date = date('Y-m-d',strtotime($end_date));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				}
				$end_new[] = $end_date;


				$result[] = $this->revenue_organic($start_date,$end_date,$campaign_id);


			}     

			$dates['status'] = 1;
			$dates['data'] = $result;
			$dates['from_datelabel'] = $end_new;
		}

		return $dates; 
	}

	private function revenue_organic($start_date,$end_date,$campaign_id){
		$current_data = 0;
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			return $current_data;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json'; 
			if (!file_exists($url)) {
				return $current_data;
			} else {
				$data = file_get_contents($url);
				$final = json_decode($data);
				$get_index = array_search($start_date,$final->dates);
				$get_index_today = array_search($end_date,$final->dates);

				if($get_index == false && $get_index_today == false){
					return 0;
				}elseif($get_index <> false && $get_index_today == false){
					$get_index_today = array_search(end($final->dates),$final->dates);
				}


				for($i=$get_index;$i<=$get_index_today;$i++){
					$current_data += $final->revenue_organic[$i];
				}

				return number_format($current_data, 2, '.', '');
			}
		}
	}

	public function ajax_ecom_avg_orderValue_users(Request $request){
		$result = $dates = array();
		$campaign_id = $request['campaign_id'];
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$dates['status'] = 0;
		} else {
			$data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

			if(!empty($data)){

				$default_duration = $data->duration;
				if($data->duration <= 3){
					$lapse ='+1 week';
					$start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
					$end_date = date('Y-m-d');
					$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				}


				if($data->duration >= 6 && $data->duration <= 12){
					$duration = $data->duration;
					$lapse ='+1 month';
				}

				if($data->duration == 24){
					$duration = $data->duration/3;
					$lapse = '+3 month';
				}
			}else{
				$duration =$default_duration =  3;
				$lapse = '+1 week';
				$start_date = date('Y-m-d',strtotime('-3 months'));
				$end_date = date('Y-m-d');
			}


			for($i=1;$i<=$duration;$i++){
				if($i==1){  
					$start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

				}else{
					$start_date = date('Y-m-d',strtotime($end_date));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				}
				$end_new[] = $end_date;


				$result[] = $this->order_value_users($start_date,$end_date,$campaign_id);


			}     

			$dates['status'] = 1;
			$dates['data'] = $result;
			$dates['from_datelabel'] = $end_new;
		}

		return $dates; 
	}

	private function order_value_users($start_date,$end_date,$campaign_id){
		$current_data = 0;
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			return $current_data;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json'; 
			if (!file_exists($url)) {
				return $current_data;
			} else {
				$data = file_get_contents($url);
				$final = json_decode($data);
				$get_index = array_search($start_date,$final->dates);
				$get_index_today = array_search($end_date,$final->dates);

				if($get_index == false && $get_index_today == false){
					return 0;
				}elseif($get_index <> false && $get_index_today == false){
					$get_index_today = array_search(end($final->dates),$final->dates);
				}


				for($i=$get_index;$i<=$get_index_today;$i++){
					$current_data += $final->order_value[$i];
				}

				return number_format($current_data, 2, '.', '');
			}
		}
	}

	public function ajax_ecom_avg_orderValue_organic(Request $request){
		$result = $dates = array();
		$campaign_id = $request['campaign_id'];
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$dates['status'] = 0;
		} else {
			$data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

			if(!empty($data)){

				$default_duration = $data->duration;
				if($data->duration <= 3){
					$lapse ='+1 week';
					$start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
					$end_date = date('Y-m-d');
					$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				}


				if($data->duration >= 6 && $data->duration <= 12){
					$duration = $data->duration;
					$lapse ='+1 month';
				}

				if($data->duration == 24){
					$duration = $data->duration/3;
					$lapse = '+3 month';
				}
			}else{
				$duration =$default_duration =  3;
				$lapse = '+1 week';
				$start_date = date('Y-m-d',strtotime('-3 months'));
				$end_date = date('Y-m-d');
			}


			for($i=1;$i<=$duration;$i++){
				if($i==1){  
					$start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

				}else{
					$start_date = date('Y-m-d',strtotime($end_date));
					$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				}
				$end_new[] = $end_date;


				$result[] = $this->order_value_organic($start_date,$end_date,$campaign_id);


			}     

			$dates['status'] = 1;
			$dates['data'] = $result;
			$dates['from_datelabel'] = $end_new;
		}

		return $dates; 
	}

	private function order_value_organic($start_date,$end_date,$campaign_id){
		$current_data = 0;
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			return $current_data;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json'; 
			if (!file_exists($url)) {
				return $current_data;
			} else {
				$data = file_get_contents($url);
				$final = json_decode($data);
				$get_index = array_search($start_date,$final->dates);
				$get_index_today = array_search($end_date,$final->dates);
				if($get_index == false && $get_index_today == false){
					return 0;
				}elseif($get_index <> false && $get_index_today == false){
					$get_index_today = array_search(end($final->dates),$final->dates);
				}


				for($i=$get_index;$i<=$get_index_today;$i++){
					$current_data += $final->order_value_organic[$i];
				}

				return number_format($current_data, 2, '.', '');

			}
		}
	}


	public function ajax_ecom_product(Request $request){
		$campaign_id = $request->campaign_id;

		$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

		$getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
		if(!empty($getCompareChart)){
			$compare_status = $getCompareChart->compare_status;
		}else{
			$compare_status = 0;
		}

		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$res['status'] = 0;
			return response()->json($res);
		} else {
			$end = date('M d, Y');

			$keysArr = $this->session_data_ecom_product($sessionHistoryRange,$campaign_id);
			
			$start_date = $keysArr['start_date'];
			$prev_day = $keysArr['prev_day'];
			$prev_date = $keysArr['prev_date'];
			$duration = $keysArr['duration'];
			$arr_name = $keysArr['keysArr']['arr_name'];
			$product = $keysArr['keysArr']['product'];
			$stats_data =  $this->get_ecom_product_stats($campaign_id,$start_date,$end,$prev_day,$prev_date);
			

			if (!file_exists($keysArr['url'])) {
				$res['status'] = 0;
				return response()->json($res);
			} else {
				$data = file_get_contents($keysArr['url']);
				$final = json_decode($data);
				$newCollection = collect($final->$arr_name->$product);

				$page = request()->has('page') ? request('page') : 1;

				$perPage = request()->has('per_page') ? request('per_page') : 4;

				$offset = ($page * $perPage) - $perPage;

				$results =  new LengthAwarePaginator(
					$newCollection->slice($offset, $perPage),
					$newCollection->count(),
					$perPage,
					$page
				);

				return view('vendor.seo_sections.ecommerce_goals.product_table', compact('final','end','start_date','prev_day','prev_date','duration','keysArr','compare_status','stats_data','results'))->render();
			}
		}
	}


	private function session_data_ecom_product($sessionHistoryRange,$campaign_id){
		if(empty($sessionHistoryRange) && $sessionHistoryRange == null){
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/three_month_product.json'; 
			$duration = 3;
			$start_date = date('M d, Y',strtotime('-3 month'));
			$start_date_new = date('Y-m-d',strtotime('-3 month'));
			$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
			$prev_date =  date('M d, Y',strtotime('-3 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

			$keysArr = [
				'arr_name'=>'current_three_array',
				'product'=>'three_current_product',
				'quantity'=>'three_current_quantity',

				'prev_arr_name'=>'prev_three_array',
				'prev_product'=>'three_prev_product',
				'prev_quantity'=>'three_prev_quantity', 

				'arr_name_organic'=>'current_three_organic_array',
				'product_organic'=>'three_current_organic_product',
				'quantity_organic'=>'three_current_organic_quantity',

				'prev_arr_name_organic'=>'prev_three_organic_array',
				'prev_product_organic'=>'three_previous_organic_product',
				'prev_quantity_organic'=>'three_previous_organic_quantity'
			];
		}else{
			if($sessionHistoryRange->duration == 1){
				$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/one_month_product.json'; 
				$duration = 1;
				$start_date = date('M d, Y',strtotime('-1 month'));
				$start_date_new = date('Y-m-d',strtotime('-1 month'));
				$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
				$prev_date =  date('M d, Y',strtotime('-1 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

				$keysArr = [
					'arr_name'=>'current_one_array',
					'product'=>'one_current_product',
					'quantity'=>'one_current_quantity',

					'prev_arr_name'=>'prev_one_array',
					'prev_product'=>'one_prev_product',
					'prev_quantity'=>'one_prev_quantity', 

					'arr_name_organic'=>'current_one_organic_array',
					'product_organic'=>'one_current_organic_product',
					'quantity_organic'=>'one_current_organic_quantity',

					'prev_arr_name_organic'=>'prev_one_organic_array',
					'prev_product_organic'=>'one_previous_organic_product',
					'prev_quantity_organic'=>'one_previous_organic_quantity'

				];

			}elseif($sessionHistoryRange->duration == 3){
				$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/three_month_product.json'; 
				$duration = 3;
				$start_date = date('M d, Y',strtotime('-3 month'));
				$start_date_new = date('Y-m-d',strtotime('-3 month'));
				$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
				$prev_date =  date('M d, Y',strtotime('-3 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

				$keysArr = [
					'arr_name'=>'current_three_array',
					'product'=>'three_current_product',
					'quantity'=>'three_current_quantity',

					'prev_arr_name'=>'prev_three_array',
					'prev_product'=>'three_prev_product',
					'prev_quantity'=>'three_prev_quantity', 

					'arr_name_organic'=>'current_three_organic_array',
					'product_organic'=>'three_current_organic_product',
					'quantity_organic'=>'three_current_organic_quantity',

					'prev_arr_name_organic'=>'prev_three_organic_array',
					'prev_product_organic'=>'three_previous_organic_product',
					'prev_quantity_organic'=>'three_previous_organic_quantity'
				];

			}elseif($sessionHistoryRange->duration == 6){
				$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/six_month_product.json'; 
				$duration = 6;
				$start_date = date('M d, Y',strtotime('-6 month'));
				$start_date_new = date('Y-m-d',strtotime('-6 month'));
				$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
				$prev_date =  date('M d, Y',strtotime('-6 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

				$keysArr = [
					'arr_name'=>'current_six_array',
					'product'=>'six_current_product',
					'quantity'=>'six_current_quantity',

					'prev_arr_name'=>'prev_six_array',
					'prev_product'=>'six_prev_product',
					'prev_quantity'=>'six_prev_quantity', 

					'arr_name_organic'=>'current_six_organic_array',
					'product_organic'=>'six_current_organic_product',
					'quantity_organic'=>'six_current_organic_quantity',

					'prev_arr_name_organic'=>'prev_six_organic_array',
					'prev_product_organic'=>'six_previous_organic_product',
					'prev_quantity_organic'=>'six_previous_organic_quantity'
				];
			}elseif($sessionHistoryRange->duration == 9){
				$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/nine_month_product.json'; 
				$duration = 9;
				$start_date = date('M d, Y',strtotime('-9 month'));
				$start_date_new = date('Y-m-d',strtotime('-9 month'));
				$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
				$prev_date =  date('M d, Y',strtotime('-9 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

				$keysArr = [
					'arr_name'=>'current_nine_array',
					'product'=>'nine_current_product',
					'quantity'=>'nine_current_quantity',

					'prev_arr_name'=>'prev_nine_array',
					'prev_product'=>'nine_prev_product',
					'prev_quantity'=>'nine_prev_quantity', 

					'arr_name_organic'=>'current_nine_organic_array',
					'product_organic'=>'nine_current_organic_product',
					'quantity_organic'=>'nine_current_organic_quantity',

					'prev_arr_name_organic'=>'prev_nine_organic_array',
					'prev_product_organic'=>'nine_previous_organic_product',
					'prev_quantity_organic'=>'nine_previous_organic_quantity'
				];
			}elseif($sessionHistoryRange->duration == 12){
				$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/year_product.json'; 
				$duration = 12;
				$start_date = date('M d, Y',strtotime('-1 year'));
				$start_date_new = date('Y-m-d',strtotime('-1 year'));
				$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
				$prev_date =  date('M d, Y',strtotime('-1 year',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

				$keysArr = [
					'arr_name'=>'current_year_array',
					'product'=>'year_current_product',
					'quantity'=>'year_current_quantity',

					'prev_arr_name'=>'prev_year_array',
					'prev_product'=>'year_prev_product',
					'prev_quantity'=>'year_prev_quantity', 

					'arr_name_organic'=>'current_year_organic_array',
					'product_organic'=>'year_current_organic_product',
					'quantity_organic'=>'year_current_organic_quantity',

					'prev_arr_name_organic'=>'prev_year_organic_array',
					'prev_product_organic'=>'year_previous_organic_product',
					'prev_quantity_organic'=>'year_previous_organic_quantity'
				];
			}elseif($sessionHistoryRange->duration == 24){
				$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/two_year_product.json'; 
				$duration = 24;
				$start_date = date('M d, Y',strtotime('-2 year'));
				$start_date_new = date('Y-m-d',strtotime('-2 year'));
				$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
				$prev_date =  date('M d, Y',strtotime('-2 year',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

				$keysArr = [
					'arr_name'=>'current_two_year_array',
					'product'=>'two_year_current_product',
					'quantity'=>'two_year_current_quantity',

					'prev_arr_name'=>'prev_two_year_array',
					'prev_product'=>'two_year_prev_product',
					'prev_quantity'=>'two_year_prev_quantity', 

					'arr_name_organic'=>'current_two_year_organic_array',
					'product_organic'=>'two_year_current_organic_product',
					'quantity_organic'=>'two_year_current_organic_quantity',

					'prev_arr_name_organic'=>'prev_two_year_organic_array',
					'prev_product_organic'=>'two_year_previous_organic_product',
					'prev_quantity_organic'=>'two_year_previous_organic_quantity'
				];
			}
		}
		return compact('keysArr','start_date','prev_day','prev_date','url','duration');
	}


	private function get_ecom_product_stats($campaign_id,$start_date,$end,$prev_day,$prev_date){

		$start_date = date('Y-m-d',strtotime($start_date));
		$end_date = date('Y-m-d',strtotime($end));
		$prev_date1 = date('Y-m-d',strtotime($prev_day));
		$prev_end_date1 = date('Y-m-d',strtotime($prev_date));
		$day_diff  =    strtotime($end_date) - strtotime($start_date);
		$count_days     =   floor($day_diff/(60*60*24));


		if (file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json')) {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);


			$get_index = array_search($start_date,$final->dates);
			$get_index_today = array_search($end_date,$final->dates);

			

			$get_indexprev = array_search($prev_end_date1,$final->dates);
			$get_index_prev = array_search($prev_date1,$final->dates);

			

			
			if($get_index == false && $get_index_today == false){
				$current_conversionRate[] = $current_conversionRate_organic[] = 0;
			}elseif($get_index && $get_index_today == false){
				$get_index_today = array_search(end($final->dates),$final->dates);

				for($i=$get_index;$i<=$get_index_today;$i++){
					$current_conversionRate[] = $final->revenue[$i];
					$current_conversionRate_organic[] = $final->revenue_organic[$i];
				}
			}else{
				for($i=$get_index;$i<=$get_index_today;$i++){
					$current_conversionRate[] = $final->revenue[$i];
					$current_conversionRate_organic[] = $final->revenue_organic[$i];
				}
			}


			if($get_indexprev == false && $get_index_prev == false){
				$previous_conversionRate[] = $previous_conversionRate_organic[] = 0;
			}elseif($get_indexprev && $get_index_prev == false){
				$end_prev = date('Y-m-d',strtotime('-1 day',strtotime($start_date))); 
				$get_index_prev = array_search($end_prev,$final->dates);

				if($get_index_prev == false){ 
					$get_index_prev = array_search(end($final->dates),$final->dates);
				}
				for($j=$get_indexprev;$j<=$get_index_prev;$j++){
					$previous_conversionRate[] = $final->revenue[$j];
					$previous_conversionRate_organic[] = $final->revenue_organic[$j];
				}
			}else{
				for($j=$get_indexprev;$j<=$get_index_prev;$j++){
					$previous_conversionRate[] = $final->revenue[$j];
					$previous_conversionRate_organic[] = $final->revenue_organic[$j];
				}
			}
			

			$final_current_conversionRate = array_sum($current_conversionRate);
			$final_previous_conversionRate = array_sum($previous_conversionRate);
			$final_current_conversionRate_organic = array_sum($current_conversionRate_organic);
			$final_previous_conversionRate_organic = array_sum($previous_conversionRate_organic);

			$result = array(
				'final_current_conversionRate' => $final_current_conversionRate,
				'final_previous_conversionRate' => $final_previous_conversionRate,
				'final_current_conversionRate_organic' => $final_current_conversionRate_organic,
				'final_previous_conversionRate_organic' => $final_previous_conversionRate_organic
			);
			return $result;
		}

	}


	public function ajax_ecom_product_pagination(Request $request){
		$campaign_id = $request->campaign_id;

		$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

		$getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
		if(!empty($getCompareChart)){
			$compare_status = $getCompareChart->compare_status;
		}else{
			$compare_status = 0;
		}

		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$res['status'] = 0;
			return response()->json($res);
		} else {
			$end = date('M d, Y');

			$keysArr = $this->session_data_ecom_product($sessionHistoryRange,$campaign_id);
			

			$start_date = $keysArr['start_date'];
			$prev_day = $keysArr['prev_day'];
			$prev_date = $keysArr['prev_date'];
			$duration = $keysArr['duration'];
			$arr_name = $keysArr['keysArr']['arr_name'];
			$product = $keysArr['keysArr']['product'];
			$stats_data =  $this->get_ecom_product_stats($campaign_id,$start_date,$end,$prev_day,$prev_date);
			if (!file_exists($keysArr['url'])) {
				$res['status'] = 0;
				return response()->json($res);
			} else {
				$data = file_get_contents($keysArr['url']);
				$final = json_decode($data);
				$newCollection = collect($final->$arr_name->$product);

				$page = request()->has('page') ? request('page') : 1;

				$perPage = request()->has('per_page') ? request('per_page') : 4;

				$offset = ($page * $perPage) - $perPage;

				$results =  new LengthAwarePaginator(
					$newCollection->slice($offset, $perPage),
					$newCollection->count(),
					$perPage,
					$page
				);

				return view('vendor.seo_sections.ecommerce_goals.pagination', compact('results'))->render();
			}
		}
	}

	/*August 30*/

	public function ajax_ecom_goal_completion_chart_viewkey(Request $request){

		$today = date('Y-m-d');
		$range = $request['value'];
		$campaign_id = $request['campaign_id'];
		$type = $request['type']?:'day';
		$module = 'organic_traffic';
		
		$state = ($request->has('key'))?'viewkey':'user';
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$res['status'] = 0;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/graph.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);

			if($request->has('compare_value')){
				$compare_status = $request->compare_value;
			}else{
				$getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
				if(!empty($getCompareChart)){
					$compare_status = $getCompareChart->compare_status;
				}else{
					$compare_status = 0;
				}
			}

			if($range == 'month'){
				$start_date_1 = date('Y-m-d',strtotime('-1 month'));
				$default_duration =1;
			}elseif($range == 'three'){
				$start_date_1 = date('Y-m-d',strtotime('-3 month'));
				$default_duration = 3;
			}elseif($range == 'six'){
				$start_date_1 = date('Y-m-d',strtotime('-6 month'));
				$default_duration =6;
			}elseif($range == 'nine'){
				$start_date_1 = date('Y-m-d',strtotime('-9 month'));
				$default_duration =9;
			}elseif($range == 'year'){
				$start_date_1 = date('Y-m-d',strtotime('-1 year'));
				$default_duration =12;
			}elseif($range == 'twoyear'){
				$start_date_1 = date('Y-m-d',strtotime('-2 year'));
				$default_duration =24;
			}else{
				$start_date_1 = date('Y-m-d',strtotime('-3 month'));
				$default_duration =3;
			}

			$startDate = date('Y-m-d',strtotime('-'.$default_duration.' months'));
			$endDate = date('Y-m-d');

			if($range == 'year'){
				$prev_start_dates =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
				$prev_end_dates =  date('Y-m-d',strtotime(' -1 year',strtotime($prev_start_dates)));
			}
			elseif($range == 'twoyear'){
				$prev_start_dates =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
				$prev_end_dates =  date('Y-m-d',strtotime(' -2 year',strtotime($prev_start_dates)));           
			}
			else{
				$prev_start_dates =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
				$prev_end_dates =  date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_start_dates)));
			}

			if($type == 'day'){
				$duration = ModuleByDateRange::calculate_days($startDate,$endDate);
				for($i=1;$i<=$duration;$i++){
					if($i==1){  
						$start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
						$prev_start_date = date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_start_dates))); 
					}else{
						$start_date = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
						$prev_start_date = date('Y-m-d',strtotime('+1 day',strtotime($prev_end_date))); 
					}

					$end_date = date('Y-m-d',strtotime('+0 day',strtotime($start_date)));    
					$prev_end_date = date('Y-m-d',strtotime('+0 day',strtotime($prev_start_date))); 

					$result[] = $this->ecom_goal_completion_chart($start_date,$end_date,$prev_start_date,$prev_end_date,$campaign_id,$type,0,0);
					if($default_duration == 1 || $default_duration == 3){
						$current[] = date('M d, Y',strtotime($start_date));
					}else{
						$current[] = date('M Y',strtotime($start_date));
					}
					$current_dates[] = date('l, F d, Y',strtotime($start_date));
					$current_prev_dates[] = date('l, F d, Y',strtotime($prev_start_date));
				}

			}


			if($type == 'week'){
				$i =1;
				$csd = $prev_end_dates;
				$sd = $startDate;
				/*infinite for loop*/
				for( ; ;){
					$start = $sd;
					$previous_start = $csd;

					if($i == 1){
						if(date('D', strtotime($start)) == 'Sun'){
							$end_date = date('Y-m-d',strtotime('saturday next week',strtotime($start)));
						}else{  
							$end_date = date('Y-m-d',strtotime('saturday this week',strtotime($start)));
						}

						if(date('D', strtotime($previous_start)) == 'Sun'){
							$previous_end = date('Y-m-d',strtotime('saturday next week',strtotime($previous_start)));
						}else{  
							$previous_end = date('Y-m-d',strtotime('saturday this week',strtotime($previous_start)));
						}
					}else{
						$end_date = date('Y-m-d',strtotime('saturday next week',strtotime($start)));
						$previous_end = date('Y-m-d',strtotime('saturday next week',strtotime($previous_start)));
					}


					$sd = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
					$csd = date('Y-m-d',strtotime('+1 day',strtotime($previous_end)));


					if($previous_end > $startDate){
						$previousEnd = date('Y-m-d',strtotime('-1 day',strtotime($startDate))); 
					}else{
						$previousEnd = $previous_end;
					}
					$previous_days = ModuleByDateRange::calculate_ecom_days($previous_start,$previousEnd);

					/*to break the infinite loop at the last entry*/
					if($end_date  > date('Y-m-d')){
						$enddate = date('Y-m-d');
						$current_days = ModuleByDateRange::calculate_ecom_days($start,$enddate);
						
						$result[] = $this->ecom_goal_completion_chart($start,$enddate,$previous_start,$previousEnd,$campaign_id,$type,$current_days,$previous_days);
						if($default_duration == 1){
							$current[] = date('M d, Y',strtotime($start));
						}else{
							$current[] = date('M Y',strtotime($start));
						}
						$current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($enddate));
						$current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
						break; 
					}else{
						$current_days = ModuleByDateRange::calculate_ecom_days($start,$end_date);
						
						$result[] = $this->ecom_goal_completion_chart($start,$end_date,$previous_start,$previousEnd,$campaign_id,$type,$current_days,$previous_days);
						if($default_duration == 1 || $default_duration == 3){
							$current[] = date('M d, Y',strtotime($start));
						}else{
							$current[] = date('M Y',strtotime($start));
						}
						$current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($end_date));
						$current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
					}

					$i++;
				}
			}

			if($type == 'month'){
				$i = 1;
				$csd = $prev_end_dates;
				$sd = $startDate;
				for( ; ;){
					$start = $sd;
					$previous_start = $csd;

					$end_date = date('Y-m-d',strtotime(date("Y-m-t", strtotime($start))));
					$previous_end = date('Y-m-d',strtotime(date("Y-m-t", strtotime($previous_start))));


					$sd = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
					$csd = date('Y-m-d',strtotime('+1 day',strtotime($previous_end)));

					if($previous_end > $startDate){
						$previousEnd = date('Y-m-d',strtotime('-1 day',strtotime($startDate))); 
					}else{
						$previousEnd = $previous_end;
					}

					$previous_days = ModuleByDateRange::calculate_ecom_days($previous_start,$previousEnd);

					if($end_date  > date('Y-m-d')){
						$enddate = date('Y-m-d');
						$current_days = ModuleByDateRange::calculate_ecom_days($start,$enddate);

						$result[] = $this->ecom_goal_completion_chart($start,$enddate,$previous_start,$previousEnd,$campaign_id,$type,$current_days,$previous_days);
						if($default_duration == 1 || $default_duration == 3){
							$current[] = date('M d, Y',strtotime($start));
						}else{
							$current[] = date('M Y',strtotime($start));
						}
						$current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($enddate));
						$current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
						break;
					}else{
						$current_days = ModuleByDateRange::calculate_ecom_days($start,$end_date);
						$result[] = $this->ecom_goal_completion_chart($start,$end_date,$previous_start,$previousEnd,$campaign_id,$type,$current_days,$previous_days);
						if($default_duration == 1 || $default_duration == 3){
							$current[] = date('M d, Y',strtotime($start));
						}else{
							$current[] = date('M Y',strtotime($start));
						}
						$current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($end_date));
						$current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
					}
					$i++;
				}
			}  


			$current_users = array_column($result, 'current_users');
			$prev_users = array_column($result, 'prev_users');
			$current_organic = array_column($result, 'current_organic');
			$prev_organic = array_column($result, 'prev_organic');

			$current_period  = date('d-m-Y', strtotime($startDate)).' to '.date('d-m-Y', strtotime($endDate));
			$prev_period   = date('d-m-Y', strtotime($prev_end_dates)).' to '.date('d-m-Y', strtotime($prev_start_dates));


			$res['from_datelabel'] = $current;
			$res['from_datelabels'] = $current_dates;
			$res['prev_from_datelabels'] = $current_prev_dates;
			$res['users'] = $current_users;
			$res['previous_users'] = $prev_users;
			$res['organic'] = $current_organic;
			$res['previous_organic'] = $prev_organic;
			$res['current_period'] = $current_period;
			$res['previous_period'] = $prev_period;
			$res['compare_status'] = $compare_status;
			$res['status'] = 1;
		}

		return response()->json($res);
	}


	public function ajax_ecom_goal_completion_overview_viewkey(Request $request){
		$endDate = date('Y-m-d');
		$range = $request['value'];
		$campaign_id = $request['campaign_id'];
		$type = $request['type']?:'day';
		$module = 'organic_traffic';
		$compare_status = ($request->compare_value)?$request->compare_value:0;
		$state = ($request->has('key'))?'viewkey':'user';

		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$res['status'] = 0;
		} else {
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/statistics.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);



			if($range == 'month'){
				$start_date_1 = date('Y-m-d',strtotime('-1 month'));
				$default_duration =1;
			}elseif($range == 'three'){
				$start_date_1 = date('Y-m-d',strtotime('-3 month'));
				$default_duration = 3;
			}elseif($range == 'six'){
				$start_date_1 = date('Y-m-d',strtotime('-6 month'));
				$default_duration =6;
			}elseif($range == 'nine'){
				$start_date_1 = date('Y-m-d',strtotime('-9 month'));
				$default_duration =9;
			}elseif($range == 'year'){
				$start_date_1 = date('Y-m-d',strtotime('-1 year'));
				$default_duration =12;
			}elseif($range == 'twoyear'){
				$start_date_1 = date('Y-m-d',strtotime('-2 year'));
				$default_duration =24;
			}else{
				$start_date_1 = date('Y-m-d',strtotime('-3 month'));
				$default_duration = 3;
			}

			$startDate = date('Y-m-d',strtotime('-'.$default_duration.' months'));

			if($range == 'year'){
				$prev_start_dates =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
				$prev_end_dates =  date('Y-m-d',strtotime(' -1 year',strtotime($prev_start_dates)));
			}
			elseif($range == 'twoyear'){
				$prev_start_dates =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
				$prev_end_dates =  date('Y-m-d',strtotime(' -2 year',strtotime($prev_start_dates)));           
			}
			else{
				$prev_start_dates =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
				$prev_end_dates =  date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_start_dates)));
			}

			$number_of_days = ModuleByDateRange::calculate_days($startDate,$endDate);

			$get_index = array_search($startDate,$final->dates);
			$get_index_today = array_search($endDate,$final->dates);

			// if($get_index_today == false){
			// 	$get_index_today = array_search(end($final->dates),$final->dates);
			// }

			$get_indexprev = array_search($prev_end_dates,$final->dates);
			$get_index_prev = array_search($prev_start_dates,$final->dates);

			// if($get_indexprev == false){
			// 	$prev_end_dates =  date('Y-m-d',strtotime('+1 day',strtotime($startDate))); 
			// 	$get_indexprev = array_search($prev_end_dates,$final->dates);
			// }
			

			$current_conversion_rate = $current_conversion_rate_organic = $current_transactions = $current_transactions_organic = $current_revenue = $current_revenue_organic = $current_avg_orderValue = $current_avg_orderValue_organic = array();

			if($get_index == false && $get_index_today == false){
				$current_conversion_rate[] = $current_conversion_rate_organic[] = $current_transactions[] = $current_transactions_organic[] = $current_revenue[] = $current_revenue_organic[] = $current_avg_orderValue[] = $current_avg_orderValue_organic[] = 0;
			}elseif($get_index && $get_index_today == false){
				$today = end($final->dates); 
				$get_index_today = array_search($today,$final->dates);
				for($i=$get_index;$i<=$get_index_today;$i++){
				//conversion rate 
					$current_conversion_rate[] = number_format($final->conversionRate[$i],2);
					$current_conversion_rate_organic[] = number_format($final->conversionRate_organic[$i],2);
				//transactions
					$current_transactions[] = number_format($final->transactions[$i],2);
					$current_transactions_organic[] = number_format($final->transactions_organic[$i],2, '.', '');
				//revenue
					$current_revenue[] = number_format($final->revenue[$i],2,'.','');
					$current_revenue_organic[] = number_format($final->revenue_organic[$i],2, '.', '');
				//avg order value
					$current_avg_orderValue[] = number_format($final->order_value[$i],2,'.','');
					$current_avg_orderValue_organic[] = number_format($final->order_value_organic[$i],2, '.', '');
				}	
			}else{
				for($i=$get_index;$i<=$get_index_today;$i++){
				//conversion rate 
					$current_conversion_rate[] = number_format($final->conversionRate[$i],2);
					$current_conversion_rate_organic[] = number_format($final->conversionRate_organic[$i],2);
				//transactions
					$current_transactions[] = number_format($final->transactions[$i],2);
					$current_transactions_organic[] = number_format($final->transactions_organic[$i],2, '.', '');
				//revenue
					$current_revenue[] = number_format($final->revenue[$i],2,'.','');
					$current_revenue_organic[] = number_format($final->revenue_organic[$i],2, '.', '');
				//avg order value
					$current_avg_orderValue[] = number_format($final->order_value[$i],2,'.','');
					$current_avg_orderValue_organic[] = number_format($final->order_value_organic[$i],2, '.', '');
				}	
			}
			

			if($get_indexprev == false && $get_index_prev == false){
				$previous_conversion_rate[] = $previous_conversion_rate_organic[] = $previous_transactions[] = $previous_transactions_organic[] = $previous_revenue[] = $previous_revenue_organic[] = $previous_avg_orderValue[] = $previous_avg_orderValue_organic[] = 0;
			}elseif($get_indexprev && $get_index_prev == false){
				
				$end_prev = date('Y-m-d',strtotime('-1 day',strtotime($startDate))); 
				$get_index_prev = array_search($end_prev,$final->dates);

				if($get_index_prev == false){ 
					$get_index_prev = array_search(end($final->dates),$final->dates);
				}

				for($j=$get_indexprev;$j<=$get_index_prev;$j++){
					//conversion rate 
					$previous_conversion_rate[] = number_format($final->conversionRate[$j],2);
					$previous_conversion_rate_organic[] = number_format($final->conversionRate_organic[$j],2);
					//transactions
					$previous_transactions[] = number_format($final->transactions[$j],2, '.', '');
					$previous_transactions_organic[] = number_format($final->transactions_organic[$j],2, '.', '');
					//revenue
					$previous_revenue[] = number_format($final->revenue[$j],2, '.', '');
					$previous_revenue_organic[] = number_format($final->revenue_organic[$j],2, '.', '');
					//avg order value
					$previous_avg_orderValue[] = number_format($final->order_value[$j],2,'.','');
					$previous_avg_orderValue_organic[] = number_format($final->order_value_organic[$j],2, '.', '');
				}
			}else{
				for($j=$get_indexprev;$j<=$get_index_prev;$j++){
					//conversion rate 
					$previous_conversion_rate[] = number_format($final->conversionRate[$j],2);
					$previous_conversion_rate_organic[] = number_format($final->conversionRate_organic[$j],2);
					//transactions
					$previous_transactions[] = number_format($final->transactions[$j],2, '.', '');
					$previous_transactions_organic[] = number_format($final->transactions_organic[$j],2, '.', '');
					//revenue
					$previous_revenue[] = number_format($final->revenue[$j],2, '.', '');
					$previous_revenue_organic[] = number_format($final->revenue_organic[$j],2, '.', '');
					//avg order value
					$previous_avg_orderValue[] = number_format($final->order_value[$j],2,'.','');
					$previous_avg_orderValue_organic[] = number_format($final->order_value_organic[$j],2, '.', '');
				}
			}


			//conversion rate
			$final_current_conversionRate = number_format((array_sum($current_conversion_rate)/$number_of_days),2);
			$final_previous_conversionRate = number_format((array_sum($previous_conversion_rate)/$number_of_days),2);
			$conversionRate_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_conversionRate,$final_previous_conversionRate);

			$final_current_conversionRate_organic = number_format((array_sum($current_conversion_rate_organic)/$number_of_days),2);
			$final_previous_conversionRate_organic = number_format((array_sum($previous_conversion_rate_organic)/$number_of_days),2);
			
			$conversionRate_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_conversionRate_organic,$final_previous_conversionRate_organic);


			//transactions
			$final_current_transactions = number_format(array_sum($current_transactions),2, '.', '');
			$final_previous_transactions = number_format(array_sum($previous_transactions),2, '.', '');
			$transactions_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_transactions,$final_previous_transactions);

			$final_current_transactions_organic = number_format(array_sum($current_transactions_organic),2, '.', '');
			$final_previous_transactions_organic = number_format(array_sum($previous_transactions_organic),2, '.', '');
			$transactions_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_transactions_organic,$final_previous_transactions_organic);


			//revenue
			$final_current_revenue = number_format(array_sum($current_revenue),2, '.', '');
			$final_previous_revenue = number_format(array_sum($previous_revenue),2, '.', '');
			$revenue_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_revenue,$final_previous_revenue);

			$final_current_revenue_organic = number_format(array_sum($current_revenue_organic),2, '.', '');
			$final_previous_revenue_organic = number_format(array_sum($previous_revenue_organic),2, '.', '');
			$revenue_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_revenue_organic,$final_previous_revenue_organic);
			
			//avg order value
			$final_current_avg_orderVal = number_format((array_sum($current_avg_orderValue)/$number_of_days),2);
			$final_previous_avg_orderVal = number_format((array_sum($previous_avg_orderValue)/$number_of_days),2);
			$avg_orderVal_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_avg_orderVal,$final_previous_avg_orderVal);

			$final_current_avg_orderVal_organic = number_format((array_sum($current_avg_orderValue_organic)/$number_of_days),2);
			$final_previous_avg_orderVal_organic = number_format((array_sum($previous_avg_orderValue_organic)/$number_of_days),2);
			$avg_orderVal_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_avg_orderVal_organic,$final_previous_avg_orderVal_organic);


			//current values
			$res['current_conversionRate'] = GoogleAnalyticsUsers::getFormattedValue($final_current_conversionRate);
			$res['current_transactions'] = GoogleAnalyticsUsers::getFormattedValue($final_current_transactions);
			$res['current_revenue'] = GoogleAnalyticsUsers::getFormattedValue($final_current_revenue);
			$res['current_avg_orderVal'] = GoogleAnalyticsUsers::getFormattedValue($final_current_avg_orderVal);

			$res['current_conversionRate_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_current_conversionRate_organic);
			$res['current_transactions_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_current_transactions_organic);
			$res['current_revenue_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_current_revenue_organic);
			$res['current_avg_orderVal_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_current_avg_orderVal_organic);

			//previous values
			$res['previous_conversionRate'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_conversionRate);
			$res['previous_transactions'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_transactions);
			$res['previous_revenue'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_revenue);
			$res['previous_avg_orderVal'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_avg_orderVal);

			$res['previous_conversionRate_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_conversionRate_organic);
			$res['previous_transactions_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_transactions_organic);
			$res['previous_revenue_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_revenue_organic);
			$res['previous_avg_orderVal_organic'] = GoogleAnalyticsUsers::getFormattedValue($final_previous_avg_orderVal_organic);

			//percentage values
			$res['conversionRate_percentage'] = $conversionRate_percentage;
			$res['transactions_percentage'] = $transactions_percentage;
			$res['revenue_percentage'] = $revenue_percentage;
			$res['avg_orderVal_percentage'] = $avg_orderVal_percentage;

			$res['conversionRate_percentage_organic'] = $conversionRate_percentage_organic;
			$res['transactions_percentage_organic'] = $transactions_percentage_organic;
			$res['revenue_percentage_organic'] = $revenue_percentage_organic;
			$res['avg_orderVal_percentage_organic'] = $avg_orderVal_percentage_organic;

			$res['compare_status'] = $compare_status;
			$res['status'] = 1;			
		}
		return response()->json($res);
	}

	public function ajax_ecom_product_viewkey(Request $request){
		$endDate = date('Y-m-d');
		$range = $request['value'];
		$campaign_id = $request['campaign_id'];
		$type = $request['type']?:'day';
		$state = ($request->has('key'))?'viewkey':'user';

		if($request->has('compare_value')){
			$compare_status = $request->compare_value;
		}else{
			$getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
			if(!empty($getCompareChart)){
				$compare_status = $getCompareChart->compare_status;
			}else{
				$compare_status = 0;
			}
		}
		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$res['status'] = 0;
			return response()->json($res);
		} else {
			$end = date('M d, Y');

			$keysArr = $this->session_data_ecom_product_vk($range,$campaign_id);
			
			$start_date = $keysArr['start_date'];
			$prev_day = $keysArr['prev_day'];
			$prev_date = $keysArr['prev_date'];
			$duration = $keysArr['duration'];
			$arr_name = $keysArr['keysArr']['arr_name'];
			$product = $keysArr['keysArr']['product'];
			$stats_data =  $this->get_ecom_product_stats($campaign_id,$start_date,$end,$prev_day,$prev_date);
			


			$data = file_get_contents($keysArr['url']);
			$final = json_decode($data);
			$newCollection = collect($final->$arr_name->$product);

			$page = request()->has('page') ? request('page') : 1;

   			 // Set default per page
			$perPage = request()->has('per_page') ? request('per_page') : 4;

   			 // Offset required to take the results
			$offset = ($page * $perPage) - $perPage;

			$results =  new LengthAwarePaginator(
				$newCollection->slice($offset, $perPage),
				$newCollection->count(),
				$perPage,
				$page
			);


			return view('viewkey.seo_sections.ecommerce_goals.product_table', compact('final','end','start_date','prev_day','prev_date','duration','keysArr','compare_status','stats_data','results'))->render();
		}
	}

	private function session_data_ecom_product_vk($sessionHistoryRange,$campaign_id){
		if($sessionHistoryRange == 'one'){
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/one_month_product.json'; 
			$duration = 1;
			$start_date = date('M d, Y',strtotime('-1 month'));
			$start_date_new = date('Y-m-d',strtotime('-1 month'));
			$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
			$prev_date =  date('M d, Y',strtotime('-1 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

			$keysArr = [
				'arr_name'=>'current_one_array',
				'product'=>'one_current_product',
				'quantity'=>'one_current_quantity',

				'prev_arr_name'=>'prev_one_array',
				'prev_product'=>'one_prev_product',
				'prev_quantity'=>'one_prev_quantity', 

				'arr_name_organic'=>'current_one_organic_array',
				'product_organic'=>'one_current_organic_product',
				'quantity_organic'=>'one_current_organic_quantity',

				'prev_arr_name_organic'=>'prev_one_organic_array',
				'prev_product_organic'=>'one_previous_organic_product',
				'prev_quantity_organic'=>'one_previous_organic_quantity'

			];
		}elseif($sessionHistoryRange == 'three'){
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/three_month_product.json'; 
			$duration = 3;
			$start_date = date('M d, Y',strtotime('-3 month'));
			$start_date_new = date('Y-m-d',strtotime('-3 month'));
			$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
			$prev_date =  date('M d, Y',strtotime('-3 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

			$keysArr = [
				'arr_name'=>'current_three_array',
				'product'=>'three_current_product',
				'quantity'=>'three_current_quantity',

				'prev_arr_name'=>'prev_three_array',
				'prev_product'=>'three_prev_product',
				'prev_quantity'=>'three_prev_quantity', 

				'arr_name_organic'=>'current_three_organic_array',
				'product_organic'=>'three_current_organic_product',
				'quantity_organic'=>'three_current_organic_quantity',

				'prev_arr_name_organic'=>'prev_three_organic_array',
				'prev_product_organic'=>'three_previous_organic_product',
				'prev_quantity_organic'=>'three_previous_organic_quantity'
			];
		}elseif($sessionHistoryRange == 'six'){
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/six_month_product.json'; 
			$duration = 6;
			$start_date = date('M d, Y',strtotime('-6 month'));
			$start_date_new = date('Y-m-d',strtotime('-6 month'));
			$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
			$prev_date =  date('M d, Y',strtotime('-6 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

			$keysArr = [
				'arr_name'=>'current_six_array',
				'product'=>'six_current_product',
				'quantity'=>'six_current_quantity',

				'prev_arr_name'=>'prev_six_array',
				'prev_product'=>'six_prev_product',
				'prev_quantity'=>'six_prev_quantity', 

				'arr_name_organic'=>'current_six_organic_array',
				'product_organic'=>'six_current_organic_product',
				'quantity_organic'=>'six_current_organic_quantity',

				'prev_arr_name_organic'=>'prev_six_organic_array',
				'prev_product_organic'=>'six_previous_organic_product',
				'prev_quantity_organic'=>'six_previous_organic_quantity'
			];
		}elseif($sessionHistoryRange == 'nine'){
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/nine_month_product.json'; 
			$duration = 9;
			$start_date = date('M d, Y',strtotime('-9 month'));
			$start_date_new = date('Y-m-d',strtotime('-9 month'));
			$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
			$prev_date =  date('M d, Y',strtotime('-9 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

			$keysArr = [
				'arr_name'=>'current_nine_array',
				'product'=>'nine_current_product',
				'quantity'=>'nine_current_quantity',

				'prev_arr_name'=>'prev_nine_array',
				'prev_product'=>'nine_prev_product',
				'prev_quantity'=>'nine_prev_quantity', 

				'arr_name_organic'=>'current_nine_organic_array',
				'product_organic'=>'nine_current_organic_product',
				'quantity_organic'=>'nine_current_organic_quantity',

				'prev_arr_name_organic'=>'prev_nine_organic_array',
				'prev_product_organic'=>'nine_previous_organic_product',
				'prev_quantity_organic'=>'nine_previous_organic_quantity'
			];
		}elseif($sessionHistoryRange == 'year'){
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/year_product.json'; 
			$duration = 12;
			$start_date = date('M d, Y',strtotime('-1 year'));
			$start_date_new = date('Y-m-d',strtotime('-1 year'));
			$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
			$prev_date =  date('M d, Y',strtotime('-1 year',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

			$keysArr = [
				'arr_name'=>'current_year_array',
				'product'=>'year_current_product',
				'quantity'=>'year_current_quantity',

				'prev_arr_name'=>'prev_year_array',
				'prev_product'=>'year_prev_product',
				'prev_quantity'=>'year_prev_quantity', 

				'arr_name_organic'=>'current_year_organic_array',
				'product_organic'=>'year_current_organic_product',
				'quantity_organic'=>'year_current_organic_quantity',

				'prev_arr_name_organic'=>'prev_year_organic_array',
				'prev_product_organic'=>'year_previous_organic_product',
				'prev_quantity_organic'=>'year_previous_organic_quantity'
			];
		}elseif($sessionHistoryRange == 'twoyear'){
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/two_year_product.json'; 
			$duration = 24;
			$start_date = date('M d, Y',strtotime('-2 year'));
			$start_date_new = date('Y-m-d',strtotime('-2 year'));
			$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
			$prev_date =  date('M d, Y',strtotime('-2 year',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

			$keysArr = [
				'arr_name'=>'current_two_year_array',
				'product'=>'two_year_current_product',
				'quantity'=>'two_year_current_quantity',

				'prev_arr_name'=>'prev_two_year_array',
				'prev_product'=>'two_year_prev_product',
				'prev_quantity'=>'two_year_prev_quantity', 

				'arr_name_organic'=>'current_two_year_organic_array',
				'product_organic'=>'two_year_current_organic_product',
				'quantity_organic'=>'two_year_current_organic_quantity',

				'prev_arr_name_organic'=>'prev_two_year_organic_array',
				'prev_product_organic'=>'two_year_previous_organic_product',
				'prev_quantity_organic'=>'two_year_previous_organic_quantity'
			];
		}else{
			$url = env('FILE_PATH')."public/ecommerce_goals/".$campaign_id.'/three_month_product.json'; 
			$duration = 3;
			$start_date = date('M d, Y',strtotime('-3 month'));
			$start_date_new = date('Y-m-d',strtotime('-3 month'));
			$prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
			$prev_date =  date('M d, Y',strtotime('-3 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

			$keysArr = [
				'arr_name'=>'current_three_array',
				'product'=>'three_current_product',
				'quantity'=>'three_current_quantity',

				'prev_arr_name'=>'prev_three_array',
				'prev_product'=>'three_prev_product',
				'prev_quantity'=>'three_prev_quantity', 

				'arr_name_organic'=>'current_three_organic_array',
				'product_organic'=>'three_current_organic_product',
				'quantity_organic'=>'three_current_organic_quantity',

				'prev_arr_name_organic'=>'prev_three_organic_array',
				'prev_product_organic'=>'three_previous_organic_product',
				'prev_quantity_organic'=>'three_previous_organic_quantity'
			];
		}
		return compact('keysArr','start_date','prev_day','prev_date','url','duration');
	}

	public function ajax_ecom_product_pagination_viewkey(Request $request){

		$endDate = date('Y-m-d');
		$range = $request['value'];
		$campaign_id = $request['campaign_id'];
		$type = $request['type']?:'day';
		$state = ($request->has('key'))?'viewkey':'user';

		if($request->has('compare_value')){
			$compare_status = $request->compare_value;
		}else{
			$getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
			if(!empty($getCompareChart)){
				$compare_status = $getCompareChart->compare_status;
			}else{
				$compare_status = 0;
			}
		}



		if (!file_exists(env('FILE_PATH')."public/ecommerce_goals/".$campaign_id)) {
			$res['status'] = 0;
			return response()->json($res);
		} else {
			$end = date('M d, Y');

			$keysArr = $this->session_data_ecom_product_vk($range,$campaign_id);
			

			$start_date = $keysArr['start_date'];
			$prev_day = $keysArr['prev_day'];
			$prev_date = $keysArr['prev_date'];
			$duration = $keysArr['duration'];
			$arr_name = $keysArr['keysArr']['arr_name'];
			$product = $keysArr['keysArr']['product'];
			$stats_data =  $this->get_ecom_product_stats($campaign_id,$start_date,$end,$prev_day,$prev_date);

			$data = file_get_contents($keysArr['url']);
			$final = json_decode($data);
			$newCollection = collect($final->$arr_name->$product);

			$page = request()->has('page') ? request('page') : 1;

   			 // Set default per page
			$perPage = request()->has('per_page') ? request('per_page') : 4;

   			 // Offset required to take the results
			$offset = ($page * $perPage) - $perPage;

			$results =  new LengthAwarePaginator(
				$newCollection->slice($offset, $perPage),
				$newCollection->count(),
				$perPage,
				$page
			);

			return view('viewkey.seo_sections.ecommerce_goals.pagination', compact('results'))->render();
		}
	}


}