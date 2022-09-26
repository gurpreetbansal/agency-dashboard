<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\SemrushUserAccount;
use App\GoogleAnalyticsUsers;
use App\ModuleByDateRange;
use App\User;
use App\SearchConsoleUsers;
use App\SearchConsoleUrl;
use App\GoogleUpdate;
use App\Error;
use Auth;
use Exception;

class googleSearchConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Search:Console';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store console data for particular campaign.';

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
        $getUser = SemrushUserAccount::
        whereHas('UserInfo', function($q){
            $q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
            ->where('subscription_status', 1);
        })  
        ->where('console_account_id','!=',NULL)
        ->where('status',0)
        //->limit(10)
        ->get();

        // file_put_contents(dirname(__FILE__).'/search_console/'.date('Y-m-d H:i:s').'.txt',print_r($getUser,true));
        
        if(isset($getUser) && !empty($getUser) && count($getUser) > 0){
            foreach($getUser as $key=>$data){

                $module = ModuleByDateRange::getModuleDateRange($data->id,'search_console');
                $end_date = date('Y-m-d',strtotime('-1 day'));
                if(isset($module) && !empty($module)){
                    $list_start_date = date('Y-m-d', strtotime("-".$module->duration." month", strtotime($end_date)));
                }else{
                    $list_start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date)));
                }



                $getAnalytics = SearchConsoleUsers::where('user_id',$data->user_id)->where('id',$data->google_console_id)->first();
                $get_profile_data = SearchConsoleUrl::where('id',$data->console_account_id)->first();
                $client = SearchConsoleUsers::ClientAuth($getAnalytics);
                $refresh_token  = $getAnalytics->google_refresh_token;
                if ($client->isAccessTokenExpired()) {
                    GoogleAnalyticsUsers::google_refresh_token($client,$refresh_token,$getAnalytics->id);
                }

                if(!empty($get_profile_data)){
                    $profile_url = $get_profile_data->siteUrl;
                    $check = SearchConsoleUrl::check_weekly_data($client,$profile_url,$data->console_account_id);
                    
                    if($check['status'] == 0 || $check['status'] == 2){
                        Error::updateOrCreate(
                            ['request_id' => $data->id,'module'=> 2],
                            ['response'=> json_encode($check),'request_id' => $data->id,'module'=> 2,'updated_at'=>now()]
                        );
                    }else{
                        $log_data = SearchConsoleUsers::log_latest_search_console_data($client,$profile_url,$data->id,$list_start_date);
                        if(isset($log_data['status']) && $log_data['status'] == 1){
                            $ifErrorExists = Error::removeExisitingError(2,$data->id);
                            if(!empty($ifErrorExists)){
                                Error::where('id',$ifErrorExists->id)->delete();
                            }
                            GoogleUpdate::updateTiming($data->id,'search_console','sc_type','1');
                        }
                    }
                }
            }
        }
    }

}