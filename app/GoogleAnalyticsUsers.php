<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;
use App\GoogleAccountViewData;
use Auth;
use App\SemrushUserAccount;
use App\SearchConsoleUrl;
use Session;

class GoogleAnalyticsUsers extends Model
{

    protected $table = 'google_analytics_users';

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
    protected $fillable = ['user_id', 'google_access_token', 'google_refresh_token', 'oauth_provider', 'oauth_uid', 'first_name', 'last_name', 'email', 'gender', 'locale', 'picture', 'link', 'token_type', 'expires_in', 'id_token', 'service_created', 'account_status', 'created_at', 'updated_at'];

    public static function get_console_urls($service, $campaignId, $console_id, $user_id)
    {
        try
        {
            $site_data = $service
            ->sites
            ->listSites();
            if (isset($site_data) && !empty($site_data))
            {
                foreach ($site_data as $site)
                {
                    SearchConsoleUrl::create(['user_id' => $user_id, 'request_id' => $campaignId, 'google_account_id' => $console_id, 'permission_level' => $site->permissionLevel, 'siteUrl' => $site->siteUrl]);
                }
            }

        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public static function getGoogleAccountsList($analytics, $campaignId, $analyticsId, $user_id, $provider)
    {
        ini_set('max_execution_time', 0);
        $error = array();
        try
        {
            $getAccounts = $analytics->management_accounts->listManagementAccounts();
        }
        catch(Exception $e)
        {
            $error = json_decode($e->getMessage() , true);
        }

        if (empty($error['error']['code']) || $error['error']['code'] == 0)
        {

            if (count($getAccounts->getItems()) > 0)
            {

                $items = $getAccounts->getItems();

                $icount = 1;
                $icountMin = 1;
                foreach ($items as $item)
                {
                    $account_id = $item->getId();
                    $account_name = $item->name;

                    $if_exists = GoogleAccountViewData::where('category_id', $account_id)->where('google_account_id', $analyticsId)->where('user_id', $user_id)->first();

                    if ($if_exists)
                    {
                       GoogleAccountViewData::where('id', $if_exists->id)->update(['category_name' => $account_name]);
                       $lastId  = $if_exists->id;
                   } else {
                    $accountData = GoogleAccountViewData::create(['user_id' => $user_id, 'request_id' => $campaignId, 'google_account_id' => $analyticsId, 'category_name' => $account_name, 'category_id' => $account_id, 'parent_id' => 0, 'created_at' => now() , 'updated_at' => now() ]);
                    $lastId = $accountData->id;
                }

                if ($provider !== 'search_console')
                {
                    $properties = $analytics->management_webproperties->listManagementWebproperties($account_id);

                    $icount++;
                    $icountMin++;
                    if (count($properties->getItems()) > 0)
                    {
                        $propertyAll = $properties->getItems();

                        foreach ($propertyAll as $singleProperty)
                        {
                            $property_id = $singleProperty->getId();
                            $property_name = $singleProperty->name;
                            $if_property = GoogleAccountViewData::where('category_id', $property_id)->where('google_account_id', $analyticsId)->where('user_id', $user_id)->where('parent_id',$lastId)->first();

                            if ($if_property){
                               GoogleAccountViewData::where('id', $if_property->id)->update(['category_name' => $property_name]);
                               $property_last_id = $if_property->id;
                           } else {
                            $propertyData = GoogleAccountViewData::create(['user_id' => $user_id, 'request_id' => $campaignId, 'google_account_id' => $analyticsId, 'category_name' => $property_name, 'category_id' => $property_id, 'parent_id' => $lastId]);
                            $property_last_id = $propertyData->id;
                        } 

                        $profiles = $analytics->management_profiles->listManagementProfiles($account_id, $property_id);
                        $icount++;
                        $icountMin++;
                        if (count($profiles->getItems()) > 0)
                        {
                            $profiles_all = $profiles->getItems();
                            foreach ($profiles_all as $profiles)
                            {
                                $profiles_id = $profiles->getId();
                                $profiles_name = $profiles->name;
                                $if_view = GoogleAccountViewData::where('category_id', $profiles_id)->where('parent_id', $property_last_id)->where('google_account_id', $analyticsId)->where('user_id', $user_id)->first();
                                if ($if_view)
                                {
                                    GoogleAccountViewData::where('id', $if_view->id)->update(['category_name' => $profiles_name]);
                                } else {
                                    GoogleAccountViewData::create(['user_id' => $user_id, 'request_id' => $campaignId, 'google_account_id' => $analyticsId, 'category_name' => $profiles_name, 'category_id' => $profiles_id, 'parent_id' => $property_last_id]);
                                }

                            }
                        }
                    }

                }
            }

            sleep(1);
        }                
    }

}
else
{
    return false;
}

}

public static function getFirstProfileId($analytics, $analyticsCategoryId)
{
        // Get the list of accounts for the authorized user.
    $error = array();
    try
    {
        $accounts = $analytics
        ->management_accounts
        ->listManagementAccounts();
        if (count($accounts->getItems()) > 0)
        {
            $items = $accounts->getItems();
            $items['accounts'] = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            $firstAccountId = $analyticsCategoryId;

                // Get the list of properties for the authorized user.
            $properties = $analytics
            ->management_webproperties
            ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0)
            {
                $items = $properties->getItems();
                $items['property'] = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                    // Get the list of views (profiles) for the authorized user.
                $profiles = $analytics
                ->management_profiles
                ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0)
                {
                    $items = $profiles->getItems();
                    return $items[0]->getId();

                }
            }
        }
    }
    catch(Exception $e)
    {
        return $e->getMessage();
        $error = (json_decode($e->getMessage() , true));
    }
}

public static function getProfileId($campaignId, $analyticsCategoryId)
{
    $data = SemrushUserAccount::where('id', $campaignId)->first();

    $google_analytics_id = $data->google_analytics_id;
    $google_property_id = $data->google_property_id;
    $google_profile_id = $data->google_profile_id;

    $profile_account_data = GoogleAccountViewData::where('id', $google_profile_id)->first();
    if (!empty($profile_account_data))
    {
        $category_id = $profile_account_data->category_id;
    }
    else
    {
        $category_id = '';
    }
    return $category_id;
}

public static function getFirstProfileIdAnalytics($analytics, $analyticsCategoryId)
{

        // Get the list of accounts for the authorized user.
    $error = array();
    try
    {
            // Get the list of properties for the authorized user.
        $properties = $analytics
        ->management_webproperties
        ->listManagementWebproperties($analyticsCategoryId);

        if (count($properties->getItems()) > 0)
        {

            $items = $properties->getItems();
            $items['property'] = $properties->getItems();
            $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
            $profiles = $analytics
            ->management_profiles
            ->listManagementProfiles($analyticsCategoryId, $firstPropertyId);

            if (count($profiles->getItems()) > 0)
            {
                $items = $profiles->getItems();
                return $items[0]->getId();

            }
        }

    }
    catch(Exception $e)
    {
        return $e->getMessage();
        $error = (json_decode($e->getMessage() , true));
    }

}
public static function getFirstPropertyId($analytics)
{

    $error = array();
    try
    {
        $accounts = $analytics
        ->management_accounts
        ->listManagementAccounts();
    }
    catch(Exception $e)
    {
        $error = (json_decode($e->getMessage() , true));
    }
    if (empty($error['error']['code']) || $error['error']['code'] == 0)
    {
        if (count($accounts->getItems()) > 0)
        {
            $items = $accounts->getItems();
            $items['accounts'] = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

                // Get the list of properties for the authorized user.
            $properties = $analytics
            ->management_webproperties
            ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0)
            {
                $items = $properties->getItems();
                $items['property'] = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                $profiles = $analytics
                ->management_profiles
                ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0)
                {
                    $items = $profiles->getItems();
                    $view_id = $profiles->getItems();
                    return $items[0]->getId();
                }
                else
                {
                    throw new Exception('No views (profiles) found for this user.');
                }
            }
            else
            {
                throw new Exception('No properties found for this user.');
            }
        }
        else
        {
            throw new Exception('No accounts found for this user.');
        }
    }
    else
    {
        return 'false';
    }
}

public static function getResults($analytics, $profileId, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $profileId, $end_date, $start_date, 'ga:users,ga:sessions', array(
        'filters' => 'ga:medium==organic',
        'metrics' => 'ga:users,ga:newUsers,ga:sessions,ga:sessionsPerUser,ga:pageviews,ga:pageviewsPerSession,ga:avgSessionDuration,ga:bounceRate',
    ));

}

public static function getProfileUrl($analytics, $analyticsCategoryId)
{
    try
    {
        $properties = $analytics
        ->management_webproperties
        ->listManagementWebproperties($analyticsCategoryId);
        if (count($properties->getItems()) > 0)
        {
            $items = $properties->getItems();
            $websiteUrl = $items[0]->websiteUrl;
            $url = preg_replace('#^https?://#', '', rtrim($websiteUrl, '/'));
            return $websiteUrl;

        }
    }
    catch(Exception $e)
    {
        return $e->getMessage();
    }
}

public static function getsearchProfileUrl($analytics, $analyticsCategoryId)
{
    try
    {
        $properties = $analytics
        ->management_webproperties
        ->listManagementWebproperties($analyticsCategoryId);
        if (count($properties->getItems()) > 0)
        {
            $items = $properties->getItems();
            $websiteUrl = $items[0]->websiteUrl;
            return $websiteUrl;

        }
    }
    catch(Exception $e)
    {
        return $e->getMessage();
    }
}

public static function getResultForDateRange($analytics, $profile, $start_date, $end_date)
{   
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:users', array(
        'dimensions' => 'ga:date',
        'filters' => 'ga:medium==organic'
    ));
}

public static function getResultForDateRangeCron($analytics, $profile, $start_date, $end_date)
{   
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:users', array(
        'dimensions' => 'ga:date',
        'filters' => 'ga:medium==organic'
    ));
}

public static function OrganicgoalCompletion($analytics, $profile, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:goalCompletionsAll', array(
        'dimensions' => 'ga:date',
        'filters' => 'ga:medium==organic'
    ));
}

public static function UsergoalCompletion($analytics, $profile, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:goalCompletionsAll', array(
        'dimensions' => 'ga:date'
    ));
}

public static function GoalCompletionStats($analytics, $profile, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:goalCompletionsAll,ga:goalValueAll,ga:goalConversionRateAll,ga:goalAbandonRateAll', array(
        'dimensions' => 'ga:date'
    ));
}

public static function GoalCompletionOrganicStats($analytics, $profile, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:goalCompletionsAll,ga:goalValueAll,ga:goalConversionRateAll,ga:goalAbandonRateAll', array(
        'dimensions' => 'ga:date',
        'filters' => 'ga:medium==organic'
    ));
}

public static function GoalCompletionLocation($analytics, $profile, $start_date, $end_date)
{
    try{
        return $analytics
        ->data_ga
        ->get('ga:' . $profile, $end_date, $start_date, 'ga:goalCompletionsAll', array(
            'dimensions' => 'ga:goalCompletionLocation',
            'sort'=>'-ga:goalCompletionsAll'
        ));
    }catch(\Exception $e){
        return $e->getMessage();
    }
}

public static function GoalCompletionOrganicLocation($analytics, $profile, $start_date, $end_date)
{
    try{
        return $analytics
        ->data_ga
        ->get('ga:' . $profile, $end_date, $start_date, 'ga:goalCompletionsAll', array(
         'dimensions' => 'ga:goalCompletionLocation',
         'segment' => 'sessions::condition::ga:medium==organic'
     ));
    }catch(\Exception $e){
        return $e->getMessage();
    }
}

public static function GoalCompletionSourceMedium($analytics, $profile, $start_date, $end_date)
{
    try{
        return $analytics
        ->data_ga
        ->get('ga:' . $profile, $end_date, $start_date, 'ga:goalCompletionsAll', array(
         'dimensions' => 'ga:sourceMedium',
         'sort'=>'-ga:goalCompletionsAll'
     ));
    }catch(\Exception $e){
        return $e->getMessage();
    }
}

public static function GoalCompletionSourceMediumOrganic($analytics, $profile, $start_date, $end_date)
{   

    try {
        return $analytics
        ->data_ga
        ->get('ga:' . $profile, $end_date, $start_date, 'ga:goalCompletionsAll', array(
         'dimensions' => 'ga:sourceMedium',
         'segment' => 'sessions::condition::ga:medium==organic'
     ));
        
    }catch(\Exception $e){
        return $e->getMessage();
    }
}

public static function getResultByWeek($analytics, $profile, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:sessions', array(
        'dimensions' => 'ga:week',
        'filters' => 'ga:medium==organic'
    ));
}

public static function getMetricsData($analytics, $profile, $start_date, $end_date)
{
    try{        
        return $analytics
        ->data_ga
        ->get('ga:' . $profile, $end_date, $start_date, 'ga:sessions, ga:users, ga:pageviews', array(
            'dimensions' => 'ga:date',
            'filters' => 'ga:medium==organic'
        ));
    }
    catch(Exception $e)
    {
        $error = json_decode($e->getMessage() , true);
        return $error;
    }
}

public static function getDomainProfileUrl($request_id)
{
    $profileUrl = SemrushUserAccount::where('id', $request_id)->first();
    return $profileUrl->domain_url;
}
public static function getSearchConsoleQuery($client, $profileUrl, $start_date, $end_date)
{
    try
    {
        $query = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
        $query->setStartDate($start_date);
        $query->setEndDate($end_date);
        $query->setDimensions(['query']);
        $query->setSearchType('web');
        $query->setRowLimit(10);

        $service = new \Google_Service_Webmasters($client);
        $site = $service
        ->sites
        ->get($profileUrl);
        $query_data = $service
        ->searchanalytics
        ->query($profileUrl, $query);

        if(isset($query_data->rows)){
            $pagesData = $query_data;
        }else{
            $pagesData = '';
        }

        return $pagesData;

    }
    catch(Exception $e)
    {
        $error = json_decode($e->getMessage() , true);
        return $error;
    }
}

public static function getSearchConsoleDevice($client, $profileUrl, $start_date, $end_date)
{
    try
    {
        $device = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
        $device->setStartDate($start_date);
        $device->setEndDate($end_date);
        $device->setDimensions(['device']);
        $device->setSearchType('web');
        $device->setRowLimit(10);
        $service = new \Google_Service_Webmasters($client);
        $site = $service
        ->sites
        ->get($profileUrl);
        $devices = $service
        ->searchanalytics
        ->query($profileUrl, $device);

        if(isset($devices->rows)){
            $pagesData = $devices;
        }else{
            $pagesData = '';
        }
        return $pagesData;

    }
    catch(Exception $e)
    {

    }
}

public static function getSearchConsolePages($client, $profileUrl, $start_date, $end_date)
{
    try
    {
        $page = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
        $page->setStartDate($start_date);
        $page->setEndDate($end_date);
        $page->setDimensions(['page']);
        $page->setSearchType('web');
        $page->setRowLimit(10);
        $service = new \Google_Service_Webmasters($client);
        $site = $service
        ->sites
        ->get($profileUrl);
        $pages = $service
        ->searchanalytics
        ->query($profileUrl, $page);

        if(isset($pages->rows)){
            $pagesData = $pages;
        }else{
            $pagesData = '';
        }

        return $pagesData;

    }
    catch(Exception $e)
    {

    }
}
public static function getSearchConsoleData($client, $profileUrl, $start_date, $end_date)
{
    try
    {
        $page = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
        $page->setStartDate($start_date);
        $page->setEndDate($end_date);
        $page->setDimensions(['date']);
        $page->setSearchType('web');
            // $page->setRowLimit(500);
        $service = new \Google_Service_Webmasters($client);
        $site = $service
        ->sites
        ->get($profileUrl);
        $pages = $service
        ->searchanalytics
        ->query($profileUrl, $page);

        if(isset($pages->rows)){
            $pagesData = $pages;
        }else{
            $pagesData = '';
        }
        return $pagesData;
    }
    catch(Exception $e)
    {
        return $e->getMessage();
    }
}

public static function getSearchConsoleCountries($client, $profileUrl, $start_date, $end_date)
{
    try
    {
        $country = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
        $country->setStartDate($start_date);
        $country->setEndDate($end_date);
        $country->setDimensions(['country']);
        $country->setSearchType('web');
        $country->setRowLimit(10);
        $service = new \Google_Service_Webmasters($client);
        $site = $service
        ->sites
        ->get($profileUrl);
        $countries = $service
        ->searchanalytics
        ->query($profileUrl, $country);

        if(isset($countries->rows)){
            $pagesData = $countries;
        }else{
            $pagesData = '';
        }
        return $pagesData;
    }
    catch(Exception $e)
    {

    }
}

public static function getSearchConsoleSearchAppearance($client, $profileUrl, $start_date, $end_date)
{
    try
    {

        $searchAppearance = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
        $searchAppearance->setStartDate($start_date);
        $searchAppearance->setEndDate($end_date);
        $searchAppearance->setDimensions(['searchAppearance']);
        $searchAppearance->setSearchType('web');
        $searchAppearance->setRowLimit(10);
        $service = new \Google_Service_Webmasters($client);
        $site = $service
        ->sites
        ->get($profileUrl);
        $searchAppearances = $service
        ->searchanalytics
        ->query($profileUrl, $searchAppearance);
        return $searchAppearances;
    }
    catch(Exception $e)
    {

    }
}

public static function getGoalCompletion($analytics, $analyticsCategoryId, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $analyticsCategoryId, $end_date, $start_date, 'ga:users,ga:sessions', array(
        'dimensions' => 'ga:keyword',
        'max-results' => '10',
        'metrics' => 'ga:sessions,ga:newUsers,ga:bounceRate,ga:pageviewsPerSession,ga:avgSessionDuration,ga:goalConversionRateAll,ga:goalCompletionsAll,ga:goalValueAll',
    ));
}

public static function getCompletionData($analytics, $analyticsCategoryId, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $analyticsCategoryId, $end_date, $start_date, 'ga:users,ga:sessions', array(
        'dimensions' => 'ga:keyword',
        'max-results' => '10',
        'metrics' => 'ga:goalCompletionsAll',
    ));
}

public static function accountInfoById($user_id, $google_account_id)
{
    $data = GoogleAnalyticsUsers::where('user_id', $user_id)->where('id', $google_account_id)->first();
    return $data;
}

public static function googleClientAuth($getAnalytics)
{
    $refresh_token = $getAnalytics->google_refresh_token;
    $service_token['access_token'] = $getAnalytics->google_access_token;
    $service_token['token_type'] = $getAnalytics->token_type;
    $service_token['expires_in'] = $getAnalytics->expires_in;
    $service_token['id_token'] = $getAnalytics->id_token;
    $service_token['created'] = $getAnalytics->service_created;
    $service_token['refresh_token'] = $getAnalytics->google_refresh_token;

    $client = new \Google_Client();
    $client->setApplicationName("AgencyDashboard");
    $client->setAuthConfig(\config('app.FILE_PATH') . \config('app.ANALYTICS_CONFIG'));
    $client->setAccessType('offline');
    $client->addScope(['https://www.googleapis.com/auth/webmasters', 'https://www.googleapis.com/auth/webmasters.readonly', 'email', 'profile', 'https://www.googleapis.com/auth/analytics.readonly']);
    $client->setAccessToken($service_token);
    $client->setApprovalPrompt('force');
    $client->setIncludeGrantedScopes(true);

    return $client;
}

public static function googleGmbClientAuth($getAnalytics)
{

    $refresh_token = $getAnalytics->google_refresh_token;
    $service_token['access_token'] = $getAnalytics->google_access_token;
    $service_token['token_type'] = $getAnalytics->token_type;
    $service_token['expires_in'] = $getAnalytics->expires_in;
    $service_token['id_token'] = $getAnalytics->id_token;
    $service_token['created'] = $getAnalytics->service_created;
    $service_token['refresh_token'] = $getAnalytics->google_refresh_token;

    $client = new \Google_Client();
    $client->setApplicationName("AgencyDashboard");
    $client->setAuthConfig(\config('app.FILE_PATH') . \config('app.ANALYTICS_CONFIG'));
    $client->setAccessType('offline');
    $client->addScope(["https://www.googleapis.com/auth/business.manage", 'email', 'profile']);
    $client->setAccessToken($service_token);
    $client->setApprovalPrompt('force');
    $client->setIncludeGrantedScopes(true);
    return $client;
}

public static function google_refresh_token($client, $refresh_token, $getAnalytics_id)
{
    $client->refreshToken($refresh_token);
    $newtoken = $client->getAccessToken();

    GoogleAnalyticsUsers::where('id', $getAnalytics_id)->update(['google_access_token' => $newtoken['access_token'], 'token_type' => $newtoken['token_type'], 'expires_in' => $newtoken['expires_in'], 'google_refresh_token' => $newtoken['refresh_token'], 'service_created' => $newtoken['created'], 'id_token' => $newtoken['id_token'], ]);
    Session::put('token', $client->getAccessToken());
}

public static function getGoogleAccountsList_update($analytics, $campaignId, $analyticsId, $user_id, $provider)
{
    $error = $response = array();
    try
    {
        $getAccounts = $analytics
        ->management_accounts
        ->listManagementAccounts();

        if (count($getAccounts->getItems()) > 0)
        {
            $items = $getAccounts->getItems();

            foreach ($items as $item)
            {
                $account_id = $item->getId();
                $account_name = $item->name;
                $if_exists = GoogleAccountViewData::where('category_id', $account_id)->where('google_account_id', $analyticsId)->where('user_id', $user_id)->first();

                if ($if_exists)
                {
                    GoogleAccountViewData::where('id', $if_exists->id)
                    ->update(['category_name' => $account_name]);

                }
                else
                {
                    $accountData = GoogleAccountViewData::create(['user_id' => $user_id, 'request_id' => $campaignId, 'google_account_id' => $analyticsId, 'category_name' => $account_name, 'category_id' => $account_id, 'parent_id' => 0, 'created_at' => now() , 'updated_at' => now() ]);
                    $lastId = $accountData->id;
                }
                if ($provider !== 'search_console')
                {
                    $properties = $analytics
                    ->management_webproperties
                    ->listManagementWebproperties($account_id);

                    if (count($properties->getItems()) > 0)
                    {
                        $propertyAll = $properties->getItems();
                        foreach ($propertyAll as $singleProperty)
                        {
                            $property_id = $singleProperty->getId();
                            $property_name = $singleProperty->name;
                            $if_property = GoogleAccountViewData::where('category_id', $property_id)->where('google_account_id', $analyticsId)->where('user_id', $user_id)->where('parent_id',$lastId)->first();

                            if ($if_property)
                            {
                                GoogleAccountViewData::where('id', $if_property->id)
                                ->update(['category_name' => $property_name]);
                            }
                            else
                            {
                                $propertyData = GoogleAccountViewData::create(['user_id' => $user_id, 'request_id' => $campaignId, 'google_account_id' => $analyticsId, 'category_name' => $property_name, 'category_id' => $property_id, 'parent_id' => $lastId, 'created_at' => now() , 'updated_at' => now() ]);
                                $property_last_id = $propertyData->id;
                            }
                            $profiles = $analytics
                            ->management_profiles
                            ->listManagementProfiles($account_id, $property_id);

                            if (count($profiles->getItems()) > 0)
                            {
                                $profiles_all = $profiles->getItems();
                                foreach ($profiles_all as $profiles)
                                {
                                    $profiles_id = $profiles->getId();
                                    $profiles_name = $profiles->name;

                                    $if_view = GoogleAccountViewData::where('category_id', $profiles_id)->where('google_account_id', $analyticsId)->where('user_id', $user_id)->where('parent_id', $property_last_id)->first();
                                    if ($if_view)
                                    {
                                        GoogleAccountViewData::where('id', $if_view->id)
                                        ->update(['category_name' => $profiles_name]);
                                    }
                                    else
                                    {
                                        $propertyData = GoogleAccountViewData::create(['user_id' => $user_id, 'request_id' => $campaignId, 'google_account_id' => $analyticsId, 'category_name' => $profiles_name, 'category_id' => $profiles_id, 'parent_id' => $property_last_id, 'created_at' => now() , 'updated_at' => now() ]);
                                    }

                                }
                            }
                        }
                    }
                }
            }
            return true;

        }
    }
    catch(Exception $e)
    {
        return false;
        $response['status'] = 0;
        $response['message'] = $e->getMessage();
        return response()
        ->json($response);
    }

}

public static function close_method()
{
    echo "<script>";
    echo "window.close();";
    echo "</script>";
}

public static function log_analytics_data($campaignId)
{
    $error = array();
   try{
        $semrush_data = SemrushUserAccount::where('google_analytics_id', '!=', NULL)->where('id', $campaignId)->first();
        if (!empty($semrush_data))
        {

            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime("-2 years", strtotime(date('Y-m-d'))));

            $day_diff = strtotime($end_date) - strtotime($start_date);
            $count_days = floor($day_diff / (60 * 60 * 24));

            $start_data = date('Y-m-d', strtotime($end_date . ' ' . $count_days . ' days'));

            $prev_start_date = date('Y-m-d', strtotime("-1 day", strtotime($end_date)));
            $prev_end_date = date('Y-m-d', strtotime("-2 years", strtotime($prev_start_date)));

            $current_period = date('d-m-Y', strtotime($end_date)) . ' to ' . date('d-m-Y', strtotime($start_date));
            $previous_period = date('d-m-Y', strtotime(date('Y-m-d', strtotime($prev_end_date)))) . ' to ' . date('d-m-Y', strtotime($prev_start_date));


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

            $getAnalytics = GoogleAnalyticsUsers::where('id', $semrush_data->google_account_id)->first();

            $user_id = $getAnalytics->user_id;

            if (!empty($getAnalytics))
            {
                $status = 1;
                $client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);

                $refresh_token = $getAnalytics->google_refresh_token;

                /*if refresh token expires*/
                if ($client->isAccessTokenExpired())
                {
                    GoogleAnalyticsUsers::google_refresh_token($client, $refresh_token, $getAnalytics->id);
                }

                $getAnalyticsId = SemrushUserAccount::where('id', $campaignId)->where('user_id', $user_id)->first();

                if (isset($getAnalyticsId->google_analytics_account))
                {
                    $analyticsCategoryId = $getAnalyticsId
                    ->google_analytics_account->category_id;

                    $analytics = new \Google_Service_Analytics($client);

                     $profile = GoogleAnalyticsUsers::AnalyticsCategoryId($semrush_data->google_profile_id);
                     $property_id = GoogleAnalyticsUsers::AnalyticsCategoryId($semrush_data->google_property_id); 

                    $current_data = GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile, $start_date, $end_date);

                    $outputRes = array_column($current_data->rows, 0);

                    $previous_data = GoogleAnalyticsUsers::getResultForDateRange($analytics, $profile, $prev_start_date, $prev_end_date);

                    $outputRes_prev = array_column($previous_data->rows, 0);

                    $count_session = array_column($current_data->rows, 1);

                    $from_dates = array_map(function ($val)
                    {
                        return date("d M, Y", strtotime($val));
                    }
                    , $outputRes);
                    $from_dates_format = array_map(function ($val)
                    {
                        return date("Y-m-d", strtotime($val));
                    }
                    , $outputRes);

                    /*prev data*/
                    $from_dates_prev = array_map(function ($val)
                    {
                        return date("d M, Y", strtotime($val));
                    }
                    , $outputRes_prev);
                    $from_dates_prev_format = array_map(function ($val)
                    {
                        return date("Y-m-d", strtotime($val));
                    }
                    , $outputRes_prev);
                    $combine_session = array_column($previous_data->rows, 1);

                    $final_array = array_merge($combine_session, $count_session);
                    $dates_final_array = array_merge($from_dates_prev, $from_dates);
                    $dates_format = array_merge($from_dates_prev_format, $from_dates_format);

                    $array = array(
                        'final_array' => $final_array,
                        'from_dates' => $dates_final_array,
                        'dates_format' => $dates_format
                    );

                    if (!file_exists(\config('app.FILE_PATH') . 'public/analytics/' . $campaignId))
                    {
                        mkdir(\config('app.FILE_PATH') . 'public/analytics/' . $campaignId, 0777, true);

                    }
                    file_put_contents(\config('app.FILE_PATH') . 'public/analytics/' . $campaignId . '/graph.json', print_r(json_encode($array, true) , true));

                    if (!empty($getAnalyticsId->google_profile_id))
                    {
                        /*current data*/
                        $start_date_new = date('Y-m-d', strtotime('-1 day', strtotime($end_date)));
                        $currentData = GoogleAnalyticsUsers::getMetricsData($analytics, $profile, $start_date, $end_date);

                        $outputRes_metrics = array_column($currentData->rows, 0);
                        $from_dates_metrics = array_map(function ($val)
                        {
                            return date("d M, Y", strtotime($val));
                        }
                        , $outputRes_metrics);
                        $from_dates_metrics_format = array_map(function ($val)
                        {
                            return date("Y-m-d", strtotime($val));
                        }
                        , $outputRes_metrics);
                        $outputRes_sessions = array_column($currentData->rows, 1);
                        $current_sessions_data = array_map(function ($val)
                        {
                            return $val;
                        }
                        , $outputRes_sessions);

                        $outputRes_users = array_column($currentData->rows, 2);
                        $current_users_data = array_map(function ($val)
                        {
                            return $val;
                        }
                        , $outputRes_users);

                        $outputRes_pageviews = array_column($currentData->rows, 3);
                        $current_pageviews_data = array_map(function ($val)
                        {
                            return $val;
                        }
                        , $outputRes_pageviews);

                        /*Previous data*/
                        $previousData = GoogleAnalyticsUsers::getMetricsData($analytics, $profile, $start_date_new, $start_data);

                        $outputRes_metrics_prev = array_column($previousData->rows, 0);
                        $from_dates_metrics_prev = array_map(function ($val)
                        {
                            return date("d M, Y", strtotime($val));
                        }
                        , $outputRes_metrics_prev);
                        $from_dates_metrics_prev_format = array_map(function ($val)
                        {
                            return date("Y-m-d", strtotime($val));
                        }
                        , $outputRes_metrics_prev);
                        $outputRes_sessions_prev = array_column($previousData->rows, 1);
                        $prev_sessions_data = array_map(function ($val)
                        {
                            return $val;
                        }
                        , $outputRes_sessions_prev);

                        $outputRes_users_prev = array_column($previousData->rows, 2);
                        $prev_users_data = array_map(function ($val)
                        {
                            return $val;
                        }
                        , $outputRes_users_prev);

                        $outputRes_pageviews_prev = array_column($previousData->rows, 3);
                        $prev_pageviews_data = array_map(function ($val)
                        {
                            return $val;
                        }
                        , $outputRes_pageviews_prev);

                        /*merged data for comparison*/

                        $metrics_dates = array_merge($from_dates_metrics_prev,$from_dates_metrics);
                        $metrics_sessions = array_merge($prev_sessions_data,$current_sessions_data);
                        $metrics_users = array_merge($prev_users_data,$current_users_data);
                        $metrics_pageviews = array_merge($prev_pageviews_data,$current_pageviews_data);
                        $dates_format = array_merge($from_dates_metrics_prev_format, $from_dates_metrics_format);

                        $final_array = array(
                            'metrics_dates' => $metrics_dates,
                            'metrics_sessions' => $metrics_sessions,
                            'metrics_users' => $metrics_users,
                            'metrics_pageviews' => $metrics_pageviews,
                            'dates_format' => $dates_format
                        );

                        if (!file_exists(\config('app.FILE_PATH') . 'public/analytics/' . $campaignId))
                        {
                            mkdir(\config('app.FILE_PATH') . 'public/analytics/' . $campaignId, 0777, true);
                        }
                        file_put_contents(\config('app.FILE_PATH') . 'public/analytics/' . $campaignId . '/metrics.json', print_r(json_encode($final_array, true) , true));
                    }


                    /*goalcompletion data*/
                   $goals = $analytics->management_goals->listManagementGoals($analyticsCategoryId, $property_id,$profile);

                    if($goals->totalResults > 0){
                        if (!file_exists(\config('app.FILE_PATH') . 'public/goalcompletion/' . $campaignId))
                        {
                            mkdir(\config('app.FILE_PATH') . 'public/goalcompletion/' . $campaignId, 0777, true);
                        }
                        Self::goal_completion_graph($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
                        Self::goal_completion_statistics($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
                        Self::location_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
                        Self::sourcemedium_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
                        Self::location_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
                        Self::location_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
                        Self::location_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
                        Self::location_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
                        Self::location_two_year($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
                        Self::sourcemedium_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
                        Self::sourcemedium_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
                        Self::sourcemedium_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
                        Self::sourcemedium_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
                        Self::sourcemedium_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
                    }

                    SemrushUserAccount::where('id',$campaignId)->update([
                        'goal_completion_count' => $goals->getTotalResults()
                    ]);

                    /* log ecommerce data if enabled*/
                    if($getAnalyticsId->ecommerce_goals == 1){
                        if (!file_exists(\config('app.FILE_PATH') . 'public/ecommerce_goals/' . $campaignId))
                        {
                            mkdir(\config('app.FILE_PATH') . 'public/ecommerce_goals/' . $campaignId, 0777, true);
                        }

                        Self::ecommerce_goal_graph($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
                        Self::ecommerce_goal_statistics($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId);
                        Self::ecommerce_product_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId);
                        Self::ecommerce_product_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId);
                        Self::ecommerce_product_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId);
                        Self::ecommerce_product_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId);
                        Self::ecommerce_product_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId);
                        Self::ecommerce_product_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId);
                    }elseif($getAnalyticsId->ecommerce_goals == 0){
                        if (file_exists(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId)) {
                            SemrushUserAccount::remove_directory(\config('app.FILE_PATH').'public/ecommerce_goals/'.$campaignId);
                        }
                    }
                }
                else
                {
                    $status = 0;
                }
            }

        }
    }
    catch(\Exception $e)
    {
        $error = json_decode($e->getMessage() , true);
        $result['status'] = 0;
        $result['message'] = $error;
        return $result;
    }

}


public static function calculate_percentage($a1, $a2){
    $value = $a1 -$a2;
    if($value != 0){
        if($a2 !=0){
           $percentage = ($value/$a2)*100;
       }else{
           $percentage = 100;
       }

       return number_format($percentage,2);
   }else{
    return 0;
}
}


public static function goal_completion_graph($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId){
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

public static function goal_completion_statistics($analytics, $profile,$start_date,$end_date,$prev_start_date,$prev_end_date,$campaignId){
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


public static function location_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId){
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


public static function location_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId){
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

public static function location_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId){
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

public static function location_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId){
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

public static function location_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId){
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

public static function location_two_year($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId){
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

public static function sourcemedium_one_month($analytics, $profile,$one_month,$today,$prev_start_one,$prev_end_one,$campaignId){
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


public static function sourcemedium_three_month($analytics, $profile,$three_month,$today,$prev_start_three,$prev_end_three,$campaignId){
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

public static function sourcemedium_six_month($analytics, $profile,$six_month,$today,$prev_start_six,$prev_end_six,$campaignId){
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

public static function sourcemedium_nine_month($analytics, $profile,$nine_month,$today,$prev_start_nine,$prev_end_nine,$campaignId){
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

public static function sourcemedium_year($analytics, $profile,$one_year,$today,$prev_start_year,$prev_end_year,$campaignId){
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

public static function sourcemedium_twoyear($analytics, $profile,$two_year,$today,$prev_start_two,$prev_end_two,$campaignId){
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
}

public static function getPropertyId($campaignId)
{
    $data = SemrushUserAccount::where('id', $campaignId)->first();

    $google_property_id = $data->google_property_id;

    $profile_account_data = GoogleAccountViewData::where('id', $google_property_id)->first();
    if (!empty($profile_account_data))
    {
        $category_id = $profile_account_data->category_id;
    }
    else
    {
        $category_id = '';
    }
    return $category_id;
}

public static function refresh_getGoogleAccountsList($analytics, $campaignId, $analyticsId, $user_id, $provider)
{
    $error = $result = array();
    try 
    {
        $getAccounts = $analytics
        ->management_accounts
        ->listManagementAccounts();

    }
    catch(Exception $e)
    {
        $error = json_decode($e->getMessage() , true);
        $result['status'] = 0;
        $result['message'] = $error['error'];
        return $result;
    }

    if (empty($error['error']) || $error['error'] == 0)
    {
        if (count($getAccounts->getItems()) > 0)
        {
            $items = $getAccounts->getItems();

            $icount = 1;
            $icountMin = 1;
            foreach ($items as $item)
            {
                $account_id = $item->getId();
                $account_name = $item->name;

                $if_exists = GoogleAccountViewData::where('category_id', $account_id)->where('google_account_id', $analyticsId)->where('user_id', $user_id)->first();

                if ($if_exists)
                {
                 GoogleAccountViewData::where('id', $if_exists->id)
                 ->update(['category_name' => $account_name]);
                 $lastId  = $if_exists->id;
             }
             else
             {
                $accountData = GoogleAccountViewData::create(['user_id' => $user_id, 'request_id' => $campaignId, 'google_account_id' => $analyticsId, 'category_name' => $account_name, 'category_id' => $account_id, 'parent_id' => 0, 'created_at' => now() , 'updated_at' => now() ]);
                $lastId = $accountData->id;
            }


            if ($provider !== 'search_console')
            {

                $properties = $analytics
                ->management_webproperties
                ->listManagementWebproperties($account_id);
                $icount++;
                $icountMin++;
                if (count($properties->getItems()) > 0)
                {
                    $propertyAll = $properties->getItems();

                    foreach ($propertyAll as $singleProperty)
                    {
                        $property_id = $singleProperty->getId();
                        $property_name = $singleProperty->name;

                        $if_property = GoogleAccountViewData::where('category_id', $property_id)->where('google_account_id', $analyticsId)->where('user_id', $user_id)->where('parent_id',$lastId)->first();

                        if ($if_property)
                        {
                         GoogleAccountViewData::where('id', $if_property->id)
                         ->update(['category_name' => $property_name]);
                         $property_last_id = $if_property->id;
                     }
                     else
                     {
                        $propertyData = GoogleAccountViewData::create(['user_id' => $user_id, 'request_id' => $campaignId, 'google_account_id' => $analyticsId, 'category_name' => $property_name, 'category_id' => $property_id, 'parent_id' => $lastId, 'created_at' => now() , 'updated_at' => now() ]);
                        $property_last_id = $propertyData->id;
                    }





                    $profiles = $analytics
                    ->management_profiles
                    ->listManagementProfiles($account_id, $property_id);

                    $icount++;
                    $icountMin++;

                    if (count($profiles->getItems()) > 0)
                    {
                        $profiles_all = $profiles->getItems();
                        foreach ($profiles_all as $profiles)
                        {
                            $profiles_id = $profiles->getId();
                            $profiles_name = $profiles->name;
                            $if_view = GoogleAccountViewData::where('category_id', $profiles_id)->where('google_account_id', $analyticsId)->where('user_id', $user_id)->where('parent_id', $property_last_id)->first();
                            if ($if_view)
                            {
                                GoogleAccountViewData::where('id', $if_view->id)
                                ->update(['category_name' => $profiles_name]);
                            }
                            else
                            {
                                GoogleAccountViewData::create(['user_id' => $user_id, 'request_id' => $campaignId, 'google_account_id' => $analyticsId, 'category_name' => $profiles_name, 'category_id' => $profiles_id, 'parent_id' => $property_last_id, 'created_at' => now() , 'updated_at' => now() ]);
                            }

                        }
                    }
                }

            }
        }

        sleep(1);
    }
}
$result['status'] = 1;
}
return $result;
}

public static function checkAnalyticsData($campaignID,$user_id,$google_email,$analytics_account_id,$analytics_property_id,$analytics_view_id){
    $getAnalytics = GoogleAnalyticsUsers::where('id', $google_email)->first();

    if($getAnalytics){
        $profile_account_data = GoogleAccountViewData::where('id', $analytics_view_id)->first();
        if(!empty($profile_account_data)){
            $profile = $profile_account_data->category_id;

            $client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);
            $refresh_token = $getAnalytics->google_refresh_token;

            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime("-1 week", strtotime(date('Y-m-d'))));

            $analytics = new \Google_Service_Analytics($client);

            $error = array();
            try {
                $current_data_check = GoogleAnalyticsUsers::getResultForDateRange($analytics,$profile,$start_date,$end_date); 
                $result['status'] = 1;
                $result['message'] = $current_data_check;
            } catch(\Exception $j) {
                $error = json_decode($j->getMessage(), true);
                $result['status'] = 0;
                $result['message'] = $error;
            }
        }else{
            $result['status'] = 2;
            $result['message'] = 'View id doesnot exists.';
        }

        return $result;
    }

}


public static function checkAnalyticsData_cron($analytics,$campaignID,$user_id,$google_email,$analytics_account_id,$analytics_property_id,$analytics_view_id){
        // $getAnalytics = GoogleAnalyticsUsers::where('id', $google_email)->first();

        //if($getAnalytics){
    $profile_account_data = GoogleAccountViewData::where('id', $analytics_view_id)->first();
    if(!empty($profile_account_data)){
        $profile = $profile_account_data->category_id;

                // $client = GoogleAnalyticsUsers::googleClientAuth($getAnalytics);
                // $refresh_token = $getAnalytics->google_refresh_token;

        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("-1 week", strtotime(date('Y-m-d'))));

                // $analytics = new \Google_Service_Analytics($client);

        $error = array();
        try {
            try{
                $current_data_check = GoogleAnalyticsUsers::getResultForDateRange($analytics,$profile,$start_date,$end_date); 
                $result['status'] = 1;
                $result['message'] = $current_data_check;
            } catch(\Exception $j) {
                $error = json_decode($j->getMessage(), true);
                $result['status'] = 2;
                $result['message'] = $error;
            }

        } catch(\Exception $j) {
            $error = json_decode($j->getMessage(), true);
            $result['status'] = 0;
            $result['message'] = $error;
        }
    }else{
        $result['status'] = 2;
        $result['message'] = 'View id doesnot exists.';
    }

    return $result;
       // }

}

    public static function AnalyticsCategoryId($profile_id){
        $profile_account_data = GoogleAccountViewData::where('id', $profile_id)->first();
        return  $profile_account_data->category_id;
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


    public static function user_ecommerce_goals($analytics, $profile, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:transactionsPerSession', array(
        'dimensions' => 'ga:date'
    ));
}


public static function organic_ecommerce_goals($analytics, $profile, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:transactionsPerSession', array(
        'dimensions' => 'ga:date',
        'filters' => 'ga:medium==organic'
    ));
}


public static function user_ecommerce_stats($analytics, $profile, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:transactionsPerSession,ga:transactions,ga:transactionRevenue,ga:revenuePerTransaction', array(
        'dimensions' => 'ga:date'
    ));
}

public static function organic_ecommerce_stats($analytics, $profile, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:transactionsPerSession,ga:transactions,ga:transactionRevenue,ga:revenuePerTransaction', array(
        'dimensions' => 'ga:date',
        'filters' => 'ga:medium==organic'
    ));
}


public static function users_product($analytics, $profile, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:itemRevenue', array(
        'dimensions' => 'ga:productName',
        'sort' => '-ga:itemRevenue'
    ));
}

public static function organic_product($analytics, $profile, $start_date, $end_date)
{
    return $analytics
    ->data_ga
    ->get('ga:' . $profile, $end_date, $start_date, 'ga:itemRevenue', array(
        'dimensions' => 'ga:productName',
        'sort' => '-ga:itemRevenue',
        'filters' => 'ga:medium==organic'
    ));
}
    public static function getFormattedValue($value){
        if($value >= 10000 && $value < 100000){
            return number_format((float)($value/1000), 2, '.', '').'K'; 
        }elseif($value >= 100000){
            return number_format((float) ($value/100000), 2, '.', '').'L';
        }else{
            return number_format((float) $value, 2, '.', '');
        }
    }

}