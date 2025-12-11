<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        
        $cart->load(['items.product']);

        return response()->json([
            'id' => $cart->id,
            'items' => $cart->items,
            'total' => $cart->items->sum(fn($item) => $item->price * $item->quantity),
            'count' => $cart->items->count(),
        ]);
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock',
                'available' => $product->stock
            ], 422);
        }

        // Add or update item
        $cartItem = CartItem::updateOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $request->product_id],
            [
                'quantity' => \DB::raw("quantity + {$request->quantity}"),
                'price' => $product->discount_price ?? $product->price,
            ]
        );

        return response()->json([
            'message' => 'Product added to cart',
            'item' => $cartItem,
        ], 201);
    }

    public function updateItem(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $cartItem = CartItem::findOrFail($itemId);
        $product = $cartItem->product;

        // Check stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock',
                'available' => $product->stock
            ], 422);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json($cartItem);
    }

    public function removeItem($itemId)
    {
        CartItem::destroy($itemId);
        return response()->json(['message' => 'Item removed from cart']);
    }

    public function clear()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
        }

        return response()->json(['message' => 'Cart cleared']);
    }
}
