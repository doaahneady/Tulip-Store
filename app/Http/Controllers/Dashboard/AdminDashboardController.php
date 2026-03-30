<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use App\Models\SecurityAuditLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Core Metrics
        $metrics = [
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
            'total_orders' => Order::count(),
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'confirmed')->count(),
            'total_products' => Product::count(),
            'low_stock_products' => Product::where('stock_quantity', '<', 10)->count(),
            'total_employees' => Employee::count(),
            'total_drivers' => Driver::count(),
            'active_drivers' => Driver::where('status', 'available')->count(),
            'total_traders' => \App\Models\Trader::count(),
        ];

        // Revenue Data
        $revenue = [
            'today' => Order::whereDate('created_at', today())->where('status', 'delivered')->sum('total'),
            'yesterday' => Order::whereDate('created_at', today()->subDay())->where('status', 'delivered')->sum('total'),
            'this_week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()])->where('status', 'delivered')->sum('total'),
            'this_month' => Order::whereMonth('created_at', now()->month)->where('status', 'delivered')->sum('total'),
            'last_month' => Order::whereMonth('created_at', now()->subMonth()->month)->where('status', 'delivered')->sum('total'),
        ];

        // Chart Data - Last 7 days revenue
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData[] = [
                'date' => $date->format('M d'),
                'revenue' => Order::whereDate('created_at', $date)->where('status', 'delivered')->sum('total'),
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        }

        // Recent Orders
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(10)->get();

        // Top Products
        $topProducts = Product::withCount('orderItems')->orderBy('order_items_count', 'desc')->take(5)->get();

        // Order Status Distribution
        $orderStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')->get()->pluck('count', 'status')->toArray();

        $loginLogs = Schema::hasTable('security_audit_logs')
            ? SecurityAuditLog::with('user')
                ->where('event_type', 'login_attempt')
                ->orderBy('created_at', 'desc')
                ->take(15)
                ->get()
            : collect();

        $systemLogs = \App\Models\SystemLog::orderBy('created_at', 'desc')->take(20)->get();

        return view('dashboards.admin.index', compact('metrics', 'revenue', 'chartData', 'recentOrders', 'topProducts', 'orderStatus', 'loginLogs', 'systemLogs'));
    }

    public function traders()
    {
        $traders = \App\Models\Trader::paginate(20);
        return view('dashboards.admin.traders.index', compact('traders'));
    }

    public function traderDetails(\App\Models\Trader $trader)
    {
        return view('dashboards.admin.traders.show', compact('trader'));
    }

    public function logs()
    {
        $logs = \App\Models\SystemLog::orderBy('created_at', 'desc')->paginate(50);
        return view('dashboards.admin.logs', compact('logs'));
    }
}
