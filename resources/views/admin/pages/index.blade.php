@extends('layouts.app')

@section('title','Pages')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Pages</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pages</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                {{-- <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Student-all" id="list-tab">List</a></li> --}}
                @can('Page Create')
                <li class="nav-item"><a class="btn btn-info" href="{{ route('page.create') }}"><i class="fa fa-plus"></i>Add New Page</a></li>
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
                                        <th>Page Name</th>
                                        <th>Page Slug</th>
                                        <th>Description</th>
                                        <th>Visibility</th>
                                        <th>Created At</th>
                                        @canany(['Page Edit','Page Delete'])
                                        <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pages as $page)
                                    <tr>
                                        <td class="text-wrap">{{ $loop->iteration }}</td>
                                        <td class="text-wrap">{{ $page->name }}</td>
                                        <td class="text-wrap">{{ $page->slug }}</td>
                                        <td class="text-wrap">{!! Str::limit(strip_tags($page->description), 100, '...') !!}</td>
                                        <td>{!! check_status($page->is_visible) !!}</td>
                                        <td class="text-wrap">{{ format_datetime($page->created_at) }}</td>
                                        @canany(['Page Edit','Page Delete'])
                                        <td>
                                            @can('Page Edit')
                                            <a class="btn btn-icon btn-sm" href="{{ route('page.edit',$page->id) }}" alt="edit"><i class="fa fa-edit"></i></a>
                                            @endcan
                                            @can('Page Delete')
                                            <form action="{{ route('page.destroy', $page->id) }}" method="POST" style="display:inline;">
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