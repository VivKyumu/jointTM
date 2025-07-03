<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Task;

class TaskAssigned extends Notification
{
    use Queueable;

    public $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Notification channels.
     */
    public function via($notifiable)
    {
        return ['database']; // or ['mail', 'database'] for both
    }

    /**
     * Store in database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'message' => 'You have been assigned a new task: ' . $this->task->title,
        ];
    }

    /**
     * (Optional) If using mail too
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Task Assigned')
            ->line('You have been assigned a new task: ' . $this->task->title)
            ->action('View Task', url('/tasks/' . $this->task->id));
    }
}
