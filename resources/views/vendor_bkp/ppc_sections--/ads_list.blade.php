 <!-- ADS Row -->
 <div class="white-box pa-0 mb-40">
  <div class="white-box-head">
    <div class="left">
      <div class="heading">
        <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}">
        <h2>Ads
          <span uk-tooltip="title: ADS Here...; pos: top-left" class="fa fa-info-circle" title=""
          aria-expanded="false"></span>
        </h2>
      </div>
    </div>
  </div>

  <div class="white-box-body">
    <div class="project-table-cover">
      <div class="project-table-head">
        <div class="project-entries ajax-loader">
          <label>Show
            <select id="ads-limit">
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          entries</label>
        </div>
        <div class="project-search ajax-loader">
          <form>
            <input type="text" placeholder="Search..." class="ads-search">
            <button type="submit">
              <span uk-icon="icon: search" class="uk-icon"></span>
            </button>
          </form>
        </div>
      </div>

      <div class="project-table-body AdsTable">
        <table id="ads-list">
          <thead>
            <tr>
              <th class="ads_sorting ajax-loader" data-sorting_type="desc" data-column_name="ad">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Ad
              </th>
              <th class="ads_sorting ajax-loader" data-sorting_type="desc" data-column_name="ad_type">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Ad Type
              </th>
              <th class="ads_sorting ajax-loader" data-sorting_type="desc" data-column_name="impressions">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Impressions
              </th>
              <th class="ads_sorting ajax-loader" data-sorting_type="desc" data-column_name="clicks">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Clicks
              </th>
              <th class="ads_sorting ajax-loader" data-sorting_type="desc" data-column_name="ctr">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                CTR
              </th>
              <th class="ads_sorting ajax-loader" data-sorting_type="desc" data-column_name="cost">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Cost
              </th>
              <th class="ads_sorting ajax-loader" data-sorting_type="desc" data-column_name="conversions">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Conversions
              </th>
            </tr>
          </thead>
          <tbody>
              @for($i=1; $i<=5; $i++)
                  <tr>
                    <td class="ajax-loader">.....</td>
                    <td class="ajax-loader">.....</td>
                    <td class="ajax-loader">.....</td>
                    <td class="ajax-loader">.....</td>
                    <td class="ajax-loader">.....</td>
                    <td class="ajax-loader">.....</td>
                    <td class="ajax-loader">.....</td>
                  </tr>
              @endfor
          </tbody>
        </table>
          <input type="hidden" id="hidden_ads_limit" value="20">
          <input type="hidden" id="hidden_ads_page" value="1">
          <input type="hidden" id="hidden_ads_column_name" value="impressions">
          <input type="hidden" id="hidden_ads_sort_type" value="desc">
      </div>

      <div class="project-table-foot" id="ads-foot">
        
      </div>


    </div>
  </div>

</div>
            <!-- ADS Row End -->