<!--Search Console Row -->
<div class="white-box pa-0 mb-40" id="console_data" style="<?php if($dashboardtype->console_account_id == ''){ echo "display: none"; } else{ echo "display: block"; }?>">
  <div class="white-box-head">
  <!-- <span class="white-box-handle" uk-icon="icon: table"></span> -->
    <div class="left">
      <div class="loader h-33 half-px"></div>
      <div class="heading">
        <img src="{{URL::asset('public/vendor/internal-pages/images/search-console-img.png')}}">
        <div>
        <h2>Search Console
          <span uk-tooltip="title: This section shows clicks and impressions in Google Search Console for selected time period, It’s a great way to understand your website’s visibility in Google. ; pos: top-left"
          class="fa fa-info-circle"></span></h2>
          <p class="search_console_time"></p>
          </div>
        </div>
      </div>
      <div class="right">
        <div class="loader h-33 half-px"></div>
        <div class="filter-list">
          <ul>
            <li>
               <a href="javascript:;" data-request-id="{{@$campaign_id}}" id="refresh_search_console_section" class="btn icon-btn color-purple" uk-tooltip="title: Refresh Search Console data; pos: top-center" title="" aria-expanded="false">
                  <img src="{{URL::asset('/public/vendor/internal-pages/images/refresh-icon.png')}}">
                </a>
            </li>
          </ul>
        </div>
      </div>
    </div>


    <div class="white-body-filter-list">
      <div class="filter-list">
        <ul>
          <li>
            <button type="button" data-module="search_console" class="searchConsole" data-value="month">One Month</button>
          </li>
          <li>
            <button type="button" data-module="search_console" class="searchConsole" data-value="three">Three Month</button>
          </li>
          <li>
            <button type="button" data-module="search_console" class="searchConsole" data-value="six">Six Month</button>
          </li>
          <li>
            <button type="button" data-module="search_console" class="searchConsole" data-value="nine">Nine Month</button>
          </li>
          <li>
            <button type="button"data-module="search_console" class="searchConsole" data-value="year">One Year</button>
          </li>
          <li>
            <button type="button" data-module="search_console" class="searchConsole" data-value="twoyear">Two Year</button>
          </li>
        </ul>
      </div>
    </div>

    <div class="white-box-body height-300 search-console-graph ajax-loader">
      <canvas id="new-canvas-search-console" height="300"></canvas>
    </div>

    <div class="white-box pa-0">
      <div class="white-box-tab-head no-border"> 
        <ul class="console-nav-bar uk-subnav uk-subnav-pill ajax-loader" uk-switcher="connect: .searchConsoleNav">
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
                    <th class="ajax-loader">Impression </th>
                    <th class="ajax-loader">CTR </th>
                    <th class="ajax-loader">Position </th>
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
                  <th class="ajax-loader">Page</th>
                  <th class="ajax-loader">Clicks</th>
                  <th class="ajax-loader">Impression </th>
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
                <th class="ajax-loader">Country</th>
                <th class="ajax-loader">Clicks</th>
                <th class="ajax-loader">Impression </th>
                <th class="ajax-loader">CTR</th>
                <th class="ajax-loader">Position</th>
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

@if(Auth::user()->role_id !=4)
<div class="white-box mb-40 " id="console_add" style="<?php if($dashboardtype->console_account_id != ''){ echo "display: none"; } else{ echo "display: block"; } ?>">
  <div class="loader h-33 "></div>
  <div class="integration-list" >
    <article>
      <figure>
        <img src="{{URL::asset('public/vendor/internal-pages/images/google-search-console-img.png')}}">
      </figure>
      <div>
        <p>To get insights about SERP, keywords and build reports for your SEO dashboard.</p>
        <a href="#" class="btn btn-border blue-btn-border" data-pd-popup-open="CampaignDetailConsolePopup">Connect</a>
      </div>

    </article>
  </div>
</div>
@endif