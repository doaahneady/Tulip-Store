<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is admin
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        // Ensure proper database connection
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            abort(500, 'Database connection failed');
        }

        // Get date range from request or use defaults (last 30 days)
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();
        
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        // Date ranges
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastYear = Carbon::now()->subYear()->startOfYear();

        // Sales Reports
        $salesReports = [
            'today' => Order::whereDate('created_at', $today)->sum('total'),
            'week' => Order::where('created_at', '>=', $thisWeek)->sum('total'),
            'month' => Order::where('created_at', '>=', $thisMonth)->sum('total'),
            'year' => Order::where('created_at', '>=', $thisYear)->sum('total'),
            'last_month' => Order::whereBetween('created_at', [$lastMonth, $thisMonth])->sum('total'),
            'last_year' => Order::whereBetween('created_at', [$lastYear, $thisYear])->sum('total'),
            'all_time' => Order::sum('total'),
        ];

        // Order Reports
        $orderReports = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'today' => Order::whereDate('created_at', $today)->count(),
            'this_month' => Order::where('created_at', '>=', $thisMonth)->count(),
        ];

        // Customer Reports
        $customerReports = [
            'total' => User::where('is_admin', false)->count(),
            'new_today' => User::whereDate('created_at', $today)->count(),
            'new_this_week' => User::where('created_at', '>=', $thisWeek)->count(),
            'new_this_month' => User::where('created_at', '>=', $thisMonth)->count(),
            'with_orders' => User::has('orders')->count(),
            'without_orders' => User::where('is_admin', false)->doesntHave('orders')->count(),
        ];

        // Product Reports
        $productReports = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<', 10)->count(),
        ];

        // Top Products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                'products.image',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderBy('revenue', 'desc')
            ->take(10)
            ->get();

        // Top Customers
        $topCustomers = User::where('is_admin', false)
            ->withCount('orders')
            ->with(['orders' => function($q) {
                $q->where('status', '!=', 'cancelled');
            }])
            ->get()
            ->map(function($user) {
                $user->total_spent = $user->orders->sum('total');
                return $user;
            })
            ->sortByDesc('total_spent')
            ->take(10);

        // Revenue by Payment Method
        $revenueByPayment = Order::select('payment_method', DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as count'))
            ->where('payment_status', 'paid')
            ->groupBy('payment_method')
            ->get();

        // Daily Sales (Last 30 days)
        $dailySales = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = Order::whereDate('created_at', $date)->sum('total');
            $orders = Order::whereDate('created_at', $date)->count();
            $dailySales[] = [
                'date' => $date->format('M d'),
                'sales' => round($sales, 2),
                'orders' => $orders
            ];
        }

        // Monthly Comparison
        $monthlyComparison = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $sales = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total');
            $orders = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyComparison[] = [
                'month' => $month->format('M Y'),
                'sales' => round($sales, 2),
                'orders' => $orders
            ];
        }

        // Category Performance
        $categoryPerformance = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('SUM(order_items.subtotal) as revenue'),
                DB::raw('SUM(order_items.quantity) as items_sold'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as orders')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();

        // Custom date range reports
        $customSales = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        $customOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $customCustomers = User::whereBetween('created_at', [$startDate, $endDate])->where('is_admin', false)->count();
        
        // Daily breakdown for selected range
        $dailyBreakdown = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $daySales = Order::whereDate('created_at', $currentDate)->sum('total');
            $dayOrders = Order::whereDate('created_at', $currentDate)->count();
            $dailyBreakdown[] = [
                'date' => $currentDate->format('Y-m-d'),
                'display_date' => $currentDate->format('M d'),
                'sales' => round($daySales, 2),
                'orders' => $dayOrders
            ];
            $currentDate->addDay();
        }
        
        // Top products in date range
        $topProductsRange = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('revenue', 'desc')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact(
            'salesReports',
            'orderReports',
            'customerReports',
            'productReports',
            'topProducts',
            'topCustomers',
            'revenueByPayment',
            'dailySales',
            'monthlyComparison',
            'categoryPerformance',
            'startDate',
            'endDate',
            'customSales',
            'customOrders',
            'customCustomers',
            'dailyBreakdown',
            'topProductsRange'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'daily');
        
        // Force UTF-8 encoding for Arabic support
        mb_internal_encoding('UTF-8');
        
        // Get all the report data
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Sales Reports
        $salesReports = [
            'today' => Order::whereDate('created_at', $today)->sum('total'),
            'week' => Order::where('created_at', '>=', $thisWeek)->sum('total'),
            'month' => Order::where('created_at', '>=', $thisMonth)->sum('total'),
            'year' => Order::where('created_at', '>=', $thisYear)->sum('total'),
            'last_month' => Order::whereBetween('created_at', [$lastMonth, $thisMonth])->sum('total'),
            'all_time' => Order::sum('total'),
        ];

        // Order Reports
        $orderReports = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'today' => Order::whereDate('created_at', $today)->count(),
            'this_month' => Order::where('created_at', '>=', $thisMonth)->count(),
        ];

        // Customer Reports
        $customerReports = [
            'total' => User::where('is_admin', false)->count(),
            'new_today' => User::whereDate('created_at', $today)->count(),
            'new_this_week' => User::where('created_at', '>=', $thisWeek)->count(),
            'new_this_month' => User::where('created_at', '>=', $thisMonth)->count(),
            'with_orders' => User::has('orders')->count(),
            'without_orders' => User::where('is_admin', false)->doesntHave('orders')->count(),
        ];

        // Product Reports
        $productReports = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<', 10)->count(),
        ];

        // Top Products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('revenue', 'desc')
            ->take(10)
            ->get();

        // Top Customers
        $topCustomers = User::where('is_admin', false)
            ->withCount('orders')
            ->with(['orders' => function($q) {
                $q->where('status', '!=', 'cancelled');
            }])
            ->get()
            ->map(function($user) {
                $user->total_spent = $user->orders->sum('total');
                return $user;
            })
            ->sortByDesc('total_spent')
            ->take(10);

        // Category Performance
        $categoryPerformance = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('SUM(order_items.subtotal) as revenue'),
                DB::raw('SUM(order_items.quantity) as items_sold')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();

        $data = compact(
            'salesReports',
            'orderReports',
            'customerReports',
            'productReports',
            'topProducts',
            'topCustomers',
            'categoryPerformance'
        );

        $pdf = Pdf::loadView('admin.reports.pdf', $data);
        
        $filename = 'daily-report-' . Carbon::now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
