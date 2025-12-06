<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
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
    public function index(){
        $todays_orders = Order::whereDate('created_at',date('Y-m-d'))->orderBy('id','desc')->get();
        $todays_order_count = Order::whereDate('created_at',date('Y-m-d'))->count();
        $total_order_count = Order::all()->count();
        $total_income = Order::where('order_status','delivered')->where('payment_status','success')->sum('total_amount');
        $current_month_sale = Order::where('order_status', 'delivered')
                                    ->where('payment_status', 'success')
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->sum('total_amount');
        return view('dashboard',compact('todays_orders','todays_order_count','total_order_count','total_income','current_month_sale'));
    }
}