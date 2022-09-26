<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignSetting extends Model {


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'campaign_settings';

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

    protected $fillable = ['user_id','request_id','client_alerts','client_emails','manager_alerts','manager_email','next_date','status'];

    public function UserInfo(){
        return $this->hasOne('App\User','id','user_id');
    }

    public function SemrushUserData(){
        return $this->belongsTo('App\SemrushUserAccount','request_id','id');
    }

    public function KeywordAlertData(){
        return $this->belongsTo('App\KeywordAlert','id','campaign_setting_id');
    }
}