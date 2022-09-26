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


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/latest/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/latest/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <main>
        <section class="not-found-section <?php if(!empty(Auth::user())){ echo 'blue';}?>">
            <div class="container">
                <div class="not-found-section-inner">
                    <div class="elem-left">
                        <h2>The request method is not allowed</h2>
                        <figure>
                            <img src="{{URL::asset('public/front/img/405-img.jpg')}}" alt="405">
                        </figure>
                        @if(!empty(Auth::user()))
                         <a href="{{url('/dashboard')}}" class="btn btn-blue">Go Back To Your Dashboard</a>
                        @else
                        <a href="{{url('/')}}" class="btn btn-blue">Go Back To Your Dashboard</a>
                        @endif
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

</body>

</html>