<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class TaskOverdueMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Collection $tasks)
    {
    }

    public function build(): self
    {
        return $this->subject('Overdue Tasks Reminder')
            ->view('emails.task-overdue');
    }
}
