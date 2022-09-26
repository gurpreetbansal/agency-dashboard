<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ModuleByDateRange extends Model {

    protected $table = 'module_by_daterange';
    
    protected $primaryKey = 'id';
    
    protected $fillable = ['user_id', 'request_id','duration', 'module','start_date','end_date','compare_start_date','compare_end_date','status','display_type'];
	
	
	
	public static function getModuleDateRange($request_id,$module){
		$result = ModuleByDateRange::where('request_id',$request_id)->where('module',$module)->first();
		return $result;
	}

	public static function calculate_weeks($start_date,$end_date){
		$date1 = new \DateTime($start_date);
		$date2 = new \DateTime($end_date);
		$difference_in_weeks = (float)($date1->diff($date2)->days /7);

		if((int)$difference_in_weeks < $difference_in_weeks){
			$data = (int)$difference_in_weeks + 1;
		}else{
			$data = $difference_in_weeks;
		}

		return  $data;
	}

	public static function getStartAndEndDate($week, $year) {
		  $dto = new \DateTime();
		  $dto->setISODate($year, $week);
		  $ret = $dto->format('Y-m-d');
		  return $ret;
	}

	public static function getSelectedDateForCharts($start_date,$end_date){
			$selected = 0;
			$analyticsStart  = date('Y-m-d',strtotime($start_date));
			$analyticsEnd  = date('Y-m-d',strtotime($end_date));
			
			$ts1Ana = strtotime($analyticsStart);
			$ts2Ana = strtotime($analyticsEnd);

			$year1Ana = date('Y', $ts1Ana);
			$year2Ana = date('Y', $ts2Ana);

			$month1Ana = date('m', $ts1Ana);
			$month2Ana = date('m', $ts2Ana);
			
			
			$day_diff  = strtotime($start_date) - strtotime($end_date);
			$count_days  = floor($day_diff/(60*60*24));
			if($count_days == '-7'){
				$selected  = 0.25;
			}else{
				$selected = (($year2Ana - $year1Ana) * 12) + ($month2Ana - $month1Ana);
			}

			return $selected;
		}

	public static function calculate_days($start_date,$end_date){
		$date1=date_create($start_date);
        $date2=date_create($end_date);
        $diff=date_diff($date1,$date2);
        $duration = $diff->format("%a");

		return  $duration;
	}

	public static function get_StartAndEndDate($week, $year) {
		$dto = new \DateTime();
		$dto->setISODate($year, $week);
		$ret['week_start'] = $dto->format('Y-m-d');
		$dto->modify('+6 days');
		$ret['week_end'] = $dto->format('Y-m-d');
		return $ret;
	}

	public static function calculate_ecom_days($start_date,$end_date){
		$date1=date_create($start_date);
		$date2=date_create($end_date);
		$diff=date_diff($date1,$date2);
		$duration = $diff->format("%a");

		return  ($duration+1);
	}

	public static function set_default_month_range($campaign_id,$user_id){
		ModuleByDateRange::updateOrCreate(
		    ['request_id' => $campaign_id,'user_id' => $user_id, 'module' => 'google_ads'],
		    [
		    	'duration' => 1,
		    	'module' => 'google_ads',
		    	'user_id' => $user_id,
		    	'request_id' => $campaign_id		    	
		    ]
		);
	}

}
