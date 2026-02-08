<?php

namespace App\Http\Controllers\Trader;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Trader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TraderProductController extends Controller
{
    private function currentTrader(): Trader
    {
        $user = Auth::guard('trader')->user();
        abort_unless($user, 401);
        $trader = Trader::where('user_id', $user->id)->firstOrFail();
        abort_unless($trader->status === Trader::STATUS_APPROVED, 403);

        return $trader;
    }

    private function categories()
    {
        if (! Schema::hasTable('categories')) {
            return collect();
        }

        return Category::query()->orderBy('name')->get();
    }

    public function index(Request $request)
    {
        $trader = $this->currentTrader();

        $products = Product::query()
            ->where('trader_id', $trader->id)
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('trader.products.index', compact('trader', 'products'));
    }

    public function create()
    {
        $trader = $this->currentTrader();
        $categories = $this->categories();

        return view('trader.products.create', compact('trader', 'categories'));
    }

    public function store(Request $request)
    {
        $trader = $this->currentTrader();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'sku' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'track_inventory' => 'nullable|boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $slug = Str::slug($validated['name']);
        if ($slug === '') {
            $slug = 'product';
        }
        $baseSlug = $slug;
        $i = 0;
        while (Product::where('slug', $slug)->exists() && $i < 50) {
            $slug = $baseSlug.'-'.random_int(1000, 9999);
            $i++;
        }

        $sku = $validated['sku'] ?? null;
        if (! $sku) {
            $sku = 'TRD-'.$trader->id.'-'.Str::upper(Str::random(6));
        }

        $imagePath = null;
        if ($request->file('image')) {
            $imagePath = Storage::disk('public')->putFile('products/trader', $request->file('image'));
        }

        $imagePaths = [];
        if ($request->file('images')) {
            foreach ($request->file('images') as $img) {
                if ($img) {
                    $imagePaths[] = Storage::disk('public')->putFile('products/trader', $img);
                }
            }
        }

        $data = [
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'details' => $validated['details'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'trader_id' => $trader->id,
            'sku' => $sku,
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'cost_price' => $validated['cost_price'] ?? null,
            'track_inventory' => (bool) ($validated['track_inventory'] ?? true),
            'stock_quantity' => (int) ($validated['stock_quantity'] ?? 0),
            'low_stock_threshold' => (int) ($validated['low_stock_threshold'] ?? 5),
            'status' => 'pending',
        ];
        if (Schema::hasColumn('products', 'is_trader_product')) {
            $data['is_trader_product'] = true;
        }
        if (Schema::hasColumn('products', 'is_active')) {
            $data['is_active'] = false;
        }
        if ($imagePath !== null && Schema::hasColumn('products', 'image')) {
            $data['image'] = $imagePath;
        }
        if ($imagePaths && Schema::hasColumn('products', 'images')) {
            $data['images'] = $imagePaths;
        }

        Product::create($data);

        return redirect()->route('trader.products.index')->with('success', 'Product submitted. Waiting for support approval.');
    }

    public function edit(Product $product)
    {
        $trader = $this->currentTrader();
        abort_unless((int) $product->trader_id === (int) $trader->id, 404);

        $categories = $this->categories();

        return view('trader.products.edit', compact('trader', 'product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $trader = $this->currentTrader();
        abort_unless((int) $product->trader_id === (int) $trader->id, 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'sku' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'track_inventory' => 'nullable|boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $updates = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'details' => $validated['details'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'sku' => $validated['sku'] ?? $product->sku,
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'cost_price' => $validated['cost_price'] ?? null,
            'track_inventory' => (bool) ($validated['track_inventory'] ?? $product->track_inventory),
            'stock_quantity' => (int) ($validated['stock_quantity'] ?? $product->stock_quantity),
            'low_stock_threshold' => (int) ($validated['low_stock_threshold'] ?? $product->low_stock_threshold),
        ];

        if ($request->file('image') && Schema::hasColumn('products', 'image')) {
            $updates['image'] = Storage::disk('public')->putFile('products/trader', $request->file('image'));
        }

        if ($request->file('images') && Schema::hasColumn('products', 'images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $img) {
                if ($img) {
                    $imagePaths[] = Storage::disk('public')->putFile('products/trader', $img);
                }
            }
            $updates['images'] = $imagePaths;
        }

        $requiresReview = (string) ($product->status ?? '') === 'approved'
            && (
                $product->getOriginal('name') !== $updates['name']
                || $product->getOriginal('description') !== $updates['description']
                || $product->getOriginal('details') !== $updates['details']
                || (string) $product->getOriginal('category_id') !== (string) $updates['category_id']
                || (string) $product->getOriginal('price') !== (string) $updates['price']
                || (string) $product->getOriginal('discount_price') !== (string) $updates['discount_price']
                || (string) $product->getOriginal('cost_price') !== (string) $updates['cost_price']
                || array_key_exists('image', $updates)
                || array_key_exists('images', $updates)
            );

        if ($requiresReview) {
            $updates['status'] = 'pending';
            if (Schema::hasColumn('products', 'is_active')) {
                $updates['is_active'] = false;
            }
            if (Schema::hasColumn('products', 'reviewed_by')) {
                $updates['reviewed_by'] = null;
            }
            if (Schema::hasColumn('products', 'reviewed_at')) {
                $updates['reviewed_at'] = null;
            }
        }

        $product->update($updates);

        return redirect()->route('trader.products.index')->with('success', $requiresReview ? 'Changes submitted. Waiting for support approval.' : 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $trader = $this->currentTrader();
        abort_unless((int) $product->trader_id === (int) $trader->id, 404);
        abort_unless(in_array((string) $product->status, ['pending', 'rejected'], true), 403);

        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    public function inventory(Request $request)
    {
        $trader = $this->currentTrader();

        $products = Product::query()
            ->where('trader_id', $trader->id)
            ->where('status', 'approved')
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(30)
            ->withQueryString();

        return view('trader.inventory', compact('trader', 'products'));
    }

    public function updateInventory(Request $request, Product $product)
    {
        $trader = $this->currentTrader();
        abort_unless((int) $product->trader_id === (int) $trader->id, 404);
        abort_unless((string) $product->status === 'approved', 403);

        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'track_inventory' => 'nullable|boolean',
        ]);

        $product->update([
            'stock_quantity' => (int) $validated['stock_quantity'],
            'low_stock_threshold' => array_key_exists('low_stock_threshold', $validated)
                ? (int) ($validated['low_stock_threshold'] ?? 0)
                : (int) ($product->low_stock_threshold ?? 0),
            'track_inventory' => array_key_exists('track_inventory', $validated)
                ? (bool) $validated['track_inventory']
                : (bool) $product->track_inventory,
        ]);

        return back()->with('success', 'Inventory updated.');
    }

    public function sales(Request $request)
    {
        $trader = $this->currentTrader();

        $from = $request->date('from');
        $to = $request->date('to');
        $to = $to ? $to->endOfDay() : null;

        $orderItemQuery = OrderItem::query()
            ->whereHas('product', function ($q) use ($trader) {
                $q->where('trader_id', $trader->id);
            })
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['delivered', 'completed']);
            })
            ->when($from, fn ($q) => $q->where('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('created_at', '<=', $to));

        $summary = [
            'revenue' => (float) (clone $orderItemQuery)->sum('total_price'),
            'units_sold' => (int) (clone $orderItemQuery)->sum('quantity'),
            'orders' => (int) (clone $orderItemQuery)->distinct()->count('order_id'),
        ];

        $topProducts = (clone $orderItemQuery)
            ->select('product_id', DB::raw('SUM(quantity) as units_sold'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->limit(50)
            ->get()
            ->map(function ($row) {
                $product = Product::find($row->product_id);

                return [
                    'id' => (int) $row->product_id,
                    'name' => $product?->name,
                    'sku' => $product?->sku,
                    'units_sold' => (int) $row->units_sold,
                    'revenue' => (float) $row->revenue,
                ];
            });

        $orderIds = (clone $orderItemQuery)->distinct()->pluck('order_id');
        $recentOrders = Order::query()
            ->whereIn('id', $orderIds)
            ->with(['user'])
            ->latest()
            ->limit(30)
            ->get();

        return view('trader.sales', compact('trader', 'summary', 'topProducts', 'recentOrders', 'from', 'to'));
    }
}
