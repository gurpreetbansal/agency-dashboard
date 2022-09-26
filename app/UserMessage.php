<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMessage extends Model {

    protected $table = "user_messages";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'message', 'banner','status'
    ];

}
