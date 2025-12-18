<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AdminDashboardService;
use App\Services\Dashboard\AuditService;
use Illuminate\Http\Request;

/**
 * Admin Dashboard Controller
 * 
 * Handles all admin dashboard functionality including:
 * - Dashboard overview with KPIs
 * - User management
 * - Order management
 * - Store management
 * - System alerts
 * 
 * @see Requirements 7.1, 7.2, 7.3, 7.4
 */
class AdminDashboardController extends Controller
{
    public function __construct(
        protected AdminDashboardService $adminService,
        protected AuditService $auditService
    ) {
        // Apply admin role middleware to all methods
        $this->middleware('dashboard.role:admin');
    }

    /**
     * Display the admin dashboard overview
     * Shows KPI cards, charts, and recent activity
     * 
     * @see Requirements 7.1, 7.2
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');

        $data = [
            'kpis' => $this->adminService->getKPIMetrics(),
            'revenueChart' => $this->adminService->getRevenueChartData($period),
            'orderChart' => $this->adminService->getOrderChartData($period),
            'recentOrders' => $this->adminService->getRecentOrders(10),
            'topStores' => $this->adminService->getTopStores(5),
            'systemAlerts' => $this->adminService->getSystemAlerts(5),
            'period' => $period,
        ];

        return view('dashboard.admin.index', $data);
    }

    /**
     * Display user management page
     * Shows paginated list of users with search and filters
     * 
     * @see Requirements 7.3
     */
    public function users(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'role' => $request->get('role'),
            'verified' => $request->get('verified'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $search = $request->get('search');

        if ($search) {
            $users = $this->adminService->searchUsers($search, $filters);
        } else {
            $users = $this->adminService->getUsers($filters);
        }

        return view('dashboard.admin.users', [
            'users' => $users,
            'filters' => $filters,
            'search' => $search,
        ]);
    }


    /**
     * Display order management page
     * Shows paginated list of orders with search and filters
     * 
     * @see Requirements 7.1
     */
    public function orders(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'status' => $request->get('status'),
            'payment_status' => $request->get('payment_status'),
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $orders = $this->adminService->getOrders($filters);

        return view('dashboard.admin.orders', [
            'orders' => $orders,
            'filters' => $filters,
        ]);
    }

    /**
     * Display store management page
     * Shows paginated list of stores with search and filters
     * 
     * @see Requirements 7.1
     */
    public function stores(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'status' => $request->get('status'),
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $stores = $this->adminService->getStores($filters);
        $topStores = $this->adminService->getTopStores(10);

        return view('dashboard.admin.stores', [
            'stores' => $stores,
            'topStores' => $topStores,
            'filters' => $filters,
        ]);
    }

    /**
     * Display system alerts page
     * Shows recent errors and warnings from system logs
     * 
     * @see Requirements 7.4
     */
    public function alerts(Request $request)
    {
        $limit = $request->get('limit', 50);
        $alerts = $this->adminService->getSystemAlerts($limit);

        return view('dashboard.admin.alerts', [
            'alerts' => $alerts,
        ]);
    }

    /**
     * Display settings page
     */
    public function settings()
    {
        return view('dashboard.admin.settings');
    }

    /**
     * Process bulk user actions
     * 
     * @see Requirements 7.5
     */
    public function bulkUserAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:activate,deactivate,verify,delete',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        $result = $this->adminService->processBulkUserAction(
            $request->input('action'),
            $request->input('user_ids')
        );

        // Log the bulk action
        $this->auditService->log(
            'bulk_' . $request->input('action'),
            'user',
            null,
            [
                'new_values' => [
                    'user_ids' => $request->input('user_ids'),
                    'result' => $result,
                ],
            ]
        );

        if ($result['success']) {
            return redirect()->back()->with('success', "Successfully processed {$result['processed']} users.");
        }

        return redirect()->back()->with('error', 'Bulk action failed: ' . implode(', ', $result['errors']));
    }

    /**
     * Process bulk order actions
     * 
     * @see Requirements 7.5
     */
    public function bulkOrderAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:cancel,complete,process',
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'integer|exists:orders,id',
        ]);

        $result = $this->adminService->processBulkOrderAction(
            $request->input('action'),
            $request->input('order_ids')
        );

        // Log the bulk action
        $this->auditService->log(
            'bulk_' . $request->input('action'),
            'order',
            null,
            [
                'new_values' => [
                    'order_ids' => $request->input('order_ids'),
                    'result' => $result,
                ],
            ]
        );

        if ($result['success']) {
            return redirect()->back()->with('success', "Successfully processed {$result['processed']} orders.");
        }

        return redirect()->back()->with('error', 'Bulk action failed: ' . implode(', ', $result['errors']));
    }

    /**
     * Process bulk store actions
     * 
     * @see Requirements 7.5
     */
    public function bulkStoreAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:approve,suspend,delete',
            'store_ids' => 'required|array|min:1',
            'store_ids.*' => 'integer|exists:stores,id',
        ]);

        $result = $this->adminService->processBulkStoreAction(
            $request->input('action'),
            $request->input('store_ids')
        );

        // Log the bulk action
        $this->auditService->log(
            'bulk_' . $request->input('action'),
            'store',
            null,
            [
                'new_values' => [
                    'store_ids' => $request->input('store_ids'),
                    'result' => $result,
                ],
            ]
        );

        if ($result['success']) {
            return redirect()->back()->with('success', "Successfully processed {$result['processed']} stores.");
        }

        return redirect()->back()->with('error', 'Bulk action failed: ' . implode(', ', $result['errors']));
    }
}
