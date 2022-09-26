<div class="section-head space-top BreakBefore">
  <h2>
    Google Ads - Performance
  </h2>
</div>

<!-- Performance Networks Row -->
<div class="white-box pa-0 mb-40 white-box-handle box-border">
  <div class="section-head">
    <h4>
      <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}"></figure> Performance Networks
    </h4>
  </div>

  <div class="white-box-body">
    <div class="project-table-cover">
      
      <div class="project-table-body AdGroupsTable">
        <table id="ads_performce_network-list" class="ppcTable sameTable">
          <thead>
            <tr>
              <th class="ad_performance_network_sorting " data-sorting_type="desc" data-column_name="publisher_by_network">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Publisher By Network
              </th>
              <th class="ad_performance_network_sorting " data-sorting_type="desc" data-column_name="impressions">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Impressions
              </th>
              <th class="ad_performance_network_sorting " data-sorting_type="desc" data-column_name="clicks">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Clicks
              </th>
              <th class="ad_performance_network_sorting " data-sorting_type="desc" data-column_name="ctr">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                CTR
              </th>
              <th class="ad_performance_network_sorting " data-sorting_type="desc" data-column_name="cost">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Cost
              </th>
              <th class="ad_performance_network_sorting " data-sorting_type="desc" data-column_name="conversions">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Conversions
              </th>
            </tr>
          </thead>
          <tbody>
              @for($i=1; $i<=5; $i++)
                  <tr>
                    <td class="">.....</td>
                    <td class="">.....</td>
                    <td class="">.....</td>
                    <td class="">.....</td>
                    <td class="">.....</td>
                    <td class="">.....</td>
                  </tr>
              @endfor
          </tbody>
          <input type="hidden" id="hidden_performance_network_limit" value="50">
          <input type="hidden" id="hidden_performance_network_page" value="1">
          <input type="hidden" id="hidden_performance_network_column_name" value="impressions">
          <input type="hidden" id="hidden_performance_network_sort_type" value="desc">
        </table>
      </div>

      


    </div>
  </div>

</div>
<!-- Performance Networks Row End -->