<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCredit extends Model {

	protected $table = "user_credits";
	
	protected $fillable = [
		'user_id', 'package_credit', 'used_credit', 'additional_credit'
	];
}
