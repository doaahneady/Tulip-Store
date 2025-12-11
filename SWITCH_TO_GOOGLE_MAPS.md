# Switch to Google Maps (Google Earth View)

## Current Status
✅ Leaflet Maps is currently active (works without API key)

## To Switch to Google Maps:

### Step 1: Get FREE Google Maps API Key
Follow instructions in `GET_GOOGLE_MAPS_KEY.md`

### Step 2: Update checkout.blade.php
Open: `resources/views/checkout.blade.php`

**Find these lines (around line 12-18):**
```html
<!-- OPTION 1: Google Maps (Requires API Key) -->
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY_HERE&libraries=places,geometry&language=ar"></script> -->

<!-- OPTION 2: Leaflet Maps (Free, No API Key Required) - CURRENTLY ACTIVE -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

**Change to:**
```html
<!-- OPTION 1: Google Maps (Requires API Key) - CURRENTLY ACTIVE -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_ACTUAL_API_KEY&libraries=places,geometry&language=ar"></script>

<!-- OPTION 2: Leaflet Maps (Free, No API Key Required) -->
<!-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" /> -->
<!-- <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script> -->
```

### Step 3: That's It!
The code automatically detects which library is loaded and uses the correct one.

## Google Maps Features:
- ✅ Satellite view (Google Earth)
- ✅ Street view
- ✅ Better routing
- ✅ More accurate
- ✅ $200 FREE monthly credit

## Leaflet Features (Current):
- ✅ No API key needed
- ✅ Completely free
- ✅ Works immediately
- ✅ Good routing
- ✅ Fast loading

## Need Help?
Read `GET_GOOGLE_MAPS_KEY.md` for detailed instructions on getting your free API key.
