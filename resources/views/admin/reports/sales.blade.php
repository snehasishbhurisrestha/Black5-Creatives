@extends('layouts.app')

@section('title','Sales Report')

@section('content')
<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Sales Report</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sales Report</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">

        {{-- Filter Form --}}
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
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                <a href="{{ route('reports.sales') }}" class="btn btn-secondary"><i class="fa fa-refresh"></i> Reset</a>
            </div>
        </form>

        {{-- Report Table --}}
        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Order No</th>
                                <th>Total Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><a href="{{ route('order.details', $order->id) }}">{{ $order->order_number }}</a></td>
                                <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                <td>{{ ucfirst($order->payment_method) }}</td>
                                <td>{{ ucfirst($order->order_status) }}</td>
                                <td>{{ format_datetime($order->created_at) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No sales data found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
