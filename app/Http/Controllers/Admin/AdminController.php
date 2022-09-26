<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\PasswordReset;
use Carbon\Carbon;
use Mail;
use App\Subscription;
use App\Package;
use App\Country;
use App\UserAddress;
use App\KeywordSearch;
use App\SemrushUserAccount;
use App\ApiBalance;
use Crypt;

class AdminController extends Controller {

    public function showLogin() {
        return view('admin.login');
    }

    public function doLogin(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $ifExists = User::where('email', $request->email)->first();
            if (!empty($ifExists)) {
                $remember_me = $request->has('remember') ? true : false;
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'admin'], $remember_me)) {
                    $request->session()->put('admin_session', Auth::user()->id);

                    return redirect()->intended('admin/dashboard');
                } else {
                    $messages = array('password' => 'Invalid credentials!');
                    return redirect()->back()->withErrors($messages);
                }
            } else {
                $messages = array('email' => 'The email is not registered with us!');
                return redirect()->back()->withErrors($messages);
            }
        }
    }

    public function dashboard($uid=null) {
       // dd($uid);
        if (!empty($uid)) {
            $user = User::where('id', Crypt::decrypt($uid))->first();
            Auth::login($user);
        }

        $dfs_balance = ApiBalance::where('name','DFS')->first();

        $user_count = User::where('role_id', '2')->count();
        $subscriptions = Subscription::where('trial_ends_at', '<=', date('Y-m-d H:i:s', strtotime(now())))->sum('amount');
        $recent_signups = User::where('role_id', '2')->orderBy('id','desc')->take(10)->get();
      
        if (!empty($recent_signups)) {
            foreach ($recent_signups as $key => $value) {
                if(isset($value->UserPackage)){
                   $package = Package::where('id', $value->UserPackage->package_id)->first();

               }else{
                $package = array();
            }
            $recent_signups[$key]->Package = $package;
        }
    }

    $logins  = User::where('role_id', '2')->orderBy('id','desc')->select('name','last_login')->get();
    $thirty_day_user = User::where('role_id','2')->whereDate('created_at','>=',date('Y-m-d',strtotime(now().' - 29 days')))->count();
   $total_keywords = KeywordSearch::count();
   $thirty_Day_keywords = KeywordSearch::whereDate('created_at','>=',date('Y-m-d',strtotime(now().' - 29 days')))->count();


   $total_projects = SemrushUserAccount::count();
   $thirty_Day_projects = SemrushUserAccount::whereDate('created','>=',date('Y-m-d',strtotime(now().' - 29 days')))->count();
   

    return view('admin/dashboard', ['user_count' => $user_count, 'subscriptions' => $subscriptions, 'recent_signups' => $recent_signups,'logins'=>$logins,'thirty_day_user'=>$thirty_day_user,'thirty_Day_keywords'=>$thirty_Day_keywords,'total_keywords'=>$total_keywords,'thirty_Day_projects'=>$thirty_Day_projects,'total_projects'=>$total_projects,'dfs_balance'=>$dfs_balance]);
}

public function show_forgot_password() {
    return view('admin.forgot_password');
}

public function send_password_link(Request $request) {

    $validator = Validator::make($request->all(), ['email' => 'required']);
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator);
    } else {
        $user = User::where('email', '=', $request->email)->first();
//            print_r($user);
//            die;
        if (empty($user)) {
            return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
        }

        PasswordReset::create([
            'email' => $request->email,
            'token' => $this->generateRandomString(60),
            'created_at' => Carbon::now()
        ]);
        $tokenData = PasswordReset::where('email', $request->email)->first();

        if ($this->sendResetEmail($request->email, $tokenData->token)) {
            return redirect()->back()->with('status', trans('A reset link has been sent to your email address.'));
        } else {
            return redirect()->back()->withErrors(['error' => trans('A Network Error occurred. Please try again.')]);
        }
    }
}

private function sendResetEmail($email, $token) {
    $user = User::where('email', $email)->select('name', 'email')->first();
    $link = \config('app.base_url') . 'admin/reset/' . $token;

    $data = array('name' => $user->name, 'email' => $user->email, 'link' => $link);
    Mail::send(['text' => 'mail'], $data, function($message) use($user) {
        $message->to($user->email, 'Imark')->subject
        ('Reset your password');
        $message->from(\config('app.mail'), 'Imark');
    });
    if (Mail::failures()) {
        return redirect()->back()->withErrors(['error' => 'Error sending reset email']);
    } else {
        return true;
    }
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

public function reset($token) {
    return view('admin.reset', ['token' => $token]);
}

public function update_password(Request $request) {
        //Validate input
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'password' => 'required|confirmed',
        'token' => 'required'
    ]);

        //check if payload is valid before moving on
    if ($validator->fails()) {
        return redirect()->back()->withErrors(['email' => 'Please complete the form']);
    }

    $password = $request->password;
// Validate the token
    $tokenData = PasswordReset::where('token', $request->token)->first();
// Redirect the user back to the password reset request form if the token is invalid
    if (!$tokenData)
        return view('admin.forgot_password');

    $user = User::where('email', $tokenData->email)->first();
// Redirect the user back if the email is invalid
    if (!$user)
        return redirect()->back()->withErrors(['email' => 'Email not found']);
//Hash and update the new password
    $user->password = \Hash::make($password);
        $user->update(); //or $user->save();
        //login the user immediately they change password successfully
        Auth::login($user);

        //Delete the token
        PasswordReset::where('email', $user->email)->delete();

        //Send Email Reset Success Email
        if ($user) {
            return view('admin.dashboard');
        } else {
            return redirect()->back()->withErrors(['email' => trans('A Network Error occurred. Please try again.')]);
        }
    }

    public function changepassword() {
        return view('admin.changepassword');
    }

    public function change_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:6|max:15',
            'confirm_password' => 'same:password',
        ], [
            'current_password.required' => 'The field is required',
        ]);
        $validator->after(function ($validator)use($request) {
            if (!Hash::check($request->current_password, auth()->user()->password)) {
                $validator->errors()->add('current_password', 'Your current password is incorrect.');
            }
        });
        if ($validator->fails()) {
            return back()->withErrors($validator);
        } else {
            User::find(auth()->user()->id)->update(['password' => Hash::make($request->password)]);
            return back()->with('success', 'Password Updated successfully!');
        }
    }

    public function profile() {
        $countries = Country::get();
        $user = User::with('UserAddress')->where('id', Auth::user()->id)->first();
        return view('admin.profile', compact('user', 'countries'));
    }

    public function updateprofile(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'profile_image' => 'image|mimes:jpg,jpeg,png',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            if ($request->has('profile_image')) {
                $image = $request->file('profile_image');
                $name = \Str::slug($request->name) . '_' . time();
                $folder = 'admin_images/';
                $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                User::uploadOne($image, $folder, 'public', $name);
            } else {
                $user_data = User::where('id', Auth::user()->id)->select('profile_image')->first();
                $filePath = $user_data->profile_image;
            }
            $update = User::where('id', Auth::user()->id)->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'profile_image' => $filePath
            ]);



            if ($update) {
                return back()->with('success', 'Profile Updated successfully!');
            } else {
                return back()->with('error', 'Error Updating profile');
            }
        }
    }


}