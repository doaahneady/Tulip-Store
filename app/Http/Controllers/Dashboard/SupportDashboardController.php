<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\DeliveryAssignment;
use App\Models\DriverLocation;
use App\Models\Order;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\SystemSetting;
use App\Models\Trader;
use App\Models\User;
use App\Services\Dashboard\CSDashboardService;
use App\Services\StatusTransitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Mail\TraderWelcomeMail;
use Illuminate\Support\Facades\Mail;

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
        $pendingTraders = Trader::query()
            ->where('status', Trader::STATUS_PENDING)
            ->orderBy('created_at', 'asc')
            ->limit(8)
            ->get();

        return view('dashboards.cs.index', compact(
            'kpi',
            'priority',
            'statusDist',
            'satisfaction',
            'today',
            'assignedToMe',
            'urgentTickets',
            'performance',
            'pendingTraders'
        ));
    }

    /**
     * Approves a trader and sends a welcome email.
     */
    public function approveTrader(Trader $trader)
    {
        $trader->update(['status' => Trader::STATUS_APPROVED]);

        // Send welcome email to trader
        try {
            if ($trader->contact_email) {
                Mail::to($trader->contact_email)->send(new TraderWelcomeMail($trader->name));
            } elseif ($trader->user && $trader->user->email) {
                Mail::to($trader->user->email)->send(new TraderWelcomeMail($trader->name));
            }
        } catch (\Exception $e) {
            // Log error but don't stop the approval process
            \Log::error('Failed to send trader welcome email: ' . $e->getMessage());
        }

        return back()->with('success', 'ط·ع¾ط¸â€¦ط·ع¾ ط·آ§ط¸â€‍ط¸â€¦ط¸ث†ط·آ§ط¸ظ¾ط¸â€ڑط·آ© ط·آ¹ط¸â€‍ط¸â€° ط·آ­ط·آ³ط·آ§ط·آ¨ ط·آ§ط¸â€‍ط·ع¾ط·آ§ط·آ¬ط·آ± ط¸ث†ط·آ¥ط·آ±ط·آ³ط·آ§ط¸â€‍ ط·آ¨ط·آ±ط¸ظ¹ط·آ¯ ط·آ§ط¸â€‍ط·ع¾ط·آ±ط·آ­ط¸ظ¹ط·آ¨');
    }

    public function rejectTrader(Request $request, Trader $trader)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $trader->update(['status' => Trader::STATUS_REJECTED]);

        if ($trader->user_id && Schema::hasTable('notifications')) {
            \App\Models\Notification::create([
                'user_id' => $trader->user_id,
                'type' => 'trader_rejection',
                'title' => 'ط·ع¾ط¸â€¦ ط·آ±ط¸ظ¾ط·آ¶ ط·آ·ط¸â€‍ط·آ¨ ط·آ§ط¸â€‍ط·ع¾ط·آ³ط·آ¬ط¸ظ¹ط¸â€‍',
                'content' => $validated['reason'],
            ]);
        }

        return back()->with('success', 'ط·ع¾ط¸â€¦ ط·آ±ط¸ظ¾ط·آ¶ ط·آ·ط¸â€‍ط·آ¨ ط·آ§ط¸â€‍ط·ع¾ط·آ§ط·آ¬ط·آ±');
    }

    public function traders(Request $request)
    {
        $traders = Trader::query()
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboards.cs.traders.index', compact('traders'));
    }

    public function traderDetails(Trader $trader)
    {
        return view('dashboards.cs.traders.show', compact('trader'));
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

        $product->loadMissing(['trader']);
        $trader = $product->trader;
        if ($trader instanceof Trader && $trader->user_id && Schema::hasTable('dashboard_notifications')) {
            \App\Models\DashboardNotification::create([
                'user_type' => User::class,
                'user_id' => $trader->user_id,
                'title' => 'ط·ع¾ط¸â€¦ط·ع¾ ط·آ§ط¸â€‍ط¸â€¦ط¸ث†ط·آ§ط¸ظ¾ط¸â€ڑط·آ© ط·آ¹ط¸â€‍ط¸â€° ط¸â€¦ط¸â€ ط·ع¾ط·آ¬ط¸ئ’',
                'message' => 'ط·ع¾ط¸â€¦ط·ع¾ ط·آ§ط¸â€‍ط¸â€¦ط¸ث†ط·آ§ط¸ظ¾ط¸â€ڑط·آ© ط·آ¹ط¸â€‍ط¸â€° ط·آ§ط¸â€‍ط¸â€¦ط¸â€ ط·ع¾ط·آ¬: '.$product->name,
                'type' => 'success',
                'is_read' => false,
                'dashboard_type' => 'cs',
                'action_url' => url('/dashboard/vendor/products'),
            ]);
        }

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

        $product->loadMissing(['trader']);
        $trader = $product->trader;
        if ($trader instanceof Trader && $trader->user_id && Schema::hasTable('dashboard_notifications')) {
            \App\Models\DashboardNotification::create([
                'user_type' => User::class,
                'user_id' => $trader->user_id,
                'title' => 'ط·ع¾ط¸â€¦ ط·آ±ط¸ظ¾ط·آ¶ ط¸â€¦ط¸â€ ط·ع¾ط·آ¬ط¸ئ’',
                'message' => 'ط·ع¾ط¸â€¦ ط·آ±ط¸ظ¾ط·آ¶ ط·آ§ط¸â€‍ط¸â€¦ط¸â€ ط·ع¾ط·آ¬: '.$product->name.' - ط·آ§ط¸â€‍ط·آ³ط·آ¨ط·آ¨: '.$validated['reason'],
                'type' => 'error',
                'is_read' => false,
                'dashboard_type' => 'cs',
                'action_url' => url('/dashboard/vendor/products'),
            ]);
        }

        return back()->with('success', 'Product rejected');
    }

    public function updateTraderProduct(Request $request, Product $product)
    {
        abort_unless($product->trader_id !== null, 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $updates = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? $product->description,
            'price' => $validated['price'],
        ];
        if (Schema::hasColumn('products', 'stock_quantity')) {
            $updates['stock_quantity'] = $validated['stock_quantity'] ?? ($product->stock_quantity ?? 0);
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('products', 'public');
            if (Schema::hasColumn('products', 'image')) {
                $updates['image'] = $path;
            }
            if (Schema::hasColumn('products', 'photo')) {
                $updates['photo'] = $path;
            }
            if (Schema::hasColumn('products', 'image_path')) {
                $updates['image_path'] = $path;
            }
            if (Schema::hasColumn('products', 'images')) {
                // Cast as array on Product model أ¢â‚¬â€‌ pass a PHP array, not a JSON string
                $updates['images'] = [$path];
            }
        }

        $product->update($updates);

        return back()->with('success', 'ط·ع¾ط¸â€¦ ط·ع¾ط·آ­ط·آ¯ط¸ظ¹ط·آ« ط·آ·ط¸â€‍ط·آ¨ ط·آ§ط¸â€‍ط¸â€¦ط¸â€ ط·ع¾ط·آ¬ ط·آ¨ط¸â€ ط·آ¬ط·آ§ط·آ­');
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
        $search = trim((string) $request->input('search', ''));

        $query = Order::query()
            ->with(['user', 'store'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', (string) $request->input('status')))
            ->when($request->filled('payment_status'), fn ($q) => $q->where('payment_status', (string) $request->input('payment_status')))
            ->when($request->filled('date_from'), fn ($q) => $q->whereDate('created_at', '>=', (string) $request->input('date_from')))
            ->when($request->filled('date_to'), fn ($q) => $q->whereDate('created_at', '<=', (string) $request->input('date_to')))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('order_number', 'like', "%{$search}%")
                        ->orWhere('recipient_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('store', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderByDesc('created_at');

        $orders = $query->paginate(25)->withQueryString();
        $statusOptions = (clone $query)->select('status')->distinct()->pluck('status')->filter()->values();
        $paymentOptions = (clone $query)->select('payment_status')->distinct()->pluck('payment_status')->filter()->values();

        return view('dashboards.cs.payrolls', compact('orders', 'statusOptions', 'paymentOptions'));
    }

    public function downloadInvoice(Order $order)
    {
        $order->loadMissing(['items.product', 'user', 'store']);
        $name = (string) ($order->order_number ?? $order->id);

        $order = $this->prepareOrderForPdf($order);

        return \App\Services\InvoicePdfService::download(
            'invoices.template-en',
            compact('order'),
            'invoice-'.$name
        );
    }

    private function prepareOrderForPdf(Order $order): Order
    {
        $order->recipient_name = $this->reshapeArabicForPdf($order->recipient_name);
        $order->village = $this->reshapeArabicForPdf($order->village);
        $order->address_note = $this->reshapeArabicForPdf($order->address_note);

        if ($order->relationLoaded('user') && $order->user) {
            $order->user->name = $this->reshapeArabicForPdf($order->user->name);
        }

        if ($order->relationLoaded('items')) {
            foreach ($order->items as $item) {
                if ($item->relationLoaded('product') && $item->product) {
                    $item->product->name = $this->reshapeArabicForPdf($item->product->name);
                }
                $item->product_name = $this->reshapeArabicForPdf($item->product_name);
            }
        }

        return $order;
    }

    private function reshapeArabicForPdf(?string $text): ?string
    {
        $text = $text === null ? null : trim($text);
        if ($text === null || $text === '') {
            return $text;
        }
        if (! preg_match('/[\x{0600}-\x{06FF}]/u', $text)) {
            return $text;
        }

        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        if (! is_array($chars) || $chars === []) {
            return $text;
        }

        $forms = [
            "\u{0621}" => ["\u{FE80}", "\u{FE80}", null, null],
            "\u{0622}" => ["\u{FE81}", "\u{FE82}", null, null],
            "\u{0623}" => ["\u{FE83}", "\u{FE84}", null, null],
            "\u{0624}" => ["\u{FE85}", "\u{FE86}", null, null],
            "\u{0625}" => ["\u{FE87}", "\u{FE88}", null, null],
            "\u{0626}" => ["\u{FE89}", "\u{FE8A}", "\u{FE8B}", "\u{FE8C}"],
            "\u{0627}" => ["\u{FE8D}", "\u{FE8E}", null, null],
            "\u{0628}" => ["\u{FE8F}", "\u{FE90}", "\u{FE91}", "\u{FE92}"],
            "\u{0629}" => ["\u{FE93}", "\u{FE94}", null, null],
            "\u{062A}" => ["\u{FE95}", "\u{FE96}", "\u{FE97}", "\u{FE98}"],
            "\u{062B}" => ["\u{FE99}", "\u{FE9A}", "\u{FE9B}", "\u{FE9C}"],
            "\u{062C}" => ["\u{FE9D}", "\u{FE9E}", "\u{FE9F}", "\u{FEA0}"],
            "\u{062D}" => ["\u{FEA1}", "\u{FEA2}", "\u{FEA3}", "\u{FEA4}"],
            "\u{062E}" => ["\u{FEA5}", "\u{FEA6}", "\u{FEA7}", "\u{FEA8}"],
            "\u{062F}" => ["\u{FEA9}", "\u{FEAA}", null, null],
            "\u{0630}" => ["\u{FEAB}", "\u{FEAC}", null, null],
            "\u{0631}" => ["\u{FEAD}", "\u{FEAE}", null, null],
            "\u{0632}" => ["\u{FEAF}", "\u{FEB0}", null, null],
            "\u{0633}" => ["\u{FEB1}", "\u{FEB2}", "\u{FEB3}", "\u{FEB4}"],
            "\u{0634}" => ["\u{FEB5}", "\u{FEB6}", "\u{FEB7}", "\u{FEB8}"],
            "\u{0635}" => ["\u{FEB9}", "\u{FEBA}", "\u{FEBB}", "\u{FEBC}"],
            "\u{0636}" => ["\u{FEBD}", "\u{FEBE}", "\u{FEBF}", "\u{FEC0}"],
            "\u{0637}" => ["\u{FEC1}", "\u{FEC2}", "\u{FEC3}", "\u{FEC4}"],
            "\u{0638}" => ["\u{FEC5}", "\u{FEC6}", "\u{FEC7}", "\u{FEC8}"],
            "\u{0639}" => ["\u{FEC9}", "\u{FECA}", "\u{FECB}", "\u{FECC}"],
            "\u{063A}" => ["\u{FECD}", "\u{FECE}", "\u{FECF}", "\u{FED0}"],
            "\u{0641}" => ["\u{FED1}", "\u{FED2}", "\u{FED3}", "\u{FED4}"],
            "\u{0642}" => ["\u{FED5}", "\u{FED6}", "\u{FED7}", "\u{FED8}"],
            "\u{0643}" => ["\u{FED9}", "\u{FEDA}", "\u{FEDB}", "\u{FEDC}"],
            "\u{0644}" => ["\u{FEDD}", "\u{FEDE}", "\u{FEDF}", "\u{FEE0}"],
            "\u{0645}" => ["\u{FEE1}", "\u{FEE2}", "\u{FEE3}", "\u{FEE4}"],
            "\u{0646}" => ["\u{FEE5}", "\u{FEE6}", "\u{FEE7}", "\u{FEE8}"],
            "\u{0647}" => ["\u{FEE9}", "\u{FEEA}", "\u{FEEB}", "\u{FEEC}"],
            "\u{0648}" => ["\u{FEED}", "\u{FEEE}", null, null],
            "\u{0649}" => ["\u{FEEF}", "\u{FEF0}", null, null],
            "\u{064A}" => ["\u{FEF1}", "\u{FEF2}", "\u{FEF3}", "\u{FEF4}"],
        ];

        $lamAlef = [
            "\u{0622}" => ["\u{FEF5}", "\u{FEF6}"],
            "\u{0623}" => ["\u{FEF7}", "\u{FEF8}"],
            "\u{0625}" => ["\u{FEF9}", "\u{FEFA}"],
            "\u{0627}" => ["\u{FEFB}", "\u{FEFC}"],
        ];

        $isJoiner = function (string $ch) use ($forms): bool {
            return isset($forms[$ch]);
        };
        $canConnectPrev = function (string $ch) use ($forms): bool {
            return isset($forms[$ch]) && $forms[$ch][1] !== null;
        };
        $canConnectNext = function (string $ch) use ($forms): bool {
            return isset($forms[$ch]) && $forms[$ch][2] !== null;
        };
        $isDiacriticOrTatweel = function (string $ch): bool {
            $cp = unpack('N', mb_convert_encoding($ch, 'UCS-4BE', 'UTF-8'))[1] ?? 0;
            return ($cp >= 0x064B && $cp <= 0x065F) || $cp === 0x0640;
        };

        $out = '';
        $len = count($chars);
        for ($i = 0; $i < $len; $i++) {
            $ch = $chars[$i];
            if ($isDiacriticOrTatweel($ch) || ! $isJoiner($ch)) {
                $out .= $ch;
                continue;
            }

            if ($ch === "\u{0644}" && isset($chars[$i + 1]) && isset($lamAlef[$chars[$i + 1]])) {
                $prev = $chars[$i - 1] ?? '';
                $connectPrev = $prev !== '' && $isJoiner($prev) && $canConnectNext($prev) && $canConnectPrev($ch);
                $out .= $connectPrev ? $lamAlef[$chars[$i + 1]][1] : $lamAlef[$chars[$i + 1]][0];
                $i++;
                continue;
            }

            $prev = $chars[$i - 1] ?? '';
            $next = $chars[$i + 1] ?? '';

            $connectPrev = $prev !== '' && $isJoiner($prev) && $canConnectNext($prev) && $canConnectPrev($ch);
            $connectNext = $next !== '' && $isJoiner($next) && ! $isDiacriticOrTatweel($next) && $canConnectNext($ch) && $canConnectPrev($next);

            if ($connectPrev && $connectNext && $forms[$ch][3] !== null) {
                $out .= $forms[$ch][3];
            } elseif ($connectPrev && $forms[$ch][1] !== null) {
                $out .= $forms[$ch][1];
            } elseif ($connectNext && $forms[$ch][2] !== null) {
                $out .= $forms[$ch][2];
            } else {
                $out .= $forms[$ch][0] ?? $ch;
            }
        }

        return "\u{200F}".$out."\u{200F}";
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
            return back()->with('error', 'ط·آ­ط·آ§ط¸â€‍ط·آ© ط·ط›ط¸ظ¹ط·آ± ط·آµط·آ§ط¸â€‍ط·آ­ط·آ©');
        }
        $employee = auth('employee')->user();
        if ($current === $next) {
            return back()->with('success', 'ط·ع¾ط¸â€¦ ط·ع¾ط·آ­ط·آ¯ط¸ظ¹ط·آ« ط·آ­ط·آ§ط¸â€‍ط·آ© ط·آ§ط¸â€‍ط·آ·ط¸â€‍ط·آ¨');
        }
        $adminOverride = (bool) ($employee->is_admin ?? false);
        if (! StatusTransitionService::canTransition('order', $current, $next, $adminOverride)) {
            return back()->with('error', 'ط·آ§ط¸â€ ط·ع¾ط¸â€ڑط·آ§ط¸â€‍ ط·ط›ط¸ظ¹ط·آ± ط¸â€¦ط·آ³ط¸â€¦ط¸ث†ط·آ­ ط¸â€‍ط¸â€‍ط·آ­ط·آ§ط¸â€‍ط·آ© ط·آ§ط¸â€‍ط¸â€¦ط·آ·ط¸â€‍ط¸ث†ط·آ¨ط·آ©');
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

        return back()->with('success', 'ط·ع¾ط¸â€¦ ط·ع¾ط·آ­ط·آ¯ط¸ظ¹ط·آ« ط·آ­ط·آ§ط¸â€‍ط·آ© ط·آ§ط¸â€‍ط·آ·ط¸â€‍ط·آ¨');
    }
}
