<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CustomGiftController extends Controller
{
    /**
     * Sample data - In production, this would come from database
     */
    private function getBoxes()
    {
        return [
            ['id' => 1, 'name' => 'صندوق صغير', 'emoji' => '📦', 'price' => 25, 'size' => 'small', 'maxItems' => 3],
            ['id' => 2, 'name' => 'صندوق متوسط', 'emoji' => '🎁', 'price' => 45, 'size' => 'medium', 'maxItems' => 5],
            ['id' => 3, 'name' => 'صندوق كبير', 'emoji' => '🎀', 'price' => 75, 'size' => 'large', 'maxItems' => 8],
            ['id' => 4, 'name' => 'صندوق فاخر', 'emoji' => '👑', 'price' => 120, 'size' => 'xl', 'maxItems' => 12],
        ];
    }

    private function getFillers()
    {
        return [
            ['id' => 1, 'name' => 'شوكولاتة فيريرو', 'emoji' => '🍫', 'price' => 35, 'category' => 'chocolate'],
            ['id' => 2, 'name' => 'شوكولاتة جوديفا', 'emoji' => '🍫', 'price' => 55, 'category' => 'chocolate'],
            ['id' => 3, 'name' => 'باقة ورد أحمر', 'emoji' => '🌹', 'price' => 45, 'category' => 'flower'],
            ['id' => 4, 'name' => 'زهور بيضاء', 'emoji' => '🌸', 'price' => 35, 'category' => 'flower'],
            ['id' => 5, 'name' => 'عطر فاخر', 'emoji' => '🌺', 'price' => 150, 'category' => 'perfume'],
            ['id' => 6, 'name' => 'عطر صغير', 'emoji' => '💐', 'price' => 75, 'category' => 'perfume'],
            ['id' => 7, 'name' => 'سوار ذهبي', 'emoji' => '💍', 'price' => 85, 'category' => 'accessory'],
            ['id' => 8, 'name' => 'قلادة فضية', 'emoji' => '📿', 'price' => 65, 'category' => 'accessory'],
            ['id' => 9, 'name' => 'حلوى ملونة', 'emoji' => '🍬', 'price' => 20, 'category' => 'candy'],
            ['id' => 10, 'name' => 'مارشميلو', 'emoji' => '🍡', 'price' => 15, 'category' => 'candy'],
            ['id' => 11, 'name' => 'دبدوب صغير', 'emoji' => '🧸', 'price' => 40, 'category' => 'toy'],
            ['id' => 12, 'name' => 'شمعة معطرة', 'emoji' => '🕯️', 'price' => 30, 'category' => 'other'],
        ];
    }

    private function getWrappings()
    {
        return [
            ['id' => 1, 'name' => 'تغليف كلاسيكي', 'emoji' => '🎁', 'price' => 0],
            ['id' => 2, 'name' => 'تغليف ذهبي', 'emoji' => '✨', 'price' => 15],
            ['id' => 3, 'name' => 'تغليف وردي', 'emoji' => '💝', 'price' => 10],
            ['id' => 4, 'name' => 'تغليف أزرق', 'emoji' => '💙', 'price' => 10],
        ];
    }

    private function getRibbons()
    {
        return [
            ['id' => 1, 'name' => 'شريط ذهبي', 'emoji' => '🎀', 'price' => 5],
            ['id' => 2, 'name' => 'شريط أحمر', 'emoji' => '❤️', 'price' => 5],
            ['id' => 3, 'name' => 'شريط وردي', 'emoji' => '💗', 'price' => 5],
            ['id' => 4, 'name' => 'شريط أبيض', 'emoji' => '🤍', 'price' => 5],
            ['id' => 5, 'name' => 'بدون شريط', 'emoji' => '➖', 'price' => 0],
        ];
    }

    private function getCards()
    {
        return [
            ['id' => 1, 'name' => 'بطاقة عيد ميلاد', 'emoji' => '🎂', 'price' => 5],
            ['id' => 2, 'name' => 'بطاقة حب', 'emoji' => '💕', 'price' => 5],
            ['id' => 3, 'name' => 'بطاقة تهنئة', 'emoji' => '🎉', 'price' => 5],
            ['id' => 4, 'name' => 'بطاقة شكر', 'emoji' => '🙏', 'price' => 5],
            ['id' => 5, 'name' => 'بطاقة عيد', 'emoji' => '🌙', 'price' => 5],
            ['id' => 6, 'name' => 'بدون بطاقة', 'emoji' => '➖', 'price' => 0],
        ];
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
