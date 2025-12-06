@extends('layouts.web-app')

@section('title') About Us @endsection

@section('content')
<!-- Page item Area -->
<section class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <h4>About Us</h4>
                    <div class="breadcrumb-links">
                        <a href="{{ route('home') }}">Home</a>
                        <span>About Us</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Page -->

<section class="about spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="about-pic">
                    <img src="{{ asset('assets/site-assets/img/about/about-us.jpg') }}" alt="">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="about-item">
                    <h4>Who We Are</h4>
                    <p>We’re a team of trendsetters, stylists, and creatives passionate about redefining the online fashion experience. From casual everyday looks to runway-inspired pieces, we bring style straight to your doorstep.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="about-item">
                    <h4>What We Do</h4>
                    <p>We curate and deliver fashion that empowers confidence. From sourcing premium fabrics to crafting unique designs, we ensure every piece tells a story — your story in style.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="about-item">
                    <h4>Why Choose Us</h4>
                    <p>We blend quality, trend, and customer care into every order. With seamless shopping, fast delivery, and responsive support, we’re not just selling fashion — we’re creating an experience you'll love.</p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection