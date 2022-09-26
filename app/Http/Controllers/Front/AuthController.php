<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\UserPackage;
use App\Package;
use App\Coupon;
use App\Subscription;
use App\KeywordSearch;
use App\SemrushUserAccount;
use Mail;
use Auth;
use Crypt;


class AuthController extends Controller {


    public function ajax_pricing_action(Request $request){
        $string = $request->id.'+'.$request->state.'+'.time();
        $final['string']  = base64_encode($string);
        return response()->json($final);
    }


    public function ajax_user_pricing_action(Request $request){
        $user = User::where('id',$request->user_id)->first();
        $string = $user->email.'+'.$user->company.'+'.$user->company_name.'+'.$request->id.'+'.$request->state.'+'.$request->user_id.'+renew';
        $final['string']  = base64_encode($string);
        return response()->json($final);
      
    }

     public function register(Request $request){
        if($request->has('id')){
             $value = base64_decode($request->id);
             $explode = explode('+', $value);
        $packageId = $explode[0];
        $state_value = $explode[1];

        return view('front.registeration', ['packageId' => $packageId,'state_value'=>$state_value]);
        }else{
            abort(404);
        }

    }

    public function doRegister(Request $request) {
        $final_string = $request->email.'+'.$request->password.'+'.$request->company.'+'.$request->company_name.'+'.$request->package_id.'+'.$request->state_value.'+'.$request->coupon;
        $encyrpt_id = base64_encode($final_string);

        $dataReturn['status'] = 'stripe_subscription';
        $dataReturn['string'] = $encyrpt_id;

        return response()->json($dataReturn);
    }

    private function registeration($user_id) {
        $app_domain = \config('app.APP_DOMAIN');
        $user = User::where('id', $user_id)->select('name', 'email', 'company_name','company')->first();
        $link = 'https://' . $user->company_name . '.' . $app_domain . 'login';
        $data = array('name' => $user->company, 'email' => $user->email, 'from' => \config('app.MAIL_FROM_NAME'), 'link' => $link);
        \Mail::send(['html' => 'mails/front/registeration'], $data, function($message) use($user) {
            $message->to($user->email, $user->company)->subject
            ('Welcome to Agency Dashboard!');
            $message->from(\config('app.mail'), 'Agency Dashboard');
        });
        if (\Mail::failures()) {
            return redirect()->back()->withErrors(['error' => 'Error sending email']);
        } else {
            return true;
        }
    }

    private function email_verification($user_id){
        $app_domain = \config('app.APP_DOMAIN');
        $user = User::where('id', $user_id)->first();
        $link = 'https://' . $user->company_name . '.' . $app_domain . 'confirmation/'.$user->email_verification_token;
        $data = array('name' => $user->name, 'email' => $user->email, 'from' => \config('app.MAIL_FROM_NAME'), 'link' => $link);
        \Mail::send(['html' => 'mails/front/email_verification'], $data, function($message) use($user) {
            $message->to($user->email, $user->name)->subject
            ('Activate Account - Agency Dashboard');
            $message->from(\config('app.mail'), 'Agency Dashboard');
        });
        if (\Mail::failures()) {
            return redirect()->back()->withErrors(['error' => 'Error sending email']);
        } else {
            return true;
        }
    }

    public function check_email_exists(Request $request) {
        $check = User::where('email', $request->email)->first();
        if (!empty($check)) {
            echo "taken";
        } else {
            echo 'not_taken';
        }
        exit();
    }
    
    //vanity url
    public function check_company_name_exists(Request $request) {
        $check = User::where('company_name', $request->company_name)->where('role', 'front')->first();
        if (!empty($check)) {
            echo "taken";
        } else {
            echo 'not_taken';
        }
        exit();
    }

    //company name
     public function check_company_exists(Request $request) {
        $check = User::where('company', $request->company)->where('role', 'front')->first();
        if (!empty($check)) {
            echo "taken";
        } else {
            echo 'not_taken';
        }
        exit();
    }

   public function check_coupon_code(Request $request){
    if(!empty($request->code) || $request->code != NULL){
        $result = Coupon::where('code',$request->code)->first();
        if(empty($result)){
            echo "not_exists";
        }else{
            echo "exists";
        }
    }else{
        echo "empty";
    }
        exit();
   }


   public function ajax_check_pricing_downgrade(Request $request){
    $response = array();
    $package = Package::where('id',$request->id)->first();
    $user = User::where('id',$request->user_id)->first();
    $used_keywords = KeywordSearch::where('user_id',$request->user_id)->count();
    $used_projects = SemrushUserAccount::where('user_id',$request->user_id)->where('status',0)->count();

    if(($package->number_of_projects < $used_projects) || ($package->number_of_keywords < $used_keywords)){
        $response['status'] = 0;
        $response['message'] = 'Delete Projects/Keywords to downgrade your subscription';
    }

    if(($package->number_of_projects >= $used_projects) && ($package->number_of_keywords >= $used_keywords)){ 
        $string = $user->email.'+'.$user->company.'+'.$user->company_name.'+'.$request->id.'+'.$request->state.'+'.$request->user_id.'+renew';
        $response['string']  = base64_encode($string);
        $response['status'] = 1;
        $response['message'] = 'continue';
        if($request->id == 5){
            $response['package_type'] = 'free';
        }else{
            $response['package_type'] = 'paid';
        }
    }
     return response()->json($response);
   }


   public function ajax_continue_downgrade(){
        $user = User::where('id',Auth::user()->id)->first();
        if(isset($user) && !empty($user)){
           $link = $_SERVER['REQUEST_SCHEME']. '://'.$user->company_name.'.'.\config('app.DOMAIN_NAME').'/dashboard';
           $response['status'] = 1;
           $response['message'] = $link;
        }else{
            $response['status'] = 0;
        }
        return response()->json($response);
   }

   public function ajax_take_to_profile(){
    $user = User::where('id',Auth::user()->id)->first();
    if(isset($user) && !empty($user)){
         $link = $_SERVER['REQUEST_SCHEME']. '://'.$user->company_name.'.'.\config('app.DOMAIN_NAME').'/profile-settings';
         $response['status'] = 1;
         $response['message'] = $link;
     }else{
        $response['status'] = 0;
    }
    return response()->json($response);
    }

}
