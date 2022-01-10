<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->times(100)->create();

        /** @var User $testUser */
        if ($testUser = User::query()->firstWhere('email', 'test-user@example.com')) {
            $testUser->products()->sync(Product::factory()->times(10)->create()->pluck('id'));
        }
    }
}
