<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleGoalCompletion extends Model {

    protected $table = 'google_goal_completion';

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
    protected $fillable = ['user_id', 'view_id', 'request_id', 'goal_count'];


}
