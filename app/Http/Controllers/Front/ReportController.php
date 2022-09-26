<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ReportController extends Controller {

    public function index(){
        return view('front.seo-report');
    }

    public function rank_tracker(){
        return view('front.rank-tracker');
    }

}