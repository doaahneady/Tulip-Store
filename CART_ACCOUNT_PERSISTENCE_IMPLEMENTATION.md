# Cart Account Persistence Implementation

## Overview
This implementation ensures that cart items (including mart products) are saved to the user's account in the database, allowing them to access their cart from any device when logged in.

## Changes Made

### 1. Database Migration
- Added new migration: `2026_04_23_000001_add_mart_support_to_cart_items.php`
- New fields added to `cart_items` table:
  - `product_type`: Distinguishes between 'regular' and 'mart' products
  - `mart_product_name`: Stores the name of mart products
  - `mart_product_image`: Stores the image URL of mart products
  - `mart_product_unit`: Stores the unit (e.g., 'كيلو غرام', 'قطعة')
  - `mart_product_emoji`: Stores the emoji icon for mart products
- Removed unique constraint on `(cart_id, product_id)` to allow multiple entries for weight-based products

### 2. Model Updates
- Updated `CartItem` model to include new fillable fields for mart products

### 3. Controller Updates (`app/Http/Controllers/CartController.php`)

#### `mergeSessionCartIntoDatabaseCart()` Method
- Now merges both regular products AND mart products from session to database
- Handles weight-based products correctly (creates new entries instead of merging)
- Handles regular mart products (merges quantities)

#### `index()` Method
- Loads mart products from database for logged-in users
- Properly calculates subtotals for weight-based products (SYP to USD conversion)
- Returns correct product information including mart-specific fields

#### `add()` Method
- For logged-in users: Saves mart products to database
- For guests: Continues to use session storage
- Handles both weight-based and regular mart products correctly
- Validates inventory for tracked products

#### `update()` Method
- Updated to work with database cart item IDs
- Handles both regular and mart products from database
- Falls back to session for guests

#### `remove()` Method
- Updated to remove items by cart_item ID from database
- Works for both regular and mart products
- Falls back to session for guests

#### `getItems()` Method
- Returns mart products from database for logged-in users
- Includes all necessary fields for checkout

#### `distinctCartCount()` Method
- Updated to count database cart items for logged-in users
- No longer counts session mart products for logged-in users

## How It Works

### For Logged-In Users:
1. When a user adds a mart product to cart, it's saved directly to the `cart_items` table
2. The `product_type` field is set to 'mart'
3. Mart-specific information is stored in the new fields
4. When the user logs in from another device, the cart is loaded from the database
5. All cart operations (add, update, remove) work with the database

### For Guest Users:
1. Cart items are stored in the session (existing behavior)
2. When they log in, session items are automatically merged into the database cart
3. After merge, session is cleared

### Weight-Based Products:
- Each weight-based purchase creates a new cart item (no quantity merging)
- This allows users to have multiple entries of the same product with different weights
- Example: 500g of tomatoes + 750g of tomatoes = 2 separate cart items

### Regular Mart Products:
- Quantities are merged when adding the same product
- Example: Adding 2 apples + 3 apples = 1 cart item with quantity 5

## Testing

To test the implementation:

1. Log in to your account
2. Add some mart products to your cart (both regular and weight-based)
3. Log out
4. Log in from another browser/device with the same account
5. You should see all the cart items you added earlier

## Benefits

- Cart items persist across devices for logged-in users
- No more lost cart items when switching devices
- Better user experience for families sharing accounts
- Consistent behavior between regular products and mart products
