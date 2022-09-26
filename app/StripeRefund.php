<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeRefund extends Model {

    protected $table = 'stripe_refunds';

    protected $primaryKey = 'id';

    protected $fillable = [
        'subscription_id', 'refund_id','amount','charge_id','refund_created_on','response'
    ];

    public function subscription_detail(){
    	return  $this->belongsTo('App\Subscription','subscription_id','id');
    }
    
}