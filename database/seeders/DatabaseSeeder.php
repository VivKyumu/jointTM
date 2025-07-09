<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 3 regular users
        User::factory(3)->create();

        // Safely create or update admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_admin' => true
            ]
        );

        // Create a specific user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create 3 regular users if they don't exist (excluding admin and test user)
        if (User::count() <= 2) { // Adjusted condition to allow test + admin
            User::factory(3)->create();
        }

        // Seed default application data
        $this->call([
            RoleSeeder::class,
            StatusSeeder::class,
            StatusesTableSeeder::class,
            GroupSeeder::class,
            TaskComplexitiesTableSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
