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
                    <li class="breadcrumb-item active" aria-current="page">Edit Inventory</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item"><a class="btn btn-info" href="{{ route('product.index') }}"><i class="fa fa-arrow-left me-2"></i>Back</a></li>
            </ul>
        </div>
    </div>
</div>

<form action="{{ route('products.inventory-edit-process') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="section-body mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <!-- Nav tabs -->
                            @include('admin.products.nav-tabs-edit')
                            <input type="hidden" name="id" value="{{ request()->segment(4) }}">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active p-3" id="inventory" role="tabpanel">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">SKU</label>
                                            <div>
                                                <input data-parsley-type="text" type="text" value="{{ $product->sku }}" class="form-control" placeholder="Enter Product SKU" name="sku">
                                            </div>
                                        </div>
                                        @if($product->product_type == 'simple')
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Stock</label>
                                            <div>
                                                <input data-parsley-type="number" type="number" value="{{ $product->stock }}" class="form-control" required placeholder="Enter Stock" name="stock">
                                            </div>
                                        </div> 
                                        @endif

                                        @if($product->product_type == 'customise')
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Customise Image Need</label>
                                            <div>
                                                <input data-parsley-type="number" type="number" value="{{ $product->need_image }}" class="form-control" required placeholder="Enter number of image need" name="need_image">
                                            </div>
                                        </div> 
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Save & Publish</h3>
                            {{-- <div class="card-options ">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                            </div> --}}
                        </div>
                        <div class="card-body">
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