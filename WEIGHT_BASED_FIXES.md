# Weight-Based Products Fixes

## Issues Fixed

### Issue 1: Product Image Not Showing in Weight Modal
**Problem:** Weight modal was showing Tulip Store logo instead of actual product image

**Root Cause:** 
- Image path was not being properly resolved
- No fallback handling for missing images
- Image path might need `/storage/` prefix

**Solution:**
Enhanced image resolution in `openWeightModal()` function:
```javascript
// Get product image with proper fallback
let productImage = product.image || product.primary_image_url || product.imageUrl || '/images/tulip_mart.jpg';

// If image doesn't start with http or /, prepend /storage/
if (productImage && !productImage.startsWith('http') && !productImage.startsWith('/')) {
    productImage = '/storage/' + productImage.replace(/^storage\//, '');
}

image.src = productImage;
image.onerror = function() {
    this.src = '/images/tulip_mart.jpg';
};
```

**File Modified:** `resources/views/components/weight-modal.blade.php`

---

### Issue 2: Cart Total Price Incorrect for Weight-Based Products
**Problem:** Cart total showing SYP 408,720,000 instead of correct amount

**Root Cause:**
- Weight-based products store `amount_paid` in SYP (Syrian Pounds)
- Cart subtotal calculation was treating SYP values as USD
- No currency conversion was happening
- Example: 15,200 SYP was being treated as 15,200 USD!

**Solution:**
Convert SYP to USD when calculating subtotal for weight-based products:

```php
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
```

**Files Modified:** 
- `app/Http/Controllers/CartController.php` (2 locations: logged-in and guest sections)

---

## Technical Details

### Currency Flow for Weight-Based Products

1. **Product Price Storage:** USD in database
2. **Modal Display:** Convert USD → SYP for display
3. **User Input:** Amount in SYP
4. **Session Storage:** `amount_paid` stored in SYP
5. **Cart Calculation:** Convert SYP → USD for subtotal
6. **Cart Display:** Convert USD → SYP for display

### Conversion Formula

```
Price per Kilo (SYP) = Price (USD) × 13,100
Amount Paid (USD) = Amount Paid (SYP) ÷ 13,100
```

### Example Calculation

**Product:** عسل جبلي (Mountain Honey)
- Price: $8.00 USD per kilo
- Price in SYP: 8.00 × 13,100 = 104,800 ل.س per kilo

**User Purchase:**
- Amount entered: 15,200 ل.س
- Weight: 15,200 ÷ 104,800 = 0.145 kg = 145 grams

**Cart Calculation:**
- Amount paid (SYP): 15,200
- Amount paid (USD): 15,200 ÷ 13,100 = $1.16
- Subtotal adds: $1.16 (not 15,200!)

**Cart Display:**
- Shows: 15,200 ل.س (converted back from USD)
- Total: Correct sum in SYP

---

## Before vs After

### Before Fix

**Weight Modal:**
```
┌─────────────────────────────────┐
│  [Tulip Store Logo]  ⚖️         │  ← Wrong image!
│                                 │
│  عسل جبلي                       │
│  سعر الكيلو: 104,800 ل.س        │
└─────────────────────────────────┘
```

**Cart Total:**
```
المجموع الفرعي: SYP 408,720,000  ← WRONG! (15,200 × 13,100 × 2)
الإجمالي: SYP 408,720,000
```

### After Fix

**Weight Modal:**
```
┌─────────────────────────────────┐
│  [Product Image]  ⚖️            │  ← Correct image!
│                                 │
│  عسل جبلي                       │
│  سعر الكيلو: 104,800 ل.س        │
└─────────────────────────────────┘
```

**Cart Total:**
```
المجموع الفرعي: SYP 31,200  ← CORRECT! (15,200 + 16,000)
الإجمالي: SYP 31,200
```

---

## Files Changed

### 1. `resources/views/components/weight-modal.blade.php`
**Changes:**
- Enhanced image path resolution
- Added `/storage/` prefix handling
- Added `onerror` fallback handler
- Support for multiple image field names

**Lines Modified:** ~405-420

### 2. `app/Http/Controllers/CartController.php`
**Changes:**
- Added SYP to USD conversion for weight-based products
- Fixed subtotal calculation (logged-in users section)
- Fixed subtotal calculation (guest users section)
- Added debug logging for troubleshooting

**Lines Modified:** 
- Lines ~175-210 (logged-in section)
- Lines ~250-280 (guest section)

---

## Testing Instructions

### Test 1: Product Image in Modal
1. Go to `http://127.0.0.1:8000/mart/products`
2. Find a weight-based product (orange button)
3. Click the orange scale button
4. ✅ Modal should show actual product image
5. ✅ If image fails to load, should show tulip_mart.jpg fallback

### Test 2: Cart Total Calculation
1. Clear cart
2. Add a weight-based product:
   - Product: عسل جبلي (8 USD/kilo = 104,800 ل.س/kilo)
   - Enter amount: 15,200 ل.س
   - Expected weight: ~145 grams
3. Add another weight-based product:
   - Product: مارشميلو (3.6 USD/kilo = 47,160 ل.س/kilo)
   - Enter amount: 16,000 ل.س
   - Expected weight: ~339 grams
4. Go to cart
5. ✅ Item 1 should show: 15,200 ل.س
6. ✅ Item 2 should show: 16,000 ل.س
7. ✅ Subtotal should show: ~31,200 ل.س (not millions!)
8. ✅ Total should match subtotal

### Test 3: Mixed Cart (Regular + Weight-Based)
1. Clear cart
2. Add regular product (e.g., 10 USD × 2 = 20 USD)
3. Add weight-based product (e.g., 15,200 ل.س = ~1.16 USD)
4. Go to cart
5. ✅ Regular product: Shows 20 USD = 262,000 ل.س
6. ✅ Weight product: Shows 15,200 ل.س
7. ✅ Total: ~277,200 ل.س (20 + 1.16 = 21.16 USD × 13,100)

---

## Debug Information

### Check Session Data
```php
// In tinker or controller
$martProducts = Session::get('mart_products');
dd($martProducts);

// Expected structure for weight-based:
[
    '3072_1234567890_5678' => [
        'id' => 3072,
        'name' => 'عسل جبلي',
        'price' => 8.00,  // USD
        'quantity' => 1,
        'is_weight_based' => true,
        'amount_paid' => 15200,  // SYP
        'weight_grams' => 145,
        'price_per_unit' => 104800,  // SYP
    ]
]
```

### Check Cart API Response
```bash
curl http://127.0.0.1:8000/api/cart
```

Expected response:
```json
{
    "items": [
        {
            "id": "3072_1234567890_5678",
            "type": "mart",
            "is_weight_based": true,
            "amount_paid": 15200,
            "weight_grams": 145,
            "subtotal": 1.16  // USD (15200 / 13100)
        }
    ],
    "subtotal": 1.16,  // USD
    "total": 1.16
}
```

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log | grep "CART INDEX DEBUG"
```

Should show:
```
[2026-04-22] local.INFO: === CART INDEX DEBUG ===
[2026-04-22] local.INFO: Mart products count: 1
[2026-04-22] local.INFO: Processing mart product ID: 3072_1234567890_5678
[2026-04-22] local.INFO: Item subtotal (USD): 1.16
```

---

## Common Issues & Solutions

### Issue: Image still not showing
**Solution:** 
- Check if product has `image` field in database
- Verify image file exists in `storage/app/public/products/`
- Run: `php artisan storage:link`

### Issue: Total still wrong
**Solution:**
- Clear browser cache (Ctrl+Shift+R)
- Clear Laravel cache: `php artisan cache:clear`
- Check exchange rate is 13100

### Issue: Weight calculation wrong
**Solution:**
- Verify `price_per_unit` is in SYP
- Check `amount_paid` is in SYP
- Formula: weight = (amount_paid / price_per_unit) × 1000

---

## Summary

✅ **Fixed:** Product image now shows correctly in weight modal  
✅ **Fixed:** Cart total calculation now correct for weight-based products  
✅ **Added:** Proper SYP ↔ USD conversion  
✅ **Added:** Image fallback handling  
✅ **Added:** Debug logging for troubleshooting  

**Files Modified:** 2  
**Lines Changed:** ~50  
**Status:** ✅ Complete & Tested  

---

**Date:** April 22, 2026  
**Version:** 1.1.0
