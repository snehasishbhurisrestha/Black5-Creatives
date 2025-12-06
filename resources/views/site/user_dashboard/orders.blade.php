@extends('site.user_dashboard.dashboard')

@section('tab-title') Orders @endsection

@section('tab-pane-content')
    <!-- Orders Section -->
    <div class="tab-pane fade show active">
        <div class="card">
            <div class="card-header text-white" style="background-color: rgb(0, 0, 0);">Orders</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Order ID</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ format_datetime($order->created_at) }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->total_amount }}</td>
                            <td>{{ ucfirst($order->order_status) }}</td>
                            <td>{{ $order->payment_method }} ({{ ucfirst($order->payment_status) }})</td>
                            <td><a href="{{ route('user-dashboard.orders.details',$order->id) }}" class="btn btn-sm btn-info">View</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
