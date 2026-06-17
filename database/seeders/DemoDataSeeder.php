<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\Task;
use App\Models\TaskComplexity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = collect([
            ['name' => 'Pending', 'color' => '#6c757d', 'order' => 1],
            ['name' => 'In Progress', 'color' => '#ffc107', 'order' => 2],
            ['name' => 'Completed', 'color' => '#28a745', 'order' => 3],
            ['name' => 'On Hold', 'color' => '#17a2b8', 'order' => 4],
        ])->mapWithKeys(fn ($status) => [
            strtolower($status['name']) => Status::updateOrCreate(
                ['name' => $status['name']],
                ['color' => $status['color'], 'order' => $status['order']]
            ),
        ]);

        $complexities = collect([
            ['name' => 'Very Simple', 'level' => 1, 'color' => '#007bff', 'duration' => 1],
            ['name' => 'Simple', 'level' => 2, 'color' => '#28a745', 'duration' => 2],
            ['name' => 'Medium', 'level' => 3, 'color' => '#ffc107', 'duration' => 4],
            ['name' => 'Complex', 'level' => 4, 'color' => '#dc3545', 'duration' => 14],
            ['name' => 'Very Complex', 'level' => 5, 'color' => '#6f42c1', 'duration' => 21],
        ])->mapWithKeys(function ($complexity) {
            DB::table('task_complexities')->updateOrInsert(
                ['level' => $complexity['level']],
                array_merge($complexity, ['created_at' => now(), 'updated_at' => now()])
            );

            return [strtolower($complexity['name']) => TaskComplexity::where('level', $complexity['level'])->first()];
        });

        $names = [
            'Grace Wanjiku', 'Brian Otieno', 'Mercy Achieng', 'Kevin Mwangi', 'Faith Njeri',
            'Samuel Kamau', 'Lydia Wambui', 'Dennis Ochieng', 'Pauline Mutua', 'James Kariuki',
            'Esther Auma', 'Collins Kipchoge', 'Beatrice Ndungu', 'Victor Onyango', 'Winnie Moraa',
            'Patrick Njoroge', 'Sandra Akinyi', 'George Muthoni', 'Cynthia Wairimu', 'Robert Odhiambo',
            'Agnes Nyambura', 'Timothy Gitonga', 'Judith Chebet', 'Emmanuel Ouma', 'Priscilla Wangari',
            'Henry Owino', 'Caroline Mwikali', 'Francis Mutuku', 'Irene Adhiambo', 'Joseph Kiptoo',
        ];

        $taskTemplates = [
            ['title' => 'Prepare monthly sales report', 'description' => 'Compile monthly sales figures and share the final report with management.'],
            ['title' => 'Update client database', 'description' => 'Review client records and update missing contact details.'],
            ['title' => 'Review project proposal', 'description' => 'Check the project proposal and note areas that need improvement.'],
            ['title' => 'Submit weekly timesheet', 'description' => 'Record weekly work hours and submit the timesheet for approval.'],
            ['title' => 'Conduct team meeting', 'description' => 'Lead a short team meeting and capture the agreed action points.'],
            ['title' => 'Fix login module bug', 'description' => 'Investigate and resolve the reported login issue.'],
            ['title' => 'Design new dashboard layout', 'description' => 'Create a cleaner dashboard layout for easier task tracking.'],
            ['title' => 'Write unit tests', 'description' => 'Add tests for the most important task management features.'],
            ['title' => 'Update system documentation', 'description' => 'Revise the user guide so it matches the current system features.'],
            ['title' => 'Send weekly progress report', 'description' => 'Prepare and send a short progress update to the supervisor.'],
            ['title' => 'Review pull request', 'description' => 'Review code changes and provide clear feedback to the developer.'],
            ['title' => 'Deploy updated application', 'description' => 'Deploy the latest approved changes to the test environment.'],
            ['title' => 'Backup database', 'description' => 'Create a database backup and confirm that the backup file is valid.'],
            ['title' => 'Respond to client emails', 'description' => 'Reply to pending client messages and flag urgent requests.'],
            ['title' => 'Create user training manual', 'description' => 'Prepare a simple manual to guide users through common tasks.'],
        ];

        $statusSequence = array_merge(
            array_fill(0, 30, 'pending'),
            array_fill(0, 42, 'in progress'),
            array_fill(0, 30, 'completed'),
            array_fill(0, 18, 'on hold')
        );
        $complexitySequence = ['very simple', 'simple', 'medium', 'complex', 'very complex'];
        $createdUsers = 0;
        $createdTasks = 0;
        $taskIndex = 0;

        foreach ($names as $userIndex => $name) {
            $email = Str::of($name)->lower()->replace(' ', '.')->ascii() . '@example.com';
            $createdAt = now()->subDays(($userIndex * 3) % 90);
            $userValues = [
                'name' => $name,
                'password' => Hash::make('password'),
                'is_admin' => false,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];

            if (Schema::hasColumn('users', 'is_active')) {
                $userValues['is_active'] = true;
            }

            $user = User::updateOrCreate(['email' => (string) $email], $userValues);
            $createdUsers++;

            for ($i = 0; $i < 4; $i++) {
                $template = $taskTemplates[($taskIndex + $i) % count($taskTemplates)];
                $statusName = $statusSequence[$taskIndex % count($statusSequence)];
                $complexityName = $complexitySequence[$taskIndex % count($complexitySequence)];
                $taskCreatedAt = now()->subDays(($taskIndex * 2) % 90)->subHours($i + 1);
                $dueAt = now()->subDays(60)->addDays(($taskIndex * 7) % 91)->setTime(17, 0);
                $updatedAt = $statusName === 'completed'
                    ? Carbon::parse($taskCreatedAt)->addDays(($taskIndex % 8) + 1)
                    : Carbon::parse($taskCreatedAt)->addDays($taskIndex % 5);

                Task::updateOrCreate(
                    ['user_id' => $user->id, 'title' => $template['title']],
                    [
                        'description' => $template['description'],
                        'status' => $statusName,
                        'status_id' => $statuses[$statusName]?->id,
                        'complexity_id' => $complexities[$complexityName]?->id,
                        'due_at' => $dueAt,
                        'completed_at' => $statusName === 'completed' ? $updatedAt : null,
                        'created_at' => $taskCreatedAt,
                        'updated_at' => $updatedAt,
                    ]
                );

                $createdTasks++;
                $taskIndex++;
            }
        }

        $this->command?->info("Demo data seeded: {$createdUsers} users and {$createdTasks} tasks.");
    }
}
