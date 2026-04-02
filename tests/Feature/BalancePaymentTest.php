<?php

namespace Tests\Feature;

use App\Mail\OrderPaidWithBalanceMail;
use App\Models\Employee;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\CrossDepartmentFlowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class BalancePaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_balance_but_cannot_mutate_it(): void
    {
        $user = User::factory()->create(['balance' => 50]);
        $this->actingAs($user);

        $this->get('/settings')
            ->assertOk()
            ->assertSee('50.00');

        $this->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
            'balance' => 9999,
        ])->assertRedirect('/profile');

        $user->refresh();
        $this->assertSame(50.0, (float) $user->balance);
    }

    public function test_balance_payment_deducts_balance_creates_invoice_and_sends_email(): void
    {
        Mail::fake();

        $user = User::factory()->create(['balance' => 1000]);
        $this->actingAs($user);

        $storeId = DB::table('stores')->insertGetId(array_filter([
            Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id' => $user->id,
            'name' => 'Test Store',
            'slug' => 'store-'.Str::lower(Str::random(6)),
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $p = Product::create([
            'name' => 'Balance Test Product',
            'slug' => 'balance-test-'.Str::lower(Str::random(6)),
            'sku' => 'SKU-'.Str::upper(Str::random(8)),
            'store_id' => $storeId,
            'price' => 100,
            'stock_quantity' => 5,
            'track_inventory' => true,
            'is_active' => true,
        ]);

        session(['cart' => [
            $p->id => 1,
        ]]);

        $idempotencyKey = 'test_balance_'.Str::lower(Str::random(10));

        $resp = $this->withHeader('Idempotency-Key', $idempotencyKey)->postJson('/api/orders/create', [
            'recipient_name' => 'Tester',
            'phone' => '0999999999',
            'village' => 'Sweida',
            'location' => ['lat' => 32.71, 'lng' => 36.56],
            'delivery_method' => 'normal',
            'payment_method' => 'balance',
            'delivery_cost' => 10,
            'service_fee' => 0,
            'address_note' => 'Near hospital',
        ]);

        $resp->assertOk()->assertJson(['success' => true]);
        $orderId = $resp->json('order_id');

        $user->refresh();
        $this->assertSame(890.0, (float) $user->balance);

        if (Schema::hasTable('customer_balance_audits')) {
            $this->assertDatabaseHas('customer_balance_audits', [
                'customer_id' => $user->id,
                'type' => 'purchase',
            ]);
        }

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'payment_method')) {
            $this->assertDatabaseHas('orders', [
                'id' => $orderId,
                'payment_method' => 'balance',
            ]);
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'payment_status')) {
            $this->assertDatabaseHas('orders', [
                'id' => $orderId,
                'payment_status' => 'paid',
            ]);
        }

        if (Schema::hasTable('invoices')) {
            $this->assertDatabaseHas('invoices', [
                'order_id' => $orderId,
                'amount' => 110,
            ]);
        }

        Mail::assertSent(OrderPaidWithBalanceMail::class);
    }

    public function test_balance_payment_is_idempotent(): void
    {
        $user = User::factory()->create(['balance' => 200]);
        $this->actingAs($user);

        $storeId = DB::table('stores')->insertGetId(array_filter([
            Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id' => $user->id,
            'name' => 'Test Store',
            'slug' => 'store-'.Str::lower(Str::random(6)),
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $p = Product::create([
            'name' => 'Balance Idempotency Product',
            'slug' => 'balance-idem-'.Str::lower(Str::random(6)),
            'sku' => 'SKU-'.Str::upper(Str::random(8)),
            'store_id' => $storeId,
            'price' => 100,
            'stock_quantity' => 5,
            'track_inventory' => true,
            'is_active' => true,
        ]);

        session(['cart' => [
            $p->id => 1,
        ]]);

        $idempotencyKey = 'test_idem_'.Str::lower(Str::random(10));

        $first = $this->withHeader('Idempotency-Key', $idempotencyKey)->postJson('/api/orders/create', [
            'recipient_name' => 'Tester',
            'phone' => '0999999999',
            'village' => 'Sweida',
            'location' => ['lat' => 32.71, 'lng' => 36.56],
            'delivery_method' => 'normal',
            'payment_method' => 'balance',
            'delivery_cost' => 10,
            'service_fee' => 0,
        ])->assertOk()->assertJson(['success' => true]);

        $orderId = $first->json('order_id');
        $user->refresh();
        $balanceAfterFirst = (float) $user->balance;

        $second = $this->withHeader('Idempotency-Key', $idempotencyKey)->postJson('/api/orders/create', [
            'recipient_name' => 'Tester',
            'phone' => '0999999999',
            'village' => 'Sweida',
            'location' => ['lat' => 32.71, 'lng' => 36.56],
            'delivery_method' => 'normal',
            'payment_method' => 'balance',
            'delivery_cost' => 10,
            'service_fee' => 0,
        ]);

        $second->assertOk()->assertJson([
            'success' => true,
            'order_id' => $orderId,
            'idempotent' => true,
        ]);

        $user->refresh();
        $this->assertSame($balanceAfterFirst, (float) $user->balance);
    }

    public function test_balance_payment_rejects_insufficient_funds(): void
    {
        $user = User::factory()->create(['balance' => 50]);
        $this->actingAs($user);

        $storeId = DB::table('stores')->insertGetId(array_filter([
            Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id' => $user->id,
            'name' => 'Test Store',
            'slug' => 'store-'.Str::lower(Str::random(6)),
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $p = Product::create([
            'name' => 'Insufficient Balance Product',
            'slug' => 'balance-low-'.Str::lower(Str::random(6)),
            'sku' => 'SKU-'.Str::upper(Str::random(8)),
            'store_id' => $storeId,
            'price' => 100,
            'stock_quantity' => 5,
            'track_inventory' => true,
            'is_active' => true,
        ]);

        session(['cart' => [
            $p->id => 1,
        ]]);

        $resp = $this->postJson('/api/orders/create', [
            'recipient_name' => 'Tester',
            'phone' => '0999999999',
            'village' => 'Sweida',
            'location' => ['lat' => 32.71, 'lng' => 36.56],
            'delivery_method' => 'normal',
            'payment_method' => 'balance',
            'delivery_cost' => 10,
            'service_fee' => 0,
        ]);

        $resp->assertStatus(422)->assertJson([
            'success' => false,
            'message' => 'The balance is not enough to submit this order.',
        ]);

        $user->refresh();
        $this->assertSame(50.0, (float) $user->balance);
        if (Schema::hasTable('orders')) {
            $this->assertDatabaseMissing('orders', [
                'payment_method' => 'balance',
            ]);
        }
    }

    public function test_balance_payment_prevents_second_purchase_when_funds_only_cover_one(): void
    {
        $user = User::factory()->create(['balance' => 120]);
        $this->actingAs($user);

        $storeId = DB::table('stores')->insertGetId(array_filter([
            Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id' => $user->id,
            'name' => 'Test Store',
            'slug' => 'store-'.Str::lower(Str::random(6)),
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $p = Product::create([
            'name' => 'Race Balance Product',
            'slug' => 'balance-race-'.Str::lower(Str::random(6)),
            'sku' => 'SKU-'.Str::upper(Str::random(8)),
            'store_id' => $storeId,
            'price' => 100,
            'stock_quantity' => 5,
            'track_inventory' => true,
            'is_active' => true,
        ]);

        $payload = [
            'recipient_name' => 'Tester',
            'phone' => '0999999999',
            'village' => 'Sweida',
            'location' => ['lat' => 32.71, 'lng' => 36.56],
            'delivery_method' => 'normal',
            'payment_method' => 'balance',
            'delivery_cost' => 10,
            'service_fee' => 0,
        ];

        $this->withSession(['cart' => [$p->id => 1]])->postJson('/api/orders/create', $payload)->assertOk()->assertJson(['success' => true]);
        $this->withSession(['cart' => [$p->id => 1]])->postJson('/api/orders/create', $payload)->assertStatus(422);

        $user->refresh();
        $this->assertGreaterThanOrEqual(0, (float) $user->balance);
    }

    public function test_partial_refund_for_balance_payment_credits_balance_back(): void
    {
        $user = User::factory()->create(['balance' => 200]);
        $this->actingAs($user);

        $storeId = DB::table('stores')->insertGetId(array_filter([
            Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id' => $user->id,
            'name' => 'Test Store',
            'slug' => 'store-'.Str::lower(Str::random(6)),
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $p = Product::create([
            'name' => 'Refund Balance Product',
            'slug' => 'balance-refund-'.Str::lower(Str::random(6)),
            'sku' => 'SKU-'.Str::upper(Str::random(8)),
            'store_id' => $storeId,
            'price' => 100,
            'stock_quantity' => 5,
            'track_inventory' => true,
            'is_active' => true,
        ]);

        session(['cart' => [
            $p->id => 1,
        ]]);

        $resp = $this->postJson('/api/orders/create', [
            'recipient_name' => 'Tester',
            'phone' => '0999999999',
            'village' => 'Sweida',
            'location' => ['lat' => 32.71, 'lng' => 36.56],
            'delivery_method' => 'normal',
            'payment_method' => 'balance',
            'delivery_cost' => 10,
            'service_fee' => 0,
        ])->assertOk()->assertJson(['success' => true]);

        $orderId = $resp->json('order_id');
        $user->refresh();
        $this->assertSame(90.0, (float) $user->balance);

        $ticket = SupportTicket::create([
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'user_id' => $user->id,
            'subject' => 'Refund request',
            'description' => 'Partial refund test',
            'priority' => 'medium',
            'status' => 'open',
            'related_order_id' => $orderId,
        ]);

        CrossDepartmentFlowService::handleTicketRefund($ticket->id, 20, 'Partial refund', null);

        $user->refresh();
        $this->assertSame(110.0, (float) $user->balance);

        if (Schema::hasTable('customer_balance_audits')) {
            $this->assertDatabaseHas('customer_balance_audits', [
                'customer_id' => $user->id,
                'amount' => 20,
            ]);
        }
        if (Schema::hasTable('financial_transactions') && Schema::hasColumn('financial_transactions', 'type')) {
            $this->assertDatabaseHas('financial_transactions', [
                'order_id' => $orderId,
                'type' => 'refund',
            ]);
        }
    }

    public function test_only_customer_support_can_access_customer_balances_dashboard_and_adjust(): void
    {
        $customer = User::factory()->create(['balance' => 10]);

        $unauthorized = Employee::factory()->create(['is_cs' => false]);
        $this->actingAs($unauthorized, 'employee');
        $this->get('/dashboard/cs/customer-balances')->assertStatus(403);

        $authorized = Employee::factory()->create(['is_cs' => true]);
        $this->actingAs($authorized, 'employee');
        $this->get('/dashboard/cs/customer-balances')->assertOk();

        $this->withSession(['_token' => 'test-token'])->post("/dashboard/cs/customers/{$customer->id}/balance", [
            '_token' => 'test-token',
            'action' => 'add',
            'amount' => 5,
        ])->assertRedirect();

        $customer->refresh();
        $this->assertSame(15.0, (float) $customer->balance);
    }
}
