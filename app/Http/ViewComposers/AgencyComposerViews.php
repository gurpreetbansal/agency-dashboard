<?php

namespace App\Http\ViewComposers;

use App\SemrushUserAccount;
use Illuminate\Contracts\View\View;
use Request;
use URL;
use Auth;
use App\DashboardType;
use App\RegionalDatabse;
use App\Announcement;
use App\UserPackage;
use App\KeywordSearch;
use App\User;
use App\TaskActivity;
use App\Traits\ViewKeyTrait;


class AgencyComposerViews
{

	use ViewKeyTrait;

	public function compose(View $view) {

		$allCampaigns = $domain_details =$dashboard =  array();
		$dashboardTypes = $regional_db = array();
		$role_id =  $project_count = 0;
		$user_package = null;
		$notifications = array();
		
		$announcements = Announcement::get();
		
		if((Auth::user() != null)){
			
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$user_package = UserPackage::where('user_id',$user_id)->latest()->first();
			$project_count = SemrushUserAccount::where('user_id',$user_id)->where('status','=',0)->count();		
			$campaign_id = Request::segment(2);

			/*notification check for alerts */
			if(Request::segment(1) == 'dashboard' && (Request::segment(2) !== '')){
				$campaign_id = null;
			}

			//$notifications = $this->getNotificationCount($user_id,Auth::user()->id,$campaign_id);
			/*notification check for alerts */
			
			
			if(Request::segment(1) == 'project-detail' && Request::segment(2) <> 'sidebar'){

				$campaignsData = $this->keySplit($campaign_id);
				$campaign_id = $campaignsData['campaign_id'];
				
			}

			if(Request::segment(1) == 'download' && (Request::segment(2) == 'seo' || Request::segment(2) == 'livekeyword') ){
				$campaign_id = Request::segment(3);
				$campaignsData = $this->keySplit($campaign_id);
				$campaign_id = $campaignsData['campaign_id'];
				
			}

			if(Request::segment(1) == 'activity'){
				if(Request::segment(2) == 'categories'){
					$category_id = Request::segment(3);
					$domain_details = TaskActivity::where('activity_id',$category_id)->first();
					//$campaign_id = $domain_details->campaign_id;
				}else{
					$campaign_id = Request::segment(3);
				}
			}

			$domain_details = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();
			
			if(isset($domain_details->ProfileInfo->agency_logo)){
				$logo = $this->agency_logo($campaign_id,$domain_details->user_id,$domain_details->ProfileInfo->agency_logo);
				$domain_details->logo_data = $logo;
			}
			if(isset($domain_details->project_logo) && !empty($domain_details->project_logo)){
				$projectlogo = $this->project_logo($campaign_id,$domain_details->project_logo);
				$domain_details->project_logo = $projectlogo;
			}

			$user = User::findorfail(Auth::user()->id);
			$role_id = $user->role_id;
			$dashboardTypes = DashboardType::where('status',1)->orderBy('order_status','asc')->get();


		}else{

			$data = $view->getData();

			if(!empty($data['campaign_id'])){
				$campaign_id = $data['campaign_id'];
				$domain_details = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();

				if(isset($domain_details->ProfileInfo->agency_logo)){
					$logo = $this->agency_logo($campaign_id,$domain_details->user_id,$domain_details->ProfileInfo->agency_logo);
					$domain_details->logo_data = $logo;
				}

				if(isset($domain_details->project_logo) && !empty($domain_details->project_logo)){
					$projectlogo = $this->project_logo($campaign_id,$domain_details->project_logo);
					$domain_details->project_logo = $projectlogo;
				}

				$user = User::findorfail($domain_details->user_id);

				$role_id = $user->role_id;
				// $allCampaigns = SemrushUserAccount::where('user_id',$domain_details->user_id)->get();
				$allCampaigns = [];

				//dd($allCampaigns);
				$dashboardType = explode(',',$domain_details->dashboard_type);


				$dashboard = DashboardType::whereIn('id',$dashboardType)->pluck('name','id')->all();

			}
		}

		$commamnData = [
			'profile_data'=> $domain_details,
			'allCampaigns'=>$allCampaigns,
			'dashboard'=>$dashboard,
			'role_id'=>$role_id,
			'dashboardTypes'=>$dashboardTypes,
			'announcements'=>$announcements,
			'user_package'=>$user_package,
			'project_count'=>$project_count,
			'notifications'=>$notifications
		];
  		// if($user <> null && $user->id == 9){
  		// 	dd($commamnData);
  		// }
		$view->with($commamnData);
	}


	private function getLogo($request_id,$user_id){

		$path  = 'public/storage/agency_logo/'.$user_id.'/'.$request_id.'/';

		if(file_exists($path)){
			$files1 = array_values(array_diff(scandir($path), array('..', '.')));
			
			if(!empty($files1)) {
				$image_url = URL::asset('public/storage/agency_logo/'.$user_id.'/'.$request_id.'/'.$files1[0]);
				$response['return_path']	=	$image_url; 
			}else{
				$response['return_path']	=	'';
			}
			return $response;  			
		}
	}


	private function agency_logo($request_id,$user_id,$image_name){

		if (file_exists(\config('app.FILE_PATH').'public/storage/agency_logo/'.$user_id.'/'.$request_id)) {
			$path  = 'public/storage/agency_logo/'.$user_id.'/'.$request_id.'/';
			if(file_exists($path)){
				$image_url = URL::asset('public/storage/agency_logo/'.$user_id.'/'.$request_id.'/'.$image_name);

			}
		}else{
			$image_url   =   '';
		}

		return $image_url;   
	}


	private function project_logo($request_id,$image_name){

		if (file_exists(\config('app.FILE_PATH').'public/storage/project_logo/'.$request_id)) {
			$path  = 'public/storage/project_logo/'.$request_id.'/';
			if(file_exists($path)){
				$image_url = URL::asset('public/storage/project_logo/'.$request_id.'/'.$image_name);
			}
		}else{
			$image_url   =   '';
		}
		
		return $image_url;   
	}


	private function getNotificationCount($parent_id,$user_id,$campaign_id){
		$count = 0; $data = array();
		$user_details = User::where('id',$user_id)->first();
		
		if(empty($user_details->notification_check_time) && ($user_details->notification_check_time == NULL)){
			$date = date('Y-m-d H:i:s',strtotime($user_details->created_at));
		}else{
			$date = $user_details->notification_check_time;
		}

		if($user_details->restrictions != NULL){
			$restrictions = $user_details->restrictions;
		}else{
			$restrictions = '';
		}
		

		$result = KeywordSearch::
		where('oneday_position','!=',0)
		->select('request_id','user_id','oneday_position','keyword','position','host_url','updated_at');		
		
		$final_record = $result->whereHas('SemrushUserData', function($q) use ($user_id,$parent_id,$restrictions,$campaign_id){
			$q->where('status', 0)
			->where('user_id',$parent_id);
			if($campaign_id <> null){
				$q->where('id',$campaign_id);
			}

			if($restrictions != ''){
				$q->whereIn('id',explode(',', $restrictions));
			}
		})
		->where('user_id',$parent_id)
		->orderBy('id','desc')->limit(20)->get();



		$result_count = $result->whereHas('SemrushUserData', function($q) use ($user_id,$parent_id,$restrictions,$campaign_id){
			$q->where('status', 0)
			->where('user_id',$parent_id);
			if($campaign_id <> null){
				$q->where('id',$campaign_id);
			}

			if($restrictions != ''){
				$q->whereIn('id',explode(',', $restrictions));
			}
			$q->where('notification_flag',0);
		})
		->where('user_id',$parent_id)
		->orderBy('id','desc')->count();	


		$data = array('result_count' =>$result_count,'campaign_id'=>$campaign_id,'result'=>$final_record);
	
		return $data;
	}

}