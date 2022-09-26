<input type="hidden" value="{{$campaign_id}}" class="campaignID">


<div class="tabs-animation">
     <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title GAGoalCompletionSection">
                        <img src="{{URL::asset('/public/vendor/images/ReportGoogleAnaLyticsGoals-icon.png')}}">
                        Google Analytics Goal Completion
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive"> 
                        <table class="table table-bordered data-table" id="googleAnalyticsGoalCompletionVIewKey">
                            <thead>
                                <tr>
                                    <th>Keyword</th>
                                    <th>Sessions</th>
                                    <th>New Users</th>
                                    <th>Bounce Rate</th>
                                    <th>Page/Session</th>
                                    <th>Avg. Session Duration</th>
                                    <th>Goal Conversion Rate</th>
                                    <th>Goal Completions</th>
                                    <th>Goal Value</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>