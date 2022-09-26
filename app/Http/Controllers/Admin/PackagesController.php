<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PackageFeature;

class PackagesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function __construct() {
        $stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
        $this->stripe = $stripe;
    }

    public function index(Request $request) {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $packages = Package::where('name', 'LIKE', "%$keyword%")
            ->orWhere('description', 'LIKE', "%$keyword%")
            ->orWhere('amount', 'LIKE', "%$keyword%")
            ->orWhere('image_tag', 'LIKE', "%$keyword%")
            ->orWhere('number_of_projects', 'LIKE', "%$keyword%")
            ->orWhere('number_of_keywords', 'LIKE', "%$keyword%")
            ->orWhere('free_trial', 'LIKE', "%$keyword%")
            ->orWhere('duration', 'LIKE', "%$keyword%")
            ->latest()->paginate($perPage);
        } else {
            $packages = Package::latest()->paginate($perPage);
        }

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {


   // $data =  $this->stripe->subscriptions->retrieve(
   //    'sub_HygiWNzKbm8jqI'
   //  );
   // echo "<pre>";
   // print_r($data);
   // die;
        return view('admin.packages.create');
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
        // dd($requestData);
        $validator = Validator::make($requestData, [
            'name' => 'required',
            'description' => 'required',
                   // 'amount' => 'required|numeric',
                   // 'image_tag' => 'required',
            'number_of_projects' => 'required|numeric',
            'number_of_keywords' => 'required|numeric',
            'duration' => 'required_if:free_trial,1',
            'monthly_amount'=>'required|numeric',
            'yearly_amount'=>'required|numeric',
            'site_audit_page'=>'required|numeric',
            "features"    => "required|array",
            "features.*"  => "required|string",
        ],['features.*.required'=>'The feature field is required.']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $package = Package::create([
                'name'=>$requestData['name'],
                'description'=>$requestData['description'],
                'image_tag'=>$requestData['image_tag'],
                'number_of_projects'=>$requestData['number_of_projects'],
                'number_of_keywords'=>$requestData['number_of_keywords'],
                'free_trial'=>$requestData['free_trial'],
                'duration'=>$requestData['duration'],
                'site_audit_page'=>$requestData['site_audit_page'],
                'monthly_amount'=>$requestData['monthly_amount'],
                'yearly_amount'=>$requestData['yearly_amount']

            ]);
            if ($package) {
                foreach($requestData['features'] as $feature){
                 PackageFeature::create([
                    'package_id'=>$package->id,
                    'feature'=>$feature
                ]);  
             }


             $stripe_product = $this->stripe->products->create([
                'name' => $requestData['name'],
                'description' =>$requestData['description']
                   // 'images' => [$requestData['image_tag']]
            ]);
             if ($stripe_product) {
                $stripe_price = $this->stripe->prices->create(
                    [
                        'unit_amount' => $requestData['monthly_amount'] * 100,
                        'currency' => 'usd',
                        'recurring' => ['interval' => 'month'],
                        'product' => $stripe_product->id
                    ]
                );
                $stripe_price_yearly = $this->stripe->prices->create(
                    [
                        'unit_amount' => $requestData['yearly_amount'] * 100,
                        'currency' => 'usd',
                        'recurring' => ['interval' => 'year'],
                        'product' => $stripe_product->id
                    ]
                );

                Package::where('id', $package->id)->update([
                    'stripe_product_id' => $stripe_product->id,
                    'stripe_price_id' => $stripe_price->id,
                    'stripe_price_yearly_id' => $stripe_price_yearly->id

                ]);
            }
        }

        return redirect('admin/packages')->with('flash_message', 'Package added!');
    }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $package = Package::findOrFail($id);

        return view('admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $package = Package::with('package_feature')->where('id',$id)->first();

        return view('admin.packages.edit', compact('package'));
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
            'name' => 'required',
            'description' => 'required',
                   // 'amount' => 'required|numeric',
                   // 'image_tag' => 'required',
            'number_of_projects' => 'required|numeric',
            'number_of_keywords' => 'required|numeric',
            'duration' => 'required_if:free_trial,1',
            'monthly_amount'=>'required|numeric',
            'yearly_amount'=>'required|numeric',
            'site_audit_page'=>'required|numeric',
            "features"    => "required|array",
            "features.*"  => "required|string"
        ], [
            'duration.required_if' => 'The duration field is required.',
            'features.*.required'=>'The feature field is required.'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $package_details = Package::findOrFail($id);
            $product_id = $package_details->stripe_product_id;
            $stripe_monthly_price_id = $package_details->stripe_price_id;
            $stripe_price_yearly_id = $package_details->stripe_price_yearly_id;
            // echo "<pre>";
            // print_r($package_details);
            // print_r($requestData);
            // die;
            $package = Package::where('id',$id)->update([
                'name'=>$requestData['name'],
                'description'=>$requestData['description'],
                'image_tag'=>$requestData['image_tag'],
                'number_of_projects'=>$requestData['number_of_projects'],
                'number_of_keywords'=>$requestData['number_of_keywords'],
                'free_trial'=>$requestData['free_trial'],
                'duration'=>$requestData['duration'],
                'site_audit_page'=>$requestData['site_audit_page'],
                'monthly_amount'=>$requestData['monthly_amount'],
                'yearly_amount'=>$requestData['yearly_amount']

            ]);

            PackageFeature::where('package_id',$id)->delete();
            foreach($requestData['features'] as $feature){
             PackageFeature::create([
                'package_id'=>$id,
                'feature'=>$feature
            ]);  
         }


         /* updating product and price on stripe*/
            $this->stripe->products->update(
              $product_id,
              ['name' => $requestData['name'],'description'=>$requestData['description']]
            );


            // $this->stripe->prices->update(
            //     $stripe_monthly_price_id,
            //     [
            //         'nickname'=>''
            //     // 'unit_amount' => $requestData['monthly_amount'] * 100,
            //     // 'currency' => 'usd',
            //     // 'recurring' => ['interval' => 'month']
            //     ]
            // );


           // $this->stripe->prices->update(
           //    $stripe_price_yearly_id,
           //    []
           // );

         return redirect('admin/packages')->with('flash_message', 'Package updated!');
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
        Package::destroy($id);

        return redirect('admin/packages')->with('flash_message', 'Package deleted!');
    }


    public function ajax_delete_package_feature(Request $request){
        $delete = PackageFeature::where('id',$request['id'])->delete();
        if($delete){
            $res['status'] = 1;
            $res['message'] = 'Feature deleted';
        }else{
            $res['status'] = 0;
            $res['message'] = 'Error !! Deleting feature';
        }
        return response()->json($res);
    }

}
