<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\RegionalDatabse;
use App\SemrushUserAccount;
use App\User;
use Auth;
use URL;

class ArchiveController extends Controller {

	public function ajax_list_archived_projects(Request $request){
		$data = array();
		$string = $request['search']["value"];
		$field = ['domain_name','domain_url'];
		$getUser = User::get_parent_user_id(Auth::user()->id);
		$searcherArr = RegionalDatabse::get_search_arr();
		$results = SemrushUserAccount::
				where('user_id', $getUser)
				->where('status', 1)
				->skip($request['start'])->take($request['length'])
				->where(function ($query) use($string, $field) {
					for ($i = 0; $i < count($field); $i++){
						$query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
					}      
				})
				->get();

		foreach ($results as $key => $result) {
			$manager_name  = $mimage = $tags = $action = '';
			$managerDetails = User::
			whereRaw("find_in_set($result->id, restrictions)")
			->select('id','name','profile_image')
			->where('role_id',3)
			->first();

			if(isset($managerDetails->name) && !empty($managerDetails->name)){
				$manager_name = $managerDetails->name;
			}

			if(isset($managerDetails->profile_image)){
				$mimage = 	'<img src="public/storage/'.$managerDetails->profile_image.'" alt="image">';
			}

			$link = URL::asset('/public/flags/'.$result->regional_db.'.png');

			$key = array_search($result->regional_db, array_column($searcherArr, 'value'));


			$searchlocation = explode('.', $searcherArr[$key]['key']);

			if(count($searchlocation) >2){
				$location = '.'.$searchlocation[1].'.'.$searchlocation[2];
			}else{
				$location = '.'.$searchlocation[1];
			}

			if(isset($result->tags) && !empty($result->tags)){
				$explode_tags = explode(',',$result->tags);
				foreach ($explode_tags as $key => $value) {
					$tags .='<span class="badge badge-primary ml-2">'.$explode_tags[$key].'</span>';
				}				
			}
			
			$data[] = [
				'<a href="https://'. $result->domain_url .'" target="_blank" data-toggle="tooltip" title="" data-original-title="Open in new window">		<i class="fa fa-external-link ml-0 mr-1"></i>    </a> <a href="/new-dashboard/'.$result->id.'" class="ml-0 mr-1">'. $result->domain_name .'</a> <a data-toggle="tooltip" title="" data-original-title="'.$manager_name.'"><span class="manager_imgae">'.$mimage.'</span></a>'.$tags,

				$result->regional_db != 'us' ? '<figure class="country-icon"><img src="/public/vendor/images/google-logo-icon.png"></figure> '. $location : '<figure class="country-icon"><img src="/public/vendor/images/google-logo-icon.png"></figure> .com',


				'<span class=""><img src="'.$link.'"></span>',
				'<span class="">'.date('Y-m-d',strtotime($result->created)).'</span>',
				'<a data-id="'.$result->id.'" class="restore_project btn btn-small" href="javascript:;" data-placement="top" title="Restore Project" data-hover="tooltip" data-original-title=""><i class="fas fa-undo"></i></a>
				<a data-id="'.$result->id.'" class="delete_project btn btn-small" href="javascript:;" data-placement="top" data-hover="tooltip" title="Delete Project"><i class="fa fa-trash"></i></a>'
			];	
		}

		$json_data = array(
			"draw"            => intval($request['draw']),   
			"recordsTotal"    => count($results),  
			"recordsFiltered" => count($results),
			"data"            => $data   
		);

		return response()->json($json_data);
	}

	public function ajax_restore_project(Request $request){
		$restore = SemrushUserAccount::where('id',$request['request_id'])->update(['status'=>0]);
		if($restore){
			$response['status'] = 1;
			$response['message']='Project restored successfully!';
		}else{
			$response['status'] = 0;
			$response['message']='Error!! Please try again.';
		}
		return response()->json($response);
	}

	public function ajax_delete_project(Request $request){
		$delete = SemrushUserAccount::where('id',$request['request_id'])->update(['status'=>2,'deleted_at'=>now()]);
		if($delete){
			$response['status'] = 1;
			$response['message'] = 'Project deleted successfully';
		}else{
			$response['status'] = 0;
			$response['message'] = 'Error!! deleting project';
		}
		return response()->json($response);
	}
}
