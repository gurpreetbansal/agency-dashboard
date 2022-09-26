<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeywordPosition extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'keyword_positions';

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
    protected $fillable = [
	'request_id','keyword_id','position','position_type','is_sync','status'
	];
	
	
	public static function getLastUpdateKeyword($request_id){
		$result = KeywordPosition::select('id','updated_at')->where('request_id',$request_id)->orderBy('updated_at','desc')->limit(1)->first();
		if(!empty($result)) {
			return $result->updated_at;
		} else {
			return null;
		}
	}
	
	
	public static function lastestKeywordPosition($requestId,$keywordId){
		$result = KeywordPosition::select('position_type','position','request_id','keyword_id')->where('request_id',$requestId)->where('keyword_id',$keywordId)->orderBy('id','desc')->limit(1)->first();
		return $result; 
	}
	
	public static function oneDayKeyword($requestId,$keywordId){
		$result = KeywordPosition::select('position','request_id','keyword_id')->where('request_id',$requestId)->where('keyword_id',$keywordId)->whereDate('created_at',date('Y-m-d', strtotime("-1 day")))->orderBy('created_at','desc')->limit(1)->first();
		// echo "<pre>";
		// print_r($result);
		// die;
		return $result; 
	}
	
	public static function weeklyKeywords($requestId,$keywordId){
		$result = KeywordPosition::select('position','request_id','keyword_id')->where('request_id',$requestId)->where('keyword_id',$keywordId)->whereDate('created_at',date('Y-m-d', strtotime("-7 days")))->orderBy('created_at','desc')->limit(1)->first();
		return $result; 
	}
	
	
	/*fourthKeywords function*/
	public static function monthlyKeywords($requestId,$keywordId){
		$result = KeywordPosition::select('position','request_id','keyword_id')->where('request_id',$requestId)->where('keyword_id',$keywordId)->whereDate('created_at',date('Y-m-d', strtotime("-30 days")))->orderBy('created_at','desc')->limit(1)->first();
		return $result; 
	}

	public static function calculate_time_span($post)
	{  
		$seconds = time() - strtotime($post);
		$year = floor($seconds /31556926);
		$months = floor($seconds /2629743);
		$week=floor($seconds /604800);
		$day = floor($seconds /86400); 
		$hours = floor($seconds / 3600);
		$mins = floor(($seconds - ($hours*3600)) / 60); 
		$secs = floor($seconds % 60);
		if($seconds < 60) $time = $secs." seconds ago";
		else if($seconds < 3600 ) $time =($mins==1)?"now":$mins." mins ago";
		else if($seconds < 86400) $time = ($hours==1)?$hours." hour ago":$hours." hours ago";
		else if($seconds < 604800) $time = ($day==1)?$day." day ago":$day." days ago";
		else if($seconds < 2629743) $time = ($week==1)?$week." week ago":$week." weeks ago";
		else if($seconds < 31556926) $time =($months==1)? $months." month ago":$months." months ago";
		else $time = ($year==1)? $year." year ago":$year." years ago";
		return $time; 
	}  


	public function campaign_data(){
		return $this->hasOne('App\SemrushUserAccount','id','request_id');
	}

	public function keyword_search(){
		return $this->hasOne('App\KeywordSearch','id','request_id');
	}
	
}
