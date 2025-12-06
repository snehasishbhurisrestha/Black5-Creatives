@extends('layouts.app')

@section('title','Brand')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Brands</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Brands</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                {{-- <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Student-all" id="list-tab">List</a></li> --}}
                @can('Brand Create')
                <li class="nav-item"><a class="btn btn-info" href="{{ route('brand.create') }}"><i class="fa fa-plus"></i>Add New Brand</a></li>
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
                                        <th>Parent Brand</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Visibility</th>
                                        <th>Popular</th>
                                        <th>Special</th>
                                        <th>Show on Home</th>
                                        <th>Show on Menu</th>
                                        <th>Created At</th>
                                        @canany(['Brand Edit','Brand Delete'])
                                        <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($brands as $brand)
                                    <tr>
                                        <td class="text-wrap">{{ $loop->iteration }}</td>
                                        <td class="text-wrap">{{ $brand->name }}</td>
                                        <td class="text-wrap">{{ $brand->parent?->name }}</td>
                                        <td class="text-wrap">{!! $brand->description !!}</td>
                                        <td><img class="img-thumbnail rounded me-2" src="{{ $brand->getFirstMediaUrl('brand') }}" width="60" alt=""></td>
                                        <td>{!! check_status($brand->is_visible) !!}</td>
                                        <td>{!! check_status($brand->is_popular) !!}</td>
                                        <td>{!! check_status($brand->is_special) !!}</td>
                                        <td>{!! check_status($brand->is_home) !!}</td>
                                        <td>{!! check_status($brand->is_menu) !!}</td>
                                        <td class="text-wrap">{{ format_datetime($brand->created_at) }}</td>
                                        @canany(['Brand Edit','Brand Delete'])
                                        <td>
                                            @can('Brand Edit')
                                            <a class="btn btn-icon btn-sm" href="{{ route('brand.edit',$brand->id) }}" alt="edit"><i class="fa fa-edit"></i></a>
                                            @endcan
                                            @can('Brand Delete')
                                            <form action="{{ route('brand.destroy', $brand->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-icon btn-sm" onclick="return confirm('Are you sure?')" type="submit"><i class="fa fa-trash-o text-danger"></i></button>
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