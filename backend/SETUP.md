# Setup Guide for Tulip Store Backend

## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js (optional, for frontend assets)

## Installation Steps

### 1. Install Dependencies

```bash
cd backend
composer install
```

### 2. Configure Environment

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Or on Windows:
```powershell
copy .env.example .env
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Configure Database

Edit the `.env` file and update the database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tulip_store
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Create Database

Create the MySQL database:

```sql
CREATE DATABASE tulip_store;
```

Or using MySQL command line:
```bash
mysql -u root -p -e "CREATE DATABASE tulip_store;"
```

### 6. Configure Email Settings

For development, you can use Mailtrap or similar service. Update `.env`:

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

For production, use your SMTP server settings.

### 7. Run Migrations

```bash
php artisan migrate
```

This will create the following tables:
- `users` - User accounts
- `email_verifications` - Email verification codes
- `personal_access_tokens` - API tokens for Sanctum
- `sessions` - Session storage (if using database sessions)

### 8. Seed Database (Optional)

To populate the database with test data:

```bash
php artisan db:seed
```

This will create 3 test users:
- **john@example.com** / password: `password123` (verified)
- **jane@example.com** / password: `password123` (verified)
- **test@example.com** / password: `password123` (unverified)

### 9. Start Development Server

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

## API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Authentication Endpoints

#### Register User
```
POST /api/auth/register
Content-Type: application/json

{
  "username": "john_doe",
  "email": "john@example.com",
  "password": "password123",
  "user_full_name": "John Doe",
  "mobile": "+1234567890",
  "address": "123 Main Street",
  "language": "english"
}
```

#### Verify Email
```
POST /api/auth/verify-email
Content-Type: application/json

{
  "email": "john@example.com",
  "verification_code": "123456"
}
```

#### Resend Verification Code
```
POST /api/auth/resend-verification
Content-Type: application/json

{
  "email": "john@example.com"
}
```

#### Login
```
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

#### Get Current User (Protected)
```
GET /api/auth/me
Authorization: Bearer {token}
```

#### Logout (Protected)
```
POST /api/auth/logout
Authorization: Bearer {token}
```

## Testing the API

### Using cURL

#### Register
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "testuser",
    "email": "test@example.com",
    "password": "password123",
    "user_full_name": "Test User"
  }'
```

#### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

#### Get Me (with token)
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Using Postman

1. Create a new request
2. Set method to POST/GET
3. Enter the URL: `http://localhost:8000/api/auth/{endpoint}`
4. Go to Headers tab and add:
   - `Content-Type: application/json`
   - `Authorization: Bearer {token}` (for protected routes)
5. Go to Body tab, select "raw" and "JSON", enter the JSON data

## Troubleshooting

### Database Connection Error
- Check MySQL is running
- Verify database credentials in `.env`
- Ensure database exists

### Email Not Sending
- Check Mailtrap/SMTP credentials in `.env`
- Check application logs: `storage/logs/laravel.log`
- For development, you can use Mailtrap to catch emails

### Migration Errors
- Drop the database and recreate it
- Run `php artisan migrate:fresh` (WARNING: This will delete all data)

### Token Issues
- Ensure you're sending the token in the Authorization header
- Check token hasn't expired
- Verify user is verified before logging in

## Notes

- Verification codes expire after 15 minutes
- Users must verify their email before logging in
- All passwords are hashed using bcrypt
- API uses Laravel Sanctum for token-based authentication
- CORS is enabled for all origins (configure in `config/cors.php` for production)

