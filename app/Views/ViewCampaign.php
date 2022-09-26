<?php
  
namespace App\Views;
  
use Illuminate\Database\Eloquent\Model;
use URL;
use App\RegionalDatabse;
use App\SearchConsoleUrl;
use App\KeywordSearch;
use App\GoogleAnalyticsUsers;
use App\SearchConsoleUsers;
use App\User;
use DB;
use DateTime;
use Auth;
use Image;
use App\GoogleUpdate;
use App\Error;
use App\AuditTask;
use App\SemrushUserAccount;
 
class ViewCampaign extends Model
{
    public $table = "campaigns";


    protected $appends = array('Check_time_diff','campaign_tag','regional_db_flag','keyword_stats','manager_details','campaign_url','client_details','site_audit_status');

    public function getCheckTimeDiffAttribute(){
        $created_at = $this->created;
        if($created_at == null){
          $class = 'empty';
          return $class;
        }
        $start_date = new DateTime($created_at);
        $since_start = $start_date->diff(new DateTime(now()));



        if($since_start->d == '0' && $since_start->h == '0' && $since_start->i <= '1'){
          $class = 'loader';
        }else{
          $class = 'empty';
        }
      
        return $class;
    }

    public function getCampaignTagAttribute($request_id){
      $explode_tags = array();
      $tags = $this->tags;
      if(!empty($tags)){
        $explode_tags = explode(',',$tags);
      }
      return $explode_tags;
    }

    public function getRegionalDbFlagAttribute(){
        $getDbDetails = RegionalDatabse::where('short_name',$this->regional_db)->first();
        $link = URL::asset('/public/storage/database_flags/'.$getDbDetails->flag);
        return $link;
    }

    public function getKeywordStatsAttribute(){
        $results = KeywordSearch::
          select(
            DB::raw('sum(CASE WHEN ((position > 30 AND position <= 100)  AND (start_ranking = 0 or start_ranking > 100)) THEN 1 ELSE 0 END) AS since_hundred'),
            DB::raw('sum(CASE WHEN ((position <= 20 and position > 10)  AND (start_ranking > 20 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_twenty'),
            DB::raw('sum(CASE WHEN ((position <= 10 and position > 3) AND (start_ranking > 10 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_ten'),
            DB::raw('sum(CASE WHEN ((position <= 3 and position > 0) AND (start_ranking >= 4 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_three')
          )
          ->where('request_id',$this->request_id)
          ->first();
         return $results;
    }

    public function getManagerDetailsAttribute(){
        $managerDetails = User::
        whereRaw("find_in_set($this->request_id, restrictions)")
        ->select('id','name','profile_image','initial_background')
        ->where('role_id',3)
        ->first();

        $figure = '';
        $manager_name = '';
          if(isset($managerDetails)){
            if(isset($managerDetails->profile_image)){
              $image = '<img src="'.$managerDetails->profile_image.'">';
              $figure = '<figure uk-tooltip="title:'.$managerDetails->name.'; pos: top-center" class="project-manager">'.$image.'</figure>';
            }else{
              $words = explode(' ', $managerDetails->name);
              $initial =  strtoupper(substr($words[0], 0, 1));
             

              $figure = '<figure uk-tooltip="title:'.$managerDetails->name.'; pos: top-center" class="project-manager '.$managerDetails->initial_background.'"><figcaption>'.$initial.'</figcaption></figure>'; 
            }
             
          }
          
        return $figure;
    }

    public static function get_manager_image($value,$request_id){
      $managerDetails = User::get_manager_details($request_id);
      $figure = '';
      if(isset($managerDetails)){
        if(isset($managerDetails->profile_image)){
          $image = '<img src="'.$managerDetails->profile_image.'">';
          $figure = '<figure uk-tooltip="title:'.$value->get_manager_name($value->id).'; pos: top-center" class="project-manager">'.$image.'</figure>';
        }else{
          $words = explode(' ', $managerDetails->name);
          $initial =  strtoupper(substr($words[0], 0, 1));
         

          $figure = '<figure uk-tooltip="title:'.$value->get_manager_name($value->id).'; pos: top-center" class="project-manager '.$managerDetails->initial_background.'"><figcaption>'.$initial.'</figcaption></figure>'; 
        }
         
      }
      return $figure;
    }

    public static function get_manager_name($request_id){
      $managerDetails = User::get_manager_details($request_id);
      $manager_name = '';
      if(isset($managerDetails->name) && !empty($managerDetails->name)){
        $manager_name = $managerDetails->name;
      }
      return $manager_name;
    }

    public static function get_regional_db_location($regional_db){

   
        $searchlocation = RegionalDatabse::where('short_name',$regional_db)->first();
        if(isset($searchlocation) && ($searchlocation <> null)){
          $location =  $searchlocation->short_name;
        }else{
           $location = '.com';
        }
      
      return $location;
    }

    public function getCampaignUrlAttribute(){
    $url = $this->domain_url;
    $url = strpos($url, 'http') !== 0 ? "http://$url" : $url;

    if(filter_var($url, FILTER_VALIDATE_URL)) {
      return $url;
    } else {
      return '//'.$url;
    }

  }


  public function get_project_errors($request_id,$moduleType){
  $getErrors = 0;
  if($moduleType == 1){$moduleName = 'analytics';}elseif($moduleType == 2){$moduleName = 'search_console';}elseif($moduleType == 3){$moduleName = 'gmb';}elseif($moduleType == 4){$moduleName = 'adwords';}elseif($moduleType == 6){$moduleName = 'facebook';}elseif($moduleType == 5){$moduleName = 'ga4';}
  $update = GoogleUpdate::whereDate($moduleName,date('Y-m-d'))->where('request_id',$request_id)->first();
  if(empty($update)){
    $getErrors = Error::where('module',$moduleType)->where('request_id',$request_id)->whereDate('updated_at','=',date('Y-m-d',strtotime('-1 day')))->count();
  }
  return $getErrors;
}



public function getClientDetailsAttribute(){
    $managerDetails = User::
    whereRaw("find_in_set($this->request_id, restrictions)")
    ->select('id','name','profile_image','initial_background')
    ->where('role_id',4)
    ->first();

    $figure = '';
    $manager_name = '';
    if(isset($managerDetails)){
      if(isset($managerDetails->profile_image)){
        $image = '<img src="'.$managerDetails->profile_image.'">';
        $figure = '<figure uk-tooltip="title:'.$managerDetails->name.'; pos: top-center" class="project-manager">'.$image.'</figure>';
      }else{
        $words = explode(' ', $managerDetails->name);
        $initial =  strtoupper(substr($words[0], 0, 1));


        $figure = '<figure uk-tooltip="title:'.$managerDetails->name.'; pos: top-center" class="project-manager '.$managerDetails->initial_background.'"><figcaption>'.$initial.'</figcaption></figure>'; 
      }

    }

    return $figure;
  }


  public static function get_client_image($value,$request_id){
    $clientDetails = User::get_client_details($request_id);
    $figure = '';
    if(isset($clientDetails)){
      if(isset($clientDetails->profile_image)){
        $image = '<img src="'.$clientDetails->profile_image.'">';
        $figure = '<figure uk-tooltip="title:'.$value->get_client_name($value->id).'; pos: top-center" class="project-manager">'.$image.'</figure>';
      }else{
        $words = explode(' ', $clientDetails->name);
        $initial =  strtoupper(substr($words[0], 0, 1));


        $figure = '<figure uk-tooltip="title:'.$value->get_client_name($value->id).'; pos: top-center" class="project-manager '.$clientDetails->initial_background.'"><figcaption>'.$initial.'</figcaption></figure>'; 
      }

    }
    return $figure;
  }


   public static function get_client_name($request_id){
    $clientDetails = User::get_client_details($request_id);
    $client_name = '';
    if(isset($clientDetails->name) && !empty($clientDetails->name)){
      $client_name = $clientDetails->name;
    }
    return $client_name;
  }

  public function get_viewkey_link($user_id,$project_id){
      $link = '';
      $encrypted_id = base64_encode($project_id.'-|-'.$user_id.'-|-'.time());
      $user = SemrushUserAccount::select('share_key')->where('id',$project_id)->first();
      $host = \config('app.url');
      $link = $host.'project-detail/'.$user->share_key;
      return $link;
  }


  public function getSiteAuditStatusAttribute(){
    $campaign_id = $this->id;
    $audit_task = AuditTask::where('campaign_id',$campaign_id)->latest()->first();
    if($audit_task <> null && (!empty($audit_task))){
      return 'check';
    }else{
      return 'run';
    }
  }

}