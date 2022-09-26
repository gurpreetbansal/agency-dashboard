<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Stripe;
use App\Subscription;
use App\Invoice;
use App\InvoiceItem;
use App\Package;

class SendStripeInvoice extends Command 
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'Stripe:SendInvoice';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Send invoice to indian customers';

    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Execute the console command.
    *
    * @return int
    */
    public function handle() {
        $days_until_due = 9; //default 9 days
        $subscription_data = Subscription::with('userDetail')->where('next_invoice_on',date('Y-m-d'))->where('stripe_status','!=','canceled')->get();

        if(isset($subscription_data) && !empty($subscription_data)){
            foreach($subscription_data as $key=>$value){
                $string = $value->stripe_plan;
                $field = ['inr_price_monthly_id','inr_price_yearly_id'];

                $get_package = Package::
                where(function ($query) use($string, $field) {
                    for ($i = 0; $i < count($field); $i++){
                        $query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
                    }      
                })
                ->first();   
                if(isset($get_package) && !empty($get_package)){
                    $package_id = $get_package->id;

                    $invoice_count = Invoice::where('customer_id',$value->customer_id)->get();

                    $string = 'AD-'.strtoupper(substr($value->userDetail->company_name, 0, 3));

                    if(count($invoice_count) > 0){
                        $invoice_number = Invoice::generate_invoice_number(count($invoice_count),$string);
                    }else{
                        $invoice_number = Invoice::generate_invoice_number(0,$string);
                    }



                    if($value->coupon_name <> null){
                        $coupon_name = $value->coupon_name;
                    }else{
                        $coupon_name = '';
                    }


                    $start_date = strtotime($value->current_period_end);
                    $end_date = strtotime('+ '.$value->subscription_interval,strtotime($value->current_period_end));


                    try {
                        $stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
                        $invoice_items =  $stripe->invoiceItems->create(
                            [
                                'customer' => $value->customer_id,
                                'price' => $value->stripe_plan,
                                'period'=> [
                                    'end' => $end_date,
                                    'start' => $start_date
                                ]
                            ]
                        );

                        if($invoice_items){
                            $invoice_item_id = $invoice_items->id;

                            $invoice_item_created = InvoiceItem::create([
                                'description'=>$invoice_items->description,
                                'amount'=>$invoice_items->amount/100,
                                'currency'=>$invoice_items->currency,
                                'quantity'=>1
                            ]);

                            $invoice = $stripe->invoices->create(
                                [
                                    'auto_advance' => false,
                                    'collection_method' => 'send_invoice',
                                    'customer' => $value->customer_id,
                                    'days_until_due' => $days_until_due,
                                    'custom_fields'=> [
                                        ['name'=>'user_id','value'=>$value->user_id],
                                        ['name'=>'subscription_type','value'=>'send_invoice'],
                                        ['name'=>'package_id','value'=>$package_id],
                                        ['name'=>'interval','value'=>$value->subscription_interval]
                                    ]                                                               
                                ]
                            );


                            $inserId = Invoice::create([
                                'subscription_master_id' => $value->id,
                                'invoice_id' => $invoice->id,
                                'subscription_id' => NULL,
                                'invoice_number' => $invoice_number,
                                'customer_id'=>$value->customer_id,
                                'billing_email'=>$value->userDetail->email,
                                'currency'=>$invoice->currency,
                                'invoice_status'=>$invoice->status,
                                'amount_paid'=>$invoice->amount_paid,
                                'amount_due'=>($invoice->amount_due)/100,
                                'amount_remaining'=>($invoice->amount_remaining)/100,
                                'invoice_created_date'=>date('Y-m-d H:i:s',$invoice->created),
                                'response'=>json_encode($invoice,true)
                            ]);

                            InvoiceItem::where('id',$invoice_item_created->id)->update([
                                'invoice_master_id' => $inserId->id
                            ]);

                            $sent =  $stripe->invoices->sendInvoice($invoice->id);

                            if($sent){
                                Invoice::where('invoice_id', $invoice->id)->update([
                                    'invoice_status'=> $sent->status,
                                    'response' => json_encode($sent,true),
                                    'hosted_invoice_url' => $sent->hosted_invoice_url,
                                    'invoice_pdf' => $sent->invoice_pdf,
                                    'current_period_start'=>date('Y-m-d H:i:s',$sent->lines['data'][0]['period']['start']),
                                    'current_period_end'=>date('Y-m-d H:i:s',$sent->lines['data'][0]['period']['end'])
                                ]);

                                Subscription::where('id',$value->id)->update([
                                    // 'invoice_link_expiration' => date('Y-m-d',strtotime('+ '.$days_until_due.' days',strtotime(now()))),
                                    'invoice_link_expiration' => date('Y-m-d H:i:s',$sent->due_date),
                                    'reminder_on' => date('Y-m-d',strtotime('+2 day',strtotime(now()))),
                                ]);
                            }
                        }
                    } catch (Exception $e) {
                        file_put_contents(dirname(__FILE__).'/scheduled_logs/stripe.txt',print_r($e->getMessage(),true));
                    }
                } //end get_package
            } //end foreach
        } //end main if
    } //end handle
}