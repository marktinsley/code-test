<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Money\Money;
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
        $productData['price'] = Money::toFloat($productData['price']);

        // Pre-check
        $this->assertEquals(0, Product::query()->count());

        // Execute
        $response = $this->postJson(
            '/api/products',
            Arr::only($productData, ['name', 'description', 'price'])
        );

        // Check
        $response->assertStatus(201);
        $productData['price'] = Money::toInt($productData['price']);
        $this->assertEquals(1, Product::query()->count());
        $this->assertDatabaseHas('products', $productData);
    }

    public function test_update_unauthenticated()
    {
        // Arrange
        $product = Product::factory()->create();

        // Execute
        $response = $this->putJson("/api/products/{$product->getKey()}");

        // Check
        $response->assertStatus(401);
    }

    public function test_update_authenticated()
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $product = Product::factory()->create();
        $newProductData = [
            'name' => $product->name . '1',
            'description' => $product->description . '2',
            'price' => Money::toFloat($product->price) + 20,
        ];

        // Pre-check
        $this->assertEquals(1, Product::query()->count());

        // Execute
        $response = $this->putJson("/api/products/{$product->getKey()}", $newProductData);

        // Check
        $response->assertOk();
        $newProductData['price'] = Money::toInt($newProductData['price']);
        $this->assertEquals(1, Product::query()->count());
        $this->assertDatabaseHas('products', $newProductData);
    }
}
