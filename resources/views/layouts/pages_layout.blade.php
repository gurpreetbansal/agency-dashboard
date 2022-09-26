 <!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Agency Dashboard</title>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:100,300,400,600' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ URL::asset('/public/front/css/single-page-basscss.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('/public/front/css/animate.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('/public/front/css/single-page-style.css')}}">
    <!-- <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> -->
    <link rel="shortcut icon" href="{{ asset('/public/front/images/fav-icon.png') }}">
    <!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        var wow = new WOW({
            boxClass: 'wow', // animated element css class (default is wow)
            animateClass: 'animated', // animation css class (default is animated)
            offset: 1, // distance to the element when triggering the animation (default is 0)
            mobile: true, // trigger animations on mobile devices (default is true)
            live: false // act on asynchronously loaded content (default is true)
        });
        wow.init();
    </script>
</head>

<body>
  @yield('content')

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="{{ URL::asset('/public/vendor/scripts/main.min.js')}}"></script>
    <script>
        particlesJS('particles-js', {
            particles: {
                color: '#67c0ea'
                , shape: 'circle'
                , opacity: 1
                , size: 2.5
                , size_random: true
                , nb: 100
                , line_linked: {
                    enable_auto: true
                    , distance: 340
                    , color: '#86cdef'
                    , opacity: 0.5
                    , width: 1
                    , condensed_mode: {
                        enable: false
                        , rotateX: 600
                        , rotateY: 600
                    }
                }
                , anim: {
                    enable: true
                    , speed: 1.5
                }
            }
            , interactivity: {
                enable: true
                , mouse: {
                    distance: 100
                }
                , detect_on: 'canvas'
                , mode: 'grab'
            }
            , retina_detect: true
        });
    </script>
</body>

</html>