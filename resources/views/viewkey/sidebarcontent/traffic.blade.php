<input type="hidden" value="{{$campaign_id}}" class="campaignID">
<div class="tabs-animation">


  <div class="row">
    <div class="col-sm-12 col-md-6 col-xl-4">
        <div class="card mb-3 widget-chart card-btm-border card-shadow-primary border-primary">
            <div class="widget-chart-content">
                <div class="widget-numbers">
                    <span class="TrafficGrowth">0%</span>
                    <span class="pr-1 ">
                        <i class="fa "></i>
                    </span>
                </div>
                <div class="widget-subheading fsize-1 pt-2 opacity-10 text-primary  font-weight-bold">Sessions</div>
                <div class="widget-description opacity-8">
                    <span class="pr-1">
                        <span class="pl-1 comparedTrafficGrowth">0</span>
                    </span>
                Organic Traffic</div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-xl-4">
        <div class="card mb-3 widget-chart card-btm-border card-shadow-success border-success">
            <div class="widget-chart-content">
                <div class="widget-numbers">
                    <span class="TotalSessions">0%</span>
                    <span class="pr-1">
                        <i class="fa "></i>
                    </span>
                </div>
                <div class="widget-subheading fsize-1 pt-2 opacity-10 text-success  font-weight-bold">Users</div>
                <div class="widget-description opacity-8">
                    <span class=" pr-1">
                        <span class="pl-1 comparedUsers">0</span>
                    </span>Organic Traffic</div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4">
            <div class="card mb-3 widget-chart card-btm-border card-shadow-warning border-warning">
                <div class="widget-chart-content">
                    <div class="widget-numbers">
                        <span class="TotalPageViews">0%</span>
                        <span class="pr-1">
                            <i class="fa "></i>
                        </span>
                    </div>
                    <div class="widget-subheading fsize-1 pt-2 opacity-10 text-warning  font-weight-bold">Pageviews</div>
                    <div class="widget-description opacity-8">
                        <span class=" pr-1">
                            <span class="pl-1 comparedPageViews">0 </span>
                        </span>
                    Organic Traffic</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title organicTrafficSection">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="{{URL::asset('/public/vendor/images/google-analytics-logo.png')}}">
                                Organic Traffic Growth
                            </div>
                            <div class="col-md-9">

                                <div class="organicTrafficBtns">
                                    <div class="graph-loader organic_traffic" style="display: none;"><img src="{{URL::asset('/public/vendor/images/new-keyword-loader.svg')}}"></div>
                                    <button type="button" data-value="all" data-module="organic_traffic" class="graph_rangeViewKey  btn btn-default trafficSection <?php if($selected == 0){ echo 'active'; }?>">All</button>
                                    <button type="button" data-value="week" data-module="organic_traffic" class="graph_rangeViewKey  btn btn-default  trafficSection <?php if($selected == 0.25){ echo 'active'; }?>">Last Week</button>
                                    <button type="button" data-value="month" data-module="organic_traffic" class="graph_rangeViewKey  btn btn-default trafficSection <?php if($selected == 1){ echo 'active'; }?>">One Month</button>
                                    <button type="button" data-value="three" data-module="organic_traffic" class="graph_rangeViewKey  btn btn-default trafficSection <?php if($selected == 3){ echo 'active'; }?>" >Three Month</button>
                                    <button type="button" data-value="six" data-module="organic_traffic" class="graph_rangeViewKey  btn btn-default trafficSection <?php if($selected == 6){ echo 'active'; }?>">Six Month</button>
                                    <button type="button" data-value="nine" data-module="organic_traffic" class="graph_rangeViewKey  btn btn-default trafficSection <?php if($selected == 9){ echo 'active'; }?>">Nine Month</button>
                                    <button type="button" data-value="year" data-module="organic_traffic" class="graph_rangeViewKey  btn btn-default  trafficSection <?php if($selected == 12){ echo 'active'; }?>">One Year</button>
                                    <button type="button" data-value="twoyear" data-module="organic_traffic" class="graph_rangeViewKey  btn btn-default trafficSection <?php if($selected == 24){ echo 'active'; }?>">Two Year</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-xl-12">

                            <div class="modalboxAnalytic">
                                <canvas id="canvas-traffic-growth-viewkey" height="300"></canvas>
                                <div class="modalbox" id="analatic_add">
                                    <div class="box" id="analatic_reminder" style="<?php if($dashboardtype->google_account_id != ''){ echo "display: none"; } else{ echo "display: block"; }?>">
                                        <h2>Google Analytics Account</h2>
                                        <p>Please Provide Google Analytics Account For better tracking.</p>

                                    </div>
                                </div>

                                <div class="trafficgrowthLoader" id="traffic_loader" style="<?php if($dashboardtype->google_account_id != ''){ echo "display: block"; } else{ echo "display: none"; }?>">
                                    <div class="ball-pulse">
                                        <div></div>
                                        <div></div>
                                        <div></div>
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
</div>