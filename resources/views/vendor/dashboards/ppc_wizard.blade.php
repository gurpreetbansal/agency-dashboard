<div class="card mb-4">
   <div class="card-header">
    <div class="media flex-wrap w-100 align-items-center">

        <div class="media-body ml-3">
            <img src="{{URL::asset('public/vendor/images/google-logo-icon.png')}}">
            <a href="javascript:void(0)">Please attach Google Adwords Account to view complete dashboard.</a>
        </div>

    </div>
</div>
<input type="hidden" class="campaignId" value="{{\Request::segment(2)}}">
<div class="card-body">
    <div class="integration-box">
        <div class="box adwords <?php if(!empty($dashboardtype->google_ads_id) && !empty($dashboardtype->google_ads_campaign_id)){ echo 'active'; }?>">
           <h5>Google Ads Account</h5>
           <p>Select an existing account or connect a new Google Ads Account </p>
           <button type="button" class="mb-2 mr-2 btn btn-gradient-info" data-toggle="modal" data-target="#ConnectGoogleAdsModal">Add Google Ads Account</button>
       </div>
       

       <input type="hidden" class="skipvalue" value="PPC">
   </div>

   <div class="right newDashboardSkip">
    <button  class="mb-2 mr-2 btn btn-gradient-info newDashboardSkipBtn">Skip & continue</button>
</div>
</div>
</div>