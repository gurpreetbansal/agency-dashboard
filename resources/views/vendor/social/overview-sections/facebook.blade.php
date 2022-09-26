<div class="uk-width-1-2@s uk-width-1-3@m">
	<div class="single">
		<div class="popup-inner" id="preparingFb" style="display: none;">
            <div class="preloader-popup">
                <img src="{{URL::asset('public/vendor/internal-pages/images/preloader.gif')}}" alt="loader">
            </div>
            <h6>Fetching your Facebook Data, <br>it may take few seconds</h6>
        </div>
		<div class="top-head social_module">
			<h6>Facebook </h6>
			<?php if(!empty($gtUser->fbid) && !empty($gtUser->fbid)){ $disableClass = ""; ?>
				<a href="#facebook" class="btn blue-btn">View More</a>
			<?php }else{ $disableClass = "disabled"; ?>
				<a href="javascript:void(0)" class="btn blue-btn " data-pd-popup-open="projectSettingFacebookPopup" id="facebookSettingsBtnId">Connect</a>
			<?php }?>
		</div>
		<div class="box-btns facebook_overview {{$disableClass}}">
			<div class="single">
				<p>
					<img src="{{url('public/vendor/internal-pages/images/social-fb.svg')}}" alt> 
					<span>
						Likes
    					<strong class="facebook_overview_likes ajax-loader">0</strong>
    				</span>
				</p>
			</div>
			<div class="single">
				<p>                  				
					<img src="{{url('public/vendor/internal-pages/images/social-fb.svg')}}" alt> 
					<span>
						Reach
    					<strong class="facebook_overview_engagement ajax-loader">0</strong>
    				</span>
				</p>
			</div>
		</div>
		<div class="graph-box fboverview {{$disableClass}}">
			<p><img src="{{url('public/vendor/internal-pages/images/social-fb.svg')}}" alt> Audience Growth</p>                    			
    		<div class="chart h-160 facebook_overview_img ajax-loader" >
    			<img  src="{{url('public/vendor/internal-pages/images/social-placeholder-graph.png')}}">
    			<canvas id="facebook_overview_organic"></canvas>
    		</div>
    	</div>
	</div>
</div>