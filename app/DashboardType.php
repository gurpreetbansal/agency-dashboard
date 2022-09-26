<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DashboardType extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dashboard_types';

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
    protected $fillable = ['name,status,order_status'];

    function child()
    {
        return $this->belongsTo('App\DashboardType','id','parent_id');
    }

}
