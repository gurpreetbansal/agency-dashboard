<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;


use App\SemrushUserAccount;
use App\GoogleAnalyticsUsers;
use App\AdwordsCampaignDetail;
use App\AdwordsKeywordDetail;
use App\AdwordsAdTextDetail;
use App\AdwordsAdGroupDetail;
use App\AdwordsPlaceHolderDetail;
use App\GoogleUpdate;
use App\Error;


/*Google Ads*/

use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\Reporting\v201809\DownloadFormat;
use Google\AdsApi\AdWords\ReportSettingsBuilder;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\SortOrder;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\Query\v201809\ServiceQueryBuilder;
use Google\AdsApi\AdWords\v201809\cm\CampaignService;
use Google\AdsApi\AdWords\v201809\cm\Page;
use Google\AdsApi\AdWords\Query\v201809\ReportQueryBuilder;
use Google\AdsApi\AdWords\Reporting\v201809\ReportDefinitionDateRangeType;
use Google\AdsApi\AdWords\Reporting\v201809\ReportDownloader;
use Google\AdsApi\AdWords\v201809\cm\ReportDefinitionReportType;



class GoogleAdsCustomer extends Model {

// public function __construct()
// {

//     $this->client_id = \config('app.ads_client_id');
//     $this->client_secret = \config('app.ads_client_secret');
//     $this->developerToken = \config('app.ads_developerToken');
// }

    protected $table = 'google_ads_customers';

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
    protected $fillable = ['user_id','request_id', 'google_ads_id', 'name', 'login_customer_id', 'currencyCode', 'customer_id', 'can_manage_clients','status'];


    public static function log_adwords_refresh($campaign_id){
        
        $value = SemrushUserAccount::
        with(array('google_adwords_account'=>function($query){
            $query->select('id','customer_id');
        }))
        ->where('status','0')
        ->where('id',$campaign_id)
        ->first();

       
        if(!empty($value->google_adwords_account) || ($value->google_adwords_account != null)){

            $ads_customer_id = $value->google_adwords_account->customer_id;

            $googleUserDetails = GoogleAnalyticsUsers::findorfail($value->google_ads_id);
            $refreshToken = $googleUserDetails->google_refresh_token;
            $adwordsSession  = self::google_ads_auth($ads_customer_id,$refreshToken);


            $today = date('Y-m-d');
            $start_date = date('Ymd',strtotime('-2 year'));
            $end_date = date('Ymd',strtotime('-1 day'));


            $fileName = $ads_customer_id.'_campaigns.csv';
            $keywords_fileName = $ads_customer_id.'_keywords.csv';
            $ads_fileName = $ads_customer_id.'_ads.csv';
            $adgroup_fileName = $ads_customer_id.'_adgroup.csv';
            $place_file = $ads_customer_id.'_placeholder.csv';

            $lastUpdate = GoogleUpdate::where('request_id',$campaign_id)->first();
            

            $duration = null;
            if($lastUpdate->adwords <> null){

                $date1 = date('Y-m-d', strtotime($lastUpdate->adwords));
                $date2 = date('Y-m-d');

                $ts1 = strtotime($date1);
                $ts2 = strtotime($date2);

                $year1 = date('Y', $ts1);
                $year2 = date('Y', $ts2);

                $month1 = date('m', $ts1);
                $month2 = date('m', $ts2);

                $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

                $duration = $diff + 1;
            }
            
            $checkLimit = self::checkdailyLimit($adwordsSession,$fileName,$ads_customer_id,$today,$campaign_id);
            /*storing Data in db using csv for today*/
           
            if($checkLimit['status'] == 'success'){

                self::campaign_reports_query_data($adwordsSession,$fileName,$ads_customer_id,$today,$campaign_id,$duration);
                self::keywords_reports_query_data($adwordsSession,$start_date,$end_date,$keywords_fileName,$ads_customer_id,$today,$campaign_id,$duration);
                self::ads_reports_query_data($adwordsSession,$start_date,$end_date,$ads_fileName,$ads_customer_id,$today,$campaign_id,$duration);
                self::adsGroup_reports_query_data($adwordsSession,$start_date,$end_date,$adgroup_fileName,$ads_customer_id,$today,$campaign_id,$duration);
                self::ads_placeholder_reports_query_data($adwordsSession,$start_date,$end_date,$place_file,$ads_customer_id,$today,$campaign_id,$duration);
                
                GoogleUpdate::updateTiming($campaign_id,'adwords','adwords_type','2');
            
            }
            
            return $checkLimit;
        }
    }

    public static function log_adwords($campaign_id){

        $value = SemrushUserAccount::
        with(array('google_adwords_account'=>function($query){
            $query->select('id','customer_id');
        }))
        ->where('status','0')
        ->where('id',$campaign_id)
        ->first();

       
        if(!empty($value->google_adwords_account) || ($value->google_adwords_account != null)){

            $ads_customer_id = $value->google_adwords_account->customer_id;

            $googleUserDetails = GoogleAnalyticsUsers::findorfail($value->google_ads_id);
            $refreshToken = $googleUserDetails->google_refresh_token;
            $adwordsSession  = self::google_ads_auth($ads_customer_id,$refreshToken);


            $today = date('Y-m-d');
            $start_date = date('Ymd',strtotime('-2 year'));
            $end_date = date('Ymd',strtotime('-1 day'));


            $fileName = $ads_customer_id.'_campaigns.csv';
            $keywords_fileName = $ads_customer_id.'_keywords.csv';
            $ads_fileName = $ads_customer_id.'_ads.csv';
            $adgroup_fileName = $ads_customer_id.'_adgroup.csv';
            $place_file = $ads_customer_id.'_placeholder.csv';

            $duration = 3;


            $checkLimit = self::checkdailyLimit($adwordsSession,$fileName,$ads_customer_id,$today,$campaign_id);
            /*storing Data in db using csv for today*/
           
            if($checkLimit['status'] == 'success'){

                self::campaign_reports_query_data($adwordsSession,$fileName,$ads_customer_id,$today,$campaign_id,$duration);
                self::keywords_reports_query_data($adwordsSession,$start_date,$end_date,$keywords_fileName,$ads_customer_id,$today,$campaign_id,$duration);
                self::ads_reports_query_data($adwordsSession,$start_date,$end_date,$ads_fileName,$ads_customer_id,$today,$campaign_id,$duration);
                self::adsGroup_reports_query_data($adwordsSession,$start_date,$end_date,$adgroup_fileName,$ads_customer_id,$today,$campaign_id,$duration);
                self::ads_placeholder_reports_query_data($adwordsSession,$start_date,$end_date,$place_file,$ads_customer_id,$today,$campaign_id,$duration);

                GoogleUpdate::updateTiming($campaign_id,'adwords','adwords_type','2');
            
            }
            
            return $checkLimit;
        }
    }

    public static function log_adwords_data($campaign_id){

        $value = SemrushUserAccount::
        with(array('google_adwords_account'=>function($query){
            $query->select('id','customer_id');
        }))
        ->where('status','0')
        ->where('id',$campaign_id)
        ->first();

       
        if(!empty($value->google_adwords_account) || ($value->google_adwords_account != null)){

            $ads_customer_id = $value->google_adwords_account->customer_id;

            $googleUserDetails = GoogleAnalyticsUsers::findorfail($value->google_ads_id);
            $refreshToken = $googleUserDetails->google_refresh_token;
            $adwordsSession  = self::google_ads_auth($ads_customer_id,$refreshToken);


            $today = date('Y-m-d');
            $start_date = date('Ymd',strtotime('-2 year'));
            $end_date = date('Ymd',strtotime('-1 day'));


            $fileName = $ads_customer_id.'_campaigns.csv';
            $keywords_fileName = $ads_customer_id.'_keywords.csv';
            $ads_fileName = $ads_customer_id.'_ads.csv';
            $adgroup_fileName = $ads_customer_id.'_adgroup.csv';
            $place_file = $ads_customer_id.'_placeholder.csv';


            $checkLimit = self::checkdailyLimit($adwordsSession,$fileName,$ads_customer_id,$today,$campaign_id);
            /*storing Data in db using csv for today*/
           
            if($checkLimit['status'] == 'success'){

                self::campaign_reports_query_data($adwordsSession,$fileName,$ads_customer_id,$today,$campaign_id);
                sleep(1);
                self::keywords_reports_query_data($adwordsSession,$start_date,$end_date,$keywords_fileName,$ads_customer_id,$today,$campaign_id);
                sleep(1);
                self::ads_reports_query_data($adwordsSession,$start_date,$end_date,$ads_fileName,$ads_customer_id,$today,$campaign_id);
                sleep(1);
                self::adsGroup_reports_query_data($adwordsSession,$start_date,$end_date,$adgroup_fileName,$ads_customer_id,$today,$campaign_id);
                sleep(1);
                self::ads_placeholder_reports_query_data($adwordsSession,$start_date,$end_date,$place_file,$ads_customer_id,$today,$campaign_id);

                GoogleUpdate::updateTiming($campaign_id,'adwords','adwords_type','2');
            
            }
            
            return $checkLimit;
        }
    }


    public static function google_ads_auth($account_id,$refreshToken){

        $oAuth2Credential = (new OAuth2TokenBuilder())
        ->withClientId(\config('app.ads_client_id'))
        ->withClientSecret(\config('app.ads_client_secret'))
        ->withRefreshToken($refreshToken)
        ->build();

        // $googleAdsClient = (new GoogleAdsClientBuilder())
        //             ->withOAuth2Credential($oAuth2Credential)
        //             ->withDeveloperToken(\config('app.ads_developerToken'))
        //             ->build();
        $googleAdsClient = (new AdWordsSessionBuilder())
        ->withDeveloperToken(\config('app.ads_developerToken'))
        ->withOAuth2Credential($oAuth2Credential)
        ->withClientCustomerId($account_id)
        ->build();

        return $googleAdsClient;
    }

    public static function checkdailyLimit($session,$fileName,$account_id,$today,$project_id){

        $adWordsServices = new AdWordsServices();
        $start_date = date('Ymd',strtotime('-1 day'));
        $end_date = date('Ymd',strtotime('-1 week'));
       
        try{
            $query = (new ReportQueryBuilder())
            ->select(['CampaignId','CampaignName','Impressions','Clicks','Cost','Conversions','Date','Device','AdNetworkType1','Slot','AccountCurrencyCode','CostPerConversion','Ctr','AverageCpc','ConversionRate','ConversionValue','AverageCost','AverageCpm','AdNetworkType2'])
            ->from(ReportDefinitionReportType::CAMPAIGN_PERFORMANCE_REPORT)
            ->duringDateRange("$end_date,$start_date")
            ->build();
            

            $reportDownloader = new ReportDownloader($session);

            $reportDownloadResult = $reportDownloader->downloadReportWithAwql(sprintf('%s', $query), 'CSV');
            $reportDownloadResult->saveToFile(\env('FILE_PATH')."public/reports/".$fileName);

            $handle = fopen(\env('FILE_PATH').'public/reports/'.$fileName, "r");

            $responce = [
                'status'=>'success',
            ];

        }catch (Exception $e) {
            $error = $e->getErrors();

            if($error[0]->getErrorString() == 'AuthenticationError.OAUTH_TOKEN_INVALID'){
                $responce = [
                    'status'=>'error',
                    'message'=>'AuthenticationError: Please reconnect your account',
                ];
            }else{
                $responce = [
                    'status'=>'error',
                    'message'=>'RATE EXCEEDED: Basic Access Daily Reporting Quota',
                ];
            }
        }
        return $responce;
    }    

    public static function campaign_reports_query_data($session,$fileName,$account_id,$today,$project_id,$duration=null){    

        $final = $array =  $campaigns = $device = $networks =  $adSlots = $networksData = $deviceData = $adSlotsData =  $file_content =array();

        
        if($duration <> null){
            $dates = self::get_data_dates($duration);
        }else{
            $dates = self::get_data_dates(48);
        }
       
        
        $adWordsServices = new AdWordsServices();
        /*$weekImp = array();*/
        $weekImp['imporession'] = array();

        $urlValues = env('FILE_PATH')."public/adwords/".$project_id.'/graphs/overview.json'; 
        if(file_exists($urlValues)){
            $dataValues = file_get_contents($urlValues);
            $values = json_decode($dataValues,true);

            $impressionGraph = $values; 
            $impressionGraphData = $values; 
        }

        $urlKeywords = env('FILE_PATH')."public/adwords/".$project_id.'/networks/list.json'; 
        if(file_exists($urlKeywords)){
            $dataValues = file_get_contents($urlKeywords);
            $networks = json_decode($dataValues,true);
        }

        $urldevices = env('FILE_PATH')."public/adwords/".$project_id.'/devices/list.json'; 
        if(file_exists($urldevices)){
            $dataValues = file_get_contents($urldevices);
            $device = json_decode($dataValues,true);
        }

        $urlcampaigns = env('FILE_PATH')."public/adwords/".$project_id.'/campaign/list.json'; 
        if(file_exists($urlcampaigns)){
            $dataValues = file_get_contents($urlcampaigns);
            $campaigns = json_decode($dataValues,true);
        }

        $urladSlots = env('FILE_PATH')."public/adwords/".$project_id.'/adSlots/list.json'; 
        if(file_exists($urladSlots)){
            $dataValues = file_get_contents($urladSlots);
            $adSlots = json_decode($dataValues,true);
        }

        try{

            for($i=0;$i<=count($dates)-1;$i++){
                
                $start_date = $dates[$i]['start_date']; 
                $end_date = $dates[$i]['end_date'];
                // $end_date = $dates[count($dates)-1]['end_date'];
                
                $query = (new ReportQueryBuilder())
                ->select(['CampaignId','CampaignName','Impressions','Clicks','Cost','Conversions','Date','Device','AdNetworkType1','Slot','AccountCurrencyCode','CostPerConversion','Ctr','AverageCpc','ConversionRate','ConversionValue','AverageCost','AverageCpm','AdNetworkType2','AllConversions'])
                ->from(ReportDefinitionReportType::CAMPAIGN_PERFORMANCE_REPORT)
                ->duringDateRange("$end_date,$start_date")
                ->build();
                

                $reportDownloader = new ReportDownloader($session);

                $reportDownloadResult = $reportDownloader->downloadReportWithAwql(sprintf('%s', $query), 'CSV');
                $reportDownloadResult->saveToFile(\env('FILE_PATH')."public/reports/".$fileName);

                $handle = fopen(\env('FILE_PATH').'public/reports/'.$fileName, "r");
                

                /*$handle = fopen(\env('FILE_PATH').'public/reports/9696489139_campaigns.csv', "r");*/
                $counter = 0;

                $datascrap = array();
                $logs[$i]['start_date'] = $start_date;
                $logs[$i]['end_date'] = $end_date;
                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {

                    $counter++;
                    if($counter <= 2){
                        continue;
                    }

                    if($data[0] == 'Total'){
                        continue;
                    }
                    $keyDate = date('Ymd',strtotime($data[6]));
                    
                    $impressionGraph['dates'][$keyDate] = $data[6];

                    if(!isset($impressionGraphData['impression'][$keyDate])){
                        if(isset($impressionGraph['impression'][$keyDate])){
                            $impressionGraph['impression'][$keyDate] += $data[2];
                        }else{
                            $impressionGraph['impression'][$keyDate] = $data[2];
                        }
                    }
                    
                    if(!isset($impressionGraphData['clicks'][$keyDate])){
                        if(isset($impressionGraph['clicks'][$keyDate])){
                            $impressionGraph['clicks'][$keyDate] +=  $data[3];
                        }else{
                            $impressionGraph['clicks'][$keyDate] =  $data[3];
                        }
                    }

                    if(!isset($impressionGraphData['ctr'][$keyDate])){
                        if(isset($impressionGraph['ctr'][$keyDate])){
                            $impressionGraph['ctr'][$keyDate] +=  str_replace('%', '', $data[12]);
                            // $impressionGraph['ctr'][$keyDate] =  $data[12];
                        }else{
                            $impressionGraph['ctr'][$keyDate] =  str_replace('%', '', $data[12]);
                        }
                    }

                    if(!isset($impressionGraphData['cost'][$keyDate])){
                        if(isset($impressionGraph['cost'][$keyDate])){
                            $impressionGraph['cost'][$keyDate] +=  $data[4]/1000000;
                        }else{
                            $impressionGraph['cost'][$keyDate] =  $data[4]/1000000;
                        }
                    }

                    if(!isset($impressionGraphData['conversions'][$keyDate])){
                        if(isset($impressionGraph['conversions'][$keyDate])){
                            $impressionGraph['conversions'][$keyDate] +=  $data[5];
                        }else{
                            $impressionGraph['conversions'][$keyDate] =  $data[5];
                        }
                    }

                    if(!isset($impressionGraphData['all_conversions'][$keyDate])){
                        if(isset($impressionGraph['all_conversions'][$keyDate])){
                            $impressionGraph['all_conversions'][$keyDate] +=  $data[19];
                        }else{
                            $impressionGraph['all_conversions'][$keyDate] =  $data[19];
                        }
                    }

                    if(!isset($impressionGraphData['average_cpc'][$keyDate])){
                        if(isset($impressionGraph['average_cpc'][$keyDate])){
                            $impressionGraph['average_cpc'][$keyDate] +=  $data[13]/1000000;
                        }else{
                            $impressionGraph['average_cpc'][$keyDate] =  $data[13]/1000000;
                        }
                    }

                    if(!isset($impressionGraphData['conversion_rate'][$keyDate])){
                        if(isset($impressionGraph['conversion_rate'][$keyDate])){
                            $impressionGraph['conversion_rate'][$keyDate] +=  str_replace('%', '', $data[14]);
                        }else{
                            $impressionGraph['conversion_rate'][$keyDate] =  str_replace('%', '', $data[14]);
                        }
                    }

                    if(!isset($impressionGraphData['conversion_value'][$keyDate])){
                        if(isset($impressionGraph['conversion_value'][$keyDate])){
                            $impressionGraph['conversion_value'][$keyDate] +=  $data[15];
                        }else{
                            $impressionGraph['conversion_value'][$keyDate] =  $data[15];
                        }
                    }

                    if(!isset($impressionGraphData['average_cost'][$keyDate])){
                        if(isset($impressionGraph['average_cost'][$keyDate])){
                            $impressionGraph['average_cost'][$keyDate] +=  $data[16]/1000000;
                        }else{
                            $impressionGraph['average_cost'][$keyDate] =  $data[16]/1000000;
                        }
                    }

                    if(!isset($impressionGraphData['average_cpm'][$keyDate])){
                        if(isset($impressionGraph['average_cpm'][$keyDate])){
                            $impressionGraph['average_cpm'][$keyDate] +=  $data[15]/1000000;
                        }else{
                            $impressionGraph['average_cpm'][$keyDate] =  $data[15]/1000000;
                        } 
                    } 

                    if(!isset($impressionGraphData['cost_per_conversion'][$keyDate])){
                        if(isset($impressionGraph['cost_per_conversion'][$keyDate])){
                            $impressionGraph['cost_per_conversion'][$keyDate] +=  $data[11]/1000000;
                        }else{
                            $impressionGraph['cost_per_conversion'][$keyDate] =  $data[11]/1000000;
                        }  
                    }

                    if($i <= 23){

                        $networkKey = str_replace(' ','',$data[18]);
                        $networks[date('Ym',strtotime($end_date))][$networkKey] =  $data[18];

                        $networksData[$networkKey]['dates'][] =  $data[6];
                        $networksData[$networkKey]['impressions'][] =  $data[2];
                        $networksData[$networkKey]['clicks'][] =  $data[3];
                        $networksData[$networkKey]['ctr'][] =  str_replace('%', '', $data[12]);
                        $networksData[$networkKey]['cost'][] =  $data[4]/1000000;
                        $networksData[$networkKey]['conversions'][] =  $data[5];


                        $deviceKey = str_replace(' ','',$data[7]);
                        $device[date('Ym',strtotime($end_date))][$deviceKey] =  $data[7];

                        $deviceData[$deviceKey]['dates'][] =  $data[6];
                        $deviceData[$deviceKey]['impressions'][] =  $data[2];
                        $deviceData[$deviceKey]['clicks'][] =  $data[3];
                        $deviceData[$deviceKey]['ctr'][] =  str_replace('%', '', $data[12]);
                        $deviceData[$deviceKey]['cost'][] =  $data[4]/1000000;
                        $deviceData[$deviceKey]['conversions'][] =  $data[5];

                        $adSlotsKey = str_replace(' ','',$data[9]);
                        $adSlots[date('Ym',strtotime($end_date))][$adSlotsKey] =  $data[9];

                        $adSlotsData[$adSlotsKey]['dates'][] =  $data[6];
                        $adSlotsData[$adSlotsKey]['impressions'][] =  $data[2];
                        $adSlotsData[$adSlotsKey]['clicks'][] =  $data[3];
                        $adSlotsData[$adSlotsKey]['ctr'][] =  str_replace('%', '', $data[12]);
                        $adSlotsData[$adSlotsKey]['cost'][] =  $data[4]/1000000;
                        $adSlotsData[$adSlotsKey]['conversions'][] =  $data[5];


                        $campaigns[date('Ym',strtotime($end_date))][$data[0]] =  $data[1];

                        $file_content[$data[0]]['dates'][] =  $data[6];
                        $file_content[$data[0]]['impressions'][] =  $data[2];
                        $file_content[$data[0]]['clicks'][] =  $data[3];
                        $file_content[$data[0]]['ctr'][] =  str_replace('%', '', $data[12]);
                        $file_content[$data[0]]['cost'][] =  $data[4]/1000000;
                        $file_content[$data[0]]['conversions'][] =  $data[5];
                        $file_content[$data[0]]['all_conversions'][] =  $data[19];
                    }
            

                }
                

                if($i <= 23){
                    if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks')) {
                        mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks', 0777, true);
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($networksData,true),true));

                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks/list.json', print_r(json_encode($networks,true),true));
                    }else{
                        // mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks', 0777, true);
                        //chmod(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks', 0777);
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($networksData,true),true));

                        if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks/list.json')){
                            file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks/list.json', print_r(json_encode($networks,true),true));
                        }else{
                        
                            file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks/list.json', print_r(json_encode($networks,true),true));

                        }
                    }
                    
                    if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices')) {
                        mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices', 0777, true);
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($deviceData,true),true));

                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices/list.json', print_r(json_encode($device,true),true));
                    }else{
                        //chmod(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices', 0777);
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($deviceData,true),true));

                        if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices/list.json')){
                            file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices/list.json', print_r(json_encode($device,true),true));
                        }else{
                        
                            file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices/list.json', print_r(json_encode($device,true),true));

                        }
                    }

                    if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign')) {
                        mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign', 0777, true);
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($file_content,true),true));

                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign/list.json', print_r(json_encode($campaigns,true),true));
                    }else{
                        //chmod(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign', 0777);
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($file_content,true),true));

                        if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign/list.json')){
                            file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign/list.json', print_r(json_encode($campaigns,true),true));
                        }else{
                        
                            file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign/list.json', print_r(json_encode($campaigns,true),true));

                        }
                    }

                    if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots')) {
                        mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots', 0777, true);
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($adSlotsData,true),true));

                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots/list.json', print_r(json_encode($adSlots,true),true));
                    }else{
                        //chmod(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots', 0777);
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($adSlotsData,true),true));

                        if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots/list.json')){
                            file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots/list.json', print_r(json_encode($adSlots,true),true));
                        }else{
                        
                            file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots/list.json', print_r(json_encode($adSlots,true),true));

                        }
                    }
                }
            
                $campaignsData = $file_content =  $deviceData = $networksData = $adSlotsData = array();
            }

            ksort($impressionGraph['dates']);
            ksort($impressionGraph['impression']);
            ksort($impressionGraph['clicks']);
            ksort($impressionGraph['ctr']);
            ksort($impressionGraph['cost']);
            ksort($impressionGraph['conversions']); 
            ksort($impressionGraph['all_conversions']); 

        
            if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs')) {
                mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs', 0777, true);
                file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs/overview.json', print_r(json_encode($impressionGraph,true),true));
            }else{
                //chmod(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs', 0777);
                if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs/overview.json')){
                    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs/overview.json', print_r(json_encode($impressionGraph,true),true));
                }else{
                    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs/overview.json', print_r(json_encode($impressionGraph,true),true));
                }
            }
            
        }catch (Exception $e) {
            return $e->getMessage();

        }

    }

    public static function keywords_reports_query_data($session,$start,$end,$keywords_fileName,$account_id,$today,$project_id,$duration=null){
        
            $keywords = $keywordsData = array();

            if($duration <> null){
                $dates = self::get_data_dates($duration);
            }else{
                $dates = self::get_data_dates();
            }
            $adWordsServices = new AdWordsServices();

            $urlKeywords = env('FILE_PATH')."public/adwords/".$project_id.'/keywords/list.json'; 
            if(file_exists($urlKeywords)){
                $dataValues = file_get_contents($urlKeywords);
                $keywords = json_decode($dataValues,true);
            }

            try{
                for($i=0;$i<=count($dates)-1;$i++){
                

                    $start_date = $dates[$i]['start_date'];
                    $end_date = $dates[$i]['end_date'];
                    // $end_date = $start;

                    $query = (new ReportQueryBuilder())
                    ->select(['Criteria','Id','Impressions','Clicks','Cost','Conversions','Ctr','Date','FirstPageCpc','FirstPositionCpc'])
                    ->from(ReportDefinitionReportType::KEYWORDS_PERFORMANCE_REPORT)
                    ->duringDateRange("$end_date,$start_date")
                    ->build();

                    $reportDownloader = new ReportDownloader($session);
                    $reportDownloadResult = $reportDownloader->downloadReportWithAwql(sprintf('%s', $query), 'CSV');
                    $reportDownloadResult->saveToFile(\env('FILE_PATH')."public/reports/".$keywords_fileName);
                    $handle = fopen(\env('FILE_PATH').'public/reports/'.$keywords_fileName, "r");
                    /*$handle = fopen(\env('FILE_PATH').'public/reports/9696489139_keywords.csv', "r");*/
                    
                    $counter =0;
                    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {

                        $counter++;
                        if($counter <= 2){
                            continue;
                        }

                        if($data[0] == 'Total'){
                            continue;
                        }
                        
                        $keywords[date('Ym',strtotime($end_date))][$data[1]] =  $data[0];

                        $keywordsData[$data[1]]['dates'][] =  $data[7];
                        $keywordsData[$data[1]]['impressions'][] =  $data[2];
                        $keywordsData[$data[1]]['clicks'][] =  $data[3];
                        $keywordsData[$data[1]]['cost'][] =  $data[4]/1000000;
                        $keywordsData[$data[1]]['conversions'][] =  $data[5];
                        $keywordsData[$data[1]]['ctr'][] =  $data[6];
                        $keywordsData[$data[1]]['first_page_cpc'][] =  trim($data[8]) <> 0 ? $data[8]/1000000 :0;
                        $keywordsData[$data[1]]['first_postion_cpc'][] =  trim($data[9]) <> '--' ? $data[9]/1000000 : 0;
                    }

                    if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/keywords')) {
                        mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/keywords', 0777, true);
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/keywords/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($keywordsData,true),true));

                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/keywords/list.json', print_r(json_encode($keywords,true),true));
                    }else{
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/keywords/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($keywordsData,true),true));

                        if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/keywords/list.json')){
                            file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/keywords/list.json', print_r(json_encode($keywords,true),true));
                        }else{
                        
                            file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/keywords/list.json', print_r(json_encode($keywords,true),true));

                        }
                    }

                    $keywordsData = array();

                }
                 
                
            }catch (Exception $e) {
                return $e->getMessage();

            }
    
        
    }

    public static function ads_reports_query_data($session,$start,$end,$ads_fileName,$account_id,$today,$project_id,$duration=null){
       
        $ads = $adsAdditional = array();
        
        if($duration <> null){
            $dates = self::get_data_dates($duration);
        }else{
            $dates = self::get_data_dates();
        }

        $adWordsServices = new AdWordsServices();

        $urlKeywords = env('FILE_PATH')."public/adwords/".$project_id.'/ads/list.json'; 
        if(file_exists($urlKeywords)){
            $dataValues = file_get_contents($urlKeywords);
            $ads = json_decode($dataValues,true);
        }

        $urlAdditional = env('FILE_PATH')."public/adwords/".$project_id.'/ads/aditional.json'; 
        if(file_exists($urlAdditional)){
            $dataAdditional = file_get_contents($urlAdditional);
            $adsAdditional = json_decode($dataAdditional,true);
        }

        try{

            for($i=0;$i<=count($dates)-1;$i++){
            

                $start_date = $dates[$i]['start_date'];
                $end_date = $dates[$i]['end_date'];
            

                $query = (new ReportQueryBuilder())
                ->select(['Headline','HeadlinePart1','HeadlinePart2','ExpandedTextAdHeadlinePart3','DisplayUrl',
                    'Description','Description1','Description2','AdType','Id','Impressions','Clicks','Cost',
                    'Conversions',
                    'ResponsiveSearchAdHeadlines','ResponsiveSearchAdDescriptions','ResponsiveSearchAdPath1','ResponsiveSearchAdPath2'
                    ,'ExpandedTextAdDescription2','ExpandedDynamicSearchCreativeDescription2','CreativeFinalUrls','Path1','Path2',
                    'LongHeadline','MultiAssetResponsiveDisplayAdHeadlines','MultiAssetResponsiveDisplayAdLongHeadline','MultiAssetResponsiveDisplayAdDescriptions','Ctr','Date'])
                ->from(ReportDefinitionReportType::AD_PERFORMANCE_REPORT)
                ->duringDateRange("$end_date,$start_date")
                ->build();

                $reportDownloader = new ReportDownloader($session);
                $reportDownloadResult = $reportDownloader->downloadReportWithAwql(sprintf('%s', $query), 'CSV');
                $reportDownloadResult->saveToFile(\env('FILE_PATH')."public/reports/".$ads_fileName);
                $handle = fopen(\env('FILE_PATH').'public/reports/'.$ads_fileName, "r");
                // $handle = fopen(\env('FILE_PATH').'public/reports/9696489139_ads.csv', "r");
                $counter = 0;
                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    $counter++;
                    if($counter <= 2){
                        continue;
                    }
                
                    if($data[0] == 'Total'){
                        continue;
                    }
            
                    $ads[date('Ym',strtotime($end_date))][$data[9]] =  $data[0];

                    $headLines = self::findHeadline($data);
                    $displayUrl = self::findDisplayUrl($data);
                    $discription = self::findDescription($data);

                    $adsAdditional[$data[9]] =  [
                        'ad_type'=>$data[8],
                        'headlines'=>$headLines,
                        'displayurl'=>$displayUrl,
                        'discription'=>$discription,
                    ];

                    $adsData[$data[9]]['dates'][] =  $data[28];
                    $adsData[$data[9]]['impressions'][] =  $data[10];
                    $adsData[$data[9]]['clicks'][] =  $data[11];
                    $adsData[$data[9]]['cost'][] =  $data[12]/1000000;
                    $adsData[$data[9]]['conversions'][] =  $data[13];
                    $adsData[$data[9]]['ctr'][] =  $data[27];
                    
                }

                if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/ads')) {
                    mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/ads', 0777, true);
                    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/ads/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($adsData,true),true));

                    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/ads/list.json', print_r(json_encode($ads,true),true));
                    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/ads/aditional.json', print_r(json_encode($adsAdditional,true),true));
                }else{
                    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/ads/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($adsData,true),true));

                    if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/ads/list.json')){
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/ads/list.json', print_r(json_encode($ads,true),true));
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/ads/aditional.json', print_r(json_encode($adsAdditional,true),true));
                    }else{
                    
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/ads/list.json', print_r(json_encode($ads,true),true));
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/ads/aditional.json', print_r(json_encode($adsAdditional,true),true));

                    }
                }

                $adsData = array();

            }
            
        }catch (Exception $e) {
            return $e->getMessage();
        }
    
    }

    public static function adsGroup_reports_query_data($session,$start,$end,$adgroup_fileName,$account_id,$today,$project_id,$duration=null){
       
        $adgroups = array();

        if($duration <> null){
            $dates = self::get_data_dates($duration);
        }else{
            $dates = self::get_data_dates();
        }

        $adWordsServices = new AdWordsServices();

        $urlKeywords = env('FILE_PATH')."public/adwords/".$project_id.'/adgroups/list.json'; 
        if(file_exists($urlKeywords)){
            $dataValues = file_get_contents($urlKeywords);
            $adgroups = json_decode($dataValues,true);
        }

        try{
            for($i=0;$i<=count($dates)-1;$i++){
            

                $start_date = $dates[$i]['start_date'];
                $end_date = $dates[$i]['end_date'];
            
                $query = (new ReportQueryBuilder())
                ->select(['AdGroupId','AdGroupName','Impressions','Clicks','Cost','Conversions','Ctr','AccountCurrencyCode','Date'])
                ->from(ReportDefinitionReportType::ADGROUP_PERFORMANCE_REPORT)
                ->duringDateRange("$end_date,$start_date")
                ->build();
                
                $reportDownloader = new ReportDownloader($session);
                $reportDownloadResult = $reportDownloader->downloadReportWithAwql(sprintf('%s', $query), 'CSV');
                $reportDownloadResult->saveToFile(\env('FILE_PATH')."public/reports/".$adgroup_fileName);
                $handle = fopen(\env('FILE_PATH').'public/reports/'.$adgroup_fileName, "r");
                
                $counter = 0;
                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    $counter++;
                    if($counter <= 2){
                        continue;
                    }

                    if($data[0] == 'Total'){
                        continue;
                    }
                    
                    

                    $adgroups[date('Ym',strtotime($end_date))][$data[0]] =  $data[1];

                    $adgroupsData[$data[0]]['dates'][] =  $data[8];
                    $adgroupsData[$data[0]]['impressions'][] =  $data[2];
                    $adgroupsData[$data[0]]['clicks'][] =  $data[3];
                    $adgroupsData[$data[0]]['cost'][] =  $data[4]/1000000;
                    $adgroupsData[$data[0]]['conversions'][] =  $data[5];
                    $adgroupsData[$data[0]]['ctr'][] =  $data[6];

                }

                if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adgroups')) {
                    mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adgroups', 0777, true);
                    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adgroups/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($adgroupsData,true),true));

                    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adgroups/list.json', print_r(json_encode($adgroups,true),true));
                }else{
                    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adgroups/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($adgroupsData,true),true));

                    if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adgroups/list.json')){
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adgroups/list.json', print_r(json_encode($adgroups,true),true));
                    }else{
                    
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adgroups/list.json', print_r(json_encode($adgroups,true),true));

                    }
                }

                $adgroupsData = array();
            }
        }catch (Exception $e) {
            return $e->getMessage();

        }
  
    }

    public static function ads_placeholder_reports_query_data($session,$start,$end,$place_file,$account_id,$today,$project_id,$duration=null){
       
        $clickType = $clickTypeData = array();
        
        if($duration <> null){
            $dates = self::get_data_dates($duration);
        }else{
            $dates = self::get_data_dates();
        }

        $adWordsServices = new AdWordsServices();

        $urlKeywords = env('FILE_PATH')."public/adwords/".$project_id.'/clickType/list.json'; 
        if(file_exists($urlKeywords)){
            $dataValues = file_get_contents($urlKeywords);
            $clickType = json_decode($dataValues,true);
        }

        try{
            
            for($i=0;$i<=count($dates)-1;$i++){
            

                $start_date = $dates[$i]['start_date'];
                $end_date = $dates[$i]['end_date'];

                $query = (new ReportQueryBuilder())
                ->select(['ClickType','Impressions','Clicks','Cost','Conversions','CampaignName','CampaignStatus','Ctr','Date'])
                ->from(ReportDefinitionReportType::CAMPAIGN_PERFORMANCE_REPORT)
                ->duringDateRange("$end_date,$start_date")
                ->build();

                $reportDownloader = new ReportDownloader($session);
                $reportDownloadResult = $reportDownloader->downloadReportWithAwql(sprintf('%s', $query), 'CSV');
                $reportDownloadResult->saveToFile(\env('FILE_PATH')."public/reports/".$place_file);
                $handle = fopen(\env('FILE_PATH').'public/reports/'.$place_file, "r");
                /*$handle = fopen(\env('FILE_PATH').'public/reports/9696489139_placeholder.csv', "r");*/
                $counter = 0;
                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    $counter++;
                    if($counter <= 2){
                        continue;
                    }

                    if($data[0] == 'Total'){
                        continue;
                    }
                    
                            
                    $clickTypeKey = str_replace(' ','',$data[0]);
                    $clickType[date('Ym',strtotime($end_date))][$clickTypeKey] =  $data[0];

                    $clickTypeData[$clickTypeKey]['dates'][] =  $data[8];
                    $clickTypeData[$clickTypeKey]['impressions'][] =  $data[1];
                    $clickTypeData[$clickTypeKey]['clicks'][] =  $data[2];
                    $clickTypeData[$clickTypeKey]['cost'][] =  $data[3]/1000000;
                    $clickTypeData[$clickTypeKey]['conversions'][] =  $data[4];
                    $clickTypeData[$clickTypeKey]['ctr'][] =  $data[7];

                }

                if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/clickType')) {
                    mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/clickType', 0777, true);
                    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/clickType/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($clickTypeData,true),true));

                    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/clickType/list.json', print_r(json_encode($clickType,true),true));
                }else{
                    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/clickType/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($clickTypeData,true),true));

                    if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/clickType/list.json')){
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/clickType/list.json', print_r(json_encode($clickType,true),true));
                    }else{
                    
                        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/clickType/list.json', print_r(json_encode($clickType,true),true));

                    }
                }

                $clickTypeData = array();
            }   
             
            
        }catch (Exception $e) {
            return $e->getMessage();

        }

    }

    
    public static function calculate_percentage($a1, $a2){
         if($a2 <> '0'){
            $percentage = (($a1-$a2)/$a2)*100;
             return number_format($percentage,2,'.',',');
         }else{
             $percentage = 0;
              return $percentage;
         }
    }

    public static function get_data_dates($range = 23){
        $lapse ='-1 month';
        $duration = $range;

        
        for($i=0;$i<=$duration;$i++){
            if($i==0){  

                $start_date = date('Ymd',strtotime('-1 day'));
                $end_date = date('Ym01',strtotime($start_date)); 
                /*$end_date = date('Ymd', strtotime($lapse, strtotime($start_date))); */
                $lastDate = date('Y-m-d',strtotime($end_date));
            }else{
                
                $start_date = date('Ymt',strtotime('-1 month',strtotime($lastDate)));
                $end_date = date('Ym01',strtotime('-1 month',strtotime($lastDate)));

                $lastDate = date('Y-m-d',strtotime($end_date));
                /*$end_date = date('Ymd',strtotime($lapse,strtotime($start_date)));*/
            }
            $res[$i]['start_date'] = $start_date;
            $res[$i]['end_date'] = $end_date;
        }   

        return $res;
    }

    public static function findHeadline($data){

        $headline = '';
        if($data[8] == 'Expanded dynamic search ad'){
            $headline = "[Dynamically generated headline]";
        }

        if($data[8] == 'Responsive search ad'){
            $headline =    implode(" | ",array_map(function($a){
                return $a['assetText'];
            },json_decode($data[14],true)));
        }

        if($data[8] =='Responsive display ad'){
            if($data[24] != '--'){
                $multidesc = json_decode($data[24],true);
                $headline =    implode(",",array_map(function($a){
                    return $a['assetText'];
                },$multidesc));
            } 
        }

        if($data[8]=='Expanded text ad'){
            $headline = $data[3];
        }

        if($data[0] != ' --' && $data[0] != null){
            $headline = $data[0];
        }

        return $headline;

    }

    public static function findDisplayUrl($data){
        $url = $path1 = $path2 = '';
        if($data[8] =='Expanded dynamic search ad'){
            $url = "[Dynamically generated display URL]";
        }

        if($data[8] == 'Responsive search ad'){
            $url = json_decode($data[20],true)[0].$data[16].'/'.$data[17].'/';
        }

        if($data[8] == 'Responsive display ad'){
            $url = json_decode($data[20],true)[0].$data[16].'/'.$data[17].'/';
        }

        if($data[8] =='Expanded text ad'){

            if($data[21]!=' --'){
                $path1 = $data[21].'/';
            }

            if($data[22] != ' --'){
                $path2 = $data[22].'/';
            }

            $url = json_decode($data[20],true)[0].$path1.$path2;
        }

        return $url;
    }

    public static function findDescription($data){

        $description =  $description1 = '';

        if($data[8] == 'Responsive search ad'){
            $description = implode(",",array_map(function($a){
                return $a['assetText'];
            },json_decode($data[15],true)));
        }



        if($data[8]=='Expanded dynamic search ad'){
            $description = '';
        }

        if($data[8] == 'Responsive display ad'){
            if($data[25]!=' --'){
                $multiDesc1 = json_decode($data[25],true);

                if(count($multiDesc1)==1){
                    $description =  implode(",",array_map(function($a){
                        return $a['assetText'];
                    },$multiDesc1));
                }else{
                    $description =  implode(",",array_map(function($a){
                        return $a;
                    },$multiDesc1));
                }
            }


            if($data[26]!=' --'){
                $multiDescDisplayDes = json_decode($data[26],true);

                if(is_array($multiDescDisplayDes) && count($multiDescDisplayDes)==1){
                    $description1 = implode(",",array_map(function($a){
                        return $a;
                    },$multiDescDisplayDes));
                }
                if(is_array($multiDescDisplayDes) && count($multiDescDisplayDes)>1){
                    $description1 =  implode(",",array_map(function($a){
                        return $a['assetText'];
                    },$multiDescDisplayDes));
                }

            }
        }


        if($data[8]=='Expanded text ad'){
            if($data[5] !=' --' && $data[5]!=null){
                $description = $data[5];
            }
            if($data[18]!=' --' && $data[18]!=null){
                $description1 = $data[18];
            }
        }



        return $description.$description1;

    }

}