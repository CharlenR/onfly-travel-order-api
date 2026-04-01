<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        User::firstOrCreate([
            'email' => 'user@onfly.com',
        ], [
            'name' => 'Regular User',
            'email' => 'user@onfly.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        User::firstOrCreate([
            'email' => 'admin@onfly.com',
        ], [
            'name' => 'Admin User',
            'email' => 'admin@onfly.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);
    }
}
