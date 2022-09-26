 <!-- Keyword Row -->
 <div class="space-top BreakBefore">
 <div class="white-box pa-0 mb-40 white-box-handle box-border">
    <div class="section-head">
      <h4>
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}"></figure> Keywords
      </h4>
    </div>
  <div class="white-box-body">
    <div class="project-table-cover">
      <div class="project-table-body">
        <table id="ads-keyword-list" class="ppcTable sameTable">
          <thead>
            <tr>
              <th class="keyword_list_sorting " data-sorting_type="desc" data-column_name="keywords">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Keyword
              </th>
              <th class="keyword_list_sorting " data-sorting_type="desc" data-column_name="impressions">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Impressions
              </th>
              <th class="keyword_list_sorting " data-sorting_type="desc" data-column_name="clicks">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Clicks
              </th>
              <th class="keyword_list_sorting " data-sorting_type="desc" data-column_name="ctr">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                CTR
              </th>
              <th class="keyword_list_sorting " data-sorting_type="desc" data-column_name="cost">
                <span uk-icon="arrow-up" class="uk-icon"></span>
                <span uk-icon="arrow-down" class="uk-icon"></span>
                Cost
              </th>
              <th class="keyword_list_sorting " data-sorting_type="desc" data-column_name="conversions">
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
        </table>
        <input type="hidden" id="hidden_keyword_limit" value="50">
        <input type="hidden" id="hidden_keyword_page" value="1">
        <input type="hidden" id="hidden_keyword_column_name" value="impressions">
        <input type="hidden" id="hidden_keyword_sort_type" value="desc">
      </div> 
      
    </div>
  </div>
</div>
</div>
<!-- Keyword Row End -->