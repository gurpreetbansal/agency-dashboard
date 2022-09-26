
<div class="project-detail-header" id="projectdetailheader">
	<div class="elem-left">
		<figure  id="projectLogo-img">
			<img src="{{ @$auditTask->domain_logo <> null ? @$auditTask->domain_logo : URL::asset('/public/vendor/images/brand_logo.png')}}" >
		</figure>
		<h3>{{ @$auditTask->crawled_url }}
			<a href="//{{@$auditTask->crawled_url}}" target="_blank"> <i class="fa fa-external-link"></i></a>
		</h3>
	</div>
	
	<div class="elem-right">
		<div class="project-manager-detail" id="agency-contact-info">
			<span class="title"><img src="{{URL::asset('public/vendor/internal-pages/images/helpdesk-icon.png')}}"> Helpdesk</span>
			<figure>
				<img src="{{ @$userProfile->logo_data <> null ? @$userProfile->logo_data : URL::asset('public/vendor/internal-pages/images/user-img.jpg')}}">
			</figure>
			<ul>
				<li><a href="tel:{{'+'.@$userProfile->ProfileInfo->country_code_val.@$userProfile->contact_no}}"><i class="fa fa-phone"></i><?php if(isset($userProfile->contact_no)){ echo '+'.@$userProfile->country_code_val.$userProfile->contact_no;}else{ echo "Agency Phone Number";}?></a></li>
				<?php 
				if(isset($userProfile->email)){
					$email =  @$userProfile->email;
				}else{
					$email= 'Agency Email Address';
				}
				?>
				<li><a href="mailto:{{@$email}}"><i class="fa fa-envelope"></i>{{ @$email }}</a></li>
			</ul>
		</div>
	</div>
</div>