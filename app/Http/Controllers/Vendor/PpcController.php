<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SemrushUserAccount;
use App\GoogleAdsCustomer;
use Auth;
use Session;
use App\GoogleAnalyticsUsers;
use App\AdwordsCampaignDetail;
use App\AdwordsKeywordDetail;
use App\AdwordsAdTextDetail;
use App\AdwordsAdGroupDetail;
use App\AdwordsPlaceHolderDetail;
use App\ModuleByDateRange;
use App\BackLinksData;
use App\User;
use DB;
use DataTables;


/*Google Ads*/
use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\Reporting\v201809\DownloadFormat;
use Google\AdsApi\AdWords\ReportSettingsBuilder;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\SortOrder;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\Query\v201809\ServiceQueryBuilder;
use Google\AdsApi\AdWords\v201809\cm\CampaignService;
use Google\AdsApi\AdWords\v201809\cm\Page;
use Google\AdsApi\AdWords\Query\v201809\ReportQueryBuilder;
use Google\AdsApi\AdWords\Reporting\v201809\ReportDefinitionDateRangeType;
use Google\AdsApi\AdWords\Reporting\v201809\ReportDownloader;
use Google\AdsApi\AdWords\v201809\cm\ReportDefinitionReportType;

use \Illuminate\Pagination\LengthAwarePaginator;

class PpcController extends Controller {

	public function __construct(){
		$this->client_id = \config('app.ads_client_id');
		$this->client_secret = \config('app.ads_client_secret');

		// $this->client_id = '147028391046-7khhiucotu8oo4pni91eqsmqpjdbsqsn.apps.googleusercontent.com';
		// $this->client_secret = 'eP5vigJmpOwrcg4nRixA9EZG';
		$this->developerToken = \config('app.ads_developerToken');
	}

	public function ppc_dashboard($domain_name, $campaign_id,Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$getGoogleAds = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();

		$AdsCustomer = GoogleAdsCustomer::where('id',$getGoogleAds->google_ads_campaign_id)->first();
		$ads_customer_id = isset($AdsCustomer->customer_id)?$AdsCustomer->customer_id:'';	
		
		$googleUserDetails = GoogleAnalyticsUsers::where('id',$getGoogleAds->google_ads_id)->first();

		$today = date('Y-m-d');
		$refreshToken = isset($googleUserDetails->google_refresh_token)?$googleUserDetails->google_refresh_token:'';
		
		
		
		$currency_code ='$';


		return view('vendor.ppc_dashboard'
			,['account_id'=>$ads_customer_id,'today'=>$today,'currency_code'=>$currency_code,'campaign_id'=>$campaign_id,'getGoogleAds'=>$getGoogleAds]
		);
		
	}

	public function ajax_refresh_adwords_data(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		
		$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();

		$acc_id = $if_Exist->google_ads_campaign_id;
		if($if_Exist->status == 0  && $if_Exist->google_ads_id <> null && $if_Exist->google_ads_campaign_id <> null){

			$data = GoogleAdsCustomer::log_adwords_data($request->campaign_id);

			if($data['status'] == 'success'){
				$response['status'] = 'success';
				$response['account_id'] = $if_Exist->google_ads_id;
			}else{
				$data['account_id'] = $if_Exist->google_ads_id;
				$response = $data;
			}

		}else{
			$response['status'] = 'error'; 
		}

		return  response()->json($response);
	}

	public function ajax_refresh_adwords_json(Request $request){
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		
		$if_Exist = SemrushUserAccount::where('user_id',$user_id)->where('id',$request->campaign_id)->first();

		$acc_id = $if_Exist->google_ads_campaign_id;
		if($if_Exist->status == 0  && $if_Exist->google_ads_id <> null && $if_Exist->google_ads_campaign_id <> null){

			$data = GoogleAdsCustomer::log_adwords_refresh($request->campaign_id);

			if($data['status'] == 'success'){
				$response['status'] = 'success';
				$response['account_id'] = $if_Exist->google_ads_id;
			}else{
				$data['account_id'] = $if_Exist->google_ads_id;
				$response = $data;
			}

		}else{
			$response['status'] = 'error'; 
		}
		return  response()->json($response);
	}
	
	public function ajaxSaveInCsv(Request $request){

		$campaign_id = 4;
		$ads_customer_id = '3702284136';
		
		$user_id = User::get_parent_user_id(Auth::user()->id);
		$getGoogleAds = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();

		if(isset($getGoogleAds) && !empty($getGoogleAds->google_ads_id) && ($getGoogleAds->google_ads_id!=null)){
			$googleUserDetails = GoogleAnalyticsUsers::findorfail($getGoogleAds->google_ads_id);
			$refreshToken = $googleUserDetails->google_refresh_token;
			$adwordsSession  = $this->google_ads_auth($ads_customer_id,$refreshToken);

			$today = date('Y-m-d');
			$start_date = date('Ymd',strtotime('-30 days'));
			$end_date = date('Ymd',strtotime('-1 day'));


			$fileName = $ads_customer_id.'_campaigns.csv';
			$keywords_fileName = $ads_customer_id.'_keywords.csv';
			$ads_fileName = $ads_customer_id.'_ads.csv';
			$adgroup_fileName = $ads_customer_id.'_adgroup.csv';
			$place_file = $ads_customer_id.'_placeholder.csv';
			
			
			$if_exists = AdwordsCampaignDetail::
			select('report_date')
			->where('client_id',$ads_customer_id)
			->orderBy('id','desc')
			->first();


			
			// if(!empty($if_exists)){
			// 	$start_date = date('Ymd',strtotime($if_exists->report_date));
			// }

			

			
			/*storing Data in db using csv for today*/
			
			$this->campaign_reports_query($adwordsSession,$start_date,$end_date,$fileName,$ads_customer_id,$today);
			$this->keywords_reports_query($adwordsSession,$start_date,$end_date,$keywords_fileName,$ads_customer_id,$today);
			$this->ads_reports_query($adwordsSession,$start_date,$end_date,$ads_fileName,$ads_customer_id,$today);
			$this->adsGroup_reports_query($adwordsSession,$start_date,$end_date,$adgroup_fileName,$ads_customer_id,$today);
			$this->ads_placeholder_reports_query($adwordsSession,$start_date,$end_date,$place_file,$ads_customer_id,$today);

			return true;
		}else{
			return 'Account not connected';
		}
		
	}
	
	private function google_ads_auth($account_id,$refreshToken){

		$oAuth2Credential = (new OAuth2TokenBuilder())
		->withClientId($this->client_id)
		->withClientSecret($this->client_secret)
		->withRefreshToken($refreshToken)
		->build();
		
		$session = (new AdWordsSessionBuilder())
		->withDeveloperToken($this->developerToken)
		->withOAuth2Credential($oAuth2Credential)
		->withClientCustomerId($account_id)
		->build();

		return $session;
	}
	
	
	private function findHeadline($data){

		$headline = '';
	    if($data[8] == 'Expanded dynamic search ad'){
	        $headline = "[Dynamically generated headline]";
	    }

	    if($data[8] == 'Responsive search ad'){
	        $headline =    implode(" | ",array_map(function($a){
	            return $a['assetText'];
	        },json_decode($data[14],true)));
	    }

	    if($data[8] =='Responsive display ad'){
	        if($data[24] != '--'){
	            $multidesc = json_decode($data[24],true);
	            $headline =    implode(",",array_map(function($a){
	                return $a['assetText'];
	            },$multidesc));
	        } 
	    }

	    if($data[8]=='Expanded text ad'){
	        $headline = $data[4];
	    }

	    if($data[0] != ' --' && $data[0] != null){
	        $headline = $data[0];
	    }

	    return $headline;

	}

	private function findDisplayUrl($data){
		$url = $path1 = $path2 = '';
        if($data[8] =='Expanded dynamic search ad'){
            $url = "[Dynamically generated display URL]";
        }

        if($data[8] == 'Responsive search ad'){
            $url = json_decode($data[20],true)[0].$data[16].'/'.$data[17].'/';
        }

        if($data[8] == 'Responsive display ad'){
			$url = json_decode($data[20],true)[0].$data[16].'/'.$data[17].'/';
		}

		if($data[8] =='Expanded text ad'){

			if($data[21]!=' --'){
				$path1 = $data[21].'/';
			}

			if($data[22] != ' --'){
				$path2 = $data[22].'/';
			}

			$url = json_decode($data[20],true)[0].$path1.$path2;
		}

    	return $url;
	}

	private function findDescription($data){

		$description =  $description1 = '';

        if($data[8] == 'Responsive search ad'){
            $description = implode(",",array_map(function($a){
                return $a['assetText'];
            },json_decode($data[15],true)));
        }



        if($data[8]=='Expanded dynamic search ad'){
            $description = '';
        }

        if($data[8] == 'Responsive display ad'){
            if($data[25]!=' --'){
                $multiDesc1 = json_decode($data[25],true);

                if(count($multiDesc1)==1){
                    $description =  implode(",",array_map(function($a){
                        return $a['assetText'];
                    },$multiDesc1));
                }else{
                    $description =  implode(",",array_map(function($a){
                        return $a;
                    },$multiDesc1));
                }
            }


            if($data[26]!=' --'){
                $multiDescDisplayDes = json_decode($data[26],true);

                if(is_array($multiDescDisplayDes) && count($multiDescDisplayDes)==1){
                    $description1 = implode(",",array_map(function($a){
                        return $a;
                    },$multiDescDisplayDes));
                }
                if(is_array($multiDescDisplayDes) && count($multiDescDisplayDes)>1){
                    $description1 =  implode(",",array_map(function($a){
                        return $a['assetText'];
                    },$multiDescDisplayDes));
                }

            }
        }


        if($data[8]=='Expanded text ad'){
            if($data[5] !=' --' && $data[5]!=null){
                $description = $data[5];
            }
            if($data[18]!=' --' && $data[18]!=null){
                $description1 = $data[18];
            }
        }



        return $description.$description1;

	}
	
	
	
	private function get_from_file($start_date,$end_date,$campaign_id){
		$begin = new \DateTime( $start_date );
		$end   = new \DateTime( $end_date );
		$imps = $clickss = $costs = $conversionss  = $average_costs = $average_cpcs = $average_cpms = $revenue_per_clicks = $total_values = array();
		if (!file_exists(\config('app.FILE_PATH')."public/adwords/".$campaign_id)) {
			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				$imp = $clicks = $cost = $conversions  = $average_cost = $average_cpc = $average_cpm = $revenue_per_click = $total_value = 0;
				$date =  $i->format("Y-m-d");

				$imps[] =  0.00;
				$clickss[] = 0.00;
				$conversionss[] = 0.00;
				$costs[] =  0.00;
				$average_costs[] = 0.00;
				$average_cpcs[] =  0.00;
				$average_cpms[] = 0.00;
				$revenue_per_clicks[] = 0.00;
				$total_values[] = 0.00;	
			}
			
			$status = 0;
		} else {
			$url = \config('app.FILE_PATH')."public/adwords/".$campaign_id."/graphs/overview.json"; 

			$data = file_get_contents($url);
			
			$final = json_decode($data);

			/**/
			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				$date =  $i->format("Ymd");
				
				$imp = $clicks = $cost = $conversions  = $average_cost = $average_cpc = $average_cpm = $revenue_per_click = $total_value = 0;

				$day =  array_column((array)$final->dates, $date);
				
				for($j=0;$j<count($day);$j++){
					$cost += $final->cost[$day[$j]];
					$imp += $final->impressions[$day[$j]];
					$clicks += $final->clicks[$day[$j]];
					$conversions += $final->conversions[$day[$j]];
					$average_cost += $final->average_cost[$day[$j]];
					$average_cpc += $final->average_cpc[$day[$j]];
					$average_cpm += $final->average_cpm[$day[$j]];
					$revenue_per_click +=$final->conversion_value[$day[$j]];
					$total_value +=$final->conversion_value[$day[$j]];
				}
				
				if($clicks=='0.00'){
					$revenue = 0.00;
				}else{
					$revenue = 	$revenue_per_click/$clicks;
				}

				$imps[] = isset($imp) && !empty($imp) ? (float)$imp : 0.00;
				$clickss[] = isset($clicks) && !empty($clicks) ? (float)$clicks : 0.00;
				$conversionss[] = isset($conversions) && !empty($conversions) ? (float)$conversions : 0.00;
				$costs[] = isset($cost) && !empty($cost) ? (float)$cost : 0.00;
				$average_costs[] = isset($average_cost) && !empty($average_cost) ? (float)$average_cost : 0.00;
				$average_cpcs[] = isset($average_cpc) && !empty($average_cpc) ? (float)$average_cpc : 0.00;
				$average_cpms[] = isset($average_cpm) && !empty($average_cpm)?(float)number_format($average_cpm,2,'.',''):0.00;
				$revenue_per_clicks[] = isset($revenue) && !empty($revenue)?(float)number_format($revenue,2,'.',''):0.00;
				$total_values[] = isset($total_value) && !empty($total_value) ? (float)$total_value : 0.00;	
			}
			$status = 1;
			
		}
		$final = array('clicks'=>$clickss,'conversions'=>$conversionss,'impressions'=>$imps,'cost'=>$costs,'average_cost'=>$average_costs,'cpc'=>$average_cpcs,'averagecpm'=>$average_cpms,'revenue_per_click'=>$revenue_per_clicks,'total_value'=>$total_values,'status'=>$status);
		return $final;
	}



	public function ppc_date_range_data(Request $request){	
		$checkIfExists = ModuleByDateRange::select('duration','status')->where('request_id',$request->campaign_id)->where('module','google_ads')->first();
		$account_id = $request->account_id;

		$start_date = $this->get_selected_duration($request->campaign_id);

		$end_date = date('Ymd');
		$summary_chart = $this->get_from_file(date('Y-m-d',strtotime($start_date)),date('Y-m-d'),$request->campaign_id);
		$dates = $this->getDatesFromRange($start_date,$end_date);	


			// dd($checkIfExists);
		if(isset($checkIfExists) && !empty($checkIfExists)){
			if($checkIfExists->status == 1){
				$status = 'true';
				$day_diff = strtotime($start_date) - strtotime($end_date);
				$count_days = floor($day_diff/(60*60*24));
				if($checkIfExists->duration == '12'){
					$prev_date =  date('Ymd',strtotime('-1 day',strtotime($start_date)));
					$prev_end_date =  date('Ymd',strtotime(' -1 year',strtotime($prev_date)));
				}
				elseif($checkIfExists->duration == '24'){
					$prev_date =  date('Ymd',strtotime('-1 day',strtotime($start_date)));
					$prev_end_date =  date('Ymd',strtotime(' -2 year',strtotime($prev_date)));			
				}
				else{
					$prev_date =  date('Ymd',strtotime('-1 day',strtotime($start_date)));
					$prev_end_date =  date('Ymd',strtotime($count_days.' days',strtotime($prev_date)));
				}

				

				$compare_dates = $this->getDatesFromRange($prev_end_date,$prev_date);	
				$compare_summary_chart = $this->get_from_file(date('Y-m-d',strtotime($prev_end_date)),date('Y-m-d',strtotime($prev_date)),$request->campaign_id);
				
				//summary chart
				$summary_chart['clicks_previous'] = $compare_summary_chart['clicks'];
				$summary_chart['conversions_previous'] = $compare_summary_chart['conversions'];
				$summary_chart['impressions_previous'] = $compare_summary_chart['impressions'];
				//performance chart values
				$summary_chart['cost_previous'] = $compare_summary_chart['cost'];
				$summary_chart['cpc_previous'] = $compare_summary_chart['cpc'];
				$summary_chart['averagecpm_previous'] = $compare_summary_chart['averagecpm'];	
				$summary_chart['revenue_per_click_previous'] = $compare_summary_chart['revenue_per_click'];
				$summary_chart['total_value_previous'] = $compare_summary_chart['total_value'];
			}else{
				$status = 'false';
			}
		}else{
			$status = 'false';
		}

		$summary_chart['date_range'] =  $dates;
		$summary_chart['compare'] =  $status;

		return response()->json($summary_chart);
	}


	function getDatesFromRange($start, $end) { 
			// Declare an empty array 
		$array = array(); 

			// Use strtotime function 
		$Variable1 = strtotime($start); 
		$Variable2 = strtotime($end); 

			// Use for loop to store dates into array 
			// 86400 sec = 24 hrs = 60*60*24 = 1 day 
		for ($currentDate = $Variable1; $currentDate <= $Variable2;  
			$currentDate += (86400)) { 

			$Store = date('Y-m-d', $currentDate); 
			$array[] = $Store; 
		} 

			// Display the dates in array format 
		return $array;

	} 

	/* new-design functions*/

	public function ajax_fetch_ads_campaign_data(Request $request){
		
		if($request->ajax())
		{
			$durationRange = $request->all();
			
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date  = $request['start_date'];
			$end_date  = $request['end_date'];
			$campaign_id  = $request['campaign_id'];

			
			$results = $this->ads_campaign_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);
			
			return view('vendor.ppc_sections.campaigns-list.table', compact('results','account_id'))->render();
		}
	}


	public function ajax_fetch_ads_campaign_data_pdf(Request $request){
		
		if($request->ajax())
		{

			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date  = $request['start_date'];
			$end_date  = $request['end_date'];
			$campaign_id  = $request['campaign_id'];

			$results = $this->ads_campaign_data_pdf($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id);
			
			return view('viewkey..pdf.ppc_sections.campaigns-list.table', compact('results','account_id'))->render();
		}
	}


	private function ads_campaign_data_pdf($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id){

		
		$ranges = $this->get_selected_durationJsonFiles($campaign_id,'campaign');
		$end_date = date('Ymd',strtotime('- 1 day'));

		$filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){
				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
		}else{
			$filterData = $ranges['finalDataNew'];
		}
		
		$newData = array();
		
		foreach ($filterData as $keyOuter => $valueOurter) {
			  
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $counterkey = 0;


			foreach ($ranges['finalDataDates'] as $key => $value) {

				$getDay = $value.date('d',strtotime($end_date));
				$day = date('Y-m-d',strtotime($getDay));
				
				$dateCheck[] = $day;

				$urlValues = env('FILE_PATH')."public/adwords/".$campaign_id.'/campaign/'.$value.'.json'; 
				if(file_exists($urlValues)){
			     	$dataValues = file_get_contents($urlValues);
			        $values = json_decode($dataValues,true);
			     }else{
			     	$values = array();
			     }
		       
				if(count($ranges['finalDataDates']) -1 == $key){
					
					if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
						$finalData = array_filter($values[$keyOuter]['dates'],function($v,$k) use($day,$ranges){

						
						if(strtotime($v) >= strtotime($ranges['dateRange']['lastEndDate'])){
							
								return $v;

							}	
							
						},ARRAY_FILTER_USE_BOTH);

						$impressionsArr = array_intersect_key($values[$keyOuter]['impressions'],$finalData);
						$clicksArr =	array_intersect_key($values[$keyOuter]['clicks'],$finalData);
				        $ctrArr =	array_intersect_key($values[$keyOuter]['ctr'],$finalData);
				        $costArr =	array_intersect_key($values[$keyOuter]['cost'],$finalData);
				        $conversionsArr =	array_intersect_key($values[$keyOuter]['conversions'],$finalData);

						$impressions +=	array_sum($impressionsArr);
				        $clicks +=	array_sum($clicksArr);
				        // $ctr +=	array_sum($ctrArr);
				        $cost +=	array_sum($costArr);
				        $conversions +=	array_sum($conversionsArr);
					}
					
					
				}else{
				    
			        if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
			        	$impressions +=	array_sum($values[$keyOuter]['impressions']);
				        $clicks +=	array_sum($values[$keyOuter]['clicks']);
				        // $ctr +=	array_sum($values[$keyOuter]['ctr']);
				        $cost +=	array_sum($values[$keyOuter]['cost']);
				        $conversions +=	array_sum($values[$keyOuter]['conversions']);
			        }
		    	}
		        $counterkey++;
			}
			
			$ctr = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;
			$newData[] = [
				'name'=>$valueOurter,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctr,
				'cost'=>$cost,
				'conversions'=>$conversions
			];
		}
		
		if($sortType == 'campaign_name'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		

		$collection = collect($newData);
		/*->where('name','LIKE','%Allora%')*/
		/*->sortByDesc('impressions');*/
		/*$sorted->values()->all();*/

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );
		
		return $results;
		
	}


	public function ajax_fetch_ads_campaign_pagination(Request $request){
		if($request->ajax())
		{
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date  = $request['start_date'];
			$end_date  = $request['end_date'];
			$campaign_id  = $request['campaign_id'];
			
			$results = $this->ads_campaign_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.campaigns-list.pagination', compact('results','account_id'))->render();
		}
	}

	private function get_selected_duration($campaign_id){
		$getModule = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','google_ads')->first();
		if(isset($getModule) && !empty($getModule)){
			if($getModule->duration == 1){
				$start_date = date('Ymd',strtotime('-1 month'));
			}elseif($getModule->duration == 3){
				$start_date = date('Ymd',strtotime('-3 month'));
			}elseif($getModule->duration == 6){
				$start_date = date('Ymd',strtotime('-6 month'));
			}elseif($getModule->duration == 9){
				$start_date = date('Ymd',strtotime('-9 month'));
			}elseif($getModule->duration == 12){
				$start_date = date('Ymd',strtotime('-1 year'));
			}elseif($getModule->duration == 24){
				$start_date = date('Ymd',strtotime('-2 year'));
			}else{
				$start_date = date('Ymd',strtotime('-1 month'));
			}
		}else{
			$start_date = date('Ymd',strtotime('-3 month'));
		}

		return $start_date;
	}

	private function get_selected_durationJsonFiles($campaign_id,$dirname,$durationRange=null){
		

		$getModule = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','google_ads')->first();
		$finalData = $finalDataNew = $finalDataDates = array();
        $url = env('FILE_PATH')."public/adwords/".$campaign_id.'/'.$dirname.'/list.json'; 
        if(file_exists($url)){
	     	$data = file_get_contents($url);
        	$final = json_decode($data,true);
	     }else{
	     	$final = array();
	     }

	    $firstCurrent = date('Y-m-d');
	    $firstDate = date('Y-m-d',strtotime('- 1 day',strtotime($firstCurrent)));
	    
	    if($durationRange <> null && isset($durationRange['duration']) && $durationRange['duration'] <> null){
	    	
	    	$durations = $durationRange['duration'];

	    	$lastEndDate  = date('Y-m-d',strtotime('-'.$durations.' month',strtotime($firstCurrent)));

			if($durations == 7 || $durations == 14){
				$lastEndDate  = date('Y-m-d',strtotime('-'.$durations.' day',strtotime($firstCurrent)));
				for ($i=0; $i <= $durations; $i++) { 
					if($i == 0){
						$start_date = date('Ym',strtotime('-1 day'));
						$lastDate = date('Y-m-d',strtotime('-1 day'));
						$lastcompareDate = date('Ym',strtotime('-1 day'));
					}else{
						$start_date = date('Ym',strtotime('-1 day',strtotime($lastDate)));
						$lastDate = date('Y-m-d',strtotime('-1 day',strtotime($lastDate)));
						
						$lastcompareDate = end($finalDataDates);
					}
				
					if(isset($final[$start_date]) && $final[$start_date] <> null){
						$finalData[$start_date] = $final[$start_date];

						// $finalDataDates[$i] = $start_date;
						if($start_date <> $lastcompareDate){
							$finalDataDates[] = $start_date;
						}

						$finalDataNew += $final[$start_date];
					}
				}
			
			}elseif($durations == 1){

				$startDates = date('Ym',strtotime('-1 day'));
				$lastDate = date('Y-m-01',strtotime('-1 day'));
				$startDate = date('Y-m-d',strtotime('-1 day'));
				$start_date = date('Ym',strtotime('-1 month',strtotime($startDate)));
				
				// echo $lastDate;
				
				if(isset($final[$start_date]) && $final[$start_date] <> null){
					$finalData[$start_date] = $final[$start_date];

					$finalDataDates[] = $startDates;
					$finalDataDates[] = $start_date;
										
					$finalDataNew += $final[$start_date];
				}
			}else{

				for ($i=0; $i <= $durations; $i++) { 
					if($i == 0){
						$start_date = date('Ym',strtotime('-1 day'));
						$lastDate = date('Y-m-01',strtotime('-1 day'));
					}else{
						$start_date = date('Ym',strtotime('-1 month',strtotime($lastDate)));
						$lastDate = date('Y-m-01',strtotime('-1 month',strtotime($lastDate)));
					}
				
					if(isset($final[$start_date]) && $final[$start_date] <> null){
						$finalData[$start_date] = $final[$start_date];

						$finalDataDates[$i] = $start_date;
						$finalDataNew += $final[$start_date];
					}
				}

			}

	    }elseif($getModule <> null){
	    	
			$lastEndDate  = date('Y-m-d',strtotime('-'.$getModule->duration.' month',strtotime($firstCurrent)));

			if($getModule->duration == 7 || $getModule->duration == 14){
				$lastEndDate  = date('Y-m-d',strtotime('-'.$getModule->duration.' day',strtotime($firstCurrent)));
				for ($i=0; $i <= $getModule->duration; $i++) { 
					if($i == 0){
						$start_date = date('Ym',strtotime('-1 day'));
						$lastDate = date('Y-m-d',strtotime('-1 day'));
						$lastcompareDate = date('Ym',strtotime('-1 day'));
					}else{
						$start_date = date('Ym',strtotime('-1 day',strtotime($lastDate)));
						$lastDate = date('Y-m-d',strtotime('-1 day',strtotime($lastDate)));
						
						$lastcompareDate = end($finalDataDates);
					}
				
					if(isset($final[$start_date]) && $final[$start_date] <> null){
						$finalData[$start_date] = $final[$start_date];

						// $finalDataDates[$i] = $start_date;
						if($start_date <> $lastcompareDate){
							$finalDataDates[] = $start_date;
						}

						$finalDataNew += $final[$start_date];
					}
				}
			
			}elseif($getModule->duration == 1){

				$startDates = date('Ym',strtotime('-1 day'));
				$lastDate = date('Y-m-01',strtotime('-1 day'));
				$startDate = date('Y-m-d',strtotime('-1 day'));
				$start_date = date('Ym',strtotime('-1 month',strtotime($startDate)));
				
				// echo $lastDate;
				
				if(isset($final[$start_date]) && $final[$start_date] <> null){
					$finalData[$start_date] = $final[$start_date];

					$finalDataDates[] = $startDates;
					$finalDataDates[] = $start_date;
										
					$finalDataNew += $final[$start_date];
				}
			}else{

				for ($i=0; $i <= $getModule->duration; $i++) { 
					if($i == 0){
						$start_date = date('Ym',strtotime('-1 day'));
						$lastDate = date('Y-m-01',strtotime('-1 day'));
					}else{
						$start_date = date('Ym',strtotime('-1 month',strtotime($lastDate)));
						$lastDate = date('Y-m-01',strtotime('-1 month',strtotime($lastDate)));
					}
				
					if(isset($final[$start_date]) && $final[$start_date] <> null){
						$finalData[$start_date] = $final[$start_date];

						$finalDataDates[$i] = $start_date;
						$finalDataNew += $final[$start_date];
					}
				}

			}

		}else{
			
			$lastEndDate  = date('Y-m-d',strtotime('-3 month',strtotime($firstCurrent)));
			for ($i=0; $i < 3; $i++) { 
				if($i == 0){
					$start_date = date('Ym',strtotime('-1 day'));
					$lastDate = date('Y-m-01',strtotime('-1 day'));
					
				}else{
					$start_date = date('Ym',strtotime('-1 month',strtotime($lastDate)));
					$lastDate = date('Y-m-01',strtotime('-1 month',strtotime($lastDate)));
					
				}

				if(isset($final[$start_date]) && $final[$start_date] <> null){
					$finalData[$start_date] = $final[$start_date];

					$finalDataDates[$i] = $start_date;
					$finalDataNew += $final[$start_date];
				}
			}

		}
		
		$dateRange = [
			'firstDate' => $firstDate,
			'lastEndDate' => $lastEndDate
		];
		return array('finalDataDates'=>$finalDataDates,'finalDataNew'=>$finalDataNew,'dateRange'=>$dateRange);
	}

	private function ads_campaign_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange = null){

			
		$ranges = $this->get_selected_durationJsonFiles($campaign_id,'campaign',$durationRange);
		$end_date = date('Ymd',strtotime('- 1 day'));

		$filterBy = ''; // or Finance etc.
       	
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){
				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
		}else{
			$filterData = $ranges['finalDataNew'];
		}
		
		$newData = array();
		
		foreach ($filterData as $keyOuter => $valueOurter) {
			  
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $allConversions = $counterkey = 0;


			foreach ($ranges['finalDataDates'] as $key => $value) {

				$getDay = $value.date('d',strtotime($end_date));
				$day = date('Y-m-d',strtotime($getDay));
				
				$dateCheck[] = $day;

				$urlValues = env('FILE_PATH')."public/adwords/".$campaign_id.'/campaign/'.$value.'.json'; 
				if(file_exists($urlValues)){
			     	$dataValues = file_get_contents($urlValues);
			        $values = json_decode($dataValues,true);
			     }else{
			     	$values = array();
			     }
		       
				if(count($ranges['finalDataDates']) -1 == $key){
					
					if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
						$finalData = array_filter($values[$keyOuter]['dates'],function($v,$k) use($day,$ranges){

						
						if(strtotime($v) >= strtotime($ranges['dateRange']['lastEndDate'])){
							
								return $v;

							}	
							
						},ARRAY_FILTER_USE_BOTH);

						$impressionsArr = array_intersect_key($values[$keyOuter]['impressions'],$finalData);
						$clicksArr =	array_intersect_key($values[$keyOuter]['clicks'],$finalData);
				        $ctrArr =	array_intersect_key($values[$keyOuter]['ctr'],$finalData);
				        $costArr =	array_intersect_key($values[$keyOuter]['cost'],$finalData);
				        $conversionsArr =	array_intersect_key($values[$keyOuter]['conversions'],$finalData);
				        $allConversionsArr =	array_intersect_key($values[$keyOuter]['all_conversions'],$finalData);

						$impressions +=	array_sum($impressionsArr);
				        $clicks +=	array_sum($clicksArr);
				        // $ctr +=	array_sum($ctrArr);
				        $cost +=	array_sum($costArr);
				        $conversions +=	array_sum($conversionsArr);
				        $allConversions +=	array_sum($allConversionsArr);
					}
					
					
				}else{
				    
			        if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
			        	$impressions +=	array_sum($values[$keyOuter]['impressions']);
				        $clicks +=	array_sum($values[$keyOuter]['clicks']);
				        // $ctr +=	array_sum($values[$keyOuter]['ctr']);
				        $cost +=	array_sum($values[$keyOuter]['cost']);
				        $conversions +=	array_sum($values[$keyOuter]['conversions']);
				        $allConversions +=	array_sum($values[$keyOuter]['all_conversions']);
			        }
		    	}
		        $counterkey++;
			}
			
			$ctr = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueOurter,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctr,
				'cost'=>$cost,
				'conversions'=>$conversions,
				'all_conversions'=>$allConversions,
			];
		}
		
		if($sortType == 'campaign_name'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		

		$collection = collect($newData);
		/*->where('name','LIKE','%Allora%')*/
		/*->sortByDesc('impressions');*/
		/*$sorted->values()->all();*/

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );
		
		return $results;
		
	}


	public function ajax_fetch_ads_keywords_data(Request $request){
		if($request->ajax())
		{
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];
			
			$results = $this->ads_keywords_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);
			return view('vendor.ppc_sections.keywords-list.table', compact('results','account_id'))->render();			
		}
	}


	public function ajax_fetch_ads_keywords_data_pdf(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = $this->ads_keywords_data_pdf($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id);
			return view('viewkey.pdf.ppc_sections.keywords-list.table', compact('results','account_id'))->render();
		}
	}


	private function ads_keywords_data_pdf($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id){
		
		$fileName = 'keywords';
		$ranges = $this->get_selected_durationJsonFiles($campaign_id,$fileName);
		$end_date = date('Ymd');

		

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){
				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
		}else{
			$filterData = $ranges['finalDataNew'];
		}
		
		$newData = array();
		foreach ($filterData as $keyOuter => $valueOurter) {
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions =  $conversions = $counterkey = 0;
			foreach ($ranges['finalDataDates'] as $key => $value) {
				$urlValues = env('FILE_PATH')."public/adwords/".$campaign_id.'/'.$fileName.'/'.$value.'.json'; 
				if(file_exists($urlValues)){
			     	$dataValues = file_get_contents($urlValues);
			        $values = json_decode($dataValues,true);
			     }else{
			     	$values = array();
			     }
		        
		         
		        $getDay = $value.date('d',strtotime($end_date));
				$day = date('Y-m-d',strtotime($getDay));

				if(count($ranges['finalDataDates']) -1 == $key){
					
					if(isset($values[$keyOuter])){
						$finalData = array_filter($values[$keyOuter]['dates'],function($v,$k) use($day,$ranges){

						
						if(strtotime($v) >= strtotime($ranges['dateRange']['lastEndDate'])){
							
								return $v;

							}	
							
						},ARRAY_FILTER_USE_BOTH);

						
						$impressionsArr = array_intersect_key($values[$keyOuter]['impressions'],$finalData);
						$clicksArr =	array_intersect_key($values[$keyOuter]['clicks'],$finalData);
				        $ctrArr =	array_intersect_key($values[$keyOuter]['ctr'],$finalData);
				        $costArr =	array_intersect_key($values[$keyOuter]['cost'],$finalData);
				        $conversionsArr =	array_intersect_key($values[$keyOuter]['conversions'],$finalData);
				      
						$impressions +=	array_sum($impressionsArr);
				        $clicks +=	array_sum($clicksArr);
				        $ctr +=	array_sum($ctrArr);
				        $cost +=	array_sum($costArr);
				        $conversions +=	array_sum($conversionsArr);				       
					}
				
					
				}else{

			        if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
			        	$impressions +=	array_sum($values[$keyOuter]['impressions']);
				        $clicks +=	array_sum($values[$keyOuter]['clicks']);
				        $ctr +=	array_sum($values[$keyOuter]['ctr']);
				        $cost +=	array_sum($values[$keyOuter]['cost']);
				        $conversions +=	array_sum($values[$keyOuter]['conversions']);
			        }
			    }    
		        $counterkey++;
			}

			$newData[] = [
				'name'=>$valueOurter,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctr,
				'cost'=>$cost,
				'conversions'=>$conversions
			];
			
		}

		if($sortType == 'keywords'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );
		return $results;
		
	}


	public function ajax_fetch_ads_keywords_pagination(Request $request){
		if($request->ajax())
		{
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = $this->ads_keywords_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.keywords-list.pagination', compact('results','account_id'))->render();
		}
	}

	
	private function ads_keywords_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange){
		
		$fileName = 'keywords';
		$ranges = $this->get_selected_durationJsonFiles($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){
				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
		}else{
			$filterData = $ranges['finalDataNew'];
		}
		
		$newData = array();
		foreach ($filterData as $keyOuter => $valueOurter) {
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions =  $conversions = $firstPageCpc = $firstPostionCpc = $counterkey = 0;
			foreach ($ranges['finalDataDates'] as $key => $value) {
				$urlValues = env('FILE_PATH')."public/adwords/".$campaign_id.'/'.$fileName.'/'.$value.'.json'; 
				if(file_exists($urlValues)){
			     	$dataValues = file_get_contents($urlValues);
			        $values = json_decode($dataValues,true);
			     }else{
			     	$values = array();
			     }
		        
		         
		        $getDay = $value.date('d',strtotime($end_date));
				$day = date('Y-m-d',strtotime($getDay));

				if(count($ranges['finalDataDates']) -1 == $key){
					
					if(isset($values[$keyOuter])){
						$finalData = array_filter($values[$keyOuter]['dates'],function($v,$k) use($day,$ranges){

						
						if(strtotime($v) >= strtotime($ranges['dateRange']['lastEndDate'])){
							
								return $v;

							}	
							
						},ARRAY_FILTER_USE_BOTH);

						
						$impressionsArr = array_intersect_key($values[$keyOuter]['impressions'],$finalData);
						$clicksArr =	array_intersect_key($values[$keyOuter]['clicks'],$finalData);
				        // $ctrArr =	array_intersect_key($values[$keyOuter]['ctr'],$finalData);
				        $costArr =	array_intersect_key($values[$keyOuter]['cost'],$finalData);
				        $conversionsArr =	array_intersect_key($values[$keyOuter]['conversions'],$finalData);
				        $firstPageCpcArr =	array_intersect_key($values[$keyOuter]['first_page_cpc'],$finalData);
				        $firstPostionCpcArr =	array_intersect_key($values[$keyOuter]['first_postion_cpc'],$finalData);

						$impressions +=	array_sum($impressionsArr);
				        $clicks +=	array_sum($clicksArr);
				        // $ctr +=	array_sum($ctrArr);
				        $cost +=	array_sum($costArr);
				        $conversions +=	array_sum($conversionsArr);
				        $firstPageCpc +=	array_sum($firstPageCpcArr);
				        $firstPostionCpc +=	array_sum($firstPostionCpcArr);
					}
				
					
				}else{

			        if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
			        	$impressions +=	array_sum($values[$keyOuter]['impressions']);
				        $clicks +=	array_sum($values[$keyOuter]['clicks']);
				        // $ctr +=	array_sum($values[$keyOuter]['ctr']);
				        $cost +=	array_sum($values[$keyOuter]['cost']);
				        $conversions +=	array_sum($values[$keyOuter]['conversions']);
				        $firstPageCpc +=	array_sum($values[$keyOuter]['first_page_cpc']);
				        $firstPostionCpc +=	array_sum($values[$keyOuter]['first_postion_cpc']);
			        }
			    }    
		        $counterkey++;
			}

			$ctr = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueOurter,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctr,
				'cost'=>$cost,
				'conversions'=>$conversions,
				'first_page_cpc'=>$firstPageCpc,
				'first_postion_cpc'=>$firstPostionCpc,
			];
			
		}

		if($sortType == 'keywords'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );
		return $results;
		
	}

	public function ajax_fetch_adGroup_data(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = $this->adsGroup_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.adsGroup-list.table', compact('results','account_id'))->render();
		}
	}

	public function ajax_fetch_adGroup_pagination(Request $request){
		if($request->ajax())
		{
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = $this->adsGroup_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.adsGroup-list.pagination', compact('results','account_id'))->render();
		}
	}

	

	private function adsGroup_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange){

		$fileName = 'adgroups';
		$ranges = $this->get_selected_durationJsonFiles($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){

				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
			
		}else{
			$filterData = $ranges['finalDataNew'];
		}

		$newData = array();
		foreach ($filterData as $keyOuter => $valueOurter) { 
			
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $counterkey = 0;
			foreach ($ranges['finalDataDates'] as $key => $value) {
				$urlValues = env('FILE_PATH')."public/adwords/".$campaign_id.'/'.$fileName.'/'.$value.'.json'; 
		        if(file_exists($urlValues)){
			     	$dataValues = file_get_contents($urlValues);
			        $values = json_decode($dataValues,true);
			     }else{
			     	$values = array();
			     }
		        
		        $getDay = $value.date('d',strtotime($end_date));
				$day = date('Y-m-d',strtotime($getDay));

				if(count($ranges['finalDataDates']) -1 == $key){
					
					if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
						$finalData = array_filter($values[$keyOuter]['dates'],function($v,$k) use($day,$ranges){

						
						if(strtotime($v) >= strtotime($ranges['dateRange']['lastEndDate'])){
							
								return $v;

							}	
							
						},ARRAY_FILTER_USE_BOTH);

						$impressionsArr = array_intersect_key($values[$keyOuter]['impressions'],$finalData);
						$clicksArr =	array_intersect_key($values[$keyOuter]['clicks'],$finalData);
				        // $ctrArr =	array_intersect_key($values[$keyOuter]['ctr'],$finalData);
				        $costArr =	array_intersect_key($values[$keyOuter]['cost'],$finalData);
				        $conversionsArr =	array_intersect_key($values[$keyOuter]['conversions'],$finalData);

						$impressions +=	array_sum($impressionsArr);
				        $clicks +=	array_sum($clicksArr);
				        // $ctr +=	array_sum($ctrArr);
				        $cost +=	array_sum($costArr);
				        $conversions +=	array_sum($conversionsArr);
					}
					
					
				}else{

			        if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
			        	$impressions +=	array_sum($values[$keyOuter]['impressions']);
				        $clicks +=	array_sum($values[$keyOuter]['clicks']);
				        // $ctr +=	array_sum($values[$keyOuter]['ctr']);
				        $cost +=	array_sum($values[$keyOuter]['cost']);
				        $conversions +=	array_sum($values[$keyOuter]['conversions']);
			        }
		    	}
		    	$counterkey++;
		        
			}

			$ctr = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueOurter,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctr,
				'cost'=>$cost,
				'conversions'=>$conversions,
			];
			
		}
		
		if($sortType == 'ad_group'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		// $collection = collect($newData)
		// /*->where('name','LIKE','%Allora%')*/
		// ->sortByDesc('impressions');
		// /*$sorted->values()->all();*/

		// $page = request()->has('page') ? request('page') : 1;

		// // Set default per page
		// $perPage = request()->has('per_page') ? request('per_page') : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );

		return $results;
		
	}

	public function ajax_fetch_adsPerformance_network_data(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = $this->adsPerformanceNetwork_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.performance-network.table', compact('results','account_id'))->render();
		}
	}

	public function ajax_fetch_adsPerformance_network_pagination(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = $this->adsPerformanceNetwork_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.performance-network.pagination', compact('results','account_id'))->render();
		}
	}

	private function adsPerformanceNetwork_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange){

		$fileName = 'networks';
		$ranges = $this->get_selected_durationJsonFiles($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){

				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
			
		}else{
			$filterData = $ranges['finalDataNew'];
		}

		$newData = array();
		foreach ($filterData as $keyOuter => $valueOurter) {
			
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $counterkey = 0;
			foreach ($ranges['finalDataDates'] as $key => $value) {
				$urlValues = env('FILE_PATH')."public/adwords/".$campaign_id.'/'.$fileName.'/'.$value.'.json'; 
		        if(file_exists($urlValues)){
			     	$dataValues = file_get_contents($urlValues);
			        $values = json_decode($dataValues,true);
			     }else{
			     	$values = array();
			     }
		        
		        $getDay = $value.date('d',strtotime($end_date));
				$day = date('Y-m-d',strtotime($getDay));

				if(count($ranges['finalDataDates']) -1 == $key){
					
					if(isset($values[$keyOuter])){
						$finalData = array_filter($values[$keyOuter]['dates'],function($v,$k) use($day,$ranges){

						
						if(strtotime($v) >= strtotime($ranges['dateRange']['lastEndDate'])){
							
								return $v;

							}	
							
						},ARRAY_FILTER_USE_BOTH);
		        

						$impressionsArr = array_intersect_key($values[$keyOuter]['impressions'],$finalData);
						$clicksArr =	array_intersect_key($values[$keyOuter]['clicks'],$finalData);
				        $ctrArr =	array_intersect_key($values[$keyOuter]['ctr'],$finalData);
				        $costArr =	array_intersect_key($values[$keyOuter]['cost'],$finalData);
				        $conversionsArr =	array_intersect_key($values[$keyOuter]['conversions'],$finalData);

						$impressions +=	array_sum($impressionsArr);
				        $clicks +=	array_sum($clicksArr);
				        // $ctr +=	array_sum($ctrArr);
				        $cost +=	array_sum($costArr);
				        $conversions +=	array_sum($conversionsArr);
					}
				}else{

			        if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
			        	$impressions +=	array_sum($values[$keyOuter]['impressions']);
				        $clicks +=	array_sum($values[$keyOuter]['clicks']);
				        // $ctr +=	array_sum($values[$keyOuter]['ctr']);
				        $cost +=	array_sum($values[$keyOuter]['cost']);
				        $conversions +=	array_sum($values[$keyOuter]['conversions']);
			        }
			    }
		        $counterkey++;
			}

			$ctr = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueOurter,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctr,
				'cost'=>$cost,
				'conversions'=>$conversions,
			];
			
		}

		if($sortType == 'publisher_by_network'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;
		
		// $collection = collect($newData)
		// /*->where('name','LIKE','%Allora%')*/
		// ->sortByDesc('impressions');
		// /*$sorted->values()->all();*/

		// $page = request()->has('page') ? request('page') : 1;

		// // Set default per page
		// $perPage = request()->has('per_page') ? request('per_page') : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );

		return $results;
		
	}


	public function ajax_fetch_adsPerformance_device_data(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = $this->ads_performanc_device_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.performance-device.table', compact('results','account_id'))->render();
		}
	}

	public function ajax_fetch_adsPerformance_device_pagination(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = $this->ads_performanc_device_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.performance-device.pagination', compact('results','account_id'))->render();
		}
	}

	private function ads_performanc_device_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange){
		$fileName = 'devices';
		$ranges = $this->get_selected_durationJsonFiles($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){

				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
			
		}else{
			$filterData = $ranges['finalDataNew'];
		}

		$newData = array();
		foreach ($filterData as $keyOuter => $valueOurter) {
			
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $counterkey = 0;
			foreach ($ranges['finalDataDates'] as $key => $value) {
				$urlValues = env('FILE_PATH')."public/adwords/".$campaign_id.'/'.$fileName.'/'.$value.'.json'; 
		        if(file_exists($urlValues)){
			     	$dataValues = file_get_contents($urlValues);
			        $values = json_decode($dataValues,true);
			     }else{
			     	$values = array();
			     }
		        
		        $getDay = $value.date('d',strtotime($end_date));
				$day = date('Y-m-d',strtotime($getDay));

		        if(count($ranges['finalDataDates']) -1 == $key){
					
					if(isset($values[$keyOuter])){
						$finalData = array_filter($values[$keyOuter]['dates'],function($v,$k) use($day,$ranges){

						
						if(strtotime($v) >= strtotime($ranges['dateRange']['lastEndDate'])){
							
								return $v;

							}	
							
						},ARRAY_FILTER_USE_BOTH);

						$impressionsArr = array_intersect_key($values[$keyOuter]['impressions'],$finalData);
						$clicksArr =	array_intersect_key($values[$keyOuter]['clicks'],$finalData);
				        $ctrArr =	array_intersect_key($values[$keyOuter]['ctr'],$finalData);
				        $costArr =	array_intersect_key($values[$keyOuter]['cost'],$finalData);
				        $conversionsArr =	array_intersect_key($values[$keyOuter]['conversions'],$finalData);

						$impressions +=	array_sum($impressionsArr);
				        $clicks +=	array_sum($clicksArr);
				        // $ctr +=	array_sum($ctrArr);
				        $cost +=	array_sum($costArr);
				        $conversions +=	array_sum($conversionsArr);
			    	}
					
				}else{

			        if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
			        	$impressions +=	array_sum($values[$keyOuter]['impressions']);
				        $clicks +=	array_sum($values[$keyOuter]['clicks']);
				        // $ctr +=	array_sum($values[$keyOuter]['ctr']);
				        $cost +=	array_sum($values[$keyOuter]['cost']);
				        $conversions +=	array_sum($values[$keyOuter]['conversions']);
			        }
		        }
		        $counterkey++;
		        
			}

			$ctr = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueOurter,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctr,
				'cost'=>$cost,
				'conversions'=>$conversions,
			];
			
		}
		
		if($sortType == 'device'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		// $collection = collect($newData)
		// /*->where('name','LIKE','%Allora%')*/
		// ->sortByDesc('impressions');
		// /*$sorted->values()->all();*/

		// $page = request()->has('page') ? request('page') : 1;

		// // Set default per page
		// $perPage = request()->has('per_page') ? request('per_page') : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );

		return $results;
	}


	public function ajax_fetch_adsPerformance_clickType_data(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date  = $request['start_date'];
			$end_date  = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = $this->ads_performance_clickType_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.clickType.table', compact('results','account_id'))->render();
		}
	}

	public function ajax_fetch_adsPerformance_clickType_pagination(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = $this->ads_performance_clickType_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.clickType.pagination', compact('results','account_id'))->render();
		}
	}

	private function ads_performance_clickType_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange){
		$fileName = 'clickType';
		$ranges = $this->get_selected_durationJsonFiles($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){

				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
			
		}else{
			$filterData = $ranges['finalDataNew'];
		}

		$newData = array();
		foreach ($filterData as $keyOuter => $valueOurter) {
			
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $counterkey = 0;
			foreach ($ranges['finalDataDates'] as $key => $value) {
				$urlValues = env('FILE_PATH')."public/adwords/".$campaign_id.'/'.$fileName.'/'.$value.'.json'; 
		        if(file_exists($urlValues)){
			     	$dataValues = file_get_contents($urlValues);
			        $values = json_decode($dataValues,true);
			     }else{
			     	$values = array();
			     }
		        
		        $getDay = $value.date('d',strtotime($end_date));
				$day = date('Y-m-d',strtotime($getDay));

		        if(count($ranges['finalDataDates']) -1 == $key){
					
					if(isset($values[$keyOuter])){
						$finalData = array_filter($values[$keyOuter]['dates'],function($v,$k) use($day,$ranges){

						
						if(strtotime($v) >= strtotime($ranges['dateRange']['lastEndDate'])){
							
								return $v;

							}	
							
						},ARRAY_FILTER_USE_BOTH);

						$impressionsArr = array_intersect_key($values[$keyOuter]['impressions'],$finalData);
						$clicksArr =	array_intersect_key($values[$keyOuter]['clicks'],$finalData);
				        // $ctrArr =	array_intersect_key($values[$keyOuter]['ctr'],$finalData);
				        $costArr =	array_intersect_key($values[$keyOuter]['cost'],$finalData);
				        $conversionsArr =	array_intersect_key($values[$keyOuter]['conversions'],$finalData);

						$impressions +=	array_sum($impressionsArr);
				        $clicks +=	array_sum($clicksArr);
				        // $ctr +=	array_sum($ctrArr);
				        $cost +=	array_sum($costArr);
				        $conversions +=	array_sum($conversionsArr);
			    	}
					
				}else{

			        if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
			        	$impressions +=	array_sum($values[$keyOuter]['impressions']);
				        $clicks +=	array_sum($values[$keyOuter]['clicks']);
				        // $ctr +=	array_sum($values[$keyOuter]['ctr']);
				        $cost +=	array_sum($values[$keyOuter]['cost']);
				        $conversions +=	array_sum($values[$keyOuter]['conversions']);
			        }
		        }
		        $counterkey++;
		        
			}

			$ctr = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueOurter,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctr,
				'cost'=>$cost,
				'conversions'=>$conversions,
			];
			
		}
		
		if($sortType == 'click_type'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		// $collection = collect($newData)
		// /*->where('name','LIKE','%Allora%')*/
		// ->sortByDesc('impressions');
		// /*$sorted->values()->all();*/

		// $page = request()->has('page') ? request('page') : 1;

		// // Set default per page
		// $perPage = request()->has('per_page') ? request('per_page') : 20;

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );

		return $results;
	}

	public function ajax_fetch_adsPerformance_adSlot_data(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = $this->ads_performance_adSlot_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.performance-adSlot.table', compact('results','account_id'))->render();
		}
	}

	public function ajax_fetch_adsPerformance_adSlot_pagination(Request $request){
		if($request->ajax())
		{	
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			$results = $this->ads_performance_adSlot_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.performance-adSlot.pagination', compact('results','account_id'))->render();
		}
	}

	private function ads_performance_adSlot_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange=null){
		$fileName = 'adSlots';
		$ranges = $this->get_selected_durationJsonFiles($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		

        $filterBy = ''; // or Finance etc.
       
		if($query <> ''){
			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query){

				if (strpos($v, $query) !== false) {
				    return $v;
				}
				
			},ARRAY_FILTER_USE_BOTH);
			
		}else{
			$filterData = $ranges['finalDataNew'];
		}

		$newData = array();
		foreach ($filterData as $keyOuter => $valueOurter) {
			
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions =  $counterkey = 0;
			foreach ($ranges['finalDataDates'] as $key => $value) {
				$urlValues = env('FILE_PATH')."public/adwords/".$campaign_id.'/'.$fileName.'/'.$value.'.json'; 
		        if(file_exists($urlValues)){
			     	$dataValues = file_get_contents($urlValues);
			        $values = json_decode($dataValues,true);
			     }else{
			     	$values = array();
			     }
		         
		        $getDay = $value.date('d',strtotime($end_date));
				$day = date('Y-m-d',strtotime($getDay));

		        if(count($ranges['finalDataDates']) -1 == $key){
					
					if(isset($values[$keyOuter])){
						$finalData = array_filter($values[$keyOuter]['dates'],function($v,$k) use($day,$ranges){

						
						if(strtotime($v) >= strtotime($ranges['dateRange']['lastEndDate'])){
							
								return $v;

							}	
							
						},ARRAY_FILTER_USE_BOTH);

						$impressionsArr = array_intersect_key($values[$keyOuter]['impressions'],$finalData);
						$clicksArr =	array_intersect_key($values[$keyOuter]['clicks'],$finalData);
				        // $ctrArr =	array_intersect_key($values[$keyOuter]['ctr'],$finalData);
				        $costArr =	array_intersect_key($values[$keyOuter]['cost'],$finalData);
				        $conversionsArr =	array_intersect_key($values[$keyOuter]['conversions'],$finalData);

						$impressions +=	array_sum($impressionsArr);
				        $clicks +=	array_sum($clicksArr);
				        // $ctr +=	array_sum($ctrArr);
				        $cost +=	array_sum($costArr);
				        $conversions +=	array_sum($conversionsArr);
					}
					
					
				}else{
				 
			        if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
			        	$impressions +=	array_sum($values[$keyOuter]['impressions']);
				        $clicks +=	array_sum($values[$keyOuter]['clicks']);
				        // $ctr +=	array_sum($values[$keyOuter]['ctr']);
				        $cost +=	array_sum($values[$keyOuter]['cost']);
				        $conversions +=	array_sum($values[$keyOuter]['conversions']);
			        }
		        }
		        $counterkey++;
			}

			$ctr = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueOurter,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctr,
				'cost'=>$cost,
				'conversions'=>$conversions,
			];
			
		}
		
		if($sortType == 'ad_slot'){
			$keys = array_column($newData, 'name');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;
	

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );

		return $results;
	}

	public function ajax_fetch_ads_data(Request $request){
		if($request->ajax())
		{
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];
			
			$addiionalData = $this->addiionalData($campaign_id);

			$results = $this->ads_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.ads-list.table', compact('results','addiionalData','account_id'))->render();
		}
	}

	public function ajax_fetch_ads_pagination(Request $request){
		if($request->ajax())
		{
			$durationRange = $request->all();
			$limit = $request['limit'];
			$account_id = $request['account_id'];
			$sortType = $request['column_name']?:'impressions';
			$sortBy = $request['order_type']?:'desc';
			$query  = $request['query'];
			$start_date = $request['start_date'];
			$end_date = $request['end_date'];
			$campaign_id = $request['campaign_id'];

			

			$results = $this->ads_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange);

			return view('vendor.ppc_sections.ads-list.pagination', compact('results','account_id'))->render();
		}
	}

	private function addiionalData($campaign_id)
	{
		$fileName = 'ads';
		$urlValues = env('FILE_PATH')."public/adwords/".$campaign_id.'/'.$fileName.'/aditional.json'; 
		if(file_exists($urlValues)){
			$dataValues = file_get_contents($urlValues);
        	$values = json_decode($dataValues,true);
		}else{
			$values = array();
		}
        

        return $values;
   	}

	private function ads_data($limit,$account_id,$sortType,$sortBy,$query,$start_date,$end_date,$campaign_id,$durationRange=null){

		$fileName = 'ads';
		$ranges = $this->get_selected_durationJsonFiles($campaign_id,$fileName,$durationRange);
		$end_date = date('Ymd');

		$filterBy = ''; // or Finance etc.
       	
       	$additionalData = $this->addiionalData($campaign_id);

		if($query <> ''){

			$filterData = array_filter($ranges['finalDataNew'],function($v,$k) use ($query,$additionalData){
				
				if (strpos($additionalData[$k]['ad_type'], $query) !== false) {
					
				    return $k;
				}
				if (strpos($additionalData[$k]['headlines'], $query) !== false) {
					return $k;
				}
				if (strpos($additionalData[$k]['displayurl'], $query) !== false) {
					return $k;
				}
				if (strpos($additionalData[$k]['discription'], $query) !== false) {
					return $k;
				}
				
			},ARRAY_FILTER_USE_BOTH);
			

		}else{
			$filterData = $ranges['finalDataNew'];
		}

		$newData = array();
		foreach ($filterData as $keyOuter => $valueOurter) {
			
			$impressions =	$clicks = 	$ctr = 	$cost =  $conversions = $counterkey = 0;
			foreach ($ranges['finalDataDates'] as $key => $value) {
				$urlValues = env('FILE_PATH')."public/adwords/".$campaign_id.'/'.$fileName.'/'.$value.'.json'; 
		        if(file_exists($urlValues)){
			     	$dataValues = file_get_contents($urlValues);
			        $values = json_decode($dataValues,true);
			     }else{
			     	$values = array();
			     }
		        
		        $getDay = $value.date('d',strtotime($end_date));
				$day = date('Y-m-d',strtotime($getDay));

				if(count($ranges['finalDataDates']) -1 == $key){
					
					if(isset($values[$keyOuter])){
						$finalData = array_filter($values[$keyOuter]['dates'],function($v,$k) use($day,$ranges){

						
						if(strtotime($v) >= strtotime($ranges['dateRange']['lastEndDate'])){
							
								return $v;

							}	
							
						},ARRAY_FILTER_USE_BOTH);

		        	/* if($counterkey == 0){
					
					if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
						$finalData = array_filter($values[$keyOuter]['dates'],function($v,$k) use($day,$values,$keyOuter,$clicks){
						
							if(strtotime($v) >= strtotime($day)){
								
								return $v;

							}	
							
						},ARRAY_FILTER_USE_BOTH);*/

						$impressionsArr = array_intersect_key($values[$keyOuter]['impressions'],$finalData);
						$clicksArr =	array_intersect_key($values[$keyOuter]['clicks'],$finalData);
				        $ctrArr =	array_intersect_key($values[$keyOuter]['ctr'],$finalData);
				        $costArr =	array_intersect_key($values[$keyOuter]['cost'],$finalData);
				        $conversionsArr =	array_intersect_key($values[$keyOuter]['conversions'],$finalData);

						$impressions +=	array_sum($impressionsArr);
				        $clicks +=	array_sum($clicksArr);
				        // $ctr +=	array_sum($ctrArr);
				        $cost +=	array_sum($costArr);
				        $conversions +=	array_sum($conversionsArr);
					}
					
					
				}else{

			        if(isset($values[$keyOuter]) && $values[$keyOuter] <> null){
			        	$impressions +=	array_sum($values[$keyOuter]['impressions']);
				        $clicks +=	array_sum($values[$keyOuter]['clicks']);
				        // $ctr +=	array_sum($values[$keyOuter]['ctr']);
				        $cost +=	array_sum($values[$keyOuter]['cost']);
				        $conversions +=	array_sum($values[$keyOuter]['conversions']);
			        }
		        }
		        $counterkey++;
			}

			$ctr = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;

			$newData[] = [
				'name'=>$valueOurter,
				'displayurl'=>$additionalData[$keyOuter]['displayurl'],
				'ad_type'=>$additionalData[$keyOuter]['ad_type'],
				'adId'=>$keyOuter,
				'impressions'=>$impressions,
				'clicks'=>$clicks,
				'ctr'=>$ctr,
				'cost'=>$cost,
				'conversions'=>$conversions,
			];
			
		}
		
		if($sortType == 'ad'){
			$keys = array_column($newData, 'displayurl');
		}else if($sortType == 'ad_type'){
			$keys = array_column($newData, 'ad_type');
		}else{
			$keys = array_column($newData, $sortType);
		}
		
		
		if($sortBy == 'desc'){
			array_multisort($keys, SORT_DESC, $newData);
		}else{
			array_multisort($keys, SORT_ASC, $newData);
		}
		


		$collection = collect($newData);

		$page = request()->has('page') ? request('page') : 1;

		// Set default per page
		$perPage = $limit <> null && $limit <> 0 ? $limit : 20;

		

		// Offset required to take the results
		$offset = ($page * $perPage) - $perPage;

		$results =  new LengthAwarePaginator(
		   $collection->slice($offset, $perPage),
		   $collection->count(),
		   $perPage,
		   $page
		 );
		
		return $results;
	}

	private function overview_duration_previous($duration = null){

		$durationMonthDay = " month";
		
		if($duration <> null){
			
			$default_duration = $duration*2;

			if($duration == 7 || $duration == 14){
				$lapse ='+0 day';
				$durationMonthDay = " days";

				$dates = date('Y-m-d',strtotime('-'.$default_duration.' days'));
				$start_date = date('Y-m-d',strtotime($dates));
				$end_date = date('Y-m-d',strtotime('+'.$duration.' days',strtotime($start_date)));
				//$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				
			}elseif($duration <= 3){
				$lapse ='+6 days';

				$dates = date('Y-m-d',strtotime('-'.$default_duration.' months'));
				$start_date = date('Y-m-d',strtotime('-1 day',strtotime($dates)));
				$end_date = date('Y-m-d',strtotime('+'.$duration.' months',strtotime($start_date)));
				$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
			
			}elseif($duration >= 6 && $duration <= 12){
				$duration = $duration;
				$lapse ='+1 month';

				$dates = date('Y-m-d',strtotime('-'.$default_duration.' months'));
				$start_date = date('Y-m-d',strtotime('-1 day',strtotime($dates)));
				$end_date = date('Y-m-d',strtotime('+'.$duration.' months',strtotime($start_date)));
			}elseif($duration == 24){
				$duration = $duration/3;
				$lapse = '+3 month';

				$dates = date('Y-m-d',strtotime('-'.$default_duration.' months'));
				$start_date = date('Y-m-d',strtotime('-1 day',strtotime($dates)));
				$end_date = date('Y-m-d',strtotime('+'.$duration.' months',strtotime($start_date)));
			}

		}else{
			$duration =  3;
			$default_duration =  6;
			$lapse = '+6 days';
			$start_date = date('Y-m-d',strtotime('-6 months'));
			$end_date = date('Y-m-d',strtotime('+'.$duration.' months',strtotime($start_date)));
			
		}
		
		$res = array();
		
		for($i=1;$i<=$duration;$i++){
			if($i==1){	
				$start_date = date('Y-m-d',strtotime($start_date));
				$end_dates = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				
			}else{
				$start_date = date('Y-m-d',strtotime('+1 day',strtotime($end_dates)));
				$end_dates = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
			}

			if($end_dates > $end_date){
				
				$end_dates = date('Y-m-d',strtotime($end_date));
			}

			$res[$i]['start_date'] = $start_date;
			$res[$i]['end_date'] = $end_dates;
		}

		return $res;
		
	}

	private function overview_duration($campaign_id,$account_id,$duration = null){
		
		
		$durationMonthDay = " months";
		if($duration <> null){
			$duration =$default_duration =  $duration;
			
			if($duration == 7 || $duration == 14){
				$duration = $duration;
				$lapse ='+0 day';
				$durationMonthDay = " days";
			}elseif($duration <= 3){
				$lapse ='+6 days';
				
				$start_date = date('Y-m-d',strtotime('-'.$duration.' months'));
				$end_date = date('Y-m-d');
				$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
			}elseif($duration >= 6 && $duration <= 12){
				$duration = $duration;
				$lapse ='+1 month';
			}elseif($duration == 24){
				$duration = $duration/3;
				$lapse = '+3 month';
			}
			
		}else{
			$data = ModuleByDateRange::select('duration')->where('request_id',$campaign_id)->where('module','google_ads')->first();
			
			if(!empty($data)){
				

				$default_duration = $data->duration;

				if($data->duration == 7 || $data->duration == 14){
					$lapse ='+0 day';
					$durationMonthDay = " days";
					$start_date = date('Y-m-d',strtotime('-'.$data->duration.' days'));
					$end_date = date('Y-m-d');
					$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				}elseif($data->duration <= 3){
					$lapse ='+6 days';

					$start_date = date('Y-m-d',strtotime('-'.$data->duration.' months'));
					$end_date = date('Y-m-d');
					$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
				}elseif($data->duration >= 6 && $data->duration <= 12){
					$duration = $data->duration;
					$lapse ='+1 month';
				}elseif($data->duration == 24){
					$duration = $data->duration/3;
					$lapse = '+3 month';
				}
			}else{
				$duration =$default_duration =  3;
				$lapse = '+6 days';
				// $lapse = '+6 days';
				$start_date = date('Y-m-d',strtotime('-3 months'));
				$end_date = date('Y-m-d');
				$duration = ModuleByDateRange::calculate_weeks($start_date,$end_date);
			}
		}

		
		for($i=1;$i<=$duration;$i++){
			if($i==1){	
				$start_date = date('Y-m-d',strtotime('-'.$default_duration. $durationMonthDay));
				$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
				
			}else{
				$start_date = date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
				$end_date = date('Y-m-d',strtotime($lapse,strtotime($start_date)));
			}
			if($end_date > date('Y-m-d')){
				
				$end_date = date('Y-m-d',strtotime('-1 day'));
			}
			$res[$i]['start_date'] = $start_date;
			$res[$i]['end_date'] = $end_date;
		}		
		return $res;
		
	}

	public function ppc_summary_impressions_graph(Request $request){
		$label = $result = array();

		$data = ModuleByDateRange::select('duration','status')->where('request_id',$request['campaign_id'])->where('module','google_ads')->first();
       	
       	if($request->duration <> null){

			$duration = $request->duration;
			$durationOld = $request->duration * 2;

		}else{

			if(!empty($data)){
				$duration = $data->duration;
				$durationOld = $data->duration * 2;
			}else{
				$duration = 3;
				$durationOld = 3 * 2;
			}
		}

		if($request->compare <> null  && $request->compare <> ''){
			$compareStatus =  $request->compare;
		}else{
			$compareStatus =  $data <> null ? $data->status : 0 ;
		}
		
		
        
		$dates = $this->overview_duration($request['campaign_id'],$request['account_id'],$duration);
		$urlValues = env('FILE_PATH')."public/adwords/".$request['campaign_id'].'/graphs/overview.json'; 
	    if(file_exists($urlValues)){
	     	$dataValues = file_get_contents($urlValues);
	        $values = json_decode($dataValues,true);
	    }else{
	    	$values = array();
	    }   
		
        $previousDuration = $this->overview_duration_previous($duration);
        
        $counterfor = $counter = $impressionCount = $clickCount  = $ctrCount = $costCount = $conversionsCount = $average_cpc = $conversion_rate = $cpc_rate = 0;
        
       
        foreach ($dates as $keyDate => $valueDate) {

        	if($counter == 0){
        		$firstDate = $valueDate['start_date'];
        		$rangeStart = $valueDate['start_date'];
        	}
        	$lastDate = $valueDate['end_date'];

	        $begin = strtotime($valueDate['start_date']);
	        $end = strtotime($valueDate['end_date']);
	        $impressions = $clicks = $ctr = $cost = $conversions = $cpcRate = 0;
	        for($i = $begin; $i <= $end; $i = $i+86400){
	        	
	        	$impressions += isset($values['impression'][date('Ymd',$i)]) ?$values['impression'][date('Ymd',$i)]:0;
				$clicks += isset($values['clicks'][date('Ymd',$i)]) ? $values['clicks'][date('Ymd',$i)]:0;
				$cost += isset($values['cost'][date('Ymd',$i)]) ? $values['cost'][date('Ymd',$i)]:0;
				$conversions += isset($values['conversions'][date('Ymd',$i)]) ? $values['conversions'][date('Ymd',$i)]:0;
				
				$cpcRate += isset($values['cost_per_conversion'][date('Ymd',$i)]) ? $values['cost_per_conversion'][date('Ymd',$i)]:0;


	        	$impressionCount += isset($values['impression'][date('Ymd',$i)]) ?$values['impression'][date('Ymd',$i)]:0;
				$clickCount += isset($values['clicks'][date('Ymd',$i)]) ? $values['clicks'][date('Ymd',$i)]:0;
				$costCount += isset($values['cost'][date('Ymd',$i)]) ? $values['cost'][date('Ymd',$i)]:0;
				$conversionsCount += isset($values['conversions'][date('Ymd',$i)]) ? $values['conversions'][date('Ymd',$i)]:0;

				$res['summaryGraph']['date_range'][$counterfor] = date('M d, Y',$i);
				$res['summaryGraph']['impressions'][$counterfor] = isset($values['impression'][date('Ymd',$i)]) ?$values['impression'][date('Ymd',$i)]:0;
				$res['summaryGraph']['clicks'][$counterfor] = isset($values['clicks'][date('Ymd',$i)]) ? $values['clicks'][date('Ymd',$i)]:0;
				$res['summaryGraph']['conversions'][$counterfor] = isset($values['conversions'][date('Ymd',$i)]) ? $values['conversions'][date('Ymd',$i)]:0;
				$res['summaryGraph']['compare'] = $data <> null ? $data->status : 0 ;

				$res['performanceGraph']['date_range'][$counterfor] = date('M d, Y',$i);
				$res['performanceGraph']['cost'][$counterfor] = isset($values['cost'][date('Ymd',$i)]) ? (float)number_format($values['cost'][date('Ymd',$i)], 2, '.', '') : 0;
				$res['performanceGraph']['cpc'][$counterfor] = isset($values['average_cpc'][date('Ymd',$i)]) ? (float)number_format($values['average_cpc'][date('Ymd',$i)], 2, '.', '') : 0;
				$res['performanceGraph']['averagecpm'][$counterfor] = isset($values['average_cpm'][date('Ymd',$i)]) ? (float)number_format($values['average_cpm'][date('Ymd',$i)], 2, '.', '') : 0;


				

				$res['performanceGraph']['revenue_per_click'][$counterfor] = isset($values['conversion_value'][date('Ymd',$i)]) && $values['conversion_value'][date('Ymd',$i)] > 0  ? (float)number_format($values['conversion_value'][date('Ymd',$i)]/$values['clicks'][date('Ymd',$i)], 2, '.', '') : 0;

				$res['performanceGraph']['total_value'][$counterfor] = isset($values['conversion_value'][date('Ymd',$i)]) ? (float)number_format($values['conversion_value'][date('Ymd',$i)], 2, '.', '') : 0;
				$res['performanceGraph']['compare'] = $data <> null ? $data->status : 0 ;

				$counterfor++;
			}

			
			$ctrs = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;
			$avgCpc = $cost > 0 && $clicks > 0 ?$cost/$clicks:0 ;
			$conversionRate = $conversions > 0 && $clicks > 0 ?$conversions/$clicks:0 ;
			
			/*$ctrs = $clicks/$impressions;
			$avgCpc = $cost/$clicks;
			$conversionRate = $conversions/$clicks;*/
			

			$ctrCount += $ctrs;
			$average_cpc += $avgCpc;
			$conversion_rate += $conversionRate;
			$cpc_rate += $cpcRate;

			$res['from_datelabel'][$counter] = date('M d, Y',$end);
			$res['impressions'][$counter] = (float)number_format($impressions, 2, '.', '');
			$res['clicks'][$counter] = (float)number_format($clicks, 2, '.', '');
			$res['ctr'][$counter] = (float)number_format($ctrs, 2, '.', ''); 
			$res['cost'][$counter] = (float)number_format($cost, 2, '.', '');
			$res['conversions'][$counter] = (float)number_format($conversions, 2, '.', '');
			$res['average_cpc'][$counter] = (float)number_format($avgCpc, 2, '.', '');
			$res['conversion_rate'][$counter] = (float)number_format($conversionRate, 2, '.', '');
			$res['cpc_rate'][$counter] = (float)number_format($cpcRate, 2, '.', '');

        	$counter++;
        }

        $ctrCounter = $impressionCount <> 0 ? ($clickCount/$impressionCount)*100 : 0 ;
        $conversionCounter = $clickCount <> 0 ? ($conversionsCount/$clickCount)*100 :0;
        $cpcRateCounter = $costCount <> 0 && $conversionsCount <> 0 ? ($costCount/$conversionsCount) : 0;

        $previousSummary = $this->ppc_summary_overview_previous($previousDuration,$values);
       
        $res['summary']['impressionCount'] = (float)number_format($impressionCount, 2, '.', '');
        $res['summary']['clickCount'] = (float)number_format($clickCount, 2, '.', '');
        $res['summary']['ctrCount'] = (float)number_format($ctrCounter, 2, '.', '');
        $res['summary']['costCount'] = (float)number_format($costCount, 2, '.', '');
        $res['summary']['conversionsCount'] = (float)number_format($conversionsCount, 2, '.', '');
        $res['summary']['average_cpc'] = (float)number_format($average_cpc/$counter, 2, '.', '');
        $res['summary']['conversion_rate'] = (float)number_format($conversionCounter, 2, '.', '');
        $res['summary']['cpc_rate'] = (float)number_format($cpcRateCounter, 2, '.', '');
        $res['summaryPrevious'] = $previousSummary;

        $res['summaryGraph']['impressions_previous'] = $previousSummary['impressions_previous'];
        $res['summaryGraph']['clicks_previous'] = $previousSummary['clicks_previous'];
        $res['summaryGraph']['conversions_previous'] = $previousSummary['conversions_previous'];
        
        $res['performanceGraph']['cost_previous'] = $previousSummary['cost_previous'];
        $res['performanceGraph']['cpc_previous'] = $previousSummary['cpc_previous'];
        $res['performanceGraph']['averagecpm_previous'] = $previousSummary['averagecpm_previous'];
        $res['performanceGraph']['revenue_per_click_previous'] = $previousSummary['revenue_per_click_previous'];
        $res['performanceGraph']['total_value_previous'] = $previousSummary['total_value_previous'];


        if(isset($impressionCount) && isset($previousSummary['impressionCount'])){
			$impressions_percentage = GoogleAdsCustomer::calculate_percentage($impressionCount,$previousSummary['impressionCount']);
		}elseif(!isset($impressionCount) && isset($previousSummary['impressionCount'])){
			$impressions_percentage = '-100';
		}elseif(isset($impressionCount) && !isset($previousSummary['impressionCount'])){
			$impressions_percentage = '0';
		}else{
			$impressions_percentage = '0';
		}

		if(isset($costCount) && isset($previousSummary['costCount'])){
			$costs_percentage = GoogleAdsCustomer::calculate_percentage($costCount,$previousSummary['costCount']);
		}elseif(!isset($costCount) && isset($previousSummary['costCount'])){
			$costs_percentage = '-100';
		}elseif(isset($costCount) && !isset($previousSummary['costCount'])){
			$costs_percentage = '0';
		}else{
			$costs_percentage = '0';
		}

		if(isset($clickCount) && isset($previousSummary['clickCount'])){
			$clicks_percentage = GoogleAdsCustomer::calculate_percentage($clickCount,$previousSummary['clickCount']);
		}elseif(!isset($clickCount) && isset($previousSummary['clickCount'])){
			$clicks_percentage = '-100';
		}elseif(isset($clickCount) && !isset($previousSummary['clickCount'])){
			$clicks_percentage = '0';
		}else{
			$clicks_percentage = '0';
		}

		if(isset($res['summary']['average_cpc']) && isset($previousSummary['average_cpc'])){
			$average_cpc_percentage = GoogleAdsCustomer::calculate_percentage($res['summary']['average_cpc'],$previousSummary['average_cpc']);
		}elseif(!isset($res['summary']['average_cpc']) && isset($previousSummary['average_cpc'])){
			$average_cpc_percentage = '-100';
		}elseif(isset($res['summary']['average_cpc']) && !isset($previousSummary['average_cpc'])){
			$average_cpc_percentage = '0';
		}else{
			$average_cpc_percentage = '0';
		}

		if(isset($ctrCounter) && isset($previousSummary['ctrCount'])){
			$ctr_percentage = GoogleAdsCustomer::calculate_percentage($ctrCounter,$previousSummary['ctrCount']);
		}elseif(!isset($ctrCounter) && isset($previousSummary['ctrCount'])){
			$ctr_percentage = '-100';
		}elseif(isset($ctrCounter) && !isset($previousSummary['ctrCount'])){
			$ctr_percentage = '0';
		}else{
			$ctr_percentage = '0';
		}
		

		if(isset($conversionsCount) && isset($previousSummary['conversionsCount'])){
			$conversions_percentage = GoogleAdsCustomer::calculate_percentage($conversionsCount,$previousSummary['conversionsCount']);
		}elseif(!isset($conversionsCount) && isset($previousSummary['conversionsCount'])){
			$conversions_percentage = '-100';
		}elseif(isset($conversionsCount) && !isset($previousSummary['conversionsCount'])){
			$conversions_percentage = '0';
		}else{
			$conversions_percentage = '0';
		}


		if(isset($conversionCounter) && isset($previousSummary['conversion_rate'])){
			$conversion_rates_percentage = GoogleAdsCustomer::calculate_percentage($conversionCounter,$previousSummary['conversion_rate']);
		}elseif(!isset($conversionCounter) && isset($previousSummary['conversion_rate'])){
			$conversion_rates_percentage = '-100';
		}elseif(isset($conversionCounter) && !isset($previousSummary['conversion_rate'])){
			$conversion_rates_percentage = '0';
		}else{
			$conversion_rates_percentage = '0';
		}


		if(isset($cpcRateCounter) && isset($previousSummary['cpc_rate'])){
			$cost_per_conversions_percentage = GoogleAdsCustomer::calculate_percentage($cpcRateCounter,$previousSummary['cpc_rate']);
		}elseif(!isset($cpcRateCounter) && isset($previousSummary['cpc_rate'])){
			$cost_per_conversions_percentage = '-100';
		}elseif(isset($cpcRateCounter) && !isset($previousSummary['cpc_rate'])){
			$cost_per_conversions_percentage = '0';
		}else{
			$cost_per_conversions_percentage = '0';
		}


		$res['impressions_percentage'] = $impressions_percentage;
		$res['costs_percentage'] = $costs_percentage;
		$res['clicks_percentage'] = $clicks_percentage;
		$res['average_cpc_percentage'] = $average_cpc_percentage;
		$res['ctr_percentage'] = $ctr_percentage;
		$res['conversions_percentage'] = $conversions_percentage;
		$res['conversion_rates_percentage'] = $conversion_rates_percentage;
		$res['cost_per_conversions_percentage'] = $cost_per_conversions_percentage;
		$res['compare'] = $compareStatus;
		$res['firstDate'] = $firstDate;
		$res['lastDate'] = $lastDate;
		if($compareStatus == 1){
			$res['range'] = date('M d Y',strtotime($rangeStart)). ' - ' . date('M d Y',strtotime($lastDate)) .' (compared to '. date('M d Y',strtotime($previousSummary['firstDate'])). ' - ' . date('M d Y',strtotime($previousSummary['lastDate'])).')';
		}else{
			$res['range'] = date('M d Y',strtotime($rangeStart)). ' - ' . date('M d Y',strtotime($lastDate));
		}

		return response()->json($res);
	}

	public function ppc_summary_overview_previous($dates,$values){

		$counterfor = $counter = $impressionCount = $clickCount  = $ctrCount = $costCount = $conversionsCount = $average_cpc = $conversion_rate = $cpc_rate= $cpcRate = 0;

        
        foreach ($dates as $keyDate => $valueDate) {
        	if($counter == 0){
        		$rangeStart = $valueDate['start_date'];
        	}
        	$lastDate = $valueDate['end_date'];
	        $begin = strtotime($valueDate['start_date']);
	        $end = strtotime($valueDate['end_date']);
	        $impressions = $clicks = $ctr = $cost = $conversions = 0;
	        for($i = $begin; $i <= $end; $i = $i+86400){
	        	
	        	$impressions += isset($values['impression'][date('Ymd',$i)]) ?$values['impression'][date('Ymd',$i)]:0;
				$cost += isset($values['cost'][date('Ymd',$i)]) ? $values['cost'][date('Ymd',$i)]:0;
				$clicks += isset($values['clicks'][date('Ymd',$i)]) ? $values['clicks'][date('Ymd',$i)]:0;
				$conversions += isset($values['conversions'][date('Ymd',$i)]) ? $values['conversions'][date('Ymd',$i)]:0;
				$cpcRate += isset($values['cost_per_conversion'][date('Ymd',$i)]) ? $values['cost_per_conversion'][date('Ymd',$i)]:0;

	        	$impressionCount += isset($values['impression'][date('Ymd',$i)]) ?$values['impression'][date('Ymd',$i)]:0;
				$clickCount += isset($values['clicks'][date('Ymd',$i)]) ? $values['clicks'][date('Ymd',$i)]:0;
				$costCount += isset($values['cost'][date('Ymd',$i)]) ? $values['cost'][date('Ymd',$i)]:0;
				$conversionsCount += isset($values['conversions'][date('Ymd',$i)]) ? $values['conversions'][date('Ymd',$i)]:0;


				/*$res['summaryGraph']['date_range'][$counterfor] = date('M d, Y',$i);*/
				$res['date_rangePre'][$counterfor] = date('M d, Y',$i);
				$res['impressions_previous'][$counterfor] = isset($values['impression'][date('Ymd',$i)]) ?$values['impression'][date('Ymd',$i)]:0;
				$res['clicks_previous'][$counterfor] = isset($values['clicks'][date('Ymd',$i)]) ? $values['clicks'][date('Ymd',$i)]:0;
				$res['conversions_previous'][$counterfor] = isset($values['conversions'][date('Ymd',$i)]) ? $values['conversions'][date('Ymd',$i)]:0;
				
		
				$res['cost_previous'][$counterfor] = isset($values['cost'][date('Ymd',$i)]) ? (float)number_format($values['cost'][date('Ymd',$i)], 2, '.', '') : 0;
				$res['cpc_previous'][$counterfor] = isset($values['average_cpc'][date('Ymd',$i)]) ? (float)number_format($values['average_cpc'][date('Ymd',$i)], 2, '.', '') : 0;
				$res['averagecpm_previous'][$counterfor] = isset($values['average_cpm'][date('Ymd',$i)]) ? (float)number_format($values['average_cpm'][date('Ymd',$i)], 2, '.', '') : 0;


				$res['revenue_per_click_previous'][$counterfor] = isset($values['clicks'][date('Ymd',$i)]) && isset($values['conversion_value'][date('Ymd',$i)]) && $values['clicks'][date('Ymd',$i)] <> 0  ? (float)number_format($values['conversion_value'][date('Ymd',$i)]/$values['clicks'][date('Ymd',$i)], 2, '.', '') : 0;

				$res['total_value_previous'][$counterfor] = isset($values['conversion_value'][date('Ymd',$i)]) && $values['conversion_value'][date('Ymd',$i)] > 0 ? (float)number_format($values['conversion_value'][date('Ymd',$i)], 2, '.', '') : 0;

				$counterfor++;

			}

			
			/*$ctrs = $clicks == 0 || $impressions == 0? 0:($clicks/$impressions)*100;
			$ctrs = $clicks > 0 && $impressions > 0 ?($clicks/$impressions)*100:0 ;*/
			$avgCpc = $cost == 0 || $clicks == 0? 0: $cost/$clicks;
			$conversionRate = $conversions == 0 || $clicks == 0? 0: $conversions/$clicks;
			

			//$ctrCount += $ctrs;
			$average_cpc += $avgCpc;
			// $conversion_rate += $conversionRate;
			$cpc_rate += $cpcRate;


        	$counter++;
        }

		$ctrCount = $clickCount > 0 && $impressionCount > 0 ?($clickCount/$impressionCount)*100:0 ;
        $conversion_rate = $clickCount <> 0 ? ($conversionsCount/$clickCount)*100 :0;
        $cpc_rate = $costCount <> 0 && $conversionsCount <> 0 ? ($costCount/$conversionsCount) : 0;

        $res['impressionCount'] = (float)number_format($impressionCount, 2, '.', '');
        $res['clickCount'] = (float)number_format($clickCount, 2, '.', '');
        $res['costCount'] = (float)number_format($costCount, 2, '.', '');
        $res['conversionsCount'] = (float)number_format($conversionsCount, 2, '.', '');
        $res['ctrCount'] = (float)number_format($ctrCount, 2, '.', '');
        $res['average_cpc'] = (float)number_format($average_cpc/$counter, 2, '.', '');;
        $res['conversion_rate'] = (float)number_format($conversion_rate, 2, '.', '');
        $res['cpc_rate'] = (float)number_format($cpc_rate, 2, '.', '');
        $res['lastDate'] = $lastDate;
        $res['firstDate'] = $rangeStart;

        return $res;
	}

	public function ppc_summary_cost_graph(Request $request){
		$label = $result = array();
		$dates = $this->overview_duration($request['campaign_id'],$request['account_id']);
		for($i=1;$i<=count($dates);$i++){
			$summary_stats = AdwordsCampaignDetail::
			where('client_id',$request['account_id'])
			->whereBetween('day',[$dates[$i]['start_date'],$dates[$i]['end_date']])
			->select(
				DB::raw('sum(cost) as cost')
			)
			->groupBy('campaign_id')
			->first();

			$label[] = date('M d, Y',strtotime($dates[$i]['end_date']));
			$result[] = isset($summary_stats->cost)?(float)number_format($summary_stats->cost, 2, '.', ''):'0';
		}

		$res['from_datelabel'] = $label;
		$res['cost'] = $result;

		return response()->json($res);
	}

	public function ppc_summary_clicks_graph(Request $request){
		$label = $result = array();

		$dates = $this->overview_duration($request['campaign_id'],$request['account_id']);
		for($i=1;$i<=count($dates);$i++){
			$summary_stats = AdwordsCampaignDetail::
			where('client_id',$request['account_id'])
			->whereBetween('day',[$dates[$i]['start_date'],$dates[$i]['end_date']])
			->select(
				DB::raw('sum(clicks) as clicks')
			)
			->groupBy('campaign_id')
			->first();

			$label[] = date('M d, Y',strtotime($dates[$i]['end_date']));
			$result[] = isset($summary_stats->clicks)?(float)number_format($summary_stats->clicks, 2, '.', ''):'0';
		}

		$res['from_datelabel'] = $label;
		$res['clicks'] = $result;
		return response()->json($res);
		
	}

	// public function ppc_summary_averageCpc_graph(Request $request){
	// 	$label = $result = array();
	// 	$dates = $this->overview_duration($request['campaign_id'],$request['account_id']);
	// 	for($i=1;$i<=count($dates);$i++){
	// 		$summary_stats = AdwordsCampaignDetail::
	// 		where('client_id',$request['account_id'])
	// 		->whereBetween('day',[$dates[$i]['start_date'],$dates[$i]['end_date']])
	// 		->select(
	// 			DB::raw('sum(cost)/sum(clicks) as average_cpc')
	// 		)
	// 		->groupBy('campaign_id')
	// 		->first();

	// 		$label[] = date('M d, Y',strtotime($dates[$i]['end_date']));
	// 		$result[] = isset($summary_stats->average_cpc)?(float)number_format($summary_stats->average_cpc, 2, '.', ''):'0';
	// 	}

	// 	$label = array_reverse($label);
	// 	$result = array_reverse($result);

	// 	$res['from_datelabel'] = $label;
	// 	$res['average_cpc'] = $result;

		
	// 	return response()->json($res);
		
	// }

	// public function ppc_summary_ctr_graph(Request $request){
	// 	$label = $result = array();
	// 	$dates = $this->overview_duration($request['campaign_id'],$request['account_id']);
	// 	for($i=1;$i<=count($dates);$i++){
	// 		$summary_stats = AdwordsCampaignDetail::
	// 		where('client_id',$request['account_id'])
	// 		->whereBetween('day',[$dates[$i]['start_date'],$dates[$i]['end_date']])
	// 		->select(
	// 			DB::raw('sum(clicks)/sum(impressions) as ctr')
	// 		)
	// 		->groupBy('campaign_id')
	// 		->first();

	// 		$label[] = date('M d, Y',strtotime($dates[$i]['end_date']));
	// 		$result[] = isset($summary_stats->ctr)?(float)number_format($summary_stats->ctr, 2, '.', ''):'0';
	// 	}

	// 	$res['from_datelabel'] = $label;
	// 	$res['ctr'] = $result;
	// 	return response()->json($res);
		
	// }

	// public function ppc_summary_conversions_graph(Request $request){
	// 	$label = $result = array();
	// 	$dates = $this->overview_duration($request['campaign_id'],$request['account_id']);
	// 	for($i=1;$i<=count($dates);$i++){
	// 		$summary_stats = AdwordsCampaignDetail::
	// 		where('client_id',$request['account_id'])
	// 		->whereBetween('day',[$dates[$i]['start_date'],$dates[$i]['end_date']])
	// 		->select(
	// 			DB::raw('sum(conversions) as conversions')
	// 		)
	// 		->groupBy('campaign_id')
	// 		->first();
	// 		$label[] = date('M d, Y',strtotime($dates[$i]['end_date']));
	// 		$result[] = isset($summary_stats->conversions)?(float)number_format($summary_stats->conversions, 2, '.', ''):'0';
	// 	}


	// 	$res['from_datelabel'] = $label;
	// 	$res['conversions'] = $result;
	// 	return response()->json($res);
		
	// }

	// public function ppc_summary_conversion_rate_graph(Request $request){
	// 	$label = $result = array();
	// 	$dates = $this->overview_duration($request['campaign_id'],$request['account_id']);
	// 	for($i=1;$i<=count($dates);$i++){
	// 		$summary_stats = AdwordsCampaignDetail::
	// 		where('client_id',$request['account_id'])
	// 		->whereBetween('day',[$dates[$i]['start_date'],$dates[$i]['end_date']])
	// 		->select(
	// 			DB::raw('sum(conversions)/sum(clicks) as conversion_rate')
	// 		)
	// 		->groupBy('campaign_id')
	// 		->first();

	// 		$label[] = date('M d, Y',strtotime($dates[$i]['end_date']));
	// 		$result[] = isset($summary_stats->conversion_rate)?(float)number_format($summary_stats->conversion_rate, 2, '.', ''):'0';
	// 	}

		

	// 	$res['from_datelabel'] = $label;
	// 	$res['conversion_rate'] = $result;
	// 	return response()->json($res);
		
	// }

	// public function ppc_summary_cpc_rate_graph(Request $request){
	// 	$label = $result = array();
	// 	$dates = $this->overview_duration($request['campaign_id'],$request['account_id']);
	// 	for($i=1;$i<=count($dates);$i++){

	// 		$summary_stats = AdwordsCampaignDetail::
	// 		where('client_id',$request['account_id'])
	// 		->whereBetween('day',[$dates[$i]['start_date'],$dates[$i]['end_date']])
	// 		->select(
	// 			DB::raw('sum(cost)/sum(conversions) as cpc_rate')
	// 		)
	// 		->groupBy('campaign_id')
	// 		->first();

	// 		$label[] = date('M d, Y',strtotime($dates[$i]['end_date']));
	// 		$result[] = isset($summary_stats->cpc_rate)?(float)number_format($summary_stats->cpc_rate, 2, '.', ''):'0';
	// 	}
		

	// 	$res['from_datelabel'] = $label;
	// 	$res['cpc_rate'] = $result;

	// 	return response()->json($res);
		
	// }

	public function ajax_fetch_date_range_chart(Request $request){
		$checkIfExists = ModuleByDateRange::where('request_id',$request->campaign_id)->where('module','google_ads')->first();

		$account_id = $request->account_id;

		if(isset($request->start_date) && isset($request->end_date)){
			$start_date = date('Ymd',strtotime($request->start_date));
			$end_date =date('Ymd',strtotime($request->end_date));
			$diff = abs(strtotime($end_date) - strtotime($start_date));
			$days = floor(($diff)/ (60*60*24));
		} elseif(isset($checkIfExists->start_date) && isset($checkIfExists->end_date)){
			$start_date = date('Ymd',strtotime($checkIfExists->start_date));
			$end_date = date('Ymd',strtotime($checkIfExists->end_date));
		} else{
			$start_date = date('Ymd',strtotime('-30 days'));
			$end_date = date('Ymd');
		}

		$dates = $this->getDatesFromRange($start_date,$end_date);	 
		$summary_chart = $this->chartData($dates,$account_id);


		if(isset($request->cmp_start_date) && isset($request->cmp_end_date)){
			$cmp_diff = abs(strtotime(date('Ymd',strtotime($request->cmp_start_date))) - strtotime(date('Ymd',strtotime($request->cmp_end_date))));
			$cmp_days = floor(($cmp_diff)/ (60*60*24));

			$compare_start_date = date('Ymd', strtotime($request->cmp_start_date));
			$compare_end_date = date('Ymd', strtotime($request->cmp_end_date));

			if($cmp_days != $days){
				return false;
			}
		}

		if($request->has('compare')){
			$compare_dates = $this->getDatesFromRange($compare_start_date,$compare_end_date);	
			$compare_summary_chart = $this->chartData($compare_dates,$account_id);
			$summary_chart['clicks_previous'] = $compare_summary_chart['clicks'];
			$summary_chart['conversions_previous'] = $compare_summary_chart['conversions'];
			$summary_chart['impressions_previous'] = $compare_summary_chart['impressions'];
			$summary_chart['cost_previous'] = $compare_summary_chart['cost'];
			$summary_chart['cpc_previous'] = $compare_summary_chart['cpc'];
			$summary_chart['revenue_per_click_previous'] = $compare_summary_chart['revenue_per_click'];
			$summary_chart['total_value_previous'] = $compare_summary_chart['total_value'];
			$summary_chart['averagecpm_previous'] = $compare_summary_chart['averagecpm'];			
		} 

		if(isset($checkIfExists->compare_start_date) && isset($checkIfExists->compare_end_date)){
			$compare_start_date = date('Ymd',strtotime($checkIfExists->compare_start_date));
			$compare_end_date = date('Ymd',strtotime($checkIfExists->compare_end_date));

			$compare_dates = $this->getDatesFromRange($compare_start_date,$compare_end_date);	
			$compare_summary_chart = $this->chartData($compare_dates,$account_id);
			$summary_chart['clicks_previous'] = $compare_summary_chart['clicks'];
			$summary_chart['conversions_previous'] = $compare_summary_chart['conversions'];
			$summary_chart['impressions_previous'] = $compare_summary_chart['impressions'];
			$summary_chart['cost_previous'] = $compare_summary_chart['cost'];
			$summary_chart['cpc_previous'] = $compare_summary_chart['cpc'];
			$summary_chart['revenue_per_click_previous'] = $compare_summary_chart['revenue_per_click'];
			$summary_chart['total_value_previous'] = $compare_summary_chart['total_value'];
			$summary_chart['averagecpm_previous'] = $compare_summary_chart['averagecpm'];	
		}

		$summary_chart['date_range'] =  $dates;

		return response()->json($summary_chart);
	}

	public function ajax_fetch_summary_statistics(Request $request){
		$role_id = User::get_user_role(Auth::user()->id);
		$account_id = $request->account_id;
		if(isset($request->start_date) && isset($request->end_date)){
			$start_date = date('Ymd',strtotime($request->start_date));
			$end_date =date('Ymd',strtotime($request->end_date));
			$diff = abs(strtotime($end_date) - strtotime($start_date));
			$days = floor(($diff)/ (60*60*24));

		} else{
			$start_date = date('Ymd',strtotime('-30 days'));
			$end_date = date('Ymd');
		}

		if(isset($request->cmp_start_date) && isset($request->cmp_end_date)){
			$cmp_diff = abs(strtotime(date('Ymd',strtotime($request->cmp_start_date))) - strtotime(date('Ymd',strtotime($request->cmp_end_date))));
			$cmp_days = floor(($cmp_diff)/ (60*60*24));

			$compare_start_date = date('Ymd', strtotime($request->cmp_start_date));
			$compare_end_date = date('Ymd', strtotime($request->cmp_end_date));


			if($cmp_days != $days){
				$summary_stats_data['status'] = false;
				$summary_stats_data['message'] = 'Please select '.$days.' days for comparison';
				return response()->json($summary_stats_data);
			}
		}


		$campaign_data  = SemrushUserAccount::findorfail($request->campaign_id);
		$campaign_id = $request->campaign_id;
		$user_id = $campaign_data->user_id;
		if($role_id != 4){
			$check_if = ModuleByDateRange::where('request_id',$request->campaign_id)->where('module','google_ads')->first();

			if(empty($check_if)){
				ModuleByDateRange::create([
					'request_id'=>$request->campaign_id,
					'user_id'=>$user_id,
					'module'=>'google_ads',
					'start_date'=>$start_date,
					'end_date'=>$end_date,
					'compare_start_date'=>$compare_start_date,
					'compare_end_date'=>$compare_end_date,
					'status'=>$status
				]);
			}else{
				ModuleByDateRange::where('id',$check_if->id)->update([
					'start_date'=>$start_date,
					'end_date'=>$end_date,
					'compare_start_date'=>$compare_start_date,
					'compare_end_date'=>$compare_end_date,
					'status'=>$status
				]);
			}
		}

		return true;
	}


	public function ajax_save_in_csv(){
		$semrush_data = SemrushUserAccount::
		with(array('google_adwords_account'=>function($query){
			$query->select('id','customer_id');
		}))
		->where('status','0')
		->whereNotNull('google_ads_id')
		->whereNotNull('google_ads_campaign_id')
		->where('id',168)
		->get();




		if(!empty($semrush_data) && isset($semrush_data)){
			foreach($semrush_data as $key=>$value){
				$project_id = $value->id;
				$ads_customer_id = $value->google_adwords_account->customer_id;

				$googleUserDetails = GoogleAnalyticsUsers::findorfail($value->google_ads_id);
				$refreshToken = $googleUserDetails->google_refresh_token;
				$adwordsSession  = $this->google_ads_auth($ads_customer_id,$refreshToken);
				
				// echo "<pre>";
				// print_r($googleUserDetails);				
				// die;

				$today = date('Y-m-d');
				$start_date = date('Ymd',strtotime('-2 year'));
				$end_date = date('Ymd',strtotime('-1 day'));


				/*$if_exists = AdwordsCampaignDetail::
				select('report_date')
				->where('client_id',$ads_customer_id)
				->orderBy('id','desc')
				->first();*/

				$fileName = $ads_customer_id.'_campaigns_new.csv';
				// $keywords_fileName = $ads_customer_id.'_keywords.csv';
				// $ads_fileName = $ads_customer_id.'_ads.csv';
				// $adgroup_fileName = $ads_customer_id.'_adgroup.csv';
				// $place_file = $ads_customer_id.'_placeholder.csv';
				/*storing Data in db using csv for today*/
				//$this->campaign_reports_query_data($adwordsSession,$fileName,$ads_customer_id,$today,$project_id);

				 // $this->keywords_reports_query($adwordsSession,$start_date,$end_date,$keywords_fileName,$ads_customer_id,$today);
				 
				// $this->ads_reports_query($adwordsSession,$start_date,$end_date,$ads_fileName,$ads_customer_id,$today);
				
				// $this->ads_placeholder_reports_query($adwordsSession,$start_date,$end_date,$place_file,$ads_customer_id,$today);

				$this->campaign_reports_query_data($adwordsSession,$fileName,$ads_customer_id,$today,$project_id);
				/*$this->keywords_reports_query_data($adwordsSession,$start_date,$end_date,$keywords_fileName,$ads_customer_id,$today,$project_id);
				$this->ads_reports_query_data($adwordsSession,$start_date,$end_date,$ads_fileName,$ads_customer_id,$today,$project_id);
				$this->adsGroup_reports_query_data($adwordsSession,$start_date,$end_date,$adgroup_fileName,$ads_customer_id,$today,$project_id);
				$this->ads_placeholder_reports_query_data($adwordsSession,$start_date,$end_date,$place_file,$ads_customer_id,$today,$project_id);*/
			}


		}

		
	}

	private function campaign_reports_query_data($session,$fileName,$account_id,$today,$project_id){	

		$final = $array =  $campaigns = $device = $networks =  $adSlots =  $file_content =array();

		//$dates = $this->get_data_dates(48);


		$start_date = date('Ymd',strtotime('-2 year'));
		$end_date = date('Ymd',strtotime('-1 day'));

	

		$query = (new ReportQueryBuilder())
        ->select(['Headline','HeadlinePart1','HeadlinePart2','ExpandedTextAdHeadlinePart3','DisplayUrl',
            'Description','Description1','Description2','AdType','Id','Impressions','Clicks','Cost',
            'Conversions',
            'ResponsiveSearchAdHeadlines','ResponsiveSearchAdDescriptions','ResponsiveSearchAdPath1','ResponsiveSearchAdPath2'
            ,'ExpandedTextAdDescription2','ExpandedDynamicSearchCreativeDescription2','CreativeFinalUrls','Path1','Path2',
            'LongHeadline','MultiAssetResponsiveDisplayAdHeadlines','MultiAssetResponsiveDisplayAdLongHeadline','MultiAssetResponsiveDisplayAdDescriptions','Ctr','Date'])
        ->from(ReportDefinitionReportType::AD_PERFORMANCE_REPORT)
        ->duringDateRange("$start_date,$end_date")
        ->build();

		$reportDownloader = new ReportDownloader($session);
		$reportDownloadResult = $reportDownloader->downloadReportWithAwql(sprintf('%s', $query), 'CSV');
		$reportDownloadResult->saveToFile(\env('FILE_PATH')."public/reports/".$fileName);

		$handle = fopen(\env('FILE_PATH').'public/reports/'.$fileName, "r");
		die('shruti');

		$adWordsServices = new AdWordsServices();
		/*$weekImp = array();*/
		$weekImp['imporession'] = array();
		try{

			for($i=0;$i<=count($dates)-1;$i++){
				
				$start_date = date('Ymd',strtotime('-2 year'));
				$end_date = date('Ymd',strtotime('-1 day'));
				
				// $query = (new ReportQueryBuilder())
				// ->select(['CampaignId','CampaignName','Impressions','Clicks','Cost','Conversions','Date','Device','AdNetworkType1','Slot','AccountCurrencyCode','CostPerConversion','Ctr','AverageCpc','ConversionRate','ConversionValue','AverageCost','AverageCpm','AdNetworkType2'])
				// ->from(ReportDefinitionReportType::CAMPAIGN_PERFORMANCE_REPORT)
				// ->duringDateRange("$end_date,$start_date")
				// ->build();

				$query = (new ReportQueryBuilder())
                ->select(['Headline','HeadlinePart1','HeadlinePart2','ExpandedTextAdHeadlinePart3','DisplayUrl',
                    'Description','Description1','Description2','AdType','Id','Impressions','Clicks','Cost',
                    'Conversions',
                    'ResponsiveSearchAdHeadlines','ResponsiveSearchAdDescriptions','ResponsiveSearchAdPath1','ResponsiveSearchAdPath2'
                    ,'ExpandedTextAdDescription2','ExpandedDynamicSearchCreativeDescription2','CreativeFinalUrls','Path1','Path2',
                    'LongHeadline','MultiAssetResponsiveDisplayAdHeadlines','MultiAssetResponsiveDisplayAdLongHeadline','MultiAssetResponsiveDisplayAdDescriptions','Ctr','Date'])
                ->from(ReportDefinitionReportType::AD_PERFORMANCE_REPORT)
                ->duringDateRange("$end_date,$start_date")
                ->build();

				$reportDownloader = new ReportDownloader($session);
				$reportDownloadResult = $reportDownloader->downloadReportWithAwql(sprintf('%s', $query), 'CSV');
				$reportDownloadResult->saveToFile(\env('FILE_PATH')."public/reports/".$fileName);

				$handle = fopen(\env('FILE_PATH').'public/reports/'.$fileName, "r");
				die('shruti');
				
				/*$handle = fopen(\env('FILE_PATH').'public/reports/9696489139_campaigns.csv', "r");*/
				$counter = 0;

				$datascrap = array();
				$logs[$i]['start_date'] = $start_date;
            	$logs[$i]['end_date'] = $end_date;
				while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {

					$counter++;
					if($counter <= 2){
						continue;
					}

					if($data[0] == 'Total'){
						continue;
					}
					$keyDate = date('Ymd',strtotime($data[6]));
					
					$impressionGraph['dates'][$keyDate] = $data[6];

					if(isset($impressionGraph['impression'][$keyDate])){
						$impressionGraph['impression'][$keyDate] += $data[2];
					}else{
						$impressionGraph['impression'][$keyDate] = $data[2];
					}
					if(isset($impressionGraph['clicks'][$keyDate])){
						$impressionGraph['clicks'][$keyDate] +=  $data[3];
					}else{
						$impressionGraph['clicks'][$keyDate] =  $data[3];
					}
					if(isset($impressionGraph['ctr'][$keyDate])){
						$impressionGraph['ctr'][$keyDate] +=  str_replace('%', '', $data[12]);
						// $impressionGraph['ctr'][$keyDate] =  $data[12];
					}else{
						$impressionGraph['ctr'][$keyDate] =  str_replace('%', '', $data[12]);
					}
					if(isset($impressionGraph['cost'][$keyDate])){
						$impressionGraph['cost'][$keyDate] +=  $data[4]/1000000;
					}else{
						$impressionGraph['cost'][$keyDate] =  $data[4]/1000000;
					}
					if(isset($impressionGraph['conversions'][$keyDate])){
						$impressionGraph['conversions'][$keyDate] +=  $data[5];
					}else{
						$impressionGraph['conversions'][$keyDate] =  $data[5];
					}

					if(isset($impressionGraph['average_cpc'][$keyDate])){
						$impressionGraph['average_cpc'][$keyDate] +=  $data[13]/1000000;
					}else{
						$impressionGraph['average_cpc'][$keyDate] =  $data[13]/1000000;
					}

					if(isset($impressionGraph['conversion_rate'][$keyDate])){
						$impressionGraph['conversion_rate'][$keyDate] +=  str_replace('%', '', $data[14]);
					}else{
						$impressionGraph['conversion_rate'][$keyDate] =  str_replace('%', '', $data[14]);
					}

					if(isset($impressionGraph['conversion_value'][$keyDate])){
						$impressionGraph['conversion_value'][$keyDate] +=  $data[15];
					}else{
						$impressionGraph['conversion_value'][$keyDate] =  $data[15];
					}

					if(isset($impressionGraph['average_cost'][$keyDate])){
						$impressionGraph['average_cost'][$keyDate] +=  $data[16]/1000000;
					}else{
						$impressionGraph['average_cost'][$keyDate] =  $data[16]/1000000;
					}

					if(isset($impressionGraph['average_cpm'][$keyDate])){
						$impressionGraph['average_cpm'][$keyDate] +=  $data[15]/1000000;
					}else{
						$impressionGraph['average_cpm'][$keyDate] =  $data[15]/1000000;
					}	

					if($i <= 23){

						$networkKey = str_replace(' ','',$data[18]);
						$networks[date('Ym',strtotime($end_date))][$networkKey] =  $data[18];

						$networksData[$networkKey]['dates'][] =  $data[6];
						$networksData[$networkKey]['impressions'][] =  $data[2];
						$networksData[$networkKey]['clicks'][] =  $data[3];
						$networksData[$networkKey]['ctr'][] =  str_replace('%', '', $data[12]);
						$networksData[$networkKey]['cost'][] =  $data[4]/1000000;
						$networksData[$networkKey]['conversions'][] =  $data[5];


						$deviceKey = str_replace(' ','',$data[7]);
						$device[date('Ym',strtotime($end_date))][$deviceKey] =  $data[7];

						$deviceData[$deviceKey]['dates'][] =  $data[6];
						$deviceData[$deviceKey]['impressions'][] =  $data[2];
						$deviceData[$deviceKey]['clicks'][] =  $data[3];
						$deviceData[$deviceKey]['ctr'][] =  str_replace('%', '', $data[12]);
						$deviceData[$deviceKey]['cost'][] =  $data[4]/1000000;
						$deviceData[$deviceKey]['conversions'][] =  $data[5];

						$adSlotsKey = str_replace(' ','',$data[9]);
						$adSlots[date('Ym',strtotime($end_date))][$adSlotsKey] =  $data[9];

						$adSlotsData[$adSlotsKey]['dates'][] =  $data[6];
						$adSlotsData[$adSlotsKey]['impressions'][] =  $data[2];
						$adSlotsData[$adSlotsKey]['clicks'][] =  $data[3];
						$adSlotsData[$adSlotsKey]['ctr'][] =  str_replace('%', '', $data[12]);
						$adSlotsData[$adSlotsKey]['cost'][] =  $data[4]/1000000;
						$adSlotsData[$adSlotsKey]['conversions'][] =  $data[5];


						$campaigns[date('Ym',strtotime($end_date))][$data[0]] =  $data[1];

						$file_content[$data[0]]['dates'][] =  $data[6];
						$file_content[$data[0]]['impressions'][] =  $data[2];
						$file_content[$data[0]]['clicks'][] =  $data[3];
						$file_content[$data[0]]['ctr'][] =  str_replace('%', '', $data[12]);
						$file_content[$data[0]]['cost'][] =  $data[4]/1000000;
						$file_content[$data[0]]['conversions'][] =  $data[5];
					}
			

				}
				
				if($i <= 23){
					if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks')) {
						mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks', 0777, true);
						file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($networksData,true),true));

						file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks/list.json', print_r(json_encode($networks,true),true));
					}else{
						file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($networksData,true),true));

						if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks/list.json')){
							file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks/list.json', print_r(json_encode($networks,true),true));
						}else{
						
					        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/networks/list.json', print_r(json_encode($networks,true),true));

						}
					}

					if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices')) {
						mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices', 0777, true);
						file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($deviceData,true),true));

						file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices/list.json', print_r(json_encode($device,true),true));
					}else{
						file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($deviceData,true),true));

						if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices/list.json')){
							file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices/list.json', print_r(json_encode($device,true),true));
						}else{
						
					        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/devices/list.json', print_r(json_encode($device,true),true));

						}
					}

					if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign')) {
						mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign', 0777, true);
						file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($file_content,true),true));

						file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign/list.json', print_r(json_encode($campaigns,true),true));
					}else{
						file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($file_content,true),true));

						if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign/list.json')){
							file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign/list.json', print_r(json_encode($campaigns,true),true));
						}else{
						
					        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/campaign/list.json', print_r(json_encode($campaigns,true),true));

						}
					}

					if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots')) {
						mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots', 0777, true);
						file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($adSlotsData,true),true));

						file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots/list.json', print_r(json_encode($adSlots,true),true));
					}else{
						file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots/'.date('Ym',strtotime($end_date)).'.json', print_r(json_encode($adSlotsData,true),true));

						if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots/list.json')){
							file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots/list.json', print_r(json_encode($adSlots,true),true));
						}else{
						
					        file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/adSlots/list.json', print_r(json_encode($adSlots,true),true));

						}
					}
				}
				

				$campaignsData = $file_content =  $deviceData = array();
			}

			ksort($impressionGraph['dates']);
			ksort($impressionGraph['impression']);
			ksort($impressionGraph['clicks']);
			ksort($impressionGraph['ctr']);
			ksort($impressionGraph['cost']);
			ksort($impressionGraph['conversions']);	

		
			if (!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs')) {
				mkdir(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs', 0777, true);
				file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs/overview.json', print_r(json_encode($impressionGraph,true),true));
			}else{
				if(!file_exists(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs/overview.json')){
					file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs/overview.json', print_r(json_encode($impressionGraph,true),true));
				}else{
				    file_put_contents(\config('app.FILE_PATH').'public/adwords/'.$project_id.'/graphs/overview.json', print_r(json_encode($impressionGraph,true),true));
				}
			}
		
		}catch (Exception $e) {
			return $e->getMessage();

		}

	}


	public function ajax_fetch_ppc_chart(Request $request){
		// dd($request->all());
		$range = $request->value;
		$campaign_id = $request->campaign_id;
		$account_id = $request->account_id;
		$today = date('Y-m-d');

		if($range == 'month'){
			$start_date = date('Y-m-d',strtotime('-1 month'));
			$duration =1;
		}elseif($range == 'three'){
			$start_date = date('Y-m-d',strtotime('-3 month'));
			$duration = 3;
		}elseif($range == 'six'){
			$start_date = date('Y-m-d',strtotime('-6 month'));
			$duration =6;
		}elseif($range == 'nine'){
			$start_date = date('Y-m-d',strtotime('-9 month'));
			$duration =9;
		}elseif($range == 'year'){
			$start_date = date('Y-m-d',strtotime('-1 year'));
			$duration =12;
		}elseif($range == 'twoyear'){
			$start_date = date('Y-m-d',strtotime('-2 year'));
			$duration =24;
		}else{
			$start_date = date('Y-m-d',strtotime('-1 month'));
			$duration =1;
		}


		$day_diff = strtotime($start_date) - strtotime($today);

		$count_days = floor($day_diff/(60*60*24));


		if($range == 'year'){
			$prev_date =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
			$prev_end_date =  date('Y-m-d',strtotime(' -1 year',strtotime($prev_date)));
		}
		elseif($range == 'twoyear'){
			$prev_date =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
			$prev_end_date =  date('Y-m-d',strtotime(' -2 year',strtotime($prev_date)));			
		}
		else{
			$prev_date =  date('Y-m-d',strtotime('-1 day',strtotime($start_date)));
			$prev_end_date =  date('Y-m-d',strtotime($count_days.' days',strtotime($prev_date)));
		}

		$dates = $this->getDatesFromRange($start_date,$today);	 
		$summary_chart = $this->get_from_file(date('Y-m-d',strtotime($start_date)),date('Y-m-d'),$campaign_id);
		
		

		if($request->compare == true){
			$compare_dates = $this->getDatesFromRange($prev_end_date,$prev_date);	
			$compare_summary_chart = $this->get_from_file(date('Y-m-d',strtotime($prev_end_date)),date('Y-m-d',strtotime($prev_date)),$campaign_id);
			//summary chart values
			$summary_chart['clicks_previous'] = $compare_summary_chart['clicks'];
			$summary_chart['conversions_previous'] = $compare_summary_chart['conversions'];
			$summary_chart['impressions_previous'] = $compare_summary_chart['impressions'];
			//performance chart values
			$summary_chart['cost_previous'] = $compare_summary_chart['cost'];
			$summary_chart['cpc_previous'] = $compare_summary_chart['cpc'];
			$summary_chart['averagecpm_previous'] = $compare_summary_chart['averagecpm'];				
			$summary_chart['revenue_per_click_previous'] = $compare_summary_chart['revenue_per_click'];
			$summary_chart['total_value_previous'] = $compare_summary_chart['total_value'];
		}

		$summary_chart['date_range'] =  $dates;
		$summary_chart['compare'] =  $request->compare;

		return response()->json($summary_chart);
	}

	public function ajax_fetch_ppc_summary_statistics(Request $request){

		if(Auth::user() <> null){
			$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
			$role_id = User::get_user_role(Auth::user()->id);
		}else{
			$getUser = SemrushUserAccount::where('id',$request->campaign_id)->first(); 
			$user_id = User::get_parent_user_id($getUser->user_id); //get user id from child
			$role_id = User::get_user_role($getUser->user_id);
		}
		$range = $request->value;
		$campaign_id = $request->campaign_id;
		$account_id = $request->account_id;
		$end_date = date('Ymd');
		$state = ($request->has('key'))?'viewkey':'user';
		$status = $request->compare == 'true' ? 1 : 0;
		
		if($range == 'seven'){
			$start_date = date('Ymd',strtotime('-6 days'));
			$duration =7;
		}elseif($range == 'fourteen'){
			$start_date = date('Ymd',strtotime('-13 days'));
			$duration =14;
		}elseif($range == 'month'){
			$start_date = date('Ymd',strtotime('-1 month'));
			$duration =1;
		}elseif($range == 'three'){
			$start_date = date('Ymd',strtotime('-3 month'));
			$duration = 3;
		}elseif($range == 'six'){
			$start_date = date('Ymd',strtotime('-6 month'));
			$duration =6;
		}elseif($range == 'nine'){
			$start_date = date('Ymd',strtotime('-9 month'));
			$duration =9;
		}elseif($range == 'year'){
			$start_date = date('Ymd',strtotime('-1 year'));
			$duration =12;
		}elseif($range == 'twoyear'){
			$start_date = date('Ymd',strtotime('-2 year'));
			$duration =24;
		}else{
			$start_date = date('Ymd',strtotime('-1 month'));
			$duration =1;
		}


		$day_diff = strtotime($start_date) - strtotime($end_date);

		$count_days = floor($day_diff/(60*60*24));


		if($range == 'year'){
			$prev_date =  date('Ymd',strtotime('-1 day',strtotime($start_date)));
			$prev_end_date =  date('Ymd',strtotime(' -1 year',strtotime($prev_date)));
		}
		elseif($range == 'twoyear'){
			$prev_date =  date('Ymd',strtotime('-1 day',strtotime($start_date)));
			$prev_end_date =  date('Ymd',strtotime(' -2 year',strtotime($prev_date)));			
		}
		else{
			$prev_date =  date('Ymd',strtotime('-1 day',strtotime($start_date)));
			$prev_end_date =  date('Ymd',strtotime($count_days.' days',strtotime($prev_date)));
		}

		if($request->has('campaign_id')){
			$campaign_data  = SemrushUserAccount::findorfail($request->campaign_id);
			$campaign_id = $request->campaign_id;
			$user_id = $campaign_data->user_id;
			if($role_id !== 4 && $state == 'user'){
				ModuleByDateRange::updateOrCreate(
					['request_id' => $request->campaign_id, 'module' => 'google_ads'],
					[
						'request_id'=>$request->campaign_id,
						'user_id'=>$user_id,
						'duration'=>$duration,
						'module'=>'google_ads',
						'start_date'=>$start_date,
						'end_date'=>$end_date,
						'compare_start_date'=>$prev_end_date,
						'compare_end_date'=>$prev_date,
						'status'=>$status
					]
				);
			}
		}

		$arrData = [
			'startDate'=> $prev_date,
			'endDate'=> $end_date,
			'preStartDate'=> $prev_date,
			'preEndDate'=> $prev_end_date,
			'compare'=> $status,
			'duration'=> $duration
		];
		
		return $arrData;
	}

	private function weekArr($startDate,$endDate){
		$lapse ='-1 week';
		$duration = 12;
		for($i=0;$i<=$duration;$i++){

			

			if($i==0){	

				$start_date = date('Ymd',strtotime('-1 day'));
				$end_date = date('Ymd',strtotime('-1 week',strtotime($start_date))); 
				/*$end_date = date('Ymd', strtotime($lapse, strtotime($start_date))); */
				$lastDate = date('Y-m-d',strtotime($end_date));
			}else{
				
				$start_date = date('Ymd',strtotime($end_date));
				$end_date = date('Ymd',strtotime('-1 week',strtotime($start_date)));

				$lastDate = date('Y-m-d',strtotime($end_date));
				/*$end_date = date('Ymd',strtotime($lapse,strtotime($start_date)));*/
			}

			if($lastDate < date('Y-m-d',strtotime($endDate))){
				break;
			}

			$res[$i]['start_date'] = $start_date;
			$res[$i]['end_date'] = $end_date;
		}	

		return $res;

	}

	private function get_data_dates($range = 23){

		$lapse ='-1 month';
		$duration = $range;

		for($i=0;$i<=$duration;$i++){
			if($i==0){	

				$start_date = date('Ymd',strtotime('-1 day'));
				$end_date = date('Ym01',strtotime($start_date)); 
				/*$end_date = date('Ymd', strtotime($lapse, strtotime($start_date))); */
				$lastDate = date('Y-m-d',strtotime($end_date));
			}else{
				
				$start_date = date('Ymt',strtotime('-1 month',strtotime($lastDate)));
				$end_date = date('Ym01',strtotime('-1 month',strtotime($lastDate)));

				$lastDate = date('Y-m-d',strtotime($end_date));
				/*$end_date = date('Ymd',strtotime($lapse,strtotime($start_date)));*/
			}
			$res[$i]['start_date'] = $start_date;
			$res[$i]['end_date'] = $end_date;
		}	
		return $res;
	}


	private function curl($url) {
        // Assigning cURL options to an array
        $options = Array(
            CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
            CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
            CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
            CURLOPT_CONNECTTIMEOUT => 120,   // Setting the amount of time (in seconds) before the request times out
            CURLOPT_TIMEOUT => 120,  // Setting the maximum amount of time for cURL to execute queries
            CURLOPT_MAXREDIRS => 10, // Setting the maximum number of redirections to follow
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
            CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
        );

        $ch = curl_init();  // Initialising cURL 
        curl_setopt_array($ch, $options);   // Setting cURL's options using the previously assigned array data in $options
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL 
        return $data;   // Returning the data from the function 
    }

    private function scrape_between($data, $start, $end){
        $data = stristr($data, $start); // Stripping all data from before $start
        $data = substr($data, strlen($start));  // Stripping $start
        $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
        $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
        return $data;   // Returning the scraped data from the function
    }

}