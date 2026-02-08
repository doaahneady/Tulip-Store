<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'tickets' => $tickets,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'category' => 'nullable|string|max:50',
            'message' => 'nullable|string',
        ]);

        $user = Auth::user();
        $tags = $user?->tags ?? '';
        $isVip = is_string($tags) && stripos($tags, 'vip') !== false;
        $priority = $validated['priority'] ?? 'medium';
        if ($isVip) {
            $priority = 'high';
        }

        $seniorAgent = Employee::query()
            ->whereIn('department', ['Customer Support', 'Support', 'Customer Service'])
            ->where('status', 'active')
            ->where(function ($q) {
                $q->where('is_manager', true)->orWhere('is_team_lead', true);
            })
            ->orderByDesc('is_manager')
            ->orderByDesc('is_team_lead')
            ->orderByDesc('performance_score')
            ->first();

        $ticket = SupportTicket::create([
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => $priority,
            'status' => 'open',
            'category' => $validated['category'] ?? null,
            'assigned_to' => $seniorAgent?->id,
        ]);

        if (! empty($validated['message'])) {
            SupportTicketReply::create([
                'ticket_id' => $ticket->id,
                'author_type' => \App\Models\User::class,
                'author_id' => Auth::id(),
                'message' => $validated['message'],
                'is_internal' => false,
            ]);
        }

        $staffIds = User::where('is_cs', true)->pluck('id');
        foreach ($staffIds as $staffId) {
            Notification::create([
                'user_id' => $staffId,
                'type' => 'ticket_alert',
                'title' => 'New Ticket',
                'message' => 'New support ticket '.$ticket->ticket_number,
                'data' => ['ticket_id' => $ticket->id],
            ]);
        }

        return response()->json([
            'success' => true,
            'ticket' => $ticket->fresh(),
        ], 201);
    }

    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $reply = SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'author_type' => \App\Models\User::class,
            'author_id' => Auth::id(),
            'message' => $validated['message'],
            'is_internal' => false,
        ]);

        return response()->json([
            'success' => true,
            'reply' => $reply->fresh(['author']),
        ]);
    }
}
