<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleReportHistory extends Model {

    protected $table = 'schedule_report_history';

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

    protected $fillable = ['report_id','email','sent_on'];

    public static function calculateTime($time)
    {  
        $seconds = time() - strtotime($time);
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

    public function ScheduledReportData(){
        return $this->belongsTo('App\ScheduleReport','report_id','id');
    }

}