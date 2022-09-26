<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DfsLanguage;
use App\DfsLocation;
use App\KwSearch;
use App\KwSearchIdea;
use App\KwHistory;
use App\SemrushUserAccount;
use App\User;
use App\KwList;
use App\KwListDetail;
use DB;
use Auth;
use URL;

use GetOpt\GetOpt;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentNames;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentParser;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V8\ResourceNames;
use Google\Ads\GoogleAds\V8\Enums\KeywordPlanNetworkEnum\KeywordPlanNetwork;
use Google\Ads\GoogleAds\V8\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\V8\Services\GenerateKeywordIdeaResult;
use Google\Ads\GoogleAds\V8\Services\KeywordAndUrlSeed;
use Google\Ads\GoogleAds\V8\Services\KeywordSeed;
use Google\Ads\GoogleAds\V8\Services\UrlSeed;
use Google\ApiCore\ApiException;

use App\Exports\ExportKeywordIdeas;
use App\Exports\ExportKeywordList;
use Maatwebsite\Excel\Facades\Excel;
use App\KeywordSearch;
use App\Exports\ExportImportedKeywordIdeas;

use App\GoogleAnalyticsUsers;

class KeywordExplorerController extends Controller {

	public function __construct(){
		$ads_data = GoogleAnalyticsUsers::where('user_id',99)->where('email','adwords@imarkinfotech.com')->where('oauth_provider','google_ads')->latest()->first();
		if(isset($ads_data) && !empty($ads_data)){
			$this->refresh_token = $ads_data->google_refresh_token;
		}else{
			$this->refresh_token = '1//0dShRDhr3JbMDCgYIARAAGA0SNwF-L9IrZyWr59zi_SoT-dYw56Lh2jEqpvIxeTQg3CGpZQbSBSTrztGqkAbzLJl7VqodHeg1pzk';
		}
	}

	public function keyword_explorer($domain_name,$campaign_id= null){	
		$user_id = User::get_parent_user_id(Auth::user()->id);
		return view('vendor.keyword_explorer.index',compact('campaign_id','user_id'));
	}

	public function ajax_get_dfs_languages(Request $request){
		$selected_value = $request->language;
		$language = DfsLanguage::get();
		$html = '';
		if(!empty($language)){
			$html = '<option language_id="0">Any Language</option>';
			foreach($language as $value){
				if($value->language_id == $selected_value){
					$selected = 'selected="selected"';
				}elseif($value->language_id === 1000){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$html .= '<option id="'.$value->id.'" language_id="'.$value->language_id.'"'.$selected.'>'.$value->language.'</option>';
			}
		}
		return response()->json($html);
	}



	public function ajax_get_dfs_locations(Request $request){
		$html = '';
		if($request->category === 1){
			$filter = ['Country','City','County','Neighborhood','Province','Region','State','Union Territory'];
		}elseif($request->category === 2){
			$filter = ['Country'];
		}else{
			$filter = ['Country','City','County','Neighborhood','Province','Region','State','Union Territory'];
		}

		if($request->type == 'import'){
			$li_class = 'import-location-data';
		}else{
			$li_class = 'location-data';
		}

		if($request->location == ''){
			$html .='<li class="selected '.$li_class.'"><a href="javascript:void(0)" location_id="0"><span class="text"><i class="fa fa-globe" aria-hidden="true"></i> Anywhere</span><small>Default</small></a></li><li class="'.$li_class.'"><a href="javascript:void(0)" id="230" location_id="2840" location_type="Country" location-name="United States"><span class="text"><span class="flag"><img src="/public/flags/us.png" alt="us"></span>United States</span><small>Country</small></a></li><li class="'.$li_class.'"><a href="javascript:void(0)" id="82" location_id="2276" location_type="Country" location-name="Germany"><span class="text"><span class="flag"><img src="/public/flags/de.png" alt="de"></span>Germany</span><small>Country</small></a></li><li class="'.$li_class.'"><a href="javascript:void(0)" id="226" location_id="2826" location_type="Country" location-name="United Kingdom"><span class="text"><span class="flag"><img src="/public/flags/uk.png" alt="uk"></span>United Kingdom</span><small>Country</small></a></li><li class="'.$li_class.'"><a href="javascript:void(0)" id="203" location_id="2724" location_type="Country" location-name="Spain"><span class="text"><span class="flag"><img src="/public/flags/es.png" alt="es"></span>Spain</span><small>Country</small></a></li><li class="'.$li_class.'"><a href="javascript:void(0)" id="73" location_id="2250" location_type="Country" location-name="France"><span class="text"><span class="flag"><img src="/public/flags/fr.png" alt="fr"></span>France</span><small>Country</small></a></li>';
		}else{
			$locations = DfsLocation::
			where('location','LIKE','%'.$request->location.'%')
			->whereIn('location_type', $filter)
			// ->limit(10)
			->get();
			if(!empty($locations) && count($locations) > 0){
				foreach($locations as $value){
					$iso = strtolower($value->country_iso_code);
					$flag = URL::asset('/public/flags/'.$iso.'.png');
					$html .='<li class="'.$li_class.'"><a href="javascript:void(0)" id="'.$value->id.'" location_id="'.$value->location_code.'" location_type="'.$value->location_type.'" location-name="'.$value->location.'"><span class="text"><span class="flag"><img src="'.$flag.'"></span>'.$value->location.'</span><small>'.$value->location_type.'</small></a></li>';
				}
			}else{
				$html .='<li><a href="javascript:void(0)">No Result Found</a></li>';
			}
		}
		return response()->json($html);
	}

	public function ajax_fetch_keyword_data(Request $request){

		$postRequest = $response =  array();
		$query = $request->search_query;
		$locations = ($request->locations)?:0;
		$language = ($request->language)?:1000; //1000 for english
		$campaign_id = $request->campaign_id;
		$user_id = $request->user_id;
		$category = $request->category;		

		$ifExists = KwSearch::where('search_term', $query)->where('category', $category)->where('location_id', $locations)->where('language_id', $language)->first();
		
		$location_name = '';
		if($locations !== 0){
			$location = DfsLocation::where('location_code',$locations)->first();
			$location_name = $location->location;
		}

		if(empty($ifExists) && $ifExists == null){
			$postRequest = array(
				'client_id' => \config('app.ads_client_id'),
				'secret_id' => \config('app.ads_client_secret'),
				'developer_token' => \config('app.ads_developerToken'),
				// 'refreshToken' => '1//0dRVZYZQvtpb4CgYIARAAGA0SNwF-L9Ir9hu9AUPHyyl95RXYKOtwjUZ-ZmCRsRVj0pbyZ2SoWDyZjx90dawDZPaC9dtc2E1E7BI',
				'refreshToken' => $this->refresh_token,
				'customer_id'=>\config('app.ads_manager_id'),
				'search_query'=>$query,
				'location'=>$locations,
				'language'=>$language,
				'category'=>$category
			); 

			$cURLConnection = curl_init('https://agencydashboard.io/keyword_planner/google-ads-php/keyword.php');
			curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
			curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);

			$apiResponse = curl_exec($cURLConnection);
			curl_close($cURLConnection);
			$apiResponseList = json_decode($apiResponse,true);

			if(!empty($apiResponseList) && ($apiResponseList <> null)){
				$kw_search = KwSearch::create([
					'campaign_id' => $campaign_id,
					'user_id' => $user_id,
					'search_term' => $query,
					'category' => $category,
					'location_id'=>$locations,
					'language_id'=>$language,
					'searched_on'=>now()
				]);

				$kw_search_id = $kw_search->id;

				foreach($apiResponseList as $key=>$value){
					$kw_search_idea = KwSearchIdea::create([
						'kw_search_id'=>$kw_search_id,
						'campaign_id'=>$campaign_id,
						'user_id'=>$user_id,
						'search_term'=>$value['keyword'],
						'category'=>$category,
						'location_id'=>$locations,
						'language_id'=>$language,
						'competition'=>$value['competition'],
						'competition_index'=>$value['competition_index'],
						'sv'=>$value['average_monthly_search'],
						'page_bid_low'=>$value['page_bid_low'],
						'page_bid_high'=>$value['page_bid_high'],
						'sv_trend'=>json_encode($value['monthly_searches'],true)
					]);

					$kw_search_idea_id = $kw_search_idea->id;
					if($key == 0){
						if($category == 1){
							KwHistory::create([
								'kw_search_idea_id'=>$kw_search_idea_id,
								'kw_search_id'=>$kw_search_id,
								'campaign_id'=>$campaign_id,
								'user_id'=>$user_id,
								'search_term'=>$query,
								'category'=>$category,
								'location_id'=>$locations,
								'language_id'=>$language,
								'competition'=>$value['competition'],
								'competition_index'=>$value['competition_index']
							]);
						}else{
							$fav_icon = null;
							$faviconn = KwHistory::scrape_favicon('https://'.$query);
							$favicon = KwHistory::xml2array($faviconn);
							if($favicon <> null){
								$fav_icon = $favicon[0];
							}
							KwHistory::create([
								'kw_search_id'=>$kw_search_id,
								'campaign_id'=>$campaign_id,
								'user_id'=>$user_id,
								'search_term'=>$query,
								'category'=>$category,
								'location_id'=>$locations,
								'language_id'=>$language,
								'favicon' =>$fav_icon
							]);
						}
					}
				}

				$response['status'] = 'stored';
				$response['id'] = $kw_search_id;
				$response['location_name'] = $location_name;
			}else{
				$response['status'] = 'error';
				$response['html'] = '<tr><td colspan="7" style="text-align:center;">No record found for the search</td></tr>';
				$response['location_name'] = $location_name;
			}
		}else{
			$kw_search_id = $ifExists->id;
			$kw_search_idea = KwSearchIdea::where('search_term', $query)->where('category', 1)->where('location_id', $locations)->where('language_id', $language)->first();
			
			KwSearch::where('id',$kw_search_id)->update([
				'updated_at'=>now()
			]);


			if($category == 1){
				if(!empty($kw_search_idea)){
					KwHistory::create([
						'kw_search_idea_id'=>$kw_search_idea->id,
						'kw_search_id'=>$kw_search_id,
						'campaign_id'=>$campaign_id,
						'user_id'=>$user_id,
						'search_term'=>$query,
						'category'=>$category,
						'location_id'=>$locations,
						'language_id'=>$language,
						'competition'=>$kw_search_idea->competition,
						'competition_index'=>$kw_search_idea->competition_index
					]);
				}
			}else{
				$fav_icon = null;
				$faviconn = KwHistory::scrape_favicon('https://'.$query);
				$favicon = KwHistory::xml2array($faviconn);
				if($favicon <> null){
					$fav_icon = $favicon[0];
				}
				KwHistory::create([
					'kw_search_id'=>$kw_search_id,
					'campaign_id'=>$campaign_id,
					'user_id'=>$user_id,
					'search_term'=>$query,
					'category'=>$category,
					'location_id'=>$locations,
					'language_id'=>$language,
					'favicon' =>$fav_icon
				]);
			}
			
			$response['status'] = 'exists';
			$response['id'] = $ifExists->id;
			$response['location_name'] = $location_name;
		}
		return response()->json($response);
	}

	public function keyword_explorer_records($domain_name,$layout){
		if($layout == 'search'){
			return \View::make('vendor.keyword_explorer.search');
		}elseif($layout == 'ideas'){
			return \View::make('vendor.keyword_explorer.detail');
		}elseif($layout == 'import'){
			return \View::make('vendor.keyword_explorer.import_listing');
		}else{
			return \View::make('vendor.keyword_explorer.listing');
		}
	}

	public function get_keyword_response($domain_name,$kw_serach_id,$row){
		$rowperpage = 50;
		$offset = ($row)?:0;
		$counting = $row+$rowperpage;
		$kw_data = KwSearch::where('id',$kw_serach_id)->first();
		$location_name = '';
		$searched_date = KeywordSearch::calculate_time_span($kw_data->searched_on);
		
		if($kw_data->location_id !==0){
			$location = DfsLocation::where('location_code',$kw_data->location_id)->first();
			$location_name = $location->location;
		}

		$query = KwSearchIdea::
		with(['kwListData'=>function($q){
			$q->where('user_id',Auth::user()->id);
		}])
		->where('kw_search_id',$kw_serach_id)
		->orderBy('sv','desc')
		->orderBy('id','asc');
		$total = $query->count();

		$keyword_detail = $query->skip($offset)
		->take($rowperpage)
		->get();

		$html = ''; $response = $ids = $list_data = array();
		if(!empty($keyword_detail) && $keyword_detail <> null){
			$counter = $offset+1;
			foreach($keyword_detail as $key=>$value){
				if($value->competition == 2){
					$competition = 'low';
				}elseif($value->competition == 3){
					$competition = 'medium';
				}elseif($value->competition == 4){
					$competition = 'high';
				}else{
					$competition = 'low';
				}

				$activeClass = ($key == 0)?'active':'';
				$tooltip_active = (count($value->kwListData) > 0)?'active':'';
				if(!empty($value->kwListData) && count($value->kwListData) > 0){
					$list_data = array();
					foreach($value->kwListData as $value_list){
						$list_name = KwList::get_list_name($value_list->kw_list_id);
						$list_data[] = array(
							'list-id'=>$value_list->kw_list_id,
							'keyword-id'=>$value->id,
							'list_name'=>$list_name
						);
					}
					$tooltip_content = json_encode($list_data,true);
				}else{
					$tooltip_content ='';
				}

				$html .= '<tr class="'.$activeClass.'"><td><input name="select_keywords[]" class="uk-checkbox selectKeyword" type="checkbox" data-id="'.$value->id.'"></td><td><div class="uk-flex"><a href="javascript:void(0)" class="marked '.$tooltip_active.'" data-id="'.$value->id.'"><i class="fa fa-star"></i><span class="tooltip-c" style="display:none;">"'.$tooltip_content.'"</span></a><p class="keyword-name">'.$value->search_term.'</p><a href="javascript:void(0)" class="copy copy-keyword-text" data-clipboard-text="'.$value->search_term.'" uk-tooltip="title: Copy Keyword; pos: top-center"><i class="fa fa-clone"></i></a></div></td><td><div class="bar-canvas"><div uk-spinner class="refresh"></div><span class="sv-trend-data" style="display:none;" data-name="'.$value->search_term.'">'.$value->sv_trend.'</span><canvas id="myChart'.$counter.'" width="100" height="50"></canvas></td><td>'.number_format($value->sv).'</td><td>$'.$value->page_bid_low.'</td><td>$'.$value->page_bid_high.'</td><td><span class="count '.$competition.'">'.$value->competition_index.'</span></td></tr>'; 

				$ids[] = [
					'id'=>$value->id,
					'sv_trend'=> json_decode($value->sv_trend,true),
				];
				$counter++;
			}

			if($counting <= $total){
				$response['show_counter'] = $counting;
			}else{
				$response['show_counter'] = $total;
			}
			
			$response['status'] = 'success';
			$response['data'] = $html;
			$response['count'] = count($keyword_detail);
			$response['ids'] = $ids;
			$response['location_name'] = $location_name;
			$response['total'] = $total;
			$response['searched_date'] = '<span uk-icon="clock"></span>'.$searched_date;
			if(date('Y-m-d',strtotime($kw_data->searched_on)) <= date('Y-m-d',strtotime('-7 day'))){
				$response['tooltip_text'] = '<div class="uk-custom-tooltip"><div class="uk-custom-tooltip-inner"><b>Last time this SERP was fetched.</b> You can request updated results by clicking the button below.<br><a href="javascript:;" class="btn blue-btn refresh-keyword-data" data-keyword-id="'.$kw_serach_id.'"><span uk-icon="refresh"></span> Fetch new SERP</a></div></div>';
			}else{
				$response['tooltip_text'] = '<div class="uk-custom-tooltip"><div class="uk-custom-tooltip-inner"><b>Last time this SERP was fetched.</b> This SERP is pretty fresh, so re-fetching is disabled.</div></div>';
			}
		}else{
			$response['status'] = 'error';
			$response['data'] = $html;
			$response['count'] = 0;
			$response['total'] = 0;
			$response['show_counter'] = 0;
			$response['location_name'] = $location_name;
			$response['searched_date'] = '<span uk-icon="clock"></span>'.$searched_date;
			$response['tooltip_text'] = '<div class="uk-custom-tooltip"><div class="uk-custom-tooltip-inner"><b>Last time this SERP was fetched.</b> This SERP is pretty fresh, so re-fetching is disabled.</div></div>';
		}
		return response()->json($response);
	}

	public function get_trend_chart(Request $request){
		$searches = $labels = array();
		$data_id = $request->data_id;
		$keyword_data = KwSearchIdea::select('id','sv_trend')->where('id',$data_id)->first();
		if(!empty($keyword_data)){
			$json = json_decode($keyword_data->sv_trend,true);
			for($i=0;$i<count($json);$i++){
				if($json[$i]['month'] == 13){
					$labels[]  =  'Jan '.$json[$i]['year'];
				}else{
					$labels[]  =  date('M Y',strtotime($json[$i]['year'].'-'.$json[$i]['month']));
				}
				$searches[]  = $json[$i]['monthly_search'];
			}
		}
		return array(
			'searches' => json_encode($searches),
			'labels' => json_encode($labels)
		);
	}

	public function ajax_export_keyword_ideas(Request $request){
		if(!empty($request['checked'])){
			$ids = explode(',',$request['checked']);
		}else{
			$ids = array();
		}
		if(!empty($ids)){
			if($request->type === 'list'){
				$kw_search_id = $request['list_id'];
				$data = KwList::select('id','name')->where('id',$kw_search_id)->first();
				$term_name = $data->name;
			}else{
				$kw_search_id = $request['kw_search_id'];
				$data = KwSearch::select('id','search_term')->where('id',$kw_search_id)->first();
				$term_name = $data->search_term;
			}
			
			if($data){
				$str = parse_url($term_name);
				if(isset($str['host'])){
					$domain_name = preg_replace('/^www\./', '', $str['host']);
					$string = rtrim($domain_name,'/');
				}else{
					$string = $str['path'];
				}
				$name = preg_replace('/\s+/', '_',$string).'_export.xlsx';
			}else{
				$name = 'keyword_ideas_export.xlsx';
			}

			ob_end_clean(); 
			ob_start(); 
			return Excel::download(new ExportKeywordIdeas($ids,$kw_search_id,$request->type),$name, \Maatwebsite\Excel\Excel::XLSX, [
				'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
			]);
		}
		
	}

	public function ajax_get_keyword_ideas_data(Request $request){
		$location  = ($request->hidden_location)?:0;
		$language  = ($request->hidden_language)?:0;
		$keyword_detail = KwSearchIdea::with('kwListData')->where('kw_search_id',$request->keyword_search_id)->orderBy($request->column_name,$request->reverse_order)->get();
		
		$html = ''; $response = $ids = $list_data = array();		
		if(!empty($keyword_detail) && $keyword_detail <> null){
			foreach($keyword_detail as $key=>$value){
				$counter = $key+1;
				if($value->competition == 2){
					$competition = 'low';
				}elseif($value->competition == 3){
					$competition = 'medium';
				}elseif($value->competition == 4){
					$competition = 'high';
				}else{
					$competition = 'low';
				}

				$activeClass = ($key == 0)?'active':'';
				$tooltip_active = (count($value->kwListData) > 0)?'active':'';
				if(!empty($value->kwListData) && count($value->kwListData) > 0){
					$list_data = array();
					foreach($value->kwListData as $value_list){
						$list_name = KwList::get_list_name($value_list->kw_list_id);
						$list_data[] = array(
							'list-id'=>$value_list->kw_list_id,
							'keyword-id'=>$value->id,
							'list_name'=>$list_name
						);
					}
					$tooltip_content = json_encode($list_data,true);
				}else{
					$tooltip_content ='';
				}

				$html .= '<tr class="'.$activeClass.'"><td><input name="select_keywords[]" class="uk-checkbox selectKeyword" type="checkbox" data-id="'.$value->id.'"></td><td><div class="uk-flex"><a href="javascript:void(0)" class="marked '.$tooltip_active.'" data-id="'.$value->id.'"><i class="fa fa-star"></i><span class="tooltip-c" style="display:none;">"'.$tooltip_content.'"</span></a><p class="keyword-name">'.$value->search_term.'</p><a href="javascript:void(0)" class="copy copy-keyword-text" data-clipboard-text="'.$value->search_term.'" uk-tooltip="title: Copy Keyword; pos: top-center"><i class="fa fa-clone"></i></a></div></td><td><div class="bar-canvas"><div uk-spinner class="refresh"></div><span class="sv-trend-data" style="display:none;" data-name="'.$value->search_term.'">'.$value->sv_trend.'</span><canvas id="myChart'.$counter.'" width="100" height="50"></canvas></td><td>'.number_format($value->sv).'</td><td>$'.$value->page_bid_low.'</td><td>$'.$value->page_bid_high.'</td><td><span class="count '.$competition.'">'.$value->competition_index.'</span></td></tr>'; 


				$ids[] = [
					'id'=>$value->id,
					'sv_trend'=> json_decode($value->sv_trend,true),
				];
			}
			
			$response['status'] = 'success';
			$response['data'] = $html;
			$response['count'] = count($keyword_detail);
			$response['ids'] = $ids;
			$response['id'] = $request->keyword_search_id;
		}else{
			$response['status'] = 'error';
			$response['data'] = $html;
			$response['count'] = 0;
		}

		return response()->json($response);
	}

	public function ajax_fetch_user_history(Request $request){
		$data = KwHistory::where('user_id',$request->user_id)->limit(100)->latest()->get();
		$html = ''; $response = array();

		if(isset($data) && ($data <> null) && (count($data) > 0)){
			foreach($data as $key=>$value){
				if($value->competition == 2){
					$competition = 'low';
				}elseif($value->competition == 3){
					$competition = 'medium';
				}elseif($value->competition == 4){
					$competition = 'high';
				}else{
					$competition = 'low';
				}

				if($value->location_id === 0){
					$location = 'Anywhere';
				}else{
					$location = DfsLocation::get_location_name($value->location_id);
				}
				if($value->language_id === 0){
					$lanuage = 'Any Language';
				}else{
					$lanuage = DfsLanguage::get_language($value->language_id);
				}
				$preselected = ($key == 0)?'active':'';
				if($value->category ==  1){
					$html .= '<div class="single select-history '.$preselected.'"><input class="keyword-data" type="hidden" data-keyword-id="'.$value->id.'" data-keyword="'.$value->search_term.'" data-location-id="'.$value->location_id.'" data-language-id="'.$value->language_id.'" data-category="1"><a href="javascript:void(0)"><div class="count '.$competition.'">'.$value->competition_index.'</div><div class="details"><h6>'.$value->search_term.'</h6><ul><li><i class="fa fa-map-marker"></i>'. $location.'</li><li><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#kwLanguage"></use></svg>'.$lanuage.'</li></ul></div></a></div>';
				}
				if($value->category ==  2){
					if (filter_var($value->favicon, FILTER_VALIDATE_URL)) {
						$favicon = $value->favicon;
					}else{
						$favicon = '//'.$value->search_term.$value->favicon;
					}
					$html .='<div class="single select-history '.$preselected.'"><input class="keyword-data" type="hidden" data-keyword-id="'.$value->id.'" data-keyword="'.$value->search_term.'" data-location-id="'.$value->location_id.'" data-language-id="0" data-category="2"><a href="javascript:void(0)"><div class="url-favicon"><img src="'.$favicon.'" alt="favicon"></div><div class="details"><h6>'.$value->search_term.'</h6><ul><li><i class="fa fa-map-marker"></i>'. $location.'</li></ul></div></a></div>';
				}
			}
			$response['count'] = count($data);
			$response['html'] = $html;
		}else{
			$response['count'] = 0;
			$response['html'] = '<div class="empty-section"><div class="inner"><img src="/public/vendor/internal-pages/images/empty.png"><h5>Your history is empty</h5><p>Start your keyword research now, your recent searched will be listed here.</p><button class="uk-offcanvas-close" type="button" uk-close>Close this panel</button></div></div>';
		}
		return response()->json($response);
	}

	public function ajax_clear_search_history(Request $request){
		$data = KwHistory::where('user_id',$request->user_id)->delete();
		$response['html'] = '<div class="empty-section"><div class="inner"><img src="/public/vendor/internal-pages/images/empty.png"><h5>Your history is empty</h5><p>Start your keyword research now, your recent searched will be listed here.</p><button class="uk-offcanvas-close" type="button" uk-close>Close this panel</button></div></div>';
		return response()->json($response);
	}

	public function ajax_create_keyword_list(Request $request){
		$response = array();
		$selected_keywords = $request->checked;
		if($request->flag == 'new'){
			$kw_list = KwList::updateOrCreate(
				['user_id'=>$request->user_id,'name'=>$request->name],
				['user_id'=>$request->user_id,'campaign_id'=>$request->campaign_id,'name'=>$request->name]
			);

			foreach($selected_keywords as $key=>$value){
				KwListDetail::updateOrCreate(
					['user_id'=>$request->user_id,'kw_list_id' =>$kw_list->id,'kw_search_idea_id' =>$value],
					['user_id'=>$request->user_id,'kw_list_id' =>$kw_list->id,'kw_search_idea_id' =>$value]
				);
			}
			$response['text'] = 'List '.$request->name.' was succesfully created.';
		}elseif($request->flag == 'existing'){
			foreach($selected_keywords as $key=>$value){
				KwListDetail::updateOrCreate(
					['user_id'=>$request->user_id,'kw_list_id' =>$request->list_id,'kw_search_idea_id' =>$value],
					['user_id'=>$request->user_id,'kw_list_id' =>$request->list_id,'kw_search_idea_id' =>$value]
				);
			}

			if(count($selected_keywords) == 1){
				$response['text'] = count($selected_keywords).' keyword was succesfully added to '.$request->name.'.';
			}else{
				$response['text'] = count($selected_keywords).' keywords were succesfully added to '.$request->name.'.';
			}
		}
		return response()->json($response);
	}

	public function ajax_fetch_keyword_list(Request $request){
		$data = KwList::withCount('kw_list_detail')->where('user_id',$request->user_id)->get();
		$html = '';
		if(!empty($data) && isset($data)){
			foreach($data as $key=>$value){
				$activeClass = ($key==0)?'active':'';
				$html .= '<li class="'.$activeClass.' select-keyword-list" data-selected-id="'.$value->id.'" data-name="'.$value->name.'"><a href="javascript:void(0)"><h6>'.$value->name.'</h6><p><strong>'.$value->kw_list_detail_count.'</strong></p></a></li>';
			}
		}else{
			$html .= '';
		}
		return response()->json($html);
	}

	public function ajax_remove_keyword_from_list(Request $request){
		$response = array();
		$remove = KwListDetail::where('kw_list_id',$request->list_id)->where('kw_search_idea_id',$request->keyword_id)->delete();
		if(!$remove){
			$response['status'] = 0;
			$response['message'] = 'Error removing keyword from the list.';
		}else{
			$response['status'] = 1;
			$response['message'] = 'Keyword was succesfully removed from the list.';
		}
		return response()->json($response);
	}

	public function ajax_fetch_lists(Request $request){
		$html = ''; $response = array();
		$lists_data = KwList::withCount('kw_list_detail')->where('user_id',$request->user_id)->latest();
		if($request->search_keyword !== ''){
			$lists_data->where('name','LIKE',  '%' . $request->search_keyword .'%');
		}
		$lists = $lists_data->get();

		if(!empty($lists) && count($lists) > 0){
			foreach($lists as $key=>$value){
				$html .='<div class="single"><a href="javascript:void(0)" class="select-list"><input type="text" readonly data-list-id="'.$value->id.'" value="'.$value->name.'" class="list-name readonly"><ul><li title="Number of keywords in list"><strong>'.$value->kw_list_detail_count.'</strong> <small>/ 10,000</small></li><li title="Created: '.date('d M y',strtotime($value->created_at)).'">'.date('d M y',strtotime($value->created_at)).'</li></ul></a><div class="action-btns"><a href="javascript:void(0)" uk-tooltip="title: Rename list; pos: top" class="rename-the-list" data-id="'.$value->id.'"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#kwPencil"></use></svg></a><a href="javascript:void(0)" uk-tooltip="title: Export list; pos: top" class="export-list" data-id="'.$value->id.'" data-name="'.$value->name.'"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#kwDownload"></use></svg></a><a href="javascript:void(0)" uk-tooltip="title: Delete list; pos: top" class="delete-list" data-id="'.$value->id.'" data-name="'.$value->name.'"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#kwDelete"></use></svg></a></div></div>';
			}
			$response['count'] = count($lists);
			$response['html'] = $html;
		}else{
			if($request->type == 'search'){
				$response['count'] = 1;
				$response['html'] = '<div class="empty-section"><div class="inner"><img src="/public/vendor/internal-pages/images/empty.png"><h5>Sorry, no list found for given filter.</h5></div></div>';
			}else{
				$response['count'] = 0;
				$response['html'] = '<div class="empty-section"><div class="inner"><img src="/public/vendor/internal-pages/images/empty.png"><h5>Your List is empty</h5><p>Add keywords to the list.</p><button class="uk-offcanvas-close" type="button" uk-close>Close this panel</button></div></div>';
			}
			
		}
		return response()->json($response);
	}

	public function ajax_update_list_name(Request $request){
		if($request->text !== ''){
			$update = KwList::where('id',$request->list_id)->update([
				'name'=>$request->text
			]);

			if(!$update){
				$response['status'] = 0;
				$response['message'] = 'Error updating list name.';
			}else{
				$response['status'] = 1;
				$response['message'] = 'List name updated succesfully.';
			}
		}else{
			$response['status'] = 0;
			$response['message'] = 'List name cannot be empty.';
		}

		return response()->json($response);
	}

	public function ajax_export_keyword_list(Request $request){
		$list = KwListDetail::where('kw_list_id',$request->list_id)->pluck('kw_search_idea_id');
		$name = preg_replace('/\s+/', '_',$request->list_name).'_export.xlsx';
		ob_end_clean(); 
		ob_start(); 
		return Excel::download(new ExportKeywordList($list),$name, \Maatwebsite\Excel\Excel::XLSX, [
			'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		]);
	}

	public function ajax_delete_list(Request $request){
		$data = KwList::findOrFail($request->list_id);
		$data->kw_list_detail()->delete();
		$data->delete();

		$response['message'] = $request->list_name.' list was succesfully deleted.';
		return response()->json($response);
	}

	public function ajax_get_listing(Request $request){
		$ids = KwListDetail::where('kw_list_id',$request->list_id)->pluck('kw_search_idea_id');

		$keyword_detail = KwSearchIdea::
		with('kwListData')
		->whereIn('id',$ids)
		->orderBy('sv','desc')
		->get();
		
		$html = ''; $response = $ids = $list_data = array();		
		$sv_sum = $avg_top_bid = $avg_ci = 0;
		if(!empty($keyword_detail) && $keyword_detail <> null && count($keyword_detail) > 0){
			foreach($keyword_detail as $key=>$value){
				$counter = $key+1;
				if($value->competition == 2){
					$competition = 'low';
				}elseif($value->competition == 3){
					$competition = 'medium';
				}elseif($value->competition == 4){
					$competition = 'high';
				}else{
					$competition = 'low';
				}

				$activeClass = ($key == 0)?'active':'';
				$tooltip_active = (count($value->kwListData) > 0)?'active':'';
				if(!empty($value->kwListData) && count($value->kwListData) > 0){
					$list_data = array();
					foreach($value->kwListData as $value_list){
						$list_name = KwList::get_list_name($value_list->kw_list_id);
						$list_data[] = array(
							'list-id'=>$value_list->kw_list_id,
							'keyword-id'=>$value->id,
							'list_name'=>$list_name
						);
					}
					$tooltip_content = json_encode($list_data,true);
				}else{
					$tooltip_content ='';
				}

				$html .= '<tr class="'.$activeClass.'"><td><input name="select_keywords[]" class="uk-checkbox selectKeyword" type="checkbox" data-id="'.$value->id.'"></td><td><div class="uk-flex"><a href="javascript:void(0)" class="marked '.$tooltip_active.'" data-id="'.$value->id.'"><i class="fa fa-star"></i><span class="tooltip-c" style="display:none;">"'.$tooltip_content.'"</span></a><p class="keyword-name">'.$value->search_term.'</p><a href="javascript:void(0)" class="copy copy-keyword-text" data-clipboard-text="'.$value->search_term.'" uk-tooltip="title: Copy Keyword; pos: top-center"><i class="fa fa-clone"></i></a></div></td><td><div class="bar-canvas"><div uk-spinner class="refresh"></div><span class="sv-trend-data" style="display:none;" data-name="'.$value->search_term.'">'.$value->sv_trend.'</span><canvas id="myChart'.$counter.'" width="100" height="50"></canvas></td><td>'.number_format($value->sv).'</td><td>$'.$value->page_bid_low.'</td><td>$'.$value->page_bid_high.'</td><td><span class="count '.$competition.'">'.$value->competition_index.'</span></td></tr>'; 

				$ids[] = [
					'id'=>$value->id,
					'sv_trend'=> json_decode($value->sv_trend,true),
				];
				$sv_sum +=  $value->sv;
				$avg_top_bid +=  $value->page_bid_high;
				$avg_ci +=  $value->competition_index;
			}
			$response['status'] = 'success';
			$response['data'] = $html;
			$response['count'] = count($keyword_detail);
			$response['ids'] = $ids;
			$response['search_sum'] = $sv_sum;
			$response['avg_top_bid'] = '$'.number_format($avg_top_bid/count($keyword_detail));
			$response['avg_ci'] = round($avg_ci/count($keyword_detail));
		}else{
			$response['status'] = 'error';
			$response['data'] = '<tr><center><td colspan="7">No keyword added to the list</td></center></tr>';
			$response['count'] = 0;
			$response['search_sum'] = 0;
			$response['avg_top_bid'] = '$0';
			$response['avg_ci'] = 0;
		}
		return response()->json($response);
	}

	public function ajax_get_refreshed_data(Request $request){
		$kw_search_id = $request->kw_search_id;
		$ifExists = KwSearch::where('id', $kw_search_id)->first();
		$location_name = '';
		if($ifExists->location_id !== 0){
			$location = DfsLocation::where('location_code',$ifExists->location_id)->first();
			$location_name = $location->location;
		}
		
		$postRequest = array(
			'client_id' => \config('app.ads_client_id'),
			'secret_id' => \config('app.ads_client_secret'),
			'developer_token' => \config('app.ads_developerToken'),
			'refreshToken' => $this->refresh_token,
			'customer_id'=>\config('app.ads_manager_id'),
			'search_query'=>$ifExists->search_term,
			'location'=>$ifExists->location_id,
			'language'=>$ifExists->language_id,
			'category'=>$ifExists->category
		);

		$cURLConnection = curl_init('https://agencydashboard.io/keyword_planner/google-ads-php/keyword.php');
		curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);

		$apiResponse = curl_exec($cURLConnection);
		curl_close($cURLConnection);
		$apiResponseList = json_decode($apiResponse,true);
		if(!empty($apiResponseList) && ($apiResponseList <> null)){
			
			KwSearchIdea::delete_associated($kw_search_id);

			KwSearch::where('id',$kw_search_id)->update([
				'searched_on'=>now()
			]);

			$user_id = $ifExists->user_id;
			$locations = $ifExists->location_id;
			$language = $ifExists->language_id;
			$category = $ifExists->category;
			$query = $ifExists->search_term;

			foreach($apiResponseList as $key=>$value){
				$kw_search_idea = KwSearchIdea::create([
					'kw_search_id'=>$kw_search_id,
					'user_id'=>$user_id,
					'search_term'=>$value['keyword'],
					'category'=>$category,
					'location_id'=>$locations,
					'language_id'=>$language,
					'competition'=>$value['competition'],
					'competition_index'=>$value['competition_index'],
					'sv'=>$value['average_monthly_search'],
					'page_bid_low'=>$value['page_bid_low'],
					'page_bid_high'=>$value['page_bid_high'],
					'sv_trend'=>json_encode($value['monthly_searches'],true)
				]);

				$kw_search_idea_id = $kw_search_idea->id;
				if($key == 0){
					if($category == 1){
						KwHistory::create([
							'kw_search_idea_id'=>$kw_search_idea_id,
							'kw_search_id'=>$kw_search_id,
							'user_id'=>$user_id,
							'search_term'=>$query,
							'category'=>$category,
							'location_id'=>$locations,
							'language_id'=>$language,
							'competition'=>$value['competition'],
							'competition_index'=>$value['competition_index']
						]);
					}else{
						$fav_icon = null;
						$faviconn = KwHistory::scrape_favicon('https://'.$query);
						$favicon = KwHistory::xml2array($faviconn);
						if($favicon <> null){
							$fav_icon = $favicon[0];
						}
						KwHistory::create([
							'kw_search_id'=>$kw_search_id,
							'user_id'=>$user_id,
							'search_term'=>$query,
							'category'=>$category,
							'location_id'=>$locations,
							'language_id'=>$language,
							'favicon' =>$fav_icon
						]);
					}
				}
			}

			$response['status'] = 'stored';
			$response['id'] = $kw_search_id;
			$response['location_name'] = $location_name;
			$response['language'] = $ifExists->language_id;
			$response['refresh_status'] = '<strong>Last time this SERP was fetched.</strong></br> This SERP is<br>pretty fresh, so re-fetching is disabled.';
		}else{
			$response['status'] = 'error';
			$response['html'] = '<tr><td colspan="7" style="text-align:center;">No record found for the search</td></tr>';
			$response['location_name'] = $location_name;
			$response['language'] = 0;
			$response['refresh_status'] = '<strong>Last time this SERP was fetched.</strong></br> This SERP is<br>pretty fresh, so re-fetching is disabled.';
		}
		return response()->json($response);
	}

	public function cron_remove_previous_data(){
		$data = KwSearch::
		whereDate('updated_at', '<=', date('Y-m-d',strtotime('-30 day')))
		->pluck('id');

		if(isset($data) && count($data) > 0){
			KwSearchIdea::with('kwListData')->whereIn('kw_search_id',$data)->delete();
			KwHistory::whereIn('kw_search_id',$data)->delete();
		}
	}

	/*import data*/
	public function ajax_fetch_keyword_data_multiple(Request $request){
		$postRequest = $response =  array();
		$query = $request->search_query;
		$locations = ($request->locations)?:0;
		$language = ($request->language)?:1000;
		$campaign_id = $request->campaign_id;
		$user_id = $request->user_id;
		$category = $request->category;		

		$exploded_query = explode(',',$query);
		$query_data = preg_replace("/[^A-Za-z0-9\-\']/", ' ', $exploded_query);
		$query_datas = str_replace(array('\'', '"', ',' , ';', '<', '>','-')," ",$query_data);
		$search_query = array_unique($query_datas);

		$location_name = '';
		if($locations !== 0){
			$location = DfsLocation::where('location_code',$locations)->first();
			$location_name = $location->location;
		}

		

		$postRequest = array(
			'client_id' => \config('app.ads_client_id'),
			'secret_id' => \config('app.ads_client_secret'),
			'developer_token' => \config('app.ads_developerToken'),
			'refreshToken' => $this->refresh_token,
			'customer_id'=>\config('app.ads_manager_id'),
			'search_query'=>$search_query,
			'location'=>$locations,
			'language'=>$language,
			'category'=>$category
		);


		$cURLConnection = curl_init('https://agencydashboard.io/keyword_planner/google-ads-php/keyword-multiple.php');
		curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, http_build_query($postRequest));
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);

		$apiResponse = curl_exec($cURLConnection);
		curl_close($cURLConnection);
		$apiResponseList = json_decode($apiResponse,true);

		file_put_contents(dirname(__FILE__).'/logs/apiResponseList.json',print_r(json_encode($apiResponseList,true),true));

		$filtered_data = array();
		if(!empty($apiResponseList) && ($apiResponseList <> null)){
			foreach($search_query as $k=>$v){
				$filtered_data[$k] = self::search_in_array($v,$apiResponseList);
			}
			file_put_contents(dirname(__FILE__).'/logs/filtered_data.json',print_r(json_encode($filtered_data,true),true));

			if(!empty($filtered_data)){
				foreach($filtered_data as $key=>$value){

					if(!empty($value)){
						$ifExists = KwSearchIdea::where('search_term', $value['keyword'])->where('kw_search_id', 0)->where('category', $category)->where('location_id', $locations)->first();

						if(!empty($ifExists)){
							KwSearchIdea::where('id',$ifExists->id)->update([
								'kw_search_id'=>0,
								'user_id'=>$user_id,
								'search_term'=>$value['keyword'],
								'category'=>$category,
								'location_id'=>$locations,
								'language_id'=>$language,
								'competition'=>$value['competition'],
								'competition_index'=>$value['competition_index'],
								'sv'=>$value['average_monthly_search'],
								'page_bid_low'=>$value['page_bid_low'],
								'page_bid_high'=>$value['page_bid_high'],
								'sv_trend'=>json_encode($value['monthly_searches'],true)
							]);
							$kw_search_idea_id[] = $ifExists->id;
						}else{
							$kw_search_idea = KwSearchIdea::create([
								'kw_search_id'=>0,
								'user_id'=>$user_id,
								'search_term'=>$value['keyword'],
								'category'=>$category,
								'location_id'=>$locations,
								'language_id'=>$language,
								'competition'=>$value['competition'],
								'competition_index'=>$value['competition_index'],
								'sv'=>$value['average_monthly_search'],
								'page_bid_low'=>$value['page_bid_low'],
								'page_bid_high'=>$value['page_bid_high'],
								'sv_trend'=>json_encode($value['monthly_searches'],true)
							]);
							$kw_search_idea_id[] = $kw_search_idea->id;
						}
					}
				}
			}
			$response['status'] = 'stored';
			$response['ids'] = $kw_search_idea_id;
			$response['location_name'] = $location_name;
			
		}else{
			$response['status'] = 'error';
			$response['html'] = '<tr><td colspan="7" style="text-align:center;">No record found for the search</td></tr>';
			$response['location_name'] = $location_name;
		}

		return response()->json($response);
	}

	public static function search_in_array($keyword,$apiResponseList){
		$new_array = array();
		$keys = array_keys(array_column($apiResponseList, 'keyword'), $keyword);
		$new_array = array_map(function($k) use ($apiResponseList){return $apiResponseList[$k];}, $keys);
		if(!empty($new_array)){			
			return $new_array[0];
		}else{
			return $new_array;
		}
	}

	public function get_keyword_response_multiple($domain_name,$ids){
		$final_ids = explode(',',$ids);
		$kw_data = KwSearchIdea::with('kwListData')->whereIn('id',$final_ids)->orderBy('sv','desc')->get();
		$html = ''; $response = $ids = $list_data = array();
		$sv_sum = $avg_top_bid = $avg_ci = 0;
		if(!empty($kw_data) && $kw_data <> null){
			$counter = 1;
			foreach($kw_data as $key=>$value){
				if($value->competition == 2){
					$competition = 'low';
				}elseif($value->competition == 3){
					$competition = 'medium';
				}elseif($value->competition == 4){
					$competition = 'high';
				}else{
					$competition = 'low';
				}

				$activeClass = ($key == 0)?'active':'';
				$tooltip_active = (count($value->kwListData) > 0)?'active':'';
				if(!empty($value->kwListData) && count($value->kwListData) > 0){
					$list_data = array();
					foreach($value->kwListData as $value_list){
						$list_name = KwList::get_list_name($value_list->kw_list_id);
						$list_data[] = array(
							'list-id'=>$value_list->kw_list_id,
							'keyword-id'=>$value->id,
							'list_name'=>$list_name
						);
					}
					$tooltip_content = json_encode($list_data,true);
				}else{
					$tooltip_content ='';
				}

				$html .= '<tr class="'.$activeClass.'"><td><input name="select_keywords[]" class="uk-checkbox selectKeyword" type="checkbox" data-id="'.$value->id.'"></td><td><div class="uk-flex"><a href="javascript:void(0)" class="marked '.$tooltip_active.'" data-id="'.$value->id.'"><i class="fa fa-star"></i><span class="tooltip-c" style="display:none;">"'.$tooltip_content.'"</span></a><p class="keyword-name">'.$value->search_term.'</p><a href="javascript:void(0)" class="copy copy-keyword-text" data-clipboard-text="'.$value->search_term.'" uk-tooltip="title: Copy Keyword; pos: top-center"><i class="fa fa-clone"></i></a></div></td><td><div class="bar-canvas"><div uk-spinner class="refresh"></div><span class="sv-trend-data" style="display:none;" data-name="'.$value->search_term.'">'.$value->sv_trend.'</span><canvas id="myChart'.$counter.'" width="100" height="50"></canvas></td><td>'.number_format($value->sv).'</td><td>$'.$value->page_bid_low.'</td><td>$'.$value->page_bid_high.'</td><td><span class="count '.$competition.'">'.$value->competition_index.'</span></td></tr>'; 

				$ids[] = [
					'id'=>$value->id,
					'sv_trend'=> json_decode($value->sv_trend,true),
				];

				$sv_sum +=  $value->sv;
				$avg_top_bid +=  $value->page_bid_high;
				$avg_ci +=  $value->competition_index;

				$counter++;
			}

			$response['status'] = 'success';
			$response['data'] = $html;
			$response['count'] = count($kw_data);
			$response['ids'] = $ids;
			$response['search_sum'] = $sv_sum;
			$response['avg_top_bid'] = '$'.number_format($avg_top_bid/count($kw_data));
			$response['avg_ci'] = round($avg_ci/count($kw_data));
		}else{
			$response['status'] = 'error';
			$response['data'] = $html;
			$response['count'] = 0;
			$response['show_counter'] = 0;
			$response['search_sum'] = 0;
			$response['avg_top_bid'] = '$0';
			$response['avg_ci'] = 0;
		}
		return response()->json($response);
	}


	public function ajax_export_imported_keyword_ideas(Request $request){
		if(!empty($request['checked'])){
			$ids = explode(',',$request['checked']);
		}else{
			$ids = array();
		}

		$name = 'keyword_ideas_export.xlsx';

		ob_end_clean(); 
		ob_start(); 
		return Excel::download(new ExportImportedKeywordIdeas($ids),$name, \Maatwebsite\Excel\Excel::XLSX, [
			'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		]);
	}
}