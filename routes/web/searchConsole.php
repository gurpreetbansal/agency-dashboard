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
				Route::get('/ajax_new_search_console','SearchConsoleController@ajax_new_search_console');
				Route::get('/ajax_fetch_listing','SearchConsoleController@ajax_fetch_listing');
				Route::get('/ajax_display_search_console_graph','SearchConsoleController@ajax_display_search_console_graph');
				Route::get('/ajax_fetch_list_data','SearchConsoleController@ajax_fetch_list_data');
				Route::get('/ajax_search_console_queries','SearchConsoleController@ajax_search_console_queries');
				Route::get('/ajax_search_console_pages','SearchConsoleController@ajax_search_console_pages');
				Route::get('/ajax_search_console_countries','SearchConsoleController@ajax_search_console_countries');
				Route::get('/ajax_get_latest_console_data','SearchConsoleController@ajax_get_latest_console_data');

			});
			Route::get('/search_console_cron','SearchConsoleController@search_console_cron');
		});
	});
	Route::get('/ajax_new_search_console','Vendor\SearchConsoleController@ajax_new_search_console');
	Route::get('/ajax_fetch_listing','Vendor\SearchConsoleController@ajax_fetch_listing');
	Route::get('/ajax_display_search_console_graph','Vendor\SearchConsoleController@ajax_display_search_console_graph');
	Route::get('/ajax_fetch_list_data','Vendor\SearchConsoleController@ajax_fetch_list_data');
	Route::get('/ajax_fetch_list_data_visibility','Vendor\SearchConsoleController@ajax_fetch_list_data_visibility');
	Route::get('/ajax_search_console_queries','Vendor\SearchConsoleController@ajax_search_console_queries');
	Route::get('/ajax_search_console_pages','Vendor\SearchConsoleController@ajax_search_console_pages');
	Route::get('/ajax_search_console_countries','Vendor\SearchConsoleController@ajax_search_console_countries');
	Route::get('/ajax_fetch_list_data_pdf','Vendor\SearchConsoleController@ajax_fetch_list_data_pdf');