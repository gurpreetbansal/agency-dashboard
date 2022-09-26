@extends('layouts.vendor_internal_pages')
@section('content')

<div class="social-area white-box">
	<div class="tab-head">
        <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: #social_tabs; animation: uk-animation-slide-left-medium, uk-animation-slide-right-medium">
            <li class="uk-active"><a href="javascript:void(0)">Overview</a></li>
            <li><a href="javascript:void(0)">Facebook</a></li>
            <li><a href="javascript:void(0)">Twitter</a></li>
            <li><a href="javascript:void(0)">Instagram</a></li>
            <li><a href="javascript:void(0)">Linkedin</a></li>
            <li><a href="javascript:void(0)">Youtube</a></li>
            <li><a href="javascript:void(0)">Pinterest</a></li>
            <li class="more-dropdown">
                <div class="uk-inline">
                    <button class="uk-button uk-button-default" type="button"><span uk-icon="more"></span> More</button>
                    <div uk-dropdown="mode: click">
                        <a href="javascript:void(0)">Pinterest</a>
                    </div>
                </div>
            </li>
        </ul>     
    </div>
    <div id="social_tabs" class="uk-switcher">
        <div class="uk-active">
        	<div class="overview-body">
                <div uk-grid class="uk-grid">
                    <div class="uk-width-1-3">
                    	<div class="single">
                    		<div class="top-head">
                    			<h6>Facebook</h6>
                    			<a href="javascript:void(0)">View More</a>
                    		</div>
                    		<div class="box-btns">
                    			<div class="single">
                    				<p>
                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-facebook-icon.png" alt> 
                    					<span>
                    						Followers
	                    					<strong>2375</strong>
	                    				</span>
                    				</p>
                    			</div>
                    			<div class="single">
                    				<p>                    				
                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-facebook-icon.png" alt> 
                    					<span>
                    						Engagement
	                    					<strong>2375</strong>
	                    				</span>
                    				</p>
                    			</div>
                    		</div>
                    		<div class="graph-box">
                				<p><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-facebook-icon.png" alt> Followers</p>                    			
	                    		<div class="chart h-160"></div>
	                    	</div>
                    	</div>
                    </div>
                    <div class="uk-width-1-3">
                    	<div class="single">
                    		<div class="top-head">
                    			<h6>Twitter</h6>
                    			<a href="javascript:void(0)">View More</a>
                    		</div>
                    		<div class="box-btns">
                    			<div class="single">
                    				<p>
                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-twitter-icon.png" alt> 
                    					<span>
                    						Followers
	                    					<strong>2375</strong>
	                    				</span>
                    				</p>
                    			</div>
                    			<div class="single">
                    				<p>                    				
                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-twitter-icon.png" alt> 
                    					<span>
                    						Engagement
	                    					<strong>2375</strong>
	                    				</span>
                    				</p>
                    			</div>
                    		</div>
                    		<div class="graph-box">
                				<p><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-twitter-icon.png" alt> Followers</p>                    			
	                    		<div class="chart h-160"></div>
	                    	</div>
                    	</div>
                    </div>
                    <div class="uk-width-1-3">
                    	<div class="single">
                    		<div class="top-head">
                    			<h6>Instagram</h6>
                    			<a href="javascript:void(0)">View More</a>
                    		</div>
                    		<div class="box-btns">
                    			<div class="single">
                    				<p>
                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-instagram-icon.png" alt> 
                    					<span>
                    						Followers
	                    					<strong>2375</strong>
	                    				</span>
                    				</p>
                    			</div>
                    			<div class="single">
                    				<p>                    				
                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-instagram-icon.png" alt> 
                    					<span>
                    						Engagement
	                    					<strong>2375</strong>
	                    				</span>
                    				</p>
                    			</div>
                    		</div>
                    		<div class="graph-box">
                				<p><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-instagram-icon.png" alt> Followers</p>                    			
	                    		<div class="chart h-160"></div>
	                    	</div>
                    	</div>
                    </div>
                    <div class="uk-width-1-3">
                    	<div class="single">
                    		<div class="top-head">
                    			<h6>Linkedin</h6>
                    			<a href="javascript:void(0)">View More</a>
                    		</div>
                    		<div class="box-btns">
                    			<div class="single">
                    				<p>
                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-linkedin-icon.png" alt> 
                    					<span>
                    						Followers
	                    					<strong>2375</strong>
	                    				</span>
                    				</p>
                    			</div>
                    			<div class="single">
                    				<p>                    				
                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-linkedin-icon.png" alt> 
                    					<span>
                    						Engagement
	                    					<strong>2375</strong>
	                    				</span>
                    				</p>
                    			</div>
                    		</div>
                    		<div class="graph-box">
                				<p><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-linkedin-icon.png" alt> Followers</p>                    			
	                    		<div class="chart h-160"></div>
	                    	</div>
                    	</div>
                    </div>
                    <div class="uk-width-1-3">
                    	<div class="single">
                    		<div class="top-head">
                    			<h6>Youtube</h6>
                    			<a href="javascript:void(0)">View More</a>
                    		</div>
                    		<div class="box-btns">
                    			<div class="single">
                    				<p>
                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-youtube-icon.png" alt> 
                    					<span>
                    						Followers
	                    					<strong>2375</strong>
	                    				</span>
                    				</p>
                    			</div>
                    			<div class="single">
                    				<p>                    				
                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-youtube-icon.png" alt> 
                    					<span>
                    						Engagement
	                    					<strong>2375</strong>
	                    				</span>
                    				</p>
                    			</div>
                    		</div>
                    		<div class="graph-box">
                				<p><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-youtube-icon.png" alt> Followers</p>                    			
	                    		<div class="chart h-160"></div>
	                    	</div>
                    	</div>
                    </div>
                    <div class="uk-width-1-3">
                    	<div class="single">
                    		<div class="top-head">
                    			<h6>Pinterest</h6>
                    			<a href="javascript:void(0)" class="btn blue-btn">Connect</a>
                    		</div>
                    		<div class="box-btns disabled">
                    			<div class="single">
                    				<p>
                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-pinterest-icon.png" alt> 
                    					<span>
                    						Followers
	                    					<strong>00</strong>
	                    				</span>
                    				</p>
                    			</div>
                    			<div class="single">
                    				<p>                    				
                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-pinterest-icon.png" alt> 
                    					<span>
                    						Engagement
	                    					<strong>00</strong>
	                    				</span>
                    				</p>
                    			</div>
                    		</div>
                    		<div class="graph-box disabled">
                				<p><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-pinterest-icon.png" alt> Followers</p>                    			
	                    		<div class="chart h-160">
	                    			<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-placeholder-graph.png" alt>
	                    		</div>
	                    	</div>
                    	</div>
                    </div>
                </div>
        	</div>
        </div>
        <div>
    		<div class="social-body">
    			<div class="single">
    				<h2><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-likes-icon.png" alt> Likes</h2>
    				<div class="grid-tab">
		                <div uk-grid class="uk-grid">
		                    <div class="uk-width-1-4">
	                    		<div class="white-box">
	                    			<h5><span>Total Likes</span></h5>
	                    			<div class="total-likes">
	                    				<h6>
	                    					<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-likes-icon.png" alt> 
	                    					<span>3,572</span>
	                    				</h6>
	                    				<p>
	                    					<i class="icon ion-arrow-up-a green"></i> 
	                    					<span>595%</span>
	                    				</p>
	                    			</div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-2">
	                    		<div class="white-box">
	                    			<h5><span>Organic vs Paid Likes</span></h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-4">
	                    		<div class="white-box">
	                    			<h5><span>Gender</span></h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-2">
	                    		<div class="white-box x2">
	                    			<h5>
	                    				<span>
	                    					Audience Growth
	                    					<i uk-tooltip="title: Audience Growth" class="fa fa-info-circle" title="" aria-expanded="false"></i>
	                    				</span>
	                    				<strong>
	                    					216K 
	                    					<cite>
	                    						<i class="icon ion-arrow-up-a green"></i> 
		                    					<span>1%</span>
		                    				</cite>
	                    				</strong>
	                    			</h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-2">
	                    		<div class="white-box x2">
	                    			<h5>
	                    				<span>
	                    					Age
	                    					<i uk-tooltip="title: Age" class="fa fa-info-circle" title="" aria-expanded="false"></i>
	                    				</span>
	                    			</h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box">
                                	<h5>
                                		<span>
                                			Top Countries
	                    					<i uk-tooltip="title: Top Countries" class="fa fa-info-circle" title="" aria-expanded="false"></i>
		                    			</span>
		                    		</h5>
                                	<div class="table-data">
		                                <table>
		                                    <tbody>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box">
                                	<h5>
                                		<span>
                                			Top Cities
	                    					<i uk-tooltip="title: Top Cities" class="fa fa-info-circle" title="" aria-expanded="false"></i>
		                    			</span>
		                    		</h5>                                	
		                            <div class="table-data">
		                                <table>
		                                    <tbody>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box">
                                	<h5>
                                		<span>
                                			Top Languages
	                    					<i uk-tooltip="title: Top Languages" class="fa fa-info-circle" title="" aria-expanded="false"></i>
		                    			</span>
		                    		</h5>
		                            <div class="table-data">
		                                <table>
		                                    <tbody>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                </div>
		            </div>
    			</div>
    			<div class="single">
    				<h2><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-engagement-icon.png" alt> Engagement</h2>
    				<div class="grid-tab">
		                <div uk-grid class="uk-grid">
		                    <div class="uk-width-3-4">
	                    		<div class="white-box">
	                    			<h5>
	                    				<span>
	                    					Audience Engagement
	                    					<i uk-tooltip="title: Audience Growth" class="fa fa-info-circle" title="" aria-expanded="false"></i>
	                    				</span>
	                    				<strong>
	                    					216K 
	                    					<cite>
	                    						<i class="icon ion-arrow-up-a green"></i> 
		                    					<span>1%</span>
		                    				</cite>
	                    				</strong>
	                    			</h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-4">
	                    		<div class="white-box">
	                    			<h5><span>Engagements</span></h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-2">
	                    		<div class="white-box x2">
	                    			<h5>
	                    				<span>
	                    					Gender
	                    					<i uk-tooltip="title: Audience Growth" class="fa fa-info-circle" title="" aria-expanded="false"></i>
	                    				</span>
	                    			</h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-2">
	                    		<div class="white-box x2">
	                    			<h5>
	                    				<span>
	                    					Age
	                    					<i uk-tooltip="title: Age" class="fa fa-info-circle" title="" aria-expanded="false"></i>
	                    				</span>
	                    			</h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box">
                                	<h5>
                                		<span>
                                			Top Countries
	                    					<i uk-tooltip="title: Top Countries" class="fa fa-info-circle" title="" aria-expanded="false"></i>
		                    			</span>
		                    		</h5>
                                	<div class="table-data">
		                                <table>
		                                    <tbody>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box">
                                	<h5>
                                		<span>
                                			Top Cities
	                    					<i uk-tooltip="title: Top Cities" class="fa fa-info-circle" title="" aria-expanded="false"></i>
		                    			</span>
		                    		</h5>                                	
		                            <div class="table-data">
		                                <table>
		                                    <tbody>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box">
                                	<h5>
                                		<span>
                                			Top Languages
	                    					<i uk-tooltip="title: Top Languages" class="fa fa-info-circle" title="" aria-expanded="false"></i>
		                    			</span>
		                    		</h5>
		                            <div class="table-data">
		                                <table>
		                                    <tbody>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                </div>
		            </div>
    			</div>
    			<div class="single">
    				<h2><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-reach-icon.png" alt> Reach</h2>
    				<div class="grid-tab">
		                <div uk-grid class="uk-grid">
		                    <div class="uk-width-1-2">
	                    		<div class="white-box">
	                    			<h5>
	                    				<span>
	                    					Total Reach
	                    					<i uk-tooltip="title: Audience Growth" class="fa fa-info-circle" title="" aria-expanded="false"></i>
	                    				</span>
	                    				<strong>3,152</strong>
	                    			</h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-4">
	                    		<div class="white-box">
	                    			<h5>
	                    				<span>
	                    					Organic vs Paid Reach
	                    					<i uk-tooltip="title: Organic vs Paid Reach" class="fa fa-info-circle" title="" aria-expanded="false"></i>
	                    				</span>
	                    			</h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-4">
	                    		<div class="white-box">
	                    			<h5>
	                    				<span>
	                    					Video Views
	                    					<i uk-tooltip="title: Video Views" class="fa fa-info-circle" title="" aria-expanded="false"></i>
	                    				</span>
	                    			</h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-2">
	                    		<div class="white-box x2">
	                    			<h5>
	                    				<span>
	                    					Gender
	                    					<i uk-tooltip="title: Age" class="fa fa-info-circle" title="" aria-expanded="false"></i>
	                    				</span>
	                    			</h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-2">
	                    		<div class="white-box x2">
	                    			<h5>
	                    				<span>
	                    					Age
	                    					<i uk-tooltip="title: Age" class="fa fa-info-circle" title="" aria-expanded="false"></i>
	                    				</span>
	                    			</h5>
                                    <div class="chart"></div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box">
                                	<h5>
                                		<span>
                                			Top Countries
	                    					<i uk-tooltip="title: Top Countries" class="fa fa-info-circle" title="" aria-expanded="false"></i>
		                    			</span>
		                    		</h5>
                                	<div class="table-data">
		                                <table>
		                                    <tbody>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                        <tr>
		                                            <td>
		                                                <span>
		                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
		                                                </span>
		                                            </td>
		                                            <td>1,590</td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box">
                                	<h5>
                                		<span>
                                			Top Cities
	                    					<i uk-tooltip="title: Top Cities" class="fa fa-info-circle" title="" aria-expanded="false"></i>
		                    			</span>
		                    		</h5>                                	
		                            <div class="table-data">
		                                <table>
		                                    <tbody>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                        <tr>
		                                            <td>Leaport</td>
		                                            <td>1,600</td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                    <div class="uk-width-1-3">
	                    		<div class="white-box">
                                	<h5>
                                		<span>
                                			Top Languages
	                    					<i uk-tooltip="title: Top Languages" class="fa fa-info-circle" title="" aria-expanded="false"></i>
		                    			</span>
		                    		</h5>
		                            <div class="table-data">
		                                <table>
		                                    <tbody>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                        <tr>
		                                            <td>English (US) (Canada)</td>
		                                            <td>1,489</td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </div>
	                    		</div>
		                    </div>
		                </div>
		            </div>
    			</div>
    			<div uk-grid class="uk-grid">
    				<div class="uk-width-3-5">
    					<div class="single">
    						<h2><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-posts-icon.png" alt> Posts</h2>    						
                    		<div class="white-box post-listing">
                    			<div class="single-post">
                    				<figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-post-attachment.jpg" alt=""></figure>
                    				<div class="post-info">
                    					<div class="post-head">
		                            		<figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/placeholder-user.png" alt></figure>
                    						<h6>
                    							<big>Daimler Double Six</big>
                    							<small>@Daimler Double Six</small>
                    							<span>Jul 17, 2022</span>
                    						</h6>
                    						<a href="#" class="btn blue-btn">Comment</a>
                    					</div>
                    					<p>It is time to take inspiration to the next level. desire is the antithesis of science.</p>
                    					<ul>
                    						<li>
                    							<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-reach-icon-small.png" alt>
                    							<span>232</span> Reach
                    						</li>
                    						<li>
                    							<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-likes-icon-small.png" alt>
                    							<span>1654</span> Likes
                    						</li>
                    						<li>
                    							<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-click-icon.png" alt>
                    							<span>234</span> Clicks
                    						</li>
                    					</ul>
                    				</div>
                    			</div>
                    			<div class="single-post">
                    				<figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-post-attachment.jpg" alt=""></figure>
                    				<div class="post-info">
                    					<div class="post-head">
		                            		<figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/placeholder-user.png" alt></figure>
                    						<h6>
                    							<big>Daimler Double Six</big>
                    							<small>@Daimler Double Six</small>
                    							<span>Jul 17, 2022</span>
                    						</h6>
                    						<a href="#" class="btn blue-btn">Comment</a>
                    					</div>
                    					<p>It is time to take inspiration to the next level. desire is the antithesis of science.</p>
                    					<ul>
                    						<li>
                    							<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-reach-icon-small.png" alt>
                    							<span>232</span> Reach
                    						</li>
                    						<li>
                    							<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-likes-icon-small.png" alt>
                    							<span>1654</span> Likes
                    						</li>
                    						<li>
                    							<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-click-icon.png" alt>
                    							<span>234</span> Clicks
                    						</li>
                    					</ul>
                    				</div>
                    			</div>
                    			<div class="single-post">
                    				<figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-post-attachment.jpg" alt=""></figure>
                    				<div class="post-info">
                    					<div class="post-head">
		                            		<figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/placeholder-user.png" alt></figure>
                    						<h6>
                    							<big>Daimler Double Six</big>
                    							<small>@Daimler Double Six</small>
                    							<span>Jul 17, 2022</span>
                    						</h6>
                    						<a href="#" class="btn blue-btn">Comment</a>
                    					</div>
                    					<p>It is time to take inspiration to the next level. desire is the antithesis of science.</p>
                    					<ul>
                    						<li>
                    							<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-reach-icon-small.png" alt>
                    							<span>232</span> Reach
                    						</li>
                    						<li>
                    							<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-likes-icon-small.png" alt>
                    							<span>1654</span> Likes
                    						</li>
                    						<li>
                    							<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-click-icon.png" alt>
                    							<span>234</span> Clicks
                    						</li>
                    					</ul>
                    				</div>
                    			</div>
                    			<div class="single-post">
                    				<figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-post-attachment.jpg" alt=""></figure>
                    				<div class="post-info">
                    					<div class="post-head">
		                            		<figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/placeholder-user.png" alt></figure>
                    						<h6>
                    							<big>Daimler Double Six</big>
                    							<small>@Daimler Double Six</small>
                    							<span>Jul 17, 2022</span>
                    						</h6>
                    						<a href="#" class="btn blue-btn">Comment</a>
                    					</div>
                    					<p>It is time to take inspiration to the next level. desire is the antithesis of science.</p>
                    					<ul>
                    						<li>
                    							<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-reach-icon-small.png" alt>
                    							<span>232</span> Reach
                    						</li>
                    						<li>
                    							<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-likes-icon-small.png" alt>
                    							<span>1654</span> Likes
                    						</li>
                    						<li>
                    							<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-click-icon.png" alt>
                    							<span>234</span> Clicks
                    						</li>
                    					</ul>
                    				</div>
                    			</div>
                    		</div>
    					</div>
    				</div>    				
    				<div class="uk-width-2-5">
    					<div class="single">
    						<h2><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/social-reviews-icon.png" alt> Reviews</h2>    						
                    		<div class="white-box reviews-listing">
                    			<div class="single-review">
		                            <figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/placeholder-user.png" alt></figure>
		                            <h6>
		                            	Arlene Stamm
                                		<span class="green">Recommended</span>
		                            </h6>
		                            <p>Jun 17, 2022</p>
		                            <div class="review-rating">
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                            </div>
		                            <div class="body">
		                                <p>Would definitely go again!</p>
		                            </div>
                    			</div>
                    			<div class="single-review">
		                            <figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/placeholder-user.png" alt></figure>
		                            <h6>
		                            	Arlene Stamm
                                		<span class="green">Recommended</span>
		                            </h6>
		                            <p>Jun 17, 2022</p>
		                            <div class="review-rating">
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                            </div>
		                            <div class="body">
		                                <p>Would definitely go again!</p>
		                            </div>
                    			</div>
                    			<div class="single-review">
		                            <figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/placeholder-user.png" alt></figure>
		                            <h6>
		                            	Arlene Stamm
                                		<span class="green">Recommended</span>
		                            </h6>
		                            <p>Jun 17, 2022</p>
		                            <div class="review-rating">
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                            </div>
		                            <div class="body">
		                                <p>Would definitely go again!</p>
		                            </div>
                    			</div>
                    			<div class="single-review">
		                            <figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/placeholder-user.png" alt></figure>
		                            <h6>
		                            	Arlene Stamm
                                		<span class="green">Recommended</span>
		                            </h6>
		                            <p>Jun 17, 2022</p>
		                            <div class="review-rating">
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                            </div>
		                            <div class="body">
		                                <p>Would definitely go again!</p>
		                            </div>
                    			</div>
                    			<div class="single-review">
		                            <figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/placeholder-user.png" alt></figure>
		                            <h6>
		                            	Arlene Stamm
                                		<span class="green">Recommended</span>
		                            </h6>
		                            <p>Jun 17, 2022</p>
		                            <div class="review-rating">
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                            </div>
		                            <div class="body">
		                                <p>Would definitely go again!</p>
		                            </div>
                    			</div>
                    			<div class="single-review">
		                            <figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/placeholder-user.png" alt></figure>
		                            <h6>
		                            	Arlene Stamm
                                		<span class="green">Recommended</span>
		                            </h6>
		                            <p>Jun 17, 2022</p>
		                            <div class="review-rating">
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                            </div>
		                            <div class="body">
		                                <p>Would definitely go again!</p>
		                            </div>
                    			</div>
                    			<div class="single-review">
		                            <figure><img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/placeholder-user.png" alt></figure>
		                            <h6>
		                            	Arlene Stamm
                                		<span class="green">Recommended</span>
		                            </h6>
		                            <p>Jun 17, 2022</p>
		                            <div class="review-rating">
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                                <i class="fa fa-star active"></i>
		                            </div>
		                            <div class="body">
		                                <p>Would definitely go again!</p>
		                            </div>
                    			</div>
                    		</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

@endsection