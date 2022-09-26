<div class="project-detail-header" id="projectdetailheader">
	<!-- <div class="loader h-73 half"></div> -->
	<div class="elem-left ajax-loader">
		<div id="project-logo-div">
		<figure  id="projectLogo-img">
			@if(isset($profile_data->project_logo) && !empty($profile_data->project_logo))
			<img src="{{$profile_data->project_logo}}">
			@else
			<img src="{{URL::asset('/public/vendor/images/brand_logo.png')}}" >
			@endif
		</figure>
	</div>
		<h3><a href="{{url('/campaign-detail/'.@$profile_data->id)}}">{{@$profile_data->host_url}} </a>
			<a href="//{{@$profile_data->host_url}}" target="_blank"> <i class="fa fa-external-link"></i></a>
			<p id="client_display_name_p">
				<small id="client_display_name"><?php if(isset($profile_data->clientName) && !empty($profile_data->clientName)){ echo $profile_data->clientName;}else{ echo "Client Name";}?></small></h3>
			</p>
	</div>
	
	<div class="elem-right ajax-loader" id="agency-contact-info_div">
		<div class="project-manager-detail" id="agency-contact-info">
			<span class="title"><img src="{{URL::asset('public/vendor/internal-pages/images/helpdesk-icon.png')}}"> Helpdesk</span>
			<figure>
				@if(isset($profile_data->logo_data) && !empty($profile_data->logo_data))
			<img src="{{$profile_data->logo_data}}">
			@else
			<img src="{{URL::asset('public/vendor/internal-pages/images/user-img.jpg')}}">
			@endif
				
			</figure>
			<ul>
				<li><a href="tel:{{'+'.@$profile_data->ProfileInfo->country_code_val.@$profile_data->ProfileInfo->contact_no}}"><i class="fa fa-phone"></i><?php if(isset($profile_data->ProfileInfo->contact_no)){ echo '+'.@$profile_data->ProfileInfo->country_code_val.$profile_data->ProfileInfo->contact_no;}else{ echo "Agency Phone Number";}?> </a></li>
				<li><a href="mailto:{{@$profile_data->ProfileInfo->email}}"><i class="fa fa-envelope"></i><?php 
				if(isset($profile_data->ProfileInfo->email)){
					echo $profile_data->ProfileInfo->email;
				}else{
					// echo @$profile_data->UserInfo->email;
					echo "Agency Email Address";
				}
				?></a></li>
			</ul>
		</div>
	</div>
</div>