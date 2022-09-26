<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleAccountViewData extends Model {

    protected $table = 'google_account_view_data';

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
    protected $fillable = ['user_id', 'request_id', 'google_account_id', 'category_name', 'category_id', 'parent_id','created_at', 'updated_at'];


	

}
