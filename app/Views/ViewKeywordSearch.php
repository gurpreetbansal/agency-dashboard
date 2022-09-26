<?php

namespace App\Views;

use Illuminate\Database\Eloquent\Model;
use App\KeywordPosition;
use App\SemrushUserAccount;

class ViewKeywordSearch extends Model
{
    public $table = "view_keyword_searches";

    protected $appends = array('regional_flag','position_type','c_position','project_name','calculated_time');

    public function SemrushUserData(){
        return $this->belongsTo('App\SemrushUserAccount','request_id','id');
    }

    public function getRegionalFlagAttribute()
    {
        $region = $this->region;
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


    public function getPositionTypeAttribute(){
        $keyword_id = $this->id;
        $request_id = $this->request_id;
        $data = KeywordPosition::where('request_id',$request_id)->where('keyword_id',$keyword_id)->orderBy('id','desc')->first();
        if(isset($data->position_type)){
            $position_type = $data->position_type;
        }else{
            $position_type = 0;
        }
        return $position_type;
    }


    public function getCPositionAttribute(){
        $pages = '>10';
        if($this->currentPosition  < 100){
           $pageNo = $this->currentPosition/10;   
            if($pageNo <= 1){
                $pages = 1;
            }elseif($pageNo <= 2){
                $pages = 2;
            }elseif($pageNo <= 3){
                $pages = 3;
            }elseif($pageNo <= 4){
                $pages = 4;
            }elseif($pageNo <= 5){
                $pages = 5;
            }elseif($pageNo <= 6){
                $pages = 6;
            }elseif($pageNo <= 7){
                $pages = 7;
            }elseif($pageNo <= 8){
                $pages = 8;
            }elseif($pageNo <= 9){
                $pages = 9;
            }elseif($pageNo <= 10){
                $pages = 10;
            }else{
                $pages = '>10';
            }
        } 
        return $pages;
    }


    public  function getCalculatedTimeAttribute()
    {  
        $seconds = time() - strtotime($this->updated_at);
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


    public function getProjectNameAttribute(){
        $data = SemrushUserAccount::where('id',$this->request_id)->first();
        if(isset($data) && !empty($data)){
            return $data->domain_name;
        }else{
            return '';
        }
    }
}