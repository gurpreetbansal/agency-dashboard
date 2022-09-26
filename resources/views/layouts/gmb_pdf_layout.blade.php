<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <title>Agency Dashboard: # 1 Reporting Platform for Digital Marketing Agencies</title>
  <meta name="description" content="Agency Dashboard offers valuable insights through SEO, Local, PPC, & Social dashboards for all your marketing channels under a single roof. Trusted by more than 500 agencies across the world.">
  <meta name="viewport"
  content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{URL::asset('public/front/img/favicon.png')}}" sizes="32x32" type="image/x-icon">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" type="text/css" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"  media="all">
  <link rel="stylesheet" type="text/css"
  href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600&display=swap">
  <link rel="stylesheet" type="text/css" href="{{URL::asset('public/viewkey/css/pdf.css')}}" media="all">


  <!-- On Page Loading JS -->
  <script src="{{URL::asset('public/vendor/internal-pages/js/bundle.min.js')}}"></script>
  <script src="{{URL::asset('public/vendor/internal-pages/js/bootstrap-select.js')}}"></script>
  <script src="{{URL::asset('public/vendor/internal-pages/js/clipboard.min.js')}}"></script>
  <script src="{{URL::asset('public/vendor/internal-pages/js/custom.js')}}"></script>
    <!--for chart.js-->
  <script src="{{URL::asset('public/vendor/scripts/moment.min.js')}}"></script>
  <script src="{{URL::asset('public/vendor/scripts/utils.js')}}"></script>
  <script src="{{URL::asset('public/vendor/internal-pages/js/Chart.min.js')}}"></script>
  <input type="hidden" class="base_url" value="<?php echo url('/');?>" />
</head>
<body>
 <main>
   <div class="pdf-face">    

    <div class="top"><img src="{{URL::asset('public/vendor/internal-pages/images/curve-top.png')}}"></div>
    <div class="right"><img src="{{URL::asset('public/vendor/internal-pages/images/curve-right.png')}}"></div>
    <div class="bottom"><img src="{{URL::asset('public/vendor/internal-pages/images/curve-bottom.png')}}"></div>

    <div class="pdf-heading">
      <div class="logo">

        @if($data->project_logo <> null)
        <img src="{{URL::asset('public/storage/project_logo/').'/'.$data->id.'/'.$data->project_logo }}">
        @else
        <img src="{{URL::asset('public/front/img/logo.png')}}">
        @endif
      </div>
       <h2>{{@$type}} <small>{{ $data->host_url}}</small></h2>
    </div>
  </div>
   @include('includes.viewkey.breadcrumb')
   <div class="project-detail-body">
    @yield('content')
  </div>
</main>
<script defer type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyfg5RfXsDreSKWq7-P-VjfW7d2-abe8c&libraries=places"></script>
<script  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/campaign_detail.js')}}"></script>
<script  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/pdf_gmb.js')}}"></script>
</body>
</html>