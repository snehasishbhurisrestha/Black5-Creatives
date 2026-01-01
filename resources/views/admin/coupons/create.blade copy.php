@extends('layouts.app')

@section('title','Coupons')

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
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Coupons Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row gy-3">
                                <div class="col-md-4">
                                    <label class="form-label">Code</label>
                                    <input type="text" name="code" value="{{ old('code')}}" class="form-control" placeholder="Enter Coupon Code">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="type">Discount Type</label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="percentage">Percentage</option>
                                        <option value="flat">Flat</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Discount Value</label>
                                    <input type="number" name="value" value="{{ old('value')}}" class="form-control" placeholder="Enter Discount Value">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Minimum Purchase</label>
                                    <input type="number" name="minimum_purchase" value="{{ old('minimum_purchase')}}" class="form-control" placeholder="Enter Minimum Purchase">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" value="{{ old('start_date')}}" class="form-control" placeholder="Enter Start Date">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" value="{{ old('end_date')}}" class="form-control" placeholder="Enter End Date">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">Publish</div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="usage_type" class="form-label">Usage Type</label>
                                <select name="usage_type" id="usage_type" class="form-control" required>
                                    <option value="one-time" selected>One-Time</option>
                                    <option value="multiple">Multiple</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Active</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline1" name="is_active" class="form-check-input" value="1" checked>
                                    <label class="form-check-label" for="customRadioInline1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline2" name="is_active" class="form-check-input" value="0">
                                    <label class="form-check-label" for="customRadioInline2">No</label>
                                </div>
                            </div>
                            <div class="md-3">
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </form>

@endsection