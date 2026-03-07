<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first shop created by ShopSeeder
        $shop = Shop::first();

        if (!$shop) {
            // If for some reason shop doesn't exist, create it (via ShopSeeder)
            $this->call(ShopSeeder::class);
            $shop = Shop::first();
        }

        Product::factory()->count(10)->create([
            'shop_id' => $shop->id, // Link to the created shop
        ]);
    }
}
