<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\UserAddress;
use App\Country;
use App\Subscription;
use App\Invoice;
use App\InvoiceItem;
use App\Package;
use App\UserPackage;
use Auth;
use Crypt;
use DB;
use PDF;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Exports\ExportInvoice;
use Maatwebsite\Excel\Facades\Excel;
use Image;
use File;


use App\UserSystemSetting;
use App\SendScheduleReport;
use App\ScheduleReport;
use Mail;
// use App\UserProfile;
use App\SemrushUserAccount;
use App\CancelFeedback;

use App\Exports\ExportManualInvoice;
use App\InvoiceChange;

use Session;

class ProfileController extends Controller {

	public function profile_settings($id = null, $uid = null){
		$invoices = array(); $currency = '$';
		if (!empty($uid)) {
			$user = User::where('id', Crypt::decrypt($uid))->first();
			Auth::login($user);
		}
		$countries = Country::get();
		$user = User::with('UserAddress')->where('id', Auth::user()->id)->first();
		$user_id = Auth::user()->id;
		$user_package = UserPackage::with('package')->where('user_id',$user_id)->latest()->first();

		$purchase_mode = $user->purchase_mode;

		$package_info = Subscription::where('user_id',Auth::user()->id)->latest()->first();

		if($purchase_mode == 1){
			$invoices = $this->get_billing_invoices($user_id);	
			$currency = '$';
		}elseif($purchase_mode == 2){
			$invoices = $this->get_manual_billing_invoices($user->email);
			$currency = '₹';
		}

		$country_id = $user->UserAddress->country;
		$system_setting = UserSystemSetting::where('user_id',$user_id)->first();
		//$packages = Package::where('status',1)->get();

		$packages = Package::orderBy('ordering','asc')->where('status',1)->get();

		return view('vendor.profile_settings.index',compact('user', 'countries','package_info','invoices','user_id','user_package','system_setting','purchase_mode','packages','currency','country_id'));
	}

	public function check_company_name(Request $request){
		$check = User::where('company_name', $request['company_name'])->where('role_id', 2)->first();
		if (!empty($check)) {
			return 'taken';
		} else {
			return 'not_taken';
		}
	}

	public function updateprofilesettings(Request $request){
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'phone' => 'required|digits:10',
			'address_line_1' => 'required',
			'city' => 'required',
			'profile_image' => 'image|mimes:jpg,jpeg,png|max:2048',
			//'country' => 'required',
			'zip' => 'required',
			'company_name' => 'required_if:role_id,2'
		]);
		if ($validator->fails()) {
			$array = array();
			foreach($validator->messages()->getMessages() as $field_name => $messages) {
				foreach($messages as $message) {
					$array[$field_name] = $message;
				}
			}         
			$response['status'] = 3;
			$response['message'] = $array;
		} else {
			$get_user_data = User::where('id',$request['user_id'])->select('company_name')->first();
			if($get_user_data->company_name != $request['company_name']){
				$check = User::where('company_name', $request['company_name'])->where('role_id', 2)->first();
				if(!empty($check)){
					$response['status'] = 0;
					$response['message'] = 'Company Name already taken';
					return response()->json($response);
				}
			}

			if($request->hasFile('profile_image')) {
				$profile_image_name = User::resizeImage($request->file('profile_image'),'profile_images',$request['name']);
				User::where('id', $request['user_id'])->update([
					'name' => $request['name'],
					'phone' => $request['phone'],
					'profile_image' => $profile_image_name,
					'company_name'=>$request['company_name']
				]);
			}

			User::where('id', $request['user_id'])->update([
				'name' => $request['name'],
				'phone' => $request['phone'],
				'company_name'=>$request['company_name']
			]);

			$address = UserAddress::updateOrCreate(
				['user_id' => $request['user_id']], [
					'address_line_1' => $request['address_line_1'],
					'address_line_2' => $request['address_line_2'],
					'city' => $request['city'],
					//'country' => $request['country'],
					'zip' => $request['zip']
				]
			);
			if ($address) {

				if($get_user_data->company_name != $request['company_name']){
					$response['company_status'] = 1;
					$this->send_profile_email($request['user_id']);
				}else{
					$response['company_status'] = 0;
				}
				$user_data = User::where('id',$request['user_id'])->select('company_name')->first();
				$response['status'] = 1;
				$response['message'] = 'Profile information updated successfully!';
				$response['link'] = 'https://' . $user_data->company_name . '.' . \config('app.APP_DOMAIN').'dashboard/'.Crypt::encrypt(Auth::user()->id);
				
			} else {
				$response['status'] =2;
				$response['message'] = 'Error!! Updating profile information.';
			}

		}
		return response()->json($response);
	}

	private function send_profile_email($user_id){
		$get_user = User::select('name','email','company_name')->where('id',$user_id)->first();
		$get_managers = User::select('name','email')->where('parent_id',$user_id)->where('role_id',3)->get();
		$link = 'https://' . $get_user->company_name . '.' . \config('app.APP_DOMAIN');

		if($get_user){
			\Mail::send('mails/vendor/profile_updated', ['email'=>$get_user->email,'name'=>$get_user->name,'link'=>$link], function($message) use($get_user){
				$message->to($get_user->email,  $get_user->name)->subject
				('Access/Vanity URL updated - Agency Dashboard');
				$message->from(\config('app.mail'), 'Agency Dashboard');
			});
		}
		if(!empty($get_managers)){
			foreach ($get_managers as $key => $value) {
				$input['email'] = $value->email;
				$input['name'] = $value->name;
				\Mail::send('mails/vendor/profile_updated', ['email'=>$value->email,'name'=>$value->name,'link'=>$link], function($message) use($input){
					$message->to($input['email'],  $input['name'])->subject
					('Company Name has been changed - Agency Dashboard');
					$message->from(\config('app.mail'), 'Agency Dashboard');
				});
			}
		}
	}

	public function update_change_password(Request $request){
		$validator = Validator::make($request->all(), [
			'new_password' => 'min:6|max:15',
			'confirm_password' => 'same:new_password',
		]);
		$validator->after(function ($validator)use($request) {
			if (!Hash::check($request->current_password, auth()->user()->password)) {
				$validator->errors()->add('current_password', 'Your current password is incorrect.');
			}
		});
		if ($validator->fails()) {
			$array = array();
			foreach($validator->messages()->getMessages() as $field_name => $messages) {
				foreach($messages as $message) {
					$array[$field_name] = $message;
				}
			}         
			$response['status'] = 0;
			$response['message'] = $array;
			
		} else {
			User::find(Auth::user()->id)->update(['password' => Hash::make($request['new_password'])]);
			$response['status'] =1;
			$response['message'] = 'Password Updated successfully!';
		}

		return response()->json($response);

	}


	private function get_billing_invoices($user_id){
		$data = array();
		$subscription = Subscription::with('invoices')->where('user_id',$user_id)->orderBy('id','desc')->get();
		if(isset($subscription) && !empty($subscription)){

			foreach($subscription as $key => $value){
				$stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
				try{
					$data[] = $stripe->invoices->all(['subscription'=>$value->stripe_id,'status'=>'paid']);	
				}catch(\Exception $e){

				}
			}
			return $data;
		}
	}


	public function download_invoice($domain,$invoice_id){
		$response = $this->get_invoice_response($invoice_id);
		if(isset($response['id'])){
			$pdf = PDF::loadView('vendor.pdf.invoice', $response);
			return $pdf->download($response['number'].'.pdf');
		}else{
			return 'error downloading pdf';
		}
	}


	private function get_invoice_response($invoice_id){
		try{
			$stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
			$response = $stripe->invoices->retrieve($invoice_id);
			return $response->toArray();
		}catch(\Exception $e){
			return $e->getMessage();
		}
	}

	public function download_excel($domain,$user_id){
		$response = array();
		$subscription = Subscription::select('customer_id')->where('user_id',$user_id)->orderBy('id','asc')->first();
		$stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
		try{
			$response = $stripe->invoices->all(['customer'=>$subscription->customer_id,'status'=>'paid'])->toArray();
			ob_end_clean(); 
			ob_start(); 
			return Excel::download(new ExportInvoice($response),'Invoice.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
				'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
			]);
		}catch(\Exception $e){
			return abort(404);
		}
	}

	public function cancel_subscription(Request $request){
		$final_response = array();
		$user_id = $request->user_id;
		$validator = Validator::make($request->all(), [
			'overall_rating' => 'required',
			'recommend' => 'required',
			'description' => 'required'
		]);


		if($validator->fails()){
			$final_response['status'] = 0;
			$final_response['message'] = 'All fields in the form are required.';
		}else{
			$user_data = User::where('id',$user_id)->first();
			if($user_data->purchase_mode == 1){
				$subscription = Subscription::select('customer_id','stripe_id')->where('user_id',$user_id)->latest()->first();	

				try{
					$stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
					$response = $stripe->subscriptions->update(
						$subscription->stripe_id,
						[
							'cancel_at_period_end' => true
						]
					);

					Subscription::where('stripe_id',$subscription->stripe_id)->where('user_id',$user_id)->update([
						'canceled_at'=>date('Y-m-d H:i:s',$response->canceled_at),
						'ends_at'=>date('Y-m-d H:i:s',$response->current_period_end),
						'cancel_response'=>json_encode($response,true),
						'next_invoice_on' => NULL
					]);

					User::where('id',$user_id)->update([
						'subscription_status'=>0,
						'subscription_ends_at'=>date('Y-m-d H:i:s',$response->current_period_end)
					]);

					$cancel_form = CancelFeedback::create([
						'user_id'=>$user_id,
						'subscription_id'=>$subscription->stripe_id,
						'sub_id'=>$subscription->id,
						'overall_rating'=>$request->overall_rating,
						'recommend'=>$request->recommend,
						'description'=>$request->description
					]);

					$this->subscription_cancel_email($user_id,$subscription->stripe_id,$cancel_form->id);
					$final_response['status'] = 1;
					$final_response['message'] = 'Subscription cancelled successfully!';
				}catch(\Exception $e){
					$final_response['status'] = 0;
					$final_response['message'] = $e->getMessage();
				}
			}else if($user_data->purchase_mode == 2){ /*for manual invoicing*/
				try{

					$subs_data = Subscription::where('user_id',$user_id)->latest()->first();

					$end_date = $subs_data->current_period_end;


					Subscription::where('id',$subs_data->id)->update([
						'canceled_at' => date('Y-m-d H:i:s',strtotime(now())),
						'ends_at' => date('Y-m-d H:i:s',strtotime($end_date)),
						'next_invoice_on' => NULL,
						'invoice_link_expiration' => NULL,
						'reminder_on' => NULL,
						'stripe_status' => 'canceled'
					]);

					User::where('id',$user_id)->update([
						'subscription_status' => 0,
						'subscription_ends_at' => date('Y-m-d H:i:s',strtotime($end_date))
					]);

					$cancel_form = CancelFeedback::create([
						'user_id'=>$user_id,
						'subscription_id'=>NULL,
						'sub_id'=>$subs_data->id,
						'overall_rating'=>$request->overall_rating,
						'recommend'=>$request->recommend,
						'description'=>$request->description
					]);

					$this->subscription_cancel_email($user_id,'manual',$cancel_form->id);
					$final_response['status'] = 1;
					$final_response['message'] = 'Subscription cancelled successfully!';
				}catch(\Exception $e){
					$final_response['status'] = 0;
					$final_response['message'] = $e->getMessage();
				}
			}else{
				try{
					User::where('id',$user_id)->update([
						'subscription_status' => 0,
						'subscription_ends_at' => date('Y-m-d H:i:s',strtotime(now()))
					]);

					$cancel_form = CancelFeedback::create([
						'user_id'=>$user_id,
						'subscription_id'=>NULL,
						'sub_id'=>NULL,
						'overall_rating'=>$request->overall_rating,
						'recommend'=>$request->recommend,
						'description'=>$request->description
					]);

					$this->subscription_cancel_email($user_id,'free',$cancel_form->id);
					$final_response['status'] = 1;
					$final_response['message'] = 'Subscription cancelled successfully!';
				}catch(\Exception $e){
					$final_response['status'] = 0;
					$final_response['message'] = $e->getMessage();
				}
			}

		}
		return $final_response;
	}

	private function subscription_cancel_email($user_id,$subscription_id,$cancel_form_id){
		try{
			$user = User::with('UserAddress')->where('id', $user_id)->first();

			if($user->UserAddress->country == 99){
				$currency = '₹';
			}else{
				$currency = '$';
			}

			$user_package = UserPackage::with('package')->where('user_id',$user_id)->latest()->first();
			$data = array('name' => $user->name, 'package_name' => $user_package->package->name,'package_price'=>$user_package->price,'package_type'=>$user_package->subscription_type,'currency'=>$currency);

			\Mail::send(['html' => 'mails/vendor/subscription_cancellation'], $data, function($message) use($user) {
				$message->to($user->email, $user->name)
				->subject('Your membership at Agency Dashboard has been CANCELLED');
				$message->from(\config('app.mail'), 'Agency Dashboard');
			});


			//admin
			$admin = User::with('UserAddress')->where('role_id', 1)->first();
			$feedback_data = CancelFeedback::where('id',$cancel_form_id)->first();
			$admin_data = array('name' => $user->name, 'package_name' => $user_package->package->name,'package_price'=>$user_package->price,'package_type'=>$user_package->subscription_type,'subscription_id'=>$subscription_id,'overall_rating'=>$feedback_data->overall_rating,'recommend'=>$feedback_data->recommend,'description'=>$feedback_data->description,'currency'=>$currency);

			\Mail::send(['html' => 'mails/vendor/admin_subscription_cancellation'], $admin_data, function($admin_message) use($admin) {
				$admin_message->to($admin->email, $admin->name)
				->subject('Subscription Cancelled');
				$admin_message->from(\config('app.mail'), 'Agency Dashboard');
			});
		}catch(Exception $e){
			return $e->getMessage();
		}
	}

	public function ajax_remove_profile_picture(Request $request){
		$response = array();
		$image = explode('/',$request->profile_image);
		$profile_image = end($image);
		$products = User::where('id',$request->user_id)->update([
			'profile_image' => null,
			'initial_background' => User::get_random_color()
		]);
		if($products){
			$fullImgPath = storage_path('app/public/profile_images/'.$profile_image);
			if(File::exists($fullImgPath)) {
				File::delete($fullImgPath);
			}
			$response['status'] = 1;
			$response['message'] = 'Profile picture removed successfully.';
		}else{
			$response['status'] = 0;
			$response['message'] = 'Error removing profile picture.';
		}

		return response()->json($response);
	}


	public function ajax_update_stripe_card_details(Request $request){
		$payment_ids = $res = array();
		$user_data = User::where('id',$request->user_id)->select('id','stripe_id')->first();
		if(isset($user_data) && !empty($user_data) && ($user_data->stripe_id <> null)){
			$customer_id = $user_data->stripe_id;
			$stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
			try{
				$payment_methods = $stripe->paymentMethods->all([
					'customer' => $customer_id,
					'type' => 'card'
				]);

				if($payment_methods['data'] <> null){
					$payment_count = count($payment_methods['data']);
					for($i=0;$i<$payment_count;$i++){
						$payment_ids[] = $payment_methods['data'][$i]['id'];

						/*detach card for customer*/
						$stripe->paymentMethods->detach(
							$payment_methods['data'][$i]['id']
						);
					}
				}
				/*create payment method*/
				$paymentMethod = $stripe->paymentMethods->create([
					'type' => 'card',
					'card' => ['token' => $request->stripeToken]
				]);


				/*attach card for customer*/
				$customer_payment_method = $stripe->paymentMethods->attach(
					$paymentMethod->id, ['customer' => $customer_id]
				);

				$stripe->customers->update($customer_id,[
					'invoice_settings' => [
						'default_payment_method' => $paymentMethod->id
					]
				]);


				User::where('id',$user_data->id)->update([
					'card_brand' => $customer_payment_method->card->brand,
					'card_last_four' => $customer_payment_method->card->last4,
					'card_exp_month' => $customer_payment_method->card->exp_month,
					'card_exp_year' => $customer_payment_method->card->exp_year
				]);

				$subscription = Subscription::where('user_id',$user_data->id)->where('customer_id',$customer_id)->update([
					'payment_id' => $customer_payment_method->id
				]);

				if($subscription){
					$response['status'] = 'success';
					$response['message'] = 'Card details updated';
				}else{
					$response['status'] = 'error';
					$response['message'] = 'Error updating card';
				}
			}catch(Exception $e){
				// echo "<pre>";
				// print_r($e->getMessage());
				// die;
				$response['status'] = 'error';
				$response['message'] = 'Error updating card';
			}			
		}else{
			$response['status'] = 'error';
			$response['message'] = 'User details not found';
		}
		return response()->json($response);
	}


	public  function update_user_system_preference(Request $request)
	{
		$response =  array();
		$data = UserSystemSetting::updateOrCreate(
			['user_id' => $request->user_id],
			['user_id' => $request->user_id, 'email_deliver_from' => $request->email_delivery, 'email_reply_to' => $request->email_reply_to]
		);
		if($data){
			$response['status'] = 1;
			$response['message'] ='System preference updated successfully.';
		}else{
			$response['status'] = 0;
			$response['message'] ='Error updating system preference.';
		}

		return response()->json($response);
	}


	public function ajax_check_current_password(Request $request){
		$response = array();
		if (!Hash::check($request->current_password, auth()->user()->password)) {
			$response['status'] = 'error';
			$response['message'] = 'current password is incorrect.';
		}else{
			$response['status'] = 'success';
		}

		return response()->json($response);
	}


	public function ajax_match_confirm_password(Request $request){
		$response = array();
		$validator = Validator::make($request->all(), [
			'confirm_password' => 'same:new_password'
		]);
		
		if ($validator->fails()) {
			$array = array();
			foreach($validator->messages()->getMessages() as $field_name => $messages) {
				foreach($messages as $message) {
					$array[$field_name] = $message;
				}
			}         
			$response['status'] = 'error';
			$response['message'] = $array;
			
		} else {
			$response['status'] = 'success';
		}

		return response()->json($response);
	}

	private function get_manual_billing_invoices($billing_email){
		$data = array();
		$subscription = Invoice::with('invoices_item')->where('billing_email',$billing_email)->orderBy('id','desc')->where('invoice_status','paid')->get();
		return $subscription;
	}

	public function stripe_download_excel($domain,$email){
		$response = array();
		try{
			$response = Invoice::with('invoices_item','subscription')->where('billing_email',$email)->orderBy('id','asc')->where('invoice_status','paid')->get();
			ob_end_clean(); 
			ob_start(); 
			return Excel::download(new ExportManualInvoice($response),'Invoice.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
				'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
			]);
		}catch(\Exception $e){
			return abort(404);
		}
	}

	public function stripe_download_invoice($domain,$invoice_id){
		try{
			$invoice = Invoice::with('invoices_item','subscription')->where('id',$invoice_id)->first();
			$user = User::with('UserAddress')->where('id',$invoice->subscription->user_id)->first();
			$pdf = PDF::loadView('vendor.pdf.manual_invoice', compact('user','invoice'));
			return $pdf->download($invoice->invoice_number.'.pdf');
		}catch(\Exception $e){
			return $e->getMessage();
		}
	}

	public function ajax_check_invoice_status(Request $request){
		$response['status'] = 'not paid';
		$invoice_id = $request->invoice_id;
		$stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
		$invoice_response = $stripe->invoices->retrieve($invoice_id);
		if($invoice_response <> null || !empty($invoice_response)){
			if($invoice_response->status == 'paid'){
				InvoiceChange::where('invoice_id',$invoice_id)->delete();
				$response['status'] = 'paid';
			}

			if($invoice_response->status == 'void'){
				$response['status'] = 'void';
			}
		}
		return response()->json($response);
	}

}