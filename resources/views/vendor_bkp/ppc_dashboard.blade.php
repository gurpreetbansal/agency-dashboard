@extends('layouts.vendor_layout')
@section('content')
<div id="myOverlay"></div>

<ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">

<?php
$types = array();
if(isset($getGoogleAds) && !empty($getGoogleAds)){
	$types = explode(',',$getGoogleAds->dashboard_type);
}
?>
<?php
 if (in_array(1, $types)){
?>
		<li class="nav-item">
			<a role="tab" class="nav-link <?php if (Request::is('campaigndetail') || Request::is('campaigndetail/*')) { echo 'active';}?>" href="{{url('/campaigndetail/'.$campaign_id)}}">
				<span>SEO Dashboard</span>
			</a>
		</li>
<?php }
if(in_array(2, $types)){
		?>

		<li class="nav-item">
			<a role="tab" class="nav-link <?php if (Request::is('ppc-dashboard') || Request::is('ppc-dashboard/*')) { echo 'active';}?>" href="{{url('/ppc-dashboard/'.$campaign_id)}}">
				<span>PPC Dashboard</span>
			</a>
		</li>
<?php } ?>
</ul>


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
<input type="hidden" class="account_id" value="{{$account_id}}">
<input type="hidden" class="today" value="{{$today}}">
<input type="hidden" class="currency_code" value="{{$currency_code}}">
<input type="hidden" class="campaign_id" value="{{$campaign_id}}">

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
<script type="text/javascript">
        var base_url = "<?php echo url('/') ?>";
        $(function () {
            var table = $('#google_ads_campaigns').DataTable({
                processing: true,
                serverSide: true,
				async: false,
                "deferRender": true,
                'ajax': {
                    'url': base_url + '/ajaxAdsCampaign',
					 'data': function (data) {
                        data.today = $('.today').val();
                        data.account_id = $('.account_id').val();
                        data.currency_code = $('.currency_code').val();

                    }
                },
                columns: [
                    {data: 'campaign_name', name: 'campaign_name', "orderable": false},
                    {data: 'impressions', name: 'impressions', "orderable": false},
                    {data: 'clicks', name: 'clicks', "orderable": false},
                    {data: 'ctr', name: 'ctr', "orderable": false},
                    {data: 'cost', name: 'cost', "orderable": false},
                    {data: 'conversions', name: 'conversions', "orderable": false}
                ]
            });

		
		$('#google_ads_keywords').DataTable({
			"destroy": true,
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdsKeywords',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                    {data: 'keywords', name: 'keywords', "orderable": false},
                    {data: 'impressions', name: 'impressions', "orderable": false},
                    {data: 'clicks', name: 'clicks', "orderable": false},
                    {data: 'ctr', name: 'ctr', "orderable": false},
                    {data: 'cost', name: 'cost', "orderable": false},
                    {data: 'conversions', name: 'conversions', "orderable": false}
                ]
		});	
		
	
       
		$('#google_ads').DataTable({
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdsData',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                   {data: 'ad', name: 'ad', "orderable": false},
                    {data: 'ad_type', name: 'ad_type', "orderable": false},
                    {data: 'impressions', name: 'impressions', "orderable": false},
                    {data: 'clicks', name: 'clicks', "orderable": false},
                    {data: 'ctr', name: 'ctr', "orderable": false},
                    {data: 'cost', name: 'cost', "orderable": false},
                    {data: 'conversions', name: 'conversions', "orderable": false}
                ]
		});	
		
		
		$('#google_ad_groups').DataTable({
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdGroupsData',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                    {data: 'ad_group', name: 'ad_group', "orderable": false},
                    {data: 'impressions', name: 'impressions', "orderable": false},
                    {data: 'clicks', name: 'clicks', "orderable": false},
                    {data: 'ctr', name: 'ctr', "orderable": false},
                    {data: 'cost', name: 'cost', "orderable": false},
                    {data: 'conversions', name: 'conversions', "orderable": false}
                ]
		});	
		
		$('#google_ad_performance_network').DataTable({
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdPerformanceNetwork',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                    {data: 'publisher_by_network', "orderable": false},
                    {data: 'impressions', "orderable": false},
                    {data: 'clicks', "orderable": false},
                    {data: 'ctr', "orderable": false},
                    {data: 'cost', "orderable": false},
                    {data: 'conversions', "orderable": false}
                ]
		});	
		
		
		
		$('#google_ad_performance_device').DataTable({
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdPerformanceDevice',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                    {data: 'device', "orderable": false},
                    {data: 'impressions', "orderable": false},
                    {data: 'clicks', "orderable": false},
                    {data: 'ctr', "orderable": false},
                    {data: 'cost', "orderable": false},
                    {data: 'conversions', "orderable": false}
                ]
		});	
		
		
		$('#google_ad_click_types').DataTable({
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdPerformanceClickTypes',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                    {data: 'click_type', "orderable": false},
                    {data: 'impressions', "orderable": false},
                    {data: 'clicks', "orderable": false},
                    {data: 'ctr', "orderable": false},
                    {data: 'cost', "orderable": false},
                    {data: 'conversions', "orderable": false}
                ]
		});	
		
		
		$('#google_ad_slots').DataTable({
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdPerformanceSlots',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                    {data: 'ad_slot', "orderable": false},
                    {data: 'impressions', "orderable": false},
                    {data: 'clicks', "orderable": false},
                    {data: 'ctr', "orderable": false},
                    {data: 'cost', "orderable": false},
                    {data: 'conversions', "orderable": false}
                ]
		});	


	

        });
		
	$(document).ready(function(){
			var campaign_id = $('.campaign_id').val();
			var account_id = $('.account_id').val();
			setTimeout(function(){
			 $.ajax({
                url:  BASE_URL + '/ajaxSaveInCsv',
				data:{campaign_id:campaign_id,account_id:account_id},
                type: 'get',
                success: function (response) {
					console.log(response);
                }
            });
			}, 5000);
	});
		 
    </script>
        
@endsection