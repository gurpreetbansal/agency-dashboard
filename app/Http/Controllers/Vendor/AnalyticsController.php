<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SemrushUserAccount;
use App\User;
use App\GoogleAnalyticsUsers;
use App\ActivityLog;
use App\ModuleByDateRange;
use App\ProjectCompareGraph;
use App\Error;
use Auth;
use Exception;
use \Illuminate\Pagination\LengthAwarePaginator;

class AnalyticsController extends Controller {

  public function cron_graph(){
    try{
      $getUser = SemrushUserAccount::whereIn('id',[20,21,22,24,25,30,49,53,71])->where('google_analytics_id','!=',NULL)->where('status',0)->orderBy('id','asc')->get();

      if(!empty($getUser)){

        $start_date = date('Y-m-d');
        $end_date =  date('Y-m-d', strtotime("-2 years", strtotime(date('Y-m-d'))));

        $day_diff  =    strtotime($end_date) - strtotime($start_date);
        $count_days     =   floor($day_diff/(60*60*24));

        $start_data   =   date('Y-m-d', strtotime($end_date.' '.$count_days.' days'));


        $prev_start_date = date('Y-m-d', strtotime("-1 day", strtotime($end_date)));
        $prev_end_date = date('Y-m-d', strtotime("-2 years", strtotime($prev_start_date))); 

        $current_period     =   date('d-m-Y', strtotime($end_date)).' to '.date('d-m-Y', strtotime($start_date));
        $previous_period    =   date('d-m-Y', strtotime(date('Y-m-d',strtotime($prev_end_date)))).' to '.date('d-m-Y', strtotime($prev_start_date));


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


        foreach($getUser as $semrush_data){

          $getAnalytics =     GoogleAnalyticsUsers::where('id',$semrush_data->google_account_id)->first();



          $user_id = $getAnalytics->user_id;
          $campaignId = $semrush_data->id;

          if(!empty($getAnalytics)){
            $status = 1;
            $client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);

            $refresh_token  = $getAnalytics->google_refresh_token;

            /*if refresh token expires*/
            if ($client->isAccessTokenExpired()) {
              GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
            }

            $getAnalyticsId = SemrushUserAccount::where('id',$campaignId)->where('user_id',$user_id)->first();

            if(isset($getAnalyticsId->google_analytics_account)){
              $analyticsCategoryId = $getAnalyticsId->google_analytics_account->category_id;


              $analytics = new \Google_Service_Analytics($client);


              $profile = GoogleAnalyticsUsers::getProfileId($campaignId,$analyticsCategoryId);



              $startDaTeCheck = date('Y-m-d');
              $endDaTeCheck = date('Y-m-d',strtotime("-1 week"));


              $error  =   array();

              try {
                $current_data_check = GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile,$startDaTeCheck,$endDaTeCheck); 
              } catch(\Exception $j) {
               $error = json_decode($j->getMessage(), true);
             }


             if(!empty($error['error']['code'])){
              Error::create([
                'request_id'=>$campaignId,
                'code'=>$error['error']['code'],
                'message'=>$error['error']['message'],
                'reason'=>$error['error']['errors'][0]['reason'],
                'module'=>1
              ]);
            }else{

              if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
                $graphfilename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/graph.json';
                if(file_exists($graphfilename)){
                  if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){
                   $this->goal_completion_graph($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
                 }
               }else{
                $this->goal_completion_graph($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
              }
            }
            elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
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
          }
          elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
            mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
            $this->goal_completion_statistics($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
          }

                        //locations data
                   //  if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
                   //      $location_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_locations.json';
                   //      if(file_exists($location_file)){
                   //          if(date("Y-m-d", filemtime($location_file)) != date('Y-m-d')){
                   //              $this->location_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
                   //          }
                   //      }else{
                   //         $this->location_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
                   //     }
                   // }
                   // elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
                   //  mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
          $this->location_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
               // }

          $this->location_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);

          $this->location_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);

          $this->location_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
   // }

             //year location
   //  if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
   //      $location_year = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_locations.json';
   //      if(file_exists($location_year)){
   //          if(date("Y-m-d", filemtime($location_year)) != date('Y-m-d')){
   //              $this->location_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
   //          }
   //      }else{
   //         $this->location_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
   //     }
   // }
   // elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
   //  mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
          $this->location_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
//}

             //two year location
// if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     $location_twoyear = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_locations.json';
//     if(file_exists($location_twoyear)){
//         if(date("Y-m-d", filemtime($location_twoyear)) != date('Y-m-d')){
//             $this->location_two_year($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
//         }
//     }else{
//       $this->location_two_year($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
//   }
// }
// elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
          $this->location_two_year($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
//}


  //sourcemedium data
// if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     $sourcemedium_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_sourcemedium.json';
//     if(file_exists($sourcemedium_file)){
//         if(date("Y-m-d", filemtime($sourcemedium_file)) != date('Y-m-d')){
//             $this->sourcemedium_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
//         }
//     }else{
//        $this->sourcemedium_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
//    }
// }
// elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
          $this->sourcemedium_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
//}



 //three sourcemedium
// if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     $sm_three_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_sourcemedium.json';
//     if(file_exists($sm_three_file)){
//         if(date("Y-m-d", filemtime($sm_three_file)) != date('Y-m-d')){
//             $this->sourcemedium_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
//         }
//     }else{
//      $this->sourcemedium_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
//  }
// }
// elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
          $this->sourcemedium_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
//}

 //six sourcemedium
// if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     $sm_six_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_sourcemedium.json';
//     if(file_exists($sm_six_file)){
//         if(date("Y-m-d", filemtime($sm_six_file)) != date('Y-m-d')){
//             $this->sourcemedium_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
//         }
//     }else{
//      $this->sourcemedium_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
//  }
// }
// elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
          $this->sourcemedium_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
//}


//nine sourcemedium
// if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     $sm_nine_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_sourcemedium.json';
//     if(file_exists($sm_nine_file)){
//         if(date("Y-m-d", filemtime($sm_nine_file)) != date('Y-m-d')){
//             $this->sourcemedium_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
//         }
//     }else{
//      $this->sourcemedium_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
//  }
// }
// elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
          $this->sourcemedium_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
//}


//year sourcemedium
// if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     $sm_year_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_sourcemedium.json';
//     if(file_exists($sm_year_file)){
//         if(date("Y-m-d", filemtime($sm_year_file)) != date('Y-m-d')){
//             $this->sourcemedium_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
//         }
//     }else{
//      $this->sourcemedium_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
//  }
// }
// elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
          $this->sourcemedium_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
//}


//two year sourcemedium
// if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     $sm_twoyear_file = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_sourcemedium.json';
//     if(file_exists($sm_twoyear_file)){
//         if(date("Y-m-d", filemtime($sm_twoyear_file)) != date('Y-m-d')){
//             $this->sourcemedium_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
//         }
//     }else{
//      $this->sourcemedium_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
//  }
// }
// elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
//     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
          $this->sourcemedium_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
//}

        }
      } else {
        $status = 0;
      }
    }
            } //end foreach
            
          }

        } catch (\Exception $e) {
         return $e->getMessage();
       }
     }


     private function goal_completion_graph($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId){
      $current_data = GoogleAnalyticsUsers::OrganicgoalCompletion($analytics, $profile,$start_date,$end_date);    
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
      if($prev_user_location_month->totalResults > 0){
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
      if($current_organic_location_month->totalResults > 0){
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
      if($prev_organic_location_month->totalResults > 0){
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


    // if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_locations.json';

    //     if(file_exists($filename)){
    //         if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
    //             file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_locations.json', print_r(json_encode($one_array,true),true));
    //         }
    //     }else{
    //         file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_locations.json', print_r(json_encode($one_array,true),true));
    //     }

    // }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
      file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_locations.json', print_r(json_encode($one_array,true),true));
    //}

      $current_month_array = $prev_month_array = $current_month_organic_array =  $prev_month_organic_array = $one_array = array();
    }


    private function location_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId){
      $current_user_location_three = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$today,$three_month);  
      if($current_user_location_three->totalResults > 0){
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
      if($prev_user_location_three->totalResults > 0){
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
      if($current_organic_location_three->totalResults > 0){
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
      if($prev_organic_location_three->totalResults > 0){
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


    // if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_month_locations.json';

    //     if(file_exists($filename)){
    //         if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
    //             file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_month_locations.json', print_r(json_encode($three_array,true),true));
    //         }
    //     }else{
    //         file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_month_locations.json', print_r(json_encode($three_array,true),true));
    //     }

    // }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
      file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_month_locations.json', print_r(json_encode($three_array,true),true));
  //  }

      $current_three_array = $prev_three_array = $current_three_organic_array =  $prev_three_organic_array = $three_array = array();
    }

    private function location_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId){
      $current_user_location_six = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$today,$six_month);  
      if($current_user_location_six->totalResults > 0){
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
      if($prev_user_location_six->totalResults > 0){
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
      if($current_organic_location_six->totalResults > 0){
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
      if($prev_organic_location_six->totalResults > 0){
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


    // if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_month_locations.json';

    //     if(file_exists($filename)){
    //         if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
    //             file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_month_locations.json', print_r(json_encode($six_array,true),true));
    //         }
    //     }else{
    //         file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_month_locations.json', print_r(json_encode($six_array,true),true));
    //     }

    // }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
      file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_month_locations.json', print_r(json_encode($six_array,true),true));
  //  }

      $current_six_array = $prev_six_array = $current_six_organic_array =  $prev_six_organic_array = $six_array = array();
    }

    private function location_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId){
      $current_user_location_nine = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$today,$nine_month);  
      if($current_user_location_nine->totalResults > 0){
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
      if($prev_user_location_nine->totalResults > 0){
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
      if($current_organic_location_nine->totalResults > 0){
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
      if($prev_organic_location_nine->totalResults > 0){
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


    // if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_month_locations.json';

    //     if(file_exists($filename)){
    //         if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
    //             file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_month_locations.json', print_r(json_encode($nine_array,true),true));
    //         }
    //     }else{
    //         file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_month_locations.json', print_r(json_encode($nine_array,true),true));
    //     }

    // }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
      file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_month_locations.json', print_r(json_encode($nine_array,true),true));
    //}

      $current_nine_array = $prev_nine_array = $current_nine_organic_array =  $prev_nine_organic_array = $nine_array = array();
    }

    private function location_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId){
      $current_user_location_year = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$today,$one_year);  
      if($current_user_location_year->totalResults > 0){
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

      if($prev_user_location_year->totalResults > 0){
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

      if($current_organic_location_year->totalResults > 0){
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
      if($prev_organic_location_year->totalResults > 0){
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


    // if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_locations.json';

    //     if(file_exists($filename)){
    //         if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
    //             file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_locations.json', print_r(json_encode($year_array,true),true));
    //         }
    //     }else{
    //         file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_locations.json', print_r(json_encode($year_array,true),true));
    //     }

    // }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
      file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_locations.json', print_r(json_encode($year_array,true),true));
    //}

      $current_year_array = $prev_year_array = $current_year_organic_array =  $prev_year_organic_array = $year_array = array();
    }

    private function location_two_year($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId){
      $current_user_location_twoyear = GoogleAnalyticsUsers::GoalCompletionLocation($analytics, $profile,$today,$two_year);  
      if($current_user_location_twoyear->totalResults > 0){
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
      if($prev_user_location_twoyear->totalResults > 0){
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
      if($current_organic_location_twoyear->totalResults > 0){
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
      if($prev_organic_location_twoyear->totalResults > 0){
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


    // if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_locations.json';

    //     if(file_exists($filename)){
    //         if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
    //             file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_locations.json', print_r(json_encode($twoyear_array,true),true));
    //         }
    //     }else{
    //         file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_locations.json', print_r(json_encode($twoyear_array,true),true));
    //     }

    // }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
      file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_locations.json', print_r(json_encode($twoyear_array,true),true));
    //}

      $current_twoyear_array = $prev_year_array = $prev_twoyear_organic_array =  $prev_twoyear_organic_array = $twoyear_array = array();
    }

    private function sourcemedium_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId){
      $current_user_sourcemedium_month = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$today,$one_month);  
      if($current_user_sourcemedium_month->totalResults > 0){
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
      if($prev_user_sourcemedium_month->totalResults > 0){
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
      if($current_organic_sourcemedium_month->totalResults > 0) {
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
      if($prev_organic_sourcemedium_month->totalResults > 0){
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


    // if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_sourcemedium.json';

    //     if(file_exists($filename)){
    //         if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
    //             file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_sourcemedium.json', print_r(json_encode($one_sm_array,true),true));
    //         }
    //     }else{
    //         file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_sourcemedium.json', print_r(json_encode($one_sm_array,true),true));
    //     }

    // }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
      file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/month_sourcemedium.json', print_r(json_encode($one_sm_array,true),true));
 //   }

      $current_month_sm_array = $prev_month_sm_array = $current_month_sm_organic_array =  $prev_month_sm_organic_array = $one_sm_array = array();
    }


    private function sourcemedium_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId){
      $current_user_sourcemedium_three = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$today,$three_month);  
      if($current_user_sourcemedium_three->totalResults > 0){
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
      if($prev_user_sourcemedium_three->totalResults > 0){
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
      if($current_organic_sourcemedium_three->totalResults > 0){
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
      if($prev_organic_sourcemedium_three->totalResults > 0){
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


    // if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_sourcemedium.json';

    //     if(file_exists($filename)){
    //         if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
    //             file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_sourcemedium.json', print_r(json_encode($three_sm_array,true),true));
    //         }
    //     }else{
    //         file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_sourcemedium.json', print_r(json_encode($three_sm_array,true),true));
    //     }

    // }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
      file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/three_sourcemedium.json', print_r(json_encode($three_sm_array,true),true));
    //}

      $current_three_sm_array = $prev_three_sm_array = $current_three_sm_organic_array =  $prev_three_sm_organic_array = $three_sm_array = array();
    }

    private function sourcemedium_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId){
      $current_user_sourcemedium_six = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$today,$six_month);  
      if($current_user_sourcemedium_six->totalResults > 0){
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
      if($prev_user_sourcemedium_six->totalResults > 0){
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
      if($current_organic_sourcemedium_six->totalResults > 0){
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
      if($prev_organic_sourcemedium_six->totalResults > 0){
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


    // if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_sourcemedium.json';

    //     if(file_exists($filename)){
    //         if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
    //             file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_sourcemedium.json', print_r(json_encode($six_sm_array,true),true));
    //         }
    //     }else{
    //         file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_sourcemedium.json', print_r(json_encode($six_sm_array,true),true));
    //     }

    // }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
      file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/six_sourcemedium.json', print_r(json_encode($six_sm_array,true),true));
   // }

      $current_six_sm_array = $prev_six_sm_array = $current_six_sm_organic_array =  $prev_six_sm_organic_array = $six_sm_array = array();
    }

    private function sourcemedium_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId){
      $current_user_sourcemedium_nine = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$today,$nine_month);  
      if($current_user_sourcemedium_nine->totalResults > 0){
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
      if($prev_user_sourcemedium_nine->totalResults > 0)  {
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
      if($current_organic_sourcemedium_nine->totalResults > 0){
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
      if($prev_organic_sourcemedium_nine->totalResults > 0){
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


    // if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_sourcemedium.json';

    //     if(file_exists($filename)){
    //         if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
    //             file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_sourcemedium.json', print_r(json_encode($nine_sm_array,true),true));
    //         }
    //     }else{
    //         file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_sourcemedium.json', print_r(json_encode($nine_sm_array,true),true));
    //     }

    // }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
      file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/nine_sourcemedium.json', print_r(json_encode($nine_sm_array,true),true));
    //}

      $current_nine_sm_array = $prev_nine_sm_array = $current_nine_sm_organic_array =  $prev_nine_sm_organic_array = $nine_sm_array = array();
    }

    private function sourcemedium_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId){
      $current_user_sourcemedium_year = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$today,$one_year);  
      if($current_user_sourcemedium_year->totalResults > 0){
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
      if($prev_user_sourcemedium_year->totalResults > 0) {
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
      if($current_organic_sourcemedium_year->totalResults > 0){
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
      if($prev_organic_sourcemedium_year->totalResults > 0){
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


    // if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_sourcemedium.json';

    //     if(file_exists($filename)){
    //         if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
    //             file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_sourcemedium.json', print_r(json_encode($year_sm_array,true),true));
    //         }
    //     }else{
    //         file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_sourcemedium.json', print_r(json_encode($year_sm_array,true),true));
    //     }

    // }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
      file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/year_sourcemedium.json', print_r(json_encode($year_sm_array,true),true));
   // }

      $current_year_sm_array = $prev_year_sm_array = $current_year_sm_organic_array =  $prev_year_sm_organic_array = $year_sm_array = array();
    }

    private function sourcemedium_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId){
      $current_user_sourcemedium_twoyear = GoogleAnalyticsUsers::GoalCompletionSourceMedium($analytics, $profile,$today,$two_year);  
      if($current_user_sourcemedium_twoyear->totalResults > 0){
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
      if($prev_user_sourcemedium_twoyear->totalResults > 0){
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
      if($current_organic_sourcemedium_twoyear->totalResults > 0){
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
      if($prev_organic_sourcemedium_twoyear->totalResults > 0){
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


    // if (file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     $filename = \config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_sourcemedium.json';

    //     if(file_exists($filename)){
    //         if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
    //             file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_sourcemedium.json', print_r(json_encode($twoyear_sm_array,true),true));
    //         }
    //     }else{
    //         file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_sourcemedium.json', print_r(json_encode($twoyear_sm_array,true),true));
    //     }

    // }elseif (!file_exists(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId)) {
    //     mkdir(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId, 0777, true);
      file_put_contents(\config('app.FILE_PATH').'public/goalcompletion/'.$campaignId.'/twoyear_sourcemedium.json', print_r(json_encode($twoyear_sm_array,true),true));
   // }

      $current_twoyear_sm_array = $prev_twoyear_sm_array = $current_twoyear_sm_organic_array =  $prev_twoyear_sm_organic_array = $twoyear_sm_array = array();
    }



    public function ajax_get_traffic_data(Request $request){
      $today = date('Y-m-d');
      $campaign_id = $request['campaignId'];

      $getUser = SemrushUserAccount::where('id',$campaign_id)->first(); 

      if(Auth::user() <> null){
          $user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
          $role_id = User::get_user_role(Auth::user()->id); 
        }else{
          $user_id = $getUser->user_id;
          $role_id = User::get_user_role($user_id); 
        }


        $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
        if(!empty($getCompareChart)){
          $compare_status = $getCompareChart->compare_status;
        }else{
          $compare_status = 0;
        }

        $sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

        if (!file_exists(env('FILE_PATH')."public/analytics/".$campaign_id)) {
          $res['status'] = 0;
        } else {
          $url = env('FILE_PATH')."public/analytics/".$campaign_id.'/graph.json'; 
          $data = file_get_contents($url);
          $final = json_decode($data);

          $dates = $this->make_dates($campaign_id);

          $compare_status = $dates['compare_status'];
          $type = $dates['type'];
          $start_date = $dates['start_date'];
          $end_date = $dates['end_date'];
          $prev_date_1 = $dates['prev_date_1'];
          $prev_end_dates = $dates['prev_end_dates'];
          $default_duration = $dates['default_duration'];
          $start_date_1 = $dates['start_date_1'];
          $duration = $dates['duration'];

          if($type == 'day'){
            $duration = ModuleByDateRange::calculate_days($start_date,$end_date);
            for($i=1;$i<=$duration;$i++){
              if($i==1){  
                $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
                $prev_start_date = date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_date_1))); 
              }else{
                $start_date = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
                $prev_start_date = date('Y-m-d',strtotime('+1 day',strtotime($prev_end_date))); 
              }

              $end_date = date('Y-m-d',strtotime('+0 day',strtotime($start_date)));    
              $prev_end_date = date('Y-m-d',strtotime('+0 day',strtotime($prev_start_date))); 


              $result[] = $this->traffic_growth_chart($start_date,$end_date,$prev_start_date,$prev_end_date,$campaign_id,$type);
              if($default_duration == 1 || $default_duration == 3){
                $current[] = date('M d, Y',strtotime($start_date));
              }else{
                $current[] = date('M Y',strtotime($start_date));
              }
              $current_dates[] = date('l, F d, Y',strtotime($start_date));
              $current_prev_dates[] = date('l, F d, Y',strtotime($prev_start_date));
            }
          }

          if($type == 'week'){
            $duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
            $i =1;
            $csd = $prev_end_dates;
            $sd = $start_date;
            /*infinite for loop*/
            for( ; ;){
              $start = $sd;
              $previous_start = $csd;

              if($i == 1){
                if(date('D', strtotime($start)) == 'Sun'){
                  $end_date = date('Y-m-d',strtotime('saturday next week',strtotime($start)));
                }else{  
                  $end_date = date('Y-m-d',strtotime('saturday this week',strtotime($start)));
                }

                if(date('D', strtotime($previous_start)) == 'Sun'){
                  $previous_end = date('Y-m-d',strtotime('saturday next week',strtotime($previous_start)));
                }else{  
                  $previous_end = date('Y-m-d',strtotime('saturday this week',strtotime($previous_start)));
                }
              }else{
                $end_date = date('Y-m-d',strtotime('saturday next week',strtotime($start)));
                $previous_end = date('Y-m-d',strtotime('saturday next week',strtotime($previous_start)));
              }


              $sd = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
              $csd = date('Y-m-d',strtotime('+1 day',strtotime($previous_end)));


              if($previous_end > $start_date){
                $previousEnd = date('Y-m-d',strtotime('-1 day',strtotime($start_date))); 
              }else{
                $previousEnd = $previous_end;
              }

              /*to break the infinite loop at the last entry*/
              if($end_date  > date('Y-m-d')){
                $enddate = $today;
                $result[] = $this->traffic_growth_chart($start,$enddate,$previous_start,$previousEnd,$campaign_id,$type);
                if($default_duration == 1 || $default_duration == 3){
                  $current[] = date('M d, Y',strtotime($start));
                }else{
                  $current[] = date('M Y',strtotime($start));
                }
                $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($enddate));
                $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
                break; 
              }else{
                $result[] = $this->traffic_growth_chart($start,$end_date,$previous_start,$previousEnd,$campaign_id,$type);
                if($default_duration == 1 || $default_duration == 3){
                  $current[] = date('M d, Y',strtotime($start));
                }else{
                  $current[] = date('M Y',strtotime($start));
                }
                $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($end_date));
                $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
              }

              $i++;
            }
          }

          if($type == 'month'){
            $i = 1;
            $csd = $prev_end_dates;
            $sd = $start_date;
            for( ; ;){
              $start = $sd;
              $previous_start = $csd;

              $end_date = date('Y-m-d',strtotime(date("Y-m-t", strtotime($start))));
              $previous_end = date('Y-m-d',strtotime(date("Y-m-t", strtotime($previous_start))));


              $sd = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
              $csd = date('Y-m-d',strtotime('+1 day',strtotime($previous_end)));

              if($previous_end > $start_date){
                $previousEnd = date('Y-m-d',strtotime('-1 day',strtotime($start_date))); 
              }else{
                $previousEnd = $previous_end;
              }

              if($end_date  > date('Y-m-d')){
                $enddate = $today;

                $result[] = $this->traffic_growth_chart($start,$enddate,$previous_start,$previousEnd,$campaign_id,$type);
                if($default_duration == 1 || $default_duration == 3){
                  $current[] = date('M d, Y',strtotime($start));
                }else{
                  $current[] = date('M Y',strtotime($start));
                }
                $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($enddate));
                $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
                break;
              }else{
                $result[] = $this->traffic_growth_chart($start,$end_date,$previous_start,$previousEnd,$campaign_id,$type);
                if($default_duration == 1 || $default_duration == 3){
                  $current[] = date('M d, Y',strtotime($start));
                }else{
                  $current[] = date('M Y',strtotime($start));
                }
                $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($end_date));
                $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
              }
              $i++;
            }

          }     

          $current_data = array_column($result,'current_data');
          $prev_data = array_column($result,'prev_data');

          if(count($current_data) == 1){
            array_unshift( $current_data,null );
            $current_data = array_merge($current_data,array(null));
            $current = array_merge($current,array(''));
            array_unshift( $current,'' );
          }
          if(count($prev_data) == 1){
            array_unshift( $prev_data, null );
            $prev_data = array_merge($prev_data,array(null)); 
          }

          $current_period  = date('M d, Y', strtotime($start_date_1)).' - '.date('M d, Y', strtotime($today));
          $prev_period   = date('M d, Y', strtotime($prev_end_dates)).' - '.date('M d, Y', strtotime($prev_date_1));

          $res['from_datelabel'] = $current;
          $res['from_datelabels'] = $current_dates;
          $res['prev_from_datelabels'] = $current_prev_dates;
          $res['count_session'] = $current_data;
          $res['combine_session'] = $prev_data;
          $res['previous_period'] = $prev_period;
          $res['current_period'] = $current_period;
          $res['compare_status'] = $compare_status;
          $res['status'] = 1;

        }

        return response()->json($res);
      }

      public function ajax_get_traffic_metrics(Request $request){
        $today = date('Y-m-d');
        $today_new = date('Y-m-d');
        $campaign_id = $request['campaignId'];
        $sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');
        if (!file_exists(env('FILE_PATH')."public/analytics/".$campaign_id)) {
          $res['traffic_growth'] = '0';
          $res['total_sessions'] = '0';
          $res['total_pageviews'] = '0';
          $res['total_users'] = '0';
          $res['final_session'] = '0 vs 0';
          $res['final_pageviews'] = '0 vs 0';
          $res['final_users'] = '0 vs 0';
          $res['current_session'] = '0';
          $res['status'] = 0;
        } else {
         $url = env('FILE_PATH')."public/analytics/".$campaign_id.'/metrics.json'; 
         $data = file_get_contents($url);
         $final = json_decode($data);

         if(empty($sessionHistoryRange) && $sessionHistoryRange == null){
          $start_date = date('Y-m-d',strtotime('-3 month'));
          $day_diff = strtotime($start_date) - strtotime($today);
          $count_days = floor($day_diff/(60*60*24));
          $prev_date =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
          $prev_end_date =  date('Y-m-d',strtotime($count_days.' days',strtotime($prev_date)));

          $start_date = date('d M, Y',strtotime($start_date));
          $today = date('d M, Y',strtotime(date('Y-m-d')));

          $get_index = array_search($start_date,$final->metrics_dates);
          $get_index_today = array_search($today,$final->metrics_dates);

          $prev_date = date('d M, Y',strtotime($prev_date));
          $prev_end_date = date('d M, Y',strtotime($prev_end_date));

          $get_indexprev = array_search($prev_date,$final->metrics_dates);
          $get_index_prev = array_search($prev_end_date,$final->metrics_dates);


        }else{
          if($sessionHistoryRange->duration == 1){
            $start_date = date('Y-m-d',strtotime('-1 month'));
          }elseif($sessionHistoryRange->duration == 3){
            $start_date = date('Y-m-d',strtotime('-3 month'));
          }elseif($sessionHistoryRange->duration == 6){
            $start_date = date('Y-m-d',strtotime('-6 month'));
          }elseif($sessionHistoryRange->duration == 9){
            $start_date = date('Y-m-d',strtotime('-9 month'));
          }elseif($sessionHistoryRange->duration == 12){
            $start_date = date('Y-m-d',strtotime('-1 year'));
          }elseif($sessionHistoryRange->duration == 24){
            $start_date = date('Y-m-d',strtotime('-2 year'));
          }
          $end_date = date('Y-m-d');

          $day_diff = strtotime($start_date) - strtotime($end_date);
          $count_days = floor($day_diff/(60*60*24));


          $prev_date =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));


          if($sessionHistoryRange->duration == 12){
           $prev_end_date =  date('Y-m-d',strtotime(' -1 year',strtotime($prev_date)));
         }
         elseif($sessionHistoryRange->duration == 24){
           $prev_end_date =  date('Y-m-d',strtotime(' -2 year',strtotime($prev_date)));
         }
         else{
           $prev_end_date =  date('Y-m-d',strtotime($count_days.' days',strtotime($prev_date)));
         }


         $start_date = date('d M, Y',strtotime($start_date));
         $end_date = date('d M, Y',strtotime($end_date));

         $get_index = array_search($start_date,$final->metrics_dates);
         $get_index_today = array_search($end_date,$final->metrics_dates);

         $prev_date = date('d M, Y',strtotime($prev_date));
         $prev_end_date = date('d M, Y',strtotime($prev_end_date));

         $get_indexprev = array_search($prev_date,$final->metrics_dates);
         $get_index_prev = array_search($prev_end_date,$final->metrics_dates);
       }


       $current_sessions_sum  = $prev_sessions_sum = $current_users_sum = $prev_users_sum = $current_pageviews_sum =  $prev_pageviews_sum = 0;

       $final_Session = '0 vs 0';
       $final_users = '0 vs 0';
       $final_pageviews = '0 vs 0';

       $current_dates = $current_sessions = $current_users = $current_pageviews = array();
       $prev_dates = $prev_sessions = $prev_users = $prev_pageviews = array();

       if($get_index == false && $get_index_today == false){
        $current_sessions[] = $current_users[] = $current_pageviews[] = 0;

      }elseif($get_index  && $get_index_today == false){
        $today = end($final->metrics_dates); 
        $get_index_today = array_search($today,$final->metrics_dates);

        for($i=$get_index;$i<=$get_index_today;$i++){
          $current_dates[] = $final->metrics_dates[$i];
          $current_sessions[] = $final->metrics_sessions[$i];
          $current_users[] = $final->metrics_users[$i];
          $current_pageviews[] = $final->metrics_pageviews[$i];
        }
      }else{
        for($i=$get_index;$i<=$get_index_today;$i++){
          $current_dates[] = $final->metrics_dates[$i];
          $current_sessions[] = $final->metrics_sessions[$i];
          $current_users[] = $final->metrics_users[$i];
          $current_pageviews[] = $final->metrics_pageviews[$i];
        }
      }


      if($get_indexprev == false && $get_index_prev == false){
        $prev_sessions[] = $prev_users[] = $prev_pageviews[] = 0;
      }elseif($get_indexprev == false  && $get_index_prev){
        $end_prev = date('d M, Y',strtotime('-1 day',strtotime($start_date))); 
        $get_indexprev = array_search($end_prev,$final->metrics_dates);
        if($get_indexprev == false){
         $today = end($final->metrics_dates); 
         $get_indexprev = array_search($today,$final->metrics_dates);
       }

       for($j=$get_indexprev;$j>=$get_index_prev;$j--){
        $prev_dates[] = $final->metrics_dates[$j];
        $prev_sessions[] = $final->metrics_sessions[$j];
        $prev_users[] = $final->metrics_users[$j];
        $prev_pageviews[] = $final->metrics_pageviews[$j];
      }
    }else{
      for($j=$get_indexprev;$j>=$get_index_prev;$j--){
        $prev_dates[] = $final->metrics_dates[$j];
        $prev_sessions[] = $final->metrics_sessions[$j];
        $prev_users[] = $final->metrics_users[$j];
        $prev_pageviews[] = $final->metrics_pageviews[$j];
      }
    }

    $current_sessions_sum = array_sum($current_sessions);
    $prev_sessions_sum = array_sum($prev_sessions);
    $final_Session = $current_sessions_sum. ' vs '.$prev_sessions_sum;


    if(($current_sessions_sum > 0) && ($prev_sessions_sum > 0)){
      $total_sessions = number_format((($current_sessions_sum - $prev_sessions_sum) / $prev_sessions_sum) * 100,2).'%';
    }else if(($current_sessions_sum == 0) && ($prev_sessions_sum > 0)) {
      $total_sessions = '-100%';
    } else if(($current_sessions_sum > 0) && ($prev_sessions_sum == 0)) {
      $total_sessions = '100%';
    } else{
      $total_sessions = 'N/A';
    }

    $current_users_sum = array_sum($current_users);
    $prev_users_sum = array_sum($prev_users);
    $final_users = $current_users_sum. ' vs '.$prev_users_sum;

    if(($current_users_sum > 0) && ($prev_users_sum > 0)){
      $total_users = number_format((($current_users_sum - $prev_users_sum) / $prev_users_sum) * 100,2).'%';
    }else if(($current_users_sum == 0) && ($prev_users_sum > 0)) {
      $total_users = '-100%';
    } else if(($current_users_sum > 0) && ($prev_users_sum == 0)) {
      $total_users = '100%';
    } else{
      $total_users = 'N/A';
    }

    $current_pageviews_sum = array_sum($current_pageviews);
    $prev_pageviews_sum = array_sum($prev_pageviews);
    $final_pageviews  = $current_pageviews_sum. ' vs '.$prev_pageviews_sum;


    if(($current_pageviews_sum > 0) && ($prev_pageviews_sum > 0)){
      $total_pageviews = number_format((($current_pageviews_sum - $prev_pageviews_sum) / $prev_pageviews_sum) * 100,2).'%';
    }else if(($current_pageviews_sum == 0) && ($prev_pageviews_sum > 0)) {
      $total_pageviews = '-100%';
    } else if(($current_pageviews_sum > 0) && ($prev_pageviews_sum == 0)) {
      $total_pageviews = '100%';
    } else{
      $total_pageviews = 'N/A';
    }




    $res['traffic_growth'] = $total_sessions;
    $res['total_sessions'] = $total_sessions;
    $res['total_pageviews'] = $total_pageviews;
    $res['total_users'] = $total_users;
    $res['final_session'] = $final_Session;
    $res['final_pageviews'] = $final_pageviews;
    $res['final_users'] = $final_users;
    $res['current_session'] = $current_sessions_sum;
    $res['status'] = 1;

  }

  return response()->json($res);
}

public function ajax_get_compare_traffic_data(Request $request){
  $state = ($request->has('key'))?'viewkey':'user';
  $campaign_id = $request['request_id'];
  $compare_value = $request['compare_value'];


  if(Auth::user() <> null){
        $user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
      }else{
        $getUser = SemrushUserAccount::where('id',$campaign_id)->first();
        $user_id = User::get_parent_user_id($getUser->user_id); //get user id from child
      }

      $ifExists = ProjectCompareGraph::getCompareChart($campaign_id);
      if($state == 'user'){
        if(empty($ifExists)){
          ProjectCompareGraph::create([
            'request_id'=>$campaign_id,
            'user_id'=>$user_id,
            'compare_status'=>$compare_value
          ]);
        }else{
          ProjectCompareGraph::where('id',$ifExists->id)->update([
            'request_id'=>$campaign_id,
            'user_id'=>$user_id,
            'compare_status'=>$compare_value
          ]);
        }
      }

      $moduleDates = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');


      if(!empty($moduleDates)){
        if($moduleDates->duration == 1){
          $startDate = date('Y-m-d',strtotime('-1 month'));
        } else if($moduleDates->duration == 3){
          $startDate = date('Y-m-d',strtotime('-3 month'));
        } else if($moduleDates->duration == 6){
          $startDate = date('Y-m-d',strtotime('-6 month'));
        }else  if($moduleDates->duration == 9){
          $startDate = date('Y-m-d',strtotime('-9 month'));
        }  else if($moduleDates->duration == 12){
          $startDate = date('Y-m-d',strtotime('-1 year'));
        } else if($moduleDates->duration == 24){
          $startDate = date('Y-m-d',strtotime('-2 year'));
        } 
      }else{
       $startDate = date('Y-m-d',strtotime('-3 month'));
     }

     $endDate = date('Y-m-d');


     $day_diff = strtotime($startDate) - strtotime($endDate);       
     $count_days = floor($day_diff/(60*60*24));     

     $prev_date =  date('Y-m-d',strtotime('-1 day',strtotime($startDate)));
     $prev_end_date =  date('Y-m-d',strtotime(($count_days+1).' days',strtotime($prev_date)));  


     if (!file_exists(env('FILE_PATH')."public/analytics/".$campaign_id)) {
       $res['status'] = 0;
     } else {

       $url = env('FILE_PATH')."public/analytics/".$campaign_id.'/graph.json'; 
       $data = file_get_contents($url);

       $final = json_decode($data);


       $get_index = array_search($startDate,$final->dates_format);
       $get_index_today = array_search($endDate,$final->dates_format);



       $get_indexprev = array_search($prev_end_date,$final->dates_format);
       $get_index_prev = array_search($prev_date,$final->dates_format);


         //  echo "<pre>";
         // echo "get_index: ".$get_index."<pre>";
         // echo "get_index_today: ".$get_index_today."<pre>";
         // echo "get_indexprev: ".$get_indexprev."<pre>";
         // echo "get_index_prev: ".$get_index_prev."<pre>";
         // die;

       if($get_index_today == false){
        $endDate = end($final->dates_format); 
        $get_index_today = array_search($endDate,$final->dates_format);
      }


      $current = $current_data = $current_prev = $prev_data = array();
      for($i=$get_index;$i<=$get_index_today;$i++){

        $current[] = $final->from_dates[$i];
        $current_dates[] = date('l, F d, Y',strtotime($final->from_dates[$i]));
        $current_data[] = $final->final_array[$i];

      }


      for($j=$get_indexprev;$j<=$get_index_prev;$j++){
        $current_prev[] = $final->from_dates[$j];
        $current_prev_dates[] = date('l, F d, Y',strtotime($final->from_dates[$j]));
        $prev_data[] = $final->final_array[$j];
      }

            // dd($current_prev);

      $current_period  = date('M d, Y', strtotime($startDate)).' - '.date('M d, Y', strtotime($endDate));
      $prev_period   = date('M d, Y', strtotime($prev_end_date)).' - '.date('M d, Y', strtotime($prev_date));


      $compareResult = ProjectCompareGraph::where('request_id',$campaign_id)->first();

      if(!empty($compareResult) && ($state == 'user')){
        $compare = $compareResult->compare_status;
      }
      else{
        $compare = $compare_value;
      }


      $res['from_datelabel'] = $current;
      $res['from_datelabels'] = $current_dates;
      $res['prev_from_datelabels'] = $current_prev_dates;
      $res['current_period'] = $current_period;
      $res['count_session'] = $current_data;
      $res['combine_session'] = $prev_data;
      $res['previous_period'] = $prev_period;
      $res['compare_status'] = $compare;
      $res['status'] = 1;
    }


    return response()->json($res);  
  }

  public function ajax_get_traffic_date_range(Request $request){

    $range = $request['value'];
    $module = $request['module'];
    $today = date('Y-m-d');
    $campaign_id = $request['campaignId'];

    $state = ($request->has('key'))?'viewkey':'user';

    if(Auth::user() <> null){
          $user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
          $role_id = User::get_user_role(Auth::user()->id);
        }else{
          $getUser = SemrushUserAccount::where('id',$campaign_id)->first();
          $user_id = User::get_parent_user_id($getUser->user_id); //get user id from child
          $role_id = User::get_user_role($getUser->user_id);
        }

        
        $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
        if(!empty($getCompareChart)){
         $compare_status = $getCompareChart->compare_status;
       }else{
         $compare_status = 0;
       }


       if($range == 'week'){
        $start_date_1 = date('Y-m-d',strtotime('-1 week'));
        $duration =1;
      } elseif($range == 'month'){
        $start_date_1 = date('Y-m-d',strtotime('-1 month'));
        $duration =1;
      }elseif($range == 'three'){
       $start_date_1 = date('Y-m-d',strtotime('-3 month'));
       $duration = 3;
     }elseif($range == 'six'){
       $start_date_1 = date('Y-m-d',strtotime('-6 month'));
       $duration =6;
     }elseif($range == 'nine'){
       $start_date_1 = date('Y-m-d',strtotime('-9 month'));
       $duration =9;
     }elseif($range == 'year'){
       $start_date_1 = date('Y-m-d',strtotime('-1 year'));
       $duration =12;
     }elseif($range == 'twoyear'){
       $start_date_1 = date('Y-m-d',strtotime('-2 year'));
       $duration =24;
     }else{
       $start_date_1 = date('Y-m-d',strtotime('-3 month'));
       $duration =3;
     }


     $day_diff = strtotime($start_date_1) - strtotime($today);

     $count_days = floor($day_diff/(60*60*24));


     if($range == 'year'){
       $prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
       $prev_end_date =  date('Y-m-d',strtotime(' -1 year',strtotime($prev_date_1)));
     }
     elseif($range == 'twoyear'){
       $prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
       $prev_end_date =  date('Y-m-d',strtotime(' -2 year',strtotime($prev_date_1)));           
     }
     else{
       $prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
       $prev_end_date =  date('Y-m-d',strtotime($count_days.' days',strtotime($prev_date_1)));
     }

     $ifCheck = ModuleByDateRange::where('request_id',$campaign_id)->where('module',$module)->first();

     if($role_id != 4 && $state == 'user'){
       if(empty($ifCheck)){
        ModuleByDateRange::create([
         'user_id'=>$user_id,
         'request_id'=>$campaign_id,
         'duration'=>$duration,
         'module'=>$module,
         'start_date'=>date('Y-m-d', strtotime($start_date_1)),
         'end_date'=>date('Y-m-d', strtotime($today))
       ]);
      }else{
        ModuleByDateRange::where('id',$ifCheck->id)->update([
         'user_id'=>$user_id,
         'request_id'=>$campaign_id,
         'duration'=>$duration,
         'module'=>$module,
         'start_date'=>date('Y-m-d', strtotime($start_date_1)),
         'end_date'=>date('Y-m-d', strtotime($today))
       ]);
      }
    }


    if (!file_exists(env('FILE_PATH')."public/analytics/".$campaign_id)) {
     $res['status'] = 0;
   } else {

     $url = env('FILE_PATH')."public/analytics/".$campaign_id.'/graph.json'; 
     $data = file_get_contents($url);

     $final = json_decode($data);


     $get_index = array_search($start_date_1,$final->dates_format);
     $get_index_today = array_search($today,$final->dates_format);

     $get_indexprev = array_search($prev_end_date,$final->dates_format);
     $get_index_prev = array_search($prev_date_1,$final->dates_format);
   //  dd($get_index_prev);

     if($get_index_today == false){
      $today = end($final->dates_format); 
      $get_index_today = array_search($today,$final->dates_format);
    }

    $current = $current_data = $current_prev = $prev_data = array();
    for($i=$get_index;$i<=$get_index_today;$i++){
      $current[] = date('M d',strtotime($final->from_dates[$i]));
      $current_dates[] = date('l, F d, Y',strtotime($final->dates_format[$i]));
      $current_data[] = $final->final_array[$i];

    }


    for($j=$get_indexprev;$j<=$get_index_prev;$j++){
      $current_prev[] = $final->from_dates[$j];
      $current_prev_dates[] = date('l, F d, Y',strtotime($final->dates_format[$j]));
      $prev_data[] = $final->final_array[$j];

    }


    // $current_period  = date('d-m-Y', strtotime($today)).' to '.date('d-m-Y', strtotime($start_date_1));
    // $prev_period   = date('d-m-Y', strtotime($prev_date_1)).' to '.date('d-m-Y', strtotime($prev_end_date));

    $current_period  = date('M d, Y', strtotime($start_date_1)).' - '.date('M d, Y', strtotime($today));
    $prev_period   = date('M d, Y', strtotime($prev_end_date)).' - '.date('M d, Y', strtotime($prev_date_1));


    $res['from_datelabel'] = $current;
    $res['from_datelabels'] = $current_dates;
    $res['prev_from_datelabels'] = $current_prev_dates;
    $res['current_period'] = $current_period;
    $res['count_session'] = $current_data;
    $res['combine_session'] = $prev_data;
    $res['previous_period'] = $prev_period;
    $res['compare_status'] = $compare_status;
    $res['status'] = 1;
  }

  return response()->json($res);
}

public function ajax_get_traffic_date_range_metrics(Request $request){
  $range = $request['value'];
  $module = $request['module'];
  $today = date('Y-m-d');
  $campaign_id = $request['campaignId'];
  $state = ($request->has('key'))?'viewkey':'user';

  if($range == 'week'){
   $start_date = date('Y-m-d',strtotime('-1 week'));
 } elseif($range == 'month'){
   $start_date = date('Y-m-d',strtotime('-1 month'));
 }elseif($range == 'three'){
   $start_date = date('Y-m-d',strtotime('-3 month'));
 }elseif($range == 'six'){
   $start_date = date('Y-m-d',strtotime('-6 month'));
 }elseif($range == 'nine'){
   $start_date = date('Y-m-d',strtotime('-9 month'));
 }elseif($range == 'year'){
   $start_date = date('Y-m-d',strtotime('-1 year'));
 }elseif($range == 'twoyear'){
   $start_date = date('Y-m-d',strtotime('-2 year'));
 }else{
   $start_date = date('Y-m-d',strtotime('-3 month'));
 }


 $day_diff = strtotime($start_date) - strtotime($today);
 $count_days = floor($day_diff/(60*60*24));


 $prev_date =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
 $prev_end_date =  date('Y-m-d',strtotime($count_days.' days',strtotime($prev_date)));


 if($range == 'year'){
   $prev_date =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
   $prev_end_date =  date('Y-m-d',strtotime(' -1 year',strtotime($prev_date)));
 }
 elseif($range == 'twoyear'){
   $prev_date =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
   $prev_end_date =  date('Y-m-d',strtotime(' -2 year',strtotime($prev_date)));
 }
 else{
   $prev_date =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
   $prev_end_date =  date('Y-m-d',strtotime($count_days.' days',strtotime($prev_date)));
 }



 if (!file_exists(env('FILE_PATH')."public/analytics/".$campaign_id)) {
   $res['traffic_growth'] = '0%';
   $res['total_sessions'] = '0%';
   $res['total_pageviews'] = '0%';
   $res['total_users'] = '0%';
   $res['final_session'] = '0 vs 0';
   $res['final_pageviews'] = '0 vs 0';
   $res['final_users'] = '0 vs 0';
   $res['current_session'] = '0';
   $res['status'] = 0;
 } else {

   $url = env('FILE_PATH')."public/analytics/".$campaign_id.'/metrics.json'; 
   $data = file_get_contents($url);
   $final = json_decode($data);

   $start_date = date('d M, Y',strtotime($start_date));
   $today = date('d M, Y',strtotime($today));

   $get_index = array_search($start_date,$final->metrics_dates);
   $get_index_today = array_search($today,$final->metrics_dates);

   $prev_date = date('d M, Y',strtotime($prev_date));
   $prev_end_date = date('d M, Y',strtotime($prev_end_date));

   $get_indexprev = array_search($prev_date,$final->metrics_dates);
   $get_index_prev = array_search($prev_end_date,$final->metrics_dates);

   $current_sessions_sum  = $prev_sessions_sum = $current_users_sum = $prev_users_sum = $current_pageviews_sum =  $prev_pageviews_sum = 0;

   $final_Session = '0 vs 0';
   $final_users = '0 vs 0';
   $final_pageviews = '0 vs 0';

   $current_dates = $current_sessions = $current_users = $current_pageviews = array();
   $prev_dates = $prev_sessions = $prev_users = $prev_pageviews = array();

   if($get_index == false && $get_index_today == false){
    $current_sessions[] = $current_users[] = $current_pageviews[] = 0;

  }elseif($get_index  && $get_index_today == false){
    $today = end($final->metrics_dates); 
    $get_index_today = array_search($today,$final->metrics_dates);

    for($i=$get_index;$i<=$get_index_today;$i++){
      $current_dates[] = $final->metrics_dates[$i];
      $current_sessions[] = $final->metrics_sessions[$i];
      $current_users[] = $final->metrics_users[$i];
      $current_pageviews[] = $final->metrics_pageviews[$i];
    }
  }else{
   for($i=$get_index;$i<=$get_index_today;$i++){
    $current_dates[] = $final->metrics_dates[$i];
    $current_sessions[] = $final->metrics_sessions[$i];
    $current_users[] = $final->metrics_users[$i];
    $current_pageviews[] = $final->metrics_pageviews[$i];
  }
}


if($get_indexprev == false && $get_index_prev == false){
  $prev_sessions[] = $prev_users[] = $prev_pageviews[] = 0;
}elseif($get_indexprev == false  && $get_index_prev){

  $end_prev = date('d M, Y',strtotime('-1 day',strtotime($start_date))); 
  $get_indexprev = array_search($end_prev,$final->metrics_dates);
  if($get_indexprev == false){
   $today = end($final->metrics_dates); 
   $get_indexprev = array_search($today,$final->metrics_dates);
 }

 for($j=$get_indexprev;$j>=$get_index_prev;$j--){
  $prev_dates[] = $final->metrics_dates[$j];
  $prev_sessions[] = $final->metrics_sessions[$j];
  $prev_users[] = $final->metrics_users[$j];
  $prev_pageviews[] = $final->metrics_pageviews[$j];
}
}else{

  for($j=$get_indexprev;$j>=$get_index_prev;$j--){
    $prev_dates[] = $final->metrics_dates[$j];
    $prev_sessions[] = $final->metrics_sessions[$j];
    $prev_users[] = $final->metrics_users[$j];
    $prev_pageviews[] = $final->metrics_pageviews[$j];
  }
}

$current_sessions_sum = array_sum($current_sessions);
$prev_sessions_sum = array_sum($prev_sessions);
$final_Session = $current_sessions_sum. ' vs '.$prev_sessions_sum;

if(($current_sessions_sum > 0) && ($prev_sessions_sum > 0)){
  $total_sessions = number_format((($current_sessions_sum - $prev_sessions_sum) / $prev_sessions_sum) * 100,2).'%';
}else if(($current_sessions_sum == 0) && ($prev_sessions_sum > 0)) {
  $total_sessions = '-100%';
} else if(($current_sessions_sum > 0) && ($prev_sessions_sum == 0)) {
  $total_sessions = '100%';
} else{
  $total_sessions = 'N/A';
}

$current_users_sum = array_sum($current_users);
$prev_users_sum = array_sum($prev_users);
$final_users = $current_users_sum. ' vs '.$prev_users_sum;

if(($current_users_sum > 0) && ($prev_users_sum > 0)){
  $total_users = number_format((($current_users_sum - $prev_users_sum) / $prev_users_sum) * 100,2).'%';
}else if(($current_users_sum == 0) && ($prev_users_sum > 0)) {
  $total_users = '-100%';
} else if(($current_users_sum > 0) && ($prev_users_sum == 0)) {
  $total_users = '100%';
} else{
  $total_users = 'N/A';
}

$current_pageviews_sum = array_sum($current_pageviews);
$prev_pageviews_sum = array_sum($prev_pageviews);
$final_pageviews  = $current_pageviews_sum. ' vs '.$prev_pageviews_sum;


if(($current_pageviews_sum > 0) && ($prev_pageviews_sum > 0)){
  $total_pageviews = number_format((($current_pageviews_sum - $prev_pageviews_sum) / $prev_pageviews_sum) * 100,2).'%';
}else if(($current_pageviews_sum == 0) && ($prev_pageviews_sum > 0)) {
  $total_pageviews = '-100%';
} else if(($current_pageviews_sum > 0) && ($prev_pageviews_sum == 0)) {
  $total_pageviews = '100%';
} else{
  $total_pageviews = 'N/A';
}




$res['traffic_growth'] = $total_sessions;
$res['total_sessions'] = $total_sessions;
$res['total_pageviews'] = $total_pageviews;
$res['total_users'] = $total_users;
$res['final_session'] = $final_Session;
$res['final_pageviews'] = $final_pageviews;
$res['final_users'] = $final_users;
$res['current_session'] = $current_sessions_sum;
$res['status'] = 1;

}

return response()->json($res);
}

private function getStartAndEndDate($week, $year) {
  $dto = new \DateTime();
  $dto->setISODate($year, $week);
  $ret = $dto->format('Y-m-d');
  return $ret;
}


public function ajax_get_goal_completion_chart_data_bkp(Request $request){
  $end_date = date('Y-m-d');
  $campaign_id = $request['campaign_id'];

  if(Auth::user() <> null){
        $user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
        $role_id = User::get_user_role(Auth::user()->id);
      }else{
       $getUser = SemrushUserAccount::where('id',$campaign_id)->first();
        $user_id = User::get_parent_user_id($getUser->user_id); //get user id from child
        $role_id = User::get_user_role($getUser->user_id);
      }


      $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
      if(!empty($getCompareChart)){
        $compare_status = $getCompareChart->compare_status;
      }else{
        $compare_status = 0;
      }

      $sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');


      if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
        $res['status'] = 0;
      } else {
        $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/graph.json'; 
        $data = file_get_contents($url);

        $final = json_decode($data);


        if(empty($sessionHistoryRange) && $sessionHistoryRange == null){
          $start_date_new = date('Y-m-d',strtotime('-3 month'));
          $start_date = date('Y-m-d',strtotime('-3 month'));


          $day_diff = strtotime($start_date_new) - strtotime($end_date);
          $count_days = floor($day_diff/(60*60*24));


          $prev_date1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_new)));
          $prev_date =  date('d M, Y',strtotime('-1 day',strtotime($start_date_new)));
          $prev_date_new =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
          $prev_end_date =  date('d M, Y',strtotime($count_days.' days',strtotime($prev_date_new)));
          $prev_end_date1 =  date('Y-m-d',strtotime($count_days.' days',strtotime($prev_date_new)));


          $get_index = array_search($start_date,$final->dates_format);
          $get_index_today = array_search($end_date,$final->dates_format);
          if($get_index_today == false){
            $get_index_today = array_search(end($final->dates_format),$final->dates_format);
          }

          $get_indexprev = array_search($prev_end_date1,$final->dates_format);
          $get_index_prev = array_search($prev_date1,$final->dates_format);
        }else{
          if($sessionHistoryRange->duration == 1){
            $start_date   = date('Y-m-d', strtotime('-1 month'));
          }elseif($sessionHistoryRange->duration == 3){
            $start_date   = date('Y-m-d', strtotime('-3 month'));
          }elseif($sessionHistoryRange->duration == 6){
            $start_date   = date('Y-m-d', strtotime('-6 month'));
          }elseif($sessionHistoryRange->duration == 9){
            $start_date   = date('Y-m-d', strtotime('-9 month'));
          }elseif($sessionHistoryRange->duration == 12){
            $start_date   = date('Y-m-d', strtotime('-1 year'));
          }elseif($sessionHistoryRange->duration == 24){
            $start_date   = date('Y-m-d', strtotime('-2 year'));
          }


          $day_diff  =    strtotime($start_date) - strtotime($end_date);
          $count_days     =   floor($day_diff/(60*60*24));


          $prev_date =  date('d M, Y',strtotime('-1 day',strtotime($start_date)));
          $prev_date1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
          $prev_date_new =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
          $prev_end_date =  date('d M, Y',strtotime($count_days.' days',strtotime($prev_date_new)));      
          $prev_end_date1 =  date('Y-m-d',strtotime($count_days.' days',strtotime($prev_date_new)));      



          $get_index = array_search($start_date,$final->dates_format);
          $get_index_today = array_search($end_date,$final->dates_format);

          if($get_index_today == false){
            $end_date = end($final->dates_format); 
            $get_index_today = array_search($end_date,$final->dates_format);
          }

          $get_indexprev = array_search($prev_end_date1,$final->dates_format);
          $get_index_prev = array_search($prev_date1,$final->dates_format);

          if($get_indexprev == false){
            $prev_end_date =  date('d M, Y',strtotime(($count_days+1).' days',strtotime($prev_date_new)));      
            $prev_end_date1 =  date('Y-m-d',strtotime(($count_days+1).' days',strtotime($prev_date_new)));    
            $get_indexprev = array_search($prev_end_date1,$final->dates_format);
          }

        }


        $current = $current_dates = $current_users = $current_organic = array();
        $current_prev = $current_prev_dates = $prev_users = $prev_organic = array();
        for($i=$get_index;$i<=$get_index_today;$i++){
          $current[] = date('M d',strtotime($final->dates_format[$i]));
          $current_dates[] = date('l, F d,Y',strtotime($final->dates_format[$i]));
          $current_users[] = $final->final_user_data[$i];
          $current_organic[] = $final->final_organic_data[$i];

        }


        for($j=$get_indexprev;$j<=$get_index_prev;$j++){
          $current_prev[] = $final->dates_format[$j];
          $current_prev_dates[] = date('l, F d,Y',strtotime($final->dates_format[$j]));
          $prev_users[] = $final->final_user_data[$j];
          $prev_organic[] = $final->final_organic_data[$j];

        }


        $current_period  = date('d-m-Y', strtotime($end_date)).' to '.date('d-m-Y', strtotime($start_date));
        $prev_period   = date('d-m-Y', strtotime($prev_date1)).' to '.date('d-m-Y', strtotime($prev_end_date1));


        $res['from_datelabel'] = $current;
        $res['from_datelabels'] = $current_dates;
        $res['prev_from_datelabels'] = $current_prev_dates;
        $res['users'] = $current_users;
        $res['previous_users'] = $prev_users;
        $res['organic'] = $current_organic;
        $res['previous_organic'] = $prev_organic;
        $res['current_period'] = $current_period;
        $res['previous_period'] = $prev_period;
        $res['compare_status'] = $compare_status;
        $res['status'] = 1;
      }

      return response()->json($res);


    }


    public function ajax_get_goal_completion_overview(Request $request){
      $end_date = date('Y-m-d');
      $campaign_id = $request['campaign_id'];

      $sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

      if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
       $res['status'] = 0;
       $res['current_goal_completion'] = '??';
       $res['current_goal_value'] = '??';
       $res['current_goal_conversion'] = '??';
       $res['current_goal_abondon'] = '??';
       $res['current_goal_completion_organic'] = '??';
       $res['current_goal_value_organic'] = '??';
       $res['current_goal_conversion_organic'] = '??';
       $res['current_goal_abondon_organic'] = '??';

       $res['previous_goal_completion'] = '??';
       $res['previous_goal_value'] = '??';
       $res['previous_goal_conversion'] = '??';
       $res['previous_goal_abondon'] = '??';
       $res['previous_goal_completion_organic'] = '??';
       $res['previous_goal_value_organic'] = '??';
       $res['previous_goal_conversion_organic'] = '??';
       $res['previous_goal_abondon_organic'] = '??';

       $res['goal_completion_percentage'] = '??';
       $res['goal_value_percentage'] = '??';
       $res['goal_conversion_rate_percentage'] = '??';
       $res['goal_abondon_rate_percentage'] = '??';


       $res['goal_completion_percentage_organic'] = '??';
       $res['goal_value_percentage_organic'] = '??';
       $res['goal_conversion_rate_percentage_organic'] = '??';
       $res['goal_abondon_rate_percentage_organic'] = '??';
     } else {
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/statistics.json'; 
      $data = file_get_contents($url);
      $final = json_decode($data);

      $dates = $this->make_dates($campaign_id);

      $compare_status = $dates['compare_status'];
      $start_date = $dates['start_date'];
      $end_date = $dates['end_date'];
      $prev_date1 = $dates['prev_date_1'];
      $prev_end_date1 = $dates['prev_end_dates'];
      $compare_status = $dates['compare_status'];
      $number_of_days = ModuleByDateRange::calculate_days($start_date,$end_date);

      $get_index = array_search($start_date,$final->dates);
      $get_index_today = array_search($end_date,$final->dates);
      $get_indexprev = array_search($prev_end_date1,$final->dates);
      $get_index_prev = array_search($prev_date1,$final->dates);

      $current_goal_completion = $current_goal_value = $current_goal_completion_organic = $current_goal_value_organic = $current_goal_conversion = $current_goal_abondon = $current_goal_conversion_organic = $current_goal_abondon_organic = array();

      $previous_goal_completion = $previous_goal_value = $previous_goal_conversion = $previous_goal_abondon = $previous_goal_completion_organic = $previous_goal_value_organic = $previous_goal_conversion_organic = $previous_goal_abondon_organic = array();

      if($get_index == false && $get_index_today == false){
        $current_goal_completion[] = $current_goal_value[] = $current_goal_conversion[] = $current_goal_abondon[] = $current_goal_completion_organic[] = $current_goal_value_organic[] = $current_goal_conversion_organic[] = $current_goal_abondon_organic[] = 0;
      }elseif($get_index  && $get_index_today == false){
        $get_index_today = array_search(end($final->dates),$final->dates);
        for($i=$get_index;$i<=$get_index_today;$i++){
         $current_dates[] = $final->dates[$i];
         $current_goal_completion[] = $final->completion_all[$i];
         $current_goal_value[] = $final->value_all[$i];
         $current_goal_conversion[] = number_format($final->conversionRate_all[$i],2);
         $current_goal_abondon[] = number_format($final->abondonRate_all[$i],2);

         $current_goal_completion_organic[] = $final->completion_all_organic[$i];
         $current_goal_value_organic[] = $final->value_all_organic[$i];
         $current_goal_conversion_organic[] = number_format($final->conversionRate_all_organic[$i],2);
         $current_goal_abondon_organic[] = number_format($final->abondonRate_all_organic[$i],2);
       }
     }else{
      for($i=$get_index;$i<=$get_index_today;$i++){
       $current_dates[] = $final->dates[$i];
       $current_goal_completion[] = $final->completion_all[$i];
       $current_goal_value[] = $final->value_all[$i];
       $current_goal_conversion[] = number_format($final->conversionRate_all[$i],2);
       $current_goal_abondon[] = number_format($final->abondonRate_all[$i],2);

       $current_goal_completion_organic[] = $final->completion_all_organic[$i];
       $current_goal_value_organic[] = $final->value_all_organic[$i];
       $current_goal_conversion_organic[] = number_format($final->conversionRate_all_organic[$i],2);
       $current_goal_abondon_organic[] = number_format($final->abondonRate_all_organic[$i],2);
     }
   }

   if($get_indexprev == false && $get_index_prev == false){
    $previous_goal_completion[] = $previous_goal_value[] = $previous_goal_conversion[] = $previous_goal_abondon[] = $previous_goal_completion_organic[] = $previous_goal_value_organic[] = $previous_goal_conversion_organic[] = $previous_goal_abondon_organic[] = 0;
  }elseif($get_indexprev == false && $get_index_prev){
    $get_indexprev = array_search(end($final->dates),$final->dates);
    for($j=$get_indexprev;$j<=$get_index_prev;$j++){
     $previous_dates[] = $final->dates[$j];
     $previous_goal_completion[] = $final->completion_all[$j];
     $previous_goal_value[] = $final->value_all[$j];
     $previous_goal_conversion[] = number_format($final->conversionRate_all[$j],2);
     $previous_goal_abondon[] = number_format($final->abondonRate_all[$j],2);

     $previous_goal_completion_organic[] = $final->completion_all_organic[$j];
     $previous_goal_value_organic[] = $final->value_all_organic[$j];
     $previous_goal_conversion_organic[] = number_format($final->conversionRate_all_organic[$j],2);
     $previous_goal_abondon_organic[] = number_format($final->abondonRate_all_organic[$j],2);
   }
 }else{
   for($j=$get_indexprev;$j<=$get_index_prev;$j++){
     $previous_dates[] = $final->dates[$j];
     $previous_goal_completion[] = $final->completion_all[$j];
     $previous_goal_value[] = $final->value_all[$j];
     $previous_goal_conversion[] = number_format($final->conversionRate_all[$j],2);
     $previous_goal_abondon[] = number_format($final->abondonRate_all[$j],2);

     $previous_goal_completion_organic[] = $final->completion_all_organic[$j];
     $previous_goal_value_organic[] = $final->value_all_organic[$j];
     $previous_goal_conversion_organic[] = number_format($final->conversionRate_all_organic[$j],2);
     $previous_goal_abondon_organic[] = number_format($final->abondonRate_all_organic[$j],2);
   }
 }

 $final_current_goal_completion = array_sum($current_goal_completion);

 $final_previous_goal_completion = array_sum($previous_goal_completion);

 $final_current_goal_value = array_sum($current_goal_value);
 $final_previous_goal_value = array_sum($previous_goal_value);

 $final_current_goal_completion_organic = array_sum($current_goal_completion_organic);
 $final_previous_goal_completion_organic = array_sum($previous_goal_completion_organic);

 $final_current_goal_value_organic = array_sum($current_goal_value_organic);
 $final_previous_goal_value_organic = array_sum($previous_goal_value_organic);


 $current_goal_conversion_rate = number_format((array_sum($current_goal_conversion)/$number_of_days),2);
 $current_goal_abondon_rate = number_format((array_sum($current_goal_abondon)/$number_of_days),2);
 $current_goal_conversion_organic_rate = number_format((array_sum($current_goal_conversion_organic)/$number_of_days),2);
 $current_goal_abondon_organic_rate = number_format((array_sum($current_goal_abondon_organic)/$number_of_days),2);

 $previous_goal_conversion_rate = number_format((array_sum($previous_goal_conversion)/$number_of_days),2);
 $previous_goal_abondon_rate = number_format((array_sum($previous_goal_abondon)/$number_of_days),2);
 $previous_goal_conversion_organic_rate = number_format((array_sum($previous_goal_conversion_organic)/$number_of_days),2);
 $previous_goal_abondon_organic_rate = number_format((array_sum($previous_goal_abondon_organic)/$number_of_days),2);

    //percentage values


 $goal_completion_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_goal_completion,$final_previous_goal_completion);
 $goal_value_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_goal_value,$final_previous_goal_value);
 $goal_conversion_rate_percentage = GoogleAnalyticsUsers::calculate_percentage($current_goal_conversion_rate,$previous_goal_conversion_rate);
 $goal_abondon_rate_percentage = GoogleAnalyticsUsers::calculate_percentage($current_goal_abondon_rate,$previous_goal_abondon_rate);
      //organic
 $goal_completion_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_goal_completion_organic,$final_previous_goal_completion_organic);
 $goal_value_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_goal_value_organic,$final_previous_goal_value_organic);
 $goal_conversion_rate_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($current_goal_conversion_organic_rate,$previous_goal_conversion_organic_rate);
 $goal_abondon_rate_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($current_goal_abondon_organic_rate,$previous_goal_abondon_organic_rate);


 $res['current_goal_completion'] = $final_current_goal_completion;
 $res['current_goal_value'] = $final_current_goal_value;
 $res['current_goal_conversion'] = $current_goal_conversion_rate;
 $res['current_goal_abondon'] = $current_goal_abondon_rate;
 $res['current_goal_completion_organic'] = $final_current_goal_completion_organic;
 $res['current_goal_value_organic'] = $final_current_goal_value_organic;
 $res['current_goal_conversion_organic'] = $current_goal_conversion_organic_rate;
 $res['current_goal_abondon_organic'] = $current_goal_abondon_organic_rate;

 $res['previous_goal_completion'] = $final_previous_goal_completion;
 $res['previous_goal_value'] = $final_previous_goal_value;
 $res['previous_goal_conversion'] = $previous_goal_conversion_rate;
 $res['previous_goal_abondon'] = $previous_goal_abondon_rate;
 $res['previous_goal_completion_organic'] = $final_previous_goal_completion_organic;
 $res['previous_goal_value_organic'] = $final_previous_goal_value_organic;
 $res['previous_goal_conversion_organic'] = $previous_goal_conversion_organic_rate;
 $res['previous_goal_abondon_organic'] = $previous_goal_abondon_organic_rate;

 $res['goal_completion_percentage'] = $goal_completion_percentage;
 $res['goal_value_percentage'] = $goal_value_percentage;
 $res['goal_conversion_rate_percentage'] = $goal_conversion_rate_percentage;
 $res['goal_abondon_rate_percentage'] = $goal_abondon_rate_percentage;


 $res['goal_completion_percentage_organic'] = $goal_completion_percentage_organic;
 $res['goal_value_percentage_organic'] = $goal_value_percentage_organic;
 $res['goal_conversion_rate_percentage_organic'] = $goal_conversion_rate_percentage_organic;
 $res['goal_abondon_rate_percentage_organic'] = $goal_abondon_rate_percentage_organic;


 $res['compare_status'] = $compare_status;
 $res['status'] = 1;

}
return response()->json($res);
}

public function ajax_get_goal_completion_location(Request $request){
  $campaign_id = $request->campaign_id;

  $sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

  $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
  if(!empty($getCompareChart)){
    $compare_status = $getCompareChart->compare_status;
  }else{
    $compare_status = 0;
  }

  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
   $res['status'] = 0;
   return response()->json($res);
 } else {
  $end = date('M d, Y');

  $keysArr = $this->session_data_goal_location($sessionHistoryRange,$campaign_id);


  $start_date = $keysArr['start_date'];
  $prev_day = $keysArr['prev_day'];
  $prev_date = $keysArr['prev_date'];
  $duration = $keysArr['duration'];
  $arr_name = $keysArr['keysArr']['arr_name'];
  $location_name = $keysArr['keysArr']['location'];
  $stats_data =  $this->get_completion_stats($campaign_id,$start_date,$end,$prev_day,$prev_date);

  $data = file_get_contents($keysArr['url']);
  $final = json_decode($data);

  $newCollection = collect($final->$arr_name->$location_name);



  $page = request()->has('page') ? request('page') : 1;

      // Set default per page
  $perPage = request()->has('per_page') ? request('per_page') : 4;

      // Offset required to take the results
  $offset = ($page * $perPage) - $perPage;

  $results =  new LengthAwarePaginator(
   $newCollection->slice($offset, $perPage),
   $newCollection->count(),
   $perPage,
   $page
 );



  return view('vendor.seo_sections.goal_completion.location_table', compact('final','end','start_date','prev_day','prev_date','duration','keysArr','compare_status','stats_data','results'))->render();
}
}

private function session_data_goal_location($sessionHistoryRange,$campaign_id){
  if(empty($sessionHistoryRange) && $sessionHistoryRange == null){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/three_month_locations.json'; 
    $duration = 3;
    $start_date = date('M d, Y',strtotime('-3 month'));
    $start_date_new = date('Y-m-d',strtotime('-3 month'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-3 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_three_array',
      'location'=>'three_current_location',
      'goal_value'=>'three_current_goal',

      'prev_arr_name'=>'prev_three_array',
      'prev_location'=>'three_prev_location',
      'prev_goal_value'=>'three_prev_goal', 

      'arr_name_organic'=>'current_three_organic_array',
      'location_organic'=>'three_current_organic_location',
      'goal_value_organic'=>'three_current_organic_goal',

      'prev_arr_name_organic'=>'prev_three_organic_array',
      'prev_location_organic'=>'three_prev_organic_location',
      'prev_goal_value_organic'=>'three_prev_organic_goal'
    ];
  }else{
    if($sessionHistoryRange->duration == 1){
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/month_locations.json'; 
      $duration = 1;
      $start_date = date('M d, Y',strtotime('-1 month'));
      $start_date_new = date('Y-m-d',strtotime('-1 month'));
      $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
      $prev_date =  date('M d, Y',strtotime('-1 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

      $keysArr = [
        'arr_name'=>'current_month_array',
        'location'=>'one_current_location',
        'goal_value'=>'one_current_goal',

        'prev_arr_name'=>'prev_month_array',
        'prev_location'=>'one_prev_location',
        'prev_goal_value'=>'one_prev_goal', 

        'arr_name_organic'=>'current_month_organic_array',
        'location_organic'=>'one_current_organic_location',
        'goal_value_organic'=>'one_current_organic_goal',

        'prev_arr_name_organic'=>'prev_month_organic_array',
        'prev_location_organic'=>'one_prev_organic_location',
        'prev_goal_value_organic'=>'one_prev_organic_goal'

      ];

    }elseif($sessionHistoryRange->duration == 3){
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/three_month_locations.json'; 
      $duration = 3;
      $start_date = date('M d, Y',strtotime('-3 month'));
      $start_date_new = date('Y-m-d',strtotime('-3 month'));
      $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
      $prev_date =  date('M d, Y',strtotime('-3 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

      $keysArr = [
        'arr_name'=>'current_three_array',
        'location'=>'three_current_location',
        'goal_value'=>'three_current_goal',

        'prev_arr_name'=>'prev_three_array',
        'prev_location'=>'three_prev_location',
        'prev_goal_value'=>'three_prev_goal', 

        'arr_name_organic'=>'current_three_organic_array',
        'location_organic'=>'three_current_organic_location',
        'goal_value_organic'=>'three_current_organic_goal',

        'prev_arr_name_organic'=>'prev_three_organic_array',
        'prev_location_organic'=>'three_prev_organic_location',
        'prev_goal_value_organic'=>'three_prev_organic_goal'
      ];

    }elseif($sessionHistoryRange->duration == 6){
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/six_month_locations.json'; 
      $duration = 6;
      $start_date = date('M d, Y',strtotime('-6 month'));
      $start_date_new = date('Y-m-d',strtotime('-6 month'));
      $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
      $prev_date =  date('M d, Y',strtotime('-6 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

      $keysArr = [
        'arr_name'=>'current_six_array',
        'location'=>'six_current_location',
        'goal_value'=>'six_current_goal',

        'prev_arr_name'=>'prev_six_array',
        'prev_location'=>'six_prev_location',
        'prev_goal_value'=>'six_prev_goal', 

        'arr_name_organic'=>'current_six_organic_array',
        'location_organic'=>'six_current_organic_location',
        'goal_value_organic'=>'six_current_organic_goal',

        'prev_arr_name_organic'=>'prev_six_organic_array',
        'prev_location_organic'=>'six_prev_organic_location',
        'prev_goal_value_organic'=>'six_prev_organic_goal'
      ];
    }elseif($sessionHistoryRange->duration == 9){
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/nine_month_locations.json'; 
      $duration = 9;
      $start_date = date('M d, Y',strtotime('-9 month'));
      $start_date_new = date('Y-m-d',strtotime('-9 month'));
      $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
      $prev_date =  date('M d, Y',strtotime('-9 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

      $keysArr = [
        'arr_name'=>'current_nine_array',
        'location'=>'nine_current_location',
        'goal_value'=>'nine_current_goal',

        'prev_arr_name'=>'prev_nine_array',
        'prev_location'=>'nine_prev_location',
        'prev_goal_value'=>'nine_prev_goal', 

        'arr_name_organic'=>'current_nine_organic_array',
        'location_organic'=>'nine_current_organic_location',
        'goal_value_organic'=>'nine_current_organic_goal',

        'prev_arr_name_organic'=>'prev_nine_organic_array',
        'prev_location_organic'=>'nine_prev_organic_location',
        'prev_goal_value_organic'=>'nine_prev_organic_goal'
      ];
    }elseif($sessionHistoryRange->duration == 12){
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/year_locations.json'; 
      $duration = 12;
      $start_date = date('M d, Y',strtotime('-1 year'));
      $start_date_new = date('Y-m-d',strtotime('-1 year'));
      $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
      $prev_date =  date('M d, Y',strtotime('-1 year',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

      $keysArr = [
        'arr_name'=>'current_year_array',
        'location'=>'year_current_location',
        'goal_value'=>'year_current_goal',

        'prev_arr_name'=>'prev_year_array',
        'prev_location'=>'year_prev_location',
        'prev_goal_value'=>'year_prev_goal', 

        'arr_name_organic'=>'current_year_organic_array',
        'location_organic'=>'year_current_organic_location',
        'goal_value_organic'=>'year_current_organic_goal',

        'prev_arr_name_organic'=>'prev_year_organic_array',
        'prev_location_organic'=>'year_prev_organic_location',
        'prev_goal_value_organic'=>'year_prev_organic_goal'
      ];
    }elseif($sessionHistoryRange->duration == 24){
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/twoyear_locations.json'; 
      $duration = 24;
      $start_date = date('M d, Y',strtotime('-2 year'));
      $start_date_new = date('Y-m-d',strtotime('-2 year'));
      $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
      $prev_date =  date('M d, Y',strtotime('-2 year',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

      $keysArr = [
        'arr_name'=>'current_twoyear_array',
        'location'=>'twoyear_current_location',
        'goal_value'=>'twoyear_current_goal',

        'prev_arr_name'=>'prev_twoyear_array',
        'prev_location'=>'twoyear_prev_location',
        'prev_goal_value'=>'twoyear_prev_goal', 

        'arr_name_organic'=>'current_twoyear_organic_array',
        'location_organic'=>'twoyear_current_organic_location',
        'goal_value_organic'=>'twoyear_current_organic_goal',

        'prev_arr_name_organic'=>'prev_twoyear_organic_array',
        'prev_location_organic'=>'twoyear_prev_organic_location',
        'prev_goal_value_organic'=>'twoyear_prev_organic_goal'
      ];
    }
  }
  return compact('keysArr','start_date','prev_day','prev_date','url','duration');
}


public function ajax_get_goal_completion_location_pagination(Request $request){
  $campaign_id = $request->campaign_id;
  $page = $request->page;

  $sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

  $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
  if(!empty($getCompareChart)){
    $compare_status = $getCompareChart->compare_status;
  }else{
    $compare_status = 0;
  }

  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
   $res['status'] = 0;
   return response()->json($res);
 } else {
  $end = date('M d, Y');
  $keysArr = $this->session_data_goal_location($sessionHistoryRange,$campaign_id);
  $start_date = $keysArr['start_date'];
  $prev_day = $keysArr['prev_day'];
  $prev_date = $keysArr['prev_date'];
  $arr_name = $keysArr['keysArr']['arr_name'];
  $location_name = $keysArr['keysArr']['location'];

  $stats_data =  $this->get_completion_stats($campaign_id,$start_date,$end,$prev_day,$prev_date);

  $data = file_get_contents($keysArr['url']);
  $final = json_decode($data);


  $newCollection = collect($final->$arr_name->$location_name);


  $page = request()->has('page') ? request('page') : 1;

    // Set default per page
  $perPage = request()->has('per_page') ? request('per_page') : 4;

    // Offset required to take the results
  $offset = ($page * $perPage) - $perPage;

  $results =  new LengthAwarePaginator(
   $newCollection->slice($offset, $perPage),
   $newCollection->count(),
   $perPage,
   $page
 );


  return view('vendor.seo_sections.goal_completion.pagination', compact('results'))->render();
}
}

public function ajax_get_goal_completion_sourcemedium(Request $request){
  $campaign_id = $request->campaign_id;

  $sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

  $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
  if(!empty($getCompareChart)){
    $compare_status = $getCompareChart->compare_status;
  }else{
    $compare_status = 0;
  }

  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
   $res['status'] = 0;
   return response()->json($res);
 } else {
  $end = date('M d, Y');

  $keysArr = $this->session_data_goal_sourcemedium($sessionHistoryRange,$campaign_id);

  $start_date = $keysArr['start_date'];
  $prev_day = $keysArr['prev_day'];
  $prev_date = $keysArr['prev_date'];
  $duration = $keysArr['duration'];
  $arr_name = $keysArr['keysArr']['arr_name'];
  $location_name = $keysArr['keysArr']['location'];

  $data = file_get_contents($keysArr['url']);
  $final = json_decode($data);

  $stats_data =  $this->get_completion_stats($campaign_id,$start_date,$end,$prev_day,$prev_date);

  $newCollection = collect($final->$arr_name->$location_name);

  $page = request()->has('page') ? request('page') : 1;
      // Set default per page
  $perPage = request()->has('per_page') ? request('per_page') : 4;
      // Offset required to take the results
  $offset = ($page * $perPage) - $perPage;

  $results =  new LengthAwarePaginator(
   $newCollection->slice($offset, $perPage),
   $newCollection->count(),
   $perPage,
   $page
 );


  return view('vendor.seo_sections.goal_completion.sourcemedium_table', compact('final','end','start_date','prev_day','prev_date','duration','keysArr','compare_status','stats_data','results'))->render();
}
}

public function ajax_get_goal_completion_sourcemedium_pagination(Request $request){
 $campaign_id = $request->campaign_id;


 $sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

 $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
 if(!empty($getCompareChart)){
  $compare_status = $getCompareChart->compare_status;
}else{
  $compare_status = 0;
}

if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
 $res['status'] = 0;
 return response()->json($res);
} else {
  $end = date('M d, Y');
  $keysArr = $this->session_data_goal_sourcemedium($sessionHistoryRange,$campaign_id);
  $start_date = $keysArr['start_date'];
  $prev_day = $keysArr['prev_day'];
  $prev_date = $keysArr['prev_date'];
  $arr_name = $keysArr['keysArr']['arr_name'];
  $location_name = $keysArr['keysArr']['location'];

  $stats_data =  $this->get_completion_stats($campaign_id,$start_date,$end,$prev_day,$prev_date);

  $data = file_get_contents($keysArr['url']);
  $final = json_decode($data);


  $newCollection = collect($final->$arr_name->$location_name);


  $page = request()->has('page') ? request('page') : 1;

    // Set default per page
  $perPage = request()->has('per_page') ? request('per_page') : 4;

    // Offset required to take the results
  $offset = ($page * $perPage) - $perPage;

  $results =  new LengthAwarePaginator(
   $newCollection->slice($offset, $perPage),
   $newCollection->count(),
   $perPage,
   $page
 );


  return view('vendor.seo_sections.goal_completion.source_medium_pagination', compact('results'))->render();
}
}

private function session_data_goal_sourcemedium($sessionHistoryRange,$campaign_id){

  if(empty($sessionHistoryRange) && $sessionHistoryRange == null){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/three_sourcemedium.json'; 
    $duration = 3;
    $start_date = date('M d, Y',strtotime('-3 month'));
    $start_date_new = date('Y-m-d',strtotime('-3 month'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-3 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_three_sm_array',
      'location'=>'three_current_sm_location',
      'goal_value'=>'three_current_sm_goal',

      'prev_arr_name'=>'prev_three_sm_array',
      'prev_location'=>'three_prev_sm_location',
      'prev_goal_value'=>'three_prev_sm_goal', 

      'arr_name_organic'=>'current_three_sm_organic_array',
      'location_organic'=>'three_current_organic_sm_location',
      'goal_value_organic'=>'three_current_organic_sm_goal',

      'prev_arr_name_organic'=>'prev_three_sm_organic_array',
      'prev_location_organic'=>'three_prev_organic_sm_location',
      'prev_goal_value_organic'=>'three_prev_organic_sm_goal'
    ];
  }else{
    if($sessionHistoryRange->duration == 1){
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/month_sourcemedium.json'; 
      $duration = 1;
      $start_date = date('M d, Y',strtotime('-1 month'));
      $start_date_new = date('Y-m-d',strtotime('-1 month'));
      $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
      $prev_date =  date('M d, Y',strtotime('-1 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

      $keysArr = [
        'arr_name'=>'current_month_sm_array',
        'location'=>'one_current_sm_location',
        'goal_value'=>'one_current_sm_goal',

        'prev_arr_name'=>'prev_month_sm_array',
        'prev_location'=>'one_prev_sm_location',
        'prev_goal_value'=>'one_prev_sm_goal', 

        'arr_name_organic'=>'current_month_sm_organic_array',
        'location_organic'=>'one_current_organic_sm_location',
        'goal_value_organic'=>'one_current_organic_sm_goal',

        'prev_arr_name_organic'=>'prev_month_sm_organic_array',
        'prev_location_organic'=>'one_prev_organic_sm_location',
        'prev_goal_value_organic'=>'one_prev_organic_sm_goal'

      ];

    }elseif($sessionHistoryRange->duration == 3){
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/three_sourcemedium.json'; 
      $duration = 3;
      $start_date = date('M d, Y',strtotime('-3 month'));
      $start_date_new = date('Y-m-d',strtotime('-3 month'));
      $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
      $prev_date =  date('M d, Y',strtotime('-3 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

      $keysArr = [
        'arr_name'=>'current_three_sm_array',
        'location'=>'three_current_sm_location',
        'goal_value'=>'three_current_sm_goal',

        'prev_arr_name'=>'prev_three_sm_array',
        'prev_location'=>'three_prev_sm_location',
        'prev_goal_value'=>'three_prev_sm_goal', 

        'arr_name_organic'=>'current_three_sm_organic_array',
        'location_organic'=>'three_current_organic_sm_location',
        'goal_value_organic'=>'three_current_organic_sm_goal',

        'prev_arr_name_organic'=>'prev_three_sm_organic_array',
        'prev_location_organic'=>'three_prev_organic_sm_location',
        'prev_goal_value_organic'=>'three_prev_organic_sm_goal'
      ];

    }elseif($sessionHistoryRange->duration == 6){
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/six_sourcemedium.json'; 
      $duration = 6;
      $start_date = date('M d, Y',strtotime('-6 month'));
      $start_date_new = date('Y-m-d',strtotime('-6 month'));
      $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
      $prev_date =  date('M d, Y',strtotime('-6 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

      $keysArr = [
        'arr_name'=>'current_six_sm_array',
        'location'=>'six_current_sm_location',
        'goal_value'=>'six_current_sm_goal',

        'prev_arr_name'=>'prev_six_sm_array',
        'prev_location'=>'six_prev_sm_location',
        'prev_goal_value'=>'six_prev_sm_goal', 

        'arr_name_organic'=>'current_six_sm_organic_array',
        'location_organic'=>'six_current_organic_sm_location',
        'goal_value_organic'=>'six_current_organic_sm_goal',

        'prev_arr_name_organic'=>'prev_six_sm_organic_array',
        'prev_location_organic'=>'six_prev_organic_sm_location',
        'prev_goal_value_organic'=>'six_prev_organic_sm_goal'
      ];
    }elseif($sessionHistoryRange->duration == 9){
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/nine_sourcemedium.json'; 
      $duration = 9;
      $start_date = date('M d, Y',strtotime('-9 month'));
      $start_date_new = date('Y-m-d',strtotime('-9 month'));
      $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
      $prev_date =  date('M d, Y',strtotime('-9 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

      $keysArr = [
        'arr_name'=>'current_nine_sm_array',
        'location'=>'nine_current_sm_location',
        'goal_value'=>'nine_current_sm_goal',

        'prev_arr_name'=>'prev_nine_sm_array',
        'prev_location'=>'nine_prev_sm_location',
        'prev_goal_value'=>'nine_prev_sm_goal', 

        'arr_name_organic'=>'current_nine_sm_organic_array',
        'location_organic'=>'nine_current_organic_sm_location',
        'goal_value_organic'=>'nine_current_organic_sm_goal',

        'prev_arr_name_organic'=>'prev_nine_sm_organic_array',
        'prev_location_organic'=>'nine_prev_organic_sm_location',
        'prev_goal_value_organic'=>'nine_prev_organic_sm_goal'
      ];
    }elseif($sessionHistoryRange->duration == 12){
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/year_sourcemedium.json'; 
      $duration = 12;
      $start_date = date('M d, Y',strtotime('-1 year'));
      $start_date_new = date('Y-m-d',strtotime('-1 year'));
      $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
      $prev_date =  date('M d, Y',strtotime('-1 year',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

      $keysArr = [
        'arr_name'=>'current_year_sm_array',
        'location'=>'year_current_sm_location',
        'goal_value'=>'year_current_sm_goal',

        'prev_arr_name'=>'prev_year_sm_array',
        'prev_location'=>'year_prev_sm_location',
        'prev_goal_value'=>'year_prev_sm_goal', 

        'arr_name_organic'=>'current_year_sm_organic_array',
        'location_organic'=>'year_current_organic_sm_location',
        'goal_value_organic'=>'year_current_organic_sm_goal',

        'prev_arr_name_organic'=>'prev_year_sm_organic_array',
        'prev_location_organic'=>'year_prev_organic_sm_location',
        'prev_goal_value_organic'=>'year_prev_organic_sm_goal'
      ];
    }elseif($sessionHistoryRange->duration == 24){
      $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/twoyear_sourcemedium.json'; 
      $duration = 24;
      $start_date = date('M d, Y',strtotime('-2 year'));
      $start_date_new = date('Y-m-d',strtotime('-2 year'));
      $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
      $prev_date =  date('M d, Y',strtotime('-2 year',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

      $keysArr = [
        'arr_name'=>'current_twoyear_sm_array',
        'location'=>'twoyear_current_sm_location',
        'goal_value'=>'twoyear_current_sm_goal',

        'prev_arr_name'=>'prev_twoyear_sm_array',
        'prev_location'=>'twoyear_prev_sm_location',
        'prev_goal_value'=>'twoyear_prev_sm_goal', 

        'arr_name_organic'=>'current_twoyear_sm_organic_array',
        'location_organic'=>'twoyear_current_organic_sm_location',
        'goal_value_organic'=>'twoyear_current_organic_sm_goal',

        'prev_arr_name_organic'=>'prev_twoyear_sm_organic_array',
        'prev_location_organic'=>'twoyear_prev_organic_sm_location',
        'prev_goal_value_organic'=>'twoyear_prev_organic_sm_goal'
      ];
    }

  }
  return compact('keysArr','start_date','prev_day','prev_date','url','duration');
}


private function get_completion_stats($campaign_id,$start_date,$end,$prev_day,$prev_date){

  $start_date = date('Y-m-d',strtotime($start_date));
  $end_date = date('Y-m-d',strtotime($end));
  $prev_date1 = date('Y-m-d',strtotime($prev_day));
  $prev_end_date1 = date('Y-m-d',strtotime($prev_date));
  $day_diff  =    strtotime($end_date) - strtotime($start_date);
  $count_days     =   floor($day_diff/(60*60*24));


  if (file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/statistics.json'; 
    $data = file_get_contents($url);

    $final = json_decode($data);

    $get_index = array_search($start_date,$final->dates);
    $get_index_today = array_search($end_date,$final->dates);

    $get_indexprev = array_search($prev_end_date1,$final->dates);
    $get_index_prev = array_search($prev_date1,$final->dates);

    if($get_index ==  false && $get_index_today == false){
      $current_goal_completion[] = $current_goal_completion_organic[] = 0;
    }elseif($get_index && $get_index_today ==  false){
      $today = end($final->dates); 
      $get_index_today = array_search($today,$final->dates);
      for($i=$get_index;$i<=$get_index_today;$i++){
        $current_goal_completion[] = $final->completion_all[$i];
        $current_goal_completion_organic[] = $final->completion_all_organic[$i];
      }
    }else{
      for($i=$get_index;$i<=$get_index_today;$i++){
        $current_goal_completion[] = $final->completion_all[$i];
        $current_goal_completion_organic[] = $final->completion_all_organic[$i];
      }
    }


    if($get_indexprev ==  false && $get_index_prev == false){
      $previous_goal_completion[] = $previous_goal_completion_organic[] = 0;
    }elseif($get_indexprev && $get_index_prev ==  false){
      $end_prev = date('Y-m-d',strtotime('-1 day',strtotime($start_date))); 
      $get_index_prev = array_search($end_prev,$final->dates);

      if($get_index_prev == false){ 
       $get_index_prev = array_search(end($final->dates),$final->dates);
     }
     
     for($j=$get_indexprev;$j<=$get_index_prev;$j++){
      $previous_goal_completion[] = $final->completion_all[$j];
      $previous_goal_completion_organic[] = $final->completion_all_organic[$j];
    }
  }else{
    for($j=$get_indexprev;$j<=$get_index_prev;$j++){
      $previous_goal_completion[] = $final->completion_all[$j];
      $previous_goal_completion_organic[] = $final->completion_all_organic[$j];
    }
  }


  $final_current_goal_completion = array_sum($current_goal_completion);
  $final_previous_goal_completion = array_sum($previous_goal_completion);


  $final_current_goal_completion_organic = array_sum($current_goal_completion_organic);
  $final_previous_goal_completion_organic = array_sum($previous_goal_completion_organic);

  $result = array(
    'final_current_goal_completion' => $final_current_goal_completion,
    'final_previous_goal_completion' => $final_previous_goal_completion,
    'final_current_goal_completion_organic' => $final_current_goal_completion_organic,
    'final_previous_goal_completion_organic' => $final_previous_goal_completion_organic
  );
  return $result;
}
}



public function ajax_goal_completion_all_users_chart(Request $request){
  $result = array();
  $campaign_id = $request['campaign_id'];

  $data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

  if(!empty($data)){

    $default_duration = $data->duration;
    if($data->duration <= 3){
      $lapse ='+1 week';
      $start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
      $end_date = date('Y-m-d');
      $duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
    }


    if($data->duration >= 6 && $data->duration <= 12){
      $duration = $data->duration;
      $lapse ='+1 month';
    }

    if($data->duration == 24){
      $duration = $data->duration/3;
      $lapse = '+3 month';
    }
  }else{
    $duration =$default_duration =  3;
    $lapse = '+1 week';
    $start_date = date('Y-m-d',strtotime('-3 months'));
    $end_date = date('Y-m-d');
  }


  for($i=1;$i<=$duration;$i++){
    if($i==1){  
      $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

    }else{
      $start_date = date('Y-m-d',strtotime($end_date));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
    }
    $end_new[] = $end_date;


    $result[] = $this->goal_completion_users_chart($start_date,$end_date,$campaign_id);


  }     

  $dates['data'] = $result;
  $dates['from_datelabel'] = $end_new;

  return $dates; 

}

private function goal_completion_users_chart($start_date,$end_date,$campaign_id){
  $current_data = 0;
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
    return $current_data;
  } else {
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/statistics.json'; 
    $data = file_get_contents($url);

    $final = json_decode($data);


    $get_index = array_search($start_date,$final->dates);
    $get_index_today = array_search($end_date,$final->dates);

    if($get_index == false && $get_index_today == false){
      return 0;
    }elseif($get_index <> false && $get_index_today == false){
      $get_index_today = array_search(end($final->dates),$final->dates);
    }


    for($i=$get_index;$i<=$get_index_today;$i++){
      $current_data += $final->completion_all[$i];
    }

    return $current_data;
  }


}


public function ajax_goal_value_all_users_chart(Request $request){
  $result = array();
  $campaign_id = $request['campaign_id'];

  $data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

  if(!empty($data)){

    $default_duration = $data->duration;
    if($data->duration <= 3){
      $lapse ='+1 week';

      $start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
      $end_date = date('Y-m-d');
      $duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
    }


    if($data->duration >= 6 && $data->duration <= 12){
      $duration = $data->duration;
      $lapse ='+1 month';
    }

    if($data->duration == 24){
      $duration = $data->duration/3;
      $lapse = '+3 month';
    }
  }else{
    $duration =$default_duration =  3;
    $lapse = '+1 week';
    $start_date = date('Y-m-d',strtotime('-3 months'));
    $end_date = date('Y-m-d');
  }


  for($i=1;$i<=$duration;$i++){
    if($i==1){  
      $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

    }else{
      $start_date = date('Y-m-d',strtotime($end_date));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
    }
    $end_new[] = $end_date;
    $result[] = $this->goal_value_chart($start_date,$end_date,$campaign_id);


  }     
  $dates['data'] = $result;
  $dates['from_datelabel'] = $end_new;

  return $dates; 

}

private function goal_value_chart($start_date,$end_date,$campaign_id){
  $current_data = 0;
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
    return $current_data;
  } else {
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/statistics.json'; 
    $data = file_get_contents($url);

    $final = json_decode($data);

    $get_index = array_search($start_date,$final->dates);
    $get_index_today = array_search($end_date,$final->dates);

    if($get_index == false && $get_index_today == false){
      return 0;
    }elseif($get_index <> false && $get_index_today == false){
      $get_index_today = array_search(end($final->dates),$final->dates);
    }


    for($i=$get_index;$i<=$get_index_today;$i++){
      $current_data += $final->value_all[$i];
    }

    return $current_data;
  }
}

public function ajax_goal_conversion_all_users_chart(Request $request){
  $result = array();
  $campaign_id = $request['campaign_id'];

  $data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

  if(!empty($data)){

    $default_duration = $data->duration;
    if($data->duration <= 3){
      $lapse ='+1 week';

      $start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
      $end_date = date('Y-m-d');
      $duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
    }


    if($data->duration >= 6 && $data->duration <= 12){
      $duration = $data->duration;
      $lapse ='+1 month';
    }

    if($data->duration == 24){
      $duration = $data->duration/3;
      $lapse = '+3 month';
    }
  }else{
    $duration =$default_duration =  3;
    $lapse = '+1 week';
    $start_date = date('Y-m-d',strtotime('-3 months'));
    $end_date = date('Y-m-d');
  }


  for($i=1;$i<=$duration;$i++){
    if($i==1){  
      $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

    }else{
      $start_date = date('Y-m-d',strtotime($end_date));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
    }
    $end_new[] = $end_date;
    $number_of_days     =   (int)floor((strtotime($end_date) - strtotime($start_date))/(60*60*24));
    $result[] = $this->goal_conversionRate_chart($start_date,$end_date,$campaign_id,$number_of_days);


  }     
  $dates['data'] = $result;
  $dates['from_datelabel'] = $end_new;

  return $dates; 

}

private function goal_conversionRate_chart($start_date,$end_date,$campaign_id,$number_of_days){
  $current_data = 0;
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
    return $current_data;
  } else {
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/statistics.json'; 
    $data = file_get_contents($url);

    $final = json_decode($data);

    $get_index = array_search($start_date,$final->dates);
    $get_index_today = array_search($end_date,$final->dates);

    if($get_index == false && $get_index_today == false){
      return 0;
    }elseif($get_index <> false && $get_index_today == false){
      $get_index_today = array_search(end($final->dates),$final->dates);
    }


    for($i=$get_index;$i<=$get_index_today;$i++){
      $current_data += $final->conversionRate_all[$i]/$number_of_days;
    }


    return number_format($current_data,2);
  }
}

public function ajax_goal_abondon_all_users_chart(Request $request){
  $result = array();
  $campaign_id = $request['campaign_id'];

  $data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

  if(!empty($data)){

    $default_duration = $data->duration;
    if($data->duration <= 3){
      $lapse ='+1 week';

      $start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
      $end_date = date('Y-m-d');
      $duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
    }


    if($data->duration >= 6 && $data->duration <= 12){
      $duration = $data->duration;
      $lapse ='+1 month';
    }

    if($data->duration == 24){
      $duration = $data->duration/3;
      $lapse = '+3 month';
    }
  }else{
    $duration =$default_duration =  3;
    $lapse = '+1 week';
    $start_date = date('Y-m-d',strtotime('-3 months'));
    $end_date = date('Y-m-d');
  }


  for($i=1;$i<=$duration;$i++){
    if($i==1){  
      $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

    }else{
      $start_date = date('Y-m-d',strtotime($end_date));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
    }
    $end_new[] = $end_date;
    $number_of_days     =   (int)floor((strtotime($end_date) - strtotime($start_date))/(60*60*24));
    $result[] = $this->goal_abondonRate_chart($start_date,$end_date,$campaign_id,$number_of_days);


  }     
  $dates['data'] = $result;
  $dates['from_datelabel'] = $end_new;

  return $dates; 

}

private function goal_abondonRate_chart($start_date,$end_date,$campaign_id,$number_of_days){
  $current_data = 0;
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
    return $current_data;
  } else {
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/statistics.json'; 
    $data = file_get_contents($url);

    $final = json_decode($data);

    $get_index = array_search($start_date,$final->dates);
    $get_index_today = array_search($end_date,$final->dates);


    if($get_index == false && $get_index_today == false){
      return 0;
    }elseif($get_index <> false && $get_index_today == false){
      $get_index_today = array_search(end($final->dates),$final->dates);
    }


    for($i=$get_index;$i<=$get_index_today;$i++){
      $current_data += $final->abondonRate_all[$i]/$number_of_days;
    }

    return number_format($current_data,2);
  }
}

//organic charts

public function ajax_goal_completion_organic_chart(Request $request){
  $result = array();
  $campaign_id = $request['campaign_id'];
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
   $dates['data'] = [0];
   $dates['from_datelabel'] = [0];
 }else{

  $data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

  if(!empty($data)){

    $default_duration = $data->duration;
    if($data->duration <= 3){
      $lapse ='+1 week';

      $start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
      $end_date = date('Y-m-d');
      $duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
    }


    if($data->duration >= 6 && $data->duration <= 12){
      $duration = $data->duration;
      $lapse ='+1 month';
    }

    if($data->duration == 24){
      $duration = $data->duration/3;
      $lapse = '+3 month';
    }
  }else{
    $duration =$default_duration =  3;
    $lapse = '+1 week';
    $start_date = date('Y-m-d',strtotime('-3 months'));
    $end_date = date('Y-m-d');
  }


  for($i=1;$i<=$duration;$i++){
    if($i==1){  
      $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

    }else{
      $start_date = date('Y-m-d',strtotime($end_date));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
    }
    $end_new[] = $end_date;
    $start_date_new[] = $start_date;
    $result[] = $this->goal_completion_organic_chart($start_date,$end_date,$campaign_id);
  }     

  array_unshift($result,0);
  array_unshift($end_new,"");

  $dates['data'] = $result;
  $dates['from_datelabel'] = $end_new;
}

return $dates; 

}

private function goal_completion_organic_chart($start_date,$end_date,$campaign_id){
  $current_data = 0;
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
    return $current_data;
  } else {
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/statistics.json'; 
    $data = file_get_contents($url);

    $final = json_decode($data);

    $get_index = array_search($start_date,$final->dates);
    $get_index_today = array_search($end_date,$final->dates);

    if($get_index == false && $get_index_today == false){
      return 0;
    }elseif($get_index <> false && $get_index_today == false){
      $get_index_today = array_search(end($final->dates),$final->dates);
    }


    for($i=$get_index;$i<=$get_index_today;$i++){
      $current_data += $final->completion_all_organic[$i];
    }

    return $current_data;
  }


}


public function ajax_goal_value_organic_chart(Request $request){
  $result = array();
  $campaign_id = $request['campaign_id'];

  $data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

  if(!empty($data)){

    $default_duration = $data->duration;
    if($data->duration <= 3){
      $lapse ='+1 week';

      $start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
      $end_date = date('Y-m-d');
      $duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
    }


    if($data->duration >= 6 && $data->duration <= 12){
      $duration = $data->duration;
      $lapse ='+1 month';
    }

    if($data->duration == 24){
      $duration = $data->duration/3;
      $lapse = '+3 month';
    }
  }else{
    $duration =$default_duration =  3;
    $lapse = '+1 week';
    $start_date = date('Y-m-d',strtotime('-3 months'));
    $end_date = date('Y-m-d');
  }


  for($i=1;$i<=$duration;$i++){
    if($i==1){  
      $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

    }else{
      $start_date = date('Y-m-d',strtotime($end_date));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
    }
    $end_new[] = $end_date;
    $result[] = $this->goal_value_organic_chart($start_date,$end_date,$campaign_id);


  }     
  $dates['data'] = $result;
  $dates['from_datelabel'] = $end_new;

  return $dates; 

}

private function goal_value_organic_chart($start_date,$end_date,$campaign_id){
  $current_data = 0;
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
    return $current_data;
  } else {
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/statistics.json'; 
    $data = file_get_contents($url);

    $final = json_decode($data);

    $get_index = array_search($start_date,$final->dates);
    $get_index_today = array_search($end_date,$final->dates);

    if($get_index == false && $get_index_today == false){
      return 0;
    }elseif($get_index <> false && $get_index_today == false){
      $get_index_today = array_search(end($final->dates),$final->dates);
    }


    for($i=$get_index;$i<=$get_index_today;$i++){
      $current_data += $final->value_all_organic[$i];
    }

    return $current_data;
  }
}


public function ajax_conversion_rate_organic_chart(Request $request){
  $result = array();
  $campaign_id = $request['campaign_id'];

  $data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

  if(!empty($data)){

    $default_duration = $data->duration;
    if($data->duration <= 3){
      $lapse ='+1 week';

      $start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
      $end_date = date('Y-m-d');
      $duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
    }


    if($data->duration >= 6 && $data->duration <= 12){
      $duration = $data->duration;
      $lapse ='+1 month';
    }

    if($data->duration == 24){
      $duration = $data->duration/3;
      $lapse = '+3 month';
    }
  }else{
    $duration =$default_duration =  3;
    $lapse = '+1 week';
    $start_date = date('Y-m-d',strtotime('-3 months'));
    $end_date = date('Y-m-d');
  }


  for($i=1;$i<=$duration;$i++){
    if($i==1){  
      $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

    }else{
      $start_date = date('Y-m-d',strtotime($end_date));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
    }
    $end_new[] = $end_date;
    $number_of_days     =   (int)floor((strtotime($end_date) - strtotime($start_date))/(60*60*24));
    $result[] = $this->conversion_rate_organic_chart($start_date,$end_date,$campaign_id,$number_of_days);


  }     
  $dates['data'] = $result;
  $dates['from_datelabel'] = $end_new;

  return $dates; 

}

private function conversion_rate_organic_chart($start_date,$end_date,$campaign_id,$number_of_days){
  $current_data = 0;
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
    return $current_data;
  } else {
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/statistics.json'; 
    $data = file_get_contents($url);

    $final = json_decode($data);

    $get_index = array_search($start_date,$final->dates);
    $get_index_today = array_search($end_date,$final->dates);

    if($get_index == false && $get_index_today == false){
      return 0;
    }elseif($get_index <> false && $get_index_today == false){
      $get_index_today = array_search(end($final->dates),$final->dates);
    }


    for($i=$get_index;$i<=$get_index_today;$i++){
      $current_data += $final->conversionRate_all_organic[$i]/$number_of_days;
    }

    return number_format($current_data,2);
  }
}

public function ajax_abondon_rate_organic_chart(Request $request){
  $result = array();
  $campaign_id = $request['campaign_id'];

  $data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','organic_traffic')->first();

  if(!empty($data)){

    $default_duration = $data->duration;
    if($data->duration <= 3){
      $lapse ='+1 week';

      $start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
      $end_date = date('Y-m-d');
      $duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
    }


    if($data->duration >= 6 && $data->duration <= 12){
      $duration = $data->duration;
      $lapse ='+1 month';
    }

    if($data->duration == 24){
      $duration = $data->duration/3;
      $lapse = '+3 month';
    }
  }else{
    $duration =$default_duration =  3;
    $lapse = '+1 week';
    $start_date = date('Y-m-d',strtotime('-3 months'));
    $end_date = date('Y-m-d');
  }


  for($i=1;$i<=$duration;$i++){
    if($i==1){  
      $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));

    }else{
      $start_date = date('Y-m-d',strtotime($end_date));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
    }
    $end_new[] = $end_date;
    $number_of_days     =   (int)floor((strtotime($end_date) - strtotime($start_date))/(60*60*24));
    $result[] = $this->abondon_rate_organic_chart($start_date,$end_date,$campaign_id,$number_of_days);


  }     
  $dates['data'] = $result;
  $dates['from_datelabel'] = $end_new;

  return $dates; 

}

private function abondon_rate_organic_chart($start_date,$end_date,$campaign_id,$number_of_days){
  $current_data = 0;
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
    return $current_data;
  } else {
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/statistics.json'; 
    $data = file_get_contents($url);

    $final = json_decode($data);

    $get_index = array_search($start_date,$final->dates);
    $get_index_today = array_search($end_date,$final->dates);

    if($get_index == false && $get_index_today == false){
      return 0;
    }elseif($get_index <> false && $get_index_today == false){
      $get_index_today = array_search(end($final->dates),$final->dates);
    }


    for($i=$get_index;$i<=$get_index_today;$i++){
      $current_data += $final->abondonRate_all_organic[$i]/$number_of_days;
    }

    return number_format($current_data,2);
  }
}

public function ajax_get_analytics_daterange_data(Request $request){
  $today = date('Y-m-d');
  $range = $request['value'];
  $campaign_id = $request['campaignId'];
  $type = $request['type']?:'day';
  $module = 'organic_traffic';
  $compare_value = $request->compare_value;


  $state = ($request->has('key'))?'viewkey':'user';

  if(Auth::user() <> null){
              $user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
              $role_id = User::get_user_role(Auth::user()->id);
            }else{
              $getUser = SemrushUserAccount::where('id',$campaign_id)->first();
              $user_id = User::get_parent_user_id($getUser->user_id); //get user id from child
              $role_id = User::get_user_role($getUser->user_id);
            }

            $ifExistsCompare = ProjectCompareGraph::getCompareChart($campaign_id);

            if($state == 'user'){
              if($ifExistsCompare <> null){
                ProjectCompareGraph::updateOrCreate(
                  ['request_id'=> $ifExistsCompare->request_id,'id'=> $ifExistsCompare->id],
                  [
                    'request_id'=>$campaign_id,
                    'user_id'=>$user_id,
                    'compare_status'=>$compare_value
                  ]
                );
              }else{
                ProjectCompareGraph::create(
                  [
                    'request_id'=>$campaign_id,
                    'user_id'=>$user_id,
                    'compare_status'=>$compare_value
                  ]
                );
              }

              sleep(1);

              $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);

              if(!empty($getCompareChart)){
                $compare_status = $getCompareChart->compare_status;
              }else{
                $compare_status = 0;
              }
            }else{
              $compare_status = $request->compare_value;
            }


            if($range == 'week'){
              $start_date_1 = date('Y-m-d',strtotime('-1 week'));
              $duration =1;
            } elseif($range == 'month'){
              $start_date_1 = date('Y-m-d',strtotime('-1 month'));
              $duration =1;
            }elseif($range == 'three'){
              $start_date_1 = date('Y-m-d',strtotime('-3 month'));
              $duration = 3;
            }elseif($range == 'six'){
              $start_date_1 = date('Y-m-d',strtotime('-6 month'));
              $duration =6;
            }elseif($range == 'nine'){
              $start_date_1 = date('Y-m-d',strtotime('-9 month'));
              $duration =9;
            }elseif($range == 'year'){
              $start_date_1 = date('Y-m-d',strtotime('-1 year'));
              $duration =12;
            }elseif($range == 'twoyear'){
              $start_date_1 = date('Y-m-d',strtotime('-2 year'));
              $duration =24;
            }else{
              $start_date_1 = date('Y-m-d',strtotime('-3 month'));
              $duration =3;
            }


            if (!file_exists(env('FILE_PATH')."public/analytics/".$campaign_id)) {
             $res['status'] = 0;
           } else {
            $ifCheck = ModuleByDateRange::where('request_id',$campaign_id)->where('module',$module)->first();


            if($role_id != 4 && $state == 'user'){
              if($ifCheck <> null){
                ModuleByDateRange::updateOrCreate(
                 ['id'=>$ifCheck->id],
                 [
                   'user_id'=>$user_id,
                   'request_id'=>$campaign_id,
                   'duration'=>$duration,
                   'module'=>$module,
                   'display_type'=>$type
                 ]
               );
              }else{
                ModuleByDateRange::create(
                  [
                   'user_id'=>$user_id,
                   'request_id'=>$campaign_id,
                   'duration'=>$duration,
                   'module'=>$module,
                   'display_type'=>$type
                 ]
               );
              }

            }

            sleep(1);

            $updatedValue = ModuleByDateRange::where('request_id',$campaign_id)->where('module',$module)->first();

            if($state == 'viewkey'){
              $default_duration = $duration;
            }else{ 
              if($updatedValue){
                $default_duration = $updatedValue->duration;
              }else{
                $default_duration =  3;
              }
            }


            $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));

            $end_date = date('Y-m-d');


            if($range == 'year'){
             $prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
             $prev_end_dates =  date('Y-m-d',strtotime(' -1 year',strtotime($prev_date_1)));
           }
           elseif($range == 'twoyear'){
             $prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
             $prev_end_dates =  date('Y-m-d',strtotime(' -2 year',strtotime($prev_date_1)));           
           }
           else{
             $prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
             $prev_end_dates =  date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_date_1)));
           }


           if($type == 'day'){

            $duration = ModuleByDateRange::calculate_days($start_date,$end_date);
            for($i=1;$i<=$duration;$i++){
              if($i==1){  
                $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
                $prev_start_date = date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_date_1))); 
              }else{
                $start_date = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
                $prev_start_date = date('Y-m-d',strtotime('+1 day',strtotime($prev_end_date))); 
              }

              $end_date = date('Y-m-d',strtotime('+0 day',strtotime($start_date)));    
              $prev_end_date = date('Y-m-d',strtotime('+0 day',strtotime($prev_start_date))); 


              $result[] = $this->traffic_growth_chart($start_date,$end_date,$prev_start_date,$prev_end_date,$campaign_id,$type);
              if($default_duration == 1 || $default_duration == 3){
                $current[] = date('M d, Y',strtotime($start_date));
              }else{
                $current[] = date('M Y',strtotime($start_date));
              }
              $current_dates[] = date('l, F d, Y',strtotime($start_date));
              $current_prev_dates[] = date('l, F d, Y',strtotime($prev_start_date));
            } 
          }

          if($type == 'week'){
            $duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
            $i =1;
            $csd = $prev_end_dates;
            $sd = $start_date;
            for( ; ;){
              $start = $sd;
              $previous_start = $csd;

              if($i == 1){
                if(date('D', strtotime($start)) == 'Sun'){
                  $end_date = date('Y-m-d',strtotime('saturday next week',strtotime($start)));
                }else{  
                  $end_date = date('Y-m-d',strtotime('saturday this week',strtotime($start)));
                }

                if(date('D', strtotime($previous_start)) == 'Sun'){
                  $previous_end = date('Y-m-d',strtotime('saturday next week',strtotime($previous_start)));
                }else{  
                  $previous_end = date('Y-m-d',strtotime('saturday this week',strtotime($previous_start)));
                }
              }else{
                $end_date = date('Y-m-d',strtotime('saturday next week',strtotime($start)));
                $previous_end = date('Y-m-d',strtotime('saturday next week',strtotime($previous_start)));
              }


              $sd = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
              $csd = date('Y-m-d',strtotime('+1 day',strtotime($previous_end)));


              if($previous_end > $start_date){
                $previousEnd = date('Y-m-d',strtotime('-1 day',strtotime($start_date))); 
              }else{
                $previousEnd = $previous_end;
              }

              if($end_date  > date('Y-m-d')){
                $enddate = $today;
                $result[] = $this->traffic_growth_chart($start,$enddate,$previous_start,$previousEnd,$campaign_id,$type);

                if($default_duration == 1 || $default_duration == 3){
                  $current[] = date('M d, Y',strtotime($start));
                }else{
                  $current[] = date('M Y',strtotime($start));
                }

                $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($enddate));
                $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
                break;
              }else{
                $result[] = $this->traffic_growth_chart($start,$end_date,$previous_start,$previousEnd,$campaign_id,$type);
                if($default_duration == 1 || $default_duration == 3){
                  $current[] = date('M d, Y',strtotime($start));
                }else{
                  $current[] = date('M Y',strtotime($start));
                }
                $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($end_date));
                $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
              }

              $i++;
            }
          }



          if($type == 'month'){
            $i = 1;
            $csd = $prev_end_dates;
            $sd = $start_date;
            for( ; ;){
              $start = $sd;
              $previous_start = $csd;

              $end_date = date('Y-m-d',strtotime(date("Y-m-t", strtotime($start))));
              $previous_end = date('Y-m-d',strtotime(date("Y-m-t", strtotime($previous_start))));


              $sd = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
              $csd = date('Y-m-d',strtotime('+1 day',strtotime($previous_end)));

              if($previous_end > $start_date){
                $previousEnd = date('Y-m-d',strtotime('-1 day',strtotime($start_date))); 
              }else{
                $previousEnd = $previous_end;
              }

              if($end_date  > date('Y-m-d')){
                $enddate = $today;

                $result[] = $this->traffic_growth_chart($start,$enddate,$previous_start,$previousEnd,$campaign_id,$type);
                if($default_duration == 1 || $default_duration == 3){
                  $current[] = date('M d, Y',strtotime($start));
                }else{
                  $current[] = date('M Y',strtotime($start));
                }
                $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($enddate));
                $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
                break;
              }else{
                $result[] = $this->traffic_growth_chart($start,$end_date,$previous_start,$previousEnd,$campaign_id,$type);
                if($default_duration == 1 || $default_duration == 3){
                  $current[] = date('M d, Y',strtotime($start));
                }else{
                  $current[] = date('M Y',strtotime($start));
                }
                $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($end_date));
                $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
              }
              $i++;
            }
          }

          $current_data = array_column($result,'current_data');
          $prev_data = array_column($result,'prev_data');


          if(count($current_data) == 1){
            array_unshift( $current_data,null );
            $current_data = array_merge($current_data,array(null));
            $current = array_merge($current,array(''));
            array_unshift( $current,'' );
          }
          if(count($prev_data) == 1){
            array_unshift( $prev_data, null );
            $prev_data = array_merge($prev_data,array(null)); 
          }

          $current_period  = date('M d, Y', strtotime($start_date_1)).' - '.date('M d, Y', strtotime($today));
          $prev_period   = date('M d, Y', strtotime($prev_end_dates)).' - '.date('M d, Y', strtotime($prev_date_1));



          $res['from_datelabel'] = $current;
          $res['from_datelabels'] = $current_dates;
          $res['prev_from_datelabels'] = $current_prev_dates;
          $res['count_session'] = $current_data;
          $res['combine_session'] = $prev_data;
          $res['previous_period'] = $prev_period;
          $res['current_period'] = $current_period;
          $res['compare_status'] = $compare_status;
          $res['status'] = 1;

        }
        return response()->json($res);
      }



      private function traffic_growth_chart($start_date,$end_date,$prev_start_date,$prev_end_date,$campaign_id,$type){
        $current_data = $prev_data = 0; $current_prev = $current_prev_dates = '';
        if (!file_exists(env('FILE_PATH')."public/analytics/".$campaign_id)) {
          $current_data = $prev_data = 0;
        }else{
          $url = env('FILE_PATH')."public/analytics/".$campaign_id.'/graph.json'; 
          $data = file_get_contents($url);
          $final = json_decode($data);

          $get_index = array_search($start_date,$final->dates_format);
          $get_index_today = array_search($end_date,$final->dates_format);

          if($get_index <> false && $get_index_today == false){
            $get_index_today = array_search(end($final->dates_format),$final->dates_format);
          }

          $get_indexprev = array_search($prev_start_date,$final->dates_format);
          $get_index_prev = array_search($prev_end_date,$final->dates_format);

          if($type == 'week' || $type == 'month'){
           if($get_index == false && $get_index_today == false){
             $current = date('M d',strtotime($start_date));
             $current_dates = date('l, F d, Y',strtotime($start_date));
             $current_data = 0;
           }elseif($get_index  && $get_index_today == false){
            $get_index_today = array_search(end($final->dates_format),$final->dates_format);
            for($i=$get_index;$i<=$get_index_today;$i++){
              $current = date('M d',strtotime($final->from_dates[$i]));
              $current_dates = date('l, F d, Y',strtotime($final->dates_format[$i]));
              $current_data += $final->final_array[$i];
            }
          }else{
            for($i=$get_index;$i<=$get_index_today;$i++){
              $current = date('M d',strtotime($final->from_dates[$i]));
              $current_dates = date('l, F d, Y',strtotime($final->dates_format[$i]));
              $current_data += $final->final_array[$i];
            }
          }


          if($get_index_prev == false && $get_indexprev == false){
           $current_prev = date('M d',strtotime($prev_start_date));
           $current_prev_dates = date('l, F d, Y',strtotime($prev_start_date));
           $prev_data = 0;
         }elseif($get_index_prev == false && $get_indexprev){
          $get_index_prev = array_search(end($final->dates_format),$final->dates_format);

          for($j=$get_indexprev;$j<=$get_index_prev;$j++){
            $current_prev = $final->from_dates[$j];
            $current_prev_dates = date('l, F d, Y',strtotime($final->dates_format[$j]));
            $prev_data += $final->final_array[$j];
          }

        }else{
          for($j=$get_indexprev;$j<=$get_index_prev;$j++){
            $current_prev = $final->from_dates[$j];
            $current_prev_dates = date('l, F d, Y',strtotime($final->dates_format[$j]));
            $prev_data += $final->final_array[$j];
          }
        }
      }else{
       if($get_index == false && $get_index_today == false){
        $current = date('M d',strtotime($start_date));
        $current_dates = date('l, F d, Y',strtotime($start_date));
        $current_data = 0;
      }else{
        for($i=$get_index;$i<=$get_index_today;$i++){
          $current = date('M d',strtotime($final->from_dates[$i]));
          $current_dates = date('l, F d, Y',strtotime($final->dates_format[$i]));
          $current_data = $final->final_array[$i];
        }
      }

      if($get_index_prev == false && $get_indexprev == false){
        $current_prev = date('M d',strtotime($prev_start_date));
        $current_prev_dates = date('l, F d, Y',strtotime($prev_start_date));
        $prev_data = 0;
      }else{
        for($j=$get_indexprev;$j<=$get_index_prev;$j++){
          $current_prev = $final->from_dates[$j];
          $current_prev_dates = date('l, F d, Y',strtotime($final->dates_format[$j]));
          $prev_data = $final->final_array[$j];
        }
      }
    }

    return array('current_data'=>$current_data,'prev_data'=>$prev_data,'current'=>$current,'current_dates'=>$current_dates,'current_prev'=>$current_prev,'current_prev_dates'=>$current_prev_dates);
  }
}

public function ajax_get_goal_completion_chart_data(Request $request){
  $campaign_id = $request['campaign_id'];
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
    $res['status'] = 0;
  } else {
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/graph.json'; 
    $data = file_get_contents($url);
    $final = json_decode($data);

    $dates = $this->make_dates($campaign_id);


    $compare_status = $dates['compare_status'];
    $type = $dates['type'];
    $start_date = $dates['start_date'];
    $end_date = $dates['end_date'];
    $prev_date_1 = $dates['prev_date_1'];
    $prev_end_dates = $dates['prev_end_dates'];
    $default_duration = $dates['default_duration'];
    $start_date_1 = $dates['start_date_1'];
    $duration = $dates['duration'];

    if($type == 'day'){
      $duration = ModuleByDateRange::calculate_days($start_date,$end_date);

      for($i=1;$i<=$duration;$i++){
        if($i==1){  
          $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
          $prev_start_date = date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_date_1))); 
        }else{
          $start_date = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
          $prev_start_date = date('Y-m-d',strtotime('+1 day',strtotime($prev_end_date))); 
        }

        $end_date = date('Y-m-d',strtotime('+0 day',strtotime($start_date)));    
        $prev_end_date = date('Y-m-d',strtotime('+0 day',strtotime($prev_start_date))); 

        $result[] = $this->goal_completion_chart($start_date,$end_date,$prev_start_date,$prev_end_date,$campaign_id,$type);
        if($default_duration == 1 || $default_duration == 3){
          $current[] = date('M d, Y',strtotime($start_date));
        }else{
          $current[] = date('M Y',strtotime($start_date));
        }
        $current_dates[] = date('l, F d, Y',strtotime($start_date));
        $current_prev_dates[] = date('l, F d, Y',strtotime($prev_start_date));           
      }
    }


    if($type == 'week'){
      $i =1;
      $csd = $prev_end_dates;
      $sd = $start_date;
      /*infinite for loop*/
      for( ; ;){
        $start = $sd;
        $previous_start = $csd;

        if($i == 1){
          if(date('D', strtotime($start)) == 'Sun'){
            $end_date = date('Y-m-d',strtotime('saturday next week',strtotime($start)));
          }else{  
            $end_date = date('Y-m-d',strtotime('saturday this week',strtotime($start)));
          }

          if(date('D', strtotime($previous_start)) == 'Sun'){
            $previous_end = date('Y-m-d',strtotime('saturday next week',strtotime($previous_start)));
          }else{  
            $previous_end = date('Y-m-d',strtotime('saturday this week',strtotime($previous_start)));
          }
        }else{
          $end_date = date('Y-m-d',strtotime('saturday next week',strtotime($start)));
          $previous_end = date('Y-m-d',strtotime('saturday next week',strtotime($previous_start)));
        }


        $sd = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
        $csd = date('Y-m-d',strtotime('+1 day',strtotime($previous_end)));


        if($previous_end > $start_date){
          $previousEnd = date('Y-m-d',strtotime('-1 day',strtotime($start_date))); 
        }else{
          $previousEnd = $previous_end;
        }

        /*to break the infinite loop at the last entry*/
        if($end_date  > date('Y-m-d')){
          $enddate = date('Y-m-d');
          $result[] = $this->goal_completion_chart($start,$enddate,$previous_start,$previousEnd,$campaign_id,$type);
          if($default_duration == 1){
            $current[] = date('M d, Y',strtotime($start));
          }else{
            $current[] = date('M Y',strtotime($start));
          }
          $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($enddate));
          $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
          break; 
        }else{
          $result[] = $this->goal_completion_chart($start,$end_date,$previous_start,$previousEnd,$campaign_id,$type);
          if($default_duration == 1 || $default_duration == 3){
            $current[] = date('M d, Y',strtotime($start));
          }else{
            $current[] = date('M Y',strtotime($start));
          }
          $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($end_date));
          $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
        }

        $i++;
      }
    }      

    if($type == 'month'){
      $i = 1;
      $csd = $prev_end_dates;
      $sd = $start_date;
      for( ; ;){
        $start = $sd;
        $previous_start = $csd;

        $end_date = date('Y-m-d',strtotime(date("Y-m-t", strtotime($start))));
        $previous_end = date('Y-m-d',strtotime(date("Y-m-t", strtotime($previous_start))));


        $sd = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
        $csd = date('Y-m-d',strtotime('+1 day',strtotime($previous_end)));

        if($previous_end > $start_date){
          $previousEnd = date('Y-m-d',strtotime('-1 day',strtotime($start_date))); 
        }else{
          $previousEnd = $previous_end;
        }

        if($end_date  > date('Y-m-d')){
          $enddate = date('Y-m-d');

          $result[] = $this->goal_completion_chart($start,$enddate,$previous_start,$previousEnd,$campaign_id,$type);
          if($default_duration == 1 || $default_duration == 3){
            $current[] = date('M d, Y',strtotime($start));
          }else{
            $current[] = date('M Y',strtotime($start));
          }
          $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($enddate));
          $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
          break;
        }else{
          $result[] = $this->goal_completion_chart($start,$end_date,$previous_start,$previousEnd,$campaign_id,$type);
          if($default_duration == 1 || $default_duration == 3){
            $current[] = date('M d, Y',strtotime($start));
          }else{
            $current[] = date('M Y',strtotime($start));
          }
          $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($end_date));
          $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
        }
        $i++;
      }

    }  


    $current_users = array_column($result,'current_users');
    $current_organic = array_column($result,'current_organic');
    $prev_users = array_column($result,'prev_users');
    $prev_organic = array_column($result,'prev_organic');

    $current_period  = date('M d, Y', strtotime($start_date_1)).' - '.date('M d, Y', strtotime(date('Y-m-d')));
    $prev_period   = date('M d, Y', strtotime($prev_end_dates)).' - '.date('M d, Y', strtotime($prev_date_1));


    $res['from_datelabel'] = $current;
    $res['from_datelabels'] = $current_dates;
    $res['prev_from_datelabels'] = $current_prev_dates;
    $res['users'] = $current_users;
    $res['previous_users'] = $prev_users;
    $res['organic'] = $current_organic;
    $res['previous_organic'] = $prev_organic;
    $res['current_period'] = $current_period;
    $res['previous_period'] = $prev_period;
    $res['compare_status'] = $compare_status;
    $res['status'] = 1;

  }

  return response()->json($res);
}

private function goal_completion_chart($start_date,$end_date,$prev_start_date,$prev_end_date,$campaign_id,$type){
  $current_users = $current_organic = $prev_users = $prev_organic = 0;
  $current_prev = $current_prev_dates = '';
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
    $current_users = $current_organic = $prev_users = $prev_organic = 0;
  }else{
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/graph.json'; 
    $data = file_get_contents($url);
    $final = json_decode($data);

    $get_index = array_search($start_date,$final->dates_format);
    $get_index_today = array_search($end_date,$final->dates_format);
    if($get_index <> false && $get_index_today == false){
      $get_index_today = array_search(end($final->dates_format),$final->dates_format);
    }

    $get_indexprev = array_search($prev_start_date,$final->dates_format);
    $get_index_prev = array_search($prev_end_date,$final->dates_format);


    if($type == 'week' || $type == 'month'){
      if($get_index == false && $get_index_today == false){
        $current = date('M d',strtotime($start_date));
        $current_dates = date('l, F d,Y',strtotime($start_date));
        $current_users = 0;
        $current_organic = 0;
      }else{
        for($i=$get_index;$i<=$get_index_today;$i++){
          $current = date('M d',strtotime($final->dates_format[$i]));
          $current_dates = date('l, F d,Y',strtotime($final->dates_format[$i]));
          $current_users += $final->final_user_data[$i];
          $current_organic += $final->final_organic_data[$i];
        }
      }



      if($get_index_prev == false && $get_indexprev == false){
        $current_prev = date('M d',strtotime($prev_start_date));
        $current_prev_dates = date('l, F d,Y',strtotime($prev_start_date));
        $prev_users = 0;
        $prev_organic = 0;
      }else{
        for($j=$get_index_prev;$j>=$get_indexprev;$j--){
          $current_prev = $final->dates_format[$j];
          $current_prev_dates = date('l, F d,Y',strtotime($final->dates_format[$j]));
          $prev_users += $final->final_user_data[$j];
          $prev_organic += $final->final_organic_data[$j];
        }
      }
    }else{
      if($get_index == false && $get_index_today == false){
        $current = date('M d',strtotime($start_date));
        $current_dates = date('l, F d,Y',strtotime($start_date));
        $current_users = 0;
        $current_organic = 0;
      }else{
        for($i=$get_index;$i<=$get_index_today;$i++){
          $current = date('M d',strtotime($final->dates_format[$i]));
          $current_dates = date('l, F d,Y',strtotime($final->dates_format[$i]));
          $current_users = $final->final_user_data[$i];
          $current_organic = $final->final_organic_data[$i];
        }
      }
      if($get_index_prev == false && $get_indexprev == false){
        $current_prev = date('M d',strtotime($prev_start_date));
        $current_prev_dates = date('l, F d,Y',strtotime($prev_start_date));
        $prev_users = 0;
        $prev_organic = 0;
      }else{
        for($j=$get_indexprev;$j<=$get_index_prev;$j++){
          $current_prev = $final->dates_format[$j];
          $current_prev_dates = date('l, F d,Y',strtotime($final->dates_format[$j]));
          $prev_users = $final->final_user_data[$j];
          $prev_organic = $final->final_organic_data[$j];
        }
      }
    }
    return array('current'=>$current,'current_dates'=>$current_dates,'current_users'=>$current_users,'current_organic'=>$current_organic,'current_prev'=>$current_prev,'current_prev_dates'=>$current_prev_dates,'prev_users'=>$prev_users,'prev_organic'=>$prev_organic);
  }
}


private function make_dates($campaign_id){
  $result =  array();
  $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
  if(!empty($getCompareChart)){
    $compare_status = $getCompareChart->compare_status;
  }else{
    $compare_status = 0;
  }

  $sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');
  if(empty($sessionHistoryRange) && $sessionHistoryRange == null){
    $start_date_1 = date('Y-m-d',strtotime('-3 month'));
    $duration = 3;
    $type = 'day';
    $range = 'three';
  }else{
    if($sessionHistoryRange->duration == 1){
      $start_date_1   = date('Y-m-d', strtotime('-1 month'));
      $duration =1;
      $range = 'month';
    }elseif($sessionHistoryRange->duration == 3){
      $start_date_1   = date('Y-m-d', strtotime('-3 month'));
      $duration = 3;
      $range = 'three';
    }elseif($sessionHistoryRange->duration == 6){
      $start_date_1   = date('Y-m-d', strtotime('-6 month'));
      $duration = 6;
      $range = 'six';
    }elseif($sessionHistoryRange->duration == 9){
      $start_date_1   = date('Y-m-d', strtotime('-9 month'));
      $duration = 9;
      $range = 'nine';
    }elseif($sessionHistoryRange->duration == 12){
      $start_date_1   = date('Y-m-d', strtotime('-1 year'));
      $duration = 12;
      $range = 'year';
    }elseif($sessionHistoryRange->duration == 24){
      $start_date_1   = date('Y-m-d', strtotime('-2 year'));
      $duration = 24;
      $range = 'twoyear';
    }
    $type = ($sessionHistoryRange->display_type)?:'day';
  }

  sleep(1);

  $updatedValue = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

  if($updatedValue){
    $default_duration = $updatedValue->duration;
  }else{
    $default_duration =  3;
  }

  if($range == 'year'){
   $prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
   $prev_end_dates =  date('Y-m-d',strtotime(' -1 year',strtotime($prev_date_1)));
 }
 elseif($range == 'twoyear'){
   $prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
   $prev_end_dates =  date('Y-m-d',strtotime(' -2 year',strtotime($prev_date_1)));           
 }
 else{
   $prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
   $prev_end_dates =  date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_date_1)));
 }

 $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
 $end_date = date('Y-m-d');

 $result['start_date'] = $start_date;
 $result['end_date'] = $end_date;
 $result['prev_date_1'] = $prev_date_1;
 $result['prev_end_dates'] = $prev_end_dates;
 $result['default_duration'] = $default_duration;
 $result['compare_status'] = $compare_status;
 $result['start_date_1'] = $start_date_1;
 $result['type'] = $type;
 $result['duration'] = $duration;

 return $result;
}

public function ajax_goal_completion_organic_chart_overview(Request $request){
  $result = array();
  $campaign_id = $request['campaign_id'];
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
   $dates['data'] = [0];
   $dates['from_datelabel'] = [0];
 }else{
  $lapse ='-7 day';
  $end_date = date('Y-m-d');
  for($i=1;$i<=6;$i++){
    if($i==1){
      $start_date = date('Y-m-d',strtotime($end_date));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
    }else{
      $start_date = date('Y-m-d',strtotime('-1 day',strtotime($end_date)));
      $end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
    }

    $end_new[] = date('M d, Y',strtotime($end_date));
    $start_date_new[] = date('M d, Y',strtotime($start_date));
    $result[] = $this->goal_completion_organic_chart($end_date,$start_date,$campaign_id);
  }     
  $result = array_reverse($result);
  $end_new = array_reverse($end_new);

  array_unshift($result, 0);
  array_unshift($end_new, "");


  $dates['data'] = $result;
  $dates['from_datelabel'] = $end_new;
}
return $dates; 
}

public function ajax_get_goal_completion_stats_overview(Request $request){
  $result = array();
  $campaign_id = $request['campaign_id'];
  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
   $res['goal_completion_percentage_organic'] = '??';
   $res['current_goal_completion_organic'] = '??';
 }else{
   $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/statistics.json'; 
   $data = file_get_contents($url);
   $final = json_decode($data);

   $goals = $goals_current = 0;
   $project_data = SemrushUserAccount::get_created_date($campaign_id);

   $current_start_date = date('Y-m-d',strtotime(now()));
   $current_end_date = date('Y-m-d',strtotime('-30 day'));

   $current_start = array_search($current_start_date,$final->dates);
   $current_end = array_search($current_end_date,$final->dates);

   $end_month_date = date('Y-m-d',strtotime('-30 day',strtotime($project_data->domain_register)));
   $start_month_date = date('Y-m-d',strtotime($project_data->domain_register));

   $prev_start = array_search($start_month_date,$final->dates);
   $prev_end = array_search($end_month_date,$final->dates);

   if($prev_start !== false && $prev_end !== false){
    for($i=$prev_end;$i<=$prev_start;$i++){
      $goals += $final->completion_all_organic[$i];
    }
  }else{
    for($i=0;$i<=29;$i++){
      $goals += $final->completion_all_organic[$i];
    }
  }

  if($current_end !== false && $current_start !== false){
    for($j=$current_end;$j<=$current_start;$j++){
      $goals_current += $final->completion_all_organic[$j];
    } 
  }else{
    $current_start_date = end($final->dates);
    $current_start = array_search($current_start_date,$final->dates);
    $current_end_date = date('Y-m-d',strtotime('-30 day',strtotime($current_start_date)));
    $current_end = array_search($current_end_date,$final->dates);
    
    for($j=$current_end;$j<=$current_start;$j++){
      $goals_current += $final->completion_all_organic[$j];
    }
  }
  $goal_completion_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($goals_current,$goals);



  $res['current_goal_completion_organic'] = shortNumbers($goals_current);
  $res['goal_completion_percentage_organic'] = $goal_completion_percentage_organic;
  $res['status'] = 1;
} 
return response()->json($res);
}


/*viewkey goals function*/

public function ajax_get_goal_completion_chart_data_viewkey(Request $request){
  $today = date('Y-m-d');
  $range = $request['value'];
  $campaign_id = $request['campaign_id'];
  $type = $request['type']?:'day';
  $module = 'organic_traffic';
  $compare_status = ($request->compare_value)?$request->compare_value:0;
  $state = ($request->has('key'))?'viewkey':'user';


  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
    $res['status'] = 0;
  } else {
   if($range == 'month'){
    $start_date_1 = date('Y-m-d',strtotime('-1 month'));
    $default_duration =1;
  }elseif($range == 'three'){
    $start_date_1 = date('Y-m-d',strtotime('-3 month'));
    $default_duration = 3;
  }elseif($range == 'six'){
    $start_date_1 = date('Y-m-d',strtotime('-6 month'));
    $default_duration =6;
  }elseif($range == 'nine'){
    $start_date_1 = date('Y-m-d',strtotime('-9 month'));
    $default_duration =9;
  }elseif($range == 'year'){
    $start_date_1 = date('Y-m-d',strtotime('-1 year'));
    $default_duration =12;
  }elseif($range == 'twoyear'){
    $start_date_1 = date('Y-m-d',strtotime('-2 year'));
    $default_duration =24;
  }else{
    $start_date_1 = date('Y-m-d',strtotime('-3 month'));
    $default_duration =3;
  }



  $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/graph.json'; 
  $data = file_get_contents($url);
  $final = json_decode($data);

  $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
  $end_date = date('Y-m-d');

  if($range == 'year'){
   $prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
   $prev_end_dates =  date('Y-m-d',strtotime(' -1 year',strtotime($prev_date_1)));
 }
 elseif($range == 'twoyear'){
   $prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
   $prev_end_dates =  date('Y-m-d',strtotime(' -2 year',strtotime($prev_date_1)));           
 }
 else{
   $prev_date_1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
   $prev_end_dates =  date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_date_1)));
 }

 if($type == 'day'){
  $duration = ModuleByDateRange::calculate_days($start_date,$end_date);
  for($i=1;$i<=$duration;$i++){
    if($i==1){  
      $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
      $prev_start_date = date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_date_1))); 
    }else{
      $start_date = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
      $prev_start_date = date('Y-m-d',strtotime('+1 day',strtotime($prev_end_date))); 
    }

    $end_date = date('Y-m-d',strtotime('+0 day',strtotime($start_date)));    
    $prev_end_date = date('Y-m-d',strtotime('+0 day',strtotime($prev_start_date))); 

    $result[] = $this->goal_completion_chart($start_date,$end_date,$prev_start_date,$prev_end_date,$campaign_id,$type);
    if($default_duration == 1 || $default_duration == 3){
      $current[] = date('M d, Y',strtotime($start_date));
    }else{
      $current[] = date('M y',strtotime($start_date));
    }
    $current_dates[] = date('l, F d, Y',strtotime($start_date));
    $current_prev_dates[] = date('l, F d, Y',strtotime($prev_start_date));
  }
}

if($type == 'week'){
  $i =1;
  $csd = $prev_end_dates;
  $sd = $start_date;
  /*infinite for loop*/
  for( ; ;){
    $start = $sd;
    $previous_start = $csd;

    if($i == 1){
      if(date('D', strtotime($start)) == 'Sun'){
        $end_date = date('Y-m-d',strtotime('saturday next week',strtotime($start)));
      }else{  
        $end_date = date('Y-m-d',strtotime('saturday this week',strtotime($start)));
      }

      if(date('D', strtotime($previous_start)) == 'Sun'){
        $previous_end = date('Y-m-d',strtotime('saturday next week',strtotime($previous_start)));
      }else{  
        $previous_end = date('Y-m-d',strtotime('saturday this week',strtotime($previous_start)));
      }
    }else{
      $end_date = date('Y-m-d',strtotime('saturday next week',strtotime($start)));
      $previous_end = date('Y-m-d',strtotime('saturday next week',strtotime($previous_start)));
    }


    $sd = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
    $csd = date('Y-m-d',strtotime('+1 day',strtotime($previous_end)));


    if($previous_end > $start_date){
      $previousEnd = date('Y-m-d',strtotime('-1 day',strtotime($start_date))); 
    }else{
      $previousEnd = $previous_end;
    }

    /*to break the infinite loop at the last entry*/
    if($end_date  > date('Y-m-d')){
      $enddate = date('Y-m-d');
      $result[] = $this->goal_completion_chart($start,$enddate,$previous_start,$previousEnd,$campaign_id,$type);
      if($default_duration == 1 || $default_duration == 3){
        $current[] = date('M d, Y',strtotime($start));
      }else{
        $current[] = date('M y',strtotime($start));
      }
      $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($enddate));
      $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
      break; 
    }else{
      $result[] = $this->goal_completion_chart($start,$end_date,$previous_start,$previousEnd,$campaign_id,$type);
      if($default_duration == 1 || $default_duration == 3){
        $current[] = date('M d, Y',strtotime($start));
      }else{
        $current[] = date('M y',strtotime($start));
      }
      $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($end_date));
      $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
    }

    $i++;
  }
}

if($type == 'month'){
  $i = 1;
  $csd = $prev_end_dates;
  $sd = $start_date;
  for( ; ;){
    $start = $sd;
    $previous_start = $csd;

    $end_date = date('Y-m-d',strtotime(date("Y-m-t", strtotime($start))));
    $previous_end = date('Y-m-d',strtotime(date("Y-m-t", strtotime($previous_start))));


    $sd = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
    $csd = date('Y-m-d',strtotime('+1 day',strtotime($previous_end)));

    if($previous_end > $start_date){
      $previousEnd = date('Y-m-d',strtotime('-1 day',strtotime($start_date))); 
    }else{
      $previousEnd = $previous_end;
    }

    if($end_date  > date('Y-m-d')){
      $enddate = date('Y-m-d');

      $result[] = $this->goal_completion_chart($start,$enddate,$previous_start,$previousEnd,$campaign_id,$type);
      if($default_duration == 1 || $default_duration == 3){
        $current[] = date('M d, Y',strtotime($start));
      }else{
        $current[] = date('M y',strtotime($start));
      }
      $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($enddate));
      $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
      break;
    }else{
      $result[] = $this->goal_completion_chart($start,$end_date,$previous_start,$previousEnd,$campaign_id,$type);
      if($default_duration == 1 || $default_duration == 3){
        $current[] = date('M d, Y',strtotime($start));
      }else{
        $current[] = date('M y',strtotime($start));
      }
      $current_dates[] = date('M d, Y',strtotime($start)) .' - '.date('M d, Y',strtotime($end_date));
      $current_prev_dates[] = date('M d, Y',strtotime($previous_start)) .' - '.date('M d, Y',strtotime($previousEnd));
    }
    $i++;
  }

}  


$current_users = array_column($result,'current_users');
$current_organic = array_column($result,'current_organic');
$prev_users = array_column($result,'prev_users');
$prev_organic = array_column($result,'prev_organic');

$current_period  = date('M d, Y', strtotime($start_date_1)).' - '.date('M d, Y', strtotime(date('Y-m-d')));
$prev_period   = date('M d, Y', strtotime($prev_end_dates)).' - '.date('M d, Y', strtotime($prev_date_1));


$res['from_datelabel'] = $current;
$res['from_datelabels'] = $current_dates;
$res['prev_from_datelabels'] = $current_prev_dates;
$res['users'] = $current_users;
$res['previous_users'] = $prev_users;
$res['organic'] = $current_organic;
$res['previous_organic'] = $prev_organic;
$res['current_period'] = $current_period;
$res['previous_period'] = $prev_period;
$res['compare_status'] = $compare_status;
$res['status'] = 1;

}

return response()->json($res);
}

public function ajax_get_goal_completion_overview_viewkey(Request $request){

  $end_date = date('Y-m-d');
  $range = $request['value'];
  $campaign_id = $request['campaign_id'];
  $type = $request['type']?:'day';
  $module = 'organic_traffic';
  $compare_status = ($request->compare_value)?$request->compare_value:0;
  $state = ($request->has('key'))?'viewkey':'user';

  $sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaign_id,'organic_traffic');

  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
   $res['status'] = 0;
   $res['current_goal_completion'] = '??';
   $res['current_goal_value'] = '??';
   $res['current_goal_conversion'] = '??';
   $res['current_goal_abondon'] = '??';
   $res['current_goal_completion_organic'] = '??';
   $res['current_goal_value_organic'] = '??';
   $res['current_goal_conversion_organic'] = '??';
   $res['current_goal_abondon_organic'] = '??';

   $res['previous_goal_completion'] = '??';
   $res['previous_goal_value'] = '??';
   $res['previous_goal_conversion'] = '??';
   $res['previous_goal_abondon'] = '??';
   $res['previous_goal_completion_organic'] = '??';
   $res['previous_goal_value_organic'] = '??';
   $res['previous_goal_conversion_organic'] = '??';
   $res['previous_goal_abondon_organic'] = '??';

   $res['goal_completion_percentage'] = '??';
   $res['goal_value_percentage'] = '??';
   $res['goal_conversion_rate_percentage'] = '??';
   $res['goal_abondon_rate_percentage'] = '??';


   $res['goal_completion_percentage_organic'] = '??';
   $res['goal_value_percentage_organic'] = '??';
   $res['goal_conversion_rate_percentage_organic'] = '??';
   $res['goal_abondon_rate_percentage_organic'] = '??';
 } else {
  $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/statistics.json'; 
  $data = file_get_contents($url);

  $final = json_decode($data);


  if($range == 'month'){
    $start_date_1 = date('Y-m-d',strtotime('-1 month'));
    $default_duration =1;
  }elseif($range == 'three'){
    $start_date_1 = date('Y-m-d',strtotime('-3 month'));
    $default_duration = 3;
  }elseif($range == 'six'){
    $start_date_1 = date('Y-m-d',strtotime('-6 month'));
    $default_duration =6;
  }elseif($range == 'nine'){
    $start_date_1 = date('Y-m-d',strtotime('-9 month'));
    $default_duration =9;
  }elseif($range == 'year'){
    $start_date_1 = date('Y-m-d',strtotime('-1 year'));
    $default_duration =12;
  }elseif($range == 'twoyear'){
    $start_date_1 = date('Y-m-d',strtotime('-2 year'));
    $default_duration =24;
  }else{
    $start_date_1 = date('Y-m-d',strtotime('-3 month'));
    $default_duration =3;
  }

  $start_date = date('Y-m-d',strtotime('-'.$default_duration.' months'));
  $end_date = date('Y-m-d');

  if($range == 'year'){
   $prev_date1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
   $prev_end_date1 =  date('Y-m-d',strtotime(' -1 year',strtotime($prev_date1)));
 }
 elseif($range == 'twoyear'){
   $prev_date1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
   $prev_end_date1 =  date('Y-m-d',strtotime(' -2 year',strtotime($prev_date1)));           
 }
 else{
   $prev_date1 =  date('Y-m-d',strtotime('-1 day',strtotime($start_date_1)));
   $prev_end_date1 =  date('Y-m-d',strtotime('-'.$default_duration.' months',strtotime($prev_date1)));
 }

 $number_of_days = ModuleByDateRange::calculate_days($start_date,$end_date);

 $get_index = array_search($start_date,$final->dates);
 $get_index_today = array_search($end_date,$final->dates);

 $get_indexprev = array_search($prev_end_date1,$final->dates);
 $get_index_prev = array_search($prev_date1,$final->dates);


 $current_goal_completion = $current_goal_value = $current_goal_completion_organic = $current_goal_value_organic = $current_goal_conversion = $current_goal_abondon = $current_goal_conversion_organic = $current_goal_abondon_organic = array();  

 $previous_goal_completion = $previous_goal_value = $previous_goal_conversion = $previous_goal_abondon = $previous_goal_completion_organic = $previous_goal_value_organic = $previous_goal_conversion_organic = $previous_goal_abondon_organic = array();

 if($get_index == false && $get_index_today == false){
  $current_goal_completion[] = $current_goal_value[] = $current_goal_conversion[] = $current_goal_abondon[] = $current_goal_completion_organic[] = $current_goal_value_organic[] = $current_goal_conversion_organic[] = $current_goal_abondon_organic[] = 0;
}elseif($get_index && $get_index_today == false){
  $today = end($final->dates); 
  $get_index_today = array_search($today,$final->dates);
  for($i=$get_index;$i<=$get_index_today;$i++){
   $current_dates[] = $final->dates[$i];
   $current_goal_completion[] = $final->completion_all[$i];
   $current_goal_value[] = $final->value_all[$i];
   $current_goal_conversion[] = number_format($final->conversionRate_all[$i],2);
   $current_goal_abondon[] = number_format($final->abondonRate_all[$i],2);

   $current_goal_completion_organic[] = $final->completion_all_organic[$i];
   $current_goal_value_organic[] = $final->value_all_organic[$i];
   $current_goal_conversion_organic[] = number_format($final->conversionRate_all_organic[$i],2);
   $current_goal_abondon_organic[] = number_format($final->abondonRate_all_organic[$i],2);
 }
}else{
  for($i=$get_index;$i<=$get_index_today;$i++){
   $current_dates[] = $final->dates[$i];
   $current_goal_completion[] = $final->completion_all[$i];
   $current_goal_value[] = $final->value_all[$i];
   $current_goal_conversion[] = number_format($final->conversionRate_all[$i],2);
   $current_goal_abondon[] = number_format($final->abondonRate_all[$i],2);

   $current_goal_completion_organic[] = $final->completion_all_organic[$i];
   $current_goal_value_organic[] = $final->value_all_organic[$i];
   $current_goal_conversion_organic[] = number_format($final->conversionRate_all_organic[$i],2);
   $current_goal_abondon_organic[] = number_format($final->abondonRate_all_organic[$i],2);
 }
}

if($get_indexprev == false && $get_index_prev == false){
  $previous_goal_completion[] = $previous_goal_value[] = $previous_goal_conversion[] = $previous_goal_abondon[] = $previous_goal_completion_organic[] = $previous_goal_value_organic[] = $previous_goal_conversion_organic[] = $previous_goal_abondon_organic[] = 0;
}elseif($get_indexprev == false && $get_index_prev){
  $get_indexprev = array_search(end($final->dates),$final->dates);
  for($j=$get_indexprev;$j<=$get_index_prev;$j++){
   $previous_dates[] = $final->dates[$j];
   $previous_goal_completion[] = $final->completion_all[$j];
   $previous_goal_value[] = $final->value_all[$j];
   $previous_goal_conversion[] = number_format($final->conversionRate_all[$j],2);
   $previous_goal_abondon[] = number_format($final->abondonRate_all[$j],2);

   $previous_goal_completion_organic[] = $final->completion_all_organic[$j];
   $previous_goal_value_organic[] = $final->value_all_organic[$j];
   $previous_goal_conversion_organic[] = number_format($final->conversionRate_all_organic[$j],2);
   $previous_goal_abondon_organic[] = number_format($final->abondonRate_all_organic[$j],2);
 }
}else{
 for($j=$get_indexprev;$j<=$get_index_prev;$j++){
   $previous_dates[] = $final->dates[$j];
   $previous_goal_completion[] = $final->completion_all[$j];
   $previous_goal_value[] = $final->value_all[$j];
   $previous_goal_conversion[] = number_format($final->conversionRate_all[$j],2);
   $previous_goal_abondon[] = number_format($final->abondonRate_all[$j],2);

   $previous_goal_completion_organic[] = $final->completion_all_organic[$j];
   $previous_goal_value_organic[] = $final->value_all_organic[$j];
   $previous_goal_conversion_organic[] = number_format($final->conversionRate_all_organic[$j],2);
   $previous_goal_abondon_organic[] = number_format($final->abondonRate_all_organic[$j],2);
 }
}

$final_current_goal_completion = array_sum($current_goal_completion);

$final_previous_goal_completion = array_sum($previous_goal_completion);

$final_current_goal_value = array_sum($current_goal_value);
$final_previous_goal_value = array_sum($previous_goal_value);

$final_current_goal_completion_organic = array_sum($current_goal_completion_organic);
$final_previous_goal_completion_organic = array_sum($previous_goal_completion_organic);

$final_current_goal_value_organic = array_sum($current_goal_value_organic);
$final_previous_goal_value_organic = array_sum($previous_goal_value_organic);


$current_goal_conversion_rate = number_format((array_sum($current_goal_conversion)/$number_of_days),2);
$current_goal_abondon_rate = number_format((array_sum($current_goal_abondon)/$number_of_days),2);
$current_goal_conversion_organic_rate = number_format((array_sum($current_goal_conversion_organic)/$number_of_days),2);
$current_goal_abondon_organic_rate = number_format((array_sum($current_goal_abondon_organic)/$number_of_days),2);

$previous_goal_conversion_rate = number_format((array_sum($previous_goal_conversion)/$number_of_days),2);
$previous_goal_abondon_rate = number_format((array_sum($previous_goal_abondon)/$number_of_days),2);
$previous_goal_conversion_organic_rate = number_format((array_sum($previous_goal_conversion_organic)/$number_of_days),2);
$previous_goal_abondon_organic_rate = number_format((array_sum($previous_goal_abondon_organic)/$number_of_days),2);

    //percentage values


$goal_completion_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_goal_completion,$final_previous_goal_completion);
$goal_value_percentage = GoogleAnalyticsUsers::calculate_percentage($final_current_goal_value,$final_previous_goal_value);
$goal_conversion_rate_percentage = GoogleAnalyticsUsers::calculate_percentage($current_goal_conversion_rate,$previous_goal_conversion_rate);
$goal_abondon_rate_percentage = GoogleAnalyticsUsers::calculate_percentage($current_goal_abondon_rate,$previous_goal_abondon_rate);
      //organic
$goal_completion_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_goal_completion_organic,$final_previous_goal_completion_organic);
$goal_value_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($final_current_goal_value_organic,$final_previous_goal_value_organic);
$goal_conversion_rate_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($current_goal_conversion_organic_rate,$previous_goal_conversion_organic_rate);
$goal_abondon_rate_percentage_organic = GoogleAnalyticsUsers::calculate_percentage($current_goal_abondon_organic_rate,$previous_goal_abondon_organic_rate);


$res['current_goal_completion'] = $final_current_goal_completion;
$res['current_goal_value'] = $final_current_goal_value;
$res['current_goal_conversion'] = $current_goal_conversion_rate;
$res['current_goal_abondon'] = $current_goal_abondon_rate;
$res['current_goal_completion_organic'] = $final_current_goal_completion_organic;
$res['current_goal_value_organic'] = $final_current_goal_value_organic;
$res['current_goal_conversion_organic'] = $current_goal_conversion_organic_rate;
$res['current_goal_abondon_organic'] = $current_goal_abondon_organic_rate;

$res['previous_goal_completion'] = $final_previous_goal_completion;
$res['previous_goal_value'] = $final_previous_goal_value;
$res['previous_goal_conversion'] = $previous_goal_conversion_rate;
$res['previous_goal_abondon'] = $previous_goal_abondon_rate;
$res['previous_goal_completion_organic'] = $final_previous_goal_completion_organic;
$res['previous_goal_value_organic'] = $final_previous_goal_value_organic;
$res['previous_goal_conversion_organic'] = $previous_goal_conversion_organic_rate;
$res['previous_goal_abondon_organic'] = $previous_goal_abondon_organic_rate;

$res['goal_completion_percentage'] = $goal_completion_percentage;
$res['goal_value_percentage'] = $goal_value_percentage;
$res['goal_conversion_rate_percentage'] = $goal_conversion_rate_percentage;
$res['goal_abondon_rate_percentage'] = $goal_abondon_rate_percentage;


$res['goal_completion_percentage_organic'] = $goal_completion_percentage_organic;
$res['goal_value_percentage_organic'] = $goal_value_percentage_organic;
$res['goal_conversion_rate_percentage_organic'] = $goal_conversion_rate_percentage_organic;
$res['goal_abondon_rate_percentage_organic'] = $goal_abondon_rate_percentage_organic;


$res['compare_status'] = $compare_status;
$res['status'] = 1;

}
return response()->json($res);
}

public function ajax_goal_completion_location_vk(Request $request){
  $range = $request->value;
  
  $campaign_id = $request->campaign_id;
  $key = $request->key;
  $type = $request->type;

  if($request->has('compare_value')){
    $compare_status = ($request->compare_value)?:0;
  }else{
    $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
    if(!empty($getCompareChart)){
      $compare_status = $getCompareChart->compare_status;
    }else{
      $compare_status = 0;
    }
  }

  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
   $res['status'] = 0;
   return response()->json($res);
 } else {
  $end = date('M d, Y');

  $keysArr = $this->session_data_goal_location_vk($range,$campaign_id);

  $start_date = $keysArr['start_date'];
  $prev_day = $keysArr['prev_day'];
  $prev_date = $keysArr['prev_date'];
  $duration = $keysArr['duration'];
  $arr_name = $keysArr['keysArr']['arr_name'];
  $location_name = $keysArr['keysArr']['location'];
  $stats_data =  $this->get_completion_stats($campaign_id,$start_date,$end,$prev_day,$prev_date);

  $data = file_get_contents($keysArr['url']);
  $final = json_decode($data);

  $newCollection = collect($final->$arr_name->$location_name);



  $page = request()->has('page') ? request('page') : 1;

      // Set default per page
  $perPage = request()->has('per_page') ? request('per_page') : 4;

      // Offset required to take the results
  $offset = ($page * $perPage) - $perPage;

  $results =  new LengthAwarePaginator(
   $newCollection->slice($offset, $perPage),
   $newCollection->count(),
   $perPage,
   $page
 );
  return view('vendor.seo_sections.goal_completion.location_table', compact('final','end','start_date','prev_day','prev_date','duration','keysArr','compare_status','stats_data','results'))->render();
}
}

private function session_data_goal_location_vk($sessionHistoryRange,$campaign_id){
  if($sessionHistoryRange == 'month'){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/month_locations.json'; 
    $duration = 1;
    $start_date = date('M d, Y',strtotime('-1 month'));
    $start_date_new = date('Y-m-d',strtotime('-1 month'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-1 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_month_array',
      'location'=>'one_current_location',
      'goal_value'=>'one_current_goal',

      'prev_arr_name'=>'prev_month_array',
      'prev_location'=>'one_prev_location',
      'prev_goal_value'=>'one_prev_goal', 

      'arr_name_organic'=>'current_month_organic_array',
      'location_organic'=>'one_current_organic_location',
      'goal_value_organic'=>'one_current_organic_goal',

      'prev_arr_name_organic'=>'prev_month_organic_array',
      'prev_location_organic'=>'one_prev_organic_location',
      'prev_goal_value_organic'=>'one_prev_organic_goal'

    ];
  }elseif($sessionHistoryRange == 'three'){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/three_month_locations.json'; 
    $duration = 3;
    $start_date = date('M d, Y',strtotime('-3 month'));
    $start_date_new = date('Y-m-d',strtotime('-3 month'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-3 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_three_array',
      'location'=>'three_current_location',
      'goal_value'=>'three_current_goal',

      'prev_arr_name'=>'prev_three_array',
      'prev_location'=>'three_prev_location',
      'prev_goal_value'=>'three_prev_goal', 

      'arr_name_organic'=>'current_three_organic_array',
      'location_organic'=>'three_current_organic_location',
      'goal_value_organic'=>'three_current_organic_goal',

      'prev_arr_name_organic'=>'prev_three_organic_array',
      'prev_location_organic'=>'three_prev_organic_location',
      'prev_goal_value_organic'=>'three_prev_organic_goal'
    ];
  }elseif($sessionHistoryRange == 'six'){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/six_month_locations.json'; 
    $duration = 6;
    $start_date = date('M d, Y',strtotime('-6 month'));
    $start_date_new = date('Y-m-d',strtotime('-6 month'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-6 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_six_array',
      'location'=>'six_current_location',
      'goal_value'=>'six_current_goal',

      'prev_arr_name'=>'prev_six_array',
      'prev_location'=>'six_prev_location',
      'prev_goal_value'=>'six_prev_goal', 

      'arr_name_organic'=>'current_six_organic_array',
      'location_organic'=>'six_current_organic_location',
      'goal_value_organic'=>'six_current_organic_goal',

      'prev_arr_name_organic'=>'prev_six_organic_array',
      'prev_location_organic'=>'six_prev_organic_location',
      'prev_goal_value_organic'=>'six_prev_organic_goal'
    ];
  }elseif($sessionHistoryRange == 'nine'){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/nine_month_locations.json'; 
    $duration = 9;
    $start_date = date('M d, Y',strtotime('-9 month'));
    $start_date_new = date('Y-m-d',strtotime('-9 month'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-9 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_nine_array',
      'location'=>'nine_current_location',
      'goal_value'=>'nine_current_goal',

      'prev_arr_name'=>'prev_nine_array',
      'prev_location'=>'nine_prev_location',
      'prev_goal_value'=>'nine_prev_goal', 

      'arr_name_organic'=>'current_nine_organic_array',
      'location_organic'=>'nine_current_organic_location',
      'goal_value_organic'=>'nine_current_organic_goal',

      'prev_arr_name_organic'=>'prev_nine_organic_array',
      'prev_location_organic'=>'nine_prev_organic_location',
      'prev_goal_value_organic'=>'nine_prev_organic_goal'
    ];
  }elseif($sessionHistoryRange == 'year'){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/year_locations.json'; 
    $duration = 12;
    $start_date = date('M d, Y',strtotime('-1 year'));
    $start_date_new = date('Y-m-d',strtotime('-1 year'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-1 year',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_year_array',
      'location'=>'year_current_location',
      'goal_value'=>'year_current_goal',

      'prev_arr_name'=>'prev_year_array',
      'prev_location'=>'year_prev_location',
      'prev_goal_value'=>'year_prev_goal', 

      'arr_name_organic'=>'current_year_organic_array',
      'location_organic'=>'year_current_organic_location',
      'goal_value_organic'=>'year_current_organic_goal',

      'prev_arr_name_organic'=>'prev_year_organic_array',
      'prev_location_organic'=>'year_prev_organic_location',
      'prev_goal_value_organic'=>'year_prev_organic_goal'
    ];
  }elseif($sessionHistoryRange == 'twoyear'){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/twoyear_locations.json'; 
    $duration = 24;
    $start_date = date('M d, Y',strtotime('-2 year'));
    $start_date_new = date('Y-m-d',strtotime('-2 year'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-2 year',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_twoyear_array',
      'location'=>'twoyear_current_location',
      'goal_value'=>'twoyear_current_goal',

      'prev_arr_name'=>'prev_twoyear_array',
      'prev_location'=>'twoyear_prev_location',
      'prev_goal_value'=>'twoyear_prev_goal', 

      'arr_name_organic'=>'current_twoyear_organic_array',
      'location_organic'=>'twoyear_current_organic_location',
      'goal_value_organic'=>'twoyear_current_organic_goal',

      'prev_arr_name_organic'=>'prev_twoyear_organic_array',
      'prev_location_organic'=>'twoyear_prev_organic_location',
      'prev_goal_value_organic'=>'twoyear_prev_organic_goal'
    ];
  }else{
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/three_month_locations.json'; 
    $duration = 3;
    $start_date = date('M d, Y',strtotime('-3 month'));
    $start_date_new = date('Y-m-d',strtotime('-3 month'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-3 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_three_array',
      'location'=>'three_current_location',
      'goal_value'=>'three_current_goal',

      'prev_arr_name'=>'prev_three_array',
      'prev_location'=>'three_prev_location',
      'prev_goal_value'=>'three_prev_goal', 

      'arr_name_organic'=>'current_three_organic_array',
      'location_organic'=>'three_current_organic_location',
      'goal_value_organic'=>'three_current_organic_goal',

      'prev_arr_name_organic'=>'prev_three_organic_array',
      'prev_location_organic'=>'three_prev_organic_location',
      'prev_goal_value_organic'=>'three_prev_organic_goal'
    ];
  }

  return compact('keysArr','start_date','prev_day','prev_date','url','duration');
}


public function ajax_goal_completion_location_pagination_vk(Request $request){
 $range = $request->value;
 
 $campaign_id = $request->campaign_id;
 $key = $request->key;
 $type = $request->type;
 $page = $request->page;

 if($request->has('compare_value')){
  $compare_status = ($request->compare_value)?:0;
}else{
 $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
 if(!empty($getCompareChart)){
  $compare_status = $getCompareChart->compare_status;
}else{
  $compare_status = 0;
}
}

if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
 $res['status'] = 0;
 return response()->json($res);
} else {
  $end = date('M d, Y');
  $keysArr = $this->session_data_goal_location_vk($range,$campaign_id);
  $start_date = $keysArr['start_date'];
  $prev_day = $keysArr['prev_day'];
  $prev_date = $keysArr['prev_date'];
  $arr_name = $keysArr['keysArr']['arr_name'];
  $location_name = $keysArr['keysArr']['location'];

  $stats_data =  $this->get_completion_stats($campaign_id,$start_date,$end,$prev_day,$prev_date);

  $data = file_get_contents($keysArr['url']);
  $final = json_decode($data);


  $newCollection = collect($final->$arr_name->$location_name);


  $page = request()->has('page') ? request('page') : 1;

      // Set default per page
  $perPage = request()->has('per_page') ? request('per_page') : 4;

      // Offset required to take the results
  $offset = ($page * $perPage) - $perPage;

  $results =  new LengthAwarePaginator(
   $newCollection->slice($offset, $perPage),
   $newCollection->count(),
   $perPage,
   $page
 );


  return view('vendor.seo_sections.goal_completion.pagination', compact('results'))->render();
}
}


public function ajax_goal_completion_sourcemedium_vk(Request $request){
  $range = $request->value;
  $campaign_id = $request->campaign_id;
  $key = $request->key;
  $type = $request->type;
  $page = $request->page;
  if($request->has('compare_value')){
    $compare_status = ($request->compare_value)?:0;
  }else{
    $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
    if(!empty($getCompareChart)){
      $compare_status = $getCompareChart->compare_status;
    }else{
      $compare_status = 0;
    }
  }

  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
   $res['status'] = 0;
   return response()->json($res);
 } else {
  $end = date('M d, Y');

  $keysArr = $this->session_data_goal_sourcemedium_vk($range,$campaign_id);

  $start_date = $keysArr['start_date'];
  $prev_day = $keysArr['prev_day'];
  $prev_date = $keysArr['prev_date'];
  $duration = $keysArr['duration'];
  $arr_name = $keysArr['keysArr']['arr_name'];
  $location_name = $keysArr['keysArr']['location'];

  $data = file_get_contents($keysArr['url']);
  $final = json_decode($data);

  $stats_data =  $this->get_completion_stats($campaign_id,$start_date,$end,$prev_day,$prev_date);

  $newCollection = collect($final->$arr_name->$location_name);

  $page = request()->has('page') ? request('page') : 1;
      // Set default per page
  $perPage = request()->has('per_page') ? request('per_page') : 4;
      // Offset required to take the results
  $offset = ($page * $perPage) - $perPage;

  $results =  new LengthAwarePaginator(
   $newCollection->slice($offset, $perPage),
   $newCollection->count(),
   $perPage,
   $page
 );


  return view('vendor.seo_sections.goal_completion.sourcemedium_table', compact('final','end','start_date','prev_day','prev_date','duration','keysArr','compare_status','stats_data','results'))->render();
}
}

public function ajax_goal_completion_sourcemedium_pagination_vk(Request $request){
  $range = $request->value;
  $campaign_id = $request->campaign_id;
  $key = $request->key;
  $type = $request->type;
  $page = $request->page;
  if($request->has('compare_value')){
    $compare_status = ($request->compare_value)?:0;
  }else{
    $getCompareChart = ProjectCompareGraph::getCompareChart($campaign_id);
    if(!empty($getCompareChart)){
      $compare_status = $getCompareChart->compare_status;
    }else{
      $compare_status = 0;
    }
  }



  if (!file_exists(env('FILE_PATH')."public/goalcompletion/".$campaign_id)) {
   $res['status'] = 0;
   return response()->json($res);
 } else {
  $end = date('M d, Y');
  $keysArr = $this->session_data_goal_sourcemedium_vk($range,$campaign_id);
  $start_date = $keysArr['start_date'];
  $prev_day = $keysArr['prev_day'];
  $prev_date = $keysArr['prev_date'];
  $arr_name = $keysArr['keysArr']['arr_name'];
  $location_name = $keysArr['keysArr']['location'];

  $stats_data =  $this->get_completion_stats($campaign_id,$start_date,$end,$prev_day,$prev_date);

  $data = file_get_contents($keysArr['url']);
  $final = json_decode($data);


  $newCollection = collect($final->$arr_name->$location_name);


  $page = request()->has('page') ? request('page') : 1;

      // Set default per page
  $perPage = request()->has('per_page') ? request('per_page') : 4;

      // Offset required to take the results
  $offset = ($page * $perPage) - $perPage;

  $results =  new LengthAwarePaginator(
   $newCollection->slice($offset, $perPage),
   $newCollection->count(),
   $perPage,
   $page
 );


  return view('vendor.seo_sections.goal_completion.source_medium_pagination', compact('results'))->render();
}
}

private function session_data_goal_sourcemedium_vk($sessionHistoryRange,$campaign_id){
  if($sessionHistoryRange == 'month'){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/month_sourcemedium.json'; 
    $duration = 1;
    $start_date = date('M d, Y',strtotime('-1 month'));
    $start_date_new = date('Y-m-d',strtotime('-1 month'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-1 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_month_sm_array',
      'location'=>'one_current_sm_location',
      'goal_value'=>'one_current_sm_goal',

      'prev_arr_name'=>'prev_month_sm_array',
      'prev_location'=>'one_prev_sm_location',
      'prev_goal_value'=>'one_prev_sm_goal', 

      'arr_name_organic'=>'current_month_sm_organic_array',
      'location_organic'=>'one_current_organic_sm_location',
      'goal_value_organic'=>'one_current_organic_sm_goal',

      'prev_arr_name_organic'=>'prev_month_sm_organic_array',
      'prev_location_organic'=>'one_prev_organic_sm_location',
      'prev_goal_value_organic'=>'one_prev_organic_sm_goal'

    ];
  }elseif($sessionHistoryRange == 'three'){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/three_sourcemedium.json'; 
    $duration = 3;
    $start_date = date('M d, Y',strtotime('-3 month'));
    $start_date_new = date('Y-m-d',strtotime('-3 month'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-3 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_three_sm_array',
      'location'=>'three_current_sm_location',
      'goal_value'=>'three_current_sm_goal',

      'prev_arr_name'=>'prev_three_sm_array',
      'prev_location'=>'three_prev_sm_location',
      'prev_goal_value'=>'three_prev_sm_goal', 

      'arr_name_organic'=>'current_three_sm_organic_array',
      'location_organic'=>'three_current_organic_sm_location',
      'goal_value_organic'=>'three_current_organic_sm_goal',

      'prev_arr_name_organic'=>'prev_three_sm_organic_array',
      'prev_location_organic'=>'three_prev_organic_sm_location',
      'prev_goal_value_organic'=>'three_prev_organic_sm_goal'
    ];
  }elseif($sessionHistoryRange == 'six'){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/six_sourcemedium.json'; 
    $duration = 6;
    $start_date = date('M d, Y',strtotime('-6 month'));
    $start_date_new = date('Y-m-d',strtotime('-6 month'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-6 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_six_sm_array',
      'location'=>'six_current_sm_location',
      'goal_value'=>'six_current_sm_goal',

      'prev_arr_name'=>'prev_six_sm_array',
      'prev_location'=>'six_prev_sm_location',
      'prev_goal_value'=>'six_prev_sm_goal', 

      'arr_name_organic'=>'current_six_sm_organic_array',
      'location_organic'=>'six_current_organic_sm_location',
      'goal_value_organic'=>'six_current_organic_sm_goal',

      'prev_arr_name_organic'=>'prev_six_sm_organic_array',
      'prev_location_organic'=>'six_prev_organic_sm_location',
      'prev_goal_value_organic'=>'six_prev_organic_sm_goal'
    ];
  }elseif($sessionHistoryRange == 'nine'){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/nine_sourcemedium.json'; 
    $duration = 9;
    $start_date = date('M d, Y',strtotime('-9 month'));
    $start_date_new = date('Y-m-d',strtotime('-9 month'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-9 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_nine_sm_array',
      'location'=>'nine_current_sm_location',
      'goal_value'=>'nine_current_sm_goal',

      'prev_arr_name'=>'prev_nine_sm_array',
      'prev_location'=>'nine_prev_sm_location',
      'prev_goal_value'=>'nine_prev_sm_goal', 

      'arr_name_organic'=>'current_nine_sm_organic_array',
      'location_organic'=>'nine_current_organic_sm_location',
      'goal_value_organic'=>'nine_current_organic_sm_goal',

      'prev_arr_name_organic'=>'prev_nine_sm_organic_array',
      'prev_location_organic'=>'nine_prev_organic_sm_location',
      'prev_goal_value_organic'=>'nine_prev_organic_sm_goal'
    ];
  }elseif($sessionHistoryRange == 'year'){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/year_sourcemedium.json'; 
    $duration = 12;
    $start_date = date('M d, Y',strtotime('-1 year'));
    $start_date_new = date('Y-m-d',strtotime('-1 year'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-1 year',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_year_sm_array',
      'location'=>'year_current_sm_location',
      'goal_value'=>'year_current_sm_goal',

      'prev_arr_name'=>'prev_year_sm_array',
      'prev_location'=>'year_prev_sm_location',
      'prev_goal_value'=>'year_prev_sm_goal', 

      'arr_name_organic'=>'current_year_sm_organic_array',
      'location_organic'=>'year_current_organic_sm_location',
      'goal_value_organic'=>'year_current_organic_sm_goal',

      'prev_arr_name_organic'=>'prev_year_sm_organic_array',
      'prev_location_organic'=>'year_prev_organic_sm_location',
      'prev_goal_value_organic'=>'year_prev_organic_sm_goal'
    ];
  }elseif($sessionHistoryRange == 'twoyear'){
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/twoyear_sourcemedium.json'; 
    $duration = 24;
    $start_date = date('M d, Y',strtotime('-2 year'));
    $start_date_new = date('Y-m-d',strtotime('-2 year'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-2 year',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_twoyear_sm_array',
      'location'=>'twoyear_current_sm_location',
      'goal_value'=>'twoyear_current_sm_goal',

      'prev_arr_name'=>'prev_twoyear_sm_array',
      'prev_location'=>'twoyear_prev_sm_location',
      'prev_goal_value'=>'twoyear_prev_sm_goal', 

      'arr_name_organic'=>'current_twoyear_sm_organic_array',
      'location_organic'=>'twoyear_current_organic_sm_location',
      'goal_value_organic'=>'twoyear_current_organic_sm_goal',

      'prev_arr_name_organic'=>'prev_twoyear_sm_organic_array',
      'prev_location_organic'=>'twoyear_prev_organic_sm_location',
      'prev_goal_value_organic'=>'twoyear_prev_organic_sm_goal'
    ];
  }else{
    $url = env('FILE_PATH')."public/goalcompletion/".$campaign_id.'/three_sourcemedium.json'; 
    $duration = 3;
    $start_date = date('M d, Y',strtotime('-3 month'));
    $start_date_new = date('Y-m-d',strtotime('-3 month'));
    $prev_day =  date('M d, Y',strtotime('-1 day',strtotime($start_date_new)));
    $prev_date =  date('M d, Y',strtotime('-3 month',strtotime(date('Y-m-d',strtotime('-1 day',strtotime($start_date_new))))));

    $keysArr = [
      'arr_name'=>'current_three_sm_array',
      'location'=>'three_current_sm_location',
      'goal_value'=>'three_current_sm_goal',

      'prev_arr_name'=>'prev_three_sm_array',
      'prev_location'=>'three_prev_sm_location',
      'prev_goal_value'=>'three_prev_sm_goal', 

      'arr_name_organic'=>'current_three_sm_organic_array',
      'location_organic'=>'three_current_organic_sm_location',
      'goal_value_organic'=>'three_current_organic_sm_goal',

      'prev_arr_name_organic'=>'prev_three_sm_organic_array',
      'prev_location_organic'=>'three_prev_organic_sm_location',
      'prev_goal_value_organic'=>'three_prev_organic_sm_goal'
    ];
  }

  return compact('keysArr','start_date','prev_day','prev_date','url','duration');
}

}
