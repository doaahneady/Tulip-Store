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

    /**
     * Cart badge count should represent the number of distinct items (lines),
     * not the sum of quantities.
     */
    protected function distinctCartCount(): int
    {
        $customGifts = Session::get('custom_gifts', []);

        // Logged-in users: count DB cart lines + session-based custom gifts
        if (Auth::check() && $this->canUseDatabaseCart()) {
            $this->mergeSessionCartIntoDatabaseCart();
            $dbCart = $this->getOrCreateDatabaseCart();
            $dbDistinct = $dbCart ? (int) $dbCart->items()->count() : 0;

            return $dbDistinct + (int) count($customGifts);
        }

        // Guests: count session cart keys + custom gifts + mart products
        $cart = Session::get('cart', []);
        $martProducts = Session::get('mart_products', []);
        return (int) count($cart) + (int) count($customGifts) + (int) count($martProducts);
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

        // Merge regular products
        $sessionCart = Session::get('cart', []);
        if (is_array($sessionCart) && !empty($sessionCart)) {
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

                $cartItem = CartItem::where([
                    'cart_id' => $dbCart->id,
                    'product_id' => $productId,
                    'product_type' => 'regular',
                ])->first();

                if (!$cartItem) {
                    $cartItem = new CartItem([
                        'cart_id' => $dbCart->id,
                        'product_id' => $productId,
                        'product_type' => 'regular',
                    ]);
                }

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

        // Merge mart products
        $martProducts = Session::get('mart_products', []);
        if (is_array($martProducts) && !empty($martProducts)) {
            foreach ($martProducts as $key => $martProduct) {
                $productId = (int) ($martProduct['id'] ?? 0);
                if ($productId <= 0) {
                    continue;
                }

                $isWeightBased = (bool) ($martProduct['is_weight_based'] ?? false);

                // For weight-based products, always create new entries
                // For regular mart products, merge quantities
                if (!$isWeightBased) {
                    $cartItem = CartItem::where([
                        'cart_id' => $dbCart->id,
                        'product_id' => $productId,
                        'product_type' => 'mart',
                        'is_weight_based' => false,
                    ])->first();

                    if ($cartItem) {
                        $cartItem->quantity += (int) ($martProduct['quantity'] ?? 1);
                        $cartItem->save();
                        continue;
                    }
                }

                // Create new cart item for weight-based or new regular mart products
                $cartItem = new CartItem([
                    'cart_id' => $dbCart->id,
                    'product_id' => $productId,
                    'product_type' => 'mart',
                    'quantity' => (int) ($martProduct['quantity'] ?? 1),
                    'unit_price' => (float) ($martProduct['price'] ?? 0),
                    'mart_product_name' => $martProduct['name'] ?? null,
                    'mart_product_image' => $martProduct['image'] ?? null,
                    'mart_product_unit' => $martProduct['unit'] ?? null,
                    'mart_product_emoji' => $martProduct['emoji'] ?? null,
                    'is_weight_based' => $isWeightBased,
                    'weight_grams' => (float) ($martProduct['weight_grams'] ?? 0),
                    'price_per_unit' => (float) ($martProduct['price_per_unit'] ?? 0),
                    'amount_paid' => (float) ($martProduct['amount_paid'] ?? 0),
                ]);
                $cartItem->save();
            }

            Session::forget('mart_products');
        }
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
                    $productType = $item->product_type ?? 'regular';

                    if ($productType === 'mart') {
                        // Mart product from database
                        $isWeightBased = $item->is_weight_based ?? false;
                        
                        if ($isWeightBased) {
                            $amountPaidSyp = (float) ($item->amount_paid ?? 0);
                            $USD_TO_SYP = 13100;
                            $itemSubtotal = $amountPaidSyp / $USD_TO_SYP;
                        } else {
                            $itemSubtotal = ((float) ($item->unit_price ?? 0)) * ((int) $item->quantity);
                        }
                        
                        $subtotal += $itemSubtotal;

                        $items[] = [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'type' => 'mart',
                            'product' => [
                                'id' => $item->product_id,
                                'name' => $item->mart_product_name ?? 'منتج',
                                'image' => $item->mart_product_image ?? null,
                                'price' => $item->unit_price ?? 0,
                                'discount_price' => null,
                                'brand' => 'توليب مارت',
                                'unit' => $item->mart_product_unit ?? 'قطعة',
                                'emoji' => $item->mart_product_emoji ?? null,
                            ],
                            'quantity' => (int) $item->quantity,
                            'subtotal' => $itemSubtotal,
                            'is_weight_based' => $isWeightBased,
                            'weight_grams' => (float) ($item->weight_grams ?? 0),
                            'price_per_unit' => (float) ($item->price_per_unit ?? 0),
                            'amount_paid' => (float) ($item->amount_paid ?? 0),
                        ];
                    } else {
                        // Regular product from database
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
                            'is_weight_based' => $item->is_weight_based ?? false,
                            'weight_grams' => $item->weight_grams ?? 0,
                            'price_per_unit' => $item->price_per_unit ?? 0,
                            'amount_paid' => $item->amount_paid ?? 0,
                        ];
                    }
                }
            }

            // Custom gifts still in session (for now)
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

            $count = $this->distinctCartCount();
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
            $isWeightBased = isset($product['is_weight_based']) && $product['is_weight_based'];
            
            // For weight-based products, amount_paid is in SYP, need to convert to USD
            if ($isWeightBased) {
                $amountPaidSyp = (float) ($product['amount_paid'] ?? 0);
                $USD_TO_SYP = 13100; // Exchange rate
                $itemSubtotal = $amountPaidSyp / $USD_TO_SYP; // Convert SYP to USD
            } else {
                $itemSubtotal = ((float) ($product['price'] ?? 0)) * ((int) ($product['quantity'] ?? 0));
            }
            
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
                // Weight-based fields
                'is_weight_based' => $isWeightBased,
                'weight_grams' => (float) ($product['weight_grams'] ?? 0),
                'price_per_unit' => (float) ($product['price_per_unit'] ?? 0),
                'amount_paid' => (float) ($product['amount_paid'] ?? 0),
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
        $count = $this->distinctCartCount();

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
                'is_weight_based' => 'sometimes|boolean',
                'amount_paid' => 'required_if:is_weight_based,true|nullable|numeric',
                'weight_grams' => 'nullable|numeric',
                'price_per_unit' => 'nullable|numeric',
            ]);

            $product = Product::find($productId);
            $isWeightBased = $request->input('is_weight_based', false);

            // For logged-in users, save to database
            if (Auth::check() && $this->canUseDatabaseCart()) {
                $this->mergeSessionCartIntoDatabaseCart();
                $dbCart = $this->getOrCreateDatabaseCart();

                if ($isWeightBased) {
                    // Weight-based products: always add as new item
                    $cartItem = new CartItem([
                        'cart_id' => $dbCart->id,
                        'product_id' => $productId,
                        'product_type' => 'mart',
                        'quantity' => 1,
                        'unit_price' => $request->input('amount_paid', 0),
                        'mart_product_name' => $request->name,
                        'mart_product_image' => $request->image,
                        'mart_product_unit' => $request->unit ?? 'كيلو غرام',
                        'mart_product_emoji' => $request->emoji,
                        'is_weight_based' => true,
                        'weight_grams' => $request->weight_grams ?? 0,
                        'price_per_unit' => $request->price_per_unit ?? 0,
                        'amount_paid' => $request->amount_paid ?? 0,
                    ]);
                    $cartItem->save();
                } else {
                    // Regular mart products: merge quantities
                    $cartItem = CartItem::where([
                        'cart_id' => $dbCart->id,
                        'product_id' => $productId,
                        'product_type' => 'mart',
                        'is_weight_based' => false,
                    ])->first();

                    if ($cartItem) {
                        $existingQty = (int) $cartItem->quantity;
                        $newQty = $existingQty + (int) $quantity;

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

                        $cartItem->quantity = $newQty;
                        $cartItem->save();
                    } else {
                        if ($product && $product->track_inventory) {
                            $available = (int) ($product->stock_quantity ?? 0);
                            if ($quantity > $available) {
                                return response()->json([
                                    'success' => false,
                                    'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح للمنتج ' . $product->name,
                                    'available' => $available,
                                ], 422);
                            }
                        }

                        $cartItem = new CartItem([
                            'cart_id' => $dbCart->id,
                            'product_id' => $productId,
                            'product_type' => 'mart',
                            'quantity' => $quantity,
                            'unit_price' => $request->price,
                            'mart_product_name' => $request->name,
                            'mart_product_image' => $request->image,
                            'mart_product_unit' => $request->unit ?? 'قطعة',
                            'mart_product_emoji' => $request->emoji,
                        ]);
                        $cartItem->save();
                    }
                }

                $totalCount = $this->distinctCartCount();

                return response()->json([
                    'success' => true,
                    'message' => 'تم إضافة المنتج للسلة',
                    'count' => $totalCount,
                ]);
            }

            // For guests, save to session (existing behavior)
            $martProducts = Session::get('mart_products', []);
            
            if ($isWeightBased) {
                // Weight-based products: always add as new item (don't merge quantities)
                $uniqueId = $productId . '_' . time() . '_' . rand(1000, 9999);
                $martProducts[$uniqueId] = [
                    'id' => $productId,
                    'name' => $request->name,
                    'price' => $request->price,
                    'quantity' => 1,
                    'image' => $request->image,
                    'unit' => $request->unit ?? 'كيلو غرام',
                    'type' => 'mart',
                    'emoji' => $request->emoji,
                    'is_weight_based' => true,
                    'weight_grams' => $request->weight_grams ?? 0,
                    'price_per_unit' => $request->price_per_unit ?? 0,
                    'amount_paid' => $request->amount_paid ?? 0,
                ];
            } else {
                // Regular mart products: merge quantities
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
            }

            Session::put('mart_products', $martProducts);

            $totalCount = $this->distinctCartCount();

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

            $totalCount = $this->distinctCartCount();
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

            $totalCount = $this->distinctCartCount();
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

        $itemId = $request->item_id;
        $qty = (int) $request->quantity;

        // For logged-in users with database cart
        if (Auth::check() && $this->canUseDatabaseCart()) {
            $this->mergeSessionCartIntoDatabaseCart();
            $dbCart = $this->getOrCreateDatabaseCart();

            // Try to find the item in the database cart
            $cartItem = CartItem::where('cart_id', $dbCart->id)
                ->where('id', $itemId)
                ->first();

            if ($cartItem) {
                // Check inventory for non-weight-based products
                if (!$cartItem->is_weight_based) {
                    $product = Product::find($cartItem->product_id);
                    if ($product && $product->track_inventory && $qty > (int) ($product->stock_quantity ?? 0)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح للمنتج',
                            'available' => (int) ($product->stock_quantity ?? 0),
                        ], 422);
                    }
                }

                if ($qty === 0) {
                    $cartItem->delete();
                } else {
                    $cartItem->quantity = $qty;
                    $cartItem->save();
                }

                $cartCount = $this->distinctCartCount();

                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث السلة',
                    'cart_count' => $cartCount,
                    'count' => $cartCount,
                ]);
            }
        }

        // For guests or session-based items
        $cart = Session::get('cart', []);
        $martProducts = Session::get('mart_products', []);

        // Check if it's a Mart product in session
        if (isset($martProducts[$itemId])) {
            $product = Product::find($itemId);
            
            if ($product && $product->track_inventory && $qty > (int) ($product->stock_quantity ?? 0)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح للمنتج ' . $product->name,
                    'available' => (int) ($product->stock_quantity ?? 0),
                ], 422);
            }

            if ($qty === 0) {
                unset($martProducts[$itemId]);
            } else {
                $martProducts[$itemId]['quantity'] = $qty;
            }
            Session::put('mart_products', $martProducts);

            $cartCount = $this->distinctCartCount();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث السلة',
                'cart_count' => $cartCount,
                'count' => $cartCount,
            ]);
        }

        // Regular product in session
        if ($qty == 0) {
            unset($cart[$itemId]);
        } else {
            $product = Product::find($itemId);
            if ($product && $product->track_inventory && $qty > (int) ($product->stock_quantity ?? 0)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح للمنتج',
                    'available' => (int) ($product->stock_quantity ?? 0),
                ], 422);
            }

            $cart[$itemId] = $qty;
        }

        Session::put('cart', $cart);

        $cartCount = $this->distinctCartCount();

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

        // For logged-in users with database cart
        if (Auth::check() && $this->canUseDatabaseCart()) {
            $this->mergeSessionCartIntoDatabaseCart();
            $dbCart = $this->getOrCreateDatabaseCart();

            // Try to find and delete the item by ID (works for both regular and mart products)
            $deleted = CartItem::where('cart_id', $dbCart->id)
                ->where('id', $itemId)
                ->delete();

            if ($deleted) {
                $cartCount = $this->distinctCartCount();
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف المنتج من السلة',
                    'count' => $cartCount,
                    'cart_count' => $cartCount,
                ]);
            }
        }

        // Check if it's a custom gift or bouquet
        if (is_string($itemId) && (str_starts_with($itemId, 'custom_gift_') || str_starts_with($itemId, 'custom_bouquet_'))) {
            $customGifts = Session::get('custom_gifts', []);
            unset($customGifts[$itemId]);
            Session::put('custom_gifts', $customGifts);
        }
        // Check if it's a mart product in session
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
                // Regular product in session
                $cart = Session::get('cart', []);
                unset($cart[$itemId]);
                Session::put('cart', $cart);
            }
        }

        $cartCount = $this->distinctCartCount();

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
                    $productType = $item->product_type ?? 'regular';

                    if ($productType === 'mart') {
                        // Mart product from database
                        $isWeightBased = $item->is_weight_based ?? false;
                        
                        $items[] = [
                            'id' => $item->id,
                            'type' => 'mart',
                            'product' => [
                                'id' => $item->product_id,
                                'name' => $item->mart_product_name ?? 'منتج',
                                'image' => $item->mart_product_image ?? null,
                                'price' => (float) ($item->unit_price ?? 0),
                                'discount_price' => null,
                            ],
                            'quantity' => (int) $item->quantity,
                            'is_weight_based' => $isWeightBased,
                            'weight_grams' => $item->weight_grams ?? 0,
                            'amount_paid' => $item->amount_paid ?? 0,
                            'price_per_unit' => $item->price_per_unit ?? 0,
                        ];
                    } else {
                        // Regular product from database
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
                            'is_weight_based' => $item->is_weight_based ?? false,
                            'weight_grams' => $item->weight_grams ?? 0,
                            'amount_paid' => $item->amount_paid ?? 0,
                            'price_per_unit' => $item->price_per_unit ?? 0,
                        ];
                    }
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
            $isWeightBased = isset($product['is_weight_based']) && $product['is_weight_based'];
            
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
                'is_weight_based' => $isWeightBased,
                'weight_grams' => (float) ($product['weight_grams'] ?? 0),
                'amount_paid' => (float) ($product['amount_paid'] ?? 0),
                'price_per_unit' => (float) ($product['price_per_unit'] ?? 0),
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
