<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Order;
use App\Services\OrderFinalizationService;
use App\Services\OrderStatusManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class OrderObserver
{
    public function __construct(
        protected OrderStatusManager $statusManager,
        protected OrderFinalizationService $finalizationService
    ) {}

    public function saving(Order $order): void
    {
        if (! $order->isDirty('status')) {
            return;
        }

        $from = $this->statusManager->normalize((string) $order->getOriginal('status'));
        $this->statusManager->setNormalizedStatus($order);
        $to = $this->statusManager->normalize((string) $order->status);

        if ($from === $to) {
            return;
        }

        $adminOverride = $this->isAdminOverride();
        if (! $this->statusManager->canTransition($from, $to, $adminOverride)) {
            throw ValidationException::withMessages([
                'status' => 'Invalid order status transition',
            ]);
        }

        if ($this->statusManager->isTerminal($from) && ! $adminOverride) {
            throw ValidationException::withMessages([
                'status' => 'Order is in a terminal status',
            ]);
        }
    }

    public function updated(Order $order): void
    {
        if (! $order->wasChanged('status')) {
            return;
        }

        $old = $this->statusManager->normalize((string) $order->getOriginal('status'));
        $new = $this->statusManager->normalize((string) $order->status);

        if (Schema::hasTable('audit_logs')) {
            AuditLog::log('order_status_changed', $order, ['status' => $old], ['status' => $new], [
                'source' => 'observer',
                'guard' => $this->activeGuard(),
            ]);
        }

        $this->finalizationService->finalizeIfNeeded($order);
    }

    private function isAdminOverride(): bool
    {
        $employee = Auth::guard('employee')->user();
        if ($employee && ($employee->is_admin ?? false)) {
            return true;
        }

        $web = Auth::guard('web')->user();
        if ($web && ($web->is_admin ?? false)) {
            return true;
        }

        return false;
    }

    private function activeGuard(): ?string
    {
        if (Auth::guard('employee')->check()) {
            return 'employee';
        }
        if (Auth::guard('web')->check()) {
            return 'web';
        }

        return null;
    }
}

