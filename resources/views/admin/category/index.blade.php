@extends('layouts.app')

@section('title','Category')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Category</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Category</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                {{-- <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Student-all" id="list-tab">List</a></li> --}}
                @can('Category Create')
                <li class="nav-item"><a class="btn btn-info" href="{{ route('category.create') }}"><i class="fa fa-plus"></i>Add New Category</a></li>
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
                                        <th>Parent Category</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Visibility</th>
                                        <th>Popular</th>
                                        <th>Special</th>
                                        <th>Show on Home</th>
                                        <th>Show on Menu</th>
                                        <th>Created At</th>
                                        @canany(['Category Edit','Category Delete'])
                                        <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categorys as $category)
                                    <tr>
                                        <td class="text-wrap">{{ $loop->iteration }}</td>
                                        <td class="text-wrap">{{ $category->name }}</td>
                                        <td class="text-wrap">{{ $category->parent?->name }}</td>
                                        <td class="text-wrap">{!! $category->description !!}</td>
                                        <td><img class="img-thumbnail rounded me-2" src="{{ $category->getFirstMediaUrl('category') }}" width="60" alt=""></td>
                                        <td>{!! check_status($category->is_visible) !!}</td>
                                        <td>{!! check_status($category->is_popular) !!}</td>
                                        <td>{!! check_status($category->is_special) !!}</td>
                                        <td>{!! check_status($category->is_home) !!}</td>
                                        <td>{!! check_status($category->is_menu) !!}</td>
                                        <td class="text-wrap">{{ format_datetime($category->created_at) }}</td>
                                        @canany(['Category Edit','Category Delete'])
                                        <td>
                                            @can('Category Edit')
                                            <a class="btn btn-icon btn-sm" href="{{ route('category.edit',$category->id) }}" alt="edit"><i class="fa fa-edit"></i></a>
                                            @endcan
                                            @can('Category Delete')
                                            @if(env('PHONE_CASE_ID') != $category->id && env('WALL_ART_ID') != $category->id)
                                            <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-icon btn-sm" onclick="return confirm('Are you sure?')" type="submit"><i class="fa fa-trash-o text-danger"></i></button>
                                            </form>
                                            @endif
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