<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Pending', 'color' => '#f39c12'],
            ['name' => 'In Progress', 'color' => '#00c0ef'],
            ['name' => 'On Hold', 'color' => '#605ca8'],
            ['name' => 'Complete', 'color' => '#00a65a'],
            ['name' => 'Created', 'color' => '#3c8dbc'],
        ];
        
        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
