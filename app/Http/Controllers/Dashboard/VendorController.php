<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FinancialTransaction;
use App\Models\InventoryAlert;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Payout;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SalesForecast;
use App\Models\Store;
use App\Models\AuditLog;
use App\Models\DashboardNotification;
use App\Models\Employee;
use App\Models\Trader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth('employee')->check() || auth('trader')->check()) {
                return $next($request);
            }

            return redirect()->route('trader.login.form');
        });
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
        if (! $store) {
            return [];
        }

        $storeOrdersBase = Order::query()
            ->whereHas('items.product', function ($q) use ($store) {
                $q->where('store_id', $store->id);
            });

        $terminal = (array) config('order_statuses.terminal', ['done']);
        $aliases = (array) config('order_statuses.aliases', []);
        foreach ($aliases as $from => $to) {
            if (in_array($to, $terminal, true)) {
                $terminal[] = $from;
            }
        }
        $terminal = array_values(array_unique(array_map('strval', $terminal)));
        $completedStatusesForDashboard = array_values(array_unique(array_merge(['delivered'], $terminal)));

        $revenueService = app(\App\Services\RevenueMetricsService::class);

        return [
            // Sales Metrics - Real data
            'total_orders' => (clone $storeOrdersBase)->count(),
            'monthly_orders' => (clone $storeOrdersBase)
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),
            'pending_orders' => (clone $storeOrdersBase)
                ->whereIn('status', ['pending', 'confirmed', 'processing'])
                ->count(),
            'completed_orders' => (clone $storeOrdersBase)
                ->whereIn('status', $completedStatusesForDashboard)
                ->count(),

            // Revenue Metrics - Real data
            // Product-only revenue (excludes delivery cost)
            'total_revenue' => $revenueService->sumProductRevenue(null, null, (int) $store->id),
            'monthly_revenue' => $revenueService->sumProductRevenue(now()->startOfMonth(), now()->endOfMonth(), (int) $store->id),
            'available_balance' => $revenueService->sumProductRevenue(null, null, (int) $store->id)
                - Payout::where('store_id', $store->id)->where('status', 'processed')->sum('amount'),
            'pending_payout' => Payout::where('store_id', $store->id)
                ->where('status', 'pending')->sum('amount'),
            'earnings_ex_delivery_total' => $revenueService->sumProductRevenueForStatuses($completedStatusesForDashboard, null, null, (int) $store->id),
            'earnings_ex_delivery_month' => $revenueService->sumProductRevenueForStatuses($completedStatusesForDashboard, now()->startOfMonth(), now()->endOfMonth(), (int) $store->id),

            // Product Metrics - Real data
            'total_products' => Product::where('store_id', $store->id)->count(),
            'active_products' => Product::where('store_id', $store->id)
                ->when(Schema::hasColumn('products', 'is_active'), function ($q) {
                    $q->where('is_active', true);
                })
                ->when(! Schema::hasColumn('products', 'is_active') && Schema::hasColumn('products', 'status'), function ($q) {
                    $q->where('status', 'active');
                })
                ->count(),
            'low_stock_products' => Product::where('store_id', $store->id)
                ->when(Schema::hasColumn('products', 'stock_quantity') && Schema::hasColumn('products', 'low_stock_threshold'), function ($q) {
                    $q->whereRaw('stock_quantity <= low_stock_threshold');
                }, function ($q) {
                    if (Schema::hasColumn('products', 'stock') && Schema::hasColumn('products', 'low_stock_threshold')) {
                        $q->whereRaw('stock <= low_stock_threshold');
                    }
                })
                ->count(),
            'out_of_stock_products' => Product::where('store_id', $store->id)
                ->when(Schema::hasColumn('products', 'stock_quantity'), function ($q) {
                    $q->where('stock_quantity', 0);
                }, function ($q) {
                    if (Schema::hasColumn('products', 'stock')) {
                        $q->where('stock', 0);
                    }
                })
                ->count(),

            // Performance Metrics - Real data
            'avg_order_value' => (function () use ($store) {
                $total = app(\App\Services\RevenueMetricsService::class)->sumProductRevenue(now()->subDays(30)->startOfDay(), now()->endOfDay(), (int) $store->id);
                $count = Order::where('store_id', $store->id)->whereIn('status', (array) config('order_statuses.terminal', ['delivered', 'done']))->count();
                return $count > 0 ? ($total / $count) : 0;
            })(),
            'conversion_rate' => $this->calculateConversionRate($store->id),
            'customer_satisfaction' => \App\Models\Review::where('is_approved', true)->whereHas('product', function ($q) use ($store) {
                $q->where('store_id', $store->id);
            })->avg('rating') ?? 0,

            // Recent Activity - Real data
            'recent_orders' => Order::query()
                ->whereHas('items.product', function ($q) use ($store) {
                    $q->where('store_id', $store->id);
                })
                ->with([
                    'customer',
                    'items' => function ($q) use ($store) {
                        $q->whereHas('product', function ($p) use ($store) {
                            $p->where('store_id', $store->id);
                        })->with('product');
                    },
                ])
                ->orderByDesc('created_at')
                ->take(5)
                ->get(),
            'top_products' => $this->getTopProducts($store->id, 5),
            'recent_reviews' => \App\Models\Review::where('is_approved', true)->whereHas('product', function ($q) use ($store) {
                $q->where('store_id', $store->id);
            })->latest()->take(5)->get(),
        ];
    }

    private function orderTotalColumn(): string
    {
        return \Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : 'total';
    }

    private function calculateConversionRate($storeId)
    {
        // Simplified conversion rate calculation
        $views = \App\Models\ProductPerformanceMetric::whereHas('product', function ($q) use ($storeId) {
            $q->where('store_id', $storeId);
        })->sum('views');
        $purchases = \App\Models\ProductPerformanceMetric::whereHas('product', function ($q) use ($storeId) {
            $q->where('store_id', $storeId);
        })->sum('purchases');

        return $views > 0 ? round(($purchases / $views) * 100, 2) : 0;
    }

    private function getTopProducts($storeId, $limit = 5)
    {
        return Product::where('store_id', $storeId)
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Inventory Management
     */
    public function products(Request $request)
    {
        $store = $this->getUserStore();

        $products = Product::query()
            ->when(! $store, function ($q) {
                $q->whereRaw('1 = 0');
            }, function ($q) use ($store) {
                $q->where('store_id', $store->id);
            })
            ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', 'store'))
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
                    $stockCol = Schema::hasColumn('products', 'stock_quantity') ? 'stock_quantity' : (Schema::hasColumn('products', 'stock') ? 'stock' : null);
                    $thresholdCol = Schema::hasColumn('products', 'low_stock_threshold') ? 'low_stock_threshold' : null;
                    if ($stockCol && $thresholdCol) {
                        $query->whereRaw($stockCol.' <= '.$thresholdCol);
                    } elseif ($stockCol) {
                        $query->where($stockCol, '<=', 10);
                    }
                } elseif ($stockStatus === 'out') {
                    $stockCol = Schema::hasColumn('products', 'stock_quantity') ? 'stock_quantity' : (Schema::hasColumn('products', 'stock') ? 'stock' : null);
                    if ($stockCol) {
                        $query->where($stockCol, 0);
                    }
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = Category::when(Schema::hasColumn('categories', 'is_active'), function ($q) {
            $q->where('is_active', true);
        })
            ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', 'store'))
            ->get();

        return view('dashboards.vendor.products', compact('products', 'categories', 'store'));
    }

    public function purchaseOrders(Request $request)
    {
        $store = $this->getUserStore();

        $orders = PurchaseOrder::where('store_id', $store->id)
            ->with(['items.product'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($orders);
        }

        $statusOptions = [
            'pending_approval',
            'approved',
            'ordered',
            'shipped',
            'partially_received',
            'received',
            'cancelled',
        ];

        $products = Product::query()
            ->where('store_id', $store->id)
            ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', 'store'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('dashboards.vendor.purchase-orders', compact('store', 'orders', 'statusOptions', 'products'));
    }

    public function createPurchaseOrder(Request $request)
    {
        $store = $this->getUserStore();

        $request->validate([
            'supplier_name' => 'nullable|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::create([
                'store_id' => $store->id,
                'supplier_name' => $request->supplier_name,
                'supplier_contact' => $request->supplier_contact,
                'status' => 'pending_approval',
                'expected_delivery_date' => $request->expected_delivery_date,
                'total_cost' => 0,
                'created_by' => Auth::id(),
                'notes' => $request->notes,
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $product = Product::where('id', $item['product_id'])->where('store_id', $store->id)->first();
                if (! $product) {
                    abort(403, 'Unauthorized product for this store');
                }

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                ]);

                $total += ($item['quantity'] * $item['unit_cost']);
            }

            $po->update(['total_cost' => $total]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'purchase_order' => $po->load('items.product')], 201);
            }

            return redirect()
                ->route('dashboard.vendor.purchase-orders')
                ->with('success', 'Purchase order created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->withErrors(['purchase_order' => $e->getMessage()])->withInput();
        }
    }

    public function receivePurchaseOrder(Request $request, PurchaseOrder $purchaseOrder)
    {
        $store = $this->getUserStore();

        if ($purchaseOrder->store_id !== $store->id) {
            abort(403, 'Unauthorized access to purchase order');
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'nullable|exists:purchase_order_items,id',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.received_quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->items as $data) {
                $item = null;
                if (! empty($data['item_id'])) {
                    $item = PurchaseOrderItem::where('id', $data['item_id'])
                        ->where('purchase_order_id', $purchaseOrder->id)
                        ->firstOrFail();
                } elseif (! empty($data['product_id'])) {
                    $item = PurchaseOrderItem::where('purchase_order_id', $purchaseOrder->id)
                        ->where('product_id', $data['product_id'])
                        ->firstOrFail();
                }

                $receivable = max(0, $item->quantity - $item->received_quantity);
                $receiveQty = min($receivable, (int) $data['received_quantity']);
                if ($receiveQty <= 0) {
                    continue;
                }

                $item->update([
                    'received_quantity' => $item->received_quantity + $receiveQty,
                ]);

                $product = Product::find($item->product_id);
                InventoryMovement::recordMovement($product, 'in', $receiveQty, 'purchase_order_receipt', null, 'PO '.$purchaseOrder->id);

                if (DB::getSchemaBuilder()->hasTable('inventory_alerts')) {
                    if ($product->stock_quantity >= $product->low_stock_threshold) {
                        InventoryAlert::where('product_id', $product->id)
                            ->where('is_resolved', false)
                            ->update([
                                'is_resolved' => true,
                                'resolved_at' => now(),
                                'resolution_notes' => 'Restocked via PO '.$purchaseOrder->id,
                            ]);
                    }
                }
            }

            $items = $purchaseOrder->items()->get();
            $allReceived = $items->every(function ($i) {
                return $i->received_quantity >= $i->quantity;
            });

            $purchaseOrder->update([
                'status' => $allReceived ? 'received' : 'partially_received',
                'received_at' => $allReceived ? now() : $purchaseOrder->received_at,
            ]);

            if ($allReceived) {
                $txnData = [
                    'store_id' => $store->id,
                    'type' => 'adjustment',
                    'status' => 'pending',
                    'amount' => $purchaseOrder->total_cost,
                    'description' => 'Supplier payment for PO '.$purchaseOrder->id,
                ];

                if (\Illuminate\Support\Facades\Schema::hasColumn('financial_transactions', 'transaction_id')) {
                    $txnData['transaction_id'] = FinancialTransaction::generateTransactionId('supplier_payment');
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('financial_transactions', 'currency')) {
                    $txnData['currency'] = 'USD';
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('financial_transactions', 'metadata')) {
                    $txnData['metadata'] = [
                        'purchase_order_id' => $purchaseOrder->id,
                        'supplier_name' => $purchaseOrder->supplier_name,
                    ];
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('financial_transactions', 'approval_status')) {
                    $txnData['approval_status'] = 'pending';
                }

                FinancialTransaction::create($txnData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'purchase_order' => $purchaseOrder->load('items.product'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $ownerUser = auth('trader')->user() ?? auth('employee')->user();
            abort_unless($ownerUser && ($ownerUser->is_trader ?? false), 403);

            $trader = Schema::hasTable('traders')
                ? Trader::where('user_id', $ownerUser->id ?? $ownerUser->user_id)->first()
                : null;
            abort_unless($trader && ($trader->status ?? null) === Trader::STATUS_APPROVED, 403);

            $data = [
                'store_id' => $store->id,
                'name' => $request->name,
                'slug' => Str::slug($request->name).'-'.Str::random(6),
                'description' => $request->description,
                'category_id' => $request->category_id,
                'sku' => $this->generateSKU($store->id),
                'price' => $request->price,
                'cost_price' => $request->cost_price,
                'stock_quantity' => $request->stock_quantity,
                'low_stock_threshold' => $request->low_stock_threshold,
                'weight' => $request->weight,
            ];

            if (Schema::hasColumn('products', 'short_description')) {
                $data['short_description'] = Str::limit($request->description, 200);
            }
            if (Schema::hasColumn('products', 'status')) {
                $data['status'] = 'pending';
            }
            if (Schema::hasColumn('products', 'is_active')) {
                $data['is_active'] = false;
            }
            if (Schema::hasColumn('products', 'trader_id')) {
                $data['trader_id'] = $trader->id;
            }
            if (Schema::hasColumn('products', 'is_trader_product')) {
                $data['is_trader_product'] = true;
            }
            if (Schema::hasColumn('products', 'reviewed_by')) {
                $data['reviewed_by'] = null;
            }
            if (Schema::hasColumn('products', 'reviewed_at')) {
                $data['reviewed_at'] = null;
            }
            if (Schema::hasColumn('products', 'rejection_reason')) {
                $data['rejection_reason'] = null;
            }

            $product = Product::create($data);

            // Handle image uploads
            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $images[] = $path;
                }
                $product->update(['images' => $images]);
            }

            $this->notifyCustomerSupportProductReview($product);

            DB::commit();

            return redirect()->route('dashboard.vendor.products')
                ->with('success', 'Product submitted. Waiting for support approval.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Failed to create product: '.$e->getMessage());
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
            'status' => 'required|in:active,inactive,out_of_stock',
        ]);

        $updates = $request->only([
            'name', 'description', 'category_id', 'price', 'cost_price',
            'stock_quantity', 'low_stock_threshold',
        ]);

        $isTraderSession = auth('trader')->check();
        if ($isTraderSession) {
            if (Schema::hasColumn('products', 'status')) {
                $updates['status'] = 'pending';
            }
            if (Schema::hasColumn('products', 'is_active')) {
                $updates['is_active'] = false;
            }
            if (Schema::hasColumn('products', 'reviewed_by')) {
                $updates['reviewed_by'] = null;
            }
            if (Schema::hasColumn('products', 'reviewed_at')) {
                $updates['reviewed_at'] = null;
            }
            if (Schema::hasColumn('products', 'rejection_reason')) {
                $updates['rejection_reason'] = null;
            }
        } else {
            $updates['status'] = $request->status;
        }

        $product->update($updates);

        if ($isTraderSession) {
            $this->notifyCustomerSupportProductReview($product);
        }

        return redirect()->route('dashboard.vendor.products')
            ->with('success', $isTraderSession ? 'Changes submitted. Waiting for support approval.' : 'Product updated successfully!');
    }

    public function deleteProduct(Product $product)
    {
        $store = $this->getUserStore();

        if ($product->store_id !== $store->id) {
            abort(403, 'Unauthorized access to product');
        }

        $product->delete();

        return redirect()->route('dashboard.vendor.products')
            ->with('success', 'Product deleted successfully!');
    }

    public function updateStock(Request $request, Product $product)
    {
        $store = $this->getUserStore();

        if ($product->store_id !== $store->id) {
            abort(403, 'Unauthorized access to product');
        }

        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product->update(['stock_quantity' => $validated['stock_quantity']]);

        return back()->with('success', 'Stock updated successfully');
    }

    public function salesForecasts(Request $request)
    {
        $store = $this->getUserStore();

        $forecasts = Schema::hasTable('sales_forecasts')
            ? SalesForecast::with('product')
                ->where('store_id', $store->id)
                ->orderBy('forecast_period', 'desc')
                ->paginate(20)
                ->withQueryString()
            : collect();

        return view('dashboards.vendor.sales-forecasts', compact('store', 'forecasts'));
    }

    public function productPerformanceMetrics(Request $request)
    {
        $store = $this->getUserStore();

        $products = Product::withCount('orderItems')
            ->where('store_id', $store->id)
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->orderByDesc('order_items_count')
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total_products' => Product::where('store_id', $store->id)->count(),
            'active_products' => Product::where('store_id', $store->id)
                ->when(Schema::hasColumn('products', 'is_active'), function ($q) {
                    $q->where('is_active', true);
                })
                ->when(! Schema::hasColumn('products', 'is_active') && Schema::hasColumn('products', 'status'), function ($q) {
                    $q->where('status', 'active');
                })
                ->count(),
            'low_stock' => Product::where('store_id', $store->id)
                ->when(Schema::hasColumn('products', 'stock_quantity'), function ($q) {
                    $q->where('stock_quantity', '>', 0)->where('stock_quantity', '<', 10);
                }, function ($q) {
                    if (Schema::hasColumn('products', 'stock')) {
                        $q->where('stock', '>', 0)->where('stock', '<', 10);
                    }
                })
                ->count(),
            'out_of_stock' => Product::where('store_id', $store->id)
                ->when(Schema::hasColumn('products', 'stock_quantity'), function ($q) {
                    $q->where('stock_quantity', 0);
                }, function ($q) {
                    if (Schema::hasColumn('products', 'stock')) {
                        $q->where('stock', 0);
                    }
                })
                ->count(),
        ];

        return view('dashboards.vendor.product-performance-metrics', compact('store', 'products', 'summary'));
    }

    /**
     * Order Management
     */
    public function orders(Request $request)
    {
        $store = $this->getUserStore();

        $storeScopeQuery = Order::query()
            ->where(function ($q) use ($store) {
                $q->where('store_id', $store->id)
                    ->orWhereHas('items.product', function ($p) use ($store) {
                        $p->where('store_id', $store->id);
                    });
            });

        $ordersQuery = (clone $storeScopeQuery)
            ->with([
                'customer',
                'items' => function ($q) use ($store) {
                    $q->whereHas('product', function ($p) use ($store) {
                        $p->where('store_id', $store->id);
                    })->with('product');
                },
            ])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('recipient_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->orWhereHas('customer', function ($c) use ($search) {
                    $c->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
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
            });

        $orders = (clone $ordersQuery)
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();
        $orderIds = $orders->getCollection()->pluck('id')->values();

        $orderLogs = $orderIds->isEmpty()
            ? collect()
            : AuditLog::query()
                ->whereIn('model_id', $orderIds)
                ->whereIn('model_type', ['Order', Order::class])
                ->orderByDesc('created_at')
                ->get()
                ->groupBy('model_id');

        $statusOptions = (clone $storeScopeQuery)->select('status')->distinct()->pluck('status')->filter()->values();
        $paymentOptions = (clone $storeScopeQuery)->select('payment_status')->distinct()->pluck('payment_status')->filter()->values();

        $orderStats = [
            'total' => (clone $storeScopeQuery)->toBase()->getCountForPagination(),
            'pending' => (clone $storeScopeQuery)->where('status', 'pending')->toBase()->getCountForPagination(),
            'processing' => (clone $storeScopeQuery)->where('status', 'processing')->toBase()->getCountForPagination(),
            'delivered' => (clone $storeScopeQuery)->whereIn('status', (array) config('order_statuses.terminal', ['delivered', 'done']))->toBase()->getCountForPagination(),
        ];

        return view('dashboards.vendor.orders', compact('orders', 'orderStats', 'store', 'orderLogs', 'statusOptions', 'paymentOptions'));
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
            'status' => 'required|string',
        ]);

        $statusManager = app(\App\Services\OrderStatusManager::class);
        $from = $statusManager->normalize((string) $order->status);
        $to = $statusManager->normalize((string) $request->status);
        $canonical = (array) config('order_statuses.canonical', []);
        if (! in_array($to, $canonical, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status',
            ], 422);
        }
        if (! in_array($to, $statusManager->allowedNext($from), true)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid order status transition',
            ], 422);
        }

        $order->update([
            'status' => $to,
            'admin_notes' => $request->notes,
        ]);

        // Trigger real-time update
        broadcast(new \App\Events\OrderStatusUpdated($order));

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
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
            'amount' => 'required|numeric|min:10|max:'.$store->available_balance,
            'bank_details' => 'required|array',
            'bank_details.account_name' => 'required|string',
            'bank_details.account_number' => 'required|string',
            'bank_details.bank_name' => 'required|string',
            'bank_details.routing_number' => 'required|string',
            'notes' => 'nullable|string|max:500',
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
                'status' => 'pending',
            ]);

            // Create financial transaction
            FinancialTransaction::create([
                'transaction_id' => 'payout_'.$payout->id.'_'.time(),
                'store_id' => $store->id,
                'user_id' => Auth::id(),
                'type' => 'payout',
                'amount' => $request->amount,
                'status' => 'pending',
                'approval_status' => 'pending',
                'description' => 'Payout request for store: '.$store->name,
                'metadata' => [
                    'payout_id' => $payout->id,
                    'bank_details' => $request->bank_details,
                ],
            ]);

            // Update store balance
            $store->update([
                'available_balance' => $store->available_balance - $request->amount,
                'pending_payout' => $store->pending_payout + $request->amount,
            ]);

            DB::commit();

            // Notify finance team
            broadcast(new \App\Events\PayoutRequested($payout));

            return redirect()->route('vendor.earnings')
                ->with('success', 'Payout request submitted successfully!');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Failed to submit payout request: '.$e->getMessage());
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
    private function notifyCustomerSupportProductReview(Product $product): void
    {
        if (! Schema::hasTable('dashboard_notifications') || ! Schema::hasTable('employees')) {
            return;
        }

        $csEmployees = Employee::query()
            ->when(Schema::hasColumn('employees', 'is_cs'), fn ($q) => $q->where('is_cs', true))
            ->when(Schema::hasColumn('employees', 'status'), fn ($q) => $q->where('status', 'active'))
            ->get(['id']);

        if ($csEmployees->isEmpty()) {
            return;
        }

        $actionUrl = null;
        try {
            $actionUrl = route('dashboard.cs.trader-products');
        } catch (\Throwable $e) {
            $actionUrl = '/dashboard/cs/trader-products';
        }

        foreach ($csEmployees as $emp) {
            DashboardNotification::create([
                'dashboard_type' => 'cs',
                'user_type' => Employee::class,
                'user_id' => $emp->id,
                'type' => 'trader_product_review',
                'title' => 'New trader product pending review',
                'message' => 'A trader submitted "'.$product->name.'" for approval.',
                'action_url' => $actionUrl,
                'icon' => 'fa-box',
                'color' => 'amber',
                'is_read' => false,
            ]);
        }
    }

    private function getUserStore()
    {
        $ownerId = null;
        if (auth('trader')->check()) {
            $ownerId = auth('trader')->id();
        } else {
            $employee = auth('employee')->user();
            $ownerId = $employee?->user_id;
        }

        if (! $ownerId) {
            abort(403, 'Unauthorized access');
        }

        $q = Store::query();
        $hasOwnerId = Schema::hasColumn('stores', 'owner_id');
        $hasUserId = Schema::hasColumn('stores', 'user_id');

        if (! $hasOwnerId && ! $hasUserId) {
            abort(500, 'Store ownership columns are not configured');
        }

        if ($hasOwnerId) {
            $q->where('owner_id', $ownerId);
        }
        if ($hasUserId) {
            if ($hasOwnerId) {
                $q->orWhere('user_id', $ownerId);
            } else {
                $q->where('user_id', $ownerId);
            }
        }

        $store = $q->first();
        if (! $store) {
            if (! auth('trader')->check()) {
                abort(403, 'Store not found');
            }

            $storeData = [
                'name' => 'My Store',
                'slug' => 'store-'.Str::lower(Str::random(10)),
            ];

            if (Schema::hasColumn('stores', 'organization_id')) {
                $orgId = DB::table('organizations')->value('id');
                if (! $orgId) {
                    $orgData = [
                        'name' => 'Default Organization',
                        'slug' => 'org-'.Str::lower(Str::random(10)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    if (Schema::hasColumn('organizations', 'status')) {
                        $orgData['status'] = 'active';
                    }
                    $orgId = DB::table('organizations')->insertGetId($orgData);
                }
                $storeData['organization_id'] = $orgId;
            }

            if ($hasOwnerId) {
                $storeData['owner_id'] = $ownerId;
            }
            if ($hasUserId) {
                $storeData['user_id'] = $ownerId;
            }

            if (Schema::hasColumn('stores', 'status')) {
                if (Schema::hasColumn('stores', 'organization_id') && $hasOwnerId && ! $hasUserId) {
                    $storeData['status'] = 'active';
                } else {
                    $storeData['status'] = 'approved';
                }
            }

            $store = Store::create($storeData);
        }

        return $store;
    }

    private function generateSKU($storeId)
    {
        $prefix = 'STR'.str_pad($storeId, 3, '0', STR_PAD_LEFT);
        $suffix = str_pad(Product::where('store_id', $storeId)->count() + 1, 4, '0', STR_PAD_LEFT);

        return $prefix.'-'.$suffix;
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

    /**
     * Inventory Alerts Management
     */
    public function getInventoryAlerts(Request $request)
    {
        $store = $this->getUserStore();

        $alerts = \App\Models\InventoryAlert::whereHas('product', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        })
            ->with('product')
            ->when($request->unresolved_only, function ($query) {
                $query->unresolved();
            })
            ->when($request->severity, function ($query, $severity) {
                $query->where('severity', $severity);
            })
            ->when($request->alert_type, function ($query, $type) {
                $query->where('alert_type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_alerts' => \App\Models\InventoryAlert::whereHas('product', function ($q) use ($store) {
                $q->where('store_id', $store->id);
            })->count(),
            'unresolved_alerts' => \App\Models\InventoryAlert::whereHas('product', function ($q) use ($store) {
                $q->where('store_id', $store->id);
            })->unresolved()->count(),
            'critical_alerts' => \App\Models\InventoryAlert::whereHas('product', function ($q) use ($store) {
                $q->where('store_id', $store->id);
            })->critical()->count(),
        ];

        return response()->json([
            'alerts' => $alerts,
            'stats' => $stats,
        ]);
    }

    public function resolveInventoryAlert(Request $request, \App\Models\InventoryAlert $alert)
    {
        $request->validate([
            'resolution_notes' => 'nullable|string',
        ]);

        $alert->resolve($request->resolution_notes);

        return response()->json(['success' => true]);
    }

    /**
     * Sales Forecasting
     */
    public function getSalesForecasts(Request $request)
    {
        $store = $this->getUserStore();

        $forecasts = \App\Models\SalesForecast::where('store_id', $store->id)
            ->with('product')
            ->when($request->period, function ($query, $period) {
                $query->where('forecast_period', $period);
            })
            ->when($request->product_id, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->orderBy('forecast_period', 'desc')
            ->paginate(20);

        return response()->json($forecasts);
    }

    public function createSalesForecast(Request $request)
    {
        $store = $this->getUserStore();

        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'forecast_period' => 'required|string',
            'forecasted_quantity' => 'required|integer|min:0',
            'forecasted_revenue' => 'required|numeric|min:0',
            'confidence_score' => 'required|numeric|min:0|max:100',
        ]);

        $forecast = \App\Models\SalesForecast::create([
            'store_id' => $store->id,
            'product_id' => $request->product_id,
            'forecast_period' => $request->forecast_period,
            'forecasted_quantity' => $request->forecasted_quantity,
            'forecasted_revenue' => $request->forecasted_revenue,
            'confidence_score' => $request->confidence_score,
            'factors' => $request->factors,
        ]);

        return response()->json(['success' => true, 'forecast' => $forecast]);
    }

    /**
     * Product Performance Metrics
     */
    public function getProductPerformanceMetrics(Request $request)
    {
        $store = $this->getUserStore();

        $metrics = \App\Models\ProductPerformanceMetric::whereHas('product', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        })
            ->with('product')
            ->when($request->product_id, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->where('metric_date', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->where('metric_date', '<=', $date);
            })
            ->orderBy('metric_date', 'desc')
            ->paginate(20);

        return response()->json($metrics);
    }

    public function updateProductPerformanceMetrics(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'metric_date' => 'required|date',
            'views' => 'nullable|integer|min:0',
            'cart_additions' => 'nullable|integer|min:0',
            'purchases' => 'nullable|integer|min:0',
            'revenue' => 'nullable|numeric|min:0',
        ]);

        $metric = \App\Models\ProductPerformanceMetric::updateOrCreate(
            [
                'product_id' => $request->product_id,
                'metric_date' => $request->metric_date,
            ],
            $request->only(['views', 'cart_additions', 'purchases', 'revenue', 'average_rating', 'review_count'])
        );

        $metric->calculateConversionRate();

        return response()->json(['success' => true, 'metric' => $metric]);
    }

    /**
     * Get dashboard metrics API
     */
    public function getDashboardMetrics()
    {
        $store = $this->getUserStore();

        return response()->json($this->getVendorMetrics($store));
    }
}
