# Tulip Store - Complete Setup Guide

**Tulip Store** - "Send Smile, Anywhere" - is a fully database-connected e-commerce platform for flowers, gifts, chocolates, and more.

## 🎯 What's Included

### Database
- ✅ **Migrations** for all tables: categories, products, carts, orders, reviews, wishlists
- ✅ **Models** with full relationships: Product, Category, Cart, CartItem, Order, OrderItem, Review, Wishlist
- ✅ **Database Seeder** with sample data (8 products across 4 categories)

### Backend (Laravel API)
- ✅ **API Controllers**: ProductController, CategoryController, CartController
- ✅ **API Routes** with pagination, search, filtering
- ✅ **Authentication** via Sanctum tokens
- ✅ **Cart Management**: Add, update, remove, clear
- ✅ **Product Search**: Full-text search across name and description

### Frontend (HTML + Vanilla JS)
- ✅ **Homepage** with Navbar, Hero, Categories, Products, Footer
- ✅ **Design** matching PDF (Teal, Orange, Beige colors)
- ✅ **Responsive Layout** (mobile, tablet, desktop)
- ✅ **Arabic & English** text content
- ✅ **Real-time Features**: Search, Cart counter, Add to cart
- ✅ **CSS Styling** (tulip-store.css) with brand colors

---

## 🚀 Quick Setup (5 Steps)

### 1️⃣ **Run Database Migrations**
```bash
php artisan migrate
```

### 2️⃣ **Seed Sample Data**
```bash
php artisan db:seed --class=TulipStoreSeeder
```

### 3️⃣ **Start Laravel Server**
```bash
php artisan serve
```

### 4️⃣ **Visit Homepage**
Go to: `http://localhost:8000`

### 5️⃣ **View Products**
Products will load automatically from the database via the API.

---

## 📁 File Structure

```
D:\Tulip-Store\
├── app/
│   ├── Http/Controllers/
│   │   └── Api/
│   │       ├── ProductController.php       (Product API)
│   │       ├── CategoryController.php      (Category API)
│   │       └── CartController.php          (Shopping Cart API)
│   └── Models/
│       ├── Product.php
│       ├── Category.php
│       ├── Cart.php
│       ├── CartItem.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── Review.php
│       └── Wishlist.php
├── database/
│   ├── migrations/
│   │   └── 2025_11_17_000001_create_tulip_store_tables.php
│   └── seeders/
│       └── TulipStoreSeeder.php
├── resources/
│   ├── css/
│   │   └── tulip-store.css                 (Brand styling)
│   └── views/
│       └── home.blade.php                  (Homepage)
├── routes/
│   ├── api.php                             (API routes)
│   └── web.php                             (Web routes)
└── .env                                     (Database config)
```

---

## 🎨 Design Features

### Colors (Tulip Store Branding)
- **Primary Teal**: `#004E89` (Dark teal buttons, headers)
- **Orange**: `#FF6B35` (Call-to-action, prices, accents)
- **Beige**: `#F5E6D3` (Search bar, category cards)
- **Cream**: `#FDF8F3` (Background)

### Components
- **Navbar**: Logo, Search, Gift icon, Cart (with count), Login button
- **Hero Section**: "Send Smile, Anywhere" with tagline and image
- **Categories**: 4 category cards (Flowers, Gifts, Chocolates, Balloons)
- **Products Grid**: Responsive grid with image, name, rating, price, "Add to Cart"
- **Footer**: Links, social, copyright

---

## 🔌 API Endpoints

### Products
```
GET    /api/products                   (Get all products with pagination)
GET    /api/products/{id}              (Get single product)
GET    /api/products/featured          (Get featured products)
GET    /api/products?search=keyword    (Search products)
GET    /api/products?category_id=1    (Filter by category)
```

### Categories
```
GET    /api/categories                 (Get all categories)
GET    /api/categories/{id}            (Get category with products)
```

### Shopping Cart (Requires Auth Token)
```
GET    /api/cart                       (Get cart items)
POST   /api/cart/add                   (Add product to cart)
PUT    /api/cart/items/{itemId}        (Update quantity)
DELETE /api/cart/items/{itemId}        (Remove item)
DELETE /api/cart/clear                 (Clear entire cart)
```

### Authentication
```
POST   /api/auth/register              (Register user)
POST   /api/auth/login                 (Login user)
POST   /api/auth/logout                (Logout)
GET    /api/auth/me                    (Get current user)
```

---

## 📊 Database Schema

### Categories Table
```sql
id, name, slug, description, image, display_order, is_active, timestamps
```

### Products Table
```sql
id, name, slug, description, details, category_id, price, discount_price,
stock, image, images (JSON), rating, reviews_count, is_featured, 
is_active, timestamps
```

### Carts Table
```sql
id, user_id, timestamps
```

### Cart Items Table
```sql
id, cart_id, product_id, quantity, price, timestamps
```

### Orders Table
```sql
id, user_id, order_number, status, subtotal, tax, shipping, total,
shipping_address, shipping_method, tracking_number, notes, timestamps
```

### Reviews Table
```sql
id, product_id, user_id, rating, comment, is_verified_purchase, timestamps
```

### Wishlists Table
```sql
id, user_id, product_id, timestamps
```

---

## 🛒 Sample Data Included

**Categories** (4):
- Flowers (الزهور الطازة)
- Gifts (الهدايا والمفاجآت)
- Chocolates (الشوكولاتة والحلويات)
- Balloons (البالونات والديكور)

**Products** (8):
- Red Roses Premium - 299.99 SAR
- Mixed Flowers - 199.99 SAR → 149.99 SAR
- White Lilies - 249.99 SAR
- Gold Gift Box - 149.99 SAR
- Scented Candles - 99.99 SAR → 79.99 SAR
- Belgian Chocolates - 179.99 SAR
- Fruit Sweets - 89.99 SAR
- Colorful Balloons - 69.99 SAR
- Silver Helium Balloons - 119.99 SAR → 99.99 SAR

---

## 🔐 Authentication

The shopping cart and orders are **protected** with Laravel Sanctum authentication.

### Workflow:
1. User registers/logs in
2. Receives an API token
3. Stores token in `localStorage.auth_token`
4. Uses token for all cart/order requests

---

## 🎯 Frontend Features

### Real-Time Search
- Search box in navbar
- Searches product name and description
- Minimum 2 characters to search
- Results update instantly

### Shopping Cart
- Add products to cart (requires login)
- Shows cart item count
- Cart persists across sessions
- Click cart icon to view cart

### Categories
- 4 category cards with emoji icons
- Click to filter products (extensible)
- Hover effects

### Product Cards
- Product image
- Category badge (orange)
- Product name and rating (⭐)
- Current price (orange) and original price (strikethrough)
- "Add to Cart" button

---

## 🚨 Troubleshooting

### Products not loading?
1. Check if migrations ran: `php artisan migrate:status`
2. Check if seeder ran: `php artisan db:seed --class=TulipStoreSeeder`
3. Check Laravel logs: `storage/logs/laravel.log`

### Cart not working?
1. Check if user is authenticated
2. Check browser console for errors
3. Verify API token is stored in localStorage

### Styling issues?
1. Clear browser cache (Ctrl+Shift+Del)
2. Verify CSS file exists: `public/css/tulip-store.css`
3. Run `php artisan optimize`

---

## 📦 Future Enhancements

- [ ] Wishlist functionality
- [ ] Order history and tracking
- [ ] User reviews and ratings
- [ ] Payment gateway integration
- [ ] Discount coupons
- [ ] Email notifications
- [ ] Admin dashboard
- [ ] Product image uploads
- [ ] Advanced filtering (price range, rating)
- [ ] Customer chat support

---

## 📞 Support

For issues or questions:
1. Check the API logs
2. Verify database connection in `.env`
3. Ensure all migrations are run
4. Clear cache: `php artisan cache:clear`

---

**Tulip Store** © 2025 - "Send Smile, Anywhere" 🌸
