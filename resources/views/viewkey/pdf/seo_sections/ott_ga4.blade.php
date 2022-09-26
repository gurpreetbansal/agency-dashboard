<!-- Organic Traffic Growth Row -->
<div class="white-box pa-0 mb-40" id="analytics4_data" style="<?php if($connectivity['ga4'] == false){ echo "display: none"; } else{ echo "display: block"; } ?>">
  <div class="box-boxshadow">
    <div class="section-head">
      <div class="d-flex">
        <h4>
          <figure><img src="{{URL::asset('public/vendor/internal-pages/images/ga4.png')}}"></figure>
          Organic Traffic Trend (GA4)
          <font class="ga4_time"></font>
          <input type="hidden" class="campaign-id" value="{{@$campaign_id}}">
        </h4>
        <div class="ga4-range"></div>
      </div>
      <hr>
    </div>
    <div class="organic-user-box">
      <div class="single selected ajax-loader" id="all-user-box-pdf">
        <h6>Users</h6>
        <h4 class="active-users-pdf">0</h4>
        <p class="active-users-comparison-pdf"></p>
      </div>
      <div class="single ajax-loader" id="new-user-box-pdf">
        <h6>New Users</h6>
        <h4 class="new-users-pdf">0</h4>
        <p class="new-users-comparison-pdf"></p>
      </div>
    </div>
    <div class="ott-allUser-ga4-pdf height-300 chart h-360 ajax-loader">
      <canvas id="ott-ga4-pdf" height="300"></canvas>
    </div>
  </div>
</div>