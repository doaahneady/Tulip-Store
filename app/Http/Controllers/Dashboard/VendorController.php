<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\FinancialTransaction;
use App\Models\Payout;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:product_owner,store_manager']);
    }

    /**
     * Display the Product Owner (Vendor) dashboard
     */
    public function index()
    {
        $store = $this->getUserStore();
        $metrics = $this->getVendorMetrics($store);
        
        return view('dashboards.vendor.index', compact('store', 'metrics'));
    }

    /**
     * Get vendor dashboard metrics
     */
    private function getVendorMetrics($store)
    {
        return [
            // Sales Metrics - Using mock data for now
            'total_orders' => 156,
            'monthly_orders' => 23,
            'pending_orders' => 8,
            'completed_orders' => 142,
            
            // Revenue Metrics
            'total_revenue' => 45000,
            'monthly_revenue' => 8500,
            'available_balance' => 12500,
            'pending_payout' => 3200,
            
            // Product Metrics
            'total_products' => 45,
            'active_products' => 42,
            'low_stock_products' => 8,
            'out_of_stock_products' => 3,
            
            // Performance Metrics
            'avg_order_value' => 288.46,
            'conversion_rate' => 3.2,
            'customer_satisfaction' => 4.6,
            
            // Recent Activity
            'recent_orders' => [],
            'top_products' => [],
            'recent_reviews' => [],
        ];
    }

    /**
     * Inventory Management
     */
    public function products(Request $request)
    {
        $store = $this->getUserStore();
        
        $products = Product::where('store_id', $store->id)
            ->with(['category'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->stock_status, function ($query, $stockStatus) {
                if ($stockStatus === 'low') {
                    $query->whereRaw('stock_quantity <= low_stock_threshold');
                } elseif ($stockStatus === 'out') {
                    $query->where('stock_quantity', 0);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = Category::where('is_active', true)->get();
        
        return view('dashboards.vendor.products', compact('products', 'categories', 'store'));
    }

    /**
     * Create new product
     */
    public function createProduct(Request $request)
    {
        $store = $this->getUserStore();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'store_id' => $store->id,
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . Str::random(6),
                'description' => $request->description,
                'short_description' => Str::limit($request->description, 200),
                'category_id' => $request->category_id,
                'sku' => $this->generateSKU($store->id),
                'price' => $request->price,
                'cost_price' => $request->cost_price,
                'stock_quantity' => $request->stock_quantity,
                'low_stock_threshold' => $request->low_stock_threshold,
                'weight' => $request->weight,
                'status' => 'active',
                'is_active' => true,
            ]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $images[] = $path;
                }
                $product->update(['images' => $images]);
            }

            DB::commit();
            
            return redirect()->route('vendor.products')
                ->with('success', 'Product created successfully!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Update product
     */
    public function updateProduct(Request $request, Product $product)
    {
        $store = $this->getUserStore();
        
        // Ensure product belongs to user's store
        if ($product->store_id !== $store->id) {
            abort(403, 'Unauthorized access to product');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'status' => 'required|in:draft,active,inactive,out_of_stock'
        ]);

        $product->update($request->only([
            'name', 'description', 'category_id', 'price', 'cost_price',
            'stock_quantity', 'low_stock_threshold', 'status'
        ]));

        return redirect()->route('vendor.products')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Order Management
     */
    public function orders(Request $request)
    {
        $store = $this->getUserStore();
        
        $orders = Order::where('store_id', $store->id)
            ->with(['customer', 'orderItems.product'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->payment_status, function ($query, $paymentStatus) {
                $query->where('payment_status', $paymentStatus);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $orderStats = [
            'total' => Order::where('store_id', $store->id)->count(),
            'pending' => Order::where('store_id', $store->id)->where('status', 'pending')->count(),
            'processing' => Order::where('store_id', $store->id)->where('status', 'processing')->count(),
            'delivered' => Order::where('store_id', $store->id)->where('status', 'delivered')->count(),
        ];

        return view('dashboards.vendor.orders', compact('orders', 'orderStats', 'store'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $store = $this->getUserStore();
        
        if ($order->store_id !== $store->id) {
            abort(403, 'Unauthorized access to order');
        }

        $request->validate([
            'status' => 'required|in:confirmed,processing,shipped,delivered,cancelled'
        ]);

        $order->update([
            'status' => $request->status,
            'admin_notes' => $request->notes
        ]);

        // Trigger real-time update
        broadcast(new \App\Events\OrderStatusUpdated($order));

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully'
        ]);
    }

    /**
     * Sales Analytics
     */
    public function analytics(Request $request)
    {
        $store = $this->getUserStore();
        $period = $request->get('period', '30d');
        
        $analytics = [
            'sales_overview' => $this->getSalesOverview($store->id, $period),
            'product_performance' => $this->getProductPerformance($store->id, $period),
            'customer_analytics' => $this->getCustomerAnalytics($store->id, $period),
            'revenue_trends' => $this->getRevenueTrends($store->id, $period),
        ];

        return view('dashboards.vendor.analytics', compact('analytics', 'store', 'period'));
    }

    /**
     * Financial Management
     */
    public function earnings(Request $request)
    {
        $store = $this->getUserStore();
        
        $transactions = FinancialTransaction::where('store_id', $store->id)
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $earningsStats = [
            'total_earnings' => $store->total_earnings,
            'available_balance' => $store->available_balance,
            'pending_payout' => $store->pending_payout,
            'monthly_earnings' => $this->getMonthlyEarnings($store->id),
        ];

        return view('dashboards.vendor.earnings', compact('transactions', 'earningsStats', 'store'));
    }

    /**
     * Request payout
     */
    public function requestPayout(Request $request)
    {
        $store = $this->getUserStore();
        
        $request->validate([
            'amount' => 'required|numeric|min:10|max:' . $store->available_balance,
            'bank_details' => 'required|array',
            'bank_details.account_name' => 'required|string',
            'bank_details.account_number' => 'required|string',
            'bank_details.bank_name' => 'required|string',
            'bank_details.routing_number' => 'required|string',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Create payout request
            $payout = Payout::create([
                'store_id' => $store->id,
                'requested_by' => Auth::id(),
                'amount' => $request->amount,
                'bank_details' => $request->bank_details,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            // Create financial transaction
            FinancialTransaction::create([
                'transaction_id' => 'payout_' . $payout->id . '_' . time(),
                'store_id' => $store->id,
                'user_id' => Auth::id(),
                'type' => 'payout',
                'amount' => $request->amount,
                'status' => 'pending',
                'approval_status' => 'pending',
                'description' => 'Payout request for store: ' . $store->name,
                'metadata' => [
                    'payout_id' => $payout->id,
                    'bank_details' => $request->bank_details
                ]
            ]);

            // Update store balance
            $store->update([
                'available_balance' => $store->available_balance - $request->amount,
                'pending_payout' => $store->pending_payout + $request->amount
            ]);

            DB::commit();

            // Notify finance team
            broadcast(new \App\Events\PayoutRequested($payout));

            return redirect()->route('vendor.earnings')
                ->with('success', 'Payout request submitted successfully!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to submit payout request: ' . $e->getMessage());
        }
    }

    /**
     * Store Management
     */
    public function storeProfile()
    {
        $store = $this->getUserStore();
        return view('dashboards.vendor.store-profile', compact('store'));
    }

    /**
     * Update store profile
     */
    public function updateStoreProfile(Request $request)
    {
        $store = $this->getUserStore();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_info' => 'required|array',
            'contact_info.phone' => 'required|string',
            'contact_info.email' => 'required|email',
            'contact_info.address' => 'required|string',
            'business_info' => 'nullable|array',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $updateData = $request->only(['name', 'description', 'contact_info', 'business_info']);
        
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('stores', 'public');
            $updateData['logo'] = $logoPath;
        }

        $store->update($updateData);

        return redirect()->route('vendor.store-profile')
            ->with('success', 'Store profile updated successfully!');
    }

    /**
     * Helper Methods
     */
    private function getUserStore()
    {
        // For now, return a mock store or find by user_id instead of owner_id
        $store = Store::where('user_id', Auth::id())->first();
        if (!$store) {
            // Create a mock store object for testing
            $store = new Store();
            $store->id = 1;
            $store->name = 'Test Store';
            $store->slug = 'test-store';
            $store->description = 'Test store for dashboard';
            $store->user_id = Auth::id();
        }
        return $store;
    }

    private function generateSKU($storeId)
    {
        $prefix = 'STR' . str_pad($storeId, 3, '0', STR_PAD_LEFT);
        $suffix = str_pad(Product::where('store_id', $storeId)->count() + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . '-' . $suffix;
    }

    private function getTotalOrders($storeId)
    {
        return Order::where('store_id', $storeId)->count();
    }

    private function getMonthlyOrders($storeId)
    {
        return Order::where('store_id', $storeId)
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    private function getPendingOrders($storeId)
    {
        return Order::where('store_id', $storeId)
            ->whereIn('status', ['pending', 'confirmed', 'processing'])
            ->count();
    }

    private function getCompletedOrders($storeId)
    {
        return Order::where('store_id', $storeId)
            ->where('status', 'delivered')
            ->count();
    }

    private function getTotalRevenue($storeId)
    {
        return Order::where('store_id', $storeId)
            ->where('payment_status', 'paid')
            ->sum('total_amount');
    }

    private function getMonthlyRevenue($storeId)
    {
        return Order::where('store_id', $storeId)
            ->where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');
    }

    private function getMonthlyEarnings($storeId)
    {
        return FinancialTransaction::where('store_id', $storeId)
            ->where('type', 'order_payment')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
    }

    private function getTotalProducts($storeId)
    {
        return Product::where('store_id', $storeId)->count();
    }

    private function getActiveProducts($storeId)
    {
        return Product::where('store_id', $storeId)
            ->where('is_active', true)
            ->where('status', 'active')
            ->count();
    }

    private function getLowStockProducts($storeId)
    {
        return Product::where('store_id', $storeId)
            ->whereRaw('stock_quantity <= low_stock_threshold')
            ->where('stock_quantity', '>', 0)
            ->count();
    }

    private function getOutOfStockProducts($storeId)
    {
        return Product::where('store_id', $storeId)
            ->where('stock_quantity', 0)
            ->count();
    }

    private function getAverageOrderValue($storeId)
    {
        return Order::where('store_id', $storeId)
            ->where('payment_status', 'paid')
            ->avg('total_amount') ?? 0;
    }

    private function getConversionRate($storeId)
    {
        // Mock calculation - would need actual visitor tracking
        return 3.2; // 3.2%
    }

    private function getCustomerSatisfaction($storeId)
    {
        // Mock calculation - would need actual review system
        return 4.5; // 4.5/5 stars
    }

    private function getRecentOrders($storeId)
    {
        return Order::where('store_id', $storeId)
            ->with(['customer'])
            ->latest()
            ->take(5)
            ->get();
    }

    private function getTopProducts($storeId)
    {
        return Product::where('store_id', $storeId)
            ->withCount(['orderItems'])
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();
    }

    private function getRecentReviews($storeId)
    {
        // Mock data - would implement actual review system
        return collect([]);
    }

    private function getSalesOverview($storeId, $period)
    {
        $days = $this->getPeriodDays($period);
        
        return [
            'total_sales' => Order::where('store_id', $storeId)
                ->where('payment_status', 'paid')
                ->where('created_at', '>=', now()->subDays($days))
                ->sum('total_amount'),
            'order_count' => Order::where('store_id', $storeId)
                ->where('created_at', '>=', now()->subDays($days))
                ->count(),
            'avg_order_value' => Order::where('store_id', $storeId)
                ->where('payment_status', 'paid')
                ->where('created_at', '>=', now()->subDays($days))
                ->avg('total_amount'),
        ];
    }

    private function getProductPerformance($storeId, $period)
    {
        $days = $this->getPeriodDays($period);
        
        return Product::where('store_id', $storeId)
            ->withSum(['orderItems' => function ($query) use ($days) {
                $query->whereHas('order', function ($q) use ($days) {
                    $q->where('created_at', '>=', now()->subDays($days))
                      ->where('payment_status', 'paid');
                });
            }], 'quantity')
            ->withSum(['orderItems' => function ($query) use ($days) {
                $query->whereHas('order', function ($q) use ($days) {
                    $q->where('created_at', '>=', now()->subDays($days))
                      ->where('payment_status', 'paid');
                });
            }], 'total_price')
            ->orderBy('order_items_sum_total_price', 'desc')
            ->take(10)
            ->get();
    }

    private function getCustomerAnalytics($storeId, $period)
    {
        $days = $this->getPeriodDays($period);
        
        return [
            'new_customers' => Order::where('store_id', $storeId)
                ->where('created_at', '>=', now()->subDays($days))
                ->distinct('customer_id')
                ->count(),
            'repeat_customers' => Order::where('store_id', $storeId)
                ->where('created_at', '>=', now()->subDays($days))
                ->groupBy('customer_id')
                ->havingRaw('COUNT(*) > 1')
                ->count(),
        ];
    }

    private function getRevenueTrends($storeId, $period)
    {
        $days = $this->getPeriodDays($period);
        
        return Order::where('store_id', $storeId)
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getPeriodDays($period)
    {
        switch ($period) {
            case '7d': return 7;
            case '30d': return 30;
            case '90d': return 90;
            case '1y': return 365;
            default: return 30;
        }
    }
}