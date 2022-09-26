
@if($getGoogleAds <> null && ($getGoogleAds->google_ads_id != '' && $getGoogleAds->google_ads_campaign_id != '') && $types <> null)
<div class="main-data-view" id="ppcDashboard">
	
	<input type="hidden" class="account_id" value="{{@$account_id}}">
	<input type="hidden" class="campaign_id" value="{{@$campaign_id}}">	
		@include('viewkey.ppc_sections.summary')
		@include('viewkey.ppc_sections.summary_chart')
		@include('viewkey.ppc_sections.performance_chart')
		
	@else
<div class="main-data-viewDeactive" id="ppcDashboard" >
		<div class="white-box mb-40 " >
			<div class="integration-list" >
				<article>
					<figure>
						<img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-img.png')}}">
					</figure>
					<div>
						<p>The PPC Dashboard is not enabled for your account.</p>
						<?php
						
							if(isset($profile_data->ProfileInfo->email)){
							$email = @$profile_data->ProfileInfo->email;
							}else{
							$email = @$profile_data->UserInfo->email;
							}
						?>

						<a href="mailto:{{ $email }}" class="btn btn-border blue-btn-border">Contact us</a>
					</div>

				</article>
			</div>
		</div>
	@endif
</div>