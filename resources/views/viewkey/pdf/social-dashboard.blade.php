	@if($selectedTab == 'facebook')
		<div id="facebook" class="common_class pdf">
			<div class="social-body">
				<div class="single">
    				<div class="grid-tab">
		                <div uk-grid class="uk-grid">
		                	<div class="uk-width-1-1">
		                		<div class="head">
			                		<h2><img src="{{url('public/vendor/internal-pages/images/social-likes-icon.png')}}" alt> Likes</h2>
			                		<p class="social_range"></p>
			                	</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box">
	                    			<h5><span>Total Likes</span></h5>
	                    			<div class="total-likes">
	                    				<h6>
	                    					<img src="{{url('public/vendor/internal-pages/images/social-likes-icon.png')}}" alt> 
	                    					<span class="likes-count">0</span>
	                    				</h6>
	                    				<p>
	                    					<i class="icon likepercenticon"></i> 
	                    					<span class="likes-percent"></span>
	                    				</p>
	                    			</div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box">
	                    			<h5><span>Gender</span></h5>
                                    <div class="chart circle-chart">
                                    	<div id="fbgenderlikesDonaughttotal" data-value="" class="donaught-total-text">
						    				<div>
						    					<p></p>
						    				</div>
						    			</div>
                                    	<canvas id="likesFbGender"></canvas>
                                    </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box">
	                    			<h5><span>Organic vs Paid Likes</span></h5>
                                    <div class="chart circle-chart">
                                    	<div id="organicPaidFblikestotal" data-value="" class="donaught-total-text">
						    				<div>
						    					<p></p>
						    				</div>
						    			</div>
                                    	<canvas id="organicPaidFblikesdonaught"></canvas>
                                    </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-1">
	                    		<div class="white-box x2">
	                    			<h5><span>Age</span></h5>
                                    <div class="chart">
                                    	<canvas id="likesFbGenderBar"></canvas>
                                    </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-1">
	                    		<div class="white-box x2">
	                    			<h5><span>Audience Growth</span></h5>
                                    <div class="chart">
                                    	<canvas id="organicPaidFblikes"></canvas>
                                    </div>
	                    		</div>
		                    </div>
		                </div>
		                <div uk-grid class="uk-grid fbCountryLikesTable BreakBefore">
		                    <div class="uk-width-1-1">
	                    		<div class="white-box height-auto">
                                	<h5><span>Top Countries</span></h5>
                                	<div class="table-data">
		                                <table>
					                        <tbody id="fbCountryLikesTable">
					                        </tbody>
					                    </table>
		                            </div>
	                    		</div>
		                    </div>
		                </div>
		                <div uk-grid class="uk-grid fbCitiesLikesTable BreakBefore">
		                    <div class="uk-width-1-1">
	                    		<div class="white-box height-auto">
                                	<h5><span>Top Cities</span></h5>                                	
		                            <div class="table-data">
		                                <table>
		                                    <tbody id="fbCitiesLikesTable">
	                        				</tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                </div>
		                <div uk-grid class="uk-grid fbLanguageLikesTable BreakBefore">
		                    <div class="uk-width-1-1">
	                    		<div class="white-box height-auto">
                                	<h5><span>Top Languages</span></h5>
		                            <div class="table-data">
		                                <table>
		                                    <tbody id="fbLanguageLikesTable">
	                        				</tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                </div>
		                <div uk-grid class="uk-grid BreakBefore">
		                    <div class="uk-width-1-1">
	                    		<h2><img src="{{url('public/vendor/internal-pages/images/social-reach-icon.png')}}" alt> Reach</h2>
		                    </div>
		                    <div class="uk-width-1-1">
	                    		<div class="white-box x2">
	                    			<h5>
	                    				<span>Total Reach</span>
	                    				<strong id="reach_count">0</strong>
	                    			</h5>
                                    <div class="chart">
                                    	<canvas id="reachGraph"></canvas>
                                    </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box x2 uk-flex">
	                    			<h5><span>Organic vs Paid Reach</span></h5>
                                    <div class="chart circle-chart">
                                    	<div id="organicPaidFbreachtotal" data-value="" class="donaught-total-text">
				                            <div>
				                                <p></p>
				                            </div>
				                        </div>
                                    	<canvas id="organicPaidFbreach"></canvas>
                                    </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-2-3">
	                    		<div class="white-box x2">
	                    			<h5><span>Gender</span></h5>
                                    <div class="chart">
                                    	<canvas id="reachFbstacked"></canvas>
                                    </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box x2 uk-flex">
	                    			<h5><span>Video Views</span></h5>
                                    <div class="chart circle-chart">
                                    	<div id="organicPaidFbvideoreachtotal" data-value="" class="donaught-total-text">
				                            <div>
				                                <p></p>
				                            </div>
				                        </div>
                                    	<canvas id="organicPaidFbvideoreach"></canvas>
                                    </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-2-3">
	                    		<div class="white-box x2">
	                    			<h5><span>Age</span></h5>
                                    <div class="chart">
                                    	<canvas id="genderReachData"></canvas>
                                    </div>
	                    		</div>
		                    </div>
		                </div>
		                <div uk-grid class="uk-grid BreakBefore">
		                    <div class="uk-width-1-1">
	                    		<div class="white-box height-auto">
                                	<h5><span>Top Countries</span></h5>
                                	<div class="table-data">
		                                <table>
		                                    <tbody id="fbCountryReachTable">
                            				</tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                </div>
		                <div uk-grid class="uk-grid fbCitiesReachTable BreakBefore">
		                    <div class="uk-width-1-1">
	                    		<div class="white-box height-auto">
                                	<h5><span>Top Cities</span></h5>                                	
		                            <div class="table-data">
		                                <table>
		                                    <tbody id="fbCitiesReachTable">
                            				</tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                </div>
		                <div uk-grid class="uk-grid fbLanguageReachTable BreakBefore">
		                    <div class="uk-width-1-1">
	                    		<div class="white-box height-auto">
                                	<h5><span>Top Languages</span></h5>
		                            <div class="table-data">
		                                <table>
		                                    <tbody id="fbLanguageReachTable">
                            				</tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                </div>

		                <div id="postDataTable" class="postDataTablepdf"></div>

		                
		                <div uk-grid class="uk-grid BreakBefore">  				
		    				<div class="uk-width-1-1">
		    					<div class="single">
		    						<h2><img src="{{url('public/vendor/internal-pages/images/social-reviews-icon.png')}}" alt> Reviews</h2>    						
		                    		<div class="white-box reviews-listing" id="reviewDataTable">
		    						</div>
		    					</div>
    						</div>
						</div>
					</div>
    			</div>
			</div>
		</div>
	@endif
