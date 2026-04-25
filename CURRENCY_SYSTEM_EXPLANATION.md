# Currency System - Already Implemented ✅

## Overview
The Tulip Store already has a **fully functional currency system** that displays prices in the user's preferred currency (USD or SYP) across all pages.

## How It Works

### 1. User Currency Preference
Users can set their preferred currency in their profile:
- **USD** (US Dollar)
- **SYP** (Syrian Pound)

The preference is stored in:
- Database: `users.currency` column
- LocalStorage: `tulip_currency` key
- Session: `currency` key (for guests)

### 2. Exchange Rate
The exchange rate is managed by administrators in the system settings:
- **Setting Key**: `usd_to_syp_rate`
- **Default Value**: 117 SYP per 1 USD
- **Location**: Admin Dashboard → System Settings

### 3. Global Functions

#### `window.formatMoney(amountUsd)`
Converts and formats prices based on user preference:
```javascript
// If user prefers SYP:
formatMoney(10) → "1,170 SYP"

// If user prefers USD:
formatMoney(10) → "$10.00"
```

#### `window.formatDualMoney(amountUsd)`
Shows both currencies:
```javascript
formatDualMoney(10) → "$10.00 • 1,170 SYP"
```

#### `window.getCurrencyPreference()`
Returns current user preference:
```javascript
getCurrencyPreference() → "SYP" or "USD"
```

#### `window.setCurrencyPreference(currency)`
Updates user preference:
```javascript
setCurrencyPreference('SYP') // Switch to Syrian Pounds
setCurrencyPreference('USD') // Switch to US Dollars
```

### 4. Global Variables

#### `window.TULIP_USD_TO_SYP`
The current exchange rate:
```javascript
console.log(window.TULIP_USD_TO_SYP); // 117 (or current rate)
```

## Implementation Details

### Location
The currency system is initialized in:
- **File**: `resources/views/components/navbar.blade.php`
- **Lines**: 120-174

### Initialization Flow
```javascript
1. Load exchange rate from system settings
2. Get user's preferred currency from:
   - User profile (if logged in)
   - LocalStorage (if previously set)
   - Session (for guests)
   - Default: SYP
3. Set global variables and functions
4. Make available to all pages
```

### Price Storage
- **Store Products**: Prices stored in **USD** in database
- **Mart Products**: Prices stored in **SYP** in database
- **Display**: Converted based on user preference using `formatMoney()`

## Usage Across Pages

### ✅ Already Working On:
1. **Store Page** (`/store`)
   - Product cards
   - Product details
   - Cart items
   - Order totals

2. **Mart Page** (`/mart`)
   - Product cards
   - Daily prices
   - Search results
   - Cart items

3. **Cart Page** (`/cart`)
   - Item prices
   - Subtotals
   - Discounts
   - Total amount

4. **Profile Page** (`/profile`)
   - Order history
   - Order details
   - Balance display

5. **Checkout Page** (`/checkout`)
   - Order summary
   - Delivery costs
   - Final total

6. **Search Results**
   - Product prices in search dropdown
   - Filtered results

## Example Usage in Code

### Display Product Price
```javascript
const product = { price: 10, discount_price: 8 };
const displayPrice = product.discount_price || product.price;

// This automatically shows in user's preferred currency
const priceHTML = `
    <div class="price">
        ${window.formatMoney(displayPrice)}
    </div>
`;
```

### Display with Old Price
```javascript
const product = { price: 10, discount_price: 8 };

const priceHTML = `
    <div class="price-current">
        ${window.formatMoney(product.discount_price)}
    </div>
    <div class="price-old">
        ${window.formatMoney(product.price)}
    </div>
`;
```

### Cart Total
```javascript
const cartTotal = 150; // USD in database

// Automatically converts and displays
document.getElementById('total').textContent = window.formatMoney(cartTotal);
// Shows: "$150.00" or "17,550 SYP" based on preference
```

## Admin Configuration

### Update Exchange Rate
1. Go to Admin Dashboard
2. Navigate to System Settings
3. Update "USD to SYP Rate" field
4. Save changes
5. Rate updates immediately across all pages

### Current Rate
The system uses the rate from `system_settings` table:
```sql
SELECT value FROM system_settings WHERE key = 'usd_to_syp_rate';
```

## User Experience

### Changing Currency Preference
Users can change their currency preference in:
1. **Profile Page**: Currency selector dropdown
2. **Automatic**: Preference saved to database and localStorage
3. **Persistent**: Preference remembered across sessions

### Visual Indicators
- Currency symbol shown with all prices
- Consistent formatting across all pages
- Automatic conversion in real-time

## Technical Notes

### Price Conversion Logic
```javascript
// USD to SYP
const priceInSYP = priceInUSD * USD_TO_SYP;

// SYP to USD
const priceInUSD = priceInSYP / USD_TO_SYP;
```

### Rounding
- SYP prices are rounded to nearest integer
- USD prices show 2 decimal places

### Formatting
- SYP: `1,170 SYP` (with thousand separators)
- USD: `$10.00` (with dollar sign and 2 decimals)

## Testing

### Verify Currency System
1. **Login** to your account
2. **Go to Profile** page
3. **Change currency** preference (USD ↔ SYP)
4. **Navigate** to different pages:
   - Store
   - Mart
   - Cart
   - Checkout
5. **Verify** all prices update automatically

### Expected Behavior
- All prices should display in selected currency
- Cart totals should update
- Order history should show in selected currency
- Search results should show in selected currency

## Troubleshooting

### Prices Not Converting
1. Check if navbar is included: `@include('components.navbar')`
2. Verify `formatMoney` function exists: `console.log(window.formatMoney)`
3. Check exchange rate: `console.log(window.TULIP_USD_TO_SYP)`
4. Verify user preference: `console.log(window.getCurrencyPreference())`

### Exchange Rate Not Updating
1. Clear browser cache
2. Check system settings in database
3. Verify admin has updated the rate
4. Reload the page

## Conclusion

✅ **The currency system is fully implemented and working!**

All prices across the site automatically display in the user's preferred currency. The system:
- Respects user preferences
- Converts prices automatically
- Updates in real-time
- Works across all pages
- Persists across sessions

**No additional changes needed** - the system is already functioning as requested!

---

**Last Updated**: April 25, 2026
**Status**: Fully Implemented ✅
**Location**: `resources/views/components/navbar.blade.php`
