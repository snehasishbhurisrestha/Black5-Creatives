@extends('layouts.app')
@section('title','Dashboard')
@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Dashboard</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ config('app.name', 'Laravel') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#admin-Dashboard">Dashboard</a></li>
                {{-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#admin-Activity">Activity</a></li> --}}
            </ul>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">
        <div class="row clearfix row-deck">
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card">
                    <div class="card-body ribbon">
                        <a href="{{ route('order.index') }}" class="my_sort_cut text-muted">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Orders</span>
                            <span>{{ $total_order_count ?? 0 }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card">
                    <div class="card-body ribbon">
                        <a href="{{ route('order.index') }}" class="my_sort_cut text-muted">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Todays Order</span>
                            <span>{{ $todays_order_count ?? 0 }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card">
                    <div class="card-body ribbon">
                        <a href="{{ route('order.index') }}" class="my_sort_cut text-muted">
                            <i class="fa fa-money"></i>
                            <span>Total Sale</span>
                            <span>₹ {{ $total_income ?? 0 }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card">
                    <div class="card-body ribbon">
                        <a href="{{ route('order.index') }}" class="my_sort_cut text-muted">
                            <i class="fa fa-money"></i>
                            <span>{{ date('F') }} Sale</span>
                            <span>{{ $current_month_sale ?? 0 }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="admin-Dashboard" role="tabpanel">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Today Orders List</h3>
                                <div class="card-options">
                                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                    <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                                    <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0 text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Order Number</th>
                                                <th>Total Amount</th>
                                                <th>Order Status</th>
                                                <th>Payment</th>
                                                <th>Updated</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($todays_orders as $order)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-wrap"><a href="{{ route('order.details',$order->id) }}">{{ $order->order_number }}</a></td>
                                                <td class="text-wrap">{{ $order->total_amount }}</td>
                                                <td class="text-wrap">{{ ucfirst($order->order_status) }}</td>
                                                <td class="text-wrap">{{ $order->payment_method }} ({{ ucfirst($order->payment_status) }}) </td>
                                                <td class="text-wrap">{{ time_ago($order->updated_at) }}</td>
                                                <td>
                                                    <a href="{{ route('order.details',$order->id) }}" class="btn btn-icon btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('order.destroy', $order->id) }}" onsubmit="return confirm('Are you sure?')" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-icon btn-sm" type="submit"><i class="fa fa-trash-o text-danger"></i></button>
                                                    </form>
                                                </td>
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
    </div>
</div>

@endsection
