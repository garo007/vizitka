<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmailUpdatedRole extends Mailable
{
    use Queueable, SerializesModels;

    protected $client;
    protected $contract;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client, $contract)
    {
        $this->client = $client;
        $this->contract = $contract;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: __('subject.updated_role'),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.updated-role',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            Attachment::fromData(fn () => $this->contract, 'contract.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
