<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkspaceInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $workspacename,
        public string $userrole,
    ) {
        // متغیرهایی که میخوام به ویو بفرستم
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // تنظیم عنوان و فرستنده
        return new Envelope(
            subject: 'دعوت نامه به ورک اسپیس' . $this->workspacename,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // مشخص کردن ویو بلید ایمیل
        return new Content(
            view: 'emails.workspace',
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
