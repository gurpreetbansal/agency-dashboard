<input type="hidden" value="{{$campaign_id}}" class="campaignID">
@if(isset($dashboardtype) && (empty($dashboardtype->google_analytics_id)) && (empty($dashboardtype->console_account_id)) && ($dashboardtype->seo_skip == 0))
<div class="wizardSection col-lg-12 col-md-12">
    @include('vendor.dashboards.wizard')
</div>
@else


<div class="tabs-animation">
    <div class="row widget-row">
        <div class="col-sm-12 col-md-6 col-xl-3">
            <div class="card  widget-chart">
                <div class="widget-chart-content">
                    <div class="icon-wrapper rounded campaign-ranking">
                        <img src="{{URL::asset('/public/vendor/images/OrganicKeywordsArrow.png')}}">
                    </div>
                    <div class="widget-numbers GoogleRanking">
                        <span>0</span>
                    </div>
                    <div class="widget-subheading fsize-1 pt-2 opacity-10 text-warning font-weight-bold">
                    Organic Keywords Ranking in Google</div>
                    <div class="widget-description opacity-8">
                        <span class="pr-1">
                            <i class="fa "></i>
                            <span class="pl-1 googleRankPosition"></span>
                        </span>
                        Keywords
                    </div>
                </div>
                <div class="widget-chart-wrapper">
                    <div id="dashboard-sparklines-simple-2" style="min-height: 120px;"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-3">
            <div class="card  widget-chart">
                <div class="widget-chart-content">
                    <div class="icon-wrapper rounded campaign-ranking">
                        <img src="{{URL::asset('/public/vendor/images/report-google-analytics-icon.png')}}">
                    </div>
                    <div class="widget-numbers"><span class="GoogleOrganicVisitors">0</span></div>
                    <div class="widget-subheading fsize-1 pt-2 opacity-10 text-danger font-weight-bold">
                        Organic Visitors from Google
                    </div>
                    <div class="widget-description opacity-8">
                        Traffic
                        <span class=" pl-1">
                            <i class="fa "></i>
                            <span class="pl-1 GoogleTraffic_growth"></span>
                        </span>
                    </div>
                </div>
                <div class="widget-chart-wrapper">
                    <div id="dashboard-sparklines-simple-1" style="min-height: 120px;"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-3">
            <div class="card  widget-chart">
                <div class="widget-chart-content">
                    <div class="icon-wrapper rounded campaign-ranking">
                        <img src="{{URL::asset('/public/vendor/images/backlink-icon.png')}}">
                    </div>
                    <div class="widget-numbers backlinks_total"><span>0</span></div>
                    <div class="widget-subheading fsize-1 pt-2 opacity-10 text-info font-weight-bold">
                    Referring Domains</div>
                    <div class="widget-description opacity-8">
                        Links
                        <span class=" pl-1">
                            <i class="fa "></i>
                            <span class="pl-1 backlinks_avg"></span>
                        </span>
                    </div>
                </div>
                <div class="widget-chart-wrapper">
                    <div id="dashboard-sparklines-simple-3" style="min-height: 120px;"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-3">
            <div class="card  widget-chart">
                <div class="widget-chart-content">
                    <div class="icon-wrapper rounded campaign-ranking">
                        <img src="{{URL::asset('/public/vendor/images/ReportGoogleAnaLyticsGoals.png')}}">
                    </div>
                    <div class="widget-numbers analyticsTotalGoal"><span>0</span></div>
                    <div class="widget-subheading fsize-1 pt-2 opacity-10 text-warning font-weight-bold">
                    Google Analytics Goals</div>
                    <div class="widget-description opacity-8">
                        Goals
                        <span class=" pl-1">
                            <i class="fa "></i>
                            <span class="pl-1 analyticsgoalResult"></span>
                        </span>
                    </div>
                </div>
                <div class="widget-chart-wrapper">
                    <div id="dashboard-sparklines-4" style="min-height: 120px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row height-100">
        <div class="col-md-7 mb-4">
           <div class="card ">
            <div class="card-header">
                <div class="media flex-wrap w-100 align-items-center">
                    <div class="media-body">
                        SUMMARY
                    </div>
                </div>
            </div>
            <div class="card-body summary-body">
                <?php
                if (!empty($summary) && isset($summary)) {
                    echo $summary->edit_section;
                }
                ?>

            </div>
        </div>
    </div>

    <div class="col-md-5 mb-4">
        <div class="card ">
            <div class="card-header">
                <div class="media flex-wrap w-100 align-items-center">
                    <div class="media-body">
                        Account Activity
                    </div>
                </div>
            </div>
            <div class="card-body">
             <div id="activity-timeline" class="account-timeline scrollpane" >

               <div class="trafficgrowthLoader" id="accountActivityLoader">
                <div class="ball-pulse">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>

        <div class="d-block text-center rm-border card-footer">
            <div class="" id="load-more">

              <div class="graph-loader account_activity" style=" display: none; "><img src="{{URL::asset('/public/vendor/images/new-keyword-loader.svg')}}"></div>

              <button type="button" data-value="0-5" class="load-more btn btn-primary">Load More <span class="text-white pl-2 opacity-3">
                <i class="fa fa-repeat"></i>
            </span></button>

        </div>
    </div>

</div>
</div>
</div>
</div>

<div class="row">
    <div class="col-md-12 col-lg-6 col-xl-12">
        <div class="mb-3 card">
            <div class="card-header-tab card-header-tab-animation card-header">
                <div class="card-header-title GoogleSearchConsoleSection">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{URL::asset('/public/vendor/images/google-search-console-logo.png')}}">
                            Search Console
                        </div>
                        <div class="col-md-9">

                            <div class="SearchConsoleBtns">
                                <div class="graph-loader searchConsole" style="display: none;"><img src="{{URL::asset('/public/vendor/images/new-keyword-loader.svg')}}"></div>
                                <button type="button" data-value="all" data-module="search_console" class=" btn btn-default searchConsole sc_section <?php if($selectedSearch == 0){ echo 'active'; }?>">All</button>
                                <button type="button" data-value="week" data-module="search_console" class=" btn btn-default  searchConsole sc_section <?php if($selectedSearch == 0.25){ echo 'active'; }?>">Last Week</button>
                                <button type="button" data-value="month" data-module="search_console" class=" btn btn-default searchConsole sc_section <?php if($selectedSearch == 1){ echo 'active'; }?>">One Month</button>
                                <button type="button" data-value="three" data-module="search_console" class=" btn btn-default searchConsole sc_section <?php if($selectedSearch == 3){ echo 'active'; }?>" >Three Month</button>
                                <button type="button" data-value="six" data-module="search_console" class="  btn btn-default searchConsole sc_section <?php if($selectedSearch == 6){ echo 'active'; }?>">Six Month</button>
                                <button type="button" data-value="nine" data-module="search_console" class="  btn btn-default searchConsole sc_section <?php if($selectedSearch == 9){ echo 'active'; }?>">Nine Month</button>
                                <button type="button" data-value="year" data-module="search_console" class="  btn btn-default  searchConsole sc_section <?php if($selectedSearch == 12){ echo 'active'; }?>">One Year</button>
                                <button type="button" data-value="twoyear" data-module="search_console" class="  btn btn-default searchConsole sc_section <?php if($selectedSearch == 24){ echo 'active'; }?>">Two Year</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane  active" id="tabs-eg-77">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="mb-3 ">
                                    <!-- <div class="card-body"> -->
                                        <div class="modalboxConsole">
                                            <canvas id="canvas-search-console" height="300"></canvas>
                                            <div class="modalbox" id="console_add" style="display: none;">
                                                <div class="box" id="console_reminder" style="<?php if($dashboardtype->google_console_id != '' && $dashboardtype->console_account_id=''){ echo "display: none"; } else{ echo "display: block"; }?>">
                                                    <h2>Google Console Account</h2>
                                                    <p>Please Provide Google Console Account For better tracking.</p>
                                                       <!--  <a href="#" pd-popup-open="PopupAddSearchConsoleAccount" class="btn"><button type="button" class="btn btn-default"> OK</button></a>
                                                        <button type="button" data-module="console_reminder" data-value="0" class="btn btn-default remind-later"> Reminder Me later</button> -->
                                                    </div>
                                                </div>

                                                <div class="searchConsoleLoader" id="console_loader" style="<?php if($dashboardtype->google_console_id != '' && $dashboardtype->console_account_id=''){ echo "display: block"; } else{ echo "display: none"; }?>">
                                                    <div class="ball-pulse">
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                </div>
                            <!-- <div class="scroll-area-sm">
                                <div class="scrollbar-container ps ps--active-y"> -->
                                    <!-- <ul class="rm-list-borders rm-list-borders-scroll list-group list-group-flush">
                                        <li class="list-group-item"> -->
                                            <div class="widget-content p-0">
                                                <div class="widget-content-wrapper">

                                                    <div class="console-tabs" style="width:100%">
                                                        <div class="card-body p-0">
                                                            <ul class="tabs-animated-shadow tabs-animated nav searchConsoleTabsSection">
                                                                <li class="nav-item mr-3">
                                                                    <button  class="btn btn-outline-primary tablinks active" onclick="openCity(event, 'Queries')">
                                                                        <span class="nav-text">Queries</span>
                                                                    </button>
                                                                </li>
                                                                <li class="nav-item mr-3">
                                                                    <button class="btn btn-outline-primary tablinks"  onclick="openCity(event, 'Pages')">
                                                                        <span class="nav-text">Pages</span>
                                                                    </button>
                                                                </li>
                                                                <li class="nav-item mr-3">
                                                                    <button  class="btn btn-outline-primary tablinks" onclick="openCity(event, 'Countries')">
                                                                        <span class="nav-text">Countries</span>
                                                                    </button>
                                                                </li>
                                                                <li class="nav-item mr-3">
                                                                    <button  class="btn btn-outline-primary tablinks" onclick="openCity(event, 'Devices')">
                                                                        <span class="nav-text">Devices</span>
                                                                    </button>
                                                                </li>
                                                            </ul>

                                                            <div class="tab-content">
                                                                <div class="tab-pane active tabcontent" id="Queries">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Query</th>
                                                                                <th>Clicks</th>
                                                                                <th>Impression</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="query_table">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="tab-pane tabcontent"  id="Pages">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Query</th>
                                                                                <th>Clicks</th>
                                                                                <th>Impression</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="pages_table">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="tab-pane tabcontent" id="Countries">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Query</th>
                                                                                <th>Clicks</th>
                                                                                <th>Impression</th>
                                                                                <th>CTR</th>
                                                                                <th>Position</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="country_table">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="tab-pane tabcontent" id="Devices">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Query</th>
                                                                                <th>Clicks</th>
                                                                                <th>Impression</th>
                                                                                <th>CTR</th>
                                                                                <th>Position</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="device_table">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--</div>-->
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- </li>
                                        </ul> -->
                                <!-- </div>
                                </div> -->
                            </div>
                        </div>
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
                                    @if($role_id != 4)
                                    <div class="custom-control custom-switch CompareTrafficGrowth">
                                        <input type="checkbox" class="custom-control-input analyticsGraphCompare" id="customSwitches" <?php if($comparison == 1) echo 'checked'?>>
                                        <label class="custom-control-label" for="customSwitches"></label>
                                    </div>
                                    @endif

                                    <div class="organicTrafficBtns">
                                        <div class="graph-loader organic_traffic" style="display: none;"><img src="{{URL::asset('/public/vendor/images/new-keyword-loader.svg')}}"></div>
                                        <!-- <button type="button" data-value="all" data-module="organic_traffic" class="graph_range  btn btn-default trafficSection <?php if($selected == 0){ echo 'active'; }?>">All</button>
                                        <button type="button" data-value="week" data-module="organic_traffic" class="graph_range  btn btn-default  trafficSection <?php if($selected == 0.25){ echo 'active'; }?>">Last Week</button> -->
                                        <button type="button" data-value="month" data-module="organic_traffic" class="graph_range  btn btn-default trafficSection <?php if($selected == 1){ echo 'active'; }?>">One Month</button>
                                        <button type="button" data-value="three" data-module="organic_traffic" class="graph_range  btn btn-default trafficSection <?php if($selected == 3){ echo 'active'; }?>" >Three Month</button>
                                        <button type="button" data-value="six" data-module="organic_traffic" class="graph_range  btn btn-default trafficSection <?php if($selected == 6){ echo 'active'; }?>">Six Month</button>
                                        <button type="button" data-value="nine" data-module="organic_traffic" class="graph_range  btn btn-default trafficSection <?php if($selected == 9){ echo 'active'; }?>">Nine Month</button>
                                        <button type="button" data-value="year" data-module="organic_traffic" class="graph_range  btn btn-default  trafficSection <?php if($selected == 12){ echo 'active'; }?>">One Year</button>
                                        <button type="button" data-value="twoyear" data-module="organic_traffic" class="graph_range  btn btn-default trafficSection <?php if($selected == 24){ echo 'active'; }?>">Two Year</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-xl-4">
                                <div class="card mb-3 widget-chart card-btm-border card-shadow-primary border-primary">
                                    <div class="widget-chart-content">
                                        <div class="widget-numbers">
                                            <span class="TrafficGrowth"></span>
                                            <span class="pr-1 ">
                                                <i class="fa "></i>
                                            </span>
                                        </div>
                                        <div class="widget-subheading fsize-1 pt-2 opacity-10 text-primary  font-weight-bold">Sessions</div>
                                        <div class="widget-description opacity-8">
                                            <span class="pr-1">
                                                <span class="pl-1 comparedTrafficGrowth"></span>
                                            </span>
                                        Organic Traffic</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-xl-4">
                                <div class="card mb-3 widget-chart card-btm-border card-shadow-success border-success">
                                    <div class="widget-chart-content">
                                        <div class="widget-numbers">
                                            <span class="TotalSessions"></span>
                                            <span class="pr-1">
                                                <i class="fa "></i>
                                            </span>
                                        </div>
                                        <div class="widget-subheading fsize-1 pt-2 opacity-10 text-success  font-weight-bold">Users</div>
                                        <div class="widget-description opacity-8">
                                            <span class=" pr-1">
                                                <span class="pl-1 comparedUsers"></span>
                                            </span>Organic Traffic</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-xl-4">
                                    <div class="card mb-3 widget-chart card-btm-border card-shadow-warning border-warning">
                                        <div class="widget-chart-content">
                                            <div class="widget-numbers">
                                                <span class="TotalPageViews"></span>
                                                <span class="pr-1">
                                                    <i class="fa "></i>
                                                </span>
                                            </div>
                                            <div class="widget-subheading fsize-1 pt-2 opacity-10 text-warning  font-weight-bold">Pageviews</div>
                                            <div class="widget-description opacity-8">
                                                <span class=" pr-1">
                                                    <span class="pl-1 comparedPageViews"> </span>
                                                </span>
                                            Organic Traffic</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-xl-12">
                                <!-- <div class=" card">
                                    <div class="card-body"> -->
                                        <!-- <canvas id="canvas-traffic-growth" height="300"></canvas> -->
                                        <div class="modalboxAnalytic">
                                            <canvas id="canvas-traffic-growth" height="300"></canvas>
                                            <div class="modalbox" id="analatic_add" style="display: none;">
                                                <div class="box" id="analatic_reminder" style="<?php if($dashboardtype->google_account_id != ''){ echo "display: none"; } else{ echo "display: block"; }?>">
                                                    <h2>Google Analytics Account</h2>
                                                    <p>Please Provide Google Analytics Account For better tracking.</p>
                                                    <!-- <a href="#" pd-popup-open="PopupAddGoogleAnalyticsAccount" class="btn"><button type="button" class="btn btn-default"> OK</button></a>
                                                        <button type="button" data-module="analatic_reminder" data-value="0" class="btn btn-default remind-later"> Reminder Me later</button> -->
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
                                    <!-- </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-6 col-xl-12">
                    <div class="mb-3 card">
                        <div class="card-header-tab card-header-tab-animation card-header">
                            <div class="card-header-title OrganicTrafficGrowthSection">
                                <img src="{{URL::asset('/public/vendor/images/OrganicKeywordsArrow.png')}}">
                                Organic Keyword Growth
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 col-xl-4">
                                    <div class="card mb-3 widget-chart card-btm-border card-shadow-info border-info">
                                        <div class="widget-chart-content">
                                            <div class="icon-wrapper rounded campaign-ranking">
                                                <img src="{{URL::asset('/public/vendor/images/roger_and_logo_moz-min.png')}}">
                                            </div>
                                            <div class="widget-numbers">
                                                <span>{{$moz_data->page_authority??'0'}}/100</span>
                                            </div>
                                            <div class="widget-subheading fsize-1 pt-2 opacity-10 text-info font-weight-bold">
                                                Page Authority
                                            </div>
                                        <!-- <div class="row">
                                            <div class="col-md-6">
                                                <div class="widget-numbers">
                                                    <span>{{$moz_data->page_authority??'0'}}/100</span>
                                                </div>
                                                <div class="widget-subheading fsize-1 pt-2 opacity-10 text-warning font-weight-bold">Page Authority</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mozImage">
                                                    <img src="{{URL::asset('/public/vendor/images/roger_and_logo_moz-min.png')}}">
                                                </div>
                                            </div>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-4">
                                <div class="card mb-3 widget-chart card-btm-border card-shadow-alternate border-alternate">
                                    <div class="widget-chart-content">
                                        <div class="icon-wrapper rounded campaign-ranking">
                                            <img src="{{URL::asset('/public/vendor/images/roger_and_logo_moz-min.png')}}">
                                        </div>
                                        <div class="widget-numbers">
                                            <span>{{$moz_data->domain_authority??'0'}}/100</span>
                                        </div>
                                        <div class="widget-subheading fsize-1 pt-2 opacity-10 text-alternate font-weight-bold">
                                            Domain Authority
                                        </div>
                                        <!-- <div class="row">
                                            <div class="col-md-6">
                                                <div class="widget-numbers">
                                                    <span>{{$moz_data->domain_authority??'0'}}/100</span>
                                                </div>
                                                <div class="widget-subheading fsize-1 pt-2 opacity-10 text-danger  font-weight-bold">Domain Authority</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mozImage">
                                                    <img src="{{URL::asset('/public/vendor/images/roger_and_logo_moz-min.png')}}">
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--chart/graph section start-->
                        <div id="c3chartline" class="keyword_hide" style="opacity:1;width: 50%;float: left;">
                            <canvas id="keywordsCanvas" width="100" height="40" > </canvas>
                        </div>
                        <div id="canvas-holder" style="width:50%; float: left;">
                            <canvas id="keywordsCanvasChartArea" width="100" height="40" ></canvas>
                        </div>
                        <!--chart/graph section end-->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-6 col-xl-12">
                <div class="mb-3 card">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title custom-title">
                            <!--i class="header-icon lnr-apartment icon-gradient bg-love-kiss"></i-->
                            <button type="button" id="multipleUpdate" class="refresh-btn">
                                <i class="pe-7s-refresh"></i>
                                <img src="{{URL::asset('/public/vendor/images/new-keyword-loader.svg')}}">
                            </button>
                            Live Keyword Tracking
                        </br>
                        <small id="yeskws_txt" ></small>
                        <small id="keywords_update" style="display: none;" >
                            <span id="strating">0/</span>
                            <span id="total_keywords">0</span>
                            <span> Keyword(s)</span>
                        </small>
                    </div>
                    @if($role_id != 4)
                    @if($keywords_left <= 0)
                    <span class="LiveKeywordsLeft">No keywords left, Upgrade your package.</span>
                
                 @endif
                 <div class="btn-actions-pane-right">
                    <div class="nav">
                        <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#liveKeywordTracking">Add New Keywords</button>
                        <div class="d-inline-block dropdown dashboardIcon">
                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dashboardBtnToggle">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="pe-7s-menu btn-icon-wrapper"></i>
                                </span>
                            </button>
                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right KeywirdSEC">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" class="nav-link" id="updateKeywords" data-toggle="modal" data-target="#LocationModalKeyword">
                                            <i class="nav-link-icon pe-7s-refresh"></i>
                                            <span>
                                                Update Keywords
                                            </span>
                                            <div class="ml-auto badge badge-pill badge-secondary"></div>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="javascript:void(0);" class="nav-link" id="multipleDelete">
                                            <i class="nav-link-icon pe-7s-trash"></i>
                                            <span>
                                                Delete Keywords
                                            </span>
                                            <div class="ml-auto badge badge-pill badge-secondary"></div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="keyword-progress-bar progress" style="display: none;margin-bottom: 15px;width: 50%;">
                        <div class="progress-bar progress-bar-striped active" style=""></div>
                    </div>
                    <div id="liveKeywordTracking" class="collapse">
                        <form id="addNewKeyword" name="keyword" role="form" method="post">
                            <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Domain Url</label>
                                        <input type="text" name="domain_url" class="form-control domain_url" required>
                                        <span class="error errorStyle"><p id="domain_url_error"></p></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Keywords Ranking</label>
                                        <textarea name="keyword_ranking" class="form-control keyword_ranking" placeholder="Enter one keyword per line" required></textarea>
                                        <span class="error errorStyle"><p id="keyword_ranking_error"></p></span>
                                    </div>
                                </div>

                                <div class="row">
                                   <div class="col-md-6 form-group">
                                    <label>Search Engine Region</label>
                                    <select name="search_engine_region" class="select form-control regions" required>
                                        <option value="">-Select-</option>
                                        <?php
                                        if(!empty($getRegions) && isset($getRegions) && count($getRegions)>0){
                                            foreach($getRegions as $region){?>
                                                <option value="{{$region->long_name}}" {{$region->short_name=='us'?'selected':''}}>{{$region->long_name}}</option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <span class="error errorStyle"><p id="regions_error"></p></span>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Tracking Options</label>
                                    <select name="tracking_options" class="select form-control tracking_options" required>
                                        <option value="">-Select-</option>
                                        <option value="desktop">Desktop</option>
                                        <option value="mobile">Mobile</option>
                                    </select>
                                    <span class="error errorStyle"><p id="tracking_options_error"></p></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Language</label>
                                    <select name="language" class="select form-control language" required>
                                        <option value="">-Select-</option>
                                        <option value="English" selected>English</option>
                                        <option value="French">French</option>
                                        <option value="Spanish">Spanish</option>
                                        <option value="Arabic">Arabic</option>
                                        <option value="Hebrew">Hebrew</option>
                                        <option value="Chinese">Chinese</option>
                                        <option value="Thailand">Thailand</option>
                                        <option value="Dutch">Dutch</option>
                                        <option value="Russian">Russian</option>
                                    </select>
                                    <span class="error errorStyle"><p id="language_error"></p></span>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Locations</label>
                                 
                                  <!--   <select class="select form-control dfs_locations" name="locations" required>
                                    </select> -->
                                    <input id="autocomplete_search" type="text" size="50" placeholder="Enter a location" autocomplete="on" name="locations" class="form-control " required>
                                    <input id="lat" type="hidden" name="lat">
                                    <input id="long" type="hidden" name="long">
                                    <span class="error errorStyle"><p id="dfs_locations_error"></p></span>
                                </div>
                            </div>
                        
                        </div>
                        <button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg" id="add_new_keyword" type="button">Submit</button>
                    </form>
                </div>
                <div class="live-Keyword-tracking hide" id="liveKeywordTrackingChart">
                    <button id="close-graph"><i class="fa fa-times fa-2x" aria-hidden="true"></i></button>
                    <div id="keywordchartConatiner"></div>
                    <div  style="margin-bottom: 22px;">
                        <a href="javascript:void(0);" class="border-0 btn-pill btn-wide btn-transition active btn btn-outline-primary" onclick="drawChartGraph({{$campaign_id}}, '-60  day');return false;" >60days</a>
                        <a href="javascript:void(0);" class="border-0 btn-pill btn-wide btn-transition active btn btn-outline-primary" onclick="drawChartGraph({{$campaign_id}}, '-90  day');return false;" >90days</a>
                        <a href="javascript:void(0);" class="border-0 btn-pill btn-wide btn-transition active btn btn-outline-primary" onclick="drawChartGraph({{$campaign_id}}, '-180  day');return false;" >180days</a>
                        <a href="javascript:void(0);" class="border-0 btn-pill btn-wide btn-transition active btn btn-outline-primary" onclick="drawChartGraph({{$campaign_id}}, '-365  day');return false;" >Last Year</a>
                        <a href="javascript:void(0);" class="border-0 btn-pill btn-wide btn-transition active btn btn-outline-primary" >All Time</a>
                    </div>
                </div>
                <!--live keyword tracking table-->
                <div class="table-responsive">
                    <table class="table table-bordered data-table" id="LiveKeywordTrackingTable">
                        <thead>
                            <tr>
                                <th>Keyword</th>
                                <th>Start</th>
                                <th>Page</th>
                                <th><img src="{{URL::asset('/public/vendor/images/google-logo-icon.png')}}" class="googleIcon"></th>
                                <th>1 Day</th>
                                <th>7 Days</th>
                                <th>30 Days</th>
                                <th>Life</th>
                                <th>Comp</th>
                                <th>Search Vol</th>
                                <th>Date Added</th>
                                <th>Url</th>
                                @if($role_id != 4)
                                <th><div class="my-checkbox">
                                    <label><input type="checkbox" name="select_all" class="selectall"/><span class="checkbox"></span></label>
                                </th>
                                @else
                                <th></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- </div> -->
<div class="row">
    <div class="col-md-12 col-lg-6 col-xl-12">
        <div class="mb-3 card">
            <div class="card-header-tab card-header-tab-animation card-header">
                <div class="card-header-title extraOrganicKeywordSection">
                    <img src="{{URL::asset('/public/vendor/images/data-analytics.png')}}">
                    Extra Organic Keywords
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table" id="google_organic_keywords">
                        <thead>
                            <tr>
                                <th>Keywords</th>
                                <th>Current Position</th>
                                <th>Traffic Percentage</th>
                                <th>Cpc (USD)</th>
                                <th>Average Volume</th>
                                <th>URL</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-lg-6 col-xl-12">
        <div class="mb-3 card">
            <div class="card-header-tab card-header-tab-animation card-header">
                <div class="card-header-title BacklinkSection">
                    <img src="{{URL::asset('/public/vendor/images/backlink-icon.png')}}">
                    Backlink Profile
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table" id="backlink_profile">
                        <thead>
                            <tr>
                                <th>Referring Page</th>
                                <th>No Follow</th>
                                <th>Anchor & Backlink</th>
                                <th>Like Type</th>
                                <th>External Links</th>
                                <th>First Seen</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-lg-6 col-xl-12">
        <div class="mb-3 card">
            <div class="card-header-tab card-header-tab-animation card-header">
                <div class="card-header-title GAGoalCompletionSection">
                    <img src="{{URL::asset('/public/vendor/images/ReportGoogleAnaLyticsGoals-icon.png')}}">
                    Google Analytics Goal Completion
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table" id="googleAnalyticsGoalCompletion">
                        <thead>
                            <tr>
                                <th>Keyword</th>
                                <th>Sessions</th>
                                <th>New Users</th>
                                <th>Bounce Rate</th>
                                <th>Page/Session</th>
                                <th>Avg. Session Duration</th>
                                <th>Goal Conversion Rate</th>
                                <th>Goal Completions</th>
                                <th>Goal Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($googleAnalyticsCurrent['goalCompletionData'])){
                                foreach($googleAnalyticsCurrent['goalCompletionData']->getRows() as $goalData){
                                    ?>
                                    <tr>
                                        <td><?php echo $goalData[0];?></td>
                                        <td><?php echo $goalData[1];?></td>
                                        <td><?php echo $goalData[2];?></td>
                                        <td><?php echo number_format($goalData[3],2);?></td>
                                        <td><?php echo number_format($goalData[4],2);?></td>
                                        <td><?php echo number_format($goalData[5],2);?></td>
                                        <td><?php echo number_format($goalData[6],2);?></td>
                                        <td><?php echo $goalData[7];?></td>
                                        <td><?php echo $goalData[8];?></td>
                                    </tr>
                                    <?php
                                }
                            } else{
                                ?>
                                <tr ><td colspan="9" style="text-align:center;">No Data</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endif