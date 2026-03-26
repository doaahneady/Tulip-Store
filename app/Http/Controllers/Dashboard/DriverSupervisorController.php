<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryRoute;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\EmployeeTrainingRecord;
use App\Models\FinancialTransaction;
use App\Models\Order;
use App\Models\SupportTicket;
use App\Models\SystemAlert;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleMaintenance;
use App\Services\CrossDepartmentFlowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DriverSupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Driver Supervisor Dashboard
     */
    public function index()
    {
        $metrics = $this->getSupervisorMetrics();

        return view('dashboards.supervisor.index', compact('metrics'));
    }

    /**
     * Get supervisor dashboard metrics
     */
    private function getSupervisorMetrics()
    {
        return Cache::remember('supervisor_metrics', 60, function () {
            // Driver Metrics - Real data
            $totalDrivers = Driver::count();
            $activeDrivers = Driver::query()
                ->when(\Illuminate\Support\Facades\Schema::hasColumn('drivers', 'is_active'), function ($q) {
                    $q->where('is_active', true);
                }, function ($q) {
                    if (\Illuminate\Support\Facades\Schema::hasColumn('drivers', 'status')) {
                        $q->where('status', 'active');
                    }
                })
                ->count();
            $availableDrivers = Driver::where('availability', 'available')->count();
            $offlineDrivers = Driver::where('availability', 'offline')->count();
            $onBreakDrivers = Driver::where('availability', 'on_break')->count();
            $driversOnDelivery = DeliveryAssignment::whereIn('status', ['assigned', 'picked_up', 'in_transit'])
                ->distinct('driver_id')->count();

            // Delivery Metrics - Real data
            $pendingAssignments = DeliveryAssignment::where('status', 'assigned')->count();
            $activeDeliveries = DeliveryAssignment::whereIn('status', ['picked_up', 'in_transit'])->count();
            $completedToday = DeliveryAssignment::where('status', 'delivered')
                ->whereDate('delivered_at', today())->count();
            $failedDeliveries = DeliveryAssignment::where('status', 'failed')
                ->whereDate('created_at', today())->count();

            // Order Metrics - Real data
            $ordersAwaitingAssignment = Order::where('status', 'pending')
                ->whereNull('assigned_driver_id')->count();
            $ordersInTransit = Order::whereIn('status', ['out_for_delivery'])
                ->whereNotNull('assigned_driver_id')->count();
            $ordersAwaitingAssignmentToday = Order::where('status', 'pending')
                ->whereNull('assigned_driver_id')
                ->whereDate('created_at', today())
                ->count();
            $deliveriesAssignedToday = DeliveryAssignment::whereDate('assigned_at', today())->count();
            $inProgressToday = DeliveryAssignment::whereDate('assigned_at', today())
                ->whereIn('status', ['picked_up', 'in_transit'])
                ->count();

            // Performance Metrics - Real data
            $avgDriverRating = Driver::whereNotNull('rating')->avg('rating') ?? 0;

            return [
                // Driver Metrics
                'total_drivers' => $totalDrivers,
                'active_drivers' => $activeDrivers,
                'available_drivers' => $availableDrivers,
                'offline_drivers' => $offlineDrivers,
                'on_break_drivers' => $onBreakDrivers,
                'drivers_on_delivery' => $driversOnDelivery,

                // Delivery Metrics
                'pending_assignments' => $pendingAssignments,
                'active_deliveries' => $activeDeliveries,
                'completed_today' => $completedToday,
                'failed_deliveries' => $failedDeliveries,
                'deliveries_today_total' => $ordersAwaitingAssignmentToday + $deliveriesAssignedToday,
                'in_progress_today' => $inProgressToday,
                'pending_today' => $ordersAwaitingAssignmentToday,

                // Order Metrics
                'orders_awaiting_assignment' => $ordersAwaitingAssignment,
                'orders_in_transit' => $ordersInTransit,

                // Performance Metrics - Real data
                'avg_delivery_time' => $this->getAverageDeliveryTime(),
                'on_time_delivery_rate' => $this->getOnTimeDeliveryRate(),
                'driver_efficiency' => $this->getDriverEfficiency(),
                'avg_driver_rating' => round($avgDriverRating, 1),

                // Vehicle Metrics - Real data
                'vehicles_in_maintenance' => VehicleMaintenance::where('status', 'in_progress')->count(),
                'maintenance_due' => VehicleMaintenance::where('next_due_date', '<=', now()->addDays(7))
                    ->where('status', 'scheduled')->count(),

                // Recent Activity - Real data
                'recent_assignments' => DeliveryAssignment::with(['order', 'driver.user'])
                    ->latest()
                    ->take(5)
                    ->get(),
                'active_routes' => DeliveryRoute::where('status', 'active')
                    ->with('driver')
                    ->take(5)
                    ->get(),
                // Samples for dashboard lists
                'drivers_sample' => Driver::query()
                    ->leftJoin('users', 'users.id', '=', 'drivers.user_id')
                    ->select([
                        'drivers.id',
                        'drivers.user_id',
                        'drivers.availability',
                        'drivers.rating',
                        'drivers.last_location_update',
                    ])
                    ->selectRaw('COALESCE(users.name, users.user_full_name, users.email) as name')
                    ->selectRaw('COALESCE(users.phone, users.mobile) as phone')
                    ->when(\Illuminate\Support\Facades\Schema::hasColumn('drivers', 'last_location'), function ($q) {
                        $q->selectRaw('ST_Y(drivers.last_location) as current_latitude')
                            ->selectRaw('ST_X(drivers.last_location) as current_longitude');
                    })
                    ->withCount('activeAssignments')
                    ->orderBy('drivers.availability')
                    ->limit(8)
                    ->get(),
                'unassigned_orders_sample' => Order::select(['id', 'order_number', 'recipient_name', 'address_note', 'created_at'])
                    ->where('status', 'pending')
                    ->whereDoesntHave('deliveryAssignment')
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get(),
            ];
        });
    }

    /**
     * Live Driver Tracking
     */
    public function liveTracking()
    {
        $drivers = Driver::with(['user', 'currentLocation'])
            ->where('status', 'active')
            ->get()
            ->map(function ($driver) {
                $loc = $driver->currentLocation;
                if (! $loc && DB::getDriverName() === 'mysql' && Schema::hasColumn('drivers', 'last_location')) {
                    $row = DB::table('drivers')
                        ->where('id', $driver->id)
                        ->whereNotNull('last_location')
                        ->select([
                            DB::raw('ST_Y(last_location) as lat'),
                            DB::raw('ST_X(last_location) as lng'),
                            'last_location_update',
                            'current_speed',
                            'current_heading',
                        ])
                        ->first();
                    if ($row && $row->lat !== null && $row->lng !== null) {
                        return [
                            'id' => $driver->id,
                            'name' => $driver->user?->name ?? $driver->user?->user_full_name ?? $driver->user?->email ?? ('Driver #'.$driver->id),
                            'availability' => $driver->availability,
                            'location' => [
                                'lat' => (float) $row->lat,
                                'lng' => (float) $row->lng,
                                'updated_at' => $row->last_location_update,
                                'speed' => $row->current_speed,
                                'heading' => $row->current_heading,
                            ],
                        ];
                    }
                }

                return [
                    'id' => $driver->id,
                    'name' => $driver->user?->name ?? $driver->user?->user_full_name ?? $driver->user?->email ?? ('Driver #'.$driver->id),
                    'availability' => $driver->availability,
                    'location' => $loc ? [
                        'lat' => (float) $loc->latitude,
                        'lng' => (float) $loc->longitude,
                        'updated_at' => $loc->recorded_at,
                        'speed' => $loc->speed,
                        'heading' => null,
                    ] : null,
                ];
            });

        return view('dashboards.supervisor.live-tracking', compact('drivers'));
    }

    /**
     * Get real-time driver locations (API endpoint)
     */
    public function getDriverLocations()
    {
        $drivers = Driver::with(['user', 'currentLocation'])
            ->where('status', 'active')
            ->get();

        $needsFallbackIds = $drivers
            ->filter(fn ($d) => ! $d->currentLocation)
            ->pluck('id')
            ->values()
            ->all();

        $fallback = collect();
        if ($needsFallbackIds && DB::getDriverName() === 'mysql' && Schema::hasColumn('drivers', 'last_location')) {
            $fallback = collect(DB::table('drivers')
                ->whereIn('id', $needsFallbackIds)
                ->whereNotNull('last_location')
                ->select([
                    'id',
                    DB::raw('ST_Y(last_location) as lat'),
                    DB::raw('ST_X(last_location) as lng'),
                    'current_speed',
                    'current_heading',
                    'last_location_update',
                ])
                ->get())
                ->keyBy('id');
        }

        $payload = $drivers->map(function ($driver) use ($fallback) {
            $loc = $driver->currentLocation;
            $fb = $fallback[$driver->id] ?? null;

            $lat = $loc ? (float) $loc->latitude : ($fb?->lat !== null ? (float) $fb->lat : null);
            $lng = $loc ? (float) $loc->longitude : ($fb?->lng !== null ? (float) $fb->lng : null);

            return [
                'id' => $driver->id,
                'name' => $driver->user?->name ?? $driver->user?->user_full_name ?? $driver->user?->email ?? ('Driver #'.$driver->id),
                'availability' => $driver->availability,
                'lat' => $lat,
                'lng' => $lng,
                'speed' => $loc?->speed ?? $fb?->current_speed,
                'heading' => $fb?->current_heading,
                'last_update' => $loc?->recorded_at ?? $fb?->last_location_update,
            ];
        })->values();

        return response()->json($payload);
    }

    /**
     * Driver Management
     */
    public function drivers(Request $request)
    {
        $drivers = Driver::with(['user'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->availability, function ($query, $availability) {
                $query->where('availability', $availability);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboards.supervisor.drivers', compact('drivers'));
    }

    public function vehicles(Request $request)
    {
        $vehicles = Vehicle::query()
            ->with(['driver.user'])
            ->when($request->search, function ($q, $search) {
                $q->where('plate_number', 'like', "%{$search}%")
                    ->orWhere('vehicle_type', 'like', "%{$search}%")
                    ->orWhere('make', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('vin', 'like', "%{$search}%");
            })
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $drivers = Driver::with('user')->where('status', 'active')->orderBy('id', 'desc')->get();

        return view('dashboards.supervisor.vehicles', compact('vehicles', 'drivers'));
    }

    public function createVehicle()
    {
        $drivers = Driver::with('user')->where('status', 'active')->orderBy('id', 'desc')->get();

        return view('dashboards.supervisor.vehicle-create', compact('drivers'));
    }

    public function storeVehicle(Request $request)
    {
        $validated = $request->validate([
            'vehicle_type' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number',
            'make' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:2100',
            'color' => 'nullable|string|max:255',
            'vin' => 'nullable|string|max:255|unique:vehicles,vin',
            'status' => 'required|in:active,inactive,maintenance',
            'notes' => 'nullable|string',
            'driver_id' => 'nullable|integer|exists:drivers,id',
        ]);

        return DB::transaction(function () use ($validated) {
            $vehicle = Vehicle::create([
                'vehicle_type' => $validated['vehicle_type'],
                'plate_number' => $validated['plate_number'],
                'make' => $validated['make'] ?? null,
                'model' => $validated['model'] ?? null,
                'year' => $validated['year'] ?? null,
                'color' => $validated['color'] ?? null,
                'vin' => $validated['vin'] ?? null,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            if (! empty($validated['driver_id'])) {
                Driver::where('vehicle_id', $vehicle->id)->update(['vehicle_id' => null]);
                $driver = Driver::findOrFail((int) $validated['driver_id']);
                $driver->update([
                    'vehicle_id' => $vehicle->id,
                    'vehicle_type' => $vehicle->vehicle_type,
                    'vehicle_plate' => $vehicle->plate_number,
                ]);
            }

            return redirect()->route('dashboard.supervisor.vehicles')->with('success', 'Vehicle created successfully');
        });
    }

    public function editVehicle(Vehicle $vehicle)
    {
        $vehicle->load('driver.user');
        $drivers = Driver::with('user')->where('status', 'active')->orderBy('id', 'desc')->get();

        return view('dashboards.supervisor.vehicle-edit', compact('vehicle', 'drivers'));
    }

    public function updateVehicle(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'vehicle_type' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number,'.$vehicle->id,
            'make' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:2100',
            'color' => 'nullable|string|max:255',
            'vin' => 'nullable|string|max:255|unique:vehicles,vin,'.$vehicle->id,
            'status' => 'required|in:active,inactive,maintenance',
            'notes' => 'nullable|string',
            'driver_id' => 'nullable|integer|exists:drivers,id',
        ]);

        return DB::transaction(function () use ($validated, $vehicle) {
            $vehicle->update([
                'vehicle_type' => $validated['vehicle_type'],
                'plate_number' => $validated['plate_number'],
                'make' => $validated['make'] ?? null,
                'model' => $validated['model'] ?? null,
                'year' => $validated['year'] ?? null,
                'color' => $validated['color'] ?? null,
                'vin' => $validated['vin'] ?? null,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            Driver::where('vehicle_id', $vehicle->id)->update(['vehicle_id' => null]);

            if (! empty($validated['driver_id'])) {
                $driver = Driver::findOrFail((int) $validated['driver_id']);
                $driver->update([
                    'vehicle_id' => $vehicle->id,
                    'vehicle_type' => $vehicle->vehicle_type,
                    'vehicle_plate' => $vehicle->plate_number,
                ]);
            }

            return redirect()->route('dashboard.supervisor.vehicles')->with('success', 'Vehicle updated successfully');
        });
    }

    public function deleteVehicle(Vehicle $vehicle)
    {
        Driver::where('vehicle_id', $vehicle->id)->update(['vehicle_id' => null]);
        $vehicle->delete();

        return back()->with('success', 'Vehicle deleted successfully');
    }

    /**
     * Update driver status
     */
    public function updateDriverStatus(Request $request, Driver $driver)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended',
            'availability' => 'required|in:available,busy,offline,on_break',
            'notes' => 'nullable|string',
        ]);

        $driver->update($request->only(['status', 'availability']));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Driver status updated successfully',
            ]);
        }

        return back()->with('success', 'Driver status updated successfully');
    }

    public function createDriver()
    {
        return view('dashboards.supervisor.driver-create');
    }

    public function storeDriver(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'max:255',
                'regex:/^[A-Za-z0-9._-]+$/',
                Rule::unique('users', 'username'),
            ],
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:8|max:255',
            'license_number' => 'required|string|max:255|unique:drivers,license_number',
            'license_expiry' => 'required|date',
            'vehicle_type' => 'required|string|max:255',
            'vehicle_plate' => 'required|string|max:255|unique:drivers,vehicle_plate',
            'status' => 'required|in:active,inactive,suspended',
            'availability' => 'required|in:available,busy,offline,on_break',
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $username = trim((string) $validated['username']);
                $driverEmail = Str::lower($username).'@drivers.local';
                $i = 0;
                while (
                    (User::where('email', $driverEmail)->exists() || Employee::where('email', $driverEmail)->exists())
                    && $i < 100
                ) {
                    $i++;
                    $driverEmail = Str::lower($username).'+'.$i.'@drivers.local';
                }

                $userData = [
                    'name' => $validated['name'],
                    'email' => $driverEmail,
                    'phone' => $validated['phone'] ?? null,
                    'password' => Hash::make($validated['password']),
                    'verified' => true,
                ];
                if (Schema::hasColumn('users', 'username')) {
                    $userData['username'] = $username;
                }

                $user = User::create($userData);

                Driver::create([
                    'user_id' => $user->id,
                    'license_number' => $validated['license_number'],
                    'license_expiry' => $validated['license_expiry'],
                    'vehicle_type' => $validated['vehicle_type'],
                    'vehicle_plate' => $validated['vehicle_plate'],
                    'status' => $validated['status'],
                    'availability' => $validated['availability'],
                ]);

                $this->createDriverEmployee($user, $validated, false);

                return redirect()->route('dashboard.supervisor.drivers')->with('success', 'Driver created successfully');
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Failed to create driver: '.$e->getMessage()]);
        }
    }

    public function editDriver(Driver $driver)
    {
        $driver->load('user');

        return view('dashboards.supervisor.driver-edit', compact('driver'));
    }

    public function updateDriver(Request $request, Driver $driver)
    {
        $usernameRule = $driver->user_id
            ? 'required|max:255|regex:/^[A-Za-z0-9._-]+$/|unique:users,username,'.$driver->user_id
            : 'required|max:255|regex:/^[A-Za-z0-9._-]+$/|unique:users,username';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => $usernameRule,
            'phone' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:8|max:255',
            'license_number' => 'required|string|max:255|unique:drivers,license_number,'.$driver->id,
            'license_expiry' => 'required|date',
            'vehicle_type' => 'required|string|max:255',
            'vehicle_plate' => 'required|string|max:255|unique:drivers,vehicle_plate,'.$driver->id,
            'status' => 'required|in:active,inactive,suspended',
            'availability' => 'required|in:available,busy,offline,on_break',
        ]);

        return DB::transaction(function () use ($validated, $driver) {
            $user = $driver->user;
            if (! $user && $driver->user_id) {
                $user = User::find($driver->user_id);
            }

            if ($user) {
                $driverEmail = Str::lower(trim((string) $validated['username'])).'@drivers.local';
                $i = 0;
                while (
                    (
                        User::where('email', $driverEmail)->where('id', '!=', $user->id)->exists() ||
                        Employee::where('email', $driverEmail)->exists()
                    ) && $i < 100
                ) {
                    $i++;
                    $driverEmail = Str::lower(trim((string) $validated['username'])).'+'.$i.'@drivers.local';
                }

                $userUpdates = [
                    'name' => $validated['name'],
                    'email' => $driverEmail,
                    'phone' => $validated['phone'] ?? null,
                ];
                if (Schema::hasColumn('users', 'username')) {
                    $userUpdates['username'] = trim((string) $validated['username']);
                }
                if (! empty($validated['password'])) {
                    $userUpdates['password'] = Hash::make($validated['password']);
                }
                $user->update($userUpdates);
            }

            $driver->update([
                'license_number' => $validated['license_number'],
                'license_expiry' => $validated['license_expiry'],
                'vehicle_type' => $validated['vehicle_type'],
                'vehicle_plate' => $validated['vehicle_plate'],
                'status' => $validated['status'],
                'availability' => $validated['availability'],
            ]);

            if ($user) {
                $this->syncDriverEmployeeFromUser($user->fresh(), $validated);
            }

            return redirect()->route('dashboard.supervisor.drivers')->with('success', 'Driver updated successfully');
        });
    }

    public function deleteDriver(Driver $driver)
    {
        $hasAssignments = DeliveryAssignment::where('driver_id', $driver->id)->exists();
        if ($hasAssignments) {
            return back()->with('error', 'Cannot delete driver with delivery history. Set to suspended instead.');
        }

        DB::transaction(function () use ($driver) {
            $user = $driver->user;
            $driver->locations()->delete();
            $driver->delete();

            if ($user) {
                $employee = $this->findEmployeeForDriverUser($user);
                if ($employee) {
                    $employee->forceDelete();
                }
                $hasOrders = Order::where('user_id', $user->id)->exists();
                if (! $hasOrders) {
                    $user->delete();
                }
            }
        });

        return back()->with('success', 'Driver deleted successfully');
    }

    /**
     * Employee portal (/employee/login) authenticates against employees — create a matching row.
     *
     * @param  bool  $reuseUserPasswordHash  When true, copy bcrypt hash from users (repair / sync without new password).
     */
    protected function createDriverEmployee(User $user, array $validated, bool $reuseUserPasswordHash = false): void
    {
        if (! Schema::hasTable('employees')) {
            return;
        }

        $parts = preg_split('/\s+/', trim($validated['name']), 2);
        $first = $parts[0] ?? 'Driver';
        $last = (isset($parts[1]) && $parts[1] !== '') ? $parts[1] : '—';

        $data = [
            'first_name' => $first,
            'last_name' => $last,
            'email' => Str::lower(trim((string) ($validated['username'] ?? 'driver_'.$user->id))).'@drivers.local',
            'phone' => $validated['phone'] ?? null,
            'status' => 'active',
        ];
        if (Schema::hasColumn('employees', 'department')) {
            $data['department'] = 'Delivery';
        }
        if (Schema::hasColumn('employees', 'position')) {
            $data['position'] = 'Driver';
        }
        if (Schema::hasColumn('employees', 'hire_date')) {
            $data['hire_date'] = now();
        }
        if (Schema::hasColumn('employees', 'user_id')) {
            $data['user_id'] = $user->id;
        }

        if ($reuseUserPasswordHash) {
            $data['password'] = $user->getAuthPassword();
        } else {
            $data['password'] = $validated['password'];
        }

        Employee::create($data);
    }

    protected function findEmployeeForDriverUser(User $user): ?Employee
    {
        if (! Schema::hasTable('employees')) {
            return null;
        }
        if (Schema::hasColumn('employees', 'user_id')) {
            $byLink = Employee::where('user_id', $user->id)->first();
            if ($byLink) {
                return $byLink;
            }
        }

        return Employee::where('email', $user->email)->first();
    }

    protected function syncDriverEmployeeFromUser(User $user, array $validated): void
    {
        if (! Schema::hasTable('employees')) {
            return;
        }

        $parts = preg_split('/\s+/', trim($validated['name']), 2);
        $first = $parts[0] ?? 'Driver';
        $last = (isset($parts[1]) && $parts[1] !== '') ? $parts[1] : '—';

        $employee = $this->findEmployeeForDriverUser($user);
        if (! $employee) {
            // No employee row yet: reuse same password hash as User so /employee/login works without forcing a new password.
            $this->createDriverEmployee($user, $validated, true);

            return;
        }

        $updates = [
            'first_name' => $first,
            'last_name' => $last,
            'email' => Str::lower(trim((string) ($validated['username'] ?? 'driver_'.$user->id))).'@drivers.local',
            'phone' => $validated['phone'] ?? null,
        ];
        if (! empty($validated['password'])) {
            $updates['password'] = $validated['password'];
        }
        if (Schema::hasColumn('employees', 'user_id') && ! $employee->user_id) {
            $updates['user_id'] = $user->id;
        }
        $employee->update($updates);
    }

    /**
     * Change order status (supervisor actions)
     *
     * Allowed in our lifecycle:
     * - out_for_delivery -> delivered
     */
    public function changeOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|max:50',
        ]);

        $next = strtolower(trim((string) $request->input('status')));
        if ($next !== 'delivered') {
            return back()->with('error', 'Status not allowed');
        }

        $order->loadMissing(['deliveryAssignments']);
        $assignment = $order->deliveryAssignments->sortByDesc('created_at')->first() ?? $order->deliveryAssignment;
        if (! $assignment) {
            return back()->with('error', 'No delivery assignment found for this order');
        }

        // Do not wrap this in a DB transaction; handleOrderCompletion manages its own financial transaction.
        if ($assignment->status !== 'delivered') {
            $assignment->updateStatus('delivered');
        }

        // Mark order delivered + cash payment paid on delivery
        CrossDepartmentFlowService::handleOrderCompletion($order->id, auth('employee')->id());

        return back()->with('success', 'Order marked as delivered');
    }

    /**
     * Order Assignment Management
     */
    public function orderAssignment(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $pendingOrders = Order::with(['customer', 'store', 'items.product'])
            ->whereIn('status', ['pending', 'confirmed', 'processing'])
            ->whereDoesntHave('deliveryAssignment')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    if (Schema::hasColumn('orders', 'order_number')) {
                        $qq->orWhere('order_number', 'like', "%{$search}%");
                    }
                    if (Schema::hasColumn('orders', 'recipient_name')) {
                        $qq->orWhere('recipient_name', 'like', "%{$search}%");
                    }
                    if (Schema::hasColumn('orders', 'phone')) {
                        $qq->orWhere('phone', 'like', "%{$search}%");
                    }
                    if (Schema::hasColumn('orders', 'village')) {
                        $qq->orWhere('village', 'like', "%{$search}%");
                    }
                })
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('store', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $availableDrivers = Driver::with(['user'])
            ->where('status', 'active')
            ->where('availability', 'available')
            ->get();

        $activeAssignments = DeliveryAssignment::with(['order', 'driver.user'])
            ->whereIn('status', ['assigned', 'accepted', 'picked_up', 'in_transit'])
            ->when($search !== '', function ($q) use ($search) {
                $q->whereHas('order', function ($oq) use ($search) {
                    $oq->where(function ($qq) use ($search) {
                        if (Schema::hasColumn('orders', 'order_number')) {
                            $qq->orWhere('order_number', 'like', "%{$search}%");
                        }
                        if (Schema::hasColumn('orders', 'recipient_name')) {
                            $qq->orWhere('recipient_name', 'like', "%{$search}%");
                        }
                        if (Schema::hasColumn('orders', 'phone')) {
                            $qq->orWhere('phone', 'like', "%{$search}%");
                        }
                        if (Schema::hasColumn('orders', 'village')) {
                            $qq->orWhere('village', 'like', "%{$search}%");
                        }
                    })
                        ->orWhereHas('customer', function ($cq) use ($search) {
                            $cq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('store', function ($sq) use ($search) {
                            $sq->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('assigned_at', 'desc')
            ->get();

        return view('dashboards.supervisor.order-assignment', compact(
            'pendingOrders', 'availableDrivers', 'activeAssignments'
        ));
    }

    /**
     * Get order details for assignment modal (JSON)
     */
    public function getOrderDetails(Order $order)
    {
        $order->load(['customer', 'store', 'items.product']);
        return response()->json($order);
    }

    /**
     * Assign order to driver
     */
    public function assignOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'driver_id' => 'required|exists:drivers,id',
            'delivery_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Check if order is already assigned
            $existingAssignment = DeliveryAssignment::where('order_id', $request->order_id)->first();
            if ($existingAssignment) {
                return response()->json(['success' => false, 'message' => 'Order already assigned']);
            }

            // Check if driver is available
            $driver = Driver::find($request->driver_id);
            if (! $driver->user_id) {
                return response()->json(['success' => false, 'message' => 'Driver has no linked login user (user_id missing)']);
            }
            if ($driver->availability !== 'available') {
                return response()->json(['success' => false, 'message' => 'Driver not available']);
            }

            $flowResult = CrossDepartmentFlowService::handleDriverAssignment(
                $request->order_id,
                $request->driver_id,
                auth('employee')->id(),
                auth('employee')->id()
            );
            $assignment = $flowResult['assignment'];

            // Update driver status
            $driver->update(['availability' => 'busy']);

            // Update order status (assigned_driver_id → users.id)
            Order::where('id', $request->order_id)->update([
                'assigned_driver_id' => $driver->user_id,
                'assigned_at' => now(),
            ]);

            // Create or update delivery route
            $this->updateDeliveryRoute($request->driver_id, $request->order_id);

            DB::commit();

            // Broadcast assignment to driver (if event exists)
            $eventClass = 'App\Events\DeliveryAssigned';
            if (class_exists($eventClass)) {
                broadcast(new $eventClass($assignment));
            }

            return response()->json([
                'success' => true,
                'message' => 'Order assigned successfully',
                'assignment' => $assignment->load(['order', 'driver.user']),
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['success' => false, 'message' => 'Assignment failed: '.$e->getMessage()]);
        }
    }

    /**
     * Route Optimization
     */
    public function routeOptimization()
    {
        $activeRoutes = DeliveryRoute::with(['driver.user'])
            ->where('status', 'active')
            ->whereDate('route_date', today())
            ->get();

        return view('dashboards.supervisor.route-optimization', compact('activeRoutes'));
    }

    /**
     * Optimize delivery routes
     */
    public function optimizeRoutes(Request $request)
    {
        $request->validate([
            'driver_ids' => 'required|array',
            'driver_ids.*' => 'exists:drivers,id',
        ]);

        $optimizedRoutes = [];

        foreach ($request->driver_ids as $driverId) {
            $route = $this->optimizeDriverRoute($driverId);
            if ($route) {
                $optimizedRoutes[] = $route;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Routes optimized successfully',
            'routes' => $optimizedRoutes,
        ]);
    }

    /**
     * Vehicle Maintenance Management
     */
    public function vehicleMaintenance()
    {
        $maintenanceRecords = VehicleMaintenance::with(['driver.user'])
            ->orderBy('maintenance_date', 'desc')
            ->paginate(20);

        $upcomingMaintenance = VehicleMaintenance::with(['driver.user'])
            ->where('next_due_date', '<=', now()->addDays(30))
            ->where('status', 'scheduled')
            ->orderBy('next_due_date', 'asc')
            ->get();

        return view('dashboards.supervisor.vehicle-maintenance', compact(
            'maintenanceRecords', 'upcomingMaintenance'
        ));
    }

    /**
     * Log vehicle maintenance
     */
    public function logMaintenance(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'type' => 'required|in:routine,repair,inspection,emergency',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'maintenance_date' => 'required|date',
            'next_due_date' => 'nullable|date|after:maintenance_date',
            'odometer_reading' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        VehicleMaintenance::create($request->all() + [
            'status' => 'completed',
        ]);

        return redirect()->route('supervisor.vehicle-maintenance')
            ->with('success', 'Maintenance record logged successfully!');
    }

    /**
     * Delivery Proof Review
     */
    public function deliveryProof()
    {
        $completedDeliveries = DeliveryAssignment::with(['order', 'driver.user'])
            ->where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->orderBy('delivered_at', 'desc')
            ->paginate(20);

        return view('dashboards.supervisor.delivery-proof', compact('completedDeliveries'));
    }

    /**
     * Verify delivery
     */
    public function verifyDelivery(Request $request, DeliveryAssignment $assignment)
    {
        $request->validate([
            'verified' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        $assignment->update([
            'verified_at' => $request->verified ? now() : null,
            'verified_by' => $request->verified ? auth()->id() : null,
            'verification_notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Delivery '.($request->verified ? 'verified' : 'rejected').' successfully',
        ]);
    }

    /**
     * Helper Methods
     */
    private function getAverageDeliveryTime()
    {
        $avgMinutes = DeliveryAssignment::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, assigned_at, delivered_at)) as avg_time')
            ->value('avg_time');

        return $avgMinutes ? round($avgMinutes) : 0;
    }

    private function getOnTimeDeliveryRate()
    {
        $totalDeliveries = DeliveryAssignment::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->count();

        if ($totalDeliveries === 0) {
            return 100;
        }

        $onTimeDeliveries = DeliveryAssignment::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->whereRaw('delivered_at <= DATE_ADD(assigned_at, INTERVAL 60 MINUTE)')
            ->count();

        return round(($onTimeDeliveries / $totalDeliveries) * 100, 1);
    }

    private function getDriverEfficiency()
    {
        // Mock calculation - would implement actual efficiency metrics
        return 87.5; // 87.5% efficiency
    }

    private function getRecentAssignments()
    {
        return DeliveryAssignment::with(['order', 'driver.user'])
            ->latest()
            ->take(5)
            ->get();
    }

    private function getActiveRoutes()
    {
        return DeliveryRoute::with(['driver.user'])
            ->where('status', 'active')
            ->whereDate('route_date', today())
            ->get();
    }

    private function getDriverAlerts()
    {
        $alerts = [];

        // Drivers offline for too long
        $offlineDrivers = Driver::where('availability', 'offline')
            ->where('last_location_update', '<', now()->subHours(2))
            ->count();

        if ($offlineDrivers > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$offlineDrivers} drivers have been offline for over 2 hours",
            ];
        }

        // Overdue deliveries
        $overdueDeliveries = DeliveryAssignment::whereIn('status', ['assigned', 'accepted', 'picked_up'])
            ->where('assigned_at', '<', now()->subHours(2))
            ->count();

        if ($overdueDeliveries > 0) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "{$overdueDeliveries} deliveries are overdue",
            ];
        }

        return $alerts;
    }

    private function updateDeliveryRoute($driverId, $orderId)
    {
        $order = Order::find($orderId);
        $route = DeliveryRoute::firstOrCreate([
            'driver_id' => $driverId,
            'route_date' => today(),
        ], [
            'waypoints' => [],
            'optimized_sequence' => [],
            'status' => 'planned',
        ]);

        // Add order to route waypoints
        $waypoints = $route->waypoints;
        $waypoints[] = [
            'order_id' => $orderId,
            'address' => $order->shipping_address,
            'coordinates' => $this->geocodeAddress($order->shipping_address),
        ];

        $route->update([
            'waypoints' => $waypoints,
            'status' => 'active',
        ]);

        return $route;
    }

    private function optimizeDriverRoute($driverId)
    {
        $route = DeliveryRoute::where('driver_id', $driverId)
            ->where('route_date', today())
            ->first();

        if (! $route || empty($route->waypoints)) {
            return null;
        }

        // Simple optimization - would use actual routing algorithm
        $optimizedSequence = $this->calculateOptimalSequence($route->waypoints);

        $route->update([
            'optimized_sequence' => $optimizedSequence,
            'total_distance' => $this->calculateTotalDistance($optimizedSequence),
            'estimated_duration' => $this->calculateEstimatedDuration($optimizedSequence),
        ]);

        return $route;
    }

    private function geocodeAddress($address)
    {
        // Mock geocoding - would integrate with actual geocoding service
        return [
            'lat' => 40.7128 + (rand(-1000, 1000) / 10000),
            'lng' => -74.0060 + (rand(-1000, 1000) / 10000),
        ];
    }

    private function calculateOptimalSequence($waypoints)
    {
        // Simple nearest neighbor algorithm - would use more sophisticated routing
        $sequence = [];
        $remaining = collect($waypoints)->keyBy('order_id');
        $current = ['coordinates' => ['lat' => 40.7128, 'lng' => -74.0060]]; // Starting point

        while ($remaining->isNotEmpty()) {
            $nearest = $remaining->sortBy(function ($waypoint) use ($current) {
                return $this->calculateDistance(
                    $current['coordinates'],
                    $waypoint['coordinates']
                );
            })->first();

            $sequence[] = $nearest['order_id'];
            $current = $nearest;
            $remaining->forget($nearest['order_id']);
        }

        return $sequence;
    }

    private function calculateDistance($point1, $point2)
    {
        // Haversine formula for distance calculation
        $lat1 = deg2rad($point1['lat']);
        $lon1 = deg2rad($point1['lng']);
        $lat2 = deg2rad($point2['lat']);
        $lon2 = deg2rad($point2['lng']);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = 6371 * $c; // Earth's radius in kilometers

        return $distance;
    }

    private function calculateTotalDistance($sequence)
    {
        // Mock calculation - would calculate actual route distance
        return count($sequence) * 5.2; // Average 5.2 km per stop
    }

    private function calculateEstimatedDuration($sequence)
    {
        // Mock calculation - would calculate actual travel time
        return count($sequence) * 15; // Average 15 minutes per stop
    }

    /**
     * Route Optimization Results
     */
    public function getRouteOptimizations(Request $request)
    {
        $optimizations = \App\Models\RouteOptimization::with('driver')
            ->when($request->driver_id, function ($query, $driverId) {
                $query->where('driver_id', $driverId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('optimization_date', 'desc')
            ->paginate(20);

        // Get statistics
        $stats = [
            'total_optimizations' => \App\Models\RouteOptimization::count(),
            'completed_optimizations' => \App\Models\RouteOptimization::where('status', 'completed')->count(),
            'avg_savings_percentage' => \App\Models\RouteOptimization::whereNotNull('savings_percentage')
                ->avg('savings_percentage') ?? 0,
            'total_distance_saved_km' => \App\Models\RouteOptimization::sum('total_distance_km'),
        ];

        return response()->json([
            'optimizations' => $optimizations,
            'stats' => $stats,
        ]);
    }

    public function saveRouteOptimization(Request $request)
    {
        $request->validate([
            'delivery_ids' => 'required|array',
            'driver_id' => 'nullable|exists:drivers,id',
            'total_distance_km' => 'required|numeric|min:0',
            'estimated_duration_minutes' => 'required|integer|min:0',
            'route_path' => 'nullable|array',
        ]);

        $optimization = \App\Models\RouteOptimization::create([
            'optimization_date' => now()->toDateString(),
            'delivery_ids' => $request->delivery_ids,
            'driver_id' => $request->driver_id,
            'total_distance_km' => $request->total_distance_km,
            'estimated_duration_minutes' => $request->estimated_duration_minutes,
            'fuel_cost' => $request->fuel_cost,
            'route_path' => $request->route_path,
            'status' => $request->status ?? 'optimized',
            'savings_percentage' => $request->savings_percentage,
        ]);

        return response()->json(['success' => true, 'optimization' => $optimization]);
    }

    /**
     * Driver Performance Scores
     */
    public function getDriverPerformanceScores(Request $request)
    {
        $scores = \App\Models\DriverPerformanceScore::with('driver')
            ->when($request->driver_id, function ($query, $driverId) {
                $query->where('driver_id', $driverId);
            })
            ->when($request->period, function ($query, $period) {
                $query->where('period', $period);
            })
            ->orderBy('overall_score', 'desc')
            ->paginate(20);

        // Get averages
        $averages = [
            'avg_on_time_rate' => \App\Models\DriverPerformanceScore::avg('on_time_rate') ?? 0,
            'avg_customer_rating' => \App\Models\DriverPerformanceScore::avg('customer_rating') ?? 0,
            'avg_overall_score' => \App\Models\DriverPerformanceScore::avg('overall_score') ?? 0,
        ];

        return response()->json([
            'scores' => $scores,
            'averages' => $averages,
        ]);
    }

    public function calculateDriverPerformanceScore(Request $request, Driver $driver)
    {
        $request->validate([
            'period' => 'required|string', // 2025-01, 2025-Q1, 2025
        ]);

        $period = $request->period;
        $startDate = $this->getPeriodStartDate($period);
        $endDate = $this->getPeriodEndDate($period);

        // Get delivery statistics
        $deliveries = DeliveryAssignment::where('driver_id', $driver->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalDeliveries = $deliveries->count();
        $onTimeDeliveries = $deliveries->where('status', 'delivered')
            ->filter(function ($delivery) {
                return $delivery->delivered_at <= $delivery->estimated_delivery_at;
            })->count();

        $onTimeRate = $totalDeliveries > 0 ? ($onTimeDeliveries / $totalDeliveries) * 100 : 0;
        $avgDeliveryTime = $deliveries->where('status', 'delivered')
            ->avg(function ($delivery) {
                return $delivery->delivered_at->diffInMinutes($delivery->assigned_at);
            }) ?? 0;

        // Calculate overall score (weighted average)
        $overallScore = ($onTimeRate * 0.4) + ($driver->rating * 20) + (80); // Simplified formula

        $score = \App\Models\DriverPerformanceScore::updateOrCreate(
            [
                'driver_id' => $driver->id,
                'period' => $period,
            ],
            [
                'total_deliveries' => $totalDeliveries,
                'on_time_deliveries' => $onTimeDeliveries,
                'on_time_rate' => round($onTimeRate, 2),
                'average_delivery_time_minutes' => round($avgDeliveryTime, 2),
                'customer_rating' => $driver->rating ?? 0,
                'overall_score' => round($overallScore, 2),
                'performance_grade' => $this->calculateGrade($overallScore),
            ]
        );

        return response()->json(['success' => true, 'score' => $score]);
    }

    private function calculateGrade($score)
    {
        if ($score >= 90) {
            return 'A';
        }
        if ($score >= 80) {
            return 'B';
        }
        if ($score >= 70) {
            return 'C';
        }
        if ($score >= 60) {
            return 'D';
        }

        return 'F';
    }

    private function getPeriodStartDate($period)
    {
        if (strlen($period) === 7) { // YYYY-MM
            return \Carbon\Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        } elseif (strlen($period) === 6 && str_contains($period, 'Q')) { // YYYY-Q1
            $parts = explode('-Q', $period);
            $quarter = (int) $parts[1];

            return \Carbon\Carbon::create($parts[0], ($quarter - 1) * 3 + 1, 1)->startOfMonth();
        } else { // YYYY
            return \Carbon\Carbon::create($period, 1, 1)->startOfYear();
        }
    }

    private function getPeriodEndDate($period)
    {
        if (strlen($period) === 7) {
            return \Carbon\Carbon::createFromFormat('Y-m', $period)->endOfMonth();
        } elseif (strlen($period) === 6 && str_contains($period, 'Q')) {
            $parts = explode('-Q', $period);
            $quarter = (int) $parts[1];

            return \Carbon\Carbon::create($parts[0], $quarter * 3, 1)->endOfMonth();
        } else {
            return \Carbon\Carbon::create($period, 12, 31)->endOfYear();
        }
    }

    /**
     * Delivery Zone Analytics
     */
    public function getZoneAnalytics(Request $request)
    {
        $analytics = \App\Models\DeliveryZoneAnalytic::when($request->zone_name, function ($query, $zoneName) {
            $query->where('zone_name', $zoneName);
        })
            ->when($request->date_from, function ($query, $date) {
                $query->where('analytics_date', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->where('analytics_date', '<=', $date);
            })
            ->orderBy('analytics_date', 'desc')
            ->paginate(20);

        // Get zone summaries
        $zones = \App\Models\DeliveryZoneAnalytic::select('zone_name')
            ->selectRaw('SUM(total_deliveries) as total_deliveries')
            ->selectRaw('SUM(completed_deliveries) as completed_deliveries')
            ->selectRaw('AVG(average_delivery_time_minutes) as avg_time')
            ->selectRaw('AVG(customer_satisfaction_score) as avg_satisfaction')
            ->groupBy('zone_name')
            ->get();

        return response()->json([
            'analytics' => $analytics,
            'zones' => $zones,
        ]);
    }

    public function generateZoneAnalytics(Request $request)
    {
        $request->validate([
            'zone_name' => 'required|string',
            'analytics_date' => 'required|date|date_format:Y-m-d',
        ]);

        // Calculate analytics for the zone on that date
        $orders = Order::whereDate('created_at', $request->analytics_date)
            ->where('village', $request->zone_name) // Assuming village represents zone
            ->get();

        $totalDeliveries = $orders->count();
        $completedDeliveries = $orders->where('status', 'delivered')->count();
        $failedDeliveries = $orders->where('status', 'cancelled')->count();

        $avgDeliveryTime = DeliveryAssignment::whereHas('order', function ($q) use ($request) {
            $q->whereDate('created_at', $request->analytics_date)
                ->where('village', $request->zone_name);
        })
            ->where('status', 'delivered')
            ->avg(DB::raw('TIMESTAMPDIFF(MINUTE, assigned_at, delivered_at)')) ?? 0;

        $analytic = \App\Models\DeliveryZoneAnalytic::updateOrCreate(
            [
                'zone_name' => $request->zone_name,
                'analytics_date' => $request->analytics_date,
            ],
            [
                'total_deliveries' => $totalDeliveries,
                'completed_deliveries' => $completedDeliveries,
                'failed_deliveries' => $failedDeliveries,
                'average_delivery_time_minutes' => round($avgDeliveryTime, 2),
                'average_delivery_cost' => 15.00, // Would calculate from actual data
                'customer_satisfaction_score' => 4.5, // Would calculate from reviews
            ]
        );

        return response()->json(['success' => true, 'analytic' => $analytic]);
    }

    public function reportIncident(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|integer|exists:drivers,id',
            'order_id' => 'nullable|integer|exists:orders,id',
            'severity' => 'required|string|in:low,medium,high,critical',
            'description' => 'required|string',
            'estimated_damage' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $driver = Driver::findOrFail($request->driver_id);
            $subject = 'Driver Incident Report';
            $ticket = SupportTicket::create([
                'ticket_number' => 'INC-'.now()->format('YmdHis').'-'.$driver->id,
                'user_id' => $driver->user_id,
                'assigned_to' => auth('employee')->id(),
                'subject' => $subject,
                'description' => $request->description,
                'priority' => in_array($request->severity, ['high', 'critical']) ? 'urgent' : 'medium',
                'status' => 'open',
                'category' => 'driver_incident',
                'tags' => ['driver_incident', $request->severity],
            ]);
            $alert = SystemAlert::create([
                'title' => 'Driver Incident',
                'message' => 'Incident reported for driver '.$driver->name,
                'type' => 'warning',
                'priority' => $request->severity,
                'is_read' => false,
                'is_resolved' => false,
            ]);
            $employeeId = null;
            if ($driver->user_id) {
                $user = User::find($driver->user_id);
                $employeeId = $user?->employee?->id;
            }
            $training = null;
            if ($employeeId) {
                $training = EmployeeTrainingRecord::create([
                    'employee_id' => $employeeId,
                    'training_name' => 'Accident Safety Course',
                    'training_type' => 'internal',
                    'description' => 'Post-incident safety training',
                    'start_date' => now()->toDateString(),
                    'status' => 'scheduled',
                    'provider' => 'Safety Dept',
                    'notes' => 'Assigned due to incident '.$ticket->ticket_number,
                ]);
            }
            $transaction = null;
            if ($request->filled('estimated_damage')) {
                $transaction = FinancialTransaction::create([
                    'transaction_id' => FinancialTransaction::generateTransactionId('insurance'),
                    'type' => 'insurance_claim',
                    'status' => 'pending',
                    'amount' => $request->estimated_damage,
                    'currency' => 'USD',
                    'description' => 'Insurance claim for driver incident '.$ticket->ticket_number,
                    'metadata' => [
                        'driver_id' => $driver->id,
                        'ticket_id' => $ticket->id,
                        'severity' => $request->severity,
                        'order_id' => $request->order_id,
                    ],
                ]);
            }

            return response()->json([
                'success' => true,
                'ticket' => $ticket,
                'alert' => $alert,
                'training' => $training,
                'transaction' => $transaction,
            ]);
        });
    }
}
