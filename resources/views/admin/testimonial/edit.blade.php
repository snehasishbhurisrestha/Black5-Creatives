@extends('layouts.app')

@section('title','Testimonial')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/dropify/css/dropify.min.css') }}">
<style>
    .star-rating {
        direction: rtl;
        display: inline-block;
    }

    .star-rating input[type="radio"] {
        display: none;
    }

    .star-rating label {
        font-size: 30px;
        color: #ccc;
        padding: 0 5px;
        cursor: pointer;
    }

    .star-rating input:checked ~ label {
        color: gold;
    }

    .star-rating input[type="radio"]:checked + label {
        color: gold;
    }

</style>
@endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Testimonial</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('testimonial.index') }}">Testimonial</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Testimonial</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item"><a class="btn btn-info" href="{{ route('testimonial.index') }}"><i class="fa fa-arrow-left me-2"></i>Back</a></li>
            </ul>
        </div>
    </div>
</div>

<form action="{{ route('testimonial.update',$testimonial->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="section-body mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">testimonial Information</h3>
                            <div class="card-options ">
                                {{-- <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Name <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Enter testimonial name" name="name" value="{{ $testimonial->name }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Address</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Enter address" name="address" value="{{ $testimonial->address }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Message</label>
                                <div class="col-md-9">
                                    <textarea rows="4" class="form-control no-resize summernote" placeholder="Enter Message" name="message">{{ $testimonial->message }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">testimonial Image</h3>
                            <div class="card-options ">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="clearfix">
                                <div class="form-group">
                                    <input type="file" class="dropify" name="image" data-default-file="{{ $testimonial->getFirstMediaUrl('testimonial') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Save & Publish</h3>
                            <div class="card-options ">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row clearfix">
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label mb-3 d-flex">Visibility</label>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_visible1" name="is_visible" class="form-check-input" value="1" {{ check_uncheck($testimonial->is_visible,1) }}>
                                        <label class="form-check-label" for="is_visible1">Show</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_visible2" name="is_visible" class="form-check-input" value="0" {{ check_uncheck($testimonial->is_visible,0) }}>
                                        <label class="form-check-label" for="is_visible2">Hide</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">Rating</label>
                                    <div class="">
                                        {{-- <input type="hidden" class="rating" data-filled="mdi mdi-star text-primary" data-empty="mdi mdi-star-outline text-muted"/> --}}
                                        <div class="star-rating">
                                            <input type="radio" name="rating" id="star5" value="5" {{ $testimonial->rating == 5 ? 'checked' : '' }}><label for="star5" class="star">&#9733;</label>
                                            <input type="radio" name="rating" id="star4" value="4" {{ $testimonial->rating == 4 ? 'checked' : '' }}><label for="star4" class="star">&#9733;</label>
                                            <input type="radio" name="rating" id="star3" value="3" {{ $testimonial->rating == 3 ? 'checked' : '' }}><label for="star3" class="star">&#9733;</label>
                                            <input type="radio" name="rating" id="star2" value="2" {{ $testimonial->rating == 2 ? 'checked' : '' }}><label for="star2" class="star">&#9733;</label>
                                            <input type="radio" name="rating" id="star1" value="1" {{ $testimonial->rating == 1 ? 'checked' : '' }}><label for="star1" class="star">&#9733;</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                                </div>
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
@endsection