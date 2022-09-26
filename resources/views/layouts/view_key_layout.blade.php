<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Agency Dashboard: # 1 Reporting Platform for Digital Marketing Agencies</title>
  <meta name="description" content="Agency Dashboard offers valuable insights through SEO, Local, PPC, & Social dashboards for all your marketing channels under a single roof. Trusted by more than 500 agencies across the world.">

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
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-TR6PRJK');</script>
  <!-- On Page Loading CSS -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600&display=swap">
  
  <link rel="stylesheet" href="{{URL::asset('public/vendor/internal-pages/css/main.css')}}">
  <link defer rel="stylesheet" href="{{URL::asset('public/vendor/internal-pages/css/custom.css')}}">
  
  <script type="text/javascript">
    (function(w,d,s,r,k,h,m){
      if(w.performance && w.performance.timing && w.performance.navigation) {
        w[r] = w[r] || function(){(w[r].q = w[r].q || []).push(arguments)};
        h=d.createElement('script');h.async=true;h.setAttribute('src',s+k);
        d.getElementsByTagName('head')[0].appendChild(h);
        (m = window.onerror),(window.onerror = function (b, c, d, f, g) {
          m && m(b, c, d, f, g),g || (g = new Error(b)),(w[r].q = w[r].q || []).push(["captureException",g]);})
      }
    })(window,document,'//static.site24x7rum.com/beacon/site24x7rum-min.js?appKey=','s247r','e7accd4f7bace3cb42b9076edf127604');
  </script>

  <!-- Facebook Pixel Code -->
  <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '1077994786367896');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=1077994786367896&ev=PageView&noscript=1"
      /></noscript>
      <!-- End Facebook Pixel Code -->
       <meta name="facebook-domain-verification" content="2bsuc949xhc10jqujxshqmy1ebpqma" />


     <!--   <script type="text/javascript">
  window._mfq = window._mfq || [];
  (function() {
    var mf = document.createElement("script");
    mf.type = "text/javascript"; mf.defer = true;
    mf.src = "//cdn.mouseflow.com/projects/548aac21-896a-42af-887d-75857dda52f7.js";
    document.getElementsByTagName("head")[0].appendChild(mf);
  })();
</script> -->
      <input type="hidden" class="base_url" value="<?php echo url('/');?>" />
      <input type="hidden" class="app_url" value="{{\env('APP_URL')}}" />
    </head>

      @if(!Request::is('audit/page/detail/*'))
  <body >
      @include('includes.viewkey.sidebar')
    @else
  <body class="extra-view-key" >
      @include('includes.viewkey.auditSidebar')
    @endif
      <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TR6PRJK"height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
     
      <div class="preloader-wrapper">
        <img src="{{URL::asset('public/vendor/internal-pages/images/preloader.gif')}}" alt="loader">
      </div>
      <main class="viewkey-output">
        @if(!Request::is('audit/page/detail/*'))
          @include('includes.viewkey.breadcrumb')
        @else
          @include('includes.viewkey.audit-breadcrumb')
        @endif
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
      <script defer type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
      <link defer rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/site_audit.js?v='.time())}}"></script>

      <script defer  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/campaign_detail.js')}}"></script>
      <script defer  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ga4_campaign_detail.js')}}"></script>
      <script defer  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ga4.js')}}"></script>
      <script defer  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/organic_keyword_growth.js')}}"></script>
      <script defer  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/backlink_profile.js')}}"></script>
      <script defer  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/goal_completion.js')}}"></script>
      <script defer  src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ecommerce_goals.js')}}"></script>
      <script defer  src="{{URL::asset('public/viewkey/scripts/live_keyword_tracking.js')}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/search_console.js')}}"></script>
      @include('includes.viewkey.keyword_popup')
      @include('includes.vendor.audit_popup')
      <!-- ppc script -->
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_summary.js')}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_summary_graph.js')}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_campaign.js')}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_keyword.js')}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_adsgroup.js')}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_ads.js')}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_performance_network.js')}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_performance_device.js')}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_performance_clickType.js')}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/ppc_performance_adSlot.js')}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/scrolls.js')}}"></script>
      <!-- ppc script -->

      <!--gmb script--> 
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/gmb.js')}}"></script>  
      <!--gmb script-->
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/audit-reports.js?v='.time())}}"></script>
      <!--social script-->
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/social_overview.js')}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/social.js')}}"></script>
      
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/activities.js')}}"></script>

      <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.css" />
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/javascript.js?v='.time())}}"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/xml/xml.js"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/htmlmixed/htmlmixed.js"></script>
      <script src="https://codemirror.net/addon/search/search.js"></script>
      <script src="https://codemirror.net/addon/search/searchcursor.js"></script>
      <script src="https://codemirror.net/addon/search/jump-to-line.js"></script>
      <script src="https://codemirror.net/addon/dialog/dialog.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/addon/fold/foldcode.min.js"></script>


      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/theme/dracula.min.css" />
      <link rel="stylesheet" href="https://codemirror.net/addon/dialog/dialog.css" /> -->

      
      <script defer src="{{URL::asset('public/viewkey/scripts/keyword_explorer.js')}}"></script>
      <!-- <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/audit.js?v='.time())}}"></script>
      <script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/audit2.js?v='.time())}}"></script> -->
      @include('includes.vendor.keyword_explorer_popup')
      @include('includes.vendor.popup_modals')
      @include('includes.vendor.audit_popup')
   
    
    </body>

    </html> 