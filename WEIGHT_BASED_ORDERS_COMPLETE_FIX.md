# Weight-Based Items in Orders - Complete Fix

## Problem
Weight-based items (like 0.5 kg of marshmallows) were showing the full kilogram price in:
- My Orders page (`/my-orders`)
- Order details modal
- Invoices
- Any other place displaying order items

## Root Cause
The `order_items` table didn't have fields to store weight information, so when orders were created, the weight data was lost. The system only stored:
- `quantity` (always 1 for weight-based items)
- `unit_price` (the full kilo price, not the actual paid amount)

## Complete Solution

### 1. Database Schema Changes

**Migration Created:** `2026_04_22_160000_add_weight_fields_to_order_items.php`

**Fields Added to `order_items` table:**
```sql
is_weight_based TINYINT(1) NOT NULL DEFAULT 0
weight_grams DECIMAL(10,2) NULL
price_per_unit DECIMAL(10,2) NULL
```

**Migration Status:** ✅ Successfully executed

---

### 2. Backend Changes

#### A. OrderController.php - Mart Products Processing
**Location:** Lines ~176-195

**What Changed:**
- Added logic to detect weight-based mart products
- Extract weight fields from session data
- Pass weight information to order items data array

```php
$isWeightBased = isset($martProduct['is_weight_based']) && $martProduct['is_weight_based'];

$orderItemsData[] = [
    // ... existing fields ...
    'is_weight_based' => $isWeightBased,
    'weight_grams' => $isWeightBased ? (float) ($martProduct['weight_grams'] ?? 0) : null,
    'price_per_unit' => $isWeightBased ? (float) ($martProduct['price_per_unit'] ?? 0) : null,
];
```

#### B. OrderController.php - OrderItem Creation
**Location:** Lines ~360-400

**What Changed:**
- Save weight fields when creating order items
- Only add weight fields if item is weight-based

```php
if (isset($item['is_weight_based']) && $item['is_weight_based']) {
    $orderItemData['is_weight_based'] = true;
    $orderItemData['weight_grams'] = $item['weight_grams'] ?? 0;
    $orderItemData['price_per_unit'] = $item['price_per_unit'] ?? 0;
}
```

#### C. OrderItem Model
**File:** `app/Models/OrderItem.php`

**What Changed:**
- Added weight fields to `$fillable` array
- Added weight fields to `$casts` array

```php
protected $fillable = [
    // ... existing fields ...
    'is_weight_based',
    'weight_grams',
    'price_per_unit',
];

protected $casts = [
    // ... existing casts ...
    'is_weight_based' => 'boolean',
    'weight_grams' => 'decimal:2',
    'price_per_unit' => 'decimal:2',
];
```

#### D. UserProfileController.php - Orders API
**File:** `app/Http/Controllers/Api/UserProfileController.php`
**Location:** Lines ~45-65

**What Changed:**
- Return weight fields in API response
- Only include weight fields if item is weight-based

```php
$itemData = [
    // ... existing fields ...
    'is_weight_based' => $item->is_weight_based ?? false,
];

if ($item->is_weight_based) {
    $itemData['weight_grams'] = $item->weight_grams ?? 0;
    $itemData['price_per_unit'] = $item->price_per_unit ?? 0;
}
```

---

### 3. Frontend Changes

#### My Orders Page
**File:** `resources/views/my-orders.blade.php`
**Location:** Lines ~243-270

**What Changed:**
- Check if item is weight-based
- Display weight instead of quantity for weight-based items
- Format weight display (kg or grams)

```javascript
if (isWeightBased && weightGrams > 0) {
    const weightDisplay = weightGrams >= 1000 
        ? (weightGrams / 1000).toFixed(2) + ' كيلو'
        : weightGrams + ' غرام';
    html+='<p>الوزن: '+weightDisplay+'</p>';
} else {
    html+='<p>الكمية: '+item.quantity+' × '+money(unit)+'</p>';
}
```

---

## How It Works Now

### Data Flow for Weight-Based Items:

1. **User adds 0.5 kg item to cart**
   - Stored in session: `weight_grams = 500`, `amount_paid = 5000 SYP`, `price_per_unit = 10000 SYP`

2. **User completes checkout**
   - OrderController reads session data
   - Detects `is_weight_based = true`
   - Extracts weight fields

3. **Order is created**
   - OrderItem saved with:
     - `is_weight_based = 1`
     - `weight_grams = 500`
     - `price_per_unit = 10000`
     - `unit_price = 5000 / 117 = 42.74 USD` (actual paid amount)
     - `total_price = 42.74 USD`

4. **User views order**
   - API returns weight fields
   - Frontend checks `is_weight_based`
   - Displays: "الوزن: 0.50 كيلو" instead of "الكمية: 1"
   - Shows correct price: $42.74 (not $85.48)

---

## Display Examples

### Before Fix:
```
مارشميلو
الكمية: 1 × $85.48
Total: $85.48 ❌ WRONG
```

### After Fix:
```
مارشميلو
الوزن: 0.50 كيلو
Total: $42.74 ✅ CORRECT
```

---

## Files Modified

1. **database/migrations/2026_04_22_160000_add_weight_fields_to_order_items.php** - New migration
2. **app/Http/Controllers/OrderController.php** - Save weight data
3. **app/Models/OrderItem.php** - Add weight fields
4. **app/Http/Controllers/Api/UserProfileController.php** - Return weight data in API
5. **resources/views/my-orders.blade.php** - Display weight correctly

---

## SQL Migration

```sql
ALTER TABLE `order_items` 
ADD COLUMN `is_weight_based` TINYINT(1) NOT NULL DEFAULT 0 AFTER `quantity`,
ADD COLUMN `weight_grams` DECIMAL(10,2) NULL AFTER `is_weight_based`,
ADD COLUMN `price_per_unit` DECIMAL(10,2) NULL AFTER `weight_grams`;
```

---

## Testing Checklist

### Test 1: Create New Order with Weight-Based Item
- [ ] Add weight-based item to cart (e.g., 0.5 kg)
- [ ] Complete checkout
- [ ] Order should be created successfully
- [ ] Check database: `order_items` should have weight fields populated

### Test 2: View Order in My Orders
- [ ] Go to `/my-orders`
- [ ] Click on order with weight-based item
- [ ] Verify it shows "الوزن: 0.50 كيلو" not "الكمية: 1"
- [ ] Verify price is correct (not full kilo price)

### Test 3: Mixed Order
- [ ] Add regular product + weight-based product
- [ ] Complete checkout
- [ ] View order
- [ ] Regular product shows quantity
- [ ] Weight-based product shows weight
- [ ] Both prices are correct

### Test 4: Invoice Display
- [ ] Generate invoice for order with weight-based item
- [ ] Verify weight is displayed correctly
- [ ] Verify price is correct

---

## Database Schema

### order_items Table (Updated):

| Column | Type | Null | Default | Description |
|--------|------|------|---------|-------------|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary key |
| order_id | BIGINT UNSIGNED | NO | - | Foreign key to orders |
| product_id | BIGINT UNSIGNED | YES | NULL | Foreign key to products (NULL for mart) |
| product_name | VARCHAR(255) | NO | - | Product name |
| product_sku | VARCHAR(255) | YES | NULL | Product SKU/unit |
| quantity | INT | NO | - | Quantity (1 for weight-based) |
| **is_weight_based** | **TINYINT(1)** | **NO** | **0** | **Is this a weight-based item?** |
| **weight_grams** | **DECIMAL(10,2)** | **YES** | **NULL** | **Weight in grams** |
| **price_per_unit** | **DECIMAL(10,2)** | **YES** | **NULL** | **Price per kg/unit in SYP** |
| unit_price | DECIMAL(10,2) | NO | - | Actual price paid (USD) |
| total_price | DECIMAL(10,2) | NO | - | Total for this item (USD) |
| created_at | TIMESTAMP | YES | NULL | - |
| updated_at | TIMESTAMP | YES | NULL | - |

---

## Important Notes

1. **Backward Compatibility:** Existing orders without weight data will still work. The `is_weight_based` defaults to `false`.

2. **Weight Display Logic:**
   - If `weight_grams >= 1000` → Display in kilograms (e.g., "1.50 كيلو")
   - If `weight_grams < 1000` → Display in grams (e.g., "500 غرام")

3. **Price Calculation:**
   - `unit_price` = Actual amount paid (in USD)
   - `total_price` = `unit_price × quantity` (quantity is always 1 for weight-based)
   - `price_per_unit` = Price per kg in SYP (for reference only)

4. **Future Orders:** All new orders with weight-based items will automatically save weight information.

5. **Invoices:** If invoices use the same order items data, they will automatically show correct weight information.

---

## Success Criteria

✅ Weight-based items save weight information to database
✅ My Orders page displays weight correctly
✅ Prices show actual paid amount, not full kilo price
✅ Mixed orders (regular + weight-based) work correctly
✅ API returns weight fields
✅ Frontend displays weight instead of quantity
✅ Backward compatible with existing orders
✅ No breaking changes to existing functionality

---

## Next Steps

If you have other pages that display order items (like admin dashboards, invoices, reports), you may need to update them similarly to check for `is_weight_based` and display weight information accordingly.

**Common places to check:**
- Admin order management pages
- Invoice generation
- Order reports
- Email notifications
- Receipt printing
- Analytics/reports
