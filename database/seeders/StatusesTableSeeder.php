<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    public function run(): void
    {
        $additionalStatuses = [
            ['name' => 'Complete', 'color' => '#00a65a'],
            ['name' => 'Created', 'color' => '#3c8dbc'],
        ];

        foreach ($additionalStatuses as $status) {
            Status::firstOrCreate(
                ['name' => $status['name']],
                ['color' => $status['color'], 'order' => 0]
            );
        }
    }
}
