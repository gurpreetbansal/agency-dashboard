<!-- Google Ads - Summary Row-->
<div class="white-box pa-0 mb-40">
  <div class="white-box-head">
    <div class="left">
      <div class="loader h-33 half-px"></div>
      <div class="heading">
          <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}">
          <div>
            <h2>Google Ads - Summary <span uk-tooltip="title: It shows a snapshot of the performance of all the key PPC metrics.; pos: top-left" class="fa fa-info-circle"></span></h2>
            <p class="adwords_time"></p>
            <p class="adwords_range"></p>
          </div>
      </div>
      </div>
      <div class="right">
        <div class="loader h-33 half-px"></div>
        <div class="filter-list">
          <ul>
            <li>
              <form>
                <label class='sw'>
                  <input type='checkbox' class="adwords_compare" <?php if(!empty(@$moduleadsStatus->status == 1)){echo "checked";}?>>
                  <div class='sw-pan'></div>
                  <div class='sw-btn'></div>
                </label>
              </form>
            </li>

            <li>
                <button type="button" class="adwords_list <?php if($selectedSearch == 7){ echo 'active'; }?>" data-value="seven">One week</button>
            </li>
            <li>
              <button type="button" class="adwords_list <?php if($selectedSearch == 14){ echo 'active'; }?>" data-value="fourteen">Two week</button>
            </li>

            <li>
              <button type="button" class="adwords_list <?php if($selectedSearch == 1){ echo 'active'; }?>" data-value="month">One Month</button>
            </li>
            <li>
              <button type="button" class="adwords_list <?php if($selectedSearch == 3){ echo 'active'; }?>" data-value="three">Three Month</button>
            </li>
            <li>
              <button type="button" class="adwords_list <?php if($selectedSearch == 6){ echo 'active'; }?>" data-value="six">Six Month</button>
            </li>
            <li>
              <button type="button" class="adwords_list <?php if($selectedSearch == 9){ echo 'active'; }?>" data-value="nine">Nine Month</button>
            </li>
            <li>
              <button type="button" class="adwords_list <?php if($selectedSearch == 12){ echo 'active'; }?>" data-value="year">One Year</button>
            </li>
            <!-- <li>
              <button type="button" class="adwords_list <?php if($selectedSearch == 24){ echo 'active'; }?>" data-value="twoyear">Two Year</button>
            </li> -->
          </ul>
          <input type="hidden" class="selected_range" value="{{@$selected_value}}">
        </div> 
      </div>
    </div>
    <div class="white-box-body">
      <div uk-grid>

        <!-- Chart Box 1 -->
        <div class="uk-width-1-4@xl uk-width-1-3@l uk-width-1-2@m">
          <div class="white-box small-chart-box ">

            <div class="small-chart-box-head">
              <figure>
                <div class="loader  h-54"></div>
                <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}">
              </figure>

              <h6 class="sv-overview ajax-loader"><big class="compare impressions-ads">0</big> Impressions <span
                uk-tooltip="title: It displays the number of times your ad is shown on a search  engine result page or related sites on the Google network.; pos: top-left" class="fa fa-info-circle"></span>
              </h6>
            </div>

            <div class="chart">
              <div class="impressions_graph ajax-loader loader-text h-60-chart"></div>
              <canvas id="summary-impressions-chart"></canvas>
            </div>

            <div class="small-chart-box-foot percentage-values">
              <p class="sv-overview ajax-loader"> <cite class="impressions-ads-percentage"><span uk-icon="icon: "></span>0%</cite></p>
            </div>
          </div>
        </div>
        <!-- Chart Box 1 End -->


        <!-- Chart Box 3 -->
        <div class="uk-width-1-4@xl uk-width-1-3@l uk-width-1-2@m">
          <div class="white-box small-chart-box ">

            <div class="small-chart-box-head">
              <figure>
                <div class="loader  h-54"></div>
                <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}">
              </figure>

              <h6 class="sv-overview ajax-loader"><big class="compare click-ads">0</big> Clicks <span uk-tooltip="title: It indicates how many times an ad was clicked.; pos: top-left"
                class="fa fa-info-circle"></span></h6>
              </div>

              <div class="chart">
                <div class="summary_clicks_graph ajax-loader loader-text h-60-chart"></div>
                <canvas id="summary-clicks-chart"></canvas>
              </div>

              <div class="small-chart-box-foot percentage-values">
                <div class="loader h-27"></div>
                <p class="sv-overview ajax-loader"> <cite class="clicks-ads-percentage"><span uk-icon="icon: "></span>0%</cite></p>
              </div>
            </div>
          </div>
          <!-- Chart Box 3 End -->


          <!-- Chart Box 5 -->
          <div class="uk-width-1-4@xl uk-width-1-3@l uk-width-1-2@m">
            <div class="white-box small-chart-box ">

              <div class="small-chart-box-head">
                <figure>
                  <div class="loader  h-54"></div>
                  <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}">
                </figure>

                <h6 class="sv-overview ajax-loader"><big class="compare ctr-ads">0%</big> CTR<span uk-tooltip="title: It is a performance metric that displays how often people click on your ad after it has been displayed.; pos: top-left"
                  class="fa fa-info-circle"></span></h6>
                </div>

                <div class="chart">
                  <div class="ctr_graph ajax-loader loader-text h-60-chart"></div>
                  <canvas id="summary-ctrAds-chart"></canvas>
                </div>

                <div class="small-chart-box-foot percentage-values">
                  <p class="sv-overview ajax-loader"> <cite class="ctr-ads-percentage"><span uk-icon="icon: "></span>0%</cite></p>
                </div>
              </div>
            </div>
            <!-- Chart Box 5 End -->

            <!-- Chart Box 2 -->
            <div class="uk-width-1-4@xl uk-width-1-3@l uk-width-1-2@m">
              <div class="white-box small-chart-box ">

                <div class="small-chart-box-head">
                  <figure>
                    <div class="loader  h-54"></div>
                    <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}">
                  </figure>

                  <h6 class="sv-overview ajax-loader"><big class="compare cost-ads">$0</big> Cost <span uk-tooltip="title: It is the sum of your cost per click (CPC) spend.; pos: top-left"
                    class="fa fa-info-circle"></span></h6>
                  </div>

                  <div class="chart">
                    <div class="costs_graph ajax-loader loader-text h-60-chart"></div>
                    <canvas id="summary-cost-chart"></canvas>
                  </div>

                  <div class="small-chart-box-foot percentage-values">
                    <p class="sv-overview ajax-loader"> <cite class="cost-ads-percentage"><span uk-icon="icon: "></span>0%</cite></p>
                  </div>
                </div>
              </div>
              <!-- Chart Box 2 End -->



              <!-- Chart Box 4 -->
              <div class="uk-width-1-4@xl uk-width-1-3@l uk-width-1-2@m">
                <div class="white-box small-chart-box ">

                  <div class="small-chart-box-head">
                    <figure>
                      <div class="loader  h-54"></div>
                      <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}">
                    </figure>

                    <h6 class="sv-overview ajax-loader"><big class="compare average-cpc-ads">$0.00</big> Average CPC<span
                      uk-tooltip="title: It is calculated by dividing the total cost of your clicks by the total number of clicks.; pos: top-left" class="fa fa-info-circle"></span>
                    </h6>
                  </div>

                  <div class="chart">
                    <div class="average_cpc_graph ajax-loader loader-text h-60-chart"></div>
                    <canvas id="summary-averageCpc-chart"></canvas>
                  </div>

                  <div class="small-chart-box-foot percentage-values">
                    <p class="sv-overview ajax-loader"> <cite class="average-cpc-ads-percentage"><span uk-icon="icon: "></span>0%</cite></p>
                  </div>
                </div>
              </div>
              <!-- Chart Box 4 End -->

              

              <!-- Chart Box 6 -->
              <div class="uk-width-1-4@xl uk-width-1-3@l uk-width-1-2@m">
                <div class="white-box small-chart-box ">

                  <div class="small-chart-box-head">
                    <figure>
                      <div class="loader  h-54"></div>
                      <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}">
                    </figure>

                    <h6 class="sv-overview ajax-loader"><big class="compare conversion-ads">00</big> Conversions<span
                      uk-tooltip="title: It is a result of someone clicking on your ad and taking an action you have defined such as purchasing or filling up a form.; pos: top-left" class="fa fa-info-circle"></span>
                    </h6>
                  </div>

                  <div class="chart">
                    <div class="conversions_graph ajax-loader loader-text h-60-chart"></div>
                    <canvas id="summary-conversionAds-chart"></canvas>
                  </div>

                  <div class="small-chart-box-foot percentage-values">
                    <p class="sv-overview ajax-loader"> <cite class="conversion-ads-percentage"><span uk-icon="icon: "></span>0%</cite></p>
                  </div>
                </div>
              </div>
              <!-- Chart Box 6 End -->

              <!-- Chart Box 7 -->
              <div class="uk-width-1-4@xl uk-width-1-3@l uk-width-1-2@m">
                <div class="white-box small-chart-box ">

                  <div class="small-chart-box-head">
                    <figure>
                      <div class="loader  h-54"></div>
                      <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}">
                    </figure>

                    <h6 class="sv-overview ajax-loader"><big class="compare conversion-rate-ads">0.00%</big> Conversion Rate <span
                      uk-tooltip="title: It is calculated by simply taking the number of conversions dividing it by the number of total clicks during the same time period.; pos: top-left"
                      class="fa fa-info-circle"></span></h6>
                    </div>

                    <div class="chart">
                      <div class="conversion_rate_graph ajax-loader loader-text h-60-chart"></div>
                      <canvas id="summary-conversionRate-chart"></canvas>
                    </div>

                    <div class="small-chart-box-foot percentage-values" >
                      <p class="sv-overview ajax-loader"> <cite class="conversion-rate-ads-percentage"><span uk-icon="icon: "></span>0%</cite></p>
                    </div>
                  </div>
                </div>
                <!-- Chart Box 7 End -->

                <!-- Chart Box 8 -->
                <div class="uk-width-1-4@xl uk-width-1-3@l uk-width-1-2@m">
                  <div class="white-box small-chart-box ">

                    <div class="small-chart-box-head">
                      <figure>
                        <div class="loader  h-54"></div>
                        <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-icon.png')}}">
                      </figure>

                      <h6 class="sv-overview ajax-loader"><big class="compare cost-per-conversion-rate-ads">00</big> Cost Per Conversion <span
                        uk-tooltip="title: It is the amount you have paid per conversion in ads.; pos: top-left"
                        class="fa fa-info-circle"></span></h6>
                      </div>

                      <div class="chart">
                        <div class="cpc_rate_graph ajax-loader loader-text h-60-chart"></div>
                        <canvas id="summary-costPerConversionRate-chart"></canvas>
                      </div>

                      <div class="small-chart-box-foot percentage-values" >
                        <p class="sv-overview ajax-loader"> <cite class="cost-per-conversion-rate-ads-percentage"><span uk-icon="icon: "></span>0%</cite></p>
                      </div>
                    </div>
                  </div>
                  <!-- Chart Box 8 End -->


                </div>
              </div>

            </div>
<!-- Google Ads - Summary Row End -->