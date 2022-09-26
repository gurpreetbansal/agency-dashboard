<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Language" content="en">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Agency Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
        <meta name="description" content="This is an example dashboard created using build-in elements and components.">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('/public/front/images/fav-icon.png') }}">

        <!--
        =========================================================
        * ArchitectUI HTML Theme Dashboard - v1.0.0
        =========================================================
        * Product Page: https://dashboardpack.com
        * Copyright 2019 DashboardPack (https://dashboardpack.com)
        * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
        =========================================================
        * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
        -->
        
        
        <link href="{{ URL::asset('/public/vendor/css/main.css?v='.time())}}" rel="stylesheet">
        <link href="{{ URL::asset('/public/vendor/css/jquery.dataTables.min.css')}}" rel="stylesheet">
        <link href="{{ URL::asset('/public/viewkey/css/jquery.mCustomScrollbar.min.css')}}" rel="stylesheet">  

        <link href="{{ URL::asset('/public/vendor/css/custom.css?v='.time())}}" rel="stylesheet">
        <link href="{{ URL::asset('/public/viewkey/css/custom.css?v='.time())}}" rel="stylesheet">
        
    </head>
    <body>

        <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">

            @include('viewkey_includes.header')

            <div class="app-main">

                @include('viewkey_includes.sidebar')
                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-inner-layout">
                            @include('viewkey_includes.breadcrumb')
                            @yield('content')                           
                         </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <input type="hidden" class="base_url" value="<?php echo url('/');?>" />
        <div class="app-drawer-overlay d-none animated"></div>
        
        <script async type="text/javascript" src="{{ URL::asset('/public/vendor/scripts/main.js?v='.time())}}"></script>
        <script src="{{ URL::asset('/public/vendor/scripts/jquery.min.js')}}"></script> 

 <!--daterangepicker-->
        <script src="{{ URL::asset('/public/vendor/scripts/moment.min.js')}}"></script>
        <script src="{{ URL::asset('/public/vendor/scripts/daterangepicker.min.js')}}"></script>
        <script src="{{ URL::asset('/public/viewkey/scripts/jquery.mCustomScrollbar.concat.min.js')}}"></script> 
        <script src="{{ URL::asset('/public/vendor/scripts/utils.js')}}"></script>
        <script src="{{ URL::asset('/public/vendor/scripts/Chart.min.js')}}"></script>
        <script src="{{ URL::asset('/public/vendor/scripts/chartjs-plugin-trendline.js')}}"></script>
        <script src="{{ URL::asset('/public/vendor/scripts/jquery.dataTables.min.js')}}"></script>
        <script src="{{ URL::asset('/public/vendor/scripts/highcharts.js')}}"></script>

       

        <script type="text/javascript" src="{{URL::asset('/public/vendor/scripts/toastr.js')}}"></script>

        <script async type="text/javascript" src="{{ URL::asset('/public/viewkey/scripts/dashboard.js?v='.time())}}"></script>
        <script async type="text/javascript" src="{{ URL::asset('/public/viewkey/scripts/sidebar.js?v='.time())}}"></script>

    </body>
</html>
