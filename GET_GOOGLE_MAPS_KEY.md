# How to Get Google Maps API Key (Free)

## Step 1: Go to Google Cloud Console
Visit: https://console.cloud.google.com/

## Step 2: Create a New Project
1. Click "Select a project" at the top
2. Click "NEW PROJECT"
3. Name it "Tulip Store"
4. Click "CREATE"

## Step 3: Enable Maps JavaScript API
1. Go to "APIs & Services" > "Library"
2. Search for "Maps JavaScript API"
3. Click on it and press "ENABLE"

## Step 4: Create API Key
1. Go to "APIs & Services" > "Credentials"
2. Click "CREATE CREDENTIALS" > "API Key"
3. Copy your API key (looks like: AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8)

## Step 5: Add Key to Your Project
Open file: `resources/views/checkout.blade.php`

Find this line:
```html
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY_HERE&libraries=places,geometry&language=ar"></script>
```

Replace `YOUR_API_KEY_HERE` with your actual key.

## Important Notes:
- ✅ Google gives you $200 FREE credit every month
- ✅ This is enough for thousands of map loads
- ✅ No credit card required for basic usage
- ✅ You can restrict the key to your domain for security

## If You Don't Want to Get API Key:
The current Leaflet map works perfectly without any API key!
