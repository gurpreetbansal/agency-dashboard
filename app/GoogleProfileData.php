<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleProfileData extends Model {

    protected $table = 'google_profile_data';

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
    protected $fillable = ['user_id', 'view_id', 'request_id', 'keywords', 'sessions', 'new_session', 'new_users', 'bounse_rate', 'page_sessions', 'avg_session', 'goal_conversions', 'goal_completions', 'goal_value'];


}
