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
        <link rel="shortcut icon" href="{{ asset('/public/front/images/fav-icon.png') }}">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <input type="hidden" class="baseUrl" value="{{url('/')}}" />

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
<link href="{{ URL::asset('/public/admin/css/main.css?v='.time())}}" rel="stylesheet"></head>
<!--<link href="{{ URL::asset('/public/admin/css/main.css?v='.time())}}" rel="stylesheet"></head>-->

<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ URL::asset('/public/admin/css/custom.css?v='.time())}}" rel="stylesheet"></head>

<!-- <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet"> -->


<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        @include('includes.admin_header')

        <div class="app-main">
            @include('includes.admin_sidebar')
            <div class="app-main__outer">
                <div class="app-main__inner">
                    @include('includes.admin_breadcrumb')

                    @yield('content')
                </div>

            </div>
        </div>
        @include('includes.admin_drawer')
        <div class="app-drawer-overlay fadeIn d-none animated"></div>
        <script type="text/javascript" src="{{ URL::asset('/public/admin/scripts/main.js?v='.time())}}"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        <script src="{{URL::asset('/public/vendor/scripts/toastr.js')}}"></script>
        <script src="https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script>
        <script src="{{ URL::asset('/public/admin/scripts/custom.js?v='.time())}}"></script>
        @if(Request::is('admin/packages') || Request::is('admin/packages/*'))
        <script src="{{ URL::asset('/public/admin/scripts/package.js?v='.time())}}"></script>
        @endif
        @if(Request::is('admin/transactions') || Request::is('admin/transactions/*'))
        <script src="{{ URL::asset('/public/admin/scripts/transactions.js?v='.time())}}"></script>
        @endif
        
        @include('includes.admin_popup_modals')

        @if(Request::is('admin/clients') && Request::is('admin/clients/*'))
            @include('admin.pagescripts.client')
        @endif
        @if(Request::is('admin/super-user'))
            @include('admin.pagescripts.super_user')
        @endif



    </body>
    </html>
