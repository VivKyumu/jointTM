<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TaskComplexity;

class TaskComplexitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $complexities = [
            [
                'name' => 'Very Simple',
                'level' => 1,
                'color' => '#00a65a', // Green
                'duration' => 1,
            ],
            [
                'name' => 'Simple',
                'level' => 2,
                'color' => '#3c8dbc', // Blue
                'duration' => 2,
            ],
            [
                'name' => 'Medium',
                'level' => 3,
                'color' => '#f39c12', // Orange
                'duration' => 4,
            ],
            [
                'name' => 'Complex',
                'level' => 4,
                'color' => '#dd4b39', // Red
                'duration' => 21,
            ],
            [
                'name' => 'Very Complex',
                'level' => 5,
                'color' => '#d81b60', // Pink
                'duration' => 30,
            ],
        ];

        foreach ($complexities as $complexity) {
            DB::table('task_complexities')->updateOrInsert(
                ['level' => $complexity['level']],
                array_merge($complexity, [
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }
}
