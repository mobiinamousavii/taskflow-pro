<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        return ['database', 'mail'];
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

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('مهلت اتمام این پروژه تا 24 ساعت اینده است')
            ->line("مهلت اتمام  {$this->task->deadline} تا 24 ساعت اینده است");
    }
}
