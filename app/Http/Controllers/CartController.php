<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    protected function canUseDatabaseCart(): bool
    {
        return Schema::hasTable('carts') && Schema::hasTable('cart_items');
    }

    protected function getOrCreateDatabaseCart(): ?Cart
    {
        if (! Auth::check() || ! $this->canUseDatabaseCart()) {
            return null;
        }

        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    protected function mergeSessionCartIntoDatabaseCart(): void
    {
        $dbCart = $this->getOrCreateDatabaseCart();
        if (! $dbCart) {
            return;
        }

        $sessionCart = Session::get('cart', []);
        if (! is_array($sessionCart) || empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $productId => $quantity) {
            $productId = (int) $productId;
            $quantity = (int) $quantity;
            if ($productId <= 0 || $quantity <= 0) {
                continue;
            }

            $product = Product::find($productId);
            if (! $product || ! $product->is_active) {
                continue;
            }

            $cartItem = CartItem::firstOrNew([
                'cart_id' => $dbCart->id,
                'product_id' => $productId,
            ]);
            $newQty = ((int) ($cartItem->quantity ?? 0)) + $quantity;

            if ($product->track_inventory) {
                $available = (int) ($product->stock_quantity ?? 0);
                $newQty = min($newQty, $available);
            }

            $cartItem->quantity = $newQty;
            $cartItem->unit_price = $product->discount_price ?? $product->price;
            $cartItem->save();
        }

        Session::forget('cart');
    }

    /**
     * Get cart items
     */
    public function index()
    {
        if (Auth::check() && $this->canUseDatabaseCart()) {
            $this->mergeSessionCartIntoDatabaseCart();
            $dbCart = $this->getOrCreateDatabaseCart();
            $dbCart?->load(['items.product.category']);

            $items = [];
            $subtotal = 0.0;

            if ($dbCart) {
                foreach ($dbCart->items as $item) {
                    $product = $item->product;
                    if (! $product) {
                        continue;
                    }

                    $unit = (float) ($item->unit_price ?? ($product->discount_price ?? $product->price));
                    $qty = (int) $item->quantity;
                    $line = $unit * $qty;
                    $subtotal += $line;

                    $items[] = [
                        'id' => (int) $product->id,
                        'product_id' => (int) $product->id,
                        'type' => 'product',
                        'product' => [
                            'id' => $product->id,
                            'name' => $product->name,
                            'image' => $product->primary_image_url,
                            'price' => $product->price,
                            'discount_price' => $product->discount_price,
                            'brand' => $product->brand ?? null,
                        ],
                        'quantity' => $qty,
                        'subtotal' => $line,
                    ];
                }
            }

            $customGifts = Session::get('custom_gifts', []);
            foreach ($customGifts as $giftId => $gift) {
                $subtotal += (float) ($gift['price'] ?? 0);
                $items[] = [
                    'id' => $giftId,
                    'type' => 'custom_gift',
                    'product' => [
                        'id' => $giftId,
                        'name' => $gift['name'] ?? 'هدية مخصصة',
                        'image' => null,
                        'price' => $gift['price'] ?? 0,
                        'discount_price' => null,
                        'brand' => 'هدية مخصصة',
                        'emojis' => $gift['emojis'] ?? [],
                        'description' => $gift['description'] ?? '',
                    ],
                    'quantity' => 1,
                    'subtotal' => (float) ($gift['price'] ?? 0),
                    'gift_details' => $gift,
                ];
            }

            $martProducts = Session::get('mart_products', []);
            foreach ($martProducts as $productId => $product) {
                $itemSubtotal = ((float) ($product['price'] ?? 0)) * ((int) ($product['quantity'] ?? 0));
                $subtotal += $itemSubtotal;

                $items[] = [
                    'id' => $productId,
                    'type' => 'mart',
                    'product' => [
                        'id' => $productId,
                        'name' => $product['name'] ?? 'منتج',
                        'image' => $product['image'] ?? null,
                        'price' => $product['price'] ?? 0,
                        'discount_price' => null,
                        'brand' => 'توليب مارت',
                        'unit' => $product['unit'] ?? 'قطعة',
                        'emoji' => $product['emoji'] ?? null,
                    ],
                    'quantity' => (int) ($product['quantity'] ?? 0),
                    'subtotal' => $itemSubtotal,
                ];
            }

            $count = (int) array_sum(array_map(fn ($i) => (int) ($i['quantity'] ?? 0), $items));
            $total = $subtotal;

            return response()->json([
                'items' => $items,
                'count' => $count,
                'cart_count' => $count,
                'subtotal' => $subtotal,
                'total' => $total,
            ]);
        }

        $cart = Session::get('cart', []);
        $cartItems = [];
        $subtotal = 0;

        // Regular products
        foreach ($cart as $productId => $quantity) {
            $product = Product::with('category')->find($productId);
            if ($product) {
                $price = $product->discount_price ?? $product->price;
                $itemSubtotal = $price * $quantity;
                $subtotal += $itemSubtotal;

                $cartItems[] = [
                    'id' => $productId,
                    'product_id' => $productId,
                    'type' => 'product',
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'image' => $product->primary_image_url,
                        'price' => $product->price,
                        'discount_price' => $product->discount_price,
                        'brand' => $product->brand,
                    ],
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                ];
            }
        }

        // Mart products
        $martProducts = Session::get('mart_products', []);
        foreach ($martProducts as $productId => $product) {
            $itemSubtotal = $product['price'] * $product['quantity'];
            $subtotal += $itemSubtotal;

            $cartItems[] = [
                'id' => $productId,
                'type' => 'mart',
                'product' => [
                    'id' => $productId,
                    'name' => $product['name'],
                    'image' => $product['image'],
                    'price' => $product['price'],
                    'discount_price' => null,
                    'brand' => 'توليب مارت',
                    'unit' => $product['unit'] ?? 'قطعة',
                ],
                'quantity' => $product['quantity'],
                'subtotal' => $itemSubtotal,
            ];
        }

        // Custom gifts
        $customGifts = Session::get('custom_gifts', []);
        foreach ($customGifts as $giftId => $gift) {
            $subtotal += $gift['price'];
            $cartItems[] = [
                'id' => $giftId,
                'type' => 'custom_gift',
                'product' => [
                    'id' => $giftId,
                    'name' => $gift['name'],
                    'image' => null,
                    'price' => $gift['price'],
                    'discount_price' => null,
                    'brand' => 'هدية مخصصة',
                    'emojis' => $gift['emojis'] ?? [],
                    'description' => $gift['description'] ?? '',
                ],
                'quantity' => 1,
                'subtotal' => $gift['price'],
                'gift_details' => $gift,
            ];
        }

        // Calculate total (no fees on cart page)
        $total = $subtotal;

        // Calculate actual count from valid items only
        $count = array_sum(array_column($cartItems, 'quantity'));

        return response()->json([
            'items' => $cartItems,
            'count' => $count,
            'cart_count' => $count,
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;
        $productType = $request->product_type ?? 'regular';

        // Handle mart products (virtual products)
        if ($productType === 'mart') {
            $request->validate([
                'product_id' => 'required',
                'name' => 'required|string',
                'price' => 'required|numeric',
                'quantity' => 'integer|min:1',
                'image' => 'nullable|string',
                'unit' => 'nullable|string',
            ]);

            $product = Product::find($productId);
            $martProducts = Session::get('mart_products', []);
            $existingQty = isset($martProducts[$productId]) ? $martProducts[$productId]['quantity'] : 0;
            $newQty = $existingQty + $quantity;

            if ($product && $product->track_inventory) {
                $available = (int) ($product->stock_quantity ?? 0);
                if ($newQty > $available) {
                    return response()->json([
                        'success' => false,
                        'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح للمنتج ' . $product->name,
                        'available' => $available,
                    ], 422);
                }
            }

            if (isset($martProducts[$productId])) {
                $martProducts[$productId]['quantity'] = $newQty;
            } else {
                $martProducts[$productId] = [
                    'id' => $productId,
                    'name' => $request->name,
                    'price' => $request->price,
                    'quantity' => $quantity,
                    'image' => $request->image,
                    'unit' => $request->unit ?? 'قطعة',
                    'type' => 'mart',
                    'emoji' => $request->emoji,
                ];
            }

            Session::put('mart_products', $martProducts);

            // Calculate total count
            $cart = Session::get('cart', []);
            $customGifts = Session::get('custom_gifts', []);
            $totalCount = array_sum($cart) + count($customGifts) + array_sum(array_column($martProducts, 'quantity'));

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المنتج للسلة',
                'count' => $totalCount,
            ]);
        }

        // Handle regular products
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        if (Auth::check() && $this->canUseDatabaseCart()) {
            $this->mergeSessionCartIntoDatabaseCart();
            $dbCart = $this->getOrCreateDatabaseCart();
            $product = Product::findOrFail($productId);

            $cartItem = CartItem::firstOrNew([
                'cart_id' => $dbCart->id,
                'product_id' => $product->id,
            ]);

            $existingQty = (int) ($cartItem->quantity ?? 0);
            $newQty = $existingQty + (int) $quantity;

            if ($product->track_inventory) {
                $available = (int) ($product->stock_quantity ?? 0);
                if ($newQty > $available) {
                    if ($available <= 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'لا يوجد مخزون من '.$product->name,
                            'available' => 0,
                        ], 422);
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح للمنتج',
                        'available' => $available,
                    ], 422);
                }
            }

            $cartItem->quantity = $newQty;
            $cartItem->unit_price = $product->discount_price ?? $product->price;
            $cartItem->save();

            $dbCart->load('items');
            $totalCount = (int) $dbCart->items->sum(fn ($i) => (int) $i->quantity)
                + (int) count(Session::get('custom_gifts', []))
                + (int) array_sum(array_column(Session::get('mart_products', []), 'quantity'));
        } else {
            $product = Product::findOrFail($productId);
            $cart = Session::get('cart', []);

            $existingQty = (int) ($cart[$productId] ?? 0);
            $newQty = $existingQty + (int) $quantity;

            if ($product->track_inventory) {
                $available = (int) ($product->stock_quantity ?? 0);
                if ($newQty > $available) {
                    if ($available <= 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'لا يوجد مخزون من '.$product->name,
                            'available' => 0,
                        ], 422);
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح للمنتج   ',
                        'available' => $available,
                    ], 422);
                }
            }

            $cart[$productId] = $newQty;
            Session::put('cart', $cart);

            $customGifts = Session::get('custom_gifts', []);
            $martProducts = Session::get('mart_products', []);
            $totalCount = array_sum($cart) + count($customGifts) + array_sum(array_column($martProducts, 'quantity'));
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المنتج للسلة',
            'count' => $totalCount,
            'cart_count' => $totalCount,
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = Session::get('cart', []);
        $martProducts = Session::get('mart_products', []);
        $productId = $request->item_id;

        // Check if it's a Mart product in session
        if (isset($martProducts[$productId])) {
            $qty = (int) $request->quantity;
            $product = Product::find($productId);
            
            if ($product && $product->track_inventory && $qty > (int) ($product->stock_quantity ?? 0)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح للمنتج ' . $product->name,
                    'available' => (int) ($product->stock_quantity ?? 0),
                ], 422);
            }

            if ($qty === 0) {
                unset($martProducts[$productId]);
            } else {
                $martProducts[$productId]['quantity'] = $qty;
            }
            Session::put('mart_products', $martProducts);

            $cartCount = array_sum($cart) + count(Session::get('custom_gifts', [])) + array_sum(array_column($martProducts, 'quantity'));

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث السلة',
                'cart_count' => $cartCount,
                'count' => $cartCount,
            ]);
        }

        if (Auth::check() && $this->canUseDatabaseCart() && is_numeric($productId)) {
            $this->mergeSessionCartIntoDatabaseCart();
            $dbCart = $this->getOrCreateDatabaseCart();
            $productIdInt = (int) $productId;
            $qty = (int) $request->quantity;
            $product = Product::find($productIdInt);

            if ($product && $product->track_inventory && $qty > (int) ($product->stock_quantity ?? 0)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح للمنتج ',
                    'available' => (int) ($product->stock_quantity ?? 0),
                ], 422);
            }

            if ($qty === 0) {
                CartItem::where('cart_id', $dbCart->id)->where('product_id', $productIdInt)->delete();
            } else {
                $item = CartItem::firstOrNew([
                    'cart_id' => $dbCart->id,
                    'product_id' => $productIdInt,
                ]);
                if ($product) {
                    $item->unit_price = $product->discount_price ?? $product->price;
                }
                $item->quantity = $qty;
                $item->save();
            }

            $dbCart->load('items');
            $totalCount = (int) $dbCart->items->sum(fn ($i) => (int) $i->quantity)
                + (int) count(Session::get('custom_gifts', []))
                + (int) array_sum(array_column(Session::get('mart_products', []), 'quantity'));

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث السلة',
                'cart_count' => $totalCount,
                'count' => $totalCount,
            ]);
        }

        if ($request->quantity == 0) {
            unset($cart[$productId]);
        } else {
            $product = Product::find($productId);
            if ($product && $product->track_inventory && (int) $request->quantity > (int) ($product->stock_quantity ?? 0)) {
                return response()->json([
                    'success' => false,
                    'message' =>   'الكمية المطلوبة تتجاوز المخزون المتاح للمنتج  ',
                    'available' => (int) ($product->stock_quantity ?? 0),
                ], 422);
            }

            $cart[$productId] = (int) $request->quantity;
        }

        Session::put('cart', $cart);

        $customGifts = Session::get('custom_gifts', []);
        $martProducts = Session::get('mart_products', []);
        $cartCount = array_sum($cart) + count($customGifts) + array_sum(array_column($martProducts, 'quantity'));

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث السلة',
            'cart_count' => $cartCount,
            'count' => $cartCount,
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $itemId = $request->input('item_id');

        if (! $itemId) {
            return response()->json([
                'success' => false,
                'message' => 'معرف المنتج مطلوب',
            ], 400);
        }

        // Check if it's a custom gift or bouquet
        if (is_string($itemId) && (str_starts_with($itemId, 'custom_gift_') || str_starts_with($itemId, 'custom_bouquet_'))) {
            $customGifts = Session::get('custom_gifts', []);
            unset($customGifts[$itemId]);
            Session::put('custom_gifts', $customGifts);
        }
        // Check if it's a mart product
        else {
            $martProducts = Session::get('mart_products', []);
            $martKeyCandidates = array_values(array_unique([
                (string) $itemId,
                is_numeric($itemId) ? (string) (int) $itemId : null,
                is_string($itemId) ? ltrim($itemId, 'm') : null,
            ]));
            $martKeyCandidates = array_values(array_filter($martKeyCandidates, fn ($v) => $v !== null && $v !== ''));

            $removedFromMart = false;
            foreach ($martKeyCandidates as $key) {
                if (array_key_exists($key, $martProducts)) {
                    unset($martProducts[$key]);
                    $removedFromMart = true;
                }
            }
            if ($removedFromMart) {
                Session::put('mart_products', $martProducts);
            } else {
                if (Auth::check() && $this->canUseDatabaseCart() && is_numeric($itemId)) {
                    $this->mergeSessionCartIntoDatabaseCart();
                    $dbCart = $this->getOrCreateDatabaseCart();
                    CartItem::where('cart_id', $dbCart->id)->where('product_id', (int) $itemId)->delete();
                } else {
                    $cart = Session::get('cart', []);
                    unset($cart[$itemId]);
                    Session::put('cart', $cart);
                }
            }
        }

        // Calculate total count
        $cart = Session::get('cart', []);
        $customGifts = Session::get('custom_gifts', []);
        $martProducts = Session::get('mart_products', []);
        $dbCount = 0;
        if (Auth::check() && $this->canUseDatabaseCart()) {
            $dbCart = Cart::where('user_id', Auth::id())->with('items')->first();
            $dbCount = $dbCart ? (int) $dbCart->items->sum(fn ($i) => (int) $i->quantity) : 0;
        }
        $cartCount = $dbCount + array_sum($cart) + count($customGifts) + array_sum(array_column($martProducts, 'quantity'));

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المنتج من السلة',
            'count' => $cartCount,
            'cart_count' => $cartCount,
        ]);
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        if (Auth::check() && $this->canUseDatabaseCart()) {
            $dbCart = Cart::where('user_id', Auth::id())->first();
            if ($dbCart) {
                CartItem::where('cart_id', $dbCart->id)->delete();
            }
        }

        Session::forget('cart');
        Session::forget('mart_products');
        Session::forget('custom_gifts');

        return response()->json([
            'success' => true,
            'message' => 'تم تفريغ السلة',
            'cart_count' => 0,
            'count' => 0,
        ]);
    }

    /**
     * Get cart items for checkout (includes store products, mart products, and custom gifts).
     */
    public function getItems()
    {
        $items = [];

        if (Auth::check() && $this->canUseDatabaseCart()) {
            $this->mergeSessionCartIntoDatabaseCart();
            $dbCart = $this->getOrCreateDatabaseCart();
            $dbCart?->load(['items.product']);

            if ($dbCart) {
                foreach ($dbCart->items as $item) {
                    if (! $item->product) {
                        continue;
                    }
                    $items[] = [
                        'id' => $item->product->id,
                        'type' => 'product',
                        'product' => [
                            'id' => $item->product->id,
                            'name' => $item->product->name,
                            'image' => $item->product->primary_image_url,
                            'price' => $item->product->price,
                            'discount_price' => $item->product->discount_price,
                        ],
                        'quantity' => (int) $item->quantity,
                    ];
                }
            }

            $customGifts = Session::get('custom_gifts', []);
            foreach ($customGifts as $giftId => $gift) {
                $items[] = [
                    'id' => $giftId,
                    'type' => 'custom_gift',
                    'product' => [
                        'id' => $giftId,
                        'name' => $gift['name'] ?? 'هدية مخصصة',
                        'image' => null,
                        'price' => (float) ($gift['price'] ?? 0),
                        'discount_price' => null,
                    ],
                    'quantity' => 1,
                ];
            }

            $martProducts = Session::get('mart_products', []);
            foreach ($martProducts as $productId => $product) {
                $items[] = [
                    'id' => $productId,
                    'type' => 'mart',
                    'product' => [
                        'id' => $productId,
                        'name' => $product['name'] ?? 'منتج',
                        'image' => $product['image'] ?? null,
                        'price' => (float) ($product['price'] ?? 0),
                        'discount_price' => null,
                        'unit' => $product['unit'] ?? 'قطعة',
                        'emoji' => $product['emoji'] ?? null,
                    ],
                    'quantity' => (int) ($product['quantity'] ?? 0),
                ];
            }

            return response()->json($items);
        }

        $cart = Session::get('cart', []);
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $items[] = [
                    'id' => $product->id,
                    'type' => 'product',
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'image' => $product->primary_image_url,
                        'price' => $product->price,
                        'discount_price' => $product->discount_price,
                    ],
                    'quantity' => $quantity,
                ];
            }
        }

        $martProducts = Session::get('mart_products', []);
        foreach ($martProducts as $productId => $product) {
            $items[] = [
                'id' => $productId,
                'type' => 'mart',
                'product' => [
                    'id' => $productId,
                    'name' => $product['name'] ?? 'منتج',
                    'image' => $product['image'] ?? null,
                    'price' => (float) ($product['price'] ?? 0),
                    'discount_price' => null,
                ],
                'quantity' => (int) ($product['quantity'] ?? 0),
            ];
        }

        $customGifts = Session::get('custom_gifts', []);
        foreach ($customGifts as $giftId => $gift) {
            $items[] = [
                'id' => $giftId,
                'type' => 'custom_gift',
                'product' => [
                    'id' => $giftId,
                    'name' => $gift['name'] ?? 'هدية مخصصة',
                    'image' => null,
                    'price' => (float) ($gift['price'] ?? 0),
                    'discount_price' => null,
                ],
                'quantity' => 1,
            ];
        }

        return response()->json($items);
    }
}
