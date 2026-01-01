@extends('layouts.app')

@section('title','Coupons')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/dropify/css/dropify.min.css') }}">
@endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Create Coupons</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('coupon.index') }}">Coupons</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Coupons</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item"><a class="btn btn-info" href="{{ route('coupon.index') }}"><i class="fa fa-arrow-left me-2"></i>Back</a></li>
            </ul>
        </div>
    </div>
</div>

<form action="{{ route('coupon.store') }}" method="post" enctype="multipart/form-data">
@csrf

<div class="section-body mt-4">
<div class="container-fluid">
<div class="row gy-4">

{{-- LEFT --}}
<div class="col-md-9">
<div class="card">
<div class="card-header">
    <h5 class="card-title mb-0">Coupons Details</h5>
</div>

<div class="card-body">
<div class="row gy-3">

    <div class="col-md-4 mb-3">
        <label class="form-label">Code</label>
        <input type="text" name="code" value="{{ old('code')}}" class="form-control" placeholder="Enter Coupon Code">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="type">Coupon Type</label>
        <select name="type" id="type" class="form-control" required>
            <option value="">Select Type</option>
            <option value="percentage">Percentage Discount</option>
            <option value="flat">Flat Discount</option>
            <option value="free_shipping">Free Shipping</option>
            <option value="bogo">Buy X Get Y</option>
            <option value="price_override">Today's Special Price</option>
        </select>
    </div>

    {{-- MAIN DISCOUNT --}}
    <div class="col-md-4 mb-3" id="discount_box" style="display:none;">
        <label class="form-label">Discount Value</label>
        <input type="number" name="value" class="form-control" placeholder="Enter Discount Value">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Minimum Purchase</label>
        <input type="number" name="minimum_purchase" class="form-control">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Start Date</label>
        <input type="datetime-local" name="start_date" class="form-control">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">End Date</label>
        <input type="datetime-local" name="end_date" class="form-control">
    </div>

</div>

<hr>

{{-- CONDITIONS --}}
<h6>Conditions</h6>

<div id="condition_section" style="display:none;">
<div class="row gy-3">

    <div class="col-md-4">
        <label class="form-label">Product Type</label>
        <select name="product_type" class="form-control">
            <option value="">Any</option>
            @foreach ($product_options as $product_option)
                <option value="{{ $product_option->slug ?? $product_option->variation_name }}">
                    {{ $product_option->variation_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Category</label>
        <select name="category" class="form-control">
            <option value="">Any</option>
            @foreach ($categorys as $category)
                <option value="{{ $category->slug }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Minimum Quantity</label>
        <input type="number" name="min_qty" class="form-control" placeholder="2 or 3">
    </div>

</div>
</div>

<hr>

<h6>Action</h6>

{{-- BOGO --}}
<div id="bogo_box" style="display:none;">
<div class="row gy-3">

    <div class="col-md-4">
        <label class="form-label">Buy Quantity</label>
        <input type="number" name="buy_qty" class="form-control" value="2">
    </div>

    <div class="col-md-4">
        <label class="form-label">Get Quantity</label>
        <input type="number" name="get_qty" class="form-control" value="1">
    </div>

    <div class="col-md-4">
        <label class="form-label">Free Product Type</label>
        <select name="free_product_type" class="form-control">
            <option value="">Same As Buy</option>
            <option value="hybrid">Hybrid</option>
            <option value="soft">Soft</option>
            <option value="hard">Hard</option>
        </select>
    </div>

</div>
</div>

{{-- PRICE OVERRIDE --}}
<div id="price_override_box" style="display:none;">
    <label class="form-label">Set Offer Price</label>
    <input type="number" name="override_price" class="form-control" placeholder="299">
</div>

{{-- FREE SHIPPING --}}
<div id="shipping_info" style="display:none;">
    <span class="badge bg-success mt-2">Free Shipping Will Be Applied</span>
</div>

</div>
</div>
</div>

{{-- RIGHT --}}
<div class="col-md-3">

<div class="card">
<div class="card-header"><h3 class="card-title">Offer Image</h3></div>
<div class="card-body">
    <input type="file" class="dropify" name="image">
    <label>Description</label>
    <textarea class="form-control" name="description" rows="4"></textarea>
</div>
</div>

<div class="card">
<div class="card-header">Publish</div>
<div class="card-body">
    <label>Usage Type</label>
    <select name="usage_type" class="form-control mb-3">
        <option value="one-time">One-Time</option>
        <option value="multiple">Multiple</option>
    </select>

    <label class="d-block mb-2">Active</label>
    <input type="radio" name="is_active" value="1" checked> Yes
    <input type="radio" name="is_active" value="0"> No

    <button type="submit" class="btn btn-info mt-3 w-100">Save</button>
</div>
</div>

</div>

</div>
</div>
</div>

</form>

@endsection


@section('script')
<script>
document.getElementById("type").addEventListener("change", function () {

    let type = this.value;

    document.getElementById("condition_section").style.display = "none";
    document.getElementById("discount_box").style.display = "none";
    document.getElementById("bogo_box").style.display = "none";
    document.getElementById("price_override_box").style.display = "none";
    document.getElementById("shipping_info").style.display = "none";

    if(type === "percentage" || type === "flat"){
        document.getElementById("discount_box").style.display = "block";
        document.getElementById("condition_section").style.display = "block";
    }

    if(type === "free_shipping"){
        document.getElementById("shipping_info").style.display = "block";
        document.getElementById("condition_section").style.display = "block";
    }

    if(type === "bogo"){
        document.getElementById("bogo_box").style.display = "block";
        document.getElementById("condition_section").style.display = "block";
    }

    if(type === "price_override"){
        document.getElementById("price_override_box").style.display = "block";
        document.getElementById("condition_section").style.display = "block";
    }

});
</script>
@endsection
