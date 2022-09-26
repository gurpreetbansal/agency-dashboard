<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Agency Dashboard: # 1 Reporting Platform for Digital Marketing Agencies</title>
  <meta name="description" content="Agency Dashboard offers valuable insights through SEO, Local, PPC, & Social dashboards for all your marketing channels under a single roof. Trusted by more than 500 agencies across the world.">

<!-- <script language='JavaScript'>
// console.log('Screen resolution is '+screen.width+'x'+screen.height+'.');
</script> -->

<!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> -->
<!-----Website Meta Tags--------->
<meta name="keywords" content="agencydashboard,Google Ads,Google Analytics,Google My Business,Google Search Console,Serp Stat,Google Analytics 4,Site Audit" />
<link rel="canonical" href="{{URL::current()}}" />
<meta property="og:url" content="{{URL::current()}}" />
<meta property="og:image" content="{{URL::asset('public/front/img/AD-logo.jpg')}}" />

<!-----Facebook Meta Tags--------->
<meta property="og:site_name" content="Agency Dashboard" />
<meta property="og:title" content="Agency Dashboard: # 1 Reporting Platform for Digital Marketing Agencies" />
<meta property="og:description" content="Agency Dashboard offers valuable insights through SEO, Local, PPC, & Social dashboards for all your marketing channels under a single roof. Trusted by more than 500 agencies across the world." />
<meta property="og:url" content="{{URL::current()}}" />
<meta property="og:image" content="{{URL::asset('public/front/img/logo.png')}}" />
<meta property="og:image:secure_url" content="{{URL::asset('public/front/img/AD-facebook.jpg')}}" />
<meta property="article:publisher" content="https://facebook.com/Agency-Dashboard-103776602396524" />

<!-----Twitter Meta Tags--------->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="@AgencyDashboard" />
<meta name="twitter:title" content="Agency Dashboard: # 1 Reporting Platform for Digital Marketing Agencies" />
<meta name="twitter:description" content="Agency Dashboard offers valuable insights through SEO, Local, PPC, & Social dashboards for all your marketing channels under a single roof. Trusted by more than 500 agencies across the world." />
<meta name="twitter:image" content="{{URL::asset('public/front/img/AD-twitter.jpg')}}" />


<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta http-equiv="content-language" content="en-us">
<!-- Favicon -->
<link rel="shortcut icon" href="{{URL::asset('public/front/img/favicon.png')}}" sizes="32x32" type="image/x-icon">

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- On Page Loading CSS -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600&display=swap">

<link rel="stylesheet" href="{{URL::asset('public/vendor/internal-pages/css/main.css')}}">
<link defer rel="stylesheet" href="{{URL::asset('public/vendor/internal-pages/css/custom.css')}}">



<input type="hidden" class="base_url" value="<?php echo url('/');?>" />



</head>

<body class="extra-view-key" >
 @include('includes.viewkey.sa-sidebar')
<div class="preloader-wrapper">
	<img src="{{URL::asset('public/vendor/internal-pages/images/preloader.gif')}}" alt="loader">
</div>
 <main class="viewkey-output">
 
  @yield('content')
</main>



<div class='back-to-top viewkey-top' id='back-to-top' title='Back to top'><i class='fa fa-angle-up'></i></div>
<!-- On Page Loading JS -->
<script defer src="{{URL::asset('public/vendor/internal-pages/js/bundle.min.js')}}"></script>

<script defer src="{{URL::asset('public/vendor/internal-pages/js/bootstrap-select.js')}}"></script>

<script defer src="{{URL::asset('public/vendor/internal-pages/js/clipboard.min.js')}}"></script>

<script defer src="{{URL::asset('public/vendor/internal-pages/js/custom.js')}}"></script>


<!--for chart.js-->
<script defer src="{{URL::asset('public/vendor/scripts/moment.min.js')}}"></script>
<script defer src="{{URL::asset('public/vendor/scripts/utils.js')}}"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/Chart.min.js')}}"></script>
<script defer src="{{URL::asset('public/vendor/scripts/chartjs-plugin-trendline.js?')}}"></script>

<!-- <script defer  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/campaign_detail.js')}}"></script> -->

<!-- ppc script -->
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/scrolls.js')}}"></script>
<!-- ppc script -->




<!-- <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/audit.js?v='.time())}}"></script> -->
<!-- <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/site_audit.js?v='.time())}}"></script> -->
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/audit-reports.js?v='.time())}}"></script>

@include('includes.vendor.audit_popup')


</body>

</html>