# API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication Flow

1. **Register** → User receives verification code via email
2. **Verify Email** → User enters code to verify account
3. **Login** → User receives access token
4. **Use Token** → Include token in Authorization header for protected routes

---

## Endpoints

### 1. Register User

**POST** `/api/auth/register`

**Request Body:**
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

**Required Fields:**
- `username` (string, unique)
- `email` (string, valid email, unique)
- `password` (string, min 6 characters)

**Optional Fields:**
- `user_full_name` (string)
- `mobile` (string)
- `address` (string)
- `language` (string, default: "english")

**Response (201):**
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

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken."],
    "username": ["The username has already been taken."]
  }
}
```

---

### 2. Verify Email

**POST** `/api/auth/verify-email`

**Request Body:**
```json
{
  "email": "john@example.com",
  "verification_code": "123456"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Email verified successfully",
  "user": {
    "id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "verified": true
  }
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Invalid or expired verification code"
}
```

**Notes:**
- Verification codes expire after 15 minutes
- Codes can only be used once
- Users must verify before logging in

---

### 3. Resend Verification Code

**POST** `/api/auth/resend-verification`

**Request Body:**
```json
{
  "email": "john@example.com"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Verification code sent successfully"
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The selected email is invalid."]
  }
}
```

**Notes:**
- Invalidates previous unused codes
- Sends new verification code via email
- Only works for unverified users

---

### 4. Login

**POST** `/api/auth/login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "user": {
    "id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "user_full_name": "John Doe",
    "mobile": "+1234567890",
    "address": "123 Main Street",
    "language": "english",
    "verified": true
  },
  "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

**Error Response (401):**
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

**Error Response (403):**
```json
{
  "success": false,
  "message": "Please verify your email before logging in",
  "requires_verification": true
}
```

**Notes:**
- Returns access token for authenticated requests
- User must be verified before login
- Token should be stored securely (use in Authorization header)

---

### 5. Get Current User (Protected)

**GET** `/api/auth/me`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "user_full_name": "John Doe",
    "mobile": "+1234567890",
    "address": "123 Main Street",
    "language": "english",
    "verified": true,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

**Error Response (401):**
```json
{
  "message": "Unauthenticated."
}
```

---

### 6. Logout (Protected)

**POST** `/api/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

**Error Response (401):**
```json
{
  "message": "Unauthenticated."
}
```

**Notes:**
- Deletes the current access token
- User must login again to get a new token

---

## Testing with cURL

### Register
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

### Verify Email
```bash
curl -X POST http://localhost:8000/api/auth/verify-email \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "verification_code": "123456"
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### Get Current User
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Logout
```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Error Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `422` - Validation Error
- `500` - Server Error

---

## Notes

- All timestamps are in ISO 8601 format
- Passwords are hashed using bcrypt
- Verification codes are 6-digit numbers
- Tokens don't expire by default (configure in `config/sanctum.php`)
- CORS is enabled for all origins (configure for production)

---

## Security Considerations

1. **Password Requirements**: Minimum 6 characters (consider adding complexity rules)
2. **Rate Limiting**: API is rate-limited to 60 requests per minute
3. **Token Storage**: Store tokens securely (never in localStorage for production)
4. **HTTPS**: Always use HTTPS in production
5. **Email Verification**: Required before login
6. **CORS**: Configure allowed origins for production

