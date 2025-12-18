<?php

namespace Tests\Property;

use App\Exceptions\Dashboard\ImmutableRecordException;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

/**
 * Property-Based Tests for Financial Transaction
 * 
 * These tests verify the correctness properties of the FinancialTransaction model
 * by running multiple iterations with randomly generated test data.
 */
class FinancialTransactionPropertyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create financial_transactions table if it doesn't exist
        if (!Schema::hasTable('financial_transactions')) {
            Schema::create('financial_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->nullable();
                $table->foreignId('order_id')->nullable();
                $table->foreignId('user_id')->nullable();
                $table->string('type');
                $table->decimal('amount', 12, 2);
                $table->decimal('balance_before', 12, 2)->default(0);
                $table->decimal('balance_after', 12, 2)->default(0);
                $table->string('reference')->nullable();
                $table->text('description')->nullable();
                $table->string('status')->default('completed');
                $table->foreignId('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->boolean('is_immutable')->default(false);
                $table->timestamps();
            });
        }

        // Create users table if it doesn't exist (for approver relationship)
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->timestamps();
            });
        }
    }

    protected function tearDown(): void
    {
        // Clean up financial transactions after each test
        if (Schema::hasTable('financial_transactions')) {
            \DB::table('financial_transactions')->truncate();
        }
        
        if (Schema::hasTable('users')) {
            \DB::table('users')->truncate();
        }
        
        parent::tearDown();
    }

    /**
     * Available transaction types for testing
     */
    protected array $transactionTypes = [
        'sale',
        'commission',
        'payout',
        'refund',
        'fee',
        'adjustment',
    ];

    /**
     * Available status values for testing
     */
    protected array $statusValues = [
        'pending',
        'completed',
        'failed',
        'cancelled',
    ];

    /**
     * Generate random financial transaction data
     */
    protected function generateRandomTransactionData(): array
    {
        $balanceBefore = round(rand(0, 100000) / 100, 2);
        $amount = round(rand(100, 10000) / 100, 2);
        
        return [
            'store_id' => null,
            'order_id' => null,
            'user_id' => null,
            'type' => $this->transactionTypes[array_rand($this->transactionTypes)],
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore + $amount,
            'reference' => 'REF-' . rand(10000, 99999),
            'description' => 'Test transaction ' . rand(1, 1000),
            'status' => $this->statusValues[array_rand($this->statusValues)],
            'is_immutable' => false,
        ];
    }

    /**
     * Counter for unique approver emails
     */
    protected static int $approverCounter = 0;

    /**
     * Create a mock approver user
     */
    protected function createApprover(): User
    {
        self::$approverCounter++;
        return User::create([
            'name' => 'Approver ' . self::$approverCounter,
            'email' => 'approver' . self::$approverCounter . '_' . time() . '@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 16: Financial Record Immutability After Approval**
     * **Validates: Requirements 6.4**
     * 
     * *For any* financial transaction marked as approved, update operations 
     * SHALL throw an ImmutableRecordException.
     * 
     * @test
     */
    public function property_financial_record_immutability_after_approval(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            // Create a random financial transaction
            $data = $this->generateRandomTransactionData();
            $transaction = FinancialTransaction::create($data);
            
            // Verify the transaction was created and is NOT immutable
            $this->assertDatabaseHas('financial_transactions', ['id' => $transaction->id]);
            $this->assertFalse($transaction->is_immutable, "Iteration $i: New transaction should not be immutable");
            
            // Create an approver
            $approver = $this->createApprover();
            
            // Approve the transaction (this should make it immutable)
            $transaction->approve($approver);
            
            // Refresh to get updated values
            $transaction->refresh();
            
            // Verify the transaction is now immutable
            $this->assertTrue(
                $transaction->is_immutable,
                "Iteration $i: Transaction should be immutable after approval"
            );
            $this->assertEquals(
                'approved',
                $transaction->status,
                "Iteration $i: Transaction status should be 'approved'"
            );
            $this->assertEquals(
                $approver->id,
                $transaction->approved_by,
                "Iteration $i: Transaction should have correct approver"
            );
            $this->assertNotNull(
                $transaction->approved_at,
                "Iteration $i: Transaction should have approval timestamp"
            );
            
            // Test: Attempting to update an immutable transaction should throw ImmutableRecordException
            $updateThrown = false;
            try {
                $transaction->update(['amount' => 999.99]);
            } catch (ImmutableRecordException $e) {
                $updateThrown = true;
                $this->assertEquals('Approved financial records cannot be modified', $e->getMessage());
            }
            
            $this->assertTrue(
                $updateThrown,
                "Iteration $i: Update operation on approved transaction should throw ImmutableRecordException"
            );
            
            // Verify the record was NOT modified
            $transaction->refresh();
            $this->assertEquals(
                $data['amount'],
                $transaction->amount,
                "Iteration $i: Transaction amount should remain unchanged after failed update"
            );
            
            // Test: Attempting to update any field should throw exception
            $fieldsToTest = ['description', 'reference', 'status', 'type'];
            foreach ($fieldsToTest as $field) {
                $fieldUpdateThrown = false;
                try {
                    $transaction->update([$field => 'modified_value']);
                } catch (ImmutableRecordException $e) {
                    $fieldUpdateThrown = true;
                }
                
                $this->assertTrue(
                    $fieldUpdateThrown,
                    "Iteration $i: Update of '$field' on approved transaction should throw ImmutableRecordException"
                );
            }
        }
    }

    /**
     * Additional test: Verify non-approved transactions can be updated
     * 
     * @test
     */
    public function property_non_approved_transactions_can_be_updated(): void
    {
        // Run 50 iterations
        for ($i = 0; $i < 50; $i++) {
            // Create a random financial transaction (not approved)
            $data = $this->generateRandomTransactionData();
            $transaction = FinancialTransaction::create($data);
            
            // Verify it's not immutable
            $this->assertFalse($transaction->is_immutable);
            
            // Update should succeed
            $newAmount = round(rand(100, 10000) / 100, 2);
            $newDescription = 'Updated description ' . rand(1, 1000);
            
            $updateSucceeded = true;
            try {
                $transaction->update([
                    'amount' => $newAmount,
                    'description' => $newDescription,
                ]);
            } catch (ImmutableRecordException $e) {
                $updateSucceeded = false;
            }
            
            $this->assertTrue(
                $updateSucceeded,
                "Iteration $i: Update on non-approved transaction should succeed"
            );
            
            // Verify the update was applied
            $transaction->refresh();
            $this->assertEquals(
                $newAmount,
                $transaction->amount,
                "Iteration $i: Transaction amount should be updated"
            );
            $this->assertEquals(
                $newDescription,
                $transaction->description,
                "Iteration $i: Transaction description should be updated"
            );
        }
    }
}
