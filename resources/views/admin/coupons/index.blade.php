@extends('layouts.app')

@section('title','Coupons')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Coupons</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Coupons</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                @can('Coupon Create')
                <li class="nav-item"><a class="btn btn-info" href="{{ route('coupon.create') }}"><i class="fa fa-plus"></i>Add New</a></li>
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
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Value</th>
                                        <th>Minimum Purchase</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Usage Type</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        @canany(['Coupon Edit','Coupon Delete'])
                                        <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coupons as $coupon)
                                    <tr>
                                        <td class="text-wrap">{{ $coupon->code }}</td>
                                        <td class="text-wrap">{{ $coupon->type }}</td>
                                        <td class="text-wrap">{{ $coupon->value }}</td>
                                        <td class="text-wrap">{{ $coupon->minimum_purchase }}</td>
                                        <td class="text-wrap">{{ $coupon->start_date }}</td>
                                        <td class="text-wrap">{{ $coupon->end_date }}</td>
                                        <td class="text-wrap">{{ $coupon->usage_type }}</td>
                                        <td>{!! check_visibility($coupon->is_active) !!}</td>
                                        <td class="text-wrap">{{ format_datetime($coupon->created_at) }}</td>
                                        @canany(['Coupon Edit','Coupon Delete'])
                                        <td>
                                            @can('Coupon Edit')
                                            <a href="{{ route('coupon.edit',$coupon->id) }}" class="btn btn-icon btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('Coupon Edit')
                                            <form action="{{ route('coupon.destroy', $coupon->id) }}" onsubmit="return confirm('Are you sure?')" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-icon btn-sm" type="submit"><i class="fa fa-trash-o text-danger"></i></button>
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