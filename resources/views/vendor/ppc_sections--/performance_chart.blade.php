  <!-- Google Ads - Cost Chart Row -->
  <div class="white-box  pa-0 mb-40 ">
  	<div class="white-box-head">
    <div class="left">
      <div class="loader h-33 half-px"></div>
      <div class="heading">
        <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}">
        <h2>Performance
          <span uk-tooltip="title: It shows the real-time performance data of your PPC campaign over a period of time.; pos: top-left" class="fa fa-info-circle"  title="" aria-expanded="false"></span>
        </h2>
      </div>
    </div>
  </div>
  	<div class="white-box-body">
  		<div class="performance_chart chart h-360 ajax-loader">
  			<canvas id="performance-chart"></canvas>
  		</div>
  	</div>
  </div>
<!-- Google Ads - Cost Chart Row End -->