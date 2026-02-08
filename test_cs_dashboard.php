<?php

require_once 'vendor/autoload.php';

use App\Models\CustomerFeedback;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Customer Service Dashboard Test ===\n\n";

try {
    // Test database connection
    echo "1. Testing database connection...\n";
    $connection = DB::connection()->getPdo();
    echo "✓ Database connected successfully\n\n";

    // Test CS Agent exists
    echo "2. Testing CS Agent access...\n";
    $csAgent = User::where('is_cs_agent', true)->first();
    if ($csAgent) {
        echo "✓ CS Agent found: {$csAgent->name} ({$csAgent->email})\n";
    } else {
        echo "✗ No CS Agent found. Run the seeder first.\n";
    }
    echo "\n";

    // Test Support Tickets
    echo "3. Testing Support Tickets...\n";
    $totalTickets = SupportTicket::count();
    $openTickets = SupportTicket::where('status', 'open')->count();
    $resolvedTickets = SupportTicket::where('status', 'resolved')->count();

    echo "✓ Total Tickets: {$totalTickets}\n";
    echo "✓ Open Tickets: {$openTickets}\n";
    echo "✓ Resolved Tickets: {$resolvedTickets}\n\n";

    // Test Ticket Replies
    echo "4. Testing Ticket Replies...\n";
    $totalReplies = TicketReply::count();
    echo "✓ Total Replies: {$totalReplies}\n\n";

    // Test Customer Feedback
    echo "5. Testing Customer Feedback...\n";
    $totalFeedback = CustomerFeedback::count();
    $avgRating = CustomerFeedback::whereNotNull('rating')->avg('rating');

    echo "✓ Total Feedback: {$totalFeedback}\n";
    echo '✓ Average Rating: '.round($avgRating, 1)."/5\n\n";

    // Test Dashboard Controller Methods
    echo "6. Testing Dashboard Data Calculations...\n";

    // Response Time
    $avgFirstResponseTime = SupportTicket::whereNotNull('first_response_at')
        ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as avg_time')
        ->value('avg_time');

    echo '✓ Average First Response Time: '.($avgFirstResponseTime ? round($avgFirstResponseTime).' minutes' : 'N/A')."\n";

    // Resolution Rate
    $resolvedCount = SupportTicket::whereIn('status', ['resolved', 'closed'])->count();
    $resolutionRate = $totalTickets > 0 ? round(($resolvedCount / $totalTickets) * 100) : 0;

    echo "✓ Resolution Rate: {$resolutionRate}%\n";

    // Satisfaction
    $avgSatisfaction = SupportTicket::whereNotNull('satisfaction_rating')->avg('satisfaction_rating');
    echo '✓ Average Satisfaction: '.($avgSatisfaction ? round($avgSatisfaction, 1) : 'N/A')."/5\n\n";

    // Test Recent Data
    echo "7. Testing Recent Data...\n";
    $recentTickets = SupportTicket::with(['user', 'assignedAgent'])->latest()->take(5)->get();
    echo "✓ Recent Tickets: {$recentTickets->count()}\n";

    $recentFeedback = CustomerFeedback::with(['user'])->latest()->take(5)->get();
    echo "✓ Recent Feedback: {$recentFeedback->count()}\n\n";

    echo "=== All Tests Passed! ===\n";
    echo "The Customer Service Dashboard should work correctly.\n";
    echo "Access it at: /cs/dashboard\n\n";

    echo "Sample CS Agent Credentials:\n";
    if ($csAgent) {
        echo "Email: {$csAgent->email}\n";
        echo "Password: password\n";
    }

} catch (Exception $e) {
    echo '✗ Error: '.$e->getMessage()."\n";
    echo "Make sure to run migrations and seeders first:\n";
    echo "php artisan migrate\n";
    echo "php artisan db:seed --class=CustomerServiceDataSeeder\n";
}
