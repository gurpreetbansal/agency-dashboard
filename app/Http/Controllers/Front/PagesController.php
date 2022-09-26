<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PagesController extends Controller {

    public function privacy_policy(){
        return view('front.privacy_policy');
    }

     public function terms_conditions(){
        return view('front.terms_conditions');
    }

}
