<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SemrushUserAccount;
use App\BacklinkSummary;
use App\SemrushBacklinkSummary;


class BackLinksData extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'backlinks_data';

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
      'user_id','request_id','title','url_from','url_to','nofollow','link_type','links_ext','link_text','first_seen','last_visited','status','deleted_at'
    ];

    public static function log_backlinks($user_id,$campaign_id){
      $domainDetails = SemrushUserAccount::select('id','user_id','domain_url','host_url','url_type')->where('id',$campaign_id)->first();
      $removeChar = ["https://", "http://", "/","www."];

      if($domainDetails->url_type == 2){
        $domain_url = str_replace($removeChar, "", $domainDetails->host_url);
      }else{
        $domain_url = str_replace($removeChar, "", $domainDetails->domain_url);
      }

      self::serpstatBackLinksSummary($campaign_id,$user_id,$domain_url);
        // self::serpstatBacklinks($campaign_id,$user_id,$domain_url);
    }



    public static function serpstatBacklinks($campaign_id,$user_id,$domain_url){
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
      $dataResult = self::http_curl_handler($data);
      $finalData = json_decode($dataResult);

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

    public static function serpstatBackLinksSummary_bkp($campaign_id,$user_id,$domain_url){

      $data = [
        "id"=> 1,
        "method"=> "SerpstatBacklinksProcedure.getSummary",
        "params"=> [
          "query"=>$domain_url
        ]
      ];

      $dataResult=    self::http_curl_handler($data);
      $finalData = json_decode($dataResult);


      if(isset($finalData->result)){
        $result_data = $finalData->result->data;

        BacklinkSummary::create([
          'user_id'=>$user_id,
          'request_id'=>$campaign_id,
          'referringDomains'=>$result_data->referringDomains,
          'referringSubDomains'=>$result_data->referringSubDomains,
          'referringLinks'=>$result_data->referringLinks,
          'noFollowLinks'=>$result_data->noFollowLinks,
          'doFollowLinks'=>$result_data->doFollowLinks,
          'referringIps'=>$result_data->referringIps,
          'referringSubnets'=>$result_data->referringSubnets,
          'domainZoneEdu'=>$result_data->domainZoneEdu,
          'domainZoneGov'=>$result_data->domainZoneGov,
          'typeText'=>$result_data->typeText,
          'typeImg'=>$result_data->typeImg,
          'typeRedirect'=>$result_data->typeRedirect,
          'mainPageLinks'=>$result_data->mainPageLinks,
          'domainRank'=>$result_data->domainRank,
          'facebookLinks'=>$result_data->facebookLinks,
          'pinterestLinks'=>$result_data->pinterestLinks,
          'linkedinLinks'=>$result_data->linkedinLinks,
          'vkLinks'=>$result_data->vkLinks
        ]);

        if($result_data->referringLinks > 0){
          self::serpstatBacklinks($campaign_id,$user_id,$domain_url);
        }



      }
      SemrushUserAccount::where('id',$campaign_id)->update([
        'backlinks_cron_date'=>date('Y-m-d',strtotime('+1 week'))
      ]);
      BacklinkSummary::cron_GetBacklinksCount($campaign_id);
    }


    public static function http_curl_handler($data){

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
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

      $data = curl_exec($ch);
      curl_close($ch);

      $dom = new \DOMDocument();
      @$dom->loadHTML($data);

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

    public static function serpstatBackLinksSummary($campaign_id,$user_id,$domain_url){
      $semrush_api_key = \config('app.SEMRUSH_API_KEY');
      $url = 'https://api.semrush.com/analytics/v1/?key='.$semrush_api_key.'&type=backlinks_overview&target='.$domain_url.'&target_type=root_domain&export_columns=ascore,total,domains_num,urls_num,ips_num,follows_num,nofollows_num,sponsored_num,ugc_num,texts_num,images_num';
      $final_data =  SemrushBacklinkSummary::buildReport(SemrushBacklinkSummary::cURL($url));

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

      sleep(1);
      self::serpstatBacklinks($campaign_id,$user_id,$domain_url);
      SemrushUserAccount::where('id',$campaign_id)->update([
        'backlinks_cron_date'=>date('Y-m-d',strtotime('+1 week'))
      ]);

      sleep(1);
      SemrushBacklinkSummary::cron_GetBacklinksCount($campaign_id);
    }
    
  }
