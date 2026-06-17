<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Task $task)
    {
    }

    public function build(): self
    {
        return $this->subject('New Task Assigned: ' . $this->task->title)
            ->view('emails.task-assigned');
    }
}
