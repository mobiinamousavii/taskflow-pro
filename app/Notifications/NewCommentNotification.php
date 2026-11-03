<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $comment;
    protected $task;

    public function __construct($comment, $task)
    {
        $this->comment = $comment;
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_comment',
            'title' => 'کامنت جدید',
            'message' => "{$this->comment->user->name} نظر  جدید روی تسک{$this->task->title} ثبت کرد.",
            'task_id' => $this->task->id,
            'comment_id' => $this->comment->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('کامنت جدید گذاشته شد')
            ->line("{$this->comment->user->name} نظر  جدید روی تسک«{$this->task->title} ثبت کرد.")
            ->line($this->comment->body);
    }
}
