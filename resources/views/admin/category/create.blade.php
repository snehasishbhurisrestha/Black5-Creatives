@extends('layouts.app')

@section('title','Category')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/dropify/css/dropify.min.css') }}">
@endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Category</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Category</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Category</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item"><a class="btn btn-info" href="{{ route('category.index') }}"><i class="fa fa-arrow-left me-2"></i>Back</a></li>
            </ul>
        </div>
    </div>
</div>

<form action="{{ route('category.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="section-body mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Category Information</h3>
                            <div class="card-options ">
                                {{-- <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Name <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Enter category name" name="name" value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="parent_id">Parent Category <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <select class="form-control input-height" id="parent_id" name="parent_id">
                                        <option selected disabled value="">Choose parent category...</option>
                                        @foreach($categorys as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @foreach ($category->children as $child)
                                                <option value="{{ $child->id }}" @if(old('parent_id') == $child->id) selected @endif>
                                                    &nbsp;&nbsp;&nbsp;-- {{ $child->name }}
                                                </option>
                                                @foreach ($child->children as $childs)
                                                    <option value="{{ $childs->id }}" @if(old('parent_id') == $childs->id) selected @endif>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-- {{ $childs->name }}
                                                    </option>
                                                    @foreach ($childs->children as $subchilds)
                                                    <option value="{{ $subchilds->id }}" @if(old('parent_id') == $subchilds->id) selected @endif>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-- {{ $subchilds->name }}
                                                    </option>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Description</label>
                                <div class="col-md-9">
                                    <textarea rows="4" class="form-control no-resize summernote" placeholder="Enter Description" name="description">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Category Image</h3>
                            <div class="card-options ">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="clearfix">
                                <div class="form-group">
                                    <input type="file" class="dropify" name="image">
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
                                        <input type="radio" id="is_visible1" name="is_visible" class="form-check-input" value="1" checked>
                                        <label class="form-check-label" for="is_visible1">Show</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_visible2" name="is_visible" class="form-check-input" value="0">
                                        <label class="form-check-label" for="is_visible2">Hide</label>
                                    </div>
                                </div>

                                <div class="col-sm-12 mb-3">
                                    <label class="form-label mb-3 d-flex">Popular Category</label>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_popular1" name="is_popular" class="form-check-input" value="1">
                                        <label class="form-check-label" for="is_popular1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_popular2" name="is_popular" class="form-check-input" value="0" checked>
                                        <label class="form-check-label" for="is_popular2">No</label>
                                    </div>
                                </div>

                                <div class="col-sm-12 mb-3">
                                    <label class="form-label mb-3 d-flex">Special Category</label>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_special1" name="is_special" class="form-check-input" value="1">
                                        <label class="form-check-label" for="is_special1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_special2" name="is_special" class="form-check-input" value="0" checked>
                                        <label class="form-check-label" for="is_special2">No</label>
                                    </div>
                                </div>

                                <div class="col-sm-12 mb-3">
                                    <label class="form-label mb-3 d-flex">Show on Home Page</label>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_home1" name="is_home" class="form-check-input" value="1">
                                        <label class="form-check-label" for="is_home1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_home2" name="is_home" class="form-check-input" value="0" checked>
                                        <label class="form-check-label" for="is_home2">No</label>
                                    </div>
                                </div>

                                <div class="col-sm-12 mb-3">
                                    <label class="form-label mb-3 d-flex">Show on Menu</label>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_menu1" name="is_menu" class="form-check-input" value="1">
                                        <label class="form-check-label" for="is_menu1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="is_menu2" name="is_menu" class="form-check-input" value="0" checked>
                                        <label class="form-check-label" for="is_menu2">No</label>
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