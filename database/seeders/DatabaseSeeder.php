<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Seeders\TaskSeeder;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Task::factory()->count(30)->create(); // Or however many you need
        // Create 3 users
        User::factory(3)->create();

        // Create a specific user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Call the task seeder
        $this->call([
            TaskSeeder::class,
            RoleSeeder::class, // Ensure RoleSeeder is called to create roles
            StatusSeeder::class,// Add other seeders here
        ]);
    }
}
