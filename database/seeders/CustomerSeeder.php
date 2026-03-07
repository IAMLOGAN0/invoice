<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user and shop created by ShopSeeder
        $user = User::first();
        $shop = Shop::first();

        if (!$user || !$shop) {
            // If for some reason user or shop doesn't exist, create them
            // This ensures CustomerSeeder can run independently or after ShopSeeder
            $this->call(ShopSeeder::class);
            $user = User::first();
            $shop = Shop::first();
        }

        Customer::factory()->count(5)->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);
    }
}
