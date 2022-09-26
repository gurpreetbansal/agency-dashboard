<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model {

    protected $table = 'task_lists';

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
    protected $fillable = ['user_id', 'category_id', 'name', 'status'];

    protected $appends = array('total_count');

    public function getTotalCountAttribute()
    {
        return $this->hasMany('App\TaskActivity','activity_id','id')->count();
    }

    /*public function activityCount(){
        return $this->hasMany('App\TaskActivity','activity_id','id')->count();
    }*/    

}
