<div class="project-detail-header" id="projectdetailheader">
	<span class="layer"></span>
	<div class="elem-left ajax-loader">
		<figure  id="projectLogo-img">
			<img src="{{ @$profile_data->project_logo <> null ? @$profile_data->project_logo : URL::asset('/public/vendor/images/brand_logo.png')}}" >
		</figure>
		<h3>{{ @$profile_data->host_url }}
			<a href="//{{@$profile_data->host_url}}" target="_blank"> <i class="fa fa-external-link"></i></a>
			<small><?php if(isset($profile_data->clientName) && !empty($profile_data->clientName)){ echo $profile_data->clientName;}else{ echo "Client Name";}?></small></h3>
	</div>
	
	<div class="elem-right ajax-loader">
		<div class="project-manager-detail" id="agency-contact-info">
			<span class="title">
				<img src="{{URL::asset('public/vendor/internal-pages/images/helpdesk-icon.png')}}"> Helpdesk
			</span>
			<figure>
				<img src="{{ @$profile_data->logo_data <> null ? @$profile_data->logo_data : URL::asset('public/vendor/internal-pages/images/user-img.jpg')}}">
			</figure>
			<ul>
				<li>
					<a href="tel:{{'+'.@$profile_data->ProfileInfo->country_code_val.@$profile_data->ProfileInfo->contact_no}}">
						<span><i class="fa fa-phone"></i></span>
						<?php if(isset($profile_data->ProfileInfo->contact_no)){ echo '+'.@$profile_data->ProfileInfo->country_code_val.$profile_data->ProfileInfo->contact_no;}else{ echo "Agency Phone Number";}?>
					</a>
				</li>
				<?php 
					if(isset($profile_data->ProfileInfo->email)){
						$email =  @$profile_data->ProfileInfo->email;
					}else{
						$email= 'Agency Email Address';
					}
				?>
				<li>
					<a href="mailto:{{@$email}}">
						<span><i class="fa fa-envelope"></i></span>
						{{ @$email }}
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>