<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class MainController extends Controller
{
    public function index()
    {
        $user = auth('employee')->user();

        // Basic metrics
        $totalColumn = \Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : 'total';
        $metrics = [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_employees' => Employee::count(),
            'revenue_today' => Order::whereDate('created_at', today())->sum($totalColumn),
            'orders_today' => Order::whereDate('created_at', today())->count(),
        ];

        return view('dashboards.main', compact('user', 'metrics'));
    }
}
