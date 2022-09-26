<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Country;
use App\User;
use App\UserAddress;

class UserController extends Controller {

    public function profile() {
        $countries = Country::get();
        $user = User::with('UserAddress')->where('id', Auth::user()->id)->first();
        return view('vendor.profile', compact('user', 'countries'));
    }

    public function updateprofile(Request $request) {

        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'phone' => 'required',
                    'address_line_1' => 'required',
                    'city' => 'required',
                    'profile_image' => 'image|mimes:jpg,jpeg,png',
                    'country' => 'required',
                    'zip' => 'required'
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            if ($request->has('profile_image')) {
                $image = $request->file('profile_image');
                $name = \Str::slug($request->name) . '_' . time();
                $folder = '/profile_images/';
                $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                User::uploadOne($image, $folder, 'public', $name);

                User::where('id', Auth::user()->id)->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'profile_image' => $filePath
                ]);
            }
            User::where('id', Auth::user()->id)->update([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);


            $address = UserAddress::updateOrCreate(
                            ['user_id' => Auth::user()->id], [
                        'address_line_1' => $request->address_line_1,
                        'address_line_2' => $request->address_line_2,
                        'city' => $request->city,
                        'country' => $request->country,
                        'zip' => $request->zip
                            ]
            );
            if ($address) {
                return back()->with('success', 'Profile Updated successfully!');
            } else {
                return back()->with('error', 'Error Updating profile');
            }
        }
    }

}
