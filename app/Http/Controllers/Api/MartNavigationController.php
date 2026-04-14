<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class MartNavigationController extends Controller
{
    public function navigation()
    {
        if (! Schema::hasTable('categories')) {
            return response()->json(['data' => []]);
        }

        $cacheKey = 'mart:navigation:v2';

        $payload = Cache::remember($cacheKey, now()->addMinutes(10), function () {
            $buildCategoriesQuery = function (bool $preferMartOnly) {
                $categoriesQuery = Category::query();

                if (Schema::hasColumn('categories', 'is_active')) {
                    $categoriesQuery->where('is_active', true);
                }

                if ($preferMartOnly) {
                    if (Schema::hasColumn('categories', 'market')) {
                        $categoriesQuery->where('market', 'mart');
                    } elseif (Schema::hasColumn('categories', 'slug')) {
                        $martSlugs = ['fruits', 'vegetables', 'khdroaat', 'khodraat', 'mart-fruits', 'mart-vegetables', 'dairy', 'bakery', 'grocery'];
                        $categoriesQuery->whereIn('slug', $martSlugs);
                    }
                }

                if (Schema::hasColumn('categories', 'display_order')) {
                    $categoriesQuery->orderBy('display_order');
                }

                $categoriesQuery->orderBy('name');

                return $categoriesQuery;
            };

            $categories = $buildCategoriesQuery(true)->get();
            if ($categories->isEmpty()) {
                $categories = $buildCategoriesQuery(false)->get();
            }

            $subcategories = collect();
            if (Schema::hasTable('subcategories')) {
                $subcategoriesQuery = Subcategory::query()->whereIn('category_id', $categories->pluck('id')->all());
                if (Schema::hasColumn('subcategories', 'is_active')) {
                    $subcategoriesQuery->where('is_active', true);
                }
                if (Schema::hasColumn('subcategories', 'display_order')) {
                    $subcategoriesQuery->orderBy('display_order');
                }
                $subcategoriesQuery->orderBy('name');
                $subcategories = $subcategoriesQuery->get();
            }

            $counts = [];
            if (Schema::hasTable('products') && Schema::hasColumn('products', 'subcategory_id')) {
                $counts = Product::query()
                    ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', 'mart'))
                    ->when(Schema::hasColumn('products', 'is_active'), fn ($q) => $q->where('is_active', true))
                    ->whereNotNull('subcategory_id')
                    ->selectRaw('subcategory_id, COUNT(*) as c')
                    ->groupBy('subcategory_id')
                    ->pluck('c', 'subcategory_id')
                    ->toArray();
            }

            $subsByCat = $subcategories->groupBy('category_id');

            return $categories->map(function (Category $c) use ($subsByCat, $counts) {
                $subs = ($subsByCat->get($c->id) ?? collect())->map(function (Subcategory $s) use ($counts) {
                    return [
                        'id' => $s->id,
                        'category_id' => $s->category_id,
                        'name' => $s->name,
                        'slug' => $s->slug,
                        'display_order' => (int) ($s->display_order ?? 0),
                        'products_count' => (int) ($counts[$s->id] ?? 0),
                    ];
                })->values()->all();

                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'slug' => $c->slug,
                    'image_url' => $c->image_url,
                    'display_order' => (int) ($c->display_order ?? 0),
                    'subcategories' => $subs,
                ];
            })->values()->all();
        });

        return response()->json(['data' => $payload]);
    }

    public function productsBySubcategory(Request $request, $idOrSlug)
    {
        abort_unless(Schema::hasTable('subcategories') && Schema::hasTable('products'), 404);

        $subcategory = null;
        if (is_numeric($idOrSlug)) {
            $subcategory = Subcategory::query()->whereKey((int) $idOrSlug)->first();
        } else {
            $slug = trim((string) $idOrSlug);
            $category = trim((string) $request->query('category', ''));

            $q = Subcategory::query()->where('slug', $slug);
            if ($category !== '') {
                $q->whereHas('category', function ($cq) use ($category) {
                    $cq->where('slug', $category);
                });
            }
            $subcategory = $q->first();
        }

        if (! $subcategory) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        $perPage = (int) $request->query('per_page', 12);
        if ($perPage < 1) {
            $perPage = 12;
        }
        if ($perPage > 1000) {
            $perPage = 1000;
        }

        $query = Product::query()
            ->where('subcategory_id', $subcategory->id)
            ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', 'mart'))
            ->when(Schema::hasColumn('products', 'is_active'), fn ($q) => $q->where('is_active', true))
            ->with(['category', 'subcategory'])
            ->when(Schema::hasTable('product_attributes'), fn ($q) => $q->with('attributes'));

        $sortBy = (string) $request->query('sort_by', 'created_at');
        $sortOrder = strtolower((string) $request->query('sort_order', 'desc'));
        if (! in_array($sortBy, ['created_at', 'name', 'price'], true)) {
            $sortBy = 'created_at';
        }
        if (! in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        return response()->json([
            'subcategory' => [
                'id' => $subcategory->id,
                'category_id' => $subcategory->category_id,
                'name' => $subcategory->name,
                'slug' => $subcategory->slug,
            ],
            'products' => $query->paginate($perPage),
        ]);
    }
}
