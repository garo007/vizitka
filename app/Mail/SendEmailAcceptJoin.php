<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailAcceptJoin extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $signedRoute = config('app.frontend_url') . '/generate-password?token=' . $this->user->join_token;

        return $this->subject(__('subject.join_accept'))
            ->view('emails.join-accept', ['signedRoute' => $signedRoute]);
    }
}
