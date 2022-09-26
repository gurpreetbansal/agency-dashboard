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

				Route::get('/gmb/connect','GMBController@connectGMB');
				Route::post('/ajax_update_gmb_data','GMBController@ajax_update_gmb_data');
				
			});
		});
	});

Route::get('/gmb/connect','Vendor\GMBController@connectGMB');

/*gmb viewkey routes*/
Route::get('/ajax_fetch_customer_view_graph','Vendor\GMBController@ajax_fetch_customer_view_graph');
Route::get('/ajax_fetch_customer_action_graph','Vendor\GMBController@ajax_fetch_customer_action_graph');
Route::get('/ajax_fetch_photo_views_graph','Vendor\GMBController@ajax_fetch_photo_views_graph');
Route::get('/ajax_get_Customer_search','Vendor\GMBController@ajax_get_Customer_search');
Route::get('/ajax_get_direction_requests','Vendor\GMBController@ajax_get_direction_requests');
Route::get('/ajax_get_phone_calls','Vendor\GMBController@ajax_get_phone_calls');
Route::get('/ajax_get_photo_quantity','Vendor\GMBController@ajax_get_photo_quantity');
Route::get('/ajax_get_gmb_reviews','Vendor\GMBController@ajax_get_gmb_reviews');
Route::get('/ajax_get_gmb_reviews_pagination','Vendor\GMBController@ajax_get_gmb_reviews_pagination');
Route::get('/ajax_get_gmb_media','Vendor\GMBController@ajax_get_gmb_media');
Route::post('/ajax_gmb_date_range','Vendor\GMBController@ajax_gmb_date_range');
Route::get('/ajax_get_gmb_pdf_reviews','Vendor\GMBController@ajax_get_gmb_pdf_reviews');
Route::get('/ajax_get_Customer_search_pdf_data','Vendor\GMBController@ajax_get_Customer_search_pdf_data');
/*gmb viewkey routes*/

