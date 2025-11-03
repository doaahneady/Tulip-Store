# Tulip Store Backend

Express + MongoDB backend for Tulip Store. Provides authentication endpoints for register and login.

## Tech
- Node.js, Express
- MongoDB via Mongoose (DB name: `Tulip`)
- JWT for auth tokens
- Bcrypt for password hashing

## Setup
1. Install dependencies
```bash
cd backend
npm install
```

2. Configure environment (create a `.env` file in `backend/`)
```env
PORT=4000
# If not provided, defaults to mongodb://127.0.0.1:27017/Tulip
# MONGO_URI=mongodb://127.0.0.1:27017/Tulip

# JWT
JWT_SECRET=change_this_to_a_long_random_string
JWT_EXPIRES_IN=7d
```

3. Run the server
```bash
# development (with reload)
npm run dev
# or production
npm start
```

The server listens on `http://localhost:4000` by default.

## Endpoints
### Health
- GET `/health` → `{ status: "ok", service: "tulip-backend" }`

### Auth
- POST `/api/auth/register`
  - Body: `{ name: string, email: string, password: string }`
  - 201 Created: `{ token, user: { id, name, email, createdAt } }`
  - 409 Conflict if email already registered

- POST `/api/auth/login`
  - Body: `{ email: string, password: string }`
  - 200 OK: `{ token, user: { id, name, email, createdAt } }`
  - 401 Unauthorized if invalid credentials

### Login Logs
- Collection: `loginlogs`
- Each login attempt (success or failure) is recorded with:
  - `emailAttempted`: string
  - `userId`: ObjectId | null
  - `success`: boolean
  - `ip`: string
  - `userAgent`: string
  - `createdAt`: Date

### Contact
- POST `/api/contact`
  - Body: `{ name: string, email: string, subject: string, message: string }`
  - 201 Created: `{ id, createdAt }`

## Notes
- If `MONGO_URI` is not set, it connects to `mongodb://127.0.0.1:27017/Tulip`.
- Passwords are stored as strong hashes with bcrypt.
- Keep `JWT_SECRET` secret and long in production.


