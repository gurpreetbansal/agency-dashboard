<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SemrushUserAccount;
use App\BacklinkSummary;
use App\Traits\ClientAuth;

class AuditTask extends Model {

  use ClientAuth;
  
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'audit_tasks';

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
    protected $fillable = [
      'user_id','campaign_id','postback_id','task_id','crawled_url','summary','pages','non_indexable','status'
  	];
    

    public static function createTask($client,$user_id,$campaign_id){

        $domainDetails = SemrushUserAccount::select('id','user_id','domain_url','host_url','url_type')->where('id',$campaign_id)->first();
        

        $postback_id = strtotime(date('Y-m-d H:i:s'));

        $post_array[] = array(
           "target" => $domainDetails->host_url,
           "max_crawl_pages" => 50,
           "load_resources" => true,
           "enable_javascript" => true,
           "custom_js" => "meta = {}; meta.url = document.URL; meta;",
           "pingback_url" => url('/postback-siteaudit?campaign_id='.$domainDetails->id.'&postback_id='.$postback_id)
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
            'user_id'=>Auth::user()->id,
            'campaign_id'=>$campaign_id,
            'postback_id'=>$postback_id,
            'crawled_url'=>$domainDetails->host_url,
            'task_id'=>$taskId,
          ]);
        }
     
    }

}