 <!-- Google Ads - Clicks Chart Row -->
 <div class="white-box pa-0  mb-40">
 	<div class="white-box-head">
    <div class="left">
      <div class="loader h-33 half-px"></div>
      <div class="heading">
        <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}">
        <h2>Summary
          <span uk-tooltip="title: Take a look at the progress of the PPC campaign over a period of time. ; pos: top-left" class="fa fa-info-circle"
          title="" aria-expanded="false"></span>
        </h2>
      </div>
    </div>
    </div>
 	<div class="white-box-body">
 		<div class="summary_chart chart h-360 ajax-loader">
 			<canvas id="chart-summary"></canvas>
 		</div>
 	</div>
 </div>
            <!-- Google Ads - Clicks Chart Row End -->