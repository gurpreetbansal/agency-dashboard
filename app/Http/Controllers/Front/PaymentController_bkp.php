<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use Stripe;
use Auth;
use Crypt;
use Mail;
use App\User;
use App\Package;
use App\UserPackage;
use App\UserAddress;
use App\Subscription;
use App\SubscriptionItem;
use App\Country;
use App\Invoice;
use App\InvoiceItem;
use App\Coupon;
use App\UserCredit;

class PaymentController_bkp extends Controller {

    public function stripePost(Request $request) {
        $input = $request->all();
        $decode_data = base64_decode($input['data-key']);
        if($input['existing_user'] !='' && !empty($input['existing_user'])){
            $explode = explode('+',$decode_data);

            $email = $explode[0];
            $company = $explode[1];
            $vanity_url = $explode[2];
            $package_id = $explode[3];
            $package_state = $explode[4];
            $user_id = $explode[5];

            $user_detail = User::where('id',$user_id)->first();
            $customer_id = $user_detail->stripe_id;
            $subscription = Subscription::where('user_id',$user_id)->where('customer_id',$customer_id)->latest()->first();
            if($customer_id <> null){
                $subscription = Subscription::where('user_id',$user_id)->where('customer_id',$customer_id)->latest()->first();
                $subscription_id = $subscription->stripe_id;
            }
            
            $country = Country::where('id', $input['country'])->first();

            $string = $input['plan'];
            $field = ['stripe_price_id','stripe_price_yearly_id'];
            $package_info = Package::
            where(function ($query) use($string, $field) {
                for ($i = 0; $i < count($field); $i++){
                    $query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
                }      
            })
            ->first();
        
            if($input['plan'] == $package_info->stripe_price_id){
                $amount  = $package_info->monthly_amount;
            } else  if($input['plan'] == $package_info->stripe_price_yearly_id){
                $amount  = $package_info->yearly_amount*12;
            }

            $token = $request->stripeToken;

            try {
                $stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
                //canceling previous subscription
                 if($customer_id <> null){
                if(($user_detail->subscription_status == 1) && ($subscription->stripe_status == 'active' || $subscription->stripe_status == 'trialing')){

                   $response = $stripe->subscriptions->cancel(
                      $subscription_id,
                      [
                        'invoice_now'=>true,
                        'prorate'=>true
                      ]
                    );


                    Subscription::where('stripe_id',$subscription_id)->where('user_id',$user_id)->update([
                        'canceled_at'=>date('Y-m-d H:i:s',$response->canceled_at),
                        'ends_at'=>date('Y-m-d H:i:s',$response->current_period_end),
                        'stripe_status'=>$response->status,
                        'cancel_response'=>json_encode($response,true)
                    ]);

                    User::where('id',$user_detail->id)->update([
                        'subscription_ends_at'=>date('Y-m-d H:i:s',$response->current_period_end),
                        'subscription_status'=>1
                    ]);

                    $this->send_cancelled_email($user_detail);
                 }
             }else{
                    $customer = $stripe->customers->create([
                        'email' => $email,
                        'name' => $input['billing_name'],
                        'shipping' => [
                            'address' => [
                                'line1' => $input['address_line_1'],
                                'line2' => $input['address_line_2'],
                                'city' => $input['city'],
                                'country' => $country->countries_name,
                                'postal_code' => $input['postal_code'],
                            ],
                            'name' => $input['billing_name'],
                        ],
                        'address' => [
                            'line1' => $input['address_line_1'],
                            'line2' => $input['address_line_2'],
                            'city' => $input['city'],
                            'country' => $country->countries_name,
                            'postal_code' => $input['postal_code'],
                        ],
                    ]);
                    $customer_id = $customer->id;
                }

                //update with new subscription

               if (!empty($customer_id)) {
                        $paymentMethod = $stripe->paymentMethods->create([
                            'type' => 'card',
                            'card' => ['token' => $token]
                        ]);

                        $customer_payment_method = $stripe->paymentMethods->attach(
                            $paymentMethod->id, ['customer' => $customer_id]
                        );

                        $paymentIntents =   $stripe->paymentIntents->create([
                          'amount' => $amount*100,
                          'currency' => 'usd',
                          'payment_method_types' => ['card'],
                          'customer'=>$customer_id
                      ]);


                        $subscription = $stripe->subscriptions->create([
                            'customer' => $customer_id,
                            'items' => [
                                ['price' => $input['plan']],
                            ],
                            'default_payment_method' => $customer_payment_method
                        ]);



                        $charge = $stripe->charges->all(['limit' => 1,'customer'=>$customer_id]);

                        if(isset($charge) && !empty($charge->data)){
                            $charge_id = $charge->data[0]->id;
                            $payment_intent = $charge->data[0]->payment_intent;
                        }else{
                            $charge_id = $payment_intent = '';
                        }


                        $user = User::where('id',$user_id)->update([
                            'name' => ucwords($input['billing_name']),
                            'card_brand'=>$customer_payment_method->card->brand,
                            'card_last_four'=>$customer_payment_method->card->last4,
                            'card_exp_month'=>$customer_payment_method->card->exp_month,
                            'card_exp_year'=>$customer_payment_method->card->exp_year,
                            'subscription_status'=>1,
                            'purchase_mode'=>1,
                            'user_type'=>0
                        ]);

                        if ($user) {
                            $package = Package::where('id', $package_id)->first();
                            if($package_state == 'month'){
                                $price = $package->monthly_amount;
                            }elseif($package_state == 'year'){
                                $price = $package->yearly_amount*12;
                            }

                            UserPackage::create([
                                'user_id' => $user_id,
                                'package_id' => $package_id,
                                'projects' => $package->number_of_projects,
                                'keywords' => $package->number_of_keywords,
                                'flag' => '1',
                                'trial_days' => 0,
                                'price'=>$price,
                                'subscription_type'=>$package_state,
                                'package_purchase' => 1
                            ]);

                             $existingCredits = UserCredit::where('user_id', $user_id)->latest()->first();
                            if(!empty($existingCredits)){
                                UserCredit::create([
                                    'user_id'=>$user_id,
                                    'used_credit'=>$existingCredits->used_credit,
                                    'additional_credit'=>$existingCredits->additional_credit
                                ]);
                            }

                            UserAddress::where('user_id',$user_id)->update([
                                'address_line_1' => $input['address_line_1'],
                                'address_line_2' => $input['address_line_2'],
                                'city' => $input['city'],
                                'country' => $input['country'],
                                'zip' => $input['postal_code']
                            ]);



                            $save_subscription = Subscription::create([
                                'user_id' => $user_id,
                                'charge_id'=>$charge_id,
                                'payment_intent_id'=>$payment_intent,
                                'payment_id'=>$customer_payment_method->id,
                                'customer_id'=>$customer_id,
                                'stripe_id' => $subscription->id,
                                'coupon_id'=>0
                            ]);

                            return redirect('/thankyou');
                        }

                    }

         }catch (Exception $e) {
            return back()->with('success', $e->getMessage());
        }

    }else{
        $explode = explode('+',$decode_data);
        $coupon = '';
        $coupon_id = 0;

        $email = $explode[0];
        $password = $explode[1];
        $company = $explode[2];
        $vanity_url = $explode[3];
        $package_id = $explode[4];
        $package_state = $explode[5];

        if($explode[6]){
            $coupon_data = Coupon::where('code',$explode[6])->first();
            $coupon = $coupon_data->coupon_code_id;
            $coupon_id = $coupon_data->id;
        }

        $exists = User::where('email',$email)->orwhere('company',$company)->orwhere('company_name',$vanity_url)->first();
        if(!empty($exists)){
            return back()->with('error', '(Email, Company Name, Vanity url) One of the fields have been already taken.');
        }else{
            $country = Country::where('id', $input['country'])->first();
            $string = $input['plan'];
            $field = ['stripe_price_id','stripe_price_yearly_id'];
            $trial_days = Package::
            where(function ($query) use($string, $field) {
                for ($i = 0; $i < count($field); $i++){
                    $query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
                }      
            })
            ->first();

            if($input['plan'] == $trial_days->stripe_price_id){
                $amount  = $trial_days->monthly_amount;
            } else  if($input['plan'] == $trial_days->stripe_price_yearly_id){
                $amount  = $trial_days->yearly_amount*12;
            }


            $token = $request->stripeToken;


            try {
                $stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
                $customer = $stripe->customers->create([
                    'email' => $email,
                    'shipping' => [
                        'address' => [
                            'line1' => $input['address_line_1'],
                            'line2' => $input['address_line_2'],
                            'city' => $input['city'],
                            'country' => $country->countries_name,
                            'postal_code' => $input['postal_code'],
                        ],
                        'name' => $input['billing_name'],
                    ],
                    'address' => [
                        'line1' => $input['address_line_1'],
                        'line2' => $input['address_line_2'],
                        'city' => $input['city'],
                        'country' => $country->countries_name,
                        'postal_code' => $input['postal_code'],
                    ],
                ]);

                if (!empty($customer)) {
                    $paymentMethod = $stripe->paymentMethods->create([
                        'type' => 'card',
                        'card' => ['token' => $token]
                    ]);

                    $customer_payment_method = $stripe->paymentMethods->attach(
                        $paymentMethod->id, ['customer' => $customer->id]
                    );

                    $paymentIntents =   $stripe->paymentIntents->create([
                      'amount' => $amount*100,
                      'currency' => 'usd',
                      'payment_method_types' => ['card'],
                      'customer'=>$customer->id
                  ]);


                    $subscription = $stripe->subscriptions->create([
                        'customer' => $customer->id,
                        'items' => [
                            ['price' => $input['plan']],
                        ],
                        'default_payment_method' => $customer_payment_method,
                        'trial_period_days' => $trial_days->duration ?: 0,
                        'coupon'=>$coupon
                    ]);



                    $charge = $stripe->charges->all(['limit' => 1,'customer'=>$customer->id]);

                    if(isset($charge) && !empty($charge->data)){
                        $charge_id = $charge->data[0]->id;
                        $payment_intent = $charge->data[0]->payment_intent;
                    }else{
                        $charge_id = $payment_intent = '';
                    }


                    $user = User::create([
                        'name' => ucwords($input['billing_name']),
                        'email'=>$email,
                        'password'=>Hash::make($password),
                        'role'=>'front',
                        'role_id'=>'2',
                        'company_name'=>strtolower(str_replace(' ','',$vanity_url)),
                        'company'=>trim($company),
                        'stripe_id' => $customer->id,
                        'card_brand'=>$customer_payment_method->card->brand,
                        'card_last_four'=>$customer_payment_method->card->last4
                    ]);
                    if ($user) {
                        $email_token = base64_encode($user->created_at.$user->id);
                        User::where('id',$user->id)->update(['email_verification_token'=>$email_token,'email_sent_at'=>now()]);

                        $package = Package::where('id', $package_id)->first();
                        if($package_state == 'month'){
                            $price = $package->monthly_amount;
                        }elseif($package_state == 'year'){
                            $price = $package->yearly_amount*12;
                        }

                        UserPackage::create([
                            'user_id' => $user->id,
                            'package_id' => $package_id,
                            'projects' => $package->number_of_projects,
                            'keywords' => $package->number_of_keywords,
                            'flag' => '1',
                            'trial_days' => $package->duration ?: 0,
                            'price'=>$price,
                            'subscription_type'=>$package_state,
                            'package_purchase' => 1
                        ]);


                        UserAddress::create([
                            'user_id' => $user->id,
                            'address_line_1' => $input['address_line_1'],
                            'address_line_2' => $input['address_line_2'],
                            'city' => $input['city'],
                            'country' => $input['country'],
                            'zip' => $input['postal_code']
                        ]);



                        $save_subscription = Subscription::create([
                            'user_id' => $user->id,
                            'charge_id'=>$charge_id,
                            'payment_intent_id'=>$payment_intent,
                            'payment_id'=>$customer_payment_method->id,
                            'customer_id'=>$customer->id,
                            'stripe_id' => $subscription->id,
                            'coupon_id'=>$coupon_id
                        ]);


                            // $subscription_id = $save_subscription->id;

                            // SubscriptionItem::create([
                            //     'stripe_id' => $subscription->id,
                            //     'stripe_plan' => $input['plan'],
                            //     'quantity' => 1,
                            //     'subscription_id' => $subscription_id,
                            // ]);


                        Auth::loginUsingId($user->id);
                        $this->registeration($user->id);
                        $this->email_verification($user->id);
                        return redirect('/thankyou');
                    }

                }
            }catch (Exception $e) {
                return back()->with('success', $e->getMessage());
            }
        } 
    }
}


private function send_cancelled_email($user){
    $data = array('name' => $user->name);
    \Mail::send(['html' => 'mails/front/subscription_downgrade'], $data, function($message) use($user) {
        $message->to($user->email, $user->company)
        ->subject('Subscription Refund!');
        $message->from(\config('app.mail'), 'Agency Dashboard');
    });
    if (\Mail::failures()) {
        return false;
    } else {
        return true;
    }
}

public function thankyou() {
    $user = Auth::user();
    $get_user_package = UserPackage::with('package')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->first();
    $redirect_link = 'https://' . $user->company_name . '.' . \config('app.APP_DOMAIN') . 'dashboard/' . Crypt::encrypt($user->id);
    Session::flush();
    return view('/thankyou', ['redirect_link' => $redirect_link, 'package_name' => $get_user_package->package->name]);
}


public function stripe_new (Request $request){ 
    $user = array();
    $countries = Country::get();
    if($request->has('reg_id')){
        $encoded = $request->reg_id;
        $string = base64_decode($encoded);
        $exploded = explode('+',$string);
        $email = $exploded[0];
        $package_id = $exploded[4];
        $package_state = $exploded[5];
    }


    if($request->has('id')){
        $encoded = $request->id;
        $string = base64_decode($encoded);
        $exploded_data = explode('+',$string);
        $email = $exploded_data[0];
        $package_id = $exploded_data[3];
        $package_state = $exploded_data[4];
        $user_id = $exploded_data[5];
        $user = User::with('UserAddress')->where('id',$user_id)->first();
    }


    $result =  Package::where('id',$package_id)->orderBy('created_at', 'desc')->first();


    if($package_state == 'month'){
        $package_price_id = $result->stripe_price_id;
        $package_amount = $result->monthly_amount;
    }elseif($package_state == 'free'){
        $package_price_id = '';
        $package_amount = 0;
    }else{
        $package_price_id = $result->stripe_price_yearly_id;
        $package_amount = $result->yearly_amount*12;
    }

    $package_name = $result->name;

    if(isset($exploded[6]) && !empty($exploded[6]) && ($exploded[6] <> null)){
        $coupon_code = $exploded[6];
        $coupon_data = Coupon::where('code',$exploded[6])->first();
        $after_discount = $this->calculate_discount($package_amount,$coupon_data);
        $coupon_state = 1;
    }else{
        $coupon_state = $after_discount =  0;$coupon_code = '';
    }


    $stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
    $prices = $stripe->prices->all(['active' => true]);
    if (!empty($prices)) {
        foreach ($prices as $key => $price) {
            $prices->data[$key]['product_data'] = $stripe->products->retrieve(
                $price->product
            );
        }
    }


    return view('front.stripe_subscription', [
        'prices' => $prices,
        'package_price_id' => $package_price_id,
        'countries' => $countries,
        'email' => $email,
        'string'=>$encoded,
        'after_discount'=>$after_discount,
        'package_amount'=>$package_amount,
        'coupon_state'=>$coupon_state,
        'package_state'=>$package_state,
        'user'=>$user,
        'coupon_code'=>$coupon_code,
        'packageId'=>$package_id,
        'package_name' => $package_name,
        'subscription_type'=>($request->has('reg_id'))?'registeration':'existing',
        'purchase_mode'=>($request->has('id'))?$user->purchase_mode:''
    ]);

}

private function calculate_discount($package_amount,$coupon_data){
    $percent = $coupon_data->value;

    $calculated_value = number_format(($package_amount * $percent)/100,2);
    if($percent ==100){
        $final = 0.00;
    }
    else{
        $final = number_format(($package_amount - $calculated_value),2);
    }
    return $final;
}

private function registeration($user_id) {
    $app_domain = \config('app.APP_DOMAIN');
    $user = User::where('id', $user_id)->select('name', 'email', 'company_name','company')->first();
    $link = 'https://' . $user->company_name . '.' . $app_domain . 'login';
    $data = array('name' => $user->company, 'email' => $user->email, 'from' => \config('app.MAIL_FROM_NAME'), 'link' => $link);
    \Mail::send(['html' => 'mails/front/registeration'], $data, function($message) use($user) {
        $message->to($user->email, $user->company)
        ->subject('Welcome to Agency Dashboard!');
        $message->from(\config('app.mail'), 'Agency Dashboard');
    });
    if (\Mail::failures()) {
        return false;
    } else {
        return true;
    }
}

private function email_verification($user_id){
    $app_domain = \config('app.APP_DOMAIN');
    $user = User::where('id', $user_id)->first();
    $link = 'https://' . $user->company_name . '.' . $app_domain . 'confirmation/'.$user->email_verification_token;
    $data = array('name' => $user->name, 'email' => $user->email, 'from' => \config('app.MAIL_FROM_NAME'), 'link' => $link);
    \Mail::send(['html' => 'mails/front/email_verification'], $data, function($message) use($user) {
        $message->to($user->email, $user->name)->subject
        ('Activate Account - Agency Dashboard');
        $message->from(\config('app.mail'), 'Agency Dashboard');
    });
    if (\Mail::failures()) {
        return false;
    } else {
        return true;
    }
}


public function create_free_forever_subscription(Request $request){
    $decode_data = base64_decode($request->data_key);
    $explode = explode('+',$decode_data);
    if($request->existing_user !='' && !empty($request->existing_user) && ($request->existing_user <> null)){
        $email = $explode[0];
        $company = $explode[1];
        $vanity_url = $explode[2];
        $package_id = $explode[3];
        $package_state = $explode[4];
        $user_id = $explode[5];

        $user = User::where('id',$user_id)->update([
            'name' => ucwords($request->billing_name),
            'email'=>$email,
            'stripe_id' => NULL,
            'purchase_mode'=>0,
            'user_type'=>1,
            'subscription_status'=>1,
            'subscription_ends_at'=> date('Y-m-d H:i:s',strtotime('+20 years'))
        ]);
        
        $package = Package::where('id', $package_id)->first();
        $price = 0;

        UserPackage::create([
            'user_id' => $user_id,
            'package_id' => $package_id,
            'projects' => $package->number_of_projects,
            'keywords' => $package->number_of_keywords,
            'flag' => '1',
            'trial_days' => 0,
            'price'=>$price,
            'subscription_type'=>$package_state,
            'package_purchase' => 1
        ]);


        UserAddress::where('user_id',$user_id)->update([
            'user_id' => $user_id,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'country' => $request->country,
            'zip' => $request->postal_code
        ]);

        UserCredit::create([
           'user_id' => $user_id,
           'package_credit' => $package->site_audit_page
       ]);

        $dataReturn['status'] = 1;
        $dataReturn['url'] = '/thankyou';
        
    }else{

        $email = $explode[0];
        $password = $explode[1];
        $company = $explode[2];
        $vanity_url = $explode[3];
        $package_id = $explode[4];
        $package_state = $explode[5];

        $country = Country::where('id', $request->country)->first();
        $user = User::create([
            'name' => ucwords($request->billing_name),
            'email'=>$email,
            'password'=>Hash::make($password),
            'role'=>'front',
            'role_id'=>'2',
            'company_name'=>strtolower(str_replace(' ','',$vanity_url)),
            'company'=>trim($company),
            'stripe_id' => NULL,
            'purchase_mode'=>0,
            'user_type'=>1,
            'subscription_ends_at'=> date('Y-m-d H:i:s',strtotime('+20 years'))
        ]);

        $email_token = base64_encode($user->created_at.$user->id);
        User::where('id',$user->id)->update(['email_verification_token'=>$email_token,'email_sent_at'=>now()]);

        $package = Package::where('id', $package_id)->first();
        $price = 0;

        if($package <> null){
            UserPackage::create([
                'user_id' => $user->id,
                'package_id' => $package_id,
                'projects' => $package->number_of_projects,
                'keywords' => $package->number_of_keywords,
                'flag' => '1',
                'trial_days' => 0,
                'price'=>$price,
                'subscription_type'=>$package_state,
                'package_purchase' => 1
            ]);


            UserAddress::create([
                'user_id' => $user->id,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city' => $request->city,
                'country' => $request->country,
                'zip' => $request->postal_code
            ]);

            UserCredit::create([
               'user_id' => $user->id,
               'package_credit' => $package->site_audit_page
           ]);

            Auth::loginUsingId($user->id);
            $this->registeration($user->id);
            $this->email_verification($user->id); 

            $dataReturn['status'] = 1;
            $dataReturn['url'] = '/thankyou';
        }
        else{
           $dataReturn['status'] = 0;
       }
    }
   return response()->json($dataReturn);
}

}
