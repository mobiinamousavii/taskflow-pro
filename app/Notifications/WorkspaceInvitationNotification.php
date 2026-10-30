<?php

namespace App\Notifications;

use App\Models\workspace_invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\WorkspaceInvitation;

class WorkspaceInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invitation;

    public function __construct(workspace_invitation $invitation)
    {
        $this->invitation =$invitation;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('شما برای عضویت در ورک‌اسپیس دعوت شدید.')
                    ->action('پذیرش دعوت', url('/invitations/' . $this->invitation->token . '/accept'));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'workspace_invitation',
            'title' => 'دعوت‌نامه جدید',
            'message' => "شما به ورک‌اسپیس «{$this->invitation->workspace->name}» دعوت شدید.",
            'workspace_id' => $this->invitation->workspace->id,
        ];
    }
}