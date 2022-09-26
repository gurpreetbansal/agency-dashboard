<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEmail extends Model {

    protected $table = "user_emails";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'subject', 'message', 'email_to', 'status'
    ];

}
