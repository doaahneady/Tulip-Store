<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $productsBase = Product::query()
            ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', 'store'));
        $categoriesBase = Category::query()
            ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', 'store'));

        // Product Metrics
        $productMetrics = [
            'total_products' => (clone $productsBase)->count(),
            'active_products' => (clone $productsBase)->where('is_active', true)->count(),
            'out_of_stock' => (clone $productsBase)->where('stock_quantity', 0)->count(),
            'low_stock' => (clone $productsBase)->where('stock_quantity', '>', 0)->where('stock_quantity', '<', 10)->count(),
            'total_categories' => (clone $categoriesBase)->count(),
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
        $topProducts = (clone $productsBase)->withSum(['orderItems as total_sold' => function ($q) {
            $q->whereHas('order', fn ($o) => $o->where('status', 'delivered'));
        }], 'quantity')
            ->withSum(['orderItems as total_revenue' => function ($q) {
                $q->whereHas('order', fn ($o) => $o->where('status', 'delivered'));
            }], 'subtotal')
            ->orderBy('total_sold', 'desc')->take(10)->get();

        // Low Stock Products
        $lowStockProducts = (clone $productsBase)->where('stock_quantity', '<', 10)
            ->orderBy('stock_quantity', 'asc')->take(10)->get();

        // Categories with Product Count
        $categories = (clone $categoriesBase)->withCount(['products' => function ($q) {
            if (Schema::hasColumn('products', 'market')) {
                $q->where('market', 'store');
            }
        }])
            ->withSum(['products' => function ($q) {
                $q->where('is_active', true);
                if (Schema::hasColumn('products', 'market')) {
                    $q->where('market', 'store');
                }
            }], 'stock_quantity')
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
        $products = (clone $productsBase)->with('category')->orderBy('created_at', 'desc')->paginate(20);

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
