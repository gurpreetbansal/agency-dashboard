<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Agency Dashboard: # 1 Reporting Platform for Digital Marketing Agencies</title>
  <meta name="description" content="Agency Dashboard offers valuable insights through SEO, Local, PPC, & Social dashboards for all your marketing channels under a single roof. Trusted by more than 500 agencies across the world.">
  <meta name="viewport"
  content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{URL::asset('public/front/img/favicon.png')}}" sizes="32x32" type="image/x-icon">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- On Page Loading CSS -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600&display=swap">

  <link rel="stylesheet" href="{{URL::asset('public/vendor/internal-pages/css/main.css?v='.time())}}">
  <link rel="stylesheet" href="{{URL::asset('public/admin/internal-pages/css/custom.css?v='.time())}}">
  <input type="hidden" class="base_url" value="<?php echo url('/');?>" />
</head>

<body >
 @include('includes.admin.sidebar')
 @include('includes.admin.header')
<main>
  @yield('content')
</main>


<!-- On Page Loading JS -->
<script src="{{URL::asset('public/vendor/internal-pages/js/bundle.min.js')}}"></script>

<script src="{{URL::asset('public/vendor/internal-pages/js/bootstrap-select.js')}}"></script>

<script src="{{URL::asset('public/vendor/internal-pages/js/clipboard.min.js')}}"></script>

<script defer src="{{URL::asset('public/vendor/internal-pages/js/tinymce.min.js')}}"></script>
<!-- <script src="{{URL::asset('public/vendor/internal-pages/js/stickyTable.js')}}"></script> -->
<link defer href="{{URL::asset('public/vendor/internal-pages/css/toastr.css')}}" rel="stylesheet" type="text/css" />
<script defer src="{{URL::asset('public/vendor/scripts/toastr.js')}}"></script>

<script  src="{{URL::asset('public/admin/internal-pages/js/custom.js?v='.time())}}"></script>

<!-- developer script starts -->
@if(Request::is('admin/dashboard') || Request::is('admin/dashboard/*'))
<script  src="{{URL::asset('public/admin/internal-pages/js/dashboard.js?v='.time())}}"></script>
@endif

@if(Request::is('admin/site-audit') || Request::is('admin/site-audit/*'))
<script  src="{{URL::asset('public/admin/internal-pages/js/site_audit.js?v='.time())}}"></script>
@endif
@if(Request::is('admin/profile-settings') || Request::is('admin/profile-settings/*'))
<script  src="{{URL::asset('public/admin/internal-pages/js/profile_settings.js?v='.time())}}"></script>
@endif
@if(Request::is('admin/agency-account-details') || Request::is('admin/agency-account-details/*'))
<script  src="{{URL::asset('public/admin/internal-pages/js/agency_account_details.js?v='.time())}}"></script>
@endif
@if(Request::is('admin/feedbacks') || Request::is('admin/feedbacks/*'))
 @include('includes.admin.cancel_feedback_popup')
<script  src="{{URL::asset('public/admin/internal-pages/js/feedback.js?v='.time())}}"></script>
@endif
</body>
</html>