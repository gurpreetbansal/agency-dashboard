<!-- Campaigns Row -->
<div class="white-box pa-0 mb-40 white-box-handle box-border">
    <div class="section-head">
      <h4>
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}"></figure> Campaigns
      </h4>
    </div>
   <div class="white-box-body">
      <div class="project-table-cover">
         <div class="project-table-body">
            <table id="ads-campaign-list" class="ppcTable sameTable">
               <thead>
                  <tr>
                     <th class="campaign_list_sorting " data-sorting_type="asc" data-column_name="campaign_name">
                        <span uk-icon="arrow-up" class="uk-icon"></span>
                        <span uk-icon="arrow-down" class="uk-icon"></span>
                        Campaign
                     </th>
                     <th class="campaign_list_sorting " data-sorting_type="asc" data-column_name="impressions">
                        <span uk-icon="arrow-up" class="uk-icon"></span>
                        <span uk-icon="arrow-down" class="uk-icon"></span>
                        Impressions
                     </th>
                     <th class="campaign_list_sorting " data-sorting_type="asc" data-column_name="clicks">
                        <span uk-icon="arrow-up" class="uk-icon"></span>
                        <span uk-icon="arrow-down" class="uk-icon"></span>
                        Clicks
                     </th>
                     <th class="campaign_list_sorting " data-sorting_type="asc" data-column_name="ctr">
                        <span uk-icon="arrow-up" class="uk-icon"></span>
                        <span uk-icon="arrow-down" class="uk-icon"></span>
                        CTR
                     </th>
                     <th class="campaign_list_sorting " data-sorting_type="asc" data-column_name="cost">
                        <span uk-icon="arrow-up" class="uk-icon"></span>
                        <span uk-icon="arrow-down" class="uk-icon"></span>
                        Cost
                     </th>
                     <th class="campaign_list_sorting " data-sorting_type="asc" data-column_name="conversions">
                        <span uk-icon="arrow-up" class="uk-icon"></span>
                        <span uk-icon="arrow-down" class="uk-icon"></span>
                        Conversions
                     </th>
                  </tr>
               </thead>
               <tbody>
                  @include('viewkey.pdf.ppc_sections.campaigns-list.table')
               </tbody>
            </table>
            <input type="hidden" id="hidden_campaign_limit" value="50">
            <input type="hidden" id="hidden_campaign_search" value="">
            <input type="hidden" id="hidden_campaign_page" value="1">
            <input type="hidden" id="hidden_campaign_column_name" value="impressions">
            <input type="hidden" id="hidden_campaign_sort_type" value="desc">
         </div>
         
      </div>
   </div>
</div>
<!-- Campaigns Row End -->