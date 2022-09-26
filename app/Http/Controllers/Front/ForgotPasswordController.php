<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\User;
use App\PasswordReset;
use Mail;

class ForgotPasswordController extends Controller {

    public function index(){
        return view('front.recover_password.index');
    }

    public function post_recover_password(Request $request){
        $validator = Validator::make($request->all(), ['email' => 'required']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $user = User::where('email', '=', $request->email)->first();

            if (empty($user)) {
                return redirect()->back()->withErrors(['email' => trans('The email you entered does not exists.')]);
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

    function generateRandomString($length = 10) {
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
            return redirect()->back()->withErrors(['error' => 'Error sending reset email']);
        } else {
            return true;
        }
    }

    public function reset_password ($token){
        return view('front.recover_password.reset', ['token' => $token]);
    }


    public function update_recover_password(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ],['email.exists'=>'Email you entered does not match the records.']);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $password = $request->password;
        $tokenData = PasswordReset::where('token', $request->token)->first();
        if (!$tokenData)
            return redirect()->back()->withErrors(['error' => trans('Token expired!')]);

        $user = User::where('email', $tokenData->email)->first();
        if (!$user)
            return redirect()->back()->withErrors(['email' => 'Email you entered does not match the records.']);
        $user->password = \Hash::make($password);
        $user->update(); 

        PasswordReset::where('email', $user->email)->delete();

        if ($user) {
            return redirect()->back()->with('status', trans('Password reset successfully.'));
        } else {
            return redirect()->back()->withErrors(['error' => trans('A Network Error occurred. Please try again.')]);
        }
    }
}