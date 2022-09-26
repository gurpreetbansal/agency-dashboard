<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\ApiBalance;
use App\SemrushUserAccount;
use App\KeywordSearch;
use App\Invoice;
use Auth;
use DB;
use App\InvoiceItem;
use App\Subscription;
use App\Country;
use App\UserAddress;
use File;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

	public function show_login() { 
        return view('Admin.login');
    }

    public function do_login (Request $request){
        $validator = Validator::make($request->all(), [
         'email' => 'required',
         'password' => 'required'
     ]);

        if ($validator->fails()) {
         return back()->withInput()->withErrors($validator);
     } else {
         $ifExists = User::where('email', $request->email)->first();
         if (!empty($ifExists)) {
            $remember_me = $request->has('remember') ? true : false;
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'admin'], $remember_me)) {
                $request->session()->put('admin_session', Auth::user()->id);

                return redirect()->intended('admin/dashboard');
            } else {
                $messages = array('password' => 'Incorrect Password');
                return redirect()->back()->withErrors($messages);
            }
        } else {
            $messages = array('email' => 'The email is not registered with us.');
            return redirect()->back()->withErrors($messages);
        }
    }
}

public function dashboard(){
    $monthly_amount = 0;
    $dfs_balance = ApiBalance::where('name','DFS')->first();
    $total_accounts = User::where('role_id', '2')->count();
    $active_accounts = User::where('role_id', '2')->where('status',1)->where('subscription_status',1)->count();
    $total_projects = SemrushUserAccount::
    where('status',0)
    ->whereHas('UserInfo', function ($query) {
        $query->where('status', 1)
        ->where('subscription_status',1);
    })
    ->count();


    $total_keywords = KeywordSearch::
    whereHas('users', function ($query) {
        $query->where('status', 1)
        ->where('subscription_status',1);
    })
    ->count();

    $monthly_amount = DB::table('subscriptions')
    ->where('subscriptions.stripe_status','active')
    ->join('invoices', 'subscriptions.id', '=', 'invoices.subscription_master_id')
    ->join('invoice_items','invoices.id','=','invoice_items.invoice_master_id')
    ->select('subscriptions.id as subs_id', 'invoices.id as invoice_master_id','invoice_items.amount','invoice_items.description')
    ->whereDate('subscriptions.created_at','>=',date('Y-m-d', strtotime('- 30 days')))
    ->whereDate('subscriptions.created_at','<=',date('Y-m-d'))
    ->sum('invoice_items.amount');

    return view('Admin.dashboard',compact('dfs_balance','total_accounts','total_projects','total_keywords','monthly_amount','active_accounts'));
}


public function get_profile(){
    $user_id = Auth::user()->id;
    $countries = Country::get();
    $user = User::with('UserAddress')->where('id', $user_id)->first();
    return view('Admin.profile_settings',compact('user_id','user','countries'));
}

public function post_profile_settings(Request $request){
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'phone' => 'required|digits:10',
        'address_line_1' => 'required',
        'city' => 'required',
        'profile_image' => 'image|mimes:jpg,jpeg,png|max:2048',
        'country' => 'required',
        'zip' => 'required'
    ]);
    if ($validator->fails()) {
        $array = array();
        foreach($validator->messages()->getMessages() as $field_name => $messages) {
            foreach($messages as $message) {
                $array[$field_name] = $message;
            }
        }         
        $response['status'] = 3;
        $response['message'] = $array;
    } else {
        $get_user_data = User::where('id',$request['user_id'])->select('company_name')->first();
        if($get_user_data->company_name != $request['company_name']){
            $check = User::where('company_name', $request['company_name'])->where('role_id', 2)->first();
            if(!empty($check)){
                $response['status'] = 0;
                $response['message'] = 'Company Name already taken';
                return response()->json($response);
            }
        }


        if($request->hasFile('profile_image')) {
            $profile_image_name = User::resizeImage($request->file('profile_image'),'profile_images',$request['name']);
            User::where('id', $request['user_id'])->update([
                'name' => $request['name'],
                'phone' => $request['phone'],
                'profile_image' => $profile_image_name
            ]);
        }else{
            User::where('id', $request['user_id'])->update([
                'name' => $request['name'],
                'phone' => $request['phone']
            ]);
        }



        $address = UserAddress::updateOrCreate(
            ['user_id' => $request['user_id']], [
                'address_line_1' => $request['address_line_1'],
                'address_line_2' => $request['address_line_2'],
                'city' => $request['city'],
                'country' => $request['country'],
                'zip' => $request['zip']
            ]
        );
        if ($address) {
            $response['status'] = 1;
            $response['message'] = 'Profile information updated successfully!';                
        } else {
            $response['status'] = 2;
            $response['message'] = 'Error!! Updating profile information.';
        }

    }
    return response()->json($response);
}

    public function ajax_remove_profile_picture(Request $request){
        $response = array();
        $image = explode('/',$request->profile_image);
        $profile_image = end($image);
        $products = User::where('id',$request->user_id)->update([
            'profile_image' => null,
            'initial_background' => User::get_random_color()
        ]);
        if($products){
            $fullImgPath = storage_path('app/public/profile_images/'.$profile_image);
            if(File::exists($fullImgPath)) {
                File::delete($fullImgPath);
            }
            $response['status'] = 1;
            $response['message'] = 'Profile picture removed successfully.';
        }else{
            $response['status'] = 0;
            $response['message'] = 'Error removing profile picture.';
        }

        return response()->json($response);
    }

    public function update_change_password(Request $request){
        $validator = Validator::make($request->all(), [
            'new_password' => 'min:6|max:15',
            'confirm_password' => 'same:new_password',
        ]);
        $validator->after(function ($validator)use($request) {
            if (!Hash::check($request->current_password, auth()->user()->password)) {
                $validator->errors()->add('current_password', 'current password is incorrect.');
            }
        });
        if ($validator->fails()) {
            $array = array();
            foreach($validator->messages()->getMessages() as $field_name => $messages) {
                foreach($messages as $message) {
                    $array[$field_name] = $message;
                }
            }         
            $response['status'] = 0;
            $response['message'] = $array;

        } else {
            User::find(Auth::user()->id)->update(['password' => Hash::make($request['new_password'])]);
            $response['status'] =1;
            $response['message'] = 'Password Updated successfully!';
        }

        return response()->json($response);

    }
}