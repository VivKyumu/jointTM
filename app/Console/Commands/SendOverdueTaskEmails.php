<?php

namespace App\Console\Commands;

use App\Mail\TaskOverdueMail;
use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendOverdueTaskEmails extends Command
{
    protected $signature = 'tasks:send-overdue-emails';

    protected $description = 'Send daily overdue task reminder emails to assigned users.';

    public function handle(): int
    {
        $users = User::whereHas('tasks', function ($query) {
            $query->whereNotNull('due_at')
                ->whereDate('due_at', '<', today())
                ->whereRaw('LOWER(status) != ?', ['completed']);
        })->with(['tasks' => function ($query) {
            $query->whereNotNull('due_at')
                ->whereDate('due_at', '<', today())
                ->whereRaw('LOWER(status) != ?', ['completed'])
                ->orderBy('due_at');
        }])->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new TaskOverdueMail($user, $user->tasks));
            EmailLog::create([
                'user_id' => $user->id,
                'recipient_email' => $user->email,
                'mail_type' => 'Overdue Tasks',
                'subject' => 'Overdue Tasks Reminder',
                'status' => 'logged',
            ]);
        }

        $this->info("Sent overdue task emails to {$users->count()} user(s).");

        return self::SUCCESS;
    }
}
