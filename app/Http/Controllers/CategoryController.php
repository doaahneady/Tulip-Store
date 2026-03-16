<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all active categories (optionally filtered by market=store|mart).
     */
    public function index(Request $request)
    {
        $query = Category::where('is_active', true);

        if (\Illuminate\Support\Facades\Schema::hasColumn('categories', 'market')) {
            $market = $request->query('market');
            if ($market === 'store' || $market === 'mart') {
                $query->where('market', $market);
            }
        }

        $categories = $query->orderBy('display_order')->orderBy('name')->get();

        if ($categories->isEmpty()) {
            $query = Category::orderBy('display_order')->orderBy('name');
            if (\Illuminate\Support\Facades\Schema::hasColumn('categories', 'market') && $request->query('market')) {
                $market = $request->query('market');
                if ($market === 'store' || $market === 'mart') {
                    $query->where('market', $market);
                }
            }
            $categories = $query->get();
        }

        return response()->json($categories);
    }
}
