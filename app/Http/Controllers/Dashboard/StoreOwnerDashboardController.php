<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AuditService;
use App\Services\Dashboard\StoreOwnerDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Store Owner Dashboard Controller
 * 
 * Handles all store owner dashboard functionality including:
 * - Dashboard overview with store-specific KPIs
 * - Product management (CRUD operations)
 * - Order viewing (store-scoped)
 * - Analytics and reporting
 * 
 * All queries are scoped to the authenticated user's store.
 * 
 * @see Requirements 12.1, 12.2, 12.4
 */
class StoreOwnerDashboardController extends Controller
{
    protected ?int $storeId = null;

    public function __construct(
        protected StoreOwnerDashboardService $storeOwnerService,
        protected AuditService $auditService
    ) {
        // Apply store-owner role middleware to all methods
        $this->middleware('dashboard.role:store-owner,admin');
    }

    /**
     * Get the current user's store ID
     * Ensures all queries are scoped to the owner's store
     * 
     * @return int|null
     */
    protected function getStoreId(): ?int
    {
        if ($this->storeId === null) {
            $user = Auth::user();
            $store = $this->storeOwnerService->getStoreForUser($user);
            $this->storeId = $store?->id;
        }
        return $this->storeId;
    }

    /**
     * Display the store owner dashboard overview
     * Shows KPI cards, charts, and recent activity
     * All data is scoped to the owner's store
     * 
     * @see Requirements 12.1
     */
    public function index(Request $request)
    {
        $storeId = $this->getStoreId();

        if (!$storeId) {
            return redirect()->route('dashboard')
                ->with('error', __('You do not have a store associated with your account.'));
        }

        $period = $request->get('period', 'month');
        $store = $this->storeOwnerService->getStoreForUser(Auth::user());

        $data = [
            'store' => $store,
            'kpis' => $this->storeOwnerService->getKPIMetrics($storeId),
            'revenueChart' => $this->storeOwnerService->getRevenueChartData($storeId, $period),
            'ordersChart' => $this->storeOwnerService->getOrdersChartData($storeId, $period),
            'recentOrders' => $this->storeOwnerService->getRecentOrders($storeId, 10),
            'topProducts' => $this->storeOwnerService->getTopSellingProducts($storeId, 5),
            'period' => $period,
        ];

        return view('dashboard.store-owner.index', $data);
    }

    /**
     * Display product management page
     * Shows paginated list of products with search and filters
     * All products are scoped to the owner's store
     * 
     * @see Requirements 12.4
     */
    public function products(Request $request)
    {
        $storeId = $this->getStoreId();

        if (!$storeId) {
            return redirect()->route('dashboard')
                ->with('error', __('You do not have a store associated with your account.'));
        }

        $filters = [
            'per_page' => $request->get('per_page', 25),
            'search' => $request->get('search'),
            'is_active' => $request->has('is_active') ? (bool) $request->get('is_active') : null,
            'category_id' => $request->get('category_id'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $products = $this->storeOwnerService->getProducts($storeId, $filters);
        $store = $this->storeOwnerService->getStoreForUser(Auth::user());

        return view('dashboard.store-owner.products', [
            'store' => $store,
            'products' => $products,
            'filters' => $filters,
        ]);
    }

    /**
     * Show form to create a new product
     * 
     * @see Requirements 12.4
     */
    public function createProduct()
    {
        $storeId = $this->getStoreId();

        if (!$storeId) {
            return redirect()->route('dashboard')
                ->with('error', __('You do not have a store associated with your account.'));
        }

        $store = $this->storeOwnerService->getStoreForUser(Auth::user());

        return view('dashboard.store-owner.products-create', [
            'store' => $store,
        ]);
    }

    /**
     * Store a new product
     * 
     * @see Requirements 12.4
     */
    public function storeProduct(Request $request)
    {
        $storeId = $this->getStoreId();

        if (!$storeId) {
            return redirect()->route('dashboard')
                ->with('error', __('You do not have a store associated with your account.'));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $product = $this->storeOwnerService->createProduct($storeId, $validated);

        // Log the action
        $this->auditService->log(
            'create',
            'product',
            $product->id,
            ['new_values' => $validated]
        );

        return redirect()->route('store-owner.products')
            ->with('success', __('Product created successfully.'));
    }

    /**
     * Show form to edit a product
     * 
     * @see Requirements 12.4
     */
    public function editProduct(int $productId)
    {
        $storeId = $this->getStoreId();

        if (!$storeId) {
            return redirect()->route('dashboard')
                ->with('error', __('You do not have a store associated with your account.'));
        }

        $products = $this->storeOwnerService->getProducts($storeId, ['per_page' => 1000]);
        $product = $products->firstWhere('id', $productId);

        if (!$product) {
            return redirect()->route('store-owner.products')
                ->with('error', __('Product not found or you do not have permission to edit it.'));
        }

        $store = $this->storeOwnerService->getStoreForUser(Auth::user());

        return view('dashboard.store-owner.products-edit', [
            'store' => $store,
            'product' => $product,
        ]);
    }

    /**
     * Update a product
     * 
     * @see Requirements 12.4
     */
    public function updateProduct(Request $request, int $productId)
    {
        $storeId = $this->getStoreId();

        if (!$storeId) {
            return redirect()->route('dashboard')
                ->with('error', __('You do not have a store associated with your account.'));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $product = $this->storeOwnerService->updateProduct($storeId, $productId, $validated);

        if (!$product) {
            return redirect()->route('store-owner.products')
                ->with('error', __('Product not found or you do not have permission to edit it.'));
        }

        // Log the action
        $this->auditService->log(
            'update',
            'product',
            $productId,
            ['new_values' => $validated]
        );

        return redirect()->route('store-owner.products')
            ->with('success', __('Product updated successfully.'));
    }

    /**
     * Delete a product
     * 
     * @see Requirements 12.4
     */
    public function destroyProduct(int $productId)
    {
        $storeId = $this->getStoreId();

        if (!$storeId) {
            return redirect()->route('dashboard')
                ->with('error', __('You do not have a store associated with your account.'));
        }

        $deleted = $this->storeOwnerService->deleteProduct($storeId, $productId);

        if (!$deleted) {
            return redirect()->route('store-owner.products')
                ->with('error', __('Product not found or you do not have permission to delete it.'));
        }

        // Log the action
        $this->auditService->log(
            'delete',
            'product',
            $productId
        );

        return redirect()->route('store-owner.products')
            ->with('success', __('Product deleted successfully.'));
    }

    /**
     * Display order management page
     * Shows paginated list of orders containing the store's products
     * All orders are scoped to the owner's store
     * 
     * @see Requirements 12.2
     */
    public function orders(Request $request)
    {
        $storeId = $this->getStoreId();

        if (!$storeId) {
            return redirect()->route('dashboard')
                ->with('error', __('You do not have a store associated with your account.'));
        }

        $filters = [
            'per_page' => $request->get('per_page', 25),
            'status' => $request->get('status'),
            'payment_status' => $request->get('payment_status'),
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $orders = $this->storeOwnerService->getOrders($storeId, $filters);
        $store = $this->storeOwnerService->getStoreForUser(Auth::user());

        return view('dashboard.store-owner.orders', [
            'store' => $store,
            'orders' => $orders,
            'filters' => $filters,
        ]);
    }

    /**
     * Display analytics page
     * Shows detailed analytics including top products, revenue trends, etc.
     * All data is scoped to the owner's store
     * 
     * @see Requirements 12.5
     */
    public function analytics(Request $request)
    {
        $storeId = $this->getStoreId();

        if (!$storeId) {
            return redirect()->route('dashboard')
                ->with('error', __('You do not have a store associated with your account.'));
        }

        $period = $request->get('period', 'month');
        $store = $this->storeOwnerService->getStoreForUser(Auth::user());

        $data = [
            'store' => $store,
            'kpis' => $this->storeOwnerService->getKPIMetrics($storeId),
            'revenueChart' => $this->storeOwnerService->getRevenueChartData($storeId, $period),
            'ordersChart' => $this->storeOwnerService->getOrdersChartData($storeId, $period),
            'topProducts' => $this->storeOwnerService->getTopSellingProducts($storeId, 10),
            'productAnalytics' => $this->storeOwnerService->getProductAnalytics($storeId),
            'commissionRate' => $this->storeOwnerService->getCommissionRate($storeId),
            'period' => $period,
        ];

        return view('dashboard.store-owner.analytics', $data);
    }
}
