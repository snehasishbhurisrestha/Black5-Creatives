{{-- <header id="header_area">
    <div class="header_top_area">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-sm-12 col-md-3"> 
                    <a class="logo" href="{{ route('home') }}"> <img alt="" src="{{ asset('assets/site-assets/img/logo.png') }}"></a> 
                </div><!--  End Col -->

                <div class="col-xs-6 col-sm-6">
                    <div class="search_warp">
                        <form method="GET" action="{{ route('search') }}" class="form-inline">
                            <input type="text" name="query" id="search-box" placeholder="Search products, brands, categories..." class="form-control">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            <div id="suggestions-box" class="suggestions"></div>
                        </form>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-3"> 
                    <div class="right_menu">
                        <ul class="nav justify-content-end">
                            <li>
                                <div class="cart_menu_area hres">
                                    <div class="cart_icon">
                                        <a href="{{ route('cart') }}"><i class="fa fa-shopping-bag " aria-hidden="true"></i></a>
                                        <span class="cart_number" id="cart-count"></span>
                                    </div>
                                    
                                    
                                    <!-- Mini Cart Wrapper -->
                                    <div class="mini-cart-wrapper">
                                        <!-- Product List -->
                                        <div class="mc-pro-list fix">
                                            @foreach(get_cart_items() as $cart_helper_item)
                                            <div class="mc-sin-pro fix">
                                                <a href="{{ route('product.details',$cart_helper_item->product?->slug) }}" class="mc-pro-image float-left"><img src="{{ getProductMainImage($cart_helper_item->product?->id) }}" width="49" height="64" alt="" /></a>
                                                <div class="mc-pro-details fix">
                                                    <a href="{{ route('product.details',$cart_helper_item->product?->slug) }}">{{ $cart_helper_item->product?->name }}</a>
                                                    <span>{{ $cart_helper_item->quantity }}xRs {{ $cart_helper_item->product?->total_price }}</span>
                                                    <a class="pro-del" href="#"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <!-- Sub Total -->
                                        <div class="mc-subtotal fix">
                                            <h4>Subtotal <span id="cart-total">Rs 0.00</span></h4>												
                                        </div>
                                        <!-- Cart Button -->
                                        <div class="mc-button">
                                            <a href="#" class="checkout_btn">checkout</a>
                                        </div>
                                    </div>											
                                </div>	
                                
                            </li>
                        </ul>
                    </div>		
                </div>
                
            </div>
        </div>
    </div> <!--  HEADER START  -->
    
    <div class="header_btm_area">
        <div class="container">
            <div class="row">		
                <div class="col-xs-12 col-sm-12 col-md-9 text-left">
                    <div class="menu_wrap">
                        <div class="main-menu">
                            <nav>
                                <ul>
                                    <li><a href="{{ route('home') }}">Home</a></li>		
                                    @if(get_menu_categories()->isNotEmpty())    
                                    <li>
                                        <a href="{{ route('categories.all') }}">Categories <i class="fa fa-angle-down"></i></a>
                                        <!-- Mega Menu -->
                                        <div class="mega-menu mm-4-column mm-left">
                                            @foreach(get_menu_categories() as $category)
                                            <div class="mm-column mm-column-link float-left">
                                                <a href="{{ route('categories.products',$category->slug) }}"><h3>{{ $category->name }}</h3></a>
                                                @foreach($category->children as $child_cata)
                                                <a href="{{ route('categories.products',$child_cata->slug) }}">{{ $child_cata->name }}</a>
                                                @endforeach											
                                            </div>
                                            @endforeach
                                        </div>
                                    </li>
                                    @endif

                                    @if(get_visible_brands()->isNotEmpty())                                                                          
                                    <li>
                                        <a href="{{ route('brands.all') }}">Shop By Brand<i class="fa fa-angle-down"></i></a>
                                        <!-- Sub Menu -->
                                        <ul class="sub-menu">
                                            @foreach(get_visible_brands() as $header_brand_item)
                                            <li><a href="{{ route('brands.products',$header_brand_item->slug) }}">{{ $header_brand_item->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    @endif

                                    @if(get_special_categories()->isNotEmpty())    
                                    @foreach(get_special_categories() as $header_special_categorie)                                                                            
                                    <li>
                                        <a href="{{ route('categories.products',$header_special_categorie->slug) }}">
                                            {{ $header_special_categorie->name }} 
                                            @if($header_special_categorie->children->isNotEmpty())
                                            <i class="fa fa-angle-down"></i>
                                            @endif
                                        </a>
                                        <!-- Sub Menu -->
                                        @if($header_special_categorie->children->isNotEmpty())
                                        <ul class="sub-menu">
                                            @foreach($header_special_categorie->children as $child_cata)
                                            <li><a href="{{ route('categories.products',$child_cata->slug) }}">{{ $child_cata->name }}</a></li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </li>
                                    @endforeach
                                    @endif
                                    
                                    <li><a href="{{ route('product.all') }}">Shop</a></li>
                                    <li><a href="{{ route('about') }}">About</a></li>
                                    <li><a href="{{ route('contact') }}">Contact</a></li>
                                </ul>
                            </nav>
                        </div> <!--  End Main Menu -->					
                        <div class="mobile-menu text-right ">
                            <nav>
                                <ul>
                                    <li><a href="{{ route('home') }}">home</a></li>																		
                                    <li>
                                        <a href="{{ route('categories.all') }}">Categories</a>
                                        <!-- Sub Menu -->
                                        <ul>
                                            @foreach(get_menu_categories() as $category)
                                            <li><a href="{{ route('categories.products',$category->slug) }}">{{ $category->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                            
                                    @foreach(get_menu_categories() as $category)
                                    <li>
                                        <a href="{{ route('categories.products',$category->slug) }}">{{ $category->name }}</a>																		
                                        <ul>
                                            @foreach($category->children as $child_cata)
                                            <li><a href="{{ route('categories.products',$child_cata->slug) }}">{{ $child_cata->name }}</a></li>
                                            @endforeach
                                        </ul>																			
                                    </li>
                                    @endforeach
                                    <li><a href="{{ route('contact') }}">contact</a></li>
                                </ul>
                            </nav>
                        </div> <!--  End mobile-menu -->
                        
                                        
                    </div>
                </div><!--  End Col -->		
                <div class="col-xs-12 col-sm-12 col-md-3 text-left">
                    <div class="right_menu">
                        <ul class="hdr_tp_right text-right">
                            <li class="lan_area"><a href="javascript:void(0);"><i class="fa fa-lock"></i>  My Account <i class="fa fa-caret-down"></i></a>
                                <ul class="csub-menu">
                                    @auth
                                    <li><a href="{{ route('user-dashboard.profile') }}">Profile</a></li>
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <li>
                                            <a href="{{ route('logout') }}" 
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                                Logout
                                            </a>
                                        </li>
                                    </form>
                                    
                                    @else
                                    <li><a href="{{ route('login') }}">Login</a></li>
                                    <li><a href="{{ route('register') }}">Register</a></li>
                                    @endif
                                </ul>
                            </li>
                            <li class="account_area"><a href="{{ route('wishlist.index') }}"><i class="fa fa-heart-o"></i> Wishlist</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header> --}}



<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-7">
                    <div class="header-top-left">
                        <p>Free shipping, 30-day return or refund guarantee.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-5">
                    <div class="header-top-right">
                        <div class="header-top-links">
                            @auth
                            <a href="{{ route('user-dashboard.profile') }}">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </a>
                            </form>
                            
                            @else
                            <a href="{{ route('login') }}">Sign in</a>
                            @endauth
                            <a href="#">FAQs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-3">
                <div class="header-logo">
                    <a href="{{ route('home') }}"><img src="{{ asset('assets/site-assets/img/logo.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <nav class="header-menu mobile-menu">
                    <ul>
                        <li @if( request()->segment(1) == '' ) class="active" @endif><a href="{{ route('home') }}">Home</a></li>
                        <li @if( request()->segment(1) == 'products' ) class="active" @endif><a href="{{ route('product.all') }}">Shop</a></li>
                        <li @if( request()->segment(1) == 'about' ) class="active" @endif><a href="{{ route('about') }}">About Us</a></li>
                        <li @if( request()->segment(1) == 'contact' ) class="active" @endif><a href="{{ route('contact') }}">Contacts</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="header-nav-option">
                    <a href="#" class="search-switch"><img src="{{ asset('assets/site-assets/img/icon/search.png') }}" alt=""></a>
                    <a href="{{ route('wishlist.index') }}"><img src="{{ asset('assets/site-assets/img/icon/heart.png') }}" alt=""></a>
                    <a href="{{ route('cart') }}">
                        <img src="{{ asset('assets/site-assets/img/icon/cart.png') }}" alt=""> 
                        <span id="cart-count">0</span>
                        <span id="cart-indicator" class="blinking-dot d-none"></span>
                    </a>
                    <div class="price" id="cart-subtotal">₹0.00</div>
                </div>
            </div>
        </div>
        <div class="canvas-open"><i class="fa fa-bars"></i></div>
    </div>
</header>