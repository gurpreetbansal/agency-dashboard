<div class="main-data-view" id="goals">
	<!-- Google Analytics Goal Completion Row -->
  <input type="hidden" class="campaign_id" value="{{@$campaign_id}}">
  <div class="white-box pa-0 mb-40 " id="analytics_data_goalmore" style="<?php if($connectivity['ua'] == false){ echo "display: none"; } else{ echo "display: block"; } ?>">
   <div class="white-box-head">
    <div class="left">
     <div class="heading">
      <img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-goal-completion-img.png')}}">
      <h2>Google Analytics Goal Completion
       <span uk-tooltip="title: This section shows goal completion from Google Analytics in selected time period.; pos: top-left" class="fa fa-info-circle"></span>
     </h2>
   </div>
 </div>
 <div class="right" id="GoalCompletionFilters">
  <div class="filter-list ajax-loader" id="goal-filter-list">
    <ul>
      <li>
        <form>
          <label class='sw'>
            <input type='checkbox'  class="analyticsGraphCompare_view" <?php if($comparison==1){ echo "checked";}?>>
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
        <button type="button" data-value="month" data-module="organic_traffic" class="graph_range_view_goal  <?php if($selected == 1){echo 'active';}?>">One Month</button>
      </li>
      <li>
        <button type="button" data-value="three" data-module="organic_traffic" class="graph_range_view_goal  <?php if($selected == 3){echo 'active';}?>">Three Month</button>
      </li>
      <li>
        <button type="button" data-value="six" data-module="organic_traffic" class="graph_range_view_goal  <?php if($selected == 6){echo 'active';}?>">Six Month</button>
      </li>
      <li>
        <button type="button" data-value="nine" data-module="organic_traffic" class="graph_range_view_goal  <?php if($selected == 9){echo 'active';}?>">Nine Month</button>
      </li>
      <li>
        <button type="button" data-value="year" data-module="organic_traffic" class="graph_range_view_goal  <?php if($selected == 12){echo 'active';}?>">One Year</button>
      </li>
      <li>
        <button type="button" data-value="twoyear" data-module="organic_traffic" class="graph_range_view_goal  <?php if($selected == 24){echo 'active';}?>">Two Year</button>
      </li>
    </ul>
  </div>
   <div class="filter-list style2">
    <ul>
      <li><button type="button" class="btn btn-sm btn-border blue-btn-border traffic_display_type_goal <?php if($display_type == 'day'){ echo 'blue-btn'; }?>"  data-type="day">Day</button></li>
      <li><button type="button" class="btn btn-sm btn-border blue-btn-border traffic_display_type_goal <?php if($display_type == 'week'){ echo 'blue-btn'; }?>"  data-type="week">Week</button></li>
      <li><button type="button" class="btn btn-sm btn-border blue-btn-border traffic_display_type_goal <?php if($display_type == 'month'){ echo 'blue-btn'; }?>"  data-type="month">Month</button></li>
    </ul>
  </div>
</div>
<div class="white-box-body">
  <div class="chart mb-40 height-300 ajax-loader goal-completion-graph">
   <canvas id="canvas-goal-completion-goals" height="300"></canvas>
 </div>
 <div uk-grid class="goal-completion-box">
   <!-- Chart Box 1 -->
   <div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-1@m">
    <h5>Goal Completions <span uk-tooltip="title: The total number of conversions. ; pos: top-left" class="fa fa-info-circle"></span></h5>
    <div class="white-box small-chart-box goals-chart-box">
     <div class="small-chart-box-head">
      <figure class="ajax-loader">
       <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
     </figure>
     <h6 class="ajax-loader"><big class="compare ajax-loader" id="goal-completion-usersGoals">0.00% </big> All Users</h6>
     </div>
     <div class="chart goal-completion-all-users-div">
      <div class="allUserGraph ajax-loader loader-text h-60-chart"></div>
      <canvas id="goal-completion-all-usersGoals"></canvas>
    </div>
    <div class="small-chart-box-foot">
      <p class="goal-completion-users-percentage ajax-loader goal_completion_percentage ajax-loader"></p>
    </div>
  </div>
  <div class="white-box small-chart-box goals-chart-box">
   <div class="small-chart-box-head">
    <figure class="ajax-loader">
     
     <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
   </figure>
   <h6 class="ajax-loader"><big class="compare ajax-loader" id="goal-completion-trafficGoals">0.00% </big> Organic Traffic</h6>
   </div>
   <div class="chart">
    <div class="OrganicGraph ajax-loader loader-text h-60-chart"></div>
    <canvas id="goal-completion-organicGoals"></canvas>
  </div>
  <div class="small-chart-box-foot">
    <p class="goal-completion-traffic-percentage ajax-loader goal_completion_percentage ajax-loader" ></p>
  </div>
</div>
</div>
<!-- Chart Box 1 End -->
<!-- Chart Box 2 -->
<div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-1@m">
  <h5>Goal Value <span uk-tooltip="title: Total Goal Value is the total value produced by goal conversions on your site. This value is calculated by multiplying the number of goal conversions by the value that you assigned to each goal. ; pos: top-left" class="fa fa-info-circle"></span></h5>
  <div class="white-box small-chart-box  goals-chart-box">
   <div class="small-chart-box-head">
    <figure class="ajax-loader">
     <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
   </figure>
   <h6 class="ajax-loader"><big class="compare ajax-loader" id="goal-value-usersGoals"></big> All Users</h6>
   </div>
   <div class="chart">
    <div class="goalValueGraph ajax-loader loader-text h-60-chart"></div>
    <canvas id="goal-value-all-usersGoals"></canvas>
  </div>
  <div class="small-chart-box-foot">
    <p class="goal-value-users-percentage ajax-loader goal_completion_percentage ajax-loader"></p>
  </div>
</div>
<div class="white-box small-chart-box  goals-chart-box">
 <div class="small-chart-box-head">
  <figure class="ajax-loader">
   <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
 </figure>
 <h6 class="ajax-loader"><big class="compare ajax-loader" id="goal-value-organicGoals"></big> Organic Traffic</h6>
 </div>
 <div class="chart">
  <div class="ValueOrganicGraph ajax-loader loader-text h-60-chart"></div>
  <canvas id="goal-value-organic-chartGoals"></canvas>
</div>
<div class="small-chart-box-foot">
  <p class="goal-value-organic-percentage ajax-loader goal_completion_percentage ajax-loader"></p>
</div>
</div>
</div>
<!-- Chart Box 2 End -->
<!-- Chart Box 3 -->
<div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-1@m">
  <h5>Goal Conversion Rate <span uk-tooltip="title:  The sum of all individual goal conversion rates. ; pos: top-left" class="fa fa-info-circle"></span></h5>
  <div class="white-box small-chart-box  goals-chart-box">
   <div class="small-chart-box-head">
    <figure class="ajax-loader">
     <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
   </figure>
   
   <h6 class="ajax-loader"><big class="compare ajax-loader" id="goal-conversion-rate-usersGoals"></big> All Users</h6>
   </div>
   <div class="chart">
    <div class="goalConversionRateGraph ajax-loader loader-text h-60-chart"></div>
    <canvas id="goal-conversion-all-usersGoals"></canvas>
  </div>
  <div class="small-chart-box-foot">
    <p class="goal-conversion-rate-users-percentage ajax-loader goal_completion_percentage ajax-loader" ></p>
  </div>
</div>
<div class="white-box small-chart-box goals-chart-box ">
 <div class="small-chart-box-head">
  <figure class="ajax-loader">
   <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
 </figure>
 <h6 class="ajax-loader"><big class="compare ajax-loader" id="goal-conversion-rate-organicGoals"></big> Organic Traffic</h6>
 </div>
 <div class="chart">
  <div class="ConversionRateOrganicGraph ajax-loader loader-text h-60-chart"></div>
  <canvas id="goal-conversionRate-organic-chartGoals"></canvas>
</div>
<div class="small-chart-box-foot">
  <p class="goal-conversion-rate-organic-percentage ajax-loader goal_completion_percentage ajax-loader" ></p>
</div>
</div>
</div>
<!-- Chart Box 3 End -->
<!-- Chart Box 4 -->
<div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-1@m">
  <h5>Total Abandonment Rate <span uk-tooltip="title:  The rate at which goals were abandoned. Defined as Total Abandoned Funnels divided by Total Goal Starts. ; pos: top-left" class="fa fa-info-circle"></span></h5>
  <div class="white-box small-chart-box  goals-chart-box">
   <div class="small-chart-box-head">
    <figure class="ajax-loader">
     <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
   </figure>
   <h6 class="ajax-loader"><big class="compare ajax-loader" id="goal-abondon-rate-usersGoals"></big> All Users</h6>
   </div>
   <div class="chart">
    <div class="goalAbandonRateGraph ajax-loader loader-text h-60-chart"></div>
    <canvas id="goal-abondon-all-usersGoals"></canvas>
  </div>
  <div class="small-chart-box-foot">
    <p class="goal-abondon-rate-users-percentage ajax-loader goal_completion_percentage ajax-loader"></p>
  </div>
</div>
<div class="white-box small-chart-box  goals-chart-box">
 <div class="small-chart-box-head">
  <figure class="ajax-loader">
   <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
 </figure>
 <h6 class="ajax-loader"><big class="compare ajax-loader" id="goal-abondon-rate-organicGoals"></big>Organic Traffic</h6>
 </div>
 <div class="chart">
  <div class="AbondonRateOrganicGraph ajax-loader loader-text h-60-chart"></div>
  <canvas id="goal-abondonRate-organic-chartGoals"></canvas>
</div>
<div class="small-chart-box-foot">
  <p class="goal-abondon-rate-organic-percentage ajax-loader goal_completion_percentage ajax-loader" ></p>
</div>
</div>
</div>
<!-- Chart Box 4 End -->
</div>
<div class="goal-completion-tab">
 <div class="white-box-tab-head mb-20">
  <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .goalCompletionTabContent" swiping="false">
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
<!-- E-commerce section start -->
<div class="white-box-body">
    @include('viewkey.dashboards.ecommerce_goals')
</div>
    <!-- E-commerce section end -->
@endif
</div>
<!-- Google Analytics Goal Completion Row End -->

<!-- Google Analytics 4 start -->
<div class="white-box pa-0 mb-40" id="ga4_goals_data" style="<?php if($connectivity['ga4'] == false){ echo "display: none"; } else{ echo "display: block"; } ?>">
  <div class="white-box-head">
   <div class="left">
    <div class="heading">
      <img src="{{URL::asset('public/vendor/internal-pages/images/ga4.png')}}">
      <div>
        <h2>Google Analytics Goal Completion (GA4)
          <span uk-tooltip="title: This section shows total number of organic visits to your website in selected time period.; pos: top-left"
          class="fa fa-info-circle"></span>
        </h2>
        <p class="ga4_time"></p>
      </div>
    </div>
  </div>

  <div class="right">
    <div class="loader h-33 half-px"></div>
    <div class="filter-list traffic-right">
      <ul>
        <li class="ga4Goals-ga4-range"></li>
        <li>
          <a href="javascript:;" id="ga4_SeoGoals_range_section" class="btn icon-btn color-blue" uk-tooltip="title: Date Range; pos: top-center">
            <img src="{{URL::asset('/public/vendor/internal-pages/images/date-rance-calender-icon.png')}}">
          </a>
        </li>
      </ul>
    </div>
  </div>

  <div class="dateRange-popup viewkey-popups" id="ga4-SeoGoals-dateRange-popup">
    <form>
      <input type="hidden" class="ga4_datepicker_selection" value="1">
      <input type="hidden" class="vk-ga4-sidebar-selected" value="dashboard">
      <div class="dateRange-fields">
        <div class="form-group uk-flex">
          <label>Date Range</label>
        </div>
        <div class="form-group uk-flex">
          <div id="ga4_SeoGoals_current_range" class="form-control ga4_daterangepicker daterange-div">
            <input type="hidden" class="ga4_SeoGoals_start_date" value="{{@$ga4_start_date}}">
            <input type="hidden" class="ga4_SeoGoals_end_date" value="{{@$ga4_end_date}}">
            <input type="hidden" class="ga4_SeoGoals_current_label" value="{{@$ga4_selected}}">
            <input type="hidden" class="ga4_SeoGoals_comparison_days">
            <i class="fa fa-calendar"></i><p></p>
          </div>
        </div>
        <div class="form-group uk-flex">
          <input type="hidden" class="ga4_is_compare">
          <label class='sw'>
            <input type='checkbox' class="ga4_SeoGoals_compare" <?php if(isset($ga4_comparison) && @$ga4_comparison == 1){echo "checked";}?>>
            <div class='sw-pan'></div>
            <div class='sw-btn'></div>
          </label>
          <label>Compare to:</label>
          <select class="form-control" id="ga4_SeoGoals_comparison" <?php if(@$ga4_comparison == 0){echo "readonly disabled";}?>  >
            <option selected="selected" value="previous_period" {{@$ga4_compare_to === 'previous_period'?'selected':''}}>Previous period</option>
            <option value="previous_year" {{@$ga4_compare_to === 'previous_year'?'selected':''}}>Previous year</option>
          </select>
        </div>
        <div class="form-group uk-flex <?php if(@$ga4_comparison == 0){echo 'ga4-hidden-previous-datepicker';}?>" id="SeoGoals-ga4-previous-section">
          <div id="ga4_SeoGoals_previous_range" class="form-control ga4_daterangepicker daterange-div">
            <input type="hidden" class="ga4_SeoGoals_prev_start_date" value="{{@$ga4_compare_start_date}}">
            <input type="hidden" class="ga4_SeoGoals_prev_end_date" value="{{@$ga4_compare_end_date}}">
            <input type="hidden" class="ga4_SeoGoals_prev_comparison_days">
            <i class="fa fa-calendar"></i><p></p>
          </div>
        </div>
        <div class="uk-flex">
          <input type="button" class="btn blue-btn ga4_SeoGoals_apply_btn" value="Apply" >
          <a href="javascript:;" class="ga4_SeoGoals_cancel_btn"><input type="button" class="btn btn-border red-btn-border" value="Cancel"></a>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="white-box-body google-analytics-section">

  <div uk-grid class="uk-grid">
    <div class="uk-width-3-5">
      <div class="white-box">
        <p>Users by Session default channel grouping over time</p>
        <div class="chart h-360 ajax-loader goals-usersBySession-defaultChannel-overTime">
          <canvas id="usersBySession_defaultChannel_overTime_goals" height="300"></canvas>
        </div>
      </div>
    </div>
    <div class="uk-width-2-5">
      <div class="white-box">
        <p>Users by Session default channel grouping</p>
        <div class="chart h-360 ajax-loader goals-usersBySession-defaultChannel">
          <canvas id="usersBySession_defaultChannel_goals" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="ga-table">
    <table class="ga-compare-goals">
      <thead>
        <tr>
          <th></th>
          <th>Users</th>
          <th>Sessions</th>
          <th>Engaged sessions</th>
          <th>Average engagement time per session</th>
          <th>Engaged sessions per user</th>
          <th>Events per session</th>
          <th>Engagement rate</th>
          <th>Event count</th>
          <th>Conversions</th>
          <th>Total revenue</th>
        </tr>
      </thead>
      <tbody class="ga-compare-goals-result ajax-loader">
        <tr>
          <td colspan="11"><center>No data available</center></td>     
        </tr>
      </tbody>
    </table>
  </div>
</div>
</div>
<!-- Google Analytics 4 end -->

<div class="white-box mb-40 " id="goal_data_rank-view" style="<?php if($connected == false){ echo "display: block"; } else{ echo "display: none"; } ?>">
  <div class="integration-list">
    <article>
      <figure> 
        <img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-goal-completion-img.png')}}">
      </figure>
      <div>
        <p>The Source is not active on your acoount.</p>
        <?php 
        if(isset($profile_data->ProfileInfo->email)){
          $email = $profile_data->ProfileInfo->email;
        }else{
          $email = $profile_data->UserInfo->email;
        }
        ?>
        <a href="mailto: {{$email}}" class="btn btn-border blue-btn-border">Contact us</a>
      </div>
    </article>
  </div>
</div>

</div>  