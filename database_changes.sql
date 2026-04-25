-- ============================================
-- WEIGHT-BASED PRODUCTS DATABASE CHANGES
-- Tulip Store - Mart Section
-- Date: April 22, 2026
-- ============================================

-- ============================================
-- 1. ADD WEIGHT FIELDS TO CART_ITEMS TABLE
-- ============================================

-- Add columns for weight-based products
ALTER TABLE `cart_items` 
ADD COLUMN `is_weight_based` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Whether this item is sold by weight' AFTER `unit_price`,
ADD COLUMN `weight_grams` DECIMAL(10,2) NULL COMMENT 'Weight in grams' AFTER `is_weight_based`,
ADD COLUMN `price_per_unit` DECIMAL(10,2) NULL COMMENT 'Price per kilogram in SYP' AFTER `weight_grams`,
ADD COLUMN `amount_paid` DECIMAL(10,2) NULL COMMENT 'Amount customer paid in SYP' AFTER `price_per_unit`;

-- ============================================
-- 2. VERIFY CHANGES
-- ============================================

-- Check table structure
DESCRIBE cart_items;

-- Expected output should include:
-- | is_weight_based | tinyint(1)    | NO   |     | 0       |       |
-- | weight_grams    | decimal(10,2) | YES  |     | NULL    |       |
-- | price_per_unit  | decimal(10,2) | YES  |     | NULL    |       |
-- | amount_paid     | decimal(10,2) | YES  |     | NULL    |       |

-- ============================================
-- 3. SAMPLE DATA QUERIES
-- ============================================

-- View all weight-based items in cart
SELECT 
    ci.id,
    ci.product_id,
    p.name,
    ci.quantity,
    ci.is_weight_based,
    ci.weight_grams,
    ci.price_per_unit,
    ci.amount_paid,
    ci.unit_price
FROM cart_items ci
LEFT JOIN products p ON ci.product_id = p.id
WHERE ci.is_weight_based = 1;

-- View cart summary with weight-based items
SELECT 
    c.user_id,
    COUNT(ci.id) as total_items,
    SUM(CASE WHEN ci.is_weight_based = 1 THEN 1 ELSE 0 END) as weight_based_items,
    SUM(CASE WHEN ci.is_weight_based = 0 THEN 1 ELSE 0 END) as regular_items,
    SUM(CASE 
        WHEN ci.is_weight_based = 1 THEN ci.amount_paid / 13100  -- Convert SYP to USD
        ELSE ci.unit_price * ci.quantity 
    END) as total_value_usd
FROM carts c
LEFT JOIN cart_items ci ON c.id = ci.cart_id
GROUP BY c.user_id;

-- ============================================
-- 4. ROLLBACK (IF NEEDED)
-- ============================================

-- Remove weight-based columns (CAUTION: This will delete data!)
-- Uncomment to execute:
-- ALTER TABLE `cart_items` 
-- DROP COLUMN `is_weight_based`,
-- DROP COLUMN `weight_grams`,
-- DROP COLUMN `price_per_unit`,
-- DROP COLUMN `amount_paid`;

-- ============================================
-- 5. PRODUCTS WITH WEIGHT UNITS
-- ============================================

-- Find products that should be weight-based
SELECT 
    id,
    name,
    price,
    JSON_UNQUOTE(JSON_EXTRACT(product_attributes, '$.unit.value')) as unit
FROM products
WHERE JSON_EXTRACT(product_attributes, '$.unit.value') REGEXP 'kilogram|gram|كيلو|كيلوغرام|غرام'
LIMIT 20;

-- Update product to be weight-based (example)
-- UPDATE products 
-- SET product_attributes = JSON_SET(
--     product_attributes, 
--     '$.unit.value', 
--     'كيلو غرام'
-- )
-- WHERE id = 123;

-- ============================================
-- 6. ANALYTICS QUERIES
-- ============================================

-- Total weight sold per product
SELECT 
    p.id,
    p.name,
    COUNT(ci.id) as times_sold,
    SUM(ci.weight_grams) as total_grams_sold,
    SUM(ci.weight_grams) / 1000 as total_kilos_sold,
    SUM(ci.amount_paid) as total_revenue_syp,
    SUM(ci.amount_paid) / 13100 as total_revenue_usd
FROM cart_items ci
JOIN products p ON ci.product_id = p.id
WHERE ci.is_weight_based = 1
GROUP BY p.id, p.name
ORDER BY total_revenue_usd DESC;

-- Average weight per purchase
SELECT 
    p.id,
    p.name,
    AVG(ci.weight_grams) as avg_grams_per_purchase,
    AVG(ci.amount_paid) as avg_amount_paid_syp
FROM cart_items ci
JOIN products p ON ci.product_id = p.id
WHERE ci.is_weight_based = 1
GROUP BY p.id, p.name;

-- ============================================
-- 7. DATA INTEGRITY CHECKS
-- ============================================

-- Check for weight-based items with missing data
SELECT 
    id,
    product_id,
    is_weight_based,
    weight_grams,
    price_per_unit,
    amount_paid
FROM cart_items
WHERE is_weight_based = 1
AND (
    weight_grams IS NULL 
    OR weight_grams = 0 
    OR price_per_unit IS NULL 
    OR price_per_unit = 0
    OR amount_paid IS NULL 
    OR amount_paid = 0
);

-- Check for regular items with weight data (shouldn't happen)
SELECT 
    id,
    product_id,
    is_weight_based,
    weight_grams,
    price_per_unit,
    amount_paid
FROM cart_items
WHERE is_weight_based = 0
AND (
    weight_grams IS NOT NULL 
    OR price_per_unit IS NOT NULL 
    OR amount_paid IS NOT NULL
);

-- ============================================
-- 8. CLEANUP QUERIES
-- ============================================

-- Remove orphaned cart items (no product)
-- DELETE ci FROM cart_items ci
-- LEFT JOIN products p ON ci.product_id = p.id
-- WHERE p.id IS NULL;

-- Clear old cart items (older than 30 days)
-- DELETE FROM cart_items 
-- WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- ============================================
-- 9. BACKUP COMMANDS
-- ============================================

-- Backup cart_items table before changes
-- mysqldump -u username -p database_name cart_items > cart_items_backup_20260422.sql

-- Restore from backup if needed
-- mysql -u username -p database_name < cart_items_backup_20260422.sql

-- ============================================
-- 10. INDEX RECOMMENDATIONS (OPTIONAL)
-- ============================================

-- Add index for faster weight-based queries
-- CREATE INDEX idx_is_weight_based ON cart_items(is_weight_based);

-- Add composite index for cart queries
-- CREATE INDEX idx_cart_weight ON cart_items(cart_id, is_weight_based);

-- ============================================
-- END OF SQL SCRIPT
-- ============================================

-- NOTES:
-- 1. Run migration using: php artisan migrate
-- 2. Session-based mart products don't use this table
-- 3. Only regular store products use cart_items table
-- 4. Mart products are stored in session: mart_products
-- 5. Exchange rate: 1 USD = 13,100 SYP (configurable)

-- MIGRATION FILE:
-- database/migrations/2026_04_22_000001_add_weight_fields_to_cart_items.php

-- SEEDER FILE:
-- database/seeders/WeightBasedProductSeeder.php
-- Run with: php artisan db:seed --class=WeightBasedProductSeeder
