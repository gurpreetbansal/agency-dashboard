<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionItem extends Model {

    protected $table = 'subscription_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'stripe_id', 'stripe_plan', 'quantity', 'subscription_id'
    ];

}
