<div class="main-data-view social-area white-box social-view-data facebookviewpostreviews" id="facebookviewpostreviews" uk-sortable="handle:.white-box-handle">
	<div id="social_tabs">
		<div id="facebook" >
     		<div class="social-body">
     			<div class="single">
					<h2><img src="{{url('public/vendor/internal-pages/images/social-posts-icon.png')}}" alt> Posts<span><i uk-tooltip="title: This comprises all the posts ever posted and the number of likes, comments, reach and clicks they get." class="fa fa-info-circle" title="" aria-expanded="false"></i></span></h2>
					<div class="white-box post-listing">
						<div class="inner fbpost-listing" id="postDataTableview">
							@for ($i = 0; $i < 6; $i++)
							<div class="single-post">
				                <div class="post-head">
						            <figure class="fromImage ajax-loader">
						                <img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-post-attachment.jpg" alt="posted-by">
						            </figure>
						            <h6>
						                <big class="fromName ajax-loader">iMark Infotech Pvt. Ltd.</big>
						                <span class="datePost ajax-loader">Sep 21, 2022</span>
						            </h6>
						        </div>
						        <p class="ajax-loader postMessage">Workplace wellness activities are a great way to boost your energy and enthusiasm. We’re always looking for ways to make our employees happy, healthy, and productive.
						        </p>
						    	<figure class="ajax-loader full_picture">
						    		<img src="" alt="post-image">
						            <figcaption><a href="https://imark.waveitdigital.com/public/vendor/internal-pages/images/placeholder-user.png" class="btn blue-btn" target="_blank"> <i class="fa fa-external-link"></i> View Post</a></figcaption>
						    	</figure>    		
								<ul class="ajax-loader fb_post_ul">
									<li class="postReach"><span><img src="{{url('public/vendor/internal-pages/images/social-reach-icon-small.png')}}" alt="social-reach-icon-small"> 0</span> Reach</li>
									<li class="postLikes"><span><img src="{{url('public/vendor/internal-pages/images/social-likes-icon-small.png')}}" alt="social-likes-icon-small"> 0</span> Likes</li>
									<li class="postClicks"><span><img src="{{url('public/vendor/internal-pages/images/social-click-icon.png')}}" alt="social-click-icon"> 0</span> Clicks</li>
									<li class="postComments"><span><img src="{{url('public/vendor/internal-pages/images/social-comment-icon.png')}}" alt="social-comment-icon"> 0</span> Comments</li>
								</ul>
					    	</div>
					    	@endfor
						</div>
						<div class="social-pagination facebook_post_pagination"></div>
					</div>
				</div>
     		</div>
     	</div>
	</div>
</div>