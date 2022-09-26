<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\ClientAuth;

use Auth;
use App\User;
use App\SharedAccess;
use App\SemrushUserAccount;
use App\AuditTask;
use App\AuditErrorList;


class AuditSyncController extends Controller {
	use ClientAuth;
	public function runAudit(Request $request){

		$domainDetails = SemrushUserAccount::
			whereHas('UserInfo', function($q){
				$q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
				->where('subscription_status', 1);
			})
			->doesntHave('auditSync')
			->where('status','0')
			->select('id','user_id','domain_url','backlinks_cron_date','url_type','host_url')
			->orderBy('id','desc')
			->limit(50)
			->get();


		foreach ($domainDetails as $key => $campaign) {
			
			$taskId = null;
			$taskType = 0;
			$postback_id = strtotime(date('Y-m-d H:m:s'));
			$post_array = array();
			$client = null;
			try {
				$client = $this->DFSAuth();
			} catch (RestClientException $e) {
				return json_decode($e->getMessage(), true);
			}
			$crawl_pages = $campaign->audit_crawl_pages ? $campaign->audit_crawl_pages : 50;

			$post_array[] = array(
				"target" => $campaign->host_url,
				"max_crawl_pages" => $crawl_pages,
				"load_resources" => true,
				"enable_javascript" => true,
				"store_raw_html"=>true,
				"custom_js" => "meta = {}; meta.url = document.URL; meta;",
				"pingback_url" => url('/postback-siteaudit?campaign_id='.$campaign->id.'&postback_id='.$postback_id)
				
			);

			try {
				$task_post_result = $client->post('/v3/on_page/task_post', $post_array);

				$response['status'] = '1'; // Insert Data Done
				$response['error'] = '0';
				$response['message'] = 'Request sent Successfully';
			} catch (RestClientException $e) {
				$response['status'] = '2'; 
				$response['error'] = '2';
				$response['message'] = $e->getMessage();
			}

			$taskId = isset($task_post_result['tasks'][0]['id']) ? $task_post_result['tasks'][0]['id'] : null;
			if($taskId <> null){
				$create = AuditTask::create([
					'user_id'=>$campaign->user_id,
					'campaign_id'=>$campaign->id,
					'task_id'=>$taskId,
					'crawled_url'=>$campaign->host_url,
					'postback_id'=>$postback_id,
				]);
			}

			/*dd($post_array);*/
		}

		print_r($create);
	} 

}