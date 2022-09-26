<?php

namespace App\Http\Controllers\ViewKey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use DB;
use Carbon\Carbon;
use App\ActivityLog;
use App\SemrushUserAccount;
use App\ProjectCompareGraph;
use App\ModuleByDateRange;
use App\GoogleAnalyticsUsers;
use App\SemrushOrganicMetric;
use App\KeywordLocationList;
use App\KeywordSearch;
use App\KeywordPosition;
use App\SemrushOrganicSearchData;
use App\BackLinksData;
use App\GoogleProfileData;
use App\GoogleGoalCompletion;
use App\BacklinkSummary;


class SeoController extends Controller
{
	
	public function get_account_activity (Request $request){
		$request_id = $request['request_id'];
		$lastDate = $request['lastDate'];
		$limit = $request['limit'];

		$roundArr = array('purple','green','pink','yellow','blue');
		$resultArr = '';

		$date = '';	

		$results = ActivityLog::campaign_activity($request_id,$limit);
		
		
		if(!empty($results) && isset($results)){
			foreach($results['data'] as $key=>$value){
				$keytime = array_rand($roundArr); 


				$date = date('l, d F Y',strtotime($value->created_at));


				if($date <> $lastDate){
					$lastDate = date('l, d F Y',strtotime($value->created_at));
					$resultArr .= '<div class="account-timeline-date">'.$date.'</div>';
				}

				$resultArr .= '<article>';
				$resultArr .= '<div class="account-timeline-time">'.date('h:i A',strtotime($value->created_at)).'</div>';
				$resultArr .= '<div class="account-timeline-badge '.$roundArr[$keytime].'"><span></span></div>';
				$resultArr .= '<div class="account-timeline-info">'.$value->description.'</div>';
				$resultArr .= '</article>';

				if($key == 0 && $lastDate == ''){
					$lastDate = date('l, d F Y',strtotime($value->created_at));
				}
				
			}

			$finalArr = array('html' => $resultArr,'limit'=>$results['limit']);
			return response()->json($finalArr);
		}
	}

	public function ajax_traffic_growth_data(Request $request){
		
		$campaignId = $request['campaignId'];

		
		try{
			$getUser = SemrushUserAccount::where('id',$campaignId)->first();
			$user_id = $getUser->user_id;
			$prev_session  = $current_pageView = $prev_pageView = $current_users = $prev_users = $from_dates=  $combine_session = $compare_status = $count_session = $current_period = $previous_period = '';
			
			$total_sessions	= $traffic_growth = $total_users = $total_pageview = 'N/A';
			$current_session = $final_Session = $final_users =  $final_pageView = '0';


			if(!empty($getUser)){
				$getCompareChart = ProjectCompareGraph::getCompareChart($campaignId);
				if(!empty($getCompareChart)){
					$compare_status = $getCompareChart->compare_status;
				}
				
				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'organic_traffic');

				if(empty($sessionHistoryRange)){

					$start_date = date('Y-m-d');
					$end_date =  date('Y-m-d', strtotime("-3 months", strtotime(date('Y-m-d'))));

					$prev_start_date = date('Y-m-d', strtotime("-3 months", strtotime($end_date)));


					$day_diff  = 	strtotime($end_date) - strtotime($start_date);
					$count_days    	=	floor($day_diff/(60*60*24));

					$start_data   =   date('Y-m-d', strtotime($end_date.' '.$count_days.' days'));

					$current_period     =   date('d-m-Y', strtotime($end_date)).' to '.date('d-m-Y', strtotime($start_date));
					$previous_period    =   date('d-m-Y', strtotime($start_data)).' to '.date('d-m-Y', strtotime("-3 months", strtotime($end_date)));

				}else{

					$end_date   =	date('Y-m-d', strtotime($sessionHistoryRange->start_date));
					$start_date    =   date('Y-m-d', strtotime($sessionHistoryRange->end_date));

					$day_diff  = 	strtotime($sessionHistoryRange->start_date) - strtotime($sessionHistoryRange->end_date);
					$count_days    	=	floor($day_diff/(60*60*24));

					$start_data   =   date('Y-m-d', strtotime($sessionHistoryRange->start_date.' '.$count_days.' days'));

					$current_period     =   date('d-m-Y', strtotime($start_date)).' to '.date('d-m-Y', strtotime($end_date));
					$previous_period    =   date('d-m-Y', strtotime($start_data)).' to '.date('d-m-Y', strtotime($start_date));					 
				}
				
				
				$getAnalytics  = GoogleAnalyticsUsers::accountInfoById($user_id,$getUser->google_account_id); 
				// echo '<pre>';
					// print_r($getAnalytics);
					// die;
				if(!empty($getAnalytics)){
					$status = 1;
					$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);
					

					$refresh_token  = $getAnalytics->google_refresh_token;


					/*if refresh token expires*/
					if ($client->isAccessTokenExpired()) {
						GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
					}

					$getAnalyticsId = SemrushUserAccount::with('google_analytics_account')->where('user_id',$user_id)->first();
					
					if(isset($getAnalyticsId->google_analytics_account)){
						$analyticsCategoryId = $getAnalyticsId->google_analytics_account->category_id;
						
						$analytics = new \Google_Service_Analytics($client);
						$profile = GoogleAnalyticsUsers::getFirstProfileId($analytics,$analyticsCategoryId);

						$removeMinus = str_replace('-','',$count_days);
						if($removeMinus >= 90){
							$current_data = GoogleAnalyticsUsers::getResultByWeek($analytics, $profile,$start_date,$end_date);	
							

							$output = array_column ($current_data->rows , 0);

							for($i=0;$i<count($output);$i++){
								$outputRes[] =ModuleByDateRange::getStartAndEndDate($output[$i],date('Y'));
							}
							
							$previous_data =  GoogleAnalyticsUsers::getResultByWeek($analytics, $profile,$start_date,$start_data);


						}else{
							$current_data = GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$start_date,$end_date);	
							
							$outputRes = array_column ($current_data->rows , 0);
							
							$previous_data =  GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$start_date,$start_data);
						}

						$count_session = array_column ( $current_data->rows , 1);
						$from_dates  =  array_map(function($val) { return date("d M, Y", strtotime($val)); }, $outputRes);			
						$combine_session = array_column($previous_data->rows , 1);



						if(!empty($getAnalyticsId->google_profile_id)){
							$currentData = GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$start_date,$end_date);
							$previousData = GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$start_date,$start_data);


							/*session data & traffic*/
							if(!empty($currentData[0][0]) && (!empty($previousData[0][0]))){
								$total_sessions = number_format(($currentData[0][0]  - $previousData[0][0]) / $previousData[0][0] * 100, 2).'%';
								$traffic_growth = number_format(($currentData[0][0]  - $previousData[0][0]) / $previousData[0][0] * 100, 2).'%'; 
							}else if(empty($currentData[0][0]) && !empty($previousData[0][0]) ) {
								$total_sessions	= ' -100%';
								$traffic_growth	= ' -100%';
							} else if(!empty($currentData[0][0]) && empty($previousData[0][0]) ) {
								$total_sessions	= ' 100%';
								$traffic_growth	= ' 100%';
							} else{
								$total_sessions	= ' N/A';
								$traffic_growth	= ' N/A';
							}


							if(!empty($currentData[0][0])){
								$current_session = number_format($currentData[0][0]);
							}else{
								$current_session = '0';
							}


							if(!empty($previousData[0][0])){
								$prev_session = number_format($previousData[0][0]);
							}else{
								$prev_session = '0';
							}


							$final_Session = $current_session. ' vs '.$prev_session;


							/*users*/
							if(!empty($currentData[0][1]) && !empty($previousData[0][1])) {
								$total_users = number_format(($currentData[0][1]  - $previousData[0][1]) / $previousData[0][1] * 100, 2).'%';

							} else if(empty($currentData[0][1]) && !empty($previousData[0][1]) ) {
								$total_users = '-100%';
							} else if(!empty($currentData[0][1]) && empty($previousData[0][1]) ) {
								$total_users = '100%';
							} else{
								$total_users = 'N/A';
							}


							if(!empty($currentData[0][1])){
								$current_users = number_format($currentData[0][1]);
							}else{
								$current_users = '0';
							}


							if(!empty($previousData[0][1])){
								$prev_users = number_format($previousData[0][1]);
							}else{
								$prev_users = '0';
							}
							$final_users = $current_users .' vs '.$prev_users;

							/*pageViews*/
							if(!empty($currentData[0][2]) && !empty($previousData[0][2])) {
								$total_pageview = number_format(($currentData[0][2]  - $previousData[0][2]) / $previousData[0][2] * 100, 2).'%';
							} else if(empty($currentData[0][2]) && !empty($previousData[0][2]) ) {
								$total_pageview = '-100%';
							} else if(!empty($currentData[0][2]) && empty($previousData[0][2]) ) {
								$total_pageview = '100%';
							} else{
								$total_pageview = 'N/A';
							}		

							if(!empty($currentData[0][2])){
								$current_pageView = number_format($currentData[0][2]);
							}else{
								$current_pageView = '0';
							}


							if(!empty($previousData[0][2])){
								$prev_pageView = number_format($previousData[0][2]);
							}else{
								$prev_pageView = '0';
							}

							$final_pageView= $current_pageView .' vs '.$prev_pageView;


							$today = date('Y-m-d');
							$yesterday = date('Y-m-d',strtotime('-1 days'));
							$beforeyesterday = date('Y-m-d',strtotime('-2 days'));

							$getCurrentStatsToday   =  GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$today,$yesterday);


							$getCurrentStatsYesterday   =  GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$yesterday,$beforeyesterday); 

							$usersPercent = number_format(($getCurrentStatsToday[0][0]  - $getCurrentStatsYesterday[0][0]) / $getCurrentStatsYesterday[0][0] * 100, 2);						
						}

					}

				}else{
					$status = 0;
				}
				
			}
			$output = array(
				'traffic_growth'=>$traffic_growth,
				'total_sessions'=>$total_sessions,
				'current_session' =>$current_session,
				'prev_session'=>$prev_session,
				'total_users'=>$total_users,
				'current_users'=>$current_users,
				'prev_users'=>$prev_users,
				'total_pageview'=>$total_pageview,
				'current_pageView'=>$current_pageView,
				'prev_pageView'=>$prev_pageView,
				'from_datelabel'=>$from_dates,
				'combine_session' => $combine_session, 
				'compare_status' => $compare_status,
				'count_session' => $count_session, 
				'current_period'=>$current_period,
				'previous_period'=>$previous_period,
				'final_session' =>$final_Session,
				'final_users'=>$final_users,
				'final_pageView'=>$final_pageView,
				'status'=>$status
			);
			return $output;

		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	public function ajax_traffic_growth_date_range(Request $request){
		$range = $request['value'];
		$module = $request['module'];
		$request_id = $request['campaignId'];
		$today = date('Y-m-d');

		$getUser = SemrushUserAccount::where('id',$request_id)->first();
		$user_id = $getUser->user_id;
		
		
		if($range == 'week'){
			$start_date = date('Y-m-d',strtotime('-1 week'));
		} elseif($range == 'month'){
			$start_date = date('Y-m-d',strtotime('-1 month'));
		}elseif($range == 'three'){
			$start_date = date('Y-m-d',strtotime('-3 month'));
		}elseif($range == 'six'){
			$start_date = date('Y-m-d',strtotime('-6 month'));
		}elseif($range == 'nine'){
			$start_date = date('Y-m-d',strtotime('-9 month'));
		}elseif($range == 'year'){
			$start_date = date('Y-m-d',strtotime('-1 year'));
		}elseif($range == 'twoyear'){
			$start_date = date('Y-m-d',strtotime('-2 year'));
		}else{
			$start_date = date('Y-m-d',strtotime('-3 month'));
		}
		
		
		$current_period = $previous_period = $from_dates = $count_session = $combine_session = $total_sessions = $total_users=$total_pageview =$final_Session = $final_users = $final_pageView = $compare = $current_session =  '';
		
		
		
		$start_data = date('Y-m-d',strtotime($start_date));
		$end_data = date('Y-m-d',strtotime($today));
		
		$day_diff = strtotime($start_date) - strtotime($today);
		$count_days = floor($day_diff/(60*60*24));
		
		$new_start_date  =   date('Y-m-d', strtotime($start_date.' '.$count_days.' days'));
		
		$getUser = SemrushUserAccount::with('google_analytics_account')->where('user_id',$user_id)->where('id',$request_id)->first();
		if(!empty($getUser)){
			$getAnalytics  = GoogleAnalyticsUsers::accountInfoById($user_id,$getUser->google_account_id); 
			
			$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);
			$refresh_token  = $getAnalytics->google_refresh_token;

			/*if refresh token expires*/
			if ($client->isAccessTokenExpired()) {
				GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
			}

			$analyticsCategoryId = $getUser->google_analytics_account->category_id;

			$analytics = new \Google_Service_Analytics($client);
			$profile = GoogleAnalyticsUsers::getFirstProfileId($analytics,$analyticsCategoryId);

			$removeMinus = str_replace('-','',$count_days);
			if($removeMinus >= 90){
				$results = GoogleAnalyticsUsers::getResultByWeek($analytics, $profile,$end_data,$start_data);
				
				$output = array_column ($results->rows , 0);

				for($i=0;$i<count($output);$i++){
					$outputRes[] = ModuleByDateRange::getStartAndEndDate($output[$i],date('Y'));
				}
				
				$previous_data =  GoogleAnalyticsUsers::getResultByWeek($analytics, $profile,$start_data,$new_start_date);
			}else{
				$results = GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$end_data,$start_data);
				
				$outputRes = array_column ($results->rows , 0);
				
				$previous_data =  GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$start_data,$new_start_date);
			}
			
			
			
			$count_session = array_column ( $results->rows , 1);
			$from_dates  =  array_map(function($val) { return date("d M, Y", strtotime($val)); }, $outputRes);			
			$combine_session = array_column($previous_data->rows , 1);
			
			
			
			/*Comparison*/
			$currentData = GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$end_data,$start_data);
			$previousData = GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$start_data,$new_start_date);
			
			$current_period     =   date('d-m-Y', strtotime($start_data)).' to '.date('d-m-Y', strtotime($end_data));
			$previous_period     =   date('d-m-Y', strtotime($new_start_date)).' to '.date('d-m-Y', strtotime($start_data));
			
			
			/*sessions & traffic growth*/
			if(!empty($currentData[0][0]) && (!empty($previousData[0][0]))){
				$total_sessions = number_format(($currentData[0][0]  - $previousData[0][0]) / $previousData[0][0] * 100, 2).'%';
				$traffic_growth = number_format(($currentData[0][0]  - $previousData[0][0]) / $previousData[0][0] * 100, 2).'%'; 
			}else if(empty($currentData[0][0]) && !empty($previousData[0][0]) ) {
				$total_sessions	= ' -100%';
				$traffic_growth	= ' -100%';
			} else if(!empty($currentData[0][0]) && empty($previousData[0][0]) ) {
				$total_sessions	= ' 100%';
				$traffic_growth	= ' 100%';
			} else{
				$total_sessions	= ' N/A';
				$traffic_growth	= ' N/A';
			}
			
			if(!empty($currentData[0][0])){
				$current_session = $currentData[0][0];
			}else{
				$current_session = '0';
			}
			
			
			if(!empty($previousData[0][0])){
				$prev_session = $previousData[0][0];
			}else{
				$prev_session = '0';
			}
			
			
			$final_Session = $current_session. ' vs '.$prev_session;
			
			
			/*users*/
			if(!empty($currentData[0][1]) && !empty($previousData[0][1])) {
				$total_users = number_format(($currentData[0][1]  - $previousData[0][1]) / $previousData[0][1] * 100, 2).'%';

			} else if(empty($currentData[0][1]) && !empty($previousData[0][1]) ) {
				$total_users = '-100%';
			} else if(!empty($currentData[0][1]) && empty($previousData[0][1]) ) {
				$total_users = '100%';
			} else{
				$total_users = 'N/A';
			}
			
			
			if(!empty($currentData[0][1])){
				$current_users = $currentData[0][1];
			}else{
				$current_users = '0';
			}
			
			
			if(!empty($previousData[0][1])){
				$prev_users = $previousData[0][1];
			}else{
				$prev_users = '0';
			}
			$final_users = $current_users .' vs '.$prev_users;

			/*pageViews*/
			if(!empty($currentData[0][2]) && !empty($previousData[0][2])) {
				$total_pageview = number_format(($currentData[0][2]  - $previousData[0][2]) / $previousData[0][2] * 100, 2).'%';
			} else if(empty($currentData[0][2]) && !empty($previousData[0][2]) ) {
				$total_pageview = '-100%';
			} else if(!empty($currentData[0][2]) && empty($previousData[0][2]) ) {
				$total_pageview = '100%';
			} else{
				$total_pageview = 'N/A';
			}		

			if(!empty($currentData[0][2])){
				$current_pageView = $currentData[0][2];
			}else{
				$current_pageView = '0';
			}
			
			
			if(!empty($previousData[0][2])){
				$prev_pageView = $previousData[0][2];
			}else{
				$prev_pageView = '0';
			}
			
			$final_pageView= $current_pageView .' vs '.$prev_pageView;
			
			$compareResult = ProjectCompareGraph::where('request_id',$request_id)->first();
			if(!empty($compareResult)){
				$compare = $compareResult->compare_status;
			}
			
			$output = array(
				'current_period'=>$current_period,
				'previous_period'=>$previous_period,
				'from_datelabel'=>$from_dates,
				'from_dates'=>$from_dates,
				'count_session'=>$count_session,
				'combine_session'=>$combine_session,
				'total_sessions'=>$total_sessions,
				'traffic_growth'=>$traffic_growth,
				'compare_status'=>$compare,
				'final_session'=>$final_Session,
				'total_users'=>$total_users,
				'final_users'=>$final_users,
				'total_pageview'=>$total_pageview,
				'final_pageView'=>$final_pageView,
				'current_session'=>$current_session

			);
		}
		
		return $output;
	}

	public function ajax_googleSearchConsole(Request $request){
		$campaignId = $request['campaignId'];
		$result = array();
		$module = $request['module']?:'';
		try{
			$getUser = SemrushUserAccount::where('id',$campaignId)->first();	
			$user_id = $getUser->user_id;
			if(!empty($getUser)){
				$getAnalytics  = GoogleAnalyticsUsers::where('user_id',$user_id)->where('id',$getUser->google_console_id)->first();

				if(!empty($getAnalytics)){
					$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);

					$refresh_token  = $getAnalytics->google_refresh_token;

					/*if refresh token expires*/
					if ($client->isAccessTokenExpired()) {
						GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
					}

					$getAnalyticsId = SemrushUserAccount::with('google_search_account')->where('user_id',$user_id)->where('id',$campaignId)->first();



					if(isset($getAnalyticsId->google_search_account)){
						$analyticsCategoryId = $getAnalyticsId->google_search_account->category_id;

						$analytics = new \Google_Service_Analytics($client);
						$profileUrl = GoogleAnalyticsUsers::getProfileUrl($analytics, $analyticsCategoryId);

						$ifExists = ModuleByDateRange::where('request_id',$campaignId)->where('module','search_console')->first();


						$end_date = date('Y-m-d');
						if($request['value']){
							if($request['value'] == 'week'){
								$start_date = date('Y-m-d',strtotime('-1 week'));
							} elseif($request['value'] == 'month'){
								$start_date = date('Y-m-d',strtotime('-1 month'));
							}elseif($request['value'] == 'three'){
								$start_date = date('Y-m-d',strtotime('-3 month'));
							}elseif($request['value'] == 'six'){
								$start_date = date('Y-m-d',strtotime('-6 month'));
							}elseif($request['value'] == 'nine'){
								$start_date = date('Y-m-d',strtotime('-9 month'));
							}elseif($request['value'] == 'year'){
								$start_date = date('Y-m-d',strtotime('-1 year'));
							}elseif($request['value'] == 'twoyear'){
								$start_date = date('Y-m-d',strtotime('-2 year'));
							}else{
								$start_date = date('Y-m-d', strtotime("-3 months", strtotime(date('Y-m-d'))));
							}
						}else{
							if(!empty($ifExists)){
								$start_date = date('Y-m-d',strtotime($ifExists->start_date));
								$end_date = date('Y-m-d',strtotime($ifExists->end_date));
							}else{
								$start_date = date('Y-m-d', strtotime("-3 months", strtotime(date('Y-m-d'))));
							}
						}




						$search_console_query = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$start_date,$end_date);
						$search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$start_date,$end_date);
						$search_console_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$start_date,$end_date);
						$search_console_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$start_date,$end_date);
						$searchConsoleData = GoogleAnalyticsUsers::getSearchConsoleData($client,$profileUrl,$start_date,$end_date);



						$query_html = $device_html =  $page_html = $country_html = '';
						$clicks = $impressions = $dates = array();

						if(!empty($search_console_query)){
							foreach($search_console_query->getRows() as $query){
								$query_html	.='
								<tr>
								<td>'.$query->keys[0].'</td>
								<td>'.$query->clicks.'</td>
								<td>'.$query->impressions.'</td>
								</tr>';

							}
							$result['query']	=	$query_html;
						}

						if(!empty($search_console_device)){
							foreach($search_console_device->getRows() as $device){
								$device_html	.='
								<tr>
								<td>'.$device->keys[0].'</td>
								<td>'.$device->clicks.'</td>
								<td>'.$device->impressions.'</td>
								<td>'.$device->ctr.'</td>
								<td>'.$device->position.'</td>
								</tr>';
							}
							$result['device'] = $device_html;
						}

						if(!empty($search_console_page)){
							foreach($search_console_page->getRows() as $page){
								$page_html	.='
								<tr>
								<td>'.$page->keys[0].'</td>
								<td>'.$page->clicks.'</td>
								<td>'.$page->impressions.'</td>
								</tr>';
							}
							$result['page'] = $page_html;
						}

						if(!empty($search_console_country)){
							foreach($search_console_country->getRows() as $country){
								$country_html	.='
								<tr>
								<td>'.$country->keys[0].'</td>
								<td>'.$country->clicks.'</td>
								<td>'.$country->impressions.'</td>
								<td>'.$country->ctr.'</td>
								<td>'.$country->position.'</td>
								</tr>';
							}
							$result['country'] = $country_html;
						}


						if(!empty($searchConsoleData)){
							foreach($searchConsoleData->getRows() as $data){
								$clicks[]    = array('t'=>strtotime($data->keys[0])*1000, 'y'=>$data->clicks);
								$impressions[] = array('t'=>strtotime($data->keys[0])*1000, 'y'=>$data->impressions);
							}
						}

						$result['clicks'] = $clicks;
						$result['impressions'] = $impressions;
						$result['status']=1;


						$click = $result['clicks'][count($result['clicks']) - 1];
						$impression = $result['impressions'][count($result['impressions']) - 1];				

						return $result;
					}else{
						$result['message'] = 'Google Search Console Account not attached.';
						$result['status'] = 0;
						return $result;
					}

				}else{
					$result['message'] = 'Google Search Console Account not attached.';
					$result['status'] = 0;
					return $result;
				}
			}
		} catch (\Exception $e) {
			//return $e->getMessage();
		}

	}


	public function keywordsMetricBarChart(Request $request){
		$request_id = $request['campaignId'];
		$month = date('Y-m-d',strtotime(" -11 month"));
		
		$names = $value =array();
		for($i = 0; $i < 12; $i++){
			$data = SemrushOrganicMetric::
			where('request_id',$request_id)
			->whereMonth('created_at',date('m', strtotime($i ."month", strtotime($month))))
			->whereYear('created_at', date('Y', strtotime($i ."month", strtotime($month))))
			->orderBy('id','desc')
			->first();
			
			if($data){
				$value[] =  $data->total_count; 
				
				$names[]  =  date('M, y', strtotime( $i ."month", strtotime($month)));
			}
		}
		
		
		
		$valuesCount =  12 - count($value);
		if($valuesCount < 12){
			for ($i=1; $i <= $valuesCount; $i++) { 		
				$value[] =  0; 
				
				$names[]  =  date('M, y', strtotime($i ."month"));
			} 
		}
		
		return array('names' => json_encode($names), 'values' => json_encode($value));
	}


	public function keywordsMetricPieChart(Request $request){
		
		$request_id = $request['campaignId'];
		$result  = SemrushOrganicMetric::where('request_id',$request_id)->orderBy('id','desc')->first();
		if(isset($result) && !empty($result)){
			
			$chartarr = [
				(int) $result->pos_1,
				(int) $result->pos_2_3,
				(int) $result->pos_4_10,
				(int) $result->pos_11_20,
				(int) $result->pos_21_30,
				(int) $result->pos_31_40,
				(int) $result->pos_41_50,
				(int) $result->pos_51_60,
				(int) $result->pos_61_70,
				(int) $result->pos_71_80,
				(int) $result->pos_81_90,
				(int) $result->pos_91_100,
			];
			
			
			$chartarrname = [
				'1 : '.(int) $result->pos_1,	
				'2-3 : '. (int) $result->pos_2_3,
				'4-10 : '. (int) $result->pos_4_10,
				'11-20 : '. (int) $result->pos_11_20,
				'21-30 : '. (int) $result->pos_21_30,	
				'31-40 : '. (int) $result->pos_31_40,	
				'41-50 : '. (int) $result->pos_41_50,
				'51-60 : '. (int) $result->pos_51_60,	
				'61-70 : '. (int) $result->pos_61_70,
				'71-80 : '. (int) $result->pos_71_80,	
				'81-90 : '. (int) $result->pos_81_90,
				'91-100 : '. (int) $result->pos_91_100,	
			];
			$resultOld =  SemrushOrganicMetric::where('request_id',$request_id)->orderBy('id','desc')->offset(1)->limit(1)->first();
			if(!empty($resultOld)){
				$old = $resultOld->total_count;
			}else{
				$old =0;
			}
			
			return array('names' => json_encode($chartarrname), 'values' => json_encode($chartarr),'totalCount' => $result->total_count, 'totalCountOld' => $old);
		}
		
	}

	public function ajaxLiveKeywordTrackingData (Request $request){
		$base_url =  url('/');

		$request_id = $request['campaign_id'];
		$sortBy =	$dir = '';
		
		
		if($request['order']['0']['column'] == 0){
			$sortBy = 'keyword';
			$dir = $request['order']['0']['dir'];

		}elseif($request['order']['0']['column'] == 1){
			$sortBy = 'startPosition';
			$dir = $request['order']['0']['dir'];
			
		}elseif($request['order']['0']['column'] == 2){
			$sortBy = 'currentPosition';
			$dir = $request['order']['0']['dir'];
			
		}elseif($request['order']['0']['column'] == 3){
			$sortBy = 'oneDayPostion';
			$dir = $request['order']['0']['dir'];

		}elseif($request['order']['0']['column'] == 4){
			$sortBy = 'weekPostion';
			$dir = $request['order']['0']['dir'];

		}elseif($request['order']['0']['column'] == 5){
			$sortBy = 'monthPostion';
			$dir = $request['order']['0']['dir'];

		}elseif($request['order']['0']['column'] == 6){
			$sortBy = 'lifeTime';
			$dir = $request['order']['0']['dir'];

		}elseif($request['order']['0']['column'] == 7){
			$sortBy = 'cmp';
			$dir = $request['order']['0']['dir'];

		}elseif($request['order']['0']['column'] == 8){
			$sortBy = 'sv';
			$dir = $request['order']['0']['dir'];

		}else{
			$sortBy = 'currentPosition';
			$dir = 'asc';

		}

		

		
		$string = $request['search']["value"];
		$field = ['keyword','cmp','sv','start_ranking','one_week_ranking','monthly_ranking','life_ranking'];


		$searchData = KeywordSearch::select('*', 
			DB::raw('(CASE WHEN start_ranking > 0  OR start_ranking != null THEN start_ranking ELSE 150 END) AS startPosition')	,
			DB::raw('(CASE WHEN position > 0  OR position != null THEN position ELSE 150 END) AS currentPosition'),
			DB::raw('(CASE WHEN oneday_position <> 0  OR oneday_position != null THEN oneday_position ELSE 0 END) AS oneDayPostion'),
			DB::raw('(CASE WHEN one_week_ranking <> 0  OR one_week_ranking != null THEN one_week_ranking ELSE 0 END) AS weekPostion'),
			DB::raw('(CASE WHEN monthly_ranking <> 0  OR monthly_ranking != null THEN monthly_ranking ELSE 0 END) AS monthPostion'),
			DB::raw('(CASE WHEN life_ranking <> 0  OR life_ranking != null THEN life_ranking ELSE 0 END) AS lifeTime')
		)
		->where('request_id',$request_id)
		->where(function ($query) use($string, $field) {
			for ($i = 0; $i < count($field); $i++){
				$query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
			}      
		})
		->orderBy('is_favorite','desc')
		// ->orderBy('currentPosition','asc')
		->orderBy($sortBy,$dir)
		
		
		->skip($request->start)->take($request->length)
		->get();
		
		
		
		$data_array = array();
		foreach($searchData as $row){
			$flag = KeywordLocationList::flagsByCode($row->canonical);
			$flagValue = strtolower($flag->loc_country_iso_code);

			$flagData = $base_url.'/public/flags/'.$flagValue.'.png';

			$sub_array = array();

			$sub_array[] = '<img src='.$flagData.'><i class="pe-7s-map-marker" data-toggle="tooltip" title="'.$row->canonical.'" ></i>'.$row->keyword.'<a href="'.$row->result_se_check_url.'" target="_blank" style="float: right;"><i class="fa fa-search" aria-hidden="true"></i></a> <button type="button" class="chart-icon" style="float: right;" data-index="'.$row->request_id.'"  data-id="'.$row->id.'"><i class="fa fa-bars"></i></button>';

			$sub_array[] = $row->startPosition <> null && $row->startPosition < 100  ? '<span class="serpTd" data-id="'.$row->id.'" data-value="'.$row->startPosition.'" >'.$row->startPosition.'</span>' : '<span class="serpTd" data-id="'.$row->id.'" data-value="'.$row->startPosition.'" > <i class="fa fa-angle-right" aria-hidden="true"></i> 100</span>' ;
			$sub_array[] = $row->currentPosition <> null && $row->currentPosition < 100   ? $row->currentPosition : '<i class="fa fa-angle-right" aria-hidden="true"></i> 100';
			$sub_array[] = $row->oneDayPostion <> 0 ? $row->oneDayPostion > 0 ? $row->oneDayPostion.'<i class="fa fa-arrow-up"></i>' : trim($row->oneDayPostion,'-').'<i class="fa fa-arrow-down"></i>' : '-' ;
			$sub_array[] = $row->weekPostion <> 0 ? $row->weekPostion > 0 ? $row->weekPostion.'<i class="fa fa-arrow-up"></i>' : trim($row->weekPostion,'-').'<i class="fa fa-arrow-down"></i>' : '-' ;
			$sub_array[] = $row->monthPostion <> 0 ? $row->monthPostion > 0 ? $row->monthPostion.'<i class="fa fa-arrow-up"></i>' : trim($row->monthPostion,'-').'<i class="fa fa-arrow-down"></i>' : '-' ;
			$sub_array[] = $row->lifeTime <> 0 ? $row->lifeTime > 0 ? $row->lifeTime.'<i class="fa fa-arrow-up"></i>' : trim($row->lifeTime,'-').'<i class="fa fa-arrow-down"></i>' : '-' ;
			$sub_array[] = number_format((float)$row->cmp, 2, '.', '');
			$sub_array[] = $row->sv;
			$sub_array[] = date('d-M-Y', strtotime($row->created_at));


			$sub_array[] = '<a href="'.$row->result_url.'" target="_blank" title="'.$row->result_url.'">'. parse_url($row->result_url,PHP_URL_PATH).'</a>';
			$sub_array[] = $row->is_favorite;

			$data_array[] = $sub_array;

		}

		
		$dataCount = KeywordSearch::select('*')
		->where('request_id',$request_id)
		->where(function ($query) use($string, $field) {
			for ($i = 0; $i < count($field); $i++){
				$query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
			}      
		})
		->orderBy('is_favorite','desc')
		->orderBy($sortBy,$dir)
		->count();


		$output = array(
			"draw"              =>  intval($request->draw),
			"recordsTotal"      =>  $dataCount,
			"recordsFiltered"   =>  $dataCount,
			"data"              =>  $data_array
		);

		return response()->json($output);
		
	}


	public function ajax_live_keyword_chart(Request $request){

		$lastDate = date('Y-m-d', strtotime($request['duration']));
		
		$keywordSearch = KeywordSearch::select('id','keyword')->where('request_id',$request['request_id'])->where('id',$request['keyword_id'])->first();
		
		$keywordPosition = KeywordPosition::where('request_id',$request['request_id'])->where('keyword_id',$request['keyword_id'])->whereDate('created_at','<=',date('Y-m-d'))->whereDate('created_at','>=',$lastDate)->get();

		
		$highchart =  array();  
		$i= 0 ;
		
		foreach($keywordPosition as $record) {
			$key = date('M j', strtotime($record->created_at)); 
			$highchart[$key] =  (int) $record->position <> '0' && $record->position <> null ? (int) $record->position : null ; 
			$i++;
		}
		$data =  array('month'=> array_keys($highchart) ,  'rank' => array_values($highchart),'keyword' => $keywordSearch->keyword);

		return response()->json($data);
	}


	public function ajaxOrganicKeywords(Request $request) {
		$campaign_id = $request->campaign_id;
		
		
		if($request['order']['0']['column'] == 0){
			$sortBy = 'keywords';
			$dir = $request['order']['0']['dir'];

		}elseif($request['order']['0']['column'] == 1){
			$sortBy = 'position';
			$dir = $request['order']['0']['dir'];
			
		}elseif($request['order']['0']['column'] == 2){
			$sortBy = 'traffic';
			$dir = $request['order']['0']['dir'];
			
		}elseif($request['order']['0']['column'] == 3){
			$sortBy = 'cpc';
			$dir = $request['order']['0']['dir'];

		}elseif($request['order']['0']['column'] == 4){
			$sortBy = 'search_volume';
			$dir = $request['order']['0']['dir'];

		}else{
			$sortBy = 'position';
			$dir = 'asc';

		}
		
		
		
		$keywords = SemrushOrganicSearchData::
		where('request_id', $campaign_id)
		->where('keywords', 'LIKE',  '%' . $request['search']["value"] .'%') 
		->orderBy($sortBy,$dir)
		->skip($request->start)
		->take($request->length)
		->get();
		
		
		$data_array = array();
		if(!empty($keywords) && isset($keywords)){
			foreach($keywords as $value){
				$sub_array = array();
				$sub_array[] = $value->keywords;
				$sub_array[] = $value->position;
				$sub_array[] = number_format($value->traffic,2);
				$sub_array[] = number_format($value->cpc,2);
				$sub_array[] = $value->search_volume;
				$sub_array[] = '<a href="'.$value->url.'" target="_blank" title="'.$value->url.'">'. parse_url($value->url,PHP_URL_PATH).'</a>';
				
				$data_array[] = $sub_array;
			}
		}
		
		
		
		$dataCount = SemrushOrganicSearchData::select('*')
		->where('request_id',$campaign_id)
		->where('keywords', 'LIKE',  '%' . $request['search']["value"] .'%')
		->orderBy($sortBy,$dir)
		->count();


		$output = array(
			"draw"              =>  intval($request->draw),
			"recordsTotal"      =>  $dataCount,
			"recordsFiltered"   =>  $dataCount,
			"data"              =>  $data_array
		);

		return response()->json($output);
	}


	public function ajax_backlink_profile_data(Request $request){

		if(isset($request['order']) && !empty($request["order"])){
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
			}elseif($request['order']['0']['column'] == 6){
				$sortBy = 'created_at';
				$dir = 'asc';	 
			}

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
			$data[$key][] = '';
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


	public function ajax_google_analytics_goal_completion(Request $request){	
		$campaignId = $request['campaign_id'];
		$data = array();
		try{
			$getUser = SemrushUserAccount::where('id',$campaignId)->first();	
			$user_id = $getUser->user_id;
			
			if(!empty($getUser)){
				
				$getAnalytics  = GoogleAnalyticsUsers::accountInfoById($user_id,$getUser->google_account_id);
			

				if(!empty($getAnalytics)){
					$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);

					$refresh_token  = $getAnalytics->google_refresh_token;

					/*if refresh token expires*/
					if ($client->isAccessTokenExpired()) {
						GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
					}

					$getAnalyticsId = SemrushUserAccount::with('google_analytics_account')->where('user_id',$user_id)->first();


					if(isset($getAnalyticsId->google_analytics_account)){
						$analyticsCategoryId = $getAnalyticsId->google_analytics_account->category_id;

						$analytics = new \Google_Service_Analytics($client);


						$profile = GoogleAnalyticsUsers::getFirstProfileId($analytics,$analyticsCategoryId);

						$property_id = GoogleAnalyticsUsers::getFirstPropertyId($analytics);
						
						$start_date = date('Y-m-d');
						$end_date =  date('Y-m-d', strtotime("-3 months", strtotime(date('Y-m-d'))));


					// Get the results from the Core Reporting API and print the results.
						$current_data = GoogleAnalyticsUsers::getResults($analytics, $profile,$start_date,$end_date);


						$prev_start_date = date('Y-m-d', strtotime("-3 months", strtotime($end_date)));
						$prev_end_date =  date('Y-m-d', strtotime("-3 months", strtotime($prev_start_date)));

					// Get the results from the Core Reporting API and print the results for previous dates.
						$prev_data = GoogleAnalyticsUsers::getResults($analytics, $profile,$prev_start_date,$prev_end_date);

						$goalCompletionData = GoogleAnalyticsUsers::getGoalCompletion($analytics,$profile,$start_date,$end_date);



						if(!empty($goalCompletionData)){
							foreach($goalCompletionData->getRows() as $key=>$value){
								$data[$key][] = $value[0];
								$data[$key][] = $value[1];
								$data[$key][] = $value[2];
								$data[$key][] = number_format($value[3],2);
								$data[$key][] = number_format($value[4],2);
								$data[$key][] = number_format($value[5],2);
								$data[$key][] = number_format($value[6],2);
								$data[$key][] = $value[7];
								$data[$key][] = $value[8];
								
							}

							$output = array(
								"draw"              =>  intval($request->draw),
								"recordsTotal"      =>  count($goalCompletionData->getRows()),
								"recordsFiltered"   =>  count($goalCompletionData->getRows()),
								"data"              =>  $data
							);

							
						}else{
							$output = array(
								"draw"              =>  intval($request->draw),
								"recordsTotal"      =>  0,
								"recordsFiltered"   =>  0,
								"data"              =>  $data
							);

						}

						return response()->json($output);

					}else{
						return false;
					}
				}else{
					$data = array();
					$output = array(
								"draw"              =>  intval($request->draw),
								"recordsTotal"      =>  0,
								"recordsFiltered"   =>  0,
								"data"              =>  $data
							);

					return response()->json($output);
				}
			}
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}


	public function ajax_googleAnalyticsGoals(Request $request){
		$goalCompletion = GoogleGoalCompletion::
		where('request_id',$request['campaignId'])
		->where('user_id',$request['user_id'])
		->first();



		$goal = GoogleProfileData::
		select(DB::raw('SUM(goal_completions) AS total'))
		->where('request_id',$request['campaignId'])
		->where('user_id',$request['user_id'])
		->orderBy('id','asc')
		->first();


		if(!empty($goalCompletion->goal_count) && !empty($goal->total) ) {
			$goal_result	=	(($goal->total - $goalCompletion->goal_count) / $goalCompletion->goal_count * 100);
			$goal_result = number_format($goal_result,2,'.','')."%";
		} else if(empty($goal->total) && !empty($goalCompletion->goal_count) ) {
			$goal_result	= ' -100%';
		} else if(!empty($goal->total) && empty($goalCompletion->goal_count) ) {
			$goal_result	= ' 100%';
		} else{
			$goal_result	= 'N/A';
		}



		if(isset($goal->total) && !empty($goal)){
			$goal_total= $goal->total;
		}	else{
			$goal_total = 'N/A';
		}	
		return array('total'=>$goal_total,'goal_result'=>$goal_result);
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

	public function ajaxUpdateTimeAgo(Request $request){
		$result  = KeywordPosition::getLastUpdateKeyword($request->request_id);

		$time_span = KeywordPosition::calculate_time_span($result);
		if($result) {			
			$response['status'] = '1'; // Insert Data Done
			$response['error'] 	= '0';
			$response['time'] 	= "Last Updated: ".$time_span." (".date('M d Y',strtotime($result)).")" ;
		} else {
			$response['status'] = '2'; // Insert Data Done
			$response['error'] = '2';
			$response['message'] = 'Getting Error to update data';

		}
		return response()->json($response);
	}


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

	public function keywordSince(Request $request){
		$request_id = $request['campaignId'];
		$results = KeywordSearch::
		select(
			DB::raw('count(life_ranking) AS total'),
			DB::raw('sum(CASE WHEN life_ranking > 0 THEN 1 ELSE 0 END) AS lifetime'),
			DB::raw('sum(CASE WHEN position > 0 THEN 1 ELSE 0 END) AS hundred'),
			DB::raw('sum(CASE WHEN position <= 50 AND position > 0 THEN 1 ELSE 0 END) AS fifty'),
			DB::raw('sum(CASE WHEN position <= 30 AND position > 0 THEN 1 ELSE 0 END) AS thirty'),
			DB::raw('sum(CASE WHEN position <= 20 AND position > 0 THEN 1 ELSE 0 END) AS twenty'),
			DB::raw('sum(CASE WHEN position <= 10 AND position > 0 THEN 1 ELSE 0 END) AS ten'),
			DB::raw('sum(CASE WHEN position <= 3 AND position > 0 THEN 1 ELSE 0 END) AS three'),
			DB::raw('sum(CASE WHEN position > 0 AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_hundred'),
			DB::raw('sum(CASE WHEN (position <= 50 and position > 0) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_fifty'),
			DB::raw('sum(CASE WHEN (position <= 30 and position > 0) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_thirty'),
			DB::raw('sum(CASE WHEN (position <= 20 and position > 0) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_twenty'),
			DB::raw('sum(CASE WHEN (position <= 10 and position > 0) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_ten'),
			DB::raw('sum(CASE WHEN (position <= 3 and position > 0) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_three')
		)
		->where('request_id',$request_id)
		->first();


		$total = ($results->total)?:'0';
		$lifetime = ($results->lifetime)?:'0';
		$hundred = ($results->hundred)?:'0';
		$fifty = ($results->fifty)?:'0';
		$thirty = ($results->thirty)?:'0';
		$twenty = ($results->twenty)?:'0';
		$ten = ($results->ten)?:'0';
		$three = ($results->three)?:'0';
		$since_hundred = ($results->since_hundred)?:'0';
		$since_fifty = ($results->since_fifty)?:'0';
		$since_thirty = ($results->since_thirty)?:'0';
		$since_twenty = ($results->since_twenty)?:'0';
		$since_ten = ($results->since_ten)?:'0';
		$since_three = ($results->since_three)?:'0';
		$output = array('total'=>$total,'lifetime'=>$lifetime,'hundred'=>$hundred,'fifty'=>$fifty,'thirty'=>$thirty,'twenty'=>$twenty,'ten'=>$ten,'three'=>$three,'since_hundred'=>$since_hundred,'since_fifty'=>$since_fifty,'since_thirty'=>$since_thirty,'since_twenty'=>$since_twenty,'since_ten'=>$since_ten,'since_three'=>$since_three);
		
		return response()->json($output);
	}
}
