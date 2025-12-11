<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

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
        $product = Product::with(['category', 'attributes', 'reviews.user'])->findOrFail($id);
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
                'message' => 'Search query must be at least 2 characters'
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
                ->get(['id','name','category_id','image']);

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
