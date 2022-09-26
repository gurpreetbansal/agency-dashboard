<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSystemSetting extends Model {

    protected $table = "user_system_settings";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'email_deliver_from', 'email_reply_to'
    ];

    public function userInfo(){
        return $this->belongsTo('App\User','id','user_id');
    }

}
