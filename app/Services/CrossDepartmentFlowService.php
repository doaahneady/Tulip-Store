<?php

namespace App\Services;

use App\Models\ApiError;
use App\Models\AuditLog;
use App\Models\DeliveryAssignment;
use App\Models\FinancialTransaction;
use App\Models\IPBlacklist;
use App\Models\Notification;
use App\Models\Order;
use App\Models\PayrollRecord;
use App\Models\SecurityAuditLog;
use App\Models\SupportTicket;
use App\Models\SystemAlert;
use App\Models\SystemLog;
use App\Models\User;
use App\Models\CustomerBalanceAudit;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Cross-Department Flow Service
 *
 * Handles flows that span multiple departments
 * Ensures proper data flow and synchronization across dashboards
 */
class CrossDepartmentFlowService
{
    /**
     * Flow: Order Completion â†’ Revenue Recognition â†’ Commission â†’ Store Payout
     *
     * Triggered when order is marked as delivered
     */
    public static function handleOrderCompletion($orderId, $userId = null)
    {
        return TransactionService::executeFinancial(function () use ($orderId, $userId) {
            $order = Order::with(['items.product', 'store'])->findOrFail($orderId);

            // Validate order can be completed
            if ($order->status !== 'out_for_delivery') {
                throw new Exception("Order {$orderId} is not in 'out_for_delivery' status");
            }

            // 1. Update order status
            StatusTransitionService::transition($order, 'status', 'delivered', $userId);
            $order = $order->fresh();

            // Cash orders are paid on delivery; card/credit should already be paid
            if (($order->payment_method ?? null) === 'cash' && ($order->payment_status ?? null) !== 'paid') {
                $order->update(['payment_status' => 'paid']);
            }

            // 2. Calculate and create commission transaction
            $commissionRate = $order->store->commission_rate ?? 0.05; // Default 5%
            $commissionAmount = $order->subtotal * $commissionRate;

            $commissionTransaction = FinancialTransaction::create([
                'transaction_id' => FinancialTransaction::generateTransactionId('commission'),
                'order_id' => $order->id,
                'store_id' => $order->store_id,
                'type' => 'commission',
                'status' => 'pending_approval',
                'amount' => $commissionAmount,
                'currency' => 'USD',
                'description' => "Platform commission for order {$order->order_number}",
                'approval_status' => 'pending',
            ]);

            // 4. Update inventory (deduct sold products)
            foreach ($order->items as $item) {
                if ($item->product && $item->product->track_inventory) {
                    // Check low stock threshold
                    if ($item->product->stock_quantity <= $item->product->low_stock_threshold) {
                        // Create inventory alert (if inventory_alerts table exists)
                        self::createInventoryAlert($item->product);
                    }
                }
            }

            // 5. Update store balance (if store_balances table exists)
            // This would be handled by a separate service or trigger

            // 6. Create notifications
            if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                $cols = [
                    'user_id' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'user_id'),
                    'type' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'type'),
                    'title' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'title'),
                    'message' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'message'),
                    'icon' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'icon'),
                    'color' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'color'),
                    'link' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'link'),
                    'data' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'data'),
                ];
                if ($cols['user_id'] && $order->user_id) {
                    $payload = [
                        'user_id' => $order->user_id,
                        'type' => $cols['type'] ? 'order_delivered' : null,
                        'title' => $cols['title'] ? 'Order Delivered' : null,
                        'message' => $cols['message'] ? "Your order {$order->order_number} has been delivered." : null,
                        'icon' => $cols['icon'] ? 'fa-shopping-bag' : null,
                        'color' => $cols['color'] ? 'green' : null,
                        'link' => $cols['link'] ? '/profile' : null,
                        'data' => $cols['data'] ? ['order_id' => $order->id] : null,
                    ];
                    $payload = array_filter($payload, fn ($v) => $v !== null);
                    if ($cols['data'] && isset($payload['data']) && is_array($payload['data'])) {
                        $payload['data'] = json_encode($payload['data']);
                    }
                    \Illuminate\Support\Facades\DB::table('notifications')->insert($payload + [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if ($order->store && $order->store->owner_id) {
                if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                    $cols = [
                        'user_id' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'user_id'),
                        'type' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'type'),
                        'title' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'title'),
                        'message' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'message'),
                        'icon' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'icon'),
                        'color' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'color'),
                        'link' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'link'),
                        'data' => \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'data'),
                    ];
                    if ($cols['user_id']) {
                        $payload = [
                            'user_id' => $order->store->owner_id,
                            'type' => $cols['type'] ? 'order_completed' : null,
                            'title' => $cols['title'] ? 'Order Completed' : null,
                            'message' => $cols['message'] ? "Order {$order->order_number} has been completed. Commission: {$commissionAmount}" : null,
                            'icon' => $cols['icon'] ? 'fa-check-circle' : null,
                            'color' => $cols['color'] ? 'blue' : null,
                            'link' => $cols['link'] ? '/dashboard/vendor' : null,
                            'data' => $cols['data'] ? ['order_id' => $order->id, 'commission' => $commissionAmount] : null,
                        ];
                        $payload = array_filter($payload, fn ($v) => $v !== null);
                        if ($cols['data'] && isset($payload['data']) && is_array($payload['data'])) {
                            $payload['data'] = json_encode($payload['data']);
                        }
                        \Illuminate\Support\Facades\DB::table('notifications')->insert($payload + [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // 7. Create audit log
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'order_completed',
                'model_type' => Order::class,
                'model_id' => $order->id,
                'old_values' => ['status' => 'out_for_delivery'],
                'new_values' => [
                    'status' => 'delivered',
                    'commission_transaction_id' => $commissionTransaction->id,
                ],
            ]);

            // Broadcast order status change for real-time dashboard updates
            \broadcast(new \App\Events\OrderStatusUpdated($order->fresh()));

            return [
                'order' => $order->fresh(),
                'commission_transaction' => $commissionTransaction,
            ];
        }, 'order_completion_flow', true, $userId);
    }

    /**
     * Flow: HR Payroll Submission â†’ Finance Approval â†’ Payment Processing
     */
    public static function handlePayrollSubmissionToFinance($payrollRecordId, $userId = null)
    {
        return TransactionService::executeFinancial(function () use ($payrollRecordId, $userId) {
            $payrollRecord = PayrollRecord::with('employee')->findOrFail($payrollRecordId);

            // Validate payroll record status
            if ($payrollRecord->status !== 'approved') {
                throw new Exception("Payroll record {$payrollRecordId} must be approved before submission to finance");
            }

            // Update payroll record status
            StatusTransitionService::transition($payrollRecord, 'status', 'submitted_to_finance', $userId);

            // Create financial transaction
            $transaction = FinancialTransaction::create([
                'transaction_id' => FinancialTransaction::generateTransactionId('payroll'),
                'user_id' => $payrollRecord->employee->user_id ?? null,
                'type' => 'payroll',
                'status' => 'pending_approval',
                'amount' => $payrollRecord->net_pay,
                'currency' => 'USD',
                'description' => "Payroll payment for {$payrollRecord->employee->full_name} - {$payrollRecord->pay_period}",
                'metadata' => [
                    'payroll_record_id' => $payrollRecord->id,
                    'employee_id' => $payrollRecord->employee_id,
                    'pay_period' => $payrollRecord->pay_period,
                ],
                'approval_status' => 'pending',
            ]);

            // Notify finance team
            $financeEmployees = \App\Models\Employee::where('is_finance', true)
                ->orWhere('is_admin', true)
                ->pluck('id');

            foreach ($financeEmployees as $financeEmployeeId) {
                Notification::create([
                    'user_id' => $financeEmployeeId,
                    'type' => 'payroll_submitted',
                    'title' => 'Payroll Submitted for Approval',
                    'message' => "Payroll record for {$payrollRecord->employee->full_name} ({$payrollRecord->pay_period}) requires finance approval.",
                    'data' => [
                        'payroll_record_id' => $payrollRecord->id,
                        'transaction_id' => $transaction->id,
                    ],
                ]);
            }

            // Audit log
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'payroll_submitted_to_finance',
                'model_type' => PayrollRecord::class,
                'model_id' => $payrollRecord->id,
                'old_values' => ['status' => 'approved'],
                'new_values' => [
                    'status' => 'submitted_to_finance',
                    'transaction_id' => $transaction->id,
                ],
            ]);

            return [
                'payroll_record' => $payrollRecord->fresh(),
                'transaction' => $transaction,
            ];
        }, 'payroll_submission_flow', true, $userId);
    }

    /**
     * Flow: Support Ticket â†’ Order Refund â†’ Financial Transaction
     */
    public static function handleTicketRefund($ticketId, $refundAmount, $reason, $userId = null)
    {
        return TransactionService::executeFinancial(function () use ($ticketId, $refundAmount, $reason, $userId) {
            $ticket = SupportTicket::with('user')->findOrFail($ticketId);

            // Validate ticket can trigger refund
            if (! $ticket->related_order_id) {
                throw new Exception("Ticket {$ticketId} is not linked to an order");
            }

            $order = Order::findOrFail($ticket->related_order_id);

            // Validate refund amount
            if ($refundAmount > $order->total) {
                throw new Exception('Refund amount cannot exceed order total');
            }

            // Update ticket status
            StatusTransitionService::transition($ticket, 'status', 'resolved', $userId);

            // Create refund transaction
            $refundTransaction = FinancialTransaction::create([
                'transaction_id' => FinancialTransaction::generateTransactionId('refund'),
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'store_id' => $order->store_id,
                'type' => 'refund',
                'status' => 'pending_approval',
                'amount' => $refundAmount,
                'currency' => 'USD',
                'description' => "Refund for order {$order->order_number} - {$reason}",
                'metadata' => [
                    'ticket_id' => $ticket->id,
                    'reason' => $reason,
                ],
                'approval_status' => 'pending',
            ]);

            if (Schema::hasTable('users') && ($order->payment_method ?? null) === 'balance') {
                $lockedUser = User::query()->lockForUpdate()->findOrFail($order->user_id);
                $current = (float) ($lockedUser->balance ?? 0);
                $new = $current + (float) $refundAmount;
                $lockedUser->forceFill(['balance' => $new])->save();

                if (Schema::hasTable('customer_balance_audits')) {
                    CustomerBalanceAudit::create([
                        'customer_id' => $lockedUser->id,
                        'amount' => (float) $refundAmount,
                        'type' => $refundAmount >= $order->total ? 'refund_full' : 'refund_partial',
                        'support_user_id' => null,
                        'created_at' => now(),
                    ]);
                }

                $update = [];
                if (Schema::hasColumn('financial_transactions', 'status')) {
                    $update['status'] = 'completed';
                }
                if (Schema::hasColumn('financial_transactions', 'approval_status')) {
                    $update['approval_status'] = 'approved';
                }
                if (Schema::hasColumn('financial_transactions', 'metadata')) {
                    $meta = is_array($refundTransaction->metadata ?? null) ? $refundTransaction->metadata : [];
                    $meta['refunded_to_balance'] = true;
                    $update['metadata'] = $meta;
                }
                if ($update !== []) {
                    $refundTransaction->update($update);
                    $refundTransaction->refresh();
                }
            }

            // Update order status if full refund
            if ($refundAmount >= $order->total) {
                StatusTransitionService::transition($order, 'status', 'refunded', $userId);
                StatusTransitionService::transition($order, 'payment_status', 'refunded', $userId);
            } else {
                // Partial refund
                StatusTransitionService::transition($order, 'payment_status', 'partial', $userId);
            }

            // Restore inventory if full refund
            if ($refundAmount >= $order->total) {
                foreach ($order->items as $item) {
                    if ($item->product && $item->product->track_inventory) {
                        $item->product->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            // Notify customer
            Notification::create([
                'user_id' => $order->user_id,
                'type' => 'refund_processed',
                'title' => 'Refund Processed',
                'message' => "Your refund request for order {$order->order_number} has been processed. Amount: {$refundAmount}",
                'data' => [
                    'order_id' => $order->id,
                    'refund_amount' => $refundAmount,
                    'transaction_id' => $refundTransaction->id,
                ],
            ]);

            // Audit log
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'ticket_refund_created',
                'model_type' => SupportTicket::class,
                'model_id' => $ticket->id,
                'old_values' => ['status' => $ticket->getOriginal('status')],
                'new_values' => [
                    'status' => 'resolved',
                    'refund_transaction_id' => $refundTransaction->id,
                    'refund_amount' => $refundAmount,
                ],
            ]);

            return [
                'ticket' => $ticket->fresh(),
                'order' => $order->fresh(),
                'refund_transaction' => $refundTransaction,
            ];
        }, 'ticket_refund_flow', true, $userId);
    }

    /**
     * Flow: Driver Assignment â†’ Order Status â†’ Driver Status
     */
    public static function handleDriverAssignment($orderId, $driverId, $assignedBy, $userId = null)
    {
        return TransactionService::execute(function () use ($orderId, $driverId, $assignedBy, $userId) {
            $order = Order::findOrFail($orderId);
            $driver = \App\Models\Driver::findOrFail($driverId);

            // Validate order can be assigned
            if (! in_array($order->status, ['pending', 'confirmed', 'processing'], true)) {
                throw new Exception("Order {$orderId} cannot be assigned in current status: {$order->status}");
            }

            // Validate driver availability
            if ($driver->availability !== 'available') {
                throw new Exception("Driver {$driverId} is not available");
            }

            // Normalize pending orders into confirmed before sending out for delivery
            if ($order->status === 'pending') {
                StatusTransitionService::transition($order, 'status', 'confirmed', $userId);
                $order = $order->fresh();
            }

            // Get the user_id for assigned_by (convert employee ID to user ID if needed)
            $assignedByUserId = null;
            if ($assignedBy) {
                // Check if assignedBy is an employee ID, convert to user_id
                $employee = \App\Models\Employee::find($assignedBy);
                if ($employee && $employee->user_id) {
                    // Validate that the user_id exists in users table
                    if (\App\Models\User::where('id', $employee->user_id)->exists()) {
                        $assignedByUserId = $employee->user_id;
                    }
                } elseif (\App\Models\User::where('id', $assignedBy)->exists()) {
                    // It's already a user ID and it exists
                    $assignedByUserId = $assignedBy;
                }
            }
            
            // If still no valid user, try to get from auth
            if (!$assignedByUserId) {
                $authEmployee = auth('employee')->user();
                if ($authEmployee && $authEmployee->user_id) {
                    // Validate that the user_id exists
                    if (\App\Models\User::where('id', $authEmployee->user_id)->exists()) {
                        $assignedByUserId = $authEmployee->user_id;
                    }
                } elseif (auth()->check()) {
                    $authUserId = auth()->id();
                    if ($authUserId && \App\Models\User::where('id', $authUserId)->exists()) {
                        $assignedByUserId = $authUserId;
                    }
                }
            }

            // Create delivery assignment
            $assignmentData = [
                'order_id' => $orderId,
                'driver_id' => $driverId,
                'status' => 'assigned',
                'assigned_at' => now(),
            ];
            
            // Only add assigned_by if we have a valid user ID
            if ($assignedByUserId) {
                $assignmentData['assigned_by'] = $assignedByUserId;
            }
            
            $assignment = DeliveryAssignment::create($assignmentData);

            // Update order status
            StatusTransitionService::transition($order, 'status', 'out_for_delivery', $userId);
            $order = $order->fresh();

            // FK: assigned_driver_id â†’ users.id
            if ($driver->user_id && Schema::hasColumn('orders', 'assigned_driver_id')) {
                $order->update([
                    'assigned_driver_id' => $driver->user_id,
                    'assigned_at' => now(),
                ]);
                $order = $order->fresh();
            }

            // Payment status rules
            // - cash: stays pending/unpaid until delivered
            // - card/credit: should be paid by the time it's out for delivery
            $method = (string) ($order->payment_method ?? '');
            if ($method !== '' && $method !== 'cash' && ($order->payment_status ?? null) !== 'paid') {
                $order->update(['payment_status' => 'paid']);
                $order = $order->fresh();
            }

            // Update driver availability
            $driver->update(['availability' => 'busy']);

            // Notify driver
            if ($driver->user_id) {
                if (\Illuminate\Support\Facades\Schema::hasTable('notifications') && \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'user_id')) {
                    $payload = [
                        'user_id' => $driver->user_id,
                    ];
                    if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'type')) {
                        $payload['type'] = 'order_assigned';
                    }
                    if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'title')) {
                        $payload['title'] = 'New Delivery Assignment';
                    }
                    if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'message')) {
                        $payload['message'] = "You have been assigned order {$order->order_number}";
                    }
                    if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'icon')) {
                        $payload['icon'] = 'fa-truck';
                    }
                    if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'color')) {
                        $payload['color'] = 'orange';
                    }
                    if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'link')) {
                        $payload['link'] = '/dashboard/supervisor/order-assignment';
                    }
                    if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'data')) {
                        $payload['data'] = json_encode([
                            'order_id' => $order->id,
                            'assignment_id' => $assignment->id,
                        ]);
                    }
                    \Illuminate\Support\Facades\DB::table('notifications')->insert($payload + [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Notify customer
            if (\Illuminate\Support\Facades\Schema::hasTable('notifications') && \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'user_id') && $order->user_id) {
                $payload = [
                    'user_id' => $order->user_id,
                ];
                if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'type')) {
                    $payload['type'] = 'order_out_for_delivery';
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'title')) {
                    $payload['title'] = 'Order Out for Delivery';
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'message')) {
                    $payload['message'] = "Your order {$order->order_number} is out for delivery";
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'icon')) {
                    $payload['icon'] = 'fa-truck';
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'color')) {
                    $payload['color'] = 'orange';
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'link')) {
                    $payload['link'] = '/profile';
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'data')) {
                    $payload['data'] = json_encode(['order_id' => $order->id]);
                }
                \Illuminate\Support\Facades\DB::table('notifications')->insert($payload + [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Audit log - ensure user_id is valid or null
            $auditUserId = null;
            $candidateId = $userId ?? $assignedBy;
            if ($candidateId && \App\Models\User::where('id', $candidateId)->exists()) {
                $auditUserId = $candidateId;
            }
            
            try {
                AuditLog::create([
                    'user_id' => $auditUserId,
                    'action' => 'driver_assigned',
                    'model_type' => Order::class,
                    'model_id' => $order->id,
                    'old_values' => ['status' => $order->getOriginal('status')],
                    'new_values' => [
                        'status' => 'out_for_delivery',
                        'driver_id' => $driverId,
                        'assignment_id' => $assignment->id,
                    ],
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                // Log warning if audit log fails, but don't break the flow
                \Illuminate\Support\Facades\Log::warning('Audit log creation failed during driver assignment', [
                    'error' => $e->getMessage(),
                    'user_id' => $auditUserId,
                    'order_id' => $orderId,
                ]);
            }
            return [
                'order' => $order->fresh(),
                'driver' => $driver->fresh(),
                'assignment' => $assignment,
            ];
        }, 'driver_assignment_flow', true, $userId);
    }

    /**
     * Flow: Admin Override â†’ All Systems
     */
    public static function handleAdminOverride($action, $modelType, $modelId, $overrideData, $userId)
    {
        return TransactionService::execute(function () use ($action, $modelType, $modelId, $overrideData, $userId) {
            $model = $modelType::findOrFail($modelId);

            // Execute override action
            switch ($action) {
                case 'force_refund':
                    return self::handleOrderCompletion($modelId, $userId);

                case 'force_status_change':
                    if (isset($overrideData['status'])) {
                        StatusTransitionService::transition(
                            $model,
                            'status',
                            $overrideData['status'],
                            $userId,
                            true // Admin override
                        );
                    }
                    break;

                case 'unlock_user':
                    $model->update(['status' => 'active']);
                    break;

                default:
                    throw new Exception("Unknown override action: {$action}");
            }

            // Create audit log with override flag
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'admin_override',
                'model_type' => $modelType,
                'model_id' => $modelId,
                'old_values' => $model->getOriginal(),
                'new_values' => $overrideData,
                'metadata' => ['override' => true, 'action' => $action],
            ]);

            return $model->fresh();
        }, 'admin_override_flow', true, $userId);
    }

    /**
     * Flow: System Error â†’ IT Alert â†’ Resolution â†’ Post-Mortem
     */
    public static function handleSystemErrorIncident(int $apiErrorId, $userId = null): array
    {
        return TransactionService::execute(function () use ($apiErrorId, $userId) {
            $error = ApiError::findOrFail($apiErrorId);

            $severity = ($error->status_code >= 500) ? 'critical' : 'high';
            $type = 'error';

            $alert = SystemAlert::create([
                'title' => 'API Error '.$error->status_code,
                'message' => ($error->error_message ?? 'Unknown error').' at '.$error->endpoint,
                'type' => $type,
                'severity' => $severity,
                'status' => 'active',
                'category' => 'api_error',
                'metadata' => [
                    'api_error_id' => $error->id,
                    'endpoint' => $error->endpoint,
                    'method' => $error->method,
                    'status_code' => $error->status_code,
                    'user_id' => $error->user_id,
                ],
                'created_by' => auth('employee')->id(),
            ]);

            $itUsers = User::where(function ($q) {
                $q->where('is_it', true)->orWhere('is_it_super', true);
            })->pluck('id');

            foreach ($itUsers as $itUserId) {
                Notification::create([
                    'user_id' => $itUserId,
                    'type' => 'system_alert',
                    'title' => 'New System Alert',
                    'message' => 'API error detected at '.$error->endpoint,
                    'data' => ['alert_id' => $alert->id, 'api_error_id' => $error->id],
                ]);
            }

            AuditLog::create([
                'user_id' => $userId,
                'action' => 'system_error_alert_created',
                'model_type' => SystemAlert::class,
                'model_id' => $alert->id,
                'old_values' => null,
                'new_values' => [
                    'severity' => $severity,
                    'api_error_id' => $error->id,
                ],
            ]);

            return [
                'api_error' => $error->fresh(),
                'system_alert' => $alert->fresh(),
            ];
        }, 'system_error_incident_flow', true, $userId);
    }

    public static function recordFailedLoginAttempt(string $identifier, ?int $userId, string $ip, ?string $userAgent = null): void
    {
        $now = now();
        $windowMinutes = 10;
        $user = $userId ? User::find($userId) : User::where('email', $identifier)->first();
        $recentIpFailures = SecurityAuditLog::where('event_type', 'login_attempt')
            ->where('status', 'failed')
            ->where('ip_address', $ip)
            ->where('created_at', '>=', $now->copy()->subMinutes($windowMinutes))
            ->count();
        $recentUserFailures = 0;
        if ($user) {
            $recentUserFailures = SecurityAuditLog::where('event_type', 'login_attempt')
                ->where('status', 'failed')
                ->where('user_id', $user->id)
                ->where('user_type', User::class)
                ->where('created_at', '>=', $now->copy()->subMinutes($windowMinutes))
                ->count();
        }
        $risk = 'low';
        if ($recentIpFailures >= 10 || $recentUserFailures >= 5) {
            $risk = 'high';
        } elseif ($recentIpFailures >= 5 || $recentUserFailures >= 3) {
            $risk = 'medium';
        }
        $auditData = [
            'event_type' => 'login_attempt',
            'user_type' => User::class,
            'user_id' => $user?->id ?? 0,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'status' => 'failed',
            'description' => 'Invalid credentials',
            'risk_level' => $risk,
        ];
        if (\Illuminate\Support\Facades\Schema::hasColumn('security_audit_logs', 'metadata')) {
            $auditData['metadata'] = ['identifier' => $identifier];
        }
        SecurityAuditLog::create($auditData);

        $sysData = [
            'level' => 'error',
            'action' => 'login_failed',
            'message' => 'Failed login attempt',
            'user' => $user?->email ?? 'unknown',
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ];
        if (\Illuminate\Support\Facades\Schema::hasColumn('system_logs', 'metadata')) {
            $sysData['metadata'] = ['identifier' => $identifier];
        }
        SystemLog::create($sysData);
        if ($user) {
            $user->increment('login_failures');
        }
        if ($user && $recentUserFailures + 1 >= 5) {
            $lockedUntil = $now->copy()->addMinutes(15);
            $user->update([
                'locked_at' => $now,
                'locked_until' => $lockedUntil,
                'lock_reason' => 'Multiple failed logins',
            ]);
            $adminIds = User::where(function ($q) {
                $q->where('is_admin', true)->orWhere('is_it', true)->orWhere('is_it_super', true);
            })->pluck('id');
            foreach ($adminIds as $adminId) {
                Notification::create([
                    'user_id' => $adminId,
                    'type' => 'security_alert',
                    'title' => 'Account locked',
                    'message' => 'User account locked due to failed logins',
                    'data' => ['user_id' => $user->id],
                ]);
            }
        }
        if ($recentIpFailures + 1 >= 15 && \Illuminate\Support\Facades\Schema::hasTable('ip_blacklists')) {
            $exists = IPBlacklist::where('ip_address', $ip)
                ->where('is_active', true)
                ->exists();
            if (! $exists) {
                IPBlacklist::create([
                    'ip_address' => $ip,
                    'reason' => 'Brute force attack',
                    'blocked_by' => null,
                    'blocked_at' => $now,
                    'expires_at' => null,
                    'is_active' => true,
                ]);
                if (\Illuminate\Support\Facades\Schema::hasColumn('ip_blacklists', 'metadata')) {
                    IPBlacklist::where('ip_address', $ip)->where('is_active', true)->update([
                        'metadata' => ['identifier' => $identifier],
                    ]);
                }
            }
            $adminIds = User::where(function ($q) {
                $q->where('is_admin', true)->orWhere('is_it', true)->orWhere('is_it_super', true);
            })->pluck('id');
            foreach ($adminIds as $adminId) {
                Notification::create([
                    'user_id' => $adminId,
                    'type' => 'security_alert',
                    'title' => 'IP blocked',
                    'message' => 'IP added to blacklist due to failed logins',
                    'data' => ['ip' => $ip],
                ]);
            }
        }
    }

    /**
     * Create inventory alert for low stock
     */
    private static function createInventoryAlert($product)
    {
        if (DB::getSchemaBuilder()->hasTable('inventory_alerts')) {
            $severity = ($product->stock_quantity === 0 || $product->stock_quantity <= max(1, (int) floor($product->low_stock_threshold / 2)))
                ? 'critical'
                : 'warning';

            \App\Models\InventoryAlert::create([
                'product_id' => $product->id,
                'alert_type' => 'low_stock',
                'current_quantity' => $product->stock_quantity,
                'threshold_quantity' => $product->low_stock_threshold,
                'severity' => $severity,
                'is_resolved' => false,
            ]);

            // Create a system alert for admins
            SystemAlert::create([
                'title' => 'Low Stock: '.$product->name,
                'message' => 'Current stock '.$product->stock_quantity.' below threshold '.$product->low_stock_threshold,
                'type' => 'inventory',
                'severity' => $severity,
                'status' => 'active',
                'category' => 'inventory',
                'metadata' => ['product_id' => $product->id],
                'created_by' => auth('employee')->id(),
            ]);

            // Notify admin users
            $adminIds = User::where('is_admin', true)->pluck('id');
            foreach ($adminIds as $adminId) {
                Notification::create([
                    'user_id' => $adminId,
                    'type' => 'inventory_alert',
                    'title' => 'Low Stock Alert',
                    'message' => $product->name.' is low on stock',
                    'data' => ['product_id' => $product->id],
                ]);
            }
        }
    }
}
