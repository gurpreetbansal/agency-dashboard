  <!-- Organic Traffic Growth Row -->
  <div class="white-box pa-0 mb-40 " id="analytics_data">
    <div class="white-box-head">
      <!-- <span class="white-box-handle" uk-icon="icon: table"></span> -->
      <div class="left">
        <div class="loader h-33 half-px"></div>
        <div class="heading">
          <img src="{{URL::asset('public/vendor/internal-pages/images/organic-traffic-growth-img.png')}}">
          <h2>Organic Traffic Trend
            <span uk-tooltip="title: This section shows total number of organic visits to your website in selected time period.; pos: top-left" class="fa fa-info-circle"></span></h2>
          </div>
        </div>
        <div class="right" id="analyticsFilters">
          <div class="loader h-33 half-px"></div>
          <div class="filter-list" id="analytic-filter-list">
            <ul>
              <li>
                <form>
                  <label class='sw'>
                    <input type='checkbox' <?php if($comparison == 1) echo 'checked'?> class="analyticsGraphCompare">
                    <div class='sw-pan'></div>
                    <div class='sw-btn'></div>
                  </label>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="white-body-filter-list">
        <div class="filter-list">
          <ul>
            <li>
              <button type="button" data-value="month" data-module="organic_traffic" class="graph_range_viewkey <?php if($selected_ua == 1){ echo 'active'; }?>">One Month</button>
            </li>
            <li>
              <button type="button" data-value="three" data-module="organic_traffic" class="graph_range_viewkey <?php if($selected_ua == 3){ echo 'active'; }?>">Three Month</button>
            </li>
            <li>
              <button type="button" data-value="six" data-module="organic_traffic" class="graph_range_viewkey <?php if($selected_ua == 6){ echo 'active'; }?>">Six Month</button>
            </li>
            <li>
              <button type="button" data-value="nine" data-module="organic_traffic" class="graph_range_viewkey <?php if($selected_ua == 9){ echo 'active'; }?>">Nine Month</button>
            </li>
            <li>
              <button type="button" data-value="year" data-module="organic_traffic" class="graph_range_viewkey <?php if($selected_ua == 12){ echo 'active'; }?>">One Year</button>
            </li>
            <li>
              <button type="button" data-value="twoyear" data-module="organic_traffic" class="graph_range_viewkey <?php if($selected_ua == 24){ echo 'active'; }?>">Two Year</button>
            </li>
          </ul>
        </div>

        <div class="filter-list style2">
          <ul>
            <li><button type="button" class="btn btn-sm btn-border blue-btn-border organic_traffic_displayType <?php if($display_type == 'day'){ echo 'blue-btn'; }?>"  data-type="day">Day</button></li>
            <li><button type="button" class="btn btn-sm btn-border blue-btn-border organic_traffic_displayType <?php if($display_type == 'week'){ echo 'blue-btn'; }?>"  data-type="week">Week</button></li>
            <li><button type="button" class="btn btn-sm btn-border blue-btn-border organic_traffic_displayType <?php if($display_type == 'month'){ echo 'blue-btn'; }?>"  data-type="month">Month</button></li>
          </ul>
        </div>

      </div>

      <div class="white-box-body">
        <!-- Organic Box Data Row -->
        <div uk-grid class="mb-20">
          <!-- Organic Box 1 -->
          <div class="uk-width-1-3@s">
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
          <div class="uk-width-1-3@s">
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
          <div class="uk-width-1-3@s">
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