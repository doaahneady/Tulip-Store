<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImageFallbackLogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_fallback_log_endpoint_accepts_payload(): void
    {
        $res = $this->postJson('/api/image-fallback/log', [
            'url' => 'https://example.test/storage/x.jpg',
            'status' => 404,
            'at' => now()->toIso8601String(),
            'context' => 'product-thumb-2',
        ]);

        $res->assertOk()->assertJson(['success' => true]);
    }
}

