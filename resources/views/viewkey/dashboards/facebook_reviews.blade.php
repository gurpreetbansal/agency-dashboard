<div class="main-data-view social-area white-box social-view-data facebookviewreviews" id="facebookviewreviews" uk-sortable="handle:.white-box-handle">
	<div id="social_tabs">
		<div id="facebook" >
     		<div class="social-body">
				<div class="single">
					<h2><img src="{{url('public/vendor/internal-pages/images/social-reviews-icon.png')}}" alt> Reviews<span><i uk-tooltip="title: Whatever your clients have to say about your business will be displayed here." class="fa fa-info-circle" title="" aria-expanded="false"></i></span></h2>    						
					<div class="white-box reviews-listing">
						<div class="inner fbreviews-listing" id="reviewDataTableview">
							@for ($i = 0; $i < 6; $i++)
							<div class="single-review">
								<div class="inner-head">
									<figure class="ajax-loader review_image"><img src="{{url('public/vendor/internal-pages/images/placeholder-user.png')}}" alt="review-image" ></figure>
									<h6 class="reviewerName ajax-loader">David Forester<span class="red">Not Recommended</span></h6>
									<p class="reviewDate ajax-loader">Jul 16, 2018</p>
								</div>
								<div class="body"><p class="reviewText ajax-loader">Spam operative that just won't stop trying to contact us despite being told we're not interested</p></div>
							</div>
							@endfor
						</div>
						<div class="social-pagination facebook_reviews_pagination"></div>
					</div>
				</div>
     		</div>
     	</div>
	</div>
</div>