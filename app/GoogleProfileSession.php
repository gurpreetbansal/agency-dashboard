<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class GoogleProfileSession extends Model {

    protected $table = 'google_profile_session';
    
    protected $primaryKey = 'id';
    
    protected $fillable = ['user_id', 'request_id', 'view_id','from_date','count_session'];
	
	
	public static function dateRangeSession($user_id,$request_id,$start_date,$end_date){
		
		$start_data     =   date('Ymd', strtotime($start_date));
		$end_data       =   date('Ymd', strtotime($end_date));
		
		$result = GoogleProfileSession::select('*',DB::raw('WEEK( from_date ) AS DWNum'),DB::raw('SUM( count_session ) AS total_session'))->where('user_id',$user_id)->where('request_id',$request_id)->whereBetween('from_date',[$start_data,$end_data])->orderBy('id','asc')->groupBy('DWNum')->get();
		
		return $result;
		
	}

}
