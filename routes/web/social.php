<?php

use Illuminate\Support\Facades\Route;

$domain = '{account}.'.\config('app.DOMAIN_NAME');
Route::group(['domain' => $domain], function() {
	Route::group(['middleware' => ['auth', 'Vendor']], function() {
		Route::group(["namespace" => "Social"], function($account) {	
			// Route::get('/facebooklogin','SocialController@sampleview');
			// Route::get('/facebookdevelopment/{id}','FacebookDataController@index');
			Route::get('/facebookcallback','SocialController@redirectToProvider');
			// Route::get('/social/facebook/{id}','SocialController@socailFacebook');

			Route::get('ajax_get_facebook_accounts','SocialController@ajax_get_facebook_accounts');
			Route::get('ajax_get_facebook_existing_accounts','SocialController@getFacebookExistingAccounts');
			Route::get('ajax_get_facebook_connected_accounts','SocialController@getFacebookConnectedAccounts');
			Route::get('ajax_refresh_facebook_acccount_list','SocialController@getrefreshFacebookAccountList');
			Route::post('ajax_update_facebook_data','SocialController@ajax_update_facebook_data');
			Route::get('log_facebook_data','SocialController@log_facebook_data');
			Route::post('ajax_disconnect_facebook','SocialController@ajaxDisconnectFacebook');

			/*Facebook view graph start*/
			Route::get('/campaign_social_content/{campaign_id}','FacebookDataController@index');
			Route::get('/facebook_total_likes','FacebookDataController@facebookOverview');
			/*Likes*/
			Route::get('/facebook-view/{keys}','FacebookDataController@getFbView');
			Route::get('/getfblikes','FacebookDataController@getFbLikes');
			Route::get('/getfborganicpaidlikes','FacebookDataController@getFbOrganicPaidLikes');
			Route::get('/getfbgenderlikes','FacebookDataController@getFbGenderLikes');
			Route::get('/getfbcountrylikes','FacebookDataController@getFbCountryLikes');
			Route::get('/getfbcitylikes','FacebookDataController@getFbCityLikes');
			Route::get('/getfblanguagelikes','FacebookDataController@getFbLanguageLikes');
			/*Reach*/
			Route::get('/getfbreach','FacebookDataController@getFbReach');
			Route::get('/getfborganicpaidreach','FacebookDataController@getFbOrganicPaidReach');
			Route::get('/getfborganicpaidvideoreach','FacebookDataController@getFbOrganicPaidVideoReach');
			Route::get('/getfbgenderreach','FacebookDataController@getFbGenderReach');
			Route::get('/getfbcountryreach','FacebookDataController@getFbCountryReach');
			Route::get('/getfbcityreach','FacebookDataController@getFbCityReach');
			Route::get('/getfblanguagereach','FacebookDataController@getFbLanguageReach');
			/*Posts*/
			Route::get('/getfbposts','FacebookDataController@getFbPosts');
			Route::get('/getfbpostspagination','FacebookDataController@getFbPostsPagination');
			/*Reviews*/
			Route::get('/getfbreviews','FacebookDataController@getFbReviews');
			Route::get('/getfbreviewspagination','FacebookDataController@getFbReviewsPagination');
			/*Facebook view graph end*/
			
			Route::post('/facebook_search','FacebookDataController@getFBsearch');
			Route::get('/social_filters','FacebookDataController@socialFilters');
			Route::get('/social_date_range_filters','FacebookDataController@socialDateRangeFilters');
			Route::get('/get_social_log_errors','SocialController@get_social_log_errors');
		});
	});
});

Route::get('/facebookcallback','Social\SocialController@redirectToProvider');
Route::get('/facebook_cron','Social\SocialController@facebookCron');
Route::get('/campaign_fb_content/{campaign_id}','Social\FacebookDataController@campaign_fb_content');

Route::group(["namespace" => "ViewKey"], function($account) {
	Route::get('/download/facebook/{keys}','SocialPdfsController@index');
});

/*Overview*/
Route::get('/get_social_log_errors','Social\SocialController@get_social_log_errors');
Route::get('/facebook_total_likes','Social\FacebookDataController@facebookOverview');
Route::post('/facebook_search','Social\FacebookDataController@getFBsearch');
Route::get('/social_filters','Social\FacebookDataController@socialFilters');
Route::get('/social_date_range_filters','Social\FacebookDataController@socialDateRangeFilters');

/*Likes*/
Route::get('/facebook-view/{keys}','Social\FacebookDataController@getFbView');
Route::get('/getfblikes','Social\FacebookDataController@getFbLikes');
Route::get('/getfborganicpaidlikes','Social\FacebookDataController@getFbOrganicPaidLikes');
Route::get('/getfbgenderlikes','Social\FacebookDataController@getFbGenderLikes');
Route::get('/getfbcountrylikes','Social\FacebookDataController@getFbCountryLikes');
Route::get('/getfbcitylikes','Social\FacebookDataController@getFbCityLikes');
Route::get('/getfblanguagelikes','Social\FacebookDataController@getFbLanguageLikes');
/*Reach*/
Route::get('/getfbreach','Social\FacebookDataController@getFbReach');
Route::get('/getfborganicpaidreach','Social\FacebookDataController@getFbOrganicPaidReach');
Route::get('/getfborganicpaidvideoreach','Social\FacebookDataController@getFbOrganicPaidVideoReach');
Route::get('/getfbgenderreach','Social\FacebookDataController@getFbGenderReach');
Route::get('/getfbcountryreach','Social\FacebookDataController@getFbCountryReach');
Route::get('/getfbcityreach','Social\FacebookDataController@getFbCityReach');
Route::get('/getfblanguagereach','Social\FacebookDataController@getFbLanguageReach');
/*Posts*/
Route::get('/getfbposts','Social\FacebookDataController@getFbPosts');
Route::get('/getfbpostspagination','Social\FacebookDataController@getFbPostsPagination');
/*Reviews*/
Route::get('/getfbreviews','Social\FacebookDataController@getFbReviews');
Route::get('/getfbreviewspagination','Social\FacebookDataController@getFbReviewsPagination');

Route::get('/facebook-view-likes/{keys}','Social\FacebookDataController@getFbViewLikes');
Route::get('/facebook-view-reach/{keys}','Social\FacebookDataController@getFbViewReach');
Route::get('/facebook-view-postreviews/{keys}','Social\FacebookDataController@getFbViewPosts');
Route::get('/facebook-view-reviews/{keys}','Social\FacebookDataController@getFbViewReviews');