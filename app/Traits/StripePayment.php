<?php 

namespace App\Traits;
use App\Subscription;
use App\SubscriptionItem;
use App\User;
use App\Invoice;
use App\InvoiceItem;
use Mail;
use App\Package;
use App\UserCredit;
use App\UserPackage;
use App\InvoiceChange;

trait StripePayment{

  public function insertSubscription($subscription){
    $get_user = User::where('stripe_id',$subscription['customer_id'])->first();
    $if_Exists = Subscription::where('stripe_id',$subscription['subscription_id'])->first();
    if($get_user){
      User::where('id',$get_user->id)->update([
        'subscription_ends_at'=>$subscription['current_period_end']
      ]);

      $subs = Subscription::updateOrCreate(
        ['stripe_id' => $subscription['subscription_id']],
        [
        'user_id' =>$get_user->id,
        'stripe_id' =>$subscription['subscription_id'], //stripe_id
        'customer_id' =>$subscription['customer_id'],
        'stripe_plan' =>$subscription['subscription_plan'], // stripe plan
        'subscription_interval' =>$subscription['subscription_interval'],
        'stripe_status' =>$subscription['subscription_status'], //stripe status
        'current_period_start' =>$subscription['current_period_start'],
        'current_period_end' =>$subscription['current_period_end'],
        'subscription_created_date' =>$subscription['subscription_created_date'],
        'amount'=>$subscription['amount']/100,
        'quantity'=>$subscription['quantity'],
        'coupon_name'=>$subscription['coupon_name'],
        'trial_ends_at'=>$subscription['trial_end_date'],
        'discount'=>$subscription['discount'],
        'response'=>json_encode($subscription['response'],true)
      ]
    );

      $subscription_id = $subs->id;

      SubscriptionItem::create([
        'stripe_id' => $subscription['subscription_id'],
        'stripe_plan' => $subscription['subscription_plan'],
        'quantity' => 1,
        'subscription_id' => $subscription_id,
      ]);

    }

  }

  public function updateSubscription($subscription){
    $get_detail = Subscription::where('stripe_id',$subscription['subscription_id'])->first();

    User::where('id',$get_detail->user_id)->update([
      'subscription_ends_at'=>$subscription['current_period_end']
    ]);

    Subscription::where('stripe_id',$subscription['subscription_id'])->update([
      'stripe_status'=>$subscription['subscription_status'],
      'current_period_start'=>$subscription['current_period_start'],
      'current_period_end'=>$subscription['current_period_end'],
      'response'=>json_encode($subscription['response'],true)
    ]);
  }

  public function insertInvoice($subscription){
    $sub_data = Subscription::where('stripe_id',$subscription['subscription_id'])->first();
    if(!empty($sub_data)){
      $sub_data_id = $sub_data->id;
    }else{
      $sub_data_id = null;
    }

    if($subscription['subscription_id'] <> null || !empty($subscription['subscription_id'])){
      $subscription_id = $subscription['subscription_id'];
    }else{
      $subscription_id = null;
    }

    if($subscription['invoice_number'] <> null || !empty($subscription['invoice_number'])){
      $invoice_number = $subscription['invoice_number'];
    }else{
      $invoice_number = null;
    }

    $inserId = Invoice::updateOrCreate(
      ['invoice_id'=>$subscription['invoice_id']],
      [
        'subscription_master_id'=>$sub_data_id,
        'invoice_id'=>$subscription['invoice_id'],
        'subscription_id'=>$subscription_id,
        'invoice_number'=>$invoice_number,
        'customer_id'=>$subscription['customer_id'],
        'billing_email'=>$subscription['billing_email'],
        'currency'=>$subscription['currency'],
        'invoice_status'=>$subscription['invoice_status'],
        'invoice_created_date'=>$subscription['invoice_created_date'],
        'response'=>json_encode($subscription['response'],true)
      ]);

    sleep(1);

    if(!empty($inserId))
    {
      $this->insertInvoiceItem($subscription["invoice_items"], $inserId->id);
    //$this->send_invoice($subscription['customer_id'],$subscription['subscription_id']);
    }
  }


  public function insertInvoiceItem($invoiceItem, $invoiceMasterId){

    foreach($invoiceItem as $item){
      InvoiceItem::create([
        'invoice_master_id'=>$invoiceMasterId,
        'description'=>$item['description'],
        'amount'=>$item['amount']/100,
        'currency'=>$item['currency'],
        'quantity'=>$item['quantity']
      ]);
    }

  }

  public function updateInvoice($invoice)
  {

    if(isset($invoice["invoice_finalized_date"])){
      Invoice::where('invoice_id',$invoice["invoice_id"])->update([
        'invoice_finalized_date'=> $invoice["invoice_finalized_date"],
        'invoice_status'=> $invoice["invoice_status"],
        'response'=>json_encode($invoice['response'],true)
      ]);
    }else{
      Invoice::where('invoice_id',$invoice["invoice_id"])->update([
        'invoice_status'=> $invoice["invoice_status"],
        'response'=>json_encode($invoice['response'],true)
      ]);
    }
  }

  public function updateInvoiceStatus($invoice){
    $invoice_response = $invoice['response'];
    $invoice_data = $invoice_response->data->object;

    if($invoice_data->custom_fields <> null && ($invoice_data->custom_fields[1]->name == 'subscription_type' && ($invoice_data->custom_fields[1]->value == 'change_plan' || $invoice_data->custom_fields[1]->value == 'send_invoice') )){
      $user_id = $invoice_data->custom_fields[0]->value;
      $previous_status = $invoice_data->custom_fields[1]->value;
      $package_id = $invoice_data->custom_fields[2]->value;
      $interval = $invoice_data->custom_fields[3]->value;
      $package_type = chop($interval,"1 ");

      if($invoice_data->number <> null || $invoice_data->number !== ''){
        $invoice_number = $invoice_data->number;
      }else{
        $invoice_number = $this->craete_invoice_number($invoice_data->customer,$user_id);
      }


      $next_invoice_on = date('Y-m-d',strtotime('-1 day',$invoice_data->lines->data[0]->period->end));

      InvoiceChange::where('user_id',$user_id)->where('invoice_id',$invoice_data->id)->delete();

      Subscription::where('user_id',$user_id)->update([
        'stripe_status' => 'canceled',
        'canceled_at' => now()
      ]);

      User::where('id',$user_id)->update([
        'stripe_id' => $invoice_data->customer,
        'subscription_status' => 1,
        'subscription_ends_at' => date('Y-m-d H:i:s',$invoice_data->lines->data[0]->period->end),
        'user_type' => 0,
        'purchase_mode' => 2
      ]); 

      $package_info = Package::where('id',$package_id)->first();

      UserPackage::create([
        'user_id' => $user_id,
        'package_id' => $package_id,
        'projects' => $package_info->number_of_projects,
        'keywords' => $package_info->number_of_keywords,
        'flag' => '1',
        'trial_days' => 0,
        'price'=>($invoice_data->lines->data[0]->price->unit_amount)/100,
        'subscription_type'=>$package_type,
        'package_purchase' => 1
      ]);

      $subscription = Subscription::create([
        'user_id' => $user_id,
        'customer_id' => $invoice_data->customer,
        'subscription_interval' => $interval,
        'current_period_start'=>date('Y-m-d H:i:s',$invoice_data->lines->data[0]->period->start),
        'current_period_end'=>date('Y-m-d H:i:s',$invoice_data->lines->data[0]->period->end),
        'stripe_plan' => $invoice_data->lines->data[0]->price->id,
        'amount' => ($invoice_data->lines->data[0]->price->unit_amount)/100,
        'stripe_status'=> 'active',
        'next_invoice_on' => $next_invoice_on,
        'invoice_link_expiration' => NULL,
        'reminder_on' => NULL,
        'subscription_created_date'=>date('Y-m-d H:i:s')
      ]);

      $invoice_insert = Invoice::updateOrCreate(
        ['invoice_id'=>$invoice_data->id],
        [
          'subscription_master_id' => $subscription->id,
          'invoice_id' => $invoice_data->id,
          'subscription_id' => NULL,
          'invoice_number' => $invoice_number,
          'customer_id'=>$invoice_data->customer,
          'billing_email'=>$invoice_data->customer_email,
          'currency'=>$invoice_data->currency,       
          'invoice_status'=>$invoice_data->status,
          'amount_paid'=>($invoice_data->amount_paid)/100,
          'amount_due'=>($invoice_data->amount_due)/100,
          'amount_remaining'=>($invoice_data->amount_remaining)/100,
          'invoice_created_date'=>date('Y-m-d H:i:s',$invoice_data->created),
          'hosted_invoice_url' => $invoice_data->hosted_invoice_url,
          'invoice_pdf' => $invoice_data->invoice_pdf,
          'current_period_start'=>date('Y-m-d H:i:s',$invoice_data->lines->data[0]->period->start),
          'current_period_end'=>date('Y-m-d H:i:s',$invoice_data->lines->data[0]->period->end),
          'response'=>json_encode($invoice_response,true),
          'invoice_interval'=> $interval
        ]);


      InvoiceItem::updateOrCreate(
        ['invoice_master_id'=>$invoice_insert->id],
        [
          'invoice_master_id' => $invoice_insert->id,
          'description'=>$invoice_data->lines->data[0]->description,
          'amount'=>($invoice_data->lines->data[0]->amount)/100,
          'currency'=>$invoice_data->currency,
          'quantity'=>1
        ]);

    }else{
        if(isset($invoice["invoice_finalized_date"])){
         Invoice::where('invoice_id',$invoice["invoice_id"])->update([
           'invoice_finalized_date'=> $invoice["invoice_finalized_date"],
           'invoice_status'=> $invoice["invoice_status"],
           'response'=>json_encode($invoice['response'],true)
         ]);
       }else{
        Invoice::where('invoice_id',$invoice["invoice_id"])->update([
         'invoice_status'=> $invoice["invoice_status"],
         'response'=>json_encode($invoice['response'],true)
       ]);
      }
      $previous_status = $invoice_data->billing_reason;
    }


    if($invoice['type'] == 'invoice.payment_succeeded'){
      $this->send_invoice_to_user($invoice_response,$previous_status);  
    }
  }



public function send_invoice_bkp($customer_id,$subscription_id) {
  $stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
  $invoice_response = $stripe->invoices->all(['customer' => $customer_id,'subscription' => $subscription_id]);
  $invoice_data =  end($invoice_response->data);

  $discounted_value = 0.00;
  if(!empty($invoice_data->total_discount_amounts)){
    $discounted_value =  number_format(($invoice_data->total_discount_amounts[0]->amount/100),2);
  }

  $data = array(
    'email' => $invoice_data->customer_email,
    'account_name' => $invoice_data->customer_shipping->name,
    'amount_paid' => ($invoice_data->amount_paid)/100,
    'invoice_number' => $invoice_data->number,
    'description' => $invoice_data->lines->data[0]->description,
    'start' => gmdate("Y-m-d", $invoice_data->lines->data[0]->period->start),
    'end' => gmdate("Y-m-d", $invoice_data->lines->data[0]->period->end),
    'city' => $invoice_data->customer_address->city,
    'country' => $invoice_data->customer_address->country,
    'line1' => $invoice_data->customer_address->line1,
    'line2' => $invoice_data->customer_address->line2,
    'postal_code' => $invoice_data->customer_address->postal_code,
    'discounted_value' => $discounted_value,
    'from' => \config('app.MAIL_FROM_NAME')
  );


  \Mail::send(['html' => 'mails/front/subscription_invoice'], $data, function($message) use($data) {
    $message->to($data['email'], $data['account_name'])->subject
    ('Invoice - Agency Dashboard!');
    $message->from(\config('app.mail'), 'Agency Dashboard');
  });
  if (\Mail::failures()) {
    return redirect()->back()->withErrors(['error' => 'Error sending email']);
  } else {
    return true;
  }
} 

public function send_invoice($customer_id,$subscription_id) {
  $stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));

  if($subscription_id <> null){
    $invoice_response = $stripe->invoices->all(['customer' => $customer_id,'subscription' => $subscription_id]);    
  }else{
    $invoice_response = $stripe->invoices->all(['customer' => $customer_id]);    
  }

  $invoice_data =  end($invoice_response->data);
    //file_put_contents(dirname(__FILE__).'/invoice_data.txt',print_r($invoice_data,true));


  /*To get detail of the subscribed plan*/
  $string = $invoice_data->lines->data[0]->plan->id;
  $field = ['stripe_price_id','stripe_price_yearly_id'];
  $package_detail = Package::
  where(function ($query) use($string, $field) {
    for ($i = 0; $i < count($field); $i++){
      $query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
    }      
  })
  ->first();

  $package_name = $package_detail->name .' Plan';
  $package_price = ($invoice_data->lines->data[0]->plan->amount)/100;

  $discounted_value = 0.00;
  if(!empty($invoice_data->total_discount_amounts)){
    $discounted_value =  number_format(($invoice_data->total_discount_amounts[0]->amount/100),2);
  }

  if($invoice_data->currency <> null || $invoice_data->currency != ''){
    $currency = $invoice_data->currency;
  }else{
    $currency = 'usd';
  }

  $data = array(
    'email' => $invoice_data->customer_email,
    'account_name' => $invoice_data->customer_shipping->name,
    'amount_paid' => ($invoice_data->amount_paid)/100,
    'invoice_number' => $invoice_data->number,
    'description' => $invoice_data->lines->data[0]->description,
    'start' => gmdate("Y-m-d", $invoice_data->lines->data[0]->period->start),
    'end' => gmdate("Y-m-d", $invoice_data->lines->data[0]->period->end),
    'city' => $invoice_data->customer_address->city,
    'country' => $invoice_data->customer_address->country,
    'line1' => $invoice_data->customer_address->line1,
    'line2' => $invoice_data->customer_address->line2,
    'postal_code' => $invoice_data->customer_address->postal_code,
    'discounted_value' => $discounted_value,
    'from' => \config('app.MAIL_FROM_NAME'),
    'package_name'=>$package_name,
    'package_price'=>$package_price,
    'status'=>$invoice_data->status,
    'next_payment_attempt'=>$invoice_data->next_payment_attempt,
    'currency'=>$currency
  );


  \Mail::send(['html' => 'mails/front/subscription_invoice'], $data, function($message) use($data) {
    $message->to($data['email'], $data['account_name'])->subject('Invoice - Agency Dashboard!');
    $message->from(\config('app.mail'), 'Agency Dashboard');
  });


  $admin = User::where('role_id', 1)->first();
  \Mail::send(['html' => 'mails/front/admin_subscription_invoice'], $data, function($admin_message) use($admin) {
    $admin_message->to($admin->email, $admin->name)->subject('Invoice - Agency Dashboard!');
    $admin_message->from(\config('app.mail'), 'Agency Dashboard');
  });

  if (\Mail::failures()) {
    return redirect()->back()->withErrors(['error' => 'Error sending email']);
  } else {
    return true;
  }
} 

public function send_invoice_to_user($response,$previous_status) {
  $invoice_data = $response->data->object;

  $user = User::select('id','company_name')->where('email',$invoice_data->customer_email)->first();

  $agency_name = $user->company_name;
  $invoice_id = $invoice_data->id;
  $invoicing = Invoice::where('invoice_id',$invoice_id)->first();
  $price_id = $invoice_data->lines->data[0]->price->id;
  $field = ['stripe_price_id','stripe_price_yearly_id','inr_price_monthly_id','inr_price_yearly_id'];
  $package_detail = Package::
  where(function ($query) use($price_id, $field) {
    for ($i = 0; $i < count($field); $i++){
      $query->orwhere($field[$i], 'LIKE',  '%' . $price_id .'%');
    }      
  })
  ->first();

  $package_name = $package_detail->name .' Plan';
  $package_price = ($invoice_data->lines->data[0]->price->unit_amount)/100;

  $discounted_value = 0.00;
  if(!empty($invoice_data->total_discount_amounts)){
    $discounted_value =  number_format(($invoice_data->total_discount_amounts[0]->amount/100),2);
  }


  if($invoice_data->currency <> null){
    $currency = $invoice_data->currency;
  }else{
    $currency = 'usd';
  }
  $data = array(
    'email' => $invoice_data->customer_email,
    'account_name' => $invoice_data->customer_shipping->name,
    'agency_name' => $agency_name,
    'amount_paid' => ($invoice_data->amount_paid)/100,
    'invoice_number' => $invoicing->invoice_number,
    'description' => $invoice_data->lines->data[0]->description,
    'start' => gmdate("Y-m-d", $invoice_data->lines->data[0]->period->start),
    'end' => gmdate("Y-m-d", $invoice_data->lines->data[0]->period->end),
    'city' => $invoice_data->customer_address->city,
    'country' => $invoice_data->customer_address->country,
    'line1' => $invoice_data->customer_address->line1,
    'line2' => $invoice_data->customer_address->line2,
    'postal_code' => $invoice_data->customer_address->postal_code,
    'discounted_value' => $discounted_value,
    'from' => \config('app.MAIL_FROM_NAME'),
    'package_name'=>$package_name,
    'package_price'=>$package_price,
    'status'=>$invoice_data->status,
    'next_payment_attempt'=>$invoice_data->next_payment_attempt,
    'currency'=> $currency,
    'previous_status'=> $previous_status
  );

  \Mail::send(['html' => 'mails/front/subscription_invoice'], $data, function($message) use($data) {
    $message->to($data['email'], $data['account_name'])->subject('Invoice - Agency Dashboard!');
    $message->from(\config('app.mail'), 'Agency Dashboard');
  });


  $admin = User::where('role_id', 1)->first();
  \Mail::send(['html' => 'mails/front/admin_subscription_invoice'], $data, function($admin_message) use($admin) {
    $admin_message->to($admin->email, $admin->name)->subject('Invoice - Agency Dashboard!');
    $admin_message->from(\config('app.mail'), 'Agency Dashboard');
  });

  if (\Mail::failures()) {
    return redirect()->back()->withErrors(['error' => 'Error sending email']);
  } else {
    return true;
  }
} 
}