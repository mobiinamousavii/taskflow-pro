<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskOverdueNotification extends Notification implements ShouldQueue
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
            'type'    => 'task_overdue',
            'title'   => 'تسک منقضی شد',
            'message' => "مهلت تسک «{$this->task->title}» تمام شده. اول مهلت را تمدید کنید.",
            'task_id' => $this->task->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('مهلت تسک تمام شد')
            ->line("مهلت تسک «{$this->task->title}» تمام شده و هنوز تکمیل نشده.")
            ->line('برای ادامه‌ی کار، اول مهلت را به تاریخ آینده تغییر دهید.');
    }
}