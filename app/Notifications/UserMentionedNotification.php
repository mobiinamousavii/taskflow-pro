<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserMentionedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $mentionedBy;
    protected $task;

    public function __construct($mentionedBy,$task)
    {
        $this->mentionedBy =$mentionedBy;
        $this->task =$task;
    }

    public function via($notifiable)
    {
        return ['database' , 'mail'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'mention',
            'title' => 'منشن جدید',
            'message' => "شما توسط {$this->mentionedBy->name} در تسک «{$this->task->title}» منشن شدید.",
            'task_id' => $this->task->id,
        ];
    }

         public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('منشن جدید در تسک')
        ->line("شما توسط {$this->mentionedBy->name} در تسک {$this->task->title} منشن شدید");
    }
}
