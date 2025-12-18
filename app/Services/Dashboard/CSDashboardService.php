<?php

namespace App\Services\Dashboard;

use App\Models\CustomerFeedback;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
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

        // Pending tickets (awaiting response)
        $pendingTickets = SupportTicket::where('status', 'pending')->count();

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
     * @param float $hours Response time in hours
     * @return string Formatted response time
     */
    protected function formatResponseTime(float $hours): string
    {
        if ($hours < 1) {
            return round($hours * 60) . ' min';
        } elseif ($hours < 24) {
            return round($hours, 1) . ' hrs';
        } else {
            return round($hours / 24, 1) . ' days';
        }
    }

    /**
     * Get tickets with filters and pagination
     * 
     * @param array $filters Filters including status, priority, assigned_to, search, per_page
     * @return LengthAwarePaginator
     * @see Requirements 9.2
     */
    public function getTickets(array $filters = []): LengthAwarePaginator
    {
        $query = SupportTicket::with(['user', 'assignedAgent', 'order']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['search'])) {
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

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        // Default sort by priority and creation date
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        
        if ($sortBy === 'priority') {
            // Custom priority ordering: urgent > high > medium > low
            $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low') " . $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Get recent tickets
     * 
     * @param int $limit Number of tickets to return
     * @return Collection
     */
    public function getRecentTickets(int $limit = 10): Collection
    {
        return SupportTicket::with(['user', 'assignedAgent'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get urgent tickets (high priority and open/pending)
     * 
     * @param int $limit Number of tickets to return
     * @return Collection
     */
    public function getUrgentTickets(int $limit = 10): Collection
    {
        return SupportTicket::with(['user', 'assignedAgent'])
            ->whereIn('priority', ['urgent', 'high'])
            ->whereIn('status', ['open', 'pending'])
            ->orderByRaw("FIELD(priority, 'urgent', 'high')")
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Assign a ticket to an agent
     * 
     * @param int $ticketId The ticket ID
     * @param int $agentId The agent user ID
     * @param User $assigner The user performing the assignment
     * @return SupportTicket|null The updated ticket or null if not found
     * @see Requirements 9.3
     */
    public function assignTicket(int $ticketId, int $agentId, User $assigner): ?SupportTicket
    {
        $ticket = SupportTicket::find($ticketId);

        if (!$ticket) {
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

        return $ticket->fresh(['user', 'assignedAgent']);
    }


    /**
     * Update ticket status
     * 
     * @param int $ticketId The ticket ID
     * @param string $status New status
     * @param User $updater The user updating the status
     * @return SupportTicket|null The updated ticket or null if not found
     */
    public function updateTicketStatus(int $ticketId, string $status, User $updater): ?SupportTicket
    {
        $ticket = SupportTicket::find($ticketId);

        if (!$ticket) {
            return null;
        }

        $oldStatus = $ticket->status;
        $updateData = ['status' => $status];

        // Set resolved_at timestamp when resolving
        if ($status === 'resolved' && !$ticket->resolved_at) {
            $updateData['resolved_at'] = now();
        }

        // Set closed_at timestamp when closing
        if ($status === 'closed' && !$ticket->closed_at) {
            $updateData['closed_at'] = now();
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

        return $ticket->fresh();
    }

    /**
     * Add a reply to a ticket
     * 
     * @param int $ticketId The ticket ID
     * @param User $user The user adding the reply
     * @param string $message The reply message
     * @param bool $isInternalNote Whether this is an internal note
     * @return TicketReply|null The created reply or null if ticket not found
     */
    public function addReply(int $ticketId, User $user, string $message, bool $isInternalNote = false): ?TicketReply
    {
        $ticket = SupportTicket::find($ticketId);

        if (!$ticket) {
            return null;
        }

        // Create the reply
        $reply = TicketReply::create([
            'ticket_id' => $ticketId,
            'user_id' => $user->id,
            'message' => $message,
            'is_internal_note' => $isInternalNote,
        ]);

        // Update first_response_at if this is the first agent response
        if (!$ticket->first_response_at && !$isInternalNote && $ticket->user_id !== $user->id) {
            $ticket->update(['first_response_at' => now()]);
        }

        // Update ticket status to pending if it was open
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'pending']);
        }

        return $reply->fresh(['user']);
    }

    /**
     * Get satisfaction metrics
     * 
     * @param Carbon|null $startDate Start date for metrics
     * @param Carbon|null $endDate End date for metrics
     * @return array Satisfaction metrics
     * @see Requirements 9.4
     */
    public function getSatisfactionMetrics(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->startOfMonth();
        $endDate = $endDate ?? Carbon::now()->endOfMonth();

        // Get tickets with satisfaction ratings
        $ratedTickets = SupportTicket::whereNotNull('satisfaction_rating')
            ->whereBetween('resolved_at', [$startDate, $endDate])
            ->get();

        $totalRated = $ratedTickets->count();
        $avgRating = $totalRated > 0 ? $ratedTickets->avg('satisfaction_rating') : 0;

        // Rating distribution
        $ratingDistribution = [
            5 => $ratedTickets->where('satisfaction_rating', 5)->count(),
            4 => $ratedTickets->where('satisfaction_rating', 4)->count(),
            3 => $ratedTickets->where('satisfaction_rating', 3)->count(),
            2 => $ratedTickets->where('satisfaction_rating', 2)->count(),
            1 => $ratedTickets->where('satisfaction_rating', 1)->count(),
        ];

        // Calculate satisfaction percentage (4 and 5 stars)
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
     * @param array $filters Filters including type, rating, status, per_page
     * @return LengthAwarePaginator
     * @see Requirements 9.5
     */
    public function getFeedback(array $filters = []): LengthAwarePaginator
    {
        $query = CustomerFeedback::with(['user', 'order', 'reviewer']);

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
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
     * @param int $limit Number of feedback items to return
     * @return Collection
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
     * @param int $feedbackId The feedback ID
     * @param User $reviewer The user responding
     * @param string $response The response message
     * @return CustomerFeedback|null The updated feedback or null if not found
     */
    public function respondToFeedback(int $feedbackId, User $reviewer, string $response): ?CustomerFeedback
    {
        $feedback = CustomerFeedback::find($feedbackId);

        if (!$feedback) {
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
     * @param int|null $agentId Specific agent ID or null for all agents
     * @return Collection Agent performance data
     */
    public function getAgentPerformance(?int $agentId = null): Collection
    {
        $query = User::whereHas('assignedTickets')
            ->withCount([
                'assignedTickets as total_assigned',
                'assignedTickets as resolved_count' => function ($q) {
                    $q->where('status', 'resolved');
                },
                'assignedTickets as open_count' => function ($q) {
                    $q->whereIn('status', ['open', 'pending']);
                },
            ]);

        if ($agentId) {
            $query->where('id', $agentId);
        }

        return $query->get()->map(function ($agent) {
            $avgResponseTime = SupportTicket::where('assigned_to', $agent->id)
                ->whereNotNull('first_response_at')
                ->get()
                ->avg(function ($ticket) {
                    return $ticket->created_at->diffInMinutes($ticket->first_response_at);
                });

            $avgSatisfaction = SupportTicket::where('assigned_to', $agent->id)
                ->whereNotNull('satisfaction_rating')
                ->avg('satisfaction_rating');

            return [
                'id' => $agent->id,
                'name' => $agent->name,
                'email' => $agent->email,
                'total_assigned' => $agent->total_assigned,
                'resolved_count' => $agent->resolved_count,
                'open_count' => $agent->open_count,
                'resolution_rate' => $agent->total_assigned > 0 
                    ? round(($agent->resolved_count / $agent->total_assigned) * 100, 1) 
                    : 0,
                'avg_response_time' => $this->formatResponseTime(($avgResponseTime ?? 0) / 60),
                'avg_satisfaction' => round($avgSatisfaction ?? 0, 1),
            ];
        });
    }

    /**
     * Get CS agents (users with CS role)
     * 
     * @return Collection
     */
    public function getCSAgents(): Collection
    {
        return User::where(function ($query) {
            $query->where('is_cs', true)
                  ->orWhere('dashboard_role', 'cs')
                  ->orWhere('role', 'cs');
        })->get();
    }

    /**
     * Get ticket trend chart data
     * 
     * @param string $period Period: 'week', 'month'
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
}
