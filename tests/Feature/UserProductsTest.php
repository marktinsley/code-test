<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_attach_to_a_user()
    {
        // Arrange
        /** @var User $user */
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $product = Product::factory()->times(3)->create()[0];

        // Pre-check
        $this->assertEquals(0, $user->products()->count());

        // Execute
        $response = $this->putJson("/api/user-products/{$product->getKey()}");

        // Check
        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertEquals(1, $user->products()->count());
        $this->assertTrue($user->products()->where('products.id', $product->getKey())->exists());
    }

    public function test_can_detach_from_a_user()
    {
        // Arrange
        /** @var User $user */
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $products = Product::factory()->times(3)->create();
        $user->products()->sync([$products[0]->getKey(), $products[2]->getKey()]);

        // Pre-check
        $this->assertEquals(2, $user->products()->count());

        // Execute
        $response = $this->deleteJson("/api/user-products/{$products[2]->getKey()}");

        // Check
        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertEquals(1, $user->products()->count());
    }
}
