<div class="popup" data-pd-popup="showProjectReachedPopup" id="showProjectReachedPopup">
    <div class="popup-inner">
        <h5>You have reached your Project limit, upgrade to continue.</h5>
        <a class="popup-close" data-pd-popup-close="showProjectReachedPopup" href="javascript:;" id="showProjectReachedPopup_close"></a>
    </div>
</div>

<div class="popup" data-pd-popup="showLiveKeywordCountPopup" id="showLiveKeywordCountPopup">
    <div class="popup-inner">
        <h5>You have reached your keyword limit, upgrade to continue.</h5>
        <a class="popup-close" data-pd-popup-close="showLiveKeywordCountPopup" href="javascript:;" id="showLiveKeywordCountPopup_close"></a>
    </div>
</div>

<div class="popup redirecting-popup" data-pd-popup="preparingFacebookDashboard" id="preparingFacebookDashboard">
    <div class="popup-inner">
        <div class="preloader-popup">
            <img src="{{URL::asset('public/vendor/internal-pages/images/preloader.gif')}}" alt="loader">
        </div>
        <h5>Preparing your Facebook Dashboard, <br>it may take few seconds</h5>
        <a class="popup-close" data-pd-popup-close="preparingFacebookDashboard" href="javascript:;" id="preparingFacebookDashboard_close"></a>
    </div>
</div>

<!-- Facebook connect popup start-->
<div class="popup" data-pd-popup="projectSettingFacebookPopup">
    <div class="popup-inner">
      <div class="facebook-progress-loader  popup-progress-loader"></div>
      <h3>Connect Your Facebook Account</h3>

      <div class="popup-elem-group">
        <div class="elem-left right-border">
          <form id="facebook_setting_popup">
            <div class="form-group">
              <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/facebook.png')}}"></span>
              <select class="form-control selectpicker " id="settings_facebook_existing_accounts" data-live-search="true">
                <option value="">Select from existing account</option>
                </select>
                <span class="errorStyle"><p id="show_facebook_last_time"></p></span>
                <div class="facebook_refresh_div  refresh-account-div">
                  <a href="javascript:;" id="refresh_facebook_account" class="btn icon-btn color-orange" uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
                    <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                  </a>
                </div>
                <div class="facebook-account-loader project-setting-loaders">
                  <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                </div>
              </div>
              <h6>Select a Page</h6>
              <div class="form-group">
                <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                <select class="form-control selectpicker" id="settings_facebook_accounts" data-live-search="true">
                  <option value="">Select from existing pages</option>
                </select>
                <div class="facebook-loader project-setting-loaders">
                  <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                </div>
              </div>
              <div class="text-left btn-group start">
                <input type="button" class="btn blue-btn mr-3" value="Save" id="settings_save_facebook_account">
              </div>
            </form>
          </div>
          <div class="elem-right flex">
            <a href="javascript:;" class="btn yellow-btn settings_FacebookAddBtn" id="settings_add_new_facebook_account">Add New Account</a>
          </div>
        </div>

        <a class="popup-close" data-pd-popup-close="projectSettingFacebookPopup" href="javascript:;" id="project_setting_facebook_close"></a>
      </div>
</div>
<!-- Facebook connect popup end-->