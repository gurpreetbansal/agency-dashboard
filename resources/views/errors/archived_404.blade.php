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
                        <h2>This campaign has been suspended by your agency.</h2>
                        <h2>To view the data, please contact agency owner.</h2>
                        <figure>
                            <img src="{{URL::asset('public/front/img/404-img.png')}}" alt="404">
                        </figure>
                        <a href="{{url('/')}}" class="btn btn-blue">Go Back To Your Dashboard</a>
                    </div>
                    @include('errors.common_right')
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