<?php

namespace App\Social;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model {
	
	protected $fillable = [
		'user_id', 'access_token', 'oauth_uid', 'oauth_provider', 'name', 'first_name', 'last_name', 'email', 'status'
	];

}