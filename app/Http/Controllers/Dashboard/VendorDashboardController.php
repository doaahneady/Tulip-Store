<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    public function index()
    {
        // Product Metrics
        $productMetrics = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'out_of_stock' => Product::where('stock_quantity', 0)->count(),
            'low_stock' => Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<', 10)->count(),
            'total_categories' => Category::count(),
        ];

        // Sales Metrics
        $totalColumn = \Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : 'total';
        $salesMetrics = [
            'total_sales' => Order::where('status', 'delivered')->sum($totalColumn),
            'sales_today' => Order::whereDate('created_at', today())->where('status', 'delivered')->sum($totalColumn),
            'sales_this_week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()])->where('status', 'delivered')->sum($totalColumn),
            'sales_this_month' => Order::whereMonth('created_at', now()->month)->where('status', 'delivered')->sum($totalColumn),
            'total_items_sold' => OrderItem::whereHas('order', fn ($q) => $q->where('status', 'delivered'))->sum('quantity'),
        ];

        // Top Selling Products
        $topProducts = Product::withSum(['orderItems as total_sold' => function ($q) {
            $q->whereHas('order', fn ($o) => $o->where('status', 'delivered'));
        }], 'quantity')
            ->withSum(['orderItems as total_revenue' => function ($q) {
                $q->whereHas('order', fn ($o) => $o->where('status', 'delivered'));
            }], 'subtotal')
            ->orderBy('total_sold', 'desc')->take(10)->get();

        // Low Stock Products
        $lowStockProducts = Product::where('stock_quantity', '<', 10)
            ->orderBy('stock_quantity', 'asc')->take(10)->get();

        // Categories with Product Count
        $categories = Category::withCount('products')
            ->withSum(['products' => fn ($q) => $q->where('is_active', true)], 'stock_quantity')
            ->orderBy('products_count', 'desc')->get();

        // Recent Orders
        $recentOrders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')->take(10)->get();

        // Daily Sales Chart (Last 7 days)
        $dailySales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailySales[] = [
                'date' => $date->format('M d'),
                'sales' => Order::whereDate('created_at', $date)->where('status', 'delivered')->sum($totalColumn),
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        }

        // All Products
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(20);

        return view('dashboards.vendor.index', compact('productMetrics', 'salesMetrics', 'topProducts', 'lowStockProducts', 'categories', 'recentOrders', 'dailySales', 'products'));
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product->update(['stock_quantity' => $request->stock_quantity]);

        return back()->with('success', 'Stock updated successfully');
    }
}
