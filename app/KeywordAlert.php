<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CampaignSetting;

class KeywordAlert extends Model {


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'keyword_alerts';

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

    protected $fillable = ['user_id','request_id','campaign_setting_id','sent_status','sent_at'];

    public function UserInfo(){
        return $this->hasOne('App\User','id','user_id');
    }

    public function SemrushUserData(){
        return $this->belongsTo('App\SemrushUserAccount','request_id','id');
    }

    public static function update_keyword_alert_status($request_id,$user_id){
        $alerts = CampaignSetting::where('request_id',$request_id)->first();
        if(!empty($alerts) || $alerts <> null){
            $checkKeyAlrt = KeywordAlert::where('request_id',$request_id)->whereDate('sent_at',date('Y-m-d'))->first();
            if($checkKeyAlrt == null){
                $check = KeywordSearch::where('request_id',$request_id)->whereDate('updated_at','<',date('Y-m-d'))->count();
                if($check == 0 && !empty($alerts)){
                    KeywordAlert::updateOrCreate(
                        ['request_id'=>$request_id,'user_id'=>$user_id],
                        [
                            'request_id'=>$request_id,
                            'campaign_setting_id'=>$alerts->id,
                            'user_id'=>$user_id,
                            'sent_status'=>0
                        ]
                    );
                }
            }
        }
    }
    
    /*public static function update_keyword_alert_status($request_id,$user_id){
        $alerts = CampaignSetting::where('request_id',$request_id)->first();
        if(!empty($alerts) || $alerts <> null){
         //   $check = KeywordSearch::where('request_id',$request_id)->whereDate('updated_at','<',date('Y-m-d'))->count();
            $check = KeywordSearch::where('request_id',$request_id)->whereDate('cron_date','<',date('Y-m-d'))->count();
            if($check == 0 && !empty($alerts)){
                KeywordAlert::updateOrCreate(
                    ['request_id'=>$request_id,'user_id'=>$user_id],
                    [
                        'request_id'=>$request_id,
                        'campaign_setting_id'=>$alerts->id,
                        'user_id'=>$user_id,
                        'sent_status'=>0
                    ]
                );
            }
        }
    }*/
}