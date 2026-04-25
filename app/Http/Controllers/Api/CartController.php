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
        
        // Ensure weight-based fields are included in the response
        $items = $cart->items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
                'product' => $item->product,
                'type' => 'product',
                // Weight-based fields
                'is_weight_based' => $item->is_weight_based ?? false,
                'weight_grams' => $item->weight_grams ?? 0,
                'price_per_unit' => $item->price_per_unit ?? 0,
                'amount_paid' => $item->amount_paid ?? 0,
            ];
        });

        $count = (int) $cart->items->sum(fn ($item) => (int) $item->quantity);

        return response()->json([
            'success' => true,
            'id' => $cart->id,
            'items' => $items,
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
            'is_weight_based' => 'sometimes|boolean',
            'amount_paid' => 'required_if:is_weight_based,true|nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $product = Product::findOrFail($request->product_id);

        $isWeightBased = (bool) $request->input('is_weight_based', false);

        if ($isWeightBased) {
            // Weight-based product logic
            $amountPaid = (float) $request->input('amount_paid', 0);
            $pricePerUnitUsd = (float) ($product->discount_price ?? $product->price);
            
            // Convert USD price to SYP (same conversion as frontend)
            $usdToSyp = (float) \App\Models\SystemSetting::get('usd_to_syp_rate', 13100);
            $pricePerUnitSyp = round($pricePerUnitUsd * $usdToSyp);
            
            if ($pricePerUnitSyp <= 0) {
                return response()->json([
                    'message' => 'سعر المنتج غير صالح',
                ], 422);
            }

            // Calculate weight in grams based on amount paid (both in SYP now)
            $weightGrams = ($amountPaid / $pricePerUnitSyp) * 1000; // price is per kg

            $cartItem = new CartItem([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price' => $amountPaid,
                'total_price' => $amountPaid,
                'is_weight_based' => true,
                'weight_grams' => $weightGrams,
                'price_per_unit' => $pricePerUnitSyp,
                'amount_paid' => $amountPaid,
            ]);
            $cartItem->save();
            
            // Debug logging
            \Log::info('Weight-based cart item saved:', [
                'id' => $cartItem->id,
                'is_weight_based' => $cartItem->is_weight_based,
                'weight_grams' => $cartItem->weight_grams,
                'amount_paid' => $cartItem->amount_paid,
                'price_per_unit' => $cartItem->price_per_unit,
            ]);
        } else {
            // Regular product logic
            $cartItem = CartItem::firstOrNew([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'is_weight_based' => false,
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
        }

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
