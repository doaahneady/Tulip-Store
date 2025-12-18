<?php

namespace Tests\Property;

use App\Exceptions\Dashboard\ImmutableRecordException;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

/**
 * Property-Based Tests for Audit Log
 * 
 * These tests verify the correctness properties of the AuditLog model
 * by running multiple iterations with randomly generated test data.
 */
class AuditLogPropertyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create audit_logs table if it doesn't exist
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('action');
                $table->string('model_type')->nullable();
                $table->unsignedBigInteger('model_id')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamps();
                
                $table->index(['model_type', 'model_id']);
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    protected function tearDown(): void
    {
        // Clean up audit logs after each test using truncate to bypass model events
        // (since delete is blocked by immutability)
        if (Schema::hasTable('audit_logs')) {
            \DB::table('audit_logs')->truncate();
        }
        
        parent::tearDown();
    }

    /**
     * Available action types for testing
     */
    protected array $actionTypes = [
        'create',
        'update',
        'delete',
        'export',
        'approve',
    ];

    /**
     * Available resource types for testing
     */
    protected array $resourceTypes = [
        'App\\Models\\Order',
        'App\\Models\\User',
        'App\\Models\\Product',
        'App\\Models\\Store',
        'App\\Models\\FinancialTransaction',
    ];

    /**
     * Generate random audit log data
     */
    protected function generateRandomAuditLogData(): array
    {
        return [
            'user_id' => rand(1, 1000),
            'action' => $this->actionTypes[array_rand($this->actionTypes)],
            'model_type' => $this->resourceTypes[array_rand($this->resourceTypes)],
            'model_id' => rand(1, 10000),
            'old_values' => $this->generateRandomMetadata(),
            'new_values' => $this->generateRandomMetadata(),
            'ip_address' => $this->generateRandomIpAddress(),
            'user_agent' => $this->generateRandomUserAgent(),
        ];
    }


    /**
     * Generate random metadata array
     */
    protected function generateRandomMetadata(): ?array
    {
        // 20% chance of null metadata
        if (rand(1, 5) === 1) {
            return null;
        }

        $metadata = [];
        $fields = ['name', 'email', 'status', 'amount', 'quantity', 'price'];
        $numFields = rand(1, 4);
        
        shuffle($fields);
        $selectedFields = array_slice($fields, 0, $numFields);
        
        foreach ($selectedFields as $field) {
            $metadata[$field] = match ($field) {
                'name' => 'Test Name ' . rand(1, 100),
                'email' => 'test' . rand(1, 100) . '@example.com',
                'status' => ['active', 'inactive', 'pending'][rand(0, 2)],
                'amount' => round(rand(100, 10000) / 100, 2),
                'quantity' => rand(1, 100),
                'price' => round(rand(100, 100000) / 100, 2),
                default => 'value_' . rand(1, 100),
            };
        }

        return $metadata;
    }

    /**
     * Generate random IP address
     */
    protected function generateRandomIpAddress(): string
    {
        return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 254);
    }

    /**
     * Generate random user agent
     */
    protected function generateRandomUserAgent(): string
    {
        $browsers = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15',
        ];
        return $browsers[array_rand($browsers)];
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 14: Audit Log Immutability**
     * **Validates: Requirements 6.2**
     * 
     * *For any* existing audit log entry, update and delete operations 
     * SHALL throw an ImmutableRecordException.
     * 
     * @test
     */
    public function property_audit_log_immutability_prevents_updates_and_deletes(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            // Create a random audit log entry
            $data = $this->generateRandomAuditLogData();
            $auditLog = AuditLog::create($data);
            
            // Verify the entry was created
            $this->assertDatabaseHas('audit_logs', ['id' => $auditLog->id]);
            
            // Test 1: Attempting to update should throw ImmutableRecordException
            $updateThrown = false;
            try {
                $auditLog->update(['action' => 'modified_action']);
            } catch (ImmutableRecordException $e) {
                $updateThrown = true;
                $this->assertEquals('Audit logs cannot be modified', $e->getMessage());
            }
            
            $this->assertTrue(
                $updateThrown,
                "Iteration $i: Update operation should throw ImmutableRecordException"
            );
            
            // Verify the record was NOT modified
            $auditLog->refresh();
            $this->assertEquals(
                $data['action'],
                $auditLog->action,
                "Iteration $i: Audit log action should remain unchanged after failed update"
            );
            
            // Test 2: Attempting to delete should throw ImmutableRecordException
            $deleteThrown = false;
            try {
                $auditLog->delete();
            } catch (ImmutableRecordException $e) {
                $deleteThrown = true;
                $this->assertEquals('Audit logs cannot be deleted', $e->getMessage());
            }
            
            $this->assertTrue(
                $deleteThrown,
                "Iteration $i: Delete operation should throw ImmutableRecordException"
            );
            
            // Verify the record still exists
            $this->assertDatabaseHas('audit_logs', ['id' => $auditLog->id]);
        }
    }


    /**
     * **Feature: dashboard-system-rebuild, Property 15: Audit Log Serialization Round-Trip**
     * **Validates: Requirements 6.5, 6.6**
     * 
     * *For any* valid AuditLog entry, serializing to JSON and then deserializing 
     * SHALL produce an entry with equivalent field values.
     * 
     * @test
     */
    public function property_audit_log_serialization_round_trip(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            // Create a random audit log entry
            $data = $this->generateRandomAuditLogData();
            $auditLog = AuditLog::create($data);
            
            // Serialize to JSON
            $json = $auditLog->serializeToJson();
            
            // Verify JSON is valid
            $this->assertJson($json, "Iteration $i: Serialized output should be valid JSON");
            
            // Deserialize back to attributes
            $deserializedAttributes = AuditLog::deserializeFromJson($json);
            
            // Verify round-trip equivalence for all fields
            $this->assertEquals(
                $auditLog->id,
                $deserializedAttributes['id'],
                "Iteration $i: ID should match after round-trip"
            );
            
            $this->assertEquals(
                $auditLog->user_id,
                $deserializedAttributes['user_id'],
                "Iteration $i: user_id should match after round-trip"
            );
            
            $this->assertEquals(
                $auditLog->action,
                $deserializedAttributes['action'],
                "Iteration $i: action should match after round-trip"
            );
            
            $this->assertEquals(
                $auditLog->model_type,
                $deserializedAttributes['model_type'],
                "Iteration $i: model_type (resource_type) should match after round-trip"
            );
            
            $this->assertEquals(
                $auditLog->model_id,
                $deserializedAttributes['model_id'],
                "Iteration $i: model_id (resource_id) should match after round-trip"
            );
            
            $this->assertEquals(
                $auditLog->old_values,
                $deserializedAttributes['old_values'],
                "Iteration $i: old_values should match after round-trip"
            );
            
            $this->assertEquals(
                $auditLog->new_values,
                $deserializedAttributes['new_values'],
                "Iteration $i: new_values should match after round-trip"
            );
            
            $this->assertEquals(
                $auditLog->ip_address,
                $deserializedAttributes['ip_address'],
                "Iteration $i: ip_address should match after round-trip"
            );
            
            $this->assertEquals(
                $auditLog->user_agent,
                $deserializedAttributes['user_agent'],
                "Iteration $i: user_agent should match after round-trip"
            );
            
            // Test createFromSerializedJson method creates equivalent model
            $reconstructedLog = AuditLog::createFromSerializedJson($json);
            
            $this->assertEquals(
                $auditLog->id,
                $reconstructedLog->id,
                "Iteration $i: fromJson should produce model with same ID"
            );
            
            $this->assertEquals(
                $auditLog->action,
                $reconstructedLog->action,
                "Iteration $i: fromJson should produce model with same action"
            );
            
            $this->assertEquals(
                $auditLog->model_type,
                $reconstructedLog->model_type,
                "Iteration $i: fromJson should produce model with same model_type"
            );
        }
    }

    /**
     * Additional test: Verify serialization produces consistent ordering
     * 
     * @test
     */
    public function property_serialization_produces_consistent_output(): void
    {
        // Run 50 iterations
        for ($i = 0; $i < 50; $i++) {
            // Create a random audit log entry
            $data = $this->generateRandomAuditLogData();
            $auditLog = AuditLog::create($data);
            
            // Serialize multiple times
            $json1 = $auditLog->serializeToJson();
            $json2 = $auditLog->serializeToJson();
            $json3 = $auditLog->serializeToJson();
            
            // All serializations should produce identical output
            $this->assertEquals(
                $json1,
                $json2,
                "Iteration $i: Multiple serializations should produce identical output"
            );
            
            $this->assertEquals(
                $json2,
                $json3,
                "Iteration $i: Multiple serializations should produce identical output"
            );
        }
    }
}
