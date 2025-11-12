# Running Tulip Store Locally

## Prerequisites
- PHP 8.1+ installed
- Node.js and npm installed
- MySQL or MariaDB running
- Composer installed

## Step-by-Step Setup

### 1. Install Dependencies

```powershell
# Navigate to project root
cd D:\Tulip-Store

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 2. Setup Environment File

```powershell
# Copy example to .env
Copy-Item .env.example .env

# Generate app key
php artisan key:generate
```

### 3. Configure Database (in .env)

Edit `D:\Tulip-Store\.env` and update:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tulip_store
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database:
```sql
CREATE DATABASE tulip_store;
```

### 4. Run Migrations

```powershell
php artisan migrate
php artisan db:seed
```

### 5. Build Frontend Assets

```powershell
# Development build (with watch)
npm run dev

# Or production build
npm run build
```

## Running the Application

### Option A: Two Terminal Windows (Recommended for Development)

**Terminal 1 - Start Vite dev server:**
```powershell
cd D:\Tulip-Store
npm run dev
```
This will run on: `http://localhost:5173`

**Terminal 2 - Start Laravel dev server:**
```powershell
cd D:\Tulip-Store
php artisan serve
```
This will run on: `http://localhost:8000`

### Option B: Build Once, Then Run Laravel Only

```powershell
# Build frontend once
npm run build

# Start Laravel (will serve built React app)
php artisan serve
```
Access on: `http://localhost:8000`

## Development Workflow

### For Frontend Development (React):
```powershell
npm run dev
# This watches for changes and hot-reloads React components
# Access via http://localhost:5173
```

### For Backend Development (Laravel):
```powershell
php artisan serve
# This runs Laravel API on http://localhost:8000
```

### Important: Proxy Configuration
The `vite.config.ts` has a proxy that automatically routes `/api` requests to Laravel:
- Frontend request to `/api/products` → redirects to `http://localhost:8000/api/products`

## Quick Commands Reference

| Command | What it does |
|---------|-------------|
| `composer install` | Install PHP packages |
| `npm install` | Install JavaScript packages |
| `php artisan key:generate` | Generate app encryption key |
| `php artisan migrate` | Run database migrations |
| `php artisan db:seed` | Seed database with test data |
| `php artisan serve` | Start Laravel dev server |
| `npm run dev` | Start Vite with hot reload |
| `npm run build` | Build production assets |
| `php artisan tinker` | Interactive PHP shell |

## Common Issues

### Port Already in Use
```powershell
# Laravel on different port
php artisan serve --port=8001

# Vite on different port
npm run dev -- --port 5174
```

### Database Connection Error
- Ensure MySQL is running
- Check credentials in .env
- Verify database exists: `CREATE DATABASE tulip_store;`

### Node Modules Issues
```powershell
# Clear and reinstall
Remove-Item node_modules -Recurse
npm install
```

### Composer Issues
```powershell
# Clear and reinstall
Remove-Item vendor -Recurse
composer install
```

## Access Points

| Service | URL | Purpose |
|---------|-----|---------|
| Frontend (Dev) | http://localhost:5173 | React development with hot reload |
| Backend | http://localhost:8000 | Laravel API & production React |
| MySQL | localhost:3306 | Database |
| Vite API | http://localhost:5173/@vite/client | Vite client |

## Testing the Setup

1. Visit `http://localhost:8000` or `http://localhost:5173`
2. You should see the Tulip Store homepage
3. Try adding products to cart
4. Test authentication endpoints

## Production Build

When ready to deploy:

```powershell
# Build frontend for production
npm run build

# Set environment to production
# Update .env: APP_ENV=production APP_DEBUG=false

# Cache configuration
php artisan config:cache
php artisan route:cache

# Serve
php artisan serve
```
