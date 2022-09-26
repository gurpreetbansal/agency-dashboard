<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  Session;
use App\GoogleAnalyticsUsers;
use App\SemrushUserAccount;
use App\SearchConsoleUsers;
use App\SearchConsoleUrl;
use App\User;
use App\TaskCategory;
use App\TaskActivity;
use App\TaskList;
use App\GlobalSetting;

use Auth;

class TaskActivitiesController extends Controller {

	public function index(Request $request, $domain,$campaign_id) 
	{

		/*$user_id  = [0,Auth()->user()->id];
		$taskCetegory = TaskCategory::whereIn('user_id',$user_id)->where('status',0)->get();*/
		$taskCetegory = TaskCategory::where('status',0)->get();

		return view('vendor.activitives.index',compact('taskCetegory','campaign_id'));
	}

	public function activity(Request $request, $campaign_id){
		if(!Auth::user()){
			return view('errors.404');
		}
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child

		if(\Request::segment(1) !== 'profile-settings'){
			$check = User::check_subscription($user_id); 
			if($check == 'expired'){
				return redirect()->to('/dashboard');
			}
		}
		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();

		if(isset($data) && !empty($data)){
			if($data->status == 1){
				$campaign_errors = Error::where('request_id',$campaign_id)->orderBy('id','desc')->whereDate('updated_at',date('Y-m-d'))->get();
				return view('vendor.campaign_archived',compact('campaign_errors'));
			}

			$taskCetegory = TaskCategory::where('status',0)->get();

			$taskActivity = TaskActivity::whereYear('activity_date', date('Y'))
			->whereMonth('activity_date', date('m'))->orderBy('category_id','ASC')->get();

			return view('vendor.activity',compact('taskCetegory','taskActivity','campaign_id','user_id'));	
		}else{
			return view('errors.404');
		}

	}


	public function activities(Request $request, $domain = null,$campaign_id = null){

		$taskCetegory = TaskCategory::where('status',0)->get();
		
		$taskActivity = TaskActivity::whereYear('activity_date', date('Y'))
		->whereMonth('activity_date', date('m'))->orderBy('category_id','ASC')->get();


		return view('vendor.seo_sections.activities.index',compact('taskCetegory','taskActivity','campaign_id'));
	}

	public function activityProcess($domain=null,$activity_id=null){
		
		if($activity_id == null){
			$taskActivity = TaskActivity::where('id',$domain)->first();
		}else{
			$taskActivity = TaskActivity::where('id',$activity_id)->first();
		}
		
		return view('vendor.seo_sections.activities.progress',compact('taskActivity'));	
	}

	public function addmore(Request $request){
		
		$campaignId = $request->campaignId;
		$categoryId = $request->categoryId;
		$categoryName = $request->categoryName;
		return view('vendor.activitives.addmore',compact('campaignId','categoryId','categoryName'));	

	}

	public function ajaxActivities(Request $request){

		$sortBy = isset($request['column_name']) && $request['column_name'] <> null ? $request['column_name']: 'id' ;
		$sortType = isset($request['order_type']) && $request['order_type'] <> null ? $request['order_type'] : 'desc';
		$limit = isset($request['limit']) && $request['limit'] <> null? $request['limit'] :10;	
		$query = isset($request['query']) ? $request['query'] : $request['query'];
		$categories = isset($request['categoryId']) ? $request['categoryId'] : 0;
		$dates = isset($request['dates']) ? $request['dates'] : date('Y-m');
		$loadtime = isset($request['loadtime']) ? $request['loadtime'] : 'more';
		$campaign_id = isset($request['campaignId']) ? $request['campaignId'] : 0;
		$tasklistId = isset($request['tasklistId']) ? $request['tasklistId'] : null;

		$rowperpage = 10;
		$offset = ($limit)?:0;

		$year = date('Y', strtotime($dates)); 
		$month = date('m', strtotime($dates)); 
		if($tasklistId == null){
			$queryy = TaskActivity::
			whereYear('activity_date', $year)
			->whereMonth('activity_date', $month)
			->where('campaign_id',$campaign_id)
			/*->orderBy('category_id','ASC')*/
			->orderBy('activity_date','DESC')
			->orderBy($sortBy,$sortType);
				//->paginate($limit);
		}else{
			$queryy = TaskActivity::
			where('activity_id',$tasklistId)
				/*->whereYear('created_at', $year)
				->whereMonth('created_at', $month)*/
				/*->orderBy('category_id','ASC')*/
				->orderBy($sortBy,$sortType);
				//->paginate($limit);
			}

			$taskActivity = $queryy->skip($offset)->take($rowperpage)->paginate($limit);

			return view('vendor.seo_sections.activities.list',compact('taskActivity','categories','loadtime'))->render();

		}

		public function ajaxActivityTotal(Request $request){

			$dates = isset($request['dates']) ? $request['dates'] : date('Y-m');
			$campaign_id = isset($request['campaignId']) ? $request['campaignId'] : 0;
			$sortBy = isset($request['column_name']) && $request['column_name'] <> null ? $request['column_name']: 'id' ;
			$sortType = isset($request['order_type']) && $request['order_type'] <> null ? $request['order_type'] : 'desc';
			$year = date('Y', strtotime($dates)); 
			$month = date('m', strtotime($dates)); 

			$taskActivity = TaskActivity::
			whereYear('activity_date', $year)
			->whereMonth('activity_date', $month)
			->where('campaign_id',$campaign_id)
			/*->orderBy('category_id','ASC')*/
			->orderBy($sortBy,$sortType)
			->pluck('time_taken')->all();

			$i = 0;
			foreach ($taskActivity as $time) {
				sscanf($time, '%d:%d', $hour, $min);
				$i += $hour * 60 + $min;
			}

			if($h = floor($i / 60)) {
				$i %= 60;
			}

			$time = sprintf('%02d:%02d', $h, $i);

			return response()->json($time);

			/*return view('vendor.seo_sections.activities.list',compact('taskActivity','categories','loadtime'))->render();*/

		}

		public function addActivitylist(Request $request){

			$validator = Validator::make($request->all(), [
				'activity_name' => 'required',
			]);

			if ($validator->fails()) {
				$message = $validator->messages()->first();
				$arr = array('status'=>false,'message'=>$message);
				return response()->json($arr);
			}

			$create = TaskList::create([
				'user_id'=>Auth::user()->id,
				'category_id'=>$request->category_id,
				'name'=>$request->activity_name,
			]);

			if($create){
				$lastid = $create->id;
				sleep(1);
				$taskList = TaskList::where('id',$lastid)->first();

				$response['status'] = true;
				$response['taskData'] = $taskList;
				$response['message'] = 'Your activity has been added successfully!';
			}else{
				$response['message'] = 'Something went wrong! Please try again.';
				$response['status'] = false;
			}
			return response()->json($response);
		}

		public function addActivity(Request $request){
			$validator = Validator::make($request->all(), [
				"status"    => "required|array",
				"status.*"  => "required|integer",
				"notes"    => "required|array",
				"notes.*"  => "required|string",
				"activity_date"    => "required|array",
				"activity_date.*"  => "required|date",
				"activity_date"    => "required|array",
				"activity_date.*"  => "required|date"
			]);

			$validator->after(function ($validator)use($request) {	
				foreach($request->status as $kk =>$vv){
					if($request->activity_hours[$kk] === null && $request->activity_seconds[$kk] === null){
						$validator->errors()->add('activity_time.'.$kk, 'The activity_time.'.$kk.' field is required.');
					}

					if($request->has('activity_image')){
						if(array_key_exists($kk,$request->activity_image)){
							if($request->activity_image[$kk] === null && $request->activityfilelink[$kk] === null){
								$validator->errors()->add('activityfilelink.'.$kk, 'The activityfilelink.'.$kk.' field is required.');
							}
						}else{
							if($request->activityfilelink[$kk] === null){
								$validator->errors()->add('activityfilelink.'.$kk, 'The activityfilelink.'.$kk.' field is required.');
							}
						}					
					}else{
						if($request->activityfilelink[$kk] === null){
							$validator->errors()->add('activityfilelink.'.$kk, 'The activityfilelink.'.$kk.' field is required.');
						}
					}
				}
			});

			if ($validator->fails()) {
				$array = array();
				$data_array = $validator->messages()->getMessages();				
				foreach($data_array as $field_name => $messages) {
					$explode = explode('.',$field_name);
					$array[$explode[1]][$explode[0]] = $messages[0];
				}

				$response['status'] = false;
				$response['message'] = $array;
				$response['array_count'] = count($array);
				return response()->json($response);
			}

			$status = $request->status;


			foreach($status as $key_status =>$key_value){
				$image_data = ''; $links = '';
				if ($request->has('activityfilelink')) {
					$links = $request->activityfilelink[$key_status];
				}

				if ($request->has('activity_image')) {
					$folder = '/activities/';
					if(array_key_exists($key_status,$request->activity_image)){
						$image = $request->activity_image[$key_status];
						$image_array = array(); 
						foreach($image as $key=>$value){

							$imageName = $value->getClientOriginalName();
							$name = strtotime(date('Y-m-d H:i:s')).'_'.pathinfo($imageName, PATHINFO_FILENAME);
							$image_array[] = $folder . strtotime(date('Y-m-d H:i:s')).'_'.$imageName;
							$filePath = $folder . $name;
							User::uploadOne($value, $folder, 'public', $name);
						}	
						$image_data = implode(',',$image_array);
					}
				}

				$hours = $request->activity_hours[$key_status] <> null ? $request->activity_hours[$key_status] : '00';
				$mins = $request->activity_seconds[$key_status] <> null ? $request->activity_seconds[$key_status] : '00';
				$time = (float) $hours.':'.$mins;

				$activityDate = date('Y-m-d',strtotime($request->activity_date[$key_status]));

				$array = array(
					'user_id'=>Auth::user()->id,
					'campaign_id'=>$request->campaign_id,
					'category_id'=>$request->category_id,
					'activity_id'=>$request->activity_id,
					'status'=>$key_value,
					'activity_date'=>$activityDate,
					'activity_hours'=>$hours,
					'activity_seconds'=>$mins,
					'notes'=>$request->notes[$key_status],
					'file_name'=>$image_data,
					'file_link'=>$links,
					'time_taken'=>$time,
				);
				TaskActivity::create($array);

				if(($key_status+1)%count($status) == 0){
					$response['status'] = true;
					$response['message'] = 'Your activity has been added successfully!';
				}else{
					$response['message'] = 'Something went wrong! Please try again.';
					$response['status'] = false;
				}
			}

			return response()->json($response);
		}



		public function deleteActivities(Request $request){
			$responceArr = array();
			$taskActivity = TaskActivity::where('id',$request->activityId)->first();

			if($taskActivity->user_id !== auth()->user()->id){
				$responceArr =	[
					'status'=>false,
					'message' => "You are not authorized user to delete this activity.",
				];
			}else{
				$deleteAct = TaskActivity::where('id',$request->activityId)->delete();
				if($deleteAct){
					$responceArr =	[
						'status'=>true,
						'message' => "Your activity has been deleted successfully.",
					];
				}else{
					$responceArr =	[
						'status'=>false,
						'message' => "something went wrong! Please try again.",
					];
				}

			}

			return response()->json($responceArr);
		}


		public function deleteActivitylist(Request $request){
			$responceArr = array();
			$taskActivity = TaskList::where('id',$request->activityId)->where('user_id',auth()->user()->id)->first();

			if($taskActivity->user_id !== auth()->user()->id){
				$responceArr =	[
					'status'=>false,
					'message' => "You are not authorized user to delete this activity list.",
				];
			}else{
				$deleteAct = TaskList::where('id',$request->activityId)->where('user_id',auth()->user()->id)->delete();
				if($deleteAct){
					TaskActivity::where('activity_id',$request->activityId)->delete();
					$responceArr =	[
						'status'=>true,
						'message' => "Your activity has been deleted successfully.",
					];
				}else{
					$responceArr =	[
						'status'=>false,
						'message' => "something went wrong! Please try again.",
					];
				}

			}

			return response()->json($responceArr);
		}

		public function viewActivity(Request $request, $domain,$campaign_id) 
		{

			$data = GlobalSetting::uploading_changes();
			if($data == true || $data == 1){
				return \View::make('errors.uploading_changes');
			}
			$user_id = User::get_parent_user_id(Auth::user()->id);
			$project_detail = SemrushUserAccount::where('id',$campaign_id)->where('user_id',$user_id)->select('id','host_url','status')->first();
			
			if(isset($project_detail) && !empty($project_detail)){
				if($project_detail->status == 1){
					//$campaign_errors = Error::where('request_id',$campaign_id)->orderBy('id','desc')->whereDate('updated_at',date('Y-m-d'))->get();
					return view('vendor.campaign_archived');
				}
				if(\Request::segment(1) !== 'profile-settings'){
					$check = User::check_subscription($user_id); 
					if($check == 'expired'){
						return redirect()->to('/dashboard');
					}
				}  

				$user_id  = [0,Auth()->user()->id];
				/*$taskCetegory = TaskCategory::whereIn('user_id',$user_id)->where('status',0)->get();*/
				$taskCetegory = TaskCategory::where('status',0)->get();

				return view('vendor.activitives.activity',compact('taskCetegory','campaign_id','user_id','project_detail'));
			}else{
				return view('errors.404');
			}
		}

		public function viewCategories(Request $request, $domain,$category_id) 
		{

			/*$user_id  = [0,Auth()->user()->id];*/

			/*$taskCetegory = TaskCategory::whereIn('user_id',$user_id)->where('status',0)->get();*/
			/*$taskCetegory = TaskList::where('status',0)->where('id',$category_id)->first();*/

			$taskCetegory = TaskActivity::
			where('activity_id',$category_id)
				/*->whereYear('created_at', $year)
				->whereMonth('created_at', $month)*/
				->orderBy('category_id','ASC')
				->paginate(10);

				return view('vendor.activitives.category-list',compact('taskCetegory'));
			}	

		}