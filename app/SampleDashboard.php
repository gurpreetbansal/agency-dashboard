<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SampleDashboard extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sample_dashboards';

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
    protected $fillable = ['request_id','status'];	
}
