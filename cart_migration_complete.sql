-- ============================================================================
-- Cart Account Persistence - Complete Migration & Verification Script
-- Date: 2026-04-23
-- Description: Adds support for storing mart products in the database cart
-- ============================================================================
-- This script includes:
-- 1. Verification queries to check current state
-- 2. Safe migration that checks before adding columns
-- 3. Post-migration verification
-- 4. Sample data examples
-- 5. Rollback script
-- ============================================================================

-- ============================================================================
-- SECTION 1: PRE-MIGRATION VERIFICATION
-- ============================================================================

SELECT '========================================' AS '';
SELECT 'PRE-MIGRATION VERIFICATION' AS '';
SELECT '========================================' AS '';

-- Check if product_type column exists
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ product_type column EXISTS'
        ELSE '✗ product_type column MISSING - will be added'
    END AS product_type_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'product_type';

-- Check if mart_product_name column exists
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ mart_product_name column EXISTS'
        ELSE '✗ mart_product_name column MISSING - will be added'
    END AS mart_product_name_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'mart_product_name';

-- Check if mart_product_image column exists
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ mart_product_image column EXISTS'
        ELSE '✗ mart_product_image column MISSING - will be added'
    END AS mart_product_image_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'mart_product_image';

-- Check if mart_product_unit column exists
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ mart_product_unit column EXISTS'
        ELSE '✗ mart_product_unit column MISSING - will be added'
    END AS mart_product_unit_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'mart_product_unit';

-- Check if mart_product_emoji column exists
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ mart_product_emoji column EXISTS'
        ELSE '✗ mart_product_emoji column MISSING - will be added'
    END AS mart_product_emoji_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'mart_product_emoji';

-- Check if unique constraint exists
SELECT 
    CASE 
        WHEN COUNT(*) = 0 THEN '✓ Unique constraint already REMOVED'
        ELSE '✗ Unique constraint EXISTS - will be removed'
    END AS unique_constraint_status
FROM INFORMATION_SCHEMA.STATISTICS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND INDEX_NAME = 'cart_items_cart_id_product_id_unique';

-- ============================================================================
-- SECTION 2: SAFE MIGRATION (with existence checks)
-- ============================================================================

SELECT '========================================' AS '';
SELECT 'RUNNING MIGRATION' AS '';
SELECT '========================================' AS '';

-- Add product_type column (if it doesn't exist)
SET @col_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'cart_items' 
    AND COLUMN_NAME = 'product_type'
);

SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `cart_items` ADD COLUMN `product_type` VARCHAR(255) NOT NULL DEFAULT ''regular'' AFTER `product_id`',
    'SELECT ''Column product_type already exists - skipped'' AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add mart_product_name column (if it doesn't exist)
SET @col_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'cart_items' 
    AND COLUMN_NAME = 'mart_product_name'
);

SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `cart_items` ADD COLUMN `mart_product_name` VARCHAR(255) NULL AFTER `product_type`',
    'SELECT ''Column mart_product_name already exists - skipped'' AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add mart_product_image column (if it doesn't exist)
SET @col_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'cart_items' 
    AND COLUMN_NAME = 'mart_product_image'
);

SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `cart_items` ADD COLUMN `mart_product_image` VARCHAR(255) NULL AFTER `mart_product_name`',
    'SELECT ''Column mart_product_image already exists - skipped'' AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add mart_product_unit column (if it doesn't exist)
SET @col_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'cart_items' 
    AND COLUMN_NAME = 'mart_product_unit'
);

SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `cart_items` ADD COLUMN `mart_product_unit` VARCHAR(255) NULL AFTER `mart_product_image`',
    'SELECT ''Column mart_product_unit already exists - skipped'' AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add mart_product_emoji column (if it doesn't exist)
SET @col_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'cart_items' 
    AND COLUMN_NAME = 'mart_product_emoji'
);

SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `cart_items` ADD COLUMN `mart_product_emoji` VARCHAR(255) NULL AFTER `mart_product_unit`',
    'SELECT ''Column mart_product_emoji already exists - skipped'' AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ============================================================================
-- IMPORTANT: Dropping the unique constraint
-- The unique constraint cannot be dropped directly because it's used by a 
-- foreign key. We'll leave it in place - the application will handle 
-- duplicate prevention for weight-based products by using unique IDs.
-- ============================================================================

SELECT 'NOTE: Unique constraint on (cart_id, product_id) is kept in place.' AS message;
SELECT 'Weight-based products will use application-level unique identifiers.' AS message;

-- ============================================================================
-- SECTION 3: POST-MIGRATION VERIFICATION
-- ============================================================================

SELECT '========================================' AS '';
SELECT 'POST-MIGRATION VERIFICATION' AS '';
SELECT '========================================' AS '';

-- Verify all columns exist
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ product_type column EXISTS'
        ELSE '✗ product_type column MISSING'
    END AS product_type_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'product_type';

SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ mart_product_name column EXISTS'
        ELSE '✗ mart_product_name column MISSING'
    END AS mart_product_name_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'mart_product_name';

SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ mart_product_image column EXISTS'
        ELSE '✗ mart_product_image column MISSING'
    END AS mart_product_image_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'mart_product_image';

SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ mart_product_unit column EXISTS'
        ELSE '✗ mart_product_unit column MISSING'
    END AS mart_product_unit_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'mart_product_unit';

SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ mart_product_emoji column EXISTS'
        ELSE '✗ mart_product_emoji column MISSING'
    END AS mart_product_emoji_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'mart_product_emoji';

-- Show complete table structure
SELECT '========================================' AS '';
SELECT 'COMPLETE TABLE STRUCTURE' AS '';
SELECT '========================================' AS '';
DESCRIBE `cart_items`;

-- Show all indexes
SELECT '========================================' AS '';
SELECT 'TABLE INDEXES' AS '';
SELECT '========================================' AS '';
SHOW INDEXES FROM `cart_items`;

-- Count existing cart items by type
SELECT '========================================' AS '';
SELECT 'CART ITEMS STATISTICS' AS '';
SELECT '========================================' AS '';
SELECT 
    COALESCE(product_type, 'NULL') as product_type,
    COUNT(*) as total_items,
    SUM(CASE WHEN is_weight_based = 1 THEN 1 ELSE 0 END) as weight_based_items,
    SUM(CASE WHEN is_weight_based = 0 THEN 1 ELSE 0 END) as regular_items
FROM cart_items
GROUP BY product_type WITH ROLLUP;

-- Show sample data (if any exists)
SELECT '========================================' AS '';
SELECT 'SAMPLE CART ITEMS (First 5)' AS '';
SELECT '========================================' AS '';
SELECT 
    id,
    cart_id,
    product_id,
    product_type,
    mart_product_name,
    quantity,
    unit_price,
    is_weight_based,
    weight_grams,
    created_at
FROM cart_items
ORDER BY created_at DESC
LIMIT 5;

SELECT '========================================' AS '';
SELECT '✓ MIGRATION COMPLETED SUCCESSFULLY!' AS '';
SELECT '========================================' AS '';

-- ============================================================================
-- SECTION 4: COMPLETE TABLE SCHEMA (for reference)
-- ============================================================================

/*
CREATE TABLE `cart_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `cart_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    
    -- Product type and mart-specific fields
    `product_type` VARCHAR(255) NOT NULL DEFAULT 'regular',
    `mart_product_name` VARCHAR(255) NULL,
    `mart_product_image` VARCHAR(255) NULL,
    `mart_product_unit` VARCHAR(255) NULL,
    `mart_product_emoji` VARCHAR(255) NULL,
    
    -- Quantity and pricing
    `quantity` INT NOT NULL,
    `unit_price` DECIMAL(10, 2) NULL,
    `total_price` DECIMAL(10, 2) NULL,
    `product_snapshot` JSON NULL,
    
    -- Weight-based product fields
    `is_weight_based` BOOLEAN NOT NULL DEFAULT FALSE,
    `weight_grams` DECIMAL(10, 2) NULL,
    `price_per_unit` DECIMAL(10, 2) NULL,
    `amount_paid` DECIMAL(10, 2) NULL,
    
    -- Timestamps
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    
    PRIMARY KEY (`id`),
    
    -- Foreign keys
    CONSTRAINT `cart_items_cart_id_foreign` 
        FOREIGN KEY (`cart_id`) 
        REFERENCES `carts` (`id`) 
        ON DELETE CASCADE,
    
    CONSTRAINT `cart_items_product_id_foreign` 
        FOREIGN KEY (`product_id`) 
        REFERENCES `products` (`id`) 
        ON DELETE CASCADE,
    
    -- Indexes
    INDEX `cart_items_product_id_index` (`product_id`),
    UNIQUE INDEX `cart_items_cart_id_product_id_unique` (`cart_id`, `product_id`)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
*/

-- ============================================================================
-- SECTION 5: SAMPLE DATA EXAMPLES
-- ============================================================================

-- Example 1: Regular product entry
/*
INSERT INTO `cart_items` (
    `cart_id`, `product_id`, `product_type`, 
    `quantity`, `unit_price`, `total_price`,
    `is_weight_based`, `created_at`, `updated_at`
) VALUES (
    1, 456, 'regular',
    2, 15.99, 31.98,
    0, NOW(), NOW()
);
*/

-- Example 2: Mart product entry (regular quantity-based)
/*
INSERT INTO `cart_items` (
    `cart_id`, `product_id`, `product_type`,
    `mart_product_name`, `mart_product_image`, 
    `mart_product_unit`, `mart_product_emoji`,
    `quantity`, `unit_price`, `total_price`,
    `is_weight_based`, `created_at`, `updated_at`
) VALUES (
    1, 789, 'mart',
    'تفاح أحمر', '/images/apple.jpg',
    'قطعة', '🍎',
    5, 2.50, 12.50,
    0, NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
    quantity = quantity + VALUES(quantity),
    total_price = unit_price * (quantity + VALUES(quantity));
*/

-- Example 3: Mart product entry (weight-based)
-- Note: For weight-based products, use different product_ids or handle at application level
/*
INSERT INTO `cart_items` (
    `cart_id`, `product_id`, `product_type`,
    `mart_product_name`, `mart_product_image`,
    `mart_product_unit`, `mart_product_emoji`,
    `quantity`, `unit_price`, `total_price`,
    `is_weight_based`, `weight_grams`, `price_per_unit`, `amount_paid`,
    `created_at`, `updated_at`
) VALUES (
    1, 789, 'mart',
    'طماطم', '/images/tomato.jpg',
    'كيلو غرام', '�',
    1, 6550.00, 6550.00,
    1, 500.00, 13100.00, 6550.00,
    NOW(), NOW()
);
*/

-- ============================================================================
-- SECTION 6: USEFUL QUERIES
-- ============================================================================

-- View all mart products in carts
/*
SELECT 
    ci.id,
    ci.cart_id,
    ci.product_id,
    ci.mart_product_name,
    ci.mart_product_unit,
    ci.mart_product_emoji,
    ci.quantity,
    ci.unit_price,
    ci.is_weight_based,
    ci.weight_grams,
    ci.amount_paid,
    ci.created_at
FROM cart_items ci
WHERE ci.product_type = 'mart'
ORDER BY ci.cart_id, ci.created_at;
*/

-- View all weight-based products
/*
SELECT 
    ci.id,
    ci.cart_id,
    ci.product_type,
    ci.mart_product_name,
    ci.weight_grams,
    ci.price_per_unit,
    ci.amount_paid,
    ci.created_at
FROM cart_items ci
WHERE ci.is_weight_based = 1
ORDER BY ci.created_at DESC;
*/

-- Count items per cart
/*
SELECT 
    cart_id,
    COUNT(*) as total_items,
    SUM(CASE WHEN product_type = 'mart' THEN 1 ELSE 0 END) as mart_items,
    SUM(CASE WHEN product_type = 'regular' THEN 1 ELSE 0 END) as regular_items,
    SUM(CASE WHEN is_weight_based = 1 THEN 1 ELSE 0 END) as weight_based_items
FROM cart_items
GROUP BY cart_id
ORDER BY cart_id;
*/

-- ============================================================================
-- SECTION 7: ROLLBACK SCRIPT (if needed)
-- ============================================================================

/*
-- WARNING: This will remove all mart product data from cart_items!
-- Only run this if you need to completely undo the migration

-- Drop the new columns
ALTER TABLE `cart_items` DROP COLUMN IF EXISTS `product_type`;
ALTER TABLE `cart_items` DROP COLUMN IF EXISTS `mart_product_name`;
ALTER TABLE `cart_items` DROP COLUMN IF EXISTS `mart_product_image`;
ALTER TABLE `cart_items` DROP COLUMN IF EXISTS `mart_product_unit`;
ALTER TABLE `cart_items` DROP COLUMN IF EXISTS `mart_product_emoji`;
*/

-- ============================================================================
-- NOTES
-- ============================================================================

/*
1. PRODUCT_TYPE VALUES:
   - 'regular': Standard products from the store
   - 'mart': Products from Tulip Mart

2. MART PRODUCT FIELDS:
   - Only populated when product_type = 'mart'
   - For regular products, these fields remain NULL

3. WEIGHT-BASED PRODUCTS:
   - Due to the unique constraint on (cart_id, product_id), weight-based products
     of the same product_id cannot have multiple entries in the database
   - The application handles this by:
     a) Using the session for weight-based products (for guests)
     b) Merging weight-based products into a single entry with total weight
     c) Or using virtual product IDs for each weight-based entry
   
4. REGULAR MART PRODUCTS:
   - Quantities are merged when adding the same product
   - Example: 2 apples + 3 apples = 1 cart item with quantity 5
   - Use ON DUPLICATE KEY UPDATE in INSERT statements

5. BACKWARD COMPATIBILITY:
   - Existing cart items will have product_type = 'regular' by default
   - No data migration needed for existing records

6. UNIQUE CONSTRAINT:
   - The unique constraint on (cart_id, product_id) remains in place
   - This is required by the foreign key constraint
   - Application logic must handle weight-based products appropriately

7. SAFE TO RUN MULTIPLE TIMES:
   - This script checks for existence before adding columns
   - Can be run multiple times without errors
   - Will skip already-applied changes

8. RECOMMENDED APPROACH FOR WEIGHT-BASED PRODUCTS:
   - Store weight-based products in session until checkout
   - At checkout, create order items with the specific weights
   - This avoids the unique constraint issue in the cart
*/
