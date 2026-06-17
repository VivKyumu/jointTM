<?php

namespace Database\Seeders;

use App\Models\EmailLog;
use App\Models\Task;
use Illuminate\Database\Seeder;

class EmailLogSeeder extends Seeder
{
    public function run(): void
    {
        EmailLog::truncate();

        $tasks = Task::with('user')->latest()->take(40)->get();

        foreach ($tasks as $task) {
            if (! $task->user) {
                continue;
            }

            EmailLog::create([
                'user_id' => $task->user_id,
                'task_id' => $task->id,
                'recipient_email' => $task->user->email,
                'mail_type' => 'Task Assigned',
                'subject' => 'New Task Assigned: ' . $task->title,
                'status' => 'logged',
                'created_at' => $task->created_at,
            ]);

            if (strtolower($task->status ?? '') !== 'pending') {
                EmailLog::create([
                    'user_id' => $task->user_id,
                    'task_id' => $task->id,
                    'recipient_email' => $task->user->email,
                    'mail_type' => 'Task Status Changed',
                    'subject' => 'Task Status Updated: ' . $task->title,
                    'status' => 'logged',
                    'created_at' => $task->updated_at,
                ]);
            }

            if ($task->due_at && $task->due_at->isPast() && strtolower($task->status ?? '') !== 'completed') {
                EmailLog::create([
                    'user_id' => $task->user_id,
                    'task_id' => $task->id,
                    'recipient_email' => $task->user->email,
                    'mail_type' => 'Overdue Tasks',
                    'subject' => 'Overdue Tasks Reminder',
                    'status' => 'logged',
                    'created_at' => now()->subHours(rand(1, 72)),
                ]);
            }
        }
    }
}
