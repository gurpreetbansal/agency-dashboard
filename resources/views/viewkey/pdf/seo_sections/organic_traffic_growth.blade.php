<!-- Organic Traffic Growth Row -->
<div class="white-box pa-0 mb-40 space-top noBreakAfter" id="analytics_data" style="<?php if($data->google_analytics_id == ''){ echo "display: none"; } else{ echo "display: block"; } ?>" >
 <div class="box-boxshadow">
  <div class="section-head">
    <h4>
      <figure><img src="{{URL::asset('public/vendor/internal-pages/images/organic-traffic-growth-img.png')}}"></figure>
      Organic Search : Traffic Growth
      <font class="analytics_time"></font>
    </h4>
    <hr />
    <p>
      This section shows clicks and impressions in Google Search Console for selected time period, It’s a great way to understand your website’s visibility in Google.
    </p>
    <ul>
      <li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Session:</b> Total number of sessions on your website for the selected time period.</li>
      <li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Users:</b> Total number of users coming to your website for the selected time period.</li>
      <li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Pageviews:</b> Total number of pageviews by users for the selected time period.</li>
    </ul>
  </div>

  <div class="white-box-body">
    <!-- Organic Box Data Row -->
    <div uk-grid>

     <!-- Organic Box 1 -->
     <div class="uk-width-1-3">
      <div class="white-box medium-chart-box">
        <div class="medium-chart-box-head">
          <h6 class="session-count"><span class="" uk-icon="icon: arrow-down"></span> 0%</h6>
          <img src="{{URL::asset('public/vendor/internal-pages/images/sessions-img.png')}}">
        Sessions</p>
      </div>
      <div class="medium-chart-box-foot">
        <p class="compare-session">0 Organic Traffic</p>
      </div>
    </div>
  </div>
  <!-- Organic Box 1 End -->
  <!-- Organic Box 2 -->
  <div class="uk-width-1-3">
    <div class="white-box medium-chart-box">
      <div class="medium-chart-box-head">
        <h6 class="user-count"><span class="" uk-icon="icon: arrow-down"></span> 0%</h6>
        <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> Users</p>
      </div>
      <div class="medium-chart-box-foot">
        <p class="compare-user">0 Organic Traffic</p>
      </div>
    </div>
  </div>
  <!-- Organic Box 2 End -->

  <!-- Organic Box 3 -->
  <div class="uk-width-1-3">
    <div class="white-box medium-chart-box">
      <div class="medium-chart-box-head">
        <h6 class="pageview-count"><span class="" uk-icon="icon: arrow-up"></span> 0%</h6>
        <p><img src="{{URL::asset('public/vendor/internal-pages/images/pageviews-img.png')}}"> Pageviews</p>
      </div>
      <div class="medium-chart-box-foot">
        <p class="compare-pageview">0 Organic Traffic</p>
      </div>
    </div>
  </div>
  <!-- Organic Box 3 End -->
</div>
<!-- Organic Box Data Row End -->

<div class="traffic-growth-graph chart h-360">
  <canvas id="new-canvas-traffic-growth" height="300"></canvas>
</div>
</div>
</div>
</div>
<!-- Organic Traffic Growth Row End -->