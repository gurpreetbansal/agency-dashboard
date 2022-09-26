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


class HeaderComposer
{

	use ViewKeyTrait;

	public function compose(View $view) {

		$allCampaigns = $domain_details =$dashboard =  array();
		$dashboardTypes = $regional_db = array();
		$role_id = $user_package = $project_count = '';
		$notifications = array();
		$announcements = Announcement::get();

		if((Auth::user() != null)){		
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
					
			$user_package = UserPackage::where('user_id',$user_id)->latest()->first();
			$project_count = SemrushUserAccount::where('user_id',$user_id)->where('status','=',0)->count();		
			// $notifications = $this->getNotificationCount($user_id,Auth::user()->id,$campaign_id);
			/*notification check for alerts */
			$notifications = [];

		}

		$dataComposer = [
			'user_package'=>$user_package,
			'project_count'=>$project_count,
			'notifications'=>$notifications
		];
  		
		$view->with($dataComposer);
	}

}