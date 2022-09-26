<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model {

    protected $table = "user_addresses";
    protected $fillable = [
        'user_id', 'address_line_1', 'address_line_2', 'city', 'country', 'zip'
    ];
    
    public function Country(){
        return $this->hasOne('App\Country','id','country');
    }

}
