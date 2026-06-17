<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\Task;
use App\Models\TaskComplexity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = collect([
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'is_admin' => true],
            ['name' => 'Vivian Mbachi', 'email' => 'vivian.mbachi@example.com', 'is_admin' => false],
            ['name' => 'Eric Kyumu', 'email' => 'eric.kyumu@example.com', 'is_admin' => false],
            ['name' => 'Grace Wanjiku', 'email' => 'grace.wanjiku@example.com', 'is_admin' => false],
            ['name' => 'Brian Otieno', 'email' => 'brian.otieno@example.com', 'is_admin' => false],
            ['name' => 'Mercy Achieng', 'email' => 'mercy.achieng@example.com', 'is_admin' => false],
        ])->map(function (array $user) {
            return User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password123'),
                    'is_admin' => $user['is_admin'],
                    'is_active' => true,
                ]
            );
        });

        $statusIds = Status::pluck('id', 'name');
        $complexityIds = TaskComplexity::pluck('id', 'name');

        $tasks = [
            [
                'title' => 'Prepare weekly project progress report',
                'description' => 'Prepare and submit the weekly progress report to the project supervisor.',
                'status' => 'pending',
                'complexity' => 'Very Simple',
                'user' => 'Vivian Mbachi',
                'due_at' => now()->addDays(2),
            ],
            [
                'title' => 'Review employee task submissions',
                'description' => 'Review all completed tasks and confirm they meet the required standards.',
                'status' => 'pending',
                'complexity' => 'Very Simple',
                'user' => 'Eric Kyumu',
                'due_at' => now()->addDays(3),
            ],
            [
                'title' => 'Update client meeting notes',
                'description' => 'Update client meeting notes and share them with the team.',
                'status' => 'pending',
                'complexity' => 'Simple',
                'user' => 'Grace Wanjiku',
                'due_at' => now()->addDays(4),
            ],
            [
                'title' => 'Design task dashboard layout',
                'description' => 'Improve the task dashboard layout so managers can read key metrics quickly.',
                'status' => 'in progress',
                'complexity' => 'Simple',
                'user' => 'Brian Otieno',
                'due_at' => now()->addDays(5),
            ],
            [
                'title' => 'Test user login and logout functionality',
                'description' => 'Test the login and logout features to ensure users can access the system correctly.',
                'status' => 'in progress',
                'complexity' => 'Medium',
                'user' => 'Mercy Achieng',
                'due_at' => now()->addDays(6),
            ],
            [
                'title' => 'Fix overdue task notification issue',
                'description' => 'Investigate and fix the notification issue for tasks that pass their due date.',
                'status' => 'in progress',
                'complexity' => 'Medium',
                'user' => 'Vivian Mbachi',
                'due_at' => now()->addDays(7),
            ],
            [
                'title' => 'Create monthly performance summary',
                'description' => 'Create a monthly summary showing completed tasks, pending work, and team progress.',
                'status' => 'completed',
                'complexity' => 'Medium',
                'user' => 'Eric Kyumu',
                'due_at' => now()->subDays(1),
                'completed_at' => now()->subHours(8),
            ],
            [
                'title' => 'Assign tasks to new team members',
                'description' => 'Assign onboarding tasks to new team members and confirm ownership.',
                'status' => 'completed',
                'complexity' => 'Complex',
                'user' => 'Admin User',
                'due_at' => now()->subDays(2),
                'completed_at' => now()->subDay(),
            ],
            [
                'title' => 'Review dashboard completion rate',
                'description' => 'Review the dashboard completion rate and confirm it matches task status data.',
                'status' => 'on hold',
                'complexity' => 'Complex',
                'user' => 'Grace Wanjiku',
                'due_at' => now()->addDays(8),
            ],
            [
                'title' => 'Update task complexity categories',
                'description' => 'Update task complexity categories and make sure each task has the correct level.',
                'status' => 'on hold',
                'complexity' => 'Very Complex',
                'user' => 'Brian Otieno',
                'due_at' => now()->addDays(10),
            ],
            [
                'title' => 'Prepare department budget request',
                'description' => 'Prepare the department budget request with clear cost estimates and supporting notes.',
                'status' => 'pending',
                'complexity' => 'Medium',
                'user' => 'Mercy Achieng',
                'due_at' => now()->addDays(9),
            ],
            [
                'title' => 'Verify user account permissions',
                'description' => 'Check user roles and confirm that each staff member has the correct system access.',
                'status' => 'pending',
                'complexity' => 'Simple',
                'user' => 'Admin User',
                'due_at' => now()->addDays(4),
            ],
            [
                'title' => 'Document task approval workflow',
                'description' => 'Write a clear workflow document explaining how tasks are submitted, reviewed, and approved.',
                'status' => 'in progress',
                'complexity' => 'Complex',
                'user' => 'Vivian Mbachi',
                'due_at' => now()->addDays(12),
            ],
            [
                'title' => 'Clean duplicate task records',
                'description' => 'Review task records and remove duplicates after confirming the correct version with team leads.',
                'status' => 'in progress',
                'complexity' => 'Medium',
                'user' => 'Eric Kyumu',
                'due_at' => now()->addDays(6),
            ],
            [
                'title' => 'Confirm weekly team attendance',
                'description' => 'Confirm weekly team attendance and update the attendance summary for management review.',
                'status' => 'completed',
                'complexity' => 'Very Simple',
                'user' => 'Grace Wanjiku',
                'due_at' => now()->subDays(1),
                'completed_at' => now()->subHours(3),
            ],
            [
                'title' => 'Update project risk register',
                'description' => 'Update the project risk register with new issues, owners, and mitigation actions.',
                'status' => 'completed',
                'complexity' => 'Complex',
                'user' => 'Brian Otieno',
                'due_at' => now()->subDays(3),
                'completed_at' => now()->subDays(2),
            ],
            [
                'title' => 'Schedule stakeholder review meeting',
                'description' => 'Schedule the stakeholder review meeting and share the agenda with all participants.',
                'status' => 'on hold',
                'complexity' => 'Simple',
                'user' => 'Mercy Achieng',
                'due_at' => now()->addDays(11),
            ],
            [
                'title' => 'Audit overdue task escalation rules',
                'description' => 'Audit the rules used to escalate overdue tasks and recommend improvements.',
                'status' => 'on hold',
                'complexity' => 'Very Complex',
                'user' => 'Admin User',
                'due_at' => now()->addDays(14),
            ],
            [
                'title' => 'Create user training checklist',
                'description' => 'Create a checklist that guides new users through the main task management features.',
                'status' => 'pending',
                'complexity' => 'Simple',
                'user' => 'Vivian Mbachi',
                'due_at' => now()->addDays(5),
            ],
            [
                'title' => 'Review monthly support tickets',
                'description' => 'Review support tickets from the month and identify recurring task management issues.',
                'status' => 'in progress',
                'complexity' => 'Medium',
                'user' => 'Eric Kyumu',
                'due_at' => now()->addDays(7),
            ],
            [
                'title' => 'Archive completed project documents',
                'description' => 'Move completed project documents into the archive folder and update the records list.',
                'status' => 'completed',
                'complexity' => 'Very Simple',
                'user' => 'Grace Wanjiku',
                'due_at' => now()->subDays(2),
                'completed_at' => now()->subDay(),
            ],
            [
                'title' => 'Improve dashboard data filters',
                'description' => 'Improve dashboard filters so admins can review tasks by status, user, and complexity.',
                'status' => 'in progress',
                'complexity' => 'Complex',
                'user' => 'Brian Otieno',
                'due_at' => now()->addDays(13),
            ],
        ];

        Task::whereNotIn('title', collect($tasks)->pluck('title'))->delete();

        foreach ($tasks as $taskData) {
            $user = $users->firstWhere('name', $taskData['user']);
            $statusName = ucwords($taskData['status']);
            $statusName = $statusName === 'In Progress' ? 'In Progress' : $statusName;
            $statusName = $statusName === 'On Hold' ? 'On Hold' : $statusName;

            Task::updateOrCreate(
                ['title' => $taskData['title']],
                [
                    'description' => $taskData['description'],
                    'user_id' => $user->id,
                    'status' => $taskData['status'],
                    'status_id' => $statusIds[$statusName] ?? null,
                    'complexity_id' => $complexityIds[$taskData['complexity']] ?? null,
                    'due_at' => $taskData['due_at'],
                    'completed_at' => $taskData['completed_at'] ?? null,
                ]
            );
        }
    }
}
