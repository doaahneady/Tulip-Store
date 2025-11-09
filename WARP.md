# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Commands you’ll use most

### Backend (Laravel API in `backend/`)

- Install deps and set up env
  ```bash
  cd backend
  composer install
  # create .env and app key (first run only)
  copy .env.example .env   # on Windows PowerShell
  php artisan key:generate
  ```
- Database
  ```bash
  php artisan migrate --seed            # create schema + seed test data
  php artisan migrate:fresh --seed      # rebuild from scratch (destructive)
  ```
- Run the API locally
  ```bash
  php artisan serve                     # serves on http://localhost:8000
  ```
- Tests
  ```bash
  php artisan test                      # run all tests (sqlite in-memory per phpunit.xml)
  php artisan test --testsuite=Feature  # just Feature tests
  php artisan test --filter ExampleTest # run a single test class
  php artisan test --filter "Tests\\Feature\\ExampleTest::test_the_application_returns_a_successful_response"  # single test method
  ```
- PHP lint/format (Laravel Pint)
  ```bash
  vendor/bin/pint --test   # check
  vendor/bin/pint          # fix
  ```

### Frontend (Express/EJS in `src/`)

- Install deps and run the site
  ```bash
  npm install
  npm start                 # serves the site on http://localhost:3000
  ```
- Backend API base URL used by the frontend is hardcoded as `http://localhost:8000/api` in `src/website/routes/catalog.js`. If your API port/host differs, update that constant (or introduce an env-based config).

## High-level architecture

Monorepo with two apps that run side-by-side during development:

- Frontend site (Node/Express + EJS)
  - Location: `src/website`
  - Entrypoint: `src/index.js` → `src/website/app.js`
  - Rendering: server-side EJS views under `src/website/views`
  - Routing: `src/website/routes/*` (home, sign-in, contact, business, return policy, catalog)
  - Data access: routes call the Laravel API using `axios` on the server (no shared DB). The catalog flow hits:
    - `GET /categories` → list
    - `GET /categories/{slug}` and `/categories/{slug}/filters`
    - `GET /categories/{slug}/products` and `/products/search` with query/filters

- Backend API (Laravel 10)
  - Location: `backend/`
  - Auth: email/password with verification codes (mail), tokens via Sanctum
  - Primary controllers and routes (`backend/routes/api.php`):
    - `POST /api/auth/register`, `verify-email`, `resend-verification`, `login`
    - Protected (Sanctum): `GET /api/auth/me`, `POST /api/auth/logout`
    - Catalog: `GET /api/categories`, `/api/categories/{slug}`, `/api/categories/{slug}/filters`, `/api/categories/{slug}/products`, `/api/products/search`, `/api/products/{slug}`
  - Data model (Eloquent):
    - `User` (email verification state, Sanctum tokens)
    - `EmailVerification` and `PasswordReset` (6-digit codes, expiry, used flags)
    - `Category` → `hasMany(Product)`; `Product` → `hasMany(ProductAttribute)` for dynamic filter facets
  - Defaults and middleware:
    - Rate limiting 60/min (see `backend/app/Providers/RouteServiceProvider.php`)
    - Global CORS enabled (adjust allowlist in `backend/config/cors.php` for production)
  - Testing configuration:
    - `backend/phpunit.xml` boots an in-memory sqlite DB and array mailer; use `php artisan test`

## Notes for Warp

- There is no existing WARP.md, CLAUDE/Cursor/Copilot rules in the repo.
- The “API proxy” mentioned in `CONNECTION_GUIDE.md` (a `src/website/routes/api.js` router) is not present; the current frontend calls the backend directly via `axios`. If you need a same-origin `/api/*` proxy on port 3000, add a small Express router to forward to `http://localhost:8000/api/*` and mount it in `app.js`.
