<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_unauthenticated()
    {
        $response = $this->postJson('/api/products');

        $response->assertStatus(401);
    }

    public function test_add_authenticated()
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $productData = Product::factory()->raw();

        // Pre-check
        $this->assertEquals(0, Product::query()->count());

        // Execute
        $response = $this->postJson(
            '/api/products',
            Arr::only($productData, ['name', 'description', 'price'])
        );

        // Check
        $response->assertStatus(201);
        $this->assertEquals(1, Product::query()->count());
        $this->assertDatabaseHas('products', $productData);
    }
}
