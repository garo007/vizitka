<?php

namespace App\Jobs;

use App\Mail\SendEmailAcceptJoin;
use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AcceptJoinJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clients;

    /**
     * Create a new job instance.
     *
     * @param $clientsIds
     */
    public function __construct($clientsIds)
    {
        $this->clients = Client::find($clientsIds);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->clients as $clients) {
            Mail::to($clients->email)->send(new SendEmailAcceptJoin($clients));
        }
    }
}
