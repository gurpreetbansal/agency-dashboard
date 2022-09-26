<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SemrushUserAccount;
use App\BackLinksData;
use App\BacklinkSummary;
use App\SemrushBacklinkSummary;

class BackLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BackLinks:serpstat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store backlinks data and summary';

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
      $domainDetails = SemrushUserAccount::
      whereHas('UserInfo', function($q){
        $q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
        ->where('subscription_status', 1);
      })
      ->where('status','0')
      ->select('id','user_id','domain_url','backlinks_cron_date','url_type','host_url')
      ->orderBy('id','desc')
      ->where(function ($query) {
        $query->where('backlinks_cron_date', '<=', date('Y-m-d'))
        ->orWhereNull('backlinks_cron_date');
      })
      ->get();

      file_put_contents(dirname(__FILE__)."/logs/backlink_cron.txt", print_r($domainDetails,true));

      $removeChar = ["https://", "http://", "/","www."];
      foreach($domainDetails as $details){
        $campaign_id = $details->id;
        $user_id = $details->user_id;

        if($details->url_type == 2){
          $domain_url = str_replace($removeChar, "", $details->host_url);
        }else{
          $domain_url = str_replace($removeChar, "", $details->domain_url);
        }
        $this->serpstatBackLinksSummary($campaign_id,$user_id,$domain_url);
      }   
    }


    private function serpstatBacklinks($campaign_id,$user_id,$domain_url){
      $data = [
        "id"=> 1,
        "method"=> "SerpstatBacklinksProcedure.getNewBacklinks",
        "params"=> [
          "query"=>$domain_url,
          "searchType"=> "domain_with_subdomains",
          "page"=> 1,
          "size"=> 20,
          "order"=> "desc"
        ]
      ];
      $dataResult = $this->http_curl_handler($data);
      $finalData = json_decode($dataResult);
         // die;
      if(isset($finalData->result)){
        $result_data = $finalData->result->data;
        foreach ($result_data as $key => $value) {
         $scraped_page = self::curl_data($value->url_from);
         $title = self::scrape_between($scraped_page, "<title>", "</title>");

         BacklinksData::create([
          'user_id'=>$user_id,
          'request_id'=>$campaign_id,
          'title'=>$title,
          'url_from'=>$value->url_from,
          'url_to'=>$value->url_to,
          'nofollow'=>$value->nofollow,
          'link_type'=>$value->link_type,
          'links_ext'=>$value->links_ext,
          'link_text'=>$value->link_text,
          'first_seen'=>$value->first_seen,
          'last_visited'=>$value->last_visited
        ]);

         $title = '';
       }

       SemrushUserAccount::where('id',$campaign_id)->update([
        'backlinks_cron_date'=>date('Y-m-d',strtotime('+1 week'))
      ]);
     }
   }
   
   private function serpstatBackLinksSummary($campaign_id,$user_id,$domain_url){

    // $semrush_api_key = \config('app.SEMRUSH_API_KEY');
    $semrush_api_key ='1ea96d770d48f5bece1e6248399a2363';
    $url = 'https://api.semrush.com/analytics/v1/?key='.$semrush_api_key.'&type=backlinks_overview&target='.$domain_url.'&target_type=root_domain&export_columns=ascore,total,domains_num,urls_num,ips_num,follows_num,nofollows_num,sponsored_num,ugc_num,texts_num,images_num';
    $final_data =  self::buildReport($url);
    // $final_data =  SemrushBacklinkSummary::buildReport(SemrushBacklinkSummary::cURL($url));
    if(isset($final_data) && count($final_data) > 0){
      $final_data = $final_data[0];
      SemrushBacklinkSummary::create([
        'user_id'=>$user_id,
        'request_id'=>$campaign_id,
        'ascore'=>$final_data['ascore']?:0,
        'total'=>$final_data['total']?:0,
        'domains_num'=>$final_data['domains_num']?:0,
        'urls_num'=>$final_data['urls_num']?:0,
        'ips_num'=>$final_data['ips_num']?:0,
        'follows_num'=>$final_data['follows_num']?:0,
        'nofollows_num'=>$final_data['nofollows_num']?:0,
        'sponsored_num'=>$final_data['sponsored_num']?:0,
        'ugc_num'=>$final_data['ugc_num']?:0,
        'texts_num'=>$final_data['texts_num']?:0,
        'images_num'=>$final_data['images_num']?:0
      ]);

    }

    $this->serpstatBacklinks($campaign_id,$user_id,$domain_url);
    SemrushUserAccount::where('id',$campaign_id)->update([
      'backlinks_cron_date'=>date('Y-m-d',strtotime('+1 week'))
    ]);
    SemrushBacklinkSummary::cron_GetBacklinksCount($campaign_id);
  }
  
  
  private function http_curl_handler($data){

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => \config('app.SERPSTAT_URL').\config('app.SERPSTAT_TOKEN'),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>json_encode($data),
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json"
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
  }

  public static  function getTitle($url) {
    $title = '';
    // Extract HTML using curl
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    // Load HTML to DOM Object
    $dom = new \DOMDocument();
    @$dom->loadHTML($data);

    // Parse DOM to get Title
    $nodes = $dom->getElementsByTagName('title');
    $title = @$nodes->item(0)->nodeValue;

    return $title;
  }


  public static function curl_data($url) {
        // Assigning cURL options to an array
    $options = Array(
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_FOLLOWLOCATION => TRUE,
      CURLOPT_AUTOREFERER => TRUE,
      CURLOPT_CONNECTTIMEOUT => 120,
      CURLOPT_TIMEOUT => 120,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",
      CURLOPT_URL => $url,
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
  }

  public static function scrape_between($data, $start, $end){
    $data = stristr($data, $start);
    $data = substr($data, strlen($start));
    $stop = stripos($data, $end);
    $data = substr($data, 0, $stop);
    return $data; 
  }




  public static function buildReport($url)
  {

    $reportData = self::cURL($url);
    // file_put_contents(dirname(__FILE__)."/logs/reportData.txt", print_r($reportData,true));
    if($reportData === false) return array();

    $lines = explode("\n", $reportData);
    if(sizeof($lines) == 0) return array();

    if(strpos($lines[0], 'ERROR:'))
    {
      throw new Exception('ERROR: ' . $reportData);
    }

    $grid = SemrushBacklinkSummary::splitCSVFields($lines);
    $firstRow = array_shift($grid);
    for($i=0;$i<sizeof($firstRow);$i++)
    {
      $firstRow[$i] = trim(strtolower(str_ireplace(' ', '_', $firstRow[$i])));
      $firstRow[$i] = str_ireplace('(%)', 'percent', $firstRow[$i]);
    } 

    $array = array();
    foreach($grid as $row)
    {

      $r = array();
      for($i=0;$i<sizeof($row);$i++)
      {
        if(isset($firstRow[$i]))
        {
          $r[$firstRow[$i]] = $row[$i];
        }
      }
      $array[] = $r;
    }
    return $array;    
  }

  public static function cURL($request)
  {
    // file_put_contents(dirname(__FILE__)."/logs/request.txt", print_r($request,true));
    $options = Array(
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_FOLLOWLOCATION => TRUE,
      CURLOPT_AUTOREFERER => TRUE,
      CURLOPT_CONNECTTIMEOUT => 120,
      CURLOPT_TIMEOUT => 120,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",
      CURLOPT_URL => $request
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $data = curl_exec($ch);
    curl_close($ch);
    // file_put_contents(dirname(__FILE__)."/logs/data.txt", print_r($data,true));
    return $data;
  }

}
