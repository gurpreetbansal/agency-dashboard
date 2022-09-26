<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Http\Request;
use App\SemrushUserAccount;
use App\User;
use App\GoogleAnalyticsUsers;
use App\ActivityLog;
use App\ModuleByDateRange;
use App\ProjectCompareGraph;
use App\Error;
use App\GoogleUpdate;
use App\GoogleErrors;
use Auth;
use Exception;

class GoogleAnalytics extends Command
{
/**
* The name and signature of the console command.
*
* @var string
*/
protected $signature = 'Google:Analytics';

/**
* The console command description.
*
* @var string
*/
protected $description = 'Store analytics data for particular campaign.';

/**
* Create a new command instance.
*
* @return void
*/
public function __construct()
{
    parent::__construct();
}

/**
* Execute the console command.
*
* @return int
*/

public function handle(){
    try{

     $getUser = SemrushUserAccount::
     whereHas('UserInfo', function($q){
        $q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
        ->where('subscription_status', 1);
    }) 

     ->whereNotNull('google_analytics_id')
     ->orderBy('id','desc')
     ->where('status',0)

     ->where(function($q){
        $q->whereRaw("exists (select * from `google_updates` where `semrush_users_account`.`id` = `google_updates`.`request_id` and (DATE(`analytics`) <> '".date('Y-m-d')."' or analytics IS NULL))  or not exists (select * from `google_updates` where `semrush_users_account`.`id` = `google_updates`.`request_id`)");


    })
     ->whereDoesntHave('GoogleErrors', function ($q) {
        $q->where('module',1)
        ->whereDate('updated_at',date('Y-m-d'));
    })
     ->get();
     file_put_contents(dirname(__FILE__).'/logs/GoogleAnalytics.txt',print_r($getUser,true));
     /*die;*/


     if(!empty($getUser)){

        $start_date = date('Y-m-d');
        $end_date =  date('Y-m-d', strtotime("-2 years", strtotime(date('Y-m-d'))));

        $day_diff  =    strtotime($end_date) - strtotime($start_date);
        $count_days     =   floor($day_diff/(60*60*24));

        $start_data   =   date('Y-m-d', strtotime("-2 years", strtotime($end_date)));
        $prev_start_date = date('Y-m-d', strtotime("-1 day", strtotime($end_date)));
        $prev_end_date = date('Y-m-d', strtotime("-2 years", strtotime($prev_start_date))); 

            //goal completion dates
        $today = date('Y-m-d');
        $one_month = date('Y-m-d',strtotime('-1 month'));
        $three_month = date('Y-m-d',strtotime('-3 month'));
        $six_month = date('Y-m-d',strtotime('-6 month'));
        $nine_month = date('Y-m-d',strtotime('-9 month'));
        $one_year = date('Y-m-d',strtotime('-1 year'));
        $two_year = date('Y-m-d', strtotime("-2 years"));

        $prev_start_one = date('Y-m-d', strtotime("-1 day", strtotime($one_month)));
        $prev_end_one = date('Y-m-d', strtotime("-1 month", strtotime($prev_start_one)));

        $prev_start_three = date('Y-m-d', strtotime("-1 day", strtotime($three_month)));
        $prev_end_three = date('Y-m-d', strtotime("-3 month", strtotime($prev_start_three)));

        $prev_start_six = date('Y-m-d', strtotime("-1 day", strtotime($six_month)));
        $prev_end_six = date('Y-m-d', strtotime("-6 month", strtotime($prev_start_six)));

        $prev_start_nine = date('Y-m-d', strtotime("-1 day", strtotime($nine_month)));
        $prev_end_nine = date('Y-m-d', strtotime("-9 month", strtotime($prev_start_nine)));

        $prev_start_year = date('Y-m-d', strtotime("-1 day", strtotime($one_year)));
        $prev_end_year = date('Y-m-d', strtotime("-1 year", strtotime($prev_start_year)));

        $prev_start_two = date('Y-m-d', strtotime("-1 day", strtotime($two_year)));
        $prev_end_two = date('Y-m-d', strtotime("-2 year", strtotime($prev_start_two)));


        foreach($getUser as $key=>$semrush_data){

            $campaignId = $semrush_data->id;  

            if($semrush_data->google_analytics_account == null){

                $result['status'] = 2;
                $result['message'] = 'category id doesnot exists.';

                Error::updateOrCreate(
                    ['request_id' => $campaignId,'module'=> 1],
                    ['response'=> json_encode($result),'request_id' => $campaignId,'module'=> 1]
                );   
                continue;
            }

            $getAnalytics = GoogleAnalyticsUsers::where('id', $semrush_data->google_account_id)->first();
            $user_id = $getAnalytics->user_id;   
            if(!empty($getAnalytics)){    
                $client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);
                $refresh_token  = $getAnalytics->google_refresh_token;

                /*if refresh token expires*/
                if ($client->isAccessTokenExpired()) {
                    GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
                }
                $analyticsCategoryId = $semrush_data->google_analytics_account->category_id;

                $analytics = new \Google_Service_Analytics($client);
            }           

            $check = GoogleAnalyticsUsers::checkAnalyticsData_cron($analytics,$semrush_data->id,$semrush_data->user_id,$semrush_data->google_account_id,$semrush_data->google_analytics_id,$semrush_data->google_property_id,$semrush_data->google_profile_id);


            if(isset($check['status']) && $check['status'] != 1){
                Error::updateOrCreate(
                    ['request_id' => $campaignId,'module'=> 1],
                    ['response'=> json_encode($check),'request_id' => $campaignId,'module'=> 1]
                );
            }else{
                $profile = GoogleAnalyticsUsers::AnalyticsCategoryId($semrush_data->google_profile_id);
                $property_id = GoogleAnalyticsUsers::AnalyticsCategoryId($semrush_data->google_property_id);


                if (file_exists(\config('app.FILE_PATH').'public/analytics/'.$campaignId)) {
                    $graphfilename = \config('app.FILE_PATH').'public/analytics/'.$campaignId.'/graph.json';
                    if(file_exists($graphfilename)){
                      if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){
                         $this->analytics_graph_data($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
                     }
                 }else{
                    $this->analytics_graph_data($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
                }
            }
            elseif (!file_exists(\config('app.FILE_PATH').'public/analytics/'.$campaignId)) {
              mkdir(\config('app.FILE_PATH').'public/analytics/'.$campaignId, 0777, true);
              $this->analytics_graph_data($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
          }


          if (file_exists(\config('app.FILE_PATH').'public/analytics/'.$campaignId)) {
            $metricsFilename = \config('app.FILE_PATH').'public/analytics/'.$campaignId.'/metrics.json';
            if(file_exists($metricsFilename)){
              if(date("Y-m-d", filemtime($metricsFilename)) != date('Y-m-d')){
                 $this->analytics_metrics_data($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId,$user_id);
             }
         }else{
            $this->analytics_metrics_data($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId,$user_id);
        }
    }
    elseif (!file_exists(\config('app.FILE_PATH').'public/analytics/'.$campaignId)) {
      mkdir(\config('app.FILE_PATH').'public/analytics/'.$campaignId, 0777, true);
      $this->analytics_metrics_data($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId,$user_id);
  }


  $goals = $analytics->management_goals->listManagementGoals($analyticsCategoryId, $property_id,$profile);

  if(isset($goals->totalResults) && $goals->totalResults > 0){

    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $graphfilename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/graph.json';
        if(file_exists($graphfilename)){
            if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){
                $this->goal_completion_graph($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
            }
        }else{
            $this->goal_completion_graph($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
        }
    } elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->goal_completion_graph($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
    }


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $stats_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/statistics.json';
        if(file_exists($stats_file)){
            if(date("Y-m-d", filemtime($stats_file)) != date('Y-m-d')){
                $this->goal_completion_statistics($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
            }
        }else{
            $this->goal_completion_statistics($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
        }
    } elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->goal_completion_statistics($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
    }

    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $location_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_locations.json';
        if(file_exists($location_file)){
            if(date("Y-m-d", filemtime($location_file)) != date('Y-m-d')){
                $this->location_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
            }
        }else{
            $this->location_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
        }
    } elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->location_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
    }

    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $location_three = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_month_locations.json';
        if(file_exists($location_three)){
            if(date("Y-m-d", filemtime($location_three)) != date('Y-m-d')){
                $this->location_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
            }
        }else{
            $this->location_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
        }
    }
    elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->location_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
    }

    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $location_six = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_month_locations.json';
        if(file_exists($location_six)){
            if(date("Y-m-d", filemtime($location_six)) != date('Y-m-d')){
                $this->location_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
            }
        }else{
            $this->location_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
        }
    }
    elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->location_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
    }


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $location_nine = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_month_locations.json';
        if(file_exists($location_nine)){
            if(date("Y-m-d", filemtime($location_nine)) != date('Y-m-d')){
                $this->location_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
            }
        }else{
            $this->location_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
        }
    }
    elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->location_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
    }

    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $location_year = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_locations.json';
        if(file_exists($location_year)){
            if(date("Y-m-d", filemtime($location_year)) != date('Y-m-d')){
                $this->location_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
            }
        }else{
            $this->location_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
        }
    }
    elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->location_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
    }

    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $location_twoyear = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_locations.json';
        if(file_exists($location_twoyear)){
            if(date("Y-m-d", filemtime($location_twoyear)) != date('Y-m-d')){
                $this->location_two_year($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
            }
        }else{
            $this->location_two_year($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
        }
    }
    elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->location_two_year($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
    }


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $sourcemedium_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_sourcemedium.json';
        if(file_exists($sourcemedium_file)){
            if(date("Y-m-d", filemtime($sourcemedium_file)) != date('Y-m-d')){
                $this->sourcemedium_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
            }
        }else{
            $this->sourcemedium_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
        }
    }
    elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->sourcemedium_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
    }

    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $sm_three_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_sourcemedium.json';
        if(file_exists($sm_three_file)){
            if(date("Y-m-d", filemtime($sm_three_file)) != date('Y-m-d')){
                $this->sourcemedium_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
            }
        }else{
            $this->sourcemedium_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
        }
    }
    elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->sourcemedium_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
    }

    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $sm_six_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_sourcemedium.json';
        if(file_exists($sm_six_file)){
            if(date("Y-m-d", filemtime($sm_six_file)) != date('Y-m-d')){
                $this->sourcemedium_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
            }
        }else{
            $this->sourcemedium_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
        }
    }
    elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->sourcemedium_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
    }


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $sm_nine_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_sourcemedium.json';
        if(file_exists($sm_nine_file)){
            if(date("Y-m-d", filemtime($sm_nine_file)) != date('Y-m-d')){
                $this->sourcemedium_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
            }
        }else{
            $this->sourcemedium_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
        }
    }
    elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->sourcemedium_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
    }


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $sm_year_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_sourcemedium.json';
        if(file_exists($sm_year_file)){
            if(date("Y-m-d", filemtime($sm_year_file)) != date('Y-m-d')){
                $this->sourcemedium_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
            }
        }else{
            $this->sourcemedium_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
        }
    }
    elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->sourcemedium_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
    }


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $sm_twoyear_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_sourcemedium.json';
        if(file_exists($sm_twoyear_file)){
            if(date("Y-m-d", filemtime($sm_twoyear_file)) != date('Y-m-d')){
                $this->sourcemedium_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
            }
        }else{
            $this->sourcemedium_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
        }
    }
    elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        $this->sourcemedium_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
    }

}

SemrushUserAccount::where('id',$campaignId)->update([
    'goal_completion_count' => $goals->getTotalResults()
]);                


if($semrush_data->ecommerce_goals == 1){
    if (!file_exists(\config('app.FILE_PATH') . 'public/ecommerce_goals/' . $campaignId))
    {
        mkdir(\config('app.FILE_PATH') . 'public/ecommerce_goals/' . $campaignId, 0777, true);
    }

    $this->ecommerce_goal_graph($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
    $this->ecommerce_goal_statistics($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
    $this->ecommerce_product_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
    $this->ecommerce_product_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
    $this->ecommerce_product_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
    $this->ecommerce_product_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
    $this->ecommerce_product_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
    $this->ecommerce_product_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
    $this->ecommerce_product_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
}elseif($semrush_data->ecommerce_goals == 0){
    if (file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        SemrushUserAccount::remove_directory(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId);
    }
}            
                   //} //get getAnalytics end
}
sleep(1);
            } //end foreach
        }
    } catch (\Exception $e) {
        return $e->getMessage();
    }
}

private function analytics_graph_data($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId){
    $current_data = GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$start_date,$end_date);    

    /*file_put_contents(dirname(__FILE__).'/logs/current_data_'.$campaignId.'.txt',print_r($current_data,true));*/
    

    $outputRes = array_column ($current_data->rows , 0);

    $previous_data =  GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$prev_start_date,$prev_end_date);

    $outputRes_prev = array_column ($previous_data->rows , 0);


    $count_session = array_column ( $current_data->rows , 1);

    $from_dates  =  array_map(function($val) { return date("d M, Y", strtotime($val)); }, $outputRes);  
    $from_dates_format  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes);  


    /*prev data*/       
    $from_dates_prev  =  array_map(function($val) { return date("d M, Y", strtotime($val)); }, $outputRes_prev);            
    $from_dates_prev_format  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes_prev);            
    $combine_session = array_column($previous_data->rows , 1);

    $final_array = array_merge($combine_session,$count_session);
    $dates_final_array = array_merge($from_dates_prev,$from_dates);
    $dates_format = array_merge($from_dates_prev_format,$from_dates_format);



    $array = array(
        'final_array' =>$final_array,
        'from_dates'=>$dates_final_array,
        'dates_format'=>$dates_format
    );

    if (file_exists(\config('app.FILE_PATH').'public/analytics/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/analytics/'.$campaignId.'/graph.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/analytics/'.$campaignId.'/graph.json', print_r(json_encode($array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/analytics/'.$campaignId.'/graph.json', print_r(json_encode($array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/analytics/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/analytics/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/analytics/'.$campaignId.'/graph.json', print_r(json_encode($array,true),true));
    }
    $final_array = $dates_final_array = $dates_format = $array =  array();
}


private function analytics_metrics_data($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId,$user_id){
    $start_date_new = date('Y-m-d',strtotime('-1 day',strtotime($end_date)));
    $currentData = GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$start_date,$end_date);

    $outputRes_metrics = array_column ($currentData->rows , 0);
    $from_dates_metrics  =  array_map(function($val) { return date("d M, Y", strtotime($val)); }, $outputRes_metrics);  
    $outputRes_sessions = array_column ($currentData->rows , 1);
    $current_sessions_data  =  array_map(function($val) { return $val; }, $outputRes_sessions); 

    $outputRes_users = array_column ($currentData->rows , 2);
    $current_users_data  =  array_map(function($val) { return $val; }, $outputRes_users);

    $outputRes_pageviews = array_column ($currentData->rows , 3);
    $current_pageviews_data  =  array_map(function($val) { return $val; }, $outputRes_pageviews);   


    /*Previous data*/
    $previousData = GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$start_date_new,$prev_end_date);

    $outputRes_metrics_prev = array_column ($previousData->rows , 0);
    $from_dates_metrics_prev  =  array_map(function($val) { return date("d M, Y", strtotime($val)); }, $outputRes_metrics_prev);    
    $outputRes_sessions_prev = array_column ($previousData->rows , 1);
    $prev_sessions_data  =  array_map(function($val) { return $val; }, $outputRes_sessions_prev);

    $outputRes_users_prev = array_column ($previousData->rows , 2);
    $prev_users_data  =  array_map(function($val) { return $val; }, $outputRes_users_prev);

    $outputRes_pageviews_prev = array_column ($previousData->rows , 3);
    $prev_pageviews_data  =  array_map(function($val) { return $val; }, $outputRes_pageviews_prev);


    /*merged data for comparison*/
    $metrics_dates = array_merge($from_dates_metrics_prev,$from_dates_metrics);
    $metrics_sessions = array_merge($prev_sessions_data,$current_sessions_data);
    $metrics_users = array_merge($prev_users_data,$current_users_data);
    $metrics_pageviews = array_merge($prev_pageviews_data,$current_pageviews_data);

    $final_array = array(
        'metrics_dates'=>$metrics_dates,
        'metrics_sessions'=>$metrics_sessions,
        'metrics_users'=>$metrics_users,
        'metrics_pageviews'=>$metrics_pageviews,
    );


    if (file_exists(\config('app.FILE_PATH').'public/analytics/'.$campaignId)) {
        $filename1 = \config('app.FILE_PATH').'public/analytics/'.$campaignId.'/metrics.json';

        if(file_exists($filename1)){
            if(date("Y-m-d", filemtime($filename1)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/analytics/'.$campaignId.'/metrics.json', print_r(json_encode($final_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/analytics/'.$campaignId.'/metrics.json', print_r(json_encode($final_array,true),true));
        }
    }elseif (!file_exists(\config('app.FILE_PATH').'public/analytics/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/analytics/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/analytics/'.$campaignId.'/metrics.json', print_r(json_encode($final_array,true),true));
    }

    $final_array = $metrics_dates = $metrics_sessions = $metrics_users = $metrics_pageviews =  array();


    /*$today = date('Y-m-d');*/
    /*$yesterday = date('Y-m-d',strtotime('-1 days'));
    $beforeyesterday = date('Y-m-d',strtotime('-2 days'));

    $getCurrentStatsToday   =  GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$today,$yesterday);
    $getCurrentStatsYesterday   =  GoogleAnalyticsUsers::getMetricsData($analytics,$profile,$yesterday,$beforeyesterday); 

    $TodayData = $getCurrentStatsToday->getRows();
    $YesterdayData = $getCurrentStatsYesterday->getRows();

    if(!empty($TodayData) && !empty($YesterdayData)){
        $usersPercent = number_format((@$getCurrentStatsToday[0][0]  - @$getCurrentStatsYesterday[0][0]) / @$getCurrentStatsYesterday[0][0] * 100, 2);  
    }else{
        $usersPercent = 0;
    }

    ActivityLog::trafficLog($campaignId,$usersPercent,$user_id); */
    GoogleUpdate::updateTiming($campaignId,'analytics','analytics_type','1');
}

private function goal_completion_graph($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId){
    $current_data = GoogleAnalyticsUsers::OrganicgoalCompletion($analytics, $profile,$start_date,$end_date);    
    /* file_put_contents(dirname(__FILE__).'/logs/current_data_goals_'.$campaignId.'.txt',print_r($current_data,true));*/
    $outputRes = array_column ($current_data->rows , 0);
    $current_organic = array_column ($current_data->rows , 1);

                                            //previous data 
    $previous_data =  GoogleAnalyticsUsers::OrganicgoalCompletion($analytics, $profile,$prev_start_date,$prev_end_date);
    $outputRes_prev = array_column ($previous_data->rows , 0);
    $previous_organic = array_column($previous_data->rows , 1);

                                            //(All Users)
    $current_users_data = GoogleAnalyticsUsers::UsergoalCompletion($analytics, $profile,$start_date,$end_date);    
    $outputResUsr = array_column ($current_users_data->rows , 0);
    $current_users = array_column ($current_users_data->rows , 1);

                                            //previous data (All Users)
    $previous_users_data =  GoogleAnalyticsUsers::UsergoalCompletion($analytics, $profile,$prev_start_date,$prev_end_date);
    $prevOutputResUsr = array_column ($previous_data->rows , 0);
    $previous_users = array_column($previous_data->rows , 1);



    $from_dates_format  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes);  
    /*prev data*/       
    $from_dates_prev_format  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes_prev);            
    $final_organic_data = array_merge($previous_organic,$current_organic);
    $final_user_data = array_merge($previous_users,$current_users);
    $dates_format = array_merge($from_dates_prev_format,$from_dates_format);


    $array = array(
        'final_organic_data' =>$final_organic_data,
        'final_user_data' =>$final_user_data,
        'dates_format'=>$dates_format
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/graph.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/graph.json', print_r(json_encode($array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/graph.json', print_r(json_encode($array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/graph.json', print_r(json_encode($array,true),true));
    }




    $final_organic_data = $final_user_data = $dates_format = $array =  array();
}

private function goal_completion_statistics($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId){
    $current_data_stats = GoogleAnalyticsUsers::GoalCompletionStats($analytics, $profile,$start_date,$end_date);    
    $outputResStats = array_column ($current_data_stats->rows , 0);
    $from_dates_stats  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputResStats);  
    $current_completion_all = array_column ($current_data_stats->rows , 1);
    $current_value_all = array_column ($current_data_stats->rows , 2);
    $current_conversionRate_all = array_column ($current_data_stats->rows , 3);
    $current_abondonRate_all = array_column ($current_data_stats->rows , 4);

                                            //previous data 
    $previous_data_stats =  GoogleAnalyticsUsers::GoalCompletionStats($analytics, $profile,$prev_start_date,$prev_end_date);
    $outputRes_prev_stats = array_column ($previous_data_stats->rows , 0);
    $from_dates_prev_stats  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes_prev_stats);
    $previous_completion_all = array_column($previous_data_stats->rows , 1);
    $previous_value_all = array_column($previous_data_stats->rows , 2);
    $previous_conversionRate_all = array_column($previous_data_stats->rows , 3);
    $previous_abondonRate_all = array_column($previous_data_stats->rows , 4);


    $current_data_organicstats = GoogleAnalyticsUsers::GoalCompletionOrganicStats($analytics, $profile,$start_date,$end_date);    
    $outputResorganicStats = array_column ($current_data_organicstats->rows , 0);
    $from_dates_organicstats  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputResorganicStats);  
    $current_completion_all_organic = array_column ($current_data_organicstats->rows , 1);
    $current_value_all_organic = array_column ($current_data_organicstats->rows , 2);
    $current_conversionRate_all_organic = array_column ($current_data_organicstats->rows , 3);
    $current_abondonRate_all_organic = array_column ($current_data_organicstats->rows , 4);

                                            //previous data 
    $previous_data_organicstats =  GoogleAnalyticsUsers::GoalCompletionOrganicStats($analytics, $profile,$prev_start_date,$prev_end_date);
    $outputRes_prev_organicstats = array_column ($previous_data_organicstats->rows , 0);
    $from_dates_prev_organicstats  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes_prev_organicstats);
    $previous_completion_all_organic = array_column($previous_data_organicstats->rows , 1);
    $previous_value_all_organic = array_column($previous_data_organicstats->rows , 2);
    $previous_conversionRate_all_organic = array_column($previous_data_organicstats->rows , 3);
    $previous_abondonRate_all_organic = array_column($previous_data_organicstats->rows , 4);


    $completion_all = array_merge($previous_completion_all,$current_completion_all);
    $value_all = array_merge($previous_value_all,$current_value_all);
    $conversionRate_all = array_merge($previous_conversionRate_all,$current_conversionRate_all);
    $abondonRate_all = array_merge($previous_abondonRate_all,$current_abondonRate_all);
    $dates = array_merge($from_dates_prev_stats,$from_dates_stats);


    $completion_all_organic = array_merge($previous_completion_all_organic,$current_completion_all_organic);
    $value_all_organic = array_merge($previous_value_all_organic,$current_value_all_organic);
    $conversionRate_all_organic = array_merge($previous_conversionRate_all_organic,$current_conversionRate_all_organic);
    $abondonRate_all_organic = array_merge($previous_abondonRate_all_organic,$current_abondonRate_all_organic);
    $dates_organic = array_merge($from_dates_prev_organicstats,$from_dates_organicstats);


    $statistics_array = array(
        'dates'=>$dates,
        'completion_all' =>$completion_all,
        'value_all' =>$value_all,
        'conversionRate_all' =>$conversionRate_all,
        'abondonRate_all' =>$abondonRate_all,
        'dates_organic'=>$dates_organic,
        'completion_all_organic' =>$completion_all_organic,
        'value_all_organic' =>$value_all_organic,
        'conversionRate_all_organic' =>$conversionRate_all_organic,
        'abondonRate_all_organic' =>$abondonRate_all_organic
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/statistics.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/statistics.json', print_r(json_encode($statistics_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/statistics.json', print_r(json_encode($statistics_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/statistics.json', print_r(json_encode($statistics_array,true),true));
    }

    $dates = $completion_all = $value_all = $conversionRate_all = $abondonRate_all =  $dates_organic = $completion_all_organic = $value_all_organic = $conversionRate_all_organic = $abondonRate_all_organic = $statistics_array =  array();
}


private function location_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId){
    $current_user_location_month = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$today,$one_month);  
    if($current_user_location_month->totalResults >0){
        $current_month_array = array(
            'one_current_location'=> array_column ($current_user_location_month->rows , 0),
            'one_current_goal' => array_column ($current_user_location_month->rows , 1)
        );
    }else{
        $current_month_array = array(
            'one_current_location'=> array(),
            'one_current_goal' => array()
        );
    }

    $prev_user_location_month = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$prev_start_one,$prev_end_one);   
    if(isset($prev_user_location_month->totalResults) && $prev_user_location_month->totalResults > 0){
        $prev_month_array = array(
            'one_prev_location'=>array_column ($prev_user_location_month->rows , 0),
            'one_prev_goal' =>array_column ($prev_user_location_month->rows , 1)
        );
    }else{
        $prev_month_array = array(
            'one_prev_location'=>array(),
            'one_prev_goal' =>array()
        );
    }

    $current_organic_location_month = GoogleAnalyticsUsers::GoalCompletionOrganicLocation($analytics, $profile,$today,$one_month); 
    if(isset($current_organic_location_month->totalResults) && $current_organic_location_month->totalResults > 0){
        $current_month_organic_array = array(
            'one_current_organic_location'=>array_column ($current_organic_location_month->rows , 0),
            'one_current_organic_goal' =>array_column ($current_organic_location_month->rows , 1)
        );
    }else{
        $current_month_organic_array = array(
            'one_current_organic_location'=>array(),
            'one_current_organic_goal' =>array()
        );
    }

    $prev_organic_location_month = GoogleAnalyticsUsers::GoalCompletionOrganicLocation($analytics, $profile,$prev_start_one,$prev_end_one); 
    if(isset($prev_organic_location_month->totalResults) && $prev_organic_location_month->totalResults > 0){
        $prev_month_organic_array = array(
            'one_prev_organic_location'=>array_column ($prev_organic_location_month->rows , 0),
            'one_prev_organic_goal' =>array_column ($prev_organic_location_month->rows , 1)
        );
    }else{
        $prev_month_organic_array = array(
            'one_prev_organic_location'=>array(),
            'one_prev_organic_goal' =>array()
        );
    }



    $one_array = array(
        'current_month_array'=>$current_month_array,
        'prev_month_array'=>$prev_month_array,
        'current_month_organic_array'=>$current_month_organic_array,
        'prev_month_organic_array'=>$prev_month_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_locations.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_locations.json', print_r(json_encode($one_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_locations.json', print_r(json_encode($one_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_locations.json', print_r(json_encode($one_array,true),true));
    }

    $current_month_array = $prev_month_array = $current_month_organic_array =  $prev_month_organic_array = $one_array = array();
}


private function location_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId){
    $current_user_location_three = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$today,$three_month);  
    if(isset($current_user_location_three->totalResults) && $current_user_location_three->totalResults > 0){
        $current_three_array = array(
            'three_current_location'=> array_column ($current_user_location_three->rows , 0),
            'three_current_goal' => array_column ($current_user_location_three->rows , 1)
        );
    }else{
        $current_three_array = array(
            'three_current_location'=> array(),
            'three_current_goal' => array()
        );
    }

    $prev_user_location_three = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$prev_start_three,$prev_end_three);   
    if(isset($prev_user_location_three->totalResults) && $prev_user_location_three->totalResults > 0){
        $prev_three_array = array(
            'three_prev_location'=>array_column ($prev_user_location_three->rows , 0),
            'three_prev_goal' =>array_column ($prev_user_location_three->rows , 1)
        );
    }else{
        $prev_three_array = array(
            'three_prev_location'=>array(),
            'three_prev_goal' =>array()
        );
    }

    $current_organic_location_three = GoogleAnalyticsUsers::GoalCompletionOrganicLocation($analytics, $profile,$today,$three_month); 
    if(isset($current_organic_location_three->totalResults) && $current_organic_location_three->totalResults > 0){
        $current_three_organic_array = array(
            'three_current_organic_location'=>array_column ($current_organic_location_three->rows , 0),
            'three_current_organic_goal' =>array_column ($current_organic_location_three->rows , 1)
        );
    }else{
        $current_three_organic_array = array(
            'three_current_organic_location'=>array(),
            'three_current_organic_goal' =>array()
        );
    }

    $prev_organic_location_three = GoogleAnalyticsUsers::GoalCompletionOrganicLocation($analytics, $profile,$prev_start_three,$prev_end_three); 
    if(isset($prev_organic_location_three->totalResults) && $prev_organic_location_three->totalResults > 0){
        $prev_three_organic_array = array(
            'three_prev_organic_location'=>array_column ($prev_organic_location_three->rows , 0),
            'three_prev_organic_goal' =>array_column ($prev_organic_location_three->rows , 1)
        );
    }else{
        $prev_three_organic_array = array(
            'three_prev_organic_location'=>array(),
            'three_prev_organic_goal' =>array()
        );
    }



    $three_array = array(
        'current_three_array'=>$current_three_array,
        'prev_three_array'=>$prev_three_array,
        'current_three_organic_array'=>$current_three_organic_array,
        'prev_three_organic_array'=>$prev_three_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_month_locations.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_month_locations.json', print_r(json_encode($three_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_month_locations.json', print_r(json_encode($three_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_month_locations.json', print_r(json_encode($three_array,true),true));
    }

    $current_three_array = $prev_three_array = $current_three_organic_array =  $prev_three_organic_array = $three_array = array();
}

private function location_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId){
    $current_user_location_six = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$today,$six_month);  
    if(isset($current_user_location_six->totalResults) && $current_user_location_six->totalResults > 0){
        $current_six_array = array(
            'six_current_location'=> array_column ($current_user_location_six->rows , 0),
            'six_current_goal' => array_column ($current_user_location_six->rows , 1)
        );
    }else{
        $current_six_array = array(
            'six_current_location'=> array(),
            'six_current_goal' => array()
        );
    }

    $prev_user_location_six = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$prev_start_six,$prev_end_six);   
    if(isset($prev_user_location_six->totalResults) && $prev_user_location_six->totalResults > 0){
        $prev_six_array = array(
            'six_prev_location'=>array_column ($prev_user_location_six->rows , 0),
            'six_prev_goal' =>array_column ($prev_user_location_six->rows , 1)
        );
    }else{
        $prev_six_array = array(
            'six_prev_location'=>array(),
            'six_prev_goal' =>array()
        );
    }

    $current_organic_location_six = GoogleAnalyticsUsers::GoalCompletionOrganicLocation($analytics, $profile,$today,$six_month); 
    if(isset($current_organic_location_six->totalResults) && $current_organic_location_six->totalResults > 0){
        $current_six_organic_array = array(
            'six_current_organic_location'=>array_column ($current_organic_location_six->rows , 0),
            'six_current_organic_goal' =>array_column ($current_organic_location_six->rows , 1)
        );
    }else{
        $current_six_organic_array = array(
            'six_current_organic_location'=>array(),
            'six_current_organic_goal' =>array()
        );
    }

    $prev_organic_location_six = GoogleAnalyticsUsers::GoalCompletionOrganicLocation($analytics, $profile,$prev_start_six,$prev_end_six); 
    if(isset($prev_organic_location_six->totalResults) && $prev_organic_location_six->totalResults > 0){
        $prev_six_organic_array = array(
            'six_prev_organic_location'=>array_column ($prev_organic_location_six->rows , 0),
            'six_prev_organic_goal' =>array_column ($prev_organic_location_six->rows , 1)
        );
    }else{
        $prev_six_organic_array = array(
            'six_prev_organic_location'=>array(),
            'six_prev_organic_goal' =>array()
        );
    }

    $six_array = array(
        'current_six_array'=>$current_six_array,
        'prev_six_array'=>$prev_six_array,
        'current_six_organic_array'=>$current_six_organic_array,
        'prev_six_organic_array'=>$prev_six_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_month_locations.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_month_locations.json', print_r(json_encode($six_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_month_locations.json', print_r(json_encode($six_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_month_locations.json', print_r(json_encode($six_array,true),true));
    }

    $current_six_array = $prev_six_array = $current_six_organic_array =  $prev_six_organic_array = $six_array = array();
}

private function location_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId){
    $current_user_location_nine = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$today,$nine_month);  
    if(isset($current_user_location_nine->totalResults) && $current_user_location_nine->totalResults > 0){
        $current_nine_array = array(
            'nine_current_location'=> array_column ($current_user_location_nine->rows , 0),
            'nine_current_goal' => array_column ($current_user_location_nine->rows , 1)
        );
    }else{
        $current_nine_array = array(
            'nine_current_location'=> array(),
            'nine_current_goal' => array()
        );
    }

    $prev_user_location_nine = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$prev_start_nine,$prev_end_nine);   
    if(isset($prev_user_location_nine->totalResults) && $prev_user_location_nine->totalResults > 0){
        $prev_nine_array = array(
            'nine_prev_location'=>array_column ($prev_user_location_nine->rows , 0),
            'nine_prev_goal' =>array_column ($prev_user_location_nine->rows , 1)
        );
    }else{
        $prev_nine_array = array(
            'nine_prev_location'=>array(),
            'nine_prev_goal' =>array()
        );
    }

    $current_organic_location_nine = GoogleAnalyticsUsers::GoalCompletionOrganicLocation($analytics, $profile,$today,$nine_month); 
    if(isset($current_organic_location_nine->totalResults) && $current_organic_location_nine->totalResults > 0){
        $current_nine_organic_array = array(
            'nine_current_organic_location'=>array_column ($current_organic_location_nine->rows , 0),
            'nine_current_organic_goal' =>array_column ($current_organic_location_nine->rows , 1)
        );
    }else{
        $current_nine_organic_array = array(
            'nine_current_organic_location'=>array(),
            'nine_current_organic_goal' =>array()
        );
    }

    $prev_organic_location_nine = GoogleAnalyticsUsers::GoalCompletionOrganicLocation($analytics, $profile,$prev_start_nine,$prev_end_nine);
    if(isset($prev_organic_location_nine->totalResults) && $prev_organic_location_nine->totalResults > 0){
        $prev_nine_organic_array = array(
            'nine_prev_organic_location'=>array_column ($prev_organic_location_nine->rows , 0),
            'nine_prev_organic_goal' =>array_column ($prev_organic_location_nine->rows , 1)
        );
    }else{
        $prev_nine_organic_array = array(
            'nine_prev_organic_location'=>array(),
            'nine_prev_organic_goal' =>array()
        );
    }



    $nine_array = array(
        'current_nine_array'=>$current_nine_array,
        'prev_nine_array'=>$prev_nine_array,
        'current_nine_organic_array'=>$current_nine_organic_array,
        'prev_nine_organic_array'=>$prev_nine_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_month_locations.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_month_locations.json', print_r(json_encode($nine_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_month_locations.json', print_r(json_encode($nine_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_month_locations.json', print_r(json_encode($nine_array,true),true));
    }

    $current_nine_array = $prev_nine_array = $current_nine_organic_array =  $prev_nine_organic_array = $nine_array = array();
}

private function location_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId){
    $current_user_location_year = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$today,$one_year);  
    if(isset($current_user_location_year->totalResults) && $current_user_location_year->totalResults > 0){
        $current_year_array = array(
            'year_current_location'=> array_column ($current_user_location_year->rows , 0),
            'year_current_goal' => array_column ($current_user_location_year->rows , 1)
        );
    }else{
        $current_year_array = array(
            'year_current_location'=> array(),
            'year_current_goal' => array()
        );
    }



    $prev_user_location_year = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$prev_start_year,$prev_end_year);  

    if(isset($prev_user_location_year->totalResults) && $prev_user_location_year->totalResults > 0){
        $prev_year_array = array(
            'year_prev_location'=>array_column ($prev_user_location_year->rows , 0),
            'year_prev_goal' =>array_column ($prev_user_location_year->rows , 1)
        );
    }else{
        $prev_year_array = array(
            'year_prev_location'=>array(),
            'year_prev_goal' =>array()
        );
    }


    $current_organic_location_year = GoogleAnalyticsUsers::GoalCompletionOrganicLocation($analytics, $profile,$today,$one_year); 

    if(isset($current_organic_location_year->totalResults) && $current_organic_location_year->totalResults > 0){
        $current_year_organic_array = array(
            'year_current_organic_location'=>array_column ($current_organic_location_year->rows , 0),
            'year_current_organic_goal' =>array_column ($current_organic_location_year->rows , 1)
        );
    }else{
        $current_year_organic_array = array(
            'year_current_organic_location'=>array(),
            'year_current_organic_goal' =>array()
        );
    }

    $prev_organic_location_year = GoogleAnalyticsUsers::GoalCompletionOrganicLocation($analytics, $profile,$prev_start_year,$prev_end_year); 
    if(isset($prev_organic_location_year->totalResults) && $prev_organic_location_year->totalResults > 0){
        $prev_year_organic_array = array(
            'year_prev_organic_location'=>array_column ($prev_organic_location_year->rows , 0),
            'year_prev_organic_goal' =>array_column ($prev_organic_location_year->rows , 1)
        );
    }else{
        $prev_year_organic_array = array(
            'year_prev_organic_location'=>array(),
            'year_prev_organic_goal' =>array()
        );
    }



    $year_array = array(
        'current_year_array'=>$current_year_array,
        'prev_year_array'=>$prev_year_array,
        'current_year_organic_array'=>$current_year_organic_array,
        'prev_year_organic_array'=>$prev_year_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_locations.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_locations.json', print_r(json_encode($year_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_locations.json', print_r(json_encode($year_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_locations.json', print_r(json_encode($year_array,true),true));
    }

    $current_year_array = $prev_year_array = $current_year_organic_array =  $prev_year_organic_array = $year_array = array();
}

private function location_two_year($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId){
    $current_user_location_twoyear = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$today,$two_year);  
    if(isset($current_user_location_twoyear->totalResults) && $current_user_location_twoyear->totalResults > 0){
        $current_twoyear_array = array(
            'twoyear_current_location'=> array_column ($current_user_location_twoyear->rows , 0),
            'twoyear_current_goal' => array_column ($current_user_location_twoyear->rows , 1)
        );
    }else{
        $current_twoyear_array = array(
            'twoyear_current_location'=> array(),
            'twoyear_current_goal' => array()
        );
    }

    $prev_user_location_twoyear = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$prev_start_two,$prev_end_two);   
    if(isset($prev_user_location_twoyear->totalResults) && $prev_user_location_twoyear->totalResults > 0){
        $prev_twoyear_array = array(
            'twoyear_prev_location'=>array_column ($prev_user_location_twoyear->rows , 0),
            'twoyear_prev_goal' =>array_column ($prev_user_location_twoyear->rows , 1)
        );
    }else{
        $prev_twoyear_array = array(
            'twoyear_prev_location'=>array(),
            'twoyear_prev_goal' =>array()
        );
    }

    $current_organic_location_twoyear = GoogleAnalyticsUsers::GoalCompletionOrganicLocation($analytics, $profile,$today,$two_year); 
    if(isset($current_organic_location_twoyear->totalResults) && $current_organic_location_twoyear->totalResults > 0){
        $current_twoyear_organic_array = array(
            'twoyear_current_organic_location'=>array_column ($current_organic_location_twoyear->rows , 0),
            'twoyear_current_organic_goal' =>array_column ($current_organic_location_twoyear->rows , 1)
        );
    }else{
        $current_twoyear_organic_array = array(
            'twoyear_current_organic_location'=>array(),
            'twoyear_current_organic_goal' =>array()
        );
    }

    $prev_organic_location_twoyear = GoogleAnalyticsUsers::GoalCompletionOrganicLocation($analytics, $profile,$prev_start_two,$prev_end_two); 
    if(isset($prev_organic_location_twoyear->totalResults) && $prev_organic_location_twoyear->totalResults > 0){
        $prev_twoyear_organic_array = array(
            'twoyear_prev_organic_location'=>array_column ($prev_organic_location_twoyear->rows , 0),
            'twoyear_prev_organic_goal' =>array_column ($prev_organic_location_twoyear->rows , 1)
        );
    }else{
        $prev_twoyear_organic_array = array(
            'twoyear_prev_organic_location'=>array(),
            'twoyear_prev_organic_goal' =>array()
        );
    }



    $twoyear_array = array(
        'current_twoyear_array'=>$current_twoyear_array,
        'prev_twoyear_array'=>$prev_twoyear_array,
        'current_twoyear_organic_array'=>$current_twoyear_organic_array,
        'prev_twoyear_organic_array'=>$prev_twoyear_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_locations.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_locations.json', print_r(json_encode($twoyear_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_locations.json', print_r(json_encode($twoyear_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_locations.json', print_r(json_encode($twoyear_array,true),true));
    }

    $current_twoyear_array = $prev_year_array = $prev_twoyear_organic_array =  $prev_twoyear_organic_array = $twoyear_array = array();
}

private function sourcemedium_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId){
    $current_user_sourcemedium_month = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$today,$one_month);  
    if(isset($current_user_sourcemedium_month->totalResults) && $current_user_sourcemedium_month->totalResults > 0){
        $current_month_sm_array = array(
            'one_current_sm_location'=> array_column ($current_user_sourcemedium_month->rows , 0),
            'one_current_sm_goal' => array_column ($current_user_sourcemedium_month->rows , 1)
        );
    }else{
        $current_month_sm_array = array(
            'one_current_sm_location'=> array(),
            'one_current_sm_goal' => array()
        );
    }

    $prev_user_sourcemedium_month = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$prev_start_one,$prev_end_one);
    if(isset($prev_user_sourcemedium_month->totalResults) && $prev_user_sourcemedium_month->totalResults > 0){
        $prev_month_sm_array = array(
            'one_prev_sm_location'=>array_column ($prev_user_sourcemedium_month->rows , 0),
            'one_prev_sm_goal' =>array_column ($prev_user_sourcemedium_month->rows , 1)
        );
    }else{
        $prev_month_sm_array = array(
            'one_prev_sm_location'=> array(),
            'one_prev_sm_goal' => array()
        );
    }

    $current_organic_sourcemedium_month = GoogleAnalyticsUsers::GoalCompletionSourceMediumOrganic($analytics, $profile,$today,$one_month);
    if(isset($current_organic_sourcemedium_month->totalResults) && $current_organic_sourcemedium_month->totalResults > 0) {
        $current_month_sm_organic_array = array(
            'one_current_organic_sm_location'=>array_column ($current_organic_sourcemedium_month->rows , 0),
            'one_current_organic_sm_goal' =>array_column ($current_organic_sourcemedium_month->rows , 1)
        );
    }else{
        $current_month_sm_organic_array = array(
            'one_current_organic_sm_location'=>array(),
            'one_current_organic_sm_goal' =>array()
        );
    }

    $prev_organic_sourcemedium_month = GoogleAnalyticsUsers::GoalCompletionSourceMediumOrganic($analytics, $profile,$prev_start_one,$prev_end_one); 
    if(isset($prev_organic_sourcemedium_month->totalResults) && $prev_organic_sourcemedium_month->totalResults > 0){
        $prev_month_sm_organic_array = array(
            'one_prev_organic_sm_location'=>array_column ($prev_organic_sourcemedium_month->rows , 0),
            'one_prev_organic_sm_goal' =>array_column ($prev_organic_sourcemedium_month->rows , 1)
        );
    }else{
        $prev_month_sm_organic_array = array(
            'one_prev_organic_sm_location'=>array(),
            'one_prev_organic_sm_goal' =>array()
        );
    }



    $one_sm_array = array(
        'current_month_sm_array'=>$current_month_sm_array,
        'prev_month_sm_array'=>$prev_month_sm_array,
        'current_month_sm_organic_array'=>$current_month_sm_organic_array,
        'prev_month_sm_organic_array'=>$prev_month_sm_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_sourcemedium.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_sourcemedium.json', print_r(json_encode($one_sm_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_sourcemedium.json', print_r(json_encode($one_sm_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_sourcemedium.json', print_r(json_encode($one_sm_array,true),true));
    }

    $current_month_sm_array = $prev_month_sm_array = $current_month_sm_organic_array =  $prev_month_sm_organic_array = $one_sm_array = array();
}


private function sourcemedium_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId){
    $current_user_sourcemedium_three = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$today,$three_month);  
    if(isset($current_user_sourcemedium_three->totalResults) && $current_user_sourcemedium_three->totalResults > 0){
        $current_three_sm_array = array(
            'three_current_sm_location'=> array_column ($current_user_sourcemedium_three->rows , 0),
            'three_current_sm_goal' => array_column ($current_user_sourcemedium_three->rows , 1)
        );
    }else{
        $current_three_sm_array = array(
            'three_current_sm_location'=> array(),
            'three_current_sm_goal' => array()
        );
    }

    $prev_user_sourcemedium_three = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$prev_start_three,$prev_end_three);   
    if(isset($prev_user_sourcemedium_three->totalResults) && $prev_user_sourcemedium_three->totalResults > 0){
        $prev_three_sm_array = array(
            'three_prev_sm_location'=>array_column ($prev_user_sourcemedium_three->rows , 0),
            'three_prev_sm_goal' =>array_column ($prev_user_sourcemedium_three->rows , 1)
        );
    }else{
        $prev_three_sm_array = array(
            'three_prev_sm_location'=>array(),
            'three_prev_sm_goal' =>array()
        );
    }

    $current_organic_sourcemedium_three = GoogleAnalyticsUsers::GoalCompletionSourceMediumOrganic($analytics, $profile,$today,$three_month); 
    if(isset($current_organic_sourcemedium_three->totalResults) && $current_organic_sourcemedium_three->totalResults > 0){
        $current_three_sm_organic_array = array(
            'three_current_organic_sm_location'=>array_column ($current_organic_sourcemedium_three->rows , 0),
            'three_current_organic_sm_goal' =>array_column ($current_organic_sourcemedium_three->rows , 1)
        );
    }else{
        $current_three_sm_organic_array = array(
            'three_current_organic_sm_location'=>array(),
            'three_current_organic_sm_goal' =>array()
        );
    }

    $prev_organic_sourcemedium_three = GoogleAnalyticsUsers::GoalCompletionSourceMediumOrganic($analytics, $profile,$prev_start_three,$prev_end_three); 
    if(isset($prev_organic_sourcemedium_three->totalResults) && $prev_organic_sourcemedium_three->totalResults > 0){
        $prev_three_sm_organic_array = array(
            'three_prev_organic_sm_location'=>array_column ($prev_organic_sourcemedium_three->rows , 0),
            'three_prev_organic_sm_goal' =>array_column ($prev_organic_sourcemedium_three->rows , 1)
        );
    }else{
        $prev_three_sm_organic_array = array(
            'three_prev_organic_sm_location'=>array(),
            'three_prev_organic_sm_goal' =>array()
        );
    }



    $three_sm_array = array(
        'current_three_sm_array'=>$current_three_sm_array,
        'prev_three_sm_array'=>$prev_three_sm_array,
        'current_three_sm_organic_array'=>$current_three_sm_organic_array,
        'prev_three_sm_organic_array'=>$prev_three_sm_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_sourcemedium.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_sourcemedium.json', print_r(json_encode($three_sm_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_sourcemedium.json', print_r(json_encode($three_sm_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_sourcemedium.json', print_r(json_encode($three_sm_array,true),true));
    }

    $current_three_sm_array = $prev_three_sm_array = $current_three_sm_organic_array =  $prev_three_sm_organic_array = $three_sm_array = array();
}

private function sourcemedium_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId){
    $current_user_sourcemedium_six = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$today,$six_month);  
    if(isset($current_user_sourcemedium_six->totalResults) && $current_user_sourcemedium_six->totalResults > 0){
        $current_six_sm_array = array(
            'six_current_sm_location'=> array_column ($current_user_sourcemedium_six->rows , 0),
            'six_current_sm_goal' => array_column ($current_user_sourcemedium_six->rows , 1)
        );
    }else{
        $current_six_sm_array = array(
            'six_current_sm_location'=> array(),
            'six_current_sm_goal' => array()
        );
    }

    $prev_user_sourcemedium_six = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$prev_start_six,$prev_end_six);   
    if(isset($prev_user_sourcemedium_six->totalResults) && $prev_user_sourcemedium_six->totalResults > 0){
        $prev_six_sm_array = array(
            'six_prev_sm_location'=>array_column ($prev_user_sourcemedium_six->rows , 0),
            'six_prev_sm_goal' =>array_column ($prev_user_sourcemedium_six->rows , 1)
        );
    }else{
        $prev_six_sm_array = array(
            'six_prev_sm_location'=>array(),
            'six_prev_sm_goal' =>array()
        );
    }

    $current_organic_sourcemedium_six = GoogleAnalyticsUsers::GoalCompletionSourceMediumOrganic($analytics, $profile,$today,$six_month); 
    if(isset($current_organic_sourcemedium_six->totalResults) && $current_organic_sourcemedium_six->totalResults > 0){
        $current_six_sm_organic_array = array(
            'six_current_organic_sm_location'=>array_column ($current_organic_sourcemedium_six->rows , 0),
            'six_current_organic_sm_goal' =>array_column ($current_organic_sourcemedium_six->rows , 1)
        );
    }else{
        $current_six_sm_organic_array = array(
            'six_current_organic_sm_location'=>array(),
            'six_current_organic_sm_goal' =>array()
        );
    }

    $prev_organic_sourcemedium_six = GoogleAnalyticsUsers::GoalCompletionSourceMediumOrganic($analytics, $profile,$prev_start_six,$prev_end_six); 
    if(isset($prev_organic_sourcemedium_six->totalResults) && $prev_organic_sourcemedium_six->totalResults > 0){
        $prev_six_sm_organic_array = array(
            'six_prev_organic_sm_location'=>array_column ($prev_organic_sourcemedium_six->rows , 0),
            'six_prev_organic_sm_goal' =>array_column ($prev_organic_sourcemedium_six->rows , 1)
        );
    }else{
        $prev_six_sm_organic_array = array(
            'six_prev_organic_sm_location'=>array(),
            'six_prev_organic_sm_goal' =>array()
        );
    }


    $six_sm_array = array(
        'current_six_sm_array'=>$current_six_sm_array,
        'prev_six_sm_array'=>$prev_six_sm_array,
        'current_six_sm_organic_array'=>$current_six_sm_organic_array,
        'prev_six_sm_organic_array'=>$prev_six_sm_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_sourcemedium.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_sourcemedium.json', print_r(json_encode($six_sm_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_sourcemedium.json', print_r(json_encode($six_sm_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_sourcemedium.json', print_r(json_encode($six_sm_array,true),true));
    }

    $current_six_sm_array = $prev_six_sm_array = $current_six_sm_organic_array =  $prev_six_sm_organic_array = $six_sm_array = array();
}

private function sourcemedium_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId){
    $current_user_sourcemedium_nine = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$today,$nine_month);  
    if(isset($current_user_sourcemedium_nine->totalResults) && $current_user_sourcemedium_nine->totalResults > 0){
        $current_nine_sm_array = array(
            'nine_current_sm_location'=> array_column ($current_user_sourcemedium_nine->rows , 0),
            'nine_current_sm_goal' => array_column ($current_user_sourcemedium_nine->rows , 1)
        );
    }else{
        $current_nine_sm_array = array(
            'nine_current_sm_location'=> array(),
            'nine_current_sm_goal' => array()
        );
    }

    $prev_user_sourcemedium_nine = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$prev_start_nine,$prev_end_nine); 
    if(isset($prev_user_sourcemedium_nine->totalResults) && $prev_user_sourcemedium_nine->totalResults > 0)  {
        $prev_nine_sm_array = array(
            'nine_prev_sm_location'=>array_column ($prev_user_sourcemedium_nine->rows , 0),
            'nine_prev_sm_goal' =>array_column ($prev_user_sourcemedium_nine->rows , 1)
        );
    }else{
        $prev_nine_sm_array = array(
            'nine_prev_sm_location'=>array(),
            'nine_prev_sm_goal' =>array()
        );
    }

    $current_organic_sourcemedium_nine = GoogleAnalyticsUsers::GoalCompletionSourceMediumOrganic($analytics, $profile,$today,$nine_month); 
    if(isset($current_organic_sourcemedium_nine->totalResults) && $current_organic_sourcemedium_nine->totalResults > 0){
        $current_nine_sm_organic_array = array(
            'nine_current_organic_sm_location'=>array_column ($current_organic_sourcemedium_nine->rows , 0),
            'nine_current_organic_sm_goal' =>array_column ($current_organic_sourcemedium_nine->rows , 1)
        );
    }else{
        $current_nine_sm_organic_array = array(
            'nine_current_organic_sm_location'=>array(),
            'nine_current_organic_sm_goal' =>array()
        );
    }

    $prev_organic_sourcemedium_nine = GoogleAnalyticsUsers::GoalCompletionSourceMediumOrganic($analytics, $profile,$prev_start_nine,$prev_end_nine); 
    if(isset($prev_organic_sourcemedium_nine->totalResults) && $prev_organic_sourcemedium_nine->totalResults > 0){
        $prev_nine_sm_organic_array = array(
            'nine_prev_organic_sm_location'=>array_column ($prev_organic_sourcemedium_nine->rows , 0),
            'nine_prev_organic_sm_goal' =>array_column ($prev_organic_sourcemedium_nine->rows , 1)
        );
    }else{
        $prev_nine_sm_organic_array = array(
            'nine_prev_organic_sm_location'=>array(),
            'nine_prev_organic_sm_goal' =>array()
        );
    }



    $nine_sm_array = array(
        'current_nine_sm_array'=>$current_nine_sm_array,
        'prev_nine_sm_array'=>$prev_nine_sm_array,
        'current_nine_sm_organic_array'=>$current_nine_sm_organic_array,
        'prev_nine_sm_organic_array'=>$prev_nine_sm_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_sourcemedium.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_sourcemedium.json', print_r(json_encode($nine_sm_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_sourcemedium.json', print_r(json_encode($nine_sm_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_sourcemedium.json', print_r(json_encode($nine_sm_array,true),true));
    }

    $current_nine_sm_array = $prev_nine_sm_array = $current_nine_sm_organic_array =  $prev_nine_sm_organic_array = $nine_sm_array = array();
}

private function sourcemedium_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId){
    $current_user_sourcemedium_year = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$today,$one_year);  
    if(isset($current_user_sourcemedium_year->totalResults) && $current_user_sourcemedium_year->totalResults > 0){
        $current_year_sm_array = array(
            'year_current_sm_location'=> array_column ($current_user_sourcemedium_year->rows , 0),
            'year_current_sm_goal' => array_column ($current_user_sourcemedium_year->rows , 1)
        );
    }else{
        $current_year_sm_array = array(
            'year_current_sm_location'=> array(),
            'year_current_sm_goal' => array()
        );
    }

    $prev_user_sourcemedium_year = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$prev_start_year,$prev_end_year);  
    if(isset($prev_user_sourcemedium_year->totalResults) && $prev_user_sourcemedium_year->totalResults > 0) {
        $prev_year_sm_array = array(
            'year_prev_sm_location'=>array_column ($prev_user_sourcemedium_year->rows , 0),
            'year_prev_sm_goal' =>array_column ($prev_user_sourcemedium_year->rows , 1)
        );
    }else{
        $prev_year_sm_array = array(
            'year_prev_sm_location'=>array(),
            'year_prev_sm_goal' =>array()
        );
    }

    $current_organic_sourcemedium_year = GoogleAnalyticsUsers::GoalCompletionSourceMediumOrganic($analytics, $profile,$today,$one_year); 
    if(isset($current_organic_sourcemedium_year->totalResults) && $current_organic_sourcemedium_year->totalResults > 0){
        $current_year_sm_organic_array = array(
            'year_current_organic_sm_location'=>array_column ($current_organic_sourcemedium_year->rows , 0),
            'year_current_organic_sm_goal' =>array_column ($current_organic_sourcemedium_year->rows , 1)
        );
    }else{
        $current_year_sm_organic_array = array(
            'year_current_organic_sm_location'=>array(),
            'year_current_organic_sm_goal' =>array()
        );
    }

    $prev_organic_sourcemedium_year = GoogleAnalyticsUsers::GoalCompletionSourceMediumOrganic($analytics, $profile,$prev_start_year,$prev_end_year); 
    if(isset($prev_organic_sourcemedium_year->totalResults) && $prev_organic_sourcemedium_year->totalResults > 0){
        $prev_year_sm_organic_array = array(
            'year_prev_organic_sm_location'=>array_column ($prev_organic_sourcemedium_year->rows , 0),
            'year_prev_organic_sm_goal' =>array_column ($prev_organic_sourcemedium_year->rows , 1)
        );
    }else{
        $prev_year_sm_organic_array = array(
            'year_prev_organic_sm_location'=>array(),
            'year_prev_organic_sm_goal' =>array()
        );
    }



    $year_sm_array = array(
        'current_year_sm_array'=>$current_year_sm_array,
        'prev_year_sm_array'=>$prev_year_sm_array,
        'current_year_sm_organic_array'=>$current_year_sm_organic_array,
        'prev_year_sm_organic_array'=>$prev_year_sm_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_sourcemedium.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_sourcemedium.json', print_r(json_encode($year_sm_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_sourcemedium.json', print_r(json_encode($year_sm_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_sourcemedium.json', print_r(json_encode($year_sm_array,true),true));
    }

    $current_year_sm_array = $prev_year_sm_array = $current_year_sm_organic_array =  $prev_year_sm_organic_array = $year_sm_array = array();
}

private function sourcemedium_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId){
    $current_user_sourcemedium_twoyear = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$today,$two_year);  
    if(isset($current_user_sourcemedium_twoyear->totalResults) && $current_user_sourcemedium_twoyear->totalResults > 0){
        $current_twoyear_sm_array = array(
            'twoyear_current_sm_location'=> array_column ($current_user_sourcemedium_twoyear->rows , 0),
            'twoyear_current_sm_goal' => array_column ($current_user_sourcemedium_twoyear->rows , 1)
        );
    }else{
        $current_twoyear_sm_array = array(
            'twoyear_current_sm_location'=> array(),
            'twoyear_current_sm_goal' => array()
        );
    }

    $prev_user_sourcemedium_twoyear = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$prev_start_two,$prev_end_two);   
    if(isset($prev_user_sourcemedium_twoyear->totalResults) && $prev_user_sourcemedium_twoyear->totalResults > 0){
        $prev_twoyear_sm_array = array(
            'twoyear_prev_sm_location'=>array_column ($prev_user_sourcemedium_twoyear->rows , 0),
            'twoyear_prev_sm_goal' =>array_column ($prev_user_sourcemedium_twoyear->rows , 1)
        );
    }else{
        $prev_twoyear_sm_array = array(
            'twoyear_prev_sm_location'=>array(),
            'twoyear_prev_sm_goal' =>array()
        );
    }

    $current_organic_sourcemedium_twoyear = GoogleAnalyticsUsers::GoalCompletionSourceMediumOrganic($analytics, $profile,$today,$two_year); 
    if(isset($current_organic_sourcemedium_twoyear->totalResults) && $current_organic_sourcemedium_twoyear->totalResults > 0){
        $current_twoyear_sm_organic_array = array(
            'twoyear_current_organic_sm_location'=>array_column ($current_organic_sourcemedium_twoyear->rows , 0),
            'twoyear_current_organic_sm_goal' =>array_column ($current_organic_sourcemedium_twoyear->rows , 1)
        );
    }else{
        $current_twoyear_sm_organic_array = array(
            'twoyear_current_organic_sm_location'=>array(),
            'twoyear_current_organic_sm_goal' =>array()
        );
    }

    $prev_organic_sourcemedium_twoyear = GoogleAnalyticsUsers::GoalCompletionSourceMediumOrganic($analytics, $profile,$prev_start_two,$prev_end_two); 
    if(isset($prev_organic_sourcemedium_twoyear->totalResults) && $prev_organic_sourcemedium_twoyear->totalResults > 0){
        $prev_twoyear_sm_organic_array = array(
            'twoyear_prev_organic_sm_location'=>array_column ($prev_organic_sourcemedium_twoyear->rows , 0),
            'twoyear_prev_organic_sm_goal' =>array_column ($prev_organic_sourcemedium_twoyear->rows , 1)
        );
    }else{
        $prev_twoyear_sm_organic_array = array(
            'twoyear_prev_organic_sm_location'=>array(),
            'twoyear_prev_organic_sm_goal' =>array()
        );
    }



    $twoyear_sm_array = array(
        'current_twoyear_sm_array'=>$current_twoyear_sm_array,
        'prev_twoyear_sm_array'=>$prev_twoyear_sm_array,
        'current_twoyear_sm_organic_array'=>$current_twoyear_sm_organic_array,
        'prev_twoyear_sm_organic_array'=>$prev_twoyear_sm_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_sourcemedium.json';

        if(file_exists($filename)){
            if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_sourcemedium.json', print_r(json_encode($twoyear_sm_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_sourcemedium.json', print_r(json_encode($twoyear_sm_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_sourcemedium.json', print_r(json_encode($twoyear_sm_array,true),true));
    }

    $current_twoyear_sm_array = $prev_twoyear_sm_array = $current_twoyear_sm_organic_array =  $prev_twoyear_sm_organic_array = $twoyear_sm_array = array();

    GoogleUpdate::updateTiming($campaignId,'analytics','analytics_type','1');
}


 public static function ecommerce_goal_graph($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId){
    $current_users_data = GoogleAnalyticsUsers::user_ecommerce_goals($analytics, $profile,$start_date,$end_date);    
    $outputRes = array_column ($current_users_data->rows , 0);
    $current_users = array_column ($current_users_data->rows , 1);

    $previous_users_data =  GoogleAnalyticsUsers::user_ecommerce_goals($analytics, $profile,$prev_start_date,$prev_end_date);
    $prevOutputResUsr = array_column ($previous_users_data->rows , 0);
    $previous_users = array_column($previous_users_data->rows , 1);

    $current_data = GoogleAnalyticsUsers::organic_ecommerce_goals($analytics, $profile,$start_date,$end_date);    
    $outputResOrganic = array_column ($current_data->rows , 0);
    $current_organic = array_column ($current_data->rows , 1);

    $previous_data =  GoogleAnalyticsUsers::organic_ecommerce_goals($analytics, $profile,$prev_start_date,$prev_end_date);
    $outputRes_prev = array_column ($previous_data->rows , 0);
    $previous_organic = array_column($previous_data->rows , 1);


    $from_dates_format  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes);  
    $from_dates_prev_format  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes_prev); 

    $final_organic_data = array_merge($previous_organic,$current_organic);
    $final_user_data = array_merge($previous_users,$current_users);
    $dates_format = array_merge($from_dates_prev_format,$from_dates_format);

    $array = array(
        'final_organic_data' =>$final_organic_data,
        'final_user_data' =>$final_user_data,
        'dates_format'=>$dates_format
    );

    if (file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        $ecom_graph = \config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/graph.json';
        if(file_exists($ecom_graph)){
            if(date("Y-m-d", filemtime($ecom_graph)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/graph.json', print_r(json_encode($array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/graph.json', print_r(json_encode($array,true),true));
        }
    }elseif (!file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/graph.json', print_r(json_encode($array,true),true));
    }

    $final_organic_data = $final_user_data = $dates_format = $array =  array();
 }

 public static function ecommerce_goal_statistics($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId){
    $current_data_stats = GoogleAnalyticsUsers::user_ecommerce_stats($analytics, $profile,$start_date,$end_date);    
    $outputResStats = array_column ($current_data_stats->rows , 0);
    $from_dates_stats  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputResStats);  
    $current_conversion_rate = array_column ($current_data_stats->rows , 1);
    $current_transactions = array_column ($current_data_stats->rows , 2);
    $current_revenue = array_column ($current_data_stats->rows , 3);
    $current_order_value = array_column ($current_data_stats->rows , 4);

    $previous_data_stats = GoogleAnalyticsUsers::user_ecommerce_stats($analytics, $profile,$prev_start_date,$prev_end_date);    
    $outputRes_prev_stats = array_column ($previous_data_stats->rows , 0);
    $from_dates_prev_stats  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes_prev_stats);  
    $previous_conversion_rate = array_column ($previous_data_stats->rows , 1);
    $previous_transactions = array_column ($previous_data_stats->rows , 2);
    $previous_revenue = array_column ($previous_data_stats->rows , 3);
    $previous_order_value = array_column ($previous_data_stats->rows , 4);

    $conversionRate = array_merge($previous_conversion_rate,$current_conversion_rate);
    $transactions = array_merge($previous_transactions,$current_transactions);
    $revenue = array_merge($previous_revenue,$current_revenue);
    $order_value = array_merge($previous_order_value,$current_order_value);
    $dates = array_merge($from_dates_prev_stats,$from_dates_stats);

    $current_data_organicstats = GoogleAnalyticsUsers::organic_ecommerce_stats($analytics, $profile,$start_date,$end_date);    
    $outputResorganicStats = array_column ($current_data_organicstats->rows , 0);
    $from_dates_organicstats  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputResorganicStats);  
    $current_conversion_rate_organic = array_column ($current_data_organicstats->rows , 1);
    $current_transactions_organic = array_column ($current_data_organicstats->rows , 2);
    $current_revenue_organic = array_column ($current_data_organicstats->rows , 3);
    $current_order_value_organic = array_column ($current_data_organicstats->rows , 4);

    $previous_data_organicstats =  GoogleAnalyticsUsers::organic_ecommerce_stats($analytics, $profile,$prev_start_date,$prev_end_date);
    $outputRes_prev_organicstats = array_column ($previous_data_organicstats->rows , 0);
    $from_dates_prev_organicstats  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $outputRes_prev_organicstats);
    $previous_conversion_rate_organic = array_column($previous_data_organicstats->rows , 1);
    $previous_transactions_organic = array_column($previous_data_organicstats->rows , 2);
    $previous_revenue_organic = array_column($previous_data_organicstats->rows , 3);
    $previous_order_value_organic = array_column($previous_data_organicstats->rows , 4);

    $conversionRate_organic = array_merge($previous_conversion_rate_organic,$current_conversion_rate_organic);
    $transactions_organic = array_merge($previous_transactions_organic,$current_transactions_organic);
    $revenue_organic = array_merge($previous_revenue_organic,$current_revenue_organic);
    $order_value_organic = array_merge($previous_order_value_organic,$current_order_value_organic);
    $dates_organic = array_merge($from_dates_prev_organicstats,$from_dates_organicstats);


    $statistics_array = array(
        'dates'=>$dates,
        'conversionRate' =>$conversionRate,
        'transactions' =>$transactions,
        'revenue' =>$revenue,
        'order_value' =>$order_value,
        'dates_organic'=>$dates_organic,
        'conversionRate_organic' =>$conversionRate_organic,
        'transactions_organic' =>$transactions_organic,
        'revenue_organic' =>$revenue_organic,
        'order_value_organic' =>$order_value_organic
    );

    if (file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        $ecom_stats = \config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/statistics.json';

        if(file_exists($ecom_stats)){
            if(date("Y-m-d", filemtime($ecom_stats)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/statistics.json', print_r(json_encode($statistics_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/statistics.json', print_r(json_encode($statistics_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/statistics.json', print_r(json_encode($statistics_array,true),true));
    }

    $dates = $conversionRate = $transactions = $revenue = $order_value =  $dates_organic = $conversionRate_organic = $transactions_organic = $revenue_organic = $order_value_organic = $statistics_array =  array();

 }

 public static function ecommerce_product_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId){
    $current_one = GoogleAnalyticsUsers::users_product($analytics, $profile,$today,$one_month); 
    if($current_one->totalResults > 0){
        $current_one_array = array(
            'one_current_product'=> array_column ($current_one->rows , 0),
            'one_current_quantity' => array_column ($current_one->rows , 1)
        );
    }else{
        $current_one_array = array(
            'one_current_product'=> array(),
            'one_current_quantity' => array()
        );
    }


    $prev_user_one = GoogleAnalyticsUsers::users_product($analytics, $profile,$prev_start_one,$prev_end_one);  
    if($prev_user_one->totalResults > 0){
        $prev_one_array = array(
            'one_prev_product'=>array_column ($prev_user_one->rows , 0),
            'one_prev_quantity' =>array_column ($prev_user_one->rows , 1)
        );
    }else{
        $prev_one_array = array(
            'one_prev_product'=>array(),
            'one_prev_quantity' =>array()
        );
    } 

    $current_organic_one = GoogleAnalyticsUsers::organic_product($analytics, $profile,$today,$one_month);  
    if($current_organic_one->totalResults > 0){
        $current_one_organic_array = array(
            'one_current_organic_product'=>array_column ($current_organic_one->rows , 0),
            'one_current_organic_quantity' =>array_column ($current_organic_one->rows , 1)
        );
    }else{
        $current_one_organic_array = array(
            'one_current_organic_product'=>array(),
            'one_current_organic_quantity' =>array()
        );
    }



    $prev_organic_one = GoogleAnalyticsUsers::organic_product($analytics, $profile,$prev_start_one,$prev_end_one);  
    if($prev_organic_one->totalResults > 0){
        $prev_one_organic_array = array(
            'one_previous_organic_product'=>array_column ($prev_organic_one->rows , 0),
            'one_previous_organic_quantity' =>array_column ($prev_organic_one->rows , 1)
        );
    }else{
        $prev_one_organic_array = array(
            'one_previous_organic_product'=>array(),
            'one_previous_organic_quantity' =>array()
        );
    }



    $one_array = array(
        'current_one_array'=>$current_one_array,
        'prev_one_array'=>$prev_one_array,
        'current_one_organic_array'=>$current_one_organic_array,
        'prev_one_organic_array'=>$prev_one_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        $ecom_one_month = \config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/one_month_product.json';

        if(file_exists($ecom_one_month)){
            if(date("Y-m-d", filemtime($ecom_one_month)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/one_month_product.json', print_r(json_encode($one_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/one_month_product.json', print_r(json_encode($one_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/one_month_product.json', print_r(json_encode($one_array,true),true));
    }

    $current_one_array = $prev_one_array = $current_one_organic_array =  $prev_one_organic_array = $one_array = array();
 }

 public static function ecommerce_product_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId){
    $current_three = GoogleAnalyticsUsers::users_product($analytics, $profile,$today,$three_month); 
    if($current_three->totalResults > 0){
        $current_three_array = array(
            'three_current_product'=> array_column ($current_three->rows , 0),
            'three_current_quantity' => array_column ($current_three->rows , 1)
        );
    }else{
        $current_three_array = array(
            'three_current_product'=> array(),
            'three_current_quantity' => array()
        );
    }


    $prev_user_three = GoogleAnalyticsUsers::users_product($analytics, $profile,$prev_start_three,$prev_end_three);  
    if($prev_user_three->totalResults > 0){
        $prev_three_array = array(
            'three_prev_product'=>array_column ($prev_user_three->rows , 0),
            'three_prev_quantity' =>array_column ($prev_user_three->rows , 1)
        );
    }else{
        $prev_three_array = array(
            'three_prev_product'=>array(),
            'three_prev_quantity' =>array()
        );
    } 

    $current_organic_three = GoogleAnalyticsUsers::organic_product($analytics, $profile,$today,$three_month);  
    if($current_organic_three->totalResults > 0){
        $current_three_organic_array = array(
            'three_current_organic_product'=>array_column ($current_organic_three->rows , 0),
            'three_current_organic_quantity' =>array_column ($current_organic_three->rows , 1)
        );
    }else{
        $current_three_organic_array = array(
            'three_current_organic_product'=>array(),
            'three_current_organic_quantity' =>array()
        );
    }



    $prev_organic_three = GoogleAnalyticsUsers::organic_product($analytics, $profile,$prev_start_three,$prev_end_three);  
    if($prev_organic_three->totalResults > 0){
        $prev_three_organic_array = array(
            'three_previous_organic_product'=>array_column ($prev_organic_three->rows , 0),
            'three_previous_organic_quantity' =>array_column ($prev_organic_three->rows , 1)
        );
    }else{
        $prev_three_organic_array = array(
            'three_previous_organic_product'=>array(),
            'three_previous_organic_quantity' =>array()
        );
    }



    $three_array = array(
        'current_three_array'=>$current_three_array,
        'prev_three_array'=>$prev_three_array,
        'current_three_organic_array'=>$current_three_organic_array,
        'prev_three_organic_array'=>$prev_three_organic_array        
    );


    if (file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        $ecom_three_month = \config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/three_month_product.json';

        if(file_exists($ecom_three_month)){
            if(date("Y-m-d", filemtime($ecom_three_month)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/three_month_product.json', print_r(json_encode($three_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/three_month_product.json', print_r(json_encode($three_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/three_month_product.json', print_r(json_encode($three_array,true),true));
    }

    $current_three_array = $prev_three_array = $current_three_organic_array =  $prev_three_organic_array = $three_array = array();
 }


 public static function ecommerce_product_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId){
    $current_six = GoogleAnalyticsUsers::users_product($analytics, $profile,$today,$six_month); 
    if($current_six->totalResults > 0){
        $current_six_array = array(
            'six_current_product'=> array_column ($current_six->rows , 0),
            'six_current_quantity' => array_column ($current_six->rows , 1)
        );
    }else{
        $current_six_array = array(
            'six_current_product'=> array(),
            'six_current_quantity' => array()
        );
    }


    $prev_user_six = GoogleAnalyticsUsers::users_product($analytics, $profile,$prev_start_six,$prev_end_six);  
    if($prev_user_six->totalResults > 0){
        $prev_six_array = array(
            'six_prev_product'=>array_column ($prev_user_six->rows , 0),
            'six_prev_quantity' =>array_column ($prev_user_six->rows , 1)
        );
    }else{
        $prev_six_array = array(
            'six_prev_product'=>array(),
            'six_prev_quantity' =>array()
        );
    } 

    $current_organic_six = GoogleAnalyticsUsers::organic_product($analytics, $profile,$today,$six_month);  
    if($current_organic_six->totalResults > 0){
        $current_six_organic_array = array(
            'six_current_organic_product'=>array_column ($current_organic_six->rows , 0),
            'six_current_organic_quantity' =>array_column ($current_organic_six->rows , 1)
        );
    }else{
        $current_six_organic_array = array(
            'six_current_organic_product'=>array(),
            'six_current_organic_quantity' =>array()
        );
    }



    $prev_organic_six = GoogleAnalyticsUsers::organic_product($analytics, $profile,$prev_start_six,$prev_end_six);  
    if($prev_organic_six->totalResults > 0){
        $prev_six_organic_array = array(
            'six_previous_organic_product'=>array_column ($prev_organic_six->rows , 0),
            'six_previous_organic_quantity' =>array_column ($prev_organic_six->rows , 1)
        );
    }else{
        $prev_six_organic_array = array(
            'six_previous_organic_product'=>array(),
            'six_previous_organic_quantity' =>array()
        );
    }



    $six_array = array(
        'current_six_array'=>$current_six_array,
        'prev_six_array'=>$prev_six_array,
        'current_six_organic_array'=>$current_six_organic_array,
        'prev_six_organic_array'=>$prev_six_organic_array        
    );

    if (file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        $ecom_six_month = \config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/six_month_product.json';

        if(file_exists($ecom_six_month)){
            if(date("Y-m-d", filemtime($ecom_six_month)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/six_month_product.json', print_r(json_encode($six_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/six_month_product.json', print_r(json_encode($six_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/six_month_product.json', print_r(json_encode($six_array,true),true));
    }

    $current_six_array = $prev_six_array = $current_six_organic_array =  $prev_six_organic_array = $six_array = array();
 }

 public static function ecommerce_product_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId){
    $current_nine = GoogleAnalyticsUsers::users_product($analytics,$profile,$today,$nine_month); 
    if($current_nine->totalResults > 0){
        $current_nine_array = array(
            'nine_current_product'=> array_column ($current_nine->rows , 0),
            'nine_current_quantity' => array_column ($current_nine->rows , 1)
        );
    }else{
        $current_nine_array = array(
            'nine_current_product'=> array(),
            'nine_current_quantity' => array()
        );
    }


    $prev_user_nine = GoogleAnalyticsUsers::users_product($analytics, $profile,$prev_start_nine,$prev_end_nine);  
    if($prev_user_nine->totalResults > 0){
        $prev_nine_array = array(
            'nine_prev_product'=>array_column ($prev_user_nine->rows , 0),
            'nine_prev_quantity' =>array_column ($prev_user_nine->rows , 1)
        );
    }else{
        $prev_nine_array = array(
            'nine_prev_product'=>array(),
            'nine_prev_quantity' =>array()
        );
    } 

    $current_organic_nine = GoogleAnalyticsUsers::organic_product($analytics, $profile,$today,$nine_month);  
    if($current_organic_nine->totalResults > 0){
        $current_nine_organic_array = array(
            'nine_current_organic_product'=>array_column ($current_organic_nine->rows , 0),
            'nine_current_organic_quantity' =>array_column ($current_organic_nine->rows , 1)
        );
    }else{
        $current_nine_organic_array = array(
            'nine_current_organic_product'=>array(),
            'nine_current_organic_quantity' =>array()
        );
    }



    $prev_organic_nine = GoogleAnalyticsUsers::organic_product($analytics, $profile,$prev_start_nine,$prev_end_nine);  
    if($prev_organic_nine->totalResults > 0){
        $prev_nine_organic_array = array(
            'nine_previous_organic_product'=>array_column ($prev_organic_nine->rows , 0),
            'nine_previous_organic_quantity' =>array_column ($prev_organic_nine->rows , 1)
        );
    }else{
        $prev_nine_organic_array = array(
            'nine_previous_organic_product'=>array(),
            'nine_previous_organic_quantity' =>array()
        );
    }



    $nine_array = array(
        'current_nine_array'=>$current_nine_array,
        'prev_nine_array'=>$prev_nine_array,
        'current_nine_organic_array'=>$current_nine_organic_array,
        'prev_nine_organic_array'=>$prev_nine_organic_array        
    );

    if (file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        $ecom_nine_month = \config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/nine_month_product.json';

        if(file_exists($ecom_nine_month)){
            if(date("Y-m-d", filemtime($ecom_nine_month)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/nine_month_product.json', print_r(json_encode($nine_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/nine_month_product.json', print_r(json_encode($nine_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/nine_month_product.json', print_r(json_encode($nine_array,true),true));
    }

    $current_nine_array = $prev_nine_array = $current_nine_organic_array =  $prev_nine_organic_array = $nine_array = array();
 } 

 public static function ecommerce_product_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId){
    $current_year = GoogleAnalyticsUsers::users_product($analytics,$profile,$today,$one_year); 
    if($current_year->totalResults > 0){
        $current_year_array = array(
            'year_current_product'=> array_column ($current_year->rows , 0),
            'year_current_quantity' => array_column ($current_year->rows , 1)
        );
    }else{
        $current_year_array = array(
            'year_current_product'=> array(),
            'year_current_quantity' => array()
        );
    }


    $prev_user_year = GoogleAnalyticsUsers::users_product($analytics, $profile,$prev_start_year,$prev_end_year);  
    if($prev_user_year->totalResults > 0){
        $prev_year_array = array(
            'year_prev_product'=>array_column ($prev_user_year->rows , 0),
            'year_prev_quantity' =>array_column ($prev_user_year->rows , 1)
        );
    }else{
        $prev_year_array = array(
            'year_prev_product'=>array(),
            'year_prev_quantity' =>array()
        );
    } 

    $current_organic_year = GoogleAnalyticsUsers::organic_product($analytics, $profile,$today,$one_year);  
    if($current_organic_year->totalResults > 0){
        $current_year_organic_array = array(
            'year_current_organic_product'=>array_column ($current_organic_year->rows , 0),
            'year_current_organic_quantity' =>array_column ($current_organic_year->rows , 1)
        );
    }else{
        $current_year_organic_array = array(
            'year_current_organic_product'=>array(),
            'year_current_organic_quantity' =>array()
        );
    }



    $prev_organic_year = GoogleAnalyticsUsers::organic_product($analytics, $profile,$prev_start_year,$prev_end_year);  
    if($prev_organic_year->totalResults > 0){
        $prev_year_organic_array = array(
            'year_previous_organic_product'=>array_column ($prev_organic_year->rows , 0),
            'year_previous_organic_quantity' =>array_column ($prev_organic_year->rows , 1)
        );
    }else{
        $prev_year_organic_array = array(
            'year_previous_organic_product'=>array(),
            'year_previous_organic_quantity' =>array()
        );
    }



    $year_array = array(
        'current_year_array'=>$current_year_array,
        'prev_year_array'=>$prev_year_array,
        'current_year_organic_array'=>$current_year_organic_array,
        'prev_year_organic_array'=>$prev_year_organic_array        
    );

    if (file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        $ecom_year = \config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/year_product.json';

        if(file_exists($ecom_year)){
            if(date("Y-m-d", filemtime($ecom_year)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/year_product.json', print_r(json_encode($year_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/year_product.json', print_r(json_encode($year_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/year_product.json', print_r(json_encode($year_array,true),true));
    }

    $current_year_array = $prev_year_array = $current_year_organic_array =  $prev_year_organic_array = $year_array = array();
 } 

 public static function ecommerce_product_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId){
    $current_two_year = GoogleAnalyticsUsers::users_product($analytics,$profile,$today,$two_year); 
    if($current_two_year->totalResults > 0){
        $current_two_year_array = array(
            'two_year_current_product'=> array_column ($current_two_year->rows , 0),
            'two_year_current_quantity' => array_column ($current_two_year->rows , 1)
        );
    }else{
        $current_two_year_array = array(
            'two_year_current_product'=> array(),
            'two_year_current_quantity' => array()
        );
    }


    $prev_user_two_year = GoogleAnalyticsUsers::users_product($analytics, $profile,$prev_start_two,$prev_end_two);  
    if($prev_user_two_year->totalResults > 0){
        $prev_two_year_array = array(
            'two_year_prev_product'=>array_column ($prev_user_two_year->rows , 0),
            'two_year_prev_quantity' =>array_column ($prev_user_two_year->rows , 1)
        );
    }else{
        $prev_two_year_array = array(
            'two_year_prev_product'=>array(),
            'two_year_prev_quantity' =>array()
        );
    } 

    $current_organic_two_year = GoogleAnalyticsUsers::organic_product($analytics, $profile,$today,$two_year);  
    if($current_organic_two_year->totalResults > 0){
        $current_two_year_organic_array = array(
            'two_year_current_organic_product'=>array_column ($current_organic_two_year->rows , 0),
            'two_year_current_organic_quantity' =>array_column ($current_organic_two_year->rows , 1)
        );
    }else{
        $current_two_year_organic_array = array(
            'two_year_current_organic_product'=>array(),
            'two_year_current_organic_quantity' =>array()
        );
    }



    $prev_organic_two_year = GoogleAnalyticsUsers::organic_product($analytics, $profile,$prev_start_two,$prev_end_two);  
    if($prev_organic_two_year->totalResults > 0){
        $prev_two_year_organic_array = array(
            'two_year_previous_organic_product'=>array_column ($prev_organic_two_year->rows , 0),
            'two_year_previous_organic_quantity' =>array_column ($prev_organic_two_year->rows , 1)
        );
    }else{
        $prev_two_year_organic_array = array(
            'two_year_previous_organic_product'=>array(),
            'two_year_previous_organic_quantity' =>array()
        );
    }



    $two_year_array = array(
        'current_two_year_array'=>$current_two_year_array,
        'prev_two_year_array'=>$prev_two_year_array,
        'current_two_year_organic_array'=>$current_two_year_organic_array,
        'prev_two_year_organic_array'=>$prev_two_year_organic_array        
    );

    if (file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        $ecom_two_year = \config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/two_year_product.json';

        if(file_exists($ecom_two_year)){
            if(date("Y-m-d", filemtime($ecom_two_year)) != date('Y-m-d')){
                file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/two_year_product.json', print_r(json_encode($two_year_array,true),true));
            }
        }else{
            file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/two_year_product.json', print_r(json_encode($two_year_array,true),true));
        }

    }elseif (!file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
        mkdir(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId, 0777, true);
        file_put_contents(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId.'/two_year_product.json', print_r(json_encode($two_year_array,true),true));
    }

    $current_two_year_array = $prev_two_year_array = $current_two_year_organic_array =  $prev_two_year_organic_array = $two_year_array = array();
 }

}
