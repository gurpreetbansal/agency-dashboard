<?php 

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\SemrushUserAccount;
use App\KeywordPosition;
use Crypt;
use URL;
use App\User;


class CommonController extends Controller {
	
	public function all_campaigns(Request $request){
		$getUser = User::findorfail(Auth::user()->id);
		$user_id = $getUser->id;

		$li = '';

		if($getUser->parent_id != ''){
			$user_id = $getUser->parent_id;
		}

		if(\Request::segment(1) !== 'profile-settings'){
			$check = User::check_subscription($user_id); 
			if($check == 'expired'){
				return $li;
			}
		}

		$url = config('app.FILE_PATH')."public/projects/".$user_id; 
		if (file_exists(\config('app.FILE_PATH') . 'public/projects/' . $user_id)){
			$file_url = config('app.FILE_PATH')."public/projects/".$user_id."/active_projects.json"; 
			if (file_exists($file_url)) {
				$data = file_get_contents($file_url);
				$search_data = json_decode($data,true);

				if($getUser->parent_id != ''){
					$id = explode(',',$getUser->restrictions);
					$search_data = array_filter($search_data, function ($var) use ($id) {
					    return in_array($var['id'], $id);
					});
					
				}
			
				if(count($search_data) > 0 && !empty($search_data)){
					foreach($search_data as $result) {
						$url = url('/campaign-detail/'.$result['id']);
						$serp_url = url('/serp/'.$result['id']);
						$serp_img = URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png');
						$some_condition_if_active = $disable_project = '';
						if($request->id == $result['id']){
							$some_condition_if_active = 'class=active';
						}

						// if(SemrushUserAccount::check_time_diff($result['created']) == 'loader'){
						// 	$disable_project = 'class=disable_project';
						// }
						
						$li	.= '<li uk-tooltip="title:'.$result['domain_name'].'; pos: top-left"' .$some_condition_if_active.'><a href="'.$url.'">'.ucfirst($result['host_url']).'</a><a href="'.$serp_url.'" class="btn small-btn icon-btn color-orange" uk-tooltip="title:Live Keyword Tracking; pos: top-center" title="" aria-expanded="false"><img src="'.$serp_img.'" class="mCS_img_loaded"></a></li>';
					} 
				} else {
					$li	.= '<li><a>No project Found</a></li>';
				}
			}
		}
		else{
			SemrushUserAccount::make_project_json();
		}

		return $li;
		
		//return response()->json($li);
	}


	public function ajax_show_view_key(Request $request){

		$link = '';
		$result = SemrushUserAccount::findorfail($request['rowid']);
		$user = User::findorfail($result->user_id);
		$encrypted_id = base64_encode($request['rowid'].'-|-'.$result->user_id.'-|-'.time());
		// $encrypted_id = Crypt::encrypt($request['rowid'].'-|-'.$result->user_id);
		$server_name = $request->getSchemeAndHttpHost();
		$host = \config('app.url');
		$link = $host.'project-detail/'.$encrypted_id;
		return response()->json($link);
	}


	public function ajax_check_campaign_time(Request $request){
		$seconds = time() - strtotime($request->timer);
		$hours = floor($seconds / 3600);
		$mins = floor(($seconds - ($hours*3600)) / 60); 
		if($hours == 0 && $mins <= 1){
			$response['status'] = 1;
		}else{
			$response['status'] = 0;
		}
		return response()->json($response);
	}

	public static function filter($filter, $array){
	    $filtered_array = array();
	    for($i = 0; $i < count($array); $i++){
	        if($array[$i] == $filter)
	            $filtered_array[] = $array[$i];
	    }
	    return $filtered_array;
	}

	public function reset_share_key(Request $request){
		$response = array();
		$project_id = $request->project_id;
		$link = '';
		$result = SemrushUserAccount::findorfail($project_id);
		$random = SemrushUserAccount::generateRandomString();
		$encrypted_id = base64_encode($project_id.'-|-'.$result->user_id.'-|-'.$random);
		$host = \config('app.url');
		$link = $host.'project-detail/'.$encrypted_id;
		SemrushUserAccount::where('id',$project_id)->update([
			'share_key'=>$encrypted_id
		]);
		$response = array('link'=>$link,'encrypted_id'=>$encrypted_id);
		return response()->json($response);
	}
}