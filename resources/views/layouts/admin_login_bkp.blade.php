<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Language" content="en">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Agency Dashboard</title>
        <meta name="viewport"
              content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
        <meta name="description" content="ArchitectUI HTML Bootstrap 4 Dashboard Template">
        <link rel="shortcut icon" href="{{ asset('/public/front/images/fav-icon.png') }}">

        <!-- Disable tap highlight on IE -->
        <meta name="msapplication-tap-highlight" content="no">

        <link href="{{ URL::asset('/public/admin/css/main.css?v='.time())}}" rel="stylesheet"></head>

    <body>
        <div class="app-container app-theme-white body-tabs-shadow">
            <div class="app-container">
                <div class="h-100">
                    <div class="h-100 no-gutters row">
                        <div class="d-none d-lg-block col-lg-4">
                            <div class="slider-light">
                                <div class="slick-slider">
                                    <div>
                                        <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-plum-plate" tabindex="-1">
                                            <div class="slide-img-bg" style="background-image: url('{{asset('public/admin/images/originals/city.jpg')}}');"></div>
                                            <div class="slider-content">
                                                <h3>Perfect Balance</h3>
                                                <p>ArchitectUI is like a dream. Some think it's too good to be true! Extensive
                                                    collection of unified React Boostrap Components and Elements.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
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
                                    <div>
                                        <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-sunny-morning" tabindex="-1">
                                            <div class="slide-img-bg" style="background-image: url(' {{ asset('public/admin/images/originals/citydark.jpg')}}');"></div>
                                            <div class="slider-content">
                                                <h3>Complex, but lightweight</h3>
                                                <p>We've included a lot of components that cover almost all use cases for any type of application.</p>
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
        <script type="text/javascript" src="{{ URL::asset('/public/admin/scripts/main.js?v='.time())}}"></script></body>

</html>

