-- Add weight-based fields to order_items table
-- This allows tracking weight information for weight-based purchases

ALTER TABLE `order_items` 
ADD COLUMN `is_weight_based` TINYINT(1) NOT NULL DEFAULT 0 AFTER `quantity`,
ADD COLUMN `weight_grams` DECIMAL(10,2) NULL AFTER `is_weight_based`,
ADD COLUMN `price_per_unit` DECIMAL(10,2) NULL AFTER `weight_grams`;

-- Verify the changes
DESCRIBE `order_items`;
