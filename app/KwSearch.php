<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KwSearch extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'kw_searches';

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
    protected $fillable = ['campaign_id','user_id','search_term','category','location_id','language_id','searched_on','status'];	
}