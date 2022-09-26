<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CancelFeedback;
use App\User;

class FeedbackController extends Controller {

	public function index(){
		return view('Admin.feedback');
	}

	public function ajax_fetch_feedback_data(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$query = $request['query'];
			$data = $this->feedback_list_data($limit,$query);
			return view('Admin.feedback.table', compact('data'))->render();
		}
	}

	public function ajax_fetch_feedback_pagination(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$query = $request['query'];
			$data = $this->feedback_list_data($limit,$query);
			return view('Admin.feedback.pagination', compact('data'))->render();
		}
	}

	private  function feedback_list_data ($limit,$search){  
		$get_users = CancelFeedback::
		with(['user_info' => function ($query) {
			$query->select('id', 'name');
		}]);
		if($search <> null){
			$ids = User::where('name','LIKE','%'.$search.'%')->pluck('id')->all();
			$get_users->whereIn('id',$ids);
		}
		$users = $get_users->paginate($limit);
		return $users;
	}

	public function ajax_fetch_client_feedback(Request $request){
		$res = array();
		$data = CancelFeedback::where('id',$request['feedback_id'])->first();
		return \View::make('Admin.feedback.cancel_form',['data'=>$data]);
	}

}