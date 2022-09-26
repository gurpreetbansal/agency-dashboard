<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\User;
use App\ScheduleReport;
use App\SemrushUserAccount;
use App\ApiBalance;
use App\SendScheduleReport;
use App\Views\ViewCampaign;
use App\ScheduleReportHistory;
use App\UserSystemSetting;
use Auth;
use DB;
use Mail;


class ReportsTestController extends Controller {

	public function test_report(){
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
		->limit(100)
		->get();

		// echo "<pre>";
		// print_r($results);
		// die;

		file_put_contents(dirname(__FILE__).'/logs/resultsss.txt',print_r($results,true));

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
					ScheduleReport::downloadPdf($keyenc,$filename,$type);          
				}

				if($value->format === 2){
					$format = 'application/xlsx';
					$filename = $dominurl.'-'.date('D-M-Y').'.xlsx';
					ScheduleReport::downloadCsv($value->request_id,$filename);          
				}

				$file_path = \config('app.FILE_PATH').'public/report_downloads/'.$filename;

				file_put_contents(dirname(__FILE__).'/logs/file_path.txt',print_r($file_path,true));
				if (file_exists($file_path)) {
					$email = explode(', ',$value->email);
					// $emails = array_map('trim', $email);
					$data = array('email_text'=>$value->mail_text);

					$system_setting  = UserSystemSetting::where('user_id',$value->user_id)->first();

					if(!empty($system_setting)){
						$email_from = $system_setting->email_deliver_from;
						$reply_to = $system_setting->email_reply_to;
					}else{
						$email_from = $reply_to = \config('app.mail');
					}

					// $emails = ['shruti.dhiman@imarkinfotech.com','govind.singh@imarkinfotech.com'];
					$emails = $email;

					file_put_contents(dirname(__FILE__).'/logs/emails_'.$value->request_id.'_'.date('Y-m-d H:i:s').'.txt',print_r($emails,true));

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
                        // }
					}
				}
				$emails =  $file_path = '';
			}
		}
	}
}