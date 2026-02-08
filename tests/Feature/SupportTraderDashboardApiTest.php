<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Trader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SupportTraderDashboardApiTest extends TestCase
{
    use RefreshDatabase;

    protected function makeTrader(): array
    {
        $user = User::factory()->create([
            'email' => 'trader.approval+'.Str::lower(Str::random(6)).'@example.com',
            'password' => bcrypt('password123'),
            'is_trader' => true,
        ]);
        $trader = Trader::create([
            'user_id' => $user->id,
            'name' => 'New Trader',
            'contact_email' => $user->email,
            'contact_phone' => '0999',
            'status' => Trader::STATUS_PENDING,
            'commission_rate' => 10,
            'payout_settings' => ['documents' => []],
        ]);

        return [$user, $trader];
    }

    public function test_pending_traders_list_and_approve(): void
    {
        [$user, $trader] = $this->makeTrader();

        $support = Employee::factory()->create([
            'is_cs' => true,
            'department' => 'Support',
        ]);
        $this->actingAs($support, 'employee');

        $resList = $this->getJson('/api/support/traders/pending');
        $resList->assertOk()->assertJsonPath('success', true);

        $resApprove = $this->postJson('/api/support/traders/'.$trader->id.'/approve');
        $resApprove->assertOk()->assertJsonPath('success', true);
        $this->assertEquals(Trader::STATUS_APPROVED, $trader->fresh()->status);
    }

    public function test_trader_product_approval_flow(): void
    {
        [$user, $trader] = $this->makeTrader();
        $trader->update(['status' => Trader::STATUS_APPROVED]);

        $support = Employee::factory()->create([
            'is_cs' => true,
            'department' => 'Support',
        ]);
        $this->actingAs($support, 'employee');

        $product = Product::create([
            'name' => 'Prod Approve',
            'slug' => 'prod-approve-'.Str::lower(Str::random(6)),
            'trader_id' => $trader->id,
            'price' => 10,
            'stock_quantity' => 2,
            'track_inventory' => true,
            'is_active' => false,
            'status' => 'pending',
        ]);

        $resQueue = $this->getJson('/api/support/trader-products/pending');
        $resQueue->assertOk()->assertJsonPath('success', true);

        $resApprove = $this->postJson('/api/support/trader-products/'.$product->id.'/approve');
        $resApprove->assertOk()->assertJsonPath('success', true);
        $this->assertEquals('approved', $product->fresh()->status);
        $this->assertTrue((bool) $product->fresh()->is_active);

        $resReject = $this->postJson('/api/support/trader-products/'.$product->id.'/reject', [
            'reason' => 'Incorrect data',
        ]);
        $resReject->assertOk()->assertJsonPath('success', true);
        $this->assertEquals('rejected', $product->fresh()->status);
        $this->assertFalse((bool) $product->fresh()->is_active);
    }
}
