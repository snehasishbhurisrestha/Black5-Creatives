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
                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                {{-- <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Student-all" id="list-tab">List</a></li> --}}
                @can('Product Create')
                <li class="nav-item"><a class="btn btn-info" href="{{ route('products.basic-info-create') }}"><i class="fa fa-plus"></i>Add New Products</a></li>
                @endcan
            </ul>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">
        <div class="tab-content">
            <div class="tab-pane active" id="Student-all">
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-striped table_custom border-style spacing5">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sl.no</th>
                                        <th>Title</th>
                                        <th>Categories</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Image</th>
                                        <th>Visiblity</th>
                                        <th>Featured</th>
                                        <th>Show on Home</th>
                                        <th>Created At</th>
                                        @canany(['Product Edit','Product Delete'])
                                        <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($proucts as $prouct)
                                    <tr>
                                        <td class="text-wrap">{{ $loop->iteration }}</td>
                                        <td class="text-wrap">{{ $prouct->name }}</td>
                                        <td>
                                            @if($prouct->categories->count())
                                                {{ $prouct->categories->pluck('name')->join(', ') }}
                                            @else
                                                <span class="text-muted">No Category</span>
                                            @endif
                                        </td>
                                        <td class="text-wrap">{!! Str::limit(strip_tags($prouct->sort_description), 100, '...') !!}</td>
                                        <td class="text-wrap">
                                            @if($prouct->product_type == 'attribute')
                                                @foreach($prouct->variations as $pvariation)
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($prouct->variations as $pvariation)
                                                        <div class="border p-1 rounded text-center">
                                                            <strong>{{ $pvariation->name }}</strong>
                                                            @foreach($pvariation->options as $pvariationOption)
                                                                <span class="badge bg-primary">
                                                                    {{ $pvariationOption->variation_name }} ({{ $pvariationOption->price }})
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @endforeach
                                            @else
                                                {{ $prouct->total_price }}
                                            @endif
                                        </td>
                                        <td class="text-wrap">
                                            @if($prouct->product_type == 'attribute')
                                                @foreach($prouct->variations as $pvariation)
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($prouct->variations as $pvariation)
                                                        <div class="border p-1 rounded text-center">
                                                            <strong>{{ $pvariation->name }}</strong>
                                                            @foreach($pvariation->options as $pvariationOption)
                                                                <span class="badge bg-primary">
                                                                    {{ $pvariationOption->variation_name }} ({{ $pvariationOption->stock }})
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @endforeach
                                            @else
                                                {{ $prouct->stock }}
                                            @endif
                                        </td>
                                        <td><img class="img-thumbnail rounded me-2" src="{{ getProductMainImage($prouct->id) }}" width="100" alt=""></td>
                                        <td>{!! check_status($prouct->is_visible) !!}</td>
                                        <td>{!! check_status($prouct->is_featured) !!}</td>
                                        <td>{!! check_status($prouct->is_home) !!}</td>
                                        <td class="text-wrap">{{ format_datetime($prouct->created_at) }}</td>
                                        @canany(['Product Edit','Product Delete'])
                                        <td>
                                            @can('Product Edit')
                                            <a class="btn btn-icon btn-sm" href="{{ route('products.basic-info-edit',$prouct->id) }}" alt="edit"><i class="fa fa-edit"></i></a>
                                            @endcan
                                            @can('Product Delete')
                                            <form action="{{ route('products.delete', $prouct->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-icon btn-sm" onclick="return confirm('Are you sure?')" type="submit" class="btn">
                                                    <i class="fa fa-trash-o text-danger"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </td>
                                        @endcanany
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection