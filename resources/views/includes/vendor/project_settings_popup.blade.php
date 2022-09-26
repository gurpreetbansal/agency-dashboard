<input type="hidden" class="campaign_id" value="{{@$campaign_id}}">
<input type="hidden" class="currentRoute" value="{{\Request::Segment(1)}}">
<div class="popup" data-pd-popup="projectSettingAnalyticsPopup">
    <div class="popup-inner">
        <div class="analytics-progress-loader popup-progress-loader"></div>
        <h3>Connect Your Google Analytics Account</h3>
        <p class="ua-text"><small><i>**Disconnect your Univeral Anlaytics to migrate to Google Analytics 4</i></small>
        </p>

        <div class="popup-elem-group">
            <div class="elem-left right-border">
                <form id="project_analytics_form">

                    <div class="form-group">
                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="settings_analytics_existing_emails"
                            data-live-search="true">
                            <option value="">Select from existing account</option>
                       </select>
                       <div class="analytic-email-loader project-setting-loaders">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                        </div>
                        <span class="errorStyle">
                            <p id="show_analytics_last_time"></p>
                        </span>
                        <div class="analytics_refresh_div refresh-account-div">
                            <a href="javascript:;" id="refresh_analytics_account" class="btn icon-btn color-orange"
                                uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
                                <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                            </a>
                        </div>
                    </div>
                    <h6>Select a Campaign</h6>
                    <div class="form-group">

                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="settings_analytics_accounts"
                            data-live-search="true">
                            <option value="">Select Account</option>
                        </select>
                        <div class="analytic-account-loader project-setting-loaders">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/select-property-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="settings_analytics_property"
                            data-live-search="true">
                            <option value="">Select Property</option>
                        </select>
                        <div class="analytic-property-loader project-setting-loaders">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/pageviews-img-small.png')}}"></span>
                        <select class="form-control selectpicker" id="settings_analytics_view" data-live-search="true">
                            <option value="">Select View</option>
                           
                        </select>
                        <div class="analytic-view-loader project-setting-loaders">
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
                                    <input name="ecommerce_goals" type="checkbox" class="ecommerce_goals"
                                        <?php if($project_detail->ecommerce_goals == 1){ echo "checked"; }?>>
                                    <div class='sw-pan'></div>
                                    <div class='sw-btn'></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="text-left btn-group start">
                        <input type="button" class="btn blue-btn mr-3" value="Save"
                            id="save_settings_analytics_account">
                    </div>
                </form>
            </div>
            <div class="elem-right flex">
                <a href="javascript:;" class="btn yellow-btn settings_analyticsAddBtn"
                    id="settings_add_new_analytics_account">Add New Account</a>
            </div>
        </div>

        <a class="popup-close" data-pd-popup-close="projectSettingAnalyticsPopup" href="javascript:;"
            id="project_setting_analytics_close"></a>
    </div>
</div>


<div class="popup" data-pd-popup="projectSettingConsolePopup">
    <div class="popup-inner">
        <div class="searchConsole-progress-loader popup-progress-loader"></div>
        <h3>Connect Your Google Search Console Account</h3>

        <div class="popup-elem-group">
            <div class="elem-left right-border">
                <form id="console_popup">
                    <div class="form-group">
                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="settings_search_console_existing_emails"
                            data-live-search="true">
                            <option value="">Select from existing account</option>
                        </select>
                        <div class="sce-loader project-setting-loaders">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                        </div>
                        <span class="errorStyle">
                            <p id="show_search_console_last_time"></p>
                        </span>
                        <div class="search_console_refresh_div refresh-account-div">
                            <a href="javascript:;" id="refresh_search_console_account" class="btn icon-btn color-orange"
                                uk-tooltip="title: Refresh Accounts; pos: top-center" aria-expanded="false">
                                <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                            </a>
                        </div>
                    </div>
                    <h6>Select a Campaign</h6>
                    <div class="form-group">

                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/project-website-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="settings_search_console_urlaccounts"
                            data-live-search="true">
                            <option value="">Select Account</option>
                        </select>
                        <div class="sc-loader project-setting-loaders">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                        </div>
                    </div>

                    <div class="text-left btn-group start">
                        <input type="button" class="btn blue-btn mr-3" value="Save" id="settings_save_console_account">
                    </div>
                </form>
            </div>
            <div class="elem-right flex">
                <a href="javascript:;" class="btn yellow-btn settings_searchConsoleAddBtn"
                    id="settings_add_new_console_account">Add New Account</a>
            </div>
        </div>

        <a class="popup-close" data-pd-popup-close="projectSettingConsolePopup" href="javascript:;"
            id="project_setting_console_close"></a>
    </div>
</div>

<div class="popup" data-pd-popup="projectSettingAdwordsPopup">
    <div class="popup-inner">
        <div class="ppc-progress-loader popup-progress-loader"></div>
        <h3>Connect Your Google Adwords Account</h3>

        <div class="popup-elem-group">
            <div class="elem-left right-border">
                <form id="adwords_setting_popup">
                    <div class="form-group">
                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="settings_adwords_existing_emails"
                            data-live-search="true">
                            <option value="">Select from existing account</option>
                        </select>
                        <div class="adwords-emails project-setting-loaders">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                        </div>
                        <span class="errorStyle">
                            <p id="show_ppc_last_time"></p>
                        </span>
                        <div class="ppc_refresh_div  refresh-account-div">
                            <a href="javascript:;" id="refresh_ppc_account" class="btn icon-btn color-orange"
                                uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
                                <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                            </a>
                        </div>
                    </div>
                    <h6>Select a Campaign</h6>
                    <div class="form-group">

                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="settings_adwords_accounts"
                            data-live-search="true">
                            <option value="">Select Account</option>
                        
                        </select>
                        <div class="adwords-loader project-setting-loaders">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                        </div>
                    </div>

                    <div class="text-left btn-group start">
                        <input type="button" class="btn blue-btn mr-3" value="Save" id="settings_save_adwords_account"
                            data-campaign-id="{{@$campaign_id}}">
                    </div>
                </form>
            </div>
            <div class="elem-right flex">
                <a href="javascript:;" class="btn yellow-btn settings_AdwordsAddBtn"
                    id="settings_add_new_adwords_account">Add New Account</a>
            </div>
        </div>

        <a class="popup-close" data-pd-popup-close="projectSettingAdwordsPopup" href="javascript:;"
            id="project_setting_adwords_close"></a>
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
                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}"></span>
                        <select class="form-control selectpicker gmb_email_setting" id="settings_gmb_existing_emails"
                            data-live-search="true">
                            <option value="">Select from existing account</option>
                        </select>
                        <div class="gmb-email-loader project-setting-loaders">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                        </div>

                        <span class="errorStyle">
                            <p id="show_gmb_last_time"></p>
                        </span>
                        <div class="gmb_refresh_div  refresh-account-div">
                            <a href="javascript:;" id="refresh_gmb_account" class="btn icon-btn color-orange"
                                uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
                                <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                            </a>
                        </div>
                    </div>
                    <h6>Select a Campaign</h6>
                    <div class="form-group">

                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="settings_gmb_accounts" data-live-search="true">
                            <option value="">Select Account</option>
                        </select>
                        <div class="gmb-loader project-setting-loaders">
                            <img src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                        </div>
                    </div>

                    <div class="text-left btn-group start">
                        <input type="button" class="btn blue-btn mr-3" value="Save" id="settings_save_gmb_account">
                    </div>
                </form>
            </div>
            <div class="elem-right flex">
                <a href="javascript:;" class="btn yellow-btn settings_GmbAddBtn" id="settings_add_new_gmb_account">Add
                    New Account</a>
            </div>
        </div>

        <a class="popup-close" data-pd-popup-close="projectSettingGmbPopup" href="javascript:;"
            id="project_setting_gmb_close"></a>
    </div>
</div>

<!-- Facebook connect popup start-->

<!-- Facebook connect popup end-->


<div class="popup" data-pd-popup="projectSettingsga4Popup">
    <div class="popup-inner">
        <div class="ga4-progress-loader popup-progress-loader"></div>
        <h3>Connect Your Google Analytics 4 Account</h3>


        <div class="popup-elem-group">
            <div class="elem-left right-border">
                <form id="project_analytics_form">

                    <div class="form-group">
                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="ga4_existing_emails" data-live-search="true">
                            <option value="">Select from existing account</option>
                        </select>
                        <div class="ga4-emails-loader project-setting-loaders"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                        </div>
                        <span class="errorStyle">
                            <p id="show_ga4_last_time"></p>
                        </span>
                        <div class="ga4_refresh_div refresh-account-div">
                            <a href="javascript:;" id="refresh_ga4_account" class="btn icon-btn color-orange"
                                uk-tooltip="title: Refresh Account; pos: top-center" aria-expanded="false">
                                <img src="{{URL::asset('public/vendor/internal-pages/images/restore-icon.png')}}">
                            </a>
                        </div>
                    </div>
                    <h6>Select a Campaign</h6>
                    <div class="form-group">
                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="ga4_accounts" data-live-search="true">
                            <option value="">Select Account</option>
                        </select>
                        <div class="ga4-account-loader project-setting-loaders"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <span class="icon"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/select-property-icon.png')}}"></span>
                        <select class="form-control selectpicker" id="ga4_property" data-live-search="true">
                            <option value="">Select Property</option>
                        </select>
                        <div class="ga4-property-loader project-setting-loaders"><img
                                src="{{URL::asset('public/vendor/internal-pages/images/project-setting-loader.gif')}}">
                        </div>
                    </div>
                    <div class="text-left btn-group start"><input type="button" class="btn blue-btn mr-3" value="Save"
                            id="save_ga4"></div>
                </form>
            </div>
            <div class="elem-right flex"><a href="javascript:;" class="btn yellow-btn settings_ga4_AddBtn"
                    id="connect_ga4">Add New Account</a></div>
        </div>
        <a class="popup-close" data-pd-popup-close="projectSettingsga4Popup" href="javascript:;"
            id="projectSettingsga4Popup_close"></a>
    </div>
</div>