<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AuditService;
use App\Services\Dashboard\CSDashboardService;
use App\Services\Dashboard\ExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Customer Support Dashboard Controller
 * 
 * Handles all CS dashboard functionality including:
 * - Dashboard overview with ticket KPIs
 * - Ticket management and assignment
 * - Customer feedback management
 * 
 * @see Requirements 9.1, 9.2, 9.5
 */
class CSDashboardController extends Controller
{
    public function __construct(
        protected CSDashboardService $csService,
        protected AuditService $auditService,
        protected ExportService $exportService
    ) {
        // Apply CS role middleware to all methods
        $this->middleware('dashboard.role:cs,admin');
    }

    /**
     * Display the CS dashboard overview
     * Shows KPI cards, ticket trends, and recent activity
     * 
     * @see Requirements 9.1
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'week');

        $data = [
            'kpis' => $this->csService->getKPIMetrics(),
            'ticketTrend' => $this->csService->getTicketTrendData($period),
            'recentTickets' => $this->csService->getRecentTickets(10),
            'urgentTickets' => $this->csService->getUrgentTickets(5),
            'ticketsByStatus' => $this->csService->getTicketStatsByStatus(),
            'ticketsByPriority' => $this->csService->getTicketStatsByPriority(),
            'ticketsByCategory' => $this->csService->getTicketStatsByCategory(),
            'satisfactionMetrics' => $this->csService->getSatisfactionMetrics(),
            'feedbackSentiment' => $this->csService->getFeedbackSentiment(),
            'recentFeedback' => $this->csService->getRecentFeedback(5),
            'period' => $period,
        ];

        return view('dashboard.cs.index', $data);
    }

    /**
     * Display tickets page
     * Shows paginated list of tickets with filters
     * 
     * @see Requirements 9.2
     */
    public function tickets(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'status' => $request->get('status'),
            'priority' => $request->get('priority'),
            'category' => $request->get('category'),
            'assigned_to' => $request->get('assigned_to'),
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $tickets = $this->csService->getTickets($filters);
        $agents = $this->csService->getCSAgents();

        return view('dashboard.cs.tickets', [
            'tickets' => $tickets,
            'agents' => $agents,
            'filters' => $filters,
        ]);
    }


    /**
     * Display single ticket details
     * 
     * @param int $ticketId The ticket ID
     */
    public function showTicket(int $ticketId)
    {
        $ticket = \App\Models\SupportTicket::with(['user', 'assignedAgent', 'order', 'replies.user'])
            ->findOrFail($ticketId);
        
        $agents = $this->csService->getCSAgents();

        return view('dashboard.cs.ticket-show', [
            'ticket' => $ticket,
            'agents' => $agents,
        ]);
    }

    /**
     * Assign a ticket to an agent
     * 
     * @see Requirements 9.3
     */
    public function assignTicket(Request $request, int $ticketId)
    {
        $request->validate([
            'agent_id' => 'required|integer|exists:users,id',
        ]);

        $ticket = $this->csService->assignTicket(
            $ticketId,
            $request->input('agent_id'),
            Auth::user()
        );

        if (!$ticket) {
            return redirect()->back()->with('error', __('Ticket not found.'));
        }

        return redirect()->back()->with('success', __('Ticket assigned successfully.'));
    }

    /**
     * Update ticket status
     */
    public function updateTicketStatus(Request $request, int $ticketId)
    {
        $request->validate([
            'status' => 'required|string|in:open,pending,in_progress,resolved,closed',
        ]);

        $ticket = $this->csService->updateTicketStatus(
            $ticketId,
            $request->input('status'),
            Auth::user()
        );

        if (!$ticket) {
            return redirect()->back()->with('error', __('Ticket not found.'));
        }

        return redirect()->back()->with('success', __('Ticket status updated successfully.'));
    }

    /**
     * Add a reply to a ticket
     */
    public function replyToTicket(Request $request, int $ticketId)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'is_internal_note' => 'sometimes|boolean',
        ]);

        $reply = $this->csService->addReply(
            $ticketId,
            Auth::user(),
            $request->input('message'),
            $request->boolean('is_internal_note', false)
        );

        if (!$reply) {
            return redirect()->back()->with('error', __('Ticket not found.'));
        }

        return redirect()->back()->with('success', __('Reply added successfully.'));
    }

    /**
     * Display feedback page
     * Shows customer feedback with filters
     * 
     * @see Requirements 9.5
     */
    public function feedback(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'type' => $request->get('type'),
            'rating' => $request->get('rating'),
            'status' => $request->get('status'),
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $feedback = $this->csService->getFeedback($filters);
        $satisfactionMetrics = $this->csService->getSatisfactionMetrics();
        $feedbackSentiment = $this->csService->getFeedbackSentiment();

        return view('dashboard.cs.feedback', [
            'feedback' => $feedback,
            'satisfactionMetrics' => $satisfactionMetrics,
            'feedbackSentiment' => $feedbackSentiment,
            'filters' => $filters,
        ]);
    }

    /**
     * Respond to customer feedback
     */
    public function respondToFeedback(Request $request, int $feedbackId)
    {
        $request->validate([
            'response' => 'required|string|max:2000',
        ]);

        $feedback = $this->csService->respondToFeedback(
            $feedbackId,
            Auth::user(),
            $request->input('response')
        );

        if (!$feedback) {
            return redirect()->back()->with('error', __('Feedback not found.'));
        }

        return redirect()->back()->with('success', __('Response submitted successfully.'));
    }

    /**
     * Display agent performance page
     */
    public function agentPerformance(Request $request)
    {
        $agentId = $request->get('agent_id');
        $performance = $this->csService->getAgentPerformance($agentId ? (int) $agentId : null);
        $agents = $this->csService->getCSAgents();

        return view('dashboard.cs.agent-performance', [
            'performance' => $performance,
            'agents' => $agents,
            'selectedAgentId' => $agentId,
        ]);
    }

    /**
     * Export tickets to CSV
     */
    public function exportTickets(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'priority' => $request->get('priority'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'per_page' => 10000, // Get all for export
        ];

        $tickets = $this->csService->getTickets($filters);

        $columns = [
            'ticket_number' => 'Ticket #',
            'subject' => 'Subject',
            'user.name' => 'Customer',
            'category' => 'Category',
            'priority' => 'Priority',
            'status' => 'Status',
            'assignedAgent.name' => 'Assigned To',
            'created_at' => 'Created',
            'resolved_at' => 'Resolved',
        ];

        // Log the export action
        $this->auditService->log(
            'export',
            'support_ticket',
            null,
            [
                'new_values' => [
                    'filters' => $filters,
                    'record_count' => $tickets->total(),
                ],
            ]
        );

        return $this->exportService->exportToCSV(
            $tickets->getCollection(),
            $columns,
            'tickets_' . date('Y-m-d') . '.csv'
        );
    }

    /**
     * Export feedback to CSV
     */
    public function exportFeedback(Request $request)
    {
        $filters = [
            'type' => $request->get('type'),
            'rating' => $request->get('rating'),
            'status' => $request->get('status'),
            'per_page' => 10000, // Get all for export
        ];

        $feedback = $this->csService->getFeedback($filters);

        $columns = [
            'id' => 'ID',
            'user.name' => 'Customer',
            'type' => 'Type',
            'rating' => 'Rating',
            'message' => 'Message',
            'status' => 'Status',
            'created_at' => 'Date',
        ];

        // Log the export action
        $this->auditService->log(
            'export',
            'customer_feedback',
            null,
            [
                'new_values' => [
                    'filters' => $filters,
                    'record_count' => $feedback->total(),
                ],
            ]
        );

        return $this->exportService->exportToCSV(
            $feedback->getCollection(),
            $columns,
            'feedback_' . date('Y-m-d') . '.csv'
        );
    }
}
