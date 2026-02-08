<?php

namespace App\Http\Controllers\Trader;

use App\Http\Controllers\Controller;
use App\Models\Trader;
use App\Models\TraderSupportMessage;
use App\Models\TraderSupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TraderSupportController extends Controller
{
    protected function getApprovedTraderOrAbort(): Trader
    {
        $user = Auth::guard('trader')->user();
        abort_unless($user && ($user->is_trader ?? false), 403);

        $trader = Trader::where('user_id', $user->id)->first();
        abort_unless($trader, 404);
        abort_unless($trader->status === Trader::STATUS_APPROVED, 403);

        return $trader;
    }

    public function index(Request $request)
    {
        $trader = $this->getApprovedTraderOrAbort();

        $tickets = TraderSupportTicket::where('trader_id', $trader->id)
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('priority'), fn ($q, $p) => $q->where('priority', $p))
            ->when($request->query('category'), fn ($q, $c) => $q->where('category', $c))
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'tickets' => $tickets,
        ]);
    }

    public function show($id)
    {
        $trader = $this->getApprovedTraderOrAbort();
        $ticket = TraderSupportTicket::where('id', $id)->where('trader_id', $trader->id)->firstOrFail();

        $ticket->load(['messages' => function ($q) {
            $q->orderBy('created_at', 'asc');
        }, 'assignedTo']);

        return response()->json([
            'success' => true,
            'ticket' => $ticket,
        ]);
    }

    public function store(Request $request)
    {
        $trader = $this->getApprovedTraderOrAbort();
        $user = Auth::guard('trader')->user();

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|in:product_approval,payment,order_issue,technical,general,dispute',
            'priority' => 'required|in:low,medium,high,urgent',
            'description' => 'required|string',
            'attachments.*' => 'file|max:5120',
        ]);

        $ticket = TraderSupportTicket::create([
            'trader_id' => $trader->id,
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'description' => $validated['description'],
            'status' => TraderSupportTicket::STATUS_OPEN,
        ]);

        $attachments = $this->storeAttachments($request, $ticket->id);

        TraderSupportMessage::create([
            'ticket_id' => $ticket->id,
            'sender_id' => $user->id,
            'sender_type' => 'trader',
            'message' => $validated['description'],
            'attachments' => $attachments ?: null,
            'is_internal_note' => false,
        ]);

        return response()->json([
            'success' => true,
            'ticket_id' => $ticket->id,
        ], 201);
    }

    public function reply(Request $request, $id)
    {
        $trader = $this->getApprovedTraderOrAbort();
        $user = Auth::guard('trader')->user();
        $ticket = TraderSupportTicket::where('id', $id)->where('trader_id', $trader->id)->firstOrFail();

        $validated = $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'file|max:5120',
        ]);

        $attachments = $this->storeAttachments($request, $ticket->id);

        TraderSupportMessage::create([
            'ticket_id' => $ticket->id,
            'sender_id' => $user->id,
            'sender_type' => 'trader',
            'message' => $validated['message'],
            'attachments' => $attachments ?: null,
            'is_internal_note' => false,
        ]);

        $ticket->update(['status' => TraderSupportTicket::STATUS_IN_PROGRESS]);

        return response()->json(['success' => true]);
    }

    public function close($id)
    {
        $trader = $this->getApprovedTraderOrAbort();
        $ticket = TraderSupportTicket::where('id', $id)->where('trader_id', $trader->id)->firstOrFail();

        $ticket->update([
            'status' => TraderSupportTicket::STATUS_CLOSED,
            'resolved_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function reopen(Request $request, $id)
    {
        $trader = $this->getApprovedTraderOrAbort();
        $user = Auth::guard('trader')->user();
        $ticket = TraderSupportTicket::where('id', $id)->where('trader_id', $trader->id)->firstOrFail();

        $validated = $request->validate([
            'reason' => 'required|string',
        ]);

        $ticket->update([
            'status' => TraderSupportTicket::STATUS_OPEN,
            'resolved_at' => null,
        ]);

        TraderSupportMessage::create([
            'ticket_id' => $ticket->id,
            'sender_id' => $user->id,
            'sender_type' => 'trader',
            'message' => $validated['reason'],
            'is_internal_note' => false,
        ]);

        return response()->json(['success' => true]);
    }

    protected function storeAttachments(Request $request, int $ticketId): array
    {
        $files = $request->file('attachments', []);
        $urls = [];
        foreach ($files as $file) {
            if (! $file->isValid()) {
                continue;
            }
            $path = $file->store("trader-support/{$ticketId}", 'public');
            $urls[] = Storage::disk('public')->url($path);
        }

        return $urls;
    }

    public function downloadAttachment($messageId, $index)
    {
        $trader = $this->getApprovedTraderOrAbort();
        $message = TraderSupportMessage::with('ticket')->findOrFail($messageId);
        abort_unless($message->ticket && (int) $message->ticket->trader_id === (int) $trader->id, 403);
        $attachments = $message->attachments ?? [];
        $idx = (int) $index;
        abort_unless(isset($attachments[$idx]), 404);
        $url = $attachments[$idx];

        return redirect()->away($url);
    }
}
