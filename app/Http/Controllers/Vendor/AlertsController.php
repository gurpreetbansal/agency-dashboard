<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\KeywordPosition;
use App\KeywordSearch;
use App\User;
use App\SemrushUserAccount;
use Auth;

use App\Views\ViewKeywordSearch;
use App\GlobalSetting;

class AlertsController extends Controller {

	public function index(){
		$global_setting = GlobalSetting::uploading_changes();
		if($global_setting == true || $global_setting == 1){
			return \View::make('errors.uploading_changes');
		}
		
		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
		if(\Request::segment(1) !== 'profile-settings'){
            $check = User::check_subscription($user_id); 
            if($check == 'expired'){
                return redirect()->to('/dashboard');
            }
        }
		return view('vendor.alerts.index');
	}

	public function all_alerts_content(){
		return \View::make('vendor.alerts.tabs.all');
	}

	public function ajax_fetch_alerts_list(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$query = $request['query'];

			$alerts = $this->alerts_list($limit,$query);

			return view('vendor.alerts.content.table_all', compact('alerts'))->render();
		}
	}

	public function ajax_fetch_alerts_pagination(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$query = $request['query'];
			
			$alerts = $this->alerts_list($limit,$query);

			return view('vendor.alerts.content.pagination_all', compact('alerts'))->render();
		}
	}

	private function alerts_list($limit,$query){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$today  = date('Y-m-d');
		$week_date = date('Y-m-d',strtotime('-7 day',strtotime($today))); 


		$user_details = User::where('id',Auth::user()->id)->first();
		if($user_details->restrictions != NULL){
			$restrictions = $user_details->restrictions;
		}else{
			$restrictions = '';
		}

		$result = ViewKeywordSearch::
		where('oneday_position','!=',0)
		->whereHas('SemrushUserData', function($q) use ($user_id,$restrictions){
			$q->where('status', 0)
			->where('user_id',$user_id);
			if($restrictions != ''){
				$q->whereIn('id',explode(',', $restrictions));
			}
		})
		->where('user_id',$user_id)
		->whereDate('updated_at','<=',$today)
		->whereDate('updated_at','>=',$week_date)
		->orderBy('updated_at','desc');
		if(!empty($query)){
			$ids = SemrushUserAccount::where('domain_name','LIKE','%'.$query.'%')->orWhere('host_url', 'LIKE', '%' . $query . '%')->pluck('id')->all();
			$result->Where(function ($dta) use($query,$ids,$result) {
				if(!empty($ids) && $ids <> null){
					$dta->orwhere(function($dta) use($ids,$result){
						$dta->whereIn('request_id',$ids);
					});
				}
				$dta->orwhere('keyword','LIKE','%'.$query.'%');
			});

		} 

		$keywords = $result
		->paginate($limit);		

		return $keywords;
	}

	public function positive_alerts_content(){
		return \View::make('vendor.alerts.tabs.positive');
	}

	public function ajax_fetch_positive_alerts_list(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$query = $request['query'];

			$alerts = $this->positive_alerts_list($limit,$query);

			return view('vendor.alerts.content.table_positive', compact('alerts'))->render();
		}
	}

	public function ajax_fetch_positive_alerts_pagination(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$query = $request['query'];

			$alerts = $this->positive_alerts_list($limit,$query);

			return view('vendor.alerts.content.pagination_positive', compact('alerts'))->render();
		}
	}

	private function positive_alerts_list($limit,$query){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$today  = date('Y-m-d');
		$week_date = date('Y-m-d',strtotime('-7 day',strtotime($today))); 
		
		$user_details = User::where('id',Auth::user()->id)->first();
		if($user_details->restrictions != NULL){
			$restrictions = $user_details->restrictions;
		}else{
			$restrictions = '';
		}

		$result = ViewKeywordSearch::
		where('oneday_position','>',0)
		->whereHas('SemrushUserData', function($q) use ($user_id,$restrictions){
			$q->where('status', 0)
			->where('user_id',$user_id);
			if($restrictions != ''){
				$q->whereIn('id',explode(',', $restrictions));
			}
		})
		->where('user_id',$user_id)
		->whereDate('updated_at','<=',$today)
		->whereDate('updated_at','>=',$week_date)
		->orderBy('updated_at','desc');
		if(!empty($query)){
			$ids = SemrushUserAccount::where('domain_name','LIKE','%'.$query.'%')->orWhere('host_url', 'LIKE', '%' . $query . '%')->pluck('id')->all();
			$result->Where(function ($dta) use($query,$ids,$result) {
				if(!empty($ids) && $ids <> null){
					$dta->orwhere(function($dta) use($ids,$result){
						$dta->whereIn('request_id',$ids);
					});
				}
				$dta->orwhere('keyword','LIKE','%'.$query.'%');
			});

		} 

		$keywords = $result
		->paginate($limit);		

		return $keywords;
	}

	public function negative_alerts_content(){
		return \View::make('vendor.alerts.tabs.negative');
	}


	public function ajax_fetch_negative_alerts_list(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$query = $request['query'];

			$alerts = $this->negative_alerts_list($limit,$query);
		
			return view('vendor.alerts.content.table_negative', compact('alerts'))->render();
		}
	}

	public function ajax_fetch_negative_alerts_pagination(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$query = $request['query'];

			$alerts = $this->negative_alerts_list($limit,$query);

			return view('vendor.alerts.content.pagination_negative', compact('alerts'))->render();
		}
	}

	private function negative_alerts_list($limit,$query){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$today  = date('Y-m-d');
		$week_date = date('Y-m-d',strtotime('-7 day',strtotime($today)));  
		
		$user_details = User::where('id',Auth::user()->id)->first();
		if($user_details->restrictions != NULL){
			$restrictions = $user_details->restrictions;
		}else{
			$restrictions = '';
		} 

		$result = ViewKeywordSearch::
		where('oneday_position','<',0)
		->whereHas('SemrushUserData', function($q) use ($user_id,$restrictions){
			$q->where('status', 0)
			->where('user_id',$user_id);
			if($restrictions != ''){
				$q->whereIn('id',explode(',', $restrictions));
			}
		})
		->where('user_id',$user_id)
		->whereDate('updated_at','<=',$today)
		->whereDate('updated_at','>=',$week_date)
		->orderBy('updated_at','desc');
		if(!empty($query)){
			$ids = SemrushUserAccount::where('domain_name','LIKE','%'.$query.'%')->orWhere('host_url', 'LIKE', '%' . $query . '%')->pluck('id')->all();
			$result->Where(function ($dta) use($query,$ids,$result) {
				if(!empty($ids) && $ids <> null){
					$dta->orwhere(function($dta) use($ids,$result){
						$dta->whereIn('request_id',$ids);
					});
				}
				$dta->orwhere('keyword','LIKE','%'.$query.'%');
			});

		} 

		$keywords = $result
		->paginate($limit);		

		return $keywords;
	}


	public function ajax_save_alert_time(){
		$user_id = Auth::user()->id;
		$update = User::where('id',$user_id)->update([
			'notification_check_time'=>now()
		]);
		if($update){
			$response['status'] = 'success';
		}else{
			$response['status'] = 'error';
		}
		return response()->json($response);
	}

}