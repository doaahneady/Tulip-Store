# Tulip Store Backend API

Laravel backend API for Tulip Store with authentication and email verification.

## Features

- User Registration
- Email Verification with 6-digit code
- User Login
- Protected API routes using Laravel Sanctum
- MySQL database support

## Setup Instructions

1. **Install Dependencies**
   ```bash
   composer install
   ```

2. **Environment Configuration**
   - Copy `.env.example` to `.env`
   - Update database credentials in `.env`:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=tulip_store
     DB_USERNAME=root
     DB_PASSWORD=your_password
     ```
   - Configure email settings (for development, you can use Mailtrap or similar):
     ```
     MAIL_MAILER=smtp
     MAIL_HOST=smtp.mailtrap.io
     MAIL_PORT=2525
     MAIL_USERNAME=your_mailtrap_username
     MAIL_PASSWORD=your_mailtrap_password
     MAIL_ENCRYPTION=tls
     MAIL_FROM_ADDRESS="noreply@tulipstore.com"
     MAIL_FROM_NAME="${APP_NAME}"
     ```

3. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

4. **Create Database**
   ```sql
   CREATE DATABASE tulip_store;
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed Database (Optional)**
   ```bash
   php artisan db:seed
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

## API Endpoints

### Public Endpoints

#### Register User
- **POST** `/api/auth/register`
- **Body:**
  ```json
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
- **Response:**
  ```json
  {
    "success": true,
    "message": "Registration successful. Please check your email for verification code.",
    "user": {
      "id": 1,
      "username": "john_doe",
      "email": "john@example.com",
      "verified": false
    }
  }
  ```

#### Verify Email
- **POST** `/api/auth/verify-email`
- **Body:**
  ```json
  {
    "email": "john@example.com",
    "verification_code": "123456"
  }
  ```

#### Resend Verification Code
- **POST** `/api/auth/resend-verification`
- **Body:**
  ```json
  {
    "email": "john@example.com"
  }
  ```

#### Login
- **POST** `/api/auth/login`
- **Body:**
  ```json
  {
    "email": "john@example.com",
    "password": "password123"
  }
  ```
- **Response:**
  ```json
  {
    "success": true,
    "message": "Login successful",
    "user": {
      "id": 1,
      "username": "john_doe",
      "email": "john@example.com",
      ...
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
  }
  ```

### Protected Endpoints (Require Bearer Token)

Include the token in the Authorization header:
```
Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

#### Get Current User
- **GET** `/api/auth/me`

#### Logout
- **POST** `/api/auth/logout`

## Test Users

After running `php artisan db:seed`, you can use these test accounts:

1. **john@example.com** / password: `password123` (verified)
2. **jane@example.com** / password: `password123` (verified)
3. **test@example.com** / password: `password123` (unverified)

## Notes

- Verification codes expire after 15 minutes
- Users must verify their email before logging in
- All passwords are hashed using bcrypt
- API uses Laravel Sanctum for authentication
