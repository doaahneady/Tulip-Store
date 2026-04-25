# Recharge System Implementation

## Overview
Implemented a complete balance recharge system that allows users to add funds to their account using multiple payment methods.

## Status: ✅ COMPLETE

## Features Implemented

### 1. Profile Page Updates ✅
**File**: `resources/views/profile.blade.php`

- Added "شحن رصيد" (Recharge Balance) button in the balance section
- Updated balance display to show currency-based amounts (USD/SYP)
- Added JavaScript function `updateBalanceDisplay()` to convert balance based on selected currency
- Balance automatically updates when user switches between USD and SYP

### 2. Recharge Page ✅
**File**: `resources/views/recharge.blade.php`

Created a new dedicated recharge page with three payment options:

#### Payment Option 1: Cash Payment
- Shows an interactive Leaflet map with satellite view (ArcGIS World Imagery)
- Storage location from checkout page:
  - Coordinates: 32.749925, 36.573006 (Sweida, Syria)
  - Name: Tulip mart توليب مارت
- Displays storage information:
  - Location marker on satellite map
  - Working hours: 9 AM - 9 PM
  - Contact phone number: 0123456789
  - Instructions to visit the storage
- Clicking the marker opens Google Maps for directions
- User acknowledges they will visit the storage

#### Payment Option 2: Credit Card (Disabled - Coming Soon)
- Marked as "قريباً" (Coming Soon)
- Grayed out and unclickable
- Shows tooltip on hover
- Will allow users to:
  - Enter amount in USD
  - Enter card details
  - Process payment instantly
  - Add balance immediately upon successful payment

#### Payment Option 3: Sham Cash
- Shows exact same information as checkout page:
  - Account image from `/images/shamcash.jpeg`
  - Account number: `cc8571e4f93387893e15f39cda36f45a`
  - Copy button to copy account number
  - Payment instructions
  - WhatsApp contact: +963 968355553
  - WhatsApp button to send payment proof
- No amount input required (user transfers any amount they want)
- User transfers money, takes screenshot, and sends via WhatsApp
- Admin manually adds the transferred amount to user's balance after verification

### 3. Routes ✅

#### Web Routes (`routes/web.php`)
```php
Route::get('/recharge', function () {
    return view('recharge');
})->name('recharge')->middleware('auth');
```

#### API Routes (`routes/api.php`)
```php
Route::middleware('auth:sanctum')->prefix('recharge')->group(function () {
    Route::post('/shamcash', [UserProfileController::class, 'rechargeShamCash']);
    Route::post('/card', [UserProfileController::class, 'rechargeCard']);
});
```

### 4. Controller Methods ✅
**File**: `app/Http/Controllers/Api/UserProfileController.php`

#### `rechargeShamCash(Request $request)`
- Validates amount (minimum $1)
- Converts USD to SYP using exchange rate
- Logs recharge request for admin review
- Returns success message

#### `rechargeCard(Request $request)`
- Validates amount and card token
- Converts USD to SYP using exchange rate
- Processes payment (ready for Stripe integration)
- Adds balance to user account immediately
- Returns new balance

## User Flow

### Cash Payment Flow
1. User clicks "شحن رصيد" button in profile
2. Selects "الدفع نقداً" (Cash Payment)
3. Views storage location on interactive map
4. Reads storage information (hours, phone, address)
5. Clicks marker to get directions in Google Maps
6. Visits storage to pay cash and recharge balance
7. Staff manually adds balance to user account

### Sham Cash Flow
1. User clicks "شحن رصيد" button in profile
2. Selects "Sham Cash"
3. Views exact same information as checkout:
   - Account image
   - Account number with copy button
   - Payment instructions
   - WhatsApp contact information
4. Transfers desired amount to provided account (cc8571e4f93387893e15f39cda36f45a)
5. Takes screenshot of payment proof
6. Sends proof via WhatsApp to +963 968355553
7. Clicks "فهمت، سأرسل إثبات الدفع" button
8. Admin receives WhatsApp message with payment proof
9. Admin manually verifies and adds the amount to user's balance

### Credit Card Flow (Coming Soon)
1. User clicks "شحن رصيد" button in profile
2. Selects "بطاقة ائتمان" (Credit Card)
3. Enters desired amount in USD
4. Enters card details (number, name, expiry, CVV)
5. Submits payment
6. Payment processed through Stripe
7. Balance added immediately upon success
8. User redirected to profile with updated balance

## Currency Conversion

### Exchange Rate
- Configured in `config/app.php` as `usd_to_syp_rate`
- Default: 13,000 SYP = 1 USD
- Used for all conversions

### Balance Display
- Stored in database as SYP (Syrian Pounds)
- Displayed based on user's currency preference
- Automatically converts when user switches currency
- Formula: `balanceUSD = balanceSYP / exchangeRate`

## Technical Details

### Sham Cash Information (Same as Checkout)
- **Account Number**: `cc8571e4f93387893e15f39cda36f45a`
- **Account Image**: `/images/shamcash.jpeg`
- **WhatsApp Contact**: +963 968355553
- **WhatsApp Link**: `https://wa.me/963968355553?text=مرحباً، أود إرسال إثبات الدفع لشحن الرصيد عبر Sham Cash`
- **Copy Function**: JavaScript function to copy account number to clipboard

### Map Integration
- Uses Leaflet.js (free, no API key required)
- Satellite imagery tiles from ArcGIS World Imagery
- Custom marker with store icon
- Popup with storage name
- Click to open Google Maps directions

### Storage Location (From Checkout Page)
```javascript
const STORAGE_LOCATION = {
    lat: 32.749925,  // Sweida, Syria
    lng: 36.573006,
    name: 'Tulip mart توليب مارت'
};
```

### API Endpoints

#### POST `/api/recharge/shamcash`
**Request:**
```json
{
    "amount": 50.00,
    "payment_method": "shamcash"
}
```

**Response:**
```json
{
    "success": true,
    "message": "تم إرسال طلب الشحن بنجاح! سيتم مراجعته خلال 24 ساعة.",
    "amount_usd": 50.00,
    "amount_syp": 650000
}
```

#### POST `/api/recharge/card`
**Request:**
```json
{
    "amount": 100.00,
    "payment_method": "card",
    "card_token": "tok_visa_..."
}
```

**Response:**
```json
{
    "success": true,
    "message": "تم شحن الرصيد بنجاح!",
    "amount_usd": 100.00,
    "amount_syp": 1300000,
    "new_balance": 1300000
}
```

## Styling

### Design System
- Primary color: #0f4f55 (Teal)
- Secondary color: #ff6b35 (Orange)
- Font: El Messiri (Arabic-friendly)
- Border radius: 12px (modern, rounded)
- Shadows: Subtle elevation effects

### Responsive Design
- Desktop: Full layout with sidebar
- Tablet: Optimized spacing
- Mobile: Stacked layout, larger touch targets

## Security Considerations

1. **Authentication Required**: All routes protected with `auth` middleware
2. **CSRF Protection**: All forms include CSRF token
3. **Input Validation**: Amount, payment method validated
4. **Logging**: All recharge attempts logged for audit
5. **Admin Approval**: Sham Cash requires manual review

## Future Enhancements

### Credit Card Integration
1. Enable credit card payment option
2. Integrate Stripe payment gateway
3. Add card tokenization
4. Implement 3D Secure authentication
5. Store payment history

### Admin Panel
1. Create recharge requests table
2. Admin dashboard to review pending requests
3. Approve/reject functionality
4. Automatic balance updates
5. Email notifications

### Additional Features
1. Recharge history page
2. Transaction receipts
3. Refund system
4. Promotional bonuses
5. Referral rewards

## Testing Checklist

- ✅ Profile page shows balance with currency
- ✅ "شحن رصيد" button navigates to recharge page
- ✅ Cash payment shows map with storage location
- ✅ Map marker clickable for directions
- ✅ Sham Cash shows transfer information
- ✅ Sham Cash form validates amount
- ✅ Sham Cash submission creates log entry
- ✅ Credit card option disabled with "قريباً" badge
- ✅ Balance display updates when currency changes
- ⏳ Admin can review and approve Sham Cash requests (needs admin panel)
- ⏳ Credit card payment processes successfully (needs Stripe integration)

## Files Modified/Created

### Created
- `resources/views/recharge.blade.php` - Recharge page
- `RECHARGE_SYSTEM_IMPLEMENTATION.md` - This documentation

### Modified
- `resources/views/profile.blade.php` - Added recharge button and currency-based balance
- `routes/web.php` - Added recharge route
- `routes/api.php` - Added recharge API routes
- `app/Http/Controllers/Api/UserProfileController.php` - Added recharge methods

## Configuration

### Exchange Rate
Add to `config/app.php`:
```php
'usd_to_syp_rate' => env('USD_TO_SYP_RATE', 13000),
```

Add to `.env`:
```
USD_TO_SYP_RATE=13000
```

### Storage Location
Update in `resources/views/recharge.blade.php`:
```javascript
const STORAGE_LOCATION = {
    lat: 33.5138,  // Your actual latitude
    lng: 36.2765,  // Your actual longitude
    name: 'مخزن Tulip Store'
};
```

## Notes

- Balance is stored in SYP in the database
- Display currency is user preference (USD/SYP)
- Sham Cash requests require manual admin approval
- Credit card payments will be instant once enabled
- Cash payments require physical visit to storage
- All amounts logged for accounting purposes
