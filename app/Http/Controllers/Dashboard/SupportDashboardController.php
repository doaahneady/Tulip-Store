<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\DeliveryAssignment;
use App\Models\DriverLocation;
use App\Models\Order;
use App\Models\PayrollRecord;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\SystemSetting;
use App\Models\User;
use App\Services\Dashboard\CSDashboardService;
use App\Services\StatusTransitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SupportDashboardController extends Controller
{
    public function __construct(
        protected CSDashboardService $dashboardService
    ) {}

    public function index(Request $request)
    {
        $kpi = $this->dashboardService->getKPIMetrics();
        $priority = $this->dashboardService->getTicketStatsByPriority();
        $statusDist = $this->dashboardService->getTicketStatsByStatus();
        $satisfaction = $this->dashboardService->getSatisfactionMetrics();

        $today = [
            'created' => SupportTicket::whereDate('created_at', now())->count(),
            'resolved' => SupportTicket::whereDate('resolved_at', now())->count(),
            'escalated' => 0,
        ];

        $assignedToMe = SupportTicket::where('assigned_to', auth('employee')->id())
            ->whereIn('status', ['open', 'in_progress', 'waiting_customer'])
            ->count();

        $urgentTickets = $this->dashboardService->getUrgentTickets(10);
        $performance = $this->dashboardService->getAgentPerformance(auth('employee')->id());

        return view('dashboards.cs.index', compact(
            'kpi',
            'priority',
            'statusDist',
            'satisfaction',
            'today',
            'assignedToMe',
            'urgentTickets',
            'performance'
        ));
    }

    public function tickets(Request $request)
    {
        $filters = $request->only(['status', 'priority', 'search', 'category']);
        $tickets = $this->dashboardService->getTickets($request->all());

        return view('dashboards.cs.tickets', compact('tickets', 'filters'));
    }

    public function showTicket($id)
    {
        $ticket = $this->dashboardService->getTicketDetails((int) $id);

        return view('dashboards.cs.ticket', compact('ticket'));
    }

    public function replyTicket(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        $this->dashboardService->addAgentReply($id, auth('employee')->user(), $request->message, $request->boolean('is_internal'));

        return back()->with('success', 'Reply sent.');
    }

    public function closeTicket($id)
    {
        $this->dashboardService->updateTicketStatus($id, 'closed', auth('employee')->user());

        return back()->with('success', 'Ticket closed.');
    }

    public function assignToMe($id)
    {
        $employee = auth('employee')->user();
        $this->dashboardService->assignTicketToEmployee((int) $id, $employee);

        return back()->with('success', 'Ticket assigned to you.');
    }

    public function resolveTicket(Request $request, $id)
    {
        $request->validate([
            'final_message' => 'nullable|string',
        ]);

        if ($request->filled('final_message')) {
            $this->dashboardService->addAgentReply((int) $id, auth('employee')->user(), $request->string('final_message'), false);
        }

        $this->dashboardService->updateTicketStatus((int) $id, 'resolved', auth('employee')->user());

        return back()->with('success', 'Ticket resolved.');
    }

    public function createTicket(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|integer',
            'user_email' => 'nullable|email',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'category' => 'nullable|string|max:50',
            'order_id' => 'nullable|integer',
            'message' => 'nullable|string',
        ]);

        $userId = $validated['user_id'] ?? null;
        if (! $userId && ! empty($validated['user_email'])) {
            $userId = User::where('email', $validated['user_email'])->value('id');
        }

        if (! $userId) {
            return back()->with('error', 'Please provide a valid user id or email.');
        }

        $ticket = SupportTicket::create([
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'user_id' => $userId,
            'assigned_to' => auth('employee')->id(),
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => $validated['priority'] ?? 'medium',
            'status' => 'in_progress',
            'category' => $validated['category'] ?? null,
            'related_order_id' => $validated['order_id'] ?? null,
        ]);

        if (! empty($validated['message'])) {
            $this->dashboardService->addAgentReply($ticket->id, auth('employee')->user(), $validated['message'], false);
        }

        return redirect()->route('dashboard.cs.tickets.show', ['ticket' => $ticket->id])
            ->with('success', 'Ticket created.');
    }

    public function initiateRefund(Request $request, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string',
        ]);

        \App\Services\CrossDepartmentFlowService::handleTicketRefund(
            (int) $id,
            (float) $validated['amount'],
            $validated['reason'],
            auth('employee')->user()->user_id ?? null
        );

        return back()->with('success', 'Refund request initiated.');
    }

    public function traderProducts(Request $request)
    {
        abort_unless(Schema::hasTable('products') && Schema::hasColumn('products', 'status'), 404);

        $hasAttrTable = Schema::hasTable('product_attributes');
        $hasIsCustom = $hasAttrTable && Schema::hasColumn('product_attributes', 'is_custom');
        $hasType = $hasAttrTable && Schema::hasColumn('product_attributes', 'type');

        $products = Product::query()
            ->with(['trader', 'store'])
            ->when($hasIsCustom, function ($q) {
                $q->with(['attributes' => function ($qq) {
                    $qq->where('is_custom', true);
                }]);
            })
            ->whereNotNull('trader_id')
            ->where('status', 'pending')
            ->when($request->search, function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->when(
                            Schema::hasTable('product_attributes')
                            && Schema::hasColumn('product_attributes', 'is_custom'),
                            function ($qq) use ($search) {
                            $qq->orWhereHas('attributes', function ($a) use ($search) {
                                $a->where('is_custom', true)
                                    ->where(function ($ax) use ($search) {
                                        $ax->where('name', 'like', "%{$search}%")
                                            ->orWhere('value', 'like', "%{$search}%")
                                            ->when(
                                                Schema::hasColumn('product_attributes', 'type'),
                                                function ($qx) use ($search) {
                                                    $qx->orWhere('type', 'like', "%{$search}%");
                                                }
                                            );
                                    });
                            });
                        });
                });
            })
            ->when($request->attr_type, function ($q, $type) {
                if (
                    ! Schema::hasTable('product_attributes')
                    || ! Schema::hasColumn('product_attributes', 'is_custom')
                    || ! Schema::hasColumn('product_attributes', 'type')
                ) {
                    return;
                }
                $q->whereHas('attributes', function ($a) use ($type) {
                    $a->where('is_custom', true)->where('type', $type);
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate(20)
            ->withQueryString();

        return view('dashboards.cs.trader-products', compact('products'));
    }

    public function approveTraderProduct(Product $product)
    {
        abort_unless(Schema::hasTable('products') && Schema::hasColumn('products', 'status'), 404);
        abort_unless($product->trader_id !== null, 404);

        $update = [
            'status' => 'active',
        ];
        if (Schema::hasColumn('products', 'is_active')) {
            $update['is_active'] = true;
        }
        if (Schema::hasColumn('products', 'reviewed_by')) {
            $update['reviewed_by'] = auth('employee')->id();
        }
        if (Schema::hasColumn('products', 'reviewed_at')) {
            $update['reviewed_at'] = now();
        }
        if (Schema::hasColumn('products', 'rejection_reason')) {
            $update['rejection_reason'] = null;
        }
        $product->update($update);

        return back()->with('success', 'Product approved');
    }

    public function rejectTraderProduct(Request $request, Product $product)
    {
        abort_unless(Schema::hasTable('products') && Schema::hasColumn('products', 'status'), 404);
        abort_unless($product->trader_id !== null, 404);

        $validated = $request->validate([
            'reason' => 'required|string|max:2000',
        ]);

        $update = [
            'status' => 'rejected',
        ];
        if (Schema::hasColumn('products', 'is_active')) {
            $update['is_active'] = false;
        }
        if (Schema::hasColumn('products', 'reviewed_by')) {
            $update['reviewed_by'] = auth('employee')->id();
        }
        if (Schema::hasColumn('products', 'reviewed_at')) {
            $update['reviewed_at'] = now();
        }
        if (Schema::hasColumn('products', 'rejection_reason')) {
            $update['rejection_reason'] = $validated['reason'];
        }
        $product->update($update);

        return back()->with('success', 'Product rejected');
    }

    public function orders(Request $request)
    {
        $query = Order::query()
            ->with(['customer', 'store'])
            ->withCount('items')
            ->when($request->search, function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('order_number', 'like', "%{$search}%")
                        ->orWhere('recipient_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->orWhereHas('customer', function ($c) use ($search) {
                    $c->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($request->payment_status, function ($q, $status) {
                $q->where('payment_status', $status);
            })
            ->when($request->date_from, function ($q, $date) {
                $q->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($q, $date) {
                $q->whereDate('created_at', '<=', $date);
            })
            ->orderByDesc('created_at');

        $orders = $query->paginate(20)->withQueryString();

        $statusOptions = (clone $query)->select('status')->distinct()->pluck('status')->filter()->values();
        $paymentOptions = (clone $query)->select('payment_status')->distinct()->pluck('payment_status')->filter()->values();

        return view('dashboards.cs.orders', compact('orders', 'statusOptions', 'paymentOptions'));
    }

    public function payrolls(Request $request)
    {
        $query = PayrollRecord::query()->with(['employee.user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $payrolls = $query->paginate(20)->appends($request->query());
        $statusOptions = ['draft', 'approved', 'paid'];

        return view('dashboards.cs.payrolls', compact('payrolls', 'statusOptions'));
    }

    public function showOrder(Order $order)
    {
        $order->load([
            'customer',
            'user',
            'store',
            'items.product',
            'deliveryAssignments.driver',
            'assignedDriver',
        ]);

        $delivery = $order->deliveryAssignments->sortByDesc('created_at')->first() ?? $order->deliveryAssignment;

        $customerLat = $order->latitude ?? ($order->shipping_address['lat'] ?? $order->shipping_address['latitude'] ?? null);
        $customerLng = $order->longitude ?? ($order->shipping_address['lng'] ?? $order->shipping_address['longitude'] ?? null);
        if (! $customerLat && $delivery) {
            $customerLat = $delivery->delivery_latitude ?? null;
        }
        if (! $customerLng && $delivery) {
            $customerLng = $delivery->delivery_longitude ?? null;
        }

        $driverTrack = collect();
        $driverLast = null;
        if ($delivery && $delivery->driver_id && Schema::hasTable('driver_locations')) {
            $from = $delivery->assigned_at ?? $order->assigned_at ?? $order->created_at ?? now()->subHours(6);
            $to = $delivery->delivered_at ?? now();
            $driverTrack = DriverLocation::query()
                ->where('driver_id', $delivery->driver_id)
                ->whereBetween('recorded_at', [$from, $to])
                ->orderBy('recorded_at')
                ->limit(600)
                ->get(['latitude', 'longitude', 'recorded_at', 'speed']);
            $driverLast = $driverTrack->last();
        }

        $driverLat = $driverLast?->latitude ?? ($delivery?->driver?->current_latitude ?? null);
        $driverLng = $driverLast?->longitude ?? ($delivery?->driver?->current_longitude ?? null);

        $auditLogs = Schema::hasTable('audit_logs')
            ? AuditLog::query()
                ->whereIn('model_type', ['Order', Order::class])
                ->where('model_id', $order->id)
                ->orderByDesc('created_at')
                ->take(50)
                ->get()
            : collect();

        $statusManager = app(\App\Services\OrderStatusManager::class);
        $currentStatus = $statusManager->normalize((string) ($order->status ?? 'pending'));
        $allowedNextStatuses = StatusTransitionService::getAllowedTransitions('order', $currentStatus);

        return view('dashboards.cs.order', compact(
            'order',
            'delivery',
            'customerLat',
            'customerLng',
            'driverLat',
            'driverLng',
            'driverTrack',
            'auditLogs',
            'allowedNextStatuses'
        ));
    }

    public function orderRoute(Order $order)
    {
        abort_unless(auth('employee')->check(), 403);

        $order->load([
            'deliveryAssignments.driver',
            'assignedDriver',
        ]);

        $delivery = $order->deliveryAssignments->sortByDesc('created_at')->first() ?? $order->deliveryAssignment;

        $customerLat = $order->latitude ?? ($order->shipping_address['lat'] ?? $order->shipping_address['latitude'] ?? null);
        $customerLng = $order->longitude ?? ($order->shipping_address['lng'] ?? $order->shipping_address['longitude'] ?? null);
        if (! $customerLat && $delivery) {
            $customerLat = $delivery->delivery_latitude ?? null;
        }
        if (! $customerLng && $delivery) {
            $customerLng = $delivery->delivery_longitude ?? null;
        }

        $driverTrack = collect();
        $driverLast = null;
        if ($delivery && $delivery->driver_id && Schema::hasTable('driver_locations')) {
            $from = $delivery->assigned_at ?? $order->assigned_at ?? $order->created_at ?? now()->subHours(6);
            $to = $delivery->delivered_at ?? now();
            $driverTrack = DriverLocation::query()
                ->where('driver_id', $delivery->driver_id)
                ->whereBetween('recorded_at', [$from, $to])
                ->orderBy('recorded_at')
                ->limit(600)
                ->get(['latitude', 'longitude', 'recorded_at', 'speed']);
            $driverLast = $driverTrack->last();
        }

        $driverLat = $driverLast?->latitude ?? ($delivery?->driver?->current_latitude ?? null);
        $driverLng = $driverLast?->longitude ?? ($delivery?->driver?->current_longitude ?? null);

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'customer' => ['lat' => $customerLat, 'lng' => $customerLng],
            'driver' => ['lat' => $driverLat, 'lng' => $driverLng],
            'track' => $driverTrack->map(fn ($p) => [
                'lat' => (float) $p->latitude,
                'lng' => (float) $p->longitude,
                'at' => optional($p->recorded_at)->toIso8601String(),
                'speed' => $p->speed,
            ])->values()->all(),
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    public function changeOrderStatus(Request $request, Order $order)
    {
        abort_unless(auth('employee')->check(), 403);
        $request->validate([
            'status' => 'required|string|max:50',
        ]);
        $statusManager = app(\App\Services\OrderStatusManager::class);
        $current = $statusManager->normalize((string) ($order->status ?? 'pending'));
        $next = $statusManager->normalize((string) $request->input('status'));
        $canonical = (array) config('order_statuses.canonical', []);
        if (! in_array($next, $canonical, true)) {
            return back()->with('error', 'حالة غير صالحة');
        }
        $employee = auth('employee')->user();
        if ($current === $next) {
            return back()->with('success', 'تم تحديث حالة الطلب');
        }
        $adminOverride = (bool) ($employee->is_admin ?? false);
        if (! StatusTransitionService::canTransition('order', $current, $next, $adminOverride)) {
            return back()->with('error', 'انتقال غير مسموح للحالة المطلوبة');
        }

        DB::transaction(function () use ($order, $current, $next, $employee, $adminOverride) {
            StatusTransitionService::transition($order, 'status', $next, $employee?->id, $adminOverride);
            if (class_exists(\App\Events\Dashboard\DashboardUpdated::class)) {
                event(new \App\Events\Dashboard\DashboardUpdated('cs', [
                    'type' => 'order_status_changed',
                    'order_id' => $order->id,
                    'from' => $current,
                    'to' => $next,
                ]));
            }
        });

        return back()->with('success', 'تم تحديث حالة الطلب');
    }
}
