@extends('layouts.app')

@section('title','Add FAQ')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/summernote/dist/summernote.css') }}">
@endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">FAQs</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('faqs.index') }}">FAQs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add FAQ</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item">
                    <a class="btn btn-info" href="{{ route('faqs.index') }}">
                        <i class="fa fa-arrow-left me-2"></i>Back
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<form action="{{ route('faqs.store') }}" method="post">
    @csrf
    <div class="section-body mt-4">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Section -->
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">FAQ Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Question <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="question" class="form-control" placeholder="Enter question" value="{{ old('question') }}" required>
                                    @error('question') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Answer <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <textarea name="answer" class="form-control summernote" rows="5" placeholder="Enter answer">{{ old('answer') }}</textarea>
                                    @error('answer') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <label class="col-md-3 col-form-label">Sort Order</label>
                                <div class="col-md-9">
                                    <input type="number" name="sort_order" class="form-control" placeholder="0" value="{{ old('sort_order', 0) }}">
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Save & Publish</h3>
                        </div>
                        <div class="card-body">
                            <div class="row clearfix">
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label mb-3 d-flex">Visibility</label>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_visible1" name="is_visible" class="form-check-input" value="1" checked>
                                        <label class="form-check-label" for="is_visible1">Show</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_visible2" name="is_visible" class="form-check-input" value="0">
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
                </div> <!-- end right section -->
            </div>
        </div>
    </div>
</form>

@endsection

@section('script')
<script src="{{ asset('assets/admin-assets/plugins/summernote/dist/summernote.min.js') }}"></script>
<script>
    $(function() {
        $('.summernote').summernote({
            height: 150,
        });
    });
</script>
@endsection
