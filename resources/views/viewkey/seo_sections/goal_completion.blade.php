<!-- Google Analytics Goal Completion Row -->
<input type="hidden" class="campaign_id" value="{{@$campaign_id}}">
<div class="white-box pa-0 mb-40 " id="analytics_data_goal" style="<?php if($connectivity['ua'] == false){ echo "display: none"; } else{ echo "display: block"; } ?>">
 <div class="white-box-head">
  <div class="left">
   <div class="heading">
    <img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-goal-completion-img.png')}}">
    <h2>Google Analytics Goal Completion
     <span uk-tooltip="title: This section shows goal completion from Google Analytics in selected time period. ; pos: top-left"
     class="fa fa-info-circle"></span>
   </h2>
 </div>
</div>
<div class="right" id="GoalCompletionFilters">
  <!-- <div class="loader h-33 half-px"></div> -->
  <div class="filter-list" id="goal-filter-list">
    <ul>
      <li>
        <form>
          <label class='sw'>
            <input type='checkbox'  class="analyticsGraphCompare_goal" <?php if($comparison==1){ echo "checked";}?>>
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
        <button type="button" data-value="month" data-module="organic_traffic" class="graph_range_viewkey <?php if($selected == 1){echo 'active';}?>">One Month</button>
      </li>
      <li>
        <button type="button" data-value="three" data-module="organic_traffic" class="graph_range_viewkey <?php if($selected == 3){echo 'active';}?>">Three Month</button>
      </li>
      <li>
        <button type="button" data-value="six" data-module="organic_traffic" class="graph_range_viewkey <?php if($selected == 6){echo 'active';}?>">Six Month</button>
      </li>
      <li>
        <button type="button" data-value="nine" data-module="organic_traffic" class="graph_range_viewkey <?php if($selected == 9){echo 'active';}?>">Nine Month</button>
      </li>
      <li>
        <button type="button" data-value="year" data-module="organic_traffic" class="graph_range_viewkey <?php if($selected == 12){echo 'active';}?>">One Year</button>
      </li>
      <li>
        <button type="button" data-value="twoyear" data-module="organic_traffic" class="graph_range_viewkey <?php if($selected == 24){echo 'active';}?>">Two Year</button>
      </li>
    </ul>
  </div>
  <div class="filter-list style2">
    <ul>
      <li><button type="button" class="btn btn-sm btn-border blue-btn-border traffic_display_typeGoal <?php if($display_type == 'day'){ echo 'blue-btn'; }?>"  data-type="day">Day</button></li>
      <li><button type="button" class="btn btn-sm btn-border blue-btn-border traffic_display_typeGoal <?php if($display_type == 'week'){ echo 'blue-btn'; }?>"  data-type="week">Week</button></li>
      <li><button type="button" class="btn btn-sm btn-border blue-btn-border traffic_display_typeGoal <?php if($display_type == 'month'){ echo 'blue-btn'; }?>"  data-type="month">Month</button></li>
    </ul>
  </div>
</div>
<div class="white-box-body">
  <div class="chart mb-40 height-300 ajax-loader goal-completion-graph">
   <canvas id="canvas-goal-completion" height="300"></canvas>
 </div>
 <div uk-grid class="goal-completion-box">
   <!-- Chart Box 1 -->
   <div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-1@m">
    <h5>Goal Completions <span uk-tooltip="title: The total number of conversions. ; pos: top-left" class="fa fa-info-circle"></span></h5>
    <div class="white-box small-chart-box goals-chart-box">
     <div class="small-chart-box-head">
      <figure>
       <!-- <div class="loader  h-54"></div> -->
       <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
     </figure>
     <!-- <div class="loader  h-67"></div> -->
     <h6><big class="compare ajax-loader" id="goal-completion-users">0.00% </big> All Users</h6>
     </div>
     <div class="chart goal-completion-all-users-div">
      <div class="allUserGraph ajax-loader loader-text h-60-chart"></div>
      <canvas id="goal-completion-all-users-new"></canvas>
    </div>
    <div class="small-chart-box-foot">
      <!-- <div class="loader h-27"></div> -->
      <p class="goal-completion-users-percentage ajax-loader goal_completion_percentage"></p>
    </div>
  </div>
  <div class="white-box small-chart-box goals-chart-box">
   <div class="small-chart-box-head">
    <figure>
     <!-- <div class="loader  h-54"></div> -->
     <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
   </figure>
   <!-- <div class="loader  h-67"></div> -->
   <h6><big class="compare ajax-loader" id="goal-completion-traffic">0.00% </big> Organic Traffic </h6>
   </div>
   <div class="chart">
    <div class="OrganicGraph ajax-loader loader-text h-60-chart"></div>
    <canvas id="goal-completion-organic-new"></canvas>
  </div>
  <div class="small-chart-box-foot">
    <!-- <div class="loader h-27"></div> -->
    <p class="goal-completion-traffic-percentage ajax-loader goal_completion_percentage" ></p>
  </div>
</div>
</div>
<!-- Chart Box 1 End -->
<!-- Chart Box 2 -->
<div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-1@m">
  <h5>Goal Value <span uk-tooltip="title: Total Goal Value is the total value produced by goal conversions on your site. This value is calculated by multiplying the number of goal conversions by the value that you assigned to each goal. ; pos: top-left" class="fa fa-info-circle"></span></h5>
  <div class="white-box small-chart-box  goals-chart-box">
   <div class="small-chart-box-head">
    <figure>
     <!-- <div class="loader  h-54"></div> -->
     <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
   </figure>
   <!-- <div class="loader  h-67"></div> -->
   <h6><big class="compare ajax-loader" id="goal-value-users"></big> All Users</h6>
   </div>
   <div class="chart">
    <div class="goalValueGraph ajax-loader loader-text h-60-chart"></div>
    <canvas id="goal-value-all-users-new"></canvas>
  </div>
  <div class="small-chart-box-foot">
    <!-- <div class="loader h-27"></div> -->
    <p class="goal-value-users-percentage ajax-loader goal_completion_percentage"></p>
  </div>
</div>
<div class="white-box small-chart-box  goals-chart-box">
 <div class="small-chart-box-head">
  <figure>
   <!-- <div class="loader  h-54"></div> -->
   <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
 </figure>
 <!-- <div class="loader  h-67"></div> -->
 <h6><big class="compare ajax-loader" id="goal-value-organic"></big> Organic Traffic</h6>
 </div>
 <div class="chart">
  <div class="ValueOrganicGraph ajax-loader loader-text h-60-chart"></div>
  <canvas id="goal-value-organic-chart-new"></canvas>
</div>
<div class="small-chart-box-foot">
  <!-- <div class="loader h-27"></div> -->
  <p class="goal-value-organic-percentage ajax-loader goal_completion_percentage"></p>
</div>
</div>
</div>
<!-- Chart Box 2 End -->
<!-- Chart Box 3 -->
<div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-1@m">
  <h5>Goal Conversion Rate <span uk-tooltip="title:  The sum of all individual goal conversion rates. ; pos: top-left" class="fa fa-info-circle"></span></h5>
  <div class="white-box small-chart-box  goals-chart-box">
   <div class="small-chart-box-head">
    <figure>
     <!-- <div class="loader  h-54"></div> -->
     <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
   </figure>
   <!-- <div class="loader  h-67"></div> -->
   <h6><big class="compare ajax-loader" id="goal-conversion-rate-users"></big> All Users</h6>
   </div>
   <div class="chart">
    <div class="goalConversionRateGraph ajax-loader loader-text h-60-chart"></div>
    <canvas id="goal-conversion-all-users-new"></canvas>
  </div>
  <div class="small-chart-box-foot">
    <!-- <div class="loader h-27"></div> -->
    <p class="goal-conversion-rate-users-percentage ajax-loader goal_completion_percentage" ></p>
  </div>
</div>
<div class="white-box small-chart-box  goals-chart-box">
 <div class="small-chart-box-head">
  <figure>
   <!-- <div class="loader  h-54"></div> -->
   <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
 </figure>
 <!-- <div class="loader  h-67"></div> -->
 <h6><big class="compare ajax-loader" id="goal-conversion-rate-organic"></big> Organic Traffic</h6>
 </div>
 <div class="chart">
  <div class="ConversionRateOrganicGraph ajax-loader loader-text h-60-chart"></div>
  <canvas id="goal-conversionRate-organic-chart-new"></canvas>
</div>
<div class="small-chart-box-foot">
  <!-- <div class="loader h-27"></div> -->
  <p class="goal-conversion-rate-organic-percentage ajax-loader goal_completion_percentage" ></p>
</div>
</div>
</div>
<!-- Chart Box 3 End -->
<!-- Chart Box 4 -->
<div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-1@m">
  <h5>Total Abandonment Rate <span uk-tooltip="title:  The rate at which goals were abandoned. Defined as Total Abandoned Funnels divided by Total Goal Starts. ; pos: top-left" class="fa fa-info-circle"></span></h5>
  <div class="white-box small-chart-box  goals-chart-box">
   <div class="small-chart-box-head">
    <figure>
     <!-- <div class="loader  h-54"></div> -->
     <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
   </figure>
   <!-- <div class="loader  h-67"></div> -->
   <h6><big class="compare ajax-loader" id="goal-abondon-rate-users"></big> All Users</h6>
   </div>
   <div class="chart">
    <div class="goalAbandonRateGraph ajax-loader loader-text h-60-chart"></div>
    <canvas id="goal-abondon-all-users-new"></canvas>
  </div>
  <div class="small-chart-box-foot">
    <!-- <div class="loader h-27"></div> -->
    <p class="goal-abondon-rate-users-percentage ajax-loader goal_completion_percentage"></p>
  </div>
</div>
<div class="white-box small-chart-box  goals-chart-box">
 <div class="small-chart-box-head">
  <figure>
   <!-- <div class="loader  h-54"></div> -->
   <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
 </figure>
 <!-- <div class="loader  h-67"></div> -->
 <h6><big class="compare ajax-loader" id="goal-abondon-rate-organic"></big>Organic Traffic</h6>
 </div>
 <div class="chart">
  <div class="AbondonRateOrganicGraph ajax-loader loader-text h-60-chart"></div>
  <canvas id="goal-abondonRate-organic-chart-new"></canvas>
</div>
<div class="small-chart-box-foot">
  <!-- <div class="loader h-27"></div> -->
  <p class="goal-abondon-rate-organic-percentage ajax-loader goal_completion_percentage" ></p>
</div>
</div>
</div>
<!-- Chart Box 4 End -->
</div>
<div class="goal-completion-tab">
 <div class="white-box-tab-head mb-20">
  <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .goalCompletionTabContent; swiping: false">
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
@if(isset($dashboardtype) && ($dashboardtype->ecommerce_goals == 1))
<!-- E-commerce section start -->
<div class="white-box-body">
    @include('viewkey.seo_sections.ecommerce_goals')
</div>
    <!-- E-commerce section end -->
@endif
</div>
<!-- Google Analytics Goal Completion Row End -->
