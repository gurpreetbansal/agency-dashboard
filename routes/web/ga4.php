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

	Route::group(["namespace" => "Vendor"], function($account) {
		Route::group(['middleware' => ['auth', 'Vendor']], function() {
			Route::get('/ajax_get_ga4_emails','GoogleAnalytics4Controller@ajax_get_ga4_emails');
			Route::get('/ajax_get_ga4_accounts','GoogleAnalytics4Controller@ajax_get_ga4_accounts');
			Route::get('/ajax_get_ga4_properties','GoogleAnalytics4Controller@ajax_get_ga4_properties');
			Route::post('/ajax_update_ga4_data','GoogleAnalytics4Controller@ajax_update_ga4_data');
			Route::post('/ajax_disconnect_ga4','GoogleAnalytics4Controller@ajax_disconnect_ga4');
			Route::get('/ajax_acquisition_overview','GoogleAnalytics4Controller@ajax_acquisition_overview');
			Route::get('/ajax_get_latest_googleAnalytics4','GoogleAnalytics4Controller@ajax_get_latest_googleAnalytics4');
			Route::get('/ajax_traffic_acquisition','GoogleAnalytics4Controller@ajax_traffic_acquisition');
			Route::get('/ajax_goals_listing_traffic_acquisition','GoogleAnalytics4Controller@ajax_goals_listing_traffic_acquisition');
			Route::get('/ajax_google_analytics_overview','GoogleAnalytics4Controller@ajax_google_analytics_overview');
			Route::post('/ajax_store_ga4_data','GoogleAnalytics4Controller@ajax_store_ga4_data');
			Route::get('/ajax_refresh_ga4_list','GoogleAnalytics4Controller@ajax_refresh_ga4_list');
			Route::get('/ajax_ga4_au_chart','GoogleAnalytics4Controller@ajax_ga4_au_chart');
			Route::get('/ajax_ga4_conversions_chart','GoogleAnalytics4Controller@ajax_ga4_conversions_chart');
			Route::get('/ajax_alluser_statistics','GoogleAnalytics4Controller@ajax_alluser_statistics');
			Route::get('/ajax_conversions_statstics','GoogleAnalytics4Controller@ajax_conversions_statstics');
			Route::get('/ajax_get_ga4_connected_accounts','GoogleAnalytics4Controller@ajax_get_ga4_connected_accounts');
		});
	});
});

Route::get('/connect_google_analytics_4','Vendor\GoogleAnalytics4Controller@connect_google_analytics_4');
Route::get('/ajax_ga4_au_chart','Vendor\GoogleAnalytics4Controller@ajax_ga4_au_chart');
Route::get('/ajax_ga4_conversions_chart','Vendor\GoogleAnalytics4Controller@ajax_ga4_conversions_chart');
Route::get('/ajax_alluser_statistics','Vendor\GoogleAnalytics4Controller@ajax_alluser_statistics');
Route::get('/ajax_conversions_statstics','Vendor\GoogleAnalytics4Controller@ajax_conversions_statstics');
Route::get('/ajax_acquisition_overview','Vendor\GoogleAnalytics4Controller@ajax_acquisition_overview');
Route::get('/ajax_traffic_acquisition','Vendor\GoogleAnalytics4Controller@ajax_traffic_acquisition');
Route::get('/ajax_goals_listing_traffic_acquisition','Vendor\GoogleAnalytics4Controller@ajax_goals_listing_traffic_acquisition');
Route::get('/ajax_google_analytics_overview','Vendor\GoogleAnalytics4Controller@ajax_google_analytics_overview');
Route::get('/ajax_SeoTraffic_acquisition_overview','Vendor\GoogleAnalytics4Controller@ajax_SeoTraffic_acquisition_overview');
Route::get('/ajax_goals_listing_traffic_acquisition_pdf','Vendor\GoogleAnalytics4Controller@ajax_goals_listing_traffic_acquisition_pdf');