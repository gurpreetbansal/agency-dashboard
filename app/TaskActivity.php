<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskActivity extends Model {

    protected $table = 'task_activities';

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
    protected $fillable = ['user_id','campaign_id','category_id','activity_id','status','file_link','file_name','activity_date','time_taken','notes'];

    public function activityLists(){
        return $this->belongsTo('App\TaskList','activity_id','id');
    }

    public function categoriesLists(){
        return $this->belongsTo('App\TaskCategory','category_id','id');
    }

}
