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
				Route::get('/', 'LoginController@showLogin')->name('front.login-new');
				Route::get('/login', 'LoginController@showLogin')->name('front.login-new');
				Route::post('/doLoginNew', 'LoginController@doLoginNew')->name('front.login-new');
				Route::get('/back_to_admin','LoginController@back_to_admin');

				Route::group(['middleware' => ['auth', 'Vendor']], function() {

					Route::get('/alerts','AlertsController@index');	

					Route::get('/logout', 'LoginController@logout');
					

					Route::get('/profile', 'UserController@profile')->name('profile');
					Route::post('/updateprofile', 'UserController@updateprofile')->name('profile');

					Route::any('/add_new_project', 'ProjectController@add_new_project');

					Route::get('/campaigndetail/{campaign_id}', 'ProjectController@campaign_detail')->name('campaigndetail');


					Route::post('/ajax_dfs_keyword_tracking', 'ProjectController@ajax_dfs_keyword_tracking');

					Route::get('/ajaxOrganicKeywords', 'ProjectController@organic_keywords')->name('vendor.organic_keywords');

					Route::get('/campaign-settings/{campaign_id}','CampaignSettingsController@index')->name('campaign-settings');	

					Route::get('/ajax_google_view_account/{account_id}/{campaignID}','CampaignSettingsController@ajax_google_view_account'); 
					Route::get('/ajax_google_view_account_analytics/{account_id}/{campaignID}','CampaignSettingsController@ajax_google_view_account_analytics'); 

					Route::get('/ajax_google_property_data/{property_id}','CampaignSettingsController@ajax_google_property_data'); 
					Route::get('/ajax_google_viewId_data/{property_id}','CampaignSettingsController@ajax_google_viewId_data'); 
					Route::post('/ajax_save_analytics_data','CampaignSettingsController@ajax_save_analytics_data'); 
					Route::post('/ajax_save_console_data','CampaignSettingsController@ajax_save_console_data');


					Route::get('/dfs_locations','ProjectController@dfs_locations');
					Route::get('/ajax_dfs_locations','ProjectController@ajax_dfs_locations');

					/*Google Adwords routes pop-up*/
					Route::get('/ajax_google_ads_campaigns/{account_id}/{campaignID}','CampaignSettingsController@ajax_google_ads_campaigns');
					Route::post('/ajax_save_google_ads_data','CampaignSettingsController@ajax_save_google_ads_data'); 

					/*ppc- dashboard start*/
					Route::get('/ppc-dashboard/{campaign_id}','PpcController@ppc_dashboard')->name('ppc.dashboard');

					Route::get('/ajaxSaveInCsv','PpcController@ajaxSaveInCsv');
					Route::get('/ajaxAdsCampaign','PpcController@ajaxAdsCampaign');
					Route::get('/ajaxAdsKeywords','PpcController@ajaxAdsKeywords');
					Route::get('/ajaxAdsData','PpcController@ajaxAdsData');
					Route::get('/ajaxAdGroupsData','PpcController@ajaxAdGroupsData');
					Route::get('/ajaxAdPerformanceNetwork','PpcController@ajaxAdPerformanceNetwork');
					Route::get('/ajaxAdPerformanceDevice','PpcController@ajaxAdPerformanceDevice');
					Route::get('/ajaxAdPerformanceClickTypes','PpcController@ajaxAdPerformanceClickTypes');
					Route::get('/ajaxAdPerformanceSlots','PpcController@ajaxAdPerformanceSlots');

					Route::get('/ppc_date_range_data','PpcController@ppc_date_range_data');
					Route::get('/summary_stats','PpcController@summary_stats');
					Route::get('/summary_statistics','PpcController@summary_statistics');
					/*ppc- dashboard end*/

					/*live keyword tracking*/
					Route::get('/ajaxLiveKeywordTrackingData','ProjectController@ajaxLiveKeywordTrackingData');
					Route::post('/ajaxgetLatestKeyword','ProjectController@ajaxgetLatestKeyword');
					Route::post('/ajaxUpdateTimeAgo','ProjectController@ajaxUpdateTimeAgo');
					Route::post('/ajax_mark_keyword_favorite','ProjectController@ajax_mark_keyword_favorite');
					Route::post('/ajax_update_tracking','ProjectController@ajax_update_tracking');
					Route::post('/ajax_delete_multiple_keywords','ProjectController@ajax_delete_multiple_keywords');
					Route::post('/ajax_live_keyword_chart','ProjectController@ajax_live_keyword_chart');
					Route::post('/ajax_update_keyword_startRank','ProjectController@ajax_update_keyword_startRank');
					Route::post('/ajax_update_keyword_data','ProjectController@ajax_update_keyword_data');
					Route::get('/serp/{campaign_id}','LiveKeywordController@serp');
					/*live keyword tracking end */

					/*Extra Organic Keywords (DFS) */
					Route::any('/ajax_dfs_extra_organic_keywords','ProjectController@ajax_dfs_extra_organic_keywords');
					Route::get('/keywordsMetricBarChart','ProjectController@keywordsMetricBarChart');
					Route::get('/keywordsMetricPieChart','ProjectController@keywordsMetricPieChart');
					Route::get('/ajax_organicKeywordRanking','DataForSeoController@ajax_organicKeywordRanking');
					
					/*Extra Organic Keywords end */

					/*serp stat start*/
					Route::post('/ajax_serp_stat','SerpStatController@ajax_serp_stat');
					Route::get('/ajax_serp_stat','SerpStatController@ajax_serp_stat');
					Route::get('/ajax_backlink_profile_datatable','SerpStatController@ajax_backlink_profile_data');
					Route::get('/ajax_referring_domains', 'SerpStatController@ajax_referring_domains');
					/*serp stat end*/

					Route::get('/ajax_googleAnalyticsGoal','ProjectController@ajax_googleAnalyticsGoal');
					Route::get('/ajax_traffic_growth_data','ProjectController@ajax_traffic_growth_data');


					/*ajax call for daterange organic traffic growth*/
					Route::get('/ajax_traffic_growth_date_range','ProjectController@ajax_traffic_growth_date_range');
					Route::get('/ajax_traffic_growth_chart_compare','ProjectController@ajax_traffic_growth_chart_compare');

					Route::get('/ajax_googleSearchConsole','ProjectController@ajax_googleSearchConsole');

					Route::get('/ajax_active_campaigns','AuthController@ajax_active_campaigns');
					Route::post('/ajax_archive_campaign','AuthController@ajax_archive_campaign');
					Route::post('/ajax_favourite_project','AuthController@ajax_favourite_project');
					Route::get('/archived-projects','AuthController@archieved_projects');
					Route::get('/ajax_list_archived_projects','ArchiveController@ajax_list_archived_projects');
					Route::post('/ajax_restore_project','ArchiveController@ajax_restore_project');
					Route::post('/ajax_delete_project','ArchiveController@ajax_delete_project');


					/*Campaign settings page*/
					Route::post('/ajax_save_campaign_general_settings','CampaignSettingsController@ajax_save_campaign_general_settings');
					Route::post('/ajax_save_campaign_white_label','CampaignSettingsController@ajax_save_campaign_white_label');
					Route::post('/ajax_upload_manager_image','CampaignSettingsController@ajax_upload_manager_image');
					Route::post('/ajax_remove_manager_image','CampaignSettingsController@ajax_remove_manager_image');

					Route::post('/ajax_upload_agency_logo','CampaignSettingsController@ajax_upload_agency_logo');

					Route::get('/ajax_get_agency_logo','CampaignSettingsController@ajax_get_agency_logo');
					Route::post('/ajax_remove_agency_logo','CampaignSettingsController@ajax_remove_agency_logo');
					Route::post('/ajax_save_dashboard_settings','CampaignSettingsController@ajax_save_dashboard_settings');


					Route::get('new-dashboard/{campaign_id}','DashboardController@new_dashboard')->name('new-dashboard');

					Route::get('/seo_content/{campaign_id}','DashboardController@seo_content');
					Route::get('/ppc_content/{campaign_id}','DashboardController@ppc_content');
					Route::get('/gmb_content/{campaign_id}','DashboardController@gmb_content');

					Route::post('/ajax_update_skip','DashboardController@ajax_update_skip');
					Route::post('/get_account_activity','DashboardController@get_account_activity');
					Route::get('/project_search_autocomplete','CommonController@project_search_autocomplete');
					Route::get('/ajax_check_campaign_time','CommonController@ajax_check_campaign_time');
					Route::get('/all_campaigns','CommonController@all_campaigns');

					Route::get('/ajax_show_view_key','CommonController@ajax_show_view_key');

					Route::get('/ajax_filter_campaigns','AuthController@ajax_filter_campaigns');

					Route::post('/resend_verification_email','AuthController@resend_verification_email');

					/* settings route */
					Route::get('/settings','SettingsController@index');
					Route::post('/save_settings','SettingsController@save_settings');
					Route::get('/unique_email','SettingsController@unique_email');
					/* settings route */
					/*authorization */
					Route::get('/authorization','AuthorizationController@index');
					Route::get('/ajax_auth_campaigns','AuthorizationController@ajax_auth_campaigns');
					Route::post('/ajax_save_tags','AuthorizationController@ajax_save_tags');
					Route::get('/ajax_get_tags','AuthorizationController@ajax_get_tags');
					Route::post('/ajax_update_analytics','AuthorizationController@ajax_update_analytics');
					Route::post('/ajax_update_search_console','AuthorizationController@ajax_update_search_console');
					
					Route::get('/ajax-gmb-connect-account/{account_id}/{campaignID}','AuthorizationController@ajax_google_view_account');

					Route::post('/ajax_save_gmb_location','AuthorizationController@ajax_save_console_data');

					/*authorization */

					/* Activity Section */

					Route::get('/activities-layout/{campaign_id}','TaskActivitiesController@index');
					Route::post('/add-activities','TaskActivitiesController@addActivity');
					Route::post('/add-activitylist','TaskActivitiesController@addActivitylist');

					Route::get('activities/{campaign_id}','TaskActivitiesController@activities');
					Route::get('activity/process/{campaign_id}','TaskActivitiesController@activityProcess');
					Route::get('activity/addmore','TaskActivitiesController@addmore');
					
					Route::get('ajax-activities','TaskActivitiesController@ajaxActivities');
					Route::get('ajax-activitytotal','TaskActivitiesController@ajaxActivityTotal');
					Route::get('delete-activities','TaskActivitiesController@deleteActivities');
					Route::get('delete-activitylist','TaskActivitiesController@deleteActivitylist');

					Route::get('/activity/create/{campaign_id}','TaskActivitiesController@viewActivity');
					Route::get('/activity/categories/{category_id}','TaskActivitiesController@viewCategories');
					
					/* account -settings */
					Route::get('/account-settings','AccountSettingsController@index')->name('account-settings');
					Route::post('/cancel_stripe_subscription','AccountSettingsController@cancel_stripe_subscription')->name('cancel_stripe_subscription');
					/* account -settings */

					
					Route::get('/ajax_get_traffic_data','AnalyticsController@ajax_get_traffic_data');
					Route::get('/ajax_get_traffic_metrics','AnalyticsController@ajax_get_traffic_metrics');
					Route::get('/ajax_get_compare_traffic_data','AnalyticsController@ajax_get_compare_traffic_data');
					Route::get('/ajax_get_traffic_date_range','AnalyticsController@ajax_get_traffic_date_range');
					Route::get('/ajax_get_traffic_date_range_metrics','AnalyticsController@ajax_get_traffic_date_range_metrics');
					Route::get('/ajax_get_goal_completion_chart_data','AnalyticsController@ajax_get_goal_completion_chart_data');
					Route::get('/ajax_get_goal_completion_overview','AnalyticsController@ajax_get_goal_completion_overview');
					Route::get('/ajax_get_goal_completion_location','AnalyticsController@ajax_get_goal_completion_location');
					Route::get('/ajax_get_goal_completion_location_pagination','AnalyticsController@ajax_get_goal_completion_location_pagination');
					Route::get('/ajax_get_goal_completion_sourcemedium','AnalyticsController@ajax_get_goal_completion_sourcemedium');
					Route::get('/ajax_get_goal_completion_sourcemedium_pagination','AnalyticsController@ajax_get_goal_completion_sourcemedium_pagination');
					Route::get('/ajax_goal_completion_all_users_chart','AnalyticsController@ajax_goal_completion_all_users_chart');
					Route::get('/ajax_goal_value_all_users_chart','AnalyticsController@ajax_goal_value_all_users_chart'); 
					Route::get('/ajax_goal_conversion_all_users_chart','AnalyticsController@ajax_goal_conversion_all_users_chart'); 
					Route::get('/ajax_goal_abondon_all_users_chart','AnalyticsController@ajax_goal_abondon_all_users_chart'); 
					Route::get('/ajax_goal_completion_organic_chart','AnalyticsController@ajax_goal_completion_organic_chart'); 
					Route::get('/ajax_goal_value_organic_chart','AnalyticsController@ajax_goal_value_organic_chart'); 
					Route::get('/ajax_conversion_rate_organic_chart','AnalyticsController@ajax_conversion_rate_organic_chart'); 
					Route::get('/ajax_abondon_rate_organic_chart','AnalyticsController@ajax_abondon_rate_organic_chart'); 

					Route::get('/ajax_ecom_goal_completion_chart','EcommerceGoalsController@ajax_ecom_goal_completion_chart');
					Route::get('/ajax_ecom_conversion_rate_users','EcommerceGoalsController@ajax_ecom_conversion_rate_users');
					Route::get('/ajax_ecom_conversion_rate_organic','EcommerceGoalsController@ajax_ecom_conversion_rate_organic');
					Route::get('/ajax_ecom_transaction_users','EcommerceGoalsController@ajax_ecom_transaction_users');
					Route::get('/ajax_ecom_transaction_organic','EcommerceGoalsController@ajax_ecom_transaction_organic');
					Route::get('/ajax_ecom_revenue_users','EcommerceGoalsController@ajax_ecom_revenue_users');
					Route::get('/ajax_ecom_revenue_organic','EcommerceGoalsController@ajax_ecom_revenue_organic');
					Route::get('/ajax_ecom_avg_orderValue_users','EcommerceGoalsController@ajax_ecom_avg_orderValue_users');
					Route::get('/ajax_ecom_avg_orderValue_organic','EcommerceGoalsController@ajax_ecom_avg_orderValue_organic');
					Route::get('/ajax_ecom_goal_completion_overview','EcommerceGoalsController@ajax_ecom_goal_completion_overview');
					Route::get('/ajax_ecom_product','EcommerceGoalsController@ajax_ecom_product');
					Route::get('/ajax_ecom_product_pagination','EcommerceGoalsController@ajax_ecom_product_pagination');
					

					/*json cron search console*/
					Route::get('/ajax_get_search_console_graph','SearchConsoleController@ajax_get_search_console_graph');
					Route::get('/ajax_get_search_console_graph_date_range','SearchConsoleController@ajax_get_search_console_graph_date_range');
					Route::get('/ajax_get_search_console_queries','SearchConsoleController@ajax_get_search_console_queries');
					Route::get('/ajax_get_search_console_devices','SearchConsoleController@ajax_get_search_console_devices');
					Route::get('/ajax_get_search_console_pages','SearchConsoleController@ajax_get_search_console_pages');
					Route::get('/ajax_get_search_console_country','SearchConsoleController@ajax_get_search_console_country');
					/*json cron search console*/

					
					/*new design routes*/		

					// campaign-list and archive campaign			
					Route::get('/dashboard', 'AuthController@dashboard_new')->name('dashboard-new');
					// Route::get('/dashboard-new', 'AuthController@dashboard_new')->name('dashboard-new');
					Route::get('/ajax_fetch_campaign_data','AuthController@ajax_fetch_campaign_data');
					Route::get('/ajax_fetch_campaign_pagination','AuthController@ajax_fetch_campaign_pagination');
					Route::post('/ajax_archive_campaigns','AuthController@ajax_archive_campaigns');
					Route::get('/archived-campaigns','AuthController@archived_campaigns');
					Route::get('/ajax_fetch_archived_campaign_data','AuthController@ajax_fetch_archived_campaign_data');
					Route::get('/ajax_fetch_archived_campaign_pagination','AuthController@ajax_fetch_archived_campaign_pagination');
					Route::post('/ajax_delete_archived_project','AuthController@ajax_delete_archived_project');
					Route::post('/ajax_restore_archived_project','AuthController@ajax_restore_archived_project');
					Route::post('/ajax_delete_campaigns','AuthController@ajax_delete_campaigns');
					Route::post('/ajax_restore_campaigns','AuthController@ajax_restore_campaigns');

					//campaign-detail 
					Route::get('/campaign-detail/{campaign_id}','CampaignDetailController@campaign_detail');
					Route::get('/campaign_seo_content/{campaign_id}','CampaignDetailController@campaign_seo_content');
					Route::get('/campaign_ppc_content/{campaign_id}','CampaignDetailController@campaign_ppc_content');
					Route::get('/campaign_gmb_content/{campaign_id}','CampaignDetailController@campaign_gmb_content');
					Route::get('/campaign_social_content/{campaign_id}','CampaignDetailController@campaign_social_content');
					Route::get('/ajaxreferringdomains', 'CampaignDetailController@ajax_referring_domains');
					Route::get('/ajaxorganicKeywordRanking','CampaignDetailController@ajaxorganicKeywordRanking');
					Route::get('/ajax_organic_visitors','CampaignDetailController@ajax_organic_visitors');
					Route::get('/ajaxgoogleAnalyticsGoal','CampaignDetailController@ajaxgoogleAnalyticsGoal');
					Route::get('/ajax_extra_organic_bar_chart','ExtraOrganicController@ajax_extra_organic_bar_chart');
					Route::get('/ajax_extra_organic_keywords','ExtraOrganicController@ajax_extra_organic_keywords');
					Route::get('/ajax_extra_organic_keywords_count','ExtraOrganicController@ajax_extra_organic_keywords_count');
					Route::get('/extra-organic-keywords/{campaign_id}','ExtraOrganicController@extra_organic_keywords');
					Route::get('/ajax_fetch_organic_keyword_data','ExtraOrganicController@ajax_fetch_organic_keyword_data');
					Route::get('/ajax_fetch_keyword_pagination','ExtraOrganicController@ajax_fetch_keyword_pagination');
					Route::get('/generate_organic_keyword_excel/{campaign_id}','ExtraOrganicController@generate_organic_keyword_excel');
					Route::get('/ajax_get_latest_organic_keyword_growth','ExtraOrganicController@ajax_get_latest_organic_keyword_growth');
					Route::get('/ajax_get_organic_keyword_growth_time','ExtraOrganicController@ajax_get_organic_keyword_growth_time');


					//backlinks
					Route::get('/ajax_fetch_backlink_data','BacklinkProfileController@ajax_fetch_backlink_data');
					Route::get('/ajax_fetch_backlink_pagination','BacklinkProfileController@ajax_fetch_backlink_pagination');
					Route::get('/ajax_backlink_chart_data','BacklinkProfileController@ajax_backlink_chart_data');

					Route::get('/ajax_get_backlinkProfile_time','BacklinkProfileController@ajax_get_backlinkProfile_time');
					Route::get('/ajax_get_latest_backlinks','BacklinkProfileController@ajax_get_latest_backlinks');

					//goal completion
					Route::get('/ajax_get_goal_completion_list','GoalCompletionController@ajax_get_goal_completion_list');
					Route::get('/ajax_get_goal_completion_pagination','GoalCompletionController@ajax_get_goal_completion_pagination');

					Route::get('/ajax_live_keyword_stats','LiveKeywordController@ajax_live_keyword_stats');
					Route::get('/ajax_live_keyword_list','LiveKeywordController@ajax_live_keyword_list');
					Route::get('/ajax_live_keyword_pagination','LiveKeywordController@ajax_live_keyword_pagination');
					Route::post('/ajax_mark_live_keyword_favorite','LiveKeywordController@ajax_mark_live_keyword_favorite');
					Route::post('/ajax_live_keyword_chart_data','LiveKeywordController@ajax_live_keyword_chart_data');
					Route::post('/ajax_update_keyword_startRanking','LiveKeywordController@ajax_update_keyword_startRanking');
					Route::post('/ajax_remove_multiple_keywords','LiveKeywordController@ajax_remove_multiple_keywords');
					Route::post('/ajax_get_keywords_time','LiveKeywordController@ajax_get_keywords_time');
					Route::post('/ajax_update_live_keyword_tracking','LiveKeywordController@ajax_update_live_keyword_tracking');
					Route::post('/ajax_update_live_keywords_location','LiveKeywordController@ajax_update_live_keywords_location');
					Route::post('/ajax_add_keywords_data','LiveKeywordController@ajax_add_keywords_data');
					Route::post('/ajax_send_dfs_request','LiveKeywordController@ajax_send_dfs_request');
					Route::get('/ajax_export_live_keywords','LiveKeywordController@ajax_export_live_keywords');
					Route::get('/ajax_page_authority_chart','CampaignDetailController@ajax_page_authority_chart');
					Route::get('/ajax_domain_authority_chart','CampaignDetailController@ajax_domain_authority_chart');
					Route::get('/ajax_organic_keyword_chart','CampaignDetailController@ajax_organic_keyword_chart');
					Route::get('/ajax_organic_visitors_chart','CampaignDetailController@ajax_organic_visitors_chart');
					Route::get('/ajax_referring_domain_chart','CampaignDetailController@ajax_referring_domain_chart');

					//profile settings
					Route::get('/profile-settings', 'ProfileController@profile_settings');
					Route::post('/updateprofilesettings', 'ProfileController@updateprofilesettings');
					Route::post('/check_company_name', 'ProfileController@check_company_name');
					Route::post('/update_change_password', 'ProfileController@update_change_password');
					Route::get('/download-invoice/{invoice_id}', 'ProfileController@download_invoice');
					Route::get('/download-excel/{user_id}', 'ProfileController@download_excel');
					Route::post('/cancel_subscription', 'ProfileController@cancel_subscription');
					Route::post('/ajax_remove_profile_picture','ProfileController@ajax_remove_profile_picture');
					Route::post('/ajax_update_stripe_card_details','ProfileController@ajax_update_stripe_card_details');
					Route::post('/update_user_system_preference','ProfileController@update_user_system_preference');
					Route::get('/store-moz-data-monthly','CampaignDetailController@store_moz_data_monthly');
					Route::get('/ajax_check_current_password','ProfileController@ajax_check_current_password');
					Route::get('/ajax_match_confirm_password','ProfileController@ajax_match_confirm_password');

					#PPC#
					Route::get('/summary_overview','PpcController@summary_overview');
					Route::get('/ajax_fetch_ads_campaign_data','PpcController@ajax_fetch_ads_campaign_data');
					Route::get('/ajax_fetch_ads_campaign_pagination','PpcController@ajax_fetch_ads_campaign_pagination');
					Route::get('/ajax_fetch_ads_keywords_data','PpcController@ajax_fetch_ads_keywords_data');
					Route::get('/ajax_fetch_ads_keywords_pagination','PpcController@ajax_fetch_ads_keywords_pagination');
					Route::get('/ajax_fetch_adGroup_data','PpcController@ajax_fetch_adGroup_data');
					Route::get('/ajax_fetch_adGroup_pagination','PpcController@ajax_fetch_adGroup_pagination');
					Route::get('/ajax_fetch_adsPerformance_network_data','PpcController@ajax_fetch_adsPerformance_network_data');
					Route::get('/ajax_fetch_adsPerformance_network_pagination','PpcController@ajax_fetch_adsPerformance_network_pagination');
					Route::get('/ajax_fetch_adsPerformance_device_data','PpcController@ajax_fetch_adsPerformance_device_data');
					Route::get('/ajax_fetch_adsPerformance_device_pagination','PpcController@ajax_fetch_adsPerformance_device_pagination');
					Route::get('/ajax_fetch_adsPerformance_clickType_data','PpcController@ajax_fetch_adsPerformance_clickType_data');
					Route::get('/ajax_fetch_adsPerformance_clickType_pagination','PpcController@ajax_fetch_adsPerformance_clickType_pagination');
					Route::get('/ajax_fetch_adsPerformance_adSlot_data','PpcController@ajax_fetch_adsPerformance_adSlot_data');
					Route::get('/ajax_fetch_adsPerformance_adSlot_pagination','PpcController@ajax_fetch_adsPerformance_adSlot_pagination');
					Route::get('/ajax_fetch_ads_data','PpcController@ajax_fetch_ads_data');
					Route::get('/ajax_fetch_ads_pagination','PpcController@ajax_fetch_ads_pagination');
					Route::get('/ppc_summary_impressions_graph','PpcController@ppc_summary_impressions_graph');
					Route::get('/ppc_summary_cost_graph','PpcController@ppc_summary_cost_graph');
					Route::get('/ppc_summary_clicks_graph','PpcController@ppc_summary_clicks_graph');
					Route::get('/ppc_summary_averageCpc_graph','PpcController@ppc_summary_averageCpc_graph');
					Route::get('/ppc_summary_ctr_graph','PpcController@ppc_summary_ctr_graph');
					Route::get('/ppc_summary_conversions_graph','PpcController@ppc_summary_conversions_graph');
					Route::get('/ppc_summary_conversion_rate_graph','PpcController@ppc_summary_conversion_rate_graph');
					Route::get('/ppc_summary_cpc_rate_graph','PpcController@ppc_summary_cpc_rate_graph');
					Route::get('/ajax_fetch_date_range_chart','PpcController@ajax_fetch_date_range_chart');
					Route::get('/ajax_fetch_summary_statistics','PpcController@ajax_fetch_summary_statistics');


					
					Route::get('/ajax_fetch_ppc_chart','PpcController@ajax_fetch_ppc_chart');
					Route::get('/ajax_fetch_ppc_summary_statistics','PpcController@ajax_fetch_ppc_summary_statistics');
					Route::get('/ajax_compare_ppc_data','PpcController@ajax_compare_ppc_data');

					Route::get('/ajax_save_in_csv','PpcController@ajax_save_in_csv');

					

					/*add new project*/
					Route::get('/checkdnsrr','CreateProjectController@checkdnsrr');
					Route::post('/complete_steps','CreateProjectController@complete_steps');
					Route::get('/add-new-project','CreateProjectController@add_new_project');
					Route::post('/store_project_info','CreateProjectController@store_project_info');
					Route::get('/ajax_get_analytics_accounts','CreateProjectController@ajax_get_analytics_accounts');
					Route::get('/ajax_get_analytics_property','CreateProjectController@ajax_get_analytics_property');
					Route::get('/ajax_get_analytics_view','CreateProjectController@ajax_get_analytics_view');
					Route::post('/ajax_save_new_project_analytics_data','CreateProjectController@ajax_save_new_project_analytics_data');
					Route::get('/ajax_get_console_urls','CreateProjectController@ajax_get_console_urls');
					Route::post('/ajax_save_new_project_console_data','CreateProjectController@ajax_save_new_project_console_data');
					Route::get('/ajax_get_adwords_accounts','CreateProjectController@ajax_get_adwords_accounts');
					Route::post('/ajax_save_new_project_adwords_data','CreateProjectController@ajax_save_new_project_adwords_data');
					Route::post('/ajax_store_ranking_details','CreateProjectController@ajax_store_ranking_details');
					Route::get('/ajax_google_analytics_accounts','CreateProjectController@ajax_google_analytics_accounts');
					Route::get('/ajax_google_cnsole_accounts','CreateProjectController@ajax_google_cnsole_accounts');
					Route::get('/ajax_adwords_accounts','CreateProjectController@ajax_adwords_accounts');
					Route::get('/ajax_delete_added_project','CreateProjectController@ajax_delete_added_project');
					/*add new project*/

					/*project settings*/
					Route::get('/project-settings/{campaign_id}','ProjectSettingsController@index');
					Route::post('/ajax_store_project_general_settings','ProjectSettingsController@ajax_store_project_general_settings');
					Route::post('/ajax_store_project_white_label','ProjectSettingsController@ajax_store_project_white_label');
					Route::post('/ajax_update_dashboard_settings','ProjectSettingsController@ajax_update_dashboard_settings');
					Route::post('/ajax_disconnect_analaytics','ProjectSettingsController@ajax_disconnect_analaytics');
					Route::post('/ajax_disconnect_console','ProjectSettingsController@ajax_disconnect_console');
					Route::post('/ajax_disconnect_adwords','ProjectSettingsController@ajax_disconnect_adwords');

					Route::post('/dashboard-activate','ProjectSettingsController@dashboardActivate');
					
					Route::post('/ajax_remove_project_logo','ProjectSettingsController@ajax_remove_project_logo');
					Route::post('/ajax_remove_project_agency_logo','ProjectSettingsController@ajax_remove_project_agency_logo');
					
					// integration routes
					Route::post('/ajax_update_analytics_data','ProjectSettingsController@ajax_update_analytics_data');
					Route::post('/ajax_update_console_data','ProjectSettingsController@ajax_update_console_data');
					
					Route::post('/ajax_update_adwords_json','ProjectSettingsController@ajax_update_adwords_json');
					Route::post('/ajax_update_adwords_data','ProjectSettingsController@ajax_update_adwords_data');
					
					Route::post('/ajax_log_adwords_connected_data','ProjectSettingsController@ajax_log_adwords_connected_data');
					Route::post('/ajax_update_summary_data','ProjectSettingsController@ajax_update_summary_data');

					Route::get('/ajax_refresh_adwords_data','PpcController@ajax_refresh_adwords_data');
					Route::get('/ajax_refresh_adwords_json','PpcController@ajax_refresh_adwords_json');
					// integration routes
					/*project settings*/

					Route::get('/ajax_get_adwords_account_id','ProjectSettingsController@ajax_get_adwords_account_id');
					Route::post('/ajax_update_project_alerts','ProjectSettingsController@ajax_update_project_alerts');
					
					/*site audit*/
					Route::get('/site-audit','SiteAuditController@index');

					Route::get('/site-audit-design','SiteAuditController@indexdesign');
					Route::get('/site-audit-pages','SiteAuditController@indexpages');
					Route::get('/site-audit-internal-design','SiteAuditController@indexdesigninternal');

					Route::get('/site-audit/{campaignId}','SiteAuditController@index');
					Route::get('/audit-pages/{campaignId}','SiteAuditController@auditPages');

					Route::get('/audit-details/{campaignId}/{page}','SiteAuditController@auditDetails');


					Route::get('/audit-page-task/{campaignId}','SiteAuditController@onPageTask');
					Route::get('/ajax-auditpageissues/{campaignId}','SiteAuditController@ajaxAuditPageIssues');
					Route::get('/audit-page-task-refresh/{campaignId}','SiteAuditController@onPageTaskRefresh');
					

						/* Ajax List*/
					Route::get('/ajax-auditstatus/{campaignId}','SiteAuditController@ajaxAuditTashStatus');
					Route::get('/ajax-auditsummary/{campaignId}','SiteAuditController@ajaxAuditSummary');

					Route::get('/ajax-auditpages-label/{campaignId}','SiteAuditController@ajaxAuditpagesLabel');
					Route::get('/ajax-auditpages/{campaignId}','SiteAuditController@ajaxAuditpages');
					Route::get('/ajax-auditpagesidebar/{campaignId}','SiteAuditController@ajaxAuditPageSidebar');
					Route::post('/ajax-auditpageserrors','SiteAuditController@ajaxAuditpagesErrors');
					Route::post('/ajax-duplicatetages','SiteAuditController@ajaxDuplicateTages');
					Route::post('/ajax-viewsourcehtml','SiteAuditController@ajaxViewSourceHtml');
					Route::post('/ajax-linktypes','SiteAuditController@ajaxLinkTypes');


					Route::get('/ajax-auditdetail-rightside/{campaignId}','SiteAuditController@ajaxAuditDetailRightside');
					
					Route::get('/ajax-auditdetail-overview/{campaignId}','SiteAuditController@ajaxAuditDetailOverview');
					Route::get('/ajax-auditdetail-issues/{campaignId}','SiteAuditController@ajaxAuditDetailIssues');
					Route::get('/ajax-auditdetail-links/{campaignId}','SiteAuditController@ajaxAuditDetailLinks');

					

					Route::post('/ajax-dinsights/{campaignId}','SiteAuditController@ajaxDesktopInsights');
					Route::post('/ajax-minsights/{campaignId}','SiteAuditController@ajaxMobileInsights');
					Route::post('/ajax_store_max_crawl','SiteAuditController@ajax_store_max_crawl');
					Route::get('/no_audit_content/{campaign_id}','SiteAuditController@no_audit_content');
					/*site audit*/

					// live keyword tag and multiple fav
					Route::post('/ajax_multiple_keyword_fav_unfav','LiveKeywordController@ajax_multiple_keyword_fav_unfav');
					Route::get('/load_keyword_tag_section/{campaign_id}','LiveKeywordController@load_keyword_tag_section');
					Route::get('/ajax_fetch_existing_keyword_tags','LiveKeywordController@ajax_fetch_existing_keyword_tags');
					Route::post('/ajax_create_keyword_tag','LiveKeywordController@ajax_create_keyword_tag');
					Route::get('/ajax_list_existing_tags','LiveKeywordController@ajax_list_existing_tags');
					Route::post('/ajax_apply_existing_tags','LiveKeywordController@ajax_apply_existing_tags');
					Route::post('/ajax_delete_keyword_tag','LiveKeywordController@ajax_delete_keyword_tag');
					/*new design routes*/	

					 //gmb routes

                    Route::post('/ajax_update_gmb_data','GMBController@ajax_update_gmb_data');

                    Route::get('/log_gmb_data','GMBController@log_gmb_data');
                    Route::post('/ajax_disconnect_gmb','ProjectSettingsController@ajax_disconnect_gmb');
                    Route::get('/ajax_get_gmb_accounts','CreateProjectController@ajax_get_gmb_accounts');
                    Route::get('/ajax_gmb_accounts','CreateProjectController@ajax_gmb_accounts');


                    Route::get('/ajax_fetch_customer_view_graph','GMBController@ajax_fetch_customer_view_graph');
                    Route::get('/ajax_fetch_customer_action_graph','GMBController@ajax_fetch_customer_action_graph');

                    Route::get('/ajax_fetch_photo_views_graph','GMBController@ajax_fetch_photo_views_graph');
                    Route::get('/ajax_get_Customer_search','GMBController@ajax_get_Customer_search');
                    Route::get('/ajax_get_direction_requests','GMBController@ajax_get_direction_requests');
                    Route::get('/ajax_get_phone_calls','GMBController@ajax_get_phone_calls');

                    Route::get('/ajax_get_photo_quantity','GMBController@ajax_get_photo_quantity');
                    Route::get('/ajax_get_gmb_reviews','GMBController@ajax_get_gmb_reviews');

                    Route::get('/ajax_get_gmb_reviews_pagination','GMBController@ajax_get_gmb_reviews_pagination');
                    Route::get('/ajax_get_gmb_media','GMBController@ajax_get_gmb_media');

                    Route::post('/ajax_gmb_date_range','GMBController@ajax_gmb_date_range');
                    //gmb routes	

					/*shared access*/ 
                    Route::get('/shared-access','SharedAccessController@index');
					Route::post('/ajax_add_user_shared_access','SharedAccessController@ajax_add_user_shared_access');
					Route::get('/ajax_check_shared_email_exists','SharedAccessController@ajax_check_shared_email_exists');
					Route::post('/ajax_remove_shared_access','SharedAccessController@ajax_remove_shared_access');
					Route::get('/render_shared_user/{user_id}','SharedAccessController@render_shared_user');
					Route::get('/ajax_check_shared_email','SharedAccessController@ajax_check_shared_email');
					Route::post('/ajax_update_existing_shared_user','SharedAccessController@ajax_update_existing_shared_user');
					Route::get('/ajax_get_role_based_projects','SharedAccessController@ajax_get_role_based_projects');
					Route::get('/ajax_get_role_based_projects_existing_user','SharedAccessController@ajax_get_role_based_projects_existing_user');
					/*shared access*/ 


					/*refresh settings*/
					
					Route::get('/ajax_refresh_gmb_acccount_list','GMBController@ajax_refresh_gmb_acccount_list');
					Route::get('/ajax_fetch_last_updated','ProjectSettingsController@ajax_fetch_last_updated');
					Route::get('/ajax_fetch_adwords_accounts','CreateProjectController@ajax_fetch_adwords_accounts');
					Route::get('/ajax_refresh_ppc_acccount_list','GoogleController@ajax_refresh_ppc_acccount_list');
					Route::get('/ajax_refresh_analytics_acccount_list','CreateProjectController@ajax_refresh_analytics_acccount_list');
				
					Route::get('/ajax_refresh_search_console_urls','SearchConsoleController@ajax_refresh_search_console_urls'); 
					Route::get('/ajax_fetch_analytics_accounts','ProjectSettingsController@ajax_fetch_analytics_accounts');
					Route::get('/ajax_fetch_analytics_property','ProjectSettingsController@ajax_fetch_analytics_property');
					Route::get('/ajax_fetch_analytics_view','ProjectSettingsController@ajax_fetch_analytics_view');
					Route::get('/ajax_fetch_console_urls','ProjectSettingsController@ajax_fetch_console_urls');
					Route::get('/ajax_fetch_adwords_campaigns','ProjectSettingsController@ajax_fetch_adwords_campaigns');
					Route::get('/ajax_fetch_adwords_emails','ProjectSettingsController@ajax_fetch_adwords_emails');
					Route::get('/ajax_fetch_gmb_accounts','ProjectSettingsController@ajax_fetch_gmb_accounts');
					Route::get('/ajax_fetch_gmb_emails','ProjectSettingsController@ajax_fetch_gmb_emails');
					/*refresh settings*/

					/*Project tags*/
					Route::get('/ajax_get_project_tags','AuthController@ajax_get_project_tags');
					Route::post('/ajax_save_project_tags','AuthController@ajax_save_project_tags');
					Route::post('/ajax_delete_project_tag','AuthController@ajax_delete_project_tag');
					/*Project tags*/

					/*Alerts*/
					Route::get('/alerts','AlertsController@index');	
					Route::get('/ajax_fetch_alerts_list','AlertsController@ajax_fetch_alerts_list');	
					Route::get('/ajax_fetch_alerts_pagination','AlertsController@ajax_fetch_alerts_pagination');		
					Route::get('/all_alerts_content','AlertsController@all_alerts_content');
					Route::get('/positive_alerts_content','AlertsController@positive_alerts_content');
					Route::get('/negative_alerts_content','AlertsController@negative_alerts_content');
					Route::get('/ajax_fetch_positive_alerts_list','AlertsController@ajax_fetch_positive_alerts_list');	
					Route::get('/ajax_fetch_positive_alerts_pagination','AlertsController@ajax_fetch_positive_alerts_pagination');
					Route::get('/ajax_fetch_negative_alerts_list','AlertsController@ajax_fetch_negative_alerts_list');	
					Route::get('/ajax_fetch_negative_alerts_pagination','AlertsController@ajax_fetch_negative_alerts_pagination');
					Route::post('/ajax_save_alert_time','AlertsController@ajax_save_alert_time');
					/*Alerts*/
					
					Route::get('/ajax_get_latest_console_data','CampaignDetailController@ajax_get_latest_console_data');
					Route::get('/ajax_get_latest_organic_traffic_trend','CampaignDetailController@ajax_get_latest_organic_traffic_trend');
					Route::get('/ajax_get_latest_gmb','GMBController@ajax_get_latest_gmb');

					Route::get('/ajax_get_google_updated_time','CampaignDetailController@ajax_get_google_updated_time');
					Route::get('/ajax_check_goal_completion_count','AnalyticsController@ajax_check_goal_completion_count');
					Route::get('/ajax_get_analytics_daterange_data','AnalyticsController@ajax_get_analytics_daterange_data');

					Route::get('/ajax_get_summary_data','CampaignDetailController@ajax_get_summary_data');
					Route::get('/ajax_get_page_authority_stats','CampaignDetailController@ajax_get_page_authority_stats');
					Route::get('/ajax_get_domain_authority_stats','CampaignDetailController@ajax_get_domain_authority_stats');
					Route::get('/ajax_backlink_profile_list','BacklinkProfileController@ajax_backlink_profile_list');
					Route::get('/ajax_get_regional_database','LiveKeywordController@ajax_get_regional_database');
					Route::get('/ajax_get_languages','LiveKeywordController@ajax_get_languages');
					Route::get('/ajax_check_keyword_count','LiveKeywordController@ajax_check_keyword_count');
					Route::get('/campaign_seo_second/{campaign_id}','CampaignDetailController@campaign_seo_second');
					Route::get('/campaign_seo_third/{campaign_id}','CampaignDetailController@campaign_seo_third');
					Route::get('/ajax_analytics_range_data','CampaignDetailController@ajax_analytics_range_data');
					Route::get('/ajax_console_range_data','CampaignDetailController@ajax_console_range_data');
					Route::get('/ajax_get_package_subscription','AuthController@ajax_get_package_subscription');

					Route::get('/ajax_get_keyword_detail','AuthController@ajax_get_keyword_detail');
					Route::get('/ajax_get_domainType','LiveKeywordController@ajax_get_domainType');
					Route::get('/change_custom_note','CreateProjectController@change_custom_note');

					Route::get('/ajax_get_error_messages','CampaignDetailController@ajax_get_error_messages');
					Route::get('/ajax_goal_completion_organic_chart_overview','AnalyticsController@ajax_goal_completion_organic_chart_overview');
					Route::get('/ajax_get_goal_completion_stats_overview','AnalyticsController@ajax_get_goal_completion_stats_overview');

					/*Add Notes*/
					Route::post('/ajax_create_campaign_notes','CampaignNotesController@ajax_create_campaign_notes');
					Route::get('/ajax_get_campaign_notes','CampaignNotesController@ajax_get_campaign_notes');
					Route::get('/ajax_remove_campaign_note','CampaignNotesController@ajax_remove_campaign_note');
					Route::post('/ajax_save_keyword_table_settings','ProjectSettingsController@ajax_save_keyword_table_settings');
					Route::get('/ajax_get_table_settings','ProjectSettingsController@ajax_get_table_settings');
					/*Add Notes*/

					/*reports schedule*/
					Route::get('/schedule-report','ReportsController@reports_schedule');
					Route::get('/ajax_fetch_campaigns','ReportsController@ajax_fetch_campaigns');
					Route::post('/ajax_create_schedule_report','ReportsController@ajax_create_schedule_report');
					Route::get('/ajax_schedule_report_list','ReportsController@ajax_schedule_report_list');
					Route::get('/ajax_schedule_report_pagination','ReportsController@ajax_schedule_report_pagination');
					Route::post('/ajax_remove_scheduled_report','ReportsController@ajax_remove_scheduled_report');
					Route::post('/ajax_send_report_now','ReportsController@ajax_send_report_now');
					Route::get('/ajax_get_scheduled_report_history','ReportsController@ajax_get_scheduled_report_history');
					Route::get('/ajax_get_scheduled_report/{request_id}','ReportsController@ajax_get_scheduled_report'); 
					Route::post('/ajax_update_schedule_report','ReportsController@ajax_update_schedule_report');
					Route::post('/ajax_update_existing_reports','ReportsController@ajax_update_existing_reports');
					
					
					Route::get('/test_report','ReportsTestController@test_report');
					/*reports schedule*/

					// Route::get('/alerts_cron','ProjectSettingsController@alerts_cron');
                    /*new design routes*/  

                    Route::get('/check_dfs_extra_keywords','ExtraOrganicController@check_dfs_extra_keywords');

                    Route::get('/update_share_key','CreateProjectController@update_share_key');
					Route::get('/reset_share_key','CommonController@reset_share_key');

					/*keyword explorer */
					Route::get('/keyword-explorer','KeywordExplorerController@keyword_explorer');
					Route::get('/keyword-explorer-detail','KeywordExplorerController@keyword_explorer_detail');
					Route::get('/ajax_get_dfs_languages','KeywordExplorerController@ajax_get_dfs_languages');
					Route::get('/ajax_get_dfs_locations','KeywordExplorerController@ajax_get_dfs_locations');
					Route::get('/ajax_fetch_keyword_data','KeywordExplorerController@ajax_fetch_keyword_data');
					Route::get('/keyword_explorer_records/{layout}','KeywordExplorerController@keyword_explorer_records');
					Route::get('/get_keyword_response/{kw_search_id}/{row_id}','KeywordExplorerController@get_keyword_response');
					Route::get('/get_keyword_search_response/{kw_search_id}','KeywordExplorerController@get_keyword_search_response');
					Route::get('/get_trend_chart','KeywordExplorerController@get_trend_chart');
					Route::get('/ajax_export_keyword_ideas','KeywordExplorerController@ajax_export_keyword_ideas');
					Route::get('/ajax_get_keyword_ideas_data','KeywordExplorerController@ajax_get_keyword_ideas_data');
					Route::get('/ajax_fetch_user_history','KeywordExplorerController@ajax_fetch_user_history');
					Route::get('/ajax_clear_search_history','KeywordExplorerController@ajax_clear_search_history');
					Route::post('/ajax_create_keyword_list','KeywordExplorerController@ajax_create_keyword_list');
					Route::get('/ajax_fetch_keyword_list','KeywordExplorerController@ajax_fetch_keyword_list');
					Route::get('/ajax_remove_keyword_from_list','KeywordExplorerController@ajax_remove_keyword_from_list');
					Route::get('/ajax_fetch_lists','KeywordExplorerController@ajax_fetch_lists');
					Route::post('/ajax_update_list_name','KeywordExplorerController@ajax_update_list_name');
					Route::get('/ajax_export_keyword_list','KeywordExplorerController@ajax_export_keyword_list');
					Route::get('/ajax_delete_list','KeywordExplorerController@ajax_delete_list');
					Route::get('/ajax-get-listing','KeywordExplorerController@ajax_get_listing');
					Route::get('/ajax_get_refreshed_data','KeywordExplorerController@ajax_get_refreshed_data');
					Route::get('/ajax_fetch_keyword_data_multiple','KeywordExplorerController@ajax_fetch_keyword_data_multiple');
					Route::get('/get_keyword_response_multiple/{ids}','KeywordExplorerController@get_keyword_response_multiple');
					Route::get('/ajax_export_imported_keyword_ideas','KeywordExplorerController@ajax_export_imported_keyword_ideas');					
					/*keyword explorer */

					Route::get('/check_moz_data','MozController@check_moz_data');
					Route::get('/update_moz_data','MozController@update_moz_data');


					/*Testing routes*/
					Route::get('/check_dfs_extra_keywords','Test\ExtraOrganicController@check_dfs_extra_keywords');
					// Route::get('/ajax_check_okg','ExtraOrganicController@ajax_check_okg');

					Route::get('/stripe-download-excel/{subscription_id}', 'ProfileController@stripe_download_excel');
					Route::get('/stripe-download-invoice/{invoice_id}', 'ProfileController@stripe_download_invoice');
					Route::get('/ajax_check_invoice_status', 'ProfileController@ajax_check_invoice_status');
				});

				Route::get('/dashboard/{id}', 'AuthController@dashboard_new')->name('dashboard-new');
				Route::get('/dashboard/{id}/{type}', 'AuthController@dashboard_new')->name('dashboard-new');
				Route::get('/profile-settings/{id}', 'ProfileController@profile_settings');

				/*live keyword tracking postback url from data from seo (except csrf token)*/
				Route::post('/postbackAddKeyResponse', 'ProjectController@postbackAddKeyResponse');

				Route::get('/postback-siteaudit', 'SiteAuditController@postbackSiteAudit');
				
				Route::post('/fetching_updated_keywords', 'ProjectController@fetching_updated_keywords');

 				Route::get('/handle_ppc_console','CronPpcController@handle_ppc_console');
 				Route::get('/audit/sync','AuditSyncController@runAudit');

 				Route::get('/cron-send-now','CronReportsController@cron_send_now');

			});
	});
	Route::post('/ajax_renew_stripe_subsciption', 'Front\PaymentController@ajax_renew_stripe_subsciption');
	// Route::get('/check_backlinks_summary','Vendor\CreateProjectController@check_backlinks_summary');

	Route::get('/logout_session', 'Vendor\LoginController@logout_session');
	//view key routes start
	#SEO#

	Route::get('/ajax_get_summary_data','Vendor\CampaignDetailController@ajax_get_summary_data');
	Route::get('/ajax_get_page_authority_stats','Vendor\CampaignDetailController@ajax_get_page_authority_stats');
	Route::get('/ajax_goal_completion_organic_chart_overview','Vendor\AnalyticsController@ajax_goal_completion_organic_chart_overview');
	Route::get('/ajax_get_goal_completion_stats_overview','Vendor\AnalyticsController@ajax_get_goal_completion_stats_overview');
	Route::get('/ajax_get_domain_authority_stats','Vendor\CampaignDetailController@ajax_get_domain_authority_stats'); 
	Route::get('/ajax_get_analytics_daterange_data','Vendor\AnalyticsController@ajax_get_analytics_daterange_data');

	Route::get('/ajaxreferringdomains', 'Vendor\CampaignDetailController@ajax_referring_domains');	
	Route::get('/ajaxorganicKeywordRanking','Vendor\CampaignDetailController@ajaxorganicKeywordRanking');
	Route::get('/ajax_get_traffic_data','Vendor\AnalyticsController@ajax_get_traffic_data');
	Route::get('/ajax_organic_visitors','Vendor\CampaignDetailController@ajax_organic_visitors');
	Route::get('/ajaxgoogleAnalyticsGoal','Vendor\CampaignDetailController@ajaxgoogleAnalyticsGoal');
	Route::get('/ajax_get_search_console_queries','Vendor\SearchConsoleController@ajax_get_search_console_queries');
	Route::get('/ajax_get_search_console_pages','Vendor\SearchConsoleController@ajax_get_search_console_pages');
	Route::get('/ajax_get_search_console_country','Vendor\SearchConsoleController@ajax_get_search_console_country');
	Route::get('/ajax_get_search_console_devices','Vendor\SearchConsoleController@ajax_get_search_console_devices');
	Route::get('/ajax_get_search_console_graph','Vendor\SearchConsoleController@ajax_get_search_console_graph');
	Route::get('/ajax_get_traffic_metrics','Vendor\AnalyticsController@ajax_get_traffic_metrics');
	Route::get('/ajax_page_authority_chart','Vendor\CampaignDetailController@ajax_page_authority_chart');
	Route::get('/ajax_domain_authority_chart','Vendor\CampaignDetailController@ajax_domain_authority_chart');
	Route::get('/ajax_organic_keyword_chart','Vendor\CampaignDetailController@ajax_organic_keyword_chart');
	Route::get('/ajax_organic_visitors_chart','Vendor\CampaignDetailController@ajax_organic_visitors_chart');
	Route::get('/ajax_referring_domain_chart','Vendor\CampaignDetailController@ajax_referring_domain_chart');
	Route::get('/ajax_get_search_console_graph_date_range','Vendor\SearchConsoleController@ajax_get_search_console_graph_date_range');

	Route::get('/ajax_live_keyword_list','Vendor\LiveKeywordController@ajax_live_keyword_list');
	Route::get('/ajax_live_keyword_pagination','Vendor\Vendor\LiveKeywordController@ajax_live_keyword_pagination');
	Route::get('/ajax_extra_organic_bar_chart','Vendor\ExtraOrganicController@ajax_extra_organic_bar_chart');
	Route::get('/ajax_extra_organic_keywords','Vendor\ExtraOrganicController@ajax_extra_organic_keywords');
	Route::get('/ajax_live_keyword_pagination','Vendor\LiveKeywordController@ajax_live_keyword_pagination');
	Route::get('/ajax_extra_organic_chart_stats','Vendor\ExtraOrganicController@ajax_extra_organic_chart_stats');
	Route::get('/ajax_extra_organic_keywords_count','Vendor\ExtraOrganicController@ajax_extra_organic_keywords_count');

	Route::get('/ajax_fetch_backlink_data','Vendor\BacklinkProfileController@ajax_fetch_backlink_data');
	Route::get('/ajax_fetch_backlink_pagination','Vendor\BacklinkProfileController@ajax_fetch_backlink_pagination');
	Route::get('/ajax_backlink_chart_data','Vendor\BacklinkProfileController@ajax_backlink_chart_data');
	Route::get('/ajax_live_keyword_stats','Vendor\LiveKeywordController@ajax_live_keyword_stats');
	Route::get('/ajax_get_traffic_date_range','Vendor\AnalyticsController@ajax_get_traffic_date_range');
	Route::get('/ajax_get_traffic_date_range_metrics','Vendor\AnalyticsController@ajax_get_traffic_date_range_metrics');
	Route::get('/ajax_get_compare_traffic_data','Vendor\AnalyticsController@ajax_get_compare_traffic_data');

	Route::get('/ajax_get_goal_completion_chart_data','Vendor\AnalyticsController@ajax_get_goal_completion_chart_data');
	Route::get('/ajax_get_goal_completion_overview','Vendor\AnalyticsController@ajax_get_goal_completion_overview');

	Route::get('/ajax_get_goal_completion_location','Vendor\AnalyticsController@ajax_get_goal_completion_location');
	Route::get('/ajax_get_goal_completion_location_pagination','Vendor\AnalyticsController@ajax_get_goal_completion_location_pagination');

	Route::get('/ajax_get_goal_completion_sourcemedium','Vendor\AnalyticsController@ajax_get_goal_completion_sourcemedium');
	Route::get('/ajax_get_goal_completion_sourcemedium_pagination','Vendor\AnalyticsController@ajax_get_goal_completion_sourcemedium_pagination');
	Route::get('/ajax_goal_completion_all_users_chart','Vendor\AnalyticsController@ajax_goal_completion_all_users_chart');
	Route::get('/ajax_goal_value_all_users_chart','Vendor\AnalyticsController@ajax_goal_value_all_users_chart'); 
	Route::get('/ajax_goal_conversion_all_users_chart','Vendor\AnalyticsController@ajax_goal_conversion_all_users_chart'); 
	Route::get('/ajax_goal_abondon_all_users_chart','Vendor\AnalyticsController@ajax_goal_abondon_all_users_chart'); 
	Route::get('/ajax_goal_completion_organic_chart','Vendor\AnalyticsController@ajax_goal_completion_organic_chart'); 
	Route::get('/ajax_goal_value_organic_chart','Vendor\AnalyticsController@ajax_goal_value_organic_chart'); 
	Route::get('/ajax_conversion_rate_organic_chart','Vendor\AnalyticsController@ajax_conversion_rate_organic_chart'); 
	Route::get('/ajax_abondon_rate_organic_chart','Vendor\AnalyticsController@ajax_abondon_rate_organic_chart'); 

	Route::get('/campaign_seo_content/{campaign_id}','Vendor\CampaignDetailController@campaign_seo_content_view');
	Route::get('/campaign_seo_content_viewmore/{campaign_id}/{encrypted_id}','Vendor\CampaignDetailController@campaign_seo_content_viewmore');

	Route::get('/campaign_ppc_content/{campaign_id}','Vendor\CampaignDetailController@campaign_ppc_content_view');
	Route::get('/campaign_ppc_content_viewmore/{campaign_id}','Vendor\CampaignDetailController@campaign_ppc_content_viewmore');

	Route::get('/ajax_fetch_organic_keyword_data','Vendor\ExtraOrganicController@ajax_fetch_organic_keyword_data');
	Route::get('/ajax_fetch_keyword_pagination','Vendor\ExtraOrganicController@ajax_fetch_keyword_pagination');

	Route::post('/ajax_live_keyword_chart_data','Vendor\LiveKeywordController@ajax_live_keyword_chart_data');

	/*Route::get('/campaign_gmb_content/{campaign_id}','Vendor\CampaignDetailController@campaign_gmb_content');*/
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

	Route::get('/ajax_get_organic_keyword_growth_time','Vendor\ExtraOrganicController@ajax_get_organic_keyword_growth_time');

    /*gmb viewkey routes*/

    /*Activity list*/

	Route::get('/activities-layout/{campaign_id}','Vendor\TaskActivitiesController@index');
	Route::get('activities/{campaign_id}','Vendor\TaskActivitiesController@activities');
	Route::get('activities-details/{campaign_id}','Vendor\TaskActivitiesController@activity');
	Route::get('activity/process/{campaign_id}','Vendor\TaskActivitiesController@activityProcess');
	Route::get('ajax-activities','Vendor\TaskActivitiesController@ajaxActivities');
	Route::get('ajax-activitytotal','Vendor\TaskActivitiesController@ajaxActivityTotal');
	Route::get('activity/process/{campaign_id}','Vendor\TaskActivitiesController@activityProcess');


	/*E-commerce goal routes*/
	Route::get('/ajax_ecom_goal_completion_overview','Vendor\EcommerceGoalsController@ajax_ecom_goal_completion_overview');
	Route::get('/ajax_ecom_goal_completion_chart','Vendor\EcommerceGoalsController@ajax_ecom_goal_completion_chart');
	Route::get('/ajax_ecom_conversion_rate_users','Vendor\EcommerceGoalsController@ajax_ecom_conversion_rate_users');
	Route::get('/ajax_ecom_conversion_rate_organic','Vendor\EcommerceGoalsController@ajax_ecom_conversion_rate_organic');
	Route::get('/ajax_ecom_transaction_users','Vendor\EcommerceGoalsController@ajax_ecom_transaction_users');
	Route::get('/ajax_ecom_transaction_organic','Vendor\EcommerceGoalsController@ajax_ecom_transaction_organic');
	Route::get('/ajax_ecom_revenue_users','Vendor\EcommerceGoalsController@ajax_ecom_revenue_users');
	Route::get('/ajax_ecom_revenue_organic','Vendor\EcommerceGoalsController@ajax_ecom_revenue_organic');
	Route::get('/ajax_ecom_avg_orderValue_users','Vendor\EcommerceGoalsController@ajax_ecom_avg_orderValue_users');
	Route::get('/ajax_ecom_avg_orderValue_organic','Vendor\EcommerceGoalsController@ajax_ecom_avg_orderValue_organic');
	Route::get('/ajax_ecom_goal_completion_overview','Vendor\EcommerceGoalsController@ajax_ecom_goal_completion_overview');
	Route::get('/ajax_ecom_product','Vendor\EcommerceGoalsController@ajax_ecom_product');
	Route::get('/ajax_ecom_product_pagination','Vendor\EcommerceGoalsController@ajax_ecom_product_pagination');

    Route::get('/ajax_list_existing_tags','Vendor\LiveKeywordController@ajax_list_existing_tags');

	#PPC#
	Route::get('/summary_overview','Vendor\PpcController@summary_overview');
	Route::get('/ajax_fetch_ads_campaign_data','Vendor\PpcController@ajax_fetch_ads_campaign_data');
	Route::get('/ajax_fetch_ads_campaign_pagination','Vendor\PpcController@ajax_fetch_ads_campaign_pagination');
	Route::get('/ajax_fetch_ads_keywords_data','Vendor\PpcController@ajax_fetch_ads_keywords_data');
	Route::get('/ajax_fetch_ads_keywords_pagination','Vendor\PpcController@ajax_fetch_ads_keywords_pagination');
	Route::get('/ajax_fetch_adGroup_data','Vendor\PpcController@ajax_fetch_adGroup_data');
	Route::get('/ajax_fetch_adGroup_pagination','Vendor\PpcController@ajax_fetch_adGroup_pagination');
	Route::get('/ajax_fetch_adsPerformance_network_data','Vendor\PpcController@ajax_fetch_adsPerformance_network_data');
	Route::get('/ajax_fetch_adsPerformance_network_pagination','Vendor\PpcController@ajax_fetch_adsPerformance_network_pagination');
	Route::get('/ajax_fetch_adsPerformance_device_data','Vendor\PpcController@ajax_fetch_adsPerformance_device_data');
	Route::get('/ajax_fetch_adsPerformance_device_pagination','Vendor\PpcController@ajax_fetch_adsPerformance_device_pagination');
	Route::get('/ajax_fetch_adsPerformance_clickType_data','Vendor\PpcController@ajax_fetch_adsPerformance_clickType_data');
	Route::get('/ajax_fetch_adsPerformance_clickType_pagination','Vendor\PpcController@ajax_fetch_adsPerformance_clickType_pagination');
	Route::get('/ajax_fetch_adsPerformance_adSlot_data','Vendor\PpcController@ajax_fetch_adsPerformance_adSlot_data');
	Route::get('/ajax_fetch_adsPerformance_adSlot_pagination','Vendor\PpcController@ajax_fetch_adsPerformance_adSlot_pagination');
	Route::get('/ajax_fetch_ads_data','Vendor\PpcController@ajax_fetch_ads_data');
	Route::get('/ajax_fetch_ads_pagination','Vendor\PpcController@ajax_fetch_ads_pagination');
	Route::get('/ppc_summary_impressions_graph','Vendor\PpcController@ppc_summary_impressions_graph');
	Route::get('/ppc_summary_cost_graph','Vendor\PpcController@ppc_summary_cost_graph');
	Route::get('/ppc_summary_clicks_graph','Vendor\PpcController@ppc_summary_clicks_graph');
	Route::get('/ppc_summary_averageCpc_graph','Vendor\PpcController@ppc_summary_averageCpc_graph');
	Route::get('/ppc_summary_ctr_graph','Vendor\PpcController@ppc_summary_ctr_graph');
	Route::get('/ppc_summary_conversions_graph','Vendor\PpcController@ppc_summary_conversions_graph');
	Route::get('/ppc_summary_conversion_rate_graph','Vendor\PpcController@ppc_summary_conversion_rate_graph');
	Route::get('/ppc_summary_cpc_rate_graph','Vendor\PpcController@ppc_summary_cpc_rate_graph');
	Route::get('/ajax_fetch_date_range_chart','Vendor\PpcController@ajax_fetch_date_range_chart');
	Route::get('/ajax_fetch_summary_statistics','Vendor\PpcController@ajax_fetch_summary_statistics');
	Route::get('/summary_statistics','Vendor\PpcController@summary_statistics');
	Route::get('/ppc_date_range_data','Vendor\PpcController@ppc_date_range_data');

	Route::get('/ajax_fetch_ppc_chart','Vendor\PpcController@ajax_fetch_ppc_chart');
	Route::get('/ajax_fetch_ppc_summary_statistics','Vendor\PpcController@ajax_fetch_ppc_summary_statistics');
	Route::get('/ajax_compare_ppc_data','Vendor\PpcController@ajax_compare_ppc_data');
	Route::post('/ajax_get_keywords_time','Vendor\LiveKeywordController@ajax_get_keywords_time');




	Route::get('/check_api_balance','Vendor\DataForSeoController@check_api_balance');

	Route::get('/spyglass/{id}','Vendor\DataForSeoController@spyglass');
	Route::get('/spyglass','Vendor\DataForSeoController@spyglass');
	Route::get('/ajax-spyglass/{id}','Vendor\DataForSeoController@ajaxSpyglass');

	Route::get('/ajax_fetch_ads_campaign_data_pdf','Vendor\PpcController@ajax_fetch_ads_campaign_data_pdf');
	Route::get('/ajax_fetch_ads_keywords_data_pdf','Vendor\PpcController@ajax_fetch_ads_keywords_data_pdf');

	Route::group(["namespace" => "ViewKey"], function($account) {	
		Route::get('/project-detail','DashboardController@index');
		Route::get('/project-detail/{keys}','DashboardController@index');
		Route::get('/project-detail/sidebar/{keys}/{type}/{active}','DashboardController@sidebar');
		Route::get('/project-detail/tabs/{keys}/{type}','DashboardController@tabs');

		Route::get('/campaign_gmb_content/{campaign_id}','DashboardController@campaign_gmb_content');
		Route::get('/campaign_social_content/{campaign_id}','DashboardController@campaign_social_content');

		Route::get('/seo-visibility/{keys}','DashboardController@seoVisibility'); 
		Route::get('/seo-rankings/{keys}','DashboardController@seoRankings'); 
		Route::get('/seo-traffic/{keys}','DashboardController@seoTraffic'); 
		Route::get('/seo-backlinks/{keys}','DashboardController@seoBacklinks'); 
		Route::get('/seo-goals/{keys}','DashboardController@seoGoals'); 
		Route::get('/seo-activity/{keys}','DashboardController@seoActivity'); 
		Route::get('/seo-keyword-explorer/{keys}','DashboardController@seoKeywordExplorer'); 
		/*keyword research routes*/
		Route::get('/ajax_get_dfs_languages','KeywordExplorerController@ajax_get_dfs_languages');
		Route::get('/ajax_get_dfs_locations','KeywordExplorerController@ajax_get_dfs_locations');
		Route::get('/keyword_explorer_records/{layout}','KeywordExplorerController@keyword_explorer_records');
		Route::get('/ajax_fetch_keyword_ideas_data','KeywordExplorerController@ajax_fetch_keyword_ideas_data');
		Route::get('/get_keyword_response/{kw_search_id}/{row_id}','KeywordExplorerController@get_keyword_response');
		Route::get('/get_keyword_search_response/{kw_search_id}','KeywordExplorerController@get_keyword_search_response');
		Route::get('/get_trend_chart','KeywordExplorerController@get_trend_chart');
		Route::get('/ajax_export_keyword_ideas','KeywordExplorerController@ajax_export_keyword_ideas');
		Route::get('/ajax_get_keyword_ideas_data','KeywordExplorerController@ajax_get_keyword_ideas_data');
		Route::get('/ajax_fetch_user_history','KeywordExplorerController@ajax_fetch_user_history');
		Route::get('/ajax_clear_search_history','KeywordExplorerController@ajax_clear_search_history');
		Route::post('/ajax_create_keyword_list','KeywordExplorerController@ajax_create_keyword_list');
		Route::get('/ajax_fetch_keyword_list','KeywordExplorerController@ajax_fetch_keyword_list');
		Route::get('/ajax_remove_keyword_from_list','KeywordExplorerController@ajax_remove_keyword_from_list');
		Route::get('/ajax_fetch_lists','KeywordExplorerController@ajax_fetch_lists');
		Route::post('/ajax_update_list_name','KeywordExplorerController@ajax_update_list_name');
		Route::get('/ajax_export_keyword_list','KeywordExplorerController@ajax_export_keyword_list');
		Route::get('/ajax_delete_list','KeywordExplorerController@ajax_delete_list');
		Route::get('/ajax-get-listing','KeywordExplorerController@ajax_get_listing');
		Route::get('/ajax_get_refreshed_data','KeywordExplorerController@ajax_get_refreshed_data');
		/*keyword research routes*/
		/* Site Audit Views */

		Route::get('/audit-overview/{keys}','DashboardController@seoAudit'); 
		Route::get('/audit-pages/{keys}','DashboardController@seoAuditPages'); 
		Route::get('/audit-details/{keys}/{page}','DashboardController@seoAuditDetails'); 

		/* Site Audit Views */

		Route::get('/ppc-campaign/{keys}','DashboardController@ppcCampaign'); 
		Route::get('/ppc-keywords/{keys}','DashboardController@ppcKeywords'); 
		Route::get('/ppc-ads/{keys}','DashboardController@ppcAds'); 
		Route::get('/ppc-performance/{keys}','DashboardController@ppcPerformance'); 

		/* extra organic keywords*/
		Route::get('/extra-organic-keywords/{keys}','ExtraOrganicController@extra_organic_keywords');
		Route::get('/ajax_fetch_organic_keyword_data','ExtraOrganicController@ajax_fetch_organic_keyword_data');
		Route::get('/ajax_fetch_keyword_pagination','ExtraOrganicController@ajax_fetch_keyword_pagination');

		/*  pdf route */

		Route::get('/download/seo/{keys}','PdfsController@index');
		Route::get('/download/livekeyword/{keys}','PdfsController@livekeyword');
		Route::get('/download/ppc/{keys}','PdfsController@ppcindex');
		Route::get('/download/pdf/{keys}/{type}','PdfsController@crowdpdf');
		Route::get('/download/gmb/{keys}','PdfsController@gmbindex');

		Route::get('/download/site-audit/{keys}','PdfsController@site_audit_index');
		Route::get('/download/site-audit-detail/{keys}/{page}','PdfsController@site_audit_detail');

	});


	/* Site Audit Ajax View*/
	Route::get('/ajax-auditstatus/{campaignId}','Vendor\SiteAuditController@ajaxAuditTashStatus');
	Route::get('/ajax-auditsummary/{campaignId}','Vendor\SiteAuditController@ajaxAuditSummaryView');

	Route::post('/ajax-auditpageserrors','Vendor\SiteAuditController@ajaxAuditpagesErrors');

	Route::get('/ajax-auditpages-label/{campaignId}','Vendor\SiteAuditController@ajaxAuditpagesLabel');
	Route::get('/ajax-auditpages/{campaignId}','Vendor\SiteAuditController@ajaxAuditpagesView');
	Route::get('/ajax-auditpagesidebar/{campaignId}','Vendor\SiteAuditController@ajaxAuditPageSidebarView');
	Route::post('/ajax-auditpageserrors','Vendor\SiteAuditController@ajaxAuditpagesErrors');
	Route::post('/ajax-duplicatetages','Vendor\SiteAuditController@ajaxDuplicateTages');
	Route::post('/ajax-viewsourcehtml','Vendor\SiteAuditController@ajaxViewSourceHtml');
	Route::post('/ajax-linktypes','Vendor\SiteAuditController@ajaxLinkTypes');


	Route::get('/ajax-auditdetail-rightside/{campaignId}','Vendor\SiteAuditController@ajaxAuditDetailRightside');
	Route::get('/ajax-auditdetail-overview/{campaignId}','Vendor\SiteAuditController@ajaxAuditDetailOverview');
	Route::get('/ajax-auditdetail-issues/{campaignId}','Vendor\SiteAuditController@ajaxAuditDetailIssues');
	Route::get('/ajax-auditdetail-links/{campaignId}','Vendor\SiteAuditController@ajaxAuditDetailLinks');
	Route::post('/ajax-dinsights/{campaignId}','Vendor\SiteAuditController@ajaxDesktopInsights');
	Route::post('/ajax-minsights/{campaignId}','Vendor\SiteAuditController@ajaxMobileInsights');


	Route::get('/ajax_get_goal_completion_chart_data_viewkey','Vendor\AnalyticsController@ajax_get_goal_completion_chart_data_viewkey');
	Route::get('/ajax_get_goal_completion_overview_viewkey','Vendor\AnalyticsController@ajax_get_goal_completion_overview_viewkey');
	Route::get('/ajax_goal_completion_location_vk','Vendor\AnalyticsController@ajax_goal_completion_location_vk');
	Route::get('/ajax_goal_completion_location_pagination_vk','Vendor\AnalyticsController@ajax_goal_completion_location_pagination_vk');
	Route::get('/ajax_goal_completion_sourcemedium_vk','Vendor\AnalyticsController@ajax_goal_completion_sourcemedium_vk');
	Route::get('/ajax_goal_completion_sourcemedium_pagination_vk','Vendor\AnalyticsController@ajax_goal_completion_sourcemedium_pagination_vk');

	Route::get('/ajax_ecom_goal_completion_chart_viewkey','Vendor\EcommerceGoalsController@ajax_ecom_goal_completion_chart_viewkey');
	Route::get('/ajax_ecom_goal_completion_overview_viewkey','Vendor\EcommerceGoalsController@ajax_ecom_goal_completion_overview_viewkey');
	Route::get('/ajax_ecom_product_viewkey','Vendor\EcommerceGoalsController@ajax_ecom_product_viewkey');
	Route::get('/ajax_ecom_product_pagination_viewkey','Vendor\EcommerceGoalsController@ajax_ecom_product_pagination_viewkey');


	Route::get('/ajax_get_google_updated_time','Vendor\CampaignDetailController@ajax_get_google_updated_time');
	Route::get('/ajax_get_backlinkProfile_time','Vendor\BacklinkProfileController@ajax_get_backlinkProfile_time');

	//view key routes end


	Route::get('/login', 'Vendor\LoginController@showLogin')->name('front.login-new');
	Route::post('/doLoginNew', 'Vendor\LoginController@doLoginNew')->name('front.login-new');

	Route::get('/connect_google_ads','Vendor\GoogleController@connectGoogleAds') ;
	Route::get('/connect_google_analytics','Vendor\CampaignSettingsController@connectGoogleAnalytics') ;
	Route::get('/cron_live_keyword_tracking','Vendor\CronController@cron_live_keyword_tracking');
	Route::post('/cron_postbackAddKeyResponse', 'Vendor\CronController@cron_postbackAddKeyResponse');
	//	Route::get('/test', 'Vendor\CronController@test');
	Route::get('/connect_gmb','Vendor\GMBController@connect_gmb');
	Route::get('/stripe_webhooks','Vendor\WebhookController@stripe_webhooks');
	Route::post('/stripe_postback_webhooks','Vendor\WebhookController@stripe_postback_webhooks');

	Route::get('/cron_graph','Vendor\AnalyticsController@cron_graph');
	Route::get('/search_console_graph','Vendor\SearchConsoleController@search_console_graph');
	Route::get('/search_console_cron','Vendor\SearchConsoleController@search_console_cron');

	Route::get('/connect_search_console','Vendor\TestController@connect_search_console');

	Route::get('/get_ecommerce_goals','Vendor\AnalyticsController@get_ecommerce_goals');


	Route::group(["namespace" => "Front"], function () {
		Route::get('/register/', 'AuthController@register')->name('register');
		Route::post('/register/', 'AuthController@register')->name('register');
		Route::post('/doRegister', 'AuthController@doRegister');
		Route::get('/ajax_pricing_action', 'AuthController@ajax_pricing_action');
		Route::get('/ajax_user_pricing_action', 'AuthController@ajax_user_pricing_action');
		Route::get('/ajax_check_pricing_downgrade', 'AuthController@ajax_check_pricing_downgrade');
		Route::get('/ajax_continue_downgrade', 'AuthController@ajax_continue_downgrade');
		Route::get('/ajax_take_to_profile', 'AuthController@ajax_take_to_profile');

		Route::get('/check_email_exists', 'AuthController@check_email_exists');
		Route::get('/check_company_name_exists', 'AuthController@check_company_name_exists');
		Route::get('/check_company_exists', 'AuthController@check_company_exists');
		Route::get('/check_coupon_code', 'AuthController@check_coupon_code');


		Route::get('subscription','PaymentController@stripe_new');
		Route::post('stripe-post', 'PaymentController@stripePost');

		Route::get('/thankyou', 'PaymentController@thankyou')->name('thankyou');

		Route::get('/privacy-policy','PagesController@privacy_policy');
		Route::get('/terms-conditions','PagesController@terms_conditions');

		Route::get('/integrations','IntegrationController@index')->name('front.integrations');
		Route::get('/seo-report','ReportController@index')->name('front.seo-report');
		//Route::get('/price','PackageController@index')->name('front.pricing');

		Route::redirect('/price', '/pricing');
		Route::get('/pricing','PackageController@index')->name('front.pricing');

		Route::get('/confirmation/{token}','VerifyController@confirmation');
		//forgot password
		Route::get('/recover-password','ForgotPasswordController@index');
		Route::post('/post_recover_password','ForgotPasswordController@post_recover_password');
		Route::get('/reset-password/{token}','ForgotPasswordController@reset_password');
		Route::post('/update_recover_password','ForgotPasswordController@update_recover_password');
		Route::get('/rank-tracker','ReportController@rank_tracker')->name('front.rank-tracker');

		Route::post('/create_free_forever_subscription','PaymentController@create_free_forever_subscription');
	});



	/* Admin routes */
	Route::group(["namespace" => "Admin"], function() {
		Route::group(['middleware' => ['Admin'], 'prefix' => 'admin'], function () {
			Route::get('/dashboard', 'AuthController@dashboard');
			Route::get('/logout', function() {
				Session::flush();
				Auth::logout();
				return Redirect::to("/login");
			});
			Route::get('/ajax_fetch_account_data','DashboardController@ajax_fetch_account_data');
			Route::get('/ajax_fetch_account_pagination','DashboardController@ajax_fetch_account_pagination');
			Route::get('/login_as_client','DashboardController@login_as_client');
			Route::get('/site-audit/create','SiteAuditController@create');
			Route::post('/site-audit/store','SiteAuditController@store');
			Route::get('/site-audit','SiteAuditController@index');
			Route::get('/ajax_fetch_audit_list_data','SiteAuditController@ajax_fetch_audit_list_data');
			Route::get('/ajax_fetch_audit_list_pagination','SiteAuditController@ajax_fetch_audit_list_pagination');
			Route::get('/site-audit/edit/{id}','SiteAuditController@edit');
			Route::post('/site-audit/update/{id}','SiteAuditController@update');
			Route::get('/site-audit/destroy/{id}','SiteAuditController@destroy');
			Route::get('/profile-settings','AuthController@get_profile');
			Route::post('/post_profile_settings','AuthController@post_profile_settings');
			Route::post('/ajax_remove_profile_picture','AuthController@ajax_remove_profile_picture');
			Route::post('/update_change_password','AuthController@update_change_password');
			Route::get('/agency-account-details/{agency_id}','AgencyAccountController@agency_account_details');
			Route::get('/ajax_fetch_agency_account_data','AgencyAccountController@ajax_fetch_agency_account_data');
			Route::get('/ajax_fetch_agency_account_pagination','AgencyAccountController@ajax_fetch_agency_account_pagination');
			/*feedback section*/
			Route::get('/feedbacks','FeedbackController@index');
			Route::get('/ajax_fetch_feedback_data','FeedbackController@ajax_fetch_feedback_data');
			Route::get('/ajax_fetch_feedback_pagination','FeedbackController@ajax_fetch_feedback_pagination');
			Route::get('/ajax_fetch_client_feedback/{feedback_id}','FeedbackController@ajax_fetch_client_feedback');
		});
	});
	Route::get('/admin/dashboard/{id}', 'Admin\AuthController@dashboard')->name('dashboard');
	Route::get('/back_to_admin','Vendor\LoginController@back_to_admin');
	/* Admin routes */

	Route::get('generate-pdf', 'PDFController@generatePDF', 'generatePDF');


	Route::get('/clearcache', function() {
		Artisan::call('cache:clear');
		Artisan::call('config:clear');
		Artisan::call('config:cache');
		Artisan::call('view:clear');
		Artisan::call('route:clear');
		return 'Cache cleared';
	// return what you want
	});

	Route::get('/project-details/{keys}','ViewKey\DashboardController@index');
	Route::get('/', function () {
		return view('index');
	});

