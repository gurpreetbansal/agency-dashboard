@extends('layouts.vendor_internal_pages')
@section('content')
<div class="tabs">
    <!-- <div class="loader h-48 half"></div> -->
    <ul class="ajax-loader breadcrumb-list">
     <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
     <li class="breadcrumb-item"><a href="{{url('/campaign-detail/'.$campaign_id)}}">{{$project_detail->host_url}}</a></li>
     <li class="uk-active breadcrumb-item">settings</li>
 </ul>

</div>

<div class="setting-container">
    <!-- <div class="loader h-300"></div> -->
    <div class="white-box pa-0 mb-40">
        <div class="white-box-head">
            <div class="left">
                <div class="heading">
                    <img src="{{URL::asset('public/vendor/internal-pages/images/project-website-icon.png')}}">
                    <h2>Project Settings</h2>
                </div>
            </div>

        </div>
        <div class="white-box-body">
            <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .profileSettingNav">
                <li>
                    <a href="#" class="ajax-loader">
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}"></figure>
                        General
                    </a>
                </li>
                @if(Auth::user()->user_type==0)
                <li>
                    <a href="#" class="ajax-loader">
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/white-label.png')}}"></figure>
                        White Label
                    </a>
                </li>
                @endif
                <li>
                    <a href="#" class="ajax-loader">
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/integrations-icon.png')}}"></figure>
                        Integration
                    </a>
                </li>
                <li class="lk-table-setting">
                    <a href="#" class="ajax-loader">
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/table-setting.png')}}"></figure>
                        Table Settings
                    </a>
                </li>
                <li>
                    <a href="#" class="ajax-loader">
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/alerts-bell-icon.png')}}"></figure>
                        Alert Settings
                    </a>
                </li>
            </ul>

            <div class="uk-switcher profileSettingNav">
                <!-- General Settings Tab -->
                <div id="project-general-div">
                    <div class="ajax-loader account-form-box">
                        <div class="account-form-box-head">
                            <figure><img src="{{URL::asset('public/vendor/internal-pages/images/profile-icon.png')}}"></figure>
                            General
                            <div class="projectGeneralSettings-progress-loader progress-loader"></div>
                        </div>
                        <div class="account-form-box-body">
                            <form id="project_general_settings" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="request_id" value="{{@$campaign_id}}">

                                <div class="field-group center" id="general-project-logo-div">
                                    <div class="form-group file-group">
                                        <label>Project Logo</label>
                                        <label class="custom-file-label">
                                            <input type="file" name="project_logo" id="project_logo" name="logo" accept="image/png,image/jpg,image/jpeg" class="genralSettings">
                                            <div class="custom-file form-control <?php if(isset($project_detail) && (isset($project_detail->project_logo) && !empty($project_detail->project_logo))){ echo 'selected';}?>" id="custom-file-div">
                                                <span uk-icon="icon:  upload"></span>
                                                <span uk-icon="icon:  pencil" class="edit"></span>
                                                <span id="fileName" class="fileName">Project Logo</span>
                                                <span>Choose a file or drag it here.</span>
                                                <div class="uploaded-file" id="img-project-logo">
                                                    @if(isset($project_detail->project_logo) && !empty($project_detail->project_logo))
                                                    <img id="project_image_preview_container" src="{{$project_detail->project_logo($campaign_id,$project_detail->project_logo)}}" alt="logo-img" >
                                                    @else
                                                    <img id="project_image_preview_container"  alt="logo-img" >
                                                    @endif

                                                </div>
                                            </div>
                                        </label>

                                    </div>

                                    <div class="elem-right text-right">
                                        <?php if($project_detail->project_logo <> '' || $project_detail->project_logo <> null){?>
                                            <input type="button" class="btn btn-sm blue-btn" value="Remove" id="remove-project-logo" data-id="{{$project_detail->id}}"  >
                                        <?php }else{?>
                                            <input type="button" class="btn btn-sm blue-btn" value="Remove" id="remove-project-logo" data-id="{{$project_detail->id}}"  disabled>
                                        <?php } ?>
                                        <span class="errorStyle error"><p id="project-logo-error"></p></span>
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label>
                                            Project Start Date
                                        </label>
                                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/small-date-icon.png')}}"></span>
                                        <input type="text" class="form-control project_domain_register genralSettings" placeholder="Project Start Date" value="{{$project_detail->domain_register}}" name="domain_register" autocomplete="off">
                                        <span class="errorStyle"><p id="setting_project_date_error"></p></span>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            Location
                                        </label>
                                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/location-icon.png')}}"></span>
                                        <select class="form-control selectpicker genralSettings" data-live-search="true" name="regional_db">
                                            @if(isset($regional_db) && !empty($regional_db))
                                            @foreach($regional_db as $db)
                                            <option value="{{$db->short_name}}" {{$db->short_name == $project_detail->regional_db  ? 'selected' : ''}}>{{$db->long_name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="errorStyle"><p id="setting_location_error"></p></span>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            Project Name
                                        </label>
                                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/website-icon.png')}}"></span>
                                        <input type="text" class="form-control project_domain_name genralSettings" placeholder="Project Name" value="{{$project_detail->domain_name}}" name="domain_name">
                                        <span class="errorStyle"><p id="setting_project_name_error"></p></span>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            Project URL
                                        </label>
                                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/website-name-icon.png')}}"></span>
                                        <input type="text" class="form-control project_domain_url genralSettings" placeholder="Project URL" value="{{$project_detail->domain_url}}" name="domain_url" disabled="disabled">
                                        <span class="errorStyle"><p id="setting_project_url_error"></p></span>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            Client Name
                                        </label>
                                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}"></span>
                                        <input type="text" class="form-control project_client_name genralSettings" placeholder="Client Name" value="{{$project_detail->clientName}}" name="clientName">
                                        <span class="errorStyle"><p id="setting_client_name_error"></p></span>
                                    </div>
                                </div>


                                <div class="form-dashboards">
                                    <div class="form-group">
                                        <label>
                                            Dashboards
                                        </label>
                                        <?php
                                        if(isset($dashboards) && !empty($dashboards)){
                                            foreach($user_dashboards  as $ud){
                                                $types[] = $ud->dashboard_id;
                                            }
                                            foreach($dashboards as $dashboard){
                                                ?>
                                                <div class="form-dashboards-row">
                                                    <label>{{$dashboard->name}}</label>
                                                    <div class="">
                                                        <label class='sw'>
                                                            <input name="dashboard[{{$dashboard->id}}]" type="checkbox" id="{{$dashboard->id}}" <?php if(in_array($dashboard->id, $types)){ echo "checked"; }?> class="dashboard_toggle">
                                                            <div class='sw-pan'></div>
                                                            <div class='sw-btn'></div>
                                                        </label>

                                                    </div>
                                                </div>

                                            <?php  } } ?>
                                        </div>
                                        <span class="errorStyle"><p id="setting_dashboard_error"></p></span>
                                    </div>

                                    <div class="form-summary">
                                        <div class="form-group">
                                            <label>
                                                <strong>Summary</strong>
                                            </label>
                                        </div>

                                        <input type="hidden" name="request_id" value="{{@$campaign_id}}" class="request_id">

                                        <div class="form-group">
                                            <div uk-grid>
                                                <div class="uk-width-1-2@s">
                                                    <label>Display Summary</label>
                                                </div>
                                                <div class="uk-width-1-2@s">
                                                    <label class='sw'>
                                                        <input name="summary_toggle" type="checkbox" class="summary_toggle genralSettings" <?php if(@$summary->display == 1){ echo "checked"; }?>>
                                                        <div class='sw-pan'></div>
                                                        <div class='sw-btn'></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <textarea id="summarydata" name="summarydata" cols="20" class="form-control genralSettings" placeholder="Write your message.." >{{@$summary->edit_section}}</textarea>
                                            <div id="character_count" style="float: right;"></div>
                                        </div>

                                        <span class="error errorStyle"><p id="summary_error"></p></span>
                                    </div>


                                    <div class="uk-text-right">
                                        <button type="submit" class="btn blue-btn" id="update_project_general_settings">Update</button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                    <!-- General Settings Tab End -->
                    <!-- White Label Tab -->
                    @if(Auth::user()->user_type==0)
                    <div>

                        <div class="account-form-box">
                            <div class="account-form-box-head">
                                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/white-label.png')}}"></figure>
                                White Label
                                <div class="projectWhiteLabelSettings-progress-loader progress-loader"></div>
                            </div>
                            <div class="account-form-box-body">
                                <form id="project_white_label" enctype="multipart/form-data">
                                 @csrf
                                 <input type="hidden" name="request_id" value="{{@$campaign_id}}">

                                 <div class="field-group center"  id="whiteLabel-agency-logo-div">
                                    <div class="form-group file-group">
                                        <label>Agency Logo</label>
                                        <label class="custom-file-label">
                                            <input type="file" name="white_label_logo" id="agency_logo" name="logo" accept="image/png,image/jpg,image/jpeg" class="whiteLabelSettings">
                                            <div class="custom-file form-control <?php if(isset($profile_info) && (isset($profile_info->agency_logo) && !empty($profile_info->agency_logo))){echo 'selected';}?>"  id="custom-file-agency-div">
                                                <span uk-icon="icon:  upload"></span>
                                                <span uk-icon="icon:  pencil" class="edit"></span>
                                                <span class="fileName">Agency Logo</span>
                                                <span>Choose a file or drag it here.</span>
                                                <div class="uploaded-file" id="img-project-logo">
                                                    @if(isset($profile_info->agency_logo) && !empty($profile_info->agency_logo))
                                                    <img id="agency_image_preview_container" src="{{$profile_info->agency_logo($campaign_id,$user_id,$profile_info->agency_logo)}}" alt="logo-img" >
                                                    @else
                                                    <img id="agency_image_preview_container"  alt="logo-img" >
                                                    @endif
                                                </div>
                                            </div>
                                        </label>

                                    </div>

                                    <div class="elem-right text-right">
                                        <?php if(isset($profile_info) && ($profile_info->agency_logo <> '' || $profile_info->agency_logo <> null)){?>
                                            <input type="button" class="btn btn-sm blue-btn" value="Remove" id="remove-agency-logo" data-id="{{$campaign_id}}"  >
                                        <?php }else{?>
                                            <input type="button" class="btn btn-sm blue-btn" value="Remove" id="remove-agency-logo" data-id="{{$campaign_id}}"  disabled>
                                        <?php } ?>
                                        <span class="errorStyle error"><p id="agency-logo-error"></p></span>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Agency Name</label>
                                        <span class="icon">
                                            <img src="{{URL::asset('public/vendor/internal-pages/images/company-icon.png')}}">
                                        </span>
                                        <input type="text" class="form-control white_label_company_name whiteLabelSettings" placeholder="Agency Name" value="{{@$profile_info->company_name}}" name="company_name" >
                                        <span class="errorStyle"><p id="whiteLabel_companyName_error"></p></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Agency Owner Name</label>
                                        <span class="icon">
                                            <img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}">
                                        </span>
                                        <input type="text" class="form-control white_label_client_name whiteLabelSettings" placeholder="Agency Owner Name" value="{{@$profile_info->client_name}}" name="client_name" >
                                        <span class="errorStyle"><p id="whiteLabel_agencyOwner_error"></p></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Agency Phone</label>
                                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/phone-icon.png')}}"></span>
                                        <div class="agency-flex">
                                            <select class="selectpicker" data-live-search="true" id="country_code" title="-Select-" name="country_code">
                                                @foreach($country as $key=>$value)
                                                <option value="{{$value->id}}" data-country-id="{{$value->country_code}}" {{$value->id == @$profile_info->country_code  ? 'selected' : ''}}>+{{$value->country_code .'('. $value->short_code.')'}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden"  class="country-code-val" value="{{@$profile_info->country_code_val}}">
                                            <input type="number" class="form-control white_label_phone whiteLabelSettings" placeholder="Agency Phone" value="{{@$profile_info->contact_no}}" name="mobile">
                                        </div>
                                        <span class="errorStyle"><p id="whiteLabel_phone_error"></p></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Agency Email</label>
                                        <span class="icon">
                                            <img src="{{URL::asset('public/vendor/internal-pages/images/mail-icon.png')}}">
                                        </span>
                                        <input type="text" class="form-control white_label_email whiteLabelSettings" name="email" placeholder="Agency Email" value="{{@$profile_info->email}}" >
                                        <span class="errorStyle"><p id="whiteLabel_email_error"></p></span>
                                    </div>

                                    @if(Auth::user()->role_id !== 4)
                                    <div class="uk-flex">
                                        <label class="ml-3">Brand your report and remove any mention of AgencyDashboard from Viewkey and Pdf <i class="fa fa-flash"></i></label>
                                        <label class='sw ml-2'>
                                            <input name="white_label_branding" type="checkbox" <?php if(@$profile_info->white_label_branding == 1){ echo "checked";}?>>
                                            <div class='sw-pan'></div>
                                            <div class='sw-btn'></div>
                                        </label>
                                    </div>                                      
                                    @endif
                                </div>
                                <div class="uk-text-right">
                                    <button type="submit" class="btn blue-btn" id="update_project_white_label">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
                <!-- White Label Tab End -->
                <!-- Integration Tab -->
                <div>
                    <div class="account-form-box">
                        <div class="account-form-box-head">
                            <figure><img src="{{URL::asset('public/vendor/internal-pages/images/integrations-icon.png')}}"></figure>
                            Integration
                        </div>
                        <div class="account-form-box-body" id="integrationTab">
                            <div class="integration-list" id="project-integration-list">
                                <?php
                                $types = array();

                                if(isset($project_detail) && !empty($project_detail)){
                                    $types = explode(',',$project_detail->dashboard_type);
                                }

                                if (in_array(1, $types)){

                                 ?>
                                 <article class="<?php if(!empty($project_detail->google_console_id) && !empty($project_detail->console_account_id)){ echo 'connected'; }?>" id="ProjectSettings-console">
                                    <figure>
                                        <img src="{{URL::asset('public/vendor/internal-pages/images/google-search-console-img.png')}}">
                                    </figure>
                                    <?php if(!empty($project_detail->google_console_id) && !empty($project_detail->console_account_id)){?>

                                        <div>
                                            <div class="connected-content">
                                                <ul>
                                                   <li uk-tooltip="title:{{$project_detail->get_console_connected_email($project_detail->google_console_id)}}; pos:top-center"><big>{{$project_detail->get_console_connected_email($project_detail->google_console_id)}}</big> Connected Email</li>
                                                   <li uk-tooltip="title:{{$project_detail->getConsoleAccount($project_detail->console_account_id)}}; pos:top-center"><big>{{$project_detail->getConsoleAccount($project_detail->console_account_id)}}</big> Account</li>
                                               </ul>
                                           </div>
                                           <a href="javascript:;" class="edit-btn" data-pd-popup-open="projectSettingConsolePopup" id="SettingsConsoleBtnId"><i class="fa fa-edit"></i></a>
                                           <a href="javascript:;" class="btn gray-btn" id="disconnectConsole">Disconnect</a>
                                       </div>

                                   <?php }else{?>
                                    <div>
                                        <p>To get insights about SERP, keywords and build reports for your SEO dashboard.</p>
                                        <a href="javascript:;" class="btn btn-border blue-btn-border" data-pd-popup-open="projectSettingConsolePopup" id="SettingsConsoleBtnId"><?php if(!empty($project_detail->google_console_id) && !empty($project_detail->console_account_id)){ echo 'Connected'; }else{ echo "Connect";}?></a>
                                    </div>
                                <?php }?>
                            </article>

                            <article class="connected" id="ProjectSettings-analytics4" @if($connectivity['ga4'] == true) style="display:flex;" @else style="display:none;" @endif>
                                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics4-img.png')}}"></figure>
                                <div>
                                    <div class="connected-content">
                                       <ul>
                                        <li uk-tooltip="title:{{$project_detail->get_analytics_connected_email($project_detail->ga4_email_id)}}; pos:top-center">
                                            <big id="analytics4_connectedEmail">{{$project_detail->get_analytics_connected_email($project_detail->ga4_email_id)}}</big> Connected Email</li>
                                            <li uk-tooltip="title:{{$project_detail->getAnalyticsAccount($project_detail->ga4_account_id)}}; pos:top-center">
                                                <big id="analytics4_connectedAccount">{{$project_detail->getConnectedData($project_detail->ga4_account_id)}}</big> Account</li>
                                                <li uk-tooltip="title:{{$project_detail->getAnalyticsAccount($project_detail->ga4_property_id)}}; pos:top-center">
                                                    <big id="analytics4_connectedProperty">{{$project_detail->getConnectedData($project_detail->ga4_property_id)}}</big> Property</li>
                                                </ul>
                                            </div>
                                            <a href="javascript:;" class="edit-btn" data-pd-popup-open="projectSettingsga4Popup" id="Settingsga4BtnId"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:;" class="btn gray-btn" id="disconnectga4">Disconnect</a>
                                        </div>


                                    </article>
                                    <article class="connected" id="ProjectSettings-analytics"  @if($connectivity['ua'] == true) style="display:flex;" @else style="display:none;" @endif>
                                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-img.png')}}"></figure>
                                        <div>
                                            <div class="connected-content">
                                                <ul>

                                                    <li uk-tooltip="title:{{$project_detail->get_analytics_connected_email($project_detail->google_account_id)}}; pos:top-center">
                                                        <big id="analytics_connectedEmail">{{$project_detail->get_analytics_connected_email($project_detail->google_account_id)}}</big> Connected Email</li>
                                                        <li uk-tooltip="title:{{$project_detail->getAnalyticsAccount($project_detail->google_analytics_id)}}; pos:top-center">
                                                            <big id="analytics_connectedAccount">{{$project_detail->getAnalyticsAccount($project_detail->google_analytics_id)}}</big> Account</li>
                                                            <li uk-tooltip="title:{{$project_detail->getAnalyticsAccount($project_detail->google_property_id)}}; pos:top-center">
                                                                <big id="analytics_connectedProperty">{{$project_detail->getAnalyticsAccount($project_detail->google_property_id)}}</big> Property</li>
                                                                <li uk-tooltip="title:{{$project_detail->getAnalyticsAccount($project_detail->google_profile_id)}}; pos:top-center">
                                                                    <big id="analytics_connectedView">{{$project_detail->getAnalyticsAccount($project_detail->google_profile_id)}}</big> Profile</li>
                                                                </ul>
                                                            </div>
                                                            <a href="javascript:;" class="edit-btn" data-pd-popup-open="projectSettingAnalyticsPopup" id="connect_ua"><i class="fa fa-edit"></i></a>
                                                            <a href="javascript:;" class="btn gray-btn" id="disconnectAnalytics">Disconnect</a>
                                                        </div>
                                                    </article>
                                                    <article class="default-analytics" @if($connectivity['ua'] == true || $connectivity['ga4'] == true) style="display:none;" @else style="display:flex;" @endif>
                                                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-img.png')}}"></figure>
                                                        <div>
                                                            <p>To get insights about your website traffic and build reports for your SEO dashboard.</p>
                                                            <a href="javascript:;" class="btn btn-border blue-btn-border" data-pd-popup-open="googleAnalytics_popup" id="SettingsAnalyticsBtnId">Connect</a>
                                                        </div>
                                                    </article>



                                                    <?php
                                                }
                                                if (in_array(2, $types)){
                                                 ?>
                                                 <article class="<?php if(!empty($project_detail->google_ads_id) && !empty($project_detail->google_ads_campaign_id)){ echo 'connected'; }?>" id="ProjectSettings-adwords">
                                                    <figure>
                                                        <img src="{{URL::asset('public/vendor/internal-pages/images/google-ads-img.png')}}">
                                                    </figure>

                                                    <?php if(!empty($project_detail->google_ads_id) && !empty($project_detail->google_ads_campaign_id)){?>

                                                       <div>
                                                        <div class="connected-content">
                                                            <ul>
                                                                <li uk-tooltip="title:{{$project_detail->get_analytics_connected_email($project_detail->google_ads_id)}}; pos:top-center"><big>{{$project_detail->get_analytics_connected_email($project_detail->google_ads_id)}}</big> Connected Email</li>
                                                                <li uk-tooltip="title:{{$project_detail->getAdwordsAccount($project_detail->google_ads_campaign_id)}}; pos:top-center"><big>{{$project_detail->getAdwordsAccount($project_detail->google_ads_campaign_id)}}</big> Account</li>
                                                            </ul>
                                                        </div>
                                                        <a href="javascript:;" class="edit-btn" data-pd-popup-open="projectSettingAdwordsPopup" id="SettingsAdwordsBtnId"><i class="fa fa-edit"></i></a>
                                                        <a href="javascript:;" class="btn gray-btn" id="disconnectAdwords">Disconnect</a>
                                                    </div>
                                                <?php }else{?>
                                                    <div>
                                                        <p>To get insights about your website traffic and build reports for your PPC dashboard.
                                                        </p>
                                                        <a href="javascript:;" class="btn btn-border blue-btn-border" data-pd-popup-open="projectSettingAdwordsPopup" id="SettingsAdwordsBtnId"><?php if(!empty($project_detail->google_ads_id)){ echo 'Connected'; }else{echo "Connect";}?></a>
                                                    </div>
                                                <?php } ?>
                                            </article>
                                        <?php }
                                        if (in_array(3, $types)){
                                            ?>

                                            <article class="<?php if(!empty($project_detail->gmb_id) && !empty($project_detail->gmb_id)){ echo 'connected'; }?>" id="ProjectSettings-gmb">
                                                <figure>
                                                    <img src="{{URL::asset('public/vendor/internal-pages/images/gmb-img.png')}}">
                                                </figure>

                                                <?php if(!empty($project_detail->gmb_id) && !empty($project_detail->gmb_id)){?>
                                                   <div>
                                                    <div class="connected-content">
                                                        <ul>
                                                            <li uk-tooltip="title:{{$project_detail->get_gmb_connected_email($project_detail->gmb_analaytics_id)}}; pos:top-center"><big>{{$project_detail->get_gmb_connected_email($project_detail->gmb_analaytics_id)}}</big> Connected Email</li>
                                                            <li uk-tooltip="title:{{$project_detail->google_gmb_account($project_detail->gmb_id)}}; pos:top-center"><big>{{$project_detail->google_gmb_account($project_detail->gmb_id)}}</big> Account</li>
                                                        </ul>
                                                    </div>
                                                    <a href="javascript:;" class="edit-btn" data-pd-popup-open="projectSettingGmbPopup" id="SettingsGMBBtnId"><i class="fa fa-edit"></i></a>
                                                    <a href="javascript:;" class="btn gray-btn" id="disconnectGMB">Disconnect</a>
                                                </div>
                                            <?php }else{?>
                                                <div>
                                                    <p>To get insights about your website traffic and build reports for your GMB dashboard.
                                                    </p>
                                                    <a href="javascript:;" class="btn btn-border blue-btn-border" data-pd-popup-open="projectSettingGmbPopup" id="SettingsGMBBtnId"><?php if(!empty($project_detail->gmb_id)){ echo 'Connected'; }else{echo "Connect";}?></a>
                                                </div>
                                            <?php } ?>
                                        </article>
                                    <?php } if (in_array(4, $types)){ ?>
                                        <article class="<?php if(!empty($project_detail->fbid) && !empty($project_detail->fbid)){ echo 'connected'; }?>" id="ProjectSettings-social">
                                            <figure>
                                                <img src="{{URL::asset('public/vendor/internal-pages/images/facebook.png')}}">
                                            </figure>
                                            <?php if(!empty($project_detail->fbid) && !empty($project_detail->fbid)){?>
                                                <div>
                                                    <div class="connected-content">
                                                       <ul>
                                                        <li uk-tooltip="title:{{ $project_detail->fbAccount->name }}; pos:top-center"><big>{{ $project_detail->fbAccount->name }}</big> Account</li>
                                                        <li uk-tooltip="title:{{ $project_detail->fbPage->page_name }}; pos:top-center"><big>{{ $project_detail->fbPage->page_name }}</big> Connected Page</li>
                                                    </ul>
                                                </div>
                                                <a href="javascript:;" class="edit-btn" data-pd-popup-open="projectSettingFacebookPopup" id="facebookSettingsBtnId"><i class="fa fa-edit"></i></a>
                                                <a href="javascript:;" class="btn gray-btn" id="disconnectFacebook">Disconnect</a>
                                            </div>
                                        <?php }else{?>
                                            <div>
                                                <p>To get insights about your facebook page traffic and build reports.</p>
                                                <a href="javascript:;" class="btn btn-border blue-btn-border" data-pd-popup-open="projectSettingFacebookPopup" id="facebookSettingsBtnId"><?php if(!empty($project_detail->fbid)){ echo 'Connected'; }else{echo "Connect";}?></a> 
                                            </div>
                                        <?php } ?>   
                                    </article>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Integration Tab End -->
                <!-- Table Settings Tab -->
                <div>
                    <div class="account-form-box">
                        <div class="account-form-box-head">
                            <figure><img src="{{URL::asset('public/vendor/internal-pages/images/table-setting.png')}}"></figure>
                            Live Keyword Settings
                        </div>
                        <div class="account-form-box-body">
                            <form>
                                <table>
                                    <tr>
                                        <th class="uk-text-left">
                                            Table Column
                                        </th>
                                        <th>Show in Project</th>
                                        <th>Show in ViewKey</th>
                                        <th>Show in Pdf</th>
                                    </tr>
                                    
                                    <tr>
                                        <td>Starting Rank</td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="start_rank" data-column="detail" type="checkbox" class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="start_rank" data-column="viewkey" type="checkbox" class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="start_rank" data-column="pdf" type="checkbox" class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Page No.</td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="page" data-column="detail" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="page" data-column="viewkey" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="page" data-column="pdf" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Google Rank</td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="google_rank" data-column="detail" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="google_rank" data-column="viewkey" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="google_rank" data-column="pdf" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            1 Day Change
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="oneday" data-column="detail" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="oneday" data-column="viewkey" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="oneday" data-column="pdf" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            7 Day Change
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="weekly" data-column="detail" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="weekly" data-column="viewkey" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="weekly" data-column="pdf" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            30 Day Change
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="monthly" data-column="detail" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="monthly" data-column="viewkey" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="monthly" data-column="pdf" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Lifetime Change (Compared to Starting Rank)
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="lifetime" data-column="detail" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="lifetime" data-column="viewkey" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="lifetime" data-column="pdf" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Competition
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="competition" data-column="detail" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="competition" data-column="viewkey" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="competition" data-column="pdf" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Search Volume
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="sv" data-column="detail" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="sv" data-column="viewkey" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="sv" data-column="pdf" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Date Added
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="date" data-column="detail" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="date" data-column="viewkey" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="date" data-column="pdf" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            URL
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="url" data-column="detail"  type="checkbox" class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="url" data-column="viewkey" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="url" data-column="pdf" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Graphs
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="graph" data-column="detail"  type="checkbox" class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="graph" data-column="viewkey" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                        <td class="uk-text-center">
                                            <label class='sw mx-auto'>
                                                <input data-name="graph" data-column="pdf" type="checkbox"  class="liveKeyword_table_toggle">
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Table Settings Tab End -->
                <!--Alert setting tab-->
                <div>
                    <div class="account-form-box">
                        <div class="account-form-box-head">
                            <figure><img src="{{URL::asset('public/vendor/internal-pages/images/alerts-bell-icon.png')}}"></figure>
                            Alert
                            <div class="alertSettings-progress-loader progress-loader"></div>
                        </div>
                        <div class="account-form-box-body">
                            <form id="project_alert_settings">
                               
                                @csrf
                                <input type="hidden" name="request_id" value="{{@$campaign_id}}">                               

                                <div class="form-row">
                                    <div class="form-group">
                                        <div class="d-flex">
                                            <label>Send to client(s) <span uk-tooltip="title: Receive daily email alerts for ranked keywords; pos: top-left" class="fa fa-info-circle"></span></label>
                                            <label class='sw ml-3'>
                                                <input name="keyword_client_alerts" type="checkbox" class="keyword_client_alerts alertSettings" <?php if(!empty($alert_setting) && ($alert_setting->client_alerts == 1)){ echo "checked";}?>>
                                                <div class='sw-pan'></div>
                                                <div class='sw-btn'></div>
                                            </label>
                                        </div>
                                        <div class="<?php if(empty($alert_setting)){ echo 'hide';}?> alert_content_div" id="alert_clients_div">
                                            <?php 
                                            if(!empty($alert_setting)){
                                                $explode = explode(', ',$alert_setting->client_emails);
                                                foreach($explode as $key=>$value){
                                                    ?>
                                                    <div class="form-group">
                                                        <div class="uk-flex">
                                                            <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/mail-icon.png')}}"></span>
                                                            <input type="text" name="keyword_alerts_client_email[]" class="keyword_alerts_client_email form-control alertSettings" placeholder="Enter email to send alerts" value="{{$value}}">
                                                            <figure class="remove-append-addEmail"><i class="fa fa-trash"></i></figure>
                                                        </div>
                                                    </div>
                                                <?php }}else{ ?>
                                                    <div class="form-group">
                                                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/mail-icon.png')}}"></span>
                                                        <input type="text" name="keyword_alerts_client_email[]" class="keyword_alerts_client_email form-control alertSettings" placeholder="Enter email to send alerts">
                                                    </div>
                                                <?php }?>

                                                <button class="uk-button uk-button-link  uk-text-capitalize mt-5" type="button" id="alerts-add-clients"><span uk-icon="icon: plus" class="uk-icon"></span> Add Recipient</button>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="<?php if(empty($alert_setting)){ echo 'hide';}?>" id="display_manager_div">
                                                <div class="uk-flex">
                                                    <label>Send to manager <span uk-tooltip="title: Receive daily email alerts for ranked keywords; pos: top-left" class="fa fa-info-circle"></span></label>
                                                    <label class='sw ml-3'>
                                                        <input name="keyword_manager_alerts" type="checkbox" class="keyword_manager_alerts alertSettings" <?php if(!empty($alert_setting) && ($alert_setting->manager_alerts == 1)){ echo "checked";}?>>
                                                        <div class='sw-pan'></div>
                                                        <div class='sw-btn'></div>
                                                    </label>
                                                </div>
                                                <div class="{{($alert_setting !== null && $alert_setting->client_alerts == 1 && $alert_setting->manager_alerts == 1)?'':'hide'}}" id="alert_manager_div">
                                                    <div class="form-group ">
                                                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/mail-icon.png')}}"></span>
                                                        <input type="text" name="keyword_manager_alerts_email" class="keyword_manager_alerts_email form-control alertSettings" placeholder="Enter email to send alerts" value="<?php if(!empty($alert_setting)){ echo $alert_setting->manager_email;}?>">
                                                    </div>   
                                                </div>   
                                            </div>   
                                        </div>
                                    </div>

                                    <div class="uk-text-right">
                                        <button type="submit" class="btn blue-btn" id="update_project_alert_settings">Update</button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                    <!--Alert setting tab end-->
                </div>
            </div>
        </div>

    </div>
    @endsection