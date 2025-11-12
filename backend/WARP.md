# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

Tulip Store is a Laravel-based e-commerce backend API with user authentication (registration, email verification, login) and product/category management. The frontend assets are managed via Vite with Tailwind CSS.

## Core Commands

### Setup & Dependencies
```bash
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### Development
```bash
php artisan serve              # Start development server (http://localhost:8000)
npm run dev                    # Start Vite dev server
php artisan tinker             # Interactive shell for testing
```

### Testing
```bash
php artisan test               # Run all tests
php artisan test tests/Feature # Run feature tests only
php artisan test tests/Unit    # Run unit tests only
php artisan test --filter=MethodName  # Run single test
```

### Code Quality
```bash
php artisan pint               # Format code (Laravel Pint)
php artisan pint --check       # Check formatting without changes
```

### Database
```bash
php artisan migrate            # Run pending migrations
php artisan migrate:rollback   # Rollback last batch
php artisan migrate:refresh    # Rollback and re-run all
php artisan db:seed            # Seed database with test data
```

### Building
```bash
npm run build                  # Build frontend assets for production
```

## Architecture

### Authentication Flow
1. **Registration** (`POST /api/auth/register`): Creates unverified user, generates 6-digit code, sends verification email
2. **Email Verification** (`POST /api/auth/verify-email`): Validates code (15-min expiry), marks user as verified
3. **Login** (`POST /api/auth/login`): Returns Sanctum API token only if email is verified
4. **Protected Routes**: Use `middleware('auth:sanctum')` for token-based authentication

Key Models:
- `User`: Main user model with verification status
- `EmailVerification`: Stores codes with expiry; codes are single-use

### Product & Category System
- Categories are the top-level taxonomy (name, slug, description)
- Products belong to categories with attributes (name, slug, description, price, image, rating, sales count)
- ProductAttribute: Additional product-specific attributes
- All product/category endpoints are public

### API Structure
- Routes defined in `routes/api.php` with prefix `/api`
- Controllers in `app/Http/Controllers/`
- Models in `app/Models/` with Eloquent relationships
- Request validation in controllers using `Validator::make()`
- Standard JSON responses with `success`, `message`, and data fields

### Frontend Assets
- CSS: `resources/css/app.css` (Tailwind)
- JS: `resources/js/app.js`
- Built via Vite (`vite.config.js` with Laravel plugin)
- Email templates: `resources/views/emails/` (Blade)

## Development Notes

- **Database**: Development uses SQLite by default (see `.env.example`). Switch to MySQL by updating DB_CONNECTION and credentials in `.env`
- **Email**: Uses Laravel Mailable classes (`app/Mail/`) for transactional emails
- **Sanctum**: API tokens stored in `personal_access_tokens` table; stateless authentication
- **Migrations**: All schema defined in `database/migrations/`; seeders in `database/seeders/`
- **Test Environment**: PHPUnit configured to use in-memory SQLite; see `phpunit.xml`

## Key Files
- `routes/api.php`: API endpoint definitions
- `app/Http/Controllers/AuthController.php`: Registration, verification, login logic
- `app/Models/User.php`: User model with mass-assignable attributes
- `config/sanctum.php`: Token configuration
- `config/mail.php`: Mail driver settings
