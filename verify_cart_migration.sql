-- ============================================================================
-- Verification Script for Cart Account Persistence Migration
-- ============================================================================

-- Check if the migration has already been applied
SELECT 'Checking if migration columns exist...' AS status;

-- Check for product_type column
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ product_type column EXISTS'
        ELSE '✗ product_type column MISSING'
    END AS product_type_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'product_type';

-- Check for mart_product_name column
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ mart_product_name column EXISTS'
        ELSE '✗ mart_product_name column MISSING'
    END AS mart_product_name_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'mart_product_name';

-- Check for mart_product_image column
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ mart_product_image column EXISTS'
        ELSE '✗ mart_product_image column MISSING'
    END AS mart_product_image_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'mart_product_image';

-- Check for mart_product_unit column
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ mart_product_unit column EXISTS'
        ELSE '✗ mart_product_unit column MISSING'
    END AS mart_product_unit_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'mart_product_unit';

-- Check for mart_product_emoji column
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✓ mart_product_emoji column EXISTS'
        ELSE '✗ mart_product_emoji column MISSING'
    END AS mart_product_emoji_status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND COLUMN_NAME = 'mart_product_emoji';

-- Check if unique constraint was removed
SELECT 
    CASE 
        WHEN COUNT(*) = 0 THEN '✓ Unique constraint REMOVED (correct)'
        ELSE '✗ Unique constraint STILL EXISTS (needs removal)'
    END AS unique_constraint_status
FROM INFORMATION_SCHEMA.STATISTICS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'cart_items' 
AND INDEX_NAME = 'cart_items_cart_id_product_id_unique';

-- Show complete table structure
SELECT '=== Complete cart_items table structure ===' AS info;
DESCRIBE `cart_items`;

-- Show all indexes
SELECT '=== All indexes on cart_items ===' AS info;
SHOW INDEXES FROM `cart_items`;

-- Count existing cart items by type
SELECT '=== Cart items count by product type ===' AS info;
SELECT 
    product_type,
    COUNT(*) as total_items,
    SUM(CASE WHEN is_weight_based = 1 THEN 1 ELSE 0 END) as weight_based_items,
    SUM(CASE WHEN is_weight_based = 0 THEN 1 ELSE 0 END) as regular_items
FROM cart_items
GROUP BY product_type
UNION ALL
SELECT 
    'TOTAL' as product_type,
    COUNT(*) as total_items,
    SUM(CASE WHEN is_weight_based = 1 THEN 1 ELSE 0 END) as weight_based_items,
    SUM(CASE WHEN is_weight_based = 0 THEN 1 ELSE 0 END) as regular_items
FROM cart_items;

-- Show sample data (if any exists)
SELECT '=== Sample cart items (first 5) ===' AS info;
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
