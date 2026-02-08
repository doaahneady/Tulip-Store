# Fix Admin Login Issue
## "The provided credentials are incorrect" - Solution Guide

---

## 🔍 Problem

When trying to login with `admin@tulipstore.com` / `password123`, you get:
```
The provided credentials are incorrect.
```

---

## ✅ Solution Steps

### Step 1: Check if Admin Employee Exists

Visit this URL to check if the admin employee exists:
```
http://127.0.0.1:8000/debug-admin-employee
```

This will show you:
- If the employee exists
- If the password is correct
- Employee status
- Role assignments

### Step 2: Create/Update Admin Employee

**Option A: Via Web Route (Easiest)**
```
http://127.0.0.1:8000/create-admin-employee
```

This will:
- Create the admin employee if it doesn't exist
- Update password to `password123` if it exists
- Set status to `active`
- Enable all dashboard roles

**Option B: Via Artisan Seeder**
```bash
php artisan db:seed --class=DashboardTestEmployeesSeeder
```

**Option C: Via Command Line Script**
```bash
php create_admin_employee.php
```

**Option D: Via Tinker (Advanced)**
```bash
php artisan tinker
```

Then run:
```php
$admin = App\Models\Employee::updateOrCreate(
    ['email' => 'admin@tulipstore.com'],
    [
        'employee_code' => 'SA001',
        'first_name' => 'Ahmed',
        'last_name' => 'Al-Manager',
        'email' => 'admin@tulipstore.com',
        'password' => bcrypt('password123'),
        'phone' => '+963-11-1234567',
        'department' => 'Administration',
        'position' => 'Chief Executive Officer',
        'employment_type' => 'full_time',
        'hire_date' => now(),
        'status' => 'active',
        'salary' => 150000.00,
        'city' => 'Damascus',
        'country' => 'Syria',
        'gender' => 'male',
        'email_verified_at' => now(),
        'is_admin' => true,
        'is_it' => true,
        'is_hr' => true,
        'is_finance' => true,
        'is_driver_supervisor' => true,
        'is_trader' => true,
    ]
);
```

---

## 🔧 Common Issues & Fixes

### Issue 1: Employee Doesn't Exist

**Symptom:** `debug-admin-employee` shows `exists: false`

**Fix:** Run `/create-admin-employee` route

---

### Issue 2: Password Doesn't Match

**Symptom:** `debug-admin-employee` shows `password_matches: false`

**Fix:** The password hash might be incorrect. Run `/create-admin-employee` to reset it.

---

### Issue 3: Employee Status is Not Active

**Symptom:** Login shows "Your account is not active"

**Fix:** Update employee status:
```php
$admin = App\Models\Employee::where('email', 'admin@tulipstore.com')->first();
$admin->status = 'active';
$admin->save();
```

---

### Issue 4: Email Not Verified

**Symptom:** Employee exists but login fails

**Fix:** Verify email:
```php
$admin = App\Models\Employee::where('email', 'admin@tulipstore.com')->first();
$admin->email_verified_at = now();
$admin->save();
```

---

## 🧪 Testing After Fix

1. **Check Employee Exists:**
   ```
   http://127.0.0.1:8000/debug-admin-employee
   ```

2. **Try Login:**
   ```
   URL: http://127.0.0.1:8000/employee/login
   Email: admin@tulipstore.com
   Password: password123
   ```

3. **Expected Result:**
   - Should redirect to dashboard selection page
   - Should see all 6 dashboards available

---

## 📋 Quick Fix Checklist

- [ ] Visit `/debug-admin-employee` to check status
- [ ] Visit `/create-admin-employee` to create/update admin
- [ ] Verify employee status is `active`
- [ ] Verify email is verified (`email_verified_at` is set)
- [ ] Verify password is `password123`
- [ ] Try login again

---

## 🔐 Correct Credentials

**Email:** `admin@tulipstore.com`  
**Password:** `password123`  
**Login URL:** `http://127.0.0.1:8000/employee/login`

---

## 🚨 Still Not Working?

If login still fails after following the steps above:

1. **Check Database Connection:**
   ```bash
   php artisan db:show
   ```

2. **Check Employee Table:**
   ```sql
   SELECT id, email, status, is_admin, email_verified_at 
   FROM employees 
   WHERE email = 'admin@tulipstore.com';
   ```

3. **Check Password Hash:**
   ```php
   // In tinker
   $admin = App\Models\Employee::where('email', 'admin@tulipstore.com')->first();
   Hash::check('password123', $admin->password); // Should return true
   ```

4. **Clear Cache:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   ```

5. **Check Laravel Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

---

## 📞 Quick Commands

**Create Admin Employee:**
```bash
# Via route
curl http://127.0.0.1:8000/create-admin-employee

# Via seeder
php artisan db:seed --class=DashboardTestEmployeesSeeder

# Via script
php create_admin_employee.php
```

**Debug Admin:**
```bash
curl http://127.0.0.1:8000/debug-admin-employee
```

---

## ✅ Success Indicators

After fixing, you should see:

1. `/debug-admin-employee` shows:
   - `exists: true`
   - `password_matches: true`
   - `status: "active"`
   - All roles enabled

2. Login succeeds and redirects to dashboard selection

3. Can access all 6 dashboards

---

**Last Updated:** {{ date('Y-m-d H:i:s') }}

