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

        $count = (int) $cart->items->sum(fn ($item) => (int) $item->quantity);

        return response()->json([
            'success' => true,
            'id' => $cart->id,
            'items' => $cart->items,
            'total' => (float) $cart->items->sum(fn ($item) => (float) ($item->total_price ?? ($item->price * $item->quantity))),
            'count' => $count,
            'cart_count' => $count,
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

        $cartItem = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $existingQuantity = $cartItem->exists ? (int) $cartItem->quantity : 0;
        $newQuantity = $existingQuantity + (int) $request->quantity;

        if ($product->track_inventory) {
            $available = (int) ($product->stock_quantity ?? 0);
            if ($available <= 0) {
                return response()->json([
                    'message' => 'المخزون لهذا المنتج صفر. لا يمكن إضافته للسلة',
                    'available' => $available,
                ], 422);
            }
            if ($newQuantity > $available) {
                return response()->json([
                    'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح',
                    'available' => $available,
                ], 422);
            }
        }

        $cartItem->quantity = $newQuantity;
        $cartItem->unit_price = $product->discount_price ?? $product->price;
        $cartItem->save();

        $cart->load('items');
        $count = (int) $cart->items->sum(fn ($item) => (int) $item->quantity);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'item' => $cartItem,
            'cart_count' => $count,
            'count' => $count,
        ], 201);
    }

    public function updateItem(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $cartItem = CartItem::findOrFail($itemId);
        $product = $cartItem->product;

        if ($product && $product->track_inventory) {
            $available = (int) ($product->stock_quantity ?? 0);
            if ($available <= 0) {
                return response()->json([
                    'message' => 'المخزون لهذا المنتج صفر. لا يمكن إضافة كميات',
                    'available' => $available,
                ], 422);
            }
            if ((int) $request->quantity > $available) {
                return response()->json([
                    'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح',
                    'available' => $available,
                ], 422);
            }
        }

        $cartItem->update(['quantity' => $request->quantity]);

        $cart = $cartItem->cart()->with('items')->first();
        $count = $cart ? (int) $cart->items->sum(fn ($item) => (int) $item->quantity) : 0;

        return response()->json([
            'success' => true,
            'item' => $cartItem->fresh(),
            'cart_count' => $count,
            'count' => $count,
        ]);
    }

    public function removeItem($itemId)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->where('id', $itemId)->delete();
            $cart->load('items');
        }

        $count = $cart ? (int) $cart->items->sum(fn ($item) => (int) $item->quantity) : 0;

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart_count' => $count,
            'count' => $count,
        ]);
    }

    public function clear()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'cart_count' => 0,
            'count' => 0,
        ]);
    }
}
