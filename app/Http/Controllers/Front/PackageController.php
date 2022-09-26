<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Package;
use App\UserPackage;
use App\User;
use Session;
use Auth;


class PackageController extends Controller {

    public function index(){
    	$user = $user_package = array();
    	if((Auth::user()!=NULL) && !empty(Auth::user())){
    		$user = User::select('subscription_status','user_type')->where('id',Auth::user()->id)->first();
	    	$user_package = UserPackage::select('package_id')->where('user_id',Auth::user()->id)->latest()->first();
	    }
    	$packages = Package::with(['package_feature'=>function($q){
            $q->where('status',1);
        }])->orderBy('ordering','asc')->where('status',1)->get();
        return view('front.pricing', ['packages' => $packages,'user_package' => $user_package,'user' => $user]);
    }

}
