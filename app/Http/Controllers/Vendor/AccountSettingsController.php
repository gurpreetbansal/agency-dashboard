<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Subscription;

class AccountSettingsController extends Controller {
		
		public function index(){
			return view('vendor.account_settings.index');
		}


		public function cancel_stripe_subscription(Request $request){
			$cancel_status = $request->status;
			if($cancel_status == true){
				$user_id = Auth::user()->id;
				$subscription = Subscription::where('user_id',$user_id)->where('stripe_status','active')->first();
				if(isset($subscription) && !empty($subscription)){
					$stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
					$stripe_response = $stripe->subscriptions->cancel(
					  $subscription->stripe_id,
					  []
					);
					if($stripe_response){
						Subscription::where('stripe_id',$subscription->stripe_id)->update([
							'stripe_status' => $stripe_response->status,
							'canceled_at'=>date('Y-m-d H:i:s',$stripe_response->canceled_at),
							'cancel_response'=>json_encode($stripe_response)
						]);
					}
				}else{
					$response['status'] = 0;
					$response['message'] = 'No subscription found for user.';
				}

				echo "<pre>";
				print_r($request->all());
				print_r($user_id);
				print_r($subscription);
				die;
		    }

		}
}
