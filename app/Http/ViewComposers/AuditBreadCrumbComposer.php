<?php

namespace App\Http\ViewComposers;

use App\SemrushUserAccount;
use Illuminate\Contracts\View\View;
use Request;
use URL;
use Auth;
use App\User;
use App\AuditTask;
use App\UserProfile;
use App\SiteAudit;
use App\SiteAuditSummary;

class AuditBreadCrumbComposer
{

	public function compose(View $view) {
		
		$userProfile = $auditTask = array();

		if((Auth::user() !== null)){

			$data = $view->getData();
			
			if(Request::segment(2) == 'detail'){
				$campaign_id = Request::segment(3);
			}else{
				$audit_id = Request::segment(4);
				$audits = SiteAudit::where('id',$audit_id)->first();
				$campaign_id = $audits <> null ? $audits->summary->campaign_id : 0;
			}
			
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child

			$domain_details = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();
			
			if(isset($domain_details->ProfileInfo->agency_logo)){
				$logo = $this->agency_logo($campaign_id,$user_id,$domain_details->ProfileInfo->agency_logo);
				$domain_details->logo_data = $logo;
			}

			if(isset($domain_details->project_logo) && !empty($domain_details->project_logo)){
				$projectlogo = $this->project_logo($campaign_id,$domain_details->project_logo);
				$domain_details->project_logo = $projectlogo;
			}
			
		}else{
			$data = $view->getData();
			$userId = null;
			$user_id = null;
			if(Request::segment(1) == 'pdf'){

				$encription = base64_decode(Request::segment(4));

				$encrypted_id = explode('-|-',$encription);
				$audit_id = $encrypted_id[0];
				$userId = $encrypted_id[1];

				if(Request::segment(3) == 'summary'){
					$audits = SiteAuditSummary::where('id',$audit_id)->first();
					$campaign_id = $audits <> null ? $audits->campaign_id : 0;
				}else{
					$audits = SiteAudit::where('id',$audit_id)->first();
					$campaign_id = $audits <> null ? $audits->summary->campaign_id : 0;
				}

			}else if(Request::segment(2) == 'detail'){
				$campaign_id = Request::segment(3);
			}else{
				$audit_id = Request::segment(4);
				$audits = SiteAudit::where('id',$audit_id)->first();
				$campaign_id = $audits <> null ? $audits->summary->campaign_id : 0;
			}
			if($userId !== ''){
				$user_id = User::get_parent_user_id($userId); //get user id from child
			}
			
			$domain_details = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();
			
			if(isset($domain_details->ProfileInfo->agency_logo)){
				$logo = $this->agency_logo($campaign_id,$user_id,$domain_details->ProfileInfo->agency_logo);
				$domain_details->logo_data = $logo;
			}

			if(isset($domain_details->project_logo) && !empty($domain_details->project_logo)){
				$projectlogo = $this->project_logo($campaign_id,$domain_details->project_logo);
				$domain_details->project_logo = $projectlogo;
			}

		}
		// dd($domain_details->project_logo);
		$view->with(['profile_data'=> $domain_details]);
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

}