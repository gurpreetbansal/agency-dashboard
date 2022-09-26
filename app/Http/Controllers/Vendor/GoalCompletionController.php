<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\GoogleProfileData;

class GoalCompletionController extends Controller {


	public function ajax_get_goal_completion_list(Request $request){
		$campaign_id = $request['campaign_id'];
		$results = $this->get_goal_data($request['campaign_id'],$request['page'],$request['column_name'],$request['order_type']);
		return view('vendor.seo_sections.goal_completion.table',compact($results,$campaign_id))->render();

	}

	public function ajax_get_goal_completion_pagination(Request $request){
		
	}


	public static function get_goal_data($campaign_id,$page,$sortBy,$sortType){
		$data = GoogleProfileData::
		where('request_id',$campaign_id)
		->where($sortBy,$sortType)
		->paginate(10);
		return $data;
	}
}