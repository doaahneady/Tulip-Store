<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_submission_requires_auth()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 100,
            'is_active' => true,
        ]);

        $resp = $this->postJson('/api/reviews', [
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Great',
        ]);

        $resp->assertStatus(401);
    }

    public function test_review_submission_and_moderation_updates_product_metrics()
    {
        $user = User::factory()->create();
        $admin = Employee::factory()->create(['is_admin' => true]);

        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 100,
            'is_active' => true,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-TEST-1',
            'payment_status' => 'paid',
            'total' => 100,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 100,
            'total_price' => 100,
        ]);

        $resp = $this->actingAs($user, 'sanctum')->postJson('/api/reviews', [
            'product_id' => $product->id,
            'rating' => 4,
            'comment' => 'Good',
        ]);
        $resp->assertStatus(201);
        $data = $resp->json()['data'];
        $this->assertEquals($product->id, $data['product_id']);
        $this->assertFalse((bool) $data['is_approved']);
        $this->assertTrue((bool) $data['is_verified_purchase']);

        $this->assertEquals(1, Review::where('product_id', $product->id)->count());

        $reviewId = $data['id'];

        $approve = $this->actingAs($admin, 'employee')->post('/dashboard/reviews/'.$reviewId.'/approve');
        $approve->assertStatus(200);

        $product->refresh();
        $this->assertEquals(1, (int) $product->reviews_count);
        $this->assertEquals(4, (int) $product->rating);

        $show = $this->getJson('/api/products/'.$product->id);
        $show->assertStatus(200);
        $json = $show->json();
        $this->assertArrayHasKey('reviews', $json);
        $this->assertCount(1, $json['reviews']);
        $this->assertEquals(4, (int) $json['reviews'][0]['rating']);
    }
}
