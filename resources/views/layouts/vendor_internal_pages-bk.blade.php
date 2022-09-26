<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Agency Dashboard: # 1 Reporting Platform for Digital Marketing Agencies</title>
  <meta name="description" content="Agency Dashboard offers valuable insights through SEO, Local, PPC, & Social dashboards for all your marketing channels under a single roof. Trusted by more than 500 agencies across the world.">
  <meta name="viewport"
  content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta http-equiv="content-language" content="en-us">
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{URL::asset('public/front/img/favicon.png')}}" sizes="32x32" type="image/x-icon">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- On Page Loading CSS -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600&display=swap">

  <link rel="stylesheet" href="{{URL::asset('public/vendor/internal-pages/css/main.css?v='.time())}}">
  <link rel="stylesheet" href="{{URL::asset('public/vendor/internal-pages/css/custom.css?v='.time())}}">



  <input type="hidden" class="base_url" value="<?php echo url('/');?>" />

   <script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="6408702a-1c16-4e85-835f-3a67d2ed706f";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>
</head>

<body >
 @include('includes.vendor.sidebar')
 @include('includes.vendor.header')
 <main>

  @if(! Request::is('dashboard') && ! Request::is('dashboard/*') && ! Request::is('archived-campaigns') && ! Request::is('profile-settings') && ! Request::is('add-new-project') && ! Request::is('alerts') && ! Request::is('shared-access') && ! Request::is('activity/categories/*'))
    @include('includes.vendor.breadcrumb')
  @endif
  @yield('content')

</main>


<!-- On Page Loading JS -->
<script src="{{URL::asset('public/vendor/internal-pages/js/bundle.min.js')}}"></script>

<script src="{{URL::asset('public/vendor/internal-pages/js/bootstrap-select.js')}}"></script>

<script src="{{URL::asset('public/vendor/internal-pages/js/clipboard.min.js')}}"></script>

<!-- <script src="{{URL::asset('public/vendor/internal-pages/js/stickyTable.js')}}"></script> -->

<script  src="{{URL::asset('public/vendor/internal-pages/js/custom.js?v='.time())}}"></script>

<!-- Page Loading JS -->
<link defer href="{{URL::asset('public/vendor/internal-pages/css/toastr.css')}}" rel="stylesheet" type="text/css" />

<script defer src="{{URL::asset('public/vendor/scripts/toastr.js')}}"></script>

<!-- datepicker -->
<!-- <link defer rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script defer src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
<link rel="stylesheet" href="https://fengyuanchen.github.io/datepicker/css/datepicker.css" type="text/css" />
<script src="https://fengyuanchen.github.io/datepicker/js/datepicker.js"></script>
<!-- datepicker -->

<!--for chart.js-->
<script defer src="{{ URL::asset('/public/vendor/scripts/moment.min.js')}}"></script>
<script defer src="{{ URL::asset('/public/vendor/scripts/utils.js')}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/Chart.min.js')}}"></script>
<script defer src="{{URL::asset('public/vendor/scripts/chartjs-plugin-trendline.js?')}}"></script>

<!--daterangepicker-->
<script defer type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script defer type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link defer rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script defer type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyfg5RfXsDreSKWq7-P-VjfW7d2-abe8c
&libraries=places"></script>


<!-- select2 -->
<link defer href="{{URL::asset('public/vendor/css/select2.min.css')}}" rel="stylesheet"/>
<script defer src="{{URL::asset('public/vendor/scripts/select2.min.js')}}" type="text/javascript"></script>
<!-- select2 -->
<!-- ckeditor -->

<!--developer js for search-->
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/sidebar.js?v='.time())}}"></script>
@if(Request::is('dashboard') || Request::is('dashboard/*'))
<link rel="stylesheet" type="text/css" href="//www.jqueryscript.net/demo/Bootstrap-4-Tag-Input-Plugin-jQuery/tagsinput.css">
<script type="text/javascript" src="//www.jqueryscript.net/demo/Bootstrap-4-Tag-Input-Plugin-jQuery/tagsinput.js"></script>

<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/custom.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/activate_account.js?v='.time())}}"></script>
@include('includes.vendor.dashboard_popup')
@endif

<script defer src='https://cdn.tiny.cloud/1/z3r8dou485bow2yio91q56fxjpe96wkuy6tncrjahrxm7nmr/tinymce/5/tinymce.min.js' referrerpolicy="origin"></script>

@if(Request::is('archived-campaigns'))
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/archived_projects.js?v='.time())}}"></script>
@endif

@if(Request::is('campaign-detail/*') || Request::is('extra-organic-keywords/*'))

<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/campaign_notes.js?v='.time())}}"></script>

<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/campaign_detail.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/organic_keyword_growth.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/backlink_profile.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/goal_completion.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/live_keyword_tracking.js?v='.time())}}"></script>


<!-- ppc script -->
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_summary.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_summary_graph.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_campaign.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_keyword.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_adsgroup.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_ads.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_performance_network.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_performance_device.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_performance_clickType.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_performance_adSlot.js?v='.time())}}"></script>
<!-- ppc script -->

<!-- gmb script -->
  <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/gmb.js?v='.time())}}"></script>
<!-- gmb script -->

<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/activities.js?v='.time())}}"></script>

@endif

@if(Request::is('serp/*'))
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/campaign_detail.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/live_keyword_tracking.js?v='.time())}}"></script>
@endif

@if(Request::is('activities-details/*'))
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/campaign_detail.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/live_keyword_tracking.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/activities.js?v='.time())}}"></script>
@endif

@if(Request::is('activity/*'))
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/activities.js?v='.time())}}"></script>
@endif

@if(Request::is('profile-settings'))
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/profile_settings.js?v='.time())}}"></script>
@endif

@if(Request::is('project-settings/*'))
@include('includes.vendor.project_settings_popup')
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/project_settings.js?v='.time())}}"></script>
@endif

<!--developer js for search-->
@if(!Request::is('add-new-project'))
@include('includes.vendor.popup_modals')
@endif

@if(Request::is('add-new-project'))
  <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/project_settings.js?v='.time())}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/add_new_project.js?v='.time())}}"></script>
@include('includes.vendor.add_new_project_popup')
@endif

@if(Request::is('campaign-detail/*'))
  <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/project_settings.js?v='.time())}}"></script>
@include('includes.vendor.campaign_detail_popup')
@endif


@if(Request::is('shared-access'))
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/shared_access.js?v='.time())}}"></script>
@include('includes.vendor.shared_access_popup')
@endif

@if(Request::is('alerts'))
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/alerts.js?v='.time())}}"></script>
@endif

</body>

</html>