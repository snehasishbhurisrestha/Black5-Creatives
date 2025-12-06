@extends('layouts.app')

@section('title','Orders')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Orders</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Orders</li>
                </ol>
            </div>
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
                            <table class="table table-hover js-exportable-desc dataTable table-striped table_custom border-style spacing5">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Order Number</th>
                                        <th>Total Amount</th>
                                        <th>Order Status</th>
                                        <th>Payment</th>
                                        <th>Updated</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td class="text-wrap">{{ format_datetime($order->created_at) }}</td>
                                        <td class="text-wrap"><a href="{{ route('order.details',$order->id) }}">{{ $order->order_number }}</a></td>
                                        <td class="text-wrap">{{ $order->total_amount }}</td>
                                        <td class="text-wrap">{{ ucfirst($order->order_status) }}</td>
                                        <td class="text-wrap">{{ $order->payment_method }} ({{ ucfirst($order->payment_status) }}) </td>
                                        <td class="text-wrap">{{ time_ago($order->updated_at) }}</td>
                                        <td>
                                            <a href="{{ route('order.details',$order->id) }}" class="btn btn-icon btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @can('Order Delete')
                                            <form action="{{ route('order.destroy', $order->id) }}" onsubmit="return confirm('Are you sure?')" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-icon btn-sm" type="submit"><i class="fa fa-trash-o text-danger"></i></button>
                                            </form>
                                            @endcan
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

@endsection