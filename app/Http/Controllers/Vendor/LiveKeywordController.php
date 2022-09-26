<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\KeywordSearch;
use App\KeywordPosition;
use App\DataforseoApiUnit;
use App\ActivityLog;
use App\User;
use DB;
use Auth;

use App\Exports\ExportKeywords;
use Maatwebsite\Excel\Facades\Excel;

use App\KeywordTag;
use App\SemrushUserAccount;
use App\KeywordLocationList;
use App\Language;
use App\RegionalDatabse;
use App\UserPackage;
use App\LiveKeywordSetting;
use App\Traits\ClientAuth;

use App\Views\ViewKeywordSearch;
use App\CampaignData;

class LiveKeywordController extends Controller {

	use ClientAuth;

	public function ajax_live_keyword_stats(Request $request){
		$campaign_id = $request['campaign_id'];
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
			DB::raw('sum(CASE WHEN ((position > 30 AND position <= 100)  AND (start_ranking = 0 or start_ranking > 100)) THEN 1 ELSE 0 END) AS since_hundred'),
			DB::raw('sum(CASE WHEN ((position <= 30 and position > 20)  AND (start_ranking > 30 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_thirty'),
			DB::raw('sum(CASE WHEN ((position <= 20 and position > 10)  AND (start_ranking > 20 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_twenty'),
			DB::raw('sum(CASE WHEN ((position <= 10 and position > 3) AND (start_ranking > 10 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_ten'),
			DB::raw('sum(CASE WHEN ((position <= 3 and position > 0) AND (start_ranking >= 4 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_three')
		)
		->where('request_id',$campaign_id)
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

	public static function live_keyword_stats($campaign_id){
		
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
			DB::raw('sum(CASE WHEN ((position > 30 AND position <= 100)  AND (start_ranking = 0 or start_ranking > 100)) THEN 1 ELSE 0 END) AS since_hundred'),
			DB::raw('sum(CASE WHEN ((position <= 30 and position > 20)  AND (start_ranking > 30 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_thirty'),
			DB::raw('sum(CASE WHEN ((position <= 20 and position > 10)  AND (start_ranking > 20 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_twenty'),
			DB::raw('sum(CASE WHEN ((position <= 10 and position > 3) AND (start_ranking > 10 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_ten'),
			DB::raw('sum(CASE WHEN ((position <= 3 and position > 0) AND (start_ranking >= 4 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_three')
		)
		->where('request_id',$campaign_id)
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
		
		return $output;

		// return response()->json($output);
	}
	
	public static function get_live_keyword_tracking($campaign_id,$sortBy,$sortType,$limit){
		// dd($sortType); 
		$searchData = KeywordSearch::select('*', 
			DB::raw('(CASE WHEN start_ranking > 0  OR start_ranking != null THEN start_ranking ELSE 150 END) AS startPosition')	,
			DB::raw('(CASE WHEN position > 0  OR position != null THEN position ELSE 150 END) AS currentPosition'),
			DB::raw('(CASE WHEN oneday_position <> 0  OR oneday_position != null THEN oneday_position ELSE 0 END) AS oneDayPostion'),
			DB::raw('(CASE WHEN one_week_ranking <> 0  OR one_week_ranking != null THEN one_week_ranking ELSE 0 END) AS weekPostion'),
			DB::raw('(CASE WHEN monthly_ranking <> 0  OR monthly_ranking != null THEN monthly_ranking ELSE 0 END) AS monthPostion'),
			DB::raw('(CASE WHEN life_ranking <> 0  OR life_ranking != null THEN life_ranking ELSE 0 END) AS lifeTime')
		)
		->where('request_id',$campaign_id)
		->orderBy('is_favorite','desc')
		->orderBy($sortBy,$sortType)
		->paginate($limit);

		return $searchData;
	}


	public function ajax_live_keyword_list(Request $request){
		
		if($request->ajax())
		{
			$checked_id = ''; $selected_type = 'all';
			$sortBy = $request['column_name'];
			$sortType = $request['order_type'];
			$limit = $request['limit'];	
			$campaign_id = $request['campaign_id'];
			$query = $request['query'];
			$state	= ($request->has('key'))?'viewkey':'user';
			if($request->has('checked_id')){
				$checked_id = $request->checked_id;
			}
			$filter_tag = $request['tag_id'];
			$tracking_type = $request->tracking_type;
			if($request->has('selected_type')){
				$selected_type = $request->selected_type;
			}
			
			if($state === 'viewkey'){
				$table_settings = LiveKeywordSetting::where('viewkey',0)->where('request_id',$campaign_id)->pluck('heading')->all();
			}else{
				$table_settings = LiveKeywordSetting::where('detail',0)->where('request_id',$campaign_id)->pluck('heading')->all();
			}

			$live_keywords = $this->live_keyword_data($limit,$campaign_id,$sortBy,$sortType,$query,$filter_tag,$tracking_type,$selected_type);
			// return response()->json($live_keywords);
			return view('vendor.seo_sections.live_keyword.table', compact('live_keywords','campaign_id','state','checked_id','table_settings'))->render();
		}
	}

	public static function ajax_live_keyword_listpdf($limit,$campaign_id,$sortBy,$sortType,$query,$filter_tag){

		$field = ['keyword','cmp','sv','start_ranking','one_week_ranking','monthly_ranking','life_ranking'];
		$searchData = KeywordSearch::select('*', 
			DB::raw('(CASE WHEN start_ranking > 0  OR start_ranking != null THEN start_ranking ELSE 150 END) AS startPosition')	,
			DB::raw('(CASE WHEN position > 0  OR position != null THEN position ELSE 150 END) AS currentPosition'),
			DB::raw('(CASE WHEN oneday_position <> 0  OR oneday_position != null THEN oneday_position ELSE 0 END) AS oneDayPostion'),
			DB::raw('(CASE WHEN one_week_ranking <> 0  OR one_week_ranking != null THEN one_week_ranking ELSE 0 END) AS weekPostion'),
			DB::raw('(CASE WHEN monthly_ranking <> 0  OR monthly_ranking != null THEN monthly_ranking ELSE 0 END) AS monthPostion'),
			DB::raw('(CASE WHEN life_ranking <> 0  OR life_ranking != null THEN life_ranking ELSE 0 END) AS lifeTime')
		)
		->where('request_id',$campaign_id)
		// ->where('is_sync','asc')
		->orderBy('is_favorite','desc')
		->orderBy($sortBy,$sortType)
		->where(function ($dta) use($query, $field) {
			for ($i = 0; $i < count($field); $i++){
				$dta->orwhere($field[$i], 'LIKE',  '%' . $query .'%');
			}      
		});
		if($filter_tag != null && $filter_tag !=''){
			$searchData->whereRaw("find_in_set('".$filter_tag."',keyword_searches.keyword_tag_id)");
		}
		
		if($limit == 'All'){
			$data_count = $searchData->count();
			$searchData = $searchData->get();			
		}else{
			$searchData = $searchData->paginate($limit);
		}
		
		return $searchData;

	}


	public function ajax_live_keyword_pagination(Request $request){
		if($request->ajax())
		{
			$selected_type = 'all';
			$sortBy = $request['column_name'];
			$sortType = $request['order_type'];
			$limit = $request['limit'];	
			$campaign_id = $request['campaign_id'];
			$query = $request['query'];
			$filter_tag = $request['tag_id'];
			$tracking_type = $request->tracking_type;
			
			if($request->has('selected_type')){
				$selected_type = $request->selected_type;				
			}

			$live_keywords = $this->live_keyword_data($limit,$campaign_id,$sortBy,$sortType,$query,$filter_tag,$tracking_type,$selected_type);
			return view('vendor.seo_sections.live_keyword.pagination', compact('live_keywords','campaign_id'))->render();
		}
	}


	private function live_keyword_data($limit,$campaign_id,$sortBy,$sortType,$query,$filter_tag,$tracking_type,$selected_type){
		$sortType = $sortType !== 'undefined' ? $sortType : 'asc' ;
		$sortBy = $sortBy !== 'undefined' ? $sortBy : 'currentPosition' ;
		
		$field = ['keyword','cmp','sv','start_ranking','one_week_ranking','monthly_ranking','life_ranking'];
		$searchData = ViewKeywordSearch::where('request_id',$campaign_id);
		if(!empty($selected_type) && isset($selected_type)){
			if($selected_type == 'favorited'){
				$searchData->where('is_favorite','1');
			}
			elseif($selected_type == 'unfavorited'){
				$searchData->where('is_favorite','0');
			}
			else{
				$searchData->orderBy('is_favorite','desc');
			}
		}else{
			$searchData->orderBy('is_favorite','desc');
		}

		$searchData->orderBy($sortBy,$sortType);

		if(!empty($tracking_type)){
			if($tracking_type !== 'all'){
				$searchData->where('tracking_option',$tracking_type);
			}		
		}

		if(!empty($query)){
			$searchData->where(function ($dta) use($query, $field) {
				for ($i = 0; $i < count($field); $i++){
					$dta->orwhere($field[$i], 'LIKE',  '%' . $query .'%');
				}      
			});
		}
		
		if($filter_tag != null && $filter_tag !=''){
			$searchData->whereRaw("find_in_set(".$filter_tag.",view_keyword_searches.keyword_tag_id)");
		}

		if($limit == 'All'){
			$data_count = $searchData->count();
			$searchData = $searchData->paginate($data_count);			
		}else{
			$searchData = $searchData->paginate($limit);
		}
		// echo "<pre>";
		// print_r($searchData);
		// die;
		return $searchData;
	}


	public function ajax_mark_live_keyword_favorite(Request $request){
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
				$response['status'] = '1'; 
				$response['error'] = '0';
				$response['message'] = $msg;

			}else{
				$response['status'] = '0'; 
				$response['error'] = '0';
				$response['message'] = 'Please try again';
			}
		}else{
			$response['status'] = '0'; 
			$response['error'] = '0';
			$response['message'] = 'Please try again';
		}
		return response()->json($response);
	}


	public function ajax_live_keyword_chart_data(Request $request){
		$keyword_data = KeywordPosition::select('created_at')->where('request_id',$request['request_id'])->where('keyword_id',$request['keyword_id'])->orderBy('id','asc')->first();


		if($request['duration'] == 'all'){
			$lastDate = date('Y-m-d', strtotime($keyword_data->created_at));
		}else{
			$lastDate = date('Y-m-d', strtotime($request['duration']));
		}
		
		$keywordPosition = KeywordPosition::where('request_id',$request['request_id'])->where('keyword_id',$request['keyword_id'])->whereDate('created_at','<=',date('Y-m-d'))->whereDate('created_at','>=',$lastDate)->orderBy('id','asc')->get();
		
		
		$data =  array();  
		
		foreach($keywordPosition as $record) {
			$values =  (int) $record->position <> '0' && $record->position <> null ? (int) $record->position : null ; 
			$data[] = array('t'=>strtotime($record->created_at)*1000,'y'=>$values);
		}
		return array('keyword'=>$data);
	}


	public function ajax_update_keyword_startRanking(Request $request){

		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		$response = array();
		$update = KeywordSearch::where('id',$request['request_id'])->update([
			'start_ranking' =>$request['start_ranking']
		]);
		

		if($update){
			$find = KeywordSearch::findorfail($request['request_id']);
			sleep(1);
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
		if(($results->position <> null && $results->position > 0) && (!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0)){
			$lifeTime = (int) $results->start_ranking - (int) $currentPostion->position;
		}elseif(($results->start_ranking == null || $results->start_ranking == 0) && (!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0)) {
			$lifeTime = 100 - (int) $currentPostion->position;
		}elseif(($results->start_ranking <> null && $results->start_ranking > 0) && (!empty($currentPostion->position) && $currentPostion->position == null || $currentPostion->position == 0)){
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

	public function ajax_remove_multiple_keywords(Request $request){
		$search = KeywordSearch::whereIn('id', $request['selected_ids'])->delete();
		$response = array();
		if($search){
			$keywordPosition = KeywordPosition::whereIn('keyword_id',$request['selected_ids'])->delete();
			if($keywordPosition){
				CampaignData::keywordsData($request['request_id']);
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


	public function ajax_get_keywords_time(Request $request){
		$result  = KeywordPosition::getLastUpdateKeyword($request->request_id);
		$time_span = KeywordPosition::calculate_time_span($result);
		if($result) {			
			$response['status'] = '1'; 
			$response['error'] 	= '0';
			$response['time'] 	= "Last Updated: ".$time_span." (".date('M d, Y',strtotime($result)).")" ;
		} else {
			$response['status'] = '2';
			$response['error'] = '2';
			$response['message'] = 'Getting Error to update data';

		}
		return response()->json($response);
	}

	public function ajax_update_live_keyword_tracking(Request $request){
		// KeywordSearch::where('request_id',$request['request_id'])->update(['is_sync'=>'1']);

		KeywordSearch::where('request_id',$request['request_id'])->whereIn('id',$request['selected_ids'])->update(['is_sync'=>'1']);
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child

		$client = null;
		try {
			$client = $this->DFSAuth();
		} catch (RestClientException $e) {
			return json_decode($e->getMessage(), true);
		}

		$results = KeywordSearch::getKeywordsData($request['selected_ids']);

		$update = KeywordSearch::updateKeywordsData($request['selected_ids']);
		

		$post_array = array();
		if(isset($results) && !empty($results)){
			foreach($results as $key=>$val){
				if($val->tracking_option == 'mobile'){
					$option_text = 'mobile';
				}else{
					$option_text ='';
				}

				if(!empty($val->lat) && !empty($val->long)){
					// echo "if";
					$location = $val->lat.','.$val->long;
					$locationType = "location_coordinate";
				}else{
					// echo "else";
					if(empty($val->lat) || empty($val->long)){
						$updateLatLong = KeywordSearch::updateKeywordLocationLatLong($request['selected_ids'],$val->canonical);
						
						$location = $updateLatLong;
						$locationType = "location_coordinate";
					}else{
						$location = $val->canonical;
						$locationType = "location_name";
					}
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

				$response['status'] = '1'; // Insert Data Done
				$response['error'] = '0';
				$response['message'] = 'Request sent Successfully';
			} catch (RestClientException $e) {
				$response['status'] = '2'; 
				$response['error'] = '2';
				$response['message'] = $e->getMessage();
			}
		}else {
			$response['status'] = '1'; 
			$response['error'] = '0';
			$response['message'] = 'Not Found!';
		}
		
		return response()->json($response);
	}

	public function ajax_update_live_keywords_location(Request $request){
		$ignore_local_listing = 0;
		$long = $lat = '';
		$ids[] = implode(',',$request['checked']);
		if(!empty($request['update_location'])){
			if(empty($request['lat']) || empty($request['long'])){
				$location = KeywordLocationList::getLatLong($request['update_location']);
				$latLong = explode(',', $location);
				$lat = $latLong[0];
				$long = $latLong[1];
			}else{
				$lat = $request['lat'];
				$long = $request['long'];
			}
		}else{
			$lat = $request['lat'];
			$long = $request['long'];
		}

		if($request->domain_type == '*.domain.com/*'){
			$url_type = 1;
		}elseif($request->domain_type == 'URL'){
			$url_type = 2;
		}

		if($request->has('local_listing') && $request->local_listing == 'true'){
			$ignore_local_listing = 1;
		}elseif($request->has('local_listing') && $request->local_listing == 'false'){
			$ignore_local_listing = 0;			
		}

		$url=rtrim($request->update_domain_url, '/');
		if ( substr($url, 0, 8) == 'https://' ) {
			$url = substr($url, 8);
		}
		if ( substr($url, 0, 7) == 'http://' ) {
			$url = substr($url, 7);
		}
		if ( substr($url, 0, 4) == 'www.' ) {
			$url = substr($url, 4);
		}

		$update = KeywordSearch::whereIn('id',$request['checked'])->update([
			'region' =>$request['update_region'],
			'tracking_option' =>$request['update_tracking_options'],
			'language' =>$request['update_language'],
			'canonical' =>$request['update_location'],
			'lat'=>$lat,
			'long'=>$long,
			'url_type'=>$url_type,
			'host_url'=>$url,
			'ignore_local_listing'=>$ignore_local_listing
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

	public function ajax_add_keywords_data(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		$user_package = User::get_user_package($user_id); 
		$used_keywords = KeywordSearch::check_keyword_count($user_id);
		$keywords_left = $user_package->keywords - $used_keywords;
		
		if($keywords_left <= 0){
			$response['status'] = '2'; 
			$response['error'] = '1';
			$response['message'] = 'You have reached your keyword limit.';
			return response()->json($response);
		}

		$data = $request->all();

		$finalstring = array_map('trim', explode(PHP_EOL, strtolower($data['keyword_field'])));

		
		if(count($finalstring) > $keywords_left){
			$response['status'] = '2'; 
			$response['error'] = '1';
			$response['message'] = 'You have '.$keywords_left.' keyword(s) left.';
			return response()->json($response);
		}
		
		$getDataByKeyword = KeywordSearch::getDataByKeyword($data);
		
		$finalstrings = array_unique($finalstring);

		if($getDataByKeyword <> null && $getDataByKeyword <> ''){
			$newKeywords = array_diff($finalstrings,$getDataByKeyword);
		}else{
			$newKeywords = $finalstrings;
		}
		
		
		if(count($newKeywords) == 0){
			$response['status'] = '0';
			$response['error'] = '1';
			$response['message'] = 'Already Added';
			
		}else{
			$response['newKeywords'] = $newKeywords;
			$response['today'] = date('d-M-Y');
			$response['status'] = '1'; 
			$response['error'] = '0';
			$response['message'] = 'Request has been sent';
		}
		
		return response()->json($response);
	}


	public function ajax_send_dfs_request(Request $request){
		$ignore_local_listing = 0;
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		$data = $request->all();
		$response = array();

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
		
		if($data['keyword_domain_type'] == '*.domain.com/*'){
			if ( strpos($url, '/') !== false ) {
				$explode = explode('/', $url);
				$url     = $explode['0'];
			}
			$data['url_type'] = 1;
		}else{
			$data['url_type'] = 2;
		}

		$data['domain_url'] = $url;
		$finalstring = $data['filtered_keywords'];		
		$newKeywords = $finalstring;

		$get_country = explode(',', $data['dfs_locations']);	


		if($request->has('ignore_local_listing') && $request->ignore_local_listing == 'true'){
			$ignore_local_listing = 1;
		}elseif($request->has('ignore_local_listing') && $request->ignore_local_listing == 'false'){
			$ignore_local_listing = 0;			
		}
		
		/*posting data on dfs for search volume*/
		$client = null;
		$searchVolumeArr = array();
		try {
			$client = $this->DFSAuth();
			$live_data_array[] = array(
				"language_name" => $data['language'],
				"keywords" => $newKeywords,
			);
			$searchVolumeArr = $client->post('/v3/keywords_data/google/search_volume/live', $live_data_array);
			unset($live_data_array);

			$searchVolumeData = $searchVolumeArr['tasks'][0]['result'];
			$resultCount = 0;

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
						'region'=>$data['regions'],
						'canonical'=>$data['dfs_locations'],
						'lat'=>$data['lat'],
						'long'=>$data['long'],
						'url_type' =>$data['url_type'],
						'ignore_local_listing' => $ignore_local_listing
					]);
					if($keywordinsert){
						$getLastId = $keywordinsert->id;
						$resultCount++;

						$post_array[] = array(
							"language_name" => $data['language'],
							"location_coordinate" => $data['lat'].','.$data['long'],
							"se_domain" => $data['regions'],
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
			}
			return response()->json($response);
		} catch (RestClientException $e) {
			return json_decode($e->getMessage(), true);
		}

	}

	public function ajax_export_live_keywords(Request $request){
		if(!empty($request['checked'])){
			$ids = explode(',',$request['checked']);
		}else{
			$ids = array();
		}
		$request_id = $request['request_id'];
		
		ob_end_clean(); 
		ob_start(); 
		return Excel::download(new ExportKeywords($ids,$request_id),'Live Keyword Tracking.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
			'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		]);
	}

	//multiple marking fav/unfav keyword

	public function ajax_multiple_keyword_fav_unfav(Request $request){

		$favorite = KeywordSearch::
		select('id','request_id','is_favorite')
		->where('request_id',$request['request_id'])
		->whereIn('id',$request['selected_ids'])
		->where('is_favorite','0')
		->pluck('id')
		->all();
		

		$unfavorite = KeywordSearch::
		select('id','request_id','is_favorite')
		->where('request_id',$request['request_id'])
		->whereIn('id',$request['selected_ids'])
		->where('is_favorite','1')
		->pluck('id')
		->all();

		$favorite_update = KeywordSearch::whereIn('id',$favorite)->update(['is_favorite'=>'1']);
		$unfavorite_update = KeywordSearch::whereIn('id',$unfavorite)->update(['is_favorite'=>'0']);
		

		if($favorite_update || $unfavorite_update){
			$response['status'] = '1'; 
			$response['error'] = '0';
			$response['message'] = 'Keyword(s) updated successfully!';
		}else{
			$response['status'] = '0'; 
			$response['error'] = '0';
			$response['message'] = 'Please try again';
		}
		
		return response()->json($response);
	}

	// keyword tags section

	public function load_keyword_tag_section($domain_name,$campaign_id){
		$keyword_tags = KeywordTag::where('request_id',$campaign_id)->get();
		return \View::make('vendor.keyword_tag.index',['keyword_tags'=>$keyword_tags]);
	}

	public function ajax_fetch_existing_keyword_tags(Request $request){
		$query = $request->search_tag;
		$request_id = $request->campaign_id;

		$list = KeywordTag::where('request_id',$request_id);
		if(!empty($query)){
			$list->where('tag','LIKE','%' . $query .'%');
		}
		$lists = $list->get();

		$html = '';
		if(count($lists) > 0){
			$html .= '<div class="form-group m-height"><label>Select Tags</label><div class="checkbox-group" id="tag_listing">';
			foreach($lists as $key=>$value){
				$html .= '<label><input type="checkbox" class="existing_tag" name="existing_tag[]" value="'.$value->id.'" data-request-id="'.$value->request_id.'"><span class="custom-checkbox"></span>'.$value->tag.'<a href="javascript:;" class="delete_keyword_tag" data-tag-id="'.$value->id.'" data-request-id="'.$value->request_id.'"><span uk-icon="icon: close"></span></a></label>';
			}
			$html .='</div></div><div class="text-left btn-group start" id="apply_div"><input type="button" class="btn blue-btn" value="Apply" id="apply_tag" disabled></div>';
		}else{
			$html .= '<div class="form-group m-height"><label id="no_keyword_tags">No tags to display</label></div><div class="text-left btn-group start" id="create_tag"><input type="button" class="btn blue-btn" value="Create New tag" id="create_keyword_tag"><span id="display_type_tag"></span></div>';
		}

		$response['html']  = $html;
		$response['searched_tag']  = $query;
		return response()->json($response);
	}

	public function ajax_create_keyword_tag(Request $request){
		$request_id  = $request->request_id;
		$ids = $request->selected_keywordss;
		$new_tag = $request->new_tag;
		$last_id = array();
		
		//foreach($ids as $key_id =>$value_id){
		$insert = KeywordTag::create([
			'request_id'=>$request_id,
				// 'keyword_id'=>$value_id,
			'tag'=>$new_tag
		]);
		$last_id = $insert->id;
		//}

		$update = KeywordSearch::whereIn('id',$ids)->update([
			'keyword_tag_id'=>$last_id
		]);

		if($update){
			$res['status'] = 1;
			$res['message'] = 'Tag assigned to selected keyword(s).';
		}else{
			$res['status'] = 0;
			$res['message'] = 'Error!! Please try again.';
		}
		return response()->json($res);
	}

	public function ajax_list_existing_tags(Request $request){
		$lists = KeywordTag::where('request_id',$request->campaign_id)->get();
		$html = '';
		if(isset($lists) && !empty($lists) && count($lists) > 0){
			$html .='<select id="filter-tags" class="selectpicker" data-live-search="true"><option value="">-Select Tag-</option>';
			foreach($lists as $key=>$value){
				$html .= '<option value="'.$value->id.'">'.$value->tag.'</option>';
			}
			$html .= '</select>';
			$response['status'] = 1;
			$response['html'] = $html;
		}else{
			$response['status'] = 0;
			$response['html'] = '';
		}
		return response()->json($response);
	}

	public function ajax_apply_existing_tags(Request $request){
		$response = array();
		$request_id  = $request->request_id;
		$ids = $request->selected_keywordss;
		$tags = implode(',',$request->existing_tag);

		$update = KeywordSearch::
		whereIn('id',$ids)
		->update([
			'keyword_tag_id' => DB::raw("CONCAT(IFNULL(keyword_tag_id, ''), '," . $tags . "')")
		]); 

		if($update){
			$response['status'] = 1;
			$response['message'] = 'Tag(s) applied to keyword(s) successfully!';
		}else{
			$response['status'] = 0;
			$response['message'] = 'Error!! Please Try again.';
		}
		return response()->json($response);
	}

	public function ajax_delete_keyword_tag(Request $request){				
		$delete = KeywordTag::where('id',$request->keyword_tag_id)->where('request_id',$request->request_id)->delete();
		if($delete){
			$response['status'] = 1;
			$response['message'] = 'Tag removed successfully!';
		}else{
			$response['status'] = 0;
			$response['message'] = 'Error deleting Tag.';
		}
		return response()->json($response);
	}


	public function serp($domain_name, $campaign_id){
		$getRegions = RegionalDatabse::where('status',1)->get();
		$language  = Language::where('status',1)->get();
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child

		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();

		if(isset($data) && !empty($data)){

			if($data->status == 1){
				//$campaign_errors = Error::where('request_id',$campaign_id)->orderBy('id','desc')->whereDate('updated_at',date('Y-m-d'))->get();
				return view('vendor.campaign_archived');
			}

			$keyword_tags = KeywordTag::where('request_id',$campaign_id)->get();
			$keywordsCount = KeywordSearch::where('user_id',$user_id)->count();	
			$keyenc = base64_encode($campaign_id.'-|-'.$user_id.'-|-'.time());	

			$table_settings = LiveKeywordSetting::where('detail',0)->where('request_id',$campaign_id)->pluck('heading')->all();

			$compactData['data'] = $data;
			$compactData['user_id'] = $user_id;
			$compactData['getRegions'] = $getRegions;
			$compactData['campaign_id'] = $campaign_id;
			$compactData['keyword_tags'] = $keyword_tags;
			$compactData['keywordsCount'] = $keywordsCount;
			$compactData['language'] = $language;
			$compactData['keyenc'] = $keyenc;
			$compactData['table_settings'] = $table_settings;
			return view('vendor.serp',$compactData);
		}else{
			return view('errors.404');
		}
	}

	public function ajax_get_regional_database(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();
		$response = array();
		$getRegions = RegionalDatabse::where('status',1)->get();
		if(!empty($getRegions)){
			$response['status'] = 1;
			$option = '<option value="">-Select-</option>';
			foreach($getRegions as $key=>$value){
				$selected = "";
				if(trim($data->rank_search_engine) == trim($value->long_name)){
					$selected = 'selected';
				}elseif(empty($data->rank_search_engine) && ($value->short_name=='us')){
					$selected = "selected";
				}
				$option .= '<option value="'.$value->long_name. '" '.$selected.'>'.$value->short_name .' ('.$value->long_name.') '.'</option>';
			}

			$response['records'] = $option;
		}else{
			$response['status'] = 0;
			$response['records'] = '';
		}		
		return response()->json($response);
	}


	public function ajax_get_languages(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();
		$response = array();
		$language = Language::where('status',1)->orderBy('name','asc')->get();
		if(!empty($language)){
			$response['status'] = 1;
			$option = '<option value="">-Select-</option>';
			foreach($language as $key=>$value){
				$selected = "";
				if(trim($data->rank_language)==$value->name){
					$selected = 'selected';
				}elseif(empty($data->rank_language) && ($value->name=='English')){
					$selected = "selected";
				}
				$option .= '<option value="'.$value->name. '" '.$selected.'>'.$value->name .'</option>';
			}

			$response['records'] = $option;
		}else{
			$response['status'] = 0;
			$response['records'] = '';
		}		
		return response()->json($response);
	}
	

	public function ajax_check_keyword_count(){
		$response = array();
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$user_package = UserPackage::where('user_id',$user_id)->latest()->first();
		// $keywordsCount = KeywordSearch::where('user_id',$user_id)->count();	
		$keywordsCount = KeywordSearch::check_keyword_count($user_id);	

		if($user_package->keywords <= $keywordsCount){
			$response['id'] = 'show_keyword_popup';
		}else{
			$response['id'] = 'AddKeywordsBtn';
		}
		return response()->json($response);
	}


	public function ajax_get_domainType(Request $request){
		$url_type = '*.domain.com/*';
		$data = SemrushUserAccount::where('id',$request->campaign_id)->select('id','url_type')->first();
		if(isset($data) && ($data <> null)){
			if($data->url_type == 1){
				$url_type = '*.domain.com/*';
			}
			if($data->url_type == 2){
				$url_type = 'URL';
			}
		}
		return response()->json($url_type);
	}
	
} 