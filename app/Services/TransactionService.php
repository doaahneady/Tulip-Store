<?php

namespace App\Services;

use App\Models\AuditLog;
use Closure;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Transaction Service
 *
 * Wraps database operations in transactions with proper error handling
 * Ensures all-or-nothing operations and proper rollback
 */
class TransactionService
{
    /**
     * Execute a closure within a database transaction
     *
     * @param  Closure  $callback  Operation to execute
     * @param  string  $operationName  Name of operation (for logging)
     * @param  bool  $requiresAudit  Whether to create audit log
     * @param  int|null  $userId  User performing the action
     * @return mixed Result of callback
     *
     * @throws Exception If transaction fails
     */
    public static function execute(Closure $callback, $operationName = 'database_operation', $requiresAudit = true, $userId = null)
    {
        DB::beginTransaction();

        try {
            $result = $callback();

            // Create audit log if required
            if ($requiresAudit) {
                self::logOperation($operationName, 'success', $userId);
            }

            DB::commit();

            return $result;

        } catch (Exception $e) {
            DB::rollBack();

            // Log failure
            self::logOperation($operationName, 'failed', $userId, $e->getMessage());

            // Re-throw exception
            throw $e;
        }
    }

    /**
     * Execute a financial operation with enhanced transaction isolation
     *
     * @param  Closure  $callback  Financial operation to execute
     * @param  string  $operationName  Name of operation
     * @param  int|null  $userId  User performing the action
     * @return mixed Result of callback
     *
     * @throws Exception If transaction fails
     */
    public static function executeFinancial(Closure $callback, $operationName = 'financial_operation', $userId = null)
    {
        // Set transaction isolation level for financial operations
        DB::statement('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ');

        return self::execute($callback, $operationName, true, $userId);
    }

    /**
     * Execute multiple operations in sequence with rollback on any failure
     *
     * @param  array  $operations  Array of closures to execute
     * @param  string  $operationName  Name of overall operation
     * @param  int|null  $userId  User performing the action
     * @return array Results of all operations
     *
     * @throws Exception If any operation fails
     */
    public static function executeBatch(array $operations, $operationName = 'batch_operation', $userId = null)
    {
        return self::execute(function () use ($operations) {
            $results = [];
            foreach ($operations as $index => $operation) {
                if (! ($operation instanceof Closure)) {
                    throw new Exception("Operation at index {$index} is not a valid closure");
                }
                $results[] = $operation();
            }

            return $results;
        }, $operationName, true, $userId);
    }

    /**
     * Execute operation with retry logic
     *
     * @param  Closure  $callback  Operation to execute
     * @param  int  $maxRetries  Maximum number of retries
     * @param  int  $retryDelay  Delay between retries in seconds
     * @param  string  $operationName  Name of operation
     * @return mixed Result of callback
     *
     * @throws Exception If all retries fail
     */
    public static function executeWithRetry(Closure $callback, $maxRetries = 3, $retryDelay = 1, $operationName = 'retry_operation')
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < $maxRetries) {
            try {
                return self::execute($callback, $operationName.'_attempt_'.($attempt + 1));
            } catch (Exception $e) {
                $lastException = $e;
                $attempt++;

                // Check if error is retryable
                if (! self::isRetryableError($e)) {
                    throw $e;
                }

                if ($attempt < $maxRetries) {
                    Log::warning('Operation failed, retrying...', [
                        'operation' => $operationName,
                        'attempt' => $attempt,
                        'max_retries' => $maxRetries,
                        'error' => $e->getMessage(),
                    ]);

                    sleep($retryDelay);
                }
            }
        }

        throw $lastException;
    }

    /**
     * Check if an error is retryable
     */
    private static function isRetryableError(Exception $e)
    {
        $retryableMessages = [
            'deadlock',
            'lock wait timeout',
            'connection',
            'timeout',
            'temporary',
        ];

        $message = strtolower($e->getMessage());

        foreach ($retryableMessages as $retryable) {
            if (strpos($message, $retryable) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log operation for audit
     */
    private static function logOperation($operationName, $status, $userId = null, $errorMessage = null)
    {
        try {
            AuditLog::create([
                'user_id' => $userId ?? auth()->id(),
                'action' => $operationName,
                'model_type' => 'Transaction',
                'model_id' => null,
                'old_values' => null,
                'new_values' => [
                    'status' => $status,
                    'operation' => $operationName,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'session_id' => session()->getId(),
            ]);

            if ($status === 'failed') {
                Log::error("Transaction failed: {$operationName}", [
                    'user_id' => $userId,
                    'error' => $errorMessage,
                ]);
            }
        } catch (Exception $e) {
            // Don't fail the operation if audit logging fails
            Log::error('Failed to create audit log', [
                'error' => $e->getMessage(),
                'operation' => $operationName,
            ]);
        }
    }

    /**
     * Execute operation with timeout protection
     *
     * @param  Closure  $callback  Operation to execute
     * @param  int  $timeoutSeconds  Maximum execution time in seconds
     * @param  string  $operationName  Name of operation
     * @return mixed Result of callback
     *
     * @throws Exception If timeout exceeded
     */
    public static function executeWithTimeout(Closure $callback, $timeoutSeconds = 30, $operationName = 'timeout_operation')
    {
        $startTime = microtime(true);

        return self::execute(function () use ($callback, $timeoutSeconds, $startTime, $operationName) {
            $result = $callback();

            $elapsed = microtime(true) - $startTime;
            if ($elapsed > $timeoutSeconds) {
                throw new Exception("Operation {$operationName} exceeded timeout of {$timeoutSeconds} seconds");
            }

            return $result;
        }, $operationName);
    }
}
