<div class="social-area white-box main-data">
	@if($dashboardStatus == false)

	<div class="white-box mb-40 " id="social_accounts" >
		<div class="integration-list" >
			<article>
				<figure>
					<img src="{{URL::asset('public/vendor/internal-pages/images/smm-img.png')}}">
				</figure>
				<div>
					<p>To get insights about your social page traffic and build reports.</p>
					<a href="javascript:;" class="btn btn-border blue-btn-border dashboardActivate" data-type="Social" data-id="{{ $campaign_id }}" >Active</a>
				</div>

			</article>
		</div>
	</div>

	@else
		<div class="tab-head">
	        <ul class="uk-subnav uk-subnav-pill">
	            <li class="uk-active social_module" id="nav_overview"><a href="#overview" >Overview</a></li>
	            <li class=" @empty($gtUser->fbid) inactive @else social_module @endempty" id="nav_facebook"><a href="#facebook" >Facebook</a></li>
	            <li class="social_module inactive" id="nav_twitter"><a href="#twitter" >Twitter</a></li>
	            <li class="social_module inactive" id="nav_instagram"><a href="#instagram">Instagram</a></li>
	            <li class="social_module inactive" id="nav_linkedin"><a href="#linkedin">Linkedin</a></li>
	            <li class="social_module inactive" id="nav_youtube"><a href="#youtube" >Youtube</a></li>
	            <li class="social_module inactive" id="nav_pinterest"><a href="#pinterest" >Pinterest</a></li>
	        </ul>
	        <div class="right">
	        	<div class="overviewFilter"></div>
	        	<div class="facebookPageFilter"></div>
	        </div>     
	    </div>
	    <div id="social_tabs" class="uk-switcher">
	    	<div class="uk-active common_class" id="overview">
	        	<div class="overview-body">
	                <div uk-grid class="uk-grid">
	                    @include('vendor.social.overview-sections.facebook')

	                    <div class="uk-width-1-2@s uk-width-1-3@m">
	                    	<div class="single">
	                    		<div class="top-head">
	                    			<h6 class="disabled">Twitter</h6>
	                    			<span class="disabled">Coming Soon</span>
	                    		</div>
	                    		<div class="box-btns disabled">
	                    			<div class="single">
	                    				<p>	  
	                    					<img src="{{url('public/vendor/internal-pages/images/social-twitter-icon.png')}}" alt> 
	                    					<span>
	                    						Likes
		                    					<strong>00</strong>
		                    				</span>
	                    				</p>
	                    			</div>
	                    			<div class="single">
	                    				<p>                    				
	                    					<img src="{{url('public/vendor/internal-pages/images/social-twitter-icon.png')}}" alt> 
	                    					<span>
	                    						Engagement
		                    					<strong>00</strong>
		                    				</span>
	                    				</p>
	                    			</div>
	                    		</div>
	                    		<div class="graph-box disabled">
	                				<p><img src="{{url('public/vendor/internal-pages/images/social-twitter-icon.png')}}" alt> Likes</p>                    			
		                    		<div class="chart h-160">
		                    			<img src="{{url('public/vendor/internal-pages/images/social-placeholder-graph.png')}}" alt>
		                    		</div>
		                    	</div>
	                    	</div>
	                    </div>

	                    <div class="uk-width-1-2@s uk-width-1-3@m">
	                    	<div class="single">
	                    		<div class="top-head">
	                    			<h6 class="disabled">Instagram</h6>
	                    			<span class="disabled">Coming Soon</span>
	                    		</div>
	                    		<div class="box-btns disabled">
	                    			<div class="single">
	                    				<p>
	                    					<img src="{{url('public/vendor/internal-pages/images/social-instagram-icon.png')}}" alt> 
	                    					<span>
	                    						Likes
		                    					<strong>00</strong>
		                    				</span>
	                    				</p>
	                    			</div>
	                    			<div class="single">
	                    				<p>                    				
	                    					<img src="{{url('public/vendor/internal-pages/images/social-instagram-icon.png')}}" alt> 
	                    					<span>
	                    						Engagement
		                    					<strong>00</strong>
		                    				</span>
	                    				</p>
	                    			</div>
	                    		</div>
	                    		<div class="graph-box disabled">
	                				<p><img src="{{url('public/vendor/internal-pages/images/social-instagram-icon.png')}}" alt> Likes</p>                    			
		                    		<div class="chart h-160">
		                    			<img src="{{url('public/vendor/internal-pages/images/social-placeholder-graph.png')}}" alt>
		                    		</div>
		                    	</div>
	                    	</div>
	                    </div>

	                    <div class="uk-width-1-2@s uk-width-1-3@m">
	                    	<div class="single">
	                    		<div class="top-head">
	                    			<h6 class="disabled">Linkedin</h6>
	                    			<span class="disabled">Coming Soon</span>
	                    		</div>
	                    		<div class="box-btns disabled">
	                    			<div class="single">
	                    				<p>
	                    					<img src="{{url('public/vendor/internal-pages/images/social-linkedin-icon.png')}}" alt> 
	                    					<span>
	                    						Likes
		                    					<strong>00</strong>
		                    				</span>
	                    				</p>
	                    			</div>
	                    			<div class="single">
	                    				<p>                    				
	                    					<img src="{{url('public/vendor/internal-pages/images/social-linkedin-icon.png')}}" alt> 
	                    					<span>
	                    						Engagement
		                    					<strong>00</strong>
		                    				</span>
	                    				</p>
	                    			</div>
	                    		</div>
	                    		<div class="graph-box disabled">
	                				<p><img src="{{url('public/vendor/internal-pages/images/social-linkedin-icon.png')}}" alt> Likes</p>                    			
		                    		<div class="chart h-160">
		                    			<img src="{{url('public/vendor/internal-pages/images/social-placeholder-graph.png')}}" alt>
		                    		</div>
		                    	</div>
	                    	</div>
	                    </div>

	                    <div class="uk-width-1-2@s uk-width-1-3@m">
	                    	<div class="single">
	                    		<div class="top-head">
	                    			<h6 class="disabled">Youtube</h6>
	                    			<span class="disabled">Coming Soon</span>
	                    		</div>
	                    		<div class="box-btns disabled">
	                    			<div class="single">
	                    				<p>
	                    					<img src="{{url('public/vendor/internal-pages/images/social-youtube-icon.png')}}" alt> 
	                    					<span>
	                    						Likes
		                    					<strong>00</strong>
		                    				</span>
	                    				</p>
	                    			</div>
	                    			<div class="single">
	                    				<p>                    				
	                    					<img src="{{url('public/vendor/internal-pages/images/social-youtube-icon.png')}}" alt> 
	                    					<span>
	                    						Engagement
		                    					<strong>00</strong>
		                    				</span>
	                    				</p>
	                    			</div>
	                    		</div>
	                    		<div class="graph-box disabled">
	                				<p><img src="{{url('public/vendor/internal-pages/images/social-youtube-icon.png')}}" alt> Likes</p>                    			
		                    		<div class="chart h-160">
		                    			<img src="{{url('public/vendor/internal-pages/images/social-placeholder-graph.png')}}" alt>
		                    		</div>
		                    	</div>
	                    	</div>
	                    </div>

	                    <div class="uk-width-1-2@s uk-width-1-3@m">
	                    	<div class="single">
	                    		<div class="top-head">
	                    			<h6 class="disabled">Pinterest</h6>
	                    			<span class="disabled">Coming Soon</span>
	                    		</div>
	                    		<div class="box-btns disabled">
	                    			<div class="single">
	                    				<p>
	                    					<img src="{{url('public/vendor/internal-pages/images/social-pinterest-icon.png')}}" alt> 
	                    					<span>
	                    						Likes
		                    					<strong>00</strong>
		                    				</span>
	                    				</p>
	                    			</div>
	                    			<div class="single">
	                    				<p>                    				
	                    					<img src="{{url('public/vendor/internal-pages/images/social-pinterest-icon.png')}}" alt> 
	                    					<span>
	                    						Engagement
		                    					<strong>00</strong>
		                    				</span>
	                    				</p>
	                    			</div>
	                    		</div>
	                    		<div class="graph-box disabled">
	                				<p><img src="{{url('public/vendor/internal-pages/images/social-pinterest-icon.png')}}" alt> Likes</p>                    			
		                    		<div class="chart h-160">
		                    			<img src="{{url('public/vendor/internal-pages/images/social-placeholder-graph.png')}}" alt>
		                    		</div>
		                    	</div>
	                    	</div>
	                    </div>
	                    
	                </div>
	        	</div>
	        </div>
	        <div id="facebook" class="common_class">
	    	</div>
	    </div>
	@endif
</div>