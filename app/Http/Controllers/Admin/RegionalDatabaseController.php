<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\RegionalDatabse;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Image;

class RegionalDatabaseController extends Controller {
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
 
    public function index(Request $request) {
        $keyword = $request->get('search');
        $perPage = 10;

        if (!empty($keyword)) {
            $databases = RegionalDatabse::where('long_name', 'LIKE', "%$keyword%")
                            ->orWhere('short_name', 'LIKE', "%$keyword%")
                            ->latest()->paginate($perPage);
        } else {
            $databases = RegionalDatabse::latest()->paginate($perPage);
        }

        return view('admin.regional-database.index', compact('databases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.regional-database.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {
       
        $requestData = $request->all();
       
        $validator = Validator::make($requestData, [
                    'short_name' => 'required',
                    'long_name' => 'required',
                    'flag' => 'required|image|mimes:jpg,jpeg,png'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            if ($request->has('flag')) {
                $image = $request->file('flag');
                $filePath = $requestData['short_name'] . '.' . $image->getClientOriginalExtension();

                Image::make(storage_path('app/public/database_flags/' . $filePath))
                        ->resize(16, 16)
                        ->save(storage_path('app/public/database_flags/' . $filePath));


            }

            $package = RegionalDatabse::create([
                'short_name'=>$requestData['short_name'],
                'long_name'=>$requestData['long_name'],
                'flag'=>$filePath
            ]);
           

            return redirect('admin/regional-database')->with('flash_message', 'Added!');
        }
    }

   
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $database = RegionalDatabse::findOrFail($id);

        return view('admin.regional-database.edit', compact('database'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id) {

        $requestData = $request->all();
         $validator = Validator::make($requestData, [
                    'short_name' => 'required',
                    'long_name' => 'required',
                    'flag' => 'image|mimes:jpg,jpeg,png'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $database = RegionalDatabse::findOrFail($id);

              if ($request->has('flag')) {
                $image = $request->file('flag');
                $filePath = $requestData['short_name'] . '.' . $image->getClientOriginalExtension();


                         $destinationPath = storage_path('/app/public/database_flags/');
                    $img = Image::make($image->path());
                    $img->resize(16, 16, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$filePath);
            }else{
                $filePath = $database->flag;
            }

            $package = RegionalDatabse::where('id',$id)->update([
                'short_name'=>$requestData['short_name'],
                'long_name'=>$requestData['long_name'],
                'flag'=>$filePath
            ]);

            return redirect('admin/regional-database')->with('flash_message', 'updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        RegionalDatabse::destroy($id);

        return redirect('admin/regional-database')->with('flash_message', 'Deleted!');
    }

}