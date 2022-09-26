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
    <link rel="shortcut icon" href="favicon.png" sizes="32x32" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="{{URL::asset('public/front/css/main.css')}}">
    <link rel="stylesheet" href="{{URL::asset('public/front/css/custom.css')}}">
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-TR6PRJK');</script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/latest/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/latest/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TR6PRJK"height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <main>
        <section class="not-found-section <?php if(!empty(Auth::user())){ echo 'blue';}?> section404">
            <div class="container">
                <div class="not-found-section-inner thankyou-text">
                    <div class="elem-left">
                        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/ag-dash-check.png')}}"></figure>
                        <h2><big>Thank you</big> for subscribing to <strong>{{@$package_name}}</strong> package.</h2>
                        <h3>You'll be redirected in a moment to your dashboard.</h3>
                        <input type="hidden" value="{{@$redirect_link}}"  class="thank_you_redirect">
                    </div>
                    <div class="elem-right">
                        <div class="post-with-icon-cover">

                            <div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
                                <figure>
                                    <img src="{{URL::asset('public/front/img/seo-icon.png')}}" alt="SEO">
                                </figure>
                                <h4>SEO</h4>
                                <p>Showcase your rankings and search visibility</p>
                            </div>

                            <div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
                                <figure>
                                    <img src="{{URL::asset('public/front/img/ppc-icon.png')}}" alt="PPC">
                                </figure>
                                <h4>Pay Per Click</h4>
                                <p>Track progress of your Google Ads campaign</p>
                            </div>

                            <div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
                                <figure>
                                    <img src="{{URL::asset('public/front/img/smm-icon.png')}}" alt="SMM">
                                </figure>
                                <h4>Social Media Marketing</h4>
                                <p>Brand monitoring on fb, twitter, insta and more...</p>
                            </div>

                            <div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
                                <figure>
                                    <img src="{{URL::asset('public/front/img/my-business-icon.png')}}" alt="My business">
                                </figure>
                                <h4>My Business</h4>
                                <p>Find out more about your local SEO</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="shape1 full">
                <img src="{{URL::asset('public/front/img/shape-1.png')}}">
            </div>

            <div class="shape3 full" >
                <img src="{{URL::asset('public/front/img/shape-3.png')}}">
            </div>
        </section>

        <div id="particles-js" class="particles-js"></div>
    </main>

    <!-- jQuery first, then Bootstrap JS. -->
    <script src="{{URL::asset('public/front/scripts/bundle.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="{{URL::asset('public/front/scripts/particle.js')}}"></script>
    <script src="{{URL::asset('public/front/scripts/rellax.js')}}"></script>
    <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
    <script src="{{URL::asset('public/front/scripts/custom.js?v='.time())}}"></script>
    <script>
        var timer = setTimeout(function () {
            value = $('.thank_you_redirect').val();
            window.location.href = value;
        }, 5000);
    </script>
</body>

</html>