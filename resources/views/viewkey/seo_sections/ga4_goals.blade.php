 <!-- Google Analytics Goal Completion Row -->
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
      <div class="filter-list traffic-right">
        <ul>
          <li class="ga4_range"></li>
          <li>
            <a href="javascript:;" id="ga4_goals_range_section" class="btn icon-btn color-blue" uk-tooltip="title: Date Range; pos: top-center">
              <img src="{{URL::asset('/public/vendor/internal-pages/images/date-rance-calender-icon.png')}}">
            </a>
          </li>
        </ul>
      </div>
  </div>

  <div class="dateRange-popup viewkey-popups" id="ga4-goals-dateRange-popup">
<form>
  <input type="hidden" class="ga4_datepicker_selection" value="1">
  <div class="dateRange-fields">
    <div class="form-group uk-flex">
      <label>Date Range</label>
    </div>
    <div class="form-group uk-flex">
      <div id="ga4_goals_current_range" class="form-control ga4_daterangepicker daterange-div">
        <input type="hidden" class="ga4_goals_start_date" value="{{@$ga4_start_date}}">
        <input type="hidden" class="ga4_goals_end_date" value="{{@$ga4_end_date}}">
        <input type="hidden" class="ga4_goals_current_label" value="{{@$ga4_selected}}">
        <input type="hidden" class="ga4_goals_comparison_days">
        <i class="fa fa-calendar"></i><p></p>
      </div>
    </div>
    <div class="form-group uk-flex">
      <input type="hidden" class="ga4_is_compare">
      <label class='sw'>
        <input type='checkbox' class="ga4_goals_compare" <?php if(isset($ga4_comparison) && @$ga4_comparison == 1){echo "checked";}?>>
        <div class='sw-pan'></div>
        <div class='sw-btn'></div>
      </label>
      <label>Compare to:</label>
      <select class="form-control" id="ga4_goals_comparison" <?php if(@$ga4_comparison == 0){echo "readonly disabled";}?>  >
        <option selected="selected" value="previous_period" {{@$ga4_compare_to === 'previous_period'?'selected':''}}>Previous period</option>
        <option value="previous_year" {{@$ga4_compare_to === 'previous_year'?'selected':''}}>Previous year</option>
      </select>
    </div>
    <div class="form-group uk-flex <?php if(@$ga4_comparison == 0){echo 'ga4-hidden-previous-datepicker';}?>" id="ga4-previous-section">
      <div id="ga4_goals_previous_range" class="form-control ga4_daterangepicker daterange-div">
        <input type="hidden" class="ga4_goals_prev_start_date" value="{{@$ga4_compare_start_date}}">
        <input type="hidden" class="ga4_goals_prev_end_date" value="{{@$ga4_compare_end_date}}">
        <input type="hidden" class="ga4_goals_prev_comparison_days">
        <i class="fa fa-calendar"></i><p></p>
      </div>
    </div>
    <div class="uk-flex">
      <input type="button" class="btn blue-btn ga4_goals_applyBtn" value="Apply" >
      <a href="javascript:;" class="ga4_goals_cancel_btn"><input type="button" class="btn btn-border red-btn-border" value="Cancel"></a>
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
          <div class="chart h-360 ajax-loader usersBySession-defaultChannel-overTime">
            <canvas id="usersBySession_defaultChannel_overTime" height="300"></canvas>
          </div>
        </div>
      </div>
      <div class="uk-width-2-5">
        <div class="white-box">
          <p>Users by Session default channel grouping</p>
          <div class="chart h-360 ajax-loader usersBySession-defaultChannel">
            <canvas id="usersBySession_defaultChannel" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="ga-table">
      <table class="ga-compare">
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
        <tbody class="ga-compare-result ajax-loader">
          <tr>
            <td colspan="11"><center>No data available</center></td>     
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>