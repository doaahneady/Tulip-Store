<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    /**
     * Search products by name
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        $products = Product::with('category')
            ->active()
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json(['data' => $products]);
    }

    /**
     * Get all products or filter by category
     */
    public function index(Request $request)
    {
        $categorySlug = $request->input('category');

        $baseQuery = Product::with('category');
        $query = (clone $baseQuery)->active();

        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
                $baseQuery->where('category_id', $category->id);
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        if ($products->isEmpty()) {
            $products = $baseQuery->orderBy('created_at', 'desc')->paginate(12);
        }

        return response()->json($products);
    }

    /**
     * Get products by category slug
     */
    public function byCategory(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $query = Product::with('category')
            ->where('category_id', $category->id)
            ->active();

        // Apply price filters
        if ($request->has('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('COALESCE(discount_price, price) >= ?', [$request->min_price]);
            });
        }
        if ($request->has('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('COALESCE(discount_price, price) <= ?', [$request->max_price]);
            });
        }

        // Apply rating filter (Amazon-style: rating and up)
        if ($request->has('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        // Apply color filters
        if ($request->has('colors')) {
            $colors = explode(',', $request->colors);
            $query->whereIn('color', $colors);
        }

        // Apply size filters
        if ($request->has('sizes')) {
            $sizes = explode(',', $request->sizes);
            $query->whereIn('size', $sizes);
        }

        // Apply brand filters
        if ($request->has('brands')) {
            $brands = explode(',', $request->brands);
            $query->whereIn('brand', $brands);
        }

        // Apply material filters
        if ($request->has('materials')) {
            $materials = explode(',', $request->materials);
            $query->whereIn('material', $materials);
        }

        // Apply condition filters
        if ($request->has('conditions')) {
            $conditions = explode(',', $request->conditions);
            $query->whereIn('condition', $conditions);
        }

        // Apply author filters (for books)
        if ($request->has('authors')) {
            $authors = explode(',', $request->authors);
            $query->whereIn('author', $authors);
        }

        // Apply genre filters (for books)
        if ($request->has('genres')) {
            $genres = explode(',', $request->genres);
            $query->whereIn('genre', $genres);
        }

        // Apply age range filters (for toys)
        if ($request->has('age_ranges')) {
            $ageRanges = explode(',', $request->age_ranges);
            $query->where(function ($q) use ($ageRanges) {
                foreach ($ageRanges as $range) {
                    [$min, $max] = explode('-', $range);
                    $q->orWhereBetween('age_range', [(int) $min, (int) $max]);
                }
            });
        }

        // Apply availability filters
        if ($request->has('availability')) {
            $availability = explode(',', $request->availability);
            if (in_array('in-stock', $availability) && ! in_array('out-of-stock', $availability)) {
                $query->where('stock_quantity', '>', 0);
            } elseif (in_array('out-of-stock', $availability) && ! in_array('in-stock', $availability)) {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'products' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ]);
        }

        return view('category', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    /**
     * Show product details page
     */
    public function show($id)
    {
        $product = Product::with('category')
            ->active()
            ->findOrFail($id);

        $reviewsCount = Review::where('product_id', $product->id)->where('is_approved', true)->count();
        $avgRating = (float) (Review::where('product_id', $product->id)->where('is_approved', true)->avg('rating') ?? 0);
        $avgRating = $reviewsCount > 0 ? round($avgRating, 1) : 0.0;

        $unitsSold = (int) OrderItem::query()
            ->where('product_id', $product->id)
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['delivered', 'completed']);
            })
            ->sum('quantity');

        $relatedProducts = Product::query()
            ->active()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('products.show', [
            'product' => $product,
            'reviewsCount' => $reviewsCount,
            'avgRating' => $avgRating,
            'unitsSold' => $unitsSold,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
