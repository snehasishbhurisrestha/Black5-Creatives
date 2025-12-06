<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Developed By Snehasish Bhurisrestha">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="M7Prime">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site-assets/img/fab.png') }}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('assets/site-assets/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/site-assets/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/site-assets/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/site-assets/css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/site-assets/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/site-assets/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/site-assets/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/site-assets/css/style.css') }}" type="text/css">

    <!-- Toast message -->
    <link href="{{ asset('assets/admin-assets/plugins/toast/toastr.css') }}" rel="stylesheet" type="text/css" />
    <!-- Toast message -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        .cart-icon-wrapper {
            position: relative;
            display: inline-block;
        }

        .blinking-dot {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 10px;
            height: 10px;
            /* background-color: red; */
            border-radius: 50%;
            box-shadow: 0 0 0 rgba(12, 11, 11, 0.7);
            animation: pulse 1.5s infinite;
            z-index: 10;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(46, 32, 32, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(255, 0, 0, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 0, 0, 0);
            }
        }


    </style>
    @yield('style')
</head>
<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas-option">
            <div class="offcanvas-links">
                <a href="#">Sign in</a>
                <a href="#">FAQs</a>
            </div>
        </div>
        <div class="offcanvas-nav-option">
            <a href="#" class="search-switch"><img src="img/icon/search.png" alt=""></a>
            <a href="#"><img src="{{ asset('assets/site-assets/img/icon/heart.png') }}" alt=""></a>
            <a href="#"><img src="{{ asset('assets/site-assets/img/icon/cart.png') }}" alt=""> <span>0</span></a>
            <div class="price">₹0.00</div>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas-text">
            <p>Free shipping, 30-day return or refund guarantee.</p>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!--  Start Header  -->
    @include('layouts.site-include.header')
    <!--  End Header  -->
    
    @yield('content')
    
    <!--  FOOTER START  -->
    @include('layouts.site-include.footer')
    <!--  FOOTER END  -->

    <!-- Search Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Js Plugins -->
    <script src="{{ asset('assets/site-assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/site-assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/site-assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/site-assets/js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/site-assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/site-assets/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('assets/site-assets/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('assets/site-assets/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('assets/site-assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/site-assets/js/main.js') }}"></script>

    <!-- toast message -->
    <script src="{{ asset('assets/admin-assets/plugins/toast/toastr.js') }}"></script>
    <script src="{{ asset('assets/admin-assets/js/toastr.init.js') }}"></script>
    <!-- toast message -->
    @include('layouts._massages')
    @include('layouts.scripts.cart_script')
    @include('layouts.scripts.locations')

    <script>
        $(document).ready(function() {
            $('#search-box').on('keyup', function() {
                let query = $(this).val();
                // if (query.length > 2) {
                    $.ajax({
                        url: "{{ route('search.suggestions') }}",
                        method: "GET",
                        data: { query: query },
                        success: function(data) {
                            let suggestionHTML = '<ul>';
                            data.forEach(item => {
                                suggestionHTML += `<li class="suggestion-item" data-url="${item.url}">
                                                    <strong>${item.type ? item.type + ': ' : ''}</strong> ${item.name}
                                                </li>`;
                            });
                            suggestionHTML += '</ul>';
                            $('#suggestions-box').html(suggestionHTML).show();
                        }
                    });
                // } else {
                //     $('#suggestions-box').hide();
                // }
            });

            $(document).on('click', '.suggestion-item', function() {
                window.location.href = $(this).data('url'); // Redirect to the selected item's URL
            });

            $(document).click(function(e) {
                if (!$(e.target).closest('#search-box, #suggestions-box').length) {
                    $('#suggestions-box').hide();
                }
            });
        });
    </script>


    @yield('script')
</body>
</html>