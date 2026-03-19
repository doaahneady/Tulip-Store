<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    private function resolveMarket(Request $request): string
    {
        $market = (string) $request->get('market', 'store');
        if (! in_array($market, ['store', 'mart'], true)) {
            return 'store';
        }

        return $market;
    }

    private function applyMarketFilter($query, string $market)
    {
        if (! Schema::hasColumn('products', 'market')) {
            return $query;
        }

        if ($market === 'store') {
            return $query->where(function ($q) use ($market) {
                $q->where('market', $market)->orWhereNull('market');
            });
        }

        return $query->where('market', $market);
    }

    public function index(Request $request)
    {
        $market = $this->resolveMarket($request);

        $query = $this->applyMarketFilter(Product::query()->active()->available(), $market);

        if (Schema::hasTable('categories')) {
            $categoryIds = null;
            if (Schema::hasColumn('categories', 'market')) {
                $ids = Category::query()->where('market', $market)->pluck('id');
                if ($ids->count()) {
                    $categoryIds = $ids;
                }
            }

            if ($categoryIds === null && Schema::hasColumn('categories', 'slug')) {
                $martSlugs = ['fruits', 'vegetables', 'khdroaat', 'khodraat', 'mart-fruits', 'mart-vegetables', 'dairy', 'bakery', 'grocery'];
                if ($market === 'mart') {
                    $ids = Category::query()->whereIn('slug', $martSlugs)->pluck('id');
                    if ($ids->count()) {
                        $categoryIds = $ids;
                    }
                } else {
                    $ids = Category::query()->whereNotIn('slug', $martSlugs)->pluck('id');
                    if ($ids->count()) {
                        $categoryIds = $ids;
                    }
                }
            }

            if ($categoryIds !== null) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $hasDetails = Schema::hasColumn('products', 'details');
            $hasSlug = Schema::hasColumn('products', 'slug');
            $query->where(function ($q) use ($search, $hasDetails, $hasSlug) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
                if ($hasDetails) {
                    $q->orWhere('details', 'like', "%{$search}%");
                }
                if ($hasSlug) {
                    $q->orWhere('slug', 'like', "%{$search}%");
                }
                $q->orWhereHas('category', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%");
                });
            });
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by category slug
        if ($request->has('category') && $request->category) {
            $category = Category::query()
                ->where('slug', $request->category)
                ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', $market))
                ->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $attrFilters = $request->input('attributes');
        if (is_array($attrFilters) && Schema::hasTable('product_attributes')) {
            foreach ($attrFilters as $k => $v) {
                $key = trim((string) $k);
                if ($key === '') {
                    continue;
                }
                $query->whereHas('attributes', function ($q) use ($key, $v) {
                    $q->where('attribute_key', $key)->orWhere('name', $key);
                    if (is_array($v)) {
                        $vals = array_values(array_filter(array_map(fn ($x) => trim((string) $x), $v), fn ($x) => $x !== ''));
                        if ($vals) {
                            $q->where(function ($inner) use ($vals) {
                                $inner->whereIn('value_text', $vals)
                                    ->orWhereIn('value', $vals);
                            });
                        }
                    } else {
                        $val = trim((string) $v);
                        if ($val !== '') {
                            $q->where(function ($inner) use ($val) {
                                $inner->where('value_text', $val)
                                    ->orWhere('value', $val);
                            });
                        }
                    }
                });
            }
        } elseif ($request->filled('attr_key') && Schema::hasTable('product_attributes')) {
            $key = trim((string) $request->input('attr_key'));
            $val = trim((string) $request->input('attr_value', ''));
            if ($key !== '') {
                $query->whereHas('attributes', function ($q) use ($key, $val) {
                    $q->where('attribute_key', $key)->orWhere('name', $key);
                    if ($val !== '') {
                        $q->where(function ($inner) use ($val) {
                            $inner->where('value_text', $val)
                                ->orWhere('value', $val);
                        });
                    }
                });
            }
        }

        // Sort
        $allowedSortBy = ['created_at', 'updated_at', 'name', 'price', 'rating', 'reviews_count'];
        $sortBy = (string) $request->get('sort_by', 'created_at');
        if (! in_array($sortBy, $allowedSortBy, true)) {
            $sortBy = 'created_at';
        }
        $sortOrder = strtolower((string) $request->get('sort_order', 'desc'));
        if (! in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'desc';
        }
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $perPage = (int) $request->get('per_page', 12);
        if ($perPage < 1) {
            $perPage = 12;
        }
        if ($perPage > 1000) {
            $perPage = 1000;
        }

        $with = ['category', 'reviews'];
        if ($market === 'mart' || (bool) $request->boolean('include_attributes')) {
            $with[] = 'attributes';
        }
        $products = $query->with($with)->paginate($perPage);

        return response()->json($products);
    }

    public function show(Request $request, $id)
    {
        $market = $this->resolveMarket($request);

        $product = Product::query()
            ->active()
            ->available()
            ->tap(fn ($q) => $this->applyMarketFilter($q, $market))
            ->whereKey($id)
            ->with([
            'category',
            'attributes',
            'reviews' => function ($q) {
                $q->where('is_approved', true)->with('user');
            },
        ])->firstOrFail();

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
        $market = $this->resolveMarket($request);

        $query = Product::featured()
            ->active()
            ->tap(fn ($q) => $this->applyMarketFilter($q, $market))
            ->with($market === 'mart' ? ['category', 'attributes'] : ['category']);

        if (Schema::hasTable('categories')) {
            if (Schema::hasColumn('categories', 'market')) {
                $ids = Category::query()->where('market', $market)->pluck('id');
                if ($ids->count()) {
                    $query->whereIn('category_id', $ids);
                }
            } elseif (Schema::hasColumn('categories', 'slug')) {
                $martSlugs = ['fruits', 'vegetables', 'khdroaat', 'khodraat', 'mart-fruits', 'mart-vegetables', 'dairy', 'bakery', 'grocery'];
                if ($market === 'mart') {
                    $ids = Category::query()->whereIn('slug', $martSlugs)->pluck('id');
                    if ($ids->count()) {
                        $query->whereIn('category_id', $ids);
                    }
                } else {
                    $ids = Category::query()->whereNotIn('slug', $martSlugs)->pluck('id');
                    if ($ids->count()) {
                        $query->whereIn('category_id', $ids);
                    }
                }
            }
        }

        $products = $query->limit((int) $request->get('limit', 8))->get();

        return response()->json($products);
    }

    public function byCategory(Request $request, $categoryId)
    {
        $market = $this->resolveMarket($request);

        $products = Product::where('category_id', $categoryId)
            ->active()
            ->tap(fn ($q) => $this->applyMarketFilter($q, $market))
            ->with($market === 'mart' ? ['category', 'attributes'] : ['category'])
            ->paginate(12);

        // Extra guard: if category does not belong to the current market, return empty set
        if (Schema::hasTable('categories')) {
            $cat = Category::query()->where('id', $categoryId)->first();
            if ($cat) {
                if (Schema::hasColumn('categories', 'market')) {
                    if ((string) ($cat->market ?? '') !== $market) {
                        return response()->json(collect([]));
                    }
                } else {
                    $martSlugs = ['fruits', 'vegetables', 'khdroaat', 'khodraat', 'mart-fruits', 'mart-vegetables', 'dairy', 'bakery', 'grocery'];
                    $isMartCat = in_array($cat->slug, $martSlugs, true);
                    if (($market === 'mart' && ! $isMartCat) || ($market === 'store' && $isMartCat)) {
                        return response()->json(collect([]));
                    }
                }
            }
        }

        return response()->json($products);
    }

    public function search(Request $request)
    {
        $market = $this->resolveMarket($request);
        $search = $request->get('q', '');

        if (strlen($search) < 2) {
            return response()->json([
                'data' => [],
                'message' => 'Search query must be at least 2 characters',
            ]);
        }

        try {
            $select = ['id', 'name', 'category_id'];
            foreach (['price', 'discount_price', 'stock_quantity', 'track_inventory', 'rating', 'reviews_count', 'description', 'details', 'slug', 'image', 'images'] as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $select[] = $col;
                }
            }
            $hasDetails = Schema::hasColumn('products', 'details');
            $hasSlug = Schema::hasColumn('products', 'slug');

            $products = $this->applyMarketFilter(Product::query()->active(), $market)
                ->with('category')
                ->where(function ($q) use ($search, $hasDetails, $hasSlug) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                    if ($hasDetails) {
                        $q->orWhere('details', 'like', "%{$search}%");
                    }
                    if ($hasSlug) {
                        $q->orWhere('slug', 'like', "%{$search}%");
                    }
                    $q->orWhereHas('category', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%");
                    });
                })
                ->limit(20)
                ->get($select);

            // Extra guard by categories for mixed datasets
            if (Schema::hasTable('categories')) {
                if (Schema::hasColumn('categories', 'market')) {
                    $products = $products->filter(fn ($p) => (string) (optional($p->category)->market ?? '') === $market)->values();
                } else {
                    $martSlugs = ['fruits', 'vegetables', 'khdroaat', 'khodraat', 'mart-fruits', 'mart-vegetables', 'dairy', 'bakery', 'grocery'];
                    $products = $products->filter(function ($p) use ($market, $martSlugs) {
                        $slug = optional($p->category)->slug;
                        return $market === 'mart'
                            ? in_array($slug, $martSlugs, true)
                            : ! in_array($slug, $martSlugs, true);
                    })->values();
                }
            }

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
