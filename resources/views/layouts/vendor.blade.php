<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ \config('app.APP_NAME')}} | Welcome</title>
    <meta name="viewport"
    content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="ArchitectUI HTML Bootstrap 4 Dashboard Template">
    <link rel="shortcut icon" href="{{ asset('/public/front/images/fav-icon.png') }}">
    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <link href="{{ URL::asset('/public/vendor/css/main.css?v='.time())}}" rel="stylesheet">
    <link href="{{ URL::asset('/public/vendor/css/custom.css?v='.time())}}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100">
                <div class="h-100 no-gutters row">
                    <div class="d-lg-flex d-xs-none col-lg-5">
                        <div class="slider-light">
                            <div class="slick-slider">

                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-premium-dark" tabindex="-1">
                                        <div class="slide-img-bg" style="background-image: url('{{asset('public/admin/images/originals/citynights.jpg')}}');"></div>
                                        <div class="slider-content">
                                            <h3>Scalable, Modular, Consistent</h3>
                                            <p>Easily exclude the components you don't require. Lightweight, consistent
                                                Bootstrap based styles across all elements and components
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    @yield('content')


                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ URL::asset('/public/vendor/scripts/main.js?v='.time())}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{URL::asset('/public/vendor/scripts/custom.js?v='.time())}}" type="text/javascript"></script>

</body>

</html>

