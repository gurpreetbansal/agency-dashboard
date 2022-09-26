<?php

namespace App\Traits;

use Session;
use App\GoogleAnalyticsUsers;
use App\SemrushUserAccount;
use App\User; 
use App\GmbLocation; 
use Exception;

require env("FILE_PATH").'api/MyBusiness.php';

trait GMBAuth {

	public function getAccounts($client){
		
		$optParams = null;
		$mybusinessService = new \Google_Service_MyBusinessAccountManagement($client);
		$data =  $mybusinessService->accounts->listAccounts()->getAccounts();
		return $data;
	}

	public function getAccountDetail($client,$accountName){
		
		$optParams = array(
				'pageSize' => 100,
		       'readMask' => array(
		           'name',
		           'title',
		           'profile',
		           'websiteUri',
		           'storeCode',
		           'languageCode',
		           'labels',
		       ),
		);

		$mybusinessService = new \Google_Service_MyBusinessBusinessInformation($client);
		$locations = $mybusinessService->accounts_locations;
		$locationsList = $locations->listAccountsLocations($accountName,$optParams)->getLocations();
		return $locationsList;
	}

	public function get_location_details($location_name){
		$final = array();
		$address = urlencode($location_name);
		$google_api_key = \config('app.GOOGLE_API_KEY');
		$googleMapUrl = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$google_api_key}";
		$geocodeResponseData = file_get_contents($googleMapUrl);
		$responseData = json_decode($geocodeResponseData, true);
		if($responseData['status']=='OK') {
			$final = array(
				'lat' => $responseData['results'][0]['geometry']['location']['lat'],
				'lng' => $responseData['results'][0]['geometry']['location']['lng']
			);
		}else{
			$final = array(
				'lat' => '',
				'lng' => ''
			);
		}
		return $final;
	}


	public function addLocation($client,$getUserDetails = null,$sessionData = null, $googleuser = null){

		$insert = GoogleAnalyticsUsers::create([
			'user_id'=>$getUserDetails->user_id,
			'google_access_token'=> $sessionData['token']['access_token'],
			'google_refresh_token'=>$sessionData['token']['refresh_token'],
			'oauth_provider'=>'gmb',
			'oauth_uid'=>$googleuser->id,
			'first_name'=>$googleuser->given_name,
			'last_name'=>$googleuser->family_name,
			'email'=>$googleuser->email,
			'gender'=>$googleuser->gender??'',
			'locale'=>$googleuser->locale??'',
			'picture'=>$googleuser->picture??'',
			'link'=>$googleuser->link??'',
			'token_type'=>$sessionData['token']['token_type'],
			'expires_in'=>$sessionData['token']['expires_in'],
			'id_token'=>$sessionData['token']['id_token'],
			'service_created'=>$sessionData['token']['created']
		]);
		if($insert){

			$getLastInsertedId = $insert->id;
			
			$data = $this->getAccounts($client);

			foreach ($data as $accKey => $account) {

				$accountName =  $account->name;
				$locationsList = $this->getAccountDetail($client,$accountName);
				
			   	// Final Goal of my Code
				if (empty($locationsList)===false) {
					foreach ($locationsList as $locKey => $location) {
						
						// $lat_long = $this->get_location_details($location->locationName);

						$requestData = [
							'user_id'=>$getUserDetails->user_id,
							'google_account_id'=>$getLastInsertedId,
							'account_id'=>$accountName,
							'location_id'=>$location->name,
							'labels'=>null,
							'language_code'=>$location->languageCode,
							'location_name'=>$location->title,
							'primary_phone'=>$location->primaryPhone,
							'store_code'=>$location->storeCode,
							'website_url'=>$location->websiteUri,
							// 'location_lat'=>$lat_long['lat'],
							// 'location_lng'=>$lat_long['lng']
						];
						GmbLocation::create($requestData);
					}

				}
			}
		}
	}

	public function updateLocation($client,$getUserDetails = null,$sessionData = null, $googleuser = null, $refresh_token = null,$checkIfExists){

		$update = GoogleAnalyticsUsers::where('user_id',$getUserDetails->user_id)->where('oauth_uid',$googleuser->id)
		->where('id',$checkIfExists->id)
		->update([
			'google_access_token'=> $sessionData['token']['access_token'],
			'google_refresh_token'=> $refresh_token,
			'oauth_provider'=>'gmb',
			'oauth_uid'=>$googleuser->id,
			'first_name'=>$googleuser->given_name,
			'last_name'=>$googleuser->family_name,
			'email'=>$googleuser->email,
			'gender'=>$googleuser->gender??'',
			'locale'=>$googleuser->locale??'',
			'picture'=>$googleuser->picture??'',
			'link'=>$googleuser->link??'',
			'token_type'=>$sessionData['token']['token_type'],
			'expires_in'=>$sessionData['token']['expires_in'],
			'id_token'=>$sessionData['token']['id_token'],
			'service_created'=>$sessionData['token']['created'],
			'updated_at'=> now()
		]);

		// if($update){

			$getLastInsertedId = $checkIfExists->id;
			
			$data = $this->getAccounts($client);

			GmbLocation::where('user_id',$getUserDetails->user_id)->where('google_account_id', $getLastInsertedId)->update(['status'=> 0]);
			foreach ($data as $accKey => $account) {
				
				$accountName =  $account->name;

				$locationsList = $this->getAccountDetail($client,$accountName);

				// echo "<pre/>"; print_r($locationsList); die;
				// Final Goal of my Code
				if (empty($locationsList)===false) {
					foreach ($locationsList as $locKey => $location) {
						$if_data_exists = GmbLocation::where('website_url',$location->websiteUri)->where('user_id',$getUserDetails->user_id)->where('google_account_id', $getLastInsertedId)->first();
						// $lat_long = $this->get_location_details($location->locationName);

						$requestData = [
							'user_id'=>$getUserDetails->user_id,
							'google_account_id'=>$getLastInsertedId,
							'account_id'=>$accountName,
							'location_id'=>$location->name,
							'labels'=>null,
							'language_code'=>$location->languageCode,
							'location_name'=>$location->title,
							'primary_phone'=>$location->primaryPhone,
							'store_code'=>$location->storeCode,
							'website_url'=>$location->websiteUri,
							'status'=> 1
							// 'location_lat'=>$lat_long['lat'],
							// 'location_lng'=>$lat_long['lng']
						];

						if(empty($if_data_exists)){
							GmbLocation::create($requestData);
						}else{
							GmbLocation::where('id',$if_data_exists->id)->update($requestData);
						}
					}

				}
			}
		// }
	}


	public function updateLocation_refresh($client,$getUserDetails = null, $refresh_token = null,$checkIfExists){
		$result = array();
		try{
			$getLastInsertedId = $checkIfExists->id;
			
			$data = $this->getAccounts($client);

			$status = GmbLocation::where('google_account_id', $getLastInsertedId)->update(['status'=> 0]);
			foreach ($data as $accKey => $account) {

				$accountName =  $account->name;
				$locationsList = $this->getAccountDetail($client,$accountName);

				
			   	// Final Goal of my Code
				if (empty($locationsList)===false) {
					foreach ($locationsList as $locKey => $location) {

						$if_data_exists = GmbLocation::where('website_url',$location->websiteUri)->where('user_id',$getUserDetails->user_id)->where('google_account_id', $getLastInsertedId)->first();


						// $lat_long = $this->get_location_details($location->locationName);					

						$requestData = [
							'user_id'=>$getUserDetails->user_id,
							'google_account_id'=>$getLastInsertedId,
							'account_id'=>$accountName,
							'location_id'=>$location->name,
							'labels'=>null,
							'language_code'=>$location->languageCode,
							'location_name'=>$location->title,
							'primary_phone'=>$location->primaryPhone,
							'store_code'=>$location->storeCode,
							'website_url'=>$location->websiteUri,
							'status'=> 1
							// 'location_lat'=>$lat_long['lat'],
							// 'location_lng'=>$lat_long['lng']
						];
						
						if(empty($if_data_exists)){
							GmbLocation::create($requestData);
						}else{
							GmbLocation::where('id',$if_data_exists->id)->update($requestData);
						}
					}

				}
			}
			$result['status'] = 1;
		}catch(Exception $e){
			$error = json_decode($e->getMessage(),true);
			$result['status'] = 0;
			$result['message'] = $error['error']['message'];
		}
		return $result;
	}


	public function getLocationReviews($client,$accountName,$locationName){
		$mybusinessService = new \Google_Service_MyBusiness($client);	
		$reviews = $mybusinessService->accounts_locations_reviews;
		$accountLocation = $accountName.'/'.$locationName;
		$listReviewsResponse = $reviews->listAccountsLocationsReviews($accountLocation);
		$array = array();
		$array['totalReviewCount'] = $listReviewsResponse->totalReviewCount;
		if($listReviewsResponse->totalReviewCount > 0){
			foreach($listReviewsResponse->reviews as $key=>$value){
				$array['review'][$key]['create_time'] = $value->createTime;
				$array['review'][$key]['update_time'] = $value->updateTime;
				$array['review'][$key]['review_id'] = $value->reviewId;
				$array['review'][$key]['reviewer_display_name'] = $value->reviewer->displayName;
				$array['review'][$key]['reviewer_profile_photo'] = $value->reviewer->profilePhotoUrl;
				$array['review'][$key]['is_anonymous'] = $value->reviewer->isAnonymous;
				$array['review'][$key]['comment'] = $value->comment;
				$array['review'][$key]['rating'] = $value->starRating;
				
				if(isset($value->reviewReply)){
					$array['review'][$key]['review_reply'] = $value->reviewReply->comment;
					$array['review'][$key]['reply_update_time'] = $value->reviewReply->updateTime;
				}else{
					$array['review'][$key]['review_reply'] = '';
					$array['review'][$key]['reply_update_time'] = '';
				}
			}
		}
		
		return $array;
	}

	public function getLocationMetrix($client,$accountName,$locationName,$start_date,$end_date){

 		// Get Review By location
		$mybusinessService = new \Google_Service_MyBusiness($client);
		$reportLocationInsightsRequest = new \Google_Service_MyBusiness_ReportLocationInsightsRequest(); 
		$basicRequest = new \Google_Service_MyBusiness_BasicMetricsRequest(); 
		$metricRequests = new \Google_Service_MyBusiness_MetricRequest(); 
		$timeRange = new \Google_Service_MyBusiness_TimeRange(); 

		$locations = $mybusinessService->accounts_locations;


		$drivingDirectionsRequest = new \Google_Service_MyBusiness_DrivingDirectionMetricsRequest(); 
		
		$metricRequests->setMetric("ALL"); 

		$basicRequest->setMetricRequests($metricRequests); 
		$accountLocation = $accountName.'/'.$locationName;
		
		$timeRange->setStartTime($end_date); 
		$timeRange->setEndTime($start_date); 
		$basicRequest->setTimeRange($timeRange); 
		$reportLocationInsightsRequest->setBasicRequest($basicRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponse = $locations->reportInsights($accountName, $reportLocationInsightsRequest);

		$locationMetrics = $reportLocationInsightsResponse->getLocationMetrics(); 
		$metricValuesArray = array();
		$metricValuesItem = array(); 
		$locationMetricsArray = array(); 

		if(count($locationMetrics) > 0){
			$locationMetricsArray['locationMetrics']['locationName'] = array($locationMetrics[0]->getLocationName()); 
			$locationMetricsArray['locationMetrics']['timeZone'] = array($locationMetrics[0]->getTimeZone()); 
			foreach ($locationMetrics[0]->getMetricValues() as $key =>$value) { 
				$metricValuesItem[$value['metric']]['endTime'] = $value['totalValue']['timeDimension']['timeRange']['endTime']; 
				$metricValuesItem[$value['metric']]['startTime'] = $value['totalValue']['timeDimension']['timeRange']['startTime']; 
				$metricValuesItem[$value['metric']]['value'] = $value['totalValue']['value']; 

				// $doughnutval[] = $value['totalValue']['value'];

			} 		
		}

		return $metricValuesItem;

	}


	public function getLocationMetrixViewMap($client,$accountName,$locationName,$start_date,$end_date){

		$mybusinessService = new \Google_Service_MyBusiness($client);	
		$reportLocationInsightsRequest = new \Google_Service_MyBusiness_ReportLocationInsightsRequest(); 
		$basicRequest = new \Google_Service_MyBusiness_BasicMetricsRequest(); 
		$metricRequests = new \Google_Service_MyBusiness_MetricRequest(); 
		$timeRange = new \Google_Service_MyBusiness_TimeRange(); 

		$locations = $mybusinessService->accounts_locations;


		$drivingDirectionsRequest = new \Google_Service_MyBusiness_DrivingDirectionMetricsRequest(); 
		

		$timeRange->setStartTime($end_date); 
		$timeRange->setEndTime($start_date); 
		$basicRequest->setTimeRange($timeRange); 

		$metricRequests->setMetric("VIEWS_MAPS"); 
		$metricRequests->setOptions("AGGREGATED_DAILY"); 

		$accountLocation = $accountName.'/'.$locationName;
		$basicRequest->setMetricRequests($metricRequests); 
		$reportLocationInsightsRequest->setBasicRequest($basicRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponse = $locations->reportInsights($accountName, $reportLocationInsightsRequest);

		

		$locationMetrics = $reportLocationInsightsResponse->getLocationMetrics(); 
		$locationMetricsArray = array(); 
		
		$metricValuesArray = array();
		$metricValuesView = array(); 
		if(count($locationMetrics) > 0){
			$DataArr = $locationMetrics[0]['metricValues'][0]['dimensionalValues'];
			foreach ($DataArr as $key =>$value) { 
				$metricValuesView['labels'][$key] = $value['timeDimension']['timeRange']['startTime']; 
				$metricValuesView['value'][$key] = $value['value']?:"0"; 
			}
		}
		

		/************  Search View  ************/
		$metricRequests->setMetric("VIEWS_SEARCH"); 
		$metricRequests->setOptions("AGGREGATED_DAILY"); 

		$basicRequest->setMetricRequests($metricRequests); 
		$reportLocationInsightsRequest->setBasicRequest($basicRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponseSearch = $locations->reportInsights($accountName, $reportLocationInsightsRequest);

		

		$locationMetricsSearch = $reportLocationInsightsResponseSearch->getLocationMetrics(); 
		$locationMetricsArray = array(); 
		
		$metricValuesArraySearch = array();


		$metricValuesSearch = array(); 
		if(count($locationMetricsSearch) > 0){
			$DataArrSearch = $locationMetricsSearch[0]['metricValues'][0]['dimensionalValues'];
			foreach ($DataArrSearch as $key =>$value) { 
				$metricValuesSearch['labels'][$key] = $value['timeDimension']['timeRange']['startTime']; 
				$metricValuesSearch['value'][$key] = $value['value']?:"0"; 
			}
		}

		
		
		return array('search'=>$metricValuesSearch,'maps'=>$metricValuesView);
	}

	public function getLocationMetrixCustomerActions($client,$accountName,$locationName,$start_date,$end_date){

		$mybusinessService = new \Google_Service_MyBusiness($client);
		$reportLocationInsightsRequest = new \Google_Service_MyBusiness_ReportLocationInsightsRequest(); 
		$basicRequest = new \Google_Service_MyBusiness_BasicMetricsRequest(); 
		$metricRequests = new \Google_Service_MyBusiness_MetricRequest(); 
		$timeRange = new \Google_Service_MyBusiness_TimeRange(); 

		$locations = $mybusinessService->accounts_locations;

		$accountLocation = $accountName.'/'.$locationName;
		$drivingDirectionsRequest = new \Google_Service_MyBusiness_DrivingDirectionMetricsRequest(); 

		$timeRange->setStartTime($end_date); 
		$timeRange->setEndTime($start_date); 
		$basicRequest->setTimeRange($timeRange); 

		$metricRequests->setMetric("ACTIONS_PHONE"); 
		$metricRequests->setOptions("AGGREGATED_DAILY"); 

		$basicRequest->setMetricRequests($metricRequests); 
		$reportLocationInsightsRequest->setBasicRequest($basicRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponsePhone = $locations->reportInsights($accountName, $reportLocationInsightsRequest);

		

		$locationMetricsPhone = $reportLocationInsightsResponsePhone->getLocationMetrics(); 
		$locationMetricsArray = array(); 
		
		$metricValuesArray = array();
		$metricValuesPhone = array(); 

		if(count($locationMetricsPhone) > 0){
			$DataArrPhone = $locationMetricsPhone[0]['metricValues'][0]['dimensionalValues'];

			foreach ($DataArrPhone as $key =>$value) { 
				$metricValuesPhone['labels'][$key] = $value['timeDimension']['timeRange']['startTime']; 
				$metricValuesPhone['value'][$key] = $value['value']?:"0"; 
			}
		}
		

		/************  ACTIONS WEBSITE  ************/
		$metricRequests->setMetric("ACTIONS_WEBSITE"); 
		$metricRequests->setOptions("AGGREGATED_DAILY"); 

		$basicRequest->setMetricRequests($metricRequests); 
		$reportLocationInsightsRequest->setBasicRequest($basicRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponseWebsite = $locations->reportInsights($accountName, $reportLocationInsightsRequest);

		

		$locationMetricsWebsite = $reportLocationInsightsResponseWebsite->getLocationMetrics(); 
		$locationMetricsArray = array(); 
		
		$metricValuesArrayWebsite = array();
		$metricValuesWebsite = array(); 

		if(count($locationMetricsWebsite) > 0){
			$DataArrWebsite = $locationMetricsWebsite[0]['metricValues'][0]['dimensionalValues'];
			foreach ($DataArrWebsite as $key =>$value) { 
				$metricValuesWebsite['labels'][$key] = $value['timeDimension']['timeRange']['startTime']; 
				$metricValuesWebsite['value'][$key] = $value['value']?:"0"; 
			}

		}

		/************  ACTIONS DRIVING DIRECTIONS  ************/

		$metricRequests->setMetric("ACTIONS_DRIVING_DIRECTIONS"); 
		$metricRequests->setOptions("AGGREGATED_DAILY"); 

		$basicRequest->setMetricRequests($metricRequests); 
		$reportLocationInsightsRequest->setBasicRequest($basicRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponseDirections = $locations->reportInsights($accountName, $reportLocationInsightsRequest);

		

		$locationMetricsDirections = $reportLocationInsightsResponseDirections->getLocationMetrics(); 
		$locationMetricsArray = array(); 
		
		$metricValuesArrayWebsite = array();
		$metricValuesDirections = array(); 

		if(count($locationMetricsDirections) > 0){
			$DataArrDirections = $locationMetricsDirections[0]['metricValues'][0]['dimensionalValues'];
			foreach ($DataArrDirections as $key =>$value) { 
				$metricValuesDirections['labels'][$key] = $value['timeDimension']['timeRange']['startTime']; 
				$metricValuesDirections['value'][$key] = $value['value']?:"0"; 
			}

		}
		
		return array('website'=>$metricValuesWebsite,'directions'=>$metricValuesDirections,'phone'=>$metricValuesPhone);
	}




	public function getLocationMedia($client,$accountName,$locationName){
		$mybusinessService = new \Google_Service_MyBusiness($client);
		$locations = $mybusinessService->accounts_locations_media;
		$accountLocation = $accountName.'/'.$locationName;
		$locationsList = $locations->listAccountsLocationsMedia($accountLocation);

		$photo = array();
		if (empty($locationsList)===false) {
			foreach ($locationsList as $locKey => $location) {
				$photo[$locKey]['createTime'] = $location['createTime']; 
				$photo[$locKey]['googleUrl'] = $location['googleUrl']; 
				$photo[$locKey]['name'] = $location['name']; 
				$photo[$locKey]['thumbnailUrl'] = $location['thumbnailUrl']; 
				$photo[$locKey]['category'] = $location['locationAssociation']['category']; 
				$photo[$locKey]['height'] = $location['dimensions']['heightPixels']; 
				$photo[$locKey]['width'] = $location['dimensions']['widthPixels']; 
				$photo[$locKey]['viewCount'] = $location['insights']['viewCount']; 
			}
		}
		return array('photo'=>$photo);
	}



	public function getLocationMetrixPhotoViews($client,$accountName,$locationName,$start_date,$end_date){

		$mybusinessService = new \Google_Service_MyBusiness($client);
		$reportLocationInsightsRequest = new \Google_Service_MyBusiness_ReportLocationInsightsRequest(); 
		$basicRequest = new \Google_Service_MyBusiness_BasicMetricsRequest(); 
		$metricRequests = new \Google_Service_MyBusiness_MetricRequest(); 
		$timeRange = new \Google_Service_MyBusiness_TimeRange(); 

		$locations = $mybusinessService->accounts_locations;


		$drivingDirectionsRequest = new \Google_Service_MyBusiness_DrivingDirectionMetricsRequest(); 

		$timeRange->setStartTime($end_date); 
		$timeRange->setEndTime($start_date); 
		$basicRequest->setTimeRange($timeRange); 
		$accountLocation = $accountName.'/'.$locationName;

		/************  PHOTOS_VIEWS_MERCHANT   ************/
		$metricRequests->setMetric("PHOTOS_VIEWS_MERCHANT"); 
		$metricRequests->setOptions("AGGREGATED_DAILY"); 

		$basicRequest->setMetricRequests($metricRequests); 
		$reportLocationInsightsRequest->setBasicRequest($basicRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponsePhone = $locations->reportInsights($accountName, $reportLocationInsightsRequest);

		$locationMetricsPhone = $reportLocationInsightsResponsePhone->getLocationMetrics(); 
		$locationMetricsArray = array(); 

		
		$metricValuesArray = array();	
		$photoViewsYou = array(); 

		if(count($locationMetricsPhone) > 0){
			$DataArrPhone = $locationMetricsPhone[0]['metricValues'][0]['dimensionalValues'];
			foreach ($DataArrPhone as $key =>$value) { 
				$photoViewsYou['labels'][$key] = $value['timeDimension']['timeRange']['startTime']; 
				$photoViewsYou['value'][$key] = $value['value']?:0; 
			}
		}

		/************  PHOTOS_VIEWS_MERCHANT  ************/
		$TotalPhotoViews = 0;
		$metricRequests->setMetric("PHOTOS_VIEWS_MERCHANT"); 
		$metricRequests->setOptions("AGGREGATED_TOTAL"); 

		$basicRequest->setMetricRequests($metricRequests); 
		$reportLocationInsightsRequest->setBasicRequest($basicRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponseWebsite = $locations->reportInsights($accountName, $reportLocationInsightsRequest);

		$locationMetricsWebsite = $reportLocationInsightsResponseWebsite->getLocationMetrics(); 
		$locationMetricsArray = array(); 
		
		$metricValuesArrayWebsite = array();

		$TotalPhotoViews = array();
		if(count($locationMetricsWebsite) > 0){
			$TotalPhotoViews = $locationMetricsWebsite[0]['metricValues'][0]['totalValue']['value'];
		}
		
		return array('you'=>$photoViewsYou,'you_total'=>$TotalPhotoViews);
	}

	public function getLocationMetrixPhotoQuantity($client,$accountName,$locationName,$start_date,$end_date){

		$mybusinessService = new \Google_Service_MyBusiness($client);
		$reportLocationInsightsRequest = new \Google_Service_MyBusiness_ReportLocationInsightsRequest(); 
		$basicRequest = new \Google_Service_MyBusiness_BasicMetricsRequest(); 
		$metricRequests = new \Google_Service_MyBusiness_MetricRequest(); 
		$timeRange = new \Google_Service_MyBusiness_TimeRange(); 
		// $media = new \Google_Service_MyBusiness_AccountsLocationsMedia_Resource(); 

		$accountLocation = $accountName.'/'.$locationName;

		$media_item= $mybusinessService->accounts_locations_media;

		// 	echo "<pre>";
		// print_r($media_item);
		// die;
		
		$locations = $mybusinessService->accounts_locations;



		$timeRange->setStartTime($end_date); 
		$timeRange->setEndTime($start_date); 
		$basicRequest->setTimeRange($timeRange); 


		/************  PHOTOS_VIEWS_MERCHANT   ************/
		$metricRequests->setMetric("PHOTOS_COUNT_MERCHANT"); 
		$metricRequests->setOptions("AGGREGATED_DAILY"); 

		$basicRequest->setMetricRequests($metricRequests); 
		$reportLocationInsightsRequest->setBasicRequest($basicRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponsePhone = $locations->reportInsights($accountName, $reportLocationInsightsRequest);
		$media_item_response = $media_item->get($accountName,$reportLocationInsightsRequest);
		
		$locationMetricsPhone = $reportLocationInsightsResponsePhone->getLocationMetrics(); 
		
		$locationMetricsArray = array(); 

		
		$metricValuesArray = array();	
		$DataArrPhone = $locationMetricsPhone[0]['metricValues'][0]['dimensionalValues'];

		$photoViewsYou = array(); 
		foreach ($DataArrPhone as $key =>$value) { 
			$photoViewsYou['labels'][$key] = $value['timeDimension']['timeRange']['startTime']; 
			$photoViewsYou['value'][$key] = $value['value']?:0; 
		}
		
		return array('you'=>$photoViewsYou,'you_total'=>$TotalPhotoViews);
	}

	public function getDirectionRequests($client,$accountName,$locationName,$num_days){
		$mybusinessService = new \Google_Service_MyBusiness($client);	
		$reportLocationInsightsRequest = new \Google_Service_MyBusiness_ReportLocationInsightsRequest();
		$drivingDirectionsRequest = new \Google_Service_MyBusiness_DrivingDirectionMetricsRequest();

		$locations = $mybusinessService->accounts_locations;
		$accountLocation = $accountName.'/'.$locationName;
		$drivingDirectionsRequest->setNumDays($num_days);

		$reportLocationInsightsRequest->setDrivingDirectionsRequest($drivingDirectionsRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponse = $locations->reportInsights($accountName, $reportLocationInsightsRequest); 
		$locationDrivingDirectionMetrics = $reportLocationInsightsResponse->getLocationDrivingDirectionMetrics();
		
		$locationDrivingDirectionMetricsArray = array();

		$topDirectionSourcesItem = array();

		if(count($locationDrivingDirectionMetrics) > 0){
			foreach ($locationDrivingDirectionMetrics[0]->getTopDirectionSources() as $key=> $value) {
				$topDirectionSourcesItem['dayCount'] = $value['dayCount'];
				foreach($value['regionCounts'] as $direction_key =>$direction_value){
					$topDirectionSourcesItem['data'][$direction_key]['label'] = $direction_value['label'];
					$topDirectionSourcesItem['data'][$direction_key]['count'] = $direction_value['count'];
					$topDirectionSourcesItem['data'][$direction_key]['latlng']['latitude'] = $direction_value['latlng']['latitude'];
					$topDirectionSourcesItem['data'][$direction_key]['latlng']['longitude'] = $direction_value['latlng']['longitude'];
				}
			}
		}

		return $topDirectionSourcesItem;

	}

	public function getPhoneCalls($client,$accountName,$locationName,$start_date,$end_date){
		$mybusinessService = new \Google_Service_MyBusiness($client);
		$reportLocationInsightsRequest = new \Google_Service_MyBusiness_ReportLocationInsightsRequest(); 
		$basicRequest = new \Google_Service_MyBusiness_BasicMetricsRequest(); 
		$metricRequests = new \Google_Service_MyBusiness_MetricRequest(); 
		$timeRange = new \Google_Service_MyBusiness_TimeRange(); 

		$locations = $mybusinessService->accounts_locations;
		$accountLocation = $accountName.'/'.$locationName;

		$drivingDirectionsRequest = new \Google_Service_MyBusiness_DrivingDirectionMetricsRequest(); 

		$timeRange->setStartTime($end_date); 
		$timeRange->setEndTime($start_date); 
		$basicRequest->setTimeRange($timeRange); 


		/************  PHOTOS_VIEWS_MERCHANT   ************/
		$metricRequests->setMetric("ACTIONS_PHONE"); 
		$metricRequests->setOptions("AGGREGATED_DAILY"); 

		$basicRequest->setMetricRequests($metricRequests); 
		$reportLocationInsightsRequest->setBasicRequest($basicRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponsePhone = $locations->reportInsights($accountName, $reportLocationInsightsRequest);

		$locationMetricsPhone = $reportLocationInsightsResponsePhone->getLocationMetrics(); 

		$locationMetricsArray = array(); 

		
		$metricValuesArray = array();	
		$photoViewsYou['value']["Sat"] = $photoViewsYou['value']["Sun"] = $photoViewsYou['value']["Mon"] = $photoViewsYou['value']["Tue"] = $photoViewsYou['value']["Wed"] = $photoViewsYou['value']["Thu"] = $photoViewsYou['value']["Fri"] = 0;

		if(count($locationMetricsPhone) > 0){
			$DataArrPhone = $locationMetricsPhone[0]['metricValues'][0]['dimensionalValues'];
			foreach ($DataArrPhone as $key =>$value) { 
				$day = date('D',strtotime($value['timeDimension']['timeRange']['startTime'])); 
				$photoViewsYou['labels'][$day] = $day; 
				$photoViewsYou['value'][$day] += $value['value']?:'0'; 
			}
		}

		return $photoViewsYou;

	}
	
	public function getPhoneQuantity($client,$accountName,$locationName,$start_date,$end_date){
		$mybusinessService = new \Google_Service_MyBusiness($client);
		$reportLocationInsightsRequest = new \Google_Service_MyBusiness_ReportLocationInsightsRequest(); 
		$basicRequest = new \Google_Service_MyBusiness_BasicMetricsRequest(); 
		$metricRequests = new \Google_Service_MyBusiness_MetricRequest(); 
		$timeRange = new \Google_Service_MyBusiness_TimeRange(); 

		$locations = $mybusinessService->accounts_locations;
		$accountLocation = $accountName.'/'.$locationName;
		$drivingDirectionsRequest = new \Google_Service_MyBusiness_DrivingDirectionMetricsRequest(); 
		
		// photo count customer
		$metricRequests->setMetric("PHOTOS_COUNT_MERCHANT"); 
		$basicRequest->setMetricRequests($metricRequests); 

		
		$timeRange->setStartTime($end_date); 
		$timeRange->setEndTime($start_date); 
		$basicRequest->setTimeRange($timeRange); 
		$reportLocationInsightsRequest->setBasicRequest($basicRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponse = $locations->reportInsights($accountName, $reportLocationInsightsRequest);

		$locationMetrics = $reportLocationInsightsResponse->getLocationMetrics(); 
		$locationMetricsArray = array(); 
		$locationMetricsArray['locationMetrics']['locationName'] = array($locationMetrics[0]->getLocationName()); 
		$locationMetricsArray['locationMetrics']['timeZone'] = array($locationMetrics[0]->getTimeZone()); 

		
		$photo_count_merchantArray = array(); 
		foreach ($locationMetrics[0]->getMetricValues() as $key =>$value) { 
			$photo_count_merchantArray['endTime'] = $value['totalValue']['timeDimension']['timeRange']['endTime']; 
			$photo_count_merchantArray['startTime'] = $value['totalValue']['timeDimension']['timeRange']['startTime']; 
			$photo_count_merchantArray['value'] = $value['totalValue']['value']?:0; 
		} 	
		
		//PHOTOS_COUNT_CUSTOMERS
		$metricRequests->setMetric("PHOTOS_COUNT_CUSTOMERS"); 
		$basicRequest->setMetricRequests($metricRequests); 

		
		$timeRange->setStartTime($end_date); 
		$timeRange->setEndTime($start_date); 
		$basicRequest->setTimeRange($timeRange); 
		$reportLocationInsightsRequest->setBasicRequest($basicRequest); 
		$reportLocationInsightsRequest->setLocationNames(array($accountLocation)); 
		$reportLocationInsightsResponse = $locations->reportInsights($accountName, $reportLocationInsightsRequest);

		$photo_count_customers = $reportLocationInsightsResponse->getLocationMetrics(); 
		$locationMetricsArray = array(); 
		$locationMetricsArray['locationMetrics']['locationName'] = array($photo_count_customers[0]->getLocationName()); 
		$locationMetricsArray['locationMetrics']['timeZone'] = array($photo_count_customers[0]->getTimeZone()); 

		
		$photo_count_customersArray = array(); 
		foreach ($photo_count_customers[0]->getMetricValues() as $key =>$value) { 
			$photo_count_customersArray['endTime'] = $value['totalValue']['timeDimension']['timeRange']['endTime']; 
			$photo_count_customersArray['startTime'] = $value['totalValue']['timeDimension']['timeRange']['startTime']; 
			$photo_count_customersArray['value'] = $value['totalValue']['value']?:0; 
		} 			
		
		return array('photo_count_merchant'=>$photo_count_merchantArray,'photo_count_customers'=>$photo_count_customersArray);

	}

}