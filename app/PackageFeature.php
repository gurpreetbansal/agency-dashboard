<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'package_features';

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
    protected $fillable = ['package_id', 'feature'];


   

}
