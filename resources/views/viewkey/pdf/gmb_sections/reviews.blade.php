<!-- Two Sections -->
<div class="white-box-group ">
	<!-- Review Section -->
	<div class="white-box pa-0 mb-40 white-box-handle space-top BreakBefore">
		<div class="section-head">
		    <h4>Reviews</h4>
		    <hr>
		    <p>
		    	<small>
		    		<em>
		    			<img src="{{URL::asset('public/vendor/internal-pages/images/info.png')}}" alt="info-icon"> Top 8 reviews
		    		</em>
	    		</small>
	    	</p>
	  	</div>
		<div class="white-box-body review-body" id="display_reviews"></div>
		<div class="uk-text-center pa-20">
	      	<p class="mb-0">
	      		<a href="{{url('/project-detail/'.$key)}}" class="btn blue-btn">To view more Click here <i class="fa fa-external-link"></i></a>
	      	</p>
	    </div>
		<!-- <div id="display_reviews_pagination" class="ajax-loader"></div> -->
	</div>
	<!-- Review Section End -->

	<!-- Latest Customer Photos Section -->
	<div class="white-box pa-0 mb-40 white-box-handle space-top BreakBefore">		
		<div class="section-head">
		    <h4>Latest Customer Photos</h4>
	  	</div>
		<div class="white-box-body photos-list" id="latest_customer_photos"></div>
	</div>
	<!-- Latest Customer Photos Section End -->
</div>
<!-- Two Sections End -->