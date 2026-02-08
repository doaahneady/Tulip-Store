<?php

namespace App\Http\Controllers\Legacy;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\FinancialTransaction;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\SupportTicket;
use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dashboardData = $this->getDashboardData($user);

        return view('dashboard.index', compact('dashboardData', 'user'));
    }

    public function admin()
    {
        $this->authorize('admin');

        $data = [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_stores' => Store::count(),
            'revenue_today' => Order::whereDate('created_at', today())->sum('total_amount'),
            'revenue_month' => Order::whereMonth('created_at', now()->month)->sum('total_amount'),
            'recent_orders' => Order::with('user')->latest()->take(10)->get(),
            'recent_users' => User::latest()->take(10)->get(),
            'system_alerts' => SystemLog::where('level', 'error')->latest()->take(5)->get(),
        ];

        return view('dashboard.admin', compact('data'));
    }

    public function it()
    {
        $this->authorize('it');

        $data = [
            'system_status' => $this->getSystemStatus(),
            'server_metrics' => $this->getServerMetrics(),
            'recent_logs' => SystemLog::latest()->take(20)->get(),
            'security_alerts' => SystemLog::where('type', 'security')->latest()->take(10)->get(),
            'performance_metrics' => $this->getPerformanceMetrics(),
        ];

        return view('dashboard.it', compact('data'));
    }

    public function cs()
    {
        $this->authorize('cs');

        $data = [
            'open_tickets' => SupportTicket::where('status', 'open')->count(),
            'pending_tickets' => SupportTicket::where('status', 'pending')->count(),
            'resolved_today' => SupportTicket::where('status', 'resolved')->whereDate('updated_at', today())->count(),
            'recent_tickets' => SupportTicket::with('user')->latest()->take(10)->get(),
            'customer_satisfaction' => $this->getCustomerSatisfactionScore(),
        ];

        return view('dashboard.cs', compact('data'));
    }

    public function hr()
    {
        $this->authorize('hr');

        $data = [
            'total_employees' => Employee::count(),
            'present_today' => Employee::whereHas('attendance', function ($q) {
                $q->whereDate('date', today())->where('status', 'present');
            })->count(),
            'on_leave' => Employee::whereHas('leaveRequests', function ($q) {
                $q->where('status', 'approved')
                    ->where('start_date', '<=', today())
                    ->where('end_date', '>=', today());
            })->count(),
            'recent_applications' => Employee::latest()->take(10)->get(),
            'payroll_summary' => $this->getPayrollSummary(),
        ];

        return view('dashboard.hr', compact('data'));
    }

    public function delivery()
    {
        $this->authorize('delivery');

        $data = [
            'active_drivers' => Driver::where('status', 'active')->count(),
            'pending_deliveries' => Order::where('status', 'processing')->count(),
            'completed_today' => Order::where('status', 'delivered')->whereDate('updated_at', today())->count(),
            'drivers_locations' => Driver::with('currentLocation')->where('status', 'active')->get(),
            'delivery_metrics' => $this->getDeliveryMetrics(),
        ];

        return view('dashboard.delivery', compact('data'));
    }

    public function finance()
    {
        $this->authorize('finance');

        $data = [
            'revenue_today' => FinancialTransaction::where('type', 'income')->whereDate('created_at', today())->sum('amount'),
            'revenue_month' => FinancialTransaction::where('type', 'income')->whereMonth('created_at', now()->month)->sum('amount'),
            'expenses_month' => FinancialTransaction::where('type', 'expense')->whereMonth('created_at', now()->month)->sum('amount'),
            'pending_payouts' => FinancialTransaction::where('status', 'pending')->sum('amount'),
            'recent_transactions' => FinancialTransaction::latest()->take(15)->get(),
            'financial_summary' => $this->getFinancialSummary(),
        ];

        return view('dashboard.finance', compact('data'));
    }

    public function storeOwner()
    {
        $this->authorize('store-owner');

        $user = Auth::user();
        $store = $user->store;

        if (! $store) {
            return redirect()->route('store.setup');
        }

        $data = [
            'store' => $store,
            'total_products' => $store->products()->count(),
            'total_orders' => Order::whereHas('items.product', function ($q) use ($store) {
                $q->where('store_id', $store->id);
            })->count(),
            'revenue_month' => Order::whereHas('items.product', function ($q) use ($store) {
                $q->where('store_id', $store->id);
            })->whereMonth('created_at', now()->month)->sum('total_amount'),
            'recent_orders' => Order::whereHas('items.product', function ($q) use ($store) {
                $q->where('store_id', $store->id);
            })->latest()->take(10)->get(),
            'top_products' => $this->getTopProducts($store),
        ];

        return view('dashboard.store-owner', compact('data'));
    }

    private function getDashboardData($user)
    {
        $data = [];

        if ($user->is_admin) {
            $data['admin'] = true;
        }

        if ($user->is_it || $user->is_it_super) {
            $data['it'] = true;
        }

        if ($user->is_cs) {
            $data['cs'] = true;
        }

        if ($user->is_hr) {
            $data['hr'] = true;
        }

        if ($user->is_driver_supervisor) {
            $data['delivery'] = true;
        }

        if ($user->is_finance || $user->is_accountant) {
            $data['finance'] = true;
        }

        if ($user->is_trader) {
            $data['store_owner'] = true;
        }

        return $data;
    }

    private function getSystemStatus()
    {
        return [
            'database' => 'healthy',
            'cache' => 'healthy',
            'storage' => 'healthy',
            'queue' => 'healthy',
        ];
    }

    private function getServerMetrics()
    {
        return [
            'cpu_usage' => rand(20, 80),
            'memory_usage' => rand(30, 70),
            'disk_usage' => rand(40, 60),
            'uptime' => '15 days',
        ];
    }

    private function getPerformanceMetrics()
    {
        return [
            'avg_response_time' => '120ms',
            'requests_per_minute' => rand(50, 200),
            'error_rate' => '0.1%',
        ];
    }

    private function getCustomerSatisfactionScore()
    {
        return rand(85, 95) / 10;
    }

    private function getPayrollSummary()
    {
        return [
            'total_salary' => rand(50000, 100000),
            'pending_payments' => rand(5000, 15000),
            'processed_this_month' => rand(80000, 120000),
        ];
    }

    private function getDeliveryMetrics()
    {
        return [
            'avg_delivery_time' => '45 minutes',
            'success_rate' => '98.5%',
            'customer_rating' => 4.7,
        ];
    }

    private function getFinancialSummary()
    {
        return [
            'profit_margin' => rand(15, 25),
            'growth_rate' => rand(5, 15),
            'cash_flow' => rand(10000, 50000),
        ];
    }

    private function getTopProducts($store)
    {
        return $store->products()
            ->withCount(['orderItems'])
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();
    }
}
