<?php

namespace App\Events\Dashboard;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

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
        $data = [
            'dashboard' => $this->dashboard,
            'payload' => $this->payload,
            'timestamp' => now()->toISOString(),
        ];
        if (app()->environment(['local', 'testing'])) {
            $key = 'test.dashboard_events.'.$this->dashboard;
            $events = Cache::get($key, []);
            if (! is_array($events)) {
                $events = [];
            }
            $events[] = $data;
            if (count($events) > 250) {
                $events = array_slice($events, -250);
            }
            Cache::put($key, $events, now()->addMinutes(15));
        }

        return $data;
    }
}
