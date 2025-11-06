# Frontend-Backend Connection Guide

## ✅ Connection Complete!

The frontend (Express.js on port 3000) is now connected to the Laravel backend (port 8000) through a proxy system.

## How It Works

1. **API Proxy**: All requests to `/api/*` from the frontend are automatically proxied to Laravel backend at `http://localhost:8000/api/*`

2. **CORS Configuration**: Laravel backend is configured to accept requests from `http://localhost:3000`

3. **No Frontend Changes Required**: The frontend JavaScript can now call the backend APIs using the same port (3000)

## Available API Endpoints

All endpoints are accessible through the frontend at `http://localhost:3000/api/`:

### Authentication Endpoints

- **POST** `/api/auth/register` - Register new user
- **POST** `/api/auth/verify-email` - Verify email with code
- **POST** `/api/auth/resend-verification` - Resend verification code
- **POST** `/api/auth/login` - Login user
- **GET** `/api/auth/me` - Get current user (requires Bearer token)
- **POST** `/api/auth/logout` - Logout (requires Bearer token)

## Usage Example

From your frontend JavaScript (e.g., in signIn.ejs), you can now make API calls:

```javascript
// Register
fetch('/api/auth/register', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    username: 'newuser',
    email: 'user@example.com',
    password: 'password123',
    user_full_name: 'New User'
  })
})
.then(res => res.json())
.then(data => console.log(data));

// Login
fetch('/api/auth/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    email: 'john@example.com',
    password: 'password123'
  })
})
.then(res => res.json())
.then(data => {
  if (data.success) {
    // Store token
    localStorage.setItem('token', data.token);
    localStorage.setItem('user', JSON.stringify(data.user));
  }
});

// Get current user (protected)
fetch('/api/auth/me', {
  headers: {
    'Authorization': `Bearer ${localStorage.getItem('token')}`
  }
})
.then(res => res.json())
.then(data => console.log(data));
```

## Testing the Connection

1. **Start Laravel Backend**:
   ```bash
   cd backend
   php artisan serve
   ```

2. **Start Express Frontend**:
   ```bash
   npm start
   ```

3. **Test API from frontend**:
   - Open browser console
   - Run: `fetch('/api/auth/login', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({email: 'john@example.com', password: 'password123'}) }).then(r => r.json()).then(console.log)`

## Configuration Files Modified

1. **`src/website/routes/api.js`** - New API proxy route
2. **`src/website/app.js`** - Added API router
3. **`package.json`** - Added axios dependency
4. **`backend/config/cors.php`** - Configured CORS for frontend

## Notes

- The proxy automatically forwards all `/api/*` requests to Laravel
- Authorization headers are preserved
- All request methods (GET, POST, PUT, DELETE, etc.) are supported
- Error handling is included for connection issues

## Next Steps

You can now update your frontend JavaScript (in signIn.ejs or other views) to use these API endpoints instead of hardcoded credentials. The backend is ready to handle:

- User registration with email verification
- Email verification with 6-digit codes
- User login with token-based authentication
- Protected routes with Bearer token authentication

