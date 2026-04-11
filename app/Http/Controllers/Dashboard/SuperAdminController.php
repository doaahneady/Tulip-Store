<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeDashboardPermission;
use App\Models\EmployeeDashboardOverride;
use App\Models\FinancialTransaction;
use App\Models\Gift;
use App\Models\GiftBox;
use App\Models\GiftCard;
use App\Models\GiftFiller;
use App\Models\GiftRibbon;
use App\Models\GiftWrapping;
use App\Models\InventoryAlert;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\PayrollRecord;
use App\Models\Permission;
use App\Models\DashboardRolePermission;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Review;
use App\Models\Role;
use App\Models\SecurityAuditLog;
use App\Models\Store;
use App\Models\SupportTicket;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\Wishlist;
use App\Services\Dashboard\CSDashboardService;
use App\Services\Dashboard\DeliveryDashboardService;
use App\Services\Dashboard\ExportService;
use App\Services\Dashboard\FinanceDashboardService;
use App\Services\Dashboard\HRDashboardService;
use App\Services\DashboardPermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Super Admin Dashboard - God Mode
     */
    public function index()
    {
        $metrics = $this->getGlobalMetrics();

        return view('dashboards.super-admin.index', compact('metrics'));
    }

    public function styleGuide()
    {
        return view('dashboards.super-admin.style-guide');
    }

    /**
     * Cross-department KPIs
     */
    public function crossDepartmentKPIs()
    {
        $kpis = $this->getCrossDepartmentKPIs();

        return view('dashboards.super-admin.cross-department-kpis', compact('kpis'));
    }

    /**
     * All Orders Overview with filters and manual override
     */
    public function orders(Request $request)
    {
        $orders = Order::with(['user', 'store'])
            ->when($request->search, function ($q, $search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->when($request->status, function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($request->payment_status, function ($q, $status) {
                $q->where('payment_status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        $statusOptions = ['pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered', 'done', 'cancelled', 'failed', 'refunded'];
        $paymentOptions = ['unpaid', 'paid', 'refunded', 'failed'];

        return view('dashboards.super-admin.orders', compact('orders', 'statusOptions', 'paymentOptions'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
            'payment_status' => 'nullable|string',
        ]);

        $statusManager = app(\App\Services\OrderStatusManager::class);
        $current = $statusManager->normalize((string) ($order->status ?? 'pending'));
        $next = $statusManager->normalize((string) $request->status);
        $canonical = (array) config('order_statuses.canonical', []);
        if (! in_array($next, $canonical, true)) {
            return back()->with('error', 'Invalid status');
        }
        if ($current !== $next && ! \App\Services\StatusTransitionService::canTransition('order', $current, $next, true)) {
            return back()->with('error', 'Invalid transition');
        }

        $old = $order->only(['status', 'payment_status']);
        if ($current !== $next) {
            \App\Services\StatusTransitionService::transition($order, 'status', $next, auth('employee')->id(), true);
        }
        if ($request->payment_status !== null && $request->payment_status !== $order->payment_status) {
            $order->update(['payment_status' => $request->payment_status]);
        }

        Cache::flush();

        AuditLog::create([
            'user_id' => auth('employee')->id(),
            'action' => 'order_status_update',
            'model_type' => 'Order',
            'model_id' => $order->id,
            'old_values' => $old,
            'new_values' => $order->only(['status', 'payment_status']),
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Order status updated');
    }

    public function overrideOrderAssignment(Request $request, Order $order)
    {
        $request->validate([
            'driver_id' => 'nullable|integer',
        ]);

        $newUserId = $request->filled('driver_id')
            ? Order::resolveAssignedDriverUserId((int) $request->driver_id)
            : null;

        $oldDriver = $order->assigned_driver_id;
        $order->update(['assigned_driver_id' => $newUserId]);

        Cache::flush();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'order_assignment_override',
            'model_type' => 'Order',
            'model_id' => $order->id,
            'old_values' => ['assigned_driver_id' => $oldDriver],
            'new_values' => ['assigned_driver_id' => $newUserId],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Order assignment updated');
    }

    /**
     * Admin Approvals (Finance, HR, Critical)
     */
    public function approvals()
    {
        $financialTransactions = FinancialTransaction::where('status', 'pending_approval')
            ->orderBy('created_at', 'desc')->paginate(20);
        $payouts = \App\Models\Payout::where('status', 'pending')->orderBy('created_at', 'desc')->paginate(20);
        $leaveRequests = \App\Models\LeaveRequest::where('status', 'pending')->orderBy('created_at', 'desc')->paginate(20);

        return view('dashboards.super-admin.approvals', compact('financialTransactions', 'payouts', 'leaveRequests'));
    }

    public function approveFinancialTransaction(FinancialTransaction $transaction)
    {
        $transaction->update(['status' => 'approved', 'approved_at' => now()]);
        Cache::flush();
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'finance_transaction_approve',
            'model_type' => 'FinancialTransaction',
            'model_id' => $transaction->id,
            'new_values' => ['status' => 'approved'],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Transaction approved');
    }

    public function rejectFinancialTransaction(FinancialTransaction $transaction)
    {
        $transaction->update(['status' => 'rejected']);
        Cache::flush();
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'finance_transaction_reject',
            'model_type' => 'FinancialTransaction',
            'model_id' => $transaction->id,
            'new_values' => ['status' => 'rejected'],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Transaction rejected');
    }

    public function approveLeave(\App\Models\LeaveRequest $leave)
    {
        $leave->update(['status' => 'approved', 'approved_at' => now()]);
        Cache::flush();
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'hr_leave_approve',
            'model_type' => 'LeaveRequest',
            'model_id' => $leave->id,
            'new_values' => ['status' => 'approved'],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Leave request approved');
    }

    public function rejectLeave(\App\Models\LeaveRequest $leave)
    {
        $leave->update(['status' => 'rejected']);
        Cache::flush();
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'hr_leave_reject',
            'model_type' => 'LeaveRequest',
            'model_id' => $leave->id,
            'new_values' => ['status' => 'rejected'],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Leave request rejected');
    }

    /**
     * Error & Failure Alerts (from IT)
     */
    public function alerts(Request $request)
    {
        $alerts = \App\Models\SystemAlert::when($request->severity, function ($q, $sev) {
            $q->where('severity', $sev);
        })
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('dashboards.super-admin.alerts', compact('alerts'));
    }

    /**
     * Manual reassignment (Orders, Tickets)
     */
    public function reassignment()
    {
        $orders = Order::whereIn('status', ['confirmed', 'processing', 'ready'])
            ->whereNull('assigned_driver_id')
            ->orderBy('created_at', 'desc')->paginate(15);
        $drivers = \App\Models\Driver::where('status', 'active')->orderBy('name')->get();
        $tickets = \App\Models\SupportTicket::whereIn('status', ['open', 'pending'])->orderBy('created_at', 'desc')->paginate(15);
        $agents = \App\Services\Dashboard\CSDashboardService::class;
        $csAgents = app(CSDashboardService::class)->getCSAgents();

        return view('dashboards.super-admin.reassignment', compact('orders', 'drivers', 'tickets', 'csAgents'));
    }

    public function reassignOrder(Request $request, Order $order)
    {
        $request->validate(['driver_id' => 'required|integer']);
        $newUserId = Order::resolveAssignedDriverUserId((int) $request->driver_id);
        if ($newUserId === null) {
            return back()->with('error', 'Invalid driver selection');
        }
        $old = $order->assigned_driver_id;
        $order->update(['assigned_driver_id' => $newUserId]);
        Cache::flush();
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'order_reassign_driver',
            'model_type' => 'Order',
            'model_id' => $order->id,
            'old_values' => ['assigned_driver_id' => $old],
            'new_values' => ['assigned_driver_id' => $newUserId],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Order reassigned');
    }

    public function reassignTicket(Request $request, \App\Models\SupportTicket $ticket)
    {
        $request->validate(['agent_id' => 'required|integer']);
        $old = $ticket->assigned_to;
        $ticket->update(['assigned_to' => $request->agent_id]);
        Cache::flush();
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'ticket_reassign_agent',
            'model_type' => 'SupportTicket',
            'model_id' => $ticket->id,
            'old_values' => ['assigned_to' => $old],
            'new_values' => ['assigned_to' => $request->agent_id],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Ticket reassigned');
    }

    public function activityLogs(Request $request)
    {
        $domain = $request->get('domain');

        $logs = AuditLog::with('user')
            ->when($domain, function ($q) use ($domain) {
                $q->where(function ($qq) use ($domain) {
                    $qq->where('action', 'like', "%{$domain}%")
                        ->orWhere('model_type', 'like', "%{$domain}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50)
            ->withQueryString();

        return view('dashboards.super-admin.activity-logs', compact('logs', 'domain'));
    }

    public function financialOverride(Request $request)
    {
        $transactions = FinancialTransaction::with('user')
            ->when($request->search, function ($q, $search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('dashboards.super-admin.financial-override', compact('transactions'));
    }

    public function updateFinancialOverride(Request $request, FinancialTransaction $transaction)
    {
        $validated = $request->validate([
            'amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $old = $transaction->only(['amount', 'status', 'description']);
        $transaction->update(array_filter($validated, fn ($v) => $v !== null && $v !== ''));

        Cache::flush();

        AuditLog::log('financial_override_update', $transaction, $old, $transaction->only(['amount', 'status', 'description']));

        return back()->with('success', 'Transaction updated');
    }

    /**
     * Notifications & Announcements
     */
    public function announcements()
    {
        $announcements = \App\Models\Announcement::orderBy('created_at', 'desc')->paginate(20);

        return view('dashboards.super-admin.announcements', compact('announcements'));
    }

    public function createAnnouncement(Request $request)
    {
        $request->validate(['title' => 'required|string', 'content' => 'required|string']);
        $announcement = \App\Models\Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'created_by' => auth()->id(),
        ]);
        Cache::flush();
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'announcement_create',
            'model_type' => 'Announcement',
            'model_id' => $announcement->id,
            'new_values' => ['title' => $request->title],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Announcement created');
    }

    /**
     * Database health & usage summary
     */
    public function databaseHealth()
    {
        $dbSize = DB::select('SELECT SUM(data_length + index_length) AS size FROM information_schema.tables WHERE table_schema = DATABASE()');
        $sizeBytes = (int) ($dbSize[0]->size ?? 0);
        $slowQueries = \App\Models\SlowQuery::count();
        $backupsTotal = \App\Models\DatabaseBackup::count();
        $lastBackup = \App\Models\DatabaseBackup::where('status', 'completed')->latest('completed_at')->first();

        $summary = [
            'database_size_bytes' => $sizeBytes,
            'database_size_mb' => round($sizeBytes / (1024 * 1024), 2),
            'slow_queries' => $slowQueries,
            'backups_total' => $backupsTotal,
            'last_backup' => $lastBackup,
        ];

        return view('dashboards.super-admin.database', compact('summary'));
    }

    /**
     * Feature toggles view (System settings with 'feature.' prefix)
     */
    public function featureToggles()
    {
        $features = SystemSetting::where('key', 'like', 'feature.%')->get();

        return view('dashboards.super-admin.features', compact('features'));
    }

    public function updateFeatureToggles(Request $request)
    {
        foreach ($request->features ?? [] as $key => $value) {
            SystemSetting::updateOrCreate(['key' => $key], ['value' => $value ? 'true' : 'false']);
        }
        Cache::flush();

        return back()->with('success', 'Feature toggles updated');
    }

    /**
     * Get global platform metrics
     */
    private function getGlobalMetrics()
    {
        return Cache::remember('admin_global_metrics', 300, function () {
            return [
                // Platform Overview
                'total_users' => User::count(),
                'active_users' => User::where('status', 'active')->count(),
                'total_stores' => Store::count(),
                'active_stores' => Store::where('status', 'active')->count(),

                // Financial Overview
                'total_revenue' => $this->sumOrderTotal(Order::where('payment_status', 'paid')),
                'revenue_today' => $this->sumOrderTotal(
                    Order::where('payment_status', 'paid')->whereDate('created_at', today())
                ),
                'monthly_revenue' => $this->sumOrderTotal(
                    Order::where('payment_status', 'paid')->whereMonth('created_at', now()->month)
                ),
                'total_commission' => FinancialTransaction::where('type', 'commission')
                    ->where('status', 'completed')->sum('amount'),
                'monthly_commission' => FinancialTransaction::where('type', 'commission')
                    ->where('status', 'completed')
                    ->whereMonth('created_at', now()->month)->sum('amount'),

                // Order Metrics
                'total_orders' => Order::count(),
                'monthly_orders' => Order::whereMonth('created_at', now()->month)->count(),
                // Awaiting assignment: pending
                'pending_orders' => Order::whereIn('status', ['pending'])->count(),
                // Active lifecycle: pending → out_for_delivery → delivered (before CS marks done)
                'active_orders' => Order::whereIn('status', ['pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered'])->count(),
                'avg_order_value' => $this->avgOrderTotal(Order::where('payment_status', 'paid')),

                // Product Metrics
                'total_products' => Product::count(),
                'active_products' => (function () {
                    $q = Product::query();
                    if (Schema::hasColumn('products', 'is_active')) {
                        $q->where('is_active', true);
                    } elseif (Schema::hasColumn('products', 'status')) {
                        $q->where('status', 'active');
                    }

                    return $q->count();
                })(),
                'low_stock_alerts' => (function () {
                    $stockCol = Schema::hasColumn('products', 'stock_quantity') ? 'stock_quantity' : (Schema::hasColumn('products', 'stock') ? 'stock' : null);
                    $thresholdCol = Schema::hasColumn('products', 'low_stock_threshold') ? 'low_stock_threshold' : null;

                    if ($stockCol && $thresholdCol) {
                        return Product::whereRaw($stockCol.' <= '.$thresholdCol)->count();
                    }
                    if ($stockCol) {
                        return Product::where($stockCol, '<=', 10)->count();
                    }

                    return 0;
                })(),
                'low_stock_products' => (function () {
                    $stockCol = Schema::hasColumn('products', 'stock_quantity') ? 'stock_quantity' : (Schema::hasColumn('products', 'stock') ? 'stock' : null);
                    $thresholdCol = Schema::hasColumn('products', 'low_stock_threshold') ? 'low_stock_threshold' : null;

                    if ($stockCol && $thresholdCol) {
                        return Product::whereRaw($stockCol.' <= '.$thresholdCol)
                            ->orderBy($stockCol, 'asc')
                            ->take(10)
                            ->get();
                    }
                    if ($stockCol) {
                        return Product::where($stockCol, '<=', 10)
                            ->orderBy($stockCol, 'asc')
                            ->take(10)
                            ->get();
                    }

                    return collect();
                })(),

                // Growth Metrics
                'user_growth' => $this->getUserGrowthRate(),
                'revenue_growth' => $this->getRevenueGrowthRate(),
                'order_growth' => $this->getOrderGrowthRate(),

                // System Health
                'system_alerts' => $this->getActiveSystemAlerts(),
                'pending_support_tickets' => SupportTicket::whereIn('status', ['open', 'pending', 'in_progress', 'waiting_customer'])->count(),
                'recent_activities' => $this->getRecentActivities(20),
                'top_performing_stores' => $this->getTopPerformingStores(),

                // Charts & tables
                'revenue_chart_30d' => $this->getRevenueChartData(30),
                'orders_by_status_30d' => $this->getOrderStatusBreakdown(30),
                'top_products_30d' => $this->getTopProducts(30),
                'geo_orders_30d' => $this->getGeoOrderPoints(30),
            ];
        });
    }

    /**
     * Collect KPIs from HR, Finance, Support, and Drivers dashboards
     */
    private function getCrossDepartmentKPIs(): array
    {
        return [
            'hr' => app(HRDashboardService::class)->getKPIMetrics(),
            'finance' => app(FinanceDashboardService::class)->getKPIMetrics(),
            'support' => app(CSDashboardService::class)->getKPIMetrics(),
            'drivers' => app(DeliveryDashboardService::class)->getKPIMetrics(),
        ];
    }

    /**
     * User Management
     */
    public function users(Request $request)
    {
        $users = User::with(['roles'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->role, function ($query, $role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $roles = Role::all();

        // Money per user (total spent on paid orders)
        $sumColumn = Schema::hasColumn('orders', 'total_amount')
            ? 'total_amount'
            : (Schema::hasColumn('orders', 'total') ? 'total' : null);
        $sumExpr = $sumColumn ? $sumColumn : implode(' + ', array_filter([
            Schema::hasColumn('orders', 'subtotal') ? 'subtotal' : null,
            Schema::hasColumn('orders', 'delivery_cost') ? 'delivery_cost' : null,
            Schema::hasColumn('orders', 'service_fee') ? 'service_fee' : null,
        ]));

        $userSpendingMap = [];
        if ($sumExpr) {
            $userFk = Schema::hasColumn('orders', 'user_id')
                ? 'user_id'
                : (Schema::hasColumn('orders', 'customer_id') ? 'customer_id' : null);

            if ($userFk) {
                $userSpendingMap = Order::where('payment_status', 'paid')
                    ->whereNotNull($userFk)
                    ->selectRaw($userFk.', SUM('.$sumExpr.') as total_spent')
                    ->groupBy($userFk)
                    ->pluck('total_spent', $userFk)
                    ->toArray();
            }
        }

        return view('dashboards.super-admin.users', compact('users', 'roles', 'userSpendingMap'));
    }

    public function traders(Request $request)
    {
        $statusOptions = [
            \App\Models\Trader::STATUS_PENDING,
            \App\Models\Trader::STATUS_APPROVED,
            \App\Models\Trader::STATUS_REJECTED,
            \App\Models\Trader::STATUS_SUSPENDED,
        ];

        $traders = \App\Models\Trader::query()
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', (string) $request->input('status')))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('dashboards.super-admin.traders.index', compact('traders', 'statusOptions'));
    }

    public function traderDetails(\App\Models\Trader $trader)
    {
        return view('dashboards.super-admin.traders.show', compact('trader'));
    }

    /**
     * Create new user
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            // Assign roles
            foreach ($request->roles as $roleId) {
                DB::table('user_roles')->insert([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                    'assigned_at' => now(),
                    'assigned_by' => auth()->id(),
                    'is_active' => true,
                ]);
            }

            DB::commit();

            return redirect()->route('dashboard.admin.users')
                ->with('success', 'User created successfully!');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Failed to create user: '.$e->getMessage());
        }
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $user->update($request->only(['name', 'email', 'phone', 'status']));

            // Update roles if provided
            if ($request->has('roles') && is_array($request->roles)) {
                DB::table('user_roles')->where('user_id', $user->id)->delete();
                foreach ($request->roles as $roleId) {
                    DB::table('user_roles')->insert([
                        'user_id' => $user->id,
                        'role_id' => $roleId,
                        'assigned_at' => now(),
                        'assigned_by' => auth()->id(),
                        'is_active' => true,
                    ]);
                }
            }

            DB::commit();

            Cache::flush();

            return redirect()->route('dashboard.admin.users')
                ->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Failed to update user: '.$e->getMessage());
        }
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        DB::beginTransaction();
        try {
            // Delete user roles
            DB::table('user_roles')->where('user_id', $user->id)->delete();

            // Delete user
            $user->delete();

            DB::commit();

            return redirect()->route('dashboard.admin.users')
                ->with('success', 'User deleted successfully!');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Failed to delete user: '.$e->getMessage());
        }
    }

    /**
     * RBAC Management
     */
    public function roles(Request $request)
    {
        $roles = collect();
        $permissions = collect();

        if (Schema::hasTable('roles')) {
            $roles = Role::query()
                ->when(method_exists(Role::query()->getModel(), 'permissions') && Schema::hasTable('permissions'), function ($q) {
                    $q->with(['permissions']);
                })
                ->get();
        }

        if (Schema::hasTable('permissions')) {
            $permissions = Permission::all()->groupBy('category');
        }

        $employees = Employee::query()
            ->when($request->search, function ($q, $search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        $dashboardCatalog = [
            'it' => ['title' => 'IT', 'sections' => ['system_health', 'errors', 'deployments', 'security', 'logs'], 'actions' => ['approve', 'submit', 'delete', 'export']],
            'admin' => ['title' => 'Admin', 'sections' => ['kpis', 'orders', 'users', 'audit', 'settings'], 'actions' => ['approve', 'submit', 'delete', 'export']],
            'mart' => ['title' => 'Tulip Mart', 'sections' => ['categories', 'products', 'daily_prices'], 'actions' => ['submit', 'delete', 'export']],
            'cs' => ['title' => 'Customer Support', 'sections' => ['tickets', 'orders', 'trader_approvals'], 'actions' => ['approve', 'submit', 'delete', 'export']],
            'hr' => ['title' => 'HR', 'sections' => ['employees', 'attendance', 'leave', 'payroll'], 'actions' => ['approve', 'submit', 'delete', 'export']],
            'finance' => ['title' => 'Finance', 'sections' => ['transactions', 'payouts', 'reports'], 'actions' => ['approve', 'submit', 'delete', 'export']],
        ];

        $roleTemplateMap = [];
        if (Schema::hasTable('dashboard_role_permissions')) {
            DashboardRolePermission::query()->get()->each(function ($r) use (&$roleTemplateMap) {
                $roleTemplateMap[$r->role_key][$r->dashboard_key] = $r;
            });
        }

        $employeeOverrideMap = [];
        if (Schema::hasTable('employee_dashboard_overrides')) {
            EmployeeDashboardOverride::query()->get()->each(function ($o) use (&$employeeOverrideMap) {
                $employeeOverrideMap[$o->employee_id][$o->dashboard_key] = $o;
            });
        }

        $resolvedPermissionMap = [];
        foreach ($employees->items() as $emp) {
            foreach (array_keys($dashboardCatalog) as $dk) {
                $resolvedPermissionMap[$emp->id][$dk] = DashboardPermissionService::resolve($emp, $dk);
            }
        }

        $preview = null;
        if ($request->filled('preview_employee') && $request->filled('preview_dashboard')) {
            $target = Employee::find((int) $request->input('preview_employee'));
            $dashboardKey = (string) $request->input('preview_dashboard');
            if ($target && isset($dashboardCatalog[$dashboardKey])) {
                $preview = [
                    'employee' => $target,
                    'dashboard_key' => $dashboardKey,
                    'resolved' => DashboardPermissionService::resolve($target, $dashboardKey),
                ];
            }
        }

        return view('dashboards.super-admin.roles', compact(
            'roles',
            'permissions',
            'employees',
            'dashboardCatalog',
            'roleTemplateMap',
            'employeeOverrideMap',
            'resolvedPermissionMap',
            'preview'
        ));
    }

    public function updateEmployeeDashboardRules(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'dashboard_key' => 'required|string|in:it,admin,mart,cs,hr,finance',
            'is_override' => 'nullable|boolean',
            'can_view' => 'nullable|boolean',
            'can_edit' => 'nullable|boolean',
            'can_view_sensitive' => 'nullable|boolean',
            'sections' => 'nullable|array',
            'sections.*' => 'string|max:80',
            'actions' => 'nullable|array',
            'actions.*' => 'string|max:80',
        ]);

        if (! Schema::hasTable('employee_dashboard_overrides')) {
            return back()->with('error', 'Permission override table is missing. Run migrations first.');
        }

        $dashboardKey = (string) $validated['dashboard_key'];
        $isOverride = (bool) ($validated['is_override'] ?? false);
        $payload = [
            'is_override' => $isOverride,
            'can_view' => $isOverride ? (bool) ($validated['can_view'] ?? false) : null,
            'can_edit' => $isOverride ? (bool) ($validated['can_edit'] ?? false) : null,
            'sections' => $isOverride ? array_values(array_unique($validated['sections'] ?? [])) : null,
            'actions' => $isOverride ? array_values(array_unique($validated['actions'] ?? [])) : null,
            'can_view_sensitive' => $isOverride ? (bool) ($validated['can_view_sensitive'] ?? false) : null,
        ];

        EmployeeDashboardOverride::updateOrCreate([
                        'employee_id' => $employee->id,
            'dashboard_key' => $dashboardKey,
        ], $payload);

        AuditLog::create([
            'user_id' => auth('employee')->id(),
            'action' => 'employee_permission_override_update',
            'model_type' => 'Employee',
            'model_id' => $employee->id,
            'new_values' => ['dashboard' => $dashboardKey, 'payload' => $payload],
            'ip_address' => request()->ip(),
        ]);

        Cache::flush();

        return back()->with('success', 'تم تحديث صلاحيات الموظف لهذا الداشبورد');
    }

    public function updateRoleDashboardPermissions(Request $request)
    {
        $validated = $request->validate([
            'role_key' => 'required|string|max:50',
            'dashboard_key' => 'required|string|in:it,admin,mart,cs,hr,finance',
            'can_view' => 'nullable|boolean',
            'can_edit' => 'nullable|boolean',
            'can_view_sensitive' => 'nullable|boolean',
            'sections' => 'nullable|array',
            'sections.*' => 'string|max:80',
            'actions' => 'nullable|array',
            'actions.*' => 'string|max:80',
        ]);

        if (! Schema::hasTable('dashboard_role_permissions')) {
            return back()->with('error', 'Role permission table is missing. Run migrations first.');
        }

        $payload = [
            'can_view' => (bool) ($validated['can_view'] ?? false),
            'can_edit' => (bool) ($validated['can_edit'] ?? false),
            'sections' => array_values(array_unique($validated['sections'] ?? [])),
            'actions' => array_values(array_unique($validated['actions'] ?? [])),
            'can_view_sensitive' => (bool) ($validated['can_view_sensitive'] ?? false),
        ];

        DashboardRolePermission::updateOrCreate([
            'role_key' => (string) $validated['role_key'],
            'dashboard_key' => (string) $validated['dashboard_key'],
        ], $payload);

        AuditLog::create([
            'user_id' => auth('employee')->id(),
            'action' => 'role_dashboard_permission_update',
            'model_type' => 'Role',
            'model_id' => null,
            'new_values' => [
                'role_key' => $validated['role_key'],
                'dashboard_key' => $validated['dashboard_key'],
                'payload' => $payload,
            ],
            'ip_address' => request()->ip(),
        ]);

        Cache::flush();

        return back()->with('success', 'تم تحديث صلاحيات الدور بنجاح');
    }

    public function permissionPreview(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'dashboard_key' => 'required|string|in:it,admin,mart,cs,hr,finance',
        ]);

        return redirect()->route('dashboard.admin.roles', [
            'preview_employee' => $validated['employee_id'],
            'preview_dashboard' => $validated['dashboard_key'],
        ]);
    }

    public function updateExchangeRate(Request $request)
    {
        $validated = $request->validate([
            'usd_to_syp_rate' => 'required|numeric|min:1|max:100000',
        ]);

        SystemSetting::set('usd_to_syp_rate', (string) $validated['usd_to_syp_rate'], 'string');
        Cache::flush();

        return back()->with('success', 'تم تحديث سعر الصرف بنجاح');
    }

    public function categories(Request $request)
    {
        $categories = Category::query()
            ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', 'store'))
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when(Schema::hasColumn('categories', 'display_order'), function ($q) {
                $q->orderBy('display_order');
            })
            ->orderBy('name')
            ->paginate(30)
            ->withQueryString();

        return view('dashboards.super-admin.categories', compact('categories'));
    }

    public function createCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => Schema::hasColumn('categories', 'image') ? 'required|image|mimes:jpg,jpeg,png,webp|max:4096' : 'nullable',
        ]);

        $slugBase = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $slug = $slugBase ?: Str::random(8);
        $suffix = 1;
        while (Category::where('slug', $slug)->exists()) {
            $suffix++;
            $slug = $slugBase.'-'.$suffix;
        }

        $data = [
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ];
        if (Schema::hasColumn('categories', 'display_order')) {
            $data['display_order'] = (int) ($validated['display_order'] ?? 0);
        }
        if (Schema::hasColumn('categories', 'is_active')) {
            $data['is_active'] = (bool) ($validated['is_active'] ?? true);
        }
        if (Schema::hasColumn('categories', 'market')) {
            $data['market'] = 'store';
        }
        if ($request->file('image') && Schema::hasColumn('categories', 'image')) {
            $data['image'] = Storage::disk('public')->putFile('categories', $request->file('image'));
        }

        $category = Category::create($data);

        Cache::flush();

        $employee = auth('employee')->user();
        $userId = auth()->id() ?? ($employee?->user_id ?? null);
        if ($userId !== null && ! \App\Models\User::whereKey($userId)->exists()) {
            $userId = null;
        }
        AuditLog::create([
            'user_id' => $userId,
            'action' => 'category_create',
            'model_type' => 'Category',
            'model_id' => $category->id,
            'new_values' => $data,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Category created');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => Schema::hasColumn('categories', 'image') ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096' : 'nullable',
        ]);

        $old = $category->only(['name', 'slug', 'description', 'display_order', 'is_active']);

        $slugBase = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $slug = $slugBase ?: $category->slug;
        $suffix = 1;
        while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $suffix++;
            $slug = $slugBase.'-'.$suffix;
        }

        $data = [
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ];
        if (Schema::hasColumn('categories', 'display_order')) {
            $data['display_order'] = (int) ($validated['display_order'] ?? ($category->display_order ?? 0));
        }
        if (Schema::hasColumn('categories', 'is_active')) {
            $data['is_active'] = (bool) ($validated['is_active'] ?? ($category->is_active ?? true));
        }
        if ($request->file('image') && Schema::hasColumn('categories', 'image')) {
            $data['image'] = Storage::disk('public')->putFile('categories', $request->file('image'));
        }

        $category->update($data);

        Cache::flush();

        $employee = auth('employee')->user();
        $userId = auth()->id() ?? ($employee?->user_id ?? null);
        if ($userId !== null && ! \App\Models\User::whereKey($userId)->exists()) {
            $userId = null;
        }
        AuditLog::create([
            'user_id' => $userId,
            'action' => 'category_update',
            'model_type' => 'Category',
            'model_id' => $category->id,
            'old_values' => $old,
            'new_values' => $data,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Category updated');
    }

    public function deleteCategory(Category $category)
    {
        $old = $category->toArray();
        $category->delete();

        Cache::flush();

        $employee = auth('employee')->user();
        $userId = auth()->id() ?? ($employee?->user_id ?? null);
        if ($userId !== null && ! \App\Models\User::whereKey($userId)->exists()) {
            $userId = null;
        }
        AuditLog::create([
            'user_id' => $userId,
            'action' => 'category_delete',
            'model_type' => 'Category',
            'model_id' => $old['id'] ?? null,
            'old_values' => $old,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Category deleted');
    }

    public function employees(Request $request)
    {
        $departments = Employee::query()
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department')
            ->values()
            ->all();

        $employees = Employee::query()
            ->with('user')
            ->when($request->search, function ($q, $search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->department, fn ($q, $dep) => $q->where('department', $dep))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->employment_type, fn ($q, $type) => $q->where('employment_type', $type))
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('dashboards.super-admin.employees', compact('employees', 'departments'));
    }

    public function editEmployeeDashboards(Employee $employee)
    {
        $definitions = [
            'admin' => 'Super Admin',
            'mart' => 'Tulip Mart',
            'it' => 'IT/DevOps',
            'hr' => 'Human Resources',
            'finance' => 'Finance',
            'supervisor' => 'Driver Supervisor',
            'vendor' => 'Store Management',
        ];

        $selected = $employee->getExplicitDashboardKeys();
        if (in_array('__none__', $selected, true)) {
            $selected = [];
        }

        return view('dashboards.super-admin.employee-dashboards', [
            'employee' => $employee,
            'definitions' => $definitions,
            'selected' => $selected,
        ]);
    }

    public function updateEmployeeDashboards(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'dashboards' => 'array',
            'dashboards.*' => 'in:admin,mart,it,hr,finance,supervisor,vendor',
        ]);

        $keys = array_values(array_unique($validated['dashboards'] ?? []));

        DB::transaction(function () use ($employee, $keys) {
            EmployeeDashboardPermission::where('employee_id', $employee->id)->delete();

            if (count($keys) === 0) {
                EmployeeDashboardPermission::create([
                    'employee_id' => $employee->id,
                    'dashboard_key' => '__none__',
                ]);

                return;
            }

            foreach ($keys as $k) {
                EmployeeDashboardPermission::create([
                    'employee_id' => $employee->id,
                    'dashboard_key' => $k,
                ]);
            }
        });

        return redirect()->route('dashboard.admin.employees')
            ->with('success', 'Dashboard permissions updated');
    }

    public function gifts(Request $request)
    {
        $gifts = null;
        $boxes = null;
        $wrappings = null;
        $ribbons = null;
        $cards = null;
        $fillers = null;

        if (Schema::hasTable('gifts')) {
            $gifts = Gift::query()
                ->when($request->search, function ($q, $search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                })
                ->when($request->active !== null && $request->active !== '', function ($q) use ($request) {
                    if (Schema::hasColumn('gifts', 'is_active')) {
                        $q->where('is_active', (bool) $request->active);
                    }
                })
                ->orderBy('created_at', 'desc')
                ->paginate(20)
                ->withQueryString();
        }

        if (Schema::hasTable('gift_boxes')) {
            $boxes = GiftBox::query()->orderBy('sort_order')->orderBy('id')->get();
        }

        if (Schema::hasTable('gift_wrappings')) {
            $wrappings = GiftWrapping::query()->orderBy('sort_order')->orderBy('id')->get();
        }

        if (Schema::hasTable('gift_ribbons')) {
            $ribbons = GiftRibbon::query()->orderBy('sort_order')->orderBy('id')->get();
        }

        if (Schema::hasTable('gift_cards')) {
            $cards = GiftCard::query()->orderBy('sort_order')->orderBy('id')->get();
        }

        if (Schema::hasTable('gift_fillers')) {
            $fillers = GiftFiller::query()->orderBy('sort_order')->orderBy('id')->get();
        }

        return view('dashboards.super-admin.gifts', compact('gifts', 'boxes', 'wrappings', 'ribbons', 'cards', 'fillers'));
    }

    public function storeGift(Request $request)
    {
        abort_unless(Schema::hasTable('gifts'), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:50',
            'occasion' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $imagePath = Storage::disk('public')->putFile('gifts', $request->file('image'));

        Gift::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? 'general',
            'occasion' => $validated['occasion'] ?? null,
            'price' => $validated['price'],
            'stock_quantity' => $validated['stock_quantity'],
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'images' => ['/storage/'.$imagePath],
        ]);

        return back()->with('success', 'Gift created');
    }

    public function storeGiftBox(Request $request)
    {
        abort_unless(Schema::hasTable('gift_boxes'), 404);

        // gift_boxes.size is an ENUM in DB: small/medium/large/xl
        // UI sometimes sends Arabic values like "متوسط" or "كبير".
        $rawSize = trim((string) $request->input('size', ''));
        $sizeNormalized = strtolower($rawSize);
        $sizeMap = [
            // Arabic -> enum
            'صغير' => 'small',
            'صغيرة' => 'small',
            'متوسط' => 'medium',
            'متوسطه' => 'medium',
            'كبير' => 'large',
            'كبيرة' => 'large',
            'اكس لارج' => 'xl',
            'اكس-لارج' => 'xl',
            'xl' => 'xl',
            'x-large' => 'xl',
            'اكس لارج' => 'xl',

            // English (case-insensitive) -> enum
            'small' => 'small',
            'medium' => 'medium',
            'large' => 'large',
            'xl' => 'xl',
        ];

        if (isset($sizeMap[$sizeNormalized])) {
            $request->merge(['size' => $sizeMap[$sizeNormalized]]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'size' => 'required|in:small,medium,large,xl',
            'price' => 'required|numeric|min:0',
            'max_items' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $imagePath = Storage::disk('public')->putFile('gift-boxes', $request->file('image'));

        GiftBox::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'size' => $validated['size'],
            'price' => $validated['price'],
            'max_items' => $validated['max_items'],
            'stock' => $validated['stock'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'image' => '/storage/'.$imagePath,
        ]);

        return back()->with('success', 'Gift box created');
    }

    public function storeGiftFiller(Request $request)
    {
        abort_unless(Schema::hasTable('gift_fillers'), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:chocolate,flower,perfume,accessory,candy,toy,other',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $imagePath = Storage::disk('public')->putFile('gift-fillers', $request->file('image'));

        GiftFiller::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'image' => '/storage/'.$imagePath,
        ]);

        return back()->with('success', 'Gift item created');
    }

    public function giftsCreation(Request $request)
    {
        $gifts = null;
        $boxes = null;
        $wrappings = null;
        $ribbons = null;
        $cards = null;
        $fillers = null;

        if (Schema::hasTable('gifts')) {
            $gifts = Gift::query()->orderBy('created_at', 'desc')->paginate(10);
        }
        if (Schema::hasTable('gift_boxes')) {
            $boxes = GiftBox::query()->orderBy('sort_order')->orderBy('id')->get();
        }
        if (Schema::hasTable('gift_wrappings')) {
            $wrappings = GiftWrapping::query()->orderBy('sort_order')->orderBy('id')->get();
        }
        if (Schema::hasTable('gift_ribbons')) {
            $ribbons = GiftRibbon::query()->orderBy('sort_order')->orderBy('id')->get();
        }
        if (Schema::hasTable('gift_cards')) {
            $cards = GiftCard::query()->orderBy('sort_order')->orderBy('id')->get();
        }
        if (Schema::hasTable('gift_fillers')) {
            $fillers = GiftFiller::query()->orderBy('sort_order')->orderBy('id')->get();
        }

        return view('dashboards.super-admin.gifts-creation', compact('gifts', 'boxes', 'wrappings', 'ribbons', 'cards', 'fillers'));
    }

    public function storeGiftWrapping(Request $request)
    {
        abort_unless(Schema::hasTable('gift_wrappings'), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'color' => 'nullable|string|max:50',
            'pattern' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $imagePath = $request->hasFile('image')
            ? '/storage/'.Storage::disk('public')->putFile('gift-wrappings', $request->file('image'))
            : null;

        GiftWrapping::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'color' => $validated['color'] ?? null,
            'pattern' => $validated['pattern'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Wrapping created');
    }

    public function storeGiftRibbon(Request $request)
    {
        abort_unless(Schema::hasTable('gift_ribbons'), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            // DB migration has `color` as non-nullable.
            'color' => 'required|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $imagePath = $request->hasFile('image')
            ? '/storage/'.Storage::disk('public')->putFile('gift-ribbons', $request->file('image'))
            : null;

        GiftRibbon::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'color' => $validated['color'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Ribbon created');
    }

    public function storeGiftCard(Request $request)
    {
        abort_unless(Schema::hasTable('gift_cards'), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'occasion' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $imagePath = $request->hasFile('image')
            ? '/storage/'.Storage::disk('public')->putFile('gift-cards', $request->file('image'))
            : null;

        GiftCard::create([
            'name' => $validated['name'],
            'occasion' => $validated['occasion'] ?? null,
            'price' => $validated['price'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Card created');
    }

    public function storeAssembledGift(Request $request)
    {
        abort_unless(Schema::hasTable('gifts'), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'box_id' => 'required|integer|exists:gift_boxes,id',
            'filler_ids' => 'required|array|min:1',
            'filler_ids.*' => 'integer|exists:gift_fillers,id',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'category' => 'nullable|string|max:50',
            'occasion' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $box = GiftBox::findOrFail($validated['box_id']);
        $fillers = GiftFiller::whereIn('id', $validated['filler_ids'])->get();

        $price = (float) ($box->price ?? 0);
        foreach ($fillers as $f) {
            $price += (float) ($f->price ?? 0);
        }

        $stocks = [(int) ($box->stock ?? 0)];
        foreach ($fillers as $f) {
            $stocks[] = (int) ($f->stock ?? 0);
        }
        $stockQty = min($stocks);

        $images = [];
        if ($request->hasFile('image')) {
            $img = Storage::disk('public')->putFile('gifts', $request->file('image'));
            $images[] = '/storage/'.$img;
        } else {
            if (! empty($box->image)) {
                $images[] = $box->image;
            }
            foreach ($fillers as $f) {
                if (! empty($f->image)) {
                    $images[] = $f->image;
                }
            }
        }

        Gift::create([
            'name' => $validated['name'],
            'description' => null,
            'category' => $validated['category'] ?? 'general',
            'occasion' => $validated['occasion'] ?? null,
            'price' => $price,
            'stock_quantity' => $stockQty,
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'images' => $images,
            'is_customizable' => false,
            'customization_options' => [
                'box_id' => (int) $box->id,
                'filler_ids' => $fillers->pluck('id')->map(fn ($id) => (int) $id)->all(),
            ],
        ]);

        return back()->with('success', 'Assembled gift created');
    }

    public function mart(Request $request)
    {
        $categories = null;
        $products = null;

        if (Schema::hasTable('categories')) {
            $categoriesQuery = Category::query()
                ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', 'mart'))
                ->when(Schema::hasColumn('categories', 'is_active'), fn ($q) => $q->where('is_active', true))
                ->when(Schema::hasColumn('categories', 'display_order'), fn ($q) => $q->orderBy('display_order'))
                ->orderBy('name');

            if (Schema::hasTable('subcategories')) {
                $categoriesQuery->with(['subcategories' => function ($q) {
                    if (Schema::hasColumn('subcategories', 'is_active')) {
                        $q->where('is_active', true);
                    }
                    if (Schema::hasColumn('subcategories', 'display_order')) {
                        $q->orderBy('display_order');
                    }
                    $q->orderBy('name');
                }]);
            }

            $categories = $categoriesQuery->get();
        }

        if (Schema::hasTable('products')) {
            $products = Product::query()
                ->with(Schema::hasTable('subcategories') ? ['category', 'subcategory'] : ['category'])
                ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', 'mart'))
                ->when($request->search, function ($q, $search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })
                ->when($request->category_id, fn ($q, $id) => $q->where('category_id', $id))
                ->when($request->subcategory_id && Schema::hasColumn('products', 'subcategory_id'), fn ($q, $id) => $q->where('subcategory_id', $id))
                ->orderBy('created_at', 'desc')
                ->paginate(25)
                ->withQueryString();
        }

        return view('dashboards.super-admin.mart', compact('categories', 'products'));
    }

    private function generateUniqueSku(string $prefix, ?int $ignoreProductId = null): string
    {
        $clean = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $prefix) ?? '', 0, 3));
        if ($clean === '') {
            $clean = 'PRD';
        }

        $latest = Product::query()
            ->when(Schema::hasColumn('products', 'sku'), fn ($q) => $q->where('sku', 'like', $clean.'-%'))
            ->orderBy('sku', 'desc')
            ->value('sku');

        $next = 1;
        if (is_string($latest) && preg_match('/-(\d+)$/', $latest, $m)) {
            $next = max(1, ((int) $m[1]) + 1);
        }

        for ($i = 0; $i < 500; $i++) {
            $sku = $clean.'-'.str_pad((string) $next, 5, '0', STR_PAD_LEFT);
            $exists = Product::query()
                ->where('sku', $sku)
                ->when($ignoreProductId !== null, fn ($q) => $q->where('id', '!=', $ignoreProductId))
                ->exists();
            if (! $exists) {
                return $sku;
            }
            $next++;
        }

        return $clean.'-'.str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    public function createMartProduct()
    {
        abort_unless(Schema::hasTable('products'), 404);
        $categories = Schema::hasTable('categories')
            ? Category::query()
                ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', 'mart'))
                ->orderBy('display_order')
                ->orderBy('name')
                ->get()
            : collect();
        $subcategories = Schema::hasTable('subcategories')
            ? Subcategory::query()
                ->whereIn('category_id', $categories->pluck('id')->all())
                ->when(Schema::hasColumn('subcategories', 'is_active'), fn ($q) => $q->where('is_active', true))
                ->orderBy('display_order')
                ->orderBy('name')
                ->get()
            : collect();
        return view('dashboards.super-admin.mart-product-create', compact('categories', 'subcategories'));
    }

    public function storeMartProduct(Request $request)
    {
        abort_unless(Schema::hasTable('products'), 404);

        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'subcategory_id' => 'nullable|integer|exists:subcategories,id',
            'sku' => ['nullable', 'string', 'max:255'],
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'track_inventory' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'unit' => 'nullable|string|max:50',
            'origin' => 'nullable|string|max:100',
        ];
        if (! Schema::hasTable('subcategories') || ! Schema::hasColumn('products', 'subcategory_id')) {
            unset($rules['subcategory_id']);
        }
        if (Schema::hasColumn('products', 'sku')) {
            $rules['sku'][] = Rule::unique('products', 'sku');
        }
        $validated = $request->validate($rules);

        if (Schema::hasTable('subcategories') && Schema::hasColumn('products', 'subcategory_id')) {
            $subId = isset($validated['subcategory_id']) ? (int) $validated['subcategory_id'] : 0;
            if ($subId > 0) {
                $sub = Subcategory::query()->with('category')->find($subId);
                if (! $sub) {
                    return back()->withErrors(['subcategory_id' => 'Invalid subcategory'])->withInput();
                }
                if (Schema::hasColumn('categories', 'market') && (string) (optional($sub->category)->market ?? '') !== 'mart') {
                    return back()->withErrors(['subcategory_id' => 'Subcategory must belong to Mart'])->withInput();
                }
                $validated['category_id'] = $sub->category_id;
            } elseif (! empty($validated['category_id'])) {
                $catId = (int) $validated['category_id'];
                $default = Subcategory::query()->where('category_id', $catId)->where('slug', 'general')->first();
                if (! $default) {
                    $default = Subcategory::create([
                        'category_id' => $catId,
                        'name' => 'عام',
                        'slug' => 'general',
                        'display_order' => 0,
                        'is_active' => true,
                    ]);
                }
                $validated['subcategory_id'] = $default->id;
            }
        }

        $slug = trim((string) ($validated['slug'] ?? ''));
        $slug = $slug === '' ? Str::slug($validated['name']) : Str::slug($slug);
        if ($slug === '') {
            $slug = 'product';
        }
        $baseSlug = $slug;
        $i = 0;
        while (Product::where('slug', $slug)->exists() && $i < 50) {
            $slug = $baseSlug.'-'.random_int(1000, 9999);
            $i++;
        }

        $imagePath = null;
        if ($request->file('image')) {
            $imagePath = Storage::disk('public')->putFile('products', $request->file('image'));
        }

        $sku = trim((string) ($validated['sku'] ?? ''));
        if ($sku === '') {
            $prefix = 'PRD';
            if (! empty($validated['category_id'])) {
                $cat = Category::find($validated['category_id']);
                if ($cat) {
                    $prefix = (string) $cat->name;
                }
            }
            $sku = $this->generateUniqueSku($prefix);
        }

        $data = [
            'name' => $validated['name'],
            'slug' => $slug,
            'sku' => $sku,
        ];
        foreach (['description','details','category_id','subcategory_id'] as $col) {
            if (Schema::hasColumn('products', $col) && array_key_exists($col, $validated)) {
                $data[$col] = $validated[$col];
            }
        }
        foreach (['price','discount_price','stock_quantity','low_stock_threshold'] as $col) {
            if (Schema::hasColumn('products', $col) && array_key_exists($col, $validated)) {
                $data[$col] = $validated[$col];
            }
        }
        foreach (['track_inventory','is_featured','is_active'] as $col) {
            if (Schema::hasColumn('products', $col) && array_key_exists($col, $validated)) {
                $data[$col] = (bool) $validated[$col];
            }
        }
        if (Schema::hasColumn('products', 'market')) {
            $data['market'] = 'mart';
        }
        if ($imagePath !== null) {
            if (Schema::hasColumn('products', 'image')) {
            $data['image'] = $imagePath;
            } elseif (Schema::hasColumn('products', 'images')) {
                $data['images'] = [$imagePath];
            }
        }

        $product = Product::create($data);

        // Optional attributes: unit, origin
        if (!empty($validated['unit'])) {
            $product->attributes()->create(['name' => 'unit', 'value' => $validated['unit']]);
        }
        if (!empty($validated['origin'])) {
            $product->attributes()->create(['name' => 'origin', 'value' => $validated['origin']]);
        }

        Cache::forget('mart:navigation:v1');

        return redirect()->route('dashboard.admin.mart.index')->with('success', 'Product created');
    }

    public function editMartProduct(Product $product)
    {
        abort_unless(Schema::hasTable('products'), 404);
        $categories = Schema::hasTable('categories')
            ? Category::query()
                ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', 'mart'))
                ->orderBy('display_order')
                ->orderBy('name')
                ->get()
            : collect();
        $subcategories = Schema::hasTable('subcategories')
            ? Subcategory::query()
                ->whereIn('category_id', $categories->pluck('id')->all())
                ->when(Schema::hasColumn('subcategories', 'is_active'), fn ($q) => $q->where('is_active', true))
                ->orderBy('display_order')
                ->orderBy('name')
                ->get()
            : collect();
        $attrs = $product->attributes()->get()->pluck('value','name');
        return view('dashboards.super-admin.mart-product-edit', compact('product','categories','subcategories','attrs'));
    }

    public function updateMartProduct(Request $request, Product $product)
    {
        abort_unless(Schema::hasTable('products'), 404);

        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'subcategory_id' => 'nullable|integer|exists:subcategories,id',
            'sku' => ['nullable', 'string', 'max:255'],
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'track_inventory' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'unit' => 'nullable|string|max:50',
            'origin' => 'nullable|string|max:100',
        ];
        if (! Schema::hasTable('subcategories') || ! Schema::hasColumn('products', 'subcategory_id')) {
            unset($rules['subcategory_id']);
        }
        if (Schema::hasColumn('products', 'sku')) {
            $rules['sku'][] = Rule::unique('products', 'sku')->ignore($product->id);
        }
        $validated = $request->validate($rules);

        if (Schema::hasTable('subcategories') && Schema::hasColumn('products', 'subcategory_id')) {
            $subId = isset($validated['subcategory_id']) ? (int) $validated['subcategory_id'] : 0;
            if ($subId > 0) {
                $sub = Subcategory::query()->with('category')->find($subId);
                if (! $sub) {
                    return back()->withErrors(['subcategory_id' => 'Invalid subcategory'])->withInput();
                }
                if (Schema::hasColumn('categories', 'market') && (string) (optional($sub->category)->market ?? '') !== 'mart') {
                    return back()->withErrors(['subcategory_id' => 'Subcategory must belong to Mart'])->withInput();
                }
                $validated['category_id'] = $sub->category_id;
            } elseif (! empty($validated['category_id'])) {
                $catId = (int) $validated['category_id'];
                $default = Subcategory::query()->where('category_id', $catId)->where('slug', 'general')->first();
                if (! $default) {
                    $default = Subcategory::create([
                        'category_id' => $catId,
                        'name' => 'عام',
                        'slug' => 'general',
                        'display_order' => 0,
                        'is_active' => true,
                    ]);
                }
                $validated['subcategory_id'] = $default->id;
            }
        }

        $slug = trim((string) ($validated['slug'] ?? ''));
        $slug = $slug === '' ? Str::slug($validated['name']) : Str::slug($slug);
        if ($slug === '') {
            $slug = 'product';
        }
        $baseSlug = $slug;
        $i = 0;
        while (Product::where('slug', $slug)->where('id','!=',$product->id)->exists() && $i < 50) {
            $slug = $baseSlug.'-'.random_int(1000, 9999);
            $i++;
        }

        $updates = [
            'name' => $validated['name'],
            'slug' => $slug,
        ];

        $sku = trim((string) ($validated['sku'] ?? ($product->sku ?? '')));
        $categoryChanged = array_key_exists('category_id', $validated) && (int) ($validated['category_id'] ?? 0) !== (int) ($product->category_id ?? 0);
        if ($sku === '' || $categoryChanged) {
            $prefix = 'PRD';
            $catId = $validated['category_id'] ?? $product->category_id;
            if (! empty($catId)) {
                $cat = Category::find($catId);
                if ($cat) {
                    $prefix = (string) $cat->name;
                }
            }
            $sku = $this->generateUniqueSku($prefix, $product->id);
        }
        $updates['sku'] = $sku;

        foreach (['description','details','category_id','subcategory_id'] as $col) {
            if (Schema::hasColumn('products', $col) && array_key_exists($col, $validated)) {
                $updates[$col] = $validated[$col];
            }
        }
        foreach (['price','discount_price','stock_quantity','low_stock_threshold'] as $col) {
            if (Schema::hasColumn('products', $col) && array_key_exists($col, $validated)) {
                $updates[$col] = $validated[$col];
            }
        }
        foreach (['track_inventory','is_featured','is_active'] as $col) {
            if (Schema::hasColumn('products', $col) && array_key_exists($col, $validated)) {
                $updates[$col] = (bool) $validated[$col];
            }
        }
        if ($request->file('image')) {
            $storedImage = Storage::disk('public')->putFile('products', $request->file('image'));
            if (Schema::hasColumn('products', 'image')) {
                $updates['image'] = $storedImage;
            } elseif (Schema::hasColumn('products', 'images')) {
                $updates['images'] = [$storedImage];
            }
        }

        $product->update($updates);

        // Update attributes
        if (isset($validated['unit'])) {
            $product->attributes()->updateOrCreate(['name'=>'unit'], ['value'=>$validated['unit']]);
        }
        if (isset($validated['origin'])) {
            $product->attributes()->updateOrCreate(['name'=>'origin'], ['value'=>$validated['origin']]);
        }

        Cache::forget('mart:navigation:v1');

        return redirect()->route('dashboard.admin.mart.index')->with('success', 'Product updated');
    }

    public function createMartCategory()
    {
        abort_unless(Schema::hasTable('categories'), 404);

        return view('dashboards.super-admin.mart-category-create');
    }

    public function storeMartCategory(Request $request)
    {
        abort_unless(Schema::hasTable('categories'), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $slug = trim((string) ($validated['slug'] ?? ''));
        $slug = $slug === '' ? Str::slug($validated['name']) : Str::slug($slug);
        if ($slug === '') {
            $slug = 'category';
        }
        $baseSlug = $slug;
        $i = 0;
        while (Category::where('slug', $slug)->exists() && $i < 50) {
            $slug = $baseSlug.'-'.random_int(1000, 9999);
            $i++;
        }

        $imagePath = null;
        if ($request->file('image')) {
            $imagePath = Storage::disk('public')->putFile('categories', $request->file('image'));
        }

        $data = [
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ];
        if (Schema::hasColumn('categories', 'display_order')) {
            $data['display_order'] = $validated['display_order'] ?? 0;
        }
        if (Schema::hasColumn('categories', 'is_active')) {
            $data['is_active'] = (bool) ($validated['is_active'] ?? true);
        }
        if ($imagePath !== null && Schema::hasColumn('categories', 'image')) {
            $data['image'] = $imagePath;
        }
        if (Schema::hasColumn('categories', 'market')) {
            $data['market'] = 'mart';
        }

        Category::create($data);

        Cache::forget('mart:navigation:v1');

        return redirect()->route('dashboard.admin.mart.index')->with('success', 'Category created');
    }

    public function editMartCategory(Category $category)
    {
        abort_unless(Schema::hasTable('categories'), 404);

        return view('dashboards.super-admin.mart-category-edit', compact('category'));
    }

    public function updateMartCategory(Request $request, Category $category)
    {
        abort_unless(Schema::hasTable('categories'), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $slug = trim((string) ($validated['slug'] ?? ''));
        $slug = $slug === '' ? Str::slug($validated['name']) : Str::slug($slug);
        if ($slug === '') {
            $slug = 'category';
        }
        $baseSlug = $slug;
        $i = 0;
        while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists() && $i < 50) {
            $slug = $baseSlug.'-'.random_int(1000, 9999);
            $i++;
        }

        $updates = [
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ];

        if (Schema::hasColumn('categories', 'display_order')) {
            $updates['display_order'] = $validated['display_order'] ?? 0;
        }
        if (Schema::hasColumn('categories', 'is_active')) {
            $updates['is_active'] = (bool) ($validated['is_active'] ?? ($category->is_active ?? true));
        }
        if (Schema::hasColumn('categories', 'market')) {
            $updates['market'] = 'store';
        }
        if ($request->file('image') && Schema::hasColumn('categories', 'image')) {
            $updates['image'] = Storage::disk('public')->putFile('categories', $request->file('image'));
        }
        if (Schema::hasColumn('categories', 'market')) {
            $updates['market'] = 'mart';
        }

        $category->update($updates);

        Cache::forget('mart:navigation:v1');

        return redirect()->route('dashboard.admin.mart.index')->with('success', 'Category updated');
    }

    public function createMartSubcategory(Request $request)
    {
        abort_unless(Schema::hasTable('subcategories') && Schema::hasTable('categories'), 404);

        $categories = Category::query()
            ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', 'mart'))
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        $prefillCategoryId = $request->query('category_id');

        return view('dashboards.super-admin.mart-subcategory-create', compact('categories', 'prefillCategoryId'));
    }

    public function storeMartSubcategory(Request $request)
    {
        abort_unless(Schema::hasTable('subcategories') && Schema::hasTable('categories'), 404);

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $category = Category::query()->findOrFail((int) $validated['category_id']);
        if (Schema::hasColumn('categories', 'market') && (string) ($category->market ?? '') !== 'mart') {
            return back()->withErrors(['category_id' => 'Category must be Mart'])->withInput();
        }

        $slug = trim((string) ($validated['slug'] ?? ''));
        $slug = $slug === '' ? Str::slug($validated['name']) : Str::slug($slug);
        if ($slug === '') {
            $slug = 'subcategory';
        }

        $baseSlug = $slug;
        $i = 0;
        while (Subcategory::where('category_id', $category->id)->where('slug', $slug)->exists() && $i < 50) {
            $slug = $baseSlug.'-'.random_int(1000, 9999);
            $i++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('subcategories', 'public');
        }

        Subcategory::create([
            'category_id' => $category->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'image' => $imagePath,
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        Cache::forget('mart:navigation:v1');

        return redirect()->route('dashboard.admin.mart.index', ['category_id' => $category->id])->with('success', 'Subcategory created');
    }

    public function editMartSubcategory(Subcategory $subcategory)
    {
        abort_unless(Schema::hasTable('subcategories') && Schema::hasTable('categories'), 404);

        $categories = Category::query()
            ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', 'mart'))
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('dashboards.super-admin.mart-subcategory-edit', compact('subcategory', 'categories'));
    }

    public function updateMartSubcategory(Request $request, Subcategory $subcategory)
    {
        abort_unless(Schema::hasTable('subcategories') && Schema::hasTable('categories'), 404);

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_image' => 'nullable|boolean',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $category = Category::query()->findOrFail((int) $validated['category_id']);
        if (Schema::hasColumn('categories', 'market') && (string) ($category->market ?? '') !== 'mart') {
            return back()->withErrors(['category_id' => 'Category must be Mart'])->withInput();
        }

        $slug = trim((string) ($validated['slug'] ?? ''));
        $slug = $slug === '' ? Str::slug($validated['name']) : Str::slug($slug);
        if ($slug === '') {
            $slug = 'subcategory';
        }

        $baseSlug = $slug;
        $i = 0;
        while (Subcategory::where('category_id', $category->id)->where('slug', $slug)->where('id', '!=', $subcategory->id)->exists() && $i < 50) {
            $slug = $baseSlug.'-'.random_int(1000, 9999);
            $i++;
        }

        $imagePath = $subcategory->image;
        
        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = null;
        }
        
        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('subcategories', 'public');
        }

        $subcategory->update([
            'category_id' => $category->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'image' => $imagePath,
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        if (Schema::hasColumn('products', 'subcategory_id')) {
            Product::query()->where('subcategory_id', $subcategory->id)->update(['category_id' => $category->id]);
        }

        Cache::forget('mart:navigation:v1');

        return redirect()->route('dashboard.admin.mart.index', ['category_id' => $category->id])->with('success', 'Subcategory updated');
    }

    public function deleteMartSubcategory(Subcategory $subcategory)
    {
        abort_unless(Schema::hasTable('subcategories'), 404);

        $categoryId = (int) ($subcategory->category_id ?? 0);
        $subcategory->delete();

        Cache::forget('mart:navigation:v1');

        return redirect()->route('dashboard.admin.mart.index', ['category_id' => $categoryId ?: null])->with('success', 'Subcategory deleted');
    }

    public function reorderMartCategories(Request $request)
    {
        abort_unless(Schema::hasTable('categories') && Schema::hasColumn('categories', 'display_order'), 404);

        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:categories,id',
        ]);

        foreach (array_values($validated['order']) as $i => $id) {
            Category::query()->whereKey((int) $id)->update(['display_order' => $i]);
        }

        Cache::forget('mart:navigation:v1');

        return response()->json(['success' => true]);
    }

    public function reorderMartSubcategories(Request $request, Category $category)
    {
        abort_unless(Schema::hasTable('subcategories') && Schema::hasColumn('subcategories', 'display_order'), 404);

        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:subcategories,id',
        ]);

        $allowed = Subcategory::query()->where('category_id', $category->id)->pluck('id')->map(fn ($x) => (int) $x)->all();
        $allowedSet = array_fill_keys($allowed, true);

        $filtered = array_values(array_filter($validated['order'], fn ($id) => isset($allowedSet[(int) $id])));

        foreach ($filtered as $i => $id) {
            Subcategory::query()->whereKey((int) $id)->update(['display_order' => $i]);
        }

        Cache::forget('mart:navigation:v1');

        return response()->json(['success' => true]);
    }

    public function bulkMoveMartProducts(Request $request)
    {
        abort_unless(Schema::hasTable('products') && Schema::hasTable('subcategories') && Schema::hasColumn('products', 'subcategory_id'), 404);

        $validated = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id',
            'target_subcategory_id' => 'required|integer|exists:subcategories,id',
        ]);

        $target = Subcategory::query()->with('category')->findOrFail((int) $validated['target_subcategory_id']);
        if (Schema::hasColumn('categories', 'market') && (string) (optional($target->category)->market ?? '') !== 'mart') {
            return back()->with('error', 'Target subcategory must belong to Mart');
        }

        Product::query()
            ->whereIn('id', array_map('intval', $validated['product_ids']))
            ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', 'mart'))
            ->update([
                'subcategory_id' => $target->id,
                'category_id' => $target->category_id,
            ]);

        Cache::forget('mart:navigation:v1');

        return back()->with('success', 'Products moved');
    }

    public function toggleGiftActive(Gift $gift)
    {
        abort_unless(Schema::hasTable('gifts') && Schema::hasColumn('gifts', 'is_active'), 404);

        $gift->update([
            'is_active' => ! (bool) $gift->is_active,
        ]);

        return back()->with('success', 'Gift status updated');
    }

    public function toggleGiftFeatured(Gift $gift)
    {
        abort_unless(Schema::hasTable('gifts') && Schema::hasColumn('gifts', 'is_featured'), 404);

        $gift->update([
            'is_featured' => ! (bool) $gift->is_featured,
        ]);

        return back()->with('success', 'Gift featured status updated');
    }

    public function deleteGift(Gift $gift)
    {
        abort_unless(Schema::hasTable('gifts'), 404);

        $gift->delete();

        return back()->with('success', 'Gift deleted');
    }

    public function toggleGiftBoxActive(GiftBox $box)
    {
        abort_unless(Schema::hasTable('gift_boxes') && Schema::hasColumn('gift_boxes', 'is_active'), 404);

        $box->update([
            'is_active' => ! (bool) ($box->is_active ?? false),
        ]);

        return back()->with('success', 'Gift box status updated');
    }

    public function deleteGiftBox(GiftBox $box)
    {
        abort_unless(Schema::hasTable('gift_boxes'), 404);

        $box->delete();

        return back()->with('success', 'Gift box deleted');
    }

    public function toggleGiftWrappingActive(GiftWrapping $wrapping)
    {
        abort_unless(Schema::hasTable('gift_wrappings') && Schema::hasColumn('gift_wrappings', 'is_active'), 404);

        $wrapping->update([
            'is_active' => ! (bool) ($wrapping->is_active ?? false),
        ]);

        return back()->with('success', 'Wrapping status updated');
    }

    public function deleteGiftWrapping(GiftWrapping $wrapping)
    {
        abort_unless(Schema::hasTable('gift_wrappings'), 404);

        $wrapping->delete();

        return back()->with('success', 'Wrapping deleted');
    }

    public function toggleGiftRibbonActive(GiftRibbon $ribbon)
    {
        abort_unless(Schema::hasTable('gift_ribbons') && Schema::hasColumn('gift_ribbons', 'is_active'), 404);

        $ribbon->update([
            'is_active' => ! (bool) ($ribbon->is_active ?? false),
        ]);

        return back()->with('success', 'Ribbon status updated');
    }

    public function deleteGiftRibbon(GiftRibbon $ribbon)
    {
        abort_unless(Schema::hasTable('gift_ribbons'), 404);

        $ribbon->delete();

        return back()->with('success', 'Ribbon deleted');
    }

    public function toggleGiftCardActive(GiftCard $card)
    {
        abort_unless(Schema::hasTable('gift_cards') && Schema::hasColumn('gift_cards', 'is_active'), 404);

        $card->update([
            'is_active' => ! (bool) ($card->is_active ?? false),
        ]);

        return back()->with('success', 'Card status updated');
    }

    public function deleteGiftCard(GiftCard $card)
    {
        abort_unless(Schema::hasTable('gift_cards'), 404);

        $card->delete();

        return back()->with('success', 'Card deleted');
    }

    public function toggleGiftFillerActive(GiftFiller $filler)
    {
        abort_unless(Schema::hasTable('gift_fillers') && Schema::hasColumn('gift_fillers', 'is_active'), 404);

        $filler->update([
            'is_active' => ! (bool) ($filler->is_active ?? false),
        ]);

        return back()->with('success', 'Filler status updated');
    }

    public function deleteGiftFiller(GiftFiller $filler)
    {
        abort_unless(Schema::hasTable('gift_fillers'), 404);

        $filler->delete();

        return back()->with('success', 'Filler deleted');
    }

    public function toggleMartProductActive(Product $product)
    {
        abort_unless(Schema::hasTable('products'), 404);

        if (Schema::hasColumn('products', 'is_active')) {
            $product->update([
                'is_active' => ! (bool) ($product->is_active ?? false),
            ]);
        } elseif (Schema::hasColumn('products', 'status')) {
            $next = ($product->status ?? null) === 'active' ? 'inactive' : 'active';
            $product->update([
                'status' => $next,
            ]);
        } else {
            abort(404);
        }

        return back()->with('success', 'Product status updated');
    }

    public function toggleMartProductFeatured(Product $product)
    {
        abort_unless(Schema::hasTable('products') && Schema::hasColumn('products', 'is_featured'), 404);

        $product->update([
            'is_featured' => ! (bool) ($product->is_featured ?? false),
        ]);

        Cache::forget('mart:navigation:v1');

        return back()->with('success', 'Product featured status updated');
    }

    public function deleteMartProduct(Product $product)
    {
        abort_unless(Schema::hasTable('products'), 404);

        $product->delete();

        Cache::forget('mart:navigation:v1');

        return back()->with('success', 'Product deleted');
    }

    public function manageDailyPrices()
    {
        abort_unless(Schema::hasTable('products'), 404);
        $categories = collect();
        
        // Get all mart categories that are fruits or vegetables
        // Look for categories with names or slugs containing these keywords
        if (Schema::hasTable('categories')) {
            $categories = Category::query()
                ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', 'mart'))
                ->where(function($q) {
                    // Match by slug or name containing fruit/vegetable keywords in Arabic or English
                    $q->where('name', 'like', '%فواكه%')
                      ->orWhere('name', 'like', '%خضروات%')
                      ->orWhere('name', 'like', '%خضار%')
                      ->orWhere('name', 'like', '%fruit%')
                      ->orWhere('name', 'like', '%vegetable%')
                      ->orWhere('slug', 'like', '%fruit%')
                      ->orWhere('slug', 'like', '%vegetable%')
                      ->orWhere('slug', 'like', '%khdroaat%')
                      ->orWhere('slug', 'like', '%khodraat%');
                })
                ->orderBy('display_order')
                ->orderBy('name')
                ->get();
        }
        
        $allowedIds = $categories->pluck('id')->all();
        $products = Product::query()
            ->with(['category', 'attributes'])
            ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', 'mart'))
            ->when(!empty($allowedIds), fn ($q) => $q->whereIn('category_id', $allowedIds))
            ->orderByRaw('category_id is null, category_id')
            ->orderBy('name')
            ->get();
        $productsPayload = $products->map(function ($p) {
            $rel = $p->relationLoaded('attributes') ? $p->getRelation('attributes') : null;
            $attrs = ($rel instanceof \Illuminate\Support\Collection)
                ? $rel->pluck('value', 'name')
                : $p->attributes()->pluck('value', 'name');
            return [
                'id' => $p->id,
                'name' => $p->name,
                'category_id' => $p->category_id,
                'category_name' => optional($p->category)->name,
                'image' => $p->image,
                'price' => $p->price,
                'discount_price' => $p->discount_price,
                'unit' => $attrs['unit'] ?? '',
                'origin' => $attrs['origin'] ?? '',
                'is_active' => (bool) ($p->is_active ?? true),
            ];
        })->values()->all();
        return view('dashboards.super-admin.mart-daily-prices', compact('productsPayload','categories'));
    }

    public function saveDailyPrices(Request $request)
    {
        abort_unless(Schema::hasTable('products'), 404);
        $raw = $request->input('items');
        $items = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);
        if (!is_array($items)) {
            return back()->with('error', 'بيانات غير صالحة');
        }
        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                if (!isset($item['id']) || !is_numeric($item['id'])) {
                    continue;
                }
                $product = Product::find($item['id']);
                if (!$product) {
                    continue;
                }
                if (Schema::hasColumn('products', 'market') && ($product->market ?? null) !== 'mart') {
                    continue;
                }
                $updates = [];
                if (Schema::hasColumn('products', 'price') && array_key_exists('price', $item)) {
                    $updates['price'] = is_numeric($item['price']) ? $item['price'] + 0 : null;
                }
                if (Schema::hasColumn('products', 'discount_price') && array_key_exists('discount_price', $item)) {
                    $updates['discount_price'] = is_numeric($item['discount_price']) ? $item['discount_price'] + 0 : null;
                } elseif (Schema::hasColumn('products', 'discount_price') && array_key_exists('price', $item)) {
                    $updates['discount_price'] = $product->price;
                }
                if (Schema::hasColumn('products', 'is_active') && array_key_exists('is_active', $item)) {
                    $updates['is_active'] = (bool) $item['is_active'];
                }
                if (!empty($updates)) {
                    $product->update($updates);
                }
                if (array_key_exists('unit', $item)) {
                    $product->attributes()->updateOrCreate(['name'=>'unit'], ['value'=>$item['unit'] ?? '']);
                }
                if (array_key_exists('origin', $item)) {
                    $product->attributes()->updateOrCreate(['name'=>'origin'], ['value'=>$item['origin'] ?? '']);
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'فشل حفظ الأسعار: '.$e->getMessage());
        }
        return redirect()->route('dashboard.admin.mart.daily-prices.manage')->with('success','تم حفظ الأسعار وتحديث حالة المنتجات');
    }

    public function toggleMartCategoryActive(Category $category)
    {
        abort_unless(Schema::hasTable('categories') && Schema::hasColumn('categories', 'is_active'), 404);

        $category->update([
            'is_active' => ! (bool) ($category->is_active ?? false),
        ]);

        Cache::forget('mart:navigation:v1');

        return back()->with('success', 'Category status updated');
    }

    public function deleteMartCategory(Category $category)
    {
        abort_unless(Schema::hasTable('categories'), 404);

        $category->delete();

        Cache::forget('mart:navigation:v1');

        return back()->with('success', 'Category deleted');
    }

    public function attendance(Request $request)
    {
        abort_unless(Schema::hasTable('attendance'), 404);

        $date = $request->date ? \Illuminate\Support\Carbon::parse($request->date)->toDateString() : today()->toDateString();

        $rows = Attendance::query()
            ->with(['employee'])
            ->whereDate('date', $date)
            ->when($request->search, function ($q, $search) {
                $q->whereHas('employee', function ($eq) use ($search) {
                    $eq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('check_in is null, check_in asc')
            ->paginate(50)
            ->withQueryString();

        $checkedIn = Attendance::query()
            ->whereDate('date', $date)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->count();

        return view('dashboards.super-admin.attendance', compact('rows', 'date', 'checkedIn'));
    }

    /**
     * Create new role
     */
    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles',
            'display_name' => 'required|string',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create($request->only(['name', 'display_name', 'description']));

            // Assign permissions
            foreach ($request->permissions as $permissionId) {
                DB::table('role_permissions')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('dashboard.admin.roles')
                ->with('success', 'Role created successfully!');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Failed to create role: '.$e->getMessage());
        }
    }

    /**
     * Platform Analytics
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30d');
        $days = $this->getPeriodDays($period);
        $startDate = now()->subDays($days);

        // Real database metrics
        $metrics = [
            // User metrics
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'new_users_period' => User::where('created_at', '>=', $startDate)->count(),
            'users_growth' => $this->calculateGrowthRate(
                User::where('created_at', '>=', $startDate)->count(),
                User::where('created_at', '>=', now()->subDays($days * 2))
                    ->where('created_at', '<', $startDate)->count()
            ),

            // Order metrics
            'total_orders' => Order::count(),
            'orders_period' => Order::where('created_at', '>=', $startDate)->count(),
            'orders_growth' => $this->calculateGrowthRate(
                Order::where('created_at', '>=', $startDate)->count(),
                Order::where('created_at', '>=', now()->subDays($days * 2))
                    ->where('created_at', '<', $startDate)->count()
            ),

            // Revenue metrics
            'total_revenue' => $this->sumOrderTotal(
                Order::where('payment_status', 'paid')
            ),
            'revenue_period' => $this->sumOrderTotal(
                Order::where('payment_status', 'paid')
                    ->where('created_at', '>=', $startDate)
            ),
            'revenue_growth' => $this->calculateGrowthRate(
                $this->sumOrderTotal(
                    Order::where('payment_status', 'paid')
                        ->where('created_at', '>=', $startDate)
                ),
                $this->sumOrderTotal(
                    Order::where('payment_status', 'paid')
                        ->where('created_at', '>=', now()->subDays($days * 2))
                        ->where('created_at', '<', $startDate)
                )
            ),

            // Store metrics
            'total_stores' => Store::count(),
            'active_stores' => Store::where('status', 'active')->count(),
            'stores_growth' => $this->calculateGrowthRate(
                Store::where('created_at', '>=', $startDate)->count(),
                Store::where('created_at', '>=', now()->subDays($days * 2))
                    ->where('created_at', '<', $startDate)->count()
            ),

            // Performance metrics
            'avg_order_value' => $this->avgOrderTotal(
                Order::where('payment_status', 'paid')
            ),
            'conversion_rate' => $this->calculateConversionRate(),
            'customer_ltv' => $this->calculateCustomerLTV(),
            'cart_abandonment' => $this->calculateCartAbandonmentRate(),
            'return_rate' => $this->calculateReturnRate(),
        ];

        // Chart data
        $chartData = [
            'revenue_chart' => $this->getRevenueChartData($days),
            'orders_chart' => $this->getOrdersChartData($days),
            'users_chart' => $this->getUsersChartData($days),
            'geographic_data' => $this->getGeographicData(),
        ];

        // Top performing data
        $topData = [
            'top_products' => $this->getTopProducts($days),
            'top_stores' => $this->getTopStores($days),
            'top_customers' => $this->getTopCustomers($days),
            'recent_activities' => $this->getRecentActivities(),
        ];

        return view('dashboards.super-admin.analytics', compact('metrics', 'chartData', 'topData', 'period'));
    }

    /**
     * Audit Logs
     */
    public function auditLogs(Request $request)
    {
        $logs = AuditLog::with(['user'])
            ->when($request->user_id, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->when($request->action, function ($query, $action) {
                $query->where('action', $action);
            })
            ->when($request->model_type, function ($query, $modelType) {
                $query->where('model_type', $modelType);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $actions = AuditLog::distinct()->pluck('action');
        $modelTypes = AuditLog::distinct()->pluck('model_type');

        return view('dashboards.super-admin.audit-logs', compact('logs', 'actions', 'modelTypes'));
    }

    /**
     * System Settings
     */
    public function settings()
    {
        $settings = SystemSetting::all()->groupBy(function ($setting) {
            return explode('.', $setting->key)[0];
        });

        return view('dashboards.super-admin.settings', compact('settings'));
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        foreach ($request->settings as $key => $value) {
            SystemSetting::where('key', $key)->update(['value' => $value]);
        }

        // Clear cache
        Cache::flush();

        return redirect()->route('dashboard.admin.settings')
            ->with('success', 'Settings updated successfully!');
    }

    /**
     * Emergency Override Functions
     */
    public function emergencyUnlockUser(User $user)
    {
        $user->update([
            'status' => 'active',
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);

        // Log emergency action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'emergency_unlock',
            'model_type' => 'User',
            'model_id' => $user->id,
            'new_values' => ['emergency_unlock' => true],
            'ip_address' => request()->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'User unlocked successfully']);
    }

    public function emergencyForceRefund(Order $order)
    {
        DB::beginTransaction();
        try {
            // Create refund transaction
            FinancialTransaction::create([
                'transaction_id' => 'emergency_refund_'.$order->id.'_'.time(),
                'order_id' => $order->id,
                'user_id' => $order->customer_id,
                'store_id' => $order->store_id,
                'type' => 'refund',
                'amount' => $order->total,
                'status' => 'completed',
                'description' => 'Emergency refund by Super Admin',
                'metadata' => ['emergency_refund' => true, 'admin_id' => auth()->id()],
            ]);

            // Update order status
            $order->update([
                'status' => 'refunded',
                'payment_status' => 'refunded',
                'admin_notes' => 'Emergency refund processed by Super Admin',
            ]);

            // Log emergency action
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'emergency_refund',
                'model_type' => 'Order',
                'model_id' => $order->id,
                'new_values' => ['emergency_refund' => true, 'amount' => $order->total],
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Emergency refund processed']);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['success' => false, 'message' => 'Failed to process refund']);
        }
    }

    public function toggleMaintenanceMode(Request $request)
    {
        $maintenanceMode = $request->boolean('maintenance_mode');

        SystemSetting::where('key', 'maintenance.mode')->update(['value' => $maintenanceMode ? 'true' : 'false']);

        // Log maintenance mode change
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'maintenance_mode_toggle',
            'model_type' => 'System',
            'new_values' => ['maintenance_mode' => $maintenanceMode],
            'ip_address' => request()->ip(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode '.($maintenanceMode ? 'enabled' : 'disabled'),
            ]);
        }

        return back()->with('success', 'Maintenance mode '.($maintenanceMode ? 'enabled' : 'disabled'));
    }

    /**
     * Helper Methods
     */
    private function getUserGrowthRate()
    {
        $currentMonth = User::whereMonth('created_at', now()->month)->count();
        $lastMonth = User::whereMonth('created_at', now()->subMonth()->month)->count();

        return $lastMonth > 0 ? round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1) : 100;
    }

    private function getRevenueGrowthRate()
    {
        $currentMonth = $this->sumOrderTotal(
            Order::where('payment_status', 'paid')->whereMonth('created_at', now()->month)
        );
        $lastMonth = $this->sumOrderTotal(
            Order::where('payment_status', 'paid')->whereMonth('created_at', now()->subMonth()->month)
        );

        return $lastMonth > 0 ? round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1) : 100;
    }

    private function getOrderGrowthRate()
    {
        $currentMonth = Order::whereMonth('created_at', now()->month)->count();
        $lastMonth = Order::whereMonth('created_at', now()->subMonth()->month)->count();

        return $lastMonth > 0 ? round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1) : 100;
    }

    private function getActiveSystemAlerts()
    {
        return DB::table('system_alerts')
            ->where('status', 'active')
            ->where('severity', 'critical')
            ->count();
    }

    private function getTopPerformingStores()
    {
        $orderTotalExpr = Schema::hasColumn('orders', 'total_amount')
            ? 'orders.total_amount'
            : (Schema::hasColumn('orders', 'total') ? 'orders.total' : 'order_items.subtotal');

        return Store::select([
            'stores.id',
            'stores.name',
            'stores.slug',
            'stores.description',
            'stores.logo',
            'stores.phone',
            'stores.email',
            'stores.status',
            'stores.total_sales',
            'stores.created_at',
        ])
            ->selectRaw('SUM(CASE WHEN orders.payment_status = ? AND MONTH(orders.created_at) = ? THEN '.$orderTotalExpr.' ELSE 0 END) as orders_sum_total', ['paid', now()->month])
            ->leftJoin('products', 'stores.id', '=', 'products.store_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->groupBy([
                'stores.id',
                'stores.name',
                'stores.slug',
                'stores.description',
                'stores.logo',
                'stores.phone',
                'stores.email',
                'stores.status',
                'stores.total_sales',
                'stores.created_at',
            ])
            ->orderBy('orders_sum_total', 'desc')
            ->take(5)
            ->get();
    }

    private function getUserAnalytics($period)
    {
        $days = $this->getPeriodDays($period);

        return [
            'new_users' => User::where('created_at', '>=', now()->subDays($days))->count(),
            'active_users' => User::where('status', 'active')->count(),
            'user_retention' => $this->calculateUserRetention($days),
            'user_growth_trend' => $this->getUserGrowthTrend($days),
        ];
    }

    private function getRevenueAnalytics($period)
    {
        $days = $this->getPeriodDays($period);

        return [
            'total_revenue' => $this->sumOrderTotal(
                Order::where('payment_status', 'paid')
                    ->where('created_at', '>=', now()->subDays($days))
            ),
            'commission_revenue' => FinancialTransaction::where('type', 'commission')
                ->where('status', 'completed')
                ->where('created_at', '>=', now()->subDays($days))
                ->sum('amount'),
            'revenue_trend' => $this->getRevenueTrend($days),
        ];
    }

    private function getOrderAnalytics($period)
    {
        $days = $this->getPeriodDays($period);

        return [
            'total_orders' => Order::where('created_at', '>=', now()->subDays($days))->count(),
            'completed_orders' => Order::where('status', 'delivered')
                ->where('created_at', '>=', now()->subDays($days))->count(),
            'avg_order_value' => $this->avgOrderTotal(
                Order::where('payment_status', 'paid')
                    ->where('created_at', '>=', now()->subDays($days))
            ),
            'order_trend' => $this->getOrderTrend($days),
        ];
    }

    private function getStoreAnalytics($period)
    {
        $days = $this->getPeriodDays($period);

        return [
            'new_stores' => Store::where('created_at', '>=', now()->subDays($days))->count(),
            'active_stores' => Store::where('status', 'active')->count(),
            'store_performance' => $this->getStorePerformance($days),
        ];
    }

    private function getGeographicAnalytics($period)
    {
        // Mock data - would implement with actual geographic tracking
        return [
            'top_countries' => [
                'United States' => 45,
                'Canada' => 23,
                'United Kingdom' => 18,
                'Australia' => 14,
            ],
            'revenue_by_region' => [
                'North America' => 68000,
                'Europe' => 45000,
                'Asia Pacific' => 32000,
                'Others' => 15000,
            ],
        ];
    }

    private function calculateUserRetention($days)
    {
        // Mock calculation - would implement actual retention logic
        return 75.5; // 75.5% retention rate
    }

    private function getUserGrowthTrend($days)
    {
        return User::where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getRevenueTrend($days)
    {
        $sumColumn = Schema::hasColumn('orders', 'total_amount')
            ? 'total_amount'
            : (Schema::hasColumn('orders', 'total') ? 'total' : null);
        $sumExpr = $sumColumn ? $sumColumn : implode(' + ', array_filter([
            Schema::hasColumn('orders', 'subtotal') ? 'subtotal' : null,
            Schema::hasColumn('orders', 'delivery_cost') ? 'delivery_cost' : null,
            Schema::hasColumn('orders', 'service_fee') ? 'service_fee' : null,
        ]));

        return Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, SUM('.$sumExpr.') as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getOrderTrend($days)
    {
        return Order::where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getStorePerformance($days)
    {
        $sumColumn = Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : 'total';

        return Store::withSum(['orders' => function ($query) use ($days) {
            $query->where('payment_status', 'paid')
                ->where('created_at', '>=', now()->subDays($days));
        }], $sumColumn)
            ->orderBy($sumColumn === 'total_amount' ? 'orders_sum_total_amount' : 'orders_sum_total', 'desc')
            ->take(10)
            ->get();
    }

    private function getPeriodDays($period)
    {
        switch ($period) {
            case '7d': return 7;
            case '30d': return 30;
            case '90d': return 90;
            case '1y': return 365;
            default: return 30;
        }
    }

    private function calculateGrowthRate($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function calculateConversionRate()
    {
        $totalUsers = User::count();
        $usersWithOrders = User::whereHas('orders')->count();

        return $totalUsers > 0 ? round(($usersWithOrders / $totalUsers) * 100, 2) : 0;
    }

    private function calculateCustomerLTV()
    {
        $avgOrderValue = $this->avgOrderTotal(Order::where('payment_status', 'paid')) ?? 0;

        // Calculate average orders per customer properly
        $customersWithOrders = User::whereHas('orders')->get();
        if ($customersWithOrders->isEmpty()) {
            return 0;
        }

        $totalOrders = $customersWithOrders->sum(function ($user) {
            return $user->orders()->count();
        });

        $avgOrdersPerCustomer = $totalOrders / $customersWithOrders->count();

        return round($avgOrderValue * $avgOrdersPerCustomer, 2);
    }

    private function calculateCartAbandonmentRate()
    {
        // Mock calculation - would need cart tracking
        return 68.5;
    }

    private function calculateReturnRate()
    {
        // Mock calculation - would need return tracking
        return 2.8;
    }

    private function getRevenueChartData($days)
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenue = $this->sumOrderTotal(
                Order::where('payment_status', 'paid')->whereDate('created_at', $date)
            );
            $data[] = [
                'date' => $date,
                'revenue' => $revenue,
            ];
        }

        return $data;
    }

    private function getOrdersChartData($days)
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $orders = Order::whereDate('created_at', $date)->count();
            $data[] = [
                'date' => $date,
                'orders' => $orders,
            ];
        }

        return $data;
    }

    private function getUsersChartData($days)
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $users = User::whereDate('created_at', $date)->count();
            $data[] = [
                'date' => $date,
                'users' => $users,
            ];
        }

        return $data;
    }

    private function sumOrderTotal($query)
    {
        if (Schema::hasColumn('orders', 'total_amount')) {
            return (float) $query->sum('total_amount');
        }
        if (Schema::hasColumn('orders', 'total')) {
            return (float) $query->sum('total');
        }
        $parts = [];
        if (Schema::hasColumn('orders', 'subtotal')) {
            $parts[] = 'subtotal';
        }
        if (Schema::hasColumn('orders', 'delivery_cost')) {
            $parts[] = 'delivery_cost';
        }
        if (Schema::hasColumn('orders', 'service_fee')) {
            $parts[] = 'service_fee';
        }
        if (! $parts) {
            return 0;
        }

        return (float) $query->selectRaw('SUM('.implode(' + ', $parts).') as agg')->value('agg') ?? 0;
    }

    private function avgOrderTotal($query)
    {
        if (Schema::hasColumn('orders', 'total_amount')) {
            return (float) $query->avg('total_amount');
        }
        if (Schema::hasColumn('orders', 'total')) {
            return (float) $query->avg('total');
        }
        $parts = [];
        if (Schema::hasColumn('orders', 'subtotal')) {
            $parts[] = 'subtotal';
        }
        if (Schema::hasColumn('orders', 'delivery_cost')) {
            $parts[] = 'delivery_cost';
        }
        if (Schema::hasColumn('orders', 'service_fee')) {
            $parts[] = 'service_fee';
        }
        if (! $parts) {
            return 0;
        }

        return (float) $query->selectRaw('AVG('.implode(' + ', $parts).') as agg')->value('agg') ?? 0;
    }

    private function getGeographicData()
    {
        // Get user distribution by country/region
        return User::selectRaw('COALESCE(country, "Unknown") as country, COUNT(*) as count')
            ->groupBy('country')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();
    }

    private function getTopProducts($days)
    {
        $imageSelect = Schema::hasColumn('products', 'image') ? 'products.image as image' : 'NULL as image';
        $groupByImage = Schema::hasColumn('products', 'image') ? ', products.image' : '';

        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', now()->subDays($days))
            ->selectRaw('
                COALESCE(products.name, order_items.product_name) as name,
                '.$imageSelect.',
                SUM(order_items.quantity) as total_sold,
                SUM(order_items.total_price) as total_revenue
            ')
            ->groupByRaw('order_items.product_id, products.name, order_items.product_name'.$groupByImage)
            ->orderByDesc('total_revenue')
            ->take(10)
            ->get();
    }

    private function getTopStores($days)
    {
        $sumColumn = Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : 'total';

        return Store::withSum(['orders' => function ($query) use ($days) {
            $query->where('payment_status', 'paid')
                ->where('created_at', '>=', now()->subDays($days));
        }], $sumColumn)
            ->withCount(['orders' => function ($query) use ($days) {
                $query->where('created_at', '>=', now()->subDays($days));
            }])
            ->orderBy($sumColumn === 'total_amount' ? 'orders_sum_total_amount' : 'orders_sum_total', 'desc')
            ->take(10)
            ->get();
    }

    private function getTopCustomers($days)
    {
        $sumColumn = Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : 'total';

        return User::withSum(['orders' => function ($query) use ($days) {
            $query->where('payment_status', 'paid')
                ->where('created_at', '>=', now()->subDays($days));
        }], $sumColumn)
            ->withCount(['orders' => function ($query) use ($days) {
                $query->where('created_at', '>=', now()->subDays($days));
            }])
            ->having($sumColumn === 'total_amount' ? 'orders_sum_total_amount' : 'orders_sum_total', '>', 0)
            ->orderBy($sumColumn === 'total_amount' ? 'orders_sum_total_amount' : 'orders_sum_total', 'desc')
            ->take(10)
            ->get();
    }

    private function getRecentActivities(int $limit = 10)
    {
        $activities = collect();

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'order',
                    'title' => 'New Order #'.$order->order_number,
                    'description' => 'Order placed by '.($order->user->name ?? 'Guest'),
                    'amount' => $order->total_amount ?? $order->total ?? (($order->subtotal ?? 0) + ($order->delivery_cost ?? 0) + ($order->service_fee ?? 0)),
                    'time' => $order->created_at,
                    'icon' => 'fa-shopping-cart',
                    'color' => 'text-blue-600',
                ];
            });

        // Recent users
        $recentUsers = User::latest()
            ->take(3)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'title' => 'New User Registration',
                    'description' => $user->name.' joined the platform',
                    'amount' => null,
                    'time' => $user->created_at,
                    'icon' => 'fa-user-plus',
                    'color' => 'text-green-600',
                ];
            });

        // Recent stores
        $recentStores = Store::latest()
            ->take(2)
            ->get()
            ->map(function ($store) {
                return [
                    'type' => 'store',
                    'title' => 'New Store Created',
                    'description' => $store->name.' opened their store',
                    'amount' => null,
                    'time' => $store->created_at,
                    'icon' => 'fa-store',
                    'color' => 'text-purple-600',
                ];
            });

        return $activities
            ->merge($recentOrders)
            ->merge($recentUsers)
            ->merge($recentStores)
            ->sortByDesc('time')
            ->take($limit)
            ->values();
    }

    private function getOrderStatusBreakdown(int $days): array
    {
        $rows = Order::where('created_at', '>=', now()->subDays($days))
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->orderByRaw('COUNT(*) DESC')
            ->get();

        $labels = [];
        $values = [];
        foreach ($rows as $row) {
            $labels[] = (string) ($row->status ?? 'unknown');
            $values[] = (int) ($row->count ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function getGeoOrderPoints(int $days): array
    {
        $rows = Order::where('created_at', '>=', now()->subDays($days))
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw('ROUND(latitude, 2) as lat, ROUND(longitude, 2) as lng, COUNT(*) as cnt')
            ->groupByRaw('ROUND(latitude, 2), ROUND(longitude, 2)')
            ->orderByDesc('cnt')
            ->take(100)
            ->get();

        return $rows->map(function ($r) {
            return [
                'x' => (float) $r->lng,
                'y' => (float) $r->lat,
                'r' => max(3, min(18, (int) $r->cnt)),
                'count' => (int) $r->cnt,
            ];
        })->all();
    }

    /**
     * Get activity feed for dashboard
     */
    public function getActivityFeed(Request $request)
    {
        $activities = \App\Models\ActivityFeed::forDashboard('admin')
            ->with(['actor', 'target'])
            ->when($request->type, function ($query, $type) {
                $query->where('activity_type', $type);
            })
            ->when($request->unread_only, function ($query) {
                $query->unread();
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($activities);
    }

    /**
     * Get notifications for dashboard
     */
    public function getNotifications(Request $request)
    {
        $user = auth()->user() ?? auth('employee')->user();

        $notifications = \App\Models\DashboardNotification::forDashboard('admin')
            ->where('user_type', get_class($user))
            ->where('user_id', $user->id)
            ->when($request->unread_only, function ($query) {
                $query->unread();
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($notifications);
    }

    /**
     * Mark notification as read
     */
    public function markNotificationRead(\App\Models\DashboardNotification $notification)
    {
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Bulk operations
     */
    public function bulkOperation(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete,activate,deactivate,export,price_increase',
            'model' => 'required|in:users,orders,products,stores',
            'ids' => 'required|array',
            'ids.*' => 'required|integer',
            'percent' => 'nullable|numeric|min:0',
        ]);

        $action = \App\Models\DashboardQuickAction::create([
            'dashboard_type' => 'admin',
            'action_type' => $request->action,
            'user_type' => get_class(auth()->user() ?? auth('employee')->user()),
            'user_id' => (auth()->user() ?? auth('employee')->user())->id,
            'description' => "Bulk {$request->action} on {$request->model}",
            'status' => 'pending',
            'parameters' => $request->only(['action', 'model', 'ids', 'percent']),
        ]);

        // Process bulk operation based on model
        $affected = 0;
        try {
            switch ($request->model) {
                case 'users':
                    $affected = $this->bulkUserOperation($request->action, $request->ids);
                    break;
                case 'orders':
                    $affected = $this->bulkOrderOperation($request->action, $request->ids);
                    break;
                case 'products':
                    $affected = $this->bulkProductOperation($request->action, $request->ids, (float) ($request->percent ?? 5));
                    break;
                case 'stores':
                    $affected = $this->bulkStoreOperation($request->action, $request->ids);
                    break;
            }

            $action->update([
                'status' => 'completed',
                'affected_records' => $affected,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully {$request->action}d {$affected} records",
                'affected' => $affected,
            ]);

        } catch (\Exception $e) {
            $action->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Bulk operation failed: '.$e->getMessage(),
            ], 500);
        }
    }

    private function bulkUserOperation($action, $ids)
    {
        $users = User::whereIn('id', $ids);

        return match ($action) {
            'activate' => $users->update(['status' => 'active']),
            'deactivate' => $users->update(['status' => 'inactive']),
            'delete' => $users->delete(),
            default => 0,
        };
    }

    private function bulkOrderOperation($action, $ids)
    {
        $orders = Order::whereIn('id', $ids);

        return match ($action) {
            'approve' => $orders->update(['status' => 'confirmed']),
            'reject' => $orders->update(['status' => 'cancelled']),
            default => 0,
        };
    }

    private function bulkProductOperation($action, $ids, $percent = 5.0)
    {
        $products = Product::whereIn('id', $ids);

        switch ($action) {
            case 'activate':
                return $products->update(['is_active' => true]);
            case 'deactivate':
                return $products->update(['is_active' => false]);
            case 'delete':
                return $products->delete();
            case 'price_increase':
                $count = 0;
                DB::beginTransaction();
                try {
                    $multiplier = 1 + ($percent / 100.0);
                    $items = Product::whereIn('id', $ids)->get(['id', 'price']);
                    foreach ($items as $item) {
                        $old = $item->price;
                        $new = round((float) $old * $multiplier, 2);
                        Product::where('id', $item->id)->update(['price' => $new]);
                        if (\Illuminate\Support\Facades\Schema::hasTable('price_histories')) {
                            DB::table('price_histories')->insert([
                                'product_id' => $item->id,
                                'old_price' => $old,
                                'new_price' => $new,
                                'changed_by' => auth('employee')->id(),
                                'change_reason' => 'Bulk price increase '.$percent.'%',
                                'changed_at' => now(),
                                'metadata' => json_encode(['bulk' => true]),
                            ]);
                        }
                        $count++;
                    }
                    Cache::flush();
                    if (\Illuminate\Support\Facades\Schema::hasTable('system_alerts')) {
                        DB::table('system_alerts')->insert([
                            'title' => 'Bulk Price Update',
                            'message' => 'Updated '.$count.' products by '.$percent.'%',
                            'type' => 'info',
                            'severity' => 'medium',
                            'status' => 'active',
                            'created_by' => auth('employee')->id(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    try {
                        $metrics = app(FinanceDashboardService::class)->getKPIMetrics();
                        if (\Illuminate\Support\Facades\Schema::hasTable('performance_metrics')) {
                            DB::table('performance_metrics')->updateOrInsert(
                                [
                                    'metric_name' => 'profit_margin',
                                    'metric_type' => 'monthly',
                                    'metric_date' => now()->startOfMonth()->toDateString(),
                                ],
                                [
                                    'value' => round($metrics['monthly_revenue']['value'] > 0
                                        ? (($metrics['monthly_revenue']['value']
                                            - (float) 0 // expenses in KPI already accounted
                                        ) / $metrics['monthly_revenue']['value']) * 100
                                        : 0, 4),
                                    'category' => 'finance',
                                    'metadata' => json_encode(['source' => 'bulk_price_update']),
                                    'updated_at' => now(),
                                ]
                            );
                        }
                    } catch (\Throwable $e) {
                        // Ignore KPI persistence errors
                    }
                    DB::commit();
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw $e;
                }

                return $count;
            default:
                return 0;
        }
    }

    private function bulkStoreOperation($action, $ids)
    {
        $stores = Store::whereIn('id', $ids);

        return match ($action) {
            'activate' => $stores->update(['status' => 'active']),
            'deactivate' => $stores->update(['status' => 'inactive']),
            'delete' => $stores->delete(),
            default => 0,
        };
    }

    /**
     * Get system status overview
     */
    public function getSystemStatus()
    {
        return response()->json([
            'users' => [
                'total' => User::count(),
                'active' => User::where('status', 'active')->count(),
                'suspended' => User::where('status', 'suspended')->count(),
            ],
            'orders' => [
                'total' => Order::count(),
                'pending' => Order::where('status', 'pending')->count(),
                'completed' => Order::where('status', 'delivered')->count(),
            ],
            'products' => [
                'total' => Product::count(),
                'active' => Product::query()
                    ->when(Schema::hasColumn('products', 'is_active'), function ($q) {
                        $q->where('is_active', true);
                    })
                    ->when(! Schema::hasColumn('products', 'is_active') && Schema::hasColumn('products', 'status'), function ($q) {
                        $q->where('status', 'active');
                    })
                    ->count(),
                'low_stock' => (function () {
                    $stockCol = Schema::hasColumn('products', 'stock_quantity') ? 'stock_quantity' : (Schema::hasColumn('products', 'stock') ? 'stock' : null);
                    $thresholdCol = Schema::hasColumn('products', 'low_stock_threshold') ? 'low_stock_threshold' : null;
                    if ($stockCol && $thresholdCol) {
                        return Product::whereRaw($stockCol.' <= '.$thresholdCol)->count();
                    }
                    if ($stockCol) {
                        return Product::where($stockCol, '<=', 10)->count();
                    }

                    return 0;
                })(),
            ],
            'system_alerts' => [
                'critical' => \App\Models\SystemAlert::where('severity', 'critical')
                    ->where('status', 'active')->count(),
                'total' => \App\Models\SystemAlert::where('status', 'active')->count(),
            ],
        ]);
    }

    /**
     * Get metrics API endpoint
     */
    public function getMetrics()
    {
        $metrics = $this->getGlobalMetrics();

        return response()->json($metrics);
    }

    /**
     * Export Users to CSV/Excel
     */
    public function exportUsers(Request $request)
    {
        $format = strtolower((string) $request->get('format', 'csv'));
        if ($format === 'excel' || $format === 'xlsx') {
            $format = 'xls';
        }

        $users = User::with('roles')
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->get();

        $columns = [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'status' => 'Status',
            'roles.name' => 'Roles',
            'created_at' => 'Created At',
            'email_verified_at' => 'Email Verified',
        ];

        $exportService = app(ExportService::class);
        $filename = 'users_export_'.now()->format('Y-m-d_His').'.'.$format;

        if ($format === 'pdf') {
            return $exportService->exportToPDF(
                $users,
                $columns,
                'dashboard.exports.pdf-template',
                [
                    'filename' => $filename,
                    'title' => 'Users Export',
                    'subtitle' => 'Complete user list',
                ]
            );
        }

        if ($format === 'xls') {
            return $exportService->exportToXLS($users, $columns, $filename);
        }

        return $exportService->exportToCSV($users, $columns, $filename);
    }

    /**
     * Export Orders to CSV/Excel
     */
    public function exportOrders(Request $request)
    {
        $format = strtolower((string) $request->get('format', 'csv'));
        if ($format === 'excel' || $format === 'xlsx') {
            $format = 'xls';
        }

        $orders = Order::with(['user', 'store', 'items'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->payment_status, function ($query, $status) {
                $query->where('payment_status', $status);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->get();

        $columns = [
            'order_number' => 'Order Number',
            'user.name' => 'Customer',
            'user.email' => 'Customer Email',
            'store.name' => 'Store',
            'status' => 'Status',
            'payment_status' => 'Payment Status',
            'subtotal' => 'Subtotal',
            'delivery_cost' => 'Delivery Cost',
            'total' => 'Total',
            'payment_method' => 'Payment Method',
            'created_at' => 'Created At',
            'delivered_at' => 'Delivered At',
        ];

        $exportService = app(ExportService::class);
        $filename = 'orders_export_'.now()->format('Y-m-d_His').'.'.$format;

        if ($format === 'pdf') {
            return $exportService->exportToPDF(
                $orders,
                $columns,
                'dashboard.exports.pdf-template',
                [
                    'filename' => $filename,
                    'title' => 'Orders Export',
                    'subtitle' => 'Complete order list',
                ]
            );
        }

        if ($format === 'xls') {
            return $exportService->exportToXLS($orders, $columns, $filename);
        }

        return $exportService->exportToCSV($orders, $columns, $filename);
    }

    /**
     * Export Financial Transactions to CSV/Excel
     */
    public function exportFinancialTransactions(Request $request)
    {
        $format = strtolower((string) $request->get('format', 'csv'));
        if ($format === 'excel' || $format === 'xlsx') {
            $format = 'xls';
        }

        $transactions = FinancialTransaction::with(['user', 'order', 'store'])
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->approval_status, function ($query, $status) {
                $query->where('approval_status', $status);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->get();

        $columns = [
            'transaction_id' => 'Transaction ID',
            'type' => 'Type',
            'status' => 'Status',
            'approval_status' => 'Approval Status',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'user.name' => 'User',
            'order.order_number' => 'Order Number',
            'store.name' => 'Store',
            'description' => 'Description',
            'created_at' => 'Created At',
            'approved_at' => 'Approved At',
        ];

        $exportService = app(ExportService::class);
        $filename = 'financial_transactions_export_'.now()->format('Y-m-d_His').'.'.$format;

        if ($format === 'pdf') {
            return $exportService->exportToPDF(
                $transactions,
                $columns,
                'dashboard.exports.pdf-template',
                [
                    'filename' => $filename,
                    'title' => 'Financial Transactions Export',
                    'subtitle' => 'Complete transaction list',
                ]
            );
        }

        if ($format === 'xls') {
            return $exportService->exportToXLS($transactions, $columns, $filename);
        }

        return $exportService->exportToCSV($transactions, $columns, $filename);
    }

    /**
     * Export Products to CSV/Excel
     */
    public function exportProducts(Request $request)
    {
        $format = strtolower((string) $request->get('format', 'csv'));
        if ($format === 'excel' || $format === 'xlsx') {
            $format = 'xls';
        }

        $products = Product::with(['store', 'category'])
            ->when($request->store_id, function ($query, $storeId) {
                $query->where('store_id', $storeId);
            })
            ->when($request->is_active !== null, function ($query) use ($request) {
                if (Schema::hasColumn('products', 'is_active')) {
                    $query->where('is_active', (bool) $request->is_active);
                } elseif (Schema::hasColumn('products', 'status')) {
                    if ((bool) $request->is_active === true) {
                        $query->where('status', 'active');
                    } else {
                        $query->where('status', '!=', 'active');
                    }
                }
            })
            ->when($request->low_stock, function ($query) {
                $stockCol = Schema::hasColumn('products', 'stock_quantity') ? 'stock_quantity' : (Schema::hasColumn('products', 'stock') ? 'stock' : null);
                $thresholdCol = Schema::hasColumn('products', 'low_stock_threshold') ? 'low_stock_threshold' : null;
                if ($stockCol && $thresholdCol) {
                    $query->whereRaw($stockCol.' <= '.$thresholdCol);
                } elseif ($stockCol) {
                    $query->where($stockCol, '<=', 10);
                }
            })
            ->get();

        $columns = [
            'id' => 'ID',
            'name' => 'Product Name',
            'sku' => 'SKU',
            'store.name' => 'Store',
            'category.name' => 'Category',
            'price' => 'Price',
            'discount_price' => 'Discount Price',
            'stock_quantity' => 'Stock Quantity',
            'low_stock_threshold' => 'Low Stock Threshold',
            'is_active' => 'Active',
            'is_featured' => 'Featured',
            'created_at' => 'Created At',
        ];

        $exportService = app(ExportService::class);
        $filename = 'products_export_'.now()->format('Y-m-d_His').'.'.$format;

        if ($format === 'pdf') {
            return $exportService->exportToPDF(
                $products,
                $columns,
                'dashboard.exports.pdf-template',
                [
                    'filename' => $filename,
                    'title' => 'Products Export',
                    'subtitle' => 'Complete product list',
                ]
            );
        }

        if ($format === 'xls') {
            return $exportService->exportToXLS($products, $columns, $filename);
        }

        return $exportService->exportToCSV($products, $columns, $filename);
    }

    /**
     * Export Audit Logs to CSV/Excel
     */
    public function exportAuditLogs(Request $request)
    {
        $format = strtolower((string) $request->get('format', 'csv'));
        if ($format === 'excel' || $format === 'xlsx') {
            $format = 'xls';
        }

        $logs = AuditLog::with('user')
            ->when($request->action, function ($query, $action) {
                $query->where('action', $action);
            })
            ->when($request->model_type, function ($query, $type) {
                $query->where('model_type', $type);
            })
            ->when($request->user_id, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $columns = [
            'id' => 'ID',
            'user.name' => 'User',
            'user.email' => 'User Email',
            'action' => 'Action',
            'model_type' => 'Model Type',
            'model_id' => 'Model ID',
            'ip_address' => 'IP Address',
            'created_at' => 'Created At',
        ];

        $exportService = app(ExportService::class);
        $filename = 'audit_logs_export_'.now()->format('Y-m-d_His').'.'.$format;

        if ($format === 'pdf') {
            return $exportService->exportToPDF(
                $logs,
                $columns,
                'dashboard.exports.pdf-template',
                [
                    'filename' => $filename,
                    'title' => 'Audit Logs Export',
                    'subtitle' => 'Complete audit trail',
                ]
            );
        }

        if ($format === 'xls') {
            return $exportService->exportToXLS($logs, $columns, $filename);
        }

        return $exportService->exportToCSV($logs, $columns, $filename);
    }

    /**
     * Export Employees to CSV/Excel
     */
    public function exportEmployees(Request $request)
    {
        $format = strtolower((string) $request->get('format', 'csv'));
        if ($format === 'excel' || $format === 'xlsx') {
            $format = 'xls';
        }

        $employees = Employee::with('user')
            ->when($request->department, function ($query, $department) {
                $query->where('department', $department);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->get();

        $columns = [
            'employee_code' => 'Employee Code',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'department' => 'Department',
            'position' => 'Position',
            'employment_type' => 'Employment Type',
            'status' => 'Status',
            'hire_date' => 'Hire Date',
            'salary' => 'Salary',
            'created_at' => 'Created At',
        ];

        $exportService = app(ExportService::class);
        $filename = 'employees_export_'.now()->format('Y-m-d_His').'.'.$format;

        if ($format === 'pdf') {
            return $exportService->exportToPDF(
                $employees,
                $columns,
                'dashboard.exports.pdf-template',
                [
                    'filename' => $filename,
                    'title' => 'Employees Export',
                    'subtitle' => 'Complete employee list',
                ]
            );
        }

        if ($format === 'xls') {
            return $exportService->exportToXLS($employees, $columns, $filename);
        }

        return $exportService->exportToCSV($employees, $columns, $filename);
    }

    /**
     * Export Stores to CSV/Excel
     */
    public function exportStores(Request $request)
    {
        $format = strtolower((string) $request->get('format', 'csv'));
        if ($format === 'excel' || $format === 'xlsx') {
            $format = 'xls';
        }

        $stores = Store::with(['owner', 'organization'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->get();

        $columns = [
            'id' => 'ID',
            'name' => 'Store Name',
            'slug' => 'Slug',
            'owner.name' => 'Owner',
            'organization.name' => 'Organization',
            'status' => 'Status',
            'commission_rate' => 'Commission Rate',
            'created_at' => 'Created At',
        ];

        $exportService = app(ExportService::class);
        $filename = 'stores_export_'.now()->format('Y-m-d_His').'.'.$format;

        if ($format === 'pdf') {
            return $exportService->exportToPDF(
                $stores,
                $columns,
                'dashboard.exports.pdf-template',
                [
                    'filename' => $filename,
                    'title' => 'Stores Export',
                    'subtitle' => 'Complete store list',
                ]
            );
        }

        if ($format === 'xls') {
            return $exportService->exportToXLS($stores, $columns, $filename);
        }

        return $exportService->exportToCSV($stores, $columns, $filename);
    }

    /**
     * Export Complete System Report (All Data)
     */
    public function exportSystemReport(Request $request)
    {
        $format = $request->get('format', 'pdf');

        $reportData = [
            'summary' => $this->getGlobalMetrics(),
            'users_count' => User::count(),
            'orders_count' => Order::count(),
            'products_count' => Product::count(),
            'stores_count' => Store::count(),
            'employees_count' => Employee::count(),
            'transactions_count' => FinancialTransaction::count(),
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'generated_by' => auth('employee')->user()->full_name ?? 'System',
        ];

        // For PDF, create a comprehensive report
        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dashboard.exports.system-report', $reportData);
            $pdf->setPaper('a4', 'portrait');
            $filename = 'system_report_'.now()->format('Y-m-d_His').'.pdf';

            return $pdf->download($filename);
        }

        // For CSV, export summary data
        $columns = [
            'metric' => 'Metric',
            'value' => 'Value',
        ];

        $data = collect([
            ['metric' => 'Total Users', 'value' => $reportData['users_count']],
            ['metric' => 'Total Orders', 'value' => $reportData['orders_count']],
            ['metric' => 'Total Products', 'value' => $reportData['products_count']],
            ['metric' => 'Total Stores', 'value' => $reportData['stores_count']],
            ['metric' => 'Total Employees', 'value' => $reportData['employees_count']],
            ['metric' => 'Total Transactions', 'value' => $reportData['transactions_count']],
            ['metric' => 'Total Revenue', 'value' => $reportData['summary']['total_revenue'] ?? 0],
            ['metric' => 'Monthly Revenue', 'value' => $reportData['summary']['monthly_revenue'] ?? 0],
        ]);

        $exportService = app(ExportService::class);
        $filename = 'system_report_'.now()->format('Y-m-d_His').'.csv';

        return $exportService->exportToCSV($data, $columns, $filename);
    }

    public function exportUserData(Request $request)
    {
        $format = strtolower((string) $request->get('format', 'csv'));
        if ($format === 'excel' || $format === 'xlsx') {
            $format = 'xls';
        }
        $userId = (int) $request->get('user_id');

        $user = User::findOrFail($userId);
        $orderUserFk = Schema::hasColumn('orders', 'user_id')
            ? 'user_id'
            : (Schema::hasColumn('orders', 'customer_id') ? 'customer_id' : null);
        $orders = $orderUserFk ? Order::where($orderUserFk, $user->id)->get() : collect();
        $wishlists = Wishlist::where('user_id', $user->id)->with('product')->get();
        $reviews = Review::where('user_id', $user->id)->with('product')->get();
        $tickets = SupportTicket::where('user_id', $user->id)->get();

        $rows = collect([
            [
                'category' => 'Profile',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? $user->mobile,
                    'address' => $user->address,
                    'created_at' => $user->created_at,
                ],
            ],
        ]);

        foreach ($orders as $o) {
            $rows->push([
                'category' => 'Order',
                'data' => [
                    'order_number' => $o->order_number,
                    'status' => $o->status,
                    'payment_status' => $o->payment_status,
                    'total' => $o->total ?? $o->total_amount ?? null,
                    'shipping_address' => $o->shipping_address ?? null,
                    'created_at' => $o->created_at,
                ],
            ]);
        }

        foreach ($wishlists as $w) {
            $rows->push([
                'category' => 'Favorite',
                'data' => [
                    'product_id' => $w->product_id,
                    'product_name' => $w->product?->name,
                    'added_at' => $w->created_at,
                ],
            ]);
        }

        foreach ($reviews as $r) {
            $rows->push([
                'category' => 'Review',
                'data' => [
                    'product_id' => $r->product_id,
                    'product_name' => $r->product?->name,
                    'rating' => $r->rating,
                    'comment' => $r->comment,
                    'created_at' => $r->created_at,
                ],
            ]);
        }

        foreach ($tickets as $t) {
            $rows->push([
                'category' => 'SupportTicket',
                'data' => [
                    'ticket_number' => $t->ticket_number,
                    'subject' => $t->subject,
                    'status' => $t->status,
                    'priority' => $t->priority,
                    'created_at' => $t->created_at,
                    'resolved_at' => $t->resolved_at,
                ],
            ]);
        }

        $columns = [
            'category' => 'Category',
            'data' => 'Data',
        ];

        $exportService = app(ExportService::class);
        $filename = 'user_data_'.$user->id.'_'.now()->format('Y-m-d_His').'.'.$format;

        if ($format === 'pdf') {
            return $exportService->exportToPDF(
                $rows,
                $columns,
                'dashboard.exports.pdf-template',
                [
                    'filename' => $filename,
                    'title' => 'User Data Export',
                    'subtitle' => (string) $user->id,
                ]
            );
        }

        if ($format === 'xls') {
            return $exportService->exportToXLS($rows, $columns, $filename);
        }

        return $exportService->exportToCSV($rows, $columns, $filename);
    }

    public function exportAnnualAudit(Request $request)
    {
        $format = strtolower((string) $request->get('format', 'csv'));
        if ($format === 'excel' || $format === 'xlsx') {
            $format = 'xls';
        }
        $year = (int) $request->get('year', now()->year);

        $transactions = FinancialTransaction::whereYear('created_at', $year)->get();
        $orders = Order::whereYear('created_at', $year)->get();
        $inventory = InventoryMovement::whereYear('created_at', $year)->with(['product', 'creator'])->get();
        $accessLogs = SecurityAuditLog::whereYear('created_at', $year)->get();
        $payroll = PayrollRecord::where('pay_period', 'like', $year.'-%')->get();
        $attendance = Attendance::whereYear('date', $year)->get();
        $employeeAttendance = EmployeeAttendance::whereYear('date', $year)->get();
        $inventoryAlerts = InventoryAlert::whereYear('created_at', $year)->with('product')->get();

        $rows = collect();

        foreach ($transactions as $t) {
            $rows->push([
                'category' => 'FinancialTransaction',
                'data' => [
                    'id' => $t->id,
                    'type' => $t->type,
                    'status' => $t->status,
                    'amount' => $t->amount,
                    'currency' => $t->currency ?? 'USD',
                    'created_at' => $t->created_at,
                ],
            ]);
        }

        foreach ($orders as $o) {
            $rows->push([
                'category' => 'Order',
                'data' => [
                    'order_number' => $o->order_number,
                    'status' => $o->status,
                    'payment_status' => $o->payment_status,
                    'total' => $o->total ?? $o->total_amount ?? null,
                    'created_at' => $o->created_at,
                    'delivered_at' => $o->delivered_at ?? null,
                ],
            ]);
        }

        foreach ($inventory as $m) {
            $rows->push([
                'category' => 'InventoryMovement',
                'data' => [
                    'product_id' => $m->product_id,
                    'product_name' => $m->product?->name,
                    'type' => $m->type,
                    'quantity' => $m->quantity,
                    'previous_stock' => $m->previous_stock,
                    'new_stock' => $m->new_stock,
                    'created_at' => $m->created_at,
                ],
            ]);
        }

        foreach ($inventoryAlerts as $ia) {
            $rows->push([
                'category' => 'InventoryAlert',
                'data' => [
                    'product_id' => $ia->product_id,
                    'product_name' => $ia->product?->name,
                    'alert_type' => $ia->alert_type,
                    'current_quantity' => $ia->current_quantity,
                    'threshold_quantity' => $ia->threshold_quantity,
                    'severity' => $ia->severity,
                    'is_resolved' => $ia->is_resolved,
                    'resolved_at' => $ia->resolved_at,
                ],
            ]);
        }

        foreach ($accessLogs as $log) {
            $rows->push([
                'category' => 'SystemAccess',
                'data' => [
                    'event_type' => $log->event_type,
                    'user_type' => $log->user_type,
                    'user_id' => $log->user_id,
                    'status' => $log->status,
                    'ip_address' => $log->ip_address,
                    'risk_level' => $log->risk_level,
                    'created_at' => $log->created_at,
                ],
            ]);
        }

        foreach ($attendance as $a) {
            $rows->push([
                'category' => 'Attendance',
                'data' => [
                    'employee_id' => $a->employee_id,
                    'date' => $a->date,
                    'check_in' => $a->check_in,
                    'check_out' => $a->check_out,
                    'work_hours' => $a->work_hours,
                    'overtime_hours' => $a->overtime_hours,
                    'status' => $a->status,
                ],
            ]);
        }

        foreach ($employeeAttendance as $ea) {
            $rows->push([
                'category' => 'EmployeeAttendance',
                'data' => [
                    'employee_id' => $ea->employee_id,
                    'date' => $ea->date,
                    'clock_in' => $ea->clock_in,
                    'clock_out' => $ea->clock_out,
                    'break_minutes' => $ea->break_minutes,
                    'total_hours' => $ea->total_hours,
                    'status' => $ea->status,
                ],
            ]);
        }

        foreach ($payroll as $p) {
            $rows->push([
                'category' => 'PayrollRecord',
                'data' => [
                    'employee_id' => $p->employee_id,
                    'pay_period' => $p->pay_period,
                    'gross_pay' => $p->gross_pay,
                    'tax_deductions' => $p->tax_deductions,
                    'net_pay' => $p->net_pay,
                    'status' => $p->status,
                ],
            ]);
        }

        $columns = [
            'category' => 'Category',
            'data' => 'Data',
        ];

        $exportService = app(ExportService::class);
        $filename = 'annual_audit_'.$year.'_'.now()->format('Y-m-d_His').'.'.$format;

        if ($format === 'pdf') {
            return $exportService->exportToPDF(
                $rows,
                $columns,
                'dashboard.exports.pdf-template',
                [
                    'filename' => $filename,
                    'title' => 'Annual Audit Records',
                    'subtitle' => (string) $year,
                ]
            );
        }

        if ($format === 'xls') {
            return $exportService->exportToXLS($rows, $columns, $filename);
        }

        return $exportService->exportToCSV($rows, $columns, $filename);
    }
}
