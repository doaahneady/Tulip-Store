<?php

namespace Tests\Property;

use App\Models\AuditLog;
use App\Models\User;
use App\Repositories\Eloquent\AuditLogRepository;
use App\Services\Dashboard\AuditService;
use App\Services\Dashboard\ExportService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Property-Based Tests for ExportService
 *
 * These tests verify the correctness properties of the ExportService
 * by running multiple iterations with randomly generated test data.
 */
class ExportServicePropertyTest extends TestCase
{
    protected ExportService $exportService;

    protected AuditService $auditService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create audit_logs table if it doesn't exist
        if (! Schema::hasTable('audit_logs')) {
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

        // Create users table if it doesn't exist
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('phone')->nullable();
                $table->boolean('is_admin')->default(false);
                $table->boolean('is_trader')->default(false);
                $table->boolean('is_finance')->default(false);
                $table->timestamps();
            });
        }

        // Set up services
        $auditLogRepository = new AuditLogRepository(new AuditLog);
        $this->auditService = new AuditService($auditLogRepository);
        $this->exportService = new ExportService($this->auditService);
    }

    protected function tearDown(): void
    {
        // Clean up data after each test
        if (Schema::hasTable('audit_logs')) {
            \DB::table('audit_logs')->truncate();
        }
        if (Schema::hasTable('users')) {
            \DB::table('users')->truncate();
        }

        parent::tearDown();
    }

    /**
     * Generate random data collection for testing
     */
    protected function generateRandomData(int $count): Collection
    {
        $data = [];

        for ($i = 0; $i < $count; $i++) {
            $data[] = [
                'id' => $i + 1,
                'name' => 'Test Item '.($i + 1).'_'.uniqid(),
                'email' => 'test'.($i + 1).'_'.uniqid().'@example.com',
                'status' => ['active', 'inactive', 'pending'][rand(0, 2)],
                'amount' => round(rand(100, 100000) / 100, 2),
                'created_at' => now()->subDays(rand(0, 365))->format('Y-m-d H:i:s'),
            ];
        }

        return collect($data);
    }

    /**
     * Generate random column definitions
     */
    protected function generateRandomColumns(): array
    {
        $allColumns = [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'status' => 'Status',
            'amount' => 'Amount',
            'created_at' => 'Created At',
        ];

        // Randomly select 2-6 columns
        $numColumns = rand(2, 6);
        $keys = array_keys($allColumns);
        shuffle($keys);
        $selectedKeys = array_slice($keys, 0, $numColumns);

        $columns = [];
        foreach ($selectedKeys as $key) {
            $columns[$key] = $allColumns[$key];
        }

        return $columns;
    }

    /**
     * Parse CSV content from streamed response
     */
    protected function parseCsvFromResponse($response): array
    {
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        // Remove BOM if present
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

        $lines = array_filter(explode("\n", trim($content)));
        $rows = [];

        foreach ($lines as $line) {
            $rows[] = str_getcsv($line);
        }

        return $rows;
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 11: CSV Export Completeness**
     * **Validates: Requirements 5.1**
     *
     * *For any* filtered dataset, the exported CSV SHALL contain exactly
     * the same number of records as the filtered view and include all specified columns.
     *
     * @test
     */
    public function property_csv_export_completeness(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            // Clean up audit logs from previous iteration
            \DB::table('audit_logs')->truncate();

            // Generate random dataset size (0 to 100 records)
            $datasetSize = rand(0, 100);

            // Generate random data
            $data = $this->generateRandomData($datasetSize);

            // Generate random columns
            $columns = $this->generateRandomColumns();

            // Export to CSV
            $filename = 'test_export_'.$i.'.csv';
            $response = $this->exportService->exportToCSV($data, $columns, $filename);

            // Parse the CSV response
            $csvRows = $this->parseCsvFromResponse($response);

            // Property 1: CSV should have header row + data rows
            $expectedRowCount = $datasetSize + 1; // +1 for header
            if ($datasetSize === 0) {
                $expectedRowCount = 1; // Just header for empty dataset
            }

            $this->assertCount(
                $expectedRowCount,
                $csvRows,
                "Iteration $i: CSV should have $expectedRowCount rows (1 header + $datasetSize data rows)"
            );

            // Property 2: Header row should contain all column labels
            if (count($csvRows) > 0) {
                $headerRow = $csvRows[0];
                $expectedHeaders = array_values($columns);

                $this->assertEquals(
                    $expectedHeaders,
                    $headerRow,
                    "Iteration $i: CSV header should contain all specified column labels"
                );
            }

            // Property 3: Each data row should have the same number of columns as header
            $columnCount = count($columns);
            for ($j = 1; $j < count($csvRows); $j++) {
                $this->assertCount(
                    $columnCount,
                    $csvRows[$j],
                    "Iteration $i, Row $j: Data row should have $columnCount columns"
                );
            }

            // Property 4: Data values should match original data
            $columnKeys = array_keys($columns);
            for ($j = 0; $j < $datasetSize; $j++) {
                $originalRow = $data[$j];
                $csvDataRow = $csvRows[$j + 1]; // +1 to skip header

                foreach ($columnKeys as $colIndex => $key) {
                    $expectedValue = (string) ($originalRow[$key] ?? '');
                    $actualValue = $csvDataRow[$colIndex] ?? '';

                    $this->assertEquals(
                        $expectedValue,
                        $actualValue,
                        "Iteration $i, Row $j, Column '$key': Value should match original data"
                    );
                }
            }
        }
    }

    /**
     * Create a test user with specific role
     */
    protected function createUserWithRole(string $role): User
    {
        $userData = [
            'name' => 'Test User '.uniqid(),
            'email' => 'test'.uniqid().'@example.com',
            'password' => bcrypt('password'),
            'is_admin' => $role === 'admin',
            'is_trader' => $role === 'trader',
            'is_finance' => $role === 'finance',
        ];

        return User::create($userData);
    }

    /**
     * Filter data based on user role (simulates role-based filtering)
     */
    protected function filterDataByRole(Collection $data, User $user): Collection
    {
        // Simulate role-based filtering
        // Admin sees all data
        if ($user->is_admin) {
            return $data;
        }

        // Trader sees only their own data (simulated by filtering to even IDs)
        if ($user->is_trader) {
            return $data->filter(fn ($item) => $item['id'] % 2 === 0);
        }

        // Finance sees only completed/active items
        if ($user->is_finance) {
            return $data->filter(fn ($item) => $item['status'] === 'active');
        }

        // Default: no data
        return collect([]);
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 12: Export Role Filtering Consistency**
     * **Validates: Requirements 5.4**
     *
     * *For any* user role and *for any* export operation, the exported data
     * SHALL match exactly the data visible in the dashboard view for that role.
     *
     * @test
     */
    public function property_export_role_filtering_consistency(): void
    {
        $roles = ['admin', 'trader', 'finance'];

        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            // Clean up from previous iteration
            \DB::table('audit_logs')->truncate();
            \DB::table('users')->truncate();

            // Generate random dataset size (10 to 50 records)
            $datasetSize = rand(10, 50);

            // Generate random data
            $fullData = $this->generateRandomData($datasetSize);

            // Pick a random role
            $role = $roles[array_rand($roles)];

            // Create user with that role
            $user = $this->createUserWithRole($role);

            // Filter data based on role (this simulates what the dashboard would show)
            $filteredData = $this->filterDataByRole($fullData, $user);

            // Define columns for export
            $columns = [
                'id' => 'ID',
                'name' => 'Name',
                'status' => 'Status',
            ];

            // Export the filtered data (as the dashboard would)
            $filename = 'role_export_'.$i.'.csv';
            $response = $this->exportService->exportWithRoleFilter(
                $filteredData,
                $columns,
                'csv',
                $filename,
                $user
            );

            // Parse the CSV response
            $csvRows = $this->parseCsvFromResponse($response);

            // Property: Exported row count should match filtered data count
            $expectedDataRows = $filteredData->count();
            $actualDataRows = count($csvRows) - 1; // Subtract header row

            $this->assertEquals(
                $expectedDataRows,
                $actualDataRows,
                "Iteration $i (role: $role): Exported rows ($actualDataRows) should match filtered data count ($expectedDataRows)"
            );

            // Property: Each exported row should exist in the filtered data
            $filteredIds = $filteredData->pluck('id')->toArray();

            for ($j = 1; $j < count($csvRows); $j++) {
                $exportedId = (int) $csvRows[$j][0]; // ID is first column

                $this->assertContains(
                    $exportedId,
                    $filteredIds,
                    "Iteration $i (role: $role): Exported ID $exportedId should be in filtered data"
                );
            }

            // Property: No data from outside the filtered set should be exported
            $fullDataIds = $fullData->pluck('id')->toArray();
            $excludedIds = array_diff($fullDataIds, $filteredIds);

            for ($j = 1; $j < count($csvRows); $j++) {
                $exportedId = (int) $csvRows[$j][0];

                $this->assertNotContains(
                    $exportedId,
                    $excludedIds,
                    "Iteration $i (role: $role): Exported ID $exportedId should NOT be in excluded data"
                );
            }
        }
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 13: Audit Log Creation on Sensitive Actions**
     * **Validates: Requirements 5.5, 6.1**
     *
     * *For any* sensitive action (create, update, delete, export, approve),
     * an audit log entry SHALL be created containing user_id, action,
     * resource_type, resource_id, timestamp, and ip_address.
     *
     * @test
     */
    public function property_audit_log_creation_on_export(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            // Clean up from previous iteration
            \DB::table('audit_logs')->truncate();
            \DB::table('users')->truncate();

            // Generate random dataset size (1 to 100 records)
            $datasetSize = rand(1, 100);

            // Generate random data
            $data = $this->generateRandomData($datasetSize);

            // Generate random columns
            $columns = $this->generateRandomColumns();

            // Count audit logs before export
            $auditLogCountBefore = AuditLog::count();

            // Perform CSV export
            $filename = 'audit_test_'.$i.'.csv';
            $this->exportService->exportToCSV($data, $columns, $filename);

            // Count audit logs after export
            $auditLogCountAfter = AuditLog::count();

            // Property 1: An audit log entry should be created for the export
            $this->assertEquals(
                $auditLogCountBefore + 1,
                $auditLogCountAfter,
                "Iteration $i: Export should create exactly one audit log entry"
            );

            // Get the created audit log
            $auditLog = AuditLog::latest()->first();

            // Property 2: Audit log should have action = 'export'
            $this->assertEquals(
                'export',
                $auditLog->action,
                "Iteration $i: Audit log action should be 'export'"
            );

            // Property 3: Audit log should have resource_type set
            $this->assertNotNull(
                $auditLog->model_type,
                "Iteration $i: Audit log should have resource_type (model_type) set"
            );

            // Property 4: Audit log should have timestamp (created_at)
            $this->assertNotNull(
                $auditLog->created_at,
                "Iteration $i: Audit log should have timestamp"
            );

            // Property 5: Audit log metadata should contain record count
            $this->assertIsArray(
                $auditLog->new_values,
                "Iteration $i: Audit log should have metadata (new_values)"
            );

            $this->assertArrayHasKey(
                'record_count',
                $auditLog->new_values,
                "Iteration $i: Audit log metadata should contain record_count"
            );

            // Property 6: Record count in audit log should match exported data count
            $this->assertEquals(
                $datasetSize,
                $auditLog->new_values['record_count'],
                "Iteration $i: Audit log record_count should match exported data count"
            );
        }
    }

    /**
     * Additional test: Verify PDF export also creates audit log
     *
     * @test
     */
    public function property_audit_log_creation_on_pdf_export(): void
    {
        // Run 50 iterations (fewer due to PDF generation overhead)
        for ($i = 0; $i < 50; $i++) {
            // Clean up from previous iteration
            \DB::table('audit_logs')->truncate();

            // Generate random dataset size (1 to 50 records)
            $datasetSize = rand(1, 50);

            // Generate random data
            $data = $this->generateRandomData($datasetSize);

            // Define columns
            $columns = [
                'id' => 'ID',
                'name' => 'Name',
                'status' => 'Status',
            ];

            // Count audit logs before export
            $auditLogCountBefore = AuditLog::count();

            // Perform PDF export
            $this->exportService->exportToPDF(
                $data,
                $columns,
                'dashboard.exports.pdf-template',
                ['title' => 'Test Export '.$i]
            );

            // Count audit logs after export
            $auditLogCountAfter = AuditLog::count();

            // Property: An audit log entry should be created for the PDF export
            $this->assertEquals(
                $auditLogCountBefore + 1,
                $auditLogCountAfter,
                "Iteration $i: PDF export should create exactly one audit log entry"
            );

            // Get the created audit log
            $auditLog = AuditLog::latest()->first();

            // Property: Audit log should have action = 'export'
            $this->assertEquals(
                'export',
                $auditLog->action,
                "Iteration $i: Audit log action should be 'export'"
            );

            // Property: Record count should match
            $this->assertEquals(
                $datasetSize,
                $auditLog->new_values['record_count'],
                "Iteration $i: Audit log record_count should match exported data count"
            );
        }
    }

    /**
     * Additional test: Verify large export threshold detection
     *
     * @test
     */
    public function property_large_export_threshold_detection(): void
    {
        // Run 50 iterations
        for ($i = 0; $i < 50; $i++) {
            // Generate random dataset size
            $datasetSize = rand(0, 2000);

            // Generate data
            $data = $this->generateRandomData($datasetSize);

            // Check if should queue
            $shouldQueue = $this->exportService->shouldQueueExport($data);
            $threshold = $this->exportService->getExportThreshold();

            // Property: shouldQueueExport should return true iff count > threshold
            if ($datasetSize > $threshold) {
                $this->assertTrue(
                    $shouldQueue,
                    "Iteration $i: Dataset of $datasetSize rows should be queued (threshold: $threshold)"
                );
            } else {
                $this->assertFalse(
                    $shouldQueue,
                    "Iteration $i: Dataset of $datasetSize rows should NOT be queued (threshold: $threshold)"
                );
            }
        }
    }
}
