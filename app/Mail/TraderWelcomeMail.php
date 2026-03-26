<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TraderWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $traderName;
    public $dashboardUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($traderName)
    {
        $this->traderName = $traderName;
        $this->dashboardUrl = url('/trader/dashboard');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تهانينا! تم قبول حساب التاجر الخاص بك في Tulip Store',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.trader_welcome',
            with: [
                'traderName' => $this->traderName,
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
