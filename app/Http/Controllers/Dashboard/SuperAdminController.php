<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Store;
use App\Models\Order;
use App\Models\Product;
use App\Models\FinancialTransaction;
use App\Models\AuditLog;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    /**
     * Super Admin Dashboard - God Mode
     */
    public function index()
    {
        $metrics = $this->getGlobalMetrics();
        return view('dashboards.super-admin.index', compact('metrics'));
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
                'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
                'monthly_revenue' => Order::where('payment_status', 'paid')
                    ->whereMonth('created_at', now()->month)->sum('total'),
                'total_commission' => FinancialTransaction::where('type', 'commission')
                    ->where('status', 'completed')->sum('amount'),
                'monthly_commission' => FinancialTransaction::where('type', 'commission')
                    ->where('status', 'completed')
                    ->whereMonth('created_at', now()->month)->sum('amount'),
                
                // Order Metrics
                'total_orders' => Order::count(),
                'monthly_orders' => Order::whereMonth('created_at', now()->month)->count(),
                'pending_orders' => Order::whereIn('status', ['pending', 'confirmed'])->count(),
                'avg_order_value' => Order::where('payment_status', 'paid')->avg('total'),
                
                // Product Metrics
                'total_products' => Product::count(),
                'active_products' => Product::where('is_active', true)->count(),
                'low_stock_alerts' => Product::whereRaw('stock_quantity <= low_stock_threshold')->count(),
                
                // Growth Metrics
                'user_growth' => $this->getUserGrowthRate(),
                'revenue_growth' => $this->getRevenueGrowthRate(),
                'order_growth' => $this->getOrderGrowthRate(),
                
                // System Health
                'system_alerts' => $this->getActiveSystemAlerts(),
                'recent_activities' => $this->getRecentActivities(),
                'top_performing_stores' => $this->getTopPerformingStores(),
            ];
        });
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
        
        return view('dashboards.super-admin.users', compact('users', 'roles'));
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
            'roles.*' => 'exists:roles,id'
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
            return back()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        DB::beginTransaction();
        try {
            $user->update($request->only(['name', 'email', 'phone', 'status']));

            // Update roles
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

            DB::commit();
            
            return redirect()->route('dashboard.admin.users')
                ->with('success', 'User updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * RBAC Management
     */
    public function roles()
    {
        $roles = Role::with(['permissions'])->get();
        $permissions = Permission::all()->groupBy('category');
        
        return view('dashboards.super-admin.roles', compact('roles', 'permissions'));
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
            'permissions.*' => 'exists:permissions,id'
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
            return back()->with('error', 'Failed to create role: ' . $e->getMessage());
        }
    }

    /**
     * Platform Analytics
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30d');
        
        $analytics = [
            'user_analytics' => $this->getUserAnalytics($period),
            'revenue_analytics' => $this->getRevenueAnalytics($period),
            'order_analytics' => $this->getOrderAnalytics($period),
            'store_analytics' => $this->getStoreAnalytics($period),
            'geographic_analytics' => $this->getGeographicAnalytics($period),
        ];

        return view('dashboards.super-admin.analytics', compact('analytics', 'period'));
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
                'transaction_id' => 'emergency_refund_' . $order->id . '_' . time(),
                'order_id' => $order->id,
                'user_id' => $order->customer_id,
                'store_id' => $order->store_id,
                'type' => 'refund',
                'amount' => $order->total,
                'status' => 'completed',
                'description' => 'Emergency refund by Super Admin',
                'metadata' => ['emergency_refund' => true, 'admin_id' => auth()->id()]
            ]);

            // Update order status
            $order->update([
                'status' => 'refunded',
                'payment_status' => 'refunded',
                'admin_notes' => 'Emergency refund processed by Super Admin'
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

        return response()->json([
            'success' => true, 
            'message' => 'Maintenance mode ' . ($maintenanceMode ? 'enabled' : 'disabled')
        ]);
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
        $currentMonth = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)->sum('total');
        $lastMonth = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->subMonth()->month)->sum('total');
        
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

    private function getRecentActivities()
    {
        return AuditLog::with(['user'])
            ->whereIn('action', ['create', 'update', 'delete'])
            ->latest()
            ->take(10)
            ->get();
    }

    private function getTopPerformingStores()
    {
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
                'stores.created_at'
            ])
            ->selectRaw('COALESCE(SUM(CASE WHEN orders.payment_status = "paid" AND MONTH(orders.created_at) = ? THEN orders.total ELSE 0 END), 0) as orders_sum_total', [now()->month])
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
                'stores.created_at'
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
            'total_revenue' => Order::where('payment_status', 'paid')
                ->where('created_at', '>=', now()->subDays($days))
                ->sum('total'),
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
            'avg_order_value' => Order::where('payment_status', 'paid')
                ->where('created_at', '>=', now()->subDays($days))
                ->avg('total'),
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
            ]
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
        return Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue')
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
        return Store::withSum(['orders' => function ($query) use ($days) {
                $query->where('payment_status', 'paid')
                      ->where('created_at', '>=', now()->subDays($days));
            }], 'total')
            ->orderBy('orders_sum_total', 'desc')
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
}