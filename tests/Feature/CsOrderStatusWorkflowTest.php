<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class CsOrderStatusWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function createOrder(string $status = 'pending'): Order
    {
        $user = User::factory()->create();

        $storeId = null;
        if (Schema::hasTable('stores')) {
            $storeId = DB::table('stores')->insertGetId(array_filter([
                Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : (Schema::hasColumn('stores', 'user_id') ? 'user_id' : null) => $user->id,
                'name' => 'Store '.Str::lower(Str::random(6)),
                'slug' => 's-'.Str::lower(Str::random(8)),
            ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));
        }

        $data = array_filter([
            Schema::hasColumn('orders', 'order_number') ? 'order_number' : null => 'ORD-'.Str::upper(Str::random(8)),
            Schema::hasColumn('orders', 'status') ? 'status' : null => $status,
            Schema::hasColumn('orders', 'payment_status') ? 'payment_status' : null => 'pending',
            Schema::hasColumn('orders', 'recipient_name') ? 'recipient_name' : null => 'Tester',
            Schema::hasColumn('orders', 'phone') ? 'phone' : null => '0999999999',
            Schema::hasColumn('orders', 'total') ? 'total' : null => 100,
            Schema::hasColumn('orders', 'user_id') ? 'user_id' : null => $user->id,
            Schema::hasColumn('orders', 'customer_id') ? 'customer_id' : null => $user->id,
            Schema::hasColumn('orders', 'store_id') ? 'store_id' : null => $storeId,
            'created_at' => now(),
            'updated_at' => now(),
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH);

        $id = DB::table('orders')->insertGetId($data);

        return Order::query()->findOrFail($id);
    }

    public function test_cs_can_change_order_status_and_audit_log_created()
    {
        $employee = Employee::factory()->create(['is_cs' => true]);
        $this->actingAs($employee, 'employee');

        $order = $this->createOrder('pending');

        $resp = $this->post(route('dashboard.cs.orders.change-status', $order), [
            'status' => 'confirmed',
        ]);

        $resp->assertStatus(302);
        $this->assertSame('confirmed', $order->fresh()->status);

        if (Schema::hasTable('audit_logs')) {
            $this->assertDatabaseHas('audit_logs', [
                'action' => 'order_status_changed',
                'model_type' => Order::class,
                'model_id' => $order->id,
            ]);
        }
    }

    public function test_cs_order_route_endpoint_returns_track_payload()
    {
        $employee = Employee::factory()->create(['is_cs' => true]);
        $this->actingAs($employee, 'employee');

        $order = $this->createOrder('pending');

        $resp = $this->getJson(route('dashboard.cs.orders.route', $order));
        $resp->assertStatus(200)->assertJson([
            'success' => true,
            'order_id' => $order->id,
        ]);
    }
}

