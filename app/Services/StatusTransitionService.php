<?php

namespace App\Services;

use App\Events\Dashboard\DashboardUpdated;
use App\Models\AuditLog;
use App\Models\Notification;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Status Transition Service
 *
 * Validates and enforces status transitions across all entities
 * Prevents invalid state changes and ensures data integrity
 */
class StatusTransitionService
{
    /**
     * Valid status transitions for each entity type
     */
    private static $allowedTransitions = [
        'order' => [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['processing', 'ready', 'shipped', 'out_for_delivery', 'delivered', 'done', 'cancelled'],
            'processing' => ['ready', 'shipped', 'out_for_delivery', 'delivered', 'done', 'cancelled'],
            'ready' => ['shipped', 'out_for_delivery', 'delivered', 'done', 'cancelled'],
            'shipped' => ['out_for_delivery', 'delivered', 'done', 'failed', 'cancelled'],
            'out_for_delivery' => ['delivered', 'done', 'failed', 'cancelled'],
            // Delivered is an intermediate success state; CS can mark final completion (done)
            'delivered' => ['done'],
            'done' => [], // Terminal success state (finalized)
            'cancelled' => [], // Final state
            'failed' => [], // Final state
            'refunded' => [], // Final state
            'returned' => [], // Final state
        ],
        'payment' => [
            'pending' => ['paid', 'failed', 'cancelled'],
            'paid' => ['refunded', 'partial'],
            'partial' => ['paid', 'refunded'],
            'failed' => ['pending', 'cancelled'],
            'refunded' => [], // Final state
            'cancelled' => [], // Final state
        ],
        'financial_transaction' => [
            'pending' => ['processing', 'cancelled', 'pending_approval'],
            'pending_approval' => ['approved', 'rejected'],
            'approved' => ['processing', 'cancelled'],
            'processing' => ['completed', 'failed'],
            'completed' => [], // Final state - locked
            'failed' => ['pending', 'cancelled'],
            'cancelled' => [], // Final state
            'rejected' => [], // Final state
        ],
        'delivery_assignment' => [
            'assigned' => ['accepted', 'rejected', 'cancelled'],
            'accepted' => ['picked_up', 'cancelled'],
            'picked_up' => ['in_transit', 'cancelled'],
            'in_transit' => ['delivered', 'failed'],
            'delivered' => [], // Final state
            'failed' => ['assigned', 'cancelled'],
            'rejected' => ['assigned', 'cancelled'],
            'cancelled' => [], // Final state
        ],
        'payroll_record' => [
            'draft' => ['approved', 'cancelled'],
            'approved' => ['submitted_to_finance', 'draft'],
            'submitted_to_finance' => ['paid', 'approved'],
            'paid' => [], // Final state
            'cancelled' => [], // Final state
        ],
        'support_ticket' => [
            'open' => ['in_progress', 'waiting_customer', 'resolved', 'closed'],
            'in_progress' => ['waiting_customer', 'resolved', 'closed'],
            'waiting_customer' => ['in_progress', 'resolved', 'closed'],
            'resolved' => ['closed'],
            'closed' => [], // Final state
        ],
    ];

    /**
     * Validate if a status transition is allowed
     *
     * @param  string  $entityType  Order, payment, financial_transaction, etc.
     * @param  string  $currentStatus  Current status
     * @param  string  $newStatus  Desired new status
     * @param  bool  $adminOverride  Allow admin to bypass restrictions
     * @return bool
     */
    public static function canTransition($entityType, $currentStatus, $newStatus, $adminOverride = false)
    {
        // Admin override allows any transition (but still logged)
        if ($adminOverride) {
            return true;
        }

        // Check if entity type exists
        if (! isset(self::$allowedTransitions[$entityType])) {
            Log::warning("Unknown entity type for status transition: {$entityType}");

            return false;
        }

        // Check if current status exists
        if (! isset(self::$allowedTransitions[$entityType][$currentStatus])) {
            Log::warning("Unknown current status for {$entityType}: {$currentStatus}");

            return false;
        }

        // Check if transition is allowed
        $allowed = self::$allowedTransitions[$entityType][$currentStatus];

        return in_array($newStatus, $allowed);
    }

    /**
     * Validate and execute status transition
     *
     * @param  object  $model  Eloquent model instance
     * @param  string  $statusField  Name of status field (default: 'status')
     * @param  string  $newStatus  Desired new status
     * @param  int|null  $userId  User performing the action (for audit)
     * @param  bool  $adminOverride  Allow admin override
     * @param  array  $additionalData  Additional data to update
     * @return bool Success status
     *
     * @throws Exception If transition is invalid
     */
    public static function transition($model, $statusField, $newStatus, $userId = null, $adminOverride = false, $additionalData = [])
    {
        $entityType = self::getEntityType($model);
        $currentStatus = $model->{$statusField};

        // Validate transition
        if (! self::canTransition($entityType, $currentStatus, $newStatus, $adminOverride)) {
            $error = "Invalid status transition for {$entityType}: {$currentStatus} → {$newStatus}";
            Log::error($error, [
                'model' => get_class($model),
                'model_id' => $model->id,
                'current_status' => $currentStatus,
                'new_status' => $newStatus,
            ]);
            throw new Exception($error);
        }

        // Store old values for audit
        $oldValues = $model->getAttributes();

        // Update status
        $updateData = array_merge([$statusField => $newStatus], $additionalData);
        $model->update($updateData);

        // Create audit log
        self::logTransition($model, $statusField, $currentStatus, $newStatus, $userId, $adminOverride);
        self::afterTransition($model, $statusField, $currentStatus, $newStatus, $userId);

        return true;
    }

    /**
     * Get entity type from model
     */
    private static function getEntityType($model)
    {
        $className = class_basename($model);

        $mapping = [
            'Order' => 'order',
            'FinancialTransaction' => 'financial_transaction',
            'DeliveryAssignment' => 'delivery_assignment',
            'PayrollRecord' => 'payroll_record',
            'SupportTicket' => 'support_ticket',
        ];

        return $mapping[$className] ?? strtolower($className);
    }

    /**
     * Log status transition for audit
     */
    private static function logTransition($model, $statusField, $oldStatus, $newStatus, $userId, $adminOverride)
    {
        try {
            $metadata = [
                'transition' => [
                    'entity_type' => self::getEntityType($model),
                    'status_field' => $statusField,
                    'from' => $oldStatus,
                    'to' => $newStatus,
                    'admin_override' => $adminOverride,
                    'timestamp' => now(),
                ],
            ];
            if (auth('employee')->check()) {
                $employee = auth('employee')->user();
                $metadata['actor'] = array_filter([
                    'guard' => 'employee',
                    'employee_id' => $employee?->id,
                    'employee_email' => $employee?->email,
                    'employee_code' => $employee?->employee_code,
                ], fn ($v) => $v !== null && $v !== '');
            }
            AuditLog::create([
                'user_id' => $userId ?? auth()->id(),
                'action' => 'status_transition',
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'old_values' => [$statusField => $oldStatus],
                'new_values' => [$statusField => $newStatus],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'session_id' => session()->getId(),
                'metadata' => $metadata,
            ]);
        } catch (Exception $e) {
            // Log error but don't fail the transition
            Log::error('Failed to create audit log for status transition', [
                'error' => $e->getMessage(),
                'model' => get_class($model),
                'model_id' => $model->id,
            ]);
        }
    }

    private static function afterTransition($model, string $statusField, $oldStatus, $newStatus, $userId): void
    {
        if ($statusField !== 'status') {
            return;
        }

        if (! ($model instanceof \App\Models\Order)) {
            return;
        }

        if ($oldStatus === $newStatus) {
            return;
        }

        $order = $model->fresh();

        if (! in_array($newStatus, ['out_for_delivery', 'delivered'], true) && Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'user_id')) {
            $orderUserId = $order->user_id ?? null;
            if ($orderUserId) {
                $title = 'Order Status Updated';
                $message = "Your order {$order->order_number} status changed: {$oldStatus} → {$newStatus}";
                $notification = [
                    'user_id' => $orderUserId,
                    'type' => 'order',
                    'title' => $title,
                    'message' => $message,
                    'icon' => 'fa-shopping-bag',
                    'color' => 'blue',
                ];
                if (Schema::hasColumn('notifications', 'link')) {
                    $notification['link'] = '/profile';
                }
                Notification::create($notification);
            }
        }

        if (class_exists(DashboardUpdated::class)) {
            $payload = [
                'type' => 'order_status_changed',
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'total' => $order->total ?? ($order->total_amount ?? null),
                    'updated_at' => optional($order->updated_at)->toIso8601String(),
                ],
                'from' => $oldStatus,
                'to' => $newStatus,
                'actor_employee_id' => $userId,
            ];

            foreach (['admin', 'it', 'finance', 'cs', 'hr', 'supervisor', 'vendor'] as $dash) {
                event(new DashboardUpdated($dash, $payload));
            }
        }
    }

    /**
     * Get allowed transitions for an entity type and current status
     */
    public static function getAllowedTransitions($entityType, $currentStatus)
    {
        return self::$allowedTransitions[$entityType][$currentStatus] ?? [];
    }

    /**
     * Check if status is a final state (cannot transition from)
     */
    public static function isFinalState($entityType, $status)
    {
        $allowed = self::$allowedTransitions[$entityType][$status] ?? [];

        return empty($allowed);
    }
}
