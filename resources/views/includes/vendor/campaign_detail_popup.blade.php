<input type="hidden" class="campaign_id" value="{{@$campaign_id}}">
<input type="hidden" class="currentRoute" value="{{\Request::Segment(1)}}">

<div class="popup" data-pd-popup="campaignDetailAnalytics">
    <div class="popup-inner">
        <div class="analytics-progress-loader popup-progress-loader"></div>
        <h3>Connect Your Google Analytics Account</h3>

        <div class="popup-elem-group">
            <div class="elem-left right-border">
                <form>

                    <div class="form-group">
                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="detail_analytics_existing_emails" data-live-search="true">
                            <option value="">Select from existing account</option>
                            <?php
                            if(!empty($get_analytics_emails)){
                             foreach($get_analytics_emails as $account){
                              ?>
                              <option value="{{$account->id}}">{{$account->email}}</option>
                          <?php } } ?>
                      </select>
                      <span class="errorStyle"><p id="show_analytics_last_time"></p></span>
                      <div class="analytics_refresh_div refresh-account-div">
                        <a href="javascript:;" id="refresh_analytics_account_detail" class="btn icon-btn color-orange" uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                        </a>
                    </div>
                </div>
                <h6>Select a Campaign</h6>
                <div class="form-group">

                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                    <select class="form-control selectpicker" id="detail_analytics_accounts" data-live-search="true">
                        <option value="">Select Account</option>
                    </select>
                    <div class="analytic-account-detail-loader detail-loaders">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                    </div>
                </div>
                <div class="form-group">
                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-property-icon.png')}}"></span>
                    <select class="form-control selectpicker" id="detail_analytics_property" data-live-search="true">
                        <option value="">Select Property</option>
                    </select>
                    <div class="analytic-property-detail-loader detail-loaders">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                    </div>
                </div>
                <div class="form-group">
                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/pageviews-img-small.png')}}"></span>
                    <select class="form-control selectpicker" id="detail_analytics_view" data-live-search="true">
                        <option value="">Select View</option>
                    </select>
                    <div class="analytic-view-detail-loader detail-loaders">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                    </div>
                </div>

                <div class="form-group">
                    <div uk-grid>
                      <div class="uk-width-1-2@s">
                        <label>E-commerce Goals</label>
                    </div>
                    <div class="uk-width-1-2@s">
                        <label class='sw'>
                          <input name="ecommerce_goals" type="checkbox" class="detail_ecommerce_goals">
                          <div class='sw-pan'></div>
                          <div class='sw-btn'></div>
                      </label>
                  </div>
              </div>
          </div>
          <div class="text-left btn-group start">
            <input type="button" class="btn blue-btn mr-3" value="Save" id="save_detail_analytics_account">
        </div>
    </form>
</div>
<div class="elem-right flex">
    <a href="javascript:;" class="btn yellow-btn detail_analyticsAddBtn" id="detail_add_new_analytics_account">Add New Account</a>
</div>
</div>

<a class="popup-close" data-pd-popup-close="campaignDetailAnalytics" href="javascript:;" id="detail_analytics_close"></a>
</div>
</div>


<div class="popup" data-pd-popup="CampaignDetailConsolePopup">
    <div class="popup-inner">
        <div class="searchConsole-progress-loader popup-progress-loader"></div>
        <h3>Connect Your Google Search Console Account</h3>

        <div class="popup-elem-group">
            <div class="elem-left right-border">
                <form>
                    <div class="form-group">
                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="detail_search_console_existing_emails" data-live-search="true">
                            <option value="">Select from existing account</option>
                            <?php
                            if(!empty($getConsoleAccount)){
                             foreach($getConsoleAccount as $account){
                              ?>
                              <option value="{{$account->id}}">{{$account->email}}</option>
                          <?php } } ?>
                      </select>
                      <span class="errorStyle"><p id="show_search_console_last_time"></p></span>
                      <div class="search_console_refresh_div refresh-account-div">
                          <a href="javascript:;" id="refresh_search_console_detail" class="btn icon-btn color-orange" uk-tooltip="title: Refresh Accounts; pos: top-center" aria-expanded="false">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                        </a>
                    </div>
                </div>
                <h6>Select a Campaign</h6>
                <div class="form-group">

                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/project-website-icon.png')}}"></span>
                    <select class="form-control selectpicker" id="detail_search_console_urlaccounts" data-live-search="true">
                        <option value="">Select Account</option>
                    </select>
                    <div class="sc-detail-loader detail-loaders">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                    </div>
                </div>

                <div class="text-left btn-group start">
                    <input type="button" class="btn blue-btn mr-3" value="Save" id="detail_save_console_account">
                </div>
            </form>
        </div>
        <div class="elem-right flex">
            <a href="javascript:;" class="btn yellow-btn detail_searchConsoleAddBtn" id="detail_add_new_console_account">Add New Account</a>
        </div>
    </div>

    <a class="popup-close" data-pd-popup-close="CampaignDetailConsolePopup" href="javascript:;" id="CampaignDetailConsole_close"></a>
</div>
</div>

<div class="popup" data-pd-popup="preparingConsoleDashboard" id="preparingConsoleDashboard">
    <div class="popup-inner">
        <h5>Preparing your SEO Dashboard, it may take few seconds</h5>
        <a class="popup-close" data-pd-popup-close="preparingConsoleDashboard" href="javascript:;" id="preparingConsoleDashboard_close"></a>
    </div>
</div>

<div class="popup" data-pd-popup="campaignDetailPpc">
    <div class="popup-inner">
        <div class="ppc-progress-loader popup-progress-loader"></div>
        <h3>Connect Your Google Adwords Account</h3>

        <div class="popup-elem-group">
            <div class="elem-left right-border">
                <form>
                    <div class="form-group">
                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="detail_adwords_existing_emails" data-live-search="true">
                            <option value="">Select from existing account</option>
                            <?php
                            if(!empty($getAdsAccounts)){
                             foreach($getAdsAccounts as $account){
                              ?>
                              <option value="{{$account->id}}">{{$account->email}}</option>
                          <?php } } ?>
                      </select>
                      <span class="errorStyle"><p id="show_ppc_last_time"></p></span>
                      <div class="ppc_refresh_div  refresh-account-div">
                          <a href="javascript:;" id="refresh_ppc_account_detail" class="btn icon-btn color-orange" uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                        </a>
                    </div>
                </div>
                <h6>Select a Campaign</h6>
                <div class="form-group">

                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                    <select class="form-control selectpicker" id="detail_adwords_accounts" data-live-search="true">
                        <option value="">Select Account</option>
                    </select>
                    <div class="detail-adwords-loader detail-loaders">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                    </div>
                </div>

                <div class="text-left btn-group start">
                    <input type="button" class="btn blue-btn mr-3" value="Save" id="detail_save_adwords_account">
                </div>
            </form>
        </div>
        <div class="elem-right flex">
            <a href="javascript:;" class="btn yellow-btn detail_AdwordsAddBtn" id="detail_add_new_adwords_account">Add New Account</a>
        </div>
    </div>

    <a class="popup-close" data-pd-popup-close="campaignDetailPpc" href="javascript:;" id="detail_adwords_close"></a>
</div>
</div>


<div class="popup redirecting-popup" data-pd-popup="preparingPPCDashboard" id="preparingPPCDashboard">
    <div class="popup-inner">
        <div class="preloader-popup">
            <img src="{{URL::asset('public/vendor/internal-pages/images/preloader.gif')}}" alt="loader">
        </div>
        <h5>Preparing your PPC Dashboard, <br>it may take few seconds</h5>
    </div>
</div>



<div class="popup" data-pd-popup="showLiveKeywordCountPopup" id="showLiveKeywordCountPopup">
    <div class="popup-inner">
        <h5>You have reached your keyword limit, upgrade to continue.</h5>
        <a class="popup-close" data-pd-popup-close="showLiveKeywordCountPopup" href="javascript:;" id="showLiveKeywordCountPopup_close"></a>
    </div>
</div>


<div class="popup" data-pd-popup="showProjectReachedPopup" id="showProjectReachedPopup">
    <div class="popup-inner">
        <h5>You have reached your Project limit, upgrade to continue.</h5>
        <a class="popup-close" data-pd-popup-close="showProjectReachedPopup" href="javascript:;" id="showProjectReachedPopup_close"></a>
    </div>
</div>

<div class="popup" data-pd-popup="projectSettingGmbPopup">
    <div class="popup-inner">
        <div class="gmb-progress-loader  popup-progress-loader"></div>
        <h3>Connect Your GMB Account</h3>

        <div class="popup-elem-group">
            <div class="elem-left right-border">
                <form id="gmb_setting_popup">
                    <div class="form-group">
                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="settings_gmb_existing_emails" data-live-search="true">
                            <option value="">Select from existing account</option>
                            <?php
                            if(!empty($getGmbAccounts)){
                             foreach($getGmbAccounts as $account){
                              ?>
                              <option value="{{$account->id}}">{{$account->email}}</option>
                          <?php } } ?>
                      </select>
                      <span class="errorStyle"><p id="show_gmb_last_time"></p></span>
                      <div class="gmb_refresh_div  refresh-account-div">
                          <a href="javascript:;" id="refresh_gmb_account" class="btn icon-btn color-orange" uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                        </a>
                    </div>
                </div>
                <h6>Select a Campaign</h6>
                <div class="form-group">

                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                    <select class="form-control selectpicker" id="settings_gmb_accounts" data-live-search="true">
                        <option value="">Select Account</option>
                    </select>
                    <div class="gmb-loader detail-loaders">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                    </div>
                </div>

                <div class="text-left btn-group start">
                    <input type="button" class="btn blue-btn mr-3" value="Save" id="settings_save_gmb_account">
                </div>
            </form>
        </div>
        <div class="elem-right flex">
            <a href="javascript:;" class="btn yellow-btn settings_GmbAddBtn" id="settings_add_new_gmb_account">Add New Account</a>
        </div>
    </div>

    <a class="popup-close" data-pd-popup-close="projectSettingGmbPopup" href="javascript:;" id="project_setting_gmb_close"></a>
</div>
</div>


<div class="popup" data-pd-popup="preparingGMBDashboard" id="preparingGMBDashboard">
    <div class="popup-inner">
        <h5>Preparing your GMB Dashboard, it may take few seconds</h5>
        <a class="popup-close" data-pd-popup-close="preparingGMBDashboard" href="javascript:;" id="preparingGMBDashboard_close"></a>
    </div>
</div>

<!-- google anlaytics 4 -->
<div class="popup" data-pd-popup="detailAnalytics4">
    <div class="popup-inner">
         <div class="analytics4-progress-loader popup-progress-loader"></div>
        <h3>Connect Your Google Analytics 4 Account</h3>

        <div class="popup-elem-group">
            <div class="elem-left right-border">
                <form>
                    <div class="form-group">
                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="detail_analytics4_existing_emails" data-live-search="true">
                            <option value="">Select from existing account</option>
                      </select>
                      <div class="analytic4-emails-detail-loader detail-loaders">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                    </div>
                      <span class="errorStyle"><p id="show_ga4_last_time"></p></span>
                      <div class="ga4_detail_refresh_div refresh-account-div">
                        <a href="javascript:;" id="refresh_ga4_account_detail" class="btn icon-btn color-orange" uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                        </a>
                    </div>
                </div>
              
                <h6>Select a Campaign</h6>
                <div class="form-group">
                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                    <select class="form-control selectpicker" id="detail_analytics4_accounts" data-live-search="true">
                        <option value="">Select Account</option>
                    </select>
                    <div class="analytic4-account-detail-loader detail-loaders">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                    </div>
                </div>
                <div class="form-group">
                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-property-icon.png')}}"></span>
                    <select class="form-control selectpicker" id="detail_analytics4_property" data-live-search="true">
                        <option value="">Select Property</option>
                    </select>
                    <div class="analytic4-property-detail-loader detail-loaders">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                    </div>
                </div>

                <div class="text-left btn-group start">
                    <input type="button" class="btn blue-btn mr-3" value="Save" id="save_detail_analytics4">
                </div>
            </form>
        </div>
        <div class="elem-right flex">
            <a href="javascript:;" class="btn yellow-btn detail_analytics4AddBtn" id="detail_addNew_analytics4">Add New Account</a>
        </div>
    </div>

    <a class="popup-close" data-pd-popup-close="detailAnalytics4" href="javascript:;" id="detailAnalytics4_close"></a>
</div>
</div>

<div class="popup redirecting-popup" data-pd-popup="preparingAnalytics4" id="preparingAnalytics4">
    <div class="popup-inner">
        <div class="preloader-popup">
            <img src="{{URL::asset('public/vendor/internal-pages/images/preloader.gif')}}" alt="loader">
        </div>
        <h5>Preparing your SEO Dashboard, <br>it may take few seconds</h5>
    </div>
</div>

<div class="popup redirecting-popup" data-pd-popup="preparingAnalytics" id="preparingAnalytics">
    <div class="popup-inner">
        <div class="preloader-popup">
            <img src="{{URL::asset('public/vendor/internal-pages/images/preloader.gif')}}" alt="loader">
        </div>
        <h5>Preparing your SEO Dashboard, <br>it may take few seconds</h5>
    </div>
</div>

<div class="popup common-analytics" data-pd-popup="googleAnalytics_detail_popup">
    <div class="popup-inner">
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-icon.jpg')}}" alt="google-analytics-icon"></figure>
        <h3>Select Your Google Analytics Account</h3>
        <div class="flex-btn">
            <a href="javascript:;" class="btn btn blue-btn" id="connect_detail_ua" data-pd-popup-open="campaignDetailAnalytics">Universal Analytics</a>
            <a href="javascript:;" class="btn btn blue-btn" id="connect_detail_ga4" data-pd-popup-open="detailAnalytics4">Google Analytics 4</a>
        </div>
        <a class="popup-close" data-pd-popup-close="googleAnalytics_detail_popup" href="javascript:;" id="googleAnalytics_detail_popup_close"></a>
    </div>
</div>