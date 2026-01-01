@extends('layouts.app')

@section('title','Payments Report')

@section('content')
<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">Payments Report</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Payments Report</li>
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
                <a href="{{ route('reports.payments') }}" class="btn btn-secondary"><i class="fa fa-refresh"></i> Reset</a>
            </div>
        </form>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Order No</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><a href="{{ route('order.details',$payment->id) }}">{{ $payment->order_number }}</a></td>
                                <td>{{ ucfirst($payment->payment_method) }}</td>
                                <td>₹{{ number_format($payment->total_amount, 2) }}</td>
                                <td>{{ format_datetime($payment->created_at) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">No payments found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
