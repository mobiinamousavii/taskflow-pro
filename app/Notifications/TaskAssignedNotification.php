<?php

namespace App\Notifications;

use App\Models\task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification  implements ShouldQueue
{
    use Queueable;
    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database' ,'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'message' => 'تسک جدید به شما اختصاص یافت:' . $this->task->title,
            'project_id' => $this->task->project_id,
            'created_at' => now(),
        ];
    }

       public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('تسک جدید به شما محول شد')
        ->line("تسک {$this->task->title} به شمامحول شد");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
