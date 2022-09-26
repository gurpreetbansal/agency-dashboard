<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activity_logs';

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
    protected $fillable = ['user_id','request_id','title','description','slug','deleted_at'];
	
	public static function trafficLog($campaignId,$usersPercent,$user_id){
		$if_exists = ActivityLog::where('request_id',$campaignId)->where('slug','traffic')->whereDate('created_at',date('Y-m-d'))->orderBy('id','desc')->get();
		
		if(count($if_exists) == 0){			
			if (substr(strval($usersPercent), 0, 1) == "-"){
			   $desc = '<b class="activity-red">'.abs($usersPercent).'%</b> decrease in traffic since yesterday';
			} else {
			   $desc = '<b class="activity-green">'.$usersPercent."%</b> increase in traffic since yesterday";
			}
			ActivityLog::create([
				'user_id'=>$user_id,
				'request_id'=>$campaignId,
				'title'=>'traffic',
				'description'=>$desc,
				'slug'=>'traffic'
			]);
		}
	}

	public static function keywordsLogTracked($user_id,$campaignId,$title,$desc,$slug){
			ActivityLog::create([
				'user_id'=>$user_id,
				'request_id'=>$campaignId,
				'title'=>$title,
				'description'=>$desc,
				'slug'=>$slug
			]);
	}

	public static function campaign_activity($campaign_id,$limit){
		$limits = $limit <> '' ? explode('-', $limit) : array('0','5');
		$results = ActivityLog::where('request_id',$campaign_id)->orderBy('created_at','desc')->skip($limits[0])->take($limits[1])->get();
		$start = (int) $limits[0] + 5;
		$end =  5;
	
		$newlimit =  $start.'-'.$end; 

		return array('data' => $results,'limit' => $newlimit);
	}

}
