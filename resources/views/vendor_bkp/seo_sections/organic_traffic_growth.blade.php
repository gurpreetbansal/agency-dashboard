  <!-- Organic Traffic Growth Row -->
  <div class="white-box pa-0 mb-40 " id="analytics_data" style="<?php if($dashboardtype->google_analytics_id == ''){ echo "display: none"; } else{ echo "display: block"; } ?>">
    <div class="white-box-head">
    <!-- <span class="white-box-handle" uk-icon="icon: table"></span> -->
      <div class="left">
        <div class="loader h-33 half-px"></div>
        <div class="heading">
          <img src="{{URL::asset('public/vendor/internal-pages/images/organic-traffic-growth-img.png')}}">
          <div>
          <h2>Organic Traffic Trend
            <span uk-tooltip="title: This section shows total number of organic visits to your website in selected time period.; pos: top-left"
            class="fa fa-info-circle"></span></h2>
            <p class="analytics_time"></p>
            </div>
          </div>
        </div>
        <div class="right" id="analyticsFilters">
          <div class="loader h-33 half-px"></div>
          <div class="filter-list" id="analytic-filter-list">
            <ul>
              <li>
                <form>
                  <label class='sw'>
                    <input type='checkbox' class="analyticsGraphCompare">
                    <div class='sw-pan'></div>
                    <div class='sw-btn'></div>
                  </label>
                </form>
              </li>

              <li>
                <a href="javascript:;" data-request-id="{{@$campaign_id}}" id="refresh_organicTraffic_section" class="btn icon-btn color-purple" uk-tooltip="title: Refresh Organic Traffic Trend data; pos: top-center" title="" aria-expanded="false"><img src="{{URL::asset('/public/vendor/internal-pages/images/refresh-icon.png')}}"></a>
              </li>
              
            </ul>
          </div>
        </div>
      </div>

      <div class="white-body-filter-list">
    <div class="filter-list">
      <ul>
        <li>
          <button type="button" data-value="month" data-module="organic_traffic" class="graph_range">One Month</button>
        </li>
        <li>
          <button type="button" data-value="three" data-module="organic_traffic" class="graph_range">Three Month</button>
        </li>
        <li>
          <button type="button" data-value="six" data-module="organic_traffic" class="graph_range">Six Month</button>
        </li>
        <li>
          <button type="button" data-value="nine" data-module="organic_traffic" class="graph_range">Nine Month</button>
        </li>
        <li>
          <button type="button" data-value="year" data-module="organic_traffic" class="graph_range">One Year</button>
        </li>
        <li>
          <button type="button" data-value="twoyear" data-module="organic_traffic" class="graph_range">Two Year</button>
        </li>
      </ul>
    </div>

    <div class="filter-list style2">
      <ul>
        <li><button type="button" class="btn btn-sm btn-border blue-btn-border organic_traffic_displayType"  data-type="day">Day</button></li>
        <li><button type="button" class="btn btn-sm btn-border blue-btn-border organic_traffic_displayType"  data-type="week">Week</button></li>
        <li><button type="button" class="btn btn-sm btn-border blue-btn-border organic_traffic_displayType"  data-type="month">Month</button></li>
      </ul>
    </div>

  </div>

      <div class="white-box-body">
        <!-- Organic Box Data Row -->
        <div uk-grid class="mb-20">
          <!-- Organic Box 1 -->
          <div class="uk-width-1-3@l uk-width-1-3@m uk-width-1-3@s ">
            <div class="white-box medium-chart-box">
              <div class="medium-chart-box-head ">
                <h6 class="session-count ajax-loader"><span class="" uk-icon="icon: arrow-down"></span> 0%</h6>
                <div class="loader h-33 "></div>
                <p><img src="{{URL::asset('public/vendor/internal-pages/images/sessions-img.png')}}"> Sessions</p>
              </div>
              <div class="medium-chart-box-foot">
                <p class="compare-session ajax-loader">0 Organic Traffic</p>
              </div>
            </div>
          </div>
          <!-- Organic Box 1 End -->

          <!-- Organic Box 2 -->
          <div class="uk-width-1-3@l uk-width-1-3@m uk-width-1-3@s ">
            <div class="white-box medium-chart-box">
              <div class="medium-chart-box-head">
                <h6 class="user-count ajax-loader"><span class="" uk-icon="icon: arrow-down"></span> 0%</h6>
                <div class="loader h-33 "></div>
                <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> Users</p>
              </div>
              <div class="medium-chart-box-foot">
                <p class="compare-user ajax-loader">0 Organic Traffic</p>
              </div>
            </div>
          </div>
          <!-- Organic Box 2 End -->

          <!-- Organic Box 3 -->
          <div class="uk-width-1-3@l uk-width-1-3@m uk-width-1-3@s ">
            <div class="white-box medium-chart-box">
              <div class="medium-chart-box-head">
                <h6 class="pageview-count ajax-loader"><span class="" uk-icon="icon: arrow-up"></span> 0%</h6>
                <div class="loader h-33 "></div>
                <p><img src="{{URL::asset('public/vendor/internal-pages/images/pageviews-img.png')}}"> Pageviews</p>
              </div>
              <div class="medium-chart-box-foot">
                <p class="compare-pageview ajax-loader">0 Organic Traffic</p>
              </div>
            </div>
          </div>
          <!-- Organic Box 3 End -->

        </div>
        <!-- Organic Box Data Row End -->

        <div class="traffic-growth-graph height-300 chart h-360 ajax-loader">
          <canvas id="new-canvas-traffic-growth" height="300"></canvas>
        </div>

      </div>

    </div>
    <!-- Organic Traffic Growth Row End -->
@if(Auth::user()->role_id !=4)
    <div class="white-box mb-40 " id="analytics_add" style="<?php if($dashboardtype->google_analytics_id != ''){ echo "display: none"; } else{ echo "display: block"; } ?>">
      <div class="loader h-33 "></div>
      <div class="integration-list" >
        <article>
          <figure>
            <img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-img.png')}}">
          </figure>
          <div>
            <p>To get insights about your website traffic and build reports for your SEO dashboard.</p>
            <a href="#" class="btn btn-border blue-btn-border" data-pd-popup-open="campaignDetailAnalytics">Connect</a>
          </div>

        </article>
      </div>
    </div>
    @endif