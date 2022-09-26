<?php

namespace App\Social;

use Illuminate\Database\Eloquent\Model;

class FacebookUserPage extends Model {
	
	protected $fillable = [
		'fbid', 'page_id', 'page_name', 'page_token', 'page_image'
	];

}
