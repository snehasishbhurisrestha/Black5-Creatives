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
                                        <th>Image</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Offer Detail</th>
                                        <th>Category</th>
                                        <th>Min Qty</th>
                                        <th>Min Purchase</th>
                                        <th>Status</th>
                                        <th>Valid Date</th>
                                        <th>Created</th>

                                        @canany(['Coupon Edit','Coupon Delete'])
                                        <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($coupons as $coupon)
                                    <tr>

                                        {{-- IMAGE --}}
                                        <td>
                                            @php
                                                $img = $coupon->getFirstMediaUrl('coupon_image');
                                            @endphp

                                            @if($img)
                                                <img src="{{ $img }}" width="60" style="border-radius:8px;">
                                            @else
                                                <span class="badge bg-secondary">No Image</span>
                                            @endif
                                        </td>

                                        <td>{{ $coupon->code }}</td>

                                        {{-- TYPE --}}
                                        <td>
                                            <span class="badge bg-info">
                                                {{ strtoupper($coupon->type) }}
                                            </span>
                                        </td>

                                        {{-- SMART OFFER DISPLAY --}}
                                        <td>
                                            @if($coupon->type == 'percentage')
                                                {{ $coupon->value }}% OFF
                                            @elseif($coupon->type == 'flat')
                                                ₹{{ $coupon->value }} OFF
                                            @elseif($coupon->type == 'free_shipping')
                                                <span class="badge bg-success">Free Shipping</span>
                                            @elseif($coupon->type == 'bogo')
                                                Buy {{ $coupon->buy_qty }} Get {{ $coupon->get_qty }}
                                                @if($coupon->free_product_type)
                                                    ({{ $coupon->free_product_type }})
                                                @endif
                                            @elseif($coupon->type == 'price_override')
                                                Special Price: ₹{{ $coupon->override_price }}
                                            @endif
                                        </td>

                                        {{-- CATEGORY --}}
                                        <td>
                                            @if($coupon->category)
                                                <span class="badge bg-dark">{{ $coupon->category }}</span>
                                            @else
                                                Any
                                            @endif

                                            <br>

                                            @if($coupon->product_type)
                                                <small class="text-primary">{{ $coupon->product_type }}</small>
                                            @endif
                                        </td>

                                        {{-- MIN QTY --}}
                                        <td>
                                            {{ $coupon->min_qty ?? '-' }}
                                        </td>

                                        {{-- MIN PURCHASE --}}
                                        <td>
                                            ₹{{ $coupon->minimum_purchase ?? 0 }}
                                        </td>

                                        {{-- STATUS --}}
                                        <td>
                                            @if($coupon->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>

                                        {{-- DATES --}}
                                        <td>
                                            <small>
                                                {{ $coupon->start_date ? $coupon->start_date->format('d M Y h:i A') : '-' }}
                                                <br>
                                                to
                                                <br>
                                                {{ $coupon->end_date ? $coupon->end_date->format('d M Y h:i A') : '-' }}
                                            </small>
                                        </td>

                                        <td>{{ format_datetime($coupon->created_at) }}</td>

                                        {{-- ACTIONS --}}
                                        @canany(['Coupon Edit','Coupon Delete'])
                                        <td>
                                            @can('Coupon Edit')
                                            <a href="{{ route('coupon.edit',$coupon->id) }}" class="btn btn-icon btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @endcan

                                            @can('Coupon Delete')
                                            <form action="{{ route('coupon.destroy', $coupon->id) }}"
                                                onsubmit="return confirm('Are you sure?')"
                                                method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-icon btn-sm" type="submit">
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