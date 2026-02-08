<?php

namespace Tests\Feature;

use App\Models\Trader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TraderDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_requires_authentication(): void
    {
        $res = $this->get('/trader/dashboard');
        $res->assertStatus(302);
    }

    public function test_dashboard_forbidden_for_non_trader_user(): void
    {
        $user = User::factory()->create([
            'is_trader' => false,
        ]);

        $res = $this->actingAs($user)->get('/trader/dashboard');
        $res->assertStatus(403);
    }

    public function test_dashboard_loads_for_approved_trader(): void
    {
        $user = User::factory()->create([
            'is_trader' => true,
            'email' => 'approveddash@example.com',
            'password' => bcrypt('password123'),
        ]);

        Trader::create([
            'user_id' => $user->id,
            'name' => 'Demo Trader',
            'contact_email' => $user->email,
            'status' => Trader::STATUS_APPROVED,
            'commission_rate' => 10,
        ]);

        $res = $this->actingAs($user)->get('/trader/dashboard');
        $res->assertOk();
        $res->assertSee('لوحة تحكم التاجر');
    }
}
