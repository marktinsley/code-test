<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Money\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $productData['price'] = Money::toInt($productData['price']);
        $response->assertStatus(201);
        $response->assertJson($productData);
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
        $newProductData['price'] = Money::toInt($newProductData['price']);
        $response->assertOk();
        $response->assertJson($newProductData);
        $this->assertEquals(1, Product::query()->count());
        $this->assertDatabaseHas('products', $newProductData);
    }

    public function test_delete_unauthenticated()
    {
        // Arrange
        $product = Product::factory()->create();

        // Execute
        $response = $this->deleteJson("/api/products/{$product->getKey()}");

        // Check
        $response->assertStatus(401);
    }

    public function test_delete_authenticated()
    {
        // Arrange
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $product = Product::factory()->times(3)->create()->get(1);

        // Pre-check
        $this->assertEquals(3, Product::query()->count());

        // Execute
        $response = $this->deleteJson("/api/products/{$product->getKey()}");

        // Check
        $response->assertOk();
        $this->assertEquals(2, Product::query()->count());
    }
}
