-- ============================================================================
-- Cart Account Persistence Migration
-- Date: 2026-04-23
-- Description: Adds support for storing mart products in the database cart
-- ============================================================================

-- ============================================================================
-- STEP 1: Add new columns to cart_items table
-- ============================================================================

-- Add product_type column to distinguish between regular and mart products
ALTER TABLE `cart_items` 
ADD COLUMN `product_type` VARCHAR(255) NOT NULL DEFAULT 'regular' 
AFTER `product_id`;

-- Add mart product name (for virtual mart products)
ALTER TABLE `cart_items` 
ADD COLUMN `mart_product_name` VARCHAR(255) NULL 
AFTER `product_type`;

-- Add mart product image URL
ALTER TABLE `cart_items` 
ADD COLUMN `mart_product_image` VARCHAR(255) NULL 
AFTER `mart_product_name`;

-- Add mart product unit (e.g., 'كيلو غرام', 'قطعة')
ALTER TABLE `cart_items` 
ADD COLUMN `mart_product_unit` VARCHAR(255) NULL 
AFTER `mart_product_image`;

-- Add mart product emoji icon
ALTER TABLE `cart_items` 
ADD COLUMN `mart_product_emoji` VARCHAR(255) NULL 
AFTER `mart_product_unit`;

-- ============================================================================
-- STEP 2: Drop unique constraint to allow multiple weight-based entries
-- ============================================================================

-- Drop the unique constraint on (cart_id, product_id)
-- This allows multiple entries of the same product with different weights
ALTER TABLE `cart_items` 
DROP INDEX `cart_items_cart_id_product_id_unique`;

-- ============================================================================
-- COMPLETE TABLE SCHEMA (After Migration)
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
    INDEX `cart_items_product_id_index` (`product_id`)
    
    -- NOTE: Unique constraint on (cart_id, product_id) was REMOVED
    -- to allow multiple entries for weight-based products
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
*/

-- ============================================================================
-- ROLLBACK SCRIPT (if needed)
-- ============================================================================

/*
-- Drop the new columns
ALTER TABLE `cart_items` DROP COLUMN `product_type`;
ALTER TABLE `cart_items` DROP COLUMN `mart_product_name`;
ALTER TABLE `cart_items` DROP COLUMN `mart_product_image`;
ALTER TABLE `cart_items` DROP COLUMN `mart_product_unit`;
ALTER TABLE `cart_items` DROP COLUMN `mart_product_emoji`;

-- Re-add the unique constraint (only if no duplicates exist)
-- Check for duplicates first:
SELECT cart_id, product_id, COUNT(*) as count 
FROM cart_items 
GROUP BY cart_id, product_id 
HAVING count > 1;

-- If no duplicates, re-add the constraint:
ALTER TABLE `cart_items` 
ADD UNIQUE INDEX `cart_items_cart_id_product_id_unique` (`cart_id`, `product_id`);
*/

-- ============================================================================
-- SAMPLE DATA EXAMPLES
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
);
*/

-- Example 3: Mart product entry (weight-based)
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
    'كيلو غرام', '🍅',
    1, 6550.00, 6550.00,
    1, 500.00, 13100.00, 6550.00,
    NOW(), NOW()
);
*/

-- ============================================================================
-- VERIFICATION QUERIES
-- ============================================================================

-- Check if columns were added successfully
/*
DESCRIBE `cart_items`;
*/

-- Check if unique constraint was removed
/*
SHOW INDEXES FROM `cart_items`;
*/

-- Count cart items by product type
/*
SELECT 
    product_type,
    COUNT(*) as total_items,
    SUM(CASE WHEN is_weight_based = 1 THEN 1 ELSE 0 END) as weight_based_items
FROM cart_items
GROUP BY product_type;
*/

-- View all mart products in carts
/*
SELECT 
    ci.id,
    ci.cart_id,
    ci.product_id,
    ci.mart_product_name,
    ci.mart_product_unit,
    ci.quantity,
    ci.unit_price,
    ci.is_weight_based,
    ci.weight_grams,
    ci.amount_paid
FROM cart_items ci
WHERE ci.product_type = 'mart'
ORDER BY ci.cart_id, ci.created_at;
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
   - Each weight-based purchase creates a separate cart item
   - Multiple entries of the same product with different weights are allowed
   - Example: 500g tomatoes + 750g tomatoes = 2 separate cart items

4. REGULAR MART PRODUCTS:
   - Quantities are merged when adding the same product
   - Example: 2 apples + 3 apples = 1 cart item with quantity 5

5. BACKWARD COMPATIBILITY:
   - Existing cart items will have product_type = 'regular' by default
   - No data migration needed for existing records

6. FOREIGN KEY CONSTRAINTS:
   - cart_id references carts(id) with CASCADE delete
   - product_id references products(id) with CASCADE delete

7. UNIQUE CONSTRAINT REMOVAL:
   - The unique constraint on (cart_id, product_id) was removed
   - This is necessary to support multiple weight-based entries
   - Application logic ensures proper handling of duplicates
*/
