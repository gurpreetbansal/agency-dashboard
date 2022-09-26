<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\SemrushUserAccount;
use Auth;
use App\User;
use App\SharedAccess;
use App\Role;


class SharedAccessController extends Controller {
	
	public function index (){
		$user_id = Auth::user()->id;
		if(\Request::segment(1) !== 'profile-settings'){
            $check = User::check_subscription($user_id); 
            if($check == 'expired'){
                return redirect()->to('/dashboard');
            }
        }
		$shared_accounts = User::where('parent_id',$user_id)->orderBy('role_id','asc')->get();
		$master_user = User::where('id',$user_id)->first();
		$projects = SemrushUserAccount::where('user_id',$user_id)->where('status',0)->get();

		return view('vendor.shared_access.index',['projects'=>$projects,'master_user'=>$master_user,'user_id'=>$user_id,'shared_accounts'=>$shared_accounts]);
	}

	public function ajax_add_user_shared_access(Request $request){

		$response = array();
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|email|unique:users',
			'password' => 'required|min:6',
			'profile_image' => 'image|mimes:png,jpg,jpeg'
		]);
		if ($validator->fails()) {
			$array = array();
			foreach($validator->messages()->getMessages() as $field_name => $messages) {
	            foreach($messages as $message) {
	                $array[$field_name] = $message;
	            }
	        }       

	        $response['status'] = 0;
			$response['message'] = $array;
			return response()->json($response);

	       
		}
		
		// $user_id = $request['user_id'];
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		$parentData = User::select('company_name')->where('id',$user_id)->first();
		$getRole = Role::where('id',$request['shared_access_new_user_access_type'])->first();
		$imageName = $color =  NULL;
		if($request->has('profile_image')){
			$imageName = User::resizeImage($request->file('profile_image'),'profile_images',$request['name']);
		}else{
			$color = $this->get_random_color();
		}
		$create = User::create([
			'name' => $request['name'],
			'email' => $request['email'],
			'password' =>  Hash::make($request['password']),
			'profile_image' => $imageName,
			'restrictions' => implode(',',$request['shared_access_new_user_projects']),
			'role_id' => $request['shared_access_new_user_access_type'],
			'parent_id'=>$user_id,
			'role'=>$getRole->name,
			'company_name'=>$parentData->company_name,
			'email_verified'=>1,
			'email_verified_at'=>now(),
			'initial_background'=>$color
		]);
		$last_id = $create->id;
		$this->send_email($last_id,$request['password']);

		if($create){
			$res['status'] = 1;
			$res['message'] = 'Access given to new user successfully !';
		} else{
			$res['status'] = 2;
			$res['message'] = 'Error!! Please try again.';
		}

		return response()->json($res);
	}



	public function send_email($user_id,$password){
		$app_domain = \config('app.APP_DOMAIN');
		$user = User::where('id', $user_id)->select('name', 'email', 'company_name','parent_id','role','restrictions')->first();
		$parentData = User::where('id',$user->parent_id)->first();
		$projects = SemrushUserAccount::select('domain_name','domain_url')->whereIn('id',explode(',',$user->restrictions))->get();
		$link = 'https://' . $parentData->company_name . '.' . $app_domain . 'login';
		$data = array('name' => $user->name, 'email' => $user->email, 'from' => \config('app.MAIL_FROM_NAME'), 'link' => $link,'password'=>$password,'role'=>$user->role,'parent_name'=>$parentData->name,'projects'=>$projects);

		\Mail::send(['html' => 'mails/vendor/shared_access'], $data, function($message) use($user,$parentData) {
			$message->to($user->email, $user->name)->subject
			('You have been invited by '.$parentData->name.' - Agency Dashboard');
			$message->from(\config('app.mail'), 'Agency Dashboard');
		});
		if (\Mail::failures()) {
			return redirect()->back()->withErrors(['error' => 'Error sending email']);
		} else {
			return true;
		}
	}


	public function ajax_check_shared_email_exists(Request $request){
		$email = User::where('email','like','%'.$request->email.'%')->first();
		if(!empty($email)){
			$response['status'] = 1;
		}else{
			$response['status'] = 0;
		}
		return response()->json($response);
	}

	public function ajax_remove_shared_access (Request $request){
		$user_id = $request->user_id;
		$find_result = User::select('name','email')->where('id',$user_id)->first();
		
		$delete = User::where('id',$user_id)->delete();
		if($delete){
			$this->send_delete_email($find_result->email,$find_result->name);
			$response['status'] = 1;
			$response['message'] = 'User deleted successfully.';
		}else{
			$response['status'] = 0;
			$response['message'] = 'Error deleting user.';
		}
		return response()->json($response);
	}

	public function send_delete_email($email,$name){
		$app_domain = \config('app.APP_DOMAIN');
		$data = array('name' => $name, 'email' => $email);

		\Mail::send(['html' => 'mails/vendor/removed_shared_access'], $data, function($message) use($email,$name) {
			$message->to($email,$name)->subject
			('Access removed from Agency Dashboard');
			$message->from(\config('app.mail'), 'Agency Dashboard');
		});
		if (\Mail::failures()) {
			return redirect()->back()->withErrors(['error' => 'Error sending email']);
		} else {
			return true;
		}
	}

	public function render_shared_user($domain_name,$user_id){
		$shared_accounts = User::where('id',$user_id)->first();
		$projects = SemrushUserAccount::where('user_id',$shared_accounts->parent_id)->where('status',0)->get();		
		return \View::make('vendor.shared_access.render_shared_user',['shared_accounts'=>$shared_accounts,'projects'=>$projects,'user_id'=>$user_id]);
	}


	public function ajax_check_shared_email(Request $request){
		$email = User::where('email','like','%'.$request->email.'%')->where('id','!=',$request->user_id)->first();

		if(!empty($email)){
			$response['status'] = 1;
		}else{
			$response['status'] = 0;
		}
		return response()->json($response);
	}


	public function ajax_update_existing_shared_user(Request $request){
		$response = array();
		$get_user = User::where('id',$request->user_id)->first();
		$getRole = Role::where('id',$request['access_type'])->first();
		$response = array();
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|email',
			'profile_image' => 'image|mimes:png,jpg,jpeg'
		]);

		$validator->after(function ($validator)use($request,$get_user) {
			if($get_user->email != $request['email']){
				$check = User::where('email', $request['email'])->first();
				if(!empty($check)){
					$validator->errors()->add('email', 'Email already exists.');
				}
			}
		});
		if ($validator->fails()) {
			// $response['status'] = 0;
			// $response['message'] = 'Error!! Please fill out all the fields.';
			// $response['errors'] = $validator->messages();
			// return response()->json($response);

			$array = array();
			foreach($validator->messages()->getMessages() as $field_name => $messages) {
	            foreach($messages as $message) {
	                $array[$field_name] = $message;
	            }
	        }       

	        $response['status'] = 0;
			$response['message'] = $array;
			return response()->json($response);
		}

		
		if($request->has('profile_image')){
			$imageName = User::resizeImage($request->file('profile_image'),'profile_images',$request['name']);
			$data = [
						'name' => $request['name'],
						'email' => $request['email'],
						'profile_image' => $imageName,
						'restrictions' => $request['shared_edit_selected_id'],
						'role_id' => $request['access_type'],
						'role'=>$getRole->name,
						'initial_background'=>NULL
					];
		}else{
			$color = ($get_user->initial_background)?$get_user->initial_background:$this->get_random_color();
			$data = [
						'name' => $request['name'],
						'email' => $request['email'],
						'restrictions' => $request['shared_edit_selected_id'],
						'role_id' => $request['access_type'],
						'role'=>$getRole->name,
						'initial_background'=>$color
					];
		}
		
		$getRole = Role::where('id',$request['access_type'])->first();
		$update = User::where('id',$request->user_id)->update($data);
		if($update){
			$this->send_update_email($request->user_id);
			$response['status'] = 1;
			$response['message'] = 'Details updated successfully.';
		}else{
			$response['status'] = 0;
			$response['message'] = 'Error, updating details of the user.';
		}
		return response()->json($response);
	}


	public function send_update_email($user_id){
		$app_domain = \config('app.APP_DOMAIN');
		$user = User::where('id', $user_id)->select('name', 'email', 'company_name','parent_id','role','restrictions')->first();
		$projects = SemrushUserAccount::select('domain_name','domain_url')->whereIn('id',explode(',',$user->restrictions))->get();
		$parentData = User::where('id',$user->parent_id)->first();
		$data = array('name' => $user->name, 'email' => $user->email,'parent_name'=>$parentData->name,'projects'=>$projects);

		\Mail::send(['html' => 'mails/vendor/shared_access_update'], $data, function($message) use($user) {
			$message->to($user->email, $user->name)->subject
			('Account details updated - Agency Dashboard');
			$message->from(\config('app.mail'), 'Agency Dashboard');
		});
		if (\Mail::failures()) {
			return redirect()->back()->withErrors(['error' => 'Error sending email']);
		} else {
			return true;
		}
	}

	public function get_random_color(){	
		$color_array = array('red','yellow','blue','orange','green');	
        $random_keys = array_rand($color_array,1);	
        $color = 'background_'.$color_array[$random_keys];	
        return $color;	
	}

	public function ajax_get_role_based_projects (Request $request){
		$role_id = $request->role_id;
		$user_id = Auth::user()->id;
		$projects = SemrushUserAccount::
		where('user_id',$user_id)
		->where('status',0)
		->get();
		$li = '';
		if(!empty($projects) && isset($projects)){
			foreach($projects as $project){
				$disabled = '';
				if($role_id == 3){
					$disabled = ($project->get_assigned_projects($project->id) == 1)?"disabled class='disabled-project-shared'":'';
				}
				$li .='<option value='.$project->id.' '.$disabled.' >'.$project->domain_name.'</option>';
			}
		}
		return response()->json($li);
	}


	public function ajax_get_role_based_projects_existing_user (Request $request){
		$role_id = $request->role_id;
		$selected_user_id = $request->selected_user_id;
		$shared_accounts = User::select('id','restrictions')->where('id',$selected_user_id)->first();

		$user_id = Auth::user()->id;
		$projects = SemrushUserAccount::
		where('user_id',$user_id)
		->where('status',0)
		->get();
		$li = '';
		if(!empty($projects) && isset($projects)){
			foreach($projects as $project){
				$disabled = '';
				$selected = (in_array($project->id, explode(',',$shared_accounts->restrictions)))?"selected":'';
				if($role_id == 3){
					$disabled = ($project->get_assigned_projects_taken($project->id,$selected_user_id) == 1)?"disabled class='disabled-project-shared'":'';
				}
				$li .='<option value='.$project->id.' '.$selected.' '.$disabled.' >'.$project->domain_name.'</option>';
			}
		}
		return response()->json($li);
	}
}