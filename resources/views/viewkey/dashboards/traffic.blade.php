<div class="main-data-view" id="traffic" uk-sortable="handle:.white-box-handle">
<!-- Organic Traffic Growth Row -->
<div class="white-box pa-0 mb-40 " id="analytics_data_traffic" style="<?php if($connectivity['ua'] == false){ echo "display: none"; } else{ echo "display: block"; } ?>">
<div class="white-box-head">
  <!-- <span class="white-box-handle" uk-icon="icon: table"></span> -->
  <div class="left">
    <div class="heading ajax-loader">
      <img src="{{URL::asset('public/vendor/internal-pages/images/organic-traffic-growth-img.png')}}">
      <h2>Organic Traffic Trend
        <span uk-tooltip="title: This section shows total number of organic visits to your website in selected time period.; pos: top-left" class="fa fa-info-circle"></span></h2>
      </div>
    </div>
    <div class="right" id="analyticsFiltersRanks">
      <div class="filter-list ajax-loader" id="analytic-filter-list-ranks">
        <ul>
          <li>
            <form>
              <label class='sw'>
                <input type='checkbox' <?php if($comparison == 1) echo 'checked'?> class="analyticsGraphCompareRank">
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
          <button type="button" data-value="month" data-module="organic_traffic" class="graph_range_rank <?php if($selected == 1){ echo 'active'; }?>">One Month</button>
        </li>
        <li>
          <button type="button" data-value="three" data-module="organic_traffic" class="graph_range_rank <?php if($selected == 3){ echo 'active'; }?>">Three Month</button>
        </li>
        <li>
          <button type="button" data-value="six" data-module="organic_traffic" class="graph_range_rank <?php if($selected == 6){ echo 'active'; }?>">Six Month</button>
        </li>
        <li>
          <button type="button" data-value="nine" data-module="organic_traffic" class="graph_range_rank <?php if($selected == 9){ echo 'active'; }?>">Nine Month</button>
        </li>
        <li>
          <button type="button" data-value="year" data-module="organic_traffic" class="graph_range_rank <?php if($selected == 12){ echo 'active'; }?>">One Year</button>
        </li>
        <li>
          <button type="button" data-value="twoyear" data-module="organic_traffic" class="graph_range_rank <?php if($selected == 24){ echo 'active'; }?>">Two Year</button>
        </li>
      </ul>
    </div>

    <div class="filter-list style2">
      <ul>
        <li><button type="button" class="btn btn-sm btn-border blue-btn-border organic_traffic_displayType_rank <?php if($display_type == 'day'){ echo 'blue-btn'; }?>"  data-type="day">Day</button></li>
        <li><button type="button" class="btn btn-sm btn-border blue-btn-border organic_traffic_displayType_rank <?php if($display_type == 'week'){ echo 'blue-btn'; }?>"  data-type="week">Week</button></li>
        <li><button type="button" class="btn btn-sm btn-border blue-btn-border organic_traffic_displayType_rank <?php if($display_type == 'month'){ echo 'blue-btn'; }?>"  data-type="month">Month</button></li>
      </ul>
    </div>

  </div>

  <div class="white-box-body">
    <!-- Organic Box Data Row -->
    <div uk-grid class="mb-20">
      <!-- Organic Box 1 -->
      <div class="uk-width-1-3@s">
        <div class="white-box medium-chart-box">
          <div class="medium-chart-box-head ">
            <h6 class="session-count ajax-loader"><span class="" uk-icon="icon: arrow-down"></span> 0%</h6>
            <p class="ajax-loader"><img src="{{URL::asset('public/vendor/internal-pages/images/sessions-img.png')}}"> Sessions</p>
          </div>
          <div class="medium-chart-box-foot">
            <p class="compare-session ajax-loader">0 Organic Traffic</p>
          </div>
        </div>
      </div>
      <!-- Organic Box 1 End -->

      <!-- Organic Box 2 -->
      <div class="uk-width-1-3@s">
        <div class="white-box medium-chart-box">
          <div class="medium-chart-box-head">
            <h6 class="user-count ajax-loader"><span class="" uk-icon="icon: arrow-down"></span> 0%</h6>
            <p class="ajax-loader"><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> Users</p>
          </div>
          <div class="medium-chart-box-foot">
            <p class="compare-user ajax-loader">0 Organic Traffic</p>
          </div>
        </div>
      </div>
      <!-- Organic Box 2 End -->

      <!-- Organic Box 3 -->
      <div class="uk-width-1-3@s">
        <div class="white-box medium-chart-box">
          <div class="medium-chart-box-head">
            <h6 class="pageview-count ajax-loader"><span class="" uk-icon="icon: arrow-up"></span> 0%</h6>
            <p class="ajax-loader"><img src="{{URL::asset('public/vendor/internal-pages/images/pageviews-img.png')}}"> Pageviews</p>
          </div>
          <div class="medium-chart-box-foot">
            <p class="compare-pageview ajax-loader">0 Organic Traffic</p>
          </div>
        </div>
      </div>
      <!-- Organic Box 3 End -->

    </div>
    <!-- Organic Box Data Row End -->

    <div class="traffic-growth-graph height-300 chart h-360 ajax-loader">
      <canvas id="new-canvas-traffic-growth-rank" height="300"></canvas>
    </div>
  </div>
</div>
<!-- Organic Traffic Growth Row End -->


<!-- Organic Traffic Growth Row -->
<div class="white-box pa-0 mb-40" id="analytics4_data_traffic" style="<?php if($connectivity['ga4'] == false){ echo "display: none"; } else{ echo "display: block"; } ?>">
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
  <li class="traffic-ga4-range"></li>
  <li>
    <a href="javascript:;" id="ga4_seoTraffic_range_section" class="btn icon-btn color-blue" uk-tooltip="title: Date Range; pos: top-center">
      <img src="{{URL::asset('/public/vendor/internal-pages/images/date-rance-calender-icon.png')}}">
    </a>
  </li>
</ul>
</div>
</div>
<div class="dateRange-popup viewkey-popups" id="ga4-seoTraffic-dateRange-popup">
<form>
<input type="hidden" class="ga4_datepicker_selection" value="1">
<input type="hidden" class="vk-ga4-sidebar-selected" value="dashboard">
<div class="dateRange-fields">
  <div class="form-group uk-flex">
    <label>Date Range</label>
  </div>
  <div class="form-group uk-flex">
    <div id="ga4_seoTraffic_current_range" class="form-control ga4_daterangepicker daterange-div">
      <input type="hidden" class="ga4_seoTraffic_start_date" value="{{@$ga4_start_date}}">
      <input type="hidden" class="ga4_seoTraffic_end_date" value="{{@$ga4_end_date}}">
      <input type="hidden" class="ga4_seoTraffic_current_label" value="{{@$ga4_selected}}">
      <input type="hidden" class="ga4_seoTraffic_comparison_days">
      <i class="fa fa-calendar"></i><p></p>
    </div>
  </div>
  <div class="form-group uk-flex">
    <input type="hidden" class="ga4_is_compare">
    <label class='sw'>
      <input type='checkbox' class="ga4_seoTraffic_compare" <?php if(isset($ga4_comparison) && @$ga4_comparison == 1){echo "checked";}?>>
      <div class='sw-pan'></div>
      <div class='sw-btn'></div>
    </label>
    <label>Compare to:</label>
    <select class="form-control" id="ga4_seoTraffic_comparison" <?php if(@$ga4_comparison == 0){echo "readonly disabled";}?>  >
      <option selected="selected" value="previous_period" {{@$ga4_compare_to === 'previous_period'?'selected':''}}>Previous period</option>
      <option value="previous_year" {{@$ga4_compare_to === 'previous_year'?'selected':''}}>Previous year</option>
    </select>
  </div>
  <div class="form-group uk-flex <?php if(@$ga4_comparison == 0){echo 'ga4-hidden-previous-datepicker';}?>" id="seoTraffic-ga4-previous-section">
    <div id="ga4_seoTraffic_previous_range" class="form-control ga4_daterangepicker daterange-div">
      <input type="hidden" class="ga4_seoTraffic_prev_start_date" value="{{@$ga4_compare_start_date}}">
      <input type="hidden" class="ga4_seoTraffic_prev_end_date" value="{{@$ga4_compare_end_date}}">
      <input type="hidden" class="ga4_seoTraffic_prev_comparison_days">
      <i class="fa fa-calendar"></i><p></p>
    </div>
  </div>
  <div class="uk-flex">
    <input type="button" class="btn blue-btn ga4_seoTraffic_apply_btn" value="Apply" >
    <a href="javascript:;" class="ga4_seoTraffic_cancel_btn"><input type="button" class="btn btn-border red-btn-border" value="Cancel"></a>
  </div>
</div>
</form>
</div>
</div>

<div class="white-box-body">
<div class="traffic-organic-user-box">
<div class="single selected ajax-loader" id="seoTraffic-all-user-box">
<h6>Users</h6>
<h4 class="traffic-active-users">0</h4>
<p class="traffic-active-users-comparison"></p>
</div>
<div class="single ajax-loader" id="seoTraffic-new-user-box">
<h6>New Users</h6>
<h4 class="traffic-new-users">0</h4>
<p class="traffic-new-users-comparison"></p>
</div>
</div>

<div class="seoTraffic-growth-graph-allUser-ga4 height-300 chart h-360 ajax-loader">
<canvas id="traffic-ott-ga4" height="300"></canvas>
</div>

<div class="seoTraffic-growth-graph-newUser-ga4 height-300 chart h-360 ajax-loader" style="display: none;">
<canvas id="traffic-ott-newUser-ga4" height="300"></canvas>
</div>
</div>
</div>
<!-- ga4 organic traffic trend end-->

<div class="white-box mb-40 " id="analytics_data-contact" style="<?php if($connected == false){ echo "display: block"; } else{ echo "display: none"; } ?>">
  <div class="integration-list">
    <article>
      <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-goal-completion-img.png')}}"></figure>
      <div>
        <p>The Source is not active on your account.</p>
        <?php 
        if(isset($profile_data) && !empty($profile_data) && (isset($profile_data->ProfileInfo) && ($profile_data->ProfileInfo <> null)) && isset($profile_data->ProfileInfo->email)){
          $email = $profile_data->ProfileInfo->email;
        }else{
          $email = $profile_data->UserInfo->email;
        }
        ?>
        <a href="mailto:{{$email}}" class="btn btn-border blue-btn-border">Contact us</a>
      </div>
    </article>
  </div>
</div>
</div>  