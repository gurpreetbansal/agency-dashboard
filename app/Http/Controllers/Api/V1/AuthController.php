<?php

namespace App\Http\Controllers\Api\v1;


use App\Http\Controllers\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException; 
use Tymon\JWTAuth\JWTAuth; 

use Tymon\JWTAuth\Exceptions\JWTException; 
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException; 

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Auth;
use App\Traits\ApiStatus;
use App\User;
use App\PasswordReset;
use Carbon\Carbon;
use Mail;

class AuthController extends Controller
{

    use ApiStatus;
    protected $guard = 'api';
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
       // $this->middleware('jwt.auth', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        
        if (! $token = Auth::guard($this->guard)->attempt($credentials)) { 

            $message = "Invalid email/password!";
            return $this->fail($message);
        }


        $tokenData = $this->respondWithToken($token);
        $data = $this->getProfile(auth($this->guard)->user()->id);
        $message = "Login in Successfully";
        
        /*file_put_contents(dirname(__FILE__).'/logss/loginToken.txt',print_r($tokenData,true));
        file_put_contents(dirname(__FILE__).'/logss/loginData.txt',print_r($data,true));*/

        return $this->success($message,$data,$tokenData);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = $this->getProfile(auth($this->guard)->user()->id);
        
        if($user){
            $message = "Login User profile";
            return $this->success($message,$user);
        }else{
            $message = "User does not exist";
            return $this->fail($message);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth($this->guard)->logout();
        $message = "You are loged out successfully.";
        return $this->success($message);
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function refresh(Request $request)
    {   
        
        $user = array();
         if(auth($this->guard)->user() <> null){
            $user = $this->getProfile(auth($this->guard)->user()->id);
            $tokenUpdate = $this->respondWithToken(auth($this->guard)->refresh());
        }else{
            $tokenUpdate = $this->respondWithToken(auth($this->guard)->refresh());
            $user = $this->getProfile(auth($this->guard)->user()->id);
        }
        
        
        $message = "Token has been updated";
        return $this->success($message,$user,$tokenUpdate);
        
    }

    // public function refresh(Request $request)
    // {   
    //     dd(auth($this->guard)->user());
    //     $tokenUpdate = $this->respondWithToken(Auth::guard($this->guard)->refresh());
    //     if(auth($this->guard)->user() <> null){
    //         $user = $this->getProfile(auth($this->guard)->user()->id);
    //     }else{
            
    //         /*JWTAuth::setToken($tokenUpdate)->toUser();*/
    //         $user = $this->getProfile(auth($this->guard)->user()->id);
    //     }
    //     $message = "Token has been updated";
    //    /* file_put_contents(dirname(__FILE__).'/logss/lastToken.txt',print_r(auth($this->guard)->refresh(),true));*/
    //     /*file_put_contents(dirname(__FILE__).'/logss/checkLoginData.txt',print_r($user,true));
    //     file_put_contents(dirname(__FILE__).'/logss/checkLoginToken.txt',print_r($tokenUpdate,true));*/
    //     return $this->success($message,$user,$tokenUpdate);
        
    // }

    /**
     * Update Profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function changePassword(Request $request)
    {
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
            $message = $validator->messages()->first();
            return $this->fail($message);
        }

        $user = User::find(auth()->user()->id)->update(['password' => Hash::make($request->password)]);
        if($user){
            $user = $this->getProfile(auth($this->guard)->user()->id);
            return $this->success('Password Updated successfully!',$user);
        }else{
            return $this->fail('Something went wrong! Please try again.');
        }
        
    }


     /**
     * Update Profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function updateProfile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'profile_image' => 'image|mimes:jpg,png,jpeg'
            //'company_name' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return $this->fail($message);
        }
        

        $user_id = auth($this->guard)->user()->id;
        $get_user_data = User::where('id',$user_id)->select('company_name')->first();

        $filePath = '';
        if ($request->has('profile_image')) {
            
            $image = $request->file('profile_image');
            $name = \Str::slug($request['name']) . '_' . time();
            $folder = '/profile_images/';
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            User::uploadOne($image, $folder, 'public', $name);
           
        }

        /*if($get_user_data->company_name != $request['company_name']){
            $check = User::where('company_name', $request['company_name'])->where('role_id', 2)->first();
            if(!empty($check)){
                $message = 'Company Name already taken';
                return $this->fail($message);
            }
        }*/

        $userUpdate = User::where('id', $user_id)->update([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'profile_image' => $filePath
            // 'company_name'=>$request['company_name']
        ]);

        if($userUpdate){
            $user = $this->getProfile(auth($this->guard)->user()->id);
            return $this->success('Profile has been updated successfully!',$user);
        }else{
            return $this->fail('Something went wrong! Please try again.');
        }
    }


    /**
     * Get global user detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    protected function getProfile($id){

        $user = User::select('id','name','email','role','role_id','phone','company_name','profile_image','status','email_verified','login_as','last_login','is_admin')->where('id',$id)->first()->toArray();
        
        return $user;
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($this->guard)->factory()->getTTL()
        ];
    }

     /**
     * Forgot password link.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function forgotPassword (Request $request){
        $validator = Validator::make($request->all(), ['email' => 'required|email|exists:users']);
        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return $this->fail($message);
        }

        PasswordReset::create([
            'email' => $request->email,
            'token' => $this->generateRandomString(60),
            'created_at' => Carbon::now()
        ]);
        $tokenData = PasswordReset::where('email', $request->email)->first();

        if ($this->sendResetEmail($request->email, $tokenData->token)) {
            return $this->success('A reset link has been sent to your email address.');
        }else{
            return $this->fail('A Network Error occurred. Please try again.');
        }
    }

    protected function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function sendResetEmail($email, $token) {
        $user = User::where('email', $email)->select('name', 'email')->first();
        $link = \config('app.base_url') . 'reset-password/' . $token;

        $data = array('name' => $user->name, 'email' => $user->email, 'link' => $link);
        Mail::send(['html' => 'mails/front/recover_password'], $data, function($message) use($user) {
            $message->to($user->email)->subject
            ('Recover your password - Agency Dashboard');
            $message->from(\config('app.mail'), 'Agency Dashboard');
        });
        if (Mail::failures()) {
            return $this->fail('Error sending reset email');
        } else {
            return true;
        }
    }

}
