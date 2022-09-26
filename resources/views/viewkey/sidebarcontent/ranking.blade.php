<input type="hidden" value="{{$campaign_id}}" class="campaignID">


<div class="tabs-animation">

    <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title GoogleSearchConsoleSection">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="{{URL::asset('/public/vendor/images/google-search-console-logo.png')}}">
                                Visibility In Google
                            </div>
                            <div class="col-md-9">

                                <div class="SearchConsoleBtns">
                                    <div class="graph-loader searchConsole" style="display: none;"><img src="{{URL::asset('/public/vendor/images/new-keyword-loader.svg')}}"></div>
                                    <button type="button" data-value="all" data-module="search_console" class=" btn btn-default searchConsoleRank sc_section <?php if($selectedSearch == 0){ echo 'active'; }?>">All</button>
                                    <button type="button" data-value="week" data-module="search_console" class=" btn btn-default  searchConsoleRank sc_section <?php if($selectedSearch == 0.25){ echo 'active'; }?>">Last Week</button>
                                    <button type="button" data-value="month" data-module="search_console" class=" btn btn-default searchConsoleRank sc_section <?php if($selectedSearch == 1){ echo 'active'; }?>">One Month</button>
                                    <button type="button" data-value="three" data-module="search_console" class=" btn btn-default searchConsoleRank sc_section <?php if($selectedSearch == 3){ echo 'active'; }?>" >Three Month</button>
                                    <button type="button" data-value="six" data-module="search_console" class="  btn btn-default searchConsoleRank sc_section <?php if($selectedSearch == 6){ echo 'active'; }?>">Six Month</button>
                                    <button type="button" data-value="nine" data-module="search_console" class="  btn btn-default searchConsoleRank sc_section <?php if($selectedSearch == 9){ echo 'active'; }?>">Nine Month</button>
                                    <button type="button" data-value="year" data-module="search_console" class="  btn btn-default  searchConsoleRank sc_section <?php if($selectedSearch == 12){ echo 'active'; }?>">One Year</button>
                                    <button type="button" data-value="twoyear" data-module="search_console" class="  btn btn-default searchConsoleRank sc_section <?php if($selectedSearch == 24){ echo 'active'; }?>">Two Year</button>
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
                                        <div class="modalboxConsole">
                                            <canvas id="canvas-search-console-ranking" height="300"></canvas>
                                            <div class="modalbox" id="console_add" >
                                                <div class="box" id="console_reminder" style="<?php if($dashboardtype->google_console_id != ''){ echo "display: none"; } else{ echo "display: block"; }?>">
                                                    <h2>Google Console Account</h2>
                                                    <p>Please Provide Google Console Account For better tracking.</p>
                                                </div>
                                            </div>

                                            <div class="searchConsoleLoader" id="console_loader" style="<?php if($dashboardtype->google_console_id != ''){ echo "display: block"; } else{ echo "display: none"; }?>">
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
                    <!--chart/graph section start-->
                    <div id="c3chartline" class="keyword_hide" style="opacity:1;width: 50%;float: left;">
                        <canvas id="keywordsCanvasRanking" width="100" height="40" > </canvas>
                    </div>
                    <div id="canvas-holder" style="width:50%; float: left;">
                        <canvas id="keywordsCanvasChartAreaRanking" width="100" height="40" ></canvas>
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
                    <div class="card-header-title livekeywordViewkey">
                        <img src="{{URL::asset('/public/viewkey/images/live-location-icon.gif')}}">
                        Live Keyword Tracking    
                    </br>
                    <small id="yeskws_txt" ></small>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">

                    <div class="live-Keyword-tracking hide" id="liveKeywordTrackingChartRanking">
                        <button id="close-graph"><i class="fa fa-times fa-2x" aria-hidden="true"></i></button>
                        <div id="keywordchartConatinerRanking"></div>
                        <div  style="margin-bottom: 22px;">
                            <a href="javascript:void(0);" class="border-0 btn-pill btn-wide btn-transition active btn btn-outline-primary" onclick="drawChartGraphRanking({{$campaign_id}}, '-60  day');return false;" >60days</a>
                            <a href="javascript:void(0);" class="border-0 btn-pill btn-wide btn-transition active btn btn-outline-primary" onclick="drawChartGraphRanking({{$campaign_id}}, '-90  day');return false;" >90days</a>
                            <a href="javascript:void(0);" class="border-0 btn-pill btn-wide btn-transition active btn btn-outline-primary" onclick="drawChartGraphRanking({{$campaign_id}}, '-180  day');return false;" >180days</a>
                            <a href="javascript:void(0);" class="border-0 btn-pill btn-wide btn-transition active btn btn-outline-primary" onclick="drawChartGraphRanking({{$campaign_id}}, '-365  day');return false;" >Last Year</a>
                            <a href="javascript:void(0);" class="border-0 btn-pill btn-wide btn-transition active btn btn-outline-primary" >All Time</a>
                        </div>
                    </div>

                     <div class="row widget-row keywordSinceSection">
                        <div class="col-md-6 col-lg-2">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
                                <div class="widget-content p-0 w-100">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left pr-2 fsize-1 marginLeft">
                                                <div class="widget-numbers mt-0 fsize-3 text-info ">
                                                    <span id="lifetime">0</span>
                                                </div>
                                            </div>
                                        </div>
                                          <div class="w-100">
                                                <div class="text-muted opacity-6">Keywords Up</div>
                                            </div>
                                        <div class="widget-content-left fsize-1">
                                            <div class="text-muted opacity-6">Since Start</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6 col-lg-2">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
                                <div class="widget-content p-0 w-100">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left pr-2 fsize-1 ">
                                                <div class="widget-numbers mt-0 fsize-3 text-info ">
                                                     <span id="three">0</span>
                                                     <span class="total">/0</span>
                                                </div>
                                            </div>
                                        </div>
                                          <div class=" w-100">
                                                <div class="text-muted opacity-6">In Top 3</div>
                                            </div>
                                        <div class="widget-content-left fsize-1">
                                            <div class="text-muted opacity-6"><span ><strong id="since_three"></strong></span> since Start</div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6 col-lg-2">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
                                <div class="widget-content p-0 w-100">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left pr-2 fsize-1 ">
                                                <div class="widget-numbers mt-0 fsize-3 text-info ">
                                                     <span id="ten">0</span>
                                                     <span class="total">/0</span>
                                                </div>
                                            </div>
                                        </div>
                                          <div class=" w-100">
                                                <div class="text-muted opacity-6">In Top 10</div>
                                            </div>
                                        <div class="widget-content-left fsize-1">
                                            <div class="text-muted opacity-6"><span ><strong id="since_ten"></strong></span> since Start</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6 col-lg-2">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
                                <div class="widget-content p-0 w-100">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left pr-2 fsize-1 ">
                                                <div class="widget-numbers mt-0 fsize-3 text-info ">
                                                     <span id="twenty">0</span>
                                                     <span class="total">/0</span>
                                                </div>
                                            </div>
                                        </div>
                                          <div class=" w-100">
                                                <div class="text-muted opacity-6">In Top 20</div>
                                            </div>
                                        <div class="widget-content-left fsize-1">
                                            <div class="text-muted opacity-6"><span ><strong id="since_twenty"></strong></span> since Start</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6 col-lg-2">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
                                <div class="widget-content p-0 w-100">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left pr-2 fsize-1 ">
                                                <div class="widget-numbers mt-0 fsize-3 text-info ">
                                                     <span id="fifty">0</span>
                                                     <span class="total">/0</span>
                                                </div>
                                            </div>
                                        </div>
                                          <div class=" w-100">
                                                <div class="text-muted opacity-6">In Top 50</div>
                                            </div>
                                        <div class="widget-content-left fsize-1">
                                            <div class="text-muted opacity-6"><span ><strong id="since_fifty"></strong></span> since Start</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6 col-lg-2">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
                                <div class="widget-content p-0 w-100">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left pr-2 fsize-1 ">
                                                <div class="widget-numbers mt-0 fsize-3 text-info ">
                                                     <span id="hundred">0</span>
                                                     <span class="total">/0</span>
                                                </div>
                                            </div>
                                        </div>
                                          <div class=" w-100">
                                                <div class="text-muted opacity-6">In Top 100</div>
                                            </div>
                                        <div class="widget-content-left fsize-1">
                                            <div class="text-muted opacity-6"><span ><strong id="since_hundred"></strong></span> since Start</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                      
                    </div>
                    <div class="clearfix"></div>

                <!--live keyword tracking table-->
                <div class="table-responsive">
                    <table class="table table-bordered data-table" id="LiveKeywordTrackingTableRanking">
                        <thead>
                            <tr>
                                <th>Keyword</th>
                                <th>Start</th>
                                <th><img src="{{URL::asset('/public/vendor/images/google-logo-icon.png')}}" class="googleIcon"></th>
                                <th>1 Day</th>
                                <th>7 Days</th>
                                <th>30 Days</th>
                                <th>Life</th>
                                <th>Comp</th>
                                <th>Search Vol</th>
                                <th>Date Added</th>
                                <th>Url</th>
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


<div class="row">
    <div class="col-md-12 col-lg-6 col-xl-12">
        <div class="mb-3 card">
            <div class="card-header-tab card-header-tab-animation card-header">
                <div class="card-header-title extraOrganicKeywordSection">
                    <img src="{{URL::asset('/public/vendor/images/data-analytics.png')}}">
                    Extra Organic Keywords
                </div>

                <div class="organickyword">
                     <ul class="tabs-animated-shadow nav-justified tabs-animated nav rankingTabsSection">
                        <li class="nav-item">
                            <button  class="btn btn-outline-primary tablinks active" onclick="rank(event, 'dfs')">
                                <span class="nav-text"><img src="{{URL::asset('/public/vendor/images/data_for_seo.png')}}" alt="dataforseo" class="dfsrank"></span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="btn btn-outline-primary tablinks" onclick="rank(event, 'consoleQuery')">
                                <span class="nav-text"><img src="{{URL::asset('/public/vendor/images/google-logo-icon.png')}}" alt="googlesearchconsole" class="gscRank">Search Console</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                 <div class="tab-content">
                    <div class="tab-pane active tabcontent" id="dfs">

                        <div class="table-responsive">
                            <table class="table table-bordered data-table" id="google_organic_keywords_ranking">
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

                    <div class="tab-pane tabcontent" id="consoleQuery">
                         <table class="table">
                            <thead>
                                <tr>
                                    <th>Query</th>
                                    <th>Clicks</th>
                                    <th>Impression</th>
                                </tr>
                            </thead>
                            <tbody class="rank_query_table">
                            </tbody>
                        </table>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>



</div>