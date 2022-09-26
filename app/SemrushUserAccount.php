<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use URL;
use App\RegionalDatabse;
use App\SearchConsoleUrl;
use App\KeywordSearch;
use App\GoogleAnalyticsUsers;
use App\SearchConsoleUsers;
use App\CampaignSetting;
use App\Error;
use DB;
use DateTime;
use Auth;
use Image;

class SemrushUserAccount extends Model {


  protected $table = 'semrush_users_account';

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
    protected $fillable = ['user_id', 'domain_name', 'domain_url', 'host_url','domain_register', 'regional_db', 'clientName', 'status', 'token', 'google_analytics_id', 'google_account_id', 'google_property_id', 'google_profile_id', 'created', 'modified','google_ads_id','dashboard_type','google_console_id','console_account_id','console_property_id','console_view_id','seo_skip','ppc_skip','tags','gmb_analaytics_id','gmb_id','deleted_at','favicon','rank_search_engine','rank_location','rank_latitude','rank_longitude','rank_device','rank_language','project_logo','is_favorite','goal_completion_count','ecommerce_goals','backlinks_cron_date','tag_id','url_type','extra_keywords_cron_date','updated_at','notification_flag','audit_crawl_pages','share_key','moz_date','ga4_email_id','ga4_account_id','ga4_property_id'];


    public function getKeywordAlertAttribute()
    {
      $request_id = $this->id;
      $alert = 0;
      $alert_data = CampaignSetting::where('request_id',$request_id)->first();
      if(!empty($alert_data)) {
        $alert = 1;
      }
      return $alert;
    }

    public static function checkdomainUrl($url, $user_id) {
      $check = SemrushUserAccount::where('domain_url', 'like', '%' . $url . '%')->where('user_id', $user_id)->first();

      if (!empty($check)) {
        return '1';
      } else {
        return '0';
      }
    }

    public static function checkdomainUrlNew($url, $user_id) {
      $check = SemrushUserAccount::where('host_url',  $url)->where('user_id', $user_id)->where('status',0)->first();

      if (!empty($check)) {
        return '1';
      } else {
        return '0';
      }
    }
    public static function checkProjectName($project_name, $user_id) {
      $check = SemrushUserAccount::where('domain_name', $project_name)->where('user_id', $user_id)->where('status',0)->first();

      if (!empty($check)) {
        return '1';
      } else {
        return '0';
      }
    }


    public static function semrush_domain_organic_data($domain_url) {
      $client = \Silktide\SemRushApi\ClientFactory::create(config('app.SEMRUSH_API_KEY'));

      $result = $client->getDomainOrganic(
        $domain_url, [
          'database' => \Silktide\SemRushApi\Data\Database::DATABASE_GOOGLE_US
        ]
      );

      return $result;
    }
    
    public function MozData(){
      return $this->hasOne('App\Moz','request_id','id');
    }

    public function google_analytics_account(){
      return $this->hasOne('App\GoogleAccountViewData','id','google_analytics_id');
    }


    public function google_search_account(){
      return $this->hasOne('App\GoogleAccountViewData','id','console_account_id');
    }

    public function google_adwords_account(){
      return $this->hasOne('App\GoogleAdsCustomer','id','google_ads_campaign_id');
    }

    public function fbAccount(){
      return $this->hasOne('App\Social\SocialAccount','id','fbid');
    }

    public function fbPage(){
      return $this->hasOne('App\Social\FacebookUserPage','id','facebook_page_id');
    }

    public static function getUserDomainDetails($id){
      $result = SemrushUserAccount::where('id',$id)->where('status',0)->first();
      if(!empty($result)){
       return $result;
     } else{
       return false;
     }
   }


   public static function accountInfoById($user_id,$request_id){
    $info = SemrushUserAccount::where('user_id',$user_id)->where('id',$request_id)->first();	
    return $info;
  }


  public function ProfileInfo(){
    return $this->hasOne('App\ProfileInfo','request_id','id');
  }

  public function UserInfo(){
    return $this->hasOne('App\User','id','user_id');
  }

  public function auditSync(){
    return $this->hasOne('App\AuditTask','campaign_id','id');
  }




  public function Keywords(){
    return $this->hasMany('App\KeywordSearch','request_id','id');
  }

  public $timestamps = false;

  public static function get_camapign_logo($user_id,$request_id){
    $path  = 'public/storage/agency_logo/'.$user_id.'/'.$request_id.'/';

    if(file_exists($path)){
      $files1 = array_values(array_diff(scandir($path), array('..', '.')));

      if(!empty($files1)) {
        $image_url = URL::asset('public/storage/agency_logo/'.$user_id.'/'.$request_id.'/'.$files1[0]);
        $response['return_path']    =   $image_url; 
      }else{
        $response['return_path']    =   '';
      }
      return $response;           
    }
  }


  public static function get_camapign_logo_data($user_id,$request_id){
    $path  = 'public/storage/agency_logo/'.$user_id.'/'.$request_id.'/';

    if(file_exists($path)){
      $files1 = array_values(array_diff(scandir($path), array('..', '.')));

      if(!empty($files1)) {
        $image_url = URL::asset('public/storage/agency_logo/'.$user_id.'/'.$request_id.'/'.$files1[0]);
        $response    =   $image_url; 
      }          
    }else{
      $response    =    URL::asset('public/vendor/images/brand_logo.png');
    }
    return $response; 
  }

  public  function get_campaign_data(){
    return $this->hasOne('App\CampaignData','request_id','id');
  }

  public  function keywordDataCount(){
    // return $this->hasOne('App\CampaignData','request_id','id')->select('keywords_count','request_id');
   return $this->hasOne('App\CampaignData','request_id','id')->select(
    DB::raw('CASE WHEN keywords_count IS NULL THEN 0 ELSE keywords_count END AS keywords_count'),
    'request_id'      
  )->withDefault(['keywords_count'=>0]);
 }


 public static function get_regional_db_flag($regional_db){
  $getDbDetails = RegionalDatabse::where('short_name',$regional_db)->first();
  $link = URL::asset('/public/storage/database_flags/'.$getDbDetails->flag);
  return $link;
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

public static function getUserRole($user_id){
  $user = User::findorfail($user_id);
  return $user->role_id;       
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

public static function get_campaign_tags($request_id){
  $explode_tags = array();
  $tags = SemrushUserAccount::where('id',$request_id)->pluck('tags')->first();
  if(!empty($tags)){
    $explode_tags = explode(',',$tags);
  }
  return $explode_tags;
}


public static function get_favicon($sUrl)
{
  $sApiUrl = 'https://www.google.com/s2/favicons?domain='.$sUrl;
   // $sUrl = str_ireplace('www.', '', $sUrl);
    // $sDomainName = parse_url($sUrl, PHP_URL_HOST);

  return $sApiUrl;
}

public static function isDomainAvailible($domain)
{

               //initialize curl
 $curlInit = curl_init($domain);
 curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
 curl_setopt($curlInit,CURLOPT_HEADER,true);
 curl_setopt($curlInit,CURLOPT_NOBODY,true);
 curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

               //get answer
 $response = curl_exec($curlInit);

 curl_close($curlInit);


 if ($response) return true;

 return false;
}


public static function project_logo($request_id,$image_name){

  if (file_exists(\config('app.FILE_PATH').'public/storage/project_logo/'.$request_id)) {
    $path  = 'public/storage/project_logo/'.$request_id.'/';
    if(file_exists($path)){
      $image_url = URL::asset('public/storage/project_logo/'.$request_id.'/'.$image_name);
    }
  }else{
    $image_url   =   '';
  }

  return $image_url;   
}


public static function getAnalyticsAccount($account_id){
  $result ='';
  $result = GoogleAccountViewData::where('id',$account_id)->select('category_name')->first();
  if(!empty($result)){
    $result = $result->category_name;
  }
  return $result;
}

public static function getAdwordsAccount($account_id){
  $result ='';
  $result = GoogleAdsCustomer::where('id',$account_id)->select('name')->first();
  if(!empty($result)){
    $result = $result->name;
  }
  return $result;
}

public static function getConsoleAccount($account_id){
  $result ='';
  $result = SearchConsoleUrl::where('id',$account_id)->select('siteUrl')->first();
  if(!empty($result)){
    $result = $result->siteUrl;
  }
  return $result;
}

public static function remove_directory($dirPath) {

  if (! is_dir($dirPath)) {
    throw new InvalidArgumentException("$dirPath must be a directory");
  }
  if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
    $dirPath .= '/';
  }
  $files = glob($dirPath . '*', GLOB_MARK);
  foreach ($files as $file) {
    if (is_dir($file)) {
      self::remove_directory($file);
    } else {
      unlink($file);
    }
  }
  rmdir($dirPath);
}
/*public static function remove_directory($dir) {
  if (is_dir($dir)) {
    $objects = scandir($dir);
    foreach ($objects as $object) {
      if ($object != "." && $object != "..") {
        if (filetype($dir."/".$object) == "dir") 
          rrmdir($dir."/".$object); 
        else unlink   ($dir."/".$object);
      }
    }
    reset($objects);
    rmdir($dir);
  }
}*/

public static function keyword_stats($request_id){
  $results = KeywordSearch::
  select(
    DB::raw('sum(CASE WHEN ((position > 30 AND position <= 100)  AND (start_ranking = 0 or start_ranking > 100)) THEN 1 ELSE 0 END) AS since_hundred'),
    DB::raw('sum(CASE WHEN ((position <= 20 and position > 10)  AND (start_ranking > 20 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_twenty'),
    DB::raw('sum(CASE WHEN ((position <= 10 and position > 3) AND (start_ranking > 10 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_ten'),
    DB::raw('sum(CASE WHEN ((position <= 3 and position > 0) AND (start_ranking >= 4 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_three')
  )
  ->where('request_id',$request_id)
  ->first();
  return $results;
}


public static function check_time_diff($created_at){

  if($created_at == null){
    $class = 'empty';
    return $class;
  }
  $start_date = new DateTime($created_at);
  $since_start = $start_date->diff(new DateTime(now()));
  


  if($since_start->d == '0' &&$since_start->h == '0' && $since_start->i <= '1'){
    $class = 'loader';
  }else{
    $class = 'empty';
  }
  
  return $class;
}

public static function get_analytics_connected_email($account_id){
  $data = '';
  $result = GoogleAnalyticsUsers::select('id','email')->where('id',$account_id)->first();
  if(!empty($result)){
    $data = $result->email;
  }
  return $data;
}

public static function get_console_connected_email($account_id){
  $data = '';
  $result = SearchConsoleUsers::select('id','email')->where('id',$account_id)->first();
  if(!empty($result)){
    $data = $result->email;
  }
  return $data;
}


public static function get_gmb_connected_email($account_id){


  $data = '';
  $result = GoogleAnalyticsUsers::select('id','email')->where('id',$account_id)->first();
  if(!empty($result)){
    $data = $result->email;
  }
  return $data;
}

public function google_gmb_account($account_id){

  $result ='';
  $result = GmbLocation::where('id',$account_id)->select('location_name')->first();
  if(!empty($result)){
    $result = $result->location_name;
  }
  return $result;
}


public function get_assigned_projects($project_id){
 $data = User::where('parent_id',Auth::user()->id)->where('role_id',3)->whereRaw("find_in_set(".$project_id.",restrictions)")->first();
 if(!empty($data)){
  return true;
}else{
  return false;
}

}

public function get_assigned_projects_taken($project_id,$user_id){
 $data = User::where('parent_id',Auth::user()->id)->where('role_id',3)->whereRaw("find_in_set(".$project_id.",restrictions)")->where('id','!=',$user_id)->first();
 if(!empty($data)){
  return true;
}else{
  return false;
}

}

public function GoogleErrors(){
 return $this->hasOne('App\Error','request_id','id');
}

public static function make_project_json(){
  $user_id = User::get_parent_user_id(Auth::user()->id);
  $data  = SemrushUserAccount::select('id','domain_name','host_url','created')->where('user_id',$user_id)->where('status',0)->orderBy('domain_name','asc')->get();
  if (!file_exists(\config('app.FILE_PATH') . 'public/projects/' . $user_id)){
    mkdir(\config('app.FILE_PATH') . 'public/projects/' . $user_id, 0777, true);
  }
  file_put_contents(\config('app.FILE_PATH').'public/projects/'.$user_id.'/active_projects.json', print_r(json_encode($data,true),true));
} 

public static function resizeImage($image, $folderName,$name){
  $name = \Str::slug($name) . '_' . time(). '.' . $image->getClientOriginalExtension();
  if (!file_exists(\config('app.FILE_PATH').'storage/app/public/'.$folderName)) {
    mkdir(\config('app.FILE_PATH').'storage/app/public/'.$folderName, 0777, true);
  }
  $fileName =  'app/public'.'/'.$folderName.'/'. $name;
  Image::make($image)->save(storage_path($fileName));
  return $name;
}

public static function getErrorMessage($moduleType,$campaign_id){
  $getErrors = Error::where('module',$moduleType)->where('request_id',$campaign_id)->whereDate('updated_at','=',date('Y-m-d'))->orderBy('id','desc')->first();
  return $getErrors;
}


public static function get_created_date($campaign_id){
  $date = SemrushUserAccount::where('id',$campaign_id)->select('id','created','domain_register')->first();
  return $date;
}


public static function day_diff($domain_register){
  $now = time();
  $your_date = strtotime(date('Y-m-d',strtotime($domain_register)));
  $datediff = $now - $your_date;
  return round($datediff / (60 * 60 * 24));
}


/*July 14*/

public static function display_google_errorMessages($module,$campaign_id){
  $response = array();
  $getErrors = SemrushUserAccount::getErrorMessage($module,$campaign_id);
  if(!empty($getErrors)){
    $error = json_decode($getErrors->response, true);
    $currentPlayer = $error['message'];
    if($error['status'] == 2){
      $response['message'] =  $currentPlayer;
      $response['status'] = 'error';
    }else{
      $response['status'] = 'google-error';
      if(is_array($currentPlayer)){
        if (array_key_exists('error',$currentPlayer)){
         if(is_array($currentPlayer['error'])){
          if (array_key_exists('message',$currentPlayer['error'])){
            $response['message'] =  $currentPlayer['error']['message'];
          }elseif(array_key_exists('error_description',$currentPlayer)){
            $response['message'] =  $currentPlayer['error_description'];
          }
        }else{
          $response['message'] =  $currentPlayer['error'];
        }
      }else{
        $response['message'] =  $currentPlayer['message'];
      }
    }else{
      $response['message'] =  $currentPlayer;
    }
  }
}
return $response;
}

public function google_myBusiness_account(){
  return $this->hasOne('App\GmbLocation','id','gmb_id');
}


public static function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

public function auditSummary(){
  return $this->hasOne('App\SiteAuditSummary','campaign_id','id')->orderBy('id','DESC');
}

public static function getConnectedData($account_id){
    $result ='';
    $result = GoogleAnalyticAccount::where('id',$account_id)->select('display_name')->first();
    if(!empty($result)){
        $result = $result->display_name;
    }
    return $result;
}


}