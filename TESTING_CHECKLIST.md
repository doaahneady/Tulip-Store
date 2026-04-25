# Stripe Payment Integration - Testing Checklist

## Test Scenarios

### 1. Basic Payment Flow
- [ ] Go to checkout page
- [ ] Select "Card" as payment method
- [ ] Fill in delivery details
- [ ] Submit order
- [ ] Verify redirect to `/payment/stripe/{orderId}`
- [ ] Verify payment page loads with correct order total in USD
- [ ] Enter test card: `4242 4242 4242 4242`
- [ ] Enter expiry: `12/25`
- [ ] Enter CVV: `123`
- [ ] Enter name: `Test User`
- [ ] Click "إتمام الدفع"
- [ ] Verify success message appears
- [ ] Verify redirect to `/my-orders` after 2 seconds
- [ ] Verify order shows as "paid" in my orders
- [ ] Verify cart is cleared

### 2. Save Card Feature
- [ ] Complete payment flow
- [ ] Check "حفظ البطاقة للمشتريات المستقبلية" checkbox
- [ ] Submit payment
- [ ] Verify payment succeeds
- [ ] Check database: user should have `stripe_customer_id`
- [ ] Check Stripe dashboard: customer should be created with card

### 3. Error Handling
- [ ] Try with declined card: `4000 0000 0000 0002`
- [ ] Verify error message displays
- [ ] Verify order remains in pending state
- [ ] Verify user can retry payment

### 4. Validation
- [ ] Try submitting with empty card number
- [ ] Try submitting with invalid expiry date
- [ ] Try submitting with short CVV
- [ ] Verify validation errors display

### 5. Security
- [ ] Try accessing payment page for order you don't own
- [ ] Verify 403 error
- [ ] Try accessing payment page for already paid order
- [ ] Verify redirect to order confirmation

### 6. UI/UX
- [ ] Verify card number formats with spaces (4 digits)
- [ ] Verify expiry auto-formats to MM/YY
- [ ] Verify CVV is masked
- [ ] Verify loading spinner shows during processing
- [ ] Verify success/error alerts are visible
- [ ] Test on mobile device
- [ ] Test back button works

### 7. Amount Display
- [ ] Verify amount always shows in USD ($)
- [ ] Verify amount matches order total
- [ ] Verify no currency conversion issues

## Test Cards

### Success
- `4242 4242 4242 4242` - Visa (succeeds)

### Declined
- `4000 0000 0000 0002` - Card declined
- `4000 0000 0000 9995` - Insufficient funds

### Errors
- `4000 0000 0000 0069` - Expired card
- `4000 0000 0000 0127` - Incorrect CVC

## Expected Results

### Successful Payment
1. Order `payment_status` = `'paid'`
2. Order `stripe_charge_id` populated
3. Financial transaction created with type `'card_payment'`
4. Cart cleared (session and database)
5. User redirected to my orders
6. If save card checked: `stripe_customer_id` saved to user

### Failed Payment
1. Order `payment_status` remains `'pending'`
2. Error message displayed to user
3. Cart NOT cleared
4. User can retry payment
5. No financial transaction created

## Database Checks

After successful payment, verify:
```sql
-- Order should be paid
SELECT id, order_number, payment_status, stripe_charge_id, total 
FROM orders 
WHERE id = {order_id};

-- Financial transaction should exist
SELECT * FROM financial_transactions 
WHERE order_id = {order_id} AND type = 'card_payment';

-- Cart should be empty
SELECT * FROM cart_items 
WHERE cart_id IN (SELECT id FROM carts WHERE user_id = {user_id});

-- If save card was checked
SELECT stripe_customer_id FROM users WHERE id = {user_id};
```

## Stripe Dashboard Checks

1. Go to https://dashboard.stripe.com/test/payments
2. Verify charge appears with correct amount
3. Verify metadata includes order_id and order_number
4. If save card: verify customer created in Customers section

## Notes

- Always use test mode keys
- Test in incognito/private window to avoid cache issues
- Check browser console for any JavaScript errors
- Check Laravel logs for any backend errors
