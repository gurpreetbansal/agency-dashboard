@extends('layouts.vendor_internal_pages')
@section('content')
<input type="hidden" name="user_id" class="user_id" value="{{@$user_id}}">
<div class="create-project-container">
	<div class="create-project-head">
		<div class="loader h-54"></div>
		<h3>Create project <small id="domain_url_value">(example.com)</small></h3>
	</div>

	<div class="loader h-300-table"></div>

	<div class="create-project-tabs">
		<ul>
			<li class="active complete" id="add-new-step1">
				<img src="{{URL::asset('public/vendor/internal-pages/images/project-info-icon.png')}}">
				Project Info
				<span><i class="fa fa-check"></i></span>
			</li>
			<li id="add-new-step2">
				<img src="{{URL::asset('public/vendor/internal-pages/images/integrations-icon.png')}}">
				Integrations
				<span><i class="fa fa-check"></i></span>
			</li>
			<li id="add-new-step3">
				<img src="{{URL::asset('public/vendor/internal-pages/images/rank-tracker-settings-icon.png')}}">
				Rank Tracker Settings
				<span><i class="fa fa-check"></i></span>
			</li>
		</ul>
	</div>
	<div class="hiddenOnLoad">

		<div class="create-project-tab-content " id="project-info">
			<div class="create-project-box">
				<h4>Welcome, <small>It only takes a few seconds to add your project</small></h4>
			</div>
			<form id="create-project-info">
				<input type="hidden" name="existed_id" class="existed_id">
				<div class="create-project-white-box d-flex">
					<div class="elem-left">

						<div class="form-group">
							<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/project-name-icon.png')}}"></span>
							<input type="text" class="form-control project_name" placeholder="Project Name" name="project_name" autocomplete="off" value="{{@$get_data->domain_name}}">
							<span class="errorStyle"><p id="project_name_error"></p></span>				
						</div>
						<div class="form-group">
							<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/project-website-icon.png')}}"></span>
							<input type="text" class="form-control domain_url has-domain-dropDownBox" placeholder="example.com" name="domain_url" autocomplete="off" value="{{@$get_data->domain_url}}" id="check_domain_url">
							<span class="errorStyle"><p id="domain_url_error"></p></span>	
							<img src="{{URL::asset('public/vendor/internal-pages/images/refresh-add-icon.gif')}}"  class="refresh-icon" style="display: none;">
							<span uk-icon="check" class="add-new-check green" style="display: none;"></span>
							<span uk-icon="close" class="add-new-cross red" style="display: none;"></span>
							<div class="domain-dropDownBox">
					            <input type="hidden" name="addNew_url_type" value="*.domain.com/*" id="addNew_url_type_input">
					            <button type="button"  class="addNew_url_type" name="addNew_url_type">*.domain.com/*</button>
					            <div class="domain-dropDownMenu"  id="addNew-url-dropDownMenu">
						            <ul class="addNew-url-type-ul">
					                    <li class="addNew-url-type-list active"><h6>*.domain.com/*</h6>All subdomains and all pages</li>
					                    <li class="addNew-url-type-list"><h6>URL</h6>Exact URL</li>
					                </ul>
					            </div>
				            </div>
						</div>
						<div class="form-group">
							<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/project-egn-icon.png')}}"></span>
							<select class="form-control regional_db selectpicker" name="regional_db"  data-live-search="true" id="regional_db">
								@if(isset($regional_db) && !empty($regional_db))
								@foreach($regional_db as $db)
								<option value="{{$db->short_name}}" {{$db->short_name=='us'?'selected':''}}>{{$db->short_name .' ('.$db->long_name.')'}}</option>
								@endforeach
								@endif
							</select>
							<span class="errorStyle"><p id="regional_db_error"></p></span>	
						</div>

						<div class="form-group">
							<label>Select your Dashboards</label>
							<div class="checkbox-group">
								<?php 
								$types = array();
								if(isset($get_data->dashboard_type)){
									$types[] = explode(',',$get_data->dashboard_type);
								}
								?>
								@if(isset($dashboardTypes) && !empty($dashboardTypes))
								@foreach($dashboardTypes as $dashboard)
								<label>
									<input name="dashboardType[{{$dashboard->id}}]" type="checkbox" class="custom-control-input btn btn-primary dashboardType" id="customSwitches{{$dashboard->id}}" value="{{$dashboard->id}}" <?php if(in_array($dashboard->id, $types)){ echo "checked"; }?>>
									<span class="custom-checkbox"></span>
									{{$dashboard->name}}
								</label>
								@endforeach
								@endif

								<span class="errorStyle"><p id="dashboardType_error"></p></span>
							</div>
						</div>

					</div>

					<div class="elem-right form-notes">
						<ul>
							<li>Name of project.</li>
							<li>Domain of project.</li>
							<li>Select version of Google you want to track results in. By default it’s Google.com</li>
						</ul>
					</div>

				</div>

				<div class="create-project-box d-flex">
					<div class="elem-left">
						<!-- <input type="reset" class="btn btn-border" value="Cancel"> -->
					</div>
					<div class="elem-right text-right">
						<input type="button"  class="btn blue-btn" value="Continue" id="submit_project_info">
					</div>
				</div>
			</form>
		</div>

		<div class="create-project-tab-content " id="integrations" style="display: none;">
			<div class="create-project-box">
				<h4>Integrations, <small>Connect your Google Analytics and Google Search Console accounts.</small>
				</h4>
			</div>
			<form id="create-integrations">
				<input type="hidden" class="last-project-id" value="{{@$campaign_id}}">
				<div id="new-integration-section">
					<div class="create-project-white-box integration-list" id="integration-list">
						<article id="addProject-search-console">
							<figure>
								<img src="{{URL::asset('public/vendor/internal-pages/images/google-search-console-img.png')}}">
							</figure>
							<div class="console_default">
								<p>To get insights about SERP, keywords and build reports for your SEO dashboard.</p>
								<a href="javascript:;" class="btn btn-border blue-btn-border" data-pd-popup-open="connectIntepopupSearchConsole" id="ConsoleBtnId">Connect</a>
							</div>


							<div class="console_connected" style="display: none;">
								<div class="connected-content">
									<ul>
										<li><big id="console_connected_email"></big> Connected Email</li>
										<li><big id="console_account"></big> Account</li>
									</ul>
								</div>
								<a href="javascript:;" class="edit-btn" data-pd-popup-open="connectIntepopupSearchConsole" id="ConsoleBtnId"><i class="fa fa-edit"></i></a>
								<a href="javascript:;" class="btn gray-btn" id="disconnectNewConsole">Disconnect</a>
							</div>
						</article>

						<article id="addProject-analytics">
							<figure>
								<img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-img.png')}}">
							</figure>
							<div class="default_analytics">
								<p>To get insights about your website traffic and build reports for your SEO dashboard.
								</p>
								<a href="javascript:;" class="btn btn-border blue-btn-border " data-pd-popup-open="connectIntepopup" id="AnalyticsBtnId">Connect</a>
							</div>

							<div class="analytics_connected" style="display: none;">
								<div class="connected-content">
									<ul>
										<li><big id="analytics_connected_email"></big> Connected Email</li>
										<li><big id="analytics_account"></big> Account</li>
										<li><big id="analyticsproperty"></big> Property</li>
										<li><big id="analyticsprofile"></big> Profile</li>
									</ul>
								</div>
								<a href="javascript:;" class="edit-btn" data-pd-popup-open="connectIntepopup" id="AnalyticsBtnId"><i class="fa fa-edit"></i></a>
								<a href="javascript:;" class="btn gray-btn" id="disconnectNewAnalytics">Disconnect</a>
							</div>

						</article>
						<article id="addProject-adwords">
							<figure>
								<img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-img.png')}}">
							</figure>
							<div class="default_adwords">
								<p>To get insights about your website traffic and build reports for your PPC dashboard.
								</p>
								<a href="javascript:;" class="btn btn-border blue-btn-border " data-pd-popup-open="connectIntepopupAdwords" id="AdwordsBtnId">Connect</a>
							</div>

							<div class="adword_connected" style="display: none;">
								<div class="connected-content">
									<ul>
										<li><big id="adwords_connected_email"></big> Connected Email</li>
										<li><big id="adwords_account"></big> Account</li>
									</ul>
								</div>
								<a href="javascript:;" class="edit-btn" data-pd-popup-open="connectIntepopupAdwords" id="AdwordsBtnId"><i class="fa fa-edit"></i></a>
								<a href="javascript:;" class="btn gray-btn" id="disconnectNewAdwords">Disconnect</a>
							</div>

						</article>

						<article id="addProject-gmb">
							<figure>
								<img src="{{URL::asset('public/vendor/internal-pages/images/gmb-img.png')}}">
							</figure>
							<div class="default_gmb">
								<p>To get insights about your website traffic and build reports for your GMB dashboard.
								</p>
								<a href="javascript:;" class="btn btn-border blue-btn-border " data-pd-popup-open="projectSettingGmbPopup" id="GmbBtnId">Connect</a>
							</div>

							<div class="gmb_connected" style="display: none;">
								<div class="connected-content">
									<ul>
										<!-- <li><big id="gmb_connected_email"></big> Connected Email</li> -->
										<li><big id="gmb_account"></big> Account</li>
									</ul>
								</div>
								<a href="javascript:;" class="edit-btn" data-pd-popup-open="projectSettingGmbPopup" id="gmbBtnId"><i class="fa fa-edit"></i></a> 
								<a href="javascript:;" class="btn gray-btn" id="disconnectGMB">Disconnect</a>
							</div>

						</article>

					</div>
				</div>

				<div class="create-project-box d-flex">
					<div class="elem-left">
						<a data-pd-popup-open="exitAndDeleteProject"><input class="btn btn-border" value="Cancel" id="cancel_project"></a>
					</div>
					<div class="elem-right text-right">
						<input type="button" class="btn btn-border" value="Previous" id="previous_integrations">
						<div class="next-btn btn blue-btn"><input type="button" class="" value="" id="store_integrations">Next <span> / Skip</span></div>
					</div>
				</div>
			</form>
		</div>

		<div class="create-project-tab-content " id="rank-tracking-settings" style="display: none;">
			<div class="create-project-box">
				<h4>Rank Tracker Settings, <small>Set up your keywords ranking tracking.</small></h4>
			</div>
			<form id="create-rank-tracking-settings">
				@csrf
				<input type="hidden" class="last-project-id-settings" name="project_id" value="{{@$campaign_id}}">
				<div class="create-project-white-box d-flex">
					<div class="elem-left">

						<div class="form-group">
							<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}"></span>
							<select name="add_project_search_engine" class="select form-control add_project_search_engine"  id="add_project_search_engine" >
								<option value="">-Select-</option>
								<?php
								if(!empty($regional_db) && isset($regional_db) && count($regional_db)>0){
									foreach($regional_db as $region){?>
										<option value="{{$region->long_name}}" {{$region->short_name=='us'?'selected':''}}>{{$region->short_name .' ('.$region->long_name.') '}}</option>
										<?php
									}
								}
								?>
							</select>
						</div>

						<div class="form-group dropdown">
							<input name="add_project_locations" id="add_project_locations" type="text" class="form-control address_location" placeholder="Location"  />
							<span class="errorStyle"><p id="address_location_error"></p></span>	
							<input id="add_project_lat" type="hidden" name="latitude">
							<input id="add_project_long" type="hidden" name="longitude">
						</div>


						<div class="form-group">
							<div class="radio-group">
								<label>
									<input type="radio" name="device" class="add_project_device" value="desktop">
									<span class="custom-radio"></span>
									Desktop
								</label>

								<label>
									<input type="radio" name="device" class="add_project_device" value="mobile">
									<span class="custom-radio"></span>
									Mobile
								</label>
							</div>
						</div>

						<div class="form-group dropdown">
							<select name="add_project_language" class="select form-control add_project_language selectpicker" data-live-search="true">
								<option value="">-Select-</option>
								@if(isset($language) && !empty($language))
								@foreach($language as $key=>$value)
								<option value="{{$value->name}}" {{$value->name=='English'?'selected':''}}>{{$value->name}}</option> 
								@endforeach
								@endif
							</select>
						</div>

					</div>

					<div class="elem-right form-notes">
						<ul>
							<li>Select version of Google you want to track results in. By default it’s Google.com</li>
							<li>Select location/region from where you want to track your rankings.</li>
						</ul>
					</div>

				</div>

				<div class="create-project-box d-flex">
					<div class="elem-left">
						<a data-pd-popup-open="exitAndDeleteProject"><input class="btn btn-border" value="Cancel" id="cancel_project"></a>
					</div>
					<div class="elem-right text-right">
						<input type="button" class="btn btn-border" value="Previous" id="previous_stores">
						<input type="submit" class="btn blue-btn" value="Finish" id="store_rank_tracking_settings">
					</div>
				</div>
			</form>
		</div>
	</div>

</div>

@endsection