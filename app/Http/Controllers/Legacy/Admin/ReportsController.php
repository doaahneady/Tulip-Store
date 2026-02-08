<?php

namespace App\Http\Controllers\Legacy\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is admin
        if (! auth()->user()->is_admin) {
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
        $sumColumn = Schema::hasColumn('orders', 'total_amount')
            ? 'total_amount'
            : (Schema::hasColumn('orders', 'total') ? 'total' : null);
        $sumExpr = $sumColumn ? $sumColumn : implode(' + ', array_filter([
            Schema::hasColumn('orders', 'subtotal') ? 'subtotal' : null,
            Schema::hasColumn('orders', 'delivery_cost') ? 'delivery_cost' : null,
            Schema::hasColumn('orders', 'service_fee') ? 'service_fee' : null,
        ]));
        $salesReports = [
            'today' => $sumExpr ? (Order::whereDate('created_at', $today)->selectRaw('SUM('.$sumExpr.') as agg')->value('agg') ?? 0) : 0,
            'week' => $sumExpr ? (Order::where('created_at', '>=', $thisWeek)->selectRaw('SUM('.$sumExpr.') as agg')->value('agg') ?? 0) : 0,
            'month' => $sumExpr ? (Order::where('created_at', '>=', $thisMonth)->selectRaw('SUM('.$sumExpr.') as agg')->value('agg') ?? 0) : 0,
            'year' => $sumExpr ? (Order::where('created_at', '>=', $thisYear)->selectRaw('SUM('.$sumExpr.') as agg')->value('agg') ?? 0) : 0,
            'last_month' => $sumExpr ? (Order::whereBetween('created_at', [$lastMonth, $thisMonth])->selectRaw('SUM('.$sumExpr.') as agg')->value('agg') ?? 0) : 0,
            'last_year' => $sumExpr ? (Order::whereBetween('created_at', [$lastYear, $thisYear])->selectRaw('SUM('.$sumExpr.') as agg')->value('agg') ?? 0) : 0,
            'all_time' => $sumExpr ? (Order::selectRaw('SUM('.$sumExpr.') as agg')->value('agg') ?? 0) : 0,
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
        $stockCol = \Schema::hasColumn('products', 'stock_quantity') ? 'stock_quantity' : (\Schema::hasColumn('products', 'stock') ? 'stock' : null);
        $productReports = [
            'total' => Product::count(),
            'active' => Product::query()
                ->when(\Schema::hasColumn('products', 'is_active'), fn ($q) => $q->where('is_active', true))
                ->when(! \Schema::hasColumn('products', 'is_active') && \Schema::hasColumn('products', 'status'), fn ($q) => $q->where('status', 'active'))
                ->count(),
            'inactive' => Product::query()
                ->when(\Schema::hasColumn('products', 'is_active'), fn ($q) => $q->where('is_active', false))
                ->when(! \Schema::hasColumn('products', 'is_active') && \Schema::hasColumn('products', 'status'), fn ($q) => $q->where('status', '!=', 'active'))
                ->count(),
            'out_of_stock' => $stockCol ? Product::where($stockCol, 0)->count() : 0,
            'low_stock' => $stockCol ? Product::where($stockCol, '>', 0)->where($stockCol, '<', 10)->count() : 0,
        ];

        // Top Products
        $imageSelect = \Schema::hasColumn('products', 'image') ? 'products.image' : DB::raw('NULL as image');
        $itemRevenueExpr = \Schema::hasColumn('order_items', 'total_price')
            ? 'SUM(order_items.total_price)'
            : (\Schema::hasColumn('order_items', 'subtotal')
                ? 'SUM(order_items.subtotal)'
                : 'SUM(order_items.quantity * order_items.unit_price)');

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                $imageSelect,
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw($itemRevenueExpr.' as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->when(\Schema::hasColumn('products', 'image'), fn ($q) => $q->groupBy('products.image'))
            ->orderBy('revenue', 'desc')
            ->take(10)
            ->get();

        // Top Customers
        $topCustomers = User::where('is_admin', false)
            ->withCount('orders')
            ->with(['orders' => function ($q) {
                $q->where('status', '!=', 'cancelled');
            }])
            ->get()
            ->map(function ($user) use ($sumColumn) {
                if ($sumColumn) {
                    $user->total_spent = (float) $user->orders->sum($sumColumn);
                } else {
                    $user->total_spent = (float) $user->orders->sum(function ($order) {
                        $subtotal = property_exists($order, 'subtotal') ? (float) ($order->subtotal ?? 0) : 0;
                        $delivery = property_exists($order, 'delivery_cost') ? (float) ($order->delivery_cost ?? 0) : 0;
                        $service = property_exists($order, 'service_fee') ? (float) ($order->service_fee ?? 0) : 0;

                        return $subtotal + $delivery + $service;
                    });
                }

                return $user;
            })
            ->sortByDesc('total_spent')
            ->take(10);

        // Revenue by Payment Method
        $revenueByPayment = Order::select('payment_method', DB::raw($sumExpr ? 'SUM('.$sumExpr.') as revenue' : '0 as revenue'), DB::raw('COUNT(*) as count'))
            ->where('payment_status', 'paid')
            ->groupBy('payment_method')
            ->get();

        // Daily Sales (Last 30 days)
        $dailySales = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = $sumExpr ? (Order::whereDate('created_at', $date)->selectRaw('SUM('.$sumExpr.') as agg')->value('agg') ?? 0) : 0;
            $orders = Order::whereDate('created_at', $date)->count();
            $dailySales[] = [
                'date' => $date->format('M d'),
                'sales' => round($sales, 2),
                'orders' => $orders,
            ];
        }

        // Monthly Comparison
        $monthlyComparison = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $sales = $sumExpr ? (Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->selectRaw('SUM('.$sumExpr.') as agg')->value('agg') ?? 0) : 0;
            $orders = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyComparison[] = [
                'month' => $month->format('M Y'),
                'sales' => round($sales, 2),
                'orders' => $orders,
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
        $customSales = $sumExpr ? (Order::whereBetween('created_at', [$startDate, $endDate])->selectRaw('SUM('.$sumExpr.') as agg')->value('agg') ?? 0) : 0;
        $customOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $customCustomers = User::whereBetween('created_at', [$startDate, $endDate])->where('is_admin', false)->count();

        // Daily breakdown for selected range
        $dailyBreakdown = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $daySales = $sumExpr ? (Order::whereDate('created_at', $currentDate)->selectRaw('SUM('.$sumExpr.') as agg')->value('agg') ?? 0) : 0;
            $dayOrders = Order::whereDate('created_at', $currentDate)->count();
            $dailyBreakdown[] = [
                'date' => $currentDate->format('Y-m-d'),
                'display_date' => $currentDate->format('M d'),
                'sales' => round($daySales, 2),
                'orders' => $dayOrders,
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
        $sumCol = \Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : (\Schema::hasColumn('orders', 'total') ? 'total' : null);
        $salesReports = [
            'today' => $sumCol ? Order::whereDate('created_at', $today)->sum($sumCol) : 0,
            'week' => $sumCol ? Order::where('created_at', '>=', $thisWeek)->sum($sumCol) : 0,
            'month' => $sumCol ? Order::where('created_at', '>=', $thisMonth)->sum($sumCol) : 0,
            'year' => $sumCol ? Order::where('created_at', '>=', $thisYear)->sum($sumCol) : 0,
            'last_month' => $sumCol ? Order::whereBetween('created_at', [$lastMonth, $thisMonth])->sum($sumCol) : 0,
            'all_time' => $sumCol ? Order::sum($sumCol) : 0,
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
            ->with(['orders' => function ($q) {
                $q->where('status', '!=', 'cancelled');
            }])
            ->get()
            ->map(function ($user) {
                $user->total_spent = $user->orders->sum($sumCol ?? 'subtotal');

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

        $filename = 'daily-report-'.Carbon::now()->format('Y-m-d').'.pdf';

        return $pdf->download($filename);
    }
}
