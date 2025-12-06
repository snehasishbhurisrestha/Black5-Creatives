<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">

    <link rel="icon" href="favicon.ico" type="image/x-icon"/>
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap Core and vandor -->
    <link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/bootstrap/css/bootstrap.min.css') }}" />
    
    <link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/sweetalert/sweetalert.css') }}">
    <!-- Plugins css -->
    <link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/summernote/dist/summernote.css') }}"/>


    <!-- Core css -->
    <link rel="stylesheet" href="{{ asset('assets/admin-assets/css/style.min.css') }}"/>

    <!-- Toast message -->
    <link href="{{ asset('assets/admin-assets/plugins/toast/toastr.css') }}" rel="stylesheet" type="text/css" />
    <!-- Toast message -->

    <!-- Select2 -->
    <link href="{{ asset('assets/admin-assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Select2 -->


    @yield('style')
</head>

<body class="font-muli theme-cyan gradient offcanvas-active">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
        </div>
    </div>

    <div id="main_content">
        <!-- Start Main top header -->
        @include('layouts.admin-include.main-top-header')

        <!-- Start Rightbar setting panel -->
        @include('layouts.admin-include.rightbar-setting-panel')

        <!-- Start Main leftbar navigation -->
        @include('layouts.admin-include.left-side-bar')
        <!-- Start project content area -->

        <div class="page">
            <!-- Start Page header -->
            @include('layouts.admin-include.page-top')
            <!-- Start Page title and tab -->

            @yield('content')

            @isset($slot)
                {{ $slot }}
            @endisset

            @include('layouts.admin-include.footer')
        </div>    
    </div>

    <!-- Start Main project js, jQuery, Bootstrap -->
    <script src="{{ asset('assets/admin-assets/bundles/lib.vendor.bundle.js') }}"></script>

    <!-- Start all plugin js -->
    <script src="{{ asset('assets/admin-assets/bundles/counterup.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin-assets/bundles/apexcharts.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin-assets/bundles/summernote.bundle.js') }}"></script>

    <!-- Start project main js and page js -->
    <script src="{{ asset('assets/admin-assets/js/core.js') }}"></script>
    <script src="{{ asset('assets/admin-assets/page-assets/js/page/index.js') }}"></script>
    <script src="{{ asset('assets/admin-assets/page-assets/js/page/summernote.js') }}"></script>

    <!-- toast message -->
    <script src="{{ asset('assets/admin-assets/plugins/toast/toastr.js') }}"></script>
    <script src="{{ asset('assets/admin-assets/js/toastr.init.js') }}"></script>
    <!-- toast message -->
    @include('layouts._massages')

    <!-- Plugin Js-->

    <!-- Start Plugin Js -->
    <script src="{{ asset('assets/admin-assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin-assets/bundles/dataTables.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin-assets/plugins/sweetalert/sweetalert.min.js') }}"></script>

    <script src="{{ asset('assets/admin-assets/page-assets/js/page/dialogs.js') }}"></script>
    <script src="{{ asset('assets/admin-assets/page-assets/js/table/datatable.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('assets/admin-assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin-assets/js/form-advanced.init.js') }}"></script>
    <!-- Select2 -->

    @yield('script')
</body>
</html>
