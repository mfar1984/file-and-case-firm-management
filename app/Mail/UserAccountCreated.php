<?php

namespace App\Mail;

use App\Services\EmailConfigurationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $userData;
    public $accountType;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($userData, $accountType)
    {
        $this->userData = $userData;
        $this->accountType = $accountType;
        $this->loginUrl = url('/login');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->accountType === 'client' ? 'Client Account Created' : 'Partner Account Created';
        
        // Get email settings from database
        $emailSettings = EmailConfigurationService::getEmailSettings();
        
        return new Envelope(
            subject: $subject,
            from: new Address($emailSettings->from_email, $emailSettings->from_name),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user-account-created',
            with: [
                'userData' => $this->userData,
                'accountType' => $this->accountType,
                'loginUrl' => $this->loginUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
