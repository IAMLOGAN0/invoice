<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User; // Import the User model
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // For hashing passwords

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure a user exists to link the shop to
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        Shop::create([
            'name' => 'My Awesome Shop',
            'gstin' => '22AAAAA0000A1Z5',
            'address' => '123 Main Street, Someville, 123456',
            'state_code' => 'DL', // Delhi
            'user_id' => $user->id, // Link to the created user
        ]);
    }
}
