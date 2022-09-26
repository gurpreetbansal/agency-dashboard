 <div class="space-top">
  <!-- ADS Row -->
 <div class="white-box pa-0 mb-40 white-box-handle box-border">
    <div class="section-head">
      <h4>
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}"></figure> Ads
      </h4>
    </div>

  <div class="white-box-body">
    <div class="project-table-cover">
      <div class="project-table-body AdsTable">
        <table id="ads-list" class="ppcTable adsTable">
          <thead>
            <tr>
              <th class="ads_sorting " data-sorting_type="desc" data-column_name="ad">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Ad
              </th>
              <th class="ads_sorting " data-sorting_type="desc" data-column_name="ad_type">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Ad Type
              </th>
              <th class="ads_sorting " data-sorting_type="desc" data-column_name="impression">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Impressions
              </th>
              <th class="ads_sorting " data-sorting_type="desc" data-column_name="click">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Clicks
              </th>
              <th class="ads_sorting " data-sorting_type="desc" data-column_name="ctr">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                CTR
              </th>
              <th class="ads_sorting " data-sorting_type="desc" data-column_name="costs">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Cost
              </th>
              <th class="ads_sorting " data-sorting_type="desc" data-column_name="conversion">
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
                    <td class="">.....</td>
                  </tr>
              @endfor
          </tbody>
        </table>
          <input type="hidden" id="hidden_ads_limit" value="50">
          <input type="hidden" id="hidden_ads_page" value="1">
          <input type="hidden" id="hidden_ads_column_name" value="impressions">
          <input type="hidden" id="hidden_ads_sort_type" value="desc">
      </div>

      
    </div>
  </div>

</div>
</div>
            <!-- ADS Row End -->