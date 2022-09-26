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
        <link href="{{ URL::asset('/public/vendor/css/fileinput.css?v='.time())}}" media="all" rel="stylesheet" type="text/css"/>

         <link href="{{ URL::asset('/public/viewkey/css/jquery.mCustomScrollbar.min.css')}}" rel="stylesheet">  
        <link href="{{ URL::asset('/public/vendor/css/custom.css?v='.time())}}" rel="stylesheet">
        <link href="{{ URL::asset('/public/vendor/css/jquery.dataTables.min.css')}}" rel="stylesheet">
        
		<link href="{{ URL::asset('/public/vendor/css/jquery-ui.css')}}" rel="stylesheet">
        <link href="{{ URL::asset('/public/vendor/css/select2.min.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{URL::asset('/public/vendor/css/selectize.default.css')}}">
        <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css"> -->


    </head>
    <body>

        <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">

            @include('includes.vendor_header')
            @include('includes.vendor_setting')

            <div class="app-main">

                @include('includes.vendor_sidebar')
                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-inner-layout">
                            @include('includes.vendor_breadcrumb')

                            @yield('content')
    					
                            <!-- <div class="loader">

                            </div> -->
                         </div>
                    </div>
                    
                </div>
            </div>
            @include('includes.vendor_drawer')
        </div>
        <input type="hidden" class="base_url" value="<?php echo url('/');?>" />
        <div class="app-drawer-overlay d-none animated"></div>
		
        <script async type="text/javascript" src="{{ URL::asset('/public/vendor/scripts/main.js?v='.time())}}"></script>

        <script src="{{ URL::asset('/public/vendor/scripts/jquery.min.js')}}"></script>
        <script src="{{ URL::asset('/public/vendor/scripts/jquery.dataTables.min.js')}}"></script>

        <script src="{{ URL::asset('/public/vendor/scripts/jquery-ui.js')}}"></script>
        <!--daterangepicker-->
        <script src="{{ URL::asset('/public/vendor/scripts/moment.min.js')}}"></script>
        <script src="{{ URL::asset('/public/vendor/scripts/daterangepicker.min.js')}}"></script>
		<!--for highcharts-->
		<script src="{{ URL::asset('/public/vendor/scripts/highcharts.js')}}"></script>
		<!--for chart.js-->
		<script src="{{ URL::asset('/public/vendor/scripts/utils.js')}}"></script>
        <script src="{{ URL::asset('/public/vendor/scripts/Chart.min.js')}}"></script>
		<script src="{{ URL::asset('/public/vendor/scripts/chartjs-plugin-trendline.js')}}"></script>
        <script src="{{ URL::asset('/public/vendor/scripts/jquery.validate.min.js')}}"></script>
        <!-- script for filr input -agency logo  -->
        <script src="{{URL::asset('/public/vendor/scripts/fileinput.js?v='.time())}}" type="text/javascript"></script>
        <script src="{{URL::asset('/public/vendor/scripts/fileinput-fa-theme.js?v='.time())}}" type="text/javascript"></script>
        <!-- script for filr input -agency logo  -->
        <script type="text/javascript" src="{{URL::asset('/public/vendor/scripts/toastr.js')}}"></script>
        <!--multiselect-->
        <script src="{{URL::asset('/public/vendor/scripts/bootstrap-multiselect.js')}}" type="text/javascript"></script>
        <script src="{{URL::asset('/public/vendor/scripts/select2.min.js')}}" type="text/javascript"></script>
        
        <script src="{{ URL::asset('/public/vendor/scripts/jquery.mCustomScrollbar.concat.min.js')}}"></script>  
        <!--tags input (settings page)-->
        <script src="{{ URL::asset('/public/vendor/scripts/selectize.js')}}"></script>  
        <script src="{{ URL::asset('/public/vendor/scripts/index.js')}}"></script>  

        <!--datatable export library-->
        <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
        <!--datatable export library-->
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyfg5RfXsDreSKWq7-P-VjfW7d2-abe8c
&libraries=places"></script>


        <!-- developer scripts -->
        @if(!Request::is('archived-projects'))
    		<script src="{{URL::asset('/public/vendor/scripts/custom.js?v='.time())}}" type="text/javascript"></script>
        @endif
         @if(! Request::is('dashboard') && ! Request::is('dashboard/*') && ! Request::is('archived-projects') && !Request::is('account-settings'))
        <script src="{{URL::asset('/public/vendor/scripts/dashboard.js?v='.time())}}" type="text/javascript"></script>
        @endif
        @if(Request::is('authorization'))
            <link rel="stylesheet" type="text/css" href="https://www.jqueryscript.net/demo/Bootstrap-4-Tag-Input-Plugin-jQuery/tagsinput.css">
            <script type="text/javascript" src="https://www.jqueryscript.net/demo/Bootstrap-4-Tag-Input-Plugin-jQuery/tagsinput.js"></script>
            <script src="{{URL::asset('/public/vendor/scripts/auth_custom.js?v='.time())}}" type="text/javascript"></script>
        @endif

         @if(Request::is('account-settings'))
        <script src="{{URL::asset('/public/vendor/scripts/account_settings.js?v='.time())}}" type="text/javascript"></script>
        @endif
		
		
		
		@if(Request::is('ppc-dashboard/*'))
			@include('vendor.ppc_page_scripts')
		@endif
		
		@if(Request::is('campaigndetail/*'))
			@include('vendor.seo_page_chart_scripts')
		@endif
		
		@if(Request::is('dashboard') || Request::is('dashboard/*'))
			@include('vendor.dashboard_script')
		@endif

        @if(Request::is('campaign-settings/*'))
            @include('vendor.settings_pagescript')
        @endif


        @if(Request::is('settings'))
            @include('vendor.settings.pagescript')
        @endif

         @if(Request::is('authorization'))
            @include('vendor.authorization.pagescript')
        @endif

         @if(Request::is('archived-projects'))
            @include('vendor.pagescripts.archieved_projects')
        @endif

        @include('includes.vendor_popup_modals')
    </body>
</html>
