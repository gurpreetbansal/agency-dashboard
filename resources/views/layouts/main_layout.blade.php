  <!doctype html>
  <html>

  <head>
    <meta charset="utf-8">
    @if(Request::is('seo-report'))
    <title>Automate SEO Reports for Agency Clients | Agency Dashboard</title>
    <meta name="description" content="Create & send automated SEO performance reports to your agency clients. Includes keyword rankings, backlink reports, website analytics & more.  Ask for a demo.">
    @elseif(Request::is('integrations'))
    <title>Integrations - Add Over 50 Platforms | Agency Dashboard</title>
    <meta name="description" content="Agency Dashboard allows you to integrate over 50 marketing platforms to engineer a custom marketing dashboard for your agency clients. Try it for FREE.">
    @elseif(Request::is('price'))
    <title>Pricing & Packages For Marketing Agencies | Agency Dashboard</title>
    <meta name="description" content="We have monthly & annual plans for freelancers, agencies, and enterprises. Save up to 20% with annual plans. Use it for FREE for 14 Days. Chat with us.">
    @else
    <title>#1 Reporting Tool For Marketing Agencies | Agency Dashboard</title>
    <meta name="description" content="Track and report the performance of your clients’ marketing campaigns with Agency Dashboard. SEO, PPC, SMM, Email, Call Tracking & More. Try FREE Trial">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{URL::asset('public/front/img/favicon.png')}}" sizes="32x32" type="image/x-icon">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSS -->

    <link defer rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link defer rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600&display=swap">

    <link rel="stylesheet" href="{{URL::asset('public/front/css/main.css')}}">
    <link rel="stylesheet" href="{{URL::asset('public/front/css/custom.css')}}">
    
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-TR6PRJK');</script>
    
    <script>
      window.fwSettings={
        'widget_id':84000000007
      };
      !function(){if("function"!=typeof window.FreshworksWidget){var n=function(){n.q.push(arguments)};n.q=[],window.FreshworksWidget=n}}()
    </script>
    <script type='text/javascript' src='https://ind-widget.freshworks.com/widgets/84000000007.js' async defer></script>

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
    <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js'); fbq('init', '5488354771220902'); fbq('track', 'PageView');</script>
    <noscript> <img height="1" width="1" src="https://www.facebook.com/tr?id=5488354771220902&ev=PageView&noscript=1"/></noscript>
    <!-- End Facebook Pixel Code -->

    <meta name="facebook-domain-verification" content="2bsuc949xhc10jqujxshqmy1ebpqma" />

    <script>
      window.onsetWidgetSettings = {
        triggerText: '🔔 What\'s New',
        page: 'agency-dashboard.onset.io'
      };

      (function (e, t) {
        e.onsetWidget = {};
        e.onsetWidget.on = function () {
          (e.onsetWidget.$ = e.onsetWidget.$ || []).push(arguments);
        };
        var c = t.getElementsByTagName('script')[0],
        i = t.createElement('script');
        i.async = true;
        i.src = 'https://widget.onset.io/widget.js';
        c.parentNode.insertBefore(i, c);
      })(window, document);
    </script>

    <input type="hidden" value="{{url('/')}}" class="base_url">
    <input type="hidden" class="app_url" value="{{\env('APP_URL')}}" />
  </head>

  <body>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TR6PRJK"height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <?php

    if(Request::is('login') || (request()->account != null) || Request::is('register') || Request::is('subscription') || Request::is('recover-password')|| Request::is('reset-password/*')) { ?>
      @yield('content')
    <?php } else{ 
      ?>
      <header>
        <nav class="navbar navbar-expand-lg">
          <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="{{url('/')}}"><img src="{{URL::asset('public/front/img/logo.svg')}}" alt="Agency Dashboard"></a>

            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
              <span class="navbar-toggler-icon">
                <span class="menu-icon-bar"></span>
                <span class="menu-icon-bar"></span>
                <span class="menu-icon-bar"></span>
              </span>
            </button>

            <!-- Navbar links -->
            <div class="navbar-elem">
              <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('/'.'#OurFeatures')}}">Features</a>
                    <ul>
                      <li class="nav-item">
                        <a class="nav-link" href="{{url('/rank-tracker')}}">
                          <figure><img src="{{URL::asset('public/front/img/rank-tracker-icon.svg')}}" alt="rank-tracker-icon"></figure>
                          Rank tracker
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{route('front.seo-report')}}">Reports</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{route('front.integrations')}}">Integrations</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{route('front.pricing')}}#comparePlans">Pricing</a>
                  </li>
                </ul>
              </div>

              <div class="auth-links">
                <ul>
                  <?php if(Auth::user() == null){ ?>
                    <li>
                      <a href="{{url('/login')}}" class="btn btn-transparent btn-border">Log in</a>
                    </li>
                    <li>
                      <a href="{{route('front.pricing')}}" class="btn btn-blue">Start Free Trial</a>
                    </li>
                  <?php } else{ 
                    $userData = App\User::get_parent_vanity(Auth::user());
                    if($userData !== '' && $userData !== null){
                      $dashboard_link = $_SERVER['REQUEST_SCHEME'].'://'.$userData.'.'.\config('app.DOMAIN_NAME').'/dashboard';
                    }else{
                      if(Auth::user()->is_admin == 1){
                        $dashboard_link = $_SERVER['REQUEST_SCHEME'].'://'.\config('app.DOMAIN_NAME').'/admin/dashboard';
                      }else{
                        $dashboard_link = url('/login');
                      }
                    }
                    ?>
                    <li>
                      <a href="{{$dashboard_link}}" class="btn btn-transparent btn-border">Dashboard</a>
                    </li>
                  <?php } ?>
                </ul>
              </div>

            </div>
          </div>
        </nav>
      </header>
      <main>

        @yield('content')

        <div id="particles-js" class="particles-js"></div>
      </main>
      <footer>
        <div class="container">
          <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
              <div class="footer-about">
                <figure>
                  <a href="{{url('/')}}"><img src="{{URL::asset('public/front/img/logo.svg')}}" alt="Agency Dashboard"></a>
                </figure>
                <p>A Powerful & Data-Driven Agency Reporting Tool</p>

                <div class="social-acc">
                  <ul>
                    <li>
                      <a href="https://www.facebook.com/Agency-Dashboard-103776602396524" target="_blank"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                      <a href="https://www.instagram.com/agencydashboard" target="_blank"><i class="fa fa-instagram"></i></a>
                    </li>
                    <li>
                      <a href="https://twitter.com/AgencyDashboard" target="_blank"><i class="fa fa-twitter"></i></a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-2 col-md-6 col-sm-6 col-6">
              <div class="quick-nav">
                <h5>Important Links</h5>
                <ul>
                  <li>
                    <a href="{{url('/'.'#OurFeatures')}}">Features</a>
                  </li>
                      <!-- <li>
                        <a href="{{route('front.seo-report')}}">Reports</a>
                      </li> -->
                      <li>
                        <a href="{{route('front.integrations')}}">Integrations</a>
                      </li>
                      <li>
                        <a href="{{route('front.pricing')}}#comparePlans">Pricing</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">
                  <div class="quick-nav">
                    <h5>Sample Dashboard</h5>
                    <ul>
                      <li>
                        <a href="{{@$link}}" target="_blank">SEO Dashboard</a>
                      </li>
                      <!-- <li>
                        <a href="{{route('front.integrations')}}">Integrations</a>
                      </li> -->
                    </ul>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12">
                  <div class="support-nav">
                    <h5>Support</h5>
                    <ul>
                      <li>
                        <figure><i class="fa fa-envelope"></i></figure>
                        <a href="mailto:support@agencydashboard.io">support@agencydashboard.io</a>
                        Send us an email to automatically create a ticket
                      </li>
                    </ul>
                    <!-- <a href="javascript:;" onclick="$crisp.push(['do', 'chat:open'])" class="btn btn-blue btn-xl chat-btn"><img src="{{URL::asset('public/front/img/chat-icon.png')}}"> Chat with us</a> -->
                  </div>

                </div>
              </div>
            </div>
            <div class="footer-copyright">
              <p>&copy; <?php echo date('Y');?> agencydashboard Inc, All Rights Reserved. <a href="{{url('/privacy-policy')}}">Privacy Policy</a> | <a href="{{url('/terms-conditions')}}">Terms and
              Conditions</a></p>
            </div>
          </footer>
        <?php } ?>
        <!-- jQuery first, then Bootstrap JS. -->
        <script defer src="{{URL::asset('public/front/scripts/bundle.min.js')}}"></script>

        <?php if(! Request::is('login') && ! Request::is('register') && ! Request::is('subscription')) { ?>
          <script defer src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
          <script defer src="{{URL::asset('public/front/scripts/particle.js')}}"></script>
        <?php } ?>
        <script defer src="{{URL::asset('public/front/scripts/rellax.js')}}"></script>
        <script defer src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
        <script defer src="{{URL::asset('public/front/scripts/custom.js')}}"></script>
        <script defer src="{{URL::asset('public/front/scripts/developer.js')}}"></script>
        <script defer src="{{URL::asset('public/front/scripts/register.js?v='.time())}}"></script>
        <script defer src="{{URL::asset('public/front/scripts/subscription.js?v='.time())}}"></script>
        @include('includes.front.downgrade_popup')

      </body>

      </html>