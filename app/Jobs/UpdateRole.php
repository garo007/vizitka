<?php

namespace App\Jobs;

use App\Mail\SendEmailUpdatedRole;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class UpdateRole implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;
    protected $fileName;
    /**
     * Create a new job instance.
     *
     * @param $client
     * @param $fileName
     */
    public function __construct($client, $fileName)
    {
        $this->client = $client;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $contract = File::get(storage_path('app/public/contracts/' . $this->fileName));

        Mail::to($this->client->email)->send(new SendEmailUpdatedRole($this->client, $contract));
    }
}
