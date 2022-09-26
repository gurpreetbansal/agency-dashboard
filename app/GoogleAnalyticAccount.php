<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;
use App\GoogleAccountViewData;
use Auth;
use App\SemrushUserAccount;
use App\SearchConsoleUrl;
use Session;

use Google\Analytics\Admin\V1alpha\AnalyticsAdminServiceClient;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;


class GoogleAnalyticAccount extends Model
{

protected $table = 'google_analytic_accounts';

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
protected $fillable = ['user_id', 'google_email_id','account_id','property_id', 'name', 'display_name', 'parent_id','created_at', 'updated_at'];

public static function get_admin_service_client($refresh_token){
	$service = new AnalyticsAdminServiceClient([
		'credentials' => \Google\ApiCore\CredentialsWrapper::build( [
			'scopes'  => [
				'https://www.googleapis.com/auth/analytics',
				'openid',
				'https://www.googleapis.com/auth/analytics.readonly',
			],
			'keyFile' => [
				'type'          => 'authorized_user',
				'client_id'     => \config('app.ads_client_id'),
				'client_secret' => \config('app.ads_client_secret'),
				'refresh_token' => $refresh_token
			],
		]),
	]);
	return $service;
}

public static function get_beta_service_client($refresh_token){
    $service = new BetaAnalyticsDataClient( [
       'credentials' => \Google\ApiCore\CredentialsWrapper::build([
           'keyFile' => [
               'type'          => 'authorized_user',
               'client_id'     => \config('app.ads_client_id'),
               'client_secret' => \config('app.ads_client_secret'),
               'refresh_token' => $refresh_token
           ],
       ]),
   ]);

    return $service;
}

public static function getGoogleAccountsList($service_client,$campaignId,$analytics_id,$user_id,$provider){
	try{
		$accounts = $service_client->listAccountSummaries();
        if(isset($accounts) && !empty($accounts)){
            foreach ($accounts as $account) {
                $if_exists = GoogleAnalyticAccount::where('account_id', $account->getAccount())->where('google_email_id', $analytics_id)->where('user_id', $user_id)->first();
                if ($if_exists){
                    GoogleAnalyticAccount::where('id', $if_exists->id)->update([
                        'name' => $account->getName(),
                        'display_name' => $account->getDisplayName()
                    ]);
                    $parent_id = $if_exists->id;
                }else{
                    $account_data = GoogleAnalyticAccount::create([
                        'user_id' => $user_id,
                        'google_email_id' => $analytics_id, 
                        'account_id' => $account->getAccount(),
                        'name' => $account->getName(),
                        'display_name' => $account->getDisplayName(),
                        'parent_id' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $parent_id = $account_data->id;
                }

                $properties = $account->getPropertySummaries();
                foreach ($properties as $property) {
                    $if_property_exists = GoogleAnalyticAccount::where('property_id', $property->getProperty())->where('google_email_id', $analytics_id)->where('user_id', $user_id)->where('parent_id',$parent_id)->first();
                    if($if_property_exists){
                        GoogleAnalyticAccount::where('id', $if_property_exists->id)->update([
                            'account_id' => NULL,
                            'property_id' => $property->getProperty(),
                            'display_name' => $property->getDisplayName()
                        ]);                            
                    }else{
                        GoogleAnalyticAccount::create([
                            'user_id' => $user_id,
                            'google_email_id' => $analytics_id,
                            'account_id' => NULL,
                            'property_id' => $property->getProperty(),
                            'display_name' => $property->getDisplayName(),
                            'parent_id' => $parent_id
                        ]);
                    }
                }
                sleep(1);
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

public static function get_run_report($service,$property,$start_date,$end_date){
    $response = $service->runReport([
        'property' => $property,
        'dateRanges' => [new DateRange(['start_date' => $start_date, 'end_date' => $end_date])],
        'dimensions' => [new Dimension(['name' => 'date'])],
        'metrics' => [new Metric(['name' => 'activeUsers']), new Metric(['name' => 'newUsers'])],
        'orderBys' => [new OrderBy(['dimension' => new OrderBy\DimensionOrderBy(['dimension_name' => 'date','order_type' => OrderBy\DimensionOrderBy\OrderType::NUMERIC])])]
    ]);
    return $response;
}

public static function traffic_acquisiion($service,$property,$start_date,$end_date){
    $response = $service->runReport([
        'property' => $property,
        'dateRanges' => [new DateRange(['start_date' => $start_date, 'end_date' => $end_date])],
        'dimensions' => [new Dimension(['name' => 'date']), new Dimension(['name' => 'sessionDefaultChannelGrouping'])],
        'metrics' => [new Metric(['name' => 'activeUsers']), new Metric(['name' => 'sessions']), new Metric(['name' => 'engagedSessions']), new Metric(['name' => 'userEngagementDuration']), new Metric(['name' => 'averageSessionDuration']), new Metric(['name' => 'eventsPerSession']), new Metric(['name' => 'engagementRate']), new Metric(['name' => 'eventCount']), new Metric(['name' => 'conversions']), new Metric(['name' => 'totalRevenue'])],
        'orderBys' => [new OrderBy(['dimension' => new OrderBy\DimensionOrderBy(['dimension_name' => 'date','order_type' => OrderBy\DimensionOrderBy\OrderType::NUMERIC])])]
    ]);
    return $response;
}

public static function log_ga4_data($service,$property,$start_date,$end_date,$campaign_id){
   try{
        $data = SemrushUserAccount::where('ga4_account_id','!=',NULL)->where('id',$campaign_id)->first();


        if (file_exists(\config('app.FILE_PATH').'public/google_analytics_4/'.$campaign_id)) {
            $graphfilename = \config('app.FILE_PATH').'public/google_analytics_4/'.$campaign_id.'/graph.json';
            if(file_exists($graphfilename)){
                if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){
                    self::graph_data($service,$property,$start_date,$end_date,$campaign_id);
                }else{
                    self::graph_data($service,$property,$start_date,$end_date,$campaign_id);
                }
            }else{
              self::graph_data($service,$property,$start_date,$end_date,$campaign_id);
            }
        }else {
            mkdir(\config('app.FILE_PATH').'public/google_analytics_4/'.$campaign_id, 0777, true);
            self::graph_data($service,$property,$start_date,$end_date,$campaign_id);
        }

         if (file_exists(\config('app.FILE_PATH').'public/google_analytics_4/'.$campaign_id)) {
            $graphfilename = \config('app.FILE_PATH').'public/google_analytics_4/'.$campaign_id.'/traffic_acquisition.json';
            
            if(file_exists($graphfilename)){
                if(date("Y-m-d", filemtime($graphfilename)) != date('Y-m-d')){
                    self::get_traffic_acquisition($service,$property,$start_date,$end_date,$campaign_id);
                }else{
                    self::get_traffic_acquisition($service,$property,$start_date,$end_date,$campaign_id);
                }
            }else{
              self::get_traffic_acquisition($service,$property,$start_date,$end_date,$campaign_id);
            }
        }else {
            mkdir(\config('app.FILE_PATH').'public/google_analytics_4/'.$campaign_id, 0777, true);
            self::get_traffic_acquisition($service,$property,$start_date,$end_date,$campaign_id);
        }
    }catch(Exception $e){
        $error = json_decode($e->getMessage(),true);
       
        $result['status'] = 0;
        $result['message'] = $error['error'];
        return $result;
    }
}

public static function graph_data($service,$property,$start_date,$end_date,$campaign_id){
    $dates = $active_users = $new_users = $data_array = $converted_date = array();

    $response = self::get_run_report($service,$property,$start_date,$end_date);

    if(!empty($response)){
        foreach ($response->getRows() as $row) {
            $date = $row->getDimensionValues()[0]->getValue();
            $dates[] = $row->getDimensionValues()[0]->getValue();
            $active_users[] = $row->getMetricValues()[0]->getValue();
            $new_users[] = $row->getMetricValues()[1]->getValue();

            $converted_date[] = date('Y-m-d',strtotime($date));
        }
    }

    $data_array = array(
        'dates'=> $converted_date,
        'active_users'=>$active_users,
        'new_users' =>$new_users
    );

    file_put_contents(env('FILE_PATH').'public/google_analytics_4/'.$campaign_id.'/graph.json', print_r(json_encode($data_array,true),true));
    
    $dates = $active_users = $new_users = $data_array = array();
}

public static function check_week_data($service,$property,$start_date,$end_date){
    $res = array();
    try{
        $response = self::get_run_report($service,$property,$start_date,$end_date);
        $res['status'] = 1;
    }catch(Exception $e){
        $error = json_decode($e->getMessage(),true);
        $res['status'] = 0;
        $res['message'] = $error['message'];
    }

    return $res;
}


public static function get_traffic_acquisition($service,$property,$start_date,$end_date,$campaign_id){
    $dates = $converted_date = $channels = $active_users = $sessions = $engaged_sessions = $average_session_duration = $user_engagement_duration = $events_per_session = $engagement_rate = $event_count = $conversions = $total_revenue = $data_array = array();

    $response = self::traffic_acquisiion($service,$property,$start_date,$end_date);

    if(!empty($response) && $response->getRowCount() > 0){
        foreach ($response->getRows() as $key => $row) {
            $dates = $row->getDimensionValues()[0]->getValue();
            $channel = $row->getDimensionValues()[1]->getValue();

            $active_users = $row->getMetricValues()[0]->getValue();
            $sessions = $row->getMetricValues()[1]->getValue();
            $engaged_sessions = $row->getMetricValues()[2]->getValue();
            $user_engagement_duration = $row->getMetricValues()[3]->getValue();
            $average_session_duration = $row->getMetricValues()[4]->getValue();
            $events_per_session = $row->getMetricValues()[5]->getValue();
            $engagement_rate = $row->getMetricValues()[6]->getValue();
            $event_count = $row->getMetricValues()[7]->getValue();
            $conversions = $row->getMetricValues()[8]->getValue();
            $total_revenue = $row->getMetricValues()[9]->getValue();

            $channel_key = str_replace(' ','_',strtolower($channel));

            $converted_date = date('Y-m-d',strtotime($dates));

            $data_array[$converted_date][$channel_key]['active_users'] = $active_users;
            $data_array[$converted_date][$channel_key]['sessions'] = $sessions;
            $data_array[$converted_date][$channel_key]['engaged_sessions'] = $engaged_sessions;
            $data_array[$converted_date][$channel_key]['average_session_duration'] = $average_session_duration;
            $data_array[$converted_date][$channel_key]['user_engagement_duration'] = $user_engagement_duration;
            $data_array[$converted_date][$channel_key]['events_per_session'] = $events_per_session;
            $data_array[$converted_date][$channel_key]['engagement_rate'] = $engagement_rate;
            $data_array[$converted_date][$channel_key]['event_count'] = $event_count;
            $data_array[$converted_date][$channel_key]['conversions'] = $conversions;
            $data_array[$converted_date][$channel_key]['total_revenue'] = $total_revenue;            
        }
    }

    file_put_contents(env('FILE_PATH').'public/google_analytics_4/'.$campaign_id.'/traffic_acquisition.json', print_r(json_encode($data_array,true),true));
    
    $dates = $converted_date = $channels = $active_users = $sessions = $engaged_sessions = $average_session_duration = $user_engagement_duration = $events_per_session = $engagement_rate = $event_count = $conversions = $total_revenue = $data_array = array();
}

public static function calculate_percentage($current_value,$previous_value){
    $difference = $current_value - $previous_value;
    if($difference == 0){
        $calculate = 0;        
    }else{
        if($previous_value > 0){
            $calculate = ($difference/$previous_value)*100;
        }else{
            $calculate = 0;
        }
    }
    return number_format($calculate,2);
}

public static function calculate_time($value){
    $secs = $value % 60;
    $hrs = $value / 60;
    $mins = $hrs % 60;
    $hrs = $hrs / 60;
    if((int)$hrs > 0){
        return (int)$hrs . "h " . (int)$mins . "m " . (int)$secs . 's';
    }elseif((int)$mins > 0){
        return (int)$mins . "m " . (int)$secs . 's';
    }elseif((int)$secs > 0){
         return '0m '.(int)$secs . 's';
    }else{
        return '-';
    }
}

public static function get_selected_range($range){
    if($range === 'One Month'){
        $duration = 1;
    }elseif($range === 'Three Month'){
        $duration = 3;
    }elseif($range === 'Six Month'){
        $duration = 6;
    }elseif($range === 'Nine Month'){
        $duration = 9;
    }elseif($range === 'One Year'){
        $duration = 12;
    }elseif($range === 'Two Year'){
        $duration = 24;
    }else{
        $duration = 0;
    }
    return $duration;
}


public static function store_ga4_data($request_id){
    $start_date = date('Y-m-d',strtotime('-4 year'));
    $end_date = date('Y-m-d',strtotime('-1 day'));

    $project_detail = SemrushUserAccount::where('id',$request_id)->first();
    $user_id = $project_detail->user_id;
    $email = $project_detail->ga4_email_id;
    $property = $project_detail->ga4_property_id;

    $get_google_user = GoogleAnalyticsUsers::where('user_id',$user_id)->where('id',$email)->first();
    $refresh_token = $get_google_user->google_refresh_token;

    $service = GoogleAnalyticAccount::get_beta_service_client($refresh_token);
    $get_property = GoogleAnalyticAccount::where('id',$property)->first();

   GoogleAnalyticAccount::log_ga4_data($service,$get_property->property_id,$start_date,$end_date,$request_id);
}

}