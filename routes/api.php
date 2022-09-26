<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

/*Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    
    Route::post('logout', 'Api\v1\AuthController@logout');
    Route::post('refresh', 'Api\v1\AuthController@refresh');
    Route::get('me', 'Api\v1\AuthController@me');

});
*/
Route::group(["namespace" => "Api\V1"], function($account) {	


	Route::post('auth/login', 'AuthController@login');
	Route::post('auth/forgot-password', 'AuthController@forgotPassword');

	Route::post('auth/check/user', 'AuthController@refresh');
	Route::group(['middleware' => ['jwt.auth']], function()  {

		// Route::group(['prefix' => 'auth'], function() {
		    Route::get('auth/profile', 'AuthController@me');
		    // Route::post('auth/check/user', 'AuthController@refresh');
		    
		    Route::post('auth/update/profile', 'AuthController@updateProfile');
		    Route::post('auth/change/password', 'AuthController@changePassword');

			Route::get('auth/logout', 'AuthController@logout');

		// });

		Route::group(['prefix' => 'projects'], function() {
			
			Route::get('dashboard/type', 'ProjectsController@getDashboardType');
			Route::get('dashboard/region', 'ProjectsController@getDashboardRegion');
			Route::post('create', 'ProjectsController@store');

			Route::get('campaigns/active', 'ProjectsController@getDashboard');
			Route::get('campaigns/archived', 'ProjectsController@getArchivedDashboard');
			Route::get('campaigns/favorite/{id}', 'ProjectsController@markFavorite');

			Route::get('campaigns/archives/{id}', 'ProjectsController@archiveCampaigns');
			Route::get('campaigns/delete/{id}', 'ProjectsController@deleteCampaigns');
			Route::get('campaigns/restore/{id}', 'ProjectsController@restoreCampaigns');

		});

	});

});