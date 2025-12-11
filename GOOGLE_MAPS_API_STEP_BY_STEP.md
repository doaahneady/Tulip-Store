# Google Maps API Key - Step by Step Guide

## ⏱️ Time Required: 5 minutes
## 💰 Cost: FREE ($200 monthly credit)

---

## Step 1: Go to Google Cloud Console
🔗 **Open this link:** https://console.cloud.google.com/

- If you're not logged in, sign in with your Google account
- Any Gmail account works!

---

## Step 2: Create a New Project

1. **Click** on the project dropdown at the top (says "Select a project")
2. **Click** "NEW PROJECT" button (top right of the popup)
3. **Enter** project name: `Tulip Store` (or any name you like)
4. **Click** "CREATE" button
5. **Wait** 10-20 seconds for project creation

---

## Step 3: Enable Maps JavaScript API

1. **Click** on the hamburger menu (☰) on the top left
2. **Go to:** "APIs & Services" → "Library"
3. **Search for:** `Maps JavaScript API`
4. **Click** on "Maps JavaScript API" in the results
5. **Click** the blue "ENABLE" button
6. **Wait** a few seconds

---

## Step 4: Enable Directions API (for routing)

1. **Click** "Library" again in the left sidebar
2. **Search for:** `Directions API`
3. **Click** on "Directions API"
4. **Click** "ENABLE"

---

## Step 5: Create API Key

1. **Click** on "Credentials" in the left sidebar
2. **Click** "CREATE CREDENTIALS" at the top
3. **Select** "API Key"
4. **Copy** the API key that appears (looks like: AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8)
5. **Click** "CLOSE" (don't restrict it yet for testing)

---

## Step 6: Add Key to Your Project

**I'll help you add it automatically!**

Just paste your API key here when you get it, and I'll update the file for you.

Your key will look like this:
```
AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8
```

---

## ⚠️ Important Notes:

### Free Tier:
- ✅ $200 FREE credit every month
- ✅ Equals ~28,000 map loads per month
- ✅ No credit card required initially
- ✅ You'll get a warning before any charges

### Security (Optional - Do Later):
After testing, you can restrict your key:
1. Go to Credentials
2. Click on your API key
3. Under "Application restrictions" → "HTTP referrers"
4. Add: `127.0.0.1:8000/*` and your domain

---

## 🆘 Troubleshooting:

**"This API project is not authorized to use this API"**
- Make sure you enabled both "Maps JavaScript API" and "Directions API"

**"RefererNotAllowedMapError"**
- Your key is restricted. Remove restrictions for testing.

**Map shows but says "For development purposes only"**
- Normal! This appears until you add billing (but you won't be charged with free tier)

---

## ✅ Once You Have Your Key:

**Just paste it here in chat and I'll update the code for you!**

Or manually:
1. Open `resources/views/checkout.blade.php`
2. Find line ~13
3. Replace `YOUR_API_KEY_HERE` with your actual key
4. Save and refresh the page

---

## 📞 Need Help?

If you get stuck at any step, just tell me which step and I'll help you!
