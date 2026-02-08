<?php

namespace Tests\Feature;

use App\Models\Trader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TraderAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_register_creates_user_and_pending_trader(): void
    {
        $email = 'trader.api+'.Str::lower(Str::random(6)).'@example.com';

        $payload = [
            'business_name_en' => 'Acme Trading LLC',
            'business_name_ar' => 'شركة اكمي',
            'email' => $email,
            'phone' => '0999888777',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
            'registration_number' => 'REG-123',
            'tax_id' => 'TAX-999',
            'contact_person' => 'John Doe',
            'business_address' => 'Main St. 123',
            'bank_name' => 'Bank A',
            'account_holder' => 'Acme',
            'account_number' => 'ACC-123',
            'iban' => 'SY12-XXXX',
        ];

        $res = $this->postJson('/api/trader/register', $payload);

        $res->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'is_trader' => true,
        ]);
        $this->assertDatabaseHas('traders', [
            'contact_email' => $email,
            'status' => Trader::STATUS_PENDING,
        ]);
    }

    public function test_api_login_rejects_non_trader_user(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
            'is_trader' => false,
            'password' => bcrypt('password123'),
        ]);

        $res = $this->postJson('/api/trader/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $res->assertStatus(403);
    }

    public function test_api_login_requires_trader_approved(): void
    {
        $user = User::factory()->create([
            'email' => 'pendingtrader@example.com',
            'is_trader' => true,
            'password' => bcrypt('password123'),
        ]);
        Trader::create([
            'user_id' => $user->id,
            'name' => 'Pending Trader',
            'contact_email' => $user->email,
            'contact_phone' => '0999',
            'status' => Trader::STATUS_PENDING,
            'commission_rate' => 10,
        ]);

        $res = $this->postJson('/api/trader/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $res->assertStatus(403);
    }

    public function test_api_login_success_for_approved_trader_returns_token(): void
    {
        $user = User::factory()->create([
            'email' => 'approvedtrader@example.com',
            'is_trader' => true,
            'password' => bcrypt('password123'),
        ]);
        Trader::create([
            'user_id' => $user->id,
            'name' => 'Approved Trader',
            'contact_email' => $user->email,
            'contact_phone' => '0999',
            'status' => Trader::STATUS_APPROVED,
            'commission_rate' => 12.5,
        ]);

        $res = $this->postJson('/api/trader/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $res->assertOk()
            ->assertJsonStructure(['success', 'token', 'redirect'])
            ->assertJson(['success' => true]);
    }

    public function test_web_login_redirects_based_on_trader_status(): void
    {
        // Pending
        $pending = User::factory()->create([
            'email' => 'webpending@example.com',
            'is_trader' => true,
            'password' => bcrypt('pass12345'),
        ]);
        Trader::create([
            'user_id' => $pending->id,
            'name' => 'Web Pending',
            'contact_email' => $pending->email,
            'status' => Trader::STATUS_PENDING,
            'commission_rate' => 10,
        ]);

        $res1 = $this->post('/trader/login', [
            'email' => $pending->email,
            'password' => 'pass12345',
        ]);
        $res1->assertSessionHasErrors('email');

        // Approved
        $approved = User::factory()->create([
            'email' => 'webapproved@example.com',
            'is_trader' => true,
            'password' => bcrypt('pass12345'),
        ]);
        Trader::create([
            'user_id' => $approved->id,
            'name' => 'Web Approved',
            'contact_email' => $approved->email,
            'status' => Trader::STATUS_APPROVED,
            'commission_rate' => 10,
        ]);

        $res2 = $this->post('/trader/login', [
            'email' => $approved->email,
            'password' => 'pass12345',
        ]);
        $res2->assertRedirect('/trader/dashboard');
    }
}
