<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;
use App\KeywordLocationList;
use App\KeywordPosition;

class KeywordSearch extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'keyword_searches';

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
	'user_id','request_id','start_ranking','oneday_position','one_week_ranking','monthly_ranking','life_ranking','keyword','cmp','sv','se_results_count','url_site','task_id','position','result_se_check_url','result_url','result_title','result_snippet','tracking_option','host_url','language','region','canonical','is_favorite','is_sync','is_flag','lat','long','cron_date','keyword_tag_id','url_type','ignore_local_listing'

	];
	
	
	public static function getKeywordsData($requested_ids){
		$data = KeywordSearch::whereIn('id', $requested_ids)->get();
		return $data;
	}
	
	
	public static function updateKeywordsData($requested_ids){
		$data =  KeywordSearch::whereIn('id',$requested_ids)->update([
			'is_sync'=>'0'
		]);
		return $data;
	}

    public static function searchKeywordCount($request_id){
        $result = KeywordSearch::where('request_id',$request_id)
        ->select(DB::raw('COUNT(id) as totalsearchkeyword'))
        ->first();

        if(!empty($result)){
            return $result->totalsearchkeyword;
        } else {
            return '0';
        }        
    }


    public static function keywordsLeft($user_id){
        $user_package = User::get_user_package($user_id); 
        $used_keywords = KeywordSearch::where('user_id',$user_id)->count();
        $keywords_left = $user_package->keywords - $used_keywords;
        return $keywords_left;
    }


    public static function get_flag_data($region){
        $region_explode  = explode('.',$region);
        $get_value = end($region_explode);
        $flagData = '';
        if(!empty($get_value)){
            $flagData = url('/').'/public/flags/'.$get_value.'.png';
        } 

        if(!empty($get_value == 'com')){
            $flagData = url('/').'/public/flags/us.png';
        }

        return $flagData;
    }

    public static function get_position_type($request_id,$keyword_id){
        $data = KeywordPosition::where('request_id',$request_id)->where('keyword_id',$keyword_id)->orderBy('id','desc')->first();
        if(isset($data->position_type)){
            $position_type = $data->position_type;
        }else{
            $position_type = 0;
        }
        return $position_type;
    }

    public static function getDataByKeyword($data){
        $keywords = trim($data['keyword_field']);
        $keywords = strtolower(preg_replace('~[\r\n\t]+~', ',', $keywords));
        $str_array =    explode(',', $keywords);
        $finalstring =  array_map('trim', $str_array);
        
        $result = KeywordSearch::
        select('keyword')
        ->where('request_id',$data['campaign_id'])
        ->whereIn('keyword',$finalstring)
        ->where('region',trim($data['search_engine_region']))
        ->where('tracking_option',trim($data['tracking_options']))
        ->where('canonical',$data['locations'])
        ->orderBy('id','desc')
        ->get()->toArray();
        
        
        
        if(!empty($result)){
            $results = array_column($result, 'keyword');
            return  $results;
        } else{
            return false;
        }
    }

   public function users(){
        return $this->hasOne('App\User','id','user_id');
    } 

    public function SemrushUserData(){
        return $this->belongsTo('App\SemrushUserAccount','request_id','id');
    }

    public static function check_keyword_count($user_id){
        $data =   KeywordSearch::
        where('user_id',$user_id)
            ->whereHas('SemrushUserData', function($query) use ($user_id){
                $query->where('status', 0)
                ->where('user_id',$user_id);
            })
       
        ->count();
        return $data;
    }

    /*May 12*/
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

    public function get_project_name($request_id){
        $data = SemrushUserAccount::where('id',$request_id)->first();
        if(isset($data) && !empty($data)){
            return $data->domain_name;
        }else{
            return '';
        }
    }

    public function get_new_rank($keyword_id,$request_id){
        $data =  KeywordPosition::
        where('request_id',$request_id)
        ->where('keyword_id',$keyword_id)
        ->latest()
        ->first();
        if(isset($data) && !empty($data)){
            if(isset($data)){
                if($data->position >= 100 || $data->position == null){
                    return '>100';
                }else{
                    return $data->position;
                }
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }


    public function get_previous_rank($keyword_id,$request_id){
        $data =  KeywordPosition::
        where('request_id',$request_id)
        ->where('keyword_id',$keyword_id)
        ->limit(2)
        ->latest()
        ->get();
        if(isset($data) && !empty($data)){
            if(isset($data[1])){
                if($data[1]->position >= 100 || $data[1]->position == null){
                    return '>100';
                }else{
                    return $data[1]->position;
                }
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }


    public function get_rank_difference($keyword_id,$request_id){
        $currentPostion = KeywordPosition::select('position_type','position','request_id','keyword_id')->where('request_id',$request_id)->where('keyword_id',$keyword_id)->orderBy('id','desc')->limit(1)->first();
        $oneData  = KeywordPosition::select('position','request_id','keyword_id')->where('request_id',$request_id)->where('keyword_id',$keyword_id)->orderBy('created_at','desc')->skip(1)->take(1)->first();
        
        if((!empty($currentPostion->position) && $currentPostion->position <> null && $currentPostion->position > 0) && (!empty($oneData->position) && $oneData->position <> null && $oneData->position > 0)){
            $oneDay = (int) $oneData->position - (int) $currentPostion->position;   
        }elseif((!empty($oneData->position) && $oneData->position <> null && $oneData->position > 0) && (!empty($currentPostion->position) && $currentPostion->position == null || $currentPostion->position == 0)){
            $oneDay = (int) $oneData->position - 100;
        }else{
            $oneDay = 0;    
        }

        return $oneDay;
    }


    public static function updateKeywordLocationLatLong($keyword_id,$canonical){
        $location = KeywordLocationList::getLatLong($canonical);
        $latLong = explode(',', $location);
        

        $update = KeywordSearch::whereIn('id',$keyword_id)->update([
            'lat'=>$latLong[0],
            'long'=>$latLong[1]
        ]);
        if($update){
            return $location;
        }
    }

     public static function live_keyword_chart_data($request_id,$keyword_id,$duration){
        $data =  array();  
        $keyword_data = KeywordPosition::select('created_at')->where('request_id',$request_id)->where('keyword_id',$keyword_id)->orderBy('id','asc')->first();
        $lastDate = date('Y-m-d', strtotime($duration));
        $keywordPosition = KeywordPosition::where('request_id',$request_id)->where('keyword_id',$keyword_id)->whereDate('created_at','<=',date('Y-m-d'))->whereDate('created_at','>=',$lastDate)->orderBy('id','asc')->get();
        
        foreach($keywordPosition as $record) {
            $values =  (int) $record->position <> '0' && $record->position <> null ? (int) $record->position : null ; 
            $data[] = array('t'=>strtotime($record->created_at)*1000,'y'=>$values);
        }
        return array('keyword'=>$data);
    }


    public static function update_notification_status($request_id){
        SemrushUserAccount::where('id',$request_id)->update(['notification_flag'=>0]);
    }

    public static function getFilteredUrl($domainUrl){
        $domain_url  = '';
        $url_info = parse_url($domainUrl);
        if (!empty($url_info) && isset($url_info['host']) && isset($url_info['scheme'])) {
            $domain_url = $url_info['scheme'].'://'.$url_info['host'].'/';
        } elseif (!empty($url_info) && isset($url_info['host']) && !isset($url_info['scheme'])) {
            $domain_url = $url_info['host'].'/';
        }else{
            $domain_url = $url_info['path'];
        }
        return $domain_url;
    }

}