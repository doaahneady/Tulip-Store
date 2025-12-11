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

        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Category::with(['products' => function ($query) {
            $query->where('is_active', true);
        }])->findOrFail($id);

        return response()->json($category);
    }
}
