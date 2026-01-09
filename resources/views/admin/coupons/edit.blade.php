@extends('layouts.app')

@section('title','Edit Coupon')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/dropify/css/dropify.min.css') }}">
@endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Edit Coupon</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('coupon.index') }}">Coupons</a></li>
                    <li class="breadcrumb-item active">Edit Coupon</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item">
                    <a class="btn btn-info" href="{{ route('coupon.index') }}">
                        <i class="fa fa-arrow-left me-2"></i>Back
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<form action="{{ route('coupon.update', $coupon->id) }}" method="post" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="section-body mt-4">
<div class="container-fluid">
<div class="row gy-4">

{{-- LEFT --}}
<div class="col-md-9">
<div class="card">
<div class="card-header">
    <h5 class="card-title mb-0">Coupon Details</h5>
</div>

<div class="card-body">
<div class="row gy-3">

<div class="col-md-4">
    <label class="form-label">Code</label>
    <input type="text" name="code" value="{{ old('code', $coupon->code) }}" class="form-control">
</div>

<div class="col-md-4">
    <label class="form-label">Coupon Type</label>
    <select name="type" id="type" class="form-control" required>
        <option value="">Select Type</option>
        <option value="percentage" {{ $coupon->type == 'percentage' ? 'selected' : '' }}>Percentage</option>
        <option value="flat" {{ $coupon->type == 'flat' ? 'selected' : '' }}>Flat</option>
        <option value="free_shipping" {{ $coupon->type == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
        <option value="bogo" {{ $coupon->type == 'bogo' ? 'selected' : '' }}>Buy X Get Y</option>
        <option value="price_override" {{ $coupon->type == 'price_override' ? 'selected' : '' }}>Special Price</option>
    </select>
</div>

<div class="col-md-4" id="discount_box">
    <label class="form-label">Discount Value</label>
    <input type="number" name="value" class="form-control" value="{{ old('value', $coupon->value) }}">
</div>

<div class="col-md-4">
    <label class="form-label">Minimum Purchase</label>
    <input type="number" name="minimum_purchase" class="form-control" value="{{ old('minimum_purchase', $coupon->minimum_purchase) }}">
</div>

<div class="col-md-4">
    <label class="form-label">Start Date</label>
    <input type="datetime-local" name="start_date" class="form-control"
           value="{{ old('start_date', optional($coupon->start_date)->format('Y-m-d\TH:i')) }}">
</div>

<div class="col-md-4">
    <label class="form-label">End Date</label>
    <input type="datetime-local" name="end_date" class="form-control"
           value="{{ old('end_date', optional($coupon->end_date)->format('Y-m-d\TH:i')) }}">
</div>

</div>

<hr>

<h6>Conditions</h6>

<div id="condition_section">
<div class="row gy-3">

<div class="col-md-4">
    <label>Product Type</label>
    <select name="product_type" class="form-control">
        <option value="">Any</option>
        @foreach ($product_options as $product_option)
            <option value="{{ $product_option->slug ?? $product_option->variation_name }}"
                {{ $coupon->product_type == ($product_option->slug ?? $product_option->variation_name) ? 'selected' : '' }}>
                {{ $product_option->variation_name }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label>Category</label>
    <select name="category" class="form-control">
        <option value="">Any</option>
        @foreach ($categorys as $category)
            <option value="{{ $category->slug }}" {{ $coupon->category == $category->slug ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label>Minimum Quantity</label>
    <input type="number" name="min_qty" class="form-control" value="{{ old('min_qty', $coupon->min_qty) }}">
</div>

</div>
</div>

<hr>

<h6>Action</h6>

<div id="bogo_box">
<div class="row gy-3">
    <div class="col-md-4">
        <label>Buy Qty</label>
        <input type="number" name="buy_qty" class="form-control" value="{{ $coupon->buy_qty }}">
    </div>
    <div class="col-md-4">
        <label>Get Qty</label>
        <input type="number" name="get_qty" class="form-control" value="{{ $coupon->get_qty }}">
    </div>
    <div class="col-md-4">
        <label>Free Product Type</label>
        <select name="free_product_type" class="form-control">
            <option value="">Same</option>
            <option value="hybrid" {{ $coupon->free_product_type == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
            <option value="soft" {{ $coupon->free_product_type == 'soft' ? 'selected' : '' }}>Soft</option>
            <option value="hard" {{ $coupon->free_product_type == 'hard' ? 'selected' : '' }}>Hard</option>
        </select>
    </div>
</div>
</div>

<div id="price_override_box">
    <label>Offer Price</label>
    <input type="number" name="override_price" class="form-control" value="{{ $coupon->override_price }}">
</div>

<div id="shipping_info">
    <span class="badge bg-success mt-2">Free Shipping Applied</span>
</div>

</div>
</div>
</div>

{{-- RIGHT --}}
<div class="col-md-3">

<div class="card">
<div class="card-header">Offer Image</div>
<div class="card-body">
    <input type="file" class="dropify" name="image"
           data-default-file="{{ $coupon->image ? asset($coupon->image) : '' }}">
    <label>Description</label>
    <textarea class="form-control" name="description" rows="4">{{ $coupon->description }}</textarea>
</div>
</div>

<div class="card">
<div class="card-header">Publish</div>
<div class="card-body">
    <label>Usage Type</label>
    <select name="usage_type" class="form-control mb-3">
        <option value="one-time" {{ $coupon->usage_type == 'one-time' ? 'selected' : '' }}>One-Time</option>
        <option value="multiple" {{ $coupon->usage_type == 'multiple' ? 'selected' : '' }}>Multiple</option>
    </select>

    <label>Active</label><br>
    <input type="radio" name="is_active" value="1" {{ $coupon->is_active == 1 ? 'checked' : '' }}> Yes
    <input type="radio" name="is_active" value="0" {{ $coupon->is_active == 0 ? 'checked' : '' }}> No

    <button type="submit" class="btn btn-info mt-3 w-100">Update</button>
</div>
</div>

</div>

</div>
</div>
</div>
</form>

@endsection
