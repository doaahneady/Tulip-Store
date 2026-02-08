<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DeliveryDashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DriverDashboardController extends Controller
{
    public function __construct(
        protected DeliveryDashboardService $dashboardService
    ) {}

    public function index()
    {
        return Inertia::render('Dashboard/Driver', [
            'metrics' => $this->dashboardService->getKPIMetrics(),
            'activeDrivers' => $this->dashboardService->getActiveDrivers(),
            'pendingDeliveries' => $this->dashboardService->getPendingDeliveries(),
        ]);
    }

    public function assignDriver(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $this->dashboardService->assignDriver($request->order_id, $request->driver_id);

        return back()->with('success', 'Driver assigned successfully.');
    }
}
