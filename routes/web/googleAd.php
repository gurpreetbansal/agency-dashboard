<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$domain = '{account}.'.\config('app.DOMAIN_NAME');

Route::group(['domain' => $domain], function() {

	Route::group(["namespace" => "Vendor\goolgeAd"], function($account) {
		Route::group(['middleware' => ['auth', 'Vendor']], function() {
			
			/********** Connect & Refesh accounts ************/

			Route::get('/ppc/connect','GoolgeAdConnectController@connectGoogleAds');
			Route::get('/ppc/connect/update','GoolgeAdConnectController@connectGoogleAdUpdates');
			Route::get('/ppc/campaigns','GoolgeAdConnectController@campaignList');

			Route::get('/ppc/disconnect','GoolgeAdConnectController@connectGoogleAds');

			Route::post('/ajax_disconnect_adwords','ProjectSettingsController@ajax_disconnect_adwords');
			
			/********** Save campaign Data ************/
			Route::post('/ppc/save/campaigns','GoolgeAdController@campaignSave');
			Route::get('/ppc/refresh/campaigns','GoolgeAdController@campaignRefresh');
			
			/********** Google Ad campaign data Ploting ************/

			Route::get('/ppc/summaries','GoolgeAdController@campaignSummary');

			Route::get('/ppc/campaign/list','GoolgeAdController@campaignData');
			Route::get('/ppc/campaigns/pagination','GoolgeAdController@campaignPagination');

			Route::get('/ppc/ads/groups','GoolgeAdController@adGroups');
			Route::get('/ppc/ads/groups/pagination','GoolgeAdController@adGroupsPagination');
			
			Route::get('/ppc/keywords/list','GoolgeAdController@keywordData');
			Route::get('/ppc/keywords/pagination','GoolgeAdController@keywordPagination');

			Route::get('/ppc/ads/list','GoolgeAdController@adsList');
			Route::get('/ppc/ads/pagination','GoolgeAdController@adsPagination');

			Route::get('/ppc/networks','GoolgeAdController@networkPerformance');
			Route::get('/ppc/networks/pagination','GoolgeAdController@networkPerformancePagination');

			Route::get('/ppc/devices','GoolgeAdController@devicesPerformance');
			Route::get('/ppc/devices/pagination','GoolgeAdController@devicesPerformancePagination');

			Route::get('/ppc/click/types','GoolgeAdController@clickTypes');
			Route::get('/ppc/click/types/pagination','GoolgeAdController@clickTypesPagination');

			Route::get('/ppc/ad/slots','GoolgeAdController@adSlots');
			Route::get('/ppc/ad/slots/pagination','GoolgeAdController@adSlotsPagination');

			
			

		});
		
	});
});

Route::get('/ppc/connect','Vendor\goolgeAd\GoolgeAdConnectController@connectGoogleAds');

/********** Google Ad campaign data Ploting view key ************/

Route::get('/ppc/summaries','Vendor\goolgeAd\GoolgeAdController@campaignSummary');

Route::get('/ppc/campaign/list','Vendor\goolgeAd\GoolgeAdController@campaignData');
Route::get('/ppc/campaigns/pagination','Vendor\goolgeAd\GoolgeAdController@campaignPagination');

Route::get('/ppc/ads/groups','Vendor\goolgeAd\GoolgeAdController@adGroups');
Route::get('/ppc/ads/groups/pagination','Vendor\goolgeAd\GoolgeAdController@adGroupsPagination');

Route::get('/ppc/keywords/list','Vendor\goolgeAd\GoolgeAdController@keywordData');
Route::get('/ppc/keywords/pagination','Vendor\goolgeAd\GoolgeAdController@keywordPagination');

Route::get('/ppc/ads/list','Vendor\goolgeAd\GoolgeAdController@adsList');
Route::get('/ppc/ads/pagination','Vendor\goolgeAd\GoolgeAdController@adsPagination');

Route::get('/ppc/networks','Vendor\goolgeAd\GoolgeAdController@networkPerformance');
Route::get('/ppc/networks/pagination','Vendor\goolgeAd\GoolgeAdController@networkPerformancePagination');

Route::get('/ppc/devices','Vendor\goolgeAd\GoolgeAdController@devicesPerformance');
Route::get('/ppc/devices/pagination','Vendor\goolgeAd\GoolgeAdController@devicesPerformancePagination');

Route::get('/ppc/click/types','Vendor\goolgeAd\GoolgeAdController@clickTypes');
Route::get('/ppc/click/types/pagination','Vendor\goolgeAd\GoolgeAdController@clickTypesPagination');

Route::get('/ppc/ad/slots','Vendor\goolgeAd\GoolgeAdController@adSlots');
Route::get('/ppc/ad/slots/pagination','Vendor\goolgeAd\GoolgeAdController@adSlotsPagination');