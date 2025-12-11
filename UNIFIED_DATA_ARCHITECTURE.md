# 🗄️ Unified Data Architecture - Single Source of Truth

## 🎯 Principle: One Database, Multiple Views

**All sections (Admin, Finance, Delivery, HR) access THE SAME data.**
- No data duplication
- Real-time synchronization
- Single source of truth
- Consistent across all dashboards

---

## 📊 Shared Database Tables

### Core Tables (Used by ALL sections)

#### 1. **users** (Central User Table)
```sql
- id
- name, email, phone
- role (customer, trader, admin, worker, driver, accountant, supervisor)
- is_admin, is_trader, is_driver_supervisor, is_accountant, etc.
- status (active, inactive, suspended)
- created_at, updated_at
```

**Used by:**
- ✅ Admin: Manage all users
- ✅ Finance: Track user transactions
- ✅ Delivery: Assign to drivers
- ✅ HR: Employee records

---

#### 2. **orders** (Central Orders Table)
```sql
- id
- user_id (customer)
- trader_id (seller)
- total_amount
- status (pending, confirmed, out_for_delivery, delivered, cancelled)
- payment_status (pending, paid, refunded)
- payment_method
- delivery_address
- created_at, updated_at
```

**Used by:**
- ✅ Admin: View all orders
- ✅ Finance: Revenue tracking
- ✅ Delivery: Assign to drivers
- ✅ Trader: Their sales

---

#### 3. **drivers** (Central Drivers Table)
```sql
- id
- name, phone, email
- license_number
- vehicle_type, vehicle_plate
- status (available, busy, offline, on_break)
- current_latitude, current_longitude
- total_deliveries
- rating
- is_active
```

**Used by:**
- ✅ Admin: Manage drivers
- ✅ Finance: Calculate payments
- ✅ Delivery Supervisor: Assign trips, track location
- ✅ HR: Employee records (if driver is employee)

---

#### 4. **trips** (Central Trips/Deliveries Table)
```sql
- id
- order_id → orders table
- driver_id → drivers table
- pickup_address, delivery_address
- distance, estimated_time, actual_time
- status (pending, in_transit, delivered, cancelled)
- payment_amount (driver payment)
- payment_status (pending, paid)
- assigned_at, delivered_at
```

**Used by:**
- ✅ Admin: Monitor all deliveries
- ✅ Finance: Driver payments, delivery costs
- ✅ Delivery Supervisor: Assign and track
- ✅ Driver: View their trips

---

#### 5. **financial_transactions** (Central Finance Table)
```sql
- id
- type (income, expense)
- category (order_payment, driver_payment, worker_salary, trader_commission)
- amount
- related_type (Order, Trip, Worker, Trader)
- related_id
- user_id (who it's for/from)
- description
- payment_method
- status (pending, completed, cancelled)
- transaction_date
```

**Used by:**
- ✅ Admin: Financial overview
- ✅ Finance: All transactions
- ✅ Delivery: Driver payments
- ✅ HR: Worker salaries
- ✅ Trader: Commission tracking

---

#### 6. **workers** (Central Workers/Employees Table)
```sql
- id
- user_id → users table (optional, if they have login)
- name, email, phone
- position, department
- hire_date
- salary
- payment_frequency (monthly, weekly, daily)
- status (active, inactive, on_leave)
- bank_account
```

**Used by:**
- ✅ Admin: All employees
- ✅ Finance: Payroll
- ✅ HR: Employee management
- ✅ Delivery: If driver is also worker

---

#### 7. **trader_accounts** (Central Trader Finance)
```sql
- id
- user_id → users table (trader)
- total_sales
- total_commission
- commission_rate (%)
- pending_payment
- paid_amount
- last_payment_date
```

**Used by:**
- ✅ Admin: Trader performance
- ✅ Finance: Commission payments
- ✅ Trader: Their earnings

---

## 🔗 How Data Flows Between Sections

### Example 1: Order → Delivery → Payment

```
1. Customer places order
   ├─ orders table: new record
   ├─ financial_transactions: income (pending)
   
2. Admin sees order
   ├─ Reads from: orders table
   ├─ Can update: status, notes
   
3. Supervisor assigns driver
   ├─ Creates: trip record
   ├─ Updates: order.status = 'out_for_delivery'
   ├─ Updates: driver.status = 'busy'
   
4. Driver delivers
   ├─ Updates: trip.status = 'delivered'
   ├─ Updates: order.status = 'delivered'
   ├─ Updates: driver.status = 'available'
   ├─ Calculates: trip.payment_amount
   
5. Finance processes payment
   ├─ Creates: financial_transactions (expense - driver payment)
   ├─ Updates: trip.payment_status = 'paid'
   ├─ Updates: financial_transactions.status = 'completed'
   
6. Admin sees complete flow
   ├─ Reads: orders, trips, financial_transactions
   ├─ Views: Complete order lifecycle
```

**Result: Everyone sees the SAME data at each step!**

---

### Example 2: Worker Salary

```
1. HR adds worker
   ├─ workers table: new record
   ├─ May create: users record (if needs login)
   
2. HR tracks attendance
   ├─ worker_attendance table: daily records
   
3. Finance calculates salary
   ├─ Reads: workers, worker_attendance
   ├─ Calculates: total salary
   ├─ Creates: financial_transactions (expense - salary)
   
4. Finance pays worker
   ├─ Updates: financial_transactions.status = 'completed'
   ├─ Records: payment_date
   
5. Admin sees payroll
   ├─ Reads: workers, financial_transactions
   ├─ Views: All salary payments
```

---

## 📱 Dashboard Views (Same Data, Different Filters)

### Admin Dashboard
```php
// Sees EVERYTHING
$allOrders = Order::all();
$allUsers = User::all();
$allTransactions = FinancialTransaction::all();
$allTrips = Trip::all();
$allWorkers = Worker::all();
```

### Finance Dashboard
```php
// Sees ALL financial data
$allTransactions = FinancialTransaction::all();
$pendingPayments = FinancialTransaction::where('status', 'pending')->get();
$driverPayments = Trip::where('payment_status', 'pending')->get();
$workerSalaries = Worker::with('transactions')->get();
```

### Delivery Supervisor Dashboard
```php
// Sees delivery-related data
$pendingOrders = Order::where('status', 'confirmed')->get();
$activeTrips = Trip::whereIn('status', ['pending', 'in_transit'])->get();
$availableDrivers = Driver::where('status', 'available')->get();
$allDrivers = Driver::all(); // For management
```

### Trader Dashboard
```php
// Sees THEIR data only
$myOrders = Order::where('trader_id', auth()->id())->get();
$myEarnings = TraderAccount::where('user_id', auth()->id())->first();
$myProducts = Product::where('trader_id', auth()->id())->get();
```

---

## 🔄 Real-Time Synchronization

### When ANY section updates data, ALL sections see it immediately:

#### Example: Supervisor assigns driver to order
```php
// Supervisor Dashboard
$trip = Trip::create([
    'order_id' => $orderId,
    'driver_id' => $driverId,
    'status' => 'pending',
]);

// This IMMEDIATELY affects:
✅ Admin Dashboard: Sees new trip
✅ Finance Dashboard: Sees pending driver payment
✅ Driver: Sees new assignment
✅ Customer: Order status updates
✅ Trader: Their order is being delivered
```

#### Example: Finance marks payment as paid
```php
// Finance Dashboard
$trip->update(['payment_status' => 'paid']);

// This IMMEDIATELY affects:
✅ Admin Dashboard: Payment status updated
✅ Delivery Supervisor: Sees driver was paid
✅ Driver: Sees payment received
✅ Financial Reports: Updated totals
```

---

## 🎯 Implementation Strategy

### 1. **Shared Models** (Already have most)
```
✅ User.php
✅ Order.php
✅ Driver.php
✅ Trip.php (just created)
🆕 Worker.php (need to create)
🆕 FinancialTransaction.php (need to create)
🆕 TraderAccount.php (need to create)
```

### 2. **Shared Relationships**
```php
// User model
public function orders() // as customer
public function traderOrders() // as trader
public function driverProfile() // if driver
public function workerProfile() // if worker
public function transactions() // financial

// Order model
public function customer() // User
public function trader() // User
public function trip() // Trip
public function transactions() // Financial

// Driver model
public function trips()
public function payments() // Financial transactions

// Trip model
public function order()
public function driver()
public function payment() // Financial transaction
```

### 3. **Shared Controllers/Services**
```
✅ OrderController (used by all)
✅ DriverController (used by supervisor, admin, finance)
🆕 FinancialTransactionController (used by all)
🆕 WorkerController (used by HR, admin, finance)
```

---

## 📊 Dashboard Data Sources

### Admin Dashboard Queries:
```php
// Overview
$totalUsers = User::count();
$totalOrders = Order::count();
$totalRevenue = FinancialTransaction::where('type', 'income')->sum('amount');
$totalExpenses = FinancialTransaction::where('type', 'expense')->sum('amount');

// Recent activity
$recentOrders = Order::latest()->take(10)->get();
$recentTrips = Trip::latest()->take(10)->get();
$recentTransactions = FinancialTransaction::latest()->take(10)->get();

// All users by role
$customers = User::where('role', 'customer')->get();
$traders = User::where('role', 'trader')->get();
$drivers = Driver::all();
$workers = Worker::all();
```

### Finance Dashboard Queries:
```php
// Financial overview
$totalIncome = FinancialTransaction::where('type', 'income')->sum('amount');
$totalExpenses = FinancialTransaction::where('type', 'expense')->sum('amount');
$netProfit = $totalIncome - $totalExpenses;

// Pending payments
$pendingDriverPayments = Trip::where('payment_status', 'pending')->sum('payment_amount');
$pendingWorkerSalaries = Worker::where('payment_status', 'pending')->sum('salary');

// By category
$orderRevenue = FinancialTransaction::where('category', 'order_payment')->sum('amount');
$driverExpenses = FinancialTransaction::where('category', 'driver_payment')->sum('amount');
$workerExpenses = FinancialTransaction::where('category', 'worker_salary')->sum('amount');
```

### Delivery Supervisor Queries:
```php
// Delivery overview
$pendingOrders = Order::where('status', 'confirmed')->count();
$activeTrips = Trip::whereIn('status', ['pending', 'in_transit'])->count();
$completedToday = Trip::where('status', 'delivered')->whereDate('delivered_at', today())->count();

// Drivers
$availableDrivers = Driver::where('status', 'available')->count();
$busyDrivers = Driver::where('status', 'busy')->count();
$allDrivers = Driver::with('trips')->get();

// Pending assignments
$ordersNeedingDriver = Order::where('status', 'confirmed')->whereDoesntHave('trip')->get();
```

---

## ✅ Benefits of Unified Data

### 1. **Consistency**
- Everyone sees the same numbers
- No conflicting data
- Single source of truth

### 2. **Real-Time**
- Updates are immediate
- No sync delays
- Live data everywhere

### 3. **Efficiency**
- No data duplication
- Easier maintenance
- Faster queries

### 4. **Accuracy**
- Financial reports are accurate
- No manual reconciliation
- Audit trail is clear

### 5. **Scalability**
- Easy to add new sections
- New features use existing data
- No migration needed

---

## 🚀 Next Steps

### To ensure unified data:

1. ✅ Use same database tables
2. ✅ Share models across sections
3. ✅ Use relationships properly
4. ✅ Update data in one place
5. ✅ Read from same source
6. ✅ No data duplication
7. ✅ Consistent status updates

### Implementation:
- All dashboards query same tables
- All updates go to same tables
- All relationships are defined
- All sections see real-time data

---

**Result: Perfect data synchronization across all sections!** ✨

Everyone sees the same data, updated in real-time, from a single source of truth.
