<?php

namespace App\Services\Client;

use App\Mail\SendEmailRequestAdmin;
use Exception;
use Illuminate\Support\Facades\Mail;

class Send
{
    protected $hasError = [];

    public function __invoke($client)
    {
        $client = auth()->guard('client')->user();
        $this->checkFilesExists($client);
        $this->checkDetailsExists($client);

        if ($this->hasError) {
            throw new Exception(__('exception.client.fill_all_fields'));
        }

        Mail::to(config('app.admin_email'))->send(new SendEmailRequestAdmin($client));

        return $client;
    }

    protected function checkFilesExists($client)
    {
        if (!$client->getStateCertificateOfCompanyAttribute()) {
            $this->hasError = true;
        }

        if (!$client->getLogoAttribute()) {
            $this->hasError = true;
        }

        if (!$client->getPrivacyPolicyAttribute()) {
            $this->hasError = true;
        }
    }

    protected function checkDetailsExists($client)
    {
        if (!$client->description) {
            $this->hasError = true;
        }
    }
}
