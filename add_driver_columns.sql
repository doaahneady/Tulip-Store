-- Add driver assignment columns to orders table
ALTER TABLE orders 
ADD COLUMN assigned_driver_id BIGINT UNSIGNED NULL AFTER user_id,
ADD COLUMN assigned_at TIMESTAMP NULL AFTER assigned_driver_id,
ADD COLUMN assigned_by BIGINT UNSIGNED NULL AFTER assigned_at,
ADD COLUMN confirmation_token VARCHAR(255) NULL UNIQUE AFTER payment_status,
ADD COLUMN confirmed_at TIMESTAMP NULL AFTER confirmation_token,
ADD COLUMN customer_signature TEXT NULL AFTER confirmed_at,
ADD COLUMN delivery_notes TEXT NULL AFTER address_note;
