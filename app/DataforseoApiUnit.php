<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataforseoApiUnit extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dataforseo_api_units';

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
	'user_id','request_id','keyword','domain_name','api_name','api_credit','status'
	];

}
