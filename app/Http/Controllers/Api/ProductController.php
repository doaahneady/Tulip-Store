<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->active();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by category slug
        if ($request->has('category') && $request->category) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $perPage = $request->get('per_page', 12);
        $products = $query->with(['category', 'reviews'])->paginate($perPage);

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with([
            'category',
            'attributes',
            'reviews' => function ($q) {
                $q->where('is_approved', true)->with('user');
            },
        ])->findOrFail($id);

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('product_performance_metrics')) {
                $key = [
                    'product_id' => $product->id,
                    'metric_date' => now()->toDateString(),
                ];
                $existing = DB::table('product_performance_metrics')->where($key)->first();
                if ($existing) {
                    DB::table('product_performance_metrics')->where('id', $existing->id)->update([
                        'views' => (int) $existing->views + 1,
                        'updated_at' => now(),
                    ]);
                } else {
                    DB::table('product_performance_metrics')->insert([
                        'product_id' => $product->id,
                        'metric_date' => now()->toDateString(),
                        'views' => 1,
                        'cart_additions' => 0,
                        'purchases' => 0,
                        'conversion_rate' => 0,
                        'revenue' => 0,
                        'average_rating' => null,
                        'review_count' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
        }

        return response()->json($product);
    }

    public function featured(Request $request)
    {
        $products = Product::featured()
            ->active()
            ->with('category')
            ->limit($request->get('limit', 8))
            ->get();

        return response()->json($products);
    }

    public function byCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)
            ->active()
            ->with('category')
            ->paginate(12);

        return response()->json($products);
    }

    public function search(Request $request)
    {
        $search = $request->get('q', '');

        if (strlen($search) < 2) {
            return response()->json([
                'data' => [],
                'message' => 'Search query must be at least 2 characters',
            ]);
        }

        try {
            $products = Product::query()
                ->active()
                ->with('category')
                ->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })
                ->limit(20)
                ->get(['id', 'name', 'category_id', 'image', 'price', 'discount_price', 'stock_quantity', 'track_inventory', 'rating', 'reviews_count']);

            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('search_logs')) {
                    DB::table('search_logs')->insert([
                        'user_id' => auth()->id(),
                        'query_text' => $search,
                        'results_count' => $products->count(),
                        'no_results' => $products->isEmpty(),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'metadata' => json_encode(['source' => 'api.product.search']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Throwable $e) {
            }

            if ($products->isEmpty()) {
                try {
                    SystemLog::create([
                        'level' => 'info',
                        'action' => 'search_zero_results',
                        'message' => 'Zero-result search',
                        'user' => auth()->user()?->id ? 'user:'.auth()->user()->id : 'guest',
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'metadata' => ['query' => $search],
                    ]);
                } catch (\Throwable $e) {
                }
                try {
                    if (\Illuminate\Support\Facades\Schema::hasTable('performance_metrics')) {
                        $key = [
                            'metric_name' => 'search_zero_results',
                            'metric_type' => 'daily',
                            'metric_date' => now()->toDateString(),
                        ];
                        $existing = DB::table('performance_metrics')->where($key)->first();
                        if ($existing) {
                            DB::table('performance_metrics')->where('id', $existing->id)->update([
                                'value' => (float) $existing->value + 1,
                                'category' => 'search',
                                'metadata' => json_encode(['query' => $search]),
                                'updated_at' => now(),
                            ]);
                        } else {
                            DB::table('performance_metrics')->insert([
                                'metric_name' => 'search_zero_results',
                                'metric_type' => 'daily',
                                'metric_date' => now()->toDateString(),
                                'value' => 1.0,
                                'category' => 'search',
                                'metadata' => json_encode(['query' => $search]),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                } catch (\Throwable $e) {
                }
            }

            return response()->json(['data' => $products]);
        } catch (\Throwable $e) {
            return response()->json([
                'data' => [],
                'message' => 'internal_error',
                'error' => $e->getMessage(),
            ], 200);
        }
    }
}
