<!-- Organic Traffic Growth Row -->
<div class="white-box pa-0 mb-40" id="analytics4_data" style="<?php if($connectivity['ga4'] == false){ echo "display: none"; } else{ echo "display: block"; } ?>">
<div class="white-box-head">
 <div class="left">
  <div class="loader h-33 half-px"></div>
  <div class="heading">
    <img src="{{URL::asset('public/vendor/internal-pages/images/ga4.png')}}">
    <div>
      <h2>Organic Traffic Trend (GA4)
        <span uk-tooltip="title: This section shows total number of organic visits to your website in selected time period.; pos: top-left"
        class="fa fa-info-circle"></span>
      </h2>
      <p class="ga4_time"></p>
      <input type="hidden" class="campaign-id" value="{{@$campaign_id}}">
    </div>
  </div>
</div>
<div class="right">
  <div class="loader h-33 half-px"></div>
  <div class="filter-list traffic-right">
    <ul>
      <li class="ga4_range"></li>
      <li>
        <a href="javascript:;" id="ga4_range_section" class="btn icon-btn color-blue" uk-tooltip="title: Date Range; pos: top-center">
          <img src="{{URL::asset('/public/vendor/internal-pages/images/date-rance-calender-icon.png')}}">
        </a>
      </li>
    </ul>
  </div>
</div>
<div class="dateRange-popup viewkey-popups" id="ga4-dateRange-popup">
<form>
  <input type="hidden" class="ga4_datepicker_selection" value="1">
  <input type="hidden" class="vk-ga4-sidebar-selected" value="dashboard">
  <div class="dateRange-fields">
    <div class="form-group uk-flex">
      <label>Date Range</label>
    </div>
    <div class="form-group uk-flex">
      <div id="ga4_current_range" class="form-control ga4_daterangepicker daterange-div">
        <input type="hidden" class="ga4_start_date" value="{{@$ga4_start_date}}">
        <input type="hidden" class="ga4_end_date" value="{{@$ga4_end_date}}">
        <input type="hidden" class="ga4_current_label" value="{{@$ga4_selected}}">
        <input type="hidden" class="ga4_comparison_days">
        <i class="fa fa-calendar"></i><p></p>
      </div>
    </div>
    <div class="form-group uk-flex">
      <input type="hidden" class="ga4_is_compare">
      <label class='sw'>
        <input type='checkbox' class="ga4_compare" <?php if(isset($ga4_comparison) && @$ga4_comparison == 1){echo "checked";}?>>
        <div class='sw-pan'></div>
        <div class='sw-btn'></div>
      </label>
      <label>Compare to:</label>
      <select class="form-control" id="ga4_comparison" <?php if(@$ga4_comparison == 0){echo "readonly disabled";}?>  >
        <option selected="selected" value="previous_period" {{@$ga4_compare_to === 'previous_period'?'selected':''}}>Previous period</option>
        <option value="previous_year" {{@$ga4_compare_to === 'previous_year'?'selected':''}}>Previous year</option>
      </select>
    </div>
    <div class="form-group uk-flex <?php if(@$ga4_comparison == 0){echo 'ga4-hidden-previous-datepicker';}?>" id="ga4-previous-section">
      <div id="ga4_previous_range" class="form-control ga4_daterangepicker daterange-div">
        <input type="hidden" class="ga4_prev_start_date" value="{{@$ga4_compare_start_date}}">
        <input type="hidden" class="ga4_prev_end_date" value="{{@$ga4_compare_end_date}}">
        <input type="hidden" class="ga4_prev_comparison_days">
        <i class="fa fa-calendar"></i><p></p>
      </div>
    </div>
    <div class="uk-flex">
      <input type="button" class="btn blue-btn ga4_apply_btn" value="Apply" >
      <a href="javascript:;" class="ga4_cancel_btn"><input type="button" class="btn btn-border red-btn-border" value="Cancel"></a>
    </div>
  </div>
</form>
</div>
</div>

<div class="white-box-body">
<div class="organic-user-box">
  <div class="single selected ajax-loader" id="all-user-box">
    <h6>Users</h6>
    <h4 class="active-users">0</h4>
    <p class="active-users-comparison"></p>
  </div>
  <div class="single ajax-loader" id="new-user-box">
    <h6>New Users</h6>
    <h4 class="new-users">0</h4>
    <p class="new-users-comparison"></p>
  </div>
</div>

<div class="traffic-growth-graph-allUser-ga4 height-300 chart h-360 ajax-loader">
  <canvas id="ott-ga4" height="300"></canvas>
</div>

<div class="traffic-growth-graph-newUser-ga4 height-300 chart h-360 ajax-loader" style="display: none;">
  <canvas id="ott-newUser-ga4" height="300"></canvas>
</div>
</div>
</div>