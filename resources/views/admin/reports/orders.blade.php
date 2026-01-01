@extends('layouts.app')

@section('title','Orders Report')

@section('content')
<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Orders Report</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Orders Report</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">
        <form method="GET" class="row mb-3">
            <div class="col-md-3">
                <label>From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label>To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
            </div>
            <div class="col-md-3 align-self-end">
                <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                <a href="{{ route('reports.orders') }}" class="btn btn-secondary"><i class="fa fa-refresh"></i> Reset</a>
            </div>
        </form>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Order Number</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><a href="{{ route('order.details', $order->id) }}">{{ $order->order_number }}</a></td>
                                <td>{{ $order->user->name ?? 'Guest' }}</td>
                                <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                <td>{{ ucfirst($order->order_status) }}</td>
                                <td>{{ ucfirst($order->payment_status) }}</td>
                                <td>{{ format_datetime($order->created_at) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center">No orders found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
