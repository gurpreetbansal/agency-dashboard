<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\SemrushUserAccount;
use Auth;
use App\User;
use App\SharedAccess;
use App\Role;


class SettingsController extends Controller {

	public function index(){
		$user_id = Auth::user()->id;
		$shared_accounts = User::where('parent_id',$user_id)->get();
		$master_user = User::where('id',$user_id)->first();
		$projects = SemrushUserAccount::where('user_id',$user_id)->get();
		return view('vendor.settings.index',['projects'=>$projects,'master_user'=>$master_user,'user_id'=>$user_id,'shared_accounts'=>$shared_accounts]);
	}

	public function save_settings (Request $request){
		$user_id = $request['user_id'];
	
			for($i=0; $i < count($request['name']) ;$i++){
			$getRole = Role::where('id',$request['access'][$i])->first();

			if(isset($request['shared_id'][$i])){
				$getdata = User::where('id',$request['shared_id'][$i])->first(); //shared access existing records
				//dd($getdata);
				$imageName = $getdata->profile_image;

				/* password  check*/
				if (trim($request['password'][$i]) == $getdata->password) {
				   $password = $getdata->password;
				}else{
					$old_pass = $request['password'][$i];
					$password = Hash::make($request['password'][$i]); 
					$this->send_email($request['shared_id'][$i],$old_pass);
				}

				/*image check*/
				
				if(isset($request['image'][$i])){
					$image = $request->file('image')[$i];
					$name = \Str::slug($request->name[$i]) . '_' . time();
					$folder = '/profile_images/';
					$filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
					$imageName = $filePath;
					User::uploadOne($image, $folder, 'public', $name);
				}
				
				$create = User::where('id',$request['shared_id'][$i])->update([
					'name' => $request['name'][$i],
					'email' => $request['email'][$i],
					'password' => $password,
					'profile_image' => $imageName,
					'restrictions' => implode(',',$request['restrictions'][$i]),
					'role_id' => $request['access'][$i],
					'role'=>$getRole->name,
					'email_verified'=>1,
					'email_verified_at'=>now()
				]);
				
			}else{
				$image = $request->file('image')[$i];
				$name = \Str::slug($request->name[$i]) . '_' . time();
				$folder = '/profile_images/';
				$filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
				$imageName = $filePath;
				User::uploadOne($image, $folder, 'public', $name);

				$create = User::create([
					'name' => $request['name'][$i],
					'email' => $request['email'][$i],
					'password' =>  Hash::make($request['password'][$i]),
					'profile_image' => $imageName,
					'restrictions' => implode(',',$request['restrictions'][$i]),
					'role_id' => $request['access'][$i],
					'parent_id'=>$user_id,
					'role'=>$getRole->name,
					'email_verified'=>1,
					'email_verified_at'=>now()
				]);
				$last_id = $create->id;
				$this->send_email($last_id,$request['password'][$i]);
			}

			
		}


		if($create){
			$res['status'] = 1;
			$res['message'] = 'Settings updated successfully!';
		} else{
			$res['status'] = 0;
			$res['message'] = 'Error!! Please try again.';
		}

		return response()->json($res);
		
	}


	public function unique_email(Request $request){
		return  User::check_email_exists($request['email']);
	}

	public function send_email($user_id,$password){
		$app_domain = \config('app.APP_DOMAIN');
        $user = User::where('id', $user_id)->select('name', 'email', 'company_name','parent_id','role')->first();
        $parentData = User::where('id',$user->parent_id)->first();
        $link = 'https://' . $parentData->company_name . '.' . $app_domain . 'login';
        $data = array('name' => $user->name, 'email' => $user->email, 'from' => \config('app.MAIL_FROM_NAME'), 'link' => $link,'password'=>$password,'role'=>$user->role,'parent_name'=>$parentData->name);

        \Mail::send(['html' => 'mails/sharedAccess'], $data, function($message) use($user) {
            $message->to($user->email, $user->name)->subject
                    ('Welcome to Agency Dashboard!');
            $message->from(\config('app.mail'), 'Agency Dashboard');
        });
        if (\Mail::failures()) {
            return redirect()->back()->withErrors(['error' => 'Error sending email']);
        } else {
            return true;
        }
	}
	
}