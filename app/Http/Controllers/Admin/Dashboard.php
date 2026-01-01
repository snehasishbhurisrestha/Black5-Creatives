<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class Dashboard extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Dashboard', only: ['index'])
        ];
    }
    // public function index(){
    //     $todays_orders = Order::whereDate('created_at',date('Y-m-d'))->orderBy('id','desc')->get();
    //     $todays_order_count = Order::whereDate('created_at',date('Y-m-d'))->count();
    //     $total_order_count = Order::all()->count();
    //     $total_income = Order::where('order_status','delivered')->where('payment_status','success')->sum('total_amount');
    //     $current_month_sale = Order::where('order_status', 'delivered')
    //                                 ->where('payment_status', 'success')
    //                                 ->whereMonth('created_at', Carbon::now()->month)
    //                                 ->whereYear('created_at', Carbon::now()->year)
    //                                 ->sum('total_amount');
    //     return view('dashboard',compact('todays_orders','todays_order_count','total_order_count','total_income','current_month_sale'));
    // }

    public function index()
    {
        $todays_orders = Order::whereDate('created_at', date('Y-m-d'))->orderBy('id', 'desc')->get();
        $todays_order_count = $todays_orders->count();
        $total_order_count = Order::count();
        $total_income = Order::where('order_status', 'delivered')
                            ->where('payment_status', 'success')
                            ->sum('total_amount');
        $current_month_sale = Order::where('order_status', 'delivered')
                            ->where('payment_status', 'success')
                            ->whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->sum('total_amount');

        // 📊 Monthly Sales Chart (last 6 months)
        $monthly_sales = Order::select(
            DB::raw('SUM(total_amount) as total'),
            DB::raw('MONTH(created_at) as month')
        )
        ->where('order_status', 'delivered')
        ->where('payment_status', 'success')
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->pluck('total', 'month')
        ->toArray();

        // 🧾 Order Status Distribution
        $order_status_data = Order::select('order_status', DB::raw('count(*) as count'))
            ->groupBy('order_status')
            ->pluck('count', 'order_status')
            ->toArray();

        // 💳 Payment Mode Distribution
        $payment_modes = Order::select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method')
            ->toArray();

        // 🏆 Best Selling Products (Top 5)
        $best_selling = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'todays_orders',
            'todays_order_count',
            'total_order_count',
            'total_income',
            'current_month_sale',
            'monthly_sales',
            'order_status_data',
            'payment_modes',
            'best_selling'
        ));
    }
}