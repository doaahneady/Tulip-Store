# Tulip Store React Frontend - Complete Setup Guide

## ✅ Pages Created

### Public Pages
- **Home** (`/`) - Landing page with categories and popular products
- **Categories** (`/categories`) - Browse all categories
- **Category** (`/category/:slug`) - View products in a category with filters
- **Product** (`/product/:idOrSlug`) - Full product details page
- **Search** (`/search`) - Search products across store

### Authentication Pages
- **Sign In** (`/signin`) - User login with email/password
- **Sign Up** (`/signup`) - User registration with email verification
- **Debug** (`/debug`) - API debugging console (development only)

---

## 🚀 Quick Start

### Prerequisites
- Node.js 16+ and npm
- Laravel backend running on `http://localhost:8000`
- MySQL database with data

### Installation

```bash
cd D:\Tulip-Store\frontend
npm install
npm run dev
```

The app will run on `http://localhost:5173`

---

## 🔧 Backend Setup Requirements

### 1. Ensure Laravel Backend is Running
```bash
cd D:\Tulip-Store\backend
php artisan serve
```
This should start on `http://localhost:8000`

### 2. Database Connection
Make sure your `.env` file has correct database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tulip_store
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Run Database Migrations
```bash
php artisan migrate
php artisan db:seed  # If you have seeders
```

### 4. Verify API Endpoints

Test each endpoint to ensure data is loading from the database:

```bash
# Get all categories
curl http://localhost:8000/api/categories

# Get all products
curl http://localhost:8000/api/products

# Search products
curl http://localhost:8000/api/products/search?q=phone

# Get products by category
curl http://localhost:8000/api/categories/fashion/products
```

---

## 🐛 Troubleshooting Database Connection Issues

### Issue: Pages show "No data" or loading indefinitely

#### Step 1: Check Backend is Running
```bash
# In a terminal, verify Laravel is running
cd D:\Tulip-Store\backend
php artisan serve

# In another terminal, test the API
curl http://localhost:8000/api/categories
```

You should see JSON response with category data.

#### Step 2: Use the Debug Console
Navigate to `http://localhost:5173/debug` and use the test buttons:
- Click "Get Categories" - should show categories from database
- Click "Get Products" - should show products from database
- Click "Search" - should search products
- Check browser console (F12) for detailed error messages

#### Step 3: Check Database
Verify data exists in MySQL:
```sql
-- Login to MySQL
mysql -u root

-- Select your database
USE tulip_store;

-- Check if tables exist
SHOW TABLES;

-- Check categories table
SELECT * FROM categories;

-- Check products table
SELECT * FROM products;
```

#### Step 4: Verify CORS (if needed)
In Laravel, make sure `config/cors.php` allows requests from frontend:
```php
'allowed_origins' => ['http://localhost:5173', 'http://localhost:8000'],
```

#### Step 5: Check Laravel Routes
Verify API routes are registered in `backend/routes/api.php`:
```php
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/search', [ProductController::class, 'search']);
```

### Issue: "API Error" Messages in Debug Console

#### If you see `404 Not Found`:
- API endpoints don't exist
- Check that controllers are defined
- Run `php artisan route:list` to see all routes

#### If you see `500 Internal Server Error`:
- Check Laravel error logs: `storage/logs/laravel.log`
- Verify database connection
- Run `php artisan tinker` to test database connection:
  ```php
  App\Models\Category::all();
  App\Models\Product::all();
  ```

#### If you see network errors or timeout:
- Ensure Laravel backend is running on port 8000
- Check firewall settings
- Try accessing `http://localhost:8000` directly in browser

---

## 🔐 Authentication Flow

### Sign Up Process
1. User enters: Name, Email, Password, Confirm Password
2. Frontend validates form
3. POST to `/api/auth/register`
4. User receives verification code via email
5. User enters code to verify email
6. Token stored in localStorage
7. User redirected to home page

### Sign In Process
1. User enters: Email, Password
2. POST to `/api/auth/login`
3. Backend returns token and user data
4. Token stored in localStorage
5. User redirected to home page

### Token Management
- Tokens stored in `localStorage.getItem('token')`
- User data stored in `localStorage.getItem('user')`
- Clear with `localStorage.removeItem('token')`

---

## 📁 Project Structure

```
frontend/
├── src/
│   ├── pages/
│   │   ├── Home.tsx              ✓ Popular products
│   │   ├── Categories.tsx        ✓ All categories
│   │   ├── Category.tsx          ✓ Products by category
│   │   ├── Product.tsx           ✓ Product details
│   │   ├── Search.tsx            ✓ Search products
│   │   ├── SignIn.tsx            ✓ Login page
│   │   ├── SignUp.tsx            ✓ Register page
│   │   └── Debug.tsx             ✓ Debug console
│   ├── shared/
│   │   ├── Navbar.tsx            Navigation bar
│   │   └── CartOffcanvas.tsx     Shopping cart
│   ├── layouts/
│   │   └── RootLayout.tsx        Main layout
│   ├── lib/
│   │   ├── api.ts                API helper
│   │   └── types.ts              TypeScript types
│   ├── App.tsx
│   ├── main.tsx
│   └── router.tsx                Route definitions
├── public/
│   ├── images/                   Product images
│   ├── css/                       Stylesheets
│   └── js/                        Legacy JavaScript
├── index.html
├── vite.config.ts
├── tsconfig.json
└── package.json
```

---

## 🛒 Cart Functionality

Products are added to cart and stored in `localStorage`:

```javascript
// Add to cart
localStorage.setItem('cart', JSON.stringify([
  {
    id: 1,
    name: 'Product Name',
    price: 99.99,
    image: '/image.jpg',
    qty: 1
  }
]))

// Get from cart
const cart = JSON.parse(localStorage.getItem('cart') || '[]')
```

---

## 📱 API Endpoints

### Categories
- `GET /api/categories` - All categories
- `GET /api/categories/{slug}` - Category details
- `GET /api/categories/{slug}/filters` - Category filters
- `GET /api/categories/{slug}/products` - Products in category

### Products
- `GET /api/products` - All products
- `GET /api/products/{slug}` - Product details
- `GET /api/products/search?q=keyword` - Search products

### Authentication
- `POST /api/auth/register` - Create account
- `POST /api/auth/login` - Login
- `POST /api/auth/verify-email` - Verify email code
- `POST /api/auth/resend-verification` - Resend code
- `POST /api/auth/forgot-password` - Forgot password
- `POST /api/auth/reset-password` - Reset password

---

## 🧪 Testing

### Manual Testing Checklist
- [ ] Home page loads with categories and products
- [ ] Click category → shows category products
- [ ] Click product → shows product details
- [ ] Search functionality works
- [ ] Add to cart works
- [ ] Sign up with new email
- [ ] Verify email code
- [ ] Sign in with credentials
- [ ] Debug console shows data from database

### Using Debug Console
1. Navigate to `http://localhost:5173/debug`
2. Click test buttons to verify API connectivity
3. Check responses in browser console (F12)
4. All endpoints should return data from database

---

## 🚢 Production Build

```bash
# Build for production
npm run build

# This creates files in backend/public/ ready for Laravel
# Laravel will serve them automatically
```

---

## 📝 Environment Variables

`.env` (if needed):
```
VITE_API_URL=http://localhost:8000/api
```

---

## 🆘 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Blank page | Check browser console, verify backend is running |
| "No data" displayed | Use /debug to test API, check database has data |
| 404 errors | Verify Laravel routes exist, check spelling |
| CORS errors | Add frontend URL to Laravel config/cors.php |
| Sign up not working | Check email provider configuration in Laravel |
| Images not loading | Ensure public/images/ path is correct |

---

## 📞 Support

If you encounter issues:
1. Check the Debug Console (`/debug`)
2. Open browser Developer Tools (F12)
3. Check Laravel logs: `storage/logs/laravel.log`
4. Verify database connection and data
5. Ensure both services are running on correct ports

---

## ✨ Next Steps

- [ ] Populate database with products and categories
- [ ] Set up email for verification
- [ ] Configure payment processing
- [ ] Add user dashboard
- [ ] Implement order management
- [ ] Add admin panel
- [ ] Deploy to production

---

**Last Updated:** November 11, 2025
**React Version:** 18.3.1
**Laravel API:** RESTful with Token Authentication
