@extends('layouts.vendor_internal_pages')
@section('content')
<div class="tabs">
    <div class="loader h-48 half"></div>
    <ul class="breadcrumb-list">
       <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
       <li class="breadcrumb-item"><a href="{{url('/campaign-detail/'.$campaign_id)}}">{{$project_detail->host_url}}</a></li>
       <li class="uk-active breadcrumb-item">settings</li>
   </ul>

</div>

<div class="setting-container">
    <div class="loader h-300"></div>
    <div class="white-box pa-0 mb-40">
        <div class="white-box-head">
            <div class="left">
                <div class="heading">
                    <img src="{{URL::asset('public/vendor/internal-pages/images/project-website-icon.png')}}">
                    <h2>Project Setting</h2>
                </div>
            </div>

        </div>
        <div class="white-box-body">
            <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .profileSettingNav">
                <li>
                    <a href="#">
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}"></figure>
                        General Settings
                    </a>
                </li>
                <li>
                    <a href="#">
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/white-label.png')}}"></figure>
                        White Label
                    </a>
                </li>
                <li>
                    <a href="#">
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/integrations-icon.png')}}"></figure>
                        Integration
                    </a>
                </li>
                <li>
                    <a href="#">
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/rank-tracker-settings-icon.png')}}"></figure>
                        Dashboard Settings
                    </a>
                </li>
                <li>
                    <a href="#">
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/rank-tracker-settings-icon.png')}}"></figure>
                        Summary Settings
                    </a>
                </li>
            </ul>

            <div class="uk-switcher profileSettingNav">
                <!-- General Settings Tab -->
                <div>
                    <div class="account-form-box">
                        <div class="account-form-box-head">
                            <figure><img src="{{URL::asset('public/vendor/internal-pages/images/profile-icon.png')}}"></figure>
                            General Settings
                        </div>
                        <div class="account-form-box-body">
                            <form id="project_general_settings" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="request_id" value="{{@$campaign_id}}">
                                <div class="form-group">
                                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/small-date-icon.png')}}"></span>
                                    <input type="text" class="form-control project_domain_register genralSettings" placeholder="Project Start Date" value="{{$project_detail->domain_register}}" name="domain_register" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/location-icon.png')}}"></span>
                                    <select class="form-control selectpicker genralSettings" data-live-search="true" name="regional_db">
                                        @if(isset($regional_db) && !empty($regional_db))
                                        @foreach($regional_db as $db)
                                        <option value="{{$db->short_name}}" {{$db->short_name == $project_detail->regional_db  ? 'selected' : ''}}>{{$db->long_name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/website-icon.png')}}"></span>
                                    <input type="text" class="form-control project_domain_name genralSettings" placeholder="Project Name" value="{{$project_detail->domain_name}}" name="domain_name">
                                </div>
                                <div class="form-group">
                                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/website-name-icon.png')}}"></span>
                                    <input type="text" class="form-control project_domain_url genralSettings" placeholder="Project URL" value="{{$project_detail->domain_url}}" name="domain_url" disabled="disabled">
                                </div>
                                <div class="form-group">
                                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}"></span>
                                    <input type="text" class="form-control project_client_name genralSettings" placeholder="Client Name" value="{{$project_detail->clientName}}" name="clientName">
                                </div>



                                <div class="form-group field-group" id="projectlogo-img-section">
                                    <div class="form-group file-group">
                                        <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/attach-icon.png')}}"></span>
                                        <label class="custom-file-label">
                                            <input type="file" name="project_logo" id="project_logo" name="logo" accept="image/png,image/jpg,image/jpeg" class="genralSettings">
                                            <div class="custom-file form-control">
                                                <span id="fileName">Project Logo</span>
                                            </div>
                                        </label>
                                        <span class="errorStyle error"><p id="project-logo-error"></p></span>

                                    </div>

                                    <div class="agency-logo-image" id="img-project-logo">
                                        @if(isset($project_detail->project_logo) && !empty($project_detail->project_logo))
                                        <img id="project_image_preview_container" src="{{$project_detail->project_logo($campaign_id,$project_detail->project_logo)}}" alt="logo-img" >
                                        @else
                                        <img id="project_image_preview_container" src="{{URL::asset('/public/vendor/images/brand_logo.png')}}"  alt="logo-img" >
                                        @endif
                                    </div>

                                </div>

                                <button type="submit" class="btn blue-btn" id="update_project_general_settings">Update</button>


                            </form>
                        </div>

                    </div>
                </div>
                <!-- General Settings Tab End -->
                <!-- White Label Tab -->
                <div>
                    <div class="account-form-box">
                        <div class="account-form-box-head">
                            <figure><img src="{{URL::asset('public/vendor/internal-pages/images/white-label.png')}}"></figure>
                            White Label
                        </div>
                        <div class="account-form-box-body">
                            <form id="project_white_label" enctype="multipart/form-data">
                               @csrf
                               <input type="hidden" name="request_id" value="{{@$campaign_id}}">
                               <div class="form-group">
                                <span class="icon">
                                    <img src="{{URL::asset('public/vendor/internal-pages/images/company-icon.png')}}">
                                </span>
                                <input type="text" class="form-control white_label_company_name whiteLabelSettings" placeholder="Agency Name" value="{{@$profile_info->company_name}}" name="company_name" >
                            </div>

                            <div class="form-group">
                                <span class="icon">
                                    <img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}">
                                </span>
                                <input type="text" class="form-control white_label_client_name whiteLabelSettings" placeholder="Agency Owner Name" value="{{@$profile_info->client_name}}" name="client_name" >
                            </div>

                            <div class="form-group">
                                <span class="icon">
                                    <img src="{{URL::asset('public/vendor/internal-pages/images/phone-icon.png')}}">
                                </span>
                                <input type="number" class="form-control white_label_phone whiteLabelSettings" placeholder="Agency Phone" value="{{@$profile_info->contact_no}}" name="mobile" >
                            </div>

                            <div class="form-group">
                                <span class="icon">
                                    <img src="{{URL::asset('public/vendor/internal-pages/images/mail-icon.png')}}">
                                </span>
                                <input type="email" class="form-control white_label_email whiteLabelSettings" name="email" placeholder="Agency Email" value="{{@$profile_info->email}}" >
                            </div>


                            <div class="form-group field-group" id="logo-img-section">
                                <div class="form-group file-group">
                                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/attach-icon.png')}}"></span>
                                    <label class="custom-file-label">
                                        <input type="file" name="white_label_logo" id="agency_logo" name="logo" accept="image/png,image/jpg,image/jpeg" class="whiteLabelSettings">
                                        <div class="custom-file form-control">
                                            <span>Agency Logo</span>
                                        </div>
                                    </label>
                                    <span class="errorStyle error"><p id="agency-logo-error"></p></span>

                                </div>

                                <div class="agency-logo-image" id="img-agency-logo">
                                    @if(isset($profile_info->agency_logo) && !empty($profile_info->agency_logo))
                                    <img id="image_preview_container" src="{{$profile_info->agency_logo($campaign_id,$user_id,$profile_info->agency_logo)}}" alt="logo-img" >
                                    @else
                                    <img id="image_preview_container" src="{{URL::asset('/public/vendor/images/brand_logo.png')}}"  alt="logo-img" >
                                    @endif
                                </div>

                            </div>
                            <button type="submit" class="btn blue-btn" id="update_project_white_label">Update</button>
                        </form>
                    </div>
                </div>
            </div>
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
                                                 <li><big>{{$project_detail->get_console_connected_email($project_detail->google_console_id)}}</big> Connected Email</li>
                                                <li><big>{{$project_detail->getConsoleAccount($project_detail->console_account_id)}}</big> Account</li>
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

                            <article class="<?php if(!empty($project_detail->google_account_id) && !empty($project_detail->google_analytics_id)){ echo 'connected'; }?>" id="ProjectSettings-analytics">
                                <figure>
                                    <img src="{{URL::asset('public/vendor/internal-pages/images/google-analytics-img.png')}}">
                                </figure>
                                <?php if(empty($project_detail->google_account_id) && empty($project_detail->google_analytics_id)){?>
                                    <div>
                                        <p>To get insights about your website traffic and build reports for your SEO dashboard.
                                        </p>
                                        <a href="javascript:;" class="btn btn-border blue-btn-border" data-pd-popup-open="projectSettingAnalyticsPopup" id="SettingsAnalyticsBtnId">Connect</a>
                                    </div>
                                <?php }elseif(!empty($project_detail->google_account_id) && !empty($project_detail->google_analytics_id)){ ?>

                                    <div>
                                        <div class="connected-content">
                                            <ul>
                                                <li><big>{{$project_detail->get_analytics_connected_email($project_detail->google_account_id)}}</big> Connected Email</li>
                                                <li><big>{{$project_detail->getAnalyticsAccount($project_detail->google_analytics_id)}}</big> Account</li>
                                                <li><big>{{$project_detail->getAnalyticsAccount($project_detail->google_property_id)}}</big> Property</li>
                                                <li><big>{{$project_detail->getAnalyticsAccount($project_detail->google_profile_id)}}</big> Profile</li>
                                            </ul>
                                        </div>
                                        <a href="javascript:;" class="edit-btn" data-pd-popup-open="projectSettingAnalyticsPopup" id="SettingsAnalyticsBtnId"><i class="fa fa-edit"></i></a>
                                        <a href="javascript:;" class="btn gray-btn" id="disconnectAnalytics">Disconnect</a>
                                    </div>
                                <?php } ?>

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
                                                <li><big>{{$project_detail->get_analytics_connected_email($project_detail->google_ads_id)}}</big> Connected Email</li>
                                                <li><big>{{$project_detail->getAdwordsAccount($project_detail->google_ads_campaign_id)}}</big> Account</li>
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
                    <?php }?>
                </div>

            </div>
        </div>
    </div>
    <!-- Integration Tab End -->
    <!-- Dashboard Settings Tab -->
    <div>
        <div class="account-form-box">
            <div class="account-form-box-head">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/rank-tracker-settings-icon.png')}}"></figure>
                Dashboard Settings

            </div>
            <div class="account-form-box-body">
               <form id="project_dashboard_settings" >
                   @csrf
                   <input type="hidden" name="request_id" value="{{@$campaign_id}}">

                   <?php
                   if(isset($dashboards) && !empty($dashboards)){
                    foreach($user_dashboards  as $ud){
                        $types[] = $ud->dashboard_id;
                    }
                    foreach($dashboards as $dashboard){
                        ?>
                        <div class="form-group">
                            <div uk-grid>
                                <div class="uk-width-1-2@s">
                                    <label>{{$dashboard->name}}</label>
                                </div>
                                <div class="uk-width-1-2@s">
                                    <label class='sw'>
                                        <input name="dashboard[{{$dashboard->id}}]" type="checkbox" id="{{$dashboard->id}}" <?php if(in_array($dashboard->id, $types)){ echo "checked"; }?> class="dashboard_toggle">
                                        <div class='sw-pan'></div>
                                        <div class='sw-btn'></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                    <?php  } } ?>
                    <button type="submit" class="btn blue-btn" id="update_project_dashboard_settings" disabled="disabled">Update</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Dashboard Settings Tab End -->


    <!-- Summary Settings Tab -->
    <div>
        <div class="account-form-box">
            <div class="account-form-box-head">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/profile-icon.png')}}"></figure>
                Summary Settings
            </div>
            <div class="account-form-box-body">
                <form id="project_summary_settings">
                    @csrf
                    <input type="hidden" name="request_id" value="{{@$campaign_id}}" class="request_id">

                    <div class="form-group">
                        <div uk-grid>
                            <div class="uk-width-1-2@s">
                                <label>Display Summary</label>
                            </div>
                            <div class="uk-width-1-2@s">
                                <label class='sw'>
                                    <input name="summary_toggle" type="checkbox" class="summary_toggle summarySettings" <?php if(@$summary->display == 1){ echo "checked"; }?>>
                                    <div class='sw-pan'></div>
                                    <div class='sw-btn'></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea id="summarydata" name="summarydata" cols="20" class="form-control summarySettings" placeholder="Write your message.." >{{@$summary->edit_section}}</textarea>
                        <div id="character_count" style="float: right;"></div>
                    </div>

                    <span class="error errorStyle"><p id="summary_error"></p></span>



                    <button type="submit" class="btn blue-btn" id="update_summary_settings">Update</button>


                </form>
            </div>

        </div>
    </div>
    <!-- Summary settings Tab End -->
</div>
</div>
</div>

</div>
@endsection