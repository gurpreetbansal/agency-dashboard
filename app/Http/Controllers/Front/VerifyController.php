<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use DateTime;


class VerifyController extends Controller {

 public function confirmation($token=null){
    if($token == null){
       return redirect('/login')->with('error', 'Invalid Login attempt');
    }

    $user = User::where('email_verification_token',$token)->first();

    if($user){
     
        $start_date = new DateTime(now());
        $since_start = $start_date->diff(new DateTime($user->email_sent_at));
        $minutes = $since_start->h;


        if($minutes > 0){
            return redirect('/login')->with('error', 'Verification link timed out, Login to request new link !');
        }else{
            $user->update([
                'email_verified' => 1,
                'email_verified_at' => now(),
                'email_verification_token' => ''

               ]);
            return redirect('/login')->with('success', 'Your account is activated, you can log in now!');
         }
    }
    
}

}
