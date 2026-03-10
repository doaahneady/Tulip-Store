<?php

namespace App\Services;

use App\Models\Order;

class OrderStatusManager
{
    public function normalize(?string $status): string
    {
        $s = strtolower(trim((string) $status));
        if ($s === '') {
            return 'pending';
        }

        $aliases = (array) config('order_statuses.aliases', []);
        if (isset($aliases[$s])) {
            $s = (string) $aliases[$s];
        }

        return $s;
    }

    public function isTerminal(string $status): bool
    {
        $s = $this->normalize($status);
        $terminal = (array) config('order_statuses.terminal', []);

        return in_array($s, $terminal, true);
    }

    public function canTransition(string $from, string $to, bool $adminOverride = false): bool
    {
        $fromN = $this->normalize($from);
        $toN = $this->normalize($to);

        return StatusTransitionService::canTransition('order', $fromN, $toN, $adminOverride);
    }

    public function allowedNext(string $from): array
    {
        $fromN = $this->normalize($from);

        return StatusTransitionService::getAllowedTransitions('order', $fromN);
    }

    public function shouldFinalizeOnStatus(string $status): bool
    {
        return $this->isTerminal($status);
    }

    public function setNormalizedStatus(Order $order): void
    {
        if (! $order->isDirty('status')) {
            return;
        }

        $order->status = $this->normalize((string) $order->status);
    }
}

