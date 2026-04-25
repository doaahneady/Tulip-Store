-- Make product_id nullable in order_items table
-- This allows mart products with string IDs to be stored with NULL product_id

ALTER TABLE `order_items` 
MODIFY COLUMN `product_id` BIGINT UNSIGNED NULL;

-- Verify the change
DESCRIBE `order_items`;
