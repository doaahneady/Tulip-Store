# Comprehensive Business Flow Documentation
## Enterprise System - Complete Flow Definitions

**Last Updated:** {{ date('Y-m-d H:i:s') }}
**System Version:** Production
**Database:** PostgreSQL/MySQL

---

## TABLE OF CONTENTS

1. [Admin Dashboard Flows](#admin-dashboard-flows)
2. [IT Dashboard Flows](#it-dashboard-flows)
3. [HR Dashboard Flows](#hr-dashboard-flows)
4. [Customer Support Dashboard Flows](#customer-support-dashboard-flows)
5. [Driver Supervisor Dashboard Flows](#driver-supervisor-dashboard-flows)
6. [Finance Dashboard Flows](#finance-dashboard-flows)
7. [Cross-Department Flows](#cross-department-flows)
8. [Status Transition Rules](#status-transition-rules)
9. [Transaction & Rollback Logic](#transaction--rollback-logic)

---

## ADMIN DASHBOARD FLOWS

### Flow 1: User Creation & Role Assignment

**Start Condition:** Admin clicks "Create User"

**Step-by-Step Actions:**
1. Admin fills user form (name, email, password, roles)
2. System validates email uniqueness
3. System validates role permissions
4. **DB Transaction Begins**
5. Create user record in `users` table
6. Create user role assignments in `user_roles` table
7. Create audit log entry in `audit_logs`
8. Send welcome email notification
9. **DB Transaction Commits**

**Status Transitions:**
- User: `pending` → `active` (after email verification)
- User Roles: `is_active` = true

**Tables Updated:**
- `users` (INSERT)
- `user_roles` (INSERT)
- `audit_logs` (INSERT)
- `notifications` (INSERT)

**Financial Impact:** None

**Rollback Behavior:**
- If any step fails, entire transaction rolls back
- No partial user creation
- Audit log records failure reason

**API Endpoint:** `POST /dashboard/admin/users`
**Permissions Required:** `users.create`, `roles.assign`

---

### Flow 2: User Deactivation

**Start Condition:** Admin clicks "Deactivate User"

**Step-by-Step Actions:**
1. Admin selects user to deactivate
2. System checks for active orders
3. System checks for pending financial transactions
4. **DB Transaction Begins**
5. Update `users.status` = 'inactive'
6. Update `user_roles.is_active` = false for all user roles
7. Terminate active sessions (if session management exists)
8. Freeze pending orders (set status = 'frozen')
9. Block finance actions (add flag to user record)
10. Create audit log entry
11. **DB Transaction Commits**

**Status Transitions:**
- User: `active` → `inactive`
- User Roles: `is_active` = true → false
- Active Orders: `status` → `frozen`

**Tables Updated:**
- `users` (UPDATE)
- `user_roles` (UPDATE)
- `orders` (UPDATE - freeze pending orders)
- `audit_logs` (INSERT)

**Financial Impact:** 
- Pending orders frozen (no payment processing)
- No new financial transactions allowed

**Rollback Behavior:**
- If any step fails, restore user to previous state
- Unfreeze orders
- Restore role access

**API Endpoint:** `PUT /dashboard/admin/users/{user}/deactivate`
**Permissions Required:** `users.update`, `users.delete`

---

### Flow 3: Emergency Order Refund

**Start Condition:** Admin clicks "Force Refund" on order

**Step-by-Step Actions:**
1. Admin selects order and provides refund reason
2. System validates order payment status
3. System checks if refund already processed
4. **DB Transaction Begins**
5. Create refund transaction in `financial_transactions`
   - type = 'refund'
   - status = 'pending'
   - amount = order.total_amount
6. Update order status = 'refunded'
7. Update order payment_status = 'refunded'
8. If order has delivery assignment, cancel it
9. Update inventory (restore product quantities)
10. Create audit log entry
11. Send notification to customer
12. **DB Transaction Commits**
13. Process refund payment (external payment gateway)
14. Update transaction status = 'completed' after payment confirmation

**Status Transitions:**
- Order: `delivered`/`processing` → `refunded`
- Order Payment: `paid` → `refunded`
- Financial Transaction: `pending` → `processing` → `completed`
- Delivery Assignment: `delivered` → `cancelled` (if exists)

**Tables Updated:**
- `financial_transactions` (INSERT)
- `orders` (UPDATE)
- `order_items` (UPDATE - restore inventory)
- `products` (UPDATE - restore stock)
- `delivery_assignments` (UPDATE - cancel if exists)
- `audit_logs` (INSERT)
- `notifications` (INSERT)

**Financial Impact:**
- Revenue decreased by refund amount
- Inventory restored
- Commission reversed (if applicable)

**Rollback Behavior:**
- If payment gateway fails, mark transaction as 'failed'
- Do not restore order status
- Log failure for manual processing

**API Endpoint:** `POST /dashboard/admin/emergency/force-refund/{order}`
**Permissions Required:** `orders.refund`, `finance.approve`

---

## IT DASHBOARD FLOWS

### Flow 4: API Error Detection & Response

**Start Condition:** API endpoint returns error status code >= 500

**Step-by-Step Actions:**
1. Error interceptor catches exception
2. **DB Transaction Begins**
3. Log error in `system_logs`
   - level = 'error'
   - message = exception message
   - context = request data, stack trace
4. Create entry in `api_errors` table
5. Check if error threshold exceeded
6. If threshold exceeded, create `system_alerts`
   - severity = 'critical'
   - status = 'active'
7. Notify IT admins via `notifications`
8. Mark affected workflow as 'degraded' (if applicable)
9. **DB Transaction Commits**
10. Trigger retry mechanism (if applicable)
11. If retry fails, escalate to admin dashboard

**Status Transitions:**
- System Alert: `active` → `acknowledged` → `resolved`
- API Error: Logged with status code

**Tables Updated:**
- `system_logs` (INSERT)
- `api_errors` (INSERT)
- `system_alerts` (INSERT - if threshold exceeded)
- `notifications` (INSERT - to IT admins)

**Financial Impact:** None (monitoring only)

**Rollback Behavior:**
- Logging failures should not block error handling
- Use separate transaction for logging

**API Endpoint:** Automatic (error interceptor)
**Permissions Required:** System-level

---

### Flow 5: Database Backup Execution

**Start Condition:** IT admin clicks "Trigger Backup" or scheduled backup runs

**Step-by-Step Actions:**
1. System validates backup prerequisites
2. Check disk space availability
3. **DB Transaction Begins**
4. Create entry in `database_backups`
   - status = 'in_progress'
   - started_at = now()
5. **DB Transaction Commits**
6. Execute database backup command
7. Calculate backup file checksum
8. **DB Transaction Begins**
9. Update `database_backups`
   - status = 'completed'
   - completed_at = now()
   - file_size = backup file size
   - checksum = calculated checksum
10. **DB Transaction Commits**
11. Verify backup integrity
12. If verification fails, mark status = 'corrupted' and alert IT

**Status Transitions:**
- Backup: `in_progress` → `completed` / `failed` / `corrupted`

**Tables Updated:**
- `database_backups` (INSERT, UPDATE)
- `system_logs` (INSERT)
- `system_alerts` (INSERT - if corrupted)

**Financial Impact:** None

**Rollback Behavior:**
- If backup fails, mark as 'failed'
- Log error details
- Alert IT team

**API Endpoint:** `POST /dashboard/it/backups/trigger`
**Permissions Required:** `system.backup`

---

## HR DASHBOARD FLOWS

### Flow 6: Employee Clock-In

**Start Condition:** Employee clicks "Clock In" button

**Step-by-Step Actions:**
1. System validates employee status = 'active'
2. System checks for scheduled shift today
3. **DB Transaction Begins**
4. Create attendance record in `attendance` or `shifts` table
   - employee_id = current employee
   - shift_date = today
   - actual_start_time = now()
   - status = 'in_progress'
5. Check if late (compare with scheduled start_time)
6. If late, set late_flag = true
7. Update shift status = 'in_progress' (if shift exists)
8. Create audit log entry
9. **DB Transaction Commits**
10. Update payroll counters (increment days worked)
11. Send notification to HR manager if late

**Status Transitions:**
- Shift: `scheduled` → `in_progress`
- Attendance: Created with status `in_progress`

**Tables Updated:**
- `shifts` (UPDATE - if scheduled shift exists)
- `attendance` (INSERT - if separate table)
- `audit_logs` (INSERT)
- `notifications` (INSERT - if late)

**Financial Impact:**
- Payroll counters updated
- Overtime calculation may be affected

**Rollback Behavior:**
- If transaction fails, no attendance recorded
- Employee can retry clock-in

**API Endpoint:** `POST /dashboard/hr/attendance/clock-in`
**Permissions Required:** `hr.attendance.create`

---

### Flow 7: Leave Request Submission & Approval

**Start Condition:** Employee submits leave request

**Step-by-Step Actions:**

**Phase 1: Employee Submission**
1. Employee fills leave request form
2. System validates leave balance
3. System checks for conflicting dates
4. **DB Transaction Begins**
5. Create leave request in `leave_requests`
   - status = 'pending'
   - employee_id = current employee
   - start_date, end_date, type
6. Deduct from leave balance (tentative)
7. Create notification to HR manager
8. **DB Transaction Commits**

**Phase 2: HR Review**
9. HR manager reviews request
10. **DB Transaction Begins**
11. Update leave request status = 'approved' or 'rejected'
12. If approved:
    - Confirm leave balance deduction
    - Update employee availability calendar
    - Create notification to employee
13. If rejected:
    - Restore leave balance
    - Create notification with rejection reason
14. Create audit log entry
15. **DB Transaction Commits**

**Phase 3: Payroll Impact**
16. On payroll calculation, include approved leave days
17. Adjust salary calculation accordingly

**Status Transitions:**
- Leave Request: `pending` → `approved` / `rejected`
- Leave Balance: Updated accordingly
- Employee Availability: Updated if approved

**Tables Updated:**
- `leave_requests` (INSERT, UPDATE)
- `leave_balances` (UPDATE)
- `audit_logs` (INSERT)
- `notifications` (INSERT)
- `payroll_records` (UPDATE - during payroll calculation)

**Financial Impact:**
- Leave days affect payroll calculation
- Unpaid leave reduces salary

**Rollback Behavior:**
- If approval fails, restore to 'pending'
- Restore leave balance
- Remove availability updates

**API Endpoints:**
- `POST /dashboard/hr/leave-requests` (employee submission)
- `POST /dashboard/hr/leave-requests/{id}/approve` (HR approval)
- `POST /dashboard/hr/leave-requests/{id}/reject` (HR rejection)

**Permissions Required:**
- Employee: `hr.leave.create`
- HR Manager: `hr.leave.approve`

---

### Flow 8: Payroll Calculation & Submission to Finance

**Start Condition:** HR manager clicks "Calculate Payroll" for pay period

**Step-by-Step Actions:**

**Phase 1: Payroll Calculation**
1. HR selects pay period (e.g., "2024-01")
2. System fetches all active employees
3. **DB Transaction Begins**
4. For each employee:
   - Calculate regular hours from `shifts` table
   - Calculate overtime hours
   - Calculate bonuses and deductions
   - Calculate gross pay and net pay
   - Create/update `payroll_records`
     - status = 'draft'
     - pay_period = selected period
5. **DB Transaction Commits**

**Phase 2: HR Review & Approval**
6. HR reviews calculated payroll
7. HR can adjust individual records
8. **DB Transaction Begins**
9. Update payroll records status = 'approved'
10. Set approved_by = current HR manager
11. Set approved_at = now()
12. Create audit log entries
13. **DB Transaction Commits**

**Phase 3: Submission to Finance**
14. **DB Transaction Begins**
15. Create financial transactions for each payroll record
   - type = 'payroll'
   - status = 'pending'
   - amount = net_pay
   - linked to payroll_record_id
16. Update payroll_records status = 'submitted_to_finance'
17. Create notification to finance team
18. **DB Transaction Commits**

**Status Transitions:**
- Payroll Record: `draft` → `approved` → `submitted_to_finance` → `paid`
- Financial Transaction: `pending` → `approved` → `completed`

**Tables Updated:**
- `payroll_records` (INSERT, UPDATE)
- `shifts` (READ - for calculation)
- `leave_requests` (READ - for deduction)
- `financial_transactions` (INSERT)
- `audit_logs` (INSERT)
- `notifications` (INSERT)

**Financial Impact:**
- Total payroll amount affects company expenses
- Must be approved by finance before payment

**Rollback Behavior:**
- If calculation fails, rollback all draft records
- If approval fails, restore to draft
- If finance submission fails, restore to approved

**API Endpoints:**
- `POST /dashboard/hr/payroll/calculate`
- `POST /dashboard/hr/payroll/submit`
- `POST /dashboard/finance/payroll/{id}/approve`
- `POST /dashboard/finance/payroll/{id}/process`

**Permissions Required:**
- HR: `hr.payroll.calculate`, `hr.payroll.submit`
- Finance: `finance.payroll.approve`, `finance.payroll.process`

---

## CUSTOMER SUPPORT DASHBOARD FLOWS

### Flow 9: Ticket Creation from Order Complaint

**Start Condition:** Customer submits complaint about order OR support agent creates ticket manually

**Step-by-Step Actions:**
1. Customer/Agent fills ticket form
2. If order-related, link to `orders` table
3. **DB Transaction Begins**
4. Create ticket in `support_tickets`
   - ticket_number = auto-generated
   - customer_id = user_id
   - related_order_id = order_id (if applicable)
   - status = 'open'
   - priority = calculated based on order value/type
5. Create first reply in `ticket_replies`
6. Create notification to support team
7. Create notification to customer
8. If order-related, update order notes
9. **DB Transaction Commits**

**Status Transitions:**
- Ticket: Created with status `open`
- Order: Notes updated (if linked)

**Tables Updated:**
- `support_tickets` (INSERT)
- `ticket_replies` (INSERT)
- `orders` (UPDATE - add note)
- `notifications` (INSERT)
- `audit_logs` (INSERT)

**Financial Impact:** None (unless ticket leads to refund)

**Rollback Behavior:**
- If ticket creation fails, no partial records
- Customer can retry submission

**API Endpoint:** `POST /dashboard/cs/tickets`
**Permissions Required:** `support.tickets.create`

---

### Flow 10: Ticket Resolution & Order Impact

**Start Condition:** Support agent resolves ticket

**Step-by-Step Actions:**
1. Support agent provides resolution details
2. **DB Transaction Begins**
3. Update ticket status = 'resolved'
4. Set resolved_at = now()
5. Create final reply in `ticket_replies`
6. If ticket requires order refund:
   - Create refund transaction (see Flow 3)
   - Update order status
7. If ticket requires order status change:
   - Update order status accordingly
8. Create notification to customer
9. Create audit log entry
10. **DB Transaction Commits**

**Status Transitions:**
- Ticket: `open`/`in_progress` → `resolved` → `closed`
- Order: May change status if ticket requires it
- Financial Transaction: Created if refund required

**Tables Updated:**
- `support_tickets` (UPDATE)
- `ticket_replies` (INSERT)
- `orders` (UPDATE - if applicable)
- `financial_transactions` (INSERT - if refund)
- `notifications` (INSERT)
- `audit_logs` (INSERT)

**Financial Impact:**
- May trigger refund transaction
- May affect order status

**Rollback Behavior:**
- If resolution fails, restore ticket to previous status
- If refund fails, mark ticket as requiring manual review

**API Endpoint:** `POST /dashboard/cs/tickets/{id}/resolve`
**Permissions Required:** `support.tickets.resolve`

---

## DRIVER SUPERVISOR DASHBOARD FLOWS

### Flow 11: Order-to-Driver Assignment

**Start Condition:** Supervisor assigns order to driver

**Step-by-Step Actions:**
1. Supervisor selects order and available driver
2. System validates driver availability
3. System validates order status
4. **DB Transaction Begins**
5. Create delivery assignment in `delivery_assignments`
   - order_id = selected order
   - driver_id = selected driver
   - assigned_by = current supervisor
   - status = 'assigned'
   - assigned_at = now()
6. Update order status = 'out_for_delivery'
7. Update driver availability = 'busy'
8. Update driver status in `drivers` table
9. Create notification to driver
10. Create notification to customer
11. Create audit log entry
12. **DB Transaction Commits**

**Status Transitions:**
- Order: `confirmed`/`processing` → `out_for_delivery`
- Driver: `available` → `busy`
- Delivery Assignment: Created with status `assigned`

**Tables Updated:**
- `delivery_assignments` (INSERT)
- `orders` (UPDATE)
- `drivers` (UPDATE)
- `notifications` (INSERT)
- `audit_logs` (INSERT)

**Financial Impact:** None (delivery fee calculated at order creation)

**Rollback Behavior:**
- If assignment fails, restore order to previous status
- Restore driver availability
- Remove any partial assignment records

**API Endpoint:** `POST /dashboard/supervisor/assign-order`
**Permissions Required:** `logistics.assign_driver`

---

### Flow 12: Delivery Completion & Financial Impact

**Start Condition:** Driver marks delivery as completed

**Step-by-Step Actions:**
1. Driver confirms delivery completion
2. Driver uploads delivery proof (photo/signature)
3. **DB Transaction Begins**
4. Update delivery assignment status = 'delivered'
5. Set delivered_at = now()
6. Store delivery proof in `delivery_assignments.delivery_proof`
7. Update order status = 'delivered'
8. Update order delivered_at = now()
9. Update order payment_status = 'paid' (if cash on delivery)
10. Update driver availability = 'available'
11. Increment driver total_deliveries counter
12. Calculate driver payout eligibility
13. Create financial transaction for driver payout
   - type = 'driver_payout'
   - status = 'pending'
   - amount = delivery_fee
14. Create financial transaction for order revenue
   - type = 'order_payment'
   - status = 'completed'
   - amount = order.total_amount
15. Calculate and create commission transaction
   - type = 'commission'
   - status = 'pending'
   - amount = order.commission_amount
16. Update inventory (deduct sold products)
17. Create notification to customer
18. Create notification to store owner
19. Create audit log entry
20. **DB Transaction Commits**

**Status Transitions:**
- Order: `out_for_delivery` → `delivered`
- Order Payment: `pending` → `paid` (if COD)
- Delivery Assignment: `in_transit` → `delivered`
- Driver: `busy` → `available`
- Financial Transactions: Created for revenue, commission, driver payout

**Tables Updated:**
- `delivery_assignments` (UPDATE)
- `orders` (UPDATE)
- `drivers` (UPDATE)
- `financial_transactions` (INSERT - 3 transactions)
- `order_items` (READ - for inventory)
- `products` (UPDATE - deduct stock)
- `notifications` (INSERT)
- `audit_logs` (INSERT)

**Financial Impact:**
- Revenue recognized
- Commission calculated
- Driver payout created
- Inventory reduced

**Rollback Behavior:**
- If any step fails, restore all statuses
- Do not process payments
- Restore inventory
- Mark for manual review

**API Endpoint:** `POST /dashboard/supervisor/deliveries/{assignment}/complete`
**Permissions Required:** `logistics.complete_delivery`

---

## FINANCE DASHBOARD FLOWS

### Flow 13: Order Completion → Revenue Recognition

**Start Condition:** Order marked as delivered (triggered by Flow 12)

**Step-by-Step Actions:**
1. System detects order status = 'delivered'
2. System validates payment status = 'paid'
3. **DB Transaction Begins**
4. Create revenue transaction in `financial_transactions`
   - type = 'order_payment'
   - status = 'completed'
   - amount = order.total_amount
   - order_id = order.id
5. Calculate platform commission
6. Create commission transaction
   - type = 'commission'
   - status = 'pending_approval'
   - amount = order.total_amount * commission_rate
7. Calculate store owner earnings
8. Update store balance (if store_balances table exists)
9. Create audit log entry
10. **DB Transaction Commits**
11. Finance reviews and approves commission
12. Process commission payout to store owner

**Status Transitions:**
- Financial Transaction (Revenue): `pending` → `completed`
- Financial Transaction (Commission): `pending` → `pending_approval` → `approved` → `completed`

**Tables Updated:**
- `financial_transactions` (INSERT - 2 transactions)
- `orders` (READ - for calculation)
- `stores` (UPDATE - balance if exists)
- `audit_logs` (INSERT)

**Financial Impact:**
- Revenue increased
- Commission liability created
- Store owner balance increased

**Rollback Behavior:**
- If transaction creation fails, order remains delivered but no revenue recorded
- Requires manual intervention

**API Endpoint:** Automatic (triggered by order completion)
**Permissions Required:** System-level

---

### Flow 14: Payout Approval & Processing

**Start Condition:** Store owner requests payout OR finance processes scheduled payout

**Step-by-Step Actions:**

**Phase 1: Payout Request (if manual)**
1. Store owner submits payout request
2. System validates store balance
3. **DB Transaction Begins**
4. Create payout record in `payouts`
   - status = 'pending'
   - amount = requested amount
   - store_id = store.id
5. **DB Transaction Commits**

**Phase 2: Finance Approval**
6. Finance reviews payout request
7. **DB Transaction Begins**
8. Update payout status = 'approved'
9. Set approved_by = finance manager
10. Set approved_at = now()
11. Create financial transaction
   - type = 'payout'
   - status = 'pending'
   - amount = payout.amount
12. Deduct from store balance
13. Create audit log entry
14. **DB Transaction Commits**

**Phase 3: Payment Processing**
15. Finance processes payment (external bank transfer)
16. **DB Transaction Begins**
17. Update payout status = 'processing'
18. Update financial transaction status = 'processing'
19. **DB Transaction Commits**
20. Execute bank transfer
21. **DB Transaction Begins**
22. Update payout status = 'completed'
23. Update financial transaction status = 'completed'
24. Set processed_at = now()
25. Set reference_number = bank reference
26. Create notification to store owner
27. **DB Transaction Commits**

**Status Transitions:**
- Payout: `pending` → `approved` → `processing` → `completed`
- Financial Transaction: `pending` → `processing` → `completed`
- Store Balance: Decreased by payout amount

**Tables Updated:**
- `payouts` (INSERT, UPDATE)
- `financial_transactions` (INSERT, UPDATE)
- `stores` (UPDATE - balance)
- `audit_logs` (INSERT)
- `notifications` (INSERT)

**Financial Impact:**
- Company cash decreased
- Store owner receives payment
- Store balance decreased

**Rollback Behavior:**
- If approval fails, restore to pending
- If payment processing fails, mark as failed and alert finance
- Do not reverse store balance deduction until confirmed failure

**API Endpoints:**
- `POST /dashboard/vendor/payouts/request` (store owner)
- `POST /dashboard/finance/payouts/{id}/approve`
- `POST /dashboard/finance/payouts/{id}/process`

**Permissions Required:**
- Store Owner: `finance.payout.request`
- Finance: `finance.payout.approve`, `finance.payout.process`

---

## CROSS-DEPARTMENT FLOWS

### Flow 15: HR → Finance Payroll Flow

**Start Condition:** HR submits approved payroll to finance

**Step-by-Step Actions:**
1. HR completes payroll calculation (Flow 8)
2. HR submits payroll to finance
3. **DB Transaction Begins**
4. Create financial transactions for each payroll record
   - type = 'payroll'
   - status = 'pending_approval'
   - amount = payroll_record.net_pay
5. Update payroll_records status = 'submitted_to_finance'
6. Create notification to finance team
7. **DB Transaction Commits**
8. Finance reviews payroll
9. Finance approves payroll
10. Finance processes payments
11. Update payroll_records status = 'paid'
12. Update financial transactions status = 'completed'

**Status Transitions:**
- Payroll Record: `approved` → `submitted_to_finance` → `paid`
- Financial Transaction: `pending_approval` → `approved` → `completed`

**Tables Updated:**
- `payroll_records` (UPDATE)
- `financial_transactions` (INSERT, UPDATE)
- `notifications` (INSERT)
- `audit_logs` (INSERT)

**Financial Impact:**
- Company expenses increased by total payroll
- Employee accounts credited

**Rollback Behavior:**
- If finance approval fails, restore payroll to approved
- If payment fails, mark for retry

---

### Flow 16: Orders → Support → Finance Flow

**Start Condition:** Customer complaint leads to refund

**Step-by-Step Actions:**
1. Customer submits complaint (Flow 9)
2. Support creates ticket linked to order
3. Support determines refund is required
4. Support escalates to finance for approval
5. Finance reviews refund request
6. **DB Transaction Begins**
7. Finance approves refund
8. Create refund transaction (Flow 3)
9. Update ticket status = 'resolved'
10. Update order status = 'refunded'
11. **DB Transaction Commits**
12. Process refund payment
13. Update transaction status = 'completed'

**Status Transitions:**
- Ticket: `open` → `resolved`
- Order: `delivered` → `refunded`
- Financial Transaction: `pending` → `approved` → `completed`

**Tables Updated:**
- `support_tickets` (UPDATE)
- `orders` (UPDATE)
- `financial_transactions` (INSERT, UPDATE)
- `audit_logs` (INSERT)

**Financial Impact:**
- Revenue decreased by refund amount
- Customer account credited

**Rollback Behavior:**
- If refund fails, ticket remains resolved but order not refunded
- Requires manual intervention

---

### Flow 17: Orders → Drivers → Finance Flow

**Start Condition:** Order assigned to driver (Flow 11) → Delivery completed (Flow 12)

**Step-by-Step Actions:**
1. Supervisor assigns order to driver
2. Driver completes delivery
3. System triggers revenue recognition (Flow 13)
4. System creates driver payout transaction
5. Finance approves driver payout
6. Finance processes driver payment
7. Update driver payout transaction status = 'completed'

**Status Transitions:**
- Order: `confirmed` → `out_for_delivery` → `delivered`
- Driver Payout: `pending` → `approved` → `completed`
- Revenue: Recognized

**Tables Updated:**
- `orders` (UPDATE)
- `delivery_assignments` (UPDATE)
- `financial_transactions` (INSERT - multiple)
- `drivers` (UPDATE)

**Financial Impact:**
- Revenue recognized
- Driver paid
- Commission calculated

**Rollback Behavior:**
- If any step fails, mark for manual review
- Do not process partial payments

---

### Flow 18: Admin Override → All Systems

**Start Condition:** Admin performs emergency override action

**Step-by-Step Actions:**
1. Admin selects override action
2. System validates admin permissions
3. **DB Transaction Begins**
4. Execute override action:
   - User deactivation (Flow 2)
   - Order refund (Flow 3)
   - Force ticket closure
   - Override financial approval
   - System maintenance mode
5. Create audit log entry with override flag
6. Create notification to affected departments
7. **DB Transaction Commits**

**Status Transitions:**
- Varies by override type
- All overrides logged with admin_id

**Tables Updated:**
- Varies by override type
- `audit_logs` (INSERT - always)
- `notifications` (INSERT - to affected departments)

**Financial Impact:**
- Varies by override type

**Rollback Behavior:**
- Admin can reverse override actions
- All reversals logged

**API Endpoint:** Various admin override endpoints
**Permissions Required:** `admin.override.*`

---

### Flow 19: IT Alerts → Admin Actions

**Start Condition:** IT system detects critical alert

**Step-by-Step Actions:**
1. System detects critical error/alert
2. IT dashboard creates system alert
3. System checks alert severity
4. If severity = 'critical' or 'emergency':
   - Create notification to admin dashboard
   - Create notification to IT team
   - Auto-escalate to admin if no response in X minutes
5. Admin reviews alert
6. Admin takes action (maintenance mode, rollback, etc.)
7. IT resolves alert
8. Update system alert status = 'resolved'

**Status Transitions:**
- System Alert: `active` → `acknowledged` → `resolved`
- System Status: May change based on alert type

**Tables Updated:**
- `system_alerts` (INSERT, UPDATE)
- `notifications` (INSERT)
- `audit_logs` (INSERT)

**Financial Impact:**
- May affect system availability
- May trigger maintenance mode

**Rollback Behavior:**
- Alert resolution can be reversed
- System can be restored to previous state

---

## STATUS TRANSITION RULES

### Order Status Transitions

**Allowed Transitions:**
- `pending` → `confirmed`, `cancelled`
- `confirmed` → `processing`, `cancelled`
- `processing` → `out_for_delivery`, `cancelled`
- `out_for_delivery` → `delivered`, `failed`
- `delivered` → `refunded`, `returned`
- `cancelled` → (final state)
- `refunded` → (final state)
- `returned` → (final state)

**Invalid Transitions (Prevented by System):**
- `delivered` → `pending` (cannot undo delivery)
- `refunded` → `delivered` (cannot undo refund)
- `cancelled` → `confirmed` (requires admin override)

---

### Payment Status Transitions

**Allowed Transitions:**
- `pending` → `paid`, `failed`, `cancelled`
- `paid` → `refunded`, `partial`
- `partial` → `paid`, `refunded`
- `failed` → `pending` (retry), `cancelled`
- `refunded` → (final state)
- `cancelled` → (final state)

---

### Financial Transaction Status Transitions

**Allowed Transitions:**
- `pending` → `processing`, `cancelled`
- `processing` → `completed`, `failed`
- `completed` → (final state - locked)
- `failed` → `pending` (retry), `cancelled`
- `cancelled` → (final state)

**Approval Workflow:**
- `pending` → `pending_approval` → `approved` → `processing` → `completed`
- `pending_approval` → `rejected` (final state)

---

### Delivery Assignment Status Transitions

**Allowed Transitions:**
- `assigned` → `accepted`, `rejected`, `cancelled`
- `accepted` → `picked_up`, `cancelled`
- `picked_up` → `in_transit`, `cancelled`
- `in_transit` → `delivered`, `failed`
- `delivered` → (final state)
- `failed` → `assigned` (reassign), `cancelled`
- `rejected` → `assigned` (reassign), `cancelled`
- `cancelled` → (final state)

---

### Payroll Record Status Transitions

**Allowed Transitions:**
- `draft` → `approved`, `cancelled`
- `approved` → `submitted_to_finance`, `draft` (edit)
- `submitted_to_finance` → `paid`, `approved` (reject)
- `paid` → (final state)
- `cancelled` → (final state)

---

## TRANSACTION & ROLLBACK LOGIC

### Database Transaction Requirements

**All Financial Operations MUST use transactions:**
- Order creation
- Payment processing
- Refund processing
- Payout processing
- Payroll processing
- Commission calculation

**All Multi-Table Updates MUST use transactions:**
- Order status changes affecting inventory
- User deactivation affecting orders
- Delivery assignment affecting order and driver status
- Payroll submission affecting multiple records

**Transaction Isolation Level:**
- Default: `READ COMMITTED`
- Financial operations: `REPEATABLE READ` or `SERIALIZABLE`

---

### Rollback Scenarios

**Scenario 1: Order Creation Failure**
- Rollback: Order, order_items, inventory deduction
- Action: Customer sees error, can retry

**Scenario 2: Payment Processing Failure**
- Rollback: Financial transaction, order payment_status
- Action: Order remains pending payment, customer can retry

**Scenario 3: Delivery Assignment Failure**
- Rollback: Delivery assignment, order status, driver availability
- Action: Order remains available for assignment

**Scenario 4: Payroll Processing Failure**
- Rollback: Financial transactions, payroll record status
- Action: Payroll remains in approved state, requires manual retry

**Scenario 5: Refund Processing Failure**
- Rollback: Financial transaction status, order status
- Action: Refund marked as failed, requires manual processing

---

### Error Handling Strategy

**Level 1: Automatic Retry**
- Network errors
- Temporary database locks
- External API timeouts

**Level 2: Manual Review**
- Validation failures
- Business rule violations
- Insufficient funds/balance

**Level 3: Admin Intervention**
- System errors
- Data corruption
- Security violations

---

## AUDIT REQUIREMENTS

**All Actions Must Be Audited:**
- User creation/deletion
- Role assignments
- Financial transactions
- Order status changes
- Payroll processing
- Admin overrides

**Audit Log Fields:**
- user_id (who performed action)
- action (what was done)
- model_type (what entity was affected)
- model_id (which specific record)
- old_values (previous state)
- new_values (new state)
- ip_address
- user_agent
- timestamp

**Audit Log Retention:**
- Financial: 7 years
- HR: 5 years
- General: 2 years

---

## END OF DOCUMENTATION

**Next Steps:**
1. Implement missing transaction wrappers
2. Add status transition validation
3. Create rollback handlers
4. Add comprehensive audit logging
5. Implement cross-department notifications
6. Add automated testing for all flows

