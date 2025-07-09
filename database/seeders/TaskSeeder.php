<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 users
        $users = User::factory(10)->create();

        foreach ($users as $user) {
            Task::factory(rand(2, 5))->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
