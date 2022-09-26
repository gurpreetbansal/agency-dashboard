<input type="hidden" class="analytics_campaign_id">
<input type="hidden" class="campaign_id">
<input type="hidden" class="currentRoute" value="{{\Request::segment(1)}}">

<div class="popup" data-pd-popup="connectIntepopupGa4">
<div class="popup-inner">
<div class="ga4-progress-loader popup-progress-loader"></div>
<h3>Connect Your Google Analytics 4 Account</h3>


<div class="popup-elem-group">
<div class="elem-left right-border">
<form id="project_analytics_form">

    <div class="form-group">
        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-icon.png')}}"></span>
        <select class="form-control selectpicker" id="ga4_addNew_existing_emails" data-live-search="true">
            <option value="">Select from existing account</option>
            <?php
            if(!empty($get_ga4_emails)){
                foreach($get_ga4_emails as $emails){
                    ?>
                    <option value="{{$emails->id}}">{{$emails->email}}</option>
                <?php } } ?>
            </select>
            <span class="errorStyle"><p id="show_ga4_last_time"></p></span>
            <div class="ga4_refresh_div refresh-account-div">
                <a href="javascript:;" id="refresh_ga4_account_addNew" class="btn icon-btn color-orange" uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
                    <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                </a>
            </div>
        </div>
        <h6>Select a Campaign</h6>
        <div class="form-group">

            <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
            <select class="form-control selectpicker" id="ga4_addNew_accounts" data-live-search="true">
                <option value="">Select Account</option>
                </select>
                <div class="ga4-account-loader addNew-loaders"><img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}"></div>
            </div>
            <div class="form-group">
                <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-property-icon.png')}}"></span>
                <select class="form-control selectpicker" id="ga4_addNew_property" data-live-search="true">
                    <option value="">Select Property</option>
                    </select>
                    <div class="ga4-property-loader addNew-loaders"><img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}"></div>
                </div>
                <div class="text-left btn-group start"><input type="button" class="btn blue-btn mr-3" value="Save" id="save_addNew_ga4"></div>
            </form>
        </div>
        <div class="elem-right flex"><a href="javascript:;" class="btn yellow-btn" id="addNew_connect_ga4">Add New Account</a></div>
    </div>
    <a class="popup-close" data-pd-popup-close="connectIntepopupGa4" href="javascript:;" id="connectIntepopupGa4_close"></a>
</div>
</div>

<div class="popup" id="analyticsPopup" data-pd-popup="connectIntepopup">
<div class="popup-inner">
    <div class="analytics-progress-loader popup-progress-loader"></div>
    <h3>Connect Your Google Analytics Account</h3>

    <div class="popup-elem-group">
        <div class="elem-left right-border">
            <form>

                <div class="form-group">
                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-icon.png')}}"></span>
                    <select class="form-control selectpicker" id="analytics_existing_emails" data-live-search="true">
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
                    <a href="javascript:;" id="refresh_analytics_account_addNew" class="btn icon-btn color-orange" uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                    </a>
                </div>
            </div>
            <h6>Select a Campaign</h6>
            <div class="form-group">

                <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                <select class="form-control selectpicker" id="analytics_accounts" data-live-search="true">
                    <option value="">Select Account</option>
                </select>
                <div class="analytic-account-addNew-loader addNew-loaders">
                    <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                </div>
            </div>
            <div class="form-group">
                <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-property-icon.png')}}"></span>
                <select class="form-control selectpicker" id="analytics_property" data-live-search="true">
                    <option value="">Select Property</option>
                </select>
                <div class="analytic-property-addNew-loader addNew-loaders">
                    <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                </div>
            </div>
            <div class="form-group">
                <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/pageviews-img-small.png')}}"></span>
                <select class="form-control selectpicker" id="analytics_view" data-live-search="true">
                    <option value="">Select View</option>
                </select>
                <div class="analytic-view-addNew-loader addNew-loaders">
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
                            <input  type="checkbox" class="add_nw_ecommerce_goals">
                            <div class='sw-pan'></div>
                            <div class='sw-btn'></div>
                        </label>
                    </div>
                </div>
            </div>


            <div class="text-left btn-group start">
                <input type="button" class="btn blue-btn mr-3" value="Save" id="save_analytics_account">
            </div>
        </form>
    </div>
    <div class="elem-right flex">
        <a href="javascript:;" class="btn yellow-btn analyticsAddBtn" id="add_new_analytics_account">Add New Account</a>
    </div>
</div>

<a class="popup-close" data-pd-popup-close="connectIntepopup" href="#" id="analytics_close"></a>
</div>
</div>

<div class="popup" data-pd-popup="connectIntepopupSearchConsole">
<div class="popup-inner">
    <div class="searchConsole-progress-loader popup-progress-loader"></div>
    <h3>Connect Your Google Search Console Account</h3>

    <div class="popup-elem-group">
        <div class="elem-left right-border">
            <form>
                <div class="form-group">
                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}"></span>
                    <select class="form-control selectpicker" id="search_console_existing_emails" data-live-search="true">
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
                      <a href="javascript:;" id="refresh_search_console_addNew" class="btn icon-btn color-orange" uk-tooltip="title: Refresh Accounts; pos: top-center" aria-expanded="false">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                    </a>
                </div>
            </div>
            <h6>Select a Campaign</h6>
            <div class="form-group">

                <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/project-website-icon.png')}}"></span>
                <select class="form-control selectpicker" id="search_console_urlaccounts" data-live-search="true">
                    <option value="">Select Account</option>
                </select>
                <div class="sc-addNew-loader addNew-loaders">
                    <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                </div>
            </div>

            <div class="text-left btn-group start">
                <input type="button" class="btn blue-btn mr-3" value="Save" id="save_console_account">
            </div>
        </form>
    </div>
    <div class="elem-right flex">
        <a href="javascript:;" class="btn yellow-btn searchConsoleAddBtn" id="add_new_console_account">Add New Account</a>
    </div>
</div>

<a class="popup-close" data-pd-popup-close="connectIntepopupSearchConsole" href="#" id="console_close"></a>
</div>
</div>

<div class="popup" data-pd-popup="connectIntepopupAdwords">
<div class="popup-inner">
    <div class="ppc-progress-loader popup-progress-loader"></div>
    <h3>Connect Your Google Adwords Account</h3>

    <div class="popup-elem-group">
        <div class="elem-left right-border">
            <form>
                <div class="form-group">
                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}"></span>
                    <select class="form-control selectpicker" id="adwords_existing_emails" data-live-search="true">
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
                      <a href="javascript:;" id="refresh_ppc_account_addNew" class="btn icon-btn color-orange" uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                    </a>
                </div>
            </div>
            <h6>Select a Campaign</h6>
            <div class="form-group">

                <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                <select class="form-control selectpicker" id="adwords_accounts" data-live-search="true">
                    <option value="">Select Account</option>
                </select>
                <div class="adwords-addNew-loader addNew-loaders">
                    <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                </div>
            </div>

            <div class="text-left btn-group start">
                <input type="button" class="btn blue-btn mr-3" value="Save" id="save_adwords_account">
            </div>
        </form>
    </div>
    <div class="elem-right flex">
        <a href="javascript:;" class="btn yellow-btn AdwordsAddBtn" id="add_new_adwords_account">Add New Account</a>
    </div>
</div>

<a class="popup-close" data-pd-popup-close="connectIntepopupAdwords" href="#" id="adwords_close"></a>
</div>
</div>

<div class="popup" data-pd-popup="projectSettingGmbPopup">
<div class="popup-inner">
   <div class="gmb-progress-loader  popup-progress-loader"></div>
   <h3>Connect Your GMB Account</h3>

   <div class="popup-elem-group">
    <div class="elem-left right-border">
        <form>
            <div class="form-group">
                <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}"></span>
                <select class="form-control selectpicker" id="settings_gmb_existing_emails" data-live-search="true">
                    <option value="">Select from existing account</option>
                    <?php
                    if(!empty($getAdsAccounts)){
                     foreach($getGmbAccounts as $account){
                      ?>
                      <option value="{{$account->id}}">{{$account->email}}</option>
                  <?php } } ?>
              </select>
              <span class="errorStyle"><p id="show_gmb_last_time"></p></span>
              <div class="gmb_refresh_div  refresh-account-div">
                <a href="javascript:;" id="refresh_gmb_account_addNew" class="btn icon-btn color-orange" uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
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
            <div class="gmb-loader addNew-loaders">
                <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
            </div>
        </div>

        <div class="text-left btn-group start">
            <input type="button" class="btn blue-btn mr-3" value="Save" id="add_new_save_gmb_account">
        </div>
    </form>
</div>
<div class="elem-right flex">
    <a href="javascript:;" class="btn yellow-btn settings_GmbAddBtn" id="settings_add_new_gmb_account">Add New Account</a>
</div>
</div>

<a class="popup-close" data-pd-popup-close="projectSettingGmbPopup" href="#" id="project_setting_gmb_close"></a>
</div>
</div>

<div class="popup" data-pd-popup="exitAndDeleteProject">
<div class="popup-inner">
    <h3>Exit and Delete Project</h3>

    <div class="popup-elem-group">
        <div class="elem-left">
            <form>
                <input type="hidden" class="lastprojectid">
                <h6>Changes you made so far will not be saved</h6>

                <div class="text-left btn-group start">
                    <input type="button" class="btn blue-btn mr-3" value="Cancel and delete" id="DeleteAddProject">
                    <input type="button" class="btn yellow-btn mr-3" value="Keep Setting up" data-pd-popup-close="exitAndDeleteProject">
                </div>
            </form>
        </div>

    </div>
</div>
</div>

<div class="popup common-analytics" data-pd-popup="addNew_analytics_popup">
    <div class="popup-inner">
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-icon.jpg')}}" alt="google-analytics-icon"></figure>
        <h3>Select Your Google Analytics Account</h3>
        <div class="flex-btn">
            <a href="javascript:;" class="btn btn blue-btn" id="connect_addNew_ua" data-pd-popup-open="connectIntepopup">Universal Analytics</a>
            <a href="javascript:;" class="btn btn blue-btn" id="connect_addNew_ga4" data-pd-popup-open="connectIntepopupGa4">Google Analytics 4</a>
        </div>
        <a class="popup-close" data-pd-popup-close="addNew_analytics_popup" href="javascript:;" id="addNew_analytics_popup_close"></a>
    </div>
</div>