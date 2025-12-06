@extends('layouts.app')

@section('title','Testimonial')

@section('style')
<style>
    .star {
        font-size: 20px;
        margin-right: 3px;
    }

    .star-rating {
        direction: rtl;
        display: inline-block;
    }

    .star-rating input[type="radio"] {
        display: none;
    }

    .star-rating label {
        font-size: 30px;
        color: #ccc;
        padding: 0 5px;
        cursor: pointer;
    }

    .star-rating input:checked ~ label {
        color: gold;
    }

    .star-rating input[type="radio"]:checked + label {
        color: gold;
    }

</style>
@endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Testimonials</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Testimonials</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                {{-- <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Student-all" id="list-tab">List</a></li> --}}
                @can('testimonial Create')
                <li class="nav-item"><a class="btn btn-info" href="{{ route('testimonial.create') }}"><i class="fa fa-plus"></i>Add New testimonial</a></li>
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
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Message</th>
                                        <th>Rating</th>
                                        <th>Image</th>
                                        <th>Visibility</th>
                                        <th>Created At</th>
                                        @canany(['Testimonial Edit','Testimonial Delete'])
                                        <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($testimonials as $testimonial)
                                    <tr>
                                        <td class="text-wrap">{{ $loop->iteration }}</td>
                                        <td class="text-wrap">{{ $testimonial->name }}</td>
                                        <td class="text-wrap">{{ $testimonial->address }}</td>
                                        <td class="text-wrap">{!! $testimonial->message !!}</td>
                                        <td>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span class="star" style="color: {{ $i <= $testimonial->rating ? '#FFD700' : '#ccc' }};">&#9733;</span>
                                                @endfor
                                                ({{ $testimonial->rating }})
                                        </td>
                                        <td><img class="img-thumbnail rounded me-2" src="{{ $testimonial->getFirstMediaUrl('testimonial') }}" width="60" alt=""></td>
                                        <td>{!! check_status($testimonial->is_visible) !!}</td>
                                        <td class="text-wrap">{{ format_datetime($testimonial->created_at) }}</td>
                                        @canany(['Testimonial Edit','Testimonial Delete'])
                                        <td>
                                            @can('Testimonial Edit')
                                            <a class="btn btn-icon btn-sm" href="{{ route('testimonial.edit',$testimonial->id) }}" alt="edit"><i class="fa fa-edit"></i></a>
                                            @endcan
                                            @can('Testimonial Delete')
                                            <form action="{{ route('testimonial.destroy', $testimonial->id) }}" method="POST" style="display:inline;">
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