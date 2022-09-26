<div class="main-data">
	@if($dashboardStatus == false)
	<div class="white-box mb-40 " id="gmb_add">
		<!-- <div class="loader h-33 "></div> -->
		<div class="integration-list" >
			<article>
				<figure>
					<img src="{{URL::asset('public/vendor/internal-pages/images/gmb-img.png')}}">
				</figure>
				<div>
					@if(Auth::user()->role_id ==4)
						<p>The Source is not active on your account.</p>
						<?php
							if(isset($profile_data->ProfileInfo->email)){
							$email = $profile_data->ProfileInfo->email;
							}else{
							$email = $profile_data->UserInfo->email;
							}
						?>

						<a href="mailto:{{ $email }}" class="btn btn-border blue-btn-border">Contact us</a>
						@else
					<p>To get insights about performance of your Google My Business page and build reports for your GMB dashboard.</p>
					<a href="javascript:;" class="btn btn-border blue-btn-border dashboardActivate" data-type="GMB" data-id="{{ $campaign_id }}" >Active</a>
					@endif
				</div>

			</article>
		</div>
	</div>
	@elseif($gtUser->gmb_id <> null)
	

	<!-- How customers search Row -->
	<div class="white-box pa-0 mb-40">
		<div class="floatingDiv"><div class="GmbErrorHeading"></div></div>
		<div class="white-box-head">
		<div class="left">
			<div class="heading">
			<h2>How customers search for your business
				<span uk-tooltip="title: It shows the division of how customers found you or about your business; pos: top-left"
				class="fa fa-info-circle"></span>
			</h2>
				<p class="gmb_time"></p>
			</div>
		</div>
		<div class="right">
			<div class="filter-list">
			<ul>
				<li>
				<button type="button" class="customer-search-range <?php if($selected_customer_search == 1){ echo 'active'; }?>" data-value="1" data-module="gmb_customer_search">One Month</button>
				</li>
				<li>
				<button type="button" class="customer-search-range <?php if($selected_customer_search == 3){ echo 'active'; }?>" data-value="3" data-module="gmb_customer_search">Three Month</button>
				</li>
				<li>
				<button type="button" class="customer-search-range <?php if($selected_customer_search == 6){ echo 'active'; }?>" data-value="6" data-module="gmb_customer_search">Six Month</button>
				</li>
				<li>
				<button type="button" class="customer-search-range <?php if($selected_customer_search == 9){ echo 'active'; }?>" data-value="9" data-module="gmb_customer_search">Nine Month</button>
				</li>
				<li>
				<button type="button" class="customer-search-range <?php if($selected_customer_search == 12){ echo 'active'; }?>" data-value="12" data-module="gmb_customer_search">One Year</button>
				</li>
			</ul>
			</div>
		</div>
		</div>
		<div class="white-box-body">
			<div class="total-searches-box">
				<div class="elem-start ajax-loader" id="customer_search_pie_chart">
					<div id="chartjs-tooltip-text"><div><p>All Searches</p></div></div>
					<div>
						<canvas id="customers_search" height="350" width="500"></canvas>
					</div>
				</div>
				<div class="elem-end">
					<ul>
						<li>
						<figure>
							<img src="{{URL::asset('public/vendor/internal-pages/images/green-pin-icon.png')}}">
						</figure>
						<h5>Direct</h5>
						<p>Customers who find your listing searching for your business name or address.</p>
						</li>
						<li>
						<figure>
							<img src="{{URL::asset('public/vendor/internal-pages/images/blue-search-icon.png')}}">
						</figure>
						<h5>Discovery</h5>
						<p>Customers who find your listing searching for a category, product, or service.</p>
						</li>
						<li>
						<figure>
							<img src="{{URL::asset('public/vendor/internal-pages/images/yellow-start-icon.png')}}">
						</figure>
						<h5>Branded</h5>
						<p>Customers who find your listing searching for a brand related to your business.</p>
						</li>
					</ul>
				</div>
			</div>
		</div>

	</div>
	<!-- How customers search Row End -->

	<!-- Where customers view your business Row -->
	<div class="white-box pa-0 mb-40">
		<div class="white-box-head">
		<div class="left">
			<div class="heading">
			<h2>Where customers view your business on Google
				<span
				uk-tooltip="title: It shows where your customers discovered your business on Google. ; pos: top-left"
				class="fa fa-info-circle"></span></h2>
			</div>
		</div>
		<div class="right">
			<div class="filter-list">
			<ul>
				<li>
				<button type="button" class="customer-view-range <?php if($selected_customer_view == 1){ echo 'active'; }?>" data-value="1" data-module="gmb_customer_view">One Month</button>
				</li>
				<li>
				<button type="button" class="customer-view-range <?php if($selected_customer_view == 3){ echo 'active'; }?>" data-value="3" data-module="gmb_customer_view">Three Month</button>
				</li>
				<li>
				<button type="button" class="customer-view-range <?php if($selected_customer_view == 6){ echo 'active'; }?>" data-value="6" data-module="gmb_customer_view">Six Month</button>
				</li>
				<li>
				<button type="button" class="customer-view-range <?php if($selected_customer_view == 9){ echo 'active'; }?>" data-value="9" data-module="gmb_customer_view">Nine Month</button>
				</li>
				<li>
				<button type="button" class="customer-view-range <?php if($selected_customer_view == 12){ echo 'active'; }?>" data-value="12" data-module="gmb_customer_view">One Year</button>
				</li>
			</ul>
			</div>
		</div>
		</div>
		<div class="white-box-body">
		<p>The Google services that customers use to find your business.</p>

		<div class="chart h-360 ajax-loader" id="customer-view-chartId">
			<canvas id="customer-views" height="300" width="1200"></canvas>
		</div>
		</div>

	</div>
	<!-- Where customers view your business Row End -->

	<!-- Customer actions Row -->
	<div class="white-box pa-0 mb-40">
		<div class="white-box-head">
		<div class="left">
			<div class="heading">
			<h2>Customer actions
				<span uk-tooltip="title: It shows the types of actions customers take on your business listing on Google. ; pos: top-left"
				class="fa fa-info-circle"></span></h2>
			</div>
		</div>
		<div class="right">
			<div class="filter-list">
			<ul>
				<li>
				<button type="button" class="customer-action-range  <?php if($selected_customer_action == 1){ echo 'active'; }?>" data-value="1" data-module="gmb_customer_action">One Month</button>
				</li>
				<li>
				<button type="button" class="customer-action-range  <?php if($selected_customer_action == 3){ echo 'active'; }?>" data-value="3" data-module="gmb_customer_action">Three Month</button>
				</li>
				<li>
				<button type="button" class="customer-action-range  <?php if($selected_customer_action == 6){ echo 'active'; }?>" data-value="6" data-module="gmb_customer_action">Six Month</button>
				</li>
				<li>
				<button type="button" class="customer-action-range  <?php if($selected_customer_action == 9){ echo 'active'; }?>" data-value="9" data-module="gmb_customer_action">Nine Month</button>
				</li>
				<li>
				<button type="button" class="customer-action-range  <?php if($selected_customer_action == 12){ echo 'active'; }?>" data-value="12" data-module="gmb_customer_action">One Year</button>
				</li>
			</ul>
			</div>
		</div>
		</div>
		<div class="white-box-body">
		<p>The most common actions that customers take on your listing.</p>

		<div class="chart h-360 ajax-loader" id="customer-actions-chartId">
			<canvas id="customer-actions" height="300" width="1200"></canvas>
		</div>
		</div>

	</div>
	<!-- Customer actions Row End -->

	<!-- Directions requests Row -->
	<div class="white-box pa-0 mb-40">
		<div class="white-box-head">
		<div class="left">
			<div class="heading">
			<h2>Directions requests
				<span uk-tooltip="title: It shows from which area codes your business listing receives direction requests. ; pos: top-left"
				class="fa fa-info-circle"></span></h2>
			</div>
		</div>
		<div class="right">
			<div class="filter-list">
				<ul>
					<li>
					<button type="button" class="direction-requests-range <?php if($selected_direction_request == 7){ echo 'active'; }?>" data-value="7" data-module="gmb_direction_requests">Last Week</button>
					</li>
					<li>
					<button type="button" class="direction-requests-range <?php if($selected_direction_request == 30){ echo 'active'; }?>" data-value="30" data-module="gmb_direction_requests">One Month</button>
					</li>
					<li>
					<button type="button" class="direction-requests-range <?php if($selected_direction_request == 90){ echo 'active'; }?>" data-value="90" data-module="gmb_direction_requests">Three Month</button>
					</li>
				</ul>
			</div>
		</div>
		</div>
		<div class="white-box-body">
		<p>The areas where customers request directions to your business from.</p>

		<div class="direction-box ajax-loader" id="direction-box-data">
			<div class="direction-box-list">
			<article><h5></h5><p></p></article>
			</div>
			<div class="direction-map-box">

			</div>
		</div>

		</div>

	</div>
	<!-- Directions requests Row End -->

	<!-- Phone calls Row -->
	<div class="white-box pa-0 mb-40">
		<div class="white-box-head">
		<div class="left">
			<div class="heading">
			<h2>Phone calls
				<span uk-tooltip="title: It shows when and how frequently your customers call your business. ; pos: top-left"
				class="fa fa-info-circle"></span></h2>
			</div>
		</div>
		<div class="right">
			<div class="filter-list">
			<ul>
				
				<li>
				<button type="button" class="phone-calls-range <?php if($selected_phone_calls == 1){ echo 'active'; }?>" data-value="1" data-module="gmb_phone_calls">One Month</button>
				</li>
				<li>
				<button type="button" class="phone-calls-range <?php if($selected_phone_calls == 3){ echo 'active'; }?>" data-value="3" data-module="gmb_phone_calls">Three Month</button>
				</li>
				<li>
				<button type="button" class="phone-calls-range <?php if($selected_phone_calls == 6){ echo 'active'; }?>" data-value="6" data-module="gmb_phone_calls">Six Month</button>
				</li>
				<li>
				<button type="button" class="phone-calls-range <?php if($selected_phone_calls == 9){ echo 'active'; }?>" data-value="9" data-module="gmb_phone_calls">Nine Month</button>
				</li>
				<li>
				<button type="button" class="phone-calls-range <?php if($selected_phone_calls == 12){ echo 'active'; }?>" data-value="12" data-module="gmb_phone_calls">One Year</button>
				</li>
			</ul>
			</div>
		</div>
		</div>
		<div class="white-box-body">
		<p>When and how many times customers call your business.</p>

		<div class="chart h-360 ajax-loader" id="phone-calls-bar-chartId">
			<canvas id="phone-calls-bar" width="1200" height="300" > </canvas>
		</div>
		</div>

	</div>
	<!-- Phone calls Row End -->

	

	<!-- Photo views Row -->
	<div class="white-box pa-0 mb-40">
		<div class="white-box-head">
		<div class="left">
			<div class="heading">
			<h2>Photo views
				<span uk-tooltip="title: It shows the number of times your business photos have been viewed.; pos: top-left"
				class="fa fa-info-circle"></span></h2>
			</div>
		</div>
		<div class="right">
			<div class="filter-list">
			<ul>
			
				<li>
				<button type="button" class="photo-views-range <?php if($selected_photo_views == 1){ echo 'active'; }?>" data-value="1" data-module="gmb_photo_views">One Month</button>
				</li>
				<li>
				<button type="button" class="photo-views-range <?php if($selected_photo_views == 3){ echo 'active'; }?>" data-value="3" data-module="gmb_photo_views">Three Month</button>
				</li>
				<li>
				<button type="button" class="photo-views-range <?php if($selected_photo_views == 6){ echo 'active'; }?>" data-value="6" data-module="gmb_photo_views">Six Month</button>
				</li>
				<li>
				<button type="button" class="photo-views-range <?php if($selected_photo_views == 9){ echo 'active'; }?>" data-value="9" data-module="gmb_photo_views">Nine Month</button>
				</li>
				<li>
				<button type="button" class="photo-views-range <?php if($selected_photo_views == 12){ echo 'active'; }?>" data-value="12" data-module="gmb_photo_views">One Year</button>
				</li>
			</ul>
			</div>
		</div>
		</div>
		<div class="white-box-body">
		<p>The number of times your business photos have been viewed, compared to photos from other businesses.
		</p>

		<div class="chart h-360 ajax-loader" id="photo-view-chartId">
			<canvas id="photo-view-chart" height="300" width="1200" ></canvas>
		</div>
		</div>

	</div>
	<!-- Photo views Row End -->

	<!-- Two Sections -->
	<div class="white-box-group">
		<!-- Review Section -->
		@include('vendor.gmb_sections.reviews')
		<!-- Review Section End -->

		<!-- Latest Customer Photos Section -->
		@include('vendor.gmb_sections.customer_photos')
		<!-- Latest Customer Photos Section End -->

	</div>
	<!-- Two Sections End -->

	@else
	
	<div class="white-box mb-40 ">
		<!-- <div class="loader h-33 "></div> -->
		<div class="integration-list" >
			<article>
				<figure>
					<img src="{{URL::asset('public/vendor/internal-pages/images/gmb-img.png')}}">
				</figure>
				<div>
					@if(Auth::user()->role_id ==4)
						<p>The Source is not active on your account.</p>
						<?php
							if(isset($profile_data->ProfileInfo->email)){
							$email = $profile_data->ProfileInfo->email;
							}else{
							$email = $profile_data->UserInfo->email;
							}
						?>

						<a href="mailto:{{ $email }}" class="btn btn-border blue-btn-border">Contact us</a>
						@else
					<p>To get insights about SERP, keywords and build reports for your GMB dashboard.</p>
					<a href="javascript:;" class="btn btn-border blue-btn-border" data-pd-popup-open="projectSettingGmbPopup" id="SettingsGMBBtnId">Connect</a>
					@endif
				</div>

			</article>
		</div>
	</div>
	

	@endif
</div>

