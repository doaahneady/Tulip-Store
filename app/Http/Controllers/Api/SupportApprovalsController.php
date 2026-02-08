<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Product;
use App\Models\Trader;
use Illuminate\Http\Request;

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

        return response()->json(['success' => true]);
    }

    public function rejectTrader(Request $request, Trader $trader)
    {
        $payload = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $trader->update(['status' => Trader::STATUS_REJECTED]);
        AuditLog::log('support_trader_rejected', $trader, null, $payload);

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
            ->where('is_trader_product', true)
            ->where('status', 'pending_approval')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(['products' => $products]);
    }

    public function approveTraderProduct(Request $request, Product $product)
    {
        $product->update([
            'status' => 'active',
            'is_active' => true,
        ]);

        AuditLog::log('support_trader_product_approved', $product);

        return response()->json(['success' => true]);
    }

    public function rejectTraderProduct(Request $request, Product $product)
    {
        $payload = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $product->update([
            'status' => 'rejected',
            'is_active' => false,
        ]);

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
