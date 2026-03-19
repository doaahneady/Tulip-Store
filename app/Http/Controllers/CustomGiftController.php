<?php

namespace App\Http\Controllers;

use App\Models\GiftBox;
use App\Models\GiftCard;
use App\Models\GiftFiller;
use App\Models\GiftRibbon;
use App\Models\GiftWrapping;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomGiftController extends Controller
{
    private function resolvePublicImage(?string $path): string
    {
        $p = trim((string) $path);
        if ($p === '') {
            return '/images/tulip_gift.jpg';
        }

        if (Str::startsWith($p, ['http://', 'https://'])) {
            return $p;
        }

        $p = preg_replace('#^(/storage/)+#', '/storage/', $p);

        if (Str::startsWith($p, '/storage/')) {
            $relative = ltrim(Str::after($p, '/storage/'), '/');
            return $relative !== '' ? '/storage/'.$relative : '/images/tulip_gift.jpg';
        }

        if (Str::startsWith($p, '/images/')) {
            return $p;
        }
        if (Str::startsWith($p, '/')) {
            return $p;
        }
        if (Str::startsWith($p, 'images/')) {
            return '/'.$p;
        }
        if (file_exists(public_path($p))) {
            return '/'.$p;
        }

        $relative = ltrim(preg_replace('#^(public/|storage/)#', '', $p), '/');
        if ($relative === '') {
            return '/images/tulip_gift.jpg';
        }

        return Storage::disk('public')->url($relative);
    }

    /**
     * Sample data - In production, this would come from database
     */
    private function getBoxes()
    {
        if (Schema::hasTable('gift_boxes')) {
            $boxesQuery = GiftBox::query()
                ->when(Schema::hasColumn('gift_boxes', 'is_active'), fn ($q) => $q->where('is_active', true));
            if (Schema::hasColumn('gift_boxes', 'sort_order')) {
                $boxesQuery->orderBy('sort_order');
            }
            $boxes = $boxesQuery->orderBy('id')->get();

            if ($boxes->isNotEmpty()) {
                return $boxes->map(function ($b) {
                    $size = (string) ($b->size ?? '');
                    $emoji = match ($size) {
                        'small' => '📦',
                        'medium' => '🎁',
                        'large' => '🎀',
                        'xl' => '👑',
                        default => '🎁',
                    };

                    return [
                        'id' => (int) $b->id,
                        'name' => (string) $b->name,
                        'emoji' => $emoji,
                        'price' => (float) ($b->price ?? 0),
                        'size' => $size,
                        'maxItems' => (int) ($b->max_items ?? 0),
                        'image' => $this->resolvePublicImage($b->image),
                    ];
                })->values()->all();
            }
        }

        return [];
    }

    private function getFillers()
    {
        if (Schema::hasTable('gift_fillers')) {
            $fillersQuery = GiftFiller::query()
                ->when(Schema::hasColumn('gift_fillers', 'is_active'), fn ($q) => $q->where('is_active', true));
            if (Schema::hasColumn('gift_fillers', 'sort_order')) {
                $fillersQuery->orderBy('sort_order');
            }
            $fillers = $fillersQuery->orderBy('id')->get();

            if ($fillers->isNotEmpty()) {
                return $fillers->map(function ($f) {
                    $category = (string) ($f->category ?? 'other');
                    $emoji = match ($category) {
                        'chocolate' => '🍫',
                        'flower' => '🌸',
                        'perfume' => '🌺',
                        'accessory' => '💍',
                        'candy' => '🍬',
                        default => '✨',
                    };

                    return [
                        'id' => (int) $f->id,
                        'name' => (string) $f->name,
                        'emoji' => $emoji,
                        'price' => (float) ($f->price ?? 0),
                        'category' => $category,
                        'image' => $this->resolvePublicImage($f->image),
                    ];
                })->values()->all();
            }
        }

        return [];
    }

    private function getWrappings()
    {
        if (! Schema::hasTable('gift_wrappings')) {
            return [];
        }

        $wrappingsQuery = GiftWrapping::query()
            ->when(Schema::hasColumn('gift_wrappings', 'is_active'), fn ($q) => $q->where('is_active', true));
        if (Schema::hasColumn('gift_wrappings', 'sort_order')) {
            $wrappingsQuery->orderBy('sort_order');
        }
        $wrappings = $wrappingsQuery->orderBy('id')->get();

        return $wrappings->map(function ($w) {
            return [
                'id' => (int) $w->id,
                'name' => (string) $w->name,
                'emoji' => '🎁',
                'price' => (float) ($w->price ?? 0),
                'image' => $this->resolvePublicImage($w->image),
            ];
        })->values()->all();
    }

    private function getRibbons()
    {
        if (! Schema::hasTable('gift_ribbons')) {
            return [];
        }

        $ribbonsQuery = GiftRibbon::query()
            ->when(Schema::hasColumn('gift_ribbons', 'is_active'), fn ($q) => $q->where('is_active', true));
        if (Schema::hasColumn('gift_ribbons', 'sort_order')) {
            $ribbonsQuery->orderBy('sort_order');
        }
        $ribbons = $ribbonsQuery->orderBy('id')->get();

        return $ribbons->map(function ($r) {
            return [
                'id' => (int) $r->id,
                'name' => (string) $r->name,
                'emoji' => '🎀',
                'price' => (float) ($r->price ?? 0),
                'image' => $this->resolvePublicImage($r->image),
            ];
        })->values()->all();
    }

    private function getCards()
    {
        if (! Schema::hasTable('gift_cards')) {
            return [];
        }

        $cardsQuery = GiftCard::query()
            ->when(Schema::hasColumn('gift_cards', 'is_active'), fn ($q) => $q->where('is_active', true));
        if (Schema::hasColumn('gift_cards', 'sort_order')) {
            $cardsQuery->orderBy('sort_order');
        }
        $cards = $cardsQuery->orderBy('id')->get();

        return $cards->map(function ($c) {
            return [
                'id' => (int) $c->id,
                'name' => (string) $c->name,
                'emoji' => '💌',
                'price' => (float) ($c->price ?? 0),
                'image' => $this->resolvePublicImage($c->image),
            ];
        })->values()->all();
    }

    /**
     * Add custom gift to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'box_id' => 'required|integer',
            'fillers' => 'array',
            'fillers.*.id' => 'integer',
            'fillers.*.qty' => 'integer|min:1',
            'store_products' => 'array',
            'store_products.*.product_id' => 'integer',
            'store_products.*.qty' => 'integer|min:1',
            'wrapping_id' => 'nullable|integer',
            'ribbon_id' => 'nullable|integer',
            'card_id' => 'nullable|integer',
            'message' => 'nullable|string|max:200',
            'recipient_name' => 'nullable|string|max:100',
        ]);

        // Get selected items
        $boxes = $this->getBoxes();
        $fillers = $this->getFillers();
        $wrappings = $this->getWrappings();
        $ribbons = $this->getRibbons();
        $cards = $this->getCards();

        $box = collect($boxes)->firstWhere('id', $request->box_id);
        if (! $box) {
            return response()->json(['success' => false, 'message' => 'صندوق غير صالح'], 400);
        }

        // Calculate total price
        $totalPrice = $box['price'];
        $giftName = $box['name'];
        $giftDescription = [];
        $giftEmojis = [$box['emoji']];

        // Add fillers
        $selectedFillers = [];
        if ($request->fillers) {
            foreach ($request->fillers as $fillerData) {
                $filler = collect($fillers)->firstWhere('id', $fillerData['id']);
                if ($filler) {
                    $qty = $fillerData['qty'];
                    $totalPrice += $filler['price'] * $qty;
                    $selectedFillers[] = [
                        'id' => $filler['id'],
                        'name' => $filler['name'],
                        'emoji' => $filler['emoji'],
                        'price' => $filler['price'],
                        'qty' => $qty,
                    ];
                    $giftDescription[] = $filler['name'].' × '.$qty;
                    for ($i = 0; $i < $qty; $i++) {
                        $giftEmojis[] = $filler['emoji'];
                    }
                }
            }
        }

        // Add wrapping
        $selectedWrapping = null;
        if ($request->wrapping_id) {
            $wrapping = collect($wrappings)->firstWhere('id', $request->wrapping_id);
            if ($wrapping) {
                $totalPrice += $wrapping['price'];
                $selectedWrapping = $wrapping;
                if ($wrapping['price'] > 0) {
                    $giftDescription[] = $wrapping['name'];
                }
            }
        }

        // Add ribbon
        $selectedRibbon = null;
        if ($request->ribbon_id) {
            $ribbon = collect($ribbons)->firstWhere('id', $request->ribbon_id);
            if ($ribbon && $ribbon['id'] !== 5) {
                $totalPrice += $ribbon['price'];
                $selectedRibbon = $ribbon;
                if ($ribbon['price'] > 0) {
                    $giftDescription[] = $ribbon['name'];
                }
                $giftEmojis[] = $ribbon['emoji'];
            }
        }

        // Add card
        $selectedCard = null;
        if ($request->card_id) {
            $card = collect($cards)->firstWhere('id', $request->card_id);
            if ($card && $card['id'] !== 6) {
                $totalPrice += $card['price'];
                $selectedCard = $card;
                if ($card['price'] > 0) {
                    $giftDescription[] = $card['name'];
                }
            }
        }

        // Add Tulip Store products
        $selectedStoreProducts = [];
        if ($request->store_products) {
            foreach ($request->store_products as $pData) {
                $p = Product::query()
                    ->active()
                    ->available()
                    ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', 'store'))
                    ->find($pData['product_id'] ?? 0);
                if ($p) {
                    $qty = max(1, (int) ($pData['qty'] ?? 1));
                    $price = (float) ($p->discount_price ?? $p->price);
                    $totalPrice += $price * $qty;
                    $selectedStoreProducts[] = [
                        'id' => $p->id,
                        'name' => $p->name,
                        'price' => $price,
                        'qty' => $qty,
                        'image' => $p->image ?? null,
                        'category_id' => $p->category_id,
                    ];
                    $giftDescription[] = $p->name.' × '.$qty;
                }
            }
        }

        // Create custom gift item for cart
        $customGiftId = 'custom_gift_'.Str::random(8);

        $customGift = [
            'id' => $customGiftId,
            'type' => 'custom_gift',
            'name' => 'هدية مخصصة - '.$giftName,
            'description' => implode(' + ', $giftDescription),
            'emojis' => $giftEmojis,
            'price' => $totalPrice,
            'box' => $box,
            'fillers' => $selectedFillers,
            'store_products' => $selectedStoreProducts,
            'wrapping' => $selectedWrapping,
            'ribbon' => $selectedRibbon,
            'card' => $selectedCard,
            'message' => $request->message,
            'recipient_name' => $request->recipient_name,
        ];

        // Add to custom gifts session
        $customGifts = Session::get('custom_gifts', []);
        $customGifts[$customGiftId] = $customGift;
        Session::put('custom_gifts', $customGifts);

        // Get total cart count (regular cart + custom gifts)
        $regularCart = Session::get('cart', []);
        $cartCount = array_sum($regularCart) + count($customGifts);

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة الهدية للسلة',
            'cart_count' => $cartCount,
            'gift_id' => $customGiftId,
        ]);
    }

    /**
     * Get all gift options
     */
    public function getOptions()
    {
        return response()->json([
            'boxes' => $this->getBoxes(),
            'fillers' => $this->getFillers(),
            'wrappings' => $this->getWrappings(),
            'ribbons' => $this->getRibbons(),
            'cards' => $this->getCards(),
        ]);
    }

    /**
     * Remove custom gift from cart
     */
    public function removeFromCart(Request $request)
    {
        $giftId = $request->gift_id;

        $customGifts = Session::get('custom_gifts', []);
        unset($customGifts[$giftId]);
        Session::put('custom_gifts', $customGifts);

        $regularCart = Session::get('cart', []);
        $cartCount = array_sum($regularCart) + count($customGifts);

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الهدية من السلة',
            'cart_count' => $cartCount,
        ]);
    }

    /**
     * Bouquet data
     */
    private function getFlowerTypes()
    {
        return [
            ['id' => 1, 'name' => 'ورد أحمر', 'emoji' => '🌹', 'price' => 8],
            ['id' => 2, 'name' => 'ورد وردي', 'emoji' => '🌸', 'price' => 8],
            ['id' => 3, 'name' => 'ورد أبيض', 'emoji' => '🤍', 'price' => 7],
            ['id' => 4, 'name' => 'ورد أصفر', 'emoji' => '🌼', 'price' => 7],
            ['id' => 5, 'name' => 'توليب', 'emoji' => '🌷', 'price' => 10],
            ['id' => 6, 'name' => 'زنبق', 'emoji' => '💮', 'price' => 12],
            ['id' => 7, 'name' => 'أوركيد', 'emoji' => '🪻', 'price' => 15],
            ['id' => 8, 'name' => 'عباد الشمس', 'emoji' => '🌻', 'price' => 9],
            ['id' => 9, 'name' => 'ياسمين', 'emoji' => '🏵️', 'price' => 6],
            ['id' => 10, 'name' => 'لافندر', 'emoji' => '💜', 'price' => 8],
        ];
    }

    private function getBouquetSizes()
    {
        return [
            ['id' => 1, 'name' => 'باقة صغيرة', 'emoji' => '💐', 'price' => 20],
            ['id' => 2, 'name' => 'باقة متوسطة', 'emoji' => '🌺', 'price' => 35],
            ['id' => 3, 'name' => 'باقة كبيرة', 'emoji' => '🌸', 'price' => 50],
            ['id' => 4, 'name' => 'باقة فاخرة', 'emoji' => '👑', 'price' => 80],
        ];
    }

    private function getWrapStyles()
    {
        return [
            ['id' => 1, 'name' => 'ورق كرافت', 'emoji' => '📜', 'price' => 0],
            ['id' => 2, 'name' => 'ورق وردي', 'emoji' => '💗', 'price' => 10],
            ['id' => 3, 'name' => 'ورق ذهبي', 'emoji' => '✨', 'price' => 15],
            ['id' => 4, 'name' => 'قماش ساتان', 'emoji' => '🎀', 'price' => 25],
            ['id' => 5, 'name' => 'صندوق فاخر', 'emoji' => '📦', 'price' => 40],
        ];
    }

    private function getExtraItems()
    {
        return [
            ['id' => 1, 'name' => 'شوكولاتة', 'emoji' => '🍫', 'price' => 25],
            ['id' => 2, 'name' => 'دبدوب صغير', 'emoji' => '🧸', 'price' => 35],
            ['id' => 3, 'name' => 'بالون', 'emoji' => '🎈', 'price' => 15],
            ['id' => 4, 'name' => 'شمعة معطرة', 'emoji' => '🕯️', 'price' => 20],
        ];
    }

    private function getBouquetCards()
    {
        return [
            ['id' => 1, 'name' => 'بطاقة حب', 'emoji' => '💕', 'price' => 5],
            ['id' => 2, 'name' => 'بطاقة عيد ميلاد', 'emoji' => '🎂', 'price' => 5],
            ['id' => 3, 'name' => 'بطاقة تهنئة', 'emoji' => '🎉', 'price' => 5],
            ['id' => 4, 'name' => 'بطاقة شكر', 'emoji' => '🙏', 'price' => 5],
            ['id' => 5, 'name' => 'بدون بطاقة', 'emoji' => '➖', 'price' => 0],
        ];
    }

    /**
     * Add custom bouquet to cart
     */
    public function addBouquetToCart(Request $request)
    {
        $request->validate([
            'flowers' => 'required|array|min:1',
            'flowers.*.id' => 'integer',
            'flowers.*.qty' => 'integer|min:1',
            'size_id' => 'nullable|integer',
            'wrap_id' => 'nullable|integer',
            'extras' => 'array',
            'card_id' => 'nullable|integer',
            'message' => 'nullable|string|max:150',
            'recipient_name' => 'nullable|string|max:100',
        ]);

        $flowerTypes = $this->getFlowerTypes();
        $sizes = $this->getBouquetSizes();
        $wraps = $this->getWrapStyles();
        $extras = $this->getExtraItems();
        $cards = $this->getBouquetCards();

        $totalPrice = 0;
        $bouquetEmojis = [];
        $description = [];

        // Add flowers
        $selectedFlowers = [];
        $totalFlowerCount = 0;
        foreach ($request->flowers as $flowerData) {
            $flower = collect($flowerTypes)->firstWhere('id', $flowerData['id']);
            if ($flower) {
                $qty = $flowerData['qty'];
                $totalPrice += $flower['price'] * $qty;
                $totalFlowerCount += $qty;
                $selectedFlowers[] = [
                    'id' => $flower['id'],
                    'name' => $flower['name'],
                    'emoji' => $flower['emoji'],
                    'price' => $flower['price'],
                    'qty' => $qty,
                ];
                $description[] = $flower['name'].' × '.$qty;
                for ($i = 0; $i < min($qty, 5); $i++) {
                    $bouquetEmojis[] = $flower['emoji'];
                }
            }
        }

        // Add size
        $selectedSize = null;
        if ($request->size_id) {
            $size = collect($sizes)->firstWhere('id', $request->size_id);
            if ($size) {
                $totalPrice += $size['price'];
                $selectedSize = $size;
            }
        }

        // Add wrap
        $selectedWrap = null;
        if ($request->wrap_id) {
            $wrap = collect($wraps)->firstWhere('id', $request->wrap_id);
            if ($wrap) {
                $totalPrice += $wrap['price'];
                $selectedWrap = $wrap;
                $bouquetEmojis[] = $wrap['emoji'];
            }
        }

        // Add extras
        $selectedExtras = [];
        if ($request->extras) {
            foreach ($request->extras as $extraId) {
                $extra = collect($extras)->firstWhere('id', $extraId);
                if ($extra) {
                    $totalPrice += $extra['price'];
                    $selectedExtras[] = $extra;
                    $description[] = $extra['name'];
                }
            }
        }

        // Add card
        $selectedCard = null;
        if ($request->card_id) {
            $card = collect($cards)->firstWhere('id', $request->card_id);
            if ($card && $card['id'] !== 5) {
                $totalPrice += $card['price'];
                $selectedCard = $card;
            }
        }

        // Create bouquet item for cart
        $bouquetId = 'custom_bouquet_'.Str::random(8);
        $sizeName = $selectedSize ? $selectedSize['name'] : 'باقة';

        $customBouquet = [
            'id' => $bouquetId,
            'type' => 'custom_bouquet',
            'name' => 'باقة ورد مخصصة - '.$totalFlowerCount.' وردة',
            'description' => implode(' + ', $description),
            'emojis' => $bouquetEmojis,
            'price' => $totalPrice,
            'flowers' => $selectedFlowers,
            'size' => $selectedSize,
            'wrap' => $selectedWrap,
            'extras' => $selectedExtras,
            'card' => $selectedCard,
            'message' => $request->message,
            'recipient_name' => $request->recipient_name,
        ];

        // Add to custom gifts session
        $customGifts = Session::get('custom_gifts', []);
        $customGifts[$bouquetId] = $customBouquet;
        Session::put('custom_gifts', $customGifts);

        // Get total cart count
        $regularCart = Session::get('cart', []);
        $cartCount = array_sum($regularCart) + count($customGifts);

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة الباقة للسلة',
            'cart_count' => $cartCount,
            'bouquet_id' => $bouquetId,
        ]);
    }
}
