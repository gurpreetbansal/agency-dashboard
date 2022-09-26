<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model {

    protected $table = 'user_packages';

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
    protected $fillable = ['user_id', 'package_id', 'projects', 'keywords', 'flag', 'trial_days','package_purchase','price','subscription_type'];

    public function package() {
        return $this->belongsTo('App\Package', 'package_id', 'id');
    }
    
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
