<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SemrushUserAccount;
use App\GmbLocation;
use App\GoogleAnalyticsUsers;
use App\GoogleUpdate;
use App\Error;
use Exception;

use App\Traits\GMBAuth;

class GoogleMyBusiness extends Command
{

    use GMBAuth;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Google:MyBusiness';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stores google my business data for the specific campaign.';

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
    public function handle()
    {
       //try{
            /*$getUser = SemrushUserAccount::
            whereHas('UserInfo', function($q){
              $q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
              ->where('subscription_status', 1);
            })  
            ->whereNotNull('gmb_analaytics_id')
            ->whereNotNull('gmb_id')
            ->where('status',0)
            ->orderBy('id','desc')
            ->limit(2)
            ->get();*/

            $getUser = SemrushUserAccount::
                whereHas('UserInfo', function($q){
                    $q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
                    ->where('subscription_status', 1);
                }) 
            
                ->whereNotNull('gmb_analaytics_id')
                ->whereNotNull('gmb_id')
                ->where('status',0)
                ->orderBy('id','desc')
                
                ->where(function($q){
                    $q->whereRaw("exists (select * from `google_updates` where `semrush_users_account`.`id` = `google_updates`.`request_id` and (DATE(`gmb`) <> '".date('Y-m-d')."' or gmb IS NULL))  or not exists (select * from `google_updates` where `semrush_users_account`.`id` = `google_updates`.`request_id`)");

                })
                ->whereDoesntHave('GoogleErrors', function ($q) {
                    $q->where('module',4)
                    ->whereDate('updated_at',date('Y-m-d'));
                })
                ->get();

                file_put_contents(dirname(__FILE__).'/logs/GoogleMyBusiness.txt',print_r($getUser,true));
                /*file_put_contents(dirname(__FILE__).'/logs/GoogleMyBusiness.txt',print_r($getUser,true));
                die;*/

            if (!empty($getUser))
            {
                $start_date = date('Y-m-d\TH:i:s\.000000000\Z',strtotime("-1 day"));
                $end_date =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-1 year", strtotime(date('Y-m-d'))));
                foreach($getUser as  $gtUser){
                    $campaign_id = $gtUser->id;
                    $user_id = $gtUser->user_id;

                    $gmbLocation =     GmbLocation::where('id',$gtUser->gmb_id)->first();
                    if(!empty($gmbLocation)){
                        $getAnalytics =     GoogleAnalyticsUsers::where('id',$gmbLocation->google_account_id)->first();

                        $client = GoogleAnalyticsUsers::googleGmbClientAuth($getAnalytics);
                        $refresh_token  = $getAnalytics->google_refresh_token;
                        /*if refresh token expires*/
                        if ($client->isAccessTokenExpired()) {
                            GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
                        }   

                        $check = $this->checkGmbData($client,$campaign_id,$user_id,$gtUser->gmb_analaytics_id,$gtUser->gmb_id);
                        if(isset($check['status']) && $check['status'] != 1){
                           /* Error::updateOrCreate(
                                ['request_id' => $campaignId],
                                ['response'=> json_encode($check),'module'=> 4]
                            );*/

                            Error::updateOrCreate(
                                ['request_id' => $campaign_id,'module'=> 4],
                                ['response'=> json_encode($check),'request_id' => $campaign_id,'module'=> 4]
                            );
                        }else{
                            /* Where customers view your business on Google */
                            if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                $customer_view_graph = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_view_graph.json';
                                if(file_exists($customer_view_graph)){
                                    if(date("Y-m-d", filemtime($customer_view_graph)) != date('Y-m-d')){
                                        $this->get_customer_view($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
                                    }
                                }else{
                                    $this->get_customer_view($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
                                }
                            }
                            elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
                                $this->get_customer_view($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
                            }

                            /* Customer actions */
                            if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                $customer_action_graph = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_action_graph.json';
                                if(file_exists($customer_action_graph)){
                                    if(date("Y-m-d", filemtime($customer_action_graph)) != date('Y-m-d')){
                                        $this->get_customer_action($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
                                    }
                                }else{
                                    $this->get_customer_action($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
                                }
                            }
                            elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
                                $this->get_customer_action($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
                            }


                                /* Photo Views */
                            if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                $photo_views = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_views.json';
                                if(file_exists($photo_views)){
                                    if(date("Y-m-d", filemtime($photo_views)) != date('Y-m-d')){
                                        $this->get_photo_views($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
                                    }
                                }else{
                                    GmbLocation::get_photo_views($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
                                }
                            }
                            elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
                                $this->get_photo_views($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date,$campaign_id);
                            }

                            /* Customer Search */
                            if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                $customer_search = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_search.json';
                                if(file_exists($customer_search)){
                                    if(date("Y-m-d", filemtime($customer_search)) != date('Y-m-d')){
                                        $this->get_customer_search($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                                    }
                                }else{
                                    $this->get_customer_search($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                                }
                            }
                            elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
                                $this->get_customer_search($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                            }    

                            /* Direction Requests */
                            if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                $direction_requests = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/direction_requests.json';
                                if(file_exists($direction_requests)){
                                    if(date("Y-m-d", filemtime($direction_requests)) != date('Y-m-d')){
                                        $this->get_direction_requests($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                                    }
                                }else{
                                    $this->get_direction_requests($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                                }
                            }
                            elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
                                $this->get_direction_requests($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                            }    

                            /* Phone calls */
                            if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                $phone_calls = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/phone_calls.json';
                                if(file_exists($phone_calls)){
                                    if(date("Y-m-d", filemtime($phone_calls)) != date('Y-m-d')){
                                        $this->get_phone_calls($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                                    }
                                }else{
                                    $this->get_phone_calls($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                                }
                            }
                            elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
                                $this->get_phone_calls($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                            }    


                            /* Reviews */
                            if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                $reviews = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/reviews.json';
                                if(file_exists($reviews)){
                                    if(date("Y-m-d", filemtime($reviews)) != date('Y-m-d')){
                                        $this->get_reviews($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                                    }
                                }else{
                                    $this->get_reviews($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                                }
                            }
                            elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
                                $this->get_reviews($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                            }

                            /*media*/
                            if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                $media = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/media.json';
                                if(file_exists($media)){
                                    if(date("Y-m-d", filemtime($media)) != date('Y-m-d')){
                                        $this->get_location_media($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                                    }
                                }else{
                                    $this->get_location_media($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                                }
                            }
                            elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
                                mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
                                $this->get_location_media($client,$gmbLocation->account_id,$gmbLocation->location_id,$campaign_id);
                            }

                            
                        } //end else
                    } //end gmbLocation
                        sleep(1);
                }  //end foreach 
            }  //end if getuser
        // }catch(\Exception $e){
        //     return $e->getMessage();
        // }
    }

    private function get_customer_view($client,$account_id,$location_id,$start_date,$end_date,$campaign_id){
        $metrixGraphViewMap = $this->getLocationMetrixViewMap($client,$account_id,$location_id,$start_date,$end_date);
        $search = $metrixGraphViewMap['search']['value'];
        $maps = $metrixGraphViewMap['maps']['value'];
        $dates = $metrixGraphViewMap['search']['labels'];
        $convrted_dates  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $dates);  

        $customer_view_array = array(
            'search' =>$search,
            'maps'=>$maps,
            'dates'=>$dates,
            'convrted_dates'=>$convrted_dates
        );

        if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            $filename = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_view_graph.json';

            if(file_exists($filename)){
                if(date("Y-m-d", filemtime($filename)) != date('Y-m-d')){
                    file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_view_graph.json', print_r(json_encode($customer_view_array,true),true));
                }
            }else{
                file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_view_graph.json', print_r(json_encode($customer_view_array,true),true));
            }

        }elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
            file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_view_graph.json', print_r(json_encode($customer_view_array,true),true));
        }

        $search = $maps = $dates= $convrted_dates = $customer_view_array =  array();
    }

    private function get_customer_action($client,$account_id,$location_id,$start_date,$end_date,$campaign_id){
        $metrixGraphAction = $this->getLocationMetrixCustomerActions($client,$account_id,$location_id,$start_date,$end_date);
        $dates = $metrixGraphAction['website']['labels'];
        $website = $metrixGraphAction['website']['value'];
        $directions = $metrixGraphAction['directions']['value'];
        $phone = $metrixGraphAction['phone']['value'];
        $convrted_dates  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $dates);  

        $customer_action_array = array(
            'website' =>$website,
            'directions'=>$directions,
            'phone'=>$phone,
            'dates'=>$dates,
            'convrted_dates'=>$convrted_dates
        );

        if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            $customer_action_graph = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_action_graph.json';

            if(file_exists($customer_action_graph)){
                if(date("Y-m-d", filemtime($customer_action_graph)) != date('Y-m-d')){
                    file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_action_graph.json', print_r(json_encode($customer_action_array,true),true));
                }
            }else{
                file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_action_graph.json', print_r(json_encode($customer_action_array,true),true));
            }

        }elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
            file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_action_graph.json', print_r(json_encode($customer_action_array,true),true));
        }

        $website = $directions = $phone = $dates= $convrted_dates = $customer_action_array =  array();
    }

    private function get_photo_views($client,$account_id,$location_id,$start_date,$end_date,$campaign_id){
        $metrixGraphAction = $this->getLocationMetrixPhotoViews($client,$account_id,$location_id,$start_date,$end_date);

        $dates = $metrixGraphAction['you']['labels'];
        $value = $metrixGraphAction['you']['value'];
        $you_total = $metrixGraphAction['you_total'];
        $convrted_dates  =  array_map(function($val) { return date("Y-m-d", strtotime($val)); }, $dates);  

        $photo_views_array = array(
            'you_total' =>$you_total,
            'value' =>$value,
            'dates'=>$dates,
            'convrted_dates'=>$convrted_dates
        );

        if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            $photo_views = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_views.json';

            if(file_exists($photo_views)){
                if(date("Y-m-d", filemtime($photo_views)) != date('Y-m-d')){
                    file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_views.json', print_r(json_encode($photo_views_array,true),true));
                }
            }else{
                file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_views.json', print_r(json_encode($photo_views_array,true),true));
            }

        }elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
            file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_views.json', print_r(json_encode($photo_views_array,true),true));
        }

        $you_total = $value = $dates= $convrted_dates = $photo_views_array =  array();
    }

    private function get_customer_search($client,$account_id,$location_id,$campaign_id){

        $start_date = date('Y-m-d\TH:i:s\.000000000\Z');
        $one_month =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-1 month"));
        $three_month = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-3 month'));
        $six_month = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-6 month'));
        $nine_month = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-9 month'));
        $one_year = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-1 year'));


        /*one month*/
        $metrixGraphAction_one = $this->getLocationMetrix($client,$account_id,$location_id,$start_date,$one_month);
        $month_array = array(
            'direct'=>$metrixGraphAction_one['QUERIES_DIRECT']['value'],
            'discovery'=>$metrixGraphAction_one['QUERIES_INDIRECT']['value'],
            'branded'=>$metrixGraphAction_one['QUERIES_CHAIN']['value']
        );
        /*three month*/
        $metrixGraphAction_three = $this->getLocationMetrix($client,$account_id,$location_id,$start_date,$three_month);
        $three_array = array(
            'direct'=>$metrixGraphAction_three['QUERIES_DIRECT']['value'],
            'discovery'=>$metrixGraphAction_three['QUERIES_INDIRECT']['value'],
            'branded'=>$metrixGraphAction_three['QUERIES_CHAIN']['value']
        );
        /*six month*/
        $metrixGraphAction_six = $this->getLocationMetrix($client,$account_id,$location_id,$start_date,$six_month);
        $six_array = array(
            'direct'=>$metrixGraphAction_six['QUERIES_DIRECT']['value'],
            'discovery'=>$metrixGraphAction_six['QUERIES_INDIRECT']['value'],
            'branded'=>$metrixGraphAction_six['QUERIES_CHAIN']['value']
        );
        /*nine month*/
        $metrixGraphAction_nine = $this->getLocationMetrix($client,$account_id,$location_id,$start_date,$nine_month);
        $nine_array = array(
            'direct'=>$metrixGraphAction_nine['QUERIES_DIRECT']['value'],
            'discovery'=>$metrixGraphAction_nine['QUERIES_INDIRECT']['value'],
            'branded'=>$metrixGraphAction_nine['QUERIES_CHAIN']['value']
        );
        /*one year*/
        $metrixGraphAction_year = $this->getLocationMetrix($client,$account_id,$location_id,$start_date,$one_year);
        $year_array = array(
            'direct'=>$metrixGraphAction_year['QUERIES_DIRECT']['value'],
            'discovery'=>$metrixGraphAction_year['QUERIES_INDIRECT']['value'],
            'branded'=>$metrixGraphAction_year['QUERIES_CHAIN']['value']
        );
        

        $final_array = array(
            'month_array'=>$month_array,
            'three_array'=>$three_array,
            'six_array'=>$six_array,
            'nine_array'=>$nine_array,
            'year_array'=>$year_array
        );


        if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            $customer_search = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_search.json';

            if(file_exists($customer_search)){
                if(date("Y-m-d", filemtime($customer_search)) != date('Y-m-d')){
                    file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_search.json', print_r(json_encode($final_array,true),true));
                }
            }else{
                file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_search.json', print_r(json_encode($final_array,true),true));
            }

        }elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
            file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/customer_search.json', print_r(json_encode($final_array,true),true));
        }

        $month_array = $three_array = $six_array= $nine_array = $final_array =  array();
        
    }

    private function get_direction_requests($client,$account_id,$location_id,$campaign_id){
        /*weekly data*/
        $getDirectionRequests_seven = $this->getDirectionRequests($client,$account_id,$location_id,"SEVEN");
        if(isset($getDirectionRequests_seven) && !empty($getDirectionRequests_seven)){
            foreach($getDirectionRequests_seven['data'] as $key=>$value_seven){
                $label_seven[] = $value_seven['label'];
                $count_seven[] = $value_seven['count'];
                $lat_seven[] = $value_seven['latlng']['latitude'];
                $long_seven[] = $value_seven['latlng']['longitude'];
            }
            $seven_array = array(
                'dayCount'=>$getDirectionRequests_seven['dayCount'],
                'label'=>$label_seven,
                'count'=>$count_seven,
                'lat'=>$lat_seven,
                'long'=>$long_seven
            );
        }else{
            $seven_array = array(
                'dayCount'=>'',
                'label'=>array(),
                'count'=>array(),
                'lat'=>array(),
                'long'=>array()
            );
        }

        /*thirty data*/
        $getDirectionRequests_thirty = $this->getDirectionRequests($client,$account_id,$location_id,"THIRTY");
        if(isset($getDirectionRequests_thirty) && !empty($getDirectionRequests_thirty)){
            foreach($getDirectionRequests_thirty['data'] as $key=>$value){
                $label[] = $value['label'];
                $count[] = $value['count'];
                $lat[] = $value['latlng']['latitude'];
                $long[] = $value['latlng']['longitude'];
            }
            $thirty_array = array(
                'dayCount'=>$getDirectionRequests_thirty['dayCount'],
                'label'=>$label,
                'count'=>$count,
                'lat'=>$lat,
                'long'=>$long
            );
        }else{
            $thirty_array = array(
                'dayCount'=>'',
                'label'=>array(),
                'count'=>array(),
                'lat'=>array(),
                'long'=>array()
            );
        }

        /*ninety data*/
        $getDirectionRequests_ninety = $this->getDirectionRequests($client,$account_id,$location_id,"NINETY");

        if(isset($getDirectionRequests_ninety) && !empty($getDirectionRequests_ninety)){
            foreach($getDirectionRequests_ninety['data'] as $key=>$value_nine){

                $label_nine[] = $value_nine['label'];
                $count_nine[] = $value_nine['count'];
                $lat_nine[] = $value_nine['latlng']['latitude'];
                $long_nine[] = $value_nine['latlng']['longitude'];
            }
            $ninety_array = array(
                'dayCount'=>$getDirectionRequests_ninety['dayCount'],
                'label'=>$label_nine,
                'count'=>$count_nine,
                'lat'=>$lat_nine,
                'long'=>$long_nine
            );
        }else{
            $ninety_array = array(
                'dayCount'=>'',
                'label'=>array(),
                'count'=>array(),
                'lat'=>array(),
                'long'=>array()
            );
        }

        $final_array = array(
            'seven_array'=>$seven_array,
            'thirty_array'=>$thirty_array,
            'ninety_array'=>$ninety_array
        );
        
        if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            $direction_requests = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/direction_requests.json';

            if(file_exists($direction_requests)){
                if(date("Y-m-d", filemtime($direction_requests)) != date('Y-m-d')){
                    file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/direction_requests.json', print_r(json_encode($final_array,true),true));
                }
            }else{
                file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/direction_requests.json', print_r(json_encode($final_array,true),true));
            }

        }elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
            file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/direction_requests.json', print_r(json_encode($final_array,true),true));
        }

        $seven_array = $thirty_array = $ninety_array = $final_array =  array();
    }

    private function get_phone_calls($client,$account_id,$location_id,$campaign_id){
        /*weekly data*/
        $start_date = date('Y-m-d\TH:i:s\.000000000\Z');
        
        $weekly =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-1 week"));
        $getDirectionRequests_seven = $this->getPhoneCalls($client,$account_id,$location_id,$start_date,$weekly);
        if($getDirectionRequests_seven){
            $week_array = array(
                'labels'=>$getDirectionRequests_seven['labels'],
                'value'=>$getDirectionRequests_seven['value']
            );
        }
        
        
        $one_month =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-1 month"));
        $getDirectionRequests_month = $this->getPhoneCalls($client,$account_id,$location_id,$start_date,$one_month);
        if($getDirectionRequests_month){
            $month_array = array(
                'labels'=>$getDirectionRequests_month['labels'],
                'value'=>$getDirectionRequests_month['value']
            );
        }

        
        
        $three_month = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-3 month'));
        $getDirectionRequests_three = $this->getPhoneCalls($client,$account_id,$location_id,$start_date,$three_month);
        if($getDirectionRequests_three){
            $three_array = array(
                'labels'=>$getDirectionRequests_three['labels'],
                'value'=>$getDirectionRequests_three['value']
            );
        }
        $six_month = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-6 month'));
        $getDirectionRequests_six = $this->getPhoneCalls($client,$account_id,$location_id,$start_date,$six_month);
        if($getDirectionRequests_six){
            $six_array = array(
                'labels'=>$getDirectionRequests_six['labels'],
                'value'=>$getDirectionRequests_six['value']
            );
        }
        $nine_month = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-9 month'));
        $getDirectionRequests_nine = $this->getPhoneCalls($client,$account_id,$location_id,$start_date,$nine_month);
        if($getDirectionRequests_nine){
            $nine_array = array(
                'labels'=>$getDirectionRequests_nine['labels'],
                'value'=>$getDirectionRequests_nine['value']
            );
        }

        $one_year = date('Y-m-d\TH:i:s\.000000000\Z',strtotime('-1 year'));
        $getDirectionRequests_year = $this->getPhoneCalls($client,$account_id,$location_id,$start_date,$one_year);
        if($getDirectionRequests_year){
            $year_array = array(
                'labels'=>$getDirectionRequests_year['labels'],
                'value'=>$getDirectionRequests_year['value']
            );
        }

        $phone_array  =  array(
            'week_array'=>$week_array,
            'month_array'=>$month_array,
            'three_array'=>$three_array,
            'six_array'=>$six_array,
            'nine_array'=>$nine_array,
            'year_array'=>$year_array,
        );

        if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            $phone_calls = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/phone_calls.json';

            if(file_exists($phone_calls)){
                if(date("Y-m-d", filemtime($phone_calls)) != date('Y-m-d')){
                    file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/phone_calls.json', print_r(json_encode($phone_array,true),true));
                }
            }else{
                file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/phone_calls.json', print_r(json_encode($phone_array,true),true));
            }

        }elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
            file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/phone_calls.json', print_r(json_encode($phone_array,true),true));
        }

        
        $week_array = $month_array = $three_array = $six_array = $nine_array = $year_array = $phone_array = array();
    }

    
    private function get_phone_quantity($client,$account_id,$location_id,$campaign_id){
        $start_date = date('Y-m-d\TH:i:s\.000000000\Z');
        $end_date =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-2 day", strtotime(date('Y-m-d'))));
        $data = array();
        
        $data = $this->getPhoneQuantity($client,$account_id,$location_id,$start_date,$end_date);

        if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            $photo_quantity = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_quantity.json';

            if(file_exists($photo_quantity)){
                if(date("Y-m-d", filemtime($photo_quantity)) != date('Y-m-d')){
                    file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_quantity.json', print_r(json_encode($data,true),true));
                }
            }else{
                file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_quantity.json', print_r(json_encode($data,true),true));
            }

        }elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
            file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/photo_quantity.json', print_r(json_encode($data,true),true));
        }

        $data = array();
    }
    
    private function get_reviews($client,$account_id,$location_id,$campaign_id){
        $getLocationReviews = array();
        
        $getLocationReviews = $this->getLocationReviews($client,$account_id,$location_id);

        if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            $reviews = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/reviews.json';

            if(file_exists($reviews)){
                if(date("Y-m-d", filemtime($reviews)) != date('Y-m-d')){
                    file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/reviews.json', print_r(json_encode($getLocationReviews,true),true));
                }
            }else{
                file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/reviews.json', print_r(json_encode($getLocationReviews,true),true));
            }

        }elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
            file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/reviews.json', print_r(json_encode($getLocationReviews),true));
        }

        $getLocationReviews = array();
    }

    private function get_location_media($client,$account_id,$location_id,$campaign_id){
        $getMedia = array();
        
        $getMedia = $this->getLocationMedia($client,$account_id,$location_id);

        if (file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            $media = \config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/media.json';

            if(file_exists($media)){
                if(date("Y-m-d", filemtime($media)) != date('Y-m-d')){
                    file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/media.json', print_r(json_encode($getMedia,true),true));
                }
            }else{
                file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/media.json', print_r(json_encode($getMedia,true),true));
            }

        }elseif (!file_exists(\config('app.FILE_PATH').'public/gmb/'.$campaign_id)) {
            mkdir(\config('app.FILE_PATH').'public/gmb/'.$campaign_id, 0777, true);
            file_put_contents(\config('app.FILE_PATH').'public/gmb/'.$campaign_id.'/media.json', print_r(json_encode($getMedia),true));
        }

        $getMedia = array();

        GoogleUpdate::updateTiming($campaign_id,'gmb','gmb_type','1');
    }


     private function checkGmbData($client,$campaignID,$user_id,$google_email,$analytics_account_id){
            $result = array();
            $getAnalytics =     GoogleAnalyticsUsers::where('id',$google_email)->first();
            if($getAnalytics){
                $gmbLocation =     GmbLocation::where('id',$analytics_account_id)->first();
                if(!empty($gmbLocation)){
                   $start_date = date('Y-m-d\TH:i:s\.000000000\Z',strtotime("-1 day"));
                   $end_date =  date('Y-m-d\TH:i:s\.000000000\Z', strtotime("-1 week", strtotime(date('Y-m-d'))));

                  
                   $error = array();
                   try {
                       $metrixGraphViewMap = $this->getLocationMetrixViewMap($client,$gmbLocation->account_id,$gmbLocation->location_id,$start_date,$end_date);
                       $search = $metrixGraphViewMap['search']['value'];
                       $result['status'] = 1;
                       $result['message'] = $search;
                   } catch(\Exception $j) {
                       $error = json_decode($j->getMessage(), true);
                       $result['status'] = 0;
                       $result['message'] = $error['error'];
                   }
               }else{
                  $result['status'] = 2;
                  $result['message'] = 'Gmb location id doesnot exists.';
              }
              return $result;
          }   
    }
}
