<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::select('id','name','slug','description')->get();
        return response()->json($categories);
    }
    public function show($slug)
    {
        $cat = Category::where('slug',$slug)->first();
        if(!$cat) return response()->json(['success'=>false, 'message'=>'Category not found'],404);
        return response()->json($cat);
    }
    public function filters($slug)
    {
        $cat = Category::where('slug',$slug)->first();
        if(!$cat) return response()->json(['success'=>false, 'message'=>'Category not found'],404);
        // Find all attribute names and value sets for all products in this category
        $atts = ProductAttribute::whereIn('product_id', $cat->products->pluck('id'))
            ->get();
        $grouped = [];
        foreach ($atts as $att) {
            if(!isset($grouped[$att->name])) $grouped[$att->name] = [];
            if(!in_array($att->value, $grouped[$att->name])) $grouped[$att->name][]=$att->value;
        }
        return response()->json($grouped);
    }
}
