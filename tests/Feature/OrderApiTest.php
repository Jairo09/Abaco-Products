<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $orderData = [
            'product_id' => $product->id,
            'quantity' => 2,
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $orderData);

        $response->assertStatus(201)
                 ->assertJsonFragment($orderData);
    }
}

