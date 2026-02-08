#!/bin/bash

# Tulip Store - Deployment Preparation Script
# This script prepares your Laravel project for Hostinger deployment

echo "🚀 Preparing Tulip Store for Hostinger Deployment..."
echo ""

# Step 1: Clear all caches
echo "📦 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo "✅ Caches cleared"
echo ""

# Step 2: Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Optimization complete"
echo ""

# Step 3: Install production dependencies
echo "📚 Installing production dependencies..."
composer install --optimize-autoloader --no-dev
echo "✅ Dependencies installed"
echo ""

# Step 4: Create deployment package
echo "📦 Creating deployment package..."
mkdir -p deployment
mkdir -p deployment/laravel
mkdir -p deployment/public_html

# Copy Laravel files (excluding public folder)
echo "Copying Laravel files..."
rsync -av --progress . deployment/laravel/ \
  --exclude deployment \
  --exclude node_modules \
  --exclude .git \
  --exclude .env \
  --exclude public

# Copy public folder contents to public_html
echo "Copying public files..."
rsync -av --progress public/ deployment/public_html/

echo "✅ Deployment package created in 'deployment' folder"
echo ""

echo "📋 Next Steps:"
echo "1. Update .env.production with your Hostinger database credentials"
echo "2. Rename .env.production to .env and place it in deployment/laravel/"
echo "3. Upload deployment/laravel/ folder to your Hostinger account"
echo "4. Upload deployment/public_html/ contents to public_html folder"
echo "5. Update index.php paths as described in HOSTINGER_DEPLOYMENT_GUIDE.md"
echo ""
echo "✨ Deployment preparation complete!"