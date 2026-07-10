<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ],
        );

        User::updateOrCreate(
            ['email' => 'admin@shuttl.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'phone' => '09171234567',
                'gender' => 'male',
                'date_of_birth' => '1990-01-01',
                'role' => 'admin',
                'rating_value' => 1000.00,
                'matches_played' => 0,
            ],
        );
    }
}
