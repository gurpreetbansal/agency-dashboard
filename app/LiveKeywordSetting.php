<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class LiveKeywordSetting extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'live_keyword_settings';

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
    protected $fillable = ['request_id','heading','detail','viewkey','pdf'];
		
}