@extends('layouts.app')

@section('title', 'Edit Offer')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/dropify/css/dropify.min.css') }}">
@endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Offers</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('offers.index') }}">Offer</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Offer</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item">
                    <a class="btn btn-info" href="{{ route('offers.index') }}">
                        <i class="fa fa-arrow-left me-2"></i>Back
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<form action="{{ route('offers.update', $offer->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="section-body mt-4">
        <div class="container-fluid">
            <div class="row">
                {{-- LEFT SECTION --}}
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Offer Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Offer Name <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Enter Offer name" name="name" value="{{ old('name', $offer->name) }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Offer Type</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="type" required>
                                        <option disabled>-- Select Offer Type --</option>
                                        <option value="discount" {{ old('type', $offer->type) == 'discount' ? 'selected' : '' }}>Discount</option>
                                        <option value="bogo" {{ old('type', $offer->type) == 'bogo' ? 'selected' : '' }}>Buy One Get One</option>
                                        <option value="free_shipping" {{ old('type', $offer->type) == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                                        <option value="special" {{ old('type', $offer->type) == 'special' ? 'selected' : '' }}>Special Offer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Description</label>
                                <div class="col-md-9">
                                    <textarea class="form-control summernote" name="description" rows="4" placeholder="Enter offer details">{{ old('description', $offer->description) }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Benefits</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="benefits" rows="4" placeholder='e.g. {"discount": "10%", "min_order": "1000"}'>{{ old('benefits', json_encode($offer->benefits, JSON_PRETTY_PRINT)) }}</textarea>
                                    <small class="text-muted">You can enter JSON or a short text about offer benefits.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Select Products</label>
                                <div class="col-md-9">
                                    <select class="form-control select2" name="product_ids[]" multiple>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ in_array($product->id, old('product_ids', $offer->products->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Choose products that are part of this offer.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Start Time</label>
                                <div class="col-md-9">
                                    <input type="datetime-local" class="form-control" name="start_time" value="{{ old('start_time', $offer->start_time->format('Y-m-d\TH:i')) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">End Time</label>
                                <div class="col-md-9">
                                    <input type="datetime-local" class="form-control" name="end_time" value="{{ old('end_time', $offer->end_time->format('Y-m-d\TH:i')) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SECTION --}}
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Offer Image</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <input type="file" class="dropify" name="image"
                                       data-allowed-file-extensions="jpg jpeg png webp"
                                       data-default-file="{{ $offer->image ? asset('storage/'.$offer->image) : '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Publish Settings</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label d-flex">Status</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status_active" name="status" class="form-check-input" value="1" {{ old('status', $offer->status) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status_inactive" name="status" class="form-check-input" value="0" {{ old('status', $offer->status) == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_inactive">Inactive</label>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Update Offer</button>
                                <a href="{{ route('offers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>

@endsection

@section('script')
<script src="{{ asset('assets/admin-assets/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/admin-assets/page-assets/js/form/dropify.js') }}"></script>
<script>
    $('.select2').select2({
        placeholder: 'Select products...',
        allowClear: true
    });
</script>
@endsection
