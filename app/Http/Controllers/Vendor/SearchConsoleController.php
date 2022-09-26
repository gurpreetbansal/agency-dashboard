<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Pagination\LengthAwarePaginator;
use App\SemrushUserAccount;
use App\GoogleAnalyticsUsers;
use App\ModuleByDateRange;
use App\User;
use Auth;
use Exception;
use App\SearchConsoleUsers;
use App\SearchConsoleUrl;
use App\Error;
use App\GoogleUpdate;


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

class SearchConsoleController extends Controller {

	public function search_console_graph (Request $request){
		try{
			$getUser = SemrushUserAccount::where('console_account_id','!=',NULL)->get();
			if(!empty($getUser)){
				$dates  = $converted_dates = $clicks = $impressions = array();
				$page_dates = $page_converted_dates = $page_key = $page_clicks = $page_impressions = array();
				$device_dates = $device_converted_dates = $device_key = $device_clicks = $device_impressions = $device_ctr = $device_positions = array();
				$country_dates = $country_converted_dates = $country_query = $country_clicks = $country_impressions = $country_ctr = $country_position = array();

				/*query variables*/
				$final_query = array();
				$month_query_keys = $month_query_clicks = $month_query_impressions = '';
				$three_query_keys = $three_query_clicks = $three_query_impressions = '';
				$six_query_keys = $six_query_clicks = $six_query_impressions = '';
				$nine_query_keys = $nine_query_clicks = $nine_query_impressions = '';
				$one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = '';
				$query_dates=$query_converted_dates=	$query_keys = $query_clicks = $query_impressions  = '';

				$nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = array();

				/*device variables*/
				$month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

				$three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
				$six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
				$nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
				$year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
				$two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
				$month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();

				foreach($getUser as $key=>$data){
					$getAnalytics  = GoogleAnalyticsUsers::where('id',$data->google_console_id)->first();
					$user_id = $data->user_id;
					$campaignId = $data->id;

					$role_id =User::get_user_role($user_id);

					if(!empty($getAnalytics)){
						$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);

						$refresh_token  = $getAnalytics->google_refresh_token;

						if ($client->isAccessTokenExpired()) {
							GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
						}

						$getAnalyticsId = SemrushUserAccount::with('google_search_account')->where('user_id',$user_id)->where('id',$campaignId)->first();


						if(isset($getAnalyticsId->google_search_account)){
							$analyticsCategoryId = $getAnalyticsId->google_search_account->category_id;
							$analytics = new \Google_Service_Analytics($client);
							$profileUrl = GoogleAnalyticsUsers::getDomainProfileUrl($campaignId);


							$end_date = date('Y-m-d');
							$start_date = date('Y-m-d', strtotime("-2 years", strtotime(date('Y-m-d'))));


							$one_month = date('Y-m-d',strtotime('-1 month'));
							$three_month = date('Y-m-d',strtotime('-3 month'));
							$six_month = date('Y-m-d',strtotime('-6 month'));
							$nine_month = date('Y-m-d',strtotime('-9 month'));
							$one_year = date('Y-m-d',strtotime('-1 year'));

							
							/*query data*/

							$search_console_query = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$start_date,$end_date);	

							$search_console_query_one = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$one_month,$end_date);	
							$search_console_query_three = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$three_month,$end_date);	
							$search_console_query_six = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$six_month,$end_date);	
							$search_console_query_nine = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$nine_month,$end_date);	
							$search_console_query_year = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$one_year,$end_date);	



							if(!empty($search_console_query_one)){
								foreach($search_console_query_one->getRows() as $month_query){

									$month_query_keys = $month_query->keys[0];
									$month_query_clicks = $month_query->clicks;
									$month_query_impressions = $month_query->impressions;

									$month_query_array[] = array(
										'month_query_keys'=>$month_query_keys,
										'month_query_clicks' =>$month_query_clicks,
										'month_query_impressions'=>$month_query_impressions
									);
								}

							}

							if(!empty($search_console_query_three)){
								foreach($search_console_query_three->getRows() as $three_query){
									$three_query_keys = $three_query->keys[0]	;
									$three_query_clicks = $three_query->clicks;
									$three_query_impressions = $three_query->impressions;

									$three_query_array[] = array(
										'three_query_keys'=>$three_query_keys,
										'three_query_clicks' =>$three_query_clicks,
										'three_query_impressions'=>$three_query_impressions
									);
								}
							}

							if(!empty($search_console_query_six)){
								foreach($search_console_query_six->getRows() as $six_query){
									$six_query_keys = $six_query->keys[0]	;
									$six_query_clicks = $six_query->clicks;
									$six_query_impressions = $six_query->impressions;


									$six_query_array[] = array(
										'six_query_keys'=>$six_query_keys,
										'six_query_clicks' =>$six_query_clicks,
										'six_query_impressions'=>$six_query_impressions
									);
								}
							}							

							if(!empty($search_console_query_nine)){
								foreach($search_console_query_nine->getRows() as $nine_query){
									$nine_query_keys = $nine_query->keys[0]	;
									$nine_query_clicks = $nine_query->clicks;
									$nine_query_impressions = $nine_query->impressions;

									$nine_query_array[] = array(
										'nine_query_keys'=>$nine_query_keys,
										'nine_query_clicks' =>$nine_query_clicks,
										'nine_query_impressions'=>$nine_query_impressions
									);
								}
							}

							if(!empty($search_console_query_year)){
								foreach($search_console_query_year->getRows() as $one_year_query){
									$one_year_query_keys = $one_year_query->keys[0]	;
									$one_year_query_clicks = $one_year_query->clicks;
									$one_year_query_impressions = $one_year_query->impressions;

									$one_year_query_array[] = array(
										'one_year_query_keys'=>$one_year_query_keys,
										'one_year_query_clicks' =>$one_year_query_clicks,
										'one_year_query_impressions'=>$one_year_query_impressions
									);
								}
							}

							if(!empty($search_console_query)){
								foreach($search_console_query->getRows() as $query_key=> $query){
									$query_keys = $query->keys[0]	;
									$query_clicks = $query->clicks;
									$query_impressions = $query->impressions;


									$query_array[] = array(
										'query_keys'=>$query_keys,
										'query_clicks' =>$query_clicks,
										'query_impressions'=>$query_impressions
									);
								}
							} 							

							$final_query = array(
								'month_query_array'=>$month_query_array,
								'three_query_array'=>$three_query_array,
								'six_query_array'=>$six_query_array,
								'nine_query_array'=>$nine_query_array,
								'one_year_query_array'=>$one_year_query_array,
								'two_year_query_array'=>$query_array
							);


							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$queryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json';
								if(date("Y-m-d", filemtime($queryfilename)) != date('Y-m-d')){
									file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json', print_r(json_encode($final_query,true),true));
								}
							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json', print_r(json_encode($final_query,true),true));
							}

							$month_query_keys = $month_query_clicks = $month_query_impressions = '';
							$three_query_keys = $three_query_clicks = $three_query_impressions = '';
							$six_query_keys = $six_query_clicks = $six_query_impressions = '';
							$nine_query_keys = $nine_query_clicks = $nine_query_impressions = '';
							$one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = '';
							$query_dates=$query_converted_dates=	$query_keys = $query_clicks = $query_impressions  = '';

							$nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = $final_query = array();

							/*query data*/


							/*device data*/
							// if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
							// 	$devicefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json';
							// 	if(date("Y-m-d", filemtime($devicefilename)) != date('Y-m-d')){

							$one_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$one_month,$end_date);
							$three_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$three_month,$end_date);
							$six_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$six_month,$end_date);
							$nine_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$nine_month,$end_date);
							$year_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$one_year,$end_date);
							$two_year_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$start_date,$end_date);


							if(!empty($one_search_console_device)){
								foreach($one_search_console_device->getRows() as $month_device){

									$month_device_keys = $month_device->keys[0];
									$month_device_clicks = $month_device->clicks;
									$month_device_impressions = $month_device->impressions;
									$month_device_ctr = $month_device->ctr;
									$month_device_position = $month_device->position;

									$month_device_array[] = array(
										'month_device_keys'=>$month_device_keys,
										'month_device_clicks' =>$month_device_clicks,
										'month_device_impressions'=>$month_device_impressions,
										'month_device_ctr'=>$month_device_ctr,
										'month_device_position'=>$month_device_position
									);
								}
							}


							if(!empty($three_search_console_device)){
								foreach($three_search_console_device->getRows() as $three_device){

									$three_device_keys = $three_device->keys[0];
									$three_device_clicks = $three_device->clicks;
									$three_device_impressions = $three_device->impressions;
									$three_device_ctr = $three_device->ctr;
									$three_device_position = $three_device->position;

									$three_device_array[] = array(
										'three_device_keys'=>$three_device_keys,
										'three_device_clicks' =>$three_device_clicks,
										'three_device_impressions'=>$three_device_impressions,
										'three_device_ctr'=>$three_device_ctr,
										'three_device_position'=>$three_device_position
									);
								}
							}


							if(!empty($six_search_console_device)){
								foreach($six_search_console_device->getRows() as $six_device){

									$six_device_keys = $six_device->keys[0];
									$six_device_clicks = $six_device->clicks;
									$six_device_impressions = $six_device->impressions;
									$six_device_ctr = $six_device->ctr;
									$six_device_position = $six_device->position;

									$six_device_array[] = array(
										'six_device_keys'=>$six_device_keys,
										'six_device_clicks' =>$six_device_clicks,
										'six_device_impressions'=>$six_device_impressions,
										'six_device_ctr'=>$six_device_ctr,
										'six_device_position'=>$six_device_position
									);
								}
							}


							if(!empty($nine_search_console_device)){
								foreach($nine_search_console_device->getRows() as $nine_device){

									$nine_device_keys = $nine_device->keys[0];
									$nine_device_clicks = $nine_device->clicks;
									$nine_device_impressions = $nine_device->impressions;
									$nine_device_ctr = $nine_device->ctr;
									$nine_device_position = $nine_device->position;

									$nine_device_array[] = array(
										'nine_device_keys'=>$nine_device_keys,
										'nine_device_clicks' =>$nine_device_clicks,
										'nine_device_impressions'=>$nine_device_impressions,
										'nine_device_ctr'=>$nine_device_ctr,
										'nine_device_position'=>$nine_device_position
									);
								}
							}


							if(!empty($year_console_device)){
								foreach($year_console_device->getRows() as $year_device){

									$year_device_keys = $year_device->keys[0];
									$year_device_clicks = $year_device->clicks;
									$year_device_impressions = $year_device->impressions;
									$year_device_ctr = $year_device->ctr;
									$year_device_position = $year_device->position;

									$year_device_array[] = array(
										'year_device_keys'=>$year_device_keys,
										'year_device_clicks' =>$year_device_clicks,
										'year_device_impressions'=>$year_device_impressions,
										'year_device_ctr'=>$year_device_ctr,
										'year_device_position'=>$year_device_position
									);
								}
							}


							if(!empty($two_year_search_console_device)){
								foreach($two_year_search_console_device->getRows() as $two_year_device){

									$two_year_device_keys = $two_year_device->keys[0];
									$two_year_device_clicks = $two_year_device->clicks;
									$two_year_device_impressions = $two_year_device->impressions;
									$two_year_device_ctr = $two_year_device->ctr;
									$two_year_device_position = $two_year_device->position;

									$two_year_device_array[] = array(
										'two_year_device_keys'=>$two_year_device_keys,
										'two_year_device_clicks' =>$two_year_device_clicks,
										'two_year_device_impressions'=>$two_year_device_impressions,
										'two_year_device_ctr'=>$two_year_device_ctr,
										'two_year_device_position'=>$two_year_device_position
									);
								}
							}

							$final_device = array(
								'month_device_array'=>$month_device_array,
								'three_device_array'=>$three_device_array,
								'six_device_array'=>$six_device_array,
								'nine_device_array'=>$nine_device_array,
								'year_device_array'=>$year_device_array,
								'two_year_device_array'=>$two_year_device_array
							);

							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$devicefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json';
								if(file_exists($devicefilename)){
									if(date("Y-m-d", filemtime($devicefilename)) != date('Y-m-d')){
										file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json', print_r(json_encode($final_device,true),true));
									}
								}else{
									file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json', print_r(json_encode($final_device,true),true));
								}

							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json', print_r(json_encode($final_device,true),true));
							}




							$month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

							$three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
							$six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
							$nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
							$year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
							$two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
							$month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();
							// 	}
							// }
							/*device data*/

							/*pages data*/
							// if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
							// 	$pagefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json';
							// 	if(date("Y-m-d", filemtime($pagefilename)) != date('Y-m-d')){

							$one_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$one_month,$end_date);
							$three_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$three_month,$end_date);
							$six_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$six_month,$end_date);
							$nine_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$nine_month,$end_date);
							$one_year_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$one_year,$end_date);
							$two_year_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$start_date,$end_date);




							if(!empty($one_month_page)){
								foreach($one_month_page->getRows() as $month_page){
									$month_page_keys = $month_page->keys[0];
									$month_page_clicks = $month_page->clicks;
									$month_page_impressions = $month_page->impressions;

									$month_page_array[] = array(
										'month_page_keys'=>$month_page_keys,
										'month_page_clicks' =>$month_page_clicks,
										'month_page_impressions'=>$month_page_impressions
									);
								}
							}

							if(!empty($three_month_page)){
								foreach($three_month_page->getRows() as $three_page){
									$three_page_keys = $three_page->keys[0];
									$three_page_clicks = $three_page->clicks;
									$three_page_impressions = $three_page->impressions;

									$three_page_array[] = array(
										'three_page_keys'=>$three_page_keys,
										'three_page_clicks' =>$three_page_clicks,
										'three_page_impressions'=>$three_page_impressions
									);
								}
							}

							if(!empty($six_month_page)){
								foreach($six_month_page->getRows() as $six_page){
									$six_page_keys = $six_page->keys[0];
									$six_page_clicks = $six_page->clicks;
									$six_page_impressions = $six_page->impressions;

									$six_page_array[] = array(
										'six_page_keys'=>$six_page_keys,
										'six_page_clicks' =>$six_page_clicks,
										'six_page_impressions'=>$six_page_impressions
									);
								}
							}

							if(!empty($nine_month_page)){
								foreach($nine_month_page->getRows() as $nine_page){
									$nine_page_keys = $nine_page->keys[0];
									$nine_page_clicks = $nine_page->clicks;
									$nine_page_impressions = $nine_page->impressions;

									$nine_page_array[] = array(
										'nine_page_keys'=>$nine_page_keys,
										'nine_page_clicks' =>$nine_page_clicks,
										'nine_page_impressions'=>$nine_page_impressions
									);
								}
							}

							if(!empty($one_year_page)){
								foreach($one_year_page->getRows() as $year_page){
									$year_page_keys = $year_page->keys[0];
									$year_page_clicks = $year_page->clicks;
									$year_page_impressions = $year_page->impressions;

									$year_page_array[] = array(
										'year_page_keys'=>$year_page_keys,
										'year_page_clicks' =>$year_page_clicks,
										'year_page_impressions'=>$year_page_impressions
									);
								}
							}

							if(!empty($two_year_page)){
								foreach($two_year_page->getRows() as $two_yearpage){
									$two_year_page_keys = $two_yearpage->keys[0];
									$two_year_page_clicks = $two_yearpage->clicks;
									$two_year_page_impressions = $two_yearpage->impressions;

									$two_year_page_array[] = array(
										'two_year_page_keys'=>$two_year_page_keys,
										'two_year_page_clicks' =>$two_year_page_clicks,
										'two_year_page_impressions'=>$two_year_page_impressions
									);
								}
							}

							$final_page = array(
								'month_page_array'=>$month_page_array,
								'three_page_array'=>$three_page_array,
								'six_page_array'=>$six_page_array,
								'nine_page_array'=>$nine_page_array,
								'year_page_array'=>$year_page_array,
								'two_year_page_array'=>$two_year_page_array
							);

							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$pagefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json';
								if(file_exists($pagefilename)){
									if(date("Y-m-d", filemtime($pagefilename)) != date('Y-m-d')){
										file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json', print_r(json_encode($final_page,true),true));
									}
								}else{
									file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json', print_r(json_encode($final_page,true),true));
								}
							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json', print_r(json_encode($final_page,true),true));
							}


							$month_page_keys = $month_page_clicks = $month_page_impressions = '';
							$three_page_keys = $three_page_clicks = $three_page_impressions = '';
							$six_page_keys = $six_page_clicks = $six_page_impressions = '';
							$nine_page_keys = $nine_page_clicks = $nine_page_impressions = '';
							$year_page_keys = $year_page_clicks = $year_page_impressions = '';
							$two_year_page_keys = $two_year_page_clicks = $two_year_page_impressions = '';
							$month_page_array = $three_page_array = $six_page_array = $nine_page_array =  $year_page_array = $two_year_page_array = $final_page = array();
							// 	}
							// }
							/*pages data*/

							/*country data*/
							// if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
							// 	$countryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json';
							// 	if(date("Y-m-d", filemtime($countryfilename)) != date('Y-m-d')){
							$month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$one_month,$end_date);
							$three_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$three_month,$end_date);
							$six_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$six_month,$end_date);
							$nine_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$nine_month,$end_date);
							$one_year_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$one_year,$end_date);
							$two_year_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$start_date,$end_date);


							if(!empty($month_country)){
								foreach($month_country->getRows() as $monthCountry){

									$month_country_keys = $monthCountry->keys[0];
									$month_country_clicks = $monthCountry->clicks;
									$month_country_impressions = $monthCountry->impressions;
									$month_country_ctr = $monthCountry->ctr;
									$month_country_position = $monthCountry->position;

									$month_country_array[] = array(
										'month_country_keys'=>$month_country_keys,
										'month_country_clicks' =>$month_country_clicks,
										'month_country_impressions'=>$month_country_impressions,
										'month_country_ctr'=>$month_country_ctr,
										'month_country_position'=>$month_country_position
									);
								}
							}

							if(!empty($three_month_country)){
								foreach($three_month_country->getRows() as $threeCountry){

									$threeCountry_keys = $threeCountry->keys[0];
									$threeCountry_clicks = $threeCountry->clicks;
									$threeCountry_impressions = $threeCountry->impressions;
									$threeCountry_ctr = $threeCountry->ctr;
									$threeCountry_position = $threeCountry->position;

									$three_country_array[] = array(
										'threeCountry_keys'=>$threeCountry_keys,
										'threeCountry_clicks' =>$threeCountry_clicks,
										'threeCountry_impressions'=>$threeCountry_impressions,
										'threeCountry_ctr'=>$threeCountry_ctr,
										'threeCountry_position'=>$threeCountry_position
									);
								}

							}

							if(!empty($six_month_country)){
								foreach($six_month_country->getRows() as $six_month_Country){

									$six_month_Country_keys = $six_month_Country->keys[0];
									$six_month_Country_clicks = $six_month_Country->clicks;
									$six_month_Country_impressions = $six_month_Country->impressions;
									$six_month_Country_ctr = $six_month_Country->ctr;
									$six_month_Country_position = $six_month_Country->position;

									$six_country_array[] = array(
										'six_month_Country_keys'=>$six_month_Country_keys,
										'six_month_Country_clicks' =>$six_month_Country_clicks,
										'six_month_Country_impressions'=>$six_month_Country_impressions,
										'six_month_Country_ctr'=>$six_month_Country_ctr,
										'six_month_Country_position'=>$six_month_Country_position
									);
								}

							}

							if(!empty($nine_month_country)){
								foreach($nine_month_country->getRows() as $nine_month_Country){

									$nine_month_Country_keys = $nine_month_Country->keys[0];
									$nine_month_Country_clicks = $nine_month_Country->clicks;
									$nine_month_Country_impressions = $nine_month_Country->impressions;
									$nine_month_Country_ctr = $nine_month_Country->ctr;
									$nine_month_Country_position = $nine_month_Country->position;

									$nine_country_array[] = array(
										'nine_month_Country_keys'=>$nine_month_Country_keys,
										'nine_month_Country_clicks' =>$nine_month_Country_clicks,
										'nine_month_Country_impressions'=>$nine_month_Country_impressions,
										'nine_month_Country_ctr'=>$nine_month_Country_ctr,
										'nine_month_Country_position'=>$nine_month_Country_position
									);
								}

							}

							if(!empty($one_year_country)){
								foreach($one_year_country->getRows() as $year_Country){

									$year_Country_keys = $year_Country->keys[0];
									$year_Country_clicks = $year_Country->clicks;
									$year_Country_impressions = $year_Country->impressions;
									$year_Country_ctr = $year_Country->ctr;
									$year_Country_position = $year_Country->position;

									$year_country_array[] = array(
										'year_Country_keys'=>$year_Country_keys,
										'year_Country_clicks' =>$year_Country_clicks,
										'year_Country_impressions'=>$year_Country_impressions,
										'year_Country_ctr'=>$year_Country_ctr,
										'year_Country_position'=>$year_Country_position
									);
								}

							}


							if(!empty($two_year_country)){
								foreach($two_year_country->getRows() as $two_year_Country){

									$two_year_Country_keys = $two_year_Country->keys[0];
									$two_year_Country_clicks = $two_year_Country->clicks;
									$two_year_Country_impressions = $two_year_Country->impressions;
									$two_year_Country_ctr = $two_year_Country->ctr;
									$two_year_Country_position = $two_year_Country->position;

									$two_year_country_array[] = array(
										'two_year_Country_keys'=>$two_year_Country_keys,
										'two_year_Country_clicks' =>$two_year_Country_clicks,
										'two_year_Country_impressions'=>$two_year_Country_impressions,
										'two_year_Country_ctr'=>$two_year_Country_ctr,
										'two_year_Country_position'=>$two_year_Country_position
									);
								}

							}

							$final_country = array(
								'month_country_array'=>$month_country_array,
								'three_country_array'=>$three_country_array,
								'six_country_array'=>$six_country_array,
								'nine_country_array'=>$nine_country_array,
								'year_country_array'=>$year_country_array,
								'two_year_country_array'=>$two_year_country_array
							);


							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$countryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json';
								if(file_exists($countryfilename)){
									if(date("Y-m-d", filemtime($countryfilename)) != date('Y-m-d')){
										file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json', print_r(json_encode($final_country,true),true));
									}
								}else{
									file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json', print_r(json_encode($final_country,true),true));
								}
							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json', print_r(json_encode($final_country,true),true));

							}

							$month_country_keys = $month_country_clicks = $month_country_impressions = $month_country_ctr = $month_country_position =  '';
							$threeCountry_keys = $threeCountry_clicks = $threeCountry_impressions = $threeCountry_ctr = $threeCountry_position =  '';
							$six_month_Country_keys = $six_month_Country_clicks = $six_month_Country_impressions = $six_month_Country_ctr = $six_month_Country_position =  '';
							$nine_month_Country_keys = $nine_month_Country_clicks = $nine_month_Country_impressions = $nine_month_Country_ctr = $nine_month_Country_position =  '';
							$year_Country_keys = $year_Country_clicks = $year_Country_impressions = $year_Country_ctr = $year_Country_position =  '';
							$two_year_Country_keys = $two_year_Country_clicks = $two_year_Country_impressions = $two_year_Country_ctr = $two_year_Country_position =  '';


							$month_country_array = $three_country_array = $six_country_array = $nine_country_array = $year_country_array = $final_country = $two_year_country_array =  array();
							// 	}
							// }
							/*country data*/

							/*graph data*/
							// if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
							// 	$graphfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json';
							// 	if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){

							$searchConsoleData = GoogleAnalyticsUsers::getSearchConsoleData($client,$profileUrl,$start_date,$end_date);
							if(!empty($searchConsoleData)){
								foreach($searchConsoleData->getRows() as $data_key=>$data){
									$dates[] = $data->keys[0];
									$converted_dates[] = strtotime($data->keys[0])*1000;
									$clicks[]    = array('t'=>strtotime($data->keys[0])*1000,'y'=>$data->clicks);
									$impressions[] = array('t'=>strtotime($data->keys[0])*1000,'y'=>$data->impressions);
								}

							}


							$data_array = array(
								'dates'=>$dates,
								'converted_dates'=>$converted_dates,
								'clicks' =>$clicks,
								'impressions'=>$impressions
							);

							if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								$graphfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json';
								if(file_exists($graphfilename)){
									if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){
										file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json', print_r(json_encode($data_array,true),true));
									}
								}else{
									file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json', print_r(json_encode($data_array,true),true));
								}
							}
							elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
								mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
								file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json', print_r(json_encode($data_array,true),true));
							}
							$dates = $converted_dates = $clicks = $impressions = array();
							// 	}
							// }
							/*graph data*/

						}					
					}
				}				
			}
		}catch(\Exception $e){
			return $e->getMessage();
		}
	}


	// public function search_console_cron(Request $request){
	// 	try{
	// 		$getUser = SemrushUserAccount::where('console_account_id','!=',NULL)->get();

	// 		if(!empty($getUser)){

	// 			/*query variables*/
	// 			$final_query = array();
	// 			$month_query_keys = $month_query_clicks = $month_query_impressions = '';
	// 			$three_query_keys = $three_query_clicks = $three_query_impressions = '';
	// 			$six_query_keys = $six_query_clicks = $six_query_impressions = '';
	// 			$nine_query_keys = $nine_query_clicks = $nine_query_impressions = '';
	// 			$one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = '';
	// 			$query_dates=$query_converted_dates=	$query_keys = $query_clicks = $query_impressions  = '';

	// 			$nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = array();

	// 			/*device variables*/
	// 			$month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

	// 			$three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
	// 			$six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
	// 			$nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
	// 			$year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
	// 			$two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
	// 			$month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();

	// 			foreach($getUser as $key=>$data){
	// 				$getAnalytics  = SearchConsoleUsers::where('id',$data->google_console_id)->first();

	// 				$user_id = $data->user_id;
	// 				$campaignId = $data->id;

	// 				$role_id =User::get_user_role($user_id);

	// 				if(!empty($getAnalytics)){
	// 					$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);

	// 					$refresh_token  = $getAnalytics->google_refresh_token;

	// 					if ($client->isAccessTokenExpired()) {
	// 						GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
	// 					}

	// 					$getAnalyticsId = SearchConsoleUrl::where('id',$data->console_account_id)->first();
	// 					$analytics = new \Google_Service_Analytics($client);

	// 					$profileUrl = $getAnalyticsId->siteUrl;

	// 					$end_date = date('Y-m-d');
	// 					$start_date = date('Y-m-d', strtotime("-2 years", strtotime(date('Y-m-d'))));


	// 					$one_month = date('Y-m-d',strtotime('-1 month'));
	// 					$three_month = date('Y-m-d',strtotime('-3 month'));
	// 					$six_month = date('Y-m-d',strtotime('-6 month'));
	// 					$nine_month = date('Y-m-d',strtotime('-9 month'));
	// 					$one_year = date('Y-m-d',strtotime('-1 year'));



	// 					/*query data*/
	// 					if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 						$queryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json';
	// 						if(file_exists($queryfilename)){

	// 							if(date("Y-m-d", filemtime($queryfilename)) != date('Y-m-d')){
	// 								$this->search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
	// 							}
	// 						}else{

	// 							$this->search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
	// 						}

	// 					}
	// 					elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 						mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
	// 						$this->search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
	// 					}


	// 					query data


	// 					/*device data*/
	// 					if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 						$devicefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json';
	// 						if(file_exists($devicefilename)){
	// 							if(date("Y-m-d", filemtime($devicefilename)) != date('Y-m-d')){
	// 								$this->search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
	// 							}
	// 						}else{
	// 							$this->search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
	// 						}

	// 					}
	// 					elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 						mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
	// 						$this->search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
	// 					}						
	// 					/*device data*/

	// 					/*pages data*/
	// 					if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 						$pagefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json';
	// 						if(file_exists($pagefilename)){
	// 							if(date("Y-m-d", filemtime($pagefilename)) != date('Y-m-d')){
	// 								$this->search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
	// 							}
	// 						}else{
	// 							$this->search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
	// 						}

	// 					}
	// 					elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 						mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
	// 						$this->search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
	// 					}

	// 					/*pages data*/

	// 					/*country data*/
	// 					if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 						$countryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json';
	// 						if(file_exists($countryfilename)){
	// 							if(date("Y-m-d", filemtime($countryfilename)) != date('Y-m-d')){
	// 								$this->search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
	// 							}
	// 						}else{
	// 							$this->search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
	// 						}

	// 					}
	// 					elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 						mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
	// 						$this->search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
	// 					}

	// 					/*country data*/

	// 					/*graph data*/

	// 					if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 						$graphfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json';
	// 						if(file_exists($graphfilename)){
	// 							if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){
	// 								$this->search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId);
	// 							}
	// 						}else{
	// 							$this->search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId);
	// 						}

	// 					}
	// 					elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 						mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
	// 						$this->search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId);
	// 					}


	// 					/*graph data*/

	// 				}					
	// 				//}
	// 			}				
	// 		}
	// 	}catch(\Exception $e){
	// 		return $e->getMessage();
	// 	}
	// }


	public function ajax_get_search_console_graph(Request $request){

		$campaignId = $request['campaignId'];

		if (!file_exists(env('FILE_PATH')."public/search_console/".$campaignId)) {
			$res['status'] = 0;
		} else {
			$url = env('FILE_PATH')."public/search_console/".$campaignId.'/graph.json'; 
			$data = file_get_contents($url);

			$final = json_decode($data);
			$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'search_console');
			if(!empty($sessionHistoryRange)){
				$duration = $sessionHistoryRange->duration;


				if($duration == 1){
					$start_date = date('Y-m-d',strtotime('-1 month'));
				}elseif($duration == 3){
					$start_date = date('Y-m-d',strtotime('-3 month'));
				}elseif($duration == 6){
					$start_date = date('Y-m-d',strtotime('-6 month'));
				}elseif($duration == 9){
					$start_date = date('Y-m-d',strtotime('-9 month'));
				}elseif($duration == 12){
					$start_date = date('Y-m-d',strtotime('-1 year'));
				}elseif($duration == 24){
					$start_date = date('Y-m-d',strtotime('-2 year'));
				}
			}else{
				$start_date = date('Y-m-d',strtotime('-3 month'));
			}

			$end = end($final->dates); 

			// if(in_array($start_date,$final->dates)){
			// 	$get_index = array_search($start_date,$final->dates);
			// 	$get_index_today = array_search($end,$final->dates);
			// }else{
			// 	$get_index = 0;
			// 	$get_index_today = array_search($end,$final->dates);
			// }


			if(in_array($start_date,$final->dates)){
				$get_index = array_search($start_date,$final->dates);
			}elseif(!in_array($start_date,$final->dates)){
				if(!empty($sessionHistoryRange)){
					$duration = $sessionHistoryRange->duration;

					if($duration == 1){
						$start_date = date('Y-m-d',strtotime('-1 month',strtotime($end)));
					}elseif($duration == 3){
						$start_date = date('Y-m-d',strtotime('-3 month',strtotime($end)));
					}elseif($duration == 6){
						$start_date = date('Y-m-d',strtotime('-6 month',strtotime($end)));
					}elseif($duration == 9){
						$start_date = date('Y-m-d',strtotime('-9 month',strtotime($end)));
					}elseif($duration == 12){
						$start_date = date('Y-m-d',strtotime('-1 year',strtotime($end)));
					}elseif($duration == 24){
						$start_date = date('Y-m-d',strtotime('-2 year',strtotime($end)));
					}
				}else{
					$start_date = date('Y-m-d',strtotime('-3 month',strtotime($end)));
				}

				$get_index = array_search($start_date,$final->dates);
				if($get_index == false){
					$get_index = 0;
				}
			}else{
				$get_index = 0;
			}

			$get_index_today = array_search($end,$final->dates);
			$current = $current_data = $current_prev = $prev_data = array();

			for($i=$get_index;$i<=$get_index_today;$i++){
				$dates[] = $final->dates[$i];
				$clicks[] = array('t'=>$final->converted_dates[$i],'y'=>$final->clicks[$i]);
				$impressions[] = array('t'=>$final->converted_dates[$i],'y'=>$final->impressions[$i]);
			} 

			

			$res['from_datelabel'] = $dates;
			$res['clicks'] = $clicks;
			$res['impressions'] = $impressions;
			$res['status'] = 1;
		}

		return response()->json($res);
	}


	public function ajax_get_search_console_graph_date_range(Request $request){


		$module = $request['module'];
		$campaignId = $request['campaignId'];

		if(Auth::user() <> null){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$role_id = User::get_user_role(Auth::user()->id);
		}else{
			$getUser = SemrushUserAccount::where('id',$campaignId)->first();
			$user_id = $getUser->user_id;
			$role_id = User::get_user_role($getUser->user_id);
		}	


		$state = ($request->has('key'))?'viewkey':'user';

		if (!file_exists(env('FILE_PATH')."public/search_console/".$campaignId)) {
			$res['status'] = 0;
		} else {

			$url = env('FILE_PATH')."public/search_console/".$campaignId.'/graph.json'; 
			$data = file_get_contents($url);

			$final = json_decode($data);

			if($request['value'] == 'week'){
				$start_date = date('Y-m-d',strtotime('-1 week'));
				$duration = 1;
			} elseif($request['value'] == 'month'){
				$start_date = date('Y-m-d',strtotime('-1 month'));
				$duration = 1;
			}elseif($request['value'] == 'three'){
				$start_date = date('Y-m-d',strtotime('-3 month'));
				$duration = 3;
			}elseif($request['value'] == 'six'){
				$start_date = date('Y-m-d',strtotime('-6 month'));
				$duration = 6;
			}elseif($request['value'] == 'nine'){
				$start_date = date('Y-m-d',strtotime('-9 month'));
				$duration = 9;
			}elseif($request['value'] == 'year'){
				$start_date = date('Y-m-d',strtotime('-1 year'));
				$duration = 12;
			}elseif($request['value'] == 'twoyear'){
				$start_date = date('Y-m-d',strtotime('-2 year'));
				$duration = 24;
			}else{
				$start_date = date('Y-m-d', strtotime("-3 month"));
				$duration = 3;
			}
			$end = end($final->dates); 


			if(in_array($start_date,$final->dates)){
				$get_index = array_search($start_date,$final->dates);
			}elseif(!in_array($start_date,$final->dates)){
				if($request['value'] == 'month'){
					$start_date = date('Y-m-d',strtotime('-1 month',strtotime($end)));
				}elseif($request['value'] == 'three'){
					$start_date = date('Y-m-d',strtotime('-3 month',strtotime($end)));
				}elseif($request['value'] == 'six'){
					$start_date = date('Y-m-d',strtotime('-6 month',strtotime($end)));
				}elseif($request['value'] == 'nine'){
					$start_date = date('Y-m-d',strtotime('-9 month',strtotime($end)));
				}elseif($request['value'] == 'year'){
					$start_date = date('Y-m-d',strtotime('-1 year',strtotime($end)));
				}elseif($request['value'] == 'twoyear'){
					$start_date = date('Y-m-d',strtotime('-2 year',strtotime($end)));
				}

				$get_index = array_search($start_date,$final->dates);
				if($get_index == false){
					$get_index = 0;
				}
			}else{
				$get_index = 0;
			}

			$get_index_today = array_search($end,$final->dates);

			if($role_id != 4 && $state == 'user'){
				$ifCheck = ModuleByDateRange::where('request_id',$campaignId)->where('module',$module)->first();
				if(empty($ifCheck)){
					ModuleByDateRange::create([
						'user_id'=>$user_id,
						'request_id'=>$campaignId,
						'duration'=>$duration,
						'module'=>$module,
						'start_date'=>date('Y-m-d', strtotime($start_date)),
						'end_date'=>date('Y-m-d', strtotime($end))
					]);
				}else{
					ModuleByDateRange::where('id',$ifCheck->id)->update([
						'user_id'=>$user_id,
						'request_id'=>$campaignId,
						'duration'=>$duration,
						'module'=>$module,
						'start_date'=>date('Y-m-d', strtotime($start_date)),
						'end_date'=>date('Y-m-d', strtotime($end))
					]);
				}

			}

			$current = $current_data = $current_prev = $prev_data = array();


			for($i=$get_index;$i<=$get_index_today;$i++){
				$dates[] = $final->dates[$i];
				$clicks[] = array('t'=>$final->converted_dates[$i],'y'=>$final->clicks[$i]);
				$impressions[] = array('t'=>$final->converted_dates[$i],'y'=>$final->impressions[$i]);
			} 

			$res['from_datelabel'] = $dates;
			$res['clicks'] = $clicks;
			$res['impressions'] = $impressions;
			$res['status'] = 1;


		}
		return response()->json($res);
	}


	public function ajax_get_search_console_queries(Request $request){

		$campaignId = $request['campaignId'];
		if (!file_exists(env('FILE_PATH')."public/search_console/".$campaignId)) {
			$res['status'] = 0;
		} else {
			$result  = array();
			$range = 'three';
			if(isset($request['value']) && $request['value'] !=null){
				$range = $request['value'];
			}else{
				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'search_console');
				if(!empty($sessionHistoryRange)){
				// $selected = DashboardController::getSelectedDateForCharts($sessionHistoryRange->start_date,$sessionHistoryRange->end_date);
					$selected = $sessionHistoryRange->duration;

					if($selected == 1){
						$range = 'month';
					}elseif($selected == 3){
						$range = 'three';
					}elseif($selected == 6){
						$range = 'six';
					}elseif($selected == 9){
						$range = 'nine';
					}elseif($selected == 12){
						$range = 'year';
					}elseif($selected == 24){
						$range = 'twoyear';
					}
				}else{
					$range = 'three';
				}
			}



			$url = env('FILE_PATH')."public/search_console/".$campaignId.'/query.json'; 
			$data = file_get_contents($url);

			$final = json_decode($data);

			$query_html = $device_html =  $page_html = $country_html = '';

			if($range == 'month'){
				if(count($final->month_query_array) > 0){
					foreach($final->month_query_array as $query_data){
						$query_html	.='
						<tr>
						<td>'.$query_data->month_query_keys.'</td>
						<td>'.$query_data->month_query_clicks.'</td>
						<td>'.$query_data->month_query_impressions.'</td>
						<td>'.number_format($query_data->month_query_ctr,2).'</td>
						<td>'.number_format($query_data->month_query_position,2).'</td>
						</tr>';				
					}

					$result['query']	=	$query_html;
				}
				else{
					$result['query']	=	'<tr><td colspan="5"><center>No query found</center></td></tr>';
				}
			}


			if($range == 'three'){
				if(count($final->three_query_array) > 0){
					foreach($final->three_query_array as $query_data){
						$query_html	.='
						<tr>
						<td>'.$query_data->three_query_keys.'</td>
						<td>'.$query_data->three_query_clicks.'</td>
						<td>'.$query_data->three_query_impressions.'</td>
						<td>'.number_format($query_data->three_query_ctr,2).'</td>
						<td>'.number_format($query_data->three_query_position,2).'</td>
						</tr>';				
					}

					$result['query']	=	$query_html;
				}
				else{
					$result['query']	=	'<tr><td colspan="5"><center>No query found</center></td></tr>';
				}
			}

			if($range == 'six'){
				if(count($final->six_query_array) > 0){
					foreach($final->six_query_array as $query_data){
						$query_html	.='
						<tr>
						<td>'.$query_data->six_query_keys.'</td>
						<td>'.$query_data->six_query_clicks.'</td>
						<td>'.$query_data->six_query_impressions.'</td>
						<td>'.number_format($query_data->six_query_ctr,2).'</td>
						<td>'.number_format($query_data->six_query_position,2).'</td>
						</tr>';				
					}

					$result['query']	=	$query_html;
				}
				else{
					$result['query']	=	'<tr><td colspan="5"><center>No query found</center></td></tr>';
				}
			}

			if($range == 'nine'){
				if(count($final->nine_query_array) > 0){
					foreach($final->nine_query_array as $query_data){
						$query_html	.='
						<tr>
						<td>'.$query_data->nine_query_keys.'</td>
						<td>'.$query_data->nine_query_clicks.'</td>
						<td>'.$query_data->nine_query_impressions.'</td>
						<td>'.number_format($query_data->nine_query_ctr,2).'</td>
						<td>'.number_format($query_data->nine_query_position,2).'</td>
						</tr>';				
					}

					$result['query']	=	$query_html;
				}
				else{
					$result['query']	=	'<tr><td colspan="5"><center>No query found</center></td></tr>';
				}
			}


			if($range == 'year'){
				if(count($final->one_year_query_array) > 0){
					foreach($final->one_year_query_array as $query_data){
						$query_html	.='
						<tr>
						<td>'.$query_data->one_year_query_keys.'</td>
						<td>'.$query_data->one_year_query_clicks.'</td>
						<td>'.$query_data->one_year_query_impressions.'</td>
						<td>'.number_format($query_data->one_year_query_ctr,2).'</td>
						<td>'.number_format($query_data->one_year_query_position,2).'</td>
						</tr>';				
					}

					$result['query']	=	$query_html;
				}
				else{
					$result['query']	=	'<tr><td colspan="5"><center>No query found</center></td></tr>';
				}
			}

			if($range == 'twoyear'){
				if(count($final->two_year_query_array) > 0){
					foreach($final->two_year_query_array as $query_data){
						$query_html	.='
						<tr>
						<td>'.$query_data->query_keys.'</td>
						<td>'.$query_data->query_clicks.'</td>
						<td>'.$query_data->query_impressions.'</td>
						<td>'.number_format($query_data->query_ctr,2).'</td>
						<td>'.number_format($query_data->query_position,2).'</td>
						</tr>';				
					}

					$result['query']	=	$query_html;
				}
				else{
					$result['query']	=	'<tr><td colspan="5"><center>No query found</center></td></tr>';
				}
			}



			return response()->json($result);

		}

	}


	public function ajax_get_search_console_devices(Request $request){
		$campaignId = $request['campaignId'];
		if (!file_exists(env('FILE_PATH')."public/search_console/".$campaignId)) {
			$res['status'] = 0;
		} else {
			$result  = array();
			$range = 'three';
			if(isset($request['value'])  && $request['value'] !=null){
				$range = $request['value'];
			}else{
				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'search_console');
				if(!empty($sessionHistoryRange)){
				// $selected = DashboardController::getSelectedDateForCharts($sessionHistoryRange->start_date,$sessionHistoryRange->end_date);
					$selected = $sessionHistoryRange->duration;
					if($selected == 1){
						$range = 'month';
					}elseif($selected == 3){
						$range = 'three';
					}elseif($selected == 6){
						$range = 'six';
					}elseif($selected == 9){
						$range = 'nine';
					}elseif($selected == 12){
						$range = 'year';
					}elseif($selected == 24){
						$range = 'twoyear';
					}
				}else{
					$range = 'three';
				}
			}


			$url = env('FILE_PATH')."public/search_console/".$campaignId.'/device.json'; 
			$data = file_get_contents($url);

			$final = json_decode($data);


			$device_html =  '';

			if($range == 'month'){
				if(count($final->month_device_array) > 0){
					foreach($final->month_device_array as $device_data){
						$device_html	.='
						<tr>
						<td>'.$device_data->month_device_keys.'</td>
						<td>'.$device_data->month_device_clicks.'</td>
						<td>'.$device_data->month_device_impressions.'</td>
						<td>'.$device_data->month_device_ctr.'</td>
						<td>'.$device_data->month_device_position.'</td>
						</tr>';				
					}

					$result['device']	=	$device_html;
				}
				else{
					$result['device']	=	'<tr><td colspan="5"><center>No device found</center></td></tr>';
				}
			}


			if($range == 'three'){
				if(count($final->three_device_array) > 0){
					foreach($final->three_device_array as $device_data){
						$device_html	.='
						<tr>
						<td>'.$device_data->three_device_keys.'</td>
						<td>'.$device_data->three_device_clicks.'</td>
						<td>'.$device_data->three_device_impressions.'</td>
						<td>'.$device_data->three_device_ctr.'</td>
						<td>'.$device_data->three_device_position.'</td>
						</tr>';					
					}
					$result['device']	=	$device_html;
				}
				else{
					$result['device']	=	'<tr><td colspan="5"><center>No device found</center></td></tr>';
				}
			}

			if($range == 'six'){
				if(count($final->six_device_array) > 0){
					foreach($final->six_device_array as $device_data){
						$device_html	.='
						<tr>
						<td>'.$device_data->six_device_keys.'</td>
						<td>'.$device_data->six_device_clicks.'</td>
						<td>'.$device_data->six_device_impressions.'</td>
						<td>'.$device_data->six_device_ctr.'</td>
						<td>'.$device_data->six_device_position.'</td>
						</tr>';						
					}
					$result['device']	=	$device_html;
				}
				else{
					$result['device']	=	'<tr><td colspan="5"><center>No device found</center></td></tr>';
				}
			}

			if($range == 'nine'){
				if(count($final->nine_device_array) > 0){
					foreach($final->nine_device_array as $device_data){
						$device_html	.='
						<tr>
						<td>'.$device_data->nine_device_keys.'</td>
						<td>'.$device_data->nine_device_clicks.'</td>
						<td>'.$device_data->nine_device_impressions.'</td>
						<td>'.$device_data->nine_device_ctr.'</td>
						<td>'.$device_data->nine_device_position.'</td>
						</tr>';					
					}

					$result['device']	=	$device_html;
				}
				else{
					$result['device']	=	'<tr><td colspan="5"><center>No device found</center></td></tr>';
				}
			}


			if($range == 'year'){
				if(count($final->year_device_array) > 0){
					foreach($final->year_device_array as $device_data){
						$device_html	.='
						<tr>
						<td>'.$device_data->year_device_keys.'</td>
						<td>'.$device_data->year_device_clicks.'</td>
						<td>'.$device_data->year_device_impressions.'</td>
						<td>'.$device_data->year_device_ctr.'</td>
						<td>'.$device_data->year_device_position.'</td>
						</tr>';				
					}
					$result['device']	=	$device_html;
				}
				else{
					$result['device']	=	'<tr><td colspan="5"><center>No device found</center></td></tr>';
				}
			}

			if($range == 'twoyear'){
				if(count($final->two_year_device_array) > 0){
					foreach($final->two_year_device_array as $device_data){
						$device_html	.='
						<tr>
						<td>'.$device_data->two_year_device_keys.'</td>
						<td>'.$device_data->two_year_device_clicks.'</td>
						<td>'.$device_data->two_year_device_impressions.'</td>
						<td>'.$device_data->two_year_device_ctr.'</td>
						<td>'.$device_data->two_year_device_position.'</td>
						</tr>';				
					}

					$result['device']	=	$device_html;
				}
				else{
					$result['device']	=	'<tr><td colspan="5"><center>No device found</center></td></tr>';
				}
			}

			return response()->json($result);

		}

	}

	public function ajax_get_search_console_pages(Request $request){
		$campaignId = $request['campaignId'];


		if (!file_exists(env('FILE_PATH')."public/search_console/".$campaignId)) {
			$res['status'] = 0;
		} else {
			$result  = array();
			$range = 'three';
			if(isset($request['value'])  && $request['value'] !=null){
				$range = $request['value'];
			}else{
				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'search_console');
				if(!empty($sessionHistoryRange)){
				// $selected = DashboardController::getSelectedDateForCharts($sessionHistoryRange->start_date,$sessionHistoryRange->end_date);
					$selected = $sessionHistoryRange->duration;
					if($selected == 1){
						$range = 'month';
					}elseif($selected == 3){
						$range = 'three';
					}elseif($selected == 6){
						$range = 'six';
					}elseif($selected == 9){
						$range = 'nine';
					}elseif($selected == 12){
						$range = 'year';
					}elseif($selected == 24){
						$range = 'twoyear';
					}
				}else{
					$range = 'three';
				}
			}

			$url = env('FILE_PATH')."public/search_console/".$campaignId.'/page.json'; 
			$data = file_get_contents($url);

			$final = json_decode($data);


			$page_html =  '';

			if($range == 'month'){
				if(count($final->month_page_array) > 0){
					foreach($final->month_page_array as $page_data){
						$page_html	.='
						<tr>
						<td>'.$page_data->month_page_keys.'</td>
						<td>'.number_format($page_data->month_page_clicks,2).'</td>
						<td>'.number_format($page_data->month_page_impressions,2).'</td>
						</tr>';				
					}
					$result['page']	=	$page_html;
				}
				else{
					$result['page']	=	'<tr><td colspan="5"><center>No page found</center></td></tr>';
				}
			}


			if($range == 'three'){
				if(count($final->three_page_array) > 0){
					foreach($final->three_page_array as $page_data){
						$page_html	.='
						<tr>
						<td>'.$page_data->three_page_keys.'</td>
						<td>'.number_format($page_data->three_page_clicks,2).'</td>
						<td>'.number_format($page_data->three_page_impressions,2).'</td>
						</tr>';					
					}
					$result['page']	=	$page_html;
				}
				else{
					$result['page']	=	'<tr><td colspan="5"><center>No page found</center></td></tr>';
				}
			}

			if($range == 'six'){
				if(count($final->six_page_array) > 0){
					foreach($final->six_page_array as $page_data){
						$page_html	.='
						<tr>
						<td>'.$page_data->six_page_keys.'</td>
						<td>'.number_format($page_data->six_page_clicks,2).'</td>
						<td>'.number_format($page_data->six_page_impressions,2).'</td>
						</tr>';							
					}
					$result['page']	=	$page_html;
				}
				else{
					$result['page']	=	'<tr><td colspan="5"><center>No page found</center></td></tr>';
				}
			}

			if($range == 'nine'){
				if(count($final->nine_page_array) > 0){
					foreach($final->nine_page_array as $page_data){
						$page_html	.='
						<tr>
						<td>'.$page_data->nine_page_keys.'</td>
						<td>'.number_format($page_data->nine_page_clicks,2).'</td>
						<td>'.number_format($page_data->nine_page_impressions,2).'</td>
						</tr>';					
					}

					$result['page']	=	$page_html;
				}
				else{
					$result['page']	=	'<tr><td colspan="5"><center>No page found</center></td></tr>';
				}
			}


			if($range == 'year'){
				if(count($final->year_page_array) > 0){
					foreach($final->year_page_array as $page_data){
						$page_html	.='
						<tr>
						<td>'.$page_data->year_page_keys.'</td>
						<td>'.number_format($page_data->year_page_clicks,2).'</td>
						<td>'.number_format($page_data->year_page_impressions,2).'</td>
						</tr>';				
					}

					$result['page']	=	$page_html;
				}
				else{
					$result['page']	=	'<tr><td colspan="5"><center>No page found</center></td></tr>';
				}
			}

			if($range == 'twoyear'){
				if(count($final->two_year_page_array) > 0){
					foreach($final->two_year_page_array as $page_data){
						$page_html	.='
						<tr>
						<td>'.$page_data->two_year_page_keys.'</td>
						<td>'.number_format($page_data->two_year_page_clicks,2).'</td>
						<td>'.number_format($page_data->two_year_page_impressions,2).'</td>
						</tr>';				
					}

					$result['page']	=	$page_html;
				}
				else{
					$result['page']	=	'<tr><td colspan="5"><center>No page found</center></td></tr>';
				}
			}

			return response()->json($result);

		}

	}

	public function ajax_get_search_console_country(Request $request){
		$campaignId = $request['campaignId'];

		if (!file_exists(env('FILE_PATH')."public/search_console/".$campaignId)) {
			$res['status'] = 0;
		} else {
			$result  = array();
			$range = 'three';
			if(isset($request['value'])  && $request['value'] !=null){
				$range = $request['value'];
			}else{
				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'search_console');
				if(!empty($sessionHistoryRange)){
				// $selected = DashboardController::getSelectedDateForCharts($sessionHistoryRange->start_date,$sessionHistoryRange->end_date);
					$selected = $sessionHistoryRange->duration;
					if($selected == 1){
						$range = 'month';
					}elseif($selected == 3){
						$range = 'three';
					}elseif($selected == 6){
						$range = 'six';
					}elseif($selected == 9){
						$range = 'nine';
					}elseif($selected == 12){
						$range = 'year';
					}elseif($selected == 24){
						$range = 'twoyear';
					}
				}else{
					$range = 'three';
				}
			}


			$url = env('FILE_PATH')."public/search_console/".$campaignId.'/country.json'; 
			$data = file_get_contents($url);

			$final = json_decode($data);


			$page_html =  '';

			if($range == 'month'){
				if(count($final->month_country_array) > 0){
					foreach($final->month_country_array as $page_data){
						$page_html	.='
						<tr>
						<td>'.strtoupper($page_data->month_country_keys).'</td>
						<td>'.$page_data->month_country_clicks.'</td>
						<td>'.$page_data->month_country_impressions.'</td>
						<td>'.number_format($page_data->month_country_ctr,2).'</td>
						<td>'.number_format($page_data->month_country_position,2).'</td>
						</tr>';				
					}

					$result['country']	=	$page_html;
				}
				else{
					$result['country']	=	'<tr><td colspan="5"><center>No country found</center></td></tr>';
				}
			}


			if($range == 'three'){
				if(count($final->three_country_array) > 0){
					foreach($final->three_country_array as $page_data){
						$page_html	.='
						<tr>
						<td>'.strtoupper($page_data->threeCountry_keys).'</td>
						<td>'.$page_data->threeCountry_clicks.'</td>
						<td>'.$page_data->threeCountry_impressions.'</td>
						<td>'.number_format($page_data->threeCountry_ctr,2).'</td>
						<td>'.number_format($page_data->threeCountry_position,2).'</td>
						</tr>';					
					}
					$result['country']	=	$page_html;
				}
				else{
					$result['country']	=	'<tr><td colspan="5"><center>No country found</center></td></tr>';
				}
			}

			if($range == 'six'){
				if(count($final->six_country_array) > 0){
					foreach($final->six_country_array as $page_data){
						$page_html	.='
						<tr>
						<td>'.strtoupper($page_data->six_month_Country_keys).'</td>
						<td>'.$page_data->six_month_Country_clicks.'</td>
						<td>'.$page_data->six_month_Country_impressions.'</td>
						<td>'.number_format($page_data->six_month_Country_ctr,2).'</td>
						<td>'.number_format($page_data->six_month_Country_position,2).'</td>
						</tr>';							
					}

					$result['country']	=	$page_html;
				}
				else{
					$result['country']	=	'<tr><td colspan="5"><center>No country found</center></td></tr>';
				}
			}

			if($range == 'nine'){
				if(count($final->six_country_array) > 0){
					foreach($final->nine_country_array as $page_data){
						$page_html	.='
						<tr>
						<td>'.strtoupper($page_data->nine_month_Country_keys).'</td>
						<td>'.$page_data->nine_month_Country_clicks.'</td>
						<td>'.$page_data->nine_month_Country_impressions.'</td>
						<td>'.number_format($page_data->nine_month_Country_ctr,2).'</td>
						<td>'.number_format($page_data->nine_month_Country_position,2).'</td>
						</tr>';					
					}

					$result['country']	=	$page_html;
				}
				else{
					$result['country']	=	'<tr><td colspan="5"><center>No country found</center></td></tr>';
				}
			}


			if($range == 'year'){
				if(count($final->year_country_array) > 0){
					foreach($final->year_country_array as $page_data){
						$page_html	.='
						<tr>
						<td>'.strtoupper($page_data->year_Country_keys).'</td>
						<td>'.$page_data->year_Country_clicks.'</td>
						<td>'.$page_data->year_Country_impressions.'</td>
						<td>'.number_format($page_data->year_Country_ctr,2).'</td>
						<td>'.number_format($page_data->year_Country_position,2).'</td>
						</tr>';				
					}
					$result['country']	=	$page_html;
				}
				else{
					$result['country']	=	'<tr><td colspan="5"><center>No country found</center></td></tr>';
				}
			}

			if($range == 'twoyear'){
				if(count($final->two_year_country_array) > 0){
					foreach($final->two_year_country_array as $page_data){
						$page_html	.='
						<tr>
						<td>'.strtoupper($page_data->two_year_Country_keys).'</td>
						<td>'.$page_data->two_year_Country_clicks.'</td>
						<td>'.$page_data->two_year_Country_impressions.'</td>
						<td>'.number_format($page_data->two_year_Country_ctr,2).'</td>
						<td>'.number_format($page_data->two_year_Country_position,2).'</td>
						</tr>';				
					}

					$result['country']	=	$page_html;
				}
				else{
					$result['country']	=	'<tr><td colspan="5"><center>No country found</center></td></tr>';
				}
			}

			return response()->json($result);

		}

	}

	private function search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year){


		$final_query = array();
		$month_query_keys = $month_query_clicks = $month_query_impressions = $month_query_ctr = $month_query_position =  '';
		$three_query_keys = $three_query_clicks = $three_query_impressions = $three_query_ctr = $three_query_position ='';
		$six_query_keys = $six_query_clicks = $six_query_impressions = $six_query_ctr= $six_query_position= '';
		$nine_query_keys = $nine_query_clicks = $nine_query_impressions = $nine_query_ctr = $nine_query_position = '';
		$one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = $one_year_query_ctr = $one_year_query_position = '';
		$query_dates=$query_converted_dates=	$query_keys = $query_clicks = $query_impressions  = $query_ctr = $query_position = '';

		$nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = array();

		$search_console_query = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$start_date,$end_date);	
		$search_console_query_one = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$one_month,$end_date);

		$search_console_query_three = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$three_month,$end_date);	
		$search_console_query_six = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$six_month,$end_date);	
		$search_console_query_nine = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$nine_month,$end_date);	
		$search_console_query_year = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$one_year,$end_date);	



		if(!empty($search_console_query_one)){
			foreach($search_console_query_one->getRows() as $month_query){

				$month_query_keys = $month_query->keys[0];
				$month_query_clicks = $month_query->clicks;
				$month_query_impressions = $month_query->impressions;
				$month_query_ctr = $month_query->ctr;
				$month_query_position = $month_query->position;

				$month_query_array[] = array(
					'month_query_keys'=>$month_query_keys,
					'month_query_clicks' =>$month_query_clicks,
					'month_query_impressions'=>$month_query_impressions,
					'month_query_ctr'=>$month_query_ctr,
					'month_query_position'=>$month_query_position
				);
			}

		}

		if(!empty($search_console_query_three)){
			foreach($search_console_query_three->getRows() as $three_query){
				$three_query_keys = $three_query->keys[0]	;
				$three_query_clicks = $three_query->clicks;
				$three_query_impressions = $three_query->impressions;
				$three_query_ctr = $three_query->ctr;
				$three_query_position = $three_query->position;

				$three_query_array[] = array(
					'three_query_keys'=>$three_query_keys,
					'three_query_clicks' =>$three_query_clicks,
					'three_query_impressions'=>$three_query_impressions,
					'three_query_ctr'=>$three_query_ctr,
					'three_query_position'=>$three_query_position
				);
			}
		}

		if(!empty($search_console_query_six)){
			foreach($search_console_query_six->getRows() as $six_query){
				$six_query_keys = $six_query->keys[0]	;
				$six_query_clicks = $six_query->clicks;
				$six_query_impressions = $six_query->impressions;
				$six_query_ctr = $six_query->ctr;
				$six_query_position = $six_query->position;


				$six_query_array[] = array(
					'six_query_keys'=>$six_query_keys,
					'six_query_clicks' =>$six_query_clicks,
					'six_query_impressions'=>$six_query_impressions,
					'six_query_ctr'=>$six_query_ctr,
					'six_query_position'=>$six_query_position
				);
			}
		}							

		if(!empty($search_console_query_nine)){
			foreach($search_console_query_nine->getRows() as $nine_query){
				$nine_query_keys = $nine_query->keys[0]	;
				$nine_query_clicks = $nine_query->clicks;
				$nine_query_impressions = $nine_query->impressions;
				$nine_query_ctr = $nine_query->ctr;
				$nine_query_position = $nine_query->position;

				$nine_query_array[] = array(
					'nine_query_keys'=>$nine_query_keys,
					'nine_query_clicks' =>$nine_query_clicks,
					'nine_query_impressions'=>$nine_query_impressions,
					'nine_query_ctr'=>$nine_query_ctr,
					'nine_query_position'=>$nine_query_position
				);
			}
		}

		if(!empty($search_console_query_year)){
			foreach($search_console_query_year->getRows() as $one_year_query){
				$one_year_query_keys = $one_year_query->keys[0]	;
				$one_year_query_clicks = $one_year_query->clicks;
				$one_year_query_impressions = $one_year_query->impressions;
				$one_year_query_ctr = $one_year_query->ctr;
				$one_year_query_position = $one_year_query->position;

				$one_year_query_array[] = array(
					'one_year_query_keys'=>$one_year_query_keys,
					'one_year_query_clicks' =>$one_year_query_clicks,
					'one_year_query_impressions'=>$one_year_query_impressions,
					'one_year_query_ctr'=>$one_year_query_ctr,
					'one_year_query_position'=>$one_year_query_position
				);
			}
		}

		if(!empty($search_console_query)){
			foreach($search_console_query->getRows() as $query_key=> $query){
				$query_keys = $query->keys[0]	;
				$query_clicks = $query->clicks;
				$query_impressions = $query->impressions;
				$query_ctr = $query->ctr;
				$query_position = $query->position;


				$query_array[] = array(
					'query_keys'=>$query_keys,
					'query_clicks' =>$query_clicks,
					'query_impressions'=>$query_impressions,
					'query_ctr'=>$query_ctr,
					'query_position'=>$query_position
				);
			}
		} 							

		$final_query = array(
			'month_query_array'=>$month_query_array,
			'three_query_array'=>$three_query_array,
			'six_query_array'=>$six_query_array,
			'nine_query_array'=>$nine_query_array,
			'one_year_query_array'=>$one_year_query_array,
			'two_year_query_array'=>$query_array
		);


	// if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 	$queryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json';
	// 	if(date("Y-m-d", filemtime($queryfilename)) != date('Y-m-d')){
		file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json', print_r(json_encode($final_query,true),true));
		//}
	// }
	// elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
	// 	mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
	// 	file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json', print_r(json_encode($final_query,true),true));
	// }

		$month_query_keys = $month_query_clicks = $month_query_impressions = $month_query_ctr = $month_query_position =  '';
		$three_query_keys = $three_query_clicks = $three_query_impressions = $three_query_ctr = $three_query_position ='';
		$six_query_keys = $six_query_clicks = $six_query_impressions = $six_query_ctr= $six_query_position= '';
		$nine_query_keys = $nine_query_clicks = $nine_query_impressions = $nine_query_ctr = $nine_query_position = '';
		$one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = $one_year_query_ctr = $one_year_query_position = '';
		$query_dates=$query_converted_dates=	$query_keys = $query_clicks = $query_impressions  = $query_ctr = $query_position ='';

		$nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = $final_query = array();
	}

	private function search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year){
		$month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

		$three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
		$six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
		$nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
		$year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
		$two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
		$month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();


		$one_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$one_month,$end_date);
		$three_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$three_month,$end_date);
		$six_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$six_month,$end_date);
		$nine_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$nine_month,$end_date);
		$year_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$one_year,$end_date);
		$two_year_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$start_date,$end_date);


		if(!empty($one_search_console_device)){
			foreach($one_search_console_device->getRows() as $month_device){

				$month_device_keys = $month_device->keys[0];
				$month_device_clicks = $month_device->clicks;
				$month_device_impressions = $month_device->impressions;
				$month_device_ctr = $month_device->ctr;
				$month_device_position = $month_device->position;

				$month_device_array[] = array(
					'month_device_keys'=>$month_device_keys,
					'month_device_clicks' =>$month_device_clicks,
					'month_device_impressions'=>$month_device_impressions,
					'month_device_ctr'=>$month_device_ctr,
					'month_device_position'=>$month_device_position
				);
			}
		}


		if(!empty($three_search_console_device)){
			foreach($three_search_console_device->getRows() as $three_device){

				$three_device_keys = $three_device->keys[0];
				$three_device_clicks = $three_device->clicks;
				$three_device_impressions = $three_device->impressions;
				$three_device_ctr = $three_device->ctr;
				$three_device_position = $three_device->position;

				$three_device_array[] = array(
					'three_device_keys'=>$three_device_keys,
					'three_device_clicks' =>$three_device_clicks,
					'three_device_impressions'=>$three_device_impressions,
					'three_device_ctr'=>$three_device_ctr,
					'three_device_position'=>$three_device_position
				);
			}
		}


		if(!empty($six_search_console_device)){
			foreach($six_search_console_device->getRows() as $six_device){

				$six_device_keys = $six_device->keys[0];
				$six_device_clicks = $six_device->clicks;
				$six_device_impressions = $six_device->impressions;
				$six_device_ctr = $six_device->ctr;
				$six_device_position = $six_device->position;

				$six_device_array[] = array(
					'six_device_keys'=>$six_device_keys,
					'six_device_clicks' =>$six_device_clicks,
					'six_device_impressions'=>$six_device_impressions,
					'six_device_ctr'=>$six_device_ctr,
					'six_device_position'=>$six_device_position
				);
			}
		}


		if(!empty($nine_search_console_device)){
			foreach($nine_search_console_device->getRows() as $nine_device){

				$nine_device_keys = $nine_device->keys[0];
				$nine_device_clicks = $nine_device->clicks;
				$nine_device_impressions = $nine_device->impressions;
				$nine_device_ctr = $nine_device->ctr;
				$nine_device_position = $nine_device->position;

				$nine_device_array[] = array(
					'nine_device_keys'=>$nine_device_keys,
					'nine_device_clicks' =>$nine_device_clicks,
					'nine_device_impressions'=>$nine_device_impressions,
					'nine_device_ctr'=>$nine_device_ctr,
					'nine_device_position'=>$nine_device_position
				);
			}
		}


		if(!empty($year_console_device)){
			foreach($year_console_device->getRows() as $year_device){

				$year_device_keys = $year_device->keys[0];
				$year_device_clicks = $year_device->clicks;
				$year_device_impressions = $year_device->impressions;
				$year_device_ctr = $year_device->ctr;
				$year_device_position = $year_device->position;

				$year_device_array[] = array(
					'year_device_keys'=>$year_device_keys,
					'year_device_clicks' =>$year_device_clicks,
					'year_device_impressions'=>$year_device_impressions,
					'year_device_ctr'=>$year_device_ctr,
					'year_device_position'=>$year_device_position
				);
			}
		}


		if(!empty($two_year_search_console_device)){
			foreach($two_year_search_console_device->getRows() as $two_year_device){

				$two_year_device_keys = $two_year_device->keys[0];
				$two_year_device_clicks = $two_year_device->clicks;
				$two_year_device_impressions = $two_year_device->impressions;
				$two_year_device_ctr = $two_year_device->ctr;
				$two_year_device_position = $two_year_device->position;

				$two_year_device_array[] = array(
					'two_year_device_keys'=>$two_year_device_keys,
					'two_year_device_clicks' =>$two_year_device_clicks,
					'two_year_device_impressions'=>$two_year_device_impressions,
					'two_year_device_ctr'=>$two_year_device_ctr,
					'two_year_device_position'=>$two_year_device_position
				);
			}
		}

		$final_device = array(
			'month_device_array'=>$month_device_array,
			'three_device_array'=>$three_device_array,
			'six_device_array'=>$six_device_array,
			'nine_device_array'=>$nine_device_array,
			'year_device_array'=>$year_device_array,
			'two_year_device_array'=>$two_year_device_array
		);

		if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
			$devicefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json';
			if(file_exists($devicefilename)){
				if(date("Y-m-d", filemtime($devicefilename)) != date('Y-m-d')){
					file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json', print_r(json_encode($final_device,true),true));
				}
			}else{
				file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json', print_r(json_encode($final_device,true),true));
			}

		}
		elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
			mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json', print_r(json_encode($final_device,true),true));
		}




		$month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

		$three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
		$six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
		$nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
		$year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
		$two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
		$month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();
	}

	private function search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year){



		$month_page_keys = $month_page_clicks = $month_page_impressions = '';
		$three_page_keys = $three_page_clicks = $three_page_impressions = '';
		$six_page_keys = $six_page_clicks = $six_page_impressions = '';
		$nine_page_keys = $nine_page_clicks = $nine_page_impressions = '';
		$year_page_keys = $year_page_clicks = $year_page_impressions = '';
		$two_year_page_keys = $two_year_page_clicks = $two_year_page_impressions = '';
		$month_page_array = $three_page_array = $six_page_array = $nine_page_array =  $year_page_array = $two_year_page_array = $final_page = array();



		$one_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$one_month,$end_date);
		$three_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$three_month,$end_date);
		$six_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$six_month,$end_date);
		$nine_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$nine_month,$end_date);
		$one_year_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$one_year,$end_date);
		$two_year_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$start_date,$end_date);




		if(!empty($one_month_page)){
			foreach($one_month_page->getRows() as $month_page){
				$month_page_keys = $month_page->keys[0];
				$month_page_clicks = $month_page->clicks;
				$month_page_impressions = $month_page->impressions;

				$month_page_array[] = array(
					'month_page_keys'=>$month_page_keys,
					'month_page_clicks' =>$month_page_clicks,
					'month_page_impressions'=>$month_page_impressions
				);
			}
		}

		if(!empty($three_month_page)){
			foreach($three_month_page->getRows() as $three_page){
				$three_page_keys = $three_page->keys[0];
				$three_page_clicks = $three_page->clicks;
				$three_page_impressions = $three_page->impressions;

				$three_page_array[] = array(
					'three_page_keys'=>$three_page_keys,
					'three_page_clicks' =>$three_page_clicks,
					'three_page_impressions'=>$three_page_impressions
				);
			}
		}

		if(!empty($six_month_page)){
			foreach($six_month_page->getRows() as $six_page){
				$six_page_keys = $six_page->keys[0];
				$six_page_clicks = $six_page->clicks;
				$six_page_impressions = $six_page->impressions;

				$six_page_array[] = array(
					'six_page_keys'=>$six_page_keys,
					'six_page_clicks' =>$six_page_clicks,
					'six_page_impressions'=>$six_page_impressions
				);
			}
		}

		if(!empty($nine_month_page)){
			foreach($nine_month_page->getRows() as $nine_page){
				$nine_page_keys = $nine_page->keys[0];
				$nine_page_clicks = $nine_page->clicks;
				$nine_page_impressions = $nine_page->impressions;

				$nine_page_array[] = array(
					'nine_page_keys'=>$nine_page_keys,
					'nine_page_clicks' =>$nine_page_clicks,
					'nine_page_impressions'=>$nine_page_impressions
				);
			}
		}

		if(!empty($one_year_page)){
			foreach($one_year_page->getRows() as $year_page){
				$year_page_keys = $year_page->keys[0];
				$year_page_clicks = $year_page->clicks;
				$year_page_impressions = $year_page->impressions;

				$year_page_array[] = array(
					'year_page_keys'=>$year_page_keys,
					'year_page_clicks' =>$year_page_clicks,
					'year_page_impressions'=>$year_page_impressions
				);
			}
		}

		if(!empty($two_year_page)){
			foreach($two_year_page->getRows() as $two_yearpage){
				$two_year_page_keys = $two_yearpage->keys[0];
				$two_year_page_clicks = $two_yearpage->clicks;
				$two_year_page_impressions = $two_yearpage->impressions;

				$two_year_page_array[] = array(
					'two_year_page_keys'=>$two_year_page_keys,
					'two_year_page_clicks' =>$two_year_page_clicks,
					'two_year_page_impressions'=>$two_year_page_impressions
				);
			}
		}

		$final_page = array(
			'month_page_array'=>$month_page_array,
			'three_page_array'=>$three_page_array,
			'six_page_array'=>$six_page_array,
			'nine_page_array'=>$nine_page_array,
			'year_page_array'=>$year_page_array,
			'two_year_page_array'=>$two_year_page_array
		);

		if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
			$pagefilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json';
			if(file_exists($pagefilename)){
				if(date("Y-m-d", filemtime($pagefilename)) != date('Y-m-d')){
					file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json', print_r(json_encode($final_page,true),true));
				}
			}else{
				file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json', print_r(json_encode($final_page,true),true));
			}
		}
		elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
			mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json', print_r(json_encode($final_page,true),true));
		}


		$month_page_keys = $month_page_clicks = $month_page_impressions = '';
		$three_page_keys = $three_page_clicks = $three_page_impressions = '';
		$six_page_keys = $six_page_clicks = $six_page_impressions = '';
		$nine_page_keys = $nine_page_clicks = $nine_page_impressions = '';
		$year_page_keys = $year_page_clicks = $year_page_impressions = '';
		$two_year_page_keys = $two_year_page_clicks = $two_year_page_impressions = '';
		$month_page_array = $three_page_array = $six_page_array = $nine_page_array =  $year_page_array = $two_year_page_array = $final_page = array();

	}

	private function search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year){


		$month_country_keys = $month_country_clicks = $month_country_impressions = $month_country_ctr = $month_country_position =  '';
		$threeCountry_keys = $threeCountry_clicks = $threeCountry_impressions = $threeCountry_ctr = $threeCountry_position =  '';
		$six_month_Country_keys = $six_month_Country_clicks = $six_month_Country_impressions = $six_month_Country_ctr = $six_month_Country_position =  '';
		$nine_month_Country_keys = $nine_month_Country_clicks = $nine_month_Country_impressions = $nine_month_Country_ctr = $nine_month_Country_position =  '';
		$year_Country_keys = $year_Country_clicks = $year_Country_impressions = $year_Country_ctr = $year_Country_position =  '';
		$two_year_Country_keys = $two_year_Country_clicks = $two_year_Country_impressions = $two_year_Country_ctr = $two_year_Country_position =  '';


		$month_country_array = $three_country_array = $six_country_array = $nine_country_array = $year_country_array = $final_country = $two_year_country_array =  array();


		$month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$one_month,$end_date);
		$three_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$three_month,$end_date);
		$six_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$six_month,$end_date);
		$nine_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$nine_month,$end_date);
		$one_year_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$one_year,$end_date);
		$two_year_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$start_date,$end_date);


		if(!empty($month_country)){
			foreach($month_country->getRows() as $monthCountry){

				$month_country_keys = $monthCountry->keys[0];
				$month_country_clicks = $monthCountry->clicks;
				$month_country_impressions = $monthCountry->impressions;
				$month_country_ctr = $monthCountry->ctr;
				$month_country_position = $monthCountry->position;

				$month_country_array[] = array(
					'month_country_keys'=>$month_country_keys,
					'month_country_clicks' =>$month_country_clicks,
					'month_country_impressions'=>$month_country_impressions,
					'month_country_ctr'=>$month_country_ctr,
					'month_country_position'=>$month_country_position
				);
			}
		}

		if(!empty($three_month_country)){
			foreach($three_month_country->getRows() as $threeCountry){

				$threeCountry_keys = $threeCountry->keys[0];
				$threeCountry_clicks = $threeCountry->clicks;
				$threeCountry_impressions = $threeCountry->impressions;
				$threeCountry_ctr = $threeCountry->ctr;
				$threeCountry_position = $threeCountry->position;

				$three_country_array[] = array(
					'threeCountry_keys'=>$threeCountry_keys,
					'threeCountry_clicks' =>$threeCountry_clicks,
					'threeCountry_impressions'=>$threeCountry_impressions,
					'threeCountry_ctr'=>$threeCountry_ctr,
					'threeCountry_position'=>$threeCountry_position
				);
			}

		}

		if(!empty($six_month_country)){
			foreach($six_month_country->getRows() as $six_month_Country){

				$six_month_Country_keys = $six_month_Country->keys[0];
				$six_month_Country_clicks = $six_month_Country->clicks;
				$six_month_Country_impressions = $six_month_Country->impressions;
				$six_month_Country_ctr = $six_month_Country->ctr;
				$six_month_Country_position = $six_month_Country->position;

				$six_country_array[] = array(
					'six_month_Country_keys'=>$six_month_Country_keys,
					'six_month_Country_clicks' =>$six_month_Country_clicks,
					'six_month_Country_impressions'=>$six_month_Country_impressions,
					'six_month_Country_ctr'=>$six_month_Country_ctr,
					'six_month_Country_position'=>$six_month_Country_position
				);
			}

		}

		if(!empty($nine_month_country)){
			foreach($nine_month_country->getRows() as $nine_month_Country){

				$nine_month_Country_keys = $nine_month_Country->keys[0];
				$nine_month_Country_clicks = $nine_month_Country->clicks;
				$nine_month_Country_impressions = $nine_month_Country->impressions;
				$nine_month_Country_ctr = $nine_month_Country->ctr;
				$nine_month_Country_position = $nine_month_Country->position;

				$nine_country_array[] = array(
					'nine_month_Country_keys'=>$nine_month_Country_keys,
					'nine_month_Country_clicks' =>$nine_month_Country_clicks,
					'nine_month_Country_impressions'=>$nine_month_Country_impressions,
					'nine_month_Country_ctr'=>$nine_month_Country_ctr,
					'nine_month_Country_position'=>$nine_month_Country_position
				);
			}

		}

		if(!empty($one_year_country)){
			foreach($one_year_country->getRows() as $year_Country){

				$year_Country_keys = $year_Country->keys[0];
				$year_Country_clicks = $year_Country->clicks;
				$year_Country_impressions = $year_Country->impressions;
				$year_Country_ctr = $year_Country->ctr;
				$year_Country_position = $year_Country->position;

				$year_country_array[] = array(
					'year_Country_keys'=>$year_Country_keys,
					'year_Country_clicks' =>$year_Country_clicks,
					'year_Country_impressions'=>$year_Country_impressions,
					'year_Country_ctr'=>$year_Country_ctr,
					'year_Country_position'=>$year_Country_position
				);
			}

		}


		if(!empty($two_year_country)){
			foreach($two_year_country->getRows() as $two_year_Country){

				$two_year_Country_keys = $two_year_Country->keys[0];
				$two_year_Country_clicks = $two_year_Country->clicks;
				$two_year_Country_impressions = $two_year_Country->impressions;
				$two_year_Country_ctr = $two_year_Country->ctr;
				$two_year_Country_position = $two_year_Country->position;

				$two_year_country_array[] = array(
					'two_year_Country_keys'=>$two_year_Country_keys,
					'two_year_Country_clicks' =>$two_year_Country_clicks,
					'two_year_Country_impressions'=>$two_year_Country_impressions,
					'two_year_Country_ctr'=>$two_year_Country_ctr,
					'two_year_Country_position'=>$two_year_Country_position
				);
			}

		}

		$final_country = array(
			'month_country_array'=>$month_country_array,
			'three_country_array'=>$three_country_array,
			'six_country_array'=>$six_country_array,
			'nine_country_array'=>$nine_country_array,
			'year_country_array'=>$year_country_array,
			'two_year_country_array'=>$two_year_country_array
		);


		if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
			$countryfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json';
			if(file_exists($countryfilename)){
				if(date("Y-m-d", filemtime($countryfilename)) != date('Y-m-d')){
					file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json', print_r(json_encode($final_country,true),true));
				}
			}else{
				file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json', print_r(json_encode($final_country,true),true));
			}
		}
		elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
			mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json', print_r(json_encode($final_country,true),true));

		}

		$month_country_keys = $month_country_clicks = $month_country_impressions = $month_country_ctr = $month_country_position =  '';
		$threeCountry_keys = $threeCountry_clicks = $threeCountry_impressions = $threeCountry_ctr = $threeCountry_position =  '';
		$six_month_Country_keys = $six_month_Country_clicks = $six_month_Country_impressions = $six_month_Country_ctr = $six_month_Country_position =  '';
		$nine_month_Country_keys = $nine_month_Country_clicks = $nine_month_Country_impressions = $nine_month_Country_ctr = $nine_month_Country_position =  '';
		$year_Country_keys = $year_Country_clicks = $year_Country_impressions = $year_Country_ctr = $year_Country_position =  '';
		$two_year_Country_keys = $two_year_Country_clicks = $two_year_Country_impressions = $two_year_Country_ctr = $two_year_Country_position =  '';


		$month_country_array = $three_country_array = $six_country_array = $nine_country_array = $year_country_array = $final_country = $two_year_country_array =  array();

	}


	private function search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId){

		$dates = $converted_dates = $clicks = $impressions = $data_array = array();


		$searchConsoleData = GoogleAnalyticsUsers::getSearchConsoleData($client,$profileUrl,$start_date,$end_date);
		if(!empty($searchConsoleData)){
			foreach($searchConsoleData->getRows() as $data_key=>$data){
				$dates[] = $data->keys[0];
				$converted_dates[] = strtotime($data->keys[0])*1000;
				$clicks[]    = array('t'=>strtotime($data->keys[0])*1000,'y'=>$data->clicks);
				$impressions[] = array('t'=>strtotime($data->keys[0])*1000,'y'=>$data->impressions);
			}

		}


		$data_array = array(
			'dates'=>$dates,
			'converted_dates'=>$converted_dates,
			'clicks' =>$clicks,
			'impressions'=>$impressions
		);

		if (file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
			$graphfilename = env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json';
			if(file_exists($graphfilename)){
				if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){
					file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json', print_r(json_encode($data_array,true),true));
				}
			}else{
				file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json', print_r(json_encode($data_array,true),true));
			}
		}
		elseif (!file_exists(env('FILE_PATH').'public/search_console/'.$campaignId)) {
			mkdir(env('FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json', print_r(json_encode($data_array,true),true));
		}
		$dates = $converted_dates = $clicks = $impressions = array();
	}

	/*06 may*/
	public function ajax_refresh_search_console_urls(Request $request){
		$response = array();
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$sc_id = $request->email;
		$campaignId = $request->campaign_id;

		$getConsoleAccount = SearchConsoleUsers::where('id',$sc_id)->first();

		if($getConsoleAccount){
			$client = SearchConsoleUsers::ConsoleClientAuth($getConsoleAccount);
			$refresh_token  = $getConsoleAccount->google_refresh_token;


			/*if refresh token expires*/
			if ($client->isAccessTokenExpired()) {
				SearchConsoleUsers::google_refresh_token($client,$refresh_token,$getConsoleAccount->id);
			}


			$service = new \Google_Service_Webmasters($client);
			$data = SearchConsoleUrl::refresh_console_urls($service,$campaignId,$sc_id,$user_id);


			if($data['status']==1){
				$response['status'] = 1;
				$response['message'] = 'Last fetched now';
			}
			if($data['status'] == 0){
				$response['status'] = 0;
				$response['message'] = 'Error refreshing account';
			}


		}else{
			$response['status'] = 2;
			$response['message'] = 'Error: Please try again.';

		}
		return response()->json($response);
	}


	/*new design*/
	public static function get_selected_range($range){
		if($range === 'One Month'){
			$duration = 1;
		}elseif($range === 'Three Month'){
			$duration = 3;
		}elseif($range === 'Six Month'){
			$duration = 6;
		}elseif($range === 'Nine Month'){
			$duration = 9;
		}elseif($range === 'One Year'){
			$duration = 12;
		}elseif($range === 'Two Year'){
			$duration = 24;
		}else{
			$duration = 0;
		}
		return $duration;
	}

	public function ajax_new_search_console(Request $request){

		$campaignId = $request['campaignId'];
		if (!file_exists(env('FILE_PATH')."public/search_console/".$campaignId)) {
			$res['status'] = 0;
		} else {
			$url = env('FILE_PATH')."public/search_console/".$campaignId.'/graphs.json'; 
			$data = file_get_contents($url);
			$final = json_decode($data);
			$last_file_date = end($final->dates);

			// if(!empty($final->dates)){
			// 	$last_file_date = date('Y-m-d',strtotime('+1 day',strtotime(end($final->dates))));
			// 	$end_date = date('Y-m-d',strtotime($last_file_date));
			// 	$display_end = date('Y-m-d',strtotime('-1 day',strtotime($end_date)));
			// }else{
			// 	$end_date = date('Y-m-d',strtotime('-1 day'));
			// 	$display_end = $end_date;
			// }

			if($request->selected_label == 0 && $request->selected_label !== null){
				$start_date = date('Y-m-d', strtotime($request->current_start));
				$end_date = date('Y-m-d', strtotime($request->current_end));
				$display_end = $end_date;
			}else{
				$display_end = date('Y-m-d',strtotime('-1 day',strtotime($end_date)));
			}

			$selected_range = $request->selected_label;
			$duration = $this->get_selected_range($selected_range);
			$module = $request['module'];
			if(Auth::user() <> null){
				$user_id = User::get_parent_user_id(Auth::user()->id);
				$role_id = User::get_user_role(Auth::user()->id);
			}else{
				$getUser = SemrushUserAccount::where('id',$campaignId)->first();
				$user_id = $getUser->user_id;
				$role_id = User::get_user_role($getUser->user_id);
			}	

			$state = ($request->has('key'))?'viewkey':'user';

			$start_date =  date('Y-m-d',strtotime($request->current_start));
		//$end_date =  date('Y-m-d',strtotime($last_file_date));

			$res['start_date'] =  date('Y/m/d', strtotime($start_date));
			$res['end_date'] = date('Y/m/d', strtotime($display_end));

			$previous_start_date =  date('Y-m-d',strtotime($request->previous_start));
			$previous_end_date =  date('Y-m-d',strtotime($request->previous_end));

			$res['previous_start_date'] = date('Y/m/d', strtotime($previous_start_date));
			$res['previous_end_date'] = date('Y/m/d', strtotime($previous_end_date));

			$calculated_duration = ModuleByDateRange::calculate_days($start_date,$end_date);

			if($request->comparison === 1 || $request->comparison === '1'){
				$display_range = date("M d' Y",strtotime($start_date)) .' - '.date("M d' Y",strtotime($display_end)).' (compared to '.date("M d' Y",strtotime($previous_start_date)) .' - '.date("M d' Y",strtotime($previous_end_date)).')';
			}else{
				$display_range = date("M d' Y",strtotime($start_date)) .' - '.date("M d' Y",strtotime($display_end));
			}


			if($role_id != 4 && $state == 'user' && $duration !== 0){
				$ifCheck = ModuleByDateRange::where('request_id',$campaignId)->where('module',$module)->first();
				$array = [
					'user_id'=>$user_id,
					'request_id'=>$campaignId,
					'duration'=>($duration === 0)?$if_check->duration:$duration,
					'module'=>$module,
					'start_date'=>date('Y-m-d', strtotime($start_date)),
					'end_date'=>date('Y-m-d', strtotime($end_date)),
					'compare_start_date'=>date('Y-m-d', strtotime($request->previous_start)),
					'compare_end_date'=>date('Y-m-d', strtotime($request->previous_end)),
					'status'=>$request->comparison,
					'comparison'=>$request->comparison_selected
				];

				if(empty($ifCheck)){
					ModuleByDateRange::create($array);
				}else{
					ModuleByDateRange::where('id',$ifCheck->id)->update($array);
				}
			}

			$dates = $clicks = $impressions = $previous_clicks = $previous_impressions = $previous_dates = $current_labels = $previous_labels = array();
			$current_clicks_count = $current_impressions_count = $current_ctr_count = $current_position_count = $previous_clicks_count = $previous_impressions_count = $previous_ctr_count = $previous_position_count = 0;
			for($i=1;$i<=$calculated_duration;$i++){
				if($i!==1){  
					$start_date = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
					$previous_start_date = date('Y-m-d',strtotime('+1 day',strtotime($previous_end_date)));
				}
				$end_date = date('Y-m-d',strtotime('+0 day',strtotime($start_date)));    
				$previous_end_date = date('Y-m-d',strtotime('+0 day',strtotime($previous_start_date)));    

				$current_index = array_search($start_date,$final->dates);
				$previous_index = array_search($previous_start_date,$final->dates);

				if($current_index === false){
					$dates[] = date('M d, Y',strtotime($start_date));
					$clicks[] = 0;
					$impressions[] = 0;
				}else{
					$dates[] = date('M d, Y',strtotime($final->dates[$current_index]));
					$clicks[] = $final->clicks[$current_index]->y;
					$impressions[] = $final->impressions[$current_index]->y;

					$current_clicks_count += $final->clicks[$current_index]->y;
					$current_impressions_count += $final->impressions[$current_index]->y;
					$current_ctr_count += $final->ctr[$current_index];
					$current_position_count += $final->position[$current_index];
				}

				if($request->comparison === 1 || $request->comparison === '1'){
					if($previous_index === false){
						$previous_dates[] = date('M d, Y',strtotime($previous_start_date));
						$previous_clicks[] = 0;
						$previous_impressions[] =  0;
					}else{
						$previous_dates[] = date('M d, Y',strtotime($final->dates[$previous_index]));
						$previous_clicks[] = $final->clicks[$previous_index]->y;
						$previous_impressions[] = $final->impressions[$previous_index]->y;

						$previous_clicks_count += $final->clicks[$previous_index]->y;
						$previous_impressions_count += $final->impressions[$previous_index]->y;
						$previous_ctr_count += $final->ctr[$previous_index];
						$previous_position_count += $final->position[$previous_index];
					}
				}
				$current_labels[] = date('l, F d, Y',strtotime($start_date));
				$previous_labels[] = date('l, F d, Y',strtotime($previous_start_date));
			}

			$res['from_datelabel'] = $dates;
			$res['previous_dates'] = $previous_dates;
			$res['clicks'] = $clicks;
			$res['impressions'] = $impressions;
			$res['previous_clicks'] = $previous_clicks;
			$res['previous_impressions'] = $previous_impressions;
			$res['comparison'] = $request->comparison;
			$res['current_labels'] = $current_labels;
			$res['previous_labels'] = $previous_labels;
			$res['display_range'] = $display_range;
			$res['duration'] = $duration;
			if($current_clicks_count > 1000 && $current_clicks_count <= 1000000){
				$res['current_clicks_count'] = round($current_clicks_count/1000,2).'K';
			}elseif($current_clicks_count >= 1000000 ){
				$res['current_clicks_count'] = round($current_clicks_count/1000000,2).'M';
			}else{
				$res['current_clicks_count'] = $current_clicks_count;
			}

			if($current_impressions_count > 1000 && $current_impressions_count <= 1000000){
				$res['current_impressions_count'] = round($current_impressions_count/1000,2).'K';
			}elseif($current_impressions_count >= 1000000){
				$res['current_impressions_count'] = round($current_impressions_count/1000000,2).'M';
			}else{
				$res['current_impressions_count'] = $current_impressions_count;
			}

			if($current_ctr_count > 0){
				$res['current_ctr_count'] = round(($current_ctr_count/$calculated_duration)*100,2).'%';
			}else{
				$res['current_ctr_count'] = '-';
			}
			if($current_position_count > 0){
				$res['current_position_count'] = round(($current_position_count/$calculated_duration),2);
			}else{
				$res['current_position_count'] = '-';
			}

			if($previous_clicks_count > 1000 && $previous_clicks_count <= 1000000){
				$res['previous_clicks_count'] = round($previous_clicks_count/1000,2).'K';
			}elseif($previous_clicks_count >= 1000000){
				$res['previous_clicks_count'] = round($previous_clicks_count/1000000,2).'M';
			}else{
				$res['previous_clicks_count'] = $previous_clicks_count;
			}

			if($previous_impressions_count > 1000 && $previous_impressions_count <= 1000000){
				$res['previous_impressions_count'] = round($previous_impressions_count/1000,2).'K';
			}elseif($previous_impressions_count >= 1000000){
				$res['previous_impressions_count'] = round($previous_impressions_count/1000000,2).'M';
			}else{
				$res['previous_impressions_count'] = $previous_impressions_count;
			}

			if($previous_ctr_count > 0){
				$res['previous_ctr_count'] = round(($previous_ctr_count/$calculated_duration)*100,2).'%';
			}else{
				$res['previous_ctr_count'] = '-';
			}
			if($previous_position_count > 0){
				$res['previous_position_count'] = round(($previous_position_count/$calculated_duration),2);
			}else{
				$res['previous_position_count'] = '-';
			}
			$res['status'] = 1;
			
		}
		return response()->json($res);		
	}

	public function ajax_fetch_listing(Request $request){

		$sidebar_selection = $request['sidebar_selection'];
		$campaignId = $request['campaignId'];
		$start_date = date('Y-m-d', strtotime($request['start_date']));
		$end_date = date('Y-m-d', strtotime($request['end_date']));
		$duration = $request['duration'];

		$data = $this->get_clientAuth($campaignId);

		$query_array = $pages_array = $country_array = array();

		$search_console_query = SearchConsoleUrl::getSearchConsoleMetrics($data['client'],$data['profile_url'],$start_date,$end_date,'query',100);

		/*queries data*/
		if(!empty($search_console_query)){
			$query_html = '';
			foreach($search_console_query->getRows() as $q_data){
				$query_array[] = array(
					'queries'=>$q_data->keys[0],
					'clicks' =>$q_data->clicks,
					'impressions'=>$q_data->impressions,
					'ctr'=>$q_data->ctr,
					'position'=>$q_data->position
				);  
			}
		}	

		/*queries data*/

		$search_console_pages = SearchConsoleUrl::getSearchConsoleMetrics($data['client'],$data['profile_url'],$start_date,$end_date,'page',100);

		/*page data*/
		if(!empty($search_console_pages)){
			$page_html = '';
			foreach($search_console_pages->getRows() as $pages_data){
				$pages_array[] = array(
					'page'=>$pages_data->keys[0],
					'clicks' =>$pages_data->clicks,
					'impressions'=>$pages_data->impressions,
					'ctr'=>$pages_data->ctr,
					'position'=>$pages_data->position
				); 
			}
		}
		/*page data*/




		$search_console_country = SearchConsoleUrl::getSearchConsoleMetrics($data['client'],$data['profile_url'],$start_date,$end_date,'country',100);
		/*country data*/
		if(!empty($search_console_country)){
			$country_html = '';
			foreach($search_console_country->getRows() as $c_key=>$country_data){
				$country_array[] = array(
					'country'=>$country_data->keys[0],
					'clicks' =>$country_data->clicks,
					'impressions'=>$country_data->impressions,
					'ctr'=>$country_data->ctr,
					'position'=>$country_data->position
				);
			}
		}
		/*country data*/


		if($duration !== 0 && $duration !== '0' && $request->selection_type == '1'){
			if (!file_exists(\config('app.FILE_PATH').'public/search_console/'.$campaignId)) {
				mkdir(\config('app.FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
			}
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/queries.json', print_r(json_encode($query_array,true),true));
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/pages.json', print_r(json_encode($pages_array,true),true));
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/countries.json', print_r(json_encode($country_array,true),true));
		}
		elseif($duration !== 0 && $duration !== '0' && $request->selection_type == '2'){
			if (!file_exists(\config('app.FILE_PATH').'public/search_console/'.$campaignId)) {
				mkdir(\config('app.FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
			}
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/viewkey_queries.json', print_r(json_encode($query_array,true),true));
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/viewkey_pages.json', print_r(json_encode($pages_array,true),true));
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/viewkey_countries.json', print_r(json_encode($country_array,true),true));
		}
		else{
			if (!file_exists(\config('app.FILE_PATH').'public/search_console/'.$campaignId)) {
				mkdir(\config('app.FILE_PATH').'public/search_console/'.$campaignId, 0777, true);	
			}
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/queries_custom.json', print_r(json_encode($query_array,true),true));
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/pages_custom.json', print_r(json_encode($pages_array,true),true));
			file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/countries_custom.json', print_r(json_encode($country_array,true),true));
		}

		$query_data = $this->fetch_listing_data($query_array,1,10);
		$pages_data = $this->fetch_listing_data($pages_array,1,10);
		$country_data = $this->fetch_listing_data($country_array,1,10);



		if($sidebar_selection === 'visibility'){
			$returnQuery = view('viewkey.seo_sections.search_console.queries_table')->with('query_data', $query_data)->render();
			$returnPages = view('viewkey.seo_sections.search_console.pages_table')->with('pages_data', $pages_data)->render();
			$returnCountry = view('viewkey.seo_sections.search_console.countries_table')->with('country_data', $country_data)->render();
		}else{
			$returnQuery = view('vendor.seo_sections.search_console.queries_table')->with('query_data', $query_data)->render();
			$returnPages = view('vendor.seo_sections.search_console.pages_table')->with('pages_data', $pages_data)->render();
			$returnCountry = view('vendor.seo_sections.search_console.countries_table')->with('country_data', $country_data)->render();
		}


		return response()->json(array('success' => true, 'query_data'=>$returnQuery, 'pages_data'=>$returnPages, 'country_data'=>$returnCountry,'duration'=>$duration));
	}

	private function get_clientAuth($campaign_id){
		$res = array();
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$get_project_data = SemrushUserAccount::select('id','google_console_id','console_account_id')->where('id',$campaign_id)->first();

		if(!empty($get_project_data)){
			$getAnalytics = SearchConsoleUsers::where('user_id',$user_id)->where('id',$get_project_data->google_console_id)->first();
			$client = SearchConsoleUsers::ClientAuth($getAnalytics);
			$refresh_token  = $getAnalytics->google_refresh_token;
			if ($client->isAccessTokenExpired()) {
				GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
			}
			$get_profile_data = SearchConsoleUrl::where('id',$get_project_data->console_account_id)->first();
			if(!empty($get_profile_data)){
				$profile_url = $get_profile_data->siteUrl;
			}

			$res['status'] = 1;
			$res['client'] = $client;
			$res['profile_url'] = $profile_url;
		}else{
			$res['status'] = 2;
			$res['message'] = 'Error, try reconnecting your account.';
		}
		return $res;
	}

	private function fetch_listing_data($search_console_data,$page,$perPage){
		$newCollection = collect($search_console_data);
		$offset = ($page * $perPage) - $perPage;
		$results =  new LengthAwarePaginator(
			$newCollection->slice($offset, $perPage),
			$newCollection->count(),
			$perPage,
			$page
		);

		return $results;
	}

	public function ajax_display_search_console_graph(Request $request){
		$campaign_id = $request['campaign_id'];
		if (!file_exists(env('FILE_PATH')."public/search_console/".$campaign_id)) {
			$res['status'] = 0;
		} else {
			$url = env('FILE_PATH')."public/search_console/".$campaign_id.'/graphs.json'; 
			if(!file_exists(env('FILE_PATH').'public/search_console/'.$campaign_id.'/graphs.json')){
				$res['status'] = 0;
			}else{
				$data = file_get_contents($url);
				$final = json_decode($data);
				if(!empty($final->dates)){
					$last_file_date = date('Y-m-d',strtotime('+1 day',strtotime(end($final->dates))));
					$end_date = date('Y-m-d',strtotime($last_file_date));
					$display_end = date('Y-m-d',strtotime('-1 day',strtotime($end_date)));
				}else{
					$end_date = date('Y-m-d',strtotime('-1 day'));
					$display_end = $end_date;
				}

				$start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
				$comparison = 0;  $comparison_period = 'previous_period';$duration = 3;
				$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'search_console');

				if(!empty($sessionHistoryRange)){
					$comparison = $sessionHistoryRange->status;
					$comparison_period = $sessionHistoryRange->comparison;
					$duration = $sessionHistoryRange->duration;
					if($duration == 1){
						$start_date = date('Y-m-d', strtotime("-1 month", strtotime($end_date)));
					}elseif($duration == 3){
						$start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
					}elseif($duration == 6){
						$start_date = date('Y-m-d', strtotime("-6 month", strtotime($end_date)));
					}elseif($duration == 9){
						$start_date = date('Y-m-d', strtotime("-9 month", strtotime($end_date)));
					}elseif($duration == 12){
						$start_date = date('Y-m-d', strtotime("-1 year", strtotime($end_date)));
					}elseif($duration == 24){
						$start_date = date('Y-m-d', strtotime("-2 year", strtotime($end_date)));
					}
				}

				$res['start_date'] =  date('Y/m/d', strtotime($start_date));
				$res['end_date'] = date('Y/m/d', strtotime($display_end));


				$dates = $clicks = $impressions = $previous_clicks = $previous_impressions = $previous_dates = $current_labels = $previous_labels = array();

				$calculated_duration = ModuleByDateRange::calculate_days($start_date,$end_date);

				if($comparison_period === 'previous_period'){
					$previous_period_dates = SearchConsoleUsers::calculate_previous_period($start_date,$calculated_duration);
				}else{
					$previous_period_dates = SearchConsoleUsers::calculate_previous_year($start_date,$end_date);	
				}

				$previous_start_date = $previous_period_dates['previous_start_date'];
				$previous_end_date = $previous_period_dates['previous_end_date'];
				$res['previous_start_date'] = date('Y/m/d', strtotime($previous_start_date));
				$res['previous_end_date'] = date('Y/m/d', strtotime($previous_end_date));

				if($comparison === 1 || $comparison === '1'){
					$display_range = date("M d' Y",strtotime($start_date)) .' - '.date("M d' Y",strtotime($display_end)).' (compared to '.date("M d' Y",strtotime($previous_start_date)) .' - '.date("M d' Y",strtotime($previous_end_date)).')';
				}else{
					$display_range = date("M d' Y",strtotime($start_date)) .' - '.date("M d' Y",strtotime($display_end));
				}

				$current_clicks_count = $current_impressions_count = $current_ctr_count = $current_position_count = $previous_clicks_count = $previous_impressions_count = $previous_ctr_count = $previous_position_count = 0;
				for($i=1;$i<=$calculated_duration;$i++){
					if($i!==1){  
						$start_date = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
						$previous_start_date = date('Y-m-d',strtotime('+1 day',strtotime($previous_end_date)));
					}
					$end_date = date('Y-m-d',strtotime('+0 day',strtotime($start_date)));    
					$previous_end_date = date('Y-m-d',strtotime('+0 day',strtotime($previous_start_date)));    

					$current_index = array_search($start_date,$final->dates);
					$previous_index = array_search($previous_start_date,$final->dates);

					if($current_index === false){
						$dates[] = date('M d, Y',strtotime($start_date));
						$clicks[] = 0;
						$impressions[] = 0;
					}else{
						$dates[] = date('M d, Y',strtotime($final->dates[$current_index]));
						$clicks[] = $final->clicks[$current_index]->y;
						$impressions[] = $final->impressions[$current_index]->y;

						$current_clicks_count += $final->clicks[$current_index]->y;
						$current_impressions_count += $final->impressions[$current_index]->y;
						$current_ctr_count += $final->ctr[$current_index];
						$current_position_count += $final->position[$current_index];
					}

					if($comparison === 1 || $comparison === '1'){
						if($previous_index === false){
							$previous_dates[] = date('M d, Y',strtotime($previous_start_date));
							$previous_clicks[] = 0;
							$previous_impressions[] =  0;
						}else{
							$previous_dates[] = date('M d, Y',strtotime($final->dates[$previous_index]));
							$previous_clicks[] = $final->clicks[$previous_index]->y;
							$previous_impressions[] = $final->impressions[$previous_index]->y;

							$previous_clicks_count += $final->clicks[$previous_index]->y;
							$previous_impressions_count += $final->impressions[$previous_index]->y;
							$previous_ctr_count += $final->ctr[$previous_index];
							$previous_position_count += $final->position[$previous_index];
						}
					}
					$current_labels[] = date('l, F d, Y',strtotime($start_date));
					$previous_labels[] = date('l, F d, Y',strtotime($previous_start_date));
				}

				$res['from_datelabel'] = $dates;
				$res['previous_dates'] = $previous_dates;
				$res['clicks'] = $clicks;
				$res['impressions'] = $impressions;
				$res['previous_clicks'] = $previous_clicks;
				$res['previous_impressions'] = $previous_impressions;
				$res['comparison'] = $comparison;
				$res['current_labels'] = $current_labels;
				$res['previous_labels'] = $previous_labels;
				$res['display_range'] = $display_range;
				$res['duration'] = $duration;
				// if($current_clicks_count > 1000 && $current_clicks_count <= 1000000){
				// 	$res['current_clicks_count'] = round($current_clicks_count/1000,2).'K';
				// }elseif($current_clicks_count >= 1000000 ){
				// 	$res['current_clicks_count'] = round($current_clicks_count/1000000,2).'M';
				// }else{
					$res['current_clicks_count'] = shortNumbers($current_clicks_count);
				// }

				// if($current_impressions_count > 1000 && $current_impressions_count <= 1000000){
				// 	$res['current_impressions_count'] = round($current_impressions_count/1000,2).'K';
				// }elseif($current_impressions_count >= 1000000){
				// 	$res['current_impressions_count'] = round($current_impressions_count/1000000,2).'M';
				// }else{
					$res['current_impressions_count'] = shortNumbers($current_impressions_count);
				// }

				if($current_ctr_count > 0){
					$res['current_ctr_count'] = round(($current_ctr_count/$calculated_duration)*100,2).'%';
				}else{
					$res['current_ctr_count'] = '-';
				}
				if($current_position_count > 0){
					$res['current_position_count'] = round(($current_position_count/$calculated_duration),2);
				}else{
					$res['current_position_count'] = '-';
				}

				// if($previous_clicks_count > 1000 && $previous_clicks_count <= 1000000){
				// 	$res['previous_clicks_count'] = round($previous_clicks_count/1000,2).'K';
				// }elseif($previous_clicks_count >= 1000000){
				// 	$res['previous_clicks_count'] = round($previous_clicks_count/1000000,2).'M';
				// }else{
					$res['previous_clicks_count'] = shortNumbers($previous_clicks_count);
				// }

				// if($previous_impressions_count > 1000 && $previous_impressions_count <= 1000000){
				// 	$res['previous_impressions_count'] = round($previous_impressions_count/1000,2).'K';
				// }elseif($previous_impressions_count >= 1000000){
				// 	$res['previous_impressions_count'] = round($previous_impressions_count/1000000,2).'M';
				// }else{
					$res['previous_impressions_count'] = shortNumbers($previous_impressions_count);
				// }

				if($previous_ctr_count > 0){
					$res['previous_ctr_count'] = round(($previous_ctr_count/$calculated_duration)*100,2).'%';
				}else{
					$res['previous_ctr_count'] = '-';
				}
				if($previous_position_count > 0){
					$res['previous_position_count'] = round(($previous_position_count/$calculated_duration),2);
				}else{
					$res['previous_position_count'] = '-';
				}
				$res['status'] = 1;
			}
		}
		return response()->json($res);
	}

	public function ajax_fetch_list_data(Request $request){
		$campaign_id = $request['campaign_id'];
		$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'search_console');
		$duration = ($sessionHistoryRange)?$sessionHistoryRange->duration:3;
		/*queries data*/

		$query_data = $pages_data = $country_data =  array();
		$q_url = env('FILE_PATH')."public/search_console/".$campaign_id.'/queries.json'; 
		if(file_exists(env('FILE_PATH').'public/search_console/'.$campaign_id.'/queries.json')){
			$q_data = file_get_contents($q_url);
			$q_array = json_decode($q_data,true);
			$query_data = $this->fetch_listing_data($q_array,1,10);
		}
		/*queries data*/

		/*pages data*/
		if(file_exists(env('FILE_PATH').'public/search_console/'.$campaign_id.'/pages.json')){
			$p_url = env('FILE_PATH')."public/search_console/".$campaign_id.'/pages.json'; 
			$p_data = file_get_contents($p_url);
			$p_array = json_decode($p_data,true);
			$pages_data = $this->fetch_listing_data($p_array,1,10);
		}
		/*pages data*/

		/*countries data*/
		if(file_exists(env('FILE_PATH').'public/search_console/'.$campaign_id.'/countries.json')){
			$c_url = env('FILE_PATH')."public/search_console/".$campaign_id.'/countries.json'; 
			$c_data = file_get_contents($c_url);
			$c_array = json_decode($c_data,true);
			$country_data = $this->fetch_listing_data($c_array,1,10);
		}
		/*countries data*/

		$returnQuery = view('vendor.seo_sections.search_console.queries_table')->with('query_data', $query_data)->render();
		$returnPages = view('vendor.seo_sections.search_console.pages_table')->with('pages_data', $pages_data)->render();
		$returnCountry = view('vendor.seo_sections.search_console.countries_table')->with('country_data', $country_data)->render();


		return response()->json(array('success' => true, 'query_data'=>$returnQuery,'pages_data'=>$returnPages,'country_data'=>$returnCountry,'duration'=>$duration));
	}

	public function ajax_search_console_queries(Request $request){	
		if (!file_exists(env('FILE_PATH')."public/search_console/".$request->campaign_id)) {
			return response()->json(array('success' => false, 'query_data'=>'','duration'=>$request->duration));
		} else {
			if($request->duration === '0'){
				$url = env('FILE_PATH')."public/search_console/".$request->campaign_id.'/queries_custom.json'; 
			}elseif($request->selection_type === '2'){
				$url = env('FILE_PATH')."public/search_console/".$request->campaign_id.'/viewkey_queries.json';
			}else{
				$url = env('FILE_PATH')."public/search_console/".$request->campaign_id.'/queries.json';
			}
			$data = file_get_contents($url);
			$final = json_decode($data,true);
			$results = $this->fetch_listing_data($final,$request->page,10);
			if($request->selected === 'visibility'){
				$return = view('viewkey.seo_sections.search_console.queries_table')->with('query_data', $results)->render();
			}else{
				$return = view('vendor.seo_sections.search_console.queries_table')->with('query_data', $results)->render();
			}
			return response()->json(array('success' => true, 'query_data'=>$return,'duration'=>$request->duration));
		}

	}

	public function ajax_search_console_pages(Request $request){
		if (!file_exists(env('FILE_PATH')."public/search_console/".$request->campaign_id)) {
			return response()->json(array('success' => false, 'pages_data'=>'','duration'=>$request->duration));
		} else {
			if($request->duration === '0'){
				$url = env('FILE_PATH')."public/search_console/".$request->campaign_id.'/pages_custom.json'; 
			}elseif($request->selection_type === '2'){
				$url = env('FILE_PATH')."public/search_console/".$request->campaign_id.'/viewkey_pages.json';
			}else{
				$url = env('FILE_PATH')."public/search_console/".$request->campaign_id.'/pages.json'; 
			}
			$data = file_get_contents($url);
			$final = json_decode($data,true);
			$results = $this->fetch_listing_data($final,$request->page,10);
			if($request->selected === 'visibility'){
				$return = view('viewkey.seo_sections.search_console.pages_table')->with('pages_data', $results)->render();
			}else{
				$return = view('vendor.seo_sections.search_console.pages_table')->with('pages_data', $results)->render();
			}
			return response()->json(array('success' => true, 'pages_data'=>$return,'duration'=>$request->duration));
		}

	}

	public function ajax_search_console_countries(Request $request){
		if (!file_exists(env('FILE_PATH')."public/search_console/".$request->campaign_id)) {
			return response()->json(array('success' => false, 'country_data'=>'','duration'=>$request->duration));
		} else {
			if($request->duration === '0'){
				$url = env('FILE_PATH')."public/search_console/".$request->campaign_id.'/countries_custom.json'; 
			}elseif($request->selection_type === '2'){
				$url = env('FILE_PATH')."public/search_console/".$request->campaign_id.'/viewkey_countries.json';
			}else{
				$url = env('FILE_PATH')."public/search_console/".$request->campaign_id.'/countries.json'; 
			}
			$data = file_get_contents($url);
			$final = json_decode($data,true);
			$results = $this->fetch_listing_data($final,$request->page,10);
			$return = view('vendor.seo_sections.search_console.countries_table')->with('country_data', $results)->render();
			return response()->json(array('success' => true, 'country_data'=>$return,'duration'=>$request->duration));
		}

	}

	public function ajax_get_latest_console_data(Request $request){
		$response = array();
		$latest_refresh  = GoogleUpdate::select('search_console')->where('request_id',$request->campaign_id)->first();
		if(date('Y-m-d') === date('Y-m-d',strtotime($latest_refresh->search_console))){
			$response['status'] = 'crawled'; 
			$response['message'] = 'This result is pretty fresh, so re-fetching is disabled.';
			return response()->json($response);
		}else{
			$user_id = User::get_parent_user_id(Auth::user()->id); /*get user id from child*/
			$module = ModuleByDateRange::getModuleDateRange($request->campaign_id,'search_console');
			$end_date = date('Y-m-d',strtotime('-1 day'));
			if(isset($module) && !empty($module)){
				$list_start_date = date('Y-m-d', strtotime("-".$module->duration." month", strtotime($end_date)));
			}else{
				$list_start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
			}

			$get_console_details = SemrushUserAccount::select('google_console_id','console_account_id')->where('id',$request->campaign_id)->first();
			$getAnalytics = SearchConsoleUsers::where('user_id',$user_id)->where('id',$get_console_details->google_console_id)->first();
			$client = SearchConsoleUsers::ClientAuth($getAnalytics);
			$refresh_token  = $getAnalytics->google_refresh_token;
			if ($client->isAccessTokenExpired()) {
				GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
			}

			$get_profile_data = SearchConsoleUrl::where('id',$get_console_details->console_account_id)->first();
			if(!empty($get_profile_data)){
				$profile_url = $get_profile_data->siteUrl;
				$check = SearchConsoleUrl::check_weekly_data($client,$profile_url,$get_console_details->console_account_id);

				if($check['status'] === 0 || $check['status'] === 2){
					Error::updateOrCreate(
						['request_id' => $request->campaign_id,'module'=> 2],
						['response'=> json_encode($check),'request_id' => $request->campaign_id,'module'=> 2]
					);

					$response = SemrushUserAccount::display_google_errorMessages(2,$request->campaign_id);
					$response['status'] = 'google-error'; 
					$response['message'] = $response['message'];
				}else{
					$log_data = SearchConsoleUsers::log_latest_search_console_data($client,$profile_url,$request->campaign_id,$list_start_date);		
					if(isset($log_data['status']) && $log_data['status'] == 1){
						$response['status'] = 'success';
						$ifErrorExists = Error::removeExisitingError(2,$request->campaign_id);
						if(!empty($ifErrorExists)){
							Error::where('id',$ifErrorExists->id)->delete();
						}
						GoogleUpdate::updateTiming($request->campaign_id,'search_console','sc_type','2');
					} else{
						$response['status'] = 'error';
						$response['message'] = $log_data['message'];
					}
				}
				return  response()->json($response);
			}
		}
	}


	public function search_console_cron(){
		$getUser = SemrushUserAccount::
		whereHas('UserInfo', function($q){
			// $q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
			// ->where('subscription_status', 1);
		})  
		->where('console_account_id','!=',NULL)
		->where('status',0)
		->limit(10)
		->where('id',53)
		->get();
		
		if(isset($getUser) && !empty($getUser) && count($getUser) > 0){
			foreach($getUser as $key=>$data){

				$module = ModuleByDateRange::getModuleDateRange($data->id,'search_console');
				$end_date = date('Y-m-d',strtotime('-1 day'));
				if(isset($module) && !empty($module)){
					$list_start_date = date('Y-m-d', strtotime("-".$module->duration." month", strtotime($end_date)));
				}else{
					$list_start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
				}



				$getAnalytics = SearchConsoleUsers::where('user_id',$data->user_id)->where('id',$data->google_console_id)->first();
				$get_profile_data = SearchConsoleUrl::where('id',$data->console_account_id)->first();
				$client = SearchConsoleUsers::ClientAuth($getAnalytics);
				$refresh_token  = $getAnalytics->google_refresh_token;
				if ($client->isAccessTokenExpired()) {
					GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
				}

				if(!empty($get_profile_data)){
					$profile_url = $get_profile_data->siteUrl;
					$check = SearchConsoleUrl::check_weekly_data($client,$profile_url,$data->console_account_id);
					
					if($check['status'] == 0 || $check['status'] == 2){
						Error::updateOrCreate(
							['request_id' => $data->id,'module'=> 2],
							['response'=> json_encode($check),'request_id' => $data->id,'module'=> 2,'updated_at'=>now()]
						);
					}else{
						$log_data = SearchConsoleUsers::log_latest_search_console_data($client,$profile_url,$data->id,$list_start_date);
						if(isset($log_data['status']) && $log_data['status'] == 1){
							$ifErrorExists = Error::removeExisitingError(2,$data->id);
							if(!empty($ifErrorExists)){
								Error::where('id',$ifErrorExists->id)->delete();
							}
							GoogleUpdate::updateTiming($data->id,'search_console','sc_type','1');
						}
					}
				}
			}
		}
	}

	public function ajax_fetch_list_data_visibility(Request $request){
		$campaign_id = $request['campaign_id'];
		$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'search_console');
		$duration = ($sessionHistoryRange)?$sessionHistoryRange->duration:3;
		/*queries data*/

		$query_data = $pages_data = $country_data =  array();
		$q_url = env('FILE_PATH')."public/search_console/".$campaign_id.'/queries.json'; 
		if(file_exists(env('FILE_PATH').'public/search_console/'.$campaign_id.'/queries.json')){
			$q_data = file_get_contents($q_url);
			$q_array = json_decode($q_data,true);
			$query_data = $this->fetch_listing_data($q_array,1,10);
		}
		/*queries data*/

		/*pages data*/
		if(file_exists(env('FILE_PATH').'public/search_console/'.$campaign_id.'/pages.json')){
			$p_url = env('FILE_PATH')."public/search_console/".$campaign_id.'/pages.json'; 
			$p_data = file_get_contents($p_url);
			$p_array = json_decode($p_data,true);
			$pages_data = $this->fetch_listing_data($p_array,1,10);
		}
		/*pages data*/

		/*countries data*/
		if(file_exists(env('FILE_PATH').'public/search_console/'.$campaign_id.'/countries.json')){
			$c_url = env('FILE_PATH')."public/search_console/".$campaign_id.'/countries.json'; 
			$c_data = file_get_contents($c_url);
			$c_array = json_decode($c_data,true);
			$country_data = $this->fetch_listing_data($c_array,1,10);
		}
		/*countries data*/

		$returnQuery = view('viewkey.seo_sections.search_console.queries_table')->with('query_data', $query_data)->render();
		$returnPages = view('viewkey.seo_sections.search_console.pages_table')->with('pages_data', $pages_data)->render();
		$returnCountry = view('viewkey.seo_sections.search_console.countries_table')->with('country_data', $country_data)->render();


		return response()->json(array('success' => true, 'query_data'=>$returnQuery,'pages_data'=>$returnPages,'country_data'=>$returnCountry,'duration'=>$duration));
	}


	public function ajax_fetch_list_data_pdf(Request $request){
		$campaign_id = $request['campaign_id'];
		$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'search_console');
		$duration = ($sessionHistoryRange)?$sessionHistoryRange->duration:3;
		/*queries data*/

		$query_data = $pages_data = $country_data =  array();
		$q_url = env('FILE_PATH')."public/search_console/".$campaign_id.'/queries.json'; 
		if(file_exists(env('FILE_PATH').'public/search_console/'.$campaign_id.'/queries.json')){
			$q_data = file_get_contents($q_url);
			$q_array = json_decode($q_data,true);
			$query_data = $this->fetch_listing_data($q_array,1,10);
		}
		/*queries data*/

		/*pages data*/
		if(file_exists(env('FILE_PATH').'public/search_console/'.$campaign_id.'/pages.json')){
			$p_url = env('FILE_PATH')."public/search_console/".$campaign_id.'/pages.json'; 
			$p_data = file_get_contents($p_url);
			$p_array = json_decode($p_data,true);
			$pages_data = $this->fetch_listing_data($p_array,1,10);
		}
		/*pages data*/

		/*countries data*/
		if(file_exists(env('FILE_PATH').'public/search_console/'.$campaign_id.'/countries.json')){
			$c_url = env('FILE_PATH')."public/search_console/".$campaign_id.'/countries.json'; 
			$c_data = file_get_contents($c_url);
			$c_array = json_decode($c_data,true);
			$country_data = $this->fetch_listing_data($c_array,1,10);
		}
		/*countries data*/

		$returnQuery = view('viewkey.pdf.seo_sections.search_console.queries_table')->with('query_data', $query_data)->render();
		$returnPages = view('viewkey.pdf.seo_sections.search_console.pages_table')->with('pages_data', $pages_data)->render();
		$returnCountry = view('viewkey.pdf.seo_sections.search_console.countries_table')->with('country_data', $country_data)->render();


		return response()->json(array('success' => true, 'query_data'=>$returnQuery,'pages_data'=>$returnPages,'country_data'=>$returnCountry,'duration'=>$duration));
	}

}