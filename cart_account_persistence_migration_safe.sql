-- ============================================================================
-- Cart Account Persistence Migration (SAFE VERSION)
-- Date: 2026-04-23
-- Description: Adds support for storing mart products in the database cart
-- This version checks if columns exist before adding them
-- ============================================================================

-- ============================================================================
-- STEP 1: Add new columns to cart_items table (with existence checks)
-- ============================================================================

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
    'SELECT ''Column product_type already exists'' AS message'
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
    'SELECT ''Column mart_product_name already exists'' AS message'
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
    'SELECT ''Column mart_product_image already exists'' AS message'
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
    'SELECT ''Column mart_product_unit already exists'' AS message'
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
    'SELECT ''Column mart_product_emoji already exists'' AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ============================================================================
-- STEP 2: Drop unique constraint (if it exists)
-- ============================================================================

SET @index_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'cart_items' 
    AND INDEX_NAME = 'cart_items_cart_id_product_id_unique'
);

SET @sql = IF(@index_exists > 0, 
    'ALTER TABLE `cart_items` DROP INDEX `cart_items_cart_id_product_id_unique`',
    'SELECT ''Index cart_items_cart_id_product_id_unique does not exist'' AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ============================================================================
-- VERIFICATION: Show current table structure
-- ============================================================================

SELECT 'Current cart_items table structure:' AS message;
DESCRIBE `cart_items`;

SELECT 'Current indexes on cart_items:' AS message;
SHOW INDEXES FROM `cart_items`;

-- ============================================================================
-- SUCCESS MESSAGE
-- ============================================================================

SELECT 'Migration completed successfully!' AS message;
SELECT 'Cart items can now store mart products in the database.' AS message;
