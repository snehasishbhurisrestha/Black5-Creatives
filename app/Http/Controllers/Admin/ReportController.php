<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller implements HasMiddleware
{
    /**
     * Define permission-based middleware for each report.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Sales Report', only: ['sales']),
            new Middleware('permission:Orders Report', only: ['orders']),
            new Middleware('permission:Products Report', only: ['products']),
            new Middleware('permission:Payments Report', only: ['payments']),
            new Middleware('permission:Customers Report', only: ['customers']),
        ];
    }

    /**
     * Sales Report
     */
    public function sales(Request $request)
    {
        $query = Order::query()->where('order_status', 'delivered')
                               ->where('payment_status', 'success');

        // ✅ Date range filtering (version-safe)
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = date('Y-m-d 00:00:00', strtotime($request->from_date));
            $to = date('Y-m-d 23:59:59', strtotime($request->to_date));
            $query->where('created_at', '>=', $from)->where('created_at', '<=', $to);
        }

        $sales = $query->latest()->paginate(20);

        return view('admin.reports.sales', compact('sales'));
    }

    /**
     * Orders Report
     */
    public function orders(Request $request)
    {
        $query = Order::query();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = date('Y-m-d 00:00:00', strtotime($request->from_date));
            $to = date('Y-m-d 23:59:59', strtotime($request->to_date));
            $query->where('created_at', '>=', $from)->where('created_at', '<=', $to);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.reports.orders', compact('orders'));
    }

    /**
     * Products Report
     */
    public function products(Request $request)
    {
        $query = Product::query();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = date('Y-m-d 00:00:00', strtotime($request->from_date));
            $to = date('Y-m-d 23:59:59', strtotime($request->to_date));
            $query->where('created_at', '>=', $from)->where('created_at', '<=', $to);
        }

        $products = $query->latest()->paginate(20);

        return view('admin.reports.products', compact('products'));
    }

    /**
     * Payments Report
     */
    public function payments(Request $request)
    {
        $query = Order::query()->where('payment_status', 'success');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = date('Y-m-d 00:00:00', strtotime($request->from_date));
            $to = date('Y-m-d 23:59:59', strtotime($request->to_date));
            $query->where('created_at', '>=', $from)->where('created_at', '<=', $to);
        }

        $payments = $query->select('id', 'order_number', 'payment_method', 'total_amount', 'created_at')
                          ->latest()
                          ->paginate(20);

        return view('admin.reports.payments', compact('payments'));
    }

    /**
     * Customers Report
     */
    public function customers(Request $request)
    {
        $query = User::query()->where('role', 'customer');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = date('Y-m-d 00:00:00', strtotime($request->from_date));
            $to = date('Y-m-d 23:59:59', strtotime($request->to_date));
            $query->where('created_at', '>=', $from)->where('created_at', '<=', $to);
        }

        $customers = $query->latest()->paginate(20);

        return view('admin.reports.customers', compact('customers'));
    }
}
