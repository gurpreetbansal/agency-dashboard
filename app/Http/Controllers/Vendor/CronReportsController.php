<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\ScheduleReport;
use App\ScheduleReportHistory;
use App\UserSystemSetting;
use Mail;


class CronReportsController extends Controller {

	public function cron_send_now(){
        $results = ScheduleReport::
        where(function ($query) {
            $query->where('next_delivery', '=', date('Y-m-d'))
            ->orWhereNull('last_delivery');
        })
        ->whereHas('UserInfo', function($query){
            $query->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
            ->where('subscription_status', 1);
        }) 
        ->whereNull('sent_status')
        ->where('status',1)
       // ->limit(1)
        ->whereIn('id',[1611])
        ->get();

        


       if(isset($results) && !empty($results)){
            $ids = $results->pluck('id');
            ScheduleReport::whereIn('id',$ids)->update(
                [
                    'sent_status'=> 2
                ]
            );
            sleep(1);
            foreach($results as $key=>$value){
                $emails =  $file_path = '';
                if($value->report_type === 1){
                    $type = 'seo';
                }elseif($value->report_type === 2){
                    $type = 'livekeyword';
                }

                $keyenc = base64_encode($value->request_id.'-|-'.$value->user_id.'-|-'.time());
                $dominurl = trim($value->project_name->host_url,'/');
                if($value->format === 1){
                    $format = 'application/pdf';
                    $filename = $dominurl.'-'.date('D-M-Y').'.pdf';
                    ScheduleReport::downloadPdf($keyenc,$filename,$type,'report_downloads');          
                }

                if($value->format === 2){
                    $format = 'application/xlsx';
                    $filename = $dominurl.'-'.date('D-M-Y').'.xlsx';
                    ScheduleReport::downloadCsv($value->request_id,$filename,'report_downloads');          
                }

                $file_path = \config('app.FILE_PATH').'public/report_downloads/'.$filename;
                if (file_exists($file_path)) {
                    $emails = '';
                    $email = explode(', ',$value->email);
                   // $emails = array_map('trim', $email);
                    $emails = array_unique($email);
                    $data = array('email_text'=>$value->mail_text);

                    $system_setting  = UserSystemSetting::where('user_id',$value->user_id)->first();

                    if(!empty($system_setting)){
                         $email_from = $system_setting->email_deliver_from;
                         $reply_to = $system_setting->email_reply_to;
                     }else{
                         $email_from = $reply_to = \config('app.mail');
                     }


                    try{
                        Mail::send(['html' => 'mails/vendor/schedule_reports'], $data, function($message) use ($emails,$value,$file_path,$filename,$format,$email_from,$reply_to)
                        {    
                            $message->to($emails)
                            ->subject($value->subject)
                            ->attach($file_path, [
                                'as' => $filename, 
                                'mime' => $format
                            ])
                            ->replyTo($reply_to);
                            $message->from($email_from, 'Agency Dashboard');   
                        });

                    }catch(\Exception $exception){
                                // return $exception->getMessage();
                    }

                    if (Mail::failures()){    
                        ScheduleReport::where('id',$value->id)->update([
                            'sent_status'=> null
                        ]); 

                        file_put_contents(dirname(__FILE__).'/logs/emails_failure_'.date('Y-m-d').'.txt',print_r(' , '.$value->id,true),FILE_APPEND);       
                    }else{
                        // if($file_path){
                           
                            unlink($file_path);
                            ScheduleReport::where('id',$value->id)->update([
                                'sent_status'=> null,
                                'last_delivery' => now(),
                                'next_delivery' => ScheduleReport::calculateDates($value)
                            ]);

                            foreach($emails as $email){
                                ScheduleReportHistory::create([
                                    'report_id' => $value->id,
                                    'email' => $email,
                                    'sent_on'=> date('Y-m-d')
                                ]);
                            }  

                            file_put_contents(dirname(__FILE__).'/logs/emails_sent_'.date('Y-m-d').'.txt',print_r(' , '.$value->id,true),FILE_APPEND);
                        // }
                             sleep(4);
                    }
                }
                $emails =  $file_path = '';
            }
        }
    
	}

}