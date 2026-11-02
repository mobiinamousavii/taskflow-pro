<?php

namespace App\Mail;

use App\Models\workspace_invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkspaceInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $invitation;

    /**
     * Create a new message instance.
     */
    public function __construct(workspace_invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // تنظیم عنوان و فرستنده
        return new Envelope(
            subject: 'دعوت نامه به ورک اسپیس' ,
        );
    }

    /**
     * Get the message content definition.
     */
 public function content(): Content
{
    return new Content(
        view: 'emails.workspace',
        with: [
            'workspacename' => $this->invitation->workspace->name,
            'userrole'=>$this->invitation->role->name,
            'token'=>$this->invitation->token,
        ],
    );
}

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
