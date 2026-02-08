<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Trader;
use App\Models\TraderSupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SupportTraderController extends Controller
{
    protected function employee()
    {
        return auth('employee')->user();
    }

    public function pendingTraders(Request $request)
    {
        $traders = Trader::where('status', Trader::STATUS_PENDING)
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        $data = $traders->through(function ($t) {
            return [
                'id' => $t->id,
                'name' => $t->name,
                'company_name' => $t->company_name,
                'contact_email' => $t->contact_email,
                'contact_phone' => $t->contact_phone,
                'status' => $t->status,
                'documents' => $t->payout_settings['documents'] ?? [],
                'business' => $t->payout_settings['business'] ?? [],
                'bank' => $t->payout_settings['bank'] ?? [],
                'user_id' => $t->user_id,
            ];
        });

        return response()->json(['success' => true, 'traders' => $data]);
    }

    public function showTrader($id)
    {
        $t = Trader::findOrFail($id);

        return response()->json([
            'success' => true,
            'trader' => [
                'id' => $t->id,
                'name' => $t->name,
                'company_name' => $t->company_name,
                'contact_email' => $t->contact_email,
                'contact_phone' => $t->contact_phone,
                'status' => $t->status,
                'documents' => $t->payout_settings['documents'] ?? [],
                'business' => $t->payout_settings['business'] ?? [],
                'bank' => $t->payout_settings['bank'] ?? [],
                'user_id' => $t->user_id,
            ],
        ]);
    }

    public function approveTrader($id)
    {
        $t = Trader::findOrFail($id);
        $t->update([
            'status' => Trader::STATUS_APPROVED,
        ]);
        if ($t->user_id && Schema::hasTable('notifications')) {
            Notification::create([
                'user_id' => $t->user_id,
                'type' => 'trader_approval',
                'title' => 'Trader Account Approved',
                'message' => 'Congratulations! Your trader account has been approved.',
                'icon' => 'check-circle',
                'color' => 'green',
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function rejectTrader(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string',
        ]);
        $t = Trader::findOrFail($id);
        $t->update([
            'status' => Trader::STATUS_REJECTED,
        ]);
        if ($t->user_id && Schema::hasTable('notifications')) {
            Notification::create([
                'user_id' => $t->user_id,
                'type' => 'trader_rejection',
                'title' => 'Trader Application Rejected',
                'message' => 'Your application was rejected. Reason: '.$validated['reason'],
                'icon' => 'x-circle',
                'color' => 'red',
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function requestTraderInfo(Request $request, $id)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);
        $t = Trader::findOrFail($id);
        TraderSupportTicket::create([
            'trader_id' => $t->id,
            'subject' => 'Trader Application - More Information Requested',
            'category' => 'general',
            'priority' => 'medium',
            'description' => $validated['message'],
            'status' => TraderSupportTicket::STATUS_OPEN,
            'assigned_to' => Auth::user()?->id,
        ]);
        if ($t->user_id && Schema::hasTable('notifications')) {
            Notification::create([
                'user_id' => $t->user_id,
                'type' => 'support_request',
                'title' => 'More Information Required',
                'message' => 'Support requested more information for your trader application.',
                'icon' => 'info',
                'color' => 'yellow',
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function pendingProducts(Request $request)
    {
        $products = Product::whereNotNull('trader_id')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        $data = $products->through(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'description' => $p->description,
                'images' => $p->images ?? [],
                'category_id' => $p->category_id,
                'trader_id' => $p->trader_id,
                'price' => $p->price,
                'cost_price' => $p->cost_price,
                'stock_quantity' => $p->stock_quantity,
                'status' => $p->status,
            ];
        });

        return response()->json(['success' => true, 'products' => $data]);
    }

    public function approveProduct($id)
    {
        $p = Product::whereNotNull('trader_id')->findOrFail($id);
        $p->update([
            'status' => 'approved',
            'is_active' => true,
        ]);
        $this->incrementDailyMetric($p->trader_id, 'products_approved', 1);
        $this->notifyTrader($p->trader_id, 'product_approved', 'Your product '.$p->name.' has been approved!', 'check-circle', 'green');

        return response()->json(['success' => true]);
    }

    public function rejectProduct(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string',
        ]);
        $p = Product::whereNotNull('trader_id')->findOrFail($id);
        $p->update([
            'status' => 'rejected',
            'is_active' => false,
        ]);
        $this->incrementDailyMetric($p->trader_id, 'products_rejected', 1);
        $this->notifyTrader($p->trader_id, 'product_rejected', 'Your product '.$p->name.' was rejected. Reason: '.$validated['reason'], 'x-circle', 'red');

        return response()->json(['success' => true]);
    }

    public function requestProductChanges(Request $request, $id)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);
        $p = Product::whereNotNull('trader_id')->findOrFail($id);
        TraderSupportTicket::create([
            'trader_id' => $p->trader_id,
            'subject' => 'Product Changes Requested: '.$p->name,
            'category' => 'product_approval',
            'priority' => 'medium',
            'description' => $validated['message'],
            'status' => TraderSupportTicket::STATUS_OPEN,
            'assigned_to' => Auth::user()?->id,
        ]);
        $this->notifyTrader($p->trader_id, 'product_changes_requested', 'Support requested changes for product '.$p->name.'.', 'info', 'yellow');

        return response()->json(['success' => true]);
    }

    public function performance($id)
    {
        $t = Trader::findOrFail($id);
        $productIds = Product::where('trader_id', $t->id)->pluck('id');
        $delivered = ['delivered', 'completed'];

        $totalProducts = $productIds->count();
        $totalRevenue = OrderItem::whereIn('product_id', $productIds)
            ->whereHas('order', fn ($q) => $q->whereIn('status', $delivered))
            ->sum('total_price');
        $commissionRate = (float) $t->commission_rate;
        $commission = round($totalRevenue * ($commissionRate / 100), 2);
        $earnings = round($totalRevenue - $commission, 2);

        $ordersInvolving = OrderItem::whereIn('product_id', $productIds)->distinct('order_id')->count('order_id');
        $completedOrders = OrderItem::whereIn('product_id', $productIds)
            ->whereHas('order', fn ($q) => $q->whereIn('status', $delivered))
            ->distinct('order_id')->count('order_id');
        $acceptanceRate = $ordersInvolving > 0 ? round(($completedOrders / $ordersInvolving) * 100, 2) : 0.0;

        $tickets = TraderSupportTicket::where('trader_id', $t->id)->get();
        $responseTimes = [];
        foreach ($tickets as $ticket) {
            $created = $ticket->created_at;
            $firstSupportMsg = $ticket->messages()->where('sender_type', 'support')->orderBy('created_at', 'asc')->first();
            if ($firstSupportMsg) {
                $responseTimes[] = $created->diffInMinutes($firstSupportMsg->created_at);
            }
        }
        $avgResponseTime = count($responseTimes) ? round(array_sum($responseTimes) / count($responseTimes), 2) : null;
        $complaints = TraderSupportTicket::where('trader_id', $t->id)->whereIn('category', ['order_issue', 'dispute'])->count();

        $flagged = $acceptanceRate < 60 || $complaints > 5;

        return response()->json([
            'success' => true,
            'metrics' => [
                'total_products' => $totalProducts,
                'total_sales' => $totalRevenue,
                'earnings' => $earnings,
                'acceptance_rate' => $acceptanceRate,
                'avg_response_time_minutes' => $avgResponseTime,
                'customer_complaints' => $complaints,
                'flagged' => $flagged,
            ],
        ]);
    }

    public function suspend($id)
    {
        $t = Trader::findOrFail($id);
        $t->update(['status' => Trader::STATUS_SUSPENDED]);
        $this->notifyTrader($t->id, 'trader_suspended', 'Your trader account has been suspended. Please contact support.', 'alert-triangle', 'orange');

        return response()->json(['success' => true]);
    }

    public function activate($id)
    {
        $t = Trader::findOrFail($id);
        $t->update(['status' => Trader::STATUS_APPROVED]);
        $this->notifyTrader($t->id, 'trader_activated', 'Your trader account has been activated.', 'check-circle', 'green');

        return response()->json(['success' => true]);
    }

    protected function incrementDailyMetric(int $traderId, string $column, int $delta = 1): void
    {
        if (! Schema::hasTable('trader_analytics_daily')) {
            return;
        }
        $date = now()->toDateString();
        DB::table('trader_analytics_daily')->updateOrInsert(
            ['trader_id' => $traderId, 'date' => $date],
            [$column => DB::raw($column.' + '.$delta), 'updated_at' => now(), 'created_at' => now()]
        );
    }

    protected function notifyTrader(int $traderId, string $type, string $message, ?string $icon = null, ?string $color = null): void
    {
        $trader = Trader::find($traderId);
        if (! $trader || ! $trader->user_id || ! Schema::hasTable('notifications')) {
            return;
        }
        Notification::create([
            'user_id' => $trader->user_id,
            'type' => $type,
            'title' => 'Support Update',
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
        ]);
    }
}
