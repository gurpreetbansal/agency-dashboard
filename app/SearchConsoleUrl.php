<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SearchConsoleUsers;
use App\GoogleAnalyticsUsers;
use Exception;


class SearchConsoleUrl extends Model {

	protected $table = 'search_console_urls';

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
    protected $fillable = ['user_id', 'request_id','google_account_id','permission_level','siteUrl','created_at', 'updated_at'];

    public static function get_console_urls_bkp($service,$campaignId, $console_id,$user_id){
        try{
            $site_data = $service->sites->listSites();
            if(isset($site_data) && !empty($site_data)){

                SearchConsoleUrl::where('user_id',$user_id)->where('google_account_id',$console_id)->where('request_id',$campaignId)->delete();
                
                foreach($site_data as $site){
                    SearchConsoleUrl::create([
                        'user_id'=>$user_id,
                        'request_id'=>$campaignId,
                        'google_account_id'=>$console_id,
                        'permission_level'=>$site->permissionLevel,
                        'siteUrl' => $site->siteUrl                 
                    ]);
                }
            }

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public static function get_console_urls($service,$campaignId, $console_id,$user_id){
        try{
            $site_data = $service->sites->listSites();
            if(isset($site_data) && !empty($site_data)){
                foreach($site_data as $site){
                    $siteUrl = $site->siteUrl;
                    $if_exists =  SearchConsoleUrl::where('siteUrl',$siteUrl)->where('google_account_id',$console_id)->where('user_id',$user_id)->first();   
                    if($if_exists){
                        SearchConsoleUrl::where('id',$if_exists->id)->update([
                            'user_id'=>$user_id,
                            'request_id'=>$campaignId,
                            'google_account_id'=>$console_id,
                            'permission_level'=>$site->permissionLevel,
                            'siteUrl' => $site->siteUrl                 
                        ]);
                    }else{
                      SearchConsoleUrl::create([
                        'user_id'=>$user_id,
                        'request_id'=>$campaignId,
                        'google_account_id'=>$console_id,
                        'permission_level'=>$site->permissionLevel,
                        'siteUrl' => $site->siteUrl                 
                    ]);
                  }
              }
          }

      }catch(Exception $e){
        return $e->getMessage();
    }
}


public static function get_console_urls_update($service,$campaignId, $console_id,$user_id){
    try{

        $site_data = $service->sites->listSites();

        if(isset($site_data) && !empty($site_data)){
            foreach($site_data as $site){
                $siteUrl = $site->siteUrl;
                $if_exists =  SearchConsoleUrl::where('siteUrl',$siteUrl)->where('google_account_id',$console_id)->where('user_id',$user_id)->first();   
                if($if_exists){
                    SearchConsoleUrl::where('id',$if_exists->id)->update([
                        'user_id'=>$user_id,
                        'request_id'=>$campaignId,
                        'google_account_id'=>$console_id,
                        'permission_level'=>$site->permissionLevel,
                        'siteUrl' => $site->siteUrl                 
                    ]);
                }else{
                  SearchConsoleUrl::create([
                    'user_id'=>$user_id,
                    'request_id'=>$campaignId,
                    'google_account_id'=>$console_id,
                    'permission_level'=>$site->permissionLevel,
                    'siteUrl' => $site->siteUrl                 
                ]);
              }
          }
      }

      return true;
  }catch(Exception $e){
    return false;
    return $e->getMessage();
}
}   

public static function refresh_console_urls($service,$campaignId, $console_id,$user_id){
    $result = array();
    try{

        $site_data = $service->sites->listSites();

        if(isset($site_data) && !empty($site_data)){
            foreach($site_data as $site){
                $siteUrl = $site->siteUrl;
                $if_exists =  SearchConsoleUrl::where('siteUrl',$siteUrl)->where('google_account_id',$console_id)->where('user_id',$user_id)->first();   
                if($if_exists){
                    SearchConsoleUrl::where('id',$if_exists->id)->update([
                        'user_id'=>$user_id,
                        'request_id'=>$campaignId,
                        'google_account_id'=>$console_id,
                        'permission_level'=>$site->permissionLevel,
                        'siteUrl' => $site->siteUrl                 
                    ]);
                }else{
                  SearchConsoleUrl::create([
                    'user_id'=>$user_id,
                    'request_id'=>$campaignId,
                    'google_account_id'=>$console_id,
                    'permission_level'=>$site->permissionLevel,
                    'siteUrl' => $site->siteUrl                 
                ]);
              }
          }
      }

      $result['status'] = 1;
  }catch(Exception $e){
    $error = json_decode($e->getMessage() , true);
    $result['status'] = 0;
    $result['message'] = $error['error'];
}
return $result;
}   


public static function checkConsoleData($campaignID,$user_id,$google_console_id,$console_account_id){
    $get_profile_data = SearchConsoleUrl::where('id',$console_account_id)->first();

    if($get_profile_data == null){
        $result['status'] = 2;
        $result['message'] = 'url not found';
        return $result;
    }

    $error   = array();

    $getAnalytics  = SearchConsoleUsers::where('user_id',$user_id)->where('id',$google_console_id)->first();
    if($getAnalytics){
        $client = SearchConsoleUsers::ConsoleClientAuth($getAnalytics);

        $refresh_token  = $getAnalytics->google_refresh_token;



        $start_date = date('Y-m-d', strtotime("-1 week", strtotime(date('Y-m-d'))));
        $end_date = date('Y-m-d');

        try{
           
            $profile_url = $get_profile_data->siteUrl;
            $page   = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $page->setStartDate($start_date);
            $page->setEndDate($end_date);
            $page->setDimensions(['date']);
            $page->setSearchType('web');
            $service = new \Google_Service_Webmasters($client);
            $pages = $service->searchanalytics->query($profile_url, $page);

            $result['status'] = 1;
            $result['message'] = $pages;    
        }catch(Exception $e){

            $error = json_decode($e->getMessage(),true);
            
            $result['status'] = 0;
            $result['message'] = $error['error'];
        }

        return $result;
    }


}


public static function checkConsoleData_cron($client,$campaignID,$user_id,$google_console_id,$console_account_id){
    $error   = array();
    
    $get_profile_data = SearchConsoleUrl::where('id',$console_account_id)->first();

    if($get_profile_data == null){
        $result['status'] = 2;
        $result['message'] = 'url not found';
        return $result;
    }
    
    $getAnalytics  = SearchConsoleUsers::where('user_id',$user_id)->where('id',$google_console_id)->first();
    if($getAnalytics){
        $start_date = date('Y-m-d', strtotime("-1 week", strtotime(date('Y-m-d'))));
        $end_date = date('Y-m-d');

        try{
            $profile_url = $get_profile_data->siteUrl;
            $page   = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $page->setStartDate($start_date);
            $page->setEndDate($end_date);
            $page->setDimensions(['date']);
            $page->setSearchType('web');
            $service = new \Google_Service_Webmasters($client);
            $pages = $service->searchanalytics->query($profile_url, $page);

            $result['status'] = 1;
            $result['message'] = $pages;    
        }catch(Exception $e){

            $error = json_decode($e->getMessage(),true);
            
            $result['status'] = 0;
            $result['message'] = $error['error'];
        }

    }
    return $result;


}


public static function search_console_query($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year){

    $final_query = array();
    $month_query_keys = $month_query_clicks = $month_query_impressions = $month_query_ctr = $month_query_position =  '';
    $three_query_keys = $three_query_clicks = $three_query_impressions = $three_query_ctr = $three_query_position ='';
    $six_query_keys = $six_query_clicks = $six_query_impressions = $six_query_ctr= $six_query_position= '';
    $nine_query_keys = $nine_query_clicks = $nine_query_impressions = $nine_query_ctr = $nine_query_position = '';
    $one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = $one_year_query_ctr = $one_year_query_position = '';
    $query_dates=$query_converted_dates=    $query_keys = $query_clicks = $query_impressions  = $query_ctr = $query_position = '';
    $nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = array();


    $search_console_query = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$start_date,$end_date); 

    $search_console_query_one = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$one_month,$end_date);  

    $search_console_query_three = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$three_month,$end_date);  
    $search_console_query_six = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$six_month,$end_date);  
    $search_console_query_nine = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$nine_month,$end_date);    
    $search_console_query_year = GoogleAnalyticsUsers::getSearchConsoleQuery($client,$profileUrl,$one_year,$end_date);  



    if(!empty($search_console_query_one)){

        foreach($search_console_query_one->getRows() as $month_query){

            $month_query_keys = $month_query->keys[0];
            $month_query_clicks = $month_query->clicks;
            $month_query_impressions = $month_query->impressions;
            $month_query_ctr = $month_query->ctr;
            $month_query_position = $month_query->position;

            $month_query_array[] = array(
                'month_query_keys'=>$month_query_keys,
                'month_query_clicks' =>$month_query_clicks,
                'month_query_impressions'=>$month_query_impressions,
                'month_query_ctr'=>$month_query_ctr,
                'month_query_position'=>$month_query_position
            );
        }

    }

    if(!empty($search_console_query_three)){
        foreach($search_console_query_three->getRows() as $three_query){
            $three_query_keys = $three_query->keys[0]   ;
            $three_query_clicks = $three_query->clicks;
            $three_query_impressions = $three_query->impressions;
            $three_query_ctr = $three_query->ctr;
            $three_query_position = $three_query->position;

            $three_query_array[] = array(
                'three_query_keys'=>$three_query_keys,
                'three_query_clicks' =>$three_query_clicks,
                'three_query_impressions'=>$three_query_impressions,
                'three_query_ctr'=>$three_query_ctr,
                'three_query_position'=>$three_query_position
            );
        }
    }

    if(!empty($search_console_query_six)){
        foreach($search_console_query_six->getRows() as $six_query){
            $six_query_keys = $six_query->keys[0]   ;
            $six_query_clicks = $six_query->clicks;
            $six_query_impressions = $six_query->impressions;
            $six_query_ctr = $six_query->ctr;
            $six_query_position = $six_query->position;


            $six_query_array[] = array(
                'six_query_keys'=>$six_query_keys,
                'six_query_clicks' =>$six_query_clicks,
                'six_query_impressions'=>$six_query_impressions,
                'six_query_ctr'=>$six_query_ctr,
                'six_query_position'=>$six_query_position
            );
        }
    }                           

    if(!empty($search_console_query_nine)){
        foreach($search_console_query_nine->getRows() as $nine_query){
            $nine_query_keys = $nine_query->keys[0] ;
            $nine_query_clicks = $nine_query->clicks;
            $nine_query_impressions = $nine_query->impressions;
            $nine_query_ctr = $nine_query->ctr;
            $nine_query_position = $nine_query->position;

            $nine_query_array[] = array(
                'nine_query_keys'=>$nine_query_keys,
                'nine_query_clicks' =>$nine_query_clicks,
                'nine_query_impressions'=>$nine_query_impressions,
                'nine_query_ctr'=>$nine_query_ctr,
                'nine_query_position'=>$nine_query_position
            );
        }
    }

    if(!empty($search_console_query_year)){
        foreach($search_console_query_year->getRows() as $one_year_query){
            $one_year_query_keys = $one_year_query->keys[0] ;
            $one_year_query_clicks = $one_year_query->clicks;
            $one_year_query_impressions = $one_year_query->impressions;
            $one_year_query_ctr = $one_year_query->ctr;
            $one_year_query_position = $one_year_query->position;

            $one_year_query_array[] = array(
                'one_year_query_keys'=>$one_year_query_keys,
                'one_year_query_clicks' =>$one_year_query_clicks,
                'one_year_query_impressions'=>$one_year_query_impressions,
                'one_year_query_ctr'=>$one_year_query_ctr,
                'one_year_query_position'=>$one_year_query_position
            );
        }
    }

    if(!empty($search_console_query)){
        foreach($search_console_query->getRows() as $query_key=> $query){
            $query_keys = $query->keys[0]   ;
            $query_clicks = $query->clicks;
            $query_impressions = $query->impressions;
            $query_ctr = $query->ctr;
            $query_position = $query->position;


            $query_array[] = array(
                'query_keys'=>$query_keys,
                'query_clicks' =>$query_clicks,
                'query_impressions'=>$query_impressions,
                'query_ctr'=>$query_ctr,
                'query_position'=>$query_position
            );
        }
    }                           

    $final_query = array(
        'month_query_array'=>$month_query_array,
        'three_query_array'=>$three_query_array,
        'six_query_array'=>$six_query_array,
        'nine_query_array'=>$nine_query_array,
        'one_year_query_array'=>$one_year_query_array,
        'two_year_query_array'=>$query_array
    );


    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/query.json', print_r(json_encode($final_query,true),true));

    $month_query_keys = $month_query_clicks = $month_query_impressions = $month_query_ctr = $month_query_position =  '';
    $three_query_keys = $three_query_clicks = $three_query_impressions = $three_query_ctr = $three_query_position ='';
    $six_query_keys = $six_query_clicks = $six_query_impressions = $six_query_ctr= $six_query_position= '';
    $nine_query_keys = $nine_query_clicks = $nine_query_impressions = $nine_query_ctr = $nine_query_position = '';
    $one_year_query_keys = $one_year_query_clicks = $one_year_query_impressions = $one_year_query_ctr = $one_year_query_position = '';
    $query_dates=$query_converted_dates=    $query_keys = $query_clicks = $query_impressions  = $query_ctr = $query_position ='';

    $nine_query_array =  $six_query_array = $three_query_array = $one_year_query_array = $month_query_array = $query_array = $final_query = array();
}

public static function search_console_devices($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year){

    $month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

    $three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
    $six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
    $nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
    $year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
    $two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
    $month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();

    $one_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$one_month,$end_date);
    $three_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$three_month,$end_date);
    $six_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$six_month,$end_date);
    $nine_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$nine_month,$end_date);
    $year_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$one_year,$end_date);
    $two_year_search_console_device = GoogleAnalyticsUsers::getSearchConsoleDevice($client,$profileUrl,$start_date,$end_date);


    if(!empty($one_search_console_device)){
        foreach($one_search_console_device->getRows() as $month_device){

            $month_device_keys = $month_device->keys[0];
            $month_device_clicks = $month_device->clicks;
            $month_device_impressions = $month_device->impressions;
            $month_device_ctr = $month_device->ctr;
            $month_device_position = $month_device->position;

            $month_device_array[] = array(
                'month_device_keys'=>$month_device_keys,
                'month_device_clicks' =>$month_device_clicks,
                'month_device_impressions'=>$month_device_impressions,
                'month_device_ctr'=>$month_device_ctr,
                'month_device_position'=>$month_device_position
            );
        }
    }


    if(!empty($three_search_console_device)){
        foreach($three_search_console_device->getRows() as $three_device){

            $three_device_keys = $three_device->keys[0];
            $three_device_clicks = $three_device->clicks;
            $three_device_impressions = $three_device->impressions;
            $three_device_ctr = $three_device->ctr;
            $three_device_position = $three_device->position;

            $three_device_array[] = array(
                'three_device_keys'=>$three_device_keys,
                'three_device_clicks' =>$three_device_clicks,
                'three_device_impressions'=>$three_device_impressions,
                'three_device_ctr'=>$three_device_ctr,
                'three_device_position'=>$three_device_position
            );
        }
    }


    if(!empty($six_search_console_device)){
        foreach($six_search_console_device->getRows() as $six_device){

            $six_device_keys = $six_device->keys[0];
            $six_device_clicks = $six_device->clicks;
            $six_device_impressions = $six_device->impressions;
            $six_device_ctr = $six_device->ctr;
            $six_device_position = $six_device->position;

            $six_device_array[] = array(
                'six_device_keys'=>$six_device_keys,
                'six_device_clicks' =>$six_device_clicks,
                'six_device_impressions'=>$six_device_impressions,
                'six_device_ctr'=>$six_device_ctr,
                'six_device_position'=>$six_device_position
            );
        }
    }


    if(!empty($nine_search_console_device)){
        foreach($nine_search_console_device->getRows() as $nine_device){

            $nine_device_keys = $nine_device->keys[0];
            $nine_device_clicks = $nine_device->clicks;
            $nine_device_impressions = $nine_device->impressions;
            $nine_device_ctr = $nine_device->ctr;
            $nine_device_position = $nine_device->position;

            $nine_device_array[] = array(
                'nine_device_keys'=>$nine_device_keys,
                'nine_device_clicks' =>$nine_device_clicks,
                'nine_device_impressions'=>$nine_device_impressions,
                'nine_device_ctr'=>$nine_device_ctr,
                'nine_device_position'=>$nine_device_position
            );
        }
    }


    if(!empty($year_console_device)){
        foreach($year_console_device->getRows() as $year_device){

            $year_device_keys = $year_device->keys[0];
            $year_device_clicks = $year_device->clicks;
            $year_device_impressions = $year_device->impressions;
            $year_device_ctr = $year_device->ctr;
            $year_device_position = $year_device->position;

            $year_device_array[] = array(
                'year_device_keys'=>$year_device_keys,
                'year_device_clicks' =>$year_device_clicks,
                'year_device_impressions'=>$year_device_impressions,
                'year_device_ctr'=>$year_device_ctr,
                'year_device_position'=>$year_device_position
            );
        }
    }


    if(!empty($two_year_search_console_device)){
        foreach($two_year_search_console_device->getRows() as $two_year_device){

            $two_year_device_keys = $two_year_device->keys[0];
            $two_year_device_clicks = $two_year_device->clicks;
            $two_year_device_impressions = $two_year_device->impressions;
            $two_year_device_ctr = $two_year_device->ctr;
            $two_year_device_position = $two_year_device->position;

            $two_year_device_array[] = array(
                'two_year_device_keys'=>$two_year_device_keys,
                'two_year_device_clicks' =>$two_year_device_clicks,
                'two_year_device_impressions'=>$two_year_device_impressions,
                'two_year_device_ctr'=>$two_year_device_ctr,
                'two_year_device_position'=>$two_year_device_position
            );
        }
    }

    $final_device = array(
        'month_device_array'=>$month_device_array,
        'three_device_array'=>$three_device_array,
        'six_device_array'=>$six_device_array,
        'nine_device_array'=>$nine_device_array,
        'year_device_array'=>$year_device_array,
        'two_year_device_array'=>$two_year_device_array
    );

    
    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/device.json', print_r(json_encode($final_device,true),true));
    




    $month_device_keys = $month_device_clicks = $month_device_impressions = $month_device_ctr =  $month_device_position =  '';

    $three_device_keys = $three_device_clicks = $three_device_impressions = $three_device_ctr =  $three_device_position =  '';
    $six_device_keys = $six_device_clicks = $six_device_impressions = $six_device_ctr =  $six_device_position =  '';
    $nine_device_keys = $nine_device_clicks = $nine_device_impressions = $nine_device_ctr =  $nine_device_position =  '';
    $year_device_keys = $year_device_clicks = $year_device_impressions = $year_device_ctr =  $year_device_position =  '';
    $two_year_device_keys = $two_year_device_clicks = $two_year_device_impressions = $two_year_device_ctr =  $two_year_device_position =  '';
    $month_device_array = $three_device_array = $six_device_array = $nine_device_array = $year_device_array = $two_year_device_array =  $final_device = array();
}

public static function search_console_pages($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year){

    $month_page_keys = $month_page_clicks = $month_page_impressions = '';
    $three_page_keys = $three_page_clicks = $three_page_impressions = '';
    $six_page_keys = $six_page_clicks = $six_page_impressions = '';
    $nine_page_keys = $nine_page_clicks = $nine_page_impressions = '';
    $year_page_keys = $year_page_clicks = $year_page_impressions = '';
    $two_year_page_keys = $two_year_page_clicks = $two_year_page_impressions = '';
    $month_page_array = $three_page_array = $six_page_array = $nine_page_array =  $year_page_array = $two_year_page_array = $final_page = array();

    $one_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$one_month,$end_date);
    $three_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$three_month,$end_date);
    $six_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$six_month,$end_date);
    $nine_month_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$nine_month,$end_date);
    $one_year_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$one_year,$end_date);
    $two_year_page =GoogleAnalyticsUsers::getSearchConsolePages($client,$profileUrl,$start_date,$end_date);




    if(!empty($one_month_page)){
        foreach($one_month_page->getRows() as $month_page){
            $month_page_keys = $month_page->keys[0];
            $month_page_clicks = $month_page->clicks;
            $month_page_impressions = $month_page->impressions;

            $month_page_array[] = array(
                'month_page_keys'=>$month_page_keys,
                'month_page_clicks' =>$month_page_clicks,
                'month_page_impressions'=>$month_page_impressions
            );
        }
    }

    if(!empty($three_month_page)){
        foreach($three_month_page->getRows() as $three_page){
            $three_page_keys = $three_page->keys[0];
            $three_page_clicks = $three_page->clicks;
            $three_page_impressions = $three_page->impressions;

            $three_page_array[] = array(
                'three_page_keys'=>$three_page_keys,
                'three_page_clicks' =>$three_page_clicks,
                'three_page_impressions'=>$three_page_impressions
            );
        }
    }

    if(!empty($six_month_page)){
        foreach($six_month_page->getRows() as $six_page){
            $six_page_keys = $six_page->keys[0];
            $six_page_clicks = $six_page->clicks;
            $six_page_impressions = $six_page->impressions;

            $six_page_array[] = array(
                'six_page_keys'=>$six_page_keys,
                'six_page_clicks' =>$six_page_clicks,
                'six_page_impressions'=>$six_page_impressions
            );
        }
    }

    if(!empty($nine_month_page)){
        foreach($nine_month_page->getRows() as $nine_page){
            $nine_page_keys = $nine_page->keys[0];
            $nine_page_clicks = $nine_page->clicks;
            $nine_page_impressions = $nine_page->impressions;

            $nine_page_array[] = array(
                'nine_page_keys'=>$nine_page_keys,
                'nine_page_clicks' =>$nine_page_clicks,
                'nine_page_impressions'=>$nine_page_impressions
            );
        }
    }

    if(!empty($one_year_page)){
        foreach($one_year_page->getRows() as $year_page){
            $year_page_keys = $year_page->keys[0];
            $year_page_clicks = $year_page->clicks;
            $year_page_impressions = $year_page->impressions;

            $year_page_array[] = array(
                'year_page_keys'=>$year_page_keys,
                'year_page_clicks' =>$year_page_clicks,
                'year_page_impressions'=>$year_page_impressions
            );
        }
    }

    if(!empty($two_year_page)){
        foreach($two_year_page->getRows() as $two_yearpage){
            $two_year_page_keys = $two_yearpage->keys[0];
            $two_year_page_clicks = $two_yearpage->clicks;
            $two_year_page_impressions = $two_yearpage->impressions;

            $two_year_page_array[] = array(
                'two_year_page_keys'=>$two_year_page_keys,
                'two_year_page_clicks' =>$two_year_page_clicks,
                'two_year_page_impressions'=>$two_year_page_impressions
            );
        }
    }

    $final_page = array(
        'month_page_array'=>$month_page_array,
        'three_page_array'=>$three_page_array,
        'six_page_array'=>$six_page_array,
        'nine_page_array'=>$nine_page_array,
        'year_page_array'=>$year_page_array,
        'two_year_page_array'=>$two_year_page_array
    );

    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/page.json', print_r(json_encode($final_page,true),true));
    


    $month_page_keys = $month_page_clicks = $month_page_impressions = '';
    $three_page_keys = $three_page_clicks = $three_page_impressions = '';
    $six_page_keys = $six_page_clicks = $six_page_impressions = '';
    $nine_page_keys = $nine_page_clicks = $nine_page_impressions = '';
    $year_page_keys = $year_page_clicks = $year_page_impressions = '';
    $two_year_page_keys = $two_year_page_clicks = $two_year_page_impressions = '';
    $month_page_array = $three_page_array = $six_page_array = $nine_page_array =  $year_page_array = $two_year_page_array = $final_page = array();

}

public static function search_console_country($client,$profileUrl,$start_date,$end_date,$campaignId,$one_month,$three_month,$six_month,$nine_month,$one_year){

    $month_country_keys = $month_country_clicks = $month_country_impressions = $month_country_ctr = $month_country_position =  '';
    $threeCountry_keys = $threeCountry_clicks = $threeCountry_impressions = $threeCountry_ctr = $threeCountry_position =  '';
    $six_month_Country_keys = $six_month_Country_clicks = $six_month_Country_impressions = $six_month_Country_ctr = $six_month_Country_position =  '';
    $nine_month_Country_keys = $nine_month_Country_clicks = $nine_month_Country_impressions = $nine_month_Country_ctr = $nine_month_Country_position =  '';
    $year_Country_keys = $year_Country_clicks = $year_Country_impressions = $year_Country_ctr = $year_Country_position =  '';
    $two_year_Country_keys = $two_year_Country_clicks = $two_year_Country_impressions = $two_year_Country_ctr = $two_year_Country_position =  '';
    $month_country_array = $three_country_array = $six_country_array = $nine_country_array = $year_country_array = $final_country = $two_year_country_array =  array();

    $month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$one_month,$end_date);
    $three_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$three_month,$end_date);
    $six_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$six_month,$end_date);
    $nine_month_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$nine_month,$end_date);
    $one_year_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$one_year,$end_date);
    $two_year_country = GoogleAnalyticsUsers::getSearchConsoleCountries($client,$profileUrl,$start_date,$end_date);


    if(!empty($month_country)){
        foreach($month_country->getRows() as $monthCountry){

            $month_country_keys = $monthCountry->keys[0];
            $month_country_clicks = $monthCountry->clicks;
            $month_country_impressions = $monthCountry->impressions;
            $month_country_ctr = $monthCountry->ctr;
            $month_country_position = $monthCountry->position;

            $month_country_array[] = array(
                'month_country_keys'=>$month_country_keys,
                'month_country_clicks' =>$month_country_clicks,
                'month_country_impressions'=>$month_country_impressions,
                'month_country_ctr'=>$month_country_ctr,
                'month_country_position'=>$month_country_position
            );
        }
    }

    if(!empty($three_month_country)){
        foreach($three_month_country->getRows() as $threeCountry){

            $threeCountry_keys = $threeCountry->keys[0];
            $threeCountry_clicks = $threeCountry->clicks;
            $threeCountry_impressions = $threeCountry->impressions;
            $threeCountry_ctr = $threeCountry->ctr;
            $threeCountry_position = $threeCountry->position;

            $three_country_array[] = array(
                'threeCountry_keys'=>$threeCountry_keys,
                'threeCountry_clicks' =>$threeCountry_clicks,
                'threeCountry_impressions'=>$threeCountry_impressions,
                'threeCountry_ctr'=>$threeCountry_ctr,
                'threeCountry_position'=>$threeCountry_position
            );
        }

    }

    if(!empty($six_month_country)){
        foreach($six_month_country->getRows() as $six_month_Country){

            $six_month_Country_keys = $six_month_Country->keys[0];
            $six_month_Country_clicks = $six_month_Country->clicks;
            $six_month_Country_impressions = $six_month_Country->impressions;
            $six_month_Country_ctr = $six_month_Country->ctr;
            $six_month_Country_position = $six_month_Country->position;

            $six_country_array[] = array(
                'six_month_Country_keys'=>$six_month_Country_keys,
                'six_month_Country_clicks' =>$six_month_Country_clicks,
                'six_month_Country_impressions'=>$six_month_Country_impressions,
                'six_month_Country_ctr'=>$six_month_Country_ctr,
                'six_month_Country_position'=>$six_month_Country_position
            );
        }

    }

    if(!empty($nine_month_country)){
        foreach($nine_month_country->getRows() as $nine_month_Country){

            $nine_month_Country_keys = $nine_month_Country->keys[0];
            $nine_month_Country_clicks = $nine_month_Country->clicks;
            $nine_month_Country_impressions = $nine_month_Country->impressions;
            $nine_month_Country_ctr = $nine_month_Country->ctr;
            $nine_month_Country_position = $nine_month_Country->position;

            $nine_country_array[] = array(
                'nine_month_Country_keys'=>$nine_month_Country_keys,
                'nine_month_Country_clicks' =>$nine_month_Country_clicks,
                'nine_month_Country_impressions'=>$nine_month_Country_impressions,
                'nine_month_Country_ctr'=>$nine_month_Country_ctr,
                'nine_month_Country_position'=>$nine_month_Country_position
            );
        }

    }

    if(!empty($one_year_country)){
        foreach($one_year_country->getRows() as $year_Country){

            $year_Country_keys = $year_Country->keys[0];
            $year_Country_clicks = $year_Country->clicks;
            $year_Country_impressions = $year_Country->impressions;
            $year_Country_ctr = $year_Country->ctr;
            $year_Country_position = $year_Country->position;

            $year_country_array[] = array(
                'year_Country_keys'=>$year_Country_keys,
                'year_Country_clicks' =>$year_Country_clicks,
                'year_Country_impressions'=>$year_Country_impressions,
                'year_Country_ctr'=>$year_Country_ctr,
                'year_Country_position'=>$year_Country_position
            );
        }

    }


    if(!empty($two_year_country)){
        foreach($two_year_country->getRows() as $two_year_Country){

            $two_year_Country_keys = $two_year_Country->keys[0];
            $two_year_Country_clicks = $two_year_Country->clicks;
            $two_year_Country_impressions = $two_year_Country->impressions;
            $two_year_Country_ctr = $two_year_Country->ctr;
            $two_year_Country_position = $two_year_Country->position;

            $two_year_country_array[] = array(
                'two_year_Country_keys'=>$two_year_Country_keys,
                'two_year_Country_clicks' =>$two_year_Country_clicks,
                'two_year_Country_impressions'=>$two_year_Country_impressions,
                'two_year_Country_ctr'=>$two_year_Country_ctr,
                'two_year_Country_position'=>$two_year_Country_position
            );
        }

    }

    $final_country = array(
        'month_country_array'=>$month_country_array,
        'three_country_array'=>$three_country_array,
        'six_country_array'=>$six_country_array,
        'nine_country_array'=>$nine_country_array,
        'year_country_array'=>$year_country_array,
        'two_year_country_array'=>$two_year_country_array
    );


    
    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/country.json', print_r(json_encode($final_country,true),true));

    

    $month_country_keys = $month_country_clicks = $month_country_impressions = $month_country_ctr = $month_country_position =  '';
    $threeCountry_keys = $threeCountry_clicks = $threeCountry_impressions = $threeCountry_ctr = $threeCountry_position =  '';
    $six_month_Country_keys = $six_month_Country_clicks = $six_month_Country_impressions = $six_month_Country_ctr = $six_month_Country_position =  '';
    $nine_month_Country_keys = $nine_month_Country_clicks = $nine_month_Country_impressions = $nine_month_Country_ctr = $nine_month_Country_position =  '';
    $year_Country_keys = $year_Country_clicks = $year_Country_impressions = $year_Country_ctr = $year_Country_position =  '';
    $two_year_Country_keys = $two_year_Country_clicks = $two_year_Country_impressions = $two_year_Country_ctr = $two_year_Country_position =  '';


    $month_country_array = $three_country_array = $six_country_array = $nine_country_array = $year_country_array = $final_country = $two_year_country_array =  array();

}


public static function search_console_graph_data($client,$profileUrl,$start_date,$end_date,$campaignId){

    $dates = $converted_dates = $clicks = $impressions = $data_array = array();

    $searchConsoleData = GoogleAnalyticsUsers::getSearchConsoleData($client,$profileUrl,$start_date,$end_date);
    if(!empty($searchConsoleData)){
        foreach($searchConsoleData->getRows() as $data_key=>$data){
            $dates[] = $data->keys[0];
            $converted_dates[] = strtotime($data->keys[0])*1000;
            $clicks[]    = array('t'=>strtotime($data->keys[0])*1000,'y'=>$data->clicks);
            $impressions[] = array('t'=>strtotime($data->keys[0])*1000,'y'=>$data->impressions);
        }

    }


    $data_array = array(
        'dates'=>$dates,
        'converted_dates'=>$converted_dates,
        'clicks' =>$clicks,
        'impressions'=>$impressions
    );

    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graph.json', print_r(json_encode($data_array,true),true));
    
    $dates = $converted_dates = $clicks = $impressions = array();
}

public static function close_method(){
    echo  "<script type='text/javascript'>";
    echo "window.close();";
    echo "getSearchConsoleAccounts();";
    echo "</script>";
}


/*new design changes*/
public static function check_weekly_data($client,$profile_url,$console_account_id){
    $error = $result = array();
    if(!empty($console_account_id)){
        $start_date = date('Y-m-d', strtotime("-1 week", strtotime(date('Y-m-d'))));
        $end_date = date('Y-m-d');
        try{
            $page = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $page->setStartDate($start_date);
            $page->setEndDate($end_date);
            $page->setDimensions(['date']);
            $page->setSearchType('web');
            $page->setDataState('final');
            $service = new \Google_Service_Webmasters($client);
            $pages = $service->searchanalytics->query($profile_url, $page);

            $result['status'] = 1;
            $result['message'] = $pages;    
        }catch(Exception $e){
            $error = json_decode($e->getMessage(),true);
            $result['status'] = 0;
            $result['message'] = $error['error'];
        }            
    }else{
        $result['status'] = 2;
        $result['message'] = 'Console url id doesnot exists.';
    }

    return $result;
}

public static function search_console_data($client,$profileUrl,$start_date,$end_date,$three_start_date,$campaignId){
   $search_console_graph = SearchConsoleUrl::getSearchConsoleMetrics($client,$profileUrl,$start_date,$end_date,'date',1000);
   $search_console_query = SearchConsoleUrl::getSearchConsoleMetrics($client,$profileUrl,$three_start_date,$end_date,'query',100);
   $search_console_pages = SearchConsoleUrl::getSearchConsoleMetrics($client,$profileUrl,$three_start_date,$end_date,'page',100);
   $search_console_country = SearchConsoleUrl::getSearchConsoleMetrics($client,$profileUrl,$three_start_date,$end_date,'country',100);

   $query_array = $pages_array = $country_array = array();

   /*graph data*/
   if(!empty($search_console_graph)){
    foreach($search_console_graph->getRows() as $data_key=>$data){
        $dates[] = $data->keys[0];
        $converted_dates[] = strtotime($data->keys[0])*1000;
        $clicks[]    = array('t'=>strtotime($data->keys[0])*1000,'y'=>$data->clicks);
        $impressions[] = array('t'=>strtotime($data->keys[0])*1000,'y'=>$data->impressions);
        $ctr[] = $data->ctr;
        $position[] = $data->position;
    }
}

$graph_array = array(
    'dates'=>$dates,
    'converted_dates'=>$converted_dates,
    'clicks' =>$clicks,
    'impressions'=>$impressions,
    'ctr'=>$ctr,
    'position'=>$position
);
/*graph data*/


/*queries data*/
if(!empty($search_console_query)){
    foreach($search_console_query->getRows() as $data_key=>$q_data){
        $query_array[] = array(
            'queries'=>$q_data->keys[0],
            'clicks' =>$q_data->clicks,
            'impressions'=>$q_data->impressions,
            'ctr'=>$q_data->ctr,
            'position'=>$q_data->position
        ); 
    }
}


/*queries data*/

/*pages data*/
if(!empty($search_console_pages)){
    foreach($search_console_pages->getRows() as $data_key=>$pages_data){
      $pages_array[] = array(
        'page'=>$pages_data->keys[0],
        'clicks' =>$pages_data->clicks,
        'impressions'=>$pages_data->impressions,
        'ctr'=>$pages_data->ctr,
        'position'=>$pages_data->position
    ); 
  }
}


/*pages data*/


/*country data*/
if(!empty($search_console_country)){
    foreach($search_console_country->getRows() as $data_key=>$country_data){
     $country_array[] = array(
        'country'=>$country_data->keys[0],
        'clicks' =>$country_data->clicks,
        'impressions'=>$country_data->impressions,
        'ctr'=>$country_data->ctr,
        'position'=>$country_data->position
    );
 }
}

/*country data*/

if (file_exists(\config('app.FILE_PATH').'public/search_console/'.$campaignId)) {
    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graphs.json', print_r(json_encode($graph_array,true),true));
    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/queries.json', print_r(json_encode($query_array,true),true));
    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/pages.json', print_r(json_encode($pages_array,true),true));
    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/countries.json', print_r(json_encode($country_array,true),true));
}else{
    mkdir(\config('app.FILE_PATH').'public/search_console/'.$campaignId, 0777, true);
    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/graphs.json', print_r(json_encode($graph_array,true),true));
    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/queries.json', print_r(json_encode($query_array,true),true));
    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/pages.json', print_r(json_encode($pages_array,true),true));
    file_put_contents(env('FILE_PATH').'public/search_console/'.$campaignId.'/countries.json', print_r(json_encode($country_array,true),true));
}

$query_array = $pages_array = $country_array = array();
}

public static function getSearchConsoleMetrics($client, $profileUrl, $start_date, $end_date,$dimension,$limit){
    try
    {
        $query = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
        $query->setStartDate($start_date);
        $query->setEndDate($end_date);
        $query->setDimensions([$dimension]);
        $query->setSearchType('web');
        $query->setDataState('final');
        $query->setRowLimit($limit);

        $service = new \Google_Service_Webmasters($client);
        $site = $service->sites->get($profileUrl);
        $query_data = $service->searchanalytics->query($profileUrl, $query);
        
        return $query_data;
    }
    catch(Exception $e)
    {
        $error = json_decode($e->getMessage() , true);
        return $error;
    }
}

}