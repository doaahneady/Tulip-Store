<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }
        
        // Get date ranges
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisWeek = Carbon::now()->startOfWeek();
        $lastWeek = Carbon::now()->subWeek()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        
        // Total Sales with comparisons
        $salesToday = Order::whereDate('created_at', $today)->sum('total');
        $salesYesterday = Order::whereDate('created_at', $yesterday)->sum('total');
        $salesWeek = Order::where('created_at', '>=', $thisWeek)->sum('total');
        $salesLastWeek = Order::whereBetween('created_at', [$lastWeek, $thisWeek])->sum('total');
        $salesMonth = Order::where('created_at', '>=', $thisMonth)->sum('total');
        $salesLastMonth = Order::whereBetween('created_at', [$lastMonth, $thisMonth])->sum('total');
        $salesYear = Order::where('created_at', '>=', $thisYear)->sum('total');
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        
        // Calculate growth percentages
        $salesGrowthDaily = $salesYesterday > 0 ? (($salesToday - $salesYesterday) / $salesYesterday) * 100 : 0;
        $salesGrowthWeekly = $salesLastWeek > 0 ? (($salesWeek - $salesLastWeek) / $salesLastWeek) * 100 : 0;
        $salesGrowthMonthly = $salesLastMonth > 0 ? (($salesMonth - $salesLastMonth) / $salesLastMonth) * 100 : 0;
        
        // Total Orders with comparisons
        $ordersToday = Order::whereDate('created_at', $today)->count();
        $ordersYesterday = Order::whereDate('created_at', $yesterday)->count();
        $ordersWeek = Order::where('created_at', '>=', $thisWeek)->count();
        $ordersLastWeek = Order::whereBetween('created_at', [$lastWeek, $thisWeek])->count();
        $ordersMonth = Order::where('created_at', '>=', $thisMonth)->count();
        $ordersTotal = Order::count();
        
        $ordersGrowthDaily = $ordersYesterday > 0 ? (($ordersToday - $ordersYesterday) / $ordersYesterday) * 100 : 0;
        
        // Order Status Breakdown
        $ordersPending = Order::where('status', 'pending')->count();
        $ordersProcessing = Order::where('status', 'processing')->count();
        $ordersShipped = Order::where('status', 'shipped')->count();
        $ordersDelivered = Order::where('status', 'delivered')->count();
        $ordersCancelled = Order::where('status', 'cancelled')->count();
        
        // Total Customers with details
        $customersTotal = User::where('is_admin', false)->count();
        $customersToday = User::whereDate('created_at', $today)->count();
        $customersWeek = User::where('created_at', '>=', $thisWeek)->count();
        $customersMonth = User::where('created_at', '>=', $thisMonth)->count();
        $customersActive = User::where('is_admin', false)
            ->whereHas('orders', function($q) use ($thisMonth) {
                $q->where('created_at', '>=', $thisMonth);
            })->count();
        
        // Average Order Value
        $avgOrderValue = Order::avg('total') ?? 0;
        $avgOrderItems = DB::table('order_items')->avg('quantity') ?? 0;
        
        // Product Statistics
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $featuredProducts = Product::where('is_featured', true)->count();
        $outOfStock = Product::where('stock', 0)->count();
        $lowStock = Product::where('stock', '>', 0)->where('stock', '<', 10)->count();
        
        // Category Statistics
        $totalCategories = DB::table('categories')->count();
        $activeCategories = DB::table('categories')->where('is_active', true)->count();
        
        // Recent Orders
        $recentOrders = Order::with('user')->latest()->take(10)->get();
        
        // Top Selling Products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', 'products.image', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderBy('total_sold', 'desc')
            ->take(5)
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
            ->take(5);
        
        // Sales Chart Data (Last 30 days)
        $salesChartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = Order::whereDate('created_at', $date)->sum('total');
            $orders = Order::whereDate('created_at', $date)->count();
            $salesChartData[] = [
                'date' => $date->format('M d'),
                'sales' => round($sales, 2),
                'orders' => $orders
            ];
        }
        
        // Monthly Sales Comparison (Last 12 months)
        $monthlySalesData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $sales = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total');
            $monthlySalesData[] = [
                'month' => $month->format('M Y'),
                'sales' => round($sales, 2)
            ];
        }
        
        // Order Status Distribution
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        // Payment Method Distribution
        $ordersByPayment = Order::select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->get();
        
        // Low Stock Products
        $lowStockProducts = Product::where('stock', '<', 10)->where('stock', '>', 0)->orderBy('stock')->take(10)->get();
        
        // Out of Stock Products
        $outOfStockProducts = Product::where('stock', 0)->take(5)->get();
        
        // Recent Customers
        $recentCustomers = User::where('is_admin', false)->latest()->take(5)->get();
        
        // Category Performance
        $categoryPerformance = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as revenue'), DB::raw('SUM(order_items.quantity) as items_sold'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();
        
        // Hourly Sales Today
        $hourlySales = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $startTime = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
            $endTime = str_pad($hour + 1, 2, '0', STR_PAD_LEFT) . ':00:00';
            
            $sales = Order::whereDate('created_at', $today)
                ->whereTime('created_at', '>=', $startTime)
                ->whereTime('created_at', '<', $endTime)
                ->sum('total');
            $hourlySales[] = [
                'hour' => str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00',
                'sales' => round($sales, 2)
            ];
        }
        
        // Conversion Rate
        $totalVisitors = 1000; // This should come from analytics
        $conversionRate = $totalVisitors > 0 ? ($ordersTotal / $totalVisitors) * 100 : 0;
        
        // Revenue by Payment Method
        $revenueByPayment = Order::select('payment_method', DB::raw('SUM(total) as revenue'))
            ->where('payment_status', 'paid')
            ->groupBy('payment_method')
            ->get();
        
        // Customer Lifetime Value
        $avgCustomerLifetimeValue = User::where('is_admin', false)
            ->withSum(['orders' => function($q) {
                $q->where('status', '!=', 'cancelled');
            }], 'total')
            ->get()
            ->avg('orders_sum_total') ?? 0;
        
        // Repeat Customer Rate
        $repeatCustomers = User::where('is_admin', false)
            ->has('orders', '>', 1)
            ->count();
        $repeatCustomerRate = $customersTotal > 0 ? ($repeatCustomers / $customersTotal) * 100 : 0;
        
        // Average Fulfillment Time
        $avgFulfillmentTime = Order::where('status', 'delivered')
            ->whereNotNull('updated_at')
            ->get()
            ->avg(function($order) {
                return $order->created_at->diffInHours($order->updated_at);
            }) ?? 0;
        
        // Revenue Goals (from database settings)
        $monthlyGoal = Setting::get('monthly_sales_goal', 50000);
        $yearlyGoal = Setting::get('yearly_sales_goal', 500000);
        $monthlyProgress = $monthlyGoal > 0 ? ($salesMonth / $monthlyGoal) * 100 : 0;
        $yearlyProgress = $yearlyGoal > 0 ? ($salesYear / $yearlyGoal) * 100 : 0;
        
        // Top Performing Days
        $topDays = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->where('created_at', '>=', $thisMonth)
            ->groupBy('date')
            ->orderBy('revenue', 'desc')
            ->take(5)
            ->get();
        
        // Product Views (mock data - should come from analytics)
        $productViews = Product::inRandomOrder()->take(5)->get()->map(function($product) {
            $product->views = rand(100, 1000);
            $product->conversion = rand(1, 10);
            return $product;
        });
        
        // Abandoned Carts (mock - should come from cart tracking)
        $abandonedCarts = rand(10, 50);
        $abandonedCartValue = rand(1000, 5000);
        
        // Customer Segments
        $newCustomers = User::where('is_admin', false)
            ->where('created_at', '>=', $thisMonth)
            ->count();
        $returningCustomers = $customersActive - $newCustomers;
        
        // Sales by Hour of Day (Last 7 days)
        $salesByHour = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $sales = Order::where('created_at', '>=', Carbon::now()->subDays(7))
                ->whereTime('created_at', '>=', str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00')
                ->whereTime('created_at', '<', str_pad($hour + 1, 2, '0', STR_PAD_LEFT) . ':00:00')
                ->avg('total') ?? 0;
            $salesByHour[] = [
                'hour' => $hour,
                'sales' => round($sales, 2)
            ];
        }
        
        // Pending Actions
        $pendingPayments = Order::where('payment_status', 'pending')->count();
        $pendingShipments = Order::where('status', 'confirmed')->count();
        $pendingReviews = 0; // Should come from reviews table
        
        return view('admin.dashboard', compact(
            'salesToday', 'salesYesterday', 'salesWeek', 'salesMonth', 'salesYear', 'totalRevenue',
            'salesGrowthDaily', 'salesGrowthWeekly', 'salesGrowthMonthly',
            'ordersToday', 'ordersWeek', 'ordersMonth', 'ordersTotal',
            'ordersGrowthDaily', 'ordersPending', 'ordersProcessing', 'ordersShipped', 'ordersDelivered', 'ordersCancelled',
            'customersTotal', 'customersToday', 'customersWeek', 'customersMonth', 'customersActive',
            'avgOrderValue', 'avgOrderItems',
            'totalProducts', 'activeProducts', 'featuredProducts', 'outOfStock', 'lowStock',
            'totalCategories', 'activeCategories',
            'recentOrders', 'topProducts', 'topCustomers',
            'lowStockProducts', 'outOfStockProducts', 'recentCustomers',
            'monthlyGoal', 'yearlyGoal', 'monthlyProgress', 'yearlyProgress',
            'pendingPayments', 'pendingShipments', 'pendingReviews'
        ));
    }
    
    public function analytics()
    {
        // Check if user is admin
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }
        
        // Get date ranges
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        
        // Sales data
        $salesMonth = Order::where('created_at', '>=', $thisMonth)->sum('total');
        $ordersMonth = Order::where('created_at', '>=', $thisMonth)->count();
        $avgOrderValue = Order::avg('total') ?? 0;
        
        // Repeat customer rate
        $customersTotal = User::where('is_admin', false)->count();
        $repeatCustomers = User::where('is_admin', false)->has('orders', '>', 1)->count();
        $repeatCustomerRate = $customersTotal > 0 ? ($repeatCustomers / $customersTotal) * 100 : 0;
        
        // Sales Chart Data (Last 30 days)
        $salesChartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = Order::whereDate('created_at', $date)->sum('total');
            $salesChartData[] = [
                'date' => $date->format('M d'),
                'sales' => round($sales, 2)
            ];
        }
        
        // Monthly Sales Comparison (Last 12 months)
        $monthlySalesData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $sales = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total');
            $monthlySalesData[] = [
                'month' => $month->format('M Y'),
                'sales' => round($sales, 2)
            ];
        }
        
        // Order Status Distribution
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        // Payment Method Distribution
        $ordersByPayment = Order::select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->get();
        
        // Category Performance
        $categoryPerformance = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();
        
        // Hourly Sales Today
        $hourlySales = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $startTime = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
            $endTime = str_pad($hour + 1, 2, '0', STR_PAD_LEFT) . ':00:00';
            $sales = Order::whereDate('created_at', $today)
                ->whereTime('created_at', '>=', $startTime)
                ->whereTime('created_at', '<', $endTime)
                ->sum('total');
            $hourlySales[] = [
                'hour' => str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00',
                'sales' => round($sales, 2)
            ];
        }
        
        // Revenue by Payment Method
        $revenueByPayment = Order::select('payment_method', DB::raw('SUM(total) as revenue'))
            ->where('payment_status', 'paid')
            ->groupBy('payment_method')
            ->get();
        
        // Customer Segments
        $newCustomers = User::where('is_admin', false)
            ->where('created_at', '>=', $thisMonth)
            ->count();
        $customersActive = User::where('is_admin', false)
            ->whereHas('orders', function($q) use ($thisMonth) {
                $q->where('created_at', '>=', $thisMonth);
            })->count();
        $returningCustomers = $customersActive - $newCustomers;
        
        // Sales by Hour of Day (Last 7 days)
        $salesByHour = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $sales = Order::where('created_at', '>=', Carbon::now()->subDays(7))
                ->whereTime('created_at', '>=', str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00')
                ->whereTime('created_at', '<', str_pad($hour + 1, 2, '0', STR_PAD_LEFT) . ':00:00')
                ->avg('total') ?? 0;
            $salesByHour[] = [
                'hour' => $hour,
                'sales' => round($sales, 2)
            ];
        }
        
        // Top Performing Days
        $topDays = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->where('created_at', '>=', $thisMonth)
            ->groupBy('date')
            ->orderBy('revenue', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.analytics', compact(
            'salesMonth', 'ordersMonth', 'avgOrderValue', 'repeatCustomerRate',
            'salesChartData', 'monthlySalesData',
            'ordersByStatus', 'ordersByPayment',
            'categoryPerformance', 'hourlySales', 'revenueByPayment',
            'topDays', 'newCustomers', 'returningCustomers', 'salesByHour'
        ));
    }
}
