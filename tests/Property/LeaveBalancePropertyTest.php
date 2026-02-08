<?php

namespace Tests\Property;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\Dashboard\AuditService;
use App\Services\Dashboard\HRDashboardService;
use App\Services\Dashboard\MetricsService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Property-Based Tests for Leave Balance Adjustment
 *
 * These tests verify the correctness properties of leave balance calculations
 * by running multiple iterations with randomly generated test data.
 */
class LeaveBalancePropertyTest extends TestCase
{
    protected HRDashboardService $hrService;

    protected static int $employeeCounter = 0;

    protected static int $userCounter = 0;

    protected function setUp(): void
    {
        parent::setUp();

        // Create employees table if it doesn't exist
        if (! Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable();
                $table->string('employee_code')->unique();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('phone');
                $table->string('department');
                $table->string('position');
                $table->date('hire_date');
                $table->decimal('salary', 10, 2);
                $table->string('status')->default('active');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Create leave_requests table if it doesn't exist
        if (! Schema::hasTable('leave_requests')) {
            Schema::create('leave_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->string('leave_type');
                $table->date('start_date');
                $table->date('end_date');
                $table->integer('days_count');
                $table->text('reason');
                $table->string('status')->default('pending');
                $table->foreignId('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();
            });
        }

        // Create users table if it doesn't exist
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->timestamps();
            });
        }

        // Create attendance table if it doesn't exist
        if (! Schema::hasTable('attendance')) {
            Schema::create('attendance', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->date('date');
                $table->time('check_in')->nullable();
                $table->time('check_out')->nullable();
                $table->integer('work_hours')->nullable();
                $table->integer('overtime_hours')->default(0);
                $table->string('status')->default('present');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->unique(['employee_id', 'date']);
            });
        }

        // Create audit_logs table if it doesn't exist
        if (! Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable();
                $table->string('action');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamps();
            });
        }

        // Initialize the HR service
        $metricsService = $this->app->make(MetricsService::class);
        $auditService = $this->app->make(AuditService::class);
        $this->hrService = new HRDashboardService($metricsService, $auditService);
    }

    protected function tearDown(): void
    {
        // Clean up tables after each test - order matters for foreign keys
        if (Schema::hasTable('audit_logs')) {
            \DB::table('audit_logs')->delete();
        }
        if (Schema::hasTable('attendance')) {
            \DB::table('attendance')->delete();
        }
        if (Schema::hasTable('leave_requests')) {
            \DB::table('leave_requests')->delete();
        }
        if (Schema::hasTable('employees')) {
            \DB::table('employees')->delete();
        }
        if (Schema::hasTable('users')) {
            \DB::table('users')->delete();
        }

        parent::tearDown();
    }

    /**
     * Available leave types for testing
     */
    protected array $leaveTypes = [
        'annual',
        'sick',
        'emergency',
    ];

    /**
     * Default leave allowances per type
     */
    protected array $defaultAllowances = [
        'annual' => 21,
        'sick' => 14,
        'emergency' => 5,
    ];

    /**
     * Generate a random employee
     */
    protected function createRandomEmployee(): Employee
    {
        self::$employeeCounter++;

        return Employee::create([
            'employee_code' => 'EMP'.str_pad(self::$employeeCounter, 5, '0', STR_PAD_LEFT),
            'first_name' => 'Test',
            'last_name' => 'Employee'.self::$employeeCounter,
            'email' => 'employee'.self::$employeeCounter.'_'.time().'@example.com',
            'phone' => '123456789'.self::$employeeCounter,
            'department' => ['IT', 'HR', 'Finance', 'Sales'][array_rand(['IT', 'HR', 'Finance', 'Sales'])],
            'position' => 'Staff',
            'hire_date' => now()->subMonths(rand(1, 24)),
            'salary' => rand(3000, 10000),
            'status' => 'active',
        ]);
    }

    /**
     * Create a mock approver user
     */
    protected function createApprover(): User
    {
        self::$userCounter++;

        return User::create([
            'name' => 'HR Manager '.self::$userCounter,
            'email' => 'hr'.self::$userCounter.'_'.time().'@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    /**
     * Create a leave request for an employee
     * Uses dates within the current year to ensure proper balance calculation
     */
    protected function createLeaveRequest(Employee $employee, string $leaveType, int $daysCount): LeaveRequest
    {
        // Use dates within the current year (past dates to avoid future date issues)
        $currentYear = now()->year;
        $startMonth = rand(1, 11);
        $startDay = rand(1, 20);
        $startDate = \Carbon\Carbon::create($currentYear, $startMonth, $startDay);
        $endDate = $startDate->copy()->addDays($daysCount - 1);

        return LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type' => $leaveType,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days_count' => $daysCount,
            'reason' => 'Test leave request',
            'status' => 'pending',
        ]);
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 19: Leave Balance Adjustment**
     * **Validates: Requirements 10.3**
     *
     * *For any* approved leave request, the employee's available leave balance
     * SHALL be reduced by the number of leave days requested.
     *
     * @test
     */
    public function property_leave_balance_adjustment_on_approval(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            // Create a random employee
            $employee = $this->createRandomEmployee();

            // Pick a random leave type
            $leaveType = $this->leaveTypes[array_rand($this->leaveTypes)];
            $totalAllowance = $this->defaultAllowances[$leaveType];

            // Generate a random number of days to request (1 to half of allowance)
            $daysToRequest = rand(1, max(1, (int) ($totalAllowance / 2)));

            // Get initial leave balance
            $initialBalance = $this->hrService->getLeaveBalance($employee->id, $leaveType);

            // Verify initial balance
            $this->assertEquals(
                $totalAllowance,
                $initialBalance['total_allowance'],
                "Iteration $i: Initial total allowance should match default"
            );
            $this->assertEquals(
                0,
                $initialBalance['used_days'],
                "Iteration $i: Initial used days should be 0"
            );
            $this->assertEquals(
                $totalAllowance,
                $initialBalance['remaining_days'],
                "Iteration $i: Initial remaining days should equal total allowance"
            );

            // Create a leave request
            $leaveRequest = $this->createLeaveRequest($employee, $leaveType, $daysToRequest);

            // Verify pending days are tracked
            $balanceAfterRequest = $this->hrService->getLeaveBalance($employee->id, $leaveType);
            $this->assertEquals(
                $daysToRequest,
                $balanceAfterRequest['pending_days'],
                "Iteration $i: Pending days should equal requested days"
            );

            // Create an approver and approve the leave request
            $approver = $this->createApprover();
            $approvedRequest = $this->hrService->approveLeaveRequest($leaveRequest->id, $approver);

            // Verify the request was approved
            $this->assertNotNull(
                $approvedRequest,
                "Iteration $i: Leave request should be approved successfully"
            );
            $this->assertEquals(
                'approved',
                $approvedRequest->status,
                "Iteration $i: Leave request status should be 'approved'"
            );

            // Get updated leave balance
            $updatedBalance = $this->hrService->getLeaveBalance($employee->id, $leaveType);

            // PROPERTY: Used days should increase by the number of days requested
            $this->assertEquals(
                $daysToRequest,
                $updatedBalance['used_days'],
                "Iteration $i: Used days should equal the approved leave days"
            );

            // PROPERTY: Remaining days should decrease by the number of days requested
            $expectedRemaining = $totalAllowance - $daysToRequest;
            $this->assertEquals(
                $expectedRemaining,
                $updatedBalance['remaining_days'],
                "Iteration $i: Remaining days should be reduced by approved leave days"
            );

            // PROPERTY: Pending days should be 0 after approval
            $this->assertEquals(
                0,
                $updatedBalance['pending_days'],
                "Iteration $i: Pending days should be 0 after approval"
            );

            // PROPERTY: Total allowance should remain unchanged
            $this->assertEquals(
                $totalAllowance,
                $updatedBalance['total_allowance'],
                "Iteration $i: Total allowance should remain unchanged after approval"
            );
        }
    }
}
