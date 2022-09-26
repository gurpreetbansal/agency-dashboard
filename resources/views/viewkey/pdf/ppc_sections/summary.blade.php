 <!-- Google Ads - Summary Row-->
 <div class="white-box pa-0 mb-40 white-box-handle">
   <div class="section-head">
    <h2>Google Ads - Summary</h2>
    <p class="adwords_range"></p>
  </div>
  <div class="white-box-body">
    <div uk-grid class="goal-completion-box">

      <!-- Chart Box 1 -->
      <div class="uk-width-1-4">
        <div class="white-box small-chart-box w-100 chartBox">
          <div class="small-chart-box-head">
            <div class="uk-flex">
              <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}"></figure>
              <h6 class="sv-overview">
                Impressions
                <small>ADS</small>
              </h6>
            </div>
            <big class="compare impressions-ads"></big>
          </div>
          <div class="chart">
            <canvas id="summary-impressions-chart"></canvas>
          </div>
          <div class="small-chart-box-foot percentage-values">
            <p class="sv-overview"> 
              <cite class="impressions-ads-percentage">
                <span uk-icon="icon: "></span>0%
              </cite>
            </p>
          </div>
        </div>
      </div>
      <!-- Chart Box 1 End -->

      <div class="uk-width-1-4">
        <div class="white-box small-chart-box w-100 chartBox">
          <div class="small-chart-box-head">
            <div class="uk-flex">
              <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}"></figure>
              <h6 class="sv-overview">
                Clicks 
                <small>ADS</small>
              </h6>
            </div>
            <big class="compare click-ads"></big>
          </div>
          <div class="chart">
            <canvas id="summary-clicks-chart"></canvas>
          </div>            
          <div class="small-chart-box-foot percentage-values">
            <p class="sv-overview"> 
              <cite class="clicks-ads-percentage">
                <span uk-icon="icon: "></span>0%
              </cite>
            </p>
          </div>
        </div>
      </div>


      <div class="uk-width-1-4">
        <div class="white-box small-chart-box w-100 chartBox">
          <div class="small-chart-box-head">
            <div class="uk-flex">
              <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}"></figure>
              <h6 class="sv-overview">
                CTR 
                <small>ADS</small>
              </h6>
            </div>
            <big class="compare ctr-ads"></big>
          </div>
          <div class="chart">
            <canvas id="summary-ctrAds-chart"></canvas>
          </div>
          <div class="small-chart-box-foot percentage-values">
            <p class="sv-overview"> 
              <cite class="ctr-ads-percentage">
                <span uk-icon="icon: "></span>0%
              </cite>
            </p>
          </div>
        </div>
      </div>

      <div class="uk-width-1-4">
        <div class="white-box small-chart-box w-100 chartBox">
          <div class="small-chart-box-head">
            <div class="uk-flex">
              <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}"></figure>
              <h6 class="sv-overview">
                Cost 
                <small>ADS</small>
              </h6>
            </div>
            <big class="compare cost-ads"></big>
          </div>
          <div class="chart">
            <canvas id="summary-cost-chart"></canvas>
          </div>
          <div class="small-chart-box-foot percentage-values">
            <p class="sv-overview"> 
              <cite class="cost-ads-percentage">
                <span uk-icon="icon: "></span>0%
              </cite>
            </p>
          </div>
        </div>
      </div>

      

      <div class="uk-width-1-4">
        <div class="white-box small-chart-box w-100 chartBox">
          <div class="small-chart-box-head">
            <div class="uk-flex">
              <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}"></figure>
              <h6 class="sv-overview">
                Average CPC
                <small>ADS</small>
              </h6>
            </div>
            <big class="compare average-cpc-ads"></big>
          </div>
          <div class="chart">
            <canvas id="summary-averageCpc-chart"></canvas>
          </div>
          <div class="small-chart-box-foot percentage-values">
            <p class="sv-overview"> 
              <cite class="average-cpc-ads-percentage">
                <span uk-icon="icon: "></span>0%
              </cite>
            </p>
          </div>
        </div>
      </div>

      

      <div class="uk-width-1-4">
        <div class="white-box small-chart-box w-100 chartBox">
          <div class="small-chart-box-head">
            <div class="uk-flex">
              <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}"></figure>
              <h6 class="sv-overview">
                Conversions
                <small>ADS</small>
              </h6>
            </div>
            <big class="compare conversion-ads"></big>
          </div>
          <div class="chart">
            <canvas id="summary-conversionAds-chart"></canvas>
          </div>
          <div class="small-chart-box-foot percentage-values">
            <p class="sv-overview"> 
              <cite class="conversion-ads-percentage">
                <span uk-icon="icon: "></span>0%
              </cite>
            </p>
          </div>
        </div>
      </div>

      <div class="uk-width-1-4">
        <div class="white-box small-chart-box w-100 chartBox">
          <div class="small-chart-box-head">
            <div class="uk-flex">
              <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}"></figure>
              <h6 class="sv-overview">
                Conversion Rate 
                <small>ADS</small>
              </h6>
            </div>
            <big class="compare conversion-rate-ads"></big>
          </div>
          <div class="chart">
            <canvas id="summary-conversionRate-chart"></canvas>
          </div>
          <div class="small-chart-box-foot percentage-values">
            <p class="sv-overview"> 
              <cite class="conversion-rate-ads-percentage">
                <span uk-icon="icon: "></span>0%
              </cite>
            </p>
          </div>
        </div>
      </div>

      <div class="uk-width-1-4">
        <div class="white-box small-chart-box w-100 chartBox">
          <div class="small-chart-box-head">
            <div class="uk-flex">
              <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}"></figure>
              <h6 class="sv-overview">
                Cost Per Conversion 
                <small>ADS</small>
              </h6>
            </div>
            <big class="compare cost-per-conversion-rate-ads"></big>
          </div>
          <div class="chart">
            <canvas id="summary-costPerConversionRate-chart"></canvas>
          </div>
          <div class="small-chart-box-foot percentage-values">
            <p class="sv-overview"> 
              <cite class="cost-per-conversion-rate-ads-percentage">
                <span uk-icon="icon: "></span>0%
              </cite>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
 <!-- Google Ads - Summary Row End -->