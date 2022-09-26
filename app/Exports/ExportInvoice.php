<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportInvoice implements FromCollection,WithHeadings
{

	private $response;

	public function __construct($response) 
	{
        $this->response = $response;
	}

	public function headings(): array {
		return [
			"Date","Duration","Description","Invoice Id","Amount","Status","Discount","Amount Paid"
		];
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $invoices = $this->response;
        // echo "<pre>";
        // print_r($invoices);
        // die;
        $array = array();
        if(isset($invoices['data']) && !empty($invoices['data'])){
            foreach($invoices['data'] as $invoice){
                
                $interval = $invoice['lines']['data'][0]['plan']['interval'];

                if($interval == '1 year' || $interval == 'year'){
                    $amount_paid = $invoice['amount_paid']/100*12;
                }else{
                    $amount_paid =  $invoice['amount_paid']/100;
                }
                
                $discounted_amount = 0.00;
                if(isset($invoice['lines']['data'][0]['discount_amounts']) && !empty($invoice['lines']['data'][0]['discount_amounts'])){
                    $discounted_amount = number_format(($invoice['lines']['data'][0]['discount_amounts'][0]['amount']/100),2);
                }
                $array[] = [
                    'date' => date('Y-m-d H:i A',$invoice['created']),
                    'duration' =>date('M d,Y',$invoice['lines']['data'][0]['period']['start']).' - '.date('M d,Y',$invoice['lines']['data'][0]['period']['end']),
                    'description' =>$invoice['lines']['data'][0]['description'],
                    'invoice_id'=>$invoice['number'],
                    'amount'=>'$'.number_format(($invoice['lines']['data'][0]['amount']/100),2),
                    'status'=>$invoice['status'],
                    'discount'=>'$'.$discounted_amount,
                    'amount_paid'=>'$'.number_format($amount_paid,2)
                ];
            }
        }
        return collect($array);
    }
}