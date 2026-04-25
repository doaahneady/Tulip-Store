-- ============================================================================
-- User Saved Cards Table
-- Date: 2026-04-23
-- Description: Creates table for storing user's saved credit/debit cards
-- ============================================================================

-- Create user_saved_cards table if it doesn't exist
CREATE TABLE IF NOT EXISTS `user_saved_cards` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `brand` VARCHAR(32) NULL COMMENT 'Card brand (Visa, Mastercard, etc.)',
  `last4` VARCHAR(4) NOT NULL COMMENT 'Last 4 digits of card number',
  `expiry` VARCHAR(7) NULL COMMENT 'Expiry date in MM/YY format',
  `holder_name` VARCHAR(255) NULL COMMENT 'Cardholder name',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Foreign key constraint
  CONSTRAINT `fk_user_saved_cards_user_id` 
    FOREIGN KEY (`user_id`) 
    REFERENCES `users`(`id`) 
    ON DELETE CASCADE,
  
  -- Indexes
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Notes:
-- ============================================================================
-- 1. This table stores only card metadata (last 4 digits, expiry, brand)
-- 2. Full card numbers are NEVER stored for security reasons
-- 3. For actual payment processing, use Stripe tokens or similar
-- 4. Cards are automatically deleted when user is deleted (CASCADE)
-- 5. The 'brand' field stores card type (Visa, Mastercard, Amex, etc.)
-- 6. The 'last4' field stores only the last 4 digits for display purposes
-- 7. The 'expiry' field stores expiration date in MM/YY format
-- 8. The 'holder_name' field is optional and stores cardholder's name
-- ============================================================================
