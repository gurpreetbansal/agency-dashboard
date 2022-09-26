<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SemrushUserAccount;
use Auth;
use DB;
use Carbon\Carbon;
use DataTables;
use Session;

use App\Traits\ClientAuth;

use App\User;
use App\SemrushOrganicSearchData;
use App\SeoAnalyticsEditSection;
use App\Moz;
use App\UserPackage;
use App\GoogleAnalyticsUsers;
use App\KeywordLocationList;
use App\DashboardType;
use App\RegionalDatabse;
use App\KeywordSearch;
use App\DataforseoApiUnit;
use App\KeywordPosition;
use App\SemrushOrganicMetric;
use App\GoogleProfileData;
use App\GoogleGoalCompletion;
use App\ProjectCompareGraph;
use App\ModuleByDateRange;
use App\GoogleProfileSession;
use App\ActivityLog;
use App\CampaignDashboard;
use App\CampaignData;
use App\KeywordAlert;

class ProjectController extends Controller {

	use ClientAuth;

	public function add_new_project(Request $request) {
		$dashboardType_array = $request->dashboardType;

		$user_id = Auth::user()->id;

		$user_package = UserPackage::with('package')->where('user_id', $user_id)->select('projects')->orderBy('created_at', 'desc')->first();
		$getCampaignsCount = SemrushUserAccount::where('user_id', $user_id)->where('status', 0)->count();
		if ($user_package->projects <= $getCampaignsCount) {
			$response['status'] = 'error';
			$response['message'] = 'You have reached your project limit, Upgrade to add more projects.';
		} else {
			$user_data = User::where('id', $user_id)->first();
			$token = bin2hex(openssl_random_pseudo_bytes(16));
			$url_info = parse_url($request->domain_url);

			if (!empty($url_info) && isset($url_info['host'])) {
				$check_domain = SemrushUserAccount::checkdomainUrl($url_info['host'], $user_id);
				$domain_url = str_replace("www.", "", $url_info['host']);
			} elseif (!empty($url_info) && isset($url_info['path'])) {
				$check_domain = SemrushUserAccount::checkdomainUrl($url_info['path'], $user_id);
				$domain_url = str_replace("www.", "", $url_info['path']);
			}
			$dashboardType = implode(',',$request->dashboardType);

			if ($check_domain == 0) {
				$semrush_user_account = SemrushUserAccount::create([
					'user_id' => Auth::user()->id,
					'domain_name' => $request->domain_name,
					'domain_url' => $request->domain_url,
					'regional_db' => $request->regional_db,
					'clientName' => $user_data->name,
					'token' => $token,
					'dashboard_type'=>$dashboardType,
					'created' => now(),
					'modified' => now()
				]);
				if ($semrush_user_account) {
					$last_inserted_id = $semrush_user_account->id;
					$domain_url = rtrim($domain_url, '/');
					if (!empty($last_inserted_id)) {
						$insertMozData = Moz::getMozData($domain_url);
						if ($insertMozData) {
							Moz::create([
								'user_id' => $user_id,
								'request_id' => $last_inserted_id,
								'domain_authority' => $insertMozData->DomainAuthority,
								'page_authority' => $insertMozData->PageAuthority,
								'status' => 0,
								'created_at' => now()
							]);
						}
						$this->CustomNote($user_id, $last_inserted_id);
						$this->addCampaignDashboards($user_id, $last_inserted_id,$dashboardType_array);
						$response['status'] = 'success';
						$response['message'] = 'Domain added successfully.';
					} else {
						$response['status'] = 'error';
						$response['message'] = 'Getting error, Try again. ';
					}
				} else {
					$response['status'] = 'error';
					$response['message'] = 'Getting error, Try again.';
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = 'Domain Name already exists';
			}
		}
		return json_encode($response);
	}

	public function addCampaignDashboards($user_id, $last_inserted_id,$dashboardType_array){
		foreach($dashboardType_array as $id){
			CampaignDashboard::create([
				'user_id'=>$user_id,
				'request_id'=>$last_inserted_id,
				'dashboard_id'=>$id,
				'dashboard_status'=>1
			]);
		}

	}

	public function CustomNote($user_id, $last_inserted_id) {
		SeoAnalyticsEditSection::create([
			'user_id' => $user_id,
			'request_id' => $last_inserted_id,
			'edit_section' => "<b>Welcome to Your Dashboard!</b>
			<p>This dashboard gives you an at-a-glance view of the aspects of your campaign that are most important to you. And since it's customizable, you can ask your account manager to update it for you.</p>
			<h5>This dashboard shows you: </h5>
			<ul>
			<li>a. Traffic from Google analytics. </li>
			<li>b. Visibility of your campaign in Google from Search Console. </li>
			<li>c. Additional Keywords that you are ranking for from SEMRUSH.</li> 
			<li>d. Work performed by our team in Activity Seaction and much more.</li></ul>
			<p>You can download a PDF copy of whole report by clicking a button in top right section.</p>
			<p>To give us feedback on this tool, please leave a message with your account manager. </p>",
			'edit_area' => 0,
			'created' => now()
		]);
		return true;
	}

	public function campaign_detail($domain_name, $campaign_id) {
		$summary = SeoAnalyticsEditSection::where('request_id', $campaign_id)->first();
		$moz_data = Moz::where('request_id', $campaign_id)->first();
		$dashboardtype = SemrushUserAccount::where('id',$campaign_id)->where('user_id',Auth::user()->id)->first();

// echo "<pre>";
// print_r($dashboardtype);
// die;

		$getRegions = RegionalDatabse::where('status',1)->get();

		$googleAnalyticsCurrent = $this->getCurrentDomainDetails($campaign_id);
		// echo '<pre>';
		// print_r($googleAnalyticsCurrent);
		// die;


		$moduleTrafficStatus = ModuleByDateRange::where('user_id',Auth::user()->id)->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

		$moduleSearchStatus = ModuleByDateRange::where('user_id',Auth::user()->id)->where('request_id',$campaign_id)->where('module','search_console')->first();
		if(!empty($moduleTrafficStatus)){
			$selected = $this->getSelectedDateForCharts($moduleTrafficStatus->start_date,$moduleTrafficStatus->end_date);
		}else{
			$selected = 0;
		}
		if(!empty($moduleSearchStatus)){
			$selectedSearch = $this->getSelectedDateForCharts($moduleSearchStatus->start_date,$moduleSearchStatus->end_date);	
		}else{
			$selectedSearch = 0;
		}




		$AnalyticsCompare = ProjectCompareGraph::where('request_id',$campaign_id)->where('user_id',Auth::user()->id)->first();
		if(!empty($AnalyticsCompare)){
			$comparison = $AnalyticsCompare->compare_status;
		}else{
			$comparison = 0;
		}


		return view('vendor.campaigndetail', ['summary' => $summary, 'moz_data' => $moz_data,'campaign_id'=>$campaign_id,'googleAnalyticsCurrent'=>$googleAnalyticsCurrent,'dashboardtype'=>$dashboardtype,'getRegions'=>$getRegions,'selected'=>$selected,'comparison'=>$comparison,'selectedSearch'=>$selectedSearch
	]);
	}

	public static function getSelectedDateForCharts($start_date,$end_date){
		$selected = 0;
		$analyticsStart  = date('Y-m-d',strtotime($start_date));
		$analyticsEnd  = date('Y-m-d',strtotime($end_date));

		$ts1Ana = strtotime($analyticsStart);
		$ts2Ana = strtotime($analyticsEnd);

		$year1Ana = date('Y', $ts1Ana);
		$year2Ana = date('Y', $ts2Ana);

		$month1Ana = date('m', $ts1Ana);
		$month2Ana = date('m', $ts2Ana);


		$day_diff  = strtotime($start_date) - strtotime($end_date);
		$count_days  = floor($day_diff/(60*60*24));
		if($count_days == '-7'){
			$selected  = 0.25;
		}else{
			$selected = (($year2Ana - $year1Ana) * 12) + ($month2Ana - $month1Ana);
		}




		return $selected;
	}

	public static function getCurrentDomainDetails($campaignId){
		try{
		//$user_id = Auth::user()->id;
			$userData = User::findorfail(Auth::user()->id);
			if($userData->parent_id !=''){
				$user_id = $userData->parent_id;
			}else{
				$user_id = Auth::user()->id;
			}
			$getUser = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaignId)->first();	

			if(!empty($getUser)){

				$getAnalytics  = GoogleAnalyticsUsers::accountInfoById($user_id,$getUser->google_account_id);


				if(!empty($getAnalytics)){
					$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);

					$refresh_token  = $getAnalytics->google_refresh_token;

					/*if refresh token expires*/
					if ($client->isAccessTokenExpired()) {
						GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
					}

					$getAnalyticsId = SemrushUserAccount::with('google_analytics_account')->where('user_id',$user_id)->where('id',$campaignId)->first();
		// dd($getAnalyticsId);

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
							$AnalyticsProfileInfo = $goalCompletionData->getprofileInfo();
							$view_id = $AnalyticsProfileInfo->profileId;
							$checkIfExists = GoogleProfileData::where('user_id',$user_id)->where('request_id',$campaignId)->get();
							if(!empty($checkIfExists)){
								GoogleProfileData::where('user_id',$user_id)->where('request_id',$campaignId)->delete();
							}
							foreach($goalCompletionData->getRows() as $key=>$value){
								GoogleProfileData::create([
									'view_id'=>$view_id,
									'request_id'=>$campaignId,
									'user_id'=>$user_id,
									'keywords'=>$value[0],
									'sessions'=>$value[1],
									'new_users'=>$value[2],
									'bounse_rate'=>$value[3],
									'page_sessions'=>$value[4],
									'avg_session'=>$value[5],
									'goal_conversions'=>$value[6],
									'goal_completions'=>$value[7],
									'goal_value'=>$value[8]
								]);
							}

						}

						$goalCompletion = GoogleAnalyticsUsers::getCompletionData($analytics,$profile,$start_date,$end_date);
						$session_count = array();
						if(!empty($goalCompletion)){
							$AnalyticsProfileInfos = $goalCompletion->getprofileInfo();
							$viewid = $AnalyticsProfileInfos->profileId;
							$if_goal_completion = GoogleGoalCompletion::where('user_id',$user_id)->where('request_id',$campaignId)->get();

							if(!empty($if_goal_completion)){
								GoogleGoalCompletion::where('user_id',$user_id)->where('request_id',$campaignId)->delete();
							}

							foreach($goalCompletion->getRows() as $rowkey=>$rowValue){
								$session_count[] = $rowValue[1];
							}
							$goal_count	=	array_sum($session_count);

							GoogleGoalCompletion::create([
								'view_id'=>$viewid,
								'request_id'=>$campaignId,
								'user_id'=>$user_id,
								'goal_count'=>$goal_count
							]);

						}

						$data = array('current_data' => $current_data, 'prev_data' => $prev_data,'goalCompletionData'=>$goalCompletionData);

						return $data;

					}else{
						return false;
					}
				}
			}
		} catch (\Exception $e) {
		//return $e->getMessage();
		}

	}

	public static function googleSearchConsole($campaignId){
		try{
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		$getUser = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaignId)->first();	
		if(!empty($getUser)){
			$getAnalytics  = GoogleAnalyticsUsers::where('user_id',$user_id)->where('id',$getUser->google_console_id)->first();

			if(!empty($getAnalytics)){
				$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);

				$refresh_token  = $getAnalytics->google_refresh_token;

				/*if refresh token expires*/
				if ($client->isAccessTokenExpired()) {
					GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
				}

				$getAnalyticsId = SemrushUserAccount::with('google_search_account')->where('user_id',$user_id)->first();

		// 
				if(isset($getAnalyticsId->google_search_account)){
					$analyticsCategoryId = $getAnalyticsId->google_search_account->category_id;
					
					$analytics = new \Google_Service_Analytics($client);
					$profileUrl = GoogleAnalyticsUsers::getProfileUrl($analytics, $analyticsCategoryId);

					$start_date = date('Y-m-d', strtotime("-3 months", strtotime(date('Y-m-d'))));
					$end_date = date('Y-m-d');


		// echo '<pre>';
		// print_r($start_date);
		// print_r($end_date);
		// die;

					$search_console_query = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$start_date,$end_date);
					$search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$start_date,$end_date);
					$search_console_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$start_date,$end_date);
					$search_console_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$start_date,$end_date);
					$search_console_searchData = GoogleAnalyticsUsers::getSearchConsoleSearchAppearance($client,$profileUrl,$start_date,$end_date);



					$data = array('query' => $search_console_query, 'pages' => $search_console_page, 'countries' => $search_console_country, 'device' => $search_console_device);
					return $data;
				}else{
					return false;
				}

			}
		}
	} catch (\Exception $e) {
			//return $e->getMessage();
	}
}


public function organic_keywords(Request $request) {
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
	
	// echo '<pre>';
	// print_r($keywords);
	// die;
	
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

public function dfs_locations(){
	$client = null;
	try {
		$client = $this->DFSAuth();
		$locations = $this->locations($client);
		if(!empty($locations) && isset($locations['tasks'][0]['result'])){
			foreach($locations['tasks'][0]['result'] as $location){
				KeywordLocationList::create([
					'loc_id'=>$location['location_code'],
					'loc_id_parent'=>$location['location_code_parent'],
					'loc_name'=>$location['location_name'],
					'loc_name_canonical'=>$location['location_name'],
					'loc_type'=>$location['location_type'],
					'loc_country_iso_code'=>$location['country_iso_code']
				]);
			}
		}
		return 'success';
	} catch (RestClientException $e) {
		$error = (json_decode($e->getMessage(), true));
	}
	
	
	
}

public function ajax_dfs_locations(Request $request){
	$search_value = $request['query']['term'];
	$results = KeywordLocationList::where('loc_name_canonical','like', '%' . $search_value . '%')->limit(10)->get();
	$res = array();
	if(!empty($results) && count($results)>0){
		foreach($results as $key=>$result){
			$res[$key]['name'] = $result['loc_name_canonical'];
			$res[$key]['id'] = $result['id'];
		}
	} 
	echo json_encode($res);
}

public function ajax_dfs_keyword_tracking (Request $request){
//	dd($request->all());

	$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
	$user_package = User::get_user_package($user_id); 
	$used_keywords = KeywordSearch::where('user_id',$user_id)->count();
	$keywords_left = $user_package->keywords - $used_keywords;
	
	if($keywords_left <= 0){
		$response['status'] = '2'; 
		$response['error'] = '1';
		$response['message'] = 'You have reached your keyword limit.';
		return response()->json($response);
	}

	$data = $request->all();


	$url=rtrim($data['domain_url'], '/');
	if ( substr($url, 0, 8) == 'https://' ) {
		$url = substr($url, 8);
	}
	if ( substr($url, 0, 7) == 'http://' ) {
		$url = substr($url, 7);
	}
	if ( substr($url, 0, 4) == 'www.' ) {
		$url = substr($url, 4);
	}
	if ( strpos($url, '/') !== false ) {
		$explode = explode('/', $url);
		$url     = $explode['0'];
	}
	$data['domain_url'] = $url;
	$finalstring = array_map('trim', explode(PHP_EOL, strtolower($data['keyword_ranking'])));
	
	if(count($finalstring) > $keywords_left){
		$response['status'] = '2'; 
		$response['error'] = '1';
		$response['message'] = 'You have '.$keywords_left.' keyword(s) left.';
		return response()->json($response);
	}
	
	


	$getDataByKeyword = $this->getDataByKeyword($data);
	
	
	if($getDataByKeyword <> null && $getDataByKeyword <> ''){
		$newKeywords = array_diff($finalstring,$getDataByKeyword);
	}else{
		$newKeywords = $finalstring;
	}
	
	
	if(count($newKeywords) == 0){
		$response['status'] = '0'; // Insert Data Done
		$response['error'] = '1';
		$response['message'] = 'Already Added';
		return response()->json($response);
	}
	
	$get_country = explode(',', $data['locations']);	
	
	/*posting data on dfs for search volume*/
	$client = null;
	$searchVolumeArr = array();
	try {
		$client = $this->DFSAuth();

		$live_data_array[] = array(
			//"location_name" => $get_country[count($get_country) - 1],
			"language_name" => $data['language'],
			"keywords" => $newKeywords,
		);



		$searchVolumeArr = $client->post('/v3/keywords_data/google/search_volume/live', $live_data_array);
		//dd($searchVolumeArr);
		unset($live_data_array);
		
	} catch (RestClientException $e) {
		return json_decode($e->getMessage(), true);
	}
	
	$searchVolumeData = $searchVolumeArr['tasks'][0]['result'];
	$resultCount = 0;
	
	KeywordSearch::where('request_id',$data['campaign_id'])->update(['is_sync'=>'1']); 

	foreach($newKeywords as $keywrd) {	
		
		if($searchVolumeData !=null){

			$svKey = array_search(trim($keywrd), array_column($searchVolumeData, 'keyword'));
			$competition = $searchVolumeData[$svKey]['competition'];
			$search_volume = $searchVolumeData[$svKey]['search_volume'];
			
			/*data insertion*/
			$keywordinsert = 	KeywordSearch::create([
				'user_id'=>$user_id,
				'request_id'=>$data['campaign_id'],
				'keyword'=>$keywrd,
				'cmp'=>$competition,
				'sv'=>$search_volume,
				'result_url'=>'http://'.$data['domain_url'].'/',
				'url_site'=>'http://'.$data['domain_url'].'/',
				'tracking_option'=>$data['tracking_options'],
				'host_url'=>$data['domain_url'],
				'language'=>$data['language'],
				'region'=>$data['search_engine_region'],
				'canonical'=>$data['locations'],
				'lat'=>$data['lat'],
				'long'=>$data['long']
			]);
			if($keywordinsert){
				$getLastId = $keywordinsert->id;
				
				$api_data = array(
					'request_id'	=> 	$data['campaign_id'],
					'keyword_name'	=>	$keywrd,
					'domain_name'	=>	$url,
					'api_name'		=>	'New Keyword - Search Volumne Api',
					'user_id' => $user_id
				);
				
				$this->insertDataSeoApiUnit($api_data);
				unset($api_data);
				$resultCount++;
				
				$post_array[] = array(
					"language_name" => $data['language'],
				//	"location_name" => $data['locations'],
					"location_coordinate" => $data['lat'].','.$data['long'],
					"se_domain" => $data['search_engine_region'],
					"domain" => $data['domain_url'],
					"keyword" => mb_convert_encoding($keywrd, "UTF-8"),
					"priority" => 2,
					"postback_data" => "advanced",
					"postback_url" => url('/postbackAddKeyResponse?request_id='.$data["campaign_id"].'&keyword_id='.$getLastId.'&user_id='.$user_id)
				);

				$result = $client->post('/v3/serp/google/organic/task_post', $post_array);
				unset($post_array);
			}
		}
	}
	
	if ($resultCount > 0) { //if keyword count is more
		$response['status'] = '1'; 
		$response['error'] = '0';
		$response['message'] = 'Keyword Added Successfully';
	} else {
		$response['status'] = '0'; 
		$response['error'] = '0';
		$response['message'] = 'Already Added';
	}
	
	
	return response()->json($response);
	
}

private function getDataByKeyword($data){
	$keywords = trim($data['keyword_ranking']);
	$keywords =	strtolower(preg_replace('~[\r\n\t]+~', ',', $keywords));
	$str_array = 	explode(',', $keywords);
	$finalstring = 	array_map('trim', $str_array);
	
	$result = KeywordSearch::
	select('keyword')
	->where('request_id',$data['campaign_id'])
	->whereIn('keyword',$finalstring)
	->where('region',trim($data['search_engine_region']))
	->where('tracking_option',trim($data['tracking_options']))
	->where('canonical',$data['locations'])
	->orderBy('id','desc')
	->get()->toArray();
	
	
	
	if(!empty($result)){
		$results = array_column($result, 'keyword');
		return	$results;
	} else{
		return false;
	}
}

private function insertDataSeoApiUnit($api_data){
	DataforseoApiUnit::create([
		'user_id'=>$api_data['user_id'],
		'keyword'=>$api_data['keyword_name'],
		'request_id'=>$api_data['request_id'],
		'domain_name'=>$api_data['domain_name'],
		'api_name'=>$api_data['api_name']
	]);
	
}

public function postbackAddKeyResponse(Request $request){
	$post_arr = json_decode(gzdecode($request->getContent()),true);
	/*file_put_contents(dirname(__FILE__)."/logs/postbackAddKeyResponse.txt", print_r($post_arr,true));*/
	$results = KeywordSearch::where('id',$request['keyword_id'])->orderBy('created_at','desc')->first();
	
	if($results){
		$keyValue = $results->host_url;
		$url_type = $results->url_type;
		$ignore_local_listing = $results->ignore_local_listing;
		$new = array_filter($post_arr['tasks'][0]['result'][0]['items'], function($value) use ($keyValue,$url_type,$ignore_local_listing) {
			if($ignore_local_listing == 1){
				if(strpos(strtolower($value['type']), strtolower('organic')) !== FALSE || strpos(strtolower($value['type']), strtolower('featured_snippet')) !== FALSE){

					// if($url_type == 2){
					// 	$hostUrl = parse_url($value['url'],PHP_URL_HOST);
					// 	$domain_name = preg_replace('/^www\./', '', $hostUrl);

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

					// if($url_type == 2){
					// 	$hostUrl = parse_url($value['url'],PHP_URL_HOST);
					// 	$domain_name = preg_replace('/^www\./', '', $hostUrl);

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
			}
		});	

		$newKey = array_keys($new);
		if($newKey){
			$key = $newKey[0];
		}else{
			$key = null;
		}


		$rank_group = $url = $title ='';
		if($key >= 0 && $key !== null){
			$rank_group = $post_arr['tasks'][0]['result'][0]['items'][$key]['rank_group'];
			$url = $post_arr['tasks'][0]['result'][0]['items'][$key]['url'];
			$title = $post_arr['tasks'][0]['result'][0]['items'][$key]['title'];
		} else{
			$url = KeywordSearch::getFilteredUrl('https://'.$keyValue);
		}

		$arra = [
			'se_results_count'=>$post_arr['tasks'][0]['result'][0]['se_results_count'],
			'result_se_check_url'=>$post_arr['tasks'][0]['result'][0]['check_url'],
			'start_ranking'=>$rank_group,
			'url_site'=>$url,
			'position'=>$rank_group,
			'result_url'=>$url,
			'result_title'=>$title,
			'is_sync'=>1
		];

		$update = KeywordSearch::where('id',$request['keyword_id'])->update($arra);
		$position_type = 0;
		
		if($key >= 0 && $key !== null){
			$position=	$post_arr['tasks'][0]['result'][0]['items'][$key]['rank_group'];

			if($post_arr['tasks'][0]['result'][0]['items'][$key]['type'] == 'featured_snippet'){
				$position_type = 2;
			}elseif($post_arr['tasks'][0]['result'][0]['items'][$key]['type'] == 'local_pack'){
				$position_type = 1;
			}
		}else{
			$position ='';
			$position_type = 0;
		}

		
		$params	= array(
			'request_id'	=>  $request['request_id'],
			'keyword_id'	=>	$request['keyword_id'],
			'position_type'		=>  $position_type,
			'position'		=>  $position,
			'updated_at'	=>	now()
		);	
		$this->addKeywordPosition($params);
		sleep(1);
		$this->updateRanking($request['request_id'],$request['keyword_id'],$request['user_id']);
		$this->keywordsData($request['request_id']);
		KeywordSearch::update_notification_status($request['request_id']);
	} 
}

private function addKeywordPosition($data){
	$insert = KeywordPosition::create([
		'request_id' => $data['request_id'],
		'keyword_id' => $data['keyword_id'],
		'position_type' => $data['position_type'],
		'position' =>  $data['position'],
		'updated_at' =>	now()
	]);
	if($insert){
		return true;
	}else{
		return false;
	}
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
	if((!empty($results->position) && $results->start_ranking <> null && $results->start_ranking > 0) && (!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0)){
		$lifeTime = (int) $results->start_ranking - (int) $currentPostion->position;
	}elseif((!empty($results->position) && $results->start_ranking == null || $results->start_ranking == 0) && (!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0)){
		$lifeTime = 100 - (int) $currentPostion->position;
	}elseif((!empty($results->position) && $results->start_ranking <> null && $results->start_ranking > 0) && (!empty($currentPostion->position) && $currentPostion->position == null || $currentPostion->position == 0)){
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


		//$user_id = Auth::user()->id;
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

public function ajaxLiveKeywordTrackingData (Request $request){
	$base_url =  url('/');
	$role_id = User::get_user_role(Auth::user()->id);
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
//dd($row->currentPosition);

		if($row->currentPosition  < 100){
			$pageNo = $row->currentPosition/10;    
			if($pageNo <= 1){
				$pages = 1;
			}elseif($pageNo <= 2){
				$pages = 2;
			}elseif($pageNo <= 3){
				$pages = 3;
			}elseif($pageNo <= 4){
				$pages = 4;
			}elseif($pageNo <= 5){
				$pages = 5;
			}elseif($pageNo <= 6){
				$pages = 6;
			}elseif($pageNo <= 7){
				$pages = 7;
			}elseif($pageNo <= 8){
				$pages = 8;
			}elseif($pageNo <= 9){
				$pages = 9;
			}elseif($pageNo <= 10){
				$pages = 10;
			}else{
				$pages = '<i class="fa fa-angle-right" aria-hidden="true"></i> 10</span>';
			}
		}else{
			$pages = '<i class="fa fa-angle-right" aria-hidden="true"></i> 10</span>';
		} 




		$flag = KeywordLocationList::flagsByCode($row->canonical);
		if(!empty($flag)){
			$flagValue = strtolower($flag->loc_country_iso_code);
			$flagData = $base_url.'/public/flags/'.$flagValue.'.png';
			$flag_img = '<img src='.$flagData.'>';
		}else{
			$flag_img = '';
		}

		$sub_array = array();
		if($row->is_favorite == 1){
			$star = '<i class="fa fa-star"></i>';
		}else{
			$star = '<i class="fa fa-star-o"></i>';
		}
			// $sub_array[] = (int) $row->currentPosition > 0 ? $row->currentPosition : 101;

		if($role_id != 4){
			$checkbox = '<div class="my-checkbox"><label><input type="checkbox" name="check_list[]" value="'.$row->id.'" /><span class="checkbox"></span></label></div>';
			$starData = '<button type="button" class="chart-icon-star" style="float: right;" data-index="'.$row->request_id.'"  data-id="'.$row->id.'">'.$star.'</button> ';
		}
		else{
			$checkbox = $starData = '';
		}

		$sub_array[] = $flag_img.'<i class="pe-7s-map-marker" data-toggle="tooltip" title="'.$row->canonical.'" ></i>'.$row->keyword.'<a href="'.$row->result_se_check_url.'" target="_blank" style="float: right;"><i class="fa fa-search" aria-hidden="true"></i></a> <button type="button" class="chart-icon" style="float: right;" data-index="'.$row->request_id.'"  data-id="'.$row->id.'"><i class="fa fa-bars"></i></button>'.$starData;


		$sub_array[] = $row->startPosition <> null && $row->startPosition < 100  ? '<span class="serpTd" data-id="'.$row->id.'" data-value="'.$row->startPosition.'" >'.$row->startPosition.'</span>' : '<span class="serpTd" data-id="'.$row->id.'" data-value="'.$row->startPosition.'" > <i class="fa fa-angle-right" aria-hidden="true"></i> 100</span>' ;
		$sub_array[] = $pages;
		$sub_array[] = $row->currentPosition <> null && $row->currentPosition < 100   ? $row->currentPosition : '<i class="fa fa-angle-right" aria-hidden="true"></i> 100';
		$sub_array[] = $row->oneDayPostion <> 0 ? $row->oneDayPostion > 0 ? $row->oneDayPostion.'<i class="fa fa-arrow-up"></i>' : trim($row->oneDayPostion,'-').'<i class="fa fa-arrow-down"></i>' : '-' ;
		$sub_array[] = $row->weekPostion <> 0 ? $row->weekPostion > 0 ? $row->weekPostion.'<i class="fa fa-arrow-up"></i>' : trim($row->weekPostion,'-').'<i class="fa fa-arrow-down"></i>' : '-' ;
		$sub_array[] = $row->monthPostion <> 0 ? $row->monthPostion > 0 ? $row->monthPostion.'<i class="fa fa-arrow-up"></i>' : trim($row->monthPostion,'-').'<i class="fa fa-arrow-down"></i>' : '-' ;
		$sub_array[] = $row->lifeTime <> 0 ? $row->lifeTime > 0 ? $row->lifeTime.'<i class="fa fa-arrow-up"></i>' : trim($row->lifeTime,'-').'<i class="fa fa-arrow-down"></i>' : '-' ;
		$sub_array[] = number_format((float)$row->cmp, 2, '.', '');
		$sub_array[] = $row->sv;
		$sub_array[] = date('d-M-Y', strtotime($row->created_at));


		$sub_array[] = '<a href="'.$row->result_url.'" target="_blank" title="'.$row->result_url.'">'. parse_url($row->result_url,PHP_URL_PATH).'</a>';
		$sub_array[] = $checkbox; 
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

public function ajaxgetLatestKeyword(Request $request){
	$getSerpValue = KeywordSearch::
	where('is_sync','0')
	->where('request_id',$request['request_id'])
	->count();	
	// echo '<pre>';
	// print_r($getSerpValue);
	// die;
	
	$response['sync']	= $getSerpValue;	
	$response['status'] = '1'; // Insert Data Done
	$response['error'] = '0';
	
	return response()->json($response);
}

private function checkKeywordUpdated($request_id){
	KeywordSearch::where('request_id',$request_id)->where('task_id','!=','')->update([
		'is_flag'=> '0'
	]);

	$select = KeywordSearch::where('request_id',$request_id)->where('is_flag','1')->orderBy('is_favorite','desc')->orderBy('position','asc')->get();
	
	if(!empty($select)){
		return $select;
	}else{
		return false;
	}

}

private function getSerpValue($request_id){		
	$results = KeywordSearch::
	leftJoin('keyword_positions', function($join) {
		$join->on('keyword_searches.id', '=', 'keyword_positions.keyword_id');
	})
	->select('keyword_searches.*',DB::raw('MAX(keyword_positions.id) AS max_id'),'keyword_positions.position AS Position','keyword_id')
	->groupBy('keyword_positions.request_id')
	->where('keyword_searches.request_id',$request_id)
	->orderBy('keyword_searches.is_favorite','desc')
	->orderBy('keyword_searches.position','asc')
	->get();	
	if(!empty($results)) {
		return	$results; 
	}else{
		return false;
	}	
}

public function ajaxUpdateTimeAgo(Request $request){
	$result  = KeywordPosition::getLastUpdateKeyword($request->request_id);
	// $now = Carbon::now();
	// $time_span = $now->diffInHours($result);
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

public function ajax_mark_keyword_favorite(Request $request){
	$result = KeywordSearch::
	where('request_id',$request['request_id'])
	->where('id',$request['keyword_id'])
	->orderBy('id','desc')
	->first();
	
	if(isset($result) && !empty($result)){
		if($result->is_favorite == 0 || $result->is_favorite == null){
			$fav	=	'1';
			$msg = 'Keyword has been marked Favorite';
		}else{
			$fav	=	'0';
			$msg = 'Keyword has been marked unfavorite';
		}
		
		$update = KeywordSearch::where('id',$result->id)->update([
			'is_favorite'=>$fav
		]);
		
		if($update){
			$response['status'] = '1'; // Insert Data Done
			$response['error'] = '0';
			$response['message'] = $msg;

		}else{
			$response['status'] = '0'; // Insert Data Done
			$response['error'] = '0';
			$response['message'] = 'Please try again';
		}
	}else{
		$response['status'] = '0'; // Insert Data Done
		$response['error'] = '0';
		$response['message'] = 'Please try again';
	}
	return response()->json($response);
}

public function ajax_update_tracking(Request $request){

	KeywordSearch::where('request_id',$request['request_id'])->update(['is_sync'=>'1']);
	// $user_id = Auth::user()->id;
	$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
	$results = KeywordSearch::getKeywordsData($request['selected_ids']);
	//dd($results);

	$update = KeywordSearch::updateKeywordsData($request['selected_ids']);


	$client = null;
	try {
		$client = $this->DFSAuth();
	} catch (RestClientException $e) {
		return json_decode($e->getMessage(), true);
	}

	$post_array = array();
	if(isset($results) && !empty($results)){
		foreach($results as $key=>$val){
			if($val->tracking_option == 'mobile'){
				$option_text = 'mobile';
			}else{
				$option_text ='';
			}

			if(!empty($val->lat) && !empty($val->long)){
				$location = $val->lat.','.$val->long;
				$locationType = "location_coordinate";
			}else{
				$location = $val->canonical;
				$locationType = "location_name";
			}

			$post_array[] = array(
				"language_name" => $val->language,
				$locationType => $location,
				"se_domain" => $val->region,
				"domain" => $val->host_url,
				"keyword" => mb_convert_encoding($val->keyword, "UTF-8"),
				"priority" => 2,
				"postback_data" => "advanced",
				"postback_url" => url('/fetching_updated_keywords?request_id='.$request["request_id"].'&data_id='.$val->id.'&user_id='.$user_id)
			);
		}
	}
	
	if (count($post_array) > 0) {
		try {
			$task_post_result = $client->post('/v3/serp/google/organic/task_post', $post_array);

			//$post_array = array();
			$response['status'] = '1'; // Insert Data Done
			$response['error'] = '0';
			$response['message'] = 'Keyword updated Successfully';
		} catch (RestClientException $e) {
			$response['status'] = '2'; // Insert Data Done
			$response['error'] = '2';
			$response['message'] = $e->getMessage();
		}
	}else {
		$response['status'] = '1'; // Insert Data Done
		$response['error'] = '0';
		$response['message'] = 'Not Found!';
	}
	
	return response()->json($response);
}

public function fetching_updated_keywords(Request $request){
	$post_arr = json_decode(gzdecode($request->getContent()),true);	
	file_put_contents(dirname(__FILE__).'/logs/update_keywords.txt',print_r($post_arr,true));

	$keywordSearch = KeywordSearch::where('id',$request['data_id'])->orderBy('created_at','desc')->first();
	
	$update_keywordPosition = KeywordPosition::where('keyword_id',$request['data_id'])->where('request_id',$request['request_id'])->whereDate('updated_at',date('Y-m-d',strtotime('-1 day')))->update([
		'status'=> '1',
		'updated_at'=>date('Y-m-d H:i:s',strtotime('-1 day'))
	]);
	
	$results = KeywordPosition::
	where('keyword_id',$request['data_id'])
	->where('request_id',$request['request_id'])
	->whereDate('updated_at',date('Y-m-d',strtotime('-1 day')))
	->orderBy('created_at','desc')
	->first();
	
	
	$keyValue = $keywordSearch->host_url;
	$url_type = $keywordSearch->url_type;
	$ignore_local_listing = $keywordSearch->ignore_local_listing;
	
	$new = array_filter($post_arr['tasks'][0]['result'][0]['items'], function($value) use ($keyValue,$url_type,$ignore_local_listing) {
		if($ignore_local_listing == 1){
			if(strpos(strtolower($value['type']), strtolower('organic')) !== FALSE || strpos(strtolower($value['type']), strtolower('featured_snippet')) !== FALSE){

				// if($url_type == 2){
				// 	$hostUrl = parse_url($value['url'],PHP_URL_HOST);
    // 				$domain_name = preg_replace('/^www\./', '', $hostUrl);

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

				// if($url_type == 2){
				// 	$hostUrl = parse_url($value['url'],PHP_URL_HOST);
    // 				$domain_name = preg_replace('/^www\./', '', $hostUrl);

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
		
		KeywordSearch::where('id',$request['data_id'])->update([
			'result_se_check_url' =>$post_arr['tasks'][0]['result'][0]['check_url'],
			'result_url'=>$post_arr['tasks'][0]['result'][0]['items'][$key]['url'],
			'url_site'=>$post_arr['tasks'][0]['result'][0]['items'][$key]['url'],
			'result_title'=>$post_arr['tasks'][0]['result'][0]['items'][$key]['title'],
			'position'=>$post_arr['tasks'][0]['result'][0]['items'][$key]['rank_group'],
			'is_sync'=>'1'
		]);
		
		$params	= array(
			'request_id'	=> $request['request_id'],
			'keyword_id'	=>	$request['data_id'],
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
			'keyword_id'	=>	$request['data_id'],
			'position_type'	=>	0,
			'position'		=>	0,
			'updated_at'	=>	now()
		);
	}
	
	

	$this->addKeywordPosition($params);
	$this->updateRanking($request['request_id'],$request['data_id'],$request['user_id']);
	$this->keywordsData($request['request_id']);
	KeywordSearch::update_notification_status($request['request_id']);
	KeywordAlert::update_keyword_alert_status($request['request_id'],$request['user_id']);
}

public function ajax_delete_multiple_keywords(Request $request){
	$search = KeywordSearch::whereIn('id', $request['selected_ids'])->delete();
	$response = array();
	if($search){
		$keywordPosition = KeywordPosition::whereIn('keyword_id',$request['selected_ids'])->delete();
		if($keywordPosition){
			$response['status'] = '1'; 
			$response['error'] = '0';
			$response['message'] = 'Keyword(s) deleted successfully';
		}else{
			$response['status'] = '2'; 
			$response['error'] = '2';
			$response['message'] = 'Getting Error deleting keyword(s)';
		}
		
	}
	return response()->json($response);
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

public function ajax_update_keyword_startRank(Request $request){
	$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
	// $user_id = Auth::user()->id;
	$response = array();
	$update = KeywordSearch::where('id',$request['request_id'])->update([
		'start_ranking' =>$request['start_ranking']
	]);
	
	if($update){
		$find = KeywordSearch::findorfail($request['request_id']);

		if($find){
			$this->updateRanking($find->request_id,$find->id,$user_id);
			
			$response['status'] = '1'; 
			$response['error'] = '0';
			$response['message'] = 'Start rank Data updated successfully';
		}
	}else{
		$response['status'] = '2'; 
		$response['error'] = '2';
		$response['message'] = 'Getting Error while updating, Try again.';	
	}
	
	return response()->json($response);
}

public function ajax_dfs_extra_organic_keywords(Request $request){
	
	// $getDomainDetails = SemrushUserAccount::where('id',$request['request_id'])->first();
	// $user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
	// $domain_name = $getDomainDetails->domain_url;
	$domainDetails = SemrushUserAccount::where('status','0')->select('id','user_id','domain_url')->orderBy('id','desc')->get();
	if(!empty($domainDetails)){
		foreach($domainDetails  as $details){
			$results = SemrushOrganicSearchData::where('request_id',$details->id)->whereDate('created_at',date('Y-m-d'))->first();
			
			$removeChar = ["https://", "http://", "/","www."];
			$http_referer = str_replace($removeChar, "", $details->domain_url);
			//dd($results);


			if(empty($results)){
				$client = null;
				$client = $this->DFSAuth();
				$post_arrays[] = array(
					"target" => $http_referer,
					"language_name" => "English",
					"location_code" => 2840,
					"filters" => [
						["keyword_data.keyword_info.search_volume", "<>", 0],
						"and",
						[
							["ranked_serp_element.serp_item.type", "<>", "paid"],
							"or",
							["ranked_serp_element.serp_item.is_malicious", "=", false]
						]
						],"

						order_by" => ["keyword_data.serp_item.rank_group,ASC","keyword_data.keyword_info.search_volume,desc"],
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
								$results = 	SemrushOrganicSearchData::where('user_id',$details->user_id)->where('request_id',$details->id)->where('keywords',$value['keyword_data']['keyword'])->orderBy('id','desc')->first();

								if($results <> null){
									$last_id =	$results->id;
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
				}
		// else{

		// 	$error_array = array(
		// 		'status_message' => $ranked_keywords['tasks'][0]['status_message'],
		// 		'status_code' => $error = $ranked_keywords['tasks'][0]['status_code'],
		// 	);
		// 	return response()->json($error_array);

		// }
			}	
		}
	}	
}


public function DFSKeywordsLog($user_id,$campaign_id){
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

public function ajax_googleAnalyticsGoal(Request $request){
	
	$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
	$goalCompletion = GoogleGoalCompletion::
	where('request_id',$request['campaignId'])
	->where('user_id',$user_id)
	->first();
	
	
	$goal = GoogleProfileData::
	select(DB::raw('SUM(goal_completions) AS total'))
	->where('request_id',$request['campaignId'])
	->where('user_id',$user_id)
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

public function ajax_traffic_growth_data(Request $request){
	$campaignId = $request['campaignId'];

	$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
	
	
	try{
		$getUser = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaignId)->first();

		$prev_session  = $current_pageView = $prev_pageView = $current_users = $prev_users = $from_dates=  $combine_session = $compare_status = $count_session = $current_period = $previous_period = '';
		
		$total_sessions	= $traffic_growth = $total_users = $total_pageview = 'N/A';
		$current_session = $final_Session = $final_users =  $final_pageView = '0';


		if(!empty($getUser)){
			$getCompareChart = ProjectCompareGraph::getCompareChart($campaignId);
			if(!empty($getCompareChart)){
				$compare_status = $getCompareChart->compare_status;
			}
			
			$sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'organic_traffic');
			//dd($sessionHistoryRange);

			if(empty($sessionHistoryRange)){

				$start_date = date('Y-m-d');
				$end_date =  date('Y-m-d', strtotime("-3 months", strtotime(date('Y-m-d'))));

				$prev_start_date = date('Y-m-d', strtotime("-3 months", strtotime($end_date)));


				$day_diff  = 	strtotime($end_date) - strtotime($start_date);
				$count_days    	=	floor($day_diff/(60*60*24));

				$start_data   =   date('Y-m-d', strtotime($end_date.' '.$count_days.' days'));

				$newDate = date('Y-m-d',strtotime('-1 day',strtotime($start_data)));
				$newEndDate = date('Y-m-d',strtotime('-1 day',strtotime($prev_start_date)));


				$current_period     =   date('d-m-Y', strtotime($end_date)).' to '.date('d-m-Y', strtotime($start_date));
				$previous_period    =   date('d-m-Y', strtotime($newEndDate)).' to '.date('d-m-Y', strtotime("-3 months", strtotime($newDate)));

			}else{

				$end_date   =	date('Y-m-d', strtotime($sessionHistoryRange->start_date));
				$start_date    =   date('Y-m-d', strtotime($sessionHistoryRange->end_date));

				$day_diff  = 	strtotime($sessionHistoryRange->start_date) - strtotime($sessionHistoryRange->end_date);
				$count_days    	=	floor($day_diff/(60*60*24));

				$start_data   =   date('Y-m-d', strtotime($sessionHistoryRange->start_date.' '.$count_days.' days'));

				$newDate = date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
				$newEndDate = date('Y-m-d',strtotime('-1 day',strtotime($start_data)));


				$current_period     =   date('d-m-Y', strtotime($start_date)).' to '.date('d-m-Y', strtotime($end_date));
				$previous_period    =   date('d-m-Y', strtotime($newEndDate)).' to '.date('d-m-Y', strtotime($newDate));					 
			}
			
			
			$getAnalytics  = GoogleAnalyticsUsers::accountInfoById($user_id,$getUser->google_account_id); 

			
			if(!empty($getAnalytics)){
				$status = 1;
				$client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);


				$refresh_token  = $getAnalytics->google_refresh_token;


				/*if refresh token expires*/
				if ($client->isAccessTokenExpired()) {
					GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
				}

				$getAnalyticsId = SemrushUserAccount::with('google_analytics_account')->where('id',$campaignId)->where('user_id',$user_id)->first();


				
				if(isset($getAnalyticsId->google_analytics_account)){
					$analyticsCategoryId = $getAnalyticsId->google_analytics_account->category_id;

					$analytics = new \Google_Service_Analytics($client);

					$profile = GoogleAnalyticsUsers::getFirstProfileId($analytics,$analyticsCategoryId);



					$removeMinus = str_replace('-','',$count_days);
					if($removeMinus >= 90){
						$current_data = GoogleAnalyticsUsers::getResultByWeek($analytics, $profile,$start_date,$end_date);	
						

						$output = array_column ($current_data->rows , 0);

						for($i=0;$i<count($output);$i++){
							$outputRes[] = $this->getStartAndEndDate($output[$i],date('Y'));
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
					//	dd($currentData);

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


						$today = date('Y-m-d');
						$yesterday = date('Y-m-d',strtotime('-1 days'));
						$beforeyesterday = date('Y-m-d',strtotime('-2 days'));

						$getCurrentStatsToday   =  GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$today,$yesterday);


						$getCurrentStatsYesterday   =  GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$yesterday,$beforeyesterday); 


						$TodayData = $getCurrentStatsToday->getRows();
						$YesterdayData = $getCurrentStatsYesterday->getRows();

						if(!empty($TodayData) && !empty($YesterdayData)){
							$usersPercent = number_format((@$getCurrentStatsToday[0][0]  - @$getCurrentStatsYesterday[0][0]) / @$getCurrentStatsYesterday[0][0] * 100, 2);	
						}else{
							$usersPercent = 0;
						}

						ActivityLog::trafficLog($campaignId,$usersPercent,$user_id);
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

private function getStartAndEndDate($week, $year) {
	$dto = new \DateTime();
	$dto->setISODate($year, $week);
	$ret = $dto->format('Y-m-d');
	return $ret;
}



private function closestDates($session_count, $finddate){
	$date = strtotime($finddate);
	$plot_month = date('Y,m', strtotime($finddate));
	foreach($session_count as $key=>$session_details) {
		$plot_dates[] 		=	strtotime($session_details->from_date);
		$plot_dates2[] 		=	strtotime($session_details->from_date);
		$check_dates = date('Y,m', strtotime($session_details->from_date));
	}
	sort($plot_dates);
	$end_date = '';
	foreach ($plot_dates as $val) {
		if ($val >= $date) {
			$end_date = $val;
			break;
		}
	}

	rsort($plot_dates2);
	$start_date = '';
	foreach ($plot_dates2 as $val) {
		if ($val <= $date) {
			$start_date = $val;
			break;
		}
	}

	$index = array_search($end_date, $plot_dates);
	$day_diff = $end_date - $start_date;
	$count = floor($day_diff/(60*60*24)) / 10;
	$index = $index - $count;
	return $index;
}


public function ajax_traffic_growth_date_range(Request $request){
	
	
	$range = $request['value'];
	$module = $request['module'];
	$request_id = $request['campaignId'];
	$today = date('Y-m-d');
	// $user_id = Auth::user()->id;
	$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
	$role_id = User::get_user_role(Auth::user()->id);
	
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
	
	$ifCheck = ModuleByDateRange::where('request_id',$request_id)->where('module',$module)->first();
	
	if($role_id != 4){
		if(empty($ifCheck)){
			ModuleByDateRange::create([
				'user_id'=>$user_id,
				'request_id'=>$request_id,
				'module'=>$module,
				'start_date'=>date('Y-m-d', strtotime($start_date)),
				'end_date'=>date('Y-m-d', strtotime($today))
			]);
		}else{
			ModuleByDateRange::where('id',$ifCheck->id)->update([
				'user_id'=>$user_id,
				'request_id'=>$request_id,
				'module'=>$module,
				'start_date'=>date('Y-m-d', strtotime($start_date)),
				'end_date'=>date('Y-m-d', strtotime($today))
			]);
		}
	}
	
	$start_data = date('Y-m-d',strtotime($start_date));
	$end_data = date('Y-m-d',strtotime($today));
	
	$day_diff = strtotime($start_date) - strtotime($today);
	$count_days = floor($day_diff/(60*60*24));
	
	$new_start_date  =   date('Y-m-d', strtotime($start_date.' '.$count_days.' days'));

	$newDate = date('Y-m-d',strtotime('-1 day',strtotime($start_data)));
	$newEndDate = date('Y-m-d',strtotime('-1 day',strtotime($new_start_date)));
	
	
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
				$outputRes[] = $this->getStartAndEndDate($output[$i],date('Y'));
			}
			
			$previous_data =  GoogleAnalyticsUsers::getResultByWeek($analytics, $profile,$newDate,$newEndDate);
		}else{
			$results = GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$end_data,$start_data);
			
			$outputRes = array_column ($results->rows , 0);
			
			$previous_data =  GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$newDate,$newEndDate);
		}
		
		// dd($previous_data);
		
		$count_session = array_column ( $results->rows , 1);
		$from_dates  =  array_map(function($val) { return date("d M, Y", strtotime($val)); }, $outputRes);			
		$combine_session = array_column($previous_data->rows , 1);
		
		
		
		/*Comparison*/
		$currentData = GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$end_data,$start_data);
		$previousData = GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$newDate,$newEndDate);
		
		$current_period     =   date('d-m-Y', strtotime($start_data)).' to '.date('d-m-Y', strtotime($end_data));
		$previous_period     =   date('d-m-Y', strtotime($newEndDate)).' to '.date('d-m-Y', strtotime($newDate));
		
		
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
		//$outputs = json_encode($output,true);

		//file_put_contents(__DIR__.'/logs/logJson.txt', print_r($outputs));

	}
	
	return $output;
}


public function ajax_traffic_growth_chart_compare(Request $request){	

	$request_id = $request['request_id'];
	$compare_value = $request['compare_value'];
	$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
	// $user_id = Auth::user()->id;
	$ifExists = ProjectCompareGraph::getCompareChart($request_id);
	
	
	if(empty($ifExists)){
		ProjectCompareGraph::create([
			'request_id'=>$request_id,
			'user_id'=>$user_id,
			'compare_status'=>$compare_value
		]);
	}else{
		ProjectCompareGraph::where('id',$ifExists->id)->update([
			'request_id'=>$request_id,
			'user_id'=>$user_id,
			'compare_status'=>$compare_value
		]);
	}
	
	$moduleDates = ModuleByDateRange::getModuleDateRange($request_id,'organic_traffic');
	
	if(!empty($moduleDates)){
		$start_date = $moduleDates->start_date;
		$end_date = $moduleDates->end_date;
	}else{
		$start_date = date('Y-m-d',strtotime('-3 months',strtotime(date('Y-m-d'))));
		$end_date = date('Y-m-d');
	}
	
	
	$start_data = date('Y-m-d',strtotime($start_date));
	$end_data = date('Y-m-d',strtotime($end_date));
	$day_diff = strtotime($start_date) - strtotime($end_date);		
	
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
				$outputRes[] = $this->getStartAndEndDate($output[$i],date('Y'));
			}
			
			$previous_data =  GoogleAnalyticsUsers::getResultByWeek($analytics, $profile,$start_data,$new_start_date);
		}else{
			$results = GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$end_data,$start_data);
			
			$outputRes = array_column ($results->rows , 0);
			
			$previous_data =  GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$start_data,$new_start_date);
		}

		 // echo '<pre>';
		 // print_r($outputRes);
		 // die;

		$count_session = array_column ( $results->rows , 1);
		$from_dates  =  array_map(function($val) { return date("d M, Y", strtotime($val)); }, $outputRes);			
		$combine_session = array_column($previous_data->rows , 1);
		
		
		
		/*Comparison*/
		$currentData = GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$end_data,$start_data);
		$previousData = GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$start_data,$new_start_date);
		
		$current_period     =   date('d-m-Y', strtotime($start_data)).' to '.date('d-m-Y', strtotime($end_data));
		$previous_period     =   date('d-m-Y', strtotime($new_start_date)).' to '.date('d-m-Y', strtotime($start_data));
		
		
		$compareResult = ProjectCompareGraph::where('request_id',$request_id)->first();
		if(!empty($compareResult)){
			$compare = $compareResult->compare_status;
		}
		
		$output = array(
			'current_period'=>$current_period,
			'previous_period'=>$previous_period,
			'from_datelabel'=>$from_dates,
			'count_session'=>$count_session,
			'combine_session'=>$combine_session,
			'compare_status'=>$compare,
			'status'=>'success'
		);
	}
	return $output;		
}




public function ajax_googleSearchConsole(Request $request){
	
	$campaignId = $request['campaignId'];
	$userData = User::findorfail(Auth::user()->id);
	$role_id =User::get_user_role(Auth::user()->id);
	
	if($userData->parent_id !=''){
		$user_id = $userData->parent_id;
	}else{
		$user_id = Auth::user()->id;
	}
	$result = array();
	$module = $request['module']?:'';
	try{
		$getUser = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaignId)->first();	
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
					//$profileUrl = GoogleAnalyticsUsers::getProfileUrl($analytics, $analyticsCategoryId);
					// $profileUrl = GoogleAnalyticsUsers::getsearchProfileUrl($analytics, $analyticsCategoryId);
					//dd($profileUrl);
					$profileUrl = GoogleAnalyticsUsers::getDomainProfileUrl($campaignId);
					
					// echo "<pre>";
					// print_r($profileUrl);
					// die;
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




					if($role_id != 4){
						if(isset($module) && !empty($module) && $module=='search_console'){
							$ifCheck = ModuleByDateRange::where('request_id',$campaignId)->where('module',$module)->first();

							if(empty($ifCheck)){
								ModuleByDateRange::create([
									'user_id'=>$user_id,
									'request_id'=>$campaignId,
									'module'=>$module,
									'start_date'=>date('Y-m-d', strtotime($start_date)),
									'end_date'=>date('Y-m-d', strtotime($end_date))
								]);
							}else{
								ModuleByDateRange::where('id',$ifCheck->id)->update([
									'user_id'=>$user_id,
									'request_id'=>$campaignId,
									'module'=>$module,
									'start_date'=>date('Y-m-d', strtotime($start_date)),
									'end_date'=>date('Y-m-d', strtotime($end_date))
								]);
							}
						}	
					}
					$url = preg_replace('#^https?://#', '', rtrim($profileUrl,'/'));
					$search_console_query = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$start_date,$end_date);	


					$search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$start_date,$end_date);
					$search_console_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$start_date,$end_date);
					$search_console_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$start_date,$end_date);
					$searchConsoleData = GoogleAnalyticsUsers::getSearchConsoleData($client,$profileUrl,$start_date,$end_date);


					$query_html = $device_html =  $page_html = $country_html = '';
					$clicks = $impressions = $dates = array();

// dd($search_console_device);
					

					if(!empty($search_console_query)){

					// 	if($search_console_query['error']['code']!=''){
					// 	$result['query']	=	'';
					// }else{
						foreach($search_console_query->getRows() as $query){
							$query_html	.='
							<tr>
							<td>'.$query->keys[0].'</td>
							<td>'.$query->clicks.'</td>
							<td>'.$query->impressions.'</td>
							</tr>';

						}
						$result['query']	=	$query_html;
					// }
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
					// 	if($search_console_query['error']['code']!=''){
					// 	$result['clicks'] = array();
					// 	$result['impressions'] = array();
					// 	$result['status']=1;					
					// }else{
						foreach($searchConsoleData->getRows() as $data){
							$clicks[]    = array('t'=>strtotime($data->keys[0])*1000, 'y'=>$data->clicks);
							$impressions[] = array('t'=>strtotime($data->keys[0])*1000, 'y'=>$data->impressions);
						}

						
					// }
					}

					$result['clicks'] = $clicks;
					$result['impressions'] = $impressions;
					$result['status']=1;


					$click = $result['clicks'][count($result['clicks']) - 1];
					$impression = $result['impressions'][count($result['impressions']) - 1];


					$this->clicksLog($user_id,$campaignId,$click,$impression);

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

public function clicksLog($user_id,$campaignId,$click,$impression){

	$results = ActivityLog::where('request_id',$campaignId)->where('slug','clicks')->whereDate('created_at',date('Y-m-d'))->orderBy('id','desc')->get();
	if(isset($results) && count($results) == 0){

		if($click['y'] > 0 && $impression['y'] > 0){

			$desc = 'Today you have got <b class="activity-green">'.$click['y'].'</b> new clicks and <b class="activity-green">'.$impression['y'].'</b> impressions';
			ActivityLog::keywordsLogTracked($user_id,$campaignId,'clicks',$desc,'clicks');

		}
	}
}

public function ajax_update_keyword_data(Request $request){
	$ids[] = implode(',',$request['checked']);
	$update = KeywordSearch::whereIn('id',$request['checked'])->update([
		'region' =>$request['update_region'],
		'tracking_option' =>$request['update_tracking_options'],
		'language' =>$request['update_language'],
		'canonical' =>$request['update_location'],
		'lat'=>$request['lat'],
		'long'=>$request['long']
	]);

	if($update){
		$response['status'] = 1;
		$response['message']= 'Keyword(s) updated successfully.';
	} else{
		$response['status'] = 0;
		$response['message']= 'Error updating keyword(s)';
	}
	return response()->json($response);

}

}
