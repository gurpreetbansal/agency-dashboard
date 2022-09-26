<div class="white-box pa-0 mb-40 space-top" id="console_data">
   <div class="box-boxshadow">
    <div class="section-head">
        <h4>
            <figure><img src="{{URL::asset('public/vendor/internal-pages/images/search-console-img.png')}}"></figure> Keyword Visibility
            <font class="search_console_time"></font>
        </h4>
        <hr />
        <p>
            This section shows clicks and impressions in Google Search Console for selected time period, It’s a great way to understand your website’s visibility in Google.
        </p>
    </div>
    
    <div class="sConsole-compare">
        <div class="uk-grid-collapse uk-child-width-expand@s uk-grid">
            <div class="single">
                <h6>Total clicks</h6>
                <ul>
                    <li>
                        <strong class="current_click">--</strong><span class="current_click_dates"></span>
                    </li>
                    <li class="show_previous_click">
                        <strong class="previous_click">--</strong><span class="previous_click_dates"></span>
                    </li>
                </ul>
            </div>
            <div class="single">
                <h6>Total impressions</h6>
                <ul>
                    <li>
                        <strong class="current_impressions">--</strong><span class="current_impressions_dates"></span>
                    </li>
                    <li class="show_previous_impressions">
                        <strong class="previous_impressions">--</strong><span class="previous_impressions_dates"></span>
                    </li>
                </ul>
            </div>
            <div class="single">
                <h6>Average CTR</h6>
                <ul>
                    <li class="show_current_ctr">
                        <strong><span class="current_ctr">--</span></strong><span class="current_ctr_dates"></span>
                    </li>
                    <li class="show_previous_ctr">
                        <strong><span class="previous_ctr">--</span></strong><span class="previous_ctr_dates"></span>
                    </li>
                </ul>
            </div>
            <div class="single">
                <h6>Average position</h6>
                <ul>
                    <li class="show_current_position">
                        <strong><span class="current_position">--</span></strong><span class="current_position_dates"></span>
                    </li>
                    <li class="show_previous_position">
                        <strong><span class="previous_position">--</span></strong><span class="previous_position_dates"></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="white-box-body height-300 search-console-graph">
        <canvas id="new-canvas-search-console" height="300"></canvas>
    </div>
</div>
<div class="table-box">
    <div class="white-box pa-0">
        <div class="box-boxshadow">
            <p><em><img src="{{URL::asset('public/vendor/internal-pages/images/info.png')}}"> Queries : Top 10 keywords which bring most traffic to your website.</em></p>
            <div class="searchConsoleQueries">
                <div class="table-responsive">
                    <table class="style1 queries">
                        <thead>
                            <tr>
                                <th>Query</th>
                                <th>Clicks</th>
                                <th>Impression </th>
                                <th>CTR </th>
                                <th>Average Position </th>
                            </tr>
                        </thead>
                        <tbody class="query_table"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="white-box pa-0 space-top">
        <div class="box-boxshadow">
            <p><em><img src="{{URL::asset('public/vendor/internal-pages/images/info.png')}}"> Pages : Top 10 pages of your website which gets the most clicks and impressions.</em></p>
            <div class="searchConsolePages">
                <div class="table-responsive">
                    <table class="style1 pages">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>Clicks</th>
                                <th>Impression</th>
                            </tr>
                        </thead>
                        <tbody class="pages_table"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="white-box pa-0">
        <div class="box-boxshadow">
            <p><em><img src="{{URL::asset('public/vendor/internal-pages/images/info.png')}}"> Countries : Top 10 countries which brings most traffic to your website.</em></p>
            <div class="searchConsoleCountries">
                <div class="table-responsive">
                    <table class="style1 countries">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Clicks</th>
                                <th>Impression </th>
                                <th>CTR</th>
                                <th>Position</th>
                            </tr>
                        </thead>
                        <tbody class="country_table"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>  
</div>