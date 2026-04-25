# Quick Fix Reference - Weight-Based Items in Checkout

## The Problem
Weight-based items in checkout were showing the full kilogram price instead of the actual price for the weight the user selected.

## The Root Cause
The `/api/cart/items` endpoint (used by checkout) was NOT returning the weight-based fields that the main cart page uses.

## The Solution

### Backend Changes (CartController.php)
Added weight-based fields to the `getItems()` method in 3 places:

1. **For authenticated users with database cart** (lines ~717-730)
2. **For authenticated users with mart products** (lines ~750-765)  
3. **For guest users with mart products** (lines ~790-805)

Added these fields to each item:
```php
'is_weight_based' => $isWeightBased,
'weight_grams' => (float) ($product['weight_grams'] ?? 0),
'amount_paid' => (float) ($product['amount_paid'] ?? 0),
'price_per_unit' => (float) ($product['price_per_unit'] ?? 0),
```

### Frontend Changes (checkout.js)
Modified the `loadCartSummary()` function to:

1. Check if item is weight-based: `item.is_weight_based`
2. If yes, use `amount_paid` (in SYP) and convert to USD:
   ```javascript
   const amountPaidSYP = parseFloat(item.amount_paid || 0);
   const amountPaidUSD = amountPaidSYP / usdToSyp;
   ```
3. Display weight instead of quantity:
   ```javascript
   const weightDisplay = weightGrams >= 1000 
       ? `${(weightGrams / 1000).toFixed(2)} كيلو`
       : `${weightGrams} غرام`;
   ```

## How It Works Now

### Data Flow:
1. User adds 0.5 kg of tomatoes at 10,000 SYP/kg
2. Backend stores: `amount_paid = 5000` (SYP), `weight_grams = 500`
3. Checkout API returns these fields
4. Frontend converts: `5000 SYP ÷ 117 = 42.74 USD`
5. Display shows: "الوزن: 0.50 كيلو" and "$42.74"

### Before vs After:

**Before:**
- Display: "الكمية: 1 × $10.00" 
- Total: $10.00 (WRONG - full kilo price)

**After:**
- Display: "الوزن: 0.50 كيلو"
- Total: $5.00 (CORRECT - actual weight price)

## Files Modified:
1. `app/Http/Controllers/CartController.php` - Added weight fields to API
2. `public/js/checkout.js` - Fixed price calculation logic

## Testing:
1. Add a weight-based item (e.g., 0.5 kg)
2. Go to checkout
3. Verify weight and price are correct
4. Check total calculation includes correct amount

## Exchange Rate:
- Default: 117 SYP = 1 USD
- Can be changed via `window.TULIP_USD_TO_SYP`
