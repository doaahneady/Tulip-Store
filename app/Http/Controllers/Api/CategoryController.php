<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CategoryController extends Controller
{
    private function resolveMarket(Request $request): string
    {
        $market = (string) $request->get('market', 'store');
        if (! in_array($market, ['store', 'mart'], true)) {
            return 'store';
        }

        return $market;
    }

    public function index(Request $request)
    {
        $market = $this->resolveMarket($request);
        $martSlugs = ['fruits', 'vegetables', 'khdroaat', 'khodraat', 'mart-fruits', 'mart-vegetables', 'dairy', 'bakery', 'grocery'];

        if (! Schema::hasTable('categories')) {
            return response()->json(['data' => []]);
        }

        $categories = Category::query()
            ->when(Schema::hasColumn('categories', 'is_active'), fn ($q) => $q->where('is_active', true))
            ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', $market))
            // Fallback for codebases without categories.market
            ->when(! Schema::hasColumn('categories', 'market') && Schema::hasColumn('categories', 'slug') && $market === 'store', fn ($q) => $q->whereNotIn('slug', $martSlugs))
            ->when(! Schema::hasColumn('categories', 'market') && Schema::hasColumn('categories', 'slug') && $market === 'mart', fn ($q) => $q->whereIn('slug', $martSlugs))
            ->when(Schema::hasColumn('categories', 'display_order'), fn ($q) => $q->orderBy('display_order'))
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $categories]);
    }

    public function show(Request $request, $id)
    {
        $market = $this->resolveMarket($request);
        $martSlugs = ['fruits', 'vegetables', 'khdroaat', 'khodraat', 'mart-fruits', 'mart-vegetables'];

        abort_unless(Schema::hasTable('categories'), 404);

        $category = Category::query()
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('slug', $id);
            })
            ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', $market))
            ->when($market === 'store' && Schema::hasColumn('categories', 'slug'), fn ($q) => $q->whereNotIn('slug', $martSlugs))
            ->with(['products' => function ($query) use ($market) {
                $query->active();
                if (Schema::hasColumn('products', 'market')) {
                    $query->where('market', $market);
                }
            }])
            ->firstOrFail();

        return response()->json($category);
    }

    public function products(Request $request, $id)
    {
        $market = $this->resolveMarket($request);
        $martSlugs = ['fruits', 'vegetables', 'khdroaat', 'khodraat', 'mart-fruits', 'mart-vegetables'];

        abort_unless(Schema::hasTable('categories'), 404);

        $category = Category::query()
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('slug', $id);
            })
            ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', $market))
            ->when($market === 'store' && Schema::hasColumn('categories', 'slug'), fn ($q) => $q->whereNotIn('slug', $martSlugs))
            ->firstOrFail();

        $products = $category->products()
            ->active()
            ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', $market))
            ->get();

        return response()->json(['data' => $products]);
    }
}
