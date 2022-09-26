<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\CampaignSetting;
use App\Views\ViewKeywordSearch;
use App\KeywordAlert;
use Mail;

class DailyKeywordAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Keyword:Alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send alerts for the ranked keywords on daily basis';

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
        $results = CampaignSetting::
        whereHas('KeywordAlertData',function($q){
            $q->where('sent_at','<=',date('Y-m-d'))
            ->where('sent_status',0)
            ->where('sent_status','!=',2);
        })
        ->whereHas('SemrushUserData', function ($q) {
            $q->where('status', 0)
            ->select('id','domain_name','host_url','clientName','share_key');
        })
        ->whereHas('UserInfo', function($q){
            $q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
            ->where('subscription_status', 1);
        })
        ->limit(50)
        ->get();

        file_put_contents(dirname(__FILE__).'/logs/keyword_alert_report.txt',print_r($results,true));

        if(!empty($results) && count($results) > 0){
            $ids = $results->pluck('request_id');
            KeywordAlert::whereIn('request_id',$ids)->update(
                [
                    'sent_status'=>2,
                    'sent_at'=>date('Y-m-d')
                ]
            );
            file_put_contents(dirname(__FILE__).'/logs/keyword_alert_report1.txt',print_r($results,true), FILE_APPEND);
            
            foreach($results as $key=>$value){
                $user_id = $value->user_id;
              //  $viewkey_link = \config('app.base_url').'project-detail/'.base64_encode($value->request_id.'-|-'.$value->user_id.'-|-'.time());
                $viewkey_link = \config('app.base_url').'project-detail/'.$value->share_key;
                
                $previous_date = date('F d',strtotime('-1 day',strtotime(now())));
                $today = date('F d',strtotime(now()));

                $result = ViewKeywordSearch::
                select('keyword','request_id','oneday_position','sv','position','region','updated_at')
                ->where('oneday_position','!=',0)
                ->where('request_id',$value->request_id)
                ->whereHas('SemrushUserData', function($q) use ($user_id){
                    $q->where('status', 0)
                    ->where('user_id',$user_id);
                })
                ->whereDate('updated_at',date('Y-m-d'))
                ->get();

                

                if(count($result) > 0){
                    $client_email = explode(', ',$value->client_emails);
                    $client_emails = array_map('trim', $client_email);
                    if($value->manager_alerts == 1){
                        $manager_email = $value->manager_email;
                        array_push($client_emails,$manager_email);
                    }

                    $data_array = array('value'=>$value,'result'=>$result,'viewkey_link'=>$viewkey_link,'previous_date'=>$previous_date,'today'=>$today);
                    
                    try{
                        Mail::send(['html' => 'mails/vendor/keyword_alerts'], $data_array, function($message) use ($client_emails,$value)
                        {    
                            $message->to($client_emails)
                            ->subject('Notification of rank change. Project: '.$value->SemrushUserData->domain_name)
                            ->from(\config('app.mail'), 'Agency Dashboard');   
                        });
                    }catch(\Exception $exception){
                        // return $exception->getMessage();
                    }


                    if (!Mail::failures()){           
                     KeywordAlert::where('campaign_setting_id',$value->id)->where('request_id',$value->request_id)->update(
                        [
                            'sent_status'=>1,
                            'sent_at'=>date('Y-m-d')
                        ]
                    );
                 }else{
                    KeywordAlert::where('campaign_setting_id',$value->id)->where('request_id',$value->request_id)->update(
                        [
                            'sent_status'=>0,
                            'sent_at'=>date('Y-m-d')
                        ]
                    );
                }
            }           
        }
    }

}
}
