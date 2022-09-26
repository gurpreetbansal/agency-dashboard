<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportManualInvoice implements FromCollection,WithHeadings
{

	private $response;

	public function __construct($response) 
	{
        $this->response = $response;
    }

    public function headings(): array {
      return [
         "Date","Duration","Description","Invoice Id","Plan Amount","Status","Discount","Amount Paid","Amount Due"
     ];
 }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        $invoices = $this->response;
       
        $array = array();
        if(isset($invoices) && !empty($invoices)){
            foreach($invoices as $invoice){

                if($invoice->subscription->subscription_interval == '1 year'){
                    $plan_amount = $invoice->subscription->amount/12;
                }else{
                    $plan_amount = $invoice->subscription->amount;
                }

                $array[] = [
                    'date' => date('Y-m-d',strtotime($invoice->invoice_created_date)),
                    'duration' =>date('M d,Y',strtotime($invoice->current_period_start)).' - '.date('M d,Y',strtotime($invoice->current_period_end)),
                    'description' =>$invoice->invoices_item->description,
                    'invoice_id'=>$invoice->invoice_number,
                    'amount'=>number_format($plan_amount,2),
                    'status'=>$invoice->invoice_status,
                    'discount'=>  $invoice->discount?:'-',
                    'amount_paid'=>number_format($invoice->amount_paid,2),
                    'amount_due'=>number_format($invoice->amount_remaining,2)
                ];               
            }
        }
        return collect($array);
    }
}