@extends('layouts.app')

@section('title','Products')

@section('content') 

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Products</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Basic Info</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item"><a class="btn btn-info" href="{{ route('product.index') }}"><i class="fa fa-arrow-left me-2"></i>Back</a></li>
            </ul>
        </div>
    </div>
</div>

<form action="{{ route('products.add-basic-edit-info') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="section-body mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- Nav tabs -->
                            @include('admin.products.nav-tabs-edit')
                            <input type="hidden" name="product_id" value="{{ request()->segment(4) }}">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active p-3" id="basicinfo" role="tabpanel">
                                    <div class="card-body">
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Name</label>
                                            <div>
                                                <input data-parsley-type="text" value="{{ $product->name }}" type="text" class="form-control" required placeholder="Enter Product Title" name="product_name">
                                            </div>
                                        </div>
                                        {{-- <div class="col-12 mb-3">
                                            <label class="form-label" for="brand">Brand</label>
                                            <div>
                                                <select class="form-control" id="brand" name="brand">
                                                    <option value="" selected disabled>Select Brand ...</option>
                                                    @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" @if($brand->id == $product->brand_id) selected @endif>{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Sort Description</label>
                                            <textarea class="form-control no-resize summernote" name="sort_description" id="">{{ $product->sort_description }}</textarea>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label">Long Description</label>
                                            <textarea class="form-control no-resize summernote" name="long_description" id="">{{ $product->long_description }}</textarea>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label">Meta Title</label>
                                            <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title', $product->meta_title ?? '') }}">
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label">Meta Keywords</label>
                                            <input type="text" class="form-control" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords ?? '') }}">
                                            <small class="text-muted">Separate keywords with commas</small>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label">Meta Description</label>
                                            <textarea class="form-control no-resize" name="meta_description" rows="3">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Category</h3>
                            <div class="card-options ">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="category-tree" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                                @if (!empty($categorys))
                                    @foreach ($categorys as $category)
                                        <!-- Only display top-level categories -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}" {{ isset($selectedCategories) && in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category{{ $category->id }}"> {{ $category->name }} </label>
                                        </div>
                                        @include('admin.products.subcategory', [
                                            'subcategories' => $category->children,
                                            'parent_id' => $category->id,
                                            'margin' => 20,
                                            'selectedCategories' => isset($selectedCategories) ? $selectedCategories : [],
                                        ])
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Brands</h3>
                            <div class="card-options ">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="category-tree" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                                <!-- Select All Checkbox -->
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="selectAllBrands">
                                    <label class="form-check-label fw-bold" for="selectAllBrands"> Select All </label>
                                </div>
                                @if (!empty($brands))
                                    @foreach ($brands as $brand)
                                        <!-- Only display top-level brands -->
                                        <div class="form-check">
                                            <input class="form-check-input brand-checkbox" type="checkbox" name="brands[]" value="{{ $brand->id }}" id="brand{{ $brand->id }}" {{ isset($selectedBrands) && in_array($brand->id, $selectedBrands) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="brand{{ $brand->id }}"> {{ $brand->name }} </label>
                                        </div>
                                        @include('admin.products.subbrands', [
                                            'subbrands' => $brand->children,
                                            'parent_id' => $brand->id,
                                            'margin' => 20,
                                            'selectedBrands' => isset($selectedBrands) ? $selectedBrands : [],
                                        ])
                                    @endforeach
                                @endif
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
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Product Type</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="product_type1" name="product_type" class="form-check-input" value="simple" {{ check_uncheck($product->product_type,'simple') }}>
                                    <label class="form-check-label" for="product_type1">Simple</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="product_type2" name="product_type" class="form-check-input" value="attribute" {{ check_uncheck($product->product_type,'attribute') }}>
                                    <label class="form-check-label" for="product_type2">Attribute</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="product_type3" name="product_type" class="form-check-input" value="customise" {{ check_uncheck($product->product_type,'customise') }}>
                                    <label class="form-check-label" for="product_type3">Customise</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Special Product</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="special" name="is_special" class="form-check-input" value="1" {{ check_uncheck($product->is_special,1) }}>
                                    <label class="form-check-label" for="special">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="notspecial" name="is_special" class="form-check-input" value="0" {{ check_uncheck($product->is_special,0) }}>
                                    <label class="form-check-label" for="notspecial">No</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Featured Product</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="is_featured1" name="is_featured" class="form-check-input" value="1" {{ check_uncheck($product->is_featured,1) }}>
                                    <label class="form-check-label" for="is_featured1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="is_featured2" name="is_featured" class="form-check-input" value="0" {{ check_uncheck($product->is_featured,0) }}>
                                    <label class="form-check-label" for="is_featured2">No</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Show on Home</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="is_home1" name="is_home" class="form-check-input" value="1" {{ check_uncheck($product->is_home,1) }}>
                                    <label class="form-check-label" for="is_home1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="is_home2" name="is_home" class="form-check-input" value="0" {{ check_uncheck($product->is_home,0) }}>
                                    <label class="form-check-label" for="is_home2">No</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Visiblity</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="show" name="is_visible" class="form-check-input" value="1" {{ check_uncheck($product->is_visible,1) }}>
                                    <label class="form-check-label" for="show">Show</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="hide" name="is_visible" class="form-check-input" value="0" {{ check_uncheck($product->is_visible,0) }}>
                                    <label class="form-check-label" for="hide">Hide</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Best Selling Product</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="is_best_selling1" name="is_best_selling" class="form-check-input" value="1" {{ check_uncheck($product->is_best_selling,1) }}>
                                    <label class="form-check-label" for="is_best_selling1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="is_best_selling2" name="is_best_selling" class="form-check-input" value="0" {{ check_uncheck($product->is_best_selling,0) }}>
                                    <label class="form-check-label" for="is_best_selling2">No</label>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                        Save & Next
                                    </button>
                                    <button type="reset" class="btn btn-secondary waves-effect">
                                        Cancel
                                    </button>
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
<script>
    const selectAll = document.getElementById("selectAllBrands");
    const brandCheckboxes = document.querySelectorAll(".brand-checkbox");

    // Handle select all click
    selectAll.addEventListener("change", function() {
        let isChecked = this.checked;
        brandCheckboxes.forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    });

    // Handle individual checkbox click
    brandCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener("change", function() {
            if (document.querySelectorAll(".brand-checkbox:checked").length === brandCheckboxes.length) {
                selectAll.checked = true;   // all checked
            } else {
                selectAll.checked = false;  // not all checked
            }
        });
    });

    // Initialize state on page load
    if (document.querySelectorAll(".brand-checkbox:checked").length === brandCheckboxes.length && brandCheckboxes.length > 0) {
        selectAll.checked = true;
    }
</script>
@endsection