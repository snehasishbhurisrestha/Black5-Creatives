@extends('layouts.app')

@section('title','Slider')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Slider</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Slider</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                {{-- <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Student-all" id="list-tab">List</a></li> --}}
                @can('Slider Create')
                <li class="nav-item"><a class="btn btn-info" href="{{ route('slider.create') }}"><i class="fa fa-plus"></i>Add New Slider</a></li>
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
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Visibility</th>
                                        <th>Created At</th>
                                        @canany(['Slider Edit','Slider Delete'])
                                        <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sliders as $slider)
                                    <tr>
                                        <td class="text-wrap">{{ $loop->iteration }}</td>
                                        <td class="text-wrap">{{ $slider->title }}</td>
                                        <td class="text-wrap">{!! $slider->description !!}</td>
                                        <td><img class="img-thumbnail rounded me-2" src="{{ $slider->getFirstMediaUrl('slider') }}" width="60" alt=""></td>
                                        <td>{!! check_status($slider->is_visible) !!}</td>
                                        <td class="text-wrap">{{ format_datetime($slider->created_at) }}</td>
                                        @canany(['Slider Edit','Slider Delete'])
                                        <td>
                                            @can('Slider Edit')
                                            <a class="btn btn-icon btn-sm" href="{{ route('slider.edit',$slider->id) }}" alt="edit"><i class="fa fa-edit"></i></a>
                                            @endcan
                                            @can('Slider Delete')
                                            <form action="{{ route('slider.destroy', $slider->id) }}" method="POST" style="display:inline;">
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