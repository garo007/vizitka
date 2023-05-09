<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailRequestAdmin extends Mailable
{
    use Queueable, SerializesModels;

    private $client;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('subject.send_request', ['company_name' => $this->client->company_name]))
            ->view('emails.send-request', ['client' => $this->client]);
    }
}
