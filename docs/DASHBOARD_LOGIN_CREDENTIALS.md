# Dashboard Login Credentials
## Complete Login Information for All Dashboards

**Last Updated:** {{ date('Y-m-d H:i:s') }}

---

## 🔐 Login URL

**Employee Login Page:**
```
http://127.0.0.1:8000/employee/login
```

**Alternative URL:**
```
http://127.0.0.1:8000/staff
```

---

## 📋 Quick Reference Table

| Dashboard | Email | Password | Employee Code | Access Level |
|-----------|-------|----------|---------------|--------------|
| **Super Admin** | `admin@tulipstore.com` | `password123` | SA001 | All 6 Dashboards |
| **IT/DevOps** | `it@tulipstore.com` | `password123` | IT001 | IT Dashboard Only |
| **HR** | `hr@tulipstore.com` | `password123` | HR001 | HR Dashboard Only |
| **Finance** | `finance@tulipstore.com` | `password123` | FIN001 | Finance Dashboard Only |
| **Driver Supervisor** | `supervisor@tulipstore.com` | `password123` | SUP001 | Supervisor Dashboard Only |
| **Vendor/Store Owner** | `vendor@tulipstore.com` | `password123` | VEN001 | Vendor Dashboard Only |

**Default Password for ALL users:** `password123`

---

## 👥 Detailed Login Information

### 1. Super Admin Dashboard (All Access)

**Credentials:**
- **Email:** `admin@tulipstore.com`
- **Password:** `password123`
- **Employee Code:** SA001
- **Name:** أحمد المدير العام (Ahmed Al-Mudir Al-Aam)

**Access:**
- ✅ Super Admin Dashboard
- ✅ IT/DevOps Dashboard
- ✅ HR Dashboard
- ✅ Finance Dashboard
- ✅ Driver Supervisor Dashboard
- ✅ Vendor Dashboard

**Features:**
- Full system access
- Dashboard switcher (can switch between all dashboards)
- User management
- Role & permission management
- System-wide analytics
- Emergency overrides
- Audit log access
- **Downloads & Exports** (NEW!)

**Dashboard URL:** `http://127.0.0.1:8000/dashboard/admin`

---

### 2. IT/DevOps Dashboard

**Credentials:**
- **Email:** `it@tulipstore.com`
- **Password:** `password123`
- **Employee Code:** IT001
- **Name:** محمد الشبكة (Mohammed Al-Shabaka)

**Access:**
- ✅ IT/DevOps Dashboard Only

**Features:**
- System health monitoring
- API error tracking
- Database performance monitoring
- System logs viewing
- Database backup management
- Deployment tracking
- System alerts management
- Resource monitoring
- Security audit logs

**Dashboard URL:** `http://127.0.0.1:8000/dashboard/it`

---

### 3. HR Dashboard

**Credentials:**
- **Email:** `hr@tulipstore.com`
- **Password:** `password123`
- **Employee Code:** HR001
- **Name:** فاطمة الموارد البشرية (Fatima Al-Mawarid Al-Bashariya)

**Access:**
- ✅ HR Dashboard Only

**Features:**
- Employee management
- Shift scheduling
- Attendance tracking
- Leave request management
- Payroll calculation
- Payroll submission to finance
- Performance reviews
- Recruiting & job applications
- Training records
- Employee onboarding
- Engagement surveys
- Leave balance management

**Dashboard URL:** `http://127.0.0.1:8000/dashboard/hr`

---

### 4. Finance Dashboard

**Credentials:**
- **Email:** `finance@tulipstore.com`
- **Password:** `password123`
- **Employee Code:** FIN001
- **Name:** خالد المالي (Khalid Al-Mali)

**Access:**
- ✅ Finance Dashboard Only

**Features:**
- Financial transaction management
- Transaction approval workflow
- Payout management (approve/process/reject)
- Revenue analytics
- Expense management
- Financial reports (P&L, Cash Flow)
- Tax management
- Payroll processing (from HR)
- Budget management
- Financial forecasts

**Dashboard URL:** `http://127.0.0.1:8000/dashboard/finance`

---

### 5. Driver Supervisor Dashboard

**Credentials:**
- **Email:** `supervisor@tulipstore.com`
- **Password:** `password123`
- **Employee Code:** SUP001
- **Name:** علي التوصيل (Ali Al-Tawseel)

**Access:**
- ✅ Driver Supervisor Dashboard Only

**Features:**
- Live driver tracking (GPS)
- Driver management
- Order-to-driver assignment
- Delivery status tracking
- Route optimization
- Vehicle maintenance tracking
- Delivery proof review
- Driver performance evaluation
- Zone analytics

**Dashboard URL:** `http://127.0.0.1:8000/dashboard/supervisor`

---

### 6. Vendor/Product Owner Dashboard

**Credentials:**
- **Email:** `vendor@tulipstore.com`
- **Password:** `password123`
- **Employee Code:** VEN001
- **Name:** سارة التاجر (Sara Al-Tajir)

**Access:**
- ✅ Vendor Dashboard Only

**Features:**
- Product management
- Inventory management
- Stock level monitoring
- Order management (store-specific)
- Sales analytics
- Earnings tracking
- Payout requests
- Store profile management
- Inventory alerts
- Sales forecasts
- Product performance metrics

**Dashboard URL:** `http://127.0.0.1:8000/dashboard/vendor`

---

## 🚀 Quick Setup

### ⚠️ IMPORTANT: If Login Fails

If you get "The provided credentials are incorrect" error:

1. **Create Admin Employee First:**
   ```
   http://127.0.0.1:8000/create-admin-employee
   ```

2. **Debug Admin Status:**
   ```
   http://127.0.0.1:8000/debug-admin-employee
   ```

3. **See Fix Guide:**
   - `docs/FIX_ADMIN_LOGIN.md` - Complete troubleshooting guide

### Option 1: Create Admin Employee (Recommended First Step)

Visit this URL to create/update the admin employee:
```
http://127.0.0.1:8000/create-admin-employee
```

### Option 2: Create All Test Employees via Route

Visit this URL to automatically create all test employees:
```
http://127.0.0.1:8000/create-test-employees
```

### Option 3: Use Database Seeder

Run the following command:
```bash
php artisan db:seed --class=DashboardTestEmployeesSeeder
```

### Option 4: Manual Creation

If employees don't exist, you can create them manually through the admin dashboard or database.

---

## 🔗 Direct Dashboard URLs

After logging in, you can access dashboards directly:

| Dashboard | Direct URL |
|-----------|------------|
| Super Admin | `http://127.0.0.1:8000/dashboard/admin` |
| IT/DevOps | `http://127.0.0.1:8000/dashboard/it` |
| HR | `http://127.0.0.1:8000/dashboard/hr` |
| Finance | `http://127.0.0.1:8000/dashboard/finance` |
| Driver Supervisor | `http://127.0.0.1:8000/dashboard/supervisor` |
| Vendor | `http://127.0.0.1:8000/dashboard/vendor` |

**Note:** You must be logged in and have the appropriate role to access these URLs.

---

## 📥 Admin Downloads Access

**Super Admin** can access downloads page:
- **URL:** `http://127.0.0.1:8000/dashboard/admin/downloads`
- **From Login Page:** Click "تحميل البيانات (Admin)" link (visible only to admins)

**Available Downloads:**
- Users Export (CSV/PDF)
- Orders Export (CSV/PDF)
- Financial Transactions Export (CSV/PDF)
- Products Export (CSV/PDF)
- Employees Export (CSV/PDF)
- Stores Export (CSV/PDF)
- Audit Logs Export (CSV/PDF)
- Complete System Report (CSV/PDF)

---

## 🧪 Testing Checklist

### Super Admin Testing
- [ ] Login with `admin@tulipstore.com` / `password123`
- [ ] Verify dashboard selection page appears
- [ ] Test switching between all 6 dashboards
- [ ] Test user management
- [ ] Test role assignment
- [ ] Test downloads & exports
- [ ] Test audit log access
- [ ] Test emergency overrides

### IT Dashboard Testing
- [ ] Login with `it@tulipstore.com` / `password123`
- [ ] Verify direct redirect to IT dashboard
- [ ] Test system health monitoring
- [ ] View system logs
- [ ] Test backup creation
- [ ] View API errors
- [ ] Test alert management

### HR Dashboard Testing
- [ ] Login with `hr@tulipstore.com` / `password123`
- [ ] Verify direct redirect to HR dashboard
- [ ] Test employee management
- [ ] Test shift scheduling
- [ ] Test payroll calculation
- [ ] Test leave request approval
- [ ] View performance reviews

### Finance Dashboard Testing
- [ ] Login with `finance@tulipstore.com` / `password123`
- [ ] Verify direct redirect to Finance dashboard
- [ ] Test transaction approval
- [ ] Test payout processing
- [ ] View financial reports
- [ ] Test payroll processing (from HR)
- [ ] View revenue analytics

### Driver Supervisor Testing
- [ ] Login with `supervisor@tulipstore.com` / `password123`
- [ ] Verify direct redirect to Supervisor dashboard
- [ ] Test driver assignment
- [ ] View live driver tracking
- [ ] Test route optimization
- [ ] View delivery status
- [ ] Test vehicle maintenance

### Vendor Dashboard Testing
- [ ] Login with `vendor@tulipstore.com` / `password123`
- [ ] Verify direct redirect to Vendor dashboard
- [ ] Test product management
- [ ] Test inventory updates
- [ ] View sales analytics
- [ ] Test payout request
- [ ] View earnings

---

## 🔒 Security Notes

⚠️ **IMPORTANT - PRODUCTION DEPLOYMENT:**

1. **Change All Passwords** - These are test credentials only
2. **Use Strong Passwords** - Minimum 12 characters, mixed case, numbers, symbols
3. **Enable 2FA** - Two-factor authentication for admin accounts
4. **Review Permissions** - Regularly audit employee access
5. **Remove Test Accounts** - Delete test employees before production
6. **Use Environment Variables** - Store credentials securely
7. **Implement Password Policy** - Enforce password complexity rules
8. **Regular Security Audits** - Review audit logs regularly

---

## 📞 Support

If you encounter login issues:

1. **Check Employee Status** - Ensure employee status is `active`
2. **Verify Email** - Email must match exactly (case-sensitive)
3. **Check Password** - Default is `password123`
4. **Database Connection** - Verify database is accessible
5. **Session Issues** - Clear browser cache and cookies
6. **Check Logs** - Review Laravel logs for errors

**Test System Status:** `http://127.0.0.1:8000/test-employee-system`
**Auth Status Check:** `http://127.0.0.1:8000/test-auth`

---

## 📝 Additional Test Users

### Multi-Role Employee (HR + Finance)
- **Email:** `multi@tulipstore.com`
- **Password:** `password123`
- **Access:** HR Dashboard + Finance Dashboard
- **Use Case:** Test dashboard selection for multi-role employees

---

## 🎯 Quick Login Commands

Copy-paste ready login URLs:

```
Super Admin:    http://127.0.0.1:8000/employee/login
                Email: admin@tulipstore.com
                Password: password123

IT Dashboard:   http://127.0.0.1:8000/employee/login
                Email: it@tulipstore.com
                Password: password123

HR Dashboard:   http://127.0.0.1:8000/employee/login
                Email: hr@tulipstore.com
                Password: password123

Finance:        http://127.0.0.1:8000/employee/login
                Email: finance@tulipstore.com
                Password: password123

Supervisor:     http://127.0.0.1:8000/employee/login
                Email: supervisor@tulipstore.com
                Password: password123

Vendor:         http://127.0.0.1:8000/employee/login
                Email: vendor@tulipstore.com
                Password: password123
```

---

**Last Updated:** {{ date('Y-m-d H:i:s') }}
**System Version:** Production Ready
**Status:** ✅ All Dashboards Operational

