<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SharedAccess extends Model {

    protected $table = 'shared_access';

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
    protected $fillable = ['user_id','name','email','password','image','restrictions','access'];

}
