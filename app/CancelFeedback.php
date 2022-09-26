<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CancelFeedback extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cancel_feedbacks';

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
    protected $fillable = ['user_id','subscription_id','sub_id','overall_rating','recommend','description'];

    public function user_info(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }

}