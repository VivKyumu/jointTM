<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Pending', 'color' => '#64748b', 'order' => 1],
            ['name' => 'In Progress', 'color' => '#f59e0b', 'order' => 2],
            ['name' => 'Completed', 'color' => '#0f766e', 'order' => 3],
            ['name' => 'On Hold', 'color' => '#2563eb', 'order' => 4],
        ];

        foreach ($statuses as $status) {
            Status::updateOrCreate(
                ['name' => $status['name']],
                [
                    'color' => $status['color'],
                    'order' => $status['order'],
                ]
            );
        }
    }
}
