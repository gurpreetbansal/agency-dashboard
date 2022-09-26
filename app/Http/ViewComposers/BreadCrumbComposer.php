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


class BreadCrumbComposer
{

	use ViewKeyTrait;

	public function compose(View $view) {
		
	

		$allCampaigns = $domain_details =$dashboard =  array();
		$dashboardTypes = $regional_db = array();
		$role_id = $user_package = $project_count = '';
		$notifications = array();
		$announcements = Announcement::get();

		if((Auth::user() !== null)){	

			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
					
			$user_package = UserPackage::where('user_id',$user_id)->latest()->first();
			$project_count = SemrushUserAccount::where('user_id',$user_id)->where('status','=',0)->count();		
			$campaign_id = Request::segment(2);

			if(Request::segment(1) == 'project-detail' && Request::segment(2) <> 'sidebar'){

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
			}
		}

		$view->with(['profile_data'=> $domain_details]);
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

}