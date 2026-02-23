<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $categories]);
    }

    public function show($id)
    {
        // Support both ID and slug
        $category = Category::where('id', $id)
            ->orWhere('slug', $id)
            ->with(['products' => function ($query) {
                $query->active();
            }])
            ->firstOrFail();

        return response()->json($category);
    }

    public function products($id)
    {
        // Support both ID and slug
        $category = Category::where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        $products = $category->products()
            ->active()
            ->get();

        return response()->json(['data' => $products]);
    }
}
