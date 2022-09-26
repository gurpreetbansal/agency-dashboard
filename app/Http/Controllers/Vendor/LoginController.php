<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\User;
use Crypt;
use Session;


class LoginController extends Controller {

	

	public function showLogin($domain_name=null) {
		
		$authUser = Auth::user();
		$host = \Route::current()->parameter('account');
		// dd($host);
		
		if(!empty(Auth::user())){

			if($host == '' || $host == null){
				User::where('id',Auth::user()->id)->update(['login_as'=>2]);
				$hostname = $authUser->company_name;
				return redirect('https://' . $hostname . '.' . \config('app.APP_DOMAIN') . 'dashboard');
			}else{
				if($host == Auth::user()->company_name){
					User::where('id',Auth::user()->id)->update(['login_as'=>2]);
					return redirect('/dashboard');
				}else{
					return redirect('/login')->with('error', 'Oops!, Wrong combination of Email-Address And Password!');
				}
			}
		}
		return view('vendor.login-new',['domain_name'=>$domain_name]);
	}

	public function doLoginNew(Request $request) {
		$validator = Validator::make($request->all(), [
			'email' => 'required|email:rfc,dns',
			'password' => 'required'
		]);

		if ($validator->fails()) {
			return back()->withInput()->withErrors($validator);
		} else {
			$host = \Route::current()->parameter('account');

			$userdata = User::where('email',$request->email)->first();

			if(empty($userdata)){
				return redirect('/login')->with('error', 'Account does not exists');
			}
			
			if(($host != '' || $host != null) && !empty($userdata) && ($host != $userdata->company_name)){
				return redirect('/login')->with('error', 'Wrong combination of Email-Address And Password');
			}

			if($userdata){
				$check = Hash::check($request->password,$userdata->password);

				if($check == 1){
				  if($userdata->status==1){

				  	$remember_me  = ( !empty( $request->remember ) )? TRUE : FALSE;

				  	Auth::login($userdata,$remember_me);
				  	Auth::user()->last_login = now();
					Auth::user()->save();
					
				  	if(Auth::user()->role_id == 1){ //login for admin
						$request->session()->put('admin_session', Auth::user()->id);
                		return redirect()->intended('admin/dashboard');
					}

						
					if(Auth::user()->company_name != ''){
						if($host == '' || $host == null){
							User::where('id',Auth::user()->id)->update(['login_as'=>2]);
							$hostname = Auth::user()->company_name;
							$request->session()->put('user_session', Auth::user()->id);
							return redirect('https://' . $hostname . '.' . \config('app.APP_DOMAIN') . 'dashboard/'.Crypt::encrypt(Auth::user()->id));
						}else{
							if($host == Auth::user()->company_name){
								User::where('id',Auth::user()->id)->update(['login_as'=>2]);
								return redirect('/dashboard');
							}else{
								return redirect('/login')->with('error', 'Wrong combination of Email-Address And Password');
							}
						}
						
					}elseif(Auth::user()->company_name == '' || Auth::user()->company_name == null){
						User::where('id',Auth::user()->id)->update(['login_as'=>2]);
						$hostData = User::where('id',Auth::user()->parent_id)->first();
						$request->session()->put('user_session', Auth::user()->id);
						return redirect('https://' . $hostData->company_name . '.' . \config('app.APP_DOMAIN') . 'dashboard/'.Crypt::encrypt(Auth::user()->id));
						
					}
				  }else{
					return redirect('/login')->with('error', 'Your Account is suspended, please contact admin');
				  }
				}else{
					return redirect('/login')->with('error', 'Wrong combination of Email-Address And Password');
				}
			}else{
				return redirect('/login')->with('error', 'Wrong combination of Email-Address And Password');
			}
		}
	}

	public function back_to_admin(Request $request){
		$get = User::where('role_id',1)->select('id')->first();
		$redirect_link = 'https://' . \config('app.APP_DOMAIN') . 'admin/dashboard/' . Crypt::encrypt($get->id);
		$request->session()->flush();
		return redirect($redirect_link);
	}


	public function logout(Request $request){
		Auth::user()->last_login = now();
		Auth::user()->save();
        Auth::logout();
		$request->session()->forget('user_session');
		$request->session()->flush();
		$redirect_link = 'https://' . \config('app.APP_DOMAIN') . 'logout_session';
		return redirect($redirect_link);
		// return \Redirect::to(\config('app.base_url'));
	}

	public function logout_session(Request $request){
		Auth::logout();
		$request->session()->forget('user_session');
		$request->session()->flush();
		return \Redirect::to(\config('app.base_url'));
	}
}
