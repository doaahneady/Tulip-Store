<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminActivityLogsTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_page_shows_at_least_one_activity_log_when_data_exists(): void
    {
        $admin = Employee::factory()->create([
            'is_admin' => true,
            'is_cs' => false,
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'user_type' => Employee::class,
            'action' => 'support_trader_approved',
            'model_type' => 'App\\Models\\Trader',
            'model_id' => 1,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
        ]);

        $response = $this->actingAs($admin, 'employee')->get(route('dashboard.admin.activity-logs', ['page' => 1]));

        $response->assertOk();
        $response->assertSee('support_trader_approved');
        $response->assertSee($admin->full_name);
        $this->assertNotEmpty($response->viewData('logs')->items());
    }
}