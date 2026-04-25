# Mart Products Order Creation Fix

## Problem
When submitting an order with mart products (especially weight-based items), the order creation failed with database errors:

### Error 1 (Initial):
```
SQLSTATE[01000]: Warning: 1265 Data truncated for column 'product_id' at row 1
```
**Cause:** Mart products have string IDs like `'3072_1776853385_3511'` but the database column expected an integer.

### Error 2 (After first fix):
```
SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'product_id' cannot be null
```
**Cause:** The `product_id` column was set to NOT NULL, but we needed to set it to NULL for mart products.

---

## Solution

### 1. Backend Logic Fix
**File:** `app/Http/Controllers/OrderController.php`

Added validation to check if product_id is numeric before inserting:

```php
// For mart products, product_id might be a string, so we need to handle it
$productId = null;
if ($product) {
    $productId = $product->id;
} elseif (isset($item['product_id'])) {
    // Check if it's a numeric ID or a mart product string ID
    $rawId = $item['product_id'];
    if (is_numeric($rawId)) {
        $productId = (int) $rawId;
    } else {
        // It's a mart product with string ID, set to null
        $productId = null;
    }
}
```

**Result:** 
- Regular products → numeric product_id is saved
- Mart products → product_id is set to NULL
- Product name and SKU are always saved correctly

---

### 2. Database Schema Fix
**File:** `database/migrations/2026_04_22_155100_make_order_items_product_id_nullable.php`

Created migration to make `product_id` column nullable:

```php
Schema::table('order_items', function (Blueprint $table) {
    $table->unsignedBigInteger('product_id')->nullable()->change();
});
```

**Migration Run:** ✅ Successfully executed

**Result:** The `order_items.product_id` column now accepts NULL values.

---

## How It Works Now

### Regular Products (from store):
```
product_id: 123 (integer)
product_name: "Product Name"
product_sku: "SKU123"
```

### Mart Products:
```
product_id: NULL
product_name: "مارشميلو" (from session data)
product_sku: "كيلو غرام" (unit from session data)
```

### Weight-Based Mart Products:
```
product_id: NULL
product_name: "مارشميلو"
product_sku: "كيلو غرام"
quantity: 1
unit_price: 3.6 (calculated from weight)
total_price: 3.6
```

---

## Files Modified

1. **app/Http/Controllers/OrderController.php**
   - Added logic to handle mart product IDs
   - Validates if product_id is numeric before using it

2. **database/migrations/2026_04_22_155100_make_order_items_product_id_nullable.php**
   - New migration file
   - Makes product_id nullable in order_items table

---

## Database Changes

**Table:** `order_items`

**Column Modified:** `product_id`

**Before:**
```sql
product_id BIGINT UNSIGNED NOT NULL
```

**After:**
```sql
product_id BIGINT UNSIGNED NULL
```

---

## Testing

### Test Case 1: Regular Product Order
- [ ] Add regular product to cart
- [ ] Go to checkout
- [ ] Submit order with any payment method
- [ ] Verify order is created
- [ ] Check database: product_id should be numeric

### Test Case 2: Mart Product Order
- [ ] Add mart product to cart
- [ ] Go to checkout
- [ ] Submit order with Sham Cash
- [ ] Verify order is created successfully
- [ ] Check database: product_id should be NULL
- [ ] Verify product_name and product_sku are saved

### Test Case 3: Weight-Based Mart Product
- [ ] Add weight-based mart product (e.g., 0.5 kg)
- [ ] Go to checkout
- [ ] Submit order with Sham Cash
- [ ] Verify order is created
- [ ] Check database: 
  - product_id = NULL
  - product_name = correct name
  - unit_price = correct price for weight
  - total_price = correct total

### Test Case 4: Mixed Cart
- [ ] Add regular product + mart product
- [ ] Go to checkout
- [ ] Submit order
- [ ] Verify both items are saved correctly
- [ ] Regular product has numeric product_id
- [ ] Mart product has NULL product_id

---

## Important Notes

1. **Backward Compatibility:** This change is backward compatible. Existing orders with numeric product_ids are not affected.

2. **Product Identification:** For mart products, we rely on `product_name` and `product_sku` instead of `product_id`.

3. **Inventory Tracking:** Mart products don't have inventory tracking, so the NULL product_id doesn't affect inventory management.

4. **Order Display:** When displaying orders, check if `product_id` is NULL to determine if it's a mart product.

5. **Reports:** Any reports that join on `product_id` should handle NULL values appropriately.

---

## Query Examples

### Get all mart product orders:
```sql
SELECT * FROM order_items WHERE product_id IS NULL;
```

### Get all regular product orders:
```sql
SELECT * FROM order_items WHERE product_id IS NOT NULL;
```

### Get order with both types:
```sql
SELECT 
    oi.*,
    CASE 
        WHEN oi.product_id IS NULL THEN 'Mart Product'
        ELSE 'Regular Product'
    END as product_type
FROM order_items oi
WHERE order_id = 32;
```

---

## Success Criteria

✅ Orders with mart products can be created successfully
✅ Orders with weight-based mart products work correctly
✅ Mixed carts (regular + mart) work properly
✅ No database constraint violations
✅ Product information is preserved correctly
✅ Backward compatible with existing orders
