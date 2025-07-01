<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run()
    {
        // Clear the table first
        Status::truncate();

        // Create default statuses
        $statuses = [
            ['name' => 'Pending', 'color' => '#ffc107', 'order' => 1],
            ['name' => 'In Progress', 'color' => '#17a2b8', 'order' => 2],
            ['name' => 'On Hold', 'color' => '#6c757d', 'order' => 3],
            ['name' => 'Completed', 'color' => '#28a745', 'order' => 4],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }

        // If you want additional random statuses (optional)
        // \App\Models\Status::factory()->count(5)->create();
    }
}