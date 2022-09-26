<div id="myOverlay"></div>
<div class="tabs-animation">





<!--date range section start-->
 <div class="row">
	<div class="col-md-12 col-lg-6 col-xl-12">
		<div class="main-card mb-3 card">
			<div class="card-body">
				<h5 class="card-title">Select Date Range</h5>
				<div class="row">
				<div class="col-md-6">
					<label>Date Range</label>
					<input type="text" class="form-control" name="dateranges" >
				</div>
				
				</div>
				<input type="hidden" class="sd">
				<input type="hidden" class="ed">
				<input type="hidden" class="csd">
				<input type="hidden" class="ced">
				<!-- Default switch -->
				<div class="custom-control custom-switch">
				  <input type="checkbox" class="custom-control-input btn btn-primary" id="customSwitches">
				  <label class="custom-control-label" for="customSwitches">Compare</label>
				</div>
			
				<div class="compareSection" style="display:none;">
				<div class="row">	
				<div class="col-md-6">
					<label>Compare Date Range</label>		
					<input type="text" class="form-control" name="dateranges1" id="reportrange">
				</div>
		
				</div>
				</div>

				<button type="button" class="mb-2 mr-2 btn btn-gradient-info right" id="submitPpcDateRange">Submit</button>
			
			</div>
			
		</div>
	</div>
</div>
<!--date range section end-->
<div class="row">

        <div class="col-md-12 col-lg-6 col-xl-12">
		<div class="mb-3 card">
			<div class="card-header-tab card-header-tab-animation card-header">
				<div class="card-header-title">
					Google Ads - Summary  
					</br></br>
				<span class="dateSection"></span>
				</div>
				
			</div>
		<div class="card-body">
				<div class="main-card mb-3 card ppcDashboard">
			<!--loader-->
				<div id="myDiv">
				<div class="summaryloader" >
					<div class="line-scale">
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					</div>
				</div>
				</div>
			<!--loader-->
				<div class="grid-menu grid-menu-2col">
				<div class="no-gutters row">
				<div class="col-sm-3">
					<div class="widget-chart widget-chart-hover">
					<div class="adsIcon" style="width:24px;height:24px">
						<img src="https://cdn.cdn-marketing-reports.com/assets/img/dashboard/icon-service-gads.svg">
					</div>
					<div class="adsTitle">
						<h2>Impressions</h2>
						<h3>Ads</h3>
					</div>
					<div class="widget-numbers" id="impressions"></div>
				</div>
				</div>
				<div class="col-sm-3">
					<div class="widget-chart widget-chart-hover">
					<div class="adsIcon" style="width:24px;height:24px">
						<img src="https://cdn.cdn-marketing-reports.com/assets/img/dashboard/icon-service-gads.svg">
					</div>
					<div class="adsTitle">
						<h2>Cost</h2>
						<h3>Ads</h3>
					</div>
					<div class="widget-numbers" id="cost"></div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="widget-chart widget-chart-hover">
					<div class="adsIcon" style="width:24px;height:24px">
						<img src="https://cdn.cdn-marketing-reports.com/assets/img/dashboard/icon-service-gads.svg">
					</div>
					<div class="adsTitle">
						<h2>Clicks</h2>
						<h3>Ads</h3>
					</div>
					<div class="widget-numbers" id="clicks"></div>				
					</div>
				</div>
				<div class="col-sm-3">
					<div class="widget-chart widget-chart-hover br-br">
					<div class="adsIcon" style="width:24px;height:24px">
						<img src="https://cdn.cdn-marketing-reports.com/assets/img/dashboard/icon-service-gads.svg">
					</div>
					<div class="adsTitle">
						<h2>Average CPC</h2>
						<h3>Ads</h3>
					</div>
					<div class="widget-numbers" id="average_cpc"></div>				
				</div>
				</div>

				<div class="col-sm-3">
					<div class="widget-chart widget-chart-hover">
					<div class="adsIcon" style="width:24px;height:24px">
					<img src="https://cdn.cdn-marketing-reports.com/assets/img/dashboard/icon-service-gads.svg">
					</div>
					<div class="adsTitle">
					<h2>CTR</h2>
					<h3>Ads</h3>
					</div>
					<div class="widget-numbers" id="ctr"></div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="widget-chart widget-chart-hover">
					<div class="adsIcon" style="width:24px;height:24px">
						<img src="https://cdn.cdn-marketing-reports.com/assets/img/dashboard/icon-service-gads.svg">
					</div>
					<div class="adsTitle">
						<h2>Conversions</h2>
						<h3>Ads</h3>
					</div>
					<div class="widget-numbers" id="conversions"></div>				
					</div>
				</div>
				<div class="col-sm-3">
					<div class="widget-chart widget-chart-hover">
					<div class="adsIcon" style="width:24px;height:24px">
						<img src="https://cdn.cdn-marketing-reports.com/assets/img/dashboard/icon-service-gads.svg">
					</div>
					<div class="adsTitle">
						<h2>Conversion Rate</h2>
						<h3>Ads</h3>
					</div>
					<div class="widget-numbers" id="conversion_rate"></div>				
					</div>
				</div>
				<div class="col-sm-3">
					<div class="widget-chart widget-chart-hover br-br">
					<div class="adsIcon" style="width:24px;height:24px">
						<img src="https://cdn.cdn-marketing-reports.com/assets/img/dashboard/icon-service-gads.svg">
					</div>
					<div class="adsTitle">
						<h2>Cost Per Conversion</h2>
						<h3>Ads</h3>
					</div>
					<div class="widget-numbers" id="cost_per_conversion"></div>
					</div>
				</div>
				</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!--summary chart section start-->
 <div class="row">
	 <div class="col-md-12 col-lg-6 col-xl-12">
			<div class="mb-3 card">
				<div class="card-body">
<!--loader-->
				<div id="myDiv">
					<div class="summaryloader" >
						<div class="line-scale">
							<div></div>
							<div></div>
							<div></div>
							<div></div>
							<div></div>
						</div>
					</div>
				</div>
<!--loader-->
					<canvas id="canvas" height="300"></canvas>
				</div>
			</div>
	</div>
</div>
<!--summary chart section end-->



<!--Performance chart section start-->
 <div class="row">
	 <div class="col-md-12 col-lg-6 col-xl-12">
			<div class="mb-3 card">
				<div class="card-body">
<!--loader-->
	<div id="myDiv">
		<div class="summaryloader" >
			<div class="line-scale">
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>
		</div>
    </div>
<!--loader-->
					<canvas id="canvasperformance" height="300"></canvas>
				</div>
			</div>
	</div>
</div>
<!--Performance chart section end-->

<!--summary section end-->


<!--campaign section start-->
<div class="row">

        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"></i>
                        Campaigns
                    </div>

                </div>
                <div class="card-body">
<!--loader-->
				<div id="myDiv">
						<div class="summaryloader" >
							<div class="line-scale">
								<div></div>
								<div></div>
								<div></div>
								<div></div>
								<div></div>
							</div>
						</div>
					</div>
<!--loader-->
                    <table class="table table-bordered data-table" id="google_ads_campaigns">
                        <thead>
                            <tr>
                                <th>Campaign</th>
                                <th>Impressions</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                                <th>Cost</th>
                                <th>Conversions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
<!--campaign section end-->


<!--keyword section start-->
<div class="row">

        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"></i>
                        Keyword
                    </div>

                </div>
                <div class="card-body">
<div id="myDiv">
		<div class="summaryloader" >
			<div class="line-scale">
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                            </div>
		</div>
    </div>
                    <table class="table table-bordered data-table" id="google_ads_keywords">
                        <thead>
                            <tr>
                                <th>Keyword</th>
                                <th>Impressions</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                                <th>Cost</th>
                                <th>Conversions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
<!--keyword section end-->

<!--ads section start-->
<div class="row">

        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"></i>
                        Ads
                    </div>

                </div>
                <div class="card-body">
<div id="myDiv">
		<div class="summaryloader" >
			<div class="line-scale">
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                            </div>
		</div>
    </div>
                    <table class="table table-bordered data-table" id="google_ads">
                        <thead>
                            <tr>
                                <th>Ad</th>
                                <th>Ad Type</th>
                                <th>Impressions</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                                <th>Cost</th>
                                <th>Conversions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
<!--ads section end-->

<!--ad groups section start-->
<div class="row">

        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"></i>
                        Ad Groups
                    </div>

                </div>
                <div class="card-body">
<div id="myDiv">
		<div class="summaryloader" >
			<div class="line-scale">
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                            </div>
															</div>
			</div>
		
                    <table class="table table-bordered data-table" id="google_ad_groups">
                        <thead>
                            <tr>
                                <th>Ad Group</th>
                                <th>Impressions</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                                <th>Cost</th>
                                <th>Conversions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
<!--ad groups section end-->

<!--Performance networks section start-->
<div class="row">

        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"></i>
                        Performance Networks
                    </div>

                </div>
                <div class="card-body">
<div id="myDiv">
		<div class="summaryloader" >
			<div class="line-scale">
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                            </div>
		</div>
    </div>
                    <table class="table table-bordered data-table" id="google_ad_performance_network">
                        <thead>
                            <tr>
                                <th>Publisher By Network</th>
                                <th>Impressions</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                                <th>Cost</th>
                                <th>Conversions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
<!--Performance networks section end-->


<!--Performance device section start-->
<div class="row">

        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"></i>
                        Performance Device
                    </div>

                </div>
                <div class="card-body">
<div id="myDiv">
		<div class="summaryloader" >
			<div class="line-scale">
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                            </div>
		</div>
    </div>
                    <table class="table table-bordered data-table" id="google_ad_performance_device">
                        <thead>
                            <tr>
                                <th>Device</th>
                                <th>Impressions</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                                <th>Cost</th>
                                <th>Conversions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
<!--Performance device section end-->


<!--Performance device section start-->
<div class="row">

        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"></i>
                        Performance Click Type
                    </div>

                </div>
                <div class="card-body">
<div id="myDiv">
		<div class="summaryloader" >
			<div class="line-scale">
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                            </div>
		</div>
    </div>
                    <table class="table table-bordered data-table" id="google_ad_click_types">
                        <thead>
                            <tr>
                                <th>Click Type</th>
                                <th>Impressions</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                                <th>Cost</th>
                                <th>Conversions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
<!--Performance device section end-->


<!--Performance ad slots section start-->
<div class="row">

        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"></i>
                        Performance Ad Slots
                    </div>

                </div>
                <div class="card-body">
<div id="myDiv">
		<div class="summaryloader" >
			<div class="line-scale">
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                                <div></div>
                                                            </div>
		</div>
    </div>
                    <table class="table table-bordered data-table" id="google_ad_slots">
                        <thead>
                            <tr>
                                <th>Ad Slot</th>
                                <th>Impressions</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                                <th>Cost</th>
                                <th>Conversions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
<!--Performance ad slots section end-->

</div>
