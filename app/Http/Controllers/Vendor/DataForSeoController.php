<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use DataTables;
use Session;
use App\SemrushUserAccount;
use App\BacklinksData;
use App\BacklinkSummary;
use App\SemrushOrganicMetric;
use App\KeywordSearch;

use App\Traits\ClientAuth;
use Mail;
use App\ApiBalance;

class DataForSeoController extends Controller {

	use ClientAuth;

	public function ajax_organicKeywordRanking(Request $request){	
		$request_id = $request['campaignId'];
		$result  = SemrushOrganicMetric::where('request_id',$request_id)->orderBy('id','desc')->first();
		
		if(isset($result) && !empty($result)){
			$total_count = $result->total_count;
		} else{
			$total_count =0;
		}
		
			$resultOld =  SemrushOrganicMetric::where('request_id',$request_id)->orderBy('id','desc')->offset(1)->limit(1)->first();
			
				if(!empty($result->total_count) && !empty($resultOld->total_count)){
					if($resultOld->total_count > 2){
						
						if($resultOld->total_count){
							$organic_keywords = round(($result->total_count-$resultOld->total_count)/$resultOld->total_count * 100, 2);
						} else {
							 $organic_keywords = 0;
						}
					} else{
						$organic_keywords	=  100;
					}
				}else if(empty($result->total_count) && !empty($resultOld->total_count) ) {
					$organic_keywords	=  -100;
				} else if(!empty($result->total_count) && empty($resultOld->total_count) ) {
					$organic_keywords	=  100;
				} else{
					$organic_keywords	=  0;
				}
		
			return array('totalCount' => $total_count, 'organic_keywords' => $organic_keywords);
		
	}

	public function check_api_balance(){

		$array = array();
        $balance = 0;
		try {
            $client = $this->DFSAuth();
            $result = $client->get('/v3/appendix/user_data');
            echo "<pre>";
            print_r($result);
            die;

            if(isset($result['tasks'][0]['result'][0]) && !empty($result['tasks'][0]['result'][0])){
	            $balance = $result['tasks'][0]['result'][0]['money']['balance'];
	            $status_code = $result['status_code'];
	            $array = array('status_code'=>$status_code,'balance'=>$balance);

	            if(($status_code == '20000') && ($balance <= 50)){
	                $data = array('balance'=>$balance);
	                Mail::send(['html' => 'mails/dfs_balance'], $data, function($message) {
	                     $message->to('shruti.dhiman@imarkinfotech.com', 'Shruti Dhiman')->subject('Balance Alert - Data For Seo');
	                     $message->from(\config('app.mail'), 'Agency Dashboard');
	                });

	                 Mail::send(['html' => 'mails/dfs_balance_new'], $data, function($message) {
	                     $message->to('ishan@imarkinfotech.com', 'Ishan Gupta')->subject('Balance Alert - Data For Seo');
	                     $message->from(\config('app.mail'), 'Agency Dashboard');
	                });

	              $email_sent_flag =1;
	              $email_sent_on =now();
	            }else{
	                $email_sent_flag =0;
	                $email_sent_on = NULL;
	            }

	            ApiBalance::where('name','DFS')->update([
	                'balance'=>$balance,
	                'email_sent'=>$email_sent_flag,
	                'email_sent_on' =>$email_sent_on,
	                'status_code'=>$result['tasks'][0]['status_code'],
	                'status_message'=>$result['tasks'][0]['status_message']
	            ]);
	        }else{
	        	ApiBalance::where('name','DFS')->update([
	                'status_code'=>$result['tasks'][0]['status_code'],
	                'status_message'=>$result['tasks'][0]['status_message']
	            ]);
	        }
        }catch(RestClientException $e){
            return json_decode($e->getMessage(), true);
        }
	}

	public function ajaxSpyglass(Request $request,$domain=null){

		$encription = base64_decode($domain);
		$encrypted_id = explode('-|-',$encription);
		$keyword_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];
		$current_time = $encrypted_id[2];

		$data = KeywordSearch::where('user_id',$user_id)->where('id',$keyword_id)->first();
		
		$array = array();
        $balance = 0;

        if($data == null){
        	return json_encode(array('this keyword page expired.'));
        }

        if(!empty($data->lat) && !empty($data->long)){
			$location = $data->lat.','.$data->long;
			$locationType = "location_coordinate";
		}else{
			if(empty($data->lat) || empty($data->long)){
				$updateLatLong = KeywordSearch::updateKeywordLocationLatLong($request['selected_ids'],$data->canonical);
				
				$location = $updateLatLong;
				$locationType = "location_coordinate";
			}else{
				$location = $data->canonical;
				$locationType = "location_name";
			}
		}
        $post_array[] = array(
		  "language_name" => $data->language,
		  $locationType => $location,
		  "se_domain" => $data->region,
		  "domain" => $data->host_url,
		  "keyword" => mb_convert_encoding($data->keyword, "UTF-8")
		);

		try {
            $client = $this->DFSAuth();
           /* $result = $client->get('/v3/serp/google/languages');*/

           	/*$urlData = url('/').'/app/Http/Controllers/Vendor/logs/result.html'; 
           	$dom = file_get_contents($urlData);*/
           	
           	$result = $client->post('/v3/serp/google/organic/live/html', $post_array);
           	$dom = $result['tasks'][0]['result'][0]['items'][0]['html']; 
          	

           	return view('vendor.spyglass-ajax',compact('dom','data'))->render();
  			/*print_r($result['tasks'][0]['result'][0]['items'][0]['html']);*/
             return json_encode($dom);
        }catch(RestClientException $e){
            return json_decode($e->getMessage(), true);
        }

	}

	public function spyglass(Request $request,$domain=null){

		$encription = base64_decode($domain);
		$encrypted_id = explode('-|-',$encription);
		$keyword_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];
		$current_time = $encrypted_id[2];

		$data = KeywordSearch::where('user_id',$user_id)->where('id',$keyword_id)->first();
		
		$array = array();
        $balance = 0;
		
		return view('vendor.spyglass',compact('data','domain'));
	}
}