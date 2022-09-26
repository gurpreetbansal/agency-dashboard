<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Agency Dashboard: # 1 Reporting Platform for Digital Marketing Agencies</title>
  <meta name="description" content="Agency Dashboard offers valuable insights through SEO, Local, PPC, & Social dashboards for all your marketing channels under a single roof. Trusted by more than 500 agencies across the world.">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{URL::asset('public/front/img/favicon.png')}}" sizes="32x32" type="image/x-icon">

  <meta name="csrf-token" content="{{ csrf_token() }}">

 
  <link rel="stylesheet" type="text/css" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"  media="all">
  <link rel="stylesheet" type="text/css"
  href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600&display=swap">
  @if(!Request::is('display-pdf') && !Request::is('display-pdf-index'))
  <link rel="stylesheet" type="text/css" href="{{URL::asset('public/viewkey/css/pdf.css')}}" media="all">
  @endif

  <!-- On Page Loading JS -->
  <script src="{{URL::asset('public/vendor/internal-pages/js/bundle.min.js')}}"></script>

  <script src="{{URL::asset('public/vendor/internal-pages/js/bootstrap-select.js')}}"></script>

  <script src="{{URL::asset('public/vendor/internal-pages/js/clipboard.min.js')}}"></script>

  <script src="{{URL::asset('public/vendor/internal-pages/js/custom.js')}}"></script>

  <!--for chart.js-->
  <script src="{{URL::asset('public/vendor/scripts/moment.min.js')}}"></script>
  <script src="{{URL::asset('public/vendor/scripts/utils.js')}}"></script>
  <script src="{{URL::asset('public/vendor/internal-pages/js/Chart.min.js')}}"></script>
  <script src="{{URL::asset('public/vendor/scripts/chartjs-plugin-trendline.js?')}}"></script>


  <input type="hidden" class="base_url" value="<?php echo url('/');?>" />
  <input type="hidden" class="pdf_status" value="1" />
</head>

<body>
 <main>
   <div class="pdf-face">    

    <div class="top"><img src="{{URL::asset('public/vendor/internal-pages/images/curve-top.png')}}"></div>
    <div class="right"><img src="{{URL::asset('public/vendor/internal-pages/images/curve-right.png')}}"></div>
    <div class="bottom"><img src="{{URL::asset('public/vendor/internal-pages/images/curve-bottom.png')}}"></div>

    <div class="pdf-heading">
      <div class="logo">
        @if(@$data->project_logo <> null)
        <img src="{{URL::asset('public/storage/project_logo/').'/'.$data->id.'/'.$data->project_logo }}">
        @else
        <img src="{{URL::asset('public/front/img/logo.png')}}">
        @endif
      </div>
      <h2>{{@$type}} <small>{{ @$data->host_url}}</small></h2>
    </div>
  </div>
   
    @if(Request::is('pdf/audit/*'))
    @include('includes.vendor.audit-breadcrumb')
    @else
    @include('includes.viewkey.breadcrumb')
    @endif
   
   <div class="project-detail-body" >
    @yield('content')
  </div>
</main>

<script  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/campaign_detail.js')}}"></script>
<script  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/search_console.js')}}"></script>
<script  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/organic_keyword_growth.js')}}"></script>
<script  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/backlink_profile.js')}}"></script>
<script  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/goal_completion.js')}}"></script>
<script  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ecommerce_goals.js')}}"></script>
<script  src="{{URL::asset('public/viewkey/scripts/live_keyword_tracking.js')}}"></script>
<script  src="{{URL::asset('public/viewkey/scripts/ga4.js?v='.time())}}"></script>

<!-- ppc script -->
<script src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_summary.js')}}"></script>
<script src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_summary_graph.js')}}"></script>
<script src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_campaign.js')}}"></script>
<script src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_keyword.js')}}"></script>
<script src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_adsgroup.js')}}"></script>
<script src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_ads.js')}}"></script>
<script src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_performance_network.js')}}"></script>
<script src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_performance_device.js')}}"></script>
<script src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_performance_clickType.js')}}"></script>
<script src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_performance_adSlot.js')}}"></script>
<script src="{{URL::asset('public/vendor/internal-pages/js/developerjs/scrolls.js')}}"></script>
<!-- ppc script -->

<!--gmb script-->
<script src="{{URL::asset('public/vendor/internal-pages/js/developerjs/gmb.js')}}"></script>
<!--gmb script-->

<script  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/audit-reports.js')}}"></script>
<script  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/social.js')}}"></script>
<!-- <script  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/audit.js')}}"></script>
<script  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/audit2.js')}}"></script>
 -->




</body>

</html>