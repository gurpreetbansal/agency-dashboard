<div class="main-data" >
	
	<input type="hidden" class="account_id" value="{{@$account_id}}">
	<input type="hidden" class="campaign_id" value="{{@$campaign_id}}">
	@if($dashboardStatus == false)
	
	<div class="white-box mb-40 " id="adwords_add" >
		<div class="integration-list" >
			<article>
				<figure>
					<img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-img.png')}}">
				</figure>
				<div>
					@if(Auth::user()->role_id == 4)
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
					<p>To get insights about Ad campaigns, Ad groups, Ad copies, keywords and build reports for your PPC dashboard.</p>
					<a href="javascript:;" class="btn btn-border blue-btn-border dashboardActivate" data-type="PPC" data-id="{{ $campaign_id }}" >Active</a>
					@endif
				</div>

			</article>
		</div>
	</div>
	
	@elseif($getGoogleAds->google_ads_id != '' && $getGoogleAds->google_ads_campaign_id != '')
	
	
		@include('vendor.ppc_sections.summary')
		@include('vendor.ppc_sections.summary_chart')
		@include('vendor.ppc_sections.performance_chart')
		@include('vendor.ppc_sections.campaigns_list')
		@include('vendor.ppc_sections.ad_groups_list')
		@include('vendor.ppc_sections.keywords_list')
		@include('vendor.ppc_sections.ads_list')
		@include('vendor.ppc_sections.ad_performance_network')
		@include('vendor.ppc_sections.ad_performance_device')
		@include('vendor.ppc_sections.ad_performance_clickType')
		@include('vendor.ppc_sections.ad_performance_adSlot')

	@else
	
		<div class="white-box mb-40 " id="adwords_add" >
			<div class="integration-list" >
				<article>
					<figure>
						<img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-img.png')}}">
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
						<p>To get insights about SERP, keywords and build reports for your PPC dashboard.</p>
						<a href="#" class="btn btn-border blue-btn-border" data-pd-popup-open="campaignDetailPpc">Connect</a>
						@endif
					</div>

				</article>
			</div>
		</div>
		
	@endif
</div>
