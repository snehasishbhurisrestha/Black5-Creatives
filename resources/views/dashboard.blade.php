@extends('layouts.app')

@section('title','Dashboard')

@section('style')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-3d@2.1.0"></script>
<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .my_sort_cut {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px 0;
        text-decoration: none !important;
    }
    
    .my_sort_cut i {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .my_sort_cut span:first-of-type {
        font-size: 0.9rem;
        margin-bottom: 5px;
    }
    
    .my_sort_cut span:last-of-type {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .card-header h3 {
        margin-bottom: 0;
    }
    
    @media (max-width: 768px) {
        .chart-container {
            height: 250px;
        }
    }
</style>
@endsection

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
            </ul>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">
        <div class="row clearfix row-deck">
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body ribbon">
                        <a href="{{ route('order.index') }}" class="my_sort_cut text-muted">
                            <i class="fa fa-shopping-cart text-primary"></i>
                            <span>Orders</span>
                            <span>{{ $total_order_count ?? 0 }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body ribbon">
                        <a href="{{ route('order.index') }}" class="my_sort_cut text-muted">
                            <i class="fa fa-shopping-cart text-success"></i>
                            <span>Todays Order</span>
                            <span>{{ $todays_order_count ?? 0 }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body ribbon">
                        <a href="{{ route('order.index') }}" class="my_sort_cut text-muted">
                            <i class="fa fa-money text-warning"></i>
                            <span>Total Sale</span>
                            <span>₹ {{ $total_income ?? 0 }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body ribbon">
                        <a href="{{ route('order.index') }}" class="my_sort_cut text-muted">
                            <i class="fa fa-money text-info"></i>
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
        <div class="row">
            <!-- Monthly Sales Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3>Monthly Sales (Last 6 Months)</h3></div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlySalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Status Distribution -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3>Order Status</h3></div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Mode Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3>Payment Modes</h3></div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="paymentModeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Best Selling Products -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3>Top 5 Best Selling Products</h3></div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="bestSellingChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
@php
    $monthLabels = [];
    foreach (array_keys($monthly_sales) as $m) {
        $monthLabels[] = date("M", mktime(0, 0, 0, $m, 1));
    }
@endphp
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Register plugins
        Chart.register(ChartDataLabels);
        
        // Convert PHP data to JS
        const monthlyLabels = @json($monthLabels);
        const monthlySales = @json(array_values($monthly_sales));

        const orderStatusLabels = @json(array_keys($order_status_data));
        const orderStatusValues = @json(array_values($order_status_data));

        const paymentModeLabels = @json(array_keys($payment_modes));
        const paymentModeValues = @json(array_values($payment_modes));

        const bestSellingLabels = @json($best_selling->pluck('name'));
        const bestSellingValues = @json($best_selling->pluck('total_sold'));

        // Color palettes
        const vibrantColors = [
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)'
        ];
        
        const pastelColors = [
            'rgba(255, 182, 193, 0.8)',
            'rgba(173, 216, 230, 0.8)',
            'rgba(255, 250, 205, 0.8)',
            'rgba(221, 160, 221, 0.8)',
            'rgba(152, 251, 152, 0.8)',
            'rgba(255, 218, 185, 0.8)'
        ];

        // Monthly Sales Chart
        new Chart(document.getElementById('monthlySalesChart'), {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Sales (₹)',
                    data: monthlySales,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    },
                    datalabels: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Order Status Chart - 3D Doughnut
        new Chart(document.getElementById('orderStatusChart'), {
            type: 'doughnut',
            data: {
                labels: orderStatusLabels,
                datasets: [{
                    data: orderStatusValues,
                    backgroundColor: vibrantColors,
                    borderColor: 'white',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    labels: {
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: (value, ctx) => {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${percentage}%`;
                        }
                    }
                },
                cutout: '60%',
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Payment Mode Chart - 3D Pie
        new Chart(document.getElementById('paymentModeChart'), {
            type: 'pie',
            data: {
                labels: paymentModeLabels,
                datasets: [{
                    data: paymentModeValues,
                    backgroundColor: pastelColors,
                    borderColor: 'white',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    },
                    datalabels: {
                        color: '#333',
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        formatter: (value, ctx) => {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return percentage >= 5 ? `${percentage}%` : null;
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Best Selling Chart - 3D Bar
        new Chart(document.getElementById('bestSellingChart'), {
            type: 'bar',
            data: {
                labels: bestSellingLabels,
                datasets: [{
                    label: 'Units Sold',
                    data: bestSellingValues,
                    backgroundColor: 'rgba(75, 192, 192, 0.8)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: '#333',
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutBounce'
                }
            }
        });
    });
</script>

@endsection