@extends('layouts.app')

@section('title','Pages')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/dropify/css/dropify.min.css') }}">
@endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Pages</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('page.index') }}">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Pages</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item"><a class="btn btn-info" href="{{ route('page.index') }}"><i class="fa fa-arrow-left me-2"></i>Back</a></li>
            </ul>
        </div>
    </div>
</div>

<form action="{{ route('page.update',$page->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="section-body mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pages Information</h3>
                            <div class="card-options ">
                                {{-- <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Page Name <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Enter page name" name="name" value="{{ old('name',$page->name) }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Description</label>
                                <div class="col-md-9">
                                    <textarea rows="4" class="form-control no-resize summernote" placeholder="Enter Description" name="description">{{ old('description',$page->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Meta Title -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Meta Title</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Enter Meta Title" name="meta_title" value="{{ old('meta_title',$page->meta_title) }}">
                                </div>
                            </div>

                            <!-- Meta Keywords -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Meta Keywords</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Enter Meta Keywords" name="meta_keywords" value="{{ old('meta_keywords',$page->meta_keywords) }}">
                                </div>
                            </div>

                            <!-- Meta Description -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Meta Description</label>
                                <div class="col-md-9">
                                    <textarea rows="3" class="form-control" placeholder="Enter Meta Description" name="meta_description">{{ old('meta_description',$page->meta_description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
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
                                        <input type="radio" id="is_visible1" name="is_visible" class="form-check-input" value="1" {{ check_uncheck($page->is_visible,1) }}>
                                        <label class="form-check-label" for="is_visible1">Show</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_visible2" name="is_visible" class="form-check-input" value="0" {{ check_uncheck($page->is_visible,0) }}>
                                        <label class="form-check-label" for="is_visible2">Hide</label>
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