# 🔧 React Frontend - Database Connection Troubleshooting

## ❌ Pages Show "No Data" or "Loading..."

### ✅ Quick Fix Checklist

#### 1️⃣ Is Laravel Backend Running?
```bash
# Terminal 1: Start Laravel
cd D:\Tulip-Store\backend
php artisan serve
```
- Should show: `Laravel development server started on [http://127.0.0.1:8000]`
- Keep this running in background

#### 2️⃣ Does Database Have Data?
```bash
# Terminal: Check database
mysql -u root
USE tulip_store;
SELECT COUNT(*) FROM categories;
SELECT COUNT(*) FROM products;
```
- Should return numbers > 0
- If 0, you need to add data to database

#### 3️⃣ Test API Directly
```bash
# Terminal: Test API endpoints
# Windows - use PowerShell's Invoke-WebRequest
Invoke-WebRequest -Uri "http://localhost:8000/api/categories" -UseBasicParsing | ConvertTo-Json

# Or use browser: open http://localhost:8000/api/categories
```
- Should see JSON with category data
- If error, Laravel has a problem

#### 4️⃣ Start React Dev Server
```bash
# Terminal 2: Start React
cd D:\Tulip-Store\frontend
npm run dev
```
- Opens on `http://localhost:5173`

#### 5️⃣ Test with Debug Console
1. Navigate to `http://localhost:5173/debug`
2. Click "Get Categories" button
3. Check the response:
   - ✅ **Green box** = Data loaded successfully
   - ❌ **Red box** = Something is wrong

---

## 🔴 If Still Not Working

### Step 1: Check Browser Console
Press `F12` → Console tab → Look for errors

#### Common Errors:

**❌ "Failed to fetch"**
```
Solution: Laravel backend not running on port 8000
Fix: Run: cd D:\Tulip-Store\backend && php artisan serve
```

**❌ "404 Not Found"**
```
Solution: API endpoints don't exist in Laravel
Fix: Check routes in backend/routes/api.php exist
```

**❌ "500 Internal Server Error"**
```
Solution: Laravel has an error processing the request
Fix: Check backend/storage/logs/laravel.log for details
```

**❌ "CORS policy blocked request"**
```
Solution: Frontend not allowed to call backend
Fix: Add to Laravel config/cors.php:
'allowed_origins' => ['http://localhost:5173']
```

### Step 2: Check Laravel Logs
```bash
# In backend directory
tail -f storage/logs/laravel.log

# Or on Windows, open file
D:\Tulip-Store\backend\storage\logs\laravel.log
```

### Step 3: Verify Database Connection
```bash
cd D:\Tulip-Store\backend

# Test in Laravel Tinker
php artisan tinker

# Run these commands:
>>> App\Models\Category::all()
>>> App\Models\Product::all()

# Should return data, not errors
```

### Step 4: Check API Routes
```bash
cd D:\Tulip-Store\backend

# List all API routes
php artisan route:list --path=api
```

Should show:
```
GET|HEAD  /api/categories
GET|HEAD  /api/products
GET|HEAD  /api/products/search
...
```

---

## 🚀 Full Reset & Restart

If nothing works, start fresh:

### 1. Backend
```bash
cd D:\Tulip-Store\backend

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Restart server
php artisan serve
```

### 2. Frontend
```bash
cd D:\Tulip-Store\frontend

# Clear node modules and reinstall
rm node_modules
rm package-lock.json
npm install

# Start dev server
npm run dev
```

### 3. Database
```bash
# Login to MySQL
mysql -u root

# Check data exists
USE tulip_store;
SELECT * FROM categories LIMIT 1;
SELECT * FROM products LIMIT 1;
```

### 4. Test in Browser
- Open `http://localhost:5173/debug`
- Click test buttons
- Check if data appears

---

## ✅ Verification Checklist

Before considering it working, verify:

- [ ] Home page shows products and categories
- [ ] Debug console (`/debug`) shows green SUCCESS for all tests
- [ ] Can click category and see products
- [ ] Can search for products
- [ ] Can click product to see details
- [ ] Add to cart works (check localStorage in F12 → Application)
- [ ] Can sign up (creates new user in database)
- [ ] Can sign in with credentials

---

## 📊 Testing Endpoints Manually

### Using Browser
```
Categories:
http://localhost:8000/api/categories

Products:
http://localhost:8000/api/products

Search:
http://localhost:8000/api/products/search?q=phone

Category Products:
http://localhost:8000/api/categories/fashion/products
```

All should return JSON with data from database.

### Using Debug Console (Recommended)
1. Go to `http://localhost:5173/debug`
2. Use the buttons to test all endpoints
3. Much easier to see responses

---

## 📝 What to Check in Order

### Priority 1: Backend Running
```
Is `php artisan serve` running on port 8000?
→ Check: http://localhost:8000
→ Should see Laravel page
```

### Priority 2: Database Connected
```
Does Laravel connect to MySQL?
→ Check: backend/.env has correct DB credentials
→ Run: php artisan migrate
→ Run: php artisan tinker
→ Try: App\Models\Category::count()
```

### Priority 3: Data Exists
```
Does database have categories and products?
→ Check: mysql -u root → SELECT * FROM categories;
→ Should return data, not empty
```

### Priority 4: API Routes Work
```
Can I access API endpoints?
→ Check: http://localhost:8000/api/categories
→ Should return JSON array
```

### Priority 5: Frontend Connection
```
Can React frontend reach backend?
→ Check: http://localhost:5173/debug
→ Click "Get Categories"
→ Should show green SUCCESS
```

---

## 🎯 Quick Diagnosis Script

Run this to diagnose issues:

```powershell
# Copy and run in PowerShell

Write-Host "=== Tulip Store Diagnostics ===" -ForegroundColor Cyan

# Check if Laravel is running
Write-Host "`n1. Checking Laravel Backend..." -ForegroundColor Yellow
try {
  $response = Invoke-WebRequest -Uri "http://localhost:8000" -UseBasicParsing -ErrorAction Stop
  Write-Host "✅ Laravel is running!" -ForegroundColor Green
} catch {
  Write-Host "❌ Laravel is NOT running on port 8000" -ForegroundColor Red
  Write-Host "   Run: cd D:\Tulip-Store\backend && php artisan serve" -ForegroundColor Yellow
  exit
}

# Check if API responds
Write-Host "`n2. Checking API..." -ForegroundColor Yellow
try {
  $response = Invoke-WebRequest -Uri "http://localhost:8000/api/categories" -UseBasicParsing -ErrorAction Stop
  $data = $response.Content | ConvertFrom-Json
  Write-Host "✅ API is responding!" -ForegroundColor Green
  Write-Host "   Found $(($data.data).Count) categories" -ForegroundColor Green
} catch {
  Write-Host "❌ API Error: $($_.Exception.Message)" -ForegroundColor Red
}

# Check if React is running
Write-Host "`n3. Checking React Frontend..." -ForegroundColor Yellow
try {
  $response = Invoke-WebRequest -Uri "http://localhost:5173" -UseBasicParsing -ErrorAction Stop
  Write-Host "✅ React frontend is running!" -ForegroundColor Green
} catch {
  Write-Host "❌ React frontend is NOT running on port 5173" -ForegroundColor Red
  Write-Host "   Run: cd D:\Tulip-Store\frontend && npm run dev" -ForegroundColor Yellow
}

Write-Host "`n=== Diagnostics Complete ===" -ForegroundColor Cyan
```

---

## 🆘 Still Not Working?

### Check These Files:
1. **Backend database config**: `D:\Tulip-Store\backend\.env`
   - DB_HOST should be 127.0.0.1
   - DB_DATABASE should be tulip_store
   - DB_USERNAME should be root

2. **Frontend API config**: `D:\Tulip-Store\frontend\src\lib\api.ts`
   - Should have `http://localhost:8000`

3. **Laravel API routes**: `D:\Tulip-Store\backend\routes\api.php`
   - Should have routes for categories, products, etc.

### Ask for Help:
Provide these when asking for support:
1. Screenshot of `/debug` page
2. Output of `php artisan tinker` → `App\Models\Category::all()`
3. Laravel error log content
4. Browser console errors (F12 → Console)

---

## ✨ Success Indicators

You know it's working when:

✅ Home page loads instantly with products
✅ Debug console shows green SUCCESS for all tests
✅ Can navigate to categories and products
✅ Search finds products
✅ No errors in browser console (F12)
✅ localStorage has 'token' and 'user' after sign up/in
✅ Cart updates when adding products

**Everything should just work after following these steps!**
