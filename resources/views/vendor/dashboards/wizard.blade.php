<div class="card mb-4">
     <div class="card-header">
            <div class="media flex-wrap w-100 align-items-center">

                <div class="media-body ml-3">
                    <img src="{{URL::asset('public/vendor/images/google-logo-icon.png')}}">
                    <a href="javascript:void(0)">Please attach Google Analytics and Google Search Accounts to view complete dashboard.</a>
                </div>

            </div>
        </div>
    <input type="hidden" class="campaignId" value="{{\Request::segment(2)}}">
    <div class="card-body">



        <div class="integration-box">

            <div class="box analytics <?php if(!empty($dashboardtype->google_account_id) && !empty($dashboardtype->google_analytics_id)){ echo 'active'; }?>">
                <h5>Connect Google Analytics</h5>
                <p>Select an existing account or connect a new Google Account</p>
                <button type="button" class="mb-2 mr-2 btn btn-gradient-info" data-toggle="modal" data-target="#ConnectGoogleAnalyticsModal">Add Google Analytics Account</button>
            </div>
       

            <div class="box console <?php if(!empty($dashboardtype->google_console_id) && !empty($dashboardtype->console_account_id)){ echo 'active'; }?>">
                <h5>Connect Google Search Console</h5>
                <p>Select an existing account or connect a new Google Account</p>
                <button type="button" class="mb-2 mr-2 btn btn-gradient-info" data-toggle="modal" data-target="#ConnectGoogleSearchConsoleModal">Add Google Search Console Account</button>
            </div>
           <input type="hidden" class="skipvalue" value="SEO">
        </div>

        <div class="right newDashboardSkip">

            <button  class="mb-2 mr-2 btn btn-gradient-info newDashboardSkipBtn">Skip & continue</button>
        </div>
    </div>
</div>