<?php

namespace Tests\Property;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\Dashboard\AuditService;
use App\Services\Dashboard\HRDashboardService;
use App\Services\Dashboard\MetricsService;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Property-Based Tests for Payroll Calculation
 *
 * These tests verify the correctness properties of payroll calculations
 * by running multiple iterations with randomly generated test data.
 *
 * **Feature: dashboard-system-rebuild, Property 20: Payroll Calculation Correctness**
 * **Validates: Requirements 10.4**
 */
class PayrollCalculationPropertyTest extends TestCase
{
    protected HRDashboardService $hrService;

    protected static int $employeeCounter = 0;

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

        // Create payroll table if it doesn't exist
        if (! Schema::hasTable('payroll')) {
            Schema::create('payroll', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->string('month');
                $table->decimal('basic_salary', 10, 2);
                $table->decimal('allowances', 10, 2)->default(0);
                $table->decimal('bonuses', 10, 2)->default(0);
                $table->decimal('overtime_pay', 10, 2)->default(0);
                $table->decimal('deductions', 10, 2)->default(0);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('insurance', 10, 2)->default(0);
                $table->decimal('net_salary', 10, 2);
                $table->string('status')->default('draft');
                $table->date('payment_date')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->unique(['employee_id', 'month']);
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
        if (Schema::hasTable('payroll')) {
            \DB::table('payroll')->delete();
        }
        if (Schema::hasTable('attendance')) {
            \DB::table('attendance')->delete();
        }
        if (Schema::hasTable('employees')) {
            \DB::table('employees')->delete();
        }

        parent::tearDown();
    }

    /**
     * Generate a random employee with a specific salary
     */
    protected function createRandomEmployee(?float $salary = null): Employee
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
            'salary' => $salary ?? rand(3000, 10000),
            'status' => 'active',
        ]);
    }

    /**
     * Create attendance records for an employee for a specific month
     * Returns the actual total overtime minutes stored in the database
     */
    protected function createAttendanceRecords(Employee $employee, string $month, int $absentDays = 0, int $overtimeMinutes = 0): int
    {
        $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $monthEnd = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $workDays = [];
        for ($date = $monthStart->copy(); $date->lte($monthEnd); $date->addDay()) {
            // Skip weekends
            if (! $date->isWeekend()) {
                $workDays[] = $date->copy();
            }
        }

        // Mark some days as absent
        $absentDates = array_slice($workDays, 0, min($absentDays, count($workDays)));
        $presentDates = array_slice($workDays, $absentDays);

        // Create absent records
        foreach ($absentDates as $date) {
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $date->format('Y-m-d'),
                'status' => 'absent',
            ]);
        }

        // Create present records with overtime distributed
        // Track actual overtime stored to avoid rounding issues
        $actualTotalOvertime = 0;
        $overtimePerDay = count($presentDates) > 0 ? (int) ($overtimeMinutes / count($presentDates)) : 0;
        foreach ($presentDates as $date) {
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $date->format('Y-m-d'),
                'check_in' => '09:00',
                'check_out' => '17:00',
                'work_hours' => 480, // 8 hours in minutes
                'overtime_hours' => $overtimePerDay,
                'status' => 'present',
            ]);
            $actualTotalOvertime += $overtimePerDay;
        }

        return $actualTotalOvertime;
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 20: Payroll Calculation Correctness**
     * **Validates: Requirements 10.4**
     *
     * *For any* employee payroll calculation, the net salary SHALL equal
     * base_salary + bonuses - deductions - (absent_days * daily_rate).
     *
     * @test
     */
    public function property_payroll_calculation_correctness(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            // Generate random salary between 3000 and 15000
            $baseSalary = round(rand(300000, 1500000) / 100, 2);

            // Create employee with specific salary
            $employee = $this->createRandomEmployee($baseSalary);

            // Generate random adjustments
            $allowances = round(rand(0, 50000) / 100, 2);
            $bonuses = round(rand(0, 100000) / 100, 2);
            $deductions = round(rand(0, 30000) / 100, 2);
            $tax = round(rand(0, 20000) / 100, 2);
            $insurance = round(rand(0, 10000) / 100, 2);

            // Generate random absent days (0-5)
            $absentDays = rand(0, 5);

            // Generate random overtime (0-600 minutes = 0-10 hours)
            $overtimeMinutes = rand(0, 600);

            // Use a month in the past to ensure we have a full month
            $month = Carbon::now()->subMonth()->format('Y-m');

            // Create attendance records and get actual overtime stored
            $actualOvertimeMinutes = $this->createAttendanceRecords($employee, $month, $absentDays, $overtimeMinutes);

            // Calculate payroll
            $adjustments = [
                'allowances' => $allowances,
                'bonuses' => $bonuses,
                'deductions' => $deductions,
                'tax' => $tax,
                'insurance' => $insurance,
            ];

            $payroll = $this->hrService->calculatePayroll($employee->id, $month, $adjustments);

            // Calculate expected values using actual overtime stored (not requested)
            $dailyRate = $baseSalary / 30;
            $hourlyRate = $dailyRate / 8;
            $expectedOvertimePay = round(($actualOvertimeMinutes / 60) * $hourlyRate * 1.5, 2);
            $absentDeduction = $absentDays * $dailyRate;

            // Expected net salary formula:
            // net_salary = base_salary + allowances + bonuses + overtime_pay - deductions - tax - insurance - absent_deduction
            // Note: The service combines deductions + absentDeduction into the deductions field
            $expectedNetSalary = $baseSalary + $allowances + $bonuses + $expectedOvertimePay
                               - $deductions - $tax - $insurance - $absentDeduction;
            $expectedNetSalary = max(0, round($expectedNetSalary, 2));

            // PROPERTY: Basic salary should match employee's salary
            $this->assertEquals(
                $baseSalary,
                (float) $payroll->basic_salary,
                "Iteration $i: Basic salary should match employee's salary"
            );

            // PROPERTY: Allowances should match input
            $this->assertEquals(
                $allowances,
                (float) $payroll->allowances,
                "Iteration $i: Allowances should match input"
            );

            // PROPERTY: Bonuses should match input
            $this->assertEquals(
                $bonuses,
                (float) $payroll->bonuses,
                "Iteration $i: Bonuses should match input"
            );

            // PROPERTY: Tax should match input
            $this->assertEquals(
                $tax,
                (float) $payroll->tax,
                "Iteration $i: Tax should match input"
            );

            // PROPERTY: Insurance should match input
            $this->assertEquals(
                $insurance,
                (float) $payroll->insurance,
                "Iteration $i: Insurance should match input"
            );

            // PROPERTY: Overtime pay should be calculated correctly (1.5x hourly rate)
            $this->assertEqualsWithDelta(
                $expectedOvertimePay,
                (float) $payroll->overtime_pay,
                0.02, // Allow small rounding difference
                "Iteration $i: Overtime pay should be calculated at 1.5x hourly rate"
            );

            // PROPERTY: Deductions should include absent days deduction
            $expectedTotalDeductions = $deductions + $absentDeduction;
            $this->assertEqualsWithDelta(
                $expectedTotalDeductions,
                (float) $payroll->deductions,
                0.02,
                "Iteration $i: Deductions should include absent days deduction"
            );

            // PROPERTY: Net salary should follow the formula
            $this->assertEqualsWithDelta(
                $expectedNetSalary,
                (float) $payroll->net_salary,
                0.05, // Allow small rounding difference
                "Iteration $i: Net salary should equal base_salary + allowances + bonuses + overtime - deductions - tax - insurance - absent_deduction"
            );

            // PROPERTY: Net salary should never be negative
            $this->assertGreaterThanOrEqual(
                0,
                (float) $payroll->net_salary,
                "Iteration $i: Net salary should never be negative"
            );

            // PROPERTY: Payroll status should be 'draft' initially
            $this->assertEquals(
                'draft',
                $payroll->status,
                "Iteration $i: New payroll should have 'draft' status"
            );
        }
    }
}
