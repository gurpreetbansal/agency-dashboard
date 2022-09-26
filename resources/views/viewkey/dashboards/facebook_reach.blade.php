<div class="main-data-view social-area white-box social-view-data facebookviewreach" id="facebookviewreach" >
	<div id="social_tabs">
     	<div id="facebook" >
     		<div class="social-body">
     			<div class="single">
     				<div class="head">
     					<h2><img src="{{url('public/vendor/internal-pages/images/social-reach-icon.png')}}" alt> Reach</h2>
     					<div class="right">
							<p class="social_range_viewreach"></p>
				        	<div class="facebookReachPageFilter"></div>
				        </div> 
     				</div>
     				<div class="grid-tab">
				        <div uk-grid class="uk-grid">
				            <div class="uk-width-1-2">
				        		<div class="white-box ">
				        			<h5>
				        				<span>
				        					Total Reach
				        					<i uk-tooltip="title: You can check the current and previous reach percentage of your posts on the last day of every month" class="fa fa-info-circle" title="" aria-expanded="false"></i>
				        				</span>
				        				<strong id="reach_count_view" class="ajax-loader">0</strong>
				        			</h5>
				                    <div class="chart facebook_totalreach_loader ajax-loader">
				                        <canvas id="reachFbGraphView"></canvas>
				                    </div>
				        		</div>
				            </div>
				            <div class="uk-width-1-4">
				        		<div class="white-box ">
				        			<h5>
				        				<span>
				        					Organic vs Paid Reach
				        					<i uk-tooltip="title: You’ll know the exact reach your posts are getting and the number of organic and paid reach among them" class="fa fa-info-circle" title="" aria-expanded="false"></i>
				        				</span>
				        			</h5>
				                    <div class="chart facebook_organicpaidreach_loader ajax-loader">
				                        <div id="organicPaidFbreachtotalview" data-value="" class="donaught-total-text">
				                            <div>
				                                <p></p>
				                            </div>
				                        </div>
				                        <canvas id="organicPaidFbreachview"></canvas>
				                    </div>
				        		</div>
				            </div>
				            <div class="uk-width-1-4">
				        		<div class="white-box ">
				        			<h5>
				        				<span>
				        					Video Views
				        					<i uk-tooltip="title: The total number of views on your videos will be shown here." class="fa fa-info-circle" title="" aria-expanded="false"></i>
				        				</span>
				        			</h5>
				                    <div class="chart facebook_organicpaidvideoreach_loader ajax-loader">
				                        <div id="organicPaidFbvideoreachtotalview" data-value="" class="donaught-total-text">
				                            <div>
				                                <p></p>
				                            </div>
				                        </div>
				                        <canvas id="organicPaidFbvideoreachview"></canvas>
				                    </div>
				        		</div>
				            </div>
				            <div class="uk-width-1-2">
				        		<div class="white-box x2 ">
				        			<h5>
				        				<span>
				        					Gender
				        					<i uk-tooltip="title: This gives you an insight into the gender distribution of your audience. You’ll get to know the exact number and percentage of both genders after hovering over the chart." class="fa fa-info-circle" title="" aria-expanded="false"></i>
				        				</span>
				        			</h5>
				                    <div class="chart facebook_genderreach_loader ajax-loader">
				                        <canvas id="reachFbstackedview"></canvas>
				                    </div>
				        		</div>
				            </div>
				            <div class="uk-width-1-2">
				        		<div class="white-box x2 ">
				        			<h5>
				        				<span>
				        					Age
				        					<i uk-tooltip="title: Know the age group of the people your posts are reaching to. You’ll get the details of both your previous and current posts." class="fa fa-info-circle" title="" aria-expanded="false"></i>
				        				</span>
				        			</h5>
				                    <div class="chart facebook_agereach_loader ajax-loader">
				                        <canvas id="genderReachDataview"></canvas>
				                    </div>
				        		</div>
				            </div>
				            <div class="uk-width-1-3">
				        		<div class="white-box ">
				                	<h5>
				                		<span>
				                			Top Countries
				        					<i uk-tooltip="title: It shows the top countries where your posts reach and the exact number of people from each country as well." class="fa fa-info-circle" title="" aria-expanded="false"></i>
				            			</span>
				            		</h5>
				                	<div class="table-data ajax-loader">
				                        <table>
				                            <tbody id="fbCountryReachTableview" class="stopScroll">
				                            	@for($i = 0; $i < 5; $i++)
					                                <tr>
					                                    <td class="ajax-loader">....</td>
					                                    <td class="ajax-loader">....</td>
					                                </tr>
					                            @endfor
				                            </tbody>
				                        </table>
				                    </div>
				        		</div>
				            </div>
				            <div class="uk-width-1-3">
				        		<div class="white-box ">
				                	<h5>
				                		<span>
				                			Top Cities
				        					<i uk-tooltip="title: Know which all cities your posts are reaching. And, you’ll also get the number of people coming from each city." class="fa fa-info-circle" title="" aria-expanded="false"></i>
				            			</span>
				            		</h5>                                	
				                    <div class="table-data ajax-loader">
				                        <table>
				                            <tbody id="fbCitiesReachTableview" class="stopScroll">
				                            	@for($i = 0; $i < 5; $i++)
					                                <tr>
					                                    <td class="ajax-loader">....</td>
					                                    <td class="ajax-loader">....</td>
					                                </tr>
					                            @endfor
				                            </tbody>
				                        </table>
				                    </div>
				        		</div>
				            </div>
				            <div class="uk-width-1-3">
				        		<div class="white-box ">
				                	<h5>
				                		<span>
				                			Top Languages
				        					<i uk-tooltip="title: This will give you an insight into the languages spoken by your targeted audience and the number of people speaking each language." class="fa fa-info-circle" title="" aria-expanded="false"></i>
				            			</span>
				            		</h5>
				                    <div class="table-data ajax-loader">
				                        <table>
				                            <tbody id="fbLanguageReachTableview" class="stopScroll">
				                            	@for($i = 0; $i < 5; $i++)
					                                <tr>
					                                    <td class="ajax-loader">....</td>
					                                    <td class="ajax-loader">....</td>
					                                </tr>
					                            @endfor
				                            </tbody>
				                        </table>
				                    </div>
				        		</div>
				            </div>
				        </div>
				    </div>
     			</div>
     		</div>
     	</div>
     </div>
</div>