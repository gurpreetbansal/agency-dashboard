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

				/********** Page Summary ************/

				Route::get('/sa/audit','SiteAuditReportsController@saAudit');
				Route::post('/site/audit/run','SiteAuditReportsController@siteAuditRun');

				Route::post('/site/audit/crowler','SiteAuditReportsController@auditCrowler');
				
				Route::post('/audit/domain/validation','SiteAuditReportsController@checkDomainValid');

				Route::get('/audit/detail/{id}','SiteAuditReportsController@siteAuditDetail');

				Route::post('/audit/overview','SiteAuditReportsController@siteAuditOverView');

				Route::get('/audit/summary/update/{id}','SiteAuditReportsController@siteAuditSummaryUpdate');
				Route::get('/audit/summary/{id}','SiteAuditReportsController@siteAuditSummary');
				Route::get('/audit/list/{id}','SiteAuditReportsController@siteAuditList');

				Route::POST('/audit/update/{id}','SiteAuditReportsController@updateAuditRefresh');

				Route::get('/audit/expire','SiteAuditReportsController@auditExpire');

				/********** Page Detail ************/

				Route::get('/audit/detail/update/{id}','SiteAuditReportsController@updateAuditPageDetail');

				Route::get('/audit/page/detail/{id}','SiteAuditReportsController@auditPageDetail');
				Route::get('/audit/page/detail-overview/{id}','SiteAuditReportsController@auditPageDetailOverview');
				Route::get('/audit/page/detail-summary/{id}','SiteAuditReportsController@auditPageDetailSummary');
				Route::get('/audit/page/detail-data/{id}','SiteAuditReportsController@auditPageDetailData');

				/********** Audit Loaders ************/

				Route::get('/audit/loader/overview','SiteAuditReportsController@auditLoaderView');
				Route::get('/audit/loader/page-detail/{id}','SiteAuditReportsController@auditLoaderDetails');


				Route::get('/site/summary/loader','SiteAuditReportsController@siteSummaryLoader');
				Route::get('/site/lists/loader','SiteAuditReportsController@siteListsLoader');

				Route::get('/pages/overview/loader','SiteAuditReportsController@pagesOverviewLoader');
				Route::get('/pages/summary/loader','SiteAuditReportsController@pagesSummaryLoader');
				Route::get('/pages/detail/loader','SiteAuditReportsController@pagesDetailLoader');

				/********** Audit Designs ************/
				Route::get('/audit/design-overview','SiteAuditReportsController@auditOverview');
				Route::get('/audit/design-detail','SiteAuditReportsController@auditDetal');

				Route::get('/audit/pdf/design-overview','SiteAuditReportsController@auditPdfOverview');
				Route::get('/audit/pdf/design-detail','SiteAuditReportsController@auditPdfDetail');

				Route::get('/campaign/audit/{id}','SiteAuditReportsController@auditCampaignOverview');
				
			});

			/********** Audit PDF ************/

			Route::get('/pdf/audit/summary/{id}','SiteAuditReportsController@pdfAuditSummary');
			Route::get('/pdf/audit/details/{id}','SiteAuditReportsController@pdfAuditDetails');

		});
	});

	/********** Audit Share Key ************/
	Route::get('/campaign/audit/{id}','Vendor\SiteAuditReportsController@auditCampaignOverview');
	
	Route::get('/audit-share/{id}','Vendor\SiteAuditReportsController@auditShare');

	Route::get('/audit/summary/{id}','Vendor\SiteAuditReportsController@siteAuditSummary');
	Route::get('/audit/list/{id}','Vendor\SiteAuditReportsController@siteAuditList');

	Route::get('/audit/loader/overview','Vendor\SiteAuditReportsController@auditLoaderView');
	Route::get('/audit/loader/page-detail/{id}','Vendor\SiteAuditReportsController@auditLoaderDetails');

	/*Route::post('/audit/domain/validation','Vendor\SiteAuditReportsController@checkDomainValid');
	Route::post('/site/audit/run','Vendor\SiteAuditReportsController@siteAuditRun');
	Route::post('/audit/overview','Vendor\SiteAuditReportsController@siteAuditOverView');*/

	Route::get('/audit/summary/update/{id}','Vendor\SiteAuditReportsController@siteAuditSummaryUpdate');

	Route::get('/audit/page/detail-overview/{id}','Vendor\SiteAuditReportsController@auditPageDetailOverview');
	Route::get('/audit/page/detail-summary/{id}','Vendor\SiteAuditReportsController@auditPageDetailSummary');
	Route::get('/audit/page/detail-data/{id}','Vendor\SiteAuditReportsController@auditPageDetailData');

	Route::get('/audit/page/detail/{auditId}/{pageId}','Vendor\SiteAuditReportsController@auditPageDetailView');

	/********** Audit PDF ************/

	Route::get('/pdf/audit/summary/{id}','Vendor\SiteAuditReportsController@pdfAuditSummary');
	Route::get('/pdf/audit/details/{id}','Vendor\SiteAuditReportsController@pdfAuditDetails');

	/********** Audit Cron Demo ************/

	Route::get('/cron/logs','Vendor\SiteAuditReportsController@cronLogs');

	
	