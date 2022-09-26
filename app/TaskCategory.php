<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskCategory extends Model {

    protected $table = 'task_categories';

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
    protected $fillable = ['user_id', 'name', 'status'];


    public function lists(){
        $user_id  = [0,Auth()->user()->id];
        return $this->hasMany('App\TaskList','category_id','id')->whereIn('user_id',$user_id);
    }

}
