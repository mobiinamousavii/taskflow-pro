<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TaskDeadlineNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'deadline_approaching',
            'title' => 'هشدار ددلاین',
            'message' => "ددلاین تسک «{$this->task->title}» تا ۲۴ ساعت آینده است.",
            'task_id' => $this->task->id,
        ];
    }
}