<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate not needed after migrate:fresh

        // Create 30 users
        $users = User::factory(10)->create();

     foreach ($users as $user) {
    \App\Models\Task::factory(rand(2, 5))->create([
        'user_id' => $user->id,
    ]);
        }
    }
}
