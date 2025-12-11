<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all active categories
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }
}
