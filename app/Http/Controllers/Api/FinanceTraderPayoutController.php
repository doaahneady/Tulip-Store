<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TraderPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceTraderPayoutController extends Controller
{
    public function index(Request $request)
    {
        $query = TraderPayout::query()->orderBy('created_at', 'desc');
        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }
        $payouts = $query->paginate(20);
        $data = $payouts->through(function ($p) {
            return [
                'id' => $p->id,
                'trader_id' => $p->trader_id,
                'amount' => $p->amount,
                'currency' => $p->currency,
                'status' => $p->status,
                'reference_number' => $p->reference_number,
                'processed_by' => $p->processed_by,
                'processed_at' => $p->processed_at,
                'created_at' => $p->created_at,
            ];
        });

        return response()->json(['success' => true, 'payouts' => $data]);
    }

    public function show($id)
    {
        $p = TraderPayout::findOrFail($id);

        return response()->json([
            'success' => true,
            'payout' => [
                'id' => $p->id,
                'trader_id' => $p->trader_id,
                'amount' => $p->amount,
                'currency' => $p->currency,
                'status' => $p->status,
                'reference_number' => $p->reference_number,
                'processed_by' => $p->processed_by,
                'processed_at' => $p->processed_at,
                'created_at' => $p->created_at,
                'notes' => $p->notes,
                'bank_details' => $p->bank_details,
            ],
        ]);
    }

    public function approve($id)
    {
        $p = TraderPayout::findOrFail($id);
        $p->update(['status' => 'approved']);

        return response()->json(['success' => true]);
    }

    public function complete(Request $request, $id)
    {
        $validated = $request->validate([
            'transaction_reference' => 'required|string|max:255',
        ]);
        $p = TraderPayout::findOrFail($id);
        $p->update([
            'status' => 'completed',
            'reference_number' => $validated['transaction_reference'],
            'processed_by' => Auth::user()?->id,
            'processed_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
