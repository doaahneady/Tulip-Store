<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\DashboardNotification;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Trader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SupportApprovalsController extends Controller
{
    public function pendingTraders(Request $request)
    {
        $traders = Trader::query()
            ->where('status', Trader::STATUS_PENDING)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(['traders' => $traders]);
    }

    public function approveTrader(Request $request, Trader $trader)
    {
        $trader->update(['status' => Trader::STATUS_APPROVED]);
        AuditLog::log('support_trader_approved', $trader);

        if (Schema::hasTable('notifications') && $trader->user_id) {
            Notification::create([
                'user_id' => $trader->user_id,
                'type' => 'support_trader_approved',
                'message' => 'تمت الموافقة على حساب التاجر الخاص بك.',
                'data' => ['trader_id' => $trader->id],
            ]);
        }

        if (Schema::hasTable('dashboard_notifications') && $trader->user_id) {
            DashboardNotification::create([
                'user_type' => User::class,
                'user_id' => $trader->user_id,
                'title' => 'تمت الموافقة على حسابك',
                'message' => 'يمكنك الآن استخدام لوحة التاجر.',
                'type' => 'success',
                'is_read' => false,
                'dashboard_type' => 'cs',
                'action_url' => url('/dashboard/vendor'),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function rejectTrader(Request $request, Trader $trader)
    {
        $payload = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $trader->update(['status' => Trader::STATUS_REJECTED]);
        AuditLog::log('support_trader_rejected', $trader, null, $payload);

        $reason = trim((string) ($payload['reason'] ?? ''));
        if (Schema::hasTable('notifications') && $trader->user_id) {
            Notification::create([
                'user_id' => $trader->user_id,
                'type' => 'support_trader_rejected',
                'message' => 'تم رفض حساب التاجر الخاص بك.'.($reason !== '' ? ' السبب: '.$reason : ''),
                'data' => ['trader_id' => $trader->id, 'reason' => $reason],
            ]);
        }

        if (Schema::hasTable('dashboard_notifications') && $trader->user_id) {
            DashboardNotification::create([
                'user_type' => User::class,
                'user_id' => $trader->user_id,
                'title' => 'تم رفض حسابك',
                'message' => $reason !== '' ? $reason : '—',
                'type' => 'error',
                'is_read' => false,
                'dashboard_type' => 'cs',
                'action_url' => url('/dashboard/vendor'),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function requestInfoTrader(Request $request, Trader $trader)
    {
        $payload = $request->validate([
            'message' => 'nullable|string|max:1000',
        ]);

        AuditLog::log('support_trader_request_info', $trader, null, $payload);

        return response()->json(['success' => true]);
    }

    public function pendingTraderProducts(Request $request)
    {
        $products = Product::query()
            ->when(Schema::hasColumn('products', 'is_trader_product'), fn ($q) => $q->where('is_trader_product', true))
            ->when(Schema::hasColumn('products', 'trader_id'), fn ($q) => $q->whereNotNull('trader_id'))
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(['success' => true, 'products' => $products]);
    }

    public function approveTraderProduct(Request $request, Product $product)
    {
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

        AuditLog::log('support_trader_product_approved', $product);

        return response()->json(['success' => true]);
    }

    public function rejectTraderProduct(Request $request, Product $product)
    {
        $payload = $request->validate([
            'reason' => 'nullable|string|max:500',
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
            $update['rejection_reason'] = $payload['reason'] ?? null;
        }
        $product->update($update);

        AuditLog::log('support_trader_product_rejected', $product, null, $payload);

        return response()->json(['success' => true]);
    }

    public function requestChangesTraderProduct(Request $request, Product $product)
    {
        $payload = $request->validate([
            'message' => 'nullable|string|max:1000',
        ]);

        $product->update([
            'status' => 'draft',
        ]);

        AuditLog::log('support_trader_product_request_changes', $product, null, $payload);

        return response()->json(['success' => true]);
    }
}
