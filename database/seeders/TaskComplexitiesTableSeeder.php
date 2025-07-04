<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'color' => '#00a65a' // Green
            ],
            [
                'name' => 'Simple', 
                'level' => 2, 
                'color' => '#3c8dbc' // Blue
            ],
            [
                'name' => 'Medium', 
                'level' => 3, 
                'color' => '#f39c12' // Orange
            ],
            [
                'name' => 'Complex', 
                'level' => 4, 
                'color' => '#dd4b39' // Red
            ],
            [
                'name' => 'Very Complex', 
                'level' => 5, 
                'color' => '#d81b60' // Pink
            ],
        ];
        
        foreach ($complexities as $complexity) {
            DB::table('task_complexities')->updateOrInsert(
                ['level' => $complexity['level']], // The condition to check
                $complexity + [                     // Data to insert/update
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}