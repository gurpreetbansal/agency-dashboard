<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiBalance extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'api_balances';

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
    protected $fillable = [
		'name','balance','email_sent','email_sent_on','status_code','status_message'
	];

}
