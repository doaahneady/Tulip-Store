<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function byCategory(Request $request, $slug)
    {
        $cat = Category::where('slug',$slug)->first();
        if(!$cat) return response()->json(['success'=>false,'message'=>'Category not found'],404);
        $query = Product::where('category_id',$cat->id);
        // Optional: search
        if($q = $request->get('q')) {
            $query->where(function($sub) use ($q){
                $sub->where('name','like',"%$q%")
                    ->orWhere('description','like',"%$q%")
                    ->orWhere('slug','like',"%$q%") ;
            });
        }
        // Optional: order (price, date, rate, sales) and dir (asc/desc)
        $order = $request->get('order');
        $dir = strtolower($request->get('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        if ($order === 'price') $query->orderBy('price', $dir);
        else if ($order === 'date') $query->orderBy('created_at', $dir);
        else if ($order === 'rate') $query->orderBy('rate', $dir);
        else if ($order === 'sales') $query->orderBy('sales', $dir);
        // Custom attribute filters
        $filters = $request->except(['q','order','dir','page']);
        foreach ($filters as $k => $v) {
            // Ignore non-attribute or empty values ("All" from selects)
            if ($k === 'currency' || $k === 'gender') continue;
            if ($v === null || $v === '') continue;
            $query->whereHas('attributes', function($attQ) use($k,$v){
                $attQ->where('name',$k)->where('value',$v);
            });
        }
        $products = $query->paginate(12);
        return response()->json($products);
    }
    public function show($slug) {
        $p = Product::where('slug',$slug)->with('attributes','category')->first();
        if(!$p) return response()->json(['success'=>false,'message'=>'Not found'],404);
        return response()->json($p);
    }
    public function search(Request $request){
        $query = Product::query();
        if($q = $request->get('q')) {
            $query->where(function($sub) use ($q){
                $sub->where('name','like',"%$q%")
                    ->orWhere('description','like',"%$q%")
                    ->orWhere('slug','like',"%$q%") ;
            });
        }
        // order + direction
        $order = $request->get('order');
        $dir = strtolower($request->get('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        if ($order === 'price') $query->orderBy('price', $dir);
        else if ($order === 'date') $query->orderBy('created_at', $dir);
        else if ($order === 'rate') $query->orderBy('rate', $dir);
        else if ($order === 'sales') $query->orderBy('sales', $dir);
        // custom filters
        $filters = $request->except(['q','order','dir','page']);
        foreach ($filters as $k => $v) {
            if ($v === null || $v === '') continue;
            $query->whereHas('attributes', function($attQ) use($k,$v){
                $attQ->where('name',$k)->where('value',$v);
            });
        }
        return response()->json($query->paginate(12));
    }
}
