# Weight-Based Products Implementation

## Overview
This implementation adds support for weight-based products (sold by kilogram or gram) in the mart section. Products with unit type "kilogram", "gram", "كيلو", "كيلوغرام", or "غرام" will display an orange scale button instead of the regular blue add-to-cart button.

## Files Created

### 1. Database Migration
**File:** `database/migrations/2026_04_22_000001_add_weight_fields_to_cart_items.php`
- Adds `is_weight_based` (boolean) column to cart_items table
- Adds `weight_grams` (decimal) column to store weight in grams
- Adds `price_per_unit` (decimal) column to store price per kg
- Adds `amount_paid` (decimal) column to store the amount customer wants to pay

### 2. Weight Modal Component
**File:** `resources/views/components/weight-modal.blade.php`
- Modal dialog for weight-based product purchase
- Shows product image and name
- Input field for amount in money (دينار)
- Real-time calculation of weight based on amount
- Displays calculated weight in grams or kilograms
- Shows price per kilogram
- Orange-themed to match weight-based product styling
- Add to cart button that sends weight-based data to API

## Files Modified

### 1. CartItem Model
**File:** `app/Models/CartItem.php`
**Changes:**
- Added new fields to `$fillable` array: `is_weight_based`, `weight_grams`, `price_per_unit`, `amount_paid`
- Added new fields to `$casts` array with proper types

### 2. CartController API
**File:** `app/Http/Controllers/Api/CartController.php`
**Changes:**
- Modified `addItem()` method to handle weight-based products
- Added validation for `is_weight_based` and `amount_paid` parameters
- Calculates weight in grams based on amount paid and price per kg
- Creates separate cart items for each weight-based purchase (no quantity increment)
- Regular products continue to work as before with quantity increment

### 3. Mart Subcategory Products View
**File:** `resources/views/mart/subcategory-products.blade.php`
**Changes:**
- Added CSS for `.add-btn-circle.weight-based` (orange background)
- Added CSS for `.counter-control.weight-based` (orange background)
- Modified `createProductCard()` JavaScript function to:
  - Detect weight-based products by checking unit attribute
  - Show orange scale icon instead of blue plus icon for weight-based products
  - Call `openWeightModal()` instead of `initCartCounter()` for weight-based products
  - Hide quantity counter for weight-based products
- Included weight modal component at end of file

### 4. Cart View (Recommended Update)
**File:** `resources/views/cart.blade.php`
**Recommended Change:**
Add weight display in cart item meta section:
```javascript
${item.is_weight_based ? `<div class="cart-item-meta-item" style="color:#f59e0b;font-weight:600;">
  <i class="fas fa-balance-scale"></i> 
  ${item.weight_grams >= 1000 ? (item.weight_grams / 1000).toFixed(2) + ' كيلو' : item.weight_grams.toFixed(0) + ' غرام'}
</div>` : ''}
```

## How It Works

### For Users:
1. Browse mart products
2. Products sold by weight (kilogram/gram) show an orange scale button
3. Click the scale button to open weight modal
4. Enter desired amount in money (e.g., 5000 دينار)
5. System calculates and displays weight (e.g., "500 غرام" if price is 10,000 د.ع/kg)
6. Click "إضافة إلى السلة" to add to cart
7. In cart, item shows weight and cost

### Technical Flow:
1. Product has attribute `unit` with value "kilogram" or "gram"
2. Frontend detects this and renders orange scale button
3. User clicks scale button → `openWeightModal(productId)` called
4. User enters amount → `calculateWeight()` computes weight
5. User clicks add → `addWeightBasedToCart()` sends API request:
   ```json
   {
     "product_id": 123,
     "quantity": 1,
     "is_weight_based": true,
     "amount_paid": 5000
   }
   ```
6. Backend calculates weight: `weight_grams = (amount_paid / price_per_kg) * 1000`
7. Creates cart item with weight data
8. Cart displays item with weight badge

## Database Schema

### cart_items table (new columns):
```sql
is_weight_based BOOLEAN DEFAULT FALSE
weight_grams DECIMAL(10,2) NULL
price_per_unit DECIMAL(10,2) NULL  -- Price per kg at time of purchase
amount_paid DECIMAL(10,2) NULL     -- Amount customer paid
```

## API Changes

### POST /api/cart/add
**New Parameters:**
- `is_weight_based` (boolean, optional): Set to true for weight-based products
- `amount_paid` (numeric, required if is_weight_based=true): Amount in money

**Example Request:**
```json
{
  "product_id": 456,
  "quantity": 1,
  "is_weight_based": true,
  "amount_paid": 7500
}
```

**Response:** Same as before, includes cart count and item data

## Testing

### To Test Weight-Based Products:
1. Run migration: `php artisan migrate`
2. Ensure a product in mart has attribute `unit` = "kilogram" or "gram"
3. Visit mart subcategory page
4. Look for orange scale button on weight-based products
5. Click scale button
6. Enter amount (e.g., 5000)
7. Verify weight calculation is correct
8. Add to cart
9. Check cart shows weight information

### Sample Product Attribute:
```php
ProductAttribute::create([
    'product_id' => 123,
    'name' => 'unit',
    'value' => 'kilogram',  // or 'gram', 'كيلو', 'كيلوغرام', 'غرام'
]);
```

## Color Scheme
- Regular products: Blue/Green (#059669)
- Weight-based products: Orange (#f59e0b)

## Icons
- Regular products: Plus icon (fa-plus)
- Weight-based products: Scale icon (fa-balance-scale)

## Responsive Design
- Modal is responsive and works on mobile devices
- Weight display adapts to screen size
- Touch-friendly buttons and inputs

## Future Enhancements
1. Allow editing weight/amount for items already in cart
2. Add minimum/maximum weight restrictions
3. Show price breakdown in cart (price per kg × weight)
4. Add weight-based product badge on product cards
5. Support for other units (liters, meters, etc.)
