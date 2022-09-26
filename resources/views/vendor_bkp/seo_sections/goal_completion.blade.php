<!-- Google Analytics Goal Completion Row -->
<input type="hidden" class="campaign_id" value="{{@$campaign_id}}"> 
<div class="white-box pa-0 mb-40" id="goal_completion_data" style="<?php if($dashboardtype->google_analytics_id == '' && $dashboardtype->google_analytics_id == null){ echo "display: none"; } else{ echo "display: block"; } ?>">
 <div class="white-box-head">
   <!-- <span class="white-box-handle" uk-icon="icon: table"></span> -->
   <div class="left">
     <div class="heading">
      <img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-goal-completion-img.png')}}">
      <div>
        <h2>Google Analytics Goal Completion
         <span uk-tooltip="title: This section shows goal completion from Google Analytics in selected time period.; pos: top-left"
         class="fa fa-info-circle"></span>
       </h2>
       <p class="analytics_time"></p>
     </div>
   </div>
 </div>
 <div class="right" id="GoalCompletionFilters">
  <div class="loader h-33 half-px"></div>
  <div class="filter-list" id="goal-filter-list">
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
  <div class="chart mb-40 height-300 ajax-loader goal-completion-graph">
   <canvas id="canvas-goal-completion" height="300"></canvas>
 </div>
 <div uk-grid class="goal-completion-box">
   <!-- Chart Box 1 -->
   <div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-2@m">
    <h5>Goal Completions</h5>
    <div class="white-box small-chart-box">
     <div class="small-chart-box-head">
      <figure>
       <div class="loader  h-54"></div>
       <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
     </figure>
     <!-- <div class="loader  h-67"></div> -->
     <h6><big class="compare ajax-loader" id="goal-completion-users">0.00% </big> All Users<span
       uk-tooltip="title: Organic Keywords Data Here...; pos: top-left"
       class="fa fa-info-circle"></span></h6>
     </div>
     <div class="chart goal-completion-all-users-div">
      <div class="allUserGraph ajax-loader loader-text h-60-chart"></div>
      <canvas id="goal-completion-all-users-new"></canvas>
    </div>
    <div class="small-chart-box-foot">
      <div class="loader h-27"></div>
      <p class="goal-completion-users-percentage ajax-loader goal_completion_percentage"></p>
    </div>
  </div>
  <div class="white-box small-chart-box">
   <div class="small-chart-box-head">
    <figure>
     <div class="loader  h-54"></div>
     <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
   </figure>
   <div class="loader  h-67"></div>
   <h6><big class="compare ajax-loader" id="goal-completion-traffic">0.00% </big> Organic Traffic <span
     uk-tooltip="title: Organic Keywords Data Here...; pos: top-left"
     class="fa fa-info-circle"></span></h6>
   </div>
   <div class="chart">
    <div class="OrganicGraph ajax-loader loader-text h-60-chart"></div>
    <canvas id="goal-completion-organic-new"></canvas>
  </div>
  <div class="small-chart-box-foot">
    <div class="loader h-27"></div>
    <p class="goal-completion-traffic-percentage ajax-loader goal_completion_percentage" ></p>
  </div>
</div>
</div>
<!-- Chart Box 1 End -->
<!-- Chart Box 2 -->
<div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-2@m">
  <h5>Goal Value</h5>
  <div class="white-box small-chart-box ">
   <div class="small-chart-box-head">
    <figure>
     <div class="loader  h-54"></div>
     <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
   </figure>
   <div class="loader  h-67"></div>
   <h6><big class="compare ajax-loader" id="goal-value-users"></big> All Users<span
     uk-tooltip="title: Organic Keywords Data Here...; pos: top-left"
     class="fa fa-info-circle"></span></h6>
   </div>
   <div class="chart">
    <div class="goalValueGraph ajax-loader loader-text h-60-chart"></div>
    <canvas id="goal-value-all-users-new"></canvas>
  </div>
  <div class="small-chart-box-foot">
    <div class="loader h-27"></div>
    <p class="goal-value-users-percentage ajax-loader goal_completion_percentage"></p>
  </div>
</div>
<div class="white-box small-chart-box ">
 <div class="small-chart-box-head">
  <figure>
   <div class="loader  h-54"></div>
   <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
 </figure>
 <div class="loader  h-67"></div>
 <h6><big class="compare ajax-loader" id="goal-value-organic"></big> Organic Traffic<span
   uk-tooltip="title: Organic Keywords Data Here...; pos: top-left"
   class="fa fa-info-circle"></span></h6>
 </div>
 <div class="chart">
  <div class="ValueOrganicGraph ajax-loader loader-text h-60-chart"></div>
  <canvas id="goal-value-organic-chart-new"></canvas>
</div>
<div class="small-chart-box-foot">
  <div class="loader h-27"></div>
  <p class="goal-value-organic-percentage ajax-loader goal_completion_percentage"></p>
</div>
</div>
</div>
<!-- Chart Box 2 End -->
<!-- Chart Box 3 -->
<div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-2@m">
  <h5>Goal Conversion Rate</h5>
  <div class="white-box small-chart-box ">
   <div class="small-chart-box-head">
    <figure>
     <div class="loader  h-54"></div>
     <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
   </figure>
   <div class="loader  h-67"></div>
   <h6><big class="compare ajax-loader" id="goal-conversion-rate-users"></big> All Users<span
     uk-tooltip="title: Organic Keywords Data Here...; pos: top-left"
     class="fa fa-info-circle"></span></h6>
   </div>
   <div class="chart">
    <div class="goalConversionRateGraph ajax-loader loader-text h-60-chart"></div>
    <canvas id="goal-conversion-all-users-new"></canvas>
  </div>
  <div class="small-chart-box-foot">
    <div class="loader h-27"></div>
    <p class="goal-conversion-rate-users-percentage ajax-loader goal_completion_percentage" ></p>
  </div>
</div>
<div class="white-box small-chart-box ">
 <div class="small-chart-box-head">
  <figure>
   <div class="loader  h-54"></div>
   <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
 </figure>
 <div class="loader  h-67"></div>
 <h6><big class="compare ajax-loader" id="goal-conversion-rate-organic"></big> Organic Traffic<span
   uk-tooltip="title: Organic Keywords Data Here...; pos: top-left"
   class="fa fa-info-circle"></span></h6>
 </div>
 <div class="chart">
  <div class="ConversionRateOrganicGraph ajax-loader loader-text h-60-chart"></div>
  <canvas id="goal-conversionRate-organic-chart-new"></canvas>
</div>
<div class="small-chart-box-foot">
  <div class="loader h-27"></div>
  <p class="goal-conversion-rate-organic-percentage ajax-loader goal_completion_percentage" ></p>
</div>
</div>
</div>
<!-- Chart Box 3 End -->
<!-- Chart Box 4 -->
<div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-2@m">
  <h5>Total Abandonment Rate</h5>
  <div class="white-box small-chart-box ">
   <div class="small-chart-box-head">
    <figure>
     <div class="loader  h-54"></div>
     <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
   </figure>
   <div class="loader  h-67"></div>
   <h6><big class="compare ajax-loader" id="goal-abondon-rate-users"></big> All Users<span
     uk-tooltip="title: Organic Keywords Data Here...; pos: top-left"
     class="fa fa-info-circle"></span></h6>
   </div>
   <div class="chart">
    <div class="goalAbandonRateGraph ajax-loader loader-text h-60-chart"></div>
    <canvas id="goal-abondon-all-users-new"></canvas>
  </div>
  <div class="small-chart-box-foot">
    <div class="loader h-27"></div>
    <p class="goal-abondon-rate-users-percentage ajax-loader goal_completion_percentage"></p>
  </div>
</div>
<div class="white-box small-chart-box ">
 <div class="small-chart-box-head">
  <figure>
   <div class="loader  h-54"></div>
   <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
 </figure>
 <div class="loader  h-67"></div>
 <h6><big class="compare ajax-loader" id="goal-abondon-rate-organic"></big>Organic Traffic<span
   uk-tooltip="title: Organic Keywords Data Here...; pos: top-left"
   class="fa fa-info-circle"></span></h6>
 </div>
 <div class="chart">
  <div class="AbondonRateOrganicGraph ajax-loader loader-text h-60-chart"></div>
  <canvas id="goal-abondonRate-organic-chart-new"></canvas>
</div>
<div class="small-chart-box-foot">
  <div class="loader h-27"></div>
  <p class="goal-abondon-rate-organic-percentage ajax-loader goal_completion_percentage" ></p>
</div>
</div>
</div>
<!-- Chart Box 4 End -->
</div>
<div class="goal-completion-tab">
 <div class="white-box-tab-head mb-20">
  <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .goalCompletionTabContent">
   <li><a href="#">Goal Completion Location</a></li>
   <li><a href="#">Source / Medium</a></li>
 </ul>
</div>
<div class="white-box-body pa-0">
  <div class="uk-switcher goalCompletionTabContent">
   <div>
    <div class="project-table-cover">
     <div class="project-table-body goalCompletionTable">
      <table id="goal_completion_location">
       <thead>
        <tr>
         <th>
          Goal Completion Location
        </th>
        <th>
          Goal Completions
        </th>
        <th>
          % Goal Completions
        </th>
      </tr>
    </thead>
    <tbody>
      @for($i=1; $i<=5; $i++)
      <tr> <td  class="ajax-loader"></td></tr>
      @endfor
    </tbody>
  </table>
</div>

<div class="project-table-foot goalCompletion-location-foot" id="goalCompletion-location-foot">
 <div class="project-entries">
  <p>................</p>
</div>
<div class="pagination GoalComp-Location">
  <ul class="pagination" role="navigation">
   <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
    <span class="page-link" aria-hidden="true">....</span>
  </li>
  <li class="page-item  active">
    <a class="page-link" href="javascript:;">...</a>
  </li>
  <li class="page-item ">
    <a class="page-link" href="javascript:;">...</a>
  </li>
  <li class="page-item">
    <a class="page-link" href="javascript:;" rel="next" aria-label="Next »">.....</a>
  </li>
</ul>
</div>
</div>
</div>
</div>
<div>
  <div class="project-table-cover">
   <div class="project-table-body goalCompletionTable">
    <table id="goal_completion_sourcemedium">
     <thead>
      <tr>
       <th>
        Source / Medium
      </th>
      <th>
        Goal Completions
      </th>
      <th>
        % Goal Completions
      </th>
    </tr>
  </thead>
  <tbody>
    @for($i=1; $i<=5; $i++)
    <tr class="ajax-loader"></tr>
    @endfor
  </tbody>
</table>
</div>
<div class="project-table-foot goalCompletion-sourceMedium-foot" id="goalCompletion-sourceMedium-foot">
 <div class="project-entries">
  <p>................</p>
</div>
<div class="pagination GoalComp-sourcemedium">
  <ul class="pagination" role="navigation">
   <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
    <span class="page-link" aria-hidden="true">....</span>
  </li>
  <li class="page-item  active">
    <a class="page-link" href="javascript:;">...</a>
  </li>
  <li class="page-item ">
    <a class="page-link" href="javascript:;">...</a>
  </li>
  <li class="page-item">
    <a class="page-link" href="javascript:;" rel="next" aria-label="Next »">.....</a>
  </li>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
@if(isset($data) && ($data->ecommerce_goals == 1))
<div class="white-box-body">
  @include('vendor.seo_sections.ecommerce_goals')
</div>
@endif
</div>
<!-- Google Analytics Goal Completion Row End -->
@if(Auth::user()->role_id !=4)
<div class="white-box mb-40 " id="goal_completion_add" style="<?php if($dashboardtype->google_analytics_id != ''){ echo "display: none"; } else{ echo "display: block"; } ?>">
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