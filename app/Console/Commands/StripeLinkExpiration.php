<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Stripe;
use App\Subscription;
use App\Invoice;
use App\User;

class StripeLinkExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Stripe:LinkExpiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire link for the unpaid invoice';

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
    public function handle()
    {
         $subscription_data = Subscription::with('userDetail')->where('invoice_link_expiration',date('Y-m-d'))->get();
         if(isset($subscription_data) && !empty($subscription_data)){
            foreach($subscription_data as $key => $value){        

                $invoice = Invoice::where('subscription_master_id',$value->id)->where('invoice_status','open')->latest()->first();
                if(!empty($invoice) && isset($invoice)){
                    file_put_contents(dirname(__FILE__).'/logs/invoice.txt',print_r($invoice,true));
                    try{
                        $stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
                        if(!empty($invoice->invoice_id) && $invoice->invoice_id != '' && $invoice->invoice_id <> null){
                            $void =  $stripe->invoices->voidInvoice($invoice->invoice_id);   

                            if($void->status == 'void'){
                                Subscription::where('id',$value->id)->update([
                                    'next_invoice_on' => NULL,
                                    'invoice_link_expiration' => NULL,
                                    'reminder_on' => NULL,
                                    'stripe_status'=>'canceled',
                                    'canceled_at'=>date('Y-m-d H:i:s',strtotime(now()))
                                ]);

                                User::where('id',$value->userDetail->id)->update([
                                    'subscription_status' => 0,
                                    'subscription_ends_at' => date('Y-m-d H:i:s',strtotime(now()))
                                ]);

                                Invoice::where('id',$invoice->id)->update([
                                   'invoice_status' =>'void'
                                ]);
                            }
                        }
                    }catch (Exception $e) {
                       
                    }
                }           
            }
        }
    }
}