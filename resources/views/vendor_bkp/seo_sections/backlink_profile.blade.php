 <!-- Backlink Profile Row -->
 <div class="white-box pa-0 mb-40">
  <div class="white-box-head">
  <!-- <span class="white-box-handle" uk-icon="icon: table"></span> -->
    <div class="left">
      <div class="heading">
        <img src="{{URL::asset('public/vendor/internal-pages/images/backlink-profile-img.png')}}">
       <div>
        <h2>Backlink Profile
          <span uk-tooltip="title: This section shows growth in referring domains month after month, however we check the total number of referring domains on weekly basis and same can be seen in graph.; pos: top-left"
          class="fa fa-info-circle"></span>
        </h2>
        <p class="backlink_profile_time"></p>
      </div>
      </div>
    </div>

    <div class="right">
      <div class="loader h-33 half-px"></div>
      <div class="filter-list">
        <ul>
          <li>
           <a href="javascript:;" data-request-id="{{@$campaign_id}}" id="refresh_backlink_profile" class="btn icon-btn color-purple" uk-tooltip="title: Refresh Backlink Profile; pos: top-center" title="" aria-expanded="false">
            <img src="{{URL::asset('/public/vendor/internal-pages/images/refresh-icon.png')}}">
          </a>
        </li>
      </ul>
    </div>
  </div>
  </div>

  <div class="white-box-body">
    <div uk-grid class="mb-40">
      <div class="uk-width-3-5@m">
        <div class="chart-head">
          <h6>Referring Domains</h6>
           <div class="filter-list">
            <ul>
              <li><button type="button"  class="backlinkChart active" data-value="all">All Time</button></li>
              <li><button type="button"  class="backlinkChart" data-value="one_year">One Year</button></li>
              <li><button type="button"  class="backlinkChart " data-value="last_30">Last 30Days</button></li>
            </ul>
          </div>
        </div>
        <div class="chart h-292  backlink-profile-graph ajax-loader">
          <input type="hidden" class="backlinkSelectdChart" value="all">

          <canvas id="chart-referring-domains"></canvas>
        </div>
      </div>
      <div class="uk-width-2-5@m pl-2">
        <div class="top-organic-keyword-table">
          <div class="top-organic-keyword-table-head">
            <h6>Summary</h6>
          </div>
          <div class="table-responsive">
            <table class="style1" id="bp_list">
              <tbody>
                 @for($i=0;$i<=12;$i++)
                <tr>
                  <td class="ajax-loader">....</td>
                  <td class="ajax-loader">....</td>
                </tr>
                @endfor
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="project-table-cover">
      <div class="project-table-head">
        <div class="project-entries">
          <label>Show
            <select id="backlink_limit">
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          entries</label>
        </div>

        <div class="project-search">
          <form>
            <input type="text" placeholder="Search..." class="backlink_search" onkeydown="return (event.keyCode!=13);">
            <div class="refresh-search-icon" id="refresh-backlinks-search">
                <span uk-icon="refresh"></span>
            </div>
            <a href="javascript:;" class="backLink-search-clear"><span class="clear-input backLinkClear" uk-icon="icon: close;"></span></a>
            <button type="submit" onclick="return false;"><span uk-icon="icon: search"></span></button>
          </form>
        </div>
      </div>

      <div class="project-table-body backLinkTable">
        <table id="backlink_data">
          <thead>
            <tr>
              <th class="backlink_sorting ajax-loader" data-sorting_type="asc" data-column_name="url_from">
                <span uk-icon="arrow-up"></span>
                <span uk-icon="arrow-down"></span>
                Source Page Title & Url | Target Page
              </th>
              <th class="backlink_sorting ajax-loader" data-sorting_type="asc" data-column_name="link_type">
                <span uk-icon="arrow-up"></span>
                <span uk-icon="arrow-down"></span>
                Link Type
              </th>
              <th class="backlink_sorting ajax-loader" data-sorting_type="asc" data-column_name="url_to">
                <span uk-icon="arrow-up"></span>
                <span uk-icon="arrow-down"></span>
                Anchor Text
              </th>
              <th class="backlink_sorting ajax-loader" data-sorting_type="asc" data-column_name="links_ext">
                <span uk-icon="arrow-up"></span>
                <span uk-icon="arrow-down"></span>
                External Links
              </th>
              <th class="backlink_sorting ajax-loader" data-sorting_type="asc" data-column_name="first_seen">
                <span uk-icon="arrow-up"></span>
                <span uk-icon="arrow-down"></span>
                First Seen
              </th>
              <th class="backlink_sorting ajax-loader" data-sorting_type="asc" data-column_name="last_visited">
                <span uk-icon="arrow-up"></span>
                <span uk-icon="arrow-down"></span>
                Last Seen
              </th>
            </tr>
          </thead>
          <tbody>
               @for($i=1; $i<=5; $i++)
                  <tr>
                     <td class="ajax-loader">....</td>
                     <td class="ajax-loader">....</td>
                     <td class="ajax-loader">....</td>
                     <td class="ajax-loader">....</td>
                     <td class="ajax-loader">....</td>
                     <td class="ajax-loader">....</td>
                  </tr>   
                @endfor  
          </tbody>
        </table>
      </div>

     

      <input type="hidden" name="hidden_page_backlink" id="hidden_page_backlink" value="1" />
      <input type="hidden" name="hidden_column_name_backlink" id="hidden_column_name_backlink" value="first_seen" />
      <input type="hidden" name="hidden_sort_type_backlink" id="hidden_sort_type_backlink" value="desc" />
      <input type="hidden" name="limit_backlink" id="limit_backlink" value="10" />

      <div class="project-table-foot backlink-profile-foot">
        

      </div>

    </div>

  </div>

</div>
            <!-- Backlink Profile Row End -->