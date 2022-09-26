<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PasswordReset extends Model {

    use Notifiable;

    protected $table = 'password_resets';
    protected $fillable = [
        'email', 'token'
    ];
    const UPDATED_AT = null;

}
