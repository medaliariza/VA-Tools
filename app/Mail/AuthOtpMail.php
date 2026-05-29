<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuthOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $fullName,
        public string $purpose
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: match ($this->purpose) {
                'register' => 'Your VA Tools Registration Code',
                'verify-email' => 'Your VA Tools Email Verification Code',
                default => 'Your VA Tools Login Code',
            },
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth-otp-code',
        );
    }
}
