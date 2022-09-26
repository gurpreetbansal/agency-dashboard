<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Http\Request;
use App\SemrushUserAccount;
use App\User;
use App\GoogleAnalyticsUsers;
use App\GoogleAnalyticAccount;
use App\GoogleUpdate;
use App\Error;
use Exception;

class GoogleAnalytics4 extends Command
{
/**
* The name and signature of the console command.
*
* @var string
*/
protected $signature = 'Google:Analytics4';

/**
* The console command description.
*
* @var string
*/
protected $description = 'Store analytics 4 data for particular campaign.';

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
	$result = SemrushUserAccount::
	whereHas('UserInfo', function($q){
		$q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
		->where('subscription_status', 1);
	}) 
	->whereNotNull('ga4_email_id')
	->orderBy('id','desc')
	->where('status',0)
	->where(function($q){
		$q->whereRaw("exists (select * from `google_updates` where `semrush_users_account`.`id` = `google_updates`.`request_id` and (DATE(`ga4`) <> '".date('Y-m-d')."' or ga4 IS NULL))  or not exists (select * from `google_updates` where `semrush_users_account`.`id` = `google_updates`.`request_id`)");
	})
	->whereDoesntHave('GoogleErrors', function ($q) {
		$q->where('module',5)
		->whereDate('updated_at',date('Y-m-d'));
	})
	->get();

	if(isset($result) && !empty($result) && count($result) > 0){
		$start_date = date('Y-m-d',strtotime('-4 year'));
		$week_start_date = date('Y-m-d',strtotime('-7 day'));
		$end_date = date('Y-m-d',strtotime('-1 day'));
		$response = array();


		foreach($result as $key=>$value){
			$user_id = User::get_parent_user_id($value->user_id);
			$get_google_user = GoogleAnalyticsUsers::where('user_id',$user_id)->where('id',$value->ga4_email_id)->first();
			$refresh_token = $get_google_user->google_refresh_token;
			$service = GoogleAnalyticAccount::get_beta_service_client($refresh_token);
			$get_property = GoogleAnalyticAccount::where('id',$value->ga4_property_id)->first();

			$check_week_data = GoogleAnalyticAccount::check_week_data($service,$get_property->property_id,$week_start_date,$end_date);
				if(isset($check_week_data['status']) && $check_week_data['status'] != 1){
					Error::updateOrCreate(
						['request_id' => $value->id,'module'=> 5],
						['response'=> json_encode($check_week_data['message']),'request_id' => $value->id,'module'=> 5]
					);
				}else{
					$log_data = GoogleAnalyticAccount::log_ga4_data($service,$get_property->property_id,$start_date,$end_date,$value->id);

					$ifErrorExists = Error::removeExisitingError(5,$value->id);
					if(!empty($ifErrorExists)){
						Error::where('id',$ifErrorExists->id)->delete();
					}
					GoogleUpdate::updateTiming($value->id,'ga4','ga4_type','1');

				} //end else
				sleep(1);
			} //end foreach
		}
	}
}