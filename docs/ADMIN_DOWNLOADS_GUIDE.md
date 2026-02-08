# Admin Downloads & Exports Guide

## Overview

Admins can now download system data in CSV or PDF format directly from the employee login area or admin dashboard.

## Access Points

### 1. From Employee Login Page
- URL: `http://127.0.0.1:8000/employee/login`
- If you're logged in as an admin, you'll see a "تحميل البيانات (Admin)" link
- Click it to access the downloads page

### 2. Direct Access (After Login)
- URL: `http://127.0.0.1:8000/dashboard/admin/downloads`
- Requires admin authentication

## Available Downloads

### 1. Users Export
- **CSV Format**: Best for Excel/Google Sheets analysis
- **PDF Format**: Best for printing and sharing
- **Includes**: User ID, Name, Email, Phone, Status, Roles, Created Date, Email Verification Status
- **Filters Available**: Status, Date Range

**URL Examples:**
- CSV: `/dashboard/admin/export/users?format=csv`
- PDF: `/dashboard/admin/export/users?format=pdf`
- With Filters: `/dashboard/admin/export/users?format=csv&status=active&date_from=2024-01-01`

### 2. Orders Export
- **Includes**: Order Number, Customer Info, Store, Status, Payment Status, Amounts, Payment Method, Dates
- **Filters Available**: Status, Payment Status, Date Range

**URL Examples:**
- CSV: `/dashboard/admin/export/orders?format=csv`
- PDF: `/dashboard/admin/export/orders?format=pdf`
- With Filters: `/dashboard/admin/export/orders?format=csv&status=delivered&payment_status=paid`

### 3. Financial Transactions Export
- **Includes**: Transaction ID, Type, Status, Approval Status, Amount, Currency, User, Order, Store, Description, Dates
- **Filters Available**: Type, Status, Approval Status, Date Range

**URL Examples:**
- CSV: `/dashboard/admin/export/financial-transactions?format=csv`
- PDF: `/dashboard/admin/export/financial-transactions?format=pdf`
- With Filters: `/dashboard/admin/export/financial-transactions?format=csv&type=order_payment&status=completed`

### 4. Products Export
- **Includes**: Product ID, Name, SKU, Store, Category, Prices, Stock Quantity, Low Stock Threshold, Active Status, Featured Status
- **Filters Available**: Store ID, Active Status, Low Stock Alert

**URL Examples:**
- CSV: `/dashboard/admin/export/products?format=csv`
- PDF: `/dashboard/admin/export/products?format=pdf`
- Low Stock Only: `/dashboard/admin/export/products?format=csv&low_stock=1`

### 5. Employees Export
- **Includes**: Employee Code, Name, Email, Phone, Department, Position, Employment Type, Status, Hire Date, Salary
- **Filters Available**: Department, Status

**URL Examples:**
- CSV: `/dashboard/admin/export/employees?format=csv`
- PDF: `/dashboard/admin/export/employees?format=pdf`
- By Department: `/dashboard/admin/export/employees?format=csv&department=IT`

### 6. Stores Export
- **Includes**: Store ID, Name, Slug, Owner, Organization, Status, Commission Rate, Created Date
- **Filters Available**: Status

**URL Examples:**
- CSV: `/dashboard/admin/export/stores?format=csv`
- PDF: `/dashboard/admin/export/stores?format=pdf`
- Active Only: `/dashboard/admin/export/stores?format=csv&status=active`

### 7. Audit Logs Export
- **Includes**: Log ID, User Info, Action, Model Type, Model ID, IP Address, Timestamp
- **Filters Available**: Action, Model Type, User ID, Date Range

**URL Examples:**
- CSV: `/dashboard/admin/audit-logs/export?format=csv`
- PDF: `/dashboard/admin/audit-logs/export?format=pdf`
- By Action: `/dashboard/admin/audit-logs/export?format=csv&action=user_created`

### 8. Complete System Report
- **Includes**: System Overview, Financial Summary, Platform Metrics
- **Formats**: CSV (summary) or PDF (comprehensive report)

**URL Examples:**
- CSV: `/dashboard/admin/export/system-report?format=csv`
- PDF: `/dashboard/admin/export/system-report?format=pdf`

## Route Reference

All routes are prefixed with `/dashboard/admin`:

| Route Name | URL | Method |
|------------|-----|--------|
| `dashboard.admin.downloads` | `/dashboard/admin/downloads` | GET |
| `dashboard.admin.export.users` | `/dashboard/admin/export/users` | GET |
| `dashboard.admin.export.orders` | `/dashboard/admin/export/orders` | GET |
| `dashboard.admin.export.financial-transactions` | `/dashboard/admin/export/financial-transactions` | GET |
| `dashboard.admin.export.products` | `/dashboard/admin/export/products` | GET |
| `dashboard.admin.export.employees` | `/dashboard/admin/export/employees` | GET |
| `dashboard.admin.export.stores` | `/dashboard/admin/export/stores` | GET |
| `dashboard.admin.audit-logs.export` | `/dashboard/admin/audit-logs/export` | GET |
| `dashboard.admin.export.system-report` | `/dashboard/admin/export/system-report` | GET |

## Query Parameters

### Common Parameters
- `format`: `csv` or `pdf` (default: `csv`)
- `date_from`: Start date (format: `YYYY-MM-DD`)
- `date_to`: End date (format: `YYYY-MM-DD`)

### Specific Parameters

**Users:**
- `status`: `active`, `inactive`, `suspended`

**Orders:**
- `status`: `pending`, `confirmed`, `processing`, `out_for_delivery`, `delivered`, `cancelled`, `refunded`
- `payment_status`: `pending`, `paid`, `failed`, `refunded`, `partial`

**Financial Transactions:**
- `type`: `order_payment`, `commission`, `payout`, `refund`, `fee`, `adjustment`, `payroll`, `expense`
- `status`: `pending`, `processing`, `completed`, `failed`, `cancelled`
- `approval_status`: `pending`, `approved`, `rejected`

**Products:**
- `store_id`: Store ID (integer)
- `is_active`: `1` or `0`
- `low_stock`: `1` to filter low stock items

**Employees:**
- `department`: Department name
- `status`: `active`, `inactive`, `terminated`, `on_leave`

**Stores:**
- `status`: `active`, `pending`, `suspended`, `closed`

**Audit Logs:**
- `action`: Action name (e.g., `user_created`, `order_updated`)
- `model_type`: Model class name
- `user_id`: User ID (integer)

## Usage Examples

### Example 1: Export All Active Users to CSV
```
GET /dashboard/admin/export/users?format=csv&status=active
```

### Example 2: Export Orders from Last Month to PDF
```
GET /dashboard/admin/export/orders?format=pdf&date_from=2024-01-01&date_to=2024-01-31
```

### Example 3: Export Completed Financial Transactions
```
GET /dashboard/admin/export/financial-transactions?format=csv&status=completed&type=order_payment
```

### Example 4: Export Low Stock Products
```
GET /dashboard/admin/export/products?format=csv&low_stock=1
```

### Example 5: Export System Report
```
GET /dashboard/admin/export/system-report?format=pdf
```

## Features

✅ **Real-time Data**: All exports use live production data
✅ **Filtering**: Filter exports by various criteria
✅ **Multiple Formats**: CSV for analysis, PDF for reports
✅ **Audit Trail**: All exports are logged in audit logs
✅ **Role-Based**: Only admins can access downloads
✅ **Performance**: Large exports are handled efficiently

## Security

- All download routes require admin authentication (`auth:employee` middleware)
- Only users with `is_admin = true` can access downloads
- All exports are logged in audit logs for compliance
- No sensitive data is exposed in URLs (filters are validated)

## Troubleshooting

### Issue: "Unauthorized" Error
**Solution**: Ensure you're logged in as an admin employee

### Issue: Export Takes Too Long
**Solution**: 
- Use date filters to limit data range
- For very large datasets, consider using CSV format
- Check server resources

### Issue: Missing Data in Export
**Solution**: 
- Check if filters are too restrictive
- Verify data exists in database
- Check date range filters

### Issue: PDF Not Generating
**Solution**:
- Ensure DomPDF package is installed: `composer require barryvdh/laravel-dompdf`
- Check server has write permissions
- Verify template file exists: `resources/views/dashboard/exports/pdf-template.blade.php`

## Notes

- CSV files include UTF-8 BOM for Excel compatibility
- PDF reports are formatted for A4 landscape printing
- Large exports (>1000 rows) may take longer to generate
- All timestamps are in server timezone
- Currency amounts are formatted with 2 decimal places

