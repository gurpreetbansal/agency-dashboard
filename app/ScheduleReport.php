<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ApiBalance;

use App\Exports\ExportReportKeywords;
use Maatwebsite\Excel\Facades\Excel;
use App\SemrushUserAccount;
use App\ProfileInfo;
use URL;

use App\ScheduleReportHistory;

class ScheduleReport extends Model {

    protected $table = 'schedule_reports';

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

    protected $appends = array('project_name','report_format','last_delivered','frequency');


    protected $fillable = ['user_id','email','request_id','subject','mail_text','rotation','day','report_type','format','last_delivery','next_delivery','status','sent_status','deleted_at'];

    public function SemrushUserData(){
        return $this->belongsTo('App\SemrushUserAccount','request_id','id');
    }

    public function UserInfo(){
        return $this->hasOne('App\User','id','user_id');
    }

    public function getProjectNameAttribute(){
        $data = SemrushUserAccount::select('id','domain_name','host_url','clientName')->where('id',$this->request_id)->first();
        return $data;
    }

    public function getReportFormatAttribute(){
        if($this->format === 1){
            return 'PDF';
        }elseif($this->format === 2){
            return 'CSV';
        }
    }


    public function getLastDeliveredAttribute(){
        if($this->last_delivery <> null){
            return KeywordPosition::calculate_time_span($this->last_delivery);
        }else{
            return 'Never';
        }
    }

    public function getFrequencyAttribute(){
        $rotation = $this->rotation;
        $day = $this->day;
        $text = $day_string = ''; $days = array();
       
        if($day !== null && !empty($day) && $day !== ''){
            $explode_day = explode(',',$day);
            for($i=0;$i<count($explode_day);$i++){
                if($explode_day[$i] < 10){
                    $explode_day[$i] = ltrim($explode_day[$i], '0');
                }
                $days[] = $this->addOrdinalNumberSuffix($explode_day[$i]);
            } 
            $day_string = implode(',',$days);
        }

        if($day_string <> '' && $rotation <> null){
             $text .= 'Every '.$rotation.' and on the '.$day_string.' of each month';
        }elseif($day_string <> '' && $rotation == null){
            $text .= 'Every '.$day_string.' of each month';
        }elseif($day_string == '' && $rotation <> null){
            $text .= 'Every '.$rotation ;
        }
              
        return $text;
    }


    private function addOrdinalNumberSuffix($num) {
        if (!in_array(($num % 100),array(11,12,13))){
            switch ($num % 10) {
                case 1:  return $num.'st';
                case 2:  return $num.'nd';
                case 3:  return $num.'rd';
            }
        }
        return $num.'th';
    }

    public static function downloadPdf($key,$filename,$type,$folder_name = 'report_downloads')
    {
        // $base_url = \config('app.base_url');
        $profile_data = null;
        $final ='2,-1'; $pages = array();
        for($i=2;$i<=50;$i++){
            $pages[] = $i;
        }
        $base_url = \config('app.base_url');

        $final = implode(', ',$pages);

        $encription = base64_decode($key);
        $encrypted_id = explode('-|-',$encription);
        $campaign_id = $encrypted_id[0];
        $user_id = $encrypted_id[1];

        $data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
        $profile_data = ProfileInfo::where('request_id',$campaign_id)->first();

        file_put_contents(dirname(__FILE__).'/logs/report_dwnls.txt',print_r($profile_data,true));

        if(!empty($profile_data) && $profile_data->white_label_branding == 1){
            $footer_logo = ScheduleReport::agency_logo($user_id,$campaign_id,$profile_data->agency_logo);
            $footer_name = ($profile_data->company_name)?$profile_data->company_name:'AgencyDashboard.io';
        }else{
            $footer_logo = 'https://agencydashboard.io/public/front/img/logo-img.png';
            $footer_name = 'AgencyDashboard.io';
        }

        try {
            $client = new \Pdfcrowd\HtmlToPdfClient("agencydashboard", "5284d0142c46f66189276e801303c514");
            $client->setPageSize("A4");
            $client->setOrientation("portrait");
            $client->setNoMargins(true);          
            $client->setHeaderHtml("<div id='first-header' class='extra-text' style='position: fixed; left: 0; top: 0; width: 100%; height: 48px;''><div style='background: url(".$base_url."public/viewkey/images/first-page.png); background-size: 100%; background-position: top left; background-repeat: no-repeat; width: 100%; height: 100%;'></div></div><div id='last-header' class='extra-text'></div>");
            $client->setHeaderHeight("0.5in");
                     
            $font_family = 'Montserrat, sans-serif';

            $client->setFooterHtml('<div style="position:absolute; left:0; right:0; top:0; bottom:0; font-family: '.$font_family.'; color: #5d5d5d; font-size: 10px; background:url('.$base_url.'public/viewkey/images/strip-curve.png), url('.$base_url.'public/viewkey/images/right-strip.png); background-repeat:no-repeat; background-position:-371px 0, 616px 0px; background-size:100%, 100%;  padding:0 30px;  box-sizing:border-box;"> <span style="width:33%;display:inline-block; vertical-align:middle; text-align:left;">The report data is provided by <span style="color:#327aee;">'.$footer_name.'</span></span><span style="width:33%;display:inline-block; vertical-align:middle; text-align:center;">Generated on:  '. date('M d,Y') .' </span> <span style="width:33%;display:inline-block; vertical-align:middle;height:0.3in; padding: 0.05in 0"><img align="right" src="'.$footer_logo.'" style="max-width: 100%; max-height: 100%;" /></span></div>');

            $client->setFooterHeight("0.4in");
            $client->setExcludeHeaderOnPages($final);
            $client->setHeaderFooterCssAnnotation(true);
            
            $url = \config('app.base_url')."download/".$type."/".$key;
        
            $pdf = $client->convertUrl($url);

            if (!file_exists(\config('app.FILE_PATH').'public/'.$folder_name.'/')) {
                mkdir(\config('app.FILE_PATH').'public/'.$folder_name.'/', 0777, true);
            }
            file_put_contents(\config('app.FILE_PATH').'public/'.$folder_name.'/'.$filename,print_r($pdf,true));

            $remainingValue = $client->getRemainingCreditCount();
            ApiBalance::where('name','pdfcrowd')->update(['balance'=>$remainingValue]);

        }
        catch(\Pdfcrowd\Error $why) {
            return response($why->getMessage(), $why->getCode())
            ->header('Content-Type', 'text/plain');
        }
    }

    public static function agency_logo($user_id,$request_id,$image_name){
        $image_url   =   '';
        if (file_exists(\config('app.FILE_PATH').'public/storage/agency_logo/'.$user_id.'/'.$request_id)) {
            $path  = 'public/storage/agency_logo/'.$user_id.'/'.$request_id.'/';
            if(file_exists($path)){
                $image_url = URL::asset('public/storage/agency_logo/'.$user_id.'/'.$request_id.'/'.$image_name);

            }
        }
        return $image_url;   
    }

    public static function downloadCsv($request_id,$filename,$folder_name = 'report_downloads')
    {
        $file_path = \config('app.FILE_PATH').'public/'.$folder_name.'/';
        if (ob_get_contents())
        ob_end_clean(); 
        ob_start(); 
        Excel::store(new ExportReportKeywords($request_id), $filename, 'store_csv');
    }

    public static function calculateDate_bkp($report_id){

        $weekArr = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];

        $startDate = $report_id->last_delivery == null ? $report_id->created_at : $report_id->last_delivery;

        /* Week Days */
        if($report_id->rotation <> null & $report_id->rotation <> ''){
            $sechduleDays = explode(',',$report_id->rotation);
            $dayName = date('D',strtotime($startDate));
            $oldArr = array();
            foreach($weekArr as $key =>$value){
                $oldArr[$key] = $value;
                if($value == $dayName){
                  break;
                }
            }

            $daysRemains = array_diff($weekArr,$oldArr);  //days selected
            
            $result=array_intersect($daysRemains,$sechduleDays);  //days selected

            if(count($result) >= 1){
                $counter = 1;
                foreach($result as $keyFinal =>$valueFinal){
                    $nextDay = $valueFinal; 
                    break;
                }
                $upcomingDay = date("Y-m-d",strtotime($nextDay.' this week'));
            }else{
                $nextDay = $sechduleDays[0]; 
                $upcomingDay = date("Y-m-d",strtotime($nextDay.' next week'));
            }
        }else{
            $upcomingDay = ''; 
        }


        /* month date filter */
        if($report_id->day <> null & $report_id->day <> ''){
            $sechduleDates = explode(',',$report_id->day);
            $lastDate = date('d',strtotime($startDate));
         
            $newNumbers = array_filter(
                $sechduleDates,
                function ($value) use($lastDate) {
                    return ($value > $lastDate);
                }
            );
           
            if(count($newNumbers) >= 1){
                foreach($newNumbers as $keyDateFinal =>$valueDateFinal){
                    $nextDate = $valueDateFinal;
                    break;
                }
                $nextFinalDate = date('Y-m-'.$nextDate); 
            }else{
                $nextDate = $sechduleDates[0];
                $nextFinalDate = date('Y-m-'.$nextDate,strtotime('+1 months')); 
            } 
        }else{
            $nextFinalDate = '';
        }

        if($nextFinalDate == '' && $upcomingDay <> '' ){
            $nextReport = $upcomingDay;
        }elseif($nextFinalDate <> '' && $upcomingDay == '' ){
            $nextReport = $nextFinalDate;
        }elseif($nextFinalDate >= $upcomingDay){
            $nextReport = $upcomingDay;
        }elseif($nextFinalDate <= $upcomingDay){
            $nextReport = $nextFinalDate;
        }else{
            $nextReport = null;
        }
       
        return $nextReport;
    }
    public static function calculateDates($report_id){

        $weekArr = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];

        $startDate = $report_id->last_delivery == null ? $report_id->created_at : $report_id->next_delivery;

        /* Week Days */
        if($report_id->rotation <> null & $report_id->rotation <> ''){
            $sechduleDays = explode(',',$report_id->rotation);
            $dayName = date('D',strtotime($startDate));
            $oldArr = array();
            foreach($weekArr as $key =>$value){
                $oldArr[$key] = $value;
                if($value == $dayName){
                  break;
                }
            }

            $daysRemains = array_diff($weekArr,$oldArr);  //days selected
            
            $result=array_intersect($daysRemains,$sechduleDays);  //days selected

            if(count($result) >= 1){
                $counter = 1;
                foreach($result as $keyFinal =>$valueFinal){
                    $nextDay = $valueFinal; 
                    break;
                }
                $upcomingDay = date("Y-m-d",strtotime($nextDay.' this week'));
            }else{
                $nextDay = $sechduleDays[0]; 
                $upcomingDay = date("Y-m-d",strtotime($nextDay.' next week'));
            }
        }else{
            $upcomingDay = ''; 
        }


        /* month date filter */
        if($report_id->day <> null & $report_id->day <> ''){
            $sechduleDates = explode(',',$report_id->day);
            $lastDate = date('d',strtotime($startDate));
         
            $newNumbers = array_filter(
                $sechduleDates,
                function ($value) use($lastDate) {
                    return ($value > $lastDate);
                }
            );
           
            if(count($newNumbers) >= 1){
                foreach($newNumbers as $keyDateFinal =>$valueDateFinal){
                    $nextDate = $valueDateFinal;
                    break;
                }
                $nextFinalDate = date('Y-m-'.$nextDate); 
            }else{
                $nextDate = $sechduleDates[0];
                $nextFinalDate = date('Y-m-'.$nextDate,strtotime('+1 months')); 
            } 
        }else{
            $nextFinalDate = '';
        }

        if($nextFinalDate == '' && $upcomingDay <> '' ){
            $nextReport = $upcomingDay;
        }elseif($nextFinalDate <> '' && $upcomingDay == '' ){
            $nextReport = $nextFinalDate;
        }elseif($nextFinalDate >= $upcomingDay){
            $nextReport = $upcomingDay;
        }elseif($nextFinalDate <= $upcomingDay){
            $nextReport = $nextFinalDate;
        }else{
            $nextReport = null;
        }
       
        return $nextReport;
    }

    public static function remove_archived_campaign_report($request_id){
        $report_id = ScheduleReport::where('request_id',$request_id)->first();
        ScheduleReportHistory::where('report_id',$report_id)->delete();
        ScheduleReport::where('request_id',$request_id)->delete();
    }


    public static function remove_archived_campaigns_report($request_ids){
        $report_ids = ScheduleReport::whereIn('request_id',$request_ids)->pluck('id');
        ScheduleReportHistory::whereIn('report_id',$report_ids)->delete();
        ScheduleReport::whereIn('request_id',$request_ids)->delete();
    }


     public static function calculateDate($report_id){

       // $weekArr = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];

        $startDate = $report_id->last_delivery == null ? $report_id->created_at : $report_id->last_delivery;
        $weekArr = [];
        $dateDay = strtotime($startDate) - 86400;
        for ($i=0; $i <7; $i++) {
            if($i !== 0){
                $dateDay  += 86400;
            }
           
           $weekArr[] = date('D',$dateDay); 
        }
       
        /* Week Days */
        if($report_id->rotation <> null && $report_id->rotation <> ''){
            $sechduleDays = explode(',',$report_id->rotation);
            $dayName = date('D',strtotime($startDate));
            $oldArr = array();
            foreach($weekArr as $key =>$value){
                $oldArr[$key] = $value;
                if($value == $dayName){
                  break;
                }
            }

            $daysRemains = array_diff($weekArr,$oldArr);  //days selected
            
            $result=array_intersect($daysRemains,$sechduleDays);  //days selected


            if(count($result) >= 1){
                $counter = 1;
                foreach($result as $keyFinal =>$valueFinal){
                    $nextDay = $valueFinal; 
                    break;
                }
                $upcomingDay = date("Y-m-d",strtotime($nextDay.' this week'));
                if(strtotime($upcomingDay) <= strtotime(now())){
                    $upcomingDay = date("Y-m-d",strtotime($nextDay.' next week'));
                }
            }else{
                $nextDay = $sechduleDays[0]; 
                $upcomingDay = date("Y-m-d",strtotime($nextDay.' next week'));
            }
        }else{
            $upcomingDay = ''; 
        }


        /* month date filter */
        if($report_id->day <> null && $report_id->day <> ''){
            $sechduleDates = explode(',',$report_id->day);
            $lastDate = date('d',strtotime($startDate));
         
            $newNumbers = array_filter(
                $sechduleDates,
                function ($value) use($lastDate) {
                    return ($value > $lastDate);
                }
            );
           
            if(count($newNumbers) >= 1){
                foreach($newNumbers as $keyDateFinal =>$valueDateFinal){
                    $nextDate = $valueDateFinal;
                    break;
                }
                $nextFinalDate = date('Y-m-'.$nextDate); 
            }else{
                $nextDate = $sechduleDates[0];
                $nextFinalDate = date('Y-m-'.$nextDate,strtotime('+1 months')); 
            } 
        }else{
            $nextFinalDate = '';
        }

        if($nextFinalDate == '' && $upcomingDay <> '' ){
            $nextReport = $upcomingDay;
        }elseif($nextFinalDate <> '' && $upcomingDay == '' ){
            $nextReport = $nextFinalDate;
        }elseif(strtotime($nextFinalDate) >= strtotime($upcomingDay)){
            $nextReport = $upcomingDay;
        }elseif(strtotime($nextFinalDate) <= strtotime($upcomingDay)){
            $nextReport = $nextFinalDate;
        }else{
            $nextReport = null;
        }
       
        return $nextReport;
    }
    
}