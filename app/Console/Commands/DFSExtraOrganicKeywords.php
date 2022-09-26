<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SemrushUserAccount;
use App\SemrushOrganicSearchData;
use App\SemrushOrganicMetric;
use App\ActivityLog;

use App\Traits\ClientAuth;
use App\RegionalDatabse;


class DFSExtraOrganicKeywords extends Command
{
  use ClientAuth;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DFS:ExtraOrganicKeywords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store Extra Organic Keyword for DFS.';

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
      $client = null;
      $domainDetails = SemrushUserAccount::whereHas('UserInfo', function($q){
        $q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
        ->where('subscription_status', 1);
      })  
      ->where('status','0')
      ->select('id','user_id','domain_url','host_url','url_type','extra_keywords_cron_date','regional_db','rank_location')
      ->where(function ($query) {
        $query->where('extra_keywords_cron_date', '<=', date('Y-m-d'))
        ->orWhereNull('extra_keywords_cron_date');
      })
      // ->limit(10)
      ->get();

      file_put_contents(dirname(__FILE__).'/logs/dfs_keywords.txt',print_r($domainDetails,true));
      
      if(!empty($domainDetails)){
        foreach($domainDetails  as $details){
          $results = SemrushOrganicSearchData::where('request_id',$details->id)->whereDate('created_at',date('Y-m-d'))->first();

          $removeChar = ["https://", "http://", "/","www."];
          if($details->url_type == 2){
            $http_referer = str_replace($removeChar, "", $details->host_url);
          }else{
            $http_referer = str_replace($removeChar, "", $details->domain_url);
          }


          if($details->rank_location !== null){
            $rd_data = RegionalDatabse::select('country','language')->where('short_name',$details->regional_db)->first();
                // if($rd_data->country <> null && $rd_data->language <> null){
                //   $location_name = $rd_data->country; 
                //   $language = $rd_data->language;
                // }else{
                //   $location_name = 'United States'; 
                //   $language = 'English';
                // }
                $location_name = ($rd_data->country <> NULL)?$rd_data->country:'United States';
                $language = ($rd_data->language <> NULL)?$rd_data->language:'English';
          }else{
            $location_name = 'United States'; 
            $language = 'English';
          }

          if(empty($results)){

            $client = $this->DFSAuth();
            $post_arrays[] = array(
              "target" => $http_referer,
              "language_name" => $language,
              "location_name"=>$location_name,
              "filters" => [
                ["keyword_data.keyword_info.search_volume", "<>", 0],
                "and",
                [
                  ["ranked_serp_element.serp_item.type", "<>", "paid"],
                  "or",
                  ["ranked_serp_element.serp_item.is_malicious", "=", false]
                ]
              ],
                        // "order_by" => ["keyword_data.serp_item.rank_group,ASC","keyword_data.keyword_info.search_volume,desc"],
              "limit"=>700
            );

            try {
              $ranked_keywords = $client->post('/v3/dataforseo_labs/ranked_keywords/live', $post_arrays);
            } catch (RestClientException $e) {
              return $e->getMessage();
            }

            file_put_contents(dirname(__FILE__).'/logs/dfs_keywords_records.txt',print_r($ranked_keywords,true));

            if($ranked_keywords['tasks'][0]['result'] != null){

              if($ranked_keywords['tasks'][0]['result'][0]['items_count'] > 0){


                /*inserting metric data*/
                $metricInsertion = SemrushOrganicMetric::create([
                  'request_id'=>$details->id,
                  'pos_1'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_1'],
                  'pos_2_3'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_2_3'],
                  'pos_4_10'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_4_10'],
                  'pos_11_20'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_11_20'],
                  'pos_21_30'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_21_30'],
                  'pos_31_40'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_31_40'],
                  'pos_41_50'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_41_50'],
                  'pos_51_60'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_51_60'],
                  'pos_61_70'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_61_70'],
                  'pos_71_80'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_71_80'],
                  'pos_81_90'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_81_90'],
                  'pos_91_100'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['pos_91_100'],
                  'etv'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['etv'],
                  'impressions_etv'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['impressions_etv'],
                  'count'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['count'],
                  'total_count'=>$ranked_keywords['tasks'][0]['result'][0]['total_count'],
                  'estimated_paid_traffic_cost'=>$ranked_keywords['tasks'][0]['result'][0]['metrics']['organic']['estimated_paid_traffic_cost'],
                ]);


                if($metricInsertion){
                  SemrushOrganicSearchData::where('request_id',$details->id)->delete();

                  $diff = 0;
                  foreach ($ranked_keywords['tasks'][0]['result'][0]['items'] as $key => $value) {
                    $results =  SemrushOrganicSearchData::where('user_id',$details->user_id)->where('request_id',$details->id)->where('keywords',$value['keyword_data']['keyword'])->orderBy('id','desc')->first();

                    if($results <> null){
                      $last_id =  $results->id;
                    } else{
                      $insertedData =  SemrushOrganicSearchData::create([
                        'user_id'=>$details->user_id,
                        'request_id' =>$details->id,
                        'domain_name'=>$value['ranked_serp_element']['serp_item']['domain'],
                        'keywords'=>$value['keyword_data']['keyword'],
                        'position'=>$value['ranked_serp_element']['serp_item']['rank_group'],
                        'previous_position'=>$value['ranked_serp_element']['serp_item']['rank_group'],
                        'position_difference'=>$diff,
                        'search_volume'=>$value['keyword_data']['keyword_info']['search_volume'],
                        'cpc'=>$value['keyword_data']['keyword_info']['cpc'],
                        'url'=>$value['ranked_serp_element']['serp_item']['url'],   
                        'traffic'=>$value['ranked_serp_element']['serp_item']['etv'],
                        'traffic_cost'=>$value['ranked_serp_element']['serp_item']['estimated_paid_traffic_cost'],
                        'competition'=>$value['keyword_data']['keyword_info']['competition'],
                        'number_results'=>$value['ranked_serp_element']['se_results_count']
                      ]);

                      if($insertedData){
                        $last_id = $insertedData->id;
                      }else{
                        $last_id = 0;
                      }
                    }
                  }


                  $this->DFSKeywordsLog($details->user_id,$details->id);  
                  SemrushUserAccount::where('id',$details->id)->update([
                    'extra_keywords_cron_date'=>date('Y-m-d',strtotime('+1 week'))
                  ]);                
                }
              }
            }

            
          }   
          $post_arrays  = array();
        }
      }
    }


    private function DFSKeywordsLog($user_id,$campaign_id){
      SemrushOrganicMetric::DFSKeywords_cron($campaign_id);
      $results = SemrushOrganicMetric::where('request_id',$campaign_id)->orderBy('id','desc')->skip(0)->take(2)->get();
      if(count($results) >0){
        if(!empty($results[0]) && !empty($results[1])){
          $total = $results[0]->total_count - $results[1]->total_count;
        }else{
          $total = $results[0]->total_count - 0;
        }

        if($total > 0){
          $desc = '<b class="activity-green">'. $total. "</b> new keywords have started ranking today";
        }elseif($total < 0){
          $desc = '<b class="activity-red">'. abs($total). "</b> keywords have lost ranking today";
        }else{
          $desc = "New keywords have not started ranking today";
        }

        ActivityLog::keywordsLogTracked($user_id,$campaign_id,'keywords',$desc,'keywords');
      }
    }
  }
