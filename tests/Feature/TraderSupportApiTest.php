<?php

namespace Tests\Feature;

use App\Models\Trader;
use App\Models\TraderSupportMessage;
use App\Models\TraderSupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class TraderSupportApiTest extends TestCase
{
    use RefreshDatabase;

    protected function makeApprovedTrader(): array
    {
        $user = User::factory()->create([
            'email' => 'trader+'.Str::lower(Str::random(6)).'@example.com',
            'password' => bcrypt('password123'),
            'is_trader' => true,
        ]);

        $trader = Trader::create([
            'user_id' => $user->id,
            'name' => 'Demo Trader',
            'contact_email' => $user->email,
            'status' => Trader::STATUS_APPROVED,
            'commission_rate' => 10,
        ]);

        $token = $user->createToken('test')->plainTextToken;

        return [$user, $trader, $token];
    }

    public function test_create_support_ticket_with_attachments(): void
    {
        [$user, $trader, $token] = $this->makeApprovedTrader();
        Storage::fake('public');

        $res = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/trader/support/tickets', [
                'subject' => 'Payment issue',
                'category' => 'payment',
                'priority' => 'high',
                'description' => 'I did not receive payout.',
            ]);

        $res->assertStatus(201)->assertJson(['success' => true]);

        $this->assertDatabaseHas('trader_support_tickets', [
            'trader_id' => $trader->id,
            'subject' => 'Payment issue',
            'category' => 'payment',
            'priority' => 'high',
            'status' => 'open',
        ]);

        $ticketId = TraderSupportTicket::first()->id;

        $resAttachments = $this->withHeader('Authorization', 'Bearer '.$token)
            ->post('/api/trader/support/tickets', [
                'subject' => 'With files',
                'category' => 'technical',
                'priority' => 'medium',
                'description' => 'See screenshots',
                'attachments' => [
                    UploadedFile::fake()->create('shot1.png', 10, 'image/png'),
                    UploadedFile::fake()->create('shot2.png', 10, 'image/png'),
                ],
            ]);
        $resAttachments->assertStatus(201);

        $ticket = TraderSupportTicket::where('subject', 'With files')->firstOrFail();
        $firstMessage = TraderSupportMessage::where('ticket_id', $ticket->id)->first();
        $this->assertNotNull($firstMessage);
        $this->assertIsArray($firstMessage->attachments);
        $this->assertCount(2, $firstMessage->attachments);
    }

    public function test_reply_to_ticket_and_status_changes(): void
    {
        [$user, $trader, $token] = $this->makeApprovedTrader();
        $ticket = TraderSupportTicket::create([
            'trader_id' => $trader->id,
            'subject' => 'Order issue',
            'category' => 'order_issue',
            'priority' => 'urgent',
            'description' => 'Problem with order X',
            'status' => 'open',
        ]);

        $res = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/trader/support/tickets/{$ticket->id}/reply", [
                'message' => 'Additional details provided.',
            ]);
        $res->assertOk()->assertJson(['success' => true]);

        $ticket->refresh();
        $this->assertEquals('in_progress', $ticket->status);
        $this->assertDatabaseHas('trader_support_messages', [
            'ticket_id' => $ticket->id,
            'sender_id' => $user->id,
        ]);
    }

    public function test_close_and_reopen_ticket(): void
    {
        [$user, $trader, $token] = $this->makeApprovedTrader();
        $ticket = TraderSupportTicket::create([
            'trader_id' => $trader->id,
            'subject' => 'General inquiry',
            'category' => 'general',
            'priority' => 'low',
            'description' => 'Just a question',
            'status' => 'open',
        ]);

        $closeRes = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/trader/support/tickets/{$ticket->id}/close");
        $closeRes->assertOk()->assertJson(['success' => true]);
        $this->assertEquals('closed', $ticket->fresh()->status);

        $reopenRes = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/trader/support/tickets/{$ticket->id}/reopen", [
                'reason' => 'Issue persists',
            ]);
        $reopenRes->assertOk()->assertJson(['success' => true]);
        $this->assertEquals('open', $ticket->fresh()->status);
        $this->assertDatabaseHas('trader_support_messages', [
            'ticket_id' => $ticket->id,
            'sender_id' => $user->id,
            'message' => 'Issue persists',
        ]);
    }
}
