<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {

    protected $table = 'invoices';

    protected $primaryKey = 'id';

    protected $fillable = [
        'subscription_master_id', 'invoice_id', 'subscription_id', 'invoice_number', 'customer_id', 'billing_email', 'currency', 'invoice_status', 'invoice_created_date','invoice_finalized_date','response','hosted_invoice_url','current_period_start','current_period_end','amount_paid', 'amount_due', 'amount_remaining','invoice_type','invoice_interval' 
    ];

     public function invoices_item(){
        return $this->hasOne('App\InvoiceItem','invoice_master_id','id');
    }
    
    public function subscription(){
        return $this->hasOne('App\Subscription','id','subscription_master_id');
    }
    
    public static function generate_invoice_number($count,$string){
		$lastid = $count+1;
		$id = str_pad($lastid, 4, 0, STR_PAD_LEFT);
		$number = $string.'-'.$id;
		return $number;
	}
}