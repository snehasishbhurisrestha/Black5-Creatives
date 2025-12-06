@extends('layouts.app')

@section('title','Offers')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Offers</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Offers</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                @can('Offer Create')
                <li class="nav-item">
                    <a class="btn btn-info" href="{{ route('offers.create') }}">
                        <i class="fa fa-plus"></i> Add New Offer
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example dataTable table-striped table_custom border-style spacing5">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sl.no</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        @canany(['Offer Edit','Offer Delete'])
                                        <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offers as $offer)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $offer->name }}</td>
                                        <td><span class="badge bg-info">{{ ucfirst($offer->type) }}</span></td>
                                        <td class="text-wrap">{!! Str::limit($offer->description, 100) !!}</td>
                                        <td>
                                            @if($offer->image)
                                                <img class="img-thumbnail rounded me-2" src="{{ asset('storage/'.$offer->image) }}" width="60" alt="">
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </td>
                                        <td>{{ $offer->start_time->format('d M Y h:i A') }}</td>
                                        <td>{{ $offer->end_time->format('d M Y h:i A') }}</td>
                                        <td>
                                            @if($offer->isActive())
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $offer->created_at->format('d M Y h:i A') }}</td>

                                        @canany(['Offer Edit','Offer Delete'])
                                        <td>
                                            @can('Offer Edit')
                                            <a class="btn btn-icon btn-sm" href="{{ route('offers.edit',$offer->id) }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @endcan

                                            @can('Offer Delete')
                                            <form action="{{ route('offers.destroy',$offer->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-icon btn-sm" onclick="return confirm('Are you sure you want to delete this offer?')" type="submit">
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
                        </div> <!-- /.table-responsive -->
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div> <!-- /.tab-pane -->
        </div> <!-- /.tab-content -->
    </div> <!-- /.container-fluid -->
</div> <!-- /.section-body -->

@endsection
