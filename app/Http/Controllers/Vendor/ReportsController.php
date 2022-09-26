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
use Auth;
use DB;
use Mail;
use App\GlobalSetting;


class ReportsController extends Controller {

	public function reports_schedule() {

		$data = GlobalSetting::uploading_changes();
		if($data == true || $data == 1){
			return \View::make('errors.uploading_changes');
		}
		$user_id = User::get_parent_user_id(Auth::user()->id);
		if(\Request::segment(1) !== 'profile-settings'){
			$check = User::check_subscription($user_id); 
			if($check == 'expired'){
				return redirect()->to('/dashboard');
			}
		}  
		return view('vendor.reports.index');
	}
	
	public function ajax_fetch_campaigns(){
		$response = array();
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$projects = ViewCampaign::select('id','domain_name','host_url')->where('user_id',$user_id)->where('status',0)->get();
		$select ='';
		if(!empty($projects) && (count($projects) > 0)){
			foreach ($projects as $key => $value) {
				$select.='<option value="'.$value->id.'">'.$value->host_url.'</option>';
			}
		}
		$response['select'] = $select;

		return response()->json($response);
	}

	public function ajax_create_schedule_report(Request $request){

		if($request->has('full_report')){
			$full_report = 'on';
		}else{
			$full_report = 'off';
		}

		$user_id = User::get_parent_user_id(Auth::user()->id);
		$validator = Validator::make($request->all(), [
			"add_recipient_email"    => "required|array",
			"add_recipient_email.*"  => "required|string|distinct|email",
			"add_project_list" => "required",
			"add_subject" => "required",
			"add_text" => "required"
		]);

		$validator->after(function ($validator)use($request) {
			if(!$request->has('full_report') && !$request->has('Keywords_report')){ 
				$validator->errors()->add('report_options', 'Select the report type you want to schedule reporting for.');
			}
			if($request->has('full_report')){
				if(($request->full_report_add_rotation === '' || $request->full_report_add_rotation === null) && ($request->full_report_add_day === '' || $request->full_report_add_day === null)){
					$validator->errors()->add('full_report_options', 'One or both of these options can be set');
				}
			}

			if($request->has('Keywords_report')){
				if(($request->keyword_report_add_rotation === '' || $request->keyword_report_add_rotation === null) && ($request->keyword_report_add_day === '' || $request->keyword_report_add_day === null)){
					$validator->errors()->add('keyword_report_options', 'One or both of these options can be set');
				}
			}
		});

		if ($validator->fails()) {
			$array = array();
			$data_array = $validator->messages()->getMessages();
			foreach($validator->messages()->getMessages() as $field_name => $messages) {
				$explode = explode('.',$field_name);
				if(isset($explode[1])){
					$array[$explode[0]][$explode[1]] = $explode[1];
				}else{
					$array[$explode[0]] = $messages[0];
				}
			} 

			$response['status'] = 0;
			$response['message'] = $array;
			return response()->json($response);
		} else {
			$projects = explode(',',$request->add_project_list);
			$finalstring =  array_map('trim', $request->add_recipient_email);			
			if($request->has('full_report')){
				foreach($projects as $key=>$value){		
					$data = ScheduleReport::where('request_id',$value)->where('format',$request->full_add_report_format)->where('report_type',1)->where('user_id',$user_id)->first();
					if(empty($data)){
						ScheduleReport::create([
							'user_id'=>$user_id,
							'email' =>implode(', ',$request->add_recipient_email),
							'subject' => $request->add_subject,
							'mail_text' => $request->add_text,
							'request_id'=>$value,
							'rotation'=>($request->full_report_add_rotation)?$request->full_report_add_rotation:NULL,
							'day'=> ($request->full_report_add_day)?$request->full_report_add_day:NULL,
							'report_type'=>1,
							'format'=>$request->full_add_report_format,
							'next_delivery'=>date('Y-m-d'),
							'deleted_at'=>null,
							'status'=>1
						]);
					}else{	
						$emailData = $this->EmailDiff($value,$finalstring);
						$concatRotation = $this->concatRotation($value,$request->full_report_add_rotation);
						$concatDay = $this->concatDay($value,$request->full_report_add_day);


						if(empty($emailData)){
							$finalEmails = implode(',',$request->add_recipient_email);
						}else{
							$emails = implode(', ',$emailData);
							$finalEmails = 	DB::raw("CONCAT(IFNULL(email, ''), ', " . $emails . "')");
						}

						ScheduleReport::where('id',$data->id)->update([
							'user_id'=>$user_id,
							'email' =>$finalEmails,
							'request_id'=>$value,
							'subject' => $request->add_subject,
							'mail_text' => $request->add_text,
							'rotation'=>$concatRotation,
							'day'=>$concatDay,
							'report_type'=>1,
							'format'=>$request->full_add_report_format,
							//'next_delivery'=>date('Y-m-d'),
							'deleted_at'=>null,
							'status'=>1
						]);

						sleep(1);

						$cal_data = ScheduleReport::where('id',$data->id)->first();
						
						ScheduleReport::where('id',$cal_data->id)->update([
							'next_delivery'=>ScheduleReport::calculateDate($cal_data)
						]);
					}
				}
			}

			if($request->has('Keywords_report')){
				foreach($projects as $key=>$value){		
					$data = ScheduleReport::where('request_id',$value)->where('format',$request->keyword_add_report_format)->where('report_type',2)->where('user_id',$user_id)->first();
					if(empty($data)){
						ScheduleReport::create([
							'user_id'=>$user_id,
							'email' =>implode(', ',$request->add_recipient_email),
							'subject' => $request->add_subject,
							'mail_text' => $request->add_text,
							'request_id'=>$value,
							'rotation'=>($request->keyword_report_add_rotation)?$request->keyword_report_add_rotation:NULL,
							'day'=> ($request->keyword_report_add_day)?$request->keyword_report_add_day:NULL,
							'report_type'=>2,
							'format'=>$request->keyword_add_report_format,
							'next_delivery'=>date('Y-m-d'),
							'deleted_at'=>null,
							'status'=>1
						]);
					}else{	
						$emailData = $this->EmailDiff($value,$finalstring);
						$concatRotation = $this->concatRotation($value,$request->keyword_report_add_rotation);
						$concatDay = $this->concatDay($value,$request->keyword_report_add_day);
						//$emails =implode(', ',$emailData);


						if(empty($emailData)){
							$finalEmails = implode(',',$request->add_recipient_email);
						}else{
							$emails = implode(', ',$emailData);
							$finalEmails = 	DB::raw("CONCAT(IFNULL(email, ''), ', " . $emails . "')");
						}
						ScheduleReport::where('id',$data->id)->update([
							'user_id'=>$user_id,
							'email' =>$finalEmails,
							'request_id'=>$value,
							'subject' => $request->add_subject,
							'mail_text' => $request->add_text,
							'rotation'=>$concatRotation,
							'day'=>$concatDay,
							'report_type'=>2,
							'format'=>$request->keyword_add_report_format,
							//'next_delivery'=>date('Y-m-d'),
							'deleted_at'=>null,
							'status'=>1
						]);

						sleep(1);

						$cal_data = ScheduleReport::where('id',$data->id)->first();
						
						ScheduleReport::where('id',$cal_data->id)->update([
							'next_delivery'=>ScheduleReport::calculateDate($cal_data)
						]);
					}
				}
			}
			$response['status'] = 1;
			$response['message'] = 'Report successfully added.';
			return response()->json($response);
		}
	}

	private function concatRotation($value,$requested_rotation){
		$report = ScheduleReport::select('rotation')->where('request_id',$value)->first();
		if($report->day <> null){
			$rotation = DB::raw("CONCAT(IFNULL(rotation, ''), ', " . $requested_rotation . "')");
		}else{
			$rotation = $requested_rotation;
		}
		return $rotation;
	}

	private function concatDay($value,$requested_days){
		$report = ScheduleReport::select('day')->where('request_id',$value)->first();

		if($report->day <> null){
			//$existed_days = explode(',',$report->day);
			$requested_days = explode(',',$requested_days);
			// $output = array_merge(array_diff($existed_days, $requested_days), array_diff($requested_days, $existed_days));
			$output = array_unique($requested_days);
			if(!empty($output)){
				sort($output);
				$day = implode(',',$output);
				$days = rtrim($day, ',');
			}else{
				$days = null;
			}
			
		}else{
			$days = ($requested_days)?$requested_days:NULL;
		}
		return $days;
	}

	private function EmailDiff($value,$finalstring){
		$report = ScheduleReport::where('request_id',$value);
		$report->where(function($report) use($finalstring){
			for( $i=0; $i>=count($finalstring); $i++ ) {
				$report->orWhereRaw( "find_in_set('{$finalstring[$i]}' , schedule_reports.email)" );
			}
		});
		$report = $report->first();
		$emails = explode(',',$report->email);

		if($emails <> null && $emails <> ''){
			$newKeywords = array_diff($finalstring,$emails);
		}else{
			$newKeywords = $finalstring;
		}

		return $newKeywords;
	}

	public function ajax_schedule_report_list(Request $request){
		if($request->ajax())
		{
			$sortBy = $request['column_name'];
			$sortType = $request['order_type'];
			$limit = $request['limit'];	
			$search = $request['search'];	
			$result = $this->schedule_report_data($limit,$sortBy,$sortType,$search);
			return view('vendor.reports.table', compact('result'))->render();
		}
	}

	public function ajax_schedule_report_pagination(Request $request){
		if($request->ajax())
		{				
			$sortBy = $request['column_name'];
			$sortType = $request['order_type'];
			$limit = $request['limit'];	
			$search = $request['search'];	
			$result = $this->schedule_report_data($limit,$sortBy,$sortType,$search);
			return view('vendor.reports.pagination', compact('result'))->render();
		}
	}

	private function schedule_report_data($limit,$sortBy,$sortType,$search){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$sortType = $sortType !== 'undefined' ? $sortType : 'asc' ;
		$sortBy = $sortBy !== 'undefined' ? $sortBy : 'created_at' ;
		$field = ['host_url','clientName'];
		$result = ScheduleReport::where('user_id',$user_id)
		->where('status',1);
		if(!empty($search)){
			$result->where(function($query) use ($user_id,$search,$field){
				$query->where('email', 'LIKE',  '%' . $search .'%');
				$query->orWhereHas('SemrushUserData', function($query) use ($user_id,$search,$field){
					$query->where(function($query) use ($user_id,$search,$field){
						for ($i = 0; $i < count($field); $i++){
							$query->orwhere($field[$i], 'LIKE',  '%' . $search .'%');
						}      
					});
				});
			});
		}

		if($sortBy == 'host_url'){
			if($sortType == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(SemrushUserAccount::select("host_url")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));
		}

		if($sortBy == 'clientName'){
			if($sortType == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(SemrushUserAccount::select("clientName")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));
		}

		if($sortBy == 'recipient'){
			$result->orderBy('email',$sortType);
		}

		if($sortBy == 'frquency'){
			$result->orderBy('rotation',$sortType)->orderBy('day',$sortType);
		}

		if($sortBy == 'last_delivery'){
			$result->orderBy('last_delivery',$sortType);
		}

		if($sortBy == 'next_delivery'){
			$result->orderBy('next_delivery',$sortType);
		}

		if($sortBy == 'format'){
			$result->orderBy('format',$sortType);
		}

		if($sortBy == 'report_type'){
			$result->orderBy('report_type',$sortType);
		}

		if($sortBy == 'created_at'){
			$result->orderBy('created_at',$sortType);
		}

		$searchData = $result
		->paginate($limit);	

		return $searchData;
	}

	public function ajax_remove_scheduled_report(Request $request){
		$response =  array();
		$delete = ScheduleReport::where('id',$request->report_id)->update([
			'deleted_at'=>now(),
			'status'=>0,
			'last_delivery'=>null
		]);
		if($delete){
			$response['status'] = 1;
			$response['message'] = 'Report removed successfully';
		}else{
			$response['status'] = 0;
			$response['message'] = 'Error deleting report.';
		}

		return response()->json($response);
	}

	public function ajax_send_report_now(Request $request){
		$response = array();
		$data = ScheduleReport::where('id',$request->report_id)->first();

		if($data->report_type === 1){
			$type = 'seo';
		}else{
			$type = 'livekeyword';
		}


		$key = base64_encode($data->request_id.'-|-'.$data->user_id.'-|-'.time());

		$dominurl = parse_url($data->project_name->host_url);
		
		if($data->format === 1){
			$filename = $dominurl['path'].'-'.date('D-M-Y').'.pdf';
			ScheduleReport::downloadPdf($key,$filename,$type,'report_downloads_now');			
		}

		if($data->format === 2){
			$filename = $dominurl['path'].'-'.date('D-M-Y').'.xlsx';
			ScheduleReport::downloadCsv($data->request_id,$filename,'report_downloads_now');			
		}

		$send = SendScheduleReport::create([
			'report_id'=> $request->report_id,
			'request_id' => $data->request_id,
			'file_name'=>$filename
		]);


		if($send){
			$response['status'] = 1;
			$response['message'] = 'Your report has been successfully emailed out';
		}else{
			$response['status'] = 0;
			$response['message'] = 'Please try again.';
		}
		return response()->json($response);
	}

	public function ajax_get_scheduled_report_history(Request $request){
		$report_id = $request->report_id;
		$data = ScheduleReportHistory::where('report_id',$report_id)->orderBy('id','desc')->get();
		$html = '';
		if(isset($data) && count($data) > 0){
			foreach($data as $key=>$value){
				$html .='<tr><td>'.$value->sent_on.'</td><td>'.$value->email.'</td><td><i class="fa fa-paper-plane"></i> '.ScheduleReportHistory::calculateTime($value->created_at).'</td></tr>';
			}
		}else{
			$html .='<tr><td colspan="3"><center>No Report History</center></td></tr>';
		}
		return $html;
	}

	public function ajax_get_scheduled_report($domain_name,$request_id){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$projects = ViewCampaign::select('id','domain_name','host_url')->where('user_id',$user_id)->where('status',0)->get();
		$result = ScheduleReport::where('id',$request_id)->first();
		return \View::make('vendor.reports.render_edit_section',['result'=>$result,'projects'=>$projects]);
	}

	public function ajax_update_schedule_report(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$validator = Validator::make($request->all(), [
			"add_recipient_email"    => "required|array",
			"add_recipient_email.*"  => "required|string|distinct|email",
			"add_project_list" => "required",
			"add_subject" => "required",
			"add_text" => "required"
		]);

		$validator->after(function ($validator)use($request) {
			if(!$request->has('full_report') && !$request->has('Keywords_report')){ 
				$validator->errors()->add('report_options', 'Select the report type you want to schedule reporting for.');
			}

			if($request->has('full_report')){
				if(($request->full_report_add_rotation === '' || $request->full_report_add_rotation === null) && ($request->full_report_add_day === '' || $request->full_report_add_day === null)){
					$validator->errors()->add('full_report_options', 'One or both of these options can be set');
				}
			}

			if($request->has('Keywords_report')){
				if(($request->keyword_report_add_rotation === '' || $request->keyword_report_add_rotation === null) && ($request->keyword_report_add_day === '' || $request->keyword_report_add_day === null)){
					$validator->errors()->add('keyword_report_options', 'One or both of these options can be set');
				}
			}
		});

		if ($validator->fails()) {
			$array = array();
			$data_array = $validator->messages()->getMessages();
			foreach($validator->messages()->getMessages() as $field_name => $messages) {
				$explode = explode('.',$field_name);
				if(isset($explode[1])){

					$array[$explode[0]][$explode[1]] = $explode[1];
				}else{
					$array[$explode[0]] = $messages[0];
				}
			} 

			$response['status'] = 0;
			$response['message'] = $array;
			return response()->json($response);
		} else {
			$projects = explode(',',$request->add_project_list);
			$finalstring =  array_map('trim', $request->add_recipient_email);

			foreach($projects as $key=>$value){	
				if($request->has('full_report')){
					$add_day = $request->full_report_add_day;
					$add_rotation = $request->full_report_add_rotation;
					$add_report_type = 1;
					$add_report_format = $request->full_add_report_format;
				}
				if($request->has('Keywords_report')){
					$add_day = $request->keyword_report_add_day;
					$add_rotation = $request->keyword_report_add_rotation;
					$add_report_type = 2;
					$add_report_format = $request->keyword_add_report_format;
				}

				$existing_data = ScheduleReport::where('id','!=',$request->report_id)->where('request_id',$value)->where('report_type',$add_report_type)->where('format',$add_report_format)->first();

				if(empty($existing_data)){
					$data = ScheduleReport::where('request_id',$value)->where('format',$add_report_format)->where('user_id',$user_id)->where('status',1)->first();
					if(empty($data)){
						ScheduleReport::create([
							'user_id'=>$user_id,
							'email' =>implode(', ',$request->add_recipient_email),
							'request_id'=>$value,
							'subject' => $request->add_subject,
							'mail_text' => $request->add_text,
							'rotation'=>$add_rotation,
							'day'=>($add_day === '')?NULL:$add_day,
							'report_type'=>$add_report_type,
							'format'=>$add_report_format,
							'next_delivery'=>date('Y-m-d'),
							'deleted_at'=>null,
							'status'=>1
						]);
					}else{	
						$concatDay = $this->concatDay($value,$add_day);
						ScheduleReport::where('id',$data->id)->update([
							'user_id'=>$user_id,
							'email' =>implode(', ',$request->add_recipient_email),
							'request_id'=>$value,
							'subject' => $request->add_subject,
							'mail_text' => $request->add_text,
							'rotation'=>$add_rotation,
							'day'=>($concatDay)?:null,
							'report_type'=>$add_report_type,
							'format'=>$add_report_format,
							//'next_delivery'=>date('Y-m-d'),
							'deleted_at'=>null,
							'status'=>1
						]);

						sleep(1);

						$cal_data = ScheduleReport::where('id',$data->id)->first();
						
						ScheduleReport::where('id',$cal_data->id)->update([
							'next_delivery'=>ScheduleReport::calculateDate($cal_data)
						]);
					}


					$response['status'] = 1;
					$response['message'] = 'Report successfully added.';
				}else{
					$report_type = ($existing_data->format == 1)?'PDF':'CSV';
					$response['status'] = 2;
					$response['message'] = $report_type.' version already exists. Do you want to replace it?';
					$response['delete_report_id'] = $request->report_id;
					$response['report_id'] = $existing_data->id;
				}
			}
			
			return response()->json($response);
		}
	}


	public function ajax_update_existing_reports(Request $request){

		$user_id = User::get_parent_user_id(Auth::user()->id);
		$projects = explode(',',$request->add_project_list);
		if(isset($projects) && !empty($projects)){ 
			foreach($projects as $key=>$value){

				if($request->has('full_report')){
					$add_day = $request->full_report_add_day;
					$add_report_format = $request->full_add_report_format;
					$add_rotation = $request->full_report_add_rotation;
					$add_report_type = 1;
				}
				if($request->has('Keywords_report')){
					$add_day = $request->keyword_report_add_day;
					$add_rotation = $request->keyword_report_add_rotation;
					$add_report_type = 2;
					$add_report_format = $request->keyword_add_report_format;
				}


				$concatDay = $this->concatDay($value,$add_day);
				ScheduleReport::where('id',$request->report_id)->update([
					'user_id'=>$user_id,
					'email' =>implode(', ',$request->add_recipient_email),
					'request_id'=>$value,
					'subject' => $request->add_subject,
					'mail_text' => $request->add_text,
					'rotation'=>$add_rotation,
					'day'=>($concatDay)?:null,
					'report_type'=>$add_report_type,
					'format'=>$add_report_format,
					'next_delivery'=>date('Y-m-d'),
					'deleted_at'=>null,
					'status'=>1
				]);
			}

			ScheduleReport::where('id',$request->delete_report_id)->update([
				'deleted_at'=>now(),
				'status'=>0
			]);

			$response['status'] = 1;
			$response['message'] = 'Report successfully added.';
		}else{
			$response['status'] = 0;
			$response['message'] = 'Error, try again later.';
		}
		return response()->json($response);
	}
}