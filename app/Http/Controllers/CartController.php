<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Get cart items
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::with('category')->find($productId);
            if ($product) {
                $price = $product->discount_price ?? $product->price;
                $itemSubtotal = $price * $quantity;
                $subtotal += $itemSubtotal;

                $cartItems[] = [
                    'id' => $productId,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'image' => $product->image,
                        'price' => $product->price,
                        'discount_price' => $product->discount_price,
                        'brand' => $product->brand,
                    ],
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                ];
            }
        }

        // Calculate total (no fees on cart page)
        $total = $subtotal;

        // Calculate actual count from valid items only
        $count = array_sum(array_column($cartItems, 'quantity'));

        return response()->json([
            'items' => $cartItems,
            'count' => $count,
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المنتج للسلة',
            'cart_count' => array_sum($cart),
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = Session::get('cart', []);
        $productId = $request->item_id;

        if ($request->quantity == 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $request->quantity;
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث السلة',
            'cart_count' => array_sum($cart),
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
        ]);

        $cart = Session::get('cart', []);
        $productId = $request->item_id;
        
        unset($cart[$productId]);
        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المنتج من السلة',
            'cart_count' => array_sum($cart),
        ]);
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        Session::forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'تم تفريغ السلة',
        ]);
    }

    /**
     * Get cart items for checkout
     */
    public function getItems()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $cartItems[] = [
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'image' => $product->image,
                        'price' => $product->price,
                        'discount_price' => $product->discount_price,
                    ],
                    'quantity' => $quantity,
                ];
            }
        }

        return response()->json($cartItems);
    }
}
