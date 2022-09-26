 <!-- Google Analytics Goal Completion Row -->
  <div class="white-box pa-0 mb-40" id="ga4_goals_data" style="<?php if($connectivity['ga4'] == false){ echo "display: none"; } else{ echo "display: block"; } ?>">
    <div class="box-boxshadow">
      <div class="section-head">
        <div class="d-flex">
        <h4>
          <figure><img src="{{URL::asset('public/vendor/internal-pages/images/ga4.png')}}"></figure>
          Google Analytics Goal Completion (GA4)
          <font class="ga4_time"></font>
        </h4>
         <div class="ga4-range"></div>
      </div>
        <hr>
      </div>
      <p>Users by Session default channel grouping over time</p>
      <div class="chart h-360 ajax-loader usersBySession-defaultChannel-overTime-pdf box-boxshadow">
        <canvas id="usersBySession_defaultChannel_overTime_pdf" height="300"></canvas>
      </div>
      <p>Users by Session default channel grouping</p>
      <div class="chart h-360 ajax-loader usersBySession-defaultChannel-pdf box-boxshadow">
        <canvas id="usersBySession_defaultChannel_pdf" height="300"></canvas>
      </div>
      <div class="ga-table BreakBefore">
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