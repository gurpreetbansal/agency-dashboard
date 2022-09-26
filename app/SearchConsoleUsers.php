<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Auth;
use Session;
use App\SearchConsoleUrl;
use App\SemrushUserAccount;
use App\User;
use App\GoogleAnalyticsUsers;
use DateTime;
use App\Country;


class SearchConsoleUsers extends Model {

	protected $table = 'search_console_users';

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
    protected $fillable = ['user_id', 'google_access_token', 'google_refresh_token', 'oauth_uid', 'first_name', 'last_name', 'email', 'gender', 'locale', 'picture', 'link', 'token_type','expires_in','id_token','service_created','created_at', 'updated_at'];



    public static function ConsoleClientAuth($getAnalytics){

        $refresh_token  = $getAnalytics->google_refresh_token;
        $service_token['access_token']   = $getAnalytics->google_access_token;
        $service_token['token_type']   = $getAnalytics->token_type;
        $service_token['expires_in']  = $getAnalytics->expires_in;
        $service_token['id_token']  = $getAnalytics->id_token;
        $service_token['created']  = $getAnalytics->service_created;
        $service_token['refresh_token']  = $getAnalytics->google_refresh_token;

        $client = new \Google_Client(); 
        $client->setApplicationName("AgencyDashboard");
        $client->setAuthConfig(\config('app.FILE_PATH').\config('app.ANALYTICS_CONFIG'));
        $client->setAccessType('offline');
        $client->addScope(['https://www.googleapis.com/auth/webmasters','https://www.googleapis.com/auth/webmasters.readonly','email','profile']);
        $client->setAccessToken($service_token);
        $client->setApprovalPrompt('force');
        $client->setIncludeGrantedScopes(true); 
        return $client;
    }


    public static function google_refresh_token($client,$refresh_token,$getAnalytics_id){
        $client->refreshToken($refresh_token);
        $newtoken = $client->getAccessToken();

        SearchConsoleUsers::where('id',$getAnalytics_id)->update([
            'google_access_token'=> $newtoken['access_token'],
            'token_type'=> $newtoken['token_type'],
            'expires_in'=> $newtoken['expires_in'],
            'google_refresh_token'=> $newtoken['refresh_token'],
            'service_created'=> $newtoken['created'],
            'id_token'=> $newtoken['id_token'],
        ]);
        Session::put('token', $client->getAccessToken());
    }

//     public static function log_console_data_bkp($campaignId){

//         try{
//             $data = SemrushUserAccount::where('console_account_id','!=',NULL)->where('id',$campaignId)->first();
//             if(!empty($data)){
//                 $dates  = $converted_dates = $clicks = $impressions = array();
//                 $page_dates = $page_converted_dates = $page_key = $page_clicks = $page_impressions = array();
//                 $device_dates = $device_converted_dates = $device_key = $device_clicks = $device_impressions = $device_ctr = $device_positions = array();
//                 $country_dates = $country_converted_dates = $country_query = $country_clicks = $country_impressions = $country_ctr = $country_position = array();

//                 /*query variables*/
//                 $final_query = array();
//                 $month_query_keys = $month_query_clicks = $month_query_impressions = '';
//                 $three_query_keys = $three_query_clicks = $three_query_impressions = '';
//                 $six_query_keys = $six_query_clicks = $six_query_impressions = '';
//                 $nine_query_keys = $nine_query_clicks = $nine_query_impressions = '';
//                 $one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = '';
//                 $query_dates=$query_converted_dates=    $query_keys = $query_clicks = $query_impressions  = '';

//                 $nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = array();

//                 /*device variables*/
//                 $month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

//                 $three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
//                 $six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
//                 $nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
//                 $year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
//                 $two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
//                 $month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();


//                 $getAnalytics  = SearchConsoleUsers::where('id',$data->google_console_id)->first();

//                 $user_id = $data->user_id;
//                 $campaignId = $data->id;

//                 $role_id =User::get_user_role($user_id);



//                 if(!empty($getAnalytics)){

//                     $client = SearchConsoleUsers::ConsoleClientAuth($getAnalytics);

//                     $refresh_token  = $getAnalytics->google_refresh_token;

//                     if ($client->isAccessTokenExpired()) {
//                         SearchConsoleUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
//                     }


//                     $getAnalyticsId = SearchConsoleUrl::where('id',$data->console_account_id)->first();


//                     if(isset($getAnalyticsId)){
//                         $profileUrl = $getAnalyticsId->siteUrl;



//                         $end_date = date('Y-m-d');
//                         $start_date = date('Y-m-d', strtotime("-2 years", strtotime(date('Y-m-d'))));


//                         $one_month = date('Y-m-d',strtotime('-1 month'));
//                         $three_month = date('Y-m-d',strtotime('-3 month'));
//                         $six_month = date('Y-m-d',strtotime('-6 month'));
//                         $nine_month = date('Y-m-d',strtotime('-9 month'));
//                         $one_year = date('Y-m-d',strtotime('-1 year'));


//                         /*graph data*/
//                         if (file_exists(\config('app.FILE_PATH').'public/search_console/'.$campaignId)) {
//                             $graphfilename = \config('app.FILE_PATH').'public/search_console/'.$campaignId.'/graph.json';
//                             if(file_exists($graphfilename)){

//                                 if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){
//                                    SearchConsoleUrl::search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId);
//                                }else{
//                                 SearchConsoleUrl::search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId);
//                             }
//                         }else{

//                           SearchConsoleUrl::search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId);
//                       }

//                   }
//                   else {
//                     mkdir(\config('app.FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
//                     SearchConsoleUrl::search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId);
//                 }
                
                
//                 /*graph data*/

//                 /*query data*/
//                 if (file_exists(\config('app.FILE_PATH').'public/search_console/'.$campaignId)) {
//                     $queryfilename = \config('app.FILE_PATH').'public/search_console/'.$campaignId.'/query.json';
//                     if(file_exists($queryfilename)){

//                         if(date("Y-m-d", filemtime($queryfilename)) != date('Y-m-d')){
//                             SearchConsoleUrl::search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//                         }else{
//                             SearchConsoleUrl::search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//                         }
//                     }else{

//                         SearchConsoleUrl::search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//                     }

//                 }
//                 else {
//                     mkdir(\config('app.FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
//                     SearchConsoleUrl::search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//                 }


//                 /*query data*/


//                 /*device data*/


//                 if (file_exists(\config('app.FILE_PATH').'public/search_console/'.$campaignId)) {
//                     $devicefilename = \config('app.FILE_PATH').'public/search_console/'.$campaignId.'/device.json';
//                     if(file_exists($devicefilename)){

//                         if(date("Y-m-d", filemtime($devicefilename)) != date('Y-m-d')){
//                          SearchConsoleUrl::search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//                      }else{
//                          SearchConsoleUrl::search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//                      }
//                  }else{

//                      SearchConsoleUrl::search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//                  }

//              }
//              else {
//                 mkdir(\config('app.FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
//                 SearchConsoleUrl::search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//             }
            
//             /*device data*/

//             /*pages data*/

//             if (file_exists(\config('app.FILE_PATH').'public/search_console/'.$campaignId)) {
//                 $pagefilename = \config('app.FILE_PATH').'public/search_console/'.$campaignId.'/page.json';
//                 if(file_exists($pagefilename)){

//                     if(date("Y-m-d", filemtime($pagefilename)) != date('Y-m-d')){
//                         SearchConsoleUrl::search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//                     }else{
//                      SearchConsoleUrl::search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//                  }
//              }else{

//                 SearchConsoleUrl::search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//             }

//         }
//         else {
//             mkdir(\config('app.FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
//             SearchConsoleUrl::search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//         }
        
        
        

//         /*pages data*/

//         /*country data*/
//         if (file_exists(\config('app.FILE_PATH').'public/search_console/'.$campaignId)) {
//             $countryfilename = \config('app.FILE_PATH').'public/search_console/'.$campaignId.'/country.json';
//             if(file_exists($countryfilename)){

//                 if(date("Y-m-d", filemtime($countryfilename)) != date('Y-m-d')){
//                     SearchConsoleUrl::search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//                 }else{
//                  SearchConsoleUrl::search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//              }
//          }else{

//            SearchConsoleUrl::search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
//        }

//    }
//    else {
//     mkdir(\config('app.FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
//     SearchConsoleUrl::search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year);
// }



// /*country data*/


// }                   
// }

// }
// }catch(\Exception $e){
//     $error = json_decode($e->getMessage(),true);
//     $result['status'] = 0;
//     $result['message'] = $error['error'];
//     return $result;
// }
// }

public static function updateRefreshNAccessTokenPPC($email,$user_id,$tokenArray){

    GoogleAnalyticsUsers::where('email', $email)->where('oauth_provider','=', 'google_ads')->update([
        'google_access_token' => $tokenArray['access_token'],
        'token_type' => $tokenArray['token_type'],
        'expires_in' => $tokenArray['expires_in'],
        'google_refresh_token' => $tokenArray['refresh_token'],
        'service_created' => $tokenArray['created'],
        'id_token' => $tokenArray['id_token']
    ]);


    
}

public static function updateRefreshNAccessToken($email,$user_id,$tokenArray){

    GoogleAnalyticsUsers::where('email', $email)->where('oauth_provider','!=', 'google_ads')->update([
        'google_access_token' => $tokenArray['access_token'],
        'token_type' => $tokenArray['token_type'],
        'expires_in' => $tokenArray['expires_in'],
        'google_refresh_token' => $tokenArray['refresh_token'],
        'service_created' => $tokenArray['created'],
        'id_token' => $tokenArray['id_token']
    ]);

    /*GoogleAnalyticsUsers::where('user_id',$user_id)->where('email', $email)->update([
        'google_access_token' => $tokenArray['access_token'],
        'token_type' => $tokenArray['token_type'],
        'expires_in' => $tokenArray['expires_in'],
        'google_refresh_token' => $tokenArray['refresh_token'],
        'service_created' => $tokenArray['created'],
        'id_token' => $tokenArray['id_token']
    ]);*/

    SearchConsoleUsers::where('user_id',$user_id)->where('email', $email)->update([
        'google_access_token' => $tokenArray['access_token'],
        'token_type' => $tokenArray['token_type'],
        'expires_in' => $tokenArray['expires_in'],
        'google_refresh_token' => $tokenArray['refresh_token'],
        'service_created' => $tokenArray['created'],
        'id_token' => $tokenArray['id_token']
    ]);
}


/*new design changes */

public static function ClientAuth($getAnalytics){
    $refresh_token  = $getAnalytics->google_refresh_token;
    $service_token['access_token']   = $getAnalytics->google_access_token;
    $service_token['token_type']   = $getAnalytics->token_type;
    $service_token['expires_in']  = $getAnalytics->expires_in;
    $service_token['id_token']  = $getAnalytics->id_token;
    $service_token['created']  = $getAnalytics->service_created;
    $service_token['refresh_token']  = $getAnalytics->google_refresh_token;

    $client = new \Google_Client(); 
    $client->setApplicationName("AgencyDashboard");
    $client->setAuthConfig(\config('app.FILE_PATH').\config('app.ANALYTICS_CONFIG'));
    $client->setAccessType('offline');
    $client->addScope(['https://www.googleapis.com/auth/webmasters','https://www.googleapis.com/auth/webmasters.readonly','email','profile']);
    $client->setAccessToken($service_token);
    $client->setApprovalPrompt('force');
    $client->setIncludeGrantedScopes(true); 
    return $client;
}

public static function log_search_console_data($client,$profileUrl,$campaignId){
 try{
    $end_date = date('Y-m-d',strtotime('-1 day'));
    $start_date = date('Y-m-d', strtotime("-4 year", strtotime($end_date)));
    $sessionHistoryRange = ModuleByDateRange::getModuleDateRange($campaignId,'search_console');
    if(isset($sessionHistoryRange) && $sessionHistoryRange->duration == 1){
        $three_start_date = date('Y-m-d', strtotime("-1 month", strtotime($end_date)));
    }elseif(isset($sessionHistoryRange) && $sessionHistoryRange->duration == 3){
        $three_start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
    }elseif(isset($sessionHistoryRange) && $sessionHistoryRange->duration == 6){
        $three_start_date = date('Y-m-d', strtotime("-6 month", strtotime($end_date)));
    }elseif(isset($sessionHistoryRange) && $sessionHistoryRange->duration == 9){
        $three_start_date = date('Y-m-d', strtotime("-9 month", strtotime($end_date)));
    }elseif(isset($sessionHistoryRange) && $sessionHistoryRange->duration == 12){
        $three_start_date = date('Y-m-d', strtotime("-1 year", strtotime($end_date)));
    }elseif(isset($sessionHistoryRange) && $sessionHistoryRange->duration == 24){
        $three_start_date = date('Y-m-d', strtotime("-2 year", strtotime($end_date)));
    }else{
        $three_start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
    }

    SearchConsoleUrl::search_console_data($client,$profileUrl,$start_date,$end_date,$three_start_date,$campaignId);
    $result['status'] = 1;
}catch(\Exception $e){
    $error = json_decode($e->getMessage(),true);
    $result['status'] = 0;
    $result['message'] = $error['error'];
}
return $result;
}

public static function calculate_previous_period($start_date,$days){
    $final_day = $days+1;
    $previous_start_date = date('Y-m-d',strtotime('-'.$final_day.'day',strtotime($start_date)));
    $previous_end_date = date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
    return array('previous_start_date' =>$previous_start_date,'previous_end_date' =>$previous_end_date);
}


public static function calculate_previous_year($start_date,$end_date){
    $s_date = new DateTime($start_date);
    $previous_start_date = $s_date->modify("-1 year")->format('Y-m-d');
    $e_date = new DateTime($end_date);
    $previous_end_date = $e_date->modify("-1 year")->format('Y-m-d');
    return array('previous_start_date' =>$previous_start_date,'previous_end_date' =>$previous_end_date);
}


public static function country_detail($iso){
    $data = Country::where('iso_code',$iso)->first();
    if($data <> null){
        $full_name = $data->countries_name;
        $flag = strtolower($data->short_code).'.png';
    }else{
        $full_name = 'Unknown Region';
        $flag = 'unknown.png';
    }
    return array('flag'=>$flag,'full_name'=>$full_name);
}

public static function log_latest_search_console_data($client,$profileUrl,$campaignId,$list_start_date){
 try{
    $end_date = date('Y-m-d',strtotime('-1 day'));
    $start_date = date('Y-m-d', strtotime("-4 year", strtotime($end_date)));
    SearchConsoleUrl::search_console_data($client,$profileUrl,$start_date,$end_date,$list_start_date,$campaignId);
    $result['status'] = 1;
}catch(\Exception $e){
    $error = json_decode($e->getMessage(),true);
    $result['status'] = 0;
    $result['message'] = $error['error'];
}
return $result;
}



public static function log_console_data($campaignId){
    try{
        $user_id = User::get_parent_user_id(Auth::user()->id); /*get user id from child*/
        $end_date = date('Y-m-d',strtotime('-1 day'));
        $three_start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));

        $get_console_details = SemrushUserAccount::select('google_console_id','console_account_id')->where('id',$campaignId)->first();
        $getAnalytics = SearchConsoleUsers::where('user_id',$user_id)->where('id',$get_console_details->google_console_id)->first();
        $client = SearchConsoleUsers::ClientAuth($getAnalytics);
        $refresh_token  = $getAnalytics->google_refresh_token;
        if ($client->isAccessTokenExpired()) {
            GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
        }

        $get_profile_data = SearchConsoleUrl::where('id',$get_console_details->console_account_id)->first();
        if(!empty($get_profile_data)){
            $profile_url = $get_profile_data->siteUrl;
            $log_data = SearchConsoleUsers::log_latest_search_console_data($client,$profile_url,$campaignId,$three_start_date);        
            if(isset($log_data['status']) && $log_data['status'] == 1){
                $response['status'] = 'success';
                $ifErrorExists = Error::removeExisitingError(2,$campaignId);
                if(!empty($ifErrorExists)){
                    Error::where('id',$ifErrorExists->id)->delete();
                }
                GoogleUpdate::updateTiming($campaignId,'search_console','sc_type','2');
            } else{
                $response['status'] = 'error';
                $response['message'] = $log_data['message'];
            }
        }
    }catch(\Exception $e){
        $error = json_decode($e->getMessage(),true);
        $result['status'] = 0;
        $result['message'] = $error['error'];
        return $result;
    }
}

public static function create_region_dateformat($language,$region,$date){
    $d = new DateTime();
    $d->setTimestamp(strtotime($date));
    $formatter = new \IntlDateFormatter(strtolower($language), \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
    return $formatter->format($d);
}

}