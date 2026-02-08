<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\ITDashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ITDashboardController extends Controller
{
    public function __construct(
        protected ITDashboardService $dashboardService
    ) {}

    public function index()
    {
        return Inertia::render('Dashboard/IT', [
            'systemMetrics' => $this->dashboardService->getSystemMetrics(),
            'kpiMetrics' => $this->dashboardService->getKPIMetrics(),
        ]);
    }

    public function systemLogs(Request $request)
    {
        return Inertia::render('Dashboard/IT/Logs', [
            'logs' => $this->dashboardService->getSystemLogs($request->all()),
        ]);
    }

    public function securityLogs(Request $request)
    {
        return Inertia::render('Dashboard/IT/SecurityLogs', [
            'logs' => $this->dashboardService->getSecurityLogs($request->all()),
        ]);
    }

    public function backups()
    {
        return Inertia::render('Dashboard/IT/Backups', [
            'backups' => $this->dashboardService->getBackups(),
        ]);
    }

    public function createBackup()
    {
        $this->dashboardService->createBackup();

        return back()->with('success', 'Backup started.');
    }

    public function clearCache()
    {
        $this->dashboardService->clearCache();

        return back()->with('success', 'Cache cleared.');
    }

    public function users(Request $request)
    {
        return Inertia::render('Dashboard/IT/Users', [
            'users' => $this->dashboardService->getUsers($request->all()),
        ]);
    }

    public function toggleUserStatus($id)
    {
        $this->dashboardService->toggleUserStatus($id);

        return back()->with('success', 'User status updated.');
    }

    public function employeeAccess()
    {
        return Inertia::render('Dashboard/IT/EmployeeAccess', [
            'employees' => $this->dashboardService->getEmployeeAccessList(),
        ]);
    }

    public function updateEmployeeRoles(Request $request, $id)
    {
        $this->dashboardService->updateEmployeeRoles($id, $request->roles);

        return back()->with('success', 'Employee roles updated.');
    }

    public function systemSettings()
    {
        return Inertia::render('Dashboard/IT/Settings', [
            'settings' => $this->dashboardService->getSystemSettings(),
        ]);
    }
}
