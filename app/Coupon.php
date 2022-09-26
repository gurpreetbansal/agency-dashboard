<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coupons';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['code','coupon_code_id','value','type'];

}
