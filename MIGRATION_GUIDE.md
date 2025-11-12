# Tulip Store - Folder Reorganization Guide for Hostinger Deployment

## Current Structure
```
Tulip-Store/
├── backend/          (Laravel API)
├── frontend/         (React SPA)
└── src/             (Old files)
```

## Target Structure (for Hostinger)
```
Tulip-Store/
├── app/              (Laravel Controllers, Models)
├── config/           (Laravel Config)
├── database/         (Migrations, Seeders)
├── public/           (Built React assets, images)
├── resources/        (React components, CSS)
│   ├── js/          (React components - move from frontend/src)
│   └── css/         (Stylesheets)
├── routes/          (Laravel routes)
├── storage/         (Laravel storage)
├── vendor/          (Composer dependencies)
├── composer.json
├── package.json
├── vite.config.ts
├── .env.example
├── .gitignore
└── README.md
```

## Steps to Reorganize

### 1. Copy Backend Files to Root
```powershell
# From backend/ to root
Copy all contents from backend/ to Tulip-Store/ root (except node_modules)
Keep: app/, config/, database/, public/, routes/, storage/, composer.json
```

### 2. Move Frontend React Files
```powershell
# Move React source files
Move frontend/src/* to resources/js/

# Update file paths in resources/js/main.tsx:
# Change from: import router from './router'
# To: import router from './router.tsx'
```

### 3. Update vite.config.ts
Location: Tulip-Store/vite.config.ts
```typescript
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/main.tsx'],
      refresh: true,
    }),
    react(),
  ],
})
```

### 4. Update public/index.html
Move from frontend/index.html to public/index.html:
```html
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Tulip Store</title>
    
    <link rel="shortcut icon" href="/images/fav-icon.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  </head>
  <body>
    <div id="root"></div>
    @vite(['resources/css/app.css', 'resources/js/main.tsx'])
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
```

### 5. Update package.json at Root
```json
{
  "name": "tulip-store",
  "version": "1.0.0",
  "type": "module",
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "preview": "vite preview"
  },
  "dependencies": {
    "react": "^18.3.1",
    "react-dom": "^18.3.1",
    "react-router-dom": "^6.28.0"
  },
  "devDependencies": {
    "@types/react": "^18.3.5",
    "@types/react-dom": "^18.3.0",
    "@vitejs/plugin-react": "^4.3.1",
    "laravel-vite-plugin": "^2.0.0",
    "typescript": "^5.6.2",
    "vite": "^5.4.9"
  }
}
```

### 6. Create .env.example
```
APP_NAME="Tulip Store"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your_hostinger_host
DB_PORT=3306
DB_DATABASE=tulip_store
DB_USERNAME=root
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@tulipstore.com"
MAIL_FROM_NAME="Tulip Store"
```

### 7. Create .gitignore
```
/vendor/
/node_modules/
.env
.env.local
.DS_Store
/storage/logs/*
/storage/framework/cache/*
/storage/framework/sessions/*
/storage/framework/views/*
/public/hot
/public/storage
/public/build
.vscode
*.swp
*.swo
Thumbs.db
```

### 8. Deploy to Hostinger
```bash
# 1. Upload files via SFTP (exclude vendor/ and node_modules/)
# 2. SSH into Hostinger and run:

cd /home/yourdomain
composer install
npm install
npm run build
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 9. Configure Hostinger Web Root
In Hostinger control panel:
- Set document root to: `/public`
- Ensure .htaccess is in public/ folder

## File Structure Example
```
resources/
├── js/
│   ├── main.tsx              (entry point)
│   ├── router.tsx
│   ├── App.tsx
│   ├── pages/
│   │   ├── Home.tsx
│   │   ├── Category.tsx
│   │   ├── Product.tsx
│   │   └── ...
│   ├── components/
│   │   ├── Navbar.tsx
│   │   ├── CartOffcanvas.tsx
│   │   └── ...
│   ├── layouts/
│   │   └── RootLayout.tsx
│   ├── shared/
│   └── lib/
│       ├── api.ts
│       └── types.ts
└── css/
    └── app.css
```

## Important Notes
- Keep `app/`, `config/`, `database/`, `routes/` from backend
- Move React code to `resources/js/`
- Run `npm run build` before deploying
- Set correct database credentials in .env on Hostinger
- Ensure storage/ and bootstrap/cache/ are writable
- Keep images in `public/images/`

## Next Steps
1. Backup current project
2. Follow steps 1-8 above
3. Test locally with `npm run dev` and `php artisan serve`
4. Deploy to Hostinger
5. Run migrations on production
