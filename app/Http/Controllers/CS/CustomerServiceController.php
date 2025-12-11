<?php

namespace App\Http\Controllers\CS;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\CustomerFeedback;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerServiceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Check if user has CS access (unified role)
        if (!$user->is_cs_agent) {
            abort(403, 'Unauthorized - Customer Service Access Required');
        }

        // Ticket Statistics
        $totalTickets = SupportTicket::count();
        $openTickets = SupportTicket::where('status', 'open')->count();
        $inProgressTickets = SupportTicket::where('status', 'in_progress')->count();
        $resolvedToday = SupportTicket::whereDate('resolved_at', Carbon::today())->count();
        
        // Response Time Metrics
        $avgFirstResponseTime = SupportTicket::whereNotNull('first_response_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as avg_time')
            ->value('avg_time');
        $avgFirstResponseTime = $avgFirstResponseTime ? round($avgFirstResponseTime) . ' دقيقة' : 'N/A';
        
        $avgResolutionTime = SupportTicket::whereNotNull('resolved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_time')
            ->value('avg_time');
        $avgResolutionTime = $avgResolutionTime ? round($avgResolutionTime) . ' ساعة' : 'N/A';

        // Satisfaction Metrics
        $avgSatisfaction = SupportTicket::whereNotNull('satisfaction_rating')
            ->avg('satisfaction_rating');
        $avgSatisfaction = $avgSatisfaction ? round($avgSatisfaction, 1) : 0;
        
        $satisfactionCount = SupportTicket::whereNotNull('satisfaction_rating')->count();

        // Recent Tickets
        $recentTickets = SupportTicket::with(['user', 'assignedAgent'])
            ->latest()
            ->take(10)
            ->get();

        // Tickets by Status
        $ticketsByStatus = SupportTicket::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Tickets by Priority
        $ticketsByPriority = SupportTicket::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get();

        // Tickets by Category
        $ticketsByCategory = SupportTicket::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        // Customer Feedback
        $pendingFeedback = CustomerFeedback::where('status', 'pending')->count();
        $avgRating = CustomerFeedback::whereNotNull('rating')->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 1) : 0;
        
        $recentFeedback = CustomerFeedback::with(['user', 'order'])
            ->latest()
            ->take(5)
            ->get();

        // Feedback by Type
        $feedbackByType = CustomerFeedback::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        // CS Agents Performance
        $agentPerformance = User::where('is_cs_agent', true)
            ->withCount([
                'assignedTickets',
                'assignedTickets as resolved_tickets' => function($query) {
                    $query->where('status', 'resolved');
                }
            ])
            ->get();

        // Tickets Timeline (Last 7 days)
        $ticketsTimeline = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = SupportTicket::whereDate('created_at', $date)->count();
            $resolved = SupportTicket::whereDate('resolved_at', $date)->count();
            $ticketsTimeline[] = [
                'date' => $date->format('M d'),
                'count' => $count,
                'resolved' => $resolved
            ];
        }

        // Additional Metrics
        $waitingCustomer = SupportTicket::where('status', 'waiting_customer')->count();
        $closedTickets = SupportTicket::where('status', 'closed')->count();
        $urgentTickets = SupportTicket::where('priority', 'urgent')->where('status', '!=', 'closed')->count();
        
        // Today's Activity
        $ticketsCreatedToday = SupportTicket::whereDate('created_at', Carbon::today())->count();
        $repliesCreatedToday = TicketReply::whereDate('created_at', Carbon::today())->count();
        $feedbackReceivedToday = CustomerFeedback::whereDate('created_at', Carbon::today())->count();
        
        // This Week Stats
        $ticketsThisWeek = SupportTicket::where('created_at', '>=', Carbon::now()->startOfWeek())->count();
        $resolvedThisWeek = SupportTicket::whereDate('resolved_at', '>=', Carbon::now()->startOfWeek())->count();
        
        // This Month Stats
        $ticketsThisMonth = SupportTicket::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        $resolvedThisMonth = SupportTicket::whereDate('resolved_at', '>=', Carbon::now()->startOfMonth())->count();
        
        // Response Rate
        $ticketsWithResponse = SupportTicket::whereNotNull('first_response_at')->count();
        $responseRate = $totalTickets > 0 ? round(($ticketsWithResponse / $totalTickets) * 100) : 0;
        
        // Resolution Rate
        $resolvedTickets = SupportTicket::whereIn('status', ['resolved', 'closed'])->count();
        $resolutionRate = $totalTickets > 0 ? round(($resolvedTickets / $totalTickets) * 100) : 0;
        
        // Satisfaction Distribution
        $satisfactionDistribution = SupportTicket::whereNotNull('satisfaction_rating')
            ->select('satisfaction_rating', DB::raw('count(*) as count'))
            ->groupBy('satisfaction_rating')
            ->orderBy('satisfaction_rating', 'desc')
            ->get();
        
        // Top Customers by Tickets
        $topCustomers = SupportTicket::select('user_id', DB::raw('count(*) as ticket_count'))
            ->groupBy('user_id')
            ->orderBy('ticket_count', 'desc')
            ->take(5)
            ->with('user')
            ->get();
        
        // Hourly Distribution (Today)
        $hourlyDistribution = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $count = SupportTicket::whereDate('created_at', Carbon::today())
                ->whereRaw('HOUR(created_at) = ?', [$hour])
                ->count();
            $hourlyDistribution[] = [
                'hour' => sprintf('%02d:00', $hour),
                'count' => $count
            ];
        }
        
        // Recent Activity Log
        $recentActivity = collect();
        
        // Get recent tickets
        $recentTicketActivity = SupportTicket::latest()->take(5)->get()->map(function($ticket) {
            return [
                'type' => 'ticket',
                'icon' => 'ticket-alt',
                'message' => "تذكرة جديدة: {$ticket->subject}",
                'user' => $ticket->user->name,
                'time' => $ticket->created_at,
                'color' => '#667eea'
            ];
        });
        
        // Get recent replies
        $recentReplies = TicketReply::with(['ticket', 'user'])->latest()->take(5)->get()->map(function($reply) {
            return [
                'type' => 'reply',
                'icon' => 'comment',
                'message' => "رد على: {$reply->ticket->subject}",
                'user' => $reply->user->name,
                'time' => $reply->created_at,
                'color' => '#3b82f6'
            ];
        });
        
        // Get recent feedback
        $recentFeedbackActivity = CustomerFeedback::with('user')->latest()->take(5)->get()->map(function($feedback) {
            return [
                'type' => 'feedback',
                'icon' => 'star',
                'message' => "رأي جديد من العميل",
                'user' => $feedback->user->name,
                'time' => $feedback->created_at,
                'color' => '#fbbf24'
            ];
        });
        
        $recentActivity = $recentTicketActivity->concat($recentReplies)->concat($recentFeedbackActivity)
            ->sortByDesc('time')->take(10)->values();

        // Chat Users
        $chatUsers = User::where(function($query) {
            $query->whereNotNull('role_id')
                  ->orWhere('is_admin', true)
                  ->orWhere('is_cs_agent', true);
        })
        ->where('id', '!=', auth()->id())
        ->with('role')
        ->get();
        
        $unreadMessagesCount = \App\Models\Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return view('cs.dashboard', compact(
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'resolvedToday',
            'avgFirstResponseTime',
            'avgResolutionTime',
            'avgSatisfaction',
            'satisfactionCount',
            'recentTickets',
            'ticketsByStatus',
            'ticketsByPriority',
            'ticketsByCategory',
            'pendingFeedback',
            'avgRating',
            'recentFeedback',
            'feedbackByType',
            'agentPerformance',
            'ticketsTimeline',
            'chatUsers',
            'unreadMessagesCount',
            'waitingCustomer',
            'closedTickets',
            'urgentTickets',
            'ticketsCreatedToday',
            'repliesCreatedToday',
            'feedbackReceivedToday',
            'ticketsThisWeek',
            'resolvedThisWeek',
            'ticketsThisMonth',
            'resolvedThisMonth',
            'responseRate',
            'resolutionRate',
            'satisfactionDistribution',
            'topCustomers',
            'hourlyDistribution',
            'recentActivity'
        ));
    }

    public function assignTicket(Request $request, $ticketId)
    {
        if (!auth()->user()->is_cs_agent) {
            return response()->json(['success' => false, 'message' => 'غير مصرح']);
        }

        $ticket = SupportTicket::findOrFail($ticketId);
        $ticket->update([
            'assigned_to' => $request->agent_id,
            'status' => 'in_progress'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تعيين التذكرة بنجاح'
        ]);
    }

    public function updateTicketStatus(Request $request, $ticketId)
    {
        $ticket = SupportTicket::findOrFail($ticketId);
        
        $updateData = ['status' => $request->status];
        
        if ($request->status === 'resolved' && !$ticket->resolved_at) {
            $updateData['resolved_at'] = now();
        }
        
        if ($request->status === 'closed' && !$ticket->closed_at) {
            $updateData['closed_at'] = now();
        }
        
        $ticket->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة التذكرة بنجاح'
        ]);
    }

    public function replyToTicket(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $ticket = SupportTicket::findOrFail($ticketId);
        
        TicketReply::create([
            'ticket_id' => $ticketId,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_internal_note' => $request->is_internal ?? false,
        ]);

        if (!$ticket->first_response_at) {
            $ticket->update(['first_response_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الرد بنجاح'
        ]);
    }

    public function showTicket($ticketId)
    {
        $user = auth()->user();
        
        if (!$user->is_cs_agent) {
            abort(403, 'Unauthorized - Customer Service Access Required');
        }

        $ticket = SupportTicket::with(['user', 'assignedAgent', 'order', 'replies.user'])
            ->findOrFail($ticketId);

        // Get all CS agents for assignment
        $csAgents = User::where('is_cs_agent', true)->get();

        // Get ticket timeline
        $timeline = collect();
        
        // Add ticket creation
        $timeline->push([
            'type' => 'created',
            'icon' => 'plus-circle',
            'color' => '#667eea',
            'title' => 'تم إنشاء التذكرة',
            'description' => "بواسطة {$ticket->user->name}",
            'time' => $ticket->created_at
        ]);

        // Add first response
        if ($ticket->first_response_at) {
            $timeline->push([
                'type' => 'first_response',
                'icon' => 'reply',
                'color' => '#3b82f6',
                'title' => 'أول رد',
                'description' => 'تم الرد على التذكرة',
                'time' => $ticket->first_response_at
            ]);
        }

        // Add assignment
        if ($ticket->assignedAgent) {
            $timeline->push([
                'type' => 'assigned',
                'icon' => 'user-check',
                'color' => '#8b5cf6',
                'title' => 'تم التعيين',
                'description' => "إلى {$ticket->assignedAgent->name}",
                'time' => $ticket->updated_at
            ]);
        }

        // Add resolution
        if ($ticket->resolved_at) {
            $timeline->push([
                'type' => 'resolved',
                'icon' => 'check-circle',
                'color' => '#22c55e',
                'title' => 'تم الحل',
                'description' => 'تم حل المشكلة',
                'time' => $ticket->resolved_at
            ]);
        }

        // Add closure
        if ($ticket->closed_at) {
            $timeline->push([
                'type' => 'closed',
                'icon' => 'times-circle',
                'color' => '#6b7280',
                'title' => 'تم الإغلاق',
                'description' => 'تم إغلاق التذكرة',
                'time' => $ticket->closed_at
            ]);
        }

        $timeline = $timeline->sortBy('time');

        // Calculate metrics
        $responseTime = null;
        if ($ticket->first_response_at) {
            $minutes = $ticket->created_at->diffInMinutes($ticket->first_response_at);
            $responseTime = $minutes < 60 ? "{$minutes} دقيقة" : round($minutes / 60, 1) . " ساعة";
        }

        $resolutionTime = null;
        if ($ticket->resolved_at) {
            $hours = $ticket->created_at->diffInHours($ticket->resolved_at);
            $resolutionTime = $hours < 24 ? "{$hours} ساعة" : round($hours / 24, 1) . " يوم";
        }

        return view('cs.ticket-details', compact(
            'ticket',
            'csAgents',
            'timeline',
            'responseTime',
            'resolutionTime'
        ));
    }
}
