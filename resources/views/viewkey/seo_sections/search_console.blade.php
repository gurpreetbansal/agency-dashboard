<!--Search Console Row -->
<div class="white-box pa-0 mb-40" id="console_data">
  <div class="white-box-head">
    <div class="left">
      <div class="loader h-33 half-px"></div>
      <div class="heading">
        <img src="{{URL::asset('public/vendor/internal-pages/images/search-console-img.png')}}">
        <div>
        <h2>Search Console
          <span uk-tooltip="title: This section shows clicks and impressions in Google Search Console for selected time period, It’s a great way to understand your website’s visibility in Google.; pos: top-left"  class="fa fa-info-circle"></span></h2>
          <p class="search_console_time"></p>
        </div>
        </div>
      </div>
      <div class="right">
        <div class="loader h-33 half-px"></div>
        <div class="filter-list">
         <ul>
          <li>
            <a href="javascript:;" id="dateRange_console_section" class="btn icon-btn color-blue" uk-tooltip="title: Date Range; pos: top-center">
              <img src="{{URL::asset('/public/vendor/internal-pages/images/date-rance-calender-icon.png')}}">
            </a>
          </li>
        </ul>
      </div>
    </div>
    @include('includes.vendor.search_console_popup')
  </div>

  <div class="sConsole-compare">
    <div class="uk-grid-collapse uk-child-width-expand@s uk-grid">
      <div class="single ajax-loader">
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
      <div class="single ajax-loader">
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
      <div class="single ajax-loader">
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
      <div class="single ajax-loader">
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

  <div class="white-box-body height-300 search-console-graph ajax-loader">
    <canvas id="new-canvas-search-console" height="300"></canvas>
  </div>

  <div class="white-box pa-0">
    <div class="white-box-tab-head no-border">
      <ul class="console-nav-bar uk-subnav uk-subnav-pill ajax-loader" uk-switcher="connect: .searchConsoleNav; swiping: false">
        <li><a href="#">Queries</a></li>
        <li class="searchConsoleTabs" data-type="pages"><a href="#">Pages</a></li>
        <li class="searchConsoleTabs" data-type="countries"><a href="#">Countries</a></li>
      </ul>
    </div>
    <div class="white-box-body pa-0">
      <div class="uk-switcher searchConsoleNav">
        <div>
          <div class="table-responsive">
            <table class="style1 queries">
              <thead>
                <tr>
                  <th class="ajax-loader">Query</th>
                  <th class="ajax-loader">Clicks</th>
                  <th class="ajax-loader">Impression</th>
                  <th class="ajax-loader">CTR </th>
                  <th class="ajax-loader">Average Position</th>
                </tr>
              </thead>
              <tbody class="query_table">
                <tr>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                </tr>
                <tr>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                </tr>
                <tr>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div>
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
        <div>
          <div class="table-responsive">
            <table class="style1 countries">
              <thead>
                <tr>
                  <th>Country</th>
                  <th>Clicks</th>
                  <th>Impression </th>
                  <th>CTR</th>
                  <th>Average Position</th>
                </tr>
              </thead>
              <tbody class="country_table"></tbody>
            </table>
          </div>
        </div>
        <input type="hidden" class="sc_duration" value="3">
      </div>
    </div>
  </div>
</div>