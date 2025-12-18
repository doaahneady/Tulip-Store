# API Endpoints by Dashboard

## Super Admin Dashboard (God Mode)

### User Management
- `GET /api/admin/users` - List all users with filters
- `POST /api/admin/users` - Create new user
- `PUT /api/admin/users/{id}` - Update user
- `DELETE /api/admin/users/{id}` - Delete user
- `POST /api/admin/users/{id}/roles` - Assign roles
- `DELETE /api/admin/users/{id}/roles/{roleId}` - Remove role

### RBAC Management
- `GET /api/admin/roles` - List all roles
- `POST /api/admin/roles` - Create role
- `PUT /api/admin/roles/{id}` - Update role
- `GET /api/admin/permissions` - List all permissions
- `POST /api/admin/roles/{id}/permissions` - Assign permissions

### Platform Analytics
- `GET /api/admin/analytics/overview` - Platform-wide metrics
- `GET /api/admin/analytics/revenue` - Revenue analytics
- `GET /api/admin/analytics/users` - User growth metrics
- `GET /api/admin/analytics/orders` - Order analytics
- `GET /api/admin/analytics/stores` - Store performance

### Audit Logs
- `GET /api/admin/audit-logs` - System audit logs
- `GET /api/admin/audit-logs/{id}` - Specific audit entry
- `POST /api/admin/audit-logs/export` - Export audit data

### Emergency Overrides
- `POST /api/admin/emergency/unlock-user/{id}` - Emergency user unlock
- `POST /api/admin/emergency/force-refund/{orderId}` - Force refund
- `POST /api/admin/emergency/system-maintenance` - Enable maintenance mode

## IT/DevOps Dashboard

### System Health
- `GET /api/devops/services` - All system services status
- `GET /api/devops/services/{id}/health` - Specific service health
- `POST /api/devops/services/{id}/restart` - Restart service
- `GET /api/devops/system/metrics` - System performance metrics

### Error Logging
- `GET /api/devops/logs` - System logs with filters
- `GET /api/devops/logs/errors` - Error logs only
- `GET /api/devops/logs/slow-queries` - Database performance issues
- `POST /api/devops/logs/export` - Export logs

### Database Management
- `GET /api/devops/database/status` - Database health
- `GET /api/devops/database/backups` - Backup status
- `POST /api/devops/database/backup` - Trigger backup
- `GET /api/devops/database/performance` - Query performance

### Deployment Management
- `GET /api/devops/deployments` - Deployment history
- `POST /api/devops/deployments` - Create deployment
- `GET /api/devops/deployments/{id}/status` - Deployment status
- `POST /api/devops/deployments/{id}/rollback` - Rollback deployment

### Integration Health
- `GET /api/devops/integrations` - External service status
- `GET /api/devops/integrations/payment-gateways` - Payment gateway health
- `POST /api/devops/integrations/{service}/test` - Test integration

## HR Dashboard

### Employee Management
- `GET /api/hr/employees` - List employees
- `POST /api/hr/employees` - Create employee
- `PUT /api/hr/employees/{id}` - Update employee
- `GET /api/hr/employees/{id}/profile` - Employee profile
- `POST /api/hr/employees/{id}/documents` - Upload documents

### Shift Management (Drivers)
- `GET /api/hr/shifts` - All shifts
- `POST /api/hr/shifts` - Schedule shift
- `PUT /api/hr/shifts/{id}` - Update shift
- `GET /api/hr/drivers/shifts` - Driver-specific shifts
- `POST /api/hr/drivers/{id}/schedule` - Schedule driver

### Payroll Processing
- `GET /api/hr/payroll` - Payroll records
- `POST /api/hr/payroll/calculate` - Calculate payroll
- `POST /api/hr/payroll/submit` - Submit to Finance
- `GET /api/hr/payroll/{period}/report` - Payroll report

### Performance Reviews
- `GET /api/hr/reviews` - Performance reviews
- `POST /api/hr/reviews` - Create review
- `PUT /api/hr/reviews/{id}` - Update review
- `GET /api/hr/employees/{id}/performance` - Employee performance

### Recruiting
- `GET /api/hr/applications` - Job applications
- `POST /api/hr/applications/{id}/status` - Update application status
- `GET /api/hr/positions` - Open positions
- `POST /api/hr/positions` - Create job posting

## Driver Supervisor Dashboard

### Live Tracking
- `GET /api/supervisor/drivers/locations` - All driver locations
- `GET /api/supervisor/drivers/{id}/location` - Specific driver location
- `GET /api/supervisor/drivers/{id}/route` - Driver route history
- `POST /api/supervisor/drivers/{id}/location` - Update location (from mobile)

### Fleet Management
- `GET /api/supervisor/drivers` - All drivers
- `GET /api/supervisor/drivers/{id}/status` - Driver status
- `POST /api/supervisor/drivers/{id}/status` - Update driver status
- `GET /api/supervisor/vehicles` - Vehicle information
- `POST /api/supervisor/vehicles/{id}/maintenance` - Log maintenance

### Order Assignment
- `GET /api/supervisor/orders/pending` - Orders awaiting assignment
- `POST /api/supervisor/orders/{id}/assign` - Assign driver to order
- `GET /api/supervisor/assignments` - All delivery assignments
- `PUT /api/supervisor/assignments/{id}` - Update assignment

### Route Optimization
- `POST /api/supervisor/routes/optimize` - Optimize delivery routes
- `GET /api/supervisor/routes/{driverId}` - Get optimized route
- `POST /api/supervisor/routes/{driverId}/update` - Update route

### Delivery Proof
- `GET /api/supervisor/deliveries/completed` - Completed deliveries
- `GET /api/supervisor/deliveries/{id}/proof` - Delivery proof
- `POST /api/supervisor/deliveries/{id}/verify` - Verify delivery

## Finance Dashboard

### Transaction Management
- `GET /api/finance/transactions` - All transactions
- `GET /api/finance/transactions/{id}` - Transaction details
- `POST /api/finance/transactions/{id}/approve` - Approve transaction
- `POST /api/finance/transactions/{id}/reject` - Reject transaction

### Revenue Tracking
- `GET /api/finance/revenue/overview` - Revenue overview
- `GET /api/finance/revenue/daily` - Daily revenue
- `GET /api/finance/revenue/by-store` - Revenue by store
- `GET /api/finance/commission/summary` - Commission summary

### Payout Management
- `GET /api/finance/payouts` - Payout requests
- `POST /api/finance/payouts/{id}/approve` - Approve payout
- `POST /api/finance/payouts/{id}/process` - Process payout
- `GET /api/finance/payouts/{id}/status` - Payout status

### Payroll Processing
- `GET /api/finance/payroll/pending` - Pending payroll from HR
- `POST /api/finance/payroll/{id}/approve` - Approve payroll
- `POST /api/finance/payroll/{id}/process` - Process payroll payments
- `GET /api/finance/payroll/history` - Payroll history

### Financial Reports
- `GET /api/finance/reports/pnl` - Profit & Loss statement
- `GET /api/finance/reports/balance-sheet` - Balance sheet
- `GET /api/finance/reports/cash-flow` - Cash flow statement
- `GET /api/finance/reports/tax` - Tax reports

### Tax Management
- `GET /api/finance/tax/summary` - Tax summary
- `GET /api/finance/tax/vat` - VAT calculations
- `POST /api/finance/tax/calculate` - Calculate taxes
- `GET /api/finance/tax/compliance` - Compliance status

## Product Owner (Vendor) Dashboard

### Inventory Management
- `GET /api/vendor/products` - Store products
- `POST /api/vendor/products` - Create product
- `PUT /api/vendor/products/{id}` - Update product
- `DELETE /api/vendor/products/{id}` - Delete product
- `POST /api/vendor/products/{id}/stock` - Update stock

### Order Management
- `GET /api/vendor/orders` - Store orders
- `GET /api/vendor/orders/{id}` - Order details
- `POST /api/vendor/orders/{id}/status` - Update order status
- `GET /api/vendor/orders/analytics` - Order analytics

### Sales Reports
- `GET /api/vendor/sales/overview` - Sales overview
- `GET /api/vendor/sales/daily` - Daily sales
- `GET /api/vendor/sales/products` - Product performance
- `GET /api/vendor/sales/customers` - Customer analytics

### Financial Management
- `GET /api/vendor/earnings` - Earnings summary
- `GET /api/vendor/transactions` - Store transactions
- `POST /api/vendor/payouts/request` - Request payout
- `GET /api/vendor/payouts` - Payout history

### Store Management
- `GET /api/vendor/store/profile` - Store profile
- `PUT /api/vendor/store/profile` - Update store profile
- `GET /api/vendor/store/settings` - Store settings
- `PUT /api/vendor/store/settings` - Update settings

## Cross-Dashboard Real-time Events

### WebSocket Endpoints
- `ws://api/events/admin` - Super Admin events
- `ws://api/events/devops` - IT/DevOps events
- `ws://api/events/hr` - HR events
- `ws://api/events/supervisor` - Driver Supervisor events
- `ws://api/events/finance` - Finance events
- `ws://api/events/vendor/{storeId}` - Vendor-specific events

### Event Types
- `order.created` - New order placed
- `order.updated` - Order status changed
- `payment.completed` - Payment processed
- `driver.location_updated` - Driver location changed
- `payout.requested` - New payout request
- `system.alert` - System alert
- `user.login` - User login event