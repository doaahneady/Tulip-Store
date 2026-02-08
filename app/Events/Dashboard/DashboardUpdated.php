<?php

namespace App\Events\Dashboard;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $dashboard,
        public array $payload = []
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('dashboard.'.$this->dashboard);
    }

    public function broadcastAs(): string
    {
        return 'dashboard.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'dashboard' => $this->dashboard,
            'payload' => $this->payload,
            'timestamp' => now()->toISOString(),
        ];
    }
}
