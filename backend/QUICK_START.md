# Quick Start Guide

## 1. Install Dependencies
```bash
cd backend
composer install
```

## 2. Setup Environment
```bash
# Copy .env.example to .env
cp .env.example .env  # Linux/Mac
copy .env.example .env  # Windows

# Generate app key
php artisan key:generate
```

## 3. Configure Database in .env
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tulip_store
DB_USERNAME=root
DB_PASSWORD=your_password
```

## 4. Create Database
```sql
CREATE DATABASE tulip_store;
```

## 5. Configure Email in .env (for development - use Mailtrap)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tulipstore.com"
MAIL_FROM_NAME="Tulip Store"
```

## 6. Run Migrations
```bash
php artisan migrate
```

## 7. Seed Database (Optional - creates test users)
```bash
php artisan db:seed
```

## 8. Start Server
```bash
php artisan serve
```

## Test Users (after seeding)
- **john@example.com** / password: `password123` (verified)
- **jane@example.com** / password: `password123` (verified)
- **test@example.com** / password: `password123` (unverified)

## API Endpoints

Base URL: `http://localhost:8000/api`

### Register
```
POST /api/auth/register
Body: {
  "username": "testuser",
  "email": "test@example.com",
  "password": "password123",
  "user_full_name": "Test User"
}
```

### Verify Email
```
POST /api/auth/verify-email
Body: {
  "email": "test@example.com",
  "verification_code": "123456"
}
```

### Login
```
POST /api/auth/login
Body: {
  "email": "john@example.com",
  "password": "password123"
}
```

### Get Current User (Protected)
```
GET /api/auth/me
Headers: Authorization: Bearer {token}
```

### Logout (Protected)
```
POST /api/auth/logout
Headers: Authorization: Bearer {token}
```

