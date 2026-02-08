<?php

namespace Tests\Feature\HR;

use App\Models\Employee;
use App\Models\FinancialTransaction;
use App\Models\Payroll;
use App\Services\Dashboard\HRDashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PayrollFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $hrService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hrService = $this->app->make(HRDashboardService::class);
    }

    public function test_employee_creation_hashes_password()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'secret123',
            'department' => 'IT',
            'position' => 'Dev',
            'hire_date' => '2025-01-01',
            'salary' => 5000,
            'status' => 'active',
            'is_it' => true,
        ];

        $employee = $this->hrService->createEmployee($data);

        $this->assertDatabaseHas('employees', ['email' => 'john.doe@example.com']);
        $this->assertTrue(Hash::check('secret123', $employee->password));
        $this->assertTrue($employee->is_it);
    }

    public function test_payroll_process_creates_financial_transaction()
    {
        $employee = Employee::factory()->create([
            'salary' => 10000,
            'status' => 'active',
        ]);

        $payroll = Payroll::create([
            'employee_id' => $employee->id,
            'month' => '2025-01',
            'basic_salary' => 10000,
            'net_salary' => 10000,
            'status' => 'draft',
        ]);

        $processedPayroll = $this->hrService->processPayroll($payroll->id);

        $this->assertEquals('processed', $processedPayroll->status);

        $this->assertDatabaseHas('financial_transactions', [
            'type' => 'salary_payment',
            'amount' => 10000,
            'status' => 'pending',
        ]);

        $txn = FinancialTransaction::where('type', 'salary_payment')->first();
        $this->assertEquals($payroll->id, $txn->metadata['payroll_id']);
        $this->assertEquals($employee->id, $txn->metadata['employee_id']);
    }
}
