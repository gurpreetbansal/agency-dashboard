<!doctype html>
<html>

    <head>
        <meta charset="utf-8">
        <title>Agency Dashboard</title>
        <meta name="viewport"
              content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Favicon -->
       <link rel="shortcut icon" href="{{ asset('/public/front/images/fav-icon.png') }}">

        <!-- CSS -->
        <link rel="stylesheet" href="{{URL::asset('public/css/main.css')}}">
        <link rel="stylesheet" href="{{URL::asset('public/css/custom.css?v='.time())}}">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/latest/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/latest/respond.min.js"></script>
          <![endif]-->
    </head>

    <body>
        @if (!Request::is('/')) {
        <div class="banner ">
            <div class="container">
                <div class="banner-text">

@endif
                    <header data-aos="fade-down" data-aos-duration="1000">
                        <nav class="navbar navbar-expand-md">
                            <div class="container">
                                <a class="navbar-brand" href="{{url('/')}}">
                                    <img src="{{URL::asset('public/front/images/logo-white.png')}}" alt="Logo">
                                    <img src="{{URL::asset('public/front/images/logo.png')}}" alt="Logo">
                                </a>

                                <!-- Toggler/collapsibe Button -->
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                                    <span class="navbar-toggler-icon"></span>
                                </button>

                                <!-- Navbar links -->
                                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                                    <ul class="navbar-nav">
                                        <li class="nav-item">
                                            <!-- <a class="nav-link" href="#Features">Features</a> -->
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{url('/pricing')}}">Pricing</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="auth-menu">
                                    <ul>
                                        <li><a href="{{url('/login')}}">Login</a></li>
                                        <li><a href="{{url('/pricing')}}" class="btn btn-menu">Start Free Trial</a></li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </header>

                    @yield('content')
                    
                     @if (!Request::is('/')) {
                </div>
            </div>

            <div class="banner-shade"></div>
        </div>
@endif
        <footer>

            <div class="container">
               <!--  <div class="try-free-section">
                    <div class="logo">
                        <img src="{{URL::asset('public/images/logo.png')}}" alt="try free">
                    </div>
                    <h3>Try SEO Reports Free for 14 Days</h3>
                    <div class="free-form">
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Enter your email">
                            </div>
                            <button type="submit" class="btn btn-green">Create Account</button>
                        </form>
                    </div>
                </div> -->

                <!-- <div class="row">
                    <div class="col-lg-3">
                        <h5>Product</h5>
                        <ul>
                            <li>
                                <a href="#">Features</a>
                            </li>
                            <li>
                                <a href="#">Integrations</a>
                            </li>
                            <li>
                                <a href="#">Pricing</a>
                            </li>
                            <li>
                                <a href="#">Start Free Trial</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-3">
                        <h5>Company</h5>
                        <ul>
                            <li><a href="#">About</a></li>
                            <li><a href="#">Customers</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Careers</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3">
                        <h5>Resources</h5>
                        <ul>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Book a Demo</a></li>
                            <li><a href="#">Help Center</a></li>
                            <li><a href="#">Dashboard Templates</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3">
                        <h5>Reviews</h5>
                        <div class="social-links">

                            <a href="#" target="_blank"><i class="fa fa-facebook"></i></a>
                            <a href="#" target="_blank"><i class="fa fa-google-plus"></i></a>
                            <a href="#" target="_blank"><i class="fa fa-linkedin"></i></a>

                        </div>
                    </div>
                </div> -->

                <div class="copyright">
                    <p>&copy; 2020 <a href="/">AGENCYDASHBOARD.IO</a>, All Rights Reserved. <a href="{{url('/privacy-policy')}}">Privacy Policy</a> | <a
                            href="{{url('/privacy-policy')}}">Terms and Conditions</a></p>
                </div>

            </div>

        </footer>


        <!-- jQuery first, then Bootstrap JS. -->
        <script src="{{URL::asset('public/js/jquery.min.js')}}"></script>
        <script src="{{URL::asset('public/js/popper.min.js')}}"></script>
        <script src="{{URL::asset('public/js/bootstrap.min.js')}}"></script>
        <script src="{{URL::asset('public/js/aos.js')}}"></script>
        <script src="{{URL::asset('public/js/slick.js')}}"></script>
        <script src="{{URL::asset('public/js/custom.js')}}"></script>
        <script src="{{URL::asset('public/js/custom_js.js?v='.time())}}"></script>
      
    </body>

</html>