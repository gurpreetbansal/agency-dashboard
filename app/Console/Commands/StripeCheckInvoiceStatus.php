<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Stripe;
use App\Invoice;
use App\InvoiceChange;

class StripeCheckInvoiceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Stripe:CheckInvoiceStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check status of invoice and remove after 5 minutes.';

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
     $now = date_create(date('Y-m-d H:i:s')); 
     $invoices = InvoiceChange::get();
     if(isset($invoices) && !empty($invoices)){
        foreach($invoices as $key=>$invoice){
            $creation_time = date_create($invoice->created_at); 
            $difference = date_diff($creation_time, $now); 
            if($difference->i >= 5){ //if more than 5 minutes
                $invoice_id = $invoice->invoice_id;
                $stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
                $void = $stripe->invoices->voidInvoice($invoice_id);  
                if($void->status == 'void'){
                    InvoiceChange::where('id',$invoice->id)->delete();
                }
            } //end-if more than 5 minutes
        }
    }
}
}