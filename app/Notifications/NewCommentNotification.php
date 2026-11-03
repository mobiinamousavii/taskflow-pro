<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification 
{

    protected $comment;
    protected $task;

    public function __construct($comment, $task)
    {
        $this->comment = $comment;
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_comment',
            'title' => 'کامنت جدید',
            'message' => "نظر جدیدی روی تسک «{$this->task->title}» ثبت شد.",
            'task_id' => $this->task->id,
            'comment_id' => $this->comment->id,
        ];
    }
}