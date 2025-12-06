{{-- <footer class="footer_area">
    <div class="container">
        <div class="row">	 
            <div class="col-md-5 col-sm-6">
                <div class="single_ftr">
                    <img src="{{ asset('assets/site-assets/img/logo-food.png') }}" alt="">
                    <div class="newsletter_form">
                        <p>Immerse yourself in the musical oasis of Gsm Musical, the 
                            unrivalled choice for KoIkata's music lovers.</p>
                        <div class="ftr_social_icon">
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-google"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> <!--  End Col -->
            
            <div class="col-md-2 col-sm-6">
                <div class="single_ftr">
                    <h4 class="sf_title">QUICK LINKS</h4>
                    <ul>
                        <li><a href="{{ route('brands.all') }}">Shop by brand </a></li>
                        <li><a href="#">Musical instrument </a></li>
                        <li><a href="#">Proffesional audio </a></li>
                        <li><a href="{{ route('contact') }}">Support</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                    </ul>
                </div>
            </div> <!--  End Col -->
            
            <div class="col-md-2 col-sm-6">
                <div class="single_ftr">
                    <h4 class="sf_title">CUSTOMER CARE</h4>
                    <ul>
                        <li><a href="{{ route('product.all') }}">Shop</a></li>
                        <li><a href="{{ route('cart') }}">Cart</a></li>
                        <li><a href="#">Orders</a></li>
                        <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                        <li><a href="#">Order Tracking</a></li>
                    </ul>
                </div>
            </div> <!--  End Col -->	

            <div class="col-md-3 col-sm-6">
                <div class="single_ftr">
                    <h4 class="sf_title">CALL</h4>
                    <ul>
                        <li>+91 70036 96900</li>
                    </ul>

                    <h4 class="sf_title">EMAIL</h4>
                    <ul>
                        <li>gsmmusicalkolkata@hotmail.com</li>
                    </ul>

                    <h4 class="sf_title">ADDRESS</h4>
                    <ul>
                        <li>Kavi Nazrul Sarani, Opposite Asma Dhaba, 
                            Baruipur, Kolkata, India, 700144</li>
                    </ul>
                </div>
            </div> <!--  End Col -->
        </div>
    </div>


    <div class="ftr_btm_area">
        <div class="container">
            <div class="row">
                
                <div class="col-sm-6">
                    <p class="copyright_text text-left">&copy; 2024 GSM MUSICAL.COM. All rights reserved.</p>
                </div>
                
                <div class="col-sm-6">
                    <div class="payment_mthd_icon text-right">
                        <ul>
                            <li><i class="fa fa-cc-paypal"></i></li>
                            <li><i class="fa fa-cc-visa"></i></li>
                            <li><i class="fa fa-cc-discover"></i></li>
                            <li><i class="fa fa-cc-mastercard"></i></li>
                            <li><i class="fa fa-cc-amex"></i></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer> --}}


<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer-about">
                    <div class="footer-logo">
                        <a href="#"><img src="{{ asset('assets/site-assets/img/footer-logo.png') }}" alt=""></a>
                    </div>
                    <p>The customer is at the heart of our unique business model, which includes design.</p>
                    <a href="#"><img src="{{ asset('assets/site-assets/img/payment.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                <div class="footer-widget">
                    <h6>Shopping</h6>
                    <ul>
                        <li><a href="#">Clothing Store</a></li>
                        <li><a href="#">Trending Shoes</a></li>
                        <li><a href="#">Accessories</a></li>
                        <li><a href="#">Sale</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="footer-widget">
                    <h6>Shopping</h6>
                    <ul>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Payment Methods</a></li>
                        <li><a href="#">Delivary</a></li>
                        <li><a href="#">Return & Exchanges</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                <div class="footer-widget">
                    <h6>NewLetter</h6>
                    <div class="footer-newslatter">
                        <p>Be the first to know about new arrivals, look books, sales & promos!</p>
                        <form action="#">
                            <input type="text" placeholder="Your email">
                            <button type="submit"><span class="icon_mail_alt"></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="footer-copyright-text">
                    <p>Copyright ©
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        All rights reserved | This template is made with <i class="fa fa-heart-o"
                        aria-hidden="true"></i> by <a href="https://snehasishbhurisrestha.github.io/My-Portfolio/" target="_blank">Snehasish Bhurisrestha</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>