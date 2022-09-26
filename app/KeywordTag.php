<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeywordTag extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'keyword_tags';

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
	'request_id','keyword_id','tag','status'
	];

}