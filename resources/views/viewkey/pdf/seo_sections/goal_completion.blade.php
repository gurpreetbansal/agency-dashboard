
<input type="hidden" class="campaign_id" value="{{@$campaign_id}}">
<div class="white-box pa-0 mb-40 space-top white-box-handle" id="analytics_data_goal">
  <div class="box-boxshadow">
    <div class="section-head">
      <h4>
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-goal-completion-img.png')}}"></figure> 
        Google Analytics Goals
        <font class="analytics_time"></font>
      </h4>
      <hr />
      <p>
        <small><em><img src="{{URL::asset('public/vendor/internal-pages/images/info.png')}}"> This section shows all goals setup in your Google Analytics account.</em></small>
      </p>
    </div>
    <div class="chart mb-40 h-230 goal-completion-graph">
      <canvas id="canvas-goal-completion" height="300"></canvas>
    </div>
  </div>
  <div class="white-box-body">
    <div class="goal-completion-box">
      <div class="uk-width-1-1">
        <h5>Goal Completions</h5>
        <div class="white-box small-chart-box goals-chart-box">
          <div class="small-chart-box-head">
            <figure>

              <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
            </figure>

            <h6><big class="compare " id="goal-completion-users">0.00% </big></h6>
            <p>All Users</p>
          </div>
          <div class="chart goal-completion-all-users-div">
            <canvas id="goal-completion-all-users-new"></canvas>
          </div>
          <div class="small-chart-box-foot">

            <p class="goal-completion-users-percentage  goal_completion_percentage"></p>
          </div>
        </div>
        <div class="white-box small-chart-box goals-chart-box">
          <div class="small-chart-box-head">
            <figure>

              <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
            </figure>

            <h6><big class="compare " id="goal-completion-traffic">0.00% </big></h6>
            <p> Organic Traffic</p>
          </div>
          <div class="chart">

            <canvas id="goal-completion-organic-new"></canvas>
          </div>
          <div class="small-chart-box-foot">

            <p class="goal-completion-traffic-percentage  goal_completion_percentage" ></p>
          </div>
        </div>
      </div>
    </div>
    <div class="goal-completion-box BreakBefore">
      <div class="uk-width-1-1">
        <h5>Goal Value</h5>
        <div class="white-box small-chart-box goals-chart-box">
          <div class="small-chart-box-head">
            <figure>

              <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
            </figure>

            <h6><big class="compare " id="goal-value-users"></big> </h6>
            <p>All Users</p>
          </div>
          <div class="chart">

            <canvas id="goal-value-all-users-new"></canvas>
          </div>
          <div class="small-chart-box-foot">

            <p class="goal-value-users-percentage  goal_completion_percentage"></p>
          </div>
        </div>
        <div class="white-box small-chart-box goals-chart-box">
          <div class="small-chart-box-head">
            <figure>

              <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
            </figure>

            <h6><big class="compare " id="goal-value-organic"></big></h6>
            <p>Organic Traffic</p>
          </div>
          <div class="chart">

            <canvas id="goal-value-organic-chart-new"></canvas>
          </div>
          <div class="small-chart-box-foot">

            <p class="goal-value-organic-percentage  goal_completion_percentage"></p>
          </div>
        </div>
      </div>
    </div>
    <div class="goal-completion-box">
      <div class="uk-width-1-1">
        <h5>Goal Conversion Rate</h5>
        <div class="white-box small-chart-box goals-chart-box">
          <div class="small-chart-box-head">
            <figure>

              <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
            </figure>

            <h6><big class="compare " id="goal-conversion-rate-users"></big></h6>
            <p>All Users</p>
          </div>
          <div class="chart">

            <canvas id="goal-conversion-all-users-new"></canvas>
          </div>
          <div class="small-chart-box-foot">

            <p class="goal-conversion-rate-users-percentage  goal_completion_percentage" ></p>
          </div>
        </div>
        <div class="white-box small-chart-box goals-chart-box">
          <div class="small-chart-box-head">
            <figure>

              <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
            </figure>

            <h6><big class="compare " id="goal-conversion-rate-organic"></big> </h6>
            <p>Organic Traffic</p>
          </div>
          <div class="chart">

            <canvas id="goal-conversionRate-organic-chart-new"></canvas>
          </div>
          <div class="small-chart-box-foot">

            <p class="goal-conversion-rate-organic-percentage  goal_completion_percentage" ></p>
          </div>
        </div>
      </div>
    </div>
    <div class="goal-completion-box">
      <div class="uk-width-1-1">
        <h5>Total Abandonment Rate</h5>
        <div class="white-box small-chart-box goals-chart-box">
          <div class="small-chart-box-head">
            <figure>

              <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
            </figure>

            <h6><big class="compare " id="goal-abondon-rate-users"></big> </h6>
            <p>All Users</p>
          </div>
          <div class="chart">

            <canvas id="goal-abondon-all-users-new"></canvas>
          </div>
          <div class="small-chart-box-foot">

            <p class="goal-abondon-rate-users-percentage  goal_completion_percentage"></p>
          </div>
        </div>
        <div class="white-box small-chart-box goals-chart-box">
          <div class="small-chart-box-head">
            <figure>

              <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
            </figure>

            <h6><big class="compare " id="goal-abondon-rate-organic"></big></h6>
            <p>Organic Traffic</p>
          </div>
          <div class="chart">

            <canvas id="goal-abondonRate-organic-chart-new"></canvas>
          </div>
          <div class="small-chart-box-foot">

            <p class="goal-abondon-rate-organic-percentage  goal_completion_percentage" ></p>
          </div>
        </div>
      </div>
    </div>

    <div class="goal-completion-tab mb-20 BreakBefore">
      <div class="white-box-tab-head mb-20">
        <ul class="uk-subnav uk-subnav-pill">
          <li class="uk-active"><a href="#">Goal Completion Location</a></li>
        </ul>
      </div>
      <div class="box-boxshadow">
        <div class="white-box-body pa-0">
          <div class="goalCompletionTabContent">
            <div class="project-table-cover table-box">
              <div class="project-table-body goalCompletionTable">
                <table id="goal_completion_location">
                  <thead>
                    <tr>
                      <th>
                        Goal Completion Location
                      </th>
                      <th>
                        Goal Completions
                      </th>
                      <th>
                        % Goal Completions
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    @for($i=1; $i<=5; $i++)
                    <tr> <td></td></tr>
                    @endfor
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="goal-completion-tab BreakBefore">
      <div class="white-box-tab-head mb-20">
        <ul class="uk-subnav uk-subnav-pill">
          <li class="uk-active"><a href="#">Source / Medium</a></li>
        </ul>
      </div>
      <div class="box-boxshadow">
        <div class="white-box-body pa-0">
          <div class="goalCompletionTabContent">
            <div class="project-table-cover table-box">
              <div class="project-table-body goalCompletionTable">
                <table id="goal_completion_sourcemedium">
                  <thead>
                    <tr>
                      <th>
                        Source / Medium
                      </th>
                      <th>
                        Goal Completions
                      </th>
                      <th>
                        % Goal Completions
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    @for($i=1; $i<=5; $i++)
                    <tr></tr>
                    @endfor
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>