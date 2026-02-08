<?php

namespace App\Services\Dashboard;

use App\Models\CustomerFeedback;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\Order;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\User;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Customer Support Dashboard Service
 *
 * Provides ticket KPIs, ticket assignment, and satisfaction metrics.
 *
 * @see Requirements 9.1, 9.3, 9.4
 */
class CSDashboardService
{
    public function __construct(
        protected MetricsService $metricsService,
        protected AuditService $auditService
    ) {}

    /**
     * Get CS KPI metrics
     *
     * @return array Array containing open_tickets, pending_tickets, resolved_today, avg_response_time
     *
     * @see Requirements 9.1
     */
    public function getKPIMetrics(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Open tickets
        $openTickets = SupportTicket::where('status', 'open')->count();
        $openTicketsYesterday = SupportTicket::where('status', 'open')
            ->where('created_at', '<', $today)
            ->count();

        // Pending/in progress tickets (awaiting resolution)
        $pendingTickets = SupportTicket::whereIn('status', ['in_progress', 'waiting_customer'])->count();

        // Resolved today
        $resolvedToday = SupportTicket::whereDate('resolved_at', $today)->count();
        $resolvedYesterday = SupportTicket::whereDate('resolved_at', $yesterday)->count();
        $resolvedGrowth = $this->metricsService->calculateGrowthPercentage(
            (float) $resolvedToday,
            (float) $resolvedYesterday
        );

        // Average response time (in hours)
        $avgResponseTime = $this->calculateAverageResponseTime();

        // Total tickets this month
        $ticketsThisMonth = SupportTicket::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $ticketsLastMonth = SupportTicket::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $ticketGrowth = $this->metricsService->calculateGrowthPercentage(
            (float) $ticketsThisMonth,
            (float) $ticketsLastMonth
        );

        return [
            'open_tickets' => [
                'value' => $openTickets,
                'previous' => $openTicketsYesterday,
            ],
            'pending_tickets' => [
                'value' => $pendingTickets,
            ],
            'resolved_today' => [
                'value' => $resolvedToday,
                'previous' => $resolvedYesterday,
                'growth' => $this->metricsService->formatPercentage($resolvedGrowth),
            ],
            'avg_response_time' => [
                'value' => round($avgResponseTime, 1),
                'formatted' => $this->formatResponseTime($avgResponseTime),
            ],
            'tickets_this_month' => [
                'value' => $ticketsThisMonth,
                'growth' => $this->metricsService->formatPercentage($ticketGrowth),
            ],
        ];
    }

    /**
     * Calculate average response time in hours
     *
     * @return float Average response time in hours
     */
    protected function calculateAverageResponseTime(): float
    {
        $ticketsWithResponse = SupportTicket::whereNotNull('first_response_at')
            ->whereMonth('created_at', now()->month)
            ->get();

        if ($ticketsWithResponse->isEmpty()) {
            return 0;
        }

        $totalMinutes = 0;
        foreach ($ticketsWithResponse as $ticket) {
            $totalMinutes += $ticket->created_at->diffInMinutes($ticket->first_response_at);
        }

        return ($totalMinutes / $ticketsWithResponse->count()) / 60; // Convert to hours
    }

    /**
     * Format response time for display
     *
     * @param  float  $hours  Response time in hours
     * @return string Formatted response time
     */
    protected function formatResponseTime(float $hours): string
    {
        if ($hours < 1) {
            return round($hours * 60).' min';
        } elseif ($hours < 24) {
            return round($hours, 1).' hrs';
        } else {
            return round($hours / 24, 1).' days';
        }
    }

    /**
     * Get tickets with filters and pagination
     *
     * @param  array  $filters  Filters including status, priority, assigned_to, search, per_page
     *
     * @see Requirements 9.2
     */
    public function getTickets(array $filters = []): LengthAwarePaginator
    {
        $query = SupportTicket::with(['user', 'assignedTo']);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (array_key_exists('assigned_to', $filters)) {
            if ((string) $filters['assigned_to'] === '0') {
                $query->whereNull('assigned_to');
            } elseif (! empty($filters['assigned_to'])) {
                $query->where('assigned_to', $filters['assigned_to']);
            }
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        // Default sort by priority and creation date
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        if ($sortBy === 'priority') {
            // Custom priority ordering: urgent > high > medium > low
            $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low') ".$sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Get recent tickets
     *
     * @param  int  $limit  Number of tickets to return
     */
    public function getRecentTickets(int $limit = 10): Collection
    {
        return SupportTicket::with(['user', 'assignedTo'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get full ticket details with conversation
     */
    public function getTicketDetails(int $id): ?SupportTicket
    {
        return SupportTicket::with([
            'user',
            'assignedTo',
            'replies.author',
        ])->find($id);
    }

    /**
     * Get urgent tickets (high priority and open/pending)
     *
     * @param  int  $limit  Number of tickets to return
     */
    public function getUrgentTickets(int $limit = 10): Collection
    {
        return SupportTicket::with(['user', 'assignedTo'])
            ->whereIn('priority', ['urgent', 'high'])
            ->whereIn('status', ['open', 'in_progress'])
            ->orderByRaw("FIELD(priority, 'urgent', 'high')")
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Assign a ticket to an agent
     *
     * @param  int  $ticketId  The ticket ID
     * @param  int  $agentId  The agent user ID
     * @param  User  $assigner  The user performing the assignment
     * @return SupportTicket|null The updated ticket or null if not found
     *
     * @see Requirements 9.3
     */
    public function assignTicket(int $ticketId, int $agentId, User $assigner): ?SupportTicket
    {
        $ticket = SupportTicket::find($ticketId);

        if (! $ticket) {
            return null;
        }

        $oldAssignee = $ticket->assigned_to;

        $ticket->update([
            'assigned_to' => $agentId,
            'status' => $ticket->status === 'open' ? 'pending' : $ticket->status,
        ]);

        // Log the assignment
        $this->auditService->log(
            'assign',
            'support_ticket',
            $ticketId,
            [
                'old_values' => ['assigned_to' => $oldAssignee],
                'new_values' => ['assigned_to' => $agentId],
            ]
        );

        return $ticket->fresh(['user', 'assignedTo']);
    }

    /**
     * Update ticket status
     *
     * @param  int  $ticketId  The ticket ID
     * @param  string  $status  New status
     * @param  User  $updater  The user updating the status
     * @return SupportTicket|null The updated ticket or null if not found
     */
    public function updateTicketStatus(int $ticketId, string $status, mixed $updater = null): ?SupportTicket
    {
        $ticket = SupportTicket::find($ticketId);

        if (! $ticket) {
            return null;
        }

        $oldStatus = $ticket->status;
        $updateData = ['status' => $status];

        // Set resolved_at timestamp when resolving
        if ($status === 'resolved' && ! $ticket->resolved_at) {
            $updateData['resolved_at'] = now();
        }

        $ticket->update($updateData);

        // Log the status change
        $this->auditService->log(
            'update',
            'support_ticket',
            $ticketId,
            [
                'old_values' => ['status' => $oldStatus],
                'new_values' => ['status' => $status],
            ]
        );

        if ($status === 'resolved' && $ticket->user_id) {
            Notification::create([
                'user_id' => $ticket->user_id,
                'type' => 'ticket_resolved',
                'title' => 'Your ticket has been resolved',
                'message' => 'Ticket '.$ticket->ticket_number.' has been marked as resolved. Please rate your support experience.',
            ]);
        }

        return $ticket->fresh();
    }

    /**
     * Assign ticket to an employee and transition status to in_progress if open
     */
    public function assignTicketToEmployee(int $ticketId, Employee $employee): ?SupportTicket
    {
        $ticket = SupportTicket::find($ticketId);
        if (! $ticket) {
            return null;
        }

        $oldAssignee = $ticket->assigned_to;
        $ticket->update([
            'assigned_to' => $employee->id,
            'status' => $ticket->status === 'open' ? 'in_progress' : $ticket->status,
        ]);

        $this->auditService->log(
            'assign',
            'support_ticket',
            $ticketId,
            [
                'old_values' => ['assigned_to' => $oldAssignee],
                'new_values' => ['assigned_to' => $employee->id],
            ]
        );

        return $ticket->fresh(['user', 'assignedTo']);
    }

    /**
     * Add a reply to a ticket
     *
     * @param  int  $ticketId  The ticket ID
     * @param  User  $user  The user adding the reply
     * @param  string  $message  The reply message
     * @param  bool  $isInternalNote  Whether this is an internal note
     * @return SupportTicketReply|null The created reply or null if ticket not found
     */
    public function addReply(int $ticketId, User $user, string $message, bool $isInternalNote = false): ?SupportTicketReply
    {
        $ticket = SupportTicket::find($ticketId);

        if (! $ticket) {
            return null;
        }

        // Create the reply
        $reply = SupportTicketReply::create([
            'ticket_id' => $ticketId,
            'author_type' => User::class,
            'author_id' => $user->id,
            'message' => $message,
            'is_internal' => $isInternalNote,
        ]);

        // Update first_response_at if this is the first agent response
        if (! $ticket->first_response_at && ! $isInternalNote && $ticket->user_id !== $user->id) {
            $ticket->update(['first_response_at' => now()]);
        }

        // Update ticket status to pending if it was open
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'pending']);
        }

        return $reply->fresh(['author']);
    }

    /**
     * Add a reply from a support agent (Employee)
     */
    public function addAgentReply(int $ticketId, Employee $employee, string $message, bool $isInternalNote = false): ?SupportTicketReply
    {
        $ticket = SupportTicket::find($ticketId);

        if (! $ticket) {
            return null;
        }

        $reply = SupportTicketReply::create([
            'ticket_id' => $ticketId,
            'author_type' => Employee::class,
            'author_id' => $employee->id,
            'message' => $message,
            'is_internal' => $isInternalNote,
        ]);

        if (! $ticket->first_response_at && ! $isInternalNote) {
            $ticket->update(['first_response_at' => now()]);
        }

        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        if (! $isInternalNote && $ticket->user_id) {
            Notification::create([
                'user_id' => $ticket->user_id,
                'type' => 'support_reply',
                'title' => 'Support replied to your ticket',
                'message' => 'Ticket '.$ticket->ticket_number.' received a reply from support.',
            ]);
        }

        return $reply->fresh(['author']);
    }

    /**
     * Get satisfaction metrics
     *
     * @param  Carbon|null  $startDate  Start date for metrics
     * @param  Carbon|null  $endDate  End date for metrics
     * @return array Satisfaction metrics
     *
     * @see Requirements 9.4
     */
    public function getSatisfactionMetrics(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->startOfMonth();
        $endDate = $endDate ?? Carbon::now()->endOfMonth();

        $rated = CustomerFeedback::whereNotNull('rating')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalRated = $rated->count();
        $avgRating = $totalRated > 0 ? $rated->avg('rating') : 0;

        $ratingDistribution = [
            5 => $rated->where('rating', 5)->count(),
            4 => $rated->where('rating', 4)->count(),
            3 => $rated->where('rating', 3)->count(),
            2 => $rated->where('rating', 2)->count(),
            1 => $rated->where('rating', 1)->count(),
        ];

        $satisfiedCount = $ratingDistribution[5] + $ratingDistribution[4];
        $satisfactionPercentage = $totalRated > 0 ? ($satisfiedCount / $totalRated) * 100 : 0;

        return [
            'average_rating' => round($avgRating, 1),
            'total_rated' => $totalRated,
            'satisfaction_percentage' => round($satisfactionPercentage, 1),
            'distribution' => $ratingDistribution,
        ];
    }

    /**
     * Get customer feedback with filters
     *
     * @param  array  $filters  Filters including type, rating, status, per_page
     *
     * @see Requirements 9.5
     */
    public function getFeedback(array $filters = []): LengthAwarePaginator
    {
        $query = CustomerFeedback::with(['user', 'order', 'reviewer']);

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Get recent feedback
     *
     * @param  int  $limit  Number of feedback items to return
     */
    public function getRecentFeedback(int $limit = 10): Collection
    {
        return CustomerFeedback::with(['user', 'order'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Respond to customer feedback
     *
     * @param  int  $feedbackId  The feedback ID
     * @param  User  $reviewer  The user responding
     * @param  string  $response  The response message
     * @return CustomerFeedback|null The updated feedback or null if not found
     */
    public function respondToFeedback(int $feedbackId, User $reviewer, string $response): ?CustomerFeedback
    {
        $feedback = CustomerFeedback::find($feedbackId);

        if (! $feedback) {
            return null;
        }

        $feedback->update([
            'response' => $response,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'status' => 'reviewed',
        ]);

        // Log the response
        $this->auditService->log(
            'respond',
            'customer_feedback',
            $feedbackId,
            [
                'new_values' => [
                    'response' => $response,
                    'reviewed_by' => $reviewer->id,
                ],
            ]
        );

        return $feedback->fresh(['user', 'reviewer']);
    }

    /**
     * Get ticket statistics by status
     *
     * @return array Statistics by status
     */
    public function getTicketStatsByStatus(): array
    {
        return SupportTicket::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get ticket statistics by priority
     *
     * @return array Statistics by priority
     */
    public function getTicketStatsByPriority(): array
    {
        return SupportTicket::select('priority', DB::raw('count(*) as count'))
            ->whereIn('status', ['open', 'pending'])
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();
    }

    /**
     * Get ticket statistics by category
     *
     * @return array Statistics by category
     */
    public function getTicketStatsByCategory(): array
    {
        return SupportTicket::select('category', DB::raw('count(*) as count'))
            ->whereMonth('created_at', now()->month)
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();
    }

    /**
     * Get agent performance metrics
     *
     * @param  int|null  $agentId  Specific agent ID or null for all agents
     * @return Collection Agent performance data
     */
    public function getAgentPerformance(?int $agentId = null): Collection
    {
        $employees = Employee::query();

        if ($agentId) {
            $employees->where('id', $agentId);
        } else {
            $employees->whereIn('id', function ($q) {
                $q->select('assigned_to')->from('support_tickets')->whereNotNull('assigned_to');
            });
        }

        return $employees->get()->map(function ($employee) {
            $assigned = SupportTicket::where('assigned_to', $employee->id);
            $totalAssigned = (clone $assigned)->count();
            $resolvedCount = (clone $assigned)->where('status', 'resolved')->count();
            $openCount = (clone $assigned)->whereIn('status', ['open', 'pending', 'in_progress', 'waiting_customer'])->count();

            $avgResponseMinutes = (clone $assigned)->whereNotNull('first_response_at')->get()
                ->avg(function ($ticket) {
                    return $ticket->created_at->diffInMinutes($ticket->first_response_at);
                }) ?? 0;

            return [
                'id' => $employee->id,
                'name' => ($employee->first_name ?? '').' '.($employee->last_name ?? ''),
                'email' => $employee->email,
                'total_assigned' => $totalAssigned,
                'resolved_count' => $resolvedCount,
                'open_count' => $openCount,
                'resolution_rate' => $totalAssigned > 0
                    ? round(($resolvedCount / $totalAssigned) * 100, 1)
                    : 0,
                'avg_response_time' => $this->formatResponseTime($avgResponseMinutes / 60),
            ];
        });
    }

    /**
     * Get CS agents (users with CS role)
     */
    public function getCSAgents(): Collection
    {
        return User::where(function ($query) {
            $query->where('is_cs', true)
                ->orWhere('is_cs_agent', true);
        })->get();
    }

    /**
     * Get ticket trend chart data
     *
     * @param  string  $period  Period: 'week', 'month'
     * @return array Chart data with labels and values
     */
    public function getTicketTrendData(string $period = 'week'): array
    {
        $labels = [];
        $created = [];
        $resolved = [];

        $days = $period === 'week' ? 7 : 30;

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format($period === 'week' ? 'D' : 'd');

            $created[] = SupportTicket::whereDate('created_at', $date)->count();
            $resolved[] = SupportTicket::whereDate('resolved_at', $date)->count();
        }

        return [
            'labels' => $labels,
            'created' => $created,
            'resolved' => $resolved,
        ];
    }

    /**
     * Get feedback sentiment summary
     *
     * @return array Sentiment summary (positive, neutral, negative)
     */
    public function getFeedbackSentiment(): array
    {
        $feedback = CustomerFeedback::whereMonth('created_at', now()->month)->get();

        $positive = $feedback->where('rating', '>=', 4)->count();
        $neutral = $feedback->where('rating', 3)->count();
        $negative = $feedback->where('rating', '<', 3)->count();
        $total = $feedback->count();

        return [
            'positive' => [
                'count' => $positive,
                'percentage' => $total > 0 ? round(($positive / $total) * 100, 1) : 0,
            ],
            'neutral' => [
                'count' => $neutral,
                'percentage' => $total > 0 ? round(($neutral / $total) * 100, 1) : 0,
            ],
            'negative' => [
                'count' => $negative,
                'percentage' => $total > 0 ? round(($negative / $total) * 100, 1) : 0,
            ],
            'total' => $total,
        ];
    }

    /**
     * Get aggregated customer profile for support view
     */
    public function getCustomerProfile(int $userId): array
    {
        $user = User::with(['employee'])->find($userId);
        if (! $user) {
            return [];
        }

        $recentOrders = Order::with(['items.product'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentTickets = SupportTicket::with(['assignedTo'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $wishlist = Wishlist::with('product')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'user' => $user,
            'recent_orders' => $recentOrders,
            'recent_tickets' => $recentTickets,
            'wishlist' => $wishlist,
        ];
    }
}
