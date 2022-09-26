<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {

    protected $table = 'subscriptions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'name', 'coupon_id','stripe_id','payment_id','payment_intent_id','charge_id', 'stripe_plan', 'quantity', 'amount', 'trial_ends_at', 'ends_at', 'stripe_status', 'customer_id','subscription_interval','current_period_start','current_period_end','subscription_created_date','response','refunded_amount','left_amount','canceled_at','cancel_response','coupon','coupon_name','discount','next_invoice_on','invoice_link_expiration','reminder_on'
    ];
    
    public function userDetail(){
        return $this->belongsTo('App\User','user_id','id');
    }
    
    
     public function packagePurchased(){
        return $this->belongsTo('App\User','stripe_plan','id');
    }

    public function invoices(){
        return $this->hasMany('App\Invoice','subscription_master_id','id')->where('invoice_id','!=',NULL);
    }

     public static function display_amount($subscription_id){
        $amount_to_be_charged = 0.00; /*75 is the base currency rate charged*/

        $subscription_data = Subscription::where('id',$subscription_id)->first();

        if($subscription_data->subscription_interval == '1 year'){
            $amount_to_be_charged = $subscription_data->amount/12;
        }else{
            $amount_to_be_charged = $subscription_data->amount;
        }

        return $amount_to_be_charged;
    }
}