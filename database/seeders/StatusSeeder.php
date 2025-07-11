<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Pending', 'color' => '#f39c12', 'order' => 1],
            ['name' => 'In Progress', 'color' => '#00c0ef', 'order' => 2],
            ['name' => 'On Hold', 'color' => '#6c757d', 'order' => 3],
            ['name' => 'Completed', 'color' => '#28a745', 'order' => 4],
        ];

        foreach ($statuses as $status) {
            Status::updateOrCreate(
                ['name' => $status['name']],
                ['color' => $status['color'], 'order' => $status['order']]
            );
        }
    }
}
