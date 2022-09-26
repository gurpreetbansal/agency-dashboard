<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Package;
use Auth;

class PackageController extends Controller {

    public function index(Request $request) {
//        echo '<pre>';
//        print_r(Auth::user());
//        die;
        $packages = Package::get();
        return view('pricing', ['packages' => $packages]);
    }

}
